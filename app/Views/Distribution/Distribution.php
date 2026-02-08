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

            <!-- Header Section with Date Navigation -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2 mb-4">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Daily Baking Schedule</h2>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="btnAddItems"
                            class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-plus mr-2"></i>Add Items
                        </button>
                    </div>
                </div>

                <!-- Date Navigation -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <button type="button" id="btnPrevDay"
                            class="p-2 rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-600">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div class="flex items-center gap-2">
                            <input type="date" id="selectedDate" value="<?= date('Y-m-d') ?>"
                                class="rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                            <span id="dateLabel" class="text-sm font-medium text-primary hidden sm:inline-block"></span>
                        </div>
                        <button type="button" id="btnNextDay"
                            class="p-2 rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-600">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <!-- Quick Date Buttons -->
                    <div class="flex gap-2 overflow-x-auto pb-1">
                        <button type="button" class="quick-date-btn px-3 py-1.5 text-xs font-medium rounded-full bg-primary text-white" data-days="0">Today</button>
                        <button type="button" class="quick-date-btn px-3 py-1.5 text-xs font-medium rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100" data-days="1">Tomorrow</button>
                        <button type="button" class="quick-date-btn px-3 py-1.5 text-xs font-medium rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100" data-days="2">+2 Days</button>
                        <button type="button" class="quick-date-btn px-3 py-1.5 text-xs font-medium rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100" data-days="3">+3 Days</button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary/10 flex items-center justify-center mr-3">
                            <i class="fas fa-bread-slice text-primary text-lg sm:text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500 truncate">Items</p>
                            <p id="totalItemsCount" class="text-base sm:text-lg font-bold text-gray-900 truncate">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-blue-50 flex items-center justify-center mr-3">
                            <i class="fas fa-boxes text-blue-500 text-lg sm:text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500 truncate">Pieces</p>
                            <p id="totalQuantityCount" class="text-base sm:text-lg font-bold text-gray-900 truncate">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-yellow-50 flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-yellow-500 text-lg sm:text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500 truncate">Pending</p>
                            <p id="pendingCount" class="text-base sm:text-lg font-bold text-gray-900 truncate">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-green-50 flex items-center justify-center mr-3">
                            <i class="fas fa-check-circle text-green-500 text-lg sm:text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500 truncate">Done</p>
                            <p id="completedCount" class="text-base sm:text-lg font-bold text-gray-900 truncate">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating Add Items button for mobile -->
            <div class="fixed bottom-6 left-0 right-0 flex justify-center z-30 md:hidden">
                <button type="button" id="btnAddItemsMobile"
                    class="w-5/6 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <i class="fas fa-plus mr-2"></i>Add Items
                </button>
            </div>

            <!-- Desktop Table View - Daily Baking List -->
            <div class="hidden lg:block p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 lg:mb-0">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-clipboard-list text-primary mr-2"></i>
                        Baking List for <span id="tableDate"><?= date('F d, Y') ?></span>
                    </h3>
                </div>
                <table id="distribution-table" class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Quantity
                            </th>
                            <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="distributionTableBody">
                        <!-- Sample Data Rows -->
                        <tr class="border-b border-gray-100 hover:bg-gray-50" data-id="1">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <i class="fas fa-bread-slice text-primary"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">Spanish Bread</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-lg font-bold text-gray-800">5</span>
                                <span class="text-gray-500 text-sm">pcs</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" class="btn-edit-qty p-2 text-primary hover:bg-primary/10 rounded-lg" title="Edit Quantity">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn-delete p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-100 hover:bg-gray-50" data-id="2">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <i class="fas fa-bread-slice text-primary"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">Pandesal</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-lg font-bold text-gray-800">100</span>
                                <span class="text-gray-500 text-sm">pcs</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" class="btn-edit-qty p-2 text-primary hover:bg-primary/10 rounded-lg" title="Edit Quantity">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn-delete p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-100 hover:bg-gray-50" data-id="3">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <i class="fas fa-bread-slice text-primary"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">Pandecoco</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-lg font-bold text-gray-800">30</span>
                                <span class="text-gray-500 text-sm">pcs</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" class="btn-edit-qty p-2 text-primary hover:bg-primary/10 rounded-lg" title="Edit Quantity">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn-delete p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-100 hover:bg-gray-50" data-id="4">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <i class="fas fa-bread-slice text-primary"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">Ensaymada</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-lg font-bold text-gray-800">20</span>
                                <span class="text-gray-500 text-sm">pcs</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" class="btn-edit-qty p-2 text-primary hover:bg-primary/10 rounded-lg" title="Edit Quantity">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn-delete p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Empty State -->
                <div id="emptyState" class="hidden text-center py-12">
                    <div class="w-20 h-20 rounded-full bg-gray-100 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-1">No items scheduled</h3>
                    <p class="text-sm text-gray-500 mb-4">Add baking items for this day</p>
                    <button type="button" id="btnAddItemsEmpty"
                        class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary">
                        <i class="fas fa-plus mr-2"></i>Add Items
                    </button>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden mb-24">
                <!-- Date Header for Mobile -->
                <div class="bg-primary text-white rounded-lg p-3 mb-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs opacity-80">Baking List</span>
                            <h3 id="mobileDateHeader" class="font-semibold"><?= date('F d, Y') ?></h3>
                        </div>
                        <span class="text-2xl font-bold" id="mobileItemCount">4</span>
                    </div>
                </div>

                <!-- Cards Container -->
                <div id="mobileCardsContainer" class="space-y-2">
                    <!-- Sample Card 1 -->
                    <div class="bg-white rounded-lg shadow-sm p-3 border-l-4 border-primary flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-bread-slice text-primary"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Spanish Bread</h4>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold text-gray-800">5</span>
                            <span class="text-xs text-gray-500">pcs</span>
                        </div>
                    </div>

                    <!-- Sample Card 2 -->
                    <div class="bg-white rounded-lg shadow-sm p-3 border-l-4 border-primary flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-bread-slice text-primary"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Pandesal</h4>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold text-gray-800">100</span>
                            <span class="text-xs text-gray-500">pcs</span>
                        </div>
                    </div>

                    <!-- Sample Card 3 -->
                    <div class="bg-white rounded-lg shadow-sm p-3 border-l-4 border-primary flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-bread-slice text-primary"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Pandecoco</h4>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold text-gray-800">30</span>
                            <span class="text-xs text-gray-500">pcs</span>
                        </div>
                    </div>

                    <!-- Sample Card 4 -->
                    <div class="bg-white rounded-lg shadow-sm p-3 border-l-4 border-primary flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-bread-slice text-primary"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Ensaymada</h4>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold text-gray-800">20</span>
                            <span class="text-xs text-gray-500">pcs</span>
                        </div>
                    </div>
                </div>

                <!-- No results message -->
                <div id="mobileNoResults" class="hidden text-center py-8 text-gray-500">
                    <i class="fas fa-clipboard-list text-4xl mb-2 text-gray-300"></i>
                    <p>No items for this day</p>
                    <button type="button" id="btnAddItemsMobileEmpty"
                        class="mt-3 inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary">
                        <i class="fas fa-plus mr-2"></i>Add Items
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Items Modal - Add Multiple Products -->
    <div id="addItemsModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-2xl mx-auto p-4 sm:p-6 border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 id="addItemsModalTitle" class="text-lg font-semibold text-primary">Add Baking Items</h3>
                    <p class="text-sm text-gray-500">Add products to bake for a specific date</p>
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

                <!-- Items List -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700">
                            <i class="fas fa-list text-primary mr-1"></i>Items to Bake
                        </label>
                        <button type="button" id="btnAddMoreItem"
                            class="text-xs text-primary hover:text-secondary font-medium">
                            <i class="fas fa-plus mr-1"></i>Add More
                        </button>
                    </div>
                    
                    <div id="itemsContainer" class="space-y-2 max-h-[300px] overflow-y-auto pr-1">
                        <!-- Item Row Template -->
                        <div class="item-row flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                            <select name="product_id[]" required
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                                <option value="">Select Product</option>
                                <option value="1">Spanish Bread</option>
                                <option value="2">Pandesal</option>
                                <option value="3">Pandecoco</option>
                                <option value="4">Ensaymada</option>
                                <option value="5">Cheese Bread</option>
                                <option value="6">Ube Pandesal</option>
                                <option value="7">Monay</option>
                                <option value="8">Pan de Coco</option>
                            </select>
                            <div class="flex items-center gap-1">
                                <button type="button" class="btn-qty-dec w-8 h-8 flex items-center justify-center border border-gray-300 bg-white text-gray-600 rounded-lg hover:bg-gray-100">-</button>
                                <input type="number" name="quantity[]" min="1" value="10" required
                                    class="w-16 px-2 py-2 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                                <button type="button" class="btn-qty-inc w-8 h-8 flex items-center justify-center border border-gray-300 bg-white text-gray-600 rounded-lg hover:bg-gray-100">+</button>
                            </div>
                            <button type="button" class="btn-remove-item p-2 text-red-500 hover:bg-red-50 rounded-lg" title="Remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="mb-4 p-3 bg-primary/5 rounded-lg border border-primary/20">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Items to add:</span>
                        <span id="itemsSummaryCount" class="text-lg font-bold text-primary">1 item</span>
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
                        <i class="fas fa-plus mr-2"></i>Add to Schedule
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
                
                <div class="text-center mb-4">
                    <div class="w-16 h-16 rounded-full bg-primary/10 mx-auto mb-2 flex items-center justify-center">
                        <i class="fas fa-bread-slice text-primary text-2xl"></i>
                    </div>
                    <h4 id="editProductName" class="font-semibold text-gray-800">Spanish Bread</h4>
                </div>

                <div class="mb-6">
                    <label for="editQuantity" class="block text-sm font-medium text-gray-700 mb-2 text-center">
                        Quantity (pieces)
                    </label>
                    <div class="flex items-center justify-center gap-3">
                        <button type="button" id="btnEditQtyDec"
                            class="w-12 h-12 flex items-center justify-center border border-gray-300 bg-gray-100 text-gray-700 text-xl font-semibold rounded-lg hover:bg-gray-200">
                            -
                        </button>
                        <input type="number" id="editQuantity" name="quantity" min="1" value="10" required
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
        $(document).ready(function() {
            // Initialize
            updateDateLabel();
            updateSummaryCounts();

            // Add Items Modal Handlers
            $('#btnAddItems, #btnAddItemsMobile, #btnAddItemsEmpty, #btnAddItemsMobileEmpty').on('click', function() {
                $('#scheduleDate').val($('#selectedDate').val());
                updateScheduleQuickBtns();
                $('#addItemsModal').removeClass('hidden');
            });

            $('#btnCloseAddItemsModal, #btnCancelAddItems').on('click', function() {
                $('#addItemsModal').addClass('hidden');
            });

            // Edit Qty Modal Handlers
            $('#btnCloseEditQtyModal, #btnCancelEditQty').on('click', function() {
                $('#editQtyModal').addClass('hidden');
            });

            // Schedule quick date buttons in modal
            $('.schedule-quick-btn').on('click', function() {
                const days = parseInt($(this).data('days'));
                const newDate = new Date();
                newDate.setDate(newDate.getDate() + days);
                $('#scheduleDate').val(formatDate(newDate));
                updateScheduleQuickBtns();
            });

            // Add more item row
            $('#btnAddMoreItem').on('click', function() {
                addItemRow();
                updateItemsSummary();
            });

            // Remove item row in modal
            $(document).on('click', '.btn-remove-item', function() {
                const container = $('#itemsContainer');
                if (container.find('.item-row').length > 1) {
                    $(this).closest('.item-row').remove();
                    updateItemsSummary();
                }
            });

            // Quantity increment/decrement in add items modal
            $(document).on('click', '.btn-qty-inc', function() {
                const input = $(this).siblings('input[name="quantity[]"]');
                input.val(parseInt(input.val() || 0) + 5);
            });

            $(document).on('click', '.btn-qty-dec', function() {
                const input = $(this).siblings('input[name="quantity[]"]');
                const val = parseInt(input.val() || 0);
                if (val > 5) input.val(val - 5);
            });

            // Edit quantity modal controls
            $('#btnEditQtyInc').on('click', function() {
                const input = $('#editQuantity');
                input.val(parseInt(input.val() || 0) + 5);
            });

            $('#btnEditQtyDec').on('click', function() {
                const input = $('#editQuantity');
                const val = parseInt(input.val() || 0);
                if (val > 5) input.val(val - 5);
            });

            // Edit quantity button click
            $(document).on('click', '.btn-edit-qty', function() {
                const row = $(this).closest('tr');
                const productName = row.find('span.font-medium').text();
                const qty = row.find('.text-lg.font-bold').text();
                
                $('#editProductName').text(productName);
                $('#editQuantity').val(parseInt(qty));
                $('#editItemId').val(row.data('id'));
                $('#editQtyModal').removeClass('hidden');
            });

            // Delete item (static - just removes from view)
            $(document).on('click', '.btn-delete', function() {
                const row = $(this).closest('tr');
                row.fadeOut(300, function() {
                    $(this).remove();
                    updateSummaryCounts();
                });
            });

            // Form submissions
            $('#addItemsForm').on('submit', function(e) {
                e.preventDefault();
                $('#addItemsModal').addClass('hidden');
                // Reset form
                $('#itemsContainer').html(getItemRowTemplate());
                updateItemsSummary();
            });

            $('#editQtyForm').on('submit', function(e) {
                e.preventDefault();
                $('#editQtyModal').addClass('hidden');
            });

            // Update summary when products change in modal
            $(document).on('change', '#itemsContainer select, #itemsContainer input', function() {
                updateItemsSummary();
            });
        });

        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }

        function updateDateLabel() {
            const dateStr = $('#selectedDate').val();
            const date = new Date(dateStr);
            const today = new Date();
            today.setHours(0,0,0,0);
            
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const formatted = date.toLocaleDateString('en-US', options);
            
            let label = '';
            const diffDays = Math.floor((date - today) / (1000 * 60 * 60 * 24));
            
            if (diffDays === 0) label = '(Today)';
            else if (diffDays === 1) label = '(Tomorrow)';
            else if (diffDays === -1) label = '(Yesterday)';
            else if (diffDays > 1) label = `(+${diffDays} days)`;
            
            $('#dateLabel').text(label);
            $('#tableDate').text(formatted);
            $('#mobileDateHeader').text(date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }));
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

        function addItemRow(productId = '', qty = 10) {
            const template = getItemRowTemplate(productId, qty);
            $('#itemsContainer').append(template);
        }

        function getItemRowTemplate(productId = '', qty = 10) {
            return `
                <div class="item-row flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                    <select name="product_id[]" required
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        <option value="">Select Product</option>
                        <option value="1" ${productId == 1 ? 'selected' : ''}>Spanish Bread</option>
                        <option value="2" ${productId == 2 ? 'selected' : ''}>Pandesal</option>
                        <option value="3" ${productId == 3 ? 'selected' : ''}>Pandecoco</option>
                        <option value="4" ${productId == 4 ? 'selected' : ''}>Ensaymada</option>
                        <option value="5" ${productId == 5 ? 'selected' : ''}>Cheese Bread</option>
                        <option value="6" ${productId == 6 ? 'selected' : ''}>Ube Pandesal</option>
                        <option value="7" ${productId == 7 ? 'selected' : ''}>Monay</option>
                        <option value="8" ${productId == 8 ? 'selected' : ''}>Pan de Coco</option>
                    </select>
                    <div class="flex items-center gap-1">
                        <button type="button" class="btn-qty-dec w-8 h-8 flex items-center justify-center border border-gray-300 bg-white text-gray-600 rounded-lg hover:bg-gray-100">-</button>
                        <input type="number" name="quantity[]" min="1" value="${qty}" required
                            class="w-16 px-2 py-2 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        <button type="button" class="btn-qty-inc w-8 h-8 flex items-center justify-center border border-gray-300 bg-white text-gray-600 rounded-lg hover:bg-gray-100">+</button>
                    </div>
                    <button type="button" class="btn-remove-item p-2 text-red-500 hover:bg-red-50 rounded-lg" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        }

        function updateItemsSummary() {
            const count = $('#itemsContainer .item-row').length;
            $('#itemsSummaryCount').text(count + (count === 1 ? ' item' : ' items'));
        }

        function updateSummaryCounts() {
            const total = $('tr[data-id]').length;
            
            let totalQty = 0;
            $('tr[data-id] .text-lg.font-bold').each(function() {
                totalQty += parseInt($(this).text()) || 0;
            });

            $('#totalItemsCount').text(total);
            $('#totalQuantityCount').text(totalQty);
            $('#pendingCount').text(total);
            $('#completedCount').text(0);
            $('#mobileItemCount').text(total);
        }
    </script>
</body>
