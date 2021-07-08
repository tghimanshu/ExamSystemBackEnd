-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2021 at 01:06 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `examsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `exampaper`
--

CREATE TABLE `exampaper` (
  `ID` int(255) NOT NULL,
  `Questions` int(255) NOT NULL,
  `Class` varchar(10) NOT NULL,
  `Subject` varchar(20) NOT NULL,
  `date` datetime NOT NULL,
  `endTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `exampaper`
--

INSERT INTO `exampaper` (`ID`, `Questions`, `Class`, `Subject`, `date`, `endTime`) VALUES
(14, 60, 'BCOM', 'Finance', '2021-07-05 21:39:04', '2021-07-05 22:39:04'),
(15, 60, 'BCOM', 'Audit', '2021-07-06 00:00:00', '2021-07-06 22:28:25'),
(17, 90, 'BscIT', 'Data Structure', '2021-07-06 21:00:00', '2021-07-06 22:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `result`
--

CREATE TABLE `result` (
  `Student ID` int(255) NOT NULL,
  `Exam ID` int(255) NOT NULL,
  `Answer ID` int(255) NOT NULL,
  `Marks` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `result`
--

INSERT INTO `result` (`Student ID`, `Exam ID`, `Answer ID`, `Marks`) VALUES
(100023, 123, 12334, 122);

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
  `Email ID` varchar(50) NOT NULL,
  `CustomPassword` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`ID`, `Name`, `Rollno.`, `Class`, `Division`, `Email ID`, `CustomPassword`) VALUES
(2, 'Haaris Hussain ', 8, 'BScIT TY', 'A', 'haariswh@gmail.com', 'MokeyDLuffy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exampaper`
--
ALTER TABLE `exampaper`
  ADD PRIMARY KEY (`ID`);

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
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
