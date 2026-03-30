<?php
// ============================================================
// CodeQuest — Game Progress API
// GET  /api/progress.php?game=python
// POST /api/progress.php          (body: game_key + progress data)
// POST /api/progress.php?action=complete_level
// ============================================================

require_once __DIR__ . '/config.php';

setCorsHeaders();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET') {
    handleGetProgress();
} elseif ($method === 'POST') {
    if ($action === 'complete_level') {
        handleCompleteLevel();
    } else {
        handleSaveProgress();
    }
} else {
    errorResponse('Method not allowed', 405);
}

// ============================================================
// Valid game keys
// ============================================================
const VALID_GAMES = ['python', 'javascript', 'htmlcss', 'sql', 'ai'];

function validateGameKey(string $key): void {
    if (!in_array($key, VALID_GAMES, true)) {
        errorResponse('game_key ไม่ถูกต้อง ต้องเป็น: ' . implode(', ', VALID_GAMES));
    }
}

// ============================================================
// GET — fetch progress for one game
// ============================================================
function handleGetProgress(): never {
    $user    = requireAuth();
    $gameKey = trim($_GET['game'] ?? '');

    if ($gameKey === '') {
        errorResponse('กรุณาระบุ game key (?game=python)');
    }
    validateGameKey($gameKey);

    $db   = getDB();
    $stmt = $db->prepare(
        'SELECT current_level, max_level_reached, total_xp,
                completed_levels, level_scores
         FROM game_progress
         WHERE user_id = ? AND game_key = ?
         LIMIT 1'
    );
    $stmt->execute([$user['id'], $gameKey]);
    $row = $stmt->fetch();

    if (!$row) {
        // No progress yet — return zeroed-out structure
        jsonResponse([
            'success'         => true,
            'game'            => $gameKey,
            'currentLevel'    => 1,
            'maxLevel'        => 0,
            'xp'              => 0,
            'completedLevels' => [],
            'levelScores'     => (object) [],
        ]);
    }

    jsonResponse([
        'success'         => true,
        'game'            => $gameKey,
        'currentLevel'    => (int) $row['current_level'],
        'maxLevel'        => (int) $row['max_level_reached'],
        'xp'              => (int) $row['total_xp'],
        'completedLevels' => json_decode($row['completed_levels'], true) ?? [],
        'levelScores'     => json_decode($row['level_scores'], true) ?? (object) [],
    ]);
}

