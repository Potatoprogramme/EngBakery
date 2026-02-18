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
                                <!-- Dynamically populated by populateMonthFilter() -->
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
            <div id="summaryCards" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mb-4">
                <!-- Dynamically populated by getExpenses() -->
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
                            <div id="recommendedOverhead" class="text-2xl font-bold text-green-600">--%</div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 my-4"></div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Total Monthly Expenses</span>
                        <div id="overheadTotalMonthly" class="text-lg font-semibold text-gray-800 mt-1">₱ 0.00</div>
                    </div>
                    <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Daily Average</span>
                        <div id="overheadDailyAvg" class="text-lg font-semibold text-gray-800 mt-1">₱ 0.00</div>
                    </div>
                    <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Overhead Collected (Est.)</span>
                        <div id="overheadCollected" class="text-lg font-semibold text-green-600 mt-1">₱ 0.00</div>
                    </div>
                    <div id="overheadDifferenceCard" class="p-3 rounded-lg border border-amber-200 bg-amber-50">
                        <span class="text-xs text-amber-600 uppercase tracking-wide">Difference</span>
                        <div id="overheadDifference" class="text-lg font-semibold text-amber-600 mt-1">₱ 0.00</div>
                        <div id="overheadDifferenceNote" class="text-xs text-amber-500"></div>
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
                        <tbody id="dailyBreakdownBody">
                            <!-- Dynamically populated -->
                        </tbody>
                        <tfoot class="bg-gray-100" id="dailyBreakdownFoot">
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-800">Total</td>
                                <td id="dailyTotalMonthly" class="px-4 py-3 text-right font-bold text-gray-800">₱ 0.00</td>
                                <td id="dailyTotalDaily" class="px-4 py-3 text-right font-bold text-gray-800">₱ 0.00</td>
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
                        <tbody id="monthlyBreakdownBody">
                            <!-- Dynamically populated -->
                        </tbody>
                        <tfoot class="bg-gray-100" id="monthlyBreakdownFoot">
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
                                <span class="flex items-center">Amount</span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="expenseTableBody">
                        <!-- Dynamically populated by getExpenses() -->
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
                    <!-- Dynamically populated by getExpenses() -->
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

    <script>
        const base_url = '<?= base_url() ?>';
        let allExpenses = []; // Store all fetched expenses

        // ================== Category Config ==================
        const categoryConfig = {
            gas:         { label: 'Gas',         icon: 'fa-fire',  bgColor: 'orange', bgClass: 'bg-orange-100', textClass: 'text-orange-600', borderClass: 'border-orange-500', badgeBg: 'bg-orange-100', badgeText: 'text-orange-700' },
            water:       { label: 'Water',       icon: 'fa-tint',  bgColor: 'blue',   bgClass: 'bg-blue-100',   textClass: 'text-blue-600',   borderClass: 'border-blue-500',   badgeBg: 'bg-blue-100',   badgeText: 'text-blue-700' },
            electricity: { label: 'Electricity', icon: 'fa-bolt',  bgColor: 'yellow', bgClass: 'bg-yellow-100', textClass: 'text-yellow-600', borderClass: 'border-yellow-500', badgeBg: 'bg-yellow-100', badgeText: 'text-yellow-700' },
            labor:       { label: 'Labor',       icon: 'fa-users', bgColor: 'green',  bgClass: 'bg-green-100',  textClass: 'text-green-600',  borderClass: 'border-green-500',  badgeBg: 'bg-green-100',  badgeText: 'text-green-700' },
            rent:        { label: 'Rent',        icon: 'fa-home',  bgColor: 'purple', bgClass: 'bg-purple-100', textClass: 'text-purple-600', borderClass: 'border-purple-500', badgeBg: 'bg-purple-100', badgeText: 'text-purple-700' },
            internet:    { label: 'Internet',    icon: 'fa-wifi',  bgColor: 'indigo', bgClass: 'bg-indigo-100', textClass: 'text-indigo-600', borderClass: 'border-indigo-500', badgeBg: 'bg-indigo-100', badgeText: 'text-indigo-700' },
        };

        function getCategoryConfig(type) {
            return categoryConfig[type] || { label: type, icon: 'fa-receipt', bgColor: 'gray', bgClass: 'bg-gray-100', textClass: 'text-gray-600', borderClass: 'border-gray-500', badgeBg: 'bg-gray-100', badgeText: 'text-gray-700' };
        }

        function formatCurrency(amount) {
            return '₱ ' + parseFloat(amount).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        }

        function formatDateISO(dateStr) {
            const date = new Date(dateStr);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return year + '-' + month + '-' + day;
        }

        // ================== Toggle Daily/Monthly View ==================
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

        closeExpenseModalBtns.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    expenseModal.classList.add('hidden');
                });
            }
        });

        expenseModal.addEventListener('click', (e) => {
            if (e.target === expenseModal) {
                expenseModal.classList.add('hidden');
            }
        });

        // Handle form submission
        document.getElementById('addExpenseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const mode = document.getElementById('expense_mode').value;
            if (mode === 'edit') {
                updateExpense();
            } else {
                addExpense();
            }
        });

        function resetExpenseForm() {
            document.getElementById('addExpenseForm').reset();
            document.getElementById('expense_id').value = '';
            document.getElementById('expense_mode').value = 'add';
        }

        // ================== CRUD Functions ==================
        function addExpense() {
            const formData = {
                expense_category: $('#expense_category').val(),
                expense_amount: $('#expense_amount').val(),
                expense_date: $('#expense_date').val(),
                billing_period: $('#billing_period').val(),
            };

            $.ajax({
                url: base_url + '/Utility/AddUtilityExpense',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        expenseModal.classList.add('hidden');
                        resetExpenseForm();
                        Toast.success(response.message);
                        getExpenses();
                    } else {
                        Toast.error(response.message || 'Failed to add expense.');
                    }
                },
                error: function() {
                    Toast.error('An error occurred while saving the expense. Please try again.');
                }
            });
        }

        function updateExpense() {
            const formData = {
                expense_id: $('#expense_id').val(),
                expense_category: $('#expense_category').val(),
                expense_amount: $('#expense_amount').val(),
                expense_date: $('#expense_date').val(),
                billing_period: $('#billing_period').val(),
            };

            $.ajax({
                url: base_url + '/Utility/UpdateUtilityExpense',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        expenseModal.classList.add('hidden');
                        resetExpenseForm();
                        Toast.success(response.message);
                        getExpenses();
                    } else {
                        Toast.error(response.message || 'Failed to update expense.');
                    }
                },
                error: function() {
                    Toast.error('An error occurred while updating the expense. Please try again.');
                }
            });
        }

        function deleteExpense(id) {
            $.ajax({
                url: base_url + '/Utility/DeleteUtilityExpense/' + id,
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Toast.success(response.message);
                        getExpenses();
                    } else {
                        Toast.error(response.message || 'Failed to delete expense.');
                    }
                },
                error: function() {
                    Toast.error('An error occurred while deleting the expense. Please try again.');
                }
            });
        }

        // ================== Get & Render Expenses ==================
        function getExpenses() {
            $.ajax({
                url: base_url + '/Utility/GetUtilityExpenses',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        allExpenses = response.data || [];
                        populateMonthFilter();
                        applyFiltersAndRender();
                    } else {
                        Toast.error(response.message || 'Failed to fetch expenses.');
                    }
                },
                error: function() {
                    Toast.error('An error occurred while fetching expenses. Please try again.');
                }
            });
        }

        // ================== Populate Month Filter Dynamically ==================
        function populateMonthFilter() {
            const select = document.getElementById('filter-month');
            const currentValue = select.value; // preserve existing selection

            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                                 'July', 'August', 'September', 'October', 'November', 'December'];

            // Collect unique year-month keys from all expenses
            const monthSet = new Set();
            allExpenses.forEach(exp => {
                const date = new Date(exp.billed_at);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                monthSet.add(year + '-' + month);
            });

            // Always include the current month even if no expenses yet
            const now = new Date();
            const currentYear = now.getFullYear();
            const currentMonth = String(now.getMonth() + 1).padStart(2, '0');
            monthSet.add(currentYear + '-' + currentMonth);

            // Sort descending (newest first)
            const sortedMonths = [...monthSet].sort().reverse();

            // Rebuild options
            select.innerHTML = '<option value="">All Months</option>';
            sortedMonths.forEach(key => {
                const [year, month] = key.split('-');
                const label = monthNames[parseInt(month, 10) - 1] + ' ' + year;
                const option = document.createElement('option');
                option.value = month;
                option.textContent = label;
                select.appendChild(option);
            });

            // Restore previous selection if still valid, otherwise default to current month
            const validValues = sortedMonths.map(k => k.split('-')[1]);
            if (currentValue && validValues.includes(currentValue)) {
                select.value = currentValue;
            } else {
                select.value = currentMonth;
            }
        }

        function applyFiltersAndRender() {
            const filterMonth = document.getElementById('filter-month').value;
            const filterCategory = document.getElementById('filter-category').value;

            let filtered = [...allExpenses];

            // Filter by month
            if (filterMonth) {
                filtered = filtered.filter(exp => {
                    const date = new Date(exp.billed_at);
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    return month === filterMonth;
                });
            }

            // Filter by category
            if (filterCategory) {
                filtered = filtered.filter(exp => exp.type === filterCategory);
            }

            renderSummaryCards(filtered);
            renderDailyBreakdown(filtered);
            renderMonthlyBreakdown(allExpenses, filterMonth);
            renderOverheadEvaluation(filtered);
            renderExpenseTable(filtered);
            renderMobileCards(filtered);
            bindExpenseEvents();
        }

        // ================== Render: Summary Cards ==================
        function renderSummaryCards(expenses) {
            const container = document.getElementById('summaryCards');
            const categories = ['gas', 'water', 'electricity', 'labor', 'rent'];
            let html = '';

            categories.forEach(cat => {
                const config = getCategoryConfig(cat);
                const total = expenses.filter(e => e.type === cat).reduce((sum, e) => sum + parseFloat(e.expense), 0);

                html += `
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">${config.label}</p>
                            <p class="text-base lg:text-lg font-bold text-gray-900 mt-1">${formatCurrency(total)}</p>
                        </div>
                        <div class="flex-shrink-0 w-9 h-9 sm:w-10 sm:h-10 ${config.bgClass} rounded-full flex items-center justify-center ml-2">
                            <i class="fas ${config.icon} ${config.textClass} text-sm sm:text-base"></i>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-3 flex items-center text-xs">
                        <span class="${config.textClass} font-medium">
                            <i class="fas fa-chart-line mr-1"></i>${total > 0 ? 'Active' : 'No expenses'}
                        </span>
                    </div>
                </div>`;
            });

            container.innerHTML = html;
        }

        // ================== Render: Daily Breakdown ==================
        function renderDailyBreakdown(expenses) {
            const tbody = document.getElementById('dailyBreakdownBody');
            const grandTotal = expenses.reduce((sum, e) => sum + parseFloat(e.expense), 0);
            const daysInMonth = 30;

            // Group by category
            const grouped = {};
            expenses.forEach(exp => {
                if (!grouped[exp.type]) grouped[exp.type] = 0;
                grouped[exp.type] += parseFloat(exp.expense);
            });

            let html = '';
            Object.keys(grouped).forEach(cat => {
                const config = getCategoryConfig(cat);
                const amount = grouped[cat];
                const daily = amount / daysInMonth;
                const pct = grandTotal > 0 ? ((amount / grandTotal) * 100).toFixed(1) : '0.0';

                html += `
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 ${config.bgClass} rounded">
                                <i class="fas ${config.icon} ${config.textClass} text-xs"></i>
                            </div>
                            ${config.label}
                        </div>
                    </td>
                    <td class="px-4 py-3 text-right font-medium">${formatCurrency(amount)}</td>
                    <td class="px-4 py-3 text-right text-gray-600">${formatCurrency(daily)}</td>
                    <td class="px-4 py-3 text-right">
                        <span class="px-2 py-1 text-xs font-medium ${config.badgeBg} ${config.badgeText} rounded-full">${pct}%</span>
                    </td>
                </tr>`;
            });

            if (Object.keys(grouped).length === 0) {
                html = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No expenses found for the selected period.</td></tr>';
            }

            tbody.innerHTML = html;

            // Update footer
            const dailyTotal = grandTotal / daysInMonth;
            document.getElementById('dailyTotalMonthly').textContent = formatCurrency(grandTotal);
            document.getElementById('dailyTotalDaily').textContent = formatCurrency(dailyTotal);
        }

        // ================== Render: Monthly Breakdown ==================
        function renderMonthlyBreakdown(expenses, filterMonth) {
            const tbody = document.getElementById('monthlyBreakdownBody');
            const tfoot = document.getElementById('monthlyBreakdownFoot');
            const categories = ['gas', 'water', 'electricity', 'labor', 'rent'];
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            // Group by month-year
            const monthlyData = {};
            expenses.forEach(exp => {
                const date = new Date(exp.billed_at);
                const key = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
                if (!monthlyData[key]) {
                    monthlyData[key] = { label: monthNames[date.getMonth()] + ' ' + date.getFullYear(), totals: {} };
                    categories.forEach(c => monthlyData[key].totals[c] = 0);
                }
                if (categories.includes(exp.type)) {
                    monthlyData[key].totals[exp.type] += parseFloat(exp.expense);
                }
            });

            // Sort by key descending
            const sortedKeys = Object.keys(monthlyData).sort().reverse();

            let html = '';
            const avgTotals = {};
            categories.forEach(c => avgTotals[c] = 0);
            let grandAvgTotal = 0;

            sortedKeys.forEach((key, idx) => {
                const data = monthlyData[key];
                let rowTotal = 0;
                categories.forEach(c => rowTotal += data.totals[c]);

                categories.forEach(c => avgTotals[c] += data.totals[c]);
                grandAvgTotal += rowTotal;

                html += `<tr class="border-b hover:bg-gray-50 ${idx % 2 === 1 ? 'bg-gray-50' : ''}">
                    <td class="px-4 py-3 font-medium">${data.label}</td>`;
                categories.forEach(c => {
                    html += `<td class="px-4 py-3 text-right">${formatCurrency(data.totals[c])}</td>`;
                });
                html += `<td class="px-4 py-3 text-right font-bold text-primary">${formatCurrency(rowTotal)}</td></tr>`;
            });

            if (sortedKeys.length === 0) {
                html = '<tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No monthly data available.</td></tr>';
            }

            tbody.innerHTML = html;

            // Render footer averages
            const count = sortedKeys.length || 1;
            let footHtml = '<tr><td class="px-4 py-3 font-semibold text-gray-800">Average</td>';
            categories.forEach(c => {
                footHtml += `<td class="px-4 py-3 text-right font-medium">${formatCurrency(avgTotals[c] / count)}</td>`;
            });
            footHtml += `<td class="px-4 py-3 text-right font-bold text-gray-800">${formatCurrency(grandAvgTotal / count)}</td></tr>`;
            tfoot.innerHTML = footHtml;
        }

        function getTotalSales(callback) {
            // Build a YYYY-MM-01 date from the selected filter month (or current month)
            const now = new Date();
            const selectedMonth = document.getElementById('filter-month').value;
            const year = now.getFullYear();
            const month = selectedMonth ? selectedMonth : String(now.getMonth() + 1).padStart(2, '0');
            const dateSegment = year + '-' + month + '-01';

            $.ajax({
                url: base_url + 'Utility/GetTotalSales/' + dateSegment,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const totalSales = parseFloat(response.total_sales) || 0;
                        if (typeof callback === 'function') callback(totalSales);
                    } else {
                        Toast.error(response.message || 'Failed to fetch total sales.');
                        if (typeof callback === 'function') callback(0);
                    }
                },
                error: function() {
                    Toast.error('An error occurred while fetching total sales. Please try again.');
                    if (typeof callback === 'function') callback(0);
                }
            });
        }

        // ================== Render: Overhead Evaluation ==================
        function renderOverheadEvaluation(expenses) {
            const totalMonthly = expenses.reduce((sum, e) => sum + parseFloat(e.expense), 0);
            const daysInMonth = 30;
            const dailyAvg = totalMonthly / daysInMonth;
            const overheadPct = 25;

            document.getElementById('overheadTotalMonthly').textContent = formatCurrency(totalMonthly);
            document.getElementById('overheadDailyAvg').textContent = formatCurrency(dailyAvg);

            getTotalSales(function(totalSales) {
                // Overhead collected = 25% of total sales
                const estimatedCollected = totalSales * (overheadPct / 100);
                document.getElementById('overheadCollected').textContent = formatCurrency(estimatedCollected);

                const difference = estimatedCollected - totalMonthly;
                const diffEl = document.getElementById('overheadDifference');
                const noteEl = document.getElementById('overheadDifferenceNote');
                const cardEl = document.getElementById('overheadDifferenceCard');

                if (difference >= 0) {
                    diffEl.textContent = '+ ' + formatCurrency(difference);
                    diffEl.className = 'text-lg font-semibold text-green-600 mt-1';
                    noteEl.textContent = 'Overhead is sufficient';
                    noteEl.className = 'text-xs text-green-500';
                    cardEl.className = 'p-3 rounded-lg border border-green-200 bg-green-50';
                } else {
                    diffEl.textContent = '- ' + formatCurrency(Math.abs(difference));
                    diffEl.className = 'text-lg font-semibold text-amber-600 mt-1';
                    noteEl.textContent = 'Overhead may be insufficient';
                    noteEl.className = 'text-xs text-amber-500';
                    cardEl.className = 'p-3 rounded-lg border border-amber-200 bg-amber-50';
                }

                // Recommended overhead: what % of sales would cover expenses
                const recommended = totalSales > 0 ? Math.ceil((totalMonthly / totalSales) * 100) : overheadPct;
                document.getElementById('recommendedOverhead').textContent = (recommended || overheadPct) + '%';
            });
        }

        // ================== Render: Desktop Expense Table ==================
        function renderExpenseTable(expenses) {
            const tbody = document.getElementById('expenseTableBody');

            if (expenses.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No expense records found.</td></tr>';
                return;
            }

            let html = '';
            expenses.forEach(exp => {
                const config = getCategoryConfig(exp.type);
                const dateFormatted = formatDate(exp.billed_at);
                const dateISO = formatDateISO(exp.billed_at);
                const amount = parseFloat(exp.expense);

                html += `
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-700">${dateFormatted}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium ${config.badgeBg} ${config.badgeText} rounded-full">${config.label}</span>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800">${formatCurrency(amount)}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button class="btn-edit-expense text-blue-600 hover:text-blue-800"
                                data-id="${exp.u_id}" data-category="${exp.type}"
                                data-amount="${amount}" data-date="${dateISO}" data-period="${exp.billing_period}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete-expense text-red-600 hover:text-red-800" data-id="${exp.u_id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
            });

            tbody.innerHTML = html;
        }

        // ================== Render: Mobile Cards ==================
        function renderMobileCards(expenses) {
            const container = document.getElementById('mobileCardsContainer');

            if (expenses.length === 0) {
                container.innerHTML = '<div class="p-6 text-center text-gray-400">No expense records found.</div>';
                return;
            }

            let html = '';
            expenses.forEach(exp => {
                const config = getCategoryConfig(exp.type);
                const dateFormatted = formatDate(exp.billed_at);
                const dateISO = formatDateISO(exp.billed_at);
                const amount = parseFloat(exp.expense);

                html += `
                <div class="p-4 bg-white rounded-lg shadow-md border-l-4 ${config.borderClass}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-1 text-xs font-medium ${config.badgeBg} ${config.badgeText} rounded-full">${config.label}</span>
                        <span class="text-xs text-gray-500">${dateFormatted}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-gray-800">${formatCurrency(amount)}</span>
                        <div class="flex gap-2">
                            <button class="btn-edit-expense p-2 text-blue-600 hover:bg-blue-50 rounded"
                                data-id="${exp.u_id}" data-category="${exp.type}"
                                data-amount="${amount}" data-date="${dateISO}" data-period="${exp.billing_period}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete-expense p-2 text-red-600 hover:bg-red-50 rounded" data-id="${exp.u_id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
            });

            container.innerHTML = html;
        }

        // ================== Bind Edit/Delete Events (after dynamic render) ==================
        function bindExpenseEvents() {
            // Edit buttons
            document.querySelectorAll('.btn-edit-expense').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('expense_id').value = this.dataset.id;
                    document.getElementById('expense_mode').value = 'edit';
                    document.getElementById('expense_category').value = this.dataset.category;
                    document.getElementById('expense_amount').value = this.dataset.amount;
                    document.getElementById('expense_date').value = this.dataset.date;
                    document.getElementById('billing_period').value = this.dataset.period;
                    document.getElementById('expenseModalTitle').textContent = 'Edit Expense';
                    document.getElementById('btnSaveExpense').textContent = 'Update Expense';
                    expenseModal.classList.remove('hidden');
                });
            });

            // Delete buttons
            document.querySelectorAll('.btn-delete-expense').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    Confirm.delete('Are you sure you want to delete this expense?', function() {
                        deleteExpense(id);
                    });
                });
            });
        }

        // ================== Filters ==================
        document.getElementById('apply-filters').addEventListener('click', function() {
            applyFiltersAndRender();
        });

        document.getElementById('reset-filters').addEventListener('click', function() {
            document.getElementById('filter-month').value = '';
            document.getElementById('filter-category').value = '';
            applyFiltersAndRender();
        });

        // Mobile search
        document.getElementById('mobileSearchInput').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const cards = document.querySelectorAll('#mobileCardsContainer > div');
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(query) ? '' : 'none';
            });
        });

        // ================== Init: Load data on page load ==================
        $(document).ready(function() {
            getExpenses();
        });
    </script>
        