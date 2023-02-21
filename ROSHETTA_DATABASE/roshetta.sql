-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2023 at 11:49 PM
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
  `front_nationtional_card` text NOT NULL,
  `back_nationtional_card` text NOT NULL,
  `graduation_cer` text NOT NULL,
  `card_id_img` text NOT NULL,
  `isactive` tinyint(1) NOT NULL,
  `doctor_id` smallint(11) DEFAULT NULL,
  `pharmacist_id` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `activation_person`
--

INSERT INTO `activation_person` (`id`, `front_nationtional_card`, `back_nationtional_card`, `graduation_cer`, `card_id_img`, `isactive`, `doctor_id`, `pharmacist_id`) VALUES
(4, 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/Person_Img/Pharmacists/99999999999999/9593668fb1bf3e0a24fb.png', 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/Person_Img/Pharmacists/99999999999999/42e207264d886ac1c349.jpg', 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/Person_Img/Pharmacists/99999999999999/4e4858f479dd3f46a395.jpg', 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/Person_Img/Pharmacists/99999999999999/43ab59e6d7e112cc382f.jpg', 1, 13, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activation_place`
--

CREATE TABLE `activation_place` (
  `id` smallint(6) NOT NULL,
  `license_img` text NOT NULL,
  `isactive` tinyint(1) NOT NULL,
  `clinic_id` mediumint(9) DEFAULT NULL,
  `pharmacy_id` mediumint(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `activation_place`
--

INSERT INTO `activation_place` (`id`, `license_img`, `isactive`, `clinic_id`, `pharmacy_id`) VALUES
(3, 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/place_Img/Pharmacy/8211895764/788588211895764.png', 1, NULL, 1),
(6, 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/place_Img/Clinic/145238/503c5b841edaf13e16ca145238.png', 0, 10, NULL);

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
  `password` varchar(255) NOT NULL,
  `security_code` text NOT NULL,
  `email_isactive` tinyint(1) NOT NULL,
  `profile_img` text DEFAULT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `ssd`, `email`, `phone_number`, `gender`, `birth_date`, `password`, `security_code`, `email_isactive`, `profile_img`, `role`) VALUES
(1, 'samy mohamed', 2222, 'sam@gmail.com', '01010205040', 'ذكر', '2000-01-12', '$2y$10$kkFMOz4K0IL32AFbeHj75ezc7ezEVs9yDNXtDd9HGjLPARJxjEgZ6', '', 1, 'http://localhost:3000/ROSHETTA_API/API_Admin/API_IMG/Profile_Img_Admin/2222/a22cf7a70bf640d892db2222.jpg', 'ADMIN'),
(3, 'hamdy ahmed', 33333333333333, 'ha@gmail.com', '01010101010', 'ذكر', '2023-01-18', '$2y$10$IuvMcAe49/0Top/QpZZfouVH7ec8ZY.vGxrDhwckrMqc5x82uwDNa', '', 0, NULL, 'ADMIN'),
(4, 'ali ahmed', 12345678912345, 'ali@gmail.com', '01020231410', 'ذكر', '2023-01-05', '$2y$10$ah/NCnRsFJBmVjfCRZbGo.xgA8FPar4IdfUOro/b3Q9VMNli3WnLG', '', 0, NULL, 'ADMIN'),
(5, 'ahmed mohamed', 11111111111111, 'ah@gmail.com', '01010101011', 'male', '2000-10-12', '$2y$10$dxxRGwYI.QcoW5N.16WyoeN4VhfI7pcXzXcwXeVQ7cI2treOZ3NqW', '3aec9dc5cc33ecb878953000fa9d210e', 0, NULL, 'ADMIN');

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
(27, '2023-02-03', 1, 58, 10),
(28, '2023-02-02', 1, 55, 10),
(29, '2023-02-04', 0, 54, 10),
(30, '2023-02-10', 0, 58, 10);

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
  `email_isactive` tinyint(1) NOT NULL,
  `profile_img` text DEFAULT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `assistant`
--

INSERT INTO `assistant` (`id`, `name`, `ssd`, `email`, `phone_number`, `gender`, `governorate`, `birth_date`, `password`, `security_code`, `email_isactive`, `profile_img`, `role`) VALUES
(1, 'ahmed ali', 99999999999999, 'mohamedsaeed00451@gmail.com', '36985214789', 'ذكر', 'البحيرة', '2023-01-12', '$2y$10$yZqkRs7N8bwd14G3rZ9jmuoeDYB1zQ0H6YygwGWNwbRvwVDA1jXDi', '9a00a6a54ac912bf27a0d0f2b5e45001', 1, 'http://localhost:3000/ROSHETTA_API/API_User/API_IMG/Profile_Img/Profile_assistant_img/99999999999999/93163599999999999999.png', 'ASSISTANT');

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
  `clinic_specialist` varchar(255) NOT NULL,
  `phone_number` text NOT NULL,
  `clinic_price` smallint(6) NOT NULL,
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

INSERT INTO `clinic` (`id`, `name`, `owner`, `clinic_specialist`, `phone_number`, `clinic_price`, `start_working`, `end_working`, `governorate`, `address`, `logo`, `ser_id`, `doctor_id`, `assistant_id`) VALUES
(10, 'Dr mohamed saeed', 'mohamed ', 'dentist', '01000000000', 50, '01:00:00', '08:00:00', 'aswan', 'aswan', 'http://localhost:3000/ROSHETTA_API/API_C_P/API_IMG/Logo_Img/Clinic/145238/1e499858fd3bd3494c04145238.png', '145238', 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `disease`
--

CREATE TABLE `disease` (
  `id` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `disease_place` varchar(255) NOT NULL,
  `disease_date` date NOT NULL,
  `patient_id` mediumint(9) NOT NULL,
  `doctor_id` smallint(6) NOT NULL,
  `clinic_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `disease`
--

INSERT INTO `disease` (`id`, `name`, `disease_place`, `disease_date`, `patient_id`, `doctor_id`, `clinic_id`) VALUES
(8, 'headache', 'head', '2023-02-02', 58, 13, 10),
(10, 'mmm', 'nnnnnn', '2023-02-05', 58, 13, 10);

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
  `email_isactive` tinyint(1) NOT NULL,
  `profile_img` text DEFAULT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `name`, `ssd`, `email`, `phone_number`, `gender`, `birth_date`, `specialist`, `governorate`, `password`, `security_code`, `email_isactive`, `profile_img`, `role`) VALUES
