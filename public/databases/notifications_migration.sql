-- ============================================================
-- NOTIFICATION SYSTEM MIGRATION
-- Run this against `engbakery` database
-- ============================================================

CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  
  -- Who is this notification for?
  -- NULL = broadcast to all matching roles
  `user_id` int(11) DEFAULT NULL,
  
  -- Role-based targeting: which employee_type(s) can see this
  -- Comma-separated: 'owner', 'admin', 'staff', 'owner,admin', 'owner,admin,staff'
  `target_roles` varchar(50) NOT NULL DEFAULT 'owner,admin',
  
  -- Notification content
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('low_stock','missed_remittance','distribution','system','approval') NOT NULL DEFAULT 'system',
  
  -- Severity level for visual styling
  `level` enum('info','warning','critical') NOT NULL DEFAULT 'info',
  
  -- Optional link to navigate to when clicked
  `action_url` varchar(255) DEFAULT NULL,
  
  -- Read tracking (per-notification; for broadcast we use a separate table)
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  
  -- Reference to related entity (e.g. material_id, remittance_id)
  `reference_id` int(11) DEFAULT NULL,
  `reference_type` varchar(50) DEFAULT NULL,
  
  -- Timestamps
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime DEFAULT NULL,

  PRIMARY KEY (`notification_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_target_roles` (`target_roles`),
  KEY `idx_type` (`type`),
  KEY `idx_is_read` (`is_read`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- Read tracking for broadcast notifications (no user_id)
-- When a broadcast notification is read by a user, insert here
-- ============================================================

CREATE TABLE IF NOT EXISTS `notification_reads` (
  `read_id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `read_at` datetime NOT NULL DEFAULT current_timestamp(),

  PRIMARY KEY (`read_id`),
  UNIQUE KEY `uq_notif_user` (`notification_id`, `user_id`),
  CONSTRAINT `fk_notif_reads_notif` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`notification_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notif_reads_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
