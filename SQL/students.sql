CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `major` enum('CY','CS','NE','CE','SE','IS','CC') NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `level` enum('Freshman','Sophomore','Junior','Senior','Postgraduate') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `booking_credit` int(11) NOT NULL DEFAULT 10 COMMENT 'Number of credits for booking rooms',
  PRIMARY KEY (`student_id`)
);
