<?php
// ============================================================
// CodeQuest — Admin: อนุมัติเติมเครดิต
// เข้าได้เฉพาะ admin (is_admin = 1 ใน DB)
// ============================================================
require_once dirname(__DIR__) . '/api/config.php';
setCorsHeaders();

$user = getAuthUser();
if (!$user || !$user['is_admin']) {
    http_response_code(403);
    die('<!DOCTYPE html><html><body style="font-family:sans-serif;background:#0f0e17;color:#fff;padding:40px;text-align:center"><h2>403 — ไม่มีสิทธิ์เข้าถึง</h2><p>ต้องเป็น admin เท่านั้น</p></body></html>');
}

$db = getDB();

// Handle inline approve/reject via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reqId = (int)($_POST['request_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    $note   = substr($_POST['admin_note'] ?? '', 0, 500);

    if ($reqId && $action === 'approve') {
        $stmt = $db->prepare('SELECT * FROM credit_requests WHERE id = ? AND status = "pending"');
        $stmt->execute([$reqId]);
        $req = $stmt->fetch();
        if ($req) {
            $db->beginTransaction();
            $db->prepare('UPDATE credit_requests SET status="approved",processed_at=NOW(),admin_note=? WHERE id=?')->execute([$note,$reqId]);
            $db->prepare('UPDATE users SET credits=credits+? WHERE id=?')->execute([$req['credits_given'],$req['user_id']]);
            $db->commit();

            // แจ้งนักเรียน
            $s = $db->prepare('SELECT email,display_name FROM users WHERE id=?');
            $s->execute([$req['user_id']]);
            $student = $s->fetch();
            if ($student) {
                $subj = "[CodeQuest] เพิ่ม {$req['credits_given']} เครดิตแล้ว!";
                $msg = "สวัสดีคุณ {$student['display_name']},\n\nเราเพิ่ม {$req['credits_given']} เครดิตให้คุณแล้ว\n\n— ทีม CodeQuest";
                @mail($student['email'], '=?UTF-8?B?'.base64_encode($subj).'?=', $msg, "From: noreply@codequest.online\r\nContent-Type: text/plain; charset=utf-8\r\n");
            }
        }
    } elseif ($reqId && $action === 'reject') {
        $db->prepare('UPDATE credit_requests SET status="rejected",processed_at=NOW(),admin_note=? WHERE id=? AND status="pending"')->execute([$note,$reqId]);
    }
    header('Location: credits.php');
    exit;
}

// Load pending + recent
$pending = $db->query(
    'SELECT cr.*,u.email,u.display_name,u.credits AS current_credits
     FROM credit_requests cr JOIN users u ON u.id=cr.user_id
     WHERE cr.status="pending" ORDER BY cr.requested_at ASC'
)->fetchAll();

