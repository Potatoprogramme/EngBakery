<div class="mb-4">
    <a href="<?= site_url('RawMaterials') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to List
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-info-circle me-2"></i>Material Details</span>
                <div>
                    <a href="<?= site_url('RawMaterials/edit/' . $material['material_id']) ?>" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <h2 class="mb-3"><?= esc($material['material_name']) ?></h2>
                
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 200px;">Category:</th>
                        <td>
                            <span class="badge badge-category">
                                <?= esc($material['category_name'] ?? 'Uncategorized') ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Quantity per Purchase:</th>
                        <td><?= number_format($material['material_quantity']) ?> <?= esc($material['unit']) ?></td>
                    </tr>
                    <tr>
                        <th>Cost per Unit:</th>
                        <td class="cost-highlight fs-5">₱<?= number_format($material['cost_per_unit'] ?? 0, 4) ?> / <?= esc($material['unit']) ?></td>
                    </tr>
                    <tr>
                        <th>Total Cost:</th>
                        <td class="fs-5">₱<?= number_format($material['total_cost'] ?? 0, 2) ?></td>
                    </tr>
                    <tr>
                        <th>Date Added:</th>
                        <td><?= date('F d, Y h:i A', strtotime($material['date_created'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-warehouse me-2"></i>Stock Status
            </div>
            <div class="card-body text-center">
                <?php 
                $currentStock = $material['current_quantity'] ?? 0;
                $maxStock = $material['material_quantity'];
                $percentage = $maxStock > 0 ? ($currentStock / $maxStock) * 100 : 0;
                $statusClass = $percentage <= 20 ? 'text-danger' : ($percentage <= 50 ? 'text-warning' : 'text-success');
                $statusText = $percentage <= 20 ? 'Low Stock' : ($percentage <= 50 ? 'Medium Stock' : 'Good Stock');
                ?>
                
                <h1 class="<?= $statusClass ?> mb-0"><?= number_format($currentStock, 2) ?></h1>
                <p class="text-muted mb-3"><?= esc($material['unit']) ?></p>
                
                <div class="progress mb-3" style="height: 30px;">
                    <?php 
                    $barClass = $percentage <= 20 ? 'bg-danger' : ($percentage <= 50 ? 'bg-warning' : 'bg-success');
                    ?>
                    <div class="progress-bar <?= $barClass ?>" 
                         role="progressbar" 
                         style="width: <?= min(100, $percentage) ?>%">
                        <?= number_format($percentage, 1) ?>%
                    </div>
                </div>
                
                <span class="badge <?= $statusClass == 'text-danger' ? 'bg-danger' : ($statusClass == 'text-warning' ? 'bg-warning' : 'bg-success') ?>">
                    <?= $statusText ?>
                </span>
                
                <hr>
                
                <p class="mb-0 text-muted">
                    <small>Max Capacity: <?= number_format($maxStock) ?> <?= esc($material['unit']) ?></small>
                </p>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <i class="fas fa-calculator me-2"></i>Cost Calculator
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="calc_quantity" class="form-label">Quantity Needed:</label>
                    <input type="number" class="form-control" id="calc_quantity" value="100" min="0" step="0.01">
                </div>
                <div class="alert alert-info mb-0">
                    <strong>Estimated Cost:</strong>
                    <span id="calc_result" class="fs-5">₱<?= number_format(100 * ($material['cost_per_unit'] ?? 0), 2) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('calc_quantity').addEventListener('input', function() {
    const quantity = parseFloat(this.value) || 0;
    const costPerUnit = <?= $material['cost_per_unit'] ?? 0 ?>;
    const totalCost = quantity * costPerUnit;
    document.getElementById('calc_result').textContent = '₱' + totalCost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
});
</script>
