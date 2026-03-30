-- ============================================================
-- CodeQuest — Credit System (เพิ่มใน phpMyAdmin)
-- รันต่อจาก schema_mysql.sql
-- ============================================================

-- เพิ่มคอลัมน์ credits ใน users
ALTER TABLE users
  ADD COLUMN credits INT UNSIGNED NOT NULL DEFAULT 0 AFTER total_xp,
  ADD COLUMN is_admin TINYINT(1) NOT NULL DEFAULT 0 AFTER is_public;

-- ตาราง credit_requests — บันทึกการขอเติมเครดิต
CREATE TABLE IF NOT EXISTS credit_requests (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id       INT UNSIGNED NOT NULL,
  amount_thb    SMALLINT UNSIGNED NOT NULL,       -- บาทที่ต้องการเติม
  credits_given SMALLINT UNSIGNED NOT NULL,       -- เครดิตที่จะได้รับ
  slip_note     VARCHAR(500)   DEFAULT '',        -- หมายเหตุจาก slip / ref no.
  status        ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  admin_note    VARCHAR(500)   DEFAULT '',
  requested_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  processed_at  DATETIME DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_user   (user_id),
  KEY idx_status (status),
  KEY idx_date   (requested_at),
  CONSTRAINT fk_cr_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ตาราง ai_tutor_log — log การใช้ AI Tutor
CREATE TABLE IF NOT EXISTS ai_tutor_log (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id      INT UNSIGNED NOT NULL,
  game_key     VARCHAR(20)  NOT NULL,
  level_num    SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  credits_used TINYINT UNSIGNED NOT NULL DEFAULT 1,
  used_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_user_date (user_id, used_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ตั้ง admin (ใส่ email ของ admin ตรงนี้)
-- UPDATE users SET is_admin = 1 WHERE email = 'namput2557@gmail.com';
