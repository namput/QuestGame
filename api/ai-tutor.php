<?php
// ============================================================
// CodeQuest — AI Tutor API
// POST /api/ai-tutor.php
// Requires auth. Streams Anthropic Claude responses via SSE.
// ============================================================

require_once __DIR__ . '/config.php';

setCorsHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Method not allowed', 405);
}

$user = requireAuth();

// ============================================================
// Credit check: ต้องมีเครดิตถึงจะใช้ได้
// ============================================================
$db = getDB();
$creditRow = $db->prepare('SELECT credits FROM users WHERE id = ?');
$creditRow->execute([$user['id']]);
$currentCredits = (int)($creditRow->fetch()['credits'] ?? 0);

if ($currentCredits <= 0) {
    errorResponse('เครดิตไม่พอ กรุณาเติมเครดิตก่อน', 402);
}

// ============================================================
// Rate limit: 20 requests per user per hour
// Uses a JSON file per user in the system temp directory.
// ============================================================
checkAiRateLimit((int) $user['id']);

// ============================================================
// Parse request body
// ============================================================
$body        = getBody();
$questType   = trim($body['questType']   ?? '');
$levelNum    = (int) ($body['levelNum']  ?? 0);
$levelTitle  = trim($body['levelTitle']  ?? '');
$challenge   = trim($body['challenge']   ?? '');
$studentCode = trim($body['studentCode'] ?? '');
$testResults = $body['testResults'] ?? [];
$messages    = $body['messages']    ?? [];

if ($questType === '') {
    errorResponse('กรุณาระบุ questType');
}
if (!is_array($messages)) {
    $messages = [];
}

// Sanitise conversation history — keep only user/assistant roles
$history = [];
foreach ($messages as $msg) {
    $role    = $msg['role']    ?? '';
    $content = trim($msg['content'] ?? '');
    if (in_array($role, ['user', 'assistant'], true) && $content !== '') {
        $history[] = ['role' => $role, 'content' => $content];
    }
}

// ============================================================
// Build system prompt (Thai)
// ============================================================
$testSummary = buildTestSummary($testResults);

$systemPrompt = <<<PROMPT
คุณคือ "ผู้ช่วยสอน CodeQuest" — ระบบ AI ติวเตอร์สำหรับเกมการเรียนเขียนโปรแกรม CodeQuest
คุณสอนด้วยวิธี Socratic (ตั้งคำถามนำเพื่อให้นักเรียนคิดเอง) ไม่ให้คำตอบตรงๆ

=== บริบทปัจจุบัน ===
วิชา (Quest): {$questType}
ด่านที่: {$levelNum} — {$levelTitle}
โจทย์: {$challenge}

=== โค้ดของนักเรียน ===
```
{$studentCode}
```

=== ผลทดสอบ ===
{$testSummary}

=== แนวทางการสอน ===
1. พูดด้วยภาษาไทยเสมอ ใช้ภาษาที่เป็นมิตร กระชับ ไม่เกิน 300 คำต่อคำตอบ
2. ห้ามเฉลยโค้ดสมบูรณ์โดยตรง — ให้คำใบ้หรือคำถามนำแทน
3. ชมเชยสิ่งที่ถูกต้องก่อน แล้วค่อยชี้แนะสิ่งที่ควรปรับปรุง
4. หากนักเรียนถามซ้ำๆ ให้ค่อยๆ เพิ่มรายละเอียดในคำใบ้ แต่ยังไม่ให้คำตอบโดยตรง
5. ใช้ emoji เล็กน้อยเพื่อทำให้น่าอ่าน (✅ ❌ 💡 🤔 เป็นต้น)
6. จบด้วยคำถามเพื่อกระตุ้นให้นักเรียนลองแก้ไขเสมอ
PROMPT;

// ============================================================
// Check if ANTHROPIC_API_KEY is configured
// ============================================================
if (ANTHROPIC_API_KEY === 'sk-ant-YOUR_KEY_HERE' || ANTHROPIC_API_KEY === '') {
    sendDemoResponse($questType, $levelNum, $studentCode);
}

// ============================================================
// Detect streaming preference
// Accept: text/event-stream → stream
// ============================================================
$acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';
$wantStream   = str_contains($acceptHeader, 'text/event-stream');

if ($wantStream) {
    streamAnthropicResponse($systemPrompt, $history);
} else {
    sendFullAnthropicResponse($systemPrompt, $history);
}

