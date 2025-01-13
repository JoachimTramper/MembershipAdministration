-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 13, 2025 at 09:13 AM
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
-- Database: `membership_administration`
--

-- --------------------------------------------------------

--
-- Table structure for table `contributions`
--

CREATE TABLE `contributions` (
  `id` int NOT NULL,
  `family_member_id` int DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `payment_date` date DEFAULT NULL,
  `fiscal_year_id` int DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contributions`
--

INSERT INTO `contributions` (`id`, `family_member_id`, `amount`, `type`, `payment_date`, `fiscal_year_id`, `note`) VALUES
(1, 8, 40.00, 'income', NULL, 3, ''),
(2, 7, 50.00, 'income', NULL, 3, ''),
(3, NULL, -100.00, 'expenses', '2022-12-06', 3, 'Roof repair. '),
(4, NULL, -200.00, 'taxes', '2022-11-14', 3, 'Sewer tax. '),
(5, 1, 100.00, 'income', '2022-11-28', 3, ''),
(6, 1, 200.00, 'income', NULL, 3, 'Donation.'),
(7, 10, 55.00, 'income', '2022-12-05', 3, ''),
(8, 5, 100.00, 'income', NULL, 3, ''),
(9, 2, 100.00, 'income', NULL, 4, ''),
(10, 3, 100.00, 'income', '2023-12-05', 4, ''),
(11, NULL, -75.00, 'expenses', '2023-12-09', 4, 'Pest control.'),
(12, NULL, -200.00, 'expenses', '2023-12-04', 4, 'Gas bill, month August.'),
(13, 4, 75.00, 'income', NULL, 4, ''),
(14, 6, 100.00, 'income', NULL, 4, ''),
(15, 9, 55.00, 'income', NULL, 4, ''),
(16, 10, 300.00, 'income', '2023-12-04', 4, 'Donation.');

-- --------------------------------------------------------

--
-- Table structure for table `families`
--

CREATE TABLE `families` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `families`
--

INSERT INTO `families` (`id`, `name`, `address`) VALUES
(1, 'Jansen', 'Kerkstraat 1, 1111 AB, Goes'),
(2, 'Pietersen', 'Hoofdstraat 2, 2222 BC, Vlissingen'),
(3, 'De Vries', 'Langstraat 3, 3333 CD, Middelburg'),
(4, 'Kok', 'Middenweg 4, 4444 DE, Terneuzen');

-- --------------------------------------------------------

--
-- Table structure for table `family_members`
--

CREATE TABLE `family_members` (
  `id` int NOT NULL,
  `family_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dob` date NOT NULL,
  `member_type_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `family_members`
--

INSERT INTO `family_members` (`id`, `family_id`, `name`, `dob`, `member_type_id`) VALUES
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
(11, 4, 'Chef Kok', '1980-01-06', 4);

-- --------------------------------------------------------

--
-- Table structure for table `fiscal_years`
--

CREATE TABLE `fiscal_years` (
  `id` int NOT NULL,
  `year` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fiscal_years`
--

INSERT INTO `fiscal_years` (`id`, `year`) VALUES
(1, 2020),
(2, 2021),
(3, 2022),
(4, 2023);

-- --------------------------------------------------------

--
-- Table structure for table `member_types`
--

CREATE TABLE `member_types` (
  `id` int NOT NULL,
  `member_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `age_from` int NOT NULL,
  `age_till` int NOT NULL,
  `discount` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_types`
--

INSERT INTO `member_types` (`id`, `member_type`, `age_from`, `age_till`, `discount`) VALUES
(1, 'Youth', 0, 7, 50),
(2, 'Aspirant', 8, 12, 40),
(3, 'Junior', 12, 17, 25),
(4, 'Adult', 18, 50, 0),
(5, 'Senior', 51, 120, 45);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `role_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_type`) VALUES
(1, 'admin'),
(2, 'secretary'),
(3, 'treasurer'),
(4, 'member');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `family_member_id` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `family_member_id`, `username`, `password`, `role`) VALUES
(1, 1, 'j.jansen', '$2y$10$n98ZW/xeAC5f9alflmNqquroKfTRpB28D99mlCSP79vFKVuuwlrU2', 1),
(2, 2, 'mariajansen', '$2y$10$/GCivviXyd/1dOf9f/jqXeBYxhXvWalf/1FG9K0Qyt/JtPQWp5LcO', 4),
(3, 3, 'tjansen', '$2y$10$JOV9IUk3icc59loA3vTW5eOa9ZvXa3Yp4It4u3g3x3zjOqHQ0hCSm', 4),
(4, 4, 'LJansen', '$2y$10$tgEvVYCi9yq9l3W4GKkXJeIOnX1LsWuLIkmR0GbEGi2DClHeiFnIC', 4),
(5, 5, 'PP', '$2y$10$2HI6LpGVbtva415XmTUQ0OwwJj/8YCpwt2t3FiqlFoLc3hk8bmcl6', 2),
(6, 6, 'AnnePietersen', '$2y$10$rIk8Undv89uZ8DH.3GcOveLPkzYlZz3s5snsmHTVk272Zo5iyzR1G', 4),
(7, 7, 'KPietersen', '$2y$10$dYkJVCL2He3WQD6bkl6Ez.shyJi9F8yD4YSUw8QqjVUIhgf7C1bY2', 4),
(8, 8, 'BP', '$2y$10$fxUebQ7ySwvYVDt9j8NqZe4/0VdLZ.1Ot595XJYcCoFDpajsg3avO', 4),
(9, 9, 'GDV', '$2y$10$oJ1fcl7R.UrINB8CHC/eleO//zuH9D7V5ZYI./.g5VSlnKWdYAp4u', 4),
(10, 10, 'JDV', '$2y$10$cJ8Ijuk0f2QxJaiiRx5./.TO0QKTXSXaCWHIwidgvWxiuRhZtHI6K', 4),
(11, 11, 'CK', '$2y$10$wP.AumMXYBswPTf5uphxGukiJHCD2JnxEpw7.x9lwl6DOTmeFjvJy', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contributions`
--
ALTER TABLE `contributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contributies_ibfk_12` (`fiscal_year_id`),
  ADD KEY `contributies_ibfk_13` (`family_member_id`);

--
-- Indexes for table `families`
--
ALTER TABLE `families`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `family_members`
--
ALTER TABLE `family_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `familie_id` (`family_id`),
  ADD KEY `soort_lid_id` (`member_type_id`);

--
-- Indexes for table `fiscal_years`
--
ALTER TABLE `fiscal_years`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member_types`
--
ALTER TABLE `member_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `connectie_rol` (`role`),
  ADD KEY `connectie_familieleden` (`family_member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contributions`
--
ALTER TABLE `contributions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `families`
--
ALTER TABLE `families`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `family_members`
--
ALTER TABLE `family_members`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `fiscal_years`
--
ALTER TABLE `fiscal_years`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `member_types`
--
ALTER TABLE `member_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contributions`
--
ALTER TABLE `contributions`
  ADD CONSTRAINT `contributions_ibfk_12` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `contributions_ibfk_13` FOREIGN KEY (`family_member_id`) REFERENCES `family_members` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `family_members`
--
ALTER TABLE `family_members`
  ADD CONSTRAINT `family_members_ibfk_1` FOREIGN KEY (`family_id`) REFERENCES `families` (`id`),
  ADD CONSTRAINT `family_members_ibfk_2` FOREIGN KEY (`member_type_id`) REFERENCES `member_types` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `connectie_familieleden` FOREIGN KEY (`family_member_id`) REFERENCES `family_members` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `connectie_rol` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
