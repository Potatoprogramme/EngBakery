<?php

namespace App\Controllers;

class OrdersController extends BaseController
{
    public function order(): string
    {
        return  view('Template/Header').
                view('Template/SideNav') . 
                view('Template/notification') .
                view('Orders/Order') .
                view('Template/Footer');
    }

    public function orderHistory(): string
    {
        return  view('Template/Header').
                view('Template/SideNav') . 
                view('Template/notification') .
                view('Orders/OrderHistory') .
                view('Template/Footer');
    }

    public function getProducts()
    {
        // Use ProductModel method instead of raw query in controller
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

        // Check for today's inventory using model method
        $dailyStock = $this->dailyStockModel->getTodaysInventory();

        if (!$dailyStock) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No inventory created for today. Please create inventory first.'
            ]);
        }

        $this->db->transStart();

        try {
            // Prepare order data
            $orderData = [
                'total_payment_due' => $data['total_payment_due'],
                'amount_received' => $data['amount_received'],
                'amount_change' => floatval($data['amount_received']) - floatval($data['total_payment_due']),
                'payment_method' => $data['payment_method'] ?? 'cash',
                'order_type' => $data['order_type'] ?? 'walk-in'
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
                $stockItem = $this->dailyStockItemsModel->getStockItemByProduct($dailyStock['daily_stock_id'], intval($item['product_id']));

                if ($stockItem) {
                    // Deduct from ending stock
                    $this->dailyStockItemsModel->deductStock($stockItem['item_id'], intval($item['quantity']));

                    // Record the sale in transactions table
                    $this->transactionsModel->recordSale(
                        $stockItem['item_id'],
                        intval($item['quantity']),
                        floatval($item['total'])
                    );
                } else {
                    // Product not in inventory - auto-add it with 0 beginning stock
                    $newItemId = $this->dailyStockItemsModel->addProductToInventory(
                        $dailyStock['daily_stock_id'],
                        intval($item['product_id']),
                        0 // beginning_stock = 0
                    );
                    
                    if ($newItemId) {
                        // Now deduct and record the sale
                        $this->dailyStockItemsModel->deductStock($newItemId, intval($item['quantity']));
                        $this->transactionsModel->recordSale(
                            $newItemId,
                            intval($item['quantity']),
                            floatval($item['total'])
                        );
                    }
                }
            }

            // Prepare result
            $result = [
                'order_id' => $orderId,
                'order_number' => $this->orderModel->generateOrderNumber(),
                'order' => $this->orderModel->getOrderById($orderId),
                'items' => $this->orderItemModel->getOrderItems($orderId)
            ];

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaction failed.');
            }

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

            // Restore stock if we have today's inventory
            if ($dailyStock) {
                foreach ($orderItems as $item) {
                    $stockItem = $this->dailyStockItemsModel->getStockItemByProduct($dailyStock['daily_stock_id'], $item['product_id']);
                    if ($stockItem) {
                        $this->dailyStockItemsModel->restoreStock($stockItem['item_id'], intval($item['amout']));
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

        return $this->response->setJSON([
            'success' => true,
            'data' => $stockItems
        ]);
    }
}