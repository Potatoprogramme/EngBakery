<?php

namespace App\Libraries;

use App\Models\RawMaterialStockModel;
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
 *  AM slot : 03:00 – 03:59  →  start-of-production inventory snapshot
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
        'am' => ['start_h' => 3,  'end_h' => 4],   // 03:00 – 03:59
        'pm' => ['start_h' => 20, 'end_h' => 21],  // 20:00 – 20:59
    ];

    /**
     * Stock classification thresholds (must match LowStockNotifier).
     */
    private const CRITICAL_PCT = 25.0;
    private const WARNING_PCT  = 40.0;

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
        // ── 1. Fetch ALL raw material stock entries ──────────────────────────
        $stockModel = new RawMaterialStockModel();
        $allItems   = $stockModel->getAllWithDetails();

        if (empty($allItems)) {
            log_message('info', 'AutoReportScheduler: No stock entries in database. Report skipped.');
            return false;
        }

        // ── 2. Classify each material by remaining stock percentage ──────────
        $criticalItems = [];
        $warningItems  = [];
        $normalItems   = [];

        foreach ($allItems as $item) {
            $initial   = floatval($item['initial_qty'] ?? 0);
            $remaining = floatval($item['remaining']   ?? 0);

            if ($initial <= 0) {
                $pct    = 0.0;
                $status = 'critical';
            } else {
                $pct    = round(($remaining / $initial) * 100, 1);
                if ($pct <= self::CRITICAL_PCT) {
                    $status = 'critical';
                } elseif ($pct <= self::WARNING_PCT) {
                    $status = 'warning';
                } else {
                    $status = 'normal';
                }
            }

            $item['stock_percentage'] = $pct;
            $item['stock_status']     = $status;
            $item['current_quantity'] = $remaining;

            if ($status === 'critical') {
                $criticalItems[] = $item;
            } elseif ($status === 'warning') {
                $warningItems[] = $item;
            } else {
                $normalItems[] = $item;
            }
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
        $slotLabel   = $slot === 'am' ? 'Morning (3:00 AM)' : 'Evening (8:00 PM)';
        $subject     = '📦 Inventory Status Report — ' . $slotLabel
                     . ' — ' . date('F d, Y', strtotime($date));

        $emailBody = self::buildEmailBody($criticalItems, $warningItems, $normalItems, $slot, $date);

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
    private static function buildEmailBody(
        array  $criticalItems,
        array  $warningItems,
        array  $normalItems,
        string $slot,
        string $date
    ): string {
        $reportDate   = date('F d, Y', strtotime($date));
        $reportTime   = date('h:i A');
        $reportRef    = 'INV-' . strtoupper($slot) . '-' . date('Ymd-His');
        $slotTitle    = $slot === 'am' ? 'Morning Inventory Report'      : 'Evening Inventory Report';
        $slotSubtitle = $slot === 'am' ? '3:00 AM Scheduled Snapshot'    : '8:00 PM Scheduled Snapshot';
        $headerColor  = $slot === 'am' ? '#17a2b8'                       : '#6f42c1';

        $totalCritical = count($criticalItems);
        $totalWarning  = count($warningItems);
        $totalNormal   = count($normalItems);
        $totalItems    = $totalCritical + $totalWarning + $totalNormal;

        // Build per-section card HTML
        $criticalCards = self::buildItemCards($criticalItems, '#dc3545', 'LOW',     '#fff5f5');
        $warningCards  = self::buildItemCards($warningItems,  '#e67e22', 'WARNING', '#fffdf0');
        $normalCards   = self::buildItemCards($normalItems,   '#28a745', 'OK',      '#f0fff4');

        $criticalSection = $totalCritical > 0
            ? "<h3 style='font-size:16px;color:#dc3545;margin:25px 0 15px;'>&#128308; Critical Stock ({$totalCritical})</h3>{$criticalCards}"
            : '';

        $warningSection = $totalWarning > 0
            ? "<h3 style='font-size:16px;color:#e67e22;margin:25px 0 15px;'>&#128993; Warning Stock ({$totalWarning})</h3>{$warningCards}"
            : '';

        $normalSection = $totalNormal > 0
            ? "<h3 style='font-size:16px;color:#28a745;margin:25px 0 15px;'>&#128994; Adequate Stock ({$totalNormal})</h3>{$normalCards}"
            : '';

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
                            <td style='padding:6px 0;font-size:13px;color:#333;'>{$totalItems} material(s) tracked</td>
                        </tr>
                    </table>

                    <hr style='border:none;border-top:1px solid #ddd;margin:15px 0;'>

                    <p style='font-size:14px;'>Dear Owner,</p>
                    <p style='font-size:14px;'>
                        Below is your <strong>{$slotSubtitle}</strong> inventory snapshot for <strong>{$reportDate}</strong>.
                        All tracked raw materials are listed with their current stock levels.
                    </p>

                    <!-- Summary Cards -->
                    <table style='margin:20px 0;'>
                        <tr>
                            <td style='padding:15px;background:#fff5f5;border:1px solid #dc3545;border-radius:5px;text-align:center;width:33%;'>
                                <div style='font-size:28px;font-weight:bold;color:#dc3545;'>{$totalCritical}</div>
                                <div style='font-size:11px;color:#666;margin-top:4px;'>Critical (&le;&nbsp;25%)</div>
                            </td>
                            <td style='width:10px;'></td>
                            <td style='padding:15px;background:#fffdf0;border:1px solid #e67e22;border-radius:5px;text-align:center;width:33%;'>
                                <div style='font-size:28px;font-weight:bold;color:#e67e22;'>{$totalWarning}</div>
                                <div style='font-size:11px;color:#666;margin-top:4px;'>Warning (&le;&nbsp;40%)</div>
                            </td>
                            <td style='width:10px;'></td>
                            <td style='padding:15px;background:#f0fff4;border:1px solid #28a745;border-radius:5px;text-align:center;width:33%;'>
                                <div style='font-size:28px;font-weight:bold;color:#28a745;'>{$totalNormal}</div>
                                <div style='font-size:11px;color:#666;margin-top:4px;'>Adequate (&gt;&nbsp;40%)</div>
                            </td>
                        </tr>
                    </table>

                    <!-- Per-Status Item Sections -->
                    {$criticalSection}
                    {$warningSection}
                    {$normalSection}

                    <hr style='border:none;border-top:1px solid #ddd;margin:25px 0 15px;'>

                    <p style='font-size:14px;'>
                        Please review any critical or warning items and arrange restocking as needed.
                        For the full inventory management interface, visit the <strong>Stock Initial</strong> page in the system.
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

    /**
     * Render a grid of material cards for a single status group.
     *
     * @param array  $items      Items in this status group
     * @param string $color      Accent / border color (hex)
     * @param string $badge      Badge label text (e.g. "LOW", "WARNING", "OK")
     * @param string $bgColor    Card background color (hex)
     */
    private static function buildItemCards(
        array  $items,
        string $color,
        string $badge,
        string $bgColor
    ): string {
        if (empty($items)) {
            return '';
        }

        $cards = '';

        foreach ($items as $item) {
            $name       = htmlspecialchars($item['material_name'] ?? '—');
            $category   = htmlspecialchars($item['category_name'] ?? '—');
            $unit       = htmlspecialchars($item['unit'] ?? '');
            $initial    = number_format(floatval($item['initial_qty']                             ?? 0), 2);
            $used       = number_format(floatval($item['qty_used']                               ?? 0), 2);
            $remaining  = number_format(floatval($item['remaining'] ?? $item['current_quantity'] ?? 0), 2);
            $pct        = $item['stock_percentage'] ?? 0;
            $lastUpdate = isset($item['updated_at'])
                ? date('M d, Y h:i A', strtotime($item['updated_at']))
                : '—';

            $cards .= "
                <div style='background:{$bgColor};border:1px solid {$color};border-radius:8px;padding:15px;margin-bottom:12px;'>
                    <div style='margin-bottom:12px;border-bottom:2px solid {$color};padding-bottom:10px;overflow:hidden;'>
                        <span style='font-size:16px;font-weight:bold;color:#333;'>{$name}</span>
                        <span style='font-size:12px;color:#888;margin-left:8px;'>({$category})</span>
                        <span style='float:right;background:{$color};color:white;padding:4px 10px;border-radius:12px;font-size:11px;font-weight:bold;'>{$badge}</span>
                    </div>
                    <table style='width:100%;border-collapse:collapse;'>
                        <tr>
                            <td style='padding:8px 0;width:33%;border-bottom:1px solid #f0f0f0;'>
                                <div style='font-size:10px;color:#888;text-transform:uppercase;'>Initial Stock</div>
                                <div style='font-size:13px;font-weight:bold;color:#333;margin-top:2px;'>{$initial} {$unit}</div>
                            </td>
                            <td style='padding:8px 0;width:33%;border-bottom:1px solid #f0f0f0;'>
                                <div style='font-size:10px;color:#888;text-transform:uppercase;'>Used</div>
                                <div style='font-size:13px;font-weight:bold;color:#333;margin-top:2px;'>{$used} {$unit}</div>
                            </td>
                            <td style='padding:8px 0;width:33%;border-bottom:1px solid #f0f0f0;'>
                                <div style='font-size:10px;color:#888;text-transform:uppercase;'>Remaining</div>
                                <div style='font-size:13px;font-weight:bold;color:{$color};margin-top:2px;'>{$remaining} {$unit}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style='padding:8px 0;'>
                                <div style='font-size:10px;color:#888;text-transform:uppercase;'>Stock Level</div>
                                <div style='font-size:15px;font-weight:bold;color:{$color};margin-top:2px;'>{$pct}%</div>
                            </td>
                            <td colspan='2' style='padding:8px 0;'>
                                <div style='font-size:10px;color:#888;text-transform:uppercase;'>Last Updated</div>
                                <div style='font-size:12px;color:#666;margin-top:2px;'>{$lastUpdate}</div>
                            </td>
                        </tr>
                    </table>
                </div>";
        }

        return $cards;
    }
}
