<?php

namespace App\Controllers;

class OrdersController extends BaseController
{
    public function order(): string
    {
        $data = $this->getSessionData();
        return view('Template/Header', $data) .
            view('Template/SideNav', $data) .
            view('Template/Notification', $data) .
            view('Orders/Order', $data) .
            view('Template/Footer', $data);
    }

    public function orderHistory(): string
    {
        $data = $this->getSessionData();
        return view('Template/Header', $data) .
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

        // Validate distributed note if order type is distributed
        $orderType = $data['order_type'] ?? 'walk-in';
        if ($orderType === 'distributed' && empty(trim($data['distributed_note'] ?? ''))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Outlet/delivery details are required for distributed orders.'
            ]);
        }

        // Check if order contains any items that need daily inventory (bakery/dough)
        // Drinks and groceries don't need inventory — they deduct raw materials directly
        $needsInventory = false;
        foreach ($data['items'] as $item) {
            $product = $this->productModel->findActiveForOrdering(intval($item['product_id']));
            if ($product && !in_array($product['category'], ['drinks', 'grocery'])) {
                $needsInventory = true;
                break;
            }
        }

        // Check for today's inventory using model method
        $dailyStock = $this->dailyStockModel->getActiveTodaysInventory();

        if (!$dailyStock && $needsInventory) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No inventory created for today. Please create inventory first.'
            ]);
        } else if ($dailyStock['is_closed'] == 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Oops, inventory is closed. Open it first to process orders!'
            ]);
        }

        // Hard-stop validation before creating order or deducting stock
        $stockValidation = $this->validateOrderStock($data['items'], $dailyStock);
        if (!$stockValidation['success']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $stockValidation['message'],
                'insufficient_products' => $stockValidation['insufficient_products'] ?? [],
                'insufficient_ingredients' => $stockValidation['insufficient_ingredients'] ?? [],
                'insufficient_materials' => $stockValidation['insufficient_materials'] ?? [],
            ]);
        }

        $this->db->transStart();

        try {
            // Prepare order data with cashier info from session
            $sessionData = $this->getSessionData();

            // Get cashier user ID from session
            $cashierUserId = intval($sessionData['user_id'] ?? session()->get('user_id') ?? 0);
            log_message('info', 'Processing payment - Cashier User ID: ' . $cashierUserId . ' | Session data: ' . print_r($sessionData, true));

            $orderData = [
                'total_payment_due' => $data['total_payment_due'],
                'amount_received' => $data['amount_received'],
                'amount_change' => floatval($data['amount_received']) - floatval($data['total_payment_due']),
                'payment_method' => $data['payment_method'] ?? 'cash',
                'order_type' => $orderType,
                'distributed_note' => $orderType === 'distributed' ? trim($data['distributed_note'] ?? '') : null,
                'cashier_id' => $cashierUserId // Now stores user_id
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
                $product = $this->productModel->findActiveForOrdering(intval($item['product_id']));
                if (!$product) {
                    throw new \Exception('Order cannot be completed: one or more products are disabled or unavailable.');
                }

                $category = $product['category'] ?? '';
                $productId = intval($item['product_id']);
                $quantity = intval($item['quantity']);

                // Drinks & groceries: deduct raw materials directly via recipe
                if (in_array($category, ['drinks', 'grocery'])) {
                    $deductResult = $this->rawMaterialStockModel->deductForProduction($productId, $quantity);
                    if (
                        !$deductResult['success'] ||
                        !empty($deductResult['has_insufficient'])
                    ) {
                        $shortNames = [];
                        foreach (($deductResult['deductions'] ?? []) as $d) {
                            if (!empty($d['insufficient']) && !empty($d['material_name'])) {
                                $shortNames[] = $d['material_name'];
                            }
                        }

                        $shortNames = array_values(array_unique($shortNames));
                        $suffix = !empty($shortNames)
                            ? ' (' . implode(', ', $shortNames) . ')'
                            : '';

                        throw new \Exception(
                            'Order cannot be completed: insufficient ingredients' . $suffix
                        );
                    }

                    // Still record in daily inventory for sales tracking if inventory exists
                    if ($dailyStock) {
                        $stockItem = $this->dailyStockItemsModel->getStockItemByProduct($dailyStock['daily_stock_id'], $productId);
                        if (!$stockItem) {
                            $newItemId = $this->dailyStockItemsModel->addProductToInventory(
                                $dailyStock['daily_stock_id'],
                                $productId,
                                0
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
                        $stockItem['item_id'],
                        $quantity,
                        floatval($item['total']),
                        $orderId
                    );
                } else {
                    $newItemId = $this->dailyStockItemsModel->addProductToInventory(
                        $dailyStock['daily_stock_id'],
                        $productId,
                        0
                    );
                    if ($newItemId) {
                        $this->dailyStockItemsModel->deductStock($newItemId, $quantity);
                        $this->transactionsModel->recordSale(
                            $newItemId,
                            $quantity,
                            floatval($item['total']),
                            $orderId
                        );
                    }
                }
            }

            // Keep one consistent order number format across table, receipt, and API.
            $formattedOrderNumber = $this->orderModel->generateOrderNumber($orderId);
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
        $orderType = $this->request->getGet('order_type');

        $orders = $this->orderModel->getOrderHistory($dateFrom, $dateTo, $orderType);

        // Replace cashier_id (user_id) with actual name for each order
        foreach ($orders as &$order) {
            $order['cashier_display_name'] = $this->usersModel->getFullName($order['cashier_id'] ?? null);
        }
        unset($order);

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

        // Replace cashier_id (user_id) with actual name for display
        $order['cashier_display_name'] = $this->usersModel->getFullName($order['cashier_id'] ?? null);

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

    /**
     * Get order history summary based on filters.
     * GET /Order/GetOrderHistorySummary?date_from=YYYY-MM-DD&date_to=YYYY-MM-DD&order_type=...
     */
    public function getOrderHistorySummary()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $orderType = $this->request->getGet('order_type');

        $summary = $this->orderModel->getOrderHistorySummary($dateFrom, $dateTo, $orderType);

        return $this->response->setJSON([
            'success' => true,
            'data' => $summary,
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

            // Check if already voided
            if (!empty($order['voided_at'])) {
                throw new \Exception('Order is already voided.');
            }

            $orderItems = $this->orderItemModel->getOrderItems($orderId);

            // Get today's inventory (optional - only restore stock if same day)
            $dailyStock = $this->dailyStockModel->getActiveTodaysInventory();

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

            // Remove related sales transactions so inventory qty sold is refunded
            $this->transactionsModel
                ->where('order_id', intval($orderId))
                ->set(['deleted_at' => date('Y-m-d H:i:s')])
                ->update();

            // Soft delete: mark as voided instead of deleting
            $cashierUserId = intval(session()->get('user_id') ?? 0);
            $this->orderModel->update($orderId, [
                'voided_at' => date('Y-m-d H:i:s'),
                'voided_by' => $cashierUserId
            ]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Failed to void order.');
            }

            // Immediate notification: order voided
            $totalAmount = floatval($order['total_payment_due'] ?? 0);
            $this->notify('notifyOrderVoided', $orderId, $totalAmount);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Order voided successfully.'
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'order_id' => $orderId
                ]
            ]);
        }
    }

    public function deleteOrder($orderId = null)
    {
        if (!$orderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID required.'
            ]);
        }

        $sessionData = $this->getSessionData();
        $employeeType = strtolower((string) ($sessionData['employee_type'] ?? session()->get('employee_type') ?? ''));

        if ($employeeType !== 'owner') {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'You do not have permission to delete orders.'
            ]);
        }

        $this->db->transStart();

        try {
            $order = $this->orderModel->find($orderId);
            if (!$order) {
                throw new \Exception('Order not found.');
            }

            // Restore stock and raw materials for each item (like void)
            $orderItems = $this->orderItemModel->getOrderItems($orderId);
            $dailyStock = $this->dailyStockModel->getActiveTodaysInventory();

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

            // Remove related sales transactions and order items
            $this->transactionsModel->deleteByOrderId(intval($orderId));
            $this->orderItemModel->deleteByOrderId(intval($orderId));

            if ($this->orderModel->delete(intval($orderId)) === false) {
                throw new \Exception('Failed to delete order.');
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Failed to delete order.');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Order deleted successfully.'
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
     * Helper: Get user name by user_id (for displaying cashier name)
     */
    private function getUserNameById($userId)
    {
        if (!$userId) return 'Unknown';
        $userModel = model('UserModel');
        $user = $userModel->find($userId);
        return $user['name'] ?? $user['username'] ?? 'Unknown';
    }

    /**
     * Get today's stock summary for the Order History page
     */
    public function getTodaysStockSummary()
    {
        // Optional date parameter for filtered history views; defaults to today
        $date = $this->request->getGet('date') ?: date('Y-m-d');

        // Get inventory for the requested date
        $dailyStock = $this->dailyStockModel->where('inventory_date', $date)->first();

        if (!$dailyStock) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No inventory for selected date.',
                'data' => []
            ]);
        }

        // Fetch all stock items for today
        $stockItems = $this->dailyStockItemsModel->fetchAllStockItems($dailyStock['daily_stock_id']);

        // Get sales data from transactions table
        $salesData = $this->transactionsModel->getSalesDataByDate($date);
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
        $quantity = intval($this->request->getGet('quantity'));

        if ($productId <= 0 || $quantity <= 0) {
            return $this->response->setJSON(['success' => true]); // nothing to check
        }

        $product = $this->productModel->findActiveForOrdering($productId);
        if (!$product) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Product is disabled or unavailable.'
            ]);
        }

        if (!in_array($product['category'] ?? '', ['drinks', 'grocery'])) {
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

    /**
     * Validate stock for a full cart without committing any deduction.
     * POST /Order/ValidateCartStock
     */
    public function validateCartStock()
    {
        $data = $this->request->getJSON(true);
        $items = $data['items'] ?? [];

        if (empty($items) || !is_array($items)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No items in order.'
            ]);
        }

        $needsInventory = false;
        foreach ($items as $item) {
            $product = $this->productModel->findActiveForOrdering(intval($item['product_id'] ?? 0));
            if ($product && !in_array($product['category'] ?? '', ['drinks', 'grocery'])) {
                $needsInventory = true;
                break;
            }
        }

        $dailyStock = $this->dailyStockModel->getActiveTodaysInventory();
        if (!$dailyStock && $needsInventory) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No inventory created for today. Please create inventory first.'
            ]);
        }

        $validation = $this->validateOrderStock($items, $dailyStock);
        return $this->response->setJSON($validation);
    }

    /**
     * Validate full-cart stock rules:
     * - Bakery/dough: quantity must not exceed daily inventory ending stock
     * - Drinks/grocery: aggregate required raw materials across all items
     */
    private function validateOrderStock(array $items, ?array $dailyStock): array
    {
        $normalizedItems = [];

        foreach ($items as $item) {
            $productId = intval($item['product_id'] ?? 0);
            $quantity = intval($item['quantity'] ?? 0);

            if ($productId <= 0 || $quantity <= 0) {
                continue;
            }

            if (!isset($normalizedItems[$productId])) {
                $normalizedItems[$productId] = [
                    'product_id' => $productId,
                    'quantity' => 0,
                ];
            }

            $normalizedItems[$productId]['quantity'] += $quantity;
        }

        if (empty($normalizedItems)) {
            return [
                'success' => false,
                'message' => 'No valid order items found.'
            ];
        }

        $bakeryShortages = [];
        $productNeeds = [];
        $materialMeta = [];

        foreach ($normalizedItems as $normalized) {
            $productId = $normalized['product_id'];
            $quantity = $normalized['quantity'];

            $product = $this->productModel->findActiveForOrdering($productId);
            if (!$product) {
                return [
                    'success' => false,
                    'message' => 'Order cannot be completed: one or more products are disabled or unavailable.'
                ];
            }

            $productName = $product['product_name'] ?? ('Product #' . $productId);
            $category = $product['category'] ?? '';

            if (in_array($category, ['drinks', 'grocery'])) {
                $preview = $this->rawMaterialStockModel->deductForProduction($productId, $quantity, true);

                if (!$preview['success']) {
                    return [
                        'success' => false,
                        'message' => 'Order cannot be completed: unable to validate ingredients for ' . $productName . '. ' . ($preview['message'] ?? '')
                    ];
                }

                $perProductNeeds = [];
                foreach (($preview['deductions'] ?? []) as $deduction) {
                    $materialId = intval($deduction['material_id'] ?? 0);
                    $deductAmount = floatval($deduction['deduct_amount'] ?? 0);

                    if ($materialId <= 0 || $deductAmount <= 0) {
                        continue;
                    }

                    if (!isset($perProductNeeds[$materialId])) {
                        $perProductNeeds[$materialId] = 0.0;
                    }
                    $perProductNeeds[$materialId] += $deductAmount;

                    if (!isset($materialMeta[$materialId])) {
                        $materialMeta[$materialId] = [
                            'name' => $deduction['material_name'] ?? ('Material #' . $materialId),
                            'unit' => $deduction['unit'] ?? '',
                            'available' => floatval($deduction['before'] ?? 0),
                        ];
                    }
                }

                $productNeeds[] = [
                    'product_name' => $productName,
                    'materials' => $perProductNeeds,
                ];

                continue;
            }

            if (!$dailyStock) {
                $bakeryShortages[] = $productName . ' (need ' . $quantity . ', have 0)';
                continue;
            }

            $stockItem = $this->dailyStockItemsModel->getStockItemByProduct(
                intval($dailyStock['daily_stock_id']),
                $productId
            );

            $available = intval($stockItem['ending_stock'] ?? 0);
            if ($available < $quantity) {
                $bakeryShortages[] = $productName . ' (need ' . $quantity . ', have ' . $available . ')';
            }
        }

        if (!empty($bakeryShortages)) {
            return [
                'success' => false,
                'message' => 'Order cannot be completed: insufficient bakery stock.',
                'insufficient_products' => $bakeryShortages,
                'insufficient_materials' => $bakeryShortages,
            ];
        }

        $materialUsed = [];
        $insufficientProducts = [];
        $insufficientMaterials = [];
        $insufficientIngredients = [];

        foreach ($productNeeds as $productNeed) {
            $productName = $productNeed['product_name'];
            $shortDetails = [];

            foreach ($productNeed['materials'] as $materialId => $requiredAmount) {
                $alreadyUsed = floatval($materialUsed[$materialId] ?? 0);
                $available = floatval($materialMeta[$materialId]['available'] ?? 0);
                $projectedUsage = $alreadyUsed + $requiredAmount;

                if ($projectedUsage > $available) {
                    $name = $materialMeta[$materialId]['name'] ?? ('Material #' . $materialId);
                    $unit = trim((string) ($materialMeta[$materialId]['unit'] ?? ''));
                    $insufficientIngredients[$materialId] = $name;
                    $shortDetails[] = $name . ' (need ' . round($projectedUsage, 2) . ($unit !== '' ? ' ' . $unit : '') . ', have ' . round($available, 2) . ')';
                }

                $materialUsed[$materialId] = $projectedUsage;
            }

            if (!empty($shortDetails)) {
                $insufficientProducts[] = $productName;
                $insufficientMaterials[] = $productName . ': ' . implode(', ', $shortDetails);
            }
        }

        if (!empty($insufficientMaterials)) {
            $ingredientNames = array_values(array_unique(array_values($insufficientIngredients)));

            return [
                'success' => false,
                'message' => 'Order cannot be completed: insufficient ingredients (' . implode(', ', $ingredientNames) . ')',
                'insufficient_products' => array_values(array_unique($insufficientProducts)),
                'insufficient_ingredients' => $ingredientNames,
                'insufficient_materials' => $insufficientMaterials,
            ];
        }

        return ['success' => true];
    }
}