-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2026 at 01:21 PM
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
-- Database: `engbakery`
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

-- --------------------------------------------------------

--
-- Table structure for table `distributions`
--

CREATE TABLE `distributions` (
  `distribution_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_qnty` int(11) NOT NULL,
  `distribution_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Coffee Expenses', '', 'drinks', '2026-02-14 12:55:00'),
(2, 'Packaging', '', 'general', '2026-02-14 12:55:33'),
(3, 'Raw Materials - Bread', '', 'bread', '2026-02-14 17:43:03'),
(4, 'Raw Materials', '', 'general', '2026-02-15 03:41:18');

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
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category`, `product_name`, `product_description`, `is_disabled`, `date_created`) VALUES
(1, 'drinks', 'Café Americano', '', 0, '2026-02-15 03:54:42'),
(2, 'drinks', 'Café Latte', '', 0, '2026-02-15 03:57:26'),
(3, 'drinks', 'Spanish Latte', '', 1, '2026-02-15 04:58:17'),
(4, 'drinks', 'Caramel Macchiato', '', 0, '2026-02-15 04:24:50'),
(5, 'drinks', 'Iced Americano', '', 0, '2026-02-15 04:09:50'),
(6, 'dough', 'Soft Dough', '', 0, '2026-02-15 04:27:38'),
(7, 'bakery', 'test1', '', 0, '2026-02-15 04:47:58'),
(8, 'grocery', '200 ml Bottled Water', '', 0, '2026-02-15 05:04:02');

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
(1, 1, 34.203636363636, 20, 6.8407272727273, 0, 40, 16.417745454545, 41.044363636364, 70, 0, 0, 0, 0, 0, 0, 0, '2026-02-15 03:54:42'),
(2, 2, 54.544545454545, 20, 10.908909090909, 0, 40, 26.181381818182, 65.453454545455, 110, 0, 0, 0, 0, 0, 0, 0, '2026-02-15 03:57:26'),
(3, 3, 56.378461538462, 20, 11.275692307692, 0, 40, 27.061661538462, 67.654153846154, 115, 0, 0, 0, 0, 0, 0, 0, '2026-02-15 03:59:13'),
(4, 4, 64.84, 20, 12.968, 0, 40, 31.1232, 77.808, 130, 0, 0, 0, 0, 0, 0, 0, '2026-02-15 04:01:00'),
(5, 5, 41.299636363636, 20, 8.2599272727273, 0, 40, 19.823825454545, 49.559563636364, 85, 0, 0, 0, 0, 0, 0, 0, '2026-02-15 04:09:50'),
(6, 6, 70.626, 0, 0, 0, 0, 0, 70.626, 71, 0, 0.04, 1838, 0, 1838, 0, 1, '2026-02-15 04:27:38'),
(7, 7, 24.42, 20, 4.884, 0, 30, 8.7912, 29.304, 42, 0, 5, 11, 0, 15, 0, 0.73, '2026-02-15 04:47:58'),
(8, 8, 14, 0, 0, 0, 40, 5.6, 14, 25, 0, 0, 0, 0, 0, 0, 0, '2026-02-15 05:04:02');

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
(1, 1, 191, 260, 'ml', '2026-02-15 03:54:42'),
(2, 1, 1, 18, 'grams', '2026-02-15 03:54:42'),
(3, 1, 2, 20, 'ml', '2026-02-15 03:54:42'),
(4, 1, 15, 1, 'pcs', '2026-02-15 03:54:42'),
(5, 1, 20, 1, 'pcs', '2026-02-15 03:54:42'),
(6, 1, 14, 3, 'pcs', '2026-02-15 03:54:42'),
(7, 2, 1, 18, 'grams', '2026-02-15 03:57:26'),
(8, 2, 3, 250, 'ml', '2026-02-15 03:57:26'),
(9, 2, 2, 25, 'ml', '2026-02-15 03:57:26'),
(10, 2, 15, 1, 'pcs', '2026-02-15 03:57:26'),
(11, 2, 20, 1, 'pcs', '2026-02-15 03:57:26'),
(12, 2, 14, 3, 'pcs', '2026-02-15 03:57:26'),
(13, 3, 1, 18, 'grams', '2026-02-15 03:59:13'),
(14, 3, 3, 250, 'ml', '2026-02-15 03:59:13'),
(15, 3, 4, 30, 'ml', '2026-02-15 03:59:13'),
(16, 3, 15, 1, 'pcs', '2026-02-15 03:59:13'),
(17, 3, 20, 1, 'pcs', '2026-02-15 03:59:13'),
(18, 3, 14, 3, 'pcs', '2026-02-15 03:59:13'),
(19, 4, 1, 18, 'grams', '2026-02-15 04:01:00'),
(20, 4, 3, 250, 'ml', '2026-02-15 04:01:00'),
(21, 4, 5, 20, 'ml', '2026-02-15 04:01:00'),
(22, 4, 6, 10, 'ml', '2026-02-15 04:01:00'),
(23, 4, 15, 1, 'pcs', '2026-02-15 04:01:00'),
(24, 4, 20, 1, 'pcs', '2026-02-15 04:01:00'),
(25, 4, 14, 3, 'pcs', '2026-02-15 04:01:00'),
(26, 5, 192, 150, 'ml', '2026-02-15 04:09:50'),
(27, 5, 1, 18, 'grams', '2026-02-15 04:09:50'),
(28, 5, 2, 20, 'ml', '2026-02-15 04:09:50'),
(29, 5, 19, 1, 'pcs', '2026-02-15 04:09:50'),
(30, 5, 14, 3, 'pcs', '2026-02-15 04:09:50'),
(31, 5, 21, 692, 'grams', '2026-02-15 04:09:50'),
(32, 5, 16, 1, 'pcs', '2026-02-15 04:09:50'),
(33, 6, 267, 1000, 'grams', '2026-02-15 04:27:38'),
(34, 6, 268, 200, 'grams', '2026-02-15 04:27:38'),
(35, 6, 279, 15, 'grams', '2026-02-15 04:27:38'),
(36, 6, 305, 10, 'grams', '2026-02-15 04:27:38'),
(37, 6, 309, 3, 'grams', '2026-02-15 04:27:38'),
(38, 6, 297, 60, 'grams', '2026-02-15 04:27:38'),
(39, 6, 281, 20, 'grams', '2026-02-15 04:27:38'),
(40, 6, 285, 100, 'grams', '2026-02-15 04:27:38'),
(41, 6, 192, 430, 'ml', '2026-02-15 04:27:38'),
(42, 7, 339, 11, 'grams', '2026-02-15 04:47:58');

