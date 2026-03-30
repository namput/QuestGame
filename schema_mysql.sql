-- ============================================================
-- CodeQuest v2 — MySQL / MariaDB Schema
-- ผจญภัยแดนโค้ด
-- ============================================================
-- ใช้กับ MariaDB 10.2+ / MySQL 5.7+ (รองรับ JSON column)
-- รันใน phpMyAdmin: เลือก database neuatech_gamecode แล้ว paste
-- ============================================================

SET NAMES utf8mb4;
SET time_zone = '+07:00';

-- ============================================================
-- 1. USERS — บัญชีผู้ใช้
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
  id            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  email         VARCHAR(255)    NOT NULL,
  password_hash VARCHAR(255)    NOT NULL,
  display_name  VARCHAR(100)    NOT NULL DEFAULT '',
  avatar_url    VARCHAR(500)    DEFAULT '',
  school        VARCHAR(200)    DEFAULT '',
  bio           TEXT            DEFAULT '',
  total_xp      INT UNSIGNED    NOT NULL DEFAULT 0,
  is_public     TINYINT(1)      NOT NULL DEFAULT 1,
  email_verified TINYINT(1)    NOT NULL DEFAULT 0,
  verify_token  VARCHAR(64)     DEFAULT NULL,
  reset_token   VARCHAR(64)     DEFAULT NULL,
  reset_expires DATETIME        DEFAULT NULL,
  last_login_at DATETIME        DEFAULT NULL,
  created_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_email (email),
  KEY idx_total_xp (total_xp),
  KEY idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. SESSIONS — auth sessions
-- ============================================================
CREATE TABLE IF NOT EXISTS sessions (
  token         VARCHAR(64)     NOT NULL,
  user_id       INT UNSIGNED    NOT NULL,
  expires_at    DATETIME        NOT NULL,
  created_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (token),
  KEY idx_user_id (user_id),
  KEY idx_expires (expires_at),
  CONSTRAINT fk_session_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- 3. GAME_PROGRESS — ความก้าวหน้าแต่ละวิชา
-- ============================================================
CREATE TABLE IF NOT EXISTS game_progress (
  id                INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id           INT UNSIGNED NOT NULL,
  game_key          VARCHAR(20)  NOT NULL,   -- python|javascript|htmlcss|sql|ai
  current_level     SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  max_level_reached SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  total_xp          INT UNSIGNED NOT NULL DEFAULT 0,
  completed_levels  JSON         NOT NULL,   -- [1,2,3,...]
  level_scores      JSON         NOT NULL,
  -- {"1":{"xp":100,"hints":0,"attempts":1,"passed":3,"total":3}}
  started_at        DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_played_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_user_game (user_id, game_key),
  KEY idx_game_xp (game_key, total_xp),
  CONSTRAINT fk_progress_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- 4. XP_HISTORY — ประวัติ XP (สำหรับ weekly leaderboard)
-- ============================================================
CREATE TABLE IF NOT EXISTS xp_history (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id    INT UNSIGNED NOT NULL,
  game_key   VARCHAR(20)  NOT NULL,
  level_num  SMALLINT UNSIGNED NOT NULL,
  xp_gained  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  hints_used TINYINT UNSIGNED  NOT NULL DEFAULT 0,
  attempts   TINYINT UNSIGNED  NOT NULL DEFAULT 1,
  earned_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_user_earned (user_id, earned_at),
  KEY idx_earned (earned_at),
  CONSTRAINT fk_xp_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- 5. CERTIFICATES — ใบรับรอง
-- ============================================================
CREATE TABLE IF NOT EXISTS certificates (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id    INT UNSIGNED NOT NULL,
  game_key   VARCHAR(20)  NOT NULL,
  cert_type  ENUM('bronze','silver','gold','platinum') NOT NULL,
  cert_name  VARCHAR(200) NOT NULL,
  cert_code  VARCHAR(32)  NOT NULL,
  issued_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_cert_code (cert_code),
  UNIQUE KEY uq_user_game_type (user_id, game_key, cert_type),
  KEY idx_user (user_id),
  CONSTRAINT fk_cert_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- 6. ACHIEVEMENTS — เหรียญรางวัล
-- ============================================================
CREATE TABLE IF NOT EXISTS achievements (
  id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id         INT UNSIGNED NOT NULL,
  achievement_key VARCHAR(50)  NOT NULL,
  game_key        VARCHAR(20)  DEFAULT NULL,
  unlocked_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_user_achievement (user_id, achievement_key),
  CONSTRAINT fk_ach_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- VIEWS — Leaderboard
-- ============================================================

-- Overall leaderboard
CREATE OR REPLACE VIEW v_leaderboard AS
SELECT
  u.id,
  u.display_name,
  u.avatar_url,
  u.school,
  u.total_xp,
  u.created_at                                           AS joined_at,
  (SELECT COUNT(DISTINCT game_key) FROM game_progress
   WHERE user_id = u.id AND max_level_reached > 0)      AS quests_started,
  (SELECT COALESCE(SUM(max_level_reached), 0)
   FROM game_progress WHERE user_id = u.id)              AS total_levels_done
FROM users u
WHERE u.is_public = 1
  AND u.total_xp > 0
ORDER BY u.total_xp DESC;

-- Weekly leaderboard
CREATE OR REPLACE VIEW v_weekly_leaderboard AS
SELECT
  u.id,
  u.display_name,
  u.avatar_url,
  u.school,
  COALESCE(SUM(xh.xp_gained), 0)  AS weekly_xp,
  COUNT(xh.id)                     AS levels_this_week
FROM users u
LEFT JOIN xp_history xh
  ON xh.user_id = u.id
  AND xh.earned_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
WHERE u.is_public = 1
GROUP BY u.id, u.display_name, u.avatar_url, u.school
HAVING weekly_xp > 0
ORDER BY weekly_xp DESC;

-- Per-quest leaderboard
CREATE OR REPLACE VIEW v_quest_leaderboard AS
SELECT
  gp.game_key,
  u.id                    AS user_id,
  u.display_name,
  u.avatar_url,
  u.school,
  gp.total_xp,
  gp.max_level_reached,
  gp.last_played_at
FROM game_progress gp
JOIN users u ON u.id = gp.user_id
WHERE u.is_public = 1
  AND gp.max_level_reached > 0
ORDER BY gp.game_key, gp.total_xp DESC;
