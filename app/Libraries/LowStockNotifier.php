<?php

namespace App\Libraries;

use App\Models\RawMaterialStockModel;
use App\Models\UsersModel;

class LowStockNotifier
{
    /**
     * Check for low stock raw materials and email all owners if any are critical.
     * For critical items (≤25%), always sends email. For warning items only (≤40%), sends once per day.
     *
     * @param float $criticalPercent  Stock percentage considered critical (default 25%)
     * @param float $warningPercent   Stock percentage considered warning (default 40%)
     * @param bool  $forceSend        Force send regardless of flag
     */
    public static function checkAndNotify(float $criticalPercent = 25, float $warningPercent = 40, bool $forceSend = false): void
    {
        log_message('info', 'Low stock check initiated at ' . date('Y-m-d H:i:s'));
        
        $stockModel = new RawMaterialStockModel();
        $lowStockItems = $stockModel->getLowStockMaterials($criticalPercent, $warningPercent);

        if (empty($lowStockItems)) {
            log_message('info', 'No low stock items found. No notification needed.');
            return; // No low stock — nothing to report
        }
        
        log_message('info', 'Found ' . count($lowStockItems) . ' low stock item(s). Checking if notification needed.');

        // Separate critical and warning items
        $criticalItems = array_filter($lowStockItems, fn($item) => $item['stock_status'] === 'critical');
        $warningItems  = array_filter($lowStockItems, fn($item) => $item['stock_status'] === 'warning');
        
        // Check if already sent today (only applies to warning-only alerts)
        $today = date('Y-m-d');
        $flagFile = WRITEPATH . 'lowstock_email_sent_' . $today . '.flag';
        
        // If there are critical items, ALWAYS send the email
        $hasCritical = !empty($criticalItems);
        
        if (!$forceSend && !$hasCritical && file_exists($flagFile)) {
            log_message('info', 'Low stock notification already sent today (warning items only). Skipping.');
            return; // Already sent today for warning-only items
        }
        
        if ($hasCritical) {
            log_message('warning', 'CRITICAL: Found ' . count($criticalItems) . ' critical stock item(s). Sending immediate alert.');
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
        log_message('info', 'Sending low stock alert to ' . count($ownerEmails) . ' owner(s): ' . implode(', ', $ownerEmails));

        // Build the email
        $emailBody = self::buildEmailBody($criticalItems, $warningItems);
        
        $subjectPrefix = $hasCritical ? '🚨 CRITICAL' : '⚠';
        $emailSubject = $subjectPrefix . ' Low Stock Alert — ' . count($lowStockItems) . ' material(s) running low';

        // Send
        try {
            $emailService = \Config\Services::email();
            $emailService->setFrom('noreply@engbakery.com', "E n' G Bakery");
            $emailService->setTo($ownerEmails);
            $emailService->setSubject($emailSubject);
            $emailService->setMessage($emailBody);
            $emailService->setMailType('html');

            if ($emailService->send()) {
                // Mark as sent for today (only for non-critical alerts)
                if (!$hasCritical) {
                    file_put_contents($flagFile, date('Y-m-d H:i:s'));
                }
                log_message('info', 'Low stock alert email sent successfully to: ' . implode(', ', $ownerEmails));
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
    public static function buildEmailBody(array $criticalItems, array $warningItems): string
    {
        $reportDate = date('F d, Y');
        $reportTime = date('h:i A');
        $reportRef  = 'LSA-' . date('Ymd-His');

        $criticalCards = '';
        foreach ($criticalItems as $item) {
            $remaining = round(floatval($item['current_quantity']), 2);
            $initial   = isset($item['initial_qty']) ? round(floatval($item['initial_qty']), 2) : '—';
            $used      = isset($item['qty_used']) ? round(floatval($item['qty_used']), 2) : '—';
            $unit      = $item['unit'] ?? '';
            $pct       = $item['stock_percentage'] ?? 0;
            $lastUpdate = isset($item['updated_at']) ? date('M d, Y h:i A', strtotime($item['updated_at'])) : '—';
            $criticalCards .= "
                <div style='background:#fff5f5;border:1px solid #dc3545;border-radius:8px;padding:15px;margin-bottom:12px;'>
                    <div style='margin-bottom:12px;border-bottom:2px solid #dc3545;padding-bottom:10px;'>
                        <div style='display:inline-block;'>
                            <span style='font-size:16px;font-weight:bold;color:#333;'>{$item['material_name']}</span>
                            <span style='font-size:12px;color:#888;margin-left:8px;'>({$item['category_name']})</span>
                        </div>
                        <div style='float:right;'>
                            <span style='background:#dc3545;color:white;padding:4px 10px;border-radius:12px;font-size:11px;font-weight:bold;'>LOW</span>
                        </div>
                    </div>
                    <table style='width:100%;border-collapse:collapse;'>
                        <tr>
                            <td style='padding:8px 0;width:50%;border-bottom:1px solid #f0f0f0;'>
                                <div style='font-size:10px;color:#888;text-transform:uppercase;'>Initial Stock</div>
                                <div style='font-size:13px;font-weight:bold;color:#333;margin-top:2px;'>{$initial} {$unit}</div>
                            </td>
                            <td style='padding:8px 0;width:50%;border-bottom:1px solid #f0f0f0;'>
                                <div style='font-size:10px;color:#888;text-transform:uppercase;'>Used</div>
                                <div style='font-size:13px;font-weight:bold;color:#333;margin-top:2px;'>{$used} {$unit}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style='padding:8px 0;border-bottom:1px solid #f0f0f0;'>
                                <div style='font-size:10px;color:#888;text-transform:uppercase;'>Remaining</div>
                                <div style='font-size:15px;font-weight:bold;color:#dc3545;margin-top:2px;'>{$remaining} {$unit}</div>
                            </td>
                            <td style='padding:8px 0;border-bottom:1px solid #f0f0f0;'>
                                <div style='font-size:10px;color:#888;text-transform:uppercase;'>Stock Level</div>
                                <div style='font-size:15px;font-weight:bold;color:#dc3545;margin-top:2px;'>{$pct}%</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='2' style='padding:8px 0;'>
                                <div style='font-size:10px;color:#888;text-transform:uppercase;'>Last Updated</div>
                                <div style='font-size:12px;color:#666;margin-top:2px;'>{$lastUpdate}</div>
                            </td>
                        </tr>
                    </table>
                </div>";
        }

        $warningCards = '';
        foreach ($warningItems as $item) {
            $remaining = round(floatval($item['current_quantity']), 2);
            $initial   = isset($item['initial_qty']) ? round(floatval($item['initial_qty']), 2) : '—';
            $used      = isset($item['qty_used']) ? round(floatval($item['qty_used']), 2) : '—';
            $unit      = $item['unit'] ?? '';
            $pct       = $item['stock_percentage'] ?? 0;
            $lastUpdate = isset($item['updated_at']) ? date('M d, Y h:i A', strtotime($item['updated_at'])) : '—';
            $warningCards .= "
                <div style='background:#fff;border:1px solid #f39c12;border-radius:8px;padding:15px;margin-bottom:12px;'>
                    <div style='margin-bottom:12px;border-bottom:2px solid #f39c12;padding-bottom:10px;'>
                        <div style='display:inline-block;'>
                            <span style='font-size:16px;font-weight:bold;color:#333;'>{$item['material_name']}</span>
                            <span style='font-size:12px;color:#888;margin-left:8px;'>({$item['category_name']})</span>
                        </div>
                        <div style='float:right;'>
                            <span style='background:#f39c12;color:white;padding:4px 10px;border-radius:12px;font-size:11px;font-weight:bold;'>LOW</span>
                        </div>
                    </div>
                    <table style='width:100%;border-collapse:collapse;'>
                        <tr>
                            <td style='padding:8px 0;width:50%;border-bottom:1px solid #f0f0f0;'>
                                <div style='font-size:10px;color:#888;text-transform:uppercase;'>Initial Stock</div>
                                <div style='font-size:13px;font-weight:bold;color:#333;margin-top:2px;'>{$initial} {$unit}</div>
                            </td>
                            <td style='padding:8px 0;width:50%;border-bottom:1px solid #f0f0f0;'>
                                <div style='font-size:10px;color:#888;text-transform:uppercase;'>Used</div>
                                <div style='font-size:13px;font-weight:bold;color:#333;margin-top:2px;'>{$used} {$unit}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style='padding:8px 0;border-bottom:1px solid #f0f0f0;'>
                                <div style='font-size:10px;color:#888;text-transform:uppercase;'>Remaining</div>
                                <div style='font-size:15px;font-weight:bold;color:#e67e22;margin-top:2px;'>{$remaining} {$unit}</div>
                            </td>
                            <td style='padding:8px 0;border-bottom:1px solid #f0f0f0;'>
                                <div style='font-size:10px;color:#888;text-transform:uppercase;'>Stock Level</div>
                                <div style='font-size:15px;font-weight:bold;color:#e67e22;margin-top:2px;'>{$pct}%</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='2' style='padding:8px 0;'>
                                <div style='font-size:10px;color:#888;text-transform:uppercase;'>Last Updated</div>
                                <div style='font-size:12px;color:#666;margin-top:2px;'>{$lastUpdate}</div>
                            </td>
                        </tr>
                    </table>
                </div>";
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
                                <div style='font-size: 12px; color: #666; margin-top: 4px;'>Low (≤ 25% remaining)</div>
                            </td>
                            <td style='width: 10px;'></td>
                            <td style='padding: 15px; background: #fff; border: 1px solid #ddd; border-radius: 5px; text-align: center; width: 50%;'>
                                <div style='font-size: 28px; font-weight: bold; color: #f39c12;'>{$totalWarning}</div>
                                <div style='font-size: 12px; color: #666; margin-top: 4px;'>Warning (≤ 40% remaining)</div>
                            </td>
                        </tr>
                    </table>

                    <!-- Stock Details Cards -->
                    <h3 style='font-size:16px;color:#333;margin:25px 0 15px;'>Material Details</h3>
                    {$criticalCards}
                    {$warningCards}

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