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
                    <li class="text-gray-700">Daily Sales Remittance</li>
                </ol>
            </nav>

            <!-- Header Card -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">
                        Daily Sales Remittance
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <a href="<?= base_url('Sales/History') ?>" 
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <i class="fas fa-history mr-2"></i>Sales History
                        </a>
                        <a href="<?= base_url('Sales/RemittanceHistory') ?>" 
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <i class="fas fa-file-invoice-dollar mr-2"></i>Remittance History
                        </a>
                        <button type="button" id="btnPrintRemittance"
                            class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                    </div>
                </div>
            </div>

            <!-- Remittance Slip Card -->
            <div id="remittanceSlip" class="bg-white rounded-lg shadow-md p-6 mb-6">
                <!-- Remittance Header -->
                <div class="border-b-2 border-gray-200 pb-4 mb-4">
                    <h3 class="text-lg font-bold text-center text-gray-800 mb-4">CASHIER'S REMITTANCE SLIP</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-600 w-20">NAME:</label>
                                <input type="text" id="cashierName" 
                                    class="flex-1 border-b border-gray-300 px-2 py-1 text-sm font-semibold text-gray-900 focus:outline-none focus:border-primary"
                                    placeholder="Enter cashier name">
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-600 w-20">EMAIL:</label>
                                <input type="email" id="cashierEmail" 
                                    class="flex-1 border-b border-gray-300 px-2 py-1 text-sm font-semibold text-gray-900 focus:outline-none focus:border-primary"
                                    placeholder="Enter email address">
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-600 w-20">DATE:</label>
                                <span id="remittanceDate" class="flex-1 px-2 py-1 text-sm font-semibold text-gray-900 bg-gray-50 rounded"></span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-600 w-20">OUTLET:</label>
                                <input type="text" id="outletName" 
                                    class="flex-1 border-b border-gray-300 px-2 py-1 text-sm font-semibold text-gray-900 focus:outline-none focus:border-primary"
                                    placeholder="Enter outlet name" value="E n' G Bakery">
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-600 w-20">SHIFT:</label>
                                <span id="shiftTime" class="flex-1 px-2 py-1 text-sm font-semibold text-gray-900 bg-gray-50 rounded">--:-- - --:--</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Grid: Cash Sales | Sales Summary -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- LEFT COLUMN: Cash Sales / Change Fund -->
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <h4 class="text-sm font-bold text-gray-700 mb-3 border-b border-gray-200 pb-2">
                            <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>CASH SALES / CHANGE FUND
                        </h4>
                        
                        <!-- Bills Breakdown -->
                        <div class="space-y-1.5">
                            <!-- 1000 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱1000</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill1000">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill1000" min="0" value="0" 
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill1000">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total1000">₱0.00</span>
                            </div>
                            <!-- 500 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱500</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill500">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill500" min="0" value="0" 
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill500">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total500">₱0.00</span>
                            </div>
                            <!-- 200 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱200</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill200">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill200" min="0" value="0" 
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill200">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total200">₱0.00</span>
                            </div>
                            <!-- 100 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱100</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill100">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill100" min="0" value="0" 
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill100">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total100">₱0.00</span>
                            </div>
                            <!-- 50 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱50</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill50">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill50" min="0" value="0" 
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill50">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total50">₱0.00</span>
                            </div>
                            <!-- 20 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱20</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill20">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill20" min="0" value="0" 
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill20">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total20">₱0.00</span>
                            </div>
                            <!-- 10 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱10</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill10">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill10" min="0" value="0" 
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill10">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total10">₱0.00</span>
                            </div>
                            <!-- 5 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱5</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill5">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill5" min="0" value="0" 
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill5">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total5">₱0.00</span>
                            </div>
                            <!-- 1 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱1</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill1">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill1" min="0" value="0" 
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill1">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total1">₱0.00</span>
                            </div>
                            <!-- 0.25 -->
                            <div class="flex items-center justify-between">
                                <span class="w-14 text-xs font-medium text-gray-700">₱0.25</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="bill-minus w-7 h-7 flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition" data-target="bill025">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" id="bill025" min="0" value="0" 
                                        class="bill-input w-12 text-center border border-gray-300 rounded py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <button type="button" class="bill-plus w-7 h-7 flex items-center justify-center bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition" data-target="bill025">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="w-20 text-right text-sm font-semibold text-gray-800" id="total025">₱0.00</span>
                            </div>
                        </div>
                        
                        <!-- Amount Enclosed -->
                        <div class="mt-3 pt-3 border-t-2 border-green-300 bg-green-50 -mx-4 px-4 -mb-4 pb-3 rounded-b-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-700">AMOUNT ENCLOSED:</span>
                                <span class="text-xl font-bold text-green-600" id="amountEnclosed">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Sales Summary -->
                    <div class="space-y-3">
                        <!-- Total Sales in System -->
                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium text-gray-600">TOTAL SALES IN SYSTEM:</span>
                                <span class="text-xl font-bold text-blue-600" id="systemTotalSales">₱0.00</span>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="p-3 bg-white rounded-lg border border-gray-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-mobile-alt text-blue-600 text-sm"></i>
                                    <span class="text-xs font-medium text-gray-700">Online Payment:</span>
                                </div>
                                <input type="number" id="gcashTotal" min="0" step="0.01" placeholder="0.00"
                                    class="w-28 text-right border border-blue-300 rounded px-2 py-1.5 text-sm font-bold text-blue-600 bg-white focus:ring-1 focus:ring-blue-600 focus:border-blue-600">
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-hand-holding-usd text-gray-600 text-sm"></i>
                                    <span class="text-xs font-medium text-gray-700">CASH OUT:</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <input type="number" id="cashOutAmount" min="0" step="0.01" placeholder="0.00"
                                        class="w-20 text-right border border-gray-200 rounded px-2 py-1.5 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <input type="text" id="cashOutReason" placeholder="Reason"
                                        class="w-24 border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-1 focus:ring-primary focus:border-primary">
                                </div>
                            </div>
                        </div>

                        <!-- Sales by Category -->
                        <div class="p-3 bg-white rounded-lg border border-gray-200">
                            <h5 class="text-xs font-semibold text-gray-600 mb-2">SALES BY CATEGORY</h5>
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between p-2 bg-amber-50 rounded">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-bread-slice text-amber-600 text-xs"></i>
                                        <span class="text-xs font-medium text-gray-700">BAKERY:</span>
                                    </div>
                                    <span class="text-sm font-bold text-amber-600" id="bakerySales">₱0.00</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-orange-50 rounded">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-mug-hot text-orange-600 text-xs"></i>
                                        <span class="text-xs font-medium text-gray-700">COFFEE/DRINKS:</span>
                                    </div>
                                    <span class="text-sm font-bold text-orange-600" id="coffeeSales">₱0.00</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-green-50 rounded">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-shopping-basket text-green-600 text-xs"></i>
                                        <span class="text-xs font-medium text-gray-700">GROCERY:</span>
                                    </div>
                                    <span class="text-sm font-bold text-green-600" id="grocerySales">₱0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="text-center p-2 bg-white rounded">
                                    <div class="text-2xl font-bold text-primary" id="totalOrders">0</div>
                                    <div class="text-xs text-gray-500">Total Orders</div>
                                </div>
                                <div class="text-center p-2 bg-white rounded">
                                    <div class="text-2xl font-bold text-amber-600" id="totalItemsSold">0</div>
                                    <div class="text-xs text-gray-500">Items Sold</div>
                                </div>
                            </div>
                        </div>

                        <!-- Variance Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200" id="cashCountVarianceContainer">
                                <span class="text-xs font-medium text-gray-600 block">CASH VARIANCE:</span>
                                <p class="text-lg font-bold" id="cashCountVariance">₱0.00</p>
                            </div>
                            <div class="p-3 bg-primary/10 rounded-lg border border-primary/20">
                                <span class="text-xs font-medium text-gray-600 block">TOTAL SALES:</span>
                                <p class="text-lg font-bold text-primary" id="totalSales">₱0.00</p>
                            </div>
                        </div>

                        <!-- Overage/Shortage -->
                        <div class="p-3 bg-gray-100 rounded-lg border border-gray-200" id="varianceContainer">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-800">OVERAGE / SHORTAGE:</span>
                                <span class="text-xl font-bold" id="variance">₱0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" id="btnResetForm"
                        class="inline-flex items-center rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </button>
                    <button type="button" id="btnSaveRemittance"
                        class="inline-flex items-center rounded-lg bg-primary px-6 py-2.5 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                        <i class="fas fa-save mr-2"></i>Save Remittance
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Hide number input spinners */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        window.BASE_URL = '<?= rtrim(site_url(), '/') ?>/';

        // Bill denominations mapping
        const billDenominations = {
            'bill1000': 1000,
            'bill500': 500,
            'bill200': 200,
            'bill100': 100,
            'bill50': 50,
            'bill20': 20,
            'bill10': 10,
            'bill5': 5,
            'bill1': 1,
            'bill025': 0.25
        };

        $(document).ready(function() {
            initializeRemittance();
            loadTodaysSalesData();
            bindBillInputEvents();
            bindBillButtonEvents();
            bindGCashInputEvent();
        });

        function initializeRemittance() {
            // Set today's date
            const today = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            $('#remittanceDate').text(today.toLocaleDateString('en-US', options));
        }

        function loadTodaysSalesData() {
            $.ajax({
                url: BASE_URL + 'Sales/GetTodaysSummary',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        
                        // Update shift time
                        if (data.shift_start && data.shift_end) {
                            $('#shiftTime').text(formatTime(data.shift_start) + ' - ' + formatTime(data.shift_end));
                        }
                        
                        // Update sales by category
                        $('#bakerySales').text(formatCurrency(data.bakery_sales || 0));
                        $('#coffeeSales').text(formatCurrency(data.coffee_sales || 0));
                        $('#grocerySales').text(formatCurrency(data.grocery_sales || 0));
                        
                        // Update total sales in system
                        $('#systemTotalSales').text(formatCurrency(data.total_sales || 0));
                        
                        // Update payment methods - set GCash input value
                        $('#gcashTotal').val(data.gcash_total || 0);
                        
                        // Update total sales
                        $('#totalSales').text(formatCurrency(data.total_sales || 0));
                        
                        // Update statistics
                        $('#totalOrders').text(data.total_orders || 0);
                        $('#totalItemsSold').text(data.total_items_sold || 0);
                        
                        // Calculate variance
                        calculateAllTotals();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading sales data:', error);
                }
            });
        }

        function bindBillInputEvents() {
            // Bind events to all bill inputs
            $('.bill-input').on('input', function() {
                updateBillTotal($(this).attr('id'));
            });

            // Also bind cash out amount
            $('#cashOutAmount').on('input', function() {
                calculateVariance();
            });
        }

        function bindBillButtonEvents() {
            // Plus buttons
            $('.bill-plus').on('click', function() {
                const targetId = $(this).data('target');
                const input = $('#' + targetId);
                const currentVal = parseInt(input.val()) || 0;
                input.val(currentVal + 1);
                updateBillTotal(targetId);
            });

            // Minus buttons
            $('.bill-minus').on('click', function() {
                const targetId = $(this).data('target');
                const input = $('#' + targetId);
                const currentVal = parseInt(input.val()) || 0;
                if (currentVal > 0) {
                    input.val(currentVal - 1);
                    updateBillTotal(targetId);
                }
            });
        }

        function updateBillTotal(inputId) {
            const denomination = billDenominations[inputId];
            const quantity = parseInt($('#' + inputId).val()) || 0;
            const total = quantity * denomination;
            
            // Update the corresponding total
            const totalId = '#total' + inputId.replace('bill', '');
            $(totalId).text(formatCurrency(total));
            
            // Recalculate all totals
            calculateAllTotals();
        }

        function bindGCashInputEvent() {
            $('#gcashTotal').on('input', function() {
                calculateVariance();
            });
        }

        function calculateAmountEnclosed() {
            let totalEnclosed = 0;
            
            // Sum all bill totals
            Object.keys(billDenominations).forEach(function(inputId) {
                const quantity = parseInt($('#' + inputId).val()) || 0;
                totalEnclosed += quantity * billDenominations[inputId];
            });
            
            $('#amountEnclosed').text(formatCurrency(totalEnclosed));
            return totalEnclosed;
        }

        function calculateCashCountVariance() {
            const amountEnclosed = calculateAmountEnclosed();
            const totalSales = parseCurrency($('#systemTotalSales').text());
            const gcash = parseFloat($('#gcashTotal').val()) || 0;
            
            // Expected cash = Total Sales - GCash
            const expectedCash = totalSales - gcash;
            const variance = amountEnclosed - expectedCash;
            
            const varianceEl = $('#cashCountVariance');
            const container = $('#cashCountVarianceContainer');
            
            container.removeClass('bg-red-50 bg-green-50 bg-gray-50');
            varianceEl.removeClass('text-red-600 text-green-600 text-gray-800');
            
            if (variance > 0) {
                container.addClass('bg-green-50');
                varianceEl.addClass('text-green-600');
                varianceEl.text('+ ' + formatCurrency(variance) + ' (Over)');
            } else if (variance < 0) {
                container.addClass('bg-red-50');
                varianceEl.addClass('text-red-600');
                varianceEl.text('- ' + formatCurrency(Math.abs(variance)) + ' (Short)');
            } else {
                container.addClass('bg-gray-50');
                varianceEl.addClass('text-gray-800');
                varianceEl.text('₱0.00 (Balanced)');
            }
            
            return variance;
        }

        function calculateAllTotals() {
            calculateAmountEnclosed();
            calculateCashCountVariance();
            calculateVariance();
        }

        function calculateVariance() {
            const amountEnclosed = parseCurrency($('#amountEnclosed').text());
            const gcash = parseFloat($('#gcashTotal').val()) || 0;
            const cashOut = parseFloat($('#cashOutAmount').val()) || 0;
            const totalSales = parseCurrency($('#totalSales').text());
            
            const totalRemitted = amountEnclosed + gcash - cashOut;
            const variance = totalRemitted - totalSales;
            
            // Update variance styling
            const container = $('#varianceContainer');
            const varianceEl = $('#variance');
            
            container.removeClass('bg-red-100 bg-green-100 bg-gray-100');
            varianceEl.removeClass('text-red-600 text-green-600 text-primary');
            
            if (variance > 0) {
                container.addClass('bg-green-100');
                varianceEl.addClass('text-green-600');
                varianceEl.text('+ ' + formatCurrency(variance) + ' (Over)');
            } else if (variance < 0) {
                container.addClass('bg-red-100');
                varianceEl.addClass('text-red-600');
                varianceEl.text('- ' + formatCurrency(Math.abs(variance)) + ' (Short)');
            } else {
                container.addClass('bg-gray-100');
                varianceEl.addClass('text-primary');
                varianceEl.text('₱0.00 (Balanced)');
            }
        }

        function formatCurrency(amount) {
            return '₱' + parseFloat(amount).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function parseCurrency(str) {
            return parseFloat(str.replace(/[₱,]/g, '')) || 0;
        }

        function formatTime(timeStr) {
            if (!timeStr) return '--:--';
            const [hours, minutes] = timeStr.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes} ${ampm}`;
        }

        function getBillsBreakdown() {
            const breakdown = {};
            Object.keys(billDenominations).forEach(function(inputId) {
                const quantity = parseInt($('#' + inputId).val()) || 0;
                if (quantity > 0) {
                    breakdown[inputId.replace('bill', '')] = quantity;
                }
            });
            return breakdown;
        }

        // Reset form
        $('#btnResetForm').on('click', function() {
            // Reset bill inputs
            $('.bill-input').val(0);
            $('.bill-total').text('₱0.00');
            
            // Reset other inputs
            $('#cashOutAmount').val(0);
            $('#cashOutReason').val('');
            $('#cashierName').val('');
            $('#cashierEmail').val('');
            $('#gcashTotal').val(0);
            $('#amountEnclosed').text('₱0.00');
            
            calculateAllTotals();
        });

        // Print functionality
        $('#btnPrintRemittance').on('click', function() {
            const content = $('#remittanceSlip').clone();
            // Remove input elements and replace with values for printing
            content.find('input').each(function() {
                const val = $(this).val() || '0';
                $(this).replaceWith('<span class="font-semibold">' + val + '</span>');
            });
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Cashier's Remittance Slip</title>
                    <script src="https://cdn.tailwindcss.com"><\/script>
                    <style>
                        @media print {
                            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                            .no-print { display: none !important; }
                        }
                        body { font-family: Arial, sans-serif; padding: 20px; }
                    </style>
                </head>
                <body>
                    ${content.html()}
                    <script>
                        setTimeout(() => { window.print(); window.close(); }, 500);
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        });

        // Save remittance
        $('#btnSaveRemittance').on('click', function() {
            const remittanceData = {
                cashier_name: $('#cashierName').val(),
                cashier_email: $('#cashierEmail').val(),
                outlet_name: $('#outletName').val(),
                date: new Date().toISOString().split('T')[0],
                amount_enclosed: parseCurrency($('#amountEnclosed').text()),
                bills_breakdown: getBillsBreakdown(),
                gcash_total: parseFloat($('#gcashTotal').val()) || 0,
                cash_out_amount: parseFloat($('#cashOutAmount').val()) || 0,
                cash_out_reason: $('#cashOutReason').val(),
                bakery_sales: parseCurrency($('#bakerySales').text()),
                coffee_sales: parseCurrency($('#coffeeSales').text()),
                grocery_sales: parseCurrency($('#grocerySales').text()),
                total_sales: parseCurrency($('#totalSales').text()),
                variance: parseCurrency($('#variance').text().replace(/[+\-()OverShortBalanced ]/g, ''))
            };

            $.ajax({
                url: BASE_URL + 'Sales/SaveRemittance',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(remittanceData),
                success: function(response) {
                    if (response.success) {
                        showToast('success', 'Remittance saved successfully!');
                    } else {
                        showToast('danger', response.message || 'Failed to save remittance');
                    }
                },
                error: function(xhr, status, error) {
                    showToast('danger', 'Error saving remittance: ' + error);
                }
            });
        });
    </script>
