-- =====================================================
-- SAMPLE DATA FOR TESTING ORDER SYSTEM
-- Run this in phpMyAdmin or MySQL CLI
-- Date: January 20, 2026
-- =====================================================

-- Clear existing test data (optional - uncomment if needed)
-- DELETE FROM daily_sales;
-- DELETE FROM order_item_id;
-- DELETE FROM orders;
-- DELETE FROM daily_stock_items;
-- DELETE FROM daily_stock;
-- DELETE FROM product_recipe;
-- DELETE FROM product_costs;
-- DELETE FROM products;

-- =====================================================
-- 1. INSERT SAMPLE PRODUCTS
-- =====================================================

INSERT INTO `products` (`product_id`, `category`, `product_name`, `product_description`, `date_created`) VALUES
(101, 'bread', 'Pandesal', 'Classic Filipino bread roll', NOW()),
(102, 'bread', 'Ensaymada', 'Sweet buttery pastry with cheese', NOW()),
(103, 'bread', 'Spanish Bread', 'Sweet bread with butter filling', NOW()),
(104, 'bread', 'Cheese Bread', 'Soft bread topped with cheese', NOW()),
(105, 'bread', 'Monay', 'Traditional oval-shaped bread', NOW()),
(106, 'bread', 'Putok', 'Cracked top sugar bread', NOW()),
(107, 'drinks', 'Cafe Americano', 'Hot brewed espresso with water', NOW()),
(108, 'drinks', 'Iced Coffee', 'Chilled coffee with ice', NOW()),
(109, 'drinks', 'Milk Tea', 'Creamy milk tea with pearls', NOW()),
(110, 'drinks', 'Fresh Juice', 'Fresh fruit juice', NOW());

-- =====================================================
-- 2. INSERT PRODUCT COSTS (PRICES)
-- =====================================================

INSERT INTO `product_costs` (`product_cost_id`, `product_id`, `direct_cost`, `overhead_cost_percentage`, `overhead_cost_amount`, `combined_recipe_cost`, `profit_margin_percentage`, `profit_amount`, `total_cost`, `selling_price`, `selling_price_per_tray`, `selling_price_per_piece`, `yield_grams`, `trays_per_yield`, `pieces_per_yield`, `date_created`) VALUES
(101, 101, 2.50, 20, 0.50, 0, 40, 1.20, 3.00, 5.00, 0, 5.00, 0, 0, 1, NOW()),
(102, 102, 12.00, 20, 2.40, 0, 40, 5.76, 14.40, 25.00, 0, 25.00, 0, 0, 1, NOW()),
(103, 103, 4.00, 20, 0.80, 0, 40, 1.92, 4.80, 8.00, 0, 8.00, 0, 0, 1, NOW()),
(104, 104, 6.00, 20, 1.20, 0, 40, 2.88, 7.20, 12.00, 0, 12.00, 0, 0, 1, NOW()),
(105, 105, 3.00, 20, 0.60, 0, 40, 1.44, 3.60, 6.00, 0, 6.00, 0, 0, 1, NOW()),
(106, 106, 3.50, 20, 0.70, 0, 40, 1.68, 4.20, 7.00, 0, 7.00, 0, 0, 1, NOW()),
(107, 107, 15.00, 20, 3.00, 0, 40, 7.20, 18.00, 30.00, 0, 30.00, 0, 0, 1, NOW()),
(108, 108, 17.50, 20, 3.50, 0, 40, 8.40, 21.00, 35.00, 0, 35.00, 0, 0, 1, NOW()),
(109, 109, 20.00, 20, 4.00, 0, 40, 9.60, 24.00, 40.00, 0, 40.00, 0, 0, 1, NOW()),
(110, 110, 12.50, 20, 2.50, 0, 40, 6.00, 15.00, 25.00, 0, 25.00, 0, 0, 1, NOW());

-- =====================================================
-- 3. CREATE TODAY'S DAILY INVENTORY
-- =====================================================

INSERT INTO `daily_stock` (`daily_stock_id`, `inventory_date`, `time_start`, `time_end`) VALUES
(1, CURDATE(), '06:00:00', '18:00:00');

-- =====================================================
-- 4. INSERT DAILY STOCK ITEMS (Bread products with stock)
-- =====================================================

INSERT INTO `daily_stock_items` (`item_id`, `daily_stock_id`, `product_id`, `beginning_stock`, `pull_out_quantity`, `ending_stock`) VALUES
(1, 1, 101, 100, 0, 100),  -- Pandesal: 100 pcs
(2, 1, 102, 50, 0, 50),    -- Ensaymada: 50 pcs
(3, 1, 103, 60, 0, 60),    -- Spanish Bread: 60 pcs
(4, 1, 104, 40, 0, 40),    -- Cheese Bread: 40 pcs
(5, 1, 105, 50, 0, 50),    -- Monay: 50 pcs
(6, 1, 106, 45, 0, 45);    -- Putok: 45 pcs

-- =====================================================
-- VERIFICATION QUERIES (Run these to check data)
-- =====================================================

-- Check products with prices:
-- SELECT p.product_id, p.category, p.product_name, pc.selling_price_per_piece as price
-- FROM products p
-- LEFT JOIN product_costs pc ON p.product_id = pc.product_id
-- ORDER BY p.category, p.product_name;

-- Check today's inventory:
-- SELECT ds.inventory_date, dsi.*, p.product_name
-- FROM daily_stock ds
-- JOIN daily_stock_items dsi ON ds.daily_stock_id = dsi.daily_stock_id
-- JOIN products p ON dsi.product_id = p.product_id
-- WHERE ds.inventory_date = CURDATE();

-- =====================================================
-- EXPECTED RESULTS:
-- =====================================================
-- 6 Bread products: ₱5-25 each
-- 4 Drink products: ₱25-40 each
-- Daily inventory created for today with 100 Pandesal, 50 Ensaymada, etc.
-- 
-- TEST FLOW:
-- 1. Go to Order page
-- 2. Products should load in Breads/Drinks tabs
-- 3. Add items to cart
-- 4. Click "View Cart" -> "Proceed to Checkout"
-- 5. Enter amount received, select payment method
-- 6. Click "Process Payment"
-- 7. Invoice should appear
-- 8. Check Order History page
-- =====================================================
