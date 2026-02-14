<?php
namespace App\Controllers;

class DistributionController extends BaseController
{
    public function index()
    {
        $sessionData = $this->getSessionData();
        $data = array_merge($sessionData, [
            'title' => 'Distribution',
        ]);

        if ($redirect = $this->redirectIfNotLoggedIn()) {
            return $redirect;
        }

        if ($redirect = $this->redirectIfNotOwnerAndAdmin()) {
            return $redirect;
        }

        return view('Template/Header', $data) .
            view('Template/SideNav', $data) .
            view('Template/Notification') .
            view('Distribution/Distribution', $data) .
            view('Template/Footer');
    }

    public function getDistributionByDate()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        
        $distributionData = $this->distributionModel->getDistributionByDate($date);

        // Check if inventory exists for this date
        $inventoryExists = $this->dailyStockModel->checkInventoryExists($date) ? true : false;

        return $this->response->setJSON([
            'success' => true,
            'message' => $distributionData ? 'Distribution records retrieved successfully' : 'No distribution records for this date',
            'data' => $distributionData ?: [],
            'inventory_locked' => $inventoryExists
        ]);
    }

    /**
     * Check if inventory already exists for a given date.
     * Used by the distribution page to lock editing.
     */
    public function checkInventoryByDate()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $inventory = $this->dailyStockModel->checkInventoryExists($date);

        return $this->response->setJSON([
            'success' => true,
            'inventory_exists' => $inventory ? true : false,
            'date' => $date
        ]);
    }

    /**
     * Get distribution records for a date range (for calendar view).
     */
    public function getDistributionByDateRange()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        if (!$startDate || !$endDate) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'error' => 'start_date and end_date are required'
            ]);
        }

        $distributionData = $this->distributionModel->getDistributionByDateRange($startDate, $endDate);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Distribution records retrieved successfully',
            'data' => $distributionData ?: []
        ]);
    }

    public function addDistribution()
    {
        $data = $this->request->getJSON();
        if (!$data) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid JSON data']);
        }

        // Validate required fields
        if (!isset($data->product_id, $data->product_qnty, $data->distribution_date)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing required fields']);
        }

        // Check if inventory already exists for this date — block changes
        if ($this->dailyStockModel->checkInventoryExists($data->distribution_date)) {
            return $this->response->setStatusCode(403)->setJSON([
                'error' => 'Inventory has already been created for this date. Delete the inventory first before modifying distribution.',
                'inventory_locked' => true
            ]);
        }

        // Check if this product already exists for the given date
        $existing = $this->distributionModel->existsForDate($data->product_id, $data->distribution_date);
        if ($existing) {
            return $this->response->setStatusCode(409)->setJSON([
                'error' => 'This product is already scheduled for the selected date.',
                'duplicate' => true
            ]);
        }

        // ── Pre-check: block if raw materials are insufficient ──
        $quantity = intval($data->product_qnty);
        if ($quantity > 0) {
            $preview = $this->rawMaterialStockModel->deductForProduction(
                intval($data->product_id),
                $quantity,
                true // preview only — don't actually deduct
            );

            if (!empty($preview['has_insufficient'])) {
                // Deduplicate by material_id — show total_needed per material
                $shortByMaterial = [];
                foreach ($preview['deductions'] as $d) {
                    if (!$d['insufficient']) continue;
                    $mid = $d['material_id'];
                    if (!isset($shortByMaterial[$mid])) {
                        $shortByMaterial[$mid] = $d['material_name']
                            . ' (need ' . ($d['total_needed'] ?? $d['deduct_amount']) . ' ' . $d['unit']
                            . ', have ' . $d['before'] . ')';
                    }
                }

                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'error' => 'Cannot add — insufficient raw material stock for this quantity.',
                    'insufficient_materials' => array_values($shortByMaterial),
                    'preview' => $preview,
                ]);
            }
        }

        // Insert distribution record
        $insertData = [
            'product_id' => $data->product_id,
            'product_qnty' => $data->product_qnty,
            'distribution_date' => $data->distribution_date,
        ];

        try {
            $this->distributionModel->insert($insertData);
            return $this->response->setJSON(['success' => true, 'message' => 'Distribution record added successfully']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to add distribution record']);
        }
    }

    public function deleteDistribution($id)
    {
        // Look up the distribution record to get its date
        $record = $this->distributionModel->find($id);
        if (!$record) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Distribution record not found']);
        }

        // Check if inventory already exists for this date — block deletion
        if ($this->dailyStockModel->checkInventoryExists($record['distribution_date'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'error' => 'Inventory has already been created for this date. Delete the inventory first before modifying distribution.',
                'inventory_locked' => true
            ]);
        }

        try {
            $this->distributionModel->delete($id);
            return $this->response->setJSON(['message' => 'Distribution record deleted successfully']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete distribution record']);
        }
    }

    public function updateDistribution($id)
    {
        $data = $this->request->getJSON();
        if (!$data) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid JSON data']);
        }

        // Validate required fields
        if (!isset($data->product_id, $data->product_qnty, $data->distribution_date)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing required fields']);
        }

        // Check if inventory already exists for this date — block updates
        if ($this->dailyStockModel->checkInventoryExists($data->distribution_date)) {
            return $this->response->setStatusCode(403)->setJSON([
                'error' => 'Inventory has already been created for this date. Delete the inventory first before modifying distribution.',
                'inventory_locked' => true
            ]);
        }

        // ── Pre-check: block if raw materials are insufficient for the updated quantity ──
        $newQty = intval($data->product_qnty);
        $existingRecord = $this->distributionModel->find($id);
        $oldQty = intval($existingRecord['product_qnty'] ?? 0);
        $qtyIncrease = $newQty - $oldQty;

        if ($qtyIncrease > 0) {
            $preview = $this->rawMaterialStockModel->deductForProduction(
                intval($data->product_id),
                $qtyIncrease,
                true // preview only
            );

            if (!empty($preview['has_insufficient'])) {
                $shortByMaterial = [];
                foreach ($preview['deductions'] as $d) {
                    if (!$d['insufficient']) continue;
                    $mid = $d['material_id'];
                    if (!isset($shortByMaterial[$mid])) {
                        $shortByMaterial[$mid] = $d['material_name']
                            . ' (need ' . ($d['total_needed'] ?? $d['deduct_amount']) . ' ' . $d['unit']
                            . ', have ' . $d['before'] . ')';
                    }
                }

                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'error' => 'Cannot update — insufficient raw material stock for the additional ' . $qtyIncrease . ' pieces.',
                    'insufficient_materials' => array_values($shortByMaterial),
                    'preview' => $preview,
                ]);
            }
        }

        // Update distribution record
        $updateData = [
            'product_id' => $data->product_id,
            'product_qnty' => $data->product_qnty,
            'distribution_date' => $data->distribution_date,
        ];

        try {
            $this->distributionModel->update($id, $updateData);
            return $this->response->setJSON(['message' => 'Distribution record updated successfully']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update distribution record']);
        }
    }

    public function checkDistributionToday()
    {
        $today = date('Y-m-d');
        $distributionData = $this->distributionModel->getDistributionByDate($today);

        return $this->response->setJSON([
            'success' => true,
            'message' => $distributionData ? 'Distribution records retrieved successfully' : 'No distribution records for today',
            'data' => $distributionData ?: []
        ]);
    }
}