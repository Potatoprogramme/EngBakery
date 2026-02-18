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

    public function createUtilityExpense()
    {
        $data = $this->request->getJSON(true);

        if ($redirect = $this->redirectIfNotLoggedIn()) {
            return $redirect;
        }

        $type = $data['type'] ?? null;
        $quantity = $data['quantity'] ?? null;
        $unit = $data['unit'] ?? null;
        $expense = $data['expense'] ?? null;
        $billed_at = $data['billed_at'] ?? null;

        $created_at = date('Y-m-d H:i:s');

        $cost_per_unit = round($expense / $quantity, 5);

        $insertData = [
            'type' => $type,
            'quantity' => $quantity,
            'unit' => $unit,
            'expense' => $expense,
            'cost_per_unit' => $cost_per_unit,
            'created_at' => $created_at,
            'billed_at' => $billed_at,
        ];


        $this->utilityExpensesModel->insert($insertData);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Utility expense recorded successfully.',
            'data' => $insertData,
        ]);
    }

    /**
     * Force resend low stock email (layout check)
     * Visit: /Utility/ResendLowStockEmail
     */
    public function resendLowStockEmail()
    {
        // Force send regardless of time/flag
        \App\Libraries\LowStockNotifier::checkAndNotify(25, 40, true);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Low stock email resend triggered.',
        ]);
    }

    public function deleteUtilityExpense()
    {
        $data = $this->request->getJSON(true);

        if ($redirect = $this->redirectIfNotLoggedIn()) {
            return $redirect;
        }

        $id = $data['id'] ?? null;

        if ($this->utilityExpensesModel->find($id) === null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'id expense not found.',
            ]);
        }

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utility expense ID is required.',
            ]);
        }

        $this->utilityExpensesModel->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Utility expense deleted successfully.',
        ]);
    }
}