<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOwnerNotificationSettings extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('owner_notification_settings')) {
            return;
        }

        $this->forge->addField([
            'owner_notification_setting_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'low_stock_enabled' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'inventory_enabled' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'remittance_enabled' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('owner_notification_setting_id', true);
        $this->forge->addUniqueKey('user_id', 'uniq_owner_notification_settings_user_id');
        $this->forge->createTable('owner_notification_settings', true);
    }

    public function down()
    {
        if ($this->db->tableExists('owner_notification_settings')) {
            $this->forge->dropTable('owner_notification_settings', true);
        }
    }
}
