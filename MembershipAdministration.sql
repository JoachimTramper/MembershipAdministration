-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 10, 2024 at 10:22 AM
-- Server version: 8.0.36
-- PHP Version: 8.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ledenadministratie`
--

-- --------------------------------------------------------

--
-- Table structure for table `boekjaren`
--

CREATE TABLE `boekjaren` (
  `id` int NOT NULL,
  `jaar` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `boekjaren`
--

INSERT INTO `boekjaren` (`id`, `jaar`) VALUES
(1, 2020),
(2, 2021),
(3, 2022),
(4, 2023);

-- --------------------------------------------------------

--
-- Table structure for table `contributies`
--

CREATE TABLE `contributies` (
  `id` int NOT NULL,
  `familielid_id` int DEFAULT NULL,
  `bedrag` decimal(10,2) NOT NULL,
  `type` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `betaaldatum` date DEFAULT NULL,
  `boekjaar_id` int DEFAULT NULL,
  `aantekening` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contributies`
--

INSERT INTO `contributies` (`id`, `familielid_id`, `bedrag`, `type`, `betaaldatum`, `boekjaar_id`, `aantekening`) VALUES
(1, 8, 40.00, 'inkomsten', NULL, 3, ''),
(2, 7, 50.00, 'inkomsten', NULL, 3, ''),
(3, NULL, -100.00, 'uitgaven', '2022-12-06', 3, 'Dak reparatie. '),
(4, NULL, -200.00, 'belastingen', '2022-11-14', 3, 'Rioolrecht.'),
(5, 1, 100.00, 'inkomsten', '2022-11-28', 3, ''),
(6, 1, 200.00, 'inkomsten', '2022-12-12', 3, 'Donatie. '),
(7, 10, 55.00, 'inkomsten', '2022-12-05', 3, ''),
(8, 5, 100.00, 'inkomsten', NULL, 3, ''),
(9, 2, 100.00, 'inkomsten', NULL, 4, ''),
(10, 3, 100.00, 'inkomsten', '2023-12-05', 4, ''),
(11, NULL, -75.00, 'inkomsten', '2023-12-09', 4, 'Ongediertebestrijding.'),
(12, NULL, -200.00, 'uitgaven', '2023-12-04', 4, 'Gas, maand augustus.'),
(13, 4, 75.00, 'inkomsten', NULL, 4, ''),
(14, 6, 100.00, 'inkomsten', NULL, 4, ''),
(15, 9, 55.00, 'inkomsten', NULL, 4, ''),
(16, 10, 300.00, 'inkomsten', '2023-12-04', 4, 'Donatie. ');

-- --------------------------------------------------------

--
-- Table structure for table `familieleden`
--

