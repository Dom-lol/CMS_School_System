-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2026 at 01:46 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_cms_school`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `posted_by` varchar(100) DEFAULT 'រដ្ឋបាល',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `posted_by`, `created_at`) VALUES
(1, 'ការឈប់សម្រាកបុណ្យចូលឆ្នាំ', 'សាលានឹងត្រូវឈប់សម្រាកចាប់ពីថ្ងៃទី ១៤ ដល់ ១៦ ខែមេសា។', 'រដ្ឋបាល', '2026-01-15 15:12:15'),
(2, 'ការប្រឡងឆមាសទី១', 'សូមបុគ្គលិកទាំងអស់រៀបចំបញ្ជីឈ្មោះសិស្សឱ្យបានរួចរាល់។', 'នាយកដ្ឋានសិក្សា', '2026-01-15 15:12:15'),
(3, 'ការឈប់សម្រាក ថ្ងៃនេះលោកគ្រូ​ ជា ឧត្តម សង្សារសុំបែក', '​ ថ្ងៃនេះ​ ថ្នាក់ទី១១A គ្មានការបង្រៀនទេ', 'រដ្ឋបាល', '2026-01-16 05:40:16'),
(4, 'លោកគ្រូប៊ុនយ៉ុងសុំច្បាប់', 'ថ្ងៃនេះលោកគ្រូប៊ុនយ៉ុងសុំច្បាប់មួយថ្ងៃមូលហេតុទៅជួបអ្នកគ្រូ Sotun។', 'រដ្ឋបាល', '2026-01-24 06:15:20');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `status` enum('present','absent','permission') NOT NULL DEFAULT 'present',
  `attendance_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `class_id`, `teacher_id`, `status`, `attendance_date`, `created_at`) VALUES
