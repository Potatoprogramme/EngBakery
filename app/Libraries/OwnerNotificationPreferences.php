<?php

namespace App\Libraries;

use App\Models\OwnerNotificationSettingsModel;

class OwnerNotificationPreferences
{
    public const TYPE_LOW_STOCK = 'low_stock';
    public const TYPE_INVENTORY = 'inventory';
    public const TYPE_REMITTANCE = 'remittance';
    public const TYPE_MATERIAL_STOCK_LOGS = 'material_stock_logs';
    public const TYPE_BEGINNING_QUANTITY_ADJUSTMENTS = 'beginning_quantity_adjustments';

    /**
     * @return array<string, int>
     */
    public static function defaultSettings(): array
    {
        return [
            'low_stock_enabled' => 1,
            'inventory_enabled' => 1,
            'remittance_enabled' => 1,
            'material_stock_logs_enabled' => 1,
            'beginning_quantity_adjustments_enabled' => 1,
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
            'material_stock_logs_enabled' => intval($record['material_stock_logs_enabled'] ?? $defaults['material_stock_logs_enabled']),
            'beginning_quantity_adjustments_enabled' => intval($record['beginning_quantity_adjustments_enabled'] ?? $defaults['beginning_quantity_adjustments_enabled']),
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
            'material_stock_logs_enabled' => self::normalizeToggle($settings['material_stock_logs_enabled'] ?? 1),
            'beginning_quantity_adjustments_enabled' => self::normalizeToggle($settings['beginning_quantity_adjustments_enabled'] ?? 1),
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

        if ($normalized === self::TYPE_MATERIAL_STOCK_LOGS) {
            return 'material_stock_logs_enabled';
        }

        if ($normalized === self::TYPE_BEGINNING_QUANTITY_ADJUSTMENTS) {
            return 'beginning_quantity_adjustments_enabled';
        }

        return null;
    }
}
