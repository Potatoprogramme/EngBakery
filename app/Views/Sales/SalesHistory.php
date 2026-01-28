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
                    <div class="flex flex-wrap gap-2">
                        <a href="<?= base_url('Sales') ?>" 
                            class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            Today's Remittance
                        </a>
                    </div>
                </div>
                <div class="border-t border-gray-200 my-4"></div>
                
                <!-- Filters -->
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 flex-1">
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <label for="filterDateFrom" class="text-sm text-gray-600 whitespace-nowrap w-12 sm:w-auto">From:</label>
                            <input type="date" id="filterDateFrom" class="flex-1 sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <label for="filterDateTo" class="text-sm text-gray-600 whitespace-nowrap w-12 sm:w-auto">To:</label>
                            <input type="date" id="filterDateTo" class="flex-1 sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                        </div>
                    </div>
                    <div class="flex gap-2 sm:justify-end">
                        <button id="btnApplyFilters" type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-filter mr-2"></i>Apply
                        </button>
                        <button id="btnResetFilters" type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </button>
                        <button id="btnExportCsv" type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg border border-green-500 px-4 py-2 text-sm font-medium text-green-600 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-200">
                            <i class="fas fa-file-csv mr-2"></i>Export
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4">
                <!-- Total Sales Card -->
                <div class="p-3 sm:p-4 bg-white rounded-lg shadow-md border-l-4 border-primary">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Total Sales</p>
                            <p class="text-lg sm:text-2xl font-bold text-primary" id="summaryTotalSales">₱52,450.00</p>
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
                            <p class="text-lg sm:text-2xl font-bold text-blue-600" id="summaryTotalOrders">847</p>
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
                            <p class="text-lg sm:text-2xl font-bold text-green-600" id="summaryCashSales">₱41,725.00</p>
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
                            <p class="text-lg sm:text-2xl font-bold text-blue-500" id="summaryGcashSales">₱10,725.00</p>
                        </div>
                        <div class="p-2 sm:p-3 bg-blue-50 rounded-full hidden sm:block">
                            <i class="fas fa-mobile-alt text-blue-500 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 sm:mb-0">
                <table id="salesHistoryTable" class="min-w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Date</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Shift</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Bakery Sales</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Coffee Sales</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">GCash</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Cash</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Total Sales</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Variance</th>
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
    <div id="salesDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-2xl mx-auto border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
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
                        <div>
                            <span class="text-sm text-gray-500">Shift:</span>
                            <p class="font-semibold text-gray-800" id="detailShift">-</p>
                        </div>
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
                                <span class="text-gray-600"><i class="fas fa-bread-slice text-amber-500 mr-2"></i>Bakery:</span>
                                <span class="font-semibold" id="detailBakery">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-mug-hot text-orange-500 mr-2"></i>Coffee/Drinks:</span>
                                <span class="font-semibold" id="detailCoffee">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-shopping-basket text-green-500 mr-2"></i>Grocery:</span>
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
                                <span class="text-gray-600"><i class="fas fa-money-bill text-green-500 mr-2"></i>Cash:</span>
                                <span class="font-semibold" id="detailCash">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-mobile-alt text-blue-500 mr-2"></i>GCash:</span>
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
                <button type="button" id="btnPrintDetails" class="flex-1 px-4 py-3 text-sm font-medium text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition-all">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
                <button type="button" id="btnCloseModal" class="flex-1 px-4 py-3 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary transition-all">
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

        // Sample Data
        const salesData = [
            {
                date: '2026-01-26',
                shift_start: '06:00',
                shift_end: '14:00',
                cashier_name: 'Raymond De. Cayao',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                bakery_sales: 4730,
                coffee_sales: 415,
                grocery_sales: 100,
                gcash_total: 276,
                cash_total: 4473,
                total_sales: 5245,
                order_count: 127,
                variance: 94,
                bills: { '1000': 2, '500': 3, '100': 10, '50': 1, '10': 2, '1': 3 },
                total_cash_enclosed: 4473
            },
            {
                date: '2026-01-25',
                shift_start: '06:00',
                shift_end: '14:00',
                cashier_name: 'Maria Santos',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                bakery_sales: 5120,
                coffee_sales: 380,
                grocery_sales: 150,
                gcash_total: 450,
                cash_total: 5200,
                total_sales: 5650,
                order_count: 142,
                variance: 0,
                bills: { '1000': 3, '500': 2, '200': 1, '100': 15, '50': 2, '20': 5 },
                total_cash_enclosed: 5200
            },
            {
                date: '2026-01-24',
                shift_start: '14:00',
                shift_end: '22:00',
                cashier_name: 'Juan Dela Cruz',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                bakery_sales: 3890,
                coffee_sales: 520,
                grocery_sales: 80,
                gcash_total: 890,
                cash_total: 3600,
                total_sales: 4490,
                order_count: 98,
                variance: -50,
                bills: { '1000': 2, '500': 2, '100': 8, '50': 4, '20': 5 },
                total_cash_enclosed: 3550
            },
            {
                date: '2026-01-23',
                shift_start: '06:00',
                shift_end: '14:00',
                cashier_name: 'Raymond De. Cayao',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                bakery_sales: 6200,
                coffee_sales: 650,
                grocery_sales: 200,
                gcash_total: 1250,
                cash_total: 5800,
                total_sales: 7050,
                order_count: 168,
                variance: 25,
                bills: { '1000': 4, '500': 2, '100': 12, '50': 6, '20': 2, '10': 3 },
                total_cash_enclosed: 5825
            },
            {
                date: '2026-01-22',
                shift_start: '06:00',
                shift_end: '14:00',
                cashier_name: 'Ana Reyes',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                bakery_sales: 4560,
                coffee_sales: 390,
                grocery_sales: 120,
                gcash_total: 570,
                cash_total: 4500,
                total_sales: 5070,
                order_count: 115,
                variance: 0,
                bills: { '1000': 3, '500': 2, '100': 10, '50': 2, '20': 2, '5': 4 },
                total_cash_enclosed: 4500
            },
            {
                date: '2026-01-21',
                shift_start: '14:00',
                shift_end: '22:00',
                cashier_name: 'Pedro Garcia',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                bakery_sales: 5890,
                coffee_sales: 720,
                grocery_sales: 180,
                gcash_total: 1890,
                cash_total: 4900,
                total_sales: 6790,
                order_count: 156,
                variance: -15,
                bills: { '1000': 3, '500': 3, '100': 6, '50': 4, '20': 3, '10': 2, '5': 1 },
                total_cash_enclosed: 4885
            },
            {
                date: '2026-01-20',
                shift_start: '06:00',
                shift_end: '14:00',
                cashier_name: 'Maria Santos',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                bakery_sales: 7250,
                coffee_sales: 850,
                grocery_sales: 250,
                gcash_total: 2350,
                cash_total: 6000,
                total_sales: 8350,
                order_count: 195,
                variance: 50,
                bills: { '1000': 5, '500': 1, '100': 8, '50': 4, '20': 1, '10': 5 },
                total_cash_enclosed: 6050
            }
        ];

        $(document).ready(function() {
            initFilters();
            renderSalesHistory(salesData);
            initDetailsModal();
        });

        function initFilters() {
            // Set default date range (last 30 days)
            const today = new Date();
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(today.getDate() - 30);
            
            $('#filterDateTo').val(today.toISOString().split('T')[0]);
            $('#filterDateFrom').val(thirtyDaysAgo.toISOString().split('T')[0]);

            $('#btnApplyFilters').on('click', function() {
                // Filter sample data by date range
                const dateFrom = new Date($('#filterDateFrom').val());
                const dateTo = new Date($('#filterDateTo').val());
                
                const filtered = salesData.filter(sale => {
                    const saleDate = new Date(sale.date);
                    return saleDate >= dateFrom && saleDate <= dateTo;
                });
                
                renderSalesHistory(filtered);
                showToast('success', 'Filters applied');
            });

            $('#btnResetFilters').on('click', function() {
                const today = new Date();
                const thirtyDaysAgo = new Date();
                thirtyDaysAgo.setDate(today.getDate() - 30);
                
                $('#filterDateTo').val(today.toISOString().split('T')[0]);
                $('#filterDateFrom').val(thirtyDaysAgo.toISOString().split('T')[0]);
                renderSalesHistory(salesData);
                showToast('info', 'Filters reset');
            });

            $('#btnExportCsv').on('click', exportToCsv);
        }

        function renderSalesHistory(history) {
            // Update summary cards
            updateSummaryCards(history);
            
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
                $('#salesHistoryTableBody').html('<tr><td colspan="9" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-receipt text-4xl mb-3"></i><p>No sales history found</p></td></tr>');
                return;
            }

            let html = '';
            history.forEach((sale, index) => {
                const date = new Date(sale.date);
                const dateStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                
                const varianceClass = parseFloat(sale.variance) >= 0 ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100';
                const varianceText = parseFloat(sale.variance) >= 0 
                    ? '+' + formatCurrency(sale.variance) 
                    : '-' + formatCurrency(Math.abs(sale.variance));

                html += `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">${dateStr}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">${formatTime(sale.shift_start)} - ${formatTime(sale.shift_end)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-amber-600 font-semibold">${formatCurrency(sale.bakery_sales || 0)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-orange-600 font-semibold">${formatCurrency(sale.coffee_sales || 0)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-blue-600 font-semibold">${formatCurrency(sale.gcash_total || 0)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-green-600 font-semibold">${formatCurrency(sale.cash_total || 0)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-primary font-bold">${formatCurrency(sale.total_sales || 0)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${varianceClass}">${varianceText}</span>
                        </td>
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
            $('#salesHistoryTable').on('click', '.btn-view-details', function() {
                const index = $(this).data('index');
                openDetailsModal(history[index]);
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
                const date = new Date(sale.date);
                const dateStr = date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
                
                const varianceClass = parseFloat(sale.variance) >= 0 ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100';
                const varianceIcon = parseFloat(sale.variance) >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                const varianceText = parseFloat(sale.variance) >= 0 
                    ? '+' + formatCurrency(sale.variance)
                    : '-' + formatCurrency(Math.abs(sale.variance));

                html += `
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-300">
                        <!-- Card Header -->
                        <div class="bg-primary/90 px-4 py-3 border-b border-gray-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar text-white"></i>
                                    <span class="font-bold text-white">${dateStr}</span>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium ${varianceClass}">
                                    <i class="fas ${varianceIcon} mr-1"></i>${varianceText}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 mt-1 text-xs text-gray-300">
                                <i class="fas fa-clock text-xs"></i>
                                <span>${formatTime(sale.shift_start)} - ${formatTime(sale.shift_end)}</span>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="p-4">
                            <!-- Sales by Category -->
                            <div class="grid grid-cols-3 gap-2 mb-3">
                                <div class="text-center p-2 bg-amber-50 rounded-lg">
                                    <i class="fas fa-bread-slice text-amber-500 text-sm"></i>
                                    <p class="text-xs text-gray-500 mt-1">Bakery</p>
                                    <p class="font-bold text-amber-600 text-sm">${formatCurrency(sale.bakery_sales)}</p>
                                </div>
                                <div class="text-center p-2 bg-orange-50 rounded-lg">
                                    <i class="fas fa-mug-hot text-orange-500 text-sm"></i>
                                    <p class="text-xs text-gray-500 mt-1">Coffee</p>
                                    <p class="font-bold text-orange-600 text-sm">${formatCurrency(sale.coffee_sales)}</p>
                                </div>
                                <div class="text-center p-2 bg-green-50 rounded-lg">
                                    <i class="fas fa-shopping-basket text-green-500 text-sm"></i>
                                    <p class="text-xs text-gray-500 mt-1">Grocery</p>
                                    <p class="font-bold text-green-600 text-sm">${formatCurrency(sale.grocery_sales)}</p>
                                </div>
                            </div>
                            
                            <!-- Payment Methods -->
                            <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-gray-600"><i class="fas fa-money-bill text-green-500 mr-1"></i>Cash</span>
                                    <span class="font-semibold text-green-600">${formatCurrency(sale.cash_total)}</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-gray-600"><i class="fas fa-mobile-alt text-blue-500 mr-1"></i>GCash</span>
                                    <span class="font-semibold text-blue-600">${formatCurrency(sale.gcash_total)}</span>
                                </div>
                            </div>
                            
                            <!-- Total & Action -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <div>
                                    <p class="text-xs text-gray-500">Total Sales</p>
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
            $('.btn-view-details-mobile').on('click', function() {
                const index = $(this).data('index');
                openDetailsModal(history[index]);
            });
        }

        function updateSummaryCards(history) {
            let totalSales = 0, totalOrders = 0, cashSales = 0, gcashSales = 0;
            
            history.forEach(sale => {
                totalSales += sale.total_sales || 0;
                totalOrders += sale.order_count || 0;
                cashSales += sale.cash_total || 0;
                gcashSales += sale.gcash_total || 0;
            });

            $('#summaryTotalSales').text(formatCurrency(totalSales));
            $('#summaryTotalOrders').text(totalOrders);
            $('#summaryCashSales').text(formatCurrency(cashSales));
            $('#summaryGcashSales').text(formatCurrency(gcashSales));
        }

        function initDetailsModal() {
            $('#btnCloseDetailsModal, #btnCloseModal').on('click', () => $('#salesDetailsModal').addClass('hidden'));
            $('#salesDetailsModal').on('click', e => { if (e.target === e.currentTarget) $('#salesDetailsModal').addClass('hidden'); });

            $('#btnPrintDetails').on('click', function() {
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

        function openDetailsModal(sale) {
            if (!sale) return;

            const date = new Date(sale.date);
            const dateStr = date.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });

            $('#detailDate').text(dateStr);
            $('#detailCashier').text(sale.cashier_name || '-');
            $('#detailOutlet').text(sale.outlet_name || 'E n\' G Bakery');
            $('#detailShift').text(formatTime(sale.shift_start) + ' - ' + formatTime(sale.shift_end));
            $('#detailOrderCount').text(sale.order_count || 0);

            $('#detailBakery').text(formatCurrency(sale.bakery_sales || 0));
            $('#detailCoffee').text(formatCurrency(sale.coffee_sales || 0));
            $('#detailGrocery').text(formatCurrency(sale.grocery_sales || 0));
            $('#detailTotalSales').text(formatCurrency(sale.total_sales || 0));

            $('#detailCash').text(formatCurrency(sale.cash_total || 0));
            $('#detailGcash').text(formatCurrency(sale.gcash_total || 0));
            $('#detailTotalEnclosed').text(formatCurrency(sale.total_cash_enclosed || 0));
            
            // Variance
            const variance = parseFloat(sale.variance) || 0;
            const container = $('#detailVarianceContainer');
            const varianceEl = $('#detailVariance');
            
            if (variance > 0) {
                container.removeClass('bg-red-100').addClass('bg-green-100');
                varianceEl.removeClass('text-red-600').addClass('text-green-600');
                varianceEl.text('+' + formatCurrency(variance) + ' (Over)');
            } else if (variance < 0) {
                container.removeClass('bg-green-100').addClass('bg-red-100');
                varianceEl.removeClass('text-green-600').addClass('text-red-600');
                varianceEl.text('-' + formatCurrency(Math.abs(variance)) + ' (Short)');
            } else {
                container.removeClass('bg-red-100 bg-green-100').addClass('bg-gray-100');
                varianceEl.removeClass('text-red-600 text-green-600').addClass('text-gray-800');
                varianceEl.text('₱0.00 (Balanced)');
            }

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
