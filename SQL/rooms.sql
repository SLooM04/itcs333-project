-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2024 at 05:55 PM
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
  `room_name` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `available_timeslot` varchar(255) NOT NULL,
  `equipment` text DEFAULT NULL,
  `department` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `thumbnail_2` varchar(255) DEFAULT NULL,
  `thumbnail_3` varchar(255) DEFAULT NULL,
  `thumbnail_4` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `capacity`, `available_timeslot`, `equipment`, `department`, `image`, `thumbnail_2`, `thumbnail_3`, `thumbnail_4`) VALUES
(1, 'Room 049', 30, '9:00 AM - 12:00 PM', 'Projector, Whiteboard', 'Computer Science', '4.jpg', NULL, NULL, NULL),
(2, 'Room 051', 25, '1:00 PM - 4:00 PM', 'Projector, Speakers', 'Computer Science', '5.jpg', NULL, NULL, NULL),
(3, 'Room 056', 20, '9:00 AM - 12:00 PM', 'Whiteboard, Microphone', 'Computer Science', '6.jpg', NULL, NULL, NULL),
(4, 'Room 057', 40, '10:00 AM - 1:00 PM', 'Smartboard, WiFi', 'Computer Science', '7.jpg', NULL, NULL, NULL),
(5, 'Room 058', 35, '2:00 PM - 5:00 PM', 'Projector, Whiteboard, Camera', 'Computer Science', '8.jpg', NULL, NULL, NULL),
(6, 'Room 060', 25, '9:00 AM - 11:00 AM', 'Monitor, Speakers', 'Computer Science', '4.jpg', NULL, NULL, NULL),
(7, 'Room 1043', 50, '11:00 AM - 2:00 PM', 'Lab Computers, WiFi', 'Computer Science', '5.jpg', NULL, NULL, NULL),
(8, 'Room 1045', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '6.jpg', NULL, NULL, NULL),
(9, 'Room 1047', 60, '9:00 AM - 12:00 PM', 'Conference Equipment', 'Computer Science', '7.jpg', NULL, NULL, NULL),
(10, 'Room 1048', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '8.jpg', NULL, NULL, NULL),
(11, 'Room 1050', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '4.jpg', NULL, NULL, NULL),
(13, 'Room 2043', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '6.jpg', NULL, NULL, NULL),
(14, 'Room 2045', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '7.jpg', NULL, NULL, NULL),
(15, 'Room 2046', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '8.jpg', NULL, NULL, NULL),
(16, 'Room 2048', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '4.jpg', NULL, NULL, NULL),
(17, 'Room 2049', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '5.jpg', NULL, NULL, NULL),
(18, 'Room 2050', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '6.jpg', NULL, NULL, NULL),
(19, 'Room 2051', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '7.jpg', NULL, NULL, NULL),
(20, 'Room 2053', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '8.jpg', NULL, NULL, NULL),
(21, 'Room 049', 30, '9:00 AM - 12:00 PM', 'Projector, Whiteboard', 'Computer Science', '4.jpg', NULL, NULL, NULL),
(22, 'Room 051', 25, '1:00 PM - 4:00 PM', 'Projector, Speakers', 'Computer Science', '5.jpg', NULL, NULL, NULL),
(23, 'Room 056', 20, '9:00 AM - 12:00 PM', 'Whiteboard, Microphone', 'Computer Science', '6.jpg', NULL, NULL, NULL),
(24, 'Room 057', 40, '10:00 AM - 1:00 PM', 'Smartboard, WiFi', 'Computer Science', '7.jpg', NULL, NULL, NULL),
(25, 'Room 058', 35, '2:00 PM - 5:00 PM', 'Projector, Whiteboard, Camera', 'Computer Science', '8.jpg', NULL, NULL, NULL),
(26, 'Room 060', 25, '9:00 AM - 11:00 AM', 'Monitor, Speakers', 'Computer Science', '4.jpg', NULL, NULL, NULL),
(27, 'Room 1043', 50, '11:00 AM - 2:00 PM', 'Lab Computers, WiFi', 'Computer Science', '5.jpg', NULL, NULL, NULL),
(28, 'Room 1045', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '6.jpg', NULL, NULL, NULL),
(29, 'Room 1047', 60, '9:00 AM - 12:00 PM', 'Conference Equipment', 'Computer Science', '7.jpg', NULL, NULL, NULL),
(30, 'Room 1048', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '8.jpg', NULL, NULL, NULL),
(31, 'Room 1050', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '4.jpg', NULL, NULL, NULL),
(32, 'Room 1052', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '5.jpg', NULL, NULL, NULL),
(33, 'Room 2043', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '6.jpg', NULL, NULL, NULL),
(34, 'Room 2045', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '7.jpg', NULL, NULL, NULL),
(35, 'Room 2046', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '8.jpg', NULL, NULL, NULL),
(36, 'Room 2048', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '4.jpg', NULL, NULL, NULL),
(37, 'Room 2049', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '5.jpg', NULL, NULL, NULL),
(38, 'Room 2050', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '6.jpg', NULL, NULL, NULL),
(39, 'Room 2051', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '7.jpg', NULL, NULL, NULL),
(40, 'Room 2053', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Computer Science', '8.jpg', NULL, NULL, NULL),
(41, 'Room 049', 30, '9:00 AM - 12:00 PM', 'Projector, Whiteboard', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(42, 'Room 051', 25, '1:00 PM - 4:00 PM', 'Projector, Speakers', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(43, 'Room 056', 20, '9:00 AM - 12:00 PM', 'Whiteboard, Microphone', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(44, 'Room 057', 40, '10:00 AM - 1:00 PM', 'Smartboard, WiFi', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(45, 'Room 058', 35, '2:00 PM - 5:00 PM', 'Projector, Whiteboard, Camera', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(46, 'Room 060', 25, '9:00 AM - 11:00 AM', 'Monitor, Speakers', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(47, 'Room 1043', 50, '11:00 AM - 2:00 PM', 'Lab Computers, WiFi', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(48, 'Room 1045', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(49, 'Room 1047', 60, '9:00 AM - 12:00 PM', 'Conference Equipment', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(50, 'Room 1048', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(51, 'Room 1050', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(52, 'Room 1052', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(53, 'Room 2043', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(54, 'Room 2045', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(55, 'Room 2046', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(56, 'Room 2048', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(57, 'Room 2049', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(58, 'Room 2050', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(59, 'Room 2051', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg'),
(60, 'Room 2053', 45, '1:00 PM - 3:00 PM', 'Projector, Whiteboard', 'Network Engineering', 'n0.jpg', 'n1.jpg', 'n2.jpg', 'n3.jpg');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