// ============================================================
// Rate limit helpers
// ============================================================
function checkAiRateLimit(int $userId): void {
    $file    = sys_get_temp_dir() . '/cq_ai_rl_' . $userId . '.json';
    $window  = 3600;   // 1 hour
    $maxReqs = 20;
    $now     = time();

    $data = ['count' => 0, 'since' => $now];
    if (file_exists($file)) {
        $raw = @file_get_contents($file);
        if ($raw) {
            $parsed = json_decode($raw, true);
            if ($parsed) {
                if (($now - $parsed['since']) < $window) {
                    $data = $parsed;
                }
                // else window expired — reset silently
            }
        }
    }

    if ($data['count'] >= $maxReqs) {
        $remaining = (int) ceil(($data['since'] + $window - $now) / 60);
        errorResponse("คุณใช้ AI Tutor ครบ {$maxReqs} ครั้งในชั่วโมงนี้แล้ว กรุณารอ {$remaining} นาที", 429);
    }

    $data['count']++;
    @file_put_contents($file, json_encode($data), LOCK_EX);
}

// ============================================================
// Build human-readable test summary from testResults array
// ============================================================
function buildTestSummary(array $testResults): string {
    if (empty($testResults)) {
        return 'ยังไม่มีผลทดสอบ';
    }

    $passed = 0;
    $failed = 0;
    $lines  = [];

    foreach ($testResults as $i => $t) {
        $idx    = $i + 1;
        $ok     = !empty($t['passed']) || !empty($t['pass']);
        $desc   = trim($t['description'] ?? $t['desc'] ?? "Test #{$idx}");
        $expect = $t['expected'] ?? '';
        $actual = $t['actual'] ?? $t['output'] ?? '';

        if ($ok) {
            $passed++;
            $lines[] = "✅ Test {$idx}: {$desc}";
        } else {
            $failed++;
            $line = "❌ Test {$idx}: {$desc}";
            if ($expect !== '' || $actual !== '') {
                $line .= " (คาดหวัง: {$expect} | ได้รับ: {$actual})";
            }
            $lines[] = $line;
        }
    }

    $total   = $passed + $failed;
    $summary = "ผ่าน {$passed}/{$total} test cases\n" . implode("\n", $lines);
    return $summary;
}

// ============================================================
// Streaming response via SSE
// ============================================================
function streamAnthropicResponse(string $systemPrompt, array $history): never {
    // SSE headers
    header('Content-Type: text/event-stream; charset=utf-8');
    header('Cache-Control: no-cache, no-store');
    header('X-Accel-Buffering: no');   // Nginx: disable proxy buffering
    header('Connection: keep-alive');

    // Disable output buffering as much as possible
    while (ob_get_level() > 0) {
        ob_end_flush();
    }

    $payload = buildAnthropicPayload($systemPrompt, $history, true);

    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => buildAnthropicHeaders(),
        CURLOPT_RETURNTRANSFER => false,
        CURLOPT_WRITEFUNCTION  => function ($ch, $chunk) {
            // Each chunk may contain multiple SSE lines from Anthropic
            $lines = explode("\n", $chunk);
            foreach ($lines as $line) {
                $line = trim($line);
                if (!str_starts_with($line, 'data:')) continue;

                $json = trim(substr($line, 5));
                if ($json === '[DONE]') {
                    echo "data: [DONE]\n\n";
                    flush();
                    continue;
                }

                $event = json_decode($json, true);
                if (!$event) continue;

                $type = $event['type'] ?? '';

                // Forward content deltas as SSE
                if ($type === 'content_block_delta') {
                    $delta = $event['delta'] ?? [];
                    if (($delta['type'] ?? '') === 'text_delta') {
                        $text = $delta['text'] ?? '';
                        $out  = json_encode(
                            ['type' => 'text', 'text' => $text],
                            JSON_UNESCAPED_UNICODE
                        );
                        echo "data: {$out}\n\n";
                        flush();
                    }
                } elseif ($type === 'message_stop') {
                    echo "data: [DONE]\n\n";
                    flush();
                } elseif ($type === 'error') {
                    $errMsg = $event['error']['message'] ?? 'Unknown error';
                    $out    = json_encode(
                        ['type' => 'error', 'error' => $errMsg],
                        JSON_UNESCAPED_UNICODE
                    );
                    echo "data: {$out}\n\n";
                    flush();
                }
            }
            return strlen($chunk);
        },
        CURLOPT_TIMEOUT        => 120,
    ]);

    $curlErr = null;
    curl_exec($ch);
    if (curl_errno($ch)) {
        $curlErr = curl_error($ch);
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($curlErr || $httpCode >= 400) {
        $errMsg = $curlErr ?? "Anthropic API returned HTTP {$httpCode}";
        $out    = json_encode(['type' => 'error', 'error' => $errMsg], JSON_UNESCAPED_UNICODE);
        echo "data: {$out}\n\n";
        flush();
    } else {
        // หักเครดิต 1 หลังจาก API ตอบสำเร็จ
        $deduct = $db->prepare('UPDATE users SET credits = GREATEST(0, credits - 1) WHERE id = ?');
        $deduct->execute([$user['id']]);
        // log การใช้งาน
        $logStmt = $db->prepare('INSERT INTO ai_tutor_log (user_id, game_key, level_num) VALUES (?, ?, ?)');
        $logStmt->execute([$user['id'], $questType, $levelNum]);
    }

    exit;
}

