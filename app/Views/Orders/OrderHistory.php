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
                    <li><a href="<?= base_url('Order') ?>" class="hover:text-primary">Order</a></li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li class="text-gray-700">Order History</li>
                </ol>
            </nav>

            <!-- Today's Sales Summary Cards -->
            <div class="mb-4 grid grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Total Orders Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-lg">
                            <i class="fas fa-receipt text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Orders</p>
                            <p id="todayTotalOrders" class="text-xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
                
                <!-- Total Revenue Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-lg">
                            <i class="fas fa-peso-sign text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Revenue</p>
                            <p id="todayTotalRevenue" class="text-xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                
                <!-- Items Sold Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-lg">
                            <i class="fas fa-shopping-basket text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Items Sold</p>
                            <p id="todayItemsSold" class="text-xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
                
                <!-- Stock Summary Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-lg">
                            <i class="fas fa-boxes-stacked text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">In Stock</p>
                            <p id="todayStockCount" class="text-xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Summary Table (Collapsible) -->
            <div class="mb-4 bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
                <button id="toggleStockSummary" class="w-full p-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 flex items-center justify-center bg-gray-50 rounded-lg">
                            <i class="fas fa-box-open text-gray-600"></i>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-800">Today's Stock Summary</span>
                            <span id="stockSummaryBadge" class="ml-2 px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">0 items</span>
                        </div>
                    </div>
                    <i id="stockChevron" class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                </button>
                <div id="stockSummaryContent" class="border-t border-gray-100">
                    <div class="p-4">
                        <div id="stockSummaryBody" class="space-y-3">
                            <div class="text-center text-gray-500 py-8">Loading stock data...</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Order History</h2>
                    <div class="flex flex-wrap gap-2">
                        <a href="<?= base_url('Order') ?>" class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-plus mr-2"></i>New Order
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
                        <button id="btnApplyFilters" type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">Apply</button>
                        <button id="btnResetFilters" type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">Reset</button>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 sm:mb-0">
                <table id="ordersTable" class="min-w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Order Number</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Date & Time</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Type</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Payment</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Amount</th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody">
                        <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-2">Loading orders...</p></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div id="orderDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg mx-auto border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
            <div id="receiptContent" class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="text-center flex-1">
                        <h2 class="text-2xl font-bold text-gray-800">EngBakery</h2>
                        <p class="text-sm text-gray-500">Order Receipt</p>
                    </div>
                    <button type="button" id="btnCloseOrderDetails" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                </div>
                <div class="border-t border-dashed border-gray-300 py-3">
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div><span class="text-gray-600">Order #:</span></div>
                        <div class="text-right font-semibold" id="detailOrderNumber">-</div>
                        <div><span class="text-gray-600">Date:</span></div>
                        <div class="text-right" id="detailOrderDate">-</div>
                        <div><span class="text-gray-600">Time:</span></div>
                        <div class="text-right" id="detailOrderTime">-</div>
                        <div><span class="text-gray-600">Type:</span></div>
                        <div class="text-right" id="detailOrderType">-</div>
                        <div><span class="text-gray-600">Payment:</span></div>
                        <div class="text-right" id="detailPaymentMethod">-</div>
                    </div>
                </div>
                <div class="border-t border-dashed border-gray-300 py-3">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Items</h4>
                    <div id="orderItemsList" class="space-y-2 text-sm"></div>
                </div>
                <div class="border-t border-dashed border-gray-300 py-3 space-y-2">
                    <div class="flex justify-between font-bold text-lg">
                        <span>TOTAL:</span>
                        <span id="detailTotalAmount">₱0.00</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Amount Received:</span>
                        <span id="detailAmountReceived">₱0.00</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Change:</span>
                        <span id="detailChange">₱0.00</span>
                    </div>
                </div>
            </div>
            <div class="px-6 pb-6 flex gap-2">
                <button type="button" id="btnPrintReceipt" class="flex-1 px-4 py-3 text-sm font-medium text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition-all">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
                <button type="button" id="btnVoidOrder" class="px-4 py-3 text-sm font-medium text-red-600 border border-red-300 rounded-lg hover:bg-red-600 hover:text-white transition-all">
                    <i class="fas fa-ban mr-2"></i>Void
                </button>
                <button type="button" id="btnCloseModal" class="flex-1 px-4 py-3 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary transition-all">Close</button>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a89dedcb22.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    
    <script>
        window.BASE_URL = '<?= rtrim(site_url(), '/') ?>/';
        let dataTable = null;
        let currentOrderId = null;

        $(document).ready(function() {
            // Set today's date in the date filters
            const today = new Date().toISOString().split('T')[0];
            $('#filterDateFrom').val(today);
            $('#filterDateTo').val(today);
            
            loadOrders(today, today); // Load today's orders by default
            loadTodaysSummary();
            loadStockSummary();
            initFilters();
            initOrderDetailsModal();
            initStockSummaryToggle();
        });

        // Load Today's Sales Summary
        function loadTodaysSummary() {
            $.ajax({
                url: BASE_URL + 'Order/GetTodaysSales',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#todayTotalOrders').text(response.data.total_orders || 0);
                        $('#todayTotalRevenue').text('₱' + parseFloat(response.data.total_revenue || 0).toFixed(2));
                        $('#todayItemsSold').text(response.data.total_items_sold || 0);
                    }
                }
            });
        }

        // Load Stock Summary
        function loadStockSummary() {
            $.ajax({
                url: BASE_URL + 'Order/GetTodaysStockSummary',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.data && response.data.length > 0) {
                        // Separate bread and drinks
                        const breadItems = response.data.filter(item => item.category === 'bread');
                        const drinkItems = response.data.filter(item => item.category === 'drinks');
                        
                        let html = '';
                        let totalProducts = response.data.length;
                        
                        // Render Bread Section
                        if (breadItems.length > 0) {
                            html += `
                                <div class="mb-4">
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                                        <i class="fas fa-bread-slice text-amber-500"></i> Bread (${breadItems.length})
                                    </h4>
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                            `;
                            
                            breadItems.forEach(item => {
                                const beginning = parseInt(item.beginning_stock) || 0;
                                const pullOut = parseInt(item.pull_out_quantity) || 0;
                                const remaining = parseInt(item.ending_stock) || 0;
                                const sold = beginning - remaining - pullOut;
                                
                                const remainingClass = remaining <= 5 ? 'text-red-600' : remaining <= 10 ? 'text-orange-500' : 'text-gray-700';
                                const stockStatus = remaining <= 5 ? 'Low Stock' : remaining <= 10 ? 'Running Low' : 'In Stock';
                                const statusClass = remaining <= 5 ? 'bg-red-50 text-red-700' : remaining <= 10 ? 'bg-orange-50 text-orange-700' : 'bg-green-50 text-green-700';
                                
                                html += `
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-gray-300 transition-colors">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex-1">
                                                <h5 class="font-medium text-gray-900 text-sm">${item.product_name}</h5>
                                                <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded ${statusClass}">${stockStatus}</span>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-4 gap-2 text-center mt-3 pt-3 border-t border-gray-200">
                                            <div>
                                                <div class="text-xs text-gray-500">Begin</div>
                                                <div class="font-semibold text-gray-700">${beginning}</div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500">Sold</div>
                                                <div class="font-semibold text-green-600">${sold > 0 ? sold : 0}</div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500">Pull</div>
                                                <div class="font-semibold text-orange-600">${pullOut}</div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500">Left</div>
                                                <div class="font-semibold ${remainingClass}">${remaining}</div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            
                            html += `
                                    </div>
                                </div>
                            `;
                        }
                        
                        // Render Drinks Section
                        if (drinkItems.length > 0) {
                            html += `
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                                        <i class="fas fa-mug-hot text-blue-500"></i> Drinks (${drinkItems.length})
                                    </h4>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            `;
                            
                            drinkItems.forEach(item => {
                                const beginning = parseInt(item.beginning_stock) || 0;
                                const pullOut = parseInt(item.pull_out_quantity) || 0;
                                const remaining = parseInt(item.ending_stock) || 0;
                                const sold = beginning - remaining - pullOut;
                                
                                html += `
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-gray-300 transition-colors text-center">
                                        <div class="font-medium text-gray-900 text-sm mb-2">${item.product_name}</div>
                                        <div class="text-xs text-gray-500 mb-1">Sold</div>
                                        <div class="text-2xl font-bold text-green-600">${sold > 0 ? sold : 0}</div>
                                    </div>
                                `;
                            });
                            
                            html += `
                                    </div>
                                </div>
                            `;
                        }
                        
                        $('#stockSummaryBody').html(html);
                        $('#todayStockCount').text(totalProducts);
                        $('#stockSummaryBadge').text(totalProducts + ' items');
                    } else {
                        $('#stockSummaryBody').html('<div class="text-center text-gray-500 py-8">No inventory data for today. <a href="' + BASE_URL + 'Inventory" class="text-primary hover:underline font-medium">Create inventory first</a>.</div>');
                        $('#todayStockCount').text('0');
                        $('#stockSummaryBadge').text('No inventory');
                    }
                },
                error: function() {
                    $('#stockSummaryBody').html('<div class="text-center text-red-500 py-8">Error loading stock data</div>');
                }
            });
        }

        // Toggle Stock Summary Section
        function initStockSummaryToggle() {
            // Keep stock summary expanded by default
            $('#stockSummaryContent').removeClass('hidden');
            $('#stockChevron').addClass('rotate-180');
            
            $('#toggleStockSummary').on('click', function() {
                $('#stockSummaryContent').toggleClass('hidden');
                $('#stockChevron').toggleClass('rotate-180');
            });
        }

        function loadOrders(dateFrom = null, dateTo = null) {
            let url = BASE_URL + 'Order/GetOrderHistory';
            const params = [];
            if (dateFrom) params.push('date_from=' + dateFrom);
            if (dateTo) params.push('date_to=' + dateTo);
            if (params.length) url += '?' + params.join('&');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        renderOrders(response.data);
                    } else {
                        $('#ordersTableBody').html('<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Failed to load orders</td></tr>');
                    }
                },
                error: function() {
                    $('#ordersTableBody').html('<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Error loading orders</td></tr>');
                }
            });
        }

        function renderOrders(orders) {
            if (dataTable) {
                dataTable.destroy();
                dataTable = null;
            }

            if (!orders || orders.length === 0) {
                $('#ordersTableBody').html('<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-receipt text-4xl mb-3"></i><p>No orders found</p></td></tr>');
                return;
            }

            let html = '';
            orders.forEach(order => {
                const orderDate = new Date(order.date_created + ' ' + order.time_created);
                const dateStr = orderDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                const timeStr = orderDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                const typeClass = order.order_type === 'foodpanda' ? 'bg-pink-100 text-pink-800' : 'bg-blue-100 text-blue-800';
                const typeName = order.order_type === 'foodpanda' ? 'Foodpanda' : 'Walk-in';

                html += `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">${order.order_number}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">${dateStr}<br><span class="text-xs text-gray-500">${timeStr}</span></td>
                        <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 rounded-full text-xs font-medium ${typeClass}">${typeName}</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 capitalize">${order.payment_method}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-semibold">₱${parseFloat(order.total_payment_due).toFixed(2)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button type="button" class="btn-view-order text-primary py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-secondary hover:bg-gray-200" data-order-id="${order.order_id}">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            $('#ordersTableBody').html(html);

            dataTable = new simpleDatatables.DataTable("#ordersTable", {
                searchable: true,
                sortable: true,
                perPage: 10,
                perPageSelect: [5, 10, 25, 50],
                labels: {
                    placeholder: "Search orders...",
                    noRows: "No orders found",
                    info: "Showing {start} to {end} of {rows} orders"
                }
            });

            $('.btn-view-order').on('click', function() {
                openOrderDetails($(this).data('order-id'));
            });
        }

        function initFilters() {
            $('#btnApplyFilters').on('click', function() {
                const dateFrom = $('#filterDateFrom').val();
                const dateTo = $('#filterDateTo').val();
                loadOrders(dateFrom, dateTo);
            });

            $('#btnResetFilters').on('click', function() {
                $('#filterDateFrom').val('');
                $('#filterDateTo').val('');
                loadOrders();
            });
        }

        function initOrderDetailsModal() {
            $('#btnCloseOrderDetails, #btnCloseModal').on('click', () => $('#orderDetailsModal').addClass('hidden'));
            $('#orderDetailsModal').on('click', e => { if (e.target === e.currentTarget) $('#orderDetailsModal').addClass('hidden'); });

            $('#btnPrintReceipt').on('click', function() {
                const content = $('#receiptContent').clone();
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <html>
                    <head>
                        <title>Receipt - EngBakery</title>
                        <style>
                            body { font-family: 'Courier New', monospace; padding: 20px; max-width: 300px; margin: 0 auto; }
                            .text-center { text-align: center; }
                            .font-bold { font-weight: bold; }
                            .text-2xl { font-size: 1.5rem; }
                            .text-lg { font-size: 1.1rem; }
                            .text-sm { font-size: 0.9rem; }
                            .border-t { border-top: 1px dashed #ccc; padding-top: 10px; margin-top: 10px; }
                            .flex { display: flex; justify-content: space-between; }
                            .space-y-2 > * + * { margin-top: 0.5rem; }
                            .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5px; }
                            .text-right { text-align: right; }
                            #btnCloseOrderDetails { display: none; }
                        </style>
                    </head>
                    <body>${content.html()}</body>
                    </html>
                `);
                printWindow.document.close();
                printWindow.print();
            });

            $('#btnVoidOrder').on('click', function() {
                if (!currentOrderId) return;
                Confirm.show('Are you sure you want to void this order? This action cannot be undone.', function() {
                    voidOrder(currentOrderId);
                });
            });
        }

        function openOrderDetails(orderId) {
            currentOrderId = orderId;
            $.ajax({
                url: BASE_URL + 'Order/GetOrderDetails/' + orderId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const order = response.data.order;
                        const items = response.data.items;
                        const orderDate = new Date(order.date_created + ' ' + order.time_created);

                        $('#detailOrderNumber').text(order.order_number);
                        $('#detailOrderDate').text(orderDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }));
                        $('#detailOrderTime').text(orderDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }));
                        $('#detailOrderType').text(order.order_type === 'foodpanda' ? 'Foodpanda' : 'Walk-in');
                        $('#detailPaymentMethod').text(order.payment_method.charAt(0).toUpperCase() + order.payment_method.slice(1));

                        let itemsHtml = '';
                        items.forEach(item => {
                            itemsHtml += `
                                <div class="flex justify-between">
                                    <span>${item.product_name} x${item.amout}</span>
                                    <span>₱${parseFloat(item.total_cost_of_item).toFixed(2)}</span>
                                </div>
                            `;
                        });
                        $('#orderItemsList').html(itemsHtml);

                        $('#detailTotalAmount').text('₱' + parseFloat(order.total_payment_due).toFixed(2));
                        $('#detailAmountReceived').text('₱' + parseFloat(order.amount_received).toFixed(2));
                        $('#detailChange').text('₱' + parseFloat(order.amount_change).toFixed(2));

                        $('#orderDetailsModal').removeClass('hidden');
                    } else {
                        Toast.error('Failed to load order details');
                    }
                },
                error: function() {
                    Toast.error('Error loading order details');
                }
            });
        }

        function voidOrder(orderId) {
            $.ajax({
                url: BASE_URL + 'Order/VoidOrder/' + orderId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Toast.success('Order voided successfully');
                        $('#orderDetailsModal').addClass('hidden');
                        loadOrders();
                    } else {
                        Toast.error(response.message || 'Failed to void order');
                    }
                },
                error: function() {
                    Toast.error('Error voiding order');
                }
            });
        }
    </script>
