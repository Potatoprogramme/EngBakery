-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2026 at 08:37 AM
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
-- Table structure for table `daily_sales`
--

CREATE TABLE `daily_sales` (
  `sale_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity_sold` int(11) NOT NULL,
  `total_sales` double NOT NULL,
  `date_created` date NOT NULL,
  `time_created` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Raw Materials', 'Raw materials used for baking and food production', 'bread', '2026-01-25 06:22:23'),
(2, 'Packaging', 'Packaging materials for products', 'general', '2026-01-25 06:22:23'),
(3, 'Office Supplies', 'Office and administrative supplies', 'general', '2026-01-25 06:22:23'),
(4, 'Coffee Expenses', 'Materials and supplies for coffee drinks', 'drinks', '2026-01-25 06:22:23'),
(5, 'Overhead Expenses', '', 'general', '2026-01-25 06:39:12');

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
  `date_created` date NOT NULL,
  `time_created` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_item_id`
--

CREATE TABLE `order_item_id` (
  `order_item_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amout` int(11) NOT NULL,
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
  `category` enum('drinks','bread','dough') NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category`, `product_name`, `product_description`, `date_created`) VALUES
(1, 'dough', 'Soft Dough - 30g', '', '2026-01-25 06:26:13'),
(2, 'bread', 'Pandecoco', '', '2026-01-25 06:56:33');

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
(11, 2, 1, 30, 0.038425462459195, 207.49749727965, '2026-01-25 07:35:19');

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
(2, 2, 175.9, 0, 0, 207.49749727965, 0, 0, 175.9, 1260, 0, 7, 2550, 0, 180, 2550, 14.17, '2026-01-25 07:31:57');

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
(84, 1, 124, 430, 'grams', '2026-01-25 07:28:39'),
(85, 1, 44, 3, 'grams', '2026-01-25 07:28:39'),
(90, 2, 39, 1500, 'grams', '2026-01-25 07:35:19'),
(91, 2, 3, 750, 'grams', '2026-01-25 07:35:19'),
(92, 2, 125, 200, 'grams', '2026-01-25 07:35:19'),
(93, 2, 34, 100, 'grams', '2026-01-25 07:35:19');

-- --------------------------------------------------------

--
-- Table structure for table `raw_materials`
--

