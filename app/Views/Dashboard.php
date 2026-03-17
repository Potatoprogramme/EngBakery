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
                            <i class="far fa-calendar-alt mr-1"></i><?= $currentDate ?> · <?= $currentTime ?>
                        </p>
                    </div>
                    <div class="mt-3 sm:mt-0">
                        <?php if ($inventoryExists): ?>
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-white/20 text-white backdrop-blur-sm">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5 animate-pulse"></span>
                                Inventory Active
                            </span>
                        <?php else: ?>
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-white/20 text-white backdrop-blur-sm">
                                <span class="w-2 h-2 bg-yellow-400 rounded-full mr-1.5"></span>
                                No Inventory Today
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Cards - Mobile: 2 cols, Desktop: 4 cols -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <!-- Today's Sales -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Today's Sales</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1">
                                ₱<?= number_format($todaysSales, 2) ?></p>
                        </div>
                        <div
                            class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-primary/10 rounded-full flex items-center justify-center ml-2">
                            <i class="fas fa-peso-sign text-primary text-base sm:text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-3 flex items-center text-xs">
                        <span class="text-green-600 font-medium">
                            <i class="fas fa-arrow-trend-up mr-1"></i>Revenue today
                        </span>
                    </div>
                </div>

                <!-- Items Sold -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Items Sold</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1">
                                <?= number_format($todaysItemsSold) ?>
                            </p>
                        </div>
                        <div
                            class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-full flex items-center justify-center ml-2">
                            <i class="fas fa-box text-blue-600 text-base sm:text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-3 flex items-center text-xs">
                        <span class="text-blue-600 font-medium">
                            <i class="fas fa-chart-line mr-1"></i>Today's total
                        </span>
                    </div>
                </div>

                <!-- Orders Today -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Orders Today</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1">
                                <?= number_format($todaysOrderCount) ?>
                            </p>
                        </div>
                        <div
                            class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-amber-50 rounded-full flex items-center justify-center ml-2">
                            <i class="fas fa-receipt text-amber-600 text-base sm:text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-3 flex items-center text-xs">
                        <span class="text-amber-600 font-medium">
                            <i class="fas fa-clock mr-1"></i>Completed
                        </span>
                    </div>
                </div>

                <!-- Avg Order Value -->
                <?php $avgOrderValue = $todaysOrderCount > 0 ? $todaysSales / $todaysOrderCount : 0; ?>
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Avg Order Value</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1">
                                ₱<?= number_format($avgOrderValue, 2) ?></p>
                        </div>
                        <div
                            class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-purple-50 rounded-full flex items-center justify-center ml-2">
                            <i class="fas fa-chart-pie text-purple-600 text-base sm:text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-3 flex items-center text-xs">
                        <span class="text-purple-600 font-medium">
                            <i class="fas fa-trending-up mr-1"></i>Per transaction
                        </span>
                    </div>
                </div>
            </div>

            <!-- Sales by Category - Kebab Style (3 horizontal cards) -->
            <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <!-- Bakery -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0 flex-1">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                <i class="fas fa-bread-slice text-amber-600 text-xs sm:text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Bakery</p>
                                <p class="text-sm sm:text-lg font-bold text-gray-900">
                                    ₱<?= number_format($bakerySales, 2) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Drinks -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0 flex-1">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                <i class="fas fa-mug-hot text-blue-600 text-xs sm:text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Drinks</p>
                                <p class="text-sm sm:text-lg font-bold text-gray-900">
                                    ₱<?= number_format($drinksSales, 2) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Grocery -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0 flex-1">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                <i class="fas fa-shopping-basket text-green-600 text-xs sm:text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Grocery</p>
                                <p class="text-sm sm:text-lg font-bold text-gray-900">
                                    ₱<?= number_format($grocerySales, 2) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods - Full Width -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 mb-4 sm:mb-6">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-credit-card text-primary mr-2"></i>
                    Payment Methods
                </h3>
                <div class="grid grid-cols-3 gap-3">
                    <!-- Cash -->
                    <div class="flex items-center p-2 sm:p-3 bg-green-50 rounded-lg">
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                            <i class="fas fa-wallet text-green-600 text-xl sm:text-2xl"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-green-700 font-medium">Cash</p>
                            <p class="text-sm sm:text-base font-bold text-gray-900">₱<?= number_format($cashSales, 2) ?>
                            </p>
                        </div>
                    </div>
                    <!-- GCash -->
                    <div class="flex items-center p-3 sm:p-4 rounded-lg bg-blue-50">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
                            <img src="<?= base_url('assets/pictures/gcash.svg') ?>" alt="GCash"
                                class="w-6 h-6 sm:w-7 sm:h-7 object-contain">
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm font-semibold text-blue-600 mb-0.5">GCash</p>
                            <p class="text-base sm:text-lg font-bold text-gray-900">
                                ₱<?= number_format($gcashSales, 2) ?>
                            </p>
                        </div>
                    </div>
                    <!-- FoodPanda -->
                    <div class="flex items-center p-2 sm:p-3 rounded-lg" style="background-color: #fff0f3;">
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                            <img src="<?= base_url('assets/pictures/food-panda.svg') ?>" alt="FoodPanda"
                                class="w-6 h-6 sm:w-7 sm:h-7 object-contain">
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium" style="color: #D70F64;">FoodPanda</p>
                            <p class="text-sm sm:text-base font-bold text-gray-900">
                                ₱<?= number_format($foodpandaSales, 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Status & Low Stock Alert -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <!-- Inventory Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-clipboard-list text-primary mr-2"></i>
                        Today's Inventory
                    </h3>
                    <?php if ($inventoryExists): ?>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-2 sm:p-3 bg-blue-50 rounded-lg">
                                <span class="text-xs sm:text-sm text-gray-600">Beginning Stock</span>
                                <span
                                    class="text-sm sm:text-base font-bold text-blue-700"><?= number_format($totalBeginningStock) ?></span>
                            </div>
                            <div class="flex justify-between items-center p-2 sm:p-3 bg-green-50 rounded-lg">
                                <span class="text-xs sm:text-sm text-gray-600">Remaining Stock</span>
                                <span
                                    class="text-sm sm:text-base font-bold text-green-700"><?= number_format($totalEndingStock) ?></span>
                            </div>
                            <div class="flex justify-between items-center p-2 sm:p-3 bg-amber-50 rounded-lg">
                                <span class="text-xs sm:text-sm text-gray-600">Sold Today</span>
                                <span
                                    class="text-sm sm:text-base font-bold text-amber-700"><?= number_format($totalBeginningStock - $totalEndingStock) ?></span>
                            </div>
                            <?php if ($inventoryData['time_start']): ?>
                                <div class="text-xs text-gray-500 text-center mt-2">
                                    <i class="far fa-clock mr-1"></i>Started at
                                    <?= date('g:i A', strtotime($inventoryData['time_start'])) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 sm:py-6">
                            <div
                                class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-clipboard-list text-gray-400 text-xl sm:text-2xl"></i>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-500 mb-3">No inventory started today</p>
                            <a href="<?= base_url('Inventory') ?>"
                                class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 bg-primary text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-secondary transition-colors">
                                <i class="fas fa-plus mr-1.5"></i>Start Inventory
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Low Stock Alert -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 lg:col-span-2">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>
                        Low Stock Alert
                        <?php if (count($lowStockProducts) > 0): ?>
                            <span
                                class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full"><?= count($lowStockProducts) ?></span>
                        <?php endif; ?>
                    </h3>
                    <?php if (count($lowStockProducts) > 0): ?>
                        <div class="overflow-x-auto">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <?php foreach (array_slice($lowStockProducts, 0, 6) as $product): ?>
                                    <div
                                        class="flex items-center justify-between p-2 sm:p-3 bg-red-50 rounded-lg border border-red-100">
                                        <div class="flex items-center min-w-0">
                                            <div
                                                class="w-7 h-7 sm:w-8 sm:h-8 bg-red-100 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                                                <i class="fas fa-bread-slice text-red-500 text-xs"></i>
                                            </div>
                                            <span
                                                class="text-xs sm:text-sm font-medium text-gray-900 truncate"><?= esc($product['product_name']) ?></span>
                                        </div>
                                        <span
                                            class="px-2 py-0.5 bg-red-200 text-red-800 text-xs font-bold rounded-full ml-2 flex-shrink-0">
                                            <?= $product['ending_stock'] ?> left
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 sm:py-6">
                            <div
                                class="w-12 h-12 sm:w-16 sm:h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-check text-green-500 text-xl sm:text-2xl"></i>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-500">All products are well-stocked!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Best Sellers & Recent Orders -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <!-- Best Sellers Today -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-fire text-orange-500 mr-2"></i>
                        Best Sellers Today
                    </h3>
                    <?php if (count($bestSellers) > 0): ?>
                        <div class="space-y-2 sm:space-y-3">
                            <?php foreach ($bestSellers as $index => $product): ?>
                                <div
                                    class="flex items-center justify-between p-2 sm:p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center min-w-0">
                                        <div
                                            class="w-6 h-6 sm:w-8 sm:h-8 <?= $index === 0 ? 'bg-yellow-400' : ($index === 1 ? 'bg-gray-300' : ($index === 2 ? 'bg-amber-600' : 'bg-gray-200')) ?> rounded-full flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                            <span class="text-xs sm:text-sm font-bold text-white"><?= $index + 1 ?></span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">
                                                <?= esc($product['product_name']) ?>
                                            </p>
                                            <p class="text-xs text-gray-500"><?= ucfirst($product['category']) ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right ml-2 flex-shrink-0">
                                        <p class="text-xs sm:text-sm font-bold text-primary">
                                            <?= number_format($product['total_sold']) ?> sold
                                        </p>
                                        <p class="text-xs text-gray-500">₱<?= number_format($product['revenue'], 2) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 sm:py-6">
                            <div
                                class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-shopping-bag text-gray-400 text-xl sm:text-2xl"></i>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-500">No sales recorded today</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-receipt text-primary mr-2"></i>
                            Recent Orders
                        </h3>
                        <a href="<?= base_url('Sales/History') ?>"
                            class="text-xs sm:text-sm text-primary hover:text-secondary font-medium">
                            View All <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>
                    <?php if (count($recentOrders) > 0): ?>
                        <div class="space-y-2 sm:space-y-3">
                            <?php foreach ($recentOrders as $order): ?>
                                <div class="flex items-center justify-between p-2 sm:p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center min-w-0">
                                        <div
                                            class="w-8 h-8 sm:w-10 sm:h-10 bg-primary/10 rounded-full flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                            <i class="fas fa-shopping-cart text-primary text-xs sm:text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">
                                                <?= $order['date_created'] . '-' . $order['order_id'] ?>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <?= date('g:i A', strtotime($order['time_created'])) ?>
                                                <?php if (($order['order_type'] ?? '') === 'foodpanda'): ?>
                                                    · <span class="font-medium" style="color: #D70F64;">FoodPanda</span>
                                                <?php else: ?>
                                                    · <?= ucfirst($order['payment_method']) ?> · <span
                                                        class="text-gray-500">Walk-in</span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs sm:text-sm font-bold text-gray-900 ml-2">₱<?= number_format($order['total_payment_due'], 2) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 sm:py-6">
                            <div
                                class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-receipt text-gray-400 text-xl sm:text-2xl"></i>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-500">No orders yet today</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sales Report Trend -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 mb-4 sm:mb-6">
                <div class="flex flex-col items-start gap-3 mb-3 sm:mb-4">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 flex items-center" id="salesTrendTitle">
                        <i class="fas fa-chart-line text-primary mr-2"></i>
                        Sales Report Trend
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
                                class="sales-trend-toggle flex-1 sm:flex-none px-3 py-2 text-xs sm:text-sm font-medium text-white bg-primary">
                                Daily
                            </button>
                            <button type="button" data-mode="weekly"
                                class="sales-trend-toggle flex-1 sm:flex-none px-3 py-2 text-xs sm:text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 border-l border-gray-200">
                                Weekly
                            </button>
                            <button type="button" data-mode="monthly"
                                class="sales-trend-toggle flex-1 sm:flex-none px-3 py-2 text-xs sm:text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 border-l border-gray-200">
                                Monthly
                            </button>
                        </div>
                    </div>
                </div>

                <p id="salesTrendSubtitle" class="text-xs sm:text-sm text-gray-500 mb-3">Daily sales for the last 14 days.</p>

                <div class="relative h-56 sm:h-64 rounded-lg border border-gray-100 bg-gradient-to-b from-white to-gray-50 p-2 sm:p-3">
                    <canvas id="salesTrendCanvas" class="w-full h-full"></canvas>
                </div>

                <div class="flex flex-wrap justify-between items-center mt-3 pt-3 border-t border-gray-100 gap-2 text-xs sm:text-sm">
                    <span class="text-gray-500"><i class="fas fa-arrow-down text-red-500 mr-1"></i>Low: <span id="salesTrendLow" class="font-semibold text-gray-700">₱0.00</span></span>
                    <span class="text-gray-500"><i class="fas fa-arrow-up text-green-500 mr-1"></i>High: <span id="salesTrendHigh" class="font-semibold text-gray-700">₱0.00</span></span>
                    <span class="text-primary font-semibold"><i class="fas fa-calculator mr-1"></i>Total: <span id="salesTrendTotal">₱0.00</span></span>
                </div>
            </div>

            <!-- System Overview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 mb-4 sm:mb-6">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-database text-gray-500 mr-2"></i>
                    System Overview
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-bread-slice text-amber-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Products</p>
                            <p class="text-lg font-bold text-gray-900"><?= number_format($totalProducts) ?></p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-cubes text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Raw Materials</p>
                            <p class="text-lg font-bold text-gray-900"><?= number_format($totalRawMaterials) ?></p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Low Stock</p>
                            <p
                                class="text-lg font-bold <?= count($lowStockProducts) > 0 ? 'text-red-600' : 'text-gray-900' ?>">
                                <?= count($lowStockProducts) ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Inventory</p>
                            <p class="text-lg font-bold <?= $inventoryExists ? 'text-green-600' : 'text-red-600' ?>">
                                <?= $inventoryExists ? 'Done' : 'Pending' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions - Mobile Friendly Grid -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                    Quick Actions
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
        const salesTrendData = <?= json_encode($salesTrend ?? ['daily' => [], 'weekly' => [], 'monthly' => []], JSON_UNESCAPED_UNICODE) ?>;
        let currentTrendMode = 'daily';
        let currentChartType = 'line';

        function formatPeso(value) {
            return '₱' + (Number(value) || 0).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function drawSalesTrend(mode) {
            const points = Array.isArray(salesTrendData[mode]) ? salesTrendData[mode] : [];
            const canvas = document.getElementById('salesTrendCanvas');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const ratio = window.devicePixelRatio || 1;
            const cssWidth = canvas.clientWidth || 700;
            const cssHeight = canvas.clientHeight || 260;
            canvas.width = Math.floor(cssWidth * ratio);
            canvas.height = Math.floor(cssHeight * ratio);
            ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

            ctx.clearRect(0, 0, cssWidth, cssHeight);

            if (!points.length) {
                ctx.fillStyle = '#6b7280';
                ctx.font = '14px sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('No sales data available', cssWidth / 2, cssHeight / 2);
                document.getElementById('salesTrendLow').textContent = formatPeso(0);
                document.getElementById('salesTrendHigh').textContent = formatPeso(0);
                document.getElementById('salesTrendTotal').textContent = formatPeso(0);
                return;
            }

            const labels = points.map((p) => p.label || '');
            const values = points.map((p) => Number(p.value) || 0);
            const minVal = Math.min(...values);
            const maxVal = Math.max(...values);
            const totalVal = values.reduce((sum, val) => sum + val, 0);

            document.getElementById('salesTrendLow').textContent = formatPeso(minVal);
            document.getElementById('salesTrendHigh').textContent = formatPeso(maxVal);
            document.getElementById('salesTrendTotal').textContent = formatPeso(totalVal);

            const subtitles = {
                daily: 'Daily sales for the last 14 days.',
                weekly: 'Weekly sales for the last 8 weeks.',
                monthly: 'Monthly sales for the last 12 months.'
            };
            const subtitleEl = document.getElementById('salesTrendSubtitle');
            if (subtitleEl) subtitleEl.textContent = subtitles[mode] || subtitles.daily;

            const pad = { top: 18, right: 18, bottom: 40, left: 46 };
            const chartW = cssWidth - pad.left - pad.right;
            const chartH = cssHeight - pad.top - pad.bottom;
            const safeMin = 0;
            let safeMax = maxVal;
            if (safeMax <= 0) safeMax = 100;
            if (safeMax < minVal + 1) safeMax = minVal + 1;

            const scaleY = (val) => pad.top + chartH - ((val - safeMin) / (safeMax - safeMin)) * chartH;
            const stepX = labels.length > 1 ? chartW / (labels.length - 1) : 0;

            // Grid + y labels
            ctx.strokeStyle = '#e5e7eb';
            ctx.fillStyle = '#6b7280';
            ctx.lineWidth = 1;
            ctx.font = '11px sans-serif';
            ctx.textAlign = 'right';
            for (let i = 0; i <= 4; i++) {
                const v = safeMin + ((safeMax - safeMin) * i) / 4;
                const y = scaleY(v);
                ctx.beginPath();
                ctx.moveTo(pad.left, y);
                ctx.lineTo(pad.left + chartW, y);
                ctx.stroke();
                ctx.fillText('₱' + Math.round(v).toLocaleString('en-PH'), pad.left - 6, y + 4);
            }

            // X labels
            ctx.textAlign = 'center';
            labels.forEach((label, idx) => {
                const x = pad.left + stepX * idx;
                const shortLabel = label.length > 10 ? label.slice(0, 10) + '…' : label;
                ctx.fillText(shortLabel, x, cssHeight - 12);
            });

            if (currentChartType === 'bar') {
                const slotW = labels.length > 0 ? chartW / labels.length : chartW;
                const barW = Math.max(8, Math.min(38, slotW * 0.62));

                values.forEach((val, idx) => {
                    const centerX = pad.left + (slotW * idx) + slotW / 2;
                    const topY = scaleY(val);
                    const barH = Math.max(0, (pad.top + chartH) - topY);
                    const leftX = centerX - (barW / 2);

                    ctx.fillStyle = '#16a34a';
                    ctx.fillRect(leftX, topY, barW, barH);
                });
            } else {
                // Line
                ctx.beginPath();
                values.forEach((val, idx) => {
                    const x = pad.left + stepX * idx;
                    const y = scaleY(val);
                    if (idx === 0) ctx.moveTo(x, y);
                    else ctx.lineTo(x, y);
                });
                ctx.strokeStyle = '#16a34a';
                ctx.lineWidth = 2.5;
                ctx.stroke();

                // Points
                ctx.fillStyle = '#16a34a';
                values.forEach((val, idx) => {
                    const x = pad.left + stepX * idx;
                    const y = scaleY(val);
                    ctx.beginPath();
                    ctx.arc(x, y, 3, 0, Math.PI * 2);
                    ctx.fill();
                });
            }
        }

        function setTrendMode(mode) {
            currentTrendMode = mode;
            document.querySelectorAll('.sales-trend-toggle').forEach((btn) => {
                const active = btn.getAttribute('data-mode') === mode;
                btn.classList.toggle('bg-primary', active);
                btn.classList.toggle('text-white', active);
                btn.classList.toggle('bg-white', !active);
                btn.classList.toggle('text-gray-600', !active);
            });
            drawSalesTrend(mode);
        }

        function setChartType(type) {
            currentChartType = (type === 'bar') ? 'bar' : 'line';

            document.querySelectorAll('.sales-chart-type-toggle').forEach((btn) => {
                const active = btn.getAttribute('data-type') === currentChartType;
                btn.classList.toggle('bg-primary', active);
                btn.classList.toggle('text-white', active);
                btn.classList.toggle('bg-white', !active);
                btn.classList.toggle('text-gray-600', !active);
            });

            const titleEl = document.getElementById('salesTrendTitle');
            if (titleEl) {
                const iconClass = currentChartType === 'bar' ? 'fa-chart-bar' : 'fa-chart-line';
                titleEl.innerHTML = `<i class="fas ${iconClass} text-primary mr-2"></i>Sales Report Trend`;
            }

            drawSalesTrend(currentTrendMode);
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.sales-trend-toggle').forEach((btn) => {
                btn.addEventListener('click', function () {
                    setTrendMode(this.getAttribute('data-mode') || 'daily');
                });
            });

            document.querySelectorAll('.sales-chart-type-toggle').forEach((btn) => {
                btn.addEventListener('click', function () {
                    setChartType(this.getAttribute('data-type') || 'line');
                });
            });

            window.addEventListener('resize', function () {
                drawSalesTrend(currentTrendMode);
            });

            setTrendMode('daily');
            setChartType('line');
        });

        // Auto-refresh dashboard every 5 minutes
        setTimeout(function () {
            location.reload();
        }, 300000);
    </script>