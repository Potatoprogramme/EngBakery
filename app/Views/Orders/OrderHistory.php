<?php
$isStaffView = strtolower((string) ($employee_type ?? session('employee_type') ?? '')) === 'staff';
$isOwnerView = strtolower((string) ($employee_type ?? session('employee_type') ?? '')) === 'owner';
?>

<body class="bg-gray-50">
    <div class="p-4 sm:ml-60">
        <div class="mt-16">
            <nav class="mb-3 sm:mb-4" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-1 text-sm text-gray-500 justify-left sm:justify-start">
                    <li><a href="<?= base_url('Dashboard') ?>" class="hover:text-primary">Dashboard</a></li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li><a href="<?= base_url('Order') ?>" class="hover:text-primary">Order</a></li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li class="text-gray-700">Order History</li>
                </ol>
            </nav>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4">
                <!-- Total Orders Card -->
                <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Total Orders</p>
                            <p class="text-lg sm:text-2xl font-bold text-blue-600" id="todayTotalOrders">0</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-blue-100 rounded-full hidden sm:block">
                            <i class="fas fa-receipt text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue Card -->
                <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-primary">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Revenue</p>
                            <p class="text-lg sm:text-2xl font-bold text-primary" id="todayTotalRevenue">₱0.00</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-primary/10 rounded-full hidden sm:block">
                            <i class="fas fa-peso-sign text-primary text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Items Sold Card -->
                <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Items Sold</p>
                            <p class="text-lg sm:text-2xl font-bold text-green-600" id="todayItemsSold">0</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-green-100 rounded-full hidden sm:block">
                            <i class="fas fa-shopping-basket text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- In Stock Card -->
                <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-amber-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">In Stock</p>
                            <p class="text-lg sm:text-2xl font-bold text-amber-600" id="todayStockCount">0</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-amber-100 rounded-full hidden sm:block">
                            <i class="fas fa-boxes-stacked text-amber-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Summary Table (Collapsible) -->
            <div class="mb-4 bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
                <button id="toggleStockSummary"
                    class="w-full p-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 flex items-center justify-center bg-gray-50 rounded-lg">
                            <i class="fas fa-box-open text-gray-600"></i>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-800">Today's Stock Summary</span>
                            <span id="stockSummaryBadge"
                                class="ml-2 px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">0
                                items</span>
                        </div>
                    </div>
                    <i id="stockChevron" class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                </button>
                <div id="stockSummaryContent" class="border-t border-gray-100">
                    <div class="p-4">
                        <div id="stockSummaryBody" class="space-y-3">
                            <div class="text-center text-gray-500 py-8">Loading stock data...</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Order History</h2>
                    <div class="flex flex-wrap gap-2">
                        <a href="<?= base_url('Order') ?>"
                            class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-plus mr-2"></i>New Order
                        </a>
                    </div>
                </div>
                <div class="border-t border-gray-200 my-4"></div>
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 flex-1">
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <label for="filterDateFrom"
                                class="text-sm text-gray-600 whitespace-nowrap w-12 sm:w-auto">From:</label>
                            <input type="date" id="filterDateFrom"
                                class="flex-1 sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <label for="filterDateTo"
                                class="text-sm text-gray-600 whitespace-nowrap w-12 sm:w-auto">To:</label>
                            <input type="date" id="filterDateTo"
                                class="flex-1 sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <label for="filterOrderType"
                                class="text-sm text-gray-600 whitespace-nowrap w-12 sm:w-auto">Type:</label>
                            <select id="filterOrderType"
                                class="flex-1 sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                                <option value="">All Types</option>
                                <option value="walk-in">Walk-in</option>
                                <option value="foodpanda">Foodpanda</option>
                                <?php if (!$isStaffView): ?>
                                <option value="distributed">Distributed</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2 sm:justify-end">
                        <button id="btnApplyFilters" type="button"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">Apply</button>
                        <button id="btnResetFilters" type="button"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 sm:mb-0">
                <table id="ordersTable" class="min-w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Order #</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Date</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Time</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Cashier</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Type</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Payment</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Amount</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Status</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody">
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500"><i
                                    class="fas fa-spinner fa-spin text-2xl"></i>
                                <p class="mt-2">Loading orders...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden space-y-3 mb-20" id="ordersCards">
                <div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500">
                    <i class="fas fa-spinner fa-spin text-4xl mb-3"></i>
                    <p>Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div id="orderDetailsModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg mx-auto border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
            <div id="receiptContent" class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="text-center flex-1">
                        <h2 class="text-2xl font-bold text-gray-800">EngBakery</h2>
                        <p class="text-sm text-gray-500">Order Receipt</p>
                    </div>
                    <button type="button" id="btnCloseOrderDetails" class="text-gray-400 hover:text-gray-600"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="border-t border-dashed border-gray-300 py-3">
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div><span class="text-gray-600">Order #:</span></div>
                        <div class="text-right font-semibold" id="detailOrderNumber">-</div>
                        <div><span class="text-gray-600">Date:</span></div>
                        <div class="text-right" id="detailOrderDate">-</div>
                        <div><span class="text-gray-600">Time:</span></div>
                        <div class="text-right" id="detailOrderTime">-</div>
                        <div><span class="text-gray-600">Type:</span></div>
                        <div class="text-right" id="detailOrderType">-</div>
                        <div><span class="text-gray-600">Payment:</span></div>
                        <div class="text-right" id="detailPaymentMethod">-</div>
                    </div>
                </div>
                <div id="distributedNoteSection" class="hidden border-t border-dashed border-gray-300 py-3">
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-truck text-purple-500"></i>
                            <span class="text-sm font-semibold text-purple-700">Distribution Note</span>
                        </div>
                        <p class="text-sm text-purple-600" id="detailDistributedNote">-</p>
                    </div>
                </div>
                <div id="voidedInfoSection" class="hidden border-t border-dashed border-gray-300 py-3">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-ban text-red-500"></i>
                            <span class="text-sm font-semibold text-red-700">ORDER VOIDED</span>
                        </div>
                        <div class="text-xs text-red-600">
                            <div>Voided by: <span id="detailVoidedBy" class="font-medium">-</span></div>
                            <div>Voided on: <span id="detailVoidedAt" class="font-medium">-</span></div>
                        </div>
                    </div>
                </div>
                <div class="border-t border-dashed border-gray-300 py-3">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Items</h4>
                    <div id="orderItemsList" class="space-y-2 text-sm"></div>
                </div>
                <div class="border-t border-dashed border-gray-300 py-3 space-y-2">
                    <div class="flex justify-between font-bold text-lg">
                        <span>TOTAL:</span>
                        <span id="detailTotalAmount">₱0.00</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Amount Received:</span>
                        <span id="detailAmountReceived">₱0.00</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Change:</span>
                        <span id="detailChange">₱0.00</span>
                    </div>
                </div>
            </div>
            <div class="px-6 pb-6 flex gap-2">
                <button type="button" id="btnPrintReceipt"
                    class="flex-1 px-4 py-3 text-sm font-medium text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition-all">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
                <button type="button" id="btnVoidOrder"
                    class="px-4 py-3 text-sm font-medium text-red-600 border border-red-300 rounded-lg hover:bg-red-600 hover:text-white transition-all">
                    <i class="fas fa-ban mr-2"></i>Void
                </button>
                <?php if ($isOwnerView): ?>
                <button type="button" id="btnDeleteOrder"
                    class="px-4 py-3 text-sm font-medium text-white bg-red-700 rounded-lg hover:bg-red-800 transition-all">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
                <?php endif; ?>
                <button type="button" id="btnCloseModal"
                    class="flex-1 px-4 py-3 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary transition-all">Close</button>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a89dedcb22.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>

    <script>
    window.BASE_URL = '<?= rtrim(site_url(), '/') ?>/';
    window.ASSET_URL = '<?= base_url() ?>';
    const isOwnerView = <?= $isOwnerView ? 'true' : 'false' ?>;
    let dataTable = null;
    let currentOrderId = null;

    function syncOrderHistoryBodyScrollLock() {
        const hasOpenModal = !$('#orderDetailsModal').hasClass('hidden');
        $('body').toggleClass('overflow-hidden', hasOpenModal);
    }

    $(document).ready(function() {
        // Set date range: 1st of current month to today
        const today = new Date();
        const firstOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        const todayStr = today.toISOString().split('T')[0];
        const firstStr = firstOfMonth.toISOString().split('T')[0];
        $('#filterDateFrom').val(firstStr);
        $('#filterDateTo').val(todayStr);

        loadOrders(firstStr, todayStr); // Load this month's orders by default
        initFilters();
        initOrderDetailsModal();
        initStockSummaryToggle();
    });

    // Load summary cards using current order history filters
    function loadFilteredSummary(dateFrom = null, dateTo = null, orderType = null) {
        const params = [];
        if (dateFrom) params.push('date_from=' + encodeURIComponent(dateFrom));
        if (dateTo) params.push('date_to=' + encodeURIComponent(dateTo));
        if (orderType) params.push('order_type=' + encodeURIComponent(orderType));

        let url = BASE_URL + 'Order/GetOrderHistorySummary';
        if (params.length) url += '?' + params.join('&');

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#todayTotalOrders').text(response.data.total_orders || 0);
                    $('#todayTotalRevenue').text('₱' + parseFloat(response.data.total_revenue || 0).toFixed(
                        2));
                    $('#todayItemsSold').text(response.data.total_items_sold || 0);
                }
            }
        });
    }

    // Load Stock Summary
    function loadStockSummary(dateForStock = null) {
        let url = BASE_URL + 'Order/GetTodaysStockSummary';
        if (dateForStock) {
            url += '?date=' + encodeURIComponent(dateForStock);
        }

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data && response.data.length > 0) {
                    // Separate bakery, drinks, and grocery
                    const breadItems = response.data.filter(item => item.category === 'bakery');
                    const drinkItems = response.data.filter(item => item.category === 'drinks');
                    const groceryItems = response.data.filter(item => item.category === 'grocery');

                    let html = '';
                    let totalProducts = response.data.length;

                    // Render Bakery Section
                    if (breadItems.length > 0) {
                        html += `
                                <div class="mb-4">
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                                        <i class="fas fa-bread-slice text-amber-500"></i> Bakery (${breadItems.length})
                                    </h4>
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                            `;

                        breadItems.forEach(item => {
                            const beginning = parseInt(item.beginning_stock) || 0;
                            const pullOut = parseInt(item.pull_out_quantity) || 0;
                            const remaining = parseInt(item.ending_stock) || 0;
                            const sold = beginning - remaining - pullOut;

                            const remainingClass = remaining <= 5 ? 'text-red-600' : remaining <=
                                10 ? 'text-orange-500' : 'text-gray-700';
                            const stockStatus = remaining <= 5 ? 'Low Stock' : remaining <= 10 ?
                                'Running Low' : 'In Stock';
                            const statusClass = remaining <= 5 ? 'bg-red-50 text-red-700' :
                                remaining <= 10 ? 'bg-orange-50 text-orange-700' :
                                'bg-green-50 text-green-700';

                            html += `
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-gray-300 transition-colors">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex-1">
                                                <h5 class="font-medium text-gray-900 text-sm">${item.product_name}</h5>
                                                <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded ${statusClass}">${stockStatus}</span>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-4 gap-2 text-center mt-3 pt-3 border-t border-gray-200">
                                            <div>
                                                <div class="text-xs text-gray-500">Begin</div>
                                                <div class="font-semibold text-gray-700">${beginning}</div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500">Sold</div>
                                                <div class="font-semibold text-green-600">${sold > 0 ? sold : 0}</div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500">Pull</div>
                                                <div class="font-semibold text-orange-600">${pullOut}</div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500">Left</div>
                                                <div class="font-semibold ${remainingClass}">${remaining}</div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                        });

                        html += `
                                    </div>
                                </div>
                            `;
                    }

                    // Render Drinks Section
                    if (drinkItems.length > 0) {
                        html += `
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                                        <i class="fas fa-mug-hot text-blue-500"></i> Drinks (${drinkItems.length})
                                    </h4>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            `;

                        drinkItems.forEach(item => {
                            // For drinks, use quantity_sold from transactions (not stock calculation)
                            const sold = parseInt(item.quantity_sold) || 0;

                            html += `
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-gray-300 transition-colors text-center">
                                        <div class="font-medium text-gray-900 text-sm mb-2">${item.product_name}</div>
                                        <div class="text-xs text-gray-500 mb-1">Sold</div>
                                        <div class="text-2xl font-bold text-green-600">${sold}</div>
                                    </div>
                                `;
                        });

                        html += `
                                    </div>
                                </div>
                            `;
                    }

                    // Render Grocery Section
                    if (groceryItems.length > 0) {
                        html += `
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                                        <i class="fas fa-shopping-basket text-green-500"></i> Grocery (${groceryItems.length})
                                    </h4>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            `;

                        groceryItems.forEach(item => {
                            const beginning = parseInt(item.beginning_stock) || 0;
                            const pullOut = parseInt(item.pull_out_quantity) || 0;
                            const remaining = parseInt(item.ending_stock) || 0;
                            const sold = beginning - remaining - pullOut;

                            html += `
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-gray-300 transition-colors text-center">
                                        <div class="font-medium text-gray-900 text-sm mb-2">${item.product_name}</div>
                                        <div class="text-xs text-gray-500 mb-1">Sold</div>
                                        <div class="text-2xl font-bold text-green-600">${sold > 0 ? sold : 0}</div>
                                    </div>
                                `;
                        });

                        html += `
                                    </div>
                                </div>
                            `;
                    }

                    $('#stockSummaryBody').html(html);
                    $('#todayStockCount').text(totalProducts);
                    $('#stockSummaryBadge').text(totalProducts + ' items');
                } else {
                    $('#stockSummaryBody').html(
                        '<div class="text-center text-gray-500 py-8">No inventory data for selected date. <a href="' +
                        BASE_URL +
                        'Inventory" class="text-primary hover:underline font-medium">Create inventory first</a>.</div>'
                        );
                    $('#todayStockCount').text('0');
                    $('#stockSummaryBadge').text('No inventory');
                }
            },
            error: function() {
                $('#stockSummaryBody').html(
                    '<div class="text-center text-red-500 py-8">Error loading stock data</div>');
            }
        });
    }

    // Toggle Stock Summary Section
    function initStockSummaryToggle() {
        // Keep stock summary collapsed by default
        $('#stockSummaryContent').addClass('hidden');
        $('#stockChevron').removeClass('rotate-180');

        $('#toggleStockSummary').on('click', function() {
            $('#stockSummaryContent').toggleClass('hidden');
            $('#stockChevron').toggleClass('rotate-180');
        });
    }

    function loadOrders(dateFrom = null, dateTo = null, orderType = null) {
        let url = BASE_URL + 'Order/GetOrderHistory';
        const params = [];
        if (dateFrom) params.push('date_from=' + dateFrom);
        if (dateTo) params.push('date_to=' + dateTo);
        if (orderType) params.push('order_type=' + orderType);
        if (params.length) url += '?' + params.join('&');

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    renderOrders(response.data);
                    // Keep summary cards in sync with selected filters
                    loadFilteredSummary(dateFrom, dateTo, orderType);
                    // Show stock snapshot based on end date (or start date if end is empty)
                    loadStockSummary(dateTo || dateFrom || null);
                } else {
                    $('#ordersTableBody').html(
                        '<tr><td colspan="9" class="px-6 py-8 text-center text-gray-500">Failed to load orders</td></tr>'
                        );
                }
            },
            error: function() {
                $('#ordersTableBody').html(
                    '<tr><td colspan="9" class="px-6 py-8 text-center text-gray-500">Error loading orders</td></tr>'
                    );
            }
        });
    }

    function renderOrders(orders) {
        renderDesktopTable(orders);
        renderMobileCards(orders);
    }

    function formatTime(timeStr) {
        if (!timeStr) return '--:--';
        const [hours, minutes] = timeStr.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return `${hour12}:${minutes || '00'} ${ampm}`;
    }

    function getOrderMeta(order) {
        const orderDate = new Date(order.date_created);
        const dateStr = orderDate.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
        const timeStr = formatTime(order.time_created);
        let typeClass, typeIcon, typeName;
        if (order.order_type === 'foodpanda') {
            typeClass = 'bg-pink-100 text-pink-800';
            typeIcon =
                `<img src="${ASSET_URL}assets/pictures/food-panda.svg" class="w-4 h-4 inline-block mr-1" alt="FoodPanda">`;
            typeName = 'Foodpanda';
        } else if (order.order_type === 'distributed') {
            typeClass = 'bg-purple-100 text-purple-800';
            typeIcon = '<i class="fas fa-truck mr-1"></i>';
            typeName = 'Distributed';
        } else {
            typeClass = 'bg-blue-100 text-blue-800';
            typeIcon = '<i class="fas fa-walking mr-1"></i>';
            typeName = 'Walk-in';
        }
        const cashierName = order.cashier_name || 'Unknown';
        const isVoided = order.voided_at !== null && order.voided_at !== undefined;
        const orderNumber = `${order.date_created}-${order.order_id}`;
        return {
            dateStr,
            timeStr,
            typeClass,
            typeIcon,
            typeName,
            cashierName,
            isVoided,
            orderNumber
        };
    }

    function renderDesktopTable(orders) {
        if (dataTable) {
            dataTable.destroy();
            dataTable = null;
        }

        if (!orders || orders.length === 0) {
            $('#ordersTableBody').html(
                '<tr><td colspan="9" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-receipt text-4xl mb-3"></i><p>No orders found</p></td></tr>'
                );
            return;
        }

        let html = '';
        orders.forEach(order => {
            const m = getOrderMeta(order);
            const rowClass = m.isVoided ? 'border-b bg-red-50/50 hover:bg-red-50' : 'border-b hover:bg-gray-50';
            const amountClass = m.isVoided ? 'line-through text-gray-400' : 'text-primary font-bold';
            const statusBadge = m.isVoided ?
                '<span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 inline-flex items-center"><i class="fas fa-ban mr-1"></i>Voided</span>' :
                '<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 inline-flex items-center"><i class="fas fa-check mr-1"></i>Completed</span>';

            html += `
                    <tr class="${rowClass}">
                        <td class="px-6 py-4 whitespace-nowrap font-medium ${m.isVoided ? 'text-gray-400' : 'text-gray-900'}">${m.orderNumber}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">${m.dateStr}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">${m.timeStr}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 text-sm">${m.cashierName}</td>
                        <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 rounded-full text-xs font-medium ${m.typeClass} inline-flex items-center">${m.typeIcon}${m.typeName}</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 capitalize">${order.payment_method}</td>
                        <td class="px-6 py-4 whitespace-nowrap ${amountClass}">₱${parseFloat(order.total_payment_due).toFixed(2)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button type="button" class="btn-view-order text-primary py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-secondary hover:bg-gray-200" data-order-id="${order.order_id}">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
        });

        $('#ordersTableBody').html(html);

        dataTable = new simpleDatatables.DataTable("#ordersTable", {
            searchable: true,
            sortable: true,
            perPage: 10,
            perPageSelect: [5, 10, 25, 50],
            labels: {
                placeholder: "Search orders...",
                noRows: "No orders found",
                info: "Showing {start} to {end} of {rows} orders"
            }
        });

        $('#ordersTable').on('click', '.btn-view-order', function() {
            openOrderDetails($(this).data('order-id'));
        });
    }

    function renderMobileCards(orders) {
        if (!orders || orders.length === 0) {
            $('#ordersCards').html(`
                    <div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500">
                        <i class="fas fa-receipt text-4xl mb-3"></i>
                        <p>No orders found</p>
                    </div>
                `);
            return;
        }

        let html = '';
        orders.forEach(order => {
            const m = getOrderMeta(order);
            const headerBg = m.isVoided ? 'bg-red-500/90' : 'bg-primary/90';
            const amountClass = m.isVoided ? 'line-through text-gray-400' : 'text-primary';
            const statusBadge = m.isVoided ?
                '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700"><i class="fas fa-ban mr-1"></i>Voided</span>' :
                '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700"><i class="fas fa-check mr-1"></i>Completed</span>';

            html += `
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-300">
                        <!-- Card Header -->
                        <div class="${headerBg} px-4 py-3 border-b border-gray-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-receipt text-white"></i>
                                    <span class="font-bold text-white">Order #${m.orderNumber}</span>
                                </div>
                                <span class="text-xs text-gray-200">${m.dateStr}</span>
                            </div>
                            <div class="flex items-center gap-2 mt-1 text-xs text-gray-200">
                                <i class="fas fa-clock text-xs"></i>
                                <span>${m.timeStr}</span>
                                <span class="mx-1">•</span>
                                <i class="fas fa-user text-xs"></i>
                                <span>${m.cashierName}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4">
                            <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-gray-600"><i class="fas fa-tag text-blue-500 mr-1"></i>Type</span>
                                    <span class="inline-flex items-center text-xs font-medium">${m.typeIcon}${m.typeName}</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-gray-600"><i class="fas fa-credit-card text-green-500 mr-1"></i>Pay</span>
                                    <span class="font-semibold text-gray-900 capitalize text-xs">${order.payment_method}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <div>
                                    <p class="text-xs text-gray-500">Total Amount</p>
                                    <p class="text-xl font-bold ${amountClass}">₱${parseFloat(order.total_payment_due).toFixed(2)}</p>
                                    <div class="mt-1">${statusBadge}</div>
                                </div>
                                <button type="button" class="btn-view-order-mobile px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary transition-all" data-order-id="${order.order_id}">
                                    <i class="fas fa-eye mr-1"></i>View
                                </button>
                            </div>
                        </div>
                    </div>
                `;
        });

        $('#ordersCards').html(html);

        $('.btn-view-order-mobile').on('click', function() {
            openOrderDetails($(this).data('order-id'));
        });
    }

    function initFilters() {
        $('#btnApplyFilters').on('click', function() {
            const dateFrom = $('#filterDateFrom').val();
            const dateTo = $('#filterDateTo').val();
            const orderType = $('#filterOrderType').val();
            loadOrders(dateFrom, dateTo, orderType);
        });

        $('#btnResetFilters').on('click', function() {
            const today = new Date();
            const firstOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const todayStr = today.toISOString().split('T')[0];
            const firstStr = firstOfMonth.toISOString().split('T')[0];
            $('#filterDateFrom').val(firstStr);
            $('#filterDateTo').val(todayStr);
            $('#filterOrderType').val('');
            loadOrders(firstStr, todayStr);
        });
    }

    function getCurrentFilters() {
        return {
            dateFrom: $('#filterDateFrom').val() || null,
            dateTo: $('#filterDateTo').val() || null,
            orderType: $('#filterOrderType').val() || null
        };
    }

    function initOrderDetailsModal() {
        $('#btnCloseOrderDetails, #btnCloseModal').on('click', function() {
            $('#orderDetailsModal').addClass('hidden');
            syncOrderHistoryBodyScrollLock();
        });

        $('#btnPrintReceipt').on('click', function() {
            const content = $('#receiptContent').clone();
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                    <html>
                    <head>
                        <title>Receipt - EngBakery</title>
                        <style>
                            body { font-family: 'Courier New', monospace; padding: 20px; max-width: 300px; margin: 0 auto; }
                            .text-center { text-align: center; }
                            .font-bold { font-weight: bold; }
                            .text-2xl { font-size: 1.5rem; }
                            .text-lg { font-size: 1.1rem; }
                            .text-sm { font-size: 0.9rem; }
                            .border-t { border-top: 1px dashed #ccc; padding-top: 10px; margin-top: 10px; }
                            .flex { display: flex; justify-content: space-between; }
                            .space-y-2 > * + * { margin-top: 0.5rem; }
                            .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5px; }
                            .text-right { text-align: right; }
                            #btnCloseOrderDetails { display: none; }
                        </style>
                    </head>
                    <body>${content.html()}</body>
                    </html>
                `);
            printWindow.document.close();
            printWindow.print();
        });

        $('#btnVoidOrder').on('click', function() {
            const btn = $(this);

            // Prevent double submission
            if (typeof ButtonLoader !== 'undefined' && ButtonLoader.isLoading(btn)) {
                return;
            }

            if (!currentOrderId) return;
            Confirm.show('Are you sure you want to void this order? This action cannot be undone.', function() {
                voidOrder(currentOrderId, btn);
            });
        });

        if (isOwnerView && $('#btnDeleteOrder').length) {
            $('#btnDeleteOrder').on('click', function() {
                const btn = $(this);

                if (typeof ButtonLoader !== 'undefined' && ButtonLoader.isLoading(btn)) {
                    return;
                }

                if (!currentOrderId) return;
                Confirm.show('Delete this order permanently? This will also delete its transactions.',
                function() {
                    deleteOrder(currentOrderId, btn);
                });
            });
        }
    }

    function openOrderDetails(orderId) {
        currentOrderId = orderId;
        $.ajax({
            url: BASE_URL + 'Order/GetOrderDetails/' + orderId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const order = response.data.order;
                    const items = response.data.items;
                    const orderDate = new Date(order.date_created + ' ' + order.time_created);

                    $('#detailOrderNumber').text(order.order_number);
                    $('#detailOrderDate').text(orderDate.toLocaleDateString('en-US', {
                        month: 'long',
                        day: 'numeric',
                        year: 'numeric'
                    }));
                    $('#detailOrderTime').text(orderDate.toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    }));

                    // Set order type with icon
                    if (order.order_type === 'foodpanda') {
                        $('#detailOrderType').html('<span class="inline-flex items-center"><img src="' +
                            ASSET_URL +
                            'assets/pictures/food-panda.svg" class="w-4 h-4 mr-1" alt="FoodPanda">Foodpanda</span>'
                            );
                    } else if (order.order_type === 'distributed') {
                        $('#detailOrderType').html(
                            '<span class="inline-flex items-center text-purple-700"><i class="fas fa-truck mr-1"></i>Distributed</span>'
                            );
                    } else {
                        $('#detailOrderType').html(
                            '<span class="inline-flex items-center"><i class="fas fa-walking mr-1"></i>Walk-in</span>'
                            );
                    }

                    // Show distributed note if applicable
                    if (order.order_type === 'distributed' && order.distributed_note) {
                        $('#detailDistributedNote').text(order.distributed_note);
                        $('#distributedNoteSection').removeClass('hidden');
                    } else {
                        $('#distributedNoteSection').addClass('hidden');
                    }

                    $('#detailPaymentMethod').text(order.payment_method.charAt(0).toUpperCase() + order
                        .payment_method.slice(1));

                    let itemsHtml = '';
                    items.forEach(item => {
                        itemsHtml += `
                                <div class="flex justify-between">
                                    <span>${item.product_name} x${item.amount}</span>
                                    <span>₱${parseFloat(item.total_cost_of_item).toFixed(2)}</span>
                                </div>
                            `;
                    });
                    $('#orderItemsList').html(itemsHtml);

                    $('#detailTotalAmount').text('₱' + parseFloat(order.total_payment_due).toFixed(2));
                    $('#detailAmountReceived').text('₱' + parseFloat(order.amount_received).toFixed(2));
                    $('#detailChange').text('₱' + parseFloat(order.amount_change).toFixed(2));

                    // Show/hide voided info
                    if (order.voided_at) {
                        const voidedDate = new Date(order.voided_at);
                        $('#detailVoidedBy').text(order.voided_by || 'Unknown');
                        $('#detailVoidedAt').text(voidedDate.toLocaleDateString('en-US', {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric'
                        }) + ' at ' + voidedDate.toLocaleTimeString('en-US', {
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        }));
                        $('#voidedInfoSection').removeClass('hidden');
                        $('#btnVoidOrder').addClass('hidden');
                    } else {
                        $('#voidedInfoSection').addClass('hidden');
                        $('#btnVoidOrder').removeClass('hidden');
                    }

                    $('#orderDetailsModal').removeClass('hidden');
                    syncOrderHistoryBodyScrollLock();
                } else {
                    Toast.error('Failed to load order details');
                }
            },
            error: function() {
                Toast.error('Error loading order details');
            }
        });
    }

    function voidOrder(orderId, btn) {
        if (typeof ButtonLoader !== 'undefined') {
            ButtonLoader.start(btn, 'Voiding...');
        }

        $.ajax({
            url: BASE_URL + 'Order/VoidOrder/' + orderId,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (typeof ButtonLoader !== 'undefined') {
                    ButtonLoader.stop(btn);
                }
                if (response.success) {
                    console.log(response);
                    Toast.success('Order voided successfully');
                    $('#orderDetailsModal').addClass('hidden');
                    syncOrderHistoryBodyScrollLock();
                    const filters = getCurrentFilters();
                    loadOrders(filters.dateFrom, filters.dateTo, filters.orderType);
                } else {
                    console.error(response);
                }
            },
            error: function(xhr) {
                if (typeof ButtonLoader !== 'undefined') {
                    ButtonLoader.stop(btn);
                }
                Toast.error('Error voiding order');
                console.error(xhr);
            }
        });
    }

    function deleteOrder(orderId, btn) {
        if (typeof ButtonLoader !== 'undefined') {
            ButtonLoader.start(btn, 'Deleting...');
        }

        $.ajax({
            url: BASE_URL + 'Order/DeleteOrder/' + orderId,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (typeof ButtonLoader !== 'undefined') {
                    ButtonLoader.stop(btn);
                }
                if (response.success) {
                    Toast.success('Order deleted successfully');
                    $('#orderDetailsModal').addClass('hidden');
                    syncOrderHistoryBodyScrollLock();
                    const filters = getCurrentFilters();
                    loadOrders(filters.dateFrom, filters.dateTo, filters.orderType);
                } else {
                    Toast.error(response.message || 'Failed to delete order');
                }
            },
            error: function() {
                if (typeof ButtonLoader !== 'undefined') {
                    ButtonLoader.stop(btn);
                }
                Toast.error('Error deleting order');
            }
        });
    }
    </script>