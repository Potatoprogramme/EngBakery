<?php

namespace App\Libraries;

use App\Models\DailyStockModel;
use App\Models\DailyStockItemsModel;
use App\Models\UsersModel;

/**
 * AutoReportScheduler
 *
 * Opportunistic pseudo-cron scheduler. Attached to CodeIgniter's `pre_system`
 * event in Config/Events.php so it is evaluated on every incoming web request.
 *
 * Design principles
 * ─────────────────
 *  • Idempotent   – flag files guarantee each slot fires exactly once per day.
 *  • Race-safe    – flock() prevents two simultaneous requests from both
 *                   passing the flag check and sending duplicate emails.
 *  • Silent       – all failures are logged; they never surface to the user.
 *  • Zero deps    – reuses the same Services::email() + UsersModel pattern
 *                   used by LowStockNotifier and DailyRemittanceReport.
 *
 * Schedule
 * ────────────────────────────────────────────────────────────────────────────
 *  AM slot : 15:00 – 15:59  →  afternoon inventory snapshot
 *  PM slot : 20:00 – 20:59  →  end-of-business-day  inventory snapshot
 *
 * Flag files (in WRITEPATH)
 * ─────────────────────────
 *  inventory_report_sent_{Y-m-d}_am.flag
 *  inventory_report_sent_{Y-m-d}_pm.flag
 *
 * Lock files (in WRITEPATH, transient – prevent concurrent send race)
 * ───────────────────────────────────────────────────────────────────
 *  inventory_report_am.lock
 *  inventory_report_pm.lock
 */
class AutoReportScheduler
{
    /**
     * Scheduled time windows.
     * Each slot fires during [ start_h, end_h ) — i.e. the entire named hour.
     *
     * @var array<string, array{start_h: int, end_h: int}>
     */
    private const SLOTS = [
        'am' => ['start_h' => 15, 'end_h' => 16],  // 15:00 – 15:59
        'pm' => ['start_h' => 20, 'end_h' => 21],  // 20:00 – 20:59
    ];

    private const CRITICAL_STOCK = 2;
    private const WARNING_STOCK  = 5;

    // =========================================================================
    //  PUBLIC API
    // =========================================================================

    /**
     * Entry point — called on every web request via Events.php.
     * Iterates over scheduled slots and fires reports that are due and unsent.
     */
    public static function runDueJobs(): void
    {
        // Disabled by product decision: inventory reports are manual-only.
        return;

        $nowH  = (int) date('G');   // 0–23, no leading zero
        $today = date('Y-m-d');

        foreach (self::SLOTS as $slot => $window) {

            // ── Is the current hour inside this slot's fire window? ───────
            if ($nowH < $window['start_h'] || $nowH >= $window['end_h']) {
                continue;
            }

            $flagFile = WRITEPATH . "inventory_report_sent_{$today}_{$slot}.flag";

            // ── Fast path: already sent today ────────────────────────────
            if (file_exists($flagFile)) {
                continue;
            }

            // ── Acquire a per-slot exclusive lock to prevent concurrent ───
            // ── requests from both passing the flag check at the same time ─
            $lockFile = WRITEPATH . "inventory_report_{$slot}.lock";
            $lock     = @fopen($lockFile, 'c');

            if ($lock === false || !flock($lock, LOCK_EX | LOCK_NB)) {
                // Another request holds the lock — it will handle this slot
                if ($lock !== false) {
                    fclose($lock);
                }
                continue;
            }

            try {
                // ── Double-checked locking: re-read flag inside the lock ─
                if (file_exists($flagFile)) {
                    continue; // Sent between our first check and lock acquire
                }

                log_message('info', "AutoReportScheduler: Firing [{$slot}] inventory report for {$today}.");

                $sent = self::sendInventoryReport($slot, $today);

                if ($sent) {
                    // Mark as sent — subsequent requests in this hour skip it
                    file_put_contents($flagFile, date('Y-m-d H:i:s'));
                    log_message('info', "AutoReportScheduler: [{$slot}] report sent and flagged for {$today}.");
                } else {
                    log_message('warning', "AutoReportScheduler: [{$slot}] report not sent (no owners, empty stock, or send failure).");
                }

            } catch (\Throwable $e) {
                log_message('error', "AutoReportScheduler [{$slot}] exception: " . $e->getMessage());
            } finally {
                flock($lock, LOCK_UN);
                fclose($lock);
            }
        }
    }

