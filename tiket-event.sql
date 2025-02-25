-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 25, 2025 at 05:19 PM
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
-- Database: `tiket-event`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `role` enum('admin','kasir','owner') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `activity` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `username`, `role`, `activity`, `ip_address`, `user_agent`, `created_at`) VALUES
(276, 0, 'kasir', 'kasir', 'Logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-25 17:06:37');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `category` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category`, `description`, `created_at`, `updated_at`) VALUES
(1, 'konser musik', 'konser artis lokal maupun internasional.', '2025-02-10 15:29:54', '0000-00-00 00:00:00'),
(2, 'olahraga', 'pertandingan sepak bola, basket, tenis, atau e-sports.', '2025-02-10 15:29:54', '0000-00-00 00:00:00'),
(3, 'festival', 'festival musik, budaya, kuliner, atau film.', '2025-02-10 15:29:54', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int NOT NULL,
  `event_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `event_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `event_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `event_preorder` datetime DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `event_category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `regular_price` decimal(10,2) DEFAULT NULL,
  `tickets_regular` int NOT NULL,
  `tickets_vip` int NOT NULL,
  `tickets_vvip` int NOT NULL,
  `event_status` enum('upcoming','ongoing','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'upcoming',
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'active',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_image`, `event_title`, `location`, `event_description`, `event_preorder`, `event_date`, `event_category`, `regular_price`, `tickets_regular`, `tickets_vip`, `tickets_vvip`, `event_status`, `status`, `archived`, `created_at`, `updated_at`) VALUES
(27, '1738978865_9444ba87476bd80fba1f.png', 'Crowd Sound ID', 'Surabaya', '- [ ] \"CROWD SOUND ID\" adalah konsep acara konser yang MAYORITAS GENRE EDM, yang mana memberikan pengalaman yang berkesan dan penuh riang gembira. dengan konsep multi stage yang kami usung, menjadi salah satu improvisasi yang baru dimana hal ini belum pernah dilakukan sebelumnya di kota surabaya. Target kami adalah bisa menghadirkan musisi dan artis skala nasional dan internasional yang bilamana ini terwujud maka akan menjadi ajang klreatifitas karya musik event yang unggul.', '2025-02-11 09:00:00', '2025-02-28 15:00:00', 'konser musik', 160000.00, 86, 42, 22, 'ongoing', 'active', 0, '2025-02-08 08:41:06', '2025-02-25 23:52:33'),
(28, '1739031048_ea20513efc7ab9fbc677.png', 'Sayfest.Kabaret', 'Bandung', 'Sayfest.Kabaret Merupakan Perlombaan kabaret antar pelajar se-Jawa barat yang di ikuti oleh tim tim kabaret terbaik di Jawa barat. Sayfest.Kabaret Ini di laksanakan oleh OSIS SMK YAPARI-AKTRIPA BANDUNG Dan bekerja sama dengan Federasi Kabaret Bandung.', '2025-04-18 12:00:00', '2025-08-09 07:03:00', 'konser musik', 55000.00, 1000, 100, 10, 'upcoming', 'active', 1, '2025-02-08 23:10:48', '2025-02-09 03:50:35'),
(31, '1738978865_9444ba87476bd80fba1f.png', 'Crowd Sound ID', 'Surabaya', '- [ ] \"CROWD SOUND ID\" adalah konsep acara konser yang MAYORITAS GENRE EDM, yang mana memberikan pengalaman yang berkesan dan penuh riang gembira. dengan konsep multi stage yang kami usung, menjadi salah satu improvisasi yang baru dimana hal ini belum pernah dilakukan sebelumnya di kota surabaya. Target kami adalah bisa menghadirkan musisi dan artis skala nasional dan internasional yang bilamana ini terwujud maka akan menjadi ajang klreatifitas karya musik event yang unggul.', '2025-02-16 09:00:00', '2025-03-01 15:00:00', 'konser musik', 160000.00, 100, 50, 25, 'ongoing', 'active', 0, '2025-02-08 08:41:06', '2025-02-16 20:47:27'),
(32, '1739031048_ea20513efc7ab9fbc677.png', 'Sayfest.Kabaret', 'Bandung', 'Sayfest.Kabaret Merupakan Perlombaan kabaret antar pelajar se-Jawa barat yang di ikuti oleh tim tim kabaret terbaik di Jawa barat. Sayfest.Kabaret Ini di laksanakan oleh OSIS SMK YAPARI-AKTRIPA BANDUNG Dan bekerja sama dengan Federasi Kabaret Bandung.', '2025-02-13 09:00:00', '2025-02-17 07:03:00', 'konser musik', 55000.00, 967, 62, 0, 'completed', 'inactive', 0, '2025-02-08 23:10:48', '2025-02-17 14:05:32'),
(33, '1739031048_ea20513efc7ab9fbc677.png', 'Sayfest.Kabaret', 'Bandung', 'Sayfest.Kabaret Merupakan Perlombaan kabaret antar pelajar se-Jawa barat yang di ikuti oleh tim tim kabaret terbaik di Jawa barat. Sayfest.Kabaret Ini di laksanakan oleh OSIS SMK YAPARI-AKTRIPA BANDUNG Dan bekerja sama dengan Federasi Kabaret Bandung.', '2025-04-18 12:00:00', '2025-08-09 07:03:00', 'konser musik', 55000.00, 1000, 100, 10, 'upcoming', 'active', 1, '2025-02-08 23:10:48', '2025-02-14 07:16:18'),
(34, '1739153679_bae5855a566dd13997b5.png', 'Sayfest.Kabaretad', 'Bandung', 'ghhgh', '2025-02-10 09:15:00', '2025-02-13 09:14:00', 'festival', 55000.00, 994, 97, 6, 'completed', 'inactive', 1, '2025-02-10 09:14:39', '2025-02-14 07:16:12'),
(35, '1738978865_9444ba87476bd80fba1f.png', 'Crowd Sound ID', 'Surabaya', '- [ ] \"CROWD SOUND ID\" adalah konsep acara konser yang MAYORITAS GENRE EDM, yang mana memberikan pengalaman yang berkesan dan penuh riang gembira. dengan konsep multi stage yang kami usung, menjadi salah satu improvisasi yang baru dimana hal ini belum pernah dilakukan sebelumnya di kota surabaya. Target kami adalah bisa menghadirkan musisi dan artis skala nasional dan internasional yang bilamana ini terwujud maka akan menjadi ajang klreatifitas karya musik event yang unggul.', '2025-02-14 09:00:00', '2025-02-22 15:00:00', 'konser musik', 160000.00, 100, 50, 25, 'completed', 'inactive', 0, '2025-02-08 08:41:06', '2025-02-25 23:53:50'),
(36, '1739031048_ea20513efc7ab9fbc677.png', 'Sayfest.Kabaret', 'Bandung', 'Sayfest.Kabaret Merupakan Perlombaan kabaret antar pelajar se-Jawa barat yang di ikuti oleh tim tim kabaret terbaik di Jawa barat. Sayfest.Kabaret Ini di laksanakan oleh OSIS SMK YAPARI-AKTRIPA BANDUNG Dan bekerja sama dengan Federasi Kabaret Bandung.', '2025-04-18 12:00:00', '2025-08-09 07:03:00', 'konser musik', 55000.00, 1000, 100, 10, 'upcoming', 'active', 1, '2025-02-08 23:10:48', '2025-02-14 07:16:24'),
(37, '1739031048_ea20513efc7ab9fbc677.png', 'Sayfest.Kabaret', 'Bandung', 'Sayfest.Kabaret Merupakan Perlombaan kabaret antar pelajar se-Jawa barat yang di ikuti oleh tim tim kabaret terbaik di Jawa barat. Sayfest.Kabaret Ini di laksanakan oleh OSIS SMK YAPARI-AKTRIPA BANDUNG Dan bekerja sama dengan Federasi Kabaret Bandung.', '2025-04-18 12:00:00', '2025-08-09 07:03:00', 'konser musik', 55000.00, 1000, 100, 10, 'upcoming', 'active', 1, '2025-02-08 23:10:48', '2025-02-14 07:16:27'),
(38, '1739153679_bae5855a566dd13997b5.png', 'Sayfest.Kabaretad', 'Bandung', 'ghhgh', '2025-02-10 09:15:00', '2025-02-13 09:14:00', 'festival', 55000.00, 1000, 100, 10, 'completed', 'inactive', 1, '2025-02-10 09:14:39', '2025-02-14 07:15:42'),
(39, '1739153679_bae5855a566dd13997b5.png', 'Sayfest.Kabaretad', 'Bandung', 'ghhgh', '2025-02-16 09:15:00', '2025-02-17 09:14:00', 'festival', 55000.00, 1000, 100, 0, 'completed', 'inactive', 0, '2025-02-10 09:14:39', '2025-02-18 08:52:27'),
(40, '1739031048_ea20513efc7ab9fbc677.png', 'Sayfest.Kabaret', 'Bandung', 'Sayfest.Kabaret Merupakan Perlombaan kabaret antar pelajar se-Jawa barat yang di ikuti oleh tim tim kabaret terbaik di Jawa barat. Sayfest.Kabaret Ini di laksanakan oleh OSIS SMK YAPARI-AKTRIPA BANDUNG Dan bekerja sama dengan Federasi Kabaret Bandung.', '2025-04-18 12:00:00', '2025-08-09 07:03:00', 'konser musik', 55000.00, 1000, 100, 10, 'upcoming', 'active', 1, '2025-02-08 23:10:48', '2025-02-14 07:16:27'),
(41, '1739031048_ea20513efc7ab9fbc677.png', 'Sayfest.Kabaret', 'Bandung', 'Sayfest.Kabaret Merupakan Perlombaan kabaret antar pelajar se-Jawa barat yang di ikuti oleh tim tim kabaret terbaik di Jawa barat. Sayfest.Kabaret Ini di laksanakan oleh OSIS SMK YAPARI-AKTRIPA BANDUNG Dan bekerja sama dengan Federasi Kabaret Bandung.', '2025-02-13 09:00:00', '2025-02-17 07:03:00', 'konser musik', 55000.00, 967, 62, 0, 'completed', 'inactive', 0, '2025-02-08 23:10:48', '2025-02-17 14:05:32'),
(42, '1739153679_bae5855a566dd13997b5.png', 'Sayfest.Kabaretad', 'Bandung', 'ghhgh', '2025-02-16 09:15:00', '2025-02-17 09:14:00', 'festival', 55000.00, 1000, 100, 10, 'completed', 'inactive', 0, '2025-02-10 09:14:39', '2025-02-18 08:52:27'),
(43, '1738978865_9444ba87476bd80fba1f.png', 'Crowd Sound ID', 'Surabaya', '- [ ] \"CROWD SOUND ID\" adalah konsep acara konser yang MAYORITAS GENRE EDM, yang mana memberikan pengalaman yang berkesan dan penuh riang gembira. dengan konsep multi stage yang kami usung, menjadi salah satu improvisasi yang baru dimana hal ini belum pernah dilakukan sebelumnya di kota surabaya. Target kami adalah bisa menghadirkan musisi dan artis skala nasional dan internasional yang bilamana ini terwujud maka akan menjadi ajang klreatifitas karya musik event yang unggul.', '2025-02-11 09:00:00', '2025-02-28 15:00:00', 'konser musik', 160000.00, 97, 49, 25, 'ongoing', 'active', 0, '2025-02-08 08:41:06', '2025-02-16 09:53:04'),
(44, '1739031048_ea20513efc7ab9fbc677.png', 'Sayfest.Kabaret', 'Bandung', 'Sayfest.Kabaret Merupakan Perlombaan kabaret antar pelajar se-Jawa barat yang di ikuti oleh tim tim kabaret terbaik di Jawa barat. Sayfest.Kabaret Ini di laksanakan oleh OSIS SMK YAPARI-AKTRIPA BANDUNG Dan bekerja sama dengan Federasi Kabaret Bandung.', '2025-04-18 12:00:00', '2025-08-09 07:03:00', 'konser musik', 55000.00, 1000, 100, 10, 'upcoming', 'active', 1, '2025-02-08 23:10:48', '2025-02-09 03:50:35'),
(45, '1738978865_9444ba87476bd80fba1f.png', 'Crowd Sound ID', 'Surabaya', '- [ ] \"CROWD SOUND ID\" adalah konsep acara konser yang MAYORITAS GENRE EDM, yang mana memberikan pengalaman yang berkesan dan penuh riang gembira. dengan konsep multi stage yang kami usung, menjadi salah satu improvisasi yang baru dimana hal ini belum pernah dilakukan sebelumnya di kota surabaya. Target kami adalah bisa menghadirkan musisi dan artis skala nasional dan internasional yang bilamana ini terwujud maka akan menjadi ajang klreatifitas karya musik event yang unggul.', '2025-02-16 09:00:00', '2025-03-01 15:00:00', 'konser musik', 160000.00, 100, 50, 25, 'ongoing', 'active', 0, '2025-02-08 08:41:06', '2025-02-16 20:47:27'),
(46, '1739031048_ea20513efc7ab9fbc677.png', 'Sayfest.Kabaret', 'Bandung', 'Sayfest.Kabaret Merupakan Perlombaan kabaret antar pelajar se-Jawa barat yang di ikuti oleh tim tim kabaret terbaik di Jawa barat. Sayfest.Kabaret Ini di laksanakan oleh OSIS SMK YAPARI-AKTRIPA BANDUNG Dan bekerja sama dengan Federasi Kabaret Bandung.', '2025-02-13 09:00:00', '2025-02-17 07:03:00', 'konser musik', 55000.00, 967, 62, 0, 'completed', 'inactive', 0, '2025-02-08 23:10:48', '2025-02-17 14:05:32'),
(47, '1739031048_ea20513efc7ab9fbc677.png', 'Sayfest.Kabaret', 'Bandung', 'Sayfest.Kabaret Merupakan Perlombaan kabaret antar pelajar se-Jawa barat yang di ikuti oleh tim tim kabaret terbaik di Jawa barat. Sayfest.Kabaret Ini di laksanakan oleh OSIS SMK YAPARI-AKTRIPA BANDUNG Dan bekerja sama dengan Federasi Kabaret Bandung.', '2025-04-18 12:00:00', '2025-08-09 07:03:00', 'konser musik', 55000.00, 1000, 100, 10, 'upcoming', 'active', 1, '2025-02-08 23:10:48', '2025-02-14 07:16:18'),
(48, '1738978865_9444ba87476bd80fba1f.png', 'Crowd Sound ID', 'Surabaya', '- [ ] \"CROWD SOUND ID\" adalah konsep acara konser yang MAYORITAS GENRE EDM, yang mana memberikan pengalaman yang berkesan dan penuh riang gembira. dengan konsep multi stage yang kami usung, menjadi salah satu improvisasi yang baru dimana hal ini belum pernah dilakukan sebelumnya di kota surabaya. Target kami adalah bisa menghadirkan musisi dan artis skala nasional dan internasional yang bilamana ini terwujud maka akan menjadi ajang klreatifitas karya musik event yang unggul.', '2025-02-14 09:00:00', '2025-02-22 15:00:00', 'konser musik', 160000.00, 100, 50, 25, 'completed', 'inactive', 0, '2025-02-08 08:41:06', '2025-02-25 23:53:50'),
(49, '1739031048_ea20513efc7ab9fbc677.png', 'Sayfest.Kabaret', 'Bandung', 'Sayfest.Kabaret Merupakan Perlombaan kabaret antar pelajar se-Jawa barat yang di ikuti oleh tim tim kabaret terbaik di Jawa barat. Sayfest.Kabaret Ini di laksanakan oleh OSIS SMK YAPARI-AKTRIPA BANDUNG Dan bekerja sama dengan Federasi Kabaret Bandung.', '2025-04-18 12:00:00', '2025-08-09 07:03:00', 'konser musik', 55000.00, 1000, 100, 10, 'upcoming', 'active', 1, '2025-02-08 23:10:48', '2025-02-14 07:16:24'),
(50, '1739778449_763db92ea9eabaa70ac5.png', 'Pitutour', 'Malang', 'Pitutour Event sudah ada di malang nih! event ini bisa mulai dijual belikan ', '2025-02-16 14:46:00', '2025-02-16 14:46:00', 'konser musik', 20000.00, 100, 100, 19, 'completed', 'inactive', 0, '2025-02-17 14:47:29', '2025-02-17 14:47:29');

