-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2018 at 10:09 AM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `livechat`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `senderid` varchar(10) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `token` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `senderid`, `message`, `token`, `createdAt`) VALUES
(68, 'std1', '<b>Abdul Waris:</b> hello', 6, '2018-05-28 07:06:56'),
(69, 'std1', '<b>Abdul Waris:</b> hello', 6, '2018-05-28 07:07:40'),
(70, 'std1', '<b>Abdul Waris:</b> ???', 6, '2018-05-28 07:07:45'),
(71, 'std1', '<b>Abdul Waris:</b> ?', 6, '2018-05-28 07:09:40'),
(72, 'std1', '<b>Abdul Waris:</b> asdf', 6, '2018-05-28 07:12:10'),
(73, 'std1', '<b>Abdul Waris:</b> ???', 6, '2018-05-28 07:12:16'),
(74, 'std1', '<b>Abdul Waris:</b> hello', 6, '2018-05-28 07:12:24'),
(75, 'std1', '<b>Abdul Waris:</b> asdf', 6, '2018-05-28 07:12:49'),
(76, 'std2', '<b>Omar Farooq:</b> ???', 7, '2018-05-28 07:15:36'),
(77, 'sup3', '<b>muhammadjawad:</b> hn g bhai g', 6, '2018-05-28 07:16:43'),
(78, 'sup2', '<b>omar farooq:</b> ?', 6, '2018-05-28 07:17:52');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `ip` varchar(15) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'users'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `ip`, `status`) VALUES
(1, 'Abdul Waris', 'abdulwaris@gmail.com', '::1', 'Available'),
(2, 'Omar Farooq', 'omarfarooq@gmail.com', '::1', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE `support` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `designation` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `support`
--

INSERT INTO `support` (`id`, `name`, `email`, `password`, `designation`) VALUES
(2, 'omar farooq', 'omarfarooq@gmail.com', '123', 'support'),
(3, 'muhammadjawad', 'muhammadjawad@gmail.com', '123', 'support'),
(4, 'Admin', 'admin@portal.com', '123', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `sid` int(11) DEFAULT NULL,
  `valid` varchar(1) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `uid`, `sid`, `valid`, `token`) VALUES
(6, 1, NULL, '1', 'bf69828cb592212358f935b015ffb21e'),
(7, 2, NULL, '1', '102c31e0775047d76e1d5907d6e96171');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Foreigh_Key_Token` (`token`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `support`
--
ALTER TABLE `support`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `Foreign_Key_UserID` (`uid`),
  ADD KEY `Foreign_Key_AdminID` (`sid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `support`
--
ALTER TABLE `support`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `Foreigh_Key_Token` FOREIGN KEY (`token`) REFERENCES `tokens` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `Foreign_Key_AdminID` FOREIGN KEY (`sid`) REFERENCES `support` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Foreign_Key_UserID` FOREIGN KEY (`uid`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