    /**
     * Public manual trigger used by Inventory/SendReport.
     */
    public static function sendManualReport(?string $date = null): bool
    {
        $targetDate = $date ?: date('Y-m-d');
        return self::sendInventoryReport('manual', $targetDate);
    }

    // =========================================================================
    //  INTERNAL HELPERS
    // =========================================================================

    /**
     * Build and dispatch the inventory status email for the given slot.
     *
     * @param string $slot  'am' or 'pm'
     * @param string $date  'Y-m-d'
     */
    private static function sendInventoryReport(string $slot, string $date): bool
    {
        /* ===== OLD RAW MATERIALS ALERT LOGIC (ARCHIVED) =====
        $stockModel = new RawMaterialStockModel();
        $allItems   = $stockModel->getAllWithDetails();

        foreach ($allItems as $item) {
            $initial   = floatval($item['initial_qty'] ?? 0);
            $remaining = floatval($item['remaining']   ?? 0);
            // Classify by percentage, then send full raw-material report.
        }
        This was replaced by BEG / PO / END alert logic
        ==================================================== */

        $dailyStockModel = new DailyStockModel();
        $dailyStock = $dailyStockModel->checkInventoryExists($date);
        if (!$dailyStock || empty($dailyStock['daily_stock_id'])) {
            log_message('info', 'AutoReportScheduler: No daily inventory record found. Report skipped.');
            return false;
        }

        $itemsModel = new DailyStockItemsModel();
        $allItems = $itemsModel->fetchAllStockItems(intval($dailyStock['daily_stock_id']));
        if (empty($allItems)) {
            log_message('info', 'AutoReportScheduler: No inventory products found. Report skipped.');
            return false;
        }

        // ── 2. Classify each product by ending stock count ───────────────────
        $criticalItems = [];
        $warningItems  = [];
        $lowItems      = [];

        foreach ($allItems as $item) {
            $category = strtolower((string) ($item['category'] ?? ''));
            if (!in_array($category, ['bakery', 'grocery'], true)) {
                continue;
            }

            $ending = intval($item['ending_stock'] ?? 0);
            if ($ending <= self::CRITICAL_STOCK) {
                $item['stock_status'] = 'critical';
                $criticalItems[] = $item;
                $lowItems[] = $item;
            } elseif ($ending <= self::WARNING_STOCK) {
                $item['stock_status'] = 'warning';
                $warningItems[] = $item;
                $lowItems[] = $item;
            } else {
                $item['stock_status'] = 'normal';
            }
        }

        if (empty($lowItems)) {
            log_message('info', 'AutoReportScheduler: No low product stock found. Report skipped.');
            return false;
        }

        // ── 3. Resolve owner recipients ──────────────────────────────────────
        $usersModel = new UsersModel();
        $owners = $usersModel
            ->where('employee_type', 'owner')
            ->where('approved', 1)
            ->findAll();

        if (empty($owners)) {
            log_message('warning', 'AutoReportScheduler: No approved owner accounts found. Report not sent.');
            return false;
        }

        $ownerEmails = array_column($owners, 'email');
        $slotLabelMap = [
            'am' => 'Afternoon (3:00 PM)',
            'pm' => 'Evening (8:00 PM)',
        ];
        $slotLabel = $slotLabelMap[$slot] ?? null;
        $subject = $slotLabel
            ? ('📦 Low Product Inventory Report — ' . $slotLabel . ' — ' . date('F d, Y', strtotime($date)))
            : ('📦 Low Product Inventory Report — ' . date('F d, Y', strtotime($date)));

        $emailBody = self::buildEmailBody($lowItems, $criticalItems, $warningItems, $slot, $date);

        // ── 4. Send via the configured email service ─────────────────────────
        try {
            $emailService = \Config\Services::email();
            $emailService->setFrom('noreply@engbakery.com', "E n' G Bakery");
            $emailService->setTo($ownerEmails);
            $emailService->setSubject($subject);
            $emailService->setMessage($emailBody);
            $emailService->setMailType('html');

            if ($emailService->send()) {
                log_message('info', 'AutoReportScheduler: Email dispatched to: ' . implode(', ', $ownerEmails));
                return true;
            }

            log_message('error', 'AutoReportScheduler: send() returned false. Debug: '
                . $emailService->printDebugger(['headers']));

        } catch (\Exception $e) {
            log_message('error', 'AutoReportScheduler: Email exception — ' . $e->getMessage());
        }

        return false;
    }

