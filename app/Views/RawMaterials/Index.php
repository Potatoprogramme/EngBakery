<?= $this->include('Templates/Header') ?>

<h5><?= esc($title) ?></h5>
<a href="<?= site_url('RawMaterials/create') ?>" class="btn btn-primary btn-sm mb-3">+ Add New</a>

<?php if (empty($materials)): ?>
    <p>No materials found. <a href="<?= site_url('RawMaterials/create') ?>">Add one</a></p>
<?php else: ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Material Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Unit Cost</th>
                <th>Per Gram/PC</th>
                <th>Current Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materials as $m): ?>
                <tr>
                    <td><?= esc($m['material_name']) ?></td>
                    <td><?= esc($m['category_name'] ?? '-') ?></td>
                    <td class="text-end"><?= number_format($m['material_quantity'] ?? 0, 2) ?></td>
                    <td><?= esc($m['unit'] ?? '-') ?></td>
                    <td class="text-end">₱<?= number_format($m['total_cost'] ?? 0, 2) ?></td>
                    <td class="text-end">₱<?= number_format($m['cost_per_unit'] ?? 0, 3) ?></td>
                    <td class="text-end"><?= number_format($m['current_quantity'] ?? 0, 2) ?> <?= esc($m['unit'] ?? '') ?></td>
                    <td>
                        <a href="<?= site_url('RawMaterials/edit/' . $m['material_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $m['material_id'] ?>, '<?= esc($m['material_name']) ?>')">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Delete?</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-2">
                <p class="mb-0">Delete <strong id="deleteMaterialName"></strong>?</p>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteMaterialName').textContent = name;
    document.getElementById('deleteForm').action = '<?= site_url('RawMaterials/delete/') ?>' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?= $this->include('Templates/Footer') ?>
