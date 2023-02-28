-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2023 at 10:49 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `onekey`
--

-- --------------------------------------------------------

--
-- Table structure for table `calls`
--

CREATE TABLE `calls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(225) DEFAULT NULL,
  `last_name` varchar(225) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone_number` varchar(225) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `email` varchar(225) DEFAULT NULL,
  `follow_up_date` varchar(10) DEFAULT NULL,
  `follow_up_notes` text DEFAULT NULL,
  `status` smallint(2) DEFAULT NULL,
  `ag` tinyint(1) NOT NULL DEFAULT 0,
  `package` smallint(2) DEFAULT NULL,
  `last_contact` varchar(10) DEFAULT NULL,
  `age` varchar(5) DEFAULT NULL,
  `gpa` varchar(5) DEFAULT NULL,
  `last_status_date` varchar(10) DEFAULT NULL,
  `last_status_notes` text DEFAULT NULL,
  `results` smallint(2) DEFAULT NULL,
  `priority` smallint(2) DEFAULT NULL,
  `cancel_reason` smallint(2) DEFAULT NULL,
  `feedbacks` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `sections` smallint(6) DEFAULT NULL,
  `user_id` int(20) DEFAULT NULL,
  `memo` text DEFAULT NULL,
  `f_results` smallint(2) DEFAULT NULL,
  `referred_by` text DEFAULT NULL,
  `first_contact` varchar(225) DEFAULT NULL,
  `nationality` varchar(225) DEFAULT NULL,
  `goal` int(10) DEFAULT NULL,
  `immigration_filling` varchar(225) DEFAULT NULL,
  `method_filling` varchar(225) DEFAULT NULL,
  `package_explain` text DEFAULT NULL,
  `confirmed_gpa` varchar(5) DEFAULT NULL,
  `applying_for` int(10) DEFAULT NULL,
  `assigned_to` bigint(20) DEFAULT NULL,
  `want_to_study` int(10) DEFAULT NULL,
  `marital_status` int(10) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `agreement_sent` smallint(5) DEFAULT NULL,
  `agree_date_sent` varchar(10) DEFAULT NULL,
  `file_name` varchar(225) DEFAULT NULL,
  `degree` smallint(3) DEFAULT NULL,
  `field_study` varchar(225) DEFAULT NULL,
  `call_schedule_date` varchar(225) DEFAULT NULL,
  `call_schedule_time` varchar(225) DEFAULT NULL,
  `eng_test` smallint(2) DEFAULT NULL,
  `eng_test_score` smallint(2) DEFAULT NULL,
  `next_step` text DEFAULT NULL,
  `payment_method` smallint(3) DEFAULT NULL,
  `agreed_to_pay` tinyint(1) DEFAULT NULL,
  `agreed_to_signed` tinyint(1) DEFAULT NULL,
  `agreement_signed_date` varchar(225) DEFAULT NULL,
  `cancel_note` text DEFAULT NULL,
  `first_call_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calls`
--
ALTER TABLE `calls`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calls`
--
ALTER TABLE `calls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
