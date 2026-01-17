/**
 * Category Modal Handler
 * Reusable component for managing material categories
 */
const CategoryModal = (function() {
    let baseUrl = '';
    let onCategoryChange = null;

    function init(config) {
        baseUrl = config.baseUrl || '';
        onCategoryChange = config.onCategoryChange || function() {};
        
        bindEvents();
    }

    function bindEvents() {
        // Open Manage Categories Modal
        $(document).on('click', '#btnManageCategories', function() {
            open();
        });

        // Close Category Modal
        $('#btnCloseCategoryModal, #btnCancelCategory').on('click', function() {
            close();
        });

        // Submit Category Form (Add/Edit)
        $('#categoryForm').on('submit', function(e) {
            e.preventDefault();
            saveCategory();
        });

        // Edit Category
        $(document).on('click', '.btn-edit-category', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const desc = $(this).data('desc');
            const label = $(this).data('label');

            $('#edit_category_id').val(id);
            $('#category_name').val(name);
            $('#category_description').val(desc);
            $('#category_label').val(label);
            $('#btnSaveCategory').text('Update');
        });

        // Delete Category
        $(document).on('click', '.btn-delete-category', function() {
            const id = $(this).data('id');
             Confirm.delete('Are you sure you want to delete this category?', function() {
                deleteCategory(id);
            });
        });
    }

    function open() {
        $('#manageCategoriesModal').removeClass('hidden');
        loadCategoriesList();
    }

    function close() {
        $('#manageCategoriesModal').addClass('hidden');
        $('#categoryForm')[0].reset();
        $('#edit_category_id').val('');
        $('#category_label').val('');
        $('#btnSaveCategory').text('Save');
    }

    function loadCategoriesList() {
        $.ajax({
            url: baseUrl + 'MaterialCategory/FetchAll',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    let html = '';
                    response.data.forEach(function(cat) {
                        // Label badge colors
                        const labelColors = {
                            'drinks': 'bg-blue-100 text-blue-800 border border-blue-300',
                            'bread': 'bg-amber-100 text-amber-800 border border-amber-300',
                            'general': 'bg-gray-100 text-gray-800 border border-gray-300'
                        };
                        const labelColor = labelColors[cat.label] || 'bg-gray-100 text-gray-800';

                        html += '<div class="flex items-center justify-between p-2 rounded-md bg-gray-50">';
                        html += '<div class="flex-1">';
                        html += '<div class="flex items-center gap-2">';
                        html += '<span class="font-medium text-gray-800">' + cat.category_name + '</span>';
                        if (cat.label) {
                            html += '<span class="text-xs px-2 py-0.5 rounded-full ' + labelColor + '">' + cat.label + '</span>';
                        }
                        html += '</div>';
                        if (cat.description) {
                            html += '<div class="text-xs text-gray-500">' + cat.description + '</div>';
                        }
                        html += '</div>';
                        html += '<div class="flex gap-2">';
                        html += '<button class="text-blue-600 hover:text-blue-800 btn-edit-category" data-id="' + cat.category_id + '" data-name="' + cat.category_name + '" data-desc="' + (cat.description || '') + '" data-label="' + (cat.label || '') + '" title="Edit"><i class="fas fa-edit"></i></button>';
                        html += '<button class="text-red-600 hover:text-red-800 btn-delete-category" data-id="' + cat.category_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
                        html += '</div>';
                        html += '</div>';
                    });
                    $('#categoriesList').html(html || '<p class="text-sm text-gray-500 text-center py-4">No categories yet</p>');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error loading categories list:', error);
            }
        });
    }

    function saveCategory() {
        const categoryId = $('#edit_category_id').val();
        const formData = {
            category_name: $('#category_name').val(),
            description: $('#category_description').val(),
            label: $('#category_label').val()
        };

        if (categoryId) {
            formData.category_id = categoryId;
        }

        const endpoint = categoryId ? 'MaterialCategory/Update' : 'MaterialCategory/Add';

        $.ajax({
            url: baseUrl + endpoint,
            type: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Toast.success(categoryId ? 'Category updated successfully!' : 'Category added successfully!');
                    $('#categoryForm')[0].reset();
                    $('#edit_category_id').val('');
                    $('#btnSaveCategory').text('Save');
                    loadCategoriesList();
                    
                    if (onCategoryChange) onCategoryChange();
                } else {
                    Toast.error('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                Toast.error('Error saving category: ' + (xhr.responseJSON?.message || error));
            }
        });
    }

    function deleteCategory(id) {
        $.ajax({
            url: baseUrl + 'MaterialCategory/Delete',
            type: 'POST',
            data: JSON.stringify({ category_id: id }),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Toast.success('Category deleted successfully!');
                    loadCategoriesList();
                    
                    if (onCategoryChange) onCategoryChange();
                } else {
                    Toast.error('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                Toast.error('Error deleting category: ' + error);
            }
        });
    }

    return {
        init: init,
        open: open,
        close: close,
        refresh: loadCategoriesList
    };
})();
