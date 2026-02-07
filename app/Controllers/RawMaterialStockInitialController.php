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
                view('Template/notification', $data) .
                view('StockInitial/StockInitial', $data) .
                view('Template/Footer', $data);
    }

    /**
     * Get all stock initial entries (AJAX)
     */
    public function getAll()
    {
        $entries = $this->rawMaterialStockInitialModel->getAllWithDetails();

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

        $entry = $this->rawMaterialStockInitialModel->getEntryById($id);

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
            !array_key_exists('initial_quantity', $data) || (string)$data['initial_quantity'] === '' ||
            !isset($data['unit']) || trim((string)$data['unit']) === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All fields are required.'
            ]);
        }

        if (!is_numeric($data['initial_quantity']) || floatval($data['initial_quantity']) < 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Initial quantity must be a number greater than or equal to 0.'
            ]);
        }

        try {
            $entryId = $this->rawMaterialStockInitialModel->addEntry($data);

            if ($entryId) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Stock initial entry added successfully.',
                    'entry_id' => $entryId
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
        if (!isset($data['entry_id']) || (string)$data['entry_id'] === '' ||
            !isset($data['material_id']) || (string)$data['material_id'] === '' ||
            !array_key_exists('initial_quantity', $data) || (string)$data['initial_quantity'] === '' ||
            !array_key_exists('quantity_used', $data) || (string)$data['quantity_used'] === '' ||
            !isset($data['unit']) || trim((string)$data['unit']) === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All fields are required.'
            ]);
        }

        if (!is_numeric($data['initial_quantity']) || floatval($data['initial_quantity']) < 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Initial quantity must be >= 0.'
            ]);
        }

        if (!is_numeric($data['quantity_used']) || floatval($data['quantity_used']) < 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Quantity used must be >= 0.'
            ]);
        }

        if (floatval($data['quantity_used']) > floatval($data['initial_quantity'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Quantity used cannot exceed initial quantity.'
            ]);
        }

        try {
            $success = $this->rawMaterialStockInitialModel->updateEntry(
                intval($data['entry_id']),
                $data
            );

            if ($success) {
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

        $entry = $this->rawMaterialStockInitialModel->find($id);

        if (!$entry) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Entry not found.'
            ]);
        }

        try {
            $this->rawMaterialStockInitialModel->deleteEntry($id);

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
