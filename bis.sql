-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2026 at 02:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bis`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `resident_id` bigint(20) UNSIGNED NOT NULL,
  `appointment_type` varchar(255) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `status` enum('pending','approved','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `action` text NOT NULL,
  `module` varchar(255) NOT NULL,
  `record_id` int(11) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `module`, `record_id`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-19 23:57:02', '2026-02-19 23:57:02'),
(2, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-19 23:57:11', '2026-02-19 23:57:11'),
(3, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-19 23:59:18', '2026-02-19 23:59:18'),
(4, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-20 00:00:05', '2026-02-20 00:00:05'),
(5, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-20 00:02:50', '2026-02-20 00:02:50'),
(6, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-20 00:05:12', '2026-02-20 00:05:12'),
(7, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-20 00:05:38', '2026-02-20 00:05:38'),
(8, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-20 00:05:46', '2026-02-20 00:05:46'),
(9, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-20 00:08:37', '2026-02-20 00:08:37'),
(10, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-20 17:23:47', '2026-02-20 17:23:47'),
(11, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-22 16:09:14', '2026-02-22 16:09:14'),
(12, 2, 'Deleted certificate request: CTRL-20260221-00002', 'Certificates', NULL, '127.0.0.1', '2026-02-22 16:26:16', '2026-02-22 16:26:16'),
(13, 2, 'Approved certificate request: CTRL-20260221-00001', 'Certificates', 1, '127.0.0.1', '2026-02-22 16:26:21', '2026-02-22 16:26:21'),
(14, 2, 'Released certificate: CTRL-20260221-00001', 'Certificates', 1, '127.0.0.1', '2026-02-22 16:27:20', '2026-02-22 16:27:20'),
(15, 2, 'Created certificate request: CTRL-20260223-00003', 'Certificates', 4, '127.0.0.1', '2026-02-22 16:34:56', '2026-02-22 16:34:56'),
(16, 2, 'Rejected certificate request: CTRL-20260223-00003', 'Certificates', 4, '127.0.0.1', '2026-02-22 16:35:18', '2026-02-22 16:35:18'),
(17, 2, 'Rejected certificate request: CTRL-20260223-00002', 'Certificates', 3, '127.0.0.1', '2026-02-22 16:35:25', '2026-02-22 16:35:25'),
(18, 2, 'Deleted certificate request: CTRL-20260223-00002', 'Certificates', NULL, '127.0.0.1', '2026-02-22 16:35:30', '2026-02-22 16:35:30'),
(19, 2, 'Deleted certificate request: CTRL-20260223-00003', 'Certificates', NULL, '127.0.0.1', '2026-02-22 16:35:35', '2026-02-22 16:35:35'),
(20, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-22 19:02:16', '2026-02-22 19:02:16'),
(21, 2, 'Created certificate type: Barangay Certificate', 'Certificate Types', 3, '127.0.0.1', '2026-02-22 19:02:38', '2026-02-22 19:02:38'),
(22, 2, 'Updated certificate type: Barangay Certificate', 'Certificate Types', 3, '127.0.0.1', '2026-02-22 19:02:54', '2026-02-22 19:02:54'),
(23, 2, 'Created certificate request: CTRL-20260223-00002', 'Certificates', 5, '127.0.0.1', '2026-02-22 19:03:25', '2026-02-22 19:03:25'),
(24, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-22 19:50:54', '2026-02-22 19:50:54'),
(25, 2, 'Approved certificate request: CTRL-20260223-00002', 'Certificates', 5, '127.0.0.1', '2026-02-22 19:51:05', '2026-02-22 19:51:05'),
(26, 2, 'Created blotter case: Case #1', 'Blotters', 1, '127.0.0.1', '2026-02-22 19:51:22', '2026-02-22 19:51:22'),
(27, 2, 'Updated blotter case: Case #1', 'Blotters', 1, '127.0.0.1', '2026-02-22 19:51:52', '2026-02-22 19:51:52'),
(28, 2, 'Deleted blotter case: Case #000001', 'Blotters', NULL, '127.0.0.1', '2026-02-22 19:51:55', '2026-02-22 19:51:55'),
(29, 2, 'Created blotter case: Case #2', 'Blotters', 2, '127.0.0.1', '2026-02-22 19:52:11', '2026-02-22 19:52:11'),
(30, 2, 'Updated blotter case: Case #2', 'Blotters', 2, '127.0.0.1', '2026-02-22 19:52:54', '2026-02-22 19:52:54'),
(31, 2, 'Updated blotter case: Case #2', 'Blotters', 2, '127.0.0.1', '2026-02-22 19:53:08', '2026-02-22 19:53:08'),
(32, 2, 'Updated blotter case: Case #2', 'Blotters', 2, '127.0.0.1', '2026-02-22 19:53:23', '2026-02-22 19:53:23'),
(33, 2, 'Updated blotter case: Case #2', 'Blotters', 2, '127.0.0.1', '2026-02-22 19:54:01', '2026-02-22 19:54:01'),
(34, 2, 'Updated blotter case: Case #2', 'Blotters', 2, '127.0.0.1', '2026-02-22 19:54:06', '2026-02-22 19:54:06'),
(35, 2, 'Updated blotter case: Case #2', 'Blotters', 2, '127.0.0.1', '2026-02-22 19:54:28', '2026-02-22 19:54:28'),
(36, 2, 'Updated blotter case: Case #2', 'Blotters', 2, '127.0.0.1', '2026-02-22 19:55:24', '2026-02-22 19:55:24'),
(37, 2, 'Changed blotter case status: Case #2 from ongoing to filed', 'Blotters', 2, '127.0.0.1', '2026-02-22 19:56:25', '2026-02-22 19:56:25'),
(38, 2, 'Changed blotter case status: Case #2 from filed to ongoing', 'Blotters', 2, '127.0.0.1', '2026-02-22 19:56:33', '2026-02-22 19:56:33'),
(39, 2, 'Changed blotter case status: Case #2 from ongoing to settled', 'Blotters', 2, '127.0.0.1', '2026-02-22 19:57:00', '2026-02-22 19:57:00'),
(40, 2, 'Deleted blotter case: Case #000002', 'Blotters', NULL, '127.0.0.1', '2026-02-22 19:57:11', '2026-02-22 19:57:11'),
(41, 2, 'Created blotter case: Case #3', 'Blotters', 3, '127.0.0.1', '2026-02-22 19:58:33', '2026-02-22 19:58:33'),
(42, 2, 'User logged out', 'Authentication', NULL, '127.0.0.1', '2026-02-22 19:59:37', '2026-02-22 19:59:37'),
(43, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-22 19:59:59', '2026-02-22 19:59:59'),
(44, 2, 'User logged out', 'Authentication', NULL, '127.0.0.1', '2026-02-22 20:00:05', '2026-02-22 20:00:05'),
(45, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-22 20:58:37', '2026-02-22 20:58:37'),
(46, 2, 'User logged out', 'Authentication', NULL, '127.0.0.1', '2026-02-22 21:26:34', '2026-02-22 21:26:34'),
(47, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-22 21:26:52', '2026-02-22 21:26:52'),
(48, 2, 'User logged out', 'Authentication', NULL, '127.0.0.1', '2026-02-22 21:42:27', '2026-02-22 21:42:27'),
(49, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-22 21:42:36', '2026-02-22 21:42:36'),
(50, 2, 'User logged out', 'Authentication', NULL, '127.0.0.1', '2026-02-22 21:43:18', '2026-02-22 21:43:18'),
(51, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-22 21:43:26', '2026-02-22 21:43:26'),
(52, 2, 'User logged out', 'Authentication', NULL, '127.0.0.1', '2026-02-22 21:55:44', '2026-02-22 21:55:44'),
(53, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-22 21:55:53', '2026-02-22 21:55:53'),
(54, 2, 'User logged out', 'Authentication', NULL, '127.0.0.1', '2026-02-22 22:20:36', '2026-02-22 22:20:36'),
(55, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-22 22:22:04', '2026-02-22 22:22:04'),
(56, 2, 'Created user: johnrey', 'Users', 3, '127.0.0.1', '2026-02-22 22:45:11', '2026-02-22 22:45:11'),
(57, 2, 'Updated user: johnrey', 'Users', 3, '127.0.0.1', '2026-02-22 22:45:38', '2026-02-22 22:45:38'),
(58, 2, 'User logged out', 'Authentication', NULL, '127.0.0.1', '2026-02-22 22:45:52', '2026-02-22 22:45:52'),
(59, 3, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-22 22:46:00', '2026-02-22 22:46:00'),
(60, 3, 'Updated resident: jo R. fa Jr.', 'Residents', 8, '127.0.0.1', '2026-02-22 23:03:12', '2026-02-22 23:03:12'),
(61, 3, 'Updated resident: jo R. fa Jr.', 'Residents', 8, '127.0.0.1', '2026-02-22 23:12:25', '2026-02-22 23:12:25'),
(62, 3, 'Updated user: johnrey', 'Users', 3, '127.0.0.1', '2026-02-22 23:13:10', '2026-02-22 23:13:10'),
(63, 3, 'Updated resident: jo R. fa Jr.', 'Residents', 8, '127.0.0.1', '2026-02-22 23:13:29', '2026-02-22 23:13:29'),
(64, 3, 'Updated resident: jo R. fa Jr.', 'Residents', 8, '127.0.0.1', '2026-02-22 23:18:04', '2026-02-22 23:18:04'),
(65, 3, 'User logged out', 'Authentication', NULL, '127.0.0.1', '2026-02-22 23:46:16', '2026-02-22 23:46:16'),
(66, 2, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-22 23:46:40', '2026-02-22 23:46:40'),
(67, 2, 'User logged out', 'Authentication', NULL, '127.0.0.1', '2026-02-22 23:47:33', '2026-02-22 23:47:33'),
(68, 3, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-22 23:47:49', '2026-02-22 23:47:49'),
(69, 3, 'Updated user: admin', 'Users', 2, '127.0.0.1', '2026-02-22 23:54:12', '2026-02-22 23:54:12'),
(70, 3, 'User logged out', 'Authentication', NULL, '127.0.0.1', '2026-02-23 00:00:08', '2026-02-23 00:00:08'),
(71, 2, 'User logged in', 'Authentication', NULL, '192.168.0.198', '2026-02-23 00:17:22', '2026-02-23 00:17:22'),
(72, 2, 'User logged out', 'Authentication', NULL, '192.168.0.198', '2026-02-23 00:17:30', '2026-02-23 00:17:30'),
(73, 3, 'User logged in', 'Authentication', NULL, '192.168.0.198', '2026-02-23 00:17:46', '2026-02-23 00:17:46'),
(74, 3, 'User logged out', 'Authentication', NULL, '192.168.0.198', '2026-02-23 00:21:31', '2026-02-23 00:21:31'),
(75, 3, 'User logged in', 'Authentication', NULL, '192.168.0.198', '2026-02-23 00:25:52', '2026-02-23 00:25:52'),
(76, 3, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-23 00:49:04', '2026-02-23 00:49:04'),
(77, 3, 'User logged in', 'Authentication', NULL, '127.0.0.1', '2026-02-23 15:59:34', '2026-02-23 15:59:34');

-- --------------------------------------------------------

--
-- Table structure for table `blotter_records`
--

CREATE TABLE `blotter_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `complainant_id` bigint(20) UNSIGNED NOT NULL,
  `respondent_id` bigint(20) UNSIGNED NOT NULL,
  `incident_date` datetime NOT NULL,
  `incident_location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('ongoing','settled','filed','dismissed') NOT NULL DEFAULT 'ongoing',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blotter_records`
