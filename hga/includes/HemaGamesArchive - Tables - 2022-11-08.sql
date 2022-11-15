-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2022 at 02:39 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `HemaGamesArchive`
--

-- --------------------------------------------------------

--
-- Table structure for table `gameList`
--

CREATE TABLE `gameList` (
  `gameID` int(10) UNSIGNED NOT NULL,
  `gameName` varchar(255) NOT NULL,
  `gameRules` text NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `gameDatestamp` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `gameTag`
--

CREATE TABLE `gameTag` (
  `gameTagID` int(10) UNSIGNED NOT NULL,
  `gameID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `infoList`
--

CREATE TABLE `infoList` (
  `infoID` int(10) UNSIGNED NOT NULL,
  `gameID` int(10) UNSIGNED NOT NULL,
  `infoMetaID` int(10) UNSIGNED NOT NULL,
  `infoText` text NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `infoDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `infoMeta`
--

CREATE TABLE `infoMeta` (
  `infoMetaID` int(10) UNSIGNED NOT NULL,
  `infoMetaName` varchar(255) NOT NULL,
  `infoMetaDescription` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tagList`
--

CREATE TABLE `tagList` (
  `tagID` int(10) UNSIGNED NOT NULL,
  `tagMetaID` int(10) UNSIGNED NOT NULL,
  `tagName` varchar(255) NOT NULL,
  `tagDescription` text DEFAULT NULL,
  `userID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tagMeta`
--

CREATE TABLE `tagMeta` (
  `tagMetaID` int(10) UNSIGNED NOT NULL,
  `tagMetaName` varchar(255) NOT NULL,
  `tagMetaDescription` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `userList`
--

CREATE TABLE `userList` (
  `userID` int(10) UNSIGNED NOT NULL,
  `userAccount` varchar(255) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `userBio` text DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `CAN_ADD` tinyint(1) NOT NULL DEFAULT 0,
  `CAN_EDIT` tinyint(1) NOT NULL DEFAULT 0,
  `CAN_ADMIN` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gameList`
--
ALTER TABLE `gameList`
  ADD PRIMARY KEY (`gameID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `gameTag`
--
ALTER TABLE `gameTag`
  ADD PRIMARY KEY (`gameTagID`),
  ADD KEY `gameID` (`gameID`),
  ADD KEY `tagID` (`tagID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `infoList`
--
ALTER TABLE `infoList`
  ADD PRIMARY KEY (`infoID`),
  ADD KEY `infoTypeID` (`infoMetaID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `gameID` (`gameID`);

--
-- Indexes for table `infoMeta`
--
ALTER TABLE `infoMeta`
  ADD PRIMARY KEY (`infoMetaID`);

--
-- Indexes for table `tagList`
--
ALTER TABLE `tagList`
  ADD PRIMARY KEY (`tagID`),
  ADD KEY `tagType` (`tagMetaID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `tagMeta`
--
ALTER TABLE `tagMeta`
  ADD PRIMARY KEY (`tagMetaID`);

--
-- Indexes for table `userList`
--
ALTER TABLE `userList`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gameList`
--
ALTER TABLE `gameList`
  MODIFY `gameID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gameTag`
--
ALTER TABLE `gameTag`
  MODIFY `gameTagID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `infoList`
--
ALTER TABLE `infoList`
  MODIFY `infoID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `infoMeta`
--
ALTER TABLE `infoMeta`
  MODIFY `infoMetaID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tagList`
--
ALTER TABLE `tagList`
  MODIFY `tagID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tagMeta`
--
ALTER TABLE `tagMeta`
  MODIFY `tagMetaID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userList`
--
ALTER TABLE `userList`
  MODIFY `userID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gameList`
--
ALTER TABLE `gameList`
  ADD CONSTRAINT `gamelist_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userList` (`userID`);

--
-- Constraints for table `gameTag`
--
ALTER TABLE `gameTag`
  ADD CONSTRAINT `gametag_ibfk_1` FOREIGN KEY (`gameID`) REFERENCES `gameList` (`gameID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gametag_ibfk_2` FOREIGN KEY (`tagID`) REFERENCES `tagList` (`tagID`),
  ADD CONSTRAINT `gametag_ibfk_3` FOREIGN KEY (`userID`) REFERENCES `userList` (`userID`);

--
-- Constraints for table `infoList`
--
ALTER TABLE `infoList`
  ADD CONSTRAINT `infolist_ibfk_1` FOREIGN KEY (`gameID`) REFERENCES `gameList` (`gameID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `infolist_ibfk_3` FOREIGN KEY (`userID`) REFERENCES `userList` (`userID`),
  ADD CONSTRAINT `infolist_ibfk_4` FOREIGN KEY (`infoMetaID`) REFERENCES `infoMeta` (`infoMetaID`);

--
-- Constraints for table `tagList`
--
ALTER TABLE `tagList`
  ADD CONSTRAINT `taglist_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `userList` (`userID`),
  ADD CONSTRAINT `taglist_ibfk_3` FOREIGN KEY (`tagMetaID`) REFERENCES `tagMeta` (`tagMetaID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
