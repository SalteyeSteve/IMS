-- phpMyAdmin SQL Dump
-- version 4.7.8
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 28, 2018 at 01:30 PM
-- Created by: Jetske de Boer
-- Server version: 5.5.59-MariaDB
-- PHP Version: 5.6.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbIMS`
--
CREATE DATABASE IF NOT EXISTS `dbIMS` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `dbIMS`;

-- --------------------------------------------------------

--
-- Table structure for table `Incident`
--
-- Creation: Jun 26, 2018 at 03:32 PM
--

DROP TABLE IF EXISTS `Incident`;
CREATE TABLE `Incident` (
  `Incident_ID` int(11) NOT NULL,
  `Datum` datetime DEFAULT NULL,
  `Baliemedewerker` varchar(255) DEFAULT NULL,
  `Behandelaar` varchar(255) DEFAULT NULL,
  `Omschrijving` text,
  `Actie` text,
  `VervolgActie` text,
  `UitgevoerdeWerkzaamheden` text,
  `Afspraken` text,
  `GereedVoorSluiten` bit(1) DEFAULT NULL,
  `IncidentGesloten` bit(1) DEFAULT NULL,
  `SluitDatum` datetime DEFAULT NULL,
  `Klant_ID` int(11) NOT NULL,
  `SoortIncident_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Klant`
--
-- Creation: Jun 26, 2018 at 03:32 PM
--

DROP TABLE IF EXISTS `Klant`;
CREATE TABLE `Klant` (
  `Klant_ID` int(11) NOT NULL,
  `Naam` varchar(255) DEFAULT NULL,
  `Telefoon` varchar(13) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Type_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SoortIncident`
--
-- Creation: Jun 26, 2018 at 03:32 PM
--

DROP TABLE IF EXISTS `SoortIncident`;
CREATE TABLE `SoortIncident` (
  `SoortIncident_ID` int(11) NOT NULL,
  `SoortIncident` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `SoortIncident`
--

INSERT INTO `SoortIncident` (`SoortIncident_ID`, `SoortIncident`) VALUES
(1, 'Software incident'),
(2, 'Hardware incident'),
(3, 'Advies'),
(4, 'Verzoek');

-- --------------------------------------------------------

--
-- Table structure for table `StudentDocentNummer`
--
-- Creation: Jun 26, 2018 at 03:32 PM
--

DROP TABLE IF EXISTS `StudentDocentNummer`;
CREATE TABLE `StudentDocentNummer` (
  `ID_nummer` varchar(255) NOT NULL DEFAULT '',
  `Klant_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `TypeKlant`
--
-- Creation: Jun 26, 2018 at 03:32 PM
--

DROP TABLE IF EXISTS `TypeKlant`;
CREATE TABLE `TypeKlant` (
  `Type_ID` int(11) NOT NULL,
  `TypeKlant` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `TypeKlant`
--

INSERT INTO `TypeKlant` (`Type_ID`, `TypeKlant`) VALUES
(1, 'Student'),
(2, 'Docent'),
(3, 'Extern');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--
-- Creation: Jun 26, 2018 at 03:32 PM
--

DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `User_ID` int(11) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `IsAdmin` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`User_ID`, `UserName`, `PasswordHash`, `IsAdmin`) VALUES
(1, 'Jetske', '$2y$10$ssB8H1FJjn3x/IXULMozBuGa.zWBpYusFL3EDuaWV5RuQB1fAlQ6m', b'1'),
(5, 'Paul', '$2y$10$fGtJG2rw01k/JnnKPlsxm.XvCywPWfbsoCYJcD3jOzAyqlAJ8EFOC', b'1'),
(6, 'Andy', '$2y$10$TLKpWBZwmEvnqNMCahLjEukVuA4z0VIGf3hm9zXB7cvHjMgontBDy', b'1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Incident`
--
ALTER TABLE `Incident`
  ADD PRIMARY KEY (`Incident_ID`),
  ADD KEY `Incident_Klant` (`Klant_ID`),
  ADD KEY `Incident_SoortIncident` (`SoortIncident_ID`);

--
-- Indexes for table `Klant`
--
ALTER TABLE `Klant`
  ADD PRIMARY KEY (`Klant_ID`),
  ADD KEY `Klant_TypeKlant` (`Type_ID`);

--
-- Indexes for table `SoortIncident`
--
ALTER TABLE `SoortIncident`
  ADD PRIMARY KEY (`SoortIncident_ID`);

--
-- Indexes for table `StudentDocentNummer`
--
ALTER TABLE `StudentDocentNummer`
  ADD PRIMARY KEY (`ID_nummer`),
  ADD UNIQUE KEY `ID_nummer` (`ID_nummer`),
  ADD KEY `Klant_StudentDocentNummer` (`Klant_ID`);

--
-- Indexes for table `TypeKlant`
--
ALTER TABLE `TypeKlant`
  ADD PRIMARY KEY (`Type_ID`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `UserName` (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Incident`
--
ALTER TABLE `Incident`
  MODIFY `Incident_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Klant`
--
ALTER TABLE `Klant`
  MODIFY `Klant_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `SoortIncident`
--
ALTER TABLE `SoortIncident`
  MODIFY `SoortIncident_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `TypeKlant`
--
ALTER TABLE `TypeKlant`
  MODIFY `Type_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Incident`
--
ALTER TABLE `Incident`
  ADD CONSTRAINT `Incident_Klant` FOREIGN KEY (`Klant_ID`) REFERENCES `Klant` (`Klant_ID`),
  ADD CONSTRAINT `Incident_SoortIncident` FOREIGN KEY (`SoortIncident_ID`) REFERENCES `SoortIncident` (`SoortIncident_ID`);

--
-- Constraints for table `Klant`
--
ALTER TABLE `Klant`
  ADD CONSTRAINT `Klant_TypeKlant` FOREIGN KEY (`Type_ID`) REFERENCES `TypeKlant` (`Type_ID`);

--
-- Constraints for table `StudentDocentNummer`
--
ALTER TABLE `StudentDocentNummer`
  ADD CONSTRAINT `Klant_StudentDocentNummer` FOREIGN KEY (`Klant_ID`) REFERENCES `Klant` (`Klant_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
