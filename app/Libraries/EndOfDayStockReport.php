<?php

namespace App\Libraries;

use App\Models\DailyStockItemsModel;
use App\Models\DailyStockModel;
use App\Models\ProductRecipeModel;
use App\Models\RawMaterialsModel;
use App\Models\UsersModel;

class EndOfDayStockReport
{
    /**
     * Returns whether system-wide inventory data exists for the provided date.
     *
     * @return array<string, mixed>
     */
    public static function getAvailabilityForDate(string $date): array
    {
        $reportDate = trim((string) $date);
        if ($reportDate === '') {
            $reportDate = date('Y-m-d');
        }

        $dailyStockModel = new DailyStockModel();
        $inventories = $dailyStockModel->getInventoriesByDate($reportDate);

        if (empty($inventories)) {
            return [
                'success' => true,
                'data' => [
                    'has_manual_changes' => false,
                    'report_date' => $reportDate,
                    'first_change_at' => null,
                    'total_changes' => 0,
                    'total_items' => 0,
                ],
            ];
        }

        $itemsModel = new DailyStockItemsModel();
        $totalItems = 0;
        $firstInventoryAt = null;

        foreach ($inventories as $inventory) {
            $inventoryId = intval($inventory['daily_stock_id'] ?? 0);
            if ($inventoryId <= 0) {
                continue;
            }

            $items = $itemsModel->fetchAllStockItems($inventoryId);
            $totalItems += count($items);

            if ($firstInventoryAt === null) {
                $firstInventoryAt = (string) ($inventory['time_start'] ?? $inventory['inventory_date'] ?? $reportDate);
            }
        }

        return [
            'success' => true,
            'data' => [
                'has_manual_changes' => $totalItems > 0,
                'report_date' => $reportDate,
                'first_change_at' => $firstInventoryAt,
                'total_changes' => $totalItems,
                'total_items' => $totalItems,
            ],
        ];
    }

    /**
     * Sends the system-wide end-of-day stock report for the given date.
     *
     * This report aggregates all inventory rows recorded on the selected date,
     * across every inventory shift for that day.
     *
     * @return array<string, mixed>
     */
    public static function sendReportForDate(string $date, ?string $until = null): array
    {
        $reportDate = trim((string) $date);
        if ($reportDate === '') {
            $reportDate = date('Y-m-d');
        }

        $dailyStockModel = new DailyStockModel();
        $inventories = $dailyStockModel->getInventoriesByDate($reportDate);

        if (empty($inventories)) {
            return [
                'success' => false,
                'message' => 'No inventory data exists for the selected date yet. Create or close inventory first.',
            ];
        }

        $itemsModel = new DailyStockItemsModel();
        $productSummaries = [];

        foreach ($inventories as $inventory) {
            $inventoryId = intval($inventory['daily_stock_id'] ?? 0);
            if ($inventoryId <= 0) {
                continue;
            }

            $items = $itemsModel->fetchAllStockItems($inventoryId);
            foreach ($items as $item) {
                $productId = intval($item['product_id'] ?? 0);
                if ($productId <= 0) {
                    continue;
                }

                if (!isset($productSummaries[$productId])) {
                    $productSummaries[$productId] = [
                        'product_id' => $productId,
                        'product_name' => (string) ($item['product_name'] ?? 'Unknown Product'),
                        'category' => (string) ($item['category'] ?? 'Uncategorized'),
                        'beginning_stock' => 0,
                        'added_qty' => 0,
                        'pull_out_quantity' => 0,
                        'distributed_out_qty' => 0,
                        'ending_stock' => 0,
                        'estimated_sold' => 0,
                    ];
                }

                $summary = &$productSummaries[$productId];
                $summary['beginning_stock'] += intval($item['beginning_stock'] ?? 0);
                $summary['added_qty'] += intval($item['added_qty'] ?? 0);
                $summary['pull_out_quantity'] += intval($item['pull_out_quantity'] ?? 0);
                $summary['distributed_out_qty'] += intval($item['distributed_out_qty'] ?? 0);
                $summary['ending_stock'] += intval($item['ending_stock'] ?? 0);
                $summary['estimated_sold'] += max(0, intval($item['beginning_stock'] ?? 0) + intval($item['added_qty'] ?? 0) - intval($item['pull_out_quantity'] ?? 0) - intval($item['distributed_out_qty'] ?? 0) - intval($item['ending_stock'] ?? 0));
                unset($summary);
            }
        }

        if (empty($productSummaries)) {
            return [
                'success' => false,
                'message' => 'No stock items were found for the selected date.',
            ];
        }

        $productSummaries = array_values($productSummaries);

        $productSalesById = self::getProductSalesByDate($reportDate);
        foreach ($productSummaries as &$summary) {
            $productId = intval($summary['product_id'] ?? 0);
            if ($productId <= 0) {
                continue;
            }

            $actualSoldQty = intval($productSalesById[$productId] ?? 0);
            if ($actualSoldQty > intval($summary['estimated_sold'] ?? 0)) {
                $summary['estimated_sold'] = $actualSoldQty;
            }
        }
        unset($summary);

        usort($productSummaries, static function (array $a, array $b): int {
            $categoryCompare = strcmp((string) ($a['category'] ?? ''), (string) ($b['category'] ?? ''));
            if ($categoryCompare !== 0) {
                return $categoryCompare;
            }

            return strcmp((string) ($a['product_name'] ?? ''), (string) ($b['product_name'] ?? ''));
        });

        $materialUsage = self::buildMaterialUsageByCategory($productSummaries);

        $ownerEmails = self::resolveOwnerEmails();
        if (empty($ownerEmails)) {
            return [
                'success' => false,
                'message' => 'No owner/admin recipients currently have inventory notifications enabled.',
            ];
        }

        $subject = 'All-Day Stock Report — ' . date('F d, Y', strtotime($reportDate));
        $body = self::buildEmailBody($productSummaries, $materialUsage, $reportDate);

        try {
            $emailService = \Config\Services::email();
            $emailService->setFrom('noreply@engbakery.com', "E n' G Bakery - Deca Sentrio");
            $emailService->setTo($ownerEmails);
            $emailService->setSubject($subject);
            $emailService->setMessage($body);
            $emailService->setMailType('html');

            if (!$emailService->send()) {
                return [
                    'success' => false,
                    'message' => 'Failed to send All-Day Stock Report.',
                ];
            }

            return [
                'success' => true,
                'message' => 'All-Day Stock Report sent successfully.',
                'recipients' => $ownerEmails,
                'report_date' => $reportDate,
                'first_change_at' => $inventories[0]['time_start'] ?? $reportDate,
                'end_at' => $until ?? date('Y-m-d H:i:s'),
            ];
        } catch (\Throwable $e) {
            log_message('error', 'EndOfDayStockReport send exception: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Exception while sending All-Day Stock Report: ' . $e->getMessage(),
            ];
        }
    }