// ============================================================
// Non-streaming (full JSON) response
// ============================================================
function sendFullAnthropicResponse(string $systemPrompt, array $history): never {
    $payload = buildAnthropicPayload($systemPrompt, $history, false);

    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => buildAnthropicHeaders(),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 60,
    ]);

    $raw      = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr  = curl_errno($ch) ? curl_error($ch) : null;
    curl_close($ch);

    if ($curlErr) {
        errorResponse('ไม่สามารถเชื่อมต่อ AI ได้: ' . $curlErr, 502);
    }

    $response = json_decode($raw, true);

    if ($httpCode >= 400) {
        $msg = $response['error']['message'] ?? "Anthropic API error {$httpCode}";
        errorResponse($msg, 502);
    }

    $text = $response['content'][0]['text'] ?? '';

    jsonResponse([
        'success'       => true,
        'message'       => $text,
        'input_tokens'  => $response['usage']['input_tokens']  ?? 0,
        'output_tokens' => $response['usage']['output_tokens'] ?? 0,
    ]);
}

// ============================================================
// Build Anthropic API payload
// ============================================================
function buildAnthropicPayload(string $systemPrompt, array $history, bool $stream): array {
    // Ensure conversation starts with a user message (Anthropic requirement)
    if (empty($history)) {
        $history = [['role' => 'user', 'content' => 'สวัสดี ช่วยฉันด้วยได้ไหม?']];
    }
    // Remove leading assistant messages
    while (!empty($history) && $history[0]['role'] === 'assistant') {
        array_shift($history);
    }
    if (empty($history)) {
        $history = [['role' => 'user', 'content' => 'ช่วยฉันด้วยได้ไหม?']];
    }

    return [
        'model'      => AI_MODEL,
        'max_tokens' => AI_MAX_TOKENS,
        'system'     => $systemPrompt,
        'messages'   => $history,
        'stream'     => $stream,
    ];
}

// ============================================================
// Build Anthropic HTTP headers
// ============================================================
function buildAnthropicHeaders(): array {
    return [
        'Content-Type: application/json',
        'x-api-key: ' . ANTHROPIC_API_KEY,
        'anthropic-version: 2023-06-01',
    ];
}

// ============================================================
// Demo response when API key is not configured
// ============================================================
function sendDemoResponse(string $questType, int $levelNum, string $studentCode): never {
    $codeSnippet = $studentCode !== ''
        ? mb_substr($studentCode, 0, 80) . (mb_strlen($studentCode) > 80 ? '…' : '')
        : '(ยังไม่มีโค้ด)';

    $demo = <<<MSG
💡 **AI Tutor Demo Mode** (ยังไม่ได้ตั้งค่า ANTHROPIC_API_KEY)

สวัสดี! ฉันเห็นว่าคุณกำลังทำด่านที่ {$levelNum} ของ {$questType} อยู่นะ 🎯

โค้ดของคุณตอนนี้:
```
{$codeSnippet}
```

🤔 ลองคิดดูว่า:
- โค้ดส่วนไหนที่คุณแน่ใจว่าถูกต้องบ้าง?
- ผลลัพธ์ที่ได้ตอนนี้แตกต่างจากที่คาดหวังยังไง?

ลองแก้ไขแล้วรันอีกครั้งดูนะครับ! 💪

*(หมายเหตุ: นี่คือ demo response กรุณาตั้งค่า ANTHROPIC_API_KEY ใน config.php เพื่อใช้งาน AI จริง)*
MSG;

    $acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';
    if (str_contains($acceptHeader, 'text/event-stream')) {
        header('Content-Type: text/event-stream; charset=utf-8');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no');
        while (ob_get_level() > 0) ob_end_flush();

        // Stream demo word-by-word to simulate real streaming
        $words = explode(' ', $demo);
        foreach ($words as $i => $word) {
            $text = ($i === 0 ? '' : ' ') . $word;
            $out  = json_encode(['type' => 'text', 'text' => $text], JSON_UNESCAPED_UNICODE);
            echo "data: {$out}\n\n";
            flush();
            usleep(30000);   // 30ms delay between words
        }
        echo "data: [DONE]\n\n";
        flush();
        exit;
    }

    jsonResponse(['success' => true, 'message' => $demo, 'demo' => true]);
}
