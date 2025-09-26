-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2025 at 10:00 AM
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
-- Database: `weart_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$763J4hJ759LTbahf5MlgD.sOvo0nIc7QZGAxDBq.MHDtEW6t5Hfpi', '2025-09-17 17:02:29');

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`id`, `name`, `bio`, `image`, `created_at`) VALUES
(1, 'Sokha Chen', 'Master of traditional Khmer painting techniques with over 20 years of experience. Specializes in depicting scenes from Cambodian mythology and daily life.', '68d6318a2ac1d.png', '2025-09-17 17:02:29'),
(2, 'Bopha Srey', 'Renowned ceramic artist who revives ancient Khmer pottery techniques. Her work has been exhibited internationally.', '68d630a3b4061.png', '2025-09-17 17:02:29'),
(3, 'Rithy Sam', 'Expert in traditional Khmer textile arts, particularly ikat weaving and natural dyeing processes.', '68d6311386d82.png', '2025-09-17 17:02:29');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `artist_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `title`, `description`, `image`, `artist_id`, `created_at`) VALUES
(1, 'Apsara Dancer', 'Traditional depiction of a celestial dancer from Khmer mythology, inspired by the bas-reliefs of Angkor Wat.', '68d62e76dc5a9.png', 1, '2025-09-17 17:02:29'),
(2, 'Ancient Pottery', 'Recreation of 12th century Khmer pottery using traditional techniques and materials.', '68d62eff34c84.png', 2, '2025-09-17 17:02:29'),
(3, 'Ikat Weaving', 'Intricately patterned textile created using the traditional ikat dyeing technique.', '68d62f57bb121.png', 3, '2025-09-17 17:02:29'),
(4, 'Floating Village', 'Scene depicting life on Tonlé Sap lake, showcasing the unique aquatic culture of Cambodia.', '68d62fadad49b.png', 1, '2025-09-17 17:02:29'),
(5, 'Angkor Sunrise', 'Silhouette of Angkor Wat at sunrise, capturing the mystical atmosphere of the ancient temple complex.', '68d630091d721.png', 1, '2025-09-17 17:02:29');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `page_name` varchar(50) NOT NULL,
  `content` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `page_name`, `content`, `updated_at`) VALUES
(1, 'about', '<h2>About WeArt</h2><p>WeArt is dedicated to preserving and promoting Khmer traditional arts. Our mission is to showcase the beauty and cultural significance of Cambodian artistic heritage.</p><h3>Our History</h3><p>Founded in 2023, WeArt emerged from a passion for Cambodian culture and a desire to ensure that traditional art forms continue to thrive in the modern world.</p><h3>Cultural Context</h3><p>Khmer art has a rich history dating back to the Angkor period. It encompasses various forms including sculpture, weaving, ceramics, and performance arts.</p>', '2025-09-17 17:02:29'),
(2, 'contact', '<h2>Contact Information</h2><p><strong>Address:</strong> Phnom Penh, Cambodia</p><p><strong>Email:</strong> info@weart.com</p><p><strong>Phone:</strong> +855 123 456 789</p><h3>Visit Our Gallery</h3><p>We welcome visitors to our gallery to experience the beauty of Khmer traditional art firsthand.</p>', '2025-09-17 17:02:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artist_id` (`artist_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page_name` (`page_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
