-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2025 at 05:36 PM
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
-- Database: `mertscars`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `appointment_type` varchar(255) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `user_id`, `appointment_type`, `start_time`, `end_time`, `created_at`, `status`, `date`) VALUES
(20, 1, 'Auto aanschaf afspraak', '2025-06-18 13:00:00', '2025-06-18 14:00:00', '2025-06-17 17:14:36', 'approved', '2025-06-18'),
(21, 1, 'Auto aanschaf afspraak', '2025-06-19 17:00:00', '2025-06-19 18:00:00', '2025-06-19 16:08:35', 'approved', '2025-06-19');

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `id` int(11) NOT NULL,
  `model` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `description` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`id`, `model`, `price`, `stock`, `description`, `image`) VALUES
(1, 'BMW M2 CS Coupé', 135200.00, 15, 'Puur racegevoel in een compact formaat. Talrijke technologieën uit de motorsport verhogen de precisie en rijdynamiek. \r\n\r\nMax. vermogen: 390 kW (530 pk)\r\n\r\nKoppel: 650 Nm\r\n\r\n0–100 km/u: 3,8 s\r\n\r\nTopsnelheid: 302 km/u', '684585671afe3.png'),
(2, 'BMW M3 Coupé', 125000.00, 15, 'M-typische performance, Innovatieve technologieën en een interessant design: de 3 Serie M Sedans laten in elk opzicht zien hoe dicht ze bij de motorsport staan.\r\n\r\nVermogen: 390 kW (530 pk)\r\n\r\nKoppel: 650 Nm\r\n\r\n0-100km/u: 3.5 s\r\n\r\nTopsnelheid: 250 km/u', '6845848dc81ed.png'),
(9, 'BMW M4 Coupé', 148200.00, 15, 'De M Coupé-modellenseries combineren een esthetische look met de markante M sportiviteit. Talrijke technologieën uit de motorsport verhogen de rijdynamiek.\r\n\r\nVermogen: 405 kW (551 pk)\r\n\r\nKoppel: 650 Nm\r\n\r\n0-100 km/h: 3.4 s\r\n\r\nTopsnelheid: 302 km/h', '6845886b99a1d.png'),
(10, 'BMW M8 Cabrio', 213400.00, 15, 'De BMW M8 Competition Cabrio en BMW M8 Cabrio combineren het pure M DNA met superieure exclusiviteit – voor uitdagend rijplezier vol sportieve flair en luxe.\r\n\r\nVermogen: 441 kW (600 pk)\r\n\r\nTransmissie:\r\nAutomatische transmissie\r\n\r\n0-100 km/h: 3,4 s', '684589272cc1a.png'),
(11, 'BMW M5 Limousine', 74530.00, 15, 'De zevende generatie van de BMW M5 Sedan brengt voor het eerst een Plug-in Hybride aandrijflijn naar de high performance executive sedan.\r\n\r\nVermogen: 535 kW (727 pk) \r\n\r\nKoppeling: 1.000 Nm \r\n\r\nTopsnelheid: 250 km/u', '684589d4f2c80.png'),
(12, 'BMW X5M SUV', 232450.00, 15, 'De BMW X5 M Competition is vernieuwd en beschikt over nóg betere prestaties, uitstraling en digitale functionaliteiten. Het is het eerste high-performance model van BMW M uitgerust met een nieuwe V8 benzinemotor met 48V Mild Hybrid technologie.\r\n\r\nVermogen: 460Kw (625pk)\r\n\r\nKoppeling: 750 Nm\r\n\r\n0-100km/h: 3,9s', '684590b06409c.png'),
(13, 'BMW I4 eDrive40', 89000.00, 15, 'Bij de BMW i4 eDrive40 zorgt voor een achterwielaandrijving en indrukwekkende sportiviteit. \r\n\r\nVermogen: 250 kW (340 pk) \r\n\r\nkoppel: 430 Nm voor \r\n\r\n0-100km/h: 5,6 sec\r\n\r\nTopsnelheid: 225km/h', '684aae1059bca.png'),
(16, 'BMW M3', 125000.00, 2, 'ssd', '6853f29ed0b44.png'),
(17, 'BMW IX 2', 124058.00, 10, 'Discover BMW iX2 - a small & sporty electric coupé SUV. Comfortable interior & range of up to 279 miles', '68542de829b2d.png');

-- --------------------------------------------------------

--
-- Table structure for table `car_category`
--

