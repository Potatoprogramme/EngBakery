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
                    <li><a href="<?= base_url('Sales') ?>" class="hover:text-primary">Daily Sales Remittance</a></li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li class="text-gray-700">Sales History</li>
                </ol>
            </nav>

            <!-- Header Card -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">
                        Sales History
                    </h2>
                    <!-- <div class="flex flex-wrap gap-2">
                        <a href="<?= base_url('Sales') ?>"
                            class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            Today's Remittance
                        </a>
                    </div> -->
                </div>
                <div class="border-t border-gray-200 my-4"></div>

                <!-- Filters -->
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
                    </div>
                    <div class="flex gap-2 sm:justify-end">
                        <button id="btnApplyFilters" type="button"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-filter mr-2"></i>Apply
                        </button>
                        <button id="btnResetFilters" type="button"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </button>
                        <button id="btnExportCsv" type="button"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg border border-green-500 px-4 py-2 text-sm font-medium text-green-600 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-200">
                            <i class="fas fa-file-csv mr-2"></i>Export
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards - Row 1: Main Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4">
                <!-- Total Sales Card -->
                <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-primary">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Total Sales</p>
                            <p class="text-lg sm:text-2xl font-bold text-primary" id="summaryTotalSales">₱0.00</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-primary/10 rounded-full hidden sm:block">
                            <i class="fas fa-peso-sign text-primary text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Orders Card -->
                <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Total Orders</p>
                            <p class="text-lg sm:text-2xl font-bold text-blue-600" id="summaryTotalOrders">0</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-blue-100 rounded-full hidden sm:block">
                            <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Cash Sales Card -->
                <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Cash Sales</p>
                            <p class="text-lg sm:text-2xl font-bold text-green-600" id="summaryCashSales">₱0.00</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-green-100 rounded-full hidden sm:block">
                            <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- GCash Sales Card -->
                <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-blue-400">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">GCash Sales</p>
                            <p class="text-lg sm:text-2xl font-bold text-blue-500" id="summaryGcashSales">₱0.00</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-blue-50 rounded-full hidden sm:block">
                            <i class="fas fa-mobile-alt text-blue-500 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards - Row 2: Category Breakdown -->
            <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-4">
                <!-- Bakery Sales Card -->
                <!-- <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-amber-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Bakery Sales</p>
                            <p class="text-lg sm:text-2xl font-bold text-amber-600" id="summaryBakerySales">₱0.00</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-amber-100 rounded-full hidden sm:block">
                            <i class="fas fa-bread-slice text-amber-600 text-xl"></i>
                        </div>
                    </div> -->
            </div>

            <!-- Coffee/Drinks Sales Card -->
            <!-- <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Coffee/Drinks</p>
                            <p class="text-lg sm:text-2xl font-bold text-orange-600" id="summaryCoffeeSales">₱0.00</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-orange-100 rounded-full hidden sm:block">
                            <i class="fas fa-mug-hot text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div> -->

            <!-- Grocery Sales Card -->
            <!-- <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-emerald-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Grocery Sales</p>
                            <p class="text-lg sm:text-2xl font-bold text-emerald-600" id="summaryGrocerySales">₱0.00</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-emerald-100 rounded-full hidden sm:block">
                            <i class="fas fa-shopping-basket text-emerald-600 text-xl"></i>
                        </div>
                    </div>
                </div> -->
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 sm:mb-0">
            <table id="salesHistoryTable" class="min-w-full text-sm text-left text-gray-500">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Order #</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Date</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Time</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Product</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap text-center">Quantity</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Total</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody id="salesHistoryTableBody">
                    <!-- Data will be populated by JS -->
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-3 mb-20" id="salesHistoryCards">
            <!-- Cards will be populated by JS -->
        </div>
    </div>
    </div>

    <!-- Sales Details Modal -->
    <div id="salesDetailsModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div
            class="relative w-full max-w-2xl mx-auto border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Sales Details</h3>
                        <p class="text-sm text-gray-500" id="detailDate">-</p>
                    </div>
                    <button type="button" id="btnCloseDetailsModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Detail Content -->
                <div class="space-y-4">
                    <!-- Cashier Info -->
                    <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <span class="text-sm text-gray-500">Cashier:</span>
                            <p class="font-semibold text-gray-800" id="detailCashier">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Outlet:</span>
                            <p class="font-semibold text-gray-800" id="detailOutlet">-</p>
                        </div>
                        <!-- <div>
                            <span class="text-sm text-gray-500">Shift:</span>
                            <p class="font-semibold text-gray-800" id="detailShift">-</p>
                        </div> -->
                        <div>
                            <span class="text-sm text-gray-500">Total Orders:</span>
                            <p class="font-semibold text-gray-800" id="detailOrderCount">0</p>
                        </div>
                    </div>

                    <!-- Sales Breakdown -->
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <h4 class="font-semibold text-gray-700 mb-3">Sales Breakdown</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i
                                        class="fas fa-bread-slice text-amber-500 mr-2"></i>Bakery:</span>
                                <span class="font-semibold" id="detailBakery">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i
                                        class="fas fa-mug-hot text-orange-500 mr-2"></i>Coffee/Drinks:</span>
                                <span class="font-semibold" id="detailCoffee">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i
                                        class="fas fa-shopping-basket text-green-500 mr-2"></i>Grocery:</span>
                                <span class="font-semibold" id="detailGrocery">₱0.00</span>
                            </div>
                            <div class="flex justify-between border-t pt-2 mt-2">
                                <span class="font-bold text-gray-800">Total Sales:</span>
                                <span class="font-bold text-primary text-lg" id="detailTotalSales">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <h4 class="font-semibold text-gray-700 mb-3">Payment Methods</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i
                                        class="fas fa-money-bill text-green-500 mr-2"></i>Cash:</span>
                                <span class="font-semibold" id="detailCash">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i
                                        class="fas fa-mobile-alt text-blue-500 mr-2"></i>GCash:</span>
                                <span class="font-semibold" id="detailGcash">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Variance -->
                    <div class="p-4 rounded-lg" id="detailVarianceContainer">
                        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                            <span class="font-bold text-gray-800">Overage/Shortage:</span>
                            <span class="font-bold text-xl" id="detailVariance">₱0.00</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 pb-6 flex gap-2">
                <button type="button" id="btnPrintDetails"
                    class="flex-1 px-4 py-3 text-sm font-medium text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition-all">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
                <button type="button" id="btnCloseModal"
                    class="flex-1 px-4 py-3 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary transition-all">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>

    <script>
        window.BASE_URL = '<?= rtrim(site_url(), '/') ?>/';
        let dataTable = null;
        let salesData = []; // Will be populated from API
        let todaysSales = null; // Today's sales before remittance

        $(document).ready(function () {
            initFilters();
            loadSalesHistory();
            getSummaryDetails();
            initDetailsModal();
        });

        /**
         * Load sales history from API
         */
        function loadSalesHistory() {
            console.log('Loading sales history...');
            const dateFrom = $('#filterDateFrom').val();
            const dateTo = $('#filterDateTo').val();

            // Show loading state
            $('#salesHistoryTableBody').html('<tr><td colspan="10" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-spinner fa-spin text-4xl mb-3"></i><p>Loading sales history...</p></td></tr>');
            $('#salesHistoryCards').html('<div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500"><i class="fas fa-spinner fa-spin text-4xl mb-3"></i><p>Loading...</p></div>');

            // Fetch sales history from API
            $.ajax({
                url: BASE_URL + 'Sales/GetSalesHistory',
                type: 'GET',
                data: { date_from: dateFrom, date_to: dateTo },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        console.log('Sales history loaded:', response.data);
                        renderSalesHistory(response.data);
                    } else {
                        showToast('error', response.message || 'Failed to load sales history');
                        salesData = [];
                        renderSalesHistory([]);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error loading sales history:', error);
                    showToast('error', 'Failed to load sales history');
                    salesData = [];
                    renderSalesHistory([]);
                }
            });
        }

        function getSummaryDetails() {
            console.log('Loading summary details...');
            const dateFrom = $('#filterDateFrom').val();
            const dateTo = $('#filterDateTo').val();
            $.ajax({
                url: BASE_URL + 'Sales/GetSummaryDetails',
                type: 'GET',
                data: { date_from: dateFrom, date_to: dateTo },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        console.log('Summary Details loaded:', response.data);
                        updateSummaryCards(response.data);
                    } else {
                        showToast('error', response.message || 'Failed to load summary details');
                        // Set all to 0
                        updateSummaryCards({
                            total_sales: 0,
                            total_orders: 0,
                            cash_sales: 0,
                            gcash_sales: 0,
                            bakery_sales: 0,
                            coffee_sales: 0,
                            grocery_sales: 0
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error loading summary details:', xhr);
                    showToast('error', 'Failed to load summary details');
                    // Set all to 0
                    updateSummaryCards({
                        total_sales: 0,
                        total_orders: 0,
                        cash_sales: 0,
                        gcash_sales: 0,
                        bakery_sales: 0,
                        coffee_sales: 0,
                        grocery_sales: 0
                    });
                }
            });
        }

        function getTransactionDetails(orderId) {
            console.log('Loading transaction details...');
            $.ajax({
                url: BASE_URL + 'Sales/GetTransactionDetails',
                type: 'POST',
                data: JSON.stringify({ order_id: orderId }),
                contentType: 'application/json',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        console.log('Transaction details loaded:', response.data);
                        openDetailsModal(response.data);
                    } else {
                        showToast('error', response.message || 'Failed to load transaction details');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error loading transaction details:', xhr);
                    showToast('error', 'Failed to load transaction details');
                }
            });
        }

        function initFilters() {
            // Set default date range (today only)
            const today = new Date();

            $('#filterDateTo').val(today.toISOString().split('T')[0]);
            $('#filterDateFrom').val(today.toISOString().split('T')[0]);

            $('#btnApplyFilters').on('click', function () {
                // Reload data from API with new date filters
                loadSalesHistory();
                getSummaryDetails();
                showToast('success', 'Filters applied');
            });

            $('#btnResetFilters').on('click', function () {
                const today = new Date();

                $('#filterDateTo').val(today.toISOString().split('T')[0]);
                $('#filterDateFrom').val(today.toISOString().split('T')[0]);
                loadSalesHistory();
                getSummaryDetails();
                showToast('info', 'Filters reset');
            });

            $('#btnExportCsv').on('click', exportToCsv);
        }

        function renderSalesHistory(history) {
            // Render desktop table
            renderDesktopTable(history);

            // Render mobile cards
            renderMobileCards(history);
        }

        function renderDesktopTable(history) {
            if (dataTable) {
                dataTable.destroy();
                dataTable = null;
            }

            if (!history || history.length === 0) {
                $('#salesHistoryTableBody').html('<tr><td colspan="7" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-receipt text-4xl mb-3"></i><p>No sales history found</p></td></tr>');
                return;
            }

            let html = '';
            history.forEach((sale, index) => {
                const date = new Date(sale.date_created);
                const dateStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                const timeStr = formatTime(sale.time_created);
                const orderNumber = `${sale.date_created}-${sale.order_id}`;

                html += `
            <tr class="border-b hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">${orderNumber}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-700">${dateStr}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-700">${timeStr}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-700">${sale.product_name || 'Unknown'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-gray-700">${sale.quantity_sold}</td>
                <td class="px-6 py-4 whitespace-nowrap text-primary font-bold">${formatCurrency(sale.total_sales || 0)}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <button type="button" class="btn-view-details text-primary py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-secondary hover:bg-gray-200" data-index="${index}">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
        `;
            });

            $('#salesHistoryTableBody').html(html);

            dataTable = new simpleDatatables.DataTable("#salesHistoryTable", {
                searchable: true,
                sortable: true,
                perPage: 10,
                perPageSelect: [5, 10, 25, 50],
                labels: {
                    placeholder: "Search sales...",
                    noRows: "No sales found",
                    info: "Showing {start} to {end} of {rows} records"
                }
            });

            // Re-bind click events after DataTable renders
            $('#salesHistoryTable').on('click', '.btn-view-details', function () {
                const index = $(this).data('index');
                const orderId = history[index].order_id;
                getTransactionDetails(orderId);
            });
        }

        function renderMobileCards(history) {
            if (!history || history.length === 0) {
                $('#salesHistoryCards').html(`
            <div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500">
                <i class="fas fa-receipt text-4xl mb-3"></i>
                <p>No sales history found</p>
            </div>
        `);
                return;
            }

            let html = '';
            history.forEach((sale, index) => {
                const date = new Date(sale.date_created);
                const dateStr = date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
                const timeStr = formatTime(sale.time_created);
                const orderNumber = `${sale.date_created}-${sale.order_id}`;

                html += `
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-300">
                <!-- Card Header -->
                <div class="bg-primary/90 px-4 py-3 border-b border-gray-300">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-receipt text-white"></i>
                            <span class="font-bold text-white">Order #${orderNumber}</span>
                        </div>
                        <span class="text-xs text-gray-200">${dateStr}</span>
                    </div>
                    <div class="flex items-center gap-2 mt-1 text-xs text-gray-200">
                        <i class="fas fa-clock text-xs"></i>
                        <span>${timeStr}</span>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="p-4">
                    <!-- Product Info -->
                    <div class="mb-3">
                        <p class="text-xs text-gray-500 mb-1">Product</p>
                        <p class="font-semibold text-gray-900">${sale.product_name || 'Unknown'}</p>
                    </div>
                    
                    <!-- Quantity & Total -->
                    <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                            <span class="text-gray-600"><i class="fas fa-boxes text-blue-500 mr-1"></i>Quantity</span>
                            <span class="font-semibold text-gray-900">${sale.quantity_sold}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-green-50 rounded">
                            <span class="text-gray-600"><i class="fas fa-peso-sign text-green-500 mr-1"></i>Total</span>
                            <span class="font-semibold text-green-600">${formatCurrency(sale.total_sales)}</span>
                        </div>
                    </div>
                    
                    <!-- Action Button -->
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div>
                            <p class="text-xs text-gray-500">Total Amount</p>
                            <p class="text-xl font-bold text-primary">${formatCurrency(sale.total_sales)}</p>
                        </div>
                        <button type="button" class="btn-view-details-mobile px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary transition-all" data-index="${index}">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                    </div>
                </div>
            </div>
        `;
            });

            $('#salesHistoryCards').html(html);

            // Bind click events for mobile cards
            $('.btn-view-details-mobile').on('click', function () {
                const index = $(this).data('index');
                const orderId = history[index].order_id;
                getTransactionDetails(orderId);
            });
        }

        function updateSummaryCards(data) {
            // Update main stats
            $('#summaryTotalSales').text(formatCurrency(data.total_sales || 0));
            $('#summaryTotalOrders').text(data.total_orders || 0);
            $('#summaryCashSales').text(formatCurrency(data.cash_sales || 0));
            $('#summaryGcashSales').text(formatCurrency(data.gcash_sales || 0));

            // Update category breakdown (if you uncomment those cards)
            $('#summaryBakerySales').text(formatCurrency(data.bakery_sales || 0));
            $('#summaryCoffeeSales').text(formatCurrency(data.coffee_sales || 0));
            $('#summaryGrocerySales').text(formatCurrency(data.grocery_sales || 0));
        }

        function initDetailsModal() {
            $('#btnCloseDetailsModal, #btnCloseModal').on('click', () => $('#salesDetailsModal').addClass('hidden'));
            $('#salesDetailsModal').on('click', e => { if (e.target === e.currentTarget) $('#salesDetailsModal').addClass('hidden'); });

            $('#btnPrintDetails').on('click', function () {
                const content = $('#salesDetailsModal .p-6').first().clone();
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Sales Details</title>
                        <script src="https://cdn.tailwindcss.com"><\/script>
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
                        <style>
                            @media print { body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
                            body { font-family: Arial, sans-serif; padding: 20px; }
                        </style>
                    </head>
                    <body>
                        ${content.html()}
                        <script>setTimeout(() => { window.print(); window.close(); }, 500);<\/script>
                    </body>
                    </html>
                `);
                printWindow.document.close();
            });
        }

        function openDetailsModal(order) {
            if (!order) return;

            const date = new Date(order.date_created);
            const dateStr = date.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
            const timeStr = formatTime(order.time_created);
            const orderNumber = `${order.date_created}-${order.order_id}`;

            $('#detailDate').text(`${dateStr} at ${timeStr}`);
            $('#detailCashier').text(order.cashier_name || '-');
            $('#detailOutlet').text(order.outlet_name || 'DECA SENTRIO');
            $('#detailShift').text('-');
            $('#detailOrderCount').text('Order #' + orderNumber);

            // Product details (grouped products)
            $('#detailBakery').text(`Products: ${order.product_name || 'Unknown'}`);
            $('#detailCoffee').text(`Total Quantity: ${order.quantity_sold || 0}`);
            $('#detailGrocery').text(`Items: ${(order.product_name || '').split(', ').length}`);
            $('#detailTotalSales').text(formatCurrency(order.total_sales || 0));

            // Payment methods
            $('#detailCash').text(formatCurrency(order.cash_total || 0));
            $('#detailGcash').text(formatCurrency(order.gcash_total || 0));

            // Hide variance section
            $('#detailVarianceContainer').hide();

            $('#salesDetailsModal').removeClass('hidden');
        }

        function exportToCsv() {
            if (!salesData || salesData.length === 0) {
                showToast('warning', 'No data to export');
                return;
            }

            const headers = ['Date', 'Shift', 'Cashier', 'Bakery Sales', 'Coffee Sales', 'GCash', 'Cash', 'Total Sales', 'Variance'];
            const rows = salesData.map(sale => [
                sale.date,
                (sale.shift_start || '') + ' - ' + (sale.shift_end || ''),
                sale.cashier_name || '',
                sale.bakery_sales || 0,
                sale.coffee_sales || 0,
                sale.gcash_total || 0,
                sale.cash_total || 0,
                sale.total_sales || 0,
                sale.variance || 0
            ]);

            let csv = headers.join(',') + '\n';
            rows.forEach(row => {
                csv += row.map(cell => `"${cell}"`).join(',') + '\n';
            });

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `sales_history_${$('#filterDateFrom').val()}_to_${$('#filterDateTo').val()}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            showToast('success', 'Sales history exported successfully');
        }

        function formatCurrency(amount) {
            return '₱' + parseFloat(amount).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function formatTime(timeStr) {
            if (!timeStr) return '--:--';
            const [hours, minutes] = timeStr.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes || '00'} ${ampm}`;
        }
    </script>
</body>