<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use App\Libraries\NotificationGenerator;

class NotificationController extends BaseController
{

    /**
     * AJAX: Get notifications for the current user.
     * Returns JSON array.
     */
    public function getNotifications()
    {
        if ($redirect = $this->redirectIfNotLoggedIn()) return $redirect;

        $session = $this->getSessionData();
        $userId = (int)$session['user_id'];
        $role = $session['employee_type'];

        $notifications = $this->notificationModel->getForUser($userId, $role, 30);

        // Format timestamps for display
        foreach ($notifications as &$n) {
            $n['time_ago'] = $this->timeAgo($n['created_at']);
            $n['is_read_by_user'] = (int)$n['is_read_by_user'];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $notifications,
        ]);
    }

    /**
     * AJAX: Get unread count only (lightweight polling endpoint).
     */
    public function getUnreadCount()
    {
        if ($redirect = $this->redirectIfNotLoggedIn()) return $redirect;

        $session = $this->getSessionData();
        $userId = (int)$session['user_id'];
        $role = $session['employee_type'];

        $count = $this->notificationModel->countUnreadForUser($userId, $role);

        return $this->response->setJSON([
            'status' => 'success',
            'count'  => $count,
        ]);
    }

    /**
     * AJAX: Mark a single notification as read.
     */
    public function markAsRead()
    {
        if ($redirect = $this->redirectIfNotLoggedIn()) return $redirect;

        $notificationId = (int)$this->request->getGet('id');
        $session = $this->getSessionData();
        $userId = (int)$session['user_id'];

        if ($notificationId <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid notification ID']);
        }

        $this->notificationModel->markAsRead($notificationId, $userId);

        return $this->response->setJSON(['status' => 'success']);
    }

    /**
     * AJAX: Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        if ($redirect = $this->redirectIfNotLoggedIn()) return $redirect;

        $session = $this->getSessionData();
        $userId = (int)$session['user_id'];
        $role = $session['employee_type'];

        $this->notificationModel->markAllAsRead($userId, $role);

        return $this->response->setJSON(['status' => 'success']);
    }

    /**
     * AJAX: Trigger notification generation (called on page load).
     * Lightweight — checks are deduplicated per day so it won't flood.
     */
    public function generate()
    {
        log_message('debug', '[NotifCtrl] generate() called');

        if ($redirect = $this->redirectIfNotLoggedIn()) {
            log_message('debug', '[NotifCtrl] generate() - not logged in, redirecting');
            return $redirect;
        }

        // Any logged-in user can trigger generation.
        // Role-based visibility is handled inside NotificationGenerator (target_roles).
        $session = $this->getSessionData();
        log_message('debug', '[NotifCtrl] generate() - employee_type: ' . ($session['employee_type'] ?? 'NULL'));

        try {
            log_message('debug', '[NotifCtrl] generate() - calling NotificationGenerator::generateAll()');
            $generator = new NotificationGenerator();
            $generator->generateAll();

            log_message('debug', '[NotifCtrl] generate() - generateAll() completed successfully');
            return $this->response->setJSON(['status' => 'success']);
        } catch (\Exception $e) {
            log_message('error', '[NotifCtrl] Notification generation failed: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * AJAX: Clean up old notifications (call periodically or from admin panel).
     */
    public function cleanup()
    {
        if ($redirect = $this->redirectIfNotLoggedIn()) return $redirect;
        if ($redirect = $this->redirectIfNotOwnerAndAdmin()) return $redirect;

        $deleted = $this->notificationModel->cleanup(30);

        return $this->response->setJSON([
            'status'  => 'success',
            'deleted' => $deleted,
        ]);
    }

    /**
     * Convert a timestamp to a human-readable "time ago" string.
     */
    private function timeAgo(string $datetime): string
    {
        $now = new \DateTime();
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
        if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
        if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
        if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
        if ($diff->i > 0) return $diff->i . ' min' . ($diff->i > 1 ? 's' : '') . ' ago';
        return 'Just now';
    }
}
