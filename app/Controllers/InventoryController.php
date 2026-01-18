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

    public function checkInventoryToday()
    {
        $today = date('Y-m-d');
        $inventory = $this->dailyStockModel->checkInventoryToday($today);

        if ($inventory) {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'Inventory exists for today.'
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

            // fetch all products first
            $products = $this->productModel->where('category', 'bread')->findAll()['product_id'];
            // insert all bread items into daily stock items model
            // $this->dailyStockModel->insertDailyStockItems($lastInsertId, $products);

            return $this->response->setStatusCode(201)->setJSON([
                'success' => true,
                'message' => 'Today\'s inventory added successfully.'
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to add today\'s inventory.'
            ]);
        }
    }
}
