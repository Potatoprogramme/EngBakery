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
            <!-- Mobile Card View -->
            <div class="sm:hidden mb-20">
                <!-- Mobile Search -->
                <div class="mb-3">
                    <div class="relative">
                        <input type="text" id="mobileSearchInput" placeholder="Search inventory..." 
                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary bg-white">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Mobile Cards Container -->
                <div id="mobileCardView" class="space-y-3">
                    <!-- Cards will be loaded via AJAX -->
                </div>

                <!-- Mobile Pagination -->
                <div id="mobilePagination" class="flex items-center justify-center gap-1 mt-4 flex-wrap">
                    <!-- Pagination will be generated via JS -->
                </div>
                <p id="mobilePageInfo" class="text-center text-sm text-gray-500 mt-2"></p>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden sm:block space-y-6 mb-20 sm:mb-0">
                
                <!-- Bakery Section -->
                <div class="bg-white rounded border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 bg-amber-50/50 border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-bread-slice text-amber-500 text-sm"></i>
                            <h3 class="text-sm font-medium text-gray-700">Bakery Products</h3>
                            <span id="bakeryCount" class="ml-auto px-2 py-0.5 text-xs font-medium text-amber-700 bg-amber-100 rounded">0 items</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="bakeryTable" class="min-w-full text-sm text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-medium text-gray-600">Items/Particulars</th>
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
                                    <td colspan="5" class="px-6 py-2 text-right text-xs text-gray-500 font-medium">Total:</td>
                                    <td class="px-6 py-2 text-sm font-medium text-gray-700" id="bakeryTotalQty">0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Drinks Section -->
                <div class="bg-white rounded border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 bg-blue-50/50 border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-mug-hot text-blue-500 text-sm"></i>
                            <h3 class="text-sm font-medium text-gray-700">Drinks</h3>
                            <span id="drinksCount" class="ml-auto px-2 py-0.5 text-xs font-medium text-blue-700 bg-blue-100 rounded">0 items</span>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-1">Drinks do not require stock tracking</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="drinksTable" class="min-w-full text-sm text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-medium text-gray-600">Items/Particulars</th>
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
                                    <td colspan="2" class="px-6 py-2 text-right text-xs text-gray-500 font-medium">Total:</td>
                                    <td class="px-6 py-2 text-sm font-medium text-gray-700" id="drinksTotalQty">0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Grocery Section -->
                <div class="bg-white rounded border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 bg-emerald-50/50 border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-shopping-basket text-emerald-500 text-sm"></i>
                            <h3 class="text-sm font-medium text-gray-700">Grocery Items</h3>
                            <span id="groceryCount" class="ml-auto px-2 py-0.5 text-xs font-medium text-emerald-700 bg-emerald-100 rounded">0 items</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="groceryTable" class="min-w-full text-sm text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-medium text-gray-600">Items/Particulars</th>
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
                                    <td colspan="5" class="px-6 py-2 text-right text-xs text-gray-500 font-medium">Total:</td>
                                    <td class="px-6 py-2 text-sm font-medium text-gray-700" id="groceryTotalQty">0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
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
                            <label for="time_start" class="block mb-1.5 text-sm font-medium text-gray-700">Start Time</label>
                            <input type="time" id="time_start" name="time_start" required
                                class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <p class="text-xs text-gray-400 mt-1">Morning (AM)</p>
                        </div>
                        <div class="mb-6">
                            <label for="time_end" class="block mb-1.5 text-sm font-medium text-gray-700">End Time</label>
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
            <p class="text-sm text-gray-500 mb-5">Product: <span id="editProductName" class="font-medium text-gray-700"></span></p>

            <form id="editInventoryForm">
                <input type="hidden" id="editItemId" name="item_id">

                <div class="mb-4">
                    <label for="editBeginningStock" class="block mb-1.5 text-sm font-medium text-gray-700">Beginning Stock</label>
                    <input type="number" id="editBeginningStock" name="beginning_stock" required min="0"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>

                <div class="mb-6">
                    <label for="editPullOutQuantity" class="block mb-1.5 text-sm font-medium text-gray-700">Pull Out Quantity</label>
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
                    <label for="selectProduct" class="block mb-1.5 text-sm font-medium text-gray-700">Select Product</label>
                    <select id="selectProduct" name="product_id" required
                        class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-all">
                        <option value="">-- Select a product --</option>
                    </select>
                    <p id="noProductsMessage" class="hidden mt-2 text-xs text-gray-500">All products are already in inventory.</p>
                </div>

                <div class="mb-6">
                    <label for="addBeginningStock" class="block mb-1.5 text-sm font-medium text-gray-700">Beginning Stock</label>
                    <input type="number" id="addBeginningStock" name="beginning_stock" min="0" value="0"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <p class="text-xs text-gray-400 mt-1">Optional - defaults to 0</p>
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
        // Track if inventory exists for today
        let inventoryExistsToday = false;

        // Delete Modal Script
        $('#btnDeleteTodaysInventory, #btnDeleteTodaysInventoryMobile').on('click', function () {
            if (!inventoryExistsToday) {
                showToast('warning', 'No inventory exists for today to delete.', 2000);
                return;
            }
            $('#deleteConfirmModal').removeClass('hidden');
        });

        // Close Delete Confirmation Modal
        $('#deleteConfirmModalClose, #deleteConfirmModalCancel').on('click', function () {
            $('#deleteConfirmModal').addClass('hidden');
        });

        // Close modal on backdrop click
        $('#deleteConfirmModalBackdrop').on('click', function () {
            $('#deleteConfirmModal').addClass('hidden');
        });

        // Confirm Delete
        $('#btnConfirmDelete').on('click', function () {
            $('#deleteConfirmModal').addClass('hidden');
            deleteTodaysInventory(); // This calls your function
        });
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

    <script>
        $(document).ready(function () {
            const baseUrl = '<?= base_url() ?>';

            // Display today's date
            const today = new Date();
            const dateString = today.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            $('#todayDate').text(dateString);

            // Open Add Inventory Modal (Desktop & Mobile)
            $('#btnAddTodaysInventory, #btnAddTodaysInventoryMobile').on('click', function () {
                $('#timeInputModal').removeClass('hidden');
                // Set default values: 08:00 AM for start, 17:00 PM for end
                $('#time_start').val('08:00');  // 8:00 AM (morning)
                $('#time_end').val('17:00');    // 5:00 PM (afternoon)
            });

            // Close Inventory Modal
            $('#btnCloseModal, #btnCancelAdd').on('click', function () {
                closeModal();
            });

            // Close modal on outside click
            $('#addMaterialModal').on('click', function (e) {
                if (e.target === this) {
                    closeModal();
                }
            });

            // Add Today's Inventory Button - Open Modal
            $('#btnAddTodaysInventory').on('click', function () {
                $('#timeInputModal').removeClass('hidden');
                // Set default values: 08:00 AM for start, 17:00 PM for end
                $('#time_start').val('08:00');  // 8:00 AM (morning)
                $('#time_end').val('17:00');    // 5:00 PM (afternoon)
            });

            // Close Time Input Modal
            $('#timeInputModalClose, #timeInputModalCancel').on('click', function () {
                $('#timeInputModal').addClass('hidden');
                $('#timeInputForm')[0].reset();
            });

            // Close modal on backdrop click
            $('#timeInputModalBackdrop').on('click', function () {
                $('#timeInputModal').addClass('hidden');
                $('#timeInputForm')[0].reset();
            });

            // Submit Time Input Form
            $('#timeInputForm').on('submit', function (e) {
                e.preventDefault();
                const timeStart = $('#time_start').val();
                const timeEnd = $('#time_end').val();

                // Validate time range
                if (timeStart >= timeEnd) {
                    showToast('warning', 'End time must be after start time');
                    return;
                }

                $('#timeInputModal').addClass('hidden');
                addTodaysInventory(timeStart, timeEnd);
                $('#timeInputForm')[0].reset();
            });

            function closeModal() {
                $('#addMaterialModal').addClass('hidden');
                $('#addMaterialForm')[0].reset();
            }

            // Submit Add Inventory Form via AJAX
            $('#addMaterialForm').on('submit', function (e) {
                e.preventDefault();

                const formData = {
                    // Add your inventory form fields here
                };

                $.ajax({
                    url: baseUrl + 'Inventory/Add',
                    type: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            alert('Inventory added successfully!');
                            closeModal();
                            loadInventory();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('Error adding inventory: ' + error);
                    }
                });
            });

            // Delete Inventory Item
            $(document).on('click', '.btn-delete', function () {
                const id = $(this).data('id');
                Confirm.delete('Are you sure you want to delete this inventory item?', () => {
                    $.ajax({
                        url: baseUrl + 'Inventory/Delete/' + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                showToast('success', 'Inventory item deleted successfully!', 2000);
                                fetchAllStockitems();
                            } else {
                                showToast('error', 'Error: ' + response.message, 3000);
                            }
                        },
                        error: function (xhr, status, error) {
                            showToast('danger', 'Error deleting inventory: ' + (xhr.responseJSON?.message || error), 3000);
                        }
                    });
                });
            });

            // Apply Filter
            $('#apply-filters').on('click', function () {
                const dateFrom = $('#filter-date-from').val();
                const dateTo = $('#filter-date-to').val();

                $('table tbody tr').each(function () {
                    const rowDate = $(this).data('date');
                    let show = true;

                    if (dateFrom && rowDate) {
                        show = show && (rowDate >= dateFrom);
                    }
                    if (dateTo && rowDate) {
                        show = show && (rowDate <= dateTo);
                    }

                    if (show) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Reset Filter
            $('#reset-filters').on('click', function () {
                $('#filter-date-from').val('');
                $('#filter-date-to').val('');
                $('table tbody tr').show();
            });
        });

        // Check first for today's inventory
        $(document).ready(function () {
            checkIfInventoryExists();
        });

        function checkIfInventoryExists() {
            const baseUrl = '<?= base_url() ?>';
            $.ajax({
                url: baseUrl + 'Inventory/CheckInventoryToday',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    // Destroy existing DataTable first
                    if (response.success) {
                        inventoryExistsToday = true;
                        // showToast('info', response.message, 2000);
                        updateDateTime(response.data);
                        fetchAllStockitems();
                    } else {
                        inventoryExistsToday = false;
                        showToast('warning', response.message, 2000);
                        loadInventory([]);
                    }
                },
                error: function (xhr, status, error) {
                    inventoryExistsToday = false;
                    console.log('Error checking inventory: ' + error);
                }
            });
        }

        function updateDateTime(data) {
            // Update date display
            if (data.inventory_date) {
                const date = new Date(data.inventory_date);
                const dateString = date.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                $('#todayDate').text(dateString);
            }

            // Update time range display
            if (data.time_start && data.time_end) {
                // Format time to 12-hour format with AM/PM
                const formatTime = (time) => {
                    const [hours, minutes] = time.split(':');
                    const hour = parseInt(hours);
                    const ampm = hour >= 12 ? 'PM' : 'AM';
                    const displayHour = hour % 12 || 12;
                    return `${displayHour}:${minutes} ${ampm}`;
                };

                const timeStart = formatTime(data.time_start);
                const timeEnd = formatTime(data.time_end);
                $('#timeRange').text(`${timeStart} - ${timeEnd}`);
            }
        }

        // If no inventory, show button for creating inventory
        function addTodaysInventory(time_start, time_end) {
            const baseUrl = '<?= base_url() ?>';
            $.ajax({
                url: baseUrl + 'Inventory/AddTodaysInventory',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({ time_start: time_start, time_end: time_end }),
                success: function (response) {
                    if (response.success) {
                        showToast('success', response.message, 2000);
                        checkIfInventoryExists();
                        fetchAllStockitems();
                        console.log(response.message);
                    } else {
                        showToast('error', response.message, 2000);
                    }
                },
                error: function (xhr, status, error) {
                    showToast('danger', 'Error adding inventory: ' + xhr.responseJSON.message, 2000);
                    console.log(xhr.responseJSON);
                }
            });
        }

        function fetchAllStockitems() {
            const baseURL = '<?= base_url() ?>';
            $.ajax({
                url: `${baseURL}Inventory/FetchAllStockItems`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        // showToast('success', response.message, 2000);
                        loadInventory(response.data);
                        console.log('Inventory data:', response.data);
                    } else {
                        console.log("Error: " + response.error);
                    }
                },
                error: function (xhr, status, error) {
                    showToast('danger', 'Error adding inventory: ' + xhr.responseJSON.message, 2000);
                    console.log(xhr.responseJSON);
                }
            });
        }

        // Mobile pagination variables
        let allInventoryItems = [];
        let filteredItems = [];
        let currentPage = 1;
        const itemsPerPage = 10;

        function loadInventory(items) {
            // Store items for mobile pagination
            allInventoryItems = items || [];
            filteredItems = [...allInventoryItems];
            currentPage = 1;

            // Separate items by category
            const bakeryItems = items ? items.filter(i => i.category === 'bakery') : [];
            const drinksItems = items ? items.filter(i => i.category === 'drinks') : [];
            const groceryItems = items ? items.filter(i => i.category === 'grocery') : [];

            // Render each category table
            renderBakeryTable(bakeryItems);
            renderDrinksTable(drinksItems);
            renderGroceryTable(groceryItems);
            
            // Update totals
            updateGrandTotals(items || []);
            
            // Render mobile cards with pagination
            renderMobileCards();
        }

        function renderBakeryTable(items) {
            let rows = '';
            let totalQty = 0;

            if (items && items.length > 0) {
                items.forEach(function(item) {
                    const price = item.selling_price_per_piece > 0 ? item.selling_price_per_piece : item.selling_price;
                    const formattedPrice = '₱' + parseFloat(price || 0).toFixed(2);
                    const beginning = parseInt(item.beginning_stock) || 0;
                    const pullOut = parseInt(item.pull_out_quantity) || 0;
                    const qtySold = parseInt(item.quantity_sold) || 0;
                    const ending_stock = beginning - pullOut - qtySold;
                    
                    totalQty += qtySold;

                    rows += '<tr class="hover:bg-gray-50 border-b border-gray-100">';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-800">' + (item.product_name || 'N/A') + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + formattedPrice + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + (item.beginning_stock || 0) + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + (item.pull_out_quantity || 0) + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + ending_stock + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + qtySold + '</td>';
                    rows += '<td class="px-6 py-3">';
                    rows += '<button class="text-amber-600 hover:text-amber-800 me-2 btn-edit" data-id="' + item.item_id + '" data-category="bakery" title="Edit"><i class="fas fa-edit"></i></button>';
                    rows += '<button class="text-red-600 hover:text-red-800 btn-delete" data-id="' + item.item_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
                    rows += '</td>';
                    rows += '</tr>';
                });
            } else {
                rows = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No bakery items in inventory</td></tr>';
            }

            $('#bakeryTableBody').html(rows);
            $('#bakeryCount').text(items.length + ' items');
            $('#bakeryTotalQty').text(totalQty);
        }

        function renderDrinksTable(items) {
            let rows = '';
            let totalQty = 0;

            if (items && items.length > 0) {
                items.forEach(function(item) {
                    const formattedPrice = '₱' + parseFloat(item.selling_price || 0).toFixed(2);
                    const qtySold = parseInt(item.quantity_sold) || 0;
                    
                    totalQty += qtySold;

                    rows += '<tr class="hover:bg-gray-50 border-b border-gray-100">';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-800">' + (item.product_name || 'N/A') + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + formattedPrice + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + qtySold + '</td>';
                    rows += '<td class="px-6 py-3">';
                    rows += '<button class="text-red-600 hover:text-red-800 btn-delete" data-id="' + item.item_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
                    rows += '</td>';
                    rows += '</tr>';
                });
            } else {
                rows = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No drinks in inventory</td></tr>';
            }

            $('#drinksTableBody').html(rows);
            $('#drinksCount').text(items.length + ' items');
            $('#drinksTotalQty').text(totalQty);
        }

        function renderGroceryTable(items) {
            let rows = '';
            let totalQty = 0;

            if (items && items.length > 0) {
                items.forEach(function(item) {
                    const formattedPrice = '₱' + parseFloat(item.selling_price || 0).toFixed(2);
                    const beginning = parseInt(item.beginning_stock) || 0;
                    const pullOut = parseInt(item.pull_out_quantity) || 0;
                    const qtySold = parseInt(item.quantity_sold) || 0;
                    const ending_stock = beginning - pullOut - qtySold;
                    
                    totalQty += qtySold;

                    rows += '<tr class="hover:bg-gray-50 border-b border-gray-100">';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-800">' + (item.product_name || 'N/A') + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + formattedPrice + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + beginning + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + pullOut + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + ending_stock + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + qtySold + '</td>';
                    rows += '<td class="px-6 py-3">';
                    rows += '<button class="text-amber-600 hover:text-amber-800 me-2 btn-edit" data-id="' + item.item_id + '" data-category="grocery" title="Edit"><i class="fas fa-edit"></i></button>';
                    rows += '<button class="text-red-600 hover:text-red-800 btn-delete" data-id="' + item.item_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
                    rows += '</td>';
                    rows += '</tr>';
                });
            } else {
                rows = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No grocery items in inventory</td></tr>';
            }

            $('#groceryTableBody').html(rows);
            $('#groceryCount').text(items.length + ' items');
            $('#groceryTotalQty').text(totalQty);
        }

        function updateGrandTotals(items) {
            let grandQty = 0;

            items.forEach(function(item) {
                grandQty += parseInt(item.quantity_sold) || 0;
            });

            $('#grandTotalQty').text(grandQty);
        }

        // Edit Inventory Item - Open Modal
        $(document).on('click', '.btn-edit', function () {
            const itemId = $(this).data('id');
            
            // Always get data from stored items array (more reliable)
            const item = allInventoryItems.find(i => i.item_id == itemId);
            
            if (item) {
                // Store item ID and populate modal
                $('#editItemId').val(itemId);
                $('#editProductName').text(item.product_name || 'N/A');
                $('#editBeginningStock').val(item.beginning_stock || 0);
                $('#editPullOutQuantity').val(item.pull_out_quantity || 0);

                // Show modal
                $('#editInventoryModal').removeClass('hidden');
            } else {
                showToast('error', 'Could not find item data', 2000);
            }
        });

        // Close Edit Modal
        $('#editInventoryModalClose, #editInventoryModalCancel').on('click', function () {
            $('#editInventoryModal').addClass('hidden');
            $('#editInventoryForm')[0].reset();
        });

        // Close modal on backdrop click
        $('#editInventoryModalBackdrop').on('click', function () {
            $('#editInventoryModal').addClass('hidden');
            $('#editInventoryForm')[0].reset();
        });


        $('#editInventoryForm').on('submit', function (e) {
            e.preventDefault();

            const itemId = $('#editItemId').val();
            const beginningStock = $('#editBeginningStock').val();
            const pullOutQuantity = $('#editPullOutQuantity').val();

            // Validate inputs
            if (beginningStock < 0 || pullOutQuantity < 0) {
                showToast('warning', 'Values cannot be negative', 2000);
                return;
            }

            const baseUrl = '<?= base_url() ?>';
            $.ajax({
                url: baseUrl + 'Inventory/UpdateStockItem/' + itemId,
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    beginning_stock: beginningStock,
                    pull_out_quantity: pullOutQuantity
                }),
                success: function (response) {
                    if (response.success) {
                        showToast('success', response.message, 2000);
                        $('#editInventoryModal').addClass('hidden');
                        $('#editInventoryForm')[0].reset();
                        fetchAllStockitems(); // Reload the table
                    } else {
                        showToast('error', response.message, 2000);
                    }
                },
                error: function (xhr, status, error) {
                    showToast('danger', 'Error updating inventory: ' + (xhr.responseJSON?.message || error), 2000);
                    console.log(xhr);
                }
            });
        });

        function deleteTodaysInventory() {
            const baseUrl = '<?= base_url() ?>';
            const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD

            $.ajax({
                url: baseUrl + 'Inventory/DeleteTodaysInventory',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({ date: today }),
                success: function (response) {
                    if (response.success) {
                        inventoryExistsToday = false;
                        showToast('success', response.message, 2000);
                        // Clear the table
                        $('#materialsTableBody').html('<tr><td colspan="8" class="px-6 py-4 text-center text-gray-500">No inventory data available</td></tr>');
                        // Reset date/time display
                        $('#timeRange').text('--:-- - --:--');
                        // Reload the table
                        fetchAllStockitems();
                    } else {
                        showToast('error', response.message, 2000);
                    }
                },
                error: function (xhr, status, error) {
                    showToast('danger', 'Error deleting inventory: ' + (xhr.responseJSON?.message || error), 2000);
                    console.log(xhr);
                }
            });
        }

        // Add Product to Inventory functionality
        $('#btnAddProductToInventory').on('click', function () {
            loadAvailableProducts();
            $('#addProductModal').removeClass('hidden');
        });

        // Close Add Product Modal
        $('#addProductModalClose, #addProductModalCancel').on('click', function () {
            $('#addProductModal').addClass('hidden');
            $('#addProductForm')[0].reset();
        });

        // Close modal on backdrop click
        $('#addProductModalBackdrop').on('click', function () {
            $('#addProductModal').addClass('hidden');
            $('#addProductForm')[0].reset();
        });

        // Load available products (not yet in inventory)
        function loadAvailableProducts() {
            const baseUrl = '<?= base_url() ?>';
            $.ajax({
                url: baseUrl + 'Inventory/GetAvailableProducts',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    const select = $('#selectProduct');
                    select.html('<option value="">-- Select a product --</option>');

                    if (response.success && response.data.length > 0) {
                        response.data.forEach(function (product) {
                            let categoryLabel = 'Unknown';
                            if (product.category === 'bakery') {
                                categoryLabel = 'Bakery';
                            } else if (product.category === 'drinks') {
                                categoryLabel = 'Drinks';
                            } else if (product.category === 'grocery') {
                                categoryLabel = 'Grocery';
                            } else if (product.category === 'dough') {
                                categoryLabel = 'Dough';
                            } else if (product.category) {
                                categoryLabel = product.category.charAt(0).toUpperCase() + product.category.slice(1);
                            }
                            select.append(`<option value="${product.product_id}">[${categoryLabel}] ${product.product_name}</option>`);
                        });
                        $('#noProductsMessage').addClass('hidden');
                        $('#btnSubmitAddProduct').prop('disabled', false);
                    } else {
                        $('#noProductsMessage').removeClass('hidden');
                        $('#btnSubmitAddProduct').prop('disabled', true);
                    }
                },
                error: function (xhr, status, error) {
                    showToast('danger', 'Error loading products: ' + error, 2000);
                }
            });
        }

        // Mobile Search functionality
        $('#mobileSearchInput').on('input', function() {
            const searchTerm = $(this).val().toLowerCase().trim();
            
            if (searchTerm === '') {
                filteredItems = [...allInventoryItems];
            } else {
                filteredItems = allInventoryItems.filter(item => {
                    return (item.product_name && item.product_name.toLowerCase().includes(searchTerm)) ||
                           (item.category && item.category.toLowerCase().includes(searchTerm));
                });
            }
            
            currentPage = 1;
            renderMobileCards();
        });

        // Render mobile cards with pagination - grouped by category
        function renderMobileCards() {
            const bakeryItems = filteredItems.filter(i => i.category === 'bakery');
            const drinksItems = filteredItems.filter(i => i.category === 'drinks');
            const groceryItems = filteredItems.filter(i => i.category === 'grocery');

            let cards = '';

            // Bakery Section
            if (bakeryItems.length > 0) {
                cards += '<div class="mb-4">';
                cards += '<div class="flex items-center gap-2 mb-2 px-1">';
                cards += '<span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Bakery</span>';
                cards += '<span class="text-xs text-gray-400">(' + bakeryItems.length + ')</span>';
                cards += '</div>';
                cards += '<div class="space-y-2">';
                bakeryItems.forEach(function(item) {
                    cards += renderMobileCard(item, 'bakery');
                });
                cards += '</div>';
                cards += '</div>';
            }

            // Drinks Section
            if (drinksItems.length > 0) {
                cards += '<div class="mb-4">';
                cards += '<div class="flex items-center gap-2 mb-2 px-1">';
                cards += '<span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Drinks</span>';
                cards += '<span class="text-xs text-gray-400">(' + drinksItems.length + ')</span>';
                cards += '</div>';
                cards += '<div class="space-y-2">';
                drinksItems.forEach(function(item) {
                    cards += renderMobileCard(item, 'drinks');
                });
                cards += '</div>';
                cards += '</div>';
            }

            // Grocery Section  
            if (groceryItems.length > 0) {
                cards += '<div class="mb-4">';
                cards += '<div class="flex items-center gap-2 mb-2 px-1">';
                cards += '<span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Grocery</span>';
                cards += '<span class="text-xs text-gray-400">(' + groceryItems.length + ')</span>';
                cards += '</div>';
                cards += '<div class="space-y-2">';
                groceryItems.forEach(function(item) {
                    cards += renderMobileCard(item, 'grocery');
                });
                cards += '</div>';
                cards += '</div>';
            }

            if (filteredItems.length === 0) {
                cards = '<div class="bg-white rounded border border-gray-200 p-6 text-center text-gray-500 text-sm">No inventory data</div>';
            }

            $('#mobileCardView').html(cards);
            $('#mobilePagination').html('');
            $('#mobilePageInfo').text('');
        }

        function renderMobileCard(item, category) {
            const price = category === 'bakery' && item.selling_price_per_piece > 0
                ? item.selling_price_per_piece
                : item.selling_price;
            const formattedPrice = '₱' + parseFloat(price || 0).toFixed(2);
            const isDrink = category === 'drinks';
            const ending_stock = isDrink ? null : (item.beginning_stock || 0) - (item.pull_out_quantity || 0) - (item.quantity_sold || 0);
            
            let borderColor = 'border-gray-200';
            if (category === 'bakery') borderColor = 'border-l-2 border-l-amber-400 border-gray-200';
            else if (category === 'drinks') borderColor = 'border-l-2 border-l-blue-400 border-gray-200';
            else if (category === 'grocery') borderColor = 'border-l-2 border-l-emerald-400 border-gray-200';

            let card = '<div class="bg-white rounded border ' + borderColor + ' p-3" data-id="' + item.item_id + '">';
            card += '  <div class="flex items-center justify-between mb-2">';
            card += '    <span class="text-sm text-gray-800">' + (item.product_name || 'N/A') + '</span>';
            card += '    <span class="text-sm font-medium text-gray-700">' + formattedPrice + '</span>';
            card += '  </div>';
            
            if (isDrink) {
                card += '  <div class="flex items-center justify-between text-xs text-gray-500 mb-2">';
                card += '    <span>Qty: <span class="text-gray-700 font-medium">' + (item.quantity_sold || 0) + '</span></span>';
                card += '    <span>Sales: <span class="text-gray-700 font-medium">₱' + (parseFloat(item.total_sales).toFixed(2) || 0) + '</span></span>';
                card += '  </div>';
            } else {
                card += '  <div class="flex items-center gap-3 text-xs text-gray-500 mb-2">';
                card += '    <span>Begin: <span class="text-gray-700">' + (item.beginning_stock || 0) + '</span></span>';
                card += '    <span>Out: <span class="text-gray-700">' + (item.pull_out_quantity || 0) + '</span></span>';
                card += '    <span>End: <span class="text-gray-700">' + ending_stock + '</span></span>';
                card += '    <span class="ml-auto">Sales: <span class="text-gray-700 font-medium">₱' + (parseFloat(item.total_sales).toFixed(2) || 0) + '</span></span>';
                card += '  </div>';
            }
            
            card += '  <div class="flex gap-2 pt-2 border-t border-gray-100">';
            if (!isDrink) {
                card += '    <button class="flex-1 text-xs text-gray-500 hover:text-amber-600 py-1 btn-edit" data-id="' + item.item_id + '">';
                card += '      <i class="fas fa-edit mr-1"></i>Edit';
                card += '    </button>';
            }
            card += '    <button class="flex-1 text-xs text-gray-500 hover:text-red-600 py-1 btn-delete" data-id="' + item.item_id + '">';
            card += '      <i class="fas fa-trash mr-1"></i>Delete';
            card += '    </button>';
            card += '  </div>';
            card += '</div>';

            return card;
        }

        // Render mobile pagination
        function renderMobilePagination(totalPages, totalItems, startIndex, endIndex) {
            let pagination = '';
            
            if (totalPages > 1) {
                // Previous button
                pagination += '<button class="px-3 py-2 text-sm rounded-lg border ' + 
                    (currentPage === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50') + 
                    '" ' + (currentPage === 1 ? 'disabled' : '') + ' data-page="prev">';
                pagination += '<i class="fas fa-chevron-left"></i>';
                pagination += '</button>';

                // Page numbers
                const maxVisiblePages = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
                
                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }

                if (startPage > 1) {
                    pagination += '<button class="px-3 py-2 text-sm rounded-lg border bg-white text-gray-700 hover:bg-gray-50" data-page="1">1</button>';
                    if (startPage > 2) {
                        pagination += '<span class="px-2 py-2 text-gray-400">...</span>';
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    pagination += '<button class="px-3 py-2 text-sm rounded-lg border ' + 
                        (i === currentPage ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50') + 
                        '" data-page="' + i + '">' + i + '</button>';
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        pagination += '<span class="px-2 py-2 text-gray-400">...</span>';
                    }
                    pagination += '<button class="px-3 py-2 text-sm rounded-lg border bg-white text-gray-700 hover:bg-gray-50" data-page="' + totalPages + '">' + totalPages + '</button>';
                }

                // Next button
                pagination += '<button class="px-3 py-2 text-sm rounded-lg border ' + 
                    (currentPage === totalPages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50') + 
                    '" ' + (currentPage === totalPages ? 'disabled' : '') + ' data-page="next">';
                pagination += '<i class="fas fa-chevron-right"></i>';
                pagination += '</button>';
            }

            $('#mobilePagination').html(pagination);
            
            // Page info
            if (totalItems > 0) {
                $('#mobilePageInfo').text('Showing ' + (startIndex + 1) + ' to ' + endIndex + ' of ' + totalItems + ' entries');
            } else {
                $('#mobilePageInfo').text('');
            }
        }

        // Mobile pagination click handler
        $(document).on('click', '#mobilePagination button:not([disabled])', function() {
            const page = $(this).data('page');
            const totalPages = Math.ceil(filteredItems.length / itemsPerPage);
            
            if (page === 'prev') {
                currentPage = Math.max(1, currentPage - 1);
            } else if (page === 'next') {
                currentPage = Math.min(totalPages, currentPage + 1);
            } else {
                currentPage = parseInt(page);
            }
            
            renderMobileCards();
            
            // Scroll to top of cards
            $('html, body').animate({
                scrollTop: $('#mobileCardView').offset().top - 100
            }, 300);
        });

        // Submit Add Product Form
        $('#addProductForm').on('submit', function (e) {
            e.preventDefault();

            const productId = $('#selectProduct').val();
            const beginningStock = $('#addBeginningStock').val() || 0;

            if (!productId) {
                showToast('warning', 'Please select a product', 2000);
                return;
            }

            const baseUrl = '<?= base_url() ?>';
            $.ajax({
                url: baseUrl + 'Inventory/AddProductToInventory',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    product_id: productId,
                    beginning_stock: beginningStock
                }),
                success: function (response) {
                    if (response.success) {
                        showToast('success', response.message, 2000);
                        $('#addProductModal').addClass('hidden');
                        $('#addProductForm')[0].reset();
                        fetchAllStockitems(); // Reload the table
                    } else {
                        showToast('error', response.message, 2000);
                    }
                },
                error: function (xhr, status, error) {
                    showToast('danger', 'Error adding product: ' + (xhr.responseJSON?.message || error), 2000);
                }
            });
        });
    </script>