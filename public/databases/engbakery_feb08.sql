-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2026 at 04:45 AM
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

--
-- Dumping data for table `daily_stock`
--

INSERT INTO `daily_stock` (`daily_stock_id`, `inventory_date`, `time_start`, `time_end`) VALUES
(1, '2026-01-25', '07:59:00', '20:00:00'),
(2, '2026-01-26', '00:14:00', '05:14:00'),
(5, '2026-01-28', '20:02:00', '23:02:00'),
(7, '2026-01-31', '08:00:00', '17:00:00'),
(9, '2026-02-01', '08:00:00', '17:00:00'),
(10, '2026-02-02', '08:00:00', '17:00:00'),
(11, '2026-02-03', '08:00:00', '17:00:00'),
(12, '2026-02-04', '08:00:00', '17:00:00'),
(24, '2026-02-07', '08:00:00', '17:00:00');

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
(1, 1, 1, 0, 0, 0),
(2, 1, 2, 100, 2, 70),
(3, 1, 3, 0, 0, 0),
(4, 2, 1, 0, 0, 0),
(5, 2, 2, 52, 3, 49),
(6, 2, 3, 0, 0, 0),
(11, 5, 2, 13, 0, 5),
(12, 7, 5, 1000, 0, 975),
(13, 7, 2, 1200, 0, 1200),
(14, 7, 3, 400, 0, 390),
(20, 9, 2, 0, 0, 0),
(21, 9, 3, 0, 0, 0),
(22, 10, 2, 200, 0, 131),
(25, 10, 3, 0, 0, 0),
(26, 11, 2, 100, 0, 90),
(27, 11, 3, 0, 0, 0),
(28, 11, 5, 0, 0, 0),
(31, 12, 5, 0, 0, 0),
(188, 24, 21, 20, 0, 19),
(189, 24, 10, 200, 0, 200),
(190, 24, 5, 200, 0, 200),
(191, 24, 3, 0, 0, 0);

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

--
-- Dumping data for table `distributions`
--

