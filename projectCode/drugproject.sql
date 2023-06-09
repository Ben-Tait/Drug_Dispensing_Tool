-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2023 at 12:40 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `drugproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `doctorId` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `doctorSSN` varchar(20) NOT NULL,
  `speciality` varchar(100) NOT NULL,
  `startYear` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drug`
--

CREATE TABLE `drug` (
  `drug_ID` int(11) NOT NULL,
  `drugName` varchar(50) NOT NULL,
  `tradeName` varchar(50) NOT NULL,
  `drugFormula` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `dateOfman` date NOT NULL,
  `expiryDate` date NOT NULL,
  `pharmCoId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drugprescription`
--

CREATE TABLE `drugprescription` (
  `prescriptionId` int(11) NOT NULL,
  `drugId` int(11) NOT NULL,
  `patientId` int(11) NOT NULL,
  `doctorId` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `patientId` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `patientSSN` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `DateOfBirth` date NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`patientId`, `firstName`, `lastName`, `patientSSN`, `address`, `DateOfBirth`, `username`, `password`, `gender`) VALUES
(8, 'Peter', 'Gathoga', '1234567890', 'Pipeline', '2001-01-01', 'pindano', '$2y$10$j3Cz/98YjLxOrizjrq.RqePGzq7Clz4FPkFe6qsoOesO7N7Jlncym', 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `patientd`
--

CREATE TABLE `patientd` (
  `patientDoctorId` int(11) NOT NULL,
  `patientId` int(11) NOT NULL,
  `doctorId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy`
--

CREATE TABLE `pharmacy` (
  `pharmID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `phoneNumber` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pharmco`
--

CREATE TABLE `pharmco` (
  `pharmCoId` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phoneNumber` varchar(15) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmco`
--

INSERT INTO `pharmco` (`pharmCoId`, `name`, `phoneNumber`, `username`, `password`) VALUES
(1, 'Dawa', '78934562', 'Dawa Co', '$2y$10$f0N8ka5nlNpHb2eOEUUcgetvXEsxdjdoqLUl.xBm6FpNAAaZa7lwi');

-- --------------------------------------------------------

--
-- Table structure for table `pharmdrug`
--

CREATE TABLE `pharmdrug` (
  `pharmDrugId` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `drugId` int(11) NOT NULL,
  `pharmId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pharmpharmco`
--

CREATE TABLE `pharmpharmco` (
  `contractID` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `contractDescription` varchar(500) NOT NULL,
  `pharmCoId` int(11) NOT NULL,
  `pharmId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`doctorId`);

--
-- Indexes for table `drug`
--
ALTER TABLE `drug`
  ADD PRIMARY KEY (`drug_ID`),
  ADD UNIQUE KEY `pharmCoId` (`pharmCoId`);

--
-- Indexes for table `drugprescription`
--
ALTER TABLE `drugprescription`
  ADD PRIMARY KEY (`prescriptionId`),
  ADD KEY `drugId` (`drugId`,`patientId`,`doctorId`),
  ADD KEY `patientId` (`patientId`),
  ADD KEY `doctorId` (`doctorId`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`patientId`);

--
-- Indexes for table `patientd`
--
ALTER TABLE `patientd`
  ADD PRIMARY KEY (`patientDoctorId`),
  ADD KEY `patientId` (`patientId`,`doctorId`),
  ADD KEY `doctorId` (`doctorId`);

--
-- Indexes for table `pharmacy`
--
ALTER TABLE `pharmacy`
  ADD PRIMARY KEY (`pharmID`);

--
-- Indexes for table `pharmco`
--
ALTER TABLE `pharmco`
  ADD PRIMARY KEY (`pharmCoId`);

--
-- Indexes for table `pharmdrug`
--
ALTER TABLE `pharmdrug`
  ADD PRIMARY KEY (`pharmDrugId`),
  ADD KEY `drugId` (`drugId`,`pharmId`),
  ADD KEY `pharmId` (`pharmId`);

--
-- Indexes for table `pharmpharmco`
--
ALTER TABLE `pharmpharmco`
  ADD PRIMARY KEY (`contractID`),
  ADD KEY `pharmCoId` (`pharmCoId`,`pharmId`),
  ADD KEY `pharmId` (`pharmId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `doctorId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drug`
--
ALTER TABLE `drug`
  MODIFY `drug_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drugprescription`
--
ALTER TABLE `drugprescription`
  MODIFY `prescriptionId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `patientId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `patientd`
--
ALTER TABLE `patientd`
  MODIFY `patientDoctorId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pharmacy`
--
ALTER TABLE `pharmacy`
  MODIFY `pharmID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pharmco`
--
ALTER TABLE `pharmco`
  MODIFY `pharmCoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pharmdrug`
--
ALTER TABLE `pharmdrug`
  MODIFY `pharmDrugId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pharmpharmco`
--
ALTER TABLE `pharmpharmco`
  MODIFY `contractID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `drug`
--
ALTER TABLE `drug`
  ADD CONSTRAINT `drug_ibfk_1` FOREIGN KEY (`pharmCoId`) REFERENCES `pharmco` (`pharmCoId`);

--
-- Constraints for table `drugprescription`
--
ALTER TABLE `drugprescription`
  ADD CONSTRAINT `drugprescription_ibfk_1` FOREIGN KEY (`patientId`) REFERENCES `patient` (`patientId`),
  ADD CONSTRAINT `drugprescription_ibfk_2` FOREIGN KEY (`doctorId`) REFERENCES `doctor` (`doctorId`),
  ADD CONSTRAINT `drugprescription_ibfk_3` FOREIGN KEY (`drugId`) REFERENCES `drug` (`drug_ID`);

--
-- Constraints for table `patientd`
--
ALTER TABLE `patientd`
  ADD CONSTRAINT `patientd_ibfk_1` FOREIGN KEY (`doctorId`) REFERENCES `doctor` (`doctorId`),
  ADD CONSTRAINT `patientd_ibfk_2` FOREIGN KEY (`patientId`) REFERENCES `patient` (`patientId`);

--
-- Constraints for table `pharmdrug`
--
ALTER TABLE `pharmdrug`
  ADD CONSTRAINT `pharmdrug_ibfk_1` FOREIGN KEY (`pharmId`) REFERENCES `pharmacy` (`pharmID`),
  ADD CONSTRAINT `pharmdrug_ibfk_2` FOREIGN KEY (`drugId`) REFERENCES `drug` (`drug_ID`);

--
-- Constraints for table `pharmpharmco`
--
ALTER TABLE `pharmpharmco`
  ADD CONSTRAINT `pharmpharmco_ibfk_1` FOREIGN KEY (`pharmCoId`) REFERENCES `pharmco` (`pharmCoId`),
  ADD CONSTRAINT `pharmpharmco_ibfk_2` FOREIGN KEY (`pharmId`) REFERENCES `pharmacy` (`pharmID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
