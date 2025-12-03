-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2025 at 07:12 AM
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
-- Database: `computer_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(10, 3, 14, 1, '2025-11-17 17:51:12');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `order_date`) VALUES
(1, 1, 599.99, '2025-11-16 01:39:40'),
(2, 2, 99.98, '2025-11-16 20:35:39'),
(3, 1, 2999.95, '2025-11-16 20:55:36'),
(4, 1, 1649.97, '2025-12-01 12:04:57'),
(5, 2, 4799.92, '2025-12-01 19:52:02');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 3, 1, 599.99),
(2, 2, 10, 2, 49.99),
(3, 3, 3, 5, 599.99),
(4, 4, 4, 3, 549.99),
(5, 5, 3, 8, 599.99);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `stock` int(11) DEFAULT 10,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `category`, `stock`, `image`) VALUES
(3, 'ASUS VivoBook 15', 'Lightweight ASUS laptop with 15.6\" display, Intel i5 processor, 8GB RAM, 512GB SSD', 599.99, NULL, 'Laptops', 5, 'asus.jpg'),
(4, 'Dell Inspiron 14', 'Portable Dell laptop with 14\" FHD display, AMD Ryzen 5, 8GB RAM, 256GB SSD', 549.99, NULL, 'Laptops', 12, 'dell.jpg'),
(5, 'MacBook Air M1', 'Powerful MacBook Air with M1 chip, 13.3\" Retina display, 8GB RAM, 256GB SSD', 999.99, NULL, 'Laptops', 6, 'macbook.jpg'),
(6, 'Microsoft Surface Laptop 4', 'Premium Surface Laptop with 13.5\" display, Intel i5, 8GB RAM, 512GB SSD, touchscreen', 1299.99, NULL, 'Laptops', 5, 'surface.jpg'),
(7, 'Huawei MateBook D15', 'Sleek Huawei laptop with 15.6\" display, Intel i7, 8GB RAM, 512GB SSD', 649.99, NULL, 'Laptops', 7, 'huawei.jpg'),
(8, 'Gaming Desktop PC', 'High-performance gaming PC with RTX 3060, Intel i7, 16GB RAM, 512GB SSD', 1299.99, NULL, 'Desktops', 4, 'desktop1.jpg'),
(9, 'Dell USB Mouse', 'Comfortable wired Dell mouse with 3-button design and 1000 DPI', 24.99, NULL, 'Mice', 30, 'dellmouse.jpg'),
(10, 'Gaming Mouse Pro', 'Precision gaming mouse with 7 programmable buttons, 16000 DPI, RGB lighting', 49.99, NULL, 'Mice', 25, 'mouse.jpg'),
(11, 'Wireless Mouse 1', 'Reliable wireless mouse with 2.4GHz connection, 1600 DPI, 18-month battery life', 34.99, NULL, 'Mice', 20, 'mouse1.jpg'),
(12, 'Ergonomic Mouse', 'Ergonomic vertical mouse designed to reduce wrist strain, 6 buttons, 1600 DPI', 39.99, NULL, 'Mice', 18, 'mouse2.jpg'),
(13, 'Compact Mouse', 'Portable compact mouse perfect for travel, wireless 2.4GHz, 1200 DPI', 29.99, NULL, 'Mice', 22, 'mouse3.jpg'),
(14, 'Advanced Gaming Mouse', 'Advanced gaming mouse with 16 programmable buttons, 20000 DPI, wireless/wired modes', 59.99, NULL, 'Mice', 15, 'mouse4.jpg'),
(15, 'Ultra-Precision Mouse', 'Professional-grade mouse with ultra-precise tracking, 24000 DPI, low-latency wireless', 69.99, NULL, 'Mice', 12, 'mouse5.jpg'),
(16, 'Mechanical Keyboard RGB', 'RGB mechanical keyboard with Cherry MX switches, programmable keys, aluminum frame', 99.99, NULL, 'Keyboards', 14, 'keyboard1.jpg'),
(17, 'Wireless Keyboard', 'Wireless keyboard with 2.4GHz connection, quiet keys, 3-month battery life', 49.99, NULL, 'Keyboards', 19, 'keyboard2.jpg'),
(18, 'Compact Keyboard', 'Compact 75% keyboard perfect for minimalist setup, USB wired, quiet mechanical switches', 39.99, NULL, 'Keyboards', 21, 'keyboard3.jpg'),
(19, 'NVIDIA RTX 3060', '12GB GDDR6 memory, 3584 CUDA cores, perfect for gaming and content creation', 329.99, NULL, 'Graphics Cards', 3, 'rtx3060.jpg'),
(20, 'DDR4 RAM 16GB', '16GB DDR4 3200MHz RAM kit (2x8GB), low latency, compatible with most systems', 59.99, NULL, 'Memory', 25, 'ram.jpg'),
(21, 'Seagate 2TB HDD', '2TB external hard drive, USB 3.0, backup software included', 49.99, NULL, 'Storage', 20, 'seagate.jpg'),
(22, 'Transcend SSD 512GB', '512GB SSD with 550MB/s read speed, durable aluminum casing', 69.99, NULL, 'Storage', 16, 'transcend.jpg'),
(23, 'XPG Gaming SSD 1TB', '1TB NVMe SSD optimized for gaming, 3500MB/s read speed, RGB lighting', 99.99, NULL, 'Storage', 10, 'xpg.jpg'),
(27, 'Hyperx', 'A Memory Card', 599.99, NULL, 'Memory', 21, 'graphicscard5090.jpeg'),
(28, 'Intel Desktop Set', 'This is a dell desktop set with windows 11 pre installed with 1 TB ssd and 4 GB Graphics Card. Best for people who works from home', 799.99, NULL, 'Desktops', 2, 'Desktop2.avif'),
(29, 'Apple Mac', 'A Brand New MAC Desktop For Designers.', 1499.99, NULL, 'Desktops', 8, 'Desktop3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `is_admin`) VALUES
(1, 'shubh', 'idk@gmail.com', '$2y$10$lPyYKIxMnILEO3j5gY9Bae.21sbiqVYqUV4e1sN.XwPewMcMEwTZi', 0),
(2, 'Group 7 Admin', 'group7@gmail.com', '$2y$10$JkZO5amaSYP6KCVbykUBsubDaiMn3Oq/bUfjeCq1tm7ZiuwnvMhSa', 1),
(3, 'idk 2', 'idk2@gmail.com', '$2y$10$PE6644sJraxYCCeWHkCvj.BNVrGmfip5L0bIQzuJUtkUTEOJj8lzG', 0),
(4, 'd1', 'd1@gmail.com', '$2y$10$MVU915dNmtlSd9Why9z.5OUX7amvICRJn7R6ftV32CInPXTuP2ehC', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
