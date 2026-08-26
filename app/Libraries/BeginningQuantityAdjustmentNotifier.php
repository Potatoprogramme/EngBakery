<?php

namespace App\Libraries;

use App\Models\UsersModel;

class BeginningQuantityAdjustmentNotifier
{
    public static function send(
        string $productName,
        int $oldBeginning,
        int $newBeginning,
        array $actor,
        string $inventoryDate
    ): void {
        if ($oldBeginning === $newBeginning) {
            return;
        }

        $owners = (new UsersModel())
            ->where('employee_type', 'owner')
            ->where('approved', 1)
            ->findAll();
        $ownerEmails = OwnerNotificationPreferences::resolveEmailsForType(
            $owners,
            OwnerNotificationPreferences::TYPE_BEGINNING_QUANTITY_ADJUSTMENTS
        );

        if (empty($ownerEmails)) {
            log_message('info', 'Beginning quantity adjustment notification skipped: no enabled owner recipients.');
            return;
        }

        $difference = $newBeginning - $oldBeginning;
        $differenceLabel = ($difference > 0 ? '+' : '') . $difference;
        $actorName = trim((string) ($actor['name'] ?? 'System')) ?: 'System';
        $actorEmail = trim((string) ($actor['email'] ?? ''));
        $safeProductName = htmlspecialchars($productName, ENT_QUOTES, 'UTF-8');
        $safeActorName = htmlspecialchars($actorName, ENT_QUOTES, 'UTF-8');
        $safeActorEmail = htmlspecialchars($actorEmail, ENT_QUOTES, 'UTF-8');
        $actorDisplay = $safeActorEmail === '' ? $safeActorName : $safeActorName . ' (' . $safeActorEmail . ')';
        $dateDisplay = date('F d, Y', strtotime($inventoryDate));

        $body = '<!doctype html><html><body style="margin:0;background:#f8fafc;font-family:Arial,sans-serif;color:#1f2937;">'
            . '<div style="max-width:620px;margin:24px auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;">'
            . '<div style="background:#fff200;border-bottom:5px solid #10a94b;padding:20px 24px;">'
            . '<div style="font-size:12px;font-weight:bold;letter-spacing:1px;color:#c1121f;">E N\' G BAKERY</div>'
            . '<h2 style="margin:8px 0 0;color:#991b1b;font-size:22px;">Beginning Quantity Adjusted</h2>'
            . '</div>'
            . '<div style="padding:24px;">'
            . '<p style="margin:0 0 18px;color:#4b5563;">The beginning quantity for an inventory item was adjusted.</p>'
            . '<table style="width:100%;border-collapse:collapse;border:1px solid #e5e7eb;">'
            . self::row('Product', $safeProductName)
            . self::row('Previous quantity', (string) $oldBeginning)
            . self::row('New quantity', (string) $newBeginning)
            . self::row('Change', $differenceLabel)
            . self::row('Adjusted by', $actorDisplay)
            . self::row('Inventory date', htmlspecialchars($dateDisplay, ENT_QUOTES, 'UTF-8'))
            . self::row('Adjusted at', date('F d, Y h:i A'))
            . '</table></div></div></body></html>';

        try {
            $email = \Config\Services::email();
            $email->setFrom('noreply@engbakery.com', "E n' G Bakery - Deca Sentrio");
            $email->setTo($ownerEmails);
            $email->setSubject('Beginning Quantity Adjusted - ' . $productName);
            $email->setMessage($body);
            $email->setMailType('html');

            if (!$email->send()) {
                log_message('error', 'Beginning quantity adjustment email failed: ' . $email->printDebugger(['headers']));
            }
        } catch (\Throwable $e) {
            log_message('error', 'Beginning quantity adjustment notification exception: ' . $e->getMessage());
        }
    }

    private static function row(string $label, string $value): string
    {
        $safeLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
        return '<tr><td style="padding:10px 12px;border-bottom:1px solid #e5e7eb;background:#f8fafc;font-weight:bold;">' . $safeLabel
            . '</td><td style="padding:10px 12px;border-bottom:1px solid #e5e7eb;">' . $value . '</td></tr>';
    }
}