-- --------------------------------------------------------

--
-- Table structure for table `events_class`
--

CREATE TABLE `events_class` (
  `id` int NOT NULL,
  `class` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `events_class`
--

INSERT INTO `events_class` (`id`, `class`, `price`, `description`, `created_at`, `updated_at`) VALUES
(5, 'regular', 5000.00, 'Tiket standar dengan harga paling terjangkau, memberikan akses umum ke acara tanpa fasilitas tambahan, seperti tempat duduk khusus atau layanan eksklusif.', '2025-02-10 15:30:42', '2025-02-12 07:04:07'),
(6, 'vip', 10000.00, 'Tiket dengan fasilitas lebih baik dibanding regular, seperti akses ke area khusus, tempat duduk lebih nyaman, antrean prioritas, atau bonus seperti merchandise dan minuman gratis.', '2025-02-10 15:30:42', '2025-02-12 07:04:07'),
(10, 'vvip', 15000.00, 'Tiket premium dengan layanan eksklusif, seperti akses backstage, meet and greet dengan artis, tempat duduk terbaik, layanan makanan dan minuman premium, serta fasilitas mewah lainnya.', '2025-02-10 15:30:42', '2025-02-12 07:04:07');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'administrator'),
(2, 'kasir', 'kasir'),
(3, 'owner', 'owner');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `event_id` int NOT NULL,
  `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `gmail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `transaction_id` varchar(155) NOT NULL,
  `ticket_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `class` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `payment` decimal(10,2) NOT NULL,
  `change` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `total_price` decimal(10,2) NOT NULL,
  `user_id` int NOT NULL,
  `status` enum('pending','paid','cancelled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('admin','kasir','owner') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password_hash`, `role`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(37, 'admin@gmail.com', 'admin', '$2y$10$HCevKcj05rirbSm5Hqm7SO/leoQFoUKc58m2PYSKcTnAb4g45HRS2', 'admin', 'active', '2025-01-24 07:59:29', '2025-02-17 07:11:20', '2025-01-24 07:59:29'),
