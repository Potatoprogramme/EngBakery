<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id',
        'target_roles',
        'title',
        'message',
        'type',
        'level',
        'action_url',
        'is_read',
        'read_at',
        'reference_id',
        'reference_type',
        'created_at',
        'expires_at',
    ];

    // ═══════════════════════════════════════════
    //  FETCH NOTIFICATIONS FOR A USER
    // ═══════════════════════════════════════════

    /**
     * Get notifications visible to a specific user based on their role.
     * Includes:
     *  - Direct notifications (user_id = this user)
     *  - Broadcast notifications (user_id IS NULL) where target_roles includes user's role
     *
     * @param int    $userId       Current user's ID
     * @param string $employeeType Current user's role (owner|admin|staff)
     * @param int    $limit        Max notifications to return
     * @param bool   $unreadOnly   If true, only return unread
     * @return array
     */
    public function getForUser(int $userId, string $employeeType, int $limit = 50, bool $unreadOnly = false): array
    {
        $builder = $this->db->table('notifications n');
        $builder->select('n.*, 
            CASE 
                WHEN n.user_id IS NOT NULL THEN n.is_read
                WHEN nr.read_id IS NOT NULL THEN 1
                ELSE 0
            END as is_read_by_user', false);
        $builder->join('notification_reads nr', "nr.notification_id = n.notification_id AND nr.user_id = {$userId}", 'left');

        // Either direct to this user OR broadcast matching their role
        $builder->groupStart();
        $builder->where('n.user_id', $userId);
        $builder->orGroupStart();
        $builder->where('n.user_id IS NULL', null, false);
        $builder->like('n.target_roles', $employeeType);
        $builder->groupEnd();
        $builder->groupEnd();

        // Exclude expired
        $builder->groupStart();
        $builder->where('n.expires_at IS NULL', null, false);
        $builder->orWhere('n.expires_at >', date('Y-m-d H:i:s'));
        $builder->groupEnd();

        if ($unreadOnly) {
            $builder->groupStart();
            // Direct: not read
            $builder->groupStart();
            $builder->where('n.user_id IS NOT NULL', null, false);
            $builder->where('n.is_read', 0);
            $builder->groupEnd();
            // Broadcast: no read entry
            $builder->orGroupStart();
            $builder->where('n.user_id IS NULL', null, false);
            $builder->where('nr.read_id IS NULL', null, false);
            $builder->groupEnd();
            $builder->groupEnd();
        }

        $builder->orderBy('n.created_at', 'DESC');
        $builder->limit($limit);

        return $builder->get()->getResultArray();
    }

    /**
     * Count unread notifications for a user
     *
     * @param int    $userId
     * @param string $employeeType
     * @return int
     */
    public function countUnreadForUser(int $userId, string $employeeType): int
    {
        $builder = $this->db->table('notifications n');
        $builder->selectCount('n.notification_id', 'unread_count');
        $builder->join('notification_reads nr', "nr.notification_id = n.notification_id AND nr.user_id = {$userId}", 'left');

        // Either direct to this user OR broadcast matching their role
        $builder->groupStart();
        $builder->where('n.user_id', $userId);
        $builder->orGroupStart();
        $builder->where('n.user_id IS NULL', null, false);
        $builder->like('n.target_roles', $employeeType);
        $builder->groupEnd();
        $builder->groupEnd();

        // Exclude expired
        $builder->groupStart();
        $builder->where('n.expires_at IS NULL', null, false);
        $builder->orWhere('n.expires_at >', date('Y-m-d H:i:s'));
        $builder->groupEnd();

        // Unread only
        $builder->groupStart();
        $builder->groupStart();
        $builder->where('n.user_id IS NOT NULL', null, false);
        $builder->where('n.is_read', 0);
        $builder->groupEnd();
        $builder->orGroupStart();
        $builder->where('n.user_id IS NULL', null, false);
        $builder->where('nr.read_id IS NULL', null, false);
        $builder->groupEnd();
        $builder->groupEnd();

        $row = $builder->get()->getRowArray();
        return (int)($row['unread_count'] ?? 0);
    }

    // ═══════════════════════════════════════════
    //  MARK AS READ
    // ═══════════════════════════════════════════

    /**
     * Mark a single notification as read for a user.
     * Handles both direct and broadcast notifications.
     *
     * @param int $notificationId
     * @param int $userId
     */
    public function markAsRead(int $notificationId, int $userId): void
    {
        $notification = $this->find($notificationId);
        if (!$notification) return;

        if ($notification['user_id'] !== null) {
            // Direct notification — update the row
            $this->update($notificationId, [
                'is_read' => 1,
                'read_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            // Broadcast — insert into notification_reads
            $this->db->table('notification_reads')->ignore(true)->insert([
                'notification_id' => $notificationId,
                'user_id'         => $userId,
                'read_at'         => date('Y-m-d H:i:s'),
            ]);
        }
    }

    /**
     * Mark ALL notifications as read for a user
     *
     * @param int    $userId
     * @param string $employeeType
     */
    public function markAllAsRead(int $userId, string $employeeType): void
    {
        // Direct notifications
        $this->where('user_id', $userId)
             ->where('is_read', 0)
             ->set(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')])
             ->update();

        // Broadcast notifications — insert reads for any unread
        $unreadBroadcasts = $this->db->table('notifications n')
            ->select('n.notification_id')
            ->join('notification_reads nr', "nr.notification_id = n.notification_id AND nr.user_id = {$userId}", 'left')
            ->where('n.user_id IS NULL', null, false)
            ->like('n.target_roles', $employeeType)
            ->where('nr.read_id IS NULL', null, false)
            ->groupStart()
            ->where('n.expires_at IS NULL', null, false)
            ->orWhere('n.expires_at >', date('Y-m-d H:i:s'))
            ->groupEnd()
            ->get()
            ->getResultArray();

        foreach ($unreadBroadcasts as $row) {
            $this->db->table('notification_reads')->ignore(true)->insert([
                'notification_id' => $row['notification_id'],
                'user_id'         => $userId,
                'read_at'         => date('Y-m-d H:i:s'),
            ]);
        }
    }

    // ═══════════════════════════════════════════
    //  CREATE NOTIFICATIONS
    // ═══════════════════════════════════════════

    /**
     * Create a broadcast notification (no specific user_id, visible to target roles)
     */
    public function createBroadcast(string $title, string $message, string $type, string $level, string $targetRoles, ?string $actionUrl = null, ?int $referenceId = null, ?string $referenceType = null, ?string $expiresAt = null): int
    {
        return $this->insert([
            'user_id'        => null,
            'target_roles'   => $targetRoles,
            'title'          => $title,
            'message'        => $message,
            'type'           => $type,
            'level'          => $level,
            'action_url'     => $actionUrl,
            'reference_id'   => $referenceId,
            'reference_type' => $referenceType,
            'expires_at'     => $expiresAt,
        ]);
    }

    /**
     * Create a direct notification for a specific user
     */
    public function createDirect(int $userId, string $title, string $message, string $type, string $level, ?string $actionUrl = null, ?int $referenceId = null, ?string $referenceType = null): int
    {
        $userModel = new \App\Models\UsersModel();
        $user = $userModel->find($userId);
        $targetRole = $user ? $user['employee_type'] : 'staff';

        return $this->insert([
            'user_id'        => $userId,
            'target_roles'   => $targetRole,
            'title'          => $title,
            'message'        => $message,
            'type'           => $type,
            'level'          => $level,
            'action_url'     => $actionUrl,
            'reference_id'   => $referenceId,
            'reference_type' => $referenceType,
        ]);
    }

    /**
     * Check if a similar notification already exists today (deduplication)
     */
    public function existsToday(string $type, ?int $referenceId = null, ?string $referenceType = null): bool
    {
        $builder = $this->db->table($this->table);
        $builder->where('type', $type);
        $builder->where("DATE(created_at) = '" . date('Y-m-d') . "'", null, false);

        if ($referenceId !== null) {
            $builder->where('reference_id', $referenceId);
        }
        if ($referenceType !== null) {
            $builder->where('reference_type', $referenceType);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Clean up old notifications (older than X days)
     */
    public function cleanup(int $daysOld = 30): int
    {
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$daysOld} days"));

        // Delete read entries first (FK constraint)
        $this->db->table('notification_reads')
            ->whereIn('notification_id', function ($subquery) use ($cutoff) {
                return $subquery->select('notification_id')
                    ->from('notifications')
                    ->where('created_at <', $cutoff);
            })
            ->delete();

        return $this->where('created_at <', $cutoff)->delete();
    }
}