CREATE TABLE `car_category` (
  `car_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `car_category`
--

INSERT INTO `car_category` (`car_id`, `category_id`) VALUES
(1, 3),
(2, 3),
(9, 3),
(10, 3),
(11, 3),
(12, 2),
(12, 3),
(13, 1),
(13, 2),
(16, 3),
(17, 2);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'BMW'),
(2, 'BMW I'),
(3, 'BMW M');

-- --------------------------------------------------------

--
-- Table structure for table `contact_request`
--

CREATE TABLE `contact_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` longtext NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_request`
--

INSERT INTO `contact_request` (`id`, `user_id`, `subject`, `message`, `status`, `created_at`) VALUES
(8, 7, 'Ik vind de login kleur niet mooi', 'Ik heb een bug ontdekt in de website', 'nieuw', '2025-06-19 17:28:51');

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250531143457', '2025-05-31 16:35:04', 132),
('DoctrineMigrations\\Version20250531143728', '2025-05-31 16:37:38', 19),
('DoctrineMigrations\\Version20250608093805', '2025-06-08 11:38:13', 156),
('DoctrineMigrations\\Version20250609130321', '2025-06-09 15:03:26', 100),
('DoctrineMigrations\\Version20250609134102', '2025-06-09 15:41:10', 8),
('DoctrineMigrations\\Version20250613121106', '2025-06-13 14:24:09', 6),
('DoctrineMigrations\\Version20250613122002', '2025-06-19 13:07:37', 2),
('DoctrineMigrations\\Version20250613122225', '2025-06-19 13:08:25', 2),
('DoctrineMigrations\\Version20250619110519', '2025-06-19 13:08:25', 129);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` longtext NOT NULL,
  `bot_response` longtext NOT NULL,
  `timestamp` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `message`, `bot_response`, `timestamp`) VALUES
(15, NULL, 'kkkk', 'Dank voor uw vraag. Wij nemen spoedig contact met u op of bezoek onze <a href=\"/faq\">FAQ</a>.', '2025-06-17 17:12:06'),
(16, NULL, 'tt', 'Dank voor uw vraag. Wij nemen spoedig contact met u op of bezoek onze <a href=\"/faq\">FAQ</a>.', '2025-06-17 17:12:10');

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_drive`
--

CREATE TABLE `test_drive` (
  `id` int(11) NOT NULL,
  `car_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `test_drive`
--

INSERT INTO `test_drive` (`id`, `car_id`, `user_id`, `date`, `time`, `status`) VALUES
(2, 1, 1, '2025-06-17', '13:00:00', 'approved'),
(3, 1, 4, '2025-06-18', '15:00:00', 'pending'),
(4, 1, 1, '2025-06-20', '18:50:00', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES
(1, 'mertcan260207@gmail.com', '[\"ROLE_BEHEERDER\"]', '$2y$13$4sISeMVIZ40Fn/swytUi0.1Azu/TlvgX.A3hSFsAXBkF0RljHBdTu'),
(2, 'mert0546@gmail.com', '[\"ROLE_MEDEWERKER\"]', '$2y$13$IArFWKDMy6P9hLPp9itDZub0Q1j6Xp6R/Tqcz1FYcn.J6DhcY3VwC'),
(4, 'gg@gmail.com', '[\"ROLE_MEDEWERKER\"]', '$2y$13$wDA6R2/Gy4182NUX35G5HuETXvp2wNLfD2Y9RPUv/aDW7KQWt30hC'),
(7, 'medewerker@gmail.com', '[\"ROLE_MEDEWERKER\"]', '$2y$13$oQ2n7QZuXlfTNp4YA4YOousTzTHzYu8tCedXweixOuogxXL7K53AC');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FE38F844A76ED395` (`user_id`);

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `car_category`
--
ALTER TABLE `car_category`
  ADD PRIMARY KEY (`car_id`,`category_id`),
  ADD KEY `IDX_897A2CC5C3C6F69F` (`car_id`),
  ADD KEY `IDX_897A2CC512469DE2` (`category_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_request`
--
ALTER TABLE `contact_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A1B8AE1EA76ED395` (`user_id`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B6BD307FA76ED395` (`user_id`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `test_drive`
--
ALTER TABLE `test_drive`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_63C35384C3C6F69F` (`car_id`),
  ADD KEY `IDX_63C35384A76ED395` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_request`
--
ALTER TABLE `contact_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test_drive`
--
ALTER TABLE `test_drive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `FK_FE38F844A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `car_category`
--
ALTER TABLE `car_category`
  ADD CONSTRAINT `FK_897A2CC512469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_897A2CC5C3C6F69F` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_request`
--
ALTER TABLE `contact_request`
  ADD CONSTRAINT `FK_A1B8AE1EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_B6BD307FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `test_drive`
--
ALTER TABLE `test_drive`
  ADD CONSTRAINT `FK_63C35384A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_63C35384C3C6F69F` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
