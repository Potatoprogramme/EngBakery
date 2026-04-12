<?php

namespace App\Libraries;

use App\Models\NotificationModel;
use App\Models\RawMaterialStockModel;
use App\Models\RemittanceDetailsModel;
use App\Models\DailyStockModel;
use App\Models\DistributionGroupModel;
use App\Models\OrderModel;

/**
 * NotificationGenerator
 * 
 * Centralized service that checks the system state and generates
 * in-app notifications for:
 *   - Low stock alerts (raw materials)
 *   - Missed remittance (sales without remittance)
 *   - Distribution events (new distributions, no distribution today)
 *   - Pending user approvals
 * 
 * Role targeting:
 *   - owner      → sees everything
 *   - admin      → sees everything except some owner-only financials
 *   - staff      → sees only their own missed remittances + low stock warnings
 */
class NotificationGenerator
{
    protected NotificationModel $notifModel;

    public function __construct()
    {
        $this->notifModel = new NotificationModel();
    }

    /**
     * Run ALL notification checks in one call.
     * Intended to be called on every page load via BaseController (lightweight),
     * or via a scheduled cron job.
     */
    public function generateAll(): void
    {
        log_message('debug', '[NotifGen] generateAll() START');
        $this->checkLowStock();
        $this->checkMissedRemittance();
        $this->checkNoDistributionToday();
        $this->checkPendingApprovals();
        log_message('debug', '[NotifGen] generateAll() END');
    }

    // ═══════════════════════════════════════════
    //  LOW STOCK (Raw Materials)
    // ═══════════════════════════════════════════

    /**
     * Check for raw materials running low.
     * Creates notifications for owner+admin (critical) or warning levels.
     */
    public function checkLowStock(float $criticalPercent = 25, float $warningPercent = 40): void
    {
        log_message('debug', '[NotifGen] checkLowStock() START');
        $stockModel = new RawMaterialStockModel();

        // Use existing method from your RawMaterialStockModel
        $lowStockItems = $stockModel->getLowStockMaterials($criticalPercent, $warningPercent);

        log_message('debug', '[NotifGen] checkLowStock() found ' . count($lowStockItems) . ' low stock item(s)');

        if (empty($lowStockItems)) {
            return;
        }

        foreach ($lowStockItems as $item) {
            $materialId = $item['material_id'];
            $status = $item['stock_status']; // 'critical' or 'warning'
            $remaining = round(floatval($item['current_quantity']), 2);
            $pct = $item['stock_percentage'] ?? 0;
            $unit = $item['unit'] ?? '';

            // Deduplicate: don't create the same alert again today
            $dedupKey = 'raw_material_' . $status;
            $alreadyExists = $this->notifModel->existsToday('low_stock', $materialId, $dedupKey);
            log_message('debug', "[NotifGen] checkLowStock() material_id={$materialId} status={$status} existsToday={$dedupKey}: " . ($alreadyExists ? 'YES (skip)' : 'NO (create)'));

            if ($alreadyExists) {
                continue;
            }

            $level = ($status === 'critical') ? 'critical' : 'warning';

            $title = "{$item['material_name']} — {$pct}% remaining";
            $message = "{$item['material_name']} ({$item['category_name']}) is at {$remaining} {$unit} ({$pct}% of initial stock). Please restock soon.";

            // Critical → all roles; Warning → owner+admin only
            $targetRoles = ($status === 'critical') ? 'owner,admin,staff' : 'owner,admin';

            log_message('debug', "[NotifGen] checkLowStock() INSERTING notification for material_id={$materialId} level={$level} title={$title}");

            $insertResult = $this->notifModel->createBroadcast(
                $title,
                $message,
                'low_stock',
                $level,
                $targetRoles,
                base_url('MaterialStock'),    // Link to material stock page
                $materialId,
                'raw_material_' . $status,
                date('Y-m-d 23:59:59')        // Expires end of day
            );

            log_message('debug', "[NotifGen] checkLowStock() insert result: " . var_export($insertResult, true));

            if (!$insertResult) {
                $errors = $this->notifModel->errors();
                log_message('error', "[NotifGen] checkLowStock() INSERT FAILED. Model errors: " . json_encode($errors));
                $dbError = $this->notifModel->db->error();
                log_message('error', "[NotifGen] checkLowStock() DB error: " . json_encode($dbError));
            }
        }
        log_message('debug', '[NotifGen] checkLowStock() END');
    }