    // =========================================================================
    //  EMAIL BUILDERS
    // =========================================================================

    /**
     * Assemble the complete HTML email body for a scheduled inventory report.
     */
    private static function buildEmailBody(array $lowItems, array $criticalItems, array $warningItems, string $slot, string $date): string
    {
        $reportDate   = date('F d, Y', strtotime($date));
        $reportTime   = date('h:i A');
        $reportRef    = 'INV-' . strtoupper($slot) . '-' . date('Ymd-His');
        if ($slot === 'am') {
            $slotTitle = 'Afternoon Inventory Report';
            $slotSubtitle = '3:00 PM Scheduled Snapshot';
            $headerColor = '#17a2b8';
        } elseif ($slot === 'pm') {
            $slotTitle = 'Evening Inventory Report';
            $slotSubtitle = '8:00 PM Scheduled Snapshot';
            $headerColor = '#6f42c1';
        } else {
            $slotTitle = 'Inventory Report';
            $slotSubtitle = 'Manually Generated Snapshot';
            $headerColor = '#0f766e';
        }

        $totalCritical = count($criticalItems);
        $totalWarning  = count($warningItems);
        $totalItems    = count($lowItems);

        usort($lowItems, static function ($a, $b) {
            $statusWeight = ['critical' => 0, 'warning' => 1, 'normal' => 2];
            $aWeight = $statusWeight[$a['stock_status'] ?? 'normal'] ?? 9;
            $bWeight = $statusWeight[$b['stock_status'] ?? 'normal'] ?? 9;
            if ($aWeight !== $bWeight) {
                return $aWeight <=> $bWeight;
            }

            $aName = strtolower((string) ($a['product_name'] ?? ''));
            $bName = strtolower((string) ($b['product_name'] ?? ''));
            return $aName <=> $bName;
        });

        $tableRows = self::buildLowStockRows($lowItems);

        $year = date('Y');

        return "
        <html>
        <head>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                .container { max-width: 750px; margin: 0 auto; padding: 20px; }
                .header { background-color: {$headerColor}; color: white; padding: 25px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background-color: #f9f9f9; padding: 25px; border: 1px solid #ddd; border-radius: 0 0 5px 5px; }
                table { width: 100%; border-collapse: collapse; }
                .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>

                <!-- Header -->
                <div class='header'>
                    <h1 style='margin:0;font-size:24px;'>{$slotTitle}</h1>
                    <p style='margin:5px 0 0;font-size:14px;'>E n' G Bakery &mdash; {$slotSubtitle}</p>
                </div>

                <div class='content'>

                    <!-- Report Metadata -->
                    <table style='margin-bottom:20px;'>
                        <tr>
                            <td style='padding:6px 0;font-size:13px;color:#555;width:140px;'><strong>Report Reference:</strong></td>
                            <td style='padding:6px 0;font-size:13px;color:#333;'>{$reportRef}</td>
                        </tr>
                        <tr>
                            <td style='padding:6px 0;font-size:13px;color:#555;'><strong>Report Date:</strong></td>
                            <td style='padding:6px 0;font-size:13px;color:#333;'>{$reportDate}</td>
                        </tr>
                        <tr>
                            <td style='padding:6px 0;font-size:13px;color:#555;'><strong>Generated At:</strong></td>
                            <td style='padding:6px 0;font-size:13px;color:#333;'>{$reportTime}</td>
                        </tr>
                        <tr>
                            <td style='padding:6px 0;font-size:13px;color:#555;'><strong>Total Materials:</strong></td>
                            <td style='padding:6px 0;font-size:13px;color:#333;'>{$totalItems} low product(s)</td>
                        </tr>
                    </table>

                    <hr style='border:none;border-top:1px solid #ddd;margin:15px 0;'>

                    <p style='font-size:14px;'>Dear Owner,</p>
                    <p style='font-size:14px;'>
                        Below is your <strong>{$slotSubtitle}</strong> inventory snapshot for <strong>{$reportDate}</strong>.
                        This alert is based on product <strong>Ending Stock</strong>. The table includes <strong>Beginning</strong>, <strong>Pull Out</strong>, and <strong>Ending</strong> values.
                    </p>

                    <!-- Summary Cards -->
                    <table style='margin:20px 0;'>
                        <tr>
                            <td style='padding:15px;background:#fff5f5;border:1px solid #dc3545;border-radius:5px;text-align:center;width:33%;'>
                                <div style='font-size:28px;font-weight:bold;color:#dc3545;'>{$totalCritical}</div>
                                <div style='font-size:11px;color:#666;margin-top:4px;'>Critical (Ending &le; 2)</div>
                            </td>
                            <td style='width:10px;'></td>
                            <td style='padding:15px;background:#fffdf0;border:1px solid #e67e22;border-radius:5px;text-align:center;width:33%;'>
                                <div style='font-size:28px;font-weight:bold;color:#e67e22;'>{$totalWarning}</div>
                                <div style='font-size:11px;color:#666;margin-top:4px;'>Warning (Ending &le; 5)</div>
                            </td>
                            <td style='width:10px;'></td>
                            <td style='padding:15px;background:#f5f5f5;border:1px solid #d1d5db;border-radius:5px;text-align:center;width:33%;'>
                                <div style='font-size:28px;font-weight:bold;color:#374151;'>{$totalItems}</div>
                                <div style='font-size:11px;color:#666;margin-top:4px;'>Total Low Alerts</div>
                            </td>
                        </tr>
                    </table>

                    <h3 style='font-size:16px;color:#111827;margin:20px 0 10px;'>Low Product Inventory Table</h3>
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
                                {$tableRows}
                            </tbody>
                        </table>
                    </div>

                    <hr style='border:none;border-top:1px solid #ddd;margin:25px 0 15px;'>

                    <p style='font-size:14px;'>
                        Please review critical and warning products and arrange replenishment as needed.
                        For the full inventory management interface, visit the <strong>Inventory</strong> page in the system.
                    </p>

                    <p style='font-size:14px;margin-top:20px;'>
                        Respectfully,<br>
                        <strong>E n' G Bakery Inventory System</strong>
                    </p>

                </div>

                <div class='footer'>
                    <p>&copy; {$year} E n' G Bakery. All rights reserved.</p>
                    <p>This is a system-generated report. Please do not reply to this email.</p>
                </div>

            </div>
        </body>
        </html>";
    }

    private static function buildLowStockRows(array $items): string
    {
        if (empty($items)) {
            return '';
        }

        $rows = '';

        foreach ($items as $item) {
            $name = htmlspecialchars((string) ($item['product_name'] ?? 'Unknown'));
            $category = htmlspecialchars((string) ($item['category'] ?? ''));
            $beg = intval($item['beginning_stock'] ?? 0);
            $po = intval($item['pull_out_quantity'] ?? 0);
            $end = intval($item['ending_stock'] ?? 0);
            $status = strtolower((string) ($item['stock_status'] ?? 'warning'));

            $statusLabel = $status === 'critical' ? 'CRITICAL' : 'WARNING';
            $statusStyle = $status === 'critical'
                ? 'background:#fff5f5;color:#dc3545;border:1px solid #dc3545;'
                : 'background:#fff8e8;color:#b45309;border:1px solid #f59e0b;';

            $rows .= "
                <tr>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;color:#111827;'>{$name}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;color:#4b5563;'>{$category}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;text-align:center;'>{$beg}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;text-align:center;'>{$po}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;text-align:center;font-weight:700;color:#b91c1c;'>{$end}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:11px;text-align:center;'>
                        <span style='padding:3px 8px;border-radius:999px;font-weight:700;{$statusStyle}'>{$statusLabel}</span>
                    </td>
                </tr>";
        }

        return $rows;
    }
}
