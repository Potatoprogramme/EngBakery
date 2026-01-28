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
                    <li class="text-gray-700">Daily Sales Remittance</li>
                </ol>
            </nav>

            <!-- Header Card -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">
                        Daily Sales Remittance
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <a href="<?= base_url('Sales/History') ?>" 
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <i class="fas fa-history mr-2"></i>Sales History
                        </a>
                        <button type="button" id="btnPrintRemittance"
                            class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                    </div>
                </div>
            </div>

            <!-- Remittance Slip Card -->
            <div id="remittanceSlip" class="bg-white rounded-lg shadow-md p-6 mb-6">
                <!-- Remittance Header -->
                <div class="border-b-2 border-gray-200 pb-4 mb-4">
                    <h3 class="text-lg font-bold text-center text-gray-800 mb-4">CASHIER'S REMITTANCE SLIP</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-600 w-20">NAME:</label>
                                <input type="text" id="cashierName" 
                                    class="flex-1 border-b border-gray-300 px-2 py-1 text-sm font-semibold text-gray-900 focus:outline-none focus:border-primary"
                                    placeholder="Enter cashier name">
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-600 w-20">DATE:</label>
                                <span id="remittanceDate" class="flex-1 px-2 py-1 text-sm font-semibold text-gray-900 bg-gray-50 rounded"></span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-600 w-20">OUTLET:</label>
                                <input type="text" id="outletName" 
                                    class="flex-1 border-b border-gray-300 px-2 py-1 text-sm font-semibold text-gray-900 focus:outline-none focus:border-primary"
                                    placeholder="Enter outlet name" value="E n' G Bakery">
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-600 w-20">SHIFT:</label>
                                <span id="shiftTime" class="flex-1 px-2 py-1 text-sm font-semibold text-gray-900 bg-gray-50 rounded">--:-- - --:--</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Cash Section - Full Width -->
                    <div>
                        <h4 class="text-md font-bold text-gray-700 mb-3 border-b border-gray-200 pb-2">
                            <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>CASH SALES / CASH ON HAND
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Total Sales in System -->
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">TOTAL SALES IN SYSTEM:</span>
                                        <p class="text-2xl font-bold text-blue-600" id="systemTotalSales">₱0.00</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Cash on Hand -->
                            <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                                <label class="text-sm font-medium text-gray-600 block mb-2">CASH ON HAND:</label>
                                <input type="number" id="cashOnHand" min="0" step="0.01" placeholder="0.00"
                                    class="w-full text-right border border-green-300 rounded px-3 py-2 text-2xl font-bold text-green-600 bg-white focus:ring-1 focus:ring-green-600 focus:border-green-600">
                            </div>

                            <!-- Variance from Cash Count -->
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600">CASH COUNT VARIANCE:</span>
                                </div>
                                <div class="mt-1">
                                    <span class="text-xl font-bold" id="cashCountVariance">₱0.00</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    (Cash Enclosed - Cash on Hand)
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Summary Section -->
                    <div>
                        <h4 class="text-md font-bold text-gray-700 mb-3 border-b border-gray-200 pb-2">
                            <i class="fas fa-chart-pie mr-2 text-blue-600"></i>SALES SUMMARY
                        </h4>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Left: Payment Methods -->
                            <div>
                                <!-- Payment Methods -->
                                <div class="space-y-3 mb-4">
                                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-mobile-alt text-blue-600"></i>
                                            <span class="text-sm font-medium text-gray-700">GCASH:</span>
                                        </div>
                                        <input type="number" id="gcashTotal" min="0" step="0.01" placeholder="0.00"
                                            class="w-32 text-right border border-blue-300 rounded px-3 py-2 text-lg font-bold text-blue-600 bg-white focus:ring-1 focus:ring-blue-600 focus:border-blue-600">
                                    </div>
                                    
                                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between p-3 bg-gray-50 rounded-lg gap-3 md:gap-2">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-hand-holding-usd text-gray-600"></i>
                                            <span class="text-sm font-medium text-gray-700">CASH OUT:</span>
                                        </div>
                                        <div class="flex flex-col md:flex-row items-stretch md:items-center gap-2 w-full md:w-auto">
                                            <input type="number" id="cashOutAmount" min="0" step="0.01" placeholder="0.00"
                                                class="w-full md:w-24 text-right border border-gray-200 rounded px-2 py-2 focus:ring-1 focus:ring-primary focus:border-primary">
                                            <input type="text" id="cashOutReason" placeholder="Reason (optional)"
                                                class="w-full md:w-32 border border-gray-200 rounded px-2 py-2 focus:ring-1 focus:ring-primary focus:border-primary">
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Sales & Variance -->
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-4 bg-primary/10 rounded-lg">
                                        <span class="text-md font-bold text-gray-800">TOTAL SALES:</span>
                                        <span class="text-2xl font-bold text-primary" id="totalSales">₱0.00</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between p-4 bg-gray-100 rounded-lg" id="varianceContainer">
                                        <span class="text-md font-bold text-gray-800">OVERAGE / SHORTAGE:</span>
                                        <span class="text-xl font-bold" id="variance">₱0.00</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Sales Categories & Statistics -->
                            <div>
                                <!-- Sales by Category -->
                                <div class="space-y-3 mb-4">
                                    <h5 class="text-sm font-semibold text-gray-600 border-b border-gray-100 pb-1">SALES BY CATEGORY</h5>
                                    
                                    <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-bread-slice text-amber-600"></i>
                                            <span class="text-sm font-medium text-gray-700">BAKERY:</span>
                                        </div>
                                        <span class="text-lg font-bold text-amber-600" id="bakerySales">₱0.00</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-mug-hot text-orange-600"></i>
                                            <span class="text-sm font-medium text-gray-700">COFFEE/DRINKS:</span>
                                        </div>
                                        <span class="text-lg font-bold text-orange-600" id="coffeeSales">₱0.00</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-shopping-basket text-green-600"></i>
                                            <span class="text-sm font-medium text-gray-700">GROCERY:</span>
                                        </div>
                                        <span class="text-lg font-bold text-green-600" id="grocerySales">₱0.00</span>
                                    </div>
                                </div>

                                <!-- Order Statistics -->
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <h5 class="text-sm font-semibold text-gray-600 mb-3">TODAY'S STATISTICS</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="text-center">
                                            <div class="text-3xl font-bold text-primary" id="totalOrders">0</div>
                                            <div class="text-xs text-gray-500">Total Orders</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-3xl font-bold text-amber-600" id="totalItemsSold">0</div>
                                            <div class="text-xs text-gray-500">Items Sold</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" id="btnResetForm"
                        class="inline-flex items-center rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </button>
                    <button type="button" id="btnSaveRemittance"
                        class="inline-flex items-center rounded-lg bg-primary px-6 py-2.5 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                        <i class="fas fa-save mr-2"></i>Save Remittance
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        window.BASE_URL = '<?= rtrim(site_url(), '/') ?>/';

        $(document).ready(function() {
            initializeRemittance();
            loadTodaysSalesData();
            bindBillInputEvents();
            bindGCashInputEvent();
            bindCashOnHandInputEvent();
        });

        function initializeRemittance() {
            // Set today's date
            const today = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            $('#remittanceDate').text(today.toLocaleDateString('en-US', options));
        }

        function loadTodaysSalesData() {
            $.ajax({
                url: BASE_URL + 'Sales/GetTodaysSummary',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        
                        // Update shift time
                        if (data.shift_start && data.shift_end) {
                            $('#shiftTime').text(formatTime(data.shift_start) + ' - ' + formatTime(data.shift_end));
                        }
                        
                        // Update sales by category
                        $('#bakerySales').text(formatCurrency(data.bakery_sales || 0));
                        $('#coffeeSales').text(formatCurrency(data.coffee_sales || 0));
                        $('#grocerySales').text(formatCurrency(data.grocery_sales || 0));
                        
                        // Update total sales in system
                        $('#systemTotalSales').text(formatCurrency(data.total_sales || 0));
                        
                        // Update payment methods - set GCash input value
                        $('#gcashTotal').val(data.gcash_total || 0);
                        
                        // Update total sales
                        $('#totalSales').text(formatCurrency(data.total_sales || 0));
                        
                        // Update statistics
                        $('#totalOrders').text(data.total_orders || 0);
                        $('#totalItemsSold').text(data.total_items_sold || 0);
                        
                        // Calculate variance
                        calculateVariance();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading sales data:', error);
                }
            });
        }

        function bindBillInputEvents() {
            $('#cashOutAmount').on('input', function() {
                calculateVariance();
            });
        }

        function bindGCashInputEvent() {
            $('#gcashTotal').on('input', function() {
                calculateVariance();
            });
        }

        function bindCashOnHandInputEvent() {
            $('#cashOnHand').on('input', function() {
                calculateCashCountVariance();
            });
        }

        function calculateTotalCash() {
            // Since we removed bill counting, cash enclosed equals cash on hand
            calculateCashCountVariance();
            calculateVariance();
        }

        function calculateCashCountVariance() {
            // Since we removed bill breakdown, cash enclosed = cash on hand for variance calculation
            const cashOnHand = parseFloat($('#cashOnHand').val()) || 0;
            // For now, assume cash enclosed equals cash on hand (variance = 0)
            const variance = 0;
            
            const varianceEl = $('#cashCountVariance');
            const container = varianceEl.parent();
            
            container.removeClass('bg-red-50 bg-green-50').addClass('bg-gray-50');
            varianceEl.removeClass('text-red-600 text-green-600').addClass('text-gray-800');
            varianceEl.text('₱0.00');
        }

        function calculateVariance() {
            const cashOnHand = parseFloat($('#cashOnHand').val()) || 0;
            const gcash = parseFloat($('#gcashTotal').val()) || 0;
            const cashOut = parseFloat($('#cashOutAmount').val()) || 0;
            const totalSales = parseCurrency($('#totalSales').text());
            
            const totalRemitted = cashOnHand + gcash - cashOut;
            const variance = totalRemitted - totalSales;
            
            $('#variance').text(formatCurrency(Math.abs(variance)));
            
            // Update variance styling
            const container = $('#varianceContainer');
            const varianceEl = $('#variance');
            
            if (variance > 0) {
                container.removeClass('bg-red-100').addClass('bg-green-100');
                varianceEl.removeClass('text-red-600').addClass('text-green-600');
                varianceEl.text('+ ' + formatCurrency(variance) + ' (Over)');
            } else if (variance < 0) {
                container.removeClass('bg-green-100').addClass('bg-red-100');
                varianceEl.removeClass('text-green-600').addClass('text-red-600');
                varianceEl.text('- ' + formatCurrency(Math.abs(variance)) + ' (Short)');
            } else {
                container.removeClass('bg-red-100 bg-green-100').addClass('bg-gray-100');
                varianceEl.removeClass('text-red-600 text-green-600').addClass('text-primary');
                varianceEl.text('₱0.00 (Balanced)');
            }
        }

        function formatCurrency(amount) {
            return '₱' + parseFloat(amount).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function parseCurrency(str) {
            return parseFloat(str.replace(/[₱,]/g, '')) || 0;
        }

        function formatTime(timeStr) {
            if (!timeStr) return '--:--';
            const [hours, minutes] = timeStr.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes} ${ampm}`;
        }

        // Reset form
        $('#btnResetForm').on('click', function() {
            $('#cashOutAmount').val(0);
            $('#cashOutReason').val('');
            $('#cashierName').val('');
            $('#gcashTotal').val(0);
            $('#cashOnHand').val(0);
            calculateTotalCash();
        });

        // Print functionality
        $('#btnPrintRemittance').on('click', function() {
            const content = $('#remittanceSlip').clone();
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Cashier's Remittance Slip</title>
                    <script src="https://cdn.tailwindcss.com"><\/script>
                    <style>
                        @media print {
                            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                            .no-print { display: none !important; }
                        }
                        body { font-family: Arial, sans-serif; padding: 20px; }
                    </style>
                </head>
                <body>
                    ${content.html()}
                    <script>
                        setTimeout(() => { window.print(); window.close(); }, 500);
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        });

        // Save remittance
        $('#btnSaveRemittance').on('click', function() {
            const remittanceData = {
                cashier_name: $('#cashierName').val(),
                outlet_name: $('#outletName').val(),
                date: new Date().toISOString().split('T')[0],
                cash_on_hand: parseFloat($('#cashOnHand').val()) || 0,
                gcash_total: parseFloat($('#gcashTotal').val()) || 0,
                cash_out_amount: parseFloat($('#cashOutAmount').val()) || 0,
                cash_out_reason: $('#cashOutReason').val(),
                bakery_sales: parseCurrency($('#bakerySales').text()),
                coffee_sales: parseCurrency($('#coffeeSales').text()),
                grocery_sales: parseCurrency($('#grocerySales').text()),
                total_sales: parseCurrency($('#totalSales').text()),
                variance: parseCurrency($('#variance').text())
            };

            $.ajax({
                url: BASE_URL + 'Sales/SaveRemittance',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(remittanceData),
                success: function(response) {
                    if (response.success) {
                        showToast('success', 'Remittance saved successfully!');
                    } else {
                        showToast('danger', response.message || 'Failed to save remittance');
                    }
                },
                error: function(xhr, status, error) {
                    showToast('danger', 'Error saving remittance: ' + error);
                }
            });
        });
    </script>
