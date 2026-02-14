<?php

namespace App\Libraries;

use App\Models\RawMaterialStockModel;
use App\Models\UsersModel;

class LowStockNotifier
{
    /**
     * Check for low stock raw materials and email all owners if any are critical.
     * Only sends one email per session to avoid spamming on every order.
     *
     * @param float $criticalLevel  Stock quantity considered critical (default 10)
     * @param float $warningLevel   Stock quantity considered warning (default 25)
     */
    public static function checkAndNotify(float $criticalLevel = 10, float $warningLevel = 25): void
    {
        $stockModel = new RawMaterialStockModel();
        $lowStockItems = $stockModel->getLowStockMaterials($criticalLevel, $warningLevel);

        if (empty($lowStockItems)) {
            return; // No low stock — nothing to report
        }

        // Get all owner emails
        $usersModel = new UsersModel();
        $owners = $usersModel->where('employee_type', 'owner')
                             ->where('approved', 1)
                             ->findAll();

        if (empty($owners)) {
            log_message('warning', 'Low stock alert: No owner accounts found to notify.');
            return;
        }

        $ownerEmails = array_column($owners, 'email');

        // Build the email
        $criticalItems = array_filter($lowStockItems, fn($item) => $item['stock_status'] === 'critical');
        $warningItems  = array_filter($lowStockItems, fn($item) => $item['stock_status'] === 'warning');

        $emailBody = self::buildEmailBody($criticalItems, $warningItems);

        // Send
        try {
            $emailService = \Config\Services::email();
            $emailService->setFrom('noreply@engbakery.com', "E n' G Bakery");
            $emailService->setTo($ownerEmails);
            $emailService->setSubject('⚠ Low Stock Alert — ' . count($lowStockItems) . ' material(s) running low');
            $emailService->setMessage($emailBody);
            $emailService->setMailType('html');

            if ($emailService->send()) {
                log_message('info', 'Low stock alert email sent to: ' . implode(', ', $ownerEmails));
            } else {
                log_message('error', 'Failed to send low stock email: ' . $emailService->printDebugger(['headers']));
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception sending low stock email: ' . $e->getMessage());
        }
    }

    /**
     * Build the HTML email body
     */
    private static function buildEmailBody(array $criticalItems, array $warningItems): string
    {
        $reportDate = date('F d, Y');
        $reportTime = date('h:i A');
        $reportRef  = 'LSA-' . date('Ymd-His');

        $criticalRows = '';
        foreach ($criticalItems as $item) {
            $remaining = round(floatval($item['current_quantity']), 2);
            $initial   = isset($item['initial_qty']) ? round(floatval($item['initial_qty']), 2) : '—';
            $used      = isset($item['qty_used']) ? round(floatval($item['qty_used']), 2) : '—';
            $unit      = $item['unit'] ?? '';
            $lastUpdate = isset($item['updated_at']) ? date('M d, Y h:i A', strtotime($item['updated_at'])) : '—';
            $criticalRows .= "
                <tr style='background-color: #fff5f5;'>
                    <td style='padding: 10px 12px; border-bottom: 1px solid #eee;'>{$item['material_name']}</td>
                    <td style='padding: 10px 12px; border-bottom: 1px solid #eee;'>{$item['category_name']}</td>
                    <td style='padding: 10px 12px; border-bottom: 1px solid #eee; text-align: right;'>{$initial} {$unit}</td>
                    <td style='padding: 10px 12px; border-bottom: 1px solid #eee; text-align: right;'>{$used} {$unit}</td>
                    <td style='padding: 10px 12px; border-bottom: 1px solid #eee; text-align: right; color: #dc3545; font-weight: bold;'>{$remaining} {$unit}</td>
                    <td style='padding: 10px 12px; border-bottom: 1px solid #eee;'><span style='background: #dc3545; color: white; padding: 2px 8px; border-radius: 10px; font-size: 11px;'>CRITICAL</span></td>
                    <td style='padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 12px; color: #888;'>{$lastUpdate}</td>
                </tr>";
        }

        $warningRows = '';
        foreach ($warningItems as $item) {
            $remaining = round(floatval($item['current_quantity']), 2);
            $initial   = isset($item['initial_qty']) ? round(floatval($item['initial_qty']), 2) : '—';
            $used      = isset($item['qty_used']) ? round(floatval($item['qty_used']), 2) : '—';
            $unit      = $item['unit'] ?? '';
            $lastUpdate = isset($item['updated_at']) ? date('M d, Y h:i A', strtotime($item['updated_at'])) : '—';
            $warningRows .= "
                <tr>
                    <td style='padding: 10px 12px; border-bottom: 1px solid #eee;'>{$item['material_name']}</td>
                    <td style='padding: 10px 12px; border-bottom: 1px solid #eee;'>{$item['category_name']}</td>
                    <td style='padding: 10px 12px; border-bottom: 1px solid #eee; text-align: right;'>{$initial} {$unit}</td>
                    <td style='padding: 10px 12px; border-bottom: 1px solid #eee; text-align: right;'>{$used} {$unit}</td>
                    <td style='padding: 10px 12px; border-bottom: 1px solid #eee; text-align: right; color: #e67e22; font-weight: bold;'>{$remaining} {$unit}</td>
                    <td style='padding: 10px 12px; border-bottom: 1px solid #eee;'><span style='background: #f39c12; color: white; padding: 2px 8px; border-radius: 10px; font-size: 11px;'>LOW</span></td>
                    <td style='padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 12px; color: #888;'>{$lastUpdate}</td>
                </tr>";
        }

        $totalCritical = count($criticalItems);
        $totalWarning  = count($warningItems);
        $totalItems    = $totalCritical + $totalWarning;

        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                .container { max-width: 750px; margin: 0 auto; padding: 20px; }
                .header { background-color: #dc3545; color: white; padding: 25px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background-color: #f9f9f9; padding: 25px; border: 1px solid #ddd; border-radius: 0 0 5px 5px; }
                table { width: 100%; border-collapse: collapse; }
                th { background-color: #007B4C; color: white; padding: 10px 12px; text-align: left; font-size: 13px; }
                .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1 style='margin: 0; font-size: 24px;'>Low Stock Alert</h1>
                    <p style='margin: 5px 0 0; font-size: 14px;'>E n' G Bakery — Raw Material Stock Report</p>
                </div>
                <div class='content'>
                    <!-- Report Details -->
                    <table style='margin-bottom: 20px;'>
                        <tr>
                            <td style='padding: 6px 0; font-size: 13px; color: #555; width: 140px;'><strong>Report Reference:</strong></td>
                            <td style='padding: 6px 0; font-size: 13px; color: #333;'>{$reportRef}</td>
                        </tr>
                        <tr>
                            <td style='padding: 6px 0; font-size: 13px; color: #555;'><strong>Date Generated:</strong></td>
                            <td style='padding: 6px 0; font-size: 13px; color: #333;'>{$reportDate}</td>
                        </tr>
                        <tr>
                            <td style='padding: 6px 0; font-size: 13px; color: #555;'><strong>Time Generated:</strong></td>
                            <td style='padding: 6px 0; font-size: 13px; color: #333;'>{$reportTime}</td>
                        </tr>
                        <tr>
                            <td style='padding: 6px 0; font-size: 13px; color: #555;'><strong>Total Alerts:</strong></td>
                            <td style='padding: 6px 0; font-size: 13px; color: #333;'>{$totalItems} material(s)</td>
                        </tr>
                    </table>

                    <hr style='border: none; border-top: 1px solid #ddd; margin: 15px 0;'>

                    <p style='font-size: 14px;'>Dear Owner,</p>
                    <p style='font-size: 14px;'>This is to inform you that the following raw materials have reached low stock levels and may require immediate restocking to ensure uninterrupted production.</p>

                    <!-- Summary Cards -->
                    <table style='margin: 20px 0;'>
                        <tr>
                            <td style='padding: 15px; background: #fff; border: 1px solid #ddd; border-radius: 5px; text-align: center; width: 50%;'>
                                <div style='font-size: 28px; font-weight: bold; color: #dc3545;'>{$totalCritical}</div>
                                <div style='font-size: 12px; color: #666; margin-top: 4px;'>Critical (≤ 10 units)</div>
                            </td>
                            <td style='width: 10px;'></td>
                            <td style='padding: 15px; background: #fff; border: 1px solid #ddd; border-radius: 5px; text-align: center; width: 50%;'>
                                <div style='font-size: 28px; font-weight: bold; color: #f39c12;'>{$totalWarning}</div>
                                <div style='font-size: 12px; color: #666; margin-top: 4px;'>Low (≤ 25 units)</div>
                            </td>
                        </tr>
                    </table>

                    <!-- Stock Details Table -->
                    <table style='margin: 20px 0;'>
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Category</th>
                                <th style='text-align: right;'>Initial Stock</th>
                                <th style='text-align: right;'>Used</th>
                                <th style='text-align: right;'>Remaining</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$criticalRows}
                            {$warningRows}
                        </tbody>
                    </table>

                    <hr style='border: none; border-top: 1px solid #ddd; margin: 15px 0;'>

                    <p style='font-size: 14px;'>
                        Please coordinate with the team to restock the above materials at the earliest convenience. 
                        Failure to replenish critical items may result in production delays and order fulfillment issues.
                    </p>
                    <p style='font-size: 14px;'>
                        For the full inventory overview, please refer to the <strong>Material Costing</strong> or <strong>Stock Initial</strong> page in the system.
                    </p>
                    <p style='font-size: 14px; margin-top: 20px;'>
                        Respectfully,<br>
                        <strong>E n' G Bakery Inventory System</strong>
                    </p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " E n' G Bakery. All rights reserved.</p>
                    <p>This is a system-generated report. Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>";
    }
}