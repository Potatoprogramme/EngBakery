<body class="bg-gray-50">
    <!-- Main Content -->
    <div class="p-3 sm:p-4 sm:ml-60">
        <div class="mt-14">
            <!-- Welcome Section -->
            <div
                class="mb-4 sm:mb-6 bg-gradient-to-r from-primary to-secondary rounded-xl p-4 sm:p-6 text-white shadow-lg">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm sm:text-base text-white/80">Good
                            <?= date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') ?>,
                        </p>
                        <h1 class="text-xl sm:text-2xl font-bold"><?= esc($name ?? 'User') ?>!</h1>
                        <p class="text-xs sm:text-sm text-white/70 mt-1">
                            <i class="far fa-calendar-alt mr-1"></i>
                            <span id="currentDate">--</span> · <span id="currentTime">--</span>
                        </p>
                    </div>
                    <div id="inventoryBadge" class="mt-3 sm:mt-0"></div>
                </div>
            </div>

            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Today's Sales</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1" id="todaysSales">₱0.00</p>
                        </div>
                        <div
                            class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-primary/10 rounded-full flex items-center justify-center ml-2">
                            <i class="fas fa-peso-sign text-primary text-base sm:text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-3 flex items-center text-xs">
                        <span class="text-green-600 font-medium"><i class="fas fa-arrow-trend-up mr-1"></i>Revenue
                            today</span>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Items Sold</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1" id="todaysItemsSold">0</p>
                        </div>
                        <div
                            class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-full flex items-center justify-center ml-2">
                            <i class="fas fa-box text-blue-600 text-base sm:text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-3 flex items-center text-xs">
                        <span class="text-blue-600 font-medium"><i class="fas fa-chart-line mr-1"></i>Today's
                            total</span>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Orders Today</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1" id="todaysOrderCount">0</p>
                        </div>
                        <div
                            class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-amber-50 rounded-full flex items-center justify-center ml-2">
                            <i class="fas fa-receipt text-amber-600 text-base sm:text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-3 flex items-center text-xs">
                        <span class="text-amber-600 font-medium"><i class="fas fa-clock mr-1"></i>Completed</span>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Avg Order Value</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1" id="avgOrderValue">₱0.00</p>
                        </div>
                        <div
                            class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-purple-50 rounded-full flex items-center justify-center ml-2">
                            <i class="fas fa-chart-pie text-purple-600 text-base sm:text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-3 flex items-center text-xs">
                        <span class="text-purple-600 font-medium"><i class="fas fa-trending-up mr-1"></i>Per
                            transaction</span>
                    </div>
                </div>
            </div>

            <!-- Sales by Category -->
            <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center min-w-0 flex-1">
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                            <i class="fas fa-bread-slice text-amber-600 text-xs sm:text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Bakery</p>
                            <p class="text-sm sm:text-lg font-bold text-gray-900" id="bakerySales">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center min-w-0 flex-1">
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                            <i class="fas fa-mug-hot text-blue-600 text-xs sm:text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Drinks</p>
                            <p class="text-sm sm:text-lg font-bold text-gray-900" id="drinksSales">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center min-w-0 flex-1">
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                            <i class="fas fa-shopping-basket text-green-600 text-xs sm:text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Grocery</p>
                            <p class="text-sm sm:text-lg font-bold text-gray-900" id="grocerySales">₱0.00</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 mb-4 sm:mb-6">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-credit-card text-primary mr-2"></i>Payment Methods
                </h3>
                <div class="grid grid-cols-3 gap-3">
                    <div class="flex items-center p-2 sm:p-3 bg-green-50 rounded-lg">
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                            <i class="fas fa-wallet text-green-600 text-xl sm:text-2xl"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-green-700 font-medium">Cash</p>
                            <p class="text-sm sm:text-base font-bold text-gray-900" id="cashSales">₱0.00</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 sm:p-4 rounded-lg bg-blue-50">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
                            <img src="<?= base_url('assets/pictures/gcash.svg') ?>" alt="GCash"
                                class="w-6 h-6 sm:w-7 sm:h-7 object-contain">
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm font-semibold text-blue-600 mb-0.5">GCash</p>
                            <p class="text-base sm:text-lg font-bold text-gray-900" id="gcashSales">₱0.00</p>
                        </div>
                    </div>
                    <div class="flex items-center p-2 sm:p-3 rounded-lg" style="background-color: #fff0f3;">
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                            <img src="<?= base_url('assets/pictures/food-panda.svg') ?>" alt="FoodPanda"
                                class="w-6 h-6 sm:w-7 sm:h-7 object-contain">
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium" style="color: #D70F64;">FoodPanda</p>
                            <p class="text-sm sm:text-base font-bold text-gray-900" id="foodpandaSales">₱0.00</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Status & Low Stock Alert -->
            <!-- FIX 1: inventorySummarySection now has full card wrapper classes.
                 JS only renders the inner body content, not the outer card shell. -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-clipboard-list text-primary mr-2"></i>
                        Today's Inventory
                    </h3>
                    <div id="inventorySummarySection">
                        <!-- JS renders inner content here -->
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 lg:col-span-2">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>
                        Low Stock Alert
                        <span id="lowStockBadge"
                            class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full hidden"></span>
                    </h3>
                    <div id="lowStockList"></div>
                </div>
            </div>

            <!-- Best Sellers & Recent Orders -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-fire text-orange-500 mr-2"></i>Best Sellers Today
                    </h3>
                    <div id="bestSellersList"></div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-receipt text-primary mr-2"></i>Recent Orders
                        </h3>
                        <a href="<?= base_url('Sales/History') ?>"
                            class="text-xs sm:text-sm text-primary hover:text-secondary font-medium">
                            View All <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>
                    <div id="recentOrdersList"></div>
                </div>
            </div>

            <!-- Sales Report Trend -->
            <!-- FIX 2: Removed the duplicate inner <script> block that was conflicting -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 mb-4 sm:mb-6">
                <div class="flex flex-col items-start gap-3 mb-3 sm:mb-4">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 flex items-center" id="salesTrendTitle">
                        <i class="fas fa-chart-line text-primary mr-2"></i>Sales Report Trend
                        <span
                            class="ml-2 inline-flex items-center justify-center w-5 h-5 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700 cursor-help"
                            title="Trend values include manual sales adjustments and discrepancy amounts folded into category totals.">
                            <i class="fas fa-info-circle text-xs"></i>
                        </span>
                    </h3>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 w-full">
                        <div class="inline-flex rounded-lg border border-gray-200 overflow-hidden w-full sm:w-auto">
                            <button type="button" data-type="line"
                                class="sales-chart-type-toggle flex-1 sm:flex-none px-3 py-2 text-xs sm:text-sm font-medium text-white bg-primary">
                                <i class="fas fa-chart-line mr-1"></i>Line
                            </button>
                            <button type="button" data-type="bar"
                                class="sales-chart-type-toggle flex-1 sm:flex-none px-3 py-2 text-xs sm:text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 border-l border-gray-200">
                                <i class="fas fa-chart-bar mr-1"></i>Bar
                            </button>
                        </div>
                        <div class="inline-flex rounded-lg border border-gray-200 overflow-hidden w-full sm:w-auto">
                            <button type="button" data-mode="daily"
                                class="sales-trend-toggle flex-1 sm:flex-none px-3 py-2 text-xs sm:text-sm font-medium text-white bg-primary">Daily</button>
                            <button type="button" data-mode="weekly"
                                class="sales-trend-toggle flex-1 sm:flex-none px-3 py-2 text-xs sm:text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 border-l border-gray-200">Weekly</button>
                            <button type="button" data-mode="monthly"
                                class="sales-trend-toggle flex-1 sm:flex-none px-3 py-2 text-xs sm:text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 border-l border-gray-200">Monthly</button>
                        </div>
                    </div>
                </div>

                <p id="salesTrendSubtitle" class="text-xs sm:text-sm text-gray-500 mb-3">Daily sales for the last 14
                    days.</p>

                <div id="salesTrendCanvasWrap"
                    class="relative h-56 sm:h-64 rounded-lg border border-gray-100 bg-gradient-to-b from-white to-gray-50 p-2 sm:p-3">
                    <canvas id="salesTrendCanvas" class="w-full h-full"></canvas>
                    <div id="salesTrendTooltip"
                        class="hidden absolute z-20 pointer-events-none rounded-md bg-gray-900/95 text-white text-[11px] leading-tight px-2 py-1 shadow-lg whitespace-nowrap">
                    </div>
                </div>

                <div
                    class="flex flex-wrap justify-between items-center mt-3 pt-3 border-t border-gray-100 gap-2 text-xs sm:text-sm">
                    <span class="text-gray-500"><i class="fas fa-arrow-down text-red-500 mr-1"></i>Low: <span
                            id="salesTrendLow" class="font-semibold text-gray-700">₱0.00</span></span>
                    <span class="text-gray-500"><i class="fas fa-arrow-up text-green-500 mr-1"></i>High: <span
                            id="salesTrendHigh" class="font-semibold text-gray-700">₱0.00</span></span>
                    <span class="text-primary font-semibold"><i class="fas fa-calculator mr-1"></i>Total: <span
                            id="salesTrendTotal">₱0.00</span></span>
                </div>
            </div>

            <!-- System Overview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 mb-4 sm:mb-6">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-database text-gray-500 mr-2"></i>System Overview
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-bread-slice text-amber-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Products</p>
                            <p class="text-lg font-bold text-gray-900" id="totalProducts">0</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-cubes text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Raw Materials</p>
                            <p class="text-lg font-bold text-gray-900" id="totalRawMaterials">0</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-triangle-exclamation text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Low Stock</p>
                            <p class="text-lg font-bold text-gray-900" id="lowStockCount">0</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Inventory</p>
                            <p class="text-lg font-bold text-gray-900" id="inventoryStatus">Pending</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i>Quick Actions
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
                    <a href="<?= base_url('Order') ?>"
                        class="flex flex-col items-center justify-center p-3 sm:p-4 bg-primary/5 rounded-xl hover:bg-primary/10 transition-colors group">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-primary rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-cart-plus text-white text-base sm:text-lg"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">New Order</span>
                    </a>
                    <a href="<?= base_url('Inventory') ?>"
                        class="flex flex-col items-center justify-center p-3 sm:p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors group">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-500 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-clipboard-check text-white text-base sm:text-lg"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">Inventory</span>
                    </a>
                    <a href="<?= base_url('Sales') ?>"
                        class="flex flex-col items-center justify-center p-3 sm:p-4 bg-amber-50 rounded-xl hover:bg-amber-100 transition-colors group">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-500 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-invoice-dollar text-white text-base sm:text-lg"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">Remittance</span>
                    </a>
                    <a href="<?= base_url('Products') ?>"
                        class="flex flex-col items-center justify-center p-3 sm:p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors group">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-500 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-box-open text-white text-base sm:text-lg"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">Products</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        let salesTrendData = { daily: [], weekly: [], monthly: [] };
        let currentTrendMode = 'daily';
        let currentChartType = 'line';
        let trendRenderRafId = null;
        let trendResizeTimer = null;
        let trendState = { width: 0, height: 0, mode: '', type: '' };
        let trendPlotPoints = [];
        let trendLastPoints = [];

        const trendEls = {
            canvas: null, subtitle: null, low: null,
            high: null, total: null, title: null,
            tooltip: null, canvasWrap: null
        };

        function formatPeso(value) {
            return '₱' + (Number(value) || 0).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function escHtml(str) {
            return $('<div>').text(str ?? '').html();
        }

        function capitalize(str) {
            return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
        }

        function populateDashboard(data) {
            // Date & Time
            $('#currentDate').text(data.currentDate ?? '--');
            $('#currentTime').text(data.currentTime ?? '--');

            // Inventory Badge
            const $badge = $('#inventoryBadge');
            $badge.html(data.inventoryExists
                ? `<span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-white/20 text-white backdrop-blur-sm">
                   <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5 animate-pulse"></span>Inventory Active
               </span>`
                : `<span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-white/20 text-white backdrop-blur-sm">
                   <span class="w-2 h-2 bg-yellow-400 rounded-full mr-1.5"></span>No Inventory Today
               </span>`
            );

            // Quick Stats
            $('#todaysSales').text(formatPeso(data.todaysSales));
            $('#todaysItemsSold').text(Number(data.todaysItemsSold).toLocaleString());
            $('#todaysOrderCount').text(Number(data.todaysOrderCount).toLocaleString());
            const avg = data.todaysOrderCount > 0 ? data.todaysSales / data.todaysOrderCount : 0;
            $('#avgOrderValue').text(formatPeso(avg));

            // Category Sales
            $('#bakerySales').text(formatPeso(data.bakerySales));
            $('#drinksSales').text(formatPeso(data.drinksSales));
            $('#grocerySales').text(formatPeso(data.grocerySales));

            // Payment Methods
            $('#cashSales').text(formatPeso(data.cashSales));
            $('#gcashSales').text(formatPeso(data.gcashSales));
            $('#foodpandaSales').text(formatPeso(data.foodpandaSales));

            // Inventory Summary
            const $inventorySection = $('#inventorySummarySection');
            if (data.inventoryExists && data.inventoryData) {
                const sold = (data.totalBeginningStock ?? 0) - (data.totalEndingStock ?? 0);
                const timeStarted = data.inventoryData.time_start
                    ? `<div class="text-xs text-gray-500 text-center mt-2">
                       <i class="far fa-clock mr-1"></i>Started at
                       ${new Date('1970-01-01T' + data.inventoryData.time_start).toLocaleTimeString('en-PH', { hour: 'numeric', minute: '2-digit', hour12: true })}
                   </div>` : '';
                $inventorySection.html(`
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-2 sm:p-3 bg-blue-50 rounded-lg">
                        <span class="text-xs sm:text-sm text-gray-600">Beginning Stock</span>
                        <span class="text-sm sm:text-base font-bold text-blue-700">${Number(data.totalBeginningStock).toLocaleString()}</span>
                    </div>
                    <div class="flex justify-between items-center p-2 sm:p-3 bg-green-50 rounded-lg">
                        <span class="text-xs sm:text-sm text-gray-600">Remaining Stock</span>
                        <span class="text-sm sm:text-base font-bold text-green-700">${Number(data.totalEndingStock).toLocaleString()}</span>
                    </div>
                    <div class="flex justify-between items-center p-2 sm:p-3 bg-amber-50 rounded-lg">
                        <span class="text-xs sm:text-sm text-gray-600">Sold Today</span>
                        <span class="text-sm sm:text-base font-bold text-amber-700">${Number(sold).toLocaleString()}</span>
                    </div>
                    ${timeStarted}
                </div>`);
            } else {
                $inventorySection.html(`
                <div class="text-center py-4 sm:py-6">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-clipboard-list text-gray-400 text-xl sm:text-2xl"></i>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-500 mb-3">No inventory started today</p>
                    <a href="<?= base_url('Inventory') ?>" class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 bg-primary text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-secondary transition-colors">
                        <i class="fas fa-plus mr-1.5"></i>Start Inventory
                    </a>
                </div>`);
            }

            // Low Stock
            const $lowStockList = $('#lowStockList');
            const $lowStockBadge = $('#lowStockBadge');
            const lowStock = data.lowStockProducts ?? [];

            if (lowStock.length > 0) {
                $lowStockBadge.text(lowStock.length).removeClass('hidden');
                const items = lowStock.slice(0, 6).map(p => `
                <div class="flex items-center justify-between p-2 sm:p-3 bg-red-50 rounded-lg border border-red-100">
                    <div class="flex items-center min-w-0">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-red-100 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                            <i class="fas fa-bread-slice text-red-500 text-xs"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-900 truncate">${escHtml(p.product_name)}</span>
                    </div>
                    <span class="px-2 py-0.5 bg-red-200 text-red-800 text-xs font-bold rounded-full ml-2 flex-shrink-0">${p.ending_stock} left</span>
                </div>`).join('');
                $lowStockList.html(`<div class="grid grid-cols-1 sm:grid-cols-2 gap-2">${items}</div>`);
            } else {
                $lowStockBadge.addClass('hidden');
                $lowStockList.html(`
                <div class="text-center py-4 sm:py-6">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-check text-green-500 text-xl sm:text-2xl"></i>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-500">All products are well-stocked!</p>
                </div>`);
            }

            // Best Sellers
            const $bestSellersList = $('#bestSellersList');
            const sellers = data.bestSellers ?? [];
            const rankColors = ['bg-yellow-400', 'bg-gray-300', 'bg-amber-600'];
            if (sellers.length > 0) {
                const items = sellers.map((p, i) => `
                <div class="flex items-center justify-between p-2 sm:p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center min-w-0">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 ${rankColors[i] ?? 'bg-gray-200'} rounded-full flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                            <span class="text-xs sm:text-sm font-bold text-white">${i + 1}</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">${escHtml(p.product_name)}</p>
                            <p class="text-xs text-gray-500">${capitalize(p.category)}</p>
                        </div>
                    </div>
                    <div class="text-right ml-2 flex-shrink-0">
                        <p class="text-xs sm:text-sm font-bold text-primary">${Number(p.total_sold).toLocaleString()} sold</p>
                        <p class="text-xs text-gray-500">${formatPeso(p.revenue)}</p>
                    </div>
                </div>`).join('');
                $bestSellersList.html(`<div class="space-y-2 sm:space-y-3">${items}</div>`);
            } else {
                $bestSellersList.html(`
                <div class="text-center py-4 sm:py-6">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-shopping-bag text-gray-400 text-xl sm:text-2xl"></i>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-500">No sales recorded today</p>
                </div>`);
            }

            // Recent Orders
            const $recentOrdersList = $('#recentOrdersList');
            const orders = data.recentOrders ?? [];
            if (orders.length > 0) {
                const items = orders.map(o => {
                    const isFoodpanda = (o.order_type ?? '') === 'foodpanda';
                    const sub = isFoodpanda
                        ? `· <span class="font-medium" style="color:#D70F64;">FoodPanda</span>`
                        : `· ${capitalize(o.payment_method ?? '')} · <span class="text-gray-500">Walk-in</span>`;
                    const time = o.time_created
                        ? new Date('1970-01-01T' + o.time_created).toLocaleTimeString('en-PH', { hour: 'numeric', minute: '2-digit', hour12: true })
                        : '';
                    return `
                    <div class="flex items-center justify-between p-2 sm:p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center min-w-0">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-primary/10 rounded-full flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                <i class="fas fa-shopping-cart text-primary text-xs sm:text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">${o.date_created}-${o.order_id}</p>
                                <p class="text-xs text-gray-500">${time} ${sub}</p>
                            </div>
                        </div>
                        <span class="text-xs sm:text-sm font-bold text-gray-900 ml-2">${formatPeso(o.total_payment_due)}</span>
                    </div>`;
                }).join('');
                $recentOrdersList.html(`<div class="space-y-2 sm:space-y-3">${items}</div>`);
            } else {
                $recentOrdersList.html(`
                <div class="text-center py-4 sm:py-6">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-receipt text-gray-400 text-xl sm:text-2xl"></i>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-500">No orders yet today</p>
                </div>`);
            }

            // System Overview
            $('#totalProducts').text(Number(data.totalProducts).toLocaleString());
            $('#totalRawMaterials').text(Number(data.totalRawMaterials).toLocaleString());

            $('#lowStockCount')
                .text(lowStock.length)
                .attr('class', `text-lg font-bold ${lowStock.length > 0 ? 'text-red-600' : 'text-gray-900'}`);

            $('#inventoryStatus')
                .text(data.inventoryExists ? 'Done' : 'Pending')
                .attr('class', `text-lg font-bold ${data.inventoryExists ? 'text-green-600' : 'text-red-600'}`);

            // Sales Trend
            if (data.salesTrend) {
                salesTrendData = data.salesTrend;
                scheduleTrendDraw(true);
            }
        }

        function fetchDashboardData() {
            $.ajax({
                url: '<?= base_url('Dashboard/GetDashboardData') ?>',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response && typeof response === 'object') {
                        populateDashboard(response);
                    }
                    console.log(response)
                },
                error: function (xhr) {
                    console.error(xhr.responseJSON?.message ?? 'Failed to load dashboard data');
                }
            });
        }

        function drawSalesTrend(mode, force = false) {
            const points = Array.isArray(salesTrendData[mode]) ? salesTrendData[mode] : [];
            const canvas = trendEls.canvas;
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const ratio = window.devicePixelRatio || 1;
            const cssWidth = canvas.parentElement ? canvas.parentElement.clientWidth - 24 : 700;
            const cssHeight = canvas.parentElement ? canvas.parentElement.clientHeight - 24 : 260;

            if (!force &&
                trendState.width === cssWidth &&
                trendState.height === cssHeight &&
                trendState.mode === mode &&
                trendState.type === currentChartType) return;

            trendState = { width: cssWidth, height: cssHeight, mode, type: currentChartType };
            canvas.width = Math.floor(cssWidth * ratio);
            canvas.height = Math.floor(cssHeight * ratio);
            ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
            ctx.clearRect(0, 0, cssWidth, cssHeight);
            trendPlotPoints = [];

            // --- Color theme ---
            const GREEN = '#16a34a';
            const GREEN_LIGHT = '#bbf7d0';
            const GRAY_LINE = '#e5e7eb';
            const GRAY_LABEL = '#9ca3af';
            const GRAY_HOVER = '#f3f4f6';

            if (!points.length) {
                ctx.fillStyle = GRAY_LABEL;
                ctx.font = `${cssWidth < 400 ? 12 : 14}px sans-serif`;
                ctx.textAlign = 'center';
                ctx.fillText('No data available', cssWidth / 2, cssHeight / 2);
                $(trendEls.low).text(formatPeso(0));
                $(trendEls.high).text(formatPeso(0));
                $(trendEls.total).text(formatPeso(0));
                trendLastPoints = [];
                hideTrendTooltip();
                return;
            }

            const labels = points.map(p => p.label || '');
            const values = points.map(p => Number(p.value) || 0);
            const maxVal = Math.max(...values, 1);
            const totalVal = values.reduce((s, v) => s + v, 0);

            $(trendEls.low).text(formatPeso(Math.min(...values)));
            $(trendEls.high).text(formatPeso(maxVal));
            $(trendEls.total).text(formatPeso(totalVal));

            const subtitles = {
                daily: 'Daily sales for the last 14 days.',
                weekly: 'Weekly sales for the last 8 weeks.',
                monthly: 'Monthly sales for the last 12 months.'
            };
            if (trendEls.subtitle) $(trendEls.subtitle).text(subtitles[mode] || '');

            const isSmall = cssWidth < 480;
            const fontSize = isSmall ? 9 : 11;
            const pad = {
                top: 20,
                right: isSmall ? 8 : 16,
                bottom: isSmall ? 50 : (mode === 'daily' || labels.length > 8 ? 54 : 40), // ✅ taller
                left: isSmall ? 44 : 52
            };

            const chartW = cssWidth - pad.left - pad.right;
            const chartH = cssHeight - pad.top - pad.bottom;
            const scaleY = v => pad.top + chartH - (v / maxVal) * chartH;

            // ── Y axis grid & labels ────────────────────────────────────────
            const ySteps = 4;
            ctx.font = `${fontSize}px sans-serif`;
            for (let i = 0; i <= ySteps; i++) {
                const v = (maxVal * i) / ySteps;
                const y = scaleY(v);

                // grid line
                ctx.beginPath();
                ctx.strokeStyle = i === 0 ? '#d1d5db' : GRAY_LINE;
                ctx.lineWidth = i === 0 ? 1.5 : 1;
                ctx.setLineDash(i === 0 ? [] : [3, 3]);
                ctx.moveTo(pad.left, y);
                ctx.lineTo(pad.left + chartW, y);
                ctx.stroke();
                ctx.setLineDash([]);

                // label
                ctx.fillStyle = GRAY_LABEL;
                ctx.textAlign = 'right';
                ctx.textBaseline = 'middle';
                const label = v >= 1000
                    ? '₱' + (v / 1000).toFixed(v % 1000 === 0 ? 0 : 1) + 'k'
                    : '₱' + Math.round(v);
                ctx.fillText(label, pad.left - 6, y);
            }

            // ── X axis labels ───────────────────────────────────────────────
            const slotW = chartW / labels.length;
            const stepX = labels.length > 1 ? chartW / (labels.length - 1) : 0;

            // ✅ Always show all labels — rotate if daily/many points
            const shouldRotate = mode === 'daily' || labels.length > 8;

            ctx.fillStyle = GRAY_LABEL;
            ctx.font = `${fontSize}px sans-serif`;
            ctx.textBaseline = 'top';

            labels.forEach((lbl, idx) => {
                const x = currentChartType === 'bar'
                    ? pad.left + slotW * idx + slotW / 2
                    : pad.left + stepX * idx;

                // Shorten weekly labels on small screens
                let display = lbl;
                if (mode === 'weekly' && isSmall) {
                    display = lbl.split(' - ')[0];
                }

                if (shouldRotate) {
                    ctx.save();
                    ctx.translate(x, cssHeight - pad.bottom + 6);
                    ctx.rotate(-Math.PI / 4); // ✅ 45° angle — fits all labels
                    ctx.textAlign = 'right';
                    ctx.fillText(display, 0, 0);
                    ctx.restore();
                } else {
                    ctx.textAlign = 'center';
                    ctx.fillText(display, x, cssHeight - pad.bottom + 6);
                }
            });

            // ── Chart drawing ───────────────────────────────────────────────
            if (currentChartType === 'bar') {
                const barW = Math.max(4, Math.min(isSmall ? 24 : 36, slotW * 0.55));
                const radius = Math.min(4, barW / 3);

                values.forEach((val, idx) => {
                    const cx = pad.left + slotW * idx + slotW / 2;
                    const topY = scaleY(val);
                    const barH = Math.max(val > 0 ? 2 : 0, pad.top + chartH - topY);
                    const x = cx - barW / 2;

                    if (barH === 0) {
                        trendPlotPoints.push({ x: cx, y: pad.top + chartH, label: labels[idx], value: val });
                        return;
                    }

                    // Gradient fill
                    const grad = ctx.createLinearGradient(0, topY, 0, topY + barH);
                    grad.addColorStop(0, GREEN);
                    grad.addColorStop(1, GREEN_LIGHT);
                    ctx.fillStyle = grad;

                    // Rounded top bars
                    ctx.beginPath();
                    ctx.moveTo(x + radius, topY);
                    ctx.lineTo(x + barW - radius, topY);
                    ctx.quadraticCurveTo(x + barW, topY, x + barW, topY + radius);
                    ctx.lineTo(x + barW, topY + barH);
                    ctx.lineTo(x, topY + barH);
                    ctx.lineTo(x, topY + radius);
                    ctx.quadraticCurveTo(x, topY, x + radius, topY);
                    ctx.closePath();
                    ctx.fill();

                    trendPlotPoints.push({ x: cx, y: topY, label: labels[idx], value: val });
                });

            } else {
                // ── Line chart ─────────────────────────────────────────────

                // Gradient area fill
                const areaGrad = ctx.createLinearGradient(0, pad.top, 0, pad.top + chartH);
                areaGrad.addColorStop(0, 'rgba(22,163,74,0.18)');
                areaGrad.addColorStop(1, 'rgba(22,163,74,0)');

                ctx.beginPath();
                values.forEach((val, idx) => {
                    const x = pad.left + stepX * idx;
                    const y = scaleY(val);
                    idx === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
                });
                // close path to bottom for fill
                ctx.lineTo(pad.left + stepX * (values.length - 1), pad.top + chartH);
                ctx.lineTo(pad.left, pad.top + chartH);
                ctx.closePath();
                ctx.fillStyle = areaGrad;
                ctx.fill();

                // Line stroke
                ctx.beginPath();
                values.forEach((val, idx) => {
                    const x = pad.left + stepX * idx;
                    const y = scaleY(val);
                    idx === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
                });
                ctx.strokeStyle = GREEN;
                ctx.lineWidth = 2.5;
                ctx.lineJoin = 'round';
                ctx.stroke();

                // Data points
                values.forEach((val, idx) => {
                    const x = pad.left + stepX * idx;
                    const y = scaleY(val);
                    const r = isSmall ? 3 : 4;

                    // White ring
                    ctx.beginPath();
                    ctx.arc(x, y, r + 1.5, 0, Math.PI * 2);
                    ctx.fillStyle = '#ffffff';
                    ctx.fill();

                    // Green dot
                    ctx.beginPath();
                    ctx.arc(x, y, r, 0, Math.PI * 2);
                    ctx.fillStyle = GREEN;
                    ctx.fill();

                    trendPlotPoints.push({ x, y, label: labels[idx], value: val });
                });
            }

            trendLastPoints = trendPlotPoints;
        }

        function hideTrendTooltip() {
            $(trendEls.tooltip).addClass('hidden');
        }

        function showTrendTooltip(clientX, clientY) {
            if (!trendEls.canvas || !trendEls.tooltip || !trendEls.canvasWrap || !trendLastPoints.length) return;

            const rect = trendEls.canvas.getBoundingClientRect();
            const localX = clientX - rect.left;
            const localY = clientY - rect.top;

            if (localX < 0 || localY < 0 || localX > rect.width || localY > rect.height) {
                hideTrendTooltip();
                return;
            }

            let nearest = trendLastPoints[0];
            let minDist = Math.abs(localX - nearest.x);
            $.each(trendLastPoints, function (i, point) {
                const d = Math.abs(localX - point.x);
                if (d < minDist) { nearest = point; minDist = d; }
            });

            const $tooltip = $(trendEls.tooltip);
            $tooltip.html(`${nearest.label}<br><strong>${formatPeso(nearest.value)}</strong>`).removeClass('hidden');

            const wrapRect = trendEls.canvasWrap.getBoundingClientRect();
            const tipW = $tooltip.outerWidth();
            const tipH = $tooltip.outerHeight();
            let left = nearest.x + 10;
            let top = nearest.y - tipH - 8;
            if (left + tipW > wrapRect.width - 6) left = wrapRect.width - tipW - 6;
            if (left < 6) left = 6;
            if (top < 6) top = nearest.y + 10;
            $tooltip.css({ left: `${left}px`, top: `${top}px` });
        }

        function scheduleTrendDraw(force = false) {
            if (trendRenderRafId !== null) cancelAnimationFrame(trendRenderRafId);
            trendRenderRafId = requestAnimationFrame(() => {
                trendRenderRafId = null;
                drawSalesTrend(currentTrendMode, force);
            });
        }

        function setTrendMode(mode) {
            currentTrendMode = mode;
            $('.sales-trend-toggle').each(function () {
                const active = $(this).data('mode') === mode;
                $(this).toggleClass('bg-primary text-white', active)
                    .toggleClass('bg-white text-gray-600', !active);
            });
            scheduleTrendDraw(true);
        }

        function setChartType(type) {
            currentChartType = type === 'bar' ? 'bar' : 'line';
            $('.sales-chart-type-toggle').each(function () {
                const active = $(this).data('type') === currentChartType;
                $(this).toggleClass('bg-primary text-white', active)
                    .toggleClass('bg-white text-gray-600', !active);
            });
            if (trendEls.title) {
                const icon = currentChartType === 'bar' ? 'fa-chart-bar' : 'fa-chart-line';
                $(trendEls.title).html(`<i class="fas ${icon} text-primary mr-2"></i>Sales Report Trend`);
            }
            scheduleTrendDraw(true);
        }

        $(document).ready(function () {
            trendEls.canvas = document.getElementById('salesTrendCanvas');
            trendEls.subtitle = document.getElementById('salesTrendSubtitle');
            trendEls.low = document.getElementById('salesTrendLow');
            trendEls.high = document.getElementById('salesTrendHigh');
            trendEls.total = document.getElementById('salesTrendTotal');
            trendEls.title = document.getElementById('salesTrendTitle');
            trendEls.tooltip = document.getElementById('salesTrendTooltip');
            trendEls.canvasWrap = document.getElementById('salesTrendCanvasWrap');

            if (trendEls.canvas) {
                $(trendEls.canvas)
                    .on('mousemove', e => showTrendTooltip(e.clientX, e.clientY))
                    .on('mouseleave', hideTrendTooltip)
                    .on('touchstart', e => {
                        const t = e.originalEvent.touches[0];
                        if (t) showTrendTooltip(t.clientX, t.clientY);
                    })
                    .on('touchmove', e => {
                        const t = e.originalEvent.touches[0];
                        if (t) showTrendTooltip(t.clientX, t.clientY);
                    })
                    .on('touchend', hideTrendTooltip);
            }

            $(document).on('click', '.sales-trend-toggle', function () {
                setTrendMode($(this).data('mode') || 'daily');
            });

            $(document).on('click', '.sales-chart-type-toggle', function () {
                setChartType($(this).data('type') || 'line');
            });

            $(window).on('resize', function () {
                if (trendResizeTimer !== null) clearTimeout(trendResizeTimer);
                trendResizeTimer = setTimeout(() => {
                    trendResizeTimer = null;
                    hideTrendTooltip();
                    scheduleTrendDraw(true);
                }, 120);
            });

            setTrendMode('daily');
            setChartType('line');
            fetchDashboardData();
        });

        // Auto-refresh every 5 minutes
        setTimeout(() => location.reload(), 300000);
    </script>