    // ═══════════════════════════════════════════
    //  MISSED REMITTANCE
    // ═══════════════════════════════════════════

    /**
     * Check if there are sales transactions from yesterday
     * that have no corresponding remittance filed.
     * 
     * Notifies:
     *  - owner+admin: "No remittance filed for [date]"
     *  - individual staff who was the cashier: personal reminder
     */
    public function checkMissedRemittance(): void
    {
        $db = \Config\Database::connect();
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        // Check if there were any orders yesterday
        $orderCount = $db->table('orders')
            ->where('date_created', $yesterday)
            ->countAllResults();

        if ($orderCount === 0) {
            return; // No sales yesterday, no remittance expected
        }

        // Check if there is a remittance for yesterday
        $remittanceModel = new RemittanceDetailsModel();
        $existingRemittance = $remittanceModel->where("DATE(remittance_date) = '{$yesterday}'", null, false)->findAll();

        if (!empty($existingRemittance)) {
            return; // Remittance already filed
        }

        // Deduplicate
        if ($this->notifModel->existsToday('missed_remittance', null, 'date_' . $yesterday)) {
            return;
        }

        $formattedDate = date('M d, Y', strtotime($yesterday));

        // Broadcast to owner + admin
        $this->notifModel->createBroadcast(
            "Missed Remittance — {$formattedDate}",
            "There were {$orderCount} order(s) on {$formattedDate} but no remittance has been filed. Please follow up with the cashier on duty.",
            'missed_remittance',
            'warning',
            'owner,admin',
            base_url('Sales'),
            null,
            'date_' . $yesterday
        );

        // Also find the cashier(s) who made orders that day and notify them directly
        $cashiers = $db->table('orders')
            ->select('cashier_name')
            ->where('date_created', $yesterday)
            ->groupBy('cashier_name')
            ->get()
            ->getResultArray();

        foreach ($cashiers as $cashier) {
            // Try to find user by name match
            $nameParts = explode(' ', trim($cashier['cashier_name']));
            if (count($nameParts) >= 2) {
                $firstName = trim($nameParts[0]);
                $lastName = trim(end($nameParts));

                $user = $db->table('users')
                    ->where('firstname', $firstName)
                    ->where('lastname', $lastName)
                    ->where('employee_type', 'staff')
                    ->where('deleted_at', null)
                    ->get()
                    ->getRowArray();

                if ($user) {
                    $this->notifModel->createDirect(
                        (int)$user['user_id'],
                        "Reminder: File your remittance for {$formattedDate}",
                        "You processed {$orderCount} order(s) on {$formattedDate} but haven't filed a remittance yet. Please submit it as soon as possible.",
                        'missed_remittance',
                        'warning',
                        base_url('Sales')
                    );
                }
            }
        }
    }

    // ═══════════════════════════════════════════
    //  DISTRIBUTION
    // ═══════════════════════════════════════════

