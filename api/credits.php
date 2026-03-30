<?php
// ============================================================
// CodeQuest — Credits API
// Actions: balance | request | approve | reject | pending
// ============================================================
require_once __DIR__ . '/config.php';
setCorsHeaders();

$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// ─── GET /api/credits.php?action=balance ─────────────────────
if ($method === 'GET' && $action === 'balance') {
    $user = requireAuth();
    $stmt = $db->prepare('SELECT credits FROM users WHERE id = ?');
    $stmt->execute([$user['id']]);
    $row = $stmt->fetch();
    jsonResponse(['credits' => (int)($row['credits'] ?? 0)]);
}

// ─── GET /api/credits.php?action=pending (admin) ─────────────
if ($method === 'GET' && $action === 'pending') {
    $user = requireAuth();
    if (!$user['is_admin']) errorResponse('Forbidden', 403);

    $stmt = $db->prepare(
        'SELECT cr.*, u.email, u.display_name
         FROM credit_requests cr
         JOIN users u ON u.id = cr.user_id
         WHERE cr.status = "pending"
         ORDER BY cr.requested_at ASC'
    );
    $stmt->execute();
    jsonResponse(['data' => $stmt->fetchAll()]);
}

// ─── POST /api/credits.php?action=request ────────────────────
if ($method === 'POST' && $action === 'request') {
    $user = requireAuth();
    $body = getBody();

    $amount_thb = (int)($body['amount_thb'] ?? 0);
    $slip_note  = trim(substr($body['slip_note'] ?? '', 0, 500));

    if ($amount_thb < 20 || $amount_thb > 10000) {
        errorResponse('จำนวนเงินไม่ถูกต้อง (20–10,000 บาท)');
    }

    // 1 บาท = 1 เครดิต
    $credits_given = $amount_thb;

    // บันทึก request
    $stmt = $db->prepare(
        'INSERT INTO credit_requests (user_id, amount_thb, credits_given, slip_note)
         VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([$user['id'], $amount_thb, $credits_given, $slip_note]);
    $reqId = $db->lastInsertId();

    // ส่งอีเมลแจ้ง admin
    $adminEmail = 'namput2557@gmail.com';
    $subject    = "[CodeQuest] เติมเครดิต {$amount_thb} บาท — รอตรวจสอบ #{$reqId}";
    $body_text  = "มีนักเรียนขอเติมเครดิต:\n\n"
        . "Request ID : #{$reqId}\n"
        . "ชื่อ       : {$user['display_name']}\n"
        . "อีเมล      : {$user['email']}\n"
        . "จำนวนเงิน  : {$amount_thb} บาท\n"
        . "เครดิตที่จะได้: {$credits_given} เครดิต\n"
        . "หมายเหตุ Slip : {$slip_note}\n\n"
        . "กรุณาตรวจสอบ slip แล้วอนุมัติที่:\n"
        . "https://ผจญภัยแดนโค้ด.online/admin/credits.php\n\n"
        . "— ระบบ CodeQuest";

    $headers = "From: noreply@codequest.online\r\n"
             . "Reply-To: {$user['email']}\r\n"
             . "Content-Type: text/plain; charset=utf-8\r\n"
             . "X-Mailer: PHP/" . phpversion();

    @mail($adminEmail, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body_text, $headers);

    jsonResponse([
        'success'       => true,
        'request_id'    => (int)$reqId,
        'amount_thb'    => $amount_thb,
        'credits_given' => $credits_given,
        'message'       => 'ส่งคำขอเรียบร้อยแล้ว admin จะตรวจสอบและเพิ่มเครดิตให้ภายใน 24 ชั่วโมง',
    ]);
}

// ─── POST /api/credits.php?action=approve ────────────────────
if ($method === 'POST' && $action === 'approve') {
    $user = requireAuth();
    if (!$user['is_admin']) errorResponse('Forbidden', 403);

    $body = getBody();
    $reqId = (int)($body['request_id'] ?? 0);
    if (!$reqId) errorResponse('request_id จำเป็น');

    // ดึง request
    $stmt = $db->prepare('SELECT * FROM credit_requests WHERE id = ? AND status = "pending"');
    $stmt->execute([$reqId]);
    $req = $stmt->fetch();
    if (!$req) errorResponse('ไม่พบคำขอ หรือถูกดำเนินการแล้ว');

    // อนุมัติ + เพิ่มเครดิต (atomic)
    $db->beginTransaction();
    try {
        $db->prepare('UPDATE credit_requests SET status="approved", processed_at=NOW(), admin_note=? WHERE id=?')
           ->execute([$body['admin_note'] ?? '', $reqId]);

        $db->prepare('UPDATE users SET credits = credits + ? WHERE id = ?')
           ->execute([$req['credits_given'], $req['user_id']]);

        $db->commit();
    } catch (Exception $e) {
        $db->rollBack();
        errorResponse('เกิดข้อผิดพลาด: ' . $e->getMessage(), 500);
    }

    // แจ้งนักเรียน
    $uStmt = $db->prepare('SELECT email, display_name FROM users WHERE id = ?');
    $uStmt->execute([$req['user_id']]);
    $student = $uStmt->fetch();
    if ($student) {
        $subj = "[CodeQuest] เพิ่มเครดิต {$req['credits_given']} เครดิตแล้ว!";
        $msg  = "สวัสดีคุณ {$student['display_name']},\n\n"
              . "เราได้อนุมัติและเพิ่ม {$req['credits_given']} เครดิตให้คุณแล้ว\n"
              . "(จากการเติมเงิน {$req['amount_thb']} บาท)\n\n"
              . "ตอนนี้คุณสามารถใช้ AI Tutor ได้เลย!\n\n"
              . "— ทีม CodeQuest";
        $hdrs = "From: noreply@codequest.online\r\nContent-Type: text/plain; charset=utf-8\r\n";
        @mail($student['email'], '=?UTF-8?B?' . base64_encode($subj) . '?=', $msg, $hdrs);
    }

    jsonResponse(['success' => true, 'message' => 'อนุมัติเรียบร้อย']);
}

// ─── POST /api/credits.php?action=reject ─────────────────────
if ($method === 'POST' && $action === 'reject') {
    $user = requireAuth();
    if (!$user['is_admin']) errorResponse('Forbidden', 403);

    $body  = getBody();
    $reqId = (int)($body['request_id'] ?? 0);
    $note  = substr($body['admin_note'] ?? '', 0, 500);
    if (!$reqId) errorResponse('request_id จำเป็น');

    $db->prepare('UPDATE credit_requests SET status="rejected", processed_at=NOW(), admin_note=? WHERE id=? AND status="pending"')
       ->execute([$note, $reqId]);

    jsonResponse(['success' => true, 'message' => 'ปฏิเสธคำขอแล้ว']);
}

errorResponse('action ไม่ถูกต้อง');
