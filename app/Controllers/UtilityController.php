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
     * Test endpoint to trigger low stock email manually.
     * Visit: /Utility/TestLowStockEmail
     */
    public function testLowStockEmail()
    {
        // Skip the 1-hour throttle for testing
        session()->remove('low_stock_email_sent_at');

        // Check what materials are low first
        $lowItems = $this->rawMaterialStockModel->getLowStockMaterials(10, 25);

        if (empty($lowItems)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No low stock materials found. Nothing to email about.',
            ]);
        }

        // Send the email
        \App\Libraries\LowStockNotifier::checkAndNotify();

        // Get owner emails for confirmation
        $owners = $this->usersModel->where('employee_type', 'owner')
                                   ->where('approved', 1)
                                   ->findAll();
        $emails = array_column($owners, 'email');

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Low stock email sent!',
            'sent_to' => $emails,
            'low_stock_count' => count($lowItems),
            'materials' => array_map(fn($item) => [
                'name' => $item['material_name'],
                'remaining' => round(floatval($item['current_quantity']), 2),
                'unit' => $item['unit'],
                'status' => $item['stock_status'],
            ], $lowItems),
        ]);
    }
}