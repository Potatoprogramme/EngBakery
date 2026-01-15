<?= $this->include('Templates/Header') ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header py-2"><i class="fas fa-plus me-2"></i><?= esc($title) ?></div>
            <div class="card-body">
                <form action="<?= site_url('MaterialCategories/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="category_name" 
                               value="<?= old('category_name') ?>" placeholder="e.g., RAW MATERIALS" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control form-control-sm" name="description" 
                               value="<?= old('description') ?>" placeholder="Optional">
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?= site_url('MaterialCategories') ?>" class="btn btn-secondary btn-sm">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->include('Templates/Footer') ?>