--

INSERT INTO `blotter_records` (`id`, `complainant_id`, `respondent_id`, `incident_date`, `incident_location`, `description`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(3, 8, 3, '2026-02-24 11:58:00', 'putian', 'fjyffufytwsesfsgrrstrstwststr', 'ongoing', 2, '2026-02-22 19:58:33', '2026-02-22 19:58:33');

-- --------------------------------------------------------

--
-- Table structure for table `certificate_requests`
--

CREATE TABLE `certificate_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `resident_id` bigint(20) UNSIGNED NOT NULL,
  `certificate_type_id` bigint(20) UNSIGNED NOT NULL,
  `purpose` text DEFAULT NULL,
  `control_number` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected','released') NOT NULL DEFAULT 'pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `released_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificate_requests`
--

INSERT INTO `certificate_requests` (`id`, `resident_id`, `certificate_type_id`, `purpose`, `control_number`, `status`, `requested_at`, `approved_by`, `approved_at`, `released_at`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'financial', 'CTRL-20260221-00001', 'released', '2026-02-21 00:05:57', 2, '2026-02-22 16:26:21', '2026-02-22 16:27:20', '2026-02-21 00:05:57', '2026-02-22 16:27:20'),
(5, 3, 3, 'rwrwr', 'CTRL-20260223-00002', 'approved', '2026-02-22 19:03:25', 2, '2026-02-22 19:51:05', NULL, '2026-02-22 19:03:25', '2026-02-22 19:51:05');

-- --------------------------------------------------------

--
-- Table structure for table `certificate_types`
--

CREATE TABLE `certificate_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `certificate_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificate_types`
--

INSERT INTO `certificate_types` (`id`, `certificate_name`, `description`, `fee`, `created_at`, `updated_at`) VALUES
(1, 'Barangay Clearance', 'financial', 150.00, '2026-02-21 00:04:20', '2026-02-21 00:04:20'),
(3, 'Barangay Certificate', NULL, 100.00, '2026-02-22 19:02:38', '2026-02-22 19:02:54');

-- --------------------------------------------------------

--
-- Table structure for table `households`
--

CREATE TABLE `households` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `household_number` varchar(255) NOT NULL,
  `head_resident_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purok` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `households`
--

INSERT INTO `households` (`id`, `household_number`, `head_resident_id`, `purok`, `address`, `created_at`, `updated_at`) VALUES
(3, '1', 3, 'Purok 1', 'xzxz', '2026-02-20 18:25:49', '2026-02-20 18:25:49'),
(4, '2', 3, 'Purok 1', 'gtwetgwt', '2026-02-20 18:59:28', '2026-02-20 18:59:28');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_02_20_000001_create_roles_table', 1),
(2, '2026_02_20_000002_create_users_table', 2),
(3, '2026_02_20_000003_create_certificate_types_table', 3),
(4, '2026_02_20_000004_create_residents_table', 4),
(5, '2026_02_20_000005_create_households_table', 5),
(6, '2026_02_20_000006_create_officials_table', 6),
(7, '2026_02_20_000007_create_certificate_requests_table', 7),
(8, '2026_02_20_000008_create_payments_table', 8),
(9, '2026_02_20_000009_create_blotter_records_table', 9),
(10, '2026_02_20_000010_create_audit_logs_table', 10),
(11, '2026_02_20_000011_create_notifications_table', 11),
(12, '2026_02_20_000012_create_appointments_table', 12),
(13, '2026_02_20_000013_create_system_settings_table', 13),
(14, '2026_02_20_000014_create_sessions_table', 14),
(15, '2026_02_20_000016_add_all_foreign_keys', 15);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `type`, `is_read`, `created_at`, `updated_at`) VALUES
(4, 2, 'New certificate request from John R. Ybanez', 'certificate_request', 0, '2026-02-22 16:34:56', '2026-02-22 16:34:56'),
(5, 2, 'New certificate request from John R. Ybanez', 'certificate_request', 0, '2026-02-22 19:03:25', '2026-02-22 19:03:25');

