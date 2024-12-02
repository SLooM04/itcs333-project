-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2024 at 03:37 PM
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
  `available_timeslot` time DEFAULT NULL,
  `equipment` text DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `thumbnail_2` varchar(255) DEFAULT NULL,
  `thumbnail_3` varchar(255) DEFAULT NULL,
  `thumbnail_4` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `capacity`, `available_timeslot`, `equipment`, `department`, `image`, `thumbnail_2`, `thumbnail_3`, `thumbnail_4`) VALUES
(1, 'Room 049', 30, '09:00:00', 'Projector, Whiteboard', 'CS', 'n0.jpg', '10.jpg', '20.jpg', '30.jpg'),
(2, 'Room 051', 25, '01:00:00', 'Projector, Speakers', 'CS', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(3, 'Room 056', 20, '09:00:00', 'Whiteboard, Microphone', 'CS', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(4, 'Room 057', 40, '10:00:00', 'Smartboard, WiFi', 'CS', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(5, 'Lab 058', 35, '02:00:00', 'Projector, Whiteboard, Camera', 'CS', 'lab.jpg', 'lab.jpg', 'lab.jpg', 'lab.jpg'),
(6, 'Room 060', 25, '09:00:00', 'Monitor, Speakers', 'CS', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(7, 'Room 1043', 50, '11:00:00', 'Lab Computers, WiFi', 'CS', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(8, 'Room 1045', 45, '01:00:00', 'Projector, Whiteboard', 'CS', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(9, 'Room 1047', 60, '09:00:00', 'Conference Equipment', 'CS', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(10, 'Room 1048', 45, '01:00:00', 'Projector, Whiteboard', 'CS', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(11, 'Room 1050', 45, '01:00:00', 'Projector, Whiteboard', 'CS', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(13, 'Lab 2043', 45, '01:00:00', 'Projector, Whiteboard', 'CS', 'lab.jpg', 'lab.jpg', 'lab.jpg', 'lab.jpg'),
(14, 'Lab 2045', 45, '01:00:00', 'Projector, Whiteboard', 'CS', 'lab.jpg', 'lab.jpg', 'lab.jpg', 'lab.jpg'),
(15, 'Room 2046', 45, '01:00:00', 'Projector, Whiteboard', 'CS', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(16, 'Room 2048', 45, '01:00:00', 'Projector, Whiteboard', 'CS', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(17, 'Room 2049', 45, '01:00:00', 'Projector, Whiteboard', 'CS', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(18, 'Room 2050', 45, '01:00:00', 'Projector, Whiteboard', 'CS', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(19, 'Lab 2051 (Benefit Lab)', 45, '01:00:00', 'Projector, Whiteboard', 'CS', '008.jpg', '008.jpg', '008.jpg', '008.jpg'),
(20, 'Lab 2053', 45, '01:00:00', 'Projector, Whiteboard', 'CS', 'lab.jpg', 'lab.jpg', 'lab.jpg', 'lab.jpg'),
(41, 'Room 077', 30, '09:00:00', 'Projector, Whiteboard', 'NE', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(42, 'Room 079', 25, '01:00:00', 'Projector, Speakers', 'NE', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(43, 'Room 084', 20, '09:00:00', 'Whiteboard, Microphone', 'NE', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(44, 'Room 086', 40, '10:00:00', 'Smartboard, WiFi', 'NE', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(45, 'Room 088', 35, '02:00:00', 'Projector, Whiteboard, Camera', 'NE', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(46, 'Room 1081', 25, '09:00:00', 'Monitor, Speakers', 'NE', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(47, 'Room 1083', 50, '11:00:00', 'Lab Computers, WiFi', 'NE', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(48, 'Room 1085', 45, '01:00:00', 'Projector, Whiteboard', 'NE', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(49, 'Room 1086', 60, '09:00:00', 'Conference Equipment', 'NE', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(50, 'Room 1087', 45, '01:00:00', 'Projector, Whiteboard', 'NE', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(51, 'Room 1089', 45, '01:00:00', 'Projector, Whiteboard', 'NE', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(52, 'Lab 2081', 45, '01:00:00', 'Projector, Whiteboard', 'NE', 'lab.jpg', 'lab.jpg', 'lab.jpg', 'lab.jpg'),
(53, 'Lab 2083', 45, '01:00:00', 'Projector, Whiteboard', 'NE', 'lab.jpg', 'lab.jpg', 'lab.jpg', 'lab.jpg'),
(54, 'Room 2084', 45, '01:00:00', 'Projector, Whiteboard', 'NE', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(55, 'Room 2086', 45, '01:00:00', 'Projector, Whiteboard', 'NE', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(56, 'Room 2087', 45, '01:00:00', 'Projector, Whiteboard', 'NE', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(57, 'Lab 2089', 45, '01:00:00', 'Projector, Whiteboard', 'NE', 'lab.jpg', 'lab.jpg', 'lab.jpg', 'lab.jpg'),
(58, 'Lab 2091 (Huawei ICT Academy)', 45, '01:00:00', 'Projector, Whiteboard', 'NE', 'h.jpg', 'h.jpg', 'h.jpg', 'h.jpg'),
(59, 'Room 021', 30, '09:00:00', 'Projector, Whiteboard', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(60, 'Room 023', 25, '01:00:00', 'Projector, Speakers', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(61, 'Room 028', 20, '09:00:00', 'Whiteboard, Microphone', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(62, 'Room 029', 40, '10:00:00', 'Smartboard, WiFi', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(63, 'Room 030', 35, '02:00:00', 'Projector, Whiteboard, Camera', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(64, 'Room 032', 25, '09:00:00', 'Monitor, Speakers', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(65, 'Room 1006', 50, '11:00:00', 'Lab Computers, WiFi', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(66, 'Room 1008', 45, '01:00:00', 'Projector, Whiteboard', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(67, 'Room 1010', 60, '09:00:00', 'Conference Equipment', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(68, 'Room 1011', 45, '01:00:00', 'Projector, Whiteboard', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(69, 'Room 1012', 45, '01:00:00', 'Projector, Whiteboard', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(70, 'Room 1014', 45, '01:00:00', 'Projector, Whiteboard', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(71, 'Room 2005', 45, '01:00:00', 'Projector, Whiteboard', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(72, 'Lab 2007', 45, '01:00:00', 'Projector, Whiteboard', 'IS', 'lab.jpg', 'lab.jpg', 'lab.jpg', 'lab.jpg'),
(73, 'Room 2008', 45, '01:00:00', 'Projector, Whiteboard', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(74, 'Room 2010', 45, '01:00:00', 'Projector, Whiteboard', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(75, 'Room 2011', 45, '01:00:00', 'Projector, Whiteboard', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(76, 'Room 2012', 45, '01:00:00', 'Projector, Whiteboard', 'IS', 'i0.jpg', 'i1.jpg', 'n2.jpg', 'i3.jpg'),
(77, 'Lab 2013', 45, '01:00:00', 'Projector, Whiteboard', 'IS', 'lab.jpg', 'lab.jpg', 'lab.jpg', 'lab.jpg'),
(78, 'Lab 2015', 45, '01:00:00', 'Projector, Whiteboard', 'IS', 'lab.jpg', 'lab.jpg', 'lab.jpg', 'lab.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
