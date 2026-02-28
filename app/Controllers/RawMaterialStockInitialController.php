<?php

namespace App\Controllers;

class RawMaterialStockInitialController extends BaseController
{
    /**
     * Render the Stock Initial page
     */
    public function index(): string
    {
        $data = $this->getSessionData();

        return  view('Template/Header', $data) .
                view('Template/SideNav', $data) .
                view('Template/Notification', $data) .
                view('StockInitial/StockInitial', $data) .
                view('Template/Footer', $data);
    }

    /**
     * Get all stock initial entries (AJAX)
     */
    public function getAll()
    {
        $entries = $this->rawMaterialStockModel->getAllWithDetails();

        return $this->response->setJSON([
            'success' => true,
            'data' => $entries
        ]);
    }

    /**
     * Get single entry by ID (AJAX)
     */
    public function getEntry($id = null)
    {
        if (empty($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Entry ID is required.'
            ]);
        }

        $entry = $this->rawMaterialStockModel->getEntryById($id);

        if (!$entry) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Entry not found.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $entry
        ]);
    }

    /**
     * Get all raw materials for dropdown (AJAX)
     */
    public function getMaterials()
    {
        $materials = $this->rawMaterialsModel->getAllWithDetails();

        return $this->response->setJSON([
            'success' => true,
            'data' => $materials
        ]);
    }

    /**
     * Add new stock initial entry (AJAX)
     */
    public function add()
    {
        $data = $this->request->getJSON(true);

        // Validate required fields
        if (!isset($data['material_id']) || (string)$data['material_id'] === '' ||
            !array_key_exists('initial_qty', $data) || (string)$data['initial_qty'] === '' ||
            !isset($data['unit']) || trim((string)$data['unit']) === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All fields are required.'
            ]);
        }

        if (!is_numeric($data['initial_qty']) || floatval($data['initial_qty']) < 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Initial quantity must be a number greater than or equal to 0.'
            ]);
        }

        try {
            $entryId = $this->rawMaterialStockModel->addEntry($data);

            if ($entryId) {
                // Check for low stock and notify owners
                \App\Libraries\LowStockNotifier::checkAndNotify();

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Stock entry added successfully.',
                    'stock_id' => $entryId
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add entry.'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update stock initial entry (AJAX)
     */
    public function update()
    {
        $data = $this->request->getJSON(true);

        // Validate required fields
        if (!isset($data['stock_id']) || (string)$data['stock_id'] === '' ||
            !isset($data['material_id']) || (string)$data['material_id'] === '' ||
            !array_key_exists('initial_qty', $data) || (string)$data['initial_qty'] === '' ||
            !isset($data['unit']) || trim((string)$data['unit']) === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All fields are required.'
            ]);
        }

        if (!is_numeric($data['initial_qty']) || floatval($data['initial_qty']) < 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Stock quantity must be >= 0.'
            ]);
        }

        // qty_used is sent directly from the form
        if (array_key_exists('qty_used', $data) && is_numeric($data['qty_used'])) {
            $data['qty_used'] = max(0, floatval($data['qty_used']));
            // Clamp: used cannot exceed initial
            if ($data['qty_used'] > floatval($data['initial_qty'])) {
                $data['qty_used'] = floatval($data['initial_qty']);
            }
        } else {
            // Preserve existing qty_used — don't reset to 0 on edit
            $existing = $this->rawMaterialStockModel->find(intval($data['stock_id']));
            $data['qty_used'] = $existing['qty_used'] ?? 0;
        }

        try {
            $success = $this->rawMaterialStockModel->updateEntry(
                intval($data['stock_id']),
                $data
            );

            if ($success) {
                // Check for low stock and notify owners
                \App\Libraries\LowStockNotifier::checkAndNotify();

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Entry updated successfully.'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update entry.'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete stock initial entry (AJAX)
     */
    public function delete($id = null)
    {
        if (empty($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Entry ID is required.'
            ]);
        }

        $entry = $this->rawMaterialStockModel->find($id);

        if (!$entry) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Entry not found.'
            ]);
        }

        try {
            $this->rawMaterialStockModel->deleteEntry($id);

            // Check for low stock and notify owners
            \App\Libraries\LowStockNotifier::checkAndNotify();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Entry deleted successfully.'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}