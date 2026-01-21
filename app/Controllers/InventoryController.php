<?php

namespace App\Controllers;

class InventoryController extends BaseController
{
    public function inventory(): string
    {
        return view('Template/Header') .
            view('Template/SideNav') .
            view('Template/Notification') .
            view('Inventory/Inventory') .
            view('Template/Footer');
    }

    public function addInventory(): string
    {
        return view('Template/Header') .
            view('Template/SideNav') .
            view('Inventory/AddInventory') .
            view('Template/Footer');
    }

    public function fetchTodaysInventory()
    {
        $today = date('Y-m-d');
        $daily_stock = $this->dailyStockModel->where('inventory_date', $today)->first();

        // Check if daily_stock exists before accessing it
        if (!$daily_stock) {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'data' => [],
                'message' => 'No inventory found for today.',
            ]);
        }

        $daily_stock_items = $this->dailyStockItemsModel->fetchAllStockItems($daily_stock['daily_stock_id']);

        if ($daily_stock_items) {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'data' => $daily_stock_items,
                'message' => 'Inventory fetched successfully.'
            ]);
        } else {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => false,
                'message' => 'No inventory items found.',
                'data' => []
            ]);
        }
    }

    public function checkInventoryToday()
    {
        $today = date('Y-m-d');
        $inventory = $this->dailyStockModel->checkInventoryToday($today);

        if ($inventory) {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'Inventory exists for today.',
                'data' => $inventory
            ]);
        } else {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => false,
                'message' => 'No Inventory found for today.'
            ]);
        }
    }

    public function addTodaysInventory()
    {
        // Implementation for adding today's inventory
        $data = $this->request->getJSON(true);
        $today = date('Y-m-d');
        $insertData = [
            'inventory_date' => $today,
            'time_start' => $data['time_start'],
            'time_end' => $data['time_end'],
        ];


        if ($this->dailyStockModel->checkInventoryExists($today)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Inventory already exists for today.'
            ]);
        }

        if ($this->dailyStockModel->addTodaysInventory($insertData)) {
            $lastInsertId = $this->dailyStockModel->getInsertID();

            // fetch ALL products (bread AND drinks) for inventory tracking
            $productIds = $this->productModel->findColumn("product_id");

            // insert all products into daily stock items model
            if ($this->dailyStockItemsModel->insertDailyStockItems($lastInsertId, $productIds)) {
                return $this->response->setStatusCode(201)->setJSON([
                    'success' => true,
                    'message' => 'Today\'s inventory added successfully.'
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to add daily stock items.',
                    'error' => $this->dailyStockItemsModel->errors(),
                ]);
            }
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to add today\'s inventory.'
            ]);
        }
    }

    /**
     * Get products not yet in today's inventory (for adding mid-day)
     */
    public function getAvailableProducts()
    {
        $today = date('Y-m-d');
        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->first();

        if (!$dailyStock) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No inventory exists for today. Create inventory first.',
                'data' => []
            ]);
        }

        $products = $this->dailyStockItemsModel->getProductsNotInInventory($dailyStock['daily_stock_id']);

        return $this->response->setJSON([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Add a product to today's existing inventory
     */
    public function addProductToInventory()
    {
        $json = $this->request->getJSON();

        if (!$json || !isset($json->product_id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Product ID is required'
            ]);
        }

        $today = date('Y-m-d');
        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->first();

        if (!$dailyStock) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'No inventory exists for today. Create inventory first.'
            ]);
        }

        $beginningStock = isset($json->beginning_stock) ? intval($json->beginning_stock) : 0;
        
        $result = $this->dailyStockItemsModel->addProductToInventory(
            $dailyStock['daily_stock_id'],
            intval($json->product_id),
            $beginningStock
        );

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Product added to inventory successfully',
                'item_id' => $result
            ]);
        } else {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Product already exists in inventory or failed to add'
            ]);
        }
    }

    public function deleteTodaysInventory()
    {
        $today = date('Y-m-d');
        if ($this->dailyStockModel->deleteInventoryByDate($today)) {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'Today\'s inventory deleted successfully.'
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to delete today\'s inventory.'
            ]);
        }
    }

    public function updateStockItem($item_id)
    {
        $json = $this->request->getJSON();

        // Validate input
        if (!$json || !isset($json->beginning_stock) || !isset($json->pull_out_quantity)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid input data'
            ]);
        }

        // Validate that values are non-negative
        if ($json->beginning_stock < 0 || $json->pull_out_quantity < 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Values cannot be negative'
            ]);
        }

        // Get the item to verify it exists
        $item = $this->dailyStockItemsModel->find($item_id);

        if (!$item) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Inventory item not found'
            ]);
        }

        // Calculate how many were sold (old beginning - old pull_out - old ending)
        $oldBeginning = intval($item['beginning_stock']);
        $oldPullOut = intval($item['pull_out_quantity']);
        $oldEnding = intval($item['ending_stock']);
        $quantitySold = $oldBeginning - $oldPullOut - $oldEnding;
        if ($quantitySold < 0) $quantitySold = 0;

        // Calculate new ending stock = new beginning - new pull_out - quantity already sold
        $newBeginning = intval($json->beginning_stock);
        $newPullOut = intval($json->pull_out_quantity);
        $newEndingStock = $newBeginning - $newPullOut - $quantitySold;
        if ($newEndingStock < 0) $newEndingStock = 0;

        // Prepare update data
        $updateData = [
            'beginning_stock' => $newBeginning,
            'pull_out_quantity' => $newPullOut,
            'ending_stock' => $newEndingStock
        ];

        // Update the item
        if ($this->dailyStockItemsModel->update($item_id, $updateData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inventory item updated successfully',
                'data' => $updateData
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to update inventory item',
                'errors' => $this->dailyStockItemsModel->errors()
            ]);
        }
    }
}
