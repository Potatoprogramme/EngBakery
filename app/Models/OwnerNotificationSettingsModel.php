<?php

namespace App\Models;

use CodeIgniter\Model;

class OwnerNotificationSettingsModel extends Model
{
    protected $table = 'owner_notification_settings';
    protected $primaryKey = 'owner_notification_setting_id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'user_id',
        'low_stock_enabled',
        'inventory_enabled',
        'remittance_enabled',
        'material_stock_logs_enabled',
    ];

    public function getByUserId(int $userId): ?array
    {
        $record = $this->where('user_id', $userId)->first();
        return $record ?: null;
    }
}
