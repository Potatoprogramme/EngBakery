-- Users soft-delete support for EngBakery
-- Run on the active application database.

USE engbakery_march15;

-- Add deleted_at for soft deletes (safe if re-run).
SET @has_col := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'users'
    AND COLUMN_NAME = 'deleted_at'
);

SET @sql := IF(@has_col = 0,
  'ALTER TABLE users ADD COLUMN deleted_at DATETIME NULL AFTER created_at',
  'SELECT ''deleted_at already exists'''
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Add index for archived-user lookups (safe if re-run).
SET @has_idx := (
  SELECT COUNT(*)
  FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'users'
    AND INDEX_NAME = 'idx_users_deleted_at'
);

SET @sql := IF(@has_idx = 0,
  'CREATE INDEX idx_users_deleted_at ON users (deleted_at)',
  'SELECT ''idx_users_deleted_at already exists'''
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Verification
SELECT DATABASE() AS active_db;
SHOW COLUMNS FROM users LIKE 'deleted_at';