(13, 'mohamed saeed ', 11111111111115, 'mohamedsaeed00451@gmail.com', '01000000001', 'ذكر', '2001-01-12', 'dentist', 'البحيرة', '$2y$10$IYm3fOKcoceVXMNGH.uKMu05auB2UT7iLtgNXdn/X7YhHKRIvdo/W', 'e184be411ff02df2e47efeb47ef52541', 1, NULL, 'DOCTOR'),
(14, 'محمد سعيد', 41052063096325, 'mo@gmail.com', '01020305040', 'ذكر', '2023-01-04', 'ggggggg', 'البحيرة', '$2y$10$1/H4rcTWBe0v42Updbfy4eabwv.qZS6ABZ8v1jqauCd4BNr1OY5Xq', 'bcd0783a21e5220cc3375e09f59d3a0a', 1, NULL, 'DOCTOR');

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
(8, 'YToxOntpOjE7YTozOntzOjQ6Im5hbWUiO3M6NjoicHJvZmluIjtzOjQ6InNpemUiO3M6MzoiMTAwIjtzOjExOiJkZXNjcmlwdGlvbiI7czoxMjoiMiBiZWZvciBlYXRlIjt9fQ==', 14);

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
(5, 'mohamed saeed', 11122233344455, 'mo@gmail.com', 'hello roshetta', '2023-02-02 13:20:45', 1, 'PATIENT');

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
  `email_isactive` double NOT NULL,
  `profile_img` text DEFAULT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `name`, `ssd`, `email`, `phone_number`, `gender`, `birth_date`, `weight`, `height`, `governorate`, `password`, `security_code`, `email_isactive`, `profile_img`, `role`) VALUES
