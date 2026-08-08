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

        $groups = $this->distributionGroupModel->getGroupsByDate($date);
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

        $groups = $this->distributionGroupModel->getGroupsByDateRange($startDate, $endDate);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Distribution groups retrieved',
            'data' => $groups,
        ]);
    }

    public function getGroup(int $id)
    {
        $group = $this->distributionGroupModel->getGroupWithItems($id);

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
        $data = $this->request->getJSON(true);
        if (!$data) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid JSON data']);
        }

        $categoryId = (int) ($data['dist_category_id'] ?? $data['category_id'] ?? 0);
        $distributionDate = trim((string) ($data['distribution_date'] ?? ''));
        $note = isset($data['distributed_to_note']) ? trim((string) $data['distributed_to_note']) : null;

        if ($categoryId <= 0 || $distributionDate === '') {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'dist_category_id and distribution_date are required',
            ]);
        }

        $category = model('DistributionCategory')->withDeleted()->find($categoryId);
        if (!$category) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Distribution category not found',
            ]);
        }

        if ($checkData = $this->distributionGroupModel->checkIfGroupExists($categoryId, $distributionDate)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Distribution group created successfully',
                'group_id' => $checkData['group_id'],
                'category_name' => trim((string) ($category['name'] ?? '')),
            ]);
        }

        $insertData = [
            'dist_category_id' => $categoryId,
            'distribution_date' => $distributionDate,
            'distributed_to_note' => $note !== '' ? $note : null,
            'forecasted_sales' => (float) ($data['forecasted_sales'] ?? 0),
            'total_cost' => (float) ($data['total_cost'] ?? 0),
        ];

        try {
            $this->distributionGroupModel->insert($insertData);
            $groupId = $this->distributionGroupModel->getInsertID();

            log_message('info', 'DISTRIBUTION GROUP ADD: Created group ID {id} for {date}', [
                'id' => $groupId,
                'date' => $distributionDate,
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Distribution group created successfully',
                'group_id' => $groupId,
                'category_name' => trim((string) ($category['name'] ?? '')),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'DISTRIBUTION GROUP ADD: {msg}', ['msg' => $e->getMessage()]);
            return $this->response->setStatusCode(500)->setJSON([
                'error' =>
                $e->getMessage() ?: 'Failed to create distribution group',
                'insert_data' => $insertData,
                'data' => $data,
            ]);
        }
    }

    public function updateGroup(int $id)
    {
        $data = $this->request->getJSON(true);
        if (!$data) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid JSON data']);
        }

        $group = $this->distributionGroupModel->find($id);

        if (!$group) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Distribution group not found']);
        }

        $updateData = [];
        if (isset($data['dist_category_id'])) {
            $categoryId = (int) $data['dist_category_id'];
            if ($categoryId <= 0) {
                return $this->response->setStatusCode(400)->setJSON([
                    'error' => 'dist_category_id is required',
                ]);
            }

            if (!model('DistributionCategory')->find($categoryId)) {
                return $this->response->setStatusCode(404)->setJSON([
                    'error' => 'Distribution category not found',
                ]);
            }

            $updateData['dist_category_id'] = $categoryId;
        }
        if (isset($data['distribution_date']))
            $updateData['distribution_date'] = $data['distribution_date'];
        if (array_key_exists('distributed_to_note', $data))
            $updateData['distributed_to_note'] = $data['distributed_to_note'];
        if (isset($data['forecasted_sales']))
            $updateData['forecasted_sales'] = $data['forecasted_sales'];
        if (isset($data['total_cost']))
            $updateData['total_cost'] = $data['total_cost'];

        if (empty($updateData)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'No updatable fields provided']);
        }

        try {
            $this->distributionGroupModel->update($id, $updateData);

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

        log_message('info', 'DISTRIBUTION GROUP DELETE START: group_id={id}, item_count={c}', [
            'id' => $id,
            'c' => count($items),
        ]);

        try {
            // Restore raw materials for every item in the group, and sync
            // each item's originating daily_stock_items row so
            // distributed_out_qty/ending_stock reflect the removal.
            foreach ($items as $item) {
                $productId = intval($item['product_id']);
                $quantity = intval($item['product_qnty']);
                $qtyMode = $item['qty_mode'] ?? 'batch';
                $actualPieces = $this->distributionQtyToPieces($productId, $quantity, $qtyMode);
                $dailyStockId = intval($item['daily_stock_id'] ?? 0);

                log_message('debug', 'DISTRIBUTION GROUP DELETE ITEM: item_id={iid}, daily_stock_id={dsid}, product_id={pid}, quantity={qty}, qty_mode={mode}, actualPieces={pieces}', [
                    'iid' => $item['id'] ?? ($item['item_id'] ?? null),
                    'dsid' => $dailyStockId,
                    'pid' => $productId,
                    'qty' => $quantity,
                    'mode' => $qtyMode,
                    'pieces' => $actualPieces,
                ]);

                if ($actualPieces > 0) {
                    $this->rawMaterialStockModel->restoreForProduction($productId, $actualPieces);
                    log_message('info', 'DISTRIBUTION GROUP DELETE: Restored {p} pieces for product {pid}', [
                        'p' => $actualPieces,
                        'pid' => $productId,
                    ]);

                    if ($dailyStockId > 0) {
                        log_message('debug', 'DISTRIBUTION GROUP DELETE SYNC: item_id={iid} — restoring {pieces} pieces to daily_stock_id={dsid}, product_id={pid}', [
                            'iid' => $item['id'] ?? ($item['item_id'] ?? null),
                            'pieces' => $actualPieces,
                            'dsid' => $dailyStockId,
                            'pid' => $productId,
                        ]);
                        $this->adjustDailyStockDistributedQty($dailyStockId, $productId, -$actualPieces);
                    } else {
                        log_message('warning', 'DISTRIBUTION GROUP DELETE SYNC SKIPPED: item_id={iid} has no daily_stock_id on record — inventory ending_stock/distributed_out_qty will NOT be restored for this item.', [
                            'iid' => $item['id'] ?? ($item['item_id'] ?? null),
                        ]);
                    }
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
        $quantity = (float) $data->product_qnty;
        $qtyMode = DistributionQuantityCalculator::normalizeQtyMode($data->qty_mode ?? 'batch');

        if ($quantity <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'product_qnty must be greater than zero',
            ]);
        }

        // Group must exist
        $group = $this->distributionGroupModel->find($groupId);
        if (!$group) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Distribution group not found']);
        }


        // Duplicate check within the group
        // if ($this->distributionItemModel->existsInGroup($groupId, $productId)) {
        //     return $this->response->setStatusCode(409)->setJSON([
        //         'error' => 'This product is already in the selected distribution group.',
        //         'duplicate' => true,
        //     ]);
        // }

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

            $activeDailyStock = $this->dailyStockModel
                ->where('inventory_date', $group['distribution_date'])
                ->orderBy('daily_stock_id', 'DESC')
                ->first();
            $activeDailyStockId = intval($activeDailyStock['daily_stock_id'] ?? 0);

            log_message('debug', 'DISTRIBUTION ITEM ADD: Resolved daily_stock_id={dsid} for distribution_date={date}', [
                'dsid' => $activeDailyStockId,
                'date' => $group['distribution_date'],
            ]);

            $insertData = [
                'distribution_id' => $groupId,
                'daily_stock_id' => $activeDailyStockId ?: null, // NEW
                'product_id' => $productId,
                'product_qnty' => $quantity,
                'qty_mode' => $qtyMode,
                'inventory_amount_used' => $inventoryAmountUsed,
            ];



            $this->distributionItemModel->insert($insertData);
            $itemId = $this->distributionItemModel->getInsertID();

            // Sync the source inventory shift's distributed_out_qty/ending_stock
            // to reflect this newly distributed quantity.
            if ($activeDailyStockId > 0 && $actualPieces > 0) {
                log_message('debug', 'DISTRIBUTION ITEM ADD SYNC: item_id={iid} — applying {pieces} pieces to daily_stock_id={dsid}, product_id={pid}', [
                    'iid' => $itemId,
                    'pieces' => $actualPieces,
                    'dsid' => $activeDailyStockId,
                    'pid' => $productId,
                ]);
                $this->adjustDailyStockDistributedQty($activeDailyStockId, $productId, $actualPieces);
            } elseif ($actualPieces > 0) {
                log_message('warning', 'DISTRIBUTION ITEM ADD SYNC SKIPPED: item_id={iid} — no matching daily_stock record found for distribution_date={date}; inventory ending_stock/distributed_out_qty will NOT reflect this addition.', [
                    'iid' => $itemId,
                    'date' => $group['distribution_date'],
                ]);
            }

            // Recompute group-level totals
            $this->distributionGroupModel->recalculateTotals($groupId);

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
            return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage() ?: 'Failed to add distribution item']);
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


        $existing = $this->distributionItemModel->find($id);

        if (!$existing) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Distribution item not found']);
        }

        $newProductId = intval($data->product_id);
        $newQtyMode = DistributionQuantityCalculator::normalizeQtyMode($data->qty_mode ?? 'batch');
        $newQty = (float) $data->product_qnty;
        $groupId = intval($existing['distribution_id']);

        if ($newQty <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'product_qnty must be greater than zero',
            ]);
        }

        // Category enforcement
        $product = $this->productModel->find($newProductId);
        if ($product && in_array(strtolower($product['category'] ?? ''), ['grocery', 'drinks', 'dough'], true) && $newQtyMode !== 'pieces') {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => ucfirst($product['category']) . ' products can only be distributed by pieces, not batches.',
            ]);
        }

        $oldProductId = intval($existing['product_id']);
        $dailyStockId = intval($existing['daily_stock_id'] ?? 0);

        $oldPieces = $this->distributionQtyToPieces($oldProductId, intval($existing['product_qnty']), $existing['qty_mode'] ?? 'batch');
        $newPieces = $this->distributionQtyToPieces($newProductId, $newQty, $newQtyMode);

        log_message('info', 'DISTRIBUTION ITEM UPDATE: item_id={id}, daily_stock_id={dsid}, product {oldPid}->{newPid}, pieces {oldPieces}->{newPieces}', [
            'id' => $id,
            'dsid' => $dailyStockId,
            'oldPid' => $oldProductId,
            'newPid' => $newProductId,
            'oldPieces' => $oldPieces,
            'newPieces' => $newPieces,
        ]);

        // Restore old raw materials first
        if ($oldPieces > 0) {
            $this->rawMaterialStockModel->restoreForProduction($oldProductId, $oldPieces);
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

            $this->distributionItemModel->update($id, [
                'product_id' => $newProductId,
                'product_qnty' => $newQty,
                'qty_mode' => $newQtyMode,
                'inventory_amount_used' => $inventoryAmountUsed,
            ]);

            // Sync the source inventory shift's distributed_out_qty/ending_stock
            // to reflect the new distributed quantity (and product, if changed).
            if ($dailyStockId > 0) {
                if ($oldProductId === $newProductId) {
                    log_message('debug', 'DISTRIBUTION ITEM UPDATE SYNC: same product ({pid}), applying pieces delta {delta}', [
                        'pid' => $newProductId,
                        'delta' => $newPieces - $oldPieces,
                    ]);
                    $this->adjustDailyStockDistributedQty($dailyStockId, $newProductId, $newPieces - $oldPieces);
                } else {
                    log_message('debug', 'DISTRIBUTION ITEM UPDATE SYNC: product changed {oldPid}->{newPid}, restoring {oldPieces} from old and applying {newPieces} to new', [
                        'oldPid' => $oldProductId,
                        'newPid' => $newProductId,
                        'oldPieces' => $oldPieces,
                        'newPieces' => $newPieces,
                    ]);
                    // Product swapped: fully restore old product's distributed
                    // portion, then apply the new product's distributed portion.
                    $this->adjustDailyStockDistributedQty($dailyStockId, $oldProductId, -$oldPieces);
                    $this->adjustDailyStockDistributedQty($dailyStockId, $newProductId, $newPieces);
                }
            } else {
                log_message('warning', 'DISTRIBUTION ITEM UPDATE SYNC SKIPPED: item_id={id} has no daily_stock_id on record — inventory ending_stock/distributed_out_qty will NOT reflect this update.', [
                    'id' => $id,
                ]);
            }

            // Recompute group-level totals
            $this->distributionGroupModel->recalculateTotals($groupId);

            \App\Libraries\LowStockNotifier::checkAndNotify();

            $group = $this->distributionGroupModel->find($groupId);
            $productName = $product['product_name'] ?? 'Unknown Product';
            $this->notify('notifyDistributionUpdated', $productName, (float) $existing['product_qnty'], $newQty, $group['distribution_date'] ?? '');

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

        $item = $this->distributionItemModel->find($id);

        if (!$item) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Distribution item not found']);
        }

        $groupId = intval($item['distribution_id']);
        $productId = intval($item['product_id']);
        $quantity = intval($item['product_qnty']);
        $qtyMode = DistributionQuantityCalculator::normalizeQtyMode($item['qty_mode'] ?? 'batch');
        $actualPieces = $this->distributionQtyToPieces($productId, $quantity, $qtyMode);
        $dailyStockId = intval($item['daily_stock_id'] ?? 0);

        log_message('info', 'DISTRIBUTION ITEM DELETE START: item_id={id}, daily_stock_id={dsid}, product_id={pid}, quantity={qty}, qty_mode={mode}, actualPieces={pieces}', [
            'id' => $id,
            'dsid' => $dailyStockId,
            'pid' => $productId,
            'qty' => $quantity,
            'mode' => $qtyMode,
            'pieces' => $actualPieces,
        ]);

        try {
            if ($actualPieces > 0) {
                $this->rawMaterialStockModel->restoreForProduction($productId, $actualPieces);
            }

            $this->distributionItemModel->delete($id);

            // Fully restore this item's distributed portion back onto the
            // source shift's inventory row (distributed_out_qty down, ending up).
            if ($dailyStockId > 0 && $actualPieces > 0) {
                log_message('debug', 'DISTRIBUTION ITEM DELETE SYNC: item_id={id} — restoring {pieces} pieces to daily_stock_id={dsid}, product_id={pid}', [
                    'id' => $id,
                    'pieces' => $actualPieces,
                    'dsid' => $dailyStockId,
                    'pid' => $productId,
                ]);
                $this->adjustDailyStockDistributedQty($dailyStockId, $productId, -$actualPieces);
            } elseif ($actualPieces > 0) {
                log_message('warning', 'DISTRIBUTION ITEM DELETE SYNC SKIPPED: item_id={id} has no daily_stock_id on record — inventory ending_stock/distributed_out_qty will NOT be restored for this delete.', [
                    'id' => $id,
                ]);
            }
            // Recompute group-level totals
            $this->distributionGroupModel->recalculateTotals($groupId);

            $product = $this->productModel->find($productId);
            $productName = $product['product_name'] ?? 'Unknown Product';
            $group = $this->distributionGroupModel->find($groupId);
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
        $groups = $this->distributionGroupModel->getGroupsByDate($today);

        return $this->response->setJSON([
            'success' => true,
            'message' => $groups ? 'Distribution groups retrieved' : 'No distribution groups for today',
            'data' => $groups,
        ]);
    }

    private function distributionQtyToPieces(int $productId, float $qty, string $qtyMode = 'batch'): int
    {
        $product = $this->productModel->find($productId);
        $costData = model('ProductCostModel')->getCostByProductId($productId);
        $metrics = DistributionQuantityCalculator::calculateDistributionMetrics($qty, $qtyMode, $product, $costData);

        return (int) $metrics['pieces'];
    }

    /**
     * Reconcile a daily_stock_items row's distributed_out_qty/ending_stock when
     * a distribution item (created via the "Distribute" action or Distribution
     * tab) changes quantity, product, or is deleted.
     *
     * @param int $dailyStockId  The shift this distribution item was pulled from.
     * @param int $productId     Product whose distributed_out_qty to adjust.
     * @param int $piecesDelta   Positive = more pieces now distributed out
     *                            (ending goes down); negative = less distributed
     *                            / fully restored (ending goes back up).
     */
    private function adjustDailyStockDistributedQty(int $dailyStockId, int $productId, int $piecesDelta): void
    {
        log_message('info', 'DISTRIBUTION SYNC CALLED: daily_stock_id={dsid}, product_id={pid}, piecesDelta={delta}', [
            'dsid' => $dailyStockId,
            'pid' => $productId,
            'delta' => $piecesDelta,
        ]);

        if ($dailyStockId <= 0 || $productId <= 0 || $piecesDelta === 0) {
            log_message('debug', 'DISTRIBUTION SYNC SKIPPED: guard failed — daily_stock_id={dsid}, product_id={pid}, piecesDelta={delta} (one or more invalid/zero)', [
                'dsid' => $dailyStockId,
                'pid' => $productId,
                'delta' => $piecesDelta,
            ]);
            return;
        }

        $dailyStockItemsModel = model('DailyStockItemsModel');
        $item = $dailyStockItemsModel
            ->where('daily_stock_id', $dailyStockId)
            ->where('product_id', $productId)
            ->first();

        if (!$item) {
            log_message('warning', 'DISTRIBUTION SYNC: No daily_stock_items row for daily_stock_id={dsid}, product_id={pid}; skipping distributed_out_qty sync (delta {delta}).', [
                'dsid' => $dailyStockId,
                'pid' => $productId,
                'delta' => $piecesDelta,
            ]);
            return;
        }

        $currentDistributedOut = intval($item['distributed_out_qty'] ?? 0);
        $currentEnding = intval($item['ending_stock'] ?? 0);
        $currentBeginning = intval($item['beginning_stock'] ?? 0);
        $currentAdded = intval($item['added_qty'] ?? 0);

        log_message('debug', 'DISTRIBUTION SYNC BEFORE: item_id={iid}, product_id={pid}, beginning={beg}, added={add}, distributed_out={dist}, ending={end}', [
            'iid' => $item['item_id'],
            'pid' => $productId,
            'beg' => $currentBeginning,
            'add' => $currentAdded,
            'dist' => $currentDistributedOut,
            'end' => $currentEnding,
        ]);

        $rawNewDistributedOut = $currentDistributedOut + $piecesDelta;
        $newDistributedOut = max(0, $rawNewDistributedOut);

        if ($rawNewDistributedOut < 0) {
            log_message('warning', 'DISTRIBUTION SYNC CLAMP: item_id={iid} — computed distributed_out_qty {raw} was negative, clamped to 0. Possible data drift (delta larger than what was on record).', [
                'iid' => $item['item_id'],
                'raw' => $rawNewDistributedOut,
            ]);
        }

        // Ending moves opposite of distributed_out_qty: more distributed -> less ending.
        $rawNewEnding = $currentEnding - $piecesDelta;

        // Clamp ending_stock to a sane range so a stale/incorrect delta can't
        // push it negative or past the total available (beginning + added).
        $maxEnding = max(0, $currentBeginning + $currentAdded);
        $newEnding = max(0, min($rawNewEnding, $maxEnding));

        if ($rawNewEnding < 0) {
            log_message('warning', 'DISTRIBUTION SYNC CLAMP: item_id={iid} — computed ending_stock {raw} was negative, clamped to 0.', [
                'iid' => $item['item_id'],
                'raw' => $rawNewEnding,
            ]);
        }

        if ($rawNewEnding > $maxEnding) {
            log_message('warning', 'DISTRIBUTION SYNC CLAMP: item_id={iid} — computed ending_stock {raw} exceeded max allowed {max} (beginning {beg} + added {add}), clamped down.', [
                'iid' => $item['item_id'],
                'raw' => $rawNewEnding,
                'max' => $maxEnding,
                'beg' => $currentBeginning,
                'add' => $currentAdded,
            ]);
        }

        $updated = $dailyStockItemsModel->update($item['item_id'], [
            'distributed_out_qty' => $newDistributedOut,
            'ending_stock' => $newEnding,
        ]);

        if (!$updated) {
            log_message('error', 'DISTRIBUTION SYNC FAILED: item_id={iid} — update() returned false. Errors: {errors}', [
                'iid' => $item['item_id'],
                'errors' => json_encode($dailyStockItemsModel->errors()),
            ]);
            return;
        }

        log_message('info', 'DISTRIBUTION SYNC AFTER: item_id={iid}, product_id={pid} — distributed_out_qty {distOld}->{distNew}, ending_stock {endOld}->{endNew} (delta {delta})', [
            'iid' => $item['item_id'],
            'pid' => $productId,
            'distOld' => $currentDistributedOut,
            'distNew' => $newDistributedOut,
            'endOld' => $currentEnding,
            'endNew' => $newEnding,
            'delta' => $piecesDelta,
        ]);
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

    public function getDistributionIdsWithItems()
    {
        $ids = $this->getDistributionCategoryIdsWithItems();

        return $this->response->setJSON([
            'success' => true,
            'data' => $ids,
        ]);
    }

    // Used by dropdowns (Add/Edit Items modal) — must return EVERY category
    public function fetchAllDistributionCategories()
    {
        $categories = $this->distributionCategoryModel
            ->orderBy('name', 'ASC')
            ->findAll();

        log_message('info', 'Fetching all distribution categories');

        return $this->response->setJSON([
            'success' => true,
            'data' => $categories,
        ]);
    }

    // New: only categories that DON'T have items yet for the given date
    public function fetchUnusedDistributionCategories()
    {
        $date = $this->request->getGet('date') ?? $this->request->getGet('distribution_date');

        $idsWithItems = $this->getDistributionCategoryIdsWithItems($date);

        $builder = $this->distributionCategoryModel->orderBy('name', 'ASC');

        if (!empty($idsWithItems)) {
            $builder->whereNotIn('dist_cat_id', $idsWithItems);
        }

        $categories = $builder->findAll();

        log_message('info', 'Fetching distribution categories with no items for date: ' . ($date ?? 'ALL DATES'));
        log_message('debug', 'Excluded category IDs (have items): ' . json_encode($idsWithItems));

        return $this->response->setJSON([
            'success' => true,
            'data' => $categories,
        ]);
    }

    private function getDistributionCategoryIdsWithItems(?string $date = null): array
    {
        $builder = $this->distributionGroupModel
            ->select('distribution_group.dist_category_id')
            ->join('distribution_item', 'distribution_group.id = distribution_item.distribution_id', 'inner')
            ->groupBy('distribution_group.dist_category_id');

        if (!empty($date)) {
            $builder->where('distribution_group.distribution_date', $date);
        }

        $groups = $builder->findAll();

        return array_column($groups, 'dist_category_id');
    }

    public function getDistributedQuantitiesForDate()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');

        $pieces = $this->distributionGroupModel->getDistributedPiecesForDate($date);

        return $this->response->setJSON([
            'success' => true,
            'date' => $date,
            'data' => $pieces, // { "12": 240, "17": 60, ... }
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
