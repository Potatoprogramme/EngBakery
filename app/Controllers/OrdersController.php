<?php

namespace App\Controllers;

class OrdersController extends BaseController
{
    public function order(): string
    {
        $data = $this->getSessionData();
        return  view('Template/Header', $data).
                view('Template/SideNav', $data) . 
                view('Template/Notification', $data) .
                view('Orders/Order', $data) .
                view('Template/Footer', $data);
    }

    public function orderHistory(): string
    {
        $data = $this->getSessionData();
        return  view('Template/Header', $data).
                view('Template/SideNav', $data) . 
                view('Template/Notification', $data) .
                view('Orders/OrderHistory', $data) .
                view('Template/Footer', $data);
    }

    public function getProducts()
    {
        $products = $this->productModel->getProductsForOrdering();

        return $this->response->setJSON([
            'success' => true,
            'data' => $products
        ]);
    }

    public function processPayment()
    {
        $data = $this->request->getJSON(true);

        // Validation
        if (empty($data['items']) || !is_array($data['items'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No items in order.'
            ]);
        }

        if (!isset($data['total_payment_due']) || !isset($data['amount_received'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Payment details required.'
            ]);
        }

        if (floatval($data['amount_received']) < floatval($data['total_payment_due'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Insufficient payment amount.'
            ]);
        }

        // Check if order contains any items that need daily inventory (bakery/dough)
        // Drinks and groceries don't need inventory — they deduct raw materials directly
        $needsInventory = false;
        foreach ($data['items'] as $item) {
            $product = $this->productModel->find(intval($item['product_id']));
            if ($product && !in_array($product['category'], ['drinks', 'grocery'])) {
                $needsInventory = true;
                break;
            }
        }

        // Check for today's inventory using model method
        $dailyStock = $this->dailyStockModel->getTodaysInventory();

        if (!$dailyStock && $needsInventory) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No inventory created for today. Please create inventory first.'
            ]);
        }

        $this->db->transStart();

        try {
            // Prepare order data with cashier info from session
            $sessionData = $this->getSessionData();
            $cashierName = $sessionData['name'] ?? session()->get('name') ?? 'Unknown';
            
            // Log the cashier name for debugging
            log_message('info', 'Processing payment - Cashier: ' . $cashierName . ' | Session data: ' . print_r($sessionData, true));
            
            $orderData = [
                'total_payment_due' => $data['total_payment_due'],
                'amount_received' => $data['amount_received'],
                'amount_change' => floatval($data['amount_received']) - floatval($data['total_payment_due']),
                'payment_method' => $data['payment_method'] ?? 'cash',
                'order_type' => $data['order_type'] ?? 'walk-in',
                'cashier_name' => $cashierName
            ];

            // Create the order
            $orderId = $this->orderModel->createOrder($orderData);
            
            if (!$orderId) {
                throw new \Exception('Failed to create order.');
            }

            // Add order items
            if (!$this->orderItemModel->addOrderItems($orderId, $data['items'])) {
                throw new \Exception('Failed to add order items.');
            }

            // Update stock and record sales for each item
            foreach ($data['items'] as $item) {
                $product = $this->productModel->find(intval($item['product_id']));
                $category = $product['category'] ?? '';
                $productId = intval($item['product_id']);
                $quantity = intval($item['quantity']);

                // Drinks & groceries: deduct raw materials directly via recipe
                if (in_array($category, ['drinks', 'grocery'])) {
                    $deductResult = $this->rawMaterialStockModel->deductForProduction($productId, $quantity);
                    if (!$deductResult['success']) {
                        log_message('warning', "Raw material deduction failed for product #{$productId}: " . ($deductResult['message'] ?? ''));
                    }

                    // Still record in daily inventory for sales tracking if inventory exists
                    if ($dailyStock) {
                        $stockItem = $this->dailyStockItemsModel->getStockItemByProduct($dailyStock['daily_stock_id'], $productId);
                        if (!$stockItem) {
                            $newItemId = $this->dailyStockItemsModel->addProductToInventory(
                                $dailyStock['daily_stock_id'], $productId, 0
                            );
                            if ($newItemId) {
                                $this->transactionsModel->recordSale($newItemId, $quantity, floatval($item['total']), $orderId);
                            }
                        } else {
                            $this->transactionsModel->recordSale($stockItem['item_id'], $quantity, floatval($item['total']), $orderId);
                        }
                    }
                    continue;
                }

                // Bakery / dough items: deduct from daily inventory as before
                if (!$dailyStock) {
                    continue;
                }

                $stockItem = $this->dailyStockItemsModel->getStockItemByProduct($dailyStock['daily_stock_id'], $productId);

                if ($stockItem) {
                    $this->dailyStockItemsModel->deductStock($stockItem['item_id'], $quantity);
                    $this->transactionsModel->recordSale(
                        $stockItem['item_id'], $quantity, floatval($item['total']), $orderId
                    );
                } else {
                    $newItemId = $this->dailyStockItemsModel->addProductToInventory(
                        $dailyStock['daily_stock_id'], $productId, 0
                    );
                    if ($newItemId) {
                        $this->dailyStockItemsModel->deductStock($newItemId, $quantity);
                        $this->transactionsModel->recordSale(
                            $newItemId, $quantity, floatval($item['total']), $orderId
                        );
                    }
                }
            }

            // Prepare result - format order number as yyyy-mm-dd - order_id
            $formattedOrderNumber = date('Y-m-d') . ' - ' . $orderId;
            $result = [
                'order_id' => $orderId,
                'order_number' => $formattedOrderNumber,
                'order' => $this->orderModel->getOrderById($orderId),
                'items' => $this->orderItemModel->getOrderItems($orderId)
            ];

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaction failed.');
            }

            // Check for low stock and notify owners via email
            \App\Libraries\LowStockNotifier::checkAndNotify();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Payment processed successfully.',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getOrderHistory()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $orders = $this->orderModel->getOrderHistory($dateFrom, $dateTo);

        return $this->response->setJSON([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function getOrderDetails($orderId = null)
    {
        if (!$orderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID required.'
            ]);
        }

        $order = $this->orderModel->getOrderById($orderId);

        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found.'
            ]);
        }

        $items = $this->orderItemModel->getOrderItems($orderId);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'order' => $order,
                'items' => $items
            ]
        ]);
    }

    public function getTodaysSales()
    {
        $orderSales = $this->orderModel->getTodaysSales();
        $itemSales = $this->transactionsModel->getTodaysSummary();

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'total_orders' => intval($orderSales['total_orders'] ?? 0),
                'total_revenue' => floatval($orderSales['total_sales'] ?? 0),
                'total_items_sold' => intval($itemSales['total_items_sold'] ?? 0)
            ]
        ]);
    }

    public function voidOrder($orderId = null)
    {
        if (!$orderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID required.'
            ]);
        }

        $this->db->transStart();

        try {
            // Get the order
            $order = $this->orderModel->find($orderId);
            if (!$order) {
                throw new \Exception('Order not found.');
            }

            $orderItems = $this->orderItemModel->getOrderItems($orderId);

            // Get today's inventory (optional - only restore stock if same day)
            $dailyStock = $this->dailyStockModel->getTodaysInventory();

            // Restore stock for each item
            foreach ($orderItems as $item) {
                $product = $this->productModel->find(intval($item['product_id']));
                $category = $product['category'] ?? '';

                // Drinks & groceries: restore raw materials via recipe
                if (in_array($category, ['drinks', 'grocery'])) {
                    $this->rawMaterialStockModel->restoreForProduction(
                        intval($item['product_id']),
                        intval($item['amount'])
                    );
                }

                // Restore daily inventory stock if it exists (for all categories)
                if ($dailyStock) {
                    $stockItem = $this->dailyStockItemsModel->getStockItemByProduct($dailyStock['daily_stock_id'], $item['product_id']);
                    if ($stockItem) {
                        $this->dailyStockItemsModel->restoreStock($stockItem['item_id'], intval($item['amount']));
                    }
                }
            }

            // Delete order items and order
            $this->orderItemModel->deleteByOrderId($orderId);
            $this->orderModel->delete($orderId);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Failed to void order.');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Order voided successfully.'
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get today's stock summary for the Order History page
     */
    public function getTodaysStockSummary()
    {
        // Get today's inventory
        $dailyStock = $this->dailyStockModel->getTodaysInventory();

        if (!$dailyStock) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No inventory for today.',
                'data' => []
            ]);
        }

        // Fetch all stock items for today
        $stockItems = $this->dailyStockItemsModel->fetchAllStockItems($dailyStock['daily_stock_id']);

        // Get sales data from transactions table
        $today = date('Y-m-d');
        $salesData = $this->transactionsModel->getSalesDataByDate($today);
        $salesMap = [];
        foreach ($salesData as $sale) {
            $salesMap[$sale['item_id']] = $sale;
        }

        // Enrich stock items with actual sales data (for drinks especially)
        foreach ($stockItems as &$item) {
            $item['quantity_sold'] = $salesMap[$item['item_id']]['quantity_sold'] ?? 0;
            $item['total_sales'] = $salesMap[$item['item_id']]['total_sales'] ?? 0;
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $stockItems
        ]);
    }

    /**
     * Check if raw materials are sufficient for a drink/grocery product.
     * Called via AJAX before adding to cart.
     * GET /Order/CheckStock?product_id=X&quantity=Y
     */
    public function checkStock()
    {
        $productId = intval($this->request->getGet('product_id'));
        $quantity   = intval($this->request->getGet('quantity'));

        if ($productId <= 0 || $quantity <= 0) {
            return $this->response->setJSON(['success' => true]); // nothing to check
        }

        $product = $this->productModel->find($productId);
        if (!$product || !in_array($product['category'] ?? '', ['drinks', 'grocery'])) {
            return $this->response->setJSON(['success' => true]); // only check drinks/grocery
        }

        // Also account for items already in the cart (sent as query param)
        $existingQty = intval($this->request->getGet('existing_qty'));
        $totalQty = $quantity + $existingQty;

        $preview = $this->rawMaterialStockModel->deductForProduction($productId, $totalQty, true);

        if (!empty($preview['has_insufficient'])) {
            $shortMaterials = [];
            foreach ($preview['deductions'] as $d) {
                if ($d['insufficient']) {
                    $shortMaterials[] = $d['material_name'] . ' (need ' . round($d['total_needed'], 2) . ' ' . ($d['unit'] ?? '') . ', have ' . round($d['before'], 2) . ')';
                }
            }

            return $this->response->setJSON([
                'success' => false,
                'insufficient' => true,
                'product_name' => $product['product_name'],
                'insufficient_materials' => [
                    ($product['product_name'] ?? 'Product') . ': ' . implode(', ', array_unique($shortMaterials))
                ]
            ]);
        }

        return $this->response->setJSON(['success' => true]);
    }
}