    /**
     * If today is a weekday and no distribution has been created,
     * remind admin/owner to add today's distribution.
     */
    public function checkNoDistributionToday(): void
    {
        $today = date('Y-m-d');
        $dayOfWeek = date('N'); // 1=Mon, 7=Sun

        // Skip weekends (optional: adjust if bakery operates 7 days)
        if ($dayOfWeek >= 7) {
            return;
        }

        // Deduplicate
        if ($this->notifModel->existsToday('distribution', null, 'no_dist_' . $today)) {
            return;
        }

        $distributionGroupModel = new DistributionGroupModel();

        $todayDist = $distributionGroupModel->where('distribution_date', $today)->findAll();

        if (!empty($todayDist)) {
            return; // Distribution already exists today
        }

        $formattedDate = date('M d, Y');

        $this->notifModel->createBroadcast(
            "No distribution for today ({$formattedDate})",
            "No products have been distributed for today. If the bakery is operating, please add today's distribution to ensure inventory is loaded.",
            'distribution',
            'info',
            'owner,admin',
            base_url('Distribution'),
            null,
            'no_dist_' . $today,
            date('Y-m-d 23:59:59') // Expires end of day
        );
    }

    // ═══════════════════════════════════════════
    //  PENDING APPROVALS (new user registrations)
    // ═══════════════════════════════════════════

    /**
     * Check if there are user accounts awaiting approval.
     * Notifies owner + admin.
     */
    public function checkPendingApprovals(): void
    {
        $db = \Config\Database::connect();

        $pendingCount = $db->table('users')
            ->where('approved', 0)
            ->where('deleted_at', null)
            ->countAllResults();

        if ($pendingCount === 0) {
            return;
        }

        // Deduplicate
        if ($this->notifModel->existsToday('approval', null, 'pending_users')) {
            return;
        }

        $this->notifModel->createBroadcast(
            "{$pendingCount} user(s) pending approval",
            "{$pendingCount} new account(s) are waiting for approval. Review them in the Manage Employee section.",
            'approval',
            'info',
            'owner,admin',
            base_url('ManageEmployee/Approval'),
            null,
            'pending_users',
            date('Y-m-d 23:59:59')
        );
    }

    // ═══════════════════════════════════════════
    //  SHORT REMITTANCE (variance alert)
    // ═══════════════════════════════════════════

    /**
     * Call this after a remittance is saved.
     * If the remittance is short, notify owner immediately.
     * 
     * @param int   $remittanceId
     * @param float $varianceAmount (negative = short)
     * @param string $cashierName
     * @param string $date
     */
    public static function notifyShortRemittance(int $remittanceId, float $varianceAmount, string $cashierName, string $date): void
    {
        if ($varianceAmount >= 0) {
            return; // Not short
        }

        $notifModel = new NotificationModel();
        $shortage = abs($varianceAmount);
        $formattedDate = date('M d, Y', strtotime($date));

        $notifModel->createBroadcast(
            "Short Remittance — ₱" . number_format($shortage, 2),
            "Cashier {$cashierName} filed a remittance for {$formattedDate} with a shortage of ₱" . number_format($shortage, 2) . ". Please review.",
            'missed_remittance',
            'critical',
            'owner,admin',
            base_url("Sales/RemittanceHistory"),
            $remittanceId,
            'short_remittance'
        );
    }

    // ═══════════════════════════════════════════
    //  DISTRIBUTION CREATED (event notification)
    // ═══════════════════════════════════════════

    /**
     * Call after a distribution is added.
     * Notifies staff so they know new products are available.
     *
     * @param string $productName
     * @param int    $quantity
     * @param string $date
     */
    public static function notifyDistributionCreated(string $productName, int $quantity, string $date): void
    {
        $notifModel = new NotificationModel();
        $formattedDate = date('M d, Y', strtotime($date));

        // Notify all roles so staff knows products are loaded
        $notifModel->createBroadcast(
            "Distribution: {$productName} × {$quantity}",
            "{$quantity} unit(s) of {$productName} were distributed for {$formattedDate}.",
            'distribution',
            'info',
            'owner,admin,staff',
            base_url('Distribution'),
            null,
            'dist_event',
            date('Y-m-d 23:59:59', strtotime($date))
        );
    }

    // ═══════════════════════════════════════════
    //  DISTRIBUTION DELETED
    // ═══════════════════════════════════════════

