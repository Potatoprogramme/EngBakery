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
            log_message('error', 'DISTRIBUTION ADD: Invalid JSON data received');
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid JSON data']);
        }

        log_message('info', 'DISTRIBUTION ADD: Starting - Product ID: {product}, Quantity: {qty}, Date: {date}', [
            'product' => $data->product_id ?? 'N/A',
            'qty' => $data->product_qnty ?? 'N/A',
            'date' => $data->distribution_date ?? 'N/A'
        ]);

        // Validate required fields
        if (!isset($data->product_id, $data->product_qnty, $data->distribution_date)) {
            log_message('error', 'DISTRIBUTION ADD: Missing required fields');
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing required fields']);
        }

        // Check if inventory already exists for this date — block changes
        if ($this->dailyStockModel->checkInventoryExists($data->distribution_date)) {
            log_message('warning', 'DISTRIBUTION ADD: Blocked - Inventory already exists for date: {date}', [
                'date' => $data->distribution_date
            ]);
            return $this->response->setStatusCode(403)->setJSON([
                'error' => 'Inventory has already been created for this date. Delete the inventory first before modifying distribution.',
                'inventory_locked' => true
            ]);
        }

        // Check if this product already exists for the given date
        $existing = $this->distributionModel->existsForDate($data->product_id, $data->distribution_date);
        if ($existing) {
            log_message('warning', 'DISTRIBUTION ADD: Duplicate - Product {product} already scheduled for {date}', [
                'product' => $data->product_id,
                'date' => $data->distribution_date
            ]);
            return $this->response->setStatusCode(409)->setJSON([
                'error' => 'This product is already scheduled for the selected date.',
                'duplicate' => true
            ]);
        }

        // ── Pre-check: block if raw materials are insufficient ──
        $quantity = intval($data->product_qnty);
        // Distribution qty = batches → convert to actual pieces for deduction
        $actualPieces = $this->distributionQtyToPieces(intval($data->product_id), $quantity);
        log_message('info', 'DISTRIBUTION ADD: Converted {batches} batches to {pieces} pieces', [
            'batches' => $quantity,
            'pieces' => $actualPieces
        ]);

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

                log_message('error', 'DISTRIBUTION ADD: Insufficient raw materials - {materials}', [
                    'materials' => json_encode(array_values($shortByMaterial))
                ]);

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
            $distributionId = $this->distributionModel->getInsertID();
            log_message('info', 'DISTRIBUTION ADD: Record inserted - ID: {id}', ['id' => $distributionId]);

            // Actually deduct raw materials now (not at inventory creation)
            if ($actualPieces > 0) {
                log_message('info', 'DISTRIBUTION ADD: Deducting raw materials for {pieces} pieces', ['pieces' => $actualPieces]);
                $deductResult = $this->rawMaterialStockModel->deductForProduction(
                    intval($data->product_id),
                    $actualPieces,
                    false // actually deduct
                );
                log_message('info', 'DISTRIBUTION ADD: Raw materials deducted successfully - {result}', [
                    'result' => json_encode($deductResult)
                ]);
            }

            // Check for low stock and notify owners
            \App\Libraries\LowStockNotifier::checkAndNotify();

            log_message('info', 'DISTRIBUTION ADD: Completed successfully for Product {product}', [
                'product' => $data->product_id
            ]);

            return $this->response->setJSON(['success' => true, 'message' => 'Distribution record added successfully']);
        } catch (\Exception $e) {
            log_message('error', 'DISTRIBUTION ADD: Exception - {message}', ['message' => $e->getMessage()]);
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to add distribution record']);
        }
    }

    public function deleteDistribution($id)
    {
        log_message('info', 'DISTRIBUTION DELETE: Starting for ID: {id}', ['id' => $id]);

        // Look up the distribution record to get its date
        $record = $this->distributionModel->find($id);
        if (!$record) {
            log_message('error', 'DISTRIBUTION DELETE: Record not found - ID: {id}', ['id' => $id]);
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Distribution record not found']);
        }

        log_message('info', 'DISTRIBUTION DELETE: Found record - Product: {product}, Qty: {qty}, Date: {date}', [
            'product' => $record['product_id'],
            'qty' => $record['product_qnty'],
            'date' => $record['distribution_date']
        ]);

        // Check if inventory already exists for this date — block deletion
        if ($this->dailyStockModel->checkInventoryExists($record['distribution_date'])) {
            log_message('warning', 'DISTRIBUTION DELETE: Blocked - Inventory exists for date: {date}', [
                'date' => $record['distribution_date']
            ]);
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
                log_message('info', 'DISTRIBUTION DELETE: Restoring {pieces} pieces to raw materials', [
                    'pieces' => $actualPieces
                ]);
                $restoreResult = $this->rawMaterialStockModel->restoreForProduction($productId, $actualPieces);
                log_message('info', 'DISTRIBUTION DELETE: Raw materials restored - {result}', [
                    'result' => json_encode($restoreResult)
                ]);
            }

            $this->distributionModel->delete($id);
            log_message('info', 'DISTRIBUTION DELETE: Record deleted successfully - ID: {id}', ['id' => $id]);

            return $this->response->setJSON(['message' => 'Distribution record deleted successfully']);
        } catch (\Exception $e) {
            log_message('error', 'DISTRIBUTION DELETE: Exception - {message}', ['message' => $e->getMessage()]);
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete distribution record']);
        }
    }

    public function updateDistribution($id)
    {
        $data = $this->request->getJSON();
        if (!$data) {
            log_message('error', 'DISTRIBUTION UPDATE: Invalid JSON data for ID: {id}', ['id' => $id]);
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid JSON data']);
        }

        log_message('info', 'DISTRIBUTION UPDATE: Starting - ID: {id}, Product: {product}, New Qty: {qty}, Date: {date}', [
            'id' => $id,
            'product' => $data->product_id ?? 'N/A',
            'qty' => $data->product_qnty ?? 'N/A',
            'date' => $data->distribution_date ?? 'N/A'
        ]);

        // Validate required fields
        if (!isset($data->product_id, $data->product_qnty, $data->distribution_date)) {
            log_message('error', 'DISTRIBUTION UPDATE: Missing required fields for ID: {id}', ['id' => $id]);
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing required fields']);
        }

        // Check if inventory already exists for this date — block updates
        if ($this->dailyStockModel->checkInventoryExists($data->distribution_date)) {
            log_message('warning', 'DISTRIBUTION UPDATE: Blocked - Inventory exists for date: {date}', [
                'date' => $data->distribution_date
            ]);
            return $this->response->setStatusCode(403)->setJSON([
                'error' => 'Inventory has already been created for this date. Delete the inventory first before modifying distribution.',
                'inventory_locked' => true
            ]);
        }

        // ── Restore-then-deduct approach: restore old qty fully, then deduct new qty fully ──
        // This ensures consistency regardless of intermediate inventory operations.
        $existingRecord = $this->distributionModel->find($id);
        $oldQty = intval($existingRecord['product_qnty'] ?? 0);
        $newQty = intval($data->product_qnty);
        $oldPieces = $this->distributionQtyToPieces(intval($existingRecord['product_id']), $oldQty);
        $newPieces = $this->distributionQtyToPieces(intval($data->product_id), $newQty);

        log_message('info', 'DISTRIBUTION UPDATE: Quantity change - Old: {old} batches ({oldPieces} pieces), New: {new} batches ({newPieces} pieces)', [
            'old' => $oldQty,
            'new' => $newQty,
            'oldPieces' => $oldPieces,
            'newPieces' => $newPieces
        ]);

        // Step 1: Restore all raw materials for the old quantity
        if ($oldPieces > 0) {
            log_message('info', 'DISTRIBUTION UPDATE: Step 1 - Restoring {pieces} pieces (old qty) to raw materials', [
                'pieces' => $oldPieces
            ]);
            $restoreResult = $this->rawMaterialStockModel->restoreForProduction(
                intval($existingRecord['product_id']),
                $oldPieces
            );
            log_message('info', 'DISTRIBUTION UPDATE: Old raw materials restored - {result}', [
                'result' => json_encode($restoreResult)
            ]);
        }

        // Step 2: Pre-check if raw materials are sufficient for the full new quantity
        if ($newPieces > 0) {
            log_message('info', 'DISTRIBUTION UPDATE: Step 2 - Checking raw materials for full new qty of {pieces} pieces', [
                'pieces' => $newPieces
            ]);
            $preview = $this->rawMaterialStockModel->deductForProduction(
                intval($data->product_id),
                $newPieces,
                true // preview only
            );

            if (!empty($preview['has_insufficient'])) {
                // Rollback: re-deduct the old amount since we already restored it
                log_message('warning', 'DISTRIBUTION UPDATE: Insufficient materials - rolling back restoration');
                if ($oldPieces > 0) {
                    $this->rawMaterialStockModel->deductForProduction(
                        intval($existingRecord['product_id']),
                        $oldPieces,
                        false
                    );
                }

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

                log_message('error', 'DISTRIBUTION UPDATE: Insufficient raw materials - {materials}', [
                    'materials' => json_encode(array_values($shortByMaterial))
                ]);

                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'error' => 'Cannot update — insufficient raw material stock for ' . $newQty . ' batch(es).',
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
            // Step 3: Deduct raw materials for the full new quantity
            if ($newPieces > 0) {
                log_message('info', 'DISTRIBUTION UPDATE: Step 3 - Deducting {pieces} pieces (full new qty) from raw materials', [
                    'pieces' => $newPieces
                ]);
                $deductResult = $this->rawMaterialStockModel->deductForProduction(
                    intval($data->product_id),
                    $newPieces,
                    false
                );
                log_message('info', 'DISTRIBUTION UPDATE: Raw materials deducted - {result}', [
                    'result' => json_encode($deductResult)
                ]);
            }

            $this->distributionModel->update($id, $updateData);
            log_message('info', 'DISTRIBUTION UPDATE: Record updated successfully - ID: {id}', ['id' => $id]);

            // Check for low stock and notify owners
            \App\Libraries\LowStockNotifier::checkAndNotify();

            log_message('info', 'DISTRIBUTION UPDATE: Completed successfully for ID: {id}', ['id' => $id]);

            return $this->response->setJSON(['message' => 'Distribution record updated successfully']);
        } catch (\Exception $e) {
            log_message('error', 'DISTRIBUTION UPDATE: Exception - {message}', ['message' => $e->getMessage()]);
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