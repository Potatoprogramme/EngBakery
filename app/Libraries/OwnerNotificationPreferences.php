<?php

namespace App\Libraries;

use App\Models\OwnerNotificationSettingsModel;

class OwnerNotificationPreferences
{
    public const TYPE_LOW_STOCK = 'low_stock';
    public const TYPE_INVENTORY = 'inventory';
    public const TYPE_REMITTANCE = 'remittance';

    /**
     * @return array<string, int>
     */
    public static function defaultSettings(): array
    {
        return [
            'low_stock_enabled' => 1,
            'inventory_enabled' => 1,
            'remittance_enabled' => 1,
        ];
    }

    /**
     * @return array<string, int>
     */
    public static function getForUser(int $userId): array
    {
        $defaults = self::defaultSettings();
        $model = new OwnerNotificationSettingsModel();
        $record = $model->getByUserId($userId);

        if (empty($record)) {
            return $defaults;
        }

        return [
            'low_stock_enabled' => intval($record['low_stock_enabled'] ?? $defaults['low_stock_enabled']),
            'inventory_enabled' => intval($record['inventory_enabled'] ?? $defaults['inventory_enabled']),
            'remittance_enabled' => intval($record['remittance_enabled'] ?? $defaults['remittance_enabled']),
        ];
    }

    /**
     * @param array<string, mixed> $settings
     */
    public static function upsertForUser(int $userId, array $settings): bool
    {
        $normalized = [
            'low_stock_enabled' => self::normalizeToggle($settings['low_stock_enabled'] ?? 1),
            'inventory_enabled' => self::normalizeToggle($settings['inventory_enabled'] ?? 1),
            'remittance_enabled' => self::normalizeToggle($settings['remittance_enabled'] ?? 1),
        ];

        $model = new OwnerNotificationSettingsModel();
        $existing = $model->getByUserId($userId);

        if ($existing) {
            return (bool) $model->update($existing['owner_notification_setting_id'], $normalized);
        }

        return (bool) $model->insert(array_merge(['user_id' => $userId], $normalized));
    }

    /**
     * @param array<int, array<string, mixed>> $owners
     * @return array<int, string>
     */
    public static function resolveEmailsForType(array $owners, string $type): array
    {
        $field = self::fieldForType($type);
        if ($field === null) {
            return [];
        }

        $model = new OwnerNotificationSettingsModel();
        $emails = [];

        foreach ($owners as $owner) {
            $email = trim((string) ($owner['email'] ?? ''));
            $userId = intval($owner['user_id'] ?? 0);

            if ($email === '' || $userId <= 0) {
                continue;
            }

            $settings = $model->getByUserId($userId);
            $enabled = $settings ? intval($settings[$field] ?? 1) : 1;

            if ($enabled === 1) {
                $emails[] = $email;
            }
        }

        return array_values(array_unique($emails));
    }

    private static function normalizeToggle(mixed $value): int
    {
        return intval($value) === 1 ? 1 : 0;
    }

    private static function fieldForType(string $type): ?string
    {
        $normalized = strtolower(trim($type));

        if ($normalized === self::TYPE_LOW_STOCK) {
            return 'low_stock_enabled';
        }

        if ($normalized === self::TYPE_INVENTORY) {
            return 'inventory_enabled';
        }

        if ($normalized === self::TYPE_REMITTANCE) {
            return 'remittance_enabled';
        }

        return null;
    }
}
