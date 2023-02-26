-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2023 at 07:04 PM
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
(3, 'http://localhost:3000/ROSHETTA_API/API_Activation/IMG/place_Img/Pharmacy/8211895764/788588211895764.png', 1, NULL, 1);

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
(1, 'samy mohamed', 22222222222222, 'mohamedsaeed00451@gmail.com', '01010205040', 'ذكر', '2000-01-12', '', '$2y$10$kkFMOz4K0IL32AFbeHj75ezc7ezEVs9yDNXtDd9HGjLPARJxjEgZ6', '898890', NULL, 1, 'http://localhost:3000/ROSHETTA_API/API_Admin/API_IMG/Profile_Img_Admin/2222/a22cf7a70bf640d892db2222.jpg', 'admin'),
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
(14, 'محمد سعيد', 41052063096325, 'mo@gmail.com', '01020305040', 'ذكر', '2023-01-04', 'ggggggg', 'البحيرة', '$2y$10$1/H4rcTWBe0v42Updbfy4eabwv.qZS6ABZ8v1jqauCd4BNr1OY5Xq', 'bcd0783a21e5220cc3375e09f59d3a0a', NULL, 1, NULL, 'DOCTOR'),
(15, 'mmss', 99999999999998, 'mohamedsaeed0451@gmail.com', '36985214787', 'ddd', '0000-00-00', 'fffffffffff', 'dd', '$2y$10$1ZWJ8IMJ3Q6GIxgXayqql.ohppMXy1kdlvg4/iB5R22JyPilDe4Ju', '771995', NULL, 0, NULL, 'doctor'),
(16, 'mmss', 99999999999997, 'mohamedsaeed451@gmail.com', '36985214788', 'ddd', '0000-00-00', 'fffffffffff', 'dd', '$2y$10$p0lnHBknX0MkCJziJ59YkON9NHQkjdLtf5ATXFsK0VkO5G2TW1tPG', '527565', NULL, 0, NULL, 'doctor'),
(32, 'mohamedsaeed', 12345678912345, 'mohamedsaeed00451@gmail.com', '01092338086', 'ddd', '2000-10-22', 'fffffffffff', 'dd', '$2y$10$Mf.dARNtZ2VtshgjNRCn3.ciNfBt1TFBCNme1Igm7FdsjkEwe0q5C', '605438', NULL, 0, NULL, 'doctor'),
(33, ' ', 12345678912385, 'm@gmail.com', '01032568974', 'd', '0000-00-00', 'bb', 'd', '$2y$10$dMsI8UtkiBNTcZvAp4fLMulpCwwkNFKf8.nOMYPShVEHY.UjuBtdi', '898613', NULL, 0, NULL, 'doctor');

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
(63, 'mohamed saeed', 12345678912344, 'mohamedsaeed00451@gmail.com', '01092338086', 'male', '2000-03-24', 60, 173, 'beheira', '$2y$10$t0YqxwKKzfOPOeDDOYOmSuZtvJCCGYbg72ukPiLb2KIGjInMp1vaK', '1b746924a73a1158aa11990c3b93d9ec', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NjMsInR5cGUiOiJQQVRJRU5UIiwiZXhwIjoxNjc3MTc3OTg5fQ.E5bjGueiHIZypWCucfURWwHVMDBJBKynnC2Uy87hodA', 1, NULL, 'patient'),
(64, 'mmss', 99999999999998, 'mohamedsaeed0451@gmail.com', '36985214787', 'ddd', '0000-00-00', 22, 22, 'dd', '$2y$10$DOUaal1OscFvIngjQ7xvu..5YFQLThuQcwp909OQxMUKHMj0lYrYi', '815493', NULL, 0, NULL, 'patient'),
(69, 'محمد سعيد جمعة عطية', 12345678912345, 'mohamedsaeed11451@gmail.com', '01092358086', 'ddd', '2000-10-22', 22, 22, 'dd', '$2y$10$hihGqC1mXfu0nvpcUBReeeb3/zQQQLMpl2UYzBwZYmwYDmmGftDTG', '764723', NULL, 0, NULL, 'patient');

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
(1, 'عمرو المسلمانى', 99999999999999, 'ammghdefe45353@gmail.com', '36985214789', 'ذكر', '2023-01-12', 'البحيرة', '$2y$10$53zyweTCQd/SOiu7fjS71eawHwd/z1yDjPH9AEZfbYmsfU6lO6vI.', '589cb4b906218c4209c1bbbafada8845', NULL, 1, 'http://localhost:3000/ROSHETTA_API/API_User/API_IMG/Profile_Img/Profile_pharmacist_img/99999999999999/51849799999999999999.jpg', 'PHARMACIST'),
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
  `time` time NOT NULL,
  `patient_id` mediumint(9) NOT NULL,
  `prescript_id` bigint(20) NOT NULL,
  `pharmacy_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `creaded_date` datetime NOT NULL DEFAULT current_timestamp(),
  `rediscovery_date` date NOT NULL,
  `ser_id` varchar(255) NOT NULL,
  `patient_id` mediumint(9) NOT NULL,
  `disease_id` smallint(6) NOT NULL,
  `doctor_id` smallint(6) NOT NULL,
  `clinic_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(1, 'http://localhost:3000/ROSHETTA_API/API_Admin/API_Video/Video/patient/30b1d6c53fcb88abca19.mp4', 'patient');

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
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `assistant`
--
ALTER TABLE `assistant`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

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
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

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
