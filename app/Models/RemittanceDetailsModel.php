<?php

namespace App\Models;

use CodeIgniter\Model;

class RemittanceDetailsModel extends Model
{
    protected $table = 'remittance_details';
    protected $primaryKey = 'remittance_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'cashier',
        'outlet_name',
        'remittance_date',
        'shift_start',
        'shift_end',
        'amount_enclosed',
        'total_online_revenue',
        'cash_out',
        'cashout_reason',
        'bakery_sales',
        'coffee_sales',
        'grocery_sales',
        'total_sales',
        'variance_amount',
        'is_short',
    ];

    public function getAllRemittances(): array
    {
        return $this->select("remittance_details.*, CONCAT(users.firstname, ' ', COALESCE(users.middlename, ''), ' ', users.lastname) AS cashier_name")
            ->join('users', 'users.user_id = remittance_details.cashier', 'left')
            ->orderBy('remittance_date', 'DESC')
            ->findAll();
    }

    public function getRemittanceDetails(int $remittanceId): ?array
    {
        return $this->select('remittance_details.*, CONCAT(users.firstname, \' \', COALESCE(users.middlename, \'\'), \' \', users.lastname) AS cashier_name, users.email AS cashier_email')
            ->join('users', 'users.user_id = remittance_details.cashier', 'left')
            ->where('remittance_details.remittance_id', $remittanceId)
            ->first();
    }

    /**
     * Check if a remittance already exists for the given date with an overlapping shift
     * Two shifts overlap if: existing_start < new_end AND existing_end > new_start
     * 
     * @param string $date The date to check (Y-m-d format)
     * @param string $shiftStart The shift start time (H:i:s format)
     * @param string $shiftEnd The shift end time (H:i:s format)
     * @param string|null $outletName Optional outlet name to check
     * @return array|null Returns the existing remittance if found, null otherwise
     */
    public function getExistingRemittanceByDateAndShift(string $date, string $shiftStart, string $shiftEnd, ?string $outletName = null): ?array
    {
        // Check for overlapping shifts on the same date
        // Overlap condition: existing_start < new_end AND existing_end > new_start
        $builder = $this->select('remittance_details.*, CONCAT(users.firstname, \' \', COALESCE(users.middlename, \'\'), \' \', users.lastname) AS cashier_name')
            ->join('users', 'users.user_id = remittance_details.cashier', 'left')
            ->where('DATE(remittance_date)', $date)
            ->where('shift_start <', $shiftEnd)
            ->where('shift_end >', $shiftStart);

        if ($outletName !== null) {
            $builder->where('outlet_name', $outletName);
        }

        return $builder->first();
    }

    /**
     * Get all remittances for a specific date
     * Used to determine which time slots are already occupied
     * 
     * @param string $date The date to check (Y-m-d format)
     * @param string|null $outletName Optional outlet name to filter by
     * @return array Returns all remittances for the given date
     */
    public function getRemittancesByDate(string $date, ?string $outletName = null): array
    {
        $builder = $this->select('remittance_details.*, CONCAT(users.firstname, \' \', COALESCE(users.middlename, \'\'), \' \', users.lastname) AS cashier_name')
            ->join('users', 'users.user_id = remittance_details.cashier', 'left')
            ->where('DATE(remittance_date)', $date)
            ->orderBy('shift_start', 'ASC');

        if ($outletName !== null) {
            $builder->where('outlet_name', $outletName);
        }

        return $builder->findAll();
    }

    /**
     * Delete a remittance and its related records
     * 
     * @param int $remittanceId The remittance ID to delete
     * @return bool Returns true if deletion was successful
     */
    public function deleteRemittance(int $remittanceId): bool
    {
        return $this->delete($remittanceId);
    }
}