-- --------------------------------------------------------

--
-- Table structure for table `officials`
--

CREATE TABLE `officials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `resident_id` bigint(20) UNSIGNED NOT NULL,
  `position` varchar(255) NOT NULL,
  `term_start` date NOT NULL,
  `term_end` date DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `officials`
--

INSERT INTO `officials` (`id`, `resident_id`, `position`, `term_start`, `term_end`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'Barangay Captain', '2026-02-01', '2026-02-28', 'active', '2026-02-20 18:09:17', '2026-02-20 18:09:17'),
(2, 3, 'Barangay Secretary', '2026-02-23', '2026-03-01', 'active', '2026-02-22 16:32:14', '2026-02-22 16:32:14');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `certificate_request_id` bigint(20) UNSIGNED NOT NULL,
  `or_number` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','gcash','maya','bank') NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `received_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `resident_code` varchar(255) NOT NULL,
  `household_id` bigint(20) UNSIGNED DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `gender` enum('male','female') NOT NULL,
  `birthdate` date NOT NULL,
  `civil_status` enum('single','married','widowed','separated') NOT NULL,
  `nationality` varchar(255) NOT NULL DEFAULT 'Filipino',
  `voter_status` tinyint(1) NOT NULL DEFAULT 0,
  `occupation` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_pwd` tinyint(1) NOT NULL DEFAULT 0,
  `is_senior` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive','deceased') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`id`, `resident_code`, `household_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `gender`, `birthdate`, `civil_status`, `nationality`, `voter_status`, `occupation`, `contact_number`, `email`, `photo`, `is_pwd`, `is_senior`, `status`, `created_at`, `updated_at`) VALUES
