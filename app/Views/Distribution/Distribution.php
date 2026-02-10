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

            <!-- Header Section -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Daily Baking Schedule</h2>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="btnAddItems"
                            class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-plus mr-2"></i>Add Items
                        </button>
                    </div>
                </div>
            </div>

            <!-- Floating Add Items button for mobile -->
            <div id="mobileAddBtnContainer" class="fixed bottom-6 left-0 right-0 flex justify-center z-30 lg:hidden">
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

            <!-- Main Layout: List + Calendar -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mb-4 lg:mb-0">
                
                <!-- Left Side: Baking List (hidden on mobile, shown on lg+) -->
                <div class="hidden lg:block lg:col-span-5 xl:col-span-4">
                    <!-- Date Navigation for Selected Date -->
                    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Selected Date</h3>
                            <input type="date" id="selectedDate" value="<?= date('Y-m-d') ?>"
                                class="rounded-md border border-gray-200 px-3 py-1.5 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="button" id="btnPrevDay"
                                class="p-2 rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-600">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <div class="text-center">
                                <p id="tableDate" class="text-lg font-bold text-gray-800"><?= date('F d, Y') ?></p>
                                <span id="dateLabel" class="text-xs font-medium text-primary"></span>
                            </div>
                            <button type="button" id="btnNextDay"
                                class="p-2 rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-600">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center mr-2">
                                    <i class="fas fa-bread-slice text-primary text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Items</p>
                                    <p id="totalItemsCount" class="text-sm font-bold text-gray-900">0</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center mr-2">
                                    <i class="fas fa-boxes text-blue-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Pieces</p>
                                    <p id="totalQuantityCount" class="text-sm font-bold text-gray-900">0</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Baking List Panel -->
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                <i class="fas fa-clipboard-list text-primary mr-1"></i>Baking List
                            </h3>
                            <button type="button" id="btnAddItemsEmpty"
                                class="text-xs text-primary hover:text-secondary font-medium">
                                <i class="fas fa-plus mr-1"></i>Add
                            </button>
                        </div>

                        <!-- List Items -->
                        <div id="distributionListContainer" class="space-y-2 max-h-[400px] overflow-y-auto">
                            <!-- Dynamically populated via JS -->
                        </div>

                        <!-- Empty State -->
                        <div id="emptyState" class="hidden text-center py-8">
                            <div class="w-16 h-16 rounded-full bg-gray-100 mx-auto mb-3 flex items-center justify-center">
                                <i class="fas fa-clipboard-list text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-sm font-medium text-gray-800 mb-1">No items scheduled</h3>
                            <p class="text-xs text-gray-500">Click "Add" to add baking items</p>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Calendar -->
                <div class="lg:col-span-7 xl:col-span-8">
                    <div class="bg-white rounded-lg shadow-md p-2 sm:p-4">
                        <!-- Calendar Header -->
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <button type="button" id="btnPrevMonth"
                                class="p-1.5 sm:p-2 rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-600">
                                <i class="fas fa-chevron-left text-xs sm:text-sm"></i>
                            </button>
                            <h3 id="calendarMonth" class="text-sm sm:text-lg font-bold text-gray-800"><?= date('F Y') ?></h3>
                            <button type="button" id="btnNextMonth"
                                class="p-1.5 sm:p-2 rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-600">
                                <i class="fas fa-chevron-right text-xs sm:text-sm"></i>
                            </button>
                        </div>

                        <!-- Calendar Grid -->
                        <div class="grid grid-cols-7 gap-0.5 sm:gap-1 mb-2">
                            <div class="text-center text-[10px] sm:text-xs font-semibold text-gray-500 py-1 sm:py-2">Sun</div>
                            <div class="text-center text-[10px] sm:text-xs font-semibold text-gray-500 py-1 sm:py-2">Mon</div>
                            <div class="text-center text-[10px] sm:text-xs font-semibold text-gray-500 py-1 sm:py-2">Tue</div>
                            <div class="text-center text-[10px] sm:text-xs font-semibold text-gray-500 py-1 sm:py-2">Wed</div>
                            <div class="text-center text-[10px] sm:text-xs font-semibold text-gray-500 py-1 sm:py-2">Thu</div>
                            <div class="text-center text-[10px] sm:text-xs font-semibold text-gray-500 py-1 sm:py-2">Fri</div>
                            <div class="text-center text-[10px] sm:text-xs font-semibold text-gray-500 py-1 sm:py-2">Sat</div>
                        </div>
                        <div id="calendarDays" class="grid grid-cols-7 gap-0.5 sm:gap-1">
                            <!-- Dynamically populated via JS -->
                        </div>

                        <!-- Legend -->
                        <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-4 mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-1">
                                <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-primary"></div>
                                <span class="text-[10px] sm:text-xs text-gray-500">Selected</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-primary/40"></div>
                                <span class="text-[10px] sm:text-xs text-gray-500">Has items</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-gray-200"></div>
                                <span class="text-[10px] sm:text-xs text-gray-500">No items</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full border-2 border-primary"></div>
                                <span class="text-[10px] sm:text-xs text-gray-500">Today</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Card View (shown below on mobile) -->
            <div class="lg:hidden mb-24" id="mobileCardSection">
                <!-- Date Header for Mobile -->
                <div class="bg-primary text-white rounded-lg p-3 mb-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs opacity-80">Baking List</span>
                            <h3 id="mobileDateHeader" class="font-semibold"><?= date('F d, Y') ?></h3>
                        </div>
                        <span class="text-2xl font-bold" id="mobileItemCount">0</span>
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

    <!-- Calendar Day Modal -->
    <div id="calendarDayModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-6 border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 id="calendarDayModalTitle" class="text-lg font-semibold text-primary">Baking List</h3>
                    <p id="calendarDayModalDate" class="text-sm text-gray-500">January 15, 2026</p>
                </div>
                <button type="button" id="btnCloseCalendarDayModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Summary -->
            <div class="flex gap-3 mb-4">
                <div class="flex-1 bg-primary/10 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold text-primary" id="modalItemCount">0</p>
                    <p class="text-xs text-gray-600">Items</p>
                </div>
                <div class="flex-1 bg-blue-50 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold text-blue-600" id="modalPiecesCount">0</p>
                    <p class="text-xs text-gray-600">Pieces</p>
                </div>
            </div>

            <!-- Items List -->
            <div id="calendarDayItemsList" class="space-y-2 max-h-[300px] overflow-y-auto mb-4">
                <!-- Dynamically populated -->
            </div>

            <!-- Empty State -->
            <div id="calendarDayEmptyState" class="hidden text-center py-6">
                <div class="w-14 h-14 rounded-full bg-gray-100 mx-auto mb-2 flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-gray-400 text-xl"></i>
                </div>
                <p class="text-sm text-gray-500">No items scheduled</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 justify-end pt-3 border-t border-gray-100">
                <button type="button" id="btnCalendarDayClose"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Close
                </button>
                <button type="button" id="btnCalendarDaySelect"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary">
                    <i class="fas fa-arrow-right mr-1"></i>Go to this date
                </button>
            </div>
        </div>
    </div>

    <!-- Add Items Modal - Search & Add Pattern -->
    <div id="addItemsModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-lg mx-auto p-4 sm:p-6 border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 id="addItemsModalTitle" class="text-lg font-semibold text-primary">Add Baking Items</h3>
                    <p class="text-sm text-gray-500">Search and add products for a specific date</p>
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

                <!-- Product Search & Add Section -->
                <div class="mb-4 p-3 border border-gray-200 rounded-lg bg-gray-50">
                    <h4 class="text-center text-sm font-medium text-gray-700 mb-3">Select Product & Quantity</h4>

                    <!-- Product Search -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" id="productSearch"
                                class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="Search product..." autocomplete="off">
                            <button type="button" id="btnClearProduct"
                                class="hidden absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                            <div id="productDropdown"
                                class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto">
                            </div>
                            <input type="hidden" id="selectedProductId">
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity (pieces) <span class="text-red-500">*</span></label>
                        <input type="number" id="addProductQty" min="1" value="10" step="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="10">
                    </div>

                    <!-- Add Product Button -->
                    <button type="button" id="btnAddProductToList"
                        class="w-full px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">
                        <i class="fas fa-plus mr-1"></i>Add Product
                    </button>
                </div>

                <!-- Added Products List -->
                <div class="mb-4 p-3 border border-gray-200 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-semibold text-gray-700">Added Products</h4>
                        <span id="itemsSummaryCount" class="text-xs font-medium text-primary bg-primary/10 px-2 py-0.5 rounded-full">0 items</span>
                    </div>
                    <div id="itemsContainer" class="space-y-2 max-h-[200px] overflow-y-auto">
                        <p id="noItemsMsg" class="text-sm text-gray-500 text-center py-2">No products added yet</p>
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
                        <i class="fas fa-save mr-2"></i>Save to Schedule
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
        let calendarData = {}; // Store distribution data keyed by date
        let currentCalendarMonth = new Date().getMonth();
        let currentCalendarYear = new Date().getFullYear();

        $(document).ready(function() {

            baseUrl = '<?= base_url() ?>';

            getProducts();
            loadDistributionByDate();
            renderCalendar();
            loadMonthDistributions();

            // ===== API FUNCTIONS =====

            function getProducts() {
                $.ajax({
                    url: baseUrl + 'Distribution/GetProducts',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data) {
                            productsData = response.data;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching products:', error);
                    }
                });
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
                            renderDistributionList(response.data);
                            renderMobileCards(response.data);
                            updateSummaryCounts(response.data);
                            updateInventoryLockState();
                        } else {
                            inventoryLocked = false;
                            renderDistributionList([]);
                            renderMobileCards([]);
                            updateSummaryCounts([]);
                            checkInventoryForDate(date);
                        }
                    },
                    error: function(xhr, status, error) {
                        inventoryLocked = false;
                        renderDistributionList([]);
                        renderMobileCards([]);
                        updateSummaryCounts([]);
                        checkInventoryForDate(date);
                    }
                });
            }

            function loadMonthDistributions() {
                const startDate = new Date(currentCalendarYear, currentCalendarMonth, 1);
                const endDate = new Date(currentCalendarYear, currentCalendarMonth + 1, 0);
                
                $.ajax({
                    url: baseUrl + 'Distribution/GetDistributionByDateRange',
                    method: 'GET',
                    data: { 
                        start_date: formatDate(startDate),
                        end_date: formatDate(endDate)
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data) {
                            // Group by date
                            calendarData = {};
                            response.data.forEach(function(item) {
                                if (!calendarData[item.distribution_date]) {
                                    calendarData[item.distribution_date] = [];
                                }
                                calendarData[item.distribution_date].push(item);
                            });
                            renderCalendar();
                        }
                    },
                    error: function() {
                        calendarData = {};
                        renderCalendar();
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
                    $('#inventoryLockBanner').removeClass('hidden');
                    $('#btnAddItems').addClass('hidden');
                    $('#mobileAddBtnContainer').addClass('hidden');
                    $('#btnAddItemsEmpty').addClass('hidden');
                    $('#btnAddItemsMobileEmpty').addClass('hidden');
                    $('.btn-edit-qty').addClass('opacity-30 cursor-not-allowed').prop('disabled', true);
                    $('.btn-delete').addClass('opacity-30 cursor-not-allowed').prop('disabled', true);
                } else {
                    $('#inventoryLockBanner').addClass('hidden');
                    $('#btnAddItems').removeClass('hidden').addClass('hidden sm:inline-flex');
                    $('#mobileAddBtnContainer').removeClass('hidden');
                    $('#btnAddItemsEmpty').removeClass('hidden');
                    $('#btnAddItemsMobileEmpty').removeClass('hidden');
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
                        showToast('success', 'Item removed successfully!', 3000);
                        loadDistributionByDate();
                        loadMonthDistributions();
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 403 && xhr.responseJSON && xhr.responseJSON.inventory_locked) {
                            showToast('warning', 'Distribution is locked because inventory has already been created for this date. Delete the inventory first to make changes.', 4000);
                            inventoryLocked = true;
                            updateInventoryLockState();
                        } else {
                            showToast('danger', 'Failed to delete item. Please try again.', 3000);
                            console.error('Error deleting item:', error);
                        }
                    }
                });
            }

            function updateDistributionItem(itemId, quantity) {
                const row = $('[data-id="' + itemId + '"]');
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
                        showToast('success', 'Quantity updated successfully!', 3000);
                        loadDistributionByDate();
                        loadMonthDistributions();
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
                            showToast('danger', 'Failed to update quantity. Please try again.', 3000);
                            console.error('Error updating item:', error);
                        }
                    }
                });
            }

            // ===== CALENDAR FUNCTIONS =====

            function renderCalendar() {
                const container = $('#calendarDays');
                container.empty();

                const firstDay = new Date(currentCalendarYear, currentCalendarMonth, 1);
                const lastDay = new Date(currentCalendarYear, currentCalendarMonth + 1, 0);
                const startingDay = firstDay.getDay();
                const totalDays = lastDay.getDate();

                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const selectedDate = $('#selectedDate').val();

                // Update month label
                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                                   'July', 'August', 'September', 'October', 'November', 'December'];
                $('#calendarMonth').text(monthNames[currentCalendarMonth] + ' ' + currentCalendarYear);

                // Empty slots for days before month starts
                for (let i = 0; i < startingDay; i++) {
                    container.append('<div class="h-16 sm:h-20"></div>');
                }

                // Render each day
                for (let day = 1; day <= totalDays; day++) {
                    const dateStr = formatDate(new Date(currentCalendarYear, currentCalendarMonth, day));
                    const isToday = (new Date(currentCalendarYear, currentCalendarMonth, day).getTime() === today.getTime());
                    const isSelected = (dateStr === selectedDate);
                    const dayData = calendarData[dateStr] || [];
                    const hasItems = dayData.length > 0;

                    let todayClass = isToday ? 'ring-2 ring-primary' : '';
                    let selectedClass = isSelected ? 'bg-primary text-white' : '';
                    let bgClass = isSelected ? '' : (hasItems ? 'bg-primary/40' : 'bg-gray-50');

                    let itemsPreview = '';
                    if (hasItems) {
                        const totalQty = dayData.reduce((sum, item) => sum + parseInt(item.product_qnty || 0), 0);
                        itemsPreview = `
                            <div class="mt-0.5 sm:mt-1 leading-tight">
                                <span class="text-[8px] sm:text-[10px] md:text-xs ${isSelected ? 'text-white/80' : 'text-primary'} font-medium block sm:inline">${dayData.length}</span>
                                <span class="hidden sm:inline text-[10px] md:text-xs ${isSelected ? 'text-white/60' : 'text-gray-500'}">(${totalQty})</span>
                            </div>
                        `;
                    }

                    const dayHtml = `
                        <div class="calendar-day h-12 sm:h-16 md:h-20 p-0.5 sm:p-1 md:p-2 rounded-md sm:rounded-lg cursor-pointer hover:shadow-md transition-all ${bgClass} ${todayClass} ${selectedClass} border border-gray-100"
                             data-date="${dateStr}">
                            <div class="text-[10px] sm:text-xs md:text-sm font-semibold ${isSelected ? 'text-white' : 'text-gray-700'}">${day}</div>
                            ${itemsPreview}
                        </div>
                    `;
                    container.append(dayHtml);
                }
            }

            // Calendar navigation
            $('#btnPrevMonth').on('click', function() {
                currentCalendarMonth--;
                if (currentCalendarMonth < 0) {
                    currentCalendarMonth = 11;
                    currentCalendarYear--;
                }
                loadMonthDistributions();
            });

            $('#btnNextMonth').on('click', function() {
                currentCalendarMonth++;
                if (currentCalendarMonth > 11) {
                    currentCalendarMonth = 0;
                    currentCalendarYear++;
                }
                loadMonthDistributions();
            });

            // Calendar day click - show modal
            $(document).on('click', '.calendar-day', function() {
                const dateStr = $(this).data('date');
                const dayData = calendarData[dateStr] || [];
                
                showCalendarDayModal(dateStr, dayData);
            });

            function showCalendarDayModal(dateStr, items) {
                const date = new Date(dateStr);
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const formatted = date.toLocaleDateString('en-US', options);

                $('#calendarDayModalDate').text(formatted);
                $('#calendarDayModal').data('selected-date', dateStr);

                const totalQty = items.reduce((sum, item) => sum + parseInt(item.product_qnty || 0), 0);
                $('#modalItemCount').text(items.length);
                $('#modalPiecesCount').text(totalQty);

                const listContainer = $('#calendarDayItemsList');
                listContainer.empty();

                if (items.length === 0) {
                    $('#calendarDayItemsList').addClass('hidden');
                    $('#calendarDayEmptyState').removeClass('hidden');
                } else {
                    $('#calendarDayItemsList').removeClass('hidden');
                    $('#calendarDayEmptyState').addClass('hidden');

                    items.forEach(function(item) {
                        const row = `
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-md bg-primary/10 flex items-center justify-center">
                                        <i class="fas fa-bread-slice text-primary text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-800">${item.product_name}</span>
                                </div>
                                <span class="text-sm font-bold text-gray-800">${item.product_qnty} <span class="text-xs text-gray-500 font-normal">pcs</span></span>
                            </div>
                        `;
                        listContainer.append(row);
                    });
                }

                $('#calendarDayModal').removeClass('hidden');
            }

            // Close calendar day modal
            $('#btnCloseCalendarDayModal, #btnCalendarDayClose').on('click', function() {
                $('#calendarDayModal').addClass('hidden');
            });

            // Go to selected date from modal
            $('#btnCalendarDaySelect').on('click', function() {
                const dateStr = $('#calendarDayModal').data('selected-date');
                $('#selectedDate').val(dateStr).trigger('change');
                $('#calendarDayModal').addClass('hidden');
            });

            // ===== RENDERING FUNCTIONS =====

            function renderDistributionList(items) {
                const container = $('#distributionListContainer');
                container.empty();

                if (items.length === 0) {
                    container.addClass('hidden');
                    $('#emptyState').removeClass('hidden');
                    return;
                }

                container.removeClass('hidden');
                $('#emptyState').addClass('hidden');

                items.forEach(function(item) {
                    const row = `
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors" data-id="${item.distribution_id}" data-product-id="${item.product_id}">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <div class="w-8 h-8 rounded-md bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-bread-slice text-primary text-xs"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-800 truncate">${item.product_name}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-800">${item.product_qnty}</span>
                                <button type="button" class="btn-edit-qty p-1.5 text-primary hover:bg-primary/10 rounded" title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button type="button" class="btn-delete p-1.5 text-red-500 hover:bg-red-50 rounded" title="Remove">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    container.append(row);
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
                        <div class="bg-white rounded-lg shadow-sm p-3 border-l-4 border-primary" data-id="${item.distribution_id}" data-product-id="${item.product_id}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-bread-slice text-primary"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">${item.product_name}</h4>
                                        <span class="text-xs text-gray-500">${item.product_qnty} pcs</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="btn-edit-qty-mobile p-2 text-primary hover:bg-primary/10 rounded-lg" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>
                                    <button type="button" class="btn-delete-mobile p-2 text-red-500 hover:bg-red-50 rounded-lg" title="Remove">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    container.append(card);
                });
            }

            // ===== DATE NAVIGATION =====

            $('#selectedDate').on('change', function() {
                updateDateLabel();
                loadDistributionByDate();
                renderCalendar();
            });

            $('#btnPrevDay').on('click', function() {
                const current = new Date($('#selectedDate').val());
                current.setDate(current.getDate() - 1);
                $('#selectedDate').val(formatDate(current)).trigger('change');
            });

            $('#btnNextDay').on('click', function() {
                const current = new Date($('#selectedDate').val());
                current.setDate(current.getDate() + 1);
                $('#selectedDate').val(formatDate(current)).trigger('change');
            });

            // Initialize
            updateDateLabel();

            // ===== ADD ITEMS MODAL =====

            let itemsToAddList = [];

            $('#btnAddItems, #btnAddItemsMobile, #btnAddItemsEmpty, #btnAddItemsMobileEmpty').on('click', function() {
                if (inventoryLocked) {
                    showToast('warning', 'Distribution is locked because inventory has already been created for this date. Delete the inventory first to make changes.', 4000);
                    return;
                }
                $('#scheduleDate').val($('#selectedDate').val());
                updateScheduleQuickBtns();
                itemsToAddList = [];
                renderAddedItemsList();
                $('#productSearch').val('');
                $('#selectedProductId').val('');
                $('#btnClearProduct').addClass('hidden');
                $('#addProductQty').val(10);
                hideProductDropdown();
                $('#addItemsModal').removeClass('hidden');
            });

            $('#btnCloseAddItemsModal, #btnCancelAddItems').on('click', function() {
                $('#addItemsModal').addClass('hidden');
            });

            // Product search input events
            $('#productSearch').on('focus', function() {
                showProductDropdown($(this).val());
            });

            $('#productSearch').on('input', function() {
                $('#selectedProductId').val('');
                $('#btnClearProduct').addClass('hidden');
                showProductDropdown($(this).val());
            });

            $('#addProductQty').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#btnAddProductToList').click();
                }
            });

            $(document).on('click', '.product-option', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                $('#selectedProductId').val(id);
                $('#productSearch').val(name);
                $('#btnClearProduct').removeClass('hidden');
                hideProductDropdown();
                $('#addProductQty').focus();
            });

            $('#btnClearProduct').on('click', function() {
                $('#selectedProductId').val('');
                $('#productSearch').val('');
                $(this).addClass('hidden');
                $('#productSearch').focus();
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#productSearch, #productDropdown').length) {
                    hideProductDropdown();
                }
            });

            function showProductDropdown(searchTerm = '') {
                const term = (searchTerm || '').toLowerCase();
                let html = '';
                const filtered = productsData.filter(p => p.product_name.toLowerCase().includes(term));

                if (filtered.length === 0) {
                    html = '<div class="px-3 py-2 text-sm text-gray-500">No products found</div>';
                } else {
                    filtered.forEach(function(product) {
                        const alreadyAdded = itemsToAddList.some(i => i.product_id == product.product_id);
                        const disabledClass = alreadyAdded ? 'opacity-50 pointer-events-none' : 'hover:bg-primary/10 cursor-pointer';
                        const badge = alreadyAdded ? '<span class="text-xs text-green-600 font-medium">Added</span>' : '';
                        html += `<div class="product-option px-3 py-2 text-sm ${disabledClass} flex items-center justify-between" data-id="${product.product_id}" data-name="${product.product_name}">
                            <span>${product.product_name}</span>
                            ${badge}
                        </div>`;
                    });
                }
                $('#productDropdown').html(html).removeClass('hidden');
            }

            function hideProductDropdown() {
                $('#productDropdown').addClass('hidden');
            }

            $('#btnAddProductToList').on('click', function() {
                const productId = $('#selectedProductId').val();
                const productName = $('#productSearch').val();
                const quantity = parseInt($('#addProductQty').val()) || 0;

                if (!productId) {
                    showToast('warning', 'Please search and select a product first.', 3000);
                    return;
                }
                if (quantity <= 0) {
                    showToast('warning', 'Please enter a valid quantity.', 3000);
                    return;
                }
                if (itemsToAddList.some(i => i.product_id == productId)) {
                    showToast('warning', 'This product is already in the list.', 3000);
                    return;
                }

                itemsToAddList.push({ product_id: productId, product_name: productName, quantity: quantity });
                renderAddedItemsList();

                $('#productSearch').val('');
                $('#selectedProductId').val('');
                $('#btnClearProduct').addClass('hidden');
                $('#addProductQty').val(10);
                $('#productSearch').focus();
            });

            $(document).on('click', '.btn-remove-added-item', function() {
                const idx = $(this).data('index');
                itemsToAddList.splice(idx, 1);
                renderAddedItemsList();
            });

            function renderAddedItemsList() {
                const container = $('#itemsContainer');
                container.empty();

                if (itemsToAddList.length === 0) {
                    container.html('<p id="noItemsMsg" class="text-sm text-gray-500 text-center py-2">No products added yet</p>');
                    $('#itemsSummaryCount').text('0 items');
                    return;
                }

                itemsToAddList.forEach(function(item, index) {
                    const row = `
                        <div class="flex items-center justify-between p-2 bg-white rounded-md border border-gray-200">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-8 h-8 rounded-md bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-bread-slice text-primary text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-sm font-medium text-gray-800 truncate block">${item.product_name}</span>
                                    <span class="text-xs text-gray-500">${item.quantity} pcs</span>
                                </div>
                            </div>
                            <button type="button" class="btn-remove-added-item p-1.5 text-red-500 hover:bg-red-50 rounded-md flex-shrink-0" data-index="${index}" title="Remove">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    `;
                    container.append(row);
                });

                const count = itemsToAddList.length;
                $('#itemsSummaryCount').text(count + (count === 1 ? ' item' : ' items'));
            }

            // ===== EDIT QTY MODAL =====

            $('#btnCloseEditQtyModal, #btnCancelEditQty').on('click', function() {
                $('#editQtyModal').addClass('hidden');
            });

            $('.schedule-quick-btn').on('click', function() {
                const days = parseInt($(this).data('days'));
                const newDate = new Date();
                newDate.setDate(newDate.getDate() + days);
                $('#scheduleDate').val(formatDate(newDate));
                updateScheduleQuickBtns();
            });

            $('#btnEditQtyInc').on('click', function() {
                const input = $('#editQuantity');
                input.val(parseInt(input.val() || 0) + 5);
            });

            $('#btnEditQtyDec').on('click', function() {
                const input = $('#editQuantity');
                const val = parseInt(input.val() || 0);
                if (val > 5) input.val(val - 5);
            });

            $(document).on('click', '.btn-edit-qty', function() {
                if (inventoryLocked) {
                    showToast('warning', 'Distribution is locked because inventory has already been created for this date. Delete the inventory first to make changes.', 4000);
                    return;
                }
                const row = $(this).closest('[data-id]');
                const productName = row.find('span.font-medium, span.truncate').first().text();
                const qty = row.find('.font-bold').first().text();

                $('#editProductName').text(productName);
                $('#editQuantity').val(parseInt(qty));
                $('#editItemId').val(row.data('id'));
                $('#editQtyModal').removeClass('hidden');
            });

            $(document).on('click', '.btn-edit-qty-mobile', function() {
                if (inventoryLocked) {
                    showToast('warning', 'Distribution is locked because inventory has already been created for this date. Delete the inventory first to make changes.', 4000);
                    return;
                }
                const card = $(this).closest('[data-id]');
                const productName = card.find('h4').text();
                const qtyText = card.find('.text-xs.text-gray-500').text();
                const qty = parseInt(qtyText) || 0;

                $('#editProductName').text(productName);
                $('#editQuantity').val(qty);
                $('#editItemId').val(card.data('id'));
                $('#editQtyModal').removeClass('hidden');
            });

            $(document).on('click', '.btn-delete', function() {
                if (inventoryLocked) {
                    showToast('warning', 'Distribution is locked because inventory has already been created for this date. Delete the inventory first to make changes.', 4000);
                    return;
                }
                const row = $(this).closest('[data-id]');
                const itemId = row.data('id');

                Confirm.delete('Are you sure you want to remove this item?', function() {
                    deleteDistributionItem(itemId);
                });
            });

            $(document).on('click', '.btn-delete-mobile', function() {
                if (inventoryLocked) {
                    showToast('warning', 'Distribution is locked because inventory has already been created for this date. Delete the inventory first to make changes.', 4000);
                    return;
                }
                const card = $(this).closest('[data-id]');
                const itemId = card.data('id');

                Confirm.delete('Are you sure you want to remove this item?', function() {
                    deleteDistributionItem(itemId);
                });
            });

            // ===== FORM SUBMISSIONS =====

            $('#addItemsForm').on('submit', function(e) {
                e.preventDefault();

                const scheduleDate = $('#scheduleDate').val();

                if (itemsToAddList.length === 0) {
                    showToast('warning', 'Please add at least one product to the list.', 3000);
                    return;
                }

                const itemsToAdd = itemsToAddList.map(function(item) {
                    return { product_id: item.product_id, quantity: item.quantity };
                });

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

                function onAllItemsAdded(hadError, duplicates) {
                    $('#btnSaveItems').prop('disabled', false).html('<i class="fas fa-plus mr-2"></i>Add to Schedule');
                    $('#addItemsModal').addClass('hidden');

                    $('#selectedDate').val(scheduleDate).trigger('change');
                    loadMonthDistributions();

                    // Reset form
                    $('#itemsContainer').html(getItemRowTemplate());
                    updateItemsSummary();

                    if (duplicates && duplicates.length > 0) {
                        showToast('warning', 'The following products are already scheduled for this date and were skipped: ' + duplicates.join(', '), 5000);
                    } else if (hadError && (!insufficients || insufficients.length === 0)) {
                        showToast('danger', 'Some items could not be added. Please check and try again.', 3000);
                    } else {
                        showToast('success', 'Items added successfully!', 3000);
                    }
                }
            });

            $('#editQtyForm').on('submit', function(e) {
                e.preventDefault();
                const itemId = $('#editItemId').val();
                const quantity = $('#editQuantity').val();

                updateDistributionItem(itemId, quantity);
                $('#editQtyModal').addClass('hidden');
            });

        });

        function formatDate(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
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

        function updateSummaryCounts(items) {
            const total = items ? items.length : 0;
            let totalQty = 0;
            if (items) {
                items.forEach(function(item) {
                    totalQty += parseInt(item.product_qnty) || 0;
                });
            }

            $('#totalItemsCount').text(total);
            $('#totalQuantityCount').text(totalQty);
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