CREATE TABLE `raw_materials` (
  `material_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_id` varchar(45) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `material_quantity` int(11) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raw_materials`
--

INSERT INTO `raw_materials` (`material_id`, `category_id`, `product_id`, `material_name`, `material_quantity`, `unit`, `date_created`) VALUES
(1, 1, '', 'Flour - All Purpose', 0, 'grams', '2026-01-25 06:22:23'),
(2, 1, '', 'Flour - Kutitap First Class', 0, 'grams', '2026-01-25 06:22:23'),
(3, 1, '', 'Sugar 93', 0, 'grams', '2026-01-25 06:22:23'),
(4, 1, '', 'Sugar Brown', 0, 'grams', '2026-01-25 06:22:23'),
(5, 1, '', 'Sugar White', 0, 'grams', '2026-01-25 06:22:23'),
(6, 1, '', 'Sugar Powdered - Penco', 0, 'grams', '2026-01-25 06:22:23'),
(7, 1, '', 'Baking Powder - Ordinary', 0, 'grams', '2026-01-25 06:22:23'),
(8, 1, '', 'Baking Powder - Calumet', 0, 'grams', '2026-01-25 06:22:23'),
(9, 1, '', 'Cream of Tartar', 0, 'grams', '2026-01-25 06:22:23'),
(10, 1, '', 'JB-15', 0, 'grams', '2026-01-25 06:22:23'),
(11, 1, '', 'Cocoa Ordinary', 0, 'grams', '2026-01-25 06:22:23'),
(12, 1, '', 'Buttermilk', 0, 'grams', '2026-01-25 06:22:23'),
(13, 1, '', 'Vanilla', 0, 'grams', '2026-01-25 06:22:23'),
(14, 1, '', 'Salt', 0, 'grams', '2026-01-25 06:22:23'),
(15, 1, '', 'Canola Oil', 0, 'grams', '2026-01-25 06:22:23'),
(16, 1, '', 'Buttercup (per piece)', 0, 'pcs', '2026-01-25 06:22:23'),
(17, 1, '', 'Cheese - Magnolia (per piece)', 0, 'grams', '2026-01-25 06:22:23'),
(18, 1, '', 'Evaporated Milk - Evaporada (per can)', 0, 'grams', '2026-01-25 06:22:23'),
(19, 1, '', 'Condensed Milk - Condensada (per can)', 0, 'grams', '2026-01-25 06:22:23'),
(20, 1, '', 'Fresh Milk - (per box)', 0, 'grams', '2026-01-25 06:22:23'),
(21, 1, '', 'Cornstarch - Farola', 0, 'grams', '2026-01-25 06:22:23'),
(22, 1, '', 'Eggs (1tray)', 0, 'pcs', '2026-01-25 06:22:23'),
(23, 1, '', 'Food Color - Strawberry Red', 0, 'grams', '2026-01-25 06:22:23'),
(24, 1, '', 'Food Color - Ube', 0, 'grams', '2026-01-25 06:22:23'),
(25, 1, '', 'Food Color - Egg Yellow', 0, 'grams', '2026-01-25 06:22:23'),
(26, 1, '', 'Baking Soda', 0, 'grams', '2026-01-25 06:22:23'),
(27, 1, '', 'Cake Flour', 0, 'grams', '2026-01-25 06:22:23'),
(28, 1, '', 'Coffee', 0, 'grams', '2026-01-25 06:22:23'),
(29, 1, '', 'Palm Oil - Cooking Ordinary', 0, 'grams', '2026-01-25 06:22:23'),
(30, 1, '', 'Desicated Coconut', 0, 'grams', '2026-01-25 06:22:23'),
(31, 1, '', 'Emulsifier - Puratos Mixo', 0, 'grams', '2026-01-25 06:22:23'),
(32, 1, '', 'Lard - Approved', 0, 'grams', '2026-01-25 06:22:23'),
(33, 1, '', 'Macco - Puratos Preservative Bread', 0, 'grams', '2026-01-25 06:22:23'),
(34, 1, '', 'Margarine - Baker\'s Choice', 0, 'grams', '2026-01-25 06:22:23'),
(35, 1, '', 'Ube Paste', 0, 'grams', '2026-01-25 06:22:23'),
(36, 1, '', 'Monggo Paste', 0, 'grams', '2026-01-25 06:22:23'),
(37, 1, '', 'Onion', 0, 'grams', '2026-01-25 06:22:23'),
(38, 1, '', 'Peanut - Skinless', 0, 'grams', '2026-01-25 06:22:23'),
(39, 1, '', 'Niyog', 0, 'grams', '2026-01-25 06:22:23'),
(40, 1, '', 'Yeast - Angel Instant', 0, 'grams', '2026-01-25 06:22:23'),
(41, 1, '', 'Raisin', 0, 'grams', '2026-01-25 06:22:23'),
(42, 1, '', 'Yeast - Red Star Active Dry', 0, 'grams', '2026-01-25 06:22:23'),
(43, 1, '', 'Flour - Mayon Third Class', 0, 'grams', '2026-01-25 06:22:23'),
(44, 1, '', 'Improver - Toupan', 0, 'grams', '2026-01-25 06:22:23'),
(45, 1, '', 'Potassium Sorbate - Preservative Cake', 0, 'grams', '2026-01-25 06:22:23'),
(46, 1, '', 'Carbonato', 0, 'grams', '2026-01-25 06:22:23'),
(47, 1, '', 'Mayonnaise - Kewpie', 0, 'grams', '2026-01-25 06:22:23'),
(48, 1, '', 'Mayonnaise - All Purpose Dressing', 0, 'grams', '2026-01-25 06:22:23'),
(49, 1, '', 'Tuna - Century (per can)', 0, 'grams', '2026-01-25 06:22:23'),
(50, 1, '', 'Cinnamon Powder - HYCO (per can)', 0, 'grams', '2026-01-25 06:22:23'),
(51, 1, '', 'Creamcheese - Anchor', 0, 'grams', '2026-01-25 06:22:23'),
(52, 1, '', 'Rolled Oats', 0, 'grams', '2026-01-25 06:22:23'),
(53, 1, '', 'All Purpose Cream', 0, 'grams', '2026-01-25 06:22:23'),
(54, 1, '', 'Cheddar Cheese - Magnolia (per piece)', 0, 'grams', '2026-01-25 06:22:23'),
(55, 1, '', 'Hotdog', 0, 'pcs', '2026-01-25 06:22:23'),
(56, 1, '', 'Nutmeg - McCormick', 0, 'grams', '2026-01-25 06:22:23'),
(57, 1, '', 'Chocolate Bar - Fuji', 0, 'grams', '2026-01-25 06:22:23'),
(58, 1, '', 'Whip It - Whipping Cream', 0, 'grams', '2026-01-25 06:22:23'),
(59, 1, '', 'Sweet Ham - CDO Regular', 0, 'pcs', '2026-01-25 06:22:23'),
(60, 1, '', 'Butter - Magnolia (per piece)', 0, 'grams', '2026-01-25 06:22:23'),
(61, 1, '', 'Condensed Milk - Ube', 0, 'grams', '2026-01-25 06:22:23'),
(62, 1, '', 'Chocolate Drops', 0, 'grams', '2026-01-25 06:22:23'),
(63, 1, '', 'Chocolate Drops - White', 0, 'grams', '2026-01-25 06:22:23'),
(64, 1, '', 'Banana - Bungoran', 0, 'grams', '2026-01-25 06:22:23'),
(65, 1, '', 'Pineapple', 0, 'pcs', '2026-01-25 06:22:23'),
(66, 1, '', 'Walnuts', 0, 'grams', '2026-01-25 06:22:23'),
(67, 1, '', 'Almond', 0, 'grams', '2026-01-25 06:22:23'),
(68, 1, '', 'Marshmallow', 0, 'pcs', '2026-01-25 06:22:23'),
(69, 1, '', 'Matcha Powder', 0, 'grams', '2026-01-25 06:22:23'),
(70, 1, '', 'Ice', 0, 'grams', '2026-01-25 06:22:23'),
(71, 1, '', 'Glucose', 0, 'grams', '2026-01-25 06:22:23'),
(72, 1, '', 'Butter - Queensland', 0, 'grams', '2026-01-25 06:22:23'),
(73, 1, '', 'Walnuts - Chopped', 0, 'grams', '2026-01-25 06:22:23'),
(74, 2, '', 'Baking Cups - 2oz', 0, 'pcs', '2026-01-25 06:22:23'),
(75, 2, '', 'Boxes - 10x10x4 in', 0, 'pcs', '2026-01-25 06:22:23'),
(76, 2, '', 'Boxes - 9x9x2 in', 0, 'pcs', '2026-01-25 06:22:23'),
(77, 2, '', 'Paper Plate', 0, 'pcs', '2026-01-25 06:22:23'),
(78, 2, '', 'Brown Paper', 0, 'pcs', '2026-01-25 06:22:23'),
(79, 2, '', 'Cello Sheet - 9x13 in', 0, 'pcs', '2026-01-25 06:22:23'),
(80, 2, '', 'Gloves', 0, 'pcs', '2026-01-25 06:22:23'),
(81, 2, '', 'Plastic Bag - Medium', 0, 'pcs', '2026-01-25 06:22:23'),
(82, 2, '', 'Plastic Bag - Mini', 0, 'pcs', '2026-01-25 06:22:23'),
(83, 2, '', 'Plastic Bag - Tiny', 0, 'pcs', '2026-01-25 06:22:23'),
(84, 2, '', 'Plastic Labo - 8x11 in', 0, 'pcs', '2026-01-25 06:22:23'),
(85, 2, '', 'Plastic Labo - 10x14 in', 0, 'pcs', '2026-01-25 06:22:23'),
(86, 2, '', 'Supot No. 5', 0, 'pcs', '2026-01-25 06:22:23'),
(87, 2, '', 'Supot No. 3', 0, 'pcs', '2026-01-25 06:22:23'),
(88, 2, '', 'Custard Plastic - 5.5 cm', 0, 'pcs', '2026-01-25 06:22:23'),
(89, 2, '', 'Pastry Pouch - 11x13 cm', 0, 'pcs', '2026-01-25 06:22:23'),
(90, 2, '', 'Pastry Pouch - 10x13.5 cm', 0, 'pcs', '2026-01-25 06:22:23'),
(91, 2, '', 'Sliced Bread Clear - 30x34+10 cm', 0, 'pcs', '2026-01-25 06:22:23'),
(92, 2, '', 'Sliced Bread Opaque', 0, 'pcs', '2026-01-25 06:22:23'),
(93, 2, '', 'Ribbon Size 2 - 50m (1.6 per box)', 0, 'pcs', '2026-01-25 06:22:23'),
(94, 2, '', 'Supot No. 1', 0, 'pcs', '2026-01-25 06:22:23'),
(95, 2, '', 'Supot No. 2', 0, 'pcs', '2026-01-25 06:22:23'),
(96, 2, '', 'Baking Cups - 3 oz', 0, 'pcs', '2026-01-25 06:22:23'),
(97, 3, '', 'Tape', 0, 'pcs', '2026-01-25 06:22:23'),
(98, 3, '', 'Tagger', 0, 'pcs', '2026-01-25 06:22:23'),
(99, 3, '', 'Sticker Paper', 0, 'pcs', '2026-01-25 06:22:23'),
(100, 3, '', 'Receipt Duplicate - L (2 Copy 50 Pages)', 0, 'ream', '2026-01-25 06:22:23'),
(101, 3, '', 'Receipt Triplicate - L (3 Copy 60 Pages)', 0, 'ream', '2026-01-25 06:22:23'),
(102, 4, '', 'Coffee Beans', 0, 'grams', '2026-01-25 06:22:23'),
(103, 4, '', 'Sugar Syrup', 0, 'grams', '2026-01-25 06:22:23'),
(104, 4, '', 'Fresh Milk', 0, 'grams', '2026-01-25 06:22:23'),
(105, 4, '', 'Condensed Milk', 0, 'grams', '2026-01-25 06:22:23'),
(106, 4, '', 'Caramel Syrup', 0, 'grams', '2026-01-25 06:22:23'),
(107, 4, '', 'French Vanilla Syrup', 0, 'grams', '2026-01-25 06:22:23'),
(108, 4, '', 'Matcha Powder - Coffee', 0, 'grams', '2026-01-25 06:22:23'),
(109, 4, '', 'Matcha Powder - Frappe', 0, 'grams', '2026-01-25 06:22:23'),
(110, 4, '', 'Vanilla Powder', 0, 'grams', '2026-01-25 06:22:23'),
(111, 4, '', 'Choco Powder', 0, 'grams', '2026-01-25 06:22:23'),
(112, 4, '', 'Strawberry Syrup', 0, 'grams', '2026-01-25 06:22:23'),
(113, 4, '', 'Choco Syrup', 0, 'grams', '2026-01-25 06:22:23'),
(114, 4, '', 'Whipping Cream - Ever Whip', 0, 'grams', '2026-01-25 06:22:23'),
(115, 4, '', 'Tissue', 0, 'pcs', '2026-01-25 06:22:23'),
(116, 4, '', '12 oz Cup with Lid', 0, 'pcs', '2026-01-25 06:22:23'),
(117, 4, '', '16 oz Cup with Lid - Coffee', 0, 'pcs', '2026-01-25 06:22:23'),
(118, 4, '', '16 oz Cup with Lid - Frappe', 0, 'pcs', '2026-01-25 06:22:23'),
(119, 4, '', 'Straw Coffee', 0, 'pcs', '2026-01-25 06:22:23'),
(120, 4, '', 'Straw Frappe', 0, 'pcs', '2026-01-25 06:22:23'),
(121, 4, '', 'Stirrer', 0, 'pcs', '2026-01-25 06:22:23'),
(122, 4, '', 'Ice - Coffee', 0, 'grams', '2026-01-25 06:22:23'),
(124, 5, '', 'Water', 0, 'grams', '2026-01-25 06:41:38'),
(125, 1, '', 'Bread Crumbs', 0, 'grams', '2026-01-25 06:22:23');

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
(14, 14, 0.014, '2026-01-25 06:22:23'),
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
(32, 32, 0.091, '2026-01-25 06:22:23'),
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
(121, 121, 0.013, '2026-01-25 06:22:23'),
(122, 122, 0.013, '2026-01-25 06:22:23');

-- --------------------------------------------------------

--
-- Table structure for table `raw_material_stock`
--

CREATE TABLE `raw_material_stock` (
  `stock_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `current_quantity` double NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raw_material_stock`
--

INSERT INTO `raw_material_stock` (`stock_id`, `material_id`, `current_quantity`, `last_updated`) VALUES
(1, 1, 25000, '2026-01-25 06:22:23'),
(2, 2, 25000, '2026-01-25 06:22:23'),
(3, 3, 50000, '2026-01-25 06:22:23'),
(4, 4, 50000, '2026-01-25 06:22:23'),
(5, 5, 50000, '2026-01-25 06:22:23'),
(6, 6, 2272, '2026-01-25 06:22:23'),
(7, 7, 1000, '2026-01-25 06:22:23'),
(8, 8, 1000, '2026-01-25 06:22:23'),
(9, 9, 50, '2026-01-25 06:22:23'),
(10, 10, 500, '2026-01-25 06:22:23'),
(11, 11, 25000, '2026-01-25 06:22:23'),
(12, 12, 25000, '2026-01-25 06:22:23'),
(13, 13, 4000, '2026-01-25 06:22:23'),
(14, 14, 25000, '2026-01-25 06:22:23'),
(15, 15, 1000, '2026-01-25 06:22:23'),
(16, 16, 200, '2026-01-25 06:22:23'),
(17, 17, 165, '2026-01-25 06:22:23'),
(18, 18, 360, '2026-01-25 06:22:23'),
(19, 19, 390, '2026-01-25 06:22:23'),
(20, 20, 1000, '2026-01-25 06:22:23'),
(21, 21, 25000, '2026-01-25 06:22:23'),
(22, 22, 30, '2026-01-25 06:22:23'),
(23, 23, 454, '2026-01-25 06:22:23'),
(24, 24, 454, '2026-01-25 06:22:23'),
(25, 25, 454, '2026-01-25 06:22:23'),
(26, 26, 250, '2026-01-25 06:22:23'),
(27, 27, 25000, '2026-01-25 06:22:23'),
(28, 28, 100, '2026-01-25 06:22:23'),
(29, 29, 1000, '2026-01-25 06:22:23'),
(30, 30, 1000, '2026-01-25 06:22:23'),
(31, 31, 250, '2026-01-25 06:22:23'),
(32, 32, 36000, '2026-01-25 06:22:23'),
(33, 33, 1000, '2026-01-25 06:22:23'),
(34, 34, 36000, '2026-01-25 06:22:23'),
(35, 35, 1000, '2026-01-25 06:22:23'),
(36, 36, 1000, '2026-01-25 06:22:23'),
(37, 37, 1000, '2026-01-25 06:22:23'),
(38, 38, 1000, '2026-01-25 06:22:23'),
(39, 39, 1000, '2026-01-25 06:22:23'),
(40, 40, 500, '2026-01-25 06:22:23'),
(41, 41, 1000, '2026-01-25 06:22:23'),
(42, 42, 800, '2026-01-25 06:22:23'),
(43, 43, 25000, '2026-01-25 06:22:23'),
(44, 44, 1000, '2026-01-25 06:22:23'),
(45, 45, 1000, '2026-01-25 06:22:23'),
(46, 46, 250, '2026-01-25 06:22:23'),
(47, 47, 1000, '2026-01-25 06:22:23'),
(48, 48, 470, '2026-01-25 06:22:23'),
(49, 49, 140, '2026-01-25 06:22:23'),
(50, 50, 500, '2026-01-25 06:22:23'),
(51, 51, 1000, '2026-01-25 06:22:23'),
(52, 52, 500, '2026-01-25 06:22:23'),
(53, 53, 250, '2026-01-25 06:22:23'),
(54, 54, 165, '2026-01-25 06:22:23'),
(55, 55, 10, '2026-01-25 06:22:23'),
(56, 56, 30, '2026-01-25 06:22:23'),
(57, 57, 1000, '2026-01-25 06:22:23'),
(58, 58, 500, '2026-01-25 06:22:23'),
(59, 59, 8, '2026-01-25 06:22:23'),
(60, 60, 220, '2026-01-25 06:22:23'),
(61, 61, 390, '2026-01-25 06:22:23'),
(62, 62, 1000, '2026-01-25 06:22:23'),
(63, 63, 1000, '2026-01-25 06:22:23'),
(64, 64, 1000, '2026-01-25 06:22:23'),
(65, 65, 1, '2026-01-25 06:22:23'),
(66, 66, 250, '2026-01-25 06:22:23'),
(67, 67, 250, '2026-01-25 06:22:23'),
(68, 68, 680, '2026-01-25 06:22:23'),
(69, 69, 100, '2026-01-25 06:22:23'),
(70, 70, 2000, '2026-01-25 06:22:23'),
(71, 71, 750, '2026-01-25 06:22:23'),
(72, 72, 225, '2026-01-25 06:22:23'),
(73, 73, 250, '2026-01-25 06:22:23'),
(74, 74, 1200, '2026-01-25 06:22:23'),
(75, 75, 20, '2026-01-25 06:22:23'),
(76, 76, 20, '2026-01-25 06:22:23'),
(77, 77, 35, '2026-01-25 06:22:23'),
(78, 78, 10, '2026-01-25 06:22:23'),
(79, 79, 2000, '2026-01-25 06:22:23'),
(80, 80, 100, '2026-01-25 06:22:23'),
(81, 81, 240, '2026-01-25 06:22:23'),
(82, 82, 500, '2026-01-25 06:22:23'),
(83, 83, 500, '2026-01-25 06:22:23'),
(84, 84, 650, '2026-01-25 06:22:23'),
(85, 85, 650, '2026-01-25 06:22:23'),
(86, 86, 100, '2026-01-25 06:22:23'),
(87, 87, 100, '2026-01-25 06:22:23'),
(88, 88, 500, '2026-01-25 06:22:23'),
(89, 89, 500, '2026-01-25 06:22:23'),
(90, 90, 500, '2026-01-25 06:22:23'),
(91, 91, 500, '2026-01-25 06:22:23'),
(92, 92, 500, '2026-01-25 06:22:23'),
(93, 93, 31, '2026-01-25 06:22:23'),
(94, 94, 100, '2026-01-25 06:22:23'),
(95, 95, 100, '2026-01-25 06:22:23'),
(96, 96, 100, '2026-01-25 06:22:23'),
(97, 97, 1, '2026-01-25 06:22:23'),
(98, 98, 1, '2026-01-25 06:22:23'),
(99, 99, 100, '2026-01-25 06:22:23'),
(100, 100, 1, '2026-01-25 06:22:23'),
(101, 101, 1, '2026-01-25 06:22:23'),
(102, 102, 1000, '2026-01-25 06:22:23'),
(103, 103, 220, '2026-01-25 06:22:23'),
(104, 104, 1000, '2026-01-25 06:22:23'),
(105, 105, 390, '2026-01-25 06:22:23'),
(106, 106, 750, '2026-01-25 06:22:23'),
(107, 107, 750, '2026-01-25 06:22:23'),
(108, 108, 100, '2026-01-25 06:22:23'),
(109, 109, 1000, '2026-01-25 06:22:23'),
(110, 110, 1000, '2026-01-25 06:22:23'),
(111, 111, 1000, '2026-01-25 06:22:23'),
(112, 112, 750, '2026-01-25 06:22:23'),
(113, 113, 750, '2026-01-25 06:22:23'),
(114, 114, 1030, '2026-01-25 06:22:23'),
(115, 115, 1000, '2026-01-25 06:22:23'),
(116, 116, 1, '2026-01-25 06:22:23'),
(117, 117, 1, '2026-01-25 06:22:23'),
(118, 118, 1, '2026-01-25 06:22:23'),
(119, 119, 1, '2026-01-25 06:22:23'),
(120, 120, 1, '2026-01-25 06:22:23'),
(121, 121, 2000, '2026-01-25 06:22:23'),
(122, 122, 2000, '2026-01-25 06:22:23'),
(124, 124, 100000, '2026-01-25 06:22:23'),
(125, 125, 100000, '2026-01-25 06:22:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daily_sales`
--
ALTER TABLE `daily_sales`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `daily_sales_ibfk1_idx` (`item_id`);

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
-- Indexes for table `order_item_id`
--
ALTER TABLE `order_item_id`
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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daily_sales`
--
ALTER TABLE `daily_sales`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `material_category`
--
ALTER TABLE `material_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- AUTO_INCREMENT for table `order_item_id`
--
ALTER TABLE `order_item_id`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_combined_recipes`
--
ALTER TABLE `product_combined_recipes`
  MODIFY `combined_recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product_costs`
--
ALTER TABLE `product_costs`
  MODIFY `product_cost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_recipe`
--
ALTER TABLE `product_recipe`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `raw_materials`
--
ALTER TABLE `raw_materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `raw_material_cost`
--
ALTER TABLE `raw_material_cost`
  MODIFY `cost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `raw_material_stock`
--
ALTER TABLE `raw_material_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `daily_sales`
--
ALTER TABLE `daily_sales`
  ADD CONSTRAINT `daily_sales_ibfk1` FOREIGN KEY (`item_id`) REFERENCES `daily_stock_items` (`item_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `daily_stock_items`
--
ALTER TABLE `daily_stock_items`
  ADD CONSTRAINT `daily_stock_items_ibfk_1` FOREIGN KEY (`daily_stock_id`) REFERENCES `daily_stock` (`daily_stock_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `material_delivery`
--
ALTER TABLE `material_delivery`
  ADD CONSTRAINT `material_delivery_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `raw_materials` (`material_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_item_id`
--
ALTER TABLE `order_item_id`
  ADD CONSTRAINT `order_item_id_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_item_id_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
