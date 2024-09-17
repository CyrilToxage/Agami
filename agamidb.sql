-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 14, 2024 at 07:29 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agamidb`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `cliID` int NOT NULL,
  `cliNom` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cliPrenom` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cliMail` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cliNumero` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cliTypID` int DEFAULT NULL,
  `cliSouID` int DEFAULT NULL,
  `cliValide` tinyint(1) NOT NULL DEFAULT '0',
  `cliDescription` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cliSexe` enum('Homme','Femme','Autres') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cliVille` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cliRue` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cliDocOC` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cliCreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cliDatesTimes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`cliID`, `cliNom`, `cliPrenom`, `cliMail`, `cliNumero`, `cliTypID`, `cliSouID`, `cliValide`, `cliDescription`, `cliSexe`, `cliVille`, `cliRue`, `cliDocOC`, `cliCreationDate`, `cliDatesTimes`) VALUES
(149, 'Ordonez', 'Maité', 'Maite@cuisine.fr', '101', 2, 3, 1, 'J\'ai besoin d\'aide pardi !', 'Femme', 'Rion Des Landes', 'rondelé 21', 'aff310a94e22e20a41cc718ea3f4e615.pdf', '2024-05-20 22:32:57', ''),
(150, 'Mignon', 'Cyril', 'mcyril2106@gmail.com', '0488263188', 1, 0, 0, '', 'Autres', 'Ottignies-Louvain-la-Neuve', 'sasa 3', NULL, '2024-06-14 20:00:49', NULL),
(151, 'Mignon', 'Cyril', 'mcyril2106@gmail.com', '0488263188', 1, 0, 0, '', 'Autres', 'Ottignies-Louvain-la-Neuve', 'sasa 3', NULL, '2024-06-14 20:04:02', NULL),
(152, 'Mignon', 'Cyril', 'mcyril2106@gmail.com', '0488263188', 1, 0, 0, '', 'Autres', 'Ottignies-Louvain-la-Neuve', 'sasa 3', NULL, '2024-06-14 20:08:19', NULL),
(153, 'Mignon', 'Cyril', 'mcyril2106@gmail.com', '0488263188', 1, 0, 0, 'e', 'Femme', 'Ottignies-Louvain-la-Neuve', 'sasa 3', NULL, '2024-06-14 20:09:50', NULL),
(154, 'Mignon', 'Cyril', 'mcyril2106@gmail.com', '0488263188', 1, 0, 0, '', 'Autres', 'Ottignies-Louvain-la-Neuve', 'sasa 3', NULL, '2024-06-14 20:11:55', '[{\"date\":\"2024-06-28T18:10:00.000Z\",\"startTime\":\"15:59\",\"endTime\":\"16:00\"}]'),
(155, 'Mignon', 'Cyril', 'mcyril2106@gmail.com', '0488263188', 1, 0, 0, '', 'Femme', 'Ottignies-Louvain-la-Neuve', 'sasa 3', NULL, '2024-06-14 20:14:19', '[{\"date\":\"2024-06-27T18:13:00.000Z\",\"startTime\":\"08:00\",\"endTime\":\"08:01\"},{\"date\":\"2024-06-25T18:13:00.000Z\",\"startTime\":\"15:59\",\"endTime\":\"16:00\"}]'),
(156, 'Mignon', 'Cyril', 'mcyril2106@gmail.com', '0488263188', 1, 0, 0, '', 'Femme', 'Ottignies-Louvain-la-Neuve', 'sasa 3', NULL, '2024-06-14 21:00:42', '[{\"date\":\"2024-06-27T18:59:00.000Z\",\"startTime\":\"08:00\",\"endTime\":\"12:34\"},{\"date\":\"2024-06-26T18:59:00.000Z\",\"startTime\":\"13:59\",\"endTime\":\"16:00\"}]'),
(157, 'QQQQQQQQQQQQ', 'Cyril', 'mcyril2106@gmail.com', '0488263188', 1, 0, 0, '', 'Autres', 'Ottignies-Louvain-la-Neuve', 'sasa 3', 'af65e06da166f203ac5069afb170c9f9.pdf', '2024-06-14 21:17:51', '[{\"date\":\"2024-06-27T19:15:00.000Z\",\"startTime\":\"08:00\",\"endTime\":\"08:34\"},{\"date\":\"2024-06-25T19:15:00.000Z\",\"startTime\":\"08:00\",\"endTime\":\"08:30\"},{\"date\":\"2024-06-28T19:15:00.000Z\",\"startTime\":\"15:16\",\"endTime\":\"15:46\"},{\"date\":\"2026-06-12T19:15:00.000Z\",\"startTime\":\"11:00\",\"endTime\":\"12:30\"},{\"date\":\"2024-06-21T19:15:00.000Z\",\"startTime\":\"08:00\",\"endTime\":\"16:00\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `photo_client`
--

CREATE TABLE `photo_client` (
  `phoID` int NOT NULL,
  `phoNom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phoCliID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `photo_client`
--

INSERT INTO `photo_client` (`phoID`, `phoNom`, `phoCliID`) VALUES
(138, '22d7f14601b408bb9fda622a37317a8c.jpg', 149),
(139, '102cbb81ce9082d1dfb1b14af02a495a.jpg', 149),
(140, '73922e6d658096c9ee01646bbe0010fa.png', 157),
(141, '5ba7d0f5ea1608e943ca3847c56bb9c0.png', 157),
(142, 'dcfd4f91907b7c0d71f9eabcccaff559.jpg', 157);

-- --------------------------------------------------------

--
-- Table structure for table `sous_type`
--

CREATE TABLE `sous_type` (
  `souID` int NOT NULL,
  `souNom` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sous_type`
--

INSERT INTO `sous_type` (`souID`, `souNom`) VALUES
(0, 'Aucun'),
(1, 'Nouvelle installation'),
(2, 'Remise en conformité'),
(3, 'Rénovation'),
(4, 'Dépannage'),
(5, 'Service de maintenance');

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `typID` int NOT NULL,
  `typNom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`typID`, `typNom`) VALUES
(1, 'Événementiel'),
(2, 'Résidentiel'),
(3, 'Tertiaire'),
(4, 'Industriel');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int NOT NULL,
  `userName` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userPassword` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `userName`, `userPassword`) VALUES
(1, 'admin', 'cacaa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`cliID`),
  ADD KEY `cliTypID` (`cliTypID`),
  ADD KEY `fk_client_sous_type` (`cliSouID`);

--
-- Indexes for table `photo_client`
--
ALTER TABLE `photo_client`
  ADD PRIMARY KEY (`phoID`),
  ADD KEY `fk_phoCliID` (`phoCliID`);

--
-- Indexes for table `sous_type`
--
ALTER TABLE `sous_type`
  ADD PRIMARY KEY (`souID`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`typID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `cliID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `photo_client`
--
ALTER TABLE `photo_client`
  MODIFY `phoID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `sous_type`
--
ALTER TABLE `sous_type`
  MODIFY `souID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `typID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `client_ibfk_1` FOREIGN KEY (`cliTypID`) REFERENCES `type` (`typID`),
  ADD CONSTRAINT `fk_client_sous_type` FOREIGN KEY (`cliSouID`) REFERENCES `sous_type` (`souID`);

--
-- Constraints for table `photo_client`
--
ALTER TABLE `photo_client`
  ADD CONSTRAINT `fk_phoCliID` FOREIGN KEY (`phoCliID`) REFERENCES `client` (`cliID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
