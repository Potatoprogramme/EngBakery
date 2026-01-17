-- Sample Data for EngBakery
-- Following rules.txt: database, columns - snake_case

-- ============================================================
-- DISABLE FOREIGN KEY CHECKS (Required for circular references)
-- ============================================================
SET FOREIGN_KEY_CHECKS = 0;

-- Clear existing data (for fresh start)
TRUNCATE TABLE raw_material_cost;
TRUNCATE TABLE raw_material_stock;
TRUNCATE TABLE raw_materials;
TRUNCATE TABLE material_category;

-- ============================================================
-- 1. Insert Material Categories
-- ============================================================
INSERT INTO `material_category` (`category_id`, `category_name`, `description`) VALUES
(1, 'RAW MATERIALS', 'Baking ingredients and raw goods'),
(2, 'PACKAGING', 'Boxes, bags, and packaging materials'),
(3, 'OFFICE SUPPLIES', 'Administrative supplies'),
(4, 'OVERHEAD EXPENSES', 'Utilities and operational costs'),
(5, 'COFFEE EXPENSES', 'Coffee shop ingredients');

-- ============================================================
-- 2. Insert Raw Materials FIRST (main table)
-- ============================================================
INSERT INTO `raw_materials` (`material_id`, `cost_id`, `stock_id`, `category_id`, `material_name`, `material_quantity`, `unit`) VALUES
-- RAW MATERIALS (category_id = 1)
(1, 1, 1, 1, 'Flour - All Purpose', 25000, 'grams'),
(2, 2, 2, 1, 'Flour - Kutitap First Class', 25000, 'grams'),
(3, 3, 3, 1, 'Sugar 99', 50000, 'grams'),
(4, 4, 4, 1, 'Sugar Brown', 50000, 'grams'),
(5, 5, 5, 1, 'Sugar White', 50000, 'grams'),
(6, 6, 6, 1, 'Sugar Powdered - Penco', 2272, 'grams'),
(7, 7, 7, 1, 'Baking Powder - Ordinary', 1000, 'grams'),
(8, 8, 8, 1, 'Baking Powder - Calumet', 1000, 'grams'),
(9, 9, 9, 1, 'Cream of Tartar', 50, 'grams'),
(10, 10, 10, 1, 'Cocoa Ordinary', 25000, 'grams'),
(11, 11, 11, 1, 'Buttermilk', 25000, 'grams'),
(12, 12, 12, 1, 'Vanilla', 4000, 'grams'),
(13, 13, 13, 1, 'Salt', 25000, 'grams'),
(14, 14, 14, 1, 'Eggs', 30, 'pcs'),
-- PACKAGING (category_id = 2)
(15, 15, 15, 2, 'Baking Cups - 2oz', 1200, 'pcs'),
(16, 16, 16, 2, 'Boxes - 10x10x4 in', 20, 'pcs'),
(17, 17, 17, 2, 'Boxes - 9x9x2 in', 20, 'pcs'),
(18, 18, 18, 2, 'Paper Plate', 35, 'pcs'),
(19, 19, 19, 2, 'Plastic Bag - Medium', 240, 'pcs'),
-- COFFEE EXPENSES (category_id = 5)
(20, 20, 20, 5, 'Coffee Beans', 1000, 'grams'),
(21, 21, 21, 5, 'Fresh Milk', 1000, 'grams'),
(22, 22, 22, 5, 'Caramel Syrup', 750, 'grams'),
(23, 23, 23, 5, 'French Vanilla Syrup', 750, 'grams');

-- ============================================================
-- 3. Insert Raw Material Costs (cost_per_unit calculated)
-- Formula: cost_per_unit = total_cost / quantity
-- ============================================================
INSERT INTO `raw_material_cost` (`cost_id`, `material_id`, `cost_per_unit`) VALUES
-- RAW MATERIALS
(1, 1, 0.054000),   -- Flour - All Purpose: 1350/25000
(2, 2, 0.036000),   -- Flour - Kutitap: 900/25000
(3, 3, 0.062000),   -- Sugar 99: 3100/50000
(4, 4, 0.060000),   -- Sugar Brown: 3000/50000
(5, 5, 0.072000),   -- Sugar White: 3580/50000
(6, 6, 0.114000),   -- Sugar Powdered: 260/2272
(7, 7, 0.084000),   -- Baking Powder Ordinary: 84/1000
(8, 8, 0.205000),   -- Baking Powder Calumet: 205/1000
(9, 9, 0.640000),   -- Cream of Tartar: 32/50
(10, 10, 0.180000), -- Cocoa Ordinary: 4500/25000
(11, 11, 0.160000), -- Buttermilk: 4000/25000
(12, 12, 0.040000), -- Vanilla: 158/4000
(13, 13, 0.014000), -- Salt: 340/25000
(14, 14, 8.833000), -- Eggs: 265/30
-- PACKAGING
(15, 15, 0.065000), -- Baking Cups 2oz: 78/1200
(16, 16, 29.900000),-- Boxes 10x10x4: 598/20
(17, 17, 19.400000),-- Boxes 9x9x2: 388/20
(18, 18, 0.914000), -- Paper Plate: 32/35
(19, 19, 0.833000), -- Plastic Bag Medium: 200/240
-- COFFEE EXPENSES
(20, 20, 1.250000), -- Coffee Beans: 1250/1000
(21, 21, 0.080000), -- Fresh Milk Coffee: 80/1000
(22, 22, 0.400000), -- Caramel Syrup: 300/750
(23, 23, 0.400000); -- French Vanilla: 300/750

-- ============================================================
-- 4. Insert Raw Material Stock
-- ============================================================
INSERT INTO `raw_material_stock` (`stock_id`, `material_id`, `current_quantity`) VALUES
(1, 1, 25000.00),
(2, 2, 25000.00),
(3, 3, 50000.00),
(4, 4, 50000.00),
(5, 5, 50000.00),
(6, 6, 2272.00),
(7, 7, 1000.00),
(8, 8, 1000.00),
(9, 9, 50.00),
(10, 10, 25000.00),
(11, 11, 25000.00),
(12, 12, 4000.00),
(13, 13, 25000.00),
(14, 14, 30.00),
(15, 15, 1200.00),
(16, 16, 20.00),
(17, 17, 20.00),
(18, 18, 35.00),
(19, 19, 240.00),
(20, 20, 1000.00),
(21, 21, 1000.00),
(22, 22, 750.00),
(23, 23, 750.00);

-- ============================================================
-- RE-ENABLE FOREIGN KEY CHECKS
-- ============================================================
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- QUERY TO VERIFY: Get all materials with computed costs
-- ============================================================
-- SELECT 
--     rm.material_name,
--     rm.material_quantity,
--     rm.unit,
--     rmc.cost_per_unit AS 'per_gram_or_pc',
--     (rm.material_quantity * rmc.cost_per_unit) AS total_cost,
--     rms.current_quantity AS stock,
--     mc.category_name
-- FROM raw_materials rm
-- JOIN raw_material_cost rmc ON rm.cost_id = rmc.cost_id
-- JOIN raw_material_stock rms ON rm.stock_id = rms.stock_id
-- JOIN material_category mc ON rm.category_id = mc.category_id
-- ORDER BY mc.category_name, rm.material_name;
