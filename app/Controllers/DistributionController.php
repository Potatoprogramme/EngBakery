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
        // Distribution qty = batches → convert to actual pieces for deduction
        $actualPieces = $this->distributionQtyToPieces(intval($data->product_id), $quantity);
        if ($actualPieces > 0) {
            $preview = $this->rawMaterialStockModel->deductForProduction(
                intval($data->product_id),
                $actualPieces,
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

            // Actually deduct raw materials now (not at inventory creation)
            if ($actualPieces > 0) {
                $deductResult = $this->rawMaterialStockModel->deductForProduction(
                    intval($data->product_id),
                    $actualPieces,
                    false // actually deduct
                );
                log_message('info', 'Distribution deduction for product ' . $data->product_id . ' x' . $quantity . ' batches (' . $actualPieces . ' pieces): ' . json_encode($deductResult));
            }

            // Check for low stock and notify owners
            \App\Libraries\LowStockNotifier::checkAndNotify();

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
            // Restore raw materials before deleting the distribution
            $productId = intval($record['product_id']);
            $quantity  = intval($record['product_qnty']);
            $actualPieces = $this->distributionQtyToPieces($productId, $quantity);
            if ($actualPieces > 0) {
                $restoreResult = $this->rawMaterialStockModel->restoreForProduction($productId, $actualPieces);
                log_message('info', 'Distribution delete — restored materials for product ' . $productId . ' x' . $quantity . ' batches (' . $actualPieces . ' pieces): ' . json_encode($restoreResult));
            }

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
        // Convert batch delta to actual pieces
        $piecesIncrease = $this->distributionQtyToPieces(intval($data->product_id), abs($qtyIncrease));
        if ($qtyIncrease < 0) $piecesIncrease = -$piecesIncrease;

        if ($piecesIncrease > 0) {
            $preview = $this->rawMaterialStockModel->deductForProduction(
                intval($data->product_id),
                $piecesIncrease,
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
                    'error' => 'Cannot update — insufficient raw material stock for the additional ' . $qtyIncrease . ' batch(es).',
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
            // Handle raw material delta: deduct increase or restore decrease
            if ($piecesIncrease > 0) {
                // Quantity went up — deduct the additional amount
                $deductResult = $this->rawMaterialStockModel->deductForProduction(
                    intval($data->product_id),
                    $piecesIncrease,
                    false
                );
                log_message('info', 'Distribution update — deducted +' . $qtyIncrease . ' batches (' . $piecesIncrease . ' pieces) of product ' . $data->product_id);
            } elseif ($piecesIncrease < 0) {
                // Quantity went down — restore the difference
                $restorePieces = abs($piecesIncrease);
                $restoreResult = $this->rawMaterialStockModel->restoreForProduction(
                    intval($data->product_id),
                    $restorePieces
                );
                log_message('info', 'Distribution update — restored ' . abs($qtyIncrease) . ' batches (' . $restorePieces . ' pieces) of product ' . $data->product_id);
            }

            $this->distributionModel->update($id, $updateData);

            // Check for low stock and notify owners
            \App\Libraries\LowStockNotifier::checkAndNotify();

            return $this->response->setJSON(['message' => 'Distribution record updated successfully']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update distribution record']);
        }
    }

    /**
     * Convert distribution quantity to actual pieces.
     * Distribution qty = batches/yields for bakery & dough products.
     * For drinks/grocery, 1 qty = 1 piece (pieces_per_yield defaults to 1).
     */
    private function distributionQtyToPieces(int $productId, int $qty): int
    {
        $product = $this->productModel->find($productId);
        $category = $product['category'] ?? '';

        // Drinks & grocery: 1 distribution qty = 1 serving
        if (in_array($category, ['drinks', 'grocery'])) {
            return $qty;
        }

        // Bakery & dough: 1 distribution qty = 1 batch = pieces_per_yield pieces
        $costData = model('ProductCostModel')->getCostByProductId($productId);
        $piecesPerYield = intval($costData['pieces_per_yield'] ?? 0);

        if ($piecesPerYield <= 0) {
            $piecesPerYield = 1;
        }

        return $qty * $piecesPerYield;
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