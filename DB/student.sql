-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2021 at 02:16 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testing`
--

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `ID` int(255) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Rollno.` int(4) NOT NULL,
  `Class` varchar(10) NOT NULL,
  `Division` char(1) NOT NULL,
  `Address` text NOT NULL,
  `MotherName` varchar(30) NOT NULL,
  `FatherName` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`ID`, `Name`, `Rollno.`, `Class`, `Division`, `Address`, `MotherName`, `FatherName`) VALUES
(1, 'Haaris Hussain ', 8, 'BScIT TY', 'A', 'lkshfoshfahvosihoishidvhosihsaoidhvoivhoisdhvoisdhvoiahaoivhsaoivhsoivsodivhaovhoavhsovhsoysoihsoidveaooivhoihsovhoidvhaohvoisvhoisvhosvhosidvhosivhyosayvhovhsljkdvasvolvjbvkvohfilshvosFLDSJHVOSD', 'Fatima Hussain', 'Wahid Hussain');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
