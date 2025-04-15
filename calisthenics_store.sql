-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2025 at 02:48 AM
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
-- Database: `calisthenics_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `image_path`) VALUES
(3, 'Pull Up Bar', 'Untuk melatih sayap anda!', 60.00, 90, 'uploads/Pull Up Bar.jpg'),
(4, 'Parallete wooden', 'Agar Anda bisa Handstand dan melatih stabilitas Anda!', 45.00, 20, 'uploads/Wooden Paralletes.jpg'),
(5, 'Dips Parallete Bar', 'Untuk memperbanyak repetisi Dips Anda!', 570.00, 10, 'uploads/Dips Paralletes Bar.jpg'),
(6, 'Gymnastic Ring', 'Untuk melatih otot core Anda!', 99.00, 99, 'uploads/Gymnastics Rings.jpg'),
(7, 'Resistance Band', 'Karet lentur yang dapat melatih otot', 80.00, 99, 'uploads/Resistance Band.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_history`
--

CREATE TABLE `purchase_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `purchase_date` datetime DEFAULT current_timestamp(),
  `quantity` int(11) DEFAULT 1,
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_history`
--

INSERT INTO `purchase_history` (`id`, `user_id`, `product_id`, `purchase_date`, `quantity`, `total_price`) VALUES
(4, 2, 3, '2024-12-07 21:22:25', 1, 60.00),
(5, 3, 3, '2024-12-07 21:23:03', 1, 60.00),
(6, 3, 3, '2024-12-07 21:30:10', 1, 60.00),
(7, 2, 4, '2024-12-07 21:44:38', 1, 45.00),
(8, 3, 5, '2024-12-07 21:44:57', 1, 570.00),
(9, 2, 3, '2024-12-08 15:33:13', 1, 60.00),
(10, 2, 3, '2024-12-08 15:35:27', 0, 0.00),
(11, 2, 3, '2024-12-08 15:35:35', 0, 0.00),
(12, 3, 3, '2024-12-08 15:37:16', 2, 120.00),
(13, 2, 4, '2024-12-08 15:39:27', 3, 135.00),
(14, 2, 4, '2024-12-08 15:42:54', 1, 45.00),
(15, 2, 4, '2024-12-08 15:43:00', 1, 45.00),
(16, 2, 5, '2024-12-08 15:43:10', 1, 570.00),
(17, 2, 5, '2024-12-08 15:45:41', 1, 570.00),
(18, 3, 4, '2024-12-08 15:45:55', 3, 135.00),
(19, 3, 5, '2024-12-08 15:46:28', 2, 1140.00),
(20, 3, 5, '2024-12-08 15:47:02', 2, 1140.00),
(21, 3, 5, '2024-12-08 15:47:08', 3, 1710.00),
(22, 3, 6, '2024-12-08 15:47:34', 5, 495.00),
(23, 2, 6, '2024-12-08 15:47:48', 1, 99.00),
(24, 2, 6, '2024-12-08 15:50:58', 4, 356.40),
(25, 3, 7, '2024-12-08 15:56:13', 5, 400.00),
(26, 3, 7, '2024-12-08 16:01:41', 1, 80.00),
(27, 2, 7, '2024-12-08 16:06:58', 1, 80.00),
(28, 2, 7, '2024-12-08 16:11:10', 2, 144.00),
(29, 3, 3, '2024-12-08 16:13:13', 1, 60.00),
(30, 3, 3, '2024-12-08 16:15:06', 1, 60.00),
(31, 3, 3, '2024-12-08 16:16:33', 2, 120.00),
(32, 3, 3, '2024-12-08 16:16:39', 6, 360.00),
(33, 2, 6, '2024-12-08 16:18:17', 1, 89.10),
(34, 2, 3, '2024-12-08 16:19:02', 21, 1134.00),
(35, 2, 7, '2024-12-08 16:19:11', 11, 792.00),
(36, 2, 3, '2024-12-08 16:26:14', 5, 270.00),
(37, 2, 3, '2024-12-08 16:26:20', 4, 216.00);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `purchase_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','member','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin1', '202cb962ac59075b964b07152d234b70', 'admin'),
(2, 'member2', '289dff07669d7a23de0ef88d2f7129e7', 'member'),
(3, 'user3', 'd81f9c1be2e08964bf9f24b15f0e4900', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `purchase_history`
--
ALTER TABLE `purchase_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_history`
--
ALTER TABLE `purchase_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchases_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `purchase_history`
--
ALTER TABLE `purchase_history`
  ADD CONSTRAINT `purchase_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchase_history_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
