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
                    <li><a href="<?= base_url('Inventory') ?>" class="hover:text-primary">Inventory</a></li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li class="text-gray-700">History</li>
                </ol>
            </nav>

            <!-- Summary Cards -->
            <div class="mb-4 grid grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Total Records Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-lg">
                            <i class="fas fa-calendar-alt text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Records</p>
                            <p id="totalRecords" class="text-xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
                
                <!-- Avg Items Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-lg">
                            <i class="fas fa-box text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Avg Items</p>
                            <p id="avgItems" class="text-xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
                
                <!-- Total Sold Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-lg">
                            <i class="fas fa-shopping-cart text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Total Sold</p>
                            <p id="totalSold" class="text-xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
                
                <!-- Total Pull Out Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-lg">
                            <i class="fas fa-undo text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Total Pull Out</p>
                            <p id="totalPullOut" class="text-xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Inventory History</h2>
                    <div class="flex flex-wrap gap-2">
                        <a href="<?= base_url('Inventory') ?>" class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Today
                        </a>
                    </div>
                </div>
                <div class="border-t border-gray-200 my-4"></div>
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
                    </div>
                    <div class="flex gap-2 sm:justify-end">
                        <button id="btnApplyFilter" type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">Apply</button>
                        <button id="btnResetFilter" type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">Reset</button>
                    </div>
                </div>
            </div>

            <!-- History Table - Desktop View -->
            <div class="hidden lg:block p-4 bg-white rounded-lg shadow-md mb-20 sm:mb-0">
                <!-- DataTable controls (top) remain here, outside scroll -->
                <div class="datatable-top">
                    <!-- ...existing code for dropdown and search... -->
                </div>
                <!-- Only the table is horizontally scrollable -->
                <div style="overflow-x: auto; width: 100%;">
                    <table id="historyTable" class="min-w-full text-sm text-left text-gray-500">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 whitespace-nowrap bg-white border-r border-gray-200 sticky left-0 z-20" style="min-width:120px;">Date</th>
                                <th scope="col" class="px-6 py-3 whitespace-nowrap bg-white border-r border-gray-200 sticky left-[120px] z-20" style="min-width:120px;">Time</th>
                                <th scope="col" class="px-6 py-3 whitespace-nowrap text-center">Products</th>
                                <th scope="col" class="px-6 py-3 whitespace-nowrap">Contents</th>
                                <th scope="col" class="px-6 py-3 whitespace-nowrap text-center">Beginning</th>
                                <th scope="col" class="px-6 py-3 whitespace-nowrap text-center">Sold</th>
                                <th scope="col" class="px-6 py-3 whitespace-nowrap text-center">Pull Out</th>
                                <th scope="col" class="px-6 py-3 whitespace-nowrap text-center">Ending</th>
                                <th scope="col" class="px-6 py-3 whitespace-nowrap text-right">Sales</th>
                            </tr>
                        </thead>
                    <tbody id="historyTableBody">
                        <tr><td colspan="9" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-2">Loading history...</p></td></tr>
                    </tbody>
                    </table>
                </div>
                <!-- DataTable controls (bottom) remain here, outside scroll -->
                <div class="datatable-bottom">
                    <!-- ...existing code for info and pagination... -->
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden space-y-3 mb-20" id="historyCards">
                <!-- Cards will be populated by JS -->
                <div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500">
                    <i class="fas fa-spinner fa-spin text-2xl"></i>
                    <p class="mt-2">Loading history...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Contents Modal -->
    <div id="contentsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-3xl mx-auto border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-hidden flex flex-col">
            <div class="sticky top-0 bg-white z-10 p-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Inventory Contents</h3>
                    <p class="text-sm text-gray-500" id="contentsModalSubtitle">-</p>
                </div>
                <button type="button" id="btnCloseContentsModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-600">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2">Product</th>
                                <th class="px-4 py-2 text-center">Begin</th>
                                <th class="px-4 py-2 text-center">Sold</th>
                                <th class="px-4 py-2 text-center">Pull Out</th>
                                <th class="px-4 py-2 text-center">Ending</th>
                            </tr>
                        </thead>
                        <tbody id="contentsModalBody">
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No items found</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    
    <script>
        const BASE_URL = '<?= base_url() ?>';
        let historyData = [];
        let dataTable = null;

        $(document).ready(function() {
            // Set default date range (last 30 days)
            const today = new Date();
            const thirtyDaysAgo = new Date(today);
            thirtyDaysAgo.setDate(today.getDate() - 30);
            
            $('#filterDateTo').val(formatDateInput(today));
            $('#filterDateFrom').val(formatDateInput(thirtyDaysAgo));
            
            loadInventoryHistory();
            
            // Event handlers
            $('#btnApplyFilter').on('click', loadInventoryHistory);
            $('#btnResetFilter').on('click', function() {
                $('#filterDateFrom').val(formatDateInput(thirtyDaysAgo));
                $('#filterDateTo').val(formatDateInput(today));
                loadInventoryHistory();
            });

            $('#btnCloseContentsModal').on('click', function() {
                $('#contentsModal').addClass('hidden');
            });

            $('#contentsModal').on('click', function(e) {
                if (e.target === this) {
                    $('#contentsModal').addClass('hidden');
                }
            });

            $('#historyTable').on('click', '.btn-view-contents', function() {
                const index = parseInt($(this).data('index'));
                openContentsModal(index);
            });
        });

        function formatDateInput(date) {
            return date.toISOString().split('T')[0];
        }

        function formatDisplayDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric',
                year: 'numeric'
            });
        }

        function formatTime(timeStr) {
            if (!timeStr) return '--:--';
            const [hours, minutes] = timeStr.split(':');
            const h = parseInt(hours);
            const ampm = h >= 12 ? 'PM' : 'AM';
            const h12 = h % 12 || 12;
            return `${h12}:${minutes} ${ampm}`;
        }

        function loadInventoryHistory() {
            const dateFrom = $('#filterDateFrom').val();
            const dateTo = $('#filterDateTo').val();
            
            // Show loading
            $('#historyTableBody').html('<tr><td colspan="9" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-2">Loading history...</p></td></tr>');
            
            $.ajax({
                url: BASE_URL + 'Inventory/FetchHistory',
                type: 'GET',
                data: { date_from: dateFrom, date_to: dateTo },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        historyData = response.data;
                        renderHistory();
                        updateSummary();
                        initDataTable();
                    } else {
                        showEmptyState();
                    }
                },
                error: function() {
                    showEmptyState('Error loading history');
                }
            });
        }

        function updateSummary() {
            const totalRecords = historyData.length;
            let totalItems = 0;
            let totalSold = 0;
            let totalPullOut = 0;
            
            historyData.forEach((inv, index) => {
                totalItems += parseInt(inv.total_items) || 0;
                totalSold += parseInt(inv.total_sold) || 0;
                totalPullOut += parseInt(inv.total_pull_out) || 0;
            });
            
            $('#totalRecords').text(totalRecords);
            $('#avgItems').text(totalRecords > 0 ? Math.round(totalItems / totalRecords) : 0);
            $('#totalSold').text(totalSold);
            $('#totalPullOut').text(totalPullOut);
        }

        function renderHistory() {
            if (historyData.length === 0) {
                showEmptyState();
                return;
            }
            
            // Render Desktop Table
            let tableHtml = '';
            const todayStr = new Date().toISOString().split('T')[0];
            
            historyData.forEach((inv, index) => {
                const isToday = inv.inventory_date === todayStr;
                const totalSales = parseFloat(inv.total_sales) || 0;
                
                tableHtml += `
                    <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 bg-white border-r border-gray-200 sticky left-0 z-10" style="min-width:120px;">
                            <span class="font-medium text-gray-900">${formatDisplayDate(inv.inventory_date)}</span>
                            ${isToday ? '<span class="ml-2 px-2 py-0.5 text-xs font-medium bg-primary text-white rounded-full">Today</span>' : ''}
                        </td>
                            <td class="px-6 py-4 text-gray-600 bg-white border-r border-gray-200 sticky left-[120px] z-10" style="min-width:120px;">${formatTime(inv.time_start)} - ${formatTime(inv.time_end)}</td>
                        <td class="px-6 py-4 text-center font-medium text-gray-700">${inv.total_items}</td>
                        <td class="px-6 py-4 text-gray-700">
                            <div class="max-w-[260px]">
                                <p class="text-sm truncate" title="${(inv.products_preview || '').replace(/"/g, '&quot;')}">${inv.products_preview || '-'}</p>
                                <button type="button" class="btn-view-contents mt-1 text-xs text-primary hover:underline" data-index="${index}">View items</button>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center text-blue-600 font-medium">${inv.total_beginning}</td>
                        <td class="px-6 py-4 text-center text-green-600 font-medium">${inv.total_sold > 0 ? inv.total_sold : '-'}</td>
                        <td class="px-6 py-4 text-center text-amber-600 font-medium">${inv.total_pull_out > 0 ? inv.total_pull_out : '-'}</td>
                        <td class="px-6 py-4 text-center font-medium text-gray-700">${inv.total_ending}</td>
                        <td class="px-6 py-4 text-right font-semibold text-primary">₱${totalSales.toFixed(2)}</td>
                    </tr>
                `;
            });
            
            $('#historyTableBody').html(tableHtml);
            
            // Render Mobile Cards
            renderMobileCards();
        }

        function renderMobileCards() {
            if (historyData.length === 0) {
                $('#historyCards').html(`
                    <div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3"></i>
                        <p>No inventory records found</p>
                    </div>
                `);
                return;
            }

            let cardsHtml = '';
            const todayStr = new Date().toISOString().split('T')[0];

            historyData.forEach((inv, index) => {
                const isToday = inv.inventory_date === todayStr;
                const totalSales = parseFloat(inv.total_sales) || 0;
                const date = new Date(inv.inventory_date);
                const dateStr = date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });

                cardsHtml += `
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                        <!-- Card Header -->
                        <div class="bg-primary/90 px-4 py-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar text-white"></i>
                                    <span class="font-bold text-white">${dateStr}</span>
                                    ${isToday ? '<span class="px-2 py-0.5 text-xs font-medium bg-white text-primary rounded-full">Today</span>' : ''}
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mt-1 text-xs text-gray-200">
                                <i class="fas fa-clock text-xs"></i>
                                <span>${formatTime(inv.time_start)} - ${formatTime(inv.time_end)}</span>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="p-4">
                            <!-- Stats Grid -->
                            <div class="grid grid-cols-4 gap-2 mb-3">
                                <div class="text-center p-2 bg-blue-50 rounded-lg">
                                    <p class="text-xs text-gray-500">Begin</p>
                                    <p class="font-bold text-blue-600">${inv.total_beginning || 0}</p>
                                </div>
                                <div class="text-center p-2 bg-green-50 rounded-lg">
                                    <p class="text-xs text-gray-500">Sold</p>
                                    <p class="font-bold text-green-600">${inv.total_sold || 0}</p>
                                </div>
                                <div class="text-center p-2 bg-amber-50 rounded-lg">
                                    <p class="text-xs text-gray-500">Pull Out</p>
                                    <p class="font-bold text-amber-600">${inv.total_pull_out || 0}</p>
                                </div>
                                <div class="text-center p-2 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500">Ending</p>
                                    <p class="font-bold text-gray-700">${inv.total_ending || 0}</p>
                                </div>
                            </div>
                            
                            <!-- Footer -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <div>
                                    <p class="text-xs text-gray-500">${inv.total_items || 0} Products</p>
                                    <p class="text-xs text-gray-500 truncate max-w-[170px]" title="${(inv.products_preview || '').replace(/"/g, '&quot;')}">${inv.products_preview || '-'}</p>
                                    <button type="button" class="btn-view-contents-mobile text-xs text-primary hover:underline mt-1" data-index="${index}">View items</button>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Total Sales</p>
                                    <p class="text-xl font-bold text-primary">₱${totalSales.toFixed(2)}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#historyCards').html(cardsHtml);

            $('.btn-view-contents-mobile').off('click').on('click', function() {
                const index = parseInt($(this).data('index'));
                openContentsModal(index);
            });
        }

        function openContentsModal(index) {
            const inventory = historyData[index];
            if (!inventory) return;

            const details = inventory.products_detail || [];
            const displayDate = formatDisplayDate(inventory.inventory_date);
            $('#contentsModalSubtitle').text(`${displayDate} • ${details.length} products`);

            if (details.length === 0) {
                $('#contentsModalBody').html('<tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No items found</td></tr>');
                $('#contentsModal').removeClass('hidden');
                return;
            }

            let rows = '';
            details.forEach(item => {
                rows += `
                    <tr class="border-b">
                        <td class="px-4 py-2 font-medium text-gray-800">${item.product_name}</td>
                        <td class="px-4 py-2 text-center text-blue-600">${item.beginning_stock}</td>
                        <td class="px-4 py-2 text-center text-green-600">${item.quantity_sold}</td>
                        <td class="px-4 py-2 text-center text-amber-600">${item.pull_out_quantity}</td>
                        <td class="px-4 py-2 text-center text-gray-700">${item.ending_stock}</td>
                    </tr>
                `;
            });

            $('#contentsModalBody').html(rows);
            $('#contentsModal').removeClass('hidden');
        }

        function initDataTable() {
            // Destroy existing DataTable if exists
            if (dataTable) {
                dataTable.destroy();
            }
            
            // Initialize new DataTable
            const table = document.getElementById('historyTable');
            if (table && historyData.length > 0) {
                dataTable = new simpleDatatables.DataTable(table, {
                    searchable: true,
                    perPageSelect: [10, 25, 50, 100],
                    labels: {
                        placeholder: "Search inventory...",
                        noRows: "No inventory records found",
                        info: "Showing {start} to {end} of {rows} entries"
                    }
                });
            }
        }

        function showEmptyState(message = 'No inventory records found') {
            $('#historyTableBody').html(`
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3"></i>
                        <p>${message}</p>
                    </td>
                </tr>
            `);
            $('#historyCards').html(`
                <div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-3"></i>
                    <p>${message}</p>
                </div>
            `);
        }

    </script>
</body>
