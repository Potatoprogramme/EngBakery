-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2026 at 01:10 PM
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
  `time_start` time DEFAULT NULL,
  `time_end` time DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL,
  `report_sent` tinyint(1) NOT NULL,
  `is_remitted` tinyint(1) NOT NULL DEFAULT 0,
  `report_sent_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_stock`
--

INSERT INTO `daily_stock` (`daily_stock_id`, `inventory_date`, `time_start`, `time_end`, `is_closed`, `report_sent`, `is_remitted`, `report_sent_at`) VALUES
(26, '2026-03-27', '06:00:00', '22:57:58', 1, 1, 0, '2026-03-27 22:57:58'),
(29, '2026-03-27', '06:00:00', '20:00:00', 0, 0, 0, NULL),
(53, '2026-03-28', '23:24:50', '23:25:35', 1, 1, 0, '2026-03-28 23:25:35'),
(54, '2026-03-28', '23:25:41', NULL, 1, 0, 0, NULL),
(58, '2026-03-29', '23:18:28', NULL, 0, 0, 0, NULL),
(59, '2026-03-30', '19:55:08', '20:02:35', 1, 1, 0, '2026-03-30 20:02:35'),
(60, '2026-03-30', '20:02:52', NULL, 0, 0, 0, NULL),
(61, '2026-03-31', '22:31:10', NULL, 0, 0, 0, NULL),
(62, '2026-04-01', '21:49:01', NULL, 0, 0, 0, '2026-04-01 22:28:02'),
(69, '2026-04-02', '18:33:10', '18:33:20', 1, 1, 0, '2026-04-02 18:33:20');

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
  `ending_stock` int(11) NOT NULL,
  `distribution_qty` int(11) DEFAULT 0,
  `is_enabled` tinyint(1) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_stock_items`
--

INSERT INTO `daily_stock_items` (`item_id`, `daily_stock_id`, `product_id`, `beginning_stock`, `pull_out_quantity`, `ending_stock`, `distribution_qty`, `is_enabled`, `notes`) VALUES
(175, 26, 14, 10, 2, 8, 10, 1, ''),
(176, 26, 1, 0, 0, 0, 0, 1, NULL),
(177, 26, 2, 0, 0, 0, 0, 1, NULL),
(178, 26, 3, 0, 0, 0, 0, 1, NULL),
(179, 26, 4, 0, 0, 0, 0, 1, NULL),
(180, 26, 5, 0, 0, 0, 0, 1, NULL),
(193, 29, 14, 10, 0, 10, 10, 0, NULL),
(194, 29, 1, 0, 0, 0, 0, 1, NULL),
(195, 29, 2, 0, 0, 0, 0, 1, NULL),
(196, 29, 3, 0, 0, 0, 0, 1, NULL),
(197, 29, 4, 0, 0, 0, 0, 1, NULL),
(198, 29, 5, 0, 0, 0, 0, 1, NULL),
(392, 53, 14, 20, 0, 15, 10, 1, 'fD'),
(393, 53, 1, 0, 0, 0, 0, 1, NULL),
(394, 53, 2, 0, 0, 0, 0, 1, NULL),
(395, 53, 3, 0, 0, 0, 0, 1, NULL),
(396, 53, 4, 0, 0, 0, 0, 1, NULL),
(397, 53, 5, 0, 0, 0, 0, 1, NULL),
(398, 54, 14, 25, 0, 25, 20, 1, ''),
(399, 54, 1, 0, 0, 0, 0, 1, NULL),
(400, 54, 2, 0, 0, 0, 0, 1, NULL),
(401, 54, 3, 0, 0, 0, 0, 1, NULL),
(402, 54, 4, 0, 0, 0, 0, 1, NULL),
(403, 54, 5, 0, 0, 0, 0, 1, NULL),
(437, 58, 1, 0, 0, 0, 0, 1, NULL),
(438, 58, 2, 0, 0, 0, 0, 1, NULL),
(439, 58, 3, 0, 0, 0, 0, 1, NULL),
(440, 58, 4, 0, 0, 0, 0, 1, NULL),
(441, 58, 5, 0, 0, 0, 0, 1, NULL),
(442, 58, 7, 0, 0, 0, 0, 0, NULL),
(443, 58, 8, 0, 0, 0, 0, 1, NULL),
(444, 58, 11, 0, 0, 0, 0, 0, NULL),
(445, 58, 12, 0, 0, 0, 0, 0, NULL),
(446, 58, 13, 0, 0, 0, 0, 0, NULL),
(447, 58, 14, 25, 0, 25, 0, 1, NULL),
(448, 59, 1, 0, 0, 0, 0, 0, NULL),
(449, 59, 2, 0, 0, 0, 0, 0, NULL),
(450, 59, 3, 0, 0, 0, 0, 0, NULL),
(451, 59, 4, 0, 0, 0, 0, 0, NULL),
(452, 59, 5, 0, 0, 0, 0, 0, NULL),
(453, 59, 7, 0, 0, 0, 0, 1, NULL),
(454, 59, 8, 0, 0, 0, 0, 0, NULL),
(455, 59, 11, 0, 0, 0, 0, 0, NULL),
(456, 59, 12, 0, 0, 0, 0, 0, NULL),
(457, 59, 13, 0, 0, 0, 0, 1, NULL),
(458, 59, 14, 25, 2, 22, 0, 1, ''),
(459, 60, 1, 0, 0, 0, 0, 0, NULL),
(460, 60, 2, 0, 0, 0, 0, 0, NULL),
(461, 60, 3, 0, 0, 0, 0, 0, NULL),
(462, 60, 4, 0, 0, 0, 0, 0, NULL),
(463, 60, 5, 0, 0, 0, 0, 0, NULL),
(464, 60, 7, 0, 0, 0, 0, 1, NULL),
(465, 60, 8, 0, 0, 0, 0, 0, NULL),
(466, 60, 11, 0, 0, 0, 0, 0, NULL),
(467, 60, 12, 0, 0, 0, 0, 0, NULL),
(468, 60, 13, 0, 0, 0, 0, 1, NULL),
(469, 60, 14, 22, 0, 22, 0, 1, NULL),
(470, 61, 1, 0, 0, 0, 0, 0, NULL),
(471, 61, 2, 0, 0, 0, 0, 0, NULL),
(472, 61, 3, 0, 0, 0, 0, 0, NULL),
(473, 61, 4, 0, 0, 0, 0, 0, NULL),
(474, 61, 5, 0, 0, 0, 0, 0, NULL),
(475, 61, 7, 0, 0, 0, 0, 0, NULL),
(476, 61, 8, 0, 0, 0, 0, 0, NULL),
(477, 61, 11, 0, 0, 0, 0, 0, NULL),
(478, 61, 12, 0, 0, 0, 0, 0, NULL),
(479, 61, 13, 0, 0, 0, 0, 0, NULL),
(480, 61, 14, 22, 0, 21, 0, 0, NULL),
(481, 62, 1, 0, 0, 0, 0, 1, NULL),
(482, 62, 2, 0, 0, 0, 0, 1, NULL),
(483, 62, 3, 0, 0, 0, 0, 1, NULL),
(484, 62, 4, 0, 0, 0, 0, 0, NULL),
(485, 62, 5, 0, 0, 0, 0, 1, NULL),
(486, 62, 7, 0, 0, 0, 0, 1, NULL),
(487, 62, 8, 0, 0, 0, 0, 1, NULL),
(488, 62, 11, 0, 0, 0, 0, 0, NULL),
(489, 62, 12, 0, 0, 0, 0, 0, NULL),
(490, 62, 13, 0, 0, 0, 0, 1, NULL),
(491, 62, 14, 21, 0, 20, 0, 1, NULL),
(492, 63, 1, 0, 0, 0, 0, 0, NULL),
(493, 63, 2, 0, 0, 0, 0, 0, NULL),
(494, 63, 3, 0, 0, 0, 0, 0, NULL),
(495, 63, 4, 0, 0, 0, 0, 0, NULL),
(496, 63, 5, 0, 0, 0, 0, 0, NULL),
(497, 63, 7, 0, 0, 0, 0, 0, NULL),
(498, 63, 8, 0, 0, 0, 0, 0, NULL),
(499, 63, 11, 0, 0, 0, 0, 0, NULL),
(500, 63, 12, 0, 0, 0, 0, 0, NULL),
(501, 63, 13, 0, 0, 0, 0, 0, NULL),
(502, 63, 14, 20, 0, 15, 0, 1, NULL),
(503, 64, 1, 0, 0, 0, 0, 0, NULL),
(504, 64, 2, 0, 0, 0, 0, 0, NULL),
(505, 64, 3, 0, 0, 0, 0, 0, NULL),
(506, 64, 4, 0, 0, 0, 0, 0, NULL),
(507, 64, 5, 0, 0, 0, 0, 0, NULL),
(508, 64, 7, 0, 0, 0, 0, 0, NULL),
(509, 64, 8, 0, 0, 0, 0, 0, NULL),
(510, 64, 11, 0, 0, 0, 0, 0, NULL),
(511, 64, 12, 0, 0, 0, 0, 0, NULL),
(512, 64, 13, 0, 0, 0, 0, 0, NULL),
(513, 64, 14, 15, 0, 15, 0, 1, NULL),
(525, 66, 1, 0, 0, 0, 0, 0, NULL),
(526, 66, 2, 0, 0, 0, 0, 0, NULL),
(527, 66, 3, 0, 0, 0, 0, 0, NULL),
(528, 66, 4, 0, 0, 0, 0, 0, NULL),
(529, 66, 5, 0, 0, 0, 0, 0, NULL),
(530, 66, 7, 0, 0, 0, 0, 0, NULL),
(531, 66, 8, 0, 0, 0, 0, 0, NULL),
(532, 66, 11, 0, 0, 0, 0, 0, NULL),
(533, 66, 12, 0, 0, 0, 0, 0, NULL),
(534, 66, 13, 0, 0, 0, 0, 0, NULL),
(535, 66, 14, 20, 0, 20, 0, 1, NULL),
(536, 67, 1, 0, 0, 0, 0, 0, NULL),
(537, 67, 2, 0, 0, 0, 0, 0, NULL),
(538, 67, 3, 0, 0, 0, 0, 0, NULL),
(539, 67, 4, 0, 0, 0, 0, 0, NULL),
(540, 67, 5, 0, 0, 0, 0, 0, NULL),
(541, 67, 7, 0, 0, 0, 0, 0, NULL),
(542, 67, 8, 0, 0, 0, 0, 0, NULL),
(543, 67, 11, 0, 0, 0, 0, 0, NULL),
(544, 67, 12, 0, 0, 0, 0, 0, NULL),
(545, 67, 13, 0, 0, 0, 0, 0, NULL),
(546, 67, 14, 20, 0, 20, 0, 1, NULL),
(547, 68, 1, 0, 0, 0, 0, 0, NULL),
(548, 68, 2, 0, 0, 0, 0, 0, NULL),
(549, 68, 3, 0, 0, 0, 0, 0, NULL),
(550, 68, 4, 0, 0, 0, 0, 0, NULL),
(551, 68, 5, 0, 0, 0, 0, 0, NULL),
(552, 68, 7, 0, 0, 0, 0, 0, NULL),
(553, 68, 8, 0, 0, 0, 0, 0, NULL),
(554, 68, 11, 0, 0, 0, 0, 0, NULL),
(555, 68, 12, 0, 0, 0, 0, 0, NULL),
(556, 68, 13, 0, 0, 0, 0, 0, NULL),
(557, 68, 14, 20, 0, 20, 0, 1, NULL),
(558, 69, 1, 0, 0, 0, 0, 0, NULL),
(559, 69, 2, 0, 0, 0, 0, 0, NULL),
(560, 69, 3, 0, 0, 0, 0, 0, NULL),
(561, 69, 4, 0, 0, 0, 0, 0, NULL),
(562, 69, 5, 0, 0, 0, 0, 0, NULL),
(563, 69, 7, 0, 0, 0, 0, 0, NULL),
(564, 69, 8, 0, 0, 0, 0, 0, NULL),
(565, 69, 11, 0, 0, 0, 0, 0, NULL),
(566, 69, 12, 0, 0, 0, 0, 0, NULL),
(567, 69, 13, 0, 0, 0, 0, 0, NULL),
(568, 69, 14, 20, 0, 20, 0, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `distribution_group`
--

CREATE TABLE `distribution_group` (
  `id` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `distribution_date` date NOT NULL,
  `distributed_to_note` varchar(500) DEFAULT NULL,
  `forecasted_sales` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `distribution_group`
--

INSERT INTO `distribution_group` (`id`, `title`, `distribution_date`, `distributed_to_note`, `forecasted_sales`, `total_cost`, `created_at`, `updated_at`) VALUES
(2, 'Group 1', '2026-03-15', 'asdasd', 70.02, 38.74, '2026-03-15 10:27:53', '2026-03-15 16:47:45'),
(3, 'Group 2', '2026-03-15', 'asdasd', 45.00, 24.42, '2026-03-15 11:12:18', '2026-03-15 16:48:14'),
(4, 'Test Group 1', '2026-03-16', 'Something', 0.00, 0.00, '2026-03-15 16:06:28', '2026-03-15 16:09:35'),
(5, 'Group 2', '2026-03-16', 'Deliver to store 1', 31.02, 15.85, '2026-03-16 19:29:02', '2026-03-16 22:25:30'),
(6, 'Group 3', '2026-03-16', 'Deliver to store 2', 184.00, 96.02, '2026-03-16 19:29:25', '2026-03-16 22:25:39'),
(7, 'test', '2026-03-17', 'asdasd', 6.00, 1.53, '2026-03-16 21:37:52', '2026-03-16 21:37:52'),
(9, 'Group 4', '2026-03-16', 'Deliver store 3', 25.02, 14.32, '2026-03-18 20:39:36', '2026-03-18 20:39:36'),
(11, 'something', '2026-03-20', NULL, 360.00, 172.64, '2026-03-20 11:27:54', '2026-03-21 23:42:27'),
(14, 'Group 1', '2026-03-21', NULL, 225.00, 142.49, '2026-03-21 23:28:11', '2026-03-21 23:28:11'),
(15, 'Group 1', '2026-03-20', NULL, 300.00, 71.60, '2026-03-21 23:38:51', '2026-03-21 23:41:13'),
(16, 'Group 1', '2026-03-25', NULL, 300.00, 71.60, '2026-03-25 21:31:47', '2026-03-25 21:31:47'),
(17, 'Group 1', '2026-03-27', NULL, 300.00, 158.32, '2026-03-27 22:19:33', '2026-03-27 22:19:33'),
(18, 'Group 1', '2026-03-28', NULL, 600.00, 316.64, '2026-03-28 18:01:30', '2026-03-28 18:01:30');

-- --------------------------------------------------------

--
-- Table structure for table `distribution_item`
--

CREATE TABLE `distribution_item` (
  `id` int(11) NOT NULL,
  `distribution_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_qnty` int(11) NOT NULL DEFAULT 0,
  `qty_mode` enum('batch','box','pieces') NOT NULL DEFAULT 'batch',
  `inventory_amount_used` decimal(12,4) NOT NULL DEFAULT 0.0000,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `distribution_item`
--

INSERT INTO `distribution_item` (`id`, `distribution_id`, `product_id`, `product_qnty`, `qty_mode`, `inventory_amount_used`, `created_at`, `updated_at`) VALUES
(8, 2, 13, 2, 'pieces', 20.0000, '2026-03-15 16:47:45', '2026-03-15 16:47:45'),
(9, 2, 7, 1, 'batch', 155.0000, '2026-03-15 16:47:45', '2026-03-15 16:47:45'),
(10, 3, 7, 1, 'batch', 155.0000, '2026-03-15 16:48:14', '2026-03-15 16:48:14'),
(11, 5, 13, 2, 'pieces', 20.0000, '2026-03-16 19:29:02', '2026-03-16 19:29:02'),
(12, 5, 7, 1, 'pieces', 9.6875, '2026-03-16 19:29:02', '2026-03-16 19:29:02'),
(13, 6, 13, 10, 'batch', 100.0000, '2026-03-16 19:29:25', '2026-03-16 19:29:25'),
(14, 6, 7, 1, 'batch', 155.0000, '2026-03-16 19:29:25', '2026-03-16 19:29:25'),
(15, 7, 7, 1, 'pieces', 9.6875, '2026-03-16 21:37:52', '2026-03-16 21:37:52'),
(17, 9, 13, 2, 'pieces', 20.0000, '2026-03-18 20:39:36', '2026-03-18 20:39:36'),
(24, 11, 14, 10, 'pieces', 250.5000, '2026-03-21 23:17:58', '2026-03-21 23:17:58'),
(26, 14, 14, 3, 'box', 225.4500, '2026-03-21 23:28:11', '2026-03-21 23:28:11'),
(28, 15, 13, 10, 'batch', 100.0000, '2026-03-21 23:41:13', '2026-03-21 23:41:13'),
(29, 11, 13, 2, 'batch', 20.0000, '2026-03-21 23:42:27', '2026-03-21 23:42:27'),
(30, 16, 13, 10, 'batch', 100.0000, '2026-03-25 21:31:47', '2026-03-25 21:31:47'),
(31, 17, 14, 10, 'pieces', 250.5000, '2026-03-27 22:19:33', '2026-03-27 22:19:33'),
(32, 18, 14, 20, 'pieces', 501.0000, '2026-03-28 18:01:30', '2026-03-28 18:01:30');

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
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `target_roles` varchar(50) NOT NULL DEFAULT 'owner,admin',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('low_stock','missed_remittance','distribution','system','approval','order','inventory','product','raw_material','remittance','user_approval') NOT NULL DEFAULT 'system',
  `level` enum('info','warning','critical') NOT NULL DEFAULT 'info',
  `action_url` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `reference_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `target_roles`, `title`, `message`, `type`, `level`, `action_url`, `is_read`, `read_at`, `reference_id`, `reference_type`, `created_at`, `expires_at`) VALUES
