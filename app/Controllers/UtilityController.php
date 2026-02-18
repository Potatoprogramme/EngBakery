<?php

namespace App\Controllers;

class UtilityController extends BaseController
{
    public function index()
    {
        $data = $this->getSessionData();

        if ($redirect = $this->redirectIfNotLoggedIn()) {
            return $redirect;
        }

        return view('Template/Header', $data)
            . view('Template/SideNav', $data)
            . view('Template/Notification', $data)
            . view('Utility/Utility', $data)
            . view('Template/Footer', $data);
    }

    /**
     * Get all utility expenses (AJAX)
     */
    public function getAllUtilityExpenses()
    {
        $expenses = $this->utilityExpensesModel->orderBy('billed_at', 'DESC')->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $expenses,
        ]);
    }

    /**
     * Add a new utility expense (AJAX)
     */
    public function addUtilityExpense()
    {
        $data = $this->request->getJSON(true);

        // Validate required fields
        if (empty($data['expense_category'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Expense category is required.',
            ]);
        }

        if (!isset($data['expense_amount']) || $data['expense_amount'] === '' || !is_numeric($data['expense_amount']) || floatval($data['expense_amount']) < 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'A valid expense amount is required.',
            ]);
        }

        if (empty($data['expense_date'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Expense date is required.',
            ]);
        }

        try {
            $insertData = [
                'type'           => $data['expense_category'],
                'expense'        => floatval($data['expense_amount']),
                'billing_period' => $data['billing_period'] ?? 'monthly',
                'quantity'       => $data['quantity'] ?? 0,
                'unit'           => $data['unit'] ?? '',
                'days'           => $data['days'] ?? 0,
                'cost_per_unit'  => $data['cost_per_unit'] ?? 0,
                'cost_per_day'   => $data['cost_per_day'] ?? 0,
                'billed_at'      => $data['expense_date'],
                'created_at'     => date('Y-m-d H:i:s'),
            ];

            $this->utilityExpensesModel->insert($insertData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Expense added successfully.',
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Update an existing utility expense (AJAX)
     */
    public function updateUtilityExpense()
    {
        $data = $this->request->getJSON(true);

        // Validate expense ID
        if (empty($data['expense_id'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Expense ID is required.',
            ]);
        }

        // Check if expense exists
        $existing = $this->utilityExpensesModel->find($data['expense_id']);
        if (!$existing) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Expense not found.',
            ]);
        }

        // Validate required fields
        if (empty($data['expense_category'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Expense category is required.',
            ]);
        }

        if (!isset($data['expense_amount']) || $data['expense_amount'] === '' || !is_numeric($data['expense_amount']) || floatval($data['expense_amount']) < 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'A valid expense amount is required.',
            ]);
        }

        if (empty($data['expense_date'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Expense date is required.',
            ]);
        }

        try {
            $updateData = [
                'type'           => $data['expense_category'],
                'expense'        => floatval($data['expense_amount']),
                'billing_period' => $data['billing_period'] ?? 'monthly',
                'quantity'       => $data['quantity'] ?? $existing['quantity'],
                'unit'           => $data['unit'] ?? $existing['unit'],
                'days'           => $data['days'] ?? $existing['days'],
                'cost_per_unit'  => $data['cost_per_unit'] ?? $existing['cost_per_unit'],
                'cost_per_day'   => $data['cost_per_day'] ?? $existing['cost_per_day'],
                'billed_at'      => $data['expense_date'],
            ];

            $this->utilityExpensesModel->update($data['expense_id'], $updateData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Expense updated successfully.',
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete a utility expense (AJAX)
     */
    public function deleteUtilityExpense($id = null)
    {
        if (empty($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Expense ID is required.',
            ]);
        }

        // Check if expense exists
        $existing = $this->utilityExpensesModel->find($id);
        if (!$existing) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Expense not found.',
            ]);
        }

        try {
            $this->utilityExpensesModel->delete($id);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Expense deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }
}