(3, 'RES-2026-00001', NULL, 'John', 'Rey', 'Ybanez', NULL, 'male', '2026-02-02', 'single', 'Filipino', 1, 'Teacher', '0804284721942', 'johnreyybanez@gmail.com', NULL, 0, 0, 'active', '2026-02-20 18:08:47', '2026-02-20 18:08:47'),
(7, 'RES-2026-00002', 4, 'John rey', 'Rey', 'Ybañez', NULL, 'male', '2024-01-03', 'single', 'Filipino', 1, 'Retired', '0804284721942', 'admin@example.com', NULL, 0, 0, 'active', '2026-02-20 19:43:12', '2026-02-20 19:43:12'),
(8, 'RES-2026-00003', 3, 'jo', 'Rey', 'fa', 'Jr.', 'male', '2026-02-01', 'married', 'Filipino', 0, 'Retired', '0804284721942', 'admin@barangay.com', 'residents/photos/I31bTwy6JTzdz8BkIhfL6LKDEMNVpZuHKsjVNvcE.jpg', 1, 0, 'active', '2026-02-20 19:55:00', '2026-02-22 23:18:04');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Administrator role with full access', '2026-02-19 23:56:26', '2026-02-19 23:56:26');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0EPIJQfk2ZCIE1zwmYBDy1DrjQc4rTmRk0utQPg0', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoicFhyVEcyTlI2eDBvMmF1VHNrRmE2MXhwWW54TnpHVlRtUkdFQ1djNSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1771574405),
('2LUVgRlbJO48wtra1uRMkvSWJGum2UinI26RfYex', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibXgybXpvSFRTc2tYNWFBUXBSOEdwa1BFTHhOR2FodGFKOUlZTGlZYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771575511),
('5eqY7ChDWhmj0rRN1uPj3dzmhCuhz5ECCAJO9V1l', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaFlKQkZFeWFiclFhZDVjTkhaYTQxZ1VRYzdSM3lUbjRFbzFMSnZBdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771577053),
('7CISkiCKJc63Mn83CcAxBz4TpColHUt2ZqJrsgka', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYjlHeThjYUdHQ3NsQ2VUc3FoNFFxZ3FuU0dSUVdTdm1Hd2pFYW16RSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1771574917),
('8ffH3yP39PRscy01CD0bmVKfdlnZtNyGXPVowrQY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQWc1V096Z1Zob0NtY1FiaHo0MVl2SFdHbm1KZFBaejBhMUd5ZTROZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771575224),
('9emnHdcUZcJ3vRCwbi8ziAsFyq4vOrScx5jVZaOl', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQnhueEFpOXp4NVNuWUVTeDNLUlRJSTdTcVZOaDFkRWt3SUd0ZFFBTiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1771574231),
('AKDksDFW3wRkNmyDCKiTYOdHvbfDzsyIbyTmz647', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiNng4ZDg5eUY2VDdBWXZNV2FYS0dIRDE2TFF2dUFpbko2eTZLM1VtSyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771577549),
('b4TChqOpVBIzDQYLTLdDuSzxTUiyjGlDs1WCbWyi', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNHNDak90TlR3UVJoMmNtSkI2eUtyb3lNU0lYQ2RheVNYZUMwR0dCSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771577502),
('Be9MrSgtvgb5bszDevDrrpUP9uFozNX6uf3Y5h2Q', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQTZtaTFhcjF0MnJLS1VOMGVQYVBkN1JFdUVGMTFSM2tRdFNBTE5TUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771575262),
('BfAzmDZgY5Xfz9beXo0lNmhAVdsEdiV6sirPGLjP', 2, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiSUc1VWxycmVxQ2s5Qnl2T0R0cHZyMWdqdDZaN0xCSDBIa0pVNWp2UiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YToxOntpOjA7czo3OiJzdWNjZXNzIjt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6Nzoic3VjY2VzcyI7czoxNzoiTG9naW4gc3VjY2Vzc2Z1bCEiO30=', 1771574712),
('bTczb2Zze4ntnjUAgDB6gpsukzLJrfTRWfh3x4eO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTUtHeWxKYTJsV2RiT0lRcUl2TGswTkhTQU5yaXRDa0t1dGJlZUEzOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771575222),
('c2YmremyxAyh3A3AEUzp700lKxto6qCGC137pllq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYU1oanRMS2xBcjk3RTJESERzcjlvUzFTV1E5YUduc01wRUlDenRXNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771575102),
('Ckov8vIMtjZYfQZCAYQzMggwd3WOmhsyveBIcnVV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTTlDZFRKQkVBa1ZFcmpDdzVJMmZ2UTdSbVljNDM5eFh4SnZNMDZ3MiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771575963),
('ClZYblxyY4DEt01rQEfnj6TTwX2iyloqYIpRJ69v', 2, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiTFlJMUlDNjM1TnFUNEZJTG9qU1FQSnpWRE80R2I5S2tiYXVFd1hzSSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YToxOntpOjA7czo3OiJzdWNjZXNzIjt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6Nzoic3VjY2VzcyI7czoxNzoiTG9naW4gc3VjY2Vzc2Z1bCEiO30=', 1771574738),
('dmaYq5Fpdy36OTk8VmWOg1Vd37txToI0fpyIytgl', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTXpqR3IyYVJqc0lUOVpQMUw3R0hPVGJkcTZuakJYUHFFOWRTTzN0WSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771575225),
('EKuCOdGyNlSMfdBj0TcCQ54inAfj5jTrmMrLHYsa', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiYU5iWmJRTVBBTVpndGZvN0lNVlE5RFpqbnZpalFLbVRzaTZycDR1eSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771577068),
('gaKAmR2cZsHJYByCj2dSzwLJLpebhcFyjd8Rbm7T', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVnBvcFI3T0dsbDJrRXQzZTlkOG5VTElxcUZnNndLRHVDdnBiQ2RMdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1771574222),
('Gb5mPCDJATFoGzF1kBawwRVJ75mA0U6PlJ1SwBBX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiSTRGWTd2TVkwMVRER1dqOGxQc3F5UGRwWjFLS0NBbWx4SWt3VjhUTiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771575216),
('hAZ9LRoDG0KjpHDtDshL9ieayz5CgVrnO7NBe46A', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoibno2OXczbmlTNEEzcDBPY3dOSWpGdmVDd1p5cWlodEE1QmZ4dEQ2RiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771575110),
('HLe5eKYL0DIwZ54dp0G40tyobKQlsfp0ZgBx5j8u', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUTdtdERPOXRxUFoxVnFYVnhXNDhjWVhYcW90NnhnZVBDSVdiOHVURSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771575708),
('hNPq6djrjAQJD73xiqJeJLbPwwr1FPdiWzzH59t3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiblpMbmV5eUp2YkJIVmVNTzBOcDR5dW9yZUFzNnFYbGR2MVBKZHBmUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771575962),
('huQaKcf1C08kd8fVBQ4YNqZzHUKDbN1JUYdxN3uF', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS1o0WGt2OXBZZWxubElVaTczU1ZGNVR6enVtMEtOeDM4SVNwZHVlcyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771575873),
('inPmpWtGGUKrD40ruEKt7qeEJVyX4Zoe3YIQ2IJs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ01ORjJmRDdDYm15NWdsWXVEY3JGRUtLZmhtQmdBbTd1TTNpeVp6NyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771577501),
('J4snI7EciF7dSy3ZgO6qRiUccREfXkY2WkiGX3Lm', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiaFdiaDBYZXZlTll0THlDMGhSMHFDbFA4eWVSS2tyZXJvVU15TURWZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771577508),
('JgEq7aQ0kGGbxdQBYU3H2M0QpIhUWHAKkZadqYSb', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNDNpRUVJSWVuams3bmczcjlxVTIzUDNUd3NNTGlPOWU0NG9ZUEtnZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771575226),
('KAOKsoVFkLnPJVgqUTNlMJw9qz75jTawGHAvA1sn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiM2ZOSXVQMmxxYUM1ZEx3bG1KZWg4SU9ETVRxZTNadUh3ZnJQWEZzWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771575114),
('L5yFQO6rbzBvWXqIRraOZew7zahfkCa7JbiE5nSQ', 2, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoibFZGcFFQNDR1SThEWGNUZmtDZFY0cHFUQlVPTTBqMllKcTdWTmRzQSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YToxOntpOjA7czo3OiJzdWNjZXNzIjt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6Nzoic3VjY2VzcyI7czoxNzoiTG9naW4gc3VjY2Vzc2Z1bCEiO30=', 1771574746),
('m2zxgLAsKdAYhsXBEk9mK7Db7e0gLwSUDEukB8b7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZzJhcVRXaTg4TGN3VWw4bG5pSHZwbkU3N0drcTIyMTYxNkFsNUVtOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771577053),
('mU0z0bO7SY1BewoMLEMd0RYzRIWkEWltDBYUdwHV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNjR1aloxSzlMNExkSUo4cm5iMUdVWVB6aENFc0Z2SmhZY0lUWkRXcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771577053),
('NhPMQspYWNSEa9v1D6tbYkueudKzjOJHKFNSbVD3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMmxDZkVSQWpoZGNVaWxya0FCYkZRc09CaHdLZk83Y2puN2FidG91QyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771575512),
('OQbBQ8fToi0HnSFrBRQQBhwPcmsT4A0c7pl72g9V', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiUm9NM3ZYMlFTSHlTZThwN3hUQVJBWUVJQXlhNzhtQzdxUXhBdkRIYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771577048),
('osfBSUUorby54xDofme2kMsrQtFEjtE47NGQ6FFq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiN2Y3ZjlGVWdRRlJ0QThuSXh0YjFjSUFKaERPNkVoNVZubnBjOXp2MiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771575524),
('OtQVQKIZIIGgAWS8ixo4pbVzGCS6JPQcnQkjFkPE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieFl5YUxnZGVnNmxQVmxMeWRvd2VDWDlWVXBtNW1weG9nSlQ2bUszUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771575225),
('ougQY0vjf0EVOMwP4dgXdQza8lycp72LVctY799z', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSThCMU5GMWxuQ0NBbUtqdktWdmFoYmtKaTh0TU1JR0pUTlBhVE1raSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771577053),
('q7jqWvpHWOwf5dnEv1rsyUO220dW5ReXl8c5nuk7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaGdaMWVCUXY5a0xtc3duS2wwODhUY3dHQk1NdEs4aHpiMmM5cW9UaSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2NlcnRpZmljYXRlcyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771574917),
('QsuS7TzpZbE2GNveubSStLxM6GDlNaQ4WgQBmDv7', 2, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidDdPaEFhdkN2QWhkd1RFazZOVGgzRGNJNmYweG13QTVFRElFNjlKSSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1771574570),
('rI83kNcFuObNv8Zfd6yhWacZzEeFLzO8qv5moLjJ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQTJyRjh5MHBjcmxTOEJOOVF2TUlFdkVPODRaZVRrdHZobVhVOXpiUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771575262),
('U6uxuKOnABiRrhsUMukPdehIt4B7XuVXM7xVI0of', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRTVhNll6Slp0MDE3UVRQZDc0SmNuWGJHRTV0WUJycnkydXAyR2RXUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771577542),
('UeceMwEhR3GM4gjOlI9BEOwFxbptf3hMxtOAWD1p', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoia0d2Yjg0MG03bkN0aXZJWGkyY3NZb09ha05UZ0ZFQTBMUXY0amZzTyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771575222),
('UXmaht1WRtINKTEgaNsPCpg6nqk3CJ6h7n326aOg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUU9SbkU4eGU4bEtGVG9Qem9jQTJrckx5bXRqdWJsWTlhODFXYWlyWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771577443),
('VGIQypi8dXuWEIS7D1Tnwa7YNBNfmTaDikzTVoBo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS0t3a2NEekVxQVd6U3NSYW9mNjdVR0EwZVRlSzA4Qzl0bzNsRkNjViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771575321),
('vpuc5ebtpTkfr6RUFPcWveXlw05wB5ljaq8fgDOK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTEtmYTVRSFZvSnlCcEpXRHFSM0RCVGdkS1hRbTA5clBkeGZDdEZ6diI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771575519),
('wcTd963w1fq5y2d8EMgpl16yYj3T8exC9aXSP7wj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZkw2YkMzWGkxdHp0aTNWWXJmNHFGcFJFSjVPN2pwcFZUUjI0MmhOeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771577501),
('wLme7R2gTg2YgUVMDOijT9zi1tekAwIrARDHcuKs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoibEJPb2dNZkFtOTFlbG9zYjdEYlNGbVZsM2VvRkxxdzhYU281bGhDNyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771577063),
('wpSbyLv29lLEFkTkdTZ8nIkWIF3wrTsIlXmfmCgE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia1FTT3MyeXhMNmttdTF0a09kZXAzaGo5TGllRTFaMFBkWTlZQ3ROVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771577542),
('x7BGFr2JePAAYtPJd0RBdpYybQzFHo8ChVDi9KzB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiVm1iZmJZQTVyM0JRWVFGbDEyRmhqVk16VjVNOG54czBFUnRLUnBLMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771575968),
('z7RGeGZHlav183WQWDytXCFgtXfGqBc6O34ogycO', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSTYwQUk5UExubzFNSGxNSE55VkhYV1FGREJZMmhqVkJZR0dMYTFMYiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1771574358),
('zJxDvarQFw37EKPA52cnBUHWgzmak8vUooBb3fUg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWEVDQWFPRmU2dTlObEl4YkkyWjE0VTZaRFptTTlkaHFsVXUwZFZaMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771577052);

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','locked') NOT NULL DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `full_name`, `username`, `email`, `password`, `contact_number`, `profile_photo`, `status`, `last_login`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 1, 'Admin User', 'admin', 'admin@example.com', '$2y$12$zJk3TeJPTHFAfg0gcwdQveuXea75paivHqHtI8tOruxgsi1Opjj/m', NULL, 'profile-photos/bbg2uC52wHeg3eLWIz2EEt0AHfh9WuaPe692belw.png', 'active', '2026-02-23 08:17:22', NULL, '2026-02-19 23:56:28', '2026-02-23 00:17:22'),
(3, 1, 'john rey', 'johnrey', 'johnreyybanez@gmail.com', '$2y$12$eR4VyniBvO7Z5ENvqKkObOa1MEYdEtnGdYmxlbwsUq6pk7S/VtDJW', '0804284721942', 'profile-photos/pEUO4kuONagKPmeqNpValp16xFNHtZ346vvw2MgV.jpg', 'active', '2026-02-23 23:59:34', 'uRslPtXsDN4oeLiOxpp3dgTsHx7Dlr1HA2fx6GBYSQEZO8C3Np6AXGQ4UW91', '2026-02-22 22:45:11', '2026-02-23 15:59:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_resident_id_foreign` (`resident_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `blotter_records`
--
ALTER TABLE `blotter_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blotter_records_complainant_id_foreign` (`complainant_id`),
  ADD KEY `blotter_records_respondent_id_foreign` (`respondent_id`),
  ADD KEY `blotter_records_created_by_foreign` (`created_by`),
  ADD KEY `blotter_records_status_index` (`status`);

--
-- Indexes for table `certificate_requests`
--
ALTER TABLE `certificate_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificate_requests_control_number_unique` (`control_number`),
  ADD KEY `certificate_requests_resident_id_foreign` (`resident_id`),
  ADD KEY `certificate_requests_certificate_type_id_foreign` (`certificate_type_id`),
  ADD KEY `certificate_requests_approved_by_foreign` (`approved_by`),
  ADD KEY `certificate_requests_status_index` (`status`);

--
-- Indexes for table `certificate_types`
--
ALTER TABLE `certificate_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `households`
--
ALTER TABLE `households`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `households_household_number_unique` (`household_number`),
  ADD KEY `households_head_resident_id_foreign` (`head_resident_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `officials`
--
ALTER TABLE `officials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `officials_resident_id_foreign` (`resident_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_certificate_request_id_foreign` (`certificate_request_id`),
  ADD KEY `payments_received_by_foreign` (`received_by`),
  ADD KEY `payments_payment_date_index` (`payment_date`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `residents_resident_code_unique` (`resident_code`),
  ADD KEY `residents_last_name_first_name_index` (`last_name`,`first_name`),
  ADD KEY `residents_household_id_foreign` (`household_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_role_name_unique` (`role_name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_settings_setting_key_unique` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `blotter_records`
--
ALTER TABLE `blotter_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `certificate_requests`
--
ALTER TABLE `certificate_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `certificate_types`
--
ALTER TABLE `certificate_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `households`
--
ALTER TABLE `households`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `officials`
--
ALTER TABLE `officials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`);

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `blotter_records`
--
ALTER TABLE `blotter_records`
  ADD CONSTRAINT `blotter_records_complainant_id_foreign` FOREIGN KEY (`complainant_id`) REFERENCES `residents` (`id`),
  ADD CONSTRAINT `blotter_records_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `blotter_records_respondent_id_foreign` FOREIGN KEY (`respondent_id`) REFERENCES `residents` (`id`);

--
-- Constraints for table `certificate_requests`
--
ALTER TABLE `certificate_requests`
  ADD CONSTRAINT `certificate_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `certificate_requests_certificate_type_id_foreign` FOREIGN KEY (`certificate_type_id`) REFERENCES `certificate_types` (`id`),
  ADD CONSTRAINT `certificate_requests_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`);

--
-- Constraints for table `households`
--
ALTER TABLE `households`
  ADD CONSTRAINT `households_head_resident_id_foreign` FOREIGN KEY (`head_resident_id`) REFERENCES `residents` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `officials`
--
ALTER TABLE `officials`
  ADD CONSTRAINT `officials_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_certificate_request_id_foreign` FOREIGN KEY (`certificate_request_id`) REFERENCES `certificate_requests` (`id`),
  ADD CONSTRAINT `payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `residents`
--
ALTER TABLE `residents`
  ADD CONSTRAINT `residents_household_id_foreign` FOREIGN KEY (`household_id`) REFERENCES `households` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
