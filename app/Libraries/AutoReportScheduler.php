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
    public static function sendManualReport(?string $date = null, ?string $slot = null): bool
    {
        $targetDate = $date ?: date('Y-m-d');
        $manualSlot = self::normalizeSlot($slot) ?? self::resolveManualSlotForNow();
        $includeMissedFirstShift = $manualSlot === 'pm' && !self::hasShiftBeenReported($targetDate, 'am');

        $sent = self::sendInventoryReport($manualSlot, $targetDate, $includeMissedFirstShift);
        if (!$sent) {
            return false;
        }

        self::markShiftReported($targetDate, $manualSlot);
        if ($includeMissedFirstShift) {
            self::markShiftReported($targetDate, 'am');
        }

        return true;
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
    private static function sendInventoryReport(string $slot, string $date, bool $includeMissedFirstShift = false): bool
    {
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

        $shiftWindows = self::resolveShiftWindowsForSlot($slot, $date, $includeMissedFirstShift);
        $shiftReports = self::buildShiftReports($allItems, $date, $shiftWindows);
        if (empty($shiftReports)) {
            log_message('info', 'AutoReportScheduler: No shift data available for inventory report. Report skipped.');
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
            'am' => 'Morning Shift',
            'pm' => 'Afternoon Shift',
        ];
        $slotLabel = $slotLabelMap[$slot] ?? null;
        $subject = $slotLabel
            ? ('📦 Inventory Report — ' . $slotLabel . ' — ' . date('F d, Y', strtotime($date)))
            : ('📦 Inventory Report — ' . date('F d, Y', strtotime($date)));

        $emailBody = self::buildEmailBody($shiftReports, $slot, $date);

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
    private static function buildEmailBody(array $shiftReports, string $slot, string $date): string
    {
        $reportDate   = date('F d, Y', strtotime($date));
        $reportTime   = date('h:i A');
        $reportRef    = 'INV-' . strtoupper($slot) . '-' . date('Ymd-His');
        if ($slot === 'am') {
            $slotTitle = 'Morning Shift Inventory Report';
            $slotSubtitle = 'Morning Shift Snapshot';
            $headerColor = '#17a2b8';
        } elseif ($slot === 'pm') {
            $slotTitle = 'Afternoon Shift Inventory Report';
            $slotSubtitle = 'Afternoon Shift Snapshot';
            $headerColor = '#6f42c1';
        } else {
            $slotTitle = 'Inventory Report';
            $slotSubtitle = 'Manually Generated Snapshot';
            $headerColor = '#0f766e';
        }

        $totalProducts = 0;
        $totalSales = 0.0;
        $totalDirectCostUsed = 0.0;
        $totalOverheadCostUsed = 0.0;
        foreach ($shiftReports as $report) {
            $totalProducts += intval($report['totals']['products'] ?? 0);
            $totalSales += floatval($report['totals']['sales'] ?? 0);
            $totalDirectCostUsed += floatval($report['totals']['direct_cost_used'] ?? 0);
            $totalOverheadCostUsed += floatval($report['totals']['overhead_cost_used'] ?? 0);
        }

        $shiftBlocks = '';
        foreach ($shiftReports as $report) {
            $label = htmlspecialchars((string) ($report['label'] ?? 'Shift'));
            $timeRange = htmlspecialchars((string) ($report['time_range'] ?? ''));
            $bakeryRows = self::buildCategoryRows($report['bakery'] ?? [], true);
            $groceryRows = self::buildCategoryRows($report['grocery'] ?? [], true);
            $drinksRows = self::buildCategoryRows($report['drinks'] ?? [], false);

            $shiftBlocks .= "
                <div style='margin-top:20px;padding:14px;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;'>
                    <div style='font-size:16px;font-weight:700;color:#0f172a;margin-bottom:4px;'>{$label}</div>
                    <div style='font-size:12px;color:#6b7280;margin-bottom:12px;'>{$timeRange}</div>

                    <div style='font-size:13px;font-weight:700;color:#111827;margin-bottom:6px;'>BREAD</div>
                    " . self::buildCategoryTable($bakeryRows, true) . "

                    <div style='font-size:13px;font-weight:700;color:#111827;margin:14px 0 6px;'>GROCERY</div>
                    " . self::buildCategoryTable($groceryRows, true) . "

                    <div style='font-size:13px;font-weight:700;color:#111827;margin:14px 0 6px;'>DRINKS</div>
                    " . self::buildCategoryTable($drinksRows, false) . "
                </div>";
        }

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
                            <td style='padding:6px 0;font-size:13px;color:#555;'><strong>Total Product Rows:</strong></td>
                            <td style='padding:6px 0;font-size:13px;color:#333;'>{$totalProducts}</td>
                        </tr>
                        <tr>
                            <td style='padding:6px 0;font-size:13px;color:#555;'><strong>Total Sales:</strong></td>
                            <td style='padding:6px 0;font-size:13px;color:#333;'>₱" . number_format($totalSales, 2) . "</td>
                        </tr>
                        <tr>
                            <td style='padding:6px 0;font-size:13px;color:#555;'><strong>Total Direct Cost Used:</strong></td>
                            <td style='padding:6px 0;font-size:13px;color:#333;'>₱" . number_format($totalDirectCostUsed, 2) . "</td>
                        </tr>
                        <tr>
                            <td style='padding:6px 0;font-size:13px;color:#555;'><strong>Total Overhead Cost Used:</strong></td>
                            <td style='padding:6px 0;font-size:13px;color:#333;'>₱" . number_format($totalOverheadCostUsed, 2) . "</td>
                        </tr>
                    </table>

                    <hr style='border:none;border-top:1px solid #ddd;margin:15px 0;'>

                    <p style='font-size:14px;'>Dear Owner,</p>
                    <p style='font-size:14px;'>
                        Below is your <strong>{$slotSubtitle}</strong> inventory snapshot for <strong>{$reportDate}</strong>.
                        Sales, direct cost used, and overhead cost used are computed per shift and per product using the required formulas.
                    </p>

                    {$shiftBlocks}

                    <hr style='border:none;border-top:1px solid #ddd;margin:25px 0 15px;'>

                    <p style='font-size:14px;'>
                        Formula used:
                        <strong>Sales = QTY SOLD × SRP</strong>
                        and
                        <strong>Direct Cost Used = Direct Cost per Piece × (PO + QTY SOLD)</strong>.
                        and
                        <strong>Overhead Cost Used = Overhead Cost per Piece × (PO + QTY SOLD)</strong>.
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

    private static function resolveShiftWindowsForSlot(string $slot, string $date, bool $includeMissedFirstShift = false): array
    {
        $all = ShiftSchedule::getShiftWindowsForDate($date);
        $byKey = [];
        foreach ($all as $window) {
            $key = strtolower((string) ($window['key'] ?? ''));
            if ($key !== '') {
                $byKey[$key] = $window;
            }
        }

        if ($slot === 'am') {
            return isset($byKey['shift_a']) ? [$byKey['shift_a']] : array_slice($all, 0, 1);
        }

        if ($slot === 'pm') {
            if ($includeMissedFirstShift) {
                $windows = [];
                if (isset($byKey['shift_a'])) {
                    $windows[] = $byKey['shift_a'];
                }
                if (isset($byKey['shift_b'])) {
                    $windows[] = $byKey['shift_b'];
                }

                return !empty($windows) ? $windows : array_slice($all, 0, 2);
            }

            return isset($byKey['shift_b']) ? [$byKey['shift_b']] : array_slice($all, 1, 1);
        }

        $windows = [];
        if (isset($byKey['shift_a'])) {
            $windows[] = $byKey['shift_a'];
        }
        if (isset($byKey['shift_b'])) {
            $windows[] = $byKey['shift_b'];
        }

        return !empty($windows) ? $windows : $all;
    }

    private static function buildShiftReports(array $allItems, string $date, array $shiftWindows): array
    {
        $reports = [];
        foreach ($shiftWindows as $window) {
            $start = (string) ($window['start'] ?? '00:00:00');
            $end = (string) ($window['end'] ?? '23:59:59');
            $label = (string) ($window['label'] ?? 'Shift');

            $soldByProduct = self::getQtySoldByProductForShift($date, $start, $end);

            $bakery = [];
            $grocery = [];
            $drinks = [];

            foreach ($allItems as $item) {
                $category = strtolower((string) ($item['category'] ?? ''));
                if (!in_array($category, ['bakery', 'grocery', 'drinks'], true)) {
                    continue;
                }

                $productId = intval($item['product_id'] ?? 0);
                $qtySold = intval($soldByProduct[$productId] ?? 0);
                $po = intval($item['pull_out_quantity'] ?? 0);
                $srp = self::resolveSrp($item);
                $sales = $qtySold * $srp;
                $directCostPerPiece = self::resolveDirectCostPerPiece($item);
                $overheadCostPerPiece = self::resolveOverheadCostPerPiece($item);
                $directCostUsed = $directCostPerPiece * ($po + $qtySold);
                $overheadCostUsed = $overheadCostPerPiece * ($po + $qtySold);

                $row = [
                    'product_name' => (string) ($item['product_name'] ?? 'Unknown'),
                    'srp' => $srp,
                    'beg' => intval($item['beginning_stock'] ?? 0),
                    'po' => $po,
                    'end' => intval($item['ending_stock'] ?? 0),
                    'qty_sold' => $qtySold,
                    'sales' => $sales,
                    'direct_cost_used' => $directCostUsed,
                    'raw_materials_used' => $directCostUsed,
                    'overhead_cost_used' => $overheadCostUsed,
                ];

                if ($category === 'bakery') {
                    $bakery[] = $row;
                } elseif ($category === 'grocery') {
                    $grocery[] = $row;
                } else {
                    $drinks[] = $row;
                }
            }

            $reports[] = [
                'label' => $label,
                'time_range' => $start . ' - ' . $end,
                'bakery' => $bakery,
                'grocery' => $grocery,
                'drinks' => $drinks,
                'totals' => [
                    'products' => count($bakery) + count($grocery) + count($drinks),
                    'sales' => self::sumRows($bakery, 'sales') + self::sumRows($grocery, 'sales') + self::sumRows($drinks, 'sales'),
                    'direct_cost_used' => self::sumRows($bakery, 'direct_cost_used') + self::sumRows($grocery, 'direct_cost_used') + self::sumRows($drinks, 'direct_cost_used'),
                    'raw_materials_used' => self::sumRows($bakery, 'direct_cost_used') + self::sumRows($grocery, 'direct_cost_used') + self::sumRows($drinks, 'direct_cost_used'),
                    'overhead_cost_used' => self::sumRows($bakery, 'overhead_cost_used') + self::sumRows($grocery, 'overhead_cost_used') + self::sumRows($drinks, 'overhead_cost_used'),
                ],
            ];
        }

        return $reports;
    }

    private static function getQtySoldByProductForShift(string $date, string $start, string $end): array
    {
        $db = \Config\Database::connect();
        $rows = $db->query(
            "SELECT dsi.product_id, SUM(t.quantity_sold) AS qty_sold
             FROM transactions t
             JOIN orders o ON o.order_id = t.order_id
             JOIN daily_stock_items dsi ON dsi.item_id = t.item_id
             WHERE t.date_created = ?
               AND o.time_created >= ?
               AND o.time_created <= ?
               AND o.voided_at IS NULL
             GROUP BY dsi.product_id",
            [$date, $start, $end]
        )->getResultArray();

        $map = [];
        foreach ($rows as $row) {
            $map[intval($row['product_id'] ?? 0)] = intval($row['qty_sold'] ?? 0);
        }

        return $map;
    }

    private static function resolveSrp(array $item): float
    {
        $category = strtolower((string) ($item['category'] ?? ''));
        $sellingPricePerPiece = floatval($item['selling_price_per_piece'] ?? 0);
        $sellingPrice = floatval($item['selling_price'] ?? 0);

        if ($category === 'bakery' && $sellingPricePerPiece > 0) {
            return $sellingPricePerPiece;
        }

        return $sellingPrice;
    }

    private static function resolveDirectCostPerPiece(array $item): float
    {
        $directCost = floatval($item['direct_cost'] ?? 0);
        $piecesPerYield = intval($item['pieces_per_yield'] ?? 0);

        if ($directCost <= 0) {
            return 0.0;
        }

        if ($piecesPerYield > 0) {
            return $directCost / $piecesPerYield;
        }

        return $directCost;
    }

    private static function resolveOverheadCostPerPiece(array $item): float
    {
        $overheadCostAmount = floatval($item['overhead_cost_amount'] ?? 0);
        $piecesPerYield = intval($item['pieces_per_yield'] ?? 0);

        if ($overheadCostAmount <= 0) {
            return 0.0;
        }

        if ($piecesPerYield > 0) {
            return $overheadCostAmount / $piecesPerYield;
        }

        return $overheadCostAmount;
    }

    private static function sumRows(array $rows, string $key): float
    {
        $sum = 0.0;
        foreach ($rows as $row) {
            $sum += floatval($row[$key] ?? 0);
        }

        return $sum;
    }

    private static function buildCategoryRows(array $rows, bool $showBegPoEnd): array
    {
        $htmlRows = '';
        $totalSales = 0.0;
        $totalDirectUsed = 0.0;
        $totalOverheadUsed = 0.0;

        foreach ($rows as $row) {
            $name = htmlspecialchars((string) ($row['product_name'] ?? 'Unknown'));
            $srp = floatval($row['srp'] ?? 0);
            $beg = intval($row['beg'] ?? 0);
            $po = intval($row['po'] ?? 0);
            $end = intval($row['end'] ?? 0);
            $qtySold = intval($row['qty_sold'] ?? 0);
            $sales = floatval($row['sales'] ?? 0);
            $directUsed = floatval($row['direct_cost_used'] ?? ($row['raw_materials_used'] ?? 0));
            $overheadUsed = floatval($row['overhead_cost_used'] ?? 0);

            $totalSales += $sales;
            $totalDirectUsed += $directUsed;
            $totalOverheadUsed += $overheadUsed;

            $htmlRows .= "<tr>
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;'>{$name}</td>
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:right;'>₱" . number_format($srp, 2) . "</td>";

            if ($showBegPoEnd) {
                $htmlRows .= "
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$beg}</td>
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$po}</td>
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$end}</td>";
            } else {
                $htmlRows .= "
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$po}</td>";
            }

            $htmlRows .= "
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$qtySold}</td>
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:right;'>₱" . number_format($sales, 2) . "</td>
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:right;'>₱" . number_format($directUsed, 2) . "</td>
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:right;'>₱" . number_format($overheadUsed, 2) . "</td>
            </tr>";
        }

        return [
            'rows' => $htmlRows,
            'total_sales' => $totalSales,
            'total_direct_used' => $totalDirectUsed,
            'total_overhead_used' => $totalOverheadUsed,
        ];
    }

    private static function buildCategoryTable(array $categoryData, bool $showBegPoEnd): string
    {
        $rowsHtml = (string) ($categoryData['rows'] ?? '');
        $totalSales = floatval($categoryData['total_sales'] ?? 0);
        $totalDirectUsed = floatval($categoryData['total_direct_used'] ?? 0);
        $totalOverheadUsed = floatval($categoryData['total_overhead_used'] ?? 0);

        $headers = "
            <th style='padding:8px;text-align:left;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>ITEMS</th>
            <th style='padding:8px;text-align:right;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>SRP</th>";

        if ($showBegPoEnd) {
            $headers .= "
            <th style='padding:8px;text-align:center;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>BEG</th>
            <th style='padding:8px;text-align:center;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>PO</th>
            <th style='padding:8px;text-align:center;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>END</th>";
        } else {
            $headers .= "
            <th style='padding:8px;text-align:center;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>PO</th>";
        }

        $headers .= "
            <th style='padding:8px;text-align:center;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>QTY SOLD</th>
            <th style='padding:8px;text-align:right;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>SALES</th>
            <th style='padding:8px;text-align:right;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>DIRECT COST USED</th>
            <th style='padding:8px;text-align:right;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>OVERHEAD COST USED</th>";

        if (trim($rowsHtml) === '') {
            $colspan = $showBegPoEnd ? 9 : 7;
            $rowsHtml = "<tr><td colspan='{$colspan}' style='padding:10px;font-size:12px;color:#6b7280;text-align:center;border-bottom:1px solid #e5e7eb;'>No items</td></tr>";
        }

        $colspanForTotalLabel = $showBegPoEnd ? 6 : 4;

        return "
            <div style='overflow-x:auto;'>
                <table style='width:100%;border-collapse:collapse;background:#fff;border:1px solid #e5e7eb;'>
                    <thead><tr>{$headers}</tr></thead>
                    <tbody>
                        {$rowsHtml}
                        <tr>
                            <td colspan='{$colspanForTotalLabel}' style='padding:8px;font-size:12px;font-weight:700;text-align:right;background:#fef9c3;border-top:1px solid #e5e7eb;'>TOTAL:</td>
                            <td style='padding:8px;font-size:12px;font-weight:700;text-align:right;background:#fef9c3;border-top:1px solid #e5e7eb;'>₱" . number_format($totalSales, 2) . "</td>
                            <td style='padding:8px;font-size:12px;font-weight:700;text-align:right;background:#fef9c3;border-top:1px solid #e5e7eb;'>₱" . number_format($totalDirectUsed, 2) . "</td>
                            <td style='padding:8px;font-size:12px;font-weight:700;text-align:right;background:#fef9c3;border-top:1px solid #e5e7eb;'>₱" . number_format($totalOverheadUsed, 2) . "</td>
                        </tr>
                    </tbody>
                </table>
            </div>";
    }

    private static function resolveManualSlotForNow(): string
    {
        return ((int) date('G') >= self::SLOTS['pm']['start_h']) ? 'pm' : 'am';
    }

    private static function normalizeSlot(?string $slot): ?string
    {
        if ($slot === null) {
            return null;
        }

        $normalized = strtolower(trim($slot));
        if (in_array($normalized, ['am', 'morning', 'first', 'first_shift', 'shift_a'], true)) {
            return 'am';
        }

        if (in_array($normalized, ['pm', 'afternoon', 'second', 'second_shift', 'shift_b'], true)) {
            return 'pm';
        }

        return null;
    }

    private static function getShiftFlagFilePath(string $date, string $slot): string
    {
        return WRITEPATH . "inventory_report_sent_{$date}_{$slot}.flag";
    }

    private static function hasShiftBeenReported(string $date, string $slot): bool
    {
        return file_exists(self::getShiftFlagFilePath($date, $slot));
    }

    private static function markShiftReported(string $date, string $slot): void
    {
        @file_put_contents(self::getShiftFlagFilePath($date, $slot), date('Y-m-d H:i:s'));
    }
}