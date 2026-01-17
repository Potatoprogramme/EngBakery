<?php
namespace App\Controllers;

class DailyStockController extends BaseController
{
    public function testView()
    {
        return view('TestViews/DailyStockTestView');
    }

    public function checkIfInventoryExists()
    {
        $dateToday = date('Y-m-d');
        $existingRecord = $this->dailyStockModel->checkInventoryExistsToday($dateToday);
        if ($existingRecord) {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'Inventory already exists for today.',
            ]);
        } else {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => false,
                'message' => 'Inventory does not exist for today. Create a new one.',
            ]);
        }
    }
    public function createParticular()
    {
        $data = $this->request->getJSON(true);

        $createData = [
            'daily_stock_id' => $data['daily_stock_id'],
            'beginning_stock' => $data['beginning_stock'],
            'pull_out_stock' => $data['pull_out_stock'],
            'ending_stock' => $data['ending_stock'],
        ];

        $this->dailyStockModel->insert($createData);

        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'message' => 'Daily stock particular created successfully.',
        ]);
    }
    public function updateParticular()
    {
        $data = $this->request->getJSON(true);

        $createData = [
            'product_id' => $data['product_id'],
            'beginning_quantity' => $data['beginning_quantity'],
            'pulled_out_quantity' => $data['pulled_out_quantity'],
            'ending_quantity' => $data['ending_quantity'],
            'date_created' => date('Y-m-d'),
            'time_created' => date('H:i:s'),
        ];

        $this->dailyStockModel->insert($createData);

        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'message' => 'Daily stock particular created successfully.',
        ]);
    }
}