(1, 6, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(2, 20, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(3, 16, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(4, 2, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(5, 14, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(6, 10, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(7, 24, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(8, 22, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(9, 28, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(10, 4, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(11, 18, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(12, 8, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(13, 12, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(14, 26, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(15, 21, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(16, 9, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(17, 3, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(18, 17, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(19, 7, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(20, 23, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(21, 29, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(22, 5, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(23, 1, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(24, 25, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(25, 15, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(26, 13, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(27, 30, 0, 13, 'permission', '2026-01-26', '2026-01-26 04:51:23'),
(28, 11, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(29, 19, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(30, 27, 0, 13, 'present', '2026-01-26', '2026-01-26 04:51:23'),
(31, 6, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(32, 20, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(33, 16, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(34, 2, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(35, 14, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(36, 10, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(37, 24, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(38, 22, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(39, 28, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(40, 4, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(41, 18, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(42, 8, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(43, 12, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(44, 26, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(45, 21, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(46, 9, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(47, 3, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(48, 17, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(49, 7, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(50, 23, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(51, 29, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(52, 5, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(53, 1, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(54, 25, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(55, 15, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(56, 13, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(57, 30, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(58, 11, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(59, 19, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(60, 27, 0, 766, 'present', '2026-01-27', '2026-01-26 04:56:43'),
(61, 6, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(62, 20, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(63, 16, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(64, 2, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(65, 14, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(66, 10, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(67, 24, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(68, 22, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(69, 28, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(70, 4, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(71, 18, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(72, 8, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(73, 12, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(74, 26, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(75, 21, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(76, 9, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(77, 3, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(78, 17, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(79, 7, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(80, 23, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(81, 29, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(82, 5, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(83, 1, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(84, 25, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(85, 15, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(86, 13, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(87, 30, 0, 766, 'permission', '2026-01-28', '2026-01-26 04:56:59'),
(88, 11, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(89, 19, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59'),
(90, 27, 0, 766, 'present', '2026-01-28', '2026-01-26 04:56:59');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `academic_year` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class_name`, `academic_year`) VALUES
(1, 'ថ្នាក់ទី ៧', NULL),
(2, 'ថ្នាក់ទី ៨', NULL),
(3, 'ថ្នាក់ទី ៩', NULL),
(4, 'ថ្នាក់ទី ១០', NULL),
(5, 'ថ្នាក់ទី ១១ វិទ្យាសាស្រ្ត', NULL),
(6, 'ថ្នាក់ទី ១១​ សង្គម', NULL),
(8, 'ថ្នាក់ទី១២សង្គម', NULL),
(9, 'ថ្នាក់ទី១២វិទ្យាសាស្រ្ត', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `monthly_score` decimal(5,2) DEFAULT '0.00',
  `exam_score` decimal(5,2) DEFAULT '0.00',
  `total_score` decimal(5,2) DEFAULT '0.00',
  `grade` varchar(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `full_name_en` varchar(255) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `dob` varchar(50) DEFAULT NULL,
  `pob` text,
  `address` text,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `father_job` varchar(100) DEFAULT NULL,
  `mother_job` varchar(100) DEFAULT NULL,
  `father_phone` varchar(20) DEFAULT NULL,
  `mother_phone` varchar(20) DEFAULT NULL,
  `stream` varchar(100) DEFAULT NULL,
  `class_name` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Active',
  `class_id` int(11) DEFAULT NULL,
  `academic_year` varchar(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT 'default.png',
  `profile_img` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `student_id`, `full_name`, `full_name_en`, `gender`, `dob`, `pob`, `address`, `father_name`, `mother_name`, `father_job`, `mother_job`, `father_phone`, `mother_phone`, `stream`, `class_name`, `status`, `class_id`, `academic_year`, `photo`, `profile_img`, `phone`) VALUES
(4, 1217, '123', 'ញឹប​ កុសល', 'Kosol', 'ស្រី', '2026-02-27', 'sdf, sdf, sdf, sd', 'hh, m, oo, j', 'ghj', 'hjk', NULL, NULL, NULL, NULL, NULL, '7-', 'Active', 1, '2025-2026', 'default.png', '123_1769513135.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(100) DEFAULT NULL,
  `teacher_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_name`, `teacher_id`) VALUES
(1, 'អក្សរសាស្ត្រខ្មែរ', '1'),
(2, 'គណិតវិទ្យា', '2'),
(3, 'រូបវិទ្យា', '3'),
(4, 'គីមីវិទ្យា', '4'),
(5, 'ជីវវិទ្យា', '5'),
(6, 'ប្រវត្តិវិទ្យា', '6'),
(7, 'ផែនដីវិទ្យា', '7');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `subjects` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT 'default_user.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `user_id`, `full_name`, `subjects`, `phone`, `profile_image`) VALUES
('1', 13, 'ជា ឧត្តម', 'អក្សរសាស្ត្រខ្មែរ', '0968263627', 'T_1_1768886981.jpg'),
('2', 2, 'អ្នកគ្រូ ចាន់ ថា', 'គណិតវិទ្យា', '0968263600', 'default_user.png'),
('3', 730, 'លោកគ្រូ ហេង ឡុង', 'រូបវិទ្យា', '090222290', 'default_user.png'),
('4', 731, 'អ្នកគ្រូ ម៉ារី យ៉ា', 'គីមីវិទ្យា', '0887777112', 'default_user.png'),
('5', 732, 'លោកគ្រូ វណ្ណឌី', 'ជីវវិទ្យា', '012222229', 'default_user.png'),
('6', 733, 'អ្នកគ្រូ ស្រីមុំ', 'ប្រវត្តិវិទ្យា', '077898900', 'default_user.png'),
('7', 734, 'លោកគ្រូ ញឹប កុសល', 'ផែនដីវិទ្យា', '012999911', 'default_user.png');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_assignments`
--

CREATE TABLE `teacher_assignments` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teacher_assignments`
--

INSERT INTO `teacher_assignments` (`id`, `teacher_id`, `class_id`, `subject_id`) VALUES
(1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL,
  `day_of_week` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `room_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`id`, `day_of_week`, `start_time`, `end_time`, `room_number`, `subject_id`, `teacher_id`, `class_id`, `is_deleted`) VALUES
(1, 'ច័ន្ទ', '07:00:00', '07:50:00', '9A', 1, 1, 1, 0),
(2, 'ច័ន្ទ', '08:00:00', '08:50:00', '9A', 2, 2, 1, 0),
(3, 'ច័ន្ទ', '09:00:00', '09:50:00', '9A', 3, 3, 1, 0),
(4, 'ច័ន្ទ', '10:00:00', '10:50:00', '9A', 4, 4, 1, 0),
(5, 'អង្គារ', '07:00:00', '07:50:00', '9A', 5, 5, 1, 0),
(6, 'អង្គារ', '08:00:00', '08:50:00', '9A', 3, 3, 1, 0),
(7, 'អង្គារ', '09:00:00', '09:50:00', '9A', 2, 2, 1, 0),
(8, 'អង្គារ', '10:00:00', '10:50:00', '9A', 1, 1, 1, 0),
(9, 'ពុធ', '07:00:00', '07:50:00', '9A', 3, 3, 1, 0),
(10, 'ពុធ', '08:00:00', '08:50:00', '9A', 2, 2, 1, 0),
(11, 'ពុធ', '09:00:00', '09:50:00', '9A', 4, 4, 1, 0),
(12, 'ពុធ', '10:00:00', '10:50:00', '9A', 2, 2, 1, 0),
(13, 'ព្រហស្បតិ៍', '07:00:00', '07:50:00', '9A', 3, 3, 1, 0),
(14, 'ព្រហស្បតិ៍', '08:00:00', '08:50:00', '9A', 1, 1, 1, 0),
(15, 'ព្រហស្បតិ៍', '09:00:00', '09:50:00', '9A', 5, 5, 1, 0),
(16, 'ព្រហស្បតិ៍', '10:00:00', '10:50:00', '9A', 6, 6, 1, 0),
(17, 'សុក្រ', '07:00:00', '07:50:00', '9A', 7, 7, 1, 0),
(18, 'សុក្រ', '08:00:00', '08:50:00', '9A', 1, 1, 1, 0),
(19, 'សុក្រ', '09:00:00', '09:50:00', '9A', 2, 2, 1, 0),
(20, 'សុក្រ', '10:00:00', '10:50:00', '9A', 3, 3, 1, 0),
(21, 'សៅរ៏', '07:00:00', '07:50:00', '9A', 4, 4, 1, 0),
(22, 'សៅរ៏', '08:00:00', '08:50:00', '9A', 5, 5, 1, 0),
(23, 'សៅរ៏', '09:00:00', '09:50:00', '9A', 6, 6, 1, 0),
(24, 'សៅរ៏', '10:00:00', '10:50:00', '9A', 7, 7, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `email`, `role`, `created_at`) VALUES
(1, 'admin', '123456', 'System Admin', '', 'admin', '2026-01-14 15:06:13'),
(2, 'staff01', 'staff123', 'staff', '', 'staff', '2026-01-14 15:06:13'),
(13, '1', '123', 'ជា ឧត្តម', '', 'teacher', '2026-01-15 05:52:52'),
(19, 'admin01', 'admin123', 'Administrator', '', 'admin', '2026-01-15 05:54:31'),
(32, 'd', '1', 'Administrator', '', 'student', '2026-01-15 06:31:33'),
(730, '3', '123', 'លោកគ្រូ ហេង ឡុង', '', 'teacher', '2026-01-20 05:01:52'),
(731, '4', '123', 'អ្នកគ្រូ ម៉ារី យ៉ា', '', 'teacher', '2026-01-20 05:01:52'),
(732, '5', '123', 'លោកគ្រូ វណ្ណឌី', '', 'teacher', '2026-01-20 05:01:52'),
(733, '6', '123', 'អ្នកគ្រូ ស្រីមុំ', '', 'teacher', '2026-01-20 05:01:52'),
(734, '7', '123', 'លោកគ្រូ រតនា', '', 'teacher', '2026-01-20 05:01:52'),
(1217, '123', '123', 'ញឹប​ កុសល', '', 'student', '2026-01-27 11:04:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_subject` (`student_id`,`subject_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `username_2` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1218;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`);

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
