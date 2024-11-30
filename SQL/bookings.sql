-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 12:50 AM
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
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
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

INSERT INTO `bookings` (`booking_id`, `room_id`, `student_id`, `teacher_id`, `start_time`, `end_time`, `status`, `created_at`, `updated_at`, `username`, `contact_number`) VALUES
(1, 4, 1, NULL, '2024-11-15 11:00:00', '1970-01-01 01:00:00', 'Pending', '2024-11-28 09:49:34', '2024-11-28 09:49:34', NULL, '+97333311222'),
(2, 43, 1, NULL, '2024-11-14 09:00:00', '1970-01-01 01:00:00', 'Pending', '2024-11-28 09:50:55', '2024-11-28 09:50:55', NULL, '+97333311222'),
(3, 5, NULL, 1, '2024-11-29 11:00:00', '1970-01-01 01:00:00', 'Pending', '2024-11-28 09:52:44', '2024-11-28 09:52:44', NULL, '+97333311222'),
(4, 7, NULL, 1, '2024-11-30 09:00:00', '1970-01-01 01:00:00', 'Pending', '2024-11-29 10:44:54', '2024-11-29 10:44:54', NULL, '+97333311222'),
(5, 6, NULL, 1, '2024-11-01 09:00:00', '1970-01-01 01:00:00', 'Pending', '2024-11-29 10:45:20', '2024-11-29 10:45:20', NULL, '+97333311222'),
(6, 6, NULL, 1, '2024-11-30 09:00:00', '1970-01-01 01:00:00', 'Pending', '2024-11-29 10:50:47', '2024-11-29 10:50:47', NULL, '+97333311222'),
(7, 47, NULL, 1, '2024-11-08 10:00:00', '1970-01-01 01:00:00', 'Pending', '2024-11-29 20:12:51', '2024-11-29 20:12:51', NULL, '+97333311222'),
(8, 47, NULL, 1, '2024-11-30 09:00:00', '1970-01-01 01:00:00', 'Pending', '2024-11-29 20:13:12', '2024-11-29 20:13:12', NULL, '+97333311222'),
(9, 5, NULL, 1, '2024-11-30 10:00:00', '2024-11-30 11:00:00', 'Pending', '2024-11-29 23:34:50', '2024-11-29 23:34:50', NULL, '+97333311222');

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
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