    private static function resolveOwnerEmails(): array
    {
        $usersModel = new UsersModel();
        $recipients = $usersModel
            ->whereIn('employee_type', ['owner', 'admin'])
            ->where('approved', 1)
            ->findAll();

        if (empty($recipients)) {
            return [];
        }

        return OwnerNotificationPreferences::resolveEmailsForType(
            $recipients,
            OwnerNotificationPreferences::TYPE_INVENTORY
        );
    }

    private static function buildEmailBody(array $productSummaries, array $materialUsage, string $reportDate): string
    {
        $reportDisplayDate = date('F d, Y', strtotime($reportDate));

        $categorySections = '';
        $groupedSummaries = [];
        foreach ($productSummaries as $summary) {
            $categoryKey = strtolower(trim((string) ($summary['category'] ?? 'uncategorized')));
            $groupedSummaries[$categoryKey][] = $summary;
        }

        $categoryOrder = ['bakery', 'grocery', 'drinks'];
        foreach ($categoryOrder as $categoryKey) {
            if (!isset($groupedSummaries[$categoryKey])) {
                continue;
            }

            $categoryRows = '';
            foreach ($groupedSummaries[$categoryKey] as $summary) {
                $productName = htmlspecialchars((string) ($summary['product_name'] ?? 'Unknown Product'));
                $beginning = self::formatQty((float) ($summary['beginning_stock'] ?? 0));
                $added = self::formatQty((float) ($summary['added_qty'] ?? 0));
                $pullOut = self::formatQty((float) ($summary['pull_out_quantity'] ?? 0));
                $distributed = self::formatQty((float) ($summary['distributed_out_qty'] ?? 0));
                $ending = self::formatQty((float) ($summary['ending_stock'] ?? 0));
                $estimatedSold = self::formatQty((float) ($summary['estimated_sold'] ?? 0));

                $categoryRows .= "
                    <tr>
                        <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;'>{$productName}</td>
                        <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$beginning}</td>
                        <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$added}</td>
                        <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$pullOut}</td>
                        <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$distributed}</td>
                        <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$ending}</td>
                        <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$estimatedSold}</td>
                    </tr>";
            }

            $categoryTitle = self::formatCategoryLabel($categoryKey);
            $categorySections .= "
                <div style='margin-top:20px;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;'>
                    <div style='background:#007B4C;color:#fff;padding:12px 14px;font-size:14px;font-weight:bold;'>" . htmlspecialchars($categoryTitle) . "</div>
                    <div style='overflow-x:auto;'>
                        <table style='background:#fff;border-collapse:collapse;width:100%;'>
                            <thead>
                                <tr>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;'>Product</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Beginning</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Added</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Pull Out</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Distributed Out</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Ending</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Sold Qty</th>
                                </tr>
                            </thead>
                            <tbody>{$categoryRows}</tbody>
                        </table>
                    </div>
                </div>";
        }

        foreach ($groupedSummaries as $categoryKey => $items) {
            if (in_array($categoryKey, $categoryOrder, true)) {
                continue;
            }

            $categoryRows = '';
            foreach ($items as $summary) {
                $productName = htmlspecialchars((string) ($summary['product_name'] ?? 'Unknown Product'));
                $beginning = self::formatQty((float) ($summary['beginning_stock'] ?? 0));
                $added = self::formatQty((float) ($summary['added_qty'] ?? 0));
                $pullOut = self::formatQty((float) ($summary['pull_out_quantity'] ?? 0));
                $distributed = self::formatQty((float) ($summary['distributed_out_qty'] ?? 0));
                $ending = self::formatQty((float) ($summary['ending_stock'] ?? 0));
                $estimatedSold = self::formatQty((float) ($summary['estimated_sold'] ?? 0));

                $categoryRows .= "
                    <tr>
                        <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;'>{$productName}</td>
                        <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$beginning}</td>
                        <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$added}</td>
                        <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$pullOut}</td>
                        <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$distributed}</td>
                        <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$ending}</td>
                        <td style='padding:10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$estimatedSold}</td>
                    </tr>";
            }

            $categoryTitle = self::formatCategoryLabel($categoryKey);
            $categorySections .= "
                <div style='margin-top:20px;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;'>
                    <div style='background:#007B4C;color:#fff;padding:12px 14px;font-size:14px;font-weight:bold;'>" . htmlspecialchars($categoryTitle) . "</div>
                    <div style='overflow-x:auto;'>
                        <table style='background:#fff;border-collapse:collapse;width:100%;'>
                            <thead>
                                <tr>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;'>Product</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Beginning</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Added</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Pull Out</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Distributed Out</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Ending</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Sold Qty</th>
                                </tr>
                            </thead>
                            <tbody>{$categoryRows}</tbody>
                        </table>
                    </div>
                </div>";
        }

        $usageByCategoryRows = '';
        $hasAnyRawMaterialUsage = false;
        foreach (($materialUsage['categories'] ?? []) as $categoryName => $categoryData) {
            if (!in_array($categoryName, ['bakery', 'drinks'], true)) {
                continue;
            }

            $materialsRows = '';
            foreach ($categoryData['materials'] as $material) {
                $usedQty = self::formatQty((float) ($material['used_qty'] ?? 0));
                $remainingQty = self::formatQty((float) ($material['remaining_qty'] ?? 0));
                $materialsRows .= "
                    <tr>
                        <td style='padding:9px 10px;border-bottom:1px solid #e5e7eb;font-size:12px;'>{$material['material_name']}</td>
                        <td style='padding:9px 10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$usedQty}</td>
                        <td style='padding:9px 10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$remainingQty}</td>
                        <td style='padding:9px 10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>{$material['unit']}</td>
                        <td style='padding:9px 10px;border-bottom:1px solid #e5e7eb;font-size:12px;text-align:center;'>₱" . number_format((float) ($material['used_cost'] ?? 0), 2) . "</td>
                    </tr>";
            }

            $hasAnyRawMaterialUsage = true;
            $sectionTitle = ucfirst((string) $categoryName) . ' Raw Materials';
            $usageByCategoryRows .= "
                <div style='margin-top:18px; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff;'>
                    <div style='background:#007B4C; color:#fff; padding:12px 14px; font-size:14px; font-weight:bold;'>" . htmlspecialchars((string) $sectionTitle) . "</div>
                    <div style='padding:0;'>
                        <table style='background:#fff;border-collapse:collapse;width:100%;'>
                            <thead>
                                <tr>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;'>Material</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Used Qty</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Remaining Qty</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Unit</th>
                                    <th style='background:#E9F1F9;color:#1f2937;padding:10px;text-align:center;'>Estimated Cost</th>
                                </tr>
                            </thead>
                            <tbody>{$materialsRows}</tbody>
                        </table>
                    </div>
                </div>";
        }

        if (!$hasAnyRawMaterialUsage) {
            $usageByCategoryRows = "
                <div style='margin-top:18px; border:1px solid #e5e7eb; border-radius:12px; padding:18px; background:#fff; color:#4b5563; font-size:13px;'>
                    No bakery or drinks raw material usage was detected for this report date.
                </div>";
        }

        $totalRawMaterials = count($materialUsage['materials'] ?? []);
        $totalRawMaterialCost = number_format((float) ($materialUsage['total_cost'] ?? 0), 2);

        return "
        <html><head><style>
            body { font-family: Arial, sans-serif; color:#1f2937; margin:0; padding:0; background:#f4f7fb; }
            .container { max-width: 1200px; margin:0 auto; padding:20px; }
            .header { background:linear-gradient(135deg, #007B4C 0%, #005A36 100%); color:#fff; padding:22px; border-radius:12px 12px 0 0; border-bottom:4px solid #F7D025; }
            .content { background:#ffffff; padding:22px; border:1px solid #dfe7f0; border-top:none; border-radius:0 0 12px 12px; }
            table { width:100%; border-collapse:collapse; }
            th { background:#007B4C; color:#fff; padding:10px; text-align:left; font-size:12px; }
            .meta { margin:0 0 16px; font-size:13px; color:#4b5563; }
            .stats { display:flex; flex-wrap:wrap; gap:12px; margin:18px 0; }
            .stat { flex:1; min-width:180px; background:linear-gradient(135deg, #ffffff 0%, #f6fbf8 100%); border:1px solid #cfe5d9; border-radius:12px; padding:14px; box-shadow:0 2px 6px rgba(0,123,76,0.06); }
            .stat-label { font-size:11px; font-weight:bold; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em; }
            .stat-value { font-size:22px; font-weight:bold; color:#005A36; margin-top:6px; }
        </style></head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1 style='margin:0;font-size:24px;'>All-Day Stock Report</h1>
                    <p style='margin:6px 0 0;font-size:13px;opacity:.9;'>E n' G Bakery - Deca Sentrio — {$reportDisplayDate}</p>
                </div>
                <div class='content'>
                    <p class='meta'>This report aggregates the full inventory movement across all inventory shifts recorded on {$reportDisplayDate}.</p>

                    <p style='font-size:14px;'>Dear Owner/Admin,</p>
                    <p style='font-size:14px;'>Below is the comprehensive end-of-day stock summary for the bakery operations, including stock movement and the raw materials consumed for the day.</p>

                    <div class='stats'>
                        <div class='stat'>
                            <div class='stat-label'>Products Covered</div>
                            <div class='stat-value'>" . count($productSummaries) . "</div>
                        </div>
                        <div class='stat'>
                            <div class='stat-label'>Raw Materials Used</div>
                            <div class='stat-value'>" . $totalRawMaterials . "</div>
                        </div>
                        <div class='stat'>
                            <div class='stat-label'>Estimated Material Cost</div>
                            <div class='stat-value'>₱" . $totalRawMaterialCost . "</div>
                        </div>
                    </div>

                    <div style='margin-top:18px;'>
                        {$categorySections}
                    </div>

                    <div style='margin-top:28px;'>
                        <h2 style='margin:0 0 12px;font-size:18px;color:#005A36;'>Used Raw Materials by Category</h2>
                        {$usageByCategoryRows}
                    </div>
                </div>
            </div>
        </body></html>";
    }

    private static function getProductSalesByDate(string $date): array
    {
        $db = \Config\Database::connect();
        $rows = $db->query(
            "SELECT dsi.product_id, SUM(t.quantity_sold) AS total_sold
             FROM transactions t
             LEFT JOIN daily_stock_items dsi ON dsi.item_id = t.item_id
             LEFT JOIN products p ON p.product_id = dsi.product_id
             WHERE t.date_created = ?
               AND t.deleted_at IS NULL
               AND (p.deleted_at IS NULL OR p.deleted_at = '')
             GROUP BY dsi.product_id",
            [$date]
        )->getResultArray();

        $mapped = [];
        foreach ($rows as $row) {
            $productId = intval($row['product_id'] ?? 0);
            if ($productId <= 0) {
                continue;
            }

            $mapped[$productId] = intval($row['total_sold'] ?? 0);
        }

        return $mapped;
    }

    private static function formatCategoryLabel(string $category): string
    {
        $normalized = strtolower(trim($category));

        return match ($normalized) {
            'bakery' => 'Bakery',
            'drinks' => 'Drinks',
            'grocery' => 'Sold Groceries',
            default => ucfirst($normalized !== '' ? $normalized : 'Uncategorized'),
        };
    }

    private static function buildMaterialUsageByCategory(array $productSummaries): array
    {
        $recipeModel = new ProductRecipeModel();
        $materialModel = new RawMaterialsModel();

        $materials = [];
        $categories = [];
        $totalCost = 0.0;

        foreach ($productSummaries as $productSummary) {
            $productId = intval($productSummary['product_id'] ?? 0);
            $estimatedSold = max(0, intval($productSummary['estimated_sold'] ?? 0));
            $productCategory = strtolower(trim((string) ($productSummary['category'] ?? 'uncategorized')));

            if (!in_array($productCategory, ['bakery', 'drinks'], true)) {
                continue;
            }

            if ($productId <= 0 || $estimatedSold <= 0) {
                continue;
            }

            $recipeRows = $recipeModel->getRecipeWithMaterialDetails($productId);
            if (empty($recipeRows)) {
                continue;
            }

            foreach ($recipeRows as $recipeRow) {
                $materialId = intval($recipeRow['material_id'] ?? 0);
                if ($materialId <= 0) {
                    continue;
                }

                $materialMeta = $materialModel->getMaterialById($materialId);
                $quantityNeeded = floatval($recipeRow['quantity_needed'] ?? 0);
                if ($quantityNeeded <= 0) {
                    continue;
                }

                $usedQty = $quantityNeeded * $estimatedSold;
                $unit = trim((string) ($materialMeta['unit'] ?? $recipeRow['unit'] ?? ''));
                $materialName = trim((string) ($materialMeta['material_name'] ?? $recipeRow['material_name'] ?? 'Unknown Material'));
                $remainingQty = floatval($materialMeta['material_quantity'] ?? 0);
                $costPerUnit = floatval($materialMeta['cost_per_unit'] ?? $recipeRow['cost_per_unit'] ?? 0);
                $usedCost = $usedQty * $costPerUnit;

                if (!isset($materials[$materialId])) {
                    $materials[$materialId] = [
                        'material_id' => $materialId,
                        'material_name' => $materialName,
                        'unit' => $unit,
                        'used_qty' => 0.0,
                        'remaining_qty' => $remainingQty,
                        'used_cost' => 0.0,
                        'category_name' => $productCategory,
                    ];
                }

                $materials[$materialId]['used_qty'] += $usedQty;
                $materials[$materialId]['used_cost'] += $usedCost;
                $totalCost += $usedCost;

                if (!isset($categories[$productCategory])) {
                    $categories[$productCategory] = [
                        'category_name' => $productCategory,
                        'materials' => [],
                    ];
                }

                $categoryMaterials = &$categories[$productCategory]['materials'];
                if (!isset($categoryMaterials[$materialId])) {
                    $categoryMaterials[$materialId] = [
                        'material_id' => $materialId,
                        'material_name' => $materialName,
                        'unit' => $unit,
                        'used_qty' => 0.0,
                        'remaining_qty' => $remainingQty,
                        'used_cost' => 0.0,
                    ];
                }

                $categoryMaterials[$materialId]['used_qty'] += $usedQty;
                $categoryMaterials[$materialId]['used_cost'] += $usedCost;
                unset($categoryMaterials);
            }
        }

        foreach ($categories as $categoryName => &$categoryData) {
            $categoryData['materials'] = array_values($categoryData['materials']);
            usort($categoryData['materials'], static function (array $a, array $b): int {
                return strcmp((string) ($a['material_name'] ?? ''), (string) ($b['material_name'] ?? ''));
            });
        }
        unset($categoryData);

        $materials = array_values($materials);
        usort($materials, static function (array $a, array $b): int {
            return strcmp((string) ($a['category_name'] ?? ''), (string) ($b['category_name'] ?? ''));
        });

        return [
            'categories' => $categories,
            'materials' => $materials,
            'total_cost' => $totalCost,
        ];
    }

    private static function formatQty(float $value): string
    {
        if (floor($value) == $value) {
            return number_format($value, 0, '.', ',');
        }

        return number_format($value, 4, '.', ',');
    }
}
