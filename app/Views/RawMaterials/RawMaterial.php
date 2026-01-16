<body class="bg-gray-50">
    <!-- Main Content -->
    <div class="p-4 sm:ml-60">
        <div class="mt-16">
            <nav class="mb-3 sm:mb-4" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-1 text-sm text-gray-500 justify-left sm:justify-start">
                    <li>
                        <a href="<?= base_url('Dashboard') ?>" class="hover:text-primary">Dashboard</a>
                    </li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </li>
                    <li class="text-gray-700">Raw Material</li>
                </ol>
            </nav>
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Raw Material Lists</h2>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="btnAddMaterial" class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            Add Raw Material
                        </button>
                        <button type="button" id="btnExport" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            Export
                        </button>
                    </div>
                </div>
                
                <!-- Divider line -->
                <div class="border-t border-gray-200 my-4"></div>
                
                <!-- Filters section -->
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full">
                        <div class="flex items-center gap-2 w-full">
                            <label for="filter-category" class="sr-only">Category</label>
                            <select id="filter-category" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                                <option value="">All Categories</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-center sm:justify-end">
                        <button id="apply-filters" type="button" class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">Apply</button>
                        <button id="reset-filters" type="button" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Floating Add Material button for mobile -->
            <div class="fixed bottom-6 left-0 right-0 flex justify-center z-30 sm:hidden">
                <button type="button" id="btnAddMaterialMobile" class="w-5/6 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    Add Raw Material
                </button>
            </div>

            <div class="p-4 bg-white rounded-lg shadow-md mb-20 sm:mb-0">
                <table id="selection-table" class="min-w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Material Name
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Category
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Quantity
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Unit
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Cost per Unit
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="materialsTableBody">
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Material Modal -->
    <div id="addMaterialModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-4 border shadow-lg rounded-md bg-white" style="max-width: 32rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary" id="modalTitle">Add Raw Material</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addMaterialForm">
                <input type="hidden" id="edit_material_id" value="">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Material Name <span class="text-red-500">*</span></label>
                    <input type="text" name="material_name" id="material_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" placeholder="e.g., Flour - All Purpose" required>
                    <p id="material_name_error" class="text-red-500 text-xs mt-1 hidden">This material name already exists.</p>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                    <div class="flex gap-2 items-center">
                        <select name="category_id" id="category_id" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Select</option>
                        </select>
                        <button type="button" id="btnManageCategories" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary" title="Manage Categories">
                            Manage
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-3 mb-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="material_quantity" id="material_quantity" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" placeholder="25000" min="1" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit <span class="text-red-500">*</span></label>
                        <select name="unit" id="unit" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="grams">grams</option>
                            <option value="pcs">pcs</option>
                            <option value="ml">ml</option>
                            <option value="kg">kg</option>
                            <option value="liters">liters</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-2 mb-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Cost <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-700 font-medium">₱</span>
                            <input type="number" name="total_cost" id="total_cost" class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" placeholder="1350.00" step="0.01" min="0.01" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Per Unit</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-700 font-medium">₱</span>
                            <div id="cost_per_unit_display" class="w-full pl-7 pr-3 py-2 border-gray-200 rounded-md bg-gray-50 text-gray-600">0.000</div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelAdd" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                    <button type="submit" id="btnSaveMaterial" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Manage Categories Modal -->
    <div id="manageCategoriesModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-4 border shadow-lg rounded-md bg-white" style="max-width: 32rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-tags me-2"></i>Manage Categories</h3>
                <button type="button" id="btnCloseCategoryModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Add/Edit Category Form -->
            <form id="categoryForm">
                <input type="hidden" id="edit_category_id" value="">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category Name <span class="text-red-500">*</span></label>
                    <input type="text" name="category_name" id="category_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" placeholder="e.g., Flour" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="category_description" id="category_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Optional description"></textarea>
                </div>
                <div class="flex gap-2 justify-end mb-4">
                    <button type="button" id="btnCancelCategory" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                    <button type="submit" id="btnSaveCategory" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Save</button>
                </div>
            </form>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-4"></div>

            <!-- Categories List -->
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Categories</h4>
                <div id="categoriesList" class="space-y-2 max-h-64 overflow-y-auto">
                    <!-- Categories will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 640px) {
            .datatable-top, .datatable-bottom {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                gap: 0.3rem !important;
                padding: 0.3rem 0;
            }
            .datatable-dropdown, .datatable-search, .datatable-info, .datatable-pagination {
                float: none !important;
                width: 100% !important;
                text-align: center !important;
                display: flex !important;
                justify-content: center !important;
                margin: 0 !important;
            }
            .datatable-search {
                margin-top: 0.5rem !important;
            }
            .datatable-pagination ul {
                justify-content: center !important;
            }
        }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a89dedcb22.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <script>
    $(document).ready(function() {
        const baseUrl = '<?= rtrim(site_url(), '/') ?>/';
        let dataTable = null;
        let materialNameExists = false;
        let checkNameTimeout = null;

        // Load data on page load
        loadMaterials();
        loadFilterCategories();

        // Real-time material name validation
        $('#material_name').on('input', function() {
            const materialName = $(this).val().trim();
            const materialId = $('#edit_material_id').val();
            
            // Clear previous timeout
            if (checkNameTimeout) clearTimeout(checkNameTimeout);
            
            if (materialName.length < 2) {
                $('#material_name_error').addClass('hidden');
                $('#material_name').removeClass('border-red-500').addClass('border-gray-300');
                materialNameExists = false;
                return;
            }
            
            // Debounce the check
            checkNameTimeout = setTimeout(function() {
                $.ajax({
                    url: baseUrl + 'RawMaterials/CheckMaterialName',
                    type: 'POST',
                    data: JSON.stringify({ material_name: materialName, material_id: materialId }),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function(response) {
                        if (response.exists) {
                            $('#material_name_error').removeClass('hidden');
                            $('#material_name').removeClass('border-gray-300').addClass('border-red-500');
                            materialNameExists = true;
                        } else {
                            $('#material_name_error').addClass('hidden');
                            $('#material_name').removeClass('border-red-500').addClass('border-gray-300');
                            materialNameExists = false;
                        }
                    }
                });
            }, 300);
        });

        // Open Add Material Modal (Desktop & Mobile)
        $('#btnAddMaterial, #btnAddMaterialMobile').on('click', function() {
            $('#addMaterialModal').removeClass('hidden');
            loadCategories();
        });

        // Close Material Modal
        $('#btnCloseModal, #btnCancelAdd').on('click', function() {
            closeModal();
        });

        // Close modal on outside click
        $('#addMaterialModal').on('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        function closeModal() {
            $('#addMaterialModal').addClass('hidden');
            $('#addMaterialForm')[0].reset();
            $('#edit_material_id').val('');
            $('#modalTitle').text('Add Raw Material');
            $('#material_name_error').addClass('hidden');
            $('#material_name').removeClass('border-red-500').addClass('border-gray-300');
            materialNameExists = false;
            $('#btnSaveMaterial').text('Save');
            $('#cost_per_unit_display').text('0.000');
        }

        // Open Manage Categories Modal
        $('#btnManageCategories').on('click', function() {
            $('#manageCategoriesModal').removeClass('hidden');
            loadCategoriesList();
        });

        // Close Category Modal
        $('#btnCloseCategoryModal, #btnCancelCategory').on('click', function() {
            closeCategoryModal();
        });

        // Close category modal on outside click
        $('#manageCategoriesModal').on('click', function(e) {
            if (e.target === this) {
                closeCategoryModal();
            }
        });

        function closeCategoryModal() {
            $('#manageCategoriesModal').addClass('hidden');
            $('#categoryForm')[0].reset();
            $('#edit_category_id').val('');
            $('#btnSaveCategory').text('Save');
        }

        // Load Categories List for Management
        function loadCategoriesList() {
            $.ajax({
                url: baseUrl + 'MaterialCategory/FetchAll',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let html = '';
                        response.data.forEach(function(cat) {
                            html += '<div class="flex items-center justify-between p-2 border border-gray-200 rounded-md bg-gray-50">';
                            html += '<div class="flex-1">';
                            html += '<div class="font-medium text-gray-800">' + cat.category_name + '</div>';
                            if (cat.description) {
                                html += '<div class="text-xs text-gray-500">' + cat.description + '</div>';
                            }
                            html += '</div>';
                            html += '<div class="flex gap-2">';
                            html += '<button class="text-blue-600 hover:text-blue-800 btn-edit-category" data-id="' + cat.category_id + '" data-name="' + cat.category_name + '" data-desc="' + (cat.description || '') + '" title="Edit"><i class="fas fa-edit"></i></button>';
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

        // Submit Category Form (Add/Edit)
        $('#categoryForm').on('submit', function(e) {
            e.preventDefault();
            
            const categoryId = $('#edit_category_id').val();
            const formData = {
                category_name: $('#category_name').val(),
                description: $('#category_description').val()
            };

            if (categoryId) {
                formData.category_id = categoryId;
            }

            // Use Update endpoint if editing, Add endpoint if creating new
            const endpoint = categoryId ? 'MaterialCategory/Update' : 'MaterialCategory/Add';

            $.ajax({
                url: baseUrl + endpoint,
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(categoryId ? 'Category updated successfully!' : 'Category added successfully!');
                        $('#categoryForm')[0].reset();
                        $('#edit_category_id').val('');
                        $('#btnSaveCategory').text('Save');
                        loadCategoriesList();
                        loadCategories();
                        loadFilterCategories();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('XHR:', xhr);
                    console.log('Status:', status);
                    console.log('Error:', error);
                    alert('Error saving category: ' + (xhr.responseJSON?.message || error));
                }
            });
        });

        // Edit Category
        $(document).on('click', '.btn-edit-category', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const desc = $(this).data('desc');
            
            $('#edit_category_id').val(id);
            $('#category_name').val(name);
            $('#category_description').val(desc);
            $('#btnSaveCategory').text('Update');
        });

        // Delete Category
        $(document).on('click', '.btn-delete-category', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this category?')) {
                $.ajax({
                    url: baseUrl + 'MaterialCategory/Delete',
                    type: 'POST',
                    data: JSON.stringify({ category_id: id }),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Category deleted successfully!');
                            loadCategoriesList();
                            loadCategories();
                            loadFilterCategories();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error deleting category: ' + error);
                    }
                });
            }
        });

        // Calculate cost per unit
        $('#material_quantity, #total_cost').on('input', function() {
            const qty = parseFloat($('#material_quantity').val()) || 0;
            const cost = parseFloat($('#total_cost').val()) || 0;
            const perUnit = qty > 0 ? (cost / qty).toFixed(3) : '0.000';
            $('#cost_per_unit_display').text(perUnit);
        });

        // Load Categories for Filter dropdown
        function loadFilterCategories() {
            $.ajax({
                url: baseUrl + 'MaterialCategory/FetchAll',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let options = '<option value="">All Categories</option>';
                        response.data.forEach(function(cat) {
                            options += '<option value="' + cat.category_id + '">' + cat.category_name + '</option>';
                        });
                        $('#filter-category').html(options);
                    }
                }
            });
        }

        // Load Categories for Modal dropdown
        function loadCategories() {
            $.ajax({
                url: baseUrl + 'MaterialCategory/FetchAll',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let options = '<option value="">Select</option>';
                        response.data.forEach(function(cat) {
                            options += '<option value="' + cat.category_id + '">' + cat.category_name + '</option>';
                        });
                        $('#category_id').html(options);
                    }
                }
            });
        }

        // Load Materials via AJAX
        function loadMaterials() {
            $.ajax({
                url: baseUrl + 'RawMaterials/GetAll',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let rows = '';
                        response.data.forEach(function(mat) {
                            rows += '<tr class="hover:bg-gray-50 cursor-pointer border-b" data-category="' + (mat.category_id || '') + '">';
                            rows += '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">' + mat.material_name + '</td>';
                            rows += '<td class="px-6 py-4">' + (mat.category_name || '-') + '</td>';
                            rows += '<td class="px-6 py-4">' + mat.material_quantity + '</td>';
                            rows += '<td class="px-6 py-4">' + mat.unit + '</td>';
                            rows += '<td class="px-6 py-4">' + parseFloat(mat.cost_per_unit || 0).toFixed(3) + '</td>';
                            rows += '<td class="px-6 py-4">';
                            rows += '<button class="text-blue-600 hover:text-blue-800 me-2 btn-edit" data-id="' + mat.material_id + '" title="Edit"><i class="fas fa-edit"></i></button>';
                            rows += '<button class="text-red-600 hover:text-red-800 btn-delete" data-id="' + mat.material_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
                            rows += '</td>';
                            rows += '</tr>';
                        });

                        // Destroy existing DataTable before updating content
                        if (dataTable) {
                            dataTable.destroy();
                            dataTable = null;
                        }

                        // Update table body
                        $('#materialsTableBody').html(rows);

                        // Re-initialize DataTable with simple config
                        const tableElement = document.getElementById('selection-table');
                        if (tableElement && typeof simpleDatatables !== 'undefined') {
                            dataTable = new simpleDatatables.DataTable('#selection-table', {
                                searchable: true,
                                sortable: true,
                                perPage: 10
                            });

                            // Add Tailwind classes for scrolling (only table content scrolls)
                            const container = document.querySelector('.datatable-container');
                            if (container) {
                                container.classList.add('max-h-96', 'overflow-y-auto', 'overflow-x-auto');
                            }
                            
                            // Add sticky header classes
                            const thead = document.querySelector('.datatable-table thead');
                            if (thead) {
                                thead.classList.add('sticky', 'top-0', 'bg-white', 'z-10');
                            }

                            // Add sticky first column classes
                            document.querySelectorAll('.datatable-table thead th:first-child').forEach(th => {
                                th.classList.add('sticky', 'left-0', 'bg-white', 'z-20');
                            });
                            document.querySelectorAll('.datatable-table tbody td:first-child').forEach(td => {
                                td.classList.add('sticky', 'left-0', 'bg-white', 'z-5');
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error loading materials: ' + error);
                }
            });
        }

        // Submit Add/Edit Material Form via AJAX
        $('#addMaterialForm').on('submit', function(e) {
            e.preventDefault();

            // Check if material name already exists
            if (materialNameExists) {
                alert('Material name already exists. Please use a different name.');
                return;
            }

            const materialId = $('#edit_material_id').val();
            const formData = {
                material_name: $('#material_name').val(),
                category_id: $('#category_id').val(),
                unit: $('#unit').val(),
                material_quantity: $('#material_quantity').val(),
                total_cost: $('#total_cost').val()
            };

            // Use Update endpoint if editing, Add endpoint if creating new
            let endpoint = 'RawMaterials/AddRawMaterial';
            if (materialId) {
                formData.material_id = materialId;
                endpoint = 'RawMaterials/UpdateRawMaterial';
            }

            $.ajax({
                url: baseUrl + endpoint,
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(materialId ? 'Material updated successfully!' : 'Material added successfully!');
                        closeModal();
                        loadMaterials();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error saving material: ' + error);
                }
            });
        });

        // Edit Material
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            
            $.ajax({
                url: baseUrl + 'RawMaterials/GetMaterial/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const mat = response.data;
                        $('#edit_material_id').val(mat.material_id);
                        $('#material_name').val(mat.material_name);
                        $('#category_id').val(mat.category_id);
                        $('#unit').val(mat.unit);
                        $('#material_quantity').val(mat.material_quantity);
                        $('#total_cost').val(parseFloat(mat.total_cost).toFixed(2));
                        $('#cost_per_unit_display').text(parseFloat(mat.cost_per_unit).toFixed(3));
                        $('#modalTitle').text('Edit Raw Material');
                        $('#btnSaveMaterial').text('Update');
                        
                        // Open modal and load categories
                        loadCategories();
                        $('#addMaterialModal').removeClass('hidden');
                        
                        // Set category after dropdown is loaded
                        setTimeout(function() {
                            $('#category_id').val(mat.category_id);
                        }, 300);
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error loading material: ' + error);
                }
            });
        });

        // Delete Material
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this material?')) {
                $.ajax({
                    url: baseUrl + 'RawMaterials/Delete/' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Material deleted successfully!');
                            loadMaterials();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error deleting material: ' + error);
                    }
                });
            }
        });

        // Apply Filter
        $('#apply-filters').on('click', function() {
            const categoryId = $('#filter-category').val();
            $('table tbody tr').each(function() {
                if (categoryId === '' || $(this).data('category') == categoryId) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Reset Filter
        $('#reset-filters').on('click', function() {
            $('#filter-category').val('');
            $('table tbody tr').show();
        });
    });
    </script>