    public static function notifyDistributionDeleted(string $productName, int $quantity, string $date): void
    {
        $notifModel = new NotificationModel();
        $formattedDate = date('M d, Y', strtotime($date));

        $notifModel->createBroadcast(
            "Distribution Deleted: {$productName} × {$quantity}",
            "Distribution of {$quantity} unit(s) of {$productName} for {$formattedDate} was deleted. Raw materials have been restored.",
            'distribution',
            'warning',
            'owner,admin',
            base_url('Distribution'),
            null,
            'dist_deleted'
        );
    }

    // ═══════════════════════════════════════════
    //  DISTRIBUTION UPDATED
    // ═══════════════════════════════════════════

    public static function notifyDistributionUpdated(string $productName, int $oldQty, int $newQty, string $date): void
    {
        $notifModel = new NotificationModel();
        $formattedDate = date('M d, Y', strtotime($date));

        $notifModel->createBroadcast(
            "Distribution Updated: {$productName}",
            "{$productName} distribution for {$formattedDate} changed from {$oldQty} to {$newQty} unit(s).",
            'distribution',
            'info',
            'owner,admin,staff',
            base_url('Distribution'),
            null,
            'dist_updated'
        );
    }

    // ═══════════════════════════════════════════
    //  ORDER VOIDED
    // ═══════════════════════════════════════════

    public static function notifyOrderVoided(int $orderId, float $totalAmount): void
    {
        $notifModel = new NotificationModel();

        $notifModel->createBroadcast(
            "Order #{$orderId} Voided",
            "Order #{$orderId} worth ₱" . number_format($totalAmount, 2) . " has been voided. Stock has been restored.",
            'order',
            'critical',
            'owner,admin',
            base_url('Orders'),
            $orderId,
            'order_voided'
        );
    }

    // ═══════════════════════════════════════════
    //  REMITTANCE FILED
    // ═══════════════════════════════════════════

    public static function notifyRemittanceFiled(int $remittanceId, string $cashierName, float $totalSales, string $date): void
    {
        $notifModel = new NotificationModel();
        $formattedDate = date('M d, Y', strtotime($date));

        $notifModel->createBroadcast(
            "Remittance Filed — ₱" . number_format($totalSales, 2),
            "Cashier {$cashierName} filed a remittance for {$formattedDate}. Total sales: ₱" . number_format($totalSales, 2) . ".",
            'remittance',
            'info',
            'owner,admin',
            base_url('Sales'),
            $remittanceId,
            'remittance_filed'
        );
    }

    // ═══════════════════════════════════════════
    //  REMITTANCE DELETED
    // ═══════════════════════════════════════════

    public static function notifyRemittanceDeleted(int $remittanceId, string $deleterName): void
    {
        $notifModel = new NotificationModel();

        $notifModel->createBroadcast(
            "Remittance #{$remittanceId} Deleted",
            "Remittance #{$remittanceId} was deleted by {$deleterName}. Please verify this action.",
            'remittance',
            'critical',
            'owner,admin',
            base_url('Sales'),
            $remittanceId,
            'remittance_deleted'
        );
    }

    // ═══════════════════════════════════════════
    //  INVENTORY CREATED
    // ═══════════════════════════════════════════

    public static function notifyInventoryCreated(string $date, int $productCount, int $carryoverCount): void
    {
        $notifModel = new NotificationModel();
        $formattedDate = date('M d, Y', strtotime($date));
        $carryMsg = $carryoverCount > 0 ? " ({$carryoverCount} carried over)" : "";

        $notifModel->createBroadcast(
            "Today's Inventory Created",
            "Inventory for {$formattedDate} has been created with {$productCount} product(s){$carryMsg}.",
            'inventory',
            'info',
            'owner,admin,staff',
            base_url('Inventory'),
            null,
            'inventory_created_' . $date,
            date('Y-m-d 23:59:59', strtotime($date))
        );
    }

