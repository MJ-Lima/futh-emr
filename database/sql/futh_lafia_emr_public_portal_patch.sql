-- =====================================================================
--  FUTH Lafia EMR — Public Portal schema patch for EXISTING live DBs
-- ---------------------------------------------------------------------
--  This script is for users who ALREADY have the live `futh_lafia_emr`
--  database (matching the futh_lafia_emr.sql dump) and just want to add
--  the public portal features on top of it.
--
--  It is IDEMPOTENT: safe to re-run. Each statement checks for the
--  existence of the column / table before adding / creating it.
--
--  After running this, also run `futh_lafia_emr_seed_data.sql` to populate
--  the new directory hospitals + portal roles.
--
--  Equivalent to running these Laravel commands on a fresh clone:
--      php artisan migrate
--      php artisan db:seed --class=PublicPortalRolesSeeder
--      php artisan db:seed --class=PublicHospitalDirectorySeeder
--
--  MariaDB / MySQL 10.4+ syntax. Designed to mirror exactly the columns
--  created by:
--      2026_11_01_000001_create_public_portal_tables.php
--      2026_11_01_000002_create_legacy_geography_tables.php
-- =====================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- 1. Extend the `hospitals` table with public-directory fields
-- ---------------------------------------------------------------------
-- MariaDB has no clean ADD COLUMN IF NOT EXISTS, so we use information_schema checks.

