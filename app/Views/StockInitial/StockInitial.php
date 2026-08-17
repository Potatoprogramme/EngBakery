<?php $isStaffView = false; ?>

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
                    <li class="text-gray-700">Material Stock</li>
                </ol>
            </nav>

            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Material Stock</h2>
                    <div class="flex flex-wrap gap-2">
                        <?php if (!$isStaffView): ?>
                            <button type="button" id="btnAddEntry"
                                class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                                Add Stock Entry
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Divider -->
                <div class="border-t border-gray-200 my-4"></div>

                <?php if (!$isStaffView): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 mb-4">
                        <div class="bg-white rounded-lg shadow-md border border-green-100 p-4">
                            <p class="text-xs uppercase tracking-wide text-green-700 font-semibold">Grand Total Initial Cost</p>
                            <p id="totalInitialCostCard" class="mt-1 text-2xl font-bold text-green-700 tabular-nums">₱0.00</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-md border border-orange-100 p-4">
                            <p class="text-xs uppercase tracking-wide text-orange-700 font-semibold">Grand Total Used Cost</p>
                            <p id="totalUsedCostCard" class="mt-1 text-2xl font-bold text-orange-700 tabular-nums">₱0.00</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-md border border-blue-100 p-4 sm:col-span-2 xl:col-span-1">
                            <p class="text-xs uppercase tracking-wide text-blue-700 font-semibold">Grand Total Remaining Cost</p>
                            <p id="totalRemainingCostCard" class="mt-1 text-2xl font-bold text-blue-700 tabular-nums">₱0.00</p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Divider -->
                <div class="border-t border-gray-200 my-4"></div>

                <!-- Filters -->
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

            <!-- Floating Add button for mobile -->
            <div class="fixed bottom-6 left-0 right-0 flex justify-center z-30 sm:hidden">
                <button type="button" id="btnAddEntryMobile"
                    class="w-5/6 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    Add Stock Entry
                </button>
            </div>

            <!-- Stock Level Legend -->
            <div class="flex items-center gap-4 mb-3 px-1 text-xs text-gray-400">
                <span class="inline-flex items-center gap-1.5">
                    <span class="inline-block w-5 h-1.5 rounded-full bg-red-500"></span>
                    <span class="text-gray-500">Critical (≤10%)</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="inline-block w-5 h-1.5 rounded-full bg-amber-400"></span>
                    <span class="text-gray-500">Low (≤25%)</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="inline-block w-5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span class="text-gray-500">Healthy</span>
                </span>
            </div>

            <!-- Tablet/Desktop Table View -->
            <div class="hidden md:block w-full p-3 md:p-4 bg-white rounded-lg shadow-md mb-20 md:mb-0 overflow-x-auto">
                <table id="stockInitialTable" class="w-full min-w-full table-auto text-sm text-left text-gray-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 md:px-4 lg:px-6 py-3">
                                <span class="flex items-center">Material Name</span>
                            </th>
                            <th scope="col" class="px-3 md:px-4 lg:px-6 py-3">
                                <span class="flex items-center">Category</span>
                            </th>
                            <th scope="col" class="px-3 md:px-4 lg:px-6 py-3">
                                <span class="flex items-center">Initial Qty</span>
                            </th>
                            <th scope="col" class="px-3 md:px-4 lg:px-6 py-3">
                                <span class="flex items-center">Used</span>
                            </th>
                            <th scope="col" class="px-3 md:px-4 lg:px-6 py-3">
                                <span class="flex items-center">Remaining</span>
                            </th>
                            <th scope="col" class="px-3 md:px-4 lg:px-6 py-3">
                                <span class="flex items-center">Unit</span>
                            </th>
                            <?php if (!$isStaffView): ?>
                                <th scope="col" class="px-3 md:px-4 lg:px-6 py-3 text-green-700">
                                    <span class="flex items-center">Initial Cost</span>
                                </th>
                                <th scope="col" class="px-3 md:px-4 lg:px-6 py-3 text-orange-700">
                                    <span class="flex items-center">Used Cost</span>
                                </th>
                                <th scope="col" class="px-3 md:px-4 lg:px-6 py-3 text-blue-700">
                                    <span class="flex items-center">Remaining Cost</span>
                                </th>
                                <th scope="col" class="px-3 md:px-4 lg:px-6 py-3">
                                    <span class="flex items-center">Date Updated</span>
                                </th>
                                <th scope="col" class="px-3 md:px-4 lg:px-6 py-3 whitespace-nowrap">
                                    <span class="flex items-center">Actions</span>
                                </th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody id="stockInitialTableBody">
                        <!-- Data loaded via AJAX -->
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden mb-24">
                <!-- Search Bar for Mobile -->
                <div class="mb-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="mobileSearch" placeholder="Search stock entries..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                    </div>
                </div>

                <!-- Cards Container -->
                <div id="stockInitialCardsContainer" class="space-y-3">
                    <!-- Cards loaded via AJAX -->
                </div>

                <!-- Mobile Pagination -->
                <div id="mobilePagination" class="mt-4 flex flex-wrap items-center justify-center gap-1">
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="stockInitialModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 border shadow-lg rounded-md bg-white"
            style="max-width: 42rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary" id="modalTitle">Add Stock Entry</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="stockInitialForm">
                <input type="hidden" id="edit_stock_id" value="">
                <input type="hidden" id="edit_cost_per_unit" value="0">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Raw Material <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" id="material_search"
                            class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Search material..." autocomplete="off" required>
                        <button type="button" id="btnClearMaterial"
                            class="hidden absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                        <div id="material_dropdown"
                            class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto">
                        </div>
                        <input type="hidden" name="material_id" id="material_id">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-3 mb-3 sm:grid-cols-2">
                    <div>
                        <label for="initial_qty" class="block text-sm font-medium text-gray-700 mb-1">
                            Stock On Hand <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="initial_qty" id="initial_qty"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="0" min="0" step="0.00001" required>
                    </div>
                    <div id="add_stock_wrapper" class="hidden">
                        <label for="add_stock_qty" class="block text-sm font-medium text-emerald-700 mb-1">
                            Add Stock
                        </label>
                        <input type="number" name="add_stock_qty" id="add_stock_qty"
                            class="w-full px-3 py-2 border border-emerald-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-400 bg-emerald-50"
                            placeholder="0" min="0" step="0.00001" value="0">
                        <span class="text-xs text-gray-500 mt-1 block">Auto-adds to Stock On Hand.</span>
                    </div>
                    <div id="qty_used_wrapper" class="hidden">
                        <label class="block text-sm font-medium text-orange-600 mb-1">
                            Used (auto-calculated)
                        </label>
                        <input type="text" id="qty_used_display" readonly
                            class="w-full px-3 py-2 border border-orange-200 rounded-md bg-orange-50 text-orange-700 font-semibold cursor-not-allowed"
                            value="0">
                    </div>
                </div>
                <!-- Remaining (editable input) -->
                <div id="remaining_qty_wrapper" class="hidden mb-3">
                    <label for="remaining_qty" class="block text-sm font-medium text-blue-600 mb-1">
                        Remaining <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="remaining_qty" id="remaining_qty"
                        class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50"
                        placeholder="0" step="0.00001" value="0">
                    <span id="remaining_error" class="text-red-500 text-xs mt-1 hidden">Remaining cannot exceed Stock On
                        Hand.</span>
                </div>
                <!-- Dynamic Cost Breakdown (edit mode only) -->
                <div id="cost_breakdown_wrapper" class="hidden mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cost Breakdown</label>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-2">
                            <p class="text-xs text-gray-500">Initial Cost</p>
                            <p id="display_initial_cost" class="text-sm font-bold text-green-700">₱0.00</p>
                        </div>
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-2">
                            <p class="text-xs text-gray-500">Used Cost</p>
                            <p id="display_used_cost" class="text-sm font-bold text-orange-700">₱0.00</p>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-2">
                            <p class="text-xs text-gray-500">Remaining Cost</p>
                            <p id="display_remaining_cost" class="text-sm font-bold text-blue-700">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit <span
                            class="text-red-500">*</span></label>
                    <select name="unit" id="unit"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        required>
                        <option value="grams">grams</option>
                        <option value="ml">ml</option>
                        <option value="pcs">pcs</option>
                    </select>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelAdd"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                    <button type="submit" id="btnSaveEntry"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-sm mx-auto p-6 border shadow-lg rounded-md bg-white">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Entry</h3>
                <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete this stock entry? This action
                    cannot be undone.</p>
                <div class="flex gap-3 justify-center">
                    <button type="button" id="btnCancelDelete"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                    <button type="button" id="btnConfirmDelete"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Warning Modal -->
    <div id="editStockWarningModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-sm mx-auto p-6 border shadow-lg rounded-md bg-white">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-amber-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Used Exceeds Stock On Hand</h3>
                <p id="editStockWarningMessage" class="text-sm text-gray-600 mb-6">This update will make remaining stock negative.</p>
                <div class="flex gap-3 justify-center">
                    <button type="button" id="btnCancelEditWarning"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                    <button type="button" id="btnProceedEditWarning"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Proceed</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Stock Entry Modal -->
    <div id="viewStockModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-6 border shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto"
            style="max-width: 42rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary">Stock Entry Details</h3>
                <button type="button" id="btnCloseViewModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Material Name & Category -->
            <div class="mb-4 p-4 bg-gradient-to-r from-primary/10 to-primary/5 rounded-lg border border-primary/20">
                <h2 id="view_material_name" class="text-xl font-bold text-gray-800 mb-1"></h2>
                <span id="view_category"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/20 text-primary"></span>
            </div>

            <!-- Stock Quantities -->
            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">Stock Quantities</h4>
                <div class="grid grid-cols-3 gap-2">
                    <div class="bg-blue-50 rounded-lg border border-blue-200 p-3 text-center">
                        <p class="text-xs text-gray-500 mb-1">Initial</p>
                        <p id="view_initial_qty" class="text-sm font-bold text-blue-700">0</p>
                    </div>
                    <div class="bg-orange-50 rounded-lg border border-orange-200 p-3 text-center">
                        <p class="text-xs text-gray-500 mb-1">Used</p>
                        <p id="view_used_qty" class="text-sm font-bold text-orange-700">0</p>
                    </div>
                    <div class="bg-emerald-50 rounded-lg border border-emerald-200 p-3 text-center">
                        <p class="text-xs text-gray-500 mb-1">Remaining</p>
                        <p id="view_remaining_qty" class="text-sm font-bold text-emerald-700">0</p>
                    </div>
                </div>
                <!-- Stock Health Bar -->
                <div class="mt-2">
                    <div id="view_health_bar_track" class="h-2 rounded-full bg-emerald-100 overflow-hidden">
                        <div id="view_health_bar" class="h-full rounded-full bg-emerald-400 transition-all"
                            style="width:0%"></div>
                    </div>
                    <p id="view_health_label" class="text-xs text-gray-500 mt-1 text-right">0%</p>
                </div>
            </div>

            <!-- Cost Breakdown -->
            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">Cost Breakdown</h4>
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-3 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Cost per Unit</span>
                        <span id="view_cost_per_unit" class="text-sm font-medium text-gray-900">₱0.000</span>
                    </div>
                    <div class="border-t border-gray-200 pt-2">
                        <div class="grid grid-cols-3 gap-2">
                            <div class="text-center">
                                <p class="text-xs text-gray-500">Initial Cost</p>
                                <p id="view_initial_cost" class="text-sm font-bold text-green-700">₱0.00</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500">Used Cost</p>
                                <p id="view_used_cost" class="text-sm font-bold text-orange-700">₱0.00</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500">Remaining Cost</p>
                                <p id="view_remaining_cost" class="text-sm font-bold text-blue-700">₱0.00</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Unit & Date -->
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-1">Unit</h4>
                    <span id="view_unit"
                        class="inline-flex items-center text-sm bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full font-medium border border-gray-200"></span>
                </div>
                <div class="text-right">
                    <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-1">Last Updated</h4>
                    <span id="view_date" class="text-sm text-gray-500"></span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 justify-end border-t border-gray-200 pt-4">
                <button type="button" id="btnCloseViewBottom"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    Close
                </button>
                <button type="button" id="btnViewEditEntry"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">
                    <i class="fas fa-edit me-1"></i> Edit
                </button>
                <button type="button" id="btnViewDeleteEntry"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                    <i class="fas fa-trash me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <!-- App Scripts -->
    <script>
        window.BASE_URL = '<?= rtrim(site_url(), '/') ?>/';
        window.USER_ROLE = '<?= esc(strtolower((string) (session('employee_type') ?? ''))) ?>';
    </script>
    <script src="<?= asset_url('js/StockInitial.js') ?>"></script>