    // ═══════════════════════════════════════════
    //  INVENTORY DELETED
    // ═══════════════════════════════════════════

    public static function notifyInventoryDeleted(string $date): void
    {
        $notifModel = new NotificationModel();
        $formattedDate = date('M d, Y', strtotime($date));

        $notifModel->createBroadcast(
            "Inventory Deleted for {$formattedDate}",
            "Today's inventory has been deleted. Products are no longer tracked for {$formattedDate}.",
            'inventory',
            'warning',
            'owner,admin',
            base_url('Inventory'),
            null,
            'inventory_deleted_' . $date
        );
    }

    // ═══════════════════════════════════════════
    //  USER APPROVED / REJECTED
    // ═══════════════════════════════════════════

    public static function notifyUserApproved(int $userId, string $approverName): void
    {
        $notifModel = new NotificationModel();

        // Direct notification to the approved user
        $notifModel->createDirect(
            $userId,
            "Account Approved",
            "Your account has been approved by {$approverName}. You now have full access to the system.",
            'user_approval',
            'info',
            base_url('Dashboard')
        );

        // Broadcast to owner/admin
        $notifModel->createBroadcast(
            "User Account Approved",
            "A new staff account (ID #{$userId}) has been approved by {$approverName}.",
            'user_approval',
            'info',
            'owner,admin',
            base_url('ManageEmployee/Approval'),
            $userId,
            'user_approved'
        );
    }

    public static function notifyUserRejected(int $userId, string $rejecterName): void
    {
        $notifModel = new NotificationModel();

        $notifModel->createBroadcast(
            "User Registration Rejected",
            "A user registration (ID #{$userId}) was rejected by {$rejecterName}.",
            'user_approval',
            'warning',
            'owner,admin',
            base_url('ManageEmployee/Approval'),
            $userId,
            'user_rejected'
        );
    }

    // ═══════════════════════════════════════════
    //  RAW MATERIAL RESTOCKED
    // ═══════════════════════════════════════════

    public static function notifyMaterialRestocked(string $materialName, float $quantity, string $unit): void
    {
        $notifModel = new NotificationModel();

        $notifModel->createBroadcast(
            "Material Restocked: {$materialName}",
            "{$materialName} has been restocked with +{$quantity} {$unit}.",
            'raw_material',
            'info',
            'owner,admin',
            base_url('MaterialStock'),
            null,
            'material_restocked'
        );
    }

    // ═══════════════════════════════════════════
    //  RAW MATERIAL DELETED
    // ═══════════════════════════════════════════

    public static function notifyMaterialDeleted(string $materialName): void
    {
        $notifModel = new NotificationModel();

        $notifModel->createBroadcast(
            "Material Deleted: {$materialName}",
            "Raw material '{$materialName}' has been permanently deleted from the system.",
            'raw_material',
            'warning',
            'owner,admin',
            base_url('RawMaterials'),
            null,
            'material_deleted'
        );
    }

    // ═══════════════════════════════════════════
    //  RAW MATERIAL STOCK ENTRY ADDED / DELETED
    // ═══════════════════════════════════════════

    public static function notifyStockEntryAdded(string $materialName, float $initialQty, string $unit): void
    {
        $notifModel = new NotificationModel();

        $notifModel->createBroadcast(
            "Stock Entry Added: {$materialName}",
            "New stock entry for {$materialName}: {$initialQty} {$unit}.",
            'raw_material',
            'info',
            'owner,admin',
            base_url('MaterialStock'),
            null,
            'stock_entry_added'
        );
    }

    public static function notifyStockEntryDeleted(string $materialName): void
    {
        $notifModel = new NotificationModel();

        $notifModel->createBroadcast(
            "Stock Entry Deleted: {$materialName}",
            "A stock entry for '{$materialName}' has been deleted.",
            'raw_material',
            'warning',
            'owner,admin',
            base_url('MaterialStock'),
            null,
            'stock_entry_deleted'
        );
    }
}
