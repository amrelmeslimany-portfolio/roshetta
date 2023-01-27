-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2023 at 10:36 PM
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
(2, 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/Person_Img/Doctors/2222/5839277908719772029815301.png', 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/Person_Img/Doctors/2222/769230673822671593820086301.png', 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/Person_Img/Doctors/2222/560629280429335554758593192.png', 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/Person_Img/Doctors/2222/192511793584912480948460146.png', 1, 1, NULL),
(4, 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/Person_Img/Pharmacists/2222/87148277117176846858514568.png', 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/Person_Img/Pharmacists/2222/39692411191302239652106549.png', 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/Person_Img/Pharmacists/2222/133383905868699504790536994.jpg', 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/Person_Img/Pharmacists/2222/3201154018448784695990032.jpg', 0, NULL, 1);

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
(3, 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/place_Img/Pharmacy/8211895764/7923768211895764.png', 1, NULL, 1),
(4, 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/place_Img/Clinic/5911701717/3236415911701717.png', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` tinyint(4) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `ssd` bigint(14) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` text NOT NULL,
  `gender` varchar(10) NOT NULL,
  `birth_date` date NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_img` text DEFAULT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `admin_name`, `ssd`, `email`, `phone_number`, `gender`, `birth_date`, `password`, `profile_img`, `role`) VALUES
(1, 'samy mohamed', 2222, 'sam@gmail.com', '12345678912', 'ذكر', '2023-01-12', '$2y$10$YeH2107mwMOwoYs1XbtJ7ukKthMJRYzIhla.9lo6tTWh7GrYwJFbK', 'http://localhost:3000/ROSHETTA_API/API_Admin/API_IMG/Profile_Img_Admin/127/517728127.png', 'ADMIN'),
(3, 'hamdy ahmed', 33333333333333, 'ha@gmail.com', '01010101010', 'ذكر', '2023-01-18', '$2y$10$IuvMcAe49/0Top/QpZZfouVH7ec8ZY.vGxrDhwckrMqc5x82uwDNa', NULL, 'ADMIN'),
(4, 'ali ahmed', 12345678912345, 'ali@gmail.com', '01020231410', 'ذكر', '2023-01-05', '$2y$10$ah/NCnRsFJBmVjfCRZbGo.xgA8FPar4IdfUOro/b3Q9VMNli3WnLG', NULL, 'ADMIN');

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
(21, '2023-01-11', 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `assistant`
--

CREATE TABLE `assistant` (
  `id` smallint(6) NOT NULL,
  `assistant_name` varchar(50) NOT NULL,
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

INSERT INTO `assistant` (`id`, `assistant_name`, `ssd`, `email`, `phone_number`, `gender`, `governorate`, `birth_date`, `password`, `security_code`, `email_isactive`, `profile_img`, `role`) VALUES
(2, ' ahmed samy', 2222, 'ali@gmail.com', '01092338084', 'ذكر', 'الجيزة', '2023-01-27', '$2y$10$OuxHXUVVVMxd5yNNMTSnMeBDVRgpYMbzPbOTZWOB2TFj7IWmt/.XK', '', 0, 'http://localhost:3000/ROSHETTA_API/API_User/API_IMG/Profile_Img/Profile_assistant_img/12345678912345/13128012345678912345.png', 'ASSISTANT'),
(3, 'mohamed moahmed', 12345222291111, 'mohamedsaeed33451@gmail.com', '01010205042', 'ذكر', 'البحيرة', '2023-01-04', '$2y$10$yxmuV27h8q.DoX55BzvqEeAbbQrvqrdTXslnLTozycdXAdGROOq26', 'ecb71dbce7c0038573f4b4ea32cd92bf', 0, NULL, 'ASSISTANT'),
(4, 'محمد سعيد  جمعة', 96325874112365, 'mohamedsaeed00451@gmail.com', '01092338406', 'ذكر', 'البحيرة', '2023-01-13', '$2y$10$uVHX6s4GRPOhpHOHVFvHs.rZmkDSJuf1FdjqHJpKJZUR/6ABD6ERG', '36eed71d28feaf4abca6bf2b1dd8376b', 1, NULL, 'ASSISTANT');

-- --------------------------------------------------------

--
-- Table structure for table `clinic`
--

CREATE TABLE `clinic` (
  `id` mediumint(9) NOT NULL,
  `clinic_name` varchar(50) NOT NULL,
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

INSERT INTO `clinic` (`id`, `clinic_name`, `owner`, `clinic_specialist`, `phone_number`, `clinic_price`, `start_working`, `end_working`, `governorate`, `address`, `logo`, `ser_id`, `doctor_id`, `assistant_id`) VALUES
(1, 'nnnnnnnn', 'mohamed ahmed', 'dddddddd', '01090336055', 500, '05:05:00', '05:05:00', 'البحيرة', 'ggggggggvvv', NULL, '5911701717', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `disease`
--

CREATE TABLE `disease` (
  `id` smallint(6) NOT NULL,
  `disease_name` varchar(255) NOT NULL,
  `disease_place` varchar(255) NOT NULL,
  `disease_date` date NOT NULL,
  `patient_id` mediumint(9) NOT NULL,
  `doctor_id` smallint(6) NOT NULL,
  `clinic_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `disease`
--

INSERT INTO `disease` (`id`, `disease_name`, `disease_place`, `disease_date`, `patient_id`, `doctor_id`, `clinic_id`) VALUES
(7, 'lllllllll', 'lllllllllll', '2023-01-05', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `id` smallint(6) NOT NULL,
  `doctor_name` varchar(50) NOT NULL,
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

INSERT INTO `doctor` (`id`, `doctor_name`, `ssd`, `email`, `phone_number`, `gender`, `birth_date`, `specialist`, `governorate`, `password`, `security_code`, `email_isactive`, `profile_img`, `role`) VALUES
(1, ' ahmed  ali', 2222, 'ah@gmail.com', '01092338086', 'ذكر', '2023-01-06', 'kkkkkkkkk', 'المنوفية', '$2y$10$VSWPSIxxIJg21GytIQITq.7oSuPKyDBkvb.Me25WLqMwp/9gbh1B.', '', 0, 'http://localhost:3000/ROSHETTA_API/API_User/API_IMG/Profile_Img/Profile_doctor_img/2222/4465652222.png', 'DOCTOR'),
(3, 'عمرو المسلمانى', 12345222291511, 'mohamedsaeed33451@gmail.com', '01010205042', 'ذكر', '2023-01-04', 'lllllllll', 'البحيرة', '$2y$10$YM9jIyC4VF0NghwkYj9gLuL5y4RuD4uh688t61matulwylpqr.q3C', '65e53fa02327324fbad8e1704caa089b', 0, NULL, 'DOCTOR'),
(4, 'عمرو المسلمانى', 12345222291512, 'ammghdefe45353@gmail.com', '01010205044', 'ذكر', '2023-01-04', 'lllllllll', 'البحيرة', '$2y$10$1i/.TFnM8fyRnkeM5EHbouBV82DqUTPrel86X.y6APr.okEjMbRIO', '8a36a8b586dcc5f5cc0e13422efe3c7e', 1, NULL, 'DOCTOR'),
(9, 'عبدالرحمن احمد  عبدالله', 74185296312365, 'mohamedsaeed00451@gmail.com', '01145823697', 'ذكر', '2023-01-12', 'mmm', 'البحيرة', '$2y$10$lj.DloxPMKTUS8ESkA05bOAX2kJuScU6Zlv1Ut5WihljISDe6qBM2', 'e9200a64953e32472944fe1cf5dff1b0', 1, NULL, 'DOCTOR');

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `id` bigint(20) NOT NULL,
  `medicine_data` text NOT NULL,
  `prescript_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` smallint(6) NOT NULL,
  `username` varchar(50) NOT NULL,
  `ssd` bigint(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` mediumint(9) NOT NULL,
  `patient_name` varchar(50) NOT NULL,
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

INSERT INTO `patient` (`id`, `patient_name`, `ssd`, `email`, `phone_number`, `gender`, `birth_date`, `weight`, `height`, `governorate`, `password`, `security_code`, `email_isactive`, `profile_img`, `role`) VALUES
(1, 'mohamed ahmed', 2222, 'mo@gmail.com', '01092338086', 'ذكر', '2023-01-19', 33, 100, 'اسوان', '$2y$10$OCuZ6iXudrGqV4fAjQsQvevUiYK4qYKJzEJpV89dg5FgMWgaYTmQK', '', 0, 'http://localhost:3000/ROSHETTA_API/API_User/API_IMG/Profile_Img/Profile_patient_img/2222/560052222.png', 'PATIENT'),
(2, 'slama', 12345678912342, 'sl@gmail.com', '01020304050', 'ذكر', '2023-01-12', 100, 200, 'الجيزة', '$2y$10$lxZ1.7QJ5OFtNEZL5Lx2v.4DxqC.EE73pce5W.ZAu0FWg8uEIWhOu', '', 0, NULL, 'PATIENT'),
(15, 'عمرو المسلمانى', 12345222296333, 'ammghdefe45353@gmail.com', '01010205045', 'ذكر', '2023-01-04', 5, 3, 'البحيرة', '$2y$10$lxfWu4TTBKkVL/fgJYLLY.YUpE514OQLk0.1I/mkTT31jL9QplmIu', 'd86c2beb6410a7582ac714abf310f3b0', 0, NULL, 'PATIENT'),
(27, 'محمد  سعيد', 12345222291511, 'mohamedsaeed33451@gmail.com', '01010205042', 'ذكر', '2023-01-04', 5, 3, 'البحيرة', '$2y$10$A37Kw7O48ovIGqnpmSMq2.2aTuf.SyH3Oru.COtlGouf39JADFErG', 'c2f4a7b5f99cfa4a36eb56857fbe1689', 0, NULL, 'PATIENT'),
(30, 'عبدالرحمن احمد  عبدالله', 74185296312365, 'mohamedsaeed00451@gmail.com', '01145823697', 'ذكر', '2023-01-12', 10, 45, 'البحيرة', '$2y$10$JVcItuHL3mkiH7D9HHxuhOgkU6gM2c5CVqsMqDBXcGdWEPTxRc1Ba', '86591cd5dae7dc01d07f434952ebce32', 1, NULL, 'PATIENT');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacist`
--

CREATE TABLE `pharmacist` (
  `id` smallint(6) NOT NULL,
  `pharmacist_name` varchar(50) NOT NULL,
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

INSERT INTO `pharmacist` (`id`, `pharmacist_name`, `ssd`, `email`, `phone_number`, `gender`, `birth_date`, `governorate`, `password`, `security_code`, `email_isactive`, `profile_img`, `role`) VALUES
(1, ' ahmed  ali', 2222, 'al@gmail.com', '01092338087', 'ذكر', '2023-01-27', 'المنوفية', '$2y$10$G.RIKu83hLeuXGg2OG/zqeRlP7ue35ycF9bfRZoGBHP3VAAoaWR5e', '', 0, 'http://localhost:3000/ROSHETTA_API/API_User/API_IMG/Profile_Img/Profile_pharmacist_img/2222/4430552222.png', 'PHARMACIST'),
(2, 'mohamed moahmed', 12345222291111, 'mohamedsaeed33451@gmail.com', '01010205042', 'ذكر', '2023-01-04', 'البحيرة', '$2y$10$hdd2w2JjYjxfpbSbe1ESuuOHzU66L1oW3xocF0Qo/xlrSCj7xPhly', '30dbbd1bb9355f616b657dd20a407ada', 0, NULL, 'PHARMACIST'),
(3, 'محمد سعيد  جمعة عطية السيد', 96325874112365, 'mohamedsaeed00451@gmail.com', '01092338406', 'ذكر', '2023-01-13', 'البحيرة', '$2y$10$nWjZhhDG8kI6twqlmah4Lu00Z7k5JxBJwD46UrA9ZOmJQDX1Kw9uu', 'ccdfe192866e5f81fe85d99fe5cfbecc', 1, NULL, 'PHARMACIST');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy`
--

CREATE TABLE `pharmacy` (
  `id` mediumint(9) NOT NULL,
  `pharmacy_name` varchar(255) NOT NULL,
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

INSERT INTO `pharmacy` (`id`, `pharmacy_name`, `owner`, `phone_number`, `start_working`, `end_working`, `governorate`, `address`, `logo`, `ser_id`, `pharmacist_id`) VALUES
(1, '', 'ali mohamed', '01010101254', '04:04:00', '05:05:00', 'البحيرة', 'ooooooooooo', 'http://localhost:3000/ROSHETTA_API/API_C_P/API_IMG/Logo_Img/Pharmacy/8211895764/4199418211895764.jpg', '8211895764', 1);

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
(7, 11, 1, '2023-01-25 13:44:35'),
(8, 11, 1, '2023-01-25 13:45:11');

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
(11, '2023-01-25 15:44:16', '2023-01-12', '5555555555', 1, 7, 1, 1);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activation_person`
--
ALTER TABLE `activation_person`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `activation_place`
--
ALTER TABLE `activation_place`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `assistant`
--
ALTER TABLE `assistant`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clinic`
--
ALTER TABLE `clinic`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `disease`
--
ALTER TABLE `disease`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `pharmacist`
--
ALTER TABLE `pharmacist`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pharmacy`
--
ALTER TABLE `pharmacy`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pharmacy_prescript`
--
ALTER TABLE `pharmacy_prescript`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `prescript`
--
ALTER TABLE `prescript`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
