<?php $isOwnerView = (($employee_type ?? '') === 'owner'); ?>

<body class="bg-gray-50">
    <!-- Main Content -->
    <div class="p-4 sm:ml-60">
        <div class="mt-16">
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">

                <div class="flex flex-wrap items-center justify-between gap-3 w-full">

                    <!-- LEFT: Navigation -->
                    <div class="flex flex-wrap gap-2">
                        <a href="<?= base_url('Inventory/History') ?>"
                            class="inline-flex h-10 items-center rounded-lg border border-gray-300 px-4 text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 focus:ring-2 focus:ring-gray-300 transition">
                            <i class="fas fa-clock-rotate-left mr-2 text-gray-500"></i>
                            History
                        </a>
                    </div>

                    <!-- RIGHT: Actions -->
                    <div class="flex flex-wrap items-center gap-2">

                        <!-- Primary Action -->
                        <button id="btnAddTodaysInventory"
                            class="inline-flex h-10 items-center rounded-lg border border-green-200 bg-green-50 px-4 text-sm font-medium text-green-700 hover:bg-green-100 focus:ring-2 focus:ring-green-300 transition">
                            <i class="fas fa-plus mr-2 text-green-500"></i>
                            Add Inventory
                        </button>

                        <!-- Secondary Actions -->
                        <button id="btnAddProductToInventory"
                            class="inline-flex h-10 items-center rounded-lg border border-green-200 bg-green-50 px-4 text-sm font-medium text-green-700 hover:bg-green-100 focus:ring-2 focus:ring-green-300 transition">
                            <i class="fas fa-box-open mr-2 text-green-500"></i>
                            Add Product
                        </button>

                        <button id="btnDistributions"
                            class="inline-flex h-10 items-center rounded-lg border border-blue-200 bg-blue-50 px-4 text-sm font-medium text-blue-700 hover:bg-blue-100 focus:ring-2 focus:ring-blue-300 transition">
                            <i class="fas fa-truck-loading mr-2 text-blue-500"></i>
                            Distributions
                            <span id="distCount"
                                class="ml-2 inline-flex items-center justify-center rounded-full bg-blue-500 px-2 py-0.5 text-xs font-semibold text-white">
                                0
                            </span>
                        </button>

                        <button id="btnSendInventoryReport"
                            class="inline-flex h-10 items-center rounded-lg border border-indigo-200 bg-indigo-50 px-4 text-sm font-medium text-indigo-700 hover:bg-indigo-100 focus:ring-2 focus:ring-indigo-300 transition">
                            <i class="fas fa-paper-plane mr-2 text-indigo-500"></i>
                            Send Report
                        </button>

                        <button id="btnResetInventoryForNextShift" onclick="openResetModal()"
                            class="hidden inline-flex h-10 items-center rounded-lg border border-yellow-200 bg-yellow-50 px-4 text-sm font-medium text-yellow-700 hover:bg-yellow-100 focus:ring-2 focus:ring-yellow-300 transition">
                            <i class="fas fa-sync-alt mr-2 text-yellow-500"></i>
                            Create New Inventory for new Shift
                        </button>

                        <!-- Divider -->
                        <div class="hidden sm:block h-6 w-px bg-gray-300 mx-1"></div>

                        <!-- Destructive Actions -->
                        <button id="btnCloseInventory" onclick="closeInventory()"
                            class="inline-flex h-10 items-center rounded-lg border border-gray-300 bg-gray-50 px-4 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:ring-2 focus:ring-gray-400 transition">
                            <i class="fas fa-lock mr-2 text-gray-500"></i>
                            Close
                        </button>

                        <button id="btnOpenInventory" onclick="openInventory()"
                            class="inline-flex h-10 items-center rounded-lg border border-green-200 bg-green-50 px-4 text-sm font-medium text-green-700 hover:bg-green-100 focus:ring-2 focus:ring-green-300 transition">
                            <i class="fas fa-lock-open mr-2 text-green-500"></i>
                            Open
                        </button>

                        <button id="btnDeleteTodaysInventory"
                            class="inline-flex h-10 items-center rounded-lg border border-red-200 bg-red-50 px-4 text-sm font-medium text-red-700 hover:bg-red-100 focus:ring-2 focus:ring-red-300 transition">
                            <i class="fas fa-trash mr-2 text-red-500"></i>
                            Delete
                        </button>


                    </div>
                </div>


                <!-- Divider line -->
                <div class="border-t border-gray-200 my-4"></div>

                <!-- Filters section -->
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <!-- Date Display -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700">Date:</label>
                            <span id="todayDate"
                                class="text-sm font-semibold text-gray-900 px-3 py-2 bg-gray-50 rounded-md border border-gray-200"></span>
                        </div>

                        <!-- Time Range Display -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700">Time:</label>
                            <span id="timeRange"
                                class="text-sm font-semibold text-gray-900 px-3 py-2 bg-gray-50 rounded-md border border-gray-200">--:--
                                - --:--</span>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Floating buttons for mobile -->
            <div class="fixed bottom-6 left-0 right-0 flex justify-center gap-2 z-30 sm:hidden px-6">
                <button id="btnAddTodaysInventoryMobile" type="button"
                    class="hidden flex-1 items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    Add Inventory
                </button>
                <button id="btnAddProductToInventoryMobile" type="button"
                    class="hidden flex-1 items-center justify-center rounded-lg bg-green-600 px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400">
                    <i class="fas fa-plus mr-2"></i> Add Product
                </button>
            </div>

            <div id="inventoryInteractionArea" class="grid grid-cols-1 xl:grid-cols-12 gap-4 items-start">
                <div id="inventoryTableArea" class="relative xl:col-span-8 min-w-0">
                    <div id="inventoryLockOverlay" class="hidden absolute inset-0 z-20 rounded-lg pointer-events-none">
                        <div class="h-full w-full flex items-center justify-center p-3">
                            <div
                                class="bg-gray-900/55 text-white text-sm font-medium rounded-lg px-4 py-2 shadow text-center">
                                Inventory is closed. Click Open to enable editing.
                            </div>
                        </div>
                    </div>
                    <!-- Category Tabs -->
                    <div class="flex gap-2 mb-3">
                        <button type="button" data-tab="bakery" onclick="switchTab('bakery')"
                            class="tab-btn flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-all border-2 border-primary text-white bg-primary shadow-md cursor-pointer">
                            <i class="fas fa-bread-slice mr-1.5 hidden sm:inline"></i> Bakery
                        </button>
                        <button type="button" data-tab="drinks" onclick="switchTab('drinks')"
                            class="tab-btn flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-all border-2 border-gray-300 text-gray-700 bg-gray-100 hover:bg-gray-200 hover:border-gray-400 cursor-pointer">
                            <i class="fas fa-mug-hot mr-1.5 hidden sm:inline"></i> Drinks
                        </button>
                        <button type="button" data-tab="grocery" onclick="switchTab('grocery')"
                            class="tab-btn flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-all border-2 border-gray-300 text-gray-700 bg-gray-100 hover:bg-gray-200 hover:border-gray-400 cursor-pointer">
                            <i class="fas fa-shopping-basket mr-1.5 hidden sm:inline"></i> Grocery
                        </button>
                    </div>

                    <!-- Tab Content: Bakery -->
                    <div id="bakery-content" class="tab-content mb-20 sm:mb-0">
                        <!-- Mobile Card View -->
                        <div class="sm:hidden">
                            <div id="bakeryMobileCards" class="space-y-3">
                                <!-- Cards will be loaded via AJAX -->
                            </div>
                        </div>
                        <!-- Desktop Table View -->
                        <div class="hidden sm:block">
                            <div class="bg-white rounded border border-gray-200 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table id="bakeryTable" class="min-w-full text-sm text-left">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">
                                                    Items/Particulars
                                                </th>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">SRP</th>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">Beginning
                                                </th>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">Pull Out
                                                </th>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">Qty Sold
                                                </th>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">Ending</th>
                                                <?php if ($isOwnerView): ?>
                                                    <th scope="col" class="px-6 py-3 font-medium text-gray-600">Overhead
                                                    </th>
                                                <?php endif; ?>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">Sales</th>
                                                <?php if ($isOwnerView): ?>
                                                    <th scope="col" class="px-6 py-3 font-medium text-gray-600">Materials
                                                        Used</th>
                                                <?php endif; ?>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bakeryTableBody">
                                            <!-- Data will be loaded via AJAX -->
                                        </tbody>
                                        <tfoot class="bg-gray-50 border-t border-gray-200">
                                            <tr>
                                                <td colspan="5"
                                                    class="px-6 py-2 text-right text-xs text-gray-500 font-medium">
                                                    Total:</td>
                                                <td class="px-6 py-2 text-sm font-medium text-gray-700"
                                                    id="bakeryTotalQty">0
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content: Drinks -->
                    <div id="drinks-content" class="tab-content hidden mb-20 sm:mb-0">
                        <!-- Mobile Card View -->
                        <div class="sm:hidden">
                            <div id="drinksMobileCards" class="space-y-3">
                                <!-- Cards will be loaded via AJAX -->
                            </div>
                        </div>
                        <!-- Desktop Table View -->
                        <div class="hidden sm:block">
                            <div class="bg-white rounded border border-gray-200 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table id="drinksTable" class="min-w-full text-sm text-left">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">
                                                    Items/Particulars
                                                </th>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">SRP</th>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">Qty Sold
                                                </th>
                                                <?php if ($isOwnerView): ?>
                                                    <th scope="col" class="px-6 py-3 font-medium text-gray-600">Overhead
                                                    </th>
                                                <?php endif; ?>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">Sales</th>
                                                <?php if ($isOwnerView): ?>
                                                    <th scope="col" class="px-6 py-3 font-medium text-gray-600">Materials
                                                        Used</th>
                                                <?php endif; ?>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="drinksTableBody">
                                            <!-- Data will be loaded via AJAX -->
                                        </tbody>
                                        <tfoot class="bg-gray-50 border-t border-gray-200">
                                            <tr>
                                                <td colspan="2"
                                                    class="px-6 py-2 text-right text-xs text-gray-500 font-medium">
                                                    Total:</td>
                                                <td class="px-6 py-2 text-sm font-medium text-gray-700"
                                                    id="drinksTotalQty">0
                                                </td>
                                                <td></td>
                                                <td class="px-6 py-2 text-sm font-medium text-gray-700"
                                                    id="drinksTotalSales">₱0.00
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content: Grocery -->
                    <div id="grocery-content" class="tab-content hidden mb-20 sm:mb-0">
                        <!-- Mobile Card View -->
                        <div class="sm:hidden">
                            <div id="groceryMobileCards" class="space-y-3">
                                <!-- Cards will be loaded via AJAX -->
                            </div>
                        </div>
                        <!-- Desktop Table View -->
                        <div class="hidden sm:block">
                            <div class="bg-white rounded border border-gray-200 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table id="groceryTable" class="min-w-full text-sm text-left">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">
                                                    Items/Particulars
                                                </th>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">SRP</th>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">Beginning
                                                </th>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">Pull Out
                                                </th>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">Qty Sold
                                                </th>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">Ending</th>
                                                <?php if ($isOwnerView): ?>
                                                    <th scope="col" class="px-6 py-3 font-medium text-gray-600">Overhead
                                                    </th>
                                                <?php endif; ?>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">Sales</th>
                                                <?php if ($isOwnerView): ?>
                                                    <th scope="col" class="px-6 py-3 font-medium text-gray-600">Materials
                                                        Used</th>
                                                <?php endif; ?>
                                                <th scope="col" class="px-6 py-3 font-medium text-gray-600">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="groceryTableBody">
                                            <!-- Data will be loaded via AJAX -->
                                        </tbody>
                                        <tfoot class="bg-gray-50 border-t border-gray-200">
                                            <tr>
                                                <td colspan="5"
                                                    class="px-6 py-2 text-right text-xs text-gray-500 font-medium">
                                                    Total:</td>
                                                <td class="px-6 py-2 text-sm font-medium text-gray-700"
                                                    id="groceryTotalQty">0
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="inventoryDisabledOverlay"
                        class="fixed inset-0 bg-gray-200 bg-opacity-60 z-[100] flex items-center justify-center hidden"
                        style="pointer-events: all;">
                        <div class="text-center">
                            <i class="fas fa-lock text-4xl text-gray-500 mb-4"></i>
                            <div class="text-lg font-semibold text-gray-700">Inventory is closed</div>
                            <div class="text-sm text-gray-500">You cannot make changes until it is reopened.</div>
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-4 min-w-0">
                    <div id="todayDistributionPanel" class="p-4 bg-white rounded-lg shadow-md">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Today's Distribution</h3>
                                <p class="text-xs text-gray-500">Groups, notes, and total cost</p>
                            </div>
                            <button id="btnRefreshTodayDistribution" type="button"
                                class="inline-flex items-center rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors">
                                <i class="fas fa-rotate-right mr-1.5"></i> Refresh
                            </button>
                        </div>

                        <div id="todayDistributionLoading" class="hidden py-8 text-center text-gray-400">
                            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                            <p class="text-sm">Loading today's distribution...</p>
                        </div>

                        <div id="todayDistributionEmpty" class="hidden py-8 text-center text-gray-400">
                            <i class="fas fa-box-open text-3xl mb-2"></i>
                            <p id="todayDistributionEmptyText" class="text-sm">No distribution records for today.</p>
                        </div>

                        <div id="todayDistributionContent" class="hidden space-y-3">
                            <div class="grid grid-cols-2 gap-2">
                                <div class="p-2.5 bg-gray-50 border border-gray-200 rounded-lg">
                                    <p class="text-[11px] text-gray-500">Items</p>
                                    <p id="todayDistSummaryItems" class="text-base font-semibold text-gray-800">0</p>
                                </div>
                                <div class="p-2.5 bg-gray-50 border border-gray-200 rounded-lg">
                                    <p class="text-[11px] text-gray-500">Groups</p>
                                    <p id="todayDistSummaryGroups" class="text-base font-semibold text-gray-800">0</p>
                                </div>
                                <div class="p-2.5 bg-gray-50 border border-gray-200 rounded-lg">
                                    <p class="text-[11px] text-gray-500">Batches</p>
                                    <p id="todayDistSummaryBatches" class="text-base font-semibold text-gray-800">0</p>
                                </div>
                                <div class="p-2.5 bg-gray-50 border border-gray-200 rounded-lg">
                                    <p class="text-[11px] text-gray-500">Pieces</p>
                                    <p id="todayDistSummaryPieces" class="text-base font-semibold text-gray-800">0</p>
                                </div>
                            </div>

                            <div id="todayDistributionSlideViewport"
                                class="relative overflow-hidden border border-gray-200 rounded-lg bg-white">
                                <div id="todayDistributionSlideTrack"
                                    class="flex transition-transform duration-300 ease-in-out"
                                    style="width: 200%; transform: translateX(0);">
                                    <div class="flex-shrink-0 p-3" style="width: 50%;">
                                        <p class="text-xs font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-layer-group mr-1 text-primary"></i> Distribution Groups
                                        </p>
                                        <div id="todayDistributionGroupList"
                                            class="space-y-2 max-h-[340px] overflow-y-auto"></div>
                                    </div>

                                    <div class="flex-shrink-0 p-3 border-l border-gray-100" style="width: 50%;">
                                        <div class="mb-2">
                                            <button type="button" id="btnTodayDistBackToGroups"
                                                class="inline-flex items-center text-xs font-medium text-primary hover:text-secondary">
                                                <i class="fas fa-arrow-left mr-1"></i>Back to groups
                                            </button>
                                        </div>

                                        <div class="mb-2.5">
                                            <p id="todayDistSelectedGroupName"
                                                class="text-sm font-semibold text-gray-800">Selected Group</p>
                                            <p id="todayDistSelectedGroupMeta" class="text-[11px] text-gray-500">0
                                                batches • 0 pcs</p>
                                            <p id="todayDistSelectedGroupNote"
                                                class="hidden text-[11px] text-amber-700 mt-1"></p>
                                        </div>

                                        <div id="todayDistributionGroupItems"
                                            class="space-y-2 max-h-[300px] overflow-y-auto"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Input Modal -->
            <div id="timeInputModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4">
                <div class="fixed inset-0 bg-black/40" id="timeInputModalBackdrop"></div>
                <div class="relative bg-white rounded-xl shadow-xl max-w-sm w-full p-6 z-10">
                    <button type="button" id="timeInputModalClose"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Create Today's Inventory</h3>
                    <p class="text-xs text-gray-500 mb-2">Create inventory now and start tracking this shift.</p>

                    <!-- Inventory Source Badge -->
                    <div id="inventoryModeBadge"
                        class="hidden mb-3 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg flex items-center gap-2">
                        <i class="fas fa-truck-loading text-blue-500 text-xs"></i>
                        <span class="text-xs font-medium text-blue-700">Using today's distribution data</span>
                    </div>
                    <div id="noDistributionModeBadge"
                        class="hidden mb-3 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg flex items-center gap-2">
                        <i class="fas fa-boxes text-gray-400 text-xs"></i>
                        <span class="text-xs text-gray-500">Using all active products (no distribution today)</span>
                    </div>

                    <!-- Carryover Preview -->
                    <div id="carryoverPreview" class="hidden mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-boxes-stacked text-amber-600"></i>
                            <span class="text-sm font-semibold text-amber-700">Yesterday's Remaining Stock</span>
                        </div>
                        <div id="carryoverList" class="space-y-1 text-xs text-gray-700 max-h-32 overflow-y-auto"></div>
                        <p class="text-xs text-amber-600 mt-2"><i class="fas fa-info-circle mr-1"></i>These will be
                            automatically added to today's beginning stock.</p>
                    </div>
                    <div id="noCarryoverPreview" class="hidden mb-4 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-box-open text-gray-400"></i>
                            <span class="text-xs text-gray-500">No remaining stock from previous day.</span>
                        </div>
                    </div>

                    <form id="timeInputForm">
                        <div class="mb-6 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                            <p class="text-xs text-gray-600">
                                <i class="fas fa-clock mr-1"></i>
                                Start time will be recorded automatically when you create inventory.
                            </p>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit"
                                class="flex-1 text-white bg-primary hover:bg-primary/90 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                                Create Inventory
                            </button>
                            <button type="button" id="timeInputModalCancel"
                                class="flex-1 text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Inventory Modal -->
    <div id="editInventoryModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40" id="editInventoryModalBackdrop"></div>
        <div
            class="relative bg-white rounded-xl shadow-xl max-w-md w-full max-h-[85vh] overflow-hidden flex flex-col p-6 z-10">
            <button type="button" id="editInventoryModalClose"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Edit Inventory Item</h3>
            <p class="text-sm text-gray-500 mb-5">Product: <span id="editProductName"
                    class="font-medium text-gray-700"></span></p>
            <div id="editAdjustmentGuide"
                class="hidden mb-4 p-2.5 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-600">
                <strong>Adjustment Guide:</strong> Use <strong>+</strong> to add and <strong>-</strong> to subtract.
                <strong>Pull Out</strong> accepts positive values only.
            </div>

            <form id="editInventoryForm" class="flex-1 overflow-y-auto pr-1">
                <input type="hidden" id="editItemId" name="item_id">
                <input type="hidden" id="editDistributionQty" value="0">
                <input type="hidden" id="editCarryoverQty" value="0">
                <input type="hidden" id="editCategory" value="">
                <input type="hidden" id="editAdjustmentMode" value="0">
                <input type="hidden" id="editOldBeginningStock" value="0">
                <input type="hidden" id="editOldPullOutQuantity" value="0">
                <input type="hidden" id="editOldEndingStock" value="0">
                <input type="hidden" id="editOldQuantitySold" value="0">

                <div class="mb-4">
                    <label for="editBeginningStock" id="editBeginningLabel"
                        class="block mb-1.5 text-sm font-medium text-gray-700">Beginning
                        Stock</label>
                    <div class="flex items-center gap-2">
                        <button type="button" id="btnDecreaseBeginning"
                            class="flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 hover:bg-gray-100 hover:border-gray-400 transition-all text-lg font-bold select-none">
                            &minus;
                        </button>
                        <input type="number" id="editBeginningStock" name="beginning_stock" required min="0" step="1"
                            class="flex-1 rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-center focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <button type="button" id="btnIncreaseBeginning"
                            class="flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 hover:bg-gray-100 hover:border-gray-400 transition-all text-lg font-bold select-none">
                            +
                        </button>
                    </div>
                    <p id="editBeginningHint" class="text-xs text-gray-400 mt-1"></p>

                    <!-- Distribution Limit Info Bar -->
                    <div id="editDistributionInfo"
                        class="hidden mt-2 p-2.5 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center gap-1.5 text-xs text-blue-700">
                            <i class="fas fa-info-circle"></i>
                            <span id="editDistInfoText"></span>
                        </div>
                    </div>

                    <!-- Over/Under Warning -->
                    <div id="editStockWarning" class="hidden mt-2 p-2.5 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="flex items-center gap-1.5 text-xs text-amber-700">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span id="editStockWarningText"></span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="editPullOutQuantity" id="editPullOutLabel"
                        class="block mb-1.5 text-sm font-medium text-gray-700">Pull Out
                        Quantity</label>
                    <div class="flex items-center gap-2">
                        <button type="button" id="btnDecreasePullOut"
                            class="flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 hover:bg-gray-100 hover:border-gray-400 transition-all text-lg font-bold select-none">
                            &minus;
                        </button>
                        <input type="number" id="editPullOutQuantity" name="pull_out_quantity" required min="0" step="1"
                            class="flex-1 rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-center focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <button type="button" id="btnIncreasePullOut"
                            class="flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 hover:bg-gray-100 hover:border-gray-400 transition-all text-lg font-bold select-none">
                            +
                        </button>
                    </div>
                    <p id="editPullOutHint" class="text-xs text-gray-400 mt-1"></p>
                </div>

                <div class="mb-4 hidden" id="editEndingGroup">
                    <label for="editEndingStock" id="editEndingLabel"
                        class="block mb-1.5 text-sm font-medium text-gray-700">Ending
                        Stock</label>
                    <input type="number" id="editEndingStock" name="ending_stock" step="1" min="0"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <p id="editEndingHint" class="text-xs text-gray-400 mt-1">Enter the actual final ending stock count.
                    </p>
                </div>

                <div class="mb-4">
                    <label for="editRemainingPreview" class="block mb-1.5 text-sm font-medium text-gray-700">Remaining
                        (Preview)</label>
                    <input type="number" id="editRemainingPreview" readonly
                        class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700">
                    <p id="editRemainingHint" class="text-xs text-gray-400 mt-1">Live preview while editing fields
                        above.</p>
                </div>

                <div class="mb-6">
                    <label for="editNotes" id="editNotesLabel"
                        class="block mb-1.5 text-sm font-medium text-gray-700">Notes</label>
                    <textarea id="editNotes" name="notes" rows="3" maxlength="500" placeholder="Add notes (optional)"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
                    <p id="editNotesHint" class="text-xs text-gray-400 mt-1">Optional — max 500 characters</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" id="btnSubmitEditInventory"
                        class="flex-1 text-white bg-primary hover:bg-primary/90 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                        Update Item
                    </button>
                    <button type="button" id="editInventoryModalCancel"
                        class="flex-1 text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Product to Inventory Modal -->
    <div id="addProductModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 " id="addProductModalBackdrop"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-sm w-full p-6 z-10">
            <button type="button" id="addProductModalClose"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
            <h3 class="text-lg font-semibold text-gray-900 mb-5">Add Product to Inventory</h3>

            <form id="addProductForm">
                <div class="mb-4">
                    <label for="selectProduct" class="block mb-1.5 text-sm font-medium text-gray-700">Select
                        Product</label>
                    <select id="selectProduct" name="product_id" required
                        class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-all">
                        <option value="">-- Select a product --</option>
                    </select>
                    <p id="noProductsMessage" class="hidden mt-2 text-xs text-gray-500">All products are already in
                        inventory.</p>
                </div>

                <div class="mb-6">
                    <label for="addBeginningStock" class="block mb-1.5 text-sm font-medium text-gray-700">Beginning
                        Stock</label>
                    <input type="number" id="addBeginningStock" name="beginning_stock" min="0" value="0" step="1"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <p class="text-xs text-gray-400 mt-1">Optional - defaults to 0</p>
                </div>

                <!-- Deduction Preview -->
                <div id="deductionPreviewContainer" class="hidden mb-4">
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                        <p class="text-xs font-semibold text-amber-700 mb-2">
                            <i class="fas fa-flask mr-1"></i> Raw Materials to be Deducted
                        </p>
                        <div id="deductionPreviewList" class="space-y-1 text-xs text-gray-700 max-h-32 overflow-y-auto">
                        </div>
                        <div id="deductionPreviewWarning" class="hidden mt-2 text-xs text-red-600 font-medium">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Some materials have insufficient stock
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" id="btnSubmitAddProduct"
                        class="flex-1 text-white bg-primary hover:bg-primary/90 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                        Add to Inventory
                    </button>
                    <button type="button" id="addProductModalCancel"
                        class="flex-1 text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50" id="deleteConfirmModalBackdrop"></div>
        <div class="relative bg-white rounded-lg shadow-lg max-w-md w-full p-6 z-10">
            <button type="button" id="deleteConfirmModalClose"
                class="absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                <i class="fas fa-xmark"></i>
            </button>
            <div class="text-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Delete Today's Inventory?</h3>
                <p class="text-gray-600 mb-6">Are you sure you want to delete today's entire inventory? This action
                    cannot be undone.</p>
            </div>
            <div class="flex gap-3">
                <button type="button" id="btnConfirmDelete"
                    class="flex-1 text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-400 font-medium rounded-lg text-sm px-5 py-2.5">
                    Delete
                </button>
                <button type="button" id="deleteConfirmModalCancel"
                    class="flex-1 text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <div id="sendReportConfirmModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50" id="sendReportConfirmModalBackdrop"></div>
        <div class="relative bg-white rounded-lg shadow-lg max-w-md w-full p-6 z-10">
            <button type="button" id="sendReportConfirmModalClose"
                class="absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                <i class="fas fa-xmark"></i>
            </button>
            <div class="text-center">
                <i class="fas fa-paper-plane text-indigo-600 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Send Inventory Report?</h3>
                <p class="text-gray-600 mb-2">Send the current auto-generated inventory report now.</p>
                <p class="text-sm text-gray-500 mb-6">This may take a few moments while the report is prepared and
                    emailed.</p>
            </div>
            <div class="flex gap-3">
                <button type="button" id="btnConfirmSendInventoryReport"
                    class="flex-1 text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5">
                    Send Report
                </button>
                <button type="button" id="sendReportConfirmModalCancel"
                    class="flex-1 text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        // Track if inventory exists for today
        let inventoryExistsToday = false;
        let inventoryIsClosed = false;
        let inventoryReportSent = false;

        function isInventoryInteractionBlocked() {
            return inventoryExistsToday && inventoryIsClosed;
        }

        function enforceInventoryLock() {
            if (isInventoryInteractionBlocked()) {
                showToast('warning', 'Inventory is closed. Click Open to continue.', 2200);
                return true;
            }
            return false;
        }

        function syncNewShiftButtonState() {
            const shouldDisable = inventoryExistsToday && (!inventoryIsClosed || !inventoryReportSent);
            const $btn = $('#btnResetInventoryForNextShift');

            $btn
                .prop('disabled', shouldDisable)
                .attr('aria-disabled', shouldDisable ? 'true' : 'false')
                .toggleClass('opacity-50 cursor-not-allowed pointer-events-none', shouldDisable);

            if (shouldDisable) {
                if (!inventoryIsClosed) {
                    $btn.attr('title', 'Close inventory first before creating a new inventory.');
                } else if (!inventoryReportSent) {
                    $btn.attr('title', 'Send inventory report first before creating a new inventory.');
                }
            } else {
                $btn.removeAttr('title');
            }
        }

        function syncInventoryInteractionLock() {
            const blocked = isInventoryInteractionBlocked();
            $('#inventoryLockOverlay').toggleClass('hidden', !blocked);

            const lockButtons = '#btnAddProductToInventory, #btnAddProductToInventoryMobile, #btnDeleteTodaysInventory';
            $(lockButtons)
                .prop('disabled', blocked)
                .toggleClass('opacity-50 cursor-not-allowed', blocked);

            syncNewShiftButtonState();
        }

        $(document).ready(function () {
            syncInventoryInteractionLock();

            // Delete Modal Script
            $('#btnDeleteTodaysInventory').on('click', function () {
                if (!inventoryExistsToday) {
                    showToast('warning', 'No inventory exists for today to delete.', 2000);
                    return;
                }
                if (enforceInventoryLock()) {
                    return;
                }
                $('#deleteConfirmModal').removeClass('hidden');
            });

            // Close Delete Confirmation Modal
            $('#deleteConfirmModalClose, #deleteConfirmModalCancel').on('click', function () {
                $('#deleteConfirmModal').addClass('hidden');
            });

            // Confirm Delete
            $('#btnConfirmDelete').on('click', function () {
                $('#deleteConfirmModal').addClass('hidden');
                deleteInventory(); // This calls your function
            });

            // Distributions button click — open distribution list modal
            $('#btnDistributions').on('click', function () {
                if (!inventoryExistsToday) {
                    showToast('warning', 'Create inventory first before loading distribution data.', 2000);
                    return;
                }
                openDistributionModal();
            });

            // Close Distribution List Modal
            $('#distributionListModalClose, #distributionListModalDone').on('click', function () {
                $('#distributionListModal').addClass('hidden');
            });

            // Load All Remaining button
            $('#btnLoadAllRemaining').on('click', function () {
                loadAllRemainingDistribution();
            });

            // Close Load Single Item Modal
            $('#loadSingleItemClose, #loadSingleItemCancel').on('click', function () {
                $('#loadSingleItemModal').addClass('hidden');
                $('#loadSingleItemForm')[0].reset();
                resetLoadItemModalState();
            });

            // +/- buttons for load quantity
            $('#btnDecreaseLoadQty').on('click', function () {
                const current = parseInt($('#loadItemQuantity').val()) || 0;
                $('#loadItemQuantity').val(Math.max(1, current - 1));
                updateLoadQuantityDisplay();
            });

            $('#btnIncreaseLoadQty').on('click', function () {
                const current = parseInt($('#loadItemQuantity').val()) || 0;
                $('#loadItemQuantity').val(current + 1);
                updateLoadQuantityDisplay();
            });

            $('#loadItemQuantity').on('input change', function () {
                updateLoadQuantityDisplay();
            });

            // Update note border on input
            $('#loadItemNote').on('input', function () {
                if ($('#loadItemNote').is('[required]') && !$(this).val().trim()) {
                    $(this).addClass('border-red-300 focus:border-red-400 focus:ring-red-200');
                } else {
                    $(this).removeClass('border-red-300 focus:border-red-400 focus:ring-red-200');
                }
            });

            // Submit Load Single Item
            $('#loadSingleItemForm').on('submit', function (e) {
                e.preventDefault();
                submitLoadSingleItem();
            });

        }); // end $(document).ready()
    </script>

    <!-- Distribution Items List Modal -->
    <div id="distributionListModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40" id="distributionListModalBackdrop"></div>
        <div
            class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-hidden flex flex-col z-10">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-truck-loading mr-2 text-primary"></i>Today's Distribution Items
                </h3>
                <button type="button" id="distributionListModalClose"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="px-6 py-4 overflow-y-auto flex-1" id="distributionListContent">
                <div class="text-center text-gray-400 py-8">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                    <p class="text-sm">Loading distribution items...</p>
                </div>
            </div>
            <div class="px-6 py-3 border-t border-gray-200 flex justify-between items-center">
                <button type="button" id="btnLoadAllRemaining"
                    class="text-sm text-primary hover:text-secondary font-medium transition-colors">
                    <i class="fas fa-download mr-1"></i> Load All Remaining
                </button>
                <button type="button" id="distributionListModalDone"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-300 transition-colors">
                    Done
                </button>
            </div>
        </div>
    </div>

    <!-- Load Single Distribution Item Modal -->
    <div id="loadSingleItemModal" class="hidden fixed inset-0 z-[10000] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40" id="loadSingleItemBackdrop"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-sm w-full p-6 z-10">
            <button type="button" id="loadSingleItemClose"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Load Distribution Item</h3>
            <p class="text-sm text-gray-500 mb-4">Product: <span id="loadItemProductName"
                    class="font-medium text-gray-700"></span></p>

            <form id="loadSingleItemForm">
                <input type="hidden" id="loadItemProductId" value="0">
                <input type="hidden" id="loadItemExpectedPieces" value="0">
                <input type="hidden" id="loadItemAlreadyLoaded" value="0">

                <!-- Distribution Info Bar -->
                <div id="loadItemDistInfo" class="mb-4 p-2.5 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs text-blue-700" id="loadItemDistInfoText"></p>
                </div>

                <div class="mb-4">
                    <label for="loadItemQuantity" class="block mb-1.5 text-sm font-medium text-gray-700">Quantity
                        (pcs)</label>
                    <div class="flex items-center gap-2">
                        <button type="button" id="btnDecreaseLoadQty"
                            class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-minus text-xs"></i>
                        </button>
                        <input type="number" id="loadItemQuantity" name="quantity" required min="1" step="1"
                            class="flex-1 rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-center focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <button type="button" id="btnIncreaseLoadQty"
                            class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Deviation Warning -->
                <div id="loadItemDeviationWarning"
                    class="hidden mb-4 p-2.5 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-xs text-amber-700 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-1.5"></i>
                        <span id="loadItemDeviationText"></span>
                    </p>
                </div>

                <div class="mb-5">
                    <label for="loadItemNote" id="loadItemNoteLabel"
                        class="block mb-1.5 text-sm font-medium text-gray-700">Note</label>
                    <textarea id="loadItemNote" name="note" rows="3" maxlength="500" placeholder="Add note (optional)"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
                    <p id="loadItemNoteHint" class="text-xs text-gray-400 mt-1">Optional — max 500 characters</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 rounded-lg bg-primary px-4 py-2.5 text-sm font-medium text-white hover:bg-secondary focus:ring-2 focus:ring-primary/40 transition-colors">
                        <i class="fas fa-check mr-1.5"></i> Confirm Load
                    </button>
                    <button type="button" id="loadSingleItemCancel"
                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Item Details Modal -->
    <div id="itemDetailsModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40" id="itemDetailsModalBackdrop"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto z-10">
            <div class="sticky top-0 bg-gradient-to-r from-primary to-secondary p-6 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white" id="itemDetailsProductName">Product Details</h3>
                <button type="button" id="itemDetailsModalClose" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <!-- Product Notes -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-sticky-note text-blue-600 mr-2"></i> Notes
                    </h4>
                    <p id="itemDetailsNotes" class="text-sm text-gray-600 whitespace-pre-wrap">—</p>
                </div>

                <!-- Shift Production Summary -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-dolly text-blue-600 mr-2"></i> Shift Production Summary
                    </h4>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-white rounded p-3 border border-blue-100">
                            <p class="text-xs text-gray-500 font-medium mb-1">Pull-Out (PO)</p>
                            <p id="itemDetailsPO" class="text-lg font-bold text-gray-800">0</p>
                        </div>
                        <div class="bg-white rounded p-3 border border-blue-100">
                            <p class="text-xs text-gray-500 font-medium mb-1">Qty Sold</p>
                            <p id="itemDetailsQtySold" class="text-lg font-bold text-gray-800">0</p>
                        </div>
                        <div class="bg-white rounded p-3 border border-green-100">
                            <p class="text-xs text-gray-500 font-medium mb-1">Total Units</p>
                            <p id="itemDetailsTotalUnits" class="text-lg font-bold text-green-600">0</p>
                        </div>
                    </div>
                </div>

                <!-- Sales Information -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-shopping-cart text-green-600 mr-2"></i> Sales Performance
                    </h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-600">SRP per Unit:</p>
                            <p id="itemDetailsSRP" class="text-sm font-semibold text-gray-800">₱0.00</p>
                        </div>
                        <div class="flex items-center justify-between bg-white rounded p-3 border border-green-100">
                            <p class="text-sm font-medium text-gray-700">Total Sales Revenue:</p>
                            <p id="itemDetailsTotalSales" class="text-lg font-bold text-green-600">₱0.00</p>
                        </div>
                    </div>
                </div>

                <!-- Raw Materials Used Section -->
                <div class="bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-flask text-orange-600 mr-2"></i> Raw Materials Used
                    </h4>
                    <p class="text-xs text-gray-500 mb-3 italic">Based on <strong>Total Units (PO + Qty Sold)</strong>
                    </p>
                    <div id="itemDetailsMaterialsList" class="space-y-2">
                        <p class="text-sm text-gray-500 text-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i>
                            Loading materials...</p>
                    </div>
                </div>

                <!-- Cost Analysis & Shift Performance -->
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-chart-bar text-purple-600 mr-2"></i> Shift Performance Analysis
                    </h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between bg-white rounded p-3 border border-purple-100">
                            <p class="text-sm font-medium text-gray-700">Total Raw Materials Cost:</p>
                            <p id="itemDetailsTotalMaterialsCost" class="text-lg font-bold text-red-600">₱0.00</p>
                        </div>
                        <div class="flex items-center justify-between bg-white rounded p-3 border-2 border-purple-200">
                            <p class="text-sm font-semibold text-gray-800">Profit/Loss:</p>
                            <p id="itemDetailsProfit" class="text-lg font-bold text-purple-600">₱0.00</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-gray-500">Profit Margin:</p>
                            <p id="itemDetailsProfitMargin" class="text-sm font-semibold text-gray-800">0%</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 border-t border-gray-200 p-4 flex gap-2 sticky bottom-0">
                <button type="button" id="itemDetailsModalCancel"
                    class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    <style>
        .inventory-item-row {
            transition: transform 220ms ease, opacity 220ms ease, background-color 220ms ease;
            will-change: transform;
        }

        @media (max-width: 640px) {

            .datatable-top,
            .datatable-bottom {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                gap: 0.3rem !important;
                padding: 0.3rem 0;
            }

            .datatable-dropdown,
            .datatable-search,
            .datatable-info,
            .datatable-pagination {
                float: none !important;
                width: 100% !important;
                text-align: center !important;
                display: flex !important;
                justify-content: center !important;
                margin: 0 !important;
            }

            .datatable-search {
                margin-top: 0.5rem !important;
            }

            .datatable-pagination ul {
                justify-content: center !important;
            }
        }
    </style>

    <script>
        // Track which source to use for inventory: 'all' or 'distribution'
        let inventorySource = 'all';
        const inventoryBaseUrl = '<?= base_url() ?>';
        let todayDistProductMap = {};
        let todayDistProductDetailCache = {};
        let todayDistProductDetailPromiseCache = {};
        let todayDistHydrationToken = 0;
        let todayDistributionGroupedData = [];
        let inventoryId = null;

        function getTodayDateForApi() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function parseInventoryNumericValue(value) {
            if (value === null || value === undefined || value === '') return 0;
            if (typeof value === 'number') return Number.isFinite(value) ? value : 0;

            const cleaned = String(value).replace(/[^0-9.-]/g, '');
            const parsed = parseFloat(cleaned);
            return Number.isFinite(parsed) ? parsed : 0;
        }

        function formatInventoryPeso(amount) {
            const numeric = parseInventoryNumericValue(amount);
            return '₱' + numeric.toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function formatInventoryNumber(value, maxFractionDigits = 0) {
            const numeric = parseInventoryNumericValue(value);
            return numeric.toLocaleString('en-PH', {
                minimumFractionDigits: 0,
                maximumFractionDigits: maxFractionDigits
            });
        }

        function escapeInventoryHtml(value) {
            const text = value == null ? '' : String(value);
            return text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function getTodayDistProductData(productId) {
            const key = String(productId || '').trim();
            if (!key) return null;
            return todayDistProductMap[key] || todayDistProductDetailCache[key] || null;
        }

        function getTodayDistPiecesPerYield(product) {
            const pieces = parseInventoryNumericValue(product && product.pieces_per_yield);
            return pieces > 0 ? pieces : 1;
        }

        function getTodayDistTraysPerYield(product) {
            const trays = parseInventoryNumericValue(product && product.trays_per_yield);
            return trays > 0 ? trays : 0;
        }

        function getTodayDistBatchPiecesPerYield(product) {
            const traysPerYield = getTodayDistTraysPerYield(product);
            const piecesPerYield = getTodayDistPiecesPerYield(product);

            return traysPerYield > 0 ? traysPerYield * piecesPerYield : piecesPerYield;
        }

        function getTodayDistBoxPieces(product) {
            return getTodayDistPiecesPerYield(product);
        }

        function getTodayDistPieces(item, product) {
            const qty = parseInventoryNumericValue(item && item.product_qnty);
            const qtyMode = ((item && item.qty_mode) || 'batch').toString().toLowerCase();
            const category = (((product && product.category) || (item && item.category) || '') + '').toLowerCase();

            if (qtyMode === 'pieces') {
                return qty;
            }

            if (category === 'drinks' || category === 'grocery') {
                return qty;
            }

            if (qtyMode === 'box') {
                return qty * getTodayDistBoxPieces(product || {});
            }

            return qty * getTodayDistBatchPiecesPerYield(product || {});
        }

        function getTodayDistYieldUnits(item, product) {
            const qty = parseInventoryNumericValue(item && item.product_qnty);
            const qtyMode = ((item && item.qty_mode) || 'batch').toString().toLowerCase();
            const category = (((product && product.category) || (item && item.category) || '') + '').toLowerCase();
            const traysPerYield = getTodayDistTraysPerYield(product || {});
            const batchPiecesPerYield = getTodayDistBatchPiecesPerYield(product || {});

            if (qtyMode === 'pieces') {
                return batchPiecesPerYield > 0 ? (qty / batchPiecesPerYield) : qty;
            }

            if (category === 'drinks' || category === 'grocery') {
                return qty;
            }

            if (qtyMode === 'box') {
                if (traysPerYield > 0) {
                    return qty / traysPerYield;
                }

                const pieces = getTodayDistPieces(item, product || {});
                return batchPiecesPerYield > 0 ? (pieces / batchPiecesPerYield) : qty;
            }

            return qty;
        }

        function calculateTodayDistItemDirectCost(item, product) {
            const productData = product || {};
            const directCostPerYield = parseInventoryNumericValue(
                productData.total_cost || productData.direct_cost
            );
            if (directCostPerYield <= 0) return 0;

            const yieldsNeeded = getTodayDistYieldUnits(item, productData);

            return yieldsNeeded > 0 ? (yieldsNeeded * directCostPerYield) : 0;
        }

        function getTodayDistMaterialAggregateKey(materialId, materialName, unit) {
            if (materialId != null && materialId !== '') {
                return `id-${materialId}-${(unit || '').toString().trim().toLowerCase()}`;
            }

            return `name-${(materialName || 'unknown').toString().trim().toLowerCase()}-${(unit || '').toString().trim().toLowerCase()}`;
        }

        function mergeTodayDistMaterialUsageEntry(materialMap, usageEntry) {
            if (!materialMap || !usageEntry) return;

            const mapKey = getTodayDistMaterialAggregateKey(
                usageEntry.material_id,
                usageEntry.material_name,
                usageEntry.unit
            );

            if (!materialMap[mapKey]) {
                materialMap[mapKey] = {
                    material_id: usageEntry.material_id,
                    material_name: usageEntry.material_name || 'Unknown Material',
                    unit: usageEntry.unit || '',
                    amount: 0,
                    line_cost: 0,
                };
            }

            materialMap[mapKey].amount += parseInventoryNumericValue(usageEntry.amount);
            materialMap[mapKey].line_cost += parseInventoryNumericValue(usageEntry.line_cost);
        }

        function todayDistMaterialMapToArray(materialMap) {
            return Object.values(materialMap || {}).sort(function (a, b) {
                return String(a.material_name || '').localeCompare(String(b.material_name || ''));
            });
        }

        function fetchTodayDistProducts(forceReload = false) {
            return new Promise(function (resolve) {
                if (!forceReload && Object.keys(todayDistProductMap).length > 0) {
                    resolve(todayDistProductMap);
                    return;
                }

                $.ajax({
                    url: inventoryBaseUrl + 'Products/GetAll',
                    method: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response && response.success && Array.isArray(response.data)) {
                            const nextMap = {};
                            response.data.forEach(function (product) {
                                const key = String(product.product_id || '').trim();
                                if (!key) return;
                                nextMap[key] = Object.assign({}, product);
                            });
                            todayDistProductMap = nextMap;
                        }
                    },
                    complete: function () {
                        resolve(todayDistProductMap);
                    }
                });
            });
        }

        function fetchTodayDistProductDetail(productId) {
            const key = String(productId || '').trim();
            if (!key) {
                return Promise.resolve(null);
            }

            if (todayDistProductDetailCache[key]) {
                return Promise.resolve(todayDistProductDetailCache[key]);
            }

            if (todayDistProductDetailPromiseCache[key]) {
                return todayDistProductDetailPromiseCache[key];
            }

            todayDistProductDetailPromiseCache[key] = new Promise(function (resolve) {
                $.ajax({
                    url: inventoryBaseUrl + 'Products/GetProduct/' + key,
                    method: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response && response.success && response.data) {
                            const productData = response.data;
                            todayDistProductDetailCache[key] = productData;
                            todayDistProductMap[key] = Object.assign({}, todayDistProductMap[key] || {},
                                productData);
                            resolve(productData);
                            return;
                        }

                        resolve(null);
                    },
                    error: function () {
                        resolve(null);
                    },
                    complete: function () {
                        delete todayDistProductDetailPromiseCache[key];
                    }
                });
            });

            return todayDistProductDetailPromiseCache[key];
        }

        function fetchTodayDistributionByDate(dateValue) {
            return new Promise(function (resolve) {
                $.ajax({
                    url: inventoryBaseUrl + 'Distribution/GetDistributionByDate',
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        date: dateValue
                    },
                    success: function (response) {
                        resolve(response || {
                            success: false,
                            data: []
                        });
                    },
                    error: function () {
                        resolve({
                            success: false,
                            data: []
                        });
                    }
                });
            });
        }

        function getTodayDistributionGroupNote(groupMeta, items = []) {
            const directCandidates = [
                groupMeta && groupMeta.distributed_to_note,
                groupMeta && groupMeta.distribution_group_note,
                groupMeta && groupMeta.overall_note,
                groupMeta && groupMeta.note,
                groupMeta && groupMeta.place_distributed_to,
                groupMeta && groupMeta.place_distributed,
            ];

            for (const candidate of directCandidates) {
                const note = (candidate || '').toString().trim();
                if (note) return note;
            }

            const itemNoteFields = [
                'distribution_group_note',
                'distributed_to_note',
                'overall_note',
                'note',
                'item_note',
                'place_distributed_to',
                'place_distributed',
            ];

            for (const item of (Array.isArray(items) ? items : [])) {
                for (const key of itemNoteFields) {
                    const note = (item && item[key] != null) ? String(item[key]).trim() : '';
                    if (note) return note;
                }
            }

            return '';
        }

        function normalizeTodayDistributionGroups(apiData) {
            const source = Array.isArray(apiData) ? apiData : [];
            const normalizedGroups = [];
            const fallbackGroupMap = {};

            source.forEach(function (entry, entryIndex) {
                if (!entry || typeof entry !== 'object') {
                    return;
                }

                const groupItems = Array.isArray(entry.items) ? entry.items : null;

                if (groupItems) {
                    const explicitGroupKey = (entry.distribution_group_key || entry.group_key || '').toString()
                        .trim();
                    const defaultGroupKey = explicitGroupKey || ((entry.id != null && entry.id !== '') ?
                        ('group-' + String(entry.id).trim()) :
                        ('group-' + (entryIndex + 1)));

                    const groupName = (entry.title || entry.distribution_group_name || entry.group_name || (
                        'Group ' + (entryIndex + 1))).toString().trim() || ('Group ' + (entryIndex + 1));
                    const groupNote = getTodayDistributionGroupNote(entry, groupItems);

                    const normalizedItems = groupItems
                        .filter(function (groupItem) {
                            return groupItem && typeof groupItem === 'object';
                        })
                        .map(function (groupItem) {
                            return Object.assign({}, groupItem, {
                                distribution_date: groupItem.distribution_date || entry
                                    .distribution_date || null,
                                distribution_group_key: (groupItem.distribution_group_key ||
                                    defaultGroupKey).toString(),
                                distribution_group_name: (groupItem.distribution_group_name ||
                                    groupName).toString(),
                                distribution_group_note: (groupItem.distribution_group_note ||
                                    groupNote).toString(),
                            });
                        });

                    if (normalizedItems.length === 0) {
                        return;
                    }

                    normalizedGroups.push({
                        group_key: defaultGroupKey,
                        group_name: groupName,
                        group_note: groupNote,
                        distribution_date: entry.distribution_date || null,
                        items: normalizedItems,
                    });

                    return;
                }

                if (!(entry.product_id || entry.product_qnty !== undefined)) {
                    return;
                }

                const fallbackGroupKey = ((entry.distribution_group_key || '').toString().trim()) || 'legacy-group';
                const fallbackGroupName = ((entry.distribution_group_name || entry.group_name || 'Default Group')
                    .toString().trim()) || 'Default Group';
                const fallbackGroupNote = getTodayDistributionGroupNote(entry, [entry]);

                if (!fallbackGroupMap[fallbackGroupKey]) {
                    fallbackGroupMap[fallbackGroupKey] = {
                        group_key: fallbackGroupKey,
                        group_name: fallbackGroupName,
                        group_note: fallbackGroupNote,
                        distribution_date: entry.distribution_date || null,
                        items: [],
                    };
                }

                fallbackGroupMap[fallbackGroupKey].items.push(Object.assign({}, entry, {
                    distribution_group_key: fallbackGroupKey,
                    distribution_group_name: fallbackGroupName,
                    distribution_group_note: fallbackGroupNote,
                }));
            });

            Object.values(fallbackGroupMap).forEach(function (group) {
                if (Array.isArray(group.items) && group.items.length > 0) {
                    normalizedGroups.push(group);
                }
            });

            return normalizedGroups;
        }

        function flattenTodayDistributionGroupItems(groups) {
            return (Array.isArray(groups) ? groups : []).reduce(function (accumulator, group) {
                const items = Array.isArray(group && group.items) ? group.items : [];
                return accumulator.concat(items);
            }, []);
        }

        function getTodayDistributionPiecesForProduct(productId) {
            const normalizedId = String(productId ?? '').trim();
            if (!normalizedId) return 0;

            return (Array.isArray(todayDistributionGroupedData) ? todayDistributionGroupedData : []).reduce(function (sum,
                group) {
                const items = Array.isArray(group && group.items) ? group.items : [];
                const groupTotal = items.reduce(function (itemSum, item) {
                    if (String(item && item.product_id) !== normalizedId) {
                        return itemSum;
                    }

                    const pieces = parseInventoryNumericValue(item && item.pieces_calculated);
                    if (pieces > 0) {
                        return itemSum + pieces;
                    }

                    const productData = getTodayDistProductData(item && item.product_id) || item || {};
                    return itemSum + getTodayDistPieces(item, productData);
                }, 0);

                return sum + groupTotal;
            }, 0);
        }

        async function accumulateTodayDistRawMaterialUsage(productId, yieldsNeeded, piecesNeeded, materialMap,
            visitedProducts = new Set(), productHint = null) {
            const key = String(productId || '').trim();
            if (!key || yieldsNeeded <= 0 || visitedProducts.has(key)) return;

            const productData = productHint || await fetchTodayDistProductDetail(key);
            if (!productData) return;

            const nextVisited = new Set(visitedProducts);
            nextVisited.add(key);

            const ingredients = Array.isArray(productData.ingredients) ? productData.ingredients : [];
            ingredients.forEach(function (ingredient) {
                const quantityPerYield = parseInventoryNumericValue(ingredient.quantity ?? ingredient
                    .quantity_needed);
                if (quantityPerYield <= 0) return;

                const amount = quantityPerYield * yieldsNeeded;
                const lineCost = amount * parseInventoryNumericValue(ingredient.cost_per_unit);

                mergeTodayDistMaterialUsageEntry(materialMap, {
                    material_id: ingredient.material_id,
                    material_name: ingredient.material_name || ('Material #' + (ingredient
                        .material_id ?? 'N/A')),
                    unit: ingredient.unit || '',
                    amount: amount,
                    line_cost: lineCost,
                });
            });

            const combinedRecipes = Array.isArray(productData.combined_recipes) ? productData.combined_recipes : [];

            for (const combinedRecipe of combinedRecipes) {
                const sourceProductId = parseInventoryNumericValue(combinedRecipe.source_product_id || combinedRecipe
                    .id);
                if (!sourceProductId) continue;

                const gramsPerPiece = parseInventoryNumericValue(combinedRecipe.grams_per_piece ?? combinedRecipe
                    .gramsPerPiece);
                if (gramsPerPiece <= 0 || piecesNeeded <= 0) continue;

                const sourceProduct = await fetchTodayDistProductDetail(sourceProductId);
                if (!sourceProduct) continue;

                const sourceYieldGrams = parseInventoryNumericValue(sourceProduct.yield_grams || combinedRecipe
                    .source_yield_grams);
                if (sourceYieldGrams <= 0) continue;

                const totalGramsNeeded = gramsPerPiece * piecesNeeded;
                const sourceYieldsNeeded = totalGramsNeeded / sourceYieldGrams;
                const sourcePiecesPerYield = getTodayDistPiecesPerYield(sourceProduct);
                const sourcePiecesNeeded = sourceYieldsNeeded * sourcePiecesPerYield;

                await accumulateTodayDistRawMaterialUsage(
                    sourceProductId,
                    sourceYieldsNeeded,
                    sourcePiecesNeeded,
                    materialMap,
                    nextVisited,
                    sourceProduct
                );
            }
        }

        async function computeTodayDistRawMaterialUsageForItem(item, productHint = null, piecesHint = null) {
            let productData = productHint || getTodayDistProductData(item && item.product_id);

            if (!productData || !Array.isArray(productData.ingredients)) {
                productData = await fetchTodayDistProductDetail(item && item.product_id);
            }

            if (!productData) return [];

            const pieces = parseInventoryNumericValue(piecesHint) > 0 ?
                parseInventoryNumericValue(piecesHint) :
                getTodayDistPieces(item, productData);

            if (pieces <= 0) return [];

            const piecesPerYield = getTodayDistPiecesPerYield(productData);
            const yieldsNeeded = piecesPerYield > 0 ? (pieces / piecesPerYield) : 0;
            if (yieldsNeeded <= 0) return [];

            const materialMap = {};
            await accumulateTodayDistRawMaterialUsage(item.product_id, yieldsNeeded, pieces, materialMap, new Set(),
                productData);
            return todayDistMaterialMapToArray(materialMap);
        }

        function setTodayDistributionRefreshLoadingState(isLoading) {
            if (isLoading) {
                $('#btnRefreshTodayDistribution')
                    .prop('disabled', true)
                    .addClass('opacity-60 cursor-not-allowed');
            } else {
                $('#btnRefreshTodayDistribution')
                    .prop('disabled', false)
                    .removeClass('opacity-60 cursor-not-allowed');
            }
        }

        function setTodayDistributionPanelPane(pane = 'groups') {
            const isItemsPane = pane === 'items';
            $('#todayDistributionSlideTrack').css('transform', isItemsPane ? 'translateX(-50%)' : 'translateX(0)');
        }

        function renderTodayDistributionPanelLoading() {
            setTodayDistributionRefreshLoadingState(true);
            $('#todayDistributionLoading').removeClass('hidden');
            $('#todayDistributionEmpty').addClass('hidden');
            $('#todayDistributionContent').addClass('hidden');
            setTodayDistributionPanelPane('groups');
        }

        function renderTodayDistributionPanelEmpty(message = 'No distribution records for today.') {
            setTodayDistributionRefreshLoadingState(false);
            todayDistributionGroupedData = [];

            $('#todayDistSummaryItems').text('0');
            $('#todayDistSummaryGroups').text('0');
            $('#todayDistSummaryBatches').text('0');
            $('#todayDistSummaryPieces').text('0');
            $('#todayDistSummaryDirectCost').text(formatInventoryPeso(0));
            $('#todayDistributionGroupList').html('<p class="text-xs text-gray-400">No distribution groups for today.</p>');
            $('#todayDistributionGroupItems').html('<p class="text-xs text-gray-400">No distributed items.</p>');
            $('#todayDistSelectedGroupName').text('Selected Group');
            $('#todayDistSelectedGroupMeta').text('0 batches • 0 pcs');
            $('#todayDistSelectedGroupNote').addClass('hidden').text('');

            $('#todayDistributionEmptyText').text(message);
            $('#todayDistributionLoading').addClass('hidden');
            $('#todayDistributionContent').addClass('hidden');
            $('#todayDistributionEmpty').removeClass('hidden');
            setTodayDistributionPanelPane('groups');
        }

        function formatTodayDistQuantityLabel(item) {
            const qtyMode = ((item.qty_mode || 'batch') + '').toLowerCase();
            const quantity = parseInventoryNumericValue(item.product_qnty);
            const pieces = parseInventoryNumericValue(item.pieces_calculated);

            if (qtyMode === 'pieces') {
                return formatInventoryNumber(pieces, 0) + ' pcs';
            }

            if (qtyMode === 'batch') {
                const isSingleBatch = Math.abs(quantity - 1) < 0.000001;
                return formatInventoryNumber(quantity, 0) + ' batch' + (isSingleBatch ? '' : 'es') + ' • ' +
                    formatInventoryNumber(pieces, 0) + ' pcs';
            }

            if (qtyMode === 'box') {
                const isSingleBox = Math.abs(quantity - 1) < 0.000001;
                return formatInventoryNumber(quantity, 0) + ' box' + (isSingleBox ? '' : 'es') + ' • ' +
                    formatInventoryNumber(pieces, 0) + ' pcs';
            }

            return formatInventoryNumber(quantity, 0) + ' ' + escapeInventoryHtml(qtyMode) + ' • ' + formatInventoryNumber(
                pieces, 0) + ' pcs';
        }

        function hydrateTodayDistributionGroups(groups) {
            const normalizedGroups = Array.isArray(groups) ? groups : [];

            return normalizedGroups.map(function (group, groupIndex) {
                const normalizedItems = (Array.isArray(group && group.items) ? group.items : []).map(function (
                    item) {
                    const productData = Object.assign({}, getTodayDistProductData(item && item
                        .product_id) || {}, item || {});
                    const piecesCalculated = getTodayDistPieces(item, productData);
                    const directCostCalculated = calculateTodayDistItemDirectCost(item, productData);

                    return Object.assign({}, item, {
                        pieces_calculated: piecesCalculated,
                        direct_cost_calculated: directCostCalculated,
                    });
                });

                const totalItems = normalizedItems.length;
                const totalBatches = normalizedItems.reduce(function (sum, item) {
                    const mode = ((item.qty_mode || 'batch') + '').toLowerCase();
                    if (mode === 'pieces') return sum;
                    return sum + parseInventoryNumericValue(item.product_qnty);
                }, 0);
                const totalPieces = normalizedItems.reduce(function (sum, item) {
                    return sum + parseInventoryNumericValue(item.pieces_calculated);
                }, 0);
                const totalDirectCost = normalizedItems.reduce(function (sum, item) {
                    return sum + parseInventoryNumericValue(item.direct_cost_calculated);
                }, 0);

                return {
                    group_key: ((group && group.group_key) || (group && group.distribution_group_key) || ('group-' +
                        (groupIndex + 1))).toString(),
                    group_name: ((group && group.group_name) || (group && group.distribution_group_name) || (
                        'Group ' + (groupIndex + 1))).toString().trim() || ('Group ' + (groupIndex + 1)),
                    group_note: getTodayDistributionGroupNote(group, normalizedItems),
                    distribution_date: (group && group.distribution_date) || null,
                    total_items: totalItems,
                    total_batches: totalBatches,
                    total_pieces: totalPieces,
                    total_direct_cost: totalDirectCost,
                    items: normalizedItems,
                };
            });
        }

        function renderTodayDistributionGroupItemsPane(groupIndex, shouldOpenPane = true) {
            const normalizedIndex = parseInt(groupIndex, 10);
            if (!Number.isFinite(normalizedIndex) || normalizedIndex < 0 || normalizedIndex >= todayDistributionGroupedData
                .length) {
                $('#todayDistributionGroupItems').html(
                    '<p class="text-xs text-gray-400">No items found for this group.</p>');
                if (shouldOpenPane) {
                    setTodayDistributionPanelPane('items');
                }
                return;
            }

            const selectedGroup = todayDistributionGroupedData[normalizedIndex];
            const groupName = (selectedGroup.group_name || ('Group ' + (normalizedIndex + 1))).toString();
            const groupNote = (selectedGroup.group_note || '').toString().trim();
            const groupBatches = formatInventoryNumber(selectedGroup.total_batches, 0);
            const groupPieces = formatInventoryNumber(selectedGroup.total_pieces, 0);
            const groupDirectCost = formatInventoryPeso(selectedGroup.total_direct_cost);

            $('#todayDistSelectedGroupName').text(groupName);
            $('#todayDistSelectedGroupMeta').text(groupBatches + ' batches • ' + groupPieces + ' pcs');

            if (groupNote) {
                $('#todayDistSelectedGroupNote')
                    .html('<i class="fas fa-sticky-note mr-1 text-amber-500"></i>' + escapeInventoryHtml(groupNote))
                    .removeClass('hidden');
            } else {
                $('#todayDistSelectedGroupNote').addClass('hidden').text('');
            }

            const itemRows = (Array.isArray(selectedGroup.items) ? selectedGroup.items : []).map(function (item) {
                const productName = escapeInventoryHtml(item.product_name || 'Unknown Product');
                const category = (item.category || '').toString().trim();
                const safeCategory = category ? (' • ' + escapeInventoryHtml(category)) : '';
                const quantityLabel = formatTodayDistQuantityLabel(item);
                const directCost = parseInventoryNumericValue(item.direct_cost_calculated);

                return `
                    <div class="p-2.5 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">${productName}</p>
                                <p class="text-[11px] text-gray-500">${quantityLabel}${safeCategory}</p>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            $('#todayDistributionGroupItems').html(itemRows ||
                '<p class="text-xs text-gray-400">No distributed items in this group.</p>');

            if (shouldOpenPane) {
                setTodayDistributionPanelPane('items');
            }
        }

        function renderTodayDistributionPanelData(groups) {
            const normalizedGroups = hydrateTodayDistributionGroups(groups);
            todayDistributionGroupedData = normalizedGroups;

            const allItems = flattenTodayDistributionGroupItems(normalizedGroups);
            const totalItems = allItems.length;
            const totalGroups = normalizedGroups.length;
            const totalBatches = normalizedGroups.reduce(function (sum, group) {
                return sum + parseInventoryNumericValue(group.total_batches);
            }, 0);
            const totalPieces = normalizedGroups.reduce(function (sum, group) {
                return sum + parseInventoryNumericValue(group.total_pieces);
            }, 0);
            const totalDirectCost = normalizedGroups.reduce(function (sum, group) {
                return sum + parseInventoryNumericValue(group.total_direct_cost);
            }, 0);

            $('#todayDistSummaryItems').text(formatInventoryNumber(totalItems, 0));
            $('#todayDistSummaryGroups').text(formatInventoryNumber(totalGroups, 0));
            $('#todayDistSummaryBatches').text(formatInventoryNumber(totalBatches, 0));
            $('#todayDistSummaryPieces').text(formatInventoryNumber(totalPieces, 0));
            $('#todayDistSummaryDirectCost').text(formatInventoryPeso(totalDirectCost));

            if (normalizedGroups.length === 0) {
                $('#todayDistributionGroupList').html(
                    '<p class="text-xs text-gray-400">No distribution groups for today.</p>');
                $('#todayDistributionGroupItems').html('<p class="text-xs text-gray-400">No distributed items.</p>');
                $('#todayDistSelectedGroupName').text('Selected Group');
                $('#todayDistSelectedGroupMeta').text('0 batches • 0 pcs');
                $('#todayDistSelectedGroupNote').addClass('hidden').text('');

                setTodayDistributionRefreshLoadingState(false);
                $('#todayDistributionLoading').addClass('hidden');
                $('#todayDistributionEmpty').addClass('hidden');
                $('#todayDistributionContent').removeClass('hidden');
                setTodayDistributionPanelPane('groups');
                return;
            }

            const groupsHtml = normalizedGroups.map(function (group, index) {
                const groupName = escapeInventoryHtml(group.group_name || ('Group ' + (index + 1)));
                const groupNote = (group.group_note || '').toString().trim();
                const safeNote = escapeInventoryHtml(groupNote);
                const batches = formatInventoryNumber(group.total_batches, 0);
                const pieces = formatInventoryNumber(group.total_pieces, 0);
                const directCost = formatInventoryPeso(group.total_direct_cost);
                const itemsCount = parseInt(group.total_items, 10) || 0;

                return `
                    <button type="button" class="btn-today-dist-open-group w-full text-left p-2.5 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors" data-group-index="${index}">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-primary truncate"><i class="fas fa-layer-group mr-1"></i>${groupName}</p>
                                <p class="text-[11px] text-gray-500 mt-0.5">${itemsCount} item(s) • ${batches} batches • ${pieces} pcs</p>
                                ${groupNote ? `<p class="text-[11px] text-amber-700 mt-1 truncate"><i class="fas fa-sticky-note mr-1 text-amber-500"></i>${safeNote}</p>` : ''}
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="inline-flex items-center justify-center mt-1 w-5 h-5 rounded-full bg-gray-100 text-gray-500">
                                    <i class="fas fa-chevron-right text-[10px]"></i>
                                </span>
                            </div>
                        </div>
                    </button>
                `;
            }).join('');

            $('#todayDistributionGroupList').html(groupsHtml);
            renderTodayDistributionGroupItemsPane(0, false);

            setTodayDistributionRefreshLoadingState(false);
            $('#todayDistributionLoading').addClass('hidden');
            $('#todayDistributionEmpty').addClass('hidden');
            $('#todayDistributionContent').removeClass('hidden');
            setTodayDistributionPanelPane('groups');
        }

        async function loadTodaysDistributionOverview(forceProductReload = false) {
            const requestToken = ++todayDistHydrationToken;
            renderTodayDistributionPanelLoading();

            try {
                await fetchTodayDistProducts(forceProductReload);

                const todayDate = getTodayDateForApi();
                const response = await fetchTodayDistributionByDate(todayDate);

                if (requestToken !== todayDistHydrationToken) return;

                const dayGroups = normalizeTodayDistributionGroups(
                    (response && response.success && Array.isArray(response.data)) ? response.data : []
                );
                const dayItems = flattenTodayDistributionGroupItems(dayGroups);
                $('#distCount').text(dayItems.length || 0);

                if (!dayItems.length) {
                    renderTodayDistributionPanelEmpty('No distribution records for today.');
                    return;
                }
                if (requestToken !== todayDistHydrationToken) return;
                renderTodayDistributionPanelData(dayGroups);
            } catch (error) {
                if (requestToken !== todayDistHydrationToken) return;
                renderTodayDistributionPanelEmpty('Unable to load today\'s distribution right now.');
            }
        }

        $(document).ready(function () {
            const baseUrl = '<?= base_url() ?>';

            // Display today's date
            const today = new Date();
            const dateString = today.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Check first for today's inventory
            $(document).ready(function () {
                checkIfDistributionExists();
                checkIfInventoryExists();
                loadTodaysDistributionOverview();
            });

            $('#todayDate').text(dateString);

            $('#btnRefreshTodayDistribution').on('click', function () {
                loadTodaysDistributionOverview(true);
            });

            $('#btnTodayDistBackToGroups').on('click', function () {
                setTodayDistributionPanelPane('groups');
            });

            $(document).on('click', '.btn-today-dist-open-group', function () {
                const selectedIndex = parseInt($(this).data('groupIndex'), 10);
                renderTodayDistributionGroupItemsPane(selectedIndex, true);
            });

            let reportShiftConfig = [];

            function formatShiftTimeLabel(timeValue) {
                if (!timeValue) {
                    return '--:--';
                }

                const parts = String(timeValue).split(':');
                const hour = parseInt(parts[0], 10);
                if (Number.isNaN(hour)) {
                    return timeValue;
                }

                const minute = parts[1] || '00';
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const hour12 = hour % 12 || 12;
                return `${hour12}:${minute} ${ampm}`;
            }

            function normalizeShiftKey(shiftKey, index) {
                const normalized = String(shiftKey || '').toLowerCase();
                if (['shift_a', 'shift_b', 'shift_c', 'shift_d'].includes(normalized)) {
                    return normalized;
                }

                if (['morning', 'am', 'first', 'first_shift'].includes(normalized)) {
                    return 'shift_a';
                }

                if (['afternoon', 'pm', 'second', 'second_shift'].includes(normalized)) {
                    return 'shift_b';
                }

                if (['weekend_morning', 'sat_sun_morning'].includes(normalized)) {
                    return 'shift_c';
                }

                if (['weekend_afternoon', 'sat_sun_afternoon'].includes(normalized)) {
                    return 'shift_d';
                }

                return index === 0 ? 'shift_a' : 'shift_b';
            }

            function buildSendReportShiftOptions(shifts) {
                const select = $('#sendReportShiftSelect');
                if (!select.length) {
                    return;
                }

                if (!Array.isArray(shifts) || shifts.length === 0) {
                    select.html([
                        '<option value="shift_a">Shift A</option>',
                        '<option value="shift_b">Shift B</option>',
                        '<option value="shift_c">Shift C</option>',
                        '<option value="shift_d">Shift D</option>'
                    ].join(''));
                    return;
                }

                const optionsHtml = shifts.map(function (shift, index) {
                    const optionValue = normalizeShiftKey(shift.key, index);
                    const label = shift.label || `Shift ${String.fromCharCode(65 + index)}`;
                    const start = formatShiftTimeLabel(shift.start);
                    const end = formatShiftTimeLabel(shift.end);

                    return `<option value="${optionValue}">${label} (${start} - ${end})</option>`;
                }).join('');

                if (optionsHtml.trim() === '') {
                    select.html([
                        '<option value="shift_a">Shift A</option>',
                        '<option value="shift_b">Shift B</option>',
                        '<option value="shift_c">Shift C</option>',
                        '<option value="shift_d">Shift D</option>'
                    ].join(''));
                    return;
                }

                select.html(optionsHtml);
            }

            function getSuggestedInventorySlot(shifts) {
                if (!Array.isArray(shifts) || shifts.length === 0) {
                    return 'shift_a';
                }

                const now = new Date();
                const currentTime = String(now.getHours()).padStart(2, '0') + ':' +
                    String(now.getMinutes()).padStart(2, '0') + ':' +
                    String(now.getSeconds()).padStart(2, '0');

                for (let i = 0; i < shifts.length; i++) {
                    const shift = shifts[i];
                    if (shift.start && shift.end && currentTime >= shift.start && currentTime <= shift.end) {
                        return normalizeShiftKey(shift.key, i);
                    }
                }

                return normalizeShiftKey(shifts[0].key, 0);
            }

            function loadSendReportShifts(callback) {
                const today = new Date();
                const dateStr = today.getFullYear() + '-' +
                    String(today.getMonth() + 1).padStart(2, '0') + '-' +
                    String(today.getDate()).padStart(2, '0');

                $.ajax({
                    url: baseUrl + '/Sales/getShiftConfig',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        date: dateStr
                    },
                    success: function (response) {
                        if (response && response.success) {
                            reportShiftConfig = response.shifts || [];
                        } else {
                            reportShiftConfig = [];
                        }

                        buildSendReportShiftOptions(reportShiftConfig);

                        const suggestedShift = getSuggestedInventorySlot(reportShiftConfig);
                        if ($('#sendReportShiftSelect option[value="' + suggestedShift + '"]').length) {
                            $('#sendReportShiftSelect').val(suggestedShift);
                        }

                        if (typeof callback === 'function') {
                            callback();
                        }
                    },
                    error: function () {
                        reportShiftConfig = [];
                        buildSendReportShiftOptions(reportShiftConfig);

                        const fallbackShift = 'shift_a';
                        $('#sendReportShiftSelect').val(fallbackShift);

                        if (typeof callback === 'function') {
                            callback();
                        }
                    }
                });
            }

            function openSendReportConfirmModal() {
                loadSendReportShifts(function () {
                    $('#sendReportConfirmModal').removeClass('hidden');
                });
            }

            function closeSendReportConfirmModal() {
                $('#sendReportConfirmModal').addClass('hidden');
                const confirmBtn = $('#btnConfirmSendInventoryReport');
                confirmBtn.prop('disabled', false)
                    .removeClass('opacity-70 cursor-not-allowed')
                    .text('Send Report');
            }

            $('#sendReportConfirmModalClose, #sendReportConfirmModalCancel, #sendReportConfirmModalBackdrop').on(
                'click',
                function () {
                    closeSendReportConfirmModal();
                });

            $('#btnSendInventoryReport').on('click', function () {
                const btn = $(this);
                if (btn.prop('disabled')) {
                    return;
                }

                openSendReportConfirmModal();
            });

            $('#btnConfirmSendInventoryReport').on('click', function () {
                const btn = $('#btnSendInventoryReport');
                const confirmBtn = $(this);

                if (btn.prop('disabled') || confirmBtn.prop('disabled')) {
                    return;
                }

                const originalHtml = btn.html();
                btn.prop('disabled', true)
                    .addClass('opacity-70 cursor-not-allowed')
                    .html('<i class="fas fa-spinner fa-spin mr-2"></i>Sending...');

                confirmBtn.prop('disabled', true)
                    .addClass('opacity-70 cursor-not-allowed')
                    .html('<i class="fas fa-spinner fa-spin mr-2"></i>Sending...');

                $.ajax({
                    url: baseUrl + '/Inventory/SendReport',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        inventory_id: inventoryId,
                        shift_key: ($('#sendReportShiftSelect').val() || '').trim()
                    }),
                    success: function (response) {
                        if (response && response.success) {
                            const redirectUrl = (response && response.redirect_url) ? response.redirect_url :
                                (baseUrl + '/Sales?daily_stock_id=' + encodeURIComponent(inventoryId));
                            window.location.href = redirectUrl;
                        } else {
                            showToast('error', (response && response.message) ||
                                'Failed to send inventory report.', 3000);
                        }
                    },
                    error: function (xhr, status, error) {
                        const message = xhr && xhr.responseJSON && xhr.responseJSON.message ?
                            xhr.responseJSON.message :
                            ('Error sending report: ' + error);
                        showToast('danger', message, 3000);
                    },
                    complete: function () {
                        btn.prop('disabled', false)
                            .removeClass('opacity-70 cursor-not-allowed')
                            .html(originalHtml);
                        closeSendReportConfirmModal();
                    }
                });
            });

            // Open Add Inventory Modal (Desktop & Mobile)
            $('#btnAddTodaysInventory, #btnAddTodaysInventoryMobile').on('click', function () {
                // Show badge immediately from current known source, then re-check in background
                updateInventoryModeBadge(inventorySource);
                checkIfDistributionExists(); // refreshes inventorySource + badge in background
                fetchYesterdayRemaining(); // Load carryover preview
                $('#timeInputModal').removeClass('hidden');
            });

            // Close Inventory Modal
            $('#btnCloseModal, #btnCancelAdd').on('click', function () {
                closeModal();
            });

            // Close Time Input Modal
            $('#timeInputModalClose, #timeInputModalCancel').on('click', function () {
                $('#timeInputModal').addClass('hidden');
                $('#timeInputForm')[0].reset();
                inventorySource = 'all'; // Reset source on cancel
            });

            // Submit Time Input Form
            $('#timeInputForm').on('submit', function (e) {
                e.preventDefault();

                $('#timeInputModal').addClass('hidden');

                // Route to the correct endpoint based on whether distribution exists today
                if (inventorySource === 'distribution') {
                    addTodaysInventoryFromDistribution();
                } else {
                    addTodaysInventory();
                }

                $('#timeInputForm')[0].reset();
            });

            function closeModal() {
                $('#addMaterialModal').addClass('hidden');
                $('#addMaterialForm')[0].reset();
            }

            // Submit Add Inventory Form via AJAX
            $('#addMaterialForm').on('submit', function (e) {
                e.preventDefault();

                const formData = {
                    // Add your inventory form fields here
                };

                $.ajax({
                    url: baseUrl + 'Inventory/Add',
                    type: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            alert('Inventory added successfully!');
                            closeModal();
                            loadInventory();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('Error adding inventory: ' + error);
                    }
                });
            });

            // Delete Inventory Item
            $(document).on('click', '.btn-delete', function () {
                if (enforceInventoryLock()) {
                    return;
                }
                const id = $(this).data('id');
                Confirm.delete('Are you sure you want to delete this inventory item?', () => {
                    $.ajax({
                        url: baseUrl + 'Inventory/Delete/' + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                showToast('success',
                                    'Inventory item deleted successfully!', 2000);
                                fetchAllStockitems();
                            } else {
                                showToast('error', response.message, 3000);
                            }
                        },
                        error: function (xhr, status, error) {
                            showToast('danger', xhr.responseJSON.message ||
                                'An error occured while deleting inventory', 3000);
                        }
                    });
                });
            });

            // Open Item Details Modal from row click
            $(document).on('click', '.inventory-item-row', function (e) {
                if ($(e.target).closest('button').length > 0) {
                    return; // Don't open if clicking on a button
                }
                openItemDetailsModal(
                    $(this).data('item-id'),
                    $(this).data('product-id'),
                    $(this).data('product-name'),
                    $(this).data('qty-sold'),
                    $(this).data('po') || 0,
                    $(this).data('price') || 0,
                    $(this).data('total-sales'),
                    $(this).data('notes')
                );
            });

            // Materials Used Button Click
            $(document).on('click', '.btn-materials-used', function (e) {
                e.stopPropagation();
                const itemId = $(this).data('item-id');
                const productId = $(this).data('product-id');
                const row = $(this).closest('tr');
                const productName = row.data('product-name');
                const qtySold = parseInt(row.data('qty-sold')) || 0;
                const po = parseInt(row.data('po')) || 0;
                const price = parseFloat(row.data('price')) || 0;
                const totalSales = parseFloat(row.data('total-sales')) || 0;
                const notes = row.data('notes');

                openItemDetailsModal(itemId, productId, productName, qtySold, po, price, totalSales, notes);
            });

            // Close Item Details Modal
            $('#itemDetailsModalClose, #itemDetailsModalCancel, #itemDetailsModalBackdrop').on('click', function () {
                $('#itemDetailsModal').addClass('hidden');
            });

            // Apply Filter
            $('#apply-filters').on('click', function () {
                const dateFrom = $('#filter-date-from').val();
                const dateTo = $('#filter-date-to').val();

                $('table tbody tr').each(function () {
                    const rowDate = $(this).data('date');
                    let show = true;

                    if (dateFrom && rowDate) {
                        show = show && (rowDate >= dateFrom);
                    }
                    if (dateTo && rowDate) {
                        show = show && (rowDate <= dateTo);
                    }

                    if (show) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Reset Filter
            $('#reset-filters').on('click', function () {
                $('#filter-date-from').val('');
                $('#filter-date-to').val('');
                $('table tbody tr').show();
            });
        });

        function fetchYesterdayRemaining() {
            const baseUrl = '<?= base_url() ?>';
            $('#carryoverPreview').addClass('hidden');
            $('#noCarryoverPreview').addClass('hidden');
            $('#carryoverList').empty();

            $.ajax({
                url: baseUrl + 'Inventory/GetYesterdayRemaining',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success && response.data && response.data.length > 0) {
                        let html = '';
                        response.data.forEach(function (item) {
                            html +=
                                '<div class="flex justify-between items-center py-1 border-b border-amber-100 last:border-0">';
                            html += '<span class="text-gray-700">' + item.product_name +
                                ' <span class="text-gray-400">(' + item.category + ')</span></span>';
                            html += '<span class="font-semibold text-amber-700">' + item
                                .remaining_stock + ' pcs</span>';
                            html += '</div>';
                        });
                        $('#carryoverList').html(html);
                        $('#carryoverPreview').removeClass('hidden');
                    } else {
                        $('#noCarryoverPreview').removeClass('hidden');
                    }
                },
                error: function () {
                    $('#noCarryoverPreview').removeClass('hidden');
                }
            });
        }

        function checkDistributionAndToggleButton() {
            const baseUrl = '<?= base_url() ?>';
            // Check if distribution exists for today and show Distributions button with count
            $.ajax({
                url: baseUrl + 'Distribution/CheckDistributionToday',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success && response.data && response.data.length > 0) {
                        // Distribution exists — show button with count
                        $('#distCount').text(response.data.length);
                        $('#btnDistributions').removeClass('hidden').addClass('sm:inline-flex');
                    } else {
                        // No distribution — hide button
                        $('#btnDistributions').addClass('hidden').removeClass('sm:inline-flex');
                    }
                },
                error: function () {
                    $('#btnDistributions').addClass('hidden').removeClass('sm:inline-flex');
                }
            });
        }

        function checkIfDistributionExists() {
            const baseUrl = '<?= base_url() ?>';
            $.ajax({
                url: baseUrl + 'Distribution/CheckDistributionToday',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success && response.data && response.data.length > 0) {
                        inventorySource = 'distribution';
                    } else {
                        inventorySource = 'all';
                    }
                    updateInventoryModeBadge(inventorySource);
                },
                error: function () {
                    // On error, default to 'all' to be safe
                    inventorySource = 'all';
                    updateInventoryModeBadge(inventorySource);
                }
            });
        }

        function updateInventoryModeBadge(source) {
            if (source === 'distribution') {
                $('#inventoryModeBadge').removeClass('hidden');
                $('#noDistributionModeBadge').addClass('hidden');
            } else {
                $('#inventoryModeBadge').addClass('hidden');
                $('#noDistributionModeBadge').removeClass('hidden');
            }
        }

        function checkActiveInventoriesAndDisableButtons() {
            const baseUrl = '<?= base_url() ?>';
            $.ajax({
                url: baseUrl + 'Inventory/CheckActiveInventories',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    const inventoryIsOpen = inventoryExistsToday && !inventoryIsClosed;
                    const hasInventoryToProcess = inventoryExistsToday && !inventoryReportSent;

                    // Keep existing endpoint check as a fallback signal for availability.
                    const hasActiveInventory = response.success && response.has_active;
                    const noActionableInventory = !hasInventoryToProcess && !hasActiveInventory;

                    // Send Report must stay disabled while inventory is still open.
                    const disableSendReport = noActionableInventory || inventoryIsOpen || inventoryReportSent;
                    $('#btnSendInventoryReport')
                        .prop('disabled', disableSendReport)
                        .toggleClass('opacity-50 cursor-not-allowed', disableSendReport);

                    // Open button should remain available for closed, unsent inventory.
                    const disableOpenButton = !inventoryExistsToday || !inventoryIsClosed || inventoryReportSent;
                    $('#btnOpenInventory')
                        .prop('disabled', disableOpenButton)
                        .toggleClass('opacity-50 cursor-not-allowed', disableOpenButton);
                },
                error: function () {
                    const inventoryIsOpen = inventoryExistsToday && !inventoryIsClosed;
                    const disableSendReport = !inventoryExistsToday || inventoryIsOpen || inventoryReportSent;
                    const disableOpenButton = !inventoryExistsToday || !inventoryIsClosed || inventoryReportSent;

                    $('#btnSendInventoryReport')
                        .prop('disabled', disableSendReport)
                        .toggleClass('opacity-50 cursor-not-allowed', disableSendReport);
                    $('#btnOpenInventory')
                        .prop('disabled', disableOpenButton)
                        .toggleClass('opacity-50 cursor-not-allowed', disableOpenButton);
                }
            });
        }

        function checkIfInventoryExists() {
            const baseUrl = '<?= base_url() ?>';
            $.ajax({
                url: baseUrl + 'Inventory/CheckInventoryToday',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    // Destroy existing DataTable first
                    if (response.success) {
                        inventoryExistsToday = true;
                        inventoryIsClosed = response.data && (response.data.is_closed === true || response.data
                            .is_closed === 1 || response.data.is_closed === '1');
                        inventoryReportSent = response.data && (response.data.report_sent === true || response.data
                            .report_sent === 1 || response.data.report_sent === '1');
                        syncInventoryInteractionLock();
                        updateDateTime(response.data);
                        fetchAllStockitems();
                        if ($('#btnSendInventoryReport').length) {
                            $('#btnSendInventoryReport').removeClass('hidden').addClass('sm:inline-flex');
                        }
                        // Show delete buttons and add product button when inventory exists
                        $('#btnDeleteTodaysInventory').removeClass('hidden').addClass('sm:inline-flex');
                        $('#btnAddProductToInventoryMobile').removeClass('hidden').addClass('inline-flex');
                        $('#btnAddProductToInventory').removeClass('hidden').addClass('sm:inline-flex');
                        $('#btnResetInventoryForNextShift').removeClass('hidden').addClass('sm:inline-flex');
                        // Only show Load from Distribution if distribution exists for today
                        checkDistributionAndToggleButton();
                        // Hide add inventory buttons
                        $('#btnAddTodaysInventory').addClass('hidden').removeClass('sm:inline-flex');
                        $('#btnAddTodaysInventoryMobile').addClass('hidden').removeClass('inline-flex');
                        console.log('Closed? ' + response.data.is_closed);
                        setInventoryState(response.data.is_closed);
                    } else {
                        inventoryExistsToday = false;
                        inventoryIsClosed = false;
                        inventoryReportSent = false;
                        syncInventoryInteractionLock();
                        showToast('warning', response.message, 2000);
                        loadInventory([]);
                        $('#btnCloseInventory').addClass('hidden');
                        $('#btnOpenInventory').addClass('hidden');
                        if ($('#btnSendInventoryReport').length) {
                            $('#btnSendInventoryReport').addClass('hidden').removeClass('sm:inline-flex');
                        }
                        // Show add inventory buttons when no inventory
                        $('#btnAddTodaysInventory').removeClass('hidden').addClass('sm:inline-flex');
                        $('#btnAddTodaysInventoryMobile').removeClass('hidden').addClass('inline-flex');
                        // Hide delete and add product buttons
                        $('#btnDeleteTodaysInventory').addClass('hidden').removeClass('sm:inline-flex');
                        $('#btnAddProductToInventoryMobile').addClass('hidden').removeClass('inline-flex');
                        $('#btnAddProductToInventory').addClass('hidden').removeClass('sm:inline-flex');
                        $('#btnResetInventoryForNextShift').addClass('hidden').removeClass('sm:inline-flex');
                        $('#btnDistributions').addClass('hidden').removeClass('sm:inline-flex');
                        checkActiveInventoriesAndDisableButtons();
                    }
                },
                error: function (xhr, status, error) {
                    inventoryExistsToday = false;
                    inventoryIsClosed = false;
                    inventoryReportSent = false;
                    syncInventoryInteractionLock();
                    console.log('Error checking inventory: ' + error);
                    $('#btnCloseInventory').addClass('hidden');
                    $('#btnOpenInventory').addClass('hidden');
                    if ($('#btnSendInventoryReport').length) {
                        $('#btnSendInventoryReport').addClass('hidden').removeClass('sm:inline-flex');
                    }
                    // Show add inventory buttons on error (safe default)
                    $('#btnAddTodaysInventory').removeClass('hidden').addClass('sm:inline-flex');
                    $('#btnAddTodaysInventoryMobile').removeClass('hidden').addClass('inline-flex');
                    // Hide delete and add product buttons
                    $('#btnDeleteTodaysInventory').addClass('hidden').removeClass('sm:inline-flex');
                    $('#btnAddProductToInventoryMobile').addClass('hidden').removeClass('inline-flex');
                    $('#btnAddProductToInventory').addClass('hidden').removeClass('sm:inline-flex');
                    $('#btnResetInventoryForNextShift').addClass('hidden').removeClass('sm:inline-flex');
                    $('#btnDistributions').addClass('hidden').removeClass('sm:inline-flex');
                }
            });
        }

        function updateDateTime(data) {
            // Update date display
            if (data.inventory_date) {
                const date = new Date(data.inventory_date);
                const dateString = date.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                $('#todayDate').text(dateString);
            }

            const hasStart = !!data.time_start;
            const hasRealEnd = !!data.time_end && data.time_end !== '00:00:00';

            // Update time range display
            if (hasStart && hasRealEnd) {
                // Format time to 12-hour format with AM/PM
                const formatTime = (time) => {
                    const [hours, minutes] = time.split(':');
                    const hour = parseInt(hours);
                    const ampm = hour >= 12 ? 'PM' : 'AM';
                    const displayHour = hour % 12 || 12;
                    return `${displayHour}:${minutes} ${ampm}`;
                };

                const timeStart = formatTime(data.time_start);
                const timeEnd = formatTime(data.time_end);
                $('#timeRange').text(`${timeStart} - ${timeEnd}`);
            } else if (hasStart) {
                const [hours, minutes] = data.time_start.split(':');
                const hour = parseInt(hours);
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const displayHour = hour % 12 || 12;
                $('#timeRange').text(`Started ${displayHour}:${minutes} ${ampm}`);
            } else {
                $('#timeRange').text('-');
            }
        }

        // ============================================================
        // Distribution Modal Functions
        // ============================================================

        /**
         * Open the distribution list modal and fetch items with status.
         */
        function openDistributionModal() {
            const baseUrl = '<?= base_url() ?>';
            $('#distributionListModal').removeClass('hidden');
            $('#distributionListContent').html(
                '<div class="text-center text-gray-400 py-8">' +
                '<i class="fas fa-spinner fa-spin text-2xl mb-2"></i>' +
                '<p class="text-sm">Loading distribution items...</p>' +
                '</div>'
            );

            $.ajax({
                url: baseUrl + 'Inventory/GetDistributionItemsWithStatus',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success && response.data && response.data.length > 0) {
                        renderDistributionList(response.data);
                    } else {
                        $('#distributionListContent').html(
                            '<div class="text-center text-gray-400 py-8">' +
                            '<i class="fas fa-box-open text-3xl mb-2"></i>' +
                            '<p class="text-sm">No distribution items for today.</p>' +
                            '</div>'
                        );
                    }
                },
                error: function () {
                    $('#distributionListContent').html(
                        '<div class="text-center text-red-400 py-8">' +
                        '<i class="fas fa-exclamation-circle text-3xl mb-2"></i>' +
                        '<p class="text-sm">Failed to load distribution items.</p>' +
                        '</div>'
                    );
                }
            });
        }

        /**
         * Render the list of distribution items in the modal.
         */
        function renderDistributionList(items) {
            let html = '';
            let hasUnloaded = false;

            items.forEach(function (item) {
                const categoryColors = {
                    bakery: {
                        bg: 'bg-amber-100',
                        text: 'text-amber-700',
                        icon: 'fa-bread-slice'
                    },
                    drinks: {
                        bg: 'bg-blue-100',
                        text: 'text-blue-700',
                        icon: 'fa-mug-hot'
                    },
                    grocery: {
                        bg: 'bg-emerald-100',
                        text: 'text-emerald-700',
                        icon: 'fa-shopping-basket'
                    },
                };
                const cat = categoryColors[item.category] || {
                    bg: 'bg-gray-100',
                    text: 'text-gray-600',
                    icon: 'fa-box'
                };

                const qtyLabel = item.qty_mode === 'batch' ?
                    item.product_qnty + ' batch' + (item.product_qnty > 1 ? 'es' : '') + ' → ' + item
                        .calculated_pieces + ' pcs' :
                    item.qty_mode === 'box' ?
                        item.product_qnty + ' box' + (item.product_qnty > 1 ? 'es' : '') + ' → ' + item
                            .calculated_pieces + ' pcs' :
                        item.calculated_pieces + ' pcs';

                const loadedQty = parseInt(item.loaded_qty) || 0;
                const isLoaded = item.loaded && loadedQty > 0;
                if (!isLoaded) hasUnloaded = true;

                html += '<div class="border border-gray-200 rounded-lg p-3 mb-2 ' + (isLoaded ? 'bg-green-50/50' :
                    'bg-white') + '">';
                html += '  <div class="flex items-start justify-between gap-2">';
                html += '    <div class="flex-1 min-w-0">';
                html += '      <div class="flex items-center gap-2 mb-1">';
                html += '        <span class="text-sm font-medium text-gray-800 truncate">' + item.product_name +
                    '</span>';
                html +=
                    '        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium ' +
                    cat.bg + ' ' + cat.text + '">';
                html += '          <i class="fas ' + cat.icon + ' mr-1"></i>' + item.category;
                html += '        </span>';
                html += '      </div>';
                html += '      <div class="flex items-center gap-3 text-xs text-gray-500">';
                html += '        <span><i class="fas fa-cubes mr-1"></i>' + qtyLabel + '</span>';
                html += '      </div>';

                if (isLoaded) {
                    html += '      <div class="mt-1.5">';
                    html +=
                        '        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-green-100 text-green-700">';
                    html += '          <i class="fas fa-check-circle mr-1"></i>Loaded: ' + loadedQty + ' pcs';
                    html += '        </span>';
                    html += '      </div>';
                }

                html += '    </div>';
                html += '    <div class="flex-shrink-0">';

                if (isLoaded) {
                    html +=
                        '      <button type="button" class="btn-load-dist-item px-3 py-1.5 text-xs font-medium rounded-lg border border-primary text-primary hover:bg-primary hover:text-white transition-colors"';
                } else {
                    html +=
                        '      <button type="button" class="btn-load-dist-item px-3 py-1.5 text-xs font-medium rounded-lg bg-primary text-white hover:bg-secondary transition-colors"';
                }
                html += '        data-product-id="' + item.product_id + '"';
                html += '        data-product-name="' + (item.product_name || '').replace(/"/g, '&quot;') + '"';
                html += '        data-expected-pieces="' + item.calculated_pieces + '"';
                html += '        data-qty-mode="' + item.qty_mode + '"';
                html += '        data-product-qnty="' + item.product_qnty + '"';
                html += '        data-loaded="' + (isLoaded ? '1' : '0') + '"';
                html += '        data-loaded-qty="' + loadedQty + '">';
                html += '        <i class="fas fa-download mr-1"></i>' + (isLoaded ? 'Load More' : 'Load');
                html += '      </button>';

                html += '    </div>';
                html += '  </div>';
                html += '</div>';
            });

            $('#distributionListContent').html(html);

            // Toggle "Load All Remaining" button visibility
            if (hasUnloaded) {
                $('#btnLoadAllRemaining').removeClass('hidden');
            } else {
                $('#btnLoadAllRemaining').addClass('hidden');
            }
        }

        /**
         * Open the load single item sub-modal from a distribution list item.
         */
        $(document).on('click', '.btn-load-dist-item', function () {
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            const expectedPieces = parseInt($(this).data('expected-pieces')) || 0;
            const qtyMode = $(this).data('qty-mode');
            const productQnty = $(this).data('product-qnty');
            const alreadyLoaded = $(this).data('loaded') === 1 || $(this).data('loaded') === '1';
            const alreadyLoadedQty = parseInt($(this).data('loaded-qty')) || 0;
            const remaining = Math.max(0, expectedPieces - alreadyLoadedQty);

            // Populate modal
            $('#loadItemProductId').val(productId);
            $('#loadItemExpectedPieces').val(expectedPieces);
            $('#loadItemAlreadyLoaded').val(alreadyLoadedQty);
            $('#loadItemProductName').text(productName);
            $('#loadItemQuantity').val(remaining > 0 ? remaining : 1);

            // Info bar
            let infoText = 'Distribution: <strong>' + expectedPieces + '</strong> pcs';
            if (qtyMode === 'batch') {
                infoText = productQnty + ' batch' + (productQnty > 1 ? 'es' : '') + ' → <strong>' + expectedPieces +
                    '</strong> pcs';
            }
            if (alreadyLoaded) {
                infoText += ' <span class="text-green-600">(previously loaded)</span>';
            }
            $('#loadItemDistInfoText').data('base-info', infoText).html(infoText);

            // Reset state
            resetLoadItemModalState();
            updateLoadQuantityDisplay();

            $('#loadSingleItemModal').removeClass('hidden');
        });

        /**
         * Update deviation warning and note requirement based on qty vs expected.
         */
        function updateLoadQuantityDisplay() {
            const expected = parseInt($('#loadItemExpectedPieces').val()) || 0;
            const alreadyLoaded = parseInt($('#loadItemAlreadyLoaded').val()) || 0;
            const current = parseInt($('#loadItemQuantity').val()) || 0;
            const totalAfter = alreadyLoaded + current;
            const remaining = expected - alreadyLoaded;

            const baseInfo = $('#loadItemDistInfoText').data('base-info') || '';
            if (expected > 0) {
                let trackerText = '';
                if (remaining >= 0) {
                    trackerText = ' — Loaded: <strong>' + alreadyLoaded + '</strong> pcs, Remaining: <strong>' +
                        remaining + '</strong> pcs';
                } else {
                    trackerText = ' — Loaded: <strong>' + alreadyLoaded + '</strong> pcs, Over by <strong>' +
                        Math.abs(remaining) + '</strong> pcs';
                }
                $('#loadItemDistInfoText').html(baseInfo + trackerText);
            } else {
                $('#loadItemDistInfoText').html(baseInfo);
            }

            if (expected > 0 && totalAfter !== expected) {
                const delta = totalAfter - expected;
                const warningText = delta > 0 ?
                    'Exceeds distribution by <strong>' + delta + '</strong> pcs — note required' :
                    'Under distribution by <strong>' + Math.abs(delta) + '</strong> pcs — note required';
                $('#loadItemDeviationText').html(warningText);
                $('#loadItemDeviationWarning').removeClass('hidden');

                // Make note required
                $('#loadItemNote').attr('required', true);
                $('#loadItemNote').attr('placeholder', 'Explain why quantity differs from distribution');
                $('#loadItemNoteLabel').html('Note <span class="text-red-500">*</span>');
                $('#loadItemNoteHint').text('Required — explain the quantity adjustment').removeClass('text-gray-400')
                    .addClass('text-red-500');
                if (!$('#loadItemNote').val()) {
                    $('#loadItemNote').addClass('border-red-300 focus:border-red-400 focus:ring-red-200');
                } else {
                    $('#loadItemNote').removeClass('border-red-300 focus:border-red-400 focus:ring-red-200');
                }
            } else {
                // Matches expected or no expected baseline
                $('#loadItemDeviationWarning').addClass('hidden');
                $('#loadItemNote').removeAttr('required');
                $('#loadItemNote').attr('placeholder', 'Add note (optional)');
                $('#loadItemNoteLabel').text('Note');
                $('#loadItemNoteHint').text('Optional — max 500 characters').removeClass('text-red-500').addClass(
                    'text-gray-400');
                $('#loadItemNote').removeClass('border-red-300 focus:border-red-400 focus:ring-red-200');
            }
        }

        /**
         * Reset the load item modal to default state.
         */
        function resetLoadItemModalState() {
            $('#loadItemDeviationWarning').addClass('hidden');
            $('#loadItemNote').removeAttr('required');
            $('#loadItemNote').attr('placeholder', 'Add note (optional)');
            $('#loadItemNoteLabel').text('Note');
            $('#loadItemNoteHint').text('Optional — max 500 characters').removeClass('text-red-500').addClass(
                'text-gray-400');
            $('#loadItemNote').removeClass('border-red-300 focus:border-red-400 focus:ring-red-200');
        }

        /**
         * Submit the single distribution item load.
         */
        function submitLoadSingleItem() {
            const baseUrl = '<?= base_url() ?>';
            const productId = parseInt($('#loadItemProductId').val());
            const quantity = parseInt($('#loadItemQuantity').val());
            const expectedPieces = parseInt($('#loadItemExpectedPieces').val());
            const note = $('#loadItemNote').val().trim();

            if (!productId || quantity <= 0) {
                showToast('warning', 'Please enter a valid quantity.', 2000);
                return;
            }

            // Client-side note validation
            const alreadyLoaded = parseInt($('#loadItemAlreadyLoaded').val()) || 0;
            if (expectedPieces > 0 && (alreadyLoaded + quantity) !== expectedPieces && !note) {
                showToast('warning', 'A note is required when total loaded differs from distribution (' + expectedPieces +
                    ' pcs).', 3000);
                $('#loadItemNote').focus();
                return;
            }

            // Disable submit button
            const $submitBtn = $('#loadSingleItemForm button[type="submit"]');
            $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1.5"></i> Loading...');

            $.ajax({
                url: baseUrl + 'Inventory/LoadDistributionItem',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    product_id: productId,
                    quantity: quantity,
                    expected_pieces: expectedPieces,
                    note: note
                }),
                success: function (response) {
                    if (response.success) {
                        showToast('success', response.message, 3000);
                        // Close the load modal and refresh the distribution list
                        $('#loadSingleItemModal').addClass('hidden');
                        $('#loadSingleItemForm')[0].reset();
                        resetLoadItemModalState();
                        // Refresh the distribution list modal
                        openDistributionModal();
                        // Refresh the inventory table
                        fetchAllStockitems();
                    } else {
                        showToast('error', response.message || 'Failed to load item.', 3000);
                    }
                },
                error: function (xhr) {
                    let msg = 'Failed to load distribution item.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    showToast('error', msg, 3000);
                },
                complete: function () {
                    $submitBtn.prop('disabled', false).html('<i class="fas fa-check mr-1.5"></i> Confirm Load');
                }
            });
        }

        /**
         * Load all remaining (unloaded) distribution items at their expected quantities.
         */
        function loadAllRemainingDistribution() {
            const baseUrl = '<?= base_url() ?>';

            // Disable the button
            const $btn = $('#btnLoadAllRemaining');
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Loading...');

            $.ajax({
                url: baseUrl + 'Inventory/LoadFromDistribution',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                success: function (response) {
                    a
                    if (response.success) {
                        showToast('success', response.message, 3000);
                        // Refresh the distribution list modal
                        openDistributionModal();
                        // Refresh the inventory table
                        fetchAllStockitems();
                    } else {
                        showToast('error', response.message, 3000);
                    }
                },
                error: function (xhr) {
                    let msg = 'Failed to load distribution data.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    showToast('error', msg, 3000);
                },
                complete: function () {
                    $btn.prop('disabled', false).html(
                        '<i class="fas fa-download mr-1"></i> Load All Remaining');
                }
            });
        }

        function addTodaysInventoryFromDistribution() {
            const baseUrl = '<?= base_url() ?>';
            $.ajax({
                url: baseUrl + 'Inventory/AddInventoryFromDistribution',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({}),
                success: function (response) {
                    if (response.success) {
                        let msg = response.message;
                        if (response.carryover_count > 0) {
                            msg += ' (' + response.carryover_count + ' item(s) carried over from yesterday.)';
                        }
                        showToast('success', msg, 3000);
                        checkIfInventoryExists();
                        fetchAllStockitems();
                    } else {
                        showToast('error', response.message, 3000);
                    }
                },
                error: function (xhr) {
                    const errorMessage = (xhr.responseJSON && xhr.responseJSON.message) ?
                        xhr.responseJSON.message :
                        'An error occurred while creating inventory from distribution.';
                    showToast('danger', errorMessage, 4000);
                }
            });
        }

        // If no inventory, show button for creating inventory
        function addTodaysInventory() {
            const baseUrl = '<?= base_url() ?>';
            $.ajax({
                url: baseUrl + 'Inventory/AddTodaysInventory',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({}),
                success: function (response) {
                    if (response.success) {
                        showToast('success', response.message, 2000);
                        checkIfInventoryExists();
                        fetchAllStockitems();
                    } else {
                        showToast('error', response.message, 2000);
                    }
                },
                error: function (xhr, status, error) {
                    showToast('danger', xhr.responseJSON.message || 'An error occured while adding inventory',
                        2000);
                    console.log(xhr.responseJSON);
                }
            });
        }

        function fetchAllStockitems() {
            const baseURL = '<?= base_url() ?>';
            $.ajax({
                url: `${baseURL}Inventory/FetchAllStockItems`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        loadInventory(response.data);
                        console.log('Inventory data:', response);
                        inventoryId = response.inventory_id || null;
                    } else {
                        console.log("Error: " + response.error);
                    }
                },
                error: function (xhr, status, error) {
                    showToast('danger', 'Error fetching inventory: ' + (xhr.responseJSON?.message || error),
                        2000);
                    console.log(xhr.responseJSON);
                }
            });
        }

        // Mobile pagination variables
        let allInventoryItems = [];
        let filteredItems = [];
        let currentPage = 1;
        const itemsPerPage = 10;

        // Carryover data map: { product_id: remaining_stock }
        let carryoverData = {};

        // Fetch carryover data once and store globally
        function fetchCarryoverData() {
            const baseUrl = '<?= base_url() ?>';
            $.ajax({
                url: baseUrl + 'Inventory/GetYesterdayRemaining',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    carryoverData = {};
                    if (response.success && response.data && response.data.length > 0) {
                        response.data.forEach(function (item) {
                            carryoverData[item.product_id] = parseInt(item.remaining_stock) || 0;
                        });
                    }
                },
                error: function () {
                    carryoverData = {};
                }
            });
        }

        function captureRowPositions(tableBodyId) {
            const positions = {};
            $('#' + tableBodyId + ' .inventory-item-row').each(function () {
                const itemId = $(this).data('item-id');
                if (itemId != null) {
                    positions[String(itemId)] = this.getBoundingClientRect().top;
                }
            });
            return positions;
        }

        function animateRowReorder(tableBodyId, beforePositions) {
            if (!beforePositions || Object.keys(beforePositions).length === 0) {
                return;
            }

            requestAnimationFrame(function () {
                $('#' + tableBodyId + ' .inventory-item-row').each(function () {
                    const itemId = $(this).data('item-id');
                    const beforeTop = beforePositions[String(itemId)];
                    if (beforeTop == null) {
                        return;
                    }

                    const afterTop = this.getBoundingClientRect().top;
                    const delta = beforeTop - afterTop;
                    if (delta === 0) {
                        return;
                    }

                    this.style.transition = 'transform 0s';
                    this.style.transform = 'translateY(' + delta + 'px)';
                    requestAnimationFrame(() => {
                        this.style.transition = 'transform 220ms ease';
                        this.style.transform = '';
                    });
                });
            });
        }

        function loadInventory(items, options) {
            const opts = options || {};
            const beforePositions = {
                bakery: captureRowPositions('bakeryTableBody'),
                drinks: captureRowPositions('drinksTableBody'),
                grocery: captureRowPositions('groceryTableBody')
            };
            const normalizedItems = (items || []).slice().sort(function (a, b) {
                const enabledA = parseInt(a.is_enabled) === 1 ? 1 : 0;
                const enabledB = parseInt(b.is_enabled) === 1 ? 1 : 0;
                if (enabledA !== enabledB) {
                    return enabledB - enabledA;
                }

                const nameA = (a.product_name || a.item || '').toString().toLowerCase();
                const nameB = (b.product_name || b.item || '').toString().toLowerCase();
                if (nameA !== nameB) {
                    return nameA.localeCompare(nameB);
                }

                return String(a.item_id || '').localeCompare(String(b.item_id || ''));
            });
            // Store items for mobile pagination
            allInventoryItems = normalizedItems;
            filteredItems = [...allInventoryItems];
            currentPage = 1;

            // Fetch carryover data for use in edit modal
            if (opts.fetchCarryover !== false) {
                fetchCarryoverData();
            }

            // Separate items by category
            const bakeryItems = normalizedItems.filter(i => i.category === 'bakery');
            const drinksItems = normalizedItems.filter(i => i.category === 'drinks');
            const groceryItems = normalizedItems.filter(i => i.category === 'grocery');

            // Render each category table
            renderBakeryTable(bakeryItems);
            renderDrinksTable(drinksItems);
            renderGroceryTable(groceryItems);

            animateRowReorder('bakeryTableBody', beforePositions.bakery);
            animateRowReorder('drinksTableBody', beforePositions.drinks);
            animateRowReorder('groceryTableBody', beforePositions.grocery);

            // Update totals
            updateGrandTotals(normalizedItems);

            // Render mobile cards with pagination
            renderMobileCards();
        }

        function applyEditedInventoryItemLocally(itemId, payload, isAdjustmentMode) {
            const index = allInventoryItems.findIndex(item => String(item.item_id) === String(itemId));
            if (index < 0) {
                return false;
            }

            const item = {
                ...allInventoryItems[index]
            };

            const beginningInput = parseInt(payload.beginning_stock) || 0;
            const pullOutInput = parseInt(payload.pull_out_quantity) || 0;
            const endingInput = parseInt(payload.ending_stock) || 0;

            if (isAdjustmentMode) {
                const oldQtySold = parseInt(item.quantity_sold_db) || parseInt(item.quantity_sold) || Math.max(0,
                    (parseInt(item.beginning_stock) || 0) - (parseInt(item.pull_out_quantity) || 0) - (parseInt(item.ending_stock) || 0)
                );

                item.beginning_stock = (parseInt(item.beginning_stock) || 0) + beginningInput;
                item.pull_out_quantity = (parseInt(item.pull_out_quantity) || 0) + pullOutInput;
                item.ending_stock = endingInput;

                const discrepancy = item.beginning_stock - (item.pull_out_quantity + oldQtySold + item.ending_stock);
                item.quantity_sold = Math.max(0, oldQtySold + discrepancy);
                item.quantity_sold_db = oldQtySold;
                item.discrepancy = item.quantity_sold - oldQtySold;
            } else {
                item.beginning_stock = beginningInput;
                item.pull_out_quantity = pullOutInput;
                // Keep ending as the physical count; reconcile pull out into qty sold.
                const currentEnding = Math.max(0, parseInt(item.ending_stock) || 0);
                const dbQtySoldRaw = parseInt(item.quantity_sold_db);
                const oldQtySold = Number.isNaN(dbQtySoldRaw) ? Math.max(0, parseInt(item.quantity_sold) || 0) : Math.max(
                    0, dbQtySoldRaw);
                item.ending_stock = currentEnding;
                item.quantity_sold = Math.max(0, item.beginning_stock - item.pull_out_quantity - item.ending_stock);
                item.quantity_sold_db = oldQtySold;
                item.discrepancy = item.quantity_sold - oldQtySold;
            }

            item.beginning_stock = Math.max(0, parseInt(item.beginning_stock) || 0);
            item.pull_out_quantity = Math.max(0, parseInt(item.pull_out_quantity) || 0);
            item.ending_stock = Math.max(0, parseInt(item.ending_stock) || 0);
            item.notes = payload.notes || '';

            // Keep sales columns in sync for immediate redraw.
            const price = parseFloat(
                (item.selling_price_per_piece > 0 ? item.selling_price_per_piece : item.selling_price) || item.srp || 0
            ) || 0;
            item.sales = item.quantity_sold * price;
            item.total_sales = item.sales;

            allInventoryItems[index] = item;
            filteredItems = [...allInventoryItems];
            return true;
        }

        function renderBakeryTable(items) {
            let rows = '';
            let totalQty = 0;

            if (items && items.length > 0) {
                items.forEach(function (item) {
                    const price = item.selling_price_per_piece > 0 ? item.selling_price_per_piece : item
                        .selling_price;
                    const formattedPrice = '₱' + parseFloat(price || 0).toFixed(2);
                    const beginning = parseInt(item.beginning_stock) || 0;
                    const pullOut = parseInt(item.pull_out_quantity) || 0;
                    const qtySold = parseInt(item.quantity_sold) || 0;
                    const ending_stock = parseInt(item.ending_stock) || 0;
                    const totalSales = (qtySold * parseFloat(price || 0)).toFixed(2);
                    const formattedSales = '₱' + parseFloat(totalSales).toFixed(2);
                    const totalCostPerYield = parseFloat(item.total_cost ?? item.direct_cost ?? 0) || 0;
                    const traysPerYield = parseInt(item.trays_per_yield) || 0;
                    const piecesPerYield = parseInt(item.pieces_per_yield) || 0;
                    const piecesPerBatch = traysPerYield > 0 && piecesPerYield > 0 ?
                        traysPerYield * piecesPerYield :
                        (piecesPerYield > 0 ? piecesPerYield : 1);
                    const overheadPercentage = parseFloat(item.overhead_cost_percentage ?? 0) || 0;
                    const overheadCostPerYield = parseFloat(item.overhead_cost_amount ?? 0) || 0;
                    const overheadOnTotal = totalCostPerYield * (overheadPercentage / 100);
                    const overheadPerYield = overheadCostPerYield > 0 ? overheadCostPerYield : overheadOnTotal;
                    const overheadPerPiece = piecesPerBatch > 0 ? overheadPerYield / piecesPerBatch : 0;
                    const overheadTotal = (overheadPerPiece * (qtySold + pullOut)).toFixed(5);
                    const formattedOverhead = '₱' + parseFloat(overheadTotal).toFixed(5);
                    const isEnabled = parseInt(item.is_enabled) === 1;

                    totalQty += qtySold;

                    rows +=
                        '<tr class="hover:bg-gray-50 border-b border-gray-100 cursor-pointer inventory-item-row' + (
                            !isEnabled ? ' opacity-50' :
                                '') + '" data-item-id="' + item.item_id + '" data-product-id="' + item.product_id +
                        '" data-qty-sold="' + qtySold + '" data-po="' + pullOut + '" data-price="' + parseFloat(
                            price || 0) + '" data-product-name="' + (item.product_name || 'N/A').replace(/"/g,
                                '&quot;') + '" data-total-sales="' + totalSales + '" data-notes="' + (item.notes || '')
                                    .replace(/"/g, '&quot;') + '">';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-800">' + (item.product_name || 'N/A') + (!
                        isEnabled ? ' <span class="text-xs text-red-400 font-medium">(Disabled)</span>' : '') +
                        '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + formattedPrice + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + beginning + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + pullOut + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + qtySold + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + ending_stock + '</td>';
                    <?php if ($isOwnerView): ?>
                        rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + formattedOverhead + '</td>';
                    <?php endif; ?>
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + formattedSales + '</td>';
                    <?php if ($isOwnerView): ?>
                        rows += '<td class="px-6 py-2.5 text-sm text-center">';
                        rows += '<button class="text-blue-600 hover:text-blue-800 btn-materials-used" data-item-id="' +
                            item.item_id + '" data-product-id="' + item.product_id +
                            '" title="View materials used"><i class="fas fa-flask"></i></button>';
                        rows += '</td>';
                    <?php endif; ?>
                    rows += '<td class="px-6 py-3 whitespace-nowrap">';
                    rows += '<button class="me-2 btn-toggle-enabled ' + (isEnabled ?
                        'text-green-600 hover:text-green-800' : 'text-gray-400 hover:text-gray-600') +
                        '" data-id="' + item.item_id + '" data-enabled="' + (isEnabled ? '1' : '0') + '" title="' +
                        (isEnabled ? 'Disable item' : 'Enable item') + '"><i class="fas ' + (isEnabled ?
                            'fa-toggle-on' : 'fa-toggle-off') + ' text-lg"></i></button>';
                    rows += (isEnabled ?
                        '<button class="text-amber-600 hover:text-amber-800 me-2 btn-edit" data-id="' + item
                            .item_id +
                        '" data-category="bakery" title="Edit"><i class="fas fa-edit"></i></button>' : '');
                    rows += (isEnabled ? '<button class="text-red-600 hover:text-red-800 btn-delete" data-id="' +
                        item.item_id + '" title="Delete"><i class="fas fa-trash"></i></button>' : '');
                    rows += '</td>';
                    rows += '</tr>';
                });
            } else {
                rows =
                    '<tr><td colspan="9" class="px-6 py-4 text-center text-gray-500">No bakery items in inventory</td></tr>';
            }

            $('#bakeryTableBody').html(rows);
            $('#bakeryTotalQty').text(totalQty);
        }

        function renderDrinksTable(items) {
            let rows = '';
            let totalQty = 0;
            let totalSales = 0;

            if (items && items.length > 0) {
                items.forEach(function (item) {
                    const srp = parseFloat(item.srp ?? item.selling_price ?? 0) || 0;
                    const formattedPrice = '₱' + srp.toFixed(2);
                    const qtySold = parseInt(item.quantity_sold) || 0;
                    const sales = parseFloat(item.sales ?? item.total_sales ?? 0) || 0;
                    const formattedSales = '₱' + sales.toFixed(2);
                    const totalCostPerYield = parseFloat(item.total_cost ?? item.direct_cost ?? 0) || 0;
                    const traysPerYield = parseInt(item.trays_per_yield) || 0;
                    const piecesPerYield = parseInt(item.pieces_per_yield) || 0;
                    const piecesPerBatch = traysPerYield > 0 && piecesPerYield > 0 ?
                        traysPerYield * piecesPerYield :
                        (piecesPerYield > 0 ? piecesPerYield : 1);
                    const overheadPercentage = parseFloat(item.overhead_cost_percentage ?? 0) || 0;
                    const overheadCostPerYield = parseFloat(item.overhead_cost_amount ?? 0) || 0;
                    const overheadOnTotal = totalCostPerYield * (overheadPercentage / 100);
                    const overheadPerYield = overheadCostPerYield > 0 ? overheadCostPerYield : overheadOnTotal;
                    const overheadPerPiece = piecesPerBatch > 0 ? overheadPerYield / piecesPerBatch : 0;
                    const overheadTotal = (overheadPerPiece * qtySold).toFixed(5);
                    const formattedOverhead = '₱' + parseFloat(overheadTotal).toFixed(5);
                    const isEnabled = parseInt(item.is_enabled) === 1;

                    totalQty += qtySold;
                    totalSales += sales;

                    rows +=
                        '<tr class="hover:bg-gray-50 border-b border-gray-100 cursor-pointer inventory-item-row' + (
                            !isEnabled ? ' opacity-50' :
                                '') + '" data-item-id="' + item.item_id + '" data-product-id="' + item.product_id +
                        '" data-qty-sold="' + qtySold + '" data-price="' + srp + '" data-product-name="' + (item
                            .item || item.product_name || 'N/A').replace(/"/g, '&quot;') + '" data-total-sales="' +
                        sales.toFixed(2) + '" data-notes="' + (item.notes || '').replace(/"/g, '&quot;') + '">';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-800">' + (item.item || item.product_name ||
                        'N/A') + (!isEnabled ?
                            ' <span class="text-xs text-red-400 font-medium">(Disabled)</span>' : '') + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + formattedPrice + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + qtySold + '</td>';
                    <?php if ($isOwnerView): ?>
                        rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + formattedOverhead + '</td>';
                    <?php endif; ?>
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + formattedSales + '</td>';
                    <?php if ($isOwnerView): ?>
                        rows += '<td class="px-6 py-2.5 text-sm text-center">';
                        rows += '<button class="text-blue-600 hover:text-blue-800 btn-materials-used" data-item-id="' +
                            item.item_id + '" data-product-id="' + item.product_id +
                            '" title="View materials used"><i class="fas fa-flask"></i></button>';
                        rows += '</td>';
                    <?php endif; ?>
                    rows += '<td class="px-6 py-3 whitespace-nowrap">';
                    rows += '<button class="me-2 btn-toggle-enabled ' + (isEnabled ?
                        'text-green-600 hover:text-green-800' : 'text-gray-400 hover:text-gray-600') +
                        '" data-id="' + item.item_id + '" data-enabled="' + (isEnabled ? '1' : '0') + '" title="' +
                        (isEnabled ? 'Disable item' : 'Enable item') + '"><i class="fas ' + (isEnabled ?
                            'fa-toggle-on' : 'fa-toggle-off') + ' text-lg"></i></button>';
                    rows += (isEnabled ? '<button class="text-red-600 hover:text-red-800 btn-delete" data-id="' +
                        item.item_id + '" title="Delete"><i class="fas fa-trash"></i></button>' : '');
                    rows += '</td>';
                    rows += '</tr>';
                });
            } else {
                rows = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No drinks in inventory</td></tr>';
            }

            $('#drinksTableBody').html(rows);
            $('#drinksTotalQty').text(totalQty);
            $('#drinksTotalSales').text('₱' + totalSales.toFixed(2));
        }

        function renderGroceryTable(items) {
            let rows = '';
            let totalQty = 0;

            if (items && items.length > 0) {
                items.forEach(function (item) {
                    const formattedPrice = '₱' + parseFloat(item.selling_price || 0).toFixed(2);
                    const beginning = parseInt(item.beginning_stock) || 0;
                    const pullOut = parseInt(item.pull_out_quantity) || 0;
                    const qtySold = parseInt(item.quantity_sold) || 0;
                    const ending_stock = parseInt(item.ending_stock) || 0;
                    const price = parseFloat(item.selling_price || 0);
                    const totalSales = (qtySold * price).toFixed(2);
                    const formattedSales = '₱' + parseFloat(totalSales).toFixed(2);
                    const totalCostPerYield = parseFloat(item.total_cost ?? item.direct_cost ?? 0) || 0;
                    const traysPerYield = parseInt(item.trays_per_yield) || 0;
                    const piecesPerYield = parseInt(item.pieces_per_yield) || 0;
                    const piecesPerBatch = traysPerYield > 0 && piecesPerYield > 0 ?
                        traysPerYield * piecesPerYield :
                        (piecesPerYield > 0 ? piecesPerYield : 1);
                    const overheadPercentage = parseFloat(item.overhead_cost_percentage ?? 0) || 0;
                    const overheadCostPerYield = parseFloat(item.overhead_cost_amount ?? 0) || 0;
                    const overheadOnTotal = totalCostPerYield * (overheadPercentage / 100);
                    const overheadPerYield = overheadCostPerYield > 0 ? overheadCostPerYield : overheadOnTotal;
                    const overheadPerPiece = piecesPerBatch > 0 ? overheadPerYield / piecesPerBatch : 0;
                    const overheadTotal = (overheadPerPiece * (qtySold + pullOut)).toFixed(5);
                    const formattedOverhead = '₱' + parseFloat(overheadTotal).toFixed(5);
                    const isEnabled = parseInt(item.is_enabled) === 1;

                    totalQty += qtySold;

                    rows +=
                        '<tr class="hover:bg-gray-50 border-b border-gray-100 cursor-pointer inventory-item-row' + (
                            !isEnabled ? ' opacity-50' :
                                '') + '" data-item-id="' + item.item_id + '" data-product-id="' + item.product_id +
                        '" data-qty-sold="' + qtySold + '" data-po="' + pullOut + '" data-price="' + parseFloat(item
                            .selling_price || 0) + '" data-product-name="' + (item.product_name || 'N/A').replace(
                                /"/g, '&quot;') + '" data-total-sales="' + totalSales + '" data-notes="' + (item
                                    .notes || '').replace(/"/g, '&quot;') + '">';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-800">' + (item.product_name || 'N/A') + (!
                        isEnabled ? ' <span class="text-xs text-red-400 font-medium">(Disabled)</span>' : '') +
                        '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + formattedPrice + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + beginning + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + pullOut + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + qtySold + '</td>';
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + ending_stock + '</td>';
                    <?php if ($isOwnerView): ?>
                        rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + formattedOverhead + '</td>';
                    <?php endif; ?>
                    rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + formattedSales + '</td>';
                    <?php if ($isOwnerView): ?>
                        rows += '<td class="px-6 py-2.5 text-sm text-center">';
                        rows += '<button class="text-blue-600 hover:text-blue-800 btn-materials-used" data-item-id="' +
                            item.item_id + '" data-product-id="' + item.product_id +
                            '" title="View materials used"><i class="fas fa-flask"></i></button>';
                        rows += '</td>';
                    <?php endif; ?>
                    rows += '<td class="px-6 py-3 whitespace-nowrap">';
                    rows += '<button class="me-2 btn-toggle-enabled ' + (isEnabled ?
                        'text-green-600 hover:text-green-800' : 'text-gray-400 hover:text-gray-600') +
                        '" data-id="' + item.item_id + '" data-enabled="' + (isEnabled ? '1' : '0') + '" title="' +
                        (isEnabled ? 'Disable item' : 'Enable item') + '"><i class="fas ' + (isEnabled ?
                            'fa-toggle-on' : 'fa-toggle-off') + ' text-lg"></i></button>';
                    rows += (isEnabled ?
                        '<button class="text-amber-600 hover:text-amber-800 me-2 btn-edit" data-id="' + item
                            .item_id +
                        '" data-category="grocery" title="Edit"><i class="fas fa-edit"></i></button>' : '');
                    rows += (isEnabled ? '<button class="text-red-600 hover:text-red-800 btn-delete" data-id="' +
                        item.item_id + '" title="Delete"><i class="fas fa-trash"></i></button>' : '');
                    rows += '</td>';
                    rows += '</tr>';
                });
            } else {
                rows =
                    '<tr><td colspan="9" class="px-6 py-4 text-center text-gray-500">No grocery items in inventory</td></tr>';
            }

            $('#groceryTableBody').html(rows);
            $('#groceryTotalQty').text(totalQty);
        }

        /**
         * Open the Item Details Modal and load data
         */
        function openItemDetailsModal(itemId, productId, productName, qtySold, po, price, totalSales, notes) {
            const baseUrl = '<?= base_url() ?>';

            // Normalize values
            qtySold = parseInt(qtySold) || 0;
            po = parseInt(po) || 0;
            price = parseFloat(price) || 0;
            totalSales = parseFloat(totalSales) || 0;

            // Calculate totals
            const totalUnits = qtySold + po;
            const srp = price;
            const salesRevenue = qtySold * srp;

            // Set header and basic info
            $('#itemDetailsProductName').text(productName || 'Product Details');
            $('#itemDetailsPO').text(po);
            $('#itemDetailsQtySold').text(qtySold);
            $('#itemDetailsTotalUnits').text(totalUnits);
            $('#itemDetailsSRP').text('₱' + srp.toFixed(2));
            $('#itemDetailsTotalSales').text('₱' + salesRevenue.toFixed(2));

            // Set notes or placeholder
            if (String(notes) && String(notes).trim()) {
                $('#itemDetailsNotes').text(notes);
            } else {
                $('#itemDetailsNotes').html('<span class="text-gray-400">No notes added</span>');
            }

            // Show loading state for materials
            $('#itemDetailsMaterialsList').html(
                '<p class="text-sm text-gray-500 text-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i> Loading materials...</p>'
            );

            // Store values for use in material calculations
            window.currentItemData = {
                itemId: itemId,
                productId: productId,
                qtySold: qtySold,
                po: po,
                totalUnits: totalUnits,
                price: price,
                salesRevenue: salesRevenue
            };

            // Open modal
            $('#itemDetailsModal').removeClass('hidden');

            // Fetch and calculate materials if total units > 0 and product has recipe
            if (totalUnits > 0 && productId) {
                fetchProductRecipe(productId, totalUnits);
            } else {
                $('#itemDetailsMaterialsList').html(
                    '<p class="text-sm text-gray-500 text-center py-4">No materials data available</p>');
                updateProfitAnalysis(0);
            }
        }

        /**
         * Fetch product recipe from backend
         */
        function fetchProductRecipe(productId, totalUnits) {
            const baseUrl = '<?= base_url() ?>';

            $.ajax({
                url: baseUrl + 'Inventory/GetProductRecipe/' + productId,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success && response.recipe && response.recipe.length > 0) {
                        displayMaterialsUsed(response.recipe, totalUnits, response);
                    } else {
                        $('#itemDetailsMaterialsList').html(
                            '<p class="text-sm text-gray-500 text-center py-4">No raw materials configured for this product</p>'
                        );
                        updateProfitAnalysis(0);
                    }
                },
                error: function (xhr, status, error) {
                    $('#itemDetailsMaterialsList').html(
                        '<p class="text-sm text-red-500 text-center py-4">Error loading materials</p>');
                    console.error('Error fetching recipe:', error);
                    updateProfitAnalysis(0);
                }
            });
        }

        /**
         * Display materials used with calculated quantities and costs
         */
        function displayMaterialsUsed(recipe, totalUnits, productData) {
            let html = '';
            let totalMaterialsCost = 0;

            const category = (productData && productData.category ? String(productData.category) : '').toLowerCase();
            const traysPerYield = parseInt(productData && productData.trays_per_yield) || 0;
            const piecesPerYield = parseInt(productData && productData.pieces_per_yield) || 0;
            let piecesPerBatch = 1;

            if (category === 'drinks' || category === 'grocery') {
                piecesPerBatch = 1;
            } else if (traysPerYield > 0 && piecesPerYield > 0) {
                piecesPerBatch = traysPerYield * piecesPerYield;
            } else if (piecesPerYield > 0) {
                piecesPerBatch = piecesPerYield;
            }

            const yieldsNeeded = piecesPerBatch > 0 ? (totalUnits / piecesPerBatch) : totalUnits;

            if (!recipe || recipe.length === 0) {
                $('#itemDetailsMaterialsList').html(
                    '<p class="text-sm text-gray-500 text-center py-4">No raw materials configured</p>');
                updateProfitAnalysis(0);
                return;
            }

            recipe.forEach(function (material) {
                const quantityNeeded = parseFloat(material.quantity_needed) || 0;
                const unit = material.unit || '';
                const materialName = material.material_name || 'Unknown Material';
                const costPerUnit = parseFloat(material.cost_per_unit) || 0;

                // Calculate total quantity and cost (quantity per yield × yields needed)
                const totalQuantity = quantityNeeded * yieldsNeeded;
                const materialCost = (totalQuantity * costPerUnit).toFixed(2);
                totalMaterialsCost += parseFloat(materialCost);

                html += '<div class="bg-gray-50 rounded-lg p-3 border border-gray-200">';
                html += '<div class="space-y-2">';
                html += '<div class="flex items-start justify-between">';
                html += '<div class="flex-1">';
                html += '<p class="text-sm font-medium text-gray-800">' + escapeHtml(materialName) + '</p>';
                html += '<p class="text-xs text-gray-500 mt-1">₱' + costPerUnit.toFixed(2) + ' per ' + escapeHtml(
                    unit) + '</p>';
                html += '</div>';
                html += '<div class="text-right">';
                html += '<p class="text-sm font-bold text-gray-800">' + totalQuantity.toFixed(2) + ' ' + escapeHtml(
                    unit) + '</p>';
                html += '</div>';
                html += '</div>';
                html += '<div class="border-t border-gray-300 pt-2 flex items-center justify-between">';
                html += '<p class="text-xs text-gray-600">Material Cost:</p>';
                html += '<p class="text-sm font-semibold text-orange-600">₱' + parseFloat(materialCost).toFixed(2) +
                    '</p>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
            });

            html += '<div class="mt-4 pt-4 border-t-2 border-orange-300">';
            html += '<div class="flex items-center justify-between bg-orange-50 rounded-lg p-3">';
            html += '<p class="text-sm font-semibold text-gray-800">Total Materials Cost:</p>';
            html += '<p class="text-lg font-bold text-orange-600">₱' + totalMaterialsCost.toFixed(2) + '</p>';
            html += '</div>';
            html += '</div>';

            $('#itemDetailsMaterialsList').html(html);
            updateProfitAnalysis(totalMaterialsCost);
        }

        /**
         * Update profit/loss analysis section
         */
        function updateProfitAnalysis(totalMaterialsCost) {
            if (!window.currentItemData) return;

            const data = window.currentItemData;
            const salesRevenue = data.salesRevenue;
            const materialsCost = parseFloat(totalMaterialsCost) || 0;
            const profit = salesRevenue - materialsCost;
            const profitMargin = salesRevenue > 0 ? ((profit / salesRevenue) * 100).toFixed(2) : 0;

            // Update costs section
            $('#itemDetailsTotalMaterialsCost').text('₱' + materialsCost.toFixed(2));

            // Update profit section with color coding
            const profitDisplay = $('#itemDetailsProfit');
            profitDisplay.text('₱' + profit.toFixed(2));

            if (profit > 0) {
                profitDisplay.removeClass('text-red-600 text-gray-600').addClass('text-green-600');
            } else if (profit < 0) {
                profitDisplay.removeClass('text-green-600 text-gray-600').addClass('text-red-600');
            } else {
                profitDisplay.removeClass('text-green-600 text-red-600').addClass('text-gray-600');
            }

            // Update margin
            $('#itemDetailsProfitMargin').text(profitMargin + '%');
            const marginDisplay = $('#itemDetailsProfitMargin');
            if (profit > 0) {
                marginDisplay.removeClass('text-red-600 text-gray-600').addClass('text-green-600');
            } else if (profit < 0) {
                marginDisplay.removeClass('text-green-600 text-gray-600').addClass('text-red-600');
            } else {
                marginDisplay.removeClass('text-green-600 text-red-600').addClass('text-gray-600');
            }
        }

        /**
         * Escape HTML special characters
         */
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function updateGrandTotals(items) {
            let grandQty = 0;

            items.forEach(function (item) {
                grandQty += parseInt(item.quantity_sold) || 0;
            });

            $('#grandTotalQty').text(grandQty);
        }

        // Edit Inventory Item - Open Modal
        $(document).on('click', '.btn-edit', function () {
            if (enforceInventoryLock()) {
                return;
            }
            const itemId = $(this).data('id');

            // Always get data from stored items array (more reliable)
            const item = allInventoryItems.find(i => i.item_id == itemId);

            if (item) {
                const category = (item.category || '').toLowerCase();
                const isAdjustmentMode = (category === 'bakery' || category === 'grocery');
                const beginningStock = parseInt(item.beginning_stock) || 0;
                const pullOutQty = parseInt(item.pull_out_quantity) || 0;
                const endingStock = parseInt(item.ending_stock) || 0;

                // Store item ID and populate modal
                $('#editItemId').val(itemId);
                $('#editCategory').val(category);
                $('#editAdjustmentMode').val(isAdjustmentMode ? '1' : '0');
                $('#editProductName').text(item.product_name || 'N/A');
                $('#editOldBeginningStock').val(beginningStock);
                $('#editOldPullOutQuantity').val(pullOutQty);
                $('#editOldEndingStock').val(endingStock);
                const quantitySold = parseInt(item.quantity_sold_db) || parseInt(item.quantity_sold) || Math.max(0,
                    beginningStock - pullOutQty - endingStock);
                $('#editOldQuantitySold').val(quantitySold);

                if (isAdjustmentMode) {
                    $('#editBeginningLabel').text('Beginning Stock ');
                    $('#editPullOutLabel').text('Pull Out Quantity (add only)');
                    $('#editEndingLabel').text('Ending Stock ');

                    $('#editAdjustmentGuide').removeClass('hidden');
                    $('#editBeginningHint').text('Enter adjustment only (e.g. +10 or -5).');
                    $('#editPullOutHint').text('Enter added PO only (e.g. +5). No subtraction.');
                    $('#editEndingHint').text('Enter the actual final ending stock count.');

                    // Beginning/Pull Out are adjustments; Ending is absolute final value.
                    $('#editBeginningStock').val(0).removeAttr('min');
                    $('#editPullOutQuantity').val(0).attr('min', 0);
                    $('#editEndingStock').val(endingStock).attr('min', 0).prop('readonly', false).removeClass(
                        'bg-gray-50 cursor-not-allowed');
                    $('#editEndingGroup').removeClass('hidden');
                } else {
                    $('#editBeginningLabel').text('Beginning Stock');
                    $('#editPullOutLabel').text('Pull Out Quantity');
                    $('#editEndingLabel').text('Ending Stock');

                    $('#editAdjustmentGuide').addClass('hidden');
                    $('#editBeginningHint').text('');
                    $('#editPullOutHint').text('');
                    $('#editEndingHint').text('Enter the actual final ending stock count.');

                    $('#editBeginningStock').val(beginningStock).attr('min', 0);
                    $('#editPullOutQuantity').val(pullOutQty).attr('min', 0);
                    $('#editEndingStock').val(endingStock).attr('min', 0).prop('readonly', false).removeClass(
                        'bg-gray-50 cursor-not-allowed');
                    $('#editEndingGroup').addClass('hidden');
                }

                $('#editNotes').val(item.notes || '');

                // Populate distribution and carryover info
                const distQtyFromDistribution = getTodayDistributionPiecesForProduct(item.product_id);
                const distQty = distQtyFromDistribution > 0 ? distQtyFromDistribution : (parseInt(item
                    .distribution_qty) || 0);
                const carryQty = parseInt(carryoverData[item.product_id]) || 0;
                $('#editDistributionQty').val(distQty);
                $('#editCarryoverQty').val(carryQty);

                resetEditPreviewUiState();

                // Update the distribution display and notes requirement
                updateBeginningStockDisplay();
                updateRemainingPreview();

                // Show modal
                $('#editInventoryModal').removeClass('hidden');
            } else {
                showToast('error', 'Could not find item data', 2000);
            }
        });

        // +/- buttons for beginning stock
        let editPreviewDebounceTimer = null;
        let editPreviewUiState = {
            infoKey: '',
            warningKey: '',
            notesRequired: null,
            remainingHint: '',
            remainingValue: null
        };

        function resetEditPreviewUiState() {
            editPreviewUiState = {
                infoKey: '',
                warningKey: '',
                notesRequired: null,
                remainingHint: '',
                remainingValue: null
            };
        }

        function runEditPreviewUpdate() {
            updateBeginningStockDisplay();
            updateRemainingPreview();
        }

        function scheduleEditPreviewUpdate(delayMs = 60) {
            if (editPreviewDebounceTimer) {
                clearTimeout(editPreviewDebounceTimer);
            }

            editPreviewDebounceTimer = setTimeout(function () {
                editPreviewDebounceTimer = null;
                runEditPreviewUpdate();
            }, delayMs);
        }

        $('#btnDecreaseBeginning').on('click', function () {
            const current = parseInt($('#editBeginningStock').val()) || 0;
            const isAdjustmentMode = $('#editAdjustmentMode').val() === '1';
            $('#editBeginningStock').val(isAdjustmentMode ? (current - 1) : Math.max(0, current - 1));
            runEditPreviewUpdate();
        });

        $('#btnIncreaseBeginning').on('click', function () {
            const current = parseInt($('#editBeginningStock').val()) || 0;
            $('#editBeginningStock').val(current + 1);
            runEditPreviewUpdate();
        });

        $('#btnDecreasePullOut').on('click', function () {
            const current = parseInt($('#editPullOutQuantity').val()) || 0;
            $('#editPullOutQuantity').val(Math.max(0, current - 1));
            runEditPreviewUpdate();
        });

        $('#btnIncreasePullOut').on('click', function () {
            const current = parseInt($('#editPullOutQuantity').val()) || 0;
            $('#editPullOutQuantity').val(current + 1);
            runEditPreviewUpdate();
        });

        // Also update on manual input change
        $('#editBeginningStock').on('input change', function () {
            scheduleEditPreviewUpdate();
        });

        $('#editPullOutQuantity, #editEndingStock').on('input change', function () {
            scheduleEditPreviewUpdate();
        });

        function updateRemainingPreview() {
            const isAdjustmentMode = $('#editAdjustmentMode').val() === '1';

            const oldBeginning = parseInt($('#editOldBeginningStock').val()) || 0;
            const oldPullOut = parseInt($('#editOldPullOutQuantity').val()) || 0;
            const oldEnding = parseInt($('#editOldEndingStock').val()) || 0;
            const oldQtySold = parseInt($('#editOldQuantitySold').val()) || 0;

            const beginningInput = parseInt($('#editBeginningStock').val()) || 0;
            const pullOutInput = parseInt($('#editPullOutQuantity').val()) || 0;
            const endingInput = parseInt($('#editEndingStock').val()) || 0;

            let projectedRemaining = 0;

            if (isAdjustmentMode) {
                const projectedBeginning = oldBeginning + beginningInput;
                const projectedPullOut = oldPullOut + pullOutInput;
                projectedRemaining = endingInput;
                const discrepancy = projectedBeginning - (projectedPullOut + oldQtySold + projectedRemaining);
                const adjustedQtySold = Math.max(0, oldQtySold + discrepancy);

                const discrepancyLabel = discrepancy > 0 ? ('+' + discrepancy) : String(discrepancy);
                const discrepancyClass = discrepancy > 0
                    ? 'text-green-700 border-green-200 bg-green-50'
                    : (discrepancy < 0 ? 'text-red-700 border-red-200 bg-red-50' : 'text-gray-700 border-gray-200 bg-gray-50');

                const nextHint =
                    '<span class="inline-flex items-center px-2 py-0.5 rounded border text-[11px] font-medium text-gray-700 border-gray-200 bg-white mr-1 mb-1">Current End: ' + oldEnding + '</span>' +
                    '<span class="inline-flex items-center px-2 py-0.5 rounded border text-[11px] font-medium text-blue-700 border-blue-200 bg-blue-50 mr-1 mb-1">New End: ' + Math.max(0, projectedRemaining) + '</span>' +
                    '<span class="inline-flex items-center px-2 py-0.5 rounded border text-[11px] font-medium ' + discrepancyClass + ' mr-1 mb-1">Discrepancy: ' + discrepancyLabel + '</span>' +
                    '<span class="inline-flex items-center px-2 py-0.5 rounded border text-[11px] font-medium text-indigo-700 border-indigo-200 bg-indigo-50 mb-1">Adjusted Qty Sold: ' + adjustedQtySold + '</span>';
                if (editPreviewUiState.remainingHint !== nextHint) {
                    $('#editRemainingHint').html(nextHint);
                    editPreviewUiState.remainingHint = nextHint;
                }
            } else {
                // In non-adjustment mode, ending is preserved and qty sold is reconciled.
                projectedRemaining = oldEnding;
                const projectedQtySold = Math.max(0, beginningInput - pullOutInput - projectedRemaining);
                const nextHint = 'Ending stays at current count (' + oldEnding + '). Qty Sold auto-recomputes to ' +
                    projectedQtySold + '.';
                if (editPreviewUiState.remainingHint !== nextHint) {
                    $('#editRemainingHint').text(nextHint);
                    editPreviewUiState.remainingHint = nextHint;
                }
            }

            const nextRemainingValue = Math.max(0, projectedRemaining);
            if (editPreviewUiState.remainingValue !== nextRemainingValue) {
                $('#editRemainingPreview').val(nextRemainingValue);
                editPreviewUiState.remainingValue = nextRemainingValue;
            }
        }

        /**
         * Update the distribution limit display and notes requirement
         * based on current beginning stock vs expected (distribution + carryover).
         */
        function updateBeginningStockDisplay() {
            const isAdjustmentMode = $('#editAdjustmentMode').val() === '1';
            const distQty = parseInt($('#editDistributionQty').val()) || 0;
            const carryQty = parseInt($('#editCarryoverQty').val()) || 0;
            const expected = distQty + carryQty;
            const oldBeginning = parseInt($('#editOldBeginningStock').val()) || 0;
            const beginningInput = parseInt($('#editBeginningStock').val()) || 0;
            const currentBeginning = isAdjustmentMode ? (oldBeginning + beginningInput) : beginningInput;

            // Distribution limit info bar
            const infoKey = expected + '|' + distQty + '|' + carryQty;
            if (expected > 0) {
                let infoText = '';
                if (distQty > 0 && carryQty > 0) {
                    infoText = 'Distribution: <strong>' + distQty + '</strong> pcs · Carryover: <strong>' + carryQty +
                        '</strong> pcs · Expected: <strong>' + expected + '</strong> pcs';
                } else if (distQty > 0) {
                    infoText = 'Distribution: <strong>' + distQty + '</strong> pcs · Expected: <strong>' + expected +
                        '</strong> pcs';
                } else {
                    infoText = 'Carryover: <strong>' + carryQty + '</strong> pcs · Expected: <strong>' + expected +
                        '</strong> pcs';
                }
                if (editPreviewUiState.infoKey !== infoKey) {
                    $('#editDistInfoText').html(infoText);
                    $('#editDistributionInfo').removeClass('hidden');
                    editPreviewUiState.infoKey = infoKey;
                }
            } else {
                if (editPreviewUiState.infoKey !== 'hidden') {
                    $('#editDistributionInfo').addClass('hidden');
                    editPreviewUiState.infoKey = 'hidden';
                }
            }

            // Over/Under warning and notes requirement
            if (expected > 0 && currentBeginning !== expected) {
                const delta = currentBeginning - expected;
                let warningText = '';
                if (delta > 0) {
                    warningText = 'Exceeds expected by <strong>' + delta + '</strong> — note required';
                } else {
                    warningText = 'Short by <strong>' + Math.abs(delta) + '</strong> — note required';
                }
                if (editPreviewUiState.warningKey !== warningText) {
                    $('#editStockWarningText').html(warningText);
                    $('#editStockWarning').removeClass('hidden');
                    editPreviewUiState.warningKey = warningText;
                }

                // Make notes required
                if (editPreviewUiState.notesRequired !== true) {
                    $('#editNotes').attr('required', true);
                    $('#editNotes').attr('placeholder', 'Explain why beginning stock differs from expected');
                    $('#editNotesLabel').html('Notes <span class="text-red-500">*</span>');
                    $('#editNotesHint').text('Required — explain the stock adjustment').removeClass('text-gray-400')
                        .addClass(
                            'text-red-500');
                    editPreviewUiState.notesRequired = true;
                }
                if (!$('#editNotes').val()) {
                    $('#editNotes').addClass('border-red-300 focus:border-red-400 focus:ring-red-200');
                } else {
                    $('#editNotes').removeClass('border-red-300 focus:border-red-400 focus:ring-red-200');
                }
            } else {
                // No deviation or no expected baseline
                if (editPreviewUiState.warningKey !== 'hidden') {
                    $('#editStockWarning').addClass('hidden');
                    editPreviewUiState.warningKey = 'hidden';
                }
                if (editPreviewUiState.notesRequired !== false) {
                    $('#editNotes').removeAttr('required');
                    $('#editNotes').attr('placeholder', 'Add notes (optional)');
                    $('#editNotesLabel').text('Notes');
                    $('#editNotesHint').text('Optional — max 500 characters').removeClass('text-red-500').addClass(
                        'text-gray-400');
                    editPreviewUiState.notesRequired = false;
                }
                $('#editNotes').removeClass('border-red-300 focus:border-red-400 focus:ring-red-200');
            }
        }

        // Update notes border styling on input
        $('#editNotes').on('input', function () {
            if ($('#editNotes').is('[required]') && !$(this).val().trim()) {
                $(this).addClass('border-red-300 focus:border-red-400 focus:ring-red-200');
            } else {
                $(this).removeClass('border-red-300 focus:border-red-400 focus:ring-red-200');
            }
        });

        // Close Edit Modal
        $('#editInventoryModalClose, #editInventoryModalCancel').on('click', function () {
            $('#editInventoryModal').addClass('hidden');
            $('#editInventoryForm')[0].reset();
            resetEditPreviewUiState();
            $('#editAdjustmentGuide').addClass('hidden');
            $('#editBeginningLabel').text('Beginning Stock');
            $('#editPullOutLabel').text('Pull Out Quantity');
            $('#editEndingLabel').text('Ending Stock');
            $('#editBeginningHint').text('');
            $('#editPullOutHint').text('');
            $('#editEndingHint').text('Enter the actual final ending stock count.');
            $('#editRemainingPreview').val('');
            $('#editRemainingHint').text('Live preview while editing fields above.');
            $('#editEndingGroup').addClass('hidden');
            $('#editBeginningStock').attr('min', 0);
            $('#editPullOutQuantity').attr('min', 0);
            $('#editEndingStock').prop('readonly', false).removeClass('bg-gray-50 cursor-not-allowed');
            // Reset distribution display state
            $('#editDistributionInfo').addClass('hidden');
            $('#editStockWarning').addClass('hidden');
            $('#editNotes').removeAttr('required');
            $('#editNotesLabel').text('Notes');
            $('#editNotesHint').text('Optional — max 500 characters').removeClass('text-red-500').addClass(
                'text-gray-400');
            $('#editNotes').removeClass('border-red-300 focus:border-red-400 focus:ring-red-200');
        });

        $(document).on('click', '.btn-toggle-enabled', function () {
            if (enforceInventoryLock()) {
                return;
            }
            const itemId = $(this).data('id');
            const currentEnabled = parseInt($(this).data('enabled'));
            const newEnabled = currentEnabled === 1 ? 0 : 1;
            const baseUrl = '<?= base_url() ?>';

            $.ajax({
                url: baseUrl + 'Inventory/ToggleStockItem/' + itemId,
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    is_enabled: newEnabled
                }),
                success: function (response) {
                    if (response.success) {
                        showToast('success', response.message, 2000);
                        fetchAllStockitems();
                    } else {
                        showToast('error', response.message, 2000);
                    }
                },
                error: function (xhr, status, error) {
                    showToast('danger', 'Error toggling item: ' + (xhr.responseJSON?.message || error),
                        2000);
                }
            });
        });

        $('#editInventoryForm').on('submit', function (e) {
            e.preventDefault();

            if (enforceInventoryLock()) {
                return;
            }

            const submitBtn = $('#btnSubmitEditInventory');
            if (submitBtn.prop('disabled')) {
                return;
            }

            const originalSubmitHtml = submitBtn.html();
            const restoreSubmitButton = function () {
                submitBtn.prop('disabled', false)
                    .removeClass('opacity-70 cursor-not-allowed')
                    .html(originalSubmitHtml);
            };

            submitBtn.prop('disabled', true)
                .addClass('opacity-70 cursor-not-allowed')
                .html('<i class="fas fa-spinner fa-spin mr-2"></i>Updating...');

            const itemId = $('#editItemId').val();
            const isAdjustmentMode = $('#editAdjustmentMode').val() === '1';
            const beginningInput = parseInt($('#editBeginningStock').val()) || 0;
            const pullOutInput = parseInt($('#editPullOutQuantity').val()) || 0;
            const endingInput = parseInt($('#editEndingStock').val()) || 0;
            const notes = $('#editNotes').val();

            const distQty = parseInt($('#editDistributionQty').val()) || 0;
            const carryQty = parseInt($('#editCarryoverQty').val()) || 0;
            const expected = distQty + carryQty;

            let payload;

            if (isAdjustmentMode) {
                const oldBeginning = parseInt($('#editOldBeginningStock').val()) || 0;
                const oldPullOut = parseInt($('#editOldPullOutQuantity').val()) || 0;

                const projectedBeginning = oldBeginning + beginningInput;
                const projectedPullOut = oldPullOut + pullOutInput;
                const projectedEnding = endingInput;

                if (pullOutInput < 0) {
                    showToast('warning', 'Pull Out only accepts positive additions.',
                        2500);
                    restoreSubmitButton();
                    return;
                }

                if (endingInput < 0) {
                    showToast('warning', 'Ending stock cannot be negative.', 2500);
                    restoreSubmitButton();
                    return;
                }

                if (projectedBeginning < 0 || projectedPullOut < 0 || projectedEnding < 0) {
                    showToast('warning', 'Adjustment results cannot go below zero', 2500);
                    restoreSubmitButton();
                    return;
                }

                if (expected > 0 && projectedBeginning !== expected && !notes.trim()) {
                    showToast('warning', 'Notes are required when beginning stock differs from expected (' +
                        expected + ')', 3000);
                    $('#editNotes').focus();
                    restoreSubmitButton();
                    return;
                }

                payload = {
                    adjustment_mode: true,
                    beginning_stock: beginningInput,
                    pull_out_quantity: pullOutInput,
                    ending_stock: endingInput,
                    notes: notes
                };
            } else {
                if (beginningInput < 0 || pullOutInput < 0) {
                    showToast('warning', 'Values cannot be negative', 2000);
                    restoreSubmitButton();
                    return;
                }

                if (expected > 0 && beginningInput !== expected && !notes.trim()) {
                    showToast('warning', 'Notes are required when beginning stock differs from expected (' +
                        expected + ')', 3000);
                    $('#editNotes').focus();
                    restoreSubmitButton();
                    return;
                }

                payload = {
                    beginning_stock: beginningInput,
                    pull_out_quantity: pullOutInput,
                    notes: notes
                };
            }

            const baseUrl = '<?= base_url() ?>';
            $.ajax({
                url: baseUrl + 'Inventory/UpdateStockItem/' + itemId,
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify(payload),
                success: function (response) {
                    if (response.success) {
                        showToast('success', response.message, 2000);
                        const patched = applyEditedInventoryItemLocally(itemId, payload,
                            isAdjustmentMode);
                        if (patched) {
                            loadInventory(allInventoryItems, {
                                fetchCarryover: false
                            });
                            // Re-sync in the background so totals remain source-of-truth accurate.
                            setTimeout(fetchAllStockitems, 700);
                        } else {
                            fetchAllStockitems(); // Fallback when local cache is missing
                        }
                        $('#editInventoryModal').addClass('hidden');
                        $('#editInventoryForm')[0].reset();
                    } else {
                        showToast('error', response.message, 2000);
                    }
                },
                error: function (xhr, status, error) {
                    // Show detailed insufficient materials modal
                    if (xhr.responseJSON && xhr.responseJSON.insufficient_materials) {
                        showInsufficientStockModal(xhr.responseJSON);
                    } else {
                        showToast('danger', 'Error updating inventory: ' + (xhr.responseJSON?.message ||
                            error), 2000);
                    }
                    console.log(xhr);
                },
                complete: function () {
                    restoreSubmitButton();
                }
            });
        });

        function deleteInventory() {
            const baseUrl = '<?= base_url() ?>';
            $.ajax({
                url: baseUrl + 'Inventory/DeleteInventory',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    inventory_id: inventoryId
                }),
                success: function (response) {
                    if (response.success) {
                        showToast('success', response.message, 2000);
                        if ($('#btnSendInventoryReport').length) {
                            $('#btnSendInventoryReport').addClass('hidden').removeClass('sm:inline-flex');
                        }
                        checkIfInventoryExists();
                        checkIfDistributionExists();
                    } else {
                        showToast('error', response.message, 2000);
                    }
                },
                error: function (xhr, status, error) {
                    showToast('danger', xhr.responseJSON.message, 2000);
                    console.log(xhr);
                }
            });
        }

        // Add Product to Inventory functionality
        $('#btnAddProductToInventory, #btnAddProductToInventoryMobile').on('click', function () {
            if (enforceInventoryLock()) {
                return;
            }
            loadAvailableProducts();
            $('#addProductModal').removeClass('hidden');
        });

        // Close Add Product Modal
        $('#addProductModalClose, #addProductModalCancel').on('click', function () {
            $('#addProductModal').addClass('hidden');
            $('#addProductForm')[0].reset();
        });

        // Load available products (not yet in inventory)
        function loadAvailableProducts() {
            const baseUrl = '<?= base_url() ?>';
            $.ajax({
                url: baseUrl + 'Inventory/GetAvailableProducts',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    const select = $('#selectProduct');
                    select.html('<option value="">-- Select a product --</option>');

                    if (response.success && response.data.length > 0) {
                        response.data.forEach(function (product) {
                            let categoryLabel = 'Unknown';
                            if (product.category === 'bakery') {
                                categoryLabel = 'Bakery';
                            } else if (product.category === 'drinks') {
                                categoryLabel = 'Drinks';
                            } else if (product.category === 'grocery') {
                                categoryLabel = 'Grocery';
                            } else if (product.category === 'dough') {
                                categoryLabel = 'Dough';
                            } else if (product.category) {
                                categoryLabel = product.category.charAt(0).toUpperCase() + product
                                    .category.slice(1);
                            }
                            select.append(
                                `<option value="${product.product_id}">[${categoryLabel}] ${product.product_name}</option>`
                            );
                        });
                        $('#noProductsMessage').addClass('hidden');
                        $('#btnSubmitAddProduct').prop('disabled', false);
                    } else {
                        $('#noProductsMessage').removeClass('hidden');
                        $('#btnSubmitAddProduct').prop('disabled', true);
                    }
                },
                error: function (xhr, status, error) {
                    showToast('danger', 'Error loading products: ' + error, 2000);
                }
            });
        }

        // Mobile Search functionality
        $('#mobileSearchInput').on('input', function () {
            const searchTerm = $(this).val().toLowerCase().trim();

            if (searchTerm === '') {
                filteredItems = [...allInventoryItems];
            } else {
                filteredItems = allInventoryItems.filter(item => {
                    return (item.product_name && item.product_name.toLowerCase().includes(searchTerm)) ||
                        (item.category && item.category.toLowerCase().includes(searchTerm));
                });
            }

            currentPage = 1;
            renderMobileCards();
        });

        // Render mobile cards for each category tab
        function renderMobileCards() {
            const bakeryItems = filteredItems.filter(i => i.category === 'bakery');
            const drinksItems = filteredItems.filter(i => i.category === 'drinks');
            const groceryItems = filteredItems.filter(i => i.category === 'grocery');

            // Bakery cards
            let bakeryCards = '';
            if (bakeryItems.length > 0) {
                bakeryItems.forEach(function (item) {
                    bakeryCards += renderMobileCard(item, 'bakery');
                });
            } else {
                bakeryCards =
                    '<div class="bg-white rounded border border-gray-200 p-6 text-center text-gray-500 text-sm">No bakery items in inventory</div>';
            }
            $('#bakeryMobileCards').html(bakeryCards);

            // Drinks cards
            let drinksCards = '';
            if (drinksItems.length > 0) {
                drinksItems.forEach(function (item) {
                    drinksCards += renderMobileCard(item, 'drinks');
                });
            } else {
                drinksCards =
                    '<div class="bg-white rounded border border-gray-200 p-6 text-center text-gray-500 text-sm">No drinks in inventory</div>';
            }
            $('#drinksMobileCards').html(drinksCards);

            // Grocery cards
            let groceryCards = '';
            if (groceryItems.length > 0) {
                groceryItems.forEach(function (item) {
                    groceryCards += renderMobileCard(item, 'grocery');
                });
            } else {
                groceryCards =
                    '<div class="bg-white rounded border border-gray-200 p-6 text-center text-gray-500 text-sm">No grocery items in inventory</div>';
            }
            $('#groceryMobileCards').html(groceryCards);
        }

        function renderMobileCard(item, category) {
            const price = category === 'bakery' && item.selling_price_per_piece > 0 ?
                item.selling_price_per_piece :
                (item.srp ?? item.selling_price);
            const formattedPrice = '₱' + parseFloat(price || 0).toFixed(2);
            const ending_stock = parseInt(item.ending_stock) || 0;
            const isEnabled = parseInt(item.is_enabled) === 1;
            const notes = item.notes || '';

            let borderColor = 'border-gray-200';
            if (category === 'bakery') borderColor = 'border-l-2 border-l-amber-400 border-gray-200';
            else if (category === 'drinks') borderColor = 'border-l-2 border-l-blue-400 border-gray-200';
            else if (category === 'grocery') borderColor = 'border-l-2 border-l-emerald-400 border-gray-200';

            let card = '<div class="bg-white rounded border ' + borderColor + ' p-3' + (!isEnabled ? ' opacity-50' : '') +
                '" data-id="' + item.item_id + '">';
            card += '  <div class="flex items-center justify-between mb-2">';
            card += '    <span class="text-sm text-gray-800">' + (item.item || item.product_name || 'N/A') + (!isEnabled ?
                ' <span class="text-xs text-red-400 font-medium">(Disabled)</span>' : '') + '</span>';
            card += '    <span class="text-sm font-medium text-gray-700">' + formattedPrice + '</span>';
            card += '  </div>';

            if (category === 'drinks') {
                card += '  <div class="flex items-center gap-3 text-xs text-gray-500 mb-2">';
                card += '    <span>Qty Sold: <span class="text-gray-700">' + (parseInt(item.quantity_sold) || 0) +
                    '</span></span>';
                card += '    <span class="ml-auto">Sales: <span class="text-gray-700 font-medium">₱' + ((parseFloat(item
                    .sales ?? item.total_sales ?? 0) || 0).toFixed(2)) + '</span></span>';
                card += '  </div>';
            } else {
                card += '  <div class="flex items-center gap-3 text-xs text-gray-500 mb-2">';
                card += '    <span>Begin: <span class="text-gray-700">' + (item.beginning_stock || 0) + '</span></span>';
                card += '    <span>Out: <span class="text-gray-700">' + (item.pull_out_quantity || 0) + '</span></span>';
                card += '    <span>End: <span class="text-gray-700">' + ending_stock + '</span></span>';
                card += '    <span class="ml-auto">Sales: <span class="text-gray-700 font-medium">₱' + (parseFloat(item
                    .total_sales).toFixed(2) || 0) + '</span></span>';
                card += '  </div>';
            }

            if (notes && category !== 'drinks') {
                card += '  <div class="text-xs text-gray-500 mb-2 px-2 py-1.5 bg-gray-50 rounded">';
                card += '    <i class="fas fa-sticky-note mr-1 text-amber-400"></i>' + notes;
                card += '  </div>';
            }

            card += '  <div class="flex gap-2 pt-2 border-t border-gray-100">';
            card += '    <button class="flex-1 text-xs py-1 btn-toggle-enabled ' + (isEnabled ?
                'text-green-600 hover:text-green-700' : 'text-gray-400 hover:text-gray-600') + '" data-id="' + item
                    .item_id + '" data-enabled="' + (isEnabled ? '1' : '0') + '">';
            card += '      <i class="fas ' + (isEnabled ? 'fa-toggle-on' : 'fa-toggle-off') + ' mr-1"></i>' + (isEnabled ?
                'Enabled' : 'Disabled');
            card += '    </button>';
            if (isEnabled && category !== 'drinks') {
                card += '    <button class="flex-1 text-xs text-gray-500 hover:text-amber-600 py-1 btn-edit" data-id="' +
                    item.item_id + '">';
                card += '      <i class="fas fa-edit mr-1"></i>Edit';
                card += '    </button>';
            }
            if (isEnabled) {
                card += '    <button class="flex-1 text-xs text-gray-500 hover:text-red-600 py-1 btn-delete" data-id="' +
                    item.item_id + '">';
                card += '      <i class="fas fa-trash mr-1"></i>Delete';
                card += '    </button>';
            }
            card += '  </div>';
            card += '</div>';

            return card;
        }

        // Render mobile pagination
        function renderMobilePagination(totalPages, totalItems, startIndex, endIndex) {
            let pagination = '';

            if (totalPages > 1) {
                // Previous button
                pagination += '<button class="px-3 py-2 text-sm rounded-lg border ' +
                    (currentPage === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' :
                        'bg-white text-gray-700 hover:bg-gray-50') +
                    '" ' + (currentPage === 1 ? 'disabled' : '') + ' data-page="prev">';
                pagination += '<i class="fas fa-chevron-left"></i>';
                pagination += '</button>';

                // Page numbers
                const maxVisiblePages = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }

                if (startPage > 1) {
                    pagination +=
                        '<button class="px-3 py-2 text-sm rounded-lg border bg-white text-gray-700 hover:bg-gray-50" data-page="1">1</button>';
                    if (startPage > 2) {
                        pagination += '<span class="px-2 py-2 text-gray-400">...</span>';
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    pagination += '<button class="px-3 py-2 text-sm rounded-lg border ' +
                        (i === currentPage ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50') +
                        '" data-page="' + i + '">' + i + '</button>';
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        pagination += '<span class="px-2 py-2 text-gray-400">...</span>';
                    }
                    pagination +=
                        '<button class="px-3 py-2 text-sm rounded-lg border bg-white text-gray-700 hover:bg-gray-50" data-page="' +
                        totalPages + '">' + totalPages + '</button>';
                }

                // Next button
                pagination += '<button class="px-3 py-2 text-sm rounded-lg border ' +
                    (currentPage === totalPages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' :
                        'bg-white text-gray-700 hover:bg-gray-50') +
                    '" ' + (currentPage === totalPages ? 'disabled' : '') + ' data-page="next">';
                pagination += '<i class="fas fa-chevron-right"></i>';
                pagination += '</button>';
            }

            $('#mobilePagination').html(pagination);

            // Page info
            if (totalItems > 0) {
                $('#mobilePageInfo').text('Showing ' + (startIndex + 1) + ' to ' + endIndex + ' of ' + totalItems +
                    ' entries');
            } else {
                $('#mobilePageInfo').text('');
            }
        }

        // Mobile pagination click handler
        $(document).on('click', '#mobilePagination button:not([disabled])', function () {
            const page = $(this).data('page');
            const totalPages = Math.ceil(filteredItems.length / itemsPerPage);

            if (page === 'prev') {
                currentPage = Math.max(1, currentPage - 1);
            } else if (page === 'next') {
                currentPage = Math.min(totalPages, currentPage + 1);
            } else {
                currentPage = parseInt(page);
            }

            renderMobileCards();

            // Scroll to top of cards
            $('html, body').animate({
                scrollTop: $('#mobileCardView').offset().top - 100
            }, 300);
        });

        // Submit Add Product Form
        $('#addProductForm').on('submit', function (e) {
            e.preventDefault();

            if (enforceInventoryLock()) {
                return;
            }

            const productId = $('#selectProduct').val();
            const beginningStock = $('#addBeginningStock').val() || 0;

            if (!productId) {
                showToast('warning', 'Please select a product', 2000);
                return;
            }

            const baseUrl = '<?= base_url() ?>';
            $.ajax({
                url: baseUrl + 'Inventory/AddProductToInventory',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    product_id: productId,
                    beginning_stock: beginningStock
                }),
                success: function (response) {
                    if (response.success) {
                        showToast('success', response.message, 2000);
                        $('#addProductModal').addClass('hidden');
                        $('#addProductForm')[0].reset();
                        fetchAllStockitems(); // Reload the table

                        // Show deduction info/warnings for the single product
                        if (response.deduction) {
                            if (response.deduction.success && response.deduction.deductions && response
                                .deduction.deductions.length > 0) {
                                var count = response.deduction.deductions.length;
                                var msg = count + ' raw material' + (count > 1 ? 's' : '') +
                                    ' deducted for ' + response.deduction.pieces + ' pcs';
                                if (response.deduction.has_insufficient) {
                                    showToast('warning', msg +
                                        ' — some materials had insufficient stock', 4000);
                                } else {
                                    showToast('info', msg, 3000);
                                }
                            } else if (!response.deduction.success) {
                                showToast('warning', 'Raw materials not deducted: ' + (response
                                    .deduction.message || 'No recipe found for this product'),
                                    5000);
                            }
                        }
                    } else {
                        showToast('error', response.message, 2000);
                    }
                },
                error: function (xhr, status, error) {
                    // Show detailed insufficient materials modal
                    if (xhr.responseJSON && xhr.responseJSON.insufficient_materials) {
                        showInsufficientStockModal(xhr.responseJSON);
                    } else {
                        showToast('danger', 'Error adding product: ' + (xhr.responseJSON?.message ||
                            error), 2000);
                    }
                }
            });
        });

        // Tab Switching Function
        function switchTab(tabName) {
            // Remove active state from all tab buttons
            document.querySelectorAll('.tab-btn').forEach(function (btn) {
                btn.classList.remove('text-white', 'bg-primary', 'shadow-md', 'border-primary');
                btn.classList.add('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200', 'border-gray-300',
                    'hover:border-gray-400');
            });

            // Add active state to clicked button
            var activeBtn = document.querySelector('.tab-btn[data-tab="' + tabName + '"]');
            if (activeBtn) {
                activeBtn.classList.remove('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200', 'border-gray-300',
                    'hover:border-gray-400');
                activeBtn.classList.add('text-white', 'bg-primary', 'shadow-md', 'border-primary');
            }

            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(function (content) {
                content.classList.add('hidden');
            });

            // Show selected tab content
            var targetContent = document.getElementById(tabName + '-content');
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        }

        /**
         * Show a BLOCKING modal when raw materials are insufficient.
         * Prevents the operation from proceeding (400 response).
         */
        function showInsufficientStockModal(data) {
            let html = '';

            // Title message
            html += '<div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">';
            html += '<p class="text-sm font-semibold text-red-700"><i class="fas fa-ban mr-2"></i>' + (data.message ||
                'Operation blocked due to insufficient raw materials.') + '</p>';
            html += '</div>';

            // Insufficient products (from batch/distribution endpoint)
            if (data.insufficient_products && data.insufficient_products.length > 0) {
                html += '<div class="mb-4">';
                html +=
                    '<h4 class="font-semibold text-red-600 mb-2 flex items-center"><i class="fas fa-exclamation-triangle mr-2"></i>Products With Insufficient Materials</h4>';
                html += '<ul class="list-disc list-inside text-sm text-gray-700 bg-red-50 rounded-lg p-3 space-y-1">';
                data.insufficient_products.forEach(function (name) {
                    html += '<li>' + name + '</li>';
                });
                html += '</ul>';
                html += '</div>';
            }

            // Insufficient materials detail (from single-product endpoints)
            if (data.insufficient_materials && data.insufficient_materials.length > 0) {
                html += '<div class="mb-4">';
                html +=
                    '<h4 class="font-semibold text-red-600 mb-2 flex items-center"><i class="fas fa-exclamation-triangle mr-2"></i>Insufficient Raw Materials</h4>';
                html += '<ul class="list-disc list-inside text-sm text-gray-700 bg-red-50 rounded-lg p-3 space-y-1">';
                data.insufficient_materials.forEach(function (detail) {
                    html += '<li>' + detail + '</li>';
                });
                html += '</ul>';
                html += '</div>';
            }

            // Tip
            html += '<div class="mt-3 p-3 bg-amber-50 rounded-lg text-sm text-amber-800">';
            html += '<i class="fas fa-lightbulb mr-1"></i> Please restock the raw materials above before proceeding.';
            html += '</div>';

            // Reuse the deduction warning modal
            $('#deductionWarningContent').html(html);
            $('#deductionWarningModal').removeClass('hidden');
        }

        /**
         * Show a warning modal with deduction issues after inventory creation.
         * Alerts for products with no recipe and/or insufficient raw material stock.
         */
        function showDeductionWarningModal(warnings, deduction) {
            let html = '';

            // Products with no recipe
            if (deduction && deduction.no_recipe_products && deduction.no_recipe_products.length > 0) {
                html += '<div class="mb-4">';
                html +=
                    '<h4 class="font-semibold text-red-600 mb-2 flex items-center"><i class="fas fa-exclamation-triangle mr-2"></i>No Recipe Found</h4>';
                html +=
                    '<p class="text-sm text-gray-600 mb-2">The following products have no raw material recipe configured. Their raw materials were <strong>not deducted</strong>:</p>';
                html += '<ul class="list-disc list-inside text-sm text-gray-700 bg-red-50 rounded-lg p-3">';
                deduction.no_recipe_products.forEach(function (name) {
                    html += '<li>' + name + '</li>';
                });
                html += '</ul>';
                html += '</div>';
            }

            // Products with insufficient stock
            if (deduction && deduction.insufficient_products && deduction.insufficient_products.length > 0) {
                html += '<div class="mb-4">';
                html +=
                    '<h4 class="font-semibold text-amber-600 mb-2 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i>Insufficient Raw Material Stock</h4>';
                html +=
                    '<p class="text-sm text-gray-600 mb-2">The following products had some raw materials with insufficient stock. Deductions were still applied but stock went below zero:</p>';
                html += '<ul class="list-disc list-inside text-sm text-gray-700 bg-amber-50 rounded-lg p-3">';
                deduction.insufficient_products.forEach(function (name) {
                    html += '<li>' + name + '</li>';
                });
                html += '</ul>';
                html += '</div>';
            }

            // Summary
            if (deduction) {
                html += '<div class="mt-3 p-3 bg-blue-50 rounded-lg text-sm text-blue-800">';
                html += '<i class="fas fa-info-circle mr-1"></i> ';
                html += 'Deducted raw materials for <strong>' + (deduction.products_deducted || 0) +
                    '</strong> of <strong>' + (deduction.total_products || 0) + '</strong> products.';
                html += '</div>';
            }

            // Use the deduction warning modal
            $('#deductionWarningContent').html(html);
            $('#deductionWarningModal').removeClass('hidden');
        }

        function closeInventory() {
            $.ajax({
                url: '<?= base_url() ?>' + 'Inventory/CloseInventory',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    inventory_id: inventoryId
                }),
                success: function (response) {
                    if (response.success) {
                        showToast('success', response.message, 2000);
                        setInventoryState(true);
                    } else {
                        showToast('error', response.message, 2000);
                    }
                },
                error: function (xhr, status, error) {
                    showToast('danger', 'Error closing inventory: ' + (xhr.responseJSON?.message || error),
                        2000);
                    console.log(xhr);
                }
            });
        }

        function openInventory() {
            $.ajax({
                url: '<?= base_url() ?>' + 'Inventory/OpenInventory',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    inventory_id: inventoryId
                }),
                success: function (response) {
                    if (response.success) {
                        showToast('success', response.message, 2000);
                        // Refresh inventory data to reflect closed status
                        setInventoryState(false);
                    } else {
                        showToast('error', response.message, 2000);
                    }
                },
                error: function (xhr, status, error) {
                    showToast('danger', 'Error opening inventory: ' + (xhr.responseJSON?.message || error),
                        2000);
                    console.log(xhr);
                }
            });
        }

        function setInventoryState(isClosed) {
            isClosed = isClosed === true || isClosed === 1 || isClosed === '1'; // force boolean
            inventoryIsClosed = isClosed;
            if (isClosed) {
                $('#btnCloseInventory').addClass('hidden');
                $('#btnOpenInventory').removeClass('hidden');
            } else {
                $('#btnCloseInventory').removeClass('hidden');
                $('#btnOpenInventory').addClass('hidden');
            }

            syncInventoryInteractionLock();
            checkActiveInventoriesAndDisableButtons();
        }

        function resetInventory() {
            if (!inventoryId) {
                showToast('error', 'No inventory to reset!', 2000);
                return;
            }

            $.ajax({
                url: '<?= base_url() ?>' + 'Inventory/ResetInventory/' + inventoryId,
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                success: function (response) {
                    if (response.success) {
                        showToast('success', response.message, 2000);
                        // Refresh inventory data to reflect new inventory
                        inventoryId = response.new_inventory_id || null;
                        checkIfInventoryExists();
                        fetchAllStockitems();
                    } else {
                        showToast('warning', response.message, 2000);
                    }
                },
                error: function (xhr, status, error) {
                    showToast('danger', 'Error resetting inventory: ' + (xhr.responseJSON?.message || error),
                        2000);
                    console.log(xhr);
                }
            });
            // Instead of confirm(), open modal
            closeResetModal();
        }

        function openResetModal() {
            if (!inventoryExistsToday) {
                showToast('warning', 'No inventory exists for today.', 2000);
                return;
            }

            if (!inventoryIsClosed) {
                showToast('warning', 'Close inventory first before creating a new shift.', 2200);
                return;
            }

            document.getElementById('resetInventoryModal').classList.remove('hidden');
        }

        function closeResetModal() {
            document.getElementById('resetInventoryModal').classList.add('hidden');
        }

        function confirmResetInventory() {
            closeResetModal();
            resetInventory(); // call your existing function
        }
    </script>
    <!-- Confirmation Modal -->
    <div id="resetInventoryModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirm Reset</h2>
            <p class="text-sm text-gray-600 mb-6">
                Are you sure you want to create a new inventory for the next shift?
                This will duplicate the current inventory and reset stock values.
            </p>
            <div class="flex justify-end gap-3">
                <button onclick="closeResetModal()"
                    class="px-4 py-2 rounded-lg border border-gray-300 bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    Cancel
                </button>
                <button onclick="confirmResetInventory()"
                    class="px-4 py-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-2 focus:ring-yellow-300 transition">
                    Yes, Reset
                </button>
            </div>
        </div>
    </div>
    <!-- Deduction Warning Modal -->
    <div id="deductionWarningModal"
        class="hidden fixed inset-0 z-[10000] flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[80vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-clipboard-check mr-2 text-primary"></i>Raw Material Deduction Report
                </h3>
                <button id="deductionWarningModalClose" onclick="$('#deductionWarningModal').addClass('hidden')"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="px-6 py-4 overflow-y-auto" id="deductionWarningContent">
                <!-- Content injected by JS -->
            </div>
            <div class="px-6 py-3 border-t border-gray-200 flex justify-end">
                <button onclick="$('#deductionWarningModal').addClass('hidden')"
                    class="px-5 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors text-sm font-medium">
                    Got it
                </button>
            </div>
        </div>
    </div>