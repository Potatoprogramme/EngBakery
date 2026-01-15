<?= $this->include('Templates/Header') ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header py-2">
                <i class="fas fa-edit me-2"></i><?= esc($title) ?>
            </div>
            <div class="card-body">
                <form action="<?= site_url('RawMaterials/update/' . $material['material_id']) ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Material Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="material_name" 
                               value="<?= old('material_name', $material['material_name']) ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="category_id" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['category_id'] ?>" <?= old('category_id', $material['category_id']) == $cat['category_id'] ? 'selected' : '' ?>>
                                        <?= esc($cat['category_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Unit <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="unit" required>
                                <option value="grams" <?= old('unit', $material['unit']) == 'grams' ? 'selected' : '' ?>>grams</option>
                                <option value="pcs" <?= old('unit', $material['unit']) == 'pcs' ? 'selected' : '' ?>>pcs</option>
                                <option value="ml" <?= old('unit', $material['unit']) == 'ml' ? 'selected' : '' ?>>ml</option>
                                <option value="kg" <?= old('unit', $material['unit']) == 'kg' ? 'selected' : '' ?>>kg</option>
                                <option value="liters" <?= old('unit', $material['unit']) == 'liters' ? 'selected' : '' ?>>liters</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-4 mb-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" name="material_quantity" id="material_quantity"
                                   value="<?= old('material_quantity', $material['material_quantity']) ?>" min="1" required>
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">Unit Cost (₱) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" name="total_cost" id="total_cost"
                                   value="<?= old('total_cost', $material['total_cost'] ?? ($material['cost_per_unit'] * $material['material_quantity'])) ?>" 
                                   step="0.01" min="0.01" required>
                            <small class="text-muted">per quantity</small>
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">Per Gram/PC</label>
                            <div class="form-control form-control-sm" id="cost_per_unit_display">₱<?= number_format($material['cost_per_unit'] ?? 0, 3) ?></div>
                            <small class="text-muted">auto-calculated</small>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="<?= site_url('RawMaterials') ?>" class="btn btn-secondary btn-sm">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('material_quantity').addEventListener('input', calculateCost);
document.getElementById('total_cost').addEventListener('input', calculateCost);
function calculateCost() {
    const qty = parseFloat(document.getElementById('material_quantity').value) || 0;
    const cost = parseFloat(document.getElementById('total_cost').value) || 0;
    document.getElementById('cost_per_unit_display').textContent = '₱' + (qty > 0 ? (cost / qty).toFixed(3) : '0.000');
}
</script>

<?= $this->include('Templates/Footer') ?>
