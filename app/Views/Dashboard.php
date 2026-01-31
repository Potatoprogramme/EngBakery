<body class="bg-gray-50">
    <!-- Main Content -->
    <div class="p-3 sm:p-4 sm:ml-60">
        <div class="mt-14">
            <!-- Header Section -->
            <div class="mb-4 sm:mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Dashboard</h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            <i class="far fa-calendar-alt mr-1"></i><?= $currentDate ?> · <?= $currentTime ?>
                        </p>
                    </div>
                    <div class="mt-2 sm:mt-0">
                        <?php if ($inventoryExists): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                Inventory Active
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-1.5"></span>
                                No Inventory Today
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Cards - Mobile: 2 cols, Desktop: 4 cols -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <!-- Today's Sales -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Today's Sales</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1">₱<?= number_format($todaysSales, 2) ?></p>
                        </div>
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-primary/10 rounded-full flex items-center justify-center ml-2">
                            <i class="fas fa-peso-sign text-primary text-base sm:text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-3 flex items-center text-xs">
                        <span class="text-green-600 font-medium">
                            <i class="fas fa-shopping-cart mr-1"></i><?= $todaysOrderCount ?> orders
                        </span>
                    </div>
                </div>

                <!-- Items Sold -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Items Sold</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1"><?= number_format($todaysItemsSold) ?></p>
                        </div>
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-full flex items-center justify-center ml-2">
                            <i class="fas fa-box text-blue-600 text-base sm:text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-3 flex items-center text-xs">
                        <span class="text-blue-600 font-medium">
                            <i class="fas fa-chart-line mr-1"></i>Today's total
                        </span>
                    </div>
                </div>

                <!-- Products -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Products</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1"><?= number_format($totalProducts) ?></p>
                        </div>
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-amber-50 rounded-full flex items-center justify-center ml-2">
                            <i class="fas fa-bread-slice text-amber-600 text-base sm:text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-3 flex items-center text-xs">
                        <span class="text-amber-600 font-medium">
                            <i class="fas fa-tags mr-1"></i>Active items
                        </span>
                    </div>
                </div>

                <!-- Raw Materials -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Raw Materials</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1"><?= number_format($totalRawMaterials) ?></p>
                        </div>
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-purple-50 rounded-full flex items-center justify-center ml-2">
                            <i class="fas fa-cubes text-purple-600 text-base sm:text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-3 flex items-center text-xs">
                        <span class="text-purple-600 font-medium">
                            <i class="fas fa-warehouse mr-1"></i>In inventory
                        </span>
                    </div>
                </div>
            </div>

            <!-- Sales by Category & Payment Methods - Mobile: Stack, Desktop: Side by Side -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <!-- Sales by Category -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-chart-pie text-primary mr-2"></i>
                        Sales by Category
                    </h3>
                    <div class="space-y-3">
                        <!-- Bakery -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0 flex-1">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                    <i class="fas fa-bread-slice text-amber-600 text-xs sm:text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">Bakery</p>
                                    <p class="text-xs text-gray-500">Bread & Pastries</p>
                                </div>
                            </div>
                            <span class="text-sm sm:text-base font-semibold text-gray-900 ml-2">₱<?= number_format($bakerySales, 2) ?></span>
                        </div>
                        <!-- Drinks -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0 flex-1">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                    <i class="fas fa-mug-hot text-blue-600 text-xs sm:text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">Drinks</p>
                                    <p class="text-xs text-gray-500">Coffee & Beverages</p>
                                </div>
                            </div>
                            <span class="text-sm sm:text-base font-semibold text-gray-900 ml-2">₱<?= number_format($drinksSales, 2) ?></span>
                        </div>
                        <!-- Grocery -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0 flex-1">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                    <i class="fas fa-shopping-basket text-green-600 text-xs sm:text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">Grocery</p>
                                    <p class="text-xs text-gray-500">Other Items</p>
                                </div>
                            </div>
                            <span class="text-sm sm:text-base font-semibold text-gray-900 ml-2">₱<?= number_format($grocerySales, 2) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-credit-card text-primary mr-2"></i>
                        Payment Methods
                    </h3>
                    <div class="space-y-3">
                        <!-- Cash -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0 flex-1">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                    <i class="fas fa-money-bill-wave text-green-600 text-xs sm:text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">Cash</p>
                                    <p class="text-xs text-gray-500">Physical payment</p>
                                </div>
                            </div>
                            <span class="text-sm sm:text-base font-semibold text-gray-900 ml-2">₱<?= number_format($cashSales, 2) ?></span>
                        </div>
                        <!-- GCash -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0 flex-1">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                    <i class="fas fa-mobile-alt text-blue-600 text-xs sm:text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">GCash</p>
                                    <p class="text-xs text-gray-500">E-wallet</p>
                                </div>
                            </div>
                            <span class="text-sm sm:text-base font-semibold text-gray-900 ml-2">₱<?= number_format($gcashSales, 2) ?></span>
                        </div>
                        <!-- Maya -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0 flex-1">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                    <i class="fas fa-wallet text-purple-600 text-xs sm:text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">Maya</p>
                                    <p class="text-xs text-gray-500">E-wallet</p>
                                </div>
                            </div>
                            <span class="text-sm sm:text-base font-semibold text-gray-900 ml-2">₱<?= number_format($mayaSales, 2) ?></span>
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
                                <span class="text-sm sm:text-base font-bold text-blue-700"><?= number_format($totalBeginningStock) ?></span>
                            </div>
                            <div class="flex justify-between items-center p-2 sm:p-3 bg-green-50 rounded-lg">
                                <span class="text-xs sm:text-sm text-gray-600">Remaining Stock</span>
                                <span class="text-sm sm:text-base font-bold text-green-700"><?= number_format($totalEndingStock) ?></span>
                            </div>
                            <div class="flex justify-between items-center p-2 sm:p-3 bg-amber-50 rounded-lg">
                                <span class="text-xs sm:text-sm text-gray-600">Sold Today</span>
                                <span class="text-sm sm:text-base font-bold text-amber-700"><?= number_format($totalBeginningStock - $totalEndingStock) ?></span>
                            </div>
                            <?php if ($inventoryData['time_start']): ?>
                                <div class="text-xs text-gray-500 text-center mt-2">
                                    <i class="far fa-clock mr-1"></i>Started at <?= date('g:i A', strtotime($inventoryData['time_start'])) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 sm:py-6">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-clipboard-list text-gray-400 text-xl sm:text-2xl"></i>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-500 mb-3">No inventory started today</p>
                            <a href="<?= base_url('Inventory') ?>" class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 bg-primary text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-secondary transition-colors">
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
                            <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full"><?= count($lowStockProducts) ?></span>
                        <?php endif; ?>
                    </h3>
                    <?php if (count($lowStockProducts) > 0): ?>
                        <div class="overflow-x-auto">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <?php foreach (array_slice($lowStockProducts, 0, 6) as $product): ?>
                                    <div class="flex items-center justify-between p-2 sm:p-3 bg-red-50 rounded-lg border border-red-100">
                                        <div class="flex items-center min-w-0">
                                            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-red-100 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                                                <i class="fas fa-bread-slice text-red-500 text-xs"></i>
                                            </div>
                                            <span class="text-xs sm:text-sm font-medium text-gray-900 truncate"><?= esc($product['product_name']) ?></span>
                                        </div>
                                        <span class="px-2 py-0.5 bg-red-200 text-red-800 text-xs font-bold rounded-full ml-2 flex-shrink-0">
                                            <?= $product['ending_stock'] ?> left
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 sm:py-6">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
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
                                <div class="flex items-center justify-between p-2 sm:p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center min-w-0">
                                        <div class="w-6 h-6 sm:w-8 sm:h-8 <?= $index === 0 ? 'bg-yellow-400' : ($index === 1 ? 'bg-gray-300' : ($index === 2 ? 'bg-amber-600' : 'bg-gray-200')) ?> rounded-full flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                            <span class="text-xs sm:text-sm font-bold text-white"><?= $index + 1 ?></span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs sm:text-sm font-medium text-gray-900 truncate"><?= esc($product['product_name']) ?></p>
                                            <p class="text-xs text-gray-500"><?= ucfirst($product['category']) ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right ml-2 flex-shrink-0">
                                        <p class="text-xs sm:text-sm font-bold text-primary"><?= number_format($product['total_sold']) ?> sold</p>
                                        <p class="text-xs text-gray-500">₱<?= number_format($product['revenue'], 2) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 sm:py-6">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
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
                        <a href="<?= base_url('Sales/History') ?>" class="text-xs sm:text-sm text-primary hover:text-secondary font-medium">
                            View All <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>
                    <?php if (count($recentOrders) > 0): ?>
                        <div class="space-y-2 sm:space-y-3">
                            <?php foreach ($recentOrders as $order): ?>
                                <div class="flex items-center justify-between p-2 sm:p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center min-w-0">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-primary/10 rounded-full flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                            <i class="fas fa-shopping-cart text-primary text-xs sm:text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs sm:text-sm font-medium text-gray-900 truncate"><?= esc($order['order_number']) ?></p>
                                            <p class="text-xs text-gray-500"><?= date('g:i A', strtotime($order['time_created'])) ?> · <?= ucfirst($order['payment_method']) ?></p>
                                        </div>
                                    </div>
                                    <span class="text-xs sm:text-sm font-bold text-gray-900 ml-2">₱<?= number_format($order['total_payment_due'], 2) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 sm:py-6">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-receipt text-gray-400 text-xl sm:text-2xl"></i>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-500">No orders yet today</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Weekly Sales Trend -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 mb-4 sm:mb-6">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-chart-line text-primary mr-2"></i>
                    Weekly Sales Trend
                </h3>
                <?php if (count($weeklyTrend) > 0): ?>
                    <?php 
                    // Calculate min and max for Y-axis
                    $salesValues = array_column($weeklyTrend, 'daily_total');
                    $minSales = min($salesValues);
                    $maxSales = max($salesValues);
                    
                    // Round to nice numbers for Y-axis labels
                    function roundToNice($value, $roundUp = true) {
                        if ($value <= 0) return 0;
                        $magnitude = pow(10, floor(log10($value)));
                        $normalized = $value / $magnitude;
                        
                        if ($roundUp) {
                            if ($normalized <= 1) $nice = 1;
                            elseif ($normalized <= 2) $nice = 2;
                            elseif ($normalized <= 5) $nice = 5;
                            else $nice = 10;
                        } else {
                            if ($normalized < 2) $nice = 1;
                            elseif ($normalized < 5) $nice = 2;
                            else $nice = 5;
                        }
                        
                        return $nice * $magnitude;
                    }
                    
                    // Calculate nice Y-axis bounds
                    $yMin = floor($minSales / 100) * 100; // Round down to nearest 100
                    $yMax = ceil($maxSales / 100) * 100;  // Round up to nearest 100
                    
                    // Ensure minimum range
                    if ($yMax - $yMin < 500) {
                        $yMax = $yMin + 500;
                    }
                    
                    // If all values are 0
                    if ($yMax == 0) {
                        $yMax = 500;
                        $yMin = 0;
                    }
                    
                    // Calculate step size (aim for 5 labels)
                    $range = $yMax - $yMin;
                    $step = ceil($range / 5 / 100) * 100; // Round to nearest 100
                    if ($step < 100) $step = 100;
                    
                    // Generate Y-axis labels
                    $yLabels = [];
                    for ($i = $yMax; $i >= $yMin; $i -= $step) {
                        $yLabels[] = $i;
                    }
                    if (end($yLabels) != $yMin) {
                        $yLabels[] = $yMin;
                    }
                    
                    $chartHeight = 160; // pixels
                    ?>
                    
                    <div class="flex">
                        <!-- Y-Axis Labels -->
                        <div class="flex flex-col justify-between pr-2 sm:pr-3 text-right" style="height: <?= $chartHeight ?>px;">
                            <?php foreach ($yLabels as $label): ?>
                                <span class="text-xs text-gray-500 leading-none">₱<?= number_format($label) ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Chart Area -->
                        <div class="flex-1 relative">
                            <!-- Grid Lines -->
                            <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                                <?php foreach ($yLabels as $index => $label): ?>
                                    <div class="border-t border-gray-100 <?= $index === 0 ? 'border-gray-200' : '' ?>"></div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Bars Container -->
                            <div class="relative flex items-end justify-between gap-1 sm:gap-2" style="height: <?= $chartHeight ?>px;">
                                <?php foreach ($weeklyTrend as $day): ?>
                                    <?php 
                                    $value = floatval($day['daily_total']);
                                    // Calculate height percentage based on Y-axis range
                                    $heightPercent = $yMax > $yMin ? (($value - $yMin) / ($yMax - $yMin)) * 100 : 0;
                                    $heightPercent = max($heightPercent, 2); // Minimum visible height
                                    ?>
                                    <div class="flex-1 flex flex-col items-center h-full justify-end group">
                                        <!-- Tooltip -->
                                        <div class="hidden group-hover:block absolute -top-8 bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-lg z-10 whitespace-nowrap">
                                            ₱<?= number_format($value, 2) ?>
                                        </div>
                                        <!-- Bar -->
                                        <div class="w-full bg-primary rounded-t-md transition-all duration-300 hover:bg-secondary cursor-pointer relative"
                                             style="height: <?= $heightPercent ?>%;">
                                            <!-- Value on top of bar (visible on hover) -->
                                            <span class="absolute -top-5 left-1/2 transform -translate-x-1/2 text-xs font-semibold text-primary opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap hidden sm:block">
                                                ₱<?= number_format($value, 0) ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- X-Axis Labels -->
                    <div class="flex mt-2">
                        <div class="w-12 sm:w-16"></div> <!-- Spacer for Y-axis -->
                        <div class="flex-1 flex justify-between gap-1 sm:gap-2">
                            <?php foreach ($weeklyTrend as $day): ?>
                                <div class="flex-1 text-center">
                                    <p class="text-xs font-medium text-gray-600"><?= date('D', strtotime($day['date_created'])) ?></p>
                                    <p class="text-xs text-gray-400 hidden sm:block"><?= date('M j', strtotime($day['date_created'])) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Summary -->
                    <div class="flex flex-wrap justify-between items-center mt-3 sm:mt-4 pt-3 border-t border-gray-100 gap-2">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <span class="text-xs sm:text-sm text-gray-500">
                                <i class="fas fa-arrow-down text-red-500 mr-1"></i>Low: <span class="font-semibold text-gray-700">₱<?= number_format($minSales, 2) ?></span>
                            </span>
                            <span class="text-xs sm:text-sm text-gray-500">
                                <i class="fas fa-arrow-up text-green-500 mr-1"></i>High: <span class="font-semibold text-gray-700">₱<?= number_format($maxSales, 2) ?></span>
                            </span>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold text-primary">
                            <i class="fas fa-calculator mr-1"></i>Total: ₱<?= number_format(array_sum($salesValues), 2) ?>
                        </span>
                    </div>
                <?php else: ?>
                    <div class="text-center py-6 sm:py-8">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-chart-bar text-gray-400 text-xl sm:text-2xl"></i>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500">No sales data available</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Quick Actions - Mobile Friendly Grid -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                    Quick Actions
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
                    <a href="<?= base_url('Order') ?>" class="flex flex-col items-center justify-center p-3 sm:p-4 bg-primary/5 rounded-xl hover:bg-primary/10 transition-colors group">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-primary rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-cart-plus text-white text-base sm:text-lg"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">New Order</span>
                    </a>
                    <a href="<?= base_url('Inventory') ?>" class="flex flex-col items-center justify-center p-3 sm:p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors group">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-500 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-clipboard-check text-white text-base sm:text-lg"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">Inventory</span>
                    </a>
                    <a href="<?= base_url('Sales') ?>" class="flex flex-col items-center justify-center p-3 sm:p-4 bg-amber-50 rounded-xl hover:bg-amber-100 transition-colors group">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-500 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-invoice-dollar text-white text-base sm:text-lg"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">Remittance</span>
                    </a>
                    <a href="<?= base_url('Products') ?>" class="flex flex-col items-center justify-center p-3 sm:p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors group">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-500 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-box-open text-white text-base sm:text-lg"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">Products</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh dashboard every 5 minutes
        setTimeout(function() {
            location.reload();
        }, 300000);
    </script>
