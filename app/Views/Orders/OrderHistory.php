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
                    <li>
                        <a href="<?= base_url('Order') ?>" class="hover:text-primary">Order</a>
                    </li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li class="text-gray-700">Order History</li>
                </ol>
            </nav>
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Order History Lists</h2>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="btnExport"
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            Export
                        </button>
                    </div>
                </div>

                <!-- Divider line -->
                <div class="border-t border-gray-200 my-4"></div>

                <!-- Filters section -->
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <!-- Date Filters -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 flex-1">
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <label for="filter-date-from" class="text-sm text-gray-600 whitespace-nowrap w-12 sm:w-auto">From:</label>
                            <input type="date" id="filter-date-from" class="flex-1 sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <label for="filter-date-to" class="text-sm text-gray-600 whitespace-nowrap w-12 sm:w-auto">To:</label>
                            <input type="date" id="filter-date-to" class="flex-1 sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                        </div>
                    </div>
                    <!-- Filter Buttons -->
                    <div class="flex gap-2 sm:justify-end">
                        <button id="apply-filters" type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">Apply</button>
                        <button id="reset-filters" type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">Reset</button>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 sm:mb-0">
                <table id="selection-table" class="min-w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">
                                    Order Number
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">
                                    Date Ordered
                                </span>
                            </th>
                             <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">
                                    Amount
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">
                                    Action
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody">
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">#ORD-2026-001</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">January 18, 2026</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-semibold">₱385.00</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button type="button" class="btn-view-order text-primary py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-secondary" data-order-id="1" data-order-number="#ORD-2026-001" data-date="January 18, 2026" data-total="385.00">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">#ORD-2026-002</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">January 17, 2026</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-semibold">₱215.00</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button type="button" class="btn-view-order text-primary py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-secondary" data-order-id="1" data-order-number="#ORD-2026-001" data-date="January 18, 2026" data-total="385.00">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">#ORD-2026-003</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">January 16, 2026</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-semibold">₱540.00</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button type="button" class="btn-view-order text-primary py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-secondary" data-order-id="1" data-order-number="#ORD-2026-001" data-date="January 18, 2026" data-total="385.00">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">#ORD-2026-004</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">January 15, 2026</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-semibold">₱125.00</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button type="button" class="btn-view-order text-primary py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-secondary" data-order-id="1" data-order-number="#ORD-2026-001" data-date="January 18, 2026" data-total="385.00">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">#ORD-2026-005</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">January 14, 2026</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-semibold">₱ 690.00</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button type="button" class="btn-view-order text-primary py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-secondary" data-order-id="1" data-order-number="#ORD-2026-001" data-date="January 18, 2026" data-total="385.00">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div id="orderDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-3xl mx-auto p-6 border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200">
                <h3 class="text-2xl font-bold text-gray-800" id="orderDetailsTitle">Order Details</h3>
                <button type="button" id="btnCloseOrderDetails" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Order Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Order Number</p>
                    <p class="text-lg font-semibold text-gray-900" id="detailOrderNumber">-</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Date Ordered</p>
                    <p class="text-lg font-semibold text-gray-900" id="detailOrderDate">-</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                    <p class="text-lg font-bold text-primary" id="detailOrderTotal">-</p>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-3">Order Items</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-gray-700 font-semibold">Product</th>
                                <th class="px-4 py-3 text-right text-gray-700 font-semibold">Price</th>
                                <th class="px-4 py-3 text-center text-gray-700 font-semibold">Quantity</th>
                                <th class="px-4 py-3 text-right text-gray-700 font-semibold">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="orderItemsList" class="divide-y divide-gray-200">
                            <!-- Items will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-700">Subtotal:</span>
                    <span class="font-semibold text-gray-900" id="summarySubtotal">₱0.00</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-700">Tax (0%):</span>
                    <span class="font-semibold text-gray-900">₱0.00</span>
                </div>
                <div class="border-t border-gray-300 pt-2 mt-2">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">Total:</span>
                        <span class="text-2xl font-bold text-primary" id="summaryTotal">₱0.00</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 mt-6 justify-end">
                <button type="button" id="btnPrintOrder" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
                <button type="button" id="btnCloseModal" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- External Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a89dedcb22.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    
    <!-- App Scripts -->
    <script>
        // Set base URL for JS modules
        window.BASE_URL = '<?= rtrim(site_url(), '/') ?>/';
        // Sample order data
        const orderData = {
            1: {
                items: [
                    { name: 'Pandesal', price: 5.00, quantity: 10, subtotal: 50.00 },
                    { name: 'Ensaymada', price: 25.00, quantity: 5, subtotal: 125.00 },
                    { name: 'Coffee', price: 30.00, quantity: 3, subtotal: 90.00 },
                    { name: 'Milk Tea', price: 40.00, quantity: 3, subtotal: 120.00 }
                ]
            },
            2: {
                items: [
                    { name: 'Spanish Bread', price: 8.00, quantity: 15, subtotal: 120.00 },
                    { name: 'Iced Coffee', price: 35.00, quantity: 2, subtotal: 70.00 },
                    { name: 'Pandesal', price: 5.00, quantity: 5, subtotal: 25.00 }
                ]
            },
            3: {
                items: [
                    { name: 'Cheese Bread', price: 12.00, quantity: 20, subtotal: 240.00 },
                    { name: 'Ensaymada', price: 25.00, quantity: 8, subtotal: 200.00 },
                    { name: 'Coffee', price: 30.00, quantity: 2, subtotal: 60.00 },
                    { name: 'Milk Tea', price: 40.00, quantity: 1, subtotal: 40.00 }
                ]
            },
            4: {
                items: [
                    { name: 'Pandesal', price: 5.00, quantity: 10, subtotal: 50.00 },
                    { name: 'Monay', price: 6.00, quantity: 5, subtotal: 30.00 },
                    { name: 'Juice', price: 25.00, quantity: 1, subtotal: 25.00 },
                    { name: 'Coffee', price: 30.00, quantity: 1, subtotal: 20.00 }
                ]
            },
            5: {
                items: [
                    { name: 'Ensaymada', price: 25.00, quantity: 10, subtotal: 250.00 },
                    { name: 'Spanish Bread', price: 8.00, quantity: 20, subtotal: 160.00 },
                    { name: 'Milk Tea', price: 40.00, quantity: 4, subtotal: 160.00 },
                    { name: 'Iced Coffee', price: 35.00, quantity: 2, subtotal: 70.00 },
                    { name: 'Pandesal', price: 5.00, quantity: 10, subtotal: 50.00 }
                ]
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable
            const dataTable = new simpleDatatables.DataTable("#selection-table", {
                searchable: true,
                sortable: true,
                perPage: 10,
                perPageSelect: [5, 10, 25, 50],
                labels: {
                    placeholder: "Search orders...",
                    noRows: "No order history found",
                    info: "Showing {start} to {end} of {rows} orders"
                }
            });

            // Order Details Modal elements
            const orderDetailsModal = document.getElementById('orderDetailsModal');
            const btnCloseOrderDetails = document.getElementById('btnCloseOrderDetails');
            const btnCloseModal = document.getElementById('btnCloseModal');
            const btnPrintOrder = document.getElementById('btnPrintOrder');

            // Function to open order details modal
            function openOrderDetails(orderId, orderNumber, orderDate, orderTotal) {
                const order = orderData[orderId];
                
                // Set order info
                document.getElementById('orderDetailsTitle').textContent = 'Order Details - ' + orderNumber;
                document.getElementById('detailOrderNumber').textContent = orderNumber;
                document.getElementById('detailOrderDate').textContent = orderDate;
                document.getElementById('detailOrderTotal').textContent = '₱' + parseFloat(orderTotal).toFixed(2);

                // Render order items
                const orderItemsList = document.getElementById('orderItemsList');
                orderItemsList.innerHTML = '';
                
                if (order && order.items) {
                    order.items.forEach(item => {
                        const row = `
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">${item.name}</td>
                                <td class="px-4 py-3 text-right text-gray-700">₱${item.price.toFixed(2)}</td>
                                <td class="px-4 py-3 text-center text-gray-700">${item.quantity}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900">₱${item.subtotal.toFixed(2)}</td>
                            </tr>
                        `;
                        orderItemsList.innerHTML += row;
                    });
                }

                // Set summary
                document.getElementById('summarySubtotal').textContent = '₱' + parseFloat(orderTotal).toFixed(2);
                document.getElementById('summaryTotal').textContent = '₱' + parseFloat(orderTotal).toFixed(2);

                // Show modal
                orderDetailsModal.classList.remove('hidden');
            }

            // View order button click handler
            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-view-order')) {
                    const btn = e.target.closest('.btn-view-order');
                    const orderId = btn.getAttribute('data-order-id');
                    const orderNumber = btn.getAttribute('data-order-number');
                    const orderDate = btn.getAttribute('data-date');
                    const orderTotal = btn.getAttribute('data-total');
                    
                    openOrderDetails(orderId, orderNumber, orderDate, orderTotal);
                }
            });

            // Close modal handlers
            btnCloseOrderDetails.addEventListener('click', function() {
                orderDetailsModal.classList.add('hidden');
            });

            btnCloseModal.addEventListener('click', function() {
                orderDetailsModal.classList.add('hidden');
            });

            // Close modal when clicking outside
            orderDetailsModal.addEventListener('click', function(e) {
                if (e.target === orderDetailsModal) {
                    orderDetailsModal.classList.add('hidden');
                }
            });

            // Print order
            btnPrintOrder.addEventListener('click', function() {
                window.print();
            });

            // Date filter functionality
            const applyFilters = document.getElementById('apply-filters');
            const resetFilters = document.getElementById('reset-filters');
            const filterDateFrom = document.getElementById('filter-date-from');
            const filterDateTo = document.getElementById('filter-date-to');

            applyFilters.addEventListener('click', function() {
                // TODO: Implement filter logic with backend
                alert('Filter functionality will be implemented with backend integration');
            });

            resetFilters.addEventListener('click', function() {
                filterDateFrom.value = '';
                filterDateTo.value = '';
                // TODO: Reset table data
            });
        });    </script>