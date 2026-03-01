-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 01, 2026 at 02:04 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u189427921_eng_karangahan`
--

-- --------------------------------------------------------

--
-- Table structure for table `daily_stock`
--

CREATE TABLE `daily_stock` (
  `daily_stock_id` int(11) NOT NULL,
  `inventory_date` date NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_stock`
--

INSERT INTO `daily_stock` (`daily_stock_id`, `inventory_date`, `time_start`, `time_end`) VALUES
(11, '2026-02-23', '08:00:00', '22:00:00'),
(12, '2026-02-25', '08:00:00', '17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `daily_stock_items`
--

CREATE TABLE `daily_stock_items` (
  `item_id` int(11) NOT NULL,
  `daily_stock_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `beginning_stock` int(11) NOT NULL,
  `pull_out_quantity` int(11) NOT NULL,
  `ending_stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_stock_items`
--

INSERT INTO `daily_stock_items` (`item_id`, `daily_stock_id`, `product_id`, `beginning_stock`, `pull_out_quantity`, `ending_stock`) VALUES
(20, 11, 9, 10, 0, 10),
(21, 11, 8, 100, 0, 89),
(22, 11, 6, 10, 0, 10),
(23, 11, 5, 10, 0, 10),
(24, 12, 13, 10, 0, 6);

-- --------------------------------------------------------

--
-- Table structure for table `distributions`
--

CREATE TABLE `distributions` (
  `distribution_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_qnty` int(11) NOT NULL,
  `qty_mode` enum('batch','pieces') NOT NULL DEFAULT 'batch',
  `distribution_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `distributions`
--

INSERT INTO `distributions` (`distribution_id`, `product_id`, `product_qnty`, `qty_mode`, `distribution_date`) VALUES
(4, 9, 10, 'pieces', '2026-02-23'),
(5, 8, 100, 'pieces', '2026-02-23'),
(6, 6, 10, 'pieces', '2026-02-23'),
(7, 5, 10, 'pieces', '2026-02-23'),
(8, 13, 10, 'pieces', '2026-02-25');

-- --------------------------------------------------------

--
-- Table structure for table `material_category`
--

CREATE TABLE `material_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `label` enum('drinks','bread','general') NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `material_category`
--

INSERT INTO `material_category` (`category_id`, `category_name`, `description`, `label`, `date_created`) VALUES
(3, 'Raw Materials - Bread', '', 'bread', '2026-02-22 14:25:13'),
(4, 'Office Supplies', '', 'general', '2026-02-22 14:51:37'),
(7, 'Raw Materials - Drinks', '', 'drinks', '2026-02-22 14:24:48'),
(8, 'Sanitation Supplies', '', 'general', '2026-02-22 14:52:05'),
(11, 'Packaging Supplies', '', 'general', '2026-02-22 15:00:43'),
(12, 'Grocery', '', 'general', '2026-02-23 08:30:38');

-- --------------------------------------------------------

--
-- Table structure for table `material_delivery`
--

CREATE TABLE `material_delivery` (
  `delivery_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `unit` varchar(255) NOT NULL,
  `total_cost` double NOT NULL,
  `date_delivered` date NOT NULL,
  `time_delivered` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `total_payment_due` double NOT NULL,
  `amount_received` double NOT NULL,
  `amount_change` double NOT NULL,
  `payment_method` enum('cash','gcash','maya','credit card','debit card') DEFAULT NULL,
  `order_type` enum('walk-in','foodpanda') DEFAULT NULL,
  `cashier_name` varchar(255) DEFAULT NULL,
  `date_created` date NOT NULL,
  `time_created` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `total_payment_due`, `amount_received`, `amount_change`, `payment_method`, `order_type`, `cashier_name`, `date_created`, `time_created`) VALUES
(3, 1758.8, 2000, 241.2, 'cash', 'walk-in', 'Saara Asug Onoya', '2026-02-23', '18:18:34'),
(4, 199.88, 200, 0.12, 'cash', 'walk-in', 'Saara Asug Onoya', '2026-02-23', '18:21:12'),
(5, 40, 500, 460, 'cash', 'walk-in', 'Saara Asug Onoya', '2026-02-25', '10:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `cost_per_item` double NOT NULL,
  `total_cost_of_item` double NOT NULL,
  `date_created` date NOT NULL,
  `time_created` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `product_id`, `order_id`, `amount`, `cost_per_item`, `total_cost_of_item`, `date_created`, `time_created`) VALUES
(3, 8, 3, 10, 175.88, 1758.8, '2026-02-23', '18:18:34'),
(4, 8, 4, 1, 175.88, 175.88, '2026-02-23', '18:21:12'),
(5, 9, 4, 2, 12, 24, '2026-02-23', '18:21:12'),
(6, 13, 5, 4, 10, 40, '2026-02-25', '10:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category` enum('drinks','bakery','dough','grocery') NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` varchar(255) NOT NULL,
  `is_disabled` tinyint(1) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category`, `product_name`, `product_description`, `is_disabled`, `date_created`, `deleted_at`) VALUES
(1, 'dough', 'Soft Dough', '', 0, '2026-02-22 09:50:38', '2026-02-22 17:50:38'),
(2, 'bakery', 'test1', '', 0, '2026-02-22 09:50:35', '2026-02-22 17:50:35'),
(3, 'grocery', 'Coke', '', 0, '2026-02-22 09:50:33', '2026-02-22 17:50:33'),
(4, 'bakery', 'test2', '', 0, '2026-02-22 09:50:31', '2026-02-22 17:50:31'),
(5, 'dough', 'Soft Dough', '', 0, '2026-02-23 06:51:59', NULL),
(6, 'dough', 'Hopia - Pabalat', '', 0, '2026-02-24 23:46:49', NULL),
(7, 'dough', 'Egg Pie - Crust', '', 0, '2026-02-24 23:47:02', NULL),
(8, 'bakery', 'Pandecoco', '', 0, '2026-02-24 09:52:04', '2026-02-24 17:52:04'),
(9, 'grocery', 'Mineral Water - Refresh', '', 0, '2026-02-23 08:57:20', NULL),
(10, 'bakery', 'Pandecoco - OD', '', 0, '2026-02-24 10:08:55', NULL),
(11, 'dough', 'Ordinary Dough', '', 0, '2026-02-24 10:06:24', NULL),
(12, 'bakery', 'Chiffon Cake', '', 0, '2026-02-24 10:20:18', NULL),
(13, 'bakery', 'Crinkles', '', 0, '2026-02-24 23:46:24', NULL),
(14, 'bakery', 'Butterscotch', '', 0, '2026-02-24 23:58:39', NULL),
(15, 'bakery', 'Milkbread', '', 0, '2026-02-25 08:13:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_combined_recipes`
--

CREATE TABLE `product_combined_recipes` (
  `combined_recipe_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `source_product_id` int(11) NOT NULL,
  `grams_per_piece` double NOT NULL,
  `cost_per_gram` double NOT NULL,
  `total_cost` double NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_combined_recipes`
--

INSERT INTO `product_combined_recipes` (`combined_recipe_id`, `product_id`, `source_product_id`, `grams_per_piece`, `cost_per_gram`, `total_cost`, `date_created`) VALUES
(1, 2, 1, 30, 0.050172821969697, 15.051846590909, '2026-02-22 06:47:30'),
(2, 4, 1, 30, 0.050172821969697, 15.051846590909, '2026-02-22 08:31:36'),
(6, 8, 5, 30, 0.038434892999637, 207.54842219804, '2026-02-23 15:17:49'),
(9, 10, 11, 25, 0.032873132454488, 98.619397363464, '2026-02-24 10:08:55');

-- --------------------------------------------------------

--
-- Table structure for table `product_costs`
--

CREATE TABLE `product_costs` (
  `product_cost_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `direct_cost` double NOT NULL,
  `overhead_cost_percentage` float NOT NULL,
  `overhead_cost_amount` double NOT NULL,
  `combined_recipe_cost` double NOT NULL,
  `profit_margin_percentage` float NOT NULL,
  `profit_amount` double NOT NULL,
  `total_cost` double NOT NULL,
  `selling_price` double NOT NULL,
  `selling_price_per_tray` double NOT NULL,
  `selling_price_per_piece` double NOT NULL,
  `yield_grams` double NOT NULL,
  `trays_per_yield` int(11) NOT NULL,
  `pieces_per_yield` int(11) NOT NULL,
  `grams_per_tray` double DEFAULT NULL,
  `grams_per_piece` double DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_costs`
--

INSERT INTO `product_costs` (`product_cost_id`, `product_id`, `direct_cost`, `overhead_cost_percentage`, `overhead_cost_amount`, `combined_recipe_cost`, `profit_margin_percentage`, `profit_amount`, `total_cost`, `selling_price`, `selling_price_per_tray`, `selling_price_per_piece`, `yield_grams`, `trays_per_yield`, `pieces_per_yield`, `grams_per_tray`, `grams_per_piece`, `date_created`) VALUES
(1, 1, 70.643333333333, 0, 0, 0, 0, 0, 70.643333333333, 71, 0, 2, 1408, 0, 46, 0, 30, '2026-02-22 06:33:54'),
(2, 2, 23, 0, 0, 15.051846590909, 30, 6.9, 23, 60, 0, 5, 100, 0, 10, 0, 10, '2026-02-22 06:47:30'),
(3, 3, 22, 0, 0, 0, 0, 0, 22, 22, 0, 0, 0, 0, 0, 0, 0, '2026-02-22 07:33:09'),
(4, 4, 9, 0, 0, 15.051846590909, 30, 2.7, 9, 40, 0, 4, 100, 0, 10, 0, 10, '2026-02-22 08:31:36'),
(5, 5, 70.643333333333, 0, 0, 0, 0, 0, 70.643333333333, 70.643333333333, 0, 0, 1838, 0, 0, 0, 0, '2026-02-24 10:11:38'),
(6, 6, 245.22444444444, 0, 0, 0, 0, 0, 245.22444444444, 245.22444444444, 0, 0, 5250, 0, 0, 0, 0, '2026-02-23 07:51:21'),
(7, 7, 63.172444444444, 0, 0, 0, 0, 0, 63.172444444444, 63.172444444444, 0, 0, 1715, 0, 6, 0, 285, '2026-02-23 07:53:37'),
(8, 8, 175.875, 0, 0, 207.54842219804, 0, 0, 175.875, 175.875, 0, 0, 2550, 0, 180, 2550, 14.17, '2026-02-23 15:17:49'),
(9, 9, 10, 0, 0, 0, 0, 0, 10, 12, 0, 0, 0, 0, 0, 0, 0, '2026-02-25 00:18:21'),
(10, 10, 120.375, 0, 0, 98.619397363464, 0, 0, 120.375, 120.375, 0, 0, 1800, 0, 120, 0, 15, '2026-02-24 10:08:33'),
(11, 11, 58.185444444444, 0, 0, 0, 0, 0, 58.185444444444, 58.185444444444, 0, 0, 1770, 0, 0, 0, 0, '2026-02-24 10:06:24'),
(12, 12, 756.39, 25, 189.0975, 0, 0, 0, 945.4875, 945.4875, 0, 0, 0, 20, 0, 231.88, 0, '2026-02-24 10:40:28'),
(13, 13, 977.70000000001, 0, 0, 0, 0, 0, 977.70000000001, 977.70000000001, 0, 10, 8850, 0, 354, 0, 25, '2026-02-25 02:21:15'),
(14, 14, 343.91283333334, 25, 85.978208333334, 0, 0, 0, 429.89104166667, 447.08668333334, 0, 0, 3182.5, 5, 9, 636.5, 70.72, '2026-02-25 00:01:59'),
(15, 15, 375.985, 0, 0, 0, 0, 0, 375.985, 375.985, 0, 0, 9070, 5, 48, 1814, 37.79, '2026-02-25 08:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `product_recipe`
--

CREATE TABLE `product_recipe` (
  `recipe_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `quantity_needed` double NOT NULL,
  `unit` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_recipe`
--

INSERT INTO `product_recipe` (`recipe_id`, `product_id`, `material_id`, `quantity_needed`, `unit`, `date_created`) VALUES
(1, 1, 6, 1000, 'grams', '2026-02-22 06:33:54'),
(2, 1, 7, 200, 'grams', '2026-02-22 06:33:54'),
(3, 1, 8, 15, 'grams', '2026-02-22 06:33:54'),
(4, 1, 9, 10, 'grams', '2026-02-22 06:33:54'),
(5, 1, 10, 3, 'grams', '2026-02-22 06:33:54'),
(6, 1, 11, 60, 'grams', '2026-02-22 06:33:54'),
(7, 1, 12, 20, 'grams', '2026-02-22 06:33:54'),
(8, 1, 13, 100, 'grams', '2026-02-22 06:33:54'),
(9, 2, 12, 100, 'grams', '2026-02-22 06:47:30'),
(10, 4, 13, 100, 'grams', '2026-02-22 08:31:36'),
(80, 8, 47, 1500, 'grams', '2026-02-23 15:17:49'),
(81, 8, 7, 750, 'grams', '2026-02-23 15:17:49'),
(82, 8, 42, 100, 'grams', '2026-02-23 15:17:49'),
(83, 8, 127, 200, 'grams', '2026-02-23 15:17:49'),
(101, 10, 47, 1000, 'grams', '2026-02-24 10:08:55'),
(102, 10, 7, 500, 'grams', '2026-02-24 10:08:55'),
(103, 10, 42, 100, 'grams', '2026-02-24 10:08:55'),
(104, 10, 127, 200, 'grams', '2026-02-24 10:08:55'),
(114, 11, 6, 1000, 'grams', '2026-02-24 10:11:17'),
(115, 11, 7, 200, 'grams', '2026-02-24 10:11:17'),
(116, 11, 8, 15, 'grams', '2026-02-24 10:11:17'),
(117, 11, 9, 7, 'grams', '2026-02-24 10:11:17'),
(118, 11, 10, 5, 'grams', '2026-02-24 10:11:17'),
(119, 11, 41, 3, 'grams', '2026-02-24 10:11:17'),
(120, 11, 24, 10, 'grams', '2026-02-24 10:11:17'),
(121, 11, 11, 50, 'grams', '2026-02-24 10:11:17'),
(122, 11, 126, 480, 'grams', '2026-02-24 10:11:17'),
(123, 5, 6, 1000, 'grams', '2026-02-24 10:11:38'),
(124, 5, 7, 200, 'grams', '2026-02-24 10:11:38'),
(125, 5, 8, 15, 'grams', '2026-02-24 10:11:38'),
(126, 5, 9, 10, 'grams', '2026-02-24 10:11:38'),
(127, 5, 10, 3, 'grams', '2026-02-24 10:11:38'),
(128, 5, 11, 60, 'grams', '2026-02-24 10:11:38'),
(129, 5, 12, 20, 'grams', '2026-02-24 10:11:38'),
(130, 5, 13, 100, 'grams', '2026-02-24 10:11:38'),
(131, 5, 126, 430, 'grams', '2026-02-24 10:11:38'),
(160, 12, 36, 1450, 'grams', '2026-02-24 10:40:28'),
(161, 12, 20, 75, 'grams', '2026-02-24 10:40:28'),
(162, 12, 8, 25, 'grams', '2026-02-24 10:40:28'),
(163, 12, 126, 900, 'grams', '2026-02-24 10:40:28'),
(164, 12, 26, 625, 'grams', '2026-02-24 10:40:28'),
(165, 12, 25, 50, 'grams', '2026-02-24 10:40:28'),
(166, 12, 31, 35, 'pcs', '2026-02-24 10:40:28'),
(167, 12, 21, 12.5, 'grams', '2026-02-24 10:40:28'),
(168, 12, 17, 1500, 'grams', '2026-02-24 10:40:28'),
(169, 12, 129, 20, 'pcs', '2026-02-24 10:40:28'),
(178, 6, 6, 2000, 'grams', '2026-02-24 23:46:49'),
(179, 6, 7, 100, 'grams', '2026-02-24 23:46:49'),
(180, 6, 8, 50, 'grams', '2026-02-24 23:46:49'),
(181, 6, 50, 1200, 'grams', '2026-02-24 23:46:49'),
(182, 6, 11, 1400, 'grams', '2026-02-24 23:46:49'),
(183, 6, 126, 500, 'grams', '2026-02-24 23:46:49'),
(184, 7, 50, 1000, 'grams', '2026-02-24 23:47:02'),
(185, 7, 11, 320, 'grams', '2026-02-24 23:47:02'),
(186, 7, 7, 30, 'grams', '2026-02-24 23:47:02'),
(187, 7, 8, 5, 'grams', '2026-02-24 23:47:02'),
(188, 7, 126, 360, 'grams', '2026-02-24 23:47:02'),
(202, 14, 12, 400, 'grams', '2026-02-25 00:01:59'),
(203, 14, 42, 200, 'grams', '2026-02-25 00:01:59'),
(204, 14, 16, 400, 'grams', '2026-02-25 00:01:59'),
(205, 14, 7, 420, 'grams', '2026-02-25 00:01:59'),
(206, 14, 31, 405, 'grams', '2026-02-25 00:01:59'),
(207, 14, 15, 840, 'grams', '2026-02-25 00:01:59'),
(208, 14, 8, 7.5, 'grams', '2026-02-25 00:01:59'),
(209, 14, 35, 7.5, 'grams', '2026-02-25 00:01:59'),
(210, 14, 19, 7.5, 'grams', '2026-02-25 00:01:59'),
(211, 14, 46, 100, 'grams', '2026-02-25 00:01:59'),
(212, 14, 77, 250, 'grams', '2026-02-25 00:01:59'),
(213, 14, 68, 100, 'grams', '2026-02-25 00:01:59'),
(214, 14, 25, 45, 'grams', '2026-02-25 00:01:59'),
(223, 13, 15, 3000, 'grams', '2026-02-25 02:21:41'),
(224, 13, 7, 2400, 'grams', '2026-02-25 02:21:41'),
(225, 13, 19, 100, 'grams', '2026-02-25 02:21:41'),
(226, 13, 24, 500, 'grams', '2026-02-25 02:21:41'),
(227, 13, 22, 300, 'grams', '2026-02-25 02:21:41'),
(228, 13, 23, 300, 'grams', '2026-02-25 02:21:41'),
(229, 13, 42, 720, 'grams', '2026-02-25 02:21:41'),
(230, 13, 31, 1530, 'grams', '2026-02-25 02:21:41'),
(231, 15, 6, 3000, 'grams', '2026-02-25 08:13:44'),
(232, 15, 9, 50, 'grams', '2026-02-25 08:13:44'),
(233, 15, 19, 90, 'grams', '2026-02-25 08:13:44'),
(234, 15, 42, 300, 'grams', '2026-02-25 08:13:44'),
(235, 15, 126, 2880, 'grams', '2026-02-25 08:13:44'),
(236, 15, 7, 2250, 'grams', '2026-02-25 08:13:44'),
(237, 15, 24, 500, 'grams', '2026-02-25 08:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `raw_materials`
--

CREATE TABLE `raw_materials` (
  `material_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `material_quantity` double NOT NULL,
  `unit` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raw_materials`
--

INSERT INTO `raw_materials` (`material_id`, `category_id`, `material_name`, `material_quantity`, `unit`, `date_created`) VALUES
(6, 3, 'Flour - Kutitap First Class', 25000, 'grams', '2026-02-22 06:19:25'),
(7, 3, 'Sugar - 99', 50000, 'grams', '2026-02-22 06:20:06'),
(8, 3, 'Salt', 25000, 'grams', '2026-02-22 06:20:47'),
(9, 3, 'Yeast - Angel Instant', 1000, 'grams', '2026-02-22 09:08:41'),
(10, 3, 'Improver - Toupan', 1000, 'grams', '2026-02-22 06:22:08'),
(11, 3, 'Lard - Approved', 36000, 'grams', '2026-02-22 06:22:54'),
(12, 3, 'Buttercup', 300, 'grams', '2026-02-22 07:02:18'),
(13, 3, 'Milk - Freshmilk', 1000, 'grams', '2026-02-22 13:44:24'),
(15, 3, 'Flour - All Purpose', 25000, 'grams', '2026-02-22 09:58:20'),
(16, 3, 'Sugar - Brown', 50000, 'grams', '2026-02-22 09:59:40'),
(17, 3, 'Sugar - White', 50000, 'grams', '2026-02-22 10:00:03'),
(18, 3, 'Sugar - Penco Powdered', 2272, 'grams', '2026-02-22 10:01:08'),
(19, 3, 'Baking Powder - Ordinary ', 1000, 'grams', '2026-02-22 10:02:25'),
(20, 3, 'Baking Powder - Calumet', 1000, 'grams', '2026-02-22 10:03:32'),
(21, 3, 'Cream of Tartar', 50, 'grams', '2026-02-22 10:04:33'),
(22, 3, 'Cocoa - JB15', 500, 'grams', '2026-02-28 10:51:08'),
(23, 3, 'Cocoa - Ordinary', 25000, 'grams', '2026-02-22 10:05:29'),
(24, 3, 'Buttermilk ', 25000, 'grams', '2026-02-22 10:06:01'),
(25, 3, 'Vanilla', 4000, 'grams', '2026-02-22 10:06:31'),
(26, 3, 'Oil - Canola', 1000, 'grams', '2026-02-22 10:07:19'),
(27, 3, 'Cheese - Magnolia', 165, 'grams', '2026-02-22 10:09:58'),
(28, 3, 'Milk - Evaporated ', 360, 'grams', '2026-02-22 13:43:54'),
(29, 3, 'Milk - Condensed', 390, 'grams', '2026-02-22 13:43:32'),
(30, 3, 'Cornstarch - Farola', 25000, 'grams', '2026-02-22 13:39:53'),
(31, 3, 'Eggs', 1350, 'grams', '2026-02-24 11:18:11'),
(32, 3, 'Food Color - Strawberry Red', 454, 'grams', '2026-02-22 13:41:16'),
(33, 3, 'Food Color - Ube', 454, 'grams', '2026-02-22 13:41:36'),
(34, 3, 'Food Color - Egg Yellow', 454, 'grams', '2026-02-22 13:42:08'),
(35, 3, 'Baking Soda', 250, 'grams', '2026-02-22 13:42:30'),
(36, 3, 'Flour - Cake', 25000, 'grams', '2026-02-22 13:43:09'),
(37, 3, 'Coffee - Blend 45', 100, 'grams', '2026-02-22 13:46:00'),
(38, 3, 'Oil - Palm Ordinary Cooking', 1000, 'grams', '2026-02-22 13:46:50'),
(39, 3, 'Desiccated Coconut', 1000, 'grams', '2026-02-22 13:47:50'),
(40, 3, 'Emulsifier - Puratos Mixo', 250, 'grams', '2026-02-22 13:48:50'),
(41, 3, 'Macco - Puratos Preservative Bread', 1000, 'grams', '2026-02-22 13:49:18'),
(42, 3, 'Margarine - Baker\'s Choice', 36000, 'grams', '2026-02-22 13:49:56'),
(43, 3, 'Ube Paste', 1000, 'grams', '2026-02-22 13:50:18'),
(44, 3, 'Monggo Paste', 1000, 'grams', '2026-02-22 13:50:46'),
(45, 3, 'Onion', 1000, 'grams', '2026-02-22 13:51:12'),
(46, 3, 'Peanut - Skinless', 1000, 'grams', '2026-02-22 13:51:38'),
(47, 3, 'Niyog', 1000, 'grams', '2026-02-22 13:51:59'),
(48, 3, 'Raisin', 1000, 'grams', '2026-02-22 13:52:25'),
(49, 3, 'Yeast - Red Star Active Dry ', 800, 'grams', '2026-02-22 13:53:00'),
(50, 3, 'Flour - Mayon Third Class', 25000, 'grams', '2026-02-22 13:54:00'),
(51, 3, 'Potassium Sorbate - Preservative Cake', 1000, 'grams', '2026-02-22 13:54:41'),
(52, 3, 'Carbonato', 250, 'grams', '2026-02-22 13:55:02'),
(53, 3, 'Mayonnaise - Kewpie', 1000, 'grams', '2026-02-22 13:55:37'),
(54, 3, 'Mayonnaise - All Purpose Dressing', 470, 'grams', '2026-02-22 13:56:19'),
(55, 3, 'Tuna - Century', 140, 'grams', '2026-02-22 13:56:54'),
(56, 3, 'Cinnamon Powder - HYCO', 500, 'grams', '2026-02-22 13:58:01'),
(57, 3, 'Creamcheese - Anchor', 1000, 'grams', '2026-02-22 14:01:37'),
(58, 3, 'Rolled Oats - Quaker', 500, 'grams', '2026-02-22 14:02:31'),
(59, 3, 'Cream - All Purpose ', 250, 'grams', '2026-02-22 14:03:19'),
(60, 3, 'Cheese - Cheddar Magnolia', 165, 'grams', '2026-02-22 14:04:15'),
(61, 3, 'Hotdog ', 10, 'grams', '2026-02-22 14:04:42'),
(62, 3, 'Nutmeg - McCormick', 30, 'grams', '2026-02-22 14:05:18'),
(63, 3, 'Chocolate Bar - Fuji', 1000, 'grams', '2026-02-22 14:06:04'),
(64, 3, 'Cream - Whipping Whip It', 500, 'grams', '2026-02-22 14:07:45'),
(65, 3, 'Sweet Ham - CDO Regular', 8, 'pcs', '2026-02-22 14:09:26'),
(66, 3, 'Butter - Magnolia ', 220, 'grams', '2026-02-22 14:09:48'),
(67, 3, 'Milk - Condensed Ube', 390, 'grams', '2026-02-22 14:11:26'),
(68, 3, 'Chocolate Drops - Choco', 1000, 'grams', '2026-02-22 14:12:00'),
(69, 3, 'Chocolate Drops - White', 1000, 'grams', '2026-02-22 14:12:38'),
(70, 3, 'Banana - Bungoran', 1000, 'grams', '2026-02-22 14:13:07'),
(71, 3, 'Pineapple', 1, 'pcs', '2026-02-22 14:14:10'),
(72, 3, 'Walnuts', 250, 'grams', '2026-02-22 14:15:00'),
(73, 3, 'Almond', 250, 'grams', '2026-02-22 14:15:18'),
(74, 3, 'Marshmallow', 680, 'pcs', '2026-02-22 14:16:22'),
(75, 3, 'Powder - Matcha', 100, 'grams', '2026-02-22 14:20:24'),
(76, 7, 'Ice', 2000, 'grams', '2026-02-22 14:25:37'),
(77, 3, 'Glucose', 750, 'grams', '2026-02-22 14:26:41'),
(78, 3, 'Butter - Queensland', 225, 'grams', '2026-02-22 14:27:48'),
(79, 7, 'Coffee - Beans', 1000, 'grams', '2026-02-22 14:29:55'),
(80, 7, 'Milk - Freshmilk UHT', 1000, 'grams', '2026-02-22 14:35:14'),
(81, 7, 'Syrup - Caramel', 750, 'grams', '2026-02-22 14:40:49'),
(82, 7, 'Syrup - French Vanilla', 750, 'grams', '2026-02-22 14:41:30'),
(83, 7, 'Powder - Matcha Frappe', 1000, 'grams', '2026-02-22 14:42:19'),
(84, 7, 'Powder - Vanilla ', 1000, 'grams', '2026-02-22 14:44:04'),
(85, 7, 'Powder - Choco', 1000, 'grams', '2026-02-22 14:44:46'),
(86, 7, 'Syrup - Choco', 750, 'grams', '2026-02-22 14:45:10'),
(87, 7, 'Syrup - Strawberry', 750, 'grams', '2026-02-22 14:45:46'),
(88, 7, 'Cream - Whipping Ever Whip', 1030, 'grams', '2026-02-22 14:47:36'),
(89, 8, 'Tissue ', 1000, 'pcs', '2026-02-22 14:56:16'),
(91, 11, 'Stirrer', 1, 'pcs', '2026-02-22 15:01:02'),
(92, 11, 'Straw - Frappe', 1, 'pcs', '2026-02-22 15:02:46'),
(93, 11, 'Straw - Iced', 1, 'pcs', '2026-02-22 15:06:59'),
(94, 11, 'Cup with Lid - 12 oz Hot', 1, 'pcs', '2026-02-22 15:05:00'),
(95, 11, 'Cup with Lid - 16 oz Iced ', 1, 'pcs', '2026-02-22 15:06:10'),
(96, 11, 'Cup with Lid - 16 oz Frappe', 1, 'pcs', '2026-02-22 15:07:59'),
(97, 4, 'Scotch Tape', 1, 'pcs', '2026-02-22 15:10:10'),
(98, 4, 'Tagger', 1, 'pcs', '2026-02-22 15:10:52'),
(99, 4, 'Sticker Paper - Joy Matte', 100, 'pcs', '2026-02-22 15:11:35'),
(100, 4, 'C Delivery Receipt - L(2 Copy 50 Pages)', 5, 'pcs', '2026-02-22 15:22:00'),
(101, 4, 'C Delivery Receipt - L(3 Copy 60 Pages)', 5, 'pcs', '2026-02-22 15:31:49'),
(104, 11, 'Boxes - 10x10x4 in', 20, 'pcs', '2026-02-22 15:54:08'),
(105, 11, 'Boxes - 9x9x2 in', 20, 'pcs', '2026-02-22 15:54:00'),
(106, 11, 'Brown Paper', 10, 'pcs', '2026-02-22 15:53:33'),
(107, 11, 'Cello Sheet - 9x13 in', 2000, 'pcs', '2026-02-22 15:58:48'),
(108, 8, 'Gloves - Clear', 100, 'pcs', '2026-02-22 15:59:26'),
(109, 11, 'Plastic Bag - Medium', 240, 'pcs', '2026-02-23 03:45:15'),
(110, 11, 'Plastic Bag - Mini', 500, 'pcs', '2026-02-23 03:45:42'),
(111, 11, 'Plastic Bag - Tiny ', 500, 'pcs', '2026-02-23 03:50:33'),
(112, 11, 'Plastic Labo - 8x11 in', 650, 'pcs', '2026-02-23 03:48:00'),
(113, 11, 'Plastic Labo - 10x14 in', 650, 'pcs', '2026-02-23 03:49:33'),
(114, 11, 'Supot No. 1', 100, 'pcs', '2026-02-23 03:52:10'),
(115, 11, 'Supot No. 2', 100, 'pcs', '2026-02-23 03:52:18'),
(116, 11, 'Supot No. 3', 100, 'pcs', '2026-02-23 03:53:29'),
(117, 11, 'Supot No. 5', 100, 'pcs', '2026-02-23 03:53:53'),
(118, 11, 'Plastic Custard - 5.5 cm', 500, 'pcs', '2026-02-23 03:55:04'),
(119, 11, 'Pastry Pouch - 11x13 cm', 500, 'pcs', '2026-02-23 03:55:51'),
(120, 11, 'Pastry Pouch - 10x13.5 cm', 500, 'pcs', '2026-02-23 03:56:29'),
(121, 11, 'Sliced Bread Clear - 30x34+10 cm', 500, 'pcs', '2026-02-23 03:59:55'),
(122, 11, 'Sliced Bread Opaque ', 100, 'pcs', '2026-02-23 03:58:30'),
(123, 11, 'Ribbon Size 2 - 50 m (1.6m/box)', 31, 'pcs', '2026-02-23 03:59:40'),
(124, 8, 'Dishwashing - Joy Green Big', 36, 'pcs', '2026-02-23 05:04:52'),
(125, 8, 'Detergent - Surf Powder', 12, 'pcs', '2026-02-23 05:05:39'),
(126, 3, 'Water', 0, 'grams', '2026-02-23 07:47:21'),
(127, 3, 'Bread Crumbs', 0, 'grams', '2026-02-23 08:01:41'),
(128, 12, 'Mineral Water - Refresh', 1, 'pcs', '2026-02-23 08:30:49'),
(129, 11, 'Box - 6x6x3 in', 1, 'pcs', '2026-02-24 10:22:57');

-- --------------------------------------------------------

--
-- Table structure for table `raw_material_cost`
--

CREATE TABLE `raw_material_cost` (
  `cost_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `cost_per_unit` double NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raw_material_cost`
--

INSERT INTO `raw_material_cost` (`cost_id`, `material_id`, `cost_per_unit`, `date_created`) VALUES
(6, 6, 0.036, '2026-02-22 06:19:25'),
(7, 7, 0.062, '2026-02-22 06:20:06'),
(8, 8, 0.0136, '2026-02-22 06:20:47'),
(9, 9, 0.256, '2026-02-22 06:21:30'),
(10, 10, 0.132, '2026-02-22 06:22:08'),
(11, 11, 0.091388888888889, '2026-02-22 06:22:54'),
(12, 12, 0.23, '2026-02-22 06:23:27'),
(13, 13, 0.09, '2026-02-22 06:24:06'),
(15, 15, 0.054, '2026-02-22 09:58:20'),
(16, 16, 0.06, '2026-02-22 09:59:40'),
(17, 17, 0.0716, '2026-02-22 10:00:03'),
(18, 18, 0.11443661971831, '2026-02-22 10:01:08'),
(19, 19, 0.084, '2026-02-22 10:02:25'),
(20, 20, 0.205, '2026-02-22 10:03:32'),
(21, 21, 0.64, '2026-02-22 10:04:33'),
(22, 22, 7.3, '2026-02-28 10:51:08'),
(23, 23, 0.18, '2026-02-22 10:05:29'),
(24, 24, 0.16, '2026-02-22 10:06:01'),
(25, 25, 0.0395, '2026-02-22 10:06:31'),
(26, 26, 0.16, '2026-02-22 10:07:19'),
(27, 27, 0.27272727272727, '2026-02-22 10:09:58'),
(28, 28, 0.1, '2026-02-22 13:38:41'),
(29, 29, 0.11794871794872, '2026-02-22 14:38:35'),
(30, 30, 0.0432, '2026-02-22 13:39:53'),
(31, 31, 0.15555555555556, '2026-02-24 11:18:11'),
(32, 32, 0.18722466960352, '2026-02-22 13:41:16'),
(33, 33, 0.18722466960352, '2026-02-22 13:41:36'),
(34, 34, 0.18722466960352, '2026-02-22 13:42:08'),
(35, 35, 0.096, '2026-02-22 13:42:30'),
(36, 36, 0.054, '2026-02-22 13:43:09'),
(37, 37, 0.78, '2026-02-23 05:01:29'),
(38, 38, 0.086, '2026-02-22 13:46:50'),
(39, 39, 0.18, '2026-02-22 13:47:50'),
(40, 40, 0.436, '2026-02-22 13:48:50'),
(41, 41, 0.32, '2026-02-22 13:49:18'),
(42, 42, 0.09375, '2026-02-22 13:49:56'),
(43, 43, 0.16, '2026-02-22 13:50:18'),
(44, 44, 0.16, '2026-02-22 13:50:46'),
(45, 45, 0.2, '2026-02-22 13:51:12'),
(46, 46, 0.102, '2026-02-22 13:51:38'),
(47, 47, 0.08, '2026-02-22 13:51:59'),
(48, 48, 0.42, '2026-02-22 13:52:25'),
(49, 49, 0.39625, '2026-02-22 13:53:00'),
(50, 50, 0.032, '2026-02-22 13:54:00'),
(51, 51, 0.458, '2026-02-22 13:54:41'),
(52, 52, 0.06, '2026-02-22 13:55:02'),
(53, 53, 0.38, '2026-02-22 13:55:37'),
(54, 54, 0.22765957446809, '2026-02-22 13:56:19'),
(55, 55, 0.32857142857143, '2026-02-22 13:56:54'),
(56, 56, 1.05, '2026-02-22 13:58:01'),
(57, 57, 0.6, '2026-02-22 14:01:37'),
(58, 58, 0.234, '2026-02-22 14:02:31'),
(59, 59, 0.284, '2026-02-22 14:03:19'),
(60, 60, 0.63636363636364, '2026-02-22 14:04:15'),
(61, 61, 6.1, '2026-02-22 14:04:42'),
(62, 62, 3.4, '2026-02-22 14:05:18'),
(63, 63, 0.305, '2026-02-22 14:06:04'),
(64, 64, 0.28, '2026-02-22 14:06:30'),
(65, 65, 15, '2026-02-22 14:09:26'),
(66, 66, 0.88636363636364, '2026-02-22 14:09:48'),
(67, 67, 0.14358974358974, '2026-02-22 14:11:26'),
(68, 68, 0.38, '2026-02-22 14:12:00'),
(69, 69, 0.28, '2026-02-22 14:12:38'),
(70, 70, 0.03, '2026-02-22 14:13:07'),
(71, 71, 35, '2026-02-22 14:14:10'),
(72, 72, 0.716, '2026-02-22 14:27:22'),
(73, 73, 0.74, '2026-02-22 14:15:18'),
(74, 74, 0.17647058823529, '2026-02-22 14:16:22'),
(75, 75, 1.65, '2026-02-22 14:20:24'),
(76, 76, 0.013, '2026-02-22 14:25:37'),
(77, 77, 0.093333333333333, '2026-02-22 14:26:41'),
(78, 78, 0.6, '2026-02-22 14:27:48'),
(79, 79, 1.25, '2026-02-22 14:29:55'),
(80, 80, 0.08, '2026-02-22 14:35:14'),
(81, 81, 0.4, '2026-02-22 14:40:49'),
(82, 82, 0.4, '2026-02-22 14:41:30'),
(83, 83, 0.3, '2026-02-22 14:42:19'),
(84, 84, 0.3, '2026-02-22 14:44:04'),
(85, 85, 0.3, '2026-02-22 14:44:46'),
(86, 86, 0.4, '2026-02-22 14:45:10'),
(87, 87, 0.4, '2026-02-22 14:45:46'),
(88, 88, 0.31553398058252, '2026-02-22 14:47:36'),
(89, 89, 0.08, '2026-02-22 14:53:11'),
(91, 91, 0.5, '2026-02-22 15:01:02'),
(92, 92, 0.5, '2026-02-22 15:02:46'),
(93, 93, 0.5, '2026-02-22 15:03:23'),
(94, 94, 9.6, '2026-02-22 15:05:00'),
(95, 95, 7.7, '2026-02-22 15:06:10'),
(96, 96, 8, '2026-02-22 15:07:59'),
(97, 97, 7.5, '2026-02-22 15:10:10'),
(98, 98, 12, '2026-02-22 15:10:52'),
(99, 99, 3.77, '2026-02-22 15:11:35'),
(100, 100, 23.6, '2026-02-22 15:21:43'),
(101, 101, 25.6, '2026-02-22 15:23:07'),
(104, 104, 29.9, '2026-02-22 15:49:47'),
(105, 105, 19.4, '2026-02-22 15:51:28'),
(106, 106, 3.2, '2026-02-22 15:53:33'),
(107, 107, 0.615, '2026-02-22 15:58:48'),
(108, 108, 0.35, '2026-02-22 15:59:26'),
(109, 109, 0.83333333333333, '2026-02-23 03:45:15'),
(110, 110, 0.154, '2026-02-23 03:45:42'),
(111, 111, 0.188, '2026-02-23 03:50:33'),
(112, 112, 0.1, '2026-02-23 03:49:47'),
(113, 113, 0.16923076923077, '2026-02-23 03:49:33'),
(114, 114, 0.35, '2026-02-23 03:51:28'),
(115, 115, 0.4, '2026-02-23 03:51:59'),
(116, 116, 0.45, '2026-02-23 03:53:29'),
(117, 117, 0.65, '2026-02-23 03:53:53'),
(118, 118, 1.306, '2026-02-23 03:55:04'),
(119, 119, 1.216, '2026-02-23 03:55:51'),
(120, 120, 0.896, '2026-02-23 03:56:29'),
(121, 121, 1.966, '2026-02-23 03:58:01'),
(122, 122, 0.95, '2026-02-23 03:58:30'),
(123, 123, 2.4193548387097, '2026-02-23 03:59:40'),
(124, 124, 11.166666666667, '2026-02-23 05:04:52'),
(125, 125, 6.6666666666667, '2026-02-23 05:05:39'),
(126, 126, 0, '2026-02-23 07:47:21'),
(127, 127, 0, '2026-02-23 08:01:41'),
(128, 128, 10, '2026-02-23 08:30:49'),
(129, 129, 10, '2026-02-24 10:22:57');

-- --------------------------------------------------------

--
-- Table structure for table `raw_material_stock`
--

CREATE TABLE `raw_material_stock` (
  `stock_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `initial_qty` double NOT NULL,
  `qty_used` double NOT NULL,
  `unit` varchar(25) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raw_material_stock`
--

INSERT INTO `raw_material_stock` (`stock_id`, `material_id`, `initial_qty`, `qty_used`, `unit`, `updated_at`) VALUES
(14, 6, 25000, 0, 'grams', '2026-02-23 10:15:40'),
(15, 7, 50000, 0, 'grams', '2026-02-28 06:23:05'),
(16, 8, 25000, 0, 'grams', '2026-02-28 06:23:05'),
(17, 9, 1000, 0, 'grams', '2026-02-23 10:15:40'),
(18, 10, 1000, 0, 'grams', '2026-02-23 10:15:40'),
(19, 11, 36000, 0, 'grams', '2026-02-23 10:15:40'),
(20, 12, 300, 0, 'grams', '2026-02-28 06:23:05'),
(21, 13, 1000, 0, 'grams', '2026-02-23 10:15:40'),
(23, 15, 25000, 0, 'grams', '2026-02-28 06:23:05'),
(24, 16, 50000, 0, 'grams', '2026-02-28 06:23:05'),
(25, 17, 50000, 0, 'grams', '2026-02-22 10:00:03'),
(26, 18, 2272, 0, 'grams', '2026-02-22 10:01:08'),
(27, 19, 1000, 0, 'grams', '2026-02-28 06:23:05'),
(28, 20, 1000, 0, 'grams', '2026-02-22 10:03:32'),
(29, 21, 50, 0, 'grams', '2026-02-22 10:04:33'),
(30, 22, 500, 0, 'grams', '2026-02-28 18:50:50'),
(31, 23, 25000, 0, 'grams', '2026-02-28 06:23:05'),
(32, 24, 25000, 0, 'grams', '2026-02-28 06:23:05'),
(33, 25, 4000, 0, 'grams', '2026-02-28 06:23:05'),
(34, 26, 1000, 0, 'grams', '2026-02-22 10:07:19'),
(35, 27, 165, 0, 'grams', '2026-02-22 10:09:58'),
(36, 28, 360, 0, 'grams', '2026-02-22 13:38:41'),
(37, 29, 390, 0, 'grams', '2026-02-22 13:39:12'),
(38, 30, 25000, 0, 'grams', '2026-02-22 13:39:53'),
(39, 31, 1350, 0, 'grams', '2026-02-28 06:23:05'),
(40, 32, 454, 0, 'grams', '2026-02-22 13:41:16'),
(41, 33, 454, 0, 'grams', '2026-02-22 13:41:36'),
(42, 34, 454, 0, 'grams', '2026-02-22 13:42:08'),
(43, 35, 250, 0, 'grams', '2026-02-28 06:23:05'),
(44, 36, 25000, 0, 'grams', '2026-02-22 13:43:09'),
(45, 37, 100, 0, 'grams', '2026-02-22 13:46:00'),
(46, 38, 1000, 0, 'grams', '2026-02-22 13:46:50'),
(47, 39, 1000, 0, 'grams', '2026-02-22 13:47:50'),
(48, 40, 250, 0, 'grams', '2026-02-22 13:48:50'),
(49, 41, 1000, 0, 'grams', '2026-02-22 13:49:18'),
(50, 42, 36000, 0, 'grams', '2026-02-28 06:23:05'),
(51, 43, 1000, 0, 'grams', '2026-02-22 13:50:18'),
(52, 44, 1000, 0, 'grams', '2026-02-22 13:50:46'),
(53, 45, 1000, 0, 'grams', '2026-02-22 13:51:12'),
(54, 46, 1000, 0, 'grams', '2026-02-28 06:23:05'),
(55, 47, 1000, 0, 'grams', '2026-02-23 10:15:40'),
(56, 48, 1000, 0, 'grams', '2026-02-22 13:52:25'),
(57, 49, 800, 0, 'grams', '2026-02-22 13:53:00'),
(58, 50, 25000, 0, 'grams', '2026-02-23 10:14:27'),
(59, 51, 1000, 0, 'grams', '2026-02-22 13:54:41'),
(60, 52, 250, 0, 'grams', '2026-02-22 13:55:02'),
(61, 53, 1000, 0, 'grams', '2026-02-22 13:55:37'),
(62, 54, 470, 0, 'grams', '2026-02-22 13:56:19'),
(63, 55, 140, 0, 'grams', '2026-02-22 13:56:54'),
(64, 56, 500, 0, 'grams', '2026-02-22 13:58:01'),
(65, 57, 1000, 0, 'grams', '2026-02-22 14:01:37'),
(66, 58, 500, 0, 'grams', '2026-02-22 14:02:31'),
(67, 59, 250, 0, 'grams', '2026-02-22 14:03:19'),
(68, 60, 165, 0, 'grams', '2026-02-22 14:04:15'),
(69, 61, 10, 0, 'grams', '2026-02-22 14:04:42'),
(70, 62, 30, 0, 'grams', '2026-02-22 14:05:18'),
(71, 63, 1000, 0, 'grams', '2026-02-22 14:06:04'),
(72, 64, 500, 0, 'grams', '2026-02-22 14:06:30'),
(73, 65, 8, 0, 'pcs', '2026-02-22 14:09:26'),
(74, 66, 220, 0, 'grams', '2026-02-22 14:09:48'),
(75, 67, 390, 0, 'grams', '2026-02-22 14:11:26'),
(76, 68, 1000, 0, 'grams', '2026-02-28 06:23:05'),
(77, 69, 1000, 0, 'grams', '2026-02-22 14:12:38'),
(78, 70, 1000, 0, 'grams', '2026-02-22 14:13:07'),
(79, 71, 1, 0, 'pcs', '2026-02-22 14:14:10'),
(80, 72, 250, 0, 'grams', '2026-02-22 14:15:00'),
(81, 73, 250, 0, 'grams', '2026-02-22 14:15:18'),
(82, 74, 680, 0, 'pcs', '2026-02-22 14:16:22'),
(83, 75, 100, 0, 'grams', '2026-02-22 14:20:24'),
(84, 76, 2000, 0, 'grams', '2026-02-22 14:25:37'),
(85, 77, 750, 0, 'grams', '2026-02-28 06:23:05'),
(86, 78, 225, 0, 'grams', '2026-02-22 14:27:48'),
(87, 79, 1000, 0, 'grams', '2026-02-22 14:29:55'),
(88, 80, 1000, 0, 'grams', '2026-02-22 14:35:14'),
(89, 81, 750, 0, 'grams', '2026-02-22 14:40:49'),
(90, 82, 750, 0, 'grams', '2026-02-22 14:41:30'),
(91, 83, 1000, 0, 'grams', '2026-02-22 14:42:19'),
(92, 84, 1000, 0, 'grams', '2026-02-22 14:44:04'),
(93, 85, 1000, 0, 'grams', '2026-02-22 14:44:46'),
(94, 86, 750, 0, 'grams', '2026-02-22 14:45:10'),
(95, 87, 750, 0, 'grams', '2026-02-22 14:45:46'),
(96, 88, 1030, 0, 'grams', '2026-02-22 14:47:36'),
(97, 89, 1000, 0, 'pcs', '2026-02-22 14:53:11'),
(99, 91, 1, 0, 'pcs', '2026-02-22 15:01:02'),
(100, 92, 1, 0, 'pcs', '2026-02-22 15:02:46'),
(101, 93, 1, 0, 'pcs', '2026-02-22 15:03:23'),
(102, 94, 1, 0, 'pcs', '2026-02-22 15:05:00'),
(103, 95, 1, 0, 'pcs', '2026-02-22 15:06:10'),
(104, 96, 1, 0, 'pcs', '2026-02-22 15:07:59'),
(105, 97, 1, 0, 'pcs', '2026-02-22 15:10:10'),
(106, 98, 1, 0, 'pcs', '2026-02-22 15:10:52'),
(107, 99, 100, 0, 'pcs', '2026-02-22 15:11:35'),
(108, 100, 5, 0, 'pcs', '2026-02-22 15:21:43'),
(109, 101, 5, 0, 'pcs', '2026-02-22 23:25:41'),
(112, 104, 20, 0, 'pcs', '2026-02-22 15:49:47'),
(113, 105, 20, 0, 'pcs', '2026-02-22 15:51:28'),
(114, 106, 10, 0, 'pcs', '2026-02-22 15:53:33'),
(115, 107, 2000, 0, 'pcs', '2026-02-22 15:58:48'),
(116, 108, 100, 0, 'pcs', '2026-02-22 15:59:26'),
(117, 109, 240, 0, 'pcs', '2026-02-23 03:45:15'),
(118, 110, 500, 0, 'pcs', '2026-02-23 03:45:42'),
(119, 111, 500, 0, 'pcs', '2026-02-23 03:46:13'),
(120, 112, 650, 0, 'pcs', '2026-02-23 03:48:00'),
(121, 113, 650, 0, 'pcs', '2026-02-23 03:49:33'),
(122, 114, 100, 0, 'pcs', '2026-02-23 03:51:28'),
(123, 115, 100, 0, 'pcs', '2026-02-23 03:51:59'),
(124, 116, 100, 0, 'pcs', '2026-02-23 03:53:29'),
(125, 117, 100, 0, 'pcs', '2026-02-23 03:53:53'),
(126, 118, 500, 0, 'pcs', '2026-02-23 03:55:04'),
(127, 119, 500, 0, 'pcs', '2026-02-23 03:55:51'),
(128, 120, 500, 0, 'pcs', '2026-02-23 03:56:29'),
(129, 121, 500, 0, 'pcs', '2026-02-23 03:58:01'),
(130, 122, 100, 0, 'pcs', '2026-02-23 03:58:30'),
(131, 123, 31, 0, 'pcs', '2026-02-23 03:59:40'),
(132, 124, 36, 0, 'pcs', '2026-02-23 05:04:52'),
(133, 125, 12, 0, 'pcs', '2026-02-23 05:05:39'),
(134, 126, 0, 0, 'grams', '2026-02-23 10:15:40'),
(135, 127, 0, 0, 'grams', '2026-02-23 10:15:40'),
(136, 128, 1, 0, 'pcs', '2026-02-23 08:30:49'),
(137, 129, 1, 0, 'pcs', '2026-02-24 10:22:57');

-- --------------------------------------------------------

--
-- Table structure for table `remittance_denominations`
--

CREATE TABLE `remittance_denominations` (
  `denomination_id` int(11) NOT NULL,
  `remittance_id` int(11) NOT NULL,
  `denomination` decimal(6,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `cash_on_hand` decimal(10,2) GENERATED ALWAYS AS (`denomination` * `quantity`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `remittance_details`
--

CREATE TABLE `remittance_details` (
  `remittance_id` int(11) NOT NULL,
  `cashier` int(11) DEFAULT NULL,
  `outlet_name` varchar(100) NOT NULL,
  `remittance_date` datetime NOT NULL,
  `shift_start` time NOT NULL,
  `shift_end` time NOT NULL,
  `amount_enclosed` double NOT NULL,
  `total_online_revenue` double NOT NULL,
  `cash_out` double NOT NULL,
  `cashout_reason` varchar(255) NOT NULL,
  `bakery_sales` double NOT NULL,
  `coffee_sales` double NOT NULL,
  `grocery_sales` double NOT NULL,
  `total_sales` double NOT NULL,
  `variance_amount` double NOT NULL,
  `is_short` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `remittance_items`
--

CREATE TABLE `remittance_items` (
  `remit_item_id` int(11) NOT NULL,
  `remittance_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `sale_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `quantity_sold` int(11) NOT NULL,
  `total_sales` double NOT NULL,
  `date_created` date NOT NULL,
  `time_created` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`sale_id`, `item_id`, `order_id`, `quantity_sold`, `total_sales`, `date_created`, `time_created`) VALUES
(3, 21, 3, 10, 1758.8, '2026-02-23', '18:18:34'),
(4, 21, 4, 1, 175.88, '2026-02-23', '18:21:12'),
(5, 20, 4, 2, 24, '2026-02-23', '18:21:12'),
(6, 24, 5, 4, 40, '2026-02-25', '10:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `employee_type` enum('owner','staff','admin') NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `birthdate` date NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `firstname`, `middlename`, `lastname`, `employee_type`, `username`, `password`, `gender`, `birthdate`, `phone_number`, `approved`, `created_at`) VALUES
(1, 'junaag@my.cspc.edu.ph', 'Julius', 'Abragan', 'Naag', 'owner', 'Julius_admin', '$2y$10$4V6vwhEMjhg/3DAp7cntjeQStaejcczT8I0w53xbOPStAUFo2BkfC', 'male', '2003-04-16', '09388702935', 1, '2026-02-27 13:48:22'),
(2, 'saaraonoya019@gmail.com', 'Saara', 'Asug', 'Onoya', 'owner', 'saaraonoya', '$2y$10$sRHICADrEzFU6rzcAoMG0.ppATsKE9rvjsQQJhWgftaYRHYz6cdV6', 'female', '2002-11-05', '09949408568', 1, '2026-02-22 05:48:50'),
(3, 'rdrew402@gmail.com', 'Jan Andrew', '', 'Barte', 'owner', 'TestAccount', '$2y$10$golTf6Heh55jND87ZgX11euJm3R3eyX2/YPCV..ifLPkdLM2O.T7y', 'male', '2026-02-04', '0987654321', 1, '2026-02-27 13:50:26'),
(4, 'naag.juliuscaesar@gmail.com', 'Julius Caesar', 'Abragan', 'Naag', 'staff', 'Julius_Staff', '$2y$10$quJG9NCsZ3Z55Oq1Hgo/seLlognSgq78DZm1gOa/gzfoYPrH6rIZK', 'male', '2003-04-16', '09388702935', 1, '2026-02-27 13:49:04');

-- --------------------------------------------------------

--
-- Table structure for table `utility_expenses`
--

CREATE TABLE `utility_expenses` (
  `u_id` int(11) NOT NULL,
  `type` enum('water','electricity','labor','gas','internet') NOT NULL,
  `billing_period` enum('hourly','daily','weekly','monthly','annually') NOT NULL,
  `quantity` double NOT NULL,
  `unit` varchar(25) NOT NULL,
  `expense` double NOT NULL,
  `days` int(11) NOT NULL,
  `cost_per_unit` double NOT NULL,
  `cost_per_day` double NOT NULL,
  `created_at` datetime NOT NULL,
  `billed_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utility_expenses`
--

INSERT INTO `utility_expenses` (`u_id`, `type`, `billing_period`, `quantity`, `unit`, `expense`, `days`, `cost_per_unit`, `cost_per_day`, `created_at`, `billed_at`) VALUES
(1, 'water', 'monthly', 0, '', 550, 0, 0, 0, '2026-02-22 14:35:45', '2026-02-23 00:00:00'),
(3, 'labor', 'monthly', 0, '', 50100, 0, 0, 0, '2026-02-23 17:13:11', '2026-02-23 00:00:00'),
(4, 'gas', 'monthly', 0, '', 1948, 0, 0, 0, '2026-02-23 17:14:00', '2026-02-23 00:00:00'),
(5, 'electricity', 'monthly', 0, '', 2000, 0, 0, 0, '2026-02-23 17:14:43', '2026-02-23 00:00:00'),
(6, '', 'monthly', 0, '', 15000, 0, 0, 0, '2026-02-23 17:15:35', '2026-02-23 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daily_stock`
--
ALTER TABLE `daily_stock`
  ADD PRIMARY KEY (`daily_stock_id`);

--
-- Indexes for table `daily_stock_items`
--
ALTER TABLE `daily_stock_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `daily_stock_id` (`daily_stock_id`),
  ADD KEY `product_id_idx` (`product_id`);

--
-- Indexes for table `distributions`
--
ALTER TABLE `distributions`
  ADD PRIMARY KEY (`distribution_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `material_category`
--
ALTER TABLE `material_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `material_delivery`
--
ALTER TABLE `material_delivery`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_combined_recipes`
--
ALTER TABLE `product_combined_recipes`
  ADD PRIMARY KEY (`combined_recipe_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `source_product_id` (`source_product_id`);

--
-- Indexes for table `product_costs`
--
ALTER TABLE `product_costs`
  ADD PRIMARY KEY (`product_cost_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_recipe`
--
ALTER TABLE `product_recipe`
  ADD PRIMARY KEY (`recipe_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `raw_materials`
--
ALTER TABLE `raw_materials`
  ADD PRIMARY KEY (`material_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `raw_material_cost`
--
ALTER TABLE `raw_material_cost`
  ADD PRIMARY KEY (`cost_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `raw_material_stock`
--
ALTER TABLE `raw_material_stock`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `raw_material_stock_ibfk_1` (`material_id`);

--
-- Indexes for table `remittance_denominations`
--
ALTER TABLE `remittance_denominations`
  ADD PRIMARY KEY (`denomination_id`),
  ADD KEY `remittance_id` (`remittance_id`);

--
-- Indexes for table `remittance_details`
--
ALTER TABLE `remittance_details`
  ADD PRIMARY KEY (`remittance_id`),
  ADD KEY `remittance_details_ibfk_1` (`cashier`);

--
-- Indexes for table `remittance_items`
--
ALTER TABLE `remittance_items`
  ADD PRIMARY KEY (`remit_item_id`),
  ADD KEY `remittance_id` (`remittance_id`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `daily_sales_ibfk1_idx` (`item_id`),
  ADD KEY `transactions_order_fk` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `utility_expenses`
--
ALTER TABLE `utility_expenses`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daily_stock`
--
ALTER TABLE `daily_stock`
  MODIFY `daily_stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `daily_stock_items`
--
ALTER TABLE `daily_stock_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `distributions`
--
ALTER TABLE `distributions`
  MODIFY `distribution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `material_category`
--
ALTER TABLE `material_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `material_delivery`
--
ALTER TABLE `material_delivery`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `product_combined_recipes`
--
ALTER TABLE `product_combined_recipes`
  MODIFY `combined_recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_costs`
--
ALTER TABLE `product_costs`
  MODIFY `product_cost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `product_recipe`
--
ALTER TABLE `product_recipe`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=238;

--
-- AUTO_INCREMENT for table `raw_materials`
--
ALTER TABLE `raw_materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `raw_material_cost`
--
ALTER TABLE `raw_material_cost`
  MODIFY `cost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `raw_material_stock`
--
ALTER TABLE `raw_material_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `remittance_denominations`
--
ALTER TABLE `remittance_denominations`
  MODIFY `denomination_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `remittance_details`
--
ALTER TABLE `remittance_details`
  MODIFY `remittance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `remittance_items`
--
ALTER TABLE `remittance_items`
  MODIFY `remit_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `utility_expenses`
--
ALTER TABLE `utility_expenses`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `daily_stock_items`
--
ALTER TABLE `daily_stock_items`
  ADD CONSTRAINT `daily_stock_items_ibfk_1` FOREIGN KEY (`daily_stock_id`) REFERENCES `daily_stock` (`daily_stock_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `distributions`
--
ALTER TABLE `distributions`
  ADD CONSTRAINT `distributions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `material_delivery`
--
ALTER TABLE `material_delivery`
  ADD CONSTRAINT `material_delivery_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `raw_materials` (`material_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_combined_recipes`
--
ALTER TABLE `product_combined_recipes`
  ADD CONSTRAINT `pcr_product_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pcr_source_product_fk` FOREIGN KEY (`source_product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_costs`
--
ALTER TABLE `product_costs`
  ADD CONSTRAINT `product_costs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_recipe`
--
ALTER TABLE `product_recipe`
  ADD CONSTRAINT `product_recipe_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_recipe_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `raw_materials` (`material_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `raw_materials`
--
ALTER TABLE `raw_materials`
  ADD CONSTRAINT `raw_materials_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `material_category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `raw_material_cost`
--
ALTER TABLE `raw_material_cost`
  ADD CONSTRAINT `raw_material_cost_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `raw_materials` (`material_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `raw_material_stock`
--
ALTER TABLE `raw_material_stock`
  ADD CONSTRAINT `raw_material_stock_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `raw_materials` (`material_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `remittance_denominations`
--
ALTER TABLE `remittance_denominations`
  ADD CONSTRAINT `remittance_denominations_ibfk_1` FOREIGN KEY (`remittance_id`) REFERENCES `remittance_details` (`remittance_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `remittance_details`
--
ALTER TABLE `remittance_details`
  ADD CONSTRAINT `remittance_details_ibfk_1` FOREIGN KEY (`cashier`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `remittance_items`
--
ALTER TABLE `remittance_items`
  ADD CONSTRAINT `remittance_items_ibfk_1` FOREIGN KEY (`remittance_id`) REFERENCES `remittance_details` (`remittance_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `remittance_items_ibfk_2` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`sale_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `daily_sales_ibfk1` FOREIGN KEY (`item_id`) REFERENCES `daily_stock_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_order_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
