<?php

namespace App\Libraries;

use App\Models\DailyStockModel;
use App\Models\DailyStockItemsModel;
use App\Models\RawMaterialStockModel;
use App\Models\UsersModel;

class LowStockNotifier
{
    private const RAW_CRITICAL_PERCENT = 25.0;
    private const RAW_WARNING_PERCENT = 40.0;

    /**
     * Send combined low-stock notifier for products and raw materials.
     */
    public static function checkAndNotify(int $criticalStock = 2, int $warningStock = 5, bool $forceSend = false): void
    {
        log_message('info', 'Combined stock check initiated at ' . date('Y-m-d H:i:s'));

        /* ===== OLD RAW MATERIALS ALERT LOGIC (ARCHIVED) =====
        $stockModel = new RawMaterialStockModel();
        $lowStockItems = $stockModel->getLowStockMaterials($criticalPercent, $warningPercent);

        if (empty($lowStockItems)) {
            log_message('info', 'No low stock items found. No notification needed.');
            return;
        }

        $criticalItems = array_filter($lowStockItems, fn($item) => $item['stock_status'] === 'critical');
        $warningItems  = array_filter($lowStockItems, fn($item) => $item['stock_status'] === 'warning');
        $hasCritical = !empty($criticalItems);

        if (!$forceSend && !$hasCritical && file_exists($flagFile)) {
            return;
        }

        $emailBody = self::buildEmailBody($criticalItems, $warningItems);
        This was replaced by BEG / PO / END alert logic
        ==================================================== */

        $today = date('Y-m-d');
        $flagFile = WRITEPATH . 'lowstock_email_sent_' . $today . '.flag';

        $items = self::getTodayProductInventoryItems($today);

        // Low stock is determined by ending stock only, while Beg/PO/End are included in the email table.
        $criticalItems = [];
        $warningItems = [];
        foreach ($items as $item) {
            $ending = intval($item['ending_stock'] ?? 0);
            if ($ending <= $criticalStock) {
                $item['stock_status'] = 'critical';
                $criticalItems[] = $item;
            } elseif ($ending <= $warningStock) {
                $item['stock_status'] = 'warning';
                $warningItems[] = $item;
            }
        }

        $rawModel = new RawMaterialStockModel();
        $rawLowItems = $rawModel->getLowStockMaterials(self::RAW_CRITICAL_PERCENT, self::RAW_WARNING_PERCENT);
        $rawCriticalItems = array_values(array_filter($rawLowItems, static fn($item) => ($item['stock_status'] ?? '') === 'critical'));
        $rawWarningItems = array_values(array_filter($rawLowItems, static fn($item) => ($item['stock_status'] ?? '') === 'warning'));

        $lowProductItems = array_merge($criticalItems, $warningItems);
        $lowRawItems = array_merge($rawCriticalItems, $rawWarningItems);

        if (empty($lowProductItems) && empty($lowRawItems)) {
            log_message('info', 'No low product/raw-material stock items found. No notification needed.');
            return;
        }

        $hasCritical = !empty($criticalItems) || !empty($rawCriticalItems);

        if (!$forceSend && !$hasCritical && file_exists($flagFile)) {
            log_message('info', 'Combined stock notification already sent today (warning items only). Skipping.');
            return; // Already sent today for warning-only items
        }

        if ($hasCritical) {
            log_message('warning', 'CRITICAL: Found low critical stock items. Sending immediate alert.');
        }

        // Get all owner emails
        $usersModel = new UsersModel();
        $owners = $usersModel->where('employee_type', 'owner')
            ->where('approved', 1)
            ->findAll();

        if (empty($owners)) {
            log_message('warning', 'Combined low stock alert: No owner accounts found to notify.');
            return;
        }

        $ownerEmails = array_column($owners, 'email');
        log_message('info', 'Sending combined low stock alert to ' . count($ownerEmails) . ' owner(s): ' . implode(', ', $ownerEmails));

        // Build the email
        $emailBody = self::buildEmailBody($criticalItems, $warningItems, $rawCriticalItems, $rawWarningItems, $today);

        $subjectPrefix = $hasCritical ? '🚨 LOW' : '⚠';
        $emailSubject = $subjectPrefix . ' Stock Alert — ' . count($lowProductItems) . ' product(s), ' . count($lowRawItems) . ' raw material(s) running low';

        // Send
        try {
            $emailService = \Config\Services::email();
            $emailService->setFrom('noreply@engbakery.com', "E n' G Bakery - Deca Sentrio");
            $emailService->setTo($ownerEmails);
            $emailService->setSubject($emailSubject);
            $emailService->setMessage($emailBody);
            $emailService->setMailType('html');

            if ($emailService->send()) {
                // Mark as sent for today (only for non-critical alerts)
                if (!$hasCritical) {
                    file_put_contents($flagFile, date('Y-m-d H:i:s'));
                }
                log_message('info', 'Combined low stock alert email sent successfully to: ' . implode(', ', $ownerEmails));
            } else {
                log_message('error', 'Failed to send combined low stock email: ' . $emailService->printDebugger(['headers']));
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception sending combined low stock email: ' . $e->getMessage());
        }
    }

    /**
     * Build today's product inventory rows with Beg/PO/End.
     */
    private static function getTodayProductInventoryItems(string $today): array
    {
        $dailyStockModel = new DailyStockModel();
        $dailyStock = $dailyStockModel->checkInventoryExists($today);
        if (!$dailyStock || empty($dailyStock['daily_stock_id'])) {
            return [];
        }

        $itemsModel = new DailyStockItemsModel();
        $items = $itemsModel->fetchAllStockItems(intval($dailyStock['daily_stock_id']));
        if (empty($items)) {
            return [];
        }

        return array_values(array_filter($items, static function (array $item): bool {
            $category = strtolower((string) ($item['category'] ?? ''));
            return in_array($category, ['bakery', 'grocery'], true);
        }));
    }

    /**
     * Build the HTML email body
     */
    public static function buildEmailBody(array $criticalItems, array $warningItems, array $rawCriticalItems, array $rawWarningItems, string $inventoryDate): string
    {
        $reportDate = date('F d, Y', strtotime($inventoryDate));
        $reportTime = date('h:i A');
        $reportRef = 'LSA-' . date('Ymd-His');
        $allItems = array_merge($criticalItems, $warningItems);

        usort($allItems, static function ($a, $b) {
            $statusWeight = ['critical' => 0, 'warning' => 1];
            $aWeight = $statusWeight[$a['stock_status'] ?? 'warning'] ?? 9;
            $bWeight = $statusWeight[$b['stock_status'] ?? 'warning'] ?? 9;
            if ($aWeight !== $bWeight) {
                return $aWeight <=> $bWeight;
            }

            $aName = strtolower((string) ($a['product_name'] ?? ''));
            $bName = strtolower((string) ($b['product_name'] ?? ''));
            return $aName <=> $bName;
        });

        $rows = '';
        foreach ($allItems as $item) {
            $name = htmlspecialchars((string) ($item['product_name'] ?? 'Unknown'));
            $category = htmlspecialchars((string) ($item['category'] ?? ''));
            $beg = intval($item['beginning_stock'] ?? 0);
            $po = intval($item['pull_out_quantity'] ?? 0);
            $end = intval($item['ending_stock'] ?? 0);
            $status = ($item['stock_status'] ?? '') === 'critical' ? 'CRITICAL' : 'WARNING';
            $statusStyle = $status === 'CRITICAL'
                ? "background:#fff5f5;color:#dc3545;border:1px solid #dc3545;"
                : "background:#fff8e8;color:#b45309;border:1px solid #f59e0b;";

            $rows .= "
                <tr>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;color:#111827;'>{$name}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;color:#4b5563;'>{$category}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;text-align:center;'>{$beg}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;text-align:center;'>{$po}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;text-align:center;font-weight:700;color:#b91c1c;'>{$end}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:11px;text-align:center;'>
                        <span style='padding:3px 8px;border-radius:999px;font-weight:700;{$statusStyle}'>{$status}</span>
                    </td>
                </tr>";
        }

        $totalCritical = count($criticalItems);
        $totalWarning = count($warningItems);
        $totalItems = $totalCritical + $totalWarning;

        $rawAllItems = array_merge($rawCriticalItems, $rawWarningItems);
        usort($rawAllItems, static function ($a, $b) {
            $statusWeight = ['critical' => 0, 'warning' => 1];
            $aWeight = $statusWeight[$a['stock_status'] ?? 'warning'] ?? 9;
            $bWeight = $statusWeight[$b['stock_status'] ?? 'warning'] ?? 9;
            if ($aWeight !== $bWeight) {
                return $aWeight <=> $bWeight;
            }

            $aName = strtolower((string) ($a['material_name'] ?? ''));
            $bName = strtolower((string) ($b['material_name'] ?? ''));
            return $aName <=> $bName;
        });

        $rawRows = '';
        foreach ($rawAllItems as $item) {
            $name = htmlspecialchars((string) ($item['material_name'] ?? 'Unknown'));
            $category = htmlspecialchars((string) ($item['category_name'] ?? ''));
            $initial = round(floatval($item['initial_qty'] ?? 0), 2);
            $used = round(floatval($item['qty_used'] ?? 0), 2);
            $remaining = round(floatval($item['current_quantity'] ?? 0), 2);
            $unit = htmlspecialchars((string) ($item['unit'] ?? ''));
            $status = ($item['stock_status'] ?? '') === 'critical' ? 'CRITICAL' : 'WARNING';
            $statusStyle = $status === 'CRITICAL'
                ? "background:#fff5f5;color:#dc3545;border:1px solid #dc3545;"
                : "background:#fff8e8;color:#b45309;border:1px solid #f59e0b;";

            $rawRows .= "
                <tr>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;color:#111827;'>{$name}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;color:#4b5563;'>{$category}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;text-align:center;'>{$initial}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;text-align:center;'>{$used}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;text-align:center;font-weight:700;color:#b91c1c;'>{$remaining} {$unit}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:11px;text-align:center;'>
                        <span style='padding:3px 8px;border-radius:999px;font-weight:700;{$statusStyle}'>{$status}</span>
                    </td>
                </tr>";
        }

        $rawCriticalCount = count($rawCriticalItems);
        $rawWarningCount = count($rawWarningItems);
        $rawTotal = $rawCriticalCount + $rawWarningCount;

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
                    <p style='margin: 5px 0 0; font-size: 14px;'>E n' G Bakery - Deca Sentrio — Products and Raw Materials Report</p>
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
                            <td style='padding: 6px 0; font-size: 13px; color: #333;'>{$totalItems} product(s), {$rawTotal} raw material(s)</td>
                        </tr>
                    </table>

                    <hr style='border: none; border-top: 1px solid #ddd; margin: 15px 0;'>

                    <p style='font-size: 14px;'>Dear Owner,</p>
                    <p style='font-size: 14px;'>This is to inform you that both product inventory and raw material stock have low-level alerts.</p>

                    <!-- Summary Cards -->
                    <table style='margin: 20px 0;'>
                        <tr>
                            <td style='padding: 15px; background: #fff; border: 1px solid #ddd; border-radius: 5px; text-align: center; width: 50%;'>
                                <div style='font-size: 28px; font-weight: bold; color: #dc3545;'>{$totalCritical}</div>
                                <div style='font-size: 12px; color: #666; margin-top: 4px;'>Critical (Ending ≤ 2)</div>
                            </td>
                            <td style='width: 10px;'></td>
                            <td style='padding: 15px; background: #fff; border: 1px solid #ddd; border-radius: 5px; text-align: center; width: 50%;'>
                                <div style='font-size: 28px; font-weight: bold; color: #f39c12;'>{$totalWarning}</div>
                                <div style='font-size: 12px; color: #666; margin-top: 4px;'>Warning (Ending ≤ 5)</div>
                            </td>
                        </tr>
                    </table>

                    <h3 style='font-size:16px;color:#333;margin:25px 0 12px;'>Low Stock Products</h3>
                    <div style='overflow-x:auto;'>
                        <table style='width:100%;border-collapse:collapse;background:#fff;border:1px solid #e5e7eb;'>
                            <thead>
                                <tr style='background:#f3f4f6;'>
                                    <th style='padding:10px;text-align:left;font-size:12px;border-bottom:1px solid #d1d5db;'>Product</th>
                                    <th style='padding:10px;text-align:left;font-size:12px;border-bottom:1px solid #d1d5db;'>Category</th>
                                    <th style='padding:10px;text-align:center;font-size:12px;border-bottom:1px solid #d1d5db;'>Beginning</th>
                                    <th style='padding:10px;text-align:center;font-size:12px;border-bottom:1px solid #d1d5db;'>Pull Out</th>
                                    <th style='padding:10px;text-align:center;font-size:12px;border-bottom:1px solid #d1d5db;'>Ending</th>
                                    <th style='padding:10px;text-align:center;font-size:12px;border-bottom:1px solid #d1d5db;'>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {$rows}
                            </tbody>
                        </table>
                    </div>

                    <h3 style='font-size:16px;color:#333;margin:25px 0 12px;'>Low Raw Materials</h3>
                    <div style='overflow-x:auto;'>
                        <table style='width:100%;border-collapse:collapse;background:#fff;border:1px solid #e5e7eb;'>
                            <thead>
                                <tr style='background:#f3f4f6;'>
                                    <th style='padding:10px;text-align:left;font-size:12px;border-bottom:1px solid #d1d5db;'>Material</th>
                                    <th style='padding:10px;text-align:left;font-size:12px;border-bottom:1px solid #d1d5db;'>Category</th>
                                    <th style='padding:10px;text-align:center;font-size:12px;border-bottom:1px solid #d1d5db;'>Initial</th>
                                    <th style='padding:10px;text-align:center;font-size:12px;border-bottom:1px solid #d1d5db;'>Used</th>
                                    <th style='padding:10px;text-align:center;font-size:12px;border-bottom:1px solid #d1d5db;'>Remaining</th>
                                    <th style='padding:10px;text-align:center;font-size:12px;border-bottom:1px solid #d1d5db;'>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {$rawRows}
                            </tbody>
                        </table>
                    </div>

                    <hr style='border: none; border-top: 1px solid #ddd; margin: 15px 0;'>

                    <p style='font-size: 14px;'>
                        Please coordinate with the team to replenish the low products at the earliest convenience.
                    </p>
                    <p style='font-size: 14px;'>
                        For the full inventory overview, please refer to the <strong>Inventory</strong> page in the system.
                    </p>
                    <p style='font-size: 14px; margin-top: 20px;'>
                        Respectfully,<br>
                        <strong>E n' G Bakery - Deca Sentrio Inventory System</strong>
                    </p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " E n' G Bakery - Deca Sentrio. All rights reserved.</p>
                    <p>This is a system-generated report. Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>";
    }
}