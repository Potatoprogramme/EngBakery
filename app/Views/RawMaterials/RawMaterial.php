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
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li class="text-gray-700">Material Costing</li>
                </ol>
            </nav>
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Material Costing Lists</h2>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="btnAddMaterial"
                            class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            Add Material Costing
                        </button>
                        </button>
                        <!-- Enable Export Button -->
                        <!-- <button type="button" id="btnExport"
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            Export
                        </button> -->
                        <!-- Disable Export Button -->
                        <button type="button" id="btnExport" disabled
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
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
                            <select id="filter-category"
                                class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                                <option value="">All Categories</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-center sm:justify-end">
                        <button id="apply-filters" type="button"
                            class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">Apply</button>
                        <button id="reset-filters" type="button"
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Stock Level Legend -->
            <div class="flex items-center gap-4 mb-3 px-1 text-xs text-gray-400">
                <span class="inline-flex items-center gap-1.5">
                    <span class="inline-block w-5 h-1.5 rounded-full bg-red-500"></span>
                    <span class="text-gray-500">Critical (≤10)</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="inline-block w-5 h-1.5 rounded-full bg-amber-400"></span>
                    <span class="text-gray-500">Low (≤25)</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="inline-block w-5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span class="text-gray-500">Healthy</span>
                </span>
            </div>

            <!-- Floating Add Material button for mobile -->
            <div class="fixed bottom-6 left-0 right-0 flex justify-center z-30 sm:hidden">
                <button type="button" id="btnAddMaterialMobile"
                    class="w-5/6 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    Add Material Costing
                </button>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block p-4 bg-white rounded-lg shadow-md mb-20 sm:mb-0">
                <table id="selection-table" class="min-w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">
                                    Material Name
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">
                                    Category
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">
                                    Label
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">
                                    Quantity
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">
                                    Unit
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap min-w-[120px]">
                                <span class="flex items-center">
                                    Cost per Unit
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap min-w-[120px]">
                                <span class="flex items-center">
                                    Total Cost
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="materialsTableBody">
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden mb-24">
                <!-- Search Bar for Mobile -->
                <div class="mb-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="mobileSearch" placeholder="Search materials..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                    </div>
                </div>

                <!-- Cards Container -->
                <div id="materialsCardsContainer" class="space-y-3">
                    <!-- Cards will be loaded via AJAX -->
                </div>

                <!-- Mobile Pagination -->
                <div id="mobilePagination" class="mt-4 flex items-center justify-center gap-2">
                    <!-- Pagination will be generated via JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Add Material Modal -->
    <div id="addMaterialModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md max-h-42 mx-auto p-4 sm:p-4 border shadow-lg rounded-md bg-white"
            style="max-width: 42rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary" id="modalTitle">Add Material Costing</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addMaterialForm">
                <input type="hidden" id="edit_material_id" value="">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Material Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="material_name" id="material_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="e.g., Flour - All Purpose" required>
                    <p id="material_name_error" class="text-red-500 text-xs mt-1 hidden">This material name already
                        exists.</p>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Category <span
                            class="text-red-500">*</span></label>
                    <div class="flex gap-2 items-center">
                        <select name="category_id" id="category_id"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                            <option value="">Select</option>
                        </select>
                        <button type="button" id="btnManageCategories"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary"
                            title="Manage Categories">
                            Manage
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-3 mb-3 sm:grid-cols-2">
                    <div>
                        <div class="flex">
                            <label for="initial_quantity"
                                class="flex-1 block text-sm font-medium text-gray-700 mb-1">Quantity <span
                                    class="text-red-500">*</span></label>
                            <label for="unit" class="w-32 block text-sm font-medium text-gray-700 mb-1">Unit of
                                Measure</label>
                        </div>
                        <div class="flex">
                            <input type="number" name="initial_quantity" id="initial_quantity"
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary border-r-0"
                                placeholder="25000" min="0" step="any" required>
                            <select name="unit" id="unit"
                                class="w-32 px-3 py-2 border border-gray-300 bg-gray-50 text-gray-700 rounded-r-md focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                                <option value="grams">grams</option>
                                <option value="ml">ml</option>
                                <option value="pcs">pcs</option>
                            </select>
                        </div>
                        <p id="qty_readonly_hint" class="text-xs text-gray-400 mt-1 hidden">
                            <i class="fas fa-info-circle"></i> Updating the quantity will also update the Stock Initial
                            page.
                        </p>
                    </div>

                    <div id="converted_qty_wrapper">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Converted</label>
                        <div class="flex">
                            <input type="text" id="converted_quantity" readonly
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-gray-600 focus:outline-none border-r-0"
                                value="0">
                            <span id="converted_unit_label"
                                class="inline-flex items-center justify-center w-20 px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 rounded-r-md font-medium text-sm">
                                kg
                            </span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-3 mb-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Cost <span
                                class="text-red-500">*</span></label>
                        <div class="relative flex items-center">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <input type="number" name="total_cost" id="total_cost"
                                class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="1350.00" step="0.00001" min="0" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cost per Unit</label>

                        <!-- Base Unit Cost -->
                        <div class="flex mb-2">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="text" id="cost_per_unit" readonly
                                    class="block w-full pl-7 pr-3 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-gray-600 focus:outline-none border-r-0"
                                    value="0.000">
                            </div>
                            <span id="cost_unit_label"
                                class="inline-flex items-center justify-center w-24 px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 rounded-r-md font-medium text-xs sm:text-sm whitespace-nowrap">
                                per unit
                            </span>
                        </div>

                        <!-- Converted Unit Cost -->
                        <div id="converted_cost_wrapper" class="hidden flex">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="text" id="converted_cost" readonly
                                    class="block w-full pl-7 pr-3 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-gray-600 focus:outline-none border-r-0"
                                    value="0.00">
                            </div>
                            <span id="converted_cost_unit_label"
                                class="inline-flex items-center justify-center w-24 px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 rounded-r-md font-medium text-xs sm:text-sm whitespace-nowrap">
                                per kg
                            </span>
                        </div>
                        <!-- Converted cost display (per kg/liter) -->
                        <div id="converted_cost_display" class="text-xs text-gray-500 mt-1 hidden"></div>
                    </div>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelAdd"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                    <button type="submit" id="btnSaveMaterial"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Material Modal -->
    <div id="viewMaterialModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-6 border shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto"
            style="max-width: 42rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary">Material Details</h3>
                <button type="button" id="btnCloseViewModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Material Name & Category -->
            <div class="mb-4 p-4 bg-gradient-to-r from-primary/10 to-primary/5 rounded-lg border border-primary/20">
                <h2 id="view_material_name" class="text-xl font-bold text-gray-800 mb-1"></h2>
                <div class="flex items-center gap-2">
                    <span id="view_category"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/20 text-primary">
                    </span>
                    <span id="view_label_badge"></span>
                </div>
            </div>

            <!-- Stock Information -->
            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">
                    Stock Information
                </h4>
                <div id="view_stock_section" class="bg-gray-50 rounded-lg border border-gray-200 p-3">
                </div>
            </div>

            <!-- Cost Breakdown -->
            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">
                    Cost Breakdown
                </h4>
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-3 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Quantity</span>
                        <span id="view_quantity" class="text-sm font-medium text-gray-900">0</span>
                    </div>
                    <div id="view_converted_qty_row" class="flex justify-between items-center hidden">
                        <span class="text-sm text-gray-600">Converted</span>
                        <span id="view_converted_qty" class="text-sm font-medium text-gray-500">0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Cost per Unit</span>
                        <span id="view_cost_per_unit" class="text-sm font-medium text-gray-900">₱ 0.000</span>
                    </div>
                    <div id="view_converted_cost_row" class="flex justify-between items-center hidden">
                        <span class="text-sm text-gray-600" id="view_converted_cost_label">Cost per kg</span>
                        <span id="view_converted_cost" class="text-sm font-medium text-gray-500">₱ 0.00</span>
                    </div>
                    <div class="border-t border-gray-300 pt-2 flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-700">Total Cost</span>
                        <span id="view_total_cost" class="text-sm font-bold text-primary">₱ 0.00</span>
                    </div>
                </div>
            </div>

            <!-- Unit of Measure -->
            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">
                    Unit of Measure
                </h4>
                <span id="view_unit" class="inline-flex items-center text-sm bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full font-medium border border-gray-200"></span>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 justify-end border-t border-gray-200 pt-4">
                <button type="button" id="btnCloseViewBottom"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    Close
                </button>
                <button type="button" id="btnViewEditMaterial"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">
                    <i class="fas fa-edit me-1"></i> Edit
                </button>
                <button type="button" id="btnViewDeleteMaterial"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                    <i class="fas fa-trash me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Category Modal Component -->
    <?= view('MaterialCategories/CategoryModal') ?>



    <!-- App Scripts -->
    <script>
    // Set base URL for JS modules
    window.BASE_URL = '<?= rtrim(site_url(), '/') ?>/';
    </script>
    <script src="<?= base_url('js/CategoryModal.js') ?>"></script>
    <script src="<?= base_url('js/RawMaterial.js') ?>"></script>