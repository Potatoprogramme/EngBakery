<?php

namespace App\Libraries;

use App\Models\RemittanceDetailsModel;
use App\Models\RemittanceDenominationsModel;
use App\Models\UsersModel;

class DailyRemittanceReport
{
    /**
     * Generate and email today's remittance summary to all owners.
     * Only sends once per day to avoid spam.
     * 
     * @param string|null $date Date to generate report for (Y-m-d format)
     * @param bool $forceResend If true, ignores the daily throttle (for testing)
     */
    public static function sendReport(?string $date = null, bool $forceResend = false): bool
    {
        $date = $date ?? date('Y-m-d');

        // Daily throttle: only send once per calendar day
        $flagFile = WRITEPATH . 'remittance_email_sent_' . $date . '.flag';
        if (!$forceResend && file_exists($flagFile)) {
            return false; // Already sent today
        }

        $remittanceModel     = new RemittanceDetailsModel();
        $denominationsModel  = new RemittanceDenominationsModel();

        // Fetch all remittances for the date with cashier names
        $remittances = $remittanceModel->getRemittancesByDate($date);

        if (empty($remittances)) {
            return false;
        }

        // Attach denomination breakdowns
        foreach ($remittances as &$r) {
            $r['denominations'] = $denominationsModel->getDenominationsBreakdown($r['remittance_id']);
        }

        // Get owner emails
        $usersModel = new UsersModel();
        $owners = $usersModel->where('employee_type', 'owner')
                             ->where('approved', 1)
                             ->findAll();

        if (empty($owners)) {
            return false;
        }

        $ownerEmails = array_column($owners, 'email');
        $emailBody   = self::buildEmailBody($remittances, $date);

        try {
            $emailService = \Config\Services::email();
            $emailService->setFrom('noreply@engbakery.com', "E n' G Bakery");
            $emailService->setTo($ownerEmails);
            $emailService->setSubject('📋 Daily Remittance Report — ' . date('F d, Y', strtotime($date)));
            $emailService->setMessage($emailBody);
            $emailService->setMailType('html');

            if ($emailService->send()) {
                // Mark as sent for today
                file_put_contents($flagFile, date('Y-m-d H:i:s'));
                return true;
            }
        } catch (\Exception $e) {
            log_message('error', 'DailyRemittanceReport email failed: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * Build the HTML email body for the remittance summary.
     */
    private static function buildEmailBody(array $remittances, string $date): string
    {
        $reportDate = date('F d, Y', strtotime($date));
        $reportTime = date('h:i A');
        $reportRef  = 'REM-' . date('Ymd-His');

        // Aggregate totals
        $totalSales      = 0;
        $totalRemitted   = 0;
        $totalBakery     = 0;
        $totalCoffee     = 0;
        $totalGrocery    = 0;
        $totalCashOut    = 0;
        $totalVariance   = 0;
        $shortCount      = 0;
        $overCount       = 0;

        foreach ($remittances as $r) {
            $totalSales    += floatval($r['total_sales']);
            $totalRemitted += floatval($r['amount_enclosed']);
            $totalBakery   += floatval($r['bakery_sales']);
            $totalCoffee   += floatval($r['coffee_sales']);
            $totalGrocery  += floatval($r['grocery_sales']);
            $totalCashOut  += floatval($r['cash_out']);
            $variance       = floatval($r['variance_amount']);
            if ($r['is_short']) {
                $totalVariance -= $variance;
                $shortCount++;
            } else {
                $totalVariance += $variance;
                if ($variance > 0) $overCount++;
            }
        }

        $shiftCount = count($remittances);

        // Build individual shift rows
        $shiftRows = '';
        foreach ($remittances as $i => $r) {
            $shiftNum    = $i + 1;
            $cashier     = $r['cashier_name'] ?? 'Unknown';
            $outlet      = $r['outlet_name'] ?? '—';
            $shiftTime   = date('h:i A', strtotime($r['shift_start'])) . ' – ' . date('h:i A', strtotime($r['shift_end']));
            $sales       = number_format(floatval($r['total_sales']), 2);
            $enclosed    = number_format(floatval($r['amount_enclosed']), 2);
            $cashOut     = number_format(floatval($r['cash_out']), 2);
            $variance    = floatval($r['variance_amount']);
            $isShort     = $r['is_short'];

            $varianceText  = '₱' . number_format($variance, 2);
            $varianceColor = '#28a745';
            $varianceLabel = 'EXACT';
            if ($variance > 0 && $isShort) {
                $varianceColor = '#dc3545';
                $varianceLabel = 'SHORT';
                $varianceText  = '-₱' . number_format($variance, 2);
            } elseif ($variance > 0 && !$isShort) {
                $varianceColor = '#007bff';
                $varianceLabel = 'OVER';
                $varianceText  = '+₱' . number_format($variance, 2);
            }

            $cashOutCell = floatval($r['cash_out']) > 0
                ? '₱' . $cashOut . '<br><span style="font-size:11px;color:#888;">' . htmlspecialchars($r['cashout_reason'] ?? '') . '</span>'
                : '—';

            $shiftRows .= "
                <tr>
                    <td style='padding:10px 12px;border-bottom:1px solid #eee;text-align:center;font-weight:bold;'>{$shiftNum}</td>
                    <td style='padding:10px 12px;border-bottom:1px solid #eee;'>{$cashier}</td>
                    <td style='padding:10px 12px;border-bottom:1px solid #eee;'>{$outlet}</td>
                    <td style='padding:10px 12px;border-bottom:1px solid #eee;'>{$shiftTime}</td>
                    <td style='padding:10px 12px;border-bottom:1px solid #eee;text-align:right;'>₱{$sales}</td>
                    <td style='padding:10px 12px;border-bottom:1px solid #eee;text-align:right;'>₱{$enclosed}</td>
                    <td style='padding:10px 12px;border-bottom:1px solid #eee;text-align:center;'>{$cashOutCell}</td>
                    <td style='padding:10px 12px;border-bottom:1px solid #eee;text-align:center;'>
                        <span style='background:{$varianceColor};color:white;padding:2px 8px;border-radius:10px;font-size:11px;'>{$varianceLabel}</span><br>
                        <span style='color:{$varianceColor};font-size:12px;font-weight:bold;'>{$varianceText}</span>
                    </td>
                </tr>";
        }

        // Totals row
        $fmtTotalSales    = number_format($totalSales, 2);
        $fmtTotalRemitted = number_format($totalRemitted, 2);
        $fmtTotalCashOut  = number_format($totalCashOut, 2);
        $fmtBakery        = number_format($totalBakery, 2);
        $fmtCoffee        = number_format($totalCoffee, 2);
        $fmtGrocery       = number_format($totalGrocery, 2);

        $netVarianceColor = $totalVariance < 0 ? '#dc3545' : ($totalVariance > 0 ? '#007bff' : '#28a745');
        $netVarianceText  = $totalVariance < 0
            ? '-₱' . number_format(abs($totalVariance), 2)
            : ($totalVariance > 0 ? '+₱' . number_format($totalVariance, 2) : '₱0.00');
        $netVarianceLabel = $totalVariance < 0 ? 'NET SHORT' : ($totalVariance > 0 ? 'NET OVER' : 'BALANCED');

        $year = date('Y');

        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                .container { max-width: 800px; margin: 0 auto; padding: 20px; }
                .header { background-color: #007B4C; color: white; padding: 25px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background-color: #f9f9f9; padding: 25px; border: 1px solid #ddd; border-radius: 0 0 5px 5px; }
                table { width: 100%; border-collapse: collapse; }
                th { background-color: #007B4C; color: white; padding: 10px 12px; text-align: left; font-size: 13px; }
                .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1 style='margin:0;font-size:24px;'>Daily Remittance Report</h1>
                    <p style='margin:5px 0 0;font-size:14px;'>E n' G Bakery — End-of-Day Summary</p>
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
                            <td style='padding:6px 0;font-size:13px;color:#555;'><strong>Total Shifts:</strong></td>
                            <td style='padding:6px 0;font-size:13px;color:#333;'>{$shiftCount} remittance(s)</td>
                        </tr>
                    </table>

                    <hr style='border:none;border-top:1px solid #ddd;margin:15px 0;'>

                    <p style='font-size:14px;'>Dear Owner,</p>
                    <p style='font-size:14px;'>Below is the complete remittance summary for <strong>{$reportDate}</strong>.</p>

                    <!-- Summary Cards -->
                    <table style='margin:20px 0;'>
                        <tr>
                            <td style='padding:15px;background:#fff;border:1px solid #ddd;border-radius:5px;text-align:center;width:25%;'>
                                <div style='font-size:24px;font-weight:bold;color:#007B4C;'>₱{$fmtTotalSales}</div>
                                <div style='font-size:11px;color:#666;margin-top:4px;'>Total Sales</div>
                            </td>
                            <td style='width:8px;'></td>
                            <td style='padding:15px;background:#fff;border:1px solid #ddd;border-radius:5px;text-align:center;width:25%;'>
                                <div style='font-size:24px;font-weight:bold;color:#333;'>₱{$fmtTotalRemitted}</div>
                                <div style='font-size:11px;color:#666;margin-top:4px;'>Cash Remitted</div>
                            </td>
                            <td style='width:8px;'></td>
                            <td style='padding:15px;background:#fff;border:1px solid #ddd;border-radius:5px;text-align:center;width:25%;'>
                                <div style='font-size:24px;font-weight:bold;color:{$netVarianceColor};'>{$netVarianceText}</div>
                                <div style='font-size:11px;color:#666;margin-top:4px;'>{$netVarianceLabel}</div>
                            </td>
                        </tr>
                    </table>

                    <!-- Sales by Category -->
                    <table style='margin:15px 0 20px;'>
                        <tr>
                            <td style='padding:10px 15px;background:#fff;border:1px solid #ddd;border-radius:5px;width:33%;'>
                                <div style='font-size:11px;color:#888;text-transform:uppercase;'>Bakery</div>
                                <div style='font-size:18px;font-weight:bold;color:#d4a017;'>₱{$fmtBakery}</div>
                            </td>
                            <td style='width:8px;'></td>
                            <td style='padding:10px 15px;background:#fff;border:1px solid #ddd;border-radius:5px;width:33%;'>
                                <div style='font-size:11px;color:#888;text-transform:uppercase;'>Coffee / Drinks</div>
                                <div style='font-size:18px;font-weight:bold;color:#6f4e37;'>₱{$fmtCoffee}</div>
                            </td>
                            <td style='width:8px;'></td>
                            <td style='padding:10px 15px;background:#fff;border:1px solid #ddd;border-radius:5px;width:33%;'>
                                <div style='font-size:11px;color:#888;text-transform:uppercase;'>Grocery</div>
                                <div style='font-size:18px;font-weight:bold;color:#2e8b57;'>₱{$fmtGrocery}</div>
                            </td>
                        </tr>
                    </table>

                    <!-- Shift Details Table -->
                    <h3 style='font-size:15px;color:#333;margin-bottom:10px;'>Shift Breakdown</h3>
                    <table style='margin:0 0 20px;'>
                        <thead>
                            <tr>
                                <th style='text-align:center;width:40px;'>#</th>
                                <th>Cashier</th>
                                <th>Outlet</th>
                                <th>Shift</th>
                                <th style='text-align:right;'>Sales</th>
                                <th style='text-align:right;'>Remitted</th>
                                <th style='text-align:center;'>Cash Out</th>
                                <th style='text-align:center;'>Variance</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$shiftRows}
                            <tr style='background:#f0f0f0;font-weight:bold;'>
                                <td colspan='4' style='padding:10px 12px;border-top:2px solid #007B4C;'>TOTALS</td>
                                <td style='padding:10px 12px;border-top:2px solid #007B4C;text-align:right;'>₱{$fmtTotalSales}</td>
                                <td style='padding:10px 12px;border-top:2px solid #007B4C;text-align:right;'>₱{$fmtTotalRemitted}</td>
                                <td style='padding:10px 12px;border-top:2px solid #007B4C;text-align:center;'>₱{$fmtTotalCashOut}</td>
                                <td style='padding:10px 12px;border-top:2px solid #007B4C;text-align:center;color:{$netVarianceColor};'>{$netVarianceText}</td>
                            </tr>
                        </tbody>
                    </table>

                    <hr style='border:none;border-top:1px solid #ddd;margin:15px 0;'>

                    <p style='font-size:14px;'>
                        Please review the above summary and address any discrepancies at your earliest convenience.
                    </p>
                    <p style='font-size:14px;margin-top:20px;'>
                        Respectfully,<br>
                        <strong>E n' G Bakery Sales System</strong>
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
}
