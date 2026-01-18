-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Database: `engbakery`
-- inayos circular dependency sa raw materials
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Drop existing tables in correct order
-- --------------------------------------------------------

DROP TABLE IF EXISTS `daily_sales`;
DROP TABLE IF EXISTS `daily_stock_items`;
DROP TABLE IF EXISTS `daily_stock`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `product_costs`;
DROP TABLE IF EXISTS `product_recipe`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `material_delivery`;
DROP TABLE IF EXISTS `raw_material_cost`;
DROP TABLE IF EXISTS `raw_material_stock`;
DROP TABLE IF EXISTS `raw_materials`;
DROP TABLE IF EXISTS `material_category`;

-- --------------------------------------------------------
-- Table structure for table `material_category`
-- --------------------------------------------------------

CREATE TABLE `material_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `label` enum('drinks','bread','general') NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `raw_materials`
-- FIXED: Removed cost_id, stock_id, material_quantity
-- --------------------------------------------------------

CREATE TABLE `raw_materials` (
  `material_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`material_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `raw_materials_ibfk_1` FOREIGN KEY (`category_id`) 
    REFERENCES `material_category` (`category_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `raw_material_cost`
-- --------------------------------------------------------

CREATE TABLE `raw_material_cost` (
  `cost_id` int(11) NOT NULL AUTO_INCREMENT,
  `material_id` int(11) NOT NULL,
  `cost_per_unit` double NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cost_id`),
  KEY `material_id` (`material_id`),
  CONSTRAINT `raw_material_cost_ibfk_1` FOREIGN KEY (`material_id`) 
    REFERENCES `raw_materials` (`material_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `raw_material_stock`
-- --------------------------------------------------------

CREATE TABLE `raw_material_stock` (
  `stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `material_id` int(11) NOT NULL,
  `current_quantity` double NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`stock_id`),
  KEY `material_id` (`material_id`),
  CONSTRAINT `raw_material_stock_ibfk_1` FOREIGN KEY (`material_id`) 
    REFERENCES `raw_materials` (`material_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `material_delivery`
-- --------------------------------------------------------

CREATE TABLE `material_delivery` (
  `delivery_id` int(11) NOT NULL AUTO_INCREMENT,
  `material_id` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `unit` varchar(255) NOT NULL,
  `total_cost` double NOT NULL,
  `date_delivered` date NOT NULL,
  `time_delivered` time NOT NULL,
  PRIMARY KEY (`delivery_id`),
  KEY `material_id` (`material_id`),
  CONSTRAINT `material_delivery_ibfk_1` FOREIGN KEY (`material_id`) 
    REFERENCES `raw_materials` (`material_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `products`
-- FIXED: Removed recipe_id
-- --------------------------------------------------------

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category` enum('drinks','bread') NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` varchar(255) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `product_recipe`
-- --------------------------------------------------------

CREATE TABLE `product_recipe` (
  `recipe_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `quantity_needed` double NOT NULL,
  `unit` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`recipe_id`),
  KEY `product_id` (`product_id`),
  KEY `material_id` (`material_id`),
  CONSTRAINT `product_recipe_ibfk_1` FOREIGN KEY (`product_id`) 
    REFERENCES `products` (`product_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_recipe_ibfk_2` FOREIGN KEY (`material_id`) 
    REFERENCES `raw_materials` (`material_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `product_costs`
-- --------------------------------------------------------

CREATE TABLE `product_costs` (
  `product_cost_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `direct_cost` double NOT NULL,
  `overhead_cost_percentage` float NOT NULL,
  `overhead_cost_amount` double NOT NULL,
  `combined_recipe_cost` double NOT NULL,
  `profit_margin_percentage` float NOT NULL,
  `profit_amount` double NOT NULL,
  `total_cost` double NOT NULL,
  `selling_price` double NOT NULL,
  `selling_price_per_tray` double DEFAULT NULL,
  `selling_price_per_piece` double DEFAULT NULL,
  `yield_grams` double DEFAULT NULL,
  `trays_per_yield` int(11) DEFAULT NULL,
  `pieces_per_yield` int(11) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`product_cost_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_costs_ibfk_1` FOREIGN KEY (`product_id`) 
    REFERENCES `products` (`product_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `daily_stock`
-- --------------------------------------------------------

CREATE TABLE `daily_stock` (
  `daily_stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_date` date NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time DEFAULT NULL,
  PRIMARY KEY (`daily_stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `daily_stock_items`
-- ADDED: product_id FK
-- --------------------------------------------------------

CREATE TABLE `daily_stock_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `daily_stock_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `beginning_stock` int(11) NOT NULL,
  `pull_out_quantity` int(11) NOT NULL DEFAULT 0,
  `ending_stock` int(11) NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `daily_stock_id` (`daily_stock_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `daily_stock_items_ibfk_1` FOREIGN KEY (`daily_stock_id`) 
    REFERENCES `daily_stock` (`daily_stock_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `daily_stock_items_ibfk_2` FOREIGN KEY (`product_id`) 
    REFERENCES `products` (`product_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `daily_sales`
-- FIXED: References daily_stock_items instead of daily_stock
-- --------------------------------------------------------

CREATE TABLE `daily_sales` (
  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `quantity_sold` int(11) NOT NULL,
  `total_sales` double NOT NULL,
  `date_created` date NOT NULL,
  `time_created` time NOT NULL,
  PRIMARY KEY (`sale_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `daily_sales_ibfk_1` FOREIGN KEY (`item_id`) 
    REFERENCES `daily_stock_items` (`item_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `orders`
-- --------------------------------------------------------

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `total_payment_due` double NOT NULL,
  `amount_received` double NOT NULL,
  `amount_change` double NOT NULL,
  `payment_method` enum('cash','gcash','maya','credit card','debit card') DEFAULT NULL,
  `order_type` enum('walk-in','foodpanda') DEFAULT NULL,
  `date_created` date NOT NULL,
  `time_created` time NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `order_items`
-- FIXED: Renamed from order_item_id, fixed typo "amout" to "amount"
-- --------------------------------------------------------

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `cost_per_item` double NOT NULL,
  `total_cost_of_item` double NOT NULL,
  `date_created` date NOT NULL,
  `time_created` time NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `product_id` (`product_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`product_id`) 
    REFERENCES `products` (`product_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`order_id`) 
    REFERENCES `orders` (`order_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Insert sample data
-- --------------------------------------------------------

-- Insert material categories
INSERT INTO `material_category` (`category_id`, `category_name`, `description`, `label`, `date_created`) VALUES
(2, 'Raw Ingredients', '', 'general', current_timestamp()),
(6, 'Packaging', '', 'general', current_timestamp()),
(9, 'Office Supplies', '', 'general', current_timestamp()),
(10, 'Coffee Expenses', '', 'drinks', current_timestamp());

-- Insert raw materials (NO cost_id, stock_id, material_quantity)
INSERT INTO `raw_materials` (`material_id`, `category_id`, `material_name`, `unit`, `date_created`) VALUES
(3, 2, 'Flour - All purpose', 'grams', current_timestamp()),
(4, 2, 'Flour - Kutitap First Class', 'grams', current_timestamp()),
(5, 2, 'Sugar 99', 'grams', current_timestamp()),
(6, 2, 'Sugar Brown', 'grams', current_timestamp()),
(7, 2, 'Sugar White', 'grams', current_timestamp()),
(8, 6, 'Baking cups 2oz', 'pcs', current_timestamp()),
(9, 6, 'Paper plate', 'pcs', current_timestamp()),
(10, 6, 'Plastic Bag - Medium', 'pcs', current_timestamp());

-- Insert material costs
INSERT INTO `raw_material_cost` (`cost_id`, `material_id`, `cost_per_unit`, `date_created`) VALUES
(3, 3, 0.054, current_timestamp()),
(4, 4, 0.036, current_timestamp()),
(5, 5, 0.062, current_timestamp()),
(6, 6, 0.06, current_timestamp()),
(7, 7, 0.0716, current_timestamp()),
(8, 8, 0.065, current_timestamp()),
(9, 9, 0.91428571428571, current_timestamp()),
(10, 10, 0.83333333333333, current_timestamp());

-- Insert material stock
INSERT INTO `raw_material_stock` (`stock_id`, `material_id`, `current_quantity`, `last_updated`) VALUES
(3, 3, 25000, current_timestamp()),
(4, 4, 25000, current_timestamp()),
(5, 5, 50000, current_timestamp()),
(6, 6, 50000, current_timestamp()),
(7, 7, 50000, current_timestamp()),
(8, 8, 1200, current_timestamp()),
(9, 9, 35, current_timestamp()),
(10, 10, 240, current_timestamp());

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
```