(2, NULL, 'owner,admin,staff', 'test1 — 0.0% remaining', 'test1 (Raw Materials) is at 1 grams (0.0% of initial stock). Please restock soon.', 'low_stock', 'critical', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_critical', '2026-03-06 14:21:20', '2026-03-06 23:59:59'),
(3, NULL, 'owner,admin,staff', 'No distribution for today (Mar 06, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-06', '2026-03-06 14:21:20', '2026-03-06 23:59:59'),
(4, NULL, 'owner,admin,staff', 'Wallnuts — 0.0% remaining', 'Wallnuts (Raw Materials - Bread) is at 0 grams (0.0% of initial stock). Please restock soon.', 'low_stock', 'critical', 'http://localhost:8080/MaterialStock', 0, NULL, 338, 'raw_material_critical', '2026-03-06 14:26:48', '2026-03-06 23:59:59'),
(6, NULL, 'owner,admin,staff', 'Canola Oil — 0.0% remaining', 'Canola Oil (Raw Materials - Bread) is at 0 grams (0.0% of initial stock). Please restock soon.', 'low_stock', 'critical', 'http://localhost:8080/MaterialStock', 0, NULL, 280, 'raw_material_critical', '2026-03-06 14:48:43', '2026-03-06 23:59:59'),
(8, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 06, 2026 has been created with 10 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-06', '2026-03-06 14:54:31', '2026-03-06 23:59:59'),
(9, NULL, 'owner,admin,staff', 'Order #2 Voided', 'Order #2 worth ₱12.51 has been voided. Stock has been restored.', 'order', 'critical', 'http://localhost:8080/Orders', 0, NULL, 2, 'order_voided', '2026-03-06 14:57:07', NULL),
(10, NULL, 'owner,admin,staff', 'Order #3 Voided', 'Order #3 worth ₱12.51 has been voided. Stock has been restored.', 'order', 'critical', 'http://localhost:8080/Orders', 0, NULL, 3, 'order_voided', '2026-03-06 15:29:36', NULL),
(11, NULL, 'owner,admin,staff', 'Canola Oil — 0.0% remaining', 'Canola Oil (Raw Materials - Bread) is at 0 grams (0.0% of initial stock). Please restock soon.', 'low_stock', 'critical', 'http://localhost:8080/MaterialStock', 0, NULL, 280, 'raw_material_critical', '2026-03-07 05:12:03', '2026-03-07 23:59:59'),
(12, NULL, 'owner,admin,staff', 'test1 — 0.0% remaining', 'test1 (Raw Materials) is at 0 grams (0.0% of initial stock). Please restock soon.', 'low_stock', 'critical', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_critical', '2026-03-07 05:12:03', '2026-03-07 23:59:59'),
(13, NULL, 'owner,admin,staff', 'Wallnuts — 20.0% remaining', 'Wallnuts (Raw Materials - Bread) is at 50 grams (20.0% of initial stock). Please restock soon.', 'low_stock', 'critical', 'http://localhost:8080/MaterialStock', 0, NULL, 338, 'raw_material_critical', '2026-03-07 05:12:03', '2026-03-07 23:59:59'),
(14, NULL, 'owner,admin', 'No distribution for today (Mar 07, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-07', '2026-03-07 05:12:03', '2026-03-07 23:59:59'),
(15, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 07, 2026 has been created with 10 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-07', '2026-03-07 05:12:30', '2026-03-07 23:59:59'),
(16, NULL, 'owner,admin', 'Inventory Deleted for Mar 07, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 07, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-07', '2026-03-07 05:12:43', NULL),
(17, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 07, 2026 has been created with 10 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-07', '2026-03-07 05:13:23', '2026-03-07 23:59:59'),
(18, NULL, 'owner,admin', 'Inventory Deleted for Mar 07, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 07, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-07', '2026-03-07 05:15:25', NULL),
(19, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 07, 2026 has been created with 10 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-07', '2026-03-07 05:17:45', '2026-03-07 23:59:59'),
(20, NULL, 'owner,admin', 'Inventory Deleted for Mar 07, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 07, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-07', '2026-03-07 05:19:49', NULL),
(21, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 07, 2026 has been created with 10 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-07', '2026-03-07 05:20:16', '2026-03-07 23:59:59'),
(22, NULL, 'owner,admin', 'Inventory Deleted for Mar 07, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 07, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-07', '2026-03-07 05:20:25', NULL),
(23, NULL, 'owner,admin,staff', 'Distribution: test × 10', '10 unit(s) of test were distributed for Mar 07, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-07 05:20:57', '2026-03-07 23:59:59'),
(24, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 07, 2026 has been created with 10 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-07', '2026-03-07 05:21:27', '2026-03-07 23:59:59'),
(25, NULL, 'owner,admin', 'Inventory Deleted for Mar 07, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 07, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-07', '2026-03-07 05:22:18', NULL),
(26, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 07, 2026 has been created with 10 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-07', '2026-03-07 05:22:40', '2026-03-07 23:59:59'),
(27, NULL, 'owner,admin', 'Order #5 Voided', 'Order #5 worth ₱12.51 has been voided. Stock has been restored.', 'order', 'critical', 'http://localhost:8080/Orders', 0, NULL, 5, 'order_voided', '2026-03-07 09:12:53', NULL),
(28, NULL, 'owner,admin', 'Missed Remittance — Mar 07, 2026', 'There were 2 order(s) on Mar 07, 2026 but no remittance has been filed. Please follow up with the cashier on duty.', 'missed_remittance', 'warning', 'http://localhost:8080/Sales', 0, NULL, NULL, 'date_2026-03-07', '2026-03-08 03:04:26', NULL),
(29, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 08, 2026 has been created with 10 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-08', '2026-03-08 03:14:27', '2026-03-08 23:59:59'),
(30, NULL, 'owner,admin', 'Wallnuts — 32.0% remaining', 'Wallnuts (Raw Materials - Bread) is at 80 grams (32.0% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 338, 'raw_material_warning', '2026-03-08 03:15:55', '2026-03-08 23:59:59'),
(31, NULL, 'owner,admin,staff', 'Distribution: test × 10', '10 unit(s) of test were distributed for Mar 08, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-08 03:24:43', '2026-03-08 23:59:59'),
(32, NULL, 'owner,admin', 'Order #8 Voided', 'Order #8 worth ₱12.51 has been voided. Stock has been restored.', 'order', 'critical', 'http://localhost:8080/Orders', 0, NULL, 8, 'order_voided', '2026-03-08 05:21:20', NULL),
(33, NULL, 'owner,admin', 'Order #12 Voided', 'Order #12 worth ₱12.51 has been voided. Stock has been restored.', 'order', 'critical', 'http://localhost:8080/Orders', 0, NULL, 12, 'order_voided', '2026-03-08 06:25:55', NULL),
(34, NULL, 'owner,admin', 'Short Remittance — ₱75.06', 'Cashier Stephen Cesista Noblesala filed a remittance for Mar 08, 2026 with a shortage of ₱75.06. Please review.', 'missed_remittance', 'critical', 'http://localhost:8080/Sales/RemittanceHistory', 0, NULL, 1, 'short_remittance', '2026-03-08 12:08:29', NULL),
(35, NULL, 'owner,admin', 'Remittance Filed — ₱87.57', 'Cashier Stephen Noblesala filed a remittance for Mar 08, 2026. Total sales: ₱87.57.', 'remittance', 'info', 'http://localhost:8080/Sales', 0, NULL, 1, 'remittance_filed', '2026-03-08 12:08:29', NULL),
(36, NULL, 'owner,admin', 'No distribution for today (Mar 12, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-12', '2026-03-12 14:16:27', '2026-03-12 23:59:59'),
(37, NULL, 'owner,admin', 'Missed Remittance — Mar 12, 2026', 'There were 10 order(s) on Mar 12, 2026 but no remittance has been filed. Please follow up with the cashier on duty.', 'missed_remittance', 'warning', 'http://localhost:8080/Sales', 0, NULL, NULL, 'date_2026-03-12', '2026-03-13 13:58:38', NULL),
(38, NULL, 'owner,admin', 'No distribution for today (Mar 13, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-13', '2026-03-13 13:58:38', '2026-03-13 23:59:59'),
(39, NULL, 'owner,admin', 'No distribution for today (Mar 14, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-14', '2026-03-14 05:45:16', '2026-03-14 23:59:59'),
(40, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 14, 2026 has been created with 10 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-14', '2026-03-14 07:10:13', '2026-03-14 23:59:59'),
(41, NULL, 'owner,admin,staff', 'Distribution: test1 × 10', '10 unit(s) of test1 were distributed for Mar 14, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-14 13:23:59', '2026-03-14 23:59:59'),
(42, NULL, 'owner,admin,staff', 'Distribution: test × 10', '10 unit(s) of test were distributed for Mar 14, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-14 13:23:59', '2026-03-14 23:59:59'),
(43, NULL, 'owner,admin,staff', 'Distribution: Iced Americano × 1', '1 unit(s) of Iced Americano were distributed for Mar 14, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-14 13:24:45', '2026-03-14 23:59:59'),
(44, NULL, 'owner,admin', 'Missed Remittance — Mar 14, 2026', 'There were 1 order(s) on Mar 14, 2026 but no remittance has been filed. Please follow up with the cashier on duty.', 'missed_remittance', 'warning', 'http://localhost:8080/Sales', 0, NULL, NULL, 'date_2026-03-14', '2026-03-14 22:29:05', NULL),
(45, NULL, 'owner,admin,staff', 'Distribution: test × 10', '10 unit(s) of test were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-14 22:48:30', '2026-03-15 23:59:59'),
(46, NULL, 'owner,admin,staff', 'Distribution: Soft Dough × 10', '10 unit(s) of Soft Dough were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-14 23:00:12', '2026-03-15 23:59:59'),
(47, NULL, 'owner,admin,staff', 'Distribution: test1 × 10', '10 unit(s) of test1 were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 00:30:06', '2026-03-15 23:59:59'),
(48, NULL, 'owner,admin,staff', 'Distribution: test1 × 10', '10 unit(s) of test1 were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 00:30:45', '2026-03-15 23:59:59'),
(49, NULL, 'owner,admin,staff', 'Distribution: test × 10', '10 unit(s) of test were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 00:32:07', '2026-03-15 23:59:59'),
(50, NULL, 'owner,admin,staff', 'Distribution: test1 × 10', '10 unit(s) of test1 were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 01:14:04', '2026-03-15 23:59:59'),
(51, NULL, 'owner,admin,staff', 'Distribution: Soft Dough × 10', '10 unit(s) of Soft Dough were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 01:15:04', '2026-03-15 23:59:59'),
(52, NULL, 'owner,admin,staff', 'Distribution: Soft Dough × 10', '10 unit(s) of Soft Dough were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 01:16:06', '2026-03-15 23:59:59'),
(53, NULL, 'owner,admin,staff', 'Distribution: Soft Dough × 10', '10 unit(s) of Soft Dough were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 01:16:39', '2026-03-15 23:59:59'),
(54, NULL, 'owner,admin,staff', 'Distribution: test1 × 10', '10 unit(s) of test1 were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 01:17:31', '2026-03-15 23:59:59'),
(55, NULL, 'owner,admin,staff', 'Distribution: test × 10', '10 unit(s) of test were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 01:28:48', '2026-03-15 23:59:59'),
(56, NULL, 'owner,admin,staff', 'Distribution: Soft Dough × 10', '10 unit(s) of Soft Dough were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 01:35:42', '2026-03-15 23:59:59'),
(57, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 01:49:50', NULL),
(58, NULL, 'owner,admin,staff', 'Distribution: Soft Dough × 10', '10 unit(s) of Soft Dough were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 01:52:10', '2026-03-15 23:59:59'),
(59, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 01:52:46', NULL),
(60, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 01:55:24', NULL),
(61, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 01:56:31', NULL),
(62, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 15, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-15', '2026-03-15 01:58:05', '2026-03-15 23:59:59'),
(63, NULL, 'owner,admin,staff', 'Distribution: test1 × 10', '10 unit(s) of test1 were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 01:58:53', '2026-03-15 23:59:59'),
(64, NULL, 'owner,admin', 'test1 — 37.6% remaining', 'test1 (Raw Materials) is at 752.69 grams (37.6% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-15 01:58:53', '2026-03-15 23:59:59'),
(65, NULL, 'owner,admin', 'Distribution Deleted: Soft Dough × 10', 'Distribution of 10 unit(s) of Soft Dough for Mar 15, 2026 was deleted. Raw materials have been restored.', 'distribution', 'warning', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_deleted', '2026-03-15 02:04:36', NULL),
(66, NULL, 'owner,admin,staff', 'Distribution: Soft Dough × 10', '10 unit(s) of Soft Dough were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 02:23:12', '2026-03-15 23:59:59'),
(67, NULL, 'owner,admin', 'Distribution Deleted: Soft Dough × 10', 'Distribution of 10 unit(s) of Soft Dough for Mar 15, 2026 was deleted. Raw materials have been restored.', 'distribution', 'warning', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_deleted', '2026-03-15 02:23:30', NULL),
(68, NULL, 'owner,admin,staff', 'Distribution: Soft Dough × 10', '10 unit(s) of Soft Dough were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 02:25:00', '2026-03-15 23:59:59'),
(69, NULL, 'owner,admin', 'Distribution Deleted: Soft Dough × 10', 'Distribution of 10 unit(s) of Soft Dough for Mar 15, 2026 was deleted. Raw materials have been restored.', 'distribution', 'warning', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_deleted', '2026-03-15 02:25:14', NULL),
(70, NULL, 'owner,admin,staff', 'Distribution: Soft Dough × 12', '12 unit(s) of Soft Dough were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 02:25:14', '2026-03-15 23:59:59'),
(71, NULL, 'owner,admin,staff', 'Distribution: test1 × 10', '10 unit(s) of test1 were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 02:27:53', '2026-03-15 23:59:59'),
(72, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 02:28:03', NULL),
(73, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 15, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-15', '2026-03-15 02:28:05', '2026-03-15 23:59:59'),
(74, NULL, 'owner,admin', 'Distribution Deleted: test1 × 10', 'Distribution of 10 unit(s) of test1 for Mar 15, 2026 was deleted. Raw materials have been restored.', 'distribution', 'warning', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_deleted', '2026-03-15 02:28:42', NULL),
(75, NULL, 'owner,admin,staff', 'Distribution: test1 × 5', '5 unit(s) of test1 were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 02:28:42', '2026-03-15 23:59:59'),
(76, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 02:28:54', NULL),
(77, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 15, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-15', '2026-03-15 02:28:56', '2026-03-15 23:59:59'),
(78, NULL, 'owner,admin,staff', 'Distribution: test1 × 10', '10 unit(s) of test1 were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 03:12:18', '2026-03-15 23:59:59'),
(79, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 03:12:32', NULL),
(80, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 15, 2026 has been created with 2 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-15', '2026-03-15 03:12:34', '2026-03-15 23:59:59'),
(81, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 03:13:46', NULL),
(82, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 15, 2026 has been created with 2 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-15', '2026-03-15 03:14:12', '2026-03-15 23:59:59'),
(83, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 03:22:33', NULL),
(84, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 15, 2026 has been created with 2 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-15', '2026-03-15 03:22:35', '2026-03-15 23:59:59'),
(85, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 03:33:48', NULL),
(86, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 15, 2026 has been created with 2 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-15', '2026-03-15 03:33:51', '2026-03-15 23:59:59'),
(87, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 03:33:56', NULL),
(88, NULL, 'owner,admin,staff', 'Distribution: test × 3', '3 unit(s) of test were distributed for Mar 16, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 08:06:33', '2026-03-16 23:59:59'),
(89, NULL, 'owner,admin', 'Distribution Deleted: test × 3', 'Distribution of 3 unit(s) of test for Mar 16, 2026 was deleted. Raw materials have been restored.', 'distribution', 'warning', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_deleted', '2026-03-15 08:09:35', NULL),
(90, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 15, 2026 has been created with 2 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-15', '2026-03-15 08:12:59', '2026-03-15 23:59:59'),
(91, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 08:13:18', NULL),
(92, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 15, 2026 has been created with 2 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-15', '2026-03-15 08:13:21', '2026-03-15 23:59:59'),
(93, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 08:13:27', NULL),
(94, NULL, 'owner,admin', 'Distribution Deleted: test1 × 5', 'Distribution of 5 unit(s) of test1 for Mar 15, 2026 was deleted. Raw materials have been restored.', 'distribution', 'warning', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_deleted', '2026-03-15 08:16:18', NULL),
(95, NULL, 'owner,admin,staff', 'Distribution: test × 2', '2 unit(s) of test were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 08:16:18', '2026-03-15 23:59:59'),
(96, NULL, 'owner,admin,staff', 'Distribution: test1 × 1', '1 unit(s) of test1 were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 08:16:18', '2026-03-15 23:59:59'),
(97, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 15, 2026 has been created with 3 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-15', '2026-03-15 08:18:23', '2026-03-15 23:59:59'),
(98, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 08:18:47', NULL),
(99, NULL, 'owner,admin', 'Distribution Deleted: test1 × 10', 'Distribution of 10 unit(s) of test1 for Mar 15, 2026 was deleted. Raw materials have been restored.', 'distribution', 'warning', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_deleted', '2026-03-15 08:19:07', NULL),
(100, NULL, 'owner,admin,staff', 'Distribution: test1 × 1', '1 unit(s) of test1 were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 08:19:07', '2026-03-15 23:59:59'),
(101, NULL, 'owner,admin', 'Distribution Deleted: test × 2', 'Distribution of 2 unit(s) of test for Mar 15, 2026 was deleted. Raw materials have been restored.', 'distribution', 'warning', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_deleted', '2026-03-15 08:47:44', NULL),
(102, NULL, 'owner,admin', 'Distribution Deleted: test1 × 1', 'Distribution of 1 unit(s) of test1 for Mar 15, 2026 was deleted. Raw materials have been restored.', 'distribution', 'warning', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_deleted', '2026-03-15 08:47:45', NULL),
(103, NULL, 'owner,admin,staff', 'Distribution: test × 2', '2 unit(s) of test were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 08:47:45', '2026-03-15 23:59:59'),
(104, NULL, 'owner,admin,staff', 'Distribution: test1 × 1', '1 unit(s) of test1 were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 08:47:45', '2026-03-15 23:59:59'),
(105, NULL, 'owner,admin', 'Distribution Deleted: test1 × 1', 'Distribution of 1 unit(s) of test1 for Mar 15, 2026 was deleted. Raw materials have been restored.', 'distribution', 'warning', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_deleted', '2026-03-15 08:48:14', NULL),
(106, NULL, 'owner,admin,staff', 'Distribution: test1 × 1', '1 unit(s) of test1 were distributed for Mar 15, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-15 08:48:14', '2026-03-15 23:59:59'),
(107, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 15, 2026 has been created with 3 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-15', '2026-03-15 08:48:24', '2026-03-15 23:59:59'),
(108, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 08:49:54', NULL),
(109, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 15, 2026 has been created with 3 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-15', '2026-03-15 08:50:33', '2026-03-15 23:59:59'),
(110, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 08:52:23', NULL),
(111, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 15, 2026 has been created with 3 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-15', '2026-03-15 08:52:25', '2026-03-15 23:59:59'),
(112, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 08:53:17', NULL),
(113, NULL, 'owner,admin', '1 user(s) pending approval', '1 new account(s) are waiting for approval. Review them in the Manage Employee section.', 'approval', 'info', 'http://localhost:8080/ManageEmployee/Approval', 0, NULL, NULL, 'pending_users', '2026-03-15 08:58:04', '2026-03-15 23:59:59'),
(114, 5, 'staff', 'Account Approved', 'Your account has been approved by Julius Naag. You now have full access to the system.', 'user_approval', 'info', 'http://localhost:8080/Dashboard', 1, '2026-03-18 22:18:06', NULL, NULL, '2026-03-15 08:58:12', NULL),
(115, NULL, 'owner,admin', 'User Account Approved', 'A new staff account (ID #5) has been approved by Julius Naag.', 'user_approval', 'info', 'http://localhost:8080/ManageEmployee/Approval', 0, NULL, 5, 'user_approved', '2026-03-15 08:58:12', NULL),
(116, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 15, 2026 has been created with 3 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-15', '2026-03-15 09:08:40', '2026-03-15 23:59:59'),
(117, NULL, 'owner,admin', 'Inventory Deleted for Mar 15, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 15, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-15', '2026-03-15 09:08:44', NULL),
(118, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 15, 2026 has been created with 3 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-15', '2026-03-15 11:00:03', '2026-03-15 23:59:59'),
(119, NULL, 'owner,admin', 'test1 — 36.5% remaining', 'test1 (Raw Materials) is at 730.69 grams (36.5% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-16 11:27:41', '2026-03-16 23:59:59'),
(120, NULL, 'owner,admin,staff', 'Distribution: test × 2', '2 unit(s) of test were distributed for Mar 16, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-16 11:29:02', '2026-03-16 23:59:59'),
(121, NULL, 'owner,admin,staff', 'Distribution: test1 × 1', '1 unit(s) of test1 were distributed for Mar 16, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-16 11:29:02', '2026-03-16 23:59:59'),
(122, NULL, 'owner,admin,staff', 'Distribution: test × 10', '10 unit(s) of test were distributed for Mar 16, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-16 11:29:25', '2026-03-16 23:59:59'),
(123, NULL, 'owner,admin,staff', 'Distribution: test1 × 1', '1 unit(s) of test1 were distributed for Mar 16, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-16 11:29:25', '2026-03-16 23:59:59'),
(124, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 16, 2026 has been created with 4 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-16', '2026-03-16 11:31:15', '2026-03-16 23:59:59'),
(125, NULL, 'owner,admin,staff', 'test1 — 21.9% remaining', 'test1 (Raw Materials) is at 437.82 grams (21.9% of initial stock). Please restock soon.', 'low_stock', 'critical', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_critical', '2026-03-16 11:44:13', '2026-03-16 23:59:59'),
(126, NULL, 'owner,admin,staff', 'Distribution: test1 × 1', '1 unit(s) of test1 were distributed for Mar 17, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-16 13:37:52', '2026-03-17 23:59:59'),
(127, NULL, 'owner,admin', 'test1 — 35.9% remaining', 'test1 (Raw Materials) is at 718.32 grams (35.9% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-17 12:44:02', '2026-03-17 23:59:59'),
(128, NULL, 'owner,admin,staff', 'Distribution: test × 10', '10 unit(s) of test were distributed for Mar 18, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-17 13:00:45', '2026-03-18 23:59:59'),
(129, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 17, 2026 has been created with 1 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-17', '2026-03-17 13:03:00', '2026-03-17 23:59:59'),
(130, NULL, 'owner,admin', 'test1 — 35.9% remaining', 'test1 (Raw Materials) is at 718.32 grams (35.9% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-18 12:33:55', '2026-03-18 23:59:59'),
(131, NULL, 'owner,admin', 'Missed Remittance — Mar 17, 2026', 'There were 1 order(s) on Mar 17, 2026 but no remittance has been filed. Please follow up with the cashier on duty.', 'missed_remittance', 'warning', 'http://localhost:8080/Sales', 0, NULL, NULL, 'date_2026-03-17', '2026-03-18 12:33:55', NULL),
(132, 5, 'staff', 'Reminder: File your remittance for Mar 17, 2026', 'You processed 1 order(s) on Mar 17, 2026 but haven\'t filed a remittance yet. Please submit it as soon as possible.', 'missed_remittance', 'warning', 'http://localhost:8080/Sales', 1, '2026-03-18 22:18:06', NULL, NULL, '2026-03-18 12:33:55', NULL),
(133, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 18, 2026 has been created with 1 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-18', '2026-03-18 12:34:05', '2026-03-18 23:59:59'),
(134, NULL, 'owner,admin,staff', 'Distribution: test × 2', '2 unit(s) of test were distributed for Mar 16, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-18 12:39:36', '2026-03-16 23:59:59'),
(135, NULL, 'owner,admin', 'Inventory Deleted for Mar 18, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 18, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-18', '2026-03-18 13:55:06', NULL),
(136, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 18, 2026 has been created with 1 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-18', '2026-03-18 13:55:10', '2026-03-18 23:59:59'),
(137, NULL, 'owner,admin', 'Inventory Deleted for Mar 18, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 18, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-18', '2026-03-18 13:58:43', NULL),
(138, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 18, 2026 has been created with 1 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-18', '2026-03-18 14:01:55', '2026-03-18 23:59:59'),
(139, NULL, 'owner,admin', 'Inventory Deleted for Mar 18, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 18, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-18', '2026-03-18 14:07:51', NULL),
(140, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 18, 2026 has been created with 1 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-18', '2026-03-18 14:10:41', '2026-03-18 23:59:59'),
(141, NULL, 'owner,admin', 'Inventory Deleted for Mar 18, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 18, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-18', '2026-03-18 14:10:46', NULL),
(142, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 18, 2026 has been created with 1 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-18', '2026-03-18 14:10:48', '2026-03-18 23:59:59'),
(143, NULL, 'owner,admin', 'Inventory Deleted for Mar 18, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 18, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-18', '2026-03-18 14:10:51', NULL),
(144, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 18, 2026 has been created with 1 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-18', '2026-03-18 14:10:58', '2026-03-18 23:59:59'),
(145, NULL, 'owner,admin', 'Inventory Deleted for Mar 18, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 18, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-18', '2026-03-18 14:11:22', NULL),
(146, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 18, 2026 has been created with 1 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-18', '2026-03-18 14:11:28', '2026-03-18 23:59:59'),
(147, NULL, 'owner,admin', 'Inventory Deleted for Mar 18, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 18, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-18', '2026-03-18 14:11:36', NULL),
(148, NULL, 'owner,admin', 'No distribution for today (Mar 18, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-18', '2026-03-18 14:11:46', '2026-03-18 23:59:59'),
(149, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 18, 2026 has been created with 10 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-18', '2026-03-18 14:11:51', '2026-03-18 23:59:59'),
(150, NULL, 'owner,admin', 'Inventory Deleted for Mar 18, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 18, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-18', '2026-03-18 15:04:44', NULL),
(151, NULL, 'owner,admin,staff', 'Distribution: test × 2', '2 unit(s) of test were distributed for Mar 18, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-18 15:05:02', '2026-03-18 23:59:59'),
(152, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 18, 2026 has been created with 7 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-18', '2026-03-18 15:05:07', '2026-03-18 23:59:59'),
(153, NULL, 'owner,admin', 'test1 — 35.9% remaining', 'test1 (Raw Materials) is at 718.32 grams (35.9% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-19 12:37:51', '2026-03-19 23:59:59'),
(154, NULL, 'owner,admin', 'No distribution for today (Mar 19, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-19', '2026-03-19 12:37:51', '2026-03-19 23:59:59'),
(155, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 19, 2026 has been created with 10 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-19', '2026-03-19 13:03:09', '2026-03-19 23:59:59'),
(156, NULL, 'owner,admin', 'test1 — 36.1% remaining', 'test1 (Raw Materials) is at 722.44 grams (36.1% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-20 03:15:46', '2026-03-20 23:59:59'),
(157, NULL, 'owner,admin', 'Missed Remittance — Mar 19, 2026', 'There were 2 order(s) on Mar 19, 2026 but no remittance has been filed. Please follow up with the cashier on duty.', 'missed_remittance', 'warning', 'http://localhost:8080/Sales', 0, NULL, NULL, 'date_2026-03-19', '2026-03-20 03:15:46', NULL),
(158, 5, 'staff', 'Reminder: File your remittance for Mar 19, 2026', 'You processed 2 order(s) on Mar 19, 2026 but haven\'t filed a remittance yet. Please submit it as soon as possible.', 'missed_remittance', 'warning', 'http://localhost:8080/Sales', 1, '2026-04-01 22:14:29', NULL, NULL, '2026-03-20 03:15:46', NULL),
(159, NULL, 'owner,admin', 'No distribution for today (Mar 20, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-20', '2026-03-20 03:15:46', '2026-03-20 23:59:59'),
(160, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 20, 2026 has been created with 10 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-20', '2026-03-20 03:16:42', '2026-03-20 23:59:59'),
(161, NULL, 'owner,admin,staff', 'Distribution: test2 × 2', '2 unit(s) of test2 were distributed for Mar 20, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-20 03:27:58', '2026-03-20 23:59:59'),
(162, NULL, 'owner,admin,staff', 'Distribution: test2 × 1', '1 unit(s) of test2 were distributed for Mar 19, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-20 03:28:33', '2026-03-19 23:59:59'),
(163, NULL, 'owner,admin', 'Inventory Deleted for Mar 20, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 20, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-20', '2026-03-20 05:42:44', NULL),
(164, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 20, 2026 has been created with 1 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-20', '2026-03-20 05:42:47', '2026-03-20 23:59:59'),
(165, NULL, 'owner,admin', 'Inventory Deleted for Mar 20, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 20, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-20', '2026-03-20 09:20:11', NULL),
(166, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 20, 2026 has been created with 1 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-20', '2026-03-20 09:20:24', '2026-03-20 23:59:59'),
(167, NULL, 'owner,admin', 'Inventory Deleted for Mar 20, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 20, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-20', '2026-03-20 09:21:01', NULL),
(168, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 20, 2026 has been created with 1 product(s) (2 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-20', '2026-03-20 09:21:05', '2026-03-20 23:59:59'),
(169, NULL, 'owner,admin,staff', 'Distribution: test1 × 10', '10 unit(s) of test1 were distributed for Mar 20, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-20 09:44:45', '2026-03-20 23:59:59'),
(170, NULL, 'owner,admin,staff', 'Distribution: test × 1', '1 unit(s) of test were distributed for Mar 20, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-20 10:11:46', '2026-03-20 23:59:59'),
(171, NULL, 'owner,admin', 'test1 — 36.1% remaining', 'test1 (Raw Materials) is at 722.44 grams (36.1% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-21 13:10:50', '2026-03-21 23:59:59'),
(172, NULL, 'owner,admin', 'No distribution for today (Mar 21, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-21', '2026-03-21 13:10:50', '2026-03-21 23:59:59'),
(173, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 21, 2026 has been created with 11 product(s) (3 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-21', '2026-03-21 14:28:02', '2026-03-21 23:59:59'),
(174, NULL, 'owner,admin', 'Inventory Deleted for Mar 21, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 21, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-21', '2026-03-21 15:09:43', NULL),
(175, NULL, 'owner,admin,staff', 'Whip It - Whipping Cream — 20.0% remaining', 'Whip It - Whipping Cream (Raw Materials - Bread) is at 100 grams (20.0% of initial stock). Please restock soon.', 'low_stock', 'critical', 'http://localhost:8080/MaterialStock', 0, NULL, 323, 'raw_material_critical', '2026-03-21 15:15:25', '2026-03-21 23:59:59'),
(176, NULL, 'owner,admin,staff', 'Distribution: test2 × 3', '3 unit(s) of test2 were distributed for Mar 21, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-21 15:27:46', '2026-03-21 23:59:59'),
(177, NULL, 'owner,admin,staff', 'Distribution: test2 × 3', '3 unit(s) of test2 were distributed for Mar 21, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-21 15:28:11', '2026-03-21 23:59:59'),
(178, NULL, 'owner,admin,staff', 'Distribution: test1 × 1', '1 unit(s) of test1 were distributed for Mar 20, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-21 15:38:51', '2026-03-20 23:59:59');
INSERT INTO `notifications` (`notification_id`, `user_id`, `target_roles`, `title`, `message`, `type`, `level`, `action_url`, `is_read`, `read_at`, `reference_id`, `reference_type`, `created_at`, `expires_at`) VALUES
(179, NULL, 'owner,admin', 'Distribution Deleted: test1 × 1', 'Distribution of 1 unit(s) of test1 for Mar 20, 2026 was deleted. Raw materials have been restored.', 'distribution', 'warning', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_deleted', '2026-03-21 15:41:12', NULL),
(180, NULL, 'owner,admin,staff', 'Distribution: test × 10', '10 unit(s) of test were distributed for Mar 20, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-21 15:41:13', '2026-03-20 23:59:59'),
(181, NULL, 'owner,admin,staff', 'Distribution: test × 2', '2 unit(s) of test were distributed for Mar 20, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-21 15:42:27', '2026-03-20 23:59:59'),
(182, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 21, 2026 has been created with 1 product(s) (3 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-21', '2026-03-21 15:49:55', '2026-03-21 23:59:59'),
(183, NULL, 'owner,admin', 'Inventory Deleted for Mar 21, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 21, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-21', '2026-03-21 15:53:31', NULL),
(184, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 21, 2026 has been created with 1 product(s) (3 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-21', '2026-03-21 15:53:35', '2026-03-21 23:59:59'),
(185, NULL, 'owner,admin', 'test1 — 36.5% remaining', 'test1 (Raw Materials) is at 729.32 grams (36.5% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-22 13:32:18', '2026-03-22 23:59:59'),
(186, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 22, 2026 has been created with 11 product(s) (3 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-22', '2026-03-22 13:32:52', '2026-03-22 23:59:59'),
(187, NULL, 'owner,admin', 'Order #119 Voided', 'Order #119 worth ₱60.00 has been voided. Stock has been restored.', 'order', 'critical', 'http://localhost:8080/Orders', 0, NULL, 119, 'order_voided', '2026-03-22 13:42:36', NULL),
(188, NULL, 'owner,admin', 'Inventory Deleted for Mar 22, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 22, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-22', '2026-03-22 13:47:53', NULL),
(189, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 22, 2026 has been created with 11 product(s) (3 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-22', '2026-03-22 13:49:07', '2026-03-22 23:59:59'),
(190, NULL, 'owner,admin', 'Inventory Deleted for Mar 22, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 22, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-22', '2026-03-22 13:49:10', NULL),
(191, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 22, 2026 has been created with 11 product(s) (3 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-22', '2026-03-22 13:49:12', '2026-03-22 23:59:59'),
(192, NULL, 'owner,admin', 'Order #120 Voided', 'Order #120 worth ₱6.00 has been voided. Stock has been restored.', 'order', 'critical', 'http://localhost:8080/Orders', 0, NULL, 120, 'order_voided', '2026-03-22 13:50:19', NULL),
(193, NULL, 'owner,admin', 'Inventory Deleted for Mar 22, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 22, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-22', '2026-03-22 13:50:29', NULL),
(194, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 22, 2026 has been created with 11 product(s) (3 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-22', '2026-03-22 14:00:03', '2026-03-22 23:59:59'),
(195, NULL, 'owner,admin', 'Order #121 Voided', 'Order #121 worth ₱30.00 has been voided. Stock has been restored.', 'order', 'critical', 'http://localhost:8080/Orders', 0, NULL, 121, 'order_voided', '2026-03-22 14:03:17', NULL),
(196, NULL, 'owner,admin', 'test1 — 36.5% remaining', 'test1 (Raw Materials) is at 729.32 grams (36.5% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-23 06:35:36', '2026-03-23 23:59:59'),
(197, NULL, 'owner,admin', 'Missed Remittance — Mar 22, 2026', 'There were 3 order(s) on Mar 22, 2026 but no remittance has been filed. Please follow up with the cashier on duty.', 'missed_remittance', 'warning', 'http://localhost:8080/Sales', 0, NULL, NULL, 'date_2026-03-22', '2026-03-23 06:35:36', NULL),
(198, 5, 'staff', 'Reminder: File your remittance for Mar 22, 2026', 'You processed 3 order(s) on Mar 22, 2026 but haven\'t filed a remittance yet. Please submit it as soon as possible.', 'missed_remittance', 'warning', 'http://localhost:8080/Sales', 1, '2026-04-01 22:14:29', NULL, NULL, '2026-03-23 06:35:36', NULL),
(199, NULL, 'owner,admin', 'No distribution for today (Mar 23, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-23', '2026-03-23 06:35:36', '2026-03-23 23:59:59'),
(200, NULL, 'owner,admin', 'test1 — 36.5% remaining', 'test1 (Raw Materials) is at 729.32 grams (36.5% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-24 12:57:38', '2026-03-24 23:59:59'),
(201, NULL, 'owner,admin', 'No distribution for today (Mar 24, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-24', '2026-03-24 12:57:38', '2026-03-24 23:59:59'),
(202, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 24, 2026 has been created with 11 product(s) (3 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-24', '2026-03-24 13:17:25', '2026-03-24 23:59:59'),
(203, NULL, 'owner,admin', 'Inventory Deleted for Mar 24, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 24, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-24', '2026-03-24 13:17:30', NULL),
(204, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 24, 2026 has been created with 11 product(s) (3 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-24', '2026-03-24 13:17:33', '2026-03-24 23:59:59'),
(205, NULL, 'owner,admin', 'Inventory Deleted for Mar 24, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 24, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-24', '2026-03-24 13:51:40', NULL),
(206, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 24, 2026 has been created with 11 product(s) (3 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-24', '2026-03-24 13:51:45', '2026-03-24 23:59:59'),
(207, NULL, 'owner,admin', 'test1 — 36.5% remaining', 'test1 (Raw Materials) is at 729.32 grams (36.5% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-25 11:30:58', '2026-03-25 23:59:59'),
(208, NULL, 'owner,admin', 'No distribution for today (Mar 25, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-25', '2026-03-25 11:30:58', '2026-03-25 23:59:59'),
(209, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 11 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 11:37:58', '2026-03-25 23:59:59'),
(210, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 11 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 12:25:47', '2026-03-25 23:59:59'),
(211, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 12:25:51', NULL),
(212, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 11 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 12:37:06', '2026-03-25 23:59:59'),
(213, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 12:47:06', NULL),
(214, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 11 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 12:47:22', '2026-03-25 23:59:59'),
(215, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 12:47:33', NULL),
(216, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 11 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 12:49:08', '2026-03-25 23:59:59'),
(217, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 13:07:56', NULL),
(218, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 13:08:08', NULL),
(219, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 11 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 13:09:26', '2026-03-25 23:59:59'),
(220, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 13:11:20', NULL),
(221, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 13:11:42', NULL),
(222, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 11 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 13:11:54', '2026-03-25 23:59:59'),
(223, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 13:11:57', NULL),
(224, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 11 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 13:14:22', '2026-03-25 23:59:59'),
(225, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 13:21:59', NULL),
(226, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 11 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 13:22:01', '2026-03-25 23:59:59'),
(227, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 13:23:01', NULL),
(228, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 11 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 13:23:05', '2026-03-25 23:59:59'),
(229, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 13:26:03', NULL),
(230, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 11 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 13:26:13', '2026-03-25 23:59:59'),
(231, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 11 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 13:26:47', '2026-03-25 23:59:59'),
(232, NULL, 'owner,admin,staff', 'Distribution: test × 10', '10 unit(s) of test were distributed for Mar 25, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-25 13:31:47', '2026-03-25 23:59:59'),
(233, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 1 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 13:31:54', '2026-03-25 23:59:59'),
(234, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 13:36:50', NULL),
(235, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 13:37:34', NULL),
(236, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 1 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 13:37:40', '2026-03-25 23:59:59'),
(237, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 13:41:52', '2026-03-25 23:59:59'),
(238, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 13:42:04', NULL),
(239, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 1 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 13:47:48', '2026-03-25 23:59:59'),
(240, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 13:48:33', '2026-03-25 23:59:59'),
(241, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 13:48:40', NULL),
(242, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 13:49:07', '2026-03-25 23:59:59'),
(243, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 14:09:17', NULL),
(244, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 14:09:33', '2026-03-25 23:59:59'),
(245, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 14:09:50', NULL),
(246, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 14:09:52', '2026-03-25 23:59:59'),
(247, NULL, 'owner,admin', 'Inventory Deleted for Mar 25, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 25, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-25', '2026-03-25 15:11:28', NULL),
(248, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 25, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-25', '2026-03-25 15:11:32', '2026-03-25 23:59:59'),
(249, NULL, 'owner,admin', 'test1 — 36.5% remaining', 'test1 (Raw Materials) is at 729.32 grams (36.5% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-27 13:17:45', '2026-03-27 23:59:59'),
(250, NULL, 'owner,admin', 'No distribution for today (Mar 27, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-27', '2026-03-27 13:17:45', '2026-03-27 23:59:59'),
(251, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 27, 2026 has been created with 11 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-27', '2026-03-27 13:18:20', '2026-03-27 23:59:59'),
(252, NULL, 'owner,admin', 'Inventory Deleted for Mar 27, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 27, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-27', '2026-03-27 13:18:28', NULL),
(253, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 27, 2026 has been created with 11 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-27', '2026-03-27 13:19:50', '2026-03-27 23:59:59'),
(254, NULL, 'owner,admin', 'Inventory Deleted for Mar 27, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 27, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-27', '2026-03-27 13:22:34', NULL),
(255, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 27, 2026 has been created with 11 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-27', '2026-03-27 14:10:34', '2026-03-27 23:59:59'),
(256, NULL, 'owner,admin,staff', 'Distribution: test2 × 10', '10 unit(s) of test2 were distributed for Mar 27, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-27 14:19:37', '2026-03-27 23:59:59'),
(257, NULL, 'owner,admin', 'Inventory Deleted for Mar 27, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 27, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-27', '2026-03-27 14:31:36', NULL),
(258, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 27, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-27', '2026-03-27 14:31:43', '2026-03-27 23:59:59'),
(259, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 27, 2026 has been created with 1 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-27', '2026-03-27 14:49:53', '2026-03-27 23:59:59'),
(260, NULL, 'owner,admin', 'Inventory Deleted for Mar 27, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 27, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-27', '2026-03-27 14:51:00', NULL),
(261, NULL, 'owner,admin', 'Inventory Deleted for Mar 27, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 27, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-27', '2026-03-27 14:51:12', NULL),
(262, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 27, 2026 has been created with 1 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-27', '2026-03-27 14:51:14', '2026-03-27 23:59:59'),
(263, NULL, 'owner,admin', 'Inventory Deleted for Mar 27, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 27, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-27', '2026-03-27 14:51:30', NULL),
(264, NULL, 'owner,admin', 'Inventory Deleted for Mar 27, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 27, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-27', '2026-03-27 14:51:37', NULL),
(265, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 27, 2026 has been created with 1 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-27', '2026-03-27 14:53:15', '2026-03-27 23:59:59'),
(266, NULL, 'owner,admin', 'Inventory Deleted for Mar 27, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 27, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-27', '2026-03-27 14:53:49', NULL),
(267, NULL, 'owner,admin', 'Inventory Deleted for Mar 27, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 27, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-27', '2026-03-27 14:53:55', NULL),
(268, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 27, 2026 has been created with 1 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-27', '2026-03-27 14:54:28', '2026-03-27 23:59:59'),
(269, NULL, 'owner,admin', 'Inventory Deleted for Mar 27, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 27, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-27', '2026-03-27 14:59:24', NULL),
(270, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 27, 2026 has been created with 1 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-27', '2026-03-27 14:59:33', '2026-03-27 23:59:59'),
(271, NULL, 'owner,admin', 'Inventory Deleted for Mar 27, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 27, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-27', '2026-03-27 14:59:59', NULL),
(272, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 27, 2026 has been created with 1 product(s).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-27', '2026-03-27 15:01:13', '2026-03-27 23:59:59'),
(273, NULL, 'owner,admin', 'test1 — 36.5% remaining', 'test1 (Raw Materials) is at 729.32 grams (36.5% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-28 05:09:31', '2026-03-28 23:59:59'),
(274, NULL, 'owner,admin', 'No distribution for today (Mar 28, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-28', '2026-03-28 05:09:31', '2026-03-28 23:59:59'),
(275, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 28, 2026 has been created with 11 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-28', '2026-03-28 05:09:49', '2026-03-28 23:59:59'),
(276, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 05:17:48', NULL),
(277, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 28, 2026 has been created with 11 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-28', '2026-03-28 05:18:02', '2026-03-28 23:59:59'),
(278, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 05:18:11', NULL),
(279, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 05:34:48', NULL),
(280, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 05:43:29', NULL),
(281, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 06:47:32', NULL),
(282, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 06:47:48', NULL),
(283, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 08:42:38', NULL),
(284, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 08:42:57', NULL),
(285, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 10:00:40', NULL),
(286, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 10:01:10', NULL),
(287, NULL, 'owner,admin,staff', 'Distribution: test2 × 20', '20 unit(s) of test2 were distributed for Mar 28, 2026.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'dist_event', '2026-03-28 10:01:30', '2026-03-28 23:59:59'),
(288, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 28, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-28', '2026-03-28 10:04:48', '2026-03-28 23:59:59'),
(289, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 10:04:57', NULL),
(290, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 28, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-28', '2026-03-28 10:05:01', '2026-03-28 23:59:59'),
(291, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 10:06:56', NULL),
(292, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 14:11:24', NULL),
(293, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 28, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-28', '2026-03-28 14:11:31', '2026-03-28 23:59:59'),
(294, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 14:14:17', NULL),
(295, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 14:30:20', NULL),
(296, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 28, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-28', '2026-03-28 14:30:26', '2026-03-28 23:59:59'),
(297, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 14:30:54', NULL),
(298, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 14:39:23', NULL),
(299, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 14:51:19', NULL),
(300, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 28, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-28', '2026-03-28 14:54:45', '2026-03-28 23:59:59'),
(301, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 15:00:20', NULL),
(302, NULL, 'owner,admin', 'Order #129 Voided', 'Order #129 worth ₱30.00 has been voided. Stock has been restored.', 'order', 'critical', 'http://localhost:8080/Orders', 0, NULL, 129, 'order_voided', '2026-03-28 15:12:51', NULL),
(303, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 15:14:08', NULL),
(304, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 15:24:43', NULL),
(305, NULL, 'owner,admin', 'Inventory Deleted for Mar 28, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 28, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-28', '2026-03-28 15:24:47', NULL),
(306, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 28, 2026 has been created with 1 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-28', '2026-03-28 15:24:50', '2026-03-28 23:59:59'),
(307, NULL, 'owner,admin', 'test1 — 36.5% remaining', 'test1 (Raw Materials) is at 729.32 grams (36.5% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-28 16:02:03', '2026-03-29 23:59:59'),
(308, NULL, 'owner,admin', 'Missed Remittance — Mar 28, 2026', 'There were 2 order(s) on Mar 28, 2026 but no remittance has been filed. Please follow up with the cashier on duty.', 'missed_remittance', 'warning', 'http://localhost:8080/Sales', 0, NULL, NULL, 'date_2026-03-28', '2026-03-28 16:02:03', NULL),
(309, 5, 'staff', 'Reminder: File your remittance for Mar 28, 2026', 'You processed 2 order(s) on Mar 28, 2026 but haven\'t filed a remittance yet. Please submit it as soon as possible.', 'missed_remittance', 'warning', 'http://localhost:8080/Sales', 1, '2026-04-01 22:14:29', NULL, NULL, '2026-03-28 16:02:03', NULL),
(310, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 29, 2026 has been created with 11 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-29', '2026-03-28 16:05:24', '2026-03-29 23:59:59'),
(311, NULL, 'owner,admin', 'Inventory Deleted for Mar 29, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 29, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-29', '2026-03-28 16:06:01', NULL),
(312, NULL, 'owner,admin', 'Order #131 Voided', 'Order #131 worth ₱150.00 has been voided. Stock has been restored.', 'order', 'critical', 'http://localhost:8080/Orders', 0, NULL, 131, 'order_voided', '2026-03-28 16:16:27', NULL),
(313, NULL, 'owner,admin', 'Order #134 Voided', 'Order #134 worth ₱60.00 has been voided. Stock has been restored.', 'order', 'critical', 'http://localhost:8080/Orders', 0, NULL, 134, 'order_voided', '2026-03-28 16:21:36', NULL),
(314, NULL, 'owner,admin', 'Inventory Deleted for Mar 29, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 29, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-29', '2026-03-29 03:04:48', NULL),
(315, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 29, 2026 has been created with 11 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-29', '2026-03-29 03:04:52', '2026-03-29 23:59:59'),
(316, NULL, 'owner,admin', 'Order #136 Voided', 'Order #136 worth ₱42.00 has been voided. Stock has been restored.', 'order', 'critical', 'http://localhost:8080/Orders', 0, NULL, 136, 'order_voided', '2026-03-29 03:15:50', NULL),
(317, NULL, 'owner,admin', 'Inventory Deleted for Mar 29, 2026', 'Today\'s inventory has been deleted. Products are no longer tracked for Mar 29, 2026.', 'inventory', 'warning', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_deleted_2026-03-29', '2026-03-29 06:59:44', NULL),
(318, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 29, 2026 has been created with 11 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-29', '2026-03-29 15:18:28', '2026-03-29 23:59:59'),
(319, NULL, 'owner,admin', 'test1 — 36.5% remaining', 'test1 (Raw Materials) is at 729.32 grams (36.5% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-30 11:54:03', '2026-03-30 23:59:59'),
(320, NULL, 'owner,admin', 'No distribution for today (Mar 30, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-30', '2026-03-30 11:54:03', '2026-03-30 23:59:59'),
(321, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 30, 2026 has been created with 11 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-30', '2026-03-30 11:55:08', '2026-03-30 23:59:59'),
(322, NULL, 'owner,admin', 'test1 — 36.5% remaining', 'test1 (Raw Materials) is at 729.32 grams (36.5% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-03-31 13:47:47', '2026-03-31 23:59:59'),
(323, NULL, 'owner,admin', 'Missed Remittance — Mar 30, 2026', 'There were 1 order(s) on Mar 30, 2026 but no remittance has been filed. Please follow up with the cashier on duty.', 'missed_remittance', 'warning', 'http://localhost:8080/Sales', 0, NULL, NULL, 'date_2026-03-30', '2026-03-31 13:47:47', NULL),
(324, 5, 'staff', 'Reminder: File your remittance for Mar 30, 2026', 'You processed 1 order(s) on Mar 30, 2026 but haven\'t filed a remittance yet. Please submit it as soon as possible.', 'missed_remittance', 'warning', 'http://localhost:8080/Sales', 1, '2026-04-01 22:14:29', NULL, NULL, '2026-03-31 13:47:47', NULL),
(325, NULL, 'owner,admin', 'No distribution for today (Mar 31, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-03-31', '2026-03-31 13:47:47', '2026-03-31 23:59:59'),
(326, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Mar 31, 2026 has been created with 11 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-03-31', '2026-03-31 14:31:10', '2026-03-31 23:59:59'),
(327, NULL, 'owner,admin', 'test1 — 36.5% remaining', 'test1 (Raw Materials) is at 729.32 grams (36.5% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-04-01 13:46:33', '2026-04-01 23:59:59'),
(328, NULL, 'owner,admin', 'Missed Remittance — Mar 31, 2026', 'There were 1 order(s) on Mar 31, 2026 but no remittance has been filed. Please follow up with the cashier on duty.', 'missed_remittance', 'warning', 'http://localhost:8080/Sales', 0, NULL, NULL, 'date_2026-03-31', '2026-04-01 13:46:33', NULL),
(329, NULL, 'owner,admin', 'No distribution for today (Apr 01, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-04-01', '2026-04-01 13:46:33', '2026-04-01 23:59:59'),
(330, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Apr 01, 2026 has been created with 11 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-04-01', '2026-04-01 13:49:01', '2026-04-01 23:59:59'),
(331, NULL, 'owner,admin', 'test1 — 36.5% remaining', 'test1 (Raw Materials) is at 729.32 grams (36.5% of initial stock). Please restock soon.', 'low_stock', 'warning', 'http://localhost:8080/MaterialStock', 0, NULL, 339, 'raw_material_warning', '2026-04-02 05:32:51', '2026-04-02 23:59:59'),
(332, NULL, 'owner,admin', 'Missed Remittance — Apr 01, 2026', 'There were 1 order(s) on Apr 01, 2026 but no remittance has been filed. Please follow up with the cashier on duty.', 'missed_remittance', 'warning', 'http://localhost:8080/Sales', 0, NULL, NULL, 'date_2026-04-01', '2026-04-02 05:32:51', NULL),
(333, NULL, 'owner,admin', 'No distribution for today (Apr 02, 2026)', 'No products have been distributed for today. If the bakery is operating, please add today\'s distribution to ensure inventory is loaded.', 'distribution', 'info', 'http://localhost:8080/Distribution', 0, NULL, NULL, 'no_dist_2026-04-02', '2026-04-02 05:32:51', '2026-04-02 23:59:59'),
(334, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Apr 02, 2026 has been created with 11 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-04-02', '2026-04-02 07:14:27', '2026-04-02 23:59:59'),
(335, NULL, 'owner,admin', 'Remittance Filed — ₱0.00', 'Cashier Stephen Noblesala filed a remittance for Apr 02, 2026. Total sales: ₱0.00.', 'remittance', 'info', 'http://localhost:8080/Sales', 0, NULL, 2, 'remittance_filed', '2026-04-02 09:27:35', NULL),
(336, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Apr 02, 2026 has been created with 11 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-04-02', '2026-04-02 09:41:24', '2026-04-02 23:59:59'),
(337, NULL, 'owner,admin', 'Remittance Filed — ₱60.00', 'Cashier Stephen Noblesala filed a remittance for Apr 02, 2026. Total sales: ₱60.00.', 'remittance', 'info', 'http://localhost:8080/Sales', 0, NULL, 3, 'remittance_filed', '2026-04-02 09:42:42', NULL),
(338, NULL, 'owner,admin,staff', 'Today\'s Inventory Created', 'Inventory for Apr 02, 2026 has been created with 11 product(s) (1 carried over).', 'inventory', 'info', 'http://localhost:8080/Inventory', 0, NULL, NULL, 'inventory_created_2026-04-02', '2026-04-02 09:50:49', '2026-04-02 23:59:59'),
(339, NULL, 'owner,admin', 'Remittance Filed — ₱0.00', 'Cashier Stephen Noblesala filed a remittance for Apr 02, 2026. Total sales: ₱0.00.', 'remittance', 'info', 'http://localhost:8080/Sales', 0, NULL, 4, 'remittance_filed', '2026-04-02 10:01:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notification_reads`
--

CREATE TABLE `notification_reads` (
  `read_id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `read_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification_reads`
--

INSERT INTO `notification_reads` (`read_id`, `notification_id`, `user_id`, `read_at`) VALUES
(1, 2, 4, '2026-03-06 22:21:53'),
(2, 4, 4, '2026-03-06 22:46:19'),
(3, 6, 4, '2026-03-06 22:54:27'),
(4, 8, 4, '2026-03-06 22:56:06'),
(6, 3, 4, '2026-03-06 23:25:20'),
(7, 9, 4, '2026-03-06 23:25:20'),
(8, 10, 4, '2026-03-06 23:31:31'),
(9, 9, 1, '2026-03-07 16:47:06'),
(10, 10, 1, '2026-03-07 16:47:06'),
(11, 11, 1, '2026-03-07 16:47:06'),
(12, 12, 1, '2026-03-07 16:47:06'),
(13, 13, 1, '2026-03-07 16:47:06'),
(14, 14, 1, '2026-03-07 16:47:06'),
(15, 15, 1, '2026-03-07 16:47:06'),
(16, 16, 1, '2026-03-07 16:47:06'),
(17, 17, 1, '2026-03-07 16:47:06'),
(18, 18, 1, '2026-03-07 16:47:06'),
(19, 19, 1, '2026-03-07 16:47:06'),
(20, 20, 1, '2026-03-07 16:47:06'),
(21, 21, 1, '2026-03-07 16:47:06'),
(22, 22, 1, '2026-03-07 16:47:06'),
(23, 23, 1, '2026-03-07 16:47:06'),
(24, 24, 1, '2026-03-07 16:47:06'),
(25, 25, 1, '2026-03-07 16:47:06'),
(26, 26, 1, '2026-03-07 16:47:06'),
(27, 27, 1, '2026-03-16 19:28:06'),
(28, 28, 1, '2026-03-16 19:28:06'),
(29, 32, 1, '2026-03-16 19:28:06'),
(30, 33, 1, '2026-03-16 19:28:06'),
(31, 34, 1, '2026-03-16 19:28:06'),
(32, 35, 1, '2026-03-16 19:28:06'),
(33, 37, 1, '2026-03-16 19:28:06'),
(34, 44, 1, '2026-03-16 19:28:06'),
(35, 57, 1, '2026-03-16 19:28:06'),
(36, 59, 1, '2026-03-16 19:28:06'),
(37, 60, 1, '2026-03-16 19:28:06'),
(38, 61, 1, '2026-03-16 19:28:06'),
(39, 65, 1, '2026-03-16 19:28:06'),
(40, 67, 1, '2026-03-16 19:28:06'),
(41, 69, 1, '2026-03-16 19:28:06'),
(42, 72, 1, '2026-03-16 19:28:06'),
(43, 74, 1, '2026-03-16 19:28:06'),
(44, 76, 1, '2026-03-16 19:28:06'),
(45, 79, 1, '2026-03-16 19:28:06'),
(46, 81, 1, '2026-03-16 19:28:06'),
(47, 83, 1, '2026-03-16 19:28:06'),
(48, 85, 1, '2026-03-16 19:28:06'),
(49, 87, 1, '2026-03-16 19:28:06'),
(50, 88, 1, '2026-03-16 19:28:06'),
(51, 89, 1, '2026-03-16 19:28:06'),
(52, 91, 1, '2026-03-16 19:28:06'),
(53, 93, 1, '2026-03-16 19:28:06'),
(54, 94, 1, '2026-03-16 19:28:06'),
(55, 98, 1, '2026-03-16 19:28:06'),
(56, 99, 1, '2026-03-16 19:28:06'),
(57, 101, 1, '2026-03-16 19:28:06'),
(58, 102, 1, '2026-03-16 19:28:06'),
(59, 105, 1, '2026-03-16 19:28:06'),
(60, 108, 1, '2026-03-16 19:28:06'),
(61, 110, 1, '2026-03-16 19:28:06'),
(62, 112, 1, '2026-03-16 19:28:06'),
(63, 115, 1, '2026-03-16 19:28:06'),
(64, 117, 1, '2026-03-16 19:28:06'),
(65, 119, 1, '2026-03-16 19:28:06'),
(66, 128, 1, '2026-03-18 20:38:39'),
(67, 130, 1, '2026-03-18 20:38:39'),
(68, 131, 1, '2026-03-18 20:38:39'),
(69, 133, 1, '2026-03-18 20:38:39'),
(70, 135, 1, '2026-03-18 22:02:31'),
(71, 136, 1, '2026-03-18 22:02:31'),
(72, 137, 1, '2026-03-18 22:02:31'),
(73, 138, 1, '2026-03-18 22:02:31'),
(74, 139, 1, '2026-03-18 22:14:42'),
(75, 140, 1, '2026-03-18 22:14:42'),
(76, 141, 1, '2026-03-18 22:14:42'),
(77, 142, 1, '2026-03-18 22:14:42'),
(78, 143, 1, '2026-03-18 22:14:42'),
(79, 144, 1, '2026-03-18 22:14:42'),
(80, 145, 1, '2026-03-18 22:14:42'),
(81, 146, 1, '2026-03-18 22:14:42'),
(82, 147, 1, '2026-03-18 22:14:42'),
(83, 148, 1, '2026-03-18 22:14:42'),
(84, 149, 1, '2026-03-18 22:14:42'),
(85, 9, 5, '2026-03-18 22:18:07'),
(86, 10, 5, '2026-03-18 22:18:07'),
(87, 128, 5, '2026-03-18 22:18:07'),
(88, 133, 5, '2026-03-18 22:18:07'),
(89, 136, 5, '2026-03-18 22:18:07'),
(90, 138, 5, '2026-03-18 22:18:07'),
(91, 140, 5, '2026-03-18 22:18:07'),
(92, 142, 5, '2026-03-18 22:18:07'),
(93, 144, 5, '2026-03-18 22:18:07'),
(94, 146, 5, '2026-03-18 22:18:07'),
(95, 149, 5, '2026-03-18 22:18:07'),
(96, 150, 1, '2026-03-18 23:09:48'),
(97, 151, 1, '2026-03-18 23:09:48'),
(98, 152, 1, '2026-03-18 23:09:48'),
(99, 153, 1, '2026-03-19 23:01:23'),
(100, 154, 1, '2026-03-19 23:01:23'),
(101, 155, 1, '2026-03-19 23:01:23'),
(102, 156, 1, '2026-03-20 16:02:15'),
(103, 157, 1, '2026-03-20 16:02:15'),
(104, 159, 1, '2026-03-20 16:02:15'),
(105, 160, 1, '2026-03-20 16:02:15'),
(106, 161, 1, '2026-03-20 16:02:15'),
(107, 163, 1, '2026-03-20 16:02:15'),
(108, 164, 1, '2026-03-20 16:02:15'),
(109, 165, 1, '2026-03-20 18:13:17'),
(110, 166, 1, '2026-03-20 18:13:17'),
(111, 167, 1, '2026-03-20 18:13:17'),
(112, 168, 1, '2026-03-20 18:13:17'),
(113, 169, 1, '2026-03-20 18:13:17'),
(114, 170, 1, '2026-03-20 18:13:17'),
(115, 174, 1, '2026-03-23 14:38:01'),
(116, 179, 1, '2026-03-23 14:38:01'),
(117, 183, 1, '2026-03-23 14:38:01'),
(118, 187, 1, '2026-03-23 14:38:01'),
(119, 188, 1, '2026-03-23 14:38:01'),
(120, 190, 1, '2026-03-23 14:38:01'),
(121, 192, 1, '2026-03-23 14:38:01'),
(122, 193, 1, '2026-03-23 14:38:01'),
(123, 195, 1, '2026-03-23 14:38:01'),
(124, 196, 1, '2026-03-23 14:38:01'),
(125, 197, 1, '2026-03-23 14:38:01'),
(126, 199, 1, '2026-03-23 14:38:01'),
(127, 203, 1, '2026-03-25 21:28:36'),
(128, 205, 1, '2026-03-25 21:28:36'),
(129, 207, 1, '2026-03-25 21:28:36'),
(130, 208, 1, '2026-03-25 21:28:36'),
(131, 209, 1, '2026-03-25 21:28:36'),
(132, 210, 1, '2026-03-25 21:28:36'),
(133, 211, 1, '2026-03-25 21:28:36'),
(134, 212, 1, '2026-03-25 21:28:36'),
(135, 213, 1, '2026-03-25 21:28:36'),
(136, 214, 1, '2026-03-25 21:28:36'),
(137, 215, 1, '2026-03-25 21:28:36'),
(138, 216, 1, '2026-03-25 21:28:36'),
(139, 217, 1, '2026-03-25 21:28:36'),
(140, 218, 1, '2026-03-25 21:28:36'),
(141, 219, 1, '2026-03-25 21:28:36'),
(142, 220, 1, '2026-03-25 21:28:36'),
(143, 221, 1, '2026-03-25 21:28:36'),
(144, 222, 1, '2026-03-25 21:28:36'),
(145, 223, 1, '2026-03-25 21:28:36'),
(146, 224, 1, '2026-03-25 21:28:36'),
(147, 225, 1, '2026-03-25 21:28:36'),
(148, 226, 1, '2026-03-25 21:28:36'),
(149, 227, 1, '2026-03-25 21:28:36'),
(150, 228, 1, '2026-03-25 21:28:36'),
(151, 229, 1, '2026-03-25 21:28:36'),
(152, 230, 1, '2026-03-25 21:28:36'),
(153, 231, 1, '2026-03-25 21:28:36'),
(154, 232, 1, '2026-03-25 22:09:48'),
(155, 233, 1, '2026-03-25 22:09:48'),
(156, 234, 1, '2026-03-25 22:09:48'),
(157, 235, 1, '2026-03-25 22:09:48'),
(158, 236, 1, '2026-03-25 22:09:48'),
(159, 237, 1, '2026-03-25 22:09:48'),
(160, 238, 1, '2026-03-25 22:09:48'),
(161, 239, 1, '2026-03-25 22:09:48'),
(162, 240, 1, '2026-03-25 22:09:48'),
(163, 241, 1, '2026-03-25 22:09:48'),
(164, 242, 1, '2026-03-25 22:09:48'),
(165, 243, 1, '2026-03-25 22:09:48'),
(166, 244, 1, '2026-03-25 22:09:48'),
(167, 245, 1, '2026-03-25 22:41:04'),
(168, 246, 1, '2026-03-25 22:41:04'),
(169, 247, 1, '2026-03-25 23:13:38'),
(170, 248, 1, '2026-03-25 23:13:38'),
(171, 252, 1, '2026-03-28 14:10:26'),
(172, 254, 1, '2026-03-28 14:10:26'),
(173, 257, 1, '2026-03-28 14:10:26'),
(174, 260, 1, '2026-03-28 14:10:26'),
(175, 261, 1, '2026-03-28 14:10:26'),
(176, 263, 1, '2026-03-28 14:10:26'),
(177, 264, 1, '2026-03-28 14:10:26'),
(178, 266, 1, '2026-03-28 14:10:26'),
(179, 267, 1, '2026-03-28 14:10:26'),
(180, 269, 1, '2026-03-28 14:10:26'),
(181, 271, 1, '2026-03-28 14:10:26'),
(182, 273, 1, '2026-03-28 14:10:26'),
(183, 274, 1, '2026-03-28 14:10:26'),
(184, 275, 1, '2026-03-28 14:10:26'),
(185, 276, 1, '2026-03-28 14:10:26'),
(186, 277, 1, '2026-03-28 14:10:26'),
(187, 278, 1, '2026-03-28 14:10:26'),
(188, 279, 1, '2026-03-28 14:10:26'),
(189, 280, 1, '2026-03-28 14:10:26'),
(190, 281, 1, '2026-03-28 22:48:33'),
(191, 282, 1, '2026-03-28 22:48:33'),
(192, 283, 1, '2026-03-28 22:48:33'),
(193, 284, 1, '2026-03-28 22:48:33'),
(194, 285, 1, '2026-03-28 22:48:33'),
(195, 286, 1, '2026-03-28 22:48:33'),
(196, 287, 1, '2026-03-28 22:48:33'),
(197, 288, 1, '2026-03-28 22:48:33'),
(198, 289, 1, '2026-03-28 22:48:33'),
(199, 290, 1, '2026-03-28 22:48:33'),
(200, 291, 1, '2026-03-28 22:48:33'),
(201, 292, 1, '2026-03-28 22:48:33'),
(202, 293, 1, '2026-03-28 22:48:33'),
(203, 294, 1, '2026-03-28 22:48:33'),
(204, 295, 1, '2026-03-28 22:48:33'),
(205, 296, 1, '2026-03-28 22:48:33'),
(206, 297, 1, '2026-03-28 22:48:33'),
(207, 298, 1, '2026-03-28 22:48:33'),
(208, 299, 1, '2026-03-28 23:01:03'),
(209, 300, 1, '2026-03-28 23:01:03'),
(210, 301, 1, '2026-03-28 23:01:03'),
(211, 302, 1, '2026-03-28 23:16:47'),
(212, 303, 1, '2026-03-28 23:16:47'),
(213, 304, 1, '2026-03-29 15:01:11'),
(214, 305, 1, '2026-03-29 15:01:11'),
(215, 307, 1, '2026-03-29 15:01:11'),
(216, 308, 1, '2026-03-29 15:01:11'),
(217, 310, 1, '2026-03-29 15:01:11'),
(218, 311, 1, '2026-03-29 15:01:11'),
(219, 312, 1, '2026-03-29 15:01:11'),
(220, 313, 1, '2026-03-29 15:01:11'),
(221, 314, 1, '2026-03-29 15:01:11'),
(222, 315, 1, '2026-03-29 15:01:11'),
(223, 316, 1, '2026-03-29 15:01:11'),
(224, 317, 1, '2026-03-29 15:01:11'),
(225, 319, 1, '2026-03-30 20:03:07'),
(226, 320, 1, '2026-03-30 20:03:07'),
(227, 321, 1, '2026-03-30 20:03:07'),
(228, 323, 1, '2026-04-01 22:08:00'),
(229, 327, 1, '2026-04-01 22:08:00'),
(230, 328, 1, '2026-04-01 22:08:00'),
(231, 329, 1, '2026-04-01 22:08:00'),
(232, 330, 1, '2026-04-01 22:08:00'),
(233, 330, 5, '2026-04-01 22:14:29');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `total_payment_due` double NOT NULL,
  `amount_received` double NOT NULL,
  `amount_change` double NOT NULL,
  `payment_method` enum('cash','gcash','maya','credit card','debit card','panda') DEFAULT NULL,
  `order_type` enum('walk-in','foodpanda','distributed') DEFAULT NULL,
  `distributed_note` text DEFAULT NULL,
  `cashier_id` int(11) DEFAULT NULL,
  `cashier_name` varchar(255) DEFAULT NULL,
  `date_created` date NOT NULL,
  `time_created` time NOT NULL,
  `voided_at` datetime DEFAULT NULL,
  `voided_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `total_payment_due`, `amount_received`, `amount_change`, `payment_method`, `order_type`, `distributed_note`, `cashier_id`, `cashier_name`, `date_created`, `time_created`, `voided_at`, `voided_by`) VALUES
(138, 30, 30, 0, 'panda', 'foodpanda', NULL, 1, NULL, '2026-03-31', '22:32:29', NULL, NULL),
(139, 140, 140, 0, 'panda', 'foodpanda', NULL, 1, NULL, '2026-04-01', '21:49:56', NULL, NULL),
(140, 150, 150, 0, 'cash', 'distributed', 'sadasd', 2, NULL, '2026-04-02', '15:32:16', NULL, NULL),
(141, 60, 60, 0, 'cash', 'distributed', 'l;ml;', 2, NULL, '2026-04-02', '17:41:59', NULL, NULL);

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
(146, 14, 138, 1, 30, 30, '2026-03-31', '22:32:29'),
(147, 14, 139, 1, 30, 30, '2026-04-01', '21:49:56'),
(148, 2, 139, 1, 110, 110, '2026-04-01', '21:49:56'),
(149, 14, 140, 5, 30, 150, '2026-04-02', '15:32:16'),
(150, 14, 141, 2, 30, 60, '2026-04-02', '17:41:59');

-- --------------------------------------------------------

--
-- Table structure for table `owner_notification_settings`
--

CREATE TABLE `owner_notification_settings` (
  `owner_notification_setting_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `low_stock_enabled` tinyint(1) NOT NULL,
  `inventory_enabled` tinyint(1) NOT NULL,
  `remittance_enabled` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner_notification_settings`
--

INSERT INTO `owner_notification_settings` (`owner_notification_setting_id`, `user_id`, `low_stock_enabled`, `inventory_enabled`, `remittance_enabled`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '2026-04-01 22:12:43', '2026-04-01 22:27:51');

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
(1, 'drinks', 'Café Americano', '', 0, '2026-02-15 03:54:42', NULL),
(2, 'drinks', 'Café Latte', '', 0, '2026-02-15 03:57:26', NULL),
(3, 'drinks', 'Spanish Latte', '', 0, '2026-02-21 09:45:38', NULL),
(4, 'drinks', 'Caramel Macchiato', '', 0, '2026-02-15 04:24:50', NULL),
(5, 'drinks', 'Iced Americano', '', 0, '2026-02-15 04:09:50', NULL),
(6, 'dough', 'Soft Dough', '', 0, '2026-02-15 04:27:38', NULL),
(7, 'bakery', 'test1', '', 0, '2026-02-28 07:31:39', NULL),
(8, 'grocery', '200 ml Bottled Water', '', 0, '2026-02-21 10:25:59', NULL),
(11, 'bakery', 'combined1', '', 0, '2026-02-21 09:50:07', '2026-02-21 17:50:07'),
(12, 'bakery', 'test2', '', 0, '2026-02-21 11:05:49', '2026-02-21 19:05:49'),
(13, 'bakery', 'test', '', 0, '2026-02-28 07:31:38', NULL),
(14, 'bakery', 'test2', '', 0, '2026-03-20 03:27:04', NULL);

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
(4, 7, 13, 9, 0.716, 103.104, '2026-03-14 07:06:40'),
(7, 14, 13, 18, 0.716, 773.28, '2026-03-21 15:08:57');

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
(7, 7, 24.42, 25, 6.105, 103.104, 30, -21.3675, 30.525, 45, 0, 6, 11, 0, 16, 0, 0.69, '2026-03-14 07:06:40'),
(8, 8, 14, 0, 0, 0, 40, 5.6, 14, 25, 0, 0, 0, 0, 0, 0, 0, '2026-02-15 05:04:02'),
(11, 11, 10, 0, 0, 0, 30, 3, 10, 15, 0, 13, 1, 0, 1, 0, 1, '2026-02-21 09:42:35'),
(12, 12, 2.22, 0, 0, 0, 30, 0.666, 2.22, 5, 0, 5, 1, 0, 1, 0, 1, '2026-02-21 10:27:25'),
(13, 13, 7.16, 20, 1.432, 0, 300, 17.184, 7.16, 30, 0, 30, 10, 0, 1, 0, 10, '2026-03-21 15:40:47'),
(14, 14, 176.65, 25, 237.4825, 773.28, 120, 237.4825, 949.93, 1500, 75, 30, 421, 20, 3, 21.05, 7.02, '2026-03-21 15:08:57');

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
(46, 11, 340, 1, 'grams', '2026-02-21 09:42:35'),
(47, 12, 339, 1, 'grams', '2026-02-21 10:27:25'),
(52, 7, 339, 11, 'grams', '2026-03-14 07:06:40'),
(91, 14, 334, 21, 'grams', '2026-03-21 15:08:57'),
(92, 14, 323, 400, 'grams', '2026-03-21 15:08:57'),
(93, 14, 324, 2, 'pcs', '2026-03-21 15:08:57'),
(94, 13, 338, 10, 'grams', '2026-03-21 15:40:47');

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
(339, 4, 'test1', 500, 'grams', '2026-03-01 02:40:24'),
(340, 3, 'combined1', 3000, 'grams', '2026-02-16 13:23:06'),
(342, 2, 'testqqe', 26000, 'grams', '2026-03-05 12:54:04');

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
(429, 339, 2.22, '2026-03-01 02:40:24'),
(430, 340, 10, '2026-02-16 13:23:06'),
(432, 342, 0.07, '2026-03-05 12:54:05');

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
(1, 1, 1000, 36, 'grams', '2026-04-01 13:49:56'),
(2, 2, 220, 45, 'grams', '2026-04-01 13:49:56'),
(3, 3, 1000, 250, 'grams', '2026-04-01 13:49:56'),
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
(14, 14, 1000, 6, 'pcs', '2026-04-01 13:49:56'),
(15, 15, 100, 1, 'pcs', '2026-04-01 13:49:56'),
(16, 16, 100, 1, 'pcs', '2026-03-14 13:24:45'),
(17, 17, 100, 0, 'pcs', '2026-02-14 17:39:11'),
(18, 18, 100, 0, 'pcs', '2026-02-14 17:39:51'),
(19, 19, 100, 1, 'pcs', '2026-03-14 13:24:45'),
(20, 20, 100, 1, 'pcs', '2026-04-01 13:49:56'),
(21, 21, 2000, 692, 'grams', '2026-03-14 13:24:45'),
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
(302, 192, 1, 0, 'ml', '2026-03-15 02:25:14'),
(303, 266, 25000, 0, 'grams', '2026-02-15 04:24:19'),
(304, 267, 25000, 39.173, 'grams', '2026-03-15 02:25:14'),
(305, 268, 50000, 7.8344000000000005, 'grams', '2026-03-15 02:25:14'),
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
(316, 279, 25000, 0.5875, 'grams', '2026-03-15 02:25:14'),
(317, 280, 1000, 0, 'grams', '2026-03-07 05:19:02'),
(318, 281, 200, 0.7833999999999999, 'grams', '2026-03-15 02:25:14'),
(319, 282, 165, 0, 'grams', '2026-02-15 04:24:19'),
(320, 283, 360, 0, 'grams', '2026-02-15 04:24:19'),
(321, 284, 390, 0, 'grams', '2026-02-15 04:24:19'),
(322, 285, 1000, 3.9175000000000004, 'grams', '2026-03-15 02:25:14'),
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
(334, 297, 36000, 2.3501000000000003, 'grams', '2026-03-15 02:25:14'),
(335, 298, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(336, 299, 36000, 0, 'grams', '2026-02-15 04:24:19'),
(337, 300, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(338, 301, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(339, 302, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(340, 303, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(341, 304, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(342, 305, 500, 0.33729999999999993, 'grams', '2026-03-15 02:25:14'),
(343, 306, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(344, 307, 800, 0, 'grams', '2026-02-15 04:24:19'),
(345, 308, 25000, 0, 'grams', '2026-02-15 04:24:19'),
(346, 309, 1000, 0.11739999999999998, 'grams', '2026-03-15 02:25:14'),
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
(360, 323, 500, 293.3333, 'grams', '2026-03-28 14:55:12'),
(361, 324, 8, 1.4667, 'pcs', '2026-03-28 14:55:12'),
(362, 325, 220, 0, 'grams', '2026-02-15 04:24:19'),
(363, 326, 390, 0, 'grams', '2026-02-15 04:24:19'),
(364, 327, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(365, 328, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(366, 329, 1000, 0, 'grams', '2026-02-15 04:24:19'),
(367, 330, 1, 0, 'pcs', '2026-02-15 04:24:19'),
(368, 331, 250, 0, 'grams', '2026-02-15 04:24:19'),
(369, 332, 250, 0, 'grams', '2026-02-15 04:24:19'),
(370, 333, 680, 0, 'pcs', '2026-02-15 04:24:19'),
(371, 334, 100, 46.199999999999996, 'grams', '2026-03-28 14:55:12'),
(372, 335, 2000, 0, 'grams', '2026-02-15 04:24:19'),
(373, 336, 750, 0, 'grams', '2026-02-15 04:24:19'),
(374, 337, 225, 0, 'grams', '2026-02-15 04:24:19'),
(375, 338, 10000000, 18992, 'grams', '2026-03-29 06:58:44'),
(431, 340, 3000, 1, 'grams', '2026-03-06 14:46:40'),
(432, 339, 2000, 1270.6833000000001, 'grams', '2026-03-29 06:58:44');

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
  `daily_stock_id` int(11) DEFAULT NULL,
  `cashier` int(11) DEFAULT NULL,
  `outlet_name` varchar(100) NOT NULL,
  `remittance_date` datetime NOT NULL,
  `shift_start` time NOT NULL,
  `shift_end` time NOT NULL,
  `amount_enclosed` double NOT NULL,
  `total_online_revenue` double NOT NULL,
  `foodpanda_revenue` double NOT NULL DEFAULT 0,
  `cash_out` double NOT NULL,
  `cashout_reason` varchar(255) NOT NULL,
  `bakery_sales` double NOT NULL,
  `coffee_sales` double NOT NULL,
  `grocery_sales` double NOT NULL,
  `total_sales` double NOT NULL,
  `variance_amount` double NOT NULL,
  `is_short` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `remittance_details`
--

INSERT INTO `remittance_details` (`remittance_id`, `daily_stock_id`, `cashier`, `outlet_name`, `remittance_date`, `shift_start`, `shift_end`, `amount_enclosed`, `total_online_revenue`, `foodpanda_revenue`, `cash_out`, `cashout_reason`, `bakery_sales`, `coffee_sales`, `grocery_sales`, `total_sales`, `variance_amount`, `is_short`) VALUES
(1, NULL, 2, 'Deca Sentrio', '2026-03-08 20:08:24', '05:00:00', '13:00:00', 0, 0, 12.51, 0, '', 87.57, 0, 0, 87.57, 75.06, 1);

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
  `time_created` time NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`sale_id`, `item_id`, `order_id`, `quantity_sold`, `total_sales`, `date_created`, `time_created`, `deleted_at`) VALUES
(147, 480, 138, 1, 30, '2026-03-31', '22:32:29', NULL),
(148, 491, 139, 1, 30, '2026-04-01', '21:49:56', NULL),
(149, 482, 139, 1, 110, '2026-04-01', '21:49:56', NULL),
(150, 502, 140, 5, 150, '2026-04-02', '15:32:16', NULL);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `firstname`, `middlename`, `lastname`, `employee_type`, `username`, `password`, `gender`, `birthdate`, `phone_number`, `approved`, `created_at`, `deleted_at`) VALUES
(1, 'junaag@my.cspc.edu.ph', 'Julius ', '', 'Naag', 'owner', 'Julz', '$2y$10$siODUx8SP8/M0NEPpDfpHO7YH8V7XT3sncckhrI0J8sL6EHyKMRtu', 'male', '2025-04-16', '09388702935', 1, '2026-02-14 12:53:18', NULL),
(2, 'stnoblesala@my.cspc.edu.ph', 'Stephen', 'Cesista', 'Noblesala', 'owner', 'mamamo', '$2y$10$Wm7ReBRbjkeIvy20./XAdOh8Ph14zfJEEkDo0O0rPZw1NA6fjOxxi', 'male', '2026-02-04', '123123123', 1, '2026-03-13 14:14:37', NULL),
(3, 'jabarte@my.cspc.edu.ph', 'lala', 'lele', 'lalu', '', 'andro', 'andro', 'male', '2026-03-04', '', 1, '2026-03-05 12:45:48', NULL),
(4, 'rdrew402@gmail.com', 'Andrew', '', 'Io', 'staff', 'admin', '$2y$12$0G6LvNM6BJQK6mbAGiVqZ.HmNknNhEhHMuhyD/6g3.s.IJOp1wGWu', 'male', '2026-03-04', '09474652786', 1, '2026-03-05 12:50:59', NULL),
(5, 'naag.juliuscaesar@gmail.com', 'Julius', '', 'Naag', 'staff', 'julius_employee', '$2y$10$plK3AKgws2Y4k2xQeuDe5uscRDOUxPjnoQNWZgTBZoxllDzBbwdLi', 'male', '2026-03-15', '09388702935', 1, '2026-03-28 06:49:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `utility_expenses`
--

CREATE TABLE `utility_expenses` (
  `u_id` int(11) NOT NULL,
  `type` varchar(55) NOT NULL,
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
(1, 'gas', 'monthly', 0, '', 100, 0, 0, 0, '2026-02-21 17:26:49', '2026-02-21 00:00:00'),
(2, 'water', 'monthly', 0, '', 200, 0, 0, 0, '2026-02-21 19:49:43', '2026-02-21 00:00:00'),
(3, 'electricity', 'monthly', 0, '', 3000, 0, 0, 0, '2026-02-21 19:54:54', '2026-02-21 00:00:00'),
(8, 'water', 'monthly', 0, '', 200, 0, 0, 0, '2026-03-14 14:05:30', '2026-03-12 00:00:00'),
(11, 'fuel', 'monthly', 0, '', 13, 0, 0, 0, '2026-03-16 21:40:07', '2026-03-16 00:00:00'),
(12, 'others - Selfie', 'monthly', 0, '', 130, 0, 0, 0, '2026-03-16 22:26:15', '2026-03-16 00:00:00');

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
-- Indexes for table `distribution_group`
--
ALTER TABLE `distribution_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_distribution_date` (`distribution_date`);

--
-- Indexes for table `distribution_item`
--
ALTER TABLE `distribution_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_distribution_id` (`distribution_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `material_category`
--
ALTER TABLE `material_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_target_roles` (`target_roles`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `notification_reads`
--
ALTER TABLE `notification_reads`
  ADD PRIMARY KEY (`read_id`),
  ADD UNIQUE KEY `uq_notif_user` (`notification_id`,`user_id`),
  ADD KEY `fk_notif_reads_user` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `cashier_id` (`cashier_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `owner_notification_settings`
--
ALTER TABLE `owner_notification_settings`
  ADD PRIMARY KEY (`owner_notification_setting_id`),
  ADD KEY `user_id` (`user_id`);

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
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `idx_users_deleted_at` (`deleted_at`);

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
  MODIFY `daily_stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `daily_stock_items`
--
ALTER TABLE `daily_stock_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=569;

--
-- AUTO_INCREMENT for table `distribution_group`
--
ALTER TABLE `distribution_group`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `distribution_item`
--
ALTER TABLE `distribution_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `material_category`
--
ALTER TABLE `material_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=340;

--
-- AUTO_INCREMENT for table `notification_reads`
--
ALTER TABLE `notification_reads`
  MODIFY `read_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=234;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `owner_notification_settings`
--
ALTER TABLE `owner_notification_settings`
  MODIFY `owner_notification_setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product_combined_recipes`
--
ALTER TABLE `product_combined_recipes`
  MODIFY `combined_recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_costs`
--
ALTER TABLE `product_costs`
  MODIFY `product_cost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product_recipe`
--
ALTER TABLE `product_recipe`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `raw_materials`
--
ALTER TABLE `raw_materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=343;

--
-- AUTO_INCREMENT for table `raw_material_cost`
--
ALTER TABLE `raw_material_cost`
  MODIFY `cost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=433;

--
-- AUTO_INCREMENT for table `raw_material_stock`
--
ALTER TABLE `raw_material_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=435;

--
-- AUTO_INCREMENT for table `remittance_denominations`
--
ALTER TABLE `remittance_denominations`
  MODIFY `denomination_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `remittance_details`
--
ALTER TABLE `remittance_details`
  MODIFY `remittance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `remittance_items`
--
ALTER TABLE `remittance_items`
  MODIFY `remit_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `utility_expenses`
--
ALTER TABLE `utility_expenses`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
-- Constraints for table `distribution_item`
--
ALTER TABLE `distribution_item`
  ADD CONSTRAINT `fk_di_group` FOREIGN KEY (`distribution_id`) REFERENCES `distribution_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_di_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `notification_reads`
--
ALTER TABLE `notification_reads`
  ADD CONSTRAINT `fk_notif_reads_notif` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`notification_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_notif_reads_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `owner_notification_settings`
--
ALTER TABLE `owner_notification_settings`
  ADD CONSTRAINT `owner_notification_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
