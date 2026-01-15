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
                            <i class="fas fa-plus me-2"></i> Add Material
                        </button>
                        <button type="button" id="btnExport" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <i class="fas fa-file-export me-2"></i> Export
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
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Raw Material
                </button>
            </div>

            <div class="p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 sm:mb-0">
                <table id="selection-table" class="min-w-full text-sm text-left text-gray-500">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Material Name
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Category
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Quantity
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Unit
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Cost per Unit
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
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
    <div id="addMaterialModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-plus me-2"></i>Add Raw Material</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addMaterialForm">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Material Name <span class="text-red-500">*</span></label>
                    <input type="text" name="material_name" id="material_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" placeholder="e.g., Flour - All Purpose" required>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Select</option>
                        </select>
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
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="material_quantity" id="material_quantity" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" placeholder="25000" min="1" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Cost <span class="text-red-500">*</span></label>
                        <input type="number" name="total_cost" id="total_cost" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" placeholder="1350.00" step="0.01" min="0.01" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Per Unit</label>
                        <div id="cost_per_unit_display" class="w-full px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-gray-600">0.000</div>
                    </div>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelAdd" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                    <button type="submit" id="btnSaveMaterial" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Save</button>
                </div>
            </form>
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

    <!-- jQuery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a89dedcb22.js" crossorigin="anonymous"></script>
    <!-- Simple DataTables -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

    <script>
    $(document).ready(function() {
        const baseUrl = '<?= site_url() ?>';
        let dataTable = null;

        // Load data on page load
        loadMaterials();
        loadFilterCategories();

        // Open Add Material Modal (Desktop & Mobile)
        $('#btnAddMaterial, #btnAddMaterialMobile').on('click', function() {
            $('#addMaterialModal').removeClass('hidden');
            loadCategories();
        });

        // Close Modal
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
            $('#cost_per_unit_display').text('0.000');
        }

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
                url: baseUrl + 'RawMaterials/GetCategories',
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
                url: baseUrl + 'RawMaterials/GetCategories',
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
                            rows += '<tr class="hover:bg-neutral-secondary-soft cursor-pointer" data-category="' + (mat.category_id || '') + '">';
                            rows += '<td class="px-6 py-4 font-medium text-heading whitespace-nowrap">' + mat.material_name + '</td>';
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
                        $('#materialsTableBody').html(rows);

                        // Initialize DataTable
                        if (dataTable) {
                            dataTable.destroy();
                        }
                        const tableElement = document.getElementById('selection-table');
                        if (tableElement && typeof simpleDatatables !== 'undefined') {
                            dataTable = new simpleDatatables.DataTable('#selection-table');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error loading materials: ' + error);
                }
            });
        }

        // Submit Add Material Form via AJAX
        $('#addMaterialForm').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                material_name: $('#material_name').val(),
                category_id: $('#category_id').val(),
                unit: $('#unit').val(),
                material_quantity: $('#material_quantity').val(),
                total_cost: $('#total_cost').val()
            };

            $.ajax({
                url: baseUrl + 'RawMaterials/AddRawMaterial',
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Material added successfully!');
                        closeModal();
                        loadMaterials();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error adding material: ' + error);
                }
            });
        });

        // Delete Material
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this material?')) {
                $.ajax({
                    url: baseUrl + 'RawMaterials/delete/' + id,
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
