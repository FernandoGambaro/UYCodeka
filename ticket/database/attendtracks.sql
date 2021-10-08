-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2017 at 09:50 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attendtracks`
--
CREATE DATABASE IF NOT EXISTS `attendtracks` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `attendtracks`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_accounts`
--

DROP TABLE IF EXISTS `tbl_accounts`;
CREATE TABLE `tbl_accounts` (
  `accId` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_accounts`
--

INSERT INTO `tbl_accounts` (`accId`, `username`, `password`, `status`, `dateCreated`) VALUES
(1, 'admin', 'admin', '1', '2017-10-16 02:39:57');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE `tbl_users` (
  `userId` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `middleName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `gender` enum('Male','Female') NOT NULL DEFAULT 'Male',
  `course` varchar(50) NOT NULL,
  `year` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `status` enum('Present','Absent') NOT NULL DEFAULT 'Present',
  `dateJoined` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`userId`, `firstName`, `middleName`, `lastName`, `gender`, `course`, `year`, `address`, `phone`, `status`, `dateJoined`) VALUES
(1, 'Tracy', 'Mae', 'Serato', 'Female', 'BSIT', '1ST', 'Bankal, Lapu-Lapu City', '09237285710', 'Present', '2017-10-16 10:35:20'),
(2, 'Vincent', 'Van', 'Gough', 'Male', 'BSCS', '2ND', 'Basak, Lapu-Lapu City', '09174932708', 'Present', '2017-10-16 10:35:20'),
(3, 'Pablo', 'Ramirez', 'Picasso', 'Male', 'BSHRM', '3RD', 'Maguikay, Mandaue City', '09221634875', 'Absent', '2017-10-16 10:35:20'),
(4, 'Leonardo', 'Da', 'Vinci', 'Male', 'BSBA', '4TH', 'Alang-Alang, Mandaue City', '09368221591', 'Present', '2017-10-16 10:35:20'),
(5, 'Vincent', 'Van', 'Gough', 'Male', 'BSCS', '2ND', 'Basak, Lapu-Lapu City', '09174932708', 'Present', '2017-10-16 10:35:20'),
(6, 'Pablo', 'Ramirez', 'Picasso', 'Male', 'BSHRM', '3RD', 'Maguikay, Mandaue City', '09221634875', 'Absent', '2017-10-16 10:35:20'),
(7, 'Leonardo', 'Da', 'Vinci', 'Male', 'BSBA', '4TH', 'Alang-Alang, Mandaue City', '09368221591', 'Present', '2017-10-16 10:35:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_accounts`
--
ALTER TABLE `tbl_accounts`
  ADD PRIMARY KEY (`accId`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_accounts`
--
ALTER TABLE `tbl_accounts`
  MODIFY `accId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
