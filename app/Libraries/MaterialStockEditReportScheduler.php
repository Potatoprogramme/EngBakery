<?php

namespace App\Libraries;

use App\Models\RawMaterialStockLogModel;
use App\Models\UsersModel;
use App\Libraries\OwnerNotificationPreferences;

class MaterialStockEditReportScheduler
{
    private const SLOT_START_HOUR = 19; // 7:00 PM
    private const SLOT_END_HOUR = 20;   // exclusive — fires during 19:00–19:59

    public static function runDueJobs(): void
    {
        $nowH = (int) date('G');
        if ($nowH < self::SLOT_START_HOUR || $nowH >= self::SLOT_END_HOUR) {
            return;
        }

        // self::runNow(false); //Uncomment this line to enable the Material Stock Edit report scheduler. It is currently disabled to avoid spamming owners during testing.

        // Note: The above line is commented out to prevent the Material Stock Edit report 
        // from being sent automatically during testing. Uncomment it when ready for production use.
        $today = date('Y-m-d');
        $flagFile = WRITEPATH . 'material_stock_edit_report_' . $today . '.flag';

        if (file_exists($flagFile)) {
            return; // already handled today (sent or confirmed empty)
        }

        $lockName = 'engbakery:material_stock_edit_report:' . $today;
        if (!self::acquireDbLock($lockName)) {
            return;
        }

        try {
            if (file_exists($flagFile)) {
                return;
            }

            self::sendIfAnyEdits($today);
            file_put_contents($flagFile, date('Y-m-d H:i:s'));
        } catch (\Throwable $e) {
            log_message('error', 'MaterialStockEditReportScheduler exception: ' . $e->getMessage());
        } finally {
            self::releaseDbLock($lockName);
        }
    }

    /**
     * Force-run the report check right now, bypassing the 7 PM time window.
     * @param bool $forceSend When true, also bypasses the once-per-day flag file
     *                        so you can re-run it repeatedly while testing.
     */
    public static function runNow(bool $forceSend = false): void
    {
        $today = date('Y-m-d');
        $flagFile = WRITEPATH . 'material_stock_edit_report_' . $today . '.flag';

        if (!$forceSend && file_exists($flagFile)) {
            return;
        }

        $lockName = 'engbakery:material_stock_edit_report:' . $today;
        if (!self::acquireDbLock($lockName)) {
            return;
        }

        try {
            if (!$forceSend && file_exists($flagFile)) {
                return;
            }

            self::sendIfAnyEdits($today);

            if (!$forceSend) {
                file_put_contents($flagFile, date('Y-m-d H:i:s'));
            }
        } catch (\Throwable $e) {
            log_message('error', 'MaterialStockEditReportScheduler exception: ' . $e->getMessage());
        } finally {
            self::releaseDbLock($lockName);
        }
    }
    private static function sendIfAnyEdits(string $date): void
    {
        $edits = (new RawMaterialStockLogModel())->getEditsForDate($date);

        if (empty($edits)) {
            log_message('info', 'MaterialStockEditReportScheduler: No manual stock edits on ' . $date . '. Skipping email.');
            return;
        }

        $usersModel = new UsersModel();
        $owners = $usersModel->where('employee_type', 'owner')->where('approved', 1)->findAll();
        if (empty($owners)) {
            log_message('warning', 'MaterialStockEditReportScheduler: No owner accounts found.');
            return;
        }

        $ownerEmails = OwnerNotificationPreferences::resolveEmailsForType(
            $owners,
            OwnerNotificationPreferences::TYPE_MATERIAL_STOCK_LOGS
        );
        if (empty($ownerEmails)) {
            log_message('info', 'MaterialStockEditReportScheduler: All owners have this notification turned off.');
            return;
        }

        $subject = 'Material Stock Manual Edits — ' . date('F d, Y', strtotime($date)) . ' (' . count($edits) . ' change(s))';
        $body = self::buildEmailBody($edits, $date);

        try {
            $emailService = \Config\Services::email();
            $emailService->setFrom('noreply@engbakery.com', "E n' G Bakery - Deca Sentrio");
            $emailService->setTo($ownerEmails);
            $emailService->setSubject($subject);
            $emailService->setMessage($body);
            $emailService->setMailType('html');

            if ($emailService->send()) {
                log_message('info', 'MaterialStockEditReportScheduler: sent to ' . implode(', ', $ownerEmails));
            } else {
                log_message('error', 'MaterialStockEditReportScheduler: send failed: ' . $emailService->printDebugger(['headers']));
            }
        } catch (\Throwable $e) {
            log_message('error', 'MaterialStockEditReportScheduler: exception sending email: ' . $e->getMessage());
        }
    }