INSERT INTO `distributions` (`distribution_id`, `product_id`, `product_qnty`, `distribution_date`) VALUES
(1, 21, 20, '2026-02-07'),
(2, 10, 200, '2026-02-07'),
(3, 5, 200, '2026-02-07');

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
(1, 'Raw Materials - Bread', 'Raw materials used for baking and food production', 'bread', '2026-01-29 13:59:12'),
(2, 'Packaging', 'Packaging materials for products', 'general', '2026-01-25 06:22:23'),
(3, 'Office Supplies', 'Office and administrative supplies', 'general', '2026-01-25 06:22:23'),
(4, 'Coffee Expenses', 'Materials and supplies for coffee drinks', 'drinks', '2026-01-25 06:22:23'),
(5, 'Overhead Expenses', '', 'general', '2026-01-25 06:39:12'),
(6, 'Raw Materials - General', '', 'general', '2026-01-29 13:59:23');

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
(39, 7, 7, 0, 'cash', 'walk-in', 'Andrew  Rivera', '2026-02-02', '21:59:13'),
(40, 7, 7, 0, 'cash', 'walk-in', 'Andrew  Rivera', '2026-02-02', '22:03:17'),
(45, 293, 293, 0, 'cash', 'walk-in', 'Andrew  Rivera', '2026-02-03', '20:15:40'),
(46, 7, 7, 0, 'maya', 'walk-in', 'Andrew  Rivera', '2026-02-03', '20:23:49'),
(47, 1150, 2000, 850, 'maya', 'foodpanda', 'Andrew  Rivera', '2026-02-03', '21:13:58'),
(48, 70, 70, 0, 'credit card', 'foodpanda', 'Andrew  Rivera', '2026-02-04', '12:53:43'),
(49, 115, 115, 0, 'cash', 'walk-in', 'Andrew  Rivera', '2026-02-04', '12:57:21'),
(50, 42, 42, 0, 'cash', 'walk-in', 'Andrew  Rivera', '2026-02-04', '13:01:04'),
(51, 28, 28, 0, 'cash', 'walk-in', 'Andrew  Rivera', '2026-02-04', '13:06:08'),
(52, 115, 115, 0, 'credit card', 'walk-in', 'Andrew  Rivera', '2026-02-04', '13:06:33'),
(53, 80, 80, 0, 'gcash', 'walk-in', 'Julius  Naag', '2026-02-05', '15:38:25'),
(54, 127, 127, 0, 'maya', 'walk-in', 'Stephen Andrew Cesista Noblesala', '2026-02-07', '22:39:10');

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
(1, 2, 1, 4, 7, 28, '2026-01-25', '23:01:36'),
(2, 2, 2, 24, 7, 168, '2026-01-25', '23:23:19'),
(3, 2, 3, 5, 7, 35, '2026-01-26', '00:15:07'),
(5, 2, 5, 6, 7, 42, '2026-01-28', '17:23:46'),
(6, 2, 6, 7, 7, 49, '2026-01-28', '22:04:33'),
(7, 2, 7, 1, 7, 7, '2026-01-28', '22:17:40'),
(8, 5, 8, 9, 30, 270, '2026-01-31', '08:37:18'),
(9, 5, 9, 3, 30, 90, '2026-01-31', '08:37:29'),
(10, 5, 10, 4, 30, 120, '2026-01-31', '08:37:45'),
(11, 5, 11, 9, 30, 270, '2026-01-31', '08:37:58'),
(12, 3, 12, 5, 115, 575, '2026-01-31', '09:06:43'),
(13, 3, 13, 5, 115, 575, '2026-01-31', '09:07:01'),
(14, 3, 14, 5, 115, 575, '2026-02-01', '10:59:34'),
(15, 5, 15, 5, 30, 150, '2026-02-01', '10:59:50'),
(16, 3, 16, 5, 115, 575, '2026-02-01', '22:31:30'),
(28, 2, 28, 15, 7, 105, '2026-02-02', '00:27:41'),
(29, 3, 28, 5, 115, 575, '2026-02-02', '00:27:41'),
(30, 2, 29, 3, 7, 21, '2026-02-02', '00:37:33'),
(31, 2, 30, 5, 7, 35, '2026-02-02', '00:44:13'),
(32, 2, 31, 4, 7, 28, '2026-02-02', '21:11:54'),
(33, 2, 32, 3, 7, 21, '2026-02-02', '21:16:17'),
(34, 2, 33, 29, 7, 203, '2026-02-02', '21:36:26'),
(35, 3, 34, 1, 115, 115, '2026-02-02', '21:38:22'),
(36, 2, 35, 1, 7, 7, '2026-02-02', '21:46:37'),
(37, 2, 36, 5, 7, 35, '2026-02-02', '21:51:34'),
(39, 2, 38, 1, 7, 7, '2026-02-02', '21:58:50'),
(40, 2, 39, 1, 7, 7, '2026-02-02', '21:59:13'),
(41, 2, 40, 1, 7, 7, '2026-02-02', '22:03:17'),
(42, 2, 45, 9, 7, 63, '2026-02-03', '20:15:40'),
(43, 3, 45, 2, 115, 230, '2026-02-03', '20:15:40'),
(44, 2, 46, 1, 7, 7, '2026-02-03', '20:23:49'),
(45, 3, 47, 10, 115, 1150, '2026-02-03', '21:13:58'),
(46, 2, 48, 10, 7, 70, '2026-02-04', '12:53:43'),
(47, 3, 49, 1, 115, 115, '2026-02-04', '12:57:21'),
(48, 2, 50, 6, 7, 42, '2026-02-04', '13:01:04'),
(49, 2, 51, 4, 7, 28, '2026-02-04', '13:06:08'),
(50, 3, 52, 1, 115, 115, '2026-02-04', '13:06:33'),
(51, 22, 53, 1, 80, 80, '2026-02-05', '15:38:25'),
(52, 3, 54, 1, 115, 115, '2026-02-07', '22:39:10'),
(53, 21, 54, 1, 12, 12, '2026-02-07', '22:39:10');

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
(1, 'dough', 'Soft Dough - 30g', '', 0, '2026-01-25 06:26:13'),
(2, 'bakery', 'Pandecoco', '', 0, '2026-01-31 00:30:42'),
(3, 'drinks', 'Spanish latte', '', 0, '2026-01-25 08:26:46'),
(5, 'grocery', 'Water 300ml', '', 0, '2026-01-31 00:35:48'),
(8, 'drinks', 'Vanilla Strawberry Frappe', '', 0, '2026-02-05 04:01:49'),
(9, 'drinks', 'Café Americano', '', 0, '2026-02-05 04:08:51'),
(10, 'drinks', 'Iced Americano', '', 1, '2026-02-07 14:31:23'),
(11, 'drinks', 'Café Latte', '', 1, '2026-02-07 14:31:16'),
(12, 'drinks', 'Caramel Macchiatio', '', 1, '2026-02-07 14:31:18'),
(13, 'drinks', 'Iced Latte', '', 1, '2026-02-07 14:31:19'),
(14, 'drinks', 'Iced Spanish Latte', '', 1, '2026-02-07 14:30:52'),
(15, 'drinks', 'Iced Caramel Macchiato', '', 1, '2026-02-07 14:30:51'),
(16, 'drinks', 'Hot Matcha', '', 1, '2026-02-07 14:31:20'),
(17, 'drinks', 'Iced Matcha Latte', '', 1, '2026-02-07 14:30:51'),
(18, 'drinks', 'Matcha Frappe', '', 1, '2026-02-07 14:31:21'),
(19, 'drinks', 'Belgian Chocolate Frappe', '', 0, '2026-02-07 14:31:39'),
(21, 'bakery', 'Maligaya Filling', '', 0, '2026-02-05 06:02:58'),
(22, 'bakery', 'Choco Croissant', '', 0, '2026-02-05 06:12:34'),
(23, 'bakery', 'Hotdog Twist Filling', '', 1, '2026-02-07 14:30:47'),
(24, 'bakery', 'Cinnamon Croissant Deca', '', 0, '2026-02-05 06:18:42'),
(25, 'bakery', 'Egg Pie Crust', '', 1, '2026-02-05 07:56:09'),
(26, 'dough', 'Soft Dough - 50 grams', '', 0, '2026-02-05 07:57:33');

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
(14, 24, 1, 30, 0.038425462459195, 31.124624591948, '2026-02-05 08:12:11'),
(15, 2, 1, 30, 0.038425462459195, 207.49749727965, '2026-02-05 08:14:24');

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
(1, 1, 70.626, 0, 0, 0, 0, 0, 70.626, 70, 0, 1.5, 1838, 0, 61, 0, 30, '2026-01-25 06:45:45'),
(2, 2, 175.9, 0, 0, 207.49749727965, 0, 0, 175.9, 384, 0, 7, 2350, 0, 180, 0, 14.17, '2026-02-05 08:14:24'),
(3, 3, 56.38, 20, 11.276, 0, 40, 27.0624, 67.656, 115, 0, 0, 0, 0, 0, 0, 0, '2026-01-25 08:26:46'),
(4, 5, 17.6, 0, 0, 0, 30, 5.28, 17.6, 30, 0, 0, 0, 0, 0, 0, 0, '2026-01-31 00:32:24'),
(7, 8, 55.176, 20, 11.0352, 0, 40, 26.48448, 66.2112, 110, 0, 0, 0, 0, 0, 0, 0, '2026-02-05 04:04:00'),
(8, 9, 34.2, 20, 6.84, 0, 40, 16.416, 41.04, 70, 0, 0, 0, 0, 0, 0, 0, '2026-02-05 04:08:51'),
(9, 10, 64.84, 20, 12.968, 0, 40, 31.1232, 77.808, 130, 0, 0, 0, 0, 0, 0, 0, '2026-02-05 04:13:35'),
(10, 11, 54.54, 20, 10.908, 0, 40, 26.1792, 65.448, 110, 0, 0, 0, 0, 0, 0, 0, '2026-02-05 04:20:00'),
(11, 12, 53.636, 20, 10.7272, 0, 40, 25.74528, 64.3632, 110, 0, 0, 0, 0, 0, 0, 0, '2026-02-05 04:26:50'),
(12, 13, 53.636, 20, 10.7272, 0, 40, 25.74528, 64.3632, 110, 0, 0, 0, 0, 0, 0, 0, '2026-02-05 04:33:24'),
(13, 14, 55.476, 20, 11.0952, 0, 40, 26.62848, 66.5712, 115, 0, 0, 0, 0, 0, 0, 0, '2026-02-05 04:36:36'),
(14, 15, 63.936, 20, 12.7872, 0, 40, 30.68928, 76.7232, 130, 0, 0, 0, 0, 0, 0, 0, '2026-02-05 04:57:19'),
(15, 16, 39.95, 20, 7.99, 0, 40, 19.176, 47.94, 90, 0, 0, 0, 0, 0, 0, 0, '2026-02-05 04:59:53'),
(16, 17, 39.046, 20, 7.8092, 0, 40, 18.74208, 46.8552, 90, 0, 0, 0, 0, 0, 0, 0, '2026-02-05 05:05:00'),
(17, 18, 44.536, 20, 8.9072, 0, 40, 21.37728, 53.4432, 100, 0, 0, 0, 0, 0, 0, 0, '2026-02-05 05:09:11'),
(18, 19, 53.176, 20, 10.6352, 0, 40, 25.52448, 63.8112, 110, 0, 0, 0, 0, 0, 0, 0, '2026-02-05 05:12:47'),
(20, 21, 154.21, 0, 0, 0, 0, 0, 154.21, 155, 0, 12, 3190, 1, 12, 3190, 265.83, '2026-02-05 06:04:30'),
(21, 22, 11.67925, 0, 0, 0, 0, 0, 11.67925, 11.67925, 0, 80, 34.25, 0, 1, 0, 34.25, '2026-02-05 06:12:34'),
(22, 23, 60.2, 0, 0, 0, 30, 18.06, 60.2, 60, 0, 30, 0, 0, 8, 0, 6.25, '2026-02-05 06:14:23'),
(23, 24, 187.56, 0, 0, 31.124624591948, 0, 0, 187.56, 188, 0, 65, 0, 0, 27, 0, 26.85, '2026-02-05 08:12:11'),
(24, 25, 63.05, 0, 0, 0, 30, 18.915, 63.05, 100, 0, 15, 1355, 0, 6, 0, 225.83, '2026-02-05 06:24:38'),
(25, 26, 70.643333333333, 0, 0, 0, 0, 0, 70.643333333333, 70, 0, 1.92, 1838, 0, 36, 0, 50, '2026-02-05 07:36:58');

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
(77, 1, 2, 1000, 'grams', '2026-01-25 07:28:39'),
(78, 1, 3, 200, 'grams', '2026-01-25 07:28:39'),
(79, 1, 14, 15, 'grams', '2026-01-25 07:28:39'),
(80, 1, 40, 10, 'grams', '2026-01-25 07:28:39'),
(81, 1, 32, 60, 'grams', '2026-01-25 07:28:39'),
(82, 1, 16, 20, 'grams', '2026-01-25 07:28:39'),
(83, 1, 20, 100, 'grams', '2026-01-25 07:28:39'),
(85, 1, 44, 3, 'grams', '2026-01-25 07:28:39'),
(94, 3, 102, 18, 'grams', '2026-01-25 08:26:46'),
(95, 3, 104, 250, 'ml', '2026-01-25 08:26:46'),
(96, 3, 121, 1, 'pcs', '2026-01-25 08:26:46'),
(97, 3, 115, 3, 'pcs', '2026-01-25 08:26:46'),
(98, 3, 105, 30, 'grams', '2026-01-25 08:26:46'),
(99, 3, 116, 1, 'pcs', '2026-01-25 08:26:46'),
(111, 5, 68, 100, 'pcs', '2026-01-31 00:35:48'),
(121, 8, 110, 45, 'grams', '2026-02-05 04:04:00'),
(122, 8, 104, 90, 'ml', '2026-02-05 04:04:00'),
(123, 8, 112, 20, 'ml', '2026-02-05 04:04:00'),
(124, 8, 114, 15, 'ml', '2026-02-05 04:04:00'),
(125, 8, 118, 1, 'pcs', '2026-02-05 04:04:00'),
(126, 8, 120, 1, 'pcs', '2026-02-05 04:04:00'),
(127, 8, 115, 3, 'pcs', '2026-02-05 04:04:00'),
(128, 8, 122, 692, 'grams', '2026-02-05 04:04:00'),
(129, 8, 130, 10, 'ml', '2026-02-05 04:04:00'),
(130, 9, 102, 18, 'grams', '2026-02-05 04:08:51'),
(131, 9, 103, 20, 'ml', '2026-02-05 04:08:51'),
(132, 9, 116, 1, 'pcs', '2026-02-05 04:08:51'),
(133, 9, 121, 1, 'pcs', '2026-02-05 04:08:51'),
(134, 9, 115, 3, 'pcs', '2026-02-05 04:08:51'),
(135, 10, 102, 18, 'grams', '2026-02-05 04:13:35'),
(136, 10, 104, 250, 'ml', '2026-02-05 04:13:35'),
(137, 10, 106, 20, 'ml', '2026-02-05 04:13:35'),
(138, 10, 107, 10, 'ml', '2026-02-05 04:13:35'),
(139, 10, 116, 1, 'pcs', '2026-02-05 04:13:35'),
(140, 10, 121, 1, 'pcs', '2026-02-05 04:13:35'),
(141, 10, 115, 3, 'pcs', '2026-02-05 04:13:35'),
(142, 11, 102, 18, 'grams', '2026-02-05 04:20:00'),
(143, 11, 104, 250, 'ml', '2026-02-05 04:20:00'),
(144, 11, 103, 25, 'ml', '2026-02-05 04:20:00'),
(145, 11, 116, 1, 'pcs', '2026-02-05 04:20:00'),
(146, 11, 121, 1, 'pcs', '2026-02-05 04:20:00'),
(147, 11, 115, 3, 'pcs', '2026-02-05 04:20:00'),
(148, 12, 102, 18, 'grams', '2026-02-05 04:26:50'),
(149, 12, 104, 150, 'ml', '2026-02-05 04:26:50'),
(150, 12, 103, 25, 'grams', '2026-02-05 04:26:50'),
(151, 12, 117, 1, 'pcs', '2026-02-05 04:26:50'),
(152, 12, 119, 1, 'pcs', '2026-02-05 04:26:50'),
(153, 12, 115, 3, 'pcs', '2026-02-05 04:26:50'),
(154, 12, 122, 692, 'grams', '2026-02-05 04:26:50'),
(155, 13, 102, 18, 'grams', '2026-02-05 04:33:24'),
(156, 13, 104, 150, 'ml', '2026-02-05 04:33:24'),
(157, 13, 103, 25, 'ml', '2026-02-05 04:33:24'),
(158, 13, 117, 1, 'pcs', '2026-02-05 04:33:24'),
(159, 13, 120, 1, 'pcs', '2026-02-05 04:33:24'),
(160, 13, 115, 3, 'pcs', '2026-02-05 04:33:24'),
(161, 13, 122, 692, 'grams', '2026-02-05 04:33:24'),
(162, 14, 102, 18, 'grams', '2026-02-05 04:36:36'),
(163, 14, 104, 150, 'grams', '2026-02-05 04:36:36'),
(164, 14, 105, 30, 'ml', '2026-02-05 04:36:36'),
(165, 14, 117, 1, 'pcs', '2026-02-05 04:36:36'),
(166, 14, 119, 1, 'pcs', '2026-02-05 04:36:36'),
(167, 14, 115, 3, 'pcs', '2026-02-05 04:36:36'),
(168, 14, 122, 692, 'grams', '2026-02-05 04:36:36'),
(169, 15, 102, 18, 'grams', '2026-02-05 04:57:19'),
(170, 15, 104, 150, 'grams', '2026-02-05 04:57:19'),
(171, 15, 106, 20, 'ml', '2026-02-05 04:57:19'),
(172, 15, 107, 10, 'ml', '2026-02-05 04:57:19'),
(173, 15, 117, 1, 'pcs', '2026-02-05 04:57:19'),
(174, 15, 119, 1, 'pcs', '2026-02-05 04:57:19'),
(175, 15, 115, 3, 'pcs', '2026-02-05 04:57:19'),
(176, 15, 122, 692, 'grams', '2026-02-05 04:57:19'),
(177, 16, 108, 5, 'grams', '2026-02-05 04:59:53'),
(178, 16, 104, 250, 'ml', '2026-02-05 04:59:53'),
(179, 16, 103, 20, 'ml', '2026-02-05 04:59:53'),
(180, 16, 116, 1, 'pcs', '2026-02-05 04:59:53'),
(181, 16, 121, 1, 'pcs', '2026-02-05 04:59:53'),
(182, 16, 115, 3, 'pcs', '2026-02-05 04:59:53'),
(183, 17, 108, 5, 'grams', '2026-02-05 05:05:00'),
(184, 17, 104, 150, 'grams', '2026-02-05 05:05:00'),
(185, 17, 103, 20, 'grams', '2026-02-05 05:05:00'),
(186, 17, 117, 1, 'pcs', '2026-02-05 05:05:00'),
(187, 17, 119, 1, 'pcs', '2026-02-05 05:05:00'),
(188, 17, 115, 3, 'pcs', '2026-02-05 05:05:00'),
(189, 17, 122, 692, 'grams', '2026-02-05 05:05:00'),
(190, 18, 109, 45, 'grams', '2026-02-05 05:09:11'),
(191, 18, 104, 90, 'grams', '2026-02-05 05:09:11'),
(192, 18, 103, 20, 'grams', '2026-02-05 05:09:11'),
(193, 18, 114, 15, 'grams', '2026-02-05 05:09:11'),
(194, 18, 118, 1, 'pcs', '2026-02-05 05:09:11'),
(195, 18, 120, 1, 'pcs', '2026-02-05 05:09:11'),
(196, 18, 115, 3, 'pcs', '2026-02-05 05:09:11'),
(197, 18, 122, 692, 'grams', '2026-02-05 05:09:11'),
(198, 19, 111, 45, 'grams', '2026-02-05 05:12:47'),
(199, 19, 104, 90, 'grams', '2026-02-05 05:12:47'),
(200, 19, 113, 15, 'ml', '2026-02-05 05:12:47'),
(201, 19, 114, 15, 'ml', '2026-02-05 05:12:47'),
(202, 19, 118, 1, 'pcs', '2026-02-05 05:12:47'),
(203, 19, 120, 1, 'pcs', '2026-02-05 05:12:47'),
(204, 19, 115, 3, 'pcs', '2026-02-05 05:12:47'),
(205, 19, 122, 692, 'grams', '2026-02-05 05:12:47'),
(206, 19, 130, 10, 'grams', '2026-02-05 05:12:47'),
(227, 21, 3, 1500, 'grams', '2026-02-05 06:07:52'),
(228, 21, 43, 1500, 'grams', '2026-02-05 06:07:52'),
(229, 21, 23, 10, 'grams', '2026-02-05 06:07:52'),
(230, 21, 13, 90, 'grams', '2026-02-05 06:07:52'),
(231, 21, 29, 90, 'grams', '2026-02-05 06:07:52'),
(232, 22, 57, 31.25, 'grams', '2026-02-05 06:12:34'),
(233, 22, 73, 3, 'grams', '2026-02-05 06:12:34'),
(234, 23, 55, 8, 'pcs', '2026-02-05 06:14:23'),
(235, 23, 48, 50, 'grams', '2026-02-05 06:14:23'),
(246, 25, 43, 1000, 'grams', '2026-02-05 06:24:38'),
(247, 25, 32, 320, 'grams', '2026-02-05 06:24:38'),
(248, 25, 3, 30, 'grams', '2026-02-05 06:24:38'),
(249, 25, 14, 5, 'grams', '2026-02-05 06:24:38'),
(250, 26, 2, 1000, 'grams', '2026-02-05 07:36:58'),
(251, 26, 3, 200, 'grams', '2026-02-05 07:36:58'),
(252, 26, 14, 15, 'grams', '2026-02-05 07:36:58'),
(253, 26, 40, 10, 'grams', '2026-02-05 07:36:58'),
(254, 26, 44, 3, 'grams', '2026-02-05 07:36:58'),
(255, 26, 32, 60, 'grams', '2026-02-05 07:36:58'),
(256, 26, 16, 20, 'grams', '2026-02-05 07:36:58'),
(257, 26, 20, 100, 'grams', '2026-02-05 07:36:58'),
(258, 26, 131, 430, 'grams', '2026-02-05 07:36:58'),
(259, 24, 5, 250, 'grams', '2026-02-05 08:12:11'),
(260, 24, 50, 15, 'grams', '2026-02-05 08:12:11'),
(261, 24, 38, 15, 'grams', '2026-02-05 08:12:11'),
(262, 24, 41, 40, 'grams', '2026-02-05 08:12:11'),
(263, 24, 73, 60, 'grams', '2026-02-05 08:12:11'),
(264, 24, 51, 100, 'grams', '2026-02-05 08:12:11'),
(265, 24, 16, 30, 'pcs', '2026-02-05 08:12:11'),
(266, 24, 20, 50, 'grams', '2026-02-05 08:12:11'),
(267, 24, 6, 180, 'grams', '2026-02-05 08:12:11'),
(268, 24, 13, 15, 'grams', '2026-02-05 08:12:11'),
(269, 2, 39, 1500, 'grams', '2026-02-05 08:14:24'),
(270, 2, 3, 750, 'grams', '2026-02-05 08:14:24'),
(271, 2, 34, 100, 'grams', '2026-02-05 08:14:24');

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
(1, 1, 'Flour - All Purpose', 0, 'grams', '2026-01-25 06:22:23'),
(2, 1, 'Flour - First Class Kutitap ', 0, 'grams', '2026-02-05 06:59:30'),
(3, 1, 'Sugar 99', 0, 'grams', '2026-01-25 08:07:58'),
(4, 1, 'Sugar Brown', 0, 'grams', '2026-01-25 06:22:23'),
(5, 1, 'Sugar White', 0, 'grams', '2026-01-25 06:22:23'),
(6, 1, 'Sugar Powdered - Penco', 0, 'grams', '2026-01-25 06:22:23'),
(7, 1, 'Baking Powder - Ordinary', 0, 'grams', '2026-01-25 06:22:23'),
(8, 1, 'Baking Powder - Calumet', 0, 'grams', '2026-01-25 06:22:23'),
(9, 1, 'Cream of Tartar', 0, 'grams', '2026-01-25 06:22:23'),
(10, 1, 'JB-15', 0, 'grams', '2026-01-25 06:22:23'),
(11, 1, 'Cocoa Ordinary', 0, 'grams', '2026-01-25 06:22:23'),
(12, 1, 'Buttermilk', 0, 'grams', '2026-01-25 06:22:23'),
(13, 1, 'Vanilla', 0, 'grams', '2026-01-25 06:22:23'),
(14, 1, 'Salt', 0, 'grams', '2026-01-25 06:22:23'),
(15, 1, 'Canola Oil', 0, 'grams', '2026-01-25 06:22:23'),
(16, 1, 'Buttercup (per piece)', 0, 'grams', '2026-02-05 07:25:18'),
(17, 1, 'Cheese - Magnolia (per piece)', 0, 'grams', '2026-01-25 06:22:23'),
(18, 1, 'Evaporated Milk - Evaporada (per can)', 0, 'grams', '2026-01-25 06:22:23'),
(19, 1, 'Condensed Milk - Condensada (per can)', 0, 'grams', '2026-01-25 06:22:23'),
(20, 1, 'Fresh Milk - (per box)', 0, 'grams', '2026-01-25 06:22:23'),
(21, 1, 'Cornstarch - Farola', 0, 'grams', '2026-01-25 06:22:23'),
(22, 1, 'Eggs (1tray)', 0, 'pcs', '2026-01-25 06:22:23'),
(23, 1, 'Food Color - Strawberry Red', 0, 'grams', '2026-01-25 06:22:23'),
(24, 1, 'Food Color - Ube', 0, 'grams', '2026-01-25 06:22:23'),
(25, 1, 'Food Color - Egg Yellow', 0, 'grams', '2026-01-25 06:22:23'),
(26, 1, 'Baking Soda', 0, 'grams', '2026-01-25 06:22:23'),
(27, 1, 'Cake Flour', 0, 'grams', '2026-01-25 06:22:23'),
(28, 1, 'Coffee', 0, 'grams', '2026-01-25 06:22:23'),
(29, 1, 'Palm Oil - Cooking Ordinary', 0, 'grams', '2026-01-25 06:22:23'),
(30, 1, 'Desicated Coconut', 0, 'grams', '2026-01-25 06:22:23'),
(31, 1, 'Emulsifier - Puratos Mixo', 0, 'grams', '2026-01-25 06:22:23'),
(32, 1, 'Lard - Approved', 0, 'grams', '2026-01-25 06:22:23'),
(33, 1, 'Macco - Puratos Preservative Bread', 0, 'grams', '2026-01-25 06:22:23'),
(34, 1, 'Margarine - Baker\'s Choice', 0, 'grams', '2026-01-25 06:22:23'),
(35, 1, 'Ube Paste', 0, 'grams', '2026-01-25 06:22:23'),
(36, 1, 'Monggo Paste', 0, 'grams', '2026-01-25 06:22:23'),
(37, 1, 'Onion', 0, 'grams', '2026-01-25 06:22:23'),
(38, 1, 'Peanut - Skinless', 0, 'grams', '2026-01-25 06:22:23'),
(39, 1, 'Niyog', 0, 'grams', '2026-01-25 06:22:23'),
(40, 1, 'Yeast - Angel Instant', 0, 'grams', '2026-01-25 06:22:23'),
(41, 1, 'Raisin', 0, 'grams', '2026-01-25 06:22:23'),
(42, 1, 'Yeast - Red Star Active Dry', 0, 'grams', '2026-01-25 06:22:23'),
(43, 1, 'Flour - Mayon Third Class', 0, 'grams', '2026-01-25 06:22:23'),
(44, 1, 'Improver - Toupan', 0, 'grams', '2026-01-25 06:22:23'),
(45, 1, 'Potassium Sorbate - Preservative Cake', 0, 'grams', '2026-01-25 06:22:23'),
(46, 1, 'Carbonato', 0, 'grams', '2026-01-25 06:22:23'),
(47, 1, 'Mayonnaise - Kewpie', 0, 'grams', '2026-01-25 06:22:23'),
(48, 1, 'Mayonnaise - All Purpose Dressing', 0, 'grams', '2026-01-25 06:22:23'),
(49, 1, 'Tuna - Century (per can)', 0, 'grams', '2026-01-25 06:22:23'),
(50, 1, 'Cinnamon Powder - HYCO (per can)', 0, 'grams', '2026-01-25 06:22:23'),
(51, 1, 'Creamcheese - Anchor', 0, 'grams', '2026-01-25 06:22:23'),
(52, 1, 'Rolled Oats', 0, 'grams', '2026-01-25 06:22:23'),
(53, 1, 'All Purpose Cream', 0, 'grams', '2026-01-25 06:22:23'),
(54, 1, 'Cheddar Cheese - Magnolia (per piece)', 0, 'grams', '2026-01-25 06:22:23'),
(55, 1, 'Hotdog', 0, 'pcs', '2026-01-25 06:22:23'),
(56, 1, 'Nutmeg - McCormick', 0, 'grams', '2026-01-25 06:22:23'),
(57, 1, 'Chocolate Bar - Fuji', 0, 'grams', '2026-01-25 06:22:23'),
(58, 1, 'Whip It - Whipping Cream', 0, 'grams', '2026-01-25 06:22:23'),
(59, 1, 'Sweet Ham - CDO Regular', 0, 'pcs', '2026-01-25 06:22:23'),
(60, 1, 'Butter - Magnolia (per piece)', 0, 'grams', '2026-01-25 06:22:23'),
(61, 1, 'Condensed Milk - Ube', 0, 'grams', '2026-01-25 06:22:23'),
(62, 1, 'Chocolate Drops', 0, 'grams', '2026-01-25 06:22:23'),
(63, 1, 'Chocolate Drops - White', 0, 'grams', '2026-01-25 06:22:23'),
(64, 1, 'Banana - Bungoran', 0, 'grams', '2026-01-25 06:22:23'),
(65, 1, 'Pineapple', 0, 'pcs', '2026-01-25 06:22:23'),
(66, 1, 'Walnuts', 0, 'grams', '2026-01-25 06:22:23'),
(67, 1, 'Almond', 0, 'grams', '2026-01-25 06:22:23'),
(68, 1, 'Marshmallow', 0, 'pcs', '2026-01-25 06:22:23'),
(69, 1, 'Matcha Powder', 0, 'grams', '2026-01-25 06:22:23'),
(70, 1, 'Ice', 0, 'grams', '2026-01-25 06:22:23'),
(71, 1, 'Glucose', 0, 'grams', '2026-01-25 06:22:23'),
(72, 1, 'Butter - Queensland', 0, 'grams', '2026-01-25 06:22:23'),
(73, 1, 'Walnuts - Chopped', 0, 'grams', '2026-01-25 06:22:23'),
(74, 2, 'Baking Cups - 2oz', 0, 'pcs', '2026-01-25 06:22:23'),
(75, 2, 'Boxes - 10x10x4 in', 0, 'pcs', '2026-01-25 06:22:23'),
(76, 2, 'Boxes - 9x9x2 in', 0, 'pcs', '2026-01-25 06:22:23'),
(77, 2, 'Paper Plate', 0, 'pcs', '2026-01-25 06:22:23'),
(78, 2, 'Brown Paper', 0, 'pcs', '2026-01-25 06:22:23'),
(79, 2, 'Cello Sheet - 9x13 in', 0, 'pcs', '2026-01-25 06:22:23'),
(80, 2, 'Gloves', 0, 'pcs', '2026-01-25 06:22:23'),
(81, 2, 'Plastic Bag - Medium', 0, 'pcs', '2026-01-25 06:22:23'),
(82, 2, 'Plastic Bag - Mini', 0, 'pcs', '2026-01-25 06:22:23'),
(83, 2, 'Plastic Bag - Tiny', 0, 'pcs', '2026-01-25 06:22:23'),
(84, 2, 'Plastic Labo - 8x11 in', 0, 'pcs', '2026-01-25 06:22:23'),
(85, 2, 'Plastic Labo - 10x14 in', 0, 'pcs', '2026-01-25 06:22:23'),
(86, 2, 'Supot No. 5', 0, 'pcs', '2026-01-25 06:22:23'),
(87, 2, 'Supot No. 3', 0, 'pcs', '2026-01-25 06:22:23'),
(88, 2, 'Custard Plastic - 5.5 cm', 0, 'pcs', '2026-01-25 06:22:23'),
(89, 2, 'Pastry Pouch - 11x13 cm', 0, 'pcs', '2026-01-25 06:22:23'),
(90, 2, 'Pastry Pouch - 10x13.5 cm', 0, 'pcs', '2026-01-25 06:22:23'),
(91, 2, 'Sliced Bread Clear - 30x34+10 cm', 0, 'pcs', '2026-01-25 06:22:23'),
(92, 2, 'Sliced Bread Opaque', 0, 'pcs', '2026-01-25 06:22:23'),
(93, 2, 'Ribbon Size 2 - 50m (1.6 per box)', 0, 'pcs', '2026-01-25 06:22:23'),
(94, 2, 'Supot No. 1', 0, 'pcs', '2026-01-25 06:22:23'),
(95, 2, 'Supot No. 2', 0, 'pcs', '2026-01-25 06:22:23'),
(96, 2, 'Baking Cups - 3 oz', 0, 'pcs', '2026-01-25 06:22:23'),
(97, 3, 'Tape', 0, 'pcs', '2026-01-25 06:22:23'),
(98, 3, 'Tagger', 0, 'pcs', '2026-01-25 06:22:23'),
(99, 3, 'Sticker Paper', 0, 'pcs', '2026-01-25 06:22:23'),
(100, 3, 'Receipt Duplicate - L (2 Copy 50 Pages)', 0, 'ream', '2026-01-25 06:22:23'),
(101, 3, 'Receipt Triplicate - L (3 Copy 60 Pages)', 0, 'ream', '2026-01-25 06:22:23'),
(102, 4, 'Coffee Beans', 0, 'grams', '2026-01-29 14:03:42'),
(103, 4, 'Sugar Syrup', 0, 'grams', '2026-01-25 06:22:23'),
(104, 4, 'Fresh Milk', 0, 'grams', '2026-01-25 06:22:23'),
(105, 4, 'Condensed Milk', 0, 'grams', '2026-01-25 06:22:23'),
(106, 4, 'Caramel Syrup', 0, 'grams', '2026-01-25 06:22:23'),
(107, 4, 'French Vanilla Syrup', 0, 'grams', '2026-01-25 06:22:23'),
(108, 4, 'Matcha Powder - Coffee', 0, 'grams', '2026-01-25 06:22:23'),
(109, 4, 'Matcha Powder - Frappe', 0, 'grams', '2026-01-25 06:22:23'),
(110, 4, 'Vanilla Powder', 0, 'grams', '2026-01-25 06:22:23'),
(111, 4, 'Choco Powder', 0, 'grams', '2026-01-25 06:22:23'),
(112, 4, 'Strawberry Syrup', 0, 'grams', '2026-01-25 06:22:23'),
(113, 4, 'Choco Syrup', 0, 'grams', '2026-01-25 06:22:23'),
(114, 4, 'Whipping Cream - Ever Whip', 0, 'grams', '2026-01-25 06:22:23'),
(115, 4, 'Tissue', 0, 'pcs', '2026-01-25 06:22:23'),
(116, 4, '12 oz Cup with Lid', 0, 'pcs', '2026-01-25 06:22:23'),
(117, 4, '16 oz Cup with Lid - Coffee', 0, 'pcs', '2026-01-25 06:22:23'),
(118, 4, '16 oz Cup with Lid - Frappe', 0, 'pcs', '2026-01-25 06:22:23'),
(119, 4, 'Straw Coffee', 0, 'pcs', '2026-01-25 06:22:23'),
(120, 4, 'Straw Frappe', 0, 'pcs', '2026-01-25 06:22:23'),
(121, 4, 'Stirrer', 0, 'pcs', '2026-01-25 06:22:23'),
(122, 4, 'Ice - Coffee', 0, 'grams', '2026-01-25 06:22:23'),
(130, 4, 'Drizzle - Strawberry Syrup', 0, 'grams', '2026-02-05 04:03:12'),
(131, 6, 'Water', 0, 'grams', '2026-02-05 07:14:40');

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
(1, 1, 0.054, '2026-01-25 06:22:23'),
(2, 2, 0.036, '2026-01-25 06:22:23'),
(3, 3, 0.062, '2026-01-25 06:22:23'),
(4, 4, 0.06, '2026-01-25 06:22:23'),
(5, 5, 0.072, '2026-01-25 06:22:23'),
(6, 6, 0.114, '2026-01-25 06:22:23'),
(7, 7, 0.084, '2026-01-25 06:22:23'),
(8, 8, 0.205, '2026-01-25 06:22:23'),
(9, 9, 0.64, '2026-01-25 06:22:23'),
(10, 10, 0.73, '2026-01-25 06:22:23'),
(11, 11, 0.18, '2026-01-25 06:22:23'),
(12, 12, 0.16, '2026-01-25 06:22:23'),
(13, 13, 0.04, '2026-01-25 06:22:23'),
(14, 14, 0.0136, '2026-02-05 07:27:20'),
(15, 15, 0.16, '2026-01-25 06:22:23'),
(16, 16, 0.23, '2026-01-25 06:22:23'),
(17, 17, 0.273, '2026-01-25 06:22:23'),
(18, 18, 0.1, '2026-01-25 06:22:23'),
(19, 19, 0.115, '2026-01-25 06:22:23'),
(20, 20, 0.09, '2026-01-25 06:22:23'),
(21, 21, 0.043, '2026-01-25 06:22:23'),
(22, 22, 8.833, '2026-01-25 06:22:23'),
(23, 23, 0.187, '2026-01-25 06:22:23'),
(24, 24, 0.187, '2026-01-25 06:22:23'),
(25, 25, 0.187, '2026-01-25 06:22:23'),
(26, 26, 0.096, '2026-01-25 06:22:23'),
(27, 27, 0.054, '2026-01-25 06:22:23'),
(28, 28, 0.65, '2026-01-25 06:22:23'),
(29, 29, 0.086, '2026-01-25 06:22:23'),
(30, 30, 0.18, '2026-01-25 06:22:23'),
(31, 31, 0.436, '2026-01-25 06:22:23'),
(32, 32, 0.091388888888889, '2026-02-05 07:31:10'),
(33, 33, 0.32, '2026-01-25 06:22:23'),
(34, 34, 0.094, '2026-01-25 06:22:23'),
(35, 35, 0.16, '2026-01-25 06:22:23'),
(36, 36, 0.16, '2026-01-25 06:22:23'),
(37, 37, 0.2, '2026-01-25 06:22:23'),
(38, 38, 0.102, '2026-01-25 06:22:23'),
(39, 39, 0.08, '2026-01-25 06:22:23'),
(40, 40, 0.256, '2026-01-25 06:22:23'),
(41, 41, 0.42, '2026-01-25 06:22:23'),
(42, 42, 0.396, '2026-01-25 06:22:23'),
(43, 43, 0.032, '2026-01-25 06:22:23'),
(44, 44, 0.132, '2026-01-25 06:22:23'),
(45, 45, 0.458, '2026-01-25 06:22:23'),
(46, 46, 0.06, '2026-01-25 06:22:23'),
(47, 47, 0.38, '2026-01-25 06:22:23'),
(48, 48, 0.228, '2026-01-25 06:22:23'),
(49, 49, 0.329, '2026-01-25 06:22:23'),
(50, 50, 1.05, '2026-01-25 06:22:23'),
(51, 51, 0.6, '2026-01-25 06:22:23'),
(52, 52, 0.234, '2026-01-25 06:22:23'),
(53, 53, 0.284, '2026-01-25 06:22:23'),
(54, 54, 0.636, '2026-01-25 06:22:23'),
(55, 55, 6.1, '2026-01-25 06:22:23'),
(56, 56, 3.4, '2026-01-25 06:22:23'),
(57, 57, 0.305, '2026-01-25 06:22:23'),
(58, 58, 0.28, '2026-01-25 06:22:23'),
(59, 59, 15, '2026-01-25 06:22:23'),
(60, 60, 0.886, '2026-01-25 06:22:23'),
(61, 61, 0.144, '2026-01-25 06:22:23'),
(62, 62, 0.38, '2026-01-25 06:22:23'),
(63, 63, 0.28, '2026-01-25 06:22:23'),
(64, 64, 0.03, '2026-01-25 06:22:23'),
(65, 65, 35, '2026-01-25 06:22:23'),
(66, 66, 0.66, '2026-01-25 06:22:23'),
(67, 67, 0.74, '2026-01-25 06:22:23'),
(68, 68, 0.176, '2026-01-25 06:22:23'),
(69, 69, 1.65, '2026-01-25 06:22:23'),
(70, 70, 0.013, '2026-01-25 06:22:23'),
(71, 71, 0.093, '2026-01-25 06:22:23'),
(72, 72, 0.6, '2026-01-25 06:22:23'),
(73, 73, 0.716, '2026-01-25 06:22:23'),
(74, 74, 0.065, '2026-01-25 06:22:23'),
(75, 75, 29.9, '2026-01-25 06:22:23'),
(76, 76, 19.4, '2026-01-25 06:22:23'),
(77, 77, 0.914, '2026-01-25 06:22:23'),
(78, 78, 3.2, '2026-01-25 06:22:23'),
(79, 79, 0.615, '2026-01-25 06:22:23'),
(80, 80, 0.35, '2026-01-25 06:22:23'),
(81, 81, 0.833, '2026-01-25 06:22:23'),
(82, 82, 0.154, '2026-01-25 06:22:23'),
(83, 83, 0.188, '2026-01-25 06:22:23'),
(84, 84, 0.1, '2026-01-25 06:22:23'),
(85, 85, 0.169, '2026-01-25 06:22:23'),
(86, 86, 0.65, '2026-01-25 06:22:23'),
(87, 87, 0.45, '2026-01-25 06:22:23'),
(88, 88, 1.306, '2026-01-25 06:22:23'),
(89, 89, 1.216, '2026-01-25 06:22:23'),
(90, 90, 0.896, '2026-01-25 06:22:23'),
(91, 91, 0.766, '2026-01-25 06:22:23'),
(92, 92, 0.95, '2026-01-25 06:22:23'),
(93, 93, 2.419, '2026-01-25 06:22:23'),
(94, 94, 0.35, '2026-01-25 06:22:23'),
(95, 95, 0.4, '2026-01-25 06:22:23'),
(96, 96, 0.7, '2026-01-25 06:22:23'),
(97, 97, 7.5, '2026-01-25 06:22:23'),
(98, 98, 12, '2026-01-25 06:22:23'),
(99, 99, 3.77, '2026-01-25 06:22:23'),
(100, 100, 168, '2026-01-25 06:22:23'),
(101, 101, 178, '2026-01-25 06:22:23'),
(102, 102, 1.25, '2026-01-25 06:22:23'),
(103, 103, 0.068, '2026-01-25 06:22:23'),
(104, 104, 0.08, '2026-01-25 06:22:23'),
(105, 105, 0.118, '2026-01-25 06:22:23'),
(106, 106, 0.4, '2026-01-25 06:22:23'),
(107, 107, 0.4, '2026-01-25 06:22:23'),
(108, 108, 1.65, '2026-01-25 06:22:23'),
(109, 109, 0.3, '2026-01-25 06:22:23'),
(110, 110, 0.3, '2026-01-25 06:22:23'),
(111, 111, 0.3, '2026-01-25 06:22:23'),
(112, 112, 0.4, '2026-01-25 06:22:23'),
(113, 113, 0.4, '2026-01-25 06:22:23'),
(114, 114, 0.316, '2026-01-25 06:22:23'),
(115, 115, 0.08, '2026-01-25 06:22:23'),
(116, 116, 9.6, '2026-01-25 06:22:23'),
(117, 117, 7.7, '2026-01-25 06:22:23'),
(118, 118, 8, '2026-01-25 06:22:23'),
(119, 119, 0.5, '2026-01-25 06:22:23'),
(120, 120, 0.5, '2026-01-25 06:22:23'),
(121, 121, 0.5, '2026-01-25 08:23:17'),
(122, 122, 0.013, '2026-01-25 06:22:23'),
(128, 130, 0.4, '2026-02-05 04:03:12'),
(129, 131, 0, '2026-02-05 07:14:40');

