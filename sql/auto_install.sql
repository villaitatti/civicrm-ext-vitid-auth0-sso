-- Create role mapping table for vitid_auth0 extension
CREATE TABLE IF NOT EXISTS `civicrm_vitid_role_mapping` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `auth0_role_name` VARCHAR(255) NOT NULL UNIQUE,
  `civicrm_role_id` INT UNSIGNED NOT NULL,
  `is_active` TINYINT DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_active` (`is_active`),
  INDEX `idx_auth0_role` (`auth0_role_name`),
  FOREIGN KEY (`civicrm_role_id`) REFERENCES `civicrm_role`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create OAuth state storage table for vitid_auth0 extension
CREATE TABLE IF NOT EXISTS `civicrm_vitid_auth0_state` (
  `state` VARCHAR(64) NOT NULL PRIMARY KEY,
  `nonce` VARCHAR(64) NOT NULL,
  `code_verifier` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