$recent = $db->query(
    'SELECT cr.*,u.email,u.display_name
     FROM credit_requests cr JOIN users u ON u.id=cr.user_id
     WHERE cr.status != "pending" ORDER BY cr.processed_at DESC LIMIT 20'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Admin: อนุมัติเครดิต</title>
<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;600;700&family=Fira+Code:wght@400&display=swap" rel="stylesheet">
<style>
:root{--bg:#0f0e17;--card:#1a1932;--accent:#a855f7;--text:#fffffe;--text-dim:#94a1b2;--success:#10b981;--error:#ef4444;--border:rgba(255,255,255,0.07);}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Prompt',sans-serif;background:var(--bg);color:var(--text);padding:24px;}
h1{font-size:1.4rem;font-weight:800;margin-bottom:4px;}
.sub{color:var(--text-dim);font-size:0.85rem;margin-bottom:28px;}
.badge-pending{background:rgba(255,230,109,0.12);color:#FFE66D;padding:2px 8px;border-radius:6px;font-size:0.75rem;font-weight:700;}
table{width:100%;border-collapse:collapse;background:var(--card);border-radius:14px;overflow:hidden;margin-bottom:32px;}
th{background:rgba(255,255,255,0.04);padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-dim);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;}
td{padding:12px 16px;font-size:0.85rem;border-top:1px solid var(--border);}
.badge{padding:3px 10px;border-radius:6px;font-size:0.72rem;font-weight:700;}
.badge-app{background:rgba(16,185,129,0.15);color:var(--success);}
.badge-rej{background:rgba(239,68,68,0.12);color:var(--error);}
.badge-wait{background:rgba(255,230,109,0.12);color:#FFE66D;}
form.inline{display:flex;gap:6px;align-items:flex-start;flex-direction:column;}
form.inline input[type=text]{background:rgba(255,255,255,0.04);border:1px solid var(--border);color:var(--text);font-family:'Prompt',sans-serif;font-size:0.78rem;padding:5px 10px;border-radius:6px;width:180px;}
.btn-approve{background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);color:var(--success);padding:6px 14px;border-radius:8px;cursor:pointer;font-family:'Prompt',sans-serif;font-size:0.8rem;font-weight:600;}
.btn-reject{background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:var(--error);padding:6px 14px;border-radius:8px;cursor:pointer;font-family:'Prompt',sans-serif;font-size:0.8rem;font-weight:600;}
.empty{text-align:center;padding:40px;color:var(--text-dim);}
</style>
</head>
<body>
<h1>🔑 Admin — อนุมัติเติมเครดิต</h1>
<div class="sub">ยินดีต้อนรับ <?=htmlspecialchars($user['display_name'])?> | <a href="../index.php" style="color:var(--accent);">← กลับหน้าหลัก</a></div>

<h2 style="font-size:1rem;margin-bottom:12px;">⏳ รอการอนุมัติ <span class="badge-pending"><?=count($pending)?> รายการ</span></h2>
<table>
<thead><tr>
  <th>#ID</th><th>นักเรียน</th><th>อีเมล</th><th>จำนวน</th><th>เครดิตปัจจุบัน</th><th>หมายเหตุ</th><th>วันที่</th><th>ดำเนินการ</th>
</tr></thead>
<tbody>
<?php if (!$pending): ?>
<tr><td colspan="8" class="empty">ไม่มีรายการรอ 🎉</td></tr>
<?php else: foreach($pending as $r): ?>
<tr>
  <td style="font-family:'Fira Code',monospace;color:var(--text-dim);">#<?=$r['id']?></td>
  <td><?=htmlspecialchars($r['display_name'])?></td>
  <td style="font-size:0.78rem;color:var(--text-dim);"><?=htmlspecialchars($r['email'])?></td>
  <td><strong style="color:#FFE66D;">฿<?=$r['amount_thb']?></strong> → <?=$r['credits_given']?> เครดิต</td>
  <td style="font-family:'Fira Code',monospace;"><?=$r['current_credits']?> cr</td>
  <td style="max-width:160px;font-size:0.78rem;color:var(--text-dim);"><?=htmlspecialchars($r['slip_note']?:'-')?></td>
  <td style="font-size:0.75rem;color:var(--text-dim);"><?=date('d/m H:i',strtotime($r['requested_at']))?></td>
  <td>
    <form method="POST" class="inline" style="flex-direction:row;gap:6px;">
      <input type="hidden" name="request_id" value="<?=$r['id']?>">
      <input type="hidden" name="action" value="approve">
      <input type="text" name="admin_note" placeholder="หมายเหตุ (ถ้ามี)">
      <button type="submit" class="btn-approve">✅ อนุมัติ</button>
    </form>
    <form method="POST" class="inline" style="flex-direction:row;gap:6px;margin-top:4px;">
      <input type="hidden" name="request_id" value="<?=$r['id']?>">
      <input type="hidden" name="action" value="reject">
      <input type="text" name="admin_note" placeholder="เหตุผลที่ปฏิเสธ">
      <button type="submit" class="btn-reject">❌ ปฏิเสธ</button>
    </form>
  </td>
</tr>
<?php endforeach; endif; ?>
</tbody>
</table>

<h2 style="font-size:1rem;margin-bottom:12px;">📋 ประวัติล่าสุด (20 รายการ)</h2>
<table>
<thead><tr><th>#ID</th><th>นักเรียน</th><th>จำนวน</th><th>สถานะ</th><th>หมายเหตุ admin</th><th>วันที่ดำเนินการ</th></tr></thead>
<tbody>
<?php if (!$recent): ?>
<tr><td colspan="6" class="empty">ยังไม่มีประวัติ</td></tr>
<?php else: foreach($recent as $r): ?>
<tr>
  <td style="font-family:'Fira Code',monospace;color:var(--text-dim);">#<?=$r['id']?></td>
  <td><?=htmlspecialchars($r['display_name'])?></td>
  <td>฿<?=$r['amount_thb']?> / <?=$r['credits_given']?> cr</td>
  <td><span class="badge <?=$r['status']==='approved'?'badge-app':($r['status']==='rejected'?'badge-rej':'badge-wait')?>"><?=$r['status']?></span></td>
  <td style="font-size:0.78rem;color:var(--text-dim);"><?=htmlspecialchars($r['admin_note']?:'-')?></td>
  <td style="font-size:0.75rem;color:var(--text-dim);"><?=$r['processed_at']?date('d/m H:i',strtotime($r['processed_at'])):'-'?></td>
</tr>
<?php endforeach; endif; ?>
</tbody>
</table>
</body>
</html>
