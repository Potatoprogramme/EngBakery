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
                    <li class="text-gray-700">Distribution</li>
                </ol>
            </nav>

            <!-- Header Section with Date Navigation -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2 mb-4">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Daily Baking Schedule</h2>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="btnAddItems"
                            class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-plus mr-2"></i>Add Items
                        </button>
                    </div>
                </div>

                <!-- Date Navigation -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <button type="button" id="btnPrevDay"
                            class="p-2 rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-600">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div class="flex items-center gap-2">
                            <input type="date" id="selectedDate" value="<?= date('Y-m-d') ?>"
                                class="rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                            <span id="dateLabel" class="text-sm font-medium text-primary hidden sm:inline-block"></span>
                        </div>
                        <button type="button" id="btnNextDay"
                            class="p-2 rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-600">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <!-- Quick Date Buttons -->
                    <div class="flex gap-2 overflow-x-auto pb-1">
                        <button type="button" class="quick-date-btn px-3 py-1.5 text-xs font-medium rounded-full bg-primary text-white" data-days="0">Today</button>
                        <button type="button" class="quick-date-btn px-3 py-1.5 text-xs font-medium rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100" data-days="1">Tomorrow</button>
                        <button type="button" class="quick-date-btn px-3 py-1.5 text-xs font-medium rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100" data-days="2">+2 Days</button>
                        <button type="button" class="quick-date-btn px-3 py-1.5 text-xs font-medium rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100" data-days="3">+3 Days</button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary/10 flex items-center justify-center mr-3">
                            <i class="fas fa-bread-slice text-primary text-lg sm:text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500 truncate">Items</p>
                            <p id="totalItemsCount" class="text-base sm:text-lg font-bold text-gray-900 truncate">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-blue-50 flex items-center justify-center mr-3">
                            <i class="fas fa-boxes text-blue-500 text-lg sm:text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500 truncate">Pieces</p>
                            <p id="totalQuantityCount" class="text-base sm:text-lg font-bold text-gray-900 truncate">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-yellow-50 flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-yellow-500 text-lg sm:text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500 truncate">Pending</p>
                            <p id="pendingCount" class="text-base sm:text-lg font-bold text-gray-900 truncate">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-green-50 flex items-center justify-center mr-3">
                            <i class="fas fa-check-circle text-green-500 text-lg sm:text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500 truncate">Done</p>
                            <p id="completedCount" class="text-base sm:text-lg font-bold text-gray-900 truncate">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating Add Items button for mobile -->
            <div id="mobileAddBtnContainer" class="fixed bottom-6 left-0 right-0 flex justify-center z-30 md:hidden">
                <button type="button" id="btnAddItemsMobile"
                    class="w-5/6 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <i class="fas fa-plus mr-2"></i>Add Items
                </button>
            </div>

            <!-- Inventory Lock Banner -->
            <div id="inventoryLockBanner" class="hidden mb-4 p-4 bg-amber-50 border border-amber-300 rounded-lg shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-lock text-amber-600 text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-amber-800 text-sm">Distribution Locked</h4>
                        <p class="text-sm text-amber-700 mt-1">
                            Inventory has already been created for this date. You cannot add, edit, or delete distribution items.
                            To make changes, delete the inventory for this date first from the
                            <a href="<?= base_url('Inventory') ?>" class="underline font-medium hover:text-amber-900">Inventory page</a>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View - Daily Baking List -->
            <div class="hidden lg:block p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 lg:mb-0">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-clipboard-list text-primary mr-2"></i>
                        Baking List for <span id="tableDate"><?= date('F d, Y') ?></span>
                    </h3>
                </div>
                <table id="distribution-table" class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Quantity
                            </th>
                            <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="distributionTableBody">
                        <!-- Dynamically populated via JS -->
                    </tbody>
                </table>

                <!-- Empty State -->
                <div id="emptyState" class="hidden text-center py-12">
                    <div class="w-20 h-20 rounded-full bg-gray-100 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-1">No items scheduled</h3>
                    <p class="text-sm text-gray-500 mb-4">Add baking items for this day</p>
                    <button type="button" id="btnAddItemsEmpty"
                        class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary">
                        <i class="fas fa-plus mr-2"></i>Add Items
                    </button>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden mb-24">
                <!-- Date Header for Mobile -->
                <div class="bg-primary text-white rounded-lg p-3 mb-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs opacity-80">Baking List</span>
                            <h3 id="mobileDateHeader" class="font-semibold"><?= date('F d, Y') ?></h3>
                        </div>
                        <span class="text-2xl font-bold" id="mobileItemCount">4</span>
                    </div>
                </div>

                <!-- Cards Container -->
                <div id="mobileCardsContainer" class="space-y-2">
                    <!-- Dynamically populated via JS -->
                </div>

                <!-- No results message -->
                <div id="mobileNoResults" class="hidden text-center py-8 text-gray-500">
                    <i class="fas fa-clipboard-list text-4xl mb-2 text-gray-300"></i>
                    <p>No items for this day</p>
                    <button type="button" id="btnAddItemsMobileEmpty"
                        class="mt-3 inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary">
                        <i class="fas fa-plus mr-2"></i>Add Items
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Items Modal - Add Multiple Products -->
    <div id="addItemsModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-2xl mx-auto p-4 sm:p-6 border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 id="addItemsModalTitle" class="text-lg font-semibold text-primary">Add Baking Items</h3>
                    <p class="text-sm text-gray-500">Add products to bake for a specific date</p>
                </div>
                <button type="button" id="btnCloseAddItemsModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="addItemsForm">
                <!-- Date Selection -->
                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <label for="scheduleDate" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-primary mr-1"></i>Schedule Date
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <input type="date" id="scheduleDate" name="schedule_date" value="<?= date('Y-m-d') ?>" required
                            class="flex-1 min-w-[150px] px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        <div class="flex gap-1">
                            <button type="button" class="schedule-quick-btn px-3 py-2 text-xs font-medium rounded-lg bg-primary text-white" data-days="0">Today</button>
                            <button type="button" class="schedule-quick-btn px-3 py-2 text-xs font-medium rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100" data-days="1">Tomorrow</button>
                            <button type="button" class="schedule-quick-btn px-3 py-2 text-xs font-medium rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100" data-days="2">+2 Days</button>
                        </div>
                    </div>
                </div>

                <!-- Items List -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700">
                            <i class="fas fa-list text-primary mr-1"></i>Items to Bake
                        </label>
                        <button type="button" id="btnAddMoreItem"
                            class="text-xs text-primary hover:text-secondary font-medium">
                            <i class="fas fa-plus mr-1"></i>Add More
                        </button>
                    </div>

                    <div id="itemsContainer" class="space-y-2 max-h-[300px] overflow-y-auto pr-1">
                        <!-- Dynamically populated via JS -->
                    </div>
                </div>

                <!-- Summary -->
                <div class="mb-4 p-3 bg-primary/5 rounded-lg border border-primary/20">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Items to add:</span>
                        <span id="itemsSummaryCount" class="text-lg font-bold text-primary">1 item</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelAddItems"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="btnSaveItems"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add to Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Quantity Modal -->
    <div id="editQtyModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-sm mx-auto p-4 sm:p-6 border shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary">Edit Quantity</h3>
                <button type="button" id="btnCloseEditQtyModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editQtyForm">
                <input type="hidden" id="editItemId" name="item_id">

                <div class="text-center mb-4">
                    <div class="w-16 h-16 rounded-full bg-primary/10 mx-auto mb-2 flex items-center justify-center">
                        <i class="fas fa-bread-slice text-primary text-2xl"></i>
                    </div>
                    <h4 id="editProductName" class="font-semibold text-gray-800">Spanish Bread</h4>
                </div>

                <div class="mb-6">
                    <label for="editQuantity" class="block text-sm font-medium text-gray-700 mb-2 text-center">
                        Quantity (pieces)
                    </label>
                    <div class="flex items-center justify-center gap-3">
                        <button type="button" id="btnEditQtyDec"
                            class="w-12 h-12 flex items-center justify-center border border-gray-300 bg-gray-100 text-gray-700 text-xl font-semibold rounded-lg hover:bg-gray-200">
                            -
                        </button>
                        <input type="number" id="editQuantity" name="quantity" min="1" value="10" required
                            class="w-24 px-4 py-3 border border-gray-300 rounded-lg text-center text-xl font-bold focus:ring-2 focus:ring-primary focus:border-primary">
                        <button type="button" id="btnEditQtyInc"
                            class="w-12 h-12 flex items-center justify-center border border-gray-300 bg-gray-100 text-gray-700 text-xl font-semibold rounded-lg hover:bg-gray-200">
                            +
                        </button>
                    </div>
                </div>

                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelEditQty"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary transition-colors">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let productsData = []; // Store fetched products (global scope for template function)
        let inventoryLocked = false; // Track if inventory exists for the selected date

        $(document).ready(function() {

            baseUrl = '<?= base_url() ?>';

            getProducts();
            loadDistributionByDate();

            // ===== API FUNCTIONS =====

            function getProducts() {
                $.ajax({
                    url: baseUrl + 'Distribution/GetProducts',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('Products fetched:', response);
                        if (response.success && response.data) {
                            
                            productsData = response.data;
                            populateProductSelects();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching products:', error);
                    }
                });
            }

            function populateProductSelects() {
                // Update the initial select in the modal
                const selects = $('#itemsContainer select[name="product_id[]"]');
                selects.each(function() {
                    const currentVal = $(this).val();
                    $(this).html(getProductOptionsHtml());
                    if (currentVal) $(this).val(currentVal);
                });
            }

            function getProductOptionsHtml(selectedId = '') {
                let html = '<option value="">Select Product</option>';
                productsData.forEach(function(product) {
                    const selected = (product.product_id == selectedId) ? 'selected' : '';
                    html += `<option value="${product.product_id}" ${selected}>${product.product_name}</option>`;
                });
                return html;
            }

            function loadDistributionByDate() {
                const date = $('#selectedDate').val();
                $.ajax({
                    url: baseUrl + 'Distribution/GetDistributionByDate',
                    method: 'GET',
                    data: { date: date },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data) {
                            inventoryLocked = response.inventory_locked || false;
                            renderDistributionTable(response.data);
                            renderMobileCards(response.data);
                            updateSummaryCounts();
                            updateInventoryLockState();
                        } else {
                            inventoryLocked = false;
                            renderDistributionTable([]);
                            renderMobileCards([]);
                            updateSummaryCounts();
                            // Still check inventory even if no distribution items
                            checkInventoryForDate(date);
                        }
                    },
                    error: function(xhr, status, error) {
                        inventoryLocked = false;
                        // No records found or server error — show empty state
                        renderDistributionTable([]);
                        renderMobileCards([]);
                        updateSummaryCounts();
                        // Still check inventory even if no distribution items
                        checkInventoryForDate(date);
                    }
                });
            }

            function checkInventoryForDate(date) {
                $.ajax({
                    url: baseUrl + 'Distribution/CheckInventoryByDate',
                    method: 'GET',
                    data: { date: date },
                    dataType: 'json',
                    success: function(response) {
                        inventoryLocked = response.inventory_exists || false;
                        updateInventoryLockState();
                    }
                });
            }

            function updateInventoryLockState() {
                if (inventoryLocked) {
                    // Show lock banner
                    $('#inventoryLockBanner').removeClass('hidden');
                    // Hide add buttons
                    $('#btnAddItems').addClass('hidden');
                    $('#mobileAddBtnContainer').addClass('hidden');
                    $('#btnAddItemsEmpty').addClass('hidden');
                    $('#btnAddItemsMobileEmpty').addClass('hidden');
                    // Disable edit/delete buttons in table
                    $('.btn-edit-qty').addClass('opacity-30 cursor-not-allowed').prop('disabled', true);
                    $('.btn-delete').addClass('opacity-30 cursor-not-allowed').prop('disabled', true);
                } else {
                    // Hide lock banner
                    $('#inventoryLockBanner').addClass('hidden');
                    // Show add buttons
                    $('#btnAddItems').removeClass('hidden').addClass('hidden sm:inline-flex');
                    $('#mobileAddBtnContainer').removeClass('hidden');
                    $('#btnAddItemsEmpty').removeClass('hidden');
                    $('#btnAddItemsMobileEmpty').removeClass('hidden');
                    // Enable edit/delete buttons in table
                    $('.btn-edit-qty').removeClass('opacity-30 cursor-not-allowed').prop('disabled', false);
                    $('.btn-delete').removeClass('opacity-30 cursor-not-allowed').prop('disabled', false);
                }
            }

            function addDistriutionItem(productId, quantity, date) {
                $.ajax({
                    url: baseUrl + 'Distribution/AddDistribution',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        product_id: productId,
                        product_qnty: quantity,
                        distribution_date: date
                    }),
                    dataType: 'json',
                    success: function(response) {
                        console.log('Item added:', response);
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 403 && xhr.responseJSON && xhr.responseJSON.inventory_locked) {
                            showToast('warning', 'Distribution is locked because inventory has already been created for this date. Delete the inventory first to make changes.', 4000);
                            inventoryLocked = true;
                            updateInventoryLockState();
                        } else if (xhr.status === 400 && xhr.responseJSON && xhr.responseJSON.insufficient_materials) {
                            showToast('danger', xhr.responseJSON.error, 4000);
                            showInsufficientMaterialsAlert(xhr.responseJSON.insufficient_materials);
                        } else if (xhr.status === 409) {
                            console.warn('Duplicate product for this date.');
                        } else {
                            console.error('Error adding item:', error);
                        }
                    }
                });
            }

            function deleteDistributionItem(itemId) {
                $.ajax({
                    url: baseUrl + 'Distribution/DeleteDistribution/' + itemId,
                    method: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        loadDistributionByDate();
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 403 && xhr.responseJSON && xhr.responseJSON.inventory_locked) {
                            showToast('warning', 'Distribution is locked because inventory has already been created for this date. Delete the inventory first to make changes.', 4000);
                            inventoryLocked = true;
                            updateInventoryLockState();
                        } else {
                            console.error('Error deleting item:', error);
                        }
                    }
                });
            }

            function updateDistributionItem(itemId, quantity) {
                // Get the current item data from the row
                const row = $('tr[data-id="' + itemId + '"]');
                const productId = row.data('product-id');
                const date = $('#selectedDate').val();

                $.ajax({
                    url: baseUrl + 'Distribution/UpdateDistribution/' + itemId,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        product_id: productId,
                        product_qnty: quantity,
                        distribution_date: date
                    }),
                    dataType: 'json',
                    success: function(response) {
                        loadDistributionByDate();
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 403 && xhr.responseJSON && xhr.responseJSON.inventory_locked) {
                            showToast('warning', 'Distribution is locked because inventory has already been created for this date. Delete the inventory first to make changes.', 4000);
                            inventoryLocked = true;
                            updateInventoryLockState();
                        } else if (xhr.status === 400 && xhr.responseJSON && xhr.responseJSON.insufficient_materials) {
                            showToast('danger', xhr.responseJSON.error, 4000);
                            showInsufficientMaterialsAlert(xhr.responseJSON.insufficient_materials);
                        } else {
                            console.error('Error updating item:', error);
                        }
                    }
                });
            }

            // ===== RENDERING FUNCTIONS =====

            function renderDistributionTable(items) {
                const tbody = $('#distributionTableBody');
                tbody.empty();

                if (items.length === 0) {
                    $('#distribution-table').addClass('hidden');
                    $('#emptyState').removeClass('hidden');
                    return;
                }

                $('#distribution-table').removeClass('hidden');
                $('#emptyState').addClass('hidden');

                items.forEach(function(item) {
                    const row = `
                        <tr class="border-b border-gray-100 hover:bg-gray-50" data-id="${item.distribution_id}" data-product-id="${item.product_id}">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <i class="fas fa-bread-slice text-primary"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">${item.product_name}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-lg font-bold text-gray-800">${item.product_qnty}</span>
                                <span class="text-gray-500 text-sm">pcs</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" class="btn-edit-qty p-2 text-primary hover:bg-primary/10 rounded-lg" title="Edit Quantity">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn-delete p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            }

            function renderMobileCards(items) {
                const container = $('#mobileCardsContainer');
                container.empty();

                if (items.length === 0) {
                    $('#mobileNoResults').removeClass('hidden');
                    return;
                }

                $('#mobileNoResults').addClass('hidden');

                items.forEach(function(item) {
                    const card = `
                        <div class="bg-white rounded-lg shadow-sm p-3 border-l-4 border-primary flex items-center justify-between" data-id="${item.distribution_id}" data-product-id="${item.product_id}">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-bread-slice text-primary"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">${item.product_name}</h4>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xl font-bold text-gray-800">${item.product_qnty}</span>
                                <span class="text-xs text-gray-500">pcs</span>
                            </div>
                        </div>
                    `;
                    container.append(card);
                });
            }

            // ===== DATE NAVIGATION =====

            // Date change
            $('#selectedDate').on('change', function() {
                updateDateLabel();
                loadDistributionByDate();
                updateQuickDateBtns();
            });

            // Previous day
            $('#btnPrevDay').on('click', function() {
                const current = new Date($('#selectedDate').val());
                current.setDate(current.getDate() - 1);
                $('#selectedDate').val(formatDate(current)).trigger('change');
            });

            // Next day
            $('#btnNextDay').on('click', function() {
                const current = new Date($('#selectedDate').val());
                current.setDate(current.getDate() + 1);
                $('#selectedDate').val(formatDate(current)).trigger('change');
            });

            // Quick date buttons
            $('.quick-date-btn').on('click', function() {
                const days = parseInt($(this).data('days'));
                const newDate = new Date();
                newDate.setDate(newDate.getDate() + days);
                $('#selectedDate').val(formatDate(newDate)).trigger('change');
            });

            function updateQuickDateBtns() {
                const selectedDate = $('#selectedDate').val();
                $('.quick-date-btn').each(function() {
                    const days = parseInt($(this).data('days'));
                    const btnDate = new Date();
                    btnDate.setDate(btnDate.getDate() + days);

                    if (formatDate(btnDate) === selectedDate) {
                        $(this).removeClass('border border-gray-300 text-gray-600 hover:bg-gray-100').addClass('bg-primary text-white');
                    } else {
                        $(this).removeClass('bg-primary text-white').addClass('border border-gray-300 text-gray-600 hover:bg-gray-100');
                    }
                });
            }

            // Initialize
            updateDateLabel();
            updateSummaryCounts();
            updateQuickDateBtns();

            // ===== ADD ITEMS MODAL =====

            $('#btnAddItems, #btnAddItemsMobile, #btnAddItemsEmpty, #btnAddItemsMobileEmpty').on('click', function() {
                if (inventoryLocked) {
                    showToast('warning', 'Distribution is locked because inventory has already been created for this date. Delete the inventory first to make changes.', 4000);
                    return;
                }
                $('#scheduleDate').val($('#selectedDate').val());
                updateScheduleQuickBtns();
                // Reset items container with fresh row using dynamic products
                $('#itemsContainer').html(getItemRowTemplate());
                updateItemsSummary();
                $('#addItemsModal').removeClass('hidden');
            });

            $('#btnCloseAddItemsModal, #btnCancelAddItems').on('click', function() {
                $('#addItemsModal').addClass('hidden');
            });

            // ===== EDIT QTY MODAL =====

            $('#btnCloseEditQtyModal, #btnCancelEditQty').on('click', function() {
                $('#editQtyModal').addClass('hidden');
            });

            // Schedule quick date buttons in modal
            $('.schedule-quick-btn').on('click', function() {
                const days = parseInt($(this).data('days'));
                const newDate = new Date();
                newDate.setDate(newDate.getDate() + days);
                $('#scheduleDate').val(formatDate(newDate));
                updateScheduleQuickBtns();
            });

            // Add more item row
            $('#btnAddMoreItem').on('click', function() {
                addItemRow();
                updateItemsSummary();
            });

            // Remove item row in modal
            $(document).on('click', '.btn-remove-item', function() {
                const container = $('#itemsContainer');
                if (container.find('.item-row').length > 1) {
                    $(this).closest('.item-row').remove();
                    updateItemsSummary();
                }
            });

            // Quantity increment/decrement in add items modal
            $(document).on('click', '.btn-qty-inc', function() {
                const input = $(this).siblings('input[name="quantity[]"]');
                input.val(parseInt(input.val() || 0) + 5);
            });

            $(document).on('click', '.btn-qty-dec', function() {
                const input = $(this).siblings('input[name="quantity[]"]');
                const val = parseInt(input.val() || 0);
                if (val > 5) input.val(val - 5);
            });

            // Edit quantity modal controls
            $('#btnEditQtyInc').on('click', function() {
                const input = $('#editQuantity');
                input.val(parseInt(input.val() || 0) + 5);
            });

            $('#btnEditQtyDec').on('click', function() {
                const input = $('#editQuantity');
                const val = parseInt(input.val() || 0);
                if (val > 5) input.val(val - 5);
            });

            // Edit quantity button click (desktop table)
            $(document).on('click', '.btn-edit-qty', function() {
                if (inventoryLocked) {
                    showToast('warning', 'Distribution is locked because inventory has already been created for this date. Delete the inventory first to make changes.', 4000);
                    return;
                }
                const row = $(this).closest('tr');
                const productName = row.find('span.font-medium').text();
                const qty = row.find('.text-lg.font-bold').text();

                $('#editProductName').text(productName);
                $('#editQuantity').val(parseInt(qty));
                $('#editItemId').val(row.data('id'));
                $('#editQtyModal').removeClass('hidden');
            });

            // Delete item — calls backend then reloads
            $(document).on('click', '.btn-delete', function() {
                if (inventoryLocked) {
                    showToast('warning', 'Distribution is locked because inventory has already been created for this date. Delete the inventory first to make changes.', 4000);
                    return;
                }
                const row = $(this).closest('tr');
                const itemId = row.data('id');

                Confirm.delete('Are you sure you want to remove this item?', function() {
                    deleteDistributionItem(itemId);
                });
            });

            // ===== FORM SUBMISSIONS =====

            // Add items form — sends each item to backend
            $('#addItemsForm').on('submit', function(e) {
                e.preventDefault();

                const scheduleDate = $('#scheduleDate').val();
                const rows = $('#itemsContainer .item-row');
                let itemsToAdd = [];

                rows.each(function() {
                    const productId = $(this).find('select[name="product_id[]"]').val();
                    const quantity = $(this).find('input[name="quantity[]"]').val();
                    if (productId && quantity) {
                        itemsToAdd.push({ product_id: productId, quantity: quantity });
                    }
                });

                if (itemsToAdd.length === 0) {
                    showToast('warning', 'Please add at least one item with a selected product.', 3000);
                    return;
                }

                // Disable submit button
                $('#btnSaveItems').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Saving...');

                let completed = 0;
                let hasError = false;
                let duplicateProducts = [];
                let insufficientProducts = [];

                itemsToAdd.forEach(function(item) {
                    $.ajax({
                        url: baseUrl + 'Distribution/AddDistribution',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            product_id: item.product_id,
                            product_qnty: item.quantity,
                            distribution_date: scheduleDate
                        }),
                        dataType: 'json',
                        success: function(response) {
                            completed++;
                            if (completed === itemsToAdd.length) {
                                onAllItemsAdded(hasError, duplicateProducts, insufficientProducts);
                            }
                        },
                        error: function(xhr, status, error) {
                            hasError = true;
                            completed++;
                            // Check if it's a duplicate error (409)
                            if (xhr.status === 409) {
                                const productName = productsData.find(p => p.product_id == item.product_id);
                                duplicateProducts.push(productName ? productName.product_name : 'Unknown product');
                            }
                            // Check if it's an insufficient materials error (400)
                            if (xhr.status === 400 && xhr.responseJSON && xhr.responseJSON.insufficient_materials) {
                                const productName = productsData.find(p => p.product_id == item.product_id);
                                insufficientProducts.push({
                                    name: productName ? productName.product_name : 'Unknown product',
                                    materials: xhr.responseJSON.insufficient_materials
                                });
                            }
                            console.error('Error adding item:', error);
                            if (completed === itemsToAdd.length) {
                                onAllItemsAdded(hasError, duplicateProducts, insufficientProducts);
                            }
                        }
                    });
                });

                function onAllItemsAdded(hadError, duplicates, insufficients) {
                    $('#btnSaveItems').prop('disabled', false).html('<i class="fas fa-plus mr-2"></i>Add to Schedule');
                    $('#addItemsModal').addClass('hidden');

                    // Switch view to the scheduled date and reload
                    $('#selectedDate').val(scheduleDate).trigger('change');

                    // Reset form
                    $('#itemsContainer').html(getItemRowTemplate());
                    updateItemsSummary();

                    if (insufficients && insufficients.length > 0) {
                        var allShort = [];
                        insufficients.forEach(function(p) {
                            p.materials.forEach(function(m) {
                                allShort.push(p.name + ': ' + m);
                            });
                        });
                        showInsufficientMaterialsAlert(allShort);
                    }

                    if (duplicates && duplicates.length > 0) {
                        showToast('warning', 'The following products are already scheduled for this date and were skipped: ' + duplicates.join(', '), 5000);
                    } else if (hadError && (!insufficients || insufficients.length === 0)) {
                        showToast('danger', 'Some items could not be added. Please check and try again.', 3000);
                    }
                }
            });

            // Edit quantity form — calls updateDistributionItem
            $('#editQtyForm').on('submit', function(e) {
                e.preventDefault();
                const itemId = $('#editItemId').val();
                const quantity = $('#editQuantity').val();

                updateDistributionItem(itemId, quantity);
                $('#editQtyModal').addClass('hidden');
            });

            // Update summary when products change in modal
            $(document).on('change', '#itemsContainer select, #itemsContainer input', function() {
                updateItemsSummary();
            });
        });

        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }

        function updateDateLabel() {
            const dateStr = $('#selectedDate').val();
            const date = new Date(dateStr);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const formatted = date.toLocaleDateString('en-US', options);

            let label = '';
            const diffDays = Math.floor((date - today) / (1000 * 60 * 60 * 24));

            if (diffDays === 0) label = '(Today)';
            else if (diffDays === 1) label = '(Tomorrow)';
            else if (diffDays === -1) label = '(Yesterday)';
            else if (diffDays > 1) label = `(+${diffDays} days)`;

            $('#dateLabel').text(label);
            $('#tableDate').text(formatted);
            $('#mobileDateHeader').text(date.toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            }));
        }

        function updateScheduleQuickBtns() {
            const selectedDate = $('#scheduleDate').val();

            $('.schedule-quick-btn').each(function() {
                const days = parseInt($(this).data('days'));
                const btnDate = new Date();
                btnDate.setDate(btnDate.getDate() + days);

                if (formatDate(btnDate) === selectedDate) {
                    $(this).removeClass('border border-gray-300 text-gray-600').addClass('bg-primary text-white');
                } else {
                    $(this).removeClass('bg-primary text-white').addClass('border border-gray-300 text-gray-600');
                }
            });
        }

        function addItemRow(productId = '', qty = 10) {
            const template = getItemRowTemplate(productId, qty);
            $('#itemsContainer').append(template);
        }

        function getItemRowTemplate(productId = '', qty = 10) {
            let optionsHtml = '<option value="">Select Product</option>';
            if (typeof productsData !== 'undefined' && productsData.length > 0) {
                productsData.forEach(function(product) {
                    const selected = (product.product_id == productId) ? 'selected' : '';
                    optionsHtml += `<option value="${product.product_id}" ${selected}>${product.product_name}</option>`;
                });
            }
            return `
                <div class="item-row flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                    <select name="product_id[]" required
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        ${optionsHtml}
                    </select>
                    <div class="flex items-center gap-1">
                        <button type="button" class="btn-qty-dec w-8 h-8 flex items-center justify-center border border-gray-300 bg-white text-gray-600 rounded-lg hover:bg-gray-100">-</button>
                        <input type="number" name="quantity[]" min="1" value="${qty}" required
                            class="w-16 px-2 py-2 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        <button type="button" class="btn-qty-inc w-8 h-8 flex items-center justify-center border border-gray-300 bg-white text-gray-600 rounded-lg hover:bg-gray-100">+</button>
                    </div>
                    <button type="button" class="btn-remove-item p-2 text-red-500 hover:bg-red-50 rounded-lg" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        }

        function updateItemsSummary() {
            const count = $('#itemsContainer .item-row').length;
            $('#itemsSummaryCount').text(count + (count === 1 ? ' item' : ' items'));
        }

        function updateSummaryCounts() {
            const total = $('tr[data-id]').length;

            let totalQty = 0;
            $('tr[data-id] .text-lg.font-bold').each(function() {
                totalQty += parseInt($(this).text()) || 0;
            });

            $('#totalItemsCount').text(total);
            $('#totalQuantityCount').text(totalQty);
            $('#pendingCount').text(total);
            $('#completedCount').text(0);
            $('#mobileItemCount').text(total);
        }

        /**
         * Show a blocking alert with details of insufficient raw materials.
         * @param {Array} materials - Array of strings describing each shortage
         */
        function showInsufficientMaterialsAlert(materials) {
            let html = '';
            html += '<div class="flex items-center gap-2 mb-3 p-3 bg-red-50 rounded-lg">';
            html += '<i class="fas fa-ban text-red-500 text-xl"></i>';
            html += '<span class="font-semibold text-red-700">Cannot add — insufficient raw material stock</span>';
            html += '</div>';
            html += '<p class="text-sm text-gray-600 mb-2">The following raw materials are short:</p>';
            html += '<ul class="list-disc list-inside text-sm text-gray-700 bg-red-50 rounded-lg p-3 space-y-1">';
            materials.forEach(function(detail) {
                html += '<li class="text-red-700">' + detail + '</li>';
            });
            html += '</ul>';
            html += '<div class="mt-3 p-3 bg-amber-50 rounded-lg text-sm text-amber-800">';
            html += '<i class="fas fa-lightbulb mr-1"></i> Please restock raw materials in <strong>Stock Initial</strong> before proceeding.';
            html += '</div>';

            $('#insufficientMaterialContent').html(html);
            $('#insufficientMaterialModal').removeClass('hidden');
        }
    </script>

<!-- Insufficient Materials Modal -->
<div id="insufficientMaterialModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[80vh] overflow-hidden flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>Insufficient Raw Materials
            </h3>
            <button onclick="$('#insufficientMaterialModal').addClass('hidden')"
                class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="px-6 py-4 overflow-y-auto" id="insufficientMaterialContent">
        </div>
        <div class="px-6 py-3 border-t border-gray-200 flex justify-end">
            <button onclick="$('#insufficientMaterialModal').addClass('hidden')"
                class="px-5 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors text-sm font-medium">
                Got it
            </button>
        </div>
    </div>
</div>
</body>