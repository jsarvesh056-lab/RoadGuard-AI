-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2026 at 03:13 PM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `roadguard_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_detection_events`
--

CREATE TABLE `ai_detection_events` (
  `detection_id` bigint(20) UNSIGNED NOT NULL,
  `incident_id` bigint(20) UNSIGNED DEFAULT NULL,
  `device_id` bigint(20) UNSIGNED DEFAULT NULL,
  `detection_type` varchar(100) NOT NULL,
  `confidence` decimal(6,5) DEFAULT NULL,
  `model_name` varchar(100) DEFAULT NULL,
  `model_version` varchar(50) DEFAULT NULL,
  `frame_path` varchar(500) DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `is_simulated` tinyint(1) NOT NULL DEFAULT 0,
  `detected_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `alert_id` bigint(20) UNSIGNED NOT NULL,
  `incident_id` bigint(20) UNSIGNED NOT NULL,
  `recipient_user_id` int(10) UNSIGNED DEFAULT NULL,
  `alert_type` enum('dashboard','email','sms','push','hardware_buzzer','other') NOT NULL DEFAULT 'dashboard',
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `delivery_status` enum('pending','sent','delivered','failed','read') NOT NULL DEFAULT 'pending',
  `is_simulated` tinyint(1) NOT NULL DEFAULT 0,
  `sent_at` datetime DEFAULT NULL,
  `read_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `device_id` bigint(20) UNSIGNED NOT NULL,
  `device_code` varchar(50) NOT NULL,
  `device_name` varchar(100) NOT NULL,
  `device_type` enum('esp32','esp32_cam','raspberry_pi','other') NOT NULL DEFAULT 'esp32',
  `installation_location` varchar(255) DEFAULT NULL,
  `device_status` enum('active','inactive','maintenance') NOT NULL DEFAULT 'inactive',
  `last_seen_at` datetime DEFAULT NULL,
  `firmware_version` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`device_id`, `device_code`, `device_name`, `device_type`, `installation_location`, `device_status`, `last_seen_at`, `firmware_version`, `created_at`, `updated_at`) VALUES
(1, 'RG-TEST-001', 'RoadGuard Test Device', 'esp32', NULL, 'inactive', NULL, 'prototype-0.1', '2026-10-31 13:24:38', '2026-10-31 13:24:38');

-- --------------------------------------------------------

--
-- Table structure for table `device_heartbeats`
--

