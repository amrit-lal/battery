-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2025 at 10:32 AM
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
-- Database: `login_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `batteries`
--

CREATE TABLE `batteries` (
  `id` int(11) NOT NULL,
  `sValue` varchar(50) DEFAULT NULL,
  `cdRatio` varchar(50) DEFAULT NULL,
  `voltage` decimal(10,2) DEFAULT NULL,
  `current` decimal(10,2) DEFAULT NULL,
  `chargeHours` decimal(10,2) DEFAULT NULL,
  `ampereHours` decimal(10,2) DEFAULT NULL,
  `wattHours` decimal(10,2) DEFAULT NULL,
  `specificGravity` decimal(10,3) DEFAULT NULL,
  `acidTemp` decimal(10,2) DEFAULT NULL,
  `realVoltage` decimal(10,2) DEFAULT NULL,
  `realCurrent` decimal(10,2) DEFAULT NULL,
  `signature` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `batteries`
--

INSERT INTO `batteries` (`id`, `sValue`, `cdRatio`, `voltage`, `current`, `chargeHours`, `ampereHours`, `wattHours`, `specificGravity`, `acidTemp`, `realVoltage`, `realCurrent`, `signature`, `remarks`, `created_at`) VALUES
(4, '20', 'C', 20.00, 202.00, 20.00, 20.00, 20.00, 18.000, 29.00, 10.00, 28.00, 'amrit', 'charged', '2025-09-28 07:57:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `full_name`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'newadmin', 'New Administrator', 'newadmin@batterypanel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-09-28 07:17:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batteries`
--
ALTER TABLE `batteries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batteries`
--
ALTER TABLE `batteries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