-- type
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `type` VARCHAR(191) NOT NULL DEFAULT ''General'' AFTER `code`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'type');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.type already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- tagline
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `tagline` VARCHAR(191) NULL AFTER `type`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'tagline');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.tagline already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- description
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `description` TEXT NULL AFTER `tagline`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'description');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.description already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- address_line1
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `address_line1` VARCHAR(191) NULL AFTER `description`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'address_line1');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.address_line1 already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- address_line2
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `address_line2` VARCHAR(191) NULL AFTER `address_line1`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'address_line2');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.address_line2 already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- city
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `city` VARCHAR(250) NULL AFTER `address_line2`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'city');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.city already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- state
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `state` VARCHAR(250) NULL AFTER `city`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'state');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.state already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- country
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `country` VARCHAR(250) NOT NULL DEFAULT ''Nigeria'' AFTER `state`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'country');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.country already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- postal_code
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `postal_code` VARCHAR(20) NULL AFTER `country`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'postal_code');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.postal_code already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- phone_main
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `phone_main` VARCHAR(30) NULL AFTER `postal_code`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'phone_main');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.phone_main already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- phone_alt
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `phone_alt` VARCHAR(30) NULL AFTER `phone_main`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'phone_alt');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.phone_alt already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- email
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `email` VARCHAR(191) NULL AFTER `phone_alt`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'email');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.email already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- website
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `website` VARCHAR(191) NULL AFTER `email`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'website');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.website already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- logo_path
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `logo_path` VARCHAR(191) NULL AFTER `website`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'logo_path');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.logo_path already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- cover_path
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `cover_path` VARCHAR(191) NULL AFTER `logo_path`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'cover_path');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.cover_path already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- latitude
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `latitude` DECIMAL(10,7) NULL AFTER `cover_path`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'latitude');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.latitude already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- longitude
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `longitude` DECIMAL(10,7) NULL AFTER `latitude`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'longitude');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.longitude already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- services (JSON)
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `services` JSON NULL AFTER `longitude`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'services');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.services already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- operating_hours (JSON)
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `operating_hours` JSON NULL AFTER `services`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'operating_hours');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.operating_hours already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- is_public
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `is_public` TINYINT(1) NOT NULL DEFAULT 1 AFTER `operating_hours`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'is_public');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.is_public already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- accepts_online_registration
SET @s = CONCAT('ALTER TABLE `hospitals` ADD COLUMN `accepts_online_registration` TINYINT(1) NOT NULL DEFAULT 1 AFTER `is_public`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'hospitals' AND COLUMN_NAME = 'accepts_online_registration');
SET @sql = IF(@c = 0, @s, 'SELECT ''hospitals.accepts_online_registration already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ---------------------------------------------------------------------
-- 2. Create the new portal tables
-- ---------------------------------------------------------------------

-- patient_portal_users
CREATE TABLE IF NOT EXISTS `patient_portal_users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient_id` BIGINT UNSIGNED DEFAULT NULL,
  `username` VARCHAR(191) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `password` VARCHAR(191) NOT NULL,
  `phone` VARCHAR(30) DEFAULT NULL,
  `status` ENUM('pending','active','suspended','locked') NOT NULL DEFAULT 'pending',
  `verification_token` VARCHAR(100) DEFAULT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `approved_at` TIMESTAMP NULL DEFAULT NULL,
  `approved_by` BIGINT UNSIGNED DEFAULT NULL,
  `last_login_at` TIMESTAMP NULL DEFAULT NULL,
  `last_login_ip` VARCHAR(45) DEFAULT NULL,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patient_portal_users_username_unique` (`username`),
  UNIQUE KEY `patient_portal_users_email_unique` (`email`),
  KEY `patient_portal_users_patient_id_index` (`patient_id`),
  KEY `patient_portal_users_approved_by_index` (`approved_by`),
  CONSTRAINT `patient_portal_users_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `patient_portal_users_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- provider_applications
CREATE TABLE IF NOT EXISTS `provider_applications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_type` ENUM('doctor','hospital','clinic') NOT NULL DEFAULT 'doctor',
  `application_number` VARCHAR(191) NOT NULL,
  `applicant_name` VARCHAR(191) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `password` VARCHAR(191) NOT NULL,
  `title` VARCHAR(30) DEFAULT NULL,
  `professional_registration_number` VARCHAR(100) DEFAULT NULL,
  `specialty` VARCHAR(191) DEFAULT NULL,
  `qualification` VARCHAR(191) DEFAULT NULL,
  `years_of_experience` INT DEFAULT NULL,
  `facility_name` VARCHAR(191) DEFAULT NULL,
  `facility_type` VARCHAR(100) DEFAULT NULL,
  `facility_license_number` VARCHAR(100) DEFAULT NULL,
  `facility_address` TEXT DEFAULT NULL,
  `facility_city` VARCHAR(150) DEFAULT NULL,
  `facility_state` VARCHAR(150) DEFAULT NULL,
  `facility_country` VARCHAR(150) DEFAULT 'Nigeria',
  `bio` TEXT DEFAULT NULL,
  `logo_path` VARCHAR(191) DEFAULT NULL,
  `website` VARCHAR(191) DEFAULT NULL,
  `services_offered` JSON DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `status` ENUM('pending','under_review','approved','rejected','withdrawn') NOT NULL DEFAULT 'pending',
  `reviewed_by` BIGINT UNSIGNED DEFAULT NULL,
  `reviewed_at` TIMESTAMP NULL DEFAULT NULL,
  `review_notes` TEXT DEFAULT NULL,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `hospital_id` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `provider_applications_application_number_unique` (`application_number`),
  UNIQUE KEY `provider_applications_email_unique` (`email`),
  KEY `provider_applications_reviewed_by_index` (`reviewed_by`),
  KEY `provider_applications_user_id_index` (`user_id`),
  KEY `provider_applications_hospital_id_index` (`hospital_id`),
  CONSTRAINT `provider_applications_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `provider_applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `provider_applications_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- patient_portal_messages
CREATE TABLE IF NOT EXISTS `patient_portal_messages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient_portal_user_id` BIGINT UNSIGNED NOT NULL,
  `patient_id` BIGINT UNSIGNED DEFAULT NULL,
  `hospital_id` BIGINT UNSIGNED DEFAULT NULL,
  `staff_user_id` BIGINT UNSIGNED DEFAULT NULL,
  `direction` ENUM('inbound','outbound') NOT NULL DEFAULT 'inbound',
  `status` ENUM('sent','delivered','read','replied') NOT NULL DEFAULT 'sent',
  `subject` VARCHAR(191) DEFAULT NULL,
  `body` TEXT NOT NULL,
  `attachments` JSON DEFAULT NULL,
  `read_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_portal_messages_patient_portal_user_id_index` (`patient_portal_user_id`),
  KEY `patient_portal_messages_patient_id_index` (`patient_id`),
  KEY `patient_portal_messages_hospital_id_index` (`hospital_id`),
  KEY `patient_portal_messages_staff_user_id_index` (`staff_user_id`),
  CONSTRAINT `patient_portal_messages_patient_portal_user_id_foreign` FOREIGN KEY (`patient_portal_user_id`) REFERENCES `patient_portal_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_portal_messages_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `patient_portal_messages_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE SET NULL,
  CONSTRAINT `patient_portal_messages_staff_user_id_foreign` FOREIGN KEY (`staff_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- patient_portal_password_reset_tokens
CREATE TABLE IF NOT EXISTS `patient_portal_password_reset_tokens` (
  `email` VARCHAR(191) NOT NULL,
  `token` VARCHAR(191) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 3. Add the two new provider roles (idempotent)
-- ---------------------------------------------------------------------
INSERT IGNORE INTO `roles` (`name`, `guard_name`, `created_at`, `updated_at`) VALUES
('private_doctor',            'web', NOW(), NOW()),
('hospital_administrator',    'web', NOW(), NOW());

-- ---------------------------------------------------------------------
-- 4. Ensure the legacy geography tables exist (in case seeders were skipped)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `states_tb` (
  `stateID` INT(11) NOT NULL,
  `code` CHAR(2) NOT NULL,
  `stateNm` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`stateID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lgas` (
  `LGID` INT(11) NOT NULL,
  `NAME` VARCHAR(50) NOT NULL,
  `STATE_CODE` CHAR(2) DEFAULT NULL,
  `STATEID` INT(11) DEFAULT NULL,
  PRIMARY KEY (`LGID`),
  KEY `lgas_state_code_index` (`STATE_CODE`),
  KEY `lgas_stateid_index` (`STATEID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 5. Register the new migration names in the Laravel migrations table
--    so a subsequent `php artisan migrate` won't try to re-run them.
-- ---------------------------------------------------------------------
INSERT IGNORE INTO `migrations` (`migration`, `batch`) VALUES
('2026_11_01_000001_create_public_portal_tables', 7),
('2026_11_01_000002_create_legacy_geography_tables', 7),
('2026_11_01_000003_create_staff_messages_and_cashier_tasks_tables', 7);

-- ---------------------------------------------------------------------
-- 6. v2.2 enhancement tables — staff_messages + cashier_tasks
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `staff_messages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hospital_id` BIGINT UNSIGNED DEFAULT NULL,
  `sender_id` BIGINT UNSIGNED NOT NULL,
  `recipient_id` BIGINT UNSIGNED DEFAULT NULL,
  `parent_id` BIGINT UNSIGNED DEFAULT NULL,
  `subject` VARCHAR(191) DEFAULT NULL,
  `body` LONGTEXT NOT NULL,
  `priority` ENUM('Normal','Important','Urgent') NOT NULL DEFAULT 'Normal',
  `status` ENUM('sent','delivered','read','archived') NOT NULL DEFAULT 'sent',
  `read_at` TIMESTAMP NULL DEFAULT NULL,
  `archived_at` TIMESTAMP NULL DEFAULT NULL,
  `attachments` JSON DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_messages_recipient_read_index` (`recipient_id`, `read_at`),
  KEY `staff_messages_sender_created_index` (`sender_id`, `created_at`),
  CONSTRAINT `staff_messages_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE SET NULL,
  CONSTRAINT `staff_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `staff_messages_recipient_id_foreign` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `staff_messages_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `staff_messages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cashier_tasks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_number` VARCHAR(191) NOT NULL,
  `hospital_id` BIGINT UNSIGNED DEFAULT NULL,
  `patient_id` BIGINT UNSIGNED DEFAULT NULL,
  `encounter_id` BIGINT UNSIGNED DEFAULT NULL,
  `appointment_id` BIGINT UNSIGNED DEFAULT NULL,
  `source_type` VARCHAR(191) NOT NULL,
  `source_id` BIGINT UNSIGNED DEFAULT NULL,
  `description` VARCHAR(191) NOT NULL,
  `amount_due` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `amount_paid` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `status` ENUM('pending','in_progress','paid','cancelled','refunded','short_paid') NOT NULL DEFAULT 'pending',
  `assigned_cashier_id` BIGINT UNSIGNED DEFAULT NULL,
  `assigned_at` TIMESTAMP NULL DEFAULT NULL,
  `paid_at` TIMESTAMP NULL DEFAULT NULL,
  `payment_method` VARCHAR(191) DEFAULT NULL,
  `receipt_number` VARCHAR(191) DEFAULT NULL,
  `invoice_id` BIGINT UNSIGNED DEFAULT NULL,
  `patient_receipt_id` BIGINT UNSIGNED DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cashier_tasks_task_number_unique` (`task_number`),
  KEY `cashier_tasks_hospital_status_index` (`hospital_id`, `status`),
  KEY `cashier_tasks_patient_status_index` (`patient_id`, `status`),
  KEY `cashier_tasks_source_index` (`source_type`, `source_id`),
  CONSTRAINT `cashier_tasks_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cashier_tasks_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cashier_tasks_encounter_id_foreign` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cashier_tasks_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cashier_tasks_assigned_cashier_id_foreign` FOREIGN KEY (`assigned_cashier_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cashier_tasks_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cashier_tasks_patient_receipt_id_foreign` FOREIGN KEY (`patient_receipt_id`) REFERENCES `patient_receipts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 7. Add must_change_password, default_password, active_role to users
-- ---------------------------------------------------------------------
SET @s = CONCAT('ALTER TABLE `users` ADD COLUMN `must_change_password` TINYINT(1) NOT NULL DEFAULT 0 AFTER `password`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'must_change_password');
SET @sql = IF(@c = 0, @s, 'SELECT ''users.must_change_password already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = CONCAT('ALTER TABLE `users` ADD COLUMN `default_password` VARCHAR(191) NULL AFTER `must_change_password`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'default_password');
SET @sql = IF(@c = 0, @s, 'SELECT ''users.default_password already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = CONCAT('ALTER TABLE `users` ADD COLUMN `active_role` VARCHAR(191) NULL AFTER `default_password`');
SET @c = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'active_role');
SET @sql = IF(@c = 0, @s, 'SELECT ''users.active_role already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Register the new migration
INSERT IGNORE INTO `migrations` (`migration`, `batch`) VALUES
('2026_11_01_000004_add_password_and_role_columns_to_users_table', 7);

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