CREATE TABLE `device_heartbeats` (
  `heartbeat_id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL,
  `network_status` varchar(30) DEFAULT NULL,
  `battery_voltage` decimal(6,3) DEFAULT NULL,
  `signal_strength` int(11) DEFAULT NULL,
  `reported_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `incident_id` bigint(20) UNSIGNED NOT NULL,
  `incident_code` varchar(40) NOT NULL,
  `reported_by` int(10) UNSIGNED DEFAULT NULL,
  `incident_type` enum('accident','road_hazard','obstacle','vehicle_breakdown','other') NOT NULL DEFAULT 'accident',
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `severity` enum('unknown','low','medium','high','critical') NOT NULL DEFAULT 'unknown',
  `incident_source` enum('manual','ai','hardware','test') NOT NULL DEFAULT 'manual',
  `incident_status` enum('reported','under_review','verified','dispatched','resolved','false_alarm') NOT NULL DEFAULT 'reported',
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `location_accuracy_m` decimal(8,2) DEFAULT NULL,
  `address_text` varchar(500) DEFAULT NULL,
  `incident_time` datetime DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `is_simulated` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Table structure for table `incident_status_history`
--

CREATE TABLE `incident_status_history` (
  `history_id` bigint(20) UNSIGNED NOT NULL,
  `incident_id` bigint(20) UNSIGNED NOT NULL,
  `changed_by` int(10) UNSIGNED DEFAULT NULL,
  `previous_status` varchar(30) DEFAULT NULL,
  `new_status` varchar(30) NOT NULL,
  `notes` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incident_status_reference`
--

CREATE TABLE `incident_status_reference` (
  `status_code` varchar(30) NOT NULL,
  `status_label` varchar(60) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_status_reference`
--

INSERT INTO `incident_status_reference` (`status_code`, `status_label`, `description`) VALUES
('dispatched', 'Assistance Dispatched', 'Response action initiated'),
('false_alarm', 'False Alarm', 'Report determined to be false'),
('reported', 'Reported', 'New incident report received'),
('resolved', 'Resolved', 'Incident marked resolved'),
('under_review', 'Under Review', 'Awaiting verification'),
('verified', 'Verified', 'Incident reviewed and verified');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `incident_id` bigint(20) UNSIGNED NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `accuracy_m` decimal(8,2) DEFAULT NULL,
  `altitude_m` decimal(9,2) DEFAULT NULL,
  `speed_kmh` decimal(7,2) DEFAULT NULL,
  `heading_deg` decimal(6,2) DEFAULT NULL,
  `source` enum('browser','gps_module','hardware','test') NOT NULL DEFAULT 'browser',
  `recorded_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Table structure for table `sensor_readings`
--

CREATE TABLE `sensor_readings` (
  `reading_id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL,
  `incident_id` bigint(20) UNSIGNED DEFAULT NULL,
  `acceleration_x` float DEFAULT NULL,
  `acceleration_y` float DEFAULT NULL,
  `acceleration_z` float DEFAULT NULL,
  `gyroscope_x` float DEFAULT NULL,
  `gyroscope_y` float DEFAULT NULL,
  `gyroscope_z` float DEFAULT NULL,
  `temperature_c` decimal(5,2) DEFAULT NULL,
  `battery_voltage` decimal(6,3) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `event_type` varchar(60) DEFAULT NULL,
  `is_simulated` tinyint(1) NOT NULL DEFAULT 0,
  `recorded_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','admin','responder') NOT NULL DEFAULT 'user',
  `account_status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_detection_events`
--
ALTER TABLE `ai_detection_events`
  ADD PRIMARY KEY (`detection_id`),
  ADD KEY `fk_ai_incident` (`incident_id`),
  ADD KEY `fk_ai_device` (`device_id`),
  ADD KEY `idx_ai_detected_at` (`detected_at`),
  ADD KEY `idx_ai_detection_type` (`detection_type`);

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`alert_id`),
  ADD KEY `fk_alerts_recipient` (`recipient_user_id`),
  ADD KEY `idx_alerts_incident` (`incident_id`),
  ADD KEY `idx_alerts_status` (`delivery_status`),
  ADD KEY `idx_alerts_created` (`created_at`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`device_id`),
  ADD UNIQUE KEY `device_code` (`device_code`),
  ADD KEY `idx_devices_status` (`device_status`);

--
-- Indexes for table `device_heartbeats`
--
ALTER TABLE `device_heartbeats`
  ADD PRIMARY KEY (`heartbeat_id`),
  ADD KEY `idx_heartbeats_device_time` (`device_id`,`reported_at`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`incident_id`),
  ADD UNIQUE KEY `incident_code` (`incident_code`),
  ADD KEY `fk_incidents_reporter` (`reported_by`),
  ADD KEY `idx_incident_status` (`incident_status`),
  ADD KEY `idx_incident_type` (`incident_type`),
  ADD KEY `idx_incident_time` (`incident_time`),
  ADD KEY `idx_incident_location` (`latitude`,`longitude`),
  ADD KEY `idx_incident_created` (`created_at`);

--
-- Indexes for table `incident_status_history`
--
ALTER TABLE `incident_status_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `fk_history_user` (`changed_by`),
  ADD KEY `idx_history_incident` (`incident_id`),
  ADD KEY `idx_history_changed` (`changed_at`);

--
-- Indexes for table `incident_status_reference`
--
ALTER TABLE `incident_status_reference`
  ADD PRIMARY KEY (`status_code`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`),
  ADD KEY `idx_locations_incident` (`incident_id`),
  ADD KEY `idx_locations_recorded` (`recorded_at`);

--
-- Indexes for table `sensor_readings`
--
ALTER TABLE `sensor_readings`
  ADD PRIMARY KEY (`reading_id`),
  ADD KEY `idx_readings_device_time` (`device_id`,`recorded_at`),
  ADD KEY `idx_readings_incident` (`incident_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_detection_events`
--
ALTER TABLE `ai_detection_events`
  MODIFY `detection_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `alert_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `device_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `device_heartbeats`
--
ALTER TABLE `device_heartbeats`
  MODIFY `heartbeat_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `incident_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incident_status_history`
--
ALTER TABLE `incident_status_history`
  MODIFY `history_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sensor_readings`
--
ALTER TABLE `sensor_readings`
  MODIFY `reading_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ai_detection_events`
--
ALTER TABLE `ai_detection_events`
  ADD CONSTRAINT `fk_ai_device` FOREIGN KEY (`device_id`) REFERENCES `devices` (`device_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ai_incident` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`incident_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `fk_alerts_incident` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`incident_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_alerts_recipient` FOREIGN KEY (`recipient_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `device_heartbeats`
--
ALTER TABLE `device_heartbeats`
  ADD CONSTRAINT `fk_heartbeats_device` FOREIGN KEY (`device_id`) REFERENCES `devices` (`device_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `incidents`
--
ALTER TABLE `incidents`
  ADD CONSTRAINT `fk_incidents_reporter` FOREIGN KEY (`reported_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `incident_status_history`
--
ALTER TABLE `incident_status_history`
  ADD CONSTRAINT `fk_history_incident` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`incident_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_history_user` FOREIGN KEY (`changed_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `fk_locations_incident` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`incident_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sensor_readings`
--
ALTER TABLE `sensor_readings`
  ADD CONSTRAINT `fk_readings_device` FOREIGN KEY (`device_id`) REFERENCES `devices` (`device_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_readings_incident` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`incident_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
