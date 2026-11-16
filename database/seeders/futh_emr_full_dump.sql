WARNING: option --ssl-verify-server-cert is disabled, because of an insecure passwordless login.
/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: futh_emr
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-0+deb13u1 from Debian

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `admissions`
--

DROP TABLE IF EXISTS `admissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admission_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `encounter_id` bigint(20) unsigned DEFAULT NULL,
  `ward_id` bigint(20) unsigned DEFAULT NULL,
  `bed_id` bigint(20) unsigned DEFAULT NULL,
  `admitting_doctor_id` bigint(20) unsigned DEFAULT NULL,
  `attending_doctor_id` bigint(20) unsigned DEFAULT NULL,
  `admission_date` datetime NOT NULL,
  `discharge_date` datetime DEFAULT NULL,
  `admission_type` enum('Emergency','Routine','Elective','Transfer In','Maternity','Day Case') NOT NULL DEFAULT 'Routine',
  `admission_source` enum('Emergency Room','Outpatient Clinic','Referral','Transfer','Direct Admission','Theatre') NOT NULL DEFAULT 'Outpatient Clinic',
  `admission_diagnosis` text DEFAULT NULL,
  `admission_reason` text DEFAULT NULL,
  `clinical_notes` text DEFAULT NULL,
  `status` enum('Admitted','Discharged','Transferred','Absconded','Death','Cancelled') NOT NULL DEFAULT 'Admitted',
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admissions_admission_number_unique` (`admission_number`),
  KEY `admissions_encounter_id_foreign` (`encounter_id`),
  KEY `admissions_bed_id_foreign` (`bed_id`),
  KEY `admissions_admitting_doctor_id_foreign` (`admitting_doctor_id`),
  KEY `admissions_attending_doctor_id_foreign` (`attending_doctor_id`),
  KEY `admissions_created_by_foreign` (`created_by`),
  KEY `admissions_hospital_id_status_index` (`hospital_id`,`status`),
  KEY `admissions_patient_id_status_index` (`patient_id`,`status`),
  KEY `admissions_ward_id_bed_id_index` (`ward_id`,`bed_id`),
  KEY `admissions_admission_number_index` (`admission_number`),
  CONSTRAINT `admissions_admitting_doctor_id_foreign` FOREIGN KEY (`admitting_doctor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `admissions_attending_doctor_id_foreign` FOREIGN KEY (`attending_doctor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `admissions_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`) ON DELETE SET NULL,
  CONSTRAINT `admissions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `admissions_encounter_id_foreign` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `admissions_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `admissions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `admissions_ward_id_foreign` FOREIGN KEY (`ward_id`) REFERENCES `wards` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admissions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `admissions` WRITE;
/*!40000 ALTER TABLE `admissions` DISABLE KEYS */;
INSERT INTO `admissions` VALUES
(1,'ADM-2026-000001',1,1,NULL,1,1,2,2,'2026-08-01 21:50:05',NULL,'Emergency','Emergency Room','Severe Malaria with Anaemia','Patient collapsed at home, brought in by family',NULL,'Admitted',1,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(2,'ADM-2026-000002',1,2,NULL,2,6,2,2,'2026-08-03 21:50:05',NULL,'Routine','Outpatient Clinic','Typhoid Fever',NULL,NULL,'Admitted',1,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(3,'ADM-2026-000003',1,3,NULL,7,31,2,2,'2026-08-04 13:50:05',NULL,'Emergency','Emergency Room','Diabetic Ketoacidosis',NULL,NULL,'Admitted',1,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL);
/*!40000 ALTER TABLE `admissions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `allergies`
--

DROP TABLE IF EXISTS `allergies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `allergies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) unsigned NOT NULL,
  `recorded_by` bigint(20) unsigned NOT NULL,
  `encounter_id` bigint(20) unsigned DEFAULT NULL,
  `substance` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `category` enum('Medication','Food','Environment','Biologic','Other') NOT NULL DEFAULT 'Medication',
  `criticality` enum('Low','High','Unable to assess') NOT NULL DEFAULT 'Low',
  `severity` enum('Mild','Moderate','Severe','Fatal') NOT NULL DEFAULT 'Mild',
  `reaction` enum('Rash','Urticaria','Anaphylaxis','Angioedema','Bronchospasm','GI upset','Hepatotoxicity','Stevens-Johnson','Other') NOT NULL DEFAULT 'Rash',
  `reaction_description` text DEFAULT NULL,
  `onset_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Active','Resolved','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `allergies_patient_id_status_index` (`patient_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `allergies`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `allergies` WRITE;
/*!40000 ALTER TABLE `allergies` DISABLE KEYS */;
INSERT INTO `allergies` VALUES
(1,1,2,1,'Sulfonamides',NULL,'Medication','High','Moderate','Rash','Generalised maculopapular rash after 3 days of cotrimoxazole','2024-05-12','Avoid all sulfonamide antibiotics','Active','2026-07-31 11:00:00','2026-07-31 11:00:00',NULL);
/*!40000 ALTER TABLE `allergies` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `appointment_slots`
--

DROP TABLE IF EXISTS `appointment_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointment_slots` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `provider_id` bigint(20) unsigned DEFAULT NULL,
  `appointment_type_id` bigint(20) unsigned DEFAULT NULL,
  `clinic_schedule_id` bigint(20) unsigned DEFAULT NULL,
  `provider_schedule_id` bigint(20) unsigned DEFAULT NULL,
  `slot_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 15,
  `capacity` int(11) NOT NULL DEFAULT 1,
  `booked_count` int(11) NOT NULL DEFAULT 0,
  `status` enum('Available','Booked','Blocked','Completed','Cancelled','No Show') NOT NULL DEFAULT 'Available',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointment_slots_hospital_id_foreign` (`hospital_id`),
  KEY `appointment_slots_appointment_type_id_foreign` (`appointment_type_id`),
  KEY `appointment_slots_clinic_schedule_id_foreign` (`clinic_schedule_id`),
  KEY `appointment_slots_provider_schedule_id_foreign` (`provider_schedule_id`),
  KEY `appointment_slots_clinic_id_slot_date_status_index` (`clinic_id`,`slot_date`,`status`),
  KEY `appointment_slots_provider_id_slot_date_index` (`provider_id`,`slot_date`),
  KEY `appointment_slots_slot_date_start_time_index` (`slot_date`,`start_time`),
  CONSTRAINT `appointment_slots_appointment_type_id_foreign` FOREIGN KEY (`appointment_type_id`) REFERENCES `appointment_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `appointment_slots_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointment_slots_clinic_schedule_id_foreign` FOREIGN KEY (`clinic_schedule_id`) REFERENCES `clinic_schedules` (`id`) ON DELETE SET NULL,
  CONSTRAINT `appointment_slots_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointment_slots_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `appointment_slots_provider_schedule_id_foreign` FOREIGN KEY (`provider_schedule_id`) REFERENCES `provider_schedules` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=501 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointment_slots`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `appointment_slots` WRITE;
/*!40000 ALTER TABLE `appointment_slots` DISABLE KEYS */;
INSERT INTO `appointment_slots` VALUES
(1,1,1,NULL,1,3,NULL,'2026-08-04','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(2,1,1,NULL,1,3,NULL,'2026-08-04','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(3,1,1,NULL,1,3,NULL,'2026-08-04','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(4,1,1,NULL,1,3,NULL,'2026-08-04','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(5,1,1,NULL,1,3,NULL,'2026-08-04','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(6,1,1,NULL,1,3,NULL,'2026-08-04','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(7,1,1,NULL,1,3,NULL,'2026-08-04','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(8,1,1,NULL,1,3,NULL,'2026-08-04','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(9,1,1,NULL,1,3,NULL,'2026-08-04','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(10,1,1,NULL,1,3,NULL,'2026-08-04','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(11,1,1,NULL,1,3,NULL,'2026-08-04','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(12,1,1,NULL,2,4,NULL,'2026-08-04','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(13,1,1,NULL,2,4,NULL,'2026-08-04','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(14,1,1,NULL,2,4,NULL,'2026-08-04','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(15,1,1,NULL,2,4,NULL,'2026-08-04','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(16,1,1,NULL,2,4,NULL,'2026-08-04','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(17,1,1,NULL,2,4,NULL,'2026-08-04','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(18,1,1,NULL,2,4,NULL,'2026-08-04','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(19,1,1,NULL,2,4,NULL,'2026-08-04','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(20,1,1,NULL,2,4,NULL,'2026-08-04','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(21,1,2,NULL,1,13,NULL,'2026-08-04','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(22,1,2,NULL,1,13,NULL,'2026-08-04','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(23,1,2,NULL,1,13,NULL,'2026-08-04','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(24,1,2,NULL,1,13,NULL,'2026-08-04','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(25,1,2,NULL,1,13,NULL,'2026-08-04','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(26,1,2,NULL,1,13,NULL,'2026-08-04','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(27,1,2,NULL,1,13,NULL,'2026-08-04','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(28,1,2,NULL,1,13,NULL,'2026-08-04','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(29,1,2,NULL,1,13,NULL,'2026-08-04','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(30,1,2,NULL,1,13,NULL,'2026-08-04','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(31,1,2,NULL,1,13,NULL,'2026-08-04','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(32,1,2,NULL,2,14,NULL,'2026-08-04','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(33,1,2,NULL,2,14,NULL,'2026-08-04','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(34,1,2,NULL,2,14,NULL,'2026-08-04','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(35,1,2,NULL,2,14,NULL,'2026-08-04','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(36,1,2,NULL,2,14,NULL,'2026-08-04','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(37,1,2,NULL,2,14,NULL,'2026-08-04','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(38,1,2,NULL,2,14,NULL,'2026-08-04','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(39,1,2,NULL,2,14,NULL,'2026-08-04','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(40,1,2,NULL,2,14,NULL,'2026-08-04','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(41,1,3,NULL,1,23,NULL,'2026-08-04','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(42,1,3,NULL,1,23,NULL,'2026-08-04','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(43,1,3,NULL,1,23,NULL,'2026-08-04','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(44,1,3,NULL,1,23,NULL,'2026-08-04','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(45,1,3,NULL,1,23,NULL,'2026-08-04','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(46,1,3,NULL,1,23,NULL,'2026-08-04','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(47,1,3,NULL,1,23,NULL,'2026-08-04','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(48,1,3,NULL,1,23,NULL,'2026-08-04','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(49,1,3,NULL,1,23,NULL,'2026-08-04','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(50,1,3,NULL,1,23,NULL,'2026-08-04','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(51,1,3,NULL,1,23,NULL,'2026-08-04','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(52,1,3,NULL,2,24,NULL,'2026-08-04','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(53,1,3,NULL,2,24,NULL,'2026-08-04','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(54,1,3,NULL,2,24,NULL,'2026-08-04','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(55,1,3,NULL,2,24,NULL,'2026-08-04','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(56,1,3,NULL,2,24,NULL,'2026-08-04','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(57,1,3,NULL,2,24,NULL,'2026-08-04','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(58,1,3,NULL,2,24,NULL,'2026-08-04','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(59,1,3,NULL,2,24,NULL,'2026-08-04','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(60,1,3,NULL,2,24,NULL,'2026-08-04','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(61,1,4,NULL,1,33,NULL,'2026-08-04','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(62,1,4,NULL,1,33,NULL,'2026-08-04','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(63,1,4,NULL,1,33,NULL,'2026-08-04','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(64,1,4,NULL,1,33,NULL,'2026-08-04','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(65,1,4,NULL,1,33,NULL,'2026-08-04','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(66,1,4,NULL,1,33,NULL,'2026-08-04','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(67,1,4,NULL,1,33,NULL,'2026-08-04','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(68,1,4,NULL,1,33,NULL,'2026-08-04','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(69,1,4,NULL,1,33,NULL,'2026-08-04','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(70,1,4,NULL,1,33,NULL,'2026-08-04','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(71,1,4,NULL,1,33,NULL,'2026-08-04','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(72,1,4,NULL,2,34,NULL,'2026-08-04','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(73,1,4,NULL,2,34,NULL,'2026-08-04','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(74,1,4,NULL,2,34,NULL,'2026-08-04','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(75,1,4,NULL,2,34,NULL,'2026-08-04','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(76,1,4,NULL,2,34,NULL,'2026-08-04','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(77,1,4,NULL,2,34,NULL,'2026-08-04','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(78,1,4,NULL,2,34,NULL,'2026-08-04','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(79,1,4,NULL,2,34,NULL,'2026-08-04','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(80,1,4,NULL,2,34,NULL,'2026-08-04','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(81,1,5,NULL,1,43,NULL,'2026-08-04','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(82,1,5,NULL,1,43,NULL,'2026-08-04','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(83,1,5,NULL,1,43,NULL,'2026-08-04','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(84,1,5,NULL,1,43,NULL,'2026-08-04','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(85,1,5,NULL,1,43,NULL,'2026-08-04','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(86,1,5,NULL,1,43,NULL,'2026-08-04','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(87,1,5,NULL,1,43,NULL,'2026-08-04','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(88,1,5,NULL,1,43,NULL,'2026-08-04','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(89,1,5,NULL,1,43,NULL,'2026-08-04','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(90,1,5,NULL,1,43,NULL,'2026-08-04','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(91,1,5,NULL,1,43,NULL,'2026-08-04','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(92,1,5,NULL,2,44,NULL,'2026-08-04','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(93,1,5,NULL,2,44,NULL,'2026-08-04','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(94,1,5,NULL,2,44,NULL,'2026-08-04','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(95,1,5,NULL,2,44,NULL,'2026-08-04','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(96,1,5,NULL,2,44,NULL,'2026-08-04','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(97,1,5,NULL,2,44,NULL,'2026-08-04','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(98,1,5,NULL,2,44,NULL,'2026-08-04','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(99,1,5,NULL,2,44,NULL,'2026-08-04','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(100,1,5,NULL,2,44,NULL,'2026-08-04','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(101,1,1,NULL,1,5,NULL,'2026-08-05','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(102,1,1,NULL,1,5,NULL,'2026-08-05','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(103,1,1,NULL,1,5,NULL,'2026-08-05','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(104,1,1,NULL,1,5,NULL,'2026-08-05','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(105,1,1,NULL,1,5,NULL,'2026-08-05','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(106,1,1,NULL,1,5,NULL,'2026-08-05','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(107,1,1,NULL,1,5,NULL,'2026-08-05','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(108,1,1,NULL,1,5,NULL,'2026-08-05','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(109,1,1,NULL,1,5,NULL,'2026-08-05','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(110,1,1,NULL,1,5,NULL,'2026-08-05','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(111,1,1,NULL,1,5,NULL,'2026-08-05','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(112,1,1,NULL,2,6,NULL,'2026-08-05','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(113,1,1,NULL,2,6,NULL,'2026-08-05','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(114,1,1,NULL,2,6,NULL,'2026-08-05','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(115,1,1,NULL,2,6,NULL,'2026-08-05','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(116,1,1,NULL,2,6,NULL,'2026-08-05','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(117,1,1,NULL,2,6,NULL,'2026-08-05','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(118,1,1,NULL,2,6,NULL,'2026-08-05','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(119,1,1,NULL,2,6,NULL,'2026-08-05','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(120,1,1,NULL,2,6,NULL,'2026-08-05','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(121,1,2,NULL,1,15,NULL,'2026-08-05','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(122,1,2,NULL,1,15,NULL,'2026-08-05','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(123,1,2,NULL,1,15,NULL,'2026-08-05','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(124,1,2,NULL,1,15,NULL,'2026-08-05','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(125,1,2,NULL,1,15,NULL,'2026-08-05','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(126,1,2,NULL,1,15,NULL,'2026-08-05','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(127,1,2,NULL,1,15,NULL,'2026-08-05','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(128,1,2,NULL,1,15,NULL,'2026-08-05','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(129,1,2,NULL,1,15,NULL,'2026-08-05','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(130,1,2,NULL,1,15,NULL,'2026-08-05','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(131,1,2,NULL,1,15,NULL,'2026-08-05','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(132,1,2,NULL,2,16,NULL,'2026-08-05','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(133,1,2,NULL,2,16,NULL,'2026-08-05','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(134,1,2,NULL,2,16,NULL,'2026-08-05','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(135,1,2,NULL,2,16,NULL,'2026-08-05','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(136,1,2,NULL,2,16,NULL,'2026-08-05','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(137,1,2,NULL,2,16,NULL,'2026-08-05','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(138,1,2,NULL,2,16,NULL,'2026-08-05','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(139,1,2,NULL,2,16,NULL,'2026-08-05','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(140,1,2,NULL,2,16,NULL,'2026-08-05','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(141,1,3,NULL,1,25,NULL,'2026-08-05','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(142,1,3,NULL,1,25,NULL,'2026-08-05','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(143,1,3,NULL,1,25,NULL,'2026-08-05','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(144,1,3,NULL,1,25,NULL,'2026-08-05','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(145,1,3,NULL,1,25,NULL,'2026-08-05','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(146,1,3,NULL,1,25,NULL,'2026-08-05','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(147,1,3,NULL,1,25,NULL,'2026-08-05','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(148,1,3,NULL,1,25,NULL,'2026-08-05','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(149,1,3,NULL,1,25,NULL,'2026-08-05','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(150,1,3,NULL,1,25,NULL,'2026-08-05','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(151,1,3,NULL,1,25,NULL,'2026-08-05','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(152,1,3,NULL,2,26,NULL,'2026-08-05','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(153,1,3,NULL,2,26,NULL,'2026-08-05','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(154,1,3,NULL,2,26,NULL,'2026-08-05','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(155,1,3,NULL,2,26,NULL,'2026-08-05','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(156,1,3,NULL,2,26,NULL,'2026-08-05','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(157,1,3,NULL,2,26,NULL,'2026-08-05','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(158,1,3,NULL,2,26,NULL,'2026-08-05','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(159,1,3,NULL,2,26,NULL,'2026-08-05','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(160,1,3,NULL,2,26,NULL,'2026-08-05','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(161,1,4,NULL,1,35,NULL,'2026-08-05','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(162,1,4,NULL,1,35,NULL,'2026-08-05','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(163,1,4,NULL,1,35,NULL,'2026-08-05','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(164,1,4,NULL,1,35,NULL,'2026-08-05','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(165,1,4,NULL,1,35,NULL,'2026-08-05','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(166,1,4,NULL,1,35,NULL,'2026-08-05','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(167,1,4,NULL,1,35,NULL,'2026-08-05','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(168,1,4,NULL,1,35,NULL,'2026-08-05','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(169,1,4,NULL,1,35,NULL,'2026-08-05','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(170,1,4,NULL,1,35,NULL,'2026-08-05','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(171,1,4,NULL,1,35,NULL,'2026-08-05','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(172,1,4,NULL,2,36,NULL,'2026-08-05','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(173,1,4,NULL,2,36,NULL,'2026-08-05','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(174,1,4,NULL,2,36,NULL,'2026-08-05','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(175,1,4,NULL,2,36,NULL,'2026-08-05','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(176,1,4,NULL,2,36,NULL,'2026-08-05','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(177,1,4,NULL,2,36,NULL,'2026-08-05','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(178,1,4,NULL,2,36,NULL,'2026-08-05','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(179,1,4,NULL,2,36,NULL,'2026-08-05','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(180,1,4,NULL,2,36,NULL,'2026-08-05','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(181,1,5,NULL,1,45,NULL,'2026-08-05','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(182,1,5,NULL,1,45,NULL,'2026-08-05','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(183,1,5,NULL,1,45,NULL,'2026-08-05','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(184,1,5,NULL,1,45,NULL,'2026-08-05','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(185,1,5,NULL,1,45,NULL,'2026-08-05','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(186,1,5,NULL,1,45,NULL,'2026-08-05','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(187,1,5,NULL,1,45,NULL,'2026-08-05','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(188,1,5,NULL,1,45,NULL,'2026-08-05','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(189,1,5,NULL,1,45,NULL,'2026-08-05','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(190,1,5,NULL,1,45,NULL,'2026-08-05','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(191,1,5,NULL,1,45,NULL,'2026-08-05','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(192,1,5,NULL,2,46,NULL,'2026-08-05','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(193,1,5,NULL,2,46,NULL,'2026-08-05','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(194,1,5,NULL,2,46,NULL,'2026-08-05','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(195,1,5,NULL,2,46,NULL,'2026-08-05','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(196,1,5,NULL,2,46,NULL,'2026-08-05','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(197,1,5,NULL,2,46,NULL,'2026-08-05','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(198,1,5,NULL,2,46,NULL,'2026-08-05','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(199,1,5,NULL,2,46,NULL,'2026-08-05','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(200,1,5,NULL,2,46,NULL,'2026-08-05','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(201,1,1,NULL,1,7,NULL,'2026-08-06','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(202,1,1,NULL,1,7,NULL,'2026-08-06','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(203,1,1,NULL,1,7,NULL,'2026-08-06','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(204,1,1,NULL,1,7,NULL,'2026-08-06','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(205,1,1,NULL,1,7,NULL,'2026-08-06','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(206,1,1,NULL,1,7,NULL,'2026-08-06','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(207,1,1,NULL,1,7,NULL,'2026-08-06','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(208,1,1,NULL,1,7,NULL,'2026-08-06','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(209,1,1,NULL,1,7,NULL,'2026-08-06','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(210,1,1,NULL,1,7,NULL,'2026-08-06','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(211,1,1,NULL,1,7,NULL,'2026-08-06','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(212,1,1,NULL,2,8,NULL,'2026-08-06','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(213,1,1,NULL,2,8,NULL,'2026-08-06','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(214,1,1,NULL,2,8,NULL,'2026-08-06','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(215,1,1,NULL,2,8,NULL,'2026-08-06','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(216,1,1,NULL,2,8,NULL,'2026-08-06','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(217,1,1,NULL,2,8,NULL,'2026-08-06','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(218,1,1,NULL,2,8,NULL,'2026-08-06','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(219,1,1,NULL,2,8,NULL,'2026-08-06','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(220,1,1,NULL,2,8,NULL,'2026-08-06','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(221,1,2,NULL,1,17,NULL,'2026-08-06','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(222,1,2,NULL,1,17,NULL,'2026-08-06','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(223,1,2,NULL,1,17,NULL,'2026-08-06','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(224,1,2,NULL,1,17,NULL,'2026-08-06','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(225,1,2,NULL,1,17,NULL,'2026-08-06','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(226,1,2,NULL,1,17,NULL,'2026-08-06','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(227,1,2,NULL,1,17,NULL,'2026-08-06','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(228,1,2,NULL,1,17,NULL,'2026-08-06','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(229,1,2,NULL,1,17,NULL,'2026-08-06','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(230,1,2,NULL,1,17,NULL,'2026-08-06','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(231,1,2,NULL,1,17,NULL,'2026-08-06','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(232,1,2,NULL,2,18,NULL,'2026-08-06','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(233,1,2,NULL,2,18,NULL,'2026-08-06','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(234,1,2,NULL,2,18,NULL,'2026-08-06','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(235,1,2,NULL,2,18,NULL,'2026-08-06','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(236,1,2,NULL,2,18,NULL,'2026-08-06','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(237,1,2,NULL,2,18,NULL,'2026-08-06','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(238,1,2,NULL,2,18,NULL,'2026-08-06','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(239,1,2,NULL,2,18,NULL,'2026-08-06','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(240,1,2,NULL,2,18,NULL,'2026-08-06','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(241,1,3,NULL,1,27,NULL,'2026-08-06','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(242,1,3,NULL,1,27,NULL,'2026-08-06','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(243,1,3,NULL,1,27,NULL,'2026-08-06','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(244,1,3,NULL,1,27,NULL,'2026-08-06','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(245,1,3,NULL,1,27,NULL,'2026-08-06','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(246,1,3,NULL,1,27,NULL,'2026-08-06','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(247,1,3,NULL,1,27,NULL,'2026-08-06','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(248,1,3,NULL,1,27,NULL,'2026-08-06','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(249,1,3,NULL,1,27,NULL,'2026-08-06','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(250,1,3,NULL,1,27,NULL,'2026-08-06','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(251,1,3,NULL,1,27,NULL,'2026-08-06','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(252,1,3,NULL,2,28,NULL,'2026-08-06','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(253,1,3,NULL,2,28,NULL,'2026-08-06','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(254,1,3,NULL,2,28,NULL,'2026-08-06','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(255,1,3,NULL,2,28,NULL,'2026-08-06','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(256,1,3,NULL,2,28,NULL,'2026-08-06','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(257,1,3,NULL,2,28,NULL,'2026-08-06','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(258,1,3,NULL,2,28,NULL,'2026-08-06','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(259,1,3,NULL,2,28,NULL,'2026-08-06','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(260,1,3,NULL,2,28,NULL,'2026-08-06','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(261,1,4,NULL,1,37,NULL,'2026-08-06','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(262,1,4,NULL,1,37,NULL,'2026-08-06','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(263,1,4,NULL,1,37,NULL,'2026-08-06','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(264,1,4,NULL,1,37,NULL,'2026-08-06','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(265,1,4,NULL,1,37,NULL,'2026-08-06','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(266,1,4,NULL,1,37,NULL,'2026-08-06','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(267,1,4,NULL,1,37,NULL,'2026-08-06','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(268,1,4,NULL,1,37,NULL,'2026-08-06','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(269,1,4,NULL,1,37,NULL,'2026-08-06','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(270,1,4,NULL,1,37,NULL,'2026-08-06','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(271,1,4,NULL,1,37,NULL,'2026-08-06','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(272,1,4,NULL,2,38,NULL,'2026-08-06','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(273,1,4,NULL,2,38,NULL,'2026-08-06','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(274,1,4,NULL,2,38,NULL,'2026-08-06','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(275,1,4,NULL,2,38,NULL,'2026-08-06','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(276,1,4,NULL,2,38,NULL,'2026-08-06','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(277,1,4,NULL,2,38,NULL,'2026-08-06','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(278,1,4,NULL,2,38,NULL,'2026-08-06','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(279,1,4,NULL,2,38,NULL,'2026-08-06','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(280,1,4,NULL,2,38,NULL,'2026-08-06','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:22','2026-08-04 21:50:22'),
(281,1,5,NULL,1,47,NULL,'2026-08-06','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(282,1,5,NULL,1,47,NULL,'2026-08-06','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(283,1,5,NULL,1,47,NULL,'2026-08-06','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(284,1,5,NULL,1,47,NULL,'2026-08-06','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(285,1,5,NULL,1,47,NULL,'2026-08-06','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(286,1,5,NULL,1,47,NULL,'2026-08-06','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(287,1,5,NULL,1,47,NULL,'2026-08-06','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(288,1,5,NULL,1,47,NULL,'2026-08-06','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(289,1,5,NULL,1,47,NULL,'2026-08-06','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(290,1,5,NULL,1,47,NULL,'2026-08-06','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(291,1,5,NULL,1,47,NULL,'2026-08-06','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(292,1,5,NULL,2,48,NULL,'2026-08-06','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(293,1,5,NULL,2,48,NULL,'2026-08-06','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(294,1,5,NULL,2,48,NULL,'2026-08-06','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(295,1,5,NULL,2,48,NULL,'2026-08-06','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(296,1,5,NULL,2,48,NULL,'2026-08-06','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(297,1,5,NULL,2,48,NULL,'2026-08-06','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(298,1,5,NULL,2,48,NULL,'2026-08-06','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(299,1,5,NULL,2,48,NULL,'2026-08-06','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(300,1,5,NULL,2,48,NULL,'2026-08-06','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(301,1,1,NULL,1,9,NULL,'2026-08-07','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(302,1,1,NULL,1,9,NULL,'2026-08-07','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(303,1,1,NULL,1,9,NULL,'2026-08-07','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(304,1,1,NULL,1,9,NULL,'2026-08-07','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(305,1,1,NULL,1,9,NULL,'2026-08-07','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(306,1,1,NULL,1,9,NULL,'2026-08-07','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(307,1,1,NULL,1,9,NULL,'2026-08-07','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(308,1,1,NULL,1,9,NULL,'2026-08-07','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(309,1,1,NULL,1,9,NULL,'2026-08-07','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(310,1,1,NULL,1,9,NULL,'2026-08-07','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(311,1,1,NULL,1,9,NULL,'2026-08-07','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(312,1,1,NULL,2,10,NULL,'2026-08-07','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(313,1,1,NULL,2,10,NULL,'2026-08-07','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(314,1,1,NULL,2,10,NULL,'2026-08-07','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(315,1,1,NULL,2,10,NULL,'2026-08-07','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(316,1,1,NULL,2,10,NULL,'2026-08-07','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(317,1,1,NULL,2,10,NULL,'2026-08-07','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(318,1,1,NULL,2,10,NULL,'2026-08-07','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(319,1,1,NULL,2,10,NULL,'2026-08-07','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(320,1,1,NULL,2,10,NULL,'2026-08-07','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(321,1,2,NULL,1,19,NULL,'2026-08-07','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(322,1,2,NULL,1,19,NULL,'2026-08-07','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(323,1,2,NULL,1,19,NULL,'2026-08-07','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(324,1,2,NULL,1,19,NULL,'2026-08-07','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(325,1,2,NULL,1,19,NULL,'2026-08-07','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(326,1,2,NULL,1,19,NULL,'2026-08-07','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(327,1,2,NULL,1,19,NULL,'2026-08-07','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(328,1,2,NULL,1,19,NULL,'2026-08-07','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(329,1,2,NULL,1,19,NULL,'2026-08-07','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(330,1,2,NULL,1,19,NULL,'2026-08-07','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(331,1,2,NULL,1,19,NULL,'2026-08-07','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(332,1,2,NULL,2,20,NULL,'2026-08-07','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(333,1,2,NULL,2,20,NULL,'2026-08-07','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(334,1,2,NULL,2,20,NULL,'2026-08-07','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(335,1,2,NULL,2,20,NULL,'2026-08-07','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(336,1,2,NULL,2,20,NULL,'2026-08-07','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(337,1,2,NULL,2,20,NULL,'2026-08-07','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(338,1,2,NULL,2,20,NULL,'2026-08-07','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(339,1,2,NULL,2,20,NULL,'2026-08-07','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(340,1,2,NULL,2,20,NULL,'2026-08-07','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(341,1,3,NULL,1,29,NULL,'2026-08-07','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(342,1,3,NULL,1,29,NULL,'2026-08-07','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(343,1,3,NULL,1,29,NULL,'2026-08-07','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(344,1,3,NULL,1,29,NULL,'2026-08-07','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(345,1,3,NULL,1,29,NULL,'2026-08-07','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(346,1,3,NULL,1,29,NULL,'2026-08-07','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(347,1,3,NULL,1,29,NULL,'2026-08-07','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(348,1,3,NULL,1,29,NULL,'2026-08-07','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(349,1,3,NULL,1,29,NULL,'2026-08-07','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(350,1,3,NULL,1,29,NULL,'2026-08-07','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(351,1,3,NULL,1,29,NULL,'2026-08-07','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(352,1,3,NULL,2,30,NULL,'2026-08-07','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(353,1,3,NULL,2,30,NULL,'2026-08-07','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(354,1,3,NULL,2,30,NULL,'2026-08-07','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(355,1,3,NULL,2,30,NULL,'2026-08-07','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(356,1,3,NULL,2,30,NULL,'2026-08-07','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(357,1,3,NULL,2,30,NULL,'2026-08-07','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(358,1,3,NULL,2,30,NULL,'2026-08-07','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(359,1,3,NULL,2,30,NULL,'2026-08-07','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(360,1,3,NULL,2,30,NULL,'2026-08-07','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(361,1,4,NULL,1,39,NULL,'2026-08-07','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(362,1,4,NULL,1,39,NULL,'2026-08-07','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(363,1,4,NULL,1,39,NULL,'2026-08-07','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(364,1,4,NULL,1,39,NULL,'2026-08-07','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(365,1,4,NULL,1,39,NULL,'2026-08-07','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(366,1,4,NULL,1,39,NULL,'2026-08-07','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(367,1,4,NULL,1,39,NULL,'2026-08-07','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(368,1,4,NULL,1,39,NULL,'2026-08-07','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(369,1,4,NULL,1,39,NULL,'2026-08-07','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(370,1,4,NULL,1,39,NULL,'2026-08-07','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(371,1,4,NULL,1,39,NULL,'2026-08-07','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(372,1,4,NULL,2,40,NULL,'2026-08-07','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(373,1,4,NULL,2,40,NULL,'2026-08-07','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(374,1,4,NULL,2,40,NULL,'2026-08-07','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(375,1,4,NULL,2,40,NULL,'2026-08-07','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(376,1,4,NULL,2,40,NULL,'2026-08-07','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(377,1,4,NULL,2,40,NULL,'2026-08-07','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(378,1,4,NULL,2,40,NULL,'2026-08-07','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(379,1,4,NULL,2,40,NULL,'2026-08-07','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(380,1,4,NULL,2,40,NULL,'2026-08-07','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(381,1,5,NULL,1,49,NULL,'2026-08-07','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(382,1,5,NULL,1,49,NULL,'2026-08-07','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(383,1,5,NULL,1,49,NULL,'2026-08-07','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(384,1,5,NULL,1,49,NULL,'2026-08-07','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(385,1,5,NULL,1,49,NULL,'2026-08-07','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(386,1,5,NULL,1,49,NULL,'2026-08-07','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(387,1,5,NULL,1,49,NULL,'2026-08-07','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(388,1,5,NULL,1,49,NULL,'2026-08-07','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(389,1,5,NULL,1,49,NULL,'2026-08-07','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(390,1,5,NULL,1,49,NULL,'2026-08-07','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(391,1,5,NULL,1,49,NULL,'2026-08-07','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(392,1,5,NULL,2,50,NULL,'2026-08-07','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(393,1,5,NULL,2,50,NULL,'2026-08-07','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(394,1,5,NULL,2,50,NULL,'2026-08-07','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(395,1,5,NULL,2,50,NULL,'2026-08-07','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(396,1,5,NULL,2,50,NULL,'2026-08-07','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(397,1,5,NULL,2,50,NULL,'2026-08-07','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(398,1,5,NULL,2,50,NULL,'2026-08-07','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(399,1,5,NULL,2,50,NULL,'2026-08-07','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(400,1,5,NULL,2,50,NULL,'2026-08-07','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(401,1,1,NULL,1,1,NULL,'2026-08-10','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(402,1,1,NULL,1,1,NULL,'2026-08-10','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(403,1,1,NULL,1,1,NULL,'2026-08-10','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(404,1,1,NULL,1,1,NULL,'2026-08-10','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(405,1,1,NULL,1,1,NULL,'2026-08-10','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(406,1,1,NULL,1,1,NULL,'2026-08-10','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(407,1,1,NULL,1,1,NULL,'2026-08-10','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(408,1,1,NULL,1,1,NULL,'2026-08-10','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(409,1,1,NULL,1,1,NULL,'2026-08-10','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(410,1,1,NULL,1,1,NULL,'2026-08-10','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(411,1,1,NULL,1,1,NULL,'2026-08-10','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(412,1,1,NULL,2,2,NULL,'2026-08-10','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(413,1,1,NULL,2,2,NULL,'2026-08-10','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(414,1,1,NULL,2,2,NULL,'2026-08-10','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(415,1,1,NULL,2,2,NULL,'2026-08-10','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(416,1,1,NULL,2,2,NULL,'2026-08-10','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(417,1,1,NULL,2,2,NULL,'2026-08-10','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(418,1,1,NULL,2,2,NULL,'2026-08-10','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(419,1,1,NULL,2,2,NULL,'2026-08-10','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(420,1,1,NULL,2,2,NULL,'2026-08-10','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(421,1,2,NULL,1,11,NULL,'2026-08-10','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(422,1,2,NULL,1,11,NULL,'2026-08-10','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(423,1,2,NULL,1,11,NULL,'2026-08-10','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(424,1,2,NULL,1,11,NULL,'2026-08-10','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(425,1,2,NULL,1,11,NULL,'2026-08-10','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(426,1,2,NULL,1,11,NULL,'2026-08-10','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(427,1,2,NULL,1,11,NULL,'2026-08-10','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(428,1,2,NULL,1,11,NULL,'2026-08-10','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(429,1,2,NULL,1,11,NULL,'2026-08-10','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(430,1,2,NULL,1,11,NULL,'2026-08-10','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(431,1,2,NULL,1,11,NULL,'2026-08-10','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(432,1,2,NULL,2,12,NULL,'2026-08-10','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(433,1,2,NULL,2,12,NULL,'2026-08-10','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(434,1,2,NULL,2,12,NULL,'2026-08-10','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(435,1,2,NULL,2,12,NULL,'2026-08-10','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(436,1,2,NULL,2,12,NULL,'2026-08-10','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(437,1,2,NULL,2,12,NULL,'2026-08-10','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(438,1,2,NULL,2,12,NULL,'2026-08-10','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(439,1,2,NULL,2,12,NULL,'2026-08-10','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(440,1,2,NULL,2,12,NULL,'2026-08-10','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(441,1,3,NULL,1,21,NULL,'2026-08-10','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(442,1,3,NULL,1,21,NULL,'2026-08-10','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(443,1,3,NULL,1,21,NULL,'2026-08-10','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(444,1,3,NULL,1,21,NULL,'2026-08-10','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(445,1,3,NULL,1,21,NULL,'2026-08-10','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(446,1,3,NULL,1,21,NULL,'2026-08-10','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(447,1,3,NULL,1,21,NULL,'2026-08-10','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(448,1,3,NULL,1,21,NULL,'2026-08-10','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(449,1,3,NULL,1,21,NULL,'2026-08-10','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(450,1,3,NULL,1,21,NULL,'2026-08-10','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(451,1,3,NULL,1,21,NULL,'2026-08-10','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(452,1,3,NULL,2,22,NULL,'2026-08-10','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(453,1,3,NULL,2,22,NULL,'2026-08-10','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(454,1,3,NULL,2,22,NULL,'2026-08-10','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(455,1,3,NULL,2,22,NULL,'2026-08-10','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(456,1,3,NULL,2,22,NULL,'2026-08-10','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(457,1,3,NULL,2,22,NULL,'2026-08-10','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(458,1,3,NULL,2,22,NULL,'2026-08-10','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(459,1,3,NULL,2,22,NULL,'2026-08-10','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(460,1,3,NULL,2,22,NULL,'2026-08-10','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(461,1,4,NULL,1,31,NULL,'2026-08-10','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(462,1,4,NULL,1,31,NULL,'2026-08-10','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(463,1,4,NULL,1,31,NULL,'2026-08-10','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(464,1,4,NULL,1,31,NULL,'2026-08-10','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(465,1,4,NULL,1,31,NULL,'2026-08-10','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(466,1,4,NULL,1,31,NULL,'2026-08-10','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(467,1,4,NULL,1,31,NULL,'2026-08-10','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(468,1,4,NULL,1,31,NULL,'2026-08-10','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(469,1,4,NULL,1,31,NULL,'2026-08-10','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(470,1,4,NULL,1,31,NULL,'2026-08-10','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(471,1,4,NULL,1,31,NULL,'2026-08-10','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(472,1,4,NULL,2,32,NULL,'2026-08-10','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(473,1,4,NULL,2,32,NULL,'2026-08-10','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(474,1,4,NULL,2,32,NULL,'2026-08-10','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(475,1,4,NULL,2,32,NULL,'2026-08-10','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(476,1,4,NULL,2,32,NULL,'2026-08-10','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(477,1,4,NULL,2,32,NULL,'2026-08-10','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(478,1,4,NULL,2,32,NULL,'2026-08-10','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(479,1,4,NULL,2,32,NULL,'2026-08-10','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(480,1,4,NULL,2,32,NULL,'2026-08-10','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(481,1,5,NULL,1,41,NULL,'2026-08-10','08:05:00','08:25:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(482,1,5,NULL,1,41,NULL,'2026-08-10','08:30:00','08:50:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(483,1,5,NULL,1,41,NULL,'2026-08-10','08:55:00','09:15:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(484,1,5,NULL,1,41,NULL,'2026-08-10','09:20:00','09:40:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(485,1,5,NULL,1,41,NULL,'2026-08-10','09:45:00','10:05:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(486,1,5,NULL,1,41,NULL,'2026-08-10','10:10:00','10:30:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(487,1,5,NULL,1,41,NULL,'2026-08-10','11:00:00','11:20:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(488,1,5,NULL,1,41,NULL,'2026-08-10','11:25:00','11:45:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(489,1,5,NULL,1,41,NULL,'2026-08-10','11:50:00','12:10:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(490,1,5,NULL,1,41,NULL,'2026-08-10','12:15:00','12:35:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(491,1,5,NULL,1,41,NULL,'2026-08-10','12:40:00','13:00:00',20,2,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(492,1,5,NULL,2,42,NULL,'2026-08-10','14:00:00','14:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(493,1,5,NULL,2,42,NULL,'2026-08-10','14:20:00','14:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(494,1,5,NULL,2,42,NULL,'2026-08-10','14:40:00','14:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(495,1,5,NULL,2,42,NULL,'2026-08-10','15:00:00','15:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(496,1,5,NULL,2,42,NULL,'2026-08-10','15:20:00','15:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(497,1,5,NULL,2,42,NULL,'2026-08-10','15:40:00','15:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(498,1,5,NULL,2,42,NULL,'2026-08-10','16:00:00','16:15:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(499,1,5,NULL,2,42,NULL,'2026-08-10','16:20:00','16:35:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23'),
(500,1,5,NULL,2,42,NULL,'2026-08-10','16:40:00','16:55:00',15,3,0,'Available',NULL,'2026-08-04 21:50:23','2026-08-04 21:50:23');
/*!40000 ALTER TABLE `appointment_slots` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `appointment_types`
--

DROP TABLE IF EXISTS `appointment_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointment_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `category` enum('New','Follow-up','Review','Procedure','Telemedicine','Emergency','Specialist') NOT NULL DEFAULT 'New',
  `color` varchar(7) NOT NULL DEFAULT '#0f5132',
  `default_duration_minutes` int(11) NOT NULL DEFAULT 30,
  `default_fee` decimal(14,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `requires_pre_auth` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `appointment_types_code_unique` (`code`),
  KEY `appointment_types_hospital_id_is_active_index` (`hospital_id`,`is_active`),
  CONSTRAINT `appointment_types_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointment_types`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `appointment_types` WRITE;
/*!40000 ALTER TABLE `appointment_types` DISABLE KEYS */;
INSERT INTO `appointment_types` VALUES
(1,1,'New Consultation','NEW-CONS','New','#0f5132',30,3000.00,NULL,0,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(2,1,'Follow-up Consultation','FUP-CONS','Follow-up','#198754',15,1500.00,NULL,0,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(3,1,'Review Consultation','RVW-CONS','Review','#6c757d',15,1500.00,NULL,0,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(4,1,'Specialist Consultation','SPC-CONS','Specialist','#0dcaf0',45,8000.00,NULL,0,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(5,1,'Antenatal Visit','ANC','New','#ffc107',20,2000.00,NULL,0,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(6,1,'Postnatal Visit','PNC','Follow-up','#fd7e14',20,2000.00,NULL,0,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(7,1,'Telemedicine Consultation','TELE','Telemedicine','#6610f2',20,2000.00,NULL,0,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(8,1,'Emergency Consultation','EMR-CONS','Emergency','#dc3545',60,5000.00,NULL,0,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(9,1,'Minor Procedure','PROC','Procedure','#fd7e14',60,15000.00,NULL,1,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL);
/*!40000 ALTER TABLE `appointment_types` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `provider_id` bigint(20) unsigned DEFAULT NULL,
  `appointment_number` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `estimated_duration` int(10) unsigned NOT NULL DEFAULT 30,
  `visit_type` enum('New','Follow-up','Review','Telemedicine') NOT NULL,
  `priority` enum('Routine','Urgent','Emergency') NOT NULL DEFAULT 'Routine',
  `reason_for_visit` text NOT NULL,
  `status` enum('Scheduled','Confirmed','Checked In','Completed','Cancelled','No Show') NOT NULL DEFAULT 'Scheduled',
  `encounter_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `notes` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `appointment_number` (`appointment_number`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES
(1,1,1,1,2,'FUTH-LF-APPT-2026-000001','2026-07-31','09:00:00',30,'New','Routine','Follow-up consultation','Scheduled',NULL,2,NULL,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(2,1,2,1,2,'FUTH-LF-APPT-2026-000002','2026-07-31','10:00:00',30,'New','Routine','Follow-up consultation','Scheduled',NULL,2,NULL,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(3,1,3,1,2,'FUTH-LF-APPT-2026-000003','2026-07-31','11:00:00',30,'New','Routine','Follow-up consultation','Scheduled',NULL,2,NULL,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(4,1,4,1,2,'FUTH-LF-APPT-2026-000004','2026-07-31','12:00:00',30,'New','Routine','Follow-up consultation','Scheduled',NULL,2,NULL,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL);
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `bed_transfers`
--

DROP TABLE IF EXISTS `bed_transfers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `bed_transfers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transfer_number` varchar(191) NOT NULL,
  `admission_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `from_ward_id` bigint(20) unsigned DEFAULT NULL,
  `from_bed_id` bigint(20) unsigned DEFAULT NULL,
  `to_ward_id` bigint(20) unsigned NOT NULL,
  `to_bed_id` bigint(20) unsigned DEFAULT NULL,
  `transfer_date` datetime NOT NULL,
  `reason` text DEFAULT NULL,
  `authorized_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bed_transfers_transfer_number_unique` (`transfer_number`),
  KEY `bed_transfers_from_ward_id_foreign` (`from_ward_id`),
  KEY `bed_transfers_from_bed_id_foreign` (`from_bed_id`),
  KEY `bed_transfers_to_ward_id_foreign` (`to_ward_id`),
  KEY `bed_transfers_to_bed_id_foreign` (`to_bed_id`),
  KEY `bed_transfers_authorized_by_foreign` (`authorized_by`),
  KEY `bed_transfers_admission_id_index` (`admission_id`),
  KEY `bed_transfers_patient_id_transfer_date_index` (`patient_id`,`transfer_date`),
  CONSTRAINT `bed_transfers_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bed_transfers_authorized_by_foreign` FOREIGN KEY (`authorized_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bed_transfers_from_bed_id_foreign` FOREIGN KEY (`from_bed_id`) REFERENCES `beds` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bed_transfers_from_ward_id_foreign` FOREIGN KEY (`from_ward_id`) REFERENCES `wards` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bed_transfers_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bed_transfers_to_bed_id_foreign` FOREIGN KEY (`to_bed_id`) REFERENCES `beds` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bed_transfers_to_ward_id_foreign` FOREIGN KEY (`to_ward_id`) REFERENCES `wards` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bed_transfers`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `bed_transfers` WRITE;
/*!40000 ALTER TABLE `bed_transfers` DISABLE KEYS */;
/*!40000 ALTER TABLE `bed_transfers` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `beds`
--

DROP TABLE IF EXISTS `beds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `beds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ward_id` bigint(20) unsigned NOT NULL,
  `bed_number` varchar(191) NOT NULL,
  `code` varchar(191) DEFAULT NULL,
  `type` enum('Regular','Electric','ICU','HDU','Baby Cot','Stretchers','Recovery','Other') NOT NULL DEFAULT 'Regular',
  `class` enum('General','Private','Semi-Private','VIP','Suite') NOT NULL DEFAULT 'General',
  `daily_rate` decimal(14,2) NOT NULL DEFAULT 0.00,
  `status` enum('Available','Occupied','Reserved','Out of Service','Cleaning','Maintenance') NOT NULL DEFAULT 'Available',
  `notes` varchar(191) DEFAULT NULL,
  `current_admission_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `beds_bed_number_unique` (`bed_number`),
  KEY `beds_ward_id_status_index` (`ward_id`,`status`),
  KEY `beds_bed_number_index` (`bed_number`),
  KEY `beds_current_admission_id_foreign` (`current_admission_id`),
  CONSTRAINT `beds_current_admission_id_foreign` FOREIGN KEY (`current_admission_id`) REFERENCES `admissions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `beds_ward_id_foreign` FOREIGN KEY (`ward_id`) REFERENCES `wards` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beds`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `beds` WRITE;
/*!40000 ALTER TABLE `beds` DISABLE KEYS */;
INSERT INTO `beds` VALUES
(1,1,'MMW-01','MMW/B1','Regular','General',5000.00,'Occupied',NULL,1,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(2,1,'MMW-02','MMW/B2','Regular','General',5000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(3,1,'MMW-03','MMW/B3','Regular','Semi-Private',5000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(4,1,'MMW-04','MMW/B4','Regular','Private',15000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(5,1,'MMW-05','MMW/B5','Regular','Private',15000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(6,2,'FMW-01','FMW/B1','Regular','General',5000.00,'Occupied',NULL,2,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(7,2,'FMW-02','FMW/B2','Regular','General',5000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(8,2,'FMW-03','FMW/B3','Regular','Semi-Private',5000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(9,2,'FMW-04','FMW/B4','Regular','Private',15000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(10,2,'FMW-05','FMW/B5','Regular','Private',15000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(11,3,'MSW-01','MSW/B1','Regular','General',7000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(12,3,'MSW-02','MSW/B2','Regular','General',7000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(13,3,'MSW-03','MSW/B3','Regular','Semi-Private',7000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(14,3,'MSW-04','MSW/B4','Regular','Private',17000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(15,3,'MSW-05','MSW/B5','Regular','Private',17000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(16,4,'FSW-01','FSW/B1','Regular','General',7000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(17,4,'FSW-02','FSW/B2','Regular','General',7000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(18,4,'FSW-03','FSW/B3','Regular','Semi-Private',7000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(19,4,'FSW-04','FSW/B4','Regular','Private',17000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(20,4,'FSW-05','FSW/B5','Regular','Private',17000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(21,5,'PAED-W-01','PAED-W/B1','Regular','General',6000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(22,5,'PAED-W-02','PAED-W/B2','Regular','General',6000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(23,5,'PAED-W-03','PAED-W/B3','Regular','Semi-Private',6000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(24,5,'PAED-W-04','PAED-W/B4','Regular','Private',16000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(25,5,'PAED-W-05','PAED-W/B5','Regular','Private',16000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(26,6,'MAT-W-01','MAT-W/B1','Regular','General',8000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(27,6,'MAT-W-02','MAT-W/B2','Regular','General',8000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(28,6,'MAT-W-03','MAT-W/B3','Regular','Semi-Private',8000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(29,6,'MAT-W-04','MAT-W/B4','Regular','Private',18000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(30,6,'MAT-W-05','MAT-W/B5','Regular','Private',18000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(31,7,'ICU-01','ICU/B1','ICU','General',50000.00,'Occupied',NULL,3,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(32,7,'ICU-02','ICU/B2','ICU','General',50000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(33,7,'ICU-03','ICU/B3','ICU','Semi-Private',50000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(34,7,'ICU-04','ICU/B4','ICU','Private',60000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(35,7,'ICU-05','ICU/B5','ICU','Private',60000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(36,8,'HDU-01','HDU/B1','HDU','General',25000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(37,8,'HDU-02','HDU/B2','HDU','General',25000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(38,8,'HDU-03','HDU/B3','HDU','Semi-Private',25000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(39,8,'HDU-04','HDU/B4','HDU','Private',35000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(40,8,'HDU-05','HDU/B5','HDU','Private',35000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(41,9,'PRIV-A-01','PRIV-A/B1','Regular','Private',30000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(42,9,'PRIV-A-02','PRIV-A/B2','Regular','Private',30000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(43,9,'PRIV-A-03','PRIV-A/B3','Regular','Private',30000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(44,9,'PRIV-A-04','PRIV-A/B4','Regular','Private',30000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(45,9,'PRIV-A-05','PRIV-A/B5','Regular','Private',30000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(46,10,'ER-OBS-01','ER-OBS/B1','Regular','General',3000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(47,10,'ER-OBS-02','ER-OBS/B2','Regular','General',3000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(48,10,'ER-OBS-03','ER-OBS/B3','Regular','Semi-Private',3000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(49,10,'ER-OBS-04','ER-OBS/B4','Regular','Private',13000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(50,10,'ER-OBS-05','ER-OBS/B5','Regular','Private',13000.00,'Available',NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL);
/*!40000 ALTER TABLE `beds` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `blood_crossmatches`
--

DROP TABLE IF EXISTS `blood_crossmatches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blood_crossmatches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `crossmatch_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `blood_request_id` bigint(20) unsigned NOT NULL,
  `blood_unit_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `performed_by` bigint(20) unsigned NOT NULL,
  `method` enum('Saline','Albumin','AHG','Gel Card','Electronic Crossmatch') NOT NULL DEFAULT 'Saline',
  `major_crossmatch` enum('Compatible','Incompatible','Pending') NOT NULL DEFAULT 'Pending',
  `minor_crossmatch` enum('Compatible','Incompatible','Not Done','Pending') NOT NULL DEFAULT 'Not Done',
  `results` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `performed_at` datetime NOT NULL,
  `status` enum('Pending','Compatible','Incompatible','Expired','Transfused','Discarded') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blood_crossmatches_crossmatch_number_unique` (`crossmatch_number`),
  KEY `blood_crossmatches_hospital_id_foreign` (`hospital_id`),
  KEY `blood_crossmatches_patient_id_foreign` (`patient_id`),
  KEY `blood_crossmatches_performed_by_foreign` (`performed_by`),
  KEY `blood_crossmatches_blood_request_id_status_index` (`blood_request_id`,`status`),
  KEY `blood_crossmatches_blood_unit_id_status_index` (`blood_unit_id`,`status`),
  CONSTRAINT `blood_crossmatches_blood_request_id_foreign` FOREIGN KEY (`blood_request_id`) REFERENCES `blood_requests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_crossmatches_blood_unit_id_foreign` FOREIGN KEY (`blood_unit_id`) REFERENCES `blood_units` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_crossmatches_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_crossmatches_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_crossmatches_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blood_crossmatches`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `blood_crossmatches` WRITE;
/*!40000 ALTER TABLE `blood_crossmatches` DISABLE KEYS */;
/*!40000 ALTER TABLE `blood_crossmatches` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `blood_donations`
--

DROP TABLE IF EXISTS `blood_donations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blood_donations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `donation_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `donor_id` bigint(20) unsigned NOT NULL,
  `donation_date` date NOT NULL,
  `donation_time` time NOT NULL,
  `donation_type` enum('Whole Blood','Plasma','Platelets','Double Red Cells','Cryoprecipitate') NOT NULL DEFAULT 'Whole Blood',
  `volume_collected_ml` decimal(6,0) NOT NULL DEFAULT 450,
  `collected_by` bigint(20) unsigned NOT NULL,
  `collection_site` varchar(191) DEFAULT NULL,
  `vein_used` varchar(191) DEFAULT NULL,
  `adverse_reactions` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Collected','Screening','Quarantined','Available','Discarded','Expired','Transfused') NOT NULL DEFAULT 'Collected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blood_donations_donation_number_unique` (`donation_number`),
  KEY `blood_donations_hospital_id_foreign` (`hospital_id`),
  KEY `blood_donations_collected_by_foreign` (`collected_by`),
  KEY `blood_donations_donor_id_donation_date_index` (`donor_id`,`donation_date`),
  CONSTRAINT `blood_donations_collected_by_foreign` FOREIGN KEY (`collected_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_donations_donor_id_foreign` FOREIGN KEY (`donor_id`) REFERENCES `blood_donors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_donations_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blood_donations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `blood_donations` WRITE;
/*!40000 ALTER TABLE `blood_donations` DISABLE KEYS */;
INSERT INTO `blood_donations` VALUES
(1,'BDN-2026-000001',1,1,'2026-07-25','10:00:00','Whole Blood',450,6,'Blood Bank','Left Antecubital',NULL,NULL,'Available','2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(2,'BDN-2026-000002',1,2,'2026-07-26','10:00:00','Whole Blood',450,6,'Blood Bank','Left Antecubital',NULL,NULL,'Available','2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(3,'BDN-2026-000003',1,3,'2026-07-27','10:00:00','Whole Blood',450,6,'Blood Bank','Left Antecubital',NULL,NULL,'Available','2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(4,'BDN-2026-000004',1,4,'2026-07-28','10:00:00','Whole Blood',450,6,'Blood Bank','Left Antecubital',NULL,NULL,'Available','2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(5,'BDN-2026-000005',1,5,'2026-07-29','10:00:00','Whole Blood',450,6,'Blood Bank','Left Antecubital',NULL,NULL,'Available','2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(6,'BDN-2026-000006',1,1,'2026-07-25','10:00:00','Whole Blood',450,6,'Blood Bank','Left Antecubital',NULL,NULL,'Available','2026-08-04 22:24:44','2026-08-04 22:24:44',NULL),
(7,'BDN-2026-000007',1,2,'2026-07-26','10:00:00','Whole Blood',450,6,'Blood Bank','Left Antecubital',NULL,NULL,'Available','2026-08-04 22:24:44','2026-08-04 22:24:44',NULL),
(8,'BDN-2026-000008',1,3,'2026-07-27','10:00:00','Whole Blood',450,6,'Blood Bank','Left Antecubital',NULL,NULL,'Available','2026-08-04 22:24:44','2026-08-04 22:24:44',NULL),
(9,'BDN-2026-000009',1,4,'2026-07-28','10:00:00','Whole Blood',450,6,'Blood Bank','Left Antecubital',NULL,NULL,'Available','2026-08-04 22:24:44','2026-08-04 22:24:44',NULL),
(10,'BDN-2026-000010',1,5,'2026-07-29','10:00:00','Whole Blood',450,6,'Blood Bank','Left Antecubital',NULL,NULL,'Available','2026-08-04 22:24:44','2026-08-04 22:24:44',NULL),
(11,'BDN-2026-000011',1,1,'2026-07-25','10:00:00','Whole Blood',450,6,'Blood Bank','Left Antecubital',NULL,NULL,'Available','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(12,'BDN-2026-000012',1,2,'2026-07-26','10:00:00','Whole Blood',450,6,'Blood Bank','Left Antecubital',NULL,NULL,'Available','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(13,'BDN-2026-000013',1,3,'2026-07-27','10:00:00','Whole Blood',450,6,'Blood Bank','Left Antecubital',NULL,NULL,'Available','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(14,'BDN-2026-000014',1,4,'2026-07-28','10:00:00','Whole Blood',450,6,'Blood Bank','Left Antecubital',NULL,NULL,'Available','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(15,'BDN-2026-000015',1,5,'2026-07-29','10:00:00','Whole Blood',450,6,'Blood Bank','Left Antecubital',NULL,NULL,'Available','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL);
/*!40000 ALTER TABLE `blood_donations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `blood_donors`
--

DROP TABLE IF EXISTS `blood_donors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blood_donors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `donor_number` varchar(191) NOT NULL,
  `donor_type` enum('Voluntary','Replacement','Autologous','Directed','Paid') NOT NULL DEFAULT 'Voluntary',
  `first_name` varchar(191) NOT NULL,
  `middle_name` varchar(191) DEFAULT NULL,
  `last_name` varchar(191) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `date_of_birth` date NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `occupation` varchar(191) DEFAULT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `rh_factor` enum('Positive','Negative') NOT NULL,
  `last_donation_date` date DEFAULT NULL,
  `total_donations` int(11) NOT NULL DEFAULT 0,
  `total_volume_donated_ml` decimal(8,0) NOT NULL DEFAULT 0,
  `is_eligible` tinyint(1) NOT NULL DEFAULT 1,
  `ineligibility_reason` text DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Active','Inactive','Deferred','Blacklisted') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blood_donors_donor_number_unique` (`donor_number`),
  KEY `blood_donors_hospital_id_status_index` (`hospital_id`,`status`),
  KEY `blood_donors_blood_group_rh_factor_index` (`blood_group`,`rh_factor`),
  CONSTRAINT `blood_donors_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blood_donors`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `blood_donors` WRITE;
/*!40000 ALTER TABLE `blood_donors` DISABLE KEYS */;
INSERT INTO `blood_donors` VALUES
(1,1,'DON-2026-000001','Voluntary','Chinedu',NULL,'Okafor','Male','1990-05-15','08031234567',NULL,NULL,'Engineer','O+','Positive','2026-07-25',3,1350,1,NULL,NULL,NULL,'Active','2026-08-04 22:24:38','2026-08-04 22:24:59',NULL),
(2,1,'DON-2026-000002','Voluntary','Fatima',NULL,'Ibrahim','Female','1985-08-22','08037654321',NULL,NULL,'Teacher','A+','Positive','2026-07-26',3,1350,1,NULL,NULL,NULL,'Active','2026-08-04 22:24:38','2026-08-04 22:24:59',NULL),
(3,1,'DON-2026-000003','Voluntary','Samuel',NULL,'Eze','Male','1988-12-03','08089876543',NULL,NULL,'Civil Servant','B+','Positive','2026-07-27',3,1350,1,NULL,NULL,NULL,'Active','2026-08-04 22:24:38','2026-08-04 22:24:59',NULL),
(4,1,'DON-2026-000004','Voluntary','Grace',NULL,'Adeyemi','Female','1992-03-17','08052345678',NULL,NULL,'Nurse','AB+','Positive','2026-07-28',3,1350,1,NULL,NULL,NULL,'Active','2026-08-04 22:24:38','2026-08-04 22:24:59',NULL),
(5,1,'DON-2026-000005','Voluntary','Mohammed',NULL,'Bello','Male','1987-07-29','08064567890',NULL,NULL,'Trader','O-','Negative','2026-07-29',3,1350,1,NULL,NULL,NULL,'Active','2026-08-04 22:24:38','2026-08-04 22:24:59',NULL),
(6,1,'DON-2026-000006','Voluntary','Aisha',NULL,'Mohammed','Female','1993-11-08','08078901234',NULL,NULL,'Banker','A-','Negative',NULL,0,0,1,NULL,NULL,NULL,'Active','2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(7,1,'DON-2026-000007','Voluntary','David',NULL,'Olawale','Male','1991-04-21','08083456789',NULL,NULL,'Driver','B-','Negative',NULL,0,0,1,NULL,NULL,NULL,'Active','2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(8,1,'DON-2026-000008','Voluntary','Mary',NULL,'Okon','Female','1989-09-14','08095678901',NULL,NULL,'Trader','O+','Positive',NULL,0,0,1,NULL,NULL,NULL,'Active','2026-08-04 22:24:38','2026-08-04 22:24:38',NULL);
/*!40000 ALTER TABLE `blood_donors` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `blood_requests`
--

DROP TABLE IF EXISTS `blood_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blood_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `request_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `encounter_id` bigint(20) unsigned DEFAULT NULL,
  `admission_id` bigint(20) unsigned DEFAULT NULL,
  `requested_by` bigint(20) unsigned NOT NULL,
  `requested_blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-','Unknown') NOT NULL,
  `component_requested` enum('Whole Blood','Packed Red Cells','Plasma','Platelets','Cryoprecipitate','Fresh Frozen Plasma') NOT NULL DEFAULT 'Whole Blood',
  `units_requested` decimal(6,0) NOT NULL DEFAULT 1,
  `urgency` enum('Routine','Urgent','Emergency','STAT') NOT NULL DEFAULT 'Routine',
  `required_by` datetime NOT NULL,
  `clinical_indication` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `patient_hb_level` decimal(5,1) DEFAULT NULL,
  `patient_blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-','Unknown') NOT NULL DEFAULT 'Unknown',
  `patient_rh_factor` enum('Positive','Negative','Unknown') NOT NULL DEFAULT 'Unknown',
  `crossmatch_notes` text DEFAULT NULL,
  `status` enum('Pending','Approved','Crossmatched','Issued','Transfused','Cancelled','Rejected') NOT NULL DEFAULT 'Pending',
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `crossmatched_by` bigint(20) unsigned DEFAULT NULL,
  `crossmatched_at` datetime DEFAULT NULL,
  `issued_by` bigint(20) unsigned DEFAULT NULL,
  `issued_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blood_requests_request_number_unique` (`request_number`),
  KEY `blood_requests_hospital_id_foreign` (`hospital_id`),
  KEY `blood_requests_encounter_id_foreign` (`encounter_id`),
  KEY `blood_requests_admission_id_foreign` (`admission_id`),
  KEY `blood_requests_requested_by_foreign` (`requested_by`),
  KEY `blood_requests_approved_by_foreign` (`approved_by`),
  KEY `blood_requests_crossmatched_by_foreign` (`crossmatched_by`),
  KEY `blood_requests_issued_by_foreign` (`issued_by`),
  KEY `blood_requests_patient_id_status_index` (`patient_id`,`status`),
  CONSTRAINT `blood_requests_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blood_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blood_requests_crossmatched_by_foreign` FOREIGN KEY (`crossmatched_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blood_requests_encounter_id_foreign` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blood_requests_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_requests_issued_by_foreign` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blood_requests_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blood_requests`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `blood_requests` WRITE;
/*!40000 ALTER TABLE `blood_requests` DISABLE KEYS */;
INSERT INTO `blood_requests` VALUES
(1,'BTR-2026-000001',1,3,NULL,NULL,2,'O+','Whole Blood',2,'Urgent','2026-08-05 22:24:38','Severe anaemia, Hb 5.2 g/dL','Severe Malaria with Anaemia',5.2,'O+','Positive',NULL,'Approved',2,'2026-08-04 22:24:38',NULL,NULL,NULL,NULL,NULL,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL);
/*!40000 ALTER TABLE `blood_requests` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `blood_transfusions`
--

DROP TABLE IF EXISTS `blood_transfusions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blood_transfusions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transfusion_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `encounter_id` bigint(20) unsigned DEFAULT NULL,
  `admission_id` bigint(20) unsigned DEFAULT NULL,
  `blood_request_id` bigint(20) unsigned NOT NULL,
  `blood_unit_id` bigint(20) unsigned NOT NULL,
  `crossmatch_id` bigint(20) unsigned DEFAULT NULL,
  `transfused_by` bigint(20) unsigned NOT NULL,
  `verified_by` bigint(20) unsigned DEFAULT NULL,
  `transfusion_started_at` datetime NOT NULL,
  `transfusion_ended_at` datetime DEFAULT NULL,
  `volume_transfused_ml` decimal(6,0) NOT NULL DEFAULT 0,
  `site_of_transfusion` varchar(191) DEFAULT NULL,
  `cannula_size` varchar(191) DEFAULT NULL,
  `pre_transfusion_bp` decimal(8,0) DEFAULT NULL,
  `post_transfusion_bp` decimal(8,0) DEFAULT NULL,
  `pre_transfusion_temp_c` decimal(4,1) DEFAULT NULL,
  `post_transfusion_temp_c` decimal(4,1) DEFAULT NULL,
  `pre_transfusion_pulse` decimal(4,0) DEFAULT NULL,
  `post_transfusion_pulse` decimal(4,0) DEFAULT NULL,
  `adverse_reactions` text DEFAULT NULL,
  `reaction_severity` enum('None','Mild','Moderate','Severe','Fatal') NOT NULL DEFAULT 'None',
  `action_taken` text DEFAULT NULL,
  `status` enum('In Progress','Completed','Interrupted','Cancelled') NOT NULL DEFAULT 'In Progress',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blood_transfusions_transfusion_number_unique` (`transfusion_number`),
  KEY `blood_transfusions_hospital_id_foreign` (`hospital_id`),
  KEY `blood_transfusions_encounter_id_foreign` (`encounter_id`),
  KEY `blood_transfusions_admission_id_foreign` (`admission_id`),
  KEY `blood_transfusions_blood_request_id_foreign` (`blood_request_id`),
  KEY `blood_transfusions_blood_unit_id_foreign` (`blood_unit_id`),
  KEY `blood_transfusions_crossmatch_id_foreign` (`crossmatch_id`),
  KEY `blood_transfusions_transfused_by_foreign` (`transfused_by`),
  KEY `blood_transfusions_verified_by_foreign` (`verified_by`),
  KEY `blood_transfusions_patient_id_transfusion_started_at_index` (`patient_id`,`transfusion_started_at`),
  CONSTRAINT `blood_transfusions_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blood_transfusions_blood_request_id_foreign` FOREIGN KEY (`blood_request_id`) REFERENCES `blood_requests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_transfusions_blood_unit_id_foreign` FOREIGN KEY (`blood_unit_id`) REFERENCES `blood_units` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_transfusions_crossmatch_id_foreign` FOREIGN KEY (`crossmatch_id`) REFERENCES `blood_crossmatches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blood_transfusions_encounter_id_foreign` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blood_transfusions_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_transfusions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_transfusions_transfused_by_foreign` FOREIGN KEY (`transfused_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_transfusions_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blood_transfusions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `blood_transfusions` WRITE;
/*!40000 ALTER TABLE `blood_transfusions` DISABLE KEYS */;
/*!40000 ALTER TABLE `blood_transfusions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `blood_units`
--

DROP TABLE IF EXISTS `blood_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blood_units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `unit_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `donation_id` bigint(20) unsigned NOT NULL,
  `donor_id` bigint(20) unsigned NOT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `component_type` enum('Whole Blood','Packed Red Cells','Plasma','Platelets','Cryoprecipitate','Fresh Frozen Plasma') NOT NULL DEFAULT 'Whole Blood',
  `volume_ml` decimal(6,0) NOT NULL DEFAULT 450,
  `collection_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `storage_location` varchar(191) DEFAULT NULL,
  `storage_temperature_c` decimal(4,1) NOT NULL DEFAULT 4.0,
  `hiv_status` enum('Pending','Negative','Positive','Not Tested') NOT NULL DEFAULT 'Pending',
  `hepatitis_b_status` enum('Pending','Negative','Positive','Not Tested') NOT NULL DEFAULT 'Pending',
  `hepatitis_c_status` enum('Pending','Negative','Positive','Not Tested') NOT NULL DEFAULT 'Pending',
  `syphilis_status` enum('Pending','Negative','Positive','Not Tested') NOT NULL DEFAULT 'Pending',
  `malaria_status` enum('Pending','Negative','Positive','Not Tested') NOT NULL DEFAULT 'Pending',
  `screening_notes` text DEFAULT NULL,
  `screened_by` bigint(20) unsigned DEFAULT NULL,
  `screened_at` datetime DEFAULT NULL,
  `is_screened` tinyint(1) NOT NULL DEFAULT 0,
  `is_quarantined` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('Collected','Screening','Available','Reserved','Transfused','Discarded','Expired') NOT NULL DEFAULT 'Collected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blood_units_unit_number_unique` (`unit_number`),
  KEY `blood_units_hospital_id_foreign` (`hospital_id`),
  KEY `blood_units_donation_id_foreign` (`donation_id`),
  KEY `blood_units_donor_id_foreign` (`donor_id`),
  KEY `blood_units_screened_by_foreign` (`screened_by`),
  KEY `blood_units_blood_group_component_type_status_index` (`blood_group`,`component_type`,`status`),
  KEY `blood_units_expiry_date_index` (`expiry_date`),
  CONSTRAINT `blood_units_donation_id_foreign` FOREIGN KEY (`donation_id`) REFERENCES `blood_donations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_units_donor_id_foreign` FOREIGN KEY (`donor_id`) REFERENCES `blood_donors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_units_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blood_units_screened_by_foreign` FOREIGN KEY (`screened_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blood_units`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `blood_units` WRITE;
/*!40000 ALTER TABLE `blood_units` DISABLE KEYS */;
INSERT INTO `blood_units` VALUES
(1,'BHU-2026-000001',1,1,1,'O+','Whole Blood',450,'2026-07-25','2026-09-08','Blood Bank Refrigerator A',4.0,'Negative','Negative','Negative','Negative','Negative',NULL,6,'2026-07-25 00:00:00',1,0,'Available','2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(2,'BHU-2026-000002',1,2,2,'A+','Whole Blood',450,'2026-07-26','2026-09-08','Blood Bank Refrigerator A',4.0,'Negative','Negative','Negative','Negative','Negative',NULL,6,'2026-07-26 00:00:00',1,0,'Available','2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(3,'BHU-2026-000003',1,3,3,'B+','Whole Blood',450,'2026-07-27','2026-09-08','Blood Bank Refrigerator A',4.0,'Negative','Negative','Negative','Negative','Negative',NULL,6,'2026-07-27 00:00:00',1,0,'Available','2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(4,'BHU-2026-000004',1,4,4,'AB+','Whole Blood',450,'2026-07-28','2026-09-08','Blood Bank Refrigerator A',4.0,'Negative','Negative','Negative','Negative','Negative',NULL,6,'2026-07-28 00:00:00',1,0,'Available','2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(5,'BHU-2026-000005',1,5,5,'O-','Whole Blood',450,'2026-07-29','2026-09-08','Blood Bank Refrigerator A',4.0,'Negative','Negative','Negative','Negative','Negative',NULL,6,'2026-07-29 00:00:00',1,0,'Available','2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(6,'BHU-2026-000006',1,6,1,'O+','Whole Blood',450,'2026-07-25','2026-09-08','Blood Bank Refrigerator A',4.0,'Negative','Negative','Negative','Negative','Negative',NULL,6,'2026-07-25 00:00:00',1,0,'Available','2026-08-04 22:24:44','2026-08-04 22:24:44',NULL),
(7,'BHU-2026-000007',1,7,2,'A+','Whole Blood',450,'2026-07-26','2026-09-08','Blood Bank Refrigerator A',4.0,'Negative','Negative','Negative','Negative','Negative',NULL,6,'2026-07-26 00:00:00',1,0,'Available','2026-08-04 22:24:44','2026-08-04 22:24:44',NULL),
(8,'BHU-2026-000008',1,8,3,'B+','Whole Blood',450,'2026-07-27','2026-09-08','Blood Bank Refrigerator A',4.0,'Negative','Negative','Negative','Negative','Negative',NULL,6,'2026-07-27 00:00:00',1,0,'Available','2026-08-04 22:24:44','2026-08-04 22:24:44',NULL),
(9,'BHU-2026-000009',1,9,4,'AB+','Whole Blood',450,'2026-07-28','2026-09-08','Blood Bank Refrigerator A',4.0,'Negative','Negative','Negative','Negative','Negative',NULL,6,'2026-07-28 00:00:00',1,0,'Available','2026-08-04 22:24:44','2026-08-04 22:24:44',NULL),
(10,'BHU-2026-000010',1,10,5,'O-','Whole Blood',450,'2026-07-29','2026-09-08','Blood Bank Refrigerator A',4.0,'Negative','Negative','Negative','Negative','Negative',NULL,6,'2026-07-29 00:00:00',1,0,'Available','2026-08-04 22:24:44','2026-08-04 22:24:44',NULL),
(11,'BHU-2026-000011',1,11,1,'O+','Whole Blood',450,'2026-07-25','2026-09-08','Blood Bank Refrigerator A',4.0,'Negative','Negative','Negative','Negative','Negative',NULL,6,'2026-07-25 00:00:00',1,0,'Available','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(12,'BHU-2026-000012',1,12,2,'A+','Whole Blood',450,'2026-07-26','2026-09-08','Blood Bank Refrigerator A',4.0,'Negative','Negative','Negative','Negative','Negative',NULL,6,'2026-07-26 00:00:00',1,0,'Available','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(13,'BHU-2026-000013',1,13,3,'B+','Whole Blood',450,'2026-07-27','2026-09-08','Blood Bank Refrigerator A',4.0,'Negative','Negative','Negative','Negative','Negative',NULL,6,'2026-07-27 00:00:00',1,0,'Available','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(14,'BHU-2026-000014',1,14,4,'AB+','Whole Blood',450,'2026-07-28','2026-09-08','Blood Bank Refrigerator A',4.0,'Negative','Negative','Negative','Negative','Negative',NULL,6,'2026-07-28 00:00:00',1,0,'Available','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(15,'BHU-2026-000015',1,15,5,'O-','Whole Blood',450,'2026-07-29','2026-09-08','Blood Bank Refrigerator A',4.0,'Negative','Negative','Negative','Negative','Negative',NULL,6,'2026-07-29 00:00:00',1,0,'Available','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL);
/*!40000 ALTER TABLE `blood_units` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `budgets`
--

DROP TABLE IF EXISTS `budgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `budgets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `chart_of_account_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `period` enum('Monthly','Quarterly','Annual') NOT NULL DEFAULT 'Annual',
  `fiscal_year` year(4) NOT NULL,
  `quarter` int(11) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `planned_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `actual_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `variance` decimal(18,2) NOT NULL DEFAULT 0.00,
  `status` enum('Draft','Approved','Active','Closed','Revised') NOT NULL DEFAULT 'Draft',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `budgets_code_unique` (`code`),
  KEY `budgets_chart_of_account_id_foreign` (`chart_of_account_id`),
  KEY `budgets_created_by_foreign` (`created_by`),
  KEY `budgets_hospital_id_fiscal_year_index` (`hospital_id`,`fiscal_year`),
  KEY `budgets_department_id_index` (`department_id`),
  CONSTRAINT `budgets_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `budgets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `budgets_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `budgets_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `budgets`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `budgets` WRITE;
/*!40000 ALTER TABLE `budgets` DISABLE KEYS */;
INSERT INTO `budgets` VALUES
(1,1,NULL,30,'Salaries - FY 2026','BGT-2026-0001','Annual',2026,NULL,NULL,250000000.00,0.00,250000000.00,'Approved',NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(2,1,8,31,'Drug Purchases - Pharmacy - FY 2026','BGT-2026-0002','Annual',2026,NULL,NULL,80000000.00,0.00,80000000.00,'Approved',NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(3,1,6,32,'Medical Supplies - Lab - FY 2026','BGT-2026-0003','Annual',2026,NULL,NULL,25000000.00,0.00,25000000.00,'Approved',NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(4,1,NULL,33,'Utilities - Electricity - FY 2026','BGT-2026-0004','Annual',2026,NULL,NULL,15000000.00,0.00,15000000.00,'Approved',NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(5,1,NULL,35,'Generator Fuel - FY 2026','BGT-2026-0005','Annual',2026,NULL,NULL,12000000.00,0.00,12000000.00,'Approved',NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(6,1,NULL,21,'Consultation Revenue Target - FY 2026','BGT-2026-0006','Annual',2026,NULL,NULL,45000000.00,0.00,45000000.00,'Approved',NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(7,1,8,22,'Pharmacy Sales Target - FY 2026','BGT-2026-0007','Annual',2026,NULL,NULL,120000000.00,0.00,120000000.00,'Approved',NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(8,1,6,23,'Lab Revenue Target - FY 2026','BGT-2026-0008','Annual',2026,NULL,NULL,35000000.00,0.00,35000000.00,'Approved',NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL);
/*!40000 ALTER TABLE `budgets` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `chart_of_accounts`
--

DROP TABLE IF EXISTS `chart_of_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `chart_of_accounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `account_code` varchar(30) NOT NULL,
  `name` varchar(191) NOT NULL,
  `type` enum('Asset','Liability','Equity','Revenue','Expense','Contra') NOT NULL DEFAULT 'Asset',
  `sub_type` enum('Current Asset','Fixed Asset','Other Asset','Current Liability','Long-term Liability','Other Liability','Capital','Retained Earnings','Operating Revenue','Other Revenue','Operating Expense','Administrative Expense','Cost of Goods Sold','Other Expense','Contra Asset','Contra Revenue') NOT NULL DEFAULT 'Current Asset',
  `currency` varchar(3) NOT NULL DEFAULT 'NGN',
  `parent_account_id` bigint(20) unsigned DEFAULT NULL,
  `opening_balance` decimal(18,2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(18,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_bank_account` tinyint(1) NOT NULL DEFAULT 0,
  `bank_name` varchar(191) DEFAULT NULL,
  `bank_account_number` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chart_of_accounts_account_code_unique` (`account_code`),
  KEY `chart_of_accounts_parent_account_id_foreign` (`parent_account_id`),
  KEY `chart_of_accounts_hospital_id_type_index` (`hospital_id`,`type`),
  KEY `chart_of_accounts_account_code_index` (`account_code`),
  CONSTRAINT `chart_of_accounts_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chart_of_accounts_parent_account_id_foreign` FOREIGN KEY (`parent_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chart_of_accounts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `chart_of_accounts` WRITE;
/*!40000 ALTER TABLE `chart_of_accounts` DISABLE KEYS */;
INSERT INTO `chart_of_accounts` VALUES
(1,1,'1000','Cash on Hand','Asset','Current Asset','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(2,1,'1010','Main Bank Account - UBA','Asset','Current Asset','NGN',NULL,0.00,0.00,1,1,'United Bank for Africa','1023456789',NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(3,1,'1020','Petty Cash','Asset','Current Asset','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(4,1,'1100','Accounts Receivable - Patients','Asset','Current Asset','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(5,1,'1110','Accounts Receivable - Insurance/HMO','Asset','Current Asset','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(6,1,'1200','Drug Inventory','Asset','Current Asset','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(7,1,'1300','Medical Supplies Inventory','Asset','Current Asset','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(8,1,'1500','Hospital Buildings','Asset','Fixed Asset','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(9,1,'1510','Medical Equipment','Asset','Fixed Asset','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(10,1,'1520','Office Equipment','Asset','Fixed Asset','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(11,1,'1530','Motor Vehicles','Asset','Fixed Asset','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(12,1,'2000','Accounts Payable - Suppliers','Liability','Current Liability','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(13,1,'2100','Salaries Payable','Liability','Current Liability','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(14,1,'2200','Tax Payable - PAYE','Liability','Current Liability','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(15,1,'2210','Tax Payable - VAT','Liability','Current Liability','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(16,1,'2300','Pension Contribution Payable','Liability','Current Liability','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(17,1,'2400','NHIF Deductions Payable','Liability','Current Liability','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(18,1,'2500','Long-term Loan - BoF','Liability','Long-term Liability','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(19,1,'3000','Capital - Government','Equity','Capital','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(20,1,'3100','Retained Earnings','Equity','Retained Earnings','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(21,1,'4000','Consultation Fees','Revenue','Operating Revenue','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(22,1,'4100','Pharmacy Sales','Revenue','Operating Revenue','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(23,1,'4200','Laboratory Services Revenue','Revenue','Operating Revenue','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(24,1,'4300','Radiology Services Revenue','Revenue','Operating Revenue','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(25,1,'4400','Inpatient Accommodation Revenue','Revenue','Operating Revenue','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(26,1,'4500','Theatre / Procedure Revenue','Revenue','Operating Revenue','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(27,1,'4600','Maternity Revenue','Revenue','Operating Revenue','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(28,1,'4700','Insurance Claims Revenue','Revenue','Operating Revenue','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(29,1,'4900','Other Income','Revenue','Other Revenue','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(30,1,'5000','Salaries & Wages','Expense','Operating Expense','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(31,1,'5100','Drug Purchases','Expense','Cost of Goods Sold','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(32,1,'5200','Medical Supplies Purchases','Expense','Cost of Goods Sold','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(33,1,'5300','Utilities - Electricity','Expense','Operating Expense','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(34,1,'5310','Utilities - Water','Expense','Operating Expense','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(35,1,'5400','Fuel & Diesel (Generator)','Expense','Operating Expense','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(36,1,'5500','Equipment Maintenance','Expense','Operating Expense','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(37,1,'5600','Building Maintenance','Expense','Operating Expense','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(38,1,'5700','Office Supplies','Expense','Administrative Expense','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(39,1,'5800','Travel & Transport','Expense','Administrative Expense','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(40,1,'5900','Staff Training','Expense','Administrative Expense','NGN',NULL,0.00,0.00,1,0,NULL,NULL,NULL,'2026-08-04 21:50:05','2026-08-04 21:50:05',NULL);
/*!40000 ALTER TABLE `chart_of_accounts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `clinic_schedules`
--

DROP TABLE IF EXISTS `clinic_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clinic_schedules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `appointment_type_id` bigint(20) unsigned DEFAULT NULL,
  `weekday` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `break_start` time DEFAULT NULL,
  `break_end` time DEFAULT NULL,
  `slot_duration_minutes` int(11) NOT NULL DEFAULT 15,
  `max_patients_per_slot` int(11) NOT NULL DEFAULT 1,
  `buffer_minutes_before` int(11) NOT NULL DEFAULT 0,
  `buffer_minutes_after` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clinic_schedules_appointment_type_id_foreign` (`appointment_type_id`),
  KEY `clinic_schedules_created_by_foreign` (`created_by`),
  KEY `clinic_schedules_clinic_id_weekday_is_active_index` (`clinic_id`,`weekday`,`is_active`),
  KEY `clinic_schedules_hospital_id_index` (`hospital_id`),
  CONSTRAINT `clinic_schedules_appointment_type_id_foreign` FOREIGN KEY (`appointment_type_id`) REFERENCES `appointment_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `clinic_schedules_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `clinic_schedules_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `clinic_schedules_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinic_schedules`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `clinic_schedules` WRITE;
/*!40000 ALTER TABLE `clinic_schedules` DISABLE KEYS */;
INSERT INTO `clinic_schedules` VALUES
(1,1,1,1,'Monday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(2,1,1,2,'Monday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(3,1,1,1,'Tuesday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(4,1,1,2,'Tuesday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(5,1,1,1,'Wednesday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(6,1,1,2,'Wednesday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(7,1,1,1,'Thursday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(8,1,1,2,'Thursday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(9,1,1,1,'Friday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(10,1,1,2,'Friday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(11,1,2,1,'Monday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(12,1,2,2,'Monday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(13,1,2,1,'Tuesday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(14,1,2,2,'Tuesday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(15,1,2,1,'Wednesday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(16,1,2,2,'Wednesday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(17,1,2,1,'Thursday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(18,1,2,2,'Thursday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(19,1,2,1,'Friday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(20,1,2,2,'Friday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(21,1,3,1,'Monday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(22,1,3,2,'Monday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(23,1,3,1,'Tuesday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(24,1,3,2,'Tuesday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(25,1,3,1,'Wednesday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(26,1,3,2,'Wednesday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(27,1,3,1,'Thursday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(28,1,3,2,'Thursday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(29,1,3,1,'Friday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(30,1,3,2,'Friday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(31,1,4,1,'Monday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(32,1,4,2,'Monday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(33,1,4,1,'Tuesday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(34,1,4,2,'Tuesday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(35,1,4,1,'Wednesday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(36,1,4,2,'Wednesday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(37,1,4,1,'Thursday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(38,1,4,2,'Thursday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(39,1,4,1,'Friday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(40,1,4,2,'Friday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(41,1,5,1,'Monday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(42,1,5,2,'Monday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(43,1,5,1,'Tuesday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(44,1,5,2,'Tuesday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(45,1,5,1,'Wednesday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(46,1,5,2,'Wednesday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(47,1,5,1,'Thursday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(48,1,5,2,'Thursday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(49,1,5,1,'Friday','08:00:00','13:00:00','10:30:00','11:00:00',20,2,5,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(50,1,5,2,'Friday','14:00:00','17:00:00',NULL,NULL,15,3,0,5,1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL);
/*!40000 ALTER TABLE `clinic_schedules` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `clinical_notes`
--

DROP TABLE IF EXISTS `clinical_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clinical_notes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `encounter_id` bigint(20) unsigned DEFAULT NULL,
  `author_id` bigint(20) unsigned NOT NULL,
  `note_number` varchar(255) NOT NULL,
  `note_type` enum('Progress','Admission','Discharge','Nursing','Procedure','Consultation','Referral','Death','Other') NOT NULL DEFAULT 'Progress',
  `title` varchar(255) DEFAULT NULL,
  `subjective` longtext DEFAULT NULL,
  `objective` longtext DEFAULT NULL,
  `assessment` longtext DEFAULT NULL,
  `plan` longtext DEFAULT NULL,
  `narrative` longtext DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `is_signed` tinyint(1) NOT NULL DEFAULT 0,
  `signed_at` timestamp NULL DEFAULT NULL,
  `signed_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `note_number` (`note_number`),
  KEY `clinical_notes_patient_id_created_at_index` (`patient_id`,`created_at`),
  KEY `clinical_notes_encounter_id_note_type_index` (`encounter_id`,`note_type`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinical_notes`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `clinical_notes` WRITE;
/*!40000 ALTER TABLE `clinical_notes` DISABLE KEYS */;
INSERT INTO `clinical_notes` VALUES
(1,1,1,1,2,'FUTH-LF-CN-2026-000001','Progress','Day 1 progress note','Patient complains of fever (3 days), headache, body pains. No vomiting, no diarrhoea.','T 38.4°C, BP 120/80, HR 88, RR 18, SpO2 98%. Acutely ill-looking, no pallor.','Suspected malaria. Differential: typhoid fever, URTI.','Send MP+RDT, FBC. Start artemether/lumefantrine. Paracetamol for fever. Review in 48h.',NULL,NULL,1,'2026-07-31 11:00:00',2,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(2,1,2,2,2,'FUTH-LF-CN-2026-000002','Progress','Day 1 progress note','Patient complains of fever (3 days), headache, body pains. No vomiting, no diarrhoea.','T 38.4°C, BP 120/80, HR 88, RR 18, SpO2 98%. Acutely ill-looking, no pallor.','Suspected malaria. Differential: typhoid fever, URTI.','Send MP+RDT, FBC. Start artemether/lumefantrine. Paracetamol for fever. Review in 48h.',NULL,NULL,1,'2026-07-31 11:00:00',2,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(3,1,3,3,2,'FUTH-LF-CN-2026-000003','Progress','Day 1 progress note','Patient complains of fever (3 days), headache, body pains. No vomiting, no diarrhoea.','T 38.4°C, BP 120/80, HR 88, RR 18, SpO2 98%. Acutely ill-looking, no pallor.','Suspected malaria. Differential: typhoid fever, URTI.','Send MP+RDT, FBC. Start artemether/lumefantrine. Paracetamol for fever. Review in 48h.',NULL,NULL,1,'2026-07-31 11:00:00',2,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(4,1,4,4,2,'FUTH-LF-CN-2026-000004','Progress','Day 1 progress note','Patient complains of fever (3 days), headache, body pains. No vomiting, no diarrhoea.','T 38.4°C, BP 120/80, HR 88, RR 18, SpO2 98%. Acutely ill-looking, no pallor.','Suspected malaria. Differential: typhoid fever, URTI.','Send MP+RDT, FBC. Start artemether/lumefantrine. Paracetamol for fever. Review in 48h.',NULL,NULL,1,'2026-07-31 11:00:00',2,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(5,1,5,5,2,'FUTH-LF-CN-2026-000005','Progress','Day 1 progress note','Patient complains of fever (3 days), headache, body pains. No vomiting, no diarrhoea.','T 38.4°C, BP 120/80, HR 88, RR 18, SpO2 98%. Acutely ill-looking, no pallor.','Suspected malaria. Differential: typhoid fever, URTI.','Send MP+RDT, FBC. Start artemether/lumefantrine. Paracetamol for fever. Review in 48h.',NULL,NULL,1,'2026-07-31 11:00:00',2,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(6,1,6,6,2,'FUTH-LF-CN-2026-000006','Progress','Day 1 progress note','Patient complains of fever (3 days), headache, body pains. No vomiting, no diarrhoea.','T 38.4°C, BP 120/80, HR 88, RR 18, SpO2 98%. Acutely ill-looking, no pallor.','Suspected malaria. Differential: typhoid fever, URTI.','Send MP+RDT, FBC. Start artemether/lumefantrine. Paracetamol for fever. Review in 48h.',NULL,NULL,1,'2026-07-31 11:00:00',2,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL);
/*!40000 ALTER TABLE `clinical_notes` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `clinics`
--

DROP TABLE IF EXISTS `clinics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clinics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clinics_hospital_id_code_unique` (`hospital_id`,`code`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinics`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `clinics` WRITE;
/*!40000 ALTER TABLE `clinics` DISABLE KEYS */;
INSERT INTO `clinics` VALUES
(1,1,'General Outpatient Clinic','GOP',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(2,1,'Antenatal Clinic','ANC',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(3,1,'Immunisation Clinic','IMM',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(4,1,'HIV/ART Clinic','ART',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(5,1,'TB Clinic','TB',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(6,1,'Hypertension Clinic','HTN',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(7,1,'Diabetes Clinic','DM',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(8,1,'Surgical Clinic','SURG',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(9,1,'Paediatric Clinic','PAED',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(10,1,'Mental Health Clinic','PSY',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(11,1,'Eye Clinic','EYE',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL);
/*!40000 ALTER TABLE `clinics` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_hospital_id_code_unique` (`hospital_id`,`code`),
  KEY `departments_hospital_id_index` (`hospital_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES
(1,1,'General Medicine','GM','Internal medicine & general practice',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(2,1,'Surgery','SRG','General & specialist surgery',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(3,1,'Paediatrics','PAED','Child health',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(4,1,'Obstetrics & Gynaecology','OBGYN','Maternal & women health',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(5,1,'Accident & Emergency','A&E','Emergency medicine',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(6,1,'Laboratory','LAB','Clinical laboratory services',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(7,1,'Radiology','RAD','Imaging services',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(8,1,'Pharmacy','PHARM','Pharmaceutical services',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(9,1,'Psychiatry','PSY','Mental health',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(10,1,'Ophthalmology','OPH','Eye care',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(11,1,'ENT','ENT','Ear, nose, throat',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(12,1,'Dental','DENT','Dental services',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(13,1,'Physiotherapy','PHY','Rehabilitation',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL);
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `diagnoses`
--

DROP TABLE IF EXISTS `diagnoses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `diagnoses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) unsigned NOT NULL,
  `encounter_id` bigint(20) unsigned DEFAULT NULL,
  `recorded_by` bigint(20) unsigned NOT NULL,
  `icd10_code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `type` enum('Principal','Admitting','Working','Discharge','Secondary','Comorbidity') NOT NULL DEFAULT 'Working',
  `status` enum('Active','Resolved','Chronic','Recurrent') NOT NULL DEFAULT 'Active',
  `onset_date` date DEFAULT NULL,
  `resolved_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `diagnoses_patient_id_status_index` (`patient_id`,`status`),
  KEY `diagnoses_icd10_code_index` (`icd10_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diagnoses`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `diagnoses` WRITE;
/*!40000 ALTER TABLE `diagnoses` DISABLE KEYS */;
INSERT INTO `diagnoses` VALUES
(1,1,1,2,'B54','Unspecified malaria','Working','Active','2026-07-28',NULL,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL);
/*!40000 ALTER TABLE `diagnoses` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `discharge_summaries`
--

DROP TABLE IF EXISTS `discharge_summaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `discharge_summaries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `discharging_doctor_id` bigint(20) unsigned NOT NULL,
  `discharge_date` datetime NOT NULL,
  `discharge_type` enum('Improved','Recovered','Refused Treatment','Absconded','Transferred','Death','AMA','Other') NOT NULL DEFAULT 'Improved',
  `final_diagnosis` text DEFAULT NULL,
  `comorbidities` text DEFAULT NULL,
  `procedures_performed` text DEFAULT NULL,
  `investigations_summary` text DEFAULT NULL,
  `treatment_summary` text DEFAULT NULL,
  `treatment_given` text DEFAULT NULL,
  `condition_on_discharge` text DEFAULT NULL,
  `medications_on_discharge` text DEFAULT NULL,
  `follow_up_instructions` text DEFAULT NULL,
  `follow_up_date` date DEFAULT NULL,
  `death_cause` text DEFAULT NULL,
  `death_time` time DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_signed` tinyint(1) NOT NULL DEFAULT 0,
  `signed_at` datetime DEFAULT NULL,
  `signed_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discharge_summaries_discharging_doctor_id_foreign` (`discharging_doctor_id`),
  KEY `discharge_summaries_signed_by_foreign` (`signed_by`),
  KEY `discharge_summaries_admission_id_index` (`admission_id`),
  KEY `discharge_summaries_patient_id_discharge_date_index` (`patient_id`,`discharge_date`),
  CONSTRAINT `discharge_summaries_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `discharge_summaries_discharging_doctor_id_foreign` FOREIGN KEY (`discharging_doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `discharge_summaries_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `discharge_summaries_signed_by_foreign` FOREIGN KEY (`signed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discharge_summaries`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `discharge_summaries` WRITE;
/*!40000 ALTER TABLE `discharge_summaries` DISABLE KEYS */;
/*!40000 ALTER TABLE `discharge_summaries` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `dispensing_logs`
--

DROP TABLE IF EXISTS `dispensing_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dispensing_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `dispensing_point_id` bigint(20) unsigned DEFAULT NULL,
  `prescription_id` bigint(20) unsigned DEFAULT NULL,
  `prescription_item_id` bigint(20) unsigned DEFAULT NULL,
  `drug_id` bigint(20) unsigned NOT NULL,
  `drug_batch_id` bigint(20) unsigned DEFAULT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `dispensed_by` bigint(20) unsigned NOT NULL,
  `reference_number` varchar(255) NOT NULL,
  `dispensed_at` datetime NOT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(14,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference_number` (`reference_number`),
  KEY `dispensing_logs_patient_id_dispensed_at_index` (`patient_id`,`dispensed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dispensing_logs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `dispensing_logs` WRITE;
/*!40000 ALTER TABLE `dispensing_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `dispensing_logs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `dispensing_points`
--

DROP TABLE IF EXISTS `dispensing_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dispensing_points` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `in_charge_id` bigint(20) unsigned DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dispensing_points`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `dispensing_points` WRITE;
/*!40000 ALTER TABLE `dispensing_points` DISABLE KEYS */;
INSERT INTO `dispensing_points` VALUES
(1,1,'Main Pharmacy','PHARM-MAIN','Ground Floor, Block A',5,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(2,1,'Emergency Pharmacy','PHARM-ER','Emergency Department',NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(3,1,'ANC Pharmacy','PHARM-ANC','Antenatal Clinic',NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(4,1,'Paediatric Pharmacy','PHARM-PAED','Children Ward',NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(5,1,'Inpatient Pharmacy','PHARM-IPD','Wards 1-5',NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(6,1,'Theatre Pharmacy','PHARM-OR','Operating Theatre',NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00');
/*!40000 ALTER TABLE `dispensing_points` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `drug_batches`
--

DROP TABLE IF EXISTS `drug_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `drug_batches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `drug_id` bigint(20) unsigned NOT NULL,
  `batch_number` varchar(255) NOT NULL,
  `expiry_date` date NOT NULL,
  `manufactured_date` date DEFAULT NULL,
  `quantity_received` decimal(12,2) NOT NULL DEFAULT 0.00,
  `quantity_remaining` decimal(12,2) NOT NULL DEFAULT 0.00,
  `cost_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `supplier` varchar(255) DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `is_quarantined` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `drug_batches_drug_id_expiry_date_index` (`drug_id`,`expiry_date`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drug_batches`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `drug_batches` WRITE;
/*!40000 ALTER TABLE `drug_batches` DISABLE KEYS */;
INSERT INTO `drug_batches` VALUES
(1,1,'BATCH-DRG-001','2027-05-08','2025-10-14',1000.00,312.00,5.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(2,2,'BATCH-DRG-002','2027-05-28','2025-08-22',1000.00,872.00,8.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(3,3,'BATCH-DRG-003','2027-09-04','2026-04-10',1000.00,524.00,4.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(4,4,'BATCH-DRG-004','2027-05-15','2025-10-31',1000.00,152.00,7.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(5,5,'BATCH-DRG-005','2027-04-08','2025-08-27',1000.00,334.00,12.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(6,6,'BATCH-DRG-006','2028-06-06','2025-12-20',1000.00,391.00,80.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(7,7,'BATCH-DRG-007','2027-08-13','2025-08-31',1000.00,709.00,6.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(8,8,'BATCH-DRG-008','2028-04-09','2026-01-02',1000.00,84.00,5.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(9,9,'BATCH-DRG-009','2027-08-14','2026-03-06',1000.00,94.00,50.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(10,10,'BATCH-DRG-010','2027-10-19','2025-12-11',1000.00,367.00,150.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(11,11,'BATCH-DRG-011','2027-10-18','2025-09-01',1000.00,494.00,8.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(12,12,'BATCH-DRG-012','2027-04-30','2025-12-28',1000.00,657.00,25.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(13,13,'BATCH-DRG-013','2027-05-28','2026-03-09',1000.00,833.00,9.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(14,14,'BATCH-DRG-014','2027-05-27','2026-04-21',1000.00,595.00,80.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(15,15,'BATCH-DRG-015','2027-12-08','2026-02-02',1000.00,752.00,120.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(16,16,'BATCH-DRG-016','2027-12-21','2025-11-05',1000.00,623.00,4.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(17,17,'BATCH-DRG-017','2027-05-15','2026-01-25',1000.00,464.00,12.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(18,18,'BATCH-DRG-018','2028-01-05','2026-04-20',1000.00,644.00,7.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(19,19,'BATCH-DRG-019','2027-04-07','2026-01-19',1000.00,653.00,9.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(20,20,'BATCH-DRG-020','2028-07-19','2026-04-03',1000.00,172.00,6.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(21,21,'BATCH-DRG-021','2028-02-01','2025-08-08',1000.00,521.00,7.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(22,22,'BATCH-DRG-022','2027-07-25','2025-10-08',1000.00,569.00,5.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(23,23,'BATCH-DRG-023','2028-01-07','2026-02-28',1000.00,454.00,4.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(24,24,'BATCH-DRG-024','2027-06-20','2026-01-07',1000.00,856.00,5.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(25,25,'BATCH-DRG-025','2027-04-06','2025-09-24',1000.00,733.00,6.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(26,26,'BATCH-DRG-026','2028-02-15','2025-08-09',1000.00,279.00,5.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(27,27,'BATCH-DRG-027','2027-12-18','2026-02-25',1000.00,550.00,350.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(28,28,'BATCH-DRG-028','2027-10-14','2025-08-07',1000.00,208.00,350.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(29,29,'BATCH-DRG-029','2027-11-30','2026-03-04',1000.00,177.00,80.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(30,30,'BATCH-DRG-030','2027-03-29','2025-09-11',1000.00,786.00,5.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(31,31,'BATCH-DRG-031','2027-04-25','2025-10-23',1000.00,534.00,8.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(32,32,'BATCH-DRG-032','2028-04-19','2025-09-01',1000.00,894.00,120.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(33,33,'BATCH-DRG-033','2027-06-11','2025-12-12',1000.00,879.00,35.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(34,34,'BATCH-DRG-034','2028-01-12','2025-10-30',1000.00,68.00,3.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(35,35,'BATCH-DRG-035','2028-01-04','2026-01-07',1000.00,929.00,4.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(36,36,'BATCH-DRG-036','2028-01-05','2025-10-22',1000.00,891.00,2.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(37,37,'BATCH-DRG-037','2028-04-12','2025-09-17',1000.00,277.00,60.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(38,38,'BATCH-DRG-038','2028-06-19','2025-10-18',1000.00,314.00,80.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(39,39,'BATCH-DRG-039','2027-09-22','2025-11-09',1000.00,700.00,25.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(40,40,'BATCH-DRG-040','2028-01-23','2025-11-09',1000.00,553.00,5.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(41,41,'BATCH-DRG-041','2027-12-21','2026-03-05',1000.00,829.00,8.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(42,42,'BATCH-DRG-042','2027-05-23','2025-12-03',1000.00,503.00,7.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(43,43,'BATCH-DRG-043','2028-03-13','2025-09-25',1000.00,523.00,8.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(44,44,'BATCH-DRG-044','2027-06-05','2025-12-07',1000.00,337.00,70.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(45,45,'BATCH-DRG-045','2027-02-17','2026-02-08',1000.00,239.00,35.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(46,46,'BATCH-DRG-046','2028-01-29','2025-08-28',1000.00,299.00,80.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(47,47,'BATCH-DRG-047','2028-01-31','2025-12-06',1000.00,135.00,25.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(48,48,'BATCH-DRG-048','2028-07-03','2025-11-15',1000.00,145.00,150.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(49,49,'BATCH-DRG-049','2028-02-12','2026-04-20',1000.00,366.00,10.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(50,50,'BATCH-DRG-050','2027-12-16','2026-02-22',1000.00,521.00,7.00,'FUTH Central Store','2026-05-02',0,'2026-07-31 11:00:00','2026-07-31 11:00:00');
/*!40000 ALTER TABLE `drug_batches` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `drug_categories`
--

DROP TABLE IF EXISTS `drug_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `drug_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(30) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drug_categories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `drug_categories` WRITE;
/*!40000 ALTER TABLE `drug_categories` DISABLE KEYS */;
INSERT INTO `drug_categories` VALUES
(1,'Nervous system','N','Analgesics, anaesthetics, anticonvulsants',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(2,'Antiinfectives for systemic use','J','Antibiotics, antivirals, antifungals, antimalarials',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(3,'Cardiovascular system','C','Antihypertensives, cardiac drugs',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(4,'Alimentary tract and metabolism','A','Antacids, antidiarrhoeals, antidiabetics',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(5,'Respiratory system','R','Bronchodilators, antihistamines',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(6,'Musculo-skeletal system','M','NSAIDs, muscle relaxants',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(7,'Genito urinary system','G','Diuretics, hormonal contraceptives',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(8,'Systemic hormonalonal preparations','H','Corticosteroids',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(9,'Dermatologicals','D','Topical antifungals, antiseptics',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(10,'Antiparasitic products','P','Antimalarials, antihelminthics',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(11,'Various','V','Nutrients, electrolytes, vitamins',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(12,'Sensory organs','S','Ophthalmic, otic preparations',1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL);
/*!40000 ALTER TABLE `drug_categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `drug_interactions`
--

DROP TABLE IF EXISTS `drug_interactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `drug_interactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `drug_a_id` bigint(20) unsigned NOT NULL,
  `drug_b_id` bigint(20) unsigned NOT NULL,
  `severity` enum('Minor','Moderate','Major','Contraindicated') NOT NULL DEFAULT 'Moderate',
  `effect` enum('Additive','Synergistic','Antagonistic','Altered metabolism','Increased toxicity','Decreased efficacy','Other') NOT NULL DEFAULT 'Other',
  `description` text DEFAULT NULL,
  `clinical_action` text DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `drug_interactions_drug_a_id_drug_b_id_unique` (`drug_a_id`,`drug_b_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drug_interactions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `drug_interactions` WRITE;
/*!40000 ALTER TABLE `drug_interactions` DISABLE KEYS */;
INSERT INTO `drug_interactions` VALUES
(1,20,22,'Major','Altered metabolism','Amlodipine + Atenolol — additive hypotensive effect; monitor BP closely.','Use with caution, monitor BP','Stockley 2024',1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(2,22,23,'Moderate','Additive','Beta-blocker + Thiazide — additive antihypertensive; risk of bradycardia.','Monitor HR & BP','Medscape',1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(3,2,29,'Moderate','Increased toxicity','NSAID + Corticosteroid — increased GI bleed risk.','Co-prescribe PPI','Stockley 2024',1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(4,39,30,'Major','Increased toxicity','Diazepam + Prednisolone — CNS depression risk.','Avoid co-prescription','Stockley 2024',1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(5,49,26,'Major','Altered metabolism','Ciprofloxacin + Glibenclamide — hypoglycaemia risk.','Monitor blood glucose','Stockley 2024',1,'2026-07-31 11:00:00','2026-07-31 11:00:00');
/*!40000 ALTER TABLE `drug_interactions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `drug_stock_levels`
--

DROP TABLE IF EXISTS `drug_stock_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `drug_stock_levels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `drug_id` bigint(20) unsigned NOT NULL,
  `dispensing_point_id` bigint(20) unsigned DEFAULT NULL,
  `quantity_on_hand` decimal(12,2) NOT NULL DEFAULT 0.00,
  `quantity_reserved` decimal(12,2) NOT NULL DEFAULT 0.00,
  `quantity_available` decimal(12,2) NOT NULL DEFAULT 0.00,
  `quantity_in_transit` decimal(12,2) NOT NULL DEFAULT 0.00,
  `reorder_level` decimal(12,2) NOT NULL DEFAULT 0.00,
  `reorder_quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `last_received_date` date DEFAULT NULL,
  `last_dispensed_date` date DEFAULT NULL,
  `last_counted_date` date DEFAULT NULL,
  `stock_status` enum('In Stock','Low Stock','Out of Stock','Over Stock','Discontinued') NOT NULL DEFAULT 'In Stock',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_drug_dispensing_point` (`drug_id`,`dispensing_point_id`),
  KEY `drug_stock_levels_dispensing_point_id_foreign` (`dispensing_point_id`),
  KEY `drug_stock_levels_hospital_id_stock_status_index` (`hospital_id`,`stock_status`),
  CONSTRAINT `drug_stock_levels_dispensing_point_id_foreign` FOREIGN KEY (`dispensing_point_id`) REFERENCES `dispensing_points` (`id`) ON DELETE SET NULL,
  CONSTRAINT `drug_stock_levels_drug_id_foreign` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `drug_stock_levels_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drug_stock_levels`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `drug_stock_levels` WRITE;
/*!40000 ALTER TABLE `drug_stock_levels` DISABLE KEYS */;
INSERT INTO `drug_stock_levels` VALUES
(1,1,1,1,200.00,0.00,62.00,0.00,100.00,500.00,'2026-07-14','2026-08-04',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(2,1,2,1,113.00,0.00,269.00,0.00,100.00,500.00,'2026-07-19','2026-08-01',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(3,1,3,1,475.00,0.00,334.00,0.00,100.00,500.00,'2026-08-02','2026-08-03',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(4,1,4,1,88.00,0.00,285.00,0.00,100.00,500.00,'2026-07-28','2026-08-04',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(5,1,5,1,421.00,0.00,215.00,0.00,100.00,500.00,'2026-07-27','2026-08-02',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(6,1,6,1,140.00,0.00,282.00,0.00,100.00,500.00,'2026-07-25','2026-08-04',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(7,1,7,1,184.00,0.00,482.00,0.00,100.00,500.00,'2026-07-21','2026-07-30',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(8,1,8,1,407.00,0.00,223.00,0.00,100.00,500.00,'2026-07-25','2026-08-04',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(9,1,9,1,412.00,0.00,208.00,0.00,100.00,500.00,'2026-07-21','2026-08-02',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(10,1,10,1,172.00,0.00,463.00,0.00,100.00,500.00,'2026-07-12','2026-08-03',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(11,1,11,1,236.00,0.00,406.00,0.00,100.00,500.00,'2026-07-23','2026-08-03',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(12,1,12,1,231.00,0.00,274.00,0.00,100.00,500.00,'2026-07-26','2026-08-02',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(13,1,13,1,298.00,0.00,265.00,0.00,100.00,500.00,'2026-08-02','2026-07-30',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(14,1,14,1,83.00,0.00,469.00,0.00,100.00,500.00,'2026-07-23','2026-07-31',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(15,1,15,1,173.00,0.00,50.00,0.00,100.00,500.00,'2026-07-18','2026-08-03',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(16,1,16,1,400.00,0.00,469.00,0.00,100.00,500.00,'2026-07-16','2026-07-31',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(17,1,17,1,390.00,0.00,399.00,0.00,100.00,500.00,'2026-07-31','2026-07-30',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(18,1,18,1,338.00,0.00,404.00,0.00,100.00,500.00,'2026-07-07','2026-07-31',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(19,1,19,1,343.00,0.00,61.00,0.00,100.00,500.00,'2026-07-30','2026-07-31',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL),
(20,1,20,1,59.00,0.00,336.00,0.00,100.00,500.00,'2026-07-26','2026-08-01',NULL,'In Stock','2026-08-04 22:24:59','2026-08-04 22:24:59',NULL);
/*!40000 ALTER TABLE `drug_stock_levels` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `drugs`
--

DROP TABLE IF EXISTS `drugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `drugs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `drug_category_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `generic_name` varchar(255) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `atc_code` varchar(20) DEFAULT NULL,
  `dosage_form` varchar(255) DEFAULT NULL,
  `strength` varchar(255) DEFAULT NULL,
  `unit_of_measure` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `cost_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `reorder_level` decimal(12,2) NOT NULL DEFAULT 0.00,
  `reorder_quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `is_controlled` tinyint(1) NOT NULL DEFAULT 0,
  `is_antenatal_safe` tinyint(1) NOT NULL DEFAULT 0,
  `is_pediatric_safe` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `drugs_name_index` (`name`),
  KEY `drugs_generic_name_index` (`generic_name`),
  KEY `drugs_atc_code_index` (`atc_code`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drugs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `drugs` WRITE;
/*!40000 ALTER TABLE `drugs` DISABLE KEYS */;
INSERT INTO `drugs` VALUES
(1,1,1,'Paracetamol Tab','Paracetamol','DRG-001','N02BE01','Tablet','500 mg','tablet',NULL,NULL,5.00,10.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(2,1,6,'Ibuprofen Tab','Ibuprofen','DRG-002','M01AE01','Tablet','400 mg','tablet',NULL,NULL,8.00,15.00,100.00,500.00,0,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(3,1,1,'Aspirin Tab','Acetylsalicylic acid','DRG-003','N02BA01','Tablet','75 mg','tablet',NULL,NULL,4.00,8.00,100.00,500.00,0,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(4,1,6,'Diclofenac Tab','Diclofenac sodium','DRG-004','M01AB05','Tablet','50 mg','tablet',NULL,NULL,7.00,14.00,100.00,500.00,0,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(5,1,2,'Amoxicillin Cap','Amoxicillin','DRG-005','J01CA04','Capsule','500 mg','capsule',NULL,NULL,12.00,25.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(6,1,2,'Amoxicillin Susp','Amoxicillin','DRG-006','J01CA04','Suspension','125 mg/5 mL','bottle',NULL,NULL,80.00,150.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(7,1,2,'Cotrimoxazole Tab','Co-trimoxazole','DRG-007','J01EE01','Tablet','480 mg','tablet',NULL,NULL,6.00,12.00,100.00,500.00,0,0,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(8,1,2,'Metronidazole Tab','Metronidazole','DRG-008','J01XD01','Tablet','400 mg','tablet',NULL,NULL,5.00,11.00,100.00,500.00,0,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(9,1,10,'Artemether/Lumefantrine','Artemether + Lumefantrine','DRG-009','P01BF02','Tablet','20/120 mg','tablet',NULL,NULL,50.00,100.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(10,1,10,'Artesunate Inject','Artesunate','DRG-010','P01BE03','Injection','60 mg/mL','vial',NULL,NULL,150.00,300.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(11,1,10,'Chloroquine Tab','Chloroquine phosphate','DRG-011','P01BA01','Tablet','250 mg','tablet',NULL,NULL,8.00,15.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(12,1,10,'Sulphadoxine/Pyrimethamine','Sulfadoxine+Pyrimethamine','DRG-012','P01BF01','Tablet','500/25 mg','tablet',NULL,NULL,25.00,50.00,100.00,500.00,0,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(13,1,10,'Amodiaquine Tab','Amodiaquine','DRG-013','P01BA06','Tablet','200 mg','tablet',NULL,NULL,9.00,18.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(14,1,2,'Zidovudine/Lamivudine Tab','Zidovudine + Lamivudine','DRG-014','J05AR01','Tablet','300/150 mg','tablet',NULL,NULL,80.00,0.00,100.00,500.00,0,1,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(15,1,2,'Tenofovir/Lamivudine/Dolutegravir','Tenofovir + Lamivudine + Dolutegravir','DRG-015','J05AR18','Tablet','300/300/50 mg','tablet',NULL,NULL,120.00,0.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(16,1,2,'Isoniazid Tab','Isoniazid','DRG-016','J04AC01','Tablet','300 mg','tablet',NULL,NULL,4.00,8.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(17,1,2,'Rifampicin Cap','Rifampicin','DRG-017','J04AB02','Capsule','300 mg','capsule',NULL,NULL,12.00,25.00,100.00,500.00,0,0,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(18,1,2,'Ethambutol Tab','Ethambutol','DRG-018','J04AK02','Tablet','400 mg','tablet',NULL,NULL,7.00,14.00,100.00,500.00,0,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(19,1,2,'Pyrazinamide Tab','Pyrazinamide','DRG-019','J04AK03','Tablet','500 mg','tablet',NULL,NULL,9.00,18.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(20,1,3,'Amlodipine Tab','Amlodipine','DRG-020','C08CA01','Tablet','10 mg','tablet',NULL,NULL,6.00,13.00,100.00,500.00,0,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(21,1,3,'Lisinopril Tab','Lisinopril','DRG-021','C09AA03','Tablet','10 mg','tablet',NULL,NULL,7.00,14.00,100.00,500.00,0,1,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(22,1,3,'Atenolol Tab','Atenolol','DRG-022','C07AB03','Tablet','50 mg','tablet',NULL,NULL,5.00,11.00,100.00,500.00,0,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(23,1,3,'Hydrochlorothiazide','Hydrochlorothiazide','DRG-023','C03AA03','Tablet','25 mg','tablet',NULL,NULL,4.00,9.00,100.00,500.00,0,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(24,1,3,'Furosemide Tab','Furosemide','DRG-024','C03CA01','Tablet','40 mg','tablet',NULL,NULL,5.00,11.00,100.00,500.00,0,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(25,1,4,'Metformin Tab','Metformin','DRG-025','A10BA02','Tablet','500 mg','tablet',NULL,NULL,6.00,13.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(26,1,4,'Glibenclamide Tab','Glibenclamide','DRG-026','A10BB01','Tablet','5 mg','tablet',NULL,NULL,5.00,11.00,100.00,500.00,0,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(27,1,4,'Insulin (soluble)','Insulin (regular)','DRG-027','A10AB01','Injection','100 IU/mL','vial',NULL,NULL,350.00,700.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(28,1,5,'Salbutamol Inh','Salbutamol','DRG-028','R03AC02','Inhaler','100 mcg/dose','inhaler',NULL,NULL,350.00,700.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(29,1,8,'Hydrocortisone Inj','Hydrocortisone','DRG-029','H02AB09','Injection','100 mg','vial',NULL,NULL,80.00,160.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(30,1,8,'Prednisolone Tab','Prednisolone','DRG-030','H02AB07','Tablet','5 mg','tablet',NULL,NULL,5.00,11.00,100.00,500.00,0,0,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(31,1,4,'Omeprazole Cap','Omeprazole','DRG-031','A02BC01','Capsule','20 mg','capsule',NULL,NULL,8.00,17.00,100.00,500.00,0,1,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(32,1,4,'Aluminium/Magnesium Antacid','Al + Mg hydroxide','DRG-032','A02AD10','Suspension','200 mg/5 mL','bottle',NULL,NULL,120.00,240.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(33,1,4,'Oral Rehydration Salts','ORS','DRG-033','A07CA03','Powder','20.5 g/L','sachet',NULL,NULL,35.00,70.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(34,1,4,'Zinc Sulphate Tab','Zinc sulfate','DRG-034','A12CX01','Tablet','20 mg','tablet',NULL,NULL,3.00,7.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(35,1,4,'Ferrous Sulphate Tab','Ferrous sulfate','DRG-035','B03AA07','Tablet','200 mg','tablet',NULL,NULL,4.00,9.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(36,1,4,'Folic Acid Tab','Folic acid','DRG-036','B03BB01','Tablet','5 mg','tablet',NULL,NULL,2.00,5.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(37,1,4,'Magnesium Sulfate Inj','Magnesium sulfate','DRG-037','A06AD04','Injection','500 mg/mL','vial',NULL,NULL,60.00,120.00,100.00,500.00,0,1,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(38,1,8,'Oxytocin Inj','Oxytocin','DRG-038','H01BB02','Injection','10 IU/mL','ampoule',NULL,NULL,80.00,160.00,100.00,500.00,0,1,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(39,1,1,'Diazepam Inj','Diazepam','DRG-039','N05BA01','Injection','5 mg/mL','ampoule',NULL,NULL,25.00,50.00,100.00,500.00,1,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(40,1,1,'Phenytoin Tab','Phenytoin','DRG-040','N03AB02','Tablet','100 mg','tablet',NULL,NULL,5.00,11.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(41,1,1,'Carbamazepine Tab','Carbamazepine','DRG-041','N03AF01','Tablet','200 mg','tablet',NULL,NULL,8.00,17.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(42,1,1,'Haloperidol Tab','Haloperidol','DRG-042','N05AD01','Tablet','5 mg','tablet',NULL,NULL,7.00,14.00,100.00,500.00,1,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(43,1,1,'Chlorpromazine Tab','Chlorpromazine','DRG-043','N05AA01','Tablet','100 mg','tablet',NULL,NULL,8.00,16.00,100.00,500.00,1,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(44,1,3,'Adrenaline Inj','Epinephrine','DRG-044','C01CA24','Injection','1 mg/mL','ampoule',NULL,NULL,70.00,140.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(45,1,4,'Atropine Inj','Atropine sulfate','DRG-045','A03BA04','Injection','0.6 mg/mL','ampoule',NULL,NULL,35.00,70.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(46,1,9,'Povidone Iodine Soln','Povidone iodine','DRG-046','D08AG01','Solution','10%','bottle',NULL,NULL,80.00,160.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(47,1,2,'Gentamicin Inj','Gentamicin','DRG-047','J01GB03','Injection','40 mg/mL','ampoule',NULL,NULL,25.00,50.00,100.00,500.00,0,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(48,1,2,'Ceftriaxone Inj','Ceftriaxone','DRG-048','J01DD04','Injection','1 g','vial',NULL,NULL,150.00,300.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(49,1,2,'Ciprofloxacin Tab','Ciprofloxacin','DRG-049','J01MA02','Tablet','500 mg','tablet',NULL,NULL,10.00,20.00,100.00,500.00,0,0,0,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(50,1,10,'Quinine Tab','Quinine sulfate','DRG-050','P01BC01','Tablet','300 mg','tablet',NULL,NULL,7.00,14.00,100.00,500.00,0,1,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL);
/*!40000 ALTER TABLE `drugs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `duty_postings`
--

DROP TABLE IF EXISTS `duty_postings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `duty_postings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `duty_roster_id` bigint(20) unsigned DEFAULT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `staff_id` bigint(20) unsigned DEFAULT NULL,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `clinic_id` bigint(20) unsigned DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `shift` enum('Morning','Afternoon','Night','On-Call','Full Day') NOT NULL DEFAULT 'Morning',
  `shift_start` time DEFAULT NULL,
  `shift_end` time DEFAULT NULL,
  `role_in_unit` enum('In-Charge','Supervisor','Officer','Assistant','Trainee') NOT NULL DEFAULT 'Officer',
  `notes` text DEFAULT NULL,
  `status` enum('Scheduled','Active','Completed','Cancelled','Swapped') NOT NULL DEFAULT 'Scheduled',
  `posted_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `duty_postings_duty_roster_id_foreign` (`duty_roster_id`),
  KEY `duty_postings_staff_id_foreign` (`staff_id`),
  KEY `duty_postings_department_id_foreign` (`department_id`),
  KEY `duty_postings_clinic_id_foreign` (`clinic_id`),
  KEY `duty_postings_posted_by_foreign` (`posted_by`),
  KEY `duty_postings_user_id_start_date_end_date_index` (`user_id`,`start_date`,`end_date`),
  KEY `duty_postings_hospital_id_department_id_clinic_id_index` (`hospital_id`,`department_id`,`clinic_id`),
  KEY `duty_postings_status_start_date_index` (`status`,`start_date`),
  CONSTRAINT `duty_postings_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE SET NULL,
  CONSTRAINT `duty_postings_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `duty_postings_duty_roster_id_foreign` FOREIGN KEY (`duty_roster_id`) REFERENCES `duty_rosters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `duty_postings_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `duty_postings_posted_by_foreign` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `duty_postings_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL,
  CONSTRAINT `duty_postings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `duty_postings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `duty_postings` WRITE;
/*!40000 ALTER TABLE `duty_postings` DISABLE KEYS */;
INSERT INTO `duty_postings` VALUES
(1,1,1,1,1,1,1,'2026-08-03','2026-08-09','Morning','08:00:00','16:00:00','In-Charge',NULL,'Active',1,'2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(2,1,1,2,2,1,1,'2026-08-03','2026-08-09','Morning','08:00:00','16:00:00','Officer',NULL,'Active',1,'2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(3,1,1,3,3,1,1,'2026-08-03','2026-08-09','Morning','08:00:00','16:00:00','Officer',NULL,'Active',1,'2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(4,1,1,4,4,5,5,'2026-08-03','2026-08-09','Morning','08:00:00','16:00:00','Officer',NULL,'Active',1,'2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(5,1,1,5,5,8,8,'2026-08-03','2026-08-09','Morning','08:00:00','16:00:00','Officer',NULL,'Active',1,'2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(6,1,1,6,6,6,6,'2026-08-03','2026-08-09','Morning','08:00:00','16:00:00','Officer',NULL,'Active',1,'2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(7,1,1,7,7,7,7,'2026-08-03','2026-08-09','Morning','08:00:00','16:00:00','Officer',NULL,'Active',1,'2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(8,1,1,8,8,1,1,'2026-08-03','2026-08-09','Morning','08:00:00','16:00:00','Officer',NULL,'Active',1,'2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(9,1,1,9,9,1,1,'2026-08-03','2026-08-09','Morning','08:00:00','16:00:00','Officer',NULL,'Active',1,'2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(10,1,1,10,NULL,1,1,'2026-08-03','2026-08-09','Morning','08:00:00','16:00:00','Officer',NULL,'Active',1,'2026-08-04 21:16:43','2026-08-04 21:16:43',NULL);
/*!40000 ALTER TABLE `duty_postings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `duty_rosters`
--

DROP TABLE IF EXISTS `duty_rosters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `duty_rosters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `cycle` enum('Daily','Weekly','Bi-Weekly','Monthly','Quarterly') NOT NULL DEFAULT 'Weekly',
  `description` text DEFAULT NULL,
  `status` enum('Draft','Active','Closed') NOT NULL DEFAULT 'Draft',
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `duty_rosters_code_unique` (`code`),
  KEY `duty_rosters_created_by_foreign` (`created_by`),
  KEY `duty_rosters_hospital_id_status_index` (`hospital_id`,`status`),
  KEY `duty_rosters_start_date_end_date_index` (`start_date`,`end_date`),
  CONSTRAINT `duty_rosters_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `duty_rosters_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `duty_rosters`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `duty_rosters` WRITE;
/*!40000 ALTER TABLE `duty_rosters` DISABLE KEYS */;
INSERT INTO `duty_rosters` VALUES
(1,1,'Default Weekly Roster','DR-2026-0001','2026-08-03','2026-08-09','Weekly',NULL,'Active',1,'2026-08-04 21:16:43','2026-08-04 21:16:43',NULL);
/*!40000 ALTER TABLE `duty_rosters` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `encounters`
--

DROP TABLE IF EXISTS `encounters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `encounters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `clinic_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `encounter_number` varchar(255) NOT NULL,
  `visit_type` enum('Outpatient','Emergency','Inpatient','Telemedicine','Follow-up') NOT NULL,
  `encounter_date` date NOT NULL,
  `encounter_time` time NOT NULL,
  `status` enum('Waiting','In Progress','Completed','Cancelled') NOT NULL DEFAULT 'Waiting',
  `chief_complaint` text DEFAULT NULL,
  `referred_by` varchar(255) DEFAULT NULL,
  `appointment_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `encounter_number` (`encounter_number`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `encounters`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `encounters` WRITE;
/*!40000 ALTER TABLE `encounters` DISABLE KEYS */;
INSERT INTO `encounters` VALUES
(1,1,1,1,2,'FUTH-LF-ENC-2026-000001','Outpatient','2026-07-31','10:30:00','In Progress','Fever and headache for 3 days',NULL,1,2,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(2,1,2,1,2,'FUTH-LF-ENC-2026-000002','Outpatient','2026-07-31','11:30:00','In Progress','Fever and headache for 3 days',NULL,2,2,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(3,1,3,1,2,'FUTH-LF-ENC-2026-000003','Outpatient','2026-07-31','12:30:00','In Progress','Fever and headache for 3 days',NULL,3,2,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(4,1,4,1,2,'FUTH-LF-ENC-2026-000004','Outpatient','2026-07-31','13:30:00','In Progress','Fever and headache for 3 days',NULL,4,2,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(5,1,5,1,2,'FUTH-LF-ENC-2026-000005','Outpatient','2026-07-31','14:30:00','In Progress','Fever and headache for 3 days',NULL,NULL,2,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(6,1,6,1,2,'FUTH-LF-ENC-2026-000006','Outpatient','2026-07-31','15:30:00','In Progress','Fever and headache for 3 days',NULL,NULL,2,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL);
/*!40000 ALTER TABLE `encounters` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `expense_claims`
--

DROP TABLE IF EXISTS `expense_claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `expense_claims` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `claim_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `claimant_id` bigint(20) unsigned NOT NULL,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(191) NOT NULL,
  `type` enum('Travel','Medical Supplies','Office Supplies','Maintenance','Utility','Transport','Meals','Training','Other') NOT NULL DEFAULT 'Other',
  `expense_date` date NOT NULL,
  `total_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `reimbursable_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `approved_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'NGN',
  `description` text DEFAULT NULL,
  `receipts` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`receipts`)),
  `status` enum('Draft','Submitted','Under Review','Approved','Rejected','Paid','Cancelled') NOT NULL DEFAULT 'Draft',
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expense_claims_claim_number_unique` (`claim_number`),
  KEY `expense_claims_department_id_foreign` (`department_id`),
  KEY `expense_claims_reviewed_by_foreign` (`reviewed_by`),
  KEY `expense_claims_approved_by_foreign` (`approved_by`),
  KEY `expense_claims_hospital_id_status_index` (`hospital_id`,`status`),
  KEY `expense_claims_claimant_id_status_index` (`claimant_id`,`status`),
  CONSTRAINT `expense_claims_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expense_claims_claimant_id_foreign` FOREIGN KEY (`claimant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `expense_claims_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expense_claims_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `expense_claims_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expense_claims`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `expense_claims` WRITE;
/*!40000 ALTER TABLE `expense_claims` DISABLE KEYS */;
/*!40000 ALTER TABLE `expense_claims` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `goods_received_note_items`
--

DROP TABLE IF EXISTS `goods_received_note_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `goods_received_note_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `grn_id` bigint(20) unsigned NOT NULL,
  `drug_id` bigint(20) unsigned NOT NULL,
  `drug_batch_id` bigint(20) unsigned DEFAULT NULL,
  `drug_name` varchar(191) NOT NULL,
  `batch_number` varchar(191) NOT NULL,
  `expiry_date` date NOT NULL,
  `manufactured_date` date DEFAULT NULL,
  `quantity_ordered` decimal(12,2) NOT NULL DEFAULT 0.00,
  `quantity_received` decimal(12,2) NOT NULL DEFAULT 0.00,
  `quantity_rejected` decimal(12,2) NOT NULL DEFAULT 0.00,
  `unit_cost` decimal(14,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(14,2) NOT NULL DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_received_note_items_drug_batch_id_foreign` (`drug_batch_id`),
  KEY `goods_received_note_items_grn_id_index` (`grn_id`),
  KEY `goods_received_note_items_drug_id_index` (`drug_id`),
  CONSTRAINT `goods_received_note_items_drug_batch_id_foreign` FOREIGN KEY (`drug_batch_id`) REFERENCES `drug_batches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `goods_received_note_items_drug_id_foreign` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `goods_received_note_items_grn_id_foreign` FOREIGN KEY (`grn_id`) REFERENCES `goods_received_notes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goods_received_note_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `goods_received_note_items` WRITE;
/*!40000 ALTER TABLE `goods_received_note_items` DISABLE KEYS */;
INSERT INTO `goods_received_note_items` VALUES
(1,1,1,NULL,'Paracetamol Tab','BT-1-2024','2028-08-04',NULL,427.00,427.00,0.00,5.00,2135.00,NULL,'2026-08-04 22:24:59','2026-08-04 22:24:59'),
(2,1,2,NULL,'Ibuprofen Tab','BT-2-2024','2028-08-04',NULL,249.00,249.00,0.00,8.00,1992.00,NULL,'2026-08-04 22:24:59','2026-08-04 22:24:59'),
(3,1,3,NULL,'Aspirin Tab','BT-3-2024','2028-08-04',NULL,270.00,270.00,0.00,4.00,1080.00,NULL,'2026-08-04 22:24:59','2026-08-04 22:24:59'),
(4,1,4,NULL,'Diclofenac Tab','BT-4-2024','2028-08-04',NULL,442.00,442.00,0.00,7.00,3094.00,NULL,'2026-08-04 22:24:59','2026-08-04 22:24:59'),
(5,1,5,NULL,'Amoxicillin Cap','BT-5-2024','2028-08-04',NULL,384.00,384.00,0.00,12.00,4608.00,NULL,'2026-08-04 22:24:59','2026-08-04 22:24:59');
/*!40000 ALTER TABLE `goods_received_note_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `goods_received_notes`
--

DROP TABLE IF EXISTS `goods_received_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `goods_received_notes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `grn_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `purchase_id` bigint(20) unsigned DEFAULT NULL,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `receipt_id` bigint(20) unsigned DEFAULT NULL,
  `dispensing_point_id` bigint(20) unsigned DEFAULT NULL,
  `received_by` bigint(20) unsigned NOT NULL,
  `verified_by` bigint(20) unsigned DEFAULT NULL,
  `received_at` datetime NOT NULL,
  `invoice_number` varchar(191) DEFAULT NULL,
  `delivery_note_number` varchar(191) DEFAULT NULL,
  `lpo_number` varchar(191) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `total_value` decimal(14,2) NOT NULL DEFAULT 0.00,
  `total_rejected` decimal(14,2) NOT NULL DEFAULT 0.00,
  `status` enum('Pending','Verified','Rejected','Posted') NOT NULL DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `goods_received_notes_grn_number_unique` (`grn_number`),
  KEY `goods_received_notes_purchase_id_foreign` (`purchase_id`),
  KEY `goods_received_notes_supplier_id_foreign` (`supplier_id`),
  KEY `goods_received_notes_receipt_id_foreign` (`receipt_id`),
  KEY `goods_received_notes_dispensing_point_id_foreign` (`dispensing_point_id`),
  KEY `goods_received_notes_received_by_foreign` (`received_by`),
  KEY `goods_received_notes_verified_by_foreign` (`verified_by`),
  KEY `goods_received_notes_hospital_id_received_at_index` (`hospital_id`,`received_at`),
  KEY `goods_received_notes_grn_number_index` (`grn_number`),
  CONSTRAINT `goods_received_notes_dispensing_point_id_foreign` FOREIGN KEY (`dispensing_point_id`) REFERENCES `dispensing_points` (`id`) ON DELETE SET NULL,
  CONSTRAINT `goods_received_notes_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `goods_received_notes_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE SET NULL,
  CONSTRAINT `goods_received_notes_receipt_id_foreign` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `goods_received_notes_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `goods_received_notes_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `goods_received_notes_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goods_received_notes`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `goods_received_notes` WRITE;
/*!40000 ALTER TABLE `goods_received_notes` DISABLE KEYS */;
INSERT INTO `goods_received_notes` VALUES
(1,'GRN-2026-000001',1,NULL,1,NULL,1,5,1,'2026-07-28 22:24:59','INV-2024-001','DN-2024-001',NULL,'2026-07-25',12909.00,0.00,'Verified',NULL,'2026-08-04 22:24:59','2026-08-04 22:24:59',NULL);
/*!40000 ALTER TABLE `goods_received_notes` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `holidays`
--

DROP TABLE IF EXISTS `holidays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `holidays` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `holiday_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `type` enum('Public Holiday','Religious','National','Hospital-specific','Local') NOT NULL DEFAULT 'Public Holiday',
  `is_working_day` tinyint(1) NOT NULL DEFAULT 0,
  `affects_clinics` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `holidays_created_by_foreign` (`created_by`),
  KEY `holidays_hospital_id_holiday_date_index` (`hospital_id`,`holiday_date`),
  CONSTRAINT `holidays_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `holidays_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holidays`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `holidays` WRITE;
/*!40000 ALTER TABLE `holidays` DISABLE KEYS */;
INSERT INTO `holidays` VALUES
(1,1,'New Year\'s Day','2026-01-01','2026-01-01','Public Holiday',0,1,NULL,1,'2026-08-04 21:50:23','2026-08-04 21:50:23',NULL),
(2,1,'Workers\' Day','2026-05-01','2026-05-01','Public Holiday',0,1,NULL,1,'2026-08-04 21:50:23','2026-08-04 21:50:23',NULL),
(3,1,'Democracy Day','2026-06-12','2026-06-12','National',0,1,NULL,1,'2026-08-04 21:50:23','2026-08-04 21:50:23',NULL),
(4,1,'Independence Day','2026-10-01','2026-10-01','National',0,1,NULL,1,'2026-08-04 21:50:23','2026-08-04 21:50:23',NULL),
(5,1,'Christmas Day','2026-12-25','2026-12-25','Religious',0,1,NULL,1,'2026-08-04 21:50:23','2026-08-04 21:50:23',NULL),
(6,1,'Boxing Day','2026-12-26','2026-12-26','Public Holiday',0,1,NULL,1,'2026-08-04 21:50:23','2026-08-04 21:50:23',NULL),
(7,1,'Eid al-Fitr (estimated)','2026-04-10','2026-04-10','Religious',0,1,NULL,1,'2026-08-04 21:50:23','2026-08-04 21:50:23',NULL),
(8,1,'Eid al-Adha (estimated)','2026-06-17','2026-06-17','Religious',0,1,NULL,1,'2026-08-04 21:50:23','2026-08-04 21:50:23',NULL),
(9,1,'Good Friday','2026-03-29','2026-03-29','Religious',0,1,NULL,1,'2026-08-04 21:50:23','2026-08-04 21:50:23',NULL),
(10,1,'Easter Monday','2026-04-01','2026-04-01','Religious',0,1,NULL,1,'2026-08-04 21:50:23','2026-08-04 21:50:23',NULL),
(11,1,'Mawlid (estimated)','2026-09-16','2026-09-16','Religious',0,1,NULL,1,'2026-08-04 21:50:23','2026-08-04 21:50:23',NULL);
/*!40000 ALTER TABLE `holidays` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `hospitals`
--

DROP TABLE IF EXISTS `hospitals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `hospitals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hospitals`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `hospitals` WRITE;
/*!40000 ALTER TABLE `hospitals` DISABLE KEYS */;
INSERT INTO `hospitals` VALUES
(1,'Federal University Teaching Hospital, Lafia','FUTH-LF',1,'2026-07-31 11:00:00','2026-07-31 11:00:00');
/*!40000 ALTER TABLE `hospitals` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `insurance_claim_lines`
--

DROP TABLE IF EXISTS `insurance_claim_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `insurance_claim_lines` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `insurance_claim_id` bigint(20) unsigned NOT NULL,
  `service_type` enum('Consultation','Pharmacy','Laboratory','Radiology','Procedure','Consumable','Accommodation','Other') NOT NULL DEFAULT 'Consultation',
  `service_code` varchar(191) DEFAULT NULL,
  `service_name` varchar(191) NOT NULL,
  `item_reference` varchar(191) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(14,2) NOT NULL DEFAULT 0.00,
  `amount_claimed` decimal(14,2) NOT NULL DEFAULT 0.00,
  `amount_approved` decimal(14,2) NOT NULL DEFAULT 0.00,
  `amount_paid` decimal(14,2) NOT NULL DEFAULT 0.00,
  `patient_copay` decimal(14,2) NOT NULL DEFAULT 0.00,
  `status` enum('Pending','Approved','Partially Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `rejection_reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `insurance_claim_lines_insurance_claim_id_status_index` (`insurance_claim_id`,`status`),
  KEY `insurance_claim_lines_service_type_index` (`service_type`),
  CONSTRAINT `insurance_claim_lines_insurance_claim_id_foreign` FOREIGN KEY (`insurance_claim_id`) REFERENCES `insurance_claims` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insurance_claim_lines`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `insurance_claim_lines` WRITE;
/*!40000 ALTER TABLE `insurance_claim_lines` DISABLE KEYS */;
/*!40000 ALTER TABLE `insurance_claim_lines` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `insurance_claims`
--

DROP TABLE IF EXISTS `insurance_claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `insurance_claims` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `claim_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `patient_insurance_id` bigint(20) unsigned NOT NULL,
  `insurance_provider_id` bigint(20) unsigned NOT NULL,
  `insurance_plan_id` bigint(20) unsigned NOT NULL,
  `encounter_id` bigint(20) unsigned DEFAULT NULL,
  `service_date_start` date NOT NULL,
  `service_date_end` date NOT NULL,
  `claim_type` enum('Outpatient','Inpatient','Emergency','Pharmacy','Laboratory','Radiology','Procedure','Maternity') NOT NULL DEFAULT 'Outpatient',
  `diagnosis_icd10` varchar(191) DEFAULT NULL,
  `diagnosis_description` text DEFAULT NULL,
  `treatment_summary` text DEFAULT NULL,
  `total_claimed` decimal(14,2) NOT NULL DEFAULT 0.00,
  `total_approved` decimal(14,2) NOT NULL DEFAULT 0.00,
  `total_paid` decimal(14,2) NOT NULL DEFAULT 0.00,
  `patient_copay` decimal(14,2) NOT NULL DEFAULT 0.00,
  `deduction` decimal(14,2) NOT NULL DEFAULT 0.00,
  `pre_authorization_code` varchar(191) DEFAULT NULL,
  `status` enum('Draft','Submitted','Acknowledged','Processing','Approved','Partially Approved','Rejected','Paid','Reversed') NOT NULL DEFAULT 'Draft',
  `submitted_at` datetime DEFAULT NULL,
  `acknowledged_at` datetime DEFAULT NULL,
  `processed_at` datetime DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `submitted_by` bigint(20) unsigned DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `insurance_claims_claim_number_unique` (`claim_number`),
  KEY `insurance_claims_patient_insurance_id_foreign` (`patient_insurance_id`),
  KEY `insurance_claims_insurance_plan_id_foreign` (`insurance_plan_id`),
  KEY `insurance_claims_encounter_id_foreign` (`encounter_id`),
  KEY `insurance_claims_created_by_foreign` (`created_by`),
  KEY `insurance_claims_submitted_by_foreign` (`submitted_by`),
  KEY `insurance_claims_hospital_id_status_index` (`hospital_id`,`status`),
  KEY `insurance_claims_patient_id_status_index` (`patient_id`,`status`),
  KEY `insurance_claims_insurance_provider_id_status_index` (`insurance_provider_id`,`status`),
  KEY `insurance_claims_claim_number_index` (`claim_number`),
  CONSTRAINT `insurance_claims_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `insurance_claims_encounter_id_foreign` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `insurance_claims_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `insurance_claims_insurance_plan_id_foreign` FOREIGN KEY (`insurance_plan_id`) REFERENCES `insurance_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `insurance_claims_insurance_provider_id_foreign` FOREIGN KEY (`insurance_provider_id`) REFERENCES `insurance_providers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `insurance_claims_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `insurance_claims_patient_insurance_id_foreign` FOREIGN KEY (`patient_insurance_id`) REFERENCES `patient_insurance` (`id`) ON DELETE CASCADE,
  CONSTRAINT `insurance_claims_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insurance_claims`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `insurance_claims` WRITE;
/*!40000 ALTER TABLE `insurance_claims` DISABLE KEYS */;
/*!40000 ALTER TABLE `insurance_claims` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `insurance_plans`
--

DROP TABLE IF EXISTS `insurance_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `insurance_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `insurance_provider_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `tier` enum('Formal','Informal','Equity','Vulnerable','Premium','Standard','VIP') NOT NULL DEFAULT 'Standard',
  `coverage_type` enum('Individual','Family','Corporate','Group') NOT NULL DEFAULT 'Individual',
  `annual_premium` decimal(14,2) NOT NULL DEFAULT 0.00,
  `annual_benefit_limit` decimal(14,2) NOT NULL DEFAULT 0.00,
  `per_encounter_limit` decimal(14,2) NOT NULL DEFAULT 0.00,
  `outpatient_deduction` decimal(14,2) NOT NULL DEFAULT 0.00,
  `inpatient_deduction` decimal(14,2) NOT NULL DEFAULT 0.00,
  `copay_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `covered_services` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`covered_services`)),
  `excluded_services` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`excluded_services`)),
  `covers_pharmacy` tinyint(1) NOT NULL DEFAULT 1,
  `covers_lab` tinyint(1) NOT NULL DEFAULT 1,
  `covers_radiology` tinyint(1) NOT NULL DEFAULT 1,
  `covers_inpatient` tinyint(1) NOT NULL DEFAULT 0,
  `covers_maternity` tinyint(1) NOT NULL DEFAULT 0,
  `covers_dental` tinyint(1) NOT NULL DEFAULT 0,
  `covers_optical` tinyint(1) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `insurance_plans_code_unique` (`code`),
  KEY `insurance_plans_insurance_provider_id_status_index` (`insurance_provider_id`,`status`),
  CONSTRAINT `insurance_plans_insurance_provider_id_foreign` FOREIGN KEY (`insurance_provider_id`) REFERENCES `insurance_providers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insurance_plans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `insurance_plans` WRITE;
/*!40000 ALTER TABLE `insurance_plans` DISABLE KEYS */;
INSERT INTO `insurance_plans` VALUES
(1,1,'NHIA Standard Plan','NHIA-STD','Standard','Family',60000.00,500000.00,50000.00,500.00,2000.00,10.00,NULL,NULL,1,1,1,1,1,0,0,'Standard family coverage plan','Active','2026-08-04 21:16:25','2026-08-04 21:16:25',NULL),
(2,1,'NHIA Formal Sector Plan','NHIA-FORMAL','Formal','Family',0.00,300000.00,30000.00,0.00,0.00,0.00,NULL,NULL,1,1,1,1,1,0,0,'NHIA Formal Sector Programme','Active','2026-08-04 21:16:25','2026-08-04 21:16:25',NULL),
(3,2,'Hygeia Standard Plan','HYGEIA-STD','Standard','Family',60000.00,500000.00,50000.00,500.00,2000.00,10.00,NULL,NULL,1,1,1,1,1,0,0,'Standard family coverage plan','Active','2026-08-04 21:16:25','2026-08-04 21:16:25',NULL),
(4,3,'AXA Mansard Standard Plan','AXA-MD-STD','Standard','Family',60000.00,500000.00,50000.00,500.00,2000.00,10.00,NULL,NULL,1,1,1,1,1,0,0,'Standard family coverage plan','Active','2026-08-04 21:16:25','2026-08-04 21:16:25',NULL),
(5,4,'Reliance Standard Plan','RLNC-STD','Standard','Family',60000.00,500000.00,50000.00,500.00,2000.00,10.00,NULL,NULL,1,1,1,1,1,0,0,'Standard family coverage plan','Active','2026-08-04 21:16:25','2026-08-04 21:16:25',NULL),
(6,5,'Greenbay Standard Plan','GRNB-STD','Standard','Family',60000.00,500000.00,50000.00,500.00,2000.00,10.00,NULL,NULL,1,1,1,1,1,0,0,'Standard family coverage plan','Active','2026-08-04 21:16:25','2026-08-04 21:16:25',NULL);
/*!40000 ALTER TABLE `insurance_plans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `insurance_providers`
--

DROP TABLE IF EXISTS `insurance_providers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `insurance_providers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `short_name` varchar(191) DEFAULT NULL,
  `type` enum('NHIA','HMO','Private Insurance','Corporate','Community-Based','Government') NOT NULL DEFAULT 'HMO',
  `nhia_code` varchar(191) DEFAULT NULL,
  `contact_person` varchar(191) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `bank_account_number` varchar(191) DEFAULT NULL,
  `bank_name` varchar(191) DEFAULT NULL,
  `capitation_rate` decimal(14,2) NOT NULL DEFAULT 0.00,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `claim_settlement_days` int(11) NOT NULL DEFAULT 30,
  `logo_path` varchar(191) DEFAULT NULL,
  `status` enum('Active','Suspended','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `insurance_providers_code_unique` (`code`),
  KEY `insurance_providers_hospital_id_status_index` (`hospital_id`,`status`),
  KEY `insurance_providers_type_index` (`type`),
  CONSTRAINT `insurance_providers_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insurance_providers`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `insurance_providers` WRITE;
/*!40000 ALTER TABLE `insurance_providers` DISABLE KEYS */;
INSERT INTO `insurance_providers` VALUES
(1,1,'National Health Insurance Authority','NHIA','NHIA','NHIA','NHIA-NG','Claims Manager','+2348000000000','nhia@claims.example','Lagos, Nigeria',NULL,NULL,1500.00,10.00,60,NULL,'Active','2026-08-04 21:16:25','2026-08-04 21:16:25',NULL),
(2,1,'Hygeia HMO','HYGEIA','Hygeia','HMO',NULL,'Claims Manager','+2348000000000','hygeia@claims.example','Lagos, Nigeria',NULL,NULL,2000.00,15.00,45,NULL,'Active','2026-08-04 21:16:25','2026-08-04 21:16:25',NULL),
(3,1,'AXA Mansard Health','AXA-MD','AXA Mansard','HMO',NULL,'Claims Manager','+2348000000000','axa-md@claims.example','Lagos, Nigeria',NULL,NULL,2500.00,12.00,30,NULL,'Active','2026-08-04 21:16:25','2026-08-04 21:16:25',NULL),
(4,1,'Reliance HMO','RLNC','Reliance','HMO',NULL,'Claims Manager','+2348000000000','rlnc@claims.example','Lagos, Nigeria',NULL,NULL,1800.00,10.00,45,NULL,'Active','2026-08-04 21:16:25','2026-08-04 21:16:25',NULL),
(5,1,'Greenbay Healthcare','GRNB','Greenbay','Private Insurance',NULL,'Claims Manager','+2348000000000','grnb@claims.example','Lagos, Nigeria',NULL,NULL,3000.00,20.00,30,NULL,'Active','2026-08-04 21:16:25','2026-08-04 21:16:25',NULL);
/*!40000 ALTER TABLE `insurance_providers` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `service_type` enum('Consultation','Procedure','Laboratory','Radiology','Pharmacy','Accommodation','Consumable','Other') NOT NULL DEFAULT 'Consultation',
  `description` varchar(191) NOT NULL,
  `reference_number` varchar(191) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(14,2) NOT NULL DEFAULT 0.00,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `tax_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `insurance_covered` decimal(14,2) NOT NULL DEFAULT 0.00,
  `patient_portion` decimal(14,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_service_id_foreign` (`service_id`),
  KEY `invoice_items_invoice_id_index` (`invoice_id`),
  KEY `invoice_items_service_type_index` (`service_type`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_items_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `encounter_id` bigint(20) unsigned DEFAULT NULL,
  `patient_insurance_id` bigint(20) unsigned DEFAULT NULL,
  `payment_mode` enum('Cash','Insurance','NHIA','Corporate','Charity','Mixed') NOT NULL DEFAULT 'Cash',
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `subtotal` decimal(14,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `insurance_covered` decimal(14,2) NOT NULL DEFAULT 0.00,
  `patient_portion` decimal(14,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `amount_paid` decimal(14,2) NOT NULL DEFAULT 0.00,
  `balance_due` decimal(14,2) NOT NULL DEFAULT 0.00,
  `status` enum('Draft','Pending','Partially Paid','Paid','Overdue','Cancelled','Refunded') NOT NULL DEFAULT 'Draft',
  `created_by` bigint(20) unsigned NOT NULL,
  `billed_by` bigint(20) unsigned DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_encounter_id_foreign` (`encounter_id`),
  KEY `invoices_patient_insurance_id_foreign` (`patient_insurance_id`),
  KEY `invoices_created_by_foreign` (`created_by`),
  KEY `invoices_billed_by_foreign` (`billed_by`),
  KEY `invoices_hospital_id_status_index` (`hospital_id`,`status`),
  KEY `invoices_patient_id_status_index` (`patient_id`,`status`),
  KEY `invoices_invoice_number_index` (`invoice_number`),
  CONSTRAINT `invoices_billed_by_foreign` FOREIGN KEY (`billed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_encounter_id_foreign` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_patient_insurance_id_foreign` FOREIGN KEY (`patient_insurance_id`) REFERENCES `patient_insurance` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `journal_entries`
--

DROP TABLE IF EXISTS `journal_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `journal_entries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `entry_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `entry_date` date NOT NULL,
  `type` enum('Sales','Purchase','Payment','Receipt','Journal','Payroll','Adjustment','Closing','Reversal') NOT NULL DEFAULT 'Journal',
  `reference_number` varchar(191) DEFAULT NULL,
  `reference_type` varchar(191) DEFAULT NULL,
  `reference_id` bigint(20) unsigned DEFAULT NULL,
  `description` text NOT NULL,
  `total_debit` decimal(18,2) NOT NULL DEFAULT 0.00,
  `total_credit` decimal(18,2) NOT NULL DEFAULT 0.00,
  `is_posted` tinyint(1) NOT NULL DEFAULT 0,
  `is_reversed` tinyint(1) NOT NULL DEFAULT 0,
  `posted_at` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `posted_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `journal_entries_entry_number_unique` (`entry_number`),
  KEY `journal_entries_created_by_foreign` (`created_by`),
  KEY `journal_entries_posted_by_foreign` (`posted_by`),
  KEY `journal_entries_hospital_id_entry_date_index` (`hospital_id`,`entry_date`),
  KEY `journal_entries_entry_number_index` (`entry_number`),
  KEY `journal_entries_reference_type_reference_id_index` (`reference_type`,`reference_id`),
  CONSTRAINT `journal_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `journal_entries_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `journal_entries_posted_by_foreign` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journal_entries`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `journal_entries` WRITE;
/*!40000 ALTER TABLE `journal_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `journal_entries` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `journal_lines`
--

DROP TABLE IF EXISTS `journal_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `journal_lines` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `journal_entry_id` bigint(20) unsigned NOT NULL,
  `chart_of_account_id` bigint(20) unsigned NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `debit` decimal(18,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(18,2) NOT NULL DEFAULT 0.00,
  `cost_center` varchar(191) DEFAULT NULL,
  `reference_id` bigint(20) unsigned DEFAULT NULL,
  `reference_type` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `journal_lines_journal_entry_id_index` (`journal_entry_id`),
  KEY `journal_lines_chart_of_account_id_index` (`chart_of_account_id`),
  CONSTRAINT `journal_lines_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `journal_lines_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journal_lines`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `journal_lines` WRITE;
/*!40000 ALTER TABLE `journal_lines` DISABLE KEYS */;
/*!40000 ALTER TABLE `journal_lines` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `lab_order_items`
--

DROP TABLE IF EXISTS `lab_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lab_order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lab_order_id` bigint(20) unsigned NOT NULL,
  `lab_test_id` bigint(20) unsigned NOT NULL,
  `test_name` varchar(255) NOT NULL,
  `loinc_code` varchar(20) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('Pending','Collected','In Progress','Completed','Verified','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lab_order_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `lab_order_items` WRITE;
/*!40000 ALTER TABLE `lab_order_items` DISABLE KEYS */;
INSERT INTO `lab_order_items` VALUES
(1,1,24,'Malaria Parasite (Blood Film)','10501-5',500.00,'Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(2,1,1,'Haemoglobin','718-7',200.00,'Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(3,2,24,'Malaria Parasite (Blood Film)','10501-5',500.00,'Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(4,2,1,'Haemoglobin','718-7',200.00,'Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(5,3,24,'Malaria Parasite (Blood Film)','10501-5',500.00,'Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(6,3,1,'Haemoglobin','718-7',200.00,'Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(7,4,24,'Malaria Parasite (Blood Film)','10501-5',500.00,'Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(8,4,1,'Haemoglobin','718-7',200.00,'Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(9,5,24,'Malaria Parasite (Blood Film)','10501-5',500.00,'Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(10,5,1,'Haemoglobin','718-7',200.00,'Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(11,6,24,'Malaria Parasite (Blood Film)','10501-5',500.00,'Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(12,6,1,'Haemoglobin','718-7',200.00,'Pending','2026-07-31 11:00:00','2026-07-31 11:00:00');
/*!40000 ALTER TABLE `lab_order_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `lab_orders`
--

DROP TABLE IF EXISTS `lab_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lab_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `encounter_id` bigint(20) unsigned DEFAULT NULL,
  `requested_by` bigint(20) unsigned NOT NULL,
  `received_by` bigint(20) unsigned DEFAULT NULL,
  `order_number` varchar(255) NOT NULL,
  `ordered_at` datetime NOT NULL,
  `collected_at` datetime DEFAULT NULL,
  `resulted_at` datetime DEFAULT NULL,
  `priority` enum('Routine','Urgent','STAT','ASAP') NOT NULL DEFAULT 'Routine',
  `status` enum('Pending','Sample Collected','In Progress','Completed','Verified','Cancelled','Rejected') NOT NULL DEFAULT 'Pending',
  `clinical_notes` text DEFAULT NULL,
  `specimen_source` text DEFAULT NULL,
  `fasting_status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `lab_orders_patient_id_ordered_at_index` (`patient_id`,`ordered_at`),
  KEY `lab_orders_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lab_orders`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `lab_orders` WRITE;
/*!40000 ALTER TABLE `lab_orders` DISABLE KEYS */;
INSERT INTO `lab_orders` VALUES
(1,1,1,1,2,NULL,'FUTH-LF-LAB-2026-000001','2026-07-31 12:00:00',NULL,NULL,'Urgent','Pending','Investigate malaria','Whole blood','Random','2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(2,1,2,2,2,NULL,'FUTH-LF-LAB-2026-000002','2026-07-31 12:00:00',NULL,NULL,'Urgent','Pending','Investigate malaria','Whole blood','Random','2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(3,1,3,3,2,NULL,'FUTH-LF-LAB-2026-000003','2026-07-31 12:00:00',NULL,NULL,'Urgent','Pending','Investigate malaria','Whole blood','Random','2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(4,1,4,4,2,NULL,'FUTH-LF-LAB-2026-000004','2026-07-31 12:00:00',NULL,NULL,'Urgent','Pending','Investigate malaria','Whole blood','Random','2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(5,1,5,5,2,NULL,'FUTH-LF-LAB-2026-000005','2026-07-31 12:00:00',NULL,NULL,'Urgent','Pending','Investigate malaria','Whole blood','Random','2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(6,1,6,6,2,NULL,'FUTH-LF-LAB-2026-000006','2026-07-31 12:00:00',NULL,NULL,'Urgent','Pending','Investigate malaria','Whole blood','Random','2026-07-31 11:00:00','2026-07-31 11:00:00',NULL);
/*!40000 ALTER TABLE `lab_orders` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `lab_panels`
--

DROP TABLE IF EXISTS `lab_panels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lab_panels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `loinc_code` varchar(20) DEFAULT NULL,
  `specimen_type` varchar(255) DEFAULT NULL,
  `container` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `turnaround_hours` int(10) unsigned NOT NULL DEFAULT 24,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `lab_panels_loinc_code_index` (`loinc_code`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lab_panels`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `lab_panels` WRITE;
/*!40000 ALTER TABLE `lab_panels` DISABLE KEYS */;
INSERT INTO `lab_panels` VALUES
(1,1,'Full Blood Count','FBC','58410-2','Whole blood','EDTA','Complete blood count panel',1500.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(2,1,'Liver Function Test','LFT','24221-7','Serum','Plain','Hepatic function panel',2500.00,6,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(3,1,'Renal Function Test','RFT','24360-2','Serum','Plain','Renal function panel',2000.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(4,1,'Lipid Profile','LIPID','24331-3','Serum','Plain','Lipid panel',2500.00,6,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(5,1,'Blood Glucose Panel','GLUC','2345-7','Plasma','Fluoride','Glucose & HbA1c',1200.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(6,1,'Urinalysis','UA','24356-0','Urine','Sterile','Routine urine examination',500.00,2,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(7,1,'Malaria Screen','MAL','10501-5','Whole blood','EDTA','Malaria microscopy & RDT',800.00,2,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(8,1,'HIV Screening','HIV','89567-1','Serum','Plain','HIV rapid test',1000.00,2,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(9,1,'Typhoid Screen','TYPH','22028-7','Serum','Plain','Widal test',1000.00,2,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(10,1,'Pregnancy Test','PT','2112-1','Urine','Sterile','Urine β-hCG',500.00,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(11,1,'Sickling Test','SICK','91580-3','Whole blood','EDTA','Sickling + Hb electrophoresis',1200.00,6,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(12,1,'Cross Match','XM','1156-9','Whole blood','EDTA','Blood group & cross-match',1500.00,3,1,'2026-07-31 11:00:00','2026-07-31 11:00:00');
/*!40000 ALTER TABLE `lab_panels` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `lab_results`
--

DROP TABLE IF EXISTS `lab_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lab_results` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lab_order_item_id` bigint(20) unsigned NOT NULL,
  `lab_test_id` bigint(20) unsigned NOT NULL,
  `performed_by` bigint(20) unsigned DEFAULT NULL,
  `verified_by` bigint(20) unsigned DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `value_numeric` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `reference_range` varchar(255) DEFAULT NULL,
  `flag` enum('Normal','Low','High','Critical Low','Critical High','Abnormal') NOT NULL DEFAULT 'Normal',
  `interpretation` text DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `performed_at` datetime DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lab_results`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `lab_results` WRITE;
/*!40000 ALTER TABLE `lab_results` DISABLE KEYS */;
/*!40000 ALTER TABLE `lab_results` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `lab_tests`
--

DROP TABLE IF EXISTS `lab_tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lab_tests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `lab_panel_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `loinc_code` varchar(20) DEFAULT NULL,
  `specimen_type` varchar(255) DEFAULT NULL,
  `container` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `reference_range_low` varchar(255) DEFAULT NULL,
  `reference_range_high` varchar(255) DEFAULT NULL,
  `reference_notes` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `turnaround_hours` int(10) unsigned NOT NULL DEFAULT 24,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `lab_tests_loinc_code_index` (`loinc_code`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lab_tests`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `lab_tests` WRITE;
/*!40000 ALTER TABLE `lab_tests` DISABLE KEYS */;
INSERT INTO `lab_tests` VALUES
(1,1,1,'Haemoglobin','HB','718-7','Whole blood','EDTA','g/dL','11.5','16.5',NULL,NULL,200.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(2,1,1,'White Blood Cell Count','WBC','6690-2','Whole blood','EDTA','x10^9/L','4','11',NULL,NULL,200.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(3,1,1,'Platelet Count','PLT','777-3','Whole blood','EDTA','x10^9/L','150','450',NULL,NULL,200.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(4,1,1,'Red Blood Cell Count','RBC','789-8','Whole blood','EDTA','x10^12/L','4.0','5.5',NULL,NULL,200.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(5,1,1,'PCV / Haematocrit','PCV','4544-3','Whole blood','EDTA','%','36','50',NULL,NULL,200.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(6,1,2,'ALT','ALT','1742-6','Serum','Plain','U/L','7','40',NULL,NULL,300.00,6,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(7,1,2,'AST','AST','1920-8','Serum','Plain','U/L','8','40',NULL,NULL,300.00,6,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(8,1,2,'Alkaline Phosphatase','ALP','6768-6','Serum','Plain','U/L','40','130',NULL,NULL,300.00,6,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(9,1,2,'Total Bilirubin','TBIL','1975-2','Serum','Plain','μmol/L','2','21',NULL,NULL,350.00,6,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(10,1,2,'Total Protein','TP','2885-2','Serum','Plain','g/L','60','80',NULL,NULL,300.00,6,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(11,1,2,'Albumin','ALB','1751-7','Serum','Plain','g/L','35','50',NULL,NULL,300.00,6,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(12,1,3,'Urea','UREA','3094-0','Serum','Plain','mmol/L','2.5','7.0',NULL,NULL,300.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(13,1,3,'Creatinine','CREAT','2160-0','Serum','Plain','μmol/L','60','120',NULL,NULL,300.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(14,1,3,'Sodium','NA','2951-2','Serum','Plain','mmol/L','135','145',NULL,NULL,300.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(15,1,3,'Potassium','K','2823-3','Serum','Plain','mmol/L','3.5','5.0',NULL,NULL,300.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(16,1,3,'Bicarbonate','HCO3','1963-8','Serum','Plain','mmol/L','22','29',NULL,NULL,300.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(17,1,5,'Fasting Blood Glucose','FBG','1558-6','Plasma','Fluoride','mmol/L','3.9','6.1',NULL,NULL,400.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(18,1,5,'HbA1c','HBA1C','4548-4','Whole blood','EDTA','%','4.0','5.6',NULL,NULL,800.00,24,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(19,1,5,'Random Blood Glucose','RBG','2339-0','Plasma','Fluoride','mmol/L','3.9','7.8',NULL,NULL,400.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(20,1,4,'Total Cholesterol','TC','2093-3','Serum','Plain','mmol/L','<5.0','<5.0',NULL,NULL,400.00,6,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(21,1,4,'HDL Cholesterol','HDL','2085-9','Serum','Plain','mmol/L','>1.0','>1.5',NULL,NULL,400.00,6,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(22,1,4,'LDL Cholesterol','LDL','13457-7','Serum','Plain','mmol/L','<3.0','<3.0',NULL,NULL,400.00,6,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(23,1,4,'Triglycerides','TG','2571-8','Serum','Plain','mmol/L','<1.7','<1.7',NULL,NULL,400.00,6,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(24,1,7,'Malaria Parasite (Blood Film)','MPBF','10501-5','Whole blood','EDTA','/HPF','Negative','Negative',NULL,NULL,500.00,2,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(25,1,7,'Malaria RDT','MRDT','9156-9','Whole blood','EDTA','','Negative','Negative',NULL,NULL,700.00,1,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(26,1,8,'HIV 1/2 Antibody','HIVAB','89567-1','Serum','Plain','','Non-reactive','Non-reactive',NULL,NULL,800.00,2,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(27,1,6,'Urine Protein','UPROT','5804-0','Urine','Sterile','mg/dL','Negative','Negative',NULL,NULL,100.00,2,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(28,1,6,'Urine Glucose','UGLUC','2350-7','Urine','Sterile','mmol/L','Negative','Negative',NULL,NULL,100.00,2,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(29,1,6,'Urine pH','UPH','5802-4','Urine','Sterile','','5.0','8.0',NULL,NULL,100.00,2,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(30,1,6,'Urine Specific Gravity','USG','5811-5','Urine','Sterile','','1.005','1.030',NULL,NULL,100.00,2,1,'2026-07-31 11:00:00','2026-07-31 11:00:00');
/*!40000 ALTER TABLE `lab_tests` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `leave_requests`
--

DROP TABLE IF EXISTS `leave_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `leave_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `leave_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `staff_id` bigint(20) unsigned DEFAULT NULL,
  `leave_type` enum('Annual','Sick','Casual','Maternity','Paternity','Compassionate','Study','Sabbatical','Unpaid','Other') NOT NULL DEFAULT 'Annual',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days_requested` int(11) NOT NULL,
  `days_approved` int(11) DEFAULT NULL,
  `reason` text NOT NULL,
  `handover_to` varchar(191) DEFAULT NULL,
  `handover_notes` text DEFAULT NULL,
  `attachment_paths` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachment_paths`)),
  `status` enum('Draft','Pending','Recommended','Approved','Rejected','Cancelled','On Leave','Returned') NOT NULL DEFAULT 'Pending',
  `recommended_by` bigint(20) unsigned DEFAULT NULL,
  `recommended_at` datetime DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leave_requests_leave_number_unique` (`leave_number`),
  KEY `leave_requests_staff_id_foreign` (`staff_id`),
  KEY `leave_requests_recommended_by_foreign` (`recommended_by`),
  KEY `leave_requests_approved_by_foreign` (`approved_by`),
  KEY `leave_requests_created_by_foreign` (`created_by`),
  KEY `leave_requests_user_id_status_index` (`user_id`,`status`),
  KEY `leave_requests_hospital_id_status_index` (`hospital_id`,`status`),
  KEY `leave_requests_start_date_end_date_index` (`start_date`,`end_date`),
  CONSTRAINT `leave_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leave_requests_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leave_requests_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leave_requests_recommended_by_foreign` FOREIGN KEY (`recommended_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leave_requests_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leave_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_requests`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `leave_requests` WRITE;
/*!40000 ALTER TABLE `leave_requests` DISABLE KEYS */;
INSERT INTO `leave_requests` VALUES
(1,'LVR-2026-000001',1,4,4,'Annual','2026-08-18','2026-08-25',7,NULL,'Annual vacation - family commitments.','Nurse Amina (afternoon shift)','All patients in Male Medical Ward stable. Bed 03 due for discharge tomorrow.',NULL,'Pending',NULL,NULL,NULL,NULL,NULL,NULL,4,'2026-08-04 21:50:23','2026-08-04 21:50:23',NULL),
(2,'LVR-2026-000002',1,5,5,'Sick','2026-08-02','2026-08-06',4,NULL,'Malaria treatment and recovery.',NULL,NULL,NULL,'Approved',NULL,NULL,1,'2026-08-02 21:50:23',NULL,NULL,5,'2026-08-04 21:50:23','2026-08-04 21:50:23',NULL);
/*!40000 ALTER TABLE `leave_requests` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `locals`
--

DROP TABLE IF EXISTS `locals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `locals` (
  `local_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `state_id` bigint(20) unsigned NOT NULL,
  `local_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`local_id`),
  UNIQUE KEY `locals_state_id_local_name_unique` (`state_id`,`local_name`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locals`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `locals` WRITE;
/*!40000 ALTER TABLE `locals` DISABLE KEYS */;
/*!40000 ALTER TABLE `locals` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_07_15_203550_create_patients_table',1),
(5,'2026_07_15_215955_create_permission_tables',1),
(6,'2026_07_16_044333_create_hospitals_table',1),
(7,'2026_07_16_044351_create_clinics_table',1),
(8,'2026_07_17_032130_create_departments_table',1),
(9,'2026_07_17_032701_create_staff_table',1),
(10,'2026_07_17_040527_add_soft_deletes_to_departments_table',1),
(11,'2026_07_17_040545_add_soft_deletes_to_clinics_table',1),
(12,'2026_07_17_040605_add_soft_deletes_to_hospitals_table',1),
(13,'2026_07_21_152947_create_encounters_table',1),
(14,'2026_07_21_155916_create_appointments_table',1),
(28,'2026_08_01_000001_create_duty_rosters_table',2),
(29,'2026_08_01_000002_create_duty_postings_table',2),
(30,'2026_08_01_000003_create_insurance_providers_table',2),
(31,'2026_08_01_000004_create_insurance_plans_table',2),
(32,'2026_08_01_000005_create_patient_insurance_table',2),
(33,'2026_08_01_000006_create_insurance_claims_table',3),
(34,'2026_08_01_000007_create_insurance_claim_lines_table',3),
(35,'2026_08_01_000008_create_pre_authorizations_table',3),
(36,'2026_08_01_000009_create_services_table',3),
(37,'2026_08_01_000010_create_invoices_table',3),
(38,'2026_08_01_000011_create_invoice_items_table',3),
(39,'2026_08_01_000012_create_payments_table',3),
(40,'2026_08_01_000013_add_clinic_and_phone_to_users_table',3),
(41,'2026_08_01_000014_add_soft_deletes_to_existing_tables',4),
(42,'2026_08_02_000001_create_ward_bed_admission_tables',5),
(43,'2026_08_02_000002_create_accounting_tables',5),
(44,'2026_08_02_000003_create_schedule_tables',5),
(46,'2026_08_03_000001_create_store_inventory_tables',6),
(47,'2026_08_03_000002_create_blood_donors_table',7),
(48,'2026_08_03_000003_create_blood_donations_table',7),
(49,'2026_08_03_000003_create_procedures_service_categories_tables',7),
(50,'2026_08_03_000004_create_blood_units_table',7),
(51,'2026_08_03_000004_create_pharmacy_stock_enhancements',7),
(52,'2026_08_03_000005_create_blood_requests_table',7),
(53,'2026_08_03_000006_create_blood_crossmatches_table',8),
(54,'2026_08_03_000007_create_blood_transfusions_table',8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES
(1,'App\\Models\\User',1),
(2,'App\\Models\\User',1),
(3,'App\\Models\\User',2),
(3,'App\\Models\\User',3),
(4,'App\\Models\\User',4),
(5,'App\\Models\\User',5),
(6,'App\\Models\\User',6),
(7,'App\\Models\\User',7),
(8,'App\\Models\\User',8),
(9,'App\\Models\\User',9),
(10,'App\\Models\\User',10);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `patient_insurance`
--

DROP TABLE IF EXISTS `patient_insurance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient_insurance` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) unsigned NOT NULL,
  `insurance_provider_id` bigint(20) unsigned NOT NULL,
  `insurance_plan_id` bigint(20) unsigned NOT NULL,
  `policy_number` varchar(191) NOT NULL,
  `member_id` varchar(191) DEFAULT NULL,
  `enrollee_code` varchar(191) DEFAULT NULL,
  `principal_relationship` enum('Self','Spouse','Child','Parent','Sibling','Dependant') NOT NULL DEFAULT 'Self',
  `principal_name` varchar(191) DEFAULT NULL,
  `principal_dob` varchar(191) DEFAULT NULL,
  `principal_phone` varchar(191) DEFAULT NULL,
  `principal_nin` varchar(191) DEFAULT NULL,
  `enrollment_date` date NOT NULL,
  `effective_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `remaining_benefit` decimal(14,2) NOT NULL DEFAULT 0.00,
  `utilized_benefit` decimal(14,2) NOT NULL DEFAULT 0.00,
  `is_primary` tinyint(1) NOT NULL DEFAULT 1,
  `verification_status` enum('Pending','Verified','Rejected','Expired') NOT NULL DEFAULT 'Pending',
  `verified_at` datetime DEFAULT NULL,
  `verified_by` bigint(20) unsigned DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Active','Suspended','Expired','Cancelled') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patient_insurance_policy_number_unique` (`policy_number`),
  KEY `patient_insurance_insurance_plan_id_foreign` (`insurance_plan_id`),
  KEY `patient_insurance_verified_by_foreign` (`verified_by`),
  KEY `patient_insurance_patient_id_status_index` (`patient_id`,`status`),
  KEY `patient_insurance_insurance_provider_id_status_index` (`insurance_provider_id`,`status`),
  KEY `patient_insurance_policy_number_index` (`policy_number`),
  KEY `patient_insurance_effective_date_expiry_date_index` (`effective_date`,`expiry_date`),
  CONSTRAINT `patient_insurance_insurance_plan_id_foreign` FOREIGN KEY (`insurance_plan_id`) REFERENCES `insurance_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_insurance_insurance_provider_id_foreign` FOREIGN KEY (`insurance_provider_id`) REFERENCES `insurance_providers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_insurance_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_insurance_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_insurance`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `patient_insurance` WRITE;
/*!40000 ALTER TABLE `patient_insurance` DISABLE KEYS */;
INSERT INTO `patient_insurance` VALUES
(1,1,1,1,'POL-1-2026','MBR-000001',NULL,'Self','Aisha  Mohammed','1990-03-12','08030000001',NULL,'2026-02-04','2026-02-04','2027-08-04',500000.00,0.00,1,'Verified','2026-08-04 21:16:43',10,NULL,'Active','2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(2,2,2,3,'POL-2-2026','MBR-000002',NULL,'Self','Chidi  Eze','1985-06-22','08030000002',NULL,'2026-02-04','2026-02-04','2027-08-04',500000.00,0.00,1,'Verified','2026-08-04 21:16:43',10,NULL,'Active','2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(3,3,3,4,'POL-3-2026','MBR-000003',NULL,'Self','Fatima  Bello','1978-11-04','08030000003',NULL,'2026-02-04','2026-02-04','2027-08-04',500000.00,0.00,1,'Verified','2026-08-04 21:16:43',10,NULL,'Active','2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(4,4,4,5,'POL-4-2026','MBR-000004',NULL,'Self','John  Okafor','1995-09-18','08030000004',NULL,'2026-02-04','2026-02-04','2027-08-04',500000.00,0.00,1,'Verified','2026-08-04 21:16:43',10,NULL,'Active','2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(5,5,5,6,'POL-5-2026','MBR-000005',NULL,'Self','Grace  Adeyemi','2001-01-30','08030000005',NULL,'2026-02-04','2026-02-04','2027-08-04',500000.00,0.00,1,'Verified','2026-08-04 21:16:43',10,NULL,'Active','2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(6,6,1,1,'POL-6-2026','MBR-000006',NULL,'Self','Ibrahim  Sani','1968-08-14','08030000006',NULL,'2026-02-04','2026-02-04','2027-08-04',500000.00,0.00,1,'Verified','2026-08-04 21:16:43',10,NULL,'Active','2026-08-04 21:16:43','2026-08-04 21:16:43',NULL);
/*!40000 ALTER TABLE `patient_insurance` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `patients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patientcode` varchar(200) NOT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `firstname` varchar(200) NOT NULL,
  `middlename` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(7) NOT NULL,
  `bloodgroup` varchar(7) DEFAULT NULL,
  `genotype` varchar(2) DEFAULT NULL,
  `marital` varchar(20) DEFAULT NULL,
  `religion` varchar(200) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `phonemain` varchar(13) NOT NULL,
  `phonealt` varchar(13) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `address` text NOT NULL,
  `city` varchar(250) NOT NULL,
  `state` varchar(250) NOT NULL,
  `country` varchar(250) NOT NULL,
  `postalcode` varchar(12) DEFAULT NULL,
  `nokname` varchar(250) NOT NULL,
  `relationship` varchar(250) NOT NULL,
  `nokphone` varchar(250) NOT NULL,
  `nokaddress` text NOT NULL,
  `hereabout` varchar(250) DEFAULT NULL,
  `notes` varchar(250) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patientcode` (`patientcode`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` VALUES
(1,'FUTH-LF-2026-000001',1,'Aisha','','Mohammed','1990-03-12','female','A+','AA','married','Christianity','Trader','08030000001',NULL,NULL,'Lafia, Nasarawa State','Lafia','Nasarawa','Nigeria',NULL,'Family Member','Spouse','08030000099','Lafia, Nasarawa State',NULL,NULL,1,'2026-07-30 11:00:00','2026-07-30 11:00:00',NULL),
(2,'FUTH-LF-2026-000002',1,'Chidi','','Eze','1985-06-22','male','O+','AS','married','Christianity','Trader','08030000002',NULL,NULL,'Akwanga, Nasarawa State','Akwanga','Nasarawa','Nigeria',NULL,'Family Member','Spouse','08030000099','Akwanga, Nasarawa State',NULL,NULL,1,'2026-07-29 11:00:00','2026-07-29 11:00:00',NULL),
(3,'FUTH-LF-2026-000003',1,'Fatima','','Bello','1978-11-04','female','B+','AA','married','Christianity','Trader','08030000003',NULL,NULL,'Keffi, Nasarawa State','Keffi','Nasarawa','Nigeria',NULL,'Family Member','Spouse','08030000099','Keffi, Nasarawa State',NULL,NULL,1,'2026-07-28 11:00:00','2026-07-28 11:00:00',NULL),
(4,'FUTH-LF-2026-000004',1,'John','','Okafor','1995-09-18','male','AB+','AA','single','Christianity','Trader','08030000004',NULL,NULL,'Lafia, Nasarawa State','Lafia','Nasarawa','Nigeria',NULL,'Family Member','Spouse','08030000099','Lafia, Nasarawa State',NULL,NULL,1,'2026-07-27 11:00:00','2026-07-27 11:00:00',NULL),
(5,'FUTH-LF-2026-000005',1,'Grace','','Adeyemi','2001-01-30','female','O-','SS','single','Christianity','Trader','08030000005',NULL,NULL,'Doma, Nasarawa State','Doma','Nasarawa','Nigeria',NULL,'Family Member','Spouse','08030000099','Doma, Nasarawa State',NULL,NULL,1,'2026-07-26 11:00:00','2026-07-26 11:00:00',NULL),
(6,'FUTH-LF-2026-000006',1,'Ibrahim','','Sani','1968-08-14','male','A-','AS','married','Christianity','Trader','08030000006',NULL,NULL,'Lafia, Nasarawa State','Lafia','Nasarawa','Nigeria',NULL,'Family Member','Spouse','08030000099','Lafia, Nasarawa State',NULL,NULL,1,'2026-07-25 11:00:00','2026-07-25 11:00:00',NULL);
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payment_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `invoice_id` bigint(20) unsigned DEFAULT NULL,
  `insurance_claim_id` bigint(20) unsigned DEFAULT NULL,
  `payer_type` enum('Patient','Insurance','NHIA','Corporate','Government','Other') NOT NULL DEFAULT 'Patient',
  `payer_name` varchar(191) DEFAULT NULL,
  `payment_method` enum('Cash','Card','Bank Transfer','Cheque','Mobile Money','POS','Insurance','Online') NOT NULL DEFAULT 'Cash',
  `amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'NGN',
  `reference_number` varchar(191) DEFAULT NULL,
  `bank_name` varchar(191) DEFAULT NULL,
  `cheque_number` varchar(191) DEFAULT NULL,
  `card_last4` varchar(4) DEFAULT NULL,
  `payment_date` datetime NOT NULL,
  `received_by` bigint(20) unsigned NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Pending','Completed','Reversed','Bounced') NOT NULL DEFAULT 'Completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_payment_number_unique` (`payment_number`),
  KEY `payments_insurance_claim_id_foreign` (`insurance_claim_id`),
  KEY `payments_received_by_foreign` (`received_by`),
  KEY `payments_hospital_id_status_index` (`hospital_id`,`status`),
  KEY `payments_patient_id_status_index` (`patient_id`,`status`),
  KEY `payments_invoice_id_index` (`invoice_id`),
  KEY `payments_payment_number_index` (`payment_number`),
  CONSTRAINT `payments_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_insurance_claim_id_foreign` FOREIGN KEY (`insurance_claim_id`) REFERENCES `insurance_claims` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=775 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES
(1,'view_patient','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(2,'view_any_patient','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(3,'create_patient','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(4,'update_patient','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(5,'delete_patient','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(6,'restore_patient','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(7,'force_delete_patient','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(8,'view_encounter','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(9,'view_any_encounter','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(10,'create_encounter','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(11,'update_encounter','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(12,'delete_encounter','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(13,'restore_encounter','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(14,'force_delete_encounter','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(15,'view_appointment','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(16,'view_any_appointment','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(17,'create_appointment','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(18,'update_appointment','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(19,'delete_appointment','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(20,'restore_appointment','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(21,'force_delete_appointment','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(22,'view_clinicalNote','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(23,'view_any_clinicalNote','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(24,'create_clinicalNote','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(25,'update_clinicalNote','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(26,'delete_clinicalNote','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(27,'restore_clinicalNote','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(28,'force_delete_clinicalNote','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(29,'view_vitals','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(30,'view_any_vitals','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(31,'create_vitals','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(32,'update_vitals','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(33,'delete_vitals','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(34,'restore_vitals','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(35,'force_delete_vitals','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(36,'view_allergy','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(37,'view_any_allergy','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(38,'create_allergy','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(39,'update_allergy','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(40,'delete_allergy','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(41,'restore_allergy','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(42,'force_delete_allergy','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(43,'view_diagnosis','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(44,'view_any_diagnosis','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(45,'create_diagnosis','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(46,'update_diagnosis','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(47,'delete_diagnosis','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(48,'restore_diagnosis','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(49,'force_delete_diagnosis','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(50,'view_labPanel','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(51,'view_any_labPanel','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(52,'create_labPanel','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(53,'update_labPanel','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(54,'delete_labPanel','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(55,'restore_labPanel','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(56,'force_delete_labPanel','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(57,'view_labTest','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(58,'view_any_labTest','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(59,'create_labTest','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(60,'update_labTest','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(61,'delete_labTest','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(62,'restore_labTest','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(63,'force_delete_labTest','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(64,'view_labOrder','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(65,'view_any_labOrder','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(66,'create_labOrder','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(67,'update_labOrder','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(68,'delete_labOrder','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(69,'restore_labOrder','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(70,'force_delete_labOrder','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(71,'view_radiologyModality','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(72,'view_any_radiologyModality','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(73,'create_radiologyModality','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(74,'update_radiologyModality','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(75,'delete_radiologyModality','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(76,'restore_radiologyModality','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(77,'force_delete_radiologyModality','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(78,'view_radiologyOrder','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(79,'view_any_radiologyOrder','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(80,'create_radiologyOrder','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(81,'update_radiologyOrder','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(82,'delete_radiologyOrder','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(83,'restore_radiologyOrder','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(84,'force_delete_radiologyOrder','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(85,'view_drugCategory','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(86,'view_any_drugCategory','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(87,'create_drugCategory','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(88,'update_drugCategory','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(89,'delete_drugCategory','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(90,'restore_drugCategory','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(91,'force_delete_drugCategory','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(92,'view_drug','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(93,'view_any_drug','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(94,'create_drug','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(95,'update_drug','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(96,'delete_drug','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(97,'restore_drug','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(98,'force_delete_drug','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(99,'view_drugBatch','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(100,'view_any_drugBatch','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(101,'create_drugBatch','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(102,'update_drugBatch','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(103,'delete_drugBatch','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(104,'restore_drugBatch','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(105,'force_delete_drugBatch','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(106,'view_drugInteraction','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(107,'view_any_drugInteraction','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(108,'create_drugInteraction','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(109,'update_drugInteraction','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(110,'delete_drugInteraction','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(111,'restore_drugInteraction','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(112,'force_delete_drugInteraction','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(113,'view_supplier','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(114,'view_any_supplier','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(115,'create_supplier','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(116,'update_supplier','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(117,'delete_supplier','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(118,'restore_supplier','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(119,'force_delete_supplier','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(120,'view_purchase','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(121,'view_any_purchase','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(122,'create_purchase','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(123,'update_purchase','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(124,'delete_purchase','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(125,'restore_purchase','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(126,'force_delete_purchase','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(127,'view_receipt','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(128,'view_any_receipt','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(129,'create_receipt','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(130,'update_receipt','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(131,'delete_receipt','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(132,'restore_receipt','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(133,'force_delete_receipt','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(134,'view_dispensingPoint','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(135,'view_any_dispensingPoint','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(136,'create_dispensingPoint','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(137,'update_dispensingPoint','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(138,'delete_dispensingPoint','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(139,'restore_dispensingPoint','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(140,'force_delete_dispensingPoint','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(141,'view_prescription','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(142,'view_any_prescription','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(143,'create_prescription','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(144,'update_prescription','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(145,'delete_prescription','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(146,'restore_prescription','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(147,'force_delete_prescription','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(148,'view_dispensingLog','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(149,'view_any_dispensingLog','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(150,'create_dispensingLog','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(151,'update_dispensingLog','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(152,'delete_dispensingLog','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(153,'restore_dispensingLog','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(154,'force_delete_dispensingLog','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(155,'view_stockMovement','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(156,'view_any_stockMovement','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(157,'create_stockMovement','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(158,'update_stockMovement','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(159,'delete_stockMovement','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(160,'restore_stockMovement','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(161,'force_delete_stockMovement','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(162,'view_stockReturn','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(163,'view_any_stockReturn','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(164,'create_stockReturn','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(165,'update_stockReturn','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(166,'delete_stockReturn','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(167,'restore_stockReturn','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(168,'force_delete_stockReturn','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(169,'view_hospital','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(170,'view_any_hospital','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(171,'create_hospital','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(172,'update_hospital','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(173,'delete_hospital','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(174,'restore_hospital','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(175,'force_delete_hospital','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(176,'view_clinic','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(177,'view_any_clinic','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(178,'create_clinic','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(179,'update_clinic','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(180,'delete_clinic','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(181,'restore_clinic','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(182,'force_delete_clinic','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(183,'view_department','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(184,'view_any_department','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(185,'create_department','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(186,'update_department','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(187,'delete_department','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(188,'restore_department','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(189,'force_delete_department','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(190,'view_staff','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(191,'view_any_staff','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(192,'create_staff','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(193,'update_staff','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(194,'delete_staff','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(195,'restore_staff','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(196,'force_delete_staff','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(197,'view_user','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(198,'view_any_user','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(199,'create_user','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(200,'update_user','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(201,'delete_user','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(202,'restore_user','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(203,'force_delete_user','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(204,'view_role','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(205,'view_any_role','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(206,'create_role','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(207,'update_role','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(208,'delete_role','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(209,'restore_role','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(210,'force_delete_role','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(211,'view_dutyRoster','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(212,'view_any_dutyRoster','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(213,'create_dutyRoster','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(214,'update_dutyRoster','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(215,'restore_dutyRoster','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(216,'restore_any_dutyRoster','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(217,'replicate_dutyRoster','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(218,'reorder_dutyRoster','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(219,'delete_dutyRoster','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(220,'delete_any_dutyRoster','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(221,'force_delete_dutyRoster','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(222,'force_delete_any_dutyRoster','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(223,'view_dutyPosting','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(224,'view_any_dutyPosting','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(225,'create_dutyPosting','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(226,'update_dutyPosting','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(227,'restore_dutyPosting','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(228,'restore_any_dutyPosting','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(229,'replicate_dutyPosting','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(230,'reorder_dutyPosting','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(231,'delete_dutyPosting','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(232,'delete_any_dutyPosting','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(233,'force_delete_dutyPosting','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(234,'force_delete_any_dutyPosting','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(235,'view_insuranceProvider','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(236,'view_any_insuranceProvider','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(237,'create_insuranceProvider','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(238,'update_insuranceProvider','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(239,'restore_insuranceProvider','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(240,'restore_any_insuranceProvider','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(241,'replicate_insuranceProvider','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(242,'reorder_insuranceProvider','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(243,'delete_insuranceProvider','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(244,'delete_any_insuranceProvider','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(245,'force_delete_insuranceProvider','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(246,'force_delete_any_insuranceProvider','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(247,'view_insurancePlan','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(248,'view_any_insurancePlan','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(249,'create_insurancePlan','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(250,'update_insurancePlan','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(251,'restore_insurancePlan','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(252,'restore_any_insurancePlan','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(253,'replicate_insurancePlan','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(254,'reorder_insurancePlan','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(255,'delete_insurancePlan','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(256,'delete_any_insurancePlan','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(257,'force_delete_insurancePlan','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(258,'force_delete_any_insurancePlan','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(259,'view_patientInsurance','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(260,'view_any_patientInsurance','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(261,'create_patientInsurance','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(262,'update_patientInsurance','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(263,'restore_patientInsurance','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(264,'restore_any_patientInsurance','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(265,'replicate_patientInsurance','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(266,'reorder_patientInsurance','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(267,'delete_patientInsurance','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(268,'delete_any_patientInsurance','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(269,'force_delete_patientInsurance','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(270,'force_delete_any_patientInsurance','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(271,'view_insuranceClaim','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(272,'view_any_insuranceClaim','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(273,'create_insuranceClaim','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(274,'update_insuranceClaim','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(275,'restore_insuranceClaim','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(276,'restore_any_insuranceClaim','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(277,'replicate_insuranceClaim','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(278,'reorder_insuranceClaim','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(279,'delete_insuranceClaim','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(280,'delete_any_insuranceClaim','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(281,'force_delete_insuranceClaim','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(282,'force_delete_any_insuranceClaim','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(283,'view_preAuthorization','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(284,'view_any_preAuthorization','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(285,'create_preAuthorization','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(286,'update_preAuthorization','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(287,'restore_preAuthorization','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(288,'restore_any_preAuthorization','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(289,'replicate_preAuthorization','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(290,'reorder_preAuthorization','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(291,'delete_preAuthorization','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(292,'delete_any_preAuthorization','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(293,'force_delete_preAuthorization','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(294,'force_delete_any_preAuthorization','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(295,'view_service','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(296,'view_any_service','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(297,'create_service','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(298,'update_service','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(299,'restore_service','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(300,'restore_any_service','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(301,'replicate_service','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(302,'reorder_service','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(303,'delete_service','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(304,'delete_any_service','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(305,'force_delete_service','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(306,'force_delete_any_service','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(307,'view_invoice','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(308,'view_any_invoice','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(309,'create_invoice','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(310,'update_invoice','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(311,'restore_invoice','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(312,'restore_any_invoice','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(313,'replicate_invoice','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(314,'reorder_invoice','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(315,'delete_invoice','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(316,'delete_any_invoice','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(317,'force_delete_invoice','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(318,'force_delete_any_invoice','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(319,'view_payment','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(320,'view_any_payment','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(321,'create_payment','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(322,'update_payment','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(323,'restore_payment','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(324,'restore_any_payment','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(325,'replicate_payment','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(326,'reorder_payment','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(327,'delete_payment','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(328,'delete_any_payment','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(329,'force_delete_payment','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(330,'force_delete_any_payment','web','2026-08-04 21:16:24','2026-08-04 21:16:24'),
(331,'view_ward','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(332,'view_any_ward','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(333,'create_ward','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(334,'update_ward','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(335,'restore_ward','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(336,'restore_any_ward','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(337,'replicate_ward','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(338,'reorder_ward','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(339,'delete_ward','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(340,'delete_any_ward','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(341,'force_delete_ward','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(342,'force_delete_any_ward','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(343,'view_bed','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(344,'view_any_bed','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(345,'create_bed','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(346,'update_bed','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(347,'restore_bed','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(348,'restore_any_bed','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(349,'replicate_bed','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(350,'reorder_bed','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(351,'delete_bed','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(352,'delete_any_bed','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(353,'force_delete_bed','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(354,'force_delete_any_bed','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(355,'view_admission','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(356,'view_any_admission','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(357,'create_admission','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(358,'update_admission','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(359,'restore_admission','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(360,'restore_any_admission','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(361,'replicate_admission','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(362,'reorder_admission','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(363,'delete_admission','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(364,'delete_any_admission','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(365,'force_delete_admission','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(366,'force_delete_any_admission','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(367,'view_bedTransfer','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(368,'view_any_bedTransfer','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(369,'create_bedTransfer','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(370,'update_bedTransfer','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(371,'restore_bedTransfer','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(372,'restore_any_bedTransfer','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(373,'replicate_bedTransfer','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(374,'reorder_bedTransfer','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(375,'delete_bedTransfer','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(376,'delete_any_bedTransfer','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(377,'force_delete_bedTransfer','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(378,'force_delete_any_bedTransfer','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(379,'view_dischargeSummary','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(380,'view_any_dischargeSummary','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(381,'create_dischargeSummary','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(382,'update_dischargeSummary','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(383,'restore_dischargeSummary','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(384,'restore_any_dischargeSummary','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(385,'replicate_dischargeSummary','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(386,'reorder_dischargeSummary','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(387,'delete_dischargeSummary','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(388,'delete_any_dischargeSummary','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(389,'force_delete_dischargeSummary','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(390,'force_delete_any_dischargeSummary','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(391,'view_chartOfAccount','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(392,'view_any_chartOfAccount','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(393,'create_chartOfAccount','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(394,'update_chartOfAccount','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(395,'restore_chartOfAccount','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(396,'restore_any_chartOfAccount','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(397,'replicate_chartOfAccount','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(398,'reorder_chartOfAccount','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(399,'delete_chartOfAccount','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(400,'delete_any_chartOfAccount','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(401,'force_delete_chartOfAccount','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(402,'force_delete_any_chartOfAccount','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(403,'view_journalEntry','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(404,'view_any_journalEntry','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(405,'create_journalEntry','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(406,'update_journalEntry','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(407,'restore_journalEntry','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(408,'restore_any_journalEntry','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(409,'replicate_journalEntry','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(410,'reorder_journalEntry','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(411,'delete_journalEntry','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(412,'delete_any_journalEntry','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(413,'force_delete_journalEntry','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(414,'force_delete_any_journalEntry','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(415,'view_journalLine','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(416,'view_any_journalLine','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(417,'create_journalLine','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(418,'update_journalLine','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(419,'restore_journalLine','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(420,'restore_any_journalLine','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(421,'replicate_journalLine','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(422,'reorder_journalLine','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(423,'delete_journalLine','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(424,'delete_any_journalLine','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(425,'force_delete_journalLine','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(426,'force_delete_any_journalLine','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(427,'view_budget','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(428,'view_any_budget','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(429,'create_budget','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(430,'update_budget','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(431,'restore_budget','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(432,'restore_any_budget','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(433,'replicate_budget','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(434,'reorder_budget','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(435,'delete_budget','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(436,'delete_any_budget','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(437,'force_delete_budget','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(438,'force_delete_any_budget','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(439,'view_expenseClaim','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(440,'view_any_expenseClaim','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(441,'create_expenseClaim','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(442,'update_expenseClaim','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(443,'restore_expenseClaim','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(444,'restore_any_expenseClaim','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(445,'replicate_expenseClaim','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(446,'reorder_expenseClaim','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(447,'delete_expenseClaim','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(448,'delete_any_expenseClaim','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(449,'force_delete_expenseClaim','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(450,'force_delete_any_expenseClaim','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(451,'view_pettyCashTransaction','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(452,'view_any_pettyCashTransaction','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(453,'create_pettyCashTransaction','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(454,'update_pettyCashTransaction','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(455,'restore_pettyCashTransaction','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(456,'restore_any_pettyCashTransaction','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(457,'replicate_pettyCashTransaction','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(458,'reorder_pettyCashTransaction','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(459,'delete_pettyCashTransaction','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(460,'delete_any_pettyCashTransaction','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(461,'force_delete_pettyCashTransaction','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(462,'force_delete_any_pettyCashTransaction','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(463,'view_appointmentType','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(464,'view_any_appointmentType','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(465,'create_appointmentType','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(466,'update_appointmentType','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(467,'restore_appointmentType','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(468,'restore_any_appointmentType','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(469,'replicate_appointmentType','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(470,'reorder_appointmentType','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(471,'delete_appointmentType','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(472,'delete_any_appointmentType','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(473,'force_delete_appointmentType','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(474,'force_delete_any_appointmentType','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(475,'view_clinicSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(476,'view_any_clinicSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(477,'create_clinicSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(478,'update_clinicSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(479,'restore_clinicSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(480,'restore_any_clinicSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(481,'replicate_clinicSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(482,'reorder_clinicSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(483,'delete_clinicSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(484,'delete_any_clinicSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(485,'force_delete_clinicSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(486,'force_delete_any_clinicSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(487,'view_providerSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(488,'view_any_providerSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(489,'create_providerSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(490,'update_providerSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(491,'restore_providerSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(492,'restore_any_providerSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(493,'replicate_providerSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(494,'reorder_providerSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(495,'delete_providerSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(496,'delete_any_providerSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(497,'force_delete_providerSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(498,'force_delete_any_providerSchedule','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(499,'view_appointmentSlot','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(500,'view_any_appointmentSlot','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(501,'create_appointmentSlot','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(502,'update_appointmentSlot','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(503,'restore_appointmentSlot','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(504,'restore_any_appointmentSlot','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(505,'replicate_appointmentSlot','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(506,'reorder_appointmentSlot','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(507,'delete_appointmentSlot','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(508,'delete_any_appointmentSlot','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(509,'force_delete_appointmentSlot','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(510,'force_delete_any_appointmentSlot','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(511,'view_holiday','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(512,'view_any_holiday','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(513,'create_holiday','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(514,'update_holiday','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(515,'restore_holiday','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(516,'restore_any_holiday','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(517,'replicate_holiday','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(518,'reorder_holiday','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(519,'delete_holiday','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(520,'delete_any_holiday','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(521,'force_delete_holiday','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(522,'force_delete_any_holiday','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(523,'view_leaveRequest','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(524,'view_any_leaveRequest','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(525,'create_leaveRequest','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(526,'update_leaveRequest','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(527,'restore_leaveRequest','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(528,'restore_any_leaveRequest','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(529,'replicate_leaveRequest','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(530,'reorder_leaveRequest','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(531,'delete_leaveRequest','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(532,'delete_any_leaveRequest','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(533,'force_delete_leaveRequest','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(534,'force_delete_any_leaveRequest','web','2026-08-04 21:51:01','2026-08-04 21:51:01'),
(535,'view_storeCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(536,'view_any_storeCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(537,'create_storeCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(538,'update_storeCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(539,'restore_storeCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(540,'restore_any_storeCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(541,'replicate_storeCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(542,'reorder_storeCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(543,'delete_storeCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(544,'delete_any_storeCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(545,'force_delete_storeCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(546,'force_delete_any_storeCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(547,'view_storeLocation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(548,'view_any_storeLocation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(549,'create_storeLocation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(550,'update_storeLocation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(551,'restore_storeLocation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(552,'restore_any_storeLocation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(553,'replicate_storeLocation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(554,'reorder_storeLocation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(555,'delete_storeLocation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(556,'delete_any_storeLocation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(557,'force_delete_storeLocation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(558,'force_delete_any_storeLocation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(559,'view_storeItem','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(560,'view_any_storeItem','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(561,'create_storeItem','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(562,'update_storeItem','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(563,'restore_storeItem','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(564,'restore_any_storeItem','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(565,'replicate_storeItem','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(566,'reorder_storeItem','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(567,'delete_storeItem','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(568,'delete_any_storeItem','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(569,'force_delete_storeItem','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(570,'force_delete_any_storeItem','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(571,'view_storeStock','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(572,'view_any_storeStock','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(573,'create_storeStock','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(574,'update_storeStock','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(575,'restore_storeStock','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(576,'restore_any_storeStock','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(577,'replicate_storeStock','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(578,'reorder_storeStock','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(579,'delete_storeStock','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(580,'delete_any_storeStock','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(581,'force_delete_storeStock','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(582,'force_delete_any_storeStock','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(583,'view_storeMovement','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(584,'view_any_storeMovement','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(585,'create_storeMovement','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(586,'update_storeMovement','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(587,'restore_storeMovement','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(588,'restore_any_storeMovement','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(589,'replicate_storeMovement','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(590,'reorder_storeMovement','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(591,'delete_storeMovement','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(592,'delete_any_storeMovement','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(593,'force_delete_storeMovement','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(594,'force_delete_any_storeMovement','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(595,'view_storeRequisition','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(596,'view_any_storeRequisition','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(597,'create_storeRequisition','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(598,'update_storeRequisition','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(599,'restore_storeRequisition','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(600,'restore_any_storeRequisition','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(601,'replicate_storeRequisition','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(602,'reorder_storeRequisition','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(603,'delete_storeRequisition','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(604,'delete_any_storeRequisition','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(605,'force_delete_storeRequisition','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(606,'force_delete_any_storeRequisition','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(607,'view_stockTake','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(608,'view_any_stockTake','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(609,'create_stockTake','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(610,'update_stockTake','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(611,'restore_stockTake','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(612,'restore_any_stockTake','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(613,'replicate_stockTake','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(614,'reorder_stockTake','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(615,'delete_stockTake','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(616,'delete_any_stockTake','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(617,'force_delete_stockTake','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(618,'force_delete_any_stockTake','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(619,'view_bloodDonor','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(620,'view_any_bloodDonor','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(621,'create_bloodDonor','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(622,'update_bloodDonor','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(623,'restore_bloodDonor','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(624,'restore_any_bloodDonor','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(625,'replicate_bloodDonor','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(626,'reorder_bloodDonor','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(627,'delete_bloodDonor','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(628,'delete_any_bloodDonor','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(629,'force_delete_bloodDonor','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(630,'force_delete_any_bloodDonor','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(631,'view_bloodDonation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(632,'view_any_bloodDonation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(633,'create_bloodDonation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(634,'update_bloodDonation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(635,'restore_bloodDonation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(636,'restore_any_bloodDonation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(637,'replicate_bloodDonation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(638,'reorder_bloodDonation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(639,'delete_bloodDonation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(640,'delete_any_bloodDonation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(641,'force_delete_bloodDonation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(642,'force_delete_any_bloodDonation','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(643,'view_bloodUnit','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(644,'view_any_bloodUnit','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(645,'create_bloodUnit','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(646,'update_bloodUnit','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(647,'restore_bloodUnit','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(648,'restore_any_bloodUnit','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(649,'replicate_bloodUnit','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(650,'reorder_bloodUnit','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(651,'delete_bloodUnit','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(652,'delete_any_bloodUnit','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(653,'force_delete_bloodUnit','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(654,'force_delete_any_bloodUnit','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(655,'view_bloodRequest','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(656,'view_any_bloodRequest','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(657,'create_bloodRequest','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(658,'update_bloodRequest','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(659,'restore_bloodRequest','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(660,'restore_any_bloodRequest','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(661,'replicate_bloodRequest','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(662,'reorder_bloodRequest','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(663,'delete_bloodRequest','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(664,'delete_any_bloodRequest','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(665,'force_delete_bloodRequest','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(666,'force_delete_any_bloodRequest','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(667,'view_bloodCrossmatch','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(668,'view_any_bloodCrossmatch','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(669,'create_bloodCrossmatch','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(670,'update_bloodCrossmatch','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(671,'restore_bloodCrossmatch','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(672,'restore_any_bloodCrossmatch','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(673,'replicate_bloodCrossmatch','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(674,'reorder_bloodCrossmatch','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(675,'delete_bloodCrossmatch','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(676,'delete_any_bloodCrossmatch','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(677,'force_delete_bloodCrossmatch','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(678,'force_delete_any_bloodCrossmatch','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(679,'view_bloodTransfusion','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(680,'view_any_bloodTransfusion','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(681,'create_bloodTransfusion','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(682,'update_bloodTransfusion','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(683,'restore_bloodTransfusion','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(684,'restore_any_bloodTransfusion','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(685,'replicate_bloodTransfusion','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(686,'reorder_bloodTransfusion','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(687,'delete_bloodTransfusion','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(688,'delete_any_bloodTransfusion','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(689,'force_delete_bloodTransfusion','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(690,'force_delete_any_bloodTransfusion','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(691,'view_serviceCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(692,'view_any_serviceCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(693,'create_serviceCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(694,'update_serviceCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(695,'restore_serviceCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(696,'restore_any_serviceCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(697,'replicate_serviceCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(698,'reorder_serviceCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(699,'delete_serviceCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(700,'delete_any_serviceCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(701,'force_delete_serviceCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(702,'force_delete_any_serviceCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(703,'view_procedureCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(704,'view_any_procedureCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(705,'create_procedureCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(706,'update_procedureCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(707,'restore_procedureCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(708,'restore_any_procedureCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(709,'replicate_procedureCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(710,'reorder_procedureCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(711,'delete_procedureCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(712,'delete_any_procedureCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(713,'force_delete_procedureCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(714,'force_delete_any_procedureCategory','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(715,'view_procedure','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(716,'view_any_procedure','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(717,'create_procedure','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(718,'update_procedure','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(719,'restore_procedure','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(720,'restore_any_procedure','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(721,'replicate_procedure','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(722,'reorder_procedure','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(723,'delete_procedure','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(724,'delete_any_procedure','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(725,'force_delete_procedure','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(726,'force_delete_any_procedure','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(727,'view_procedureBooking','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(728,'view_any_procedureBooking','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(729,'create_procedureBooking','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(730,'update_procedureBooking','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(731,'restore_procedureBooking','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(732,'restore_any_procedureBooking','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(733,'replicate_procedureBooking','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(734,'reorder_procedureBooking','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(735,'delete_procedureBooking','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(736,'delete_any_procedureBooking','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(737,'force_delete_procedureBooking','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(738,'force_delete_any_procedureBooking','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(739,'view_procedureSession','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(740,'view_any_procedureSession','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(741,'create_procedureSession','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(742,'update_procedureSession','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(743,'restore_procedureSession','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(744,'restore_any_procedureSession','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(745,'replicate_procedureSession','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(746,'reorder_procedureSession','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(747,'delete_procedureSession','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(748,'delete_any_procedureSession','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(749,'force_delete_procedureSession','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(750,'force_delete_any_procedureSession','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(751,'view_goodsReceivedNote','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(752,'view_any_goodsReceivedNote','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(753,'create_goodsReceivedNote','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(754,'update_goodsReceivedNote','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(755,'restore_goodsReceivedNote','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(756,'restore_any_goodsReceivedNote','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(757,'replicate_goodsReceivedNote','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(758,'reorder_goodsReceivedNote','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(759,'delete_goodsReceivedNote','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(760,'delete_any_goodsReceivedNote','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(761,'force_delete_goodsReceivedNote','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(762,'force_delete_any_goodsReceivedNote','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(763,'view_drugStockLevel','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(764,'view_any_drugStockLevel','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(765,'create_drugStockLevel','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(766,'update_drugStockLevel','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(767,'restore_drugStockLevel','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(768,'restore_any_drugStockLevel','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(769,'replicate_drugStockLevel','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(770,'reorder_drugStockLevel','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(771,'delete_drugStockLevel','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(772,'delete_any_drugStockLevel','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(773,'force_delete_drugStockLevel','web','2026-08-04 22:25:25','2026-08-04 22:25:25'),
(774,'force_delete_any_drugStockLevel','web','2026-08-04 22:25:25','2026-08-04 22:25:25');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `petty_cash_transactions`
--

DROP TABLE IF EXISTS `petty_cash_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `petty_cash_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `voucher_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `dispensing_point_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` enum('Top Up','Expense','Reimbursement','Adjustment') NOT NULL DEFAULT 'Expense',
  `transaction_date` datetime NOT NULL,
  `amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `balance_after` decimal(18,2) NOT NULL DEFAULT 0.00,
  `payee` varchar(191) DEFAULT NULL,
  `category` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `receipt_path` varchar(191) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Posted') NOT NULL DEFAULT 'Pending',
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `petty_cash_transactions_voucher_number_unique` (`voucher_number`),
  KEY `petty_cash_transactions_dispensing_point_id_foreign` (`dispensing_point_id`),
  KEY `petty_cash_transactions_user_id_foreign` (`user_id`),
  KEY `petty_cash_transactions_approved_by_foreign` (`approved_by`),
  KEY `petty_cash_transactions_hospital_id_transaction_date_index` (`hospital_id`,`transaction_date`),
  KEY `petty_cash_transactions_voucher_number_index` (`voucher_number`),
  CONSTRAINT `petty_cash_transactions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `petty_cash_transactions_dispensing_point_id_foreign` FOREIGN KEY (`dispensing_point_id`) REFERENCES `dispensing_points` (`id`) ON DELETE SET NULL,
  CONSTRAINT `petty_cash_transactions_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `petty_cash_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `petty_cash_transactions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `petty_cash_transactions` WRITE;
/*!40000 ALTER TABLE `petty_cash_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `petty_cash_transactions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pre_authorizations`
--

DROP TABLE IF EXISTS `pre_authorizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pre_authorizations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `authorization_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `patient_insurance_id` bigint(20) unsigned NOT NULL,
  `insurance_provider_id` bigint(20) unsigned NOT NULL,
  `encounter_id` bigint(20) unsigned DEFAULT NULL,
  `requested_by` bigint(20) unsigned NOT NULL,
  `service_type` enum('Consultation','Pharmacy','Laboratory','Radiology','Procedure','Surgery','Inpatient Admission','Maternity','Specialist Referral','Other') NOT NULL DEFAULT 'Consultation',
  `clinical_justification` text NOT NULL,
  `diagnosis_icd10` varchar(191) DEFAULT NULL,
  `diagnosis_description` text DEFAULT NULL,
  `estimated_cost` decimal(14,2) NOT NULL DEFAULT 0.00,
  `approved_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `requested_date` date NOT NULL,
  `required_by_date` date DEFAULT NULL,
  `authorized_date` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `priority` enum('Routine','Urgent','Emergency') NOT NULL DEFAULT 'Routine',
  `status` enum('Pending','Approved','Partially Approved','Rejected','Expired','Utilized','Cancelled') NOT NULL DEFAULT 'Pending',
  `provider_response` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `processed_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pre_authorizations_authorization_number_unique` (`authorization_number`),
  KEY `pre_authorizations_patient_insurance_id_foreign` (`patient_insurance_id`),
  KEY `pre_authorizations_encounter_id_foreign` (`encounter_id`),
  KEY `pre_authorizations_requested_by_foreign` (`requested_by`),
  KEY `pre_authorizations_processed_by_foreign` (`processed_by`),
  KEY `pre_authorizations_hospital_id_status_index` (`hospital_id`,`status`),
  KEY `pre_authorizations_patient_id_status_index` (`patient_id`,`status`),
  KEY `pre_authorizations_insurance_provider_id_status_index` (`insurance_provider_id`,`status`),
  KEY `pre_authorizations_authorization_number_index` (`authorization_number`),
  CONSTRAINT `pre_authorizations_encounter_id_foreign` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pre_authorizations_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pre_authorizations_insurance_provider_id_foreign` FOREIGN KEY (`insurance_provider_id`) REFERENCES `insurance_providers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pre_authorizations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pre_authorizations_patient_insurance_id_foreign` FOREIGN KEY (`patient_insurance_id`) REFERENCES `patient_insurance` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pre_authorizations_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pre_authorizations_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pre_authorizations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pre_authorizations` WRITE;
/*!40000 ALTER TABLE `pre_authorizations` DISABLE KEYS */;
/*!40000 ALTER TABLE `pre_authorizations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `prescription_items`
--

DROP TABLE IF EXISTS `prescription_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `prescription_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prescription_id` bigint(20) unsigned NOT NULL,
  `drug_id` bigint(20) unsigned NOT NULL,
  `drug_batch_id` bigint(20) unsigned DEFAULT NULL,
  `drug_name` varchar(255) NOT NULL,
  `dosage_form` varchar(255) DEFAULT NULL,
  `strength` varchar(255) DEFAULT NULL,
  `dose` varchar(255) NOT NULL,
  `route` varchar(255) NOT NULL DEFAULT 'PO',
  `frequency` varchar(255) NOT NULL,
  `duration` varchar(255) NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 0,
  `quantity_dispensed` int(10) unsigned NOT NULL DEFAULT 0,
  `instructions` text DEFAULT NULL,
  `status` enum('Pending','Partially Dispensed','Dispensed','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prescription_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `prescription_items` WRITE;
/*!40000 ALTER TABLE `prescription_items` DISABLE KEYS */;
INSERT INTO `prescription_items` VALUES
(1,1,9,9,'Artemether/Lumefantrine','Tablet','20/120 mg','4 tablets','PO','BD','3 days',24,0,'Take 4 tablets stat, then 4 tablets at 8, 24, 48 hours','Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(2,1,1,1,'Paracetamol Tab','Tablet','500 mg','1 tablet','PO','TDS','3 days',9,0,'For fever as needed','Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(3,2,9,9,'Artemether/Lumefantrine','Tablet','20/120 mg','4 tablets','PO','BD','3 days',24,0,'Take 4 tablets stat, then 4 tablets at 8, 24, 48 hours','Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(4,2,1,1,'Paracetamol Tab','Tablet','500 mg','1 tablet','PO','TDS','3 days',9,0,'For fever as needed','Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(5,3,9,9,'Artemether/Lumefantrine','Tablet','20/120 mg','4 tablets','PO','BD','3 days',24,0,'Take 4 tablets stat, then 4 tablets at 8, 24, 48 hours','Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(6,3,1,1,'Paracetamol Tab','Tablet','500 mg','1 tablet','PO','TDS','3 days',9,0,'For fever as needed','Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(7,4,9,9,'Artemether/Lumefantrine','Tablet','20/120 mg','4 tablets','PO','BD','3 days',24,0,'Take 4 tablets stat, then 4 tablets at 8, 24, 48 hours','Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(8,4,1,1,'Paracetamol Tab','Tablet','500 mg','1 tablet','PO','TDS','3 days',9,0,'For fever as needed','Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(9,5,9,9,'Artemether/Lumefantrine','Tablet','20/120 mg','4 tablets','PO','BD','3 days',24,0,'Take 4 tablets stat, then 4 tablets at 8, 24, 48 hours','Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(10,5,1,1,'Paracetamol Tab','Tablet','500 mg','1 tablet','PO','TDS','3 days',9,0,'For fever as needed','Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(11,6,9,9,'Artemether/Lumefantrine','Tablet','20/120 mg','4 tablets','PO','BD','3 days',24,0,'Take 4 tablets stat, then 4 tablets at 8, 24, 48 hours','Pending','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(12,6,1,1,'Paracetamol Tab','Tablet','500 mg','1 tablet','PO','TDS','3 days',9,0,'For fever as needed','Pending','2026-07-31 11:00:00','2026-07-31 11:00:00');
/*!40000 ALTER TABLE `prescription_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `prescriptions`
--

DROP TABLE IF EXISTS `prescriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `prescriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `encounter_id` bigint(20) unsigned DEFAULT NULL,
  `prescriber_id` bigint(20) unsigned NOT NULL,
  `dispensing_point_id` bigint(20) unsigned DEFAULT NULL,
  `prescription_number` varchar(255) NOT NULL,
  `prescribed_at` datetime NOT NULL,
  `dispensed_at` datetime DEFAULT NULL,
  `dispensed_by` bigint(20) unsigned DEFAULT NULL,
  `status` enum('Pending','Partially Dispensed','Dispensed','Cancelled','Rejected') NOT NULL DEFAULT 'Pending',
  `priority` enum('Routine','Urgent','STAT') NOT NULL DEFAULT 'Routine',
  `is_inpatient` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prescription_number` (`prescription_number`),
  KEY `prescriptions_patient_id_prescribed_at_index` (`patient_id`,`prescribed_at`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prescriptions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `prescriptions` WRITE;
/*!40000 ALTER TABLE `prescriptions` DISABLE KEYS */;
INSERT INTO `prescriptions` VALUES
(1,1,1,1,2,1,'FUTH-LF-RX-2026-000001','2026-07-31 12:00:00',NULL,NULL,'Pending','Routine',0,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(2,1,2,2,2,1,'FUTH-LF-RX-2026-000002','2026-07-31 12:00:00',NULL,NULL,'Pending','Routine',0,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(3,1,3,3,2,1,'FUTH-LF-RX-2026-000003','2026-07-31 12:00:00',NULL,NULL,'Pending','Routine',0,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(4,1,4,4,2,1,'FUTH-LF-RX-2026-000004','2026-07-31 12:00:00',NULL,NULL,'Pending','Routine',0,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(5,1,5,5,2,1,'FUTH-LF-RX-2026-000005','2026-07-31 12:00:00',NULL,NULL,'Pending','Routine',0,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(6,1,6,6,2,1,'FUTH-LF-RX-2026-000006','2026-07-31 12:00:00',NULL,NULL,'Pending','Routine',0,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL);
/*!40000 ALTER TABLE `prescriptions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `procedure_bookings`
--

DROP TABLE IF EXISTS `procedure_bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `procedure_bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `encounter_id` bigint(20) unsigned DEFAULT NULL,
  `admission_id` bigint(20) unsigned DEFAULT NULL,
  `procedure_id` bigint(20) unsigned NOT NULL,
  `surgeon_id` bigint(20) unsigned DEFAULT NULL,
  `anaesthetist_id` bigint(20) unsigned DEFAULT NULL,
  `assistant_id` bigint(20) unsigned DEFAULT NULL,
  `scrub_nurse_id` bigint(20) unsigned DEFAULT NULL,
  `circulating_nurse_id` bigint(20) unsigned DEFAULT NULL,
  `theatre_id` bigint(20) unsigned DEFAULT NULL,
  `scheduled_start` datetime NOT NULL,
  `scheduled_end` datetime DEFAULT NULL,
  `priority` enum('Elective','Urgent','Emergency') NOT NULL DEFAULT 'Elective',
  `booking_status` enum('Scheduled','Confirmed','In Progress','Completed','Cancelled','Postponed','No Show') NOT NULL DEFAULT 'Scheduled',
  `pre_op_assessment_date` date DEFAULT NULL,
  `consent_signed` tinyint(1) NOT NULL DEFAULT 0,
  `consent_signed_at` datetime DEFAULT NULL,
  `consent_witnessed_by` bigint(20) unsigned DEFAULT NULL,
  `fasting_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `investigations_done` tinyint(1) NOT NULL DEFAULT 0,
  `anaesthesia_review_done` tinyint(1) NOT NULL DEFAULT 0,
  `blood_reserved` tinyint(1) NOT NULL DEFAULT 0,
  `pre_op_notes` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `procedure_bookings_booking_number_unique` (`booking_number`),
  KEY `procedure_bookings_hospital_id_foreign` (`hospital_id`),
  KEY `procedure_bookings_encounter_id_foreign` (`encounter_id`),
  KEY `procedure_bookings_admission_id_foreign` (`admission_id`),
  KEY `procedure_bookings_surgeon_id_foreign` (`surgeon_id`),
  KEY `procedure_bookings_anaesthetist_id_foreign` (`anaesthetist_id`),
  KEY `procedure_bookings_assistant_id_foreign` (`assistant_id`),
  KEY `procedure_bookings_scrub_nurse_id_foreign` (`scrub_nurse_id`),
  KEY `procedure_bookings_circulating_nurse_id_foreign` (`circulating_nurse_id`),
  KEY `procedure_bookings_theatre_id_foreign` (`theatre_id`),
  KEY `procedure_bookings_consent_witnessed_by_foreign` (`consent_witnessed_by`),
  KEY `procedure_bookings_created_by_foreign` (`created_by`),
  KEY `procedure_bookings_patient_id_scheduled_start_index` (`patient_id`,`scheduled_start`),
  KEY `procedure_bookings_procedure_id_booking_status_index` (`procedure_id`,`booking_status`),
  KEY `procedure_bookings_booking_number_index` (`booking_number`),
  CONSTRAINT `procedure_bookings_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `procedure_bookings_anaesthetist_id_foreign` FOREIGN KEY (`anaesthetist_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `procedure_bookings_assistant_id_foreign` FOREIGN KEY (`assistant_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `procedure_bookings_circulating_nurse_id_foreign` FOREIGN KEY (`circulating_nurse_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `procedure_bookings_consent_witnessed_by_foreign` FOREIGN KEY (`consent_witnessed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `procedure_bookings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `procedure_bookings_encounter_id_foreign` FOREIGN KEY (`encounter_id`) REFERENCES `encounters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `procedure_bookings_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `procedure_bookings_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `procedure_bookings_procedure_id_foreign` FOREIGN KEY (`procedure_id`) REFERENCES `procedures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `procedure_bookings_scrub_nurse_id_foreign` FOREIGN KEY (`scrub_nurse_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `procedure_bookings_surgeon_id_foreign` FOREIGN KEY (`surgeon_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `procedure_bookings_theatre_id_foreign` FOREIGN KEY (`theatre_id`) REFERENCES `store_locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procedure_bookings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `procedure_bookings` WRITE;
/*!40000 ALTER TABLE `procedure_bookings` DISABLE KEYS */;
INSERT INTO `procedure_bookings` VALUES
(1,'PBK-2026-000001',1,4,NULL,NULL,1,2,NULL,NULL,NULL,NULL,NULL,'2026-08-06 09:00:38','2026-08-06 10:30:38','Elective','Confirmed',NULL,1,'2026-08-03 22:24:38',1,1,1,1,0,'Patient prepared for elective appendectomy. Pre-op investigations within normal limits.',NULL,NULL,2,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL);
/*!40000 ALTER TABLE `procedure_bookings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `procedure_categories`
--

DROP TABLE IF EXISTS `procedure_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `procedure_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `specialty` enum('General Surgery','Orthopaedics','OBGYN','ENT','Ophthalmology','Dental','Cardiology','Urology','Neurosurgery','Plastic Surgery','Paediatric Surgery','Endoscopy','Other') NOT NULL DEFAULT 'General Surgery',
  `description` text DEFAULT NULL,
  `requires_consent` tinyint(1) NOT NULL DEFAULT 1,
  `requires_anaesthesia` tinyint(1) NOT NULL DEFAULT 1,
  `requires_pre_op_assessment` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `procedure_categories_code_unique` (`code`),
  KEY `procedure_categories_hospital_id_specialty_index` (`hospital_id`,`specialty`),
  CONSTRAINT `procedure_categories_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procedure_categories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `procedure_categories` WRITE;
/*!40000 ALTER TABLE `procedure_categories` DISABLE KEYS */;
INSERT INTO `procedure_categories` VALUES
(1,1,'Minor Surgical Procedures','MSP','General Surgery',NULL,1,1,1,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(2,1,'Major Surgical Procedures','MJR','General Surgery',NULL,1,1,1,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(3,1,'Obstetric Procedures','OBS','OBGYN',NULL,1,1,1,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(4,1,'Orthopaedic Procedures','ORT','Orthopaedics',NULL,1,1,1,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(5,1,'Endoscopic Procedures','END','Endoscopy',NULL,1,1,1,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL);
/*!40000 ALTER TABLE `procedure_categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `procedure_sessions`
--

DROP TABLE IF EXISTS `procedure_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `procedure_sessions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_number` varchar(191) NOT NULL,
  `procedure_booking_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `procedure_id` bigint(20) unsigned NOT NULL,
  `surgeon_id` bigint(20) unsigned DEFAULT NULL,
  `anaesthetist_id` bigint(20) unsigned DEFAULT NULL,
  `theatre_id` bigint(20) unsigned DEFAULT NULL,
  `actual_start` datetime NOT NULL,
  `actual_end` datetime DEFAULT NULL,
  `duration_minutes` int(11) DEFAULT NULL,
  `anaesthesia_used` enum('Local','Regional','Spinal','Epidural','General','Sedation','None') NOT NULL DEFAULT 'Local',
  `anaesthesia_notes` text DEFAULT NULL,
  `findings_intra_op` text DEFAULT NULL,
  `procedure_performed` text DEFAULT NULL,
  `steps_taken` text DEFAULT NULL,
  `complications_intra_op` text DEFAULT NULL,
  `blood_loss_ml` text DEFAULT NULL,
  `specimens_sent` text DEFAULT NULL,
  `implants_used` text DEFAULT NULL,
  `drains_placed` text DEFAULT NULL,
  `dressing_applied` text DEFAULT NULL,
  `post_op_instructions` text DEFAULT NULL,
  `outcome` enum('Successful','Complicated','Incomplete','Abandoned','Death') NOT NULL DEFAULT 'Successful',
  `notes` text DEFAULT NULL,
  `is_signed` tinyint(1) NOT NULL DEFAULT 0,
  `signed_at` datetime DEFAULT NULL,
  `signed_by` bigint(20) unsigned DEFAULT NULL,
  `status` enum('In Progress','Completed','Abandoned','Cancelled') NOT NULL DEFAULT 'In Progress',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `procedure_sessions_session_number_unique` (`session_number`),
  KEY `procedure_sessions_procedure_id_foreign` (`procedure_id`),
  KEY `procedure_sessions_surgeon_id_foreign` (`surgeon_id`),
  KEY `procedure_sessions_anaesthetist_id_foreign` (`anaesthetist_id`),
  KEY `procedure_sessions_theatre_id_foreign` (`theatre_id`),
  KEY `procedure_sessions_signed_by_foreign` (`signed_by`),
  KEY `procedure_sessions_procedure_booking_id_index` (`procedure_booking_id`),
  KEY `procedure_sessions_patient_id_actual_start_index` (`patient_id`,`actual_start`),
  KEY `procedure_sessions_session_number_index` (`session_number`),
  CONSTRAINT `procedure_sessions_anaesthetist_id_foreign` FOREIGN KEY (`anaesthetist_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `procedure_sessions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `procedure_sessions_procedure_booking_id_foreign` FOREIGN KEY (`procedure_booking_id`) REFERENCES `procedure_bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `procedure_sessions_procedure_id_foreign` FOREIGN KEY (`procedure_id`) REFERENCES `procedures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `procedure_sessions_signed_by_foreign` FOREIGN KEY (`signed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `procedure_sessions_surgeon_id_foreign` FOREIGN KEY (`surgeon_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `procedure_sessions_theatre_id_foreign` FOREIGN KEY (`theatre_id`) REFERENCES `store_locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procedure_sessions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `procedure_sessions` WRITE;
/*!40000 ALTER TABLE `procedure_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `procedure_sessions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `procedures`
--

DROP TABLE IF EXISTS `procedures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `procedures` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `procedure_category_id` bigint(20) unsigned DEFAULT NULL,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `icd10_pcs_code` varchar(191) DEFAULT NULL,
  `cpt_code` varchar(191) DEFAULT NULL,
  `type` enum('Minor','Major','Intermediate','Complex','Day Case') NOT NULL DEFAULT 'Minor',
  `site` enum('Theatre','Procedure Room','Ward Bedside','Consulting Room','Emergency Room','ICU','Other') NOT NULL DEFAULT 'Theatre',
  `anaesthesia_type` enum('Local','Regional','Spinal','Epidural','General','Sedation','None') NOT NULL DEFAULT 'Local',
  `estimated_duration_minutes` int(11) NOT NULL DEFAULT 60,
  `default_pre_op_days` int(11) NOT NULL DEFAULT 0,
  `default_post_op_days` int(11) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `indication` text DEFAULT NULL,
  `contraindication` text DEFAULT NULL,
  `pre_op_instructions` text DEFAULT NULL,
  `post_op_instructions` text DEFAULT NULL,
  `complications` text DEFAULT NULL,
  `standard_fee` decimal(14,2) NOT NULL DEFAULT 0.00,
  `nhia_rate` decimal(14,2) NOT NULL DEFAULT 0.00,
  `private_rate` decimal(14,2) NOT NULL DEFAULT 0.00,
  `surgeon_fee` decimal(14,2) NOT NULL DEFAULT 0.00,
  `anaesthetist_fee` decimal(14,2) NOT NULL DEFAULT 0.00,
  `theatre_fee` decimal(14,2) NOT NULL DEFAULT 0.00,
  `consumables_fee` decimal(14,2) NOT NULL DEFAULT 0.00,
  `requires_pre_authorization` tinyint(1) NOT NULL DEFAULT 0,
  `requires_blood_reservation` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `procedures_code_unique` (`code`),
  KEY `procedures_procedure_category_id_foreign` (`procedure_category_id`),
  KEY `procedures_service_id_foreign` (`service_id`),
  KEY `procedures_hospital_id_type_index` (`hospital_id`,`type`),
  KEY `procedures_code_index` (`code`),
  CONSTRAINT `procedures_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `procedures_procedure_category_id_foreign` FOREIGN KEY (`procedure_category_id`) REFERENCES `procedure_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `procedures_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procedures`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `procedures` WRITE;
/*!40000 ALTER TABLE `procedures` DISABLE KEYS */;
INSERT INTO `procedures` VALUES
(1,1,2,NULL,'Appendectomy','APP-001',NULL,NULL,'Major','Theatre','General',90,1,1,NULL,NULL,NULL,NULL,NULL,NULL,150000.00,80000.00,225000.00,50000.00,25000.00,50000.00,25000.00,0,0,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(2,1,2,NULL,'Hernia Repair','HRN-001',NULL,NULL,'Major','Theatre','Spinal',75,1,1,NULL,NULL,NULL,NULL,NULL,NULL,120000.00,60000.00,180000.00,40000.00,20000.00,40000.00,20000.00,0,0,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(3,1,3,NULL,'Caesarean Section','CS-001',NULL,NULL,'Major','Theatre','Spinal',60,1,1,NULL,NULL,NULL,NULL,NULL,NULL,180000.00,90000.00,270000.00,60000.00,30000.00,60000.00,30000.00,0,0,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(4,1,3,NULL,'Normal Delivery (Episiotomy)','NVD-001',NULL,NULL,'Minor','Theatre','Local',45,1,1,NULL,NULL,NULL,NULL,NULL,NULL,35000.00,20000.00,52500.00,15000.00,5000.00,10000.00,5000.00,0,0,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(5,1,1,NULL,'Incision & Drainage (Abscess)','IAD-001',NULL,NULL,'Minor','Procedure Room','Local',30,1,1,NULL,NULL,NULL,NULL,NULL,NULL,15000.00,8000.00,22500.00,7000.00,0.00,5000.00,3000.00,0,0,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(6,1,1,NULL,'Suture Removal','SUR-001',NULL,NULL,'Minor','Consulting Room','None',15,1,1,NULL,NULL,NULL,NULL,NULL,NULL,3000.00,1500.00,4500.00,1500.00,0.00,0.00,500.00,0,0,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(7,1,1,NULL,'Wound Debridement','WDB-001',NULL,NULL,'Minor','Procedure Room','Local',30,1,1,NULL,NULL,NULL,NULL,NULL,NULL,10000.00,5000.00,15000.00,5000.00,0.00,3000.00,2000.00,0,0,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(8,1,4,NULL,'Fracture Reduction (Closed)','FRC-001',NULL,NULL,'Intermediate','Theatre','General',60,1,1,NULL,NULL,NULL,NULL,NULL,NULL,80000.00,40000.00,120000.00,30000.00,15000.00,25000.00,10000.00,0,0,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(9,1,5,NULL,'Upper GI Endoscopy','UGE-001',NULL,NULL,'Intermediate','Procedure Room','Sedation',30,1,1,NULL,NULL,NULL,NULL,NULL,NULL,45000.00,25000.00,67500.00,20000.00,10000.00,10000.00,5000.00,0,0,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(10,1,5,NULL,'Colonoscopy','CLN-001',NULL,NULL,'Intermediate','Procedure Room','Sedation',45,1,1,NULL,NULL,NULL,NULL,NULL,NULL,60000.00,35000.00,90000.00,25000.00,12000.00,15000.00,8000.00,0,0,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL);
/*!40000 ALTER TABLE `procedures` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `provider_schedules`
--

DROP TABLE IF EXISTS `provider_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_schedules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `clinic_id` bigint(20) unsigned DEFAULT NULL,
  `weekday` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `break_start` time DEFAULT NULL,
  `break_end` time DEFAULT NULL,
  `slot_duration_minutes` int(11) NOT NULL DEFAULT 15,
  `max_patients_per_slot` int(11) NOT NULL DEFAULT 1,
  `effective_from` date DEFAULT NULL,
  `effective_until` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `provider_schedules_clinic_id_foreign` (`clinic_id`),
  KEY `provider_schedules_created_by_foreign` (`created_by`),
  KEY `provider_schedules_user_id_weekday_is_active_index` (`user_id`,`weekday`,`is_active`),
  KEY `provider_schedules_hospital_id_index` (`hospital_id`),
  CONSTRAINT `provider_schedules_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE SET NULL,
  CONSTRAINT `provider_schedules_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `provider_schedules_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `provider_schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_schedules`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `provider_schedules` WRITE;
/*!40000 ALTER TABLE `provider_schedules` DISABLE KEYS */;
INSERT INTO `provider_schedules` VALUES
(1,1,2,1,'Monday','09:00:00','16:00:00','13:00:00','14:00:00',20,1,'2026-01-01','2026-12-31',1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(2,1,2,1,'Tuesday','09:00:00','16:00:00','13:00:00','14:00:00',20,1,'2026-01-01','2026-12-31',1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(3,1,2,1,'Wednesday','09:00:00','16:00:00','13:00:00','14:00:00',20,1,'2026-01-01','2026-12-31',1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(4,1,2,1,'Thursday','09:00:00','16:00:00','13:00:00','14:00:00',20,1,'2026-01-01','2026-12-31',1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL),
(5,1,2,1,'Friday','09:00:00','16:00:00','13:00:00','14:00:00',20,1,'2026-01-01','2026-12-31',1,NULL,1,'2026-08-04 21:50:22','2026-08-04 21:50:22',NULL);
/*!40000 ALTER TABLE `provider_schedules` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `purchase_items`
--

DROP TABLE IF EXISTS `purchase_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint(20) unsigned NOT NULL,
  `drug_id` bigint(20) unsigned NOT NULL,
  `drug_name` varchar(255) NOT NULL,
  `batch_number` varchar(255) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `quantity_ordered` decimal(12,2) NOT NULL DEFAULT 0.00,
  `quantity_received` decimal(12,2) NOT NULL DEFAULT 0.00,
  `unit_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `discount_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(14,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `purchase_items` WRITE;
/*!40000 ALTER TABLE `purchase_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchases` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `received_by` bigint(20) unsigned NOT NULL,
  `purchase_number` varchar(255) NOT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `lpo_number` varchar(255) DEFAULT NULL,
  `order_date` date NOT NULL,
  `received_date` date DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `subtotal` decimal(14,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `amount_paid` decimal(14,2) NOT NULL DEFAULT 0.00,
  `status` enum('Draft','Ordered','Partially Received','Received','Cancelled') NOT NULL DEFAULT 'Draft',
  `payment_status` enum('Unpaid','Partially Paid','Paid') NOT NULL DEFAULT 'Unpaid',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_number` (`purchase_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchases`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `radiology_modalities`
--

DROP TABLE IF EXISTS `radiology_modalities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radiology_modalities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `turnaround_hours` int(10) unsigned NOT NULL DEFAULT 24,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radiology_modalities`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `radiology_modalities` WRITE;
/*!40000 ALTER TABLE `radiology_modalities` DISABLE KEYS */;
INSERT INTO `radiology_modalities` VALUES
(1,1,'Plain X-Ray','XRAY','Conventional radiography',1500.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(2,1,'Ultrasound','US','Real-time ultrasonography',3000.00,4,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(3,1,'Computed Tomography','CT','Cross-sectional CT imaging',18000.00,24,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(4,1,'Magnetic Resonance','MR','MRI imaging',25000.00,48,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(5,1,'Fluoroscopy','FL','Fluoroscopic examinations',4000.00,8,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(6,1,'Mammography','MG','Breast imaging',6000.00,24,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(7,1,'Doppler Ultrasound','DUS','Vascular Doppler studies',5000.00,8,1,'2026-07-31 11:00:00','2026-07-31 11:00:00'),
(8,1,'Interventional Radiology','IVR','Image-guided procedures',10000.00,12,1,'2026-07-31 11:00:00','2026-07-31 11:00:00');
/*!40000 ALTER TABLE `radiology_modalities` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `radiology_orders`
--

DROP TABLE IF EXISTS `radiology_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radiology_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `encounter_id` bigint(20) unsigned DEFAULT NULL,
  `modality_id` bigint(20) unsigned NOT NULL,
  `requested_by` bigint(20) unsigned NOT NULL,
  `received_by` bigint(20) unsigned DEFAULT NULL,
  `order_number` varchar(255) NOT NULL,
  `ordered_at` datetime NOT NULL,
  `performed_at` datetime DEFAULT NULL,
  `resulted_at` datetime DEFAULT NULL,
  `examination` varchar(255) NOT NULL,
  `body_part` varchar(255) DEFAULT NULL,
  `clinical_indication` text DEFAULT NULL,
  `request_notes` text DEFAULT NULL,
  `priority` enum('Routine','Urgent','STAT') NOT NULL DEFAULT 'Routine',
  `status` enum('Pending','Scheduled','In Progress','Completed','Verified','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `radiology_orders_patient_id_ordered_at_index` (`patient_id`,`ordered_at`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radiology_orders`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `radiology_orders` WRITE;
/*!40000 ALTER TABLE `radiology_orders` DISABLE KEYS */;
INSERT INTO `radiology_orders` VALUES
(1,1,1,1,1,2,NULL,'FUTH-LF-RAD-2026-000001','2026-07-31 12:00:00',NULL,NULL,'Chest X-Ray PA view','Chest','Suspected pneumonia / TB screening',NULL,'Routine','Pending','2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(2,1,3,3,1,2,NULL,'FUTH-LF-RAD-2026-000002','2026-07-31 12:00:00',NULL,NULL,'Chest X-Ray PA view','Chest','Suspected pneumonia / TB screening',NULL,'Routine','Pending','2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(3,1,5,5,1,2,NULL,'FUTH-LF-RAD-2026-000003','2026-07-31 12:00:00',NULL,NULL,'Chest X-Ray PA view','Chest','Suspected pneumonia / TB screening',NULL,'Routine','Pending','2026-07-31 11:00:00','2026-07-31 11:00:00',NULL);
/*!40000 ALTER TABLE `radiology_orders` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `radiology_results`
--

DROP TABLE IF EXISTS `radiology_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radiology_results` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `radiology_order_id` bigint(20) unsigned NOT NULL,
  `reported_by` bigint(20) unsigned DEFAULT NULL,
  `verified_by` bigint(20) unsigned DEFAULT NULL,
  `findings` text NOT NULL,
  `impression` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `reported_at` datetime DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radiology_results`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `radiology_results` WRITE;
/*!40000 ALTER TABLE `radiology_results` DISABLE KEYS */;
/*!40000 ALTER TABLE `radiology_results` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `receipt_items`
--

DROP TABLE IF EXISTS `receipt_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `receipt_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `receipt_id` bigint(20) unsigned NOT NULL,
  `drug_id` bigint(20) unsigned NOT NULL,
  `drug_batch_id` bigint(20) unsigned DEFAULT NULL,
  `batch_number` varchar(255) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `quantity_received` decimal(12,2) NOT NULL DEFAULT 0.00,
  `quantity_rejected` decimal(12,2) NOT NULL DEFAULT 0.00,
  `unit_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receipt_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `receipt_items` WRITE;
/*!40000 ALTER TABLE `receipt_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `receipt_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `receipts`
--

DROP TABLE IF EXISTS `receipts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `receipts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `purchase_id` bigint(20) unsigned DEFAULT NULL,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `received_by` bigint(20) unsigned NOT NULL,
  `receipt_number` varchar(255) NOT NULL,
  `received_date` date NOT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `delivery_note_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Pending','Verified','Rejected') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `receipt_number` (`receipt_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receipts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `receipts` WRITE;
/*!40000 ALTER TABLE `receipts` DISABLE KEYS */;
/*!40000 ALTER TABLE `receipts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES
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
(210,1),
(211,1),
(212,1),
(213,1),
(214,1),
(215,1),
(216,1),
(217,1),
(218,1),
(219,1),
(220,1),
(221,1),
(222,1),
(223,1),
(224,1),
(225,1),
(226,1),
(227,1),
(228,1),
(229,1),
(230,1),
(231,1),
(232,1),
(233,1),
(234,1),
(235,1),
(236,1),
(237,1),
(238,1),
(239,1),
(240,1),
(241,1),
(242,1),
(243,1),
(244,1),
(245,1),
(246,1),
(247,1),
(248,1),
(249,1),
(250,1),
(251,1),
(252,1),
(253,1),
(254,1),
(255,1),
(256,1),
(257,1),
(258,1),
(259,1),
(260,1),
(261,1),
(262,1),
(263,1),
(264,1),
(265,1),
(266,1),
(267,1),
(268,1),
(269,1),
(270,1),
(271,1),
(272,1),
(273,1),
(274,1),
(275,1),
(276,1),
(277,1),
(278,1),
(279,1),
(280,1),
(281,1),
(282,1),
(283,1),
(284,1),
(285,1),
(286,1),
(287,1),
(288,1),
(289,1),
(290,1),
(291,1),
(292,1),
(293,1),
(294,1),
(295,1),
(296,1),
(297,1),
(298,1),
(299,1),
(300,1),
(301,1),
(302,1),
(303,1),
(304,1),
(305,1),
(306,1),
(307,1),
(308,1),
(309,1),
(310,1),
(311,1),
(312,1),
(313,1),
(314,1),
(315,1),
(316,1),
(317,1),
(318,1),
(319,1),
(320,1),
(321,1),
(322,1),
(323,1),
(324,1),
(325,1),
(326,1),
(327,1),
(328,1),
(329,1),
(330,1),
(331,1),
(332,1),
(333,1),
(334,1),
(335,1),
(336,1),
(337,1),
(338,1),
(339,1),
(340,1),
(341,1),
(342,1),
(343,1),
(344,1),
(345,1),
(346,1),
(347,1),
(348,1),
(349,1),
(350,1),
(351,1),
(352,1),
(353,1),
(354,1),
(355,1),
(356,1),
(357,1),
(358,1),
(359,1),
(360,1),
(361,1),
(362,1),
(363,1),
(364,1),
(365,1),
(366,1),
(367,1),
(368,1),
(369,1),
(370,1),
(371,1),
(372,1),
(373,1),
(374,1),
(375,1),
(376,1),
(377,1),
(378,1),
(379,1),
(380,1),
(381,1),
(382,1),
(383,1),
(384,1),
(385,1),
(386,1),
(387,1),
(388,1),
(389,1),
(390,1),
(391,1),
(392,1),
(393,1),
(394,1),
(395,1),
(396,1),
(397,1),
(398,1),
(399,1),
(400,1),
(401,1),
(402,1),
(403,1),
(404,1),
(405,1),
(406,1),
(407,1),
(408,1),
(409,1),
(410,1),
(411,1),
(412,1),
(413,1),
(414,1),
(415,1),
(416,1),
(417,1),
(418,1),
(419,1),
(420,1),
(421,1),
(422,1),
(423,1),
(424,1),
(425,1),
(426,1),
(427,1),
(428,1),
(429,1),
(430,1),
(431,1),
(432,1),
(433,1),
(434,1),
(435,1),
(436,1),
(437,1),
(438,1),
(439,1),
(440,1),
(441,1),
(442,1),
(443,1),
(444,1),
(445,1),
(446,1),
(447,1),
(448,1),
(449,1),
(450,1),
(451,1),
(452,1),
(453,1),
(454,1),
(455,1),
(456,1),
(457,1),
(458,1),
(459,1),
(460,1),
(461,1),
(462,1),
(463,1),
(464,1),
(465,1),
(466,1),
(467,1),
(468,1),
(469,1),
(470,1),
(471,1),
(472,1),
(473,1),
(474,1),
(475,1),
(476,1),
(477,1),
(478,1),
(479,1),
(480,1),
(481,1),
(482,1),
(483,1),
(484,1),
(485,1),
(486,1),
(487,1),
(488,1),
(489,1),
(490,1),
(491,1),
(492,1),
(493,1),
(494,1),
(495,1),
(496,1),
(497,1),
(498,1),
(499,1),
(500,1),
(501,1),
(502,1),
(503,1),
(504,1),
(505,1),
(506,1),
(507,1),
(508,1),
(509,1),
(510,1),
(511,1),
(512,1),
(513,1),
(514,1),
(515,1),
(516,1),
(517,1),
(518,1),
(519,1),
(520,1),
(521,1),
(522,1),
(523,1),
(524,1),
(525,1),
(526,1),
(527,1),
(528,1),
(529,1),
(530,1),
(531,1),
(532,1),
(533,1),
(534,1),
(535,1),
(536,1),
(537,1),
(538,1),
(539,1),
(540,1),
(541,1),
(542,1),
(543,1),
(544,1),
(545,1),
(546,1),
(547,1),
(548,1),
(549,1),
(550,1),
(551,1),
(552,1),
(553,1),
(554,1),
(555,1),
(556,1),
(557,1),
(558,1),
(559,1),
(560,1),
(561,1),
(562,1),
(563,1),
(564,1),
(565,1),
(566,1),
(567,1),
(568,1),
(569,1),
(570,1),
(571,1),
(572,1),
(573,1),
(574,1),
(575,1),
(576,1),
(577,1),
(578,1),
(579,1),
(580,1),
(581,1),
(582,1),
(583,1),
(584,1),
(585,1),
(586,1),
(587,1),
(588,1),
(589,1),
(590,1),
(591,1),
(592,1),
(593,1),
(594,1),
(595,1),
(596,1),
(597,1),
(598,1),
(599,1),
(600,1),
(601,1),
(602,1),
(603,1),
(604,1),
(605,1),
(606,1),
(607,1),
(608,1),
(609,1),
(610,1),
(611,1),
(612,1),
(613,1),
(614,1),
(615,1),
(616,1),
(617,1),
(618,1),
(619,1),
(620,1),
(621,1),
(622,1),
(623,1),
(624,1),
(625,1),
(626,1),
(627,1),
(628,1),
(629,1),
(630,1),
(631,1),
(632,1),
(633,1),
(634,1),
(635,1),
(636,1),
(637,1),
(638,1),
(639,1),
(640,1),
(641,1),
(642,1),
(643,1),
(644,1),
(645,1),
(646,1),
(647,1),
(648,1),
(649,1),
(650,1),
(651,1),
(652,1),
(653,1),
(654,1),
(655,1),
(656,1),
(657,1),
(658,1),
(659,1),
(660,1),
(661,1),
(662,1),
(663,1),
(664,1),
(665,1),
(666,1),
(667,1),
(668,1),
(669,1),
(670,1),
(671,1),
(672,1),
(673,1),
(674,1),
(675,1),
(676,1),
(677,1),
(678,1),
(679,1),
(680,1),
(681,1),
(682,1),
(683,1),
(684,1),
(685,1),
(686,1),
(687,1),
(688,1),
(689,1),
(690,1),
(691,1),
(692,1),
(693,1),
(694,1),
(695,1),
(696,1),
(697,1),
(698,1),
(699,1),
(700,1),
(701,1),
(702,1),
(703,1),
(704,1),
(705,1),
(706,1),
(707,1),
(708,1),
(709,1),
(710,1),
(711,1),
(712,1),
(713,1),
(714,1),
(715,1),
(716,1),
(717,1),
(718,1),
(719,1),
(720,1),
(721,1),
(722,1),
(723,1),
(724,1),
(725,1),
(726,1),
(727,1),
(728,1),
(729,1),
(730,1),
(731,1),
(732,1),
(733,1),
(734,1),
(735,1),
(736,1),
(737,1),
(738,1),
(739,1),
(740,1),
(741,1),
(742,1),
(743,1),
(744,1),
(745,1),
(746,1),
(747,1),
(748,1),
(749,1),
(750,1),
(751,1),
(752,1),
(753,1),
(754,1),
(755,1),
(756,1),
(757,1),
(758,1),
(759,1),
(760,1),
(761,1),
(762,1),
(763,1),
(764,1),
(765,1),
(766,1),
(767,1),
(768,1),
(769,1),
(770,1),
(771,1),
(772,1),
(773,1),
(774,1),
(1,2),
(2,2),
(3,2),
(4,2),
(8,2),
(9,2),
(10,2),
(11,2),
(15,2),
(16,2),
(17,2),
(18,2),
(22,2),
(23,2),
(24,2),
(25,2),
(29,2),
(30,2),
(31,2),
(32,2),
(36,2),
(37,2),
(38,2),
(39,2),
(43,2),
(44,2),
(45,2),
(46,2),
(50,2),
(51,2),
(52,2),
(53,2),
(57,2),
(58,2),
(59,2),
(60,2),
(64,2),
(65,2),
(66,2),
(67,2),
(71,2),
(72,2),
(73,2),
(74,2),
(78,2),
(79,2),
(80,2),
(81,2),
(85,2),
(86,2),
(87,2),
(88,2),
(92,2),
(93,2),
(94,2),
(95,2),
(99,2),
(100,2),
(101,2),
(102,2),
(141,2),
(142,2),
(143,2),
(144,2),
(169,2),
(170,2),
(171,2),
(172,2),
(176,2),
(177,2),
(178,2),
(179,2),
(183,2),
(184,2),
(185,2),
(186,2),
(190,2),
(191,2),
(192,2),
(193,2),
(197,2),
(198,2),
(199,2),
(200,2),
(201,2),
(211,2),
(212,2),
(213,2),
(214,2),
(223,2),
(224,2),
(225,2),
(226,2),
(235,2),
(236,2),
(237,2),
(238,2),
(247,2),
(248,2),
(249,2),
(250,2),
(259,2),
(260,2),
(261,2),
(262,2),
(271,2),
(272,2),
(273,2),
(274,2),
(283,2),
(284,2),
(285,2),
(286,2),
(295,2),
(296,2),
(297,2),
(298,2),
(307,2),
(308,2),
(309,2),
(310,2),
(319,2),
(320,2),
(321,2),
(322,2),
(331,2),
(332,2),
(333,2),
(334,2),
(343,2),
(344,2),
(345,2),
(346,2),
(355,2),
(356,2),
(357,2),
(358,2),
(367,2),
(368,2),
(369,2),
(370,2),
(379,2),
(380,2),
(381,2),
(382,2),
(391,2),
(392,2),
(403,2),
(404,2),
(427,2),
(428,2),
(429,2),
(430,2),
(439,2),
(440,2),
(441,2),
(442,2),
(463,2),
(464,2),
(465,2),
(466,2),
(475,2),
(476,2),
(477,2),
(478,2),
(487,2),
(488,2),
(489,2),
(490,2),
(499,2),
(500,2),
(501,2),
(502,2),
(511,2),
(512,2),
(513,2),
(514,2),
(523,2),
(524,2),
(525,2),
(526,2),
(535,2),
(536,2),
(537,2),
(538,2),
(547,2),
(548,2),
(549,2),
(550,2),
(559,2),
(560,2),
(561,2),
(562,2),
(571,2),
(572,2),
(573,2),
(574,2),
(583,2),
(584,2),
(585,2),
(595,2),
(596,2),
(597,2),
(598,2),
(607,2),
(608,2),
(609,2),
(610,2),
(619,2),
(620,2),
(621,2),
(622,2),
(631,2),
(632,2),
(633,2),
(634,2),
(643,2),
(644,2),
(645,2),
(646,2),
(655,2),
(656,2),
(657,2),
(658,2),
(667,2),
(668,2),
(669,2),
(670,2),
(679,2),
(680,2),
(681,2),
(682,2),
(691,2),
(692,2),
(693,2),
(694,2),
(703,2),
(704,2),
(705,2),
(706,2),
(715,2),
(716,2),
(717,2),
(718,2),
(727,2),
(728,2),
(729,2),
(730,2),
(739,2),
(740,2),
(741,2),
(742,2),
(751,2),
(752,2),
(753,2),
(754,2),
(763,2),
(764,2),
(765,2),
(766,2),
(1,3),
(2,3),
(3,3),
(4,3),
(8,3),
(9,3),
(10,3),
(11,3),
(15,3),
(16,3),
(17,3),
(18,3),
(22,3),
(23,3),
(24,3),
(25,3),
(29,3),
(30,3),
(31,3),
(32,3),
(36,3),
(37,3),
(38,3),
(39,3),
(43,3),
(44,3),
(45,3),
(46,3),
(50,3),
(51,3),
(57,3),
(58,3),
(64,3),
(65,3),
(66,3),
(67,3),
(71,3),
(72,3),
(78,3),
(79,3),
(80,3),
(81,3),
(92,3),
(93,3),
(141,3),
(142,3),
(143,3),
(144,3),
(223,3),
(224,3),
(259,3),
(260,3),
(271,3),
(272,3),
(273,3),
(274,3),
(283,3),
(284,3),
(285,3),
(286,3),
(295,3),
(296,3),
(307,3),
(308,3),
(331,3),
(332,3),
(343,3),
(344,3),
(355,3),
(356,3),
(357,3),
(358,3),
(367,3),
(368,3),
(369,3),
(379,3),
(380,3),
(381,3),
(382,3),
(463,3),
(464,3),
(475,3),
(476,3),
(487,3),
(488,3),
(499,3),
(500,3),
(502,3),
(523,3),
(524,3),
(525,3),
(559,3),
(560,3),
(571,3),
(572,3),
(595,3),
(596,3),
(597,3),
(619,3),
(620,3),
(631,3),
(632,3),
(643,3),
(644,3),
(655,3),
(656,3),
(657,3),
(658,3),
(667,3),
(668,3),
(669,3),
(679,3),
(680,3),
(681,3),
(691,3),
(692,3),
(703,3),
(704,3),
(715,3),
(716,3),
(727,3),
(728,3),
(729,3),
(730,3),
(739,3),
(740,3),
(741,3),
(742,3),
(1,4),
(2,4),
(8,4),
(9,4),
(11,4),
(15,4),
(16,4),
(22,4),
(23,4),
(24,4),
(25,4),
(29,4),
(30,4),
(31,4),
(32,4),
(36,4),
(37,4),
(43,4),
(44,4),
(141,4),
(142,4),
(223,4),
(224,4),
(331,4),
(332,4),
(343,4),
(344,4),
(346,4),
(355,4),
(356,4),
(358,4),
(367,4),
(368,4),
(379,4),
(380,4),
(499,4),
(500,4),
(523,4),
(524,4),
(525,4),
(559,4),
(560,4),
(571,4),
(572,4),
(595,4),
(596,4),
(597,4),
(619,4),
(620,4),
(643,4),
(644,4),
(655,4),
(656,4),
(679,4),
(680,4),
(682,4),
(727,4),
(728,4),
(730,4),
(739,4),
(740,4),
(742,4),
(1,5),
(2,5),
(85,5),
(86,5),
(87,5),
(88,5),
(92,5),
(93,5),
(94,5),
(95,5),
(99,5),
(100,5),
(101,5),
(102,5),
(113,5),
(114,5),
(134,5),
(135,5),
(136,5),
(137,5),
(141,5),
(142,5),
(143,5),
(144,5),
(155,5),
(156,5),
(157,5),
(223,5),
(224,5),
(499,5),
(500,5),
(523,5),
(524,5),
(525,5),
(559,5),
(560,5),
(571,5),
(572,5),
(573,5),
(574,5),
(583,5),
(584,5),
(585,5),
(595,5),
(596,5),
(751,5),
(752,5),
(753,5),
(754,5),
(763,5),
(764,5),
(765,5),
(766,5),
(1,6),
(2,6),
(8,6),
(9,6),
(50,6),
(51,6),
(57,6),
(58,6),
(64,6),
(65,6),
(66,6),
(67,6),
(223,6),
(224,6),
(559,6),
(560,6),
(571,6),
(572,6),
(595,6),
(596,6),
(597,6),
(619,6),
(620,6),
(621,6),
(622,6),
(631,6),
(632,6),
(633,6),
(634,6),
(643,6),
(644,6),
(645,6),
(646,6),
(655,6),
(656,6),
(667,6),
(668,6),
(669,6),
(679,6),
(680,6),
(1,7),
(2,7),
(8,7),
(9,7),
(71,7),
(72,7),
(78,7),
(79,7),
(80,7),
(81,7),
(223,7),
(224,7),
(1,8),
(2,8),
(3,8),
(4,8),
(8,8),
(9,8),
(10,8),
(15,8),
(16,8),
(17,8),
(18,8),
(176,8),
(177,8),
(183,8),
(184,8),
(190,8),
(191,8),
(223,8),
(224,8),
(331,8),
(332,8),
(343,8),
(344,8),
(355,8),
(356,8),
(379,8),
(380,8),
(463,8),
(464,8),
(475,8),
(476,8),
(487,8),
(488,8),
(499,8),
(500,8),
(511,8),
(512,8),
(523,8),
(524,8),
(559,8),
(560,8),
(619,8),
(620,8),
(655,8),
(656,8),
(679,8),
(680,8),
(691,8),
(692,8),
(727,8),
(728,8),
(739,8),
(740,8),
(1,9),
(2,9),
(223,9),
(224,9),
(259,9),
(260,9),
(271,9),
(272,9),
(273,9),
(274,9),
(295,9),
(296,9),
(307,9),
(308,9),
(309,9),
(310,9),
(319,9),
(320,9),
(321,9),
(322,9),
(355,9),
(356,9),
(391,9),
(392,9),
(393,9),
(394,9),
(403,9),
(404,9),
(405,9),
(406,9),
(415,9),
(416,9),
(417,9),
(418,9),
(427,9),
(428,9),
(429,9),
(430,9),
(439,9),
(440,9),
(441,9),
(442,9),
(451,9),
(452,9),
(453,9),
(454,9),
(523,9),
(524,9),
(559,9),
(560,9),
(583,9),
(584,9),
(691,9),
(692,9),
(693,9),
(694,9),
(703,9),
(704,9),
(715,9),
(716,9),
(727,9),
(728,9),
(751,9),
(752,9),
(763,9),
(764,9),
(1,10),
(2,10),
(8,10),
(9,10),
(223,10),
(224,10),
(235,10),
(236,10),
(247,10),
(248,10),
(259,10),
(260,10),
(261,10),
(262,10),
(271,10),
(272,10),
(273,10),
(274,10),
(283,10),
(284,10),
(285,10),
(286,10),
(307,10),
(308,10),
(355,10),
(356,10),
(379,10),
(380,10),
(499,10),
(500,10),
(655,10),
(656,10),
(679,10),
(680,10),
(691,10),
(692,10),
(715,10),
(716,10),
(727,10),
(728,10);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'Super Admin','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(2,'Medical Administrator','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(3,'Doctor','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(4,'Nurse','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(5,'Pharmacist','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(6,'Laboratory Scientist','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(7,'Radiographer','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(8,'Medical Records Officer','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(9,'Accountant','web','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(10,'Insurance Officer','web','2026-08-04 21:16:25','2026-08-04 21:16:25');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `service_categories`
--

DROP TABLE IF EXISTS `service_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `type` enum('Consultation','Procedure','Laboratory','Radiology','Pharmacy','Accommodation','Consumable','Equipment','Emergency','Other') NOT NULL DEFAULT 'Consultation',
  `color` varchar(7) NOT NULL DEFAULT '#0f5132',
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_categories_code_unique` (`code`),
  KEY `service_categories_parent_id_foreign` (`parent_id`),
  KEY `service_categories_hospital_id_type_index` (`hospital_id`,`type`),
  CONSTRAINT `service_categories_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `service_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_categories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `service_categories` WRITE;
/*!40000 ALTER TABLE `service_categories` DISABLE KEYS */;
INSERT INTO `service_categories` VALUES
(1,1,NULL,'General Consultations','CON-GEN','Consultation','#0f5132',NULL,1,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(2,1,NULL,'Specialist Consultations','CON-SPC','Consultation','#198754',NULL,1,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(3,1,NULL,'Emergency Consultations','CON-EMR','Emergency','#dc3545',NULL,1,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(4,1,NULL,'Laboratory Tests','LAB-GEN','Laboratory','#0dcaf0',NULL,1,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(5,1,NULL,'Radiology Services','RAD-GEN','Radiology','#6610f2',NULL,1,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(6,1,NULL,'Pharmacy Items','PHM-GEN','Pharmacy','#fd7e14',NULL,1,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(7,1,NULL,'Inpatient Accommodation','Ward-GEN','Accommodation','#6c757d',NULL,1,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(8,1,NULL,'Minor Procedures','PRC-MIN','Procedure','#ffc107',NULL,1,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(9,1,NULL,'Major Procedures','PRC-MAJ','Procedure','#fd7e14',NULL,1,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(10,1,NULL,'Maternity Services','MAT-GEN','Procedure','#e83e8c',NULL,1,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL);
/*!40000 ALTER TABLE `service_categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `category` enum('Consultation','Procedure','Laboratory','Radiology','Pharmacy','Accommodation','Consumable','Equipment','Emergency','Other') NOT NULL DEFAULT 'Consultation',
  `service_category_id` bigint(20) unsigned DEFAULT NULL,
  `sub_category` enum('General','Specialist','Surgery','Dental','Optical','Mental Health','Maternity','Physiotherapy') NOT NULL DEFAULT 'General',
  `nhia_code` varchar(191) DEFAULT NULL,
  `standard_price` decimal(14,2) NOT NULL DEFAULT 0.00,
  `nhia_rate` decimal(14,2) NOT NULL DEFAULT 0.00,
  `private_rate` decimal(14,2) NOT NULL DEFAULT 0.00,
  `cost_price` decimal(14,2) NOT NULL DEFAULT 0.00,
  `unit_of_measure` varchar(191) DEFAULT NULL,
  `is_taxable` tinyint(1) NOT NULL DEFAULT 0,
  `tax_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `requires_pre_authorization` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_code_unique` (`code`),
  KEY `services_hospital_id_category_status_index` (`hospital_id`,`category`,`status`),
  KEY `services_code_index` (`code`),
  KEY `services_service_category_id_foreign` (`service_category_id`),
  CONSTRAINT `services_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `services_service_category_id_foreign` FOREIGN KEY (`service_category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES
(1,1,'General Consultation','CONS-GEN','Consultation',1,'General',NULL,3000.00,1500.00,5000.00,0.00,NULL,0,0.00,NULL,0,'Active','2026-08-04 21:16:43','2026-08-04 22:24:59',NULL),
(2,1,'Specialist Consultation','CONS-SPC','Consultation',2,'General',NULL,8000.00,3000.00,12000.00,0.00,NULL,0,0.00,NULL,0,'Active','2026-08-04 21:16:43','2026-08-04 22:24:59',NULL),
(3,1,'Full Blood Count','LAB-FBC','Laboratory',4,'General',NULL,2500.00,1500.00,4000.00,0.00,NULL,0,0.00,NULL,0,'Active','2026-08-04 21:16:43','2026-08-04 22:24:59',NULL),
(4,1,'Malaria Parasite Test','LAB-MPS','Laboratory',4,'General',NULL,1500.00,800.00,2500.00,0.00,NULL,0,0.00,NULL,0,'Active','2026-08-04 21:16:43','2026-08-04 22:24:59',NULL),
(5,1,'Chest X-Ray','RAD-CXR','Radiology',5,'General',NULL,8000.00,5000.00,12000.00,0.00,NULL,0,0.00,NULL,0,'Active','2026-08-04 21:16:43','2026-08-04 22:24:59',NULL),
(6,1,'Pelvic Ultrasound','RAD-US-PV','Radiology',5,'General',NULL,10000.00,6000.00,15000.00,0.00,NULL,0,0.00,NULL,0,'Active','2026-08-04 21:16:43','2026-08-04 22:24:59',NULL),
(7,1,'Ward Admission (per day)','WARD-GEN','Accommodation',NULL,'General',NULL,15000.00,8000.00,25000.00,0.00,NULL,0,0.00,NULL,0,'Active','2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(8,1,'Normal Delivery','MAT-NVD','Procedure',NULL,'General',NULL,35000.00,20000.00,60000.00,0.00,NULL,0,0.00,NULL,0,'Active','2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(9,1,'Caesarean Section','MAT-CS','Procedure',NULL,'General',NULL,150000.00,80000.00,250000.00,0.00,NULL,0,0.00,NULL,0,'Active','2026-08-04 21:16:43','2026-08-04 21:16:43',NULL),
(10,1,'IV Cannulation','PROC-IV','Procedure',NULL,'General',NULL,1000.00,500.00,2000.00,0.00,NULL,0,0.00,NULL,0,'Active','2026-08-04 21:16:43','2026-08-04 21:16:43',NULL);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('0pJCJRqfrTmye2FFXbbeFrwlB4LzowxCie7ZJ1nW',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicTV1R0pmTE51ZTRRcVQxaENzUnVBQUZ4QVcwMjFpQVI5a0lHMDZaaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7fX0=',1785874803),
('0vsPbPO9G2xVWDygmWfYUczN7vq81i5dE16O0fCj',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiR2lNMXJzbmx3SVhPYVFyaU9OaVAzZ3RlMXd1Um9pdXZXa0VWSjZhTiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3N0b2NrLXRha2VzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zdG9jay10YWtlcyI7czo1OiJyb3V0ZSI7czo0MjoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLnN0b2NrLXRha2VzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878759),
('0vVFMA18KKKSTs3K3PLqY0Ua4VyEBA2eCx1WAmkz',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOGZwMWl2cDFDa0NwUkt0bTdzbFpqeFJMNGJJZW9pQ05LSzNYSjVrOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9mdXRoLWxvZ28uanBlZyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785875501),
('3eb9ougTzPmSMYsLkN8UOOWbtzNN4hy7rglw8mvR',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV2owMVl6TVlWUUt5VVhmZU9GRzcxSWx2ajF1RHlJelZDNktibkxCZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875249),
('3r53Se3smDZHSFwy3xM6uYEoLNjBk4ifBzuCeTny',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY1g2bGtIQzhXRGFvajNnUm9mYzI1RWllS0d0bE9CcHNnR1dKV05GTiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Byb2NlZHVyZXMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Byb2NlZHVyZXMiO3M6NToicm91dGUiO3M6NDE6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5wcm9jZWR1cmVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878760),
('3R94WkJNFcbPcOYuMAoOuw1NG3UGPnaPjhPGQ5m0',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWGJFZ0J5YXZkSVZjSnNpNjdtZ1pPN0ZjcGJVZ1ZydUNhaGJuNW9ZSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875251),
('3ZqxENPtNMSxCK4yjsrex7fjlnGOoB91wdcwNHek',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoib25rRHRSZFhNNWVvWmlIRHRkMzlRakxRWWNtdVpSODFERzRzNFFObiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Jsb29kLXJlcXVlc3RzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9ibG9vZC1yZXF1ZXN0cyI7czo1OiJyb3V0ZSI7czo0NToiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmJsb29kLXJlcXVlc3RzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878760),
('4AeFdjhonTBiYnNQQohj9xDUFfykwkTq7oWb9Cp3',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicVJDTlFScWo0QkJpZXJpcWxFenRJTEgxZlpkTDlsVWp1THNnRHJ6YyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2hvbGlkYXlzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9ob2xpZGF5cyI7czo1OiJyb3V0ZSI7czozOToiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmhvbGlkYXlzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876794),
('4kw3VAlI45HdUgqtm30aMkSFbVfvF1GfZCsc4Kcd',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieE1aejd5Nm5RSHlzVnJDcU5UOVFwVEQ3WlBxdHFjbEpFTmNNTHNHRiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Byb2NlZHVyZS1zZXNzaW9ucyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vcHJvY2VkdXJlLXNlc3Npb25zIjtzOjU6InJvdXRlIjtzOjQ5OiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMucHJvY2VkdXJlLXNlc3Npb25zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878760),
('4LzOMT2sqs9cW8ejLf0ynptAvyDChhnwPRikgNqk',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWjF0NXdoSmlaRzRHZE9LeE9IMlVhTG44M3JpUWdoanI2a3ZXYlV1VCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875125),
('4o3y61xHimg4XdDpmoNspD2MqdF0qQtvkpmgW18a',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUktHd05ocUJ5MXVlTk5VWmxiUG5HQ1VjUTdrdnJLYzR6eFByb09uRiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0ODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Byb2NlZHVyZS1jYXRlZ29yaWVzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcm9jZWR1cmUtY2F0ZWdvcmllcyI7czo1OiJyb3V0ZSI7czo1MToiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLnByb2NlZHVyZS1jYXRlZ29yaWVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878760),
('7fLdN4hpuuzRtvgHJNbkBug74j2egudtQHERem5W',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQUdWQUg3UWIwVHpIdFdsTFVKTVRibEFCekFzVmIwbGwxa2t3Ynh2RCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785874749),
('89MXa4z1CVCBXSKaAiwJMtSeCuBMfmQW7jU293kM',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoib3QxOWI5dGdiU3FuY0FpN0JxQ28yWXZWODBtOVp6ZFQxdDROVG42OSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2FkbWlzc2lvbnMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2FkbWlzc2lvbnMiO3M6NToicm91dGUiO3M6NDE6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5hZG1pc3Npb25zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876845),
('8JYRjKOUdV3P7ffcqs1pHD6yY6zAbsEyhZHmnLOY',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRmNsUjBPT1l0RHVrV2ZrVjh0VE1ldVhrZlJaU2p6V280SllCY0picyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3NlcnZpY2UtY2F0ZWdvcmllcyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vc2VydmljZS1jYXRlZ29yaWVzIjtzOjU6InJvdXRlIjtzOjQ5OiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMuc2VydmljZS1jYXRlZ29yaWVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878760),
('8QZVG2X1E4B3gKsDjrAlmhGu09vY8wbd2IX9NEVk',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidkR2RndKQTVGV1ZpYUFSUkVxYWtVaWhTa25iYXEwQ29FR09aaUZrNCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3N0b3JlLWNhdGVnb3JpZXMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0NDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3N0b3JlLWNhdGVnb3JpZXMiO3M6NToicm91dGUiO3M6NDc6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5zdG9yZS1jYXRlZ29yaWVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878759),
('8xDxYtogmVQ5H4BVuZLcgjn7p8NpANxY8G35wAiM',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWTZZMFF5VTNLeDlSMVhYcTZhRWJBQ2VPZHdhR2FIUGg2WFdpcEdnWCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Jsb29kLXRyYW5zZnVzaW9ucyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vYmxvb2QtdHJhbnNmdXNpb25zIjtzOjU6InJvdXRlIjtzOjQ5OiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMuYmxvb2QtdHJhbnNmdXNpb25zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878760),
('9j9R3bCXB6vh1EZ7OpsHTfeYsZPDYYBlAYNHE7s7',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUXR5SWpnOWR2NHJIWkNBSExWb24wZHhNWXAzb0piVWlaS0I3NDRzZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875426),
('9tcRq8C9H9OMDsC6sW7NQ9qs2MpYHwi08fDgQgVF',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMVljdW9TeUQwT2ZpTFpHenJ6eVJZdGlhWXFLVGhTeTM4WWRXTWhmUiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2hvbGlkYXlzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9ob2xpZGF5cyI7czo1OiJyb3V0ZSI7czozOToiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmhvbGlkYXlzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876845),
('9xsIpRlHuM9veExjzNXGpxjNoSGj2c9PF5dQAT5Y',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoianpYOTd3NzlBdnpHcUN2MzlzUloySW5udWUyMkJSTU9ST0x3REpZZiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3N0b3JlLXJlcXVpc2l0aW9ucyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vc3RvcmUtcmVxdWlzaXRpb25zIjtzOjU6InJvdXRlIjtzOjQ5OiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMuc3RvcmUtcmVxdWlzaXRpb25zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878759),
('aa3ZRb6ifH3U8LckKm1kd0Q72KJrIXxlZqb9VIzn',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZ2pPUzZ1MGd1ZnR1RzU4TXBCdEVLNTl0emF6UThjd3ZMR0hDSk9sMiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3N0b3JlLWl0ZW1zIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zdG9yZS1pdGVtcyI7czo1OiJyb3V0ZSI7czo0MjoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLnN0b3JlLWl0ZW1zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878759),
('ak1s7vYLrk0k2OzPT8pPuG38OTENEOk6tFuKbngR',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoidjlzb1VtRTJYdGpobXl1bW01alQ1NGg5b3d4TU83R3BoV2kxTHFTNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875276),
('bN4rrsnrIictvsWxTAhMckilMwhny0POxfHDfQ31',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoicVdWWGcwR2x1MzR6MzE2TG5yTjZwVjJPY01FYXVEc2VEdTNGcHQyOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785874900),
('CAdawsi25LwMg4oFCEO7ZgT04MQNeD9nvYJOpxJ1',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoib0k2ZlVQOUc2elh3T01ObnNqckROVjBaNXcwNlphMHFLcVh3R0NUUCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2pvdXJuYWwtZW50cmllcyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vam91cm5hbC1lbnRyaWVzIjtzOjU6InJvdXRlIjtzOjQ2OiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMuam91cm5hbC1lbnRyaWVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876793),
('CAPGOaDbhRzpHPpzLDdLhMOLgnmUGavTMlSlaudB',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVWxmaEd2M2tFRlZubDNZNHlsNmQyZ2EwZURMWFY1S3pLbWVCNWdlNSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3N0b3JlLW1vdmVtZW50cyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vc3RvcmUtbW92ZW1lbnRzIjtzOjU6InJvdXRlIjtzOjQ2OiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMuc3RvcmUtbW92ZW1lbnRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878759),
('CbUAPXZ1Zvdvb9ocuvKyILT9NNc8dntIipZjEKfP',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaENtN3VEYUtWSVFweTJMeGFVQkd0VHZIZlBDUklxcDdCRGF0VnEzYiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Byb3ZpZGVyLXNjaGVkdWxlcyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vcHJvdmlkZXItc2NoZWR1bGVzIjtzOjU6InJvdXRlIjtzOjQ5OiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMucHJvdmlkZXItc2NoZWR1bGVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876845),
('ceWwWkSw17KhsGDQp9XbcTPeP9QybtufB4oy042o',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiclVKandDRE9aa1VkRDdTUndIOEs2R2F6QUVSS2hGT3I5cjRCZFFVVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785876775),
('d5eA5Q68gspVrFuVxTv9KhjJEOgLq4d7gawG1BZQ',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicGVWeHo1VDJCR1RURllPc0hpb1VBVXQ4emwzSjROODZya2FnOHhTOSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2J1ZGdldHMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozNToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2J1ZGdldHMiO3M6NToicm91dGUiO3M6Mzg6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5idWRnZXRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876845),
('D6rC3IhUk235N58UKWXMiLRPlykaH7VxiMTLPszS',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN3EwTUoybjhPQWF5TDJtZ2lvRlQ4ZWI4WlhvTDBudEVCbGZ2bFNvaCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Jsb29kLXVuaXRzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9ibG9vZC11bml0cyI7czo1OiJyb3V0ZSI7czo0MjoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmJsb29kLXVuaXRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878760),
('DdljE9VwotBpa2m4HahyGWViJ9Emvgrxh40Iv6Zm',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOUJ1OTRkTlJ4TWtnYTc1d3dXbGV1ZGhrcEg2M0dERFliRzEycGJJbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7fX0=',1785875439),
('dvUZF70h2N9kqYq35lQUMJyiFQy4Wqi5qBn0epaw',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNnpoUXdHMW93TjhxZHh1V29zMHY3UmNUU3ZiN3I0N3hUM2dvWUNoNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875248),
('efao4S5CJnw8Hd2Szom8k3gxjMFZNzxAhaHWLO6O',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidUdPajI0VlJSdDNCZFROcDZtV0FsZmVRdTl0TXVpQ0JLaFBZeHdUYSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2JlZC10cmFuc2ZlcnMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0MToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2JlZC10cmFuc2ZlcnMiO3M6NToicm91dGUiO3M6NDQ6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5iZWQtdHJhbnNmZXJzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876845),
('ehaPSqwAmAd3pDsE1BBIK4tuyJXt5QCHO8nqSqOE',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU3E0ZU5EWFg1MldBbmhyT0ZwcnBpMFdNeVJWRDYyeVE5TEdvSVhhYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785874984),
('Eiaq2gvBAa7K6U5hNBy5JLSi9V7lembyOX6Uttcq',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY29zMFFpSnVGWlRXOTdNTHpQWmlVN0FoVnNwaWp4bVJZU0t2eWlnZSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Byb2NlZHVyZS1ib29raW5ncyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vcHJvY2VkdXJlLWJvb2tpbmdzIjtzOjU6InJvdXRlIjtzOjQ5OiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMucHJvY2VkdXJlLWJvb2tpbmdzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878760),
('eT7VWyRHhYi1ZiGYPRWADjKYkv7xtZUxKLpdFW0L',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUlk0a2NaNmxzTE10QllUZlZqbVZqNmZ2Sk1aNVAwM2l5S25kaGp0eiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2xlYXZlLXJlcXVlc3RzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sZWF2ZS1yZXF1ZXN0cyI7czo1OiJyb3V0ZSI7czo0NToiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmxlYXZlLXJlcXVlc3RzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876794),
('F2oIcXLy8RDZVdXgVDMPfiIVk0xkuPiwK9Qu8TAc',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRngxaGhvWEtmQkx5UDUwMjBBaVQyVVdrM2ZzWDEzMlI1N2tBWGNKeCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Rpc2NoYXJnZS1zdW1tYXJpZXMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0NzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Rpc2NoYXJnZS1zdW1tYXJpZXMiO3M6NToicm91dGUiO3M6NTA6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5kaXNjaGFyZ2Utc3VtbWFyaWVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876845),
('FBJOSYS3M8ZLrm79XPlPLsoYvKMWUnQ5GOfibeLF',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoicnF1ZEVCRUJxT1YzN3lSNXk0bUZsc3VEQlk4azBkVFlkVzZUOXA3RSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875426),
('fRC5Paa6xmb3TSucXAk5ATZZtlwlwbuhm4nPjxCA',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSmVOeWQ5RUdVSHh2TFh5Q091dTFEMXl2VlFZalBnRmpUeGZSdFl1dSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2JlZHMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozMjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2JlZHMiO3M6NToicm91dGUiO3M6MzU6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5iZWRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876845),
('fWZ2J5vvi3qGILdsfVZBKTyxGUUJFQLpiv3cVSKo',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoibWFhVmt1d2ZoUkplclRBQmN4aUp4WEZPUWVFd3czelV6S1BDZzhiQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785876845),
('Gb3MEm0RQQvp4ri0hlDma7543pl0RSxCna5ZGAkk',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVGtoTU5WMXVrVzJaUFhTaWZ5TTVmZlV0bmFtRGFrSmdmekNDdkNMWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785876793),
('GcVcHE0Xn1Dl2KRDM2Bz0W4e7AXGXSW7kkpdlewF',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicThwMUdmbFJrUWxXZU5qR2VpODZCM29ucldJQnFVTk5WM2FzdGZPTSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3N0b3JlLXN0b2NrcyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vc3RvcmUtc3RvY2tzIjtzOjU6InJvdXRlIjtzOjQzOiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMuc3RvcmUtc3RvY2tzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878759),
('H3tD5kdh96KD9CE49m4Sz8nqwXiAdaeXD2ACLxNN',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiY2daTTdoRnFoWXNINm5HeUxCZGE3QUlhWTdST2h6UWM4Mm1VQ2hqQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875126),
('HaPfTXSjmTpnfSJLQl78b7l2rgNGPXva1SYymisg',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSEp3VlZCRVlxZXZCMTZwQUJGbEYwUVFvaWlDQ3J1NU40dnVOR1RpOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875302),
('hMxKjFu4FW2JusTVDyecJZ1s4bncLieko8DPHPXE',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR21POW12dDZjZFJwdVpJZ0pOMlFYcmQ3MWg3dU8xUmk0Y1NUdFNCRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875126),
('hzn2ll9KGVqJ7bOGtFRorSyyE5w0fDmPvaGVPWqN',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNHE2bHFUU3hNQ3FuSVA0d0R5ZnNMOGtRU09kanNpWWxKc0lpTjBZSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875252),
('IYoiGKgp1MDU825hCbBptULut061AQS8jg2Cwcwq',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS2g2RDRuMEQybk1wVlBUYnBNSlhES1RoS2swUmNBZVVta3FrTTE2TSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3BhdGllbnRzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wYXRpZW50cyI7czo1OiJyb3V0ZSI7czozOToiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLnBhdGllbnRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785875502),
('IZxbJ8Xtapp67krLT8WEny0dq5WPjRFXTyH7yf4q',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTXJ2Mm1kNFBhbVhnTFkyVkd5ekZIbjhyMmlzR0JubUNxd0t5TU9hbSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2NsaW5pYy1zY2hlZHVsZXMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0NDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2NsaW5pYy1zY2hlZHVsZXMiO3M6NToicm91dGUiO3M6NDc6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5jbGluaWMtc2NoZWR1bGVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876793),
('jxNIjKbyjH98QWN89jTInjy67LncTkUKCKERDR1O',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoidUJMUGFSZVFIa3RZaXB3NjVYT0NGb1QyNlVSa3poTU0zSk1ZcjdkdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875247),
('kawXmrDqpYpzAYiMjZvE0S7WkOnqVXHJDkYqywzu',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ2tnYW1RZW9kalM5TEdvVllWMEduNE1FbmdySDV2Tm90cndmMmFERyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2NsaW5pYy1zY2hlZHVsZXMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0NDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2NsaW5pYy1zY2hlZHVsZXMiO3M6NToicm91dGUiO3M6NDc6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5jbGluaWMtc2NoZWR1bGVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876845),
('kDidHIPZqccANtAKNovaKZgLlVgcB3O1DNy0768f',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR1BQdWRFb21JMU5hSWZQOFdGZWVSR1V3NGxPa0gwdnFwVHNGYnFleiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785877682),
('Ku0yehCeA3MgWi8tYhVgHE4zeBfN41L3rGh0elU0',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNXJPaVB5Z2JCYkYxQVhiS0NsbHF6YTk5WU1lOW52cHVSYjNJMXFMTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785874958),
('kXeczia6Bm4qKRdMCwMxZ6I4T0nqlYIPNxZqiiQW',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQVlyVFFacWVoZUI2VlpuazcxeUg3RWNYSVBFYkNyeEJ6RldmSDk0NCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785874855),
('l1YvkUl2SryJK5z400lP7p2b53vEgZ1oVc7OqRHw',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieGQybkdaTWYxNXNqSjc5dWh3Zmk3cWpuU0xWZkU4MlRUVTI5WXUxZyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2RydWctc3RvY2stbGV2ZWxzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kcnVnLXN0b2NrLWxldmVscyI7czo1OiJyb3V0ZSI7czo0ODoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmRydWctc3RvY2stbGV2ZWxzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878760),
('LJEssOk3diO9SY52ecgrpWGsYpEkC9BBMwwvB6K0',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUGRuTFU0akVTeXhmS1BvRWJIVHMweXUzOE9YeU5vNTBESjE3c2c3WiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875501),
('LKkT7j1nCRdOWznvHLMku1VbnC5ngeufLdELmGHh',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicVI3cmZlOFhXTXcwOENhUEgzdnZ3SDFwN0ZqOEhYRk1WeTkxSFpUWiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3dhcmRzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi93YXJkcyI7czo1OiJyb3V0ZSI7czozNjoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLndhcmRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876845),
('Lo3WKu5zDatdFNQBWgRrXd3op6Z35Cuy8HoOhLJk',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWXIyNTBnWjVyT0Fvb1JyOGprU2h4eWZraEp6MWpXcWk0bWtQc1VpMiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2FwcG9pbnRtZW50LXR5cGVzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9hcHBvaW50bWVudC10eXBlcyI7czo1OiJyb3V0ZSI7czo0ODoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmFwcG9pbnRtZW50LXR5cGVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876793),
('lr2yRvCTIq3PUyCCum7ltsZ18Tw7cZq3GF7mRmfy',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQXpyTTFIWXlrT0VBbVVnNUNuMEFZSUExWThYVDYyQVRnMkRPZFZhWCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Jsb29kLWRvbm9ycyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vYmxvb2QtZG9ub3JzIjtzOjU6InJvdXRlIjtzOjQzOiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMuYmxvb2QtZG9ub3JzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878760),
('mF3fr8jhgiwN7OjyEdZZwzbsUAhKIoBpGbo2XJI9',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOEszbThRR1VXVEFDZE1lTkNXRjljeTdYYnJSTzdMYVc4T2FwV0pxUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875426),
('mu3NBXBv036d407fK5l1aJwIXGkG2hEWnf6bJVJM',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoid0RJdTZWU0txamp2QU5DUG5JNFBVZU5hUFRjVjVmcFdIN2F5YWdORCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3N0b3JlLWxvY2F0aW9ucyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vc3RvcmUtbG9jYXRpb25zIjtzOjU6InJvdXRlIjtzOjQ2OiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMuc3RvcmUtbG9jYXRpb25zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878759),
('n7WQbrxML14NP8oB0lecFmFUh7QKnq3pE1V9DoSI',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiazhtVzQzVDFtSmJmN2tmdHhlQjg3UXNkVzNiY21oYVpWWDB6T2ZPOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785878759),
('o2zpjtOt2sVhszUGAI1YXmNaJ99p0Zgr9MSPzSlO',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUU02amVjYzlWNmt4alN1U2ZscEo0MnhzWkJ6VGVyeDlHdmgzU3pSOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7fX0=',1785875488),
('oa3BTTstY879R6RAmmCNjteILjWZ4SD1AiRUTmaX',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMU5OSDVrWmFjbVZ3NllTMGh0QkwzZWhDNkx6bkJLaGJvMVZhMDNvMyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Jsb29kLWRvbmF0aW9ucyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vYmxvb2QtZG9uYXRpb25zIjtzOjU6InJvdXRlIjtzOjQ2OiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMuYmxvb2QtZG9uYXRpb25zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878760),
('OAEJrqidxi977XaErfeHHHgGloWIeUEoOeA5Yegf',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoidjBuQWdzV2s1blpIMkE5bVlDcTdEY3BiN09ib1ViV0p3ZUIzQ1dHUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875456),
('Odax9A7CI4AFbml5R4wygd48kCnYQDlkItIEbXW9',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNmh3VWJXbTczR015dkdtdXpvSFI1VmhXYjlTcUsxeEJCNXNqZ1FMNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9pbnZvaWNlcyI7czo1OiJyb3V0ZSI7czozOToiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmludm9pY2VzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2ludm9pY2VzIjt9fQ==',1785875347),
('OWa9LpZen1dUhni790SjeP3ODfr4P8xMeexAGsVH',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUmo5WW5DMTlobkVvZ0ROdmpuYllPNFB4dW9IV1B2a0pPYnlzWHQxbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875200),
('OyN20mZfr1zustJxE5aZNLwh4qe8yupDE8y881sk',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTWxHdTNWYlN6R3MwQjF2elVVMWVDT1lId3VEYllqTGxQZ0UxTERiSSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2JlZHMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozMjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2JlZHMiO3M6NToicm91dGUiO3M6MzU6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5iZWRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876793),
('PbXMbX778J3Kn8pNOhoQOgBwBwfAigKzXrnfpRJH',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTXNEN0hYUkpibXVDSzhHMmc2WW9GaHZXY1N3VUtLeUIwT1BVV3VpZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875095),
('rC367q2gYgFsWK8GXREdlW5hfhEyqDhphvQMwdkD',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0dlMmROU3JDaVN0c2NPUjB6NGxVUmxpa3hPZXplVUxiajYwYThjdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875426),
('RfcWR8RCoLyrGvnRXP6juJNiNy68vZfTqq8pM61U',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTjBGZTZVbWppbkVqR0tKUTZncENjOXgzOFZiR3VuMW1BQU9xM0V1SSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875200),
('RL75VYBrER6LJLonBvSSs7elEpOzImXzsg4BglsN',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUTFkdzJJNmpOdjJrdnpJaHN1NTF0dnpMWURxTmI1b2V6bTlBZjY4ayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875418),
('rnT3QQYR2VwJZDw13jyFSIZiTUKGZQiymBeViZKV',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoicHZUa3VrYmN0cjNFTHhwczAzOFF6V3UwVG1taGJVakt5Wk5jMG1pVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875051),
('t8tHENMxqWg3dYJbxS6VuU06tjO7RP2qWMJefHXa',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ1pVZ0xyeHk3T0hkT0RZdmR6TGNkUlowZHlITFJiRm04WXlDdzZ1SiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2xlYXZlLXJlcXVlc3RzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sZWF2ZS1yZXF1ZXN0cyI7czo1OiJyb3V0ZSI7czo0NToiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmxlYXZlLXJlcXVlc3RzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876845),
('tkkB1Y7lamM3OvnLvPTQaw8EjAimQ3shXOLHbKHE',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicGw3TjNJVkRmbHRuRzNFOEJiTndFVWZsdUxjT1lFbTZuQVhCTkxUcyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2FwcG9pbnRtZW50LXR5cGVzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9hcHBvaW50bWVudC10eXBlcyI7czo1OiJyb3V0ZSI7czo0ODoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmFwcG9pbnRtZW50LXR5cGVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876845),
('uIa6mASrTLHuYOwRoxGR1e6l8547usfGmEv8tfdP',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTnFPRW9aRloxbnNuZHNKRTZiRlNPVGVMT1pHNXM5N3dqcEZKRVJPUSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2V4cGVuc2UtY2xhaW1zIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9leHBlbnNlLWNsYWltcyI7czo1OiJyb3V0ZSI7czo0NToiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmV4cGVuc2UtY2xhaW1zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876845),
('Ujgab1H41WHqNcAVqyGCTuzWeMMf4n78r1okVXPF',NULL,'127.0.0.1','curl/8.14.1','YToyOntzOjY6Il90b2tlbiI7czo0MDoiRkpkZzVmdllWZzhYdE9ycDBqNmRnSVRhMWE1SUdiWHRlTjVqdFJpOCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785875456),
('V293RAF2FR4wVXW1dQaFTdvXCAdj3Xb2tobr0nJe',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYVVVc3RLWFpqcVdWWXE4c0NiWjFYVTdocVdoVG9JOFZ2U2FWSDZiRiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2pvdXJuYWwtZW50cmllcyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vam91cm5hbC1lbnRyaWVzIjtzOjU6InJvdXRlIjtzOjQ2OiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMuam91cm5hbC1lbnRyaWVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876845),
('VbxIF07qaMWLORLaCZSgO0d7dleNbFdJbwKc8zpG',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYVRwZnZueTVGMWxySWdnM3RKcEI1UWZTNTFvOUEyOVRvcTg2SHl2bCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2NoYXJ0LW9mLWFjY291bnRzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9jaGFydC1vZi1hY2NvdW50cyI7czo1OiJyb3V0ZSI7czo0ODoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmNoYXJ0LW9mLWFjY291bnRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876793),
('VC9JII3AlkUCrgbN7A4edXWhaDqOJB4Ya85ynBo5',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVVVtUGRuM0VHN3A2WEdrNkF2Q2J5bjFNR3lpNk9WakF1b3dlRWxsMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785874796),
('vkUlO85mtdyyVxvPoppX11AbEkHmHxgX8lMyD1ua',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQnZCOEp0VEFGSkczMTB1bmZha3hWemJwMGh3ak56WEh6dEEwTmQwbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875426),
('vT3oiGcFXwILys7OMm2ANJ4XvDX6qr4uKkrpNDiB',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWnFqaVY1U0U0Zzl2cGJUZVlrQlIzYldnM0J3Mk1PNkpudm5aaGVSYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785876051),
('wkfZiQQzP18Q3Xf26gh31PrvPW5AcUkAO79VheSq',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRkw2ZFJVdmRPNzQ0WDF1TEk4OUVTOXJ4cjVJYkoyTmtZcG5TOFVPUiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2FwcG9pbnRtZW50LXNsb3RzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9hcHBvaW50bWVudC1zbG90cyI7czo1OiJyb3V0ZSI7czo0ODoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmFwcG9pbnRtZW50LXNsb3RzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876845),
('WzMRmBxxAMU2qs5JmJsIW3ibicBU52TfEdOiGEke',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU2ZvRHdjdnFZQUVSa1plaklSbWlOM09rZ3prZG1IZFVidmR3NG9yMyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2FkbWlzc2lvbnMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2FkbWlzc2lvbnMiO3M6NToicm91dGUiO3M6NDE6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5hZG1pc3Npb25zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876793),
('x4G64zMyXpwUB5kDJXpl4e8lSfr6hxcY5AqmG366',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiamRoNXU4UFVLbDlkOUhybDNFSVVrUWJiWkx1T3hqYTN6a29zUGluSCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2J1ZGdldHMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozNToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2J1ZGdldHMiO3M6NToicm91dGUiO3M6Mzg6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5idWRnZXRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876793),
('XBDFuNp1ZaeorrrxsCcTV9rJKxcuMIbZwG5oG0Hs',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoieVJZdFpueXpmWlFPazdKV2x1Z0R5UW90ZXY3bm13NUpocHQydkhLdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875017),
('XOdZDy8Ce3VCIEZtTCWiY4OwcRO7blKs834MnpTw',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoick9jYUREd2RYdFR5OEVtS25pQ3RnSTBaNndZZjM0eURHMW1EeGlpZSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Jsb29kLWNyb3NzbWF0Y2hlcyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vYmxvb2QtY3Jvc3NtYXRjaGVzIjtzOjU6InJvdXRlIjtzOjQ5OiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMuYmxvb2QtY3Jvc3NtYXRjaGVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878760),
('YaYcPNbWTSesS8NfRqTwPMdJazjvDCaLy41a2CBN',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVlg4bTFCZkUyM29aU0NmbzJUSEhneWFiQ1BXM2pEWTBWRWxGTmdIVCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2NoYXJ0LW9mLWFjY291bnRzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9jaGFydC1vZi1hY2NvdW50cyI7czo1OiJyb3V0ZSI7czo0ODoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmNoYXJ0LW9mLWFjY291bnRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876845),
('yYBUbh7cNWFhrMO8Ef12mGD4WH4PbGq1ul12dPJU',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWkNWazNaYzlicllyQ1dzbVRmYzgzRXhGV3YycW9EWWhhUm01WnpvWCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2FwcG9pbnRtZW50LXNsb3RzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9hcHBvaW50bWVudC1zbG90cyI7czo1OiJyb3V0ZSI7czo0ODoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmFwcG9pbnRtZW50LXNsb3RzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876793),
('Z3SEd8izNBoECtZsqZNW0jf9smfymAG49TLkiEHa',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWGJUZXBBZ3cydHo2Mk5VY1dFOTl6YlNMaUQwazdqeENWMkExNmQ2ZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785874841),
('Zb9U738qKhpSRcnR26CqSAesIVF6jsLWOgUg3UdK',NULL,'127.0.0.1','curl/8.14.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoicVJMWEdka2ZXb0lEYkxZTEVxdnpLRFVPbzhsTm01YklUZFlGaTBhSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785875468),
('zymAXpHdAJ1zH8WtDOGjBBEDkUZ2ytdSBFSWalH2',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVTladG9ONDhmeE8wa2RSQ2llTmVmTURKNmtGbEN6Mk4xSlljQUV2dCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0ODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2dvb2RzLXJlY2VpdmVkLW5vdGVzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9nb29kcy1yZWNlaXZlZC1ub3RlcyI7czo1OiJyb3V0ZSI7czo1MToiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLmdvb2RzLXJlY2VpdmVkLW5vdGVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785878760),
('ZYuFfOMZEXHK069NuvhZ19GCGOOX70Fr0mHX2kmz',NULL,'127.0.0.1','curl/8.14.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVGsyZ2NPbnp5ZzJCaUxCSjA0UWhES2pUOUVkN1pKZ2lBcmR1c09TVyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3dhcmRzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi93YXJkcyI7czo1OiJyb3V0ZSI7czozNjoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLndhcmRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785876793);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `clinic_id` bigint(20) unsigned DEFAULT NULL,
  `staff_number` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `staff_type` enum('Doctor','Nurse','Pharmacist','Laboratory Scientist','Radiographer','Physiotherapist','Administrator','Accountant','ICT','Medical Records','Other') NOT NULL,
  `passport` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_number` (`staff_number`),
  UNIQUE KEY `staff_user_id_unique` (`user_id`),
  KEY `staff_staff_number_index` (`staff_number`),
  KEY `staff_last_name_index` (`last_name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES
(1,1,1,1,NULL,'FUTH/STAFF/0001','Mr.','System','','Administrator','Male',NULL,'+2348033000001','Lafia, Nasarawa','ICT Lead','Administrator',NULL,NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(2,2,1,1,NULL,'FUTH/STAFF/0002','Dr.','Okoro','','Chukwuemeka','Male',NULL,'+2348033000002','Lafia, Nasarawa','Consultant Physician','Doctor',NULL,NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(3,3,1,4,NULL,'FUTH/STAFF/0003','Dr.','Adebayo','','Funmilayo','Female',NULL,'+2348033000003','Lafia, Nasarawa','Senior Registrar Obs & Gyn','Doctor',NULL,NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(4,4,1,1,NULL,'FUTH/STAFF/0004','Mr.','John','','Bala','Male',NULL,'+2348033000004','Lafia, Nasarawa','Principal Nursing Officer','Nurse',NULL,NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(5,5,1,8,NULL,'FUTH/STAFF/0005','Pharm.','Musa','','Ibrahim','Male',NULL,'+2348033000005','Lafia, Nasarawa','Chief Pharmacist','Pharmacist',NULL,NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(6,6,1,6,NULL,'FUTH/STAFF/0006','Mrs.','Aisha','','Yusuf','Female',NULL,'+2348033000006','Lafia, Nasarawa','Senior Lab Scientist','Laboratory Scientist',NULL,NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(7,7,1,7,NULL,'FUTH/STAFF/0007','Mr.','Tunde','','Okafor','Male',NULL,'+2348033000007','Lafia, Nasarawa','Senior Radiographer','Radiographer',NULL,NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(8,8,1,1,NULL,'FUTH/STAFF/0008','Mrs.','Hauwa','','Sani','Female',NULL,'+2348033000008','Lafia, Nasarawa','Records Officer','Medical Records',NULL,NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(9,9,1,1,NULL,'FUTH/STAFF/0009','Mr.','David','','Eze','Male',NULL,'+2348033000009','Lafia, Nasarawa','Finance Officer','Accountant',NULL,NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL);
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `states` (
  `state_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`state_id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `states`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `states` WRITE;
/*!40000 ALTER TABLE `states` DISABLE KEYS */;
INSERT INTO `states` VALUES
(1,'Abia','NG-AB','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(2,'Adamawa','NG-AD','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(3,'Akwa Ibom','NG-AK','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(4,'Anambra','NG-AN','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(5,'Bauchi','NG-BA','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(6,'Bayelsa','NG-BY','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(7,'Benue','NG-BE','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(8,'Borno','NG-BO','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(9,'Cross River','NG-CR','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(10,'Delta','NG-DE','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(11,'Ebonyi','NG-EB','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(12,'Edo','NG-ED','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(13,'Ekiti','NG-EK','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(14,'Enugu','NG-EN','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(15,'FCT','NG-FC','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(16,'Gombe','NG-GO','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(17,'Imo','NG-IM','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(18,'Jigawa','NG-JI','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(19,'Kaduna','NG-KD','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(20,'Kano','NG-KN','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(21,'Katsina','NG-KT','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(22,'Kebbi','NG-KE','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(23,'Kogi','NG-KO','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(24,'Kwara','NG-KW','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(25,'Lagos','NG-LA','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(26,'Nasarawa','NG-NA','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(27,'Niger','NG-NI','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(28,'Ogun','NG-OG','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(29,'Ondo','NG-ON','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(30,'Osun','NG-OS','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(31,'Oyo','NG-OY','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(32,'Plateau','NG-PL','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(33,'Rivers','NG-RI','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(34,'Sokoto','NG-SO','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(35,'Taraba','NG-TA','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(36,'Yobe','NG-YO','2026-07-31 11:00:00','2026-07-31 11:00:00'),
(37,'Zamfara','NG-ZA','2026-07-31 11:00:00','2026-07-31 11:00:00');
/*!40000 ALTER TABLE `states` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `drug_id` bigint(20) unsigned NOT NULL,
  `drug_batch_id` bigint(20) unsigned DEFAULT NULL,
  `dispensing_point_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` enum('Receipt','Transfer In','Transfer Out','Dispense','Adjustment In','Adjustment Out','Return','Disposal','Stock Take') NOT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `movement_date` datetime NOT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `balance_after` decimal(12,2) NOT NULL DEFAULT 0.00,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_drug_id_movement_date_index` (`drug_id`,`movement_date`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
INSERT INTO `stock_movements` VALUES
(1,1,1,1,1,5,'Receipt','BATCH-DRG-001','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(2,1,2,2,1,5,'Receipt','BATCH-DRG-002','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(3,1,3,3,1,5,'Receipt','BATCH-DRG-003','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(4,1,4,4,1,5,'Receipt','BATCH-DRG-004','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(5,1,5,5,1,5,'Receipt','BATCH-DRG-005','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(6,1,6,6,1,5,'Receipt','BATCH-DRG-006','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(7,1,7,7,1,5,'Receipt','BATCH-DRG-007','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(8,1,8,8,1,5,'Receipt','BATCH-DRG-008','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(9,1,9,9,1,5,'Receipt','BATCH-DRG-009','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(10,1,10,10,1,5,'Receipt','BATCH-DRG-010','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(11,1,11,11,1,5,'Receipt','BATCH-DRG-011','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(12,1,12,12,1,5,'Receipt','BATCH-DRG-012','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(13,1,13,13,1,5,'Receipt','BATCH-DRG-013','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(14,1,14,14,1,5,'Receipt','BATCH-DRG-014','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(15,1,15,15,1,5,'Receipt','BATCH-DRG-015','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(16,1,16,16,1,5,'Receipt','BATCH-DRG-016','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(17,1,17,17,1,5,'Receipt','BATCH-DRG-017','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(18,1,18,18,1,5,'Receipt','BATCH-DRG-018','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(19,1,19,19,1,5,'Receipt','BATCH-DRG-019','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(20,1,20,20,1,5,'Receipt','BATCH-DRG-020','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(21,1,21,21,1,5,'Receipt','BATCH-DRG-021','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(22,1,22,22,1,5,'Receipt','BATCH-DRG-022','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(23,1,23,23,1,5,'Receipt','BATCH-DRG-023','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(24,1,24,24,1,5,'Receipt','BATCH-DRG-024','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(25,1,25,25,1,5,'Receipt','BATCH-DRG-025','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(26,1,26,26,1,5,'Receipt','BATCH-DRG-026','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(27,1,27,27,1,5,'Receipt','BATCH-DRG-027','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(28,1,28,28,1,5,'Receipt','BATCH-DRG-028','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(29,1,29,29,1,5,'Receipt','BATCH-DRG-029','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(30,1,30,30,1,5,'Receipt','BATCH-DRG-030','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(31,1,31,31,1,5,'Receipt','BATCH-DRG-031','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(32,1,32,32,1,5,'Receipt','BATCH-DRG-032','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(33,1,33,33,1,5,'Receipt','BATCH-DRG-033','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(34,1,34,34,1,5,'Receipt','BATCH-DRG-034','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(35,1,35,35,1,5,'Receipt','BATCH-DRG-035','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(36,1,36,36,1,5,'Receipt','BATCH-DRG-036','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(37,1,37,37,1,5,'Receipt','BATCH-DRG-037','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(38,1,38,38,1,5,'Receipt','BATCH-DRG-038','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(39,1,39,39,1,5,'Receipt','BATCH-DRG-039','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(40,1,40,40,1,5,'Receipt','BATCH-DRG-040','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(41,1,41,41,1,5,'Receipt','BATCH-DRG-041','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(42,1,42,42,1,5,'Receipt','BATCH-DRG-042','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(43,1,43,43,1,5,'Receipt','BATCH-DRG-043','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(44,1,44,44,1,5,'Receipt','BATCH-DRG-044','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(45,1,45,45,1,5,'Receipt','BATCH-DRG-045','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(46,1,46,46,1,5,'Receipt','BATCH-DRG-046','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(47,1,47,47,1,5,'Receipt','BATCH-DRG-047','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(48,1,48,48,1,5,'Receipt','BATCH-DRG-048','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(49,1,49,49,1,5,'Receipt','BATCH-DRG-049','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00'),
(50,1,50,50,1,5,'Receipt','BATCH-DRG-050','2026-05-02 12:00:00',1000.00,1000.00,'Stock receipt','2026-05-02 11:00:00','2026-05-02 11:00:00');
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `stock_returns`
--

DROP TABLE IF EXISTS `stock_returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_returns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned DEFAULT NULL,
  `dispensing_log_id` bigint(20) unsigned DEFAULT NULL,
  `drug_id` bigint(20) unsigned NOT NULL,
  `drug_batch_id` bigint(20) unsigned DEFAULT NULL,
  `received_by` bigint(20) unsigned NOT NULL,
  `return_number` varchar(255) NOT NULL,
  `returned_at` datetime NOT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `reason` enum('Expired','Damaged','Wrong Dispense','Patient Returned','Other') NOT NULL DEFAULT 'Patient Returned',
  `notes` text DEFAULT NULL,
  `disposition` enum('Restocked','Quarantined','Disposed') NOT NULL DEFAULT 'Quarantined',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `return_number` (`return_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_returns`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `stock_returns` WRITE;
/*!40000 ALTER TABLE `stock_returns` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_returns` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `stock_take_items`
--

DROP TABLE IF EXISTS `stock_take_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_take_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `stock_take_id` bigint(20) unsigned NOT NULL,
  `store_item_id` bigint(20) unsigned DEFAULT NULL,
  `drug_id` bigint(20) unsigned DEFAULT NULL,
  `item_name` varchar(191) NOT NULL,
  `batch_number` varchar(191) DEFAULT NULL,
  `system_quantity` decimal(14,2) NOT NULL DEFAULT 0.00,
  `counted_quantity` decimal(14,2) NOT NULL DEFAULT 0.00,
  `variance` decimal(14,2) NOT NULL DEFAULT 0.00,
  `unit_cost` decimal(14,2) NOT NULL DEFAULT 0.00,
  `variance_value` decimal(14,2) NOT NULL DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_take_items_store_item_id_foreign` (`store_item_id`),
  KEY `stock_take_items_drug_id_foreign` (`drug_id`),
  KEY `stock_take_items_stock_take_id_index` (`stock_take_id`),
  CONSTRAINT `stock_take_items_drug_id_foreign` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_take_items_stock_take_id_foreign` FOREIGN KEY (`stock_take_id`) REFERENCES `stock_takes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_take_items_store_item_id_foreign` FOREIGN KEY (`store_item_id`) REFERENCES `store_items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_take_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `stock_take_items` WRITE;
/*!40000 ALTER TABLE `stock_take_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_take_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `stock_takes`
--

DROP TABLE IF EXISTS `stock_takes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_takes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `stock_take_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `location_id` bigint(20) unsigned DEFAULT NULL,
  `scope` enum('Full Hospital','Single Location','Pharmacy','Specific Items') NOT NULL DEFAULT 'Full Hospital',
  `stock_take_date` date NOT NULL,
  `initiated_by` bigint(20) unsigned NOT NULL,
  `counted_by` bigint(20) unsigned DEFAULT NULL,
  `verified_by` bigint(20) unsigned DEFAULT NULL,
  `status` enum('Planned','In Progress','Counted','Verified','Closed','Cancelled') NOT NULL DEFAULT 'Planned',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_takes_stock_take_number_unique` (`stock_take_number`),
  KEY `stock_takes_hospital_id_foreign` (`hospital_id`),
  KEY `stock_takes_location_id_foreign` (`location_id`),
  KEY `stock_takes_initiated_by_foreign` (`initiated_by`),
  KEY `stock_takes_counted_by_foreign` (`counted_by`),
  KEY `stock_takes_verified_by_foreign` (`verified_by`),
  CONSTRAINT `stock_takes_counted_by_foreign` FOREIGN KEY (`counted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_takes_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_takes_initiated_by_foreign` FOREIGN KEY (`initiated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_takes_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `store_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_takes_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_takes`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `stock_takes` WRITE;
/*!40000 ALTER TABLE `stock_takes` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_takes` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `store_categories`
--

DROP TABLE IF EXISTS `store_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `type` enum('Medical Supplies','Office Supplies','Cleaning','Furniture','Equipment','IT','Kitchen','Linens','Fuel & Lubricants','Stationery','PPE','Other') NOT NULL DEFAULT 'Medical Supplies',
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_categories_code_unique` (`code`),
  KEY `store_categories_hospital_id_type_index` (`hospital_id`,`type`),
  CONSTRAINT `store_categories_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_categories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `store_categories` WRITE;
/*!40000 ALTER TABLE `store_categories` DISABLE KEYS */;
INSERT INTO `store_categories` VALUES
(1,1,'Medical Supplies','MSC','Medical Supplies',NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(2,1,'Personal Protective Equipment','PPE','PPE',NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(3,1,'Office Supplies','OFC','Office Supplies',NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(4,1,'Cleaning Materials','CLN','Cleaning',NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(5,1,'Linens','LNS','Linens',NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(6,1,'Fuel & Lubricants','FUL','Fuel & Lubricants',NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(7,1,'Stationery','STN','Stationery',NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(8,1,'IT Supplies','ITS','IT',NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL);
/*!40000 ALTER TABLE `store_categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `store_items`
--

DROP TABLE IF EXISTS `store_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `store_category_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `barcode` varchar(191) DEFAULT NULL,
  `generic_name` varchar(191) DEFAULT NULL,
  `brand` varchar(191) DEFAULT NULL,
  `manufacturer` varchar(191) DEFAULT NULL,
  `supplier` varchar(191) DEFAULT NULL,
  `unit_of_measure` varchar(191) NOT NULL,
  `pack_size` varchar(191) DEFAULT NULL,
  `units_per_pack` int(11) NOT NULL DEFAULT 1,
  `cost_price` decimal(14,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(14,2) NOT NULL DEFAULT 0.00,
  `reorder_level` decimal(14,2) NOT NULL DEFAULT 0.00,
  `reorder_quantity` decimal(14,2) NOT NULL DEFAULT 0.00,
  `is_consumable` tinyint(1) NOT NULL DEFAULT 1,
  `is_reusable` tinyint(1) NOT NULL DEFAULT 0,
  `requires_cold_chain` tinyint(1) NOT NULL DEFAULT 0,
  `is_controlled` tinyint(1) NOT NULL DEFAULT 0,
  `tracks_expiry` tinyint(1) NOT NULL DEFAULT 0,
  `tracks_serial` tinyint(1) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_items_code_unique` (`code`),
  KEY `store_items_store_category_id_foreign` (`store_category_id`),
  KEY `store_items_hospital_id_store_category_id_index` (`hospital_id`,`store_category_id`),
  KEY `store_items_code_index` (`code`),
  CONSTRAINT `store_items_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `store_items_store_category_id_foreign` FOREIGN KEY (`store_category_id`) REFERENCES `store_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `store_items` WRITE;
/*!40000 ALTER TABLE `store_items` DISABLE KEYS */;
INSERT INTO `store_items` VALUES
(1,1,2,'Surgical Gloves (Size 7.5)','MSG-SG75',NULL,NULL,NULL,NULL,NULL,'Box of 100',NULL,1,4500.00,5500.00,20.00,40.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(2,1,2,'Surgical Gloves (Size 8)','MSG-SG80',NULL,NULL,NULL,NULL,NULL,'Box of 100',NULL,1,4500.00,5500.00,20.00,40.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(3,1,2,'Examination Gloves (L)','MSG-EXL',NULL,NULL,NULL,NULL,NULL,'Box of 100',NULL,1,2500.00,3000.00,30.00,60.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(4,1,2,'Surgical Face Mask','MSK-SFM',NULL,NULL,NULL,NULL,NULL,'Box of 50',NULL,1,1500.00,2000.00,50.00,100.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(5,1,2,'N95 Respirator','MSK-N95',NULL,NULL,NULL,NULL,NULL,'Piece',NULL,1,350.00,500.00,200.00,400.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(6,1,1,'Syringe 5ml Disposable','MSY-5ML',NULL,NULL,NULL,NULL,NULL,'Piece',NULL,1,25.00,50.00,1000.00,2000.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(7,1,1,'Syringe 10ml Disposable','MSY-10ML',NULL,NULL,NULL,NULL,NULL,'Piece',NULL,1,35.00,70.00,1000.00,2000.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(8,1,1,'Needle 21G','MND-21G',NULL,NULL,NULL,NULL,NULL,'Piece',NULL,1,15.00,30.00,2000.00,4000.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(9,1,1,'Needle 23G','MND-23G',NULL,NULL,NULL,NULL,NULL,'Piece',NULL,1,15.00,30.00,2000.00,4000.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(10,1,1,'IV Giving Set','MIV-GS',NULL,NULL,NULL,NULL,NULL,'Piece',NULL,1,250.00,400.00,200.00,400.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(11,1,1,'Cannula 18G','MCA-18G',NULL,NULL,NULL,NULL,NULL,'Piece',NULL,1,200.00,350.00,200.00,400.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(12,1,1,'Cannula 20G','MCA-20G',NULL,NULL,NULL,NULL,NULL,'Piece',NULL,1,200.00,350.00,200.00,400.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(13,1,1,'Gauze Swab 10x10','MGS-1010',NULL,NULL,NULL,NULL,NULL,'Pack of 100',NULL,1,1500.00,2000.00,100.00,200.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(14,1,1,'Cotton Wool 500g','MCW-500',NULL,NULL,NULL,NULL,NULL,'Roll',NULL,1,1200.00,1800.00,50.00,100.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(15,1,1,'Surgical Spirit 1L','MSS-1L',NULL,NULL,NULL,NULL,NULL,'Bottle',NULL,1,800.00,1200.00,50.00,100.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(16,1,1,'Savlon 1L','MSV-1L',NULL,NULL,NULL,NULL,NULL,'Bottle',NULL,1,1500.00,2200.00,30.00,60.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(17,1,1,'Dettol 1L','MDT-1L',NULL,NULL,NULL,NULL,NULL,'Bottle',NULL,1,1800.00,2500.00,30.00,60.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(18,1,5,'Bed Sheet (White)','LBS-WHT',NULL,NULL,NULL,NULL,NULL,'Piece',NULL,1,2500.00,3500.00,50.00,100.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(19,1,5,'Patient Gown','LPG-GWN',NULL,NULL,NULL,NULL,NULL,'Piece',NULL,1,3000.00,4500.00,50.00,100.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(20,1,6,'Diesel (Generator)','FUL-DIE',NULL,NULL,NULL,NULL,NULL,'Litre',NULL,1,1200.00,0.00,500.00,1000.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(21,1,7,'A4 Paper Rim','STN-A4',NULL,NULL,NULL,NULL,NULL,'Rim',NULL,1,5500.00,7000.00,50.00,100.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(22,1,7,'Pen (Blue)','STN-PNB',NULL,NULL,NULL,NULL,NULL,'Box of 50',NULL,1,3500.00,4500.00,20.00,40.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(23,1,4,'Bleach 5L','CLN-BL5',NULL,NULL,NULL,NULL,NULL,'Container',NULL,1,2500.00,3500.00,30.00,60.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(24,1,4,'Detergent 5kg','CLN-DT5',NULL,NULL,NULL,NULL,NULL,'Pack',NULL,1,4500.00,6000.00,20.00,40.00,1,0,0,0,0,0,NULL,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL);
/*!40000 ALTER TABLE `store_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `store_locations`
--

DROP TABLE IF EXISTS `store_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `type` enum('Main Store','Sub-Store','Pharmacy Store','Theatre Store','Lab Store','Ward Store','Emergency Store','Other') NOT NULL DEFAULT 'Main Store',
  `building` varchar(191) DEFAULT NULL,
  `floor` varchar(191) DEFAULT NULL,
  `room` varchar(191) DEFAULT NULL,
  `in_charge_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_locations_code_unique` (`code`),
  KEY `store_locations_department_id_foreign` (`department_id`),
  KEY `store_locations_in_charge_id_foreign` (`in_charge_id`),
  KEY `store_locations_hospital_id_type_index` (`hospital_id`,`type`),
  CONSTRAINT `store_locations_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `store_locations_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `store_locations_in_charge_id_foreign` FOREIGN KEY (`in_charge_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_locations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `store_locations` WRITE;
/*!40000 ALTER TABLE `store_locations` DISABLE KEYS */;
INSERT INTO `store_locations` VALUES
(1,1,1,'Main Store','MS-001','Main Store','Admin Block','Ground','G05',1,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(2,1,2,'Theatre Store','TS-001','Theatre Store','Theatre Block','First','T101',1,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(3,1,6,'Lab Store','LS-001','Lab Store','Lab Block','Ground','L003',1,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(4,1,5,'Emergency Store','ES-001','Emergency Store','A&E Block','Ground','AE001',1,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(5,1,1,'Ward Store (Male Medical)','WS-MMW','Ward Store','Ward Block','Ground','MMW-Store',1,1,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL);
/*!40000 ALTER TABLE `store_locations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `store_movements`
--

DROP TABLE IF EXISTS `store_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `movement_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `store_item_id` bigint(20) unsigned NOT NULL,
  `store_stock_id` bigint(20) unsigned DEFAULT NULL,
  `from_location_id` bigint(20) unsigned DEFAULT NULL,
  `to_location_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` enum('Receipt','Transfer In','Transfer Out','Issue','Return','Adjustment In','Adjustment Out','Disposal','Stock Take') NOT NULL DEFAULT 'Issue',
  `reference_number` varchar(191) DEFAULT NULL,
  `reference_type` varchar(191) DEFAULT NULL,
  `reference_id` bigint(20) unsigned DEFAULT NULL,
  `movement_date` datetime NOT NULL,
  `quantity` decimal(14,2) NOT NULL DEFAULT 0.00,
  `balance_after` decimal(14,2) NOT NULL DEFAULT 0.00,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_movements_movement_number_unique` (`movement_number`),
  KEY `store_movements_hospital_id_foreign` (`hospital_id`),
  KEY `store_movements_store_stock_id_foreign` (`store_stock_id`),
  KEY `store_movements_to_location_id_foreign` (`to_location_id`),
  KEY `store_movements_user_id_foreign` (`user_id`),
  KEY `store_movements_store_item_id_movement_date_index` (`store_item_id`,`movement_date`),
  KEY `store_movements_from_location_id_to_location_id_index` (`from_location_id`,`to_location_id`),
  CONSTRAINT `store_movements_from_location_id_foreign` FOREIGN KEY (`from_location_id`) REFERENCES `store_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `store_movements_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `store_movements_store_item_id_foreign` FOREIGN KEY (`store_item_id`) REFERENCES `store_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `store_movements_store_stock_id_foreign` FOREIGN KEY (`store_stock_id`) REFERENCES `store_stocks` (`id`) ON DELETE SET NULL,
  CONSTRAINT `store_movements_to_location_id_foreign` FOREIGN KEY (`to_location_id`) REFERENCES `store_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `store_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_movements`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `store_movements` WRITE;
/*!40000 ALTER TABLE `store_movements` DISABLE KEYS */;
/*!40000 ALTER TABLE `store_movements` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `store_requisition_items`
--

DROP TABLE IF EXISTS `store_requisition_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_requisition_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_requisition_id` bigint(20) unsigned NOT NULL,
  `store_item_id` bigint(20) unsigned NOT NULL,
  `quantity_requested` decimal(14,2) NOT NULL DEFAULT 0.00,
  `quantity_approved` decimal(14,2) NOT NULL DEFAULT 0.00,
  `quantity_issued` decimal(14,2) NOT NULL DEFAULT 0.00,
  `unit_cost` decimal(14,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `store_requisition_items_store_item_id_foreign` (`store_item_id`),
  KEY `store_requisition_items_store_requisition_id_index` (`store_requisition_id`),
  CONSTRAINT `store_requisition_items_store_item_id_foreign` FOREIGN KEY (`store_item_id`) REFERENCES `store_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `store_requisition_items_store_requisition_id_foreign` FOREIGN KEY (`store_requisition_id`) REFERENCES `store_requisitions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_requisition_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `store_requisition_items` WRITE;
/*!40000 ALTER TABLE `store_requisition_items` DISABLE KEYS */;
INSERT INTO `store_requisition_items` VALUES
(1,1,6,500.00,500.00,0.00,25.00,NULL,'2026-08-04 22:24:38','2026-08-04 22:24:38');
/*!40000 ALTER TABLE `store_requisition_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `store_requisitions`
--

DROP TABLE IF EXISTS `store_requisitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_requisitions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `requisition_number` varchar(191) NOT NULL,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `requesting_department_id` bigint(20) unsigned NOT NULL,
  `requesting_location_id` bigint(20) unsigned DEFAULT NULL,
  `source_location_id` bigint(20) unsigned DEFAULT NULL,
  `requested_by` bigint(20) unsigned NOT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `issued_by` bigint(20) unsigned DEFAULT NULL,
  `received_by` bigint(20) unsigned DEFAULT NULL,
  `required_date` date NOT NULL,
  `approved_date` date DEFAULT NULL,
  `issued_date` date DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `priority` enum('Routine','Urgent','Emergency') NOT NULL DEFAULT 'Routine',
  `purpose` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Draft','Pending','Approved','Partially Issued','Issued','Received','Rejected','Cancelled') NOT NULL DEFAULT 'Draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_requisitions_requisition_number_unique` (`requisition_number`),
  KEY `store_requisitions_hospital_id_foreign` (`hospital_id`),
  KEY `store_requisitions_requesting_location_id_foreign` (`requesting_location_id`),
  KEY `store_requisitions_source_location_id_foreign` (`source_location_id`),
  KEY `store_requisitions_requested_by_foreign` (`requested_by`),
  KEY `store_requisitions_approved_by_foreign` (`approved_by`),
  KEY `store_requisitions_issued_by_foreign` (`issued_by`),
  KEY `store_requisitions_received_by_foreign` (`received_by`),
  KEY `store_requisitions_requesting_department_id_status_index` (`requesting_department_id`,`status`),
  KEY `store_requisitions_requisition_number_index` (`requisition_number`),
  CONSTRAINT `store_requisitions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `store_requisitions_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `store_requisitions_issued_by_foreign` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `store_requisitions_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `store_requisitions_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `store_requisitions_requesting_department_id_foreign` FOREIGN KEY (`requesting_department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `store_requisitions_requesting_location_id_foreign` FOREIGN KEY (`requesting_location_id`) REFERENCES `store_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `store_requisitions_source_location_id_foreign` FOREIGN KEY (`source_location_id`) REFERENCES `store_locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_requisitions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `store_requisitions` WRITE;
/*!40000 ALTER TABLE `store_requisitions` DISABLE KEYS */;
INSERT INTO `store_requisitions` VALUES
(1,'REQ-2026-000001',1,5,4,1,1,1,NULL,NULL,'2026-08-05','2026-08-04',NULL,NULL,'Urgent','Restock A&E for night shift',NULL,'Approved','2026-08-04 22:24:38','2026-08-04 22:24:38',NULL);
/*!40000 ALTER TABLE `store_requisitions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `store_stocks`
--

DROP TABLE IF EXISTS `store_stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_stocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `store_item_id` bigint(20) unsigned NOT NULL,
  `store_location_id` bigint(20) unsigned NOT NULL,
  `batch_number` varchar(191) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `quantity_received` decimal(14,2) NOT NULL DEFAULT 0.00,
  `quantity_remaining` decimal(14,2) NOT NULL DEFAULT 0.00,
  `unit_cost` decimal(14,2) NOT NULL DEFAULT 0.00,
  `received_date` date DEFAULT NULL,
  `reference_number` varchar(191) DEFAULT NULL,
  `is_quarantined` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `store_stocks_hospital_id_foreign` (`hospital_id`),
  KEY `store_stocks_store_item_id_store_location_id_index` (`store_item_id`,`store_location_id`),
  KEY `store_stocks_store_location_id_quantity_remaining_index` (`store_location_id`,`quantity_remaining`),
  KEY `store_stocks_expiry_date_index` (`expiry_date`),
  CONSTRAINT `store_stocks_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `store_stocks_store_item_id_foreign` FOREIGN KEY (`store_item_id`) REFERENCES `store_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `store_stocks_store_location_id_foreign` FOREIGN KEY (`store_location_id`) REFERENCES `store_locations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_stocks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `store_stocks` WRITE;
/*!40000 ALTER TABLE `store_stocks` DISABLE KEYS */;
INSERT INTO `store_stocks` VALUES
(1,1,1,1,'PPE-2024-001',NULL,100.00,75.00,4500.00,'2026-06-20',NULL,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(2,1,4,1,'PPE-2024-002',NULL,200.00,150.00,1500.00,'2026-06-20',NULL,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(3,1,6,1,'SYR-2024-001',NULL,5000.00,4200.00,25.00,'2026-06-20',NULL,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(4,1,7,1,'SYR-2024-002',NULL,3000.00,2500.00,35.00,'2026-06-20',NULL,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(5,1,8,1,'NDL-2024-001',NULL,10000.00,8200.00,15.00,'2026-06-20',NULL,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(6,1,9,1,'NDL-2024-002',NULL,10000.00,7500.00,15.00,'2026-06-20',NULL,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(7,1,10,1,'IVS-2024-001',NULL,500.00,320.00,250.00,'2026-06-20',NULL,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(8,1,11,1,'CAN-2024-001',NULL,500.00,280.00,200.00,'2026-06-20',NULL,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(9,1,12,1,'CAN-2024-002',NULL,500.00,310.00,200.00,'2026-06-20',NULL,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(10,1,18,1,'LNS-2024-001',NULL,200.00,145.00,2500.00,'2026-06-20',NULL,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL),
(11,1,20,1,'FUL-2024-001',NULL,2000.00,850.00,1200.00,'2026-06-20',NULL,0,'2026-08-04 22:24:38','2026-08-04 22:24:38',NULL);
/*!40000 ALTER TABLE `store_stocks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `tax_id` varchar(255) DEFAULT NULL,
  `registration_number` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES
(1,1,'Emzor Pharmaceuticals','EMZ','Mr. Olu Adeyemi','+2348033000001','sales@emzor.com','Lagos, Nigeria',NULL,NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(2,1,'Fidson Healthcare','FID','Mrs. Joy Okeke','+2348033000002','sales@fidson.com','Lagos, Nigeria',NULL,NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(3,1,'May & Baker Nigeria','MNB','Mr. Sam Oke','+2348033000003','orders@mayandbaker.com','Lagos, Nigeria',NULL,NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(4,1,'Evans Medical','EVM','Dr. Grace Bello','+2348033000004','info@evansmedical.com','Lagos, Nigeria',NULL,NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(5,1,'Swipha Nigeria','SWP','Mr. Tunde Salami','+2348033000005','sales@swipha.com','Lagos, Nigeria',NULL,NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(6,1,'Unique Pharmaceuticals','UNQ','Mrs. Aisha Bello','+2348033000006','orders@uniquepharma.com','Lagos, Nigeria',NULL,NULL,1,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL);
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` int(11) NOT NULL DEFAULT 1,
  `department_id` int(11) NOT NULL DEFAULT 1,
  `default_clinic_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `avatar_url` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive','suspended','locked') NOT NULL DEFAULT 'active',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `users_default_clinic_id_foreign` (`default_clinic_id`),
  CONSTRAINT `users_default_clinic_id_foreign` FOREIGN KEY (`default_clinic_id`) REFERENCES `clinics` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,1,1,NULL,'System Administrator','admin@futhlafia.gov.ng',NULL,NULL,NULL,'$2y$12$wqwTJozV5iow7zLW.S4bp.WOYbHd3L/CyEp/fcxDkIcXHpYeiIJ1y','active',NULL,NULL,NULL,'2026-07-31 11:00:00','2026-08-04 21:16:43'),
(2,1,1,NULL,'Okoro Chukwuemeka','dr.okoro@futhlafia.gov.ng',NULL,NULL,NULL,'$2y$12$wqwTJozV5iow7zLW.S4bp.WOYbHd3L/CyEp/fcxDkIcXHpYeiIJ1y','active',NULL,NULL,NULL,'2026-07-31 11:00:00','2026-08-04 21:16:43'),
(3,1,4,NULL,'Adebayo Funmilayo','dr.adebayo@futhlafia.gov.ng',NULL,NULL,NULL,'$2y$12$wqwTJozV5iow7zLW.S4bp.WOYbHd3L/CyEp/fcxDkIcXHpYeiIJ1y','active',NULL,NULL,NULL,'2026-07-31 11:00:00','2026-08-04 21:16:43'),
(4,1,1,NULL,'John Bala','nurse.john@futhlafia.gov.ng',NULL,NULL,NULL,'$2y$12$wqwTJozV5iow7zLW.S4bp.WOYbHd3L/CyEp/fcxDkIcXHpYeiIJ1y','active',NULL,NULL,NULL,'2026-07-31 11:00:00','2026-08-04 21:16:43'),
(5,1,8,NULL,'Musa Ibrahim','pharm.musa@futhlafia.gov.ng',NULL,NULL,NULL,'$2y$12$wqwTJozV5iow7zLW.S4bp.WOYbHd3L/CyEp/fcxDkIcXHpYeiIJ1y','active',NULL,NULL,NULL,'2026-07-31 11:00:00','2026-08-04 21:16:43'),
(6,1,6,NULL,'Aisha Yusuf','lab.aisha@futhlafia.gov.ng',NULL,NULL,NULL,'$2y$12$wqwTJozV5iow7zLW.S4bp.WOYbHd3L/CyEp/fcxDkIcXHpYeiIJ1y','active',NULL,NULL,NULL,'2026-07-31 11:00:00','2026-08-04 21:16:43'),
(7,1,7,NULL,'Tunde Okafor','rad.tunde@futhlafia.gov.ng',NULL,NULL,NULL,'$2y$12$wqwTJozV5iow7zLW.S4bp.WOYbHd3L/CyEp/fcxDkIcXHpYeiIJ1y','active',NULL,NULL,NULL,'2026-07-31 11:00:00','2026-08-04 21:16:43'),
(8,1,1,NULL,'Hauwa Sani','records.hauwa@futhlafia.gov.ng',NULL,NULL,NULL,'$2y$12$wqwTJozV5iow7zLW.S4bp.WOYbHd3L/CyEp/fcxDkIcXHpYeiIJ1y','active',NULL,NULL,NULL,'2026-07-31 11:00:00','2026-08-04 21:16:43'),
(9,1,1,NULL,'David Eze','accounts.david@futhlafia.gov.ng',NULL,NULL,NULL,'$2y$12$wqwTJozV5iow7zLW.S4bp.WOYbHd3L/CyEp/fcxDkIcXHpYeiIJ1y','active',NULL,NULL,NULL,'2026-07-31 11:00:00','2026-08-04 21:16:43'),
(10,1,1,NULL,'Amina Bello','insurance.officer@futhlafia.gov.ng',NULL,NULL,NULL,'$2y$12$wqwTJozV5iow7zLW.S4bp.WOYbHd3L/CyEp/fcxDkIcXHpYeiIJ1y','active',NULL,NULL,NULL,'2026-08-04 21:16:25','2026-08-04 21:16:43');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `vitals`
--

DROP TABLE IF EXISTS `vitals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `vitals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `encounter_id` bigint(20) unsigned DEFAULT NULL,
  `recorded_by` bigint(20) unsigned NOT NULL,
  `recorded_at` datetime NOT NULL,
  `height_cm` decimal(5,2) DEFAULT NULL,
  `weight_kg` decimal(5,2) DEFAULT NULL,
  `bmi` decimal(5,2) DEFAULT NULL,
  `muac_cm` decimal(5,2) DEFAULT NULL,
  `systolic_bp` smallint(5) unsigned DEFAULT NULL,
  `diastolic_bp` smallint(5) unsigned DEFAULT NULL,
  `heart_rate` smallint(5) unsigned DEFAULT NULL,
  `bp_position` enum('Sitting','Standing','Supine','Unknown') NOT NULL DEFAULT 'Unknown',
  `respiratory_rate` smallint(5) unsigned DEFAULT NULL,
  `spo2` smallint(5) unsigned DEFAULT NULL,
  `temperature_c` decimal(4,2) DEFAULT NULL,
  `temperature_route` enum('Oral','Axillary','Tympanic','Rectal','Forehead') NOT NULL DEFAULT 'Oral',
  `pain_score` smallint(5) unsigned DEFAULT NULL,
  `glasgow_coma_score` varchar(8) DEFAULT NULL,
  `blood_glucose_mmol_l` decimal(5,2) DEFAULT NULL,
  `glucose_fasting` enum('Fasting','Random','Postprandial','Unknown') NOT NULL DEFAULT 'Unknown',
  `fetal_heart_rate` smallint(5) unsigned DEFAULT NULL,
  `fundal_height_cm` decimal(5,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vitals_patient_id_recorded_at_index` (`patient_id`,`recorded_at`),
  KEY `vitals_encounter_id_index` (`encounter_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vitals`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `vitals` WRITE;
/*!40000 ALTER TABLE `vitals` DISABLE KEYS */;
INSERT INTO `vitals` VALUES
(1,1,1,1,2,'2026-07-31 12:00:00',165.00,65.00,23.88,NULL,120,80,88,'Sitting',18,98,38.40,'Oral',3,'15',NULL,'Unknown',NULL,NULL,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(2,1,2,2,2,'2026-07-31 12:00:00',165.00,65.00,23.88,NULL,120,80,88,'Sitting',18,98,38.40,'Oral',3,'15',NULL,'Unknown',NULL,NULL,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(3,1,3,3,2,'2026-07-31 12:00:00',165.00,65.00,23.88,NULL,120,80,88,'Sitting',18,98,38.40,'Oral',3,'15',NULL,'Unknown',NULL,NULL,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(4,1,4,4,2,'2026-07-31 12:00:00',165.00,65.00,23.88,NULL,120,80,88,'Sitting',18,98,38.40,'Oral',3,'15',NULL,'Unknown',NULL,NULL,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(5,1,5,5,2,'2026-07-31 12:00:00',165.00,65.00,23.88,NULL,120,80,88,'Sitting',18,98,38.40,'Oral',3,'15',NULL,'Unknown',NULL,NULL,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL),
(6,1,6,6,2,'2026-07-31 12:00:00',165.00,65.00,23.88,NULL,120,80,88,'Sitting',18,98,38.40,'Oral',3,'15',NULL,'Unknown',NULL,NULL,NULL,'2026-07-31 11:00:00','2026-07-31 11:00:00',NULL);
/*!40000 ALTER TABLE `vitals` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `wards`
--

DROP TABLE IF EXISTS `wards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wards` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) unsigned NOT NULL,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `type` enum('General','Male Medical','Female Medical','Male Surgical','Female Surgical','Paediatrics','Maternity','Antenatal','Postnatal','ICU','HDU','NICU','Emergency','Isolation','Private','Psychiatry','Other') NOT NULL DEFAULT 'General',
  `gender_restriction` enum('Any','Male Only','Female Only','Paediatrics Only') NOT NULL DEFAULT 'Any',
  `total_beds` int(11) NOT NULL DEFAULT 0,
  `floor` varchar(191) DEFAULT NULL,
  `wing` varchar(191) DEFAULT NULL,
  `daily_room_rate` decimal(14,2) NOT NULL DEFAULT 0.00,
  `in_charge_id` bigint(20) unsigned DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Active','Closed','Maintenance') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wards_code_unique` (`code`),
  KEY `wards_department_id_foreign` (`department_id`),
  KEY `wards_in_charge_id_foreign` (`in_charge_id`),
  KEY `wards_hospital_id_status_index` (`hospital_id`,`status`),
  CONSTRAINT `wards_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `wards_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wards_in_charge_id_foreign` FOREIGN KEY (`in_charge_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wards`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `wards` WRITE;
/*!40000 ALTER TABLE `wards` DISABLE KEYS */;
INSERT INTO `wards` VALUES
(1,1,1,'Male Medical Ward','MMW','Male Medical','Male Only',20,'Ground','East',5000.00,NULL,NULL,'Active','2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(2,1,1,'Female Medical Ward','FMW','Female Medical','Female Only',20,'Ground','West',5000.00,NULL,NULL,'Active','2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(3,1,2,'Male Surgical Ward','MSW','Male Surgical','Male Only',15,'First','East',7000.00,NULL,NULL,'Active','2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(4,1,2,'Female Surgical Ward','FSW','Female Surgical','Female Only',15,'First','West',7000.00,NULL,NULL,'Active','2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(5,1,3,'Paediatrics Ward','PAED-W','Paediatrics','Paediatrics Only',18,'Second','East',6000.00,NULL,NULL,'Active','2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(6,1,4,'Maternity Ward','MAT-W','Maternity','Female Only',22,'Second','West',8000.00,NULL,NULL,'Active','2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(7,1,5,'Intensive Care Unit (ICU)','ICU','ICU','Any',6,'Ground','Central',50000.00,NULL,NULL,'Active','2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(8,1,5,'High Dependency Unit (HDU)','HDU','HDU','Any',8,'Ground','Central',25000.00,NULL,NULL,'Active','2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(9,1,1,'Private Ward A','PRIV-A','Private','Any',10,'Third','East',20000.00,NULL,NULL,'Active','2026-08-04 21:50:05','2026-08-04 21:50:05',NULL),
(10,1,5,'Emergency Observation Ward','ER-OBS','Emergency','Any',12,'Ground','Central',3000.00,NULL,NULL,'Active','2026-08-04 21:50:05','2026-08-04 21:50:05',NULL);
/*!40000 ALTER TABLE `wards` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Dumping routines for database 'futh_emr'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-08-04 21:38:26
