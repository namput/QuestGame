<?php
// ============================================================
// CodeQuest — Database & App Config
// ⚠️  ไฟล์นี้ถูกป้องกันโดย .htaccess — ห้าม expose ออกสาธารณะ
// ============================================================

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'neuatech_gamecode');
define('DB_USER', 'neuatech_gamecode');
define('DB_PASS', 'utcThbtHw8SP96q');
define('DB_CHARSET', 'utf8mb4');

// App settings
define('APP_NAME', 'CodeQuest');
define('SESSION_LIFETIME', 30 * 24 * 3600);  // 30 วัน (วินาที)
define('TOKEN_LENGTH', 64);

// CORS — ใส่ domain จริงตอน production
define('ALLOWED_ORIGIN', '*');   // หรือ 'https://ผจญภัยแดนโค้ด.online'

// ============================================================
// Global exception handler — ให้ error ทุกชนิด return JSON
// ============================================================
set_exception_handler(function (Throwable $e) {
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
    }
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    exit;
});

// ============================================================
// Database connection (PDO singleton)
// ============================================================
function getDB(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
    );
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }
    return $pdo;
}

// ============================================================
// Response helpers
// ============================================================
function jsonResponse(mixed $data, int $status = 200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function errorResponse(string $message, int $status = 400): never {
    jsonResponse(['error' => $message], $status);
}

// ============================================================
// CORS headers — เรียกก่อน output ทุกครั้ง
// ============================================================
function setCorsHeaders(): void {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
    header('Access-Control-Allow-Origin: ' . (ALLOWED_ORIGIN === '*' ? $origin : ALLOWED_ORIGIN));
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-Token');
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
}

// ============================================================
// Auth token helpers
// ============================================================
function generateToken(int $length = TOKEN_LENGTH): string {
    return bin2hex(random_bytes($length / 2));
}

// ดึง user จาก Bearer token ใน Authorization header หรือ cookie
function getAuthUser(): ?array {
    $token = null;

    // 1. Authorization: Bearer <token>
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (str_starts_with($authHeader, 'Bearer ')) {
        $token = substr($authHeader, 7);
    }

    // 2. Cookie: cq_session=<token>
    if (!$token && !empty($_COOKIE['cq_session'])) {
        $token = $_COOKIE['cq_session'];
    }

    if (!$token) return null;

    $db  = getDB();
    $sql = 'SELECT u.id, u.email, u.display_name, u.avatar_url, u.school,
                   u.total_xp, u.is_public, u.is_admin
            FROM sessions s
            JOIN users u ON u.id = s.user_id
            WHERE s.token = ? AND s.expires_at > NOW()
            LIMIT 1';
    $stmt = $db->prepare($sql);
    $stmt->execute([$token]);
    return $stmt->fetch() ?: null;
}

function requireAuth(): array {
    $user = getAuthUser();
    if (!$user) errorResponse('กรุณาเข้าสู่ระบบ', 401);
    return $user;
}

// ============================================================
// Request body helper
// ============================================================
function getBody(): array {
    $raw = file_get_contents('php://input');
    return json_decode($raw, true) ?? [];
}
