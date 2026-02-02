<body class="bg-gray-50">
    <div class="p-4 sm:ml-60">
        <div class="mt-16">
            <nav class="mb-3 sm:mb-4" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-1 text-sm text-gray-500 justify-left sm:justify-start">
                    <li><a href="<?= base_url('Dashboard') ?>" class="hover:text-primary">Dashboard</a></li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li><a href="<?= base_url('Sales') ?>" class="hover:text-primary">Daily Sales Remittance</a></li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li class="text-gray-700">Remittance History</li>
                </ol>
            </nav>

            <!-- Header Card -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">
                        Remittance History
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <a href="<?= base_url('Sales') ?>"
                            class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            Today's Remittance
                        </a>
                    </div>
                </div>
                <div class="border-t border-gray-200 my-4"></div>

                <!-- Filters -->
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 flex-1">
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <label for="filterDateFrom" class="text-sm text-gray-600 whitespace-nowrap w-12 sm:w-auto">From:</label>
                            <input type="date" id="filterDateFrom" class="flex-1 sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <label for="filterDateTo" class="text-sm text-gray-600 whitespace-nowrap w-12 sm:w-auto">To:</label>
                            <input type="date" id="filterDateTo" class="flex-1 sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <label for="filterCashier" class="text-sm text-gray-600 whitespace-nowrap w-12 sm:w-auto">Cashier:</label>
                            <select id="filterCashier" class="flex-1 sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                                <option value="">All Cashiers</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2 sm:justify-end">
                        <button id="btnApplyFilters" type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-filter mr-2"></i>Apply
                        </button>
                        <button id="btnResetFilters" type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </button>
                        <button id="btnExportCsv" type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg border border-green-500 px-4 py-2 text-sm font-medium text-green-600 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-200">
                            <i class="fas fa-file-csv mr-2"></i>Export
                        </button>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 sm:mb-0">
                <table id="remittanceHistoryTable" class="min-w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Date</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Cashier</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Total Sales</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Total Remitted</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Variance</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody id="remittanceHistoryTableBody">
                        <!-- Data will be populated by JS -->
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden space-y-3 mb-20" id="remittanceHistoryCards">
                <!-- Cards will be populated by JS -->
            </div>
        </div>
    </div>

    <!-- Remittance Details Modal -->
    <div id="remittanceDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-2xl mx-auto border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Remittance Details</h3>
                        <p class="text-sm text-gray-500" id="detailDate">-</p>
                    </div>
                    <button type="button" id="btnCloseDetailsModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Detail Content -->
                <div class="space-y-4">
                    <!-- Cashier Info -->
                    <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <span class="text-sm text-gray-500">Cashier:</span>
                            <p class="font-semibold text-gray-800" id="detailCashier">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Email:</span>
                            <p class="font-semibold text-gray-800" id="detailCashierEmail">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Outlet:</span>
                            <p class="font-semibold text-gray-800" id="detailOutlet">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Shift:</span>
                            <p class="font-semibold text-gray-800" id="detailShift">-</p>
                        </div>
                    </div>


                    <!-- Denomination Breakdown Table -->
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <h4 class="font-semibold text-gray-700 mb-3">Cash Denomination Breakdown</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Denomination</th>
                                        <th class="px-3 py-2 text-center font-semibold text-gray-700">Quantity</th>
                                        <th class="px-3 py-2 text-right font-semibold text-gray-700">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="denominationTableBody">
                                    <tr>
                                        <td colspan="3" class="px-3 py-2 text-center text-gray-500">No denominations recorded</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Remittance Breakdown -->
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <h4 class="font-semibold text-gray-700 mb-3">Remittance Breakdown</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-money-bill text-green-500 mr-2"></i>Cash On Hand:</span>
                                <span class="font-semibold" id="detailCashOnHand">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-mobile-alt text-blue-500 mr-2"></i>GCash:</span>
                                <span class="font-semibold" id="detailGcash">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-hand-holding-usd text-gray-500 mr-2"></i>Cash Out:</span>
                                <span class="font-semibold text-red-600" id="detailCashOut">-₱0.00</span>
                            </div>
                            <div class="flex justify-between border-t pt-2 mt-2">
                                <span class="font-bold text-gray-800">Total Remitted:</span>
                                <span class="font-bold text-primary text-lg" id="detailTotalRemitted">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Breakdown -->
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <h4 class="font-semibold text-gray-700 mb-3">Sales by Category</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-money-bill text-green-500 mr-2"></i>Bakery/Bread:</span>
                                <span class="font-semibold" id="detailBakerySales">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-mobile-alt text-blue-500 mr-2"></i>Coffee/Drinks:</span>
                                <span class="font-semibold" id="detailCoffeeSales">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-hand-holding-usd text-gray-500 mr-2"></i>Grocery:</span>
                                <span class="font-semibold" id="detailGrocerySales">₱0.00</span>
                            </div>
                            <div class="flex justify-between border-t pt-2 mt-2">
                                <span class="font-bold text-gray-800">Total Sales:</span>
                                <span class="font-bold text-primary text-lg" id="detailTotalSales">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sales vs Remittance -->
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <h4 class="font-semibold text-gray-700 mb-3">Sales vs Remittance</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-chart-line text-primary mr-2"></i>Total Sales:</span>
                                <span class="font-semibold" id="detailTotalSales">₱0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600"><i class="fas fa-hand-holding-usd text-green-500 mr-2"></i>Total Remitted:</span>
                                <span class="font-semibold" id="detailTotalRemittedCompare">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Cash Out Reason -->
                    <div class="p-4 border border-gray-200 rounded-lg" id="cashOutReasonContainer" style="display: none;">
                        <h4 class="font-semibold text-gray-700 mb-2">Cash Out Reason</h4>
                        <p class="text-gray-600" id="detailCashOutReason">-</p>
                    </div>

                    <!-- Variance -->
                    <div class="p-4 rounded-lg" id="detailVarianceContainer">
                        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                            <span class="font-bold text-gray-800">Overage/Shortage:</span>
                            <span class="font-bold text-xl" id="detailVariance">₱0.00</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 pb-6 flex gap-2">
                <button type="button" id="btnPrintDetails" class="flex-1 px-4 py-3 text-sm font-medium text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition-all">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
                <button type="button" id="btnCloseModal" class="flex-1 px-4 py-3 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary transition-all">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        window.BASE_URL = '<?= rtrim(site_url(), '/') ?>/';
        let dataTable = null;
        let remittanceData = []; // Store fetched data for filtering
        let currentRemittanceDetails = null; // Store current remittance details for printing

        function getAllRemittances() {
            $.ajax({
                url: BASE_URL + 'Sales/GetRemittanceHistory',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        remittanceData = response.data; // Store data
                        renderRemittanceHistory(response.data);
                        initFilters(); // Initialize filters after data is loaded
                        console.log('Remittance data fetched:', response.data);
                    } else {
                        showToast('error', 'Failed to fetch remittance history');
                    }
                },
                error: function() {
                    showToast('error', 'An error occurred while fetching remittance history');
                }
            });
        }

        function getRemittanceDetails(remittanceId) {
            $.ajax({
                url: BASE_URL + 'Sales/GetRemittanceDetails/' + remittanceId,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        renderRemittanceDetails(response.data);
                        console.log('Remittance details fetched:', response.data);
                    } else {
                        showToast('error', 'Failed to fetch remittance details');
                    }
                },
                error: function() {
                    showToast('error', 'An error occurred while fetching remittance details');
                }
            });
        }

        $(document).ready(function() {
            getAllRemittances();
            initModalHandlers();
        });

        function initFilters() {
            // Set default date range (last 30 days)
            const today = new Date();
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(today.getDate() - 30);

            $('#filterDateTo').val(today.toISOString().split('T')[0]);
            $('#filterDateFrom').val(thirtyDaysAgo.toISOString().split('T')[0]);

            // Populate cashier filter
            const cashiers = [...new Set(remittanceData.map(r => r.cashier_name))];
            cashiers.forEach(cashier => {
                $('#filterCashier').append(`<option value="${cashier}">${cashier}</option>`);
            });

            $('#btnApplyFilters').on('click', function() {
                const dateFrom = new Date($('#filterDateFrom').val());
                const dateTo = new Date($('#filterDateTo').val());
                const cashier = $('#filterCashier').val();

                const filtered = remittanceData.filter(remittance => {
                    const remittanceDate = new Date(remittance.date);
                    const dateMatch = remittanceDate >= dateFrom && remittanceDate <= dateTo;
                    const cashierMatch = !cashier || remittance.cashier_name === cashier;
                    return dateMatch && cashierMatch;
                });

                renderRemittanceHistory(filtered);
                showToast('success', 'Filters applied');
            });

            $('#btnResetFilters').on('click', function() {
                const today = new Date();
                const thirtyDaysAgo = new Date();
                thirtyDaysAgo.setDate(today.getDate() - 30);

                $('#filterDateTo').val(today.toISOString().split('T')[0]);
                $('#filterDateFrom').val(thirtyDaysAgo.toISOString().split('T')[0]);
                $('#filterCashier').val('');
                renderRemittanceHistory(remittanceData);
                showToast('info', 'Filters reset');
            });

            $('#btnExportCsv').on('click', exportToCsv);
        }

        function renderRemittanceHistory(history) {
            updateSummaryCards(history);
            renderDesktopTable(history);
            renderMobileCards(history);
        }

        function renderRemittanceDetails(data) {
            // Store current details for printing
            currentRemittanceDetails = data;

            const details = data.details;
            const denominations = data.denominations;

            $('#detailDate').text(new Date(details.remittance_date).toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            }));
            $('#detailCashier').text(details.cashier_name || '-');
            $('#detailCashierEmail').text(details.cashier_email || '-');
            $('#detailOutlet').text(details.outlet_name || '-');
            $('#detailShift').text(`${formatTime(details.shift_start)} - ${formatTime(details.shift_end)}`);
            $('#detailSubmittedAt').text(details.created_at ? new Date(details.created_at).toLocaleString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }) : '-');
            $('#detailCashOnHand').text(formatCurrency(details.amount_enclosed || 0));
            $('#detailGcash').text(formatCurrency(details.total_online_revenue || 0));
            $('#detailCashOut').text(parseFloat(details.cash_out) > 0 ? '-' + formatCurrency(details.cash_out) : '₱0.00');
            $('#detailTotalRemitted').text(formatCurrency((parseFloat(details.amount_enclosed) || 0) + (parseFloat(details.total_online_revenue) || 0) + (parseFloat(details.cash_out) || 0)));
            $('#detailTotalSales').text(formatCurrency(details.total_sales || 0));
            $('#detailTotalRemittedCompare').text(formatCurrency((parseFloat(details.amount_enclosed) || 0) + (parseFloat(details.total_online_revenue) || 0) + (parseFloat(details.cash_out) || 0)));

            // Populate sales by category
            $('#detailBakerySales').text(formatCurrency(details.bakery_sales || 0));
            $('#detailCoffeeSales').text(formatCurrency(details.coffee_sales || 0));
            $('#detailDoughSales').text(formatCurrency(details.dough_sales || 0));
            $('#detailGrocerySales').text(formatCurrency(details.grocery_sales || 0));

            // Populate denomination breakdown table
            if (denominations && denominations.length > 0) {
                let denominationHtml = '';
                denominations.forEach(denom => {
                    const quantity = parseInt(denom.quantity) || 0;
                    const total = parseFloat(denom.cash_on_hand) || 0;
                    const denomLabel = parseFloat(denom.denomination) === 0.25 ? '₱0.25' : '₱' + parseFloat(denom.denomination).toLocaleString('en-PH');

                    denominationHtml += `
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 font-medium text-gray-700">${denomLabel}</td>
                            <td class="px-3 py-2 text-center text-gray-700">${quantity}</td>
                            <td class="px-3 py-2 text-right font-semibold text-gray-800">${formatCurrency(total)}</td>
                        </tr>
                    `;
                });
                $('#denominationTableBody').html(denominationHtml);
            } else {
                $('#denominationTableBody').html('<tr><td colspan="3" class="px-3 py-2 text-center text-gray-500">No denominations recorded</td></tr>');
            }

            // Use variance_amount and is_short from database
            const varianceAmount = parseFloat(details.variance_amount) || 0;
            const isShort = parseInt(details.is_short) === 1;
            const variance = isShort ? -varianceAmount : varianceAmount;
            const varianceText = isShort ? '-' + formatCurrency(varianceAmount) + ' (Short)' : '+' + formatCurrency(varianceAmount) + ' (Over)';
            if (varianceAmount === 0) {
                $('#detailVariance').text('₱0.00 (Balanced)');
            } else {
                $('#detailVariance').text(varianceText);
            }
            const varianceContainer = $('#detailVarianceContainer');
            varianceContainer.removeClass('bg-green-100 bg-red-100 bg-gray-100 text-green-800 text-red-800');
            if (varianceAmount === 0) {
                varianceContainer.addClass('bg-gray-100');
            } else if (isShort) {
                varianceContainer.addClass('bg-red-100 text-red-800');
            } else {
                varianceContainer.addClass('bg-green-100 text-green-800');
            }

            if (parseFloat(details.cash_out) > 0 && details.cashout_reason) {
                $('#cashOutReasonContainer').show();
                $('#detailCashOutReason').text(details.cashout_reason);
            } else {
                $('#cashOutReasonContainer').hide();
                $('#detailCashOutReason').text('-');
            }
        }

        function renderDesktopTable(history) {
            if (dataTable) {
                dataTable.destroy();
                dataTable = null;
            }

            if (!history || history.length === 0) {
                $('#remittanceHistoryTableBody').html('<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-file-invoice-dollar text-4xl mb-3"></i><p>No remittance history found</p></td></tr>');
                return;
            }

            let html = '';
            history.forEach((remittance, index) => {
                const date = new Date(remittance.remittance_date);
                const dateStr = date.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });

                // Use variance_amount and is_short from database
                const varianceAmount = parseFloat(remittance.variance_amount) || 0;
                const isShort = parseInt(remittance.is_short) === 1;
                const varianceClass = isShort ? 'text-red-600 bg-red-100' : 'text-green-600 bg-green-100';
                let varianceText;
                if (varianceAmount === 0) {
                    varianceText = '₱0.00';
                } else {
                    varianceText = isShort ? '-' + formatCurrency(varianceAmount) : '+' + formatCurrency(varianceAmount);
                }

                html += `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">${dateStr}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">${remittance.cashier_name || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-semibold">${formatCurrency(remittance.total_sales || 0)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-primary font-bold">${formatCurrency(Number(remittance.amount_enclosed || 0) + Number(remittance.total_online_revenue || 0) + Number(remittance.cash_out || 0))}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${varianceClass}">${varianceText}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button type="button" class="btn-view-details text-primary py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-secondary hover:bg-gray-200" data-index="${index}">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            $('#remittanceHistoryTableBody').html(html);

            dataTable = new simpleDatatables.DataTable("#remittanceHistoryTable", {
                searchable: true,
                sortable: true,
                perPage: 10,
                perPageSelect: [5, 10, 25, 50],
                labels: {
                    placeholder: "Search remittances...",
                    noRows: "No remittances found",
                    info: "Showing {start} to {end} of {rows} records"
                }
            });

            // Re-bind click events after DataTable renders
            $('#remittanceHistoryTable').on('click', '.btn-view-details', function() {
                const index = $(this).data('index');
                const remittanceId = history[index].id || history[index].remittance_id;
                getRemittanceDetails(remittanceId);
                $('#remittanceDetailsModal').removeClass('hidden');
            });
        }

        function renderMobileCards(history) {
            if (!history || history.length === 0) {
                $('#remittanceHistoryCards').html(`
                    <div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500">
                        <i class="fas fa-file-invoice-dollar text-4xl mb-3"></i>
                        <p>No remittance history found</p>
                    </div>
                `);
                return;
            }

            let html = '';
            history.forEach((remittance, index) => {
                const date = new Date(remittance.remittance_date);
                const dateStr = date.toLocaleDateString('en-US', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric'
                });

                // Use variance_amount and is_short from database
                const varianceAmount = parseFloat(remittance.variance_amount) || 0;
                const isShort = parseInt(remittance.is_short) === 1;
                const varianceClass = isShort ? 'text-red-600 bg-red-100' : 'text-green-600 bg-green-100';
                const varianceIcon = isShort ? 'fa-arrow-down' : 'fa-arrow-up';
                let varianceText;
                if (varianceAmount === 0) {
                    varianceText = '₱0.00';
                } else {
                    varianceText = isShort ? '-' + formatCurrency(varianceAmount) : '+' + formatCurrency(varianceAmount);
                }

                html += `
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-300">
                        <!-- Card Header -->
                        <div class="bg-primary/90 px-4 py-3 border-b border-gray-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar text-white"></i>
                                    <span class="font-bold text-white">${dateStr}</span>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium ${varianceClass}">
                                    <i class="fas ${varianceIcon} mr-1"></i>${varianceText}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 mt-1 text-xs text-gray-300">
                                <i class="fas fa-user text-xs"></i>
                                <span>${remittance.cashier_name}</span>
                                <span class="text-gray-300">|</span>
                                <i class="fas fa-clock text-xs"></i>
                                <span>${formatTime(remittance.shift_start)} - ${formatTime(remittance.shift_end)}</span>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="p-4">
                            <!-- Remittance Breakdown -->
                            <div class="grid grid-cols-3 gap-2 mb-3">
                                <div class="text-center p-2 bg-green-50 rounded-lg">
                                    <i class="fas fa-money-bill-wave text-green-500 text-sm"></i>
                                    <p class="text-xs text-gray-500 mt-1">Cash</p>
                                    <p class="font-bold text-green-600 text-sm">${formatCurrency(remittance.amount_enclosed || 0)}</p>
                                </div>
                                <div class="text-center p-2 bg-blue-50 rounded-lg">
                                    <i class="fas fa-mobile-alt text-blue-500 text-sm"></i>
                                    <p class="text-xs text-gray-500 mt-1">Online</p>
                                    <p class="font-bold text-blue-600 text-sm">${formatCurrency(remittance.total_online_revenue || 0)}</p>
                                </div>
                                <div class="text-center p-2 bg-red-50 rounded-lg">
                                    <i class="fas fa-hand-holding-usd text-red-500 text-sm"></i>
                                    <p class="text-xs text-gray-500 mt-1">Cash Out</p>
                                    <p class="font-bold text-red-600 text-sm">${remittance.cash_out > 0 ? formatCurrency(remittance.cash_out) : '₱0.00'}</p>
                                </div>
                            </div>
                            
                            <!-- Sales vs Remitted -->
                            <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-gray-600"><i class="fas fa-chart-line text-primary mr-1"></i>Sales</span>
                                    <span class="font-semibold text-gray-700">${formatCurrency(remittance.total_sales || 0)}</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-primary/10 rounded">
                                    <span class="text-gray-600"><i class="fas fa-hand-holding-usd text-primary mr-1"></i>Remitted</span>
                                    <span class="font-semibold text-primary">${formatCurrency(Number(remittance.amount_enclosed || 0) + Number(remittance.total_online_revenue || 0) + Number(remittance.cash_out || 0))}</span>
                                </div>
                            </div>
                            
                            <!-- Total & Action -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <div>
                                    <p class="text-xs text-gray-500">Total Remitted</p>
                                    <p class="text-xl font-bold text-primary">${formatCurrency(Number(remittance.amount_enclosed || 0) + Number(remittance.total_online_revenue || 0) + Number(remittance.cash_out || 0))}</p>
                                </div>
                                <button type="button" class="btn-view-details-mobile px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary transition-all" data-index="${index}">
                                    <i class="fas fa-eye mr-1"></i>View
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#remittanceHistoryCards').html(html);

            // Bind click events for mobile cards
            $('.btn-view-details-mobile').on('click', function() {
                const index = $(this).data('index');
                const remittanceId = history[index].id || history[index].remittance_id;
                getRemittanceDetails(remittanceId);
                $('#remittanceDetailsModal').removeClass('hidden');
            });
        }

        function updateSummaryCards(history) {
            let totalRemitted = 0,
                remittanceCount = 0,
                cashRemitted = 0,
                gcashRemitted = 0;

            history.forEach(remittance => {
                totalRemitted += Number(remittance.amount_enclosed || 0) + Number(remittance.total_online_revenue || 0) + Number(remittance.cash_out || 0);
                remittanceCount++;
                cashRemitted += Number(remittance.amount_enclosed || 0);
                gcashRemitted += Number(remittance.total_online_revenue || 0);
            });

            $('#summaryTotalRemitted').text(formatCurrency(totalRemitted));
            $('#summaryRemittanceCount').text(remittanceCount);
            $('#summaryCashRemitted').text(formatCurrency(cashRemitted));
            $('#summaryGcashRemitted').text(formatCurrency(gcashRemitted));
        }

        function initModalHandlers() {
            $('#btnCloseDetailsModal, #btnCloseModal').on('click', () => $('#remittanceDetailsModal').addClass('hidden'));
            $('#remittanceDetailsModal').on('click', e => {
                if (e.target === e.currentTarget) $('#remittanceDetailsModal').addClass('hidden');
            });

            $('#btnPrintDetails').on('click', function() {
                printRemittanceSlip();
            });
        }

        function printRemittanceSlip() {
            if (!currentRemittanceDetails) {
                showToast('error', 'No remittance data available');
                return;
            }

            // Get current detail data from modal and stored details
            const cashierName = $('#detailCashier').text();
            const remittanceDate = $('#detailDate').text();
            const outletName = $('#detailOutlet').text();
            const shiftText = $('#detailShift').text();
            const [shiftStart, shiftEnd] = shiftText.split(' - ').map(t => t.trim());

            const amountEnclosed = $('#detailCashOnHand').text();
            const onlinePayment = $('#detailGcash').text();
            const cashOut = $('#detailCashOut').text();
            const bakerySales = $('#detailTotalSales').text(); // Using total sales for bakery display
            const grocerySales = '₱0.00';
            const coffeeSales = '₱0.00';
            const totalSales = $('#detailTotalSales').text();
            const variance = $('#detailVariance').text();

            // Build denominations table rows from stored data
            const denominations = currentRemittanceDetails.denominations || [];
            const denominationMap = {};

            // Create a map of denomination to quantity and total
            denominations.forEach(denom => {
                const denomValue = parseFloat(denom.denomination);
                denominationMap[denomValue] = {
                    quantity: parseInt(denom.quantity) || 0,
                    total: parseFloat(denom.cash_on_hand) || 0
                };
            });

            // Define all denominations in order
            const allDenominations = [{
                    value: 1000,
                    label: '1000x'
                },
                {
                    value: 500,
                    label: '500x'
                },
                {
                    value: 200,
                    label: '200x'
                },
                {
                    value: 100,
                    label: '100x'
                },
                {
                    value: 50,
                    label: '50x'
                },
                {
                    value: 20,
                    label: '20x'
                },
                {
                    value: 10,
                    label: '10x'
                },
                {
                    value: 5,
                    label: '5x'
                },
                {
                    value: 1,
                    label: '1x'
                },
                {
                    value: 0.25,
                    label: '.25x'
                }
            ];

            // Build denomination rows
            let denominationRows = '';
            allDenominations.forEach(denom => {
                const data = denominationMap[denom.value];
                const quantity = data ? data.quantity : 0;
                const total = data ? data.total : 0;
                const quantityText = quantity > 0 ? quantity : '';
                const totalText = total > 0 ? formatCurrency(total) : '';

                denominationRows += `<tr><td class="denom-col">${denom.label}</td><td class="count-col">${quantityText}</td><td class="equals-col">=</td><td class="total-col">${totalText}</td></tr>`;
            });

            // Build the print HTML using the same template as Sales.php
            const printHtml = `
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Cashier's Remittance Slip</title>
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    @page { size: 4in 6in; margin: 0; }
                    @media print {
                        html, body { width: 4in; height: 6in; margin: 0; padding: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                        .slip-container { border: none; }
                    }
                    body { font-family: Arial, sans-serif; font-size: 9pt; line-height: 1.2; width: 4in; height: 6in; padding: 0.1in; background: #fff; }
                    .slip-container { width: 100%; height: 100%; border: 1.5px solid #000; }
                    .slip-header { text-align: center; font-weight: bold; font-size: 11pt; padding: 6px 0; border-bottom: 1px solid #000; }
                    
                    /* Info table for aligned columns */
                    .info-table { width: 100%; border-collapse: collapse; }
                    .info-table td { border: 1px solid #000; border-top: none; padding: 2px 4px; height: 18px; font-size: 9pt; }
                    .info-table .label-col { font-weight: bold; width: 15%; white-space: nowrap; }
                    .info-table .value-col { width: 35%; }
                    
                    .section-header { text-align: center; font-weight: bold; font-size: 9pt; padding: 4px 0; border-bottom: 1px solid #000; background: #f5f5f5; }
                    .bills-table { width: 100%; border-collapse: collapse; }
                    .bills-table th, .bills-table td { border: 1px solid #000; padding: 1px 4px; text-align: center; height: 16px; font-size: 8.5pt; }
                    .bills-table th { background: #f0f0f0; font-weight: bold; }
                    .bills-table .denom-col { width: 22%; text-align: left; font-weight: bold; }
                    .bills-table .count-col { width: 20%; }
                    .bills-table .equals-col { width: 8%; }
                    .bills-table .total-col { width: 25%; text-align: right; padding-right: 6px; }
                    
                    /* Summary table */
                    .summary-table { width: 100%; border-collapse: collapse; }
                    .summary-table td { border: 1px solid #000; padding: 2px 4px; height: 18px; font-size: 9pt; }
                    .summary-table .sum-label { font-weight: bold; width: 25%; }
                    .summary-table .sum-value { width: 25%; text-align: right; }
                    .summary-table .total-row td { background: #f0f0f0; }
                    .summary-table .variance-row td { font-weight: bold; }
                    
                    .total-row { background: #f0f0f0; }
                    .variance-row .summary-label, .variance-row .summary-value { font-weight: bold; }
                </style>
            </head>
            <body>
                <div class="slip-container">
                    <div class="slip-header">CASHIER'S REMITTANCE SLIP</div>
                    
                    <!-- Info section with aligned columns -->
                    <table class="info-table">
                        <tr>
                            <td class="label-col">NAMES:</td>
                            <td class="value-col" colspan="3">${cashierName}</td>
                        </tr>
                        <tr>
                            <td class="label-col">DATE:</td>
                            <td class="value-col">${remittanceDate}</td>
                            <td class="label-col">OUTLET:</td>
                            <td class="value-col">${outletName}</td>
                        </tr>
                        <tr>
                            <td class="label-col">SHIFT:</td>
                            <td class="value-col"> ${shiftStart}</td>
                            <td class="label-col">TO:</td>
                            <td class="value-col"> ${shiftEnd}</td>
                        </tr>
                    </table>
                    
                    <div class="section-header">CASH SALES/CHANGE FUND</div>
                    <table class="bills-table">
                        <thead><tr><th class="denom-col">BILLS/COINS</th><th class="count-col"></th><th class="equals-col">=</th><th class="total-col"></th></tr></thead>
                        <tbody>
                            ${denominationRows}
                        </tbody>
                    </table>
                    
                    <!-- Summary section with aligned columns -->
                    <table class="summary-table">
                        <tr class="total-row">
                            <td class="sum-label" colspan="2">TOTAL AMOUNT ENCLOSED:</td>
                            <td class="sum-value" colspan="2">${amountEnclosed}</td>
                        </tr>
                        <tr>
                            <td class="sum-label" colspan="2">ONLINE PAYMENT:</td>
                            <td class="sum-value" colspan="2">${onlinePayment}</td>
                        </tr>
                        <tr>
                            <td class="sum-label">CASH OUT:</td>
                            <td class="sum-value">${cashOut}</td>
                            <td class="sum-value" colspan="2" style="text-align:left;font-size:8pt;">${$('#detailCashOutReason').text()}</td>
                        </tr>
                        <tr>
                            <td class="sum-label">BAKERY:</td>
                            <td class="sum-value">${bakerySales}</td>
                            <td class="sum-label">GROCERY:</td>
                            <td class="sum-value">${grocerySales}</td>
                        </tr>
                        <tr>
                            <td class="sum-label" colspan="2">COFFEE:</td>
                            <td class="sum-value" colspan="2">${coffeeSales}</td>
                        </tr>
                        <tr class="total-row">
                            <td class="sum-label" colspan="2">TOTAL SALES:</td>
                            <td class="sum-value" colspan="2">${totalSales}</td>
                        </tr>
                        <tr class="variance-row">
                            <td class="sum-label" colspan="2">OVERAGE/SHORTAGE:</td>
                            <td class="sum-value" colspan="2">${variance}</td>
                        </tr>
                    </table>
                </div>
            </body>
            </html>`;

            // Write to hidden iframe and print
            if (!document.getElementById('printFrame')) {
                const iframe = document.createElement('iframe');
                iframe.id = 'printFrame';
                iframe.style.position = 'absolute';
                iframe.style.top = '-9999px';
                iframe.style.left = '-9999px';
                iframe.style.width = '0';
                iframe.style.height = '0';
                iframe.style.border = 'none';
                document.body.appendChild(iframe);
            }

            const printFrame = document.getElementById('printFrame');
            const frameDoc = printFrame.contentWindow || printFrame.contentDocument.document || printFrame.contentDocument;

            frameDoc.document.open();
            frameDoc.document.write(printHtml);
            frameDoc.document.close();

            // Wait for content to load then print
            setTimeout(function() {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
            }, 250);
        }

        function exportToCsv() {
            if (!remittanceData || remittanceData.length === 0) {
                showToast('warning', 'No data to export');
                return;
            }

            const headers = [
                'Date',
                'Cashier',
                'Email',
                'Shift',
                'Cash On Hand',
                'Online Payments',
                'Cash Out',
                'Cash Out Reason',
                'Total Sales',
                'Total Remitted',
                'Variance'
            ];
            const rows = remittanceData.map(remittance => {
                const varianceAmount = parseFloat(remittance.variance_amount) || 0;
                const isShort = parseInt(remittance.is_short) === 1;
                const variance = isShort ? -varianceAmount : varianceAmount;
                return [
                    remittance.remittance_date,
                    remittance.cashier_name || '',
                    remittance.cashier_email || '',
                    (remittance.shift_start || '') + ' - ' + (remittance.shift_end || ''),
                    remittance.amount_enclosed || 0,
                    remittance.total_online_revenue || 0,
                    remittance.cash_out || 0,
                    remittance.cashout_reason || '',
                    remittance.total_sales || 0,
                    Number(remittance.amount_enclosed || 0) + Number(remittance.total_online_revenue || 0) + Number(remittance.cash_out || 0),
                    variance
                ];
            });

            let csv = headers.join(',') + '\n';
            rows.forEach(row => {
                csv += row.map(cell => `"${cell}"`).join(',') + '\n';
            });

            const blob = new Blob([csv], {
                type: 'text/csv'
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `remittance_history_${$('#filterDateFrom').val()}_to_${$('#filterDateTo').val()}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            showToast('success', 'Remittance history exported successfully');
        }

        function formatCurrency(amount) {
            return '₱' + parseFloat(amount).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function formatTime(timeStr) {
            if (!timeStr) return '--:--';
            const [hours, minutes] = timeStr.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes || '00'} ${ampm}`;
        }
    </script>
</body>