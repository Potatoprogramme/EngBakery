<?php
$json = json_decode(file_get_contents(__DIR__ . '/test_getall.json'), true);
echo "Success: " . ($json['success'] ? 'true' : 'false') . "\n";
echo "Total entries: " . count($json['data']) . "\n";

$withQty = 0;
foreach ($json['data'] as $r) {
    if (floatval($r['initial_qty']) > 0) $withQty++;
}
echo "With initial_qty > 0: $withQty\n";

echo "\nFirst 5 entries:\n";
foreach (array_slice($json['data'], 0, 5) as $r) {
    echo "  stock_id={$r['stock_id']} material_id={$r['material_id']} name=" . substr($r['material_name'], 0, 30) . " initial={$r['initial_qty']} cost={$r['cost_per_unit']}\n";
}

echo "\nLast 3 entries:\n";
foreach (array_slice($json['data'], -3) as $r) {
    echo "  stock_id={$r['stock_id']} material_id={$r['material_id']} name=" . substr($r['material_name'], 0, 30) . " initial={$r['initial_qty']} cost={$r['cost_per_unit']}\n";
}

// Check for null stock_ids
$nullStock = 0;
foreach ($json['data'] as $r) {
    if (empty($r['stock_id'])) $nullStock++;
}
echo "\nWith NULL stock_id: $nullStock\n";
