<?php
// ============================================================
// CodeQuest — Auth API
// POST /api/auth.php?action=register|login|logout|me|update_profile
// ============================================================

require_once __DIR__ . '/config.php';

setCorsHeaders();

$action = $_GET['action'] ?? '';

match ($action) {
    'register'       => handleRegister(),
    'login'          => handleLogin(),
    'logout'         => handleLogout(),
    'me'             => handleMe(),
    'update_profile' => handleUpdateProfile(),
    default          => errorResponse('action ไม่ถูกต้อง', 404),
};

// ============================================================
// Helpers
// ============================================================

/**
 * Rate-limit login attempts: max 5 per IP per 15 minutes.
 * Uses a JSON file in sys_get_temp_dir() — no extra DB table needed.
 */
function checkLoginRateLimit(string $ip): void {
    $file    = sys_get_temp_dir() . '/cq_rl_' . md5($ip) . '.json';
    $window  = 15 * 60;   // 15 minutes in seconds
    $maxTries = 5;
    $now     = time();

    $data = ['count' => 0, 'since' => $now];
    if (file_exists($file)) {
        $raw = @file_get_contents($file);
        if ($raw) {
            $parsed = json_decode($raw, true);
            if ($parsed && ($now - $parsed['since']) < $window) {
                $data = $parsed;
            }
        }
    }

    if ($data['count'] >= $maxTries) {
        $remaining = (int) ceil(($data['since'] + $window - $now) / 60);
        errorResponse("พยายามเข้าสู่ระบบบ่อยเกินไป กรุณารอ {$remaining} นาที", 429);
    }
}

function recordFailedLogin(string $ip): void {
    $file   = sys_get_temp_dir() . '/cq_rl_' . md5($ip) . '.json';
    $window = 15 * 60;
    $now    = time();

    $data = ['count' => 0, 'since' => $now];
    if (file_exists($file)) {
        $raw = @file_get_contents($file);
        if ($raw) {
            $parsed = json_decode($raw, true);
            if ($parsed && ($now - $parsed['since']) < $window) {
                $data = $parsed;
            }
        }
    }

    $data['count']++;
    @file_put_contents($file, json_encode($data), LOCK_EX);
}

function clearLoginRateLimit(string $ip): void {
    $file = sys_get_temp_dir() . '/cq_rl_' . md5($ip) . '.json';
    @unlink($file);
}

function createSession(int $userId): string {
    $db      = getDB();
    $token   = generateToken();
    $expires = date('Y-m-d H:i:s', time() + SESSION_LIFETIME);

    $stmt = $db->prepare(
        'INSERT INTO sessions (token, user_id, expires_at) VALUES (?, ?, ?)'
    );
    $stmt->execute([$token, $userId, $expires]);
    return $token;
}

function setSessionCookie(string $token): void {
    setcookie(
        'cq_session',
        $token,
        [
            'expires'  => time() + SESSION_LIFETIME,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure'   => false,   // shared hosting may lack forced HTTPS
        ]
    );
}

function formatUser(array $row): array {
    return [
        'id'           => (int) $row['id'],
        'email'        => $row['email'],
        'display_name' => $row['display_name'],
        'avatar_url'   => $row['avatar_url'] ?? '',
        'school'       => $row['school'] ?? '',
        'total_xp'     => (int) $row['total_xp'],
        'is_public'    => (bool) ($row['is_public'] ?? 1),
    ];
}

// ============================================================
// Handlers
// ============================================================

function handleRegister(): never {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        errorResponse('Method not allowed', 405);
    }

    $body        = getBody();
    $email       = trim($body['email'] ?? '');
    $password    = $body['password'] ?? '';
    $displayName = trim($body['display_name'] ?? '');

    // Validate required fields
    if ($email === '' || $password === '' || $displayName === '') {
        errorResponse('กรุณากรอกข้อมูลให้ครบ');
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        errorResponse('รูปแบบอีเมลไม่ถูกต้อง');
    }

    // Validate password length
    if (strlen($password) < 6) {
        errorResponse('รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร');
    }

    // Validate display_name length
    if (mb_strlen($displayName) > 100) {
        errorResponse('ชื่อแสดงยาวเกินไป (สูงสุด 100 ตัวอักษร)');
    }

    $db = getDB();

    // Check duplicate email
    $stmt = $db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        errorResponse('อีเมลนี้ถูกใช้แล้ว');
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $db->prepare(
        'INSERT INTO users (email, password_hash, display_name, last_login_at)
         VALUES (?, ?, ?, NOW())'
    );
    $stmt->execute([$email, $hash, $displayName]);
    $userId = (int) $db->lastInsertId();

    $token = createSession($userId);
    setSessionCookie($token);

    $stmt = $db->prepare(
        'SELECT id, email, display_name, avatar_url, school, total_xp, is_public
         FROM users WHERE id = ? LIMIT 1'
    );
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    jsonResponse([
        'success' => true,
        'token'   => $token,
        'user'    => formatUser($user),
    ], 201);
}

