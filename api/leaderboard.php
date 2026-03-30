<?php
// ============================================================
// CodeQuest — Leaderboard API (public, no auth required)
// GET /api/leaderboard.php?type=overall|weekly|quest|stats
//                          &game=python
//                          &limit=50&offset=0
// ============================================================

require_once __DIR__ . '/config.php';

setCorsHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    errorResponse('Method not allowed', 405);
}

$type   = $_GET['type']   ?? 'overall';
$game   = trim($_GET['game']   ?? '');
$limit  = max(1, min(200, (int) ($_GET['limit']  ?? 50)));
$offset = max(0, (int) ($_GET['offset'] ?? 0));

match ($type) {
    'overall' => handleOverall($limit, $offset),
    'weekly'  => handleWeekly($limit, $offset),
    'quest'   => handleQuest($game, $limit, $offset),
    'stats'   => handleStats(),
    default   => errorResponse('type ไม่ถูกต้อง ใช้: overall, weekly, quest, stats', 400),
};

// ============================================================
// Helpers
// ============================================================

/**
 * Add 1-based rank to rows starting at $offset + 1.
 */
function addRank(array $rows, int $offset = 0): array {
    $rank = $offset + 1;
    foreach ($rows as &$row) {
        $row['rank'] = $rank++;
        // Cast numeric fields
        if (isset($row['id']))               $row['id']               = (int) $row['id'];
        if (isset($row['total_xp']))         $row['total_xp']         = (int) $row['total_xp'];
        if (isset($row['weekly_xp']))        $row['weekly_xp']        = (int) $row['weekly_xp'];
        if (isset($row['quests_started']))   $row['quests_started']   = (int) $row['quests_started'];
        if (isset($row['total_levels_done'])) $row['total_levels_done'] = (int) $row['total_levels_done'];
        if (isset($row['max_level_reached'])) $row['max_level_reached'] = (int) $row['max_level_reached'];
        if (isset($row['levels_this_week'])) $row['levels_this_week'] = (int) $row['levels_this_week'];
        if (isset($row['user_id']))          $row['user_id']          = (int) $row['user_id'];
    }
    unset($row);
    return $rows;
}

// ============================================================
// Overall leaderboard
// ============================================================
function handleOverall(int $limit, int $offset): never {
    $db = getDB();

    // Count total eligible users
    $totalStmt = $db->query(
        'SELECT COUNT(*) FROM v_leaderboard'
    );
    $total = (int) $totalStmt->fetchColumn();

    $stmt = $db->prepare(
        'SELECT id, display_name, avatar_url, school, total_xp,
                quests_started, total_levels_done, joined_at
         FROM v_leaderboard
         LIMIT ? OFFSET ?'
    );
    $stmt->execute([$limit, $offset]);
    $rows = $stmt->fetchAll();

    jsonResponse([
        'success' => true,
        'type'    => 'overall',
        'total'   => $total,
        'limit'   => $limit,
        'offset'  => $offset,
        'data'    => addRank($rows, $offset),
    ]);
}

// ============================================================
// Weekly leaderboard
// ============================================================
function handleWeekly(int $limit, int $offset): never {
    $db = getDB();

    $totalStmt = $db->query('SELECT COUNT(*) FROM v_weekly_leaderboard');
    $total     = (int) $totalStmt->fetchColumn();

    $stmt = $db->prepare(
        'SELECT id, display_name, avatar_url, school, weekly_xp, levels_this_week
         FROM v_weekly_leaderboard
         LIMIT ? OFFSET ?'
    );
    $stmt->execute([$limit, $offset]);
    $rows = $stmt->fetchAll();

    jsonResponse([
        'success' => true,
        'type'    => 'weekly',
        'total'   => $total,
        'limit'   => $limit,
        'offset'  => $offset,
        'data'    => addRank($rows, $offset),
    ]);
}

// ============================================================
// Per-quest leaderboard
// ============================================================
function handleQuest(string $game, int $limit, int $offset): never {
    $validGames = ['python', 'javascript', 'htmlcss', 'sql', 'ai'];

    if ($game === '') {
        errorResponse('กรุณาระบุ ?game=python (หรือ js / htmlcss / sql / ai)');
    }
    if (!in_array($game, $validGames, true)) {
        errorResponse('game ไม่ถูกต้อง ต้องเป็น: ' . implode(', ', $validGames));
    }

    $db = getDB();

    $totalStmt = $db->prepare(
        'SELECT COUNT(*) FROM v_quest_leaderboard WHERE game_key = ?'
    );
    $totalStmt->execute([$game]);
    $total = (int) $totalStmt->fetchColumn();

    $stmt = $db->prepare(
        'SELECT user_id AS id, display_name, avatar_url, school,
                total_xp, max_level_reached, last_played_at
         FROM v_quest_leaderboard
         WHERE game_key = ?
         LIMIT ? OFFSET ?'
    );
    $stmt->execute([$game, $limit, $offset]);
    $rows = $stmt->fetchAll();

    jsonResponse([
        'success'  => true,
        'type'     => 'quest',
        'game'     => $game,
        'total'    => $total,
        'limit'    => $limit,
        'offset'   => $offset,
        'data'     => addRank($rows, $offset),
    ]);
}

// ============================================================
// Global stats
// ============================================================
function handleStats(): never {
    $db = getDB();

    // Total users
    $totalUsers = (int) $db->query(
        'SELECT COUNT(*) FROM users WHERE total_xp > 0'
    )->fetchColumn();

    // Total XP across all users
    $totalXpAll = (int) $db->query(
        'SELECT COALESCE(SUM(total_xp), 0) FROM users'
    )->fetchColumn();

    // Most popular quest (most players who have started it)
    $popularStmt = $db->query(
        'SELECT game_key, COUNT(*) AS player_count
         FROM game_progress
         WHERE max_level_reached > 0
         GROUP BY game_key
         ORDER BY player_count DESC
         LIMIT 1'
    );
    $popular = $popularStmt->fetch();

    // Active users this week
    $weeklyActive = (int) $db->query(
        'SELECT COUNT(DISTINCT user_id) FROM xp_history
         WHERE earned_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)'
    )->fetchColumn();

    // XP earned this week
    $weeklyXp = (int) $db->query(
        'SELECT COALESCE(SUM(xp_gained), 0) FROM xp_history
         WHERE earned_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)'
    )->fetchColumn();

    jsonResponse([
        'success'           => true,
        'total_users'       => $totalUsers,
        'total_xp_all'      => $totalXpAll,
        'most_popular_quest' => $popular ? [
            'game_key'     => $popular['game_key'],
            'player_count' => (int) $popular['player_count'],
        ] : null,
        'weekly_active_users' => $weeklyActive,
        'weekly_xp_earned'    => $weeklyXp,
    ]);
}
