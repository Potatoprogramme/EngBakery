<?php

namespace App\Libraries;

use App\Models\DailyStockModel;
use App\Models\DailyStockItemsModel;
use App\Models\UsersModel;
use App\Libraries\ShiftSchedule;
use App\Libraries\OwnerNotificationPreferences;

/**
 * AutoReportScheduler
 *
 * Opportunistic pseudo-cron scheduler. Attached to CodeIgniter's `pre_system`
 * event in Config/Events.php so it is evaluated on every incoming web request.
 *
 * Design principles
 * ─────────────────
 *  • Idempotent   – database run markers guarantee each slot fires once/day.
 *  • Race-safe    – DB named locks prevent concurrent duplicate sends.
 *  • Silent       – all failures are logged; they never surface to the user.
 *  • Zero deps    – reuses the same Services::email() + UsersModel pattern
 *                   used by LowStockNotifier and DailyRemittanceReport.
 *
 * Schedule
 * ────────────────────────────────────────────────────────────────────────────
 *  AM slot : 15:00 – 15:59  →  afternoon inventory snapshot
 *  PM slot : 20:00 – 20:59  →  end-of-business-day  inventory snapshot
 *
 * DB table (auto-created)
 * ───────────────────────
 *  inventory_report_runs(report_date, slot, sent_at)
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

    /**
     * DB table for scheduled report idempotency.
     */
    private const RUNS_TABLE = 'inventory_report_runs';

    // =========================================================================
    //  PUBLIC API
    // =========================================================================

    /**
     * Entry point — called on every web request via Events.php.
     * Iterates over scheduled slots and fires reports that are due and unsent.
     */
    public static function runDueJobs(): void
    {
        $nowH  = (int) date('G');   // 0–23, no leading zero
        $today = date('Y-m-d');

        self::ensureRunsTableExists();

        foreach (self::SLOTS as $slot => $window) {

            // ── Is the current hour inside this slot's fire window? ───────
            if ($nowH < $window['start_h'] || $nowH >= $window['end_h']) {
                continue;
            }

            // ── Fast path: already sent today (DB marker) ────────────────
            if (self::hasShiftBeenReported($today, $slot)) {
                continue;
            }

            $lockName = self::buildDbLockName($today, $slot);
            if (!self::acquireDbLock($lockName)) {
                // Another request/process holds the lock and will process this slot.
                continue;
            }

            try {
                // ── Double-check inside lock to avoid race duplicates ─────
                if (self::hasShiftBeenReported($today, $slot)) {
                    continue;
                }

                log_message('info', "AutoReportScheduler: Firing [{$slot}] inventory report for {$today}.");

                $sent = self::sendInventoryReport($slot, $today);

                if ($sent) {
                    self::markShiftReported($today, $slot);
                    log_message('info', "AutoReportScheduler: [{$slot}] report sent and recorded in DB for {$today}.");
                } else {
                    log_message('warning', "AutoReportScheduler: [{$slot}] report not sent (no owners, empty stock, or send failure).");
                }

            } catch (\Throwable $e) {
                log_message('error', "AutoReportScheduler [{$slot}] exception: " . $e->getMessage());
            } finally {
                self::releaseDbLock($lockName);
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

        $sent = self::sendInventoryReport($manualSlot, $targetDate);
        return $sent;
    }

    /**
     * Manual trigger for a specific inventory shift row.
     * Uses the row's time_start/time_end as the authoritative sales window.
     */
    public static function sendManualReportForInventory(int $inventoryId, ?string $resendReason = null, ?int $cashierUserId = null): array
    {
        $dailyStockModel = new DailyStockModel();
        $dailyStock = $dailyStockModel->find($inventoryId);

        if (empty($dailyStock)) {
            return [
                'success' => false,
                'message' => 'Inventory record not found.',
            ];
        }

        $date = (string) ($dailyStock['inventory_date'] ?? '');
        $shiftStart = trim((string) ($dailyStock['time_start'] ?? ''));
        $shiftEnd = trim((string) ($dailyStock['time_end'] ?? ''));

        if ($date === '' || $shiftStart === '' || $shiftEnd === '' || $shiftEnd === '00:00:00') {
            return [
                'success' => false,
                'message' => 'Shift time window is incomplete. Close the inventory before sending.',
            ];
        }

        $itemsModel = new DailyStockItemsModel();
        $allItems = $itemsModel->fetchAllStockItems($inventoryId);
        if (empty($allItems)) {
            return [
                'success' => false,
                'message' => 'No inventory products found for this shift.',
            ];
        }

        $shiftLabel = 'Shift (' . self::formatShiftTimeRange($shiftStart, $shiftEnd) . ')';
        $shiftReports = self::buildShiftReports($allItems, $date, [[
            'key' => 'inventory_' . $inventoryId,
            'label' => $shiftLabel,
            'start' => $shiftStart,
            'end' => $shiftEnd,
        ]], $cashierUserId);

        if (empty($shiftReports)) {
            return [
                'success' => false,
                'message' => 'No shift data available for inventory report.',
            ];
        }

        $usersModel = new UsersModel();
        $owners = $usersModel
            ->where('employee_type', 'owner')
            ->where('approved', 1)
            ->findAll();

        if (empty($owners)) {
            return [
                'success' => false,
                'message' => 'No approved owner recipients found.',
            ];
        }

        $isResend = !empty($dailyStock['report_sent_at']);
        $reason = trim((string) $resendReason);
        if ($reason === '') {
            $reason = 'inventory was reopened and corrected after the previous report.';
        }

        $ownerEmails = self::resolveInventoryRecipients($owners);
        log_message('info', 'AutoReportScheduler: Manual inventory send requested for inventory_id=' . $inventoryId
            . ' date=' . $date
            . ' cashier_user_id=' . intval($cashierUserId ?? 0)
            . ' recipients=' . implode(', ', $ownerEmails));

        $cashierMeta = self::resolveCashierMeta($cashierUserId);

        $slotMeta = [
            'title' => 'Inventory Shift Report',
            'subtitle' => 'Manual Shift Snapshot',
            'header_color' => '#dbeafe',
            'subject_label' => $shiftLabel,
            'cashier' => $cashierMeta,
        ];

        $subject = 'Inventory Report - ' . date('F d, Y', strtotime($date)) . ' - ' . self::formatShiftTimeRange($shiftStart, $shiftEnd);
        if ($isResend) {
            $subject .= ' [RESENT]';
        }

        $resendMeta = null;
        if ($isResend) {
            $resendMeta = [
                'reason' => $reason,
                'previous_sent_at' => (string) ($dailyStock['report_sent_at'] ?? ''),
            ];
        }

        $emailBody = self::buildEmailBody($shiftReports, 'manual', $date, $slotMeta, $resendMeta);

        try {
            $emailService = \Config\Services::email();
            $emailService->setFrom('noreply@engbakery.com', "E n' G Bakery - Karangahan");
            $emailService->setTo($ownerEmails);
            $emailService->setSubject($subject);
            $emailService->setMessage($emailBody);
            $emailService->setMailType('html');

            if (!$emailService->send()) {
                log_message('error', 'AutoReportScheduler: sendManualReportForInventory failed. Debug: '
                    . $emailService->printDebugger(['headers']));

                return [
                    'success' => false,
                    'message' => 'Email delivery failed. Please check email configuration.',
                    'recipients' => $ownerEmails,
                ];
            }

            log_message('info', 'AutoReportScheduler: Manual inventory email dispatched for inventory_id=' . $inventoryId
                . ' recipients=' . implode(', ', $ownerEmails));

            return [
                'success' => true,
                'resent' => $isResend,
                'recipients' => $ownerEmails,
                'message' => $isResend
                    ? 'Resent inventory report successfully. This version supersedes the previous email.'
                    : 'Inventory report sent successfully.',
            ];
        } catch (\Throwable $e) {
            log_message('error', 'AutoReportScheduler: sendManualReportForInventory exception: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Unexpected error while sending report.',
                'recipients' => $ownerEmails,
            ];
        }
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

        $shiftWindows = self::resolveShiftWindowsForSlot($slot, $date);
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

        $ownerEmails = self::resolveInventoryRecipients($owners);
        $slotMeta = self::resolveSlotMeta($slot, $shiftReports, $date);
        $slotLabel = $slotMeta['subject_label'] ?? null;
        $subject = $slotLabel
            ? ('📦 Inventory Report — ' . $slotLabel . ' — ' . date('F d, Y', strtotime($date)))
            : ('📦 Inventory Report — ' . date('F d, Y', strtotime($date)));

        $emailBody = self::buildEmailBody($shiftReports, $slot, $date, $slotMeta);

        // ── 4. Send via the configured email service ─────────────────────────
        try {
            $emailService = \Config\Services::email();
            $emailService->setFrom('noreply@engbakery.com', "E n' G Bakery - Karangahan");
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
    private static function buildEmailBody(array $shiftReports, string $slot, string $date, array $slotMeta, ?array $resendMeta = null): string
    {
        $reportDate   = date('F d, Y', strtotime($date));
        $reportTime   = date('h:i A');
        $reportRef    = 'INV-' . strtoupper($slot) . '-' . date('Ymd-His');
        $slotTitle = $slotMeta['title'] ?? 'Inventory Report';
        $slotSubtitle = $slotMeta['subtitle'] ?? 'Manually Generated Snapshot';
        $headerColor = $slotMeta['header_color'] ?? '#dbeafe';

        $totalProducts = 0;
        $totalSales = 0.0;
        $totalRawMaterialsUsed = 0.0;
        $totalOverheadCostUsed = 0.0;
        foreach ($shiftReports as $report) {
            $totalProducts += intval($report['totals']['products'] ?? 0);
            $totalSales += floatval($report['totals']['sales'] ?? 0);
            $totalRawMaterialsUsed += floatval($report['totals']['raw_materials_used'] ?? 0);
            $totalOverheadCostUsed += floatval($report['totals']['overhead_cost_used'] ?? 0);
        }

        $shiftCoverageParts = [];
        foreach ($shiftReports as $report) {
            $coverageLabel = trim((string) ($report['label'] ?? 'Shift'));
            $coverageTime = trim((string) ($report['time_range'] ?? ''));
            $shiftCoverageParts[] = trim($coverageLabel . ($coverageTime !== '' ? (' (' . $coverageTime . ')') : ''));
        }
        $shiftCoverage = implode(' • ', array_filter($shiftCoverageParts));
        $shiftCoverageEscaped = htmlspecialchars($shiftCoverage !== '' ? $shiftCoverage : '—');

        $cashierMeta = is_array($slotMeta['cashier'] ?? null) ? $slotMeta['cashier'] : [];
        $cashierName = trim((string) ($cashierMeta['name'] ?? ''));
        $cashierDisplay = $cashierName !== '' ? $cashierName : 'Unknown';
        $cashierDisplayEscaped = htmlspecialchars($cashierDisplay);

        $showOverheadColumn = true;

        $shiftBlocks = '';
        foreach ($shiftReports as $report) {
            $label = htmlspecialchars((string) ($report['label'] ?? 'Shift'));
            $timeRange = htmlspecialchars((string) ($report['time_range'] ?? ''));
            $bakeryRows = self::buildCategoryRows($report['bakery'] ?? [], true, $showOverheadColumn);
            $groceryRows = self::buildCategoryRows($report['grocery'] ?? [], true, $showOverheadColumn);
            $drinksRows = self::buildCategoryRows($report['drinks'] ?? [], false, $showOverheadColumn);

            $shiftBlocks .= "
                <div style='margin-top:20px;padding:16px;background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 2px 8px rgba(15,23,42,0.04);'>
                    <div style='display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap;margin-bottom:10px;'>
                        <div style='font-size:16px;font-weight:700;color:#0f172a;'>{$label}</div>
                        <span style='font-size:11px;font-weight:600;color:#334155;background:#e2e8f0;padding:4px 8px;border-radius:999px;'>Shift Time: {$timeRange}</span>
                    </div>

                    <div style='font-size:12px;font-weight:800;letter-spacing:.04em;color:#334155;margin-bottom:6px;'>BREAD</div>
                    " . self::buildCategoryTable($bakeryRows, true, $showOverheadColumn) . "

                    <div style='font-size:12px;font-weight:800;letter-spacing:.04em;color:#334155;margin:14px 0 6px;'>GROCERY</div>
                    " . self::buildCategoryTable($groceryRows, true, $showOverheadColumn) . "

                    <div style='font-size:12px;font-weight:800;letter-spacing:.04em;color:#334155;margin:14px 0 6px;'>DRINKS</div>
                    " . self::buildCategoryTable($drinksRows, false, $showOverheadColumn) . "
                </div>";
        }

        $resendBanner = '';
        if ($resendMeta !== null) {
            $reasonEscaped = htmlspecialchars((string) ($resendMeta['reason'] ?? 'inventory correction'));
            $previousSentAtRaw = trim((string) ($resendMeta['previous_sent_at'] ?? ''));
            $previousSentAt = $previousSentAtRaw;
            if ($previousSentAtRaw !== '' && strtotime($previousSentAtRaw) !== false) {
                $previousSentAt = date('M d, Y h:i A', strtotime($previousSentAtRaw));
            }
            $previousSentAtEscaped = htmlspecialchars($previousSentAt !== '' ? $previousSentAt : 'N/A');

            $resendBanner = "
                <div style='margin:8px 0 14px;background:#fff7ed;color:#9a3412;border:1px solid #fdba74;border-radius:10px;padding:12px;font-size:13px;'>
                    <strong>RESENT VERSION:</strong> This email supersedes the previous inventory report.<br>
                    <strong>Reason:</strong> {$reasonEscaped}<br>
                    <strong>Previous Sent At:</strong> {$previousSentAtEscaped}
                </div>";
        }

        $year = date('Y');

        return "
        <html>
        <head>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.5; color: #1f2937; margin: 0; padding: 0; background:#f1f5f9; }
                .container { max-width: 780px; margin: 0 auto; padding: 20px; }
                .header { background-color: {$headerColor}; color: #111827; padding: 26px; text-align: left; border-radius: 14px 14px 0 0; border-bottom: 3px solid #16a34a; }
                .content { background-color: #f8fafc; padding: 24px; border: 1px solid #e2e8f0; border-top:none; border-radius: 0 0 14px 14px; }
                table { width: 100%; border-collapse: collapse; }
                .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #64748b; }
            </style>
        </head>
        <body>
            <div class='container'>

                <!-- Header -->
                <div class='header'>
                    <h1 style='margin:0;font-size:24px;line-height:1.2;'>{$slotTitle}</h1>
                    <p style='margin:8px 0 0;font-size:14px;opacity:.92;color:#111827;'>E n' G Bakery - Karangahan &mdash; {$slotSubtitle}</p>
                </div>

                <div class='content'>
                    <table style='margin-bottom:16px;'>
                        <tr>
                            <td style='width:33.33%;padding:6px;'>
                                <div style='background:#fee2e2;color:#111827;border:1px solid #fca5a5;border-radius:10px;padding:12px;'>
                                    <div style='font-size:11px;opacity:.85;'>OVERALL TOTAL SALES</div>
                                    <div style='font-size:20px;font-weight:800;margin-top:4px;'>₱" . number_format($totalSales, 2) . "</div>
                                </div>
                            </td>
                            <td style='width:33.33%;padding:6px;'>
                                <div style='background:#ecfeff;color:#0f766e;border:1px solid #99f6e4;border-radius:10px;padding:12px;'>
                                    <div style='font-size:11px;'>TOTAL RAW MATERIALS USED</div>
                                    <div style='font-size:18px;font-weight:700;margin-top:4px;'>₱" . number_format($totalRawMaterialsUsed, 2) . "</div>
                                </div>
                            </td>
                            <td style='width:33.33%;padding:6px;'>
                                <div style='background:#ecfdf5;color:#166534;border:1px solid #bbf7d0;border-radius:10px;padding:12px;'>
                                    <div style='font-size:11px;'>TOTAL PRODUCT ROWS</div>
                                    <div style='font-size:18px;font-weight:700;margin-top:4px;'>" . intval($totalProducts) . "</div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <div style='margin:4px 6px 10px;background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;border-radius:10px;padding:10px 12px;font-size:12px;'>
                        Total Overhead Cost Used: <strong>₱" . number_format($totalOverheadCostUsed, 2) . "</strong>
                    </div>

                    <!-- Report Metadata -->
                    <table style='margin-bottom:20px;background:#ffffff;border:1px solid #e2e8f0;border-radius:10px;'>
                        <tr>
                            <td style='padding:8px 12px;font-size:13px;color:#64748b;width:160px;'><strong>Report Reference:</strong></td>
                            <td style='padding:8px 12px;font-size:13px;color:#0f172a;'>{$reportRef}</td>
                        </tr>
                        <tr>
                            <td style='padding:8px 12px;font-size:13px;color:#64748b;'><strong>Report Date:</strong></td>
                            <td style='padding:8px 12px;font-size:13px;color:#0f172a;'>{$reportDate}</td>
                        </tr>
                        <tr>
                            <td style='padding:8px 12px;font-size:13px;color:#64748b;'><strong>Generated At:</strong></td>
                            <td style='padding:8px 12px;font-size:13px;color:#0f172a;'>{$reportTime}</td>
                        </tr>
                        <tr>
                            <td style='padding:8px 12px;font-size:13px;color:#64748b;'><strong>Shift Time:</strong></td>
                            <td style='padding:8px 12px;font-size:13px;color:#0f172a;'>{$shiftCoverageEscaped}</td>
                        </tr>
                        <tr>
                            <td style='padding:8px 12px;font-size:13px;color:#64748b;'><strong>Cashier:</strong></td>
                            <td style='padding:8px 12px;font-size:13px;color:#0f172a;'>{$cashierDisplayEscaped}</td>
                        </tr>
                        <tr>
                            <td style='padding:8px 12px;font-size:13px;color:#64748b;'><strong>Total Product Rows:</strong></td>
                            <td style='padding:8px 12px;font-size:13px;color:#0f172a;'>{$totalProducts}</td>
                        </tr>
                        <tr>
                            <td style='padding:8px 12px;font-size:13px;color:#64748b;'><strong>Overall Total Sales:</strong></td>
                            <td style='padding:8px 12px;font-size:13px;color:#0f172a;'>₱" . number_format($totalSales, 2) . "</td>
                        </tr>
                        <tr>
                            <td style='padding:8px 12px;font-size:13px;color:#64748b;'><strong>Total Raw Materials Used:</strong></td>
                            <td style='padding:8px 12px;font-size:13px;color:#0f172a;'>₱" . number_format($totalRawMaterialsUsed, 2) . "</td>
                        </tr>
                    </table>

                    <hr style='border:none;border-top:1px solid #dbeafe;margin:16px 0;'>

                    <p style='font-size:14px;'>Dear Owner,</p>
                    <p style='font-size:14px;'>
                        Below is your <strong>{$slotSubtitle}</strong> inventory snapshot for <strong>{$reportDate}</strong>.
                        Sales and raw materials used are computed per shift and per product using the required formulas.
                    </p>

                    {$resendBanner}

                    {$shiftBlocks}

                    <hr style='border:none;border-top:1px solid #dbeafe;margin:24px 0 14px;'>

                    <div style='font-size:13px;background:#fffbeb;border:1px solid #fde68a;color:#78350f;padding:12px;border-radius:10px;'>
                        Formula used:
                        <strong>Sales = QTY SOLD × SRP</strong>
                        and
                        <strong>Raw Materials Used = Raw Material Cost per Piece × (PO + QTY SOLD)</strong>.
                        and <strong>Overhead Cost Used = Overhead Cost per Piece × (PO + QTY SOLD)</strong>.
                        For the full inventory management interface, visit the <strong>Inventory</strong> page in the system.
                    </div>

                    <p style='font-size:14px;margin-top:20px;'>
                        Respectfully,<br>
                        <strong>E n' G Bakery - Karangahan Inventory System</strong>
                    </p>

                </div>

                <div class='footer'>
                    <p>&copy; {$year} E n' G Bakery - Karangahan. All rights reserved.</p>
                    <p>This is a system-generated report. Please do not reply to this email.</p>
                </div>

            </div>
        </body>
        </html>";
    }

    private static function resolveShiftWindowsForSlot(string $slot, string $date): array
    {
        $all = ShiftSchedule::getShiftWindowsForDate($date);
        $byKey = [];
        foreach ($all as $window) {
            $key = strtolower((string) ($window['key'] ?? ''));
            if ($key !== '') {
                $byKey[$key] = $window;
            }
        }

        $slotKey = strtolower(trim($slot));
        if (isset($byKey[$slotKey])) {
            return [$byKey[$slotKey]];
        }

        if ($slot === 'am') {
            return isset($byKey['shift_a']) ? [$byKey['shift_a']] : array_slice($all, 0, 1);
        }

        if ($slot === 'pm') {
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

    private static function buildShiftReports(array $allItems, string $date, array $shiftWindows, ?int $cashierUserId = null): array
    {
        $reports = [];
        foreach ($shiftWindows as $window) {
            $start = (string) ($window['start'] ?? '00:00:00');
            $end = (string) ($window['end'] ?? '23:59:59');
            $label = (string) ($window['label'] ?? 'Shift');

            $soldByProduct = self::getQtySoldByProductForShift($date, $start, $end, $cashierUserId);

            $bakery = [];
            $grocery = [];
            $drinks = [];

            foreach ($allItems as $item) {
                $isEnabled = intval($item['is_enabled'] ?? 1) === 1;
                $isProductDisabled = intval($item['is_disabled'] ?? 0) === 1;
                if (!$isEnabled || $isProductDisabled) {
                    continue;
                }

                $category = strtolower((string) ($item['category'] ?? ''));
                if (!in_array($category, ['bakery', 'grocery', 'drinks'], true)) {
                    continue;
                }

                $productId = intval($item['product_id'] ?? 0);
                $qtySoldFromOrders = intval($soldByProduct[$productId] ?? 0);
                $beg = intval($item['beginning_stock'] ?? 0);
                $po = intval($item['pull_out_quantity'] ?? 0);
                $end = intval($item['ending_stock'] ?? 0);
                $inventoryQtySold = max(0, $beg - $po - $end);

                // Keep report qty sold consistent with the inventory screen for bakery/grocery.
                $qtySold = $qtySoldFromOrders;
                if (in_array($category, ['bakery', 'grocery'], true)) {
                    $qtySold = max($qtySoldFromOrders, $inventoryQtySold);
                }

                $srp = self::resolveSrp($item);
                $sales = $qtySold * $srp;
                $directCostPerPiece = self::resolveDirectCostPerPiece($item);
                $rawMaterialsUsed = $directCostPerPiece * ($po + $qtySold);
                $overheadCostPerPiece = self::resolveOverheadCostPerPiece($item);
                $overheadCostUsed = $overheadCostPerPiece * ($po + $qtySold);

                $row = [
                    'product_name' => (string) ($item['product_name'] ?? 'Unknown'),
                    'srp' => $srp,
                    'beg' => $beg,
                    'po' => $po,
                    'end' => $end,
                    'qty_sold' => $qtySold,
                    'sales' => $sales,
                    'raw_materials_used' => $rawMaterialsUsed,
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
                'time_range' => self::formatShiftTimeRange($start, $end),
                'bakery' => $bakery,
                'grocery' => $grocery,
                'drinks' => $drinks,
                'totals' => [
                    'products' => count($bakery) + count($grocery) + count($drinks),
                    'sales' => self::sumRows($bakery, 'sales') + self::sumRows($grocery, 'sales') + self::sumRows($drinks, 'sales'),
                    'raw_materials_used' => self::sumRows($bakery, 'raw_materials_used') + self::sumRows($grocery, 'raw_materials_used') + self::sumRows($drinks, 'raw_materials_used'),
                    'overhead_cost_used' => self::sumRows($bakery, 'overhead_cost_used') + self::sumRows($grocery, 'overhead_cost_used') + self::sumRows($drinks, 'overhead_cost_used'),
                ],
            ];
        }

        return $reports;
    }

    private static function getQtySoldByProductForShift(string $date, string $start, string $end, ?int $cashierUserId = null): array
    {
        $db = \Config\Database::connect();
        $sql = "SELECT dsi.product_id, SUM(t.quantity_sold) AS qty_sold
             FROM transactions t
             JOIN orders o ON o.order_id = t.order_id
             JOIN daily_stock_items dsi ON dsi.item_id = t.item_id
             WHERE t.date_created = ?
               AND o.time_created >= ?
               AND o.time_created <= ?
               AND o.voided_at IS NULL";

        $params = [$date, $start, $end];

        if (!empty($cashierUserId) && $db->fieldExists('cashier_id', 'orders')) {
            $sql .= " AND o.cashier_id = ?";
            $params[] = intval($cashierUserId);
        }

        $sql .= " GROUP BY dsi.product_id";

        $rows = $db->query($sql, $params)->getResultArray();

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
        $piecesPerBatch = self::resolvePiecesPerBatch($item);

        if ($directCost <= 0) {
            return 0.0;
        }

        if ($piecesPerBatch > 0) {
            return $directCost / $piecesPerBatch;
        }

        return $directCost;
    }

    private static function resolveOverheadCostPerPiece(array $item): float
    {
        $overheadCostAmount = floatval($item['overhead_cost_amount'] ?? 0);
        $piecesPerBatch = self::resolvePiecesPerBatch($item);

        if ($overheadCostAmount <= 0) {
            return 0.0;
        }

        if ($piecesPerBatch > 0) {
            return $overheadCostAmount / $piecesPerBatch;
        }

        return $overheadCostAmount;
    }

    private static function formatShiftTimeRange(string $start, string $end): string
    {
        $startTs = strtotime($start);
        $endTs = strtotime($end);

        if ($startTs === false || $endTs === false) {
            return $start . ' - ' . $end;
        }

        return date('h:i A', $startTs) . ' - ' . date('h:i A', $endTs);
    }

    private static function sumRows(array $rows, string $key): float
    {
        $sum = 0.0;
        foreach ($rows as $row) {
            $sum += floatval($row[$key] ?? 0);
        }

        return $sum;
    }

    private static function buildCategoryRows(array $rows, bool $showBegPoEnd, bool $showOverheadColumn): array
    {
        $htmlRows = '';
        $totalSales = 0.0;
        $totalRawUsed = 0.0;
        $totalOverheadUsed = 0.0;

        foreach ($rows as $row) {
            $name = htmlspecialchars((string) ($row['product_name'] ?? 'Unknown'));
            $srp = floatval($row['srp'] ?? 0);
            $beg = intval($row['beg'] ?? 0);
            $po = intval($row['po'] ?? 0);
            $end = intval($row['end'] ?? 0);
            $qtySold = intval($row['qty_sold'] ?? 0);
            $sales = floatval($row['sales'] ?? 0);
            $rawUsed = floatval($row['raw_materials_used'] ?? 0);
            $overheadUsed = floatval($row['overhead_cost_used'] ?? 0);

            $totalSales += $sales;
            $totalRawUsed += $rawUsed;
            $totalOverheadUsed += $overheadUsed;

            $htmlRows .= "<tr>
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;'>{$name}</td>
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:right;'>₱" . number_format($srp, 2) . "</td>";

            if ($showBegPoEnd) {
                $htmlRows .= "
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$beg}</td>
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$po}</td>
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$end}</td>";
            }

            $htmlRows .= "
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$qtySold}</td>
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:right;'>₱" . number_format($sales, 2) . "</td>
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:right;'>₱" . number_format($rawUsed, 2) . "</td>";

            if ($showOverheadColumn) {
                $htmlRows .= "
                <td style='padding:8px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:right;'>₱" . number_format($overheadUsed, 2) . "</td>";
            }

            $htmlRows .= "
            </tr>";
        }

        return [
            'rows' => $htmlRows,
            'total_sales' => $totalSales,
            'total_raw_used' => $totalRawUsed,
            'total_overhead_used' => $totalOverheadUsed,
        ];
    }

    private static function buildCategoryTable(array $categoryData, bool $showBegPoEnd, bool $showOverheadColumn): string
    {
        $rowsHtml = (string) ($categoryData['rows'] ?? '');
        $totalSales = floatval($categoryData['total_sales'] ?? 0);
        $totalRawUsed = floatval($categoryData['total_raw_used'] ?? 0);
        $totalOverheadUsed = floatval($categoryData['total_overhead_used'] ?? 0);

        $headers = "
            <th style='padding:8px;text-align:left;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>ITEMS</th>
            <th style='padding:8px;text-align:right;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>SRP</th>";

        if ($showBegPoEnd) {
            $headers .= "
            <th style='padding:8px;text-align:center;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>BEG</th>
            <th style='padding:8px;text-align:center;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>PO</th>
            <th style='padding:8px;text-align:center;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>END</th>";
        }

        $headers .= "
            <th style='padding:8px;text-align:center;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>QTY SOLD</th>
            <th style='padding:8px;text-align:right;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>SALES</th>
            <th style='padding:8px;text-align:right;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>RAW MATERIALS USED</th>";

        if ($showOverheadColumn) {
            $headers .= "
            <th style='padding:8px;text-align:right;font-size:11px;border-bottom:1px solid #d1d5db;background:#fef9c3;'>OVERHEAD COST USED</th>";
        }

        if (trim($rowsHtml) === '') {
            $baseColspan = $showBegPoEnd ? 8 : 5;
            $colspan = $showOverheadColumn ? $baseColspan + 1 : $baseColspan;
            $rowsHtml = "<tr><td colspan='{$colspan}' style='padding:10px;font-size:12px;color:#6b7280;text-align:center;border-bottom:1px solid #e5e7eb;'>No items</td></tr>";
        }

        $colspanForTotalLabel = $showBegPoEnd ? 6 : 3;
        $totalCells = "
                            <td style='padding:8px;font-size:12px;font-weight:700;text-align:right;background:#fef9c3;border-top:1px solid #e5e7eb;'>₱" . number_format($totalSales, 2) . "</td>
                            <td style='padding:8px;font-size:12px;font-weight:700;text-align:right;background:#fef9c3;border-top:1px solid #e5e7eb;'>₱" . number_format($totalRawUsed, 2) . "</td>";

        if ($showOverheadColumn) {
            $totalCells .= "
                            <td style='padding:8px;font-size:12px;font-weight:700;text-align:right;background:#fef9c3;border-top:1px solid #e5e7eb;'>₱" . number_format($totalOverheadUsed, 2) . "</td>";
        }

        return "
            <div style='overflow-x:auto;'>
                <table style='width:100%;border-collapse:collapse;background:#fff;border:1px solid #e5e7eb;'>
                    <thead><tr>{$headers}</tr></thead>
                    <tbody>
                        {$rowsHtml}
                        <tr>
                            <td colspan='{$colspanForTotalLabel}' style='padding:8px;font-size:12px;font-weight:700;text-align:right;background:#fef9c3;border-top:1px solid #e5e7eb;'>TOTAL:</td>
                            {$totalCells}
                        </tr>
                    </tbody>
                </table>
            </div>";
    }

    private static function resolveManualSlotForNow(): string
    {
        $date = date('Y-m-d');
        $now = date('H:i:s');
        $windows = ShiftSchedule::getShiftWindowsForDate($date);

        foreach ($windows as $window) {
            $start = (string) ($window['start'] ?? '00:00:00');
            $end = (string) ($window['end'] ?? '23:59:59');
            if ($now >= $start && $now <= $end) {
                return strtolower((string) ($window['key'] ?? 'shift_a'));
            }
        }

        return 'shift_a';
    }

    private static function normalizeSlot(?string $slot): ?string
    {
        if ($slot === null) {
            return null;
        }

        $normalized = strtolower(trim($slot));
        if (in_array($normalized, ['shift_a', 'shift_b', 'shift_c', 'shift_d'], true)) {
            return $normalized;
        }

        if (in_array($normalized, ['am', 'morning', 'first', 'first_shift'], true)) {
            return 'shift_a';
        }

        if (in_array($normalized, ['pm', 'afternoon', 'second', 'second_shift'], true)) {
            return 'shift_b';
        }

        return null;
    }

    private static function resolveSlotMeta(string $slot, array $shiftReports, string $date): array
    {
        if ($slot === 'am') {
            return [
                'title' => 'Morning Shift Inventory Report',
                'subtitle' => 'Morning Shift Snapshot',
                'header_color' => '#dbeafe',
                'subject_label' => 'Morning Shift',
            ];
        }

        if ($slot === 'pm') {
            return [
                'title' => 'Afternoon Shift Inventory Report',
                'subtitle' => 'Afternoon Shift Snapshot',
                'header_color' => '#dbeafe',
                'subject_label' => 'Afternoon Shift',
            ];
        }

        $label = '';
        $windows = ShiftSchedule::getShiftWindowsForDate($date);
        foreach ($windows as $window) {
            if (strtolower((string) ($window['key'] ?? '')) === strtolower($slot)) {
                $label = (string) ($window['label'] ?? '');
                break;
            }
        }

        if ($label === '' && !empty($shiftReports)) {
            $label = (string) ($shiftReports[0]['label'] ?? 'Shift');
        }

        $label = $label !== '' ? $label : 'Inventory Report';

        return [
            'title' => $label . ' Inventory Report',
            'subtitle' => $label . ' Snapshot',
            'header_color' => '#dbeafe',
            'subject_label' => $label,
        ];
    }

    private static function hasShiftBeenReported(string $date, string $slot): bool
    {
        self::ensureRunsTableExists();
        $db = \Config\Database::connect();
        $builder = $db->table(self::RUNS_TABLE);

        return $builder
            ->where('report_date', $date)
            ->where('slot', $slot)
            ->countAllResults() > 0;
    }

    private static function markShiftReported(string $date, string $slot): void
    {
        self::ensureRunsTableExists();
        $db = \Config\Database::connect();

        $db->query(
            'INSERT INTO ' . self::RUNS_TABLE . ' (report_date, slot, sent_at) VALUES (?, ?, ?) '
                . 'ON DUPLICATE KEY UPDATE sent_at = VALUES(sent_at)',
            [$date, $slot, date('Y-m-d H:i:s')]
        );
    }

    private static function ensureRunsTableExists(): void
    {
        static $ensured = false;
        if ($ensured) {
            return;
        }

        $db = \Config\Database::connect();
        $table = self::RUNS_TABLE;

        if (!$db->tableExists($table)) {
            $db->query(
                "CREATE TABLE IF NOT EXISTS {$table} ("
                    . 'run_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,'
                    . 'report_date DATE NOT NULL,'
                    . 'slot VARCHAR(32) NOT NULL,'
                    . 'sent_at DATETIME NOT NULL,'
                    . 'created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,'
                    . 'PRIMARY KEY (run_id),'
                    . 'UNIQUE KEY uq_inventory_report_runs_date_slot (report_date, slot)'
                    . ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci'
            );
        }

        $ensured = true;
    }

    private static function buildDbLockName(string $date, string $slot): string
    {
        return 'engbakery:inventory_report:' . $date . ':' . $slot;
    }

    private static function acquireDbLock(string $lockName): bool
    {
        try {
            $db = \Config\Database::connect();
            $row = $db->query('SELECT GET_LOCK(?, 0) AS lock_status', [$lockName])->getRowArray();
            return intval($row['lock_status'] ?? 0) === 1;
        } catch (\Throwable $e) {
            log_message('error', 'AutoReportScheduler: failed to acquire DB lock [' . $lockName . ']: ' . $e->getMessage());
            return false;
        }
    }

    private static function releaseDbLock(string $lockName): void
    {
        try {
            $db = \Config\Database::connect();
            $db->query('SELECT RELEASE_LOCK(?)', [$lockName]);
        } catch (\Throwable $e) {
            log_message('error', 'AutoReportScheduler: failed to release DB lock [' . $lockName . ']: ' . $e->getMessage());
        }
    }

    private static function resolvePiecesPerBatch(array $item): int
    {
        $category = strtolower((string) ($item['category'] ?? ''));
        if (in_array($category, ['drinks', 'grocery'], true)) {
            return 1;
        }

        $traysPerYield = intval($item['trays_per_yield'] ?? 0);
        $piecesPerYield = intval($item['pieces_per_yield'] ?? 0);

        if ($traysPerYield > 0 && $piecesPerYield > 0) {
            return $traysPerYield * $piecesPerYield;
        }

        if ($piecesPerYield > 0) {
            return $piecesPerYield;
        }

        return 1;
    }

    /**
     * @param array<int, array<string, mixed>> $owners
     * @return array<int, string>
     */
    private static function resolveInventoryRecipients(array $owners): array
    {
        return OwnerNotificationPreferences::resolveEmailsForType($owners, OwnerNotificationPreferences::TYPE_INVENTORY);
    }

    /**
     * Resolve cashier metadata for report header labeling.
     */
    private static function resolveCashierMeta(?int $cashierUserId): array
    {
        $cashierId = intval($cashierUserId ?? 0);
        if ($cashierId <= 0) {
            return [
                'user_id' => null,
                'name' => 'Unknown',
            ];
        }

        $usersModel = new UsersModel();
        $cashier = $usersModel->find($cashierId);
        if (empty($cashier)) {
            return [
                'user_id' => $cashierId,
                'name' => 'Unknown',
            ];
        }

        $first = trim((string) ($cashier['firstname'] ?? ''));
        $middle = trim((string) ($cashier['middlename'] ?? ''));
        $last = trim((string) ($cashier['lastname'] ?? ''));
        $fullName = trim($first . ' ' . $middle . ' ' . $last);

        return [
            'user_id' => $cashierId,
            'name' => $fullName !== '' ? $fullName : 'Unknown',
        ];
    }
}