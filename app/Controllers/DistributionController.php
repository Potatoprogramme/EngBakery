<?php

namespace App\Controllers;

use App\Libraries\DistributionQuantityCalculator;

class DistributionController extends BaseController
{
    public function index()
    {
        $sessionData = $this->getSessionData();
        $data = array_merge($sessionData, ['title' => 'Distribution']);

        if ($redirect = $this->redirectIfNotLoggedIn())
            return $redirect;
        if ($redirect = $this->redirectIfNotOwnerAndAdmin())
            return $redirect;

        return view('Template/Header', $data)
            . view('Template/SideNav', $data)
            . view('Template/Notification')
            . view('Distribution/Distribution', $data)
            . view('Template/Footer');
    }

    public function getDistributionByDate()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');

        $groups = model('DistributionGroupModel')->getGroupsByDate($date);
        $inventoryLocked = (bool) $this->dailyStockModel->checkInventoryExists($date);

        return $this->response->setJSON([
            'success' => true,
            'message' => $groups ? 'Distribution groups retrieved' : 'No distribution groups for this date',
            'data' => $groups,
            'inventory_locked' => $inventoryLocked,
        ]);
    }

    public function getDistributionByDateRange()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        if (!$startDate || !$endDate) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'error' => 'start_date and end_date are required',
            ]);
        }

        $groups = model('DistributionGroupModel')->getGroupsByDateRange($startDate, $endDate);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Distribution groups retrieved',
            'data' => $groups,
        ]);
    }

    public function getGroup(int $id)
    {
        $group = model('DistributionGroupModel')->getGroupWithItems($id);

        if (!$group) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'error' => 'Distribution group not found',
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $group,
        ]);
    }

    public function addGroup()
    {
        $data = $this->request->getJSON();
        if (!$data) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid JSON data']);
        }

        if (empty($data->title) || empty($data->distribution_date)) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'title and distribution_date are required',
            ]);
        }

        $insertData = [
            'title' => trim($data->title),
            'distribution_date' => $data->distribution_date,
            'distributed_to_note' => $data->distributed_to_note ?? null,
            'forecasted_sales' => $data->forecasted_sales ?? 0,
            'total_cost' => $data->total_cost ?? 0,
        ];

        try {
            $groupModel = model('DistributionGroupModel');
            $groupModel->insert($insertData);
            $groupId = $groupModel->getInsertID();

            log_message('info', 'DISTRIBUTION GROUP ADD: Created group ID {id} for {date}', [
                'id' => $groupId,
                'date' => $data->distribution_date,
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Distribution group created successfully',
                'group_id' => $groupId,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'DISTRIBUTION GROUP ADD: {msg}', ['msg' => $e->getMessage()]);
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to create distribution group']);
        }
    }

    public function updateGroup(int $id)
    {
        $data = $this->request->getJSON();
        if (!$data) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid JSON data']);
        }

        $groupModel = model('DistributionGroupModel');
        $group = $groupModel->find($id);

        if (!$group) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Distribution group not found']);
        }

        $updateData = [];
        if (isset($data->title))
            $updateData['title'] = trim($data->title);
        if (isset($data->distribution_date))
            $updateData['distribution_date'] = $data->distribution_date;
        if (isset($data->distributed_to_note))
            $updateData['distributed_to_note'] = $data->distributed_to_note;
        if (isset($data->forecasted_sales))
            $updateData['forecasted_sales'] = $data->forecasted_sales;
        if (isset($data->total_cost))
            $updateData['total_cost'] = $data->total_cost;

        if (empty($updateData)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'No updatable fields provided']);
        }

        try {
            $groupModel->update($id, $updateData);

            log_message('info', 'DISTRIBUTION GROUP UPDATE: Updated group ID {id}', ['id' => $id]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Distribution group updated successfully',
            ]);
        } catch (\Exception $e) {
            log_message('error', 'DISTRIBUTION GROUP UPDATE: {msg}', ['msg' => $e->getMessage()]);
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update distribution group']);
        }
    }

    public function deleteGroup(int $id)
    {
        $groupModel = model('DistributionGroupModel');
        $itemModel = model('DistributionItemModel');

        $group = $groupModel->find($id);
        if (!$group) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Distribution group not found']);
        }

        $items = $itemModel->getItemsByGroup($id);

        try {
            // Restore raw materials for every item in the group
            foreach ($items as $item) {
                $productId = intval($item['product_id']);
                $quantity = intval($item['product_qnty']);
                $qtyMode = $item['qty_mode'] ?? 'batch';
                $actualPieces = $this->distributionQtyToPieces($productId, $quantity, $qtyMode);

                if ($actualPieces > 0) {
                    $this->rawMaterialStockModel->restoreForProduction($productId, $actualPieces);
                    log_message('info', 'DISTRIBUTION GROUP DELETE: Restored {p} pieces for product {pid}', [
                        'p' => $actualPieces,
                        'pid' => $productId,
                    ]);
                }
            }

            // Delete all items then the group itself
            $itemModel->deleteByGroup($id);
            $groupModel->delete($id);

            log_message('info', 'DISTRIBUTION GROUP DELETE: Deleted group ID {id} with {c} items', [
                'id' => $id,
                'c' => count($items),
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Distribution group and its items deleted successfully',
            ]);
        } catch (\Exception $e) {
            log_message('error', 'DISTRIBUTION GROUP DELETE: {msg}', ['msg' => $e->getMessage()]);
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete distribution group']);
        }
    }

    public function addItem()
    {
        $data = $this->request->getJSON();
        if (!$data) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid JSON data']);
        }

        // Required fields
        if (!isset($data->distribution_id, $data->product_id, $data->product_qnty)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'distribution_id, product_id, and product_qnty are required']);
        }

        $groupId = intval($data->distribution_id);
        $productId = intval($data->product_id);
        $quantity = intval($data->product_qnty);
        $qtyMode = DistributionQuantityCalculator::normalizeQtyMode($data->qty_mode ?? 'batch');

        $groupModel = model('DistributionGroupModel');
        $itemModel = model('DistributionItemModel');

        // Group must exist
        $group = $groupModel->find($groupId);
        if (!$group) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Distribution group not found']);
        }

        // Duplicate check within the group
        if ($itemModel->existsInGroup($groupId, $productId)) {
            return $this->response->setStatusCode(409)->setJSON([
                'error' => 'This product is already in the selected distribution group.',
                'duplicate' => true,
            ]);
        }

        // Category enforcement: grocery/drinks/dough → pieces only
        $product = $this->productModel->find($productId);
        if ($product && in_array(strtolower($product['category'] ?? ''), ['grocery', 'drinks', 'dough'], true) && $qtyMode !== 'pieces') {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => ucfirst($product['category']) . ' products can only be distributed by pieces, not batches.',
            ]);
        }

        $actualPieces = $this->distributionQtyToPieces($productId, $quantity, $qtyMode);

        // Pre-check raw material stock
        if ($actualPieces > 0) {
            $preview = $this->rawMaterialStockModel->deductForProduction($productId, $actualPieces, true);

            if (!empty($preview['has_insufficient'])) {
                $shortages = $this->buildShortageMessages($preview['deductions']);
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'error' => 'Cannot add — insufficient raw material stock.',
                    'insufficient_materials' => $shortages,
                    'preview' => $preview,
                ]);
            }
        }

        try {
            // Compute inventory_amount_used (raw-material units consumed)
            $inventoryAmountUsed = 0;
            if ($actualPieces > 0) {
                $deductResult = $this->rawMaterialStockModel->deductForProduction($productId, $actualPieces, false);
                $inventoryAmountUsed = $this->sumDeductedAmount($deductResult);
            }

            $insertData = [
                'distribution_id' => $groupId,
                'product_id' => $productId,
                'product_qnty' => $quantity,
                'qty_mode' => $qtyMode,
                'inventory_amount_used' => $inventoryAmountUsed,
            ];

            $itemModel->insert($insertData);
            $itemId = $itemModel->getInsertID();

            // Recompute group-level totals
            $groupModel->recalculateTotals($groupId);

            // Low-stock notifications
            \App\Libraries\LowStockNotifier::checkAndNotify();

            $productName = $product['product_name'] ?? 'Unknown Product';
            $this->notify('notifyDistributionCreated', $productName, $quantity, $group['distribution_date']);

            log_message('info', 'DISTRIBUTION ITEM ADD: Item ID {iid} added to group {gid}', [
                'iid' => $itemId,
                'gid' => $groupId,
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Distribution item added successfully',
                'item_id' => $itemId,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'DISTRIBUTION ITEM ADD: {msg}', ['msg' => $e->getMessage()]);
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to add distribution item']);
        }
    }

    public function updateItem(int $id)
    {
        $data = $this->request->getJSON();
        if (!$data) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid JSON data']);
        }

        if (!isset($data->product_id, $data->product_qnty)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'product_id and product_qnty are required']);
        }

        $itemModel = model('DistributionItemModel');
        $existing = $itemModel->find($id);

        if (!$existing) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Distribution item not found']);
        }

        $newProductId = intval($data->product_id);
        $newQtyMode = DistributionQuantityCalculator::normalizeQtyMode($data->qty_mode ?? 'batch');
        $newQty = intval($data->product_qnty);
        $groupId = intval($existing['distribution_id']);

        // Category enforcement
        $product = $this->productModel->find($newProductId);
        if ($product && in_array(strtolower($product['category'] ?? ''), ['grocery', 'drinks', 'dough'], true) && $newQtyMode !== 'pieces') {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => ucfirst($product['category']) . ' products can only be distributed by pieces, not batches.',
            ]);
        }

        $oldPieces = $this->distributionQtyToPieces(intval($existing['product_id']), intval($existing['product_qnty']), $existing['qty_mode'] ?? 'batch');
        $newPieces = $this->distributionQtyToPieces($newProductId, $newQty, $newQtyMode);

        // Restore old raw materials first
        if ($oldPieces > 0) {
            $this->rawMaterialStockModel->restoreForProduction(intval($existing['product_id']), $oldPieces);
        }

        // Pre-check new quantity
        if ($newPieces > 0) {
            $preview = $this->rawMaterialStockModel->deductForProduction($newProductId, $newPieces, true);
            if (!empty($preview['has_insufficient'])) {
                // Rollback: re-deduct the old amount
                if ($oldPieces > 0) {
                    $this->rawMaterialStockModel->deductForProduction(intval($existing['product_id']), $oldPieces, false);
                }
                $shortages = $this->buildShortageMessages($preview['deductions']);
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'error' => 'Cannot update — insufficient raw material stock.',
                    'insufficient_materials' => $shortages,
                ]);
            }
        }

        try {
            $inventoryAmountUsed = 0;
            if ($newPieces > 0) {
                $deductResult = $this->rawMaterialStockModel->deductForProduction($newProductId, $newPieces, false);
                $inventoryAmountUsed = $this->sumDeductedAmount($deductResult);
            }

            $itemModel->update($id, [
                'product_id' => $newProductId,
                'product_qnty' => $newQty,
                'qty_mode' => $newQtyMode,
                'inventory_amount_used' => $inventoryAmountUsed,
            ]);

            // Recompute group-level totals
            model('DistributionGroupModel')->recalculateTotals($groupId);

            \App\Libraries\LowStockNotifier::checkAndNotify();

            $group = model('DistributionGroupModel')->find($groupId);
            $productName = $product['product_name'] ?? 'Unknown Product';
            $this->notify('notifyDistributionUpdated', $productName, intval($existing['product_qnty']), $newQty, $group['distribution_date'] ?? '');

            log_message('info', 'DISTRIBUTION ITEM UPDATE: Item ID {id} updated', ['id' => $id]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Distribution item updated successfully',
            ]);
        } catch (\Exception $e) {
            log_message('error', 'DISTRIBUTION ITEM UPDATE: {msg}', ['msg' => $e->getMessage()]);
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update distribution item']);
        }
    }

    public function deleteItem(int $id)
    {
        $itemModel = model('DistributionItemModel');
        $item = $itemModel->find($id);

        if (!$item) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Distribution item not found']);
        }

        $groupId = intval($item['distribution_id']);
        $productId = intval($item['product_id']);
        $quantity = intval($item['product_qnty']);
        $qtyMode = DistributionQuantityCalculator::normalizeQtyMode($item['qty_mode'] ?? 'batch');
        $actualPieces = $this->distributionQtyToPieces($productId, $quantity, $qtyMode);

        try {
            if ($actualPieces > 0) {
                $this->rawMaterialStockModel->restoreForProduction($productId, $actualPieces);
            }

            $itemModel->delete($id);

            // Recompute group-level totals
            model('DistributionGroupModel')->recalculateTotals($groupId);

            $product = $this->productModel->find($productId);
            $productName = $product['product_name'] ?? 'Unknown Product';
            $group = model('DistributionGroupModel')->find($groupId);
            $this->notify('notifyDistributionDeleted', $productName, $quantity, $group['distribution_date'] ?? '');

            log_message('info', 'DISTRIBUTION ITEM DELETE: Item ID {id} deleted from group {gid}', [
                'id' => $id,
                'gid' => $groupId,
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Distribution item deleted successfully',
            ]);
        } catch (\Exception $e) {
            log_message('error', 'DISTRIBUTION ITEM DELETE: {msg}', ['msg' => $e->getMessage()]);
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete distribution item']);
        }
    }

    public function checkInventoryByDate()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $inventory = $this->dailyStockModel->checkInventoryExists($date);

        return $this->response->setJSON([
            'success' => true,
            'inventory_exists' => (bool) $inventory,
            'date' => $date,
        ]);
    }

    public function checkDistributionToday()
    {
        $today = date('Y-m-d');
        $groups = model('DistributionGroupModel')->getGroupsByDate($today);

        return $this->response->setJSON([
            'success' => true,
            'message' => $groups ? 'Distribution groups retrieved' : 'No distribution groups for today',
            'data' => $groups,
        ]);
    }

    private function distributionQtyToPieces(int $productId, int $qty, string $qtyMode = 'batch'): int
    {
        $product = $this->productModel->find($productId);
        $costData = model('ProductCostModel')->getCostByProductId($productId);
        $metrics = DistributionQuantityCalculator::calculateDistributionMetrics($qty, $qtyMode, $product, $costData);

        return (int) $metrics['pieces'];
    }

    /**
     * Build human-readable shortage messages from a deduction preview.
     *
     * @param  array $deductions
     * @return string[]
     */
    private function buildShortageMessages(array $deductions): array
    {
        $shortByMaterial = [];
        foreach ($deductions as $d) {
            if (empty($d['insufficient']))
                continue;
            $mid = $d['material_id'];
            if (!isset($shortByMaterial[$mid])) {
                $shortByMaterial[$mid] = $d['material_name']
                    . ' (need ' . ($d['total_needed'] ?? $d['deduct_amount']) . ' ' . $d['unit']
                    . ', have ' . $d['before'] . ')';
            }
        }
        return array_values($shortByMaterial);
    }

    /**
     * Sum the total raw-material units deducted across all deduction entries.
     * Falls back to 0 if the result shape is unexpected.
     *
     * @param  array $deductResult – result from rawMaterialStockModel::deductForProduction
     * @return float
     */
    private function sumDeductedAmount(array $deductResult): float
    {
        $total = 0.0;
        foreach ($deductResult['deductions'] ?? [] as $d) {
            $total += (float) ($d['deduct_amount'] ?? 0);
        }
        return $total;
    }

    public function addDistributionCategory()
    {
        $data = $this->request->getJSON();
        $name = trim((string) ($data->name ?? $data->category_name ?? ''));

        if ($name === '') {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Category name is required'
            ]);
        }

        $insert = $this->distributionCategoryModel->insert([
            'name' => $name
        ]);

        if ($insert) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Distribution category added successfully'
            ]);
        }

        return $this->response->setStatusCode(500)->setJSON([
            'error' => 'Failed to add distribution category'
        ]);
    }

    public function fetchAllDistributionCategories()
    {
        $categories = $this->distributionCategoryModel
            ->orderBy('name', 'ASC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function updateDistributionCategory()
    {
        $data = $this->request->getJSON(true);

        $categoryId = (int) ($data['category_id'] ?? 0);
        $name = trim((string) ($data['name'] ?? $data['category_name'] ?? ''));

        if ($categoryId <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Category ID is required'
            ]);
        }

        if ($name === '') {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Category name is required'
            ]);
        }

        if (!$this->distributionCategoryModel->find($categoryId)) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Distribution category not found'
            ]);
        }

        $updated = $this->distributionCategoryModel->update($categoryId, [
            'name' => $name,
        ]);

        if ($updated) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Distribution category updated successfully'
            ]);
        }

        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'message' => 'Failed to update distribution category'
        ]);
    }

    public function deleteDistributionCategory()
    {
        $data = $this->request->getJSON(true);

        $categoryId = (int) ($data['category_id'] ?? 0);

        if ($categoryId <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Category ID is required'
            ]);
        }

        if (!$this->distributionCategoryModel->find($categoryId)) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Distribution category not found'
            ]);
        }

        $deleted = $this->distributionCategoryModel->delete($categoryId);

        if ($deleted) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Distribution category deleted successfully'
            ]);
        }

        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'message' => 'Failed to delete distribution category'
        ]);
    }
}