-- --------------------------------------------------------

--
-- Table structure for table `raw_materials`
--

CREATE TABLE `raw_materials` (
  `material_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `material_quantity` int(11) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raw_materials`
--

INSERT INTO `raw_materials` (`material_id`, `category_id`, `material_name`, `material_quantity`, `unit`, `date_created`) VALUES
(1, 1, 'Coffee Beans', 1000, 'grams', '2026-02-14 12:59:52'),
(2, 1, 'Sugar Syrup', 220, 'grams', '2026-02-14 13:02:14'),
(3, 1, 'Fresh Milk', 1000, 'grams', '2026-02-14 13:02:48'),
(4, 1, 'Condensed Milk', 390, 'grams', '2026-02-14 13:03:27'),
(5, 1, 'Caramel Syrup', 750, 'grams', '2026-02-14 13:03:45'),
(6, 1, 'French Vanilla Syrup', 750, 'grams', '2026-02-14 13:04:26'),
(7, 1, 'Matcha Powder - Coffee', 100, 'grams', '2026-02-14 13:04:57'),
(8, 1, 'Matcha Powder - Frappe', 1000, 'grams', '2026-02-14 13:05:46'),
(9, 1, 'Vanilla Powder', 1000, 'grams', '2026-02-14 13:59:20'),
(10, 1, 'Choco Powder', 1000, 'grams', '2026-02-14 13:59:54'),
(11, 1, 'Strawberry Syrup', 750, 'grams', '2026-02-14 17:32:31'),
(12, 1, 'Choco Syrup', 750, 'grams', '2026-02-14 17:35:28'),
(13, 1, 'Whipping Cream - Ever Whip', 1030, 'grams', '2026-02-14 17:35:55'),
(14, 1, 'Tissue', 1000, 'pcs', '2026-02-15 03:51:45'),
(15, 1, '12oz Cup with Lid', 100, 'pcs', '2026-02-15 03:44:12'),
(16, 1, '16oz Cup with Lid - Coffee', 100, 'pcs', '2026-02-15 03:44:03'),
(17, 1, '16 oz Cup with Lid - Frappe', 100, 'pcs', '2026-02-15 03:43:53'),
(18, 1, 'Straw Coffee', 100, 'pcs', '2026-02-15 03:49:04'),
(19, 1, 'Straw Frappe', 100, 'pcs', '2026-02-15 03:49:20'),
(20, 1, 'Stirrer', 100, 'pcs', '2026-02-15 03:45:14'),
(21, 1, 'Ice', 2000, 'grams', '2026-02-14 17:40:31'),
(168, 2, 'Baking Cups - 2oz', 1200, 'pcs', '2026-02-15 03:23:59'),
(169, 2, 'Boxes - 10x10x4 in', 20, 'pcs', '2026-02-15 03:23:53'),
(170, 2, 'Boxes - 9x9x2 in', 20, 'grams', '2026-02-15 03:24:18'),
(171, 2, 'Paper Plate', 35, 'pcs', '2026-02-15 03:25:12'),
(172, 2, 'Brown Paper', 10, 'pcs', '2026-02-15 03:25:07'),
(173, 2, 'Cello Sheet - 9x13 in', 2000, 'pcs', '2026-02-15 03:29:29'),
(174, 2, 'Gloves', 100, 'pcs', '2026-02-15 03:29:24'),
(175, 2, 'Plastic Bag - Medium', 240, 'pcs', '2026-02-15 03:29:20'),
(176, 2, 'Plastic Bag - Mini', 500, 'pcs', '2026-02-15 03:29:52'),
(177, 2, 'Plastic Bag - Tiny', 500, 'pcs', '2026-02-15 03:30:33'),
(178, 2, 'Plastic Labo - 8x11 in', 650, 'pcs', '2026-02-15 03:31:02'),
(179, 2, 'Plastic Labo - 10x14 in', 650, 'pcs', '2026-02-15 03:33:00'),
(180, 2, 'Supot No. 5', 100, 'pcs', '2026-02-15 03:33:17'),
(181, 2, 'Supot No. 3', 100, 'pcs', '2026-02-15 03:33:37'),
(182, 2, 'Custard Plastic - 5.5 cm', 500, 'pcs', '2026-02-15 03:33:53'),
(183, 2, 'Pastry Pouch - 11x13 cm', 500, 'pcs', '2026-02-15 03:34:22'),
(184, 2, 'Pastry Pouch - 10x13.5 cm', 500, 'pcs', '2026-02-15 03:34:40'),
(185, 2, 'Sliced Bread Clear - 30x34+10 cm', 500, 'pcs', '2026-02-15 03:34:56'),
(186, 2, 'Sliced Bread Opaque', 100, 'pcs', '2026-02-15 03:35:16'),
(187, 2, 'Ribbon Size 2 - 50m (1.6 per box)', 31, 'pcs', '2026-02-15 03:45:05'),
(188, 2, 'Supot No. 1 ', 100, 'pcs', '2026-02-15 03:36:30'),
(189, 2, 'Supot No. 2', 100, 'pcs', '2026-02-15 03:36:45'),
(190, 2, 'Baking Cups - 3 oz', 100, 'pcs', '2026-02-15 03:36:58'),
(191, 4, 'Hot Water', 1, 'ml', '2026-02-15 03:42:04'),
(192, 4, 'Water', 1, 'ml', '2026-02-15 04:03:02'),
(266, 3, 'Flour - All Purpose', 25000, 'grams', '2026-02-15 04:24:19'),
(267, 3, 'Flour - Kutitap First Class', 25000, 'grams', '2026-02-15 04:24:19'),
(268, 3, 'Sugar 99', 50000, 'grams', '2026-02-15 04:24:19'),
(269, 3, 'Sugar Brown', 50000, 'grams', '2026-02-15 04:24:19'),
(270, 3, 'Sugar White', 50000, 'grams', '2026-02-15 04:24:19'),
(271, 3, 'Sugar Powdered - Penco', 2272, 'grams', '2026-02-15 04:24:19'),
(272, 3, 'Baking Powder - Ordinary', 1000, 'grams', '2026-02-15 04:24:19'),
(273, 3, 'Baking Powder - Calumet', 1000, 'grams', '2026-02-15 04:24:19'),
(274, 3, 'Cream of Tartar', 50, 'grams', '2026-02-15 04:24:19'),
(275, 3, 'JB-15', 500, 'grams', '2026-02-15 04:24:19'),
(276, 3, 'Cocoa Ordinary', 25000, 'grams', '2026-02-15 04:24:19'),
(277, 3, 'Buttermilk', 25000, 'grams', '2026-02-15 04:24:19'),
(278, 3, 'Vanilla', 4000, 'grams', '2026-02-15 04:24:19'),
(279, 3, 'Salt', 25000, 'grams', '2026-02-15 04:24:19'),
(280, 3, 'Canola Oil', 1000, 'grams', '2026-02-15 04:24:19'),
(281, 3, 'Buttercup (per piece)', 200, 'grams', '2026-02-15 04:24:19'),
(282, 3, 'Cheese - Magnolia (per piece)', 165, 'grams', '2026-02-15 04:24:19'),
(283, 3, 'Evaporated Milk - Evaporada (per can)', 360, 'grams', '2026-02-15 04:24:19'),
(284, 3, 'Condensed Milk - Condensada (per can)', 390, 'grams', '2026-02-15 04:24:19'),
(285, 3, 'Fresh Milk - (per box)', 1000, 'grams', '2026-02-15 04:24:19'),
(286, 3, 'Cornstarch - Farola', 25000, 'grams', '2026-02-15 04:24:19'),
(287, 3, 'Eggs (1 tray)', 30, 'pcs', '2026-02-15 04:24:19'),
(288, 3, 'Food Color - Strawberry Red', 454, 'grams', '2026-02-15 04:24:19'),
(289, 3, 'Food Color - Ube', 454, 'grams', '2026-02-15 04:24:19'),
(290, 3, 'Food Color - Egg Yellow', 454, 'grams', '2026-02-15 04:24:19'),
(291, 3, 'Baking Soda', 250, 'grams', '2026-02-15 04:24:19'),
(292, 3, 'Cake Flour', 25000, 'grams', '2026-02-15 04:24:19'),
(293, 3, 'Coffee', 100, 'grams', '2026-02-15 04:24:19'),
(294, 3, 'Palm Oil - Cooking Ordinary', 1000, 'grams', '2026-02-15 04:24:19'),
(295, 3, 'Desicated Cocounut', 1000, 'grams', '2026-02-15 04:24:19'),
(296, 3, 'Emulsifier - Puratos Mixo', 250, 'grams', '2026-02-15 04:24:19'),
(297, 3, 'Lard - Approved', 36000, 'grams', '2026-02-15 04:24:19'),
(298, 3, 'Macco - Puratos Preservative Bread', 1000, 'grams', '2026-02-15 04:24:19'),
(299, 3, 'Margarine - Baker\'s Choice', 36000, 'grams', '2026-02-15 04:24:19'),
(300, 3, 'Ube Paste', 1000, 'grams', '2026-02-15 04:24:19'),
(301, 3, 'Monggo Paste', 1000, 'grams', '2026-02-15 04:24:19'),
(302, 3, 'Onion', 1000, 'grams', '2026-02-15 04:24:19'),
(303, 3, 'Peanut - Skinless', 1000, 'grams', '2026-02-15 04:24:19'),
(304, 3, 'Niyog', 1000, 'grams', '2026-02-15 04:24:19'),
(305, 3, 'Yeast - Angel Instant', 500, 'grams', '2026-02-15 04:24:19'),
(306, 3, 'Raisin', 1000, 'grams', '2026-02-15 04:24:19'),
(307, 3, 'Yeast - Red Star Active Dry', 800, 'grams', '2026-02-15 04:24:19'),
(308, 3, 'Flour - Mayon Third Class', 25000, 'grams', '2026-02-15 04:24:19'),
(309, 3, 'Improver - Toupan', 1000, 'grams', '2026-02-15 04:24:19'),
(310, 3, 'Potassium Sorbate - Preservative Cake', 1000, 'grams', '2026-02-15 04:24:19'),
(311, 3, 'Carbonato', 250, 'grams', '2026-02-15 04:24:19'),
(312, 3, 'Mayonnaise - Kewpie', 1000, 'grams', '2026-02-15 04:24:19'),
(313, 3, 'Mayonnaise - All Purpose Dressing', 470, 'grams', '2026-02-15 04:24:19'),
(314, 3, 'Tuna - Century (per can)', 140, 'grams', '2026-02-15 04:24:19'),
(315, 3, 'Cinnamon Powder - HYCO (per can)', 500, 'grams', '2026-02-15 04:24:19'),
(316, 3, 'Creamcheese - Anchor', 1000, 'grams', '2026-02-15 04:24:19'),
(317, 3, 'Rolled Oats', 500, 'grams', '2026-02-15 04:24:19'),
(318, 3, 'All Purpose Cream', 250, 'grams', '2026-02-15 04:24:19'),
(319, 3, 'Cheddar Cheese - Magnolia (per piece)', 165, 'grams', '2026-02-15 04:24:19'),
(320, 3, 'Hotdog', 10, 'pcs', '2026-02-15 04:24:19'),
(321, 3, 'Nutmeg - McCormick', 30, 'grams', '2026-02-15 04:24:19'),
(322, 3, 'Chocolate Bar - Fuji', 1000, 'grams', '2026-02-15 04:24:19'),
(323, 3, 'Whip It - Whipping Cream', 500, 'grams', '2026-02-15 04:24:19'),
(324, 3, 'Sweet Ham - CDO Regular', 8, 'pcs', '2026-02-15 04:24:19'),
(325, 3, 'Butter - Magnolia (per piece)', 220, 'grams', '2026-02-15 04:24:19'),
(326, 3, 'Condensed Milk - Ube', 390, 'grams', '2026-02-15 04:24:19'),
(327, 3, 'Chocolate Drops', 1000, 'grams', '2026-02-15 04:24:19'),
(328, 3, 'Chocolate Drops - White', 1000, 'grams', '2026-02-15 04:24:19'),
(329, 3, 'Banana - Bungoran', 1000, 'grams', '2026-02-15 04:24:19'),
(330, 3, 'Pineapple', 1, 'pcs', '2026-02-15 04:24:19'),
(331, 3, 'Walnuts', 250, 'grams', '2026-02-15 04:24:19'),
(332, 3, 'Almond', 250, 'grams', '2026-02-15 04:24:19'),
(333, 3, 'Marshmallow', 680, 'pcs', '2026-02-15 04:24:19'),
(334, 3, 'Matcha Powder', 100, 'grams', '2026-02-15 04:24:19'),
(335, 3, 'Ice', 2000, 'grams', '2026-02-15 04:24:19'),
(336, 3, 'Glucose', 750, 'grams', '2026-02-15 04:24:19'),
(337, 3, 'Butter - Queensland', 225, 'grams', '2026-02-15 04:24:19'),
(338, 3, 'Wallnuts', 250, 'grams', '2026-02-15 04:24:19'),
(339, 4, 'test1', 100, 'grams', '2026-02-15 04:46:02');

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
(1, 1, 1.25, '2026-02-14 12:59:52'),
(2, 2, 0.068181818181818, '2026-02-14 13:02:14'),
(3, 3, 0.08, '2026-02-14 13:02:48'),
(4, 4, 0.11794871794872, '2026-02-14 13:03:27'),
(5, 5, 0.4, '2026-02-14 13:03:45'),
(6, 6, 0.4, '2026-02-14 13:04:26'),
(7, 7, 1.65, '2026-02-14 13:04:57'),
(8, 8, 0.3, '2026-02-14 13:05:46'),
(9, 9, 0.3, '2026-02-14 13:59:20'),
(10, 10, 0.3, '2026-02-14 13:59:54'),
(11, 11, 0.4, '2026-02-14 17:32:31'),
(12, 12, 0.4, '2026-02-14 17:35:28'),
(13, 13, 0.31553398058252, '2026-02-14 17:35:55'),
(14, 14, 0.08, '2026-02-14 17:36:17'),
(15, 15, 9.6, '2026-02-14 17:38:26'),
(16, 16, 7.7, '2026-02-14 17:38:54'),
(17, 17, 8, '2026-02-14 17:39:11'),
(18, 18, 0.5, '2026-02-14 17:39:51'),
(19, 19, 0.5, '2026-02-14 17:40:06'),
(20, 20, 0.5, '2026-02-14 17:40:19'),
(21, 21, 0.013, '2026-02-14 17:40:31'),
(277, 168, 0.065, '2026-02-15 03:21:16'),
(278, 169, 29.9, '2026-02-15 03:23:53'),
(279, 170, 19.4, '2026-02-15 03:24:18'),
(280, 171, 0.91428571428571, '2026-02-15 03:24:36'),
(281, 172, 3.2, '2026-02-15 03:25:07'),
(282, 173, 0.615, '2026-02-15 03:27:57'),
(283, 174, 0.35, '2026-02-15 03:28:11'),
(284, 175, 0.83333333333333, '2026-02-15 03:29:20'),
(285, 176, 0.154, '2026-02-15 03:29:52'),
(286, 177, 0.188, '2026-02-15 03:30:33'),
(287, 178, 0.1, '2026-02-15 03:31:02'),
(288, 179, 0.16923076923077, '2026-02-15 03:33:00'),
(289, 180, 0.65, '2026-02-15 03:33:17'),
(290, 181, 0.45, '2026-02-15 03:33:37'),
(291, 182, 1.306, '2026-02-15 03:33:53'),
(292, 183, 1.216, '2026-02-15 03:34:22'),
(293, 184, 0.896, '2026-02-15 03:34:40'),
(294, 185, 1.966, '2026-02-15 03:34:56'),
(295, 186, 0.95, '2026-02-15 03:35:16'),
(296, 187, 2.4193548387097, '2026-02-15 03:36:16'),
(297, 188, 0.35, '2026-02-15 03:36:30'),
(298, 189, 0.4, '2026-02-15 03:36:45'),
(299, 190, 0.7, '2026-02-15 03:36:58'),
(300, 191, 0, '2026-02-15 03:42:04'),
(301, 192, 0, '2026-02-15 04:02:52'),
(302, 266, 0.054, '2026-02-15 04:24:19'),
(303, 267, 0.036, '2026-02-15 04:24:19'),
(304, 268, 0.062, '2026-02-15 04:24:19'),
(305, 269, 0.06, '2026-02-15 04:24:19'),
(306, 270, 0.072, '2026-02-15 04:24:19'),
(307, 271, 0.114, '2026-02-15 04:24:19'),
(308, 272, 0.084, '2026-02-15 04:24:19'),
(309, 273, 0.205, '2026-02-15 04:24:19'),
(310, 274, 0.64, '2026-02-15 04:24:19'),
(311, 275, 0.73, '2026-02-15 04:24:19'),
(312, 276, 0.18, '2026-02-15 04:24:19'),
(313, 277, 0.16, '2026-02-15 04:24:19'),
(314, 278, 0.04, '2026-02-15 04:24:19'),
(315, 279, 0.014, '2026-02-15 04:24:19'),
(316, 280, 0.16, '2026-02-15 04:24:19'),
(317, 281, 0.23, '2026-02-15 04:24:19'),
(318, 282, 0.273, '2026-02-15 04:24:19'),
(319, 283, 0.1, '2026-02-15 04:24:19'),
(320, 284, 0.115, '2026-02-15 04:24:19'),
(321, 285, 0.09, '2026-02-15 04:24:19'),
(322, 286, 0.043, '2026-02-15 04:24:19'),
(323, 287, 8.833, '2026-02-15 04:24:19'),
(324, 288, 0.187, '2026-02-15 04:24:19'),
(325, 289, 0.187, '2026-02-15 04:24:19'),
(326, 290, 0.187, '2026-02-15 04:24:19'),
(327, 291, 0.096, '2026-02-15 04:24:19'),
(328, 292, 0.054, '2026-02-15 04:24:19'),
(329, 293, 0.65, '2026-02-15 04:24:19'),
(330, 294, 0.086, '2026-02-15 04:24:19'),
(331, 295, 0.18, '2026-02-15 04:24:19'),
(332, 296, 0.436, '2026-02-15 04:24:19'),
(333, 297, 0.091, '2026-02-15 04:24:19'),
(334, 298, 0.32, '2026-02-15 04:24:19'),
(335, 299, 0.094, '2026-02-15 04:24:19'),
(336, 300, 0.16, '2026-02-15 04:24:19'),
(337, 301, 0.16, '2026-02-15 04:24:19'),
(338, 302, 0.2, '2026-02-15 04:24:19'),
(339, 303, 0.102, '2026-02-15 04:24:19'),
(340, 304, 0.08, '2026-02-15 04:24:19'),
(341, 305, 0.256, '2026-02-15 04:24:19'),
(342, 306, 0.42, '2026-02-15 04:24:19'),
(343, 307, 0.396, '2026-02-15 04:24:19'),
(344, 308, 0.032, '2026-02-15 04:24:19'),
(345, 309, 0.132, '2026-02-15 04:24:19'),
(346, 310, 0.458, '2026-02-15 04:24:19'),
(347, 311, 0.06, '2026-02-15 04:24:19'),
(348, 312, 0.38, '2026-02-15 04:24:19'),
(349, 313, 0.228, '2026-02-15 04:24:19'),
(350, 314, 0.329, '2026-02-15 04:24:19'),
(351, 315, 1.05, '2026-02-15 04:24:19'),
(352, 316, 0.6, '2026-02-15 04:24:19'),
(353, 317, 0.234, '2026-02-15 04:24:19'),
(354, 318, 0.284, '2026-02-15 04:24:19'),
(355, 319, 0.636, '2026-02-15 04:24:19'),
(356, 320, 6.1, '2026-02-15 04:24:19'),
(357, 321, 3.4, '2026-02-15 04:24:19'),
(358, 322, 0.305, '2026-02-15 04:24:19'),
(359, 323, 0.28, '2026-02-15 04:24:19'),
(360, 324, 15, '2026-02-15 04:24:19'),
(361, 325, 0.886, '2026-02-15 04:24:19'),
(362, 326, 0.144, '2026-02-15 04:24:19'),
(363, 327, 0.38, '2026-02-15 04:24:19'),
(364, 328, 0.28, '2026-02-15 04:24:19'),
(365, 329, 0.03, '2026-02-15 04:24:19'),
(366, 330, 35, '2026-02-15 04:24:19'),
(367, 331, 0.66, '2026-02-15 04:24:19'),
(368, 332, 0.74, '2026-02-15 04:24:19'),
(369, 333, 0.176, '2026-02-15 04:24:19'),
(370, 334, 1.65, '2026-02-15 04:24:19'),
(371, 335, 0.013, '2026-02-15 04:24:19'),
(372, 336, 0.093, '2026-02-15 04:24:19'),
(373, 337, 0.6, '2026-02-15 04:24:19'),
(374, 338, 0.716, '2026-02-15 04:24:19'),
(429, 339, 2.22, '2026-02-15 04:46:02');

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
(1, 1, 1000, 0, 'grams', '2026-02-14 12:59:52'),
(2, 2, 220, 0, 'grams', '2026-02-14 13:02:14'),
(3, 3, 1000, 0, 'grams', '2026-02-14 13:02:48'),
(4, 4, 390, 0, 'grams', '2026-02-14 13:03:27'),
(5, 5, 750, 0, 'grams', '2026-02-14 13:03:45'),
(6, 6, 750, 0, 'grams', '2026-02-14 13:04:26'),
(7, 7, 100, 0, 'grams', '2026-02-14 13:04:57'),
(8, 8, 1000, 0, 'grams', '2026-02-14 13:05:46'),
(9, 9, 1000, 0, 'grams', '2026-02-14 13:59:20'),
(10, 10, 1000, 0, 'grams', '2026-02-14 13:59:54'),
(11, 11, 750, 0, 'grams', '2026-02-14 17:32:31'),
(12, 12, 750, 0, 'grams', '2026-02-14 17:35:28'),
(13, 13, 1030, 0, 'grams', '2026-02-14 17:35:55'),
(14, 14, 1000, 0, 'pcs', '2026-02-14 17:36:17'),
(15, 15, 100, 0, 'pcs', '2026-02-14 17:38:26'),
(16, 16, 100, 0, 'pcs', '2026-02-14 17:38:54'),
(17, 17, 100, 0, 'pcs', '2026-02-14 17:39:11'),
(18, 18, 100, 0, 'pcs', '2026-02-14 17:39:51'),
(19, 19, 100, 0, 'pcs', '2026-02-14 17:40:06'),
(20, 20, 100, 0, 'pcs', '2026-02-14 17:40:19'),
(21, 21, 2000, 0, 'grams', '2026-02-14 17:40:31'),
(277, 168, 1200, 0, 'pcs', '2026-02-15 03:21:16'),
(278, 169, 20, 0, 'pcs', '2026-02-15 03:23:53'),
(279, 170, 20, 0, 'grams', '2026-02-15 03:24:18'),
(280, 171, 35, 0, 'pcs', '2026-02-15 03:24:36'),
(281, 172, 10, 0, 'pcs', '2026-02-15 03:25:07'),
(282, 173, 2000, 0, 'pcs', '2026-02-15 03:27:57'),
(283, 174, 100, 0, 'pcs', '2026-02-15 03:28:11'),
(284, 175, 240, 0, 'pcs', '2026-02-15 03:29:20'),
(285, 176, 500, 0, 'pcs', '2026-02-15 03:29:52'),
(286, 177, 500, 0, 'pcs', '2026-02-15 03:30:33'),
(287, 178, 650, 0, 'pcs', '2026-02-15 03:31:02'),
(288, 179, 650, 0, 'pcs', '2026-02-15 03:33:00'),
(289, 180, 100, 0, 'pcs', '2026-02-15 03:33:17'),
(290, 181, 100, 0, 'pcs', '2026-02-15 03:33:37'),
(291, 182, 500, 0, 'pcs', '2026-02-15 03:33:53'),
(292, 183, 500, 0, 'pcs', '2026-02-15 03:34:22'),
(293, 184, 500, 0, 'pcs', '2026-02-15 03:34:40'),
(294, 185, 500, 0, 'pcs', '2026-02-15 03:34:56'),
(295, 186, 100, 0, 'pcs', '2026-02-15 03:35:16'),
(296, 187, 31, 0, 'pcs', '2026-02-15 03:36:16'),
(297, 188, 100, 0, 'pcs', '2026-02-15 03:36:30'),
(298, 189, 100, 0, 'pcs', '2026-02-15 03:36:45'),
(299, 190, 100, 0, 'pcs', '2026-02-15 03:36:58'),
(301, 191, 1, 0, 'ml', '2026-02-15 03:42:04'),
(302, 192, 1, 0, 'ml', '2026-02-15 04:02:52'),
(303, 266, 25000, 0, 'grams', '2026-02-15 04:24:19'),
(304, 267, 25000, 0, 'grams', '2026-02-15 04:24:19'),
(305, 268, 50000, 0, 'grams', '2026-02-15 04:24:19'),
(306, 269, 50000, 0, 'grams', '2026-02-15 04:24:19'),
(307, 270, 50000, 0, 'grams', '2026-02-15 04:24:19'),
(308, 271, 2272, 0, 'grams', '2026-02-15 04:24:19'),
(309, 272, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(310, 273, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(311, 274, 50, 0, 'grams', '2026-02-15 04:24:19'),
(312, 275, 500, 0, 'grams', '2026-02-15 04:24:19'),
(313, 276, 25000, 0, 'grams', '2026-02-15 04:24:19'),
(314, 277, 25000, 0, 'grams', '2026-02-15 04:24:19'),
(315, 278, 4000, 0, 'grams', '2026-02-15 04:24:19'),
(316, 279, 25000, 0, 'grams', '2026-02-15 04:24:19'),
(317, 280, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(318, 281, 200, 0, 'grams', '2026-02-15 04:24:19'),
(319, 282, 165, 0, 'grams', '2026-02-15 04:24:19'),
(320, 283, 360, 0, 'grams', '2026-02-15 04:24:19'),
(321, 284, 390, 0, 'grams', '2026-02-15 04:24:19'),
(322, 285, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(323, 286, 25000, 0, 'grams', '2026-02-15 04:24:19'),
(324, 287, 30, 0, 'pcs', '2026-02-15 04:24:19'),
(325, 288, 454, 0, 'grams', '2026-02-15 04:24:19'),
(326, 289, 454, 0, 'grams', '2026-02-15 04:24:19'),
(327, 290, 454, 0, 'grams', '2026-02-15 04:24:19'),
(328, 291, 250, 0, 'grams', '2026-02-15 04:24:19'),
(329, 292, 25000, 0, 'grams', '2026-02-15 04:24:19'),
(330, 293, 100, 0, 'grams', '2026-02-15 04:24:19'),
(331, 294, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(332, 295, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(333, 296, 250, 0, 'grams', '2026-02-15 04:24:19'),
(334, 297, 36000, 0, 'grams', '2026-02-15 04:24:19'),
(335, 298, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(336, 299, 36000, 0, 'grams', '2026-02-15 04:24:19'),
(337, 300, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(338, 301, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(339, 302, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(340, 303, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(341, 304, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(342, 305, 500, 0, 'grams', '2026-02-15 04:24:19'),
(343, 306, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(344, 307, 800, 0, 'grams', '2026-02-15 04:24:19'),
(345, 308, 25000, 0, 'grams', '2026-02-15 04:24:19'),
(346, 309, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(347, 310, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(348, 311, 250, 0, 'grams', '2026-02-15 04:24:19'),
(349, 312, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(350, 313, 470, 0, 'grams', '2026-02-15 04:24:19'),
(351, 314, 140, 0, 'grams', '2026-02-15 04:24:19'),
(352, 315, 500, 0, 'grams', '2026-02-15 04:24:19'),
(353, 316, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(354, 317, 500, 0, 'grams', '2026-02-15 04:24:19'),
(355, 318, 250, 0, 'grams', '2026-02-15 04:24:19'),
(356, 319, 165, 0, 'grams', '2026-02-15 04:24:19'),
(357, 320, 10, 0, 'pcs', '2026-02-15 04:24:19'),
(358, 321, 30, 0, 'grams', '2026-02-15 04:24:19'),
(359, 322, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(360, 323, 500, 0, 'grams', '2026-02-15 04:24:19'),
(361, 324, 8, 0, 'pcs', '2026-02-15 04:24:19'),
(362, 325, 220, 0, 'grams', '2026-02-15 04:24:19'),
(363, 326, 390, 0, 'grams', '2026-02-15 04:24:19'),
(364, 327, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(365, 328, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(366, 329, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(367, 330, 1, 0, 'pcs', '2026-02-15 04:24:19'),
(368, 331, 250, 0, 'grams', '2026-02-15 04:24:19'),
(369, 332, 250, 0, 'grams', '2026-02-15 04:24:19'),
(370, 333, 680, 0, 'pcs', '2026-02-15 04:24:19'),
(371, 334, 100, 0, 'grams', '2026-02-15 04:24:19'),
(372, 335, 2000, 0, 'grams', '2026-02-15 04:24:19'),
(373, 336, 750, 0, 'grams', '2026-02-15 04:24:19'),
(374, 337, 225, 0, 'grams', '2026-02-15 04:24:19'),
(375, 338, 250, 0, 'grams', '2026-02-15 04:24:19'),
(430, 339, 100, 0, 'grams', '2026-02-15 04:46:02');

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
(1, 'junaag@my.cspc.edu.ph', 'Julius ', '', 'Naag', 'owner', 'Julz', '$2y$10$siODUx8SP8/M0NEPpDfpHO7YH8V7XT3sncckhrI0J8sL6EHyKMRtu', 'male', '2025-04-16', '09388702935', 1, '2026-02-14 12:53:18');

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
  MODIFY `daily_stock_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_stock_items`
--
ALTER TABLE `daily_stock_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `distributions`
--
ALTER TABLE `distributions`
  MODIFY `distribution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `material_category`
--
ALTER TABLE `material_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `material_delivery`
--
ALTER TABLE `material_delivery`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_combined_recipes`
--
ALTER TABLE `product_combined_recipes`
  MODIFY `combined_recipe_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_costs`
--
ALTER TABLE `product_costs`
  MODIFY `product_cost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_recipe`
--
ALTER TABLE `product_recipe`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `raw_materials`
--
ALTER TABLE `raw_materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=340;

--
-- AUTO_INCREMENT for table `raw_material_cost`
--
ALTER TABLE `raw_material_cost`
  MODIFY `cost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=430;

--
-- AUTO_INCREMENT for table `raw_material_stock`
--
ALTER TABLE `raw_material_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=431;

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
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `utility_expenses`
--
ALTER TABLE `utility_expenses`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT;

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