(95, 'owner@gmail.com', 'owner', '$2y$10$WmJnqKx8wfKgAVsU7YcBZeqqTKUoLADOe.OlVrBSavuPwcC4jfsei', 'owner', 'active', '2025-02-06 11:19:02', '2025-02-06 11:19:02', '2025-02-06 11:19:02'),
(96, 'kasir@gmail.com', 'kasir', '$2y$10$qoriB1DVVLJ8Cuc9torMZun6q/d0d524RfGlNxLI7Bap37QolqQAC', 'kasir', 'active', '2025-02-07 00:37:20', '2025-02-07 00:37:20', '2025-02-07 00:37:20'),
(99, 'kasir2@gmail.com', 'kasir22', '$2y$10$V4Z4p7cM0dCRBYq/maHB0e/HZEa7fHWMwUYBMJ7neIQ1YY2LyIgLu', 'kasir', 'inactive', '2025-02-08 17:36:54', '2025-02-17 07:18:27', '2025-02-08 17:36:54'),
(100, 'tanaka@gmail.com', 'megajelek', '$2y$10$V8VvZ/36kIiiyYxSn9xEReuSU6VQpKctpxYlO7Yhh12pYL.G5zzO2', 'owner', 'active', '2025-02-08 17:50:59', '2025-02-08 17:50:59', '2025-02-08 17:50:59'),
(101, 'raisanuraida11@gmail.com', 'megaaaa', '$2y$10$5X5isBnNYrU0MuLtyS7TKOxQeT8ddCpNaEUNzljKuxFsfMT/dhP1G', 'kasir', 'active', '2025-02-17 07:10:55', '2025-02-17 07:10:55', '2025-02-17 07:10:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_activity_logs` (`user_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `events_class`
--
ALTER TABLE `events_class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=277;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `events_class`
--
ALTER TABLE `events_class`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
