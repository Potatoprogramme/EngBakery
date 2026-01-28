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
                    <li class="text-gray-700">Remittance History</li>
                </ol>
            </nav>
            
            <!-- Header Card -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">
                        Remittance History
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
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <label for="filterCashier" class="text-sm text-gray-600 whitespace-nowrap w-12 sm:w-auto">Cashier:</label>
                            <select id="filterCashier" class="flex-1 sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                                <option value="">All Cashiers</option>
                            </select>
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

            <!-- Desktop Table View -->
            <div class="hidden lg:block p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 sm:mb-0">
                <table id="remittanceHistoryTable" class="min-w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Date</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Cashier</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Cash On Hand</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">GCash</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Cash Out</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Total Sales</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Total Remitted</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Variance</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody id="remittanceHistoryTableBody">
                        <!-- Data will be populated by JS -->
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden space-y-3 mb-20" id="remittanceHistoryCards">
                <!-- Cards will be populated by JS -->
            </div>
        </div>
    </div>

    <!-- Remittance Details Modal -->
    <div id="remittanceDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-2xl mx-auto border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Remittance Details</h3>
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
                            <span class="text-sm text-gray-500">Email:</span>
                            <p class="font-semibold text-gray-800" id="detailCashierEmail">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Outlet:</span>
                            <p class="font-semibold text-gray-800" id="detailOutlet">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Shift:</span>
                            <p class="font-semibold text-gray-800" id="detailShift">-</p>
                        </div>
                        <div class="col-span-2">
                            <span class="text-sm text-gray-500">Submitted:</span>
                            <p class="font-semibold text-gray-800" id="detailSubmittedAt">-</p>
                        </div>
                    </div>

                    <!-- Remittance Breakdown -->
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <h4 class="font-semibold text-gray-700 mb-3">Remittance Breakdown</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-money-bill text-green-500 mr-2"></i>Cash On Hand:</span>
                                <span class="font-semibold" id="detailCashOnHand">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-mobile-alt text-blue-500 mr-2"></i>GCash:</span>
                                <span class="font-semibold" id="detailGcash">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-hand-holding-usd text-gray-500 mr-2"></i>Cash Out:</span>
                                <span class="font-semibold text-red-600" id="detailCashOut">-₱0.00</span>
                            </div>
                            <div class="flex justify-between border-t pt-2 mt-2">
                                <span class="font-bold text-gray-800">Total Remitted:</span>
                                <span class="font-bold text-primary text-lg" id="detailTotalRemitted">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sales vs Remittance -->
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <h4 class="font-semibold text-gray-700 mb-3">Sales vs Remittance</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-chart-line text-primary mr-2"></i>Total Sales:</span>
                                <span class="font-semibold" id="detailTotalSales">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-hand-holding-usd text-green-500 mr-2"></i>Total Remitted:</span>
                                <span class="font-semibold" id="detailTotalRemittedCompare">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Cash Out Reason -->
                    <div class="p-4 border border-gray-200 rounded-lg" id="cashOutReasonContainer" style="display: none;">
                        <h4 class="font-semibold text-gray-700 mb-2">Cash Out Reason</h4>
                        <p class="text-gray-600" id="detailCashOutReason">-</p>
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

        // Sample Data for Remittance History
        const remittanceData = [
            {
                id: 1,
                date: '2026-01-28',
                cashier_name: 'Ana Reyes',
                cashier_email: 'ana.reyes@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '06:00',
                shift_end: '14:00',
                cash_on_hand: 5750,
                gcash_total: 1320,
                cash_out_amount: 150,
                cash_out_reason: 'Delivery fee payment',
                total_sales: 6920,
                total_remitted: 6920,
                variance: 0,
                submitted_at: '2026-01-28 14:35:00'
            },
            {
                id: 2,
                date: '2026-01-27',
                cashier_name: 'Raymond De. Cayao',
                cashier_email: 'raymond.cayao@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '06:00',
                shift_end: '14:00',
                cash_on_hand: 4500,
                gcash_total: 276,
                cash_out_amount: 200,
                cash_out_reason: 'Change fund for tomorrow',
                total_sales: 5245,
                total_remitted: 4576,
                variance: -469,
                submitted_at: '2026-01-27 14:30:00'
            },
            {
                id: 3,
                date: '2026-01-26',
                cashier_name: 'Maria Santos',
                cashier_email: 'maria.santos@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '14:00',
                shift_end: '22:00',
                cash_on_hand: 3890,
                gcash_total: 1560,
                cash_out_amount: 0,
                cash_out_reason: '',
                total_sales: 5450,
                total_remitted: 5450,
                variance: 0,
                submitted_at: '2026-01-26 22:15:00'
            },
            {
                id: 4,
                date: '2026-01-26',
                cashier_name: 'Pedro Garcia',
                cashier_email: 'pedro.garcia@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '06:00',
                shift_end: '14:00',
                cash_on_hand: 5200,
                gcash_total: 450,
                cash_out_amount: 0,
                cash_out_reason: '',
                total_sales: 5650,
                total_remitted: 5650,
                variance: 0,
                submitted_at: '2026-01-26 14:15:00'
            },
            {
                id: 5,
                date: '2026-01-25',
                cashier_name: 'Juan Dela Cruz',
                cashier_email: 'juan.delacruz@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '14:00',
                shift_end: '22:00',
                cash_on_hand: 3600,
                gcash_total: 890,
                cash_out_amount: 50,
                cash_out_reason: 'Emergency supplies',
                total_sales: 4490,
                total_remitted: 4440,
                variance: -50,
                submitted_at: '2026-01-25 22:05:00'
            },
            {
                id: 6,
                date: '2026-01-25',
                cashier_name: 'Lisa Mae Cruz',
                cashier_email: 'lisa.cruz@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '06:00',
                shift_end: '14:00',
                cash_on_hand: 4890,
                gcash_total: 760,
                cash_out_amount: 75,
                cash_out_reason: 'Office supplies',
                total_sales: 5575,
                total_remitted: 5575,
                variance: 0,
                submitted_at: '2026-01-25 14:12:00'
            },
            {
                id: 7,
                date: '2026-01-24',
                cashier_name: 'Raymond De. Cayao',
                cashier_email: 'raymond.cayao@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '06:00',
                shift_end: '14:00',
                cash_on_hand: 5850,
                gcash_total: 1250,
                cash_out_amount: 50,
                cash_out_reason: 'Minor expense',
                total_sales: 7050,
                total_remitted: 7050,
                variance: 0,
                submitted_at: '2026-01-24 14:20:00'
            },
            {
                id: 8,
                date: '2026-01-23',
                cashier_name: 'Ana Reyes',
                cashier_email: 'ana.reyes@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '14:00',
                shift_end: '22:00',
                cash_on_hand: 3250,
                gcash_total: 1820,
                cash_out_amount: 0,
                cash_out_reason: '',
                total_sales: 5070,
                total_remitted: 5070,
                variance: 0,
                submitted_at: '2026-01-23 22:08:00'
            },
            {
                id: 9,
                date: '2026-01-23',
                cashier_name: 'Maria Santos',
                cashier_email: 'maria.santos@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '06:00',
                shift_end: '14:00',
                cash_on_hand: 4550,
                gcash_total: 570,
                cash_out_amount: 0,
                cash_out_reason: '',
                total_sales: 5070,
                total_remitted: 5120,
                variance: 50,
                submitted_at: '2026-01-23 14:10:00'
            },
            {
                id: 10,
                date: '2026-01-22',
                cashier_name: 'Pedro Garcia',
                cashier_email: 'pedro.garcia@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '14:00',
                shift_end: '22:00',
                cash_on_hand: 4900,
                gcash_total: 1890,
                cash_out_amount: 0,
                cash_out_reason: '',
                total_sales: 6790,
                total_remitted: 6790,
                variance: 0,
                submitted_at: '2026-01-22 22:00:00'
            },
            {
                id: 11,
                date: '2026-01-22',
                cashier_name: 'Juan Dela Cruz',
                cashier_email: 'juan.delacruz@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '06:00',
                shift_end: '14:00',
                cash_on_hand: 4320,
                gcash_total: 980,
                cash_out_amount: 120,
                cash_out_reason: 'Cleaning supplies',
                total_sales: 5180,
                total_remitted: 5180,
                variance: 0,
                submitted_at: '2026-01-22 14:18:00'
            },
            {
                id: 12,
                date: '2026-01-21',
                cashier_name: 'Maria Santos',
                cashier_email: 'maria.santos@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '06:00',
                shift_end: '14:00',
                cash_on_hand: 6100,
                gcash_total: 2350,
                cash_out_amount: 100,
                cash_out_reason: 'Supplies purchase',
                total_sales: 8350,
                total_remitted: 8350,
                variance: 0,
                submitted_at: '2026-01-21 14:25:00'
            },
            {
                id: 13,
                date: '2026-01-20',
                cashier_name: 'Lisa Mae Cruz',
                cashier_email: 'lisa.cruz@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '14:00',
                shift_end: '22:00',
                cash_on_hand: 3890,
                gcash_total: 1240,
                cash_out_amount: 25,
                cash_out_reason: 'Taxi fare',
                total_sales: 5105,
                total_remitted: 5105,
                variance: 0,
                submitted_at: '2026-01-20 22:10:00'
            },
            {
                id: 14,
                date: '2026-01-20',
                cashier_name: 'Raymond De. Cayao',
                cashier_email: 'raymond.cayao@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '06:00',
                shift_end: '14:00',
                cash_on_hand: 5680,
                gcash_total: 670,
                cash_out_amount: 0,
                cash_out_reason: '',
                total_sales: 6350,
                total_remitted: 6350,
                variance: 0,
                submitted_at: '2026-01-20 14:05:00'
            },
            {
                id: 15,
                date: '2026-01-19',
                cashier_name: 'Ana Reyes',
                cashier_email: 'ana.reyes@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '14:00',
                shift_end: '22:00',
                cash_on_hand: 4120,
                gcash_total: 1580,
                cash_out_amount: 80,
                cash_out_reason: 'Maintenance fee',
                total_sales: 5620,
                total_remitted: 5620,
                variance: 0,
                submitted_at: '2026-01-19 22:15:00'
            },
            {
                id: 16,
                date: '2026-01-19',
                cashier_name: 'Pedro Garcia',
                cashier_email: 'pedro.garcia@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '06:00',
                shift_end: '14:00',
                cash_on_hand: 4750,
                gcash_total: 825,
                cash_out_amount: 0,
                cash_out_reason: '',
                total_sales: 5575,
                total_remitted: 5575,
                variance: 0,
                submitted_at: '2026-01-19 14:22:00'
            },
            {
                id: 17,
                date: '2026-01-18',
                cashier_name: 'Maria Santos',
                cashier_email: 'maria.santos@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '06:00',
                shift_end: '14:00',
                cash_on_hand: 5320,
                gcash_total: 1180,
                cash_out_amount: 150,
                cash_out_reason: 'Ingredient purchase',
                total_sales: 6350,
                total_remitted: 6350,
                variance: 0,
                submitted_at: '2026-01-18 14:28:00'
            },
            {
                id: 18,
                date: '2026-01-17',
                cashier_name: 'Juan Dela Cruz',
                cashier_email: 'juan.delacruz@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '14:00',
                shift_end: '22:00',
                cash_on_hand: 3590,
                gcash_total: 960,
                cash_out_amount: 30,
                cash_out_reason: 'Delivery charge',
                total_sales: 4520,
                total_remitted: 4520,
                variance: 0,
                submitted_at: '2026-01-17 22:12:00'
            },
            {
                id: 19,
                date: '2026-01-16',
                cashier_name: 'Lisa Mae Cruz',
                cashier_email: 'lisa.cruz@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '06:00',
                shift_end: '14:00',
                cash_on_hand: 4890,
                gcash_total: 1340,
                cash_out_amount: 75,
                cash_out_reason: 'Equipment repair',
                total_sales: 6155,
                total_remitted: 6155,
                variance: 0,
                submitted_at: '2026-01-16 14:35:00'
            },
            {
                id: 20,
                date: '2026-01-15',
                cashier_name: 'Raymond De. Cayao',
                cashier_email: 'raymond.cayao@engbakery.com',
                outlet_name: 'E n\' G Bakery - Deca Seutrio',
                shift_start: '06:00',
                shift_end: '14:00',
                cash_on_hand: 5480,
                gcash_total: 720,
                cash_out_amount: 0,
                cash_out_reason: '',
                total_sales: 6200,
                total_remitted: 6200,
                variance: 0,
                submitted_at: '2026-01-15 14:15:00'
            }
        ];

        $(document).ready(function() {
            initFilters();
            renderRemittanceHistory(remittanceData);
            initDetailsModal();
        });

        function initFilters() {
            // Set default date range (last 30 days)
            const today = new Date();
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(today.getDate() - 30);
            
            $('#filterDateTo').val(today.toISOString().split('T')[0]);
            $('#filterDateFrom').val(thirtyDaysAgo.toISOString().split('T')[0]);

            // Populate cashier filter
            const cashiers = [...new Set(remittanceData.map(r => r.cashier_name))];
            cashiers.forEach(cashier => {
                $('#filterCashier').append(`<option value="${cashier}">${cashier}</option>`);
            });

            $('#btnApplyFilters').on('click', function() {
                const dateFrom = new Date($('#filterDateFrom').val());
                const dateTo = new Date($('#filterDateTo').val());
                const cashier = $('#filterCashier').val();
                
                const filtered = remittanceData.filter(remittance => {
                    const remittanceDate = new Date(remittance.date);
                    const dateMatch = remittanceDate >= dateFrom && remittanceDate <= dateTo;
                    const cashierMatch = !cashier || remittance.cashier_name === cashier;
                    return dateMatch && cashierMatch;
                });
                
                renderRemittanceHistory(filtered);
                showToast('success', 'Filters applied');
            });

            $('#btnResetFilters').on('click', function() {
                const today = new Date();
                const thirtyDaysAgo = new Date();
                thirtyDaysAgo.setDate(today.getDate() - 30);
                
                $('#filterDateTo').val(today.toISOString().split('T')[0]);
                $('#filterDateFrom').val(thirtyDaysAgo.toISOString().split('T')[0]);
                $('#filterCashier').val('');
                renderRemittanceHistory(remittanceData);
                showToast('info', 'Filters reset');
            });

            $('#btnExportCsv').on('click', exportToCsv);
        }

        function renderRemittanceHistory(history) {
            updateSummaryCards(history);
            renderDesktopTable(history);
            renderMobileCards(history);
        }

        function renderDesktopTable(history) {
            if (dataTable) {
                dataTable.destroy();
                dataTable = null;
            }

            if (!history || history.length === 0) {
                $('#remittanceHistoryTableBody').html('<tr><td colspan="9" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-file-invoice-dollar text-4xl mb-3"></i><p>No remittance history found</p></td></tr>');
                return;
            }

            let html = '';
            history.forEach((remittance, index) => {
                const date = new Date(remittance.date);
                const dateStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                
                const varianceClass = parseFloat(remittance.variance) >= 0 ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100';
                const varianceText = parseFloat(remittance.variance) >= 0 
                    ? '+' + formatCurrency(remittance.variance) 
                    : '-' + formatCurrency(Math.abs(remittance.variance));

                html += `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">${dateStr}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">${remittance.cashier_name || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-green-600 font-semibold">${formatCurrency(remittance.cash_on_hand || 0)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-blue-600 font-semibold">${formatCurrency(remittance.gcash_total || 0)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-red-600 font-semibold">${remittance.cash_out_amount > 0 ? '-' + formatCurrency(remittance.cash_out_amount) : '₱0.00'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-semibold">${formatCurrency(remittance.total_sales || 0)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-primary font-bold">${formatCurrency(remittance.total_remitted || 0)}</td>
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

            $('#remittanceHistoryTableBody').html(html);

            dataTable = new simpleDatatables.DataTable("#remittanceHistoryTable", {
                searchable: true,
                sortable: true,
                perPage: 10,
                perPageSelect: [5, 10, 25, 50],
                labels: {
                    placeholder: "Search remittances...",
                    noRows: "No remittances found",
                    info: "Showing {start} to {end} of {rows} records"
                }
            });

            // Re-bind click events after DataTable renders
            $('#remittanceHistoryTable').on('click', '.btn-view-details', function() {
                const index = $(this).data('index');
                openDetailsModal(history[index]);
            });
        }

        function renderMobileCards(history) {
            if (!history || history.length === 0) {
                $('#remittanceHistoryCards').html(`
                    <div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500">
                        <i class="fas fa-file-invoice-dollar text-4xl mb-3"></i>
                        <p>No remittance history found</p>
                    </div>
                `);
                return;
            }

            let html = '';
            history.forEach((remittance, index) => {
                const date = new Date(remittance.date);
                const dateStr = date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
                
                const varianceClass = parseFloat(remittance.variance) >= 0 ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100';
                const varianceIcon = parseFloat(remittance.variance) >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                const varianceText = parseFloat(remittance.variance) >= 0 
                    ? '+' + formatCurrency(remittance.variance)
                    : '-' + formatCurrency(Math.abs(remittance.variance));

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
                                <i class="fas fa-user text-xs"></i>
                                <span>${remittance.cashier_name}</span>
                                <span class="text-gray-300">|</span>
                                <i class="fas fa-clock text-xs"></i>
                                <span>${formatTime(remittance.shift_start)} - ${formatTime(remittance.shift_end)}</span>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="p-4">
                            <!-- Remittance Breakdown -->
                            <div class="grid grid-cols-3 gap-2 mb-3">
                                <div class="text-center p-2 bg-green-50 rounded-lg">
                                    <i class="fas fa-money-bill-wave text-green-500 text-sm"></i>
                                    <p class="text-xs text-gray-500 mt-1">Cash</p>
                                    <p class="font-bold text-green-600 text-sm">${formatCurrency(remittance.cash_on_hand)}</p>
                                </div>
                                <div class="text-center p-2 bg-blue-50 rounded-lg">
                                    <i class="fas fa-mobile-alt text-blue-500 text-sm"></i>
                                    <p class="text-xs text-gray-500 mt-1">GCash</p>
                                    <p class="font-bold text-blue-600 text-sm">${formatCurrency(remittance.gcash_total)}</p>
                                </div>
                                <div class="text-center p-2 bg-red-50 rounded-lg">
                                    <i class="fas fa-hand-holding-usd text-red-500 text-sm"></i>
                                    <p class="text-xs text-gray-500 mt-1">Cash Out</p>
                                    <p class="font-bold text-red-600 text-sm">${remittance.cash_out_amount > 0 ? '-' + formatCurrency(remittance.cash_out_amount) : '₱0.00'}</p>
                                </div>
                            </div>
                            
                            <!-- Sales vs Remitted -->
                            <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-gray-600"><i class="fas fa-chart-line text-primary mr-1"></i>Sales</span>
                                    <span class="font-semibold text-gray-700">${formatCurrency(remittance.total_sales)}</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-primary/10 rounded">
                                    <span class="text-gray-600"><i class="fas fa-hand-holding-usd text-primary mr-1"></i>Remitted</span>
                                    <span class="font-semibold text-primary">${formatCurrency(remittance.total_remitted)}</span>
                                </div>
                            </div>
                            
                            <!-- Total & Action -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <div>
                                    <p class="text-xs text-gray-500">Total Remitted</p>
                                    <p class="text-xl font-bold text-primary">${formatCurrency(remittance.total_remitted)}</p>
                                </div>
                                <button type="button" class="btn-view-details-mobile px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary transition-all" data-index="${index}">
                                    <i class="fas fa-eye mr-1"></i>View
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#remittanceHistoryCards').html(html);

            // Bind click events for mobile cards
            $('.btn-view-details-mobile').on('click', function() {
                const index = $(this).data('index');
                openDetailsModal(history[index]);
            });
        }

        function updateSummaryCards(history) {
            let totalRemitted = 0, remittanceCount = 0, cashRemitted = 0, gcashRemitted = 0;
            
            history.forEach(remittance => {
                totalRemitted += remittance.total_remitted || 0;
                remittanceCount++;
                cashRemitted += remittance.cash_on_hand || 0;
                gcashRemitted += remittance.gcash_total || 0;
            });

            $('#summaryTotalRemitted').text(formatCurrency(totalRemitted));
            $('#summaryRemittanceCount').text(remittanceCount);
            $('#summaryCashRemitted').text(formatCurrency(cashRemitted));
            $('#summaryGcashRemitted').text(formatCurrency(gcashRemitted));
        }

        function initDetailsModal() {
            $('#btnCloseDetailsModal, #btnCloseModal').on('click', () => $('#remittanceDetailsModal').addClass('hidden'));
            $('#remittanceDetailsModal').on('click', e => { if (e.target === e.currentTarget) $('#remittanceDetailsModal').addClass('hidden'); });

            $('#btnPrintDetails').on('click', function() {
                const content = $('#remittanceDetailsModal .p-6').first().clone();
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Remittance Details</title>
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

        function openDetailsModal(remittance) {
            if (!remittance) return;

            const date = new Date(remittance.date);
            const dateStr = date.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });

            $('#detailDate').text(dateStr);
            $('#detailCashier').text(remittance.cashier_name || '-');
            $('#detailCashierEmail').text(remittance.cashier_email || '-');
            $('#detailOutlet').text(remittance.outlet_name || 'E n\' G Bakery');
            $('#detailShift').text(formatTime(remittance.shift_start) + ' - ' + formatTime(remittance.shift_end));
            
            // Format submitted time
            if (remittance.submitted_at) {
                const submittedDate = new Date(remittance.submitted_at);
                $('#detailSubmittedAt').text(submittedDate.toLocaleString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                }));
            } else {
                $('#detailSubmittedAt').text('-');
            }

            // Remittance breakdown
            $('#detailCashOnHand').text(formatCurrency(remittance.cash_on_hand || 0));
            $('#detailGcash').text(formatCurrency(remittance.gcash_total || 0));
            $('#detailCashOut').text(remittance.cash_out_amount > 0 ? '-' + formatCurrency(remittance.cash_out_amount) : '₱0.00');
            $('#detailTotalRemitted').text(formatCurrency(remittance.total_remitted || 0));

            // Sales vs Remittance
            $('#detailTotalSales').text(formatCurrency(remittance.total_sales || 0));
            $('#detailTotalRemittedCompare').text(formatCurrency(remittance.total_remitted || 0));

            // Cash out reason
            if (remittance.cash_out_amount > 0 && remittance.cash_out_reason) {
                $('#cashOutReasonContainer').show();
                $('#detailCashOutReason').text(remittance.cash_out_reason);
            } else {
                $('#cashOutReasonContainer').hide();
            }
            
            // Variance
            const variance = parseFloat(remittance.variance) || 0;
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

            $('#remittanceDetailsModal').removeClass('hidden');
        }

        function exportToCsv() {
            if (!remittanceData || remittanceData.length === 0) {
                showToast('warning', 'No data to export');
                return;
            }

            const headers = ['Date', 'Cashier', 'Email', 'Shift', 'Cash On Hand', 'GCash', 'Cash Out', 'Cash Out Reason', 'Total Sales', 'Total Remitted', 'Variance'];
            const rows = remittanceData.map(remittance => [
                remittance.date,
                remittance.cashier_name || '',
                remittance.cashier_email || '',
                (remittance.shift_start || '') + ' - ' + (remittance.shift_end || ''),
                remittance.cash_on_hand || 0,
                remittance.gcash_total || 0,
                remittance.cash_out_amount || 0,
                remittance.cash_out_reason || '',
                remittance.total_sales || 0,
                remittance.total_remitted || 0,
                remittance.variance || 0
            ]);

            let csv = headers.join(',') + '\n';
            rows.forEach(row => {
                csv += row.map(cell => `"${cell}"`).join(',') + '\n';
            });

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `remittance_history_${$('#filterDateFrom').val()}_to_${$('#filterDateTo').val()}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            showToast('success', 'Remittance history exported successfully');
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
