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
                    <li class="text-gray-700">Product</li>
                </ol>
            </nav>
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Product Lists</h2>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="btnAddMaterial" class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            Add Product
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
                            <label for="filter-category" class="sr-only">Product Category</label>
                            <select id="filter-category" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                                <option value="">All Product Categories</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-center sm:justify-end">
                        <button id="apply-filters" type="button" class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">Apply</button>
                        <button id="reset-filters" type="button" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Floating Add Product button for mobile -->
            <div class="fixed bottom-6 left-0 right-0 flex justify-center z-30 sm:hidden">
                <button type="button" id="btnAddMaterialMobile" class="w-5/6 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    Add Product
                </button>
            </div>

            <div class="p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 sm:mb-0">
                <table id="selection-table" class="min-w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Product Name
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Product Category
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

    <!-- Add Product Modal -->
    <div id="addMaterialModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-4 border shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto" style="max-width: 32rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary">Add Product</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addMaterialForm">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="material_name" id="material_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" placeholder="e.g., Cafe Latte" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Product Category <span class="text-red-500">*</span></label>
                    <div class="flex gap-2 items-center">
                        <select name="category_id" id="category_id" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Select</option>
                        </select>
                        <button type="button" id="btnManageCategories" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary" title="Manage Product Categories">
                            Manage
                        </button>
                    </div>
                </div>
                <div class="mb-3 p-3 border border-gray-200 rounded-md bg-gray-50">
                    <h2 class="text-center text-m font-medium">Product Ingredients</h2>
                
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Ingredients <span class="text-red-500">*</span></label>
                        <div class="flex items-center">
                            <select name="ingredient_id" id="ingredient_id" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Select Ingredient</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                            <input type="number" name="ingredient_quantity" id="ingredient_quantity" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" placeholder="100" min="1">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <select name="ingredient_unit" id="ingredient_unit" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="grams">grams</option>
                                <option value="pcs">pcs</option>
                                <option value="ml">ml</option>
                                <option value="kg">kg</option>
                                <option value="liters">liters</option>
                            </select>
                        </div>
                    </div>
                    <div class="">
                        <button type="button" id="btnAddIngredient" class="w-full px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">
                            Add Ingredient
                        </button>
                    </div>
                </div>

                <!-- Ingredients List Container -->
                <div class="mb-4 p-3 border border-gray-200 rounded-md bg-gray-50">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Added Ingredients</h4>
                    <div id="ingredientsList" class="space-y-2 max-h-40 overflow-y-auto">
                        <p class="text-sm text-gray-500 text-center py-2">No ingredients added yet</p>
                    </div>
                </div>

                <!-- Costing Container -->
                <div class="mb-4 p-3 border border-gray-200 rounded-md bg-gray-50">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Costing Breakdown</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Direct Cost:</span>
                            <span id="directCostDisplay" class="text-sm font-medium text-gray-800">₱ 0.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <label for="overheadCost" class="text-sm text-gray-600">Overhead Cost:</label>
                            <div class="relative w-28">
                                <span class="absolute left-2 top-1.5 text-gray-700 text-sm">₱</span>
                                <input type="number" id="overheadCost" class="w-full pl-6 pr-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="0.00" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <hr class="border-t border-gray-300">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Total Cost:</span>
                            <span id="totalCostDisplay" class="text-sm font-semibold text-gray-800">₱ 0.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <label for="profitMargin" class="text-sm text-gray-600">Profit Margin (%):</label>
                            <div class="relative w-20">
                                <input type="number" id="profitMargin" class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="30" min="0" value="30">
                                <span class="absolute right-2 top-1.5 text-gray-700 text-sm">%</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Profit Amount:</span>
                            <span id="profitAmountDisplay" class="text-sm font-medium text-green-600">₱ 0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Selling Price Container -->
                <div class="mb-4 p-3 border-2 border-primary rounded-md bg-primary/5">
                    <div class="flex justify-between items-center">
                        <span class="text-base font-semibold text-gray-800">Selling Price:</span>
                        <span id="sellingPriceDisplay" class="text-lg font-bold text-primary">₱ 0.00</span>
                    </div>
                </div>

                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelAdd" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                    <button type="submit" id="btnSaveMaterial" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Manage Product Categories Modal -->
    <div id="manageCategoriesModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-4 border shadow-lg rounded-md bg-white" style="max-width: 32rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-tags me-2"></i>Manage Product Categories</h3>
                <button type="button" id="btnCloseCategoryModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Add/Edit Product Category Form -->
            <form id="categoryForm">
                <input type="hidden" id="edit_category_id" value="">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Category Name <span class="text-red-500">*</span></label>
                    <input type="text" name="category_name" id="category_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" placeholder="e.g., Bread/Coffee" required>
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

            <!-- Product Categories List -->
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Product Categories</h4>
                <div id="categoriesList" class="space-y-2 max-h-64 overflow-y-auto">
                    <!-- Product Categories will be loaded here -->
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

        // Open Add Product Modal (Desktop & Mobile)
        $('#btnAddMaterial, #btnAddMaterialMobile').on('click', function() {
            $('#addMaterialModal').removeClass('hidden');
            loadCategories();
            loadIngredients();
            // test ingredients
            updateIngredientsListDisplay();
            updateCostingDisplay();
        });

        // Close Product Modal
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
            ingredientsList = [];
            updateIngredientsListDisplay();
            updateCostingDisplay();
        }

        // Ingredients test data
        let ingredientsList = [
            {
                id: 'sample1',
                name: 'All Purpose Flour',
                quantity: 500,
                unit: 'grams',
                costPerUnit: 0.054,
                totalCost: 27.00
            },
            {
                id: 'sample2',
                name: 'White Sugar',
                quantity: 100,
                unit: 'grams',
                costPerUnit: 0.065,
                totalCost: 6.50
            },
            {
                id: 'sample3',
                name: 'Fresh Milk',
                quantity: 250,
                unit: 'ml',
                costPerUnit: 0.12,
                totalCost: 30.00
            }
        ];

        // Load Ingredients (Raw Materials) for dropdown
        function loadIngredients() {
            $.ajax({
                url: baseUrl + 'RawMaterials/GetAll',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let options = '<option value="">Select Ingredient</option>';
                        response.data.forEach(function(mat) {
                            options += '<option value="' + mat.material_id + '" data-name="' + mat.material_name + '" data-cost="' + mat.cost_per_unit + '" data-unit="' + mat.unit + '">' + mat.material_name + ' (₱' + parseFloat(mat.cost_per_unit || 0).toFixed(3) + '/' + mat.unit + ')</option>';
                        });
                        $('#ingredient_id').html(options);
                    }
                }
            });
        }

        // Add Ingredient to List
        $('#btnAddIngredient').on('click', function() {
            const select = $('#ingredient_id');
            const selectedOption = select.find('option:selected');
            const ingredientId = select.val();
            const ingredientName = selectedOption.data('name');
            const costPerUnit = parseFloat(selectedOption.data('cost')) || 0;
            const quantity = parseFloat($('#ingredient_quantity').val()) || 0;
            const unit = $('#ingredient_unit').val();

            if (!ingredientId || quantity <= 0) {
                alert('Please select an ingredient and enter a valid quantity.');
                return;
            }

            // Check if ingredient already exists
            const existingIndex = ingredientsList.findIndex(item => item.id === ingredientId);
            if (existingIndex >= 0) {
                alert('This ingredient is already added. Remove it first to add again.');
                return;
            }

            const totalCost = costPerUnit * quantity;

            ingredientsList.push({
                id: ingredientId,
                name: ingredientName,
                quantity: quantity,
                unit: unit,
                costPerUnit: costPerUnit,
                totalCost: totalCost
            });

            updateIngredientsListDisplay();
            updateCostingDisplay();

            // Reset ingredient inputs
            $('#ingredient_id').val('');
            $('#ingredient_quantity').val('');
        });

        // Remove Ingredient from List
        $(document).on('click', '.btn-remove-ingredient', function() {
            const index = $(this).data('index');
            const ingredientName = ingredientsList[index].name;
            
            if (confirm('Are you sure you want to remove "' + ingredientName + '" from the ingredients list?')) {
                ingredientsList.splice(index, 1);
                updateIngredientsListDisplay();
                updateCostingDisplay();
            }
        });

        // Update Ingredients List Display
        function updateIngredientsListDisplay() {
            if (ingredientsList.length === 0) {
                $('#ingredientsList').html('<p class="text-sm text-gray-500 text-center py-2">No ingredients added yet</p>');
                return;
            }

            let html = '';
            ingredientsList.forEach(function(item, index) {
                html += '<div class="flex items-center justify-between p-2 border border-gray-200 rounded-md bg-white">';
                html += '<div class="flex-1">';
                html += '<div class="text-sm font-medium text-gray-800">' + item.name + '</div>';
                html += '<div class="text-xs text-gray-500">' + item.quantity + ' ' + item.unit + ' × ₱' + item.costPerUnit.toFixed(3) + ' = ₱' + item.totalCost.toFixed(2) + '</div>';
                html += '</div>';
                html += '<button type="button" class="text-red-600 hover:text-red-800 btn-remove-ingredient" data-index="' + index + '" title="Remove"><i class="fas fa-times"></i></button>';
                html += '</div>';
            });
            $('#ingredientsList').html(html);
        }

        // Update Costing Display
        function updateCostingDisplay() {
            const directCost = ingredientsList.reduce((sum, item) => sum + item.totalCost, 0);
            const overheadCost = parseFloat($('#overheadCost').val()) || 0;
            const totalCost = directCost + overheadCost;
            const profitMargin = parseFloat($('#profitMargin').val()) || 0;
            const profitAmount = totalCost * (profitMargin / 100);
            const sellingPrice = totalCost + profitAmount;

            $('#directCostDisplay').text('₱ ' + directCost.toFixed(2));
            $('#totalCostDisplay').text('₱ ' + totalCost.toFixed(2));
            $('#profitAmountDisplay').text('₱ ' + profitAmount.toFixed(2));
            $('#sellingPriceDisplay').text('₱ ' + sellingPrice.toFixed(2));
        }

        // Recalculate on overhead/profit change
        $('#overheadCost, #profitMargin').on('input', function() {
            updateCostingDisplay();
        });

        // Open Manage Product Categories Modal
        $('#btnManageCategories').on('click', function() {
            $('#manageCategoriesModal').removeClass('hidden');
            loadCategoriesList();
        });

        // Close Product Category Modal
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

        // Load Product Categories List for Management
        function loadCategoriesList() {
            $.ajax({
                url: baseUrl + 'Products/GetCategories',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let html = '';
                        response.data.forEach(function(cat) {
                            html += '<div class="flex items-center justify-between p-2 border border-gray-200 rounded-md bg-gray-50">';
                            html += '<div class="flex-1">';
                            html += '<div class="font-medium text-gray-800">' + cat.category_name + '</div>';
                            if (cat.category_description) {
                                html += '<div class="text-xs text-gray-500">' + cat.category_description + '</div>';
                            }
                            html += '</div>';
                            html += '<div class="flex gap-2">';
                            html += '<button class="text-blue-600 hover:text-blue-800 btn-edit-category" data-id="' + cat.category_id + '" data-name="' + cat.category_name + '" data-desc="' + (cat.category_description || '') + '" title="Edit"><i class="fas fa-edit"></i></button>';
                            html += '<button class="text-red-600 hover:text-red-800 btn-delete-category" data-id="' + cat.category_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
                            html += '</div>';
                            html += '</div>';
                        });
                        $('#categoriesList').html(html || '<p class="text-sm text-gray-500 text-center py-4">No categories yet</p>');
                    }
                }
            });
        }

        // Submit Product Category Form (Add/Edit)
        $('#categoryForm').on('submit', function(e) {
            e.preventDefault();
            
            const categoryId = $('#edit_category_id').val();
            const formData = {
                category_name: $('#category_name').val(),
                category_description: $('#category_description').val()
            };

            if (categoryId) {
                formData.category_id = categoryId;
            }

            $.ajax({
                url: baseUrl + 'MaterialCategory/Add',
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(categoryId ? 'Product Category updated successfully!' : 'Product Category added successfully!');
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
                    alert('Error saving category: ' + error);
                }
            });
        });

        // Edit Product Category
        $(document).on('click', '.btn-edit-category', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const desc = $(this).data('desc');
            
            $('#edit_category_id').val(id);
            $('#category_name').val(name);
            $('#category_description').val(desc);
            $('#btnSaveCategory').text('Update');
        });

        // Delete Product Category
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
                            alert('Product Category deleted successfully!');
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

        // Load Product Categories for Filter dropdown
        function loadFilterCategories() {
            $.ajax({
                url: baseUrl + 'Products/GetCategories',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let options = '<option value="">All Product Categories</option>';
                        response.data.forEach(function(cat) {
                            options += '<option value="' + cat.category_id + '">' + cat.category_name + '</option>';
                        });
                        $('#filter-category').html(options);
                    }
                }
            });
        }

        // Load Product Categories for Modal dropdown
        function loadCategories() {
            $.ajax({
                url: baseUrl + 'Products/GetCategories',
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

        // Load products via AJAX
        function loadMaterials() {
            $.ajax({
                url: baseUrl + 'Products/GetAll',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Destroy existing DataTable first
                    if (dataTable) {
                        dataTable.destroy();
                        dataTable = null;
                    }

                    let rows = '';
                    if (response.success && response.data && response.data.length > 0) {
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
                    }
                    $('#materialsTableBody').html(rows);

                    // Initialize DataTable with custom labels
                    const tableElement = document.getElementById('selection-table');
                    if (tableElement && typeof simpleDatatables !== 'undefined') {
                        dataTable = new simpleDatatables.DataTable('#selection-table', {
                            labels: {
                                placeholder: "Search products...",
                                perPage: "entries per page",
                                noRows: "No product data available",
                                noResults: "No results match your search",
                                info: "Showing {start} to {end} of {rows} entries"
                            },
                            perPage: 10,
                            perPageSelect: [5, 10, 25, 50]
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error loading products: ' + error);
                    // Still initialize DataTable on error to show controls
                    if (dataTable) {
                        dataTable.destroy();
                        dataTable = null;
                    }
                    $('#materialsTableBody').html('');
                    const tableElement = document.getElementById('selection-table');
                    if (tableElement && typeof simpleDatatables !== 'undefined') {
                        dataTable = new simpleDatatables.DataTable('#selection-table', {
                            labels: {
                                placeholder: "Search products...",
                                perPage: "entries per page",
                                noRows: "No product data available",
                                noResults: "No results match your search",
                                info: "Showing {start} to {end} of {rows} entries"
                            },
                            perPage: 10,
                            perPageSelect: [5, 10, 25, 50]
                        });
                    }
                }
            });
        }

        // Submit Add Product Form via AJAX
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
                url: baseUrl + 'Products/AddRawMaterial',
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Product added successfully!');
                        closeModal();
                        loadMaterials();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error adding product: ' + error);
                }
            });
        });

        // Delete Product
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this product?')) {
                $.ajax({
                    url: baseUrl + 'Products/delete/' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Product deleted successfully!');
                            loadMaterials();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error deleting product: ' + error);
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
