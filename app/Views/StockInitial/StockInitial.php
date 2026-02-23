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
                        <button type="button" id="btnAddEntry"
                            class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            Add Stock Entry
                        </button>
                    </div>
                </div>

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

            <!-- Desktop Table View -->
            <div class="hidden lg:block p-4 bg-white rounded-lg shadow-md mb-20 sm:mb-0">
                <table id="stockInitialTable" class="min-w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">Material Name</span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">Category</span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">Initial Qty</span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">Used</span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">Remaining</span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">Unit</span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">Date Updated</span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="stockInitialTableBody">
                        <!-- Data loaded via AJAX -->
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
                        <input type="text" id="mobileSearch" placeholder="Search stock entries..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                    </div>
                </div>

                <!-- Cards Container -->
                <div id="stockInitialCardsContainer" class="space-y-3">
                    <!-- Cards loaded via AJAX -->
                </div>

                <!-- Mobile Pagination -->
                <div id="mobilePagination" class="mt-4 flex items-center justify-center gap-2">
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
                        <div class="flex">
                            <label for="initial_qty" class="flex-1 block text-sm font-medium text-gray-700 mb-1">
                                Stock On Hand <span class="text-red-500">*</span>
                            </label>
                        </div>
                        <input type="number" name="initial_qty" id="initial_qty"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="0" min="0" step="0.00001" required>
                    </div>
                    <div id="remaining_qty_wrapper" class="hidden">
                        <div class="flex">
                            <label for="remaining_qty" class="flex-1 block text-sm font-medium text-gray-700 mb-1">
                                Remaining
                            </label>
                        </div>
                        <input type="number" name="remaining_qty" id="remaining_qty"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="0" min="0" step="0.00001">
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

    <!-- App Scripts -->
    <script>
    window.BASE_URL = '<?= rtrim(site_url(), '/') ?>/';
    </script>
    <script src="<?= base_url('js/StockInitial.js') ?>"></script>