// ============================================================
// POST — save / sync full progress
// ============================================================
function handleSaveProgress(): never {
    $user = requireAuth();
    $body = getBody();

    $gameKey         = trim($body['game_key'] ?? '');
    $currentLevel    = (int) ($body['currentLevel'] ?? 1);
    $maxLevel        = (int) ($body['maxLevel'] ?? 0);
    $xp              = (int) ($body['xp'] ?? 0);
    $completedLevels = $body['completedLevels'] ?? [];
    $levelScores     = $body['levelScores'] ?? [];
    $levelNum        = isset($body['levelNum']) ? (int) $body['levelNum'] : null;
    $xpGained        = (int) ($body['xpGained'] ?? 0);
    $hintsUsed       = (int) ($body['hintsUsed'] ?? 0);
    $attempts        = (int) ($body['attempts'] ?? 1);

    if ($gameKey === '') errorResponse('กรุณาระบุ game_key');
    validateGameKey($gameKey);

    if (!is_array($completedLevels)) $completedLevels = [];
    if (!is_array($levelScores))     $levelScores     = [];

    $completedJson = json_encode(array_values(array_unique(array_map('intval', $completedLevels))));
    $scoresJson    = json_encode($levelScores);

    $db = getDB();

    // UPSERT game_progress
    $stmt = $db->prepare(
        'INSERT INTO game_progress
            (user_id, game_key, current_level, max_level_reached, total_xp,
             completed_levels, level_scores)
         VALUES (?, ?, ?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE
            current_level     = VALUES(current_level),
            max_level_reached = GREATEST(max_level_reached, VALUES(max_level_reached)),
            total_xp          = VALUES(total_xp),
            completed_levels  = VALUES(completed_levels),
            level_scores      = VALUES(level_scores),
            last_played_at    = NOW()'
    );
    $stmt->execute([
        $user['id'], $gameKey,
        $currentLevel, $maxLevel, $xp,
        $completedJson, $scoresJson,
    ]);

    // Log XP history if a specific level was just completed
    if ($levelNum !== null && $xpGained > 0) {
        // Only insert if not already recorded (idempotent)
        $check = $db->prepare(
            'SELECT id FROM xp_history
             WHERE user_id = ? AND game_key = ? AND level_num = ?
             LIMIT 1'
        );
        $check->execute([$user['id'], $gameKey, $levelNum]);
        if (!$check->fetch()) {
            $db->prepare(
                'INSERT INTO xp_history
                    (user_id, game_key, level_num, xp_gained, hints_used, attempts)
                 VALUES (?, ?, ?, ?, ?, ?)'
            )->execute([$user['id'], $gameKey, $levelNum, $xpGained, $hintsUsed, $attempts]);
        }
    }

    // Recompute total_xp across all games for the user
    $totalXp = recomputeTotalXp($db, (int) $user['id']);

    jsonResponse(['success' => true, 'totalXp' => $totalXp]);
}

// ============================================================
// POST ?action=complete_level — atomic single-level completion
// ============================================================
function handleCompleteLevel(): never {
    $user = requireAuth();
    $body = getBody();

    $gameKey     = trim($body['game_key'] ?? '');
    $levelNum    = (int) ($body['level_num'] ?? 0);
    $xpGained    = (int) ($body['xp_gained'] ?? 0);
    $hintsUsed   = (int) ($body['hints_used'] ?? 0);
    $attempts    = max(1, (int) ($body['attempts'] ?? 1));
    $passedCases = (int) ($body['passed_cases'] ?? 0);
    $totalCases  = (int) ($body['total_cases'] ?? 0);

    if ($gameKey === '')  errorResponse('กรุณาระบุ game_key');
    if ($levelNum <= 0)   errorResponse('level_num ต้องมากกว่า 0');
    validateGameKey($gameKey);

    $db = getDB();

    // --- fetch current progress (or create row) ---
    $stmt = $db->prepare(
        'SELECT id, max_level_reached, total_xp, completed_levels, level_scores
         FROM game_progress
         WHERE user_id = ? AND game_key = ?
         LIMIT 1'
    );
    $stmt->execute([$user['id'], $gameKey]);
    $progress = $stmt->fetch();

    if (!$progress) {
        // Insert initial row
        $db->prepare(
            'INSERT INTO game_progress
                (user_id, game_key, current_level, max_level_reached, total_xp,
                 completed_levels, level_scores)
             VALUES (?, ?, 1, 0, 0, ?, ?)'
        )->execute([$user['id'], $gameKey, '[]', '{}']);

        $stmt->execute([$user['id'], $gameKey]);
        $progress = $stmt->fetch();
    }

    $completedLevels = json_decode($progress['completed_levels'], true) ?? [];
    $levelScores     = json_decode($progress['level_scores'], true)     ?? [];

    $alreadyDone = in_array($levelNum, $completedLevels, true);

    // Append to completed list
    if (!$alreadyDone) {
        $completedLevels[] = $levelNum;
    }

    // Update score for this level (keep best XP)
    $existingXp = (int) ($levelScores[(string) $levelNum]['xp'] ?? 0);
    if ($xpGained >= $existingXp) {
        $levelScores[(string) $levelNum] = [
            'xp'       => $xpGained,
            'hints'    => $hintsUsed,
            'attempts' => $attempts,
            'passed'   => $passedCases,
            'total'    => $totalCases,
        ];
    }

    $newMaxLevel    = max((int) $progress['max_level_reached'], $levelNum);
    $newTotalXp     = max(0, (int) $progress['total_xp'] + ($alreadyDone ? 0 : $xpGained));
    $nextLevel      = $levelNum + 1;

    $db->prepare(
        'UPDATE game_progress SET
            current_level     = GREATEST(current_level, ?),
            max_level_reached = GREATEST(max_level_reached, ?),
            total_xp          = ?,
            completed_levels  = ?,
            level_scores      = ?,
            last_played_at    = NOW()
         WHERE user_id = ? AND game_key = ?'
    )->execute([
        $nextLevel,
        $newMaxLevel,
        $newTotalXp,
        json_encode(array_values(array_unique($completedLevels))),
        json_encode($levelScores),
        $user['id'],
        $gameKey,
    ]);

    // Log XP history only on first completion
    if (!$alreadyDone && $xpGained > 0) {
        $db->prepare(
            'INSERT IGNORE INTO xp_history
                (user_id, game_key, level_num, xp_gained, hints_used, attempts)
             VALUES (?, ?, ?, ?, ?, ?)'
        )->execute([$user['id'], $gameKey, $levelNum, $xpGained, $hintsUsed, $attempts]);
    }

    // Sync users.total_xp
    $totalXp = recomputeTotalXp($db, (int) $user['id']);

    jsonResponse([
        'success'    => true,
        'levelNum'   => $levelNum,
        'xpGained'   => $alreadyDone ? 0 : $xpGained,
        'newGameXp'  => $newTotalXp,
        'totalXp'    => $totalXp,
        'alreadyDone' => $alreadyDone,
    ]);
}

// ============================================================
// Helpers
// ============================================================

function recomputeTotalXp(PDO $db, int $userId): int {
    $stmt = $db->prepare(
        'SELECT COALESCE(SUM(total_xp), 0) AS t FROM game_progress WHERE user_id = ?'
    );
    $stmt->execute([$userId]);
    $total = (int) $stmt->fetchColumn();

    $db->prepare('UPDATE users SET total_xp = ? WHERE id = ?')
       ->execute([$total, $userId]);

    return $total;
}
