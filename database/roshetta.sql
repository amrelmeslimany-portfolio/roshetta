-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2023 at 11:05 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `roshetta`
--

-- --------------------------------------------------------

--
-- Table structure for table `activation_person`
--

CREATE TABLE `activation_person` (
  `id` smallint(6) NOT NULL,
  `images` text DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL,
  `user_id` smallint(6) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `activation_person`
--

INSERT INTO `activation_person` (`id`, `images`, `isActive`, `user_id`, `role`) VALUES
(1, 'ph-99999999999999', 0, 1, 'pharmacist');

-- --------------------------------------------------------

--
-- Table structure for table `activation_place`
--

CREATE TABLE `activation_place` (
  `id` mediumint(9) NOT NULL,
  `license_img` text DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL,
  `place_id` mediumint(9) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `activation_place`
--

INSERT INTO `activation_place` (`id`, `license_img`, `isActive`, `place_id`, `role`) VALUES
(2, 'ph-8211895764', 1, 1, 'pharmacy');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ssd` bigint(14) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` text NOT NULL,
  `gender` varchar(10) NOT NULL,
  `birth_date` date NOT NULL,
  `governorate` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `security_code` text NOT NULL,
  `token` text DEFAULT NULL,
  `email_isActive` tinyint(1) NOT NULL,
  `profile_img` text DEFAULT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `ssd`, `email`, `phone_number`, `gender`, `birth_date`, `governorate`, `password`, `security_code`, `token`, `email_isActive`, `profile_img`, `role`) VALUES
(1, 'samy mohamed', 22222222222222, 'mohamedsaeed00451@gmail.com', '01010205040', 'ذكر', '2000-01-12', 'اسوان', '$2y$10$kkFMOz4K0IL32AFbeHj75ezc7ezEVs9yDNXtDd9HGjLPARJxjEgZ6', '512720', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwidHlwZSI6ImFkbWluIiwiZXhwIjoxNjc3OTk2Mjc1fQ.JKZa17VvxbppawSH1gjg_accz5cGvXBKJDRimPTk3bk', 1, NULL, 'admin'),
(3, 'hamdy ahmed', 33333333333333, 'ha@gmail.com', '01010101010', 'ذكر', '2023-01-18', '', '$2y$10$IuvMcAe49/0Top/QpZZfouVH7ec8ZY.vGxrDhwckrMqc5x82uwDNa', '', NULL, 0, NULL, 'admin'),
(4, 'ali ahmed', 12345678912345, 'ali@gmail.com', '01020231410', 'ذكر', '2023-01-05', '', '$2y$10$ah/NCnRsFJBmVjfCRZbGo.xgA8FPar4IdfUOro/b3Q9VMNli3WnLG', '', NULL, 0, NULL, 'admin'),
(5, 'ahmed mohamed', 11111111111111, 'ah@gmail.com', '01010101011', 'male', '2000-10-12', '', '$2y$10$dxxRGwYI.QcoW5N.16WyoeN4VhfI7pcXzXcwXeVQ7cI2treOZ3NqW', '3aec9dc5cc33ecb878953000fa9d210e', NULL, 0, NULL, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `appoint_date` date NOT NULL,
  `appoint_case` tinyint(4) NOT NULL,
  `patient_id` mediumint(9) NOT NULL,
  `clinic_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `appoint_date`, `appoint_case`, `patient_id`, `clinic_id`) VALUES
(32, '2023-02-25', 0, 70, 12),
(33, '2023-02-25', 0, 55, 12),
(34, '2023-02-25', 0, 58, 12),
(35, '2023-02-25', 0, 64, 12),
(36, '2023-02-25', 0, 70, 12);

-- --------------------------------------------------------

--
-- Table structure for table `assistant`
--

CREATE TABLE `assistant` (
  `id` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ssd` bigint(14) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` text NOT NULL,
  `gender` varchar(10) NOT NULL,
  `governorate` varchar(50) NOT NULL,
  `birth_date` date NOT NULL,
  `password` varchar(255) NOT NULL,
  `security_code` text NOT NULL,
  `token` text DEFAULT NULL,
  `email_isActive` tinyint(1) NOT NULL,
  `profile_img` text DEFAULT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `assistant`
--

INSERT INTO `assistant` (`id`, `name`, `ssd`, `email`, `phone_number`, `gender`, `governorate`, `birth_date`, `password`, `security_code`, `token`, `email_isActive`, `profile_img`, `role`) VALUES
(1, 'ahmed ali', 99999999999999, 'mohamedsaeed00451@gmail.com', '36985214789', 'ذكر', 'البحيرة', '2023-01-12', '$2y$10$yZqkRs7N8bwd14G3rZ9jmuoeDYB1zQ0H6YygwGWNwbRvwVDA1jXDi', '9a00a6a54ac912bf27a0d0f2b5e45001', NULL, 1, 'http://localhost:3000/ROSHETTA_API/API_User/API_IMG/Profile_Img/Profile_assistant_img/99999999999999/93163599999999999999.png', 'ASSISTANT'),
(2, 'mmss', 99999999999995, 'mohamedsaeed0451@gmail.com', '36985214788', 'ddd', 'dd', '0000-00-00', '$2y$10$qnYXOkpifziawKARcoWacOtfIdPq5WjJYpJ17MXuPn9Qtr/D7vK2u', '696129', NULL, 0, NULL, 'assistant'),
(3, 'hh hh', 12345678912385, 'm@gmail.com', '01032568974', 'd', 'd', '0000-00-00', '$2y$10$8VoDn0oIAM.iUO2QQLOKIePkTjfJmNOOSFZrtZTARCIWEum/Y/ubu', '843957', NULL, 0, NULL, 'assistant');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `profile_img` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `name`, `time`, `message`, `profile_img`) VALUES
(NULL, 'عمرو  المسلمانى', '06:03', 'مرحبا', NULL),
(NULL, 'محمد سعيد', '06:04', 'اهلا عمور', NULL),
(NULL, 'عمرو  المسلمانى', '06:04', 'عمور مين يا علق متدلعنيش', NULL),
(NULL, 'محمد سعيد', '06:04', 'خلاص متزعلش', NULL),
(NULL, 'mohamed saeed gomaa', '10:51', 'hello', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `clinic`
--

CREATE TABLE `clinic` (
  `id` mediumint(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `specialist` varchar(255) NOT NULL,
  `phone_number` text NOT NULL,
  `price` smallint(6) NOT NULL,
  `start_working` time NOT NULL,
  `end_working` time NOT NULL,
  `governorate` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `logo` text DEFAULT NULL,
  `ser_id` varchar(255) NOT NULL,
  `doctor_id` smallint(6) NOT NULL,
  `assistant_id` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `clinic`
--

INSERT INTO `clinic` (`id`, `name`, `owner`, `specialist`, `phone_number`, `price`, `start_working`, `end_working`, `governorate`, `address`, `logo`, `ser_id`, `doctor_id`, `assistant_id`) VALUES
(12, 'dr mohamed', 'mohamed saeed', 'بطنة', '01039258855', 210, '33:23:40', '39:23:41', 'البحيرة', 'دمنهور', NULL, '123456', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `disease`
--

CREATE TABLE `disease` (
  `id` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `patient_id` mediumint(9) NOT NULL,
  `doctor_id` smallint(6) NOT NULL,
  `clinic_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `disease`
--

INSERT INTO `disease` (`id`, `name`, `place`, `date`, `patient_id`, `doctor_id`, `clinic_id`) VALUES
(11, 'صداع', 'الرأس', '2023-03-04', 70, 1, 12),
(12, 'كحة', 'الزور', '2023-03-02', 70, 1, 12);

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `id` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ssd` bigint(14) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` text NOT NULL,
  `gender` varchar(10) NOT NULL,
  `birth_date` date NOT NULL,
  `specialist` varchar(255) NOT NULL,
  `governorate` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `security_code` text NOT NULL,
  `token` text DEFAULT NULL,
  `email_isActive` tinyint(1) NOT NULL,
  `profile_img` text DEFAULT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `name`, `ssd`, `email`, `phone_number`, `gender`, `birth_date`, `specialist`, `governorate`, `password`, `security_code`, `token`, `email_isActive`, `profile_img`, `role`) VALUES
(1, 'mohamed saeed', 12345678912345, 'mo@gmail.com', '01092338585', 'male', '2000-10-22', 'بطنه', 'aswan', '$2y$10$nhFyxDMDBtsW.HXz9vEeL.77kGYEZmWbdJQtVINCnDSWqx0bnkXCi', '969539', NULL, 1, NULL, 'doctor');

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `id` bigint(20) NOT NULL,
  `medicine_data` text NOT NULL,
  `prescript_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`id`, `medicine_data`, `prescript_id`) VALUES
(9, 'YTo2OntzOjE3OiJtZWRpY2luZVtuYW1lXVsxXSI7czo2OiLYrdmC2YYiO3M6MTc6Im1lZGljaW5lW3NpemVdWzFdIjtpOjUwMDtzOjI0OiJtZWRpY2luZVtkZXNjcmlwdGlvbl1bMV0iO3M6MjE6ItmF2LHYqtmK2YYg2YrZiNmF2YrYpyI7czoxNzoibWVkaWNpbmVbbmFtZV1bMl0iO3M6MTc6Itio2LHYtNin2YUg2LXYp9i5IjtzOjE3OiJtZWRpY2luZVtzaXplXVsyXSI7aToxMDA7czoyNDoibWVkaWNpbmVbZGVzY3JpcHRpb25dWzJdIjtzOjIxOiLZhdix2KrZitmGINmK2YjZhdmK2KciO30=', 17);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ssd` bigint(14) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` longtext NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `m_case` tinyint(1) NOT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `name`, `ssd`, `email`, `message`, `time`, `m_case`, `role`) VALUES
(1, 'محمد سعيد جمعة', 12345222296333, 'mohamedsaeed00451@gmail.com', 'اريد المساعدة', '2023-01-31 13:18:34', 1, 'PATIENT'),
(2, 'محمد سعيد جمعة', 12345222296333, 'mohamedsaeed00451@gmail.com', 'اريد المساعدة', '2023-01-31 13:19:58', 1, 'PATIENT'),
(3, 'عمرو  المسلمانى', 11111111111115, 'mohamedsaeed00451@gmail.com', 'اريد المساعدة', '2023-01-31 13:20:59', 1, 'DOCTOR'),
(4, 'عمرو المسلمانى', 99999999999999, 'ammghdefe45353@gmail.com', 'اريد المساعدة', '2023-01-31 13:27:04', 0, 'PHARMACIST'),
(5, 'mohamed saeed', 11122233344455, 'mo@gmail.com', 'hello roshetta', '2023-02-02 13:20:45', 1, 'PATIENT'),
(6, 'mohamed saeed', 12345678912345, 'mo@gmail.com', 'مرحبا بكم فى روشتة', '2023-02-28 13:18:35', 0, 'patient');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` mediumint(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ssd` bigint(14) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` text NOT NULL,
  `gender` varchar(10) NOT NULL,
  `birth_date` date NOT NULL,
  `weight` smallint(6) NOT NULL,
  `height` smallint(6) NOT NULL,
  `governorate` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `security_code` text NOT NULL,
  `token` text DEFAULT NULL,
  `email_isActive` double NOT NULL,
  `profile_img` text DEFAULT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `name`, `ssd`, `email`, `phone_number`, `gender`, `birth_date`, `weight`, `height`, `governorate`, `password`, `security_code`, `token`, `email_isActive`, `profile_img`, `role`) VALUES
(54, 'محمود', 11111111, 'mohamedsaeed33451@gmail.com', '011122', 'mm', '2023-01-12', 4, 55, 'fffffff', '11', 'jjjjjjjjj', NULL, 1, 'llllllllll', 'patient'),
(55, 'احمد ', 8888, 'mohamedsaeed44451@gmail.com', '111', 'ت', '2023-01-12', 441, 4, 'ت', '44', '44', NULL, 1, 'ح', 'patient'),
(58, 'mohamed', 36985214789652, 'mohamed@gmail.com', '01022223335', 'male', '1999-10-04', 90, 140, 'aswan', '$2y$10$s6n1BuAevOQjadD48QS8ru4KdyM6zTZwo2yCmJBXtHdEGMw3/xDey', '0757a7705087c1eee179f53383232c47', NULL, 0, NULL, 'patient'),
(63, 'mohamed saeed', 12345678912344, 'mohamedsaeed00451@gmail.com', '01092338086', 'male', '2000-03-24', 60, 173, 'beheira', '$2y$10$9y7h1e3CTMGXuakdX2mHc.KSI0NdZmcQ2XKZn8NGqEwpUt7oqI7A6', '244353', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NjMsInR5cGUiOiJQQVRJRU5UIiwiZXhwIjoxNjc3MTc3OTg5fQ.E5bjGueiHIZypWCucfURWwHVMDBJBKynnC2Uy87hodA', 1, NULL, 'patient'),
(64, 'mmss', 99999999999998, 'mohamedsaeed0451@gmail.com', '36985214787', 'ddd', '0000-00-00', 22, 22, 'dd', '$2y$10$DOUaal1OscFvIngjQ7xvu..5YFQLThuQcwp909OQxMUKHMj0lYrYi', '815493', NULL, 0, NULL, 'patient'),
(70, 'mohamed saeed', 12345678912345, 'mo@gmail.com', '01092338588', 'male', '2000-10-22', 100, 200, 'البحيرة', '$2y$10$jvxW4dTBwjftDbKK1rGr0uUjDysspZwFS7ZH3/HgeihbjfC1uFxw.', '968617', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NzAsInR5cGUiOiJwYXRpZW50IiwiZXhwIjoxNjc3OTU3MTA5fQ.8v2gJ-Kt468tiMmreV8CiR9bgXcb58Io6v0-TNPniN8', 1, 'pa-12345678912345', 'patient');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacist`
--

CREATE TABLE `pharmacist` (
  `id` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ssd` bigint(14) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` text NOT NULL,
  `gender` varchar(10) NOT NULL,
  `birth_date` date NOT NULL,
  `governorate` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `security_code` text NOT NULL,
  `token` text DEFAULT NULL,
  `email_isActive` tinyint(1) NOT NULL,
  `profile_img` text DEFAULT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pharmacist`
--

INSERT INTO `pharmacist` (`id`, `name`, `ssd`, `email`, `phone_number`, `gender`, `birth_date`, `governorate`, `password`, `security_code`, `token`, `email_isActive`, `profile_img`, `role`) VALUES
(1, 'عمرو المسلمانى', 99999999999999, 'ammghdefe45353@gmail.com', '36985214789', 'ذكر', '2023-01-12', 'البحيرة', '$2y$10$53zyweTCQd/SOiu7fjS71eawHwd/z1yDjPH9AEZfbYmsfU6lO6vI.', '589cb4b906218c4209c1bbbafada8845', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwidHlwZSI6InBoYXJtYWNpc3QiLCJleHAiOjE2Nzc3ODg3OTV9.XDJ1GVwHyR9ZA9xDnJx90n9RvEtMG7_3XbKV-0mi36k', 1, NULL, 'pharmacist'),
(2, 'mmss', 99999999999995, 'mohamedsaeed0451@gmail.com', '36985214787', 'ddd', '0000-00-00', 'dd', '$2y$10$CmuxHEC4fezP.WmGYQ3PZ.eeHWfGHNhyCl9LLj.IE04FtrkEqd/M6', '855027', NULL, 0, NULL, 'pharmacist'),
(3, 'mmss', 99999994999995, 'mohamedsaeed451@gmail.com', '36985714787', 'ddd', '0000-00-00', 'dd', '$2y$10$QeqkbsLz/et5FePZoGwe2O.wiEdJSa3iKFvoyRTkaSZflbYxQnXOe', '524222', NULL, 0, NULL, 'pharmacist');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy`
--

CREATE TABLE `pharmacy` (
  `id` mediumint(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `owner` varchar(50) NOT NULL,
  `phone_number` text NOT NULL,
  `start_working` time NOT NULL,
  `end_working` time NOT NULL,
  `governorate` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `logo` text DEFAULT NULL,
  `ser_id` varchar(255) NOT NULL,
  `pharmacist_id` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pharmacy`
--

INSERT INTO `pharmacy` (`id`, `name`, `owner`, `phone_number`, `start_working`, `end_working`, `governorate`, `address`, `logo`, `ser_id`, `pharmacist_id`) VALUES
(1, 'Dr ali mohamed', 'ali mohamed', '01222222222', '06:00:00', '12:00:00', 'behira', 'aswan', 'http://localhost:3000/ROSHETTA_API/API_C_P/API_IMG/Logo_Img/Pharmacy/8211895764/e0d420c038561baf61b28211895764.jpg', '8211895764', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_order`
--

CREATE TABLE `pharmacy_order` (
  `id` smallint(6) NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  `patient_id` mediumint(9) NOT NULL,
  `prescript_id` bigint(20) NOT NULL,
  `pharmacy_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pharmacy_order`
--

INSERT INTO `pharmacy_order` (`id`, `time`, `patient_id`, `prescript_id`, `pharmacy_id`) VALUES
(17, '2023-03-04 19:17:48', 70, 17, 1),
(18, '2023-03-04 19:25:06', 70, 17, 1),
(19, '2023-03-04 19:38:52', 70, 17, 1),
(20, '2023-03-04 19:39:09', 70, 17, 1),
(21, '2023-03-04 19:43:05', 70, 17, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_prescript`
--

CREATE TABLE `pharmacy_prescript` (
  `id` bigint(20) NOT NULL,
  `prescript_id` bigint(20) NOT NULL,
  `pharmacy_id` mediumint(9) NOT NULL,
  `date_pay` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescript`
--

CREATE TABLE `prescript` (
  `id` bigint(20) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `rediscovery_date` date NOT NULL,
  `ser_id` varchar(255) NOT NULL,
  `patient_id` mediumint(9) NOT NULL,
  `disease_id` smallint(6) NOT NULL,
  `doctor_id` smallint(6) NOT NULL,
  `clinic_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `prescript`
--

INSERT INTO `prescript` (`id`, `created_date`, `rediscovery_date`, `ser_id`, `patient_id`, `disease_id`, `doctor_id`, `clinic_id`) VALUES
(17, '2023-03-04 19:07:20', '2023-03-22', '563214', 70, 11, 1, 12);

-- --------------------------------------------------------

--
-- Table structure for table `specialist`
--

CREATE TABLE `specialist` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `specialist`
--

INSERT INTO `specialist` (`id`, `name`, `ar_name`) VALUES
(1, 'Accident and emergency medicine', 'طب الطوارئ والحوادث'),
(2, 'Allergology', 'علم الحساسية'),
(3, 'Anaesthetics', 'التخدير'),
(4, 'Biological hematology', 'الأمراض الدموية الحيوية'),
(5, 'Cardiology', 'أمراض القلب'),
(6, 'Child psychiatry', 'طب الأطفال النفسي'),
(7, 'Clinical biology', 'الأحياء السريرية'),
(8, 'Clinical chemistry', 'الكيمياء السريرية'),
(9, 'Clinical neurophysiology', 'العلم العصبي الفيزيولوجي السريري'),
(10, 'Clinical radiology', 'التصوير الطبي السريري'),
(11, 'Dental, oral and maxillo-facial surgery', 'جراحة الأسنان والفم والوجه'),
(12, 'Dermato-venerology', 'أمراض الجلد والأمراض الجنسية'),
(13, 'Dermatology', 'أمراض الجلدية'),
(14, 'Endocrinology', 'علم الغدد الصماء'),
(15, 'Gastro-enterologic surgery', 'جراحة الجهاز الهضمي'),
(16, 'Gastroenterology', 'أمراض المعدة والأمعاء'),
(17, 'General hematology', 'الأمراض الدموية العامة'),
(18, 'General Practice', 'الطب العام'),
(19, 'General surgery', 'جراحة عامة'),
(20, 'Geriatrics', 'طب المسنين'),
(21, 'Immunology', 'مناعة'),
(22, 'Infectious diseases', 'الأمراض المعدية'),
(23, 'Internal medicine', 'الطب الباطني'),
(24, 'Laboratory medicine', 'طب المختبرات'),
(25, 'Maxillo-facial surgery', 'جراحة الفك والوجه'),
(26, 'Microbiology', 'علم الأحياء الدقيقة'),
(27, 'Nephrology', 'أمراض الكلى'),
(28, 'Neuro-psychiatry', 'أمراض الأعصاب والطب النفسي'),
(29, 'Neurology', 'أمراض الأعصاب'),
(30, 'Neurosurgery', 'جراحة الأعصاب'),
(31, 'Nuclear medicine', 'الطب النووي'),
(32, 'Obstetrics and gynecology', 'النسا والتوليد'),
(33, 'Occupational medicine', 'طب العمل'),
(34, 'Ophthalmology', 'طب العيون'),
(35, 'Orthopaedics', 'جراحة العظام'),
(36, 'Otorhinolaryngology', 'أمراض الأنف والأذن والحنجرة'),
(37, 'Paediatric surgery', 'جراحة الأطفال'),
(38, 'Paediatrics', 'طب الأطفال'),
(39, 'Pathology', 'علم المرض'),
(40, 'Pharmacology', 'علم الأدوية'),
(41, 'Physical medicine and rehabilitation', 'الطب الطبيعي وإعادة التأهيل'),
(42, 'Plastic surgery', 'جراحة التجميل'),
(43, 'Podiatric Medicine', 'طب القدمين'),
(44, 'Podiatric Surgery', 'جراحة القدمين'),
(45, 'Psychiatry', 'طب النفس'),
(46, 'Public health and Preventive Medicine', 'الصحة العامة والوقاية'),
(47, 'Radiology', 'التصوير الطبي'),
(48, 'Radiotherapy', 'العلاج الإشعاعي'),
(49, 'Respiratory medicine', 'أمراض الجهاز التنفسي'),
(50, 'Rheumatology', 'الروماتيزم'),
(51, 'Stomatology', 'طب الفم والأسنان'),
(52, 'Thoracic surgery', 'جراحة الصدر'),
(53, 'Tropical medicine', 'طب الأمراض الاستوائية'),
(54, 'Urology', 'جراحة المسالك البولية'),
(55, 'Vascular surgery', 'جراحة الأوعية الدموية'),
(56, 'Venereology', 'أمراض الجنس');

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE `video` (
  `id` tinyint(4) NOT NULL,
  `video` text NOT NULL,
  `type` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `video`
--

INSERT INTO `video` (`id`, `video`, `type`) VALUES
(1, 'pa.mp4', 'patient');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activation_person`
--
ALTER TABLE `activation_person`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activation_place`
--
ALTER TABLE `activation_place`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `ssd uniqu` (`ssd`,`phone_number`) USING HASH;

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appoint` (`patient_id`),
  ADD KEY `appoint2` (`clinic_id`);

--
-- Indexes for table `assistant`
--
ALTER TABLE `assistant`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `ssd` (`ssd`,`phone_number`) USING HASH;

--
-- Indexes for table `clinic`
--
ALTER TABLE `clinic`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`) USING HASH,
  ADD KEY `clinic1` (`assistant_id`),
  ADD KEY `clinic__2` (`doctor_id`);

--
-- Indexes for table `disease`
--
ALTER TABLE `disease`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disease1` (`patient_id`),
  ADD KEY `doc_dis` (`doctor_id`),
  ADD KEY `cli_dis` (`clinic_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `ssd` (`ssd`,`phone_number`) USING HASH;

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicne_prescipt` (`prescript_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ssd` (`ssd`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_number` (`phone_number`) USING HASH;

--
-- Indexes for table `pharmacist`
--
ALTER TABLE `pharmacist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `ssd` (`ssd`,`phone_number`) USING HASH;

--
-- Indexes for table `pharmacy`
--
ALTER TABLE `pharmacy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`) USING HASH,
  ADD KEY `phar_1` (`pharmacist_id`);

--
-- Indexes for table `pharmacy_order`
--
ALTER TABLE `pharmacy_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pharmacy_order` (`pharmacy_id`),
  ADD KEY `patient_order` (`patient_id`),
  ADD KEY `prescript_order` (`prescript_id`);

--
-- Indexes for table `pharmacy_prescript`
--
ALTER TABLE `pharmacy_prescript`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pharmacy1122` (`pharmacy_id`),
  ADD KEY `prescript1122` (`prescript_id`);

--
-- Indexes for table `prescript`
--
ALTER TABLE `prescript`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescript_doctor` (`doctor_id`),
  ADD KEY `prescript_patient` (`patient_id`),
  ADD KEY `prescript_disease` (`disease_id`),
  ADD KEY `prescript_clinic` (`clinic_id`);

--
-- Indexes for table `specialist`
--
ALTER TABLE `specialist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activation_person`
--
ALTER TABLE `activation_person`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `activation_place`
--
ALTER TABLE `activation_place`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `assistant`
--
ALTER TABLE `assistant`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `clinic`
--
ALTER TABLE `clinic`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `disease`
--
ALTER TABLE `disease`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `pharmacist`
--
ALTER TABLE `pharmacist`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pharmacy`
--
ALTER TABLE `pharmacy`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pharmacy_order`
--
ALTER TABLE `pharmacy_order`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pharmacy_prescript`
--
ALTER TABLE `pharmacy_prescript`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `prescript`
--
ALTER TABLE `prescript`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `specialist`
--
ALTER TABLE `specialist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `video`
--
ALTER TABLE `video`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activation_place`
--
ALTER TABLE `activation_place`
  ADD CONSTRAINT `clinic_125` FOREIGN KEY (`clinic_id`) REFERENCES `clinic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pharmacy_125` FOREIGN KEY (`pharmacy_id`) REFERENCES `pharmacy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appoint` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appoint2` FOREIGN KEY (`clinic_id`) REFERENCES `clinic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clinic`
--
ALTER TABLE `clinic`
  ADD CONSTRAINT `clinic1` FOREIGN KEY (`assistant_id`) REFERENCES `assistant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clinic__2` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `disease`
--
ALTER TABLE `disease`
  ADD CONSTRAINT `cli_dis` FOREIGN KEY (`clinic_id`) REFERENCES `clinic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `disease1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `doc_dis` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `medicine`
--
ALTER TABLE `medicine`
  ADD CONSTRAINT `medicne_prescipt` FOREIGN KEY (`prescript_id`) REFERENCES `prescript` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pharmacy`
--
ALTER TABLE `pharmacy`
  ADD CONSTRAINT `phar_1` FOREIGN KEY (`pharmacist_id`) REFERENCES `pharmacist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pharmacy_order`
--
ALTER TABLE `pharmacy_order`
  ADD CONSTRAINT `patient_order` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pharmacy_order` FOREIGN KEY (`pharmacy_id`) REFERENCES `pharmacy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prescript_order` FOREIGN KEY (`prescript_id`) REFERENCES `prescript` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pharmacy_prescript`
--
ALTER TABLE `pharmacy_prescript`
  ADD CONSTRAINT `pharmacy1122` FOREIGN KEY (`pharmacy_id`) REFERENCES `pharmacy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prescript1122` FOREIGN KEY (`prescript_id`) REFERENCES `prescript` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prescript`
--
ALTER TABLE `prescript`
  ADD CONSTRAINT `prescript_clinic` FOREIGN KEY (`clinic_id`) REFERENCES `clinic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prescript_disease` FOREIGN KEY (`disease_id`) REFERENCES `disease` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prescript_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prescript_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
