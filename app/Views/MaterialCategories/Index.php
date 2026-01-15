<?= $this->include('Templates/Header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-tags me-2"></i><?= esc($title) ?></h4>
    <a href="<?= site_url('MaterialCategories/create') ?>" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i>Add
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($categories)): ?>
            <div class="text-center py-5">
                <p class="text-muted mb-2">No categories found.</p>
                <a href="<?= site_url('MaterialCategories/create') ?>" class="btn btn-primary btn-sm">Add First Category</a>
            </div>
        <?php else: ?>
            <table class="table table-hover table-sm mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th class="text-center">Materials</th>
                        <th class="text-center" style="width:100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= esc($category['category_name']) ?></td>
                            <td><small class="text-muted"><?= esc($category['description'] ?? '-') ?></small></td>
                            <td class="text-center"><?= $category['material_count'] ?? 0 ?></td>
                            <td class="text-center">
                                <a href="<?= site_url('MaterialCategories/edit/' . $category['category_id']) ?>" class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                        onclick="confirmDelete(<?= $category['category_id'] ?>, '<?= esc($category['category_name']) ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Delete?</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-2">Delete <strong id="deleteCategoryName"></strong>?</div>
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
    document.getElementById('deleteCategoryName').textContent = name;
    document.getElementById('deleteForm').action = '<?= site_url('MaterialCategories/delete/') ?>' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?= $this->include('Templates/Footer') ?>