    private static function buildEmailBody(array $edits, string $date): string
    {
        $reportDate = date('F d, Y', strtotime($date));
        $rows = '';

        foreach ($edits as $edit) {
            $name = htmlspecialchars((string) ($edit['material_name'] ?? 'Unknown'));
            $action = strtoupper((string) ($edit['action'] ?? ''));
            $amount = round(floatval($edit['amount'] ?? 0), 4);
            $unit = htmlspecialchars((string) ($edit['unit'] ?? ''));
            $before = round(floatval($edit['before_qty'] ?? 0), 4);
            $after = round(floatval($edit['after_qty'] ?? 0), 4);
            $who = htmlspecialchars((string) ($edit['changed_by_name'] ?? 'Unknown'));
            $time = date('h:i A', strtotime((string) ($edit['created_at'] ?? 'now')));

            $badgeStyle = $action === 'ADDED'
                ? "background:#ecfdf5;color:#166534;border:1px solid #bbf7d0;"
                : "background:#fef2f2;color:#991b1b;border:1px solid #fecaca;";

            $rows .= "
                <tr>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;'>{$name}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:11px;text-align:center;'>
                        <span style='padding:3px 8px;border-radius:999px;font-weight:700;{$badgeStyle}'>{$action}</span>
                    </td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;text-align:center;'>{$amount} {$unit}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$before} → {$after}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;'>{$who}</td>
                    <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$time}</td>
                </tr>";
        }

        return "
        <html><head><style>
            body { font-family: Arial, sans-serif; color:#333; margin:0; padding:0; }
            .container { max-width: 750px; margin:0 auto; padding:20px; }
            .header { background:#0f766e; color:#fff; padding:22px; border-radius:8px 8px 0 0; }
            .content { background:#f9f9f9; padding:22px; border:1px solid #ddd; border-top:none; border-radius:0 0 8px 8px; }
            table { width:100%; border-collapse:collapse; }
            th { background:#0f766e; color:#fff; padding:10px; text-align:left; font-size:12px; }
        </style></head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1 style='margin:0;font-size:22px;'>Material Stock — Manual Edits Report</h1>
                    <p style='margin:6px 0 0;font-size:13px;opacity:.9;'>E n' G Bakery - Deca Sentrio — {$reportDate}</p>
                </div>
                <div class='content'>
                    <p style='font-size:14px;'>Dear Owner,</p>
                    <p style='font-size:14px;'>Below are all manual additions/subtractions made to Material Stock today (" . count($edits) . " total).</p>
                    <div style='overflow-x:auto;'>
                        <table style='background:#fff;border:1px solid #e5e7eb;'>
                            <thead><tr>
                                <th>Material</th>
                                <th style='text-align:center;'>Action</th>
                                <th style='text-align:center;'>Amount</th>
                                <th style='text-align:center;'>Before → After</th>
                                <th>Edited By</th>
                                <th style='text-align:center;'>Time</th>
                            </tr></thead>
                            <tbody>{$rows}</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </body></html>";
    }

    private static function acquireDbLock(string $lockName): bool
    {
        try {
            $db = \Config\Database::connect();
            $row = $db->query('SELECT GET_LOCK(?, 0) AS lock_status', [$lockName])->getRowArray();
            return intval($row['lock_status'] ?? 0) === 1;
        } catch (\Throwable $e) {
            log_message('error', 'MaterialStockEditReportScheduler: lock error: ' . $e->getMessage());
            return false;
        }
    }

    private static function releaseDbLock(string $lockName): void
    {
        try {
            $db = \Config\Database::connect();
            $db->query('SELECT RELEASE_LOCK(?)', [$lockName]);
        } catch (\Throwable $e) {
            log_message('error', 'MaterialStockEditReportScheduler: unlock error: ' . $e->getMessage());
        }
    }
}
