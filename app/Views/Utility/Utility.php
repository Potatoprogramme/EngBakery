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
                    <li class="text-gray-700">Utility Expenses</li>
                </ol>
            </nav>

            <!-- Header Section -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Utility Expense Tracking</h2>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="btnAddExpense"
                            class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-plus mr-2"></i>Add Expense
                        </button>
                        <button type="button" id="btnExport" disabled
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            Export
                        </button>
                    </div>
                </div>

                <!-- Divider line -->
                <div class="border-t border-gray-200 my-4"></div>

                <!-- Filters section -->
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full">
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <label for="filter-month" class="sr-only">Month</label>
                            <select id="filter-month"
                                class="w-full sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                                <option value="">All Months</option>
                                <option value="01" selected>January 2026</option>
                                <option value="02">February 2026</option>
                                <option value="03">March 2026</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <label for="filter-category" class="sr-only">Expense Category</label>
                            <select id="filter-category"
                                class="w-full sm:w-48 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                                <option value="">All Categories</option>
                                <option value="gas">Gas</option>
                                <option value="water">Water</option>
                                <option value="electricity">Electricity</option>
                                <option value="labor">Labor</option>
                                <option value="rent">Rent</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-center sm:justify-end">
                        <button id="apply-filters" type="button"
                            class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">Apply</button>
                        <button id="reset-filters" type="button"
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Monthly Summary Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-4">
                <!-- Gas Card -->
                <div class="p-4 bg-white rounded-lg shadow-md border-l-4 border-orange-500">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Gas</span>
                        <div class="p-2 bg-orange-100 rounded-full">
                            <i class="fas fa-fire text-orange-500 text-sm"></i>
                        </div>
                    </div>
                    <div class="text-xl font-bold text-gray-800">₱ 3,500.00</div>
                    <div class="text-xs text-gray-500 mt-1">Monthly</div>
                </div>

                <!-- Water Card -->
                <div class="p-4 bg-white rounded-lg shadow-md border-l-4 border-blue-500">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Water</span>
                        <div class="p-2 bg-blue-100 rounded-full">
                            <i class="fas fa-tint text-blue-500 text-sm"></i>
                        </div>
                    </div>
                    <div class="text-xl font-bold text-gray-800">₱ 1,200.00</div>
                    <div class="text-xs text-gray-500 mt-1">Monthly</div>
                </div>

                <!-- Electricity Card -->
                <div class="p-4 bg-white rounded-lg shadow-md border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Electricity</span>
                        <div class="p-2 bg-yellow-100 rounded-full">
                            <i class="fas fa-bolt text-yellow-500 text-sm"></i>
                        </div>
                    </div>
                    <div class="text-xl font-bold text-gray-800">₱ 8,500.00</div>
                    <div class="text-xs text-gray-500 mt-1">Monthly</div>
                </div>

                <!-- Labor Card -->
                <div class="p-4 bg-white rounded-lg shadow-md border-l-4 border-green-500">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Labor</span>
                        <div class="p-2 bg-green-100 rounded-full">
                            <i class="fas fa-users text-green-500 text-sm"></i>
                        </div>
                    </div>
                    <div class="text-xl font-bold text-gray-800">₱ 25,000.00</div>
                    <div class="text-xs text-gray-500 mt-1">Monthly</div>
                </div>

                <!-- Rent Card -->
                <div class="p-4 bg-white rounded-lg shadow-md border-l-4 border-purple-500">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Rent</span>
                        <div class="p-2 bg-purple-100 rounded-full">
                            <i class="fas fa-home text-purple-500 text-sm"></i>
                        </div>
                    </div>
                    <div class="text-xl font-bold text-gray-800">₱ 15,000.00</div>
                    <div class="text-xs text-gray-500 mt-1">Monthly</div>
                </div>
            </div>

            <!-- Overhead Evaluation Section -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Overhead Cost Evaluation</h3>
                        <p class="text-sm text-gray-500">Evaluate if the current 25% overhead per product is sufficient to cover utility expenses.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Current Overhead</div>
                            <div class="text-2xl font-bold text-primary">25%</div>
                        </div>
                        <div class="h-12 w-px bg-gray-200"></div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Recommended</div>
                            <div class="text-2xl font-bold text-green-600">27%</div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 my-4"></div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Total Monthly Expenses</span>
                        <div class="text-lg font-semibold text-gray-800 mt-1">₱ 53,200.00</div>
                    </div>
                    <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Daily Average</span>
                        <div class="text-lg font-semibold text-gray-800 mt-1">₱ 1,773.33</div>
                    </div>
                    <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Overhead Collected (Est.)</span>
                        <div class="text-lg font-semibold text-green-600 mt-1">₱ 48,750.00</div>
                    </div>
                    <div class="p-3 rounded-lg border border-amber-200 bg-amber-50">
                        <span class="text-xs text-amber-600 uppercase tracking-wide">Difference</span>
                        <div class="text-lg font-semibold text-amber-600 mt-1">- ₱ 4,450.00</div>
                        <div class="text-xs text-amber-500">Overhead may be insufficient</div>
                    </div>
                </div>
            </div>

            <!-- Tabulated View Section -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Utility Breakdown</h3>
                    <div class="flex gap-2">
                        <button id="viewDaily" type="button"
                            class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            Daily View
                        </button>
                        <button id="viewMonthly" type="button"
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            Monthly View
                        </button>
                    </div>
                </div>

                <!-- Daily Breakdown Table -->
                <div id="dailyBreakdown" class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 font-semibold text-gray-700 border-b">Category</th>
                                <th class="px-4 py-3 font-semibold text-gray-700 border-b text-right">Monthly Amount</th>
                                <th class="px-4 py-3 font-semibold text-gray-700 border-b text-right">Daily Rate</th>
                                <th class="px-4 py-3 font-semibold text-gray-700 border-b text-right">% of Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 bg-orange-100 rounded">
                                            <i class="fas fa-fire text-orange-500 text-xs"></i>
                                        </div>
                                        Gas
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right font-medium">₱ 3,500.00</td>
                                <td class="px-4 py-3 text-right text-gray-600">₱ 116.67</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-700 rounded-full">6.6%</span>
                                </td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 bg-blue-100 rounded">
                                            <i class="fas fa-tint text-blue-500 text-xs"></i>
                                        </div>
                                        Water
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right font-medium">₱ 1,200.00</td>
                                <td class="px-4 py-3 text-right text-gray-600">₱ 40.00</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">2.3%</span>
                                </td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 bg-yellow-100 rounded">
                                            <i class="fas fa-bolt text-yellow-500 text-xs"></i>
                                        </div>
                                        Electricity
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right font-medium">₱ 8,500.00</td>
                                <td class="px-4 py-3 text-right text-gray-600">₱ 283.33</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">16.0%</span>
                                </td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 bg-green-100 rounded">
                                            <i class="fas fa-users text-green-500 text-xs"></i>
                                        </div>
                                        Labor
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right font-medium">₱ 25,000.00</td>
                                <td class="px-4 py-3 text-right text-gray-600">₱ 833.33</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">47.0%</span>
                                </td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 bg-purple-100 rounded">
                                            <i class="fas fa-home text-purple-500 text-xs"></i>
                                        </div>
                                        Rent
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right font-medium">₱ 15,000.00</td>
                                <td class="px-4 py-3 text-right text-gray-600">₱ 500.00</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded-full">28.2%</span>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-100">
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-800">Total</td>
                                <td class="px-4 py-3 text-right font-bold text-gray-800">₱ 53,200.00</td>
                                <td class="px-4 py-3 text-right font-bold text-gray-800">₱ 1,773.33</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="px-2 py-1 text-xs font-medium bg-gray-200 text-gray-700 rounded-full">100%</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Monthly Breakdown Table (Hidden by default) -->
                <div id="monthlyBreakdown" class="hidden overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 font-semibold text-gray-700 border-b">Month</th>
                                <th class="px-4 py-3 font-semibold text-gray-700 border-b text-right">Gas</th>
                                <th class="px-4 py-3 font-semibold text-gray-700 border-b text-right">Water</th>
                                <th class="px-4 py-3 font-semibold text-gray-700 border-b text-right">Electricity</th>
                                <th class="px-4 py-3 font-semibold text-gray-700 border-b text-right">Labor</th>
                                <th class="px-4 py-3 font-semibold text-gray-700 border-b text-right">Rent</th>
                                <th class="px-4 py-3 font-semibold text-gray-700 border-b text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">January 2026</td>
                                <td class="px-4 py-3 text-right">₱ 3,500</td>
                                <td class="px-4 py-3 text-right">₱ 1,200</td>
                                <td class="px-4 py-3 text-right">₱ 8,500</td>
                                <td class="px-4 py-3 text-right">₱ 25,000</td>
                                <td class="px-4 py-3 text-right">₱ 15,000</td>
                                <td class="px-4 py-3 text-right font-bold text-primary">₱ 53,200</td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50 bg-gray-50">
                                <td class="px-4 py-3 font-medium">December 2025</td>
                                <td class="px-4 py-3 text-right">₱ 3,200</td>
                                <td class="px-4 py-3 text-right">₱ 1,150</td>
                                <td class="px-4 py-3 text-right">₱ 9,200</td>
                                <td class="px-4 py-3 text-right">₱ 25,000</td>
                                <td class="px-4 py-3 text-right">₱ 15,000</td>
                                <td class="px-4 py-3 text-right font-bold text-primary">₱ 53,550</td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">November 2025</td>
                                <td class="px-4 py-3 text-right">₱ 3,100</td>
                                <td class="px-4 py-3 text-right">₱ 1,100</td>
                                <td class="px-4 py-3 text-right">₱ 7,800</td>
                                <td class="px-4 py-3 text-right">₱ 25,000</td>
                                <td class="px-4 py-3 text-right">₱ 15,000</td>
                                <td class="px-4 py-3 text-right font-bold text-primary">₱ 52,000</td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-100">
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-800">Average</td>
                                <td class="px-4 py-3 text-right font-medium">₱ 3,267</td>
                                <td class="px-4 py-3 text-right font-medium">₱ 1,150</td>
                                <td class="px-4 py-3 text-right font-medium">₱ 8,500</td>
                                <td class="px-4 py-3 text-right font-medium">₱ 25,000</td>
                                <td class="px-4 py-3 text-right font-medium">₱ 15,000</td>
                                <td class="px-4 py-3 text-right font-bold text-gray-800">₱ 52,917</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Desktop Table View - Expense Records -->
            <div class="hidden lg:block p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 lg:mb-0">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Expense Records</h3>
                <table id="expense-table" class="min-w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">Date</span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">Category</span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">Description</span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">Amount</span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="expenseTableBody">
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-700">Jan 31, 2026</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Labor</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">Monthly staff salary</td>
                            <td class="px-6 py-4 font-medium text-gray-800">₱ 25,000.00</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button class="btn-edit-expense text-blue-600 hover:text-blue-800" data-id="1" data-category="labor" data-description="Monthly staff salary" data-amount="25000" data-date="2026-01-31" data-period="monthly"><i class="fas fa-edit"></i></button>
                                    <button class="btn-delete-expense text-red-600 hover:text-red-800" data-id="1"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-700">Jan 28, 2026</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded-full">Rent</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">Monthly shop rental</td>
                            <td class="px-6 py-4 font-medium text-gray-800">₱ 15,000.00</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button class="btn-edit-expense text-blue-600 hover:text-blue-800" data-id="2" data-category="rent" data-description="Monthly shop rental" data-amount="15000" data-date="2026-01-28" data-period="monthly"><i class="fas fa-edit"></i></button>
                                    <button class="btn-delete-expense text-red-600 hover:text-red-800" data-id="2"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-700">Jan 25, 2026</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Electricity</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">Electric bill - January</td>
                            <td class="px-6 py-4 font-medium text-gray-800">₱ 8,500.00</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button class="btn-edit-expense text-blue-600 hover:text-blue-800" data-id="3" data-category="electricity" data-description="Electric bill - January" data-amount="8500" data-date="2026-01-25" data-period="monthly"><i class="fas fa-edit"></i></button>
                                    <button class="btn-delete-expense text-red-600 hover:text-red-800" data-id="3"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-700">Jan 20, 2026</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-700 rounded-full">Gas</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">LPG refill - 3 tanks</td>
                            <td class="px-6 py-4 font-medium text-gray-800">₱ 3,500.00</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button class="btn-edit-expense text-blue-600 hover:text-blue-800" data-id="4" data-category="gas" data-description="LPG refill - 3 tanks" data-amount="3500" data-date="2026-01-20" data-period="monthly"><i class="fas fa-edit"></i></button>
                                    <button class="btn-delete-expense text-red-600 hover:text-red-800" data-id="4"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-700">Jan 15, 2026</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">Water</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">Water bill - January</td>
                            <td class="px-6 py-4 font-medium text-gray-800">₱ 1,200.00</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button class="btn-edit-expense text-blue-600 hover:text-blue-800" data-id="5" data-category="water" data-description="Water bill - January" data-amount="1200" data-date="2026-01-15" data-period="monthly"><i class="fas fa-edit"></i></button>
                                    <button class="btn-delete-expense text-red-600 hover:text-red-800" data-id="5"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden mb-24">
                <!-- Search input for mobile -->
                <div class="mb-3">
                    <div class="relative">
                        <input type="text" id="mobileSearchInput" placeholder="Search expenses..."
                            class="w-full px-4 py-2.5 pl-10 text-sm border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Cards Container -->
                <div id="mobileCardsContainer" class="space-y-3">
                    <!-- Card 1 -->
                    <div class="p-4 bg-white rounded-lg shadow-md border-l-4 border-green-500">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Labor</span>
                            <span class="text-xs text-gray-500">Jan 31, 2026</span>
                        </div>
                        <div class="text-sm text-gray-600 mb-2">Monthly staff salary</div>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-800">₱ 25,000.00</span>
                            <div class="flex gap-2">
                                <button class="btn-edit-expense p-2 text-blue-600 hover:bg-blue-50 rounded" data-id="1" data-category="labor" data-description="Monthly staff salary" data-amount="25000" data-date="2026-01-31" data-period="monthly"><i class="fas fa-edit"></i></button>
                                <button class="btn-delete-expense p-2 text-red-600 hover:bg-red-50 rounded" data-id="1"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="p-4 bg-white rounded-lg shadow-md border-l-4 border-purple-500">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded-full">Rent</span>
                            <span class="text-xs text-gray-500">Jan 28, 2026</span>
                        </div>
                        <div class="text-sm text-gray-600 mb-2">Monthly shop rental</div>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-800">₱ 15,000.00</span>
                            <div class="flex gap-2">
                                <button class="btn-edit-expense p-2 text-blue-600 hover:bg-blue-50 rounded" data-id="2" data-category="rent" data-description="Monthly shop rental" data-amount="15000" data-date="2026-01-28" data-period="monthly"><i class="fas fa-edit"></i></button>
                                <button class="btn-delete-expense p-2 text-red-600 hover:bg-red-50 rounded" data-id="2"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="p-4 bg-white rounded-lg shadow-md border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Electricity</span>
                            <span class="text-xs text-gray-500">Jan 25, 2026</span>
                        </div>
                        <div class="text-sm text-gray-600 mb-2">Electric bill - January</div>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-800">₱ 8,500.00</span>
                            <div class="flex gap-2">
                                <button class="btn-edit-expense p-2 text-blue-600 hover:bg-blue-50 rounded" data-id="3" data-category="electricity" data-description="Electric bill - January" data-amount="8500" data-date="2026-01-25" data-period="monthly"><i class="fas fa-edit"></i></button>
                                <button class="btn-delete-expense p-2 text-red-600 hover:bg-red-50 rounded" data-id="3"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="p-4 bg-white rounded-lg shadow-md border-l-4 border-orange-500">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-700 rounded-full">Gas</span>
                            <span class="text-xs text-gray-500">Jan 20, 2026</span>
                        </div>
                        <div class="text-sm text-gray-600 mb-2">LPG refill - 3 tanks</div>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-800">₱ 3,500.00</span>
                            <div class="flex gap-2">
                                <button class="btn-edit-expense p-2 text-blue-600 hover:bg-blue-50 rounded" data-id="4" data-category="gas" data-description="LPG refill - 3 tanks" data-amount="3500" data-date="2026-01-20" data-period="monthly"><i class="fas fa-edit"></i></button>
                                <button class="btn-delete-expense p-2 text-red-600 hover:bg-red-50 rounded" data-id="4"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5 -->
                    <div class="p-4 bg-white rounded-lg shadow-md border-l-4 border-blue-500">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">Water</span>
                            <span class="text-xs text-gray-500">Jan 15, 2026</span>
                        </div>
                        <div class="text-sm text-gray-600 mb-2">Water bill - January</div>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-800">₱ 1,200.00</span>
                            <div class="flex gap-2">
                                <button class="btn-edit-expense p-2 text-blue-600 hover:bg-blue-50 rounded" data-id="5" data-category="water" data-description="Water bill - January" data-amount="1200" data-date="2026-01-15" data-period="monthly"><i class="fas fa-edit"></i></button>
                                <button class="btn-delete-expense p-2 text-red-600 hover:bg-red-50 rounded" data-id="5"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Pagination -->
                <div id="mobilePagination" class="mt-4 flex items-center justify-center gap-2">
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-100">Previous</button>
                    <span class="px-3 py-1 text-sm bg-primary text-white rounded-lg">1</span>
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-100">Next</button>
                </div>
            </div>

            <!-- Floating Add Expense button for mobile -->
            <div class="fixed bottom-6 left-0 right-0 flex justify-center z-30 md:hidden">
                <button type="button" id="btnAddExpenseMobile"
                    class="w-5/6 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <i class="fas fa-plus mr-2"></i>Add Expense
                </button>
            </div>
        </div>
    </div>

    <!-- Add/Edit Expense Modal -->
    <div id="addExpenseModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-6 border shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 id="expenseModalTitle" class="text-lg font-semibold text-primary">Add Expense</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="addExpenseForm">
                <input type="hidden" id="expense_id" name="expense_id" value="">
                <input type="hidden" id="expense_mode" name="expense_mode" value="add">
                
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-sm font-medium text-gray-700">Expense Category <span class="text-red-500">*</span></label>
                        <button type="button" id="btnManageCategories" class="text-xs text-primary hover:text-secondary hover:underline">
                            <i class="fas fa-cog mr-1"></i>Manage Categories
                        </button>
                    </div>
                    <select name="expense_category" id="expense_category"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
                        <option value="">Select Category</option>
                        <option value="gas">Gas</option>
                        <option value="water">Water</option>
                        <option value="electricity">Electricity</option>
                        <option value="labor">Labor</option>
                        <option value="rent">Rent</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input type="text" name="expense_description" id="expense_description"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="e.g., Electric bill - January">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount <span class="text-red-500">*</span></label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">₱</span>
                        <input type="number" name="expense_amount" id="expense_amount"
                            class="flex-1 w-full px-3 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="0.00" min="0" step="0.01" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="expense_date" id="expense_date"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Billing Period</label>
                    <select name="billing_period" id="billing_period"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="monthly">Monthly</option>
                        <option value="weekly">Weekly</option>
                        <option value="one-time">One-time</option>
                    </select>
                </div>

                <div class="flex gap-2 mt-6">
                    <button type="button" id="btnCancelExpense"
                        class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                    <button type="submit" id="btnSaveExpense"
                        class="flex-1 px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                        Save Expense
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Management Modal -->
    <div id="categoryModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-lg mx-auto p-4 sm:p-6 border shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary">Manage Expense Categories</h3>
                <button type="button" id="btnCloseCategoryModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Add New Category -->
            <div class="mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Add New Category</h4>
                <form id="addCategoryForm" class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category Name <span class="text-red-500">*</span></label>
                            <input type="text" id="category_name" name="category_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                                placeholder="e.g., Maintenance" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Color Theme</label>
                            <select id="category_color" name="category_color"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                                <option value="orange">Orange</option>
                                <option value="blue">Blue</option>
                                <option value="yellow">Yellow</option>
                                <option value="green">Green</option>
                                <option value="purple">Purple</option>
                                <option value="red">Red</option>
                                <option value="pink">Pink</option>
                                <option value="indigo">Indigo</option>
                                <option value="teal">Teal</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <button type="submit" id="btnAddCategory"
                            class="w-full px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-plus mr-2"></i>Add Category
                        </button>
                    </div>
                </form>
            </div>

            <!-- Categories List -->
            <div class="border border-gray-200 rounded-lg">
                <div class="p-3 bg-gray-50 border-b border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700">Existing Categories</h4>
                </div>
                <div id="categoriesList" class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
                    <!-- Gas -->
                    <div class="p-3 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full bg-orange-500"></div>
                            <div>
                                <span class="font-medium text-gray-800">Gas</span>
                                <span class="ml-2 px-2 py-0.5 text-xs bg-orange-100 text-orange-700 rounded-full">orange</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn-edit-category p-2 text-blue-600 hover:bg-blue-50 rounded" data-id="1" data-name="Gas" data-color="orange">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete-category p-2 text-red-600 hover:bg-red-50 rounded" data-id="1">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Water -->
                    <div class="p-3 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                            <div>
                                <span class="font-medium text-gray-800">Water</span>
                                <span class="ml-2 px-2 py-0.5 text-xs bg-blue-100 text-blue-700 rounded-full">blue</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn-edit-category p-2 text-blue-600 hover:bg-blue-50 rounded" data-id="2" data-name="Water" data-color="blue">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete-category p-2 text-red-600 hover:bg-red-50 rounded" data-id="2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Electricity -->
                    <div class="p-3 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full bg-yellow-500"></div>
                            <div>
                                <span class="font-medium text-gray-800">Electricity</span>
                                <span class="ml-2 px-2 py-0.5 text-xs bg-yellow-100 text-yellow-700 rounded-full">yellow</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn-edit-category p-2 text-blue-600 hover:bg-blue-50 rounded" data-id="3" data-name="Electricity" data-color="yellow">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete-category p-2 text-red-600 hover:bg-red-50 rounded" data-id="3">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Labor -->
                    <div class="p-3 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full bg-green-500"></div>
                            <div>
                                <span class="font-medium text-gray-800">Labor</span>
                                <span class="ml-2 px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded-full">green</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn-edit-category p-2 text-blue-600 hover:bg-blue-50 rounded" data-id="4" data-name="Labor" data-color="green">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete-category p-2 text-red-600 hover:bg-red-50 rounded" data-id="4">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Rent -->
                    <div class="p-3 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full bg-purple-500"></div>
                            <div>
                                <span class="font-medium text-gray-800">Rent</span>
                                <span class="ml-2 px-2 py-0.5 text-xs bg-purple-100 text-purple-700 rounded-full">purple</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn-edit-category p-2 text-blue-600 hover:bg-blue-50 rounded" data-id="5" data-name="Rent" data-color="purple">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete-category p-2 text-red-600 hover:bg-red-50 rounded" data-id="5">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <button type="button" id="btnCloseCategoryModalBottom"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[60] flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-6 border shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary">Edit Category</h3>
                <button type="button" id="btnCloseEditCategoryModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editCategoryForm">
                <input type="hidden" id="edit_category_id" name="edit_category_id" value="">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category Name <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_category_name" name="edit_category_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="e.g., Maintenance" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color Theme</label>
                    <select id="edit_category_color" name="edit_category_color"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="orange">Orange</option>
                        <option value="blue">Blue</option>
                        <option value="yellow">Yellow</option>
                        <option value="green">Green</option>
                        <option value="purple">Purple</option>
                        <option value="red">Red</option>
                        <option value="pink">Pink</option>
                        <option value="indigo">Indigo</option>
                        <option value="teal">Teal</option>
                    </select>
                </div>

                <!-- Preview -->
                <div class="mb-4 p-3 border border-gray-200 rounded-lg bg-gray-50">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                    <div class="flex items-center gap-3">
                        <div id="editCategoryPreviewColor" class="w-4 h-4 rounded-full bg-orange-500"></div>
                        <span id="editCategoryPreviewName" class="font-medium text-gray-800">Category Name</span>
                    </div>
                </div>

                <div class="flex gap-2 mt-6">
                    <button type="button" id="btnCancelEditCategory"
                        class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                    <button type="submit" id="btnUpdateCategory"
                        class="flex-1 px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[70] flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-sm mx-auto p-4 sm:p-6 border shadow-lg rounded-md bg-white">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Confirm Delete</h3>
                <p id="deleteConfirmMessage" class="text-sm text-gray-600 mb-6">Are you sure you want to delete this item? This action cannot be undone.</p>
                <input type="hidden" id="delete_item_id" value="">
                <input type="hidden" id="delete_item_type" value="">
                <div class="flex gap-2">
                    <button type="button" id="btnCancelDelete"
                        class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                    <button type="button" id="btnConfirmDelete"
                        class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle Daily/Monthly View
        document.getElementById('viewDaily').addEventListener('click', function() {
            document.getElementById('dailyBreakdown').classList.remove('hidden');
            document.getElementById('monthlyBreakdown').classList.add('hidden');
            this.classList.remove('border', 'border-gray-200', 'text-gray-700', 'hover:bg-gray-100');
            this.classList.add('bg-primary', 'text-white', 'hover:bg-secondary');
            document.getElementById('viewMonthly').classList.remove('bg-primary', 'text-white', 'hover:bg-secondary');
            document.getElementById('viewMonthly').classList.add('border', 'border-gray-200', 'text-gray-700', 'hover:bg-gray-100');
        });

        document.getElementById('viewMonthly').addEventListener('click', function() {
            document.getElementById('monthlyBreakdown').classList.remove('hidden');
            document.getElementById('dailyBreakdown').classList.add('hidden');
            this.classList.remove('border', 'border-gray-200', 'text-gray-700', 'hover:bg-gray-100');
            this.classList.add('bg-primary', 'text-white', 'hover:bg-secondary');
            document.getElementById('viewDaily').classList.remove('bg-primary', 'text-white', 'hover:bg-secondary');
            document.getElementById('viewDaily').classList.add('border', 'border-gray-200', 'text-gray-700', 'hover:bg-gray-100');
        });

        // ================== Expense Modal ==================
        const expenseModal = document.getElementById('addExpenseModal');
        const openExpenseModalBtns = [document.getElementById('btnAddExpense'), document.getElementById('btnAddExpenseMobile')];
        const closeExpenseModalBtns = [document.getElementById('btnCloseModal'), document.getElementById('btnCancelExpense')];

        // Open Add Expense Modal
        openExpenseModalBtns.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    resetExpenseForm();
                    document.getElementById('expenseModalTitle').textContent = 'Add Expense';
                    document.getElementById('expense_mode').value = 'add';
                    document.getElementById('btnSaveExpense').textContent = 'Save Expense';
                    expenseModal.classList.remove('hidden');
                });
            }
        });

        // Close Expense Modal
        closeExpenseModalBtns.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    expenseModal.classList.add('hidden');
                });
            }
        });

        // Close modal when clicking outside
        expenseModal.addEventListener('click', (e) => {
            if (e.target === expenseModal) {
                expenseModal.classList.add('hidden');
            }
        });

        // Reset expense form
        function resetExpenseForm() {
            document.getElementById('addExpenseForm').reset();
            document.getElementById('expense_id').value = '';
            document.getElementById('expense_mode').value = 'add';
        }

        // Edit Expense - attach to edit buttons
        document.querySelectorAll('.btn-edit-expense').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const category = this.dataset.category;
                const description = this.dataset.description;
                const amount = this.dataset.amount;
                const date = this.dataset.date;
                const period = this.dataset.period;

                document.getElementById('expense_id').value = id;
                document.getElementById('expense_mode').value = 'edit';
                document.getElementById('expense_category').value = category;
                document.getElementById('expense_description').value = description;
                document.getElementById('expense_amount').value = amount;
                document.getElementById('expense_date').value = date;
                document.getElementById('billing_period').value = period;
                document.getElementById('expenseModalTitle').textContent = 'Edit Expense';
                document.getElementById('btnSaveExpense').textContent = 'Update Expense';
                
                expenseModal.classList.remove('hidden');
            });
        });

        // ================== Category Modal ==================
        const categoryModal = document.getElementById('categoryModal');
        const btnManageCategories = document.getElementById('btnManageCategories');
        const closeCategoryModalBtns = [document.getElementById('btnCloseCategoryModal'), document.getElementById('btnCloseCategoryModalBottom')];

        if (btnManageCategories) {
            btnManageCategories.addEventListener('click', () => {
                categoryModal.classList.remove('hidden');
            });
        }

        closeCategoryModalBtns.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    categoryModal.classList.add('hidden');
                });
            }
        });

        categoryModal.addEventListener('click', (e) => {
            if (e.target === categoryModal) {
                categoryModal.classList.add('hidden');
            }
        });

        // ================== Edit Category Modal ==================
        const editCategoryModal = document.getElementById('editCategoryModal');
        const closeEditCategoryBtns = [document.getElementById('btnCloseEditCategoryModal'), document.getElementById('btnCancelEditCategory')];

        // Open Edit Category Modal
        document.querySelectorAll('.btn-edit-category').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const color = this.dataset.color;

                document.getElementById('edit_category_id').value = id;
                document.getElementById('edit_category_name').value = name;
                document.getElementById('edit_category_color').value = color;

                updateCategoryPreview();
                editCategoryModal.classList.remove('hidden');
            });
        });

        closeEditCategoryBtns.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    editCategoryModal.classList.add('hidden');
                });
            }
        });

        editCategoryModal.addEventListener('click', (e) => {
            if (e.target === editCategoryModal) {
                editCategoryModal.classList.add('hidden');
            }
        });

        // Update category preview
        function updateCategoryPreview() {
            const name = document.getElementById('edit_category_name').value || 'Category Name';
            const color = document.getElementById('edit_category_color').value || 'orange';

            document.getElementById('editCategoryPreviewName').textContent = name;
            document.getElementById('editCategoryPreviewColor').className = 'w-4 h-4 rounded-full bg-' + color + '-500';
        }

        // Live preview updates
        document.getElementById('edit_category_name').addEventListener('input', updateCategoryPreview);
        document.getElementById('edit_category_color').addEventListener('change', updateCategoryPreview);

        // ================== Delete Confirmation Modal ==================
        const deleteConfirmModal = document.getElementById('deleteConfirmModal');

        // Delete Category
        document.querySelectorAll('.btn-delete-category').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('delete_item_id').value = id;
                document.getElementById('delete_item_type').value = 'category';
                document.getElementById('deleteConfirmMessage').textContent = 'Are you sure you want to delete this category? All expenses in this category will need to be reassigned.';
                deleteConfirmModal.classList.remove('hidden');
            });
        });

        // Delete Expense
        document.querySelectorAll('.btn-delete-expense').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('delete_item_id').value = id;
                document.getElementById('delete_item_type').value = 'expense';
                document.getElementById('deleteConfirmMessage').textContent = 'Are you sure you want to delete this expense? This action cannot be undone.';
                deleteConfirmModal.classList.remove('hidden');
            });
        });

        document.getElementById('btnCancelDelete').addEventListener('click', () => {
            deleteConfirmModal.classList.add('hidden');
        });

        document.getElementById('btnConfirmDelete').addEventListener('click', () => {
            const id = document.getElementById('delete_item_id').value;
            const type = document.getElementById('delete_item_type').value;
            
            // Here you would make AJAX call to delete
            console.log('Deleting ' + type + ' with ID: ' + id);
            
            deleteConfirmModal.classList.add('hidden');
            // Refresh page or update UI after deletion
        });

        deleteConfirmModal.addEventListener('click', (e) => {
            if (e.target === deleteConfirmModal) {
                deleteConfirmModal.classList.add('hidden');
            }
        });
    </script>
        