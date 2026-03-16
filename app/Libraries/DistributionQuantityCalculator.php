<?php

namespace App\Libraries;

class DistributionQuantityCalculator
{
    public static function normalizeQtyMode(?string $qtyMode): string
    {
        $mode = strtolower(trim((string) $qtyMode));
        if (in_array($mode, ['batch', 'box', 'pieces'], true)) {
            return $mode;
        }

        return 'batch';
    }

    public static function calculateDistributionMetrics($quantity, ?string $qtyMode, ?array $product, ?array $costData): array
    {
        $normalizedMode = self::normalizeQtyMode($qtyMode);
        $normalizedQty  = max(0.0, (float) $quantity);
        $category       = self::normalizeCategory($product);
        $traysPerYield  = self::positiveInt($costData['trays_per_yield'] ?? 0);
        $piecesPerUnit  = self::positiveInt($costData['pieces_per_yield'] ?? 0);
        $batchPieces    = self::getBatchPiecesPerYield($product, $costData);
        $boxPieces      = self::getBoxPieces($product, $costData);

        if ($normalizedMode === 'pieces') {
            $pieces     = $normalizedQty;
            $yieldUnits = self::calculateYieldUnitsFromPieces($pieces, $product, $costData);
        } elseif (in_array($category, ['drinks', 'grocery'], true)) {
            $pieces     = $normalizedQty;
            $yieldUnits = $normalizedQty;
        } elseif ($normalizedMode === 'box') {
            $pieces = $normalizedQty * $boxPieces;

            if ($traysPerYield > 0) {
                $yieldUnits = $normalizedQty / $traysPerYield;
            } else {
                $yieldUnits = self::calculateYieldUnitsFromPieces($pieces, $product, $costData);
            }
        } else {
            $pieces     = $normalizedQty * $batchPieces;
            $yieldUnits = $normalizedQty;
        }

        return [
            'qty_mode'         => $normalizedMode,
            'quantity'         => $normalizedQty,
            'pieces'           => (int) round($pieces),
            'pieces_exact'     => $pieces,
            'yield_units'      => $yieldUnits,
            'batch_pieces'     => $batchPieces,
            'box_pieces'       => $boxPieces,
            'trays_per_yield'  => $traysPerYield,
            'pieces_per_unit'  => $piecesPerUnit > 0 ? $piecesPerUnit : 1,
            'category'         => $category,
            'unit_price'       => self::resolveUnitPrice($normalizedMode, $costData),
        ];
    }

    public static function calculatePieceMetrics($pieces, ?array $product, ?array $costData): array
    {
        $normalizedPieces = max(0.0, (float) $pieces);

        return [
            'pieces'       => (int) round($normalizedPieces),
            'pieces_exact' => $normalizedPieces,
            'yield_units'  => self::calculateYieldUnitsFromPieces($normalizedPieces, $product, $costData),
            'batch_pieces' => self::getBatchPiecesPerYield($product, $costData),
            'category'     => self::normalizeCategory($product),
        ];
    }

    public static function getBatchPiecesPerYield(?array $product, ?array $costData): int
    {
        $category      = self::normalizeCategory($product);
        $traysPerYield = self::positiveInt($costData['trays_per_yield'] ?? 0);
        $piecesPerUnit = self::positiveInt($costData['pieces_per_yield'] ?? 0);

        if (in_array($category, ['drinks', 'grocery'], true)) {
            return 1;
        }

        if ($traysPerYield > 0 && $piecesPerUnit > 0) {
            return $traysPerYield * $piecesPerUnit;
        }

        if ($piecesPerUnit > 0) {
            return $piecesPerUnit;
        }

        return 1;
    }

    public static function getBoxPieces(?array $product, ?array $costData): int
    {
        $category      = self::normalizeCategory($product);
        $piecesPerUnit = self::positiveInt($costData['pieces_per_yield'] ?? 0);

        if (in_array($category, ['drinks', 'grocery'], true)) {
            return 1;
        }

        if ($piecesPerUnit > 0) {
            return $piecesPerUnit;
        }

        return self::getBatchPiecesPerYield($product, $costData);
    }

    private static function calculateYieldUnitsFromPieces(float $pieces, ?array $product, ?array $costData): float
    {
        $category = self::normalizeCategory($product);
        if (in_array($category, ['drinks', 'grocery'], true)) {
            return $pieces;
        }

        $batchPieces = max(1, self::getBatchPiecesPerYield($product, $costData));

        return $pieces / $batchPieces;
    }

    private static function resolveUnitPrice(string $qtyMode, ?array $costData): float
    {
        if ($qtyMode === 'pieces') {
            return self::firstPositiveFloat([
                $costData['selling_price_per_piece'] ?? 0,
                $costData['selling_price'] ?? 0,
                $costData['selling_price_per_tray'] ?? 0,
            ]);
        }

        if ($qtyMode === 'box') {
            return self::firstPositiveFloat([
                $costData['selling_price_per_tray'] ?? 0,
                $costData['selling_price'] ?? 0,
                $costData['selling_price_per_piece'] ?? 0,
            ]);
        }

        return self::firstPositiveFloat([
            $costData['selling_price'] ?? 0,
            $costData['selling_price_per_tray'] ?? 0,
            $costData['selling_price_per_piece'] ?? 0,
        ]);
    }

    private static function firstPositiveFloat(array $values): float
    {
        foreach ($values as $value) {
            $parsed = (float) $value;
            if ($parsed > 0) {
                return $parsed;
            }
        }

        return 0.0;
    }

    private static function positiveInt($value): int
    {
        $parsed = (int) round((float) $value);
        return $parsed > 0 ? $parsed : 0;
    }

    private static function normalizeCategory(?array $product): string
    {
        return strtolower(trim((string) ($product['category'] ?? '')));
    }
}