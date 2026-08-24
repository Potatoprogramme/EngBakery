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
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between w-full">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Daily Baking Schedule</h2>
                    <div class="flex flex-wrap gap-2 self-start sm:self-auto">
                        <button type="button" id="btnManageDistributionCategories"
                            class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-tags mr-2"></i>Manage Categories
                        </button>
                        <button type="button" id="btnAddItems"
                            class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-plus mr-2"></i>Add Items
                        </button>
                    </div>
                </div>
                <div class="mt-3 sm:hidden">
                    <button type="button" id="btnManageDistributionCategoriesMobile"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-primary px-4 py-3 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                        <i class="fas fa-tags mr-2"></i>Manage Categories
                    </button>
                </div>
            </div>

            <!-- Floating Add Items button for mobile -->
            <div id="mobileAddBtnContainer"
                class="fixed bottom-6 left-0 right-0 flex flex-col items-center gap-2 z-30 lg:hidden">
                <button type="button" id="btnAddItemsMobile"
                    class="w-5/6 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <i class="fas fa-plus mr-2"></i>Add Items
                </button>
            </div>

            <!-- Main Layout: List + Calendar -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-6 mb-4 lg:mb-6">

                <!-- Left Side: Baking List (hidden on mobile, shown on lg+) -->
                <div class="hidden lg:flex lg:flex-col lg:min-h-0 lg:col-span-5 xl:col-span-4">
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
                                    <p class="text-[10px] text-gray-400">Entire day, includes distributions and store
                                    </p>
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
                                        <p class="text-xs text-gray-500">Total Cost (Day)</p>
                                        <p id="ownerTotalCostTotalDesktop" class="text-sm font-bold text-emerald-600">₱0.00
                                        </p>
                                    </div>
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                                        <i class="fas fa-calculator text-emerald-600 text-sm"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg shadow-sm border border-amber-100 p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500">Overhead Cost (Day)</p>
                                        <p id="ownerOverheadCostTotalDesktop" class="text-sm font-bold text-amber-600">₱0.00
                                        </p>
                                    </div>
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                                        <i class="fas fa-chart-pie text-amber-600 text-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Distribution Groups Panel -->
                    <div class="bg-white rounded-lg shadow-md p-4 flex flex-col flex-1 min-h-0 overflow-hidden">
                        <div class="flex items-center justify-between mb-3 flex-shrink-0">
                            <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                <i class="fas fa-layer-group mr-1 text-primary"></i>Distribution Groups
                            </h3>
                            <button type="button" id="btnAddItemsEmpty"
                                class="text-xs text-primary hover:text-secondary font-medium">
                                <i class="fas fa-plus mr-1"></i>Add
                            </button>
                        </div>

                        <!-- List Items - Scrollable (Store entry renders first, then distribution groups) -->
                        <div id="distributionListContainer"
                            class="space-y-2 overflow-y-auto flex-1 min-h-0 pr-2 scrollbar-thin scrollbar-track-gray-100 scrollbar-thumb-gray-300 hover:scrollbar-thumb-gray-400">
                            <!-- Dynamically populated via JS -->
                        </div>

                        <!-- Empty State -->
                        <div id="emptyState" class="hidden text-center py-8">
                            <div
                                class="w-16 h-16 rounded-full bg-gray-100 mx-auto mb-3 flex items-center justify-center">
                                <i class="fas fa-layer-group text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-sm font-medium text-gray-800 mb-1">No distribution groups scheduled</h3>
                            <p class="text-xs text-gray-500">Click "Add" to add a distribution group</p>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Calendar -->
                <div class="lg:col-span-7 xl:col-span-8 lg:flex lg:min-h-0">
                    <div class="bg-white rounded-lg shadow-md p-2 sm:p-4 w-full lg:h-full lg:flex lg:flex-col">
                        <!-- Calendar Header -->
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <button type="button" id="btnPrevMonth"
                                class="p-1.5 sm:p-2 rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-600">
                                <i class="fas fa-chevron-left text-xs sm:text-sm"></i>
                            </button>
                            <h3 id="calendarMonth" class="text-sm sm:text-lg font-bold text-gray-800"><?= date('F Y') ?>
                            </h3>
                            <button type="button" id="btnNextMonth"
                                class="p-1.5 sm:p-2 rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-600">
                                <i class="fas fa-chevron-right text-xs sm:text-sm"></i>
                            </button>
                        </div>

                        <!-- Calendar Grid -->
                        <div class="grid grid-cols-7 gap-0.5 sm:gap-1 mb-2">
                            <div class="text-center text-[10px] sm:text-xs font-semibold text-gray-500 py-1 sm:py-2">Sun
                            </div>
                            <div class="text-center text-[10px] sm:text-xs font-semibold text-gray-500 py-1 sm:py-2">Mon
                            </div>
                            <div class="text-center text-[10px] sm:text-xs font-semibold text-gray-500 py-1 sm:py-2">Tue
                            </div>
                            <div class="text-center text-[10px] sm:text-xs font-semibold text-gray-500 py-1 sm:py-2">Wed
                            </div>
                            <div class="text-center text-[10px] sm:text-xs font-semibold text-gray-500 py-1 sm:py-2">Thu
                            </div>
                            <div class="text-center text-[10px] sm:text-xs font-semibold text-gray-500 py-1 sm:py-2">Fri
                            </div>
                            <div class="text-center text-[10px] sm:text-xs font-semibold text-gray-500 py-1 sm:py-2">Sat
                            </div>
                        </div>
                        <div id="calendarDays" class="grid grid-cols-7 gap-0.5 sm:gap-1">
                            <!-- Dynamically populated via JS -->
                        </div>

                        <!-- Legend -->
                        <div
                            class="flex flex-wrap items-center justify-center gap-2 sm:gap-4 mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-1">
                                <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full border-2 border-primary"></div>
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
                                <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-primary"></div>
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
                            <span id="ownerGroupCountBadge"
                                class="text-xs font-medium text-primary bg-primary/10 px-2 py-0.5 rounded-full">0
                                groups</span>
                        </div>

                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                            <div class="rounded-lg border border-gray-100 p-3 bg-gray-50">
                                <h4 id="ownerRawUsageHeading"
                                    class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Raw Material
                                    Usage (Entire Day)</h4>
                                <div id="ownerDayRawMaterialUsage"
                                    class="space-y-1.5 overflow-y-auto max-h-[300px] scrollbar-thin scrollbar-track-gray-100 scrollbar-thumb-gray-300">
                                    <p class="text-xs text-gray-400">No raw material usage for this date.</p>
                                </div>
                            </div>

                            <div class="rounded-lg border border-gray-100 p-3 bg-gray-50">
                                <h4 id="ownerForecastTotalCostHeading"
                                    class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Per-Group
                                    Forecast & Total Cost</h4>
                                <div id="ownerGroupAnalyticsContainer"
                                    class="space-y-2 overflow-y-auto max-h-[300px] scrollbar-thin scrollbar-track-gray-100 scrollbar-thumb-gray-300 pr-1">
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
                                <p class="text-[10px] text-gray-400">Based on distributions and store</p>
                            </div>
                        </div>
                        <p id="mobileForecastedSalesTotal" class="text-sm font-bold text-primary">₱0.00</p>
                    </div>
                </div>

                <?php if ($isOwnerView): ?>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div class="bg-white rounded-lg p-3 border border-emerald-100 shadow-sm">
                            <p class="text-[10px] text-gray-500 uppercase tracking-wide">Total Cost</p>
                            <p id="ownerTotalCostTotalMobile" class="text-sm font-bold text-emerald-600">₱0.00</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-amber-100 shadow-sm">
                            <p class="text-[10px] text-gray-500 uppercase tracking-wide">Overhead Cost</p>
                            <p id="ownerOverheadCostTotalMobile" class="text-sm font-bold text-amber-600">₱0.00</p>
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
                        <span id="allDistributionDatesCount"
                            class="text-xs font-medium text-primary bg-primary/10 px-2 py-0.5 rounded-full">0
                            dates</span>
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
        <div
            class="relative w-full max-w-2xl mx-auto p-4 sm:p-6 border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
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

            <!-- Slide View: Group List -> Group Detail -->
            <div id="calendarDaySlideViewport" class="relative overflow-hidden mb-4">
                <div id="calendarDaySlideTrack" class="flex transition-transform duration-300 ease-in-out"
                    style="width: 200%; transform: translateX(0);">
                    <div class="flex-shrink-0" style="width: 50%;">
                        <div id="calendarDayItemsList" class="space-y-2 max-h-[300px] overflow-y-auto">
                            <!-- Dynamically populated -->
                        </div>
                    </div>
                    <div class="flex-shrink-0" style="width: 50%;">
                        <div class="mb-2">
                            <button type="button" id="btnCalendarDayBackToGroups"
                                class="inline-flex items-center text-xs font-medium text-primary hover:text-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>Back to groups
                            </button>
                        </div>
                        <div id="calendarDayGroupDetailContent" class="space-y-2 max-h-[300px] overflow-y-auto">
                            <!-- Dynamically populated -->
                        </div>
                    </div>
                </div>
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
        <div
            class="relative w-full max-w-2xl mx-auto p-4 sm:p-6 border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 id="addItemsModalTitle" class="text-lg font-semibold text-primary">Add Baking Items</h3>
                    <p id="addItemsModalSubtitle" class="text-sm text-gray-500">Search and add products for a specific
                        date</p>
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
                            <button type="button"
                                class="schedule-quick-btn px-3 py-2 text-xs font-medium rounded-lg bg-primary text-white"
                                data-days="0">Today</button>
                            <button type="button"
                                class="schedule-quick-btn px-3 py-2 text-xs font-medium rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100"
                                data-days="1">Tomorrow</button>
                            <button type="button"
                                class="schedule-quick-btn px-3 py-2 text-xs font-medium rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100"
                                data-days="2">+2 Days</button>
                        </div>
                    </div>
                </div>

                <div class="mb-4 p-3 border border-gray-200 rounded-lg bg-gray-50">
                    <div class="mb-3">
                        <script src="<?= asset_url('js/DistributionDropdown.js') ?>"></script>
                        <label for="distributionGroupName" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-layer-group text-primary mr-1"></i>Destination Category
                        </label>
                        <select id="distributionGroupName" onclick="loadUnusedStores()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm bg-white">
                            <option value="">Select a category</option>
                        </select>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product <span
                                class="text-red-500">*</span></label>
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
                    <!-- <div id="productYieldInfo" class="hidden mb-3 p-2 bg-blue-50 border border-blue-200 rounded-md">
                        <div class="flex items-center gap-2 text-sm text-blue-700">
                            <i class="fas fa-info-circle"></i>
                            <span>1 batch = <strong id="piecesPerYieldDisplay">0</strong> pieces</span>
                        </div>
                    </div> -->

                    <!-- Quantity Mode & Quantity (shown after product is selected) -->
                    <div id="qtyModeSection" class="hidden">

                        <!-- Quantity Mode Toggle -->
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity Per</label>
                            <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                                <!--
    <button type="button" id="btnModeBatch"
        class="qty-mode-btn flex-1 px-3 py-2 text-sm font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors"
        data-mode="batch">
        <i class="fas fa-boxes mr-1"></i>Batch
    </button>
    <button type="button" id="btnModeBox"
        class="qty-mode-btn flex-1 px-3 py-2 text-sm font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors"
        data-mode="box">
        <i class="fas fa-box-open mr-1"></i>Box
    </button>
    -->
                                <button type="button" id="btnModePieces"
                                    class="qty-mode-btn flex-1 px-3 py-2 text-sm font-medium bg-primary text-white transition-colors"
                                    data-mode="pieces">
                                    <i class="fas fa-puzzle-piece mr-1"></i>Piece
                                </button>
                            </div>
                            <input type="hidden" id="selectedQtyMode" value="pieces">
                        </div>

                        <!-- Quantity -->
                        <div class="mb-3">
                            <label id="addQtyLabel" class="block text-sm font-medium text-gray-700 mb-1">Quantity (per
                                piece) <span class="text-red-500">*</span></label>
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
                        <span id="itemsSummaryCount"
                            class="text-xs font-medium text-primary bg-primary/10 px-2 py-0.5 rounded-full">0
                            items</span>
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
                    <span id="editQtyModeBadge"
                        class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full bg-primary/10 text-primary">batch</span>
                </div>

                <div class="mb-6">
                    <label for="editQuantity" id="editQtyLabel"
                        class="block text-sm font-medium text-gray-700 mb-2 text-center">
                        Quantity (per batch)
                    </label>
                    <div class="flex items-center justify-center gap-3">
                        <button type="button" id="btnEditQtyDec"
                            class="w-12 h-12 flex items-center justify-center border border-gray-300 bg-gray-100 text-gray-700 text-xl font-semibold rounded-lg hover:bg-gray-200">
                            -
                        </button>
                        <input type="number" id="editQuantity" name="quantity" min="1" value="10" step="0.00001"
                            required
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
        let ownerRawUsageHydrationToken = 0; // Prevent stale async owner analytics updates
        let modalGroupDetailHydrationToken = 0; // Prevent stale modal group detail ingredient hydration
        let calendarData = {}; // Store distribution data keyed by date
        let allDistributionData = {}; // Store all distribution records keyed by date
        let currentDayDistributionItems = []; // Store current selected date distribution items
        let currentDayGroupedData = []; // Grouped analytics for selected date
        let currentDaySummary = {}; // Day analytics summary for selected date
        let
            storeItemsData = []; // Items added to Store for the selected date — rendered as the first entry in the distribution list
        let selectedGroupFilter = {
            date: '',
            key: ''
        }; // Active selected group scope for analytics cards/panels
        let addItemsModalMode = 'create'; // create | edit
        let editingGroupContext = null; // Active group edit metadata context
        let lastValidScheduleDate = ''; // Last accepted value of #scheduleDate, used to revert blocked selections
        let currentCalendarMonth = new Date().getMonth();
        let currentCalendarYear = new Date().getFullYear();
        const distributionGroupStorageKey = 'engbakery_distribution_group_meta_v1';
        const enableApiDebugLogs = true;



        $(document).ready(function() {

            let storeItemsByDate = {}; // date -> raw items from Inventory/GetAddedStockItems
            let
                storeGroupByDate = {}; // date -> decorated "group" object (same shape as groupDistributionsByGroup output)
            let storeRawUsageHydrationTokens = {};
            const STORE_GROUP_KEY = 'store';

            // Reuses the exact same cost/forecast helpers as normal distribution items,
            // wrapping each store item in a synthetic distribution-item shape (qty_mode: 'pieces').
            function decorateStoreItemAsDistributionItem(storeItem, dateStr) {
                const productId = storeItem.product_id;
                const productData = getProductAnalyticsData(productId);
                const addedQty = parseNumericValue(storeItem.added_qty);
                const distributedQty = parseNumericValue(storeItem.distributed_out_qty);
                // remaining_qty is computed server-side (added - distributed, floored at 0);
                // fall back to computing it here in case an older API response is cached.
                const remainingQty = (storeItem.remaining_qty !== undefined && storeItem.remaining_qty !== null) ?
                    parseNumericValue(storeItem.remaining_qty) :
                    Math.max(0, addedQty - distributedQty);

                const item = {
                    distribution_date: dateStr,
                    product_id: productId,
                    product_name: storeItem.product_name || (productData && productData.product_name) ||
                        'Unknown Product',
                    // product_qnty reflects what's still remaining in the Store (not yet
                    // sent to a distribution group), so totals/forecast stay accurate.
                    product_qnty: remainingQty,
                    added_qty: addedQty, // total added today, before deducting distribution
                    distributed_qty: distributedQty, // already sent out to distribution groups
                    remaining_qty: remainingQty,
                    qty_mode: 'pieces',
                    distribution_group_key: STORE_GROUP_KEY,
                    distribution_group_name: 'Store',
                    distribution_group_note: '',
                    raw_material_usage: [],
                };

                const fallbackUnitPrice = firstPositiveValue([storeItem.selling_price_per_piece, storeItem
                    .selling_price
                ]);
                const unitPrice = (productData ? getForecastUnitPrice(productData, 'pieces') : 0) ||
                    fallbackUnitPrice;

                item.forecasted_sales = remainingQty * unitPrice;
                applyItemCostTotals(item, productData);

                return item;
            }

            function buildStoreGroupObject(storeItems, dateStr) {
                const items = (Array.isArray(storeItems) ? storeItems : [])
                    .map(function(raw) {
                        return decorateStoreItemAsDistributionItem(raw, dateStr);
                    });

                return {
                    group_key: STORE_GROUP_KEY,
                    group_name: 'Store',
                    group_note: '',
                    dist_category_id: 0,
                    total_items: items.length,
                    total_batches: 0, // store items are always tracked in pieces
                    total_pieces: items.reduce((sum, i) => sum + parseNumericValue(i.product_qnty),
                        0), // remaining pieces
                    total_added_pieces: items.reduce((sum, i) => sum + parseNumericValue(i.added_qty), 0),
                    total_distributed_pieces: items.reduce((sum, i) => sum + parseNumericValue(i.distributed_qty),
                        0),
                    forecasted_sales: items.reduce((sum, i) => sum + parseNumericValue(i.forecasted_sales), 0),
                    total_cost: sumCurrencyField(items, 'total_cost'),
                    raw_material_usage_total: [],
                    source_group_ids: [],
                    items: items,
                };
            }

            function fetchStoreItemsForDate(dateStr) {
                return new Promise(function(resolve) {
                    $.ajax({
                        url: baseUrl + 'Inventory/GetAddedStockItems',
                        method: 'GET',
                        data: {
                            date: dateStr
                        },
                        dataType: 'json',
                        success: function(response) {
                            const items = (response && response.success && Array.isArray(
                                response.data)) ? response.data : [];
                            storeItemsByDate[dateStr] = items;
                            resolve(items);
                        },
                        error: function() {
                            storeItemsByDate[dateStr] = [];
                            resolve([]);
                        }
                    });
                });
            }

            async function ensureStoreGroupForDate(dateStr, forceRefresh = false) {
                if (!forceRefresh && storeGroupByDate[dateStr]) return storeGroupByDate[dateStr];

                const items = (!forceRefresh && storeItemsByDate[dateStr]) ? storeItemsByDate[dateStr] :
                    await fetchStoreItemsForDate(dateStr);
                const group = buildStoreGroupObject(items, dateStr);
                storeGroupByDate[dateStr] = group;
                return group;
            }


            function ensureCategoryOptionExists(categoryId, categoryName) {
                const idStr = String(categoryId || '').trim();
                if (!idStr || idStr === '0') return;

                const $select = $('#distributionGroupName');
                if ($select.find(`option[value="${idStr}"]`).length > 0) {
                    $select.val(idStr);
                    return;
                }

                const label = (categoryName || 'Unknown Category').toString().trim() + ' (deleted)';
                $select.append($('<option>', {
                    value: idStr,
                    text: label
                }));
                $select.val(idStr);
            }

            function logDistributionFlow(level, message, details = {}) {
                if (!enableApiDebugLogs) return;

                const logger = (level === 'error') ? console.error :
                    ((level === 'warn') ? console.warn :
                        ((level === 'debug') ? console.debug : console.log));

                logger(`[DistributionFlow] ${message}`, Object.assign({
                    at: new Date().toISOString(),
                }, details || {}));
            }

            function normalizeDistributionGroupIdForApi(groupIdOrKey) {
                const rawValue = String(groupIdOrKey ?? '').trim();
                if (!rawValue) return '';

                const withoutPrefix = rawValue.replace(/^group-/i, '');
                const numericOnly = withoutPrefix.match(/^\d+$/) ? withoutPrefix : rawValue;
                return numericOnly;
            }

            function isPersistedDistributionGroupKey(groupKey) {
                return /^group-\d+$/i.test(String(groupKey || '').trim());
            }

            function getDistributionDisplayGroupKey(group) {
                const categoryId = parseInt(group && (group.dist_category_id ?? group.category_id ?? group
                    .dist_cat_id), 10);
                if (Number.isFinite(categoryId) && categoryId > 0) {
                    return 'category-' + categoryId;
                }

                const groupId = String(group && group.id != null ? group.id : '').trim();
                return groupId ? ('group-' + groupId) : 'group-unknown';
            }

            function getItemSourceGroupIds(item) {
                const sourceIds = new Set();

                if (item && Array.isArray(item.distribution_group_ids)) {
                    item.distribution_group_ids.forEach(function(groupId) {
                        const parsed = parseInt(groupId, 10);
                        if (Number.isFinite(parsed) && parsed > 0) {
                            sourceIds.add(parsed);
                        }
                    });
                }

                const parsedDistributionId = parseInt(item && item.distribution_id, 10);
                if (Number.isFinite(parsedDistributionId) && parsedDistributionId > 0) {
                    sourceIds.add(parsedDistributionId);
                }

                return Array.from(sourceIds);
            }

            const modalScrollLockSelectors = [
                '#calendarDayModal',
                '#addItemsModal',
                '#editQtyModal',
                '#insufficientMaterialModal',
            ];

            function syncModalBodyScrollLock() {
                const hasOpenModal = modalScrollLockSelectors.some(function(selector) {
                    const modal = $(selector);
                    return modal.length > 0 && !modal.hasClass('hidden');
                });

                $('body').css('overflow', hasOpenModal ? 'hidden' : '');
            }

            function initializeModalBodyScrollLock() {
                modalScrollLockSelectors.forEach(function(selector) {
                    const modalElement = document.querySelector(selector);
                    if (!modalElement) return;

                    const observer = new MutationObserver(function() {
                        syncModalBodyScrollLock();
                    });

                    observer.observe(modalElement, {
                        attributes: true,
                        attributeFilter: ['class']
                    });
                });

                syncModalBodyScrollLock();
            }

            baseUrl = '<?= base_url() ?>';
            window.BASE_URL = baseUrl;

            initializeModalBodyScrollLock();

            // function switchDistributionPanelTab(tabName) {
            //     const isStore = tabName === 'store';

            //     $('#tabPanelDistributionGroups')
            //         .toggleClass('hidden', isStore)
            //         .toggleClass('flex', !isStore);
            //     $('#tabPanelStore')
            //         .toggleClass('hidden', !isStore)
            //         .toggleClass('flex', isStore);

            //     $('.distribution-panel-tab-btn')
            //         .removeClass('bg-primary text-white')
            //         .addClass('text-gray-500 hover:text-gray-700');
            //     $(`.distribution-panel-tab-btn[data-tab="${tabName}"]`)
            //         .addClass('bg-primary text-white')
            //         .removeClass('text-gray-500 hover:text-gray-700');

            //     $('#btnAddItemsEmpty').toggleClass('hidden', isStore);

            //     if (isStore) {
            //         loadStoreAddedItems(); // NEW
            //     }
            // }

            // $('.distribution-panel-tab-btn').on('click', function() {
            //     switchDistributionPanelTab($(this).data('tab'));
            // });

            // // Set default active tab
            // switchDistributionPanelTab('distributionGroups');

            // ===== OPEN INVENTORY GATE FOR "ADD ITEMS" =====
            // The Add Items button(s) should only be usable while there is an
            // open (not closed / not yet reported / not yet remitted) inventory
            // shift for today. Without an open inventory there is no shift to
            // pull stock from, so distribution items can't be created.
            //
            // `openInventoryStatus` starts 'checking' (buttons disabled) so a
            // click can't slip through before the very first AJAX check for
            // today's inventory has resolved. Without this, refreshing the
            // page and clicking immediately would show the "closed" warning
            // even when inventory is actually open, since the async check
            // hadn't finished yet.
            let openInventoryStatus = 'checking'; // 'checking' | 'open' | 'closed'

            // The open-inventory requirement only makes sense for TODAY's
            // distributions (you need an open shift to pull live stock from).
            // Advance distributions for a future — or a past — date don't
            // depend on today's shift at all, so the buttons should stay
            // enabled whenever the page's selected date isn't today.
            function isSelectedDateToday() {
                const selectedDate = ($('#selectedDate').val() || '').toString();
                return selectedDate === formatDate(new Date());
            }

            function applyAddItemsButtonState() {
                const onToday = isSelectedDateToday();
                const disabled = onToday && openInventoryStatus !== 'open';

                let title = '';
                if (!onToday) {
                    title = ''; // No gate for advance/past dates.
                } else if (openInventoryStatus === 'checking') {
                    title = 'Checking today\'s inventory status…';
                } else if (openInventoryStatus === 'closed') {
                    title = 'Open today\'s inventory before adding distribution items.';
                }

                $('#btnAddItems, #btnAddItemsMobile')
                    .prop('disabled', disabled)
                    .toggleClass('opacity-50 cursor-not-allowed pointer-events-none', disabled)
                    .attr('title', title);

                $('#btnAddItemsEmpty')
                    .prop('disabled', disabled)
                    .toggleClass('opacity-50 cursor-not-allowed pointer-events-none', disabled)
                    .attr('title', title);

                // Keep the Add/Edit modal's "Today" quick-select in sync too,
                // in case it's already open (or opens later) while the
                // inventory check is still resolving.
                if (typeof updateScheduleQuickBtns === 'function') {
                    updateScheduleQuickBtns();
                }
            }

            function checkOpenInventoryAndToggleAddItemsButtons() {
                // Only today's shift status is relevant; skip the network
                // round-trip entirely when browsing another date so the
                // buttons don't flash into a disabled "checking" state.
                if (!isSelectedDateToday()) {
                    applyAddItemsButtonState();
                    return;
                }

                openInventoryStatus = 'checking';
                applyAddItemsButtonState();

                $.ajax({
                    url: baseUrl + 'Inventory/CheckActiveInventories',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        openInventoryStatus = (response && response.success && response.has_active) ?
                            'open' : 'closed';
                        applyAddItemsButtonState();
                    },
                    error: function() {
                        // Fail closed: if we can't confirm an open inventory,
                        // don't allow new distribution items to be added
                        // (only matters when we're actually gating, i.e. today).
                        openInventoryStatus = 'closed';
                        applyAddItemsButtonState();
                    }
                });
            }

            applyAddItemsButtonState();
            getProducts();
            loadProductCostData();
            loadDistributionByDate();
            renderCalendar();
            loadMonthDistributions();
            loadAllDistributions();
            checkOpenInventoryAndToggleAddItemsButtons();
            loadStoreAddedItems();
            // ===== API FUNCTIONS =====

            function getProducts() {
                $.ajax({
                    url: baseUrl + 'Distribution/GetProducts',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data) {
                            productsData = response.data;
                            mergeProductCostRecords(productsData, {
                                preserveExistingCostFields: true
                            });
                        }

                        const selectedDate = ($('#selectedDate').val() || '').toString();
                        const displayState = getDisplayStateForSelectedGroup(
                            selectedDate,
                            currentDayDistributionItems,
                            currentDayGroupedData,
                            currentDaySummary
                        );
                        const daySummary = getDayScopedSummary(currentDaySummary);

                        updateSummaryCounts(displayState.items, displayState.summary, selectedDate);
                        refreshDayTotalsDisplay(selectedDate);
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

            function mergeProductCostRecords(records, options = {}) {
                const recordArray = Array.isArray(records) ? records : [];
                const preserveExistingCostFields = Boolean(options && options.preserveExistingCostFields);
                const protectedCostFields = [
                    'direct_cost',
                    'combined_recipe_cost',
                    'overhead_cost_percentage',
                    'overhead_cost_amount',
                    'total_cost',
                    'pieces_per_yield',
                    'trays_per_yield',
                    'selling_price',
                    'selling_price_per_piece'
                ];

                console.log('📦 [mergeProductCostRecords] received', recordArray.length, 'records');
                if (recordArray.length > 0) {
                    console.log('   Sample product:', recordArray[0].product_id, recordArray[0].product_name,
                        '| combined_recipe_cost:', recordArray[0].combined_recipe_cost);
                }

                recordArray.forEach(function(record) {
                    const productId = String(record.product_id || '').trim();
                    if (!productId) return;

                    const existingRecord = Object.assign({}, productCostMap[productId] || {});
                    const mergedRecord = Object.assign({}, record);

                    if (preserveExistingCostFields) {
                        protectedCostFields.forEach(function(field) {
                            const existingRaw = existingRecord[field];
                            const incomingRaw = mergedRecord[field];
                            const existingValue = parseNumericValue(existingRaw);
                            const incomingValue = parseNumericValue(incomingRaw);

                            const incomingLooksMissing = incomingRaw === undefined ||
                                incomingRaw === null || incomingRaw === '';
                            const incomingLooksDefaulted = incomingValue <= 0 && existingValue > 0;

                            if ((incomingLooksMissing || incomingLooksDefaulted) && existingRaw !==
                                undefined) {
                                mergedRecord[field] = existingRaw;
                            }
                        });
                    }

                    productCostMap[productId] = Object.assign({}, existingRecord, mergedRecord);
                });
            }

            function loadProductCostData() {
                $.ajax({
                    url: baseUrl + 'Products/GetAll',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.success && Array.isArray(response.data)) {
                            console.log('\ud83d\udd04 [loadProductCostData] Merging', response.data
                                .length, 'products with cost details');
                            mergeProductCostRecords(response.data, {
                                preserveExistingCostFields: false
                            });
                            const selectedDateForStoreRefresh = ($('#selectedDate').val() || '')
                                .toString();
                            if (storeGroupByDate[selectedDateForStoreRefresh]) {
                                renderDistributionList(currentDayDistributionItems,
                                    currentDayGroupedData, selectedDateForStoreRefresh);
                            }
                            // Re-decorate existing items with new cost data (including combined_recipe_cost) instead of re-fetching
                            if (currentDayDistributionItems.length > 0) {
                                console.log('\ud83d\udd04 [loadProductCostData] Re-decorating',
                                    currentDayDistributionItems.length,
                                    'items with enriched product costs');
                                currentDayDistributionItems = decorateDistributionItems(
                                    currentDayDistributionItems, currentSelectedDate);

                                // Re-aggregate and re-render with updated costs
                                const selectedDate = ($('#selectedDate').val() || '').toString();
                                const displayState = getDisplayStateForSelectedGroup(
                                    selectedDate,
                                    currentDayDistributionItems,
                                    currentDayGroupedData,
                                    currentDaySummary
                                );
                                const daySummary = getDayScopedSummary(currentDaySummary);

                                updateSummaryCounts(displayState.items, displayState.summary,
                                    selectedDate);
                                refreshDayTotalsDisplay(selectedDate);
                                renderOwnerAnalytics(displayState.groups, displayState.summary);
                            }
                        }

                        renderAllDistributionsList();
                    },
                    error: function() {
                        // Keep using lightweight product data fallback.
                    }
                });
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

            function getProductTraysPerYield(product) {
                const parsed = parseNumericValue(product && product.trays_per_yield);
                return parsed > 0 ? parsed : 0;
            }

            function getProductBatchPiecesPerYield(product) {
                const traysPerYield = getProductTraysPerYield(product);
                const piecesPerYield = getProductPiecesPerYield(product);

                return traysPerYield > 0 ? traysPerYield * piecesPerYield : piecesPerYield;
            }

            function getProductBoxPieces(product) {
                return getProductPiecesPerYield(product);
            }

            function getDistributionPieces(item, product) {
                const quantity = parseNumericValue(item && item.product_qnty);
                const qtyMode = ((item && item.qty_mode) || 'batch').toLowerCase();
                const category = ((product && product.category) || '').toLowerCase();
                const batchPiecesPerYield = getProductBatchPiecesPerYield(product);

                if (qtyMode === 'pieces') {
                    return quantity;
                }

                if (category === 'drinks' || category === 'grocery') {
                    return quantity;
                }

                if (qtyMode === 'box') {
                    return quantity * getProductBoxPieces(product);
                }

                return quantity * batchPiecesPerYield;
            }

            function calculateTotalDistributionPieces(items) {
                return (Array.isArray(items) ? items : []).reduce(function(sum, item) {
                    const productData = getProductAnalyticsData(item && item.product_id);
                    return sum + getDistributionPieces(item, productData);
                }, 0);
            }

            window.calculateTotalDistributionPieces = calculateTotalDistributionPieces;

            function getDistributionYieldUnits(item, product) {
                const quantity = parseNumericValue(item && item.product_qnty);
                const qtyMode = ((item && item.qty_mode) || 'batch').toLowerCase();
                const category = ((product && product.category) || '').toLowerCase();
                const traysPerYield = getProductTraysPerYield(product);
                const batchPiecesPerYield = getProductBatchPiecesPerYield(product);

                if (qtyMode === 'pieces') {
                    return batchPiecesPerYield > 0 ? (quantity / batchPiecesPerYield) : quantity;
                }

                if (category === 'drinks' || category === 'grocery') {
                    return quantity;
                }

                if (qtyMode === 'box') {
                    if (traysPerYield > 0) {
                        return quantity / traysPerYield;
                    }

                    const pieces = getDistributionPieces(item, product);
                    return batchPiecesPerYield > 0 ? (pieces / batchPiecesPerYield) : quantity;
                }

                return quantity;
            }

            function resolveProductOverheadCostPerYield(product) {
                const directCost = parseNumericValue(product && product.direct_cost);
                let overheadCostAmount = parseNumericValue(product && product.overhead_cost_amount);

                if (overheadCostAmount <= 0 && directCost > 0) {
                    const overheadCostPercentage = parseNumericValue(product && product.overhead_cost_percentage);
                    if (overheadCostPercentage > 0) {
                        overheadCostAmount = directCost * (overheadCostPercentage / 100);
                    }
                }

                return overheadCostAmount > 0 ? overheadCostAmount : 0;
            }

            function resolveProductTotalCostPerYield(product) {
                if (!product) {
                    console.warn('⚠️  [resolveProductTotalCostPerYield] product is null/undefined');
                    return 0;
                }
                const productId = product.product_id;
                const productName = product.product_name || 'unknown';
                const storedTotal = parseNumericValue(product && product.total_cost);
                const directCost = parseNumericValue(product && product.direct_cost);
                const combinedRecipeCost = parseNumericValue(product && product.combined_recipe_cost);
                const overheadCostAmount = resolveProductOverheadCostPerYield(product);
                const directPlusCombined = directCost + combinedRecipeCost;
                const composedWithOverhead = directPlusCombined + overheadCostAmount;
                const costTolerance = 0.009;

                // Stored product_costs.total_cost is inconsistent: some rows include
                // combined recipes, some do not. Never skip combined cost when it is
                // clearly missing, or group totals drift further with every extra item.
                if (storedTotal > 0) {
                    const alreadyIncludesCombined = combinedRecipeCost <= 0 ||
                        storedTotal + costTolerance >= directPlusCombined ||
                        storedTotal + costTolerance >= composedWithOverhead;
                    const resolvedStoredTotal = alreadyIncludesCombined ?
                        storedTotal :
                        (storedTotal + combinedRecipeCost);

                    console.log('✓ [resolveProductTotalCostPerYield] Product', productId, productName,
                        'stored total_cost =', storedTotal, '| combined:', combinedRecipeCost,
                        '| resolved =', resolvedStoredTotal);
                    return resolvedStoredTotal;
                }

                const resolvedFallbackTotal = composedWithOverhead;
                console.log('⚠️  [resolveProductTotalCostPerYield] FALLBACK for Product', productId, productName,
                    '{direct:', directCost, '+ combined:', combinedRecipeCost, '+ overhead:',
                    overheadCostAmount, '} = TOTAL:', resolvedFallbackTotal);
                return resolvedFallbackTotal > 0 ? resolvedFallbackTotal : 0;
            }

            function calculateItemTotalCost(item, product) {
                const totalCostPerYield = resolveProductTotalCostPerYield(product);
                if (totalCostPerYield <= 0) return 0;

                const qty = parseNumericValue(item && item.product_qnty);
                const qtyMode = (item && item.qty_mode) || 'batch';
                const yieldsNeeded = getDistributionYieldUnits(item, product);
                // Multiply full-precision yield cost, then round once per item (see applyItemCostTotals).
                const itemTotal = yieldsNeeded > 0 ? (yieldsNeeded * totalCostPerYield) : 0;
                console.log('💰 [calculateItemTotalCost] id:', product && product.product_id, product && product
                    .product_name, '| qty:', qty, qtyMode, '| costPerYield:', totalCostPerYield, '| yields:',
                    yieldsNeeded, '=> itemTotal:', itemTotal);
                return itemTotal;
            }

            function applyItemCostTotals(item, product) {
                const decoratedItem = item || {};
                const productData = product || getProductAnalyticsData(decoratedItem.product_id);

                decoratedItem.total_cost = roundCurrency(calculateItemTotalCost(decoratedItem, productData));
                decoratedItem.overhead_cost = roundCurrency(calculateItemOverheadCost(decoratedItem, productData));

                const computedAdditionalCost = calculateItemAdditionalCost(decoratedItem, productData);
                decoratedItem.additional_cost = roundCurrency(computedAdditionalCost);

                const unitCostPerPiece = resolveProductTotalCostPerPiece(productData);
                const additionalCostPerPiece = resolveProductAdditionalCostPerPiece(productData);
                decoratedItem.unit_cost_per_piece = unitCostPerPiece;
                decoratedItem.additional_cost_per_piece = additionalCostPerPiece;
                decoratedItem.total_price_per_piece = unitCostPerPiece + additionalCostPerPiece;

                return decoratedItem;
            }

            function calculateItemOverheadCost(item, product) {
                const overheadCostPerYield = resolveProductOverheadCostPerYield(product);
                if (overheadCostPerYield <= 0) return 0;

                const yieldsNeeded = getDistributionYieldUnits(item, product);

                return yieldsNeeded > 0 ? (yieldsNeeded * overheadCostPerYield) : 0;
            }

            function resolveProductAdditionalCostPerYield(product) {
                return parseNumericValue(product && product.combined_recipe_cost);
            }

            function resolveProductTotalCostPerPiece(product) {
                const totalCostPerYield = resolveProductTotalCostPerYield(product);
                const piecesPerYield = getProductBatchPiecesPerYield(product);
                return (totalCostPerYield > 0 && piecesPerYield > 0) ? (totalCostPerYield / piecesPerYield) : 0;
            }

            function resolveProductAdditionalCostPerPiece(product) {
                const additionalCostPerYield = resolveProductAdditionalCostPerYield(product);
                const piecesPerYield = getProductBatchPiecesPerYield(product);
                return (additionalCostPerYield > 0 && piecesPerYield > 0) ? (additionalCostPerYield /
                    piecesPerYield) : 0;
            }

            function calculateItemAdditionalCost(item, product) {
                const additionalCostPerYield = resolveProductAdditionalCostPerYield(product);
                if (additionalCostPerYield <= 0) return 0;

                const yieldsNeeded = getDistributionYieldUnits(item, product);
                return yieldsNeeded > 0 ? (yieldsNeeded * additionalCostPerYield) : 0;
            }

            function calculateItemAdditionalCostPerPiece(item, product) {
                const additionalCostTotal = calculateItemAdditionalCost(item, product);
                if (additionalCostTotal <= 0) return 0;

                const pieces = getDistributionPieces(item, product);
                return pieces > 0 ? (additionalCostTotal / pieces) : 0;
            }

            function decorateDistributionItems(items, fallbackDate = '') {
                const normalizedItems = applyLocalDistributionGroupMeta(items, fallbackDate);

                return normalizedItems.map(function(item) {
                    const decoratedItem = Object.assign({}, item);
                    const productData = getProductAnalyticsData(decoratedItem.product_id);
                    const quantity = parseNumericValue(decoratedItem.product_qnty);

                    // ─────────────────────────────────────────────────────────────
                    // Compute forecasted sales
                    // Priority: explicit value > computed from product pricing
                    // ─────────────────────────────────────────────────────────────
                    const explicitForecast = parseNumericValue(decoratedItem.forecasted_sales);
                    const computedForecast = quantity * getForecastUnitPrice(productData, decoratedItem
                        .qty_mode || 'batch');
                    decoratedItem.forecasted_sales = explicitForecast > 0 ? explicitForecast :
                        computedForecast;

                    // ─────────────────────────────────────────────────────────────
                    // Compute total cost
                    // Priority: explicit value > product total_cost > calculated
                    // Use product's total_cost field (cost to produce one batch/piece/yield)
                    // ─────────────────────────────────────────────────────────────
                    const explicitTotalCost = parseNumericValue(decoratedItem.total_cost);
                    let computedTotalCost = 0;

                    if (explicitTotalCost > 0) {
                        // Use explicit value if available
                        computedTotalCost = explicitTotalCost;
                    } else if (productData && parseNumericValue(productData.total_cost) > 0) {
                        // Use tray-aware yield conversion so box and piece modes match backend costing.
                        computedTotalCost = calculateItemTotalCost(decoratedItem, productData);
                    } else {
                        // Fallback: calculate from product data
                        computedTotalCost = calculateItemTotalCost(decoratedItem, productData);
                    }

                    decoratedItem.total_cost = computedTotalCost;

                    // ─────────────────────────────────────────────────────────────
                    // Compute overhead cost
                    // Priority: explicit value > product overhead_cost_amount > calculated
                    // ─────────────────────────────────────────────────────────────
                    const explicitOverheadCost = parseNumericValue(decoratedItem.overhead_cost);
                    let computedOverheadCost = 0;

                    if (explicitOverheadCost > 0) {
                        // Use explicit value if available
                        computedOverheadCost = explicitOverheadCost;
                    } else if (productData && parseNumericValue(productData.overhead_cost_amount) > 0) {
                        // Use product's overhead cost
                        computedOverheadCost = calculateItemOverheadCost(decoratedItem, productData);
                    } else {
                        // Fallback: calculate from product data
                        computedOverheadCost = calculateItemOverheadCost(decoratedItem, productData);
                    }

                    decoratedItem.overhead_cost = computedOverheadCost;

                    const explicitAdditionalCost = parseNumericValue(decoratedItem.additional_cost);
                    let computedAdditionalCost = calculateItemAdditionalCost(decoratedItem, productData);
                    if (computedAdditionalCost <= 0 && explicitAdditionalCost > 0) {
                        computedAdditionalCost = explicitAdditionalCost;
                    }

                    decoratedItem.additional_cost = computedAdditionalCost;

                    const piecesPerYield = getProductPiecesPerYield(productData);
                    const unitCostPerPiece = resolveProductTotalCostPerPiece(productData);
                    const additionalCostPerPiece = resolveProductAdditionalCostPerPiece(productData);
                    const explicitAdditionalCostPerPiece = (explicitAdditionalCost > 0 && piecesPerYield >
                            0) ?
                        (explicitAdditionalCost / piecesPerYield) : 0;

                    decoratedItem.unit_cost_per_piece = unitCostPerPiece;
                    decoratedItem.additional_cost_per_piece = additionalCostPerPiece > 0 ?
                        additionalCostPerPiece :
                        (explicitAdditionalCostPerPiece > 0 ? explicitAdditionalCostPerPiece :
                            calculateItemAdditionalCostPerPiece(decoratedItem, productData));
                    decoratedItem.total_price_per_piece =
                        parseNumericValue(decoratedItem.unit_cost_per_piece) +
                        parseNumericValue(decoratedItem.additional_cost_per_piece);

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
                        group_note: (groupMeta.group_note || '').toString(),
                        dist_category_id: (groupMeta.dist_category_id != null ? String(groupMeta
                            .dist_category_id) : ''),
                        dist_category_name: (groupMeta.dist_category_name || '').toString()
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
                    const dateValue = ((enrichedItem && enrichedItem.distribution_date) || fallbackDate ||
                        '').toString().trim();
                    const productId = enrichedItem ? enrichedItem.product_id : null;
                    const explicitGroupKey = ((enrichedItem && enrichedItem.distribution_group_key) || '')
                        .toString().trim();
                    const hasPersistedGroupKey = isPersistedDistributionGroupKey(explicitGroupKey);
                    const localMeta = getLocalDistributionGroupMeta(dateValue, productId);

                    if (localMeta && !hasPersistedGroupKey) {
                        if (!enrichedItem.distribution_group_key) {
                            enrichedItem.distribution_group_key = localMeta.group_key;
                        }
                        if (!enrichedItem.distribution_group_name) {
                            enrichedItem.distribution_group_name = localMeta.group_name;
                        }
                        if (!enrichedItem.distribution_group_note) {
                            enrichedItem.distribution_group_note = localMeta.group_note;
                        }
                        if (!enrichedItem.dist_category_id && localMeta.dist_category_id) {
                            enrichedItem.dist_category_id = localMeta.dist_category_id;
                        }
                        if (!enrichedItem.distribution_category_name && localMeta.dist_category_name) {
                            enrichedItem.distribution_category_name = localMeta.dist_category_name;
                        }
                    }

                    return enrichedItem;
                });
            }

            function getDistributionGroupKey(item, fallbackDate = '') {
                const displayKey = ((item && item.distribution_display_group_key) || '').toString().trim();
                if (displayKey) return displayKey;

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

            function groupDistributionsByGroup(items, fallbackDate = '') {
                const groupedMap = {};

                (items || []).forEach(function(item) {
                    const groupKey = getDistributionGroupKey(item, fallbackDate);
                    const categoryId = parseInt((item && (item.dist_category_id ?? item
                            .distribution_category_id ?? item.category_id ?? item.dist_cat_id)) || 0,
                        10);

                    if (!groupedMap[groupKey]) {
                        groupedMap[groupKey] = {
                            group_key: groupKey,
                            group_name: getDistributionGroupName(item, fallbackDate),
                            group_note: getDistributionGroupNote(item),
                            dist_category_id: categoryId > 0 ? categoryId : 0,
                            total_items: 0,
                            total_batches: 0,
                            total_pieces: 0,
                            forecasted_sales: 0,
                            total_cost: 0,
                            raw_material_usage_total: [],
                            _raw_material_usage_map: {},
                            source_group_ids: [],
                            items: [],
                        };
                    }

                    if (categoryId > 0 && (!groupedMap[groupKey].dist_category_id || groupedMap[groupKey]
                            .dist_category_id <= 0)) {
                        groupedMap[groupKey].dist_category_id = categoryId;
                    }

                    const sourceGroupIds = getItemSourceGroupIds(item);
                    sourceGroupIds.forEach(function(groupId) {
                        if (groupedMap[groupKey].source_group_ids.indexOf(groupId) === -1) {
                            groupedMap[groupKey].source_group_ids.push(groupId);
                        }
                    });

                    const quantity = parseNumericValue(item.product_qnty);
                    const qtyMode = (item.qty_mode || 'batch').toLowerCase();
                    const productData = getProductAnalyticsData(item.product_id);

                    groupedMap[groupKey].total_items += 1;
                    if (qtyMode !== 'pieces') {
                        groupedMap[groupKey].total_batches += quantity;
                    }
                    groupedMap[groupKey].total_pieces += getDistributionPieces(item, productData);

                    const fallbackForecast = quantity * getForecastUnitPrice(
                        productData,
                        qtyMode
                    );

                    groupedMap[groupKey].forecasted_sales += hasPersistedNumericValue(item,
                            'forecasted_sales') ?
                        parseNumericValue(item.forecasted_sales) :
                        fallbackForecast;
                    groupedMap[groupKey].total_cost += parseNumericValue(item.total_cost);

                    (Array.isArray(item.raw_material_usage) ? item.raw_material_usage : []).forEach(
                        function(material) {
                            mergeMaterialUsageEntry(groupedMap[groupKey]._raw_material_usage_map,
                                material);
                        });

                    groupedMap[groupKey].items.push(item);
                });

                return Object.values(groupedMap).map(function(group) {
                    const normalizedGroup = Object.assign({}, group);
                    normalizedGroup.raw_material_usage_total = materialUsageMapToArray(normalizedGroup
                        ._raw_material_usage_map);
                    delete normalizedGroup._raw_material_usage_map;
                    normalizedGroup.items = mergeGroupItemsByProduct(normalizedGroup.items);
                    normalizedGroup.total_items = normalizedGroup.items.length;
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

                const totalBatches = group ?
                    parseNumericValue(group.total_batches) :
                    groupItems.reduce(function(sum, item) {
                        return sum + (((item.qty_mode || 'batch') !== 'pieces') ? parseNumericValue(item
                            .product_qnty) : 0);
                    }, 0);

                const totalPieces = group ?
                    parseNumericValue(group.total_pieces) :
                    calculateTotalDistributionPieces(groupItems);

                const forecastTotal = resolveGroupForecastedSales(group, groupItems);

                const totalCostTotal = resolveGroupTotalCost(group, groupItems);
                const additionalCostTotal = calculateAdditionalCostTotal(groupItems);

                const overheadCostTotal = resolveGroupOverheadCost(group, groupItems);

                return {
                    total_items: groupItems.length,
                    total_groups: groupItems.length > 0 ? 1 : 0,
                    total_batches: totalBatches,
                    total_pieces: totalPieces,
                    forecasted_sales_total: forecastTotal,
                    total_cost_total: totalCostTotal,
                    additional_cost_total: additionalCostTotal,
                    overhead_cost_total: overheadCostTotal,
                    raw_material_usage_total: Array.isArray(group && group.raw_material_usage_total) ?
                        group.raw_material_usage_total : [],
                };
            }

            function hasPersistedNumericValue(source, fieldName) {
                if (!source || typeof source !== 'object') {
                    return false;
                }

                if (!Object.prototype.hasOwnProperty.call(source, fieldName)) {
                    return false;
                }

                const rawValue = source[fieldName];
                return rawValue !== null && rawValue !== '';
            }

            function resolveGroupForecastedSales(group, items) {
                if (hasPersistedNumericValue(group, 'forecasted_sales')) {
                    return parseNumericValue(group.forecasted_sales);
                }

                return calculateForecastedSalesTotal(items);
            }

            function resolveGroupTotalCost(group, items) {
                const normalizedItems = Array.isArray(items) ? items : [];
                if (normalizedItems.length > 0) {
                    return normalizedItems.reduce(function(sum, item) {
                        return sum + parseNumericValue(item.total_cost);
                    }, 0);
                }

                if (hasPersistedNumericValue(group, 'total_cost')) {
                    return parseNumericValue(group.total_cost);
                }

                return normalizedItems.reduce(function(sum, item) {
                    return sum + parseNumericValue(item.total_cost);
                }, 0);
            }

            function resolveGroupOverheadCost(group, items) {
                if (hasPersistedNumericValue(group, 'overhead_cost')) {
                    return parseNumericValue(group.overhead_cost);
                }

                return (Array.isArray(items) ? items : []).reduce(function(sum, item) {
                    return sum + parseNumericValue(item.overhead_cost);
                }, 0);
            }

            function getDayScopedSummary(summary = null) {
                if (summary && typeof summary === 'object' && Object.keys(summary).length > 0) {
                    return summary;
                }

                if (currentDaySummary && typeof currentDaySummary === 'object') {
                    return currentDaySummary;
                }

                return {};
            }

            function getDayScopedSummaryWithStore(dateStr) {
                const targetDate = (dateStr || $('#selectedDate').val() || '').toString();
                const base = getDayScopedSummary(currentDaySummary) || {};
                const store = storeGroupByDate[targetDate];

                if (!store) return base;

                const storeOverhead = store.items.reduce((sum, item) => sum + parseNumericValue(item.overhead_cost),
                    0);

                return Object.assign({}, base, {
                    total_items: parseNumericValue(base.total_items) + store.items.length,
                    total_pieces: parseNumericValue(base.total_pieces) + parseNumericValue(store
                        .total_pieces),
                    forecasted_sales_total: parseNumericValue(base.forecasted_sales_total) +
                        parseNumericValue(store.forecasted_sales),
                    total_cost_total: parseNumericValue(base.total_cost_total) + parseNumericValue(store
                        .total_cost),
                    overhead_cost_total: parseNumericValue(base.overhead_cost_total) + storeOverhead,
                });
            }

            // Central place to refresh the "entire day" cards (forecasted sales,
            // total cost, overhead) so distributions + Store always stay in sync.
            function refreshDayTotalsDisplay(dateStr) {
                const targetDate = (dateStr || $('#selectedDate').val() || '').toString();
                const mergedSummary = getDayScopedSummaryWithStore(targetDate);
                updateForecastedSales(currentDayDistributionItems, mergedSummary);
                renderOwnerDayMetrics(mergedSummary);
            }

            function setSelectedGroupFilter(dateStr, groupKey) {
                selectedGroupFilter = {
                    date: (dateStr || '').toString().trim(),
                    key: (groupKey || '').toString().trim(),
                };
            }

            function clearSelectedGroupFilter(dateStr = null) {
                if (dateStr == null) {
                    selectedGroupFilter = {
                        date: '',
                        key: ''
                    };
                    return;
                }

                const normalizedDate = (dateStr || '').toString().trim();
                if ((selectedGroupFilter.date || '') === normalizedDate) {
                    selectedGroupFilter = {
                        date: '',
                        key: ''
                    };
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
                return Number.isFinite(value) ?
                    value.toLocaleString('en-PH', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 4
                    }) :
                    '0';
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

            function computeTotalCostFromRawMaterialUsage(usage) {
                return (Array.isArray(usage) ? usage : []).reduce(function(sum, material) {
                    return sum + parseNumericValue(material.line_cost);
                }, 0);
            }

            function mergeGroupItemsByProduct(items) {
                const mergedMap = {};
                const order = [];

                (Array.isArray(items) ? items : []).forEach(function(item) {
                    const productId = String(item && item.product_id || '').trim();
                    const qtyMode = ((item && item.qty_mode) || 'batch').toLowerCase();
                    const mergeKey = productId + '::' + qtyMode;

                    if (!mergedMap[mergeKey]) {
                        const clonedItem = Object.assign({}, item);
                        clonedItem.product_qnty = parseNumericValue(item.product_qnty);
                        clonedItem.forecasted_sales = parseNumericValue(item.forecasted_sales);
                        clonedItem.total_cost = parseNumericValue(item.total_cost);
                        clonedItem.overhead_cost = parseNumericValue(item.overhead_cost);
                        clonedItem.additional_cost = parseNumericValue(item.additional_cost);
                        clonedItem.raw_material_usage = Array.isArray(item.raw_material_usage) ?
                            item.raw_material_usage.slice() : [];

                        const mergedIds = [];
                        const initialId = getDistributionItemId(item);
                        if (initialId) mergedIds.push(initialId);
                        clonedItem._merged_item_ids = mergedIds;

                        mergedMap[mergeKey] = clonedItem;
                        order.push(mergeKey);
                    } else {
                        const existing = mergedMap[mergeKey];
                        existing.product_qnty += parseNumericValue(item.product_qnty);
                        existing.forecasted_sales += parseNumericValue(item.forecasted_sales);
                        existing.total_cost += parseNumericValue(item.total_cost);
                        existing.overhead_cost += parseNumericValue(item.overhead_cost);
                        existing.additional_cost += parseNumericValue(item.additional_cost);

                        const materialMap = {};
                        existing.raw_material_usage.forEach(function(material) {
                            mergeMaterialUsageEntry(materialMap, material);
                        });
                        (Array.isArray(item.raw_material_usage) ? item.raw_material_usage : []).forEach(
                            function(material) {
                                mergeMaterialUsageEntry(materialMap, material);
                            });
                        existing.raw_material_usage = materialUsageMapToArray(materialMap);

                        const mergeId = getDistributionItemId(item);
                        if (mergeId && existing._merged_item_ids.indexOf(mergeId) === -1) {
                            existing._merged_item_ids.push(mergeId);
                        }
                    }
                });

                return order.map(function(key) {
                    return mergedMap[key];
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

            async function accumulateRawMaterialUsage(productId, yieldsNeeded, piecesNeeded, materialMap,
                visitedProducts = new Set()) {
                const key = String(productId || '').trim();
                if (!key || yieldsNeeded <= 0) return;
                if (visitedProducts.has(key)) return;

                const currentProduct = await fetchProductDetail(key);
                if (!currentProduct) return;

                const nextVisited = new Set(visitedProducts);
                nextVisited.add(key);

                const ingredients = Array.isArray(currentProduct.ingredients) ? currentProduct.ingredients : [];
                ingredients.forEach(function(ingredient) {
                    const quantityPerYield = parseNumericValue(ingredient.quantity ?? ingredient
                        .quantity_needed);
                    if (quantityPerYield <= 0) return;

                    const amount = quantityPerYield * yieldsNeeded;
                    const lineCost = amount * parseNumericValue(ingredient.cost_per_unit);

                    mergeMaterialUsageEntry(materialMap, {
                        material_id: ingredient.material_id,
                        material_name: ingredient.material_name || ('Material #' + (ingredient
                            .material_id ?? 'N/A')),
                        unit: ingredient.unit || '',
                        amount: amount,
                        line_cost: lineCost,
                    });
                });

                const combinedRecipes = Array.isArray(currentProduct.combined_recipes) ? currentProduct
                    .combined_recipes : [];

                for (const combinedRecipe of combinedRecipes) {
                    const sourceProductId = parseNumericValue(combinedRecipe.source_product_id || combinedRecipe
                        .id);
                    if (!sourceProductId) continue;

                    const gramsPerPiece = parseNumericValue(combinedRecipe.grams_per_piece ?? combinedRecipe
                        .gramsPerPiece);
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
                let productData = getProductAnalyticsData(item.product_id);
                if (!productData) {
                    productData = await fetchProductDetail(item.product_id);
                }
                if (!productData) return [];

                const pieces = getDistributionPieces(item, productData);
                if (pieces <= 0) return [];

                // Keep raw-material costing aligned with item/group total-cost conversion.
                const yieldsNeeded = getDistributionYieldUnits(item, productData);
                if (yieldsNeeded <= 0) return [];

                const materialMap = {};
                await accumulateRawMaterialUsage(item.product_id, yieldsNeeded, pieces, materialMap);
                return materialUsageMapToArray(materialMap);
            }

            async function hydrateOwnerRawMaterialAnalytics(selectedDate, decoratedItems, summaryTemplate = {}) {
                if (!isOwnerView) {
                    return;
                }

                const targetDate = (selectedDate || '').toString().trim();
                if (!targetDate) {
                    return;
                }

                if (!Array.isArray(decoratedItems) || decoratedItems.length === 0) {
                    const emptySummary = Object.assign({}, summaryTemplate, {
                        raw_material_usage_total: []
                    });
                    currentDaySummary = emptySummary;

                    const emptyDisplayState = getDisplayStateForSelectedGroup(targetDate, [], [], emptySummary);
                    updateSummaryCounts(emptyDisplayState.items, emptyDisplayState.summary, targetDate);
                    refreshDayTotalsDisplay(targetDate);
                    renderOwnerAnalytics(emptyDisplayState.groups, emptyDisplayState.summary);
                    return;
                }

                const requestToken = ++ownerRawUsageHydrationToken;

                const usagePromises = decoratedItems.map(async function(item) {
                    try {
                        const usage = await computeRawMaterialUsageForItem(item);
                        const recomputedTotalCost = usage.length > 0 ?
                            computeTotalCostFromRawMaterialUsage(usage) :
                            parseNumericValue(item.total_cost);
                        return Object.assign({}, item, {
                            raw_material_usage: usage,
                            total_cost: recomputedTotalCost,
                        });
                    } catch (error) {
                        return Object.assign({}, item, {
                            raw_material_usage: []
                        });
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
                    (Array.isArray(item.raw_material_usage) ? item.raw_material_usage : []).forEach(
                        function(material) {
                            mergeMaterialUsageEntry(dayMaterialMap, material);
                        });
                });

                const ownerSummary = Object.assign({}, summaryTemplate, {
                    raw_material_usage_total: materialUsageMapToArray(dayMaterialMap),
                    // Keep the day-level Total Cost card consistent with the same
                    // raw-material-derived costs now stored on each item.
                    total_cost_total: ownerDecoratedItems.reduce(function(sum, item) {
                        return sum + parseNumericValue(item.total_cost);
                    }, 0),
                });

                currentDayDistributionItems = ownerDecoratedItems;
                currentDayGroupedData = groupDistributionsByGroup(ownerDecoratedItems, targetDate);
                currentDaySummary = ownerSummary;

                // These were computed with cached costs on first render — refresh them now
                // that item.total_cost reflects raw material usage.
                renderDistributionList(currentDayDistributionItems, currentDayGroupedData, targetDate);
                renderMobileCards(currentDayDistributionItems, currentDayGroupedData, targetDate);

                const displayState = getDisplayStateForSelectedGroup(
                    targetDate,
                    currentDayDistributionItems,
                    currentDayGroupedData,
                    currentDaySummary
                );
                const daySummary = getDayScopedSummary(ownerSummary);

                updateSummaryCounts(displayState.items, displayState.summary, targetDate);
                refreshDayTotalsDisplay(targetDate);
                renderOwnerAnalytics(displayState.groups, displayState.summary);

                if (!$('#calendarDayModal').hasClass('hidden') && $('#calendarDayModal').data(
                        'selected-date') === targetDate) {
                    $('#calendarDayModal').data('day-items', ownerDecoratedItems);
                    $('#calendarDayModal').data('day-summary', ownerSummary);
                    updateModalForecastedSales(ownerDecoratedItems, ownerSummary);
                }
            }

            function renderOwnerDayMetrics(summary) {
                if (!isOwnerView) return;

                const totalCost = parseNumericValue(summary.total_cost_total);
                const overheadCost = parseNumericValue(summary.overhead_cost_total);

                $('#ownerTotalCostTotalDesktop').text('₱ ' + totalCost.toFixed(5));
                $('#ownerTotalCostTotalMobile').text('₱ ' + totalCost.toFixed(5));
                $('#ownerOverheadCostTotalDesktop').text('₱ ' + overheadCost.toFixed(5));
                $('#ownerOverheadCostTotalMobile').text('₱ ' + overheadCost.toFixed(5));
            }

            function renderOwnerAnalytics(groups, summary) {
                if (!isOwnerView) {
                    return;
                }

                const normalizedGroups = Array.isArray(groups) ? groups : [];
                const groupContainer = $('#ownerGroupAnalyticsContainer');
                const materialsContainer = $('#ownerDayRawMaterialUsage');
                const rawUsageHeading = $('#ownerRawUsageHeading');
                const forecastHeading = $('#ownerForecastTotalCostHeading');
                const selectedDateValue = ($('#selectedDate').val() || '').toString().trim();
                const activeScopeDate = (selectedGroupFilter.date || '').toString().trim();
                const activeScopeKey = (selectedGroupFilter.key || '').toString().trim();
                const isGroupScoped = Boolean(activeScopeDate && activeScopeKey && activeScopeDate ===
                    selectedDateValue);
                const scopedGroupName = isGroupScoped && normalizedGroups.length === 1 ?
                    (normalizedGroups[0].group_name || 'Selected Group') :
                    'Selected Group';

                $('#ownerGroupCountBadge').text(
                    `${normalizedGroups.length} ${normalizedGroups.length === 1 ? 'group' : 'groups'}`);

                rawUsageHeading.text(isGroupScoped ?
                    `Raw Material Usage (${scopedGroupName})` :
                    'Raw Material Usage (Entire Day)'
                );
                forecastHeading.text(isGroupScoped ?
                    `Forecast & Total Cost (${scopedGroupName})` :
                    'Per-Group Forecast & Total Cost'
                );

                const dayMaterials = Array.isArray(summary.raw_material_usage_total) ?
                    summary.raw_material_usage_total : [];

                if (dayMaterials.length === 0) {
                    materialsContainer.html(
                        `<p class="text-xs text-gray-400">${isGroupScoped ? 'No raw material usage for this selected group.' : 'No raw material usage for this date.'}</p>`
                    );
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
                    const groupTotal = parseNumericValue(group.total_cost);
                    const groupOverhead = resolveGroupOverheadCost(group, groupItems);
                    const groupNote = (group.group_note || '').toString().trim();

                    const itemsHtml = groupItems.map(function(item) {
                        const quantity = parseNumericValue(item.product_qnty);
                        const itemForecast = hasPersistedNumericValue(item, 'forecasted_sales') ?
                            parseNumericValue(item.forecasted_sales) :
                            (quantity * getForecastUnitPrice(
                                getProductAnalyticsData(item.product_id),
                                item.qty_mode || 'batch'
                            ));
                        const itemTotal = parseNumericValue(item.total_cost);
                        const unitPerPiece = parseNumericValue(item.unit_cost_per_piece);
                        const additionalPerPiece = parseNumericValue(item
                            .additional_cost_per_piece);
                        const totalPerPiece = parseNumericValue(item.total_price_per_piece);

                        return `
                            <div class="p-2 bg-gray-50 rounded-md border border-gray-100">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-gray-800 truncate">${item.product_name}</p>
                                        <p class="text-[11px] text-gray-500">${quantity} ${getQtyModeShortLabel(item.qty_mode || 'batch')}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[11px] text-primary font-semibold">${formatPesoAmount(itemForecast)}</p>
                                        <p class="text-[11px] text-emerald-600 font-semibold">${formatPesoAmount(itemTotal)}</p>
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
                                    <p class="text-[11px] font-semibold text-primary">Total Selling Price: ${formatPesoAmount(groupForecast)}</p>
                                    <p class="text-[11px] font-semibold text-emerald-600">Total Cost: ${formatPesoAmount(groupTotal)}</p>
                                    <p class="text-[11px] font-semibold text-amber-600">Overhead Cost: ${formatPesoAmount(groupOverhead)}</p>
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
                    const actualItems = dayItems.filter(function(item) {
                        return !(item && item.__empty_group_placeholder);
                    });
                    const batchQty = actualItems.reduce(function(sum, item) {
                        return sum + (((item.qty_mode || 'batch') !== 'pieces') ? parseNumericValue(
                            item.product_qnty) : 0);
                    }, 0);
                    const piecesQty = actualItems.reduce(function(sum, item) {
                        const productData = getProductAnalyticsData(item.product_id);
                        return sum + getDistributionPieces(item, productData);
                    }, 0);
                    const dayForecast = calculateForecastedSalesTotal(actualItems);
                    const groupCount = getDistinctGroupCount(dayItems, dateStr);
                    const previewNames = actualItems.slice(0, 2).map(item => item.product_name).join(', ');
                    const extraItems = actualItems.length > 2 ? ' +' + (actualItems.length - 2) + ' more' :
                        '';
                    const note = extractDistributionNote(dayItems);
                    const noteHtml = note ?
                        `<p class="text-[11px] text-amber-700 mt-1 truncate"><i class="fas fa-sticky-note mr-1 text-amber-500"></i>${note}</p>` :
                        '';

                    const row = `
                        <button type="button" class="all-distribution-entry w-full text-left p-3 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-100 transition-colors" data-date="${dateStr}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-800">${formatDateLabel(dateStr)}</p>
                                    <p class="text-xs text-gray-500 truncate">${previewNames || 'No product details'}${extraItems}</p>
                                    ${noteHtml}
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-xs font-semibold text-gray-700">${actualItems.length} ${actualItems.length === 1 ? 'item' : 'items'}</p>
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
                    data: {
                        date: date
                    },
                    dataType: 'json',
                    success: function(response) {
                        const responseNote = extractDistributionNote([], response.distribution_note ||
                            response.overall_note || response.note || response
                            .place_distributed_to || response.place_distributed || '');

                        if (response.success) {
                            // Flatten nested groups into a single array of items
                            // Each item gets decorated with its group metadata and pricing information
                            const flattenedItems = [];
                            const apiGroups = Array.isArray(response.data) ? response.data : [];

                            apiGroups.forEach(function(group) {
                                const groupItems = Array.isArray(group.items) ? group.items : [];
                                groupItems.forEach(function(item) {
                                    // Attach group-level information to each item
                                    const itemWithGroup = Object.assign({}, item, {
                                        // Group metadata
                                        distribution_id: group.id,
                                        dist_category_id: group
                                            .dist_category_id || group
                                            .category_id || group.dist_cat_id ||
                                            item.dist_category_id || item
                                            .category_id || item.dist_cat_id,
                                        distribution_group_key: 'group-' +
                                            String(group.id),
                                        distribution_display_group_key: getDistributionDisplayGroupKey(
                                            group),
                                        distribution_group_name: group.title,
                                        distribution_group_note: group
                                            .distributed_to_note || '',
                                        group_title: group.title,
                                        group_forecasted_sales: group
                                            .forecasted_sales,
                                        group_total_cost: group.total_cost,
                                        distributed_to_note: group
                                            .distributed_to_note,
                                        // Trust backend-computed values; don't override
                                    });
                                    flattenedItems.push(itemWithGroup);
                                });
                            });

                            const items = decorateDistributionItems(flattenedItems, date);
                            const groupedData = groupDistributionsByGroup(items, date);
                            const summary = Object.assign({
                                    date: date,
                                    total_items: items.length,
                                    total_groups: getDistinctGroupCount(items, date),
                                    total_batches: items.reduce((sum, item) => sum + (((item
                                            .qty_mode || 'batch') !== 'pieces') ?
                                        parseNumericValue(item.product_qnty) : 0), 0),
                                    total_pieces: calculateTotalDistributionPieces(items),
                                    forecasted_sales_total: calculateForecastedSalesTotal(items),
                                    total_cost_total: items.reduce((sum, item) => sum +
                                        parseNumericValue(item.total_cost), 0),
                                    additional_cost_total: calculateAdditionalCostTotal(items),
                                    raw_material_usage_total: []
                                },
                                response.daily_summary || {}
                            );

                            summary.total_items = items.length;
                            summary.total_groups = getDistinctGroupCount(items, date);
                            summary.total_batches = items.reduce((sum, item) => sum + (((item
                                .qty_mode || 'batch') !== 'pieces') ? parseNumericValue(item
                                .product_qnty) : 0), 0);
                            summary.total_pieces = calculateTotalDistributionPieces(items);
                            summary.forecasted_sales_total = calculateForecastedSalesTotal(items);
                            summary.total_cost_total = items.reduce((sum, item) => sum +
                                parseNumericValue(item.total_cost), 0);
                            summary.additional_cost_total = calculateAdditionalCostTotal(items);
                            summary.overhead_cost_total = items.reduce((sum, item) => sum +
                                parseNumericValue(item.overhead_cost), 0);

                            if (!Array.isArray(summary.raw_material_usage_total)) {
                                summary.raw_material_usage_total = [];
                            }

                            currentDayDistributionItems = items;
                            currentDayGroupedData = groupedData;
                            currentDaySummary = summary;

                            // For owner view, per-item/group Total Cost is corrected asynchronously by
                            // raw-material hydration. Show 0 on first paint instead of the stale cached
                            // total, so the number only ever appears once — correct.
                            let itemsForInitialRender = items;
                            let groupedDataForInitialRender = groupedData;
                            let summaryForInitialRender = summary;

                            if (isOwnerView) {
                                itemsForInitialRender = items.map(function(item) {
                                    return Object.assign({}, item, {
                                        total_cost: 0,
                                        overhead_cost: 0,
                                        additional_cost: 0,
                                    });
                                });
                                groupedDataForInitialRender = groupDistributionsByGroup(itemsForInitialRender, date);
                                summaryForInitialRender = Object.assign({}, summary, {
                                    total_cost_total: 0,
                                    overhead_cost_total: 0,
                                    additional_cost_total: 0,
                                });

                                currentDayDistributionItems = itemsForInitialRender;
                                currentDayGroupedData = groupedDataForInitialRender;
                                currentDaySummary = summaryForInitialRender;
                            }

                            renderDistributionList(itemsForInitialRender, groupedDataForInitialRender, date);
                            renderMobileCards(itemsForInitialRender, groupedDataForInitialRender, date);

                            const displayState = getDisplayStateForSelectedGroup(date, itemsForInitialRender,
                                groupedDataForInitialRender, summaryForInitialRender);
                            const daySummary = getDayScopedSummary(summaryForInitialRender);

                            updateSummaryCounts(displayState.items, displayState.summary, date);
                            refreshDayTotalsDisplay(date);
                            renderOwnerAnalytics(displayState.groups, displayState.summary);

                            updateMainDistributionNotePanels(displayState.items, responseNote);

                            if (isOwnerView) {
                                // Hydrate with the real (unzeroed) items — raw material usage is
                                // computed from product_id/qty, not from item.total_cost, so the
                                // zeroing above doesn't affect the hydration math.
                                hydrateOwnerRawMaterialAnalytics(date, items, summary);
                            }

                            if (!isOwnerView) {
                                setTimeout(function() {
                                    console.log(
                                        '[Re-Decorate] Re-rendering with enriched product costs (combined_recipe_cost)...'
                                    );
                                    const freshItems = decorateDistributionItems(
                                        currentDayDistributionItems, date);
                                    const freshGrouped = groupDistributionsByGroup(freshItems, date);
                                    const freshSummary = Object.assign({}, currentDaySummary, {
                                        total_cost_total: freshItems.reduce((s, i) => s +
                                            parseNumericValue(i.total_cost), 0),
                                        additional_cost_total: calculateAdditionalCostTotal(freshItems),
                                        overhead_cost_total: freshItems.reduce((s, i) => s +
                                            parseNumericValue(i.overhead_cost), 0)
                                    });
                                    currentDayDistributionItems = freshItems;
                                    currentDayGroupedData = freshGrouped;
                                    currentDaySummary = freshSummary;
                                    const freshState = getDisplayStateForSelectedGroup(date,
                                        freshItems, freshGrouped, freshSummary);
                                    refreshDayTotalsDisplay(date);
                                    renderOwnerAnalytics(freshState.groups, freshState.summary)
                                }, 600);
                            }

                            if (!$('#calendarDayModal').hasClass('hidden') && $('#calendarDayModal')
                                .data('selected-date') === date) {
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
                        console.error('%c[DISTRIBUTION ERROR]', 'color: #FF0000; font-weight: bold', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            error: error,
                            responseText: xhr.responseText,
                            date: date
                        });
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
                            const flattenedItems = flattenGroupedDataIfNeeded(response.data, '');
                            const decoratedItems = decorateDistributionItems(flattenedItems);
                            calendarData = groupDistributionsByDate(applyLocalDistributionGroupMeta(
                                decoratedItems));
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
                            const apiGroups = Array.isArray(response.data) ? response.data : [];
                            const flattenedItems = flattenGroupedDataIfNeeded(apiGroups, '');
                            const placeholderItems = [];

                            apiGroups.forEach(function(group) {
                                const groupItems = Array.isArray(group.items) ? group.items : [];
                                if (groupItems.length > 0) return;

                                placeholderItems.push({
                                    distribution_date: (group.distribution_date || '')
                                        .toString(),
                                    distribution_id: group.id,
                                    distribution_group_key: 'group-' + String(group.id),
                                    distribution_display_group_key: getDistributionDisplayGroupKey(
                                        group),
                                    distribution_group_name: group.title ||
                                        'Default Group',
                                    distribution_group_note: group
                                        .distributed_to_note || '',
                                    distributed_to_note: group.distributed_to_note ||
                                        '',
                                    product_id: null,
                                    product_name: '',
                                    product_qnty: 0,
                                    qty_mode: 'batch',
                                    forecasted_sales: 0,
                                    total_cost: 0,
                                    __empty_group_placeholder: true,
                                });
                            });

                            const decoratedItems = decorateDistributionItems(flattenedItems);
                            const normalizedItems = applyLocalDistributionGroupMeta(decoratedItems
                                .concat(placeholderItems));
                            allDistributionData = groupDistributionsByDate(normalizedItems);
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

            // OLD → Distribution/AddDistribution
            // NEW → Distribution/AddItem
            function addDistributionItemRequest(payload, rawQtyMode = null, allowInsufficient = false) {
                return new Promise(function(resolve, reject) {
                    const requestId = `add-item-${Date.now()}-${Math.floor(Math.random() * 1000)}`;
                    const normalizedDistributionId = normalizeDistributionGroupIdForApi(payload
                        .distribution_id);
                    const requestPayload = {
                        distribution_id: normalizedDistributionId,
                        product_id: payload.product_id,
                        product_qnty: payload.product_qnty,
                        qty_mode: payload.qty_mode,
                        allow_insufficient: allowInsufficient,
                    };

                    logDistributionFlow('log', 'Add distribution item request started.', {
                        request_id: requestId,
                        endpoint: 'Distribution/AddItem',
                        raw_distribution_id: payload.distribution_id,
                        normalized_distribution_id: normalizedDistributionId,
                        payload: requestPayload,
                        raw_qty_mode: rawQtyMode,
                    });

                    $.ajax({
                        url: baseUrl + 'Distribution/AddItem',
                        method: 'POST',
                        contentType: 'application/json',
                        dataType: 'json',
                        data: JSON.stringify(requestPayload),
                        success: function(response) {
                            logDistributionFlow('log',
                                'Add distribution item request succeeded.', {
                                    request_id: requestId,
                                    endpoint: 'Distribution/AddItem',
                                    response: response || {},
                                });
                            resolve(response || {});
                        },
                        error: function(xhr) {
                            logDistributionFlow('error',
                                'Add distribution item request failed.', {
                                    request_id: requestId,
                                    endpoint: 'Distribution/AddItem',
                                    status: xhr.status,
                                    status_text: xhr.statusText,
                                    response: xhr.responseJSON,
                                    payload: requestPayload,
                                    raw_qty_mode: rawQtyMode
                                });
                            const responseJson = xhr.responseJSON || {};
                            if (!allowInsufficient && xhr.status === 400 && responseJson.insufficient_materials) {
                                const shortageText = Array.isArray(responseJson.insufficient_materials) ?
                                    responseJson.insufficient_materials.join('\n') :
                                    Object.values(responseJson.insufficient_materials).flat().join('\n');
                                confirmInsufficientMaterials([shortageText]).then(function(proceed) {
                                    if (proceed) {
                                        addDistributionItemRequest(payload, rawQtyMode, true)
                                            .then(resolve)
                                            .catch(reject);
                                        return;
                                    }
                                    reject({
                                        bypass_cancelled: true
                                    });
                                });
                                return;
                            }
                            reject(xhr);
                        }
                    });
                });
            }

            // OLD → Distribution/DeleteDistribution/:id
            // NEW → Distribution/DeleteItem/:id
            function deleteDistributionItemRequest(itemId) {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: baseUrl + 'Distribution/DeleteItem/' + itemId,
                        method: 'POST',
                        dataType: 'json',
                        success: function(response) {
                            resolve(response || {});
                        },
                        error: function(xhr) {
                            console.error('  ERROR - Failed to delete item:', {
                                status: xhr.status,
                                error: xhr.responseJSON,
                                itemId: itemId
                            });
                            reject(xhr);
                        }
                    });
                });
            }

            function deleteDistributionGroupRequest(groupId) {
                return new Promise(function(resolve, reject) {
                    const normalizedGroupId = normalizeDistributionGroupIdForApi(groupId);

                    $.ajax({
                        url: baseUrl + 'Distribution/DeleteGroup/' + normalizedGroupId,
                        method: 'POST',
                        dataType: 'json',
                        success: function(response) {
                            resolve(response || {});
                        },
                        error: function(xhr) {
                            console.error('  ERROR - Failed to delete group:', {
                                status: xhr.status,
                                error: xhr.responseJSON,
                                groupId: groupId,
                                normalizedGroupId: normalizedGroupId,
                            });
                            reject(xhr);
                        }
                    });
                });
            }

            function updateDistributionGroupRequest(groupId, payload) {
                return new Promise(function(resolve, reject) {
                    const normalizedGroupId = normalizeDistributionGroupIdForApi(groupId);

                    $.ajax({
                        url: baseUrl + 'Distribution/UpdateGroup/' + normalizedGroupId,
                        method: 'POST',
                        contentType: 'application/json',
                        dataType: 'json',
                        data: JSON.stringify(payload || {}),
                        success: function(response) {
                            resolve(response || {});
                        },
                        error: function(xhr) {
                            console.error('  ERROR - Failed to update group:', {
                                status: xhr.status,
                                error: xhr.responseJSON,
                                groupId: groupId,
                                normalizedGroupId: normalizedGroupId,
                                payload: payload || {},
                            });
                            reject(xhr);
                        }
                    });
                });
            }

            function fetchDistributionItemsByDateRequest(dateStr) {
                return new Promise(function(resolve) {
                    $.ajax({
                        url: baseUrl + 'Distribution/GetDistributionByDate',
                        method: 'GET',
                        data: {
                            date: dateStr
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response && response.success) {
                                // Flatten grouped data before decorating
                                const flattenedData = flattenGroupedDataIfNeeded(response
                                    .data || [], dateStr);
                                resolve(decorateDistributionItems(flattenedData, dateStr));
                                return;
                            }
                            resolve([]);
                        },
                        error: function() {
                            resolve([]);
                        }
                    });
                });
            }

            function getDistributionItemId(item) {
                const rawId = item && (
                    item.id ??
                    item.item_id ??
                    item.distribution_item_id ??
                    item.distribution_id
                );
                const parsed = parseInt(rawId, 10);
                return Number.isFinite(parsed) && parsed > 0 ? parsed : null;
            }

            function getMergedDistributionItemIds(item) {
                if (item && Array.isArray(item._merged_item_ids) && item._merged_item_ids.length > 0) {
                    return item._merged_item_ids.filter(id => Number.isFinite(id) && id > 0);
                }
                const singleId = getDistributionItemId(item);
                return singleId ? [singleId] : [];
            }

            function getDistributionItemIdentityKey(item) {
                const distributionItemId = getDistributionItemId(item);
                if (distributionItemId) {
                    return `id-${distributionItemId}`;
                }

                return `product-${String(item && item.product_id || '').trim()}`;
            }

            function deleteDistributionItem(itemId, productId = null, dateValue = null) {
                $.ajax({
                    url: baseUrl + 'Distribution/DeleteItem/' + itemId,
                    method: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (productId != null) {
                            removeLocalDistributionGroupMeta(dateValue || $('#selectedDate').val(),
                                productId);
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


            // OLD → Distribution/UpdateDistribution/:id
            // NEW → Distribution/UpdateItem/:id
            function updateDistributionItem(itemId, quantity, allowInsufficient = false) {
                const row = $('[data-id="' + itemId + '"]');
                const productId = row.data('product-id');
                const qtyMode = row.data('qty-mode') || 'batch';
                const date = $('#selectedDate').val();
                const requestId = `update-item-${itemId}-${Date.now()}`;
                const requestPayload = {
                    product_id: productId,
                    product_qnty: quantity,
                    qty_mode: qtyMode,
                    allow_insufficient: allowInsufficient,
                };

                logDistributionFlow('log', 'Update distribution item request started.', {
                    request_id: requestId,
                    endpoint: 'Distribution/UpdateItem/:id',
                    item_id: itemId,
                    selected_date: date,
                    payload: requestPayload,
                });

                $.ajax({
                    url: baseUrl + 'Distribution/UpdateItem/' + itemId,
                    method: 'POST',
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify(requestPayload),
                    success: function(response) {
                        logDistributionFlow('log', 'Update distribution item request succeeded.', {
                            request_id: requestId,
                            endpoint: 'Distribution/UpdateItem/:id',
                            item_id: itemId,
                            response: response || {},
                        });
                        showToast('success', 'Quantity updated successfully!', 3000);
                        loadDistributionByDate();
                        loadMonthDistributions();
                        loadAllDistributions();
                    },
                    error: function(xhr) {
                        logDistributionFlow('error', 'Update distribution item request failed.', {
                            request_id: requestId,
                            endpoint: 'Distribution/UpdateItem/:id',
                            item_id: itemId,
                            status: xhr.status,
                            response: xhr.responseJSON || null,
                        });

                        if (!allowInsufficient && xhr.status === 400 && xhr.responseJSON && xhr.responseJSON
                            .insufficient_materials) {
                            const shortageText = Array.isArray(xhr.responseJSON.insufficient_materials) ?
                                xhr.responseJSON.insufficient_materials.join('\n') :
                                Object.values(xhr.responseJSON.insufficient_materials).flat().join('\n');
                            confirmInsufficientMaterials([shortageText]).then(function(proceed) {
                                if (proceed) {
                                    updateDistributionItem(itemId, quantity, true);
                                    return;
                                }
                                showToast('danger', xhr.responseJSON.error, 4000);
                            });
                            return;
                        } else {
                            showToast('danger', 'Failed to update quantity. Please try again.', 3000);
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
                    'July', 'August', 'September', 'October', 'November', 'December'
                ];
                $('#calendarMonth').text(monthNames[currentCalendarMonth] + ' ' + currentCalendarYear);

                // Empty slots for days before month starts
                for (let i = 0; i < startingDay; i++) {
                    container.append('<div class="h-16 sm:h-20"></div>');
                }

                // Render each day
                for (let day = 1; day <= totalDays; day++) {
                    const dateStr = formatDate(new Date(currentCalendarYear, currentCalendarMonth, day));
                    const isToday = (new Date(currentCalendarYear, currentCalendarMonth, day).getTime() === today
                        .getTime());
                    const isSelected = (dateStr === selectedDate);
                    const dayData = calendarData[dateStr] || [];
                    const hasItems = dayData.length > 0;

                    let todayClass = isToday ? 'bg-primary text-white' : '';
                    let selectedClass = isSelected ? 'ring-2 ring-primary' : '';
                    let bgClass = isToday ? '' : (hasItems ? 'bg-primary/5' : 'bg-gray-50');

                    let groupsPreview = '';
                    if (hasItems) {
                        // Determine if data is already grouped
                        let groupedData = [];
                        if (dayData.length > 0 && dayData[0] && typeof dayData[0] === 'object' && Array.isArray(
                                dayData[0].items)) {
                            // Already grouped (from calendar API) - transform to expected format
                            groupedData = dayData.map(function(apiGroup) {
                                const groupItems = Array.isArray(apiGroup.items) ? apiGroup.items : [];
                                return {
                                    group_key: getDistributionDisplayGroupKey(apiGroup),
                                    group_name: (apiGroup.title || 'Default Group').toString(),
                                    group_note: (apiGroup.distributed_to_note || '').toString(),
                                    forecasted_sales: parseNumericValue(apiGroup.forecasted_sales),
                                    total_cost: parseNumericValue(apiGroup.total_cost),
                                    items: groupItems,
                                    _apiId: apiGroup.id
                                };
                            });
                        } else {
                            // Flat items - need to group
                            groupedData = normalizeGroupedData(dayData, null, dateStr);
                        }

                        const maxVisibleGroups = 2;
                        const visibleGroups = groupedData.slice(0, maxVisibleGroups);
                        const hiddenGroupsCount = Math.max(0, groupedData.length - maxVisibleGroups);

                        groupsPreview = `
                            <div class="mt-0.5 flex flex-col gap-0.5 overflow-hidden">
                                ${visibleGroups.map(function (group) {
                            const groupName = escapeHtml((group.group_name || 'Default Group').toString());
                            const groupKey = escapeHtml((group.group_key || '').toString());
                            return `
                                        <button
                                            type="button"
                                            class="calendar-group-chip w-full text-left truncate px-1 sm:px-1.5 py-0.5 rounded text-[8px] sm:text-[9px] md:text-[10px] font-medium hover:opacity-90 transition-opacity ${isToday ? 'bg-white/20 text-white' : 'bg-primary/10 text-primary'}"
                                            data-date="${dateStr}"
                                            data-group-key="${groupKey}"
                                            title="${groupName}">
                                            ${groupName}
                                        </button>
                                    `;
                        }).join('')}
                                ${hiddenGroupsCount > 0 ? `<div class="w-full text-left px-1 sm:px-1.5 py-0.5 rounded text-[8px] sm:text-[9px] md:text-[10px] font-medium ${isToday ? 'bg-white/20 text-white' : 'bg-primary/10 text-primary'}">+${hiddenGroupsCount}</div>` : ''}
                            </div>
                        `;
                    }

                    const dayHtml = `
                        <div class="calendar-day h-16 sm:h-20 md:h-24 p-0.5 sm:p-1 md:p-2 rounded-md sm:rounded-lg cursor-pointer hover:shadow-md transition-all ${bgClass} ${todayClass} ${selectedClass} border border-gray-100 overflow-hidden"
                             data-date="${dateStr}">
                            <div class="text-[10px] sm:text-xs md:text-sm font-semibold ${isToday ? 'text-white' : 'text-gray-700'}">${day}</div>
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

            function setCalendarDaySelectButtonScope(scope, groupKey = '') {
                const normalizedScope = (scope === 'group') ? 'group' : 'date';
                const normalizedGroupKey = (groupKey || '').toString();

                $('#calendarDayModal').data('selection-scope', normalizedScope);

                if (normalizedScope === 'group' && normalizedGroupKey) {
                    $('#calendarDayModal').data('selected-group-key', normalizedGroupKey);
                } else {
                    $('#calendarDayModal').removeData('selected-group-key');
                }

                $('#btnCalendarDaySelect').html(
                    `<i class="fas fa-arrow-right mr-1"></i>${normalizedScope === 'group' ? 'Go to this group' : 'Go to this date'}`
                );
            }

            function setCalendarDayModalPane(pane = 'list') {
                const isDetail = pane === 'detail';
                $('#calendarDaySlideTrack').css('transform', isDetail ? 'translateX(-50%)' : 'translateX(0)');
            }

            function renderCalendarModalGroupDetail(dateStr, group, options = {}) {
                const groupItems = Array.isArray(group && group.items) ? group.items : [];
                const groupSummary = buildGroupScopedSummary(group, groupItems, dateStr);
                const groupName = escapeHtml((group && group.group_name ? group.group_name : 'Default Group')
                    .toString());
                const groupNote = escapeHtml((group && group.group_note ? group.group_note : '').toString().trim());
                const groupKey = escapeHtml((group && group.group_key ? group.group_key : '').toString());
                const isStoreGroup = (group && group.group_key) === STORE_GROUP_KEY;
                const totalForecast = parseNumericValue(groupSummary.forecasted_sales_total);
                const totalCost = parseNumericValue(groupSummary.total_cost_total);
                const totalOverhead = parseNumericValue(groupSummary.overhead_cost_total);
                const totalBatches = parseNumericValue(groupSummary.total_batches);
                const totalPieces = parseNumericValue(groupSummary.total_pieces);
                const isMaterialLoading = Boolean(options && options.materialLoading);

                // While raw material usage (the source of truth for cost) is still
                // hydrating, don't show the stale cached total — show a placeholder
                // instead of flashing an incorrect number.
                const totalCostDisplay = isMaterialLoading ? '…' : formatPesoAmount(totalCost);

                const materialUsageByItemHtml = groupItems.map(function(item) {
                    const quantity = parseNumericValue(item.product_qnty);
                    const itemMaterials = Array.isArray(item.raw_material_usage) ? item.raw_material_usage : [];
                    const itemMaterialHtml = (isMaterialLoading && itemMaterials.length === 0) ?
                        '<p class="text-[11px] text-gray-400">Loading ingredients used...</p>' :
                        renderMaterialUsageList(itemMaterials);

                    return `
                        <div class="p-2 bg-gray-50 rounded-md border border-gray-100">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs font-semibold text-gray-800 truncate">${escapeHtml(item.product_name || '')}</p>
                                <p class="text-[11px] text-gray-500 flex-shrink-0">${formatQuantityValue(quantity)} ${getQtyModeShortLabel(item.qty_mode || 'batch')}</p>
                            </div>
                            <div class="mt-1.5 pl-1 border-l-2 border-primary/20 space-y-1">${itemMaterialHtml}</div>
                        </div>
                    `;
                }).join('');

                const itemsHtml = groupItems.map(function(item) {
                    const quantity = parseNumericValue(item.product_qnty);
                    const itemForecast = hasPersistedNumericValue(item, 'forecasted_sales') ?
                        parseNumericValue(item.forecasted_sales) :
                        (quantity * getForecastUnitPrice(
                            getProductAnalyticsData(item.product_id),
                            item.qty_mode || 'batch'
                        ));
                    const itemTotal = parseNumericValue(item.total_cost);
                    const unitPerPiece = parseNumericValue(item.unit_cost_per_piece);
                    const additionalPerPiece = parseNumericValue(item.additional_cost_per_piece);
                    const totalPerPiece = parseNumericValue(item.total_price_per_piece);

                    // While raw material usage is still hydrating for this item, show a
                    // placeholder instead of the stale cached total_cost.
                    const itemHasOwnUsage = Array.isArray(item.raw_material_usage) && item.raw_material_usage.length > 0;
                    const itemTotalDisplay = (isMaterialLoading && !itemHasOwnUsage) ?
                        '…' :
                        formatPesoAmount(itemTotal);

                    // Store items show a full breakdown so it's clear how much of
                    // what was added has already gone out to a distribution group.
                    const quantityLineHtml = isStoreGroup ?
                        `${formatQuantityValue(parseNumericValue(item.added_qty))} added • ${formatQuantityValue(parseNumericValue(item.distributed_qty))} distributed • ${formatQuantityValue(quantity)} remaining` :
                        `${formatQuantityValue(quantity)} ${getQtyModeShortLabel(item.qty_mode || 'batch')}`;

                    return `
        <div class="p-2 bg-gray-50 rounded-md border border-gray-100">
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-800 truncate">${escapeHtml(item.product_name || '')}</p>
                    <p class="text-[11px] text-gray-500">${quantityLineHtml}</p>
                </div>
                <div class="text-right">
                    <p class="text-[11px] text-primary font-semibold">Total Selling Price: ${formatPesoAmount(itemForecast)}</p>
                    <p class="text-[11px] text-emerald-600 font-semibold">Total Cost: ${itemTotalDisplay}</p>
                </div>
            </div>
        </div>
    `;
                }).join('');

                return `
                    <div class="space-y-2.5">
                        <div class="p-2.5 bg-white border border-gray-200 rounded-lg">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-primary truncate">
                                        <i class="fas ${isStoreGroup ? 'fa-store' : 'fa-layer-group'} mr-1"></i>${groupName}
                                    </p>
                                    <p class="text-[11px] text-gray-500 mt-0.5">${groupItems.length} item(s) • ${formatQuantityValue(totalBatches)} batches • ${formatQuantityValue(totalPieces)} pcs</p>
                                </div>
                                ${isStoreGroup ? '' : `
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <button type="button" class="btn-modal-edit-group px-2 py-1 text-[10px] font-medium rounded-md bg-primary/10 text-primary hover:bg-primary/20" data-date="${dateStr}" data-group-key="${groupKey}">Edit</button>
                                    <button type="button" class="btn-modal-delete-group px-2 py-1 text-[10px] font-medium rounded-md bg-red-100 text-red-600 hover:bg-red-200" data-date="${dateStr}" data-group-key="${groupKey}">Delete</button>
                                </div>
                                `}
                            </div>
                            ${groupNote ? `<p class="text-[11px] text-amber-700 mt-1"><i class="fas fa-sticky-note mr-1 text-amber-500"></i>${groupNote}</p>` : ''}
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div class="p-2.5 bg-amber-50 border border-amber-100 rounded-lg">
                                <p class="text-[11px] text-gray-600">Group Overhead Cost</p>
                                <p class="text-sm font-semibold text-amber-600">${formatPesoAmount(totalOverhead)}</p>
                            </div>
                            <div class="p-2.5 bg-emerald-50 border border-emerald-100 rounded-lg">
                                <p class="text-[11px] text-gray-600">Group Total Cost</p>
                                <p class="text-sm font-semibold text-emerald-600">${totalCostDisplay}</p>
                            </div>
                        </div>

                        <div class="p-2.5 bg-white border border-gray-200 rounded-lg">
                            <p class="text-xs font-semibold text-gray-700 mb-1"><i class="fas fa-flask mr-1 text-primary"></i>Raw Material Usage (Per Item)</p>
                            <div class="space-y-1.5">${materialUsageByItemHtml || '<p class="text-[11px] text-gray-400">No items in this group.</p>'}</div>
                        </div>

                        <div class="p-2.5 bg-white border border-gray-200 rounded-lg">
                            <p class="text-xs font-semibold text-gray-700 mb-1.5"><i class="fas fa-list mr-1 text-primary"></i>Group Items</p>
                            <div class="space-y-1.5">${itemsHtml || '<p class="text-[11px] text-gray-400">No items in this group.</p>'}</div>
                        </div>
                    </div>
                `;
            }

            async function hydrateGroupItemsRawMaterialUsageForModal(items) {
                const normalizedItems = Array.isArray(items) ? items : [];

                const hydratedItems = await Promise.all(normalizedItems.map(async function(item) {
                    let usage = Array.isArray(item && item.raw_material_usage) ? item.raw_material_usage : [];

                    if (usage.length === 0) {
                        try {
                            usage = await computeRawMaterialUsageForItem(item);
                        } catch (error) {
                            usage = [];
                        }
                    }

                    // Raw material usage is now the source of truth for an item's cost —
                    // whatever its ingredients actually cost is what "Total Cost" shows,
                    // instead of a possibly-stale cached product cost field.
                    const recomputedTotalCost = usage.length > 0 ?
                        computeTotalCostFromRawMaterialUsage(usage) :
                        parseNumericValue(item.total_cost);

                    return Object.assign({}, item, {
                        raw_material_usage: usage,
                        total_cost: recomputedTotalCost,
                    });
                }));

                const materialMap = {};
                hydratedItems.forEach(function(item) {
                    (Array.isArray(item.raw_material_usage) ? item.raw_material_usage : []).forEach(
                        function(material) {
                            mergeMaterialUsageEntry(materialMap, material);
                        });
                });

                return {
                    items: hydratedItems,
                    materialTotal: materialUsageMapToArray(materialMap),
                };
            }

            async function openCalendarModalGroupDetail(dateStr, groupKey, sourceItems = null) {
                const normalizedDate = (dateStr || '').toString();
                const normalizedGroupKey = (groupKey || '').toString();
                const candidateItems = Array.isArray(sourceItems) ?
                    flattenGroupedDataIfNeeded(sourceItems, normalizedDate) :
                    flattenGroupedDataIfNeeded(($('#calendarDayModal').data('day-items') || calendarData[
                        normalizedDate] || []), normalizedDate);

                const groupedData = normalizeGroupedData(candidateItems, null, normalizedDate);
                const matchedGroup = groupedData.find(function(group) {
                    return String(group.group_key || '') === normalizedGroupKey;
                });

                if (!matchedGroup) return;

                const modalFormattedDate = ($('#calendarDayModal').data('formatted-date') || '')
                    .toString();
                const modalGroupCountRaw = parseInt($('#calendarDayModal').data('day-group-count'), 10);
                const modalGroupCount = Number.isFinite(modalGroupCountRaw) ? modalGroupCountRaw : groupedData
                    .length;

                $('#calendarDayModalTitle').text('Distribution Group');
                $('#calendarDayModalDate').text(modalGroupCount > 0 ?
                    `${modalFormattedDate} • ${modalGroupCount} ${modalGroupCount === 1 ? 'group' : 'groups'}` :
                    modalFormattedDate
                );
                $('#calendarDaySummaryCards').removeClass('hidden');

                const groupItems = Array.isArray(matchedGroup.items) ? matchedGroup.items : [];
                const groupSummary = buildGroupScopedSummary(matchedGroup, groupItems, normalizedDate);
                const needsHydration = groupItems.some(function(item) {
                    return !Array.isArray(item && item.raw_material_usage) || item.raw_material_usage
                        .length === 0;
                });

                const hydrationToken = ++modalGroupDetailHydrationToken;

                $('#calendarDayGroupDetailContent').html(renderCalendarModalGroupDetail(normalizedDate,
                    matchedGroup, {
                        materialLoading: needsHydration
                    }));
                $('#calendarDayModal').data('day-summary', groupSummary);
                updateModalForecastedSales(groupItems, groupSummary);
                setCalendarDaySelectButtonScope('group', normalizedGroupKey);
                setCalendarDayModalPane('detail');

                if (!needsHydration) {
                    return;
                }

                const hydrated = await hydrateGroupItemsRawMaterialUsageForModal(groupItems);

                if (hydrationToken !== modalGroupDetailHydrationToken) {
                    return;
                }

                const activeDate = ($('#calendarDayModal').data('selected-date') || '').toString();
                const activeScope = ($('#calendarDayModal').data('selection-scope') || '').toString();
                const activeGroupKey = ($('#calendarDayModal').data('selected-group-key') || '').toString();

                if ($('#calendarDayModal').hasClass('hidden') ||
                    activeDate !== normalizedDate ||
                    activeScope !== 'group' ||
                    activeGroupKey !== normalizedGroupKey) {
                    return;
                }

                const hydratedGroup = Object.assign({}, matchedGroup, {
                    items: hydrated.items,
                    raw_material_usage_total: hydrated.materialTotal,
                    total_items: hydrated.items.length,
                });
                const hydratedSummary = buildGroupScopedSummary(hydratedGroup, hydrated.items, normalizedDate);

                $('#calendarDayGroupDetailContent').html(renderCalendarModalGroupDetail(normalizedDate,
                    hydratedGroup));
                $('#calendarDayModal').data('day-summary', hydratedSummary);
                updateModalForecastedSales(hydrated.items, hydratedSummary);
            }

            function openSpecificGroupView(dateStr, groupKey, sourceItems = null) {
                const normalizedDate = (dateStr || '').toString();
                const normalizedGroupKey = (groupKey || '').toString();

                setSelectedGroupFilter(normalizedDate, normalizedGroupKey);

                const candidateItems = Array.isArray(sourceItems) ?
                    flattenGroupedDataIfNeeded(sourceItems, normalizedDate) :
                    flattenGroupedDataIfNeeded((calendarData[normalizedDate] || currentDayDistributionItems || []),
                        normalizedDate);

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
                refreshDayTotalsDisplay(normalizedDate);
                renderOwnerAnalytics([matchedGroup], groupSummary);

                showCalendarDayModal(normalizedDate, candidateItems, {
                    summary: groupSummary,
                    groupPicker: true,
                    scope: 'group',
                    groupKey: normalizedGroupKey
                });
            }

            // Calendar date click - show all groups so user can choose one
            $(document).on('click', '.calendar-day', async function(e) {
                if ($(e.target).closest('.calendar-group-chip').length) return;

                const dateStr = ($(this).data('date') || '').toString();
                const storeGroup = await ensureStoreGroupForDate(dateStr);
                const dayData = (calendarData[dateStr] || []).concat(storeGroup.items);
                showCalendarDayModal(dateStr, dayData, {
                    groupPicker: true
                });
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
                const dateStr = ($(this).data('date') || $('#calendarDayModal').data('selected-date') || '')
                    .toString();
                const groupKey = ($(this).data('group-key') || '').toString();
                const dayItems = $('#calendarDayModal').data('day-items') || calendarData[dateStr] || [];
                openCalendarModalGroupDetail(dateStr, groupKey, dayItems);
            });

            // Group list (non-picker mode) - open selected group detail pane
            $(document).on('click', '.modal-group-detail-btn', function() {
                const dateStr = ($(this).data('date') || $('#calendarDayModal').data('selected-date') || '')
                    .toString();
                const groupKey = ($(this).data('group-key') || '').toString();
                const dayItems = $('#calendarDayModal').data('day-items') || calendarData[dateStr] || [];
                openCalendarModalGroupDetail(dateStr, groupKey, dayItems);
            });

            $(document).on('click', '.btn-modal-edit-group', function() {
                const dateStr = ($(this).data('date') || $('#calendarDayModal').data('selected-date') || '')
                    .toString();
                const groupKey = ($(this).data('group-key') || '').toString();
                const dayItems = $('#calendarDayModal').data('day-items') || calendarData[dateStr] || [];
                openDistributionGroupEditModal(dateStr, groupKey, dayItems);
            });

            $(document).on('click', '.btn-modal-delete-group', function() {
                const dateStr = ($(this).data('date') || $('#calendarDayModal').data('selected-date') || '')
                    .toString();
                const groupKey = ($(this).data('group-key') || '').toString();
                const dayItems = $('#calendarDayModal').data('day-items') || calendarData[dateStr] || [];

                Confirm.delete('Delete this distribution group and all its items?', async function() {
                    await deleteDistributionGroupByKey(dateStr, groupKey, dayItems);
                });
            });

            $(document).on('click', '#btnCalendarDayBackToGroups', function() {
                const dayItems = $('#calendarDayModal').data('day-items') || [];
                const baseSummary = $('#calendarDayModal').data('base-day-summary') || {};
                const baseScope = ($('#calendarDayModal').data('base-selection-scope') || 'date')
                    .toString();
                const baseGroupKey = ($('#calendarDayModal').data('base-selected-group-key') || '')
                    .toString();
                const isGroupPickerMode = Boolean($('#calendarDayModal').data('group-picker-mode'));
                const formattedDate = ($('#calendarDayModal').data('formatted-date') || '').toString();
                const groupCountRaw = parseInt($('#calendarDayModal').data('day-group-count'), 10);
                const groupCount = Number.isFinite(groupCountRaw) ? groupCountRaw : 0;

                $('#calendarDayModal').data('day-summary', baseSummary);
                updateModalForecastedSales(dayItems, baseSummary);
                setCalendarDaySelectButtonScope(baseScope, baseGroupKey);
                setCalendarDayModalPane('list');

                $('#calendarDayModalTitle').text(
                    isGroupPickerMode ?
                    'Select Distribution Group' :
                    (groupCount > 1 ? 'Distribution Groups' : 'Distribution Group')
                );
                $('#calendarDayModalDate').text(groupCount > 0 ?
                    `${formattedDate} • ${groupCount} ${groupCount === 1 ? 'group' : 'groups'}` :
                    formattedDate
                );

                if (isGroupPickerMode) {
                    $('#calendarDaySummaryCards').addClass('hidden');
                } else {
                    $('#calendarDaySummaryCards').removeClass('hidden');
                }
            });

            // Helper function to flatten grouped data from API into flat items array
            function flattenGroupedDataIfNeeded(data, dateStr) {
                if (!Array.isArray(data) || data.length === 0) {
                    return [];
                }

                // Check if data is already structured as groups with items
                // (each element has an 'items' property containing actual distribution items)
                if (data[0] && typeof data[0] === 'object' && Array.isArray(data[0].items)) {

                    const flatItems = [];
                    data.forEach(function(group) {
                        const groupDate = (group.distribution_date || dateStr || '').toString();
                        const groupItems = Array.isArray(group.items) ? group.items : [];

                        // Use group ID as the distribution_group_key to preserve grouping during regroup phase
                        const groupKey = 'group-' + String(group.id);
                        const displayGroupKey = getDistributionDisplayGroupKey(group);

                        groupItems.forEach(function(item) {
                            flatItems.push(Object.assign({}, item, {
                                distribution_date: groupDate,
                                distribution_id: group.id,
                                distribution_group_key: groupKey, // Preserve original group
                                distribution_display_group_key: displayGroupKey,
                                distribution_group_name: group.title,
                                distribution_group_note: group.distributed_to_note,
                                group_title: group.title,
                                group_forecasted_sales: group.forecasted_sales,
                                group_total_cost: group.total_cost,
                                distributed_to_note: group.distributed_to_note
                            }));
                        });
                    });
                    return flatItems;
                }
                return data;
            }

            function showCalendarDayModal(dateStr, items, modalOptions = {}) {

                const date = new Date(dateStr + 'T00:00:00');
                const dateDisplayOptions = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                const formatted = date.toLocaleDateString('en-US', dateDisplayOptions);

                // Determine if data is already grouped or flat items
                let groupedData = [];
                let flatItems = [];

                if (Array.isArray(items) && items.length > 0 && items[0] && typeof items[0] === 'object' && Array
                    .isArray(items[0].items)) {

                    // Transform API groups to match expected format
                    groupedData = items.map(function(apiGroup) {
                        const groupItems = Array.isArray(apiGroup.items) ? apiGroup.items : [];
                        const totalBatches = groupItems.reduce(function(sum, item) {
                            return sum + (((item.qty_mode || 'batch') !== 'pieces') ?
                                parseNumericValue(item.product_qnty) : 0);
                        }, 0);
                        const totalPieces = calculateTotalDistributionPieces(groupItems);

                        return {
                            group_key: getDistributionDisplayGroupKey(apiGroup),
                            group_name: (apiGroup.title || 'Default Group').toString(),
                            group_note: (apiGroup.distributed_to_note || '').toString(),
                            total_items: groupItems.length,
                            total_batches: totalBatches,
                            total_pieces: totalPieces,
                            forecasted_sales: parseNumericValue(apiGroup.forecasted_sales),
                            total_cost: parseNumericValue(apiGroup.total_cost),
                            items: groupItems,
                            // Keep original API data for reference
                            _apiId: apiGroup.id,
                            _apiDate: apiGroup.distribution_date
                        };
                    });

                    // Extract all items for calculations (preserve original group key)
                    flatItems = [];
                    groupedData.forEach(function(group) {
                        if (Array.isArray(group.items)) {
                            group.items.forEach(function(item) {
                                flatItems.push(Object.assign({}, item, {
                                    distribution_date: group._apiDate || dateStr,
                                    distribution_id: group._apiId,
                                    distribution_group_key: group.group_key,
                                    distribution_group_name: group.group_name,
                                    distribution_group_note: group.group_note
                                }));
                            });
                        }
                    });
                } else {
                    flatItems = items;
                    groupedData = normalizeGroupedData(flatItems, null, dateStr);
                }

                const groupCount = groupedData.length;

                const selectedDate = $('#selectedDate').val();
                const providedSummary = (modalOptions && typeof modalOptions === 'object' && modalOptions.summary) ?
                    modalOptions.summary :
                    null;
                const isGroupPickerMode = Boolean(modalOptions && modalOptions.groupPicker);
                const requestedScope = (modalOptions && typeof modalOptions === 'object' && modalOptions.scope ===
                        'group') ?
                    'group' :
                    'date';
                const providedGroupKey = (modalOptions && typeof modalOptions === 'object' && modalOptions
                        .groupKey) ?
                    String(modalOptions.groupKey) :
                    '';
                const shouldOpenSpecificGroup = requestedScope === 'group' && providedGroupKey !== '';
                const selectionScope = shouldOpenSpecificGroup ? 'group' : 'date';
                const baseSelectionScope = isGroupPickerMode ? 'date' : selectionScope;
                const shouldUseCurrentDaySummary = (selectedDate === dateStr) &&
                    Array.isArray(currentDayDistributionItems) &&
                    currentDayDistributionItems.length === flatItems.length;
                const modalSummary = providedSummary || (shouldUseCurrentDaySummary && currentDaySummary ?
                    currentDaySummary : {});

                if (isGroupPickerMode) {
                    $('#calendarDaySummaryCards').addClass('hidden');
                } else {
                    $('#calendarDaySummaryCards').removeClass('hidden');
                }

                $('#calendarDayModalTitle').text(
                    isGroupPickerMode ?
                    'Select Distribution Group' :
                    (groupCount > 1 ? 'Distribution Groups' : 'Distribution Group')
                );
                $('#calendarDayModalDate').text(groupCount > 0 ?
                    `${formatted} • ${groupCount} ${groupCount === 1 ? 'group' : 'groups'}` :
                    formatted
                );
                $('#calendarDayModal').data('selected-date', dateStr);
                $('#calendarDayModal').data('day-summary', modalSummary);
                $('#calendarDayModal').data('base-day-summary', modalSummary);
                $('#calendarDayModal').data('base-selection-scope', baseSelectionScope);
                $('#calendarDayModal').data('base-selected-group-key', providedGroupKey);
                $('#calendarDayModal').data('group-picker-mode', isGroupPickerMode);
                $('#calendarDayModal').data('formatted-date', formatted);
                $('#calendarDayModal').data('day-group-count', groupCount);
                $('#calendarDayGroupDetailContent').empty();
                setCalendarDaySelectButtonScope(selectionScope, providedGroupKey);
                setCalendarDayModalPane('list');

                const hasSummaryCounts = modalSummary && typeof modalSummary === 'object';
                const batchTotal = hasSummaryCounts && modalSummary.total_batches != null ?
                    parseNumericValue(modalSummary.total_batches) :
                    flatItems.reduce((sum, item) => sum + ((item.qty_mode || 'batch') !== 'pieces' ?
                        parseNumericValue(item.product_qnty) : 0), 0);
                const piecesTotal = hasSummaryCounts && modalSummary.total_pieces != null ?
                    parseNumericValue(modalSummary.total_pieces) :
                    calculateTotalDistributionPieces(flatItems);
                const totalItems = hasSummaryCounts && modalSummary.total_items != null ?
                    parseNumericValue(modalSummary.total_items) :
                    flatItems.length;

                $('#modalItemCount').text(formatQuantityValue(totalItems));
                $('#modalBatchesCount').text(formatQuantityValue(batchTotal));
                $('#modalPiecesCount').text(formatQuantityValue(piecesTotal));
                $('#calendarDayModal').data('day-items', flatItems);
                updateModalForecastedSales(flatItems, modalSummary);

                const listContainer = $('#calendarDayItemsList');
                listContainer.empty();
                updateCalendarDayNotePanel(flatItems);

                if (flatItems.length === 0) {
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
                            const isStoreGroup = groupKey === STORE_GROUP_KEY; // add this
                            const groupSummary = buildGroupScopedSummary(group, groupItems, dateStr);

                            return `
                                <button type="button" class="modal-group-picker-btn w-full text-left p-2.5 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors" data-date="${dateStr}" data-group-key="${groupKey}">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-primary truncate"><i class="fas fa-layer-group mr-1"></i>${groupName}</p>
                                            <p class="text-[11px] text-gray-500 mt-0.5">${groupItems.length} item(s)</p>
                                        </div>
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                        <div class="text-right">
                                            <p class="text-[11px] font-semibold text-primary">${formatPesoAmount(parseNumericValue(groupSummary.forecasted_sales_total))}</p>
                                            <p class="text-[10px] text-gray-500 mt-0.5">View details</p>
                                        </div>
                                        <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                            <i class="fas fa-chevron-right text-[10px]"></i>
                                        </span>
                                    </div>
                                </div>
                                ${isOwnerView ? `
                                <div class="flex items-center gap-3 mt-1.5">
                                    <span class="text-[10px] text-gray-500">Cost: <span class="font-semibold text-emerald-600">${formatPesoAmount(parseNumericValue(groupSummary.total_cost_total))}</span></span>
                                    <span class="text-[10px] text-gray-500">Overhead: <span class="font-semibold text-amber-600">${formatPesoAmount(parseNumericValue(groupSummary.overhead_cost_total))}</span></span>
                                </div>
                                ` : ''}
                                ${groupNote ? `<p class="text-[11px] text-amber-700 mt-1.5 truncate"><i class="fas fa-sticky-note mr-1 text-amber-500"></i>${groupNote}</p>` : ''}
                            </button>
    `
                        }).join('');

                        listContainer.html(pickerHtml ||
                            '<p class="text-xs text-gray-400">No groups available.</p>');
                    } else {

                        groupedData.forEach(function(group) {
                            const groupItems = Array.isArray(group.items) ? group.items : [];
                            const groupName = escapeHtml((group.group_name || 'Default Group').toString());
                            const groupNote = escapeHtml((group.group_note || '').toString().trim());
                            const groupKey = escapeHtml((group.group_key || '').toString());

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
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold text-primary truncate">
                                                    <i class="fas fa-layer-group mr-1"></i>${groupName}
                                                </p>
                                                <p class="text-[11px] text-gray-600">${groupItems.length} item(s)</p>
                                            </div>
                                            <button type="button" class="modal-group-detail-btn w-6 h-6 rounded-full bg-primary/10 text-primary hover:bg-primary/20 flex items-center justify-center flex-shrink-0" data-date="${dateStr}" data-group-key="${groupKey}" title="View group details">
                                                <i class="fas fa-chevron-right text-[10px]"></i>
                                            </button>
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

                    if (shouldOpenSpecificGroup) {
                        openCalendarModalGroupDetail(dateStr, providedGroupKey, flatItems);
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
                const selectionScope = ($('#calendarDayModal').data('selection-scope') || 'date')
                    .toString();
                const selectedGroupKey = ($('#calendarDayModal').data('selected-group-key') || '')
                    .toString();

                if (selectionScope === 'group' && selectedGroupKey) {
                    setSelectedGroupFilter(dateStr, selectedGroupKey);
                } else {
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

            $(document).on('click', '.distribution-group-entry', async function() {
                const dateStr = ($(this).data('date') || $('#selectedDate').val() || '').toString();
                const groupKey = ($(this).data('group-key') || '').toString();

                if (groupKey === STORE_GROUP_KEY) {
                    await openStoreGroupView(dateStr);
                    return;
                }

                openSpecificGroupView(dateStr, groupKey, currentDayDistributionItems);
            });

            async function openStoreGroupView(dateStr) {
                const normalizedDate = (dateStr || '').toString();
                setSelectedGroupFilter(normalizedDate, STORE_GROUP_KEY);

                const storeGroup = await ensureStoreGroupForDate(normalizedDate);
                const groupSummary = buildGroupScopedSummary(storeGroup, storeGroup.items, normalizedDate);

                showCalendarDayModal(normalizedDate, storeGroup.items, {
                    summary: groupSummary,
                    groupPicker: false
                });
                await openCalendarModalGroupDetail(normalizedDate, STORE_GROUP_KEY, storeGroup.items);

                updateSummaryCounts(storeGroup.items, groupSummary, normalizedDate);
                refreshDayTotalsDisplay(normalizedDate);
                renderOwnerAnalytics([storeGroup], groupSummary);
            }

            // ===== RENDERING FUNCTIONS =====

            function updateDistributionListScrollLimit() {
                const container = $('#distributionListContainer');
                if (!container.length) return;

                container.css('max-height', '');

                const groupEntries = container.children('.distribution-group-entry');
                if (groupEntries.length < 3) return;

                let maxHeight = 0;
                groupEntries.slice(0, 3).each(function() {
                    maxHeight += ($(this).outerHeight(true) || 0);
                });

                if (maxHeight > 0) {
                    container.css('max-height', Math.ceil(maxHeight) + 'px');
                }
            }

            function renderDistributionList(items, groupedData = null, fallbackDate = '') {
                const container = $('#distributionListContainer');
                container.empty();
                container.css('max-height', '');

                const selectedDate = (fallbackDate || $('#selectedDate').val() || '').toString();
                const normalizedGroups = normalizeGroupedData(items, groupedData, selectedDate);
                const storeGroup = storeGroupByDate[selectedDate] || buildStoreGroupObject([], selectedDate);
                const hasStoreItems = storeGroup.items.length > 0;

                if (items.length === 0 && !hasStoreItems) {
                    container.addClass('hidden');
                    $('#emptyState').removeClass('hidden');
                    return;
                }

                container.removeClass('hidden');
                $('#emptyState').addClass('hidden');

                container.append(renderDistributionGroupEntryHtml(storeGroup, selectedDate));
                normalizedGroups.forEach(function(group) {
                    container.append(renderDistributionGroupEntryHtml(group, selectedDate));
                });

                updateDistributionListScrollLimit();
            }

            function renderDistributionGroupEntryHtml(group, selectedDate) {
                const groupItems = Array.isArray(group.items) ? group.items : [];
                const isStoreGroup = (group.group_key || '') === STORE_GROUP_KEY;
                const groupName = escapeHtml((group.group_name || 'Default Group').toString());
                const groupNote = escapeHtml((group.group_note || '').toString().trim());
                const groupKey = escapeHtml((group.group_key || '').toString());
                const iconClass = isStoreGroup ? 'fa-store' : 'fa-layer-group';

                const totalBatches = groupItems.reduce((sum, item) =>
                    sum + (((item.qty_mode || 'batch') !== 'pieces') ? parseNumericValue(item.product_qnty) :
                        0), 0);
                const totalPieces = calculateTotalDistributionPieces(groupItems);
                const forecastTotal = resolveGroupForecastedSales(group, groupItems);
                const totalCost = resolveGroupTotalCost(group, groupItems);
                const totalOverhead = resolveGroupOverheadCost(group, groupItems);
                const totalDistributedPieces = parseNumericValue(group.total_distributed_pieces);

                return `
        <button type="button" class="distribution-group-entry w-full text-left p-3 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-100 transition-colors" data-group-key="${groupKey}" data-date="${selectedDate}">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-primary truncate"><i class="fas ${iconClass} mr-1"></i>${groupName}</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">${groupItems.length} item(s)</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs font-semibold text-primary">${formatPesoAmount(forecastTotal)}</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">${formatQuantityValue(totalBatches)} batches • ${formatQuantityValue(totalPieces)} pcs${isStoreGroup ? ' remaining' : ''}</p>
                    ${isStoreGroup ? `<p class="text-[11px] text-gray-400 mt-0.5">${formatQuantityValue(totalDistributedPieces)} pcs already distributed</p>` : ''}
                </div>
            </div>
            ${isOwnerView ? `
            <div class="flex items-center justify-between gap-3 mt-2 pt-2 border-t border-gray-200">
                <span class="text-[10px] text-gray-500">Total Cost: <span class="font-semibold text-emerald-600">${formatPesoAmount(totalCost)}</span></span>
                <span class="text-[10px] text-gray-500">Overhead: <span class="font-semibold text-amber-600">${formatPesoAmount(totalOverhead)}</span></span>
            </div>
            ` : ''}
            ${groupNote ? `<p class="text-[11px] text-amber-700 mt-1.5 truncate"><i class="fas fa-sticky-note mr-1 text-amber-500"></i>${groupNote}</p>` : ''}
        </button>
    `;
            }

            //     function renderStoreItemsList(items) {
            //         const container = $('#storeListContainer');
            //         container.empty();

            //         const normalizedItems = Array.isArray(items) ? items : [];

            //         if (normalizedItems.length === 0) {
            //             container.addClass('hidden');
            //             $('#emptyStateStore').removeClass('hidden');
            //             return;
            //         }

            //         container.removeClass('hidden');
            //         $('#emptyStateStore').addClass('hidden');

            //         normalizedItems.forEach(function(item) {
            //             const productName = escapeHtml(item.product_name || 'Unknown Product');
            //             const category = escapeHtml((item.category || '').toString());
            //             const addedQty = parseNumericValue(item.added_qty);
            //             const price = parseNumericValue(
            //                 (item.selling_price_per_piece > 0 ? item.selling_price_per_piece : item.selling_price)
            //             );
            //             const estimatedValue = addedQty * price;

            //             const row = `
            //     <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
            //         <div class="flex items-start justify-between gap-3">
            //             <div class="min-w-0">
            //                 <p class="text-sm font-semibold text-gray-800 truncate">${productName}</p>
            //                 <p class="text-[11px] text-gray-500 mt-0.5">${category}</p>
            //             </div>
            //             <div class="text-right flex-shrink-0">
            //                 <p class="text-xs font-semibold text-primary">+${formatQuantityValue(addedQty)} added</p>
            //                 <p class="text-[11px] text-gray-500 mt-0.5">${formatPesoAmount(estimatedValue)}</p>
            //             </div>
            //         </div>
            //     </div>
            // `;

            //             container.append(row);
            //         });
            //     }

            //     function loadStoreAddedItems() {
            //         const date = ($('#selectedDate').val() || '').toString();
            //         $.ajax({
            //             url: baseUrl + 'Inventory/GetAddedStockItems',
            //             method: 'GET',
            //             data: {
            //                 date: date
            //             },
            //             dataType: 'json',
            //             success: function(response) {
            //                 const items = (response && response.success && Array.isArray(response.data)) ? response.data : [];
            //                 renderStoreItemsList(items);
            //             },
            //             error: function() {
            //                 renderStoreItemsList([]);
            //             }
            //         });
            //     }


            function computeStoreItemMetrics(item) {
                const addedQty = parseNumericValue(item.added_qty);
                const productData = getProductAnalyticsData(item.product_id);

                // Store items are tracked in pieces, so build a synthetic
                // distribution-shaped item to reuse the same cost helpers
                // used for regular distribution items.
                const syntheticItem = {
                    product_id: item.product_id,
                    product_qnty: addedQty,
                    qty_mode: 'pieces'
                };

                const fallbackUnitPrice = firstPositiveValue([
                    item.selling_price_per_piece,
                    item.selling_price
                ]);
                const productUnitPrice = productData ? getForecastUnitPrice(productData, 'pieces') : 0;
                const unitPrice = productUnitPrice > 0 ? productUnitPrice : fallbackUnitPrice;

                const forecast = addedQty * unitPrice;
                const totalCost = productData ? calculateItemTotalCost(syntheticItem, productData) : 0;
                const overheadCost = productData ? calculateItemOverheadCost(syntheticItem, productData) : 0;

                return {
                    addedQty: addedQty,
                    forecast: forecast,
                    totalCost: totalCost,
                    overheadCost: overheadCost
                };
            }

            function buildStoreDistributionEntryHtml(storeItems) {
                const normalizedItems = Array.isArray(storeItems) ? storeItems : [];

                let totalAdded = 0;
                let totalForecast = 0;
                let totalCost = 0;
                let totalOverhead = 0;

                const rowsHtml = normalizedItems.map(function(item) {
                    const productName = escapeHtml(item.product_name || 'Unknown Product');
                    const category = escapeHtml((item.category || '').toString());
                    const metrics = computeStoreItemMetrics(item);

                    totalAdded += metrics.addedQty;
                    totalForecast += metrics.forecast;
                    totalCost += metrics.totalCost;
                    totalOverhead += metrics.overheadCost;

                    return `
            <div class="p-2 bg-gray-50 rounded-md border border-gray-100">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-800 truncate">${productName}</p>
                        <p class="text-[11px] text-gray-500">${category} • ${formatQuantityValue(metrics.addedQty)} added</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-[11px] text-primary font-semibold">${formatPesoAmount(metrics.forecast)}</p>
                        ${isOwnerView ? `<p class="text-[11px] text-emerald-600 font-semibold">${formatPesoAmount(metrics.totalCost)}</p>` : ''}
                    </div>
                </div>
            </div>
        `;
                }).join('');

                return `
        <div class="border border-gray-100 rounded-lg overflow-hidden">
            <button type="button" class="store-distribution-entry w-full text-left p-3 bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-primary truncate"><i class="fas fa-store mr-1"></i>Store</p>
                        <p class="text-[11px] text-gray-500 mt-0.5">${normalizedItems.length} item(s) added</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs font-semibold text-primary">${formatPesoAmount(totalForecast)}</p>
                        <p class="text-[11px] text-gray-500 mt-0.5">${formatQuantityValue(totalAdded)} pcs added</p>
                    </div>
                </div>
                ${isOwnerView ? `
                <div class="flex items-center justify-between gap-3 mt-2 pt-2 border-t border-gray-200">
                    <span class="text-[10px] text-gray-500">Total Cost: <span class="font-semibold text-emerald-600">${formatPesoAmount(totalCost)}</span></span>
                    <span class="text-[10px] text-gray-500">Overhead: <span class="font-semibold text-amber-600">${formatPesoAmount(totalOverhead)}</span></span>
                </div>
                ` : ''}
            </button>
            <div class="store-entry-detail hidden px-3 pb-3 pt-2 bg-white space-y-1.5 border-t border-gray-100">
                ${rowsHtml || '<p class="text-xs text-gray-400 text-center py-2">No items added to the store for this date.</p>'}
            </div>
        </div>
    `;
            }

            function loadStoreAddedItems() {
                const date = ($('#selectedDate').val() || '').toString();
                ensureStoreGroupForDate(date, true).then(function() {
                    renderDistributionList(currentDayDistributionItems, currentDayGroupedData, date);
                    refreshDayTotalsDisplay(date); // keep the "entire day" cards in sync with Store
                });
            }

            $(document).on('click', '.store-distribution-entry', function() {
                $(this).closest('.border').find('.store-entry-detail').toggleClass('hidden');
            });

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
                        return sum + (((item.qty_mode || 'batch') !== 'pieces') ? parseNumericValue(
                            item.product_qnty) : 0);
                    }, 0);

                    const totalPieces = calculateTotalDistributionPieces(groupItems);

                    const forecastTotal = resolveGroupForecastedSales(group, groupItems);
                    const totalCost = resolveGroupTotalCost(group, groupItems);
                    const totalOverhead = resolveGroupOverheadCost(group, groupItems);

                    const card = `
                        <button type="button" class="distribution-group-entry w-full text-left bg-white rounded-lg shadow-sm p-3 border-l-4 border-primary" data-group-key="${groupKey}" data-date="${selectedDate}">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <h4 class="font-medium text-gray-800 truncate"><i class="fas fa-layer-group text-primary mr-1"></i>${groupName}</h4>
                                    <p class="text-xs text-gray-500 mt-0.5">${groupItems.length} item(s) • ${formatQuantityValue(totalBatches)} batches • ${formatQuantityValue(totalPieces)} pcs</p>
                                </div>
                                <span class="text-[11px] font-semibold text-primary flex-shrink-0">${formatPesoAmount(forecastTotal)}</span>
                            </div>
                            ${isOwnerView ? `
                            <div class="flex items-center justify-between gap-3 mt-2 pt-2 border-t border-gray-100">
                                <span class="text-[10px] text-gray-500">Cost: <span class="font-semibold text-emerald-600">${formatPesoAmount(totalCost)}</span></span>
                                <span class="text-[10px] text-gray-500">Overhead: <span class="font-semibold text-amber-600">${formatPesoAmount(totalOverhead)}</span></span>
                            </div>
                            ` : ''}
                            ${groupNote ? `<p class="text-[11px] text-amber-700 mt-1.5 truncate"><i class="fas fa-sticky-note mr-1 text-amber-500"></i>${groupNote}</p>` : ''}
                        </button>
                    `;

                    container.append(card);
                });
            }


            $(window).on('resize', function() {
                updateDistributionListScrollLimit();
            });

            // ===== DATE NAVIGATION =====

            $('#selectedDate').on('change', function() {
                const selectedDate = ($('#selectedDate').val() || '').toString();
                if ((selectedGroupFilter.date || '') !== selectedDate) {
                    clearSelectedGroupFilter();
                }

                updateDateLabel();
                loadDistributionByDate();
                renderCalendar();
                loadStoreAddedItems();

                // Re-evaluate the Add Items button gate for the newly
                // selected date (only today requires an open inventory).
                checkOpenInventoryAndToggleAddItemsButtons();
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

            function setAddItemsModalUiMode(mode) {
                const normalizedMode = (mode === 'edit') ? 'edit' : 'create';
                addItemsModalMode = normalizedMode;

                const isEditMode = normalizedMode === 'edit';
                const isDeletedCategoryEdit = isEditMode && editingGroupContext && editingGroupContext
                    .is_deleted_category;
                const saveButtonLabel = isEditMode ?
                    '<i class="fas fa-save mr-2"></i>Save Group Changes' :
                    '<i class="fas fa-save mr-2"></i>Save to Schedule';

                $('#addItemsModalTitle').text(isEditMode ? 'Edit Distribution Group' : 'Add Baking Items');
                $('#addItemsModalSubtitle').text(isEditMode ?
                    'Add/remove items, rename group, and update note' :
                    'Search and add products for a specific date'
                );
                $('#btnSaveItems').html(
                    isDeletedCategoryEdit ?
                    '<i class="fas fa-lock mr-2"></i>Save Group Changes' :
                    saveButtonLabel
                );
                $('#btnSaveItems').prop('disabled', isDeletedCategoryEdit)
                    .toggleClass('opacity-50 cursor-not-allowed', isDeletedCategoryEdit)
                    .attr('aria-disabled', isDeletedCategoryEdit ? 'true' : 'false');

                if (isDeletedCategoryEdit) {
                    $('#btnSaveItems').attr('title', 'This category has been deleted and can no longer be edited.');
                } else {
                    $('#btnSaveItems').removeAttr('title');
                }

                $('#scheduleDate').prop('disabled', isEditMode);
                $('.schedule-quick-btn').prop('disabled', isEditMode);
                $('#distributionGroupName').prop('disabled', isEditMode);

                if (isEditMode) {
                    $('.schedule-quick-btn').addClass('opacity-50 cursor-not-allowed');
                    $('#distributionGroupName').addClass('bg-gray-100 cursor-not-allowed opacity-70');
                } else {
                    $('.schedule-quick-btn').removeClass('opacity-50 cursor-not-allowed');
                    $('#distributionGroupName').removeClass('bg-gray-100 cursor-not-allowed opacity-70');
                }
            }

            function resetAddItemsModalForm(dateValue = '', preserveContext = false) {
                // Clear stale editing context to prevent it from leaking into fresh creates
                // (unless we're in edit mode and want to preserve the context)
                if (!preserveContext) {
                    clearGroupEditContext();
                }

                $('#scheduleDate').val(dateValue || $('#selectedDate').val());
                lastValidScheduleDate = ($('#scheduleDate').val() || '').toString();
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
                $('#btnModeBatch').removeClass('hidden');
                $('#btnModeBox').removeClass('hidden');
                $('#btnModePieces').removeClass('pointer-events-none w-full');
                $('.qty-mode-btn').prop('disabled', false).removeClass(
                    'cursor-not-allowed bg-gray-200 text-gray-400');
                $('#selectedQtyMode').val('pieces');
                $('.qty-mode-btn').removeClass('bg-primary text-white').addClass(
                    'bg-white text-gray-600 hover:bg-gray-50');
                $('#btnModePieces').removeClass('bg-white text-gray-600 hover:bg-gray-50').addClass(
                    'bg-primary text-white');
                $('#addQtyLabel').html('Quantity (per piece) <span class="text-red-500">*</span>');
                hideProductDropdown();
            }

            function clearGroupEditContext() {
                editingGroupContext = null;
                setAddItemsModalUiMode('create');
            }

            async function resolveGroupForDateAndKey(dateStr, groupKey, sourceItems = null, ensureIds = false) {
                const normalizedDate = (dateStr || '').toString();
                const normalizedGroupKey = (groupKey || '').toString();

                let candidateItems = Array.isArray(sourceItems) ?
                    flattenGroupedDataIfNeeded(sourceItems, normalizedDate) :
                    flattenGroupedDataIfNeeded(($('#calendarDayModal').data('day-items') || calendarData[
                        normalizedDate] || []), normalizedDate);

                let groupedData = normalizeGroupedData(candidateItems, null, normalizedDate);
                let matchedGroup = groupedData.find(function(group) {
                    return String(group.group_key || '') === normalizedGroupKey;
                });

                if (!matchedGroup || (ensureIds && Array.isArray(matchedGroup.items) && matchedGroup.items.some(
                        function(item) {
                            return !getDistributionItemId(item);
                        }))) {
                    const byDateItems = await fetchDistributionItemsByDateRequest(normalizedDate);
                    groupedData = normalizeGroupedData(byDateItems, null, normalizedDate);
                    matchedGroup = groupedData.find(function(group) {
                        return String(group.group_key || '') === normalizedGroupKey;
                    });

                    if (matchedGroup) {
                        candidateItems = byDateItems;
                    }
                }

                return {
                    date: normalizedDate,
                    groupKey: normalizedGroupKey,
                    matchedGroup: matchedGroup || null,
                    items: candidateItems,
                };
            }

            async function openDistributionGroupEditModal(dateStr, groupKey, sourceItems = null) {
                const resolved = await resolveGroupForDateAndKey(dateStr, groupKey, sourceItems, true);
                if (!resolved.matchedGroup) {
                    showToast('warning', 'Unable to load this group for editing.', 3200);
                    return;
                }

                const group = resolved.matchedGroup;
                const groupItems = Array.isArray(group.items) ? group.items : [];
                const normalizedDate = resolved.date;
                const normalizedGroupKey = resolved.groupKey;
                let groupCategoryId = parseInt((group && (group.dist_category_id ?? group.category_id ?? group
                    .dist_cat_id ?? (group.items && group.items[0] && (group.items[0]
                        .dist_category_id ?? group.items[0].category_id ?? group.items[0]
                        .dist_cat_id)))) || 0, 10);

                if (!groupCategoryId) {
                    const groupKeyMatch = normalizedGroupKey.match(/^category-(\d+)$/i);
                    if (groupKeyMatch) {
                        groupCategoryId = parseInt(groupKeyMatch[1], 10) || 0;
                    }
                }

                if (!groupCategoryId && Array.isArray(groupItems) && groupItems.length > 0) {
                    const fallbackProductId = String((groupItems.find(function(item) {
                        return item && item.product_id;
                    }) || {}).product_id || '').trim();
                    const fallbackMeta = fallbackProductId ? getLocalDistributionGroupMeta(normalizedDate,
                        fallbackProductId) : null;
                    if (fallbackMeta && fallbackMeta.dist_category_id) {
                        groupCategoryId = parseInt(fallbackMeta.dist_category_id, 10) || 0;
                    }
                }
                const groupCategoryName = (group.group_name || group.title || '').toString().trim();

                editingGroupContext = {
                    date: normalizedDate,
                    group_key: normalizedGroupKey,
                    group_ids: Array.isArray(group.source_group_ids) && group.source_group_ids.length > 0 ?
                        group.source_group_ids.slice() : Array.from(new Set(groupItems.map(function(item) {
                            return getDistributionItemId(item);
                        }).filter(Boolean))),
                    dist_category_id: groupCategoryId > 0 ? groupCategoryId : parseInt(group
                        .dist_category_id || 0, 10) || 0,
                    dist_category_name: (group.group_name || group.title || '').toString().trim(), // NEW
                    original_name: (group.group_name || '').toString().trim(),
                    original_note: (group.group_note || '').toString(),
                    is_deleted_category: false,
                    existing_items: groupItems.map(function(item) {
                        return {
                            item_id: getDistributionItemId(item),
                            item_ids: getMergedDistributionItemIds(item), // NEW
                            identity_key: getDistributionItemIdentityKey(item),
                            product_id: String(item.product_id || '').trim(),
                            product_name: (item.product_name || '').toString(),
                            quantity: parseNumericValue(item.product_qnty),
                            qty_mode: ((item.qty_mode || 'batch') || 'batch').toString()
                                .toLowerCase(),
                        };
                    })
                };

                setAddItemsModalUiMode('edit');
                resetAddItemsModalForm(normalizedDate, true);

                await loadStores(editingGroupContext.dist_category_id || '');
                const hasActiveCategoryOption = Array.isArray(storesCache) && storesCache.some(function(
                    category) {
                    return String(category && (category.dist_cat_id ?? category.id ?? '')) === String(
                        editingGroupContext.dist_category_id || '');
                });
                editingGroupContext.is_deleted_category = Boolean(editingGroupContext.dist_category_id > 0 && !
                    hasActiveCategoryOption);
                ensureCategoryOptionExists(editingGroupContext.dist_category_id, editingGroupContext
                    .dist_category_name);
                setAddItemsModalUiMode('edit');

                itemsToAddList = editingGroupContext.existing_items.map(function(item) {
                    return {
                        product_id: item.product_id,
                        product_name: item.product_name,
                        quantity: item.quantity,
                        qty_mode: item.qty_mode,
                        pieces_per_yield: parseNumericValue((getProductAnalyticsData(item.product_id) || {}).pieces_per_yield),
                        existing: true,
                        item_id: item.item_id,
                        item_ids: item.item_ids, // NEW
                        identity_key: item.identity_key,
                    };
                });
                renderAddedItemsList();

                $('#distributionGroupName').val(editingGroupContext.dist_category_id || '');
                // if (categoryLoad && typeof categoryLoad.always === 'function') {
                //     categoryLoad.always(function() {
                //         if (editingGroupContext && editingGroupContext.dist_category_id) {
                //             $('#distributionGroupName').val(String(editingGroupContext.dist_category_id));
                //             $('#distributionGroupName').find(
                //                 'option[value="' + String(editingGroupContext.dist_category_id) + '"]'
                //             ).prop('selected', true);
                //         }
                //     });
                // }
                $('#overallDistributionNote').val(editingGroupContext.original_note);
                $('#calendarDayModal').addClass('hidden');
                $('#addItemsModal').removeClass('hidden');
            }

            async function deleteDistributionGroupByKey(dateStr, groupKey, sourceItems = null) {
                const resolved = await resolveGroupForDateAndKey(dateStr, groupKey, sourceItems, true);
                if (!resolved.matchedGroup) {
                    showToast('warning', 'Group not found.', 3000);
                    return;
                }

                const groupItems = Array.isArray(resolved.matchedGroup.items) ? resolved.matchedGroup.items : [];

                const resolvedGroupIds = Array.isArray(resolved.matchedGroup.source_group_ids) &&
                    resolved.matchedGroup.source_group_ids.length > 0 ?
                    Array.from(new Set(resolved.matchedGroup.source_group_ids.map(function(groupId) {
                        return normalizeDistributionGroupIdForApi(groupId);
                    }).filter(Boolean))) :
                    Array.from(new Set(groupItems.map(function(item) {
                        return normalizeDistributionGroupIdForApi(item && item.distribution_id);
                    }).filter(Boolean)));

                if (resolvedGroupIds.length > 0) {
                    try {
                        const deleteGroupResults = await Promise.allSettled(
                            resolvedGroupIds.map(function(groupId) {
                                return deleteDistributionGroupRequest(groupId);
                            })
                        );

                        const hasFailedDelete = deleteGroupResults.some(function(result) {
                            return result.status === 'rejected' ||
                                (result.status === 'fulfilled' && result.value && result.value
                                    .success === false);
                        });

                        if (hasFailedDelete) {
                            showToast('danger', 'Failed to delete distribution group.', 3200);
                            return;
                        }

                        groupItems.forEach(function(item) {
                            if (item && item.product_id != null) {
                                removeLocalDistributionGroupMeta(resolved.date, item.product_id);
                            }
                        });

                        clearSelectedGroupFilter(resolved.date);
                        $('#calendarDayModal').addClass('hidden');
                        if (($('#selectedDate').val() || '').toString() === resolved.date) {
                            loadDistributionByDate();
                        }
                        loadMonthDistributions();
                        loadAllDistributions();

                        showToast('success', 'Distribution group deleted successfully.', 3200);
                        return;
                    } catch (error) {
                        console.error('Failed to delete distribution group using group endpoint.', error);
                        showToast('danger', 'Failed to delete distribution group.', 3200);
                        return;
                    }
                }

                if (groupItems.length === 0) {
                    showToast('warning', 'Group has no items to delete.', 3000);
                    return;
                }

                const deletableItems = groupItems
                    .map(function(item) {
                        return {
                            item_id: getDistributionItemId(item),
                            product_id: item.product_id,
                        };
                    })
                    .filter(function(item) {
                        return item.item_id != null;
                    });

                if (deletableItems.length === 0) {
                    showToast('danger', 'Unable to delete this group because item IDs are missing.', 3600);
                    return;
                }

                const results = await Promise.allSettled(deletableItems.map(function(item) {
                    return deleteDistributionItemRequest(item.item_id);
                }));

                let deletedCount = 0;
                let failedCount = 0;

                results.forEach(function(result, index) {
                    if (result.status === 'fulfilled' && !(result.value && result.value.success ===
                            false)) {
                        deletedCount += 1;
                        removeLocalDistributionGroupMeta(resolved.date, deletableItems[index]
                            .product_id);
                    } else {
                        failedCount += 1;
                    }
                });

                clearSelectedGroupFilter(resolved.date);
                $('#calendarDayModal').addClass('hidden');
                if (($('#selectedDate').val() || '').toString() === resolved.date) {
                    loadDistributionByDate();
                }
                loadMonthDistributions();
                loadAllDistributions();

                if (failedCount === 0) {
                    showToast('success', 'Distribution group deleted successfully.', 3200);
                } else if (deletedCount > 0) {
                    showToast('warning', `Deleted ${deletedCount} item(s). ${failedCount} item(s) failed.`,
                        4200);
                } else {
                    showToast('danger', 'Failed to delete distribution group.', 3200);
                }
            }

            $('#btnAddItems, #btnAddItemsMobile, #btnAddItemsEmpty').on('click', function() {
                // The open-inventory gate only applies to today's distributions;
                // advance/past dates don't depend on today's shift at all.
                if (isSelectedDateToday()) {
                    if (openInventoryStatus === 'checking') {
                        showToast('warning',
                            'Still checking today\'s inventory status — try again in a moment.', 2500);
                        return;
                    }
                    if (openInventoryStatus !== 'open') {
                        showToast('warning', 'Open today\'s inventory before adding distribution items.',
                            3200);
                        return;
                    }
                }

                clearGroupEditContext();
                resetAddItemsModalForm($('#selectedDate').val());
                $('#distributionGroupName').val('');
                $('#overallDistributionNote').val('');
                $('#addItemsModal').removeClass('hidden');
            });

            $('#btnCloseAddItemsModal, #btnCancelAddItems').on('click', function() {
                $('#addItemsModal').addClass('hidden');
                clearGroupEditContext();
            });

            // Product search input events
            $('#productSearch').on('focus', function() {
                showProductDropdown($(this).val());
            });

            $('#productSearch').on('input', function() {
                const searchTerm = $(this).val();
                $('#selectedProductId').val('');
                $('#btnClearProduct').addClass('hidden');
                showProductDropdown(searchTerm);
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

                // Show effective pieces-per-batch info for the selected product
                const product = getProductAnalyticsData(id) || productsData.find(p => p.product_id == id);
                const batchPiecesPerYield = product ? getProductBatchPiecesPerYield(product) : 0;
                if (product && batchPiecesPerYield > 0) {
                    $('#piecesPerYieldDisplay').text(formatQuantityValue(batchPiecesPerYield));
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
                        const disabledClass = alreadyAdded ? 'opacity-50 pointer-events-none' :
                            'hover:bg-primary/10 cursor-pointer';
                        const piecesOnly = isPiecesOnlyCategory(product.category);
                        let badge = '';
                        if (alreadyAdded) {
                            badge = '<span class="text-xs text-green-600 font-medium">Added</span>';
                        } else if (piecesOnly) {
                            badge =
                                '<span class="text-[10px] text-blue-500 font-medium">pieces only</span>';
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
                return cat === 'grocery' || cat === 'drinks' || cat === 'dough';
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
                // Restore toggle to normal interactive state
                $('#btnModePieces').removeClass('pointer-events-none w-full hidden');
                $('.qty-mode-btn').prop('disabled', false).removeClass(
                    'cursor-not-allowed bg-gray-200 text-gray-400');
                // Reset to pieces mode when unlocking (batch/box are commented out)
                $('#selectedQtyMode').val('pieces');
                $('.qty-mode-btn').removeClass('bg-primary text-white').addClass(
                    'bg-white text-gray-600 hover:bg-gray-50');
                $('#btnModePieces').removeClass('bg-white text-gray-600 hover:bg-gray-50').addClass(
                    'bg-primary text-white');
                $('#addQtyLabel').html('Quantity (per piece) <span class="text-red-500">*</span>');
            }

            $('.qty-mode-btn').on('click', function() {
                if ($(this).prop('disabled')) return;
                const mode = $(this).data('mode');
                $('#selectedQtyMode').val(mode);

                // Update toggle button styles
                $('.qty-mode-btn').removeClass('bg-primary text-white').addClass(
                    'bg-white text-gray-600 hover:bg-gray-50');
                $(this).removeClass('bg-white text-gray-600 hover:bg-gray-50').addClass(
                    'bg-primary text-white');

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

                const product = getProductAnalyticsData(productId) || productsData.find(p => p.product_id ==
                    productId);
                const piecesPerYield = product ? parseNumericValue(product.pieces_per_yield || 0) : 0;
                const traysPerYield = product ? parseNumericValue(product.trays_per_yield || 0) : 0;
                const batchPiecesPerYield = product ? getProductBatchPiecesPerYield(product) : 0;
                const category = (product && product.category ? String(product.category) : '').toLowerCase();
                const isPiecesOnly = category === 'grocery' || category === 'drinks' || category === 'dough';

                if (batchPiecesPerYield <= 0) {
                    $('#piecesConversionHint').addClass('hidden');
                    return;
                }

                if (mode === 'batch') {
                    const boxesPerBatch = traysPerYield > 0 ? traysPerYield : 1;
                    const totalPieces = qty * boxesPerBatch * piecesPerYield;
                    const batchBreakdown = traysPerYield > 0 ?
                        (formatQuantityValue(boxesPerBatch) + ' box(es) × ' + formatQuantityValue(piecesPerYield) +
                            ' pcs/box') :
                        (formatQuantityValue(piecesPerYield) + ' pcs/batch');
                    $('#conversionText').text(formatQuantityValue(qty) + ' batch(es) × (' + batchBreakdown +
                        ') = ' + formatQuantityValue(totalPieces) + ' pieces total');
                    $('#piecesConversionHint').removeClass('hidden');
                } else if (mode === 'box') {
                    const boxPieces = piecesPerYield > 0 ? piecesPerYield : 1;
                    const totalPieces = qty * boxPieces;
                    const batchesUsed = batchPiecesPerYield > 0 ? (totalPieces / batchPiecesPerYield) : 0;
                    const conversionText = formatQuantityValue(qty) + ' box(es) × ' +
                        formatQuantityValue(boxPieces) + ' pcs/box = ' + formatQuantityValue(totalPieces) +
                        ' pieces total';

                    $('#conversionText').text(conversionText);
                    $('#piecesConversionHint').removeClass('hidden');
                } else if (mode === 'pieces') {
                    if (isPiecesOnly) {
                        $('#conversionText').text(formatQuantityValue(qty) +
                            ' piece(s) total (pieces-only product)');
                    } else {
                        const batches = qty / batchPiecesPerYield;
                        $('#conversionText').text(formatQuantityValue(qty) + ' pieces'
                            // ÷ ' +
                            //     formatQuantityValue(batchPiecesPerYield) + ' pcs/batch = ' +
                            //     formatQuantityValue(batches) + ' batch(es) of raw materials used'
                        );
                    }
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
                    console.warn('  -> No product selected');
                    showToast('warning', 'Please search and select a product first.', 3000);
                    return;
                }
                if (quantity <= 0) {
                    console.warn('  -> Invalid quantity:', quantity);
                    showToast('warning', 'Please enter a valid quantity.', 3000);
                    return;
                }
                if (itemsToAddList.some(i => i.product_id == productId)) {
                    console.warn('  -> Product already in list');
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
                    container.html(
                        '<p id="noItemsMsg" class="text-sm text-gray-500 text-center py-2">No products added yet</p>'
                    );
                    $('#itemsSummaryCount').text('0 items');
                    return;
                }

                itemsToAddList.forEach(function(item, index) {
                    const modeLabel = getQtyModeShortLabel(item.qty_mode);
                    const modeBadgeColor = item.qty_mode === 'pieces' ?
                        'bg-blue-100 text-blue-700' :
                        (item.qty_mode === 'box' ? 'bg-amber-100 text-amber-700' :
                            'bg-primary/10 text-primary');
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
                const isEditMode = (typeof addItemsModalMode !== 'undefined') &&
                    addItemsModalMode === 'edit' && !!editingGroupContext;

                // Defense-in-depth: the button is already disabled in this
                // state via updateScheduleQuickBtns(), but guard the handler
                // too in case it's ever triggered programmatically.
                if (!isEditMode && days === 0 && openInventoryStatus !== 'open') {
                    showToast('warning', 'Open today\'s inventory before scheduling items for today.',
                        3200);
                    return;
                }

                const newDate = new Date();
                newDate.setDate(newDate.getDate() + days);
                $('#scheduleDate').val(formatDate(newDate));
                lastValidScheduleDate = formatDate(newDate);
                updateScheduleQuickBtns();
            });

            // Guard the native date picker too: typing/selecting today's date
            // directly (bypassing the quick-select buttons) should be blocked
            // the same way while today's inventory isn't open.
            $('#scheduleDate').on('change', function() {
                const isEditMode = (typeof addItemsModalMode !== 'undefined') &&
                    addItemsModalMode === 'edit' && !!editingGroupContext;
                const picked = ($(this).val() || '').toString();

                if (!isEditMode && picked === formatDate(new Date()) && openInventoryStatus !== 'open') {
                    showToast('warning', 'Open today\'s inventory before scheduling items for today.',
                        3200);
                    // Revert to the previously selected (non-today) date.
                    $(this).val(lastValidScheduleDate || formatDate(new Date(Date.now() + 86400000)));
                } else {
                    lastValidScheduleDate = picked;
                }

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
                $('#editQtyModeBadge').text(qtyMode).removeClass(
                        'bg-primary/10 text-primary bg-blue-100 text-blue-700 bg-amber-100 text-amber-700 bg-gray-200 text-gray-600'
                    )
                    .addClass(qtyMode === 'pieces' ? 'bg-blue-100 text-blue-700' : (qtyMode === 'box' ?
                        'bg-amber-100 text-amber-700' : 'bg-primary/10 text-primary'));
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
                $('#editQtyModeBadge').text(qtyMode).removeClass(
                        'bg-primary/10 text-primary bg-blue-100 text-blue-700 bg-amber-100 text-amber-700 bg-gray-200 text-gray-600'
                    )
                    .addClass(qtyMode === 'pieces' ? 'bg-blue-100 text-blue-700' : (qtyMode === 'box' ?
                        'bg-amber-100 text-amber-700' : 'bg-primary/10 text-primary'));
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

            $('#addItemsForm').on('submit', async function(e) {
                e.preventDefault();

                const scheduleDate = ($('#scheduleDate').val() || '').toString();
                const distributionCategoryId = parseInt($('#distributionGroupName').val() || '0', 10) ||
                    0;
                const selectedCategoryName = ($('#distributionGroupName option:selected').text() || '')
                    .trim();
                const distributionGroupNote = ($('#overallDistributionNote').val() || '').trim();
                const isEditMode = addItemsModalMode === 'edit' && editingGroupContext;
                let createdGroupId = null;

                logDistributionFlow('log', 'Add items form submit started.', {
                    mode: isEditMode ? 'edit-group' : 'create-group',
                    schedule_date: scheduleDate,
                    selected_category_id: distributionCategoryId,
                    selected_category_name: selectedCategoryName,
                    entered_group_note_length: distributionGroupNote.length,
                    list_item_count: itemsToAddList.length,
                    list_items: itemsToAddList.map(function(item) {
                        return {
                            product_id: item.product_id,
                            product_name: item.product_name,
                            quantity: parseNumericValue(item.quantity),
                            qty_mode: item.qty_mode,
                            existing: Boolean(item.existing),
                            item_id: item.item_id != null ? parseInt(item.item_id, 10) : null,
                        };
                    }),
                });

                if (itemsToAddList.length === 0 && !isEditMode) {
                    console.warn('  -> No items in list and not edit mode');
                    showToast('warning', 'Please add at least one product to the list.', 3000);
                    logDistributionFlow('warn', 'Add items form submit aborted.', {
                        reason: 'No items in create mode.',
                    });
                    return;
                }

                if (isEditMode && editingGroupContext && editingGroupContext.is_deleted_category) {
                    showToast('warning', 'This category has been deleted and can no longer be edited.',
                        3200);
                    return;
                }

                if (!distributionCategoryId) {
                    showToast('warning', 'Please select a destination category.', 3000);
                    return;
                }

                $('#btnSaveItems').prop('disabled', true).html(
                    '<i class=\"fas fa-spinner fa-spin mr-2\"></i>Saving...');


                try {
                    if (isEditMode) {
                        const context = editingGroupContext;
                        const targetDate = (context.date || scheduleDate || '').toString();
                        const targetGroupKey = (context.group_key || '').toString();
                        const targetGroupIds = Array.isArray(context.group_ids) && context.group_ids
                            .length > 0 ?
                            context.group_ids : [normalizeDistributionGroupIdForApi(targetGroupKey)]
                            .filter(Boolean);
                        const savedGroupName = selectedCategoryName ||
                            (context.original_name || '').toString().trim() ||
                            getNextAutoGroupName(targetDate);
                        if (targetGroupIds.length === 0) {
                            showToast('danger', 'Unable to resolve distribution group for update.',
                                3600);
                            return;
                        }

                        logDistributionFlow('log', 'Update group flow started.', {
                            target_date: targetDate,
                            target_group_key: targetGroupKey,
                            target_group_ids: targetGroupIds,
                            target_category_id: distributionCategoryId,
                            original_group_name: (context.original_name || '').toString(),
                            original_note_length: ((context.original_note || '').toString())
                                .length,
                            saved_group_name: savedGroupName,
                            updated_note_length: distributionGroupNote.length,
                        });

                        const updateGroupPayload = {
                            dist_category_id: distributionCategoryId,
                            distributed_to_note: distributionGroupNote || null,
                        };

                        logDistributionFlow('log', 'Update group metadata request started.', {
                            endpoint: 'Distribution/UpdateGroup/:id',
                            group_ids: targetGroupIds,
                            payload: updateGroupPayload,
                        });

                        await Promise.all(targetGroupIds.map(function(groupId) {
                            return updateDistributionGroupRequest(groupId,
                                updateGroupPayload);
                        }));

                        logDistributionFlow('log', 'Update group metadata request completed.', {
                            endpoint: 'Distribution/UpdateGroup/:id',
                            group_ids: targetGroupIds,
                        });

                        const normalizedItems = itemsToAddList.map(function(item) {
                            return {
                                product_id: String(item.product_id || '').trim(),
                                quantity: parseNumericValue(item.quantity),
                                qty_mode: ((item.qty_mode || 'batch').toString().toLowerCase()),
                                existing: Boolean(item.existing),
                                item_id: item.item_id != null ? parseInt(item.item_id, 10) : null,
                                item_ids: Array.isArray(item.item_ids) && item.item_ids.length >
                                    0 ?
                                    item.item_ids : (item.item_id != null ? [parseInt(item
                                        .item_id, 10)] : []),
                                identity_key: (item.identity_key ||
                                        `product-${String(item.product_id || '').trim()}`)
                                    .toString(),
                            };
                        });

                        const existingItemsNow = normalizedItems.filter(function(item) {
                            return item.existing;
                        });
                        const existingIdentitySet = new Set(existingItemsNow.map(function(item) {
                            return item.identity_key;
                        }));
                        const existingBefore = Array.isArray(context.existing_items) ? context
                            .existing_items : [];

                        const removedExisting = existingBefore.filter(function(item) {
                            return !existingIdentitySet.has(String(item.identity_key || ''));
                        });

                        const removableIdPairs = [];
                        removedExisting.forEach(function(item) {
                            const ids = Array.isArray(item.item_ids) && item.item_ids.length >
                                0 ?
                                item.item_ids :
                                (item.item_id != null ? [item.item_id] : []);
                            ids.forEach(function(id) {
                                removableIdPairs.push({
                                    item_id: id,
                                    product_id: item.product_id
                                });
                            });
                        });
                        const removedMissingIdCount = removedExisting.filter(function(item) {
                            const ids = Array.isArray(item.item_ids) && item.item_ids.length >
                                0 ?
                                item.item_ids :
                                (item.item_id != null ? [item.item_id] : []);
                            return ids.length === 0;
                        }).length;

                        logDistributionFlow('log', 'Update group diff summary.', {
                            normalized_item_count: normalizedItems.length,
                            existing_now_count: existingItemsNow.length,
                            existing_before_count: existingBefore.length,
                            removed_existing_count: removedExisting.length,
                            removable_id_pairs_count: removableIdPairs.length,
                            removed_missing_id_count: removedMissingIdCount,
                        });

                        const deleteResults = await Promise.allSettled(removableIdPairs.map(function(
                            pair) {
                            return deleteDistributionItemRequest(pair.item_id);
                        }));

                        let deletedCount = 0;
                        let deleteFailedCount = 0;
                        const deletedProductIds = [];

                        deleteResults.forEach(function(result, index) {
                            if (result.status === 'fulfilled' && !(result.value && result.value
                                    .success === false)) {
                                deletedCount += 1;
                                deletedProductIds.push(removableIdPairs[index].product_id);
                            } else {
                                deleteFailedCount += 1;
                            }
                        });

                        logDistributionFlow('log', 'Update group removed-item delete results.', {
                            attempted: removableIdPairs.length,
                            deleted_count: deletedCount,
                            failed_count: deleteFailedCount,
                            deleted_product_ids: deletedProductIds,
                        });

                        const newItems = normalizedItems.filter(function(item) {
                            return !item.existing;
                        });

                        const addPayloads = newItems.map(function(item) {
                            return {
                                distribution_id: targetGroupIds[0],
                                product_id: item.product_id,
                                product_qnty: item.quantity,
                                qty_mode: item.qty_mode,
                            };
                        });

                        logDistributionFlow('log', 'Update group add-item phase started.', {
                            new_item_count: newItems.length,
                            add_payload_count: addPayloads.length,
                            add_payloads: addPayloads,
                        });

                        const addResults = await Promise.allSettled(addPayloads.map(function(payload) {
                            return addDistributionItemRequest(payload);
                        }));

                        const succeededAdds = [];
                        let duplicateCount = 0;
                        let addFailedCount = 0;
                        let sawInsufficient = false;
                        let insufficientMaterials = [];

                        addResults.forEach(function(result, index) {
                            const currentPayload = addPayloads[index];

                            if (result.status === 'fulfilled') {
                                const response = result.value || {};
                                if (response.success === false && response.error) {
                                    console.warn('Item add returned failure response:',
                                        response);
                                    addFailedCount += 1;
                                    return;
                                }

                                succeededAdds.push(currentPayload);
                                return;
                            }

                            const xhr = result.reason || {};
                            const responseJson = xhr.responseJSON || {};
                            if (xhr.bypass_cancelled) {
                                return;
                            }
                            if (xhr.status === 409 || responseJson.duplicate) {
                                duplicateCount += 1;
                                return;
                            }

                            // Handle insufficient materials (accept array or object)
                            if (xhr.status === 400 && responseJson.insufficient_materials) {
                                sawInsufficient = true;
                                if (Array.isArray(responseJson.insufficient_materials)) {
                                    insufficientMaterials = insufficientMaterials.concat(
                                        responseJson.insufficient_materials);
                                } else if (typeof responseJson.insufficient_materials ===
                                    'object') {
                                    // Convert object to array of strings
                                    insufficientMaterials = insufficientMaterials.concat(
                                        Object.values(responseJson.insufficient_materials)
                                        .flat()
                                    );
                                }
                                return;
                            }

                            addFailedCount += 1;
                        });

                        logDistributionFlow('log', 'Update group add-item phase completed.', {
                            attempted: addPayloads.length,
                            succeeded_count: succeededAdds.length,
                            duplicate_count: duplicateCount,
                            failed_count: addFailedCount,
                            saw_insufficient: sawInsufficient,
                            insufficient_count: insufficientMaterials.length,
                        });

                        deletedProductIds.forEach(function(productId) {
                            removeLocalDistributionGroupMeta(targetDate, productId);
                        });

                        const remainingExistingProductIds = existingItemsNow.map(function(item) {
                            return item.product_id;
                        });
                        const succeededAddProductIds = succeededAdds.map(function(item) {
                            return item.product_id;
                        });
                        const finalProductIds = Array.from(new Set(
                            remainingExistingProductIds.concat(succeededAddProductIds).map(
                                function(productId) {
                                    return String(productId || '').trim();
                                }).filter(Boolean)
                        ));

                        logDistributionFlow('log', 'Update group final reconciliation.', {
                            remaining_existing_product_ids: remainingExistingProductIds,
                            succeeded_add_product_ids: succeededAddProductIds,
                            final_product_ids: finalProductIds,
                        });

                        if (finalProductIds.length > 0) {
                            setLocalDistributionGroupMetaForItems(targetDate, finalProductIds, {
                                group_key: 'category-' + distributionCategoryId,
                                group_name: savedGroupName,
                                group_note: distributionGroupNote,
                                dist_category_id: distributionCategoryId,
                                dist_category_name: selectedCategoryName,
                            });
                            setSelectedGroupFilter(targetDate, 'category-' + distributionCategoryId);
                        } else {
                            clearSelectedGroupFilter(targetDate);
                        }

                        if (sawInsufficient && insufficientMaterials.length > 0) {
                            showInsufficientMaterialsAlert(Array.from(new Set(insufficientMaterials)));
                        }

                        $('#addItemsModal').addClass('hidden');
                        clearGroupEditContext();
                        resetAddItemsModalForm(targetDate);
                        $('#overallDistributionNote').val('');
                        $('#selectedDate').val(targetDate).trigger('change');
                        loadMonthDistributions();
                        loadAllDistributions();

                        const attemptedAdds = addPayloads.length;
                        if (deletedCount > 0 || succeededAdds.length > 0 || (finalProductIds.length >
                                0 && (distributionCategoryId !== (context.dist_category_id || 0) ||
                                    distributionGroupNote !== context.original_note))) {
                            let message = `Distribution group "${savedGroupName}" updated.`;
                            if (deletedCount > 0) {
                                message += ` Removed ${deletedCount} item(s).`;
                            }
                            if (succeededAdds.length > 0) {
                                message += ` Added ${succeededAdds.length} item(s).`;
                            }
                            if (attemptedAdds > 0 && (duplicateCount > 0 || addFailedCount > 0 ||
                                    sawInsufficient)) {
                                message += ' Some items were not added.';
                            }
                            if (removedMissingIdCount > 0 || deleteFailedCount > 0) {
                                message += ' Some removed items could not be deleted.';
                            }

                            logDistributionFlow('log', 'Update group completed with changes.', {
                                toast_type: (duplicateCount > 0 || addFailedCount > 0 ||
                                        deleteFailedCount > 0 || removedMissingIdCount > 0) ?
                                    'warning' : 'success',
                                message: message,
                            });

                            showToast((duplicateCount > 0 || addFailedCount > 0 || deleteFailedCount >
                                    0 || removedMissingIdCount > 0) ? 'warning' : 'success',
                                message, 4500);
                        } else if (attemptedAdds > 0 && duplicateCount === attemptedAdds) {
                            logDistributionFlow('warn',
                                'Update group completed with no new adds due to duplicates.', {
                                    attempted_adds: attemptedAdds,
                                    duplicate_count: duplicateCount,
                                });
                            showToast('warning', 'All selected products already exist for that date.',
                                4000);
                        } else if (finalProductIds.length === 0 && removedExisting.length > 0) {
                            logDistributionFlow('log',
                                'Update group completed with empty group result.', {
                                    removed_existing_count: removedExisting.length,
                                });
                            showToast('success', 'Distribution group is now empty.', 3200);
                        } else {
                            logDistributionFlow('warn',
                                'Update group completed with no effective changes.', {
                                    attempted_adds: attemptedAdds,
                                    final_product_count: finalProductIds.length,
                                });
                            showToast('warning', 'No changes were made to this group.', 3200);
                        }

                        return;
                    }

                    // Guard: ensure editingGroupContext is fully cleared in create mode
                    if (editingGroupContext && editingGroupContext.group_id) {
                        console.error('BUG: Create mode has stale editingGroupContext:',
                            editingGroupContext);
                        showToast('danger',
                            'Unexpected state: stale editing context in create mode. Please refresh and try again.',
                            4000);
                        return;
                    }

                    const itemsToAdd = itemsToAddList.map(function(item) {
                        return {
                            product_id: item.product_id,
                            quantity: parseNumericValue(item.quantity),
                            qty_mode: item.qty_mode,
                            raw_qty_mode: item.qty_mode
                        };
                    });

                    const savedGroupName = selectedCategoryName || getNextAutoGroupName(scheduleDate);
                    const addGroupPayload = {
                        dist_category_id: distributionCategoryId,
                        distribution_date: scheduleDate,
                        distributed_to_note: distributionGroupNote || null,
                    };

                    logDistributionFlow('log', 'Create group request started.', {
                        endpoint: 'Distribution/AddGroup',
                        payload: addGroupPayload,
                        normalized_items_to_add: itemsToAdd,
                    });

                    const groupResponse = await $.ajax({
                        url: baseUrl + 'Distribution/AddGroup',
                        method: 'POST',
                        contentType: 'application/json',
                        dataType: 'json',
                        data: JSON.stringify(addGroupPayload),
                    });

                    logDistributionFlow('log', 'Create group request completed.', {
                        endpoint: 'Distribution/AddGroup',
                        response: groupResponse || {},
                    });

                    if (!groupResponse || !groupResponse.group_id) {
                        logDistributionFlow('error', 'Create group request returned no group_id.', {
                            response: groupResponse || null,
                        });
                        showToast('danger', 'Failed to create distribution group.', 3200);
                        return;
                    }

                    const newGroupId = String(groupResponse.group_id).trim();
                    createdGroupId = newGroupId;
                    const groupMeta = {
                        group_key: 'category-' + distributionCategoryId,
                        group_name: savedGroupName,
                        group_note: distributionGroupNote,
                        dist_category_id: distributionCategoryId,
                        dist_category_name: selectedCategoryName,
                    };

                    const payloads = itemsToAdd.map(function(item) {
                        return {
                            distribution_id: newGroupId,
                            product_id: item.product_id,
                            product_qnty: item.quantity,
                            qty_mode: item.qty_mode,
                            raw_qty_mode: item.raw_qty_mode
                        };
                    });

                    logDistributionFlow('log', 'Create group add-item phase started.', {
                        group_id: newGroupId,
                        payload_count: payloads.length,
                        payloads: payloads,
                    });

                    const results = await Promise.allSettled(payloads.map(function(payload) {
                        return addDistributionItemRequest(payload, payload.raw_qty_mode);
                    }));

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
                                console.warn('Item add returned failure response:', response);
                                genericErrorCount += 1;
                                return;
                            }

                            succeededPayloads.push(currentPayload);
                            return;
                        }

                        const xhr = result.reason || {};
                        const responseJson = xhr.responseJSON || {};
                        if (xhr.bypass_cancelled) {
                            return;
                        }

                        if (xhr.status === 409 || responseJson.duplicate) {
                            duplicateCount += 1;
                            return;
                        }

                        // Handle insufficient materials (accept array or object)
                        if (xhr.status === 400 && responseJson.insufficient_materials) {
                            sawInsufficient = true;
                            if (Array.isArray(responseJson.insufficient_materials)) {
                                insufficientMaterials = insufficientMaterials.concat(
                                    responseJson.insufficient_materials);
                            } else if (typeof responseJson.insufficient_materials ===
                                'object') {
                                // Convert object to array of strings
                                insufficientMaterials = insufficientMaterials.concat(
                                    Object.values(responseJson.insufficient_materials)
                                    .flat()
                                );
                            }
                            return;
                        }

                        genericErrorCount += 1;
                    });

                    logDistributionFlow('log', 'Create group add-item phase completed.', {
                        group_id: newGroupId,
                        attempted: payloads.length,
                        succeeded_count: succeededPayloads.length,
                        duplicate_count: duplicateCount,
                        generic_error_count: genericErrorCount,
                        saw_insufficient: sawInsufficient,
                        insufficient_count: insufficientMaterials.length,
                    });

                    if (succeededPayloads.length > 0) {
                        const productIds = succeededPayloads.map(function(payload) {
                            return payload.product_id;
                        });

                        setLocalDistributionGroupMetaForItems(scheduleDate, productIds, groupMeta);

                        $('#addItemsModal').addClass('hidden');
                        clearGroupEditContext();
                        resetAddItemsModalForm(scheduleDate);
                        $('#overallDistributionNote').val('');
                        $('#selectedDate').val(scheduleDate).trigger('change');
                        loadMonthDistributions();
                        loadAllDistributions();
                    }

                    if (sawInsufficient && insufficientMaterials.length > 0) {
                        showInsufficientMaterialsAlert(Array.from(new Set(insufficientMaterials)));
                    }

                    const totalAttempted = payloads.length;
                    if (succeededPayloads.length === totalAttempted) {
                        logDistributionFlow('log', 'Create group completed with full success.', {
                            group_id: newGroupId,
                            group_name: savedGroupName,
                            total_attempted: totalAttempted,
                        });
                        showToast('success',
                            `Distribution group "${savedGroupName}" added successfully!`, 3200);
                        return;
                    }

                    if (succeededPayloads.length > 0) {
                        let partialMessage =
                            `Saved ${succeededPayloads.length} of ${totalAttempted} item(s) to group "${savedGroupName}".`;
                        if (duplicateCount > 0) {
                            partialMessage += ` ${duplicateCount} duplicate item(s) skipped.`;
                        }
                        if (sawInsufficient || genericErrorCount > 0) {
                            partialMessage += ' Some items were not added.';
                        }

                        logDistributionFlow('warn', 'Create group completed with partial success.', {
                            group_id: newGroupId,
                            message: partialMessage,
                        });

                        showToast('warning', partialMessage, 4500);
                        return;
                    }

                    // All items failed — rollback by deleting the empty group
                    console.warn('All items failed to add. Rolling back group creation for group:',
                        newGroupId);
                    logDistributionFlow('warn',
                        'Create group add-item phase fully failed; rollback started.', {
                            group_id: newGroupId,
                            attempted: totalAttempted,
                            duplicate_count: duplicateCount,
                            saw_insufficient: sawInsufficient,
                            generic_error_count: genericErrorCount,
                        });
                    try {
                        await deleteDistributionGroupRequest(newGroupId);
                        logDistributionFlow('log', 'Create group rollback succeeded.', {
                            group_id: newGroupId,
                        });
                    } catch (rollbackError) {
                        console.error('Group rollback failed:', rollbackError);
                        logDistributionFlow('error', 'Create group rollback failed.', {
                            group_id: newGroupId,
                            error: rollbackError,
                        });
                        // Still show error to user even if rollback fails
                    }

                    if (duplicateCount === totalAttempted) {
                        showToast('warning', 'All selected products already exist for that date.',
                            4000);
                    } else if (sawInsufficient) {
                        showToast('danger',
                            'Insufficient raw materials for one or more selected items.', 4500);
                    } else {
                        showToast('danger', 'Failed to add distribution group. Please try again.',
                            3200);
                    }
                } catch (error) {
                    console.error('Error saving distribution group:', error);
                    logDistributionFlow('error', 'Add items form submit failed with exception.', {
                        mode: isEditMode ? 'edit-group' : 'create-group',
                        created_group_id: createdGroupId,
                        error: error,
                    });
                    // If we created a group but hit an error during item addition, clean it up
                    if (createdGroupId) {
                        try {
                            await deleteDistributionGroupRequest(createdGroupId);
                            logDistributionFlow('log', 'Cleanup after exception succeeded.', {
                                group_id: createdGroupId,
                            });
                        } catch (cleanupError) {
                            console.error('Group cleanup failed:', cleanupError);
                            logDistributionFlow('error', 'Cleanup after exception failed.', {
                                group_id: createdGroupId,
                                error: cleanupError,
                            });
                        }
                    }
                    showToast('danger', 'Failed to save distribution group. Please try again.', 3200);
                } finally {
                    const saveButtonLabel = addItemsModalMode === 'edit' ?
                        '<i class="fas fa-save mr-2"></i>Save Group Changes' :
                        '<i class="fas fa-save mr-2"></i>Save to Schedule';
                    $('#btnSaveItems').prop('disabled', false).html(saveButtonLabel);
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

        function sumCurrencyField(items, field) {
            return (Array.isArray(items) ? items : []).reduce(function(sum, item) {
                return sum + parseNumericValue(item && item[field]);
            }, 0);
        }

        function getDistinctGroupCount(items, fallbackDate = '') {
            // Count distinct display group keys so same-category rows collapse into one group.
            const set = new Set();
            (items || []).forEach(function(item) {
                const key = (item && (item.distribution_display_group_key || item.distribution_group_key)) || '';
                if (key) {
                    set.add(key);
                }
            });
            return set.size;
        }

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
            // Mirror the Add Items gate: the "Today" quick-select is only
            // safe to use while today's inventory is actually open, since
            // picking Today here is equivalent to clicking Add Items for
            // today. Without this, a user could bypass the gate entirely by
            // choosing another date first, opening the modal, then hitting
            // "Today" to sneak a today-dated item in.
            const isEditMode = (typeof addItemsModalMode !== 'undefined') &&
                addItemsModalMode === 'edit' && !!editingGroupContext;
            const todayGateBlocked = (typeof openInventoryStatus !== 'undefined') &&
                openInventoryStatus !== 'open';

            $('.schedule-quick-btn').each(function() {
                const days = parseInt($(this).data('days'));
                const btnDate = new Date();
                btnDate.setDate(btnDate.getDate() + days);
                const isTodayBtn = days === 0;
                // Edit mode disables every quick button (date can't change);
                // outside edit mode, only the Today button is gated, and only
                // while today's inventory isn't open.
                const blockThisBtn = isEditMode || (isTodayBtn && todayGateBlocked);

                $(this)
                    .prop('disabled', blockThisBtn)
                    .toggleClass('opacity-50 cursor-not-allowed', blockThisBtn)
                    .attr('title', (!isEditMode && isTodayBtn && todayGateBlocked) ?
                        'Open today\'s inventory before scheduling items for today.' : '');

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
            const totalPiecesCalculator = (typeof window.calculateTotalDistributionPieces === 'function') ?
                window.calculateTotalDistributionPieces :
                function() {
                    return 0;
                };

            const computedTotalItems = distributionItems.length;
            const computedTotalBatches = distributionItems.reduce(function(sum, item) {
                return sum + (((item.qty_mode || 'batch') === 'pieces') ? 0 : parseNumericValue(item.product_qnty));
            }, 0);
            const computedTotalPieces = totalPiecesCalculator(distributionItems);

            const total = hasSummary && summary.total_items != null ?
                (parseInt(summary.total_items) || 0) :
                computedTotalItems;
            const totalBatches = hasSummary && summary.total_batches != null ?
                parseNumericValue(summary.total_batches) :
                computedTotalBatches;
            const totalPieces = hasSummary && summary.total_pieces != null ?
                parseNumericValue(summary.total_pieces) :
                computedTotalPieces;
            const totalGroups = hasSummary && summary.total_groups != null ?
                (parseInt(summary.total_groups) || 0) :
                getDistinctGroupCount(distributionItems, fallbackDate);

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

        function formatPesoAmountPrecise(amount) {
            const safeAmount = Number.isFinite(amount) ? amount : 0;
            return '₱' + safeAmount.toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 5
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

        function calculateAdditionalCostTotal(items) {
            const distributionItems = Array.isArray(items) ? items : [];

            return distributionItems.reduce(function(sum, item) {
                return sum + parseNumericValue(item.additional_cost);
            }, 0);
        }

        function updateModalForecastedSales(items, summary = null) {
            const hasSummaryForecast = summary && Object.prototype.hasOwnProperty.call(summary, 'forecasted_sales_total');
            const forecastedTotal = hasSummaryForecast ?
                parseNumericValue(summary.forecasted_sales_total) :
                calculateForecastedSalesTotal(items);
            $('#modalForecastedSalesTotal').text(formatPesoAmount(forecastedTotal));
        }

        function updateForecastedSales(items, summary = null) {
            const hasSummaryForecast = summary && Object.prototype.hasOwnProperty.call(summary, 'forecasted_sales_total');
            const forecastedTotal = hasSummaryForecast ?
                parseNumericValue(summary.forecasted_sales_total) :
                calculateForecastedSalesTotal(items);

            const formattedTotal = formatPesoAmount(forecastedTotal);
            $('#forecastedSalesTotal').text(formattedTotal);
            $('#mobileForecastedSalesTotal').text(formattedTotal);
        }

        /**
         * Show a blocking alert with details of insufficient raw materials.
         * @param {Array} materials - Array of strings describing each shortage
         */
        let insufficientMaterialsResolver = null;

        function confirmInsufficientMaterials(materials) {
            return new Promise(function(resolve) {
                insufficientMaterialsResolver = resolve;
                showInsufficientMaterialsAlert(materials);
            });
        }

        function resolveInsufficientMaterials(proceed) {
            $('#insufficientMaterialModal').addClass('hidden');
            if (insufficientMaterialsResolver) {
                const resolve = insufficientMaterialsResolver;
                insufficientMaterialsResolver = null;
                resolve(proceed);
            }
        }

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
            html +=
                '<i class="fas fa-lightbulb mr-1"></i> Please restock raw materials in <strong>Stock Initial</strong> before proceeding.';
            html += '</div>';

            $('#insufficientMaterialContent').html(html);
            $('#insufficientMaterialModal').removeClass('hidden');
        }
    </script>

    <!-- Manage Distribution Categories Modal -->
    <div id="manageDistributionCategoriesModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-tags mr-2 text-primary"></i>Manage Distribution Categories
                </h3>
                <button type="button" id="btnCloseDistributionCategoryModal"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="px-6 py-5 overflow-y-auto">
                <form id="distributionCategoryForm" class="space-y-4">
                    <input type="hidden" id="edit_distribution_category_id" value="">
                    <div>
                        <label for="distribution_category_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="distribution_category_name" name="name" maxlength="191" required
                            placeholder="e.g., Morning Delivery"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" id="btnCancelDistributionCategory"
                            class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" id="btnSaveDistributionCategory"
                            class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary">
                            Save
                        </button>
                    </div>
                </form>

                <div class="my-5 border-t border-gray-200"></div>

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Categories</h4>
                        <button type="button" id="btnRefreshDistributionCategories"
                            class="text-xs font-medium text-primary hover:text-secondary">
                            Refresh
                        </button>
                    </div>
                    <div id="distributionCategoriesList" class="space-y-2">
                        <div class="text-sm text-gray-500 text-center py-4">Loading categories...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= asset_url('js/DistributionCategoryModal.js') ?>?v=<?= time() ?>"></script>

    <!-- Insufficient Materials Modal -->
    <div id="insufficientMaterialModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[80vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>Insufficient Raw Materials
                </h3>
                <button onclick="resolveInsufficientMaterials(false)"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="px-6 py-4 overflow-y-auto" id="insufficientMaterialContent">
            </div>
            <div class="px-6 py-3 border-t border-gray-200 flex justify-end">
                <div class="flex justify-end gap-2">
                    <button onclick="resolveInsufficientMaterials(false)"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium">
                        Cancel
                    </button>
                    <button onclick="resolveInsufficientMaterials(true)"
                        class="px-5 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors text-sm font-medium">
                        Proceed anyway
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>