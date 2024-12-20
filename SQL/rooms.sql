-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2024 at 02:59 AM
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
-- Database: `itcs333 project`
--

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_name` varchar(100) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `equipment` text DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `floor` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `thumbnail_2` varchar(255) DEFAULT NULL,
  `thumbnail_3` varchar(255) DEFAULT NULL,
  `thumbnail_4` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `capacity`, `equipment`, `department`, `floor`, `image`, `thumbnail_2`, `thumbnail_3`, `thumbnail_4`) VALUES
(1, 'Lab 021', 30, 'PC, Projector, Whiteboard, WiFi', 'IS', 'Grand Floor', 'isl0.jpg', 'isl1.jpg', 'isl2.jpg', 'isl0.jpg'),
(2, 'Lab 023', 25, 'PC, Projector, Whiteboard, WiFi', 'IS', 'Grand Floor', 'isl0.jpg', 'isl1.jpg', 'isl2.jpg', 'isl0.jpg'),
(3, 'Room 028', 20, 'PC, Projector, Whiteboard, WiFi', 'IS', 'Grand Floor', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(4, 'Room 029', 40, 'PC, Projector, Whiteboard, WiFi', 'IS', 'Grand Floor', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(5, 'Lab 030', 35, 'PC, Projector, Whiteboard, WiFi', 'IS', 'Grand Floor', 'isl0.jpg', 'isl1.jpg', 'isl2.jpg', 'isl0.jpg'),
(6, 'Room 032', 25, 'PC, Projector, Whiteboard, WiFi', 'IS', 'Grand Floor', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(7, 'Lab 1006', 50, 'PC Lan, Projector, Whiteboard, WiFi', 'IS', 'First Floor', 'isl0.jpg', 'isl1.jpg', 'isl2.jpg', 'isl0.jpg'),
(8, 'Lab 1008', 45, 'PC, Projector, Whiteboard, WiFi', 'IS', 'First Floor', 'isl0.jpg', 'isl1.jpg', 'isl2.jpg', 'isl0.jpg'),
(9, 'Room 1010', 60, 'PC, Projector, Whiteboard, WiFi', 'IS', 'First Floor', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(10, 'Room 1011', 45, 'PC, Projector, Whiteboard, WiFi', 'IS', 'First Floor', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(11, 'Lab 1012', 45, 'PC, Projector, Whiteboard, WiFi', 'IS', 'First Floor', 'isl0.jpg', 'isl1.jpg', 'isl2.jpg', 'isl0.jpg'),
(12, 'Room 1014', 45, 'PC, Projector, Whiteboard, WiFi', 'IS', 'First Floor', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(13, 'Lab 2005', 45, 'PC, Projector, Whiteboard, WiFi', 'IS', 'Second Floor', 'isl0.jpg', 'isl1.jpg', 'isl2.jpg', 'isl0.jpg'),
(14, 'Lab 2007', 45, 'PC Lan, Projector, Whiteboard, WiFi', 'IS', 'Second Floor', 'isl0.jpg', 'isl1.jpg', 'isl2.jpg', 'isl0.jpg'),
(15, 'Room 2008', 45, 'PC, Projector, Whiteboard, WiFi', 'IS', 'Second Floor', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(16, 'Room 2010', 45, 'PC, Projector, Whiteboard, WiFi', 'IS', 'Second Floor', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(17, 'Room 2011', 45, 'PC, Projector, Whiteboard, WiFi', 'IS', 'Second Floor', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(18, 'Room 2012', 45, 'PC, Projector, Whiteboard, WiFi', 'IS', 'Second Floor', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(19, 'Lab 2013', 45, 'PC Lan, Projector, Whiteboard, WiFi', 'IS', 'Second Floor', 'isl0.jpg', 'isl1.jpg', 'isl2.jpg', 'isl0.jpg'),
(20, 'Lab 2015', 45, 'PC Lan, Projector, Whiteboard, WiFi', 'IS', 'Second Floor', 'isl0.jpg', 'isl1.jpg', 'isl2.jpg', 'isl0.jpg'),
(21, 'Room 049', 30, 'PC, Projector, Whiteboard, WiFi', 'CS', 'Grand Floor', 'cs0.jpg', 'cs1.jpg', 'cs2.jpg', 'cs3.jpg'),
(22, 'Lab 051', 25, 'PC, Projector, Whiteboard, WiFi', 'CS', 'Grand Floor', 'lab.jpg', 'lab.jpg', 'lab.jpg', 'lab.jpg'),
(23, 'Room 056', 20, 'PC, Projector, Whiteboard, WiFi', 'CS', 'Grand Floor', 'cs0.jpg', 'cs1.jpg', 'cs2.jpg', 'cs3.jpg'),
(24, 'Room 057', 40, 'PC, Projector, Whiteboard, WiFi', 'CS', 'Grand Floor', 'cs0.jpg', 'cs1.jpg', 'cs2.jpg', 'cs3.jpg'),
(25, 'Lab 058', 35, 'PC Lan, Projector, Whiteboard, WiFi', 'CS', 'Grand Floor', 'lab.jpg', 'lab.jpg', 'lab.jpg', 'lab.jpg'),
(26, 'Room 060', 25, 'PC, Projector, Whiteboard, WiFi', 'CS', 'Grand Floor', 'cs0.jpg', 'cs1.jpg', 'cs2.jpg', 'cs3.jpg'),
(27, 'Room 1043', 50, 'PC Lan, Projector, Whiteboard, WiFi', 'CS', 'First Floor', 'cs0.jpg', 'cs1.jpg', 'cs2.jpg', 'cs3.jpg'),
(28, 'Room 1045', 45, 'PC, Projector, Whiteboard, WiFi', 'CS', 'First Floor', 'cs0.jpg', 'cs1.jpg', 'cs2.jpg', 'cs3.jpg'),
(29, 'Room 1047', 60, 'PC, Projector, Whiteboard, WiFi', 'CS', 'First Floor', 'cs0.jpg', 'cs1.jpg', 'cs2.jpg', 'cs3.jpg'),
(30, 'Room 1048', 45, 'PC, Projector, Whiteboard, WiFi', 'CS', 'First Floor', 'cs0.jpg', 'cs1.jpg', 'cs2.jpg', 'cs3.jpg'),
(31, 'Room 1050', 45, 'PC, Projector, Whiteboard, WiFi', 'CS', 'First Floor', 'cs0.jpg', 'cs1.jpg', 'cs2.jpg', 'cs3.jpg'),
(32, 'Room 1052', 45, 'PC, Projector, Whiteboard, WiFi', 'CS', 'First Floor', 'cs0.jpg', 'cs1.jpg', 'cs2.jpg', 'cs3.jpg'),
(33, 'Lab 2043', 45, 'PC Lan, Projector, Whiteboard, WiFi', 'CS', 'Second Floor', 'lab.jpg', 'lab.jpg', 'lab.jpg', 'lab.jpg'),
(34, 'Lab 2045', 45, 'PC Lan, Projector, Whiteboard, WiFi', 'CS', 'Second Floor', 'lab.jpg', 'lab.jpg', 'lab.jpg', 'lab.jpg'),
(35, 'Room 2046', 45, 'PC, Projector, Whiteboard, WiFi', 'CS', 'Second Floor', 'cs0.jpg', 'cs1.jpg', 'cs2.jpg', 'cs3.jpg'),
(36, 'Room 2048', 45, 'PC, Projector, Whiteboard, WiFi', 'CS', 'Second Floor', 'cs0.jpg', 'cs1.jpg', 'cs2.jpg', 'cs3.jpg'),
(37, 'Room 2049', 45, 'PC, Projector, Whiteboard, WiFi', 'CS', 'Second Floor', 'cs0.jpg', 'cs1.jpg', 'cs2.jpg', 'cs3.jpg'),
(38, 'Room 2050', 45, 'PC, Projector, Whiteboard, WiFi', 'CS', 'Second Floor', 'cs0.jpg', 'cs1.jpg', 'cs2.jpg', 'cs3.jpg'),
(39, 'Lab 2051 (Benefit 1)', 45, 'PC Lan, Projector, Whiteboard, WiFi', 'CS', 'Second Floor', 'benefit0.jpg', 'benefit1.jpg', 'benefit0.jpg', 'benefit1.jpg'),
(40, 'Lab 2053 (Benefit 2)', 30, 'PC, Projector, Whiteboard, WiFi', 'CS', 'Second Floor', 'benefit0.jpg', 'benefit1.jpg', 'benefit0.jpg', 'benefit1.jpg'),
(41, 'Room 077', 30, 'PC, Projector, Whiteboard, WiFi', 'NE', 'Grand Floor', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(42, 'Lab 079', 25, 'PC, Projector, Whiteboard, WiFi', 'NE', 'Grand Floor', 'nell0.jpg', 'nell1.jpg', 'nell2.jpg', 'nell3.jpg'),
(43, 'Room 084', 20, 'PC, Projector, Whiteboard, WiFi', 'NE', 'Grand Floor', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(44, 'Room 085', 40, 'PC, Projector, Whiteboard, WiFi', 'NE', 'Grand Floor', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(45, 'Lab 086', 40, 'PC, Projector, Whiteboard, WiFi', 'NE', 'Grand Floor', 'nell0.jpg', 'nell1.jpg', 'nell2.jpg', 'nell3.jpg'),
(46, 'Room 088', 40, 'PC, Projector, Whiteboard, WiFi', 'NE', 'Grand Floor', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(47, 'Lab 1081 (Advanced Digital Laboratory)', 35, 'PC, Projector, Whiteboard, WiFi', 'NE', 'First Floor', 'neC0.jpg', 'neC1.jpg', 'neC0.jpg', 'neC1.jpg'),
(48, 'Lab 1083 (Digital Laboratory)', 25, 'PC, Projector, Whiteboard, WiFi', 'NE', 'First Floor', 'neC0.jpg', 'neC1.jpg', 'neC0.jpg', 'neC1.jpg'),
(49, 'Room 1085', 50, 'PC, Projector, Whiteboard, WiFi', 'NE', 'First Floor', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(50, 'Room 1086', 45, 'PC, Projector, Whiteboard, WiFi', 'NE', 'First Floor', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(52, 'Lab 1087 (Microprocessor Laboratory)', 60, 'PC, Projector, Whiteboard, WiFi', 'NE', 'First Floor', 'neC0.jpg', 'neC1.jpg', 'neC0.jpg', 'neC1.jpg'),
(53, 'Lab 1089 (Computer Electronics Laboratory)', 45, 'PC, Projector, Whiteboard, WiFi', 'NE', 'First Floor', 'neC0.jpg', 'neC1.jpg', 'neC0.jpg', 'neC1.jpg'),
(54, 'Lab 2081 (network Laboratory 2)', 40, 'PC, Projector, Whiteboard, WiFi', 'NE', 'Second Floor', 'nel0.jpg', 'nel0.jpg', 'nel0.jpg', 'nel0.jpg'),
(55, 'Lab 2083 (Pc Instructional Laboratory)', 40, 'PC, Projector, Whiteboard, WiFi', 'NE', 'Second Floor', 'nel0.jpg', 'nel0.jpg', 'nel0.jpg', 'nel0.jpg'),
(56, 'Room 2084', 40, 'PC, Projector, Whiteboard, WiFi', 'NE', 'Second Floor', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(57, 'Room 2086', 40, 'PC, Projector, Whiteboard, WiFi', 'NE', 'Second Floor', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(58, 'Room 2087', 40, 'PC, Projector, Whiteboard, WiFi', 'NE', 'Second Floor', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(59, 'Room 2088', 40, 'PC, Projector, Whiteboard, WiFi', 'NE', 'Second Floor', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(60, 'Lab 2089 (IoT)', 40, 'PC, Projector, Whiteboard, WiFi', 'NE', 'Second Floor', 'iot.jpg', 'iot.jpg', 'iot.jpg', 'iot.jpg'),
(61, 'Lab 2091 (Huawei ICT Academy)', 40, 'PC, Projector, Whiteboard, WiFi', 'NE', 'Second Floor', 'Huawei.jpg', 'Huawei.jpg', 'Huawei.jpg', 'Huawei.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