-- --------------------------------------------------------

--
-- Table structure for table `raw_material_stock`
--

CREATE TABLE `raw_material_stock` (
  `stock_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `initial_qty` int(11) NOT NULL,
  `qty_used` int(11) NOT NULL,
  `unit` varchar(25) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(62, 191, 54, 1, 115, '2026-02-07', '22:39:10'),
(63, 188, 54, 1, 12, '2026-02-07', '22:39:10');

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
(1, 'jabarte@my.cspc.edu.ph', 'Andrew', '', 'Rivera', 'staff', 'JanAnDroo', '$2y$12$N/Cdvn2LdthNHTUyn9dlROOMl5qYYY/P1cQMYJJb1L3y0KFF/LqD2', 'male', '2026-02-06', '09474652786', 1, '2026-02-02 12:57:53'),
(2, 'junaag@my.cspc.edu.ph', 'Julius', '', 'Naag', 'staff', 'Mizuto', '$2y$10$5BuIUfhjh8LLYvr//RyyTu1qCDlxqWBIgN24aM3KrLdBmQp7z6gfS', 'male', '2026-02-12', '09388702935', 1, '2026-02-05 06:34:27'),
(3, 'stnoblesala@my.cspc.edu.ph', 'Stephen Andrew', 'Cesista', 'Noblesala', 'owner', 'mamao', '', 'male', '2000-12-02', '', 1, '2026-02-07 11:33:33');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daily_stock`
--
ALTER TABLE `daily_stock`
  MODIFY `daily_stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `daily_stock_items`
--
ALTER TABLE `daily_stock_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT for table `distributions`
--
ALTER TABLE `distributions`
  MODIFY `distribution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `material_category`
--
ALTER TABLE `material_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `material_delivery`
--
ALTER TABLE `material_delivery`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `product_combined_recipes`
--
ALTER TABLE `product_combined_recipes`
  MODIFY `combined_recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `product_costs`
--
ALTER TABLE `product_costs`
  MODIFY `product_cost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `product_recipe`
--
ALTER TABLE `product_recipe`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=272;

--
-- AUTO_INCREMENT for table `raw_materials`
--
ALTER TABLE `raw_materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `raw_material_cost`
--
ALTER TABLE `raw_material_cost`
  MODIFY `cost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `raw_material_stock`
--
ALTER TABLE `raw_material_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

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
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