function handleLogin(): never {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        errorResponse('Method not allowed', 405);
    }

    $ip   = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    checkLoginRateLimit($ip);

    $body     = getBody();
    $email    = trim($body['email'] ?? '');
    $password = $body['password'] ?? '';

    if ($email === '' || $password === '') {
        errorResponse('กรุณากรอกข้อมูลให้ครบ');
    }

    $db   = getDB();
    $stmt = $db->prepare(
        'SELECT id, email, password_hash, display_name, avatar_url, school, total_xp, is_public
         FROM users WHERE email = ? LIMIT 1'
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        recordFailedLogin($ip);
        errorResponse('อีเมลหรือรหัสผ่านไม่ถูกต้อง', 401);
    }

    // Successful login — clear rate-limit counter and update last_login_at
    clearLoginRateLimit($ip);

    $db->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?')
       ->execute([$user['id']]);

    $token = createSession((int) $user['id']);
    setSessionCookie($token);

    jsonResponse([
        'success' => true,
        'token'   => $token,
        'user'    => formatUser($user),
    ]);
}

function handleLogout(): never {
    // Accept both GET and POST
    $token = null;

    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (str_starts_with($authHeader, 'Bearer ')) {
        $token = substr($authHeader, 7);
    }
    if (!$token && !empty($_COOKIE['cq_session'])) {
        $token = $_COOKIE['cq_session'];
    }

    if ($token) {
        $db = getDB();
        $db->prepare('DELETE FROM sessions WHERE token = ?')->execute([$token]);
    }

    // Clear cookie
    setcookie('cq_session', '', [
        'expires'  => time() - 3600,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => false,
    ]);

    jsonResponse(['success' => true, 'message' => 'ออกจากระบบแล้ว']);
}

function handleMe(): never {
    $user = requireAuth();
    jsonResponse(['success' => true, 'user' => formatUser($user)]);
}

function handleUpdateProfile(): never {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        errorResponse('Method not allowed', 405);
    }

    $user = requireAuth();
    $body = getBody();

    $displayName = isset($body['display_name']) ? trim($body['display_name']) : null;
    $school      = isset($body['school'])       ? trim($body['school'])       : null;
    $avatarUrl   = isset($body['avatar_url'])   ? trim($body['avatar_url'])   : null;
    $isPublic    = isset($body['is_public'])    ? (bool) $body['is_public']   : null;

    // Build dynamic SET clause
    $sets   = [];
    $params = [];

    if ($displayName !== null) {
        if ($displayName === '') errorResponse('ชื่อแสดงต้องไม่ว่าง');
        if (mb_strlen($displayName) > 100) errorResponse('ชื่อแสดงยาวเกินไป (สูงสุด 100 ตัวอักษร)');
        $sets[]   = 'display_name = ?';
        $params[] = $displayName;
    }
    if ($school !== null) {
        $sets[]   = 'school = ?';
        $params[] = mb_substr($school, 0, 200);
    }
    if ($avatarUrl !== null) {
        if ($avatarUrl !== '' && !filter_var($avatarUrl, FILTER_VALIDATE_URL)) {
            errorResponse('URL รูปโปรไฟล์ไม่ถูกต้อง');
        }
        $sets[]   = 'avatar_url = ?';
        $params[] = mb_substr($avatarUrl, 0, 500);
    }
    if ($isPublic !== null) {
        $sets[]   = 'is_public = ?';
        $params[] = $isPublic ? 1 : 0;
    }

    if (empty($sets)) {
        errorResponse('ไม่มีข้อมูลที่จะอัปเดต');
    }

    $params[] = $user['id'];
    $db       = getDB();
    $db->prepare('UPDATE users SET ' . implode(', ', $sets) . ' WHERE id = ?')
       ->execute($params);

    // Return updated user
    $stmt = $db->prepare(
        'SELECT id, email, display_name, avatar_url, school, total_xp, is_public
         FROM users WHERE id = ? LIMIT 1'
    );
    $stmt->execute([$user['id']]);
    $updated = $stmt->fetch();

    jsonResponse(['success' => true, 'user' => formatUser($updated)]);
}
