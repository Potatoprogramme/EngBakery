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
                    <li class="text-gray-700">Inventory</li>
                </ol>
            </nav>
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Daily Inventory Lists</h2>
                    <div class="flex flex-wrap gap-2">
                        <a href="<?= base_url('Inventory/History') ?>"
                            class="hidden sm:inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <i class="fas fa-history mr-2"></i> History
                        </a>
                        <button id="btnAddProductToInventory" type="button"
                            class="hidden sm:inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400">
                            <i class="fas fa-plus mr-2"></i> Add Product
                        </button>
                        <button id="btnAddTodaysInventory" type="button"
                            class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            Add Today's Inventory
                        </button>
                        <button id="btnDeleteTodaysInventory" type="button"
                            class="hidden sm:inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400">
                            Delete Today's Inventory
                        </button>
                    </div>
                </div>

                <!-- Divider line -->
                <div class="border-t border-gray-200 my-4"></div>

                <!-- Filters section -->
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <!-- Date Display -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700">Date:</label>
                            <span id="todayDate"
                                class="text-sm font-semibold text-gray-900 px-3 py-2 bg-gray-50 rounded-md border border-gray-200"></span>
                        </div>

                        <!-- Time Range Display -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700">Time:</label>
                            <span id="timeRange"
                                class="text-sm font-semibold text-gray-900 px-3 py-2 bg-gray-50 rounded-md border border-gray-200">--:--
                                - --:--</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating buttons for mobile -->
            <div class="fixed bottom-6 left-0 right-0 flex justify-center gap-2 z-30 sm:hidden px-6">
                <button id="btnAddTodaysInventoryMobile" type="button"
                    class="flex-1 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    Add Inventory
                </button>
                <button id="btnDeleteTodaysInventoryMobile" type="button"
                    class="flex-1 inline-flex items-center justify-center rounded-lg bg-red-600 px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400">
                    Delete
                </button>
            </div>

            <!-- Category Tabs -->
            <div class="flex gap-2 mb-3">
                <button type="button" data-tab="bakery" onclick="switchTab('bakery')"
                    class="tab-btn flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-all border-2 border-primary text-white bg-primary shadow-md cursor-pointer">
                    <i class="fas fa-bread-slice mr-1.5 hidden sm:inline"></i> Bakery
                </button>
                <button type="button" data-tab="drinks" onclick="switchTab('drinks')"
                    class="tab-btn flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-all border-2 border-gray-300 text-gray-700 bg-gray-100 hover:bg-gray-200 hover:border-gray-400 cursor-pointer">
                    <i class="fas fa-mug-hot mr-1.5 hidden sm:inline"></i> Drinks
                </button>
                <button type="button" data-tab="grocery" onclick="switchTab('grocery')"
                    class="tab-btn flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-all border-2 border-gray-300 text-gray-700 bg-gray-100 hover:bg-gray-200 hover:border-gray-400 cursor-pointer">
                    <i class="fas fa-shopping-basket mr-1.5 hidden sm:inline"></i> Grocery
                </button>
            </div>

            <!-- Tab Content: Bakery -->
            <div id="bakery-content" class="tab-content mb-20 sm:mb-0">
                <!-- Mobile Card View -->
                <div class="sm:hidden">
                    <div id="bakeryMobileCards" class="space-y-3">
                        <!-- Cards will be loaded via AJAX -->
                    </div>
                </div>
                <!-- Desktop Table View -->
                <div class="hidden sm:block">
                    <div class="bg-white rounded border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="bakeryTable" class="min-w-full text-sm text-left">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">Items/Particulars
                                        </th>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">SRP</th>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">Beginning</th>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">Pull Out</th>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">Ending</th>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">Qty Sold</th>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="bakeryTableBody">
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                                <tfoot class="bg-gray-50 border-t border-gray-200">
                                    <tr>
                                        <td colspan="5" class="px-6 py-2 text-right text-xs text-gray-500 font-medium">
                                            Total:</td>
                                        <td class="px-6 py-2 text-sm font-medium text-gray-700" id="bakeryTotalQty">0
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Drinks -->
            <div id="drinks-content" class="tab-content hidden mb-20 sm:mb-0">
                <!-- Mobile Card View -->
                <div class="sm:hidden">
                    <div id="drinksMobileCards" class="space-y-3">
                        <!-- Cards will be loaded via AJAX -->
                    </div>
                </div>
                <!-- Desktop Table View -->
                <div class="hidden sm:block">
                    <div class="bg-white rounded border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="drinksTable" class="min-w-full text-sm text-left">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">Items/Particulars
                                        </th>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">SRP</th>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">Qty Sold</th>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="drinksTableBody">
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                                <tfoot class="bg-gray-50 border-t border-gray-200">
                                    <tr>
                                        <td colspan="2" class="px-6 py-2 text-right text-xs text-gray-500 font-medium">
                                            Total:</td>
                                        <td class="px-6 py-2 text-sm font-medium text-gray-700" id="drinksTotalQty">0
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Grocery -->
            <div id="grocery-content" class="tab-content hidden mb-20 sm:mb-0">
                <!-- Mobile Card View -->
                <div class="sm:hidden">
                    <div id="groceryMobileCards" class="space-y-3">
                        <!-- Cards will be loaded via AJAX -->
                    </div>
                </div>
                <!-- Desktop Table View -->
                <div class="hidden sm:block">
                    <div class="bg-white rounded border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="groceryTable" class="min-w-full text-sm text-left">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">Items/Particulars
                                        </th>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">SRP</th>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">Beginning</th>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">Pull Out</th>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">Ending</th>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">Qty Sold</th>
                                        <th scope="col" class="px-6 py-3 font-medium text-gray-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="groceryTableBody">
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                                <tfoot class="bg-gray-50 border-t border-gray-200">
                                    <tr>
                                        <td colspan="5" class="px-6 py-2 text-right text-xs text-gray-500 font-medium">
                                            Total:</td>
                                        <td class="px-6 py-2 text-sm font-medium text-gray-700" id="groceryTotalQty">0
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Input Modal -->
            <div id="timeInputModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4">
                <div class="fixed inset-0 bg-black/40" id="timeInputModalBackdrop"></div>
                <div class="relative bg-white rounded-xl shadow-xl max-w-sm w-full p-6 z-10">
                    <button type="button" id="timeInputModalClose"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Create Today's Inventory</h3>
                    <p class="text-xs text-gray-500 mb-5">Set the operating hours for today.</p>
                    <form id="timeInputForm">
                        <div class="mb-4">
                            <label for="time_start" class="block mb-1.5 text-sm font-medium text-gray-700">Start
                                Time</label>
                            <input type="time" id="time_start" name="time_start" required
                                class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <p class="text-xs text-gray-400 mt-1">Morning (AM)</p>
                        </div>
                        <div class="mb-6">
                            <label for="time_end" class="block mb-1.5 text-sm font-medium text-gray-700">End
                                Time</label>
                            <input type="time" id="time_end" name="time_end" required
                                class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <p class="text-xs text-gray-400 mt-1">Afternoon/Evening (PM)</p>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit"
                                class="flex-1 text-white bg-primary hover:bg-primary/90 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                                Create Inventory
                            </button>
                            <button type="button" id="timeInputModalCancel"
                                class="flex-1 text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Inventory Modal -->
    <div id="editInventoryModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40" id="editInventoryModalBackdrop"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-sm w-full p-6 z-10">
            <button type="button" id="editInventoryModalClose"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Edit Inventory Item</h3>
            <p class="text-sm text-gray-500 mb-5">Product: <span id="editProductName"
                    class="font-medium text-gray-700"></span></p>

            <form id="editInventoryForm">
                <input type="hidden" id="editItemId" name="item_id">

                <div class="mb-4">
                    <label for="editBeginningStock" class="block mb-1.5 text-sm font-medium text-gray-700">Beginning
                        Stock</label>
                    <input type="number" id="editBeginningStock" name="beginning_stock" required min="0"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>

                <div class="mb-4">
                    <label for="editPullOutQuantity" class="block mb-1.5 text-sm font-medium text-gray-700">Pull Out
                        Quantity</label>
                    <input type="number" id="editPullOutQuantity" name="pull_out_quantity" required min="0"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 text-white bg-primary hover:bg-primary/90 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                        Update Item
                    </button>
                    <button type="button" id="editInventoryModalCancel"
                        class="flex-1 text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Product to Inventory Modal -->
    <div id="addProductModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 " id="addProductModalBackdrop"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-sm w-full p-6 z-10">
            <button type="button" id="addProductModalClose"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
            <h3 class="text-lg font-semibold text-gray-900 mb-5">Add Product to Inventory</h3>

            <form id="addProductForm">
                <div class="mb-4">
                    <label for="selectProduct" class="block mb-1.5 text-sm font-medium text-gray-700">Select
                        Product</label>
                    <select id="selectProduct" name="product_id" required
                        class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-all">
                        <option value="">-- Select a product --</option>
                    </select>
                    <p id="noProductsMessage" class="hidden mt-2 text-xs text-gray-500">All products are already in
                        inventory.</p>
                </div>

                <div class="mb-4">
                    <label for="addBeginningStock" class="block mb-1.5 text-sm font-medium text-gray-700">Beginning
                        Stock</label>
                    <input type="number" id="addBeginningStock" name="beginning_stock" min="0" value="0"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <p class="text-xs text-gray-400 mt-1">Optional - defaults to 0</p>
                </div>

                <!-- Deduction Preview -->
                <div id="deductionPreviewContainer" class="hidden mb-4">
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                        <p class="text-xs font-semibold text-amber-700 mb-2">
                            <i class="fas fa-flask mr-1"></i> Raw Materials to be Deducted
                        </p>
                        <div id="deductionPreviewList" class="space-y-1 text-xs text-gray-700 max-h-32 overflow-y-auto"></div>
                        <div id="deductionPreviewWarning" class="hidden mt-2 text-xs text-red-600 font-medium">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Some materials have insufficient stock
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" id="btnSubmitAddProduct"
                        class="flex-1 text-white bg-primary hover:bg-primary/90 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                        Add to Inventory
                    </button>
                    <button type="button" id="addProductModalCancel"
                        class="flex-1 text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50" id="deleteConfirmModalBackdrop"></div>
        <div class="relative bg-white rounded-lg shadow-lg max-w-md w-full p-6 z-10">
            <button type="button" id="deleteConfirmModalClose"
                class="absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                <i class="fas fa-xmark"></i>
            </button>
            <div class="text-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Delete Today's Inventory?</h3>
                <p class="text-gray-600 mb-6">Are you sure you want to delete today's entire inventory? This action
                    cannot be undone.</p>
            </div>
            <div class="flex gap-3">
                <button type="button" id="btnConfirmDelete"
                    class="flex-1 text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-400 font-medium rounded-lg text-sm px-5 py-2.5">
                    Delete
                </button>
                <button type="button" id="deleteConfirmModalCancel"
                    class="flex-1 text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    <script>
    window.BASE_URL = '<?= rtrim(site_url(), '/') ?>/';
    </script>
    <style>
    @media (max-width: 640px) {

        .datatable-top,
        .datatable-bottom {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            gap: 0.3rem !important;
            padding: 0.3rem 0;
        }

        .datatable-dropdown,
        .datatable-search,
        .datatable-info,
        .datatable-pagination {
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

    <script src="<?= base_url('js/inventory/inventory-api.js') ?>"></script>
    <script src="<?= base_url('js/inventory/inventory-modal.js') ?>"></script>
    <script src="<?= base_url('js/inventory/inventory-main.js') ?>"></script>