CREATE TABLE `familieleden` (
  `id` int NOT NULL,
  `familie_id` int DEFAULT NULL,
  `naam` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `geboortedatum` date NOT NULL,
  `soort_lid_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `familieleden`
--

INSERT INTO `familieleden` (`id`, `familie_id`, `naam`, `geboortedatum`, `soort_lid_id`) VALUES
(1, 1, 'Jan Jansen', '1975-11-11', 4),
(2, 1, 'Maria Jansen', '1978-11-18', 4),
(3, 1, 'Tim Jansen', '2005-11-22', 4),
(4, 1, 'Lisa Jansen', '2007-11-08', 3),
(5, 2, 'Piet Pietersen', '1980-11-10', 4),
(6, 2, 'Anne Pietersen', '1982-11-25', 4),
(7, 2, 'Knabbel Pietersen', '2014-11-25', 2),
(8, 2, 'Babbel Pietersen', '2018-11-12', 1),
(9, 3, 'Gerda De Vries', '1950-11-24', 5),
(10, 3, 'Jo De Vries', '1949-11-01', 5),
(11, 4, 'Chef Kok', '1980-12-02', 4);

-- --------------------------------------------------------

--
-- Table structure for table `families`
--

CREATE TABLE `families` (
  `id` int NOT NULL,
  `naam` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `adres` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `families`
--

INSERT INTO `families` (`id`, `naam`, `adres`) VALUES
(1, 'Jansen', 'Kerkstraat 1, 1111 AB, Goes'),
(2, 'Pietersen', 'Hoofdstraat 2, 2222 BC, Vlissingen'),
(3, 'De Vries', 'Langstraat 3, 3333 CD, Middelburg'),
(4, 'Kok', 'Middenweg 4, 4444 DE, Terneuzen');

-- --------------------------------------------------------

--
-- Table structure for table `gebruikers`
--

CREATE TABLE `gebruikers` (
  `id` int NOT NULL,
  `familieleden_id` int NOT NULL,
  `gebruikersnaam` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `wachtwoord` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rol` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gebruikers`
--

INSERT INTO `gebruikers` (`id`, `familieleden_id`, `gebruikersnaam`, `wachtwoord`, `rol`) VALUES
(1, 1, 'j.jansen', '$2y$10$2eriikAgrPZR1eFpEXe82OpOHrh7UinN8VAScIJHq07mS2.IY/iY2', 1),
(2, 2, 'mariajansen', '$2y$10$kLgxWBWuGzSxOu6xZqZ5WOE7OOsYI9UuPToOf93qFTUaIg.1S7g0S', 4),
(3, 3, 'tjansen', '$2y$10$rSje0vC1WFifktq72gOIW.vQ/Bz9hmWILfdBtHB9LUYKECBgNajae', 4),
(4, 4, 'LJansen', '$2y$10$7SosxyL5bVcRXAiGGy5vT.JMG0DMZ.zzsnjYrZtGBuKbbrNCL0C6W', 4),
(5, 5, 'PP', '$2y$10$M8DF0OcQvICV0B4sXtuzIeoN4klRJwDy7YsPbeSl5S.l/dI1fKBse', 2),
(6, 6, 'AnnePietersen', '$2y$10$rIk8Undv89uZ8DH.3GcOveLPkzYlZz3s5snsmHTVk272Zo5iyzR1G', 4),
(7, 7, 'KPietersen', '$2y$10$dYkJVCL2He3WQD6bkl6Ez.shyJi9F8yD4YSUw8QqjVUIhgf7C1bY2', 4),
(8, 8, 'BP', '$2y$10$F6S2Mlf5AsckGx84MLYJ6uZc1lXahH.6CbdCrw6zAq5m.hpsSrhkC', 4),
(9, 9, 'GDV', '$2y$10$oJ1fcl7R.UrINB8CHC/eleO//zuH9D7V5ZYI./.g5VSlnKWdYAp4u', 4),
(10, 10, 'JDV', '$2y$10$D40TbZoPvpaMdJMrzdwrLeXilWMkkOsOLZMeRBVJy24STUYfsZZEe', 4),
(11, 11, 'CK', '$2y$10$J5j8qzhXobhRWTiRQhX6XeGohHgQnjw6rv9/P7bE/5Y2/SKOv.3la', 3);

-- --------------------------------------------------------

--
-- Table structure for table `rol`
--

CREATE TABLE `rol` (
  `id` int NOT NULL,
  `rol_soort` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rol`
--

INSERT INTO `rol` (`id`, `rol_soort`) VALUES
(1, 'admin'),
(2, 'secretaris'),
(3, 'penningmeester'),
(4, 'lid');

-- --------------------------------------------------------

--
-- Table structure for table `soorten_lid`
--

CREATE TABLE `soorten_lid` (
  `id` int NOT NULL,
  `soort_lid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `leeftijd_vanaf` int NOT NULL,
  `leeftijd_tot` int NOT NULL,
  `korting` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `soorten_lid`
--

INSERT INTO `soorten_lid` (`id`, `soort_lid`, `leeftijd_vanaf`, `leeftijd_tot`, `korting`) VALUES
(1, 'Jeugd ', 0, 7, 50),
(2, 'Aspirant', 8, 12, 40),
(3, 'Junior', 12, 17, 25),
(4, 'Senior', 18, 50, 0),
(5, 'Oudere', 51, 120, 45);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boekjaren`
--
ALTER TABLE `boekjaren`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contributies`
--
ALTER TABLE `contributies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contributies_ibfk_12` (`boekjaar_id`),
  ADD KEY `contributies_ibfk_13` (`familielid_id`);

--
-- Indexes for table `familieleden`
--
ALTER TABLE `familieleden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `familie_id` (`familie_id`),
  ADD KEY `soort_lid_id` (`soort_lid_id`);

--
-- Indexes for table `families`
--
ALTER TABLE `families`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gebruikers`
--
ALTER TABLE `gebruikers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `connectie_rol` (`rol`),
  ADD KEY `connectie_familieleden` (`familieleden_id`);

--
-- Indexes for table `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `soorten_lid`
--
ALTER TABLE `soorten_lid`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boekjaren`
--
ALTER TABLE `boekjaren`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contributies`
--
ALTER TABLE `contributies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `familieleden`
--
ALTER TABLE `familieleden`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `families`
--
ALTER TABLE `families`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gebruikers`
--
ALTER TABLE `gebruikers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `soorten_lid`
--
ALTER TABLE `soorten_lid`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contributies`
--
ALTER TABLE `contributies`
  ADD CONSTRAINT `contributies_ibfk_12` FOREIGN KEY (`boekjaar_id`) REFERENCES `boekjaren` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `contributies_ibfk_13` FOREIGN KEY (`familielid_id`) REFERENCES `familieleden` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `familieleden`
--
ALTER TABLE `familieleden`
  ADD CONSTRAINT `familieleden_ibfk_1` FOREIGN KEY (`familie_id`) REFERENCES `families` (`id`),
  ADD CONSTRAINT `familieleden_ibfk_2` FOREIGN KEY (`soort_lid_id`) REFERENCES `soorten_lid` (`id`);

--
-- Constraints for table `gebruikers`
--
ALTER TABLE `gebruikers`
  ADD CONSTRAINT `connectie_familieleden` FOREIGN KEY (`familieleden_id`) REFERENCES `familieleden` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `connectie_rol` FOREIGN KEY (`rol`) REFERENCES `rol` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
