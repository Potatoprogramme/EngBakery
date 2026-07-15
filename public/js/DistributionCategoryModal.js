/**
 * Distribution Category Modal Handler
 * Reusable component for managing distribution categories.
 */
(function () {
    const baseUrl = window.BASE_URL || '';
    let initialized = false;

    function init() {
        if (initialized) {
            return;
        }

        initialized = true;
        bindEvents();
    }

    function bindEvents() {
        $(document).on('click', '#btnManageDistributionCategories, #btnManageDistributionCategoriesMobile', function () {
            open();
        });

        $(document).on('click', '#btnCloseDistributionCategoryModal, #btnCancelDistributionCategory', function () {
            close();
        });

        $(document).on('click', '#btnRefreshDistributionCategories', function () {
            loadCategoriesList();
        });

        $(document).on('submit', '#distributionCategoryForm', function (event) {
            event.preventDefault();
            saveCategory();
        });

        $(document).on('click', '.btn-edit-distribution-category', function () {
            const button = $(this);
            $('#edit_distribution_category_id').val(button.data('id'));
            $('#distribution_category_name').val(button.data('name') || '');
            $('#btnSaveDistributionCategory').text('Update');
        });

        $(document).on('click', '.btn-delete-distribution-category', function () {
            const categoryId = $(this).data('id');

            if (!window.confirm('Delete this distribution category?')) {
                return;
            }

            deleteCategory(categoryId);
        });
    }

    function open() {
        $('#manageDistributionCategoriesModal').removeClass('hidden');
        document.body.classList.add('overflow-hidden');
        loadCategoriesList();
    }

    function close() {
        $('#manageDistributionCategoriesModal').addClass('hidden');
        document.body.classList.remove('overflow-hidden');
        resetForm();
    }

    function resetForm() {
        $('#distributionCategoryForm')[0]?.reset();
        $('#edit_distribution_category_id').val('');
        $('#btnSaveDistributionCategory').text('Save');
    }

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function notify(type, message) {
        if (typeof showToast === 'function') {
            showToast(type, message, 3000);
            return;
        }

        alert(message);
    }

    function loadCategoriesList() {
        $.ajax({
            url: baseUrl + 'DistributionCategory/FetchAll',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                const categories = Array.isArray(response.data) ? response.data : [];
                let html = '';

                if (!categories.length) {
                    $('#distributionCategoriesList').html(
                        '<p class="text-sm text-gray-500 text-center py-4">No distribution categories yet.</p>'
                    );
                    return;
                }

                categories.forEach(function (category) {
                    const categoryId = category.dist_cat_id ?? category.id ?? '';
                    const categoryName = escapeHtml(category.name ?? '');

                    html += '<div class="flex items-center justify-between gap-3 rounded-lg border border-gray-100 bg-gray-50 px-3 py-2">';
                    html += '<div class="min-w-0 flex-1">';
                    html += '<p class="truncate text-sm font-medium text-gray-800">' + categoryName + '</p>';
                    html += '</div>';
                    html += '<div class="flex shrink-0 items-center gap-2">';
                    html += '<button type="button" class="btn-edit-distribution-category text-sm text-blue-600 hover:text-blue-800" data-id="' + categoryId + '" data-name="' + categoryName + '" title="Edit">';
                    html += '<i class="fas fa-edit"></i>';
                    html += '</button>';
                    html += '<button type="button" class="btn-delete-distribution-category text-sm text-red-600 hover:text-red-800" data-id="' + categoryId + '" title="Delete">';
                    html += '<i class="fas fa-trash"></i>';
                    html += '</button>';
                    html += '</div>';
                    html += '</div>';
                });

                $('#distributionCategoriesList').html(html);
                $(document).trigger('distribution-categories-updated');
            },
            error: function () {
                $('#distributionCategoriesList').html(
                    '<p class="text-sm text-red-500 text-center py-4">Unable to load categories.</p>'
                );
            }
        });
    }

    function saveCategory() {
        const categoryId = ($('#edit_distribution_category_id').val() || '').toString().trim();
        const name = ($('#distribution_category_name').val() || '').toString().trim();

        if (!name) {
            notify('danger', 'Category name is required.');
            return;
        }

        const payload = {
            name: name
        };

        const endpoint = categoryId ? 'DistributionCategory/Update' : 'DistributionCategory/Add';

        if (categoryId) {
            payload.category_id = categoryId;
        }

        $.ajax({
            url: baseUrl + endpoint,
            type: 'POST',
            data: JSON.stringify(payload),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                if (response && response.success) {
                    notify('success', response.message || 'Category saved successfully.');
                    resetForm();
                    loadCategoriesList();
                    return;
                }

                notify('danger', response?.message || 'Failed to save category.');
            },
            error: function (xhr) {
                notify('danger', xhr.responseJSON?.message || xhr.responseJSON?.error || 'Failed to save category.');
            }
        });
    }

    function deleteCategory(categoryId) {
        $.ajax({
            url: baseUrl + 'DistributionCategory/Delete',
            type: 'POST',
            data: JSON.stringify({ category_id: categoryId }),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                if (response && response.success) {
                    notify('success', response.message || 'Category deleted successfully.');
                    loadCategoriesList();
                    return;
                }

                notify('danger', response?.message || 'Failed to delete category.');
            },
            error: function (xhr) {
                notify('danger', xhr.responseJSON?.message || xhr.responseJSON?.error || 'Failed to delete category.');
            }
        });
    }

    $(function () {
        init();
    });

    window.DistributionCategoryModal = {
        open: open,
        close: close,
        refresh: loadCategoriesList,
    };
})();