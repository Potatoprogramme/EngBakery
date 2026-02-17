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
        $lowItems = $this->rawMaterialStockModel->getLowStockMaterials(20, 40);

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

    /**
     * Test endpoint to send remittance email to test recipient
     * Visit: /Utility/TestRemittanceEmail
     */
    public function testRemittanceEmail()
    {
        // Force send email even if already sent today
        $testEmail = 'jabarte@my.cspc.edu.ph';
        
        // Get today's remittances
        $remittanceModel = new \App\Models\RemittanceDetailsModel();
        $today = date('Y-m-d');
        $remittances = $remittanceModel->getRemittancesByDate($today);
        
        if (empty($remittances)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "No remittances found for {$today}. Please create at least one remittance first.",
            ]);
        }
        
        // Attach denominations to each remittance
        $denominationModel = new \App\Models\RemittanceDenominationsModel();
        foreach ($remittances as &$rem) {
            $rem['denominations'] = $denominationModel
                ->where('remittance_id', $rem['remittance_id'])
                ->findAll();
        }
        
        // Build email body
        $library = new \App\Libraries\DailyRemittanceReport();
        $emailBody = $library->buildEmailBody($remittances, $today);
        
        // Send test email
        $email = \Config\Services::email();
        $email->setFrom('stephenandrewnoblesala30@gmail.com', 'E n\' G Bakery System');
        $email->setTo($testEmail);
        $email->setSubject('📋 [TEST] Daily Remittance Report — ' . date('F j, Y', strtotime($today)));
        $email->setMessage($emailBody);
        
        if ($email->send()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Test remittance email sent successfully!',
                'sent_to' => $testEmail,
                'remittance_count' => count($remittances),
                'date' => $today,
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to send test email.',
                'error' => $email->printDebugger(['headers']),
            ]);
        }
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