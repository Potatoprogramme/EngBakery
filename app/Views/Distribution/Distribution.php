<?php $isOwnerView = (($employee_type ?? '') === 'owner'); ?>

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

            <!-- Main Layout: List + Calendar -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-6 mb-4 lg:mb-6">
                
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
                    <div class="grid grid-cols-3 gap-2 mb-4">
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
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mr-2">
                                    <i class="fas fa-boxes text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Batches</p>
                                    <p id="totalBatchCount" class="text-sm font-bold text-gray-900">0</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center mr-2">
                                    <i class="fas fa-puzzle-piece text-blue-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Pieces</p>
                                    <p id="totalPiecesCount" class="text-sm font-bold text-gray-900">0</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Forecasted Sales Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-3 mb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center mr-2">
                                    <i class="fas fa-coins text-primary text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Forecasted Sales</p>
                                    <p class="text-[10px] text-gray-400">Entire day, based on distributions only</p>
                                </div>
                            </div>
                            <p id="forecastedSalesTotal" class="text-sm font-bold text-primary">₱0.00</p>
                        </div>
                    </div>

                    <?php if ($isOwnerView): ?>
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="bg-white rounded-lg shadow-sm border border-emerald-100 p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500">Direct Cost (Day)</p>
                                    <p id="ownerDirectCostTotalDesktop" class="text-sm font-bold text-emerald-600">₱0.00</p>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                                    <i class="fas fa-calculator text-emerald-600 text-sm"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm border border-violet-100 p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500">Utilities (Day)</p>
                                    <p id="ownerUtilityCostTotalDesktop" class="text-sm font-bold text-violet-600">₱0.00</p>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center">
                                    <i class="fas fa-bolt text-violet-600 text-sm"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Distribution Groups Panel -->
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                <i class="fas fa-layer-group text-primary mr-1"></i>Distribution Groups
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
                                <i class="fas fa-layer-group text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-sm font-medium text-gray-800 mb-1">No distribution groups scheduled</h3>
                            <p class="text-xs text-gray-500">Click "Add" to add a distribution group</p>
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

            <?php if ($isOwnerView): ?>
            <div id="ownerAnalyticsPanel" class="mb-4 lg:mb-6">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">
                            <i class="fas fa-chart-line text-primary mr-1"></i>Distribution Analytics
                        </h3>
                        <span id="ownerGroupCountBadge" class="text-xs font-medium text-primary bg-primary/10 px-2 py-0.5 rounded-full">0 groups</span>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        <div class="rounded-lg border border-gray-100 p-3 bg-gray-50">
                            <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Raw Material Usage (Entire Day)</h4>
                            <div id="ownerDayRawMaterialUsage" class="space-y-1.5 max-h-[340px] overflow-y-auto">
                                <p class="text-xs text-gray-400">No raw material usage for this date.</p>
                            </div>
                        </div>

                        <div class="rounded-lg border border-gray-100 p-3 bg-gray-50">
                            <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Per-Group Forecast & Direct Cost</h4>
                            <div id="ownerGroupAnalyticsContainer" class="space-y-2 max-h-[340px] overflow-y-auto">
                                <p class="text-xs text-gray-400">No distribution groups yet.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Mobile Card View (shown below on mobile) -->
            <div class="lg:hidden mb-10" id="mobileCardSection">
                <!-- Date Header for Mobile -->
                <div class="bg-primary text-white rounded-lg p-3 mb-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs opacity-80">Distribution Groups</span>
                            <h3 id="mobileDateHeader" class="font-semibold"><?= date('F d, Y') ?></h3>
                        </div>
                        <div class="flex items-center gap-3 text-right">
                            <div>
                                <span class="text-2xl font-bold" id="mobileItemCount">0</span>
                                <span class="text-[10px] opacity-70 block">items</span>
                            </div>
                            <div class="w-px h-8 bg-white/30"></div>
                            <div>
                                <span class="text-lg font-bold" id="mobileBatchCount">0</span>
                                <span class="text-[10px] opacity-70 block">batches</span>
                            </div>
                            <div>
                                <span class="text-lg font-bold" id="mobilePiecesCount">0</span>
                                <span class="text-[10px] opacity-70 block">pieces</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Forecasted Sales (Mobile) -->
                <div class="bg-white rounded-lg p-3 mb-3 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                                <i class="fas fa-coins text-primary text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Forecasted Sales</p>
                                <p class="text-[10px] text-gray-400">Based on distributions only</p>
                            </div>
                        </div>
                        <p id="mobileForecastedSalesTotal" class="text-sm font-bold text-primary">₱0.00</p>
                    </div>
                </div>

                <?php if ($isOwnerView): ?>
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div class="bg-white rounded-lg p-3 border border-emerald-100 shadow-sm">
                        <p class="text-[10px] text-gray-500 uppercase tracking-wide">Direct Cost</p>
                        <p id="ownerDirectCostTotalMobile" class="text-sm font-bold text-emerald-600">₱0.00</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-violet-100 shadow-sm">
                        <p class="text-[10px] text-gray-500 uppercase tracking-wide">Utilities</p>
                        <p id="ownerUtilityCostTotalMobile" class="text-sm font-bold text-violet-600">₱0.00</p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Cards Container -->
                <div id="mobileCardsContainer" class="space-y-2">
                    <!-- Dynamically populated via JS -->
                </div>

                <!-- No results message -->
                <div id="mobileNoResults" class="hidden text-center py-8 text-gray-500">
                    <i class="fas fa-clipboard-list text-4xl mb-2 text-gray-300"></i>
                    <p>No distribution groups for this day</p>
                </div>
            </div>

            <!-- All Distributions List -->
            <div class="mb-24 lg:mb-0">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">
                            <i class="fas fa-list text-primary mr-1"></i>Upcoming Distributions
                        </h3>
                        <span id="allDistributionDatesCount" class="text-xs font-medium text-primary bg-primary/10 px-2 py-0.5 rounded-full">0 dates</span>
                    </div>

                    <div id="allDistributionsListContainer" class="space-y-2 max-h-[360px] overflow-y-auto">
                        <!-- Dynamically populated via JS -->
                    </div>

                    <div id="allDistributionsEmptyState" class="hidden text-center py-8 text-gray-500">
                        <div class="w-14 h-14 rounded-full bg-gray-100 mx-auto mb-2 flex items-center justify-center">
                            <i class="fas fa-calendar-times text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-700">No upcoming distributions found</p>
                        <p class="text-xs text-gray-500 mt-1">Add items for today or future dates to see them here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Day Modal -->
    <div id="calendarDayModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-2xl mx-auto p-4 sm:p-6 border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 id="calendarDayModalTitle" class="text-lg font-semibold text-primary">Distribution Groups</h3>
                    <p id="calendarDayModalDate" class="text-sm text-gray-500">January 15, 2026</p>
                </div>
                <button type="button" id="btnCloseCalendarDayModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Summary -->
            <div id="calendarDaySummaryCards" class="flex gap-3 mb-4">
                <div class="flex-1 bg-primary/10 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold text-primary" id="modalItemCount">0</p>
                    <p class="text-xs text-gray-600">Items</p>
                </div>
                <div class="flex-1 bg-gray-100 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold text-gray-700" id="modalBatchesCount">0</p>
                    <p class="text-xs text-gray-600">Batches</p>
                </div>
                <div class="flex-1 bg-blue-50 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold text-blue-600" id="modalPiecesCount">0</p>
                    <p class="text-xs text-gray-600">Pieces</p>
                </div>
            </div>

            
            <!-- Forecasted Sales (Modal) -->
            <div class="flex items-center justify-between p-2.5 bg-primary/10 border border-primary/20 rounded-lg mb-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-coins text-primary text-sm"></i>
                    <span class="text-xs text-gray-700">Forecasted Sales</span>
                </div>
                <span id="modalForecastedSalesTotal" class="text-sm font-bold text-primary">₱0.00</span>
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
        <div class="relative w-full max-w-2xl mx-auto p-4 sm:p-6 border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
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

                <div class="mb-4 p-3 border border-gray-200 rounded-lg bg-gray-50">
                    <div class="mb-3">
                        <label for="distributionGroupName" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-layer-group text-primary mr-1"></i>Distribution Group Name
                            <span class="text-xs text-gray-400 font-normal ml-1">(optional)</span>
                        </label>
                        <input type="text" id="distributionGroupName" maxlength="191"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm"
                            placeholder="e.g. Morning Batch, Outlet A, Group 1">
                        <p class="mt-1 text-[11px] text-gray-500">Leave blank to auto-name this group.</p>
                    </div>

                    <div>
                        <label for="overallDistributionNote" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-sticky-note text-primary mr-1"></i>Group Note
                            <span class="text-xs text-gray-400 font-normal ml-1">(optional)</span>
                        </label>
                        <textarea id="overallDistributionNote" name="overall_note" rows="2" maxlength="500"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm resize-none"
                            placeholder="e.g. Rush delivery for all branches, special occasion order..."></textarea>
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

                    <!-- Product Info (pieces per yield) -->
                    <div id="productYieldInfo" class="hidden mb-3 p-2 bg-blue-50 border border-blue-200 rounded-md">
                        <div class="flex items-center gap-2 text-sm text-blue-700">
                            <i class="fas fa-info-circle"></i>
                            <span>1 batch = <strong id="piecesPerYieldDisplay">0</strong> pieces</span>
                        </div>
                    </div>

                    <!-- Quantity Mode & Quantity (shown after product is selected) -->
                    <div id="qtyModeSection" class="hidden">

                    <!-- Quantity Mode Toggle -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity Per</label>
                        <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                            <button type="button" id="btnModeBatch" class="qty-mode-btn flex-1 px-3 py-2 text-sm font-medium bg-primary text-white transition-colors" data-mode="batch">
                                <i class="fas fa-boxes mr-1"></i>Batch
                            </button>
                            <button type="button" id="btnModeBox" class="qty-mode-btn flex-1 px-3 py-2 text-sm font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors" data-mode="box">
                                <i class="fas fa-box-open mr-1"></i>Box
                            </button>
                            <button type="button" id="btnModePieces" class="qty-mode-btn flex-1 px-3 py-2 text-sm font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors" data-mode="pieces">
                                <i class="fas fa-puzzle-piece mr-1"></i>Piece
                            </button>
                        </div>
                        <input type="hidden" id="selectedQtyMode" value="batch">
                    </div>

                    <!-- Quantity -->
                    <div class="mb-3">
                        <label id="addQtyLabel" class="block text-sm font-medium text-gray-700 mb-1">Quantity (per batch) <span class="text-red-500">*</span></label>
                        <input type="number" id="addProductQty" min="1" value="10" step="0.00001"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="10">
                        <p id="piecesConversionHint" class="hidden mt-1 text-xs text-gray-500">
                            <i class="fas fa-calculator mr-1"></i><span id="conversionText"></span>
                        </p>
                    </div>

                    </div><!-- /qtyModeSection -->

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
                <input type="hidden" id="editItemQtyMode" name="qty_mode" value="batch">

                <div class="text-center mb-4">
                    <div class="w-16 h-16 rounded-full bg-primary/10 mx-auto mb-2 flex items-center justify-center">
                        <i class="fas fa-bread-slice text-primary text-2xl"></i>
                    </div>
                    <h4 id="editProductName" class="font-semibold text-gray-800">Spanish Bread</h4>
                    <span id="editQtyModeBadge" class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full bg-primary/10 text-primary">batch</span>
                </div>

                <div class="mb-6">
                    <label for="editQuantity" id="editQtyLabel" class="block text-sm font-medium text-gray-700 mb-2 text-center">
                        Quantity (per batch)
                    </label>
                    <div class="flex items-center justify-center gap-3">
                        <button type="button" id="btnEditQtyDec"
                            class="w-12 h-12 flex items-center justify-center border border-gray-300 bg-gray-100 text-gray-700 text-xl font-semibold rounded-lg hover:bg-gray-200">
                            -
                        </button>
                        <input type="number" id="editQuantity" name="quantity" min="1" value="10" step="0.00001" required
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
        const isOwnerView = <?= $isOwnerView ? 'true' : 'false' ?>;
        let productsData = []; // Store fetched products (global scope for template function)
        let productCostMap = {}; // Product pricing/cost details keyed by product_id
        let productDetailCache = {}; // Full product detail cache (ingredients + combined recipes)
        let productDetailPromiseCache = {}; // In-flight product detail requests
        let utilityExpensesByDate = {}; // Utility totals keyed by billed date
        let ownerRawUsageHydrationToken = 0; // Prevent stale async owner analytics updates
        let calendarData = {}; // Store distribution data keyed by date
        let allDistributionData = {}; // Store all distribution records keyed by date
        let currentDayDistributionItems = []; // Store current selected date distribution items
        let currentDayGroupedData = []; // Grouped analytics for selected date
        let currentDaySummary = {}; // Day analytics summary for selected date
        let selectedGroupFilter = { date: '', key: '' }; // Active selected group scope for analytics cards/panels
        let currentCalendarMonth = new Date().getMonth();
        let currentCalendarYear = new Date().getFullYear();
        const distributionGroupStorageKey = 'engbakery_distribution_group_meta_v1';

        $(document).ready(function() {

            baseUrl = '<?= base_url() ?>';

            getProducts();
            loadProductCostData();
            loadUtilityExpenses();
            loadDistributionByDate();
            renderCalendar();
            loadMonthDistributions();
            loadAllDistributions();

            // ===== API FUNCTIONS =====

            function getProducts() {
                $.ajax({
                    url: baseUrl + 'Distribution/GetProducts',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data) {
                            productsData = response.data;
                            mergeProductCostRecords(productsData);
                        }

                        const selectedDate = ($('#selectedDate').val() || '').toString();
                        const displayState = getDisplayStateForSelectedGroup(
                            selectedDate,
                            currentDayDistributionItems,
                            currentDayGroupedData,
                            currentDaySummary
                        );

                        updateSummaryCounts(displayState.items, displayState.summary, selectedDate);
                        updateForecastedSales(displayState.items, displayState.summary);
                        renderOwnerDayMetrics(displayState.summary);
                        renderOwnerAnalytics(displayState.groups, displayState.summary);

                        renderAllDistributionsList();

                        if (!$('#calendarDayModal').hasClass('hidden')) {
                            const modalItems = $('#calendarDayModal').data('day-items') || [];
                            const modalSummary = $('#calendarDayModal').data('day-summary') || null;
                            updateModalForecastedSales(modalItems, modalSummary);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching products:', error);
                    }
                });
            }

            function mergeProductCostRecords(records) {
                (Array.isArray(records) ? records : []).forEach(function(record) {
                    const productId = String(record.product_id || '').trim();
                    if (!productId) return;

                    productCostMap[productId] = Object.assign({}, productCostMap[productId] || {}, record);
                });
            }

            function loadProductCostData() {
                $.ajax({
                    url: baseUrl + 'Products/GetAll',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.success && Array.isArray(response.data)) {
                            mergeProductCostRecords(response.data);
                        }

                        if (currentDayDistributionItems.length > 0) {
                            loadDistributionByDate();
                        } else {
                            renderAllDistributionsList();
                        }
                    },
                    error: function() {
                        // Keep using lightweight product data fallback.
                    }
                });
            }

            function loadUtilityExpenses() {
                $.ajax({
                    url: baseUrl + 'Utility/GetUtilityExpenses',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        utilityExpensesByDate = {};

                        if (response && response.success && Array.isArray(response.data)) {
                            response.data.forEach(function(expense) {
                                const billedDate = (expense.billed_at || '').toString().slice(0, 10);
                                if (!billedDate) return;

                                utilityExpensesByDate[billedDate] = (utilityExpensesByDate[billedDate] || 0)
                                    + parseNumericValue(expense.expense);
                            });
                        }

                        const selectedDate = ($('#selectedDate').val() || '').toString();
                        if (selectedDate && currentDaySummary) {
                            currentDaySummary.utilities_expense_total = getUtilityExpenseForDate(selectedDate);

                            const displayState = getDisplayStateForSelectedGroup(
                                selectedDate,
                                currentDayDistributionItems,
                                currentDayGroupedData,
                                currentDaySummary
                            );
                            displayState.summary.utilities_expense_total = getUtilityExpenseForDate(selectedDate);

                            renderOwnerDayMetrics(displayState.summary);
                        }
                    },
                    error: function() {
                        utilityExpensesByDate = {};
                    }
                });
            }

            function getUtilityExpenseForDate(dateValue) {
                const dateKey = (dateValue || '').toString().slice(0, 10);
                if (!dateKey) return 0;
                return parseNumericValue(utilityExpensesByDate[dateKey]);
            }

            function getProductAnalyticsData(productId) {
                const key = String(productId || '').trim();
                if (!key) return null;

                if (productCostMap[key]) {
                    return productCostMap[key];
                }

                const fromList = productsData.find(function(product) {
                    return String(product.product_id) === key;
                });

                return fromList || null;
            }

            function getProductPiecesPerYield(product) {
                const parsed = parseNumericValue(product && product.pieces_per_yield);
                return parsed > 0 ? parsed : 1;
            }

            function getDistributionPieces(item, product) {
                const quantity = parseNumericValue(item && item.product_qnty);
                const qtyMode = ((item && item.qty_mode) || 'batch').toLowerCase();
                const category = ((product && product.category) || '').toLowerCase();
                const piecesPerYield = getProductPiecesPerYield(product);

                if (qtyMode === 'pieces') {
                    return quantity;
                }

                if (category === 'drinks' || category === 'grocery') {
                    return quantity;
                }

                return quantity * piecesPerYield;
            }

            function calculateItemDirectCost(item, product) {
                const directCostPerYield = parseNumericValue(product && product.direct_cost);
                if (directCostPerYield <= 0) return 0;

                const pieces = getDistributionPieces(item, product);
                const piecesPerYield = getProductPiecesPerYield(product);
                const yieldsNeeded = pieces / piecesPerYield;

                return yieldsNeeded > 0 ? (yieldsNeeded * directCostPerYield) : 0;
            }

            function decorateDistributionItems(items, fallbackDate = '') {
                const normalizedItems = applyLocalDistributionGroupMeta(items, fallbackDate);

                return normalizedItems.map(function(item) {
                    const decoratedItem = Object.assign({}, item);
                    const productData = getProductAnalyticsData(decoratedItem.product_id);
                    const quantity = parseNumericValue(decoratedItem.product_qnty);

                    const explicitForecast = parseNumericValue(decoratedItem.forecasted_sales);
                    const computedForecast = quantity * getForecastUnitPrice(productData, decoratedItem.qty_mode || 'batch');
                    decoratedItem.forecasted_sales = explicitForecast > 0 ? explicitForecast : computedForecast;

                    const explicitDirectCost = parseNumericValue(decoratedItem.direct_cost);
                    decoratedItem.direct_cost = explicitDirectCost > 0
                        ? explicitDirectCost
                        : calculateItemDirectCost(decoratedItem, productData);

                    if (!Array.isArray(decoratedItem.raw_material_usage)) {
                        decoratedItem.raw_material_usage = [];
                    }

                    return decoratedItem;
                });
            }

            function extractDistributionNote(items, fallbackNote = '') {
                const fallback = (fallbackNote || '').toString().trim();
                if (fallback) return fallback;

                if (!Array.isArray(items) || items.length === 0) return '';

                const noteFields = [
                    'distribution_note',
                    'overall_note',
                    'distribution_group_note',
                    'note',
                    'item_note',
                    'distributed_note',
                    'place_distributed_to',
                    'place_distributed'
                ];

                for (const item of items) {
                    const localGroupNote = getDistributionGroupNote(item);
                    if (localGroupNote) return localGroupNote;

                    for (const key of noteFields) {
                        const value = (item && item[key] != null) ? String(item[key]).trim() : '';
                        if (value) return value;
                    }
                }

                return '';
            }

            function updateMainDistributionNotePanels(items, fallbackNote = '') {
                const note = extractDistributionNote(items, fallbackNote);

                if (note) {
                    $('#distributionNoteText').text(note);
                    $('#mobileDistributionNoteText').text(note);
                    $('#distributionNotePanel').removeClass('hidden');
                    $('#mobileDistributionNotePanel').removeClass('hidden');
                } else {
                    $('#distributionNoteText').text('');
                    $('#mobileDistributionNoteText').text('');
                    $('#distributionNotePanel').addClass('hidden');
                    $('#mobileDistributionNotePanel').addClass('hidden');
                }
            }

            function updateCalendarDayNotePanel(items, fallbackNote = '') {
                const note = extractDistributionNote(items, fallbackNote);

                if (note) {
                    $('#calendarDayNoteText').text(note);
                    $('#calendarDayNotePanel').removeClass('hidden');
                } else {
                    $('#calendarDayNoteText').text('');
                    $('#calendarDayNotePanel').addClass('hidden');
                }
            }

            function getQtyModeLabel(mode) {
                if (mode === 'pieces') return 'piece';
                if (mode === 'box') return 'box';
                return 'batch';
            }

            function getQtyModeShortLabel(mode) {
                if (mode === 'pieces') return 'pcs';
                if (mode === 'box') return 'box';
                return 'batch';
            }

            function getQtyModeBadgeColor(mode) {
                if (mode === 'pieces') return 'bg-blue-100 text-blue-700';
                if (mode === 'box') return 'bg-amber-100 text-amber-700';
                return 'bg-gray-200 text-gray-600';
            }

            function getStoredDistributionGroupMeta() {
                try {
                    const raw = localStorage.getItem(distributionGroupStorageKey);
                    if (!raw) return {};
                    const parsed = JSON.parse(raw);
                    return (parsed && typeof parsed === 'object') ? parsed : {};
                } catch (error) {
                    return {};
                }
            }

            function saveStoredDistributionGroupMeta(meta) {
                try {
                    localStorage.setItem(distributionGroupStorageKey, JSON.stringify(meta || {}));
                } catch (error) {
                    console.warn('Unable to persist distribution group metadata.', error);
                }
            }

            function getLocalDistributionGroupMeta(dateValue, productId) {
                const dateKey = (dateValue || '').toString().trim();
                const productKey = String(productId || '').trim();
                if (!dateKey || !productKey) return null;

                const allMeta = getStoredDistributionGroupMeta();
                const dateMeta = allMeta[dateKey] || {};
                return dateMeta['product_' + productKey] || null;
            }

            function setLocalDistributionGroupMetaForItems(dateValue, productIds, groupMeta) {
                const dateKey = (dateValue || '').toString().trim();
                if (!dateKey || !Array.isArray(productIds) || productIds.length === 0) return;

                const allMeta = getStoredDistributionGroupMeta();
                if (!allMeta[dateKey] || typeof allMeta[dateKey] !== 'object') {
                    allMeta[dateKey] = {};
                }

                productIds.forEach(function(productId) {
                    const productKey = String(productId || '').trim();
                    if (!productKey) return;

                    allMeta[dateKey]['product_' + productKey] = {
                        group_key: (groupMeta.group_key || '').toString(),
                        group_name: (groupMeta.group_name || '').toString() || 'Default Group',
                        group_note: (groupMeta.group_note || '').toString()
                    };
                });

                saveStoredDistributionGroupMeta(allMeta);
            }

            function removeLocalDistributionGroupMeta(dateValue, productId) {
                const dateKey = (dateValue || '').toString().trim();
                const productKey = String(productId || '').trim();
                if (!dateKey || !productKey) return;

                const allMeta = getStoredDistributionGroupMeta();
                const dateMeta = allMeta[dateKey];
                if (!dateMeta || typeof dateMeta !== 'object') return;

                delete dateMeta['product_' + productKey];

                if (Object.keys(dateMeta).length === 0) {
                    delete allMeta[dateKey];
                }

                saveStoredDistributionGroupMeta(allMeta);
            }

            function buildClientGroupKey(dateValue, groupName) {
                const safeDate = (dateValue || '').toString().replace(/[^0-9]/g, '') || 'date';
                const safeName = (groupName || 'group')
                    .toString()
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '') || 'group';

                return `local-${safeDate}-${safeName}-${Date.now()}-${Math.floor(Math.random() * 1000)}`;
            }

            function getNextAutoGroupName(dateValue) {
                const dateKey = (dateValue || '').toString().trim();
                if (!dateKey) return 'Group 1';

                const allMeta = getStoredDistributionGroupMeta();
                const dateMeta = allMeta[dateKey] || {};
                const usedNames = new Set(
                    Object.values(dateMeta)
                        .map(meta => (meta && meta.group_name ? String(meta.group_name).trim() : ''))
                        .filter(Boolean)
                );

                let sequence = 1;
                while (usedNames.has(`Group ${sequence}`)) {
                    sequence += 1;
                }

                return `Group ${sequence}`;
            }

            function applyLocalDistributionGroupMeta(items, fallbackDate = '') {
                return (Array.isArray(items) ? items : []).map(function(item) {
                    const enrichedItem = Object.assign({}, item);
                    const dateValue = ((enrichedItem && enrichedItem.distribution_date) || fallbackDate || '').toString().trim();
                    const productId = enrichedItem ? enrichedItem.product_id : null;
                    const localMeta = getLocalDistributionGroupMeta(dateValue, productId);

                    if (localMeta) {
                        if (!enrichedItem.distribution_group_key) {
                            enrichedItem.distribution_group_key = localMeta.group_key;
                        }
                        if (!enrichedItem.distribution_group_name) {
                            enrichedItem.distribution_group_name = localMeta.group_name;
                        }
                        if (!enrichedItem.distribution_group_note) {
                            enrichedItem.distribution_group_note = localMeta.group_note;
                        }
                    }

                    return enrichedItem;
                });
            }

            function getDistributionGroupKey(item, fallbackDate = '') {
                const explicit = ((item && item.distribution_group_key) || '').toString().trim();
                if (explicit) return explicit;

                const dateValue = ((item && item.distribution_date) || fallbackDate || '').toString().trim();
                const localMeta = getLocalDistributionGroupMeta(dateValue, item && item.product_id);
                if (localMeta && localMeta.group_key) {
                    return String(localMeta.group_key).trim();
                }

                return dateValue ? ('legacy-' + dateValue) : 'legacy-unknown';
            }

            function getDistributionGroupName(item, fallbackDate = '') {
                const explicit = ((item && item.distribution_group_name) || '').toString().trim();
                if (explicit) return explicit;

                const dateValue = ((item && item.distribution_date) || fallbackDate || '').toString().trim();
                const localMeta = getLocalDistributionGroupMeta(dateValue, item && item.product_id);
                if (localMeta && localMeta.group_name) {
                    return String(localMeta.group_name).trim();
                }

                return dateValue ? 'Default Group' : 'Ungrouped';
            }

            function getDistributionGroupNote(item) {
                const explicit = ((item && item.distribution_group_note) || '').toString().trim();
                if (explicit) return explicit;

                const dateValue = ((item && item.distribution_date) || '').toString().trim();
                const localMeta = getLocalDistributionGroupMeta(dateValue, item && item.product_id);
                if (localMeta && localMeta.group_note) {
                    return String(localMeta.group_note).trim();
                }

                return '';
            }

            function getDistinctGroupCount(items, fallbackDate = '') {
                const set = new Set();
                (items || []).forEach(function(item) {
                    set.add(getDistributionGroupKey(item, fallbackDate));
                });
                return set.size;
            }

            function groupDistributionsByGroup(items, fallbackDate = '') {
                const groupedMap = {};

                (items || []).forEach(function(item) {
                    const groupKey = getDistributionGroupKey(item, fallbackDate);

                    if (!groupedMap[groupKey]) {
                        groupedMap[groupKey] = {
                            group_key: groupKey,
                            group_name: getDistributionGroupName(item, fallbackDate),
                            group_note: getDistributionGroupNote(item),
                            total_items: 0,
                            total_batches: 0,
                            total_pieces: 0,
                            forecasted_sales: 0,
                            direct_cost: 0,
                            raw_material_usage_total: [],
                            _raw_material_usage_map: {},
                            items: [],
                        };
                    }

                    const quantity = parseNumericValue(item.product_qnty);
                    const qtyMode = (item.qty_mode || 'batch').toLowerCase();

                    groupedMap[groupKey].total_items += 1;
                    if (qtyMode === 'pieces') {
                        groupedMap[groupKey].total_pieces += quantity;
                    } else {
                        groupedMap[groupKey].total_batches += quantity;
                    }

                    const fallbackForecast = quantity * getForecastUnitPrice(
                        getProductAnalyticsData(item.product_id),
                        qtyMode
                    );

                    groupedMap[groupKey].forecasted_sales += parseNumericValue(item.forecasted_sales) || fallbackForecast;
                    groupedMap[groupKey].direct_cost += parseNumericValue(item.direct_cost);

                    (Array.isArray(item.raw_material_usage) ? item.raw_material_usage : []).forEach(function(material) {
                        mergeMaterialUsageEntry(groupedMap[groupKey]._raw_material_usage_map, material);
                    });

                    groupedMap[groupKey].items.push(item);
                });

                return Object.values(groupedMap).map(function(group) {
                    const normalizedGroup = Object.assign({}, group);
                    normalizedGroup.raw_material_usage_total = materialUsageMapToArray(normalizedGroup._raw_material_usage_map);
                    delete normalizedGroup._raw_material_usage_map;
                    return normalizedGroup;
                });
            }

            function normalizeGroupedData(items, groupedData, fallbackDate = '') {
                if (Array.isArray(groupedData) && groupedData.length > 0) {
                    return groupedData;
                }
                return groupDistributionsByGroup(items, fallbackDate);
            }

            function buildGroupScopedSummary(group, items, dateStr) {
                const groupItems = Array.isArray(items) ? items : [];

                const totalBatches = group
                    ? parseNumericValue(group.total_batches)
                    : groupItems.reduce(function(sum, item) {
                        return sum + (((item.qty_mode || 'batch') !== 'pieces') ? parseNumericValue(item.product_qnty) : 0);
                    }, 0);

                const totalPieces = group
                    ? parseNumericValue(group.total_pieces)
                    : groupItems.reduce(function(sum, item) {
                        return sum + (((item.qty_mode || 'batch') === 'pieces') ? parseNumericValue(item.product_qnty) : 0);
                    }, 0);

                const forecastTotal = group
                    ? (parseNumericValue(group.forecasted_sales) || calculateForecastedSalesTotal(groupItems))
                    : calculateForecastedSalesTotal(groupItems);

                const directCostTotal = group
                    ? parseNumericValue(group.direct_cost)
                    : groupItems.reduce(function(sum, item) {
                        return sum + parseNumericValue(item.direct_cost);
                    }, 0);

                return {
                    total_items: groupItems.length,
                    total_groups: groupItems.length > 0 ? 1 : 0,
                    total_batches: totalBatches,
                    total_pieces: totalPieces,
                    forecasted_sales_total: forecastTotal,
                    direct_cost_total: directCostTotal,
                    utilities_expense_total: getUtilityExpenseForDate(dateStr),
                    raw_material_usage_total: Array.isArray(group && group.raw_material_usage_total)
                        ? group.raw_material_usage_total
                        : [],
                };
            }

            function setSelectedGroupFilter(dateStr, groupKey) {
                selectedGroupFilter = {
                    date: (dateStr || '').toString().trim(),
                    key: (groupKey || '').toString().trim(),
                };
            }

            function clearSelectedGroupFilter(dateStr = null) {
                if (dateStr == null) {
                    selectedGroupFilter = { date: '', key: '' };
                    return;
                }

                const normalizedDate = (dateStr || '').toString().trim();
                if ((selectedGroupFilter.date || '') === normalizedDate) {
                    selectedGroupFilter = { date: '', key: '' };
                }
            }

            function getDisplayStateForSelectedGroup(dateStr, items, groupedData, summary) {
                const normalizedDate = (dateStr || '').toString().trim();
                const normalizedItems = Array.isArray(items) ? items : [];
                const normalizedGroups = normalizeGroupedData(normalizedItems, groupedData, normalizedDate);
                const normalizedSummary = (summary && typeof summary === 'object') ? summary : {};

                const activeDate = (selectedGroupFilter.date || '').toString().trim();
                const activeKey = (selectedGroupFilter.key || '').toString().trim();

                if (!activeDate || !activeKey || activeDate !== normalizedDate) {
                    return {
                        items: normalizedItems,
                        groups: normalizedGroups,
                        summary: normalizedSummary,
                    };
                }

                const matchedGroup = normalizedGroups.find(function(group) {
                    return String(group.group_key || '').trim() === activeKey;
                });

                if (!matchedGroup) {
                    clearSelectedGroupFilter(normalizedDate);
                    return {
                        items: normalizedItems,
                        groups: normalizedGroups,
                        summary: normalizedSummary,
                    };
                }

                const matchedItems = Array.isArray(matchedGroup.items) ? matchedGroup.items : [];
                const matchedSummary = buildGroupScopedSummary(matchedGroup, matchedItems, normalizedDate);

                return {
                    items: matchedItems,
                    groups: [matchedGroup],
                    summary: matchedSummary,
                };
            }

            function formatMaterialAmount(amount) {
                const value = parseNumericValue(amount);
                return Number.isFinite(value)
                    ? value.toLocaleString('en-PH', { minimumFractionDigits: 0, maximumFractionDigits: 4 })
                    : '0';
            }

            function renderMaterialUsageList(materials) {
                if (!Array.isArray(materials) || materials.length === 0) {
                    return '<p class="text-[11px] text-gray-400">No material usage data.</p>';
                }

                return materials.map(function(material) {
                    const amount = formatMaterialAmount(material.amount);
                    const unit = (material.unit || '').toString().trim();
                    const lineCost = parseNumericValue(material.line_cost);
                    return `
                        <div class="flex items-center justify-between text-[11px] text-gray-600">
                            <span class="truncate pr-2">${material.material_name}: ${amount}${unit ? ' ' + unit : ''}</span>
                            <span class="font-medium text-gray-700">${formatPesoAmount(lineCost)}</span>
                        </div>
                    `;
                }).join('');
            }

            function getMaterialAggregateKey(materialId, materialName, unit) {
                if (materialId != null && materialId !== '') {
                    return `id-${materialId}-${(unit || '').toString().trim().toLowerCase()}`;
                }

                return `name-${(materialName || 'unknown').toString().trim().toLowerCase()}-${(unit || '').toString().trim().toLowerCase()}`;
            }

            function mergeMaterialUsageEntry(materialMap, usageEntry) {
                if (!usageEntry || !materialMap) return;

                const materialKey = getMaterialAggregateKey(
                    usageEntry.material_id,
                    usageEntry.material_name,
                    usageEntry.unit
                );

                if (!materialMap[materialKey]) {
                    materialMap[materialKey] = {
                        material_id: usageEntry.material_id,
                        material_name: usageEntry.material_name || 'Unknown Material',
                        unit: usageEntry.unit || '',
                        amount: 0,
                        line_cost: 0,
                    };
                }

                materialMap[materialKey].amount += parseNumericValue(usageEntry.amount);
                materialMap[materialKey].line_cost += parseNumericValue(usageEntry.line_cost);
            }

            function materialUsageMapToArray(materialMap) {
                return Object.values(materialMap || {}).sort(function(a, b) {
                    return String(a.material_name || '').localeCompare(String(b.material_name || ''));
                });
            }

            function fetchProductDetail(productId) {
                const key = String(productId || '').trim();
                if (!key) {
                    return Promise.resolve(null);
                }

                if (productDetailCache[key]) {
                    return Promise.resolve(productDetailCache[key]);
                }

                if (productDetailPromiseCache[key]) {
                    return productDetailPromiseCache[key];
                }

                productDetailPromiseCache[key] = new Promise(function(resolve) {
                    $.ajax({
                        url: baseUrl + 'Products/GetProduct/' + key,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response && response.success && response.data) {
                                const productData = response.data;
                                productDetailCache[key] = productData;
                                mergeProductCostRecords([productData]);
                                resolve(productData);
                                return;
                            }

                            resolve(null);
                        },
                        error: function() {
                            resolve(null);
                        },
                        complete: function() {
                            delete productDetailPromiseCache[key];
                        }
                    });
                });

                return productDetailPromiseCache[key];
            }

            async function accumulateRawMaterialUsage(productId, yieldsNeeded, piecesNeeded, materialMap, visitedProducts = new Set()) {
                const key = String(productId || '').trim();
                if (!key || yieldsNeeded <= 0) return;
                if (visitedProducts.has(key)) return;

                const currentProduct = await fetchProductDetail(key);
                if (!currentProduct) return;

                const nextVisited = new Set(visitedProducts);
                nextVisited.add(key);

                const ingredients = Array.isArray(currentProduct.ingredients) ? currentProduct.ingredients : [];
                ingredients.forEach(function(ingredient) {
                    const quantityPerYield = parseNumericValue(ingredient.quantity ?? ingredient.quantity_needed);
                    if (quantityPerYield <= 0) return;

                    const amount = quantityPerYield * yieldsNeeded;
                    const lineCost = amount * parseNumericValue(ingredient.cost_per_unit);

                    mergeMaterialUsageEntry(materialMap, {
                        material_id: ingredient.material_id,
                        material_name: ingredient.material_name || ('Material #' + (ingredient.material_id ?? 'N/A')),
                        unit: ingredient.unit || '',
                        amount: amount,
                        line_cost: lineCost,
                    });
                });

                const combinedRecipes = Array.isArray(currentProduct.combined_recipes) ? currentProduct.combined_recipes : [];

                for (const combinedRecipe of combinedRecipes) {
                    const sourceProductId = parseNumericValue(combinedRecipe.source_product_id || combinedRecipe.id);
                    if (!sourceProductId) continue;

                    const gramsPerPiece = parseNumericValue(combinedRecipe.grams_per_piece ?? combinedRecipe.gramsPerPiece);
                    if (gramsPerPiece <= 0 || piecesNeeded <= 0) continue;

                    const sourceProduct = await fetchProductDetail(sourceProductId);
                    if (!sourceProduct) continue;

                    const sourceYieldGrams = parseNumericValue(sourceProduct.yield_grams);
                    if (sourceYieldGrams <= 0) continue;

                    const totalGramsNeeded = gramsPerPiece * piecesNeeded;
                    const sourceYieldsNeeded = totalGramsNeeded / sourceYieldGrams;
                    const sourcePiecesPerYield = getProductPiecesPerYield(sourceProduct);
                    const sourcePiecesNeeded = sourceYieldsNeeded * sourcePiecesPerYield;

                    await accumulateRawMaterialUsage(
                        sourceProductId,
                        sourceYieldsNeeded,
                        sourcePiecesNeeded,
                        materialMap,
                        nextVisited
                    );
                }
            }

            async function computeRawMaterialUsageForItem(item) {
                const productData = getProductAnalyticsData(item.product_id);
                if (!productData) return [];

                const pieces = getDistributionPieces(item, productData);
                if (pieces <= 0) return [];

                const piecesPerYield = getProductPiecesPerYield(productData);
                const yieldsNeeded = pieces / piecesPerYield;
                if (yieldsNeeded <= 0) return [];

                const materialMap = {};
                await accumulateRawMaterialUsage(item.product_id, yieldsNeeded, pieces, materialMap);
                return materialUsageMapToArray(materialMap);
            }

            async function hydrateOwnerRawMaterialAnalytics(selectedDate, decoratedItems, summaryTemplate = {}) {
                if (!isOwnerView) return;

                const targetDate = (selectedDate || '').toString().trim();
                if (!targetDate) return;

                if (!Array.isArray(decoratedItems) || decoratedItems.length === 0) {
                    const emptySummary = Object.assign({}, summaryTemplate, { raw_material_usage_total: [] });
                    currentDaySummary = emptySummary;

                    const emptyDisplayState = getDisplayStateForSelectedGroup(targetDate, [], [], emptySummary);
                    updateSummaryCounts(emptyDisplayState.items, emptyDisplayState.summary, targetDate);
                    updateForecastedSales(emptyDisplayState.items, emptyDisplayState.summary);
                    renderOwnerDayMetrics(emptyDisplayState.summary);
                    renderOwnerAnalytics(emptyDisplayState.groups, emptyDisplayState.summary);
                    return;
                }

                const requestToken = ++ownerRawUsageHydrationToken;

                const usagePromises = decoratedItems.map(async function(item) {
                    try {
                        const usage = await computeRawMaterialUsageForItem(item);
                        return Object.assign({}, item, { raw_material_usage: usage });
                    } catch (error) {
                        return Object.assign({}, item, { raw_material_usage: [] });
                    }
                });

                const ownerDecoratedItems = await Promise.all(usagePromises);

                if (requestToken !== ownerRawUsageHydrationToken) {
                    return;
                }

                if (($('#selectedDate').val() || '').toString().trim() !== targetDate) {
                    return;
                }

                const dayMaterialMap = {};
                ownerDecoratedItems.forEach(function(item) {
                    (Array.isArray(item.raw_material_usage) ? item.raw_material_usage : []).forEach(function(material) {
                        mergeMaterialUsageEntry(dayMaterialMap, material);
                    });
                });

                const ownerSummary = Object.assign({}, summaryTemplate, {
                    raw_material_usage_total: materialUsageMapToArray(dayMaterialMap),
                });

                currentDayDistributionItems = ownerDecoratedItems;
                currentDayGroupedData = groupDistributionsByGroup(ownerDecoratedItems, targetDate);
                currentDaySummary = ownerSummary;

                const displayState = getDisplayStateForSelectedGroup(
                    targetDate,
                    currentDayDistributionItems,
                    currentDayGroupedData,
                    currentDaySummary
                );

                updateSummaryCounts(displayState.items, displayState.summary, targetDate);
                updateForecastedSales(displayState.items, displayState.summary);
                renderOwnerDayMetrics(displayState.summary);
                renderOwnerAnalytics(displayState.groups, displayState.summary);

                if (!$('#calendarDayModal').hasClass('hidden') && $('#calendarDayModal').data('selected-date') === targetDate) {
                    $('#calendarDayModal').data('day-items', ownerDecoratedItems);
                    $('#calendarDayModal').data('day-summary', ownerSummary);
                    updateModalForecastedSales(ownerDecoratedItems, ownerSummary);
                }
            }

            function renderOwnerDayMetrics(summary) {
                if (!isOwnerView) return;

                const directCost = parseNumericValue(summary.direct_cost_total);
                const utilitiesCost = parseNumericValue(summary.utilities_expense_total);

                $('#ownerDirectCostTotalDesktop').text(formatPesoAmount(directCost));
                $('#ownerUtilityCostTotalDesktop').text(formatPesoAmount(utilitiesCost));
                $('#ownerDirectCostTotalMobile').text(formatPesoAmount(directCost));
                $('#ownerUtilityCostTotalMobile').text(formatPesoAmount(utilitiesCost));
            }

            function renderOwnerAnalytics(groups, summary) {
                if (!isOwnerView) return;

                const normalizedGroups = Array.isArray(groups) ? groups : [];
                const groupContainer = $('#ownerGroupAnalyticsContainer');
                const materialsContainer = $('#ownerDayRawMaterialUsage');

                $('#ownerGroupCountBadge').text(`${normalizedGroups.length} ${normalizedGroups.length === 1 ? 'group' : 'groups'}`);

                const dayMaterials = Array.isArray(summary.raw_material_usage_total)
                    ? summary.raw_material_usage_total
                    : [];

                if (dayMaterials.length === 0) {
                    materialsContainer.html('<p class="text-xs text-gray-400">No raw material usage for this date.</p>');
                } else {
                    materialsContainer.html(dayMaterials.map(function(material) {
                        return `
                            <div class="flex items-center justify-between text-xs text-gray-700 border-b border-gray-100 pb-1">
                                <span class="truncate pr-2">${material.material_name}: ${formatMaterialAmount(material.amount)}${material.unit ? ' ' + material.unit : ''}</span>
                                <span class="font-semibold text-gray-800">${formatPesoAmount(parseNumericValue(material.line_cost))}</span>
                            </div>
                        `;
                    }).join(''));
                }

                if (normalizedGroups.length === 0) {
                    groupContainer.html('<p class="text-xs text-gray-400">No distribution groups yet.</p>');
                    return;
                }

                const html = normalizedGroups.map(function(group) {
                    const groupItems = Array.isArray(group.items) ? group.items : [];
                    const groupForecast = parseNumericValue(group.forecasted_sales);
                    const groupDirect = parseNumericValue(group.direct_cost);
                    const groupNote = (group.group_note || '').toString().trim();

                    const itemsHtml = groupItems.map(function(item) {
                        const quantity = parseNumericValue(item.product_qnty);
                        const itemForecast = parseNumericValue(item.forecasted_sales) || (quantity * getForecastUnitPrice(
                            getProductAnalyticsData(item.product_id),
                            item.qty_mode || 'batch'
                        ));
                        const itemDirect = parseNumericValue(item.direct_cost);

                        return `
                            <div class="p-2 bg-gray-50 rounded-md border border-gray-100">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-gray-800 truncate">${item.product_name}</p>
                                        <p class="text-[11px] text-gray-500">${quantity} ${getQtyModeShortLabel(item.qty_mode || 'batch')}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[11px] text-primary font-semibold">${formatPesoAmount(itemForecast)}</p>
                                        <p class="text-[11px] text-emerald-600 font-semibold">${formatPesoAmount(itemDirect)}</p>
                                    </div>
                                </div>
                                <div class="mt-1.5 pl-1 border-l-2 border-primary/20">
                                    ${renderMaterialUsageList(item.raw_material_usage || [])}
                                </div>
                            </div>
                        `;
                    }).join('');

                    return `
                        <div class="p-2.5 bg-white rounded-lg border border-gray-200">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-800 truncate">${group.group_name || 'Default Group'}</p>
                                    <p class="text-[11px] text-gray-500">${group.total_items || groupItems.length} item(s)</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[11px] font-semibold text-primary">${formatPesoAmount(groupForecast)}</p>
                                    <p class="text-[11px] font-semibold text-emerald-600">${formatPesoAmount(groupDirect)}</p>
                                </div>
                            </div>
                            ${groupNote ? `<p class="text-[11px] text-amber-700 mb-2"><i class="fas fa-sticky-note mr-1 text-amber-500"></i>${groupNote}</p>` : ''}
                            <div class="space-y-1.5">${itemsHtml || '<p class="text-[11px] text-gray-400">No items.</p>'}</div>
                        </div>
                    `;
                }).join('');

                groupContainer.html(html);
            }

            function groupDistributionsByDate(items) {
                const grouped = {};
                (items || []).forEach(function(item) {
                    if (!grouped[item.distribution_date]) {
                        grouped[item.distribution_date] = [];
                    }
                    grouped[item.distribution_date].push(item);
                });
                return grouped;
            }

            function formatDateLabel(dateStr) {
                const parsed = new Date(dateStr + 'T00:00:00');
                if (isNaN(parsed.getTime())) return dateStr;

                return parsed.toLocaleDateString('en-US', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
            }

            function renderAllDistributionsList() {
                const container = $('#allDistributionsListContainer');
                container.empty();

                const today = new Date();
                today.setHours(0, 0, 0, 0);

                const dates = Object.keys(allDistributionData).filter(function(dateStr) {
                    const parsed = new Date(dateStr + 'T00:00:00');
                    return !isNaN(parsed.getTime()) && parsed >= today;
                }).sort(function(a, b) {
                    return new Date(b + 'T00:00:00') - new Date(a + 'T00:00:00');
                });

                $('#allDistributionDatesCount').text(dates.length + (dates.length === 1 ? ' date' : ' dates'));

                if (dates.length === 0) {
                    $('#allDistributionsEmptyState').removeClass('hidden');
                    return;
                }

                $('#allDistributionsEmptyState').addClass('hidden');

                dates.forEach(function(dateStr) {
                    const dayItems = allDistributionData[dateStr] || [];
                    const batchQty = dayItems.reduce((sum, item) => sum + ((item.qty_mode || 'batch') !== 'pieces' ? parseNumericValue(item.product_qnty) : 0), 0);
                    const piecesQty = dayItems.reduce((sum, item) => sum + ((item.qty_mode || 'batch') === 'pieces' ? parseNumericValue(item.product_qnty) : 0), 0);
                    const dayForecast = calculateForecastedSalesTotal(dayItems);
                    const groupCount = getDistinctGroupCount(dayItems, dateStr);
                    const previewNames = dayItems.slice(0, 2).map(item => item.product_name).join(', ');
                    const extraItems = dayItems.length > 2 ? ' +' + (dayItems.length - 2) + ' more' : '';
                    const note = extractDistributionNote(dayItems);
                    const noteHtml = note
                        ? `<p class="text-[11px] text-amber-700 mt-1 truncate"><i class="fas fa-sticky-note mr-1 text-amber-500"></i>${note}</p>`
                        : '';

                    const row = `
                        <button type="button" class="all-distribution-entry w-full text-left p-3 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-100 transition-colors" data-date="${dateStr}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-800">${formatDateLabel(dateStr)}</p>
                                    <p class="text-xs text-gray-500 truncate">${previewNames || 'No product details'}${extraItems}</p>
                                    ${noteHtml}
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-xs font-semibold text-gray-700">${dayItems.length} ${dayItems.length === 1 ? 'item' : 'items'}</p>
                                    <p class="text-[11px] text-gray-500">${groupCount} ${groupCount === 1 ? 'group' : 'groups'}</p>
                                    <p class="text-[11px] text-gray-500">${formatQuantityValue(batchQty)} batches · ${formatQuantityValue(piecesQty)} pieces</p>
                                    <p class="text-[11px] font-semibold text-primary mt-1"><i class="fas fa-coins mr-1"></i>${formatPesoAmount(dayForecast)}</p>
                                </div>
                            </div>
                        </button>
                    `;

                    container.append(row);
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
                        const responseNote = extractDistributionNote([], response.distribution_note || response.overall_note || response.note || response.place_distributed_to || response.place_distributed || '');

                        if (response.success) {
                            const items = decorateDistributionItems(response.data || [], date);
                            const groupedData = groupDistributionsByGroup(items, date);
                            const summary = Object.assign(
                                {
                                    date: date,
                                    total_items: items.length,
                                    total_groups: getDistinctGroupCount(items, date),
                                    total_batches: items.reduce((sum, item) => sum + (((item.qty_mode || 'batch') !== 'pieces') ? parseNumericValue(item.product_qnty) : 0), 0),
                                    total_pieces: items.reduce((sum, item) => sum + (((item.qty_mode || 'batch') === 'pieces') ? parseNumericValue(item.product_qnty) : 0), 0),
                                    forecasted_sales_total: calculateForecastedSalesTotal(items),
                                    direct_cost_total: items.reduce((sum, item) => sum + parseNumericValue(item.direct_cost), 0),
                                    utilities_expense_total: getUtilityExpenseForDate(date),
                                    raw_material_usage_total: []
                                },
                                response.daily_summary || {}
                            );

                            summary.total_items = items.length;
                            summary.total_groups = getDistinctGroupCount(items, date);
                            summary.total_batches = items.reduce((sum, item) => sum + (((item.qty_mode || 'batch') !== 'pieces') ? parseNumericValue(item.product_qnty) : 0), 0);
                            summary.total_pieces = items.reduce((sum, item) => sum + (((item.qty_mode || 'batch') === 'pieces') ? parseNumericValue(item.product_qnty) : 0), 0);
                            summary.forecasted_sales_total = calculateForecastedSalesTotal(items);
                            summary.direct_cost_total = items.reduce((sum, item) => sum + parseNumericValue(item.direct_cost), 0);
                            summary.utilities_expense_total = getUtilityExpenseForDate(date);

                            if (!Array.isArray(summary.raw_material_usage_total)) {
                                summary.raw_material_usage_total = [];
                            }

                            currentDayDistributionItems = items;
                            currentDayGroupedData = groupedData;
                            currentDaySummary = summary;

                            renderDistributionList(items, groupedData, date);
                            renderMobileCards(items, groupedData, date);

                            const displayState = getDisplayStateForSelectedGroup(date, items, groupedData, summary);

                            updateSummaryCounts(displayState.items, displayState.summary, date);
                            updateForecastedSales(displayState.items, displayState.summary);
                            renderOwnerDayMetrics(displayState.summary);
                            renderOwnerAnalytics(displayState.groups, displayState.summary);
                            updateMainDistributionNotePanels(displayState.items, responseNote);

                            if (isOwnerView) {
                                hydrateOwnerRawMaterialAnalytics(date, items, summary);
                            }

                            if (!$('#calendarDayModal').hasClass('hidden') && $('#calendarDayModal').data('selected-date') === date) {
                                $('#calendarDayModal').data('day-items', items);
                                $('#calendarDayModal').data('day-summary', summary);
                                updateModalForecastedSales(items, summary);
                            }
                        } else {
                            clearSelectedGroupFilter(date);
                            currentDayDistributionItems = [];
                            currentDayGroupedData = [];
                            currentDaySummary = {};
                            renderDistributionList([], [], date);
                            renderMobileCards([], [], date);
                            updateSummaryCounts([], {}, date);
                            updateForecastedSales([], {});
                            renderOwnerDayMetrics({});
                            renderOwnerAnalytics([], {});
                            updateMainDistributionNotePanels([]);
                        }
                    },
                    error: function(xhr, status, error) {
                        clearSelectedGroupFilter(date);
                        currentDayDistributionItems = [];
                        currentDayGroupedData = [];
                        currentDaySummary = {};
                        renderDistributionList([], [], date);
                        renderMobileCards([], [], date);
                        updateSummaryCounts([], {}, date);
                        updateForecastedSales([], {});
                        renderOwnerDayMetrics({});
                        renderOwnerAnalytics([], {});
                        updateMainDistributionNotePanels([]);
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
                            calendarData = groupDistributionsByDate(applyLocalDistributionGroupMeta(response.data));
                        } else {
                            calendarData = {};
                        }
                        renderCalendar();
                    },
                    error: function() {
                        calendarData = {};
                        renderCalendar();
                    }
                });
            }

            function loadAllDistributions() {
                const todayStr = formatDate(new Date());

                $.ajax({
                    url: baseUrl + 'Distribution/GetDistributionByDateRange',
                    method: 'GET',
                    data: {
                        start_date: todayStr,
                        end_date: '2100-12-31'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data) {
                            allDistributionData = groupDistributionsByDate(applyLocalDistributionGroupMeta(response.data));
                        } else {
                            allDistributionData = {};
                        }
                        renderAllDistributionsList();
                    },
                    error: function() {
                        allDistributionData = {};
                        renderAllDistributionsList();
                    }
                });
            }

            function addDistributionItemRequest(payload) {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: baseUrl + 'Distribution/AddDistribution',
                        method: 'POST',
                        contentType: 'application/json',
                        dataType: 'json',
                        data: JSON.stringify(payload),
                        success: function(response) {
                            resolve(response || {});
                        },
                        error: function(xhr) {
                            reject(xhr);
                        }
                    });
                });
            }

            function deleteDistributionItem(itemId, productId = null, dateValue = null) {
                $.ajax({
                    url: baseUrl + 'Distribution/DeleteDistribution/' + itemId,
                    method: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (productId != null) {
                            removeLocalDistributionGroupMeta(dateValue || $('#selectedDate').val(), productId);
                        }
                        showToast('success', 'Item removed successfully!', 3000);
                        loadDistributionByDate();
                        loadMonthDistributions();
                        loadAllDistributions();
                    },
                    error: function(xhr, status, error) {
                        showToast('danger', 'Failed to delete item. Please try again.', 3000);
                        console.error('Error deleting item:', error);
                    }
                });
            }

            function updateDistributionItem(itemId, quantity) {
                const row = $('[data-id="' + itemId + '"]');
                const productId = row.data('product-id');
                const qtyMode = row.data('qty-mode') || 'batch';
                const date = $('#selectedDate').val();

                $.ajax({
                    url: baseUrl + 'Distribution/UpdateDistribution/' + itemId,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        product_id: productId,
                        product_qnty: quantity,
                        qty_mode: qtyMode === 'box' ? 'batch' : qtyMode,
                        distribution_date: date
                    }),
                    dataType: 'json',
                    success: function(response) {
                        showToast('success', 'Quantity updated successfully!', 3000);
                        loadDistributionByDate();
                        loadMonthDistributions();
                        loadAllDistributions();
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 400 && xhr.responseJSON && xhr.responseJSON.insufficient_materials) {
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
                    let bgClass = isSelected ? '' : (hasItems ? 'bg-primary/5' : 'bg-gray-50');

                    let groupsPreview = '';
                    if (hasItems) {
                        const groupedData = normalizeGroupedData(dayData, null, dateStr);
                        const maxVisibleGroups = window.matchMedia('(max-width: 639px)').matches ? 2 : 3;
                        const visibleGroups = groupedData.slice(0, maxVisibleGroups);
                        const hiddenGroupsCount = Math.max(0, groupedData.length - visibleGroups.length);

                        groupsPreview = `
                            <div class="mt-0.5 sm:mt-1 space-y-0.5 sm:space-y-1">
                                ${visibleGroups.map(function(group) {
                                    const groupName = escapeHtml((group.group_name || 'Default Group').toString());
                                    const groupKey = escapeHtml((group.group_key || '').toString());
                                    return `
                                        <button
                                            type="button"
                                            class="calendar-group-chip w-full text-left truncate px-1 sm:px-1.5 py-0.5 rounded text-[8px] sm:text-[9px] md:text-[10px] font-medium hover:opacity-90 transition-opacity ${isSelected ? 'bg-white/20 text-white' : 'bg-primary/10 text-primary'}"
                                            data-date="${dateStr}"
                                            data-group-key="${groupKey}"
                                            title="${groupName}">
                                            ${groupName}
                                        </button>
                                    `;
                                }).join('')}
                                ${hiddenGroupsCount > 0 ? `<div class="text-[8px] sm:text-[9px] font-medium ${isSelected ? 'text-white/80' : 'text-gray-500'}">+${hiddenGroupsCount} more</div>` : ''}
                            </div>
                        `;
                    }

                    const dayHtml = `
                        <div class="calendar-day h-16 sm:h-20 md:h-24 p-0.5 sm:p-1 md:p-2 rounded-md sm:rounded-lg cursor-pointer hover:shadow-md transition-all ${bgClass} ${todayClass} ${selectedClass} border border-gray-100"
                             data-date="${dateStr}">
                            <div class="text-[10px] sm:text-xs md:text-sm font-semibold ${isSelected ? 'text-white' : 'text-gray-700'}">${day}</div>
                            ${groupsPreview}
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

            function openSpecificGroupView(dateStr, groupKey, sourceItems = null) {
                const normalizedDate = (dateStr || '').toString();
                const normalizedGroupKey = (groupKey || '').toString();

                setSelectedGroupFilter(normalizedDate, normalizedGroupKey);

                const candidateItems = Array.isArray(sourceItems)
                    ? sourceItems
                    : (calendarData[normalizedDate] || currentDayDistributionItems || []);

                const groupedData = normalizeGroupedData(candidateItems, null, normalizedDate);
                const matchedGroup = groupedData.find(function(group) {
                    return String(group.group_key || '') === normalizedGroupKey;
                });

                if (!matchedGroup) {
                    clearSelectedGroupFilter(normalizedDate);
                    return;
                }

                const groupItems = Array.isArray(matchedGroup.items) ? matchedGroup.items : [];
                const groupSummary = buildGroupScopedSummary(matchedGroup, groupItems, normalizedDate);

                updateSummaryCounts(groupItems, groupSummary, normalizedDate);
                updateForecastedSales(groupItems, groupSummary);
                renderOwnerDayMetrics(groupSummary);
                renderOwnerAnalytics([matchedGroup], groupSummary);

                showCalendarDayModal(normalizedDate, groupItems, {
                    summary: groupSummary,
                    scope: 'group'
                });
            }

            // Calendar date click - show all groups so user can choose one
            $(document).on('click', '.calendar-day', function(e) {
                if ($(e.target).closest('.calendar-group-chip').length) {
                    return;
                }

                const dateStr = ($(this).data('date') || '').toString();
                const dayData = calendarData[dateStr] || [];
                showCalendarDayModal(dateStr, dayData, { groupPicker: true });
            });

            // Calendar group chip click - open selected group directly
            $(document).on('click', '.calendar-group-chip', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const dateStr = ($(this).data('date') || '').toString();
                const groupKey = ($(this).data('group-key') || '').toString();
                openSpecificGroupView(dateStr, groupKey, calendarData[dateStr] || []);
            });

            // Group picker inside modal - open selected group
            $(document).on('click', '.modal-group-picker-btn', function() {
                const dateStr = ($(this).data('date') || $('#calendarDayModal').data('selected-date') || '').toString();
                const groupKey = ($(this).data('group-key') || '').toString();
                const dayItems = $('#calendarDayModal').data('day-items') || calendarData[dateStr] || [];
                openSpecificGroupView(dateStr, groupKey, dayItems);
            });

            function showCalendarDayModal(dateStr, items, modalOptions = {}) {
                const date = new Date(dateStr + 'T00:00:00');
                const dateDisplayOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const formatted = date.toLocaleDateString('en-US', dateDisplayOptions);
                const groupedData = normalizeGroupedData(items, null, dateStr);
                const groupCount = groupedData.length;
                const selectedDate = $('#selectedDate').val();
                const providedSummary = (modalOptions && typeof modalOptions === 'object' && modalOptions.summary)
                    ? modalOptions.summary
                    : null;
                const isGroupPickerMode = Boolean(modalOptions && modalOptions.groupPicker);
                const requestedScope = (modalOptions && typeof modalOptions === 'object' && modalOptions.scope === 'group')
                    ? 'group'
                    : 'date';
                const selectionScope = isGroupPickerMode ? 'date' : requestedScope;
                const shouldUseCurrentDaySummary = (selectedDate === dateStr)
                    && Array.isArray(currentDayDistributionItems)
                    && currentDayDistributionItems.length === items.length;
                const modalSummary = providedSummary || (shouldUseCurrentDaySummary && currentDaySummary ? currentDaySummary : {});

                if (isGroupPickerMode) {
                    $('#calendarDaySummaryCards').addClass('hidden');
                } else {
                    $('#calendarDaySummaryCards').removeClass('hidden');
                }

                $('#calendarDayModalTitle').text(
                    isGroupPickerMode
                        ? 'Select Distribution Group'
                        : (groupCount > 1 ? 'Distribution Groups' : 'Distribution Group')
                );
                $('#calendarDayModalDate').text(groupCount > 0
                    ? `${formatted} • ${groupCount} ${groupCount === 1 ? 'group' : 'groups'}`
                    : formatted
                );
                $('#calendarDayModal').data('selected-date', dateStr);
                $('#calendarDayModal').data('day-summary', modalSummary);
                $('#calendarDayModal').data('selection-scope', selectionScope);
                $('#btnCalendarDaySelect').html(
                    `<i class="fas fa-arrow-right mr-1"></i>${selectionScope === 'group' ? 'Go to this group' : 'Go to this date'}`
                );

                const batchTotal = items.reduce((sum, item) => sum + ((item.qty_mode || 'batch') !== 'pieces' ? parseNumericValue(item.product_qnty) : 0), 0);
                const piecesTotal = items.reduce((sum, item) => sum + ((item.qty_mode || 'batch') === 'pieces' ? parseNumericValue(item.product_qnty) : 0), 0);
                $('#modalItemCount').text(items.length);
                $('#modalBatchesCount').text(formatQuantityValue(batchTotal));
                $('#modalPiecesCount').text(formatQuantityValue(piecesTotal));
                $('#calendarDayModal').data('day-items', items);
                updateModalForecastedSales(items, modalSummary);

                const listContainer = $('#calendarDayItemsList');
                listContainer.empty();
                updateCalendarDayNotePanel(items);

                if (items.length === 0) {
                    $('#calendarDayItemsList').addClass('hidden');
                    $('#calendarDayEmptyState').removeClass('hidden');
                } else {
                    $('#calendarDayItemsList').removeClass('hidden');
                    $('#calendarDayEmptyState').addClass('hidden');

                    if (isGroupPickerMode) {
                        const pickerHtml = groupedData.map(function(group) {
                            const groupItems = Array.isArray(group.items) ? group.items : [];
                            const groupName = escapeHtml((group.group_name || 'Default Group').toString());
                            const groupNote = escapeHtml((group.group_note || '').toString().trim());
                            const groupKey = escapeHtml((group.group_key || '').toString());
                            const groupSummary = buildGroupScopedSummary(group, groupItems, dateStr);

                            return `
                                <button type="button" class="modal-group-picker-btn w-full text-left p-2.5 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors" data-date="${dateStr}" data-group-key="${groupKey}">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-primary truncate"><i class="fas fa-layer-group mr-1"></i>${groupName}</p>
                                            <p class="text-[11px] text-gray-500 mt-0.5">${groupItems.length} item(s)</p>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <p class="text-[11px] font-semibold text-primary">${formatPesoAmount(parseNumericValue(groupSummary.forecasted_sales_total))}</p>
                                            <p class="text-[10px] text-gray-500 mt-0.5">Tap to open</p>
                                        </div>
                                    </div>
                                    ${groupNote ? `<p class="text-[11px] text-amber-700 mt-1.5 truncate"><i class="fas fa-sticky-note mr-1 text-amber-500"></i>${groupNote}</p>` : ''}
                                </button>
                            `;
                        }).join('');

                        listContainer.html(pickerHtml || '<p class="text-xs text-gray-400">No groups available.</p>');
                    } else {

                        groupedData.forEach(function(group) {
                            const groupItems = Array.isArray(group.items) ? group.items : [];
                            const groupName = escapeHtml((group.group_name || 'Default Group').toString());
                            const groupNote = escapeHtml((group.group_note || '').toString().trim());

                            const rowsHtml = groupItems.map(function(item) {
                                return `
                                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-8 h-8 rounded-md bg-primary/10 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-bread-slice text-primary text-xs"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <span class="text-sm font-medium text-gray-800 truncate block">${escapeHtml(item.product_name || '')}</span>
                                            </div>
                                        </div>
                                        <span class="text-sm font-bold text-gray-800">${formatQuantityValue(item.product_qnty)} <span class="text-xs text-gray-500 font-normal">${getQtyModeShortLabel(item.qty_mode || 'batch')}</span></span>
                                    </div>
                                `;
                            }).join('');

                            const groupCard = `
                                <div class="p-2.5 bg-white border border-gray-200 rounded-lg space-y-2">
                                    <div class="p-2 bg-primary/5 border border-primary/20 rounded-lg">
                                        <div class="flex items-center justify-between gap-2">
                                            <p class="text-xs font-semibold text-primary truncate">
                                                <i class="fas fa-layer-group mr-1"></i>${groupName}
                                            </p>
                                            <p class="text-[11px] text-gray-600">${groupItems.length} item(s)</p>
                                        </div>
                                        ${groupNote ? `<p class="text-[11px] text-amber-700 mt-1"><i class="fas fa-sticky-note mr-1 text-amber-500"></i>${groupNote}</p>` : ''}
                                    </div>
                                    <div class="space-y-2">
                                        ${rowsHtml || '<p class="text-xs text-gray-400 px-1 py-1">No items in this group.</p>'}
                                    </div>
                                </div>
                            `;

                            listContainer.append(groupCard);
                        });
                    }
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
                const selectionScope = ($('#calendarDayModal').data('selection-scope') || 'date').toString();

                if (selectionScope !== 'group') {
                    clearSelectedGroupFilter(dateStr);
                }

                const parsedDate = new Date(dateStr + 'T00:00:00');
                if (!isNaN(parsedDate.getTime())) {
                    currentCalendarMonth = parsedDate.getMonth();
                    currentCalendarYear = parsedDate.getFullYear();
                    loadMonthDistributions();
                }

                $('#selectedDate').val(dateStr).trigger('change');
                $('#calendarDayModal').addClass('hidden');
            });

            $(document).on('click', '.all-distribution-entry', function() {
                const dateStr = $(this).data('date');
                const dayData = allDistributionData[dateStr] || [];
                showCalendarDayModal(dateStr, dayData);
            });

            $(document).on('click', '.distribution-group-entry', function() {
                const dateStr = ($(this).data('date') || $('#selectedDate').val() || '').toString();
                const groupKey = ($(this).data('group-key') || '').toString();
                openSpecificGroupView(dateStr, groupKey, currentDayDistributionItems);
            });

            // ===== RENDERING FUNCTIONS =====

            function renderDistributionList(items, groupedData = null, fallbackDate = '') {
                const container = $('#distributionListContainer');
                container.empty();

                if (items.length === 0) {
                    container.addClass('hidden');
                    $('#emptyState').removeClass('hidden');
                    return;
                }

                container.removeClass('hidden');
                $('#emptyState').addClass('hidden');

                const selectedDate = (fallbackDate || $('#selectedDate').val() || '').toString();
                const normalizedGroups = normalizeGroupedData(items, groupedData, selectedDate);

                normalizedGroups.forEach(function(group) {
                    const groupItems = Array.isArray(group.items) ? group.items : [];
                    const groupName = escapeHtml((group.group_name || 'Default Group').toString());
                    const groupNoteRaw = (group.group_note || '').toString().trim();
                    const groupNote = escapeHtml(groupNoteRaw);
                    const groupKey = escapeHtml((group.group_key || '').toString());

                    const totalBatches = groupItems.reduce(function(sum, item) {
                        return sum + (((item.qty_mode || 'batch') !== 'pieces') ? parseNumericValue(item.product_qnty) : 0);
                    }, 0);

                    const totalPieces = groupItems.reduce(function(sum, item) {
                        return sum + (((item.qty_mode || 'batch') === 'pieces') ? parseNumericValue(item.product_qnty) : 0);
                    }, 0);

                    const forecastTotal = parseNumericValue(group.forecasted_sales) || calculateForecastedSalesTotal(groupItems);

                    const row = `
                        <button type="button" class="distribution-group-entry w-full text-left p-3 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-100 transition-colors" data-group-key="${groupKey}" data-date="${selectedDate}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-primary truncate"><i class="fas fa-layer-group mr-1"></i>${groupName}</p>
                                    <p class="text-[11px] text-gray-500 mt-0.5">${groupItems.length} item(s)</p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-xs font-semibold text-primary">${formatPesoAmount(forecastTotal)}</p>
                                    <p class="text-[11px] text-gray-500 mt-0.5">${formatQuantityValue(totalBatches)} batches • ${formatQuantityValue(totalPieces)} pcs</p>
                                </div>
                            </div>
                            ${groupNote ? `<p class="text-[11px] text-amber-700 mt-1.5 truncate"><i class="fas fa-sticky-note mr-1 text-amber-500"></i>${groupNote}</p>` : ''}
                        </button>
                    `;

                    container.append(row);
                });
            }

            function renderMobileCards(items, groupedData = null, fallbackDate = '') {
                const container = $('#mobileCardsContainer');
                container.empty();

                if (items.length === 0) {
                    $('#mobileNoResults').removeClass('hidden');
                    return;
                }

                $('#mobileNoResults').addClass('hidden');

                const selectedDate = (fallbackDate || $('#selectedDate').val() || '').toString();
                const normalizedGroups = normalizeGroupedData(items, groupedData, selectedDate);

                normalizedGroups.forEach(function(group) {
                    const groupItems = Array.isArray(group.items) ? group.items : [];
                    const groupName = escapeHtml((group.group_name || 'Default Group').toString());
                    const groupNoteRaw = (group.group_note || '').toString().trim();
                    const groupNote = escapeHtml(groupNoteRaw);
                    const groupKey = escapeHtml((group.group_key || '').toString());

                    const totalBatches = groupItems.reduce(function(sum, item) {
                        return sum + (((item.qty_mode || 'batch') !== 'pieces') ? parseNumericValue(item.product_qnty) : 0);
                    }, 0);

                    const totalPieces = groupItems.reduce(function(sum, item) {
                        return sum + (((item.qty_mode || 'batch') === 'pieces') ? parseNumericValue(item.product_qnty) : 0);
                    }, 0);

                    const forecastTotal = parseNumericValue(group.forecasted_sales) || calculateForecastedSalesTotal(groupItems);

                    const card = `
                        <button type="button" class="distribution-group-entry w-full text-left bg-white rounded-lg shadow-sm p-3 border-l-4 border-primary" data-group-key="${groupKey}" data-date="${selectedDate}">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <h4 class="font-medium text-gray-800 truncate"><i class="fas fa-layer-group text-primary mr-1"></i>${groupName}</h4>
                                    <p class="text-xs text-gray-500 mt-0.5">${groupItems.length} item(s) • ${formatQuantityValue(totalBatches)} batches • ${formatQuantityValue(totalPieces)} pcs</p>
                                </div>
                                <span class="text-[11px] font-semibold text-primary flex-shrink-0">${formatPesoAmount(forecastTotal)}</span>
                            </div>
                            ${groupNote ? `<p class="text-[11px] text-amber-700 mt-1.5 truncate"><i class="fas fa-sticky-note mr-1 text-amber-500"></i>${groupNote}</p>` : ''}
                        </button>
                    `;

                    container.append(card);
                });
            }

            // ===== DATE NAVIGATION =====

            $('#selectedDate').on('change', function() {
                const selectedDate = ($('#selectedDate').val() || '').toString();
                if ((selectedGroupFilter.date || '') !== selectedDate) {
                    clearSelectedGroupFilter();
                }

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

            $('#btnAddItems, #btnAddItemsMobile, #btnAddItemsEmpty').on('click', function() {
                $('#scheduleDate').val($('#selectedDate').val());
                updateScheduleQuickBtns();
                itemsToAddList = [];
                renderAddedItemsList();
                $('#productSearch').val('');
                $('#selectedProductId').val('');
                $('#btnClearProduct').addClass('hidden');
                $('#addProductQty').val(10);
                $('#productYieldInfo').addClass('hidden');
                $('#piecesConversionHint').addClass('hidden');
                $('#qtyModeSection').addClass('hidden');
                // Reset mode toggle to batch and re-enable buttons
                $('#btnModeBatch').removeClass('hidden');
                $('#btnModeBox').removeClass('hidden');
                $('#btnModePieces').removeClass('pointer-events-none w-full');
                $('.qty-mode-btn').prop('disabled', false).removeClass('cursor-not-allowed bg-gray-200 text-gray-400');
                $('#selectedQtyMode').val('batch');
                $('.qty-mode-btn').removeClass('bg-primary text-white').addClass('bg-white text-gray-600 hover:bg-gray-50');
                $('#btnModeBatch').removeClass('bg-white text-gray-600 hover:bg-gray-50').addClass('bg-primary text-white');
                $('#addQtyLabel').html('Quantity (per batch) <span class="text-red-500">*</span>');
                hideProductDropdown();
                $('#distributionGroupName').val('');
                $('#overallDistributionNote').val('');
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

                // Show pieces_per_yield info for the selected product
                const product = productsData.find(p => p.product_id == id);
                if (product && product.pieces_per_yield > 0) {
                    $('#piecesPerYieldDisplay').text(product.pieces_per_yield);
                    $('#productYieldInfo').removeClass('hidden');
                } else {
                    $('#productYieldInfo').addClass('hidden');
                }

                // Grocery & Drinks: force pieces mode, disable batch toggle
                if (product && isPiecesOnlyCategory(product.category)) {
                    forceQtyMode('pieces');
                } else {
                    unlockQtyModeToggle();
                }

                // Show quantity mode & quantity fields
                $('#qtyModeSection').removeClass('hidden');

                updateConversionHint();

                $('#addProductQty').focus();
            });

            $('#btnClearProduct').on('click', function() {
                $('#selectedProductId').val('');
                $('#productSearch').val('');
                $(this).addClass('hidden');
                $('#productYieldInfo').addClass('hidden');
                $('#piecesConversionHint').addClass('hidden');
                $('#qtyModeSection').addClass('hidden');
                unlockQtyModeToggle();
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
                        const piecesOnly = isPiecesOnlyCategory(product.category);
                        let badge = '';
                        if (alreadyAdded) {
                            badge = '<span class="text-xs text-green-600 font-medium">Added</span>';
                        } else if (piecesOnly) {
                            badge = '<span class="text-[10px] text-blue-500 font-medium">pieces only</span>';
                        }
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

            // ===== QUANTITY MODE TOGGLE =====

            /**
             * Check whether a product category only supports pieces mode (no batches).
             */
            function isPiecesOnlyCategory(category) {
                const cat = (category || '').toLowerCase();
                return cat === 'grocery' || cat === 'drinks';
            }

            /**
             * Force the qty mode to pieces-only: hide the batch button, show pieces
             * as a non-clickable label so the user sees the mode without interacting.
             */
            function forceQtyMode(mode) {
                $('#selectedQtyMode').val(mode);
                // Hide batch button, show only pieces as a static label
                $('#btnModeBatch').addClass('hidden');
                $('#btnModeBox').addClass('hidden');
                $('#btnModePieces').removeClass('hidden bg-white text-gray-600 hover:bg-gray-50')
                    .addClass('bg-primary text-white pointer-events-none w-full');
                $('.qty-mode-btn').prop('disabled', true);
                $('#addQtyLabel').html('Quantity (per piece) <span class="text-red-500">*</span>');
            }

            /**
             * Unlock the qty mode toggle buttons so the user can freely switch.
             */
            function unlockQtyModeToggle() {
                // Restore both buttons to normal interactive state
                $('#btnModeBatch').removeClass('hidden');
                $('#btnModeBox').removeClass('hidden');
                $('#btnModePieces').removeClass('pointer-events-none w-full');
                $('.qty-mode-btn').prop('disabled', false).removeClass('cursor-not-allowed bg-gray-200 text-gray-400');
                // Reset to batch mode when unlocking
                $('#selectedQtyMode').val('batch');
                $('.qty-mode-btn').removeClass('bg-primary text-white').addClass('bg-white text-gray-600 hover:bg-gray-50');
                $('#btnModeBatch').removeClass('bg-white text-gray-600 hover:bg-gray-50').addClass('bg-primary text-white');
                $('#addQtyLabel').html('Quantity (per batch) <span class="text-red-500">*</span>');
            }

            $('.qty-mode-btn').on('click', function() {
                if ($(this).prop('disabled')) return;
                const mode = $(this).data('mode');
                $('#selectedQtyMode').val(mode);

                // Update toggle button styles
                $('.qty-mode-btn').removeClass('bg-primary text-white').addClass('bg-white text-gray-600 hover:bg-gray-50');
                $(this).removeClass('bg-white text-gray-600 hover:bg-gray-50').addClass('bg-primary text-white');

                // Update quantity label
                if (mode === 'pieces') {
                    $('#addQtyLabel').html('Quantity (per piece) <span class="text-red-500">*</span>');
                } else if (mode === 'box') {
                    $('#addQtyLabel').html('Quantity (per box) <span class="text-red-500">*</span>');
                } else {
                    $('#addQtyLabel').html('Quantity (per batch) <span class="text-red-500">*</span>');
                }

                updateConversionHint();
            });

            // Update conversion hint when quantity changes
            $('#addProductQty').on('input', function() {
                updateConversionHint();
            });

            function updateConversionHint() {
                const productId = $('#selectedProductId').val();
                const mode = $('#selectedQtyMode').val();
                const qty = parseNumericValue($('#addProductQty').val());

                if (!productId || qty <= 0) {
                    $('#piecesConversionHint').addClass('hidden');
                    return;
                }

                const product = productsData.find(p => p.product_id == productId);
                const piecesPerYield = product ? parseNumericValue(product.pieces_per_yield || 0) : 0;

                if (piecesPerYield <= 0) {
                    $('#piecesConversionHint').addClass('hidden');
                    return;
                }

                if (mode === 'batch') {
                    const totalPieces = qty * piecesPerYield;
                    $('#conversionText').text(qty + ' batch(es) × ' + piecesPerYield + ' pcs/batch = ' + totalPieces + ' pieces total');
                    $('#piecesConversionHint').removeClass('hidden');
                } else if (mode === 'pieces') {
                    const batches = (qty / piecesPerYield).toFixed(2);
                    $('#conversionText').text(qty + ' pieces ÷ ' + piecesPerYield + ' pcs/batch = ' + batches + ' batch(es) of raw materials used');
                    $('#piecesConversionHint').removeClass('hidden');
                } else {
                    $('#piecesConversionHint').addClass('hidden');
                }
            }

            $('#btnAddProductToList').on('click', function() {
                const productId = $('#selectedProductId').val();
                const productName = $('#productSearch').val();
                const quantity = parseNumericValue($('#addProductQty').val());
                const qtyMode = $('#selectedQtyMode').val();

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

                // Get pieces_per_yield for display purposes
                const product = productsData.find(p => p.product_id == productId);
                const piecesPerYield = product ? parseNumericValue(product.pieces_per_yield || 0) : 0;

                itemsToAddList.push({
                    product_id: productId,
                    product_name: productName,
                    quantity: quantity,
                    qty_mode: qtyMode,
                    pieces_per_yield: piecesPerYield
                });
                renderAddedItemsList();

                $('#productSearch').val('');
                $('#selectedProductId').val('');
                $('#btnClearProduct').addClass('hidden');
                $('#productYieldInfo').addClass('hidden');
                $('#piecesConversionHint').addClass('hidden');
                $('#qtyModeSection').addClass('hidden');
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
                    const modeLabel = getQtyModeShortLabel(item.qty_mode);
                    const modeBadgeColor = item.qty_mode === 'pieces'
                        ? 'bg-blue-100 text-blue-700'
                        : (item.qty_mode === 'box' ? 'bg-amber-100 text-amber-700' : 'bg-primary/10 text-primary');
                    const row = `
                        <div class="flex items-center justify-between p-2 bg-white rounded-md border border-gray-200">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-8 h-8 rounded-md bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-bread-slice text-primary text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-sm font-medium text-gray-800 truncate block">${item.product_name}</span>
                                    <div class="flex items-center gap-1">
                                        <span class="text-xs text-gray-500">${item.quantity} ${modeLabel}</span>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded-full ${modeBadgeColor} font-medium">${item.qty_mode}</span>
                                    </div>
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
                const row = $(this).closest('[data-id]');
                const productName = row.find('span.font-medium, span.truncate').first().text();
                const qty = row.find('.font-bold').first().text();
                const qtyMode = row.data('qty-mode') || 'batch';

                $('#editProductName').text(productName);
                $('#editQuantity').val(parseInt(qty));
                $('#editItemId').val(row.data('id'));
                $('#editItemQtyMode').val(qtyMode);
                $('#editQtyModeBadge').text(qtyMode).removeClass('bg-primary/10 text-primary bg-blue-100 text-blue-700 bg-amber-100 text-amber-700 bg-gray-200 text-gray-600')
                    .addClass(qtyMode === 'pieces' ? 'bg-blue-100 text-blue-700' : (qtyMode === 'box' ? 'bg-amber-100 text-amber-700' : 'bg-primary/10 text-primary'));
                $('#editQtyLabel').text('Quantity (per ' + getQtyModeLabel(qtyMode) + ')');
                $('#editQtyModal').removeClass('hidden');
            });

            $(document).on('click', '.btn-edit-qty-mobile', function() {
                const card = $(this).closest('[data-id]');
                const productName = card.find('h4').text();
                const qtyText = card.find('.text-xs.text-gray-500').first().text();
                const qty = parseInt(qtyText) || 0;
                const qtyMode = card.data('qty-mode') || 'batch';

                $('#editProductName').text(productName);
                $('#editQuantity').val(qty);
                $('#editItemId').val(card.data('id'));
                $('#editItemQtyMode').val(qtyMode);
                $('#editQtyModeBadge').text(qtyMode).removeClass('bg-primary/10 text-primary bg-blue-100 text-blue-700 bg-amber-100 text-amber-700 bg-gray-200 text-gray-600')
                    .addClass(qtyMode === 'pieces' ? 'bg-blue-100 text-blue-700' : (qtyMode === 'box' ? 'bg-amber-100 text-amber-700' : 'bg-primary/10 text-primary'));
                $('#editQtyLabel').text('Quantity (per ' + getQtyModeLabel(qtyMode) + ')');
                $('#editQtyModal').removeClass('hidden');
            });

            $(document).on('click', '.btn-delete', function() {
                const row = $(this).closest('[data-id]');
                const itemId = row.data('id');
                const productId = row.data('product-id');
                const dateValue = $('#selectedDate').val();

                Confirm.delete('Are you sure you want to remove this item?', function() {
                    deleteDistributionItem(itemId, productId, dateValue);
                });
            });

            $(document).on('click', '.btn-delete-mobile', function() {
                const card = $(this).closest('[data-id]');
                const itemId = card.data('id');
                const productId = card.data('product-id');
                const dateValue = $('#selectedDate').val();

                Confirm.delete('Are you sure you want to remove this item?', function() {
                    deleteDistributionItem(itemId, productId, dateValue);
                });
            });

            // ===== FORM SUBMISSIONS =====

            $('#addItemsForm').on('submit', function(e) {
                e.preventDefault();

                const scheduleDate = $('#scheduleDate').val();
                const distributionGroupName = ($('#distributionGroupName').val() || '').trim();
                const distributionGroupNote = ($('#overallDistributionNote').val() || '').trim();

                if (itemsToAddList.length === 0) {
                    showToast('warning', 'Please add at least one product to the list.', 3000);
                    return;
                }

                const itemsToAdd = itemsToAddList.map(function(item) {
                    return {
                        product_id: item.product_id,
                        quantity: parseNumericValue(item.quantity),
                        qty_mode: item.qty_mode === 'box' ? 'batch' : item.qty_mode
                    };
                });

                const savedGroupName = distributionGroupName || getNextAutoGroupName(scheduleDate);
                const groupMeta = {
                    group_key: buildClientGroupKey(scheduleDate, savedGroupName),
                    group_name: savedGroupName,
                    group_note: distributionGroupNote,
                };

                $('#btnSaveItems').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Saving...');

                const payloads = itemsToAdd.map(function(item) {
                    return {
                        product_id: item.product_id,
                        product_qnty: item.quantity,
                        qty_mode: item.qty_mode,
                        distribution_date: scheduleDate,
                    };
                });

                Promise.allSettled(payloads.map(function(payload) {
                    return addDistributionItemRequest(payload);
                }))
                    .then(function(results) {
                        const succeededPayloads = [];
                        let duplicateCount = 0;
                        let genericErrorCount = 0;
                        let sawInsufficient = false;
                        let insufficientMaterials = [];

                        results.forEach(function(result, index) {
                            const currentPayload = payloads[index];

                            if (result.status === 'fulfilled') {
                                const response = result.value || {};
                                if (response.success === false && response.error) {
                                    genericErrorCount += 1;
                                    return;
                                }

                                succeededPayloads.push(currentPayload);
                                return;
                            }

                            const xhr = result.reason || {};
                            const responseJson = xhr.responseJSON || {};

                            if (xhr.status === 409 || responseJson.duplicate) {
                                duplicateCount += 1;
                                return;
                            }

                            if (xhr.status === 400 && Array.isArray(responseJson.insufficient_materials)) {
                                sawInsufficient = true;
                                insufficientMaterials = insufficientMaterials.concat(responseJson.insufficient_materials);
                                return;
                            }

                            genericErrorCount += 1;
                        });

                        if (succeededPayloads.length > 0) {
                            const productIds = succeededPayloads.map(function(payload) {
                                return payload.product_id;
                            });

                            setLocalDistributionGroupMetaForItems(scheduleDate, productIds, groupMeta);

                            $('#addItemsModal').addClass('hidden');
                            $('#selectedDate').val(scheduleDate).trigger('change');
                            loadMonthDistributions();
                            loadAllDistributions();

                            itemsToAddList = [];
                            renderAddedItemsList();
                        }

                        if (sawInsufficient && insufficientMaterials.length > 0) {
                            showInsufficientMaterialsAlert(Array.from(new Set(insufficientMaterials)));
                        }

                        const totalAttempted = payloads.length;
                        if (succeededPayloads.length === totalAttempted) {
                            showToast('success', `Distribution group "${savedGroupName}" added successfully!`, 3200);
                            return;
                        }

                        if (succeededPayloads.length > 0) {
                            let partialMessage = `Saved ${succeededPayloads.length} of ${totalAttempted} item(s) to group "${savedGroupName}".`;
                            if (duplicateCount > 0) {
                                partialMessage += ` ${duplicateCount} duplicate item(s) skipped.`;
                            }
                            if (sawInsufficient || genericErrorCount > 0) {
                                partialMessage += ' Some items were not added.';
                            }

                            showToast('warning', partialMessage, 4500);
                            return;
                        }

                        if (duplicateCount === totalAttempted) {
                            showToast('warning', 'All selected products already exist for that date.', 4000);
                        } else if (sawInsufficient) {
                            showToast('danger', 'Insufficient raw materials for one or more selected items.', 4500);
                        } else {
                            showToast('danger', 'Failed to add distribution group. Please try again.', 3200);
                        }
                    })
                    .catch(function(error) {
                        console.error('Error saving distribution group:', error);
                        showToast('danger', 'Failed to add distribution group. Please try again.', 3200);
                    })
                    .finally(function() {
                        $('#btnSaveItems').prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Save to Schedule');
                    });
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

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
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

        function updateSummaryCounts(items, summary = {}, fallbackDate = '') {
            const distributionItems = Array.isArray(items) ? items : [];
            const hasSummary = summary && typeof summary === 'object';

            const computedTotalItems = distributionItems.length;
            const computedTotalBatches = distributionItems.reduce(function(sum, item) {
                return sum + (((item.qty_mode || 'batch') === 'pieces') ? 0 : parseNumericValue(item.product_qnty));
            }, 0);
            const computedTotalPieces = distributionItems.reduce(function(sum, item) {
                return sum + (((item.qty_mode || 'batch') === 'pieces') ? parseNumericValue(item.product_qnty) : 0);
            }, 0);

            const total = hasSummary && summary.total_items != null
                ? (parseInt(summary.total_items) || 0)
                : computedTotalItems;
            const totalBatches = hasSummary && summary.total_batches != null
                ? parseNumericValue(summary.total_batches)
                : computedTotalBatches;
            const totalPieces = hasSummary && summary.total_pieces != null
                ? parseNumericValue(summary.total_pieces)
                : computedTotalPieces;
            const totalGroups = hasSummary && summary.total_groups != null
                ? (parseInt(summary.total_groups) || 0)
                : getDistinctGroupCount(distributionItems, fallbackDate);

            $('#totalItemsCount').text(total);
            $('#totalBatchCount').text(formatQuantityValue(totalBatches));
            $('#totalPiecesCount').text(formatQuantityValue(totalPieces));
            $('#mobileItemCount').text(total);
            $('#mobileBatchCount').text(formatQuantityValue(totalBatches));
            $('#mobilePiecesCount').text(formatQuantityValue(totalPieces));

            if (isOwnerView) {
                $('#ownerGroupCountBadge').text(`${totalGroups} ${totalGroups === 1 ? 'group' : 'groups'}`);
            }
        }

        function parseNumericValue(value) {
            const parsed = parseFloat(value);
            return Number.isFinite(parsed) ? parsed : 0;
        }

        function firstPositiveValue(values) {
            for (let index = 0; index < values.length; index++) {
                const parsed = parseNumericValue(values[index]);
                if (parsed > 0) return parsed;
            }
            return 0;
        }

        function getForecastUnitPrice(product, qtyMode) {
            if (!product) return 0;

            const mode = (qtyMode || 'batch').toLowerCase();

            if (mode === 'pieces') {
                return firstPositiveValue([
                    product.selling_price_per_piece,
                    product.price_per_piece,
                    product.price,
                    product.selling_price,
                    product.selling_price_overall
                ]);
            }

            if (mode === 'box') {
                return firstPositiveValue([
                    product.selling_price_per_tray,
                    product.price_per_tray,
                    product.selling_price,
                    product.price,
                    product.selling_price_overall
                ]);
            }

            return firstPositiveValue([
                product.selling_price,
                product.selling_price_overall,
                product.price,
                product.selling_price_per_tray,
                product.selling_price_per_piece
            ]);
        }

        function formatPesoAmount(amount) {
            const safeAmount = Number.isFinite(amount) ? amount : 0;
            return '₱' + safeAmount.toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function formatQuantityValue(value) {
            const safeValue = parseNumericValue(value);
            if (!Number.isFinite(safeValue)) return '0';

            if (Math.abs(safeValue - Math.round(safeValue)) < 0.00001) {
                return Math.round(safeValue).toString();
            }

            return safeValue.toLocaleString('en-PH', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2,
            });
        }

        function calculateForecastedSalesTotal(items) {
            const distributionItems = Array.isArray(items) ? items : [];
            let forecastedTotal = 0;

            distributionItems.forEach(function(item) {
                const quantity = parseNumericValue(item.product_qnty);
                if (quantity <= 0) return;

                const explicitForecast = parseNumericValue(item.forecasted_sales);
                if (explicitForecast > 0) {
                    forecastedTotal += explicitForecast;
                    return;
                }

                const matchedProduct = productsData.find(function(product) {
                    return String(product.product_id) === String(item.product_id);
                }) || getProductAnalyticsData(item.product_id);

                const unitPrice = getForecastUnitPrice(matchedProduct, item.qty_mode || 'batch');
                forecastedTotal += quantity * unitPrice;
            });

            return forecastedTotal;
        }

        function updateModalForecastedSales(items, summary = null) {
            const hasSummaryForecast = summary && Object.prototype.hasOwnProperty.call(summary, 'forecasted_sales_total');
            const forecastedTotal = hasSummaryForecast
                ? parseNumericValue(summary.forecasted_sales_total)
                : calculateForecastedSalesTotal(items);
            $('#modalForecastedSalesTotal').text(formatPesoAmount(forecastedTotal));
        }

        function updateForecastedSales(items, summary = null) {
            const hasSummaryForecast = summary && Object.prototype.hasOwnProperty.call(summary, 'forecasted_sales_total');
            const forecastedTotal = hasSummaryForecast
                ? parseNumericValue(summary.forecasted_sales_total)
                : calculateForecastedSalesTotal(items);

            const formattedTotal = formatPesoAmount(forecastedTotal);
            $('#forecastedSalesTotal').text(formattedTotal);
            $('#mobileForecastedSalesTotal').text(formattedTotal);
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