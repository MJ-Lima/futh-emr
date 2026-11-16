-- ============================================================================
-- FUTH-LAFIA-EMR  —  Database Dump (fulth-emr.sql)
-- Federal University Teaching Hospital, Lafia — Electronic Medical Record
-- Laravel 12 + Filament v5 + Spatie Permission
-- ----------------------------------------------------------------------------
-- Schema version:   1.0
-- Generated:        2026-07-31T09:57:30Z
-- Engine:           InnoDB / utf8mb4
-- Standards:        ICD-10, LOINC, ATC/DICOM, ISO 3166-2:NG
-- ============================================================================
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+01:00";



-- ---------------------------------------------------------------------------
-- Table: users
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` INT NOT NULL DEFAULT 1,
    `department_id` INT NOT NULL DEFAULT 1,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `status` ENUM('active','inactive','suspended','locked') NOT NULL DEFAULT 'active',
    `last_login_at` TIMESTAMP NULL DEFAULT NULL,
    `last_login_ip` VARCHAR(45) NULL DEFAULT NULL,
    `remember_token` VARCHAR(100) NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `hospital_id`, `department_id`, `name`, `email`, `password`, `status`, `created_at`, `updated_at`) VALUES
  (1,1,1,'System Administrator','admin@futhlafia.gov.ng','$2y$12$wH8yR9oKlqJzN1vYpXmCeOuGmF6dEsRbT0nQaZcVxYwLiHkMpNsRq','active','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,1,'Okoro Chukwuemeka','dr.okoro@futhlafia.gov.ng','$2y$12$wH8yR9oKlqJzN1vYpXmCeOuGmF6dEsRbT0nQaZcVxYwLiHkMpNsRq','active','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,4,'Adebayo Funmilayo','dr.adebayo@futhlafia.gov.ng','$2y$12$wH8yR9oKlqJzN1vYpXmCeOuGmF6dEsRbT0nQaZcVxYwLiHkMpNsRq','active','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,1,1,'John Bala','nurse.john@futhlafia.gov.ng','$2y$12$wH8yR9oKlqJzN1vYpXmCeOuGmF6dEsRbT0nQaZcVxYwLiHkMpNsRq','active','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,1,8,'Musa Ibrahim','pharm.musa@futhlafia.gov.ng','$2y$12$wH8yR9oKlqJzN1vYpXmCeOuGmF6dEsRbT0nQaZcVxYwLiHkMpNsRq','active','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,1,6,'Aisha Yusuf','lab.aisha@futhlafia.gov.ng','$2y$12$wH8yR9oKlqJzN1vYpXmCeOuGmF6dEsRbT0nQaZcVxYwLiHkMpNsRq','active','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,1,7,'Tunde Okafor','rad.tunde@futhlafia.gov.ng','$2y$12$wH8yR9oKlqJzN1vYpXmCeOuGmF6dEsRbT0nQaZcVxYwLiHkMpNsRq','active','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,1,1,'Hauwa Sani','records.hauwa@futhlafia.gov.ng','$2y$12$wH8yR9oKlqJzN1vYpXmCeOuGmF6dEsRbT0nQaZcVxYwLiHkMpNsRq','active','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (9,1,1,'David Eze','accounts.david@futhlafia.gov.ng','$2y$12$wH8yR9oKlqJzN1vYpXmCeOuGmF6dEsRbT0nQaZcVxYwLiHkMpNsRq','active','2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: password_reset_tokens
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: sessions
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `ip_address` VARCHAR(45) NULL DEFAULT NULL,
    `user_agent` TEXT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `sessions_user_id_index` (`user_id`),
    INDEX `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: cache
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: cache_locks
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: jobs
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL DEFAULT NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: job_batches
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
    `id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT NULL,
    `cancelled_at` INT NULL DEFAULT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: failed_jobs
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(255) NOT NULL UNIQUE,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: hospitals
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `hospitals`;
CREATE TABLE `hospitals` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(255) NOT NULL UNIQUE,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `hospitals` (`id`, `name`, `code`, `status`, `created_at`, `updated_at`) VALUES
  (1,'Federal University Teaching Hospital, Lafia','FUTH-LF',1,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: departments
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `departments_hospital_id_code_unique` (`hospital_id`,`code`),
    INDEX `departments_hospital_id_index` (`hospital_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `departments` (`id`, `hospital_id`, `name`, `code`, `description`, `status`, `created_at`, `updated_at`) VALUES
  (1,1,'General Medicine','GM','Internal medicine & general practice',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,'Surgery','SRG','General & specialist surgery',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,'Paediatrics','PAED','Child health',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,1,'Obstetrics & Gynaecology','OBGYN','Maternal & women health',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,1,'Accident & Emergency','A&E','Emergency medicine',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,1,'Laboratory','LAB','Clinical laboratory services',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,1,'Radiology','RAD','Imaging services',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,1,'Pharmacy','PHARM','Pharmaceutical services',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (9,1,'Psychiatry','PSY','Mental health',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (10,1,'Ophthalmology','OPH','Eye care',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (11,1,'ENT','ENT','Ear, nose, throat',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (12,1,'Dental','DENT','Dental services',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (13,1,'Physiotherapy','PHY','Rehabilitation',1,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: clinics
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `clinics`;
CREATE TABLE `clinics` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(255) NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `clinics_hospital_id_code_unique` (`hospital_id`,`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `clinics` (`id`, `hospital_id`, `name`, `code`, `status`, `created_at`, `updated_at`) VALUES
  (1,1,'General Outpatient Clinic','GOP',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,'Antenatal Clinic','ANC',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,'Immunisation Clinic','IMM',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,1,'HIV/ART Clinic','ART',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,1,'TB Clinic','TB',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,1,'Hypertension Clinic','HTN',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,1,'Diabetes Clinic','DM',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,1,'Surgical Clinic','SURG',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (9,1,'Paediatric Clinic','PAED',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (10,1,'Mental Health Clinic','PSY',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (11,1,'Eye Clinic','EYE',1,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: staff
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `department_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `clinic_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `staff_number` VARCHAR(255) NOT NULL UNIQUE,
    `title` VARCHAR(255) NULL,
    `first_name` VARCHAR(255) NOT NULL,
    `middle_name` VARCHAR(255) NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `gender` ENUM('Male','Female') NOT NULL,
    `date_of_birth` DATE NULL,
    `phone` VARCHAR(255) NULL,
    `address` TEXT NULL,
    `designation` VARCHAR(255) NULL,
    `staff_type` ENUM('Doctor','Nurse','Pharmacist','Laboratory Scientist','Radiographer','Physiotherapist','Administrator','Accountant','ICT','Medical Records','Other') NOT NULL,
    `passport` VARCHAR(255) NULL,
    `signature` VARCHAR(255) NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `staff_user_id_unique` (`user_id`),
    INDEX `staff_staff_number_index` (`staff_number`),
    INDEX `staff_last_name_index` (`last_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `staff` (`id`, `user_id`, `hospital_id`, `department_id`, `clinic_id`, `staff_number`, `title`, `first_name`, `middle_name`, `last_name`, `gender`, `date_of_birth`, `phone`, `address`, `designation`, `staff_type`, `passport`, `signature`, `status`, `created_at`, `updated_at`) VALUES
  (1,1,1,1,NULL,'FUTH/STAFF/0001','Mr.','System','','Administrator','Male',NULL,'+2348033000001','Lafia, Nasarawa','ICT Lead','Administrator',NULL,NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,2,1,1,NULL,'FUTH/STAFF/0002','Dr.','Okoro','','Chukwuemeka','Male',NULL,'+2348033000002','Lafia, Nasarawa','Consultant Physician','Doctor',NULL,NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,3,1,4,NULL,'FUTH/STAFF/0003','Dr.','Adebayo','','Funmilayo','Female',NULL,'+2348033000003','Lafia, Nasarawa','Senior Registrar Obs & Gyn','Doctor',NULL,NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,4,1,1,NULL,'FUTH/STAFF/0004','Mr.','John','','Bala','Male',NULL,'+2348033000004','Lafia, Nasarawa','Principal Nursing Officer','Nurse',NULL,NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,5,1,8,NULL,'FUTH/STAFF/0005','Pharm.','Musa','','Ibrahim','Male',NULL,'+2348033000005','Lafia, Nasarawa','Chief Pharmacist','Pharmacist',NULL,NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,6,1,6,NULL,'FUTH/STAFF/0006','Mrs.','Aisha','','Yusuf','Female',NULL,'+2348033000006','Lafia, Nasarawa','Senior Lab Scientist','Laboratory Scientist',NULL,NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,7,1,7,NULL,'FUTH/STAFF/0007','Mr.','Tunde','','Okafor','Male',NULL,'+2348033000007','Lafia, Nasarawa','Senior Radiographer','Radiographer',NULL,NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,8,1,1,NULL,'FUTH/STAFF/0008','Mrs.','Hauwa','','Sani','Female',NULL,'+2348033000008','Lafia, Nasarawa','Records Officer','Medical Records',NULL,NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (9,9,1,1,NULL,'FUTH/STAFF/0009','Mr.','David','','Eze','Male',NULL,'+2348033000009','Lafia, Nasarawa','Finance Officer','Accountant',NULL,NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: patients
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `patients`;
CREATE TABLE `patients` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `patientcode` VARCHAR(200) NOT NULL UNIQUE,
    `hospital_id` INT NULL,
    `firstname` VARCHAR(200) NOT NULL,
    `middlename` VARCHAR(200) NOT NULL,
    `lastname` VARCHAR(200) NOT NULL,
    `dob` DATE NOT NULL,
    `gender` VARCHAR(7) NOT NULL,
    `bloodgroup` VARCHAR(7) NULL,
    `genotype` VARCHAR(2) NULL,
    `marital` VARCHAR(20) NULL,
    `religion` VARCHAR(200) NULL,
    `occupation` VARCHAR(255) NULL,
    `phonemain` VARCHAR(13) NOT NULL,
    `phonealt` VARCHAR(13) NULL,
    `email` VARCHAR(250) NULL,
    `address` TEXT NOT NULL,
    `city` VARCHAR(250) NOT NULL,
    `state` VARCHAR(250) NOT NULL,
    `country` VARCHAR(250) NOT NULL,
    `postalcode` VARCHAR(12) NULL,
    `nokname` VARCHAR(250) NOT NULL,
    `relationship` VARCHAR(250) NOT NULL,
    `nokphone` VARCHAR(250) NOT NULL,
    `nokaddress` TEXT NOT NULL,
    `hereabout` VARCHAR(250) NULL,
    `notes` VARCHAR(250) NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `patients` (`id`, `patientcode`, `hospital_id`, `firstname`, `middlename`, `lastname`, `dob`, `gender`, `bloodgroup`, `genotype`, `marital`, `religion`, `occupation`, `phonemain`, `phonealt`, `email`, `address`, `city`, `state`, `country`, `postalcode`, `nokname`, `relationship`, `nokphone`, `nokaddress`, `hereabout`, `notes`, `status`, `created_at`, `updated_at`) VALUES
  (1,'FUTH-LF-2026-000001',1,'Aisha','','Mohammed','1990-03-12','female','A+','AA','married','Christianity','Trader','08030000001',NULL,NULL,'Lafia, Nasarawa State','Lafia','Nasarawa','Nigeria',NULL,'Family Member','Spouse','08030000099','Lafia, Nasarawa State',NULL,NULL,1,'2026-07-30 12:00:00','2026-07-30 12:00:00'),
  (2,'FUTH-LF-2026-000002',1,'Chidi','','Eze','1985-06-22','male','O+','AS','married','Christianity','Trader','08030000002',NULL,NULL,'Akwanga, Nasarawa State','Akwanga','Nasarawa','Nigeria',NULL,'Family Member','Spouse','08030000099','Akwanga, Nasarawa State',NULL,NULL,1,'2026-07-29 12:00:00','2026-07-29 12:00:00'),
  (3,'FUTH-LF-2026-000003',1,'Fatima','','Bello','1978-11-04','female','B+','AA','married','Christianity','Trader','08030000003',NULL,NULL,'Keffi, Nasarawa State','Keffi','Nasarawa','Nigeria',NULL,'Family Member','Spouse','08030000099','Keffi, Nasarawa State',NULL,NULL,1,'2026-07-28 12:00:00','2026-07-28 12:00:00'),
  (4,'FUTH-LF-2026-000004',1,'John','','Okafor','1995-09-18','male','AB+','AA','single','Christianity','Trader','08030000004',NULL,NULL,'Lafia, Nasarawa State','Lafia','Nasarawa','Nigeria',NULL,'Family Member','Spouse','08030000099','Lafia, Nasarawa State',NULL,NULL,1,'2026-07-27 12:00:00','2026-07-27 12:00:00'),
  (5,'FUTH-LF-2026-000005',1,'Grace','','Adeyemi','2001-01-30','female','O-','SS','single','Christianity','Trader','08030000005',NULL,NULL,'Doma, Nasarawa State','Doma','Nasarawa','Nigeria',NULL,'Family Member','Spouse','08030000099','Doma, Nasarawa State',NULL,NULL,1,'2026-07-26 12:00:00','2026-07-26 12:00:00'),
  (6,'FUTH-LF-2026-000006',1,'Ibrahim','','Sani','1968-08-14','male','A-','AS','married','Christianity','Trader','08030000006',NULL,NULL,'Lafia, Nasarawa State','Lafia','Nasarawa','Nigeria',NULL,'Family Member','Spouse','08030000099','Lafia, Nasarawa State',NULL,NULL,1,'2026-07-25 12:00:00','2026-07-25 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: appointments
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `appointments`;
CREATE TABLE `appointments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `patient_id` BIGINT UNSIGNED NOT NULL,
    `clinic_id` BIGINT UNSIGNED NOT NULL,
    `provider_id` BIGINT UNSIGNED NULL,
    `appointment_number` VARCHAR(255) NOT NULL UNIQUE,
    `appointment_date` DATE NOT NULL,
    `appointment_time` TIME NOT NULL,
    `estimated_duration` INT UNSIGNED NOT NULL DEFAULT 30,
    `visit_type` ENUM('New','Follow-up','Review','Telemedicine') NOT NULL,
    `priority` ENUM('Routine','Urgent','Emergency') NOT NULL DEFAULT 'Routine',
    `reason_for_visit` TEXT NOT NULL,
    `status` ENUM('Scheduled','Confirmed','Checked In','Completed','Cancelled','No Show') NOT NULL DEFAULT 'Scheduled',
    `encounter_id` BIGINT UNSIGNED NULL,
    `created_by` BIGINT UNSIGNED NOT NULL,
    `notes` TEXT NULL,
    `cancellation_reason` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `appointments` (`id`, `hospital_id`, `patient_id`, `clinic_id`, `provider_id`, `appointment_number`, `appointment_date`, `appointment_time`, `estimated_duration`, `visit_type`, `priority`, `reason_for_visit`, `status`, `encounter_id`, `created_by`, `notes`, `cancellation_reason`, `created_at`, `updated_at`) VALUES
  (1,1,1,1,2,'FUTH-LF-APPT-2026-000001','2026-07-31','09:00:00',30,'New','Routine','Follow-up consultation','Scheduled',NULL,2,NULL,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,2,1,2,'FUTH-LF-APPT-2026-000002','2026-07-31','10:00:00',30,'New','Routine','Follow-up consultation','Scheduled',NULL,2,NULL,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,3,1,2,'FUTH-LF-APPT-2026-000003','2026-07-31','11:00:00',30,'New','Routine','Follow-up consultation','Scheduled',NULL,2,NULL,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,1,4,1,2,'FUTH-LF-APPT-2026-000004','2026-07-31','12:00:00',30,'New','Routine','Follow-up consultation','Scheduled',NULL,2,NULL,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: encounters
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `encounters`;
CREATE TABLE `encounters` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `patient_id` BIGINT UNSIGNED NOT NULL,
    `clinic_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `encounter_number` VARCHAR(255) NOT NULL UNIQUE,
    `visit_type` ENUM('Outpatient','Emergency','Inpatient','Telemedicine','Follow-up') NOT NULL,
    `encounter_date` DATE NOT NULL,
    `encounter_time` TIME NOT NULL,
    `status` ENUM('Waiting','In Progress','Completed','Cancelled') NOT NULL DEFAULT 'Waiting',
    `chief_complaint` TEXT NULL,
    `referred_by` VARCHAR(255) NULL,
    `appointment_id` BIGINT UNSIGNED NULL,
    `created_by` BIGINT UNSIGNED NOT NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `encounters` (`id`, `hospital_id`, `patient_id`, `clinic_id`, `user_id`, `encounter_number`, `visit_type`, `encounter_date`, `encounter_time`, `status`, `chief_complaint`, `referred_by`, `appointment_id`, `created_by`, `notes`, `created_at`, `updated_at`) VALUES
  (1,1,1,1,2,'FUTH-LF-ENC-2026-000001','Outpatient','2026-07-31','10:30:00','In Progress','Fever and headache for 3 days',NULL,1,2,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,2,1,2,'FUTH-LF-ENC-2026-000002','Outpatient','2026-07-31','11:30:00','In Progress','Fever and headache for 3 days',NULL,2,2,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,3,1,2,'FUTH-LF-ENC-2026-000003','Outpatient','2026-07-31','12:30:00','In Progress','Fever and headache for 3 days',NULL,3,2,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,1,4,1,2,'FUTH-LF-ENC-2026-000004','Outpatient','2026-07-31','13:30:00','In Progress','Fever and headache for 3 days',NULL,4,2,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,1,5,1,2,'FUTH-LF-ENC-2026-000005','Outpatient','2026-07-31','14:30:00','In Progress','Fever and headache for 3 days',NULL,NULL,2,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,1,6,1,2,'FUTH-LF-ENC-2026-000006','Outpatient','2026-07-31','15:30:00','In Progress','Fever and headache for 3 days',NULL,NULL,2,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: clinical_notes
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `clinical_notes`;
CREATE TABLE `clinical_notes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `patient_id` BIGINT UNSIGNED NOT NULL,
    `encounter_id` BIGINT UNSIGNED NULL,
    `author_id` BIGINT UNSIGNED NOT NULL,
    `note_number` VARCHAR(255) NOT NULL UNIQUE,
    `note_type` ENUM('Progress','Admission','Discharge','Nursing','Procedure','Consultation','Referral','Death','Other') NOT NULL DEFAULT 'Progress',
    `title` VARCHAR(255) NULL,
    `subjective` LONGTEXT NULL,
    `objective` LONGTEXT NULL,
    `assessment` LONGTEXT NULL,
    `plan` LONGTEXT NULL,
    `narrative` LONGTEXT NULL,
    `tags` JSON NULL,
    `is_signed` TINYINT(1) NOT NULL DEFAULT 0,
    `signed_at` TIMESTAMP NULL DEFAULT NULL,
    `signed_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `clinical_notes_patient_id_created_at_index` (`patient_id`,`created_at`),
    INDEX `clinical_notes_encounter_id_note_type_index` (`encounter_id`,`note_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `clinical_notes` (`id`, `hospital_id`, `patient_id`, `encounter_id`, `author_id`, `note_number`, `note_type`, `title`, `subjective`, `objective`, `assessment`, `plan`, `narrative`, `tags`, `is_signed`, `signed_at`, `signed_by`, `created_at`, `updated_at`) VALUES
  (1,1,1,1,2,'FUTH-LF-CN-2026-000001','Progress','Day 1 progress note','Patient complains of fever (3 days), headache, body pains. No vomiting, no diarrhoea.','T 38.4°C, BP 120/80, HR 88, RR 18, SpO2 98%. Acutely ill-looking, no pallor.','Suspected malaria. Differential: typhoid fever, URTI.','Send MP+RDT, FBC. Start artemether/lumefantrine. Paracetamol for fever. Review in 48h.',NULL,NULL,1,'2026-07-31 12:00:00',2,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,2,2,2,'FUTH-LF-CN-2026-000002','Progress','Day 1 progress note','Patient complains of fever (3 days), headache, body pains. No vomiting, no diarrhoea.','T 38.4°C, BP 120/80, HR 88, RR 18, SpO2 98%. Acutely ill-looking, no pallor.','Suspected malaria. Differential: typhoid fever, URTI.','Send MP+RDT, FBC. Start artemether/lumefantrine. Paracetamol for fever. Review in 48h.',NULL,NULL,1,'2026-07-31 12:00:00',2,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,3,3,2,'FUTH-LF-CN-2026-000003','Progress','Day 1 progress note','Patient complains of fever (3 days), headache, body pains. No vomiting, no diarrhoea.','T 38.4°C, BP 120/80, HR 88, RR 18, SpO2 98%. Acutely ill-looking, no pallor.','Suspected malaria. Differential: typhoid fever, URTI.','Send MP+RDT, FBC. Start artemether/lumefantrine. Paracetamol for fever. Review in 48h.',NULL,NULL,1,'2026-07-31 12:00:00',2,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,1,4,4,2,'FUTH-LF-CN-2026-000004','Progress','Day 1 progress note','Patient complains of fever (3 days), headache, body pains. No vomiting, no diarrhoea.','T 38.4°C, BP 120/80, HR 88, RR 18, SpO2 98%. Acutely ill-looking, no pallor.','Suspected malaria. Differential: typhoid fever, URTI.','Send MP+RDT, FBC. Start artemether/lumefantrine. Paracetamol for fever. Review in 48h.',NULL,NULL,1,'2026-07-31 12:00:00',2,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,1,5,5,2,'FUTH-LF-CN-2026-000005','Progress','Day 1 progress note','Patient complains of fever (3 days), headache, body pains. No vomiting, no diarrhoea.','T 38.4°C, BP 120/80, HR 88, RR 18, SpO2 98%. Acutely ill-looking, no pallor.','Suspected malaria. Differential: typhoid fever, URTI.','Send MP+RDT, FBC. Start artemether/lumefantrine. Paracetamol for fever. Review in 48h.',NULL,NULL,1,'2026-07-31 12:00:00',2,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,1,6,6,2,'FUTH-LF-CN-2026-000006','Progress','Day 1 progress note','Patient complains of fever (3 days), headache, body pains. No vomiting, no diarrhoea.','T 38.4°C, BP 120/80, HR 88, RR 18, SpO2 98%. Acutely ill-looking, no pallor.','Suspected malaria. Differential: typhoid fever, URTI.','Send MP+RDT, FBC. Start artemether/lumefantrine. Paracetamol for fever. Review in 48h.',NULL,NULL,1,'2026-07-31 12:00:00',2,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: vitals
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `vitals`;
CREATE TABLE `vitals` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `patient_id` BIGINT UNSIGNED NOT NULL,
    `encounter_id` BIGINT UNSIGNED NULL,
    `recorded_by` BIGINT UNSIGNED NOT NULL,
    `recorded_at` DATETIME NOT NULL,
    `height_cm` DECIMAL(5,2) NULL,
    `weight_kg` DECIMAL(5,2) NULL,
    `bmi` DECIMAL(5,2) NULL,
    `muac_cm` DECIMAL(5,2) NULL,
    `systolic_bp` SMALLINT UNSIGNED NULL,
    `diastolic_bp` SMALLINT UNSIGNED NULL,
    `heart_rate` SMALLINT UNSIGNED NULL,
    `bp_position` ENUM('Sitting','Standing','Supine','Unknown') NOT NULL DEFAULT 'Unknown',
    `respiratory_rate` SMALLINT UNSIGNED NULL,
    `spo2` SMALLINT UNSIGNED NULL,
    `temperature_c` DECIMAL(4,2) NULL,
    `temperature_route` ENUM('Oral','Axillary','Tympanic','Rectal','Forehead') NOT NULL DEFAULT 'Oral',
    `pain_score` SMALLINT UNSIGNED NULL,
    `glasgow_coma_score` VARCHAR(8) NULL,
    `blood_glucose_mmol_l` DECIMAL(5,2) NULL,
    `glucose_fasting` ENUM('Fasting','Random','Postprandial','Unknown') NOT NULL DEFAULT 'Unknown',
    `fetal_heart_rate` SMALLINT UNSIGNED NULL,
    `fundal_height_cm` DECIMAL(5,2) NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `vitals_patient_id_recorded_at_index` (`patient_id`,`recorded_at`),
    INDEX `vitals_encounter_id_index` (`encounter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `vitals` (`id`, `hospital_id`, `patient_id`, `encounter_id`, `recorded_by`, `recorded_at`, `height_cm`, `weight_kg`, `bmi`, `muac_cm`, `systolic_bp`, `diastolic_bp`, `heart_rate`, `bp_position`, `respiratory_rate`, `spo2`, `temperature_c`, `temperature_route`, `pain_score`, `glasgow_coma_score`, `blood_glucose_mmol_l`, `glucose_fasting`, `fetal_heart_rate`, `fundal_height_cm`, `notes`, `created_at`, `updated_at`) VALUES
  (1,1,1,1,2,'2026-07-31 12:00:00',165,65,23.88,NULL,120,80,88,'Sitting',18,98,38.4,'Oral',3,'15',NULL,'Unknown',NULL,NULL,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,2,2,2,'2026-07-31 12:00:00',165,65,23.88,NULL,120,80,88,'Sitting',18,98,38.4,'Oral',3,'15',NULL,'Unknown',NULL,NULL,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,3,3,2,'2026-07-31 12:00:00',165,65,23.88,NULL,120,80,88,'Sitting',18,98,38.4,'Oral',3,'15',NULL,'Unknown',NULL,NULL,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,1,4,4,2,'2026-07-31 12:00:00',165,65,23.88,NULL,120,80,88,'Sitting',18,98,38.4,'Oral',3,'15',NULL,'Unknown',NULL,NULL,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,1,5,5,2,'2026-07-31 12:00:00',165,65,23.88,NULL,120,80,88,'Sitting',18,98,38.4,'Oral',3,'15',NULL,'Unknown',NULL,NULL,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,1,6,6,2,'2026-07-31 12:00:00',165,65,23.88,NULL,120,80,88,'Sitting',18,98,38.4,'Oral',3,'15',NULL,'Unknown',NULL,NULL,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: allergies
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `allergies`;
CREATE TABLE `allergies` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `patient_id` BIGINT UNSIGNED NOT NULL,
    `recorded_by` BIGINT UNSIGNED NOT NULL,
    `encounter_id` BIGINT UNSIGNED NULL,
    `substance` VARCHAR(255) NOT NULL,
    `code` VARCHAR(255) NULL,
    `category` ENUM('Medication','Food','Environment','Biologic','Other') NOT NULL DEFAULT 'Medication',
    `criticality` ENUM('Low','High','Unable to assess') NOT NULL DEFAULT 'Low',
    `severity` ENUM('Mild','Moderate','Severe','Fatal') NOT NULL DEFAULT 'Mild',
    `reaction` ENUM('Rash','Urticaria','Anaphylaxis','Angioedema','Bronchospasm','GI upset','Hepatotoxicity','Stevens-Johnson','Other') NOT NULL DEFAULT 'Rash',
    `reaction_description` TEXT NULL,
    `onset_date` DATE NULL,
    `notes` TEXT NULL,
    `status` ENUM('Active','Resolved','Inactive') NOT NULL DEFAULT 'Active',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `allergies_patient_id_status_index` (`patient_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `allergies` (`id`, `patient_id`, `recorded_by`, `encounter_id`, `substance`, `code`, `category`, `criticality`, `severity`, `reaction`, `reaction_description`, `onset_date`, `notes`, `status`, `created_at`, `updated_at`) VALUES
  (1,1,2,1,'Sulfonamides',NULL,'Medication','High','Moderate','Rash','Generalised maculopapular rash after 3 days of cotrimoxazole','2024-05-12','Avoid all sulfonamide antibiotics','Active','2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: diagnoses
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `diagnoses`;
CREATE TABLE `diagnoses` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `patient_id` BIGINT UNSIGNED NOT NULL,
    `encounter_id` BIGINT UNSIGNED NULL,
    `recorded_by` BIGINT UNSIGNED NOT NULL,
    `icd10_code` VARCHAR(10) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `type` ENUM('Principal','Admitting','Working','Discharge','Secondary','Comorbidity') NOT NULL DEFAULT 'Working',
    `status` ENUM('Active','Resolved','Chronic','Recurrent') NOT NULL DEFAULT 'Active',
    `onset_date` DATE NULL,
    `resolved_date` DATE NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `diagnoses_patient_id_status_index` (`patient_id`,`status`),
    INDEX `diagnoses_icd10_code_index` (`icd10_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `diagnoses` (`id`, `patient_id`, `encounter_id`, `recorded_by`, `icd10_code`, `description`, `type`, `status`, `onset_date`, `resolved_date`, `notes`, `created_at`, `updated_at`) VALUES
  (1,1,1,2,'B54','Unspecified malaria','Working','Active','2026-07-28',NULL,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: drug_categories
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `drug_categories`;
CREATE TABLE `drug_categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `code` VARCHAR(30) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `drug_categories` (`id`, `name`, `code`, `description`, `status`, `created_at`, `updated_at`) VALUES
  (1,'Nervous system','N','Analgesics, anaesthetics, anticonvulsants',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,'Antiinfectives for systemic use','J','Antibiotics, antivirals, antifungals, antimalarials',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,'Cardiovascular system','C','Antihypertensives, cardiac drugs',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,'Alimentary tract and metabolism','A','Antacids, antidiarrhoeals, antidiabetics',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,'Respiratory system','R','Bronchodilators, antihistamines',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,'Musculo-skeletal system','M','NSAIDs, muscle relaxants',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,'Genito urinary system','G','Diuretics, hormonal contraceptives',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,'Systemic hormonalonal preparations','H','Corticosteroids',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (9,'Dermatologicals','D','Topical antifungals, antiseptics',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (10,'Antiparasitic products','P','Antimalarials, antihelminthics',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (11,'Various','V','Nutrients, electrolytes, vitamins',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (12,'Sensory organs','S','Ophthalmic, otic preparations',1,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: drugs
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `drugs`;
CREATE TABLE `drugs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `drug_category_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(255) NOT NULL,
    `generic_name` VARCHAR(255) NULL,
    `code` VARCHAR(255) NOT NULL UNIQUE,
    `atc_code` VARCHAR(20) NULL,
    `dosage_form` VARCHAR(255) NULL,
    `strength` VARCHAR(255) NULL,
    `unit_of_measure` VARCHAR(255) NULL,
    `manufacturer` VARCHAR(255) NULL,
    `supplier` VARCHAR(255) NULL,
    `cost_price` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `selling_price` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `reorder_level` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `reorder_quantity` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `is_controlled` TINYINT(1) NOT NULL DEFAULT 0,
    `is_antenatal_safe` TINYINT(1) NOT NULL DEFAULT 0,
    `is_pediatric_safe` TINYINT(1) NOT NULL DEFAULT 0,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `drugs_name_index` (`name`),
    INDEX `drugs_generic_name_index` (`generic_name`),
    INDEX `drugs_atc_code_index` (`atc_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `drugs` (`id`, `hospital_id`, `drug_category_id`, `name`, `generic_name`, `code`, `atc_code`, `dosage_form`, `strength`, `unit_of_measure`, `manufacturer`, `supplier`, `cost_price`, `selling_price`, `reorder_level`, `reorder_quantity`, `is_controlled`, `is_antenatal_safe`, `is_pediatric_safe`, `status`, `created_at`, `updated_at`) VALUES
  (1,1,1,'Paracetamol Tab','Paracetamol','DRG-001','N02BE01','Tablet','500 mg','tablet',NULL,NULL,5,10,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,6,'Ibuprofen Tab','Ibuprofen','DRG-002','M01AE01','Tablet','400 mg','tablet',NULL,NULL,8,15,100,500,0,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,1,'Aspirin Tab','Acetylsalicylic acid','DRG-003','N02BA01','Tablet','75 mg','tablet',NULL,NULL,4,8,100,500,0,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,1,6,'Diclofenac Tab','Diclofenac sodium','DRG-004','M01AB05','Tablet','50 mg','tablet',NULL,NULL,7,14,100,500,0,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,1,2,'Amoxicillin Cap','Amoxicillin','DRG-005','J01CA04','Capsule','500 mg','capsule',NULL,NULL,12,25,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,1,2,'Amoxicillin Susp','Amoxicillin','DRG-006','J01CA04','Suspension','125 mg/5 mL','bottle',NULL,NULL,80,150,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,1,2,'Cotrimoxazole Tab','Co-trimoxazole','DRG-007','J01EE01','Tablet','480 mg','tablet',NULL,NULL,6,12,100,500,0,0,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,1,2,'Metronidazole Tab','Metronidazole','DRG-008','J01XD01','Tablet','400 mg','tablet',NULL,NULL,5,11,100,500,0,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (9,1,10,'Artemether/Lumefantrine','Artemether + Lumefantrine','DRG-009','P01BF02','Tablet','20/120 mg','tablet',NULL,NULL,50,100,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (10,1,10,'Artesunate Inject','Artesunate','DRG-010','P01BE03','Injection','60 mg/mL','vial',NULL,NULL,150,300,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (11,1,10,'Chloroquine Tab','Chloroquine phosphate','DRG-011','P01BA01','Tablet','250 mg','tablet',NULL,NULL,8,15,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (12,1,10,'Sulphadoxine/Pyrimethamine','Sulfadoxine+Pyrimethamine','DRG-012','P01BF01','Tablet','500/25 mg','tablet',NULL,NULL,25,50,100,500,0,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (13,1,10,'Amodiaquine Tab','Amodiaquine','DRG-013','P01BA06','Tablet','200 mg','tablet',NULL,NULL,9,18,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (14,1,2,'Zidovudine/Lamivudine Tab','Zidovudine + Lamivudine','DRG-014','J05AR01','Tablet','300/150 mg','tablet',NULL,NULL,80,0,100,500,0,1,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (15,1,2,'Tenofovir/Lamivudine/Dolutegravir','Tenofovir + Lamivudine + Dolutegravir','DRG-015','J05AR18','Tablet','300/300/50 mg','tablet',NULL,NULL,120,0,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (16,1,2,'Isoniazid Tab','Isoniazid','DRG-016','J04AC01','Tablet','300 mg','tablet',NULL,NULL,4,8,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (17,1,2,'Rifampicin Cap','Rifampicin','DRG-017','J04AB02','Capsule','300 mg','capsule',NULL,NULL,12,25,100,500,0,0,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (18,1,2,'Ethambutol Tab','Ethambutol','DRG-018','J04AK02','Tablet','400 mg','tablet',NULL,NULL,7,14,100,500,0,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (19,1,2,'Pyrazinamide Tab','Pyrazinamide','DRG-019','J04AK03','Tablet','500 mg','tablet',NULL,NULL,9,18,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (20,1,3,'Amlodipine Tab','Amlodipine','DRG-020','C08CA01','Tablet','10 mg','tablet',NULL,NULL,6,13,100,500,0,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (21,1,3,'Lisinopril Tab','Lisinopril','DRG-021','C09AA03','Tablet','10 mg','tablet',NULL,NULL,7,14,100,500,0,1,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (22,1,3,'Atenolol Tab','Atenolol','DRG-022','C07AB03','Tablet','50 mg','tablet',NULL,NULL,5,11,100,500,0,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (23,1,3,'Hydrochlorothiazide','Hydrochlorothiazide','DRG-023','C03AA03','Tablet','25 mg','tablet',NULL,NULL,4,9,100,500,0,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (24,1,3,'Furosemide Tab','Furosemide','DRG-024','C03CA01','Tablet','40 mg','tablet',NULL,NULL,5,11,100,500,0,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (25,1,4,'Metformin Tab','Metformin','DRG-025','A10BA02','Tablet','500 mg','tablet',NULL,NULL,6,13,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (26,1,4,'Glibenclamide Tab','Glibenclamide','DRG-026','A10BB01','Tablet','5 mg','tablet',NULL,NULL,5,11,100,500,0,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (27,1,4,'Insulin (soluble)','Insulin (regular)','DRG-027','A10AB01','Injection','100 IU/mL','vial',NULL,NULL,350,700,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (28,1,5,'Salbutamol Inh','Salbutamol','DRG-028','R03AC02','Inhaler','100 mcg/dose','inhaler',NULL,NULL,350,700,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (29,1,8,'Hydrocortisone Inj','Hydrocortisone','DRG-029','H02AB09','Injection','100 mg','vial',NULL,NULL,80,160,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (30,1,8,'Prednisolone Tab','Prednisolone','DRG-030','H02AB07','Tablet','5 mg','tablet',NULL,NULL,5,11,100,500,0,0,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (31,1,4,'Omeprazole Cap','Omeprazole','DRG-031','A02BC01','Capsule','20 mg','capsule',NULL,NULL,8,17,100,500,0,1,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (32,1,4,'Aluminium/Magnesium Antacid','Al + Mg hydroxide','DRG-032','A02AD10','Suspension','200 mg/5 mL','bottle',NULL,NULL,120,240,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (33,1,4,'Oral Rehydration Salts','ORS','DRG-033','A07CA03','Powder','20.5 g/L','sachet',NULL,NULL,35,70,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (34,1,4,'Zinc Sulphate Tab','Zinc sulfate','DRG-034','A12CX01','Tablet','20 mg','tablet',NULL,NULL,3,7,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (35,1,4,'Ferrous Sulphate Tab','Ferrous sulfate','DRG-035','B03AA07','Tablet','200 mg','tablet',NULL,NULL,4,9,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (36,1,4,'Folic Acid Tab','Folic acid','DRG-036','B03BB01','Tablet','5 mg','tablet',NULL,NULL,2,5,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (37,1,4,'Magnesium Sulfate Inj','Magnesium sulfate','DRG-037','A06AD04','Injection','500 mg/mL','vial',NULL,NULL,60,120,100,500,0,1,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (38,1,8,'Oxytocin Inj','Oxytocin','DRG-038','H01BB02','Injection','10 IU/mL','ampoule',NULL,NULL,80,160,100,500,0,1,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (39,1,1,'Diazepam Inj','Diazepam','DRG-039','N05BA01','Injection','5 mg/mL','ampoule',NULL,NULL,25,50,100,500,1,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (40,1,1,'Phenytoin Tab','Phenytoin','DRG-040','N03AB02','Tablet','100 mg','tablet',NULL,NULL,5,11,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (41,1,1,'Carbamazepine Tab','Carbamazepine','DRG-041','N03AF01','Tablet','200 mg','tablet',NULL,NULL,8,17,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (42,1,1,'Haloperidol Tab','Haloperidol','DRG-042','N05AD01','Tablet','5 mg','tablet',NULL,NULL,7,14,100,500,1,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (43,1,1,'Chlorpromazine Tab','Chlorpromazine','DRG-043','N05AA01','Tablet','100 mg','tablet',NULL,NULL,8,16,100,500,1,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (44,1,3,'Adrenaline Inj','Epinephrine','DRG-044','C01CA24','Injection','1 mg/mL','ampoule',NULL,NULL,70,140,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (45,1,4,'Atropine Inj','Atropine sulfate','DRG-045','A03BA04','Injection','0.6 mg/mL','ampoule',NULL,NULL,35,70,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (46,1,9,'Povidone Iodine Soln','Povidone iodine','DRG-046','D08AG01','Solution','10%','bottle',NULL,NULL,80,160,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (47,1,2,'Gentamicin Inj','Gentamicin','DRG-047','J01GB03','Injection','40 mg/mL','ampoule',NULL,NULL,25,50,100,500,0,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (48,1,2,'Ceftriaxone Inj','Ceftriaxone','DRG-048','J01DD04','Injection','1 g','vial',NULL,NULL,150,300,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (49,1,2,'Ciprofloxacin Tab','Ciprofloxacin','DRG-049','J01MA02','Tablet','500 mg','tablet',NULL,NULL,10,20,100,500,0,0,0,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (50,1,10,'Quinine Tab','Quinine sulfate','DRG-050','P01BC01','Tablet','300 mg','tablet',NULL,NULL,7,14,100,500,0,1,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: drug_batches
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `drug_batches`;
CREATE TABLE `drug_batches` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `drug_id` BIGINT UNSIGNED NOT NULL,
    `batch_number` VARCHAR(255) NOT NULL,
    `expiry_date` DATE NOT NULL,
    `manufactured_date` DATE NULL,
    `quantity_received` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `quantity_remaining` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `cost_price` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `supplier` VARCHAR(255) NULL,
    `received_date` DATE NULL,
    `is_quarantined` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `drug_batches_drug_id_expiry_date_index` (`drug_id`,`expiry_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `drug_batches` (`id`, `drug_id`, `batch_number`, `expiry_date`, `manufactured_date`, `quantity_received`, `quantity_remaining`, `cost_price`, `supplier`, `received_date`, `is_quarantined`, `created_at`, `updated_at`) VALUES
  (1,1,'BATCH-DRG-001','2027-05-08','2025-10-14',1000,312,5,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,2,'BATCH-DRG-002','2027-05-28','2025-08-22',1000,872,8,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,3,'BATCH-DRG-003','2027-09-04','2026-04-10',1000,524,4,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,4,'BATCH-DRG-004','2027-05-15','2025-10-31',1000,152,7,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,5,'BATCH-DRG-005','2027-04-08','2025-08-27',1000,334,12,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,6,'BATCH-DRG-006','2028-06-06','2025-12-20',1000,391,80,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,7,'BATCH-DRG-007','2027-08-13','2025-08-31',1000,709,6,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,8,'BATCH-DRG-008','2028-04-09','2026-01-02',1000,84,5,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (9,9,'BATCH-DRG-009','2027-08-14','2026-03-06',1000,94,50,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (10,10,'BATCH-DRG-010','2027-10-19','2025-12-11',1000,367,150,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (11,11,'BATCH-DRG-011','2027-10-18','2025-09-01',1000,494,8,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (12,12,'BATCH-DRG-012','2027-04-30','2025-12-28',1000,657,25,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (13,13,'BATCH-DRG-013','2027-05-28','2026-03-09',1000,833,9,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (14,14,'BATCH-DRG-014','2027-05-27','2026-04-21',1000,595,80,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (15,15,'BATCH-DRG-015','2027-12-08','2026-02-02',1000,752,120,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (16,16,'BATCH-DRG-016','2027-12-21','2025-11-05',1000,623,4,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (17,17,'BATCH-DRG-017','2027-05-15','2026-01-25',1000,464,12,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (18,18,'BATCH-DRG-018','2028-01-05','2026-04-20',1000,644,7,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (19,19,'BATCH-DRG-019','2027-04-07','2026-01-19',1000,653,9,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (20,20,'BATCH-DRG-020','2028-07-19','2026-04-03',1000,172,6,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (21,21,'BATCH-DRG-021','2028-02-01','2025-08-08',1000,521,7,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (22,22,'BATCH-DRG-022','2027-07-25','2025-10-08',1000,569,5,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (23,23,'BATCH-DRG-023','2028-01-07','2026-02-28',1000,454,4,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (24,24,'BATCH-DRG-024','2027-06-20','2026-01-07',1000,856,5,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (25,25,'BATCH-DRG-025','2027-04-06','2025-09-24',1000,733,6,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (26,26,'BATCH-DRG-026','2028-02-15','2025-08-09',1000,279,5,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (27,27,'BATCH-DRG-027','2027-12-18','2026-02-25',1000,550,350,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (28,28,'BATCH-DRG-028','2027-10-14','2025-08-07',1000,208,350,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (29,29,'BATCH-DRG-029','2027-11-30','2026-03-04',1000,177,80,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (30,30,'BATCH-DRG-030','2027-03-29','2025-09-11',1000,786,5,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (31,31,'BATCH-DRG-031','2027-04-25','2025-10-23',1000,534,8,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (32,32,'BATCH-DRG-032','2028-04-19','2025-09-01',1000,894,120,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (33,33,'BATCH-DRG-033','2027-06-11','2025-12-12',1000,879,35,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (34,34,'BATCH-DRG-034','2028-01-12','2025-10-30',1000,68,3,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (35,35,'BATCH-DRG-035','2028-01-04','2026-01-07',1000,929,4,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (36,36,'BATCH-DRG-036','2028-01-05','2025-10-22',1000,891,2,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (37,37,'BATCH-DRG-037','2028-04-12','2025-09-17',1000,277,60,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (38,38,'BATCH-DRG-038','2028-06-19','2025-10-18',1000,314,80,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (39,39,'BATCH-DRG-039','2027-09-22','2025-11-09',1000,700,25,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (40,40,'BATCH-DRG-040','2028-01-23','2025-11-09',1000,553,5,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (41,41,'BATCH-DRG-041','2027-12-21','2026-03-05',1000,829,8,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (42,42,'BATCH-DRG-042','2027-05-23','2025-12-03',1000,503,7,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (43,43,'BATCH-DRG-043','2028-03-13','2025-09-25',1000,523,8,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (44,44,'BATCH-DRG-044','2027-06-05','2025-12-07',1000,337,70,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (45,45,'BATCH-DRG-045','2027-02-17','2026-02-08',1000,239,35,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (46,46,'BATCH-DRG-046','2028-01-29','2025-08-28',1000,299,80,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (47,47,'BATCH-DRG-047','2028-01-31','2025-12-06',1000,135,25,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (48,48,'BATCH-DRG-048','2028-07-03','2025-11-15',1000,145,150,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (49,49,'BATCH-DRG-049','2028-02-12','2026-04-20',1000,366,10,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (50,50,'BATCH-DRG-050','2027-12-16','2026-02-22',1000,521,7,'FUTH Central Store','2026-05-02',0,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: drug_interactions
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `drug_interactions`;
CREATE TABLE `drug_interactions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `drug_a_id` BIGINT UNSIGNED NOT NULL,
    `drug_b_id` BIGINT UNSIGNED NOT NULL,
    `severity` ENUM('Minor','Moderate','Major','Contraindicated') NOT NULL DEFAULT 'Moderate',
    `effect` ENUM('Additive','Synergistic','Antagonistic','Altered metabolism','Increased toxicity','Decreased efficacy','Other') NOT NULL DEFAULT 'Other',
    `description` TEXT NULL,
    `clinical_action` TEXT NULL,
    `source` VARCHAR(255) NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `drug_interactions_drug_a_id_drug_b_id_unique` (`drug_a_id`,`drug_b_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `drug_interactions` (`id`, `drug_a_id`, `drug_b_id`, `severity`, `effect`, `description`, `clinical_action`, `source`, `status`, `created_at`, `updated_at`) VALUES
  (1,20,22,'Major','Altered metabolism','Amlodipine + Atenolol — additive hypotensive effect; monitor BP closely.','Use with caution, monitor BP','Stockley 2024',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,22,23,'Moderate','Additive','Beta-blocker + Thiazide — additive antihypertensive; risk of bradycardia.','Monitor HR & BP','Medscape',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,2,29,'Moderate','Increased toxicity','NSAID + Corticosteroid — increased GI bleed risk.','Co-prescribe PPI','Stockley 2024',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,39,30,'Major','Increased toxicity','Diazepam + Prednisolone — CNS depression risk.','Avoid co-prescription','Stockley 2024',1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,49,26,'Major','Altered metabolism','Ciprofloxacin + Glibenclamide — hypoglycaemia risk.','Monitor blood glucose','Stockley 2024',1,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: lab_panels
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `lab_panels`;
CREATE TABLE `lab_panels` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(255) NOT NULL UNIQUE,
    `loinc_code` VARCHAR(20) NULL,
    `specimen_type` VARCHAR(255) NULL,
    `container` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `price` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `turnaround_hours` INT UNSIGNED NOT NULL DEFAULT 24,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `lab_panels_loinc_code_index` (`loinc_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lab_panels` (`id`, `hospital_id`, `name`, `code`, `loinc_code`, `specimen_type`, `container`, `description`, `price`, `turnaround_hours`, `status`, `created_at`, `updated_at`) VALUES
  (1,1,'Full Blood Count','FBC','58410-2','Whole blood','EDTA','Complete blood count panel',1500,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,'Liver Function Test','LFT','24221-7','Serum','Plain','Hepatic function panel',2500,6,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,'Renal Function Test','RFT','24360-2','Serum','Plain','Renal function panel',2000,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,1,'Lipid Profile','LIPID','24331-3','Serum','Plain','Lipid panel',2500,6,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,1,'Blood Glucose Panel','GLUC','2345-7','Plasma','Fluoride','Glucose & HbA1c',1200,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,1,'Urinalysis','UA','24356-0','Urine','Sterile','Routine urine examination',500,2,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,1,'Malaria Screen','MAL','10501-5','Whole blood','EDTA','Malaria microscopy & RDT',800,2,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,1,'HIV Screening','HIV','89567-1','Serum','Plain','HIV rapid test',1000,2,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (9,1,'Typhoid Screen','TYPH','22028-7','Serum','Plain','Widal test',1000,2,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (10,1,'Pregnancy Test','PT','2112-1','Urine','Sterile','Urine β-hCG',500,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (11,1,'Sickling Test','SICK','91580-3','Whole blood','EDTA','Sickling + Hb electrophoresis',1200,6,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (12,1,'Cross Match','XM','1156-9','Whole blood','EDTA','Blood group & cross-match',1500,3,1,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: lab_tests
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `lab_tests`;
CREATE TABLE `lab_tests` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `lab_panel_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(255) NOT NULL UNIQUE,
    `loinc_code` VARCHAR(20) NULL,
    `specimen_type` VARCHAR(255) NULL,
    `container` VARCHAR(255) NULL,
    `unit` VARCHAR(255) NULL,
    `reference_range_low` VARCHAR(255) NULL,
    `reference_range_high` VARCHAR(255) NULL,
    `reference_notes` TEXT NULL,
    `description` TEXT NULL,
    `price` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `turnaround_hours` INT UNSIGNED NOT NULL DEFAULT 24,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `lab_tests_loinc_code_index` (`loinc_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lab_tests` (`id`, `hospital_id`, `lab_panel_id`, `name`, `code`, `loinc_code`, `specimen_type`, `container`, `unit`, `reference_range_low`, `reference_range_high`, `reference_notes`, `description`, `price`, `turnaround_hours`, `status`, `created_at`, `updated_at`) VALUES
  (1,1,1,'Haemoglobin','HB','718-7','Whole blood','EDTA','g/dL','11.5','16.5',NULL,NULL,200,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,1,'White Blood Cell Count','WBC','6690-2','Whole blood','EDTA','x10^9/L','4','11',NULL,NULL,200,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,1,'Platelet Count','PLT','777-3','Whole blood','EDTA','x10^9/L','150','450',NULL,NULL,200,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,1,1,'Red Blood Cell Count','RBC','789-8','Whole blood','EDTA','x10^12/L','4.0','5.5',NULL,NULL,200,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,1,1,'PCV / Haematocrit','PCV','4544-3','Whole blood','EDTA','%','36','50',NULL,NULL,200,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,1,2,'ALT','ALT','1742-6','Serum','Plain','U/L','7','40',NULL,NULL,300,6,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,1,2,'AST','AST','1920-8','Serum','Plain','U/L','8','40',NULL,NULL,300,6,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,1,2,'Alkaline Phosphatase','ALP','6768-6','Serum','Plain','U/L','40','130',NULL,NULL,300,6,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (9,1,2,'Total Bilirubin','TBIL','1975-2','Serum','Plain','μmol/L','2','21',NULL,NULL,350,6,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (10,1,2,'Total Protein','TP','2885-2','Serum','Plain','g/L','60','80',NULL,NULL,300,6,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (11,1,2,'Albumin','ALB','1751-7','Serum','Plain','g/L','35','50',NULL,NULL,300,6,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (12,1,3,'Urea','UREA','3094-0','Serum','Plain','mmol/L','2.5','7.0',NULL,NULL,300,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (13,1,3,'Creatinine','CREAT','2160-0','Serum','Plain','μmol/L','60','120',NULL,NULL,300,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (14,1,3,'Sodium','NA','2951-2','Serum','Plain','mmol/L','135','145',NULL,NULL,300,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (15,1,3,'Potassium','K','2823-3','Serum','Plain','mmol/L','3.5','5.0',NULL,NULL,300,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (16,1,3,'Bicarbonate','HCO3','1963-8','Serum','Plain','mmol/L','22','29',NULL,NULL,300,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (17,1,5,'Fasting Blood Glucose','FBG','1558-6','Plasma','Fluoride','mmol/L','3.9','6.1',NULL,NULL,400,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (18,1,5,'HbA1c','HBA1C','4548-4','Whole blood','EDTA','%','4.0','5.6',NULL,NULL,800,24,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (19,1,5,'Random Blood Glucose','RBG','2339-0','Plasma','Fluoride','mmol/L','3.9','7.8',NULL,NULL,400,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (20,1,4,'Total Cholesterol','TC','2093-3','Serum','Plain','mmol/L','<5.0','<5.0',NULL,NULL,400,6,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (21,1,4,'HDL Cholesterol','HDL','2085-9','Serum','Plain','mmol/L','>1.0','>1.5',NULL,NULL,400,6,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (22,1,4,'LDL Cholesterol','LDL','13457-7','Serum','Plain','mmol/L','<3.0','<3.0',NULL,NULL,400,6,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (23,1,4,'Triglycerides','TG','2571-8','Serum','Plain','mmol/L','<1.7','<1.7',NULL,NULL,400,6,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (24,1,7,'Malaria Parasite (Blood Film)','MPBF','10501-5','Whole blood','EDTA','/HPF','Negative','Negative',NULL,NULL,500,2,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (25,1,7,'Malaria RDT','MRDT','9156-9','Whole blood','EDTA','','Negative','Negative',NULL,NULL,700,1,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (26,1,8,'HIV 1/2 Antibody','HIVAB','89567-1','Serum','Plain','','Non-reactive','Non-reactive',NULL,NULL,800,2,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (27,1,6,'Urine Protein','UPROT','5804-0','Urine','Sterile','mg/dL','Negative','Negative',NULL,NULL,100,2,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (28,1,6,'Urine Glucose','UGLUC','2350-7','Urine','Sterile','mmol/L','Negative','Negative',NULL,NULL,100,2,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (29,1,6,'Urine pH','UPH','5802-4','Urine','Sterile','','5.0','8.0',NULL,NULL,100,2,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (30,1,6,'Urine Specific Gravity','USG','5811-5','Urine','Sterile','','1.005','1.030',NULL,NULL,100,2,1,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: lab_orders
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `lab_orders`;
CREATE TABLE `lab_orders` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `patient_id` BIGINT UNSIGNED NOT NULL,
    `encounter_id` BIGINT UNSIGNED NULL,
    `requested_by` BIGINT UNSIGNED NOT NULL,
    `received_by` BIGINT UNSIGNED NULL,
    `order_number` VARCHAR(255) NOT NULL UNIQUE,
    `ordered_at` DATETIME NOT NULL,
    `collected_at` DATETIME NULL,
    `resulted_at` DATETIME NULL,
    `priority` ENUM('Routine','Urgent','STAT','ASAP') NOT NULL DEFAULT 'Routine',
    `status` ENUM('Pending','Sample Collected','In Progress','Completed','Verified','Cancelled','Rejected') NOT NULL DEFAULT 'Pending',
    `clinical_notes` TEXT NULL,
    `specimen_source` TEXT NULL,
    `fasting_status` VARCHAR(20) NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `lab_orders_patient_id_ordered_at_index` (`patient_id`,`ordered_at`),
    INDEX `lab_orders_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lab_orders` (`id`, `hospital_id`, `patient_id`, `encounter_id`, `requested_by`, `received_by`, `order_number`, `ordered_at`, `collected_at`, `resulted_at`, `priority`, `status`, `clinical_notes`, `specimen_source`, `fasting_status`, `created_at`, `updated_at`) VALUES
  (1,1,1,1,2,NULL,'FUTH-LF-LAB-2026-000001','2026-07-31 12:00:00',NULL,NULL,'Urgent','Pending','Investigate malaria','Whole blood','Random','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,2,2,2,NULL,'FUTH-LF-LAB-2026-000002','2026-07-31 12:00:00',NULL,NULL,'Urgent','Pending','Investigate malaria','Whole blood','Random','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,3,3,2,NULL,'FUTH-LF-LAB-2026-000003','2026-07-31 12:00:00',NULL,NULL,'Urgent','Pending','Investigate malaria','Whole blood','Random','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,1,4,4,2,NULL,'FUTH-LF-LAB-2026-000004','2026-07-31 12:00:00',NULL,NULL,'Urgent','Pending','Investigate malaria','Whole blood','Random','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,1,5,5,2,NULL,'FUTH-LF-LAB-2026-000005','2026-07-31 12:00:00',NULL,NULL,'Urgent','Pending','Investigate malaria','Whole blood','Random','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,1,6,6,2,NULL,'FUTH-LF-LAB-2026-000006','2026-07-31 12:00:00',NULL,NULL,'Urgent','Pending','Investigate malaria','Whole blood','Random','2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: lab_order_items
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `lab_order_items`;
CREATE TABLE `lab_order_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `lab_order_id` BIGINT UNSIGNED NOT NULL,
    `lab_test_id` BIGINT UNSIGNED NOT NULL,
    `test_name` VARCHAR(255) NOT NULL,
    `loinc_code` VARCHAR(20) NULL,
    `price` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `status` ENUM('Pending','Collected','In Progress','Completed','Verified','Cancelled') NOT NULL DEFAULT 'Pending',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lab_order_items` (`id`, `lab_order_id`, `lab_test_id`, `test_name`, `loinc_code`, `price`, `status`, `created_at`, `updated_at`) VALUES
  (1,1,24,'Malaria Parasite (Blood Film)','10501-5',500,'Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,1,'Haemoglobin','718-7',200,'Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,2,24,'Malaria Parasite (Blood Film)','10501-5',500,'Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,2,1,'Haemoglobin','718-7',200,'Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,3,24,'Malaria Parasite (Blood Film)','10501-5',500,'Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,3,1,'Haemoglobin','718-7',200,'Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,4,24,'Malaria Parasite (Blood Film)','10501-5',500,'Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,4,1,'Haemoglobin','718-7',200,'Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (9,5,24,'Malaria Parasite (Blood Film)','10501-5',500,'Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (10,5,1,'Haemoglobin','718-7',200,'Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (11,6,24,'Malaria Parasite (Blood Film)','10501-5',500,'Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (12,6,1,'Haemoglobin','718-7',200,'Pending','2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: lab_results
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `lab_results`;
CREATE TABLE `lab_results` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `lab_order_item_id` BIGINT UNSIGNED NOT NULL,
    `lab_test_id` BIGINT UNSIGNED NOT NULL,
    `performed_by` BIGINT UNSIGNED NULL,
    `verified_by` BIGINT UNSIGNED NULL,
    `value` VARCHAR(255) NULL,
    `value_numeric` VARCHAR(255) NULL,
    `unit` VARCHAR(255) NULL,
    `reference_range` VARCHAR(255) NULL,
    `flag` ENUM('Normal','Low','High','Critical Low','Critical High','Abnormal') NOT NULL DEFAULT 'Normal',
    `interpretation` TEXT NULL,
    `comments` TEXT NULL,
    `performed_at` DATETIME NULL,
    `verified_at` DATETIME NULL,
    `is_verified` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: radiology_modalities
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `radiology_modalities`;
CREATE TABLE `radiology_modalities` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(10) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `price` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `turnaround_hours` INT UNSIGNED NOT NULL DEFAULT 24,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `radiology_modalities` (`id`, `hospital_id`, `name`, `code`, `description`, `price`, `turnaround_hours`, `status`, `created_at`, `updated_at`) VALUES
  (1,1,'Plain X-Ray','XRAY','Conventional radiography',1500,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,'Ultrasound','US','Real-time ultrasonography',3000,4,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,'Computed Tomography','CT','Cross-sectional CT imaging',18000,24,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,1,'Magnetic Resonance','MR','MRI imaging',25000,48,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,1,'Fluoroscopy','FL','Fluoroscopic examinations',4000,8,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,1,'Mammography','MG','Breast imaging',6000,24,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,1,'Doppler Ultrasound','DUS','Vascular Doppler studies',5000,8,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,1,'Interventional Radiology','IVR','Image-guided procedures',10000,12,1,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: radiology_orders
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `radiology_orders`;
CREATE TABLE `radiology_orders` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `patient_id` BIGINT UNSIGNED NOT NULL,
    `encounter_id` BIGINT UNSIGNED NULL,
    `modality_id` BIGINT UNSIGNED NOT NULL,
    `requested_by` BIGINT UNSIGNED NOT NULL,
    `received_by` BIGINT UNSIGNED NULL,
    `order_number` VARCHAR(255) NOT NULL UNIQUE,
    `ordered_at` DATETIME NOT NULL,
    `performed_at` DATETIME NULL,
    `resulted_at` DATETIME NULL,
    `examination` VARCHAR(255) NOT NULL,
    `body_part` VARCHAR(255) NULL,
    `clinical_indication` TEXT NULL,
    `request_notes` TEXT NULL,
    `priority` ENUM('Routine','Urgent','STAT') NOT NULL DEFAULT 'Routine',
    `status` ENUM('Pending','Scheduled','In Progress','Completed','Verified','Cancelled') NOT NULL DEFAULT 'Pending',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `radiology_orders_patient_id_ordered_at_index` (`patient_id`,`ordered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `radiology_orders` (`id`, `hospital_id`, `patient_id`, `encounter_id`, `modality_id`, `requested_by`, `received_by`, `order_number`, `ordered_at`, `performed_at`, `resulted_at`, `examination`, `body_part`, `clinical_indication`, `request_notes`, `priority`, `status`, `created_at`, `updated_at`) VALUES
  (1,1,1,1,1,2,NULL,'FUTH-LF-RAD-2026-000001','2026-07-31 12:00:00',NULL,NULL,'Chest X-Ray PA view','Chest','Suspected pneumonia / TB screening',NULL,'Routine','Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,3,3,1,2,NULL,'FUTH-LF-RAD-2026-000002','2026-07-31 12:00:00',NULL,NULL,'Chest X-Ray PA view','Chest','Suspected pneumonia / TB screening',NULL,'Routine','Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,5,5,1,2,NULL,'FUTH-LF-RAD-2026-000003','2026-07-31 12:00:00',NULL,NULL,'Chest X-Ray PA view','Chest','Suspected pneumonia / TB screening',NULL,'Routine','Pending','2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: radiology_results
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `radiology_results`;
CREATE TABLE `radiology_results` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `radiology_order_id` BIGINT UNSIGNED NOT NULL,
    `reported_by` BIGINT UNSIGNED NULL,
    `verified_by` BIGINT UNSIGNED NULL,
    `findings` TEXT NOT NULL,
    `impression` TEXT NULL,
    `recommendations` TEXT NULL,
    `images` JSON NULL,
    `reported_at` DATETIME NULL,
    `verified_at` DATETIME NULL,
    `is_verified` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: suppliers
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(255) NOT NULL UNIQUE,
    `contact_person` VARCHAR(255) NULL,
    `phone` VARCHAR(255) NULL,
    `email` VARCHAR(255) NULL,
    `address` TEXT NULL,
    `tax_id` VARCHAR(255) NULL,
    `registration_number` VARCHAR(255) NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `suppliers` (`id`, `hospital_id`, `name`, `code`, `contact_person`, `phone`, `email`, `address`, `tax_id`, `registration_number`, `status`, `created_at`, `updated_at`) VALUES
  (1,1,'Emzor Pharmaceuticals','EMZ','Mr. Olu Adeyemi','+2348033000001','sales@emzor.com','Lagos, Nigeria',NULL,NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,'Fidson Healthcare','FID','Mrs. Joy Okeke','+2348033000002','sales@fidson.com','Lagos, Nigeria',NULL,NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,'May & Baker Nigeria','MNB','Mr. Sam Oke','+2348033000003','orders@mayandbaker.com','Lagos, Nigeria',NULL,NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,1,'Evans Medical','EVM','Dr. Grace Bello','+2348033000004','info@evansmedical.com','Lagos, Nigeria',NULL,NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,1,'Swipha Nigeria','SWP','Mr. Tunde Salami','+2348033000005','sales@swipha.com','Lagos, Nigeria',NULL,NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,1,'Unique Pharmaceuticals','UNQ','Mrs. Aisha Bello','+2348033000006','orders@uniquepharma.com','Lagos, Nigeria',NULL,NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: dispensing_points
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `dispensing_points`;
CREATE TABLE `dispensing_points` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(255) NOT NULL UNIQUE,
    `location` VARCHAR(255) NULL,
    `in_charge_id` BIGINT UNSIGNED NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `dispensing_points` (`id`, `hospital_id`, `name`, `code`, `location`, `in_charge_id`, `status`, `created_at`, `updated_at`) VALUES
  (1,1,'Main Pharmacy','PHARM-MAIN','Ground Floor, Block A',5,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,'Emergency Pharmacy','PHARM-ER','Emergency Department',NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,'ANC Pharmacy','PHARM-ANC','Antenatal Clinic',NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,1,'Paediatric Pharmacy','PHARM-PAED','Children Ward',NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,1,'Inpatient Pharmacy','PHARM-IPD','Wards 1-5',NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,1,'Theatre Pharmacy','PHARM-OR','Operating Theatre',NULL,1,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: prescriptions
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `prescriptions`;
CREATE TABLE `prescriptions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `patient_id` BIGINT UNSIGNED NOT NULL,
    `encounter_id` BIGINT UNSIGNED NULL,
    `prescriber_id` BIGINT UNSIGNED NOT NULL,
    `dispensing_point_id` BIGINT UNSIGNED NULL,
    `prescription_number` VARCHAR(255) NOT NULL UNIQUE,
    `prescribed_at` DATETIME NOT NULL,
    `dispensed_at` DATETIME NULL,
    `dispensed_by` BIGINT UNSIGNED NULL,
    `status` ENUM('Pending','Partially Dispensed','Dispensed','Cancelled','Rejected') NOT NULL DEFAULT 'Pending',
    `priority` ENUM('Routine','Urgent','STAT') NOT NULL DEFAULT 'Routine',
    `is_inpatient` TINYINT(1) NOT NULL DEFAULT 0,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `prescriptions_patient_id_prescribed_at_index` (`patient_id`,`prescribed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `prescriptions` (`id`, `hospital_id`, `patient_id`, `encounter_id`, `prescriber_id`, `dispensing_point_id`, `prescription_number`, `prescribed_at`, `dispensed_at`, `dispensed_by`, `status`, `priority`, `is_inpatient`, `notes`, `created_at`, `updated_at`) VALUES
  (1,1,1,1,2,1,'FUTH-LF-RX-2026-000001','2026-07-31 12:00:00',NULL,NULL,'Pending','Routine',0,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,2,2,2,1,'FUTH-LF-RX-2026-000002','2026-07-31 12:00:00',NULL,NULL,'Pending','Routine',0,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,1,3,3,2,1,'FUTH-LF-RX-2026-000003','2026-07-31 12:00:00',NULL,NULL,'Pending','Routine',0,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,1,4,4,2,1,'FUTH-LF-RX-2026-000004','2026-07-31 12:00:00',NULL,NULL,'Pending','Routine',0,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,1,5,5,2,1,'FUTH-LF-RX-2026-000005','2026-07-31 12:00:00',NULL,NULL,'Pending','Routine',0,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,1,6,6,2,1,'FUTH-LF-RX-2026-000006','2026-07-31 12:00:00',NULL,NULL,'Pending','Routine',0,NULL,'2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: prescription_items
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `prescription_items`;
CREATE TABLE `prescription_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `prescription_id` BIGINT UNSIGNED NOT NULL,
    `drug_id` BIGINT UNSIGNED NOT NULL,
    `drug_batch_id` BIGINT UNSIGNED NULL,
    `drug_name` VARCHAR(255) NOT NULL,
    `dosage_form` VARCHAR(255) NULL,
    `strength` VARCHAR(255) NULL,
    `dose` VARCHAR(255) NOT NULL,
    `route` VARCHAR(255) NOT NULL DEFAULT 'PO',
    `frequency` VARCHAR(255) NOT NULL,
    `duration` VARCHAR(255) NOT NULL,
    `quantity` INT UNSIGNED NOT NULL DEFAULT 0,
    `quantity_dispensed` INT UNSIGNED NOT NULL DEFAULT 0,
    `instructions` TEXT NULL,
    `status` ENUM('Pending','Partially Dispensed','Dispensed','Cancelled') NOT NULL DEFAULT 'Pending',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `prescription_items` (`id`, `prescription_id`, `drug_id`, `drug_batch_id`, `drug_name`, `dosage_form`, `strength`, `dose`, `route`, `frequency`, `duration`, `quantity`, `quantity_dispensed`, `instructions`, `status`, `created_at`, `updated_at`) VALUES
  (1,1,9,9,'Artemether/Lumefantrine','Tablet','20/120 mg','4 tablets','PO','BD','3 days',24,0,'Take 4 tablets stat, then 4 tablets at 8, 24, 48 hours','Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,1,1,1,'Paracetamol Tab','Tablet','500 mg','1 tablet','PO','TDS','3 days',9,0,'For fever as needed','Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,2,9,9,'Artemether/Lumefantrine','Tablet','20/120 mg','4 tablets','PO','BD','3 days',24,0,'Take 4 tablets stat, then 4 tablets at 8, 24, 48 hours','Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,2,1,1,'Paracetamol Tab','Tablet','500 mg','1 tablet','PO','TDS','3 days',9,0,'For fever as needed','Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,3,9,9,'Artemether/Lumefantrine','Tablet','20/120 mg','4 tablets','PO','BD','3 days',24,0,'Take 4 tablets stat, then 4 tablets at 8, 24, 48 hours','Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,3,1,1,'Paracetamol Tab','Tablet','500 mg','1 tablet','PO','TDS','3 days',9,0,'For fever as needed','Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,4,9,9,'Artemether/Lumefantrine','Tablet','20/120 mg','4 tablets','PO','BD','3 days',24,0,'Take 4 tablets stat, then 4 tablets at 8, 24, 48 hours','Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,4,1,1,'Paracetamol Tab','Tablet','500 mg','1 tablet','PO','TDS','3 days',9,0,'For fever as needed','Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (9,5,9,9,'Artemether/Lumefantrine','Tablet','20/120 mg','4 tablets','PO','BD','3 days',24,0,'Take 4 tablets stat, then 4 tablets at 8, 24, 48 hours','Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (10,5,1,1,'Paracetamol Tab','Tablet','500 mg','1 tablet','PO','TDS','3 days',9,0,'For fever as needed','Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (11,6,9,9,'Artemether/Lumefantrine','Tablet','20/120 mg','4 tablets','PO','BD','3 days',24,0,'Take 4 tablets stat, then 4 tablets at 8, 24, 48 hours','Pending','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (12,6,1,1,'Paracetamol Tab','Tablet','500 mg','1 tablet','PO','TDS','3 days',9,0,'For fever as needed','Pending','2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: dispensing_logs
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `dispensing_logs`;
CREATE TABLE `dispensing_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `dispensing_point_id` BIGINT UNSIGNED NULL,
    `prescription_id` BIGINT UNSIGNED NULL,
    `prescription_item_id` BIGINT UNSIGNED NULL,
    `drug_id` BIGINT UNSIGNED NOT NULL,
    `drug_batch_id` BIGINT UNSIGNED NULL,
    `patient_id` BIGINT UNSIGNED NOT NULL,
    `dispensed_by` BIGINT UNSIGNED NOT NULL,
    `reference_number` VARCHAR(255) NOT NULL UNIQUE,
    `dispensed_at` DATETIME NOT NULL,
    `quantity` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `unit_price` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `total_price` DECIMAL(14,2) NOT NULL DEFAULT 0,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `dispensing_logs_patient_id_dispensed_at_index` (`patient_id`,`dispensed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: stock_movements
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `stock_movements`;
CREATE TABLE `stock_movements` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `drug_id` BIGINT UNSIGNED NOT NULL,
    `drug_batch_id` BIGINT UNSIGNED NULL,
    `dispensing_point_id` BIGINT UNSIGNED NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `type` ENUM('Receipt','Transfer In','Transfer Out','Dispense','Adjustment In','Adjustment Out','Return','Disposal','Stock Take') NOT NULL,
    `reference_number` VARCHAR(255) NULL,
    `movement_date` DATETIME NOT NULL,
    `quantity` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `balance_after` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `reason` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `stock_movements_drug_id_movement_date_index` (`drug_id`,`movement_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `stock_movements` (`id`, `hospital_id`, `drug_id`, `drug_batch_id`, `dispensing_point_id`, `user_id`, `type`, `reference_number`, `movement_date`, `quantity`, `balance_after`, `reason`, `created_at`, `updated_at`) VALUES
  (1,1,1,1,1,5,'Receipt','BATCH-DRG-001','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (2,1,2,2,1,5,'Receipt','BATCH-DRG-002','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (3,1,3,3,1,5,'Receipt','BATCH-DRG-003','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (4,1,4,4,1,5,'Receipt','BATCH-DRG-004','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (5,1,5,5,1,5,'Receipt','BATCH-DRG-005','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (6,1,6,6,1,5,'Receipt','BATCH-DRG-006','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (7,1,7,7,1,5,'Receipt','BATCH-DRG-007','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (8,1,8,8,1,5,'Receipt','BATCH-DRG-008','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (9,1,9,9,1,5,'Receipt','BATCH-DRG-009','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (10,1,10,10,1,5,'Receipt','BATCH-DRG-010','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (11,1,11,11,1,5,'Receipt','BATCH-DRG-011','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (12,1,12,12,1,5,'Receipt','BATCH-DRG-012','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (13,1,13,13,1,5,'Receipt','BATCH-DRG-013','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (14,1,14,14,1,5,'Receipt','BATCH-DRG-014','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (15,1,15,15,1,5,'Receipt','BATCH-DRG-015','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (16,1,16,16,1,5,'Receipt','BATCH-DRG-016','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (17,1,17,17,1,5,'Receipt','BATCH-DRG-017','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (18,1,18,18,1,5,'Receipt','BATCH-DRG-018','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (19,1,19,19,1,5,'Receipt','BATCH-DRG-019','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (20,1,20,20,1,5,'Receipt','BATCH-DRG-020','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (21,1,21,21,1,5,'Receipt','BATCH-DRG-021','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (22,1,22,22,1,5,'Receipt','BATCH-DRG-022','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (23,1,23,23,1,5,'Receipt','BATCH-DRG-023','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (24,1,24,24,1,5,'Receipt','BATCH-DRG-024','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (25,1,25,25,1,5,'Receipt','BATCH-DRG-025','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (26,1,26,26,1,5,'Receipt','BATCH-DRG-026','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (27,1,27,27,1,5,'Receipt','BATCH-DRG-027','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (28,1,28,28,1,5,'Receipt','BATCH-DRG-028','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (29,1,29,29,1,5,'Receipt','BATCH-DRG-029','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (30,1,30,30,1,5,'Receipt','BATCH-DRG-030','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (31,1,31,31,1,5,'Receipt','BATCH-DRG-031','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (32,1,32,32,1,5,'Receipt','BATCH-DRG-032','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (33,1,33,33,1,5,'Receipt','BATCH-DRG-033','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (34,1,34,34,1,5,'Receipt','BATCH-DRG-034','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (35,1,35,35,1,5,'Receipt','BATCH-DRG-035','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (36,1,36,36,1,5,'Receipt','BATCH-DRG-036','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (37,1,37,37,1,5,'Receipt','BATCH-DRG-037','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (38,1,38,38,1,5,'Receipt','BATCH-DRG-038','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (39,1,39,39,1,5,'Receipt','BATCH-DRG-039','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (40,1,40,40,1,5,'Receipt','BATCH-DRG-040','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (41,1,41,41,1,5,'Receipt','BATCH-DRG-041','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (42,1,42,42,1,5,'Receipt','BATCH-DRG-042','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (43,1,43,43,1,5,'Receipt','BATCH-DRG-043','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (44,1,44,44,1,5,'Receipt','BATCH-DRG-044','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (45,1,45,45,1,5,'Receipt','BATCH-DRG-045','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (46,1,46,46,1,5,'Receipt','BATCH-DRG-046','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (47,1,47,47,1,5,'Receipt','BATCH-DRG-047','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (48,1,48,48,1,5,'Receipt','BATCH-DRG-048','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (49,1,49,49,1,5,'Receipt','BATCH-DRG-049','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00'),
  (50,1,50,50,1,5,'Receipt','BATCH-DRG-050','2026-05-02 12:00:00',1000,1000,'Stock receipt','2026-05-02 12:00:00','2026-05-02 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: stock_returns
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `stock_returns`;
CREATE TABLE `stock_returns` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `patient_id` BIGINT UNSIGNED NULL,
    `dispensing_log_id` BIGINT UNSIGNED NULL,
    `drug_id` BIGINT UNSIGNED NOT NULL,
    `drug_batch_id` BIGINT UNSIGNED NULL,
    `received_by` BIGINT UNSIGNED NOT NULL,
    `return_number` VARCHAR(255) NOT NULL UNIQUE,
    `returned_at` DATETIME NOT NULL,
    `quantity` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `reason` ENUM('Expired','Damaged','Wrong Dispense','Patient Returned','Other') NOT NULL DEFAULT 'Patient Returned',
    `notes` TEXT NULL,
    `disposition` ENUM('Restocked','Quarantined','Disposed') NOT NULL DEFAULT 'Quarantined',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: purchases
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `purchases`;
CREATE TABLE `purchases` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `supplier_id` BIGINT UNSIGNED NOT NULL,
    `received_by` BIGINT UNSIGNED NOT NULL,
    `purchase_number` VARCHAR(255) NOT NULL UNIQUE,
    `invoice_number` VARCHAR(255) NULL,
    `lpo_number` VARCHAR(255) NULL,
    `order_date` DATE NOT NULL,
    `received_date` DATE NULL,
    `invoice_date` DATE NULL,
    `subtotal` DECIMAL(14,2) NOT NULL DEFAULT 0,
    `tax_amount` DECIMAL(14,2) NOT NULL DEFAULT 0,
    `discount_amount` DECIMAL(14,2) NOT NULL DEFAULT 0,
    `total_amount` DECIMAL(14,2) NOT NULL DEFAULT 0,
    `amount_paid` DECIMAL(14,2) NOT NULL DEFAULT 0,
    `status` ENUM('Draft','Ordered','Partially Received','Received','Cancelled') NOT NULL DEFAULT 'Draft',
    `payment_status` ENUM('Unpaid','Partially Paid','Paid') NOT NULL DEFAULT 'Unpaid',
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: purchase_items
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `purchase_items`;
CREATE TABLE `purchase_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `purchase_id` BIGINT UNSIGNED NOT NULL,
    `drug_id` BIGINT UNSIGNED NOT NULL,
    `drug_name` VARCHAR(255) NOT NULL,
    `batch_number` VARCHAR(255) NULL,
    `expiry_date` DATE NULL,
    `quantity_ordered` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `quantity_received` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `unit_cost` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `tax_percent` DECIMAL(5,2) NOT NULL DEFAULT 0,
    `discount_percent` DECIMAL(5,2) NOT NULL DEFAULT 0,
    `line_total` DECIMAL(14,2) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: receipts
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `receipts`;
CREATE TABLE `receipts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hospital_id` BIGINT UNSIGNED NOT NULL,
    `purchase_id` BIGINT UNSIGNED NULL,
    `supplier_id` BIGINT UNSIGNED NULL,
    `received_by` BIGINT UNSIGNED NOT NULL,
    `receipt_number` VARCHAR(255) NOT NULL UNIQUE,
    `received_date` DATE NOT NULL,
    `invoice_number` VARCHAR(255) NULL,
    `delivery_note_number` VARCHAR(255) NULL,
    `notes` TEXT NULL,
    `status` ENUM('Pending','Verified','Rejected') NOT NULL DEFAULT 'Pending',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: receipt_items
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `receipt_items`;
CREATE TABLE `receipt_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `receipt_id` BIGINT UNSIGNED NOT NULL,
    `drug_id` BIGINT UNSIGNED NOT NULL,
    `drug_batch_id` BIGINT UNSIGNED NULL,
    `batch_number` VARCHAR(255) NULL,
    `expiry_date` DATE NULL,
    `quantity_received` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `quantity_rejected` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `unit_cost` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: permissions
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `guard_name` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
  (1,'view_patient','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,'view_any_patient','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,'create_patient','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,'update_patient','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,'delete_patient','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,'restore_patient','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,'force_delete_patient','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,'view_encounter','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (9,'view_any_encounter','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (10,'create_encounter','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (11,'update_encounter','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (12,'delete_encounter','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (13,'restore_encounter','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (14,'force_delete_encounter','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (15,'view_appointment','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (16,'view_any_appointment','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (17,'create_appointment','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (18,'update_appointment','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (19,'delete_appointment','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (20,'restore_appointment','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (21,'force_delete_appointment','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (22,'view_clinicalNote','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (23,'view_any_clinicalNote','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (24,'create_clinicalNote','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (25,'update_clinicalNote','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (26,'delete_clinicalNote','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (27,'restore_clinicalNote','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (28,'force_delete_clinicalNote','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (29,'view_vitals','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (30,'view_any_vitals','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (31,'create_vitals','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (32,'update_vitals','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (33,'delete_vitals','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (34,'restore_vitals','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (35,'force_delete_vitals','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (36,'view_allergy','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (37,'view_any_allergy','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (38,'create_allergy','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (39,'update_allergy','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (40,'delete_allergy','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (41,'restore_allergy','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (42,'force_delete_allergy','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (43,'view_diagnosis','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (44,'view_any_diagnosis','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (45,'create_diagnosis','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (46,'update_diagnosis','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (47,'delete_diagnosis','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (48,'restore_diagnosis','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (49,'force_delete_diagnosis','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (50,'view_labPanel','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (51,'view_any_labPanel','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (52,'create_labPanel','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (53,'update_labPanel','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (54,'delete_labPanel','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (55,'restore_labPanel','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (56,'force_delete_labPanel','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (57,'view_labTest','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (58,'view_any_labTest','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (59,'create_labTest','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (60,'update_labTest','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (61,'delete_labTest','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (62,'restore_labTest','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (63,'force_delete_labTest','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (64,'view_labOrder','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (65,'view_any_labOrder','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (66,'create_labOrder','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (67,'update_labOrder','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (68,'delete_labOrder','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (69,'restore_labOrder','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (70,'force_delete_labOrder','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (71,'view_radiologyModality','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (72,'view_any_radiologyModality','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (73,'create_radiologyModality','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (74,'update_radiologyModality','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (75,'delete_radiologyModality','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (76,'restore_radiologyModality','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (77,'force_delete_radiologyModality','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (78,'view_radiologyOrder','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (79,'view_any_radiologyOrder','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (80,'create_radiologyOrder','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (81,'update_radiologyOrder','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (82,'delete_radiologyOrder','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (83,'restore_radiologyOrder','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (84,'force_delete_radiologyOrder','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (85,'view_drugCategory','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (86,'view_any_drugCategory','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (87,'create_drugCategory','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (88,'update_drugCategory','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (89,'delete_drugCategory','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (90,'restore_drugCategory','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (91,'force_delete_drugCategory','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (92,'view_drug','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (93,'view_any_drug','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (94,'create_drug','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (95,'update_drug','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (96,'delete_drug','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (97,'restore_drug','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (98,'force_delete_drug','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (99,'view_drugBatch','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (100,'view_any_drugBatch','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (101,'create_drugBatch','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (102,'update_drugBatch','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (103,'delete_drugBatch','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (104,'restore_drugBatch','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (105,'force_delete_drugBatch','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (106,'view_drugInteraction','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (107,'view_any_drugInteraction','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (108,'create_drugInteraction','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (109,'update_drugInteraction','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (110,'delete_drugInteraction','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (111,'restore_drugInteraction','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (112,'force_delete_drugInteraction','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (113,'view_supplier','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (114,'view_any_supplier','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (115,'create_supplier','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (116,'update_supplier','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (117,'delete_supplier','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (118,'restore_supplier','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (119,'force_delete_supplier','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (120,'view_purchase','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (121,'view_any_purchase','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (122,'create_purchase','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (123,'update_purchase','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (124,'delete_purchase','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (125,'restore_purchase','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (126,'force_delete_purchase','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (127,'view_receipt','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (128,'view_any_receipt','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (129,'create_receipt','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (130,'update_receipt','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (131,'delete_receipt','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (132,'restore_receipt','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (133,'force_delete_receipt','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (134,'view_dispensingPoint','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (135,'view_any_dispensingPoint','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (136,'create_dispensingPoint','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (137,'update_dispensingPoint','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (138,'delete_dispensingPoint','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (139,'restore_dispensingPoint','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (140,'force_delete_dispensingPoint','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (141,'view_prescription','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (142,'view_any_prescription','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (143,'create_prescription','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (144,'update_prescription','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (145,'delete_prescription','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (146,'restore_prescription','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (147,'force_delete_prescription','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (148,'view_dispensingLog','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (149,'view_any_dispensingLog','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (150,'create_dispensingLog','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (151,'update_dispensingLog','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (152,'delete_dispensingLog','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (153,'restore_dispensingLog','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (154,'force_delete_dispensingLog','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (155,'view_stockMovement','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (156,'view_any_stockMovement','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (157,'create_stockMovement','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (158,'update_stockMovement','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (159,'delete_stockMovement','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (160,'restore_stockMovement','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (161,'force_delete_stockMovement','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (162,'view_stockReturn','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (163,'view_any_stockReturn','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (164,'create_stockReturn','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (165,'update_stockReturn','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (166,'delete_stockReturn','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (167,'restore_stockReturn','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (168,'force_delete_stockReturn','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (169,'view_hospital','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (170,'view_any_hospital','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (171,'create_hospital','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (172,'update_hospital','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (173,'delete_hospital','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (174,'restore_hospital','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (175,'force_delete_hospital','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (176,'view_clinic','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (177,'view_any_clinic','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (178,'create_clinic','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (179,'update_clinic','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (180,'delete_clinic','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (181,'restore_clinic','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (182,'force_delete_clinic','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (183,'view_department','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (184,'view_any_department','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (185,'create_department','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (186,'update_department','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (187,'delete_department','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (188,'restore_department','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (189,'force_delete_department','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (190,'view_staff','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (191,'view_any_staff','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (192,'create_staff','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (193,'update_staff','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (194,'delete_staff','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (195,'restore_staff','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (196,'force_delete_staff','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (197,'view_user','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (198,'view_any_user','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (199,'create_user','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (200,'update_user','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (201,'delete_user','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (202,'restore_user','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (203,'force_delete_user','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (204,'view_role','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (205,'view_any_role','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (206,'create_role','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (207,'update_role','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (208,'delete_role','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (209,'restore_role','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (210,'force_delete_role','web','2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: roles
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `guard_name` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
  (1,'Super Admin','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,'Medical Administrator','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,'Doctor','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,'Nurse','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,'Pharmacist','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,'Laboratory Scientist','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,'Radiographer','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,'Medical Records Officer','web','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (9,'Accountant','web','2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: role_has_permissions
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
    `permission_id` BIGINT UNSIGNED NOT NULL,
    `role_id` BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (`permission_id`,`role_id`),
    INDEX `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
  (1,1),
  (2,1),
  (3,1),
  (4,1),
  (5,1),
  (6,1),
  (7,1),
  (8,1),
  (9,1),
  (10,1),
  (11,1),
  (12,1),
  (13,1),
  (14,1),
  (15,1),
  (16,1),
  (17,1),
  (18,1),
  (19,1),
  (20,1),
  (21,1),
  (22,1),
  (23,1),
  (24,1),
  (25,1),
  (26,1),
  (27,1),
  (28,1),
  (29,1),
  (30,1),
  (31,1),
  (32,1),
  (33,1),
  (34,1),
  (35,1),
  (36,1),
  (37,1),
  (38,1),
  (39,1),
  (40,1),
  (41,1),
  (42,1),
  (43,1),
  (44,1),
  (45,1),
  (46,1),
  (47,1),
  (48,1),
  (49,1),
  (50,1),
  (51,1),
  (52,1),
  (53,1),
  (54,1),
  (55,1),
  (56,1),
  (57,1),
  (58,1),
  (59,1),
  (60,1),
  (61,1),
  (62,1),
  (63,1),
  (64,1),
  (65,1),
  (66,1),
  (67,1),
  (68,1),
  (69,1),
  (70,1),
  (71,1),
  (72,1),
  (73,1),
  (74,1),
  (75,1),
  (76,1),
  (77,1),
  (78,1),
  (79,1),
  (80,1),
  (81,1),
  (82,1),
  (83,1),
  (84,1),
  (85,1),
  (86,1),
  (87,1),
  (88,1),
  (89,1),
  (90,1),
  (91,1),
  (92,1),
  (93,1),
  (94,1),
  (95,1),
  (96,1),
  (97,1),
  (98,1),
  (99,1),
  (100,1),
  (101,1),
  (102,1),
  (103,1),
  (104,1),
  (105,1),
  (106,1),
  (107,1),
  (108,1),
  (109,1),
  (110,1),
  (111,1),
  (112,1),
  (113,1),
  (114,1),
  (115,1),
  (116,1),
  (117,1),
  (118,1),
  (119,1),
  (120,1),
  (121,1),
  (122,1),
  (123,1),
  (124,1),
  (125,1),
  (126,1),
  (127,1),
  (128,1),
  (129,1),
  (130,1),
  (131,1),
  (132,1),
  (133,1),
  (134,1),
  (135,1),
  (136,1),
  (137,1),
  (138,1),
  (139,1),
  (140,1),
  (141,1),
  (142,1),
  (143,1),
  (144,1),
  (145,1),
  (146,1),
  (147,1),
  (148,1),
  (149,1),
  (150,1),
  (151,1),
  (152,1),
  (153,1),
  (154,1),
  (155,1),
  (156,1),
  (157,1),
  (158,1),
  (159,1),
  (160,1),
  (161,1),
  (162,1),
  (163,1),
  (164,1),
  (165,1),
  (166,1),
  (167,1),
  (168,1),
  (169,1),
  (170,1),
  (171,1),
  (172,1),
  (173,1),
  (174,1),
  (175,1),
  (176,1),
  (177,1),
  (178,1),
  (179,1),
  (180,1),
  (181,1),
  (182,1),
  (183,1),
  (184,1),
  (185,1),
  (186,1),
  (187,1),
  (188,1),
  (189,1),
  (190,1),
  (191,1),
  (192,1),
  (193,1),
  (194,1),
  (195,1),
  (196,1),
  (197,1),
  (198,1),
  (199,1),
  (200,1),
  (201,1),
  (202,1),
  (203,1),
  (204,1),
  (205,1),
  (206,1),
  (207,1),
  (208,1),
  (209,1),
  (210,1);

-- ---------------------------------------------------------------------------
-- Table: model_has_roles
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
    `role_id` BIGINT UNSIGNED NOT NULL,
    `model_type` VARCHAR(255) NOT NULL,
    `model_id` BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (`role_id`,`model_id`,`model_type`),
    INDEX `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `model_has_roles` (`role_id`, `model_id`, `model_type`) VALUES
  (2,1,'App\\Models\\User'),
  (3,2,'App\\Models\\User'),
  (3,3,'App\\Models\\User'),
  (4,4,'App\\Models\\User'),
  (5,5,'App\\Models\\User'),
  (6,6,'App\\Models\\User'),
  (7,7,'App\\Models\\User'),
  (8,8,'App\\Models\\User'),
  (9,9,'App\\Models\\User'),
  (1,1,'App\\Models\\User');

-- ---------------------------------------------------------------------------
-- Table: model_has_permissions
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions` (
    `permission_id` BIGINT UNSIGNED NOT NULL,
    `model_type` VARCHAR(255) NOT NULL,
    `model_id` BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
    INDEX `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Table: states
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `states`;
CREATE TABLE `states` (
    `state_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `code` VARCHAR(10) NOT NULL UNIQUE,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `states` (`state_id`, `name`, `code`, `created_at`, `updated_at`) VALUES
  (1,'Abia','NG-AB','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,'Adamawa','NG-AD','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,'Akwa Ibom','NG-AK','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,'Anambra','NG-AN','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,'Bauchi','NG-BA','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,'Bayelsa','NG-BY','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,'Benue','NG-BE','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,'Borno','NG-BO','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (9,'Cross River','NG-CR','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (10,'Delta','NG-DE','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (11,'Ebonyi','NG-EB','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (12,'Edo','NG-ED','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (13,'Ekiti','NG-EK','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (14,'Enugu','NG-EN','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (15,'FCT','NG-FC','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (16,'Gombe','NG-GO','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (17,'Imo','NG-IM','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (18,'Jigawa','NG-JI','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (19,'Kaduna','NG-KD','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (20,'Kano','NG-KN','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (21,'Katsina','NG-KT','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (22,'Kebbi','NG-KE','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (23,'Kogi','NG-KO','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (24,'Kwara','NG-KW','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (25,'Lagos','NG-LA','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (26,'Nasarawa','NG-NA','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (27,'Niger','NG-NI','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (28,'Ogun','NG-OG','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (29,'Ondo','NG-ON','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (30,'Osun','NG-OS','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (31,'Oyo','NG-OY','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (32,'Plateau','NG-PL','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (33,'Rivers','NG-RI','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (34,'Sokoto','NG-SO','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (35,'Taraba','NG-TA','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (36,'Yobe','NG-YO','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (37,'Zamfara','NG-ZA','2026-07-31 12:00:00','2026-07-31 12:00:00');

-- ---------------------------------------------------------------------------
-- Table: locals
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `locals`;
CREATE TABLE `locals` (
    `local_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `state_id` BIGINT UNSIGNED NOT NULL,
    `local_name` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`local_id`),
    UNIQUE KEY `locals_state_id_local_name_unique` (`state_id`,`local_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `locals` (`local_id`, `state_id`, `local_name`, `created_at`, `updated_at`) VALUES
  (1,26,'Lafia','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (2,26,'Akwanga','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (3,26,'Wamba','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (4,26,'Nasarawa Eggon','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (5,26,'Keffi','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (6,26,'Kokona','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (7,26,'Obi','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (8,26,'Doma','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (9,26,'Keana','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (10,26,'Awe','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (11,26,'Nasarawa','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (12,26,'Toto','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (13,26,'Karü','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (14,26,'Wamba','2026-07-31 12:00:00','2026-07-31 12:00:00'),
  (15,26,'Akpasu','2026-07-31 12:00:00','2026-07-31 12:00:00');


SET FOREIGN_KEY_CHECKS = 1;


-- ============================================================================
-- End of dump
-- ============================================================================
