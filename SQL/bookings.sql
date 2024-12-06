-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2024 at 06:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `room_name` varchar(15) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('Pending','Confirmed','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `username` varchar(50) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `room_id`, `room_name`, `student_id`, `teacher_id`, `start_time`, `end_time`, `status`, `created_at`, `updated_at`, `username`, `contact_number`) VALUES
(74, 23, 'Room 056', 3, NULL, '2024-12-01 20:00:00', '2024-12-01 21:30:00', 'Pending', '2024-12-01 16:04:54', '2024-12-06 20:03:16', 'Murtadha', '+973 33311268'),
(76, 23, 'Room 056', 2, NULL, '2024-12-01 19:00:00', '2024-12-01 20:00:00', 'Confirmed', '2024-12-01 16:09:30', '2024-12-06 20:03:16', 'Murtadha', '+973 33311268'),
(77, 23, 'Room 056', 2, NULL, '2024-12-01 22:00:00', '2024-12-01 23:00:00', 'Pending', '2024-12-01 16:11:03', '2024-12-06 20:03:16', 'Murtadha', '+973 33311268'),
(78, 25, 'Lab 058', 2, NULL, '2024-12-01 19:00:00', '2024-12-01 20:00:00', 'Cancelled', '2024-12-01 16:19:35', '2024-12-06 20:05:29', 'Murtadha', '+973 33311268'),
(79, 23, 'Room 056', 2, NULL, '2024-12-03 16:00:00', '2024-12-03 17:00:00', 'Pending', '2024-12-03 13:15:31', '2024-12-06 20:03:16', 'Murtadha', '+973 33311268'),
(80, 21, 'Room 049', 2, NULL, '2024-12-03 20:00:00', '2024-12-03 21:00:00', 'Pending', '2024-12-03 13:36:41', '2024-12-06 20:06:12', 'Murtadha', '+973 33311268'),
(81, 28, 'Room 1045', 1, NULL, '2024-12-10 18:00:00', '2024-12-10 19:30:00', 'Cancelled', '2024-12-05 19:18:49', '2024-12-08 08:34:08', 'mrzizt', '+973 33859722'),
(82, 3, 'Room 028', 1, NULL, '2024-12-18 17:00:00', '2024-12-18 18:00:00', 'Pending', '2024-12-06 05:27:34', '2024-12-06 05:27:34', 'mrzizt', '+973 33859722'),
(83, 1, 'Room 021', 1, NULL, '2024-12-08 08:00:00', '2024-12-08 09:30:00', 'Cancelled', '2024-12-08 05:28:59', '2024-12-08 08:33:57', 'mrzizt', '+973 33859722'),
(84, 4, 'Room 029', 1, NULL, '2024-12-08 09:00:00', '2024-12-08 10:30:00', 'Pending', '2024-12-08 05:32:11', '2024-12-08 05:32:11', 'mrzizt', '+973 33859722'),
(85, 1, 'Room 021', NULL, 1, '2024-12-08 10:00:00', '2024-12-08 11:00:00', 'Pending', '2024-12-08 07:35:33', '2024-12-08 07:35:33', 'tech', '+973 33859722');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