(54, 'محمود', 11111111, 'mohamedsaeed33451@gmail.com', '011122', 'mm', '2023-01-12', 4, 55, 'fffffff', '11', 'jjjjjjjjj', 1, 'llllllllll', 'hhh'),
(55, 'احمد ', 8888, 'mohamedsaeed44451@gmail.com', '111', 'ت', '2023-01-12', 441, 4, 'ت', '44', '44', 1, 'ح', 'تت'),
(58, 'mohamed', 36985214789652, 'mohamed@gmail.com', '01022223335', 'male', '1999-10-04', 90, 140, 'aswan', '$2y$10$s6n1BuAevOQjadD48QS8ru4KdyM6zTZwo2yCmJBXtHdEGMw3/xDey', '0757a7705087c1eee179f53383232c47', 0, NULL, 'PATIENT'),
(63, 'mohamed saeed', 12345678912344, 'mohamedsaeed00451@gmail.com', '01092338086', 'male', '2003-02-08', 60, 173, 'beheira', '$2y$10$t0YqxwKKzfOPOeDDOYOmSuZtvJCCGYbg72ukPiLb2KIGjInMp1vaK', '1b746924a73a1158aa11990c3b93d9ec', 1, NULL, 'PATIENT');

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
  `email_isactive` tinyint(1) NOT NULL,
  `profile_img` text DEFAULT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pharmacist`
--

INSERT INTO `pharmacist` (`id`, `name`, `ssd`, `email`, `phone_number`, `gender`, `birth_date`, `governorate`, `password`, `security_code`, `email_isactive`, `profile_img`, `role`) VALUES
(1, 'عمرو المسلمانى', 99999999999999, 'ammghdefe45353@gmail.com', '36985214789', 'ذكر', '2023-01-12', 'البحيرة', '$2y$10$53zyweTCQd/SOiu7fjS71eawHwd/z1yDjPH9AEZfbYmsfU6lO6vI.', '589cb4b906218c4209c1bbbafada8845', 1, 'http://localhost:3000/ROSHETTA_API/API_User/API_IMG/Profile_Img/Profile_pharmacist_img/99999999999999/51849799999999999999.jpg', 'PHARMACIST');

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
  `time` time NOT NULL,
  `patient_id` mediumint(9) NOT NULL,
  `prescript_id` bigint(20) NOT NULL,
  `pharmacy_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pharmacy_order`
--

INSERT INTO `pharmacy_order` (`id`, `time`, `patient_id`, `prescript_id`, `pharmacy_id`) VALUES
(1, '04:58:00', 58, 14, 1),
(2, '04:58:00', 54, 16, 1),
(16, '23:51:00', 55, 15, 1);

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

--
-- Dumping data for table `pharmacy_prescript`
--

INSERT INTO `pharmacy_prescript` (`id`, `prescript_id`, `pharmacy_id`, `date_pay`) VALUES
(9, 15, 1, '2023-02-03 13:08:56');

-- --------------------------------------------------------

--
-- Table structure for table `prescript`
--

CREATE TABLE `prescript` (
  `id` bigint(20) NOT NULL,
  `creaded_date` datetime NOT NULL DEFAULT current_timestamp(),
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

INSERT INTO `prescript` (`id`, `creaded_date`, `rediscovery_date`, `ser_id`, `patient_id`, `disease_id`, `doctor_id`, `clinic_id`) VALUES
(14, '2023-02-03 14:27:13', '2023-02-08', '235698', 58, 8, 13, 10),
(15, '2023-02-03 14:38:06', '2023-10-12', '34898858', 55, 8, 13, 10),
(16, '2023-02-05 21:35:23', '2023-02-10', '18841058', 54, 10, 13, 10);

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
(2, 'http://localhost:3000/ROSHETTA_API/API_Admin/Video/doctor/24144213.mp4', 'doctor'),
(3, 'http://localhost:3000/ROSHETTA_API/API_Admin/Video/assistant/57122609.mp4', 'assistant'),
(4, 'http://localhost:3000/ROSHETTA_API/API_Admin/Video/pharmacist/57876410.mp4', 'pharmacist'),
(5, 'http://localhost:3000/ROSHETTA_API/API_Admin/Video/patient/64707331.mp4', 'patient');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activation_person`
--
ALTER TABLE `activation_person`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_122` (`doctor_id`),
  ADD KEY `pharmcist_122` (`pharmacist_id`);

--
-- Indexes for table `activation_place`
--
ALTER TABLE `activation_place`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_125` (`clinic_id`),
  ADD KEY `pharmacy_125` (`pharmacy_id`);

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
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `activation_place`
--
ALTER TABLE `activation_place`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `assistant`
--
ALTER TABLE `assistant`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clinic`
--
ALTER TABLE `clinic`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `disease`
--
ALTER TABLE `disease`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `pharmacist`
--
ALTER TABLE `pharmacist`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pharmacy`
--
ALTER TABLE `pharmacy`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pharmacy_order`
--
ALTER TABLE `pharmacy_order`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pharmacy_prescript`
--
ALTER TABLE `pharmacy_prescript`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `prescript`
--
ALTER TABLE `prescript`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `video`
--
ALTER TABLE `video`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activation_person`
--
ALTER TABLE `activation_person`
  ADD CONSTRAINT `doctor_122` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pharmcist_122` FOREIGN KEY (`pharmacist_id`) REFERENCES `pharmacist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
