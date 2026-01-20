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
                    <li class="text-gray-700">Order</li>
                </ol>
            </nav>
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Product Lists</h2>
                    <div class="flex flex-wrap gap-2">
                        <a href="<?= base_url('Order/OrderHistory') ?>" id="btnExport" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            Order History
                        </a>
                    </div>
                </div>
            </div>

            <!-- Floating Cart Button -->
            <div id="floatingCartContainer" class="fixed bottom-6 right-6 z-40">
                <!-- Mini Cart Panel (Hidden by default) -->
                <div id="miniCartPanel" class="hidden absolute bottom-20 right-0 w-80 sm:w-96 bg-white border border-gray-200 shadow-2xl max-h-[70vh] overflow-hidden">
                    <!-- Mini Cart Header -->
                    <div class="bg-primary text-white px-4 py-3 flex justify-between items-center">
                        <h4 class="font-semibold">Your Order</h4>
                        <button type="button" id="btnCloseMiniCart" class="text-white hover:text-gray-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <!-- Mini Cart Items -->
                    <div id="miniCartItems" class="max-h-64 overflow-y-auto p-3">
                        <!-- Items will be dynamically inserted here -->
                    </div>
                    
                    <!-- Mini Cart Footer -->
                    <div class="border-t border-gray-200 p-4 bg-gray-50">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-600 font-medium">Total:</span>
                            <span class="text-xl font-bold text-primary" id="miniCartTotal">P0.00</span>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" id="btnClearCart" class="flex-1 px-3 py-2 text-sm font-medium text-gray-600 bg-gray-200 hover:bg-gray-300 transition-colors">
                                Clear
                            </button>
                            <button type="button" id="btnMiniCartCheckout" class="flex-[2] px-4 py-2 text-sm font-bold text-white bg-primary hover:bg-secondary transition-colors">
                                Checkout
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Floating Cart Button -->
                <button type="button" id="floatingCartBtn" class="relative w-16 h-16 bg-primary hover:bg-secondary text-white rounded-full shadow-lg hover:shadow-xl transition-all flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span id="cartBadge" class="hidden absolute -top-1 -right-1 w-6 h-6 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">0</span>
                </button>
            </div>

            <!-- Tabs -->
            <div class="flex gap-2 mb-4">
                <button data-tab="breads" class="tab-btn flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-all border-2 border-primary text-white bg-primary shadow-md">
                    Breads
                </button>
                <button data-tab="drinks" class="tab-btn flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-all border-2 border-gray-300 text-gray-700 bg-gray-100 hover:bg-gray-200 hover:border-gray-400">
                    Drinks
                </button>
            </div>

            <!-- Tab Content: Breads -->
            <div id="breads-content" class="tab-content p-4 bg-white rounded-lg shadow-md mb-20 sm:mb-0">
                <div id="breads-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Products will be loaded dynamically -->
                    <div class="col-span-full text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                        <p>Loading products...</p>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Drinks -->
            <div id="drinks-content" class="tab-content hidden p-4 bg-white rounded-lg shadow-md mb-20 sm:mb-0">
                <div id="drinks-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Products will be loaded dynamically -->
                    <div class="col-span-full text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                        <p>Loading products...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Selection Modal -->
    <div id="productModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-md mx-auto p-6 border shadow-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800" id="productModalTitle">Product Name</h3>
                <button type="button" id="btnCloseProductModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="productOrderForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                    <div class="text-2xl font-bold text-primary" id="productPrice">P0.00</div>
                </div>
                <div class="mb-4">
                    <label for="productQuantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <button type="button" id="btnQtyDecrease" class="flex-shrink-0 w-10 h-10 flex items-center justify-center border border-gray-300 bg-gray-100 text-gray-700 text-lg font-semibold hover:bg-gray-200">
                            -
                        </button>
                        <input type="number" id="productQuantity" name="quantity" min="1" value="1" class="w-full px-4 py-2 border border-gray-300 text-center focus:outline-none focus:ring-2 focus:ring-primary" required>
                        <button type="button" id="btnQtyIncrease" class="flex-shrink-0 w-10 h-10 flex items-center justify-center border border-gray-300 bg-gray-100 text-gray-700 text-lg font-semibold hover:bg-gray-200">
                            +
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                    <div class="text-xl font-bold text-gray-800" id="productTotal">P0.00</div>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelProduct" class="px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="flex-1 max-w-[75%] px-4 py-3 text-lg font-bold text-white bg-primary hover:bg-secondary transition-all">Add to Order</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Customer Orders Modal -->
    <div id="customerOrdersModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-2xl mx-auto p-6 border shadow-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Customer Orders</h3>
                <button type="button" id="btnCloseOrdersModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="ordersListContainer">
                <!-- Orders will be dynamically inserted here -->
            </div>

            <div class="mt-6 pt-4 border-t border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-semibold text-gray-800">Grand Total:</span>
                    <span class="text-2xl font-bold text-primary" id="grandTotal">P0.00</span>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnClearOrders" class="px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200">Clear All</button>
                    <button type="submit" id="btnCheckout" class="flex-1 max-w-[75%] px-4 py-3 text-lg font-bold text-white bg-primary hover:bg-secondary transition-all">Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Order Quantity Modal -->
    <div id="editOrderModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-md mx-auto p-6 border shadow-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800" id="editOrderModalTitle">Edit Order</h3>
                <button type="button" id="btnCloseEditOrderModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editOrderForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                    <div class="text-2xl font-bold text-primary" id="editOrderPrice">P0.00</div>
                </div>
                <div class="mb-4">
                    <label for="editOrderQuantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <button type="button" id="btnEditQtyDecrease" class="flex-shrink-0 w-10 h-10 flex items-center justify-center border border-gray-300 bg-gray-100 text-gray-700 text-lg font-semibold hover:bg-gray-200">
                            -
                        </button>
                        <input type="number" id="editOrderQuantity" name="quantity" min="1" value="1" class="w-full px-4 py-2 border border-gray-300 text-center focus:outline-none focus:ring-2 focus:ring-primary" required>
                        <button type="button" id="btnEditQtyIncrease" class="flex-shrink-0 w-10 h-10 flex items-center justify-center border border-gray-300 bg-gray-100 text-gray-700 text-lg font-semibold hover:bg-gray-200">
                            +
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                    <div class="text-xl font-bold text-gray-800" id="editOrderTotal">P0.00</div>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelEditOrder" class="px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="flex-1 max-w-[75%] px-4 py-3 text-lg font-bold text-white bg-primary hover:bg-secondary transition-all">Update Order</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Checkout Modal with Step Progress -->
    <div id="checkoutModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-2 sm:p-4">
        <div class="relative w-full max-w-2xl mx-auto border shadow-xl bg-white max-h-[95vh] overflow-y-auto">
            <!-- Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 p-5 sm:p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800">Checkout</h3>
                    <button type="button" id="btnCloseCheckout" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Redesigned Step Progress Bar -->
                <div class="relative">
                    <!-- Progress Background Line -->
                    <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200"></div>
                    <!-- Progress Active Line -->
                    <div id="progressLine" class="absolute top-5 left-0 h-1 bg-primary transition-all duration-300" style="width: 0%;"></div>
                    
                    <!-- Steps Container -->
                    <div class="relative flex justify-between">
                        <!-- Step 1 -->
                        <div class="flex flex-col items-center">
                            <div id="step1" class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full border-2 border-primary bg-primary text-white font-bold text-base sm:text-lg shadow-md z-10">
                                1
                            </div>
                            <span id="step1Label" class="text-xs sm:text-sm mt-2 text-primary font-semibold">Cart</span>
                        </div>
                        <!-- Step 2 -->
                        <div class="flex flex-col items-center">
                            <div id="step2" class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full border-2 border-gray-300 bg-white text-gray-400 font-bold text-base sm:text-lg z-10">
                                2
                            </div>
                            <span id="step2Label" class="text-xs sm:text-sm mt-2 text-gray-400 font-medium">Payment</span>
                        </div>
                        <!-- Step 3 -->
                        <div class="flex flex-col items-center">
                            <div id="step3" class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full border-2 border-gray-300 bg-white text-gray-400 font-bold text-base sm:text-lg z-10">
                                3
                            </div>
                            <span id="step3Label" class="text-xs sm:text-sm mt-2 text-gray-400 font-medium">Amount</span>
                        </div>
                        <!-- Step 4 -->
                        <div class="flex flex-col items-center">
                            <div id="step4" class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full border-2 border-gray-300 bg-white text-gray-400 font-bold text-base sm:text-lg z-10">
                                4
                            </div>
                            <span id="step4Label" class="text-xs sm:text-sm mt-2 text-gray-400 font-medium">Done</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step Content Container -->
            <div class="p-5 sm:p-6">
                <!-- Step 1: Cart Review -->
                <div id="checkoutStep1" class="checkout-step">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h4>
                    <div id="checkoutCartItems" class="space-y-3 max-h-60 overflow-y-auto mb-4">
                        <!-- Cart items will be inserted here -->
                    </div>
                    <div class="border-t-2 pt-4">
                        <div class="flex justify-between items-center text-xl font-bold">
                            <span>Total:</span>
                            <span class="text-primary" id="checkoutTotal">P0.00</span>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="button" id="btnCancelCheckout" class="flex-1 px-5 py-4 text-base font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">Cancel</button>
                        <button type="button" id="btnToStep2" class="flex-1 px-5 py-4 text-base font-bold text-white bg-primary hover:bg-secondary transition-colors">Next</button>
                    </div>
                </div>

                <!-- Step 2: Payment Method -->
                <div id="checkoutStep2" class="checkout-step hidden">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Select Payment Method</h4>
                    <div class="mb-5">
                        <label class="block text-base font-medium text-gray-700 mb-2">Order Type</label>
                        <select id="checkoutOrderType" class="w-full px-4 py-4 border border-gray-300 text-base focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="walk-in">Walk-in</option>
                            <option value="foodpanda">FoodPanda</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="block text-base font-medium text-gray-700 mb-2">Payment Method</label>
                        <select id="checkoutPaymentMethod" class="w-full px-4 py-4 border border-gray-300 text-base focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="cash">Cash</option>
                            <option value="gcash">GCash</option>
                            <option value="maya">Maya</option>
                            <option value="credit card">Credit Card</option>
                            <option value="debit card">Debit Card</option>
                        </select>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="button" id="btnBackToStep1" class="flex-1 px-5 py-4 text-base font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">Back</button>
                        <button type="button" id="btnToStep3" class="flex-1 px-5 py-4 text-base font-bold text-white bg-primary hover:bg-secondary transition-colors">Next</button>
                    </div>
                </div>

                <!-- Step 3: Amount Tendered -->
                <div id="checkoutStep3" class="checkout-step hidden">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Enter Amount Tendered</h4>
                    <div class="mb-4 p-4 bg-primary/5 border border-primary/20">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Total Amount:</span>
                            <span class="text-2xl font-bold text-primary" id="step3Total">P0.00</span>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="block text-base font-medium text-gray-700 mb-2">Amount Tendered</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-xl">P</span>
                            <input type="number" id="amountTendered" class="w-full pl-10 pr-4 py-4 border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-2xl font-semibold\" placeholder="0.00" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-3 mb-5">
                        <button type="button" class="quick-amount px-3 py-3 text-sm font-semibold border-2 border-gray-300 bg-gray-50 hover:bg-primary hover:text-white hover:border-primary transition-colors" data-type="exact">Exact</button>
                        <button type="button" class="quick-amount px-3 py-3 text-sm font-semibold border-2 border-gray-300 bg-gray-50 hover:bg-primary hover:text-white hover:border-primary transition-colors" data-amount="50">P50</button>
                        <button type="button" class="quick-amount px-3 py-3 text-sm font-semibold border-2 border-gray-300 bg-gray-50 hover:bg-primary hover:text-white hover:border-primary transition-colors" data-amount="100">P100</button>
                        <button type="button" class="quick-amount px-3 py-3 text-sm font-semibold border-2 border-gray-300 bg-gray-50 hover:bg-primary hover:text-white hover:border-primary transition-colors" data-amount="500">P500</button>
                    </div>
                    <div class="p-4 bg-gray-100 border-2 border-gray-200 mb-5">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 font-medium text-lg">Change:</span>
                            <span class="text-2xl font-bold" id="changeAmount">P0.00</span>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="button" id="btnBackToStep2" class="flex-1 px-5 py-4 text-base font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">Back</button>
                        <button type="button" id="btnCompleteCheckout" class="flex-1 px-5 py-4 text-base font-bold text-white bg-green-600 hover:bg-green-700 transition-colors">Complete</button>
                    </div>
                </div>

                <!-- Step 4: Success -->
                <div id="checkoutStep4" class="checkout-step hidden">
                    <div class="text-center py-8">
                        <div class="w-24 h-24 mx-auto mb-6 flex items-center justify-center bg-green-100 text-green-600 rounded-full border-4 border-green-500">
                            <i class="fas fa-check text-5xl"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-800 mb-2">Order Complete!</h4>
                        <p class="text-gray-500 mb-1">Order Number:</p>
                        <p class="text-3xl font-bold text-primary mb-6" id="orderNumber">ORD-000000-000</p>
                        <div class="p-5 bg-gray-50 border-2 border-gray-200 mb-6 text-left">
                            <div class="flex justify-between text-base mb-2">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-bold text-gray-800" id="finalTotal">P0.00</span>
                            </div>
                            <div class="flex justify-between text-base mb-2">
                                <span class="text-gray-600">Tendered:</span>
                                <span class="text-gray-800" id="finalTendered">P0.00</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-300">
                                <span class="text-gray-700">Change:</span>
                                <span class="text-green-600" id="finalChange">P0.00</span>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" id="btnPrintInvoice" class="flex-1 px-5 py-4 text-base font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                                <i class="fas fa-print mr-2"></i> Print
                            </button>
                            <button type="button" id="btnNewOrder" class="flex-1 px-5 py-4 text-base font-bold text-white bg-primary hover:bg-secondary transition-colors">New Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exit Confirmation Modal -->
    <div id="exitConfirmModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 overflow-y-auto h-full w-full z-[60] flex items-center justify-center p-4">
        <div class="relative w-full max-w-md mx-auto p-8 border shadow-xl bg-white">
            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-5 flex items-center justify-center bg-yellow-100 text-yellow-600 rounded-full">
                    <i class="fas fa-exclamation-triangle text-4xl"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-800 mb-3">Exit Checkout?</h4>
                <p class="text-gray-600 mb-6">Are you sure you want to exit? Your checkout progress will be lost.</p>
                <div class="flex gap-3">
                    <button type="button" id="btnCancelExit" class="flex-1 px-5 py-4 text-base font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">Stay</button>
                    <button type="button" id="btnConfirmExit" class="flex-1 px-5 py-4 text-base font-bold text-white bg-red-600 hover:bg-red-700 transition-colors">Exit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Material Modal -->
    <div id="addMaterialModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md max-h-42 mx-auto p-4 sm:p-4 border shadow-lg rounded-md bg-white"
            style="max-width: 42rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary" id="modalTitle">Add Order</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addMaterialForm">
                <input type="hidden" id="edit_material_id" value="">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Material Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="material_name" id="material_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="e.g., Flour - All Purpose" required>
                    <p id="material_name_error" class="text-red-500 text-xs mt-1 hidden">This material name already
                        exists.</p>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Category <span
                            class="text-red-500">*</span></label>
                    <div class="flex gap-2 items-center">
                        <select name="category_id" id="category_id"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                            <option value="">Select</option>
                        </select>
                        <button type="button" id="btnManageCategories"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary"
                            title="Manage Categories">
                            Manage
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-3 mb-3 sm:grid-cols-2">
                    <div>
                        <div class="flex">
                            <label for="material_quantity" class="flex-1 block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                            <label for="unit" class="w-32 block text-sm font-medium text-gray-700 mb-1">Unit of Measure</label>
                        </div>
                        <div class="flex">
                            <input type="number" name="material_quantity" id="material_quantity" 
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary border-r-0" 
                                placeholder="25000" min="0.01" step="0.01" required>
                            <select name="unit" id="unit"
                                class="w-32 px-3 py-2 border border-gray-300 bg-gray-50 text-gray-700 rounded-r-md focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                                <option value="grams">grams</option>
                                <option value="ml">ml</option>
                                <option value="pcs">pcs</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="converted_qty_wrapper">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Converted Quantity</label>
                        <div class="flex">
                            <input type="text" id="converted_quantity" readonly
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-gray-600 focus:outline-none border-r-0" 
                                value="0">
                            <span id="converted_unit_label" 
                                class="inline-flex items-center justify-center w-20 px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 rounded-r-md font-medium text-sm">
                                kg
                            </span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-3 mb-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Cost <span
                                class="text-red-500">*</span></label>
                        <div class="relative flex items-center">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <input type="number" name="total_cost" id="total_cost"
                                class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="1350.00" step="0.01" min="0.01" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cost per Unit</label>
                        
                        <!-- Base Unit Cost -->
                        <div class="flex mb-2">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="text" id="cost_per_unit" readonly
                                    class="block w-full pl-7 pr-3 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-gray-600 focus:outline-none border-r-0" 
                                    value="0.000">
                            </div>
                            <span id="cost_unit_label" 
                                class="inline-flex items-center justify-center w-24 px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 rounded-r-md font-medium text-xs sm:text-sm whitespace-nowrap">
                                per unit
                            </span>
                        </div>

                        <!-- Converted Unit Cost -->
                        <div id="converted_cost_wrapper" class="hidden flex">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="text" id="converted_cost" readonly
                                    class="block w-full pl-7 pr-3 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-gray-600 focus:outline-none border-r-0" 
                                    value="0.00">
                            </div>
                            <span id="converted_cost_unit_label" 
                                class="inline-flex items-center justify-center w-24 px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 rounded-r-md font-medium text-xs sm:text-sm whitespace-nowrap">
                                per kg
                            </span>
                        </div>
                        <!-- Converted cost display (per kg/liter) -->
                        <div id="converted_cost_display" class="text-xs text-gray-500 mt-1 hidden"></div>
                    </div>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelAdd"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                    <button type="submit" id="btnSaveMaterial"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Save</button>
                </div>
            </form>
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
        // Cart system
        let orderCart = [];
        let productsData = []; // Store all products from database
        
        // Product modal state (global for scope access)
        let currentProductPrice = 0;
        let currentProductId = null;

        // Attach click handlers to product cards (called after loading)
        function attachProductCardHandlers() {
            document.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const productName = this.getAttribute('data-product-name');
                    const price = parseFloat(this.getAttribute('data-product-price'));
                    
                    currentProductId = productId;
                    currentProductPrice = price;
                    document.getElementById('productModalTitle').textContent = productName;
                    document.getElementById('productPrice').textContent = 'P' + price.toFixed(2);
                    document.getElementById('productQuantity').value = 1;
                    document.getElementById('productTotal').textContent = 'P' + price.toFixed(2);
                    
                    document.getElementById('productModal').classList.remove('hidden');
                });
            });
        }

        // Load products from database
        async function loadProducts() {
            try {
                const response = await fetch(BASE_URL + 'Order/GetProducts');
                const result = await response.json();
                
                if (result.success) {
                    productsData = result.data;
                    renderProducts();
                } else {
                    showProductError('Failed to load products');
                }
            } catch (error) {
                console.error('Error loading products:', error);
                showProductError('Error connecting to server');
            }
        }

        // Render products to their respective grids
        function renderProducts() {
            const breadsGrid = document.getElementById('breads-grid');
            const drinksGrid = document.getElementById('drinks-grid');
            
            const breads = productsData.filter(p => p.category === 'bread');
            const drinks = productsData.filter(p => p.category === 'drinks');
            
            breadsGrid.innerHTML = breads.length > 0 ? breads.map(p => createProductCard(p)).join('') : noProductsHtml('breads');
            drinksGrid.innerHTML = drinks.length > 0 ? drinks.map(p => createProductCard(p)).join('') : noProductsHtml('drinks');
            
            // Re-attach click handlers to new product cards
            attachProductCardHandlers();
        }

        function createProductCard(product) {
            const price = parseFloat(product.price) || 0;
            return `
                <button type="button" class="product-card bg-white block p-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-primary transition-all text-left" 
                    data-product-id="${product.product_id}"
                    data-product-name="${product.product_name}" 
                    data-product-price="${price.toFixed(2)}">
                    <h5 class="text-lg/5 font-semibold text-gray-800">${product.product_name}</h5>
                    <p class="text-sm text-gray-600 line-clamp-2">${product.product_description || 'No description'}</p>
                    <p class="mt-2 py-2 text-center text-2xl font-bold text-primary rounded bg-gray-100 border border-gray-300">P${price.toFixed(2)}</p>
                </button>
            `;
        }

        function noProductsHtml(category) {
            return `
                <div class="col-span-full text-center py-8 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-2"></i>
                    <p>No ${category} products available</p>
                    <p class="text-sm">Add products in the Products section</p>
                </div>
            `;
        }

        function showProductError(message) {
            const errorHtml = `
                <div class="col-span-full text-center py-8 text-red-500">
                    <i class="fas fa-exclamation-triangle text-4xl mb-2"></i>
                    <p>${message}</p>
                    <button onclick="loadProducts()" class="mt-2 px-4 py-2 bg-primary text-white rounded hover:bg-secondary">Retry</button>
                </div>
            `;
            document.getElementById('breads-grid').innerHTML = errorHtml;
            document.getElementById('drinks-grid').innerHTML = errorHtml;
        }

        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Load products on page load
            loadProducts();

            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Remove active state from all buttons
                    tabButtons.forEach(btn => {
                        btn.classList.remove('text-white', 'bg-primary', 'shadow-md', 'border-primary');
                        btn.classList.add('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200', 'border-gray-300', 'hover:border-gray-400');
                    });

                    // Add active state to clicked button
                    this.classList.remove('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200', 'border-gray-300', 'hover:border-gray-400');
                    this.classList.add('text-white', 'bg-primary', 'shadow-md', 'border-primary');

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Show the selected tab content
                    document.getElementById(targetTab + '-content').classList.remove('hidden');
                });
            });

            // Product card click handlers - moved to function for dynamic loading
            const productModal = document.getElementById('productModal');
            const productModalTitle = document.getElementById('productModalTitle');
            const productPrice = document.getElementById('productPrice');
            const productQuantity = document.getElementById('productQuantity');
            const productTotal = document.getElementById('productTotal');
            const btnQtyDecrease = document.getElementById('btnQtyDecrease');
            const btnQtyIncrease = document.getElementById('btnQtyIncrease');
            const btnCloseProductModal = document.getElementById('btnCloseProductModal');
            const btnCancelProduct = document.getElementById('btnCancelProduct');
            const productOrderForm = document.getElementById('productOrderForm');

            // Helper to keep quantity and total in sync
            function updateProductTotal() {
                let quantity = parseInt(productQuantity.value) || 0;
                if (quantity < 1) {
                    quantity = 1;
                    productQuantity.value = 1;
                }
                const total = currentProductPrice * quantity;
                productTotal.textContent = 'P' + total.toFixed(2);
            }

            // Update total when quantity changes manually
            productQuantity.addEventListener('input', updateProductTotal);

            // Decrease quantity
            btnQtyDecrease.addEventListener('click', function() {
                let quantity = parseInt(productQuantity.value) || 1;
                if (quantity > 1) {
                    productQuantity.value = quantity - 1;
                    updateProductTotal();
                }
            });

            // Increase quantity
            btnQtyIncrease.addEventListener('click', function() {
                let quantity = parseInt(productQuantity.value) || 1;
                productQuantity.value = quantity + 1;
                updateProductTotal();
            });

            // Close modal handlers
            btnCloseProductModal.addEventListener('click', function() {
                productModal.classList.add('hidden');
            });

            btnCancelProduct.addEventListener('click', function() {
                productModal.classList.add('hidden');
            });

            // Close modal when clicking outside
            productModal.addEventListener('click', function(e) {
                if (e.target === productModal) {
                    productModal.classList.add('hidden');
                }
            });

            // Handle form submission - Add to Cart
            productOrderForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const productId = currentProductId;
                const productName = productModalTitle.textContent;
                const quantity = parseInt(productQuantity.value);
                const price = currentProductPrice;
                const total = price * quantity;
                
                // Check if product already exists in cart
                const existingItem = orderCart.find(item => item.product_id === productId);
                if (existingItem) {
                    existingItem.quantity += quantity;
                    existingItem.total = existingItem.quantity * existingItem.price;
                } else {
                    orderCart.push({
                        product_id: productId,
                        name: productName,
                        price: price,
                        quantity: quantity,
                        total: total
                    });
                }
                
                console.log('Order Cart:', orderCart);
                Toast.success('Added ' + quantity + ' x ' + productName + ' to order!');
                updateCartBadge();
                
                productModal.classList.add('hidden');
            });

            // Customer Orders Modal functionality
            const customerOrdersModal = document.getElementById('customerOrdersModal');
            const btnCloseOrdersModal = document.getElementById('btnCloseOrdersModal');
            const ordersListContainer = document.getElementById('ordersListContainer');
            const grandTotal = document.getElementById('grandTotal');
            const btnClearOrders = document.getElementById('btnClearOrders');
            const btnCheckout = document.getElementById('btnCheckout');

            // Function to render orders list
            function renderOrdersList() {
                if (orderCart.length === 0) {
                    ordersListContainer.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-shopping-cart text-4xl mb-3"></i>
                            <p class="text-lg">No orders yet</p>
                            <p class="text-sm">Add products to get started</p>
                        </div>
                    `;
                    grandTotal.textContent = 'P0.00';
                    return;
                }

                let total = 0;
                let html = '<div class="space-y-3">';
                
                orderCart.forEach((item, index) => {
                    total += item.total;
                    html += `
                        <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 hover:bg-gray-100 transition-colors">
                            <div class="flex-1 cursor-pointer btn-edit-order" data-index="${index}">
                                <h4 class="font-semibold text-gray-800">${item.name}</h4>
                                <p class="text-sm text-gray-600">P${item.price.toFixed(2)} x ${item.quantity}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-primary">P${item.total.toFixed(2)}</span>
                                <button type="button" class="btn-remove-order text-red-500 hover:text-red-700" data-index="${index}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                ordersListContainer.innerHTML = html;
                grandTotal.textContent = 'P' + total.toFixed(2);

                // Add remove button handlers
                document.querySelectorAll('.btn-remove-order').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const index = parseInt(this.getAttribute('data-index'));
                        orderCart.splice(index, 1);
                        renderOrdersList();
                    });
                });

                // Add edit order handlers
                document.querySelectorAll('.btn-edit-order').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const index = parseInt(this.getAttribute('data-index'));
                        openEditOrderModal(index);
                    });
                });
            }

            // Edit Order Modal functionality
            const editOrderModal = document.getElementById('editOrderModal');
            const editOrderModalTitle = document.getElementById('editOrderModalTitle');
            const editOrderPrice = document.getElementById('editOrderPrice');
            const editOrderQuantity = document.getElementById('editOrderQuantity');
            const editOrderTotal = document.getElementById('editOrderTotal');
            const btnEditQtyDecrease = document.getElementById('btnEditQtyDecrease');
            const btnEditQtyIncrease = document.getElementById('btnEditQtyIncrease');
            const btnCloseEditOrderModal = document.getElementById('btnCloseEditOrderModal');
            const btnCancelEditOrder = document.getElementById('btnCancelEditOrder');
            const editOrderForm = document.getElementById('editOrderForm');

            let currentEditIndex = -1;
            let currentEditPrice = 0;

            // Open edit order modal
            function openEditOrderModal(index) {
                const item = orderCart[index];
                currentEditIndex = index;
                currentEditPrice = item.price;
                
                editOrderModalTitle.textContent = 'Edit: ' + item.name;
                editOrderPrice.textContent = 'P' + item.price.toFixed(2);
                editOrderQuantity.value = item.quantity;
                editOrderTotal.textContent = 'P' + item.total.toFixed(2);
                
                editOrderModal.classList.remove('hidden');
            }

            // Update edit order total
            function updateEditOrderTotal() {
                let quantity = parseInt(editOrderQuantity.value) || 0;
                if (quantity < 1) {
                    quantity = 1;
                    editOrderQuantity.value = 1;
                }
                const total = currentEditPrice * quantity;
                editOrderTotal.textContent = 'P' + total.toFixed(2);
            }

            // Edit quantity handlers
            editOrderQuantity.addEventListener('input', updateEditOrderTotal);

            btnEditQtyDecrease.addEventListener('click', function() {
                let quantity = parseInt(editOrderQuantity.value) || 1;
                if (quantity > 1) {
                    editOrderQuantity.value = quantity - 1;
                    updateEditOrderTotal();
                }
            });

            btnEditQtyIncrease.addEventListener('click', function() {
                let quantity = parseInt(editOrderQuantity.value) || 1;
                editOrderQuantity.value = quantity + 1;
                updateEditOrderTotal();
            });

            // Close edit order modal
            btnCloseEditOrderModal.addEventListener('click', function() {
                editOrderModal.classList.add('hidden');
            });

            btnCancelEditOrder.addEventListener('click', function() {
                editOrderModal.classList.add('hidden');
            });

            editOrderModal.addEventListener('click', function(e) {
                if (e.target === editOrderModal) {
                    editOrderModal.classList.add('hidden');
                }
            });

            // Update order in cart
            editOrderForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const newQuantity = parseInt(editOrderQuantity.value);
                
                if (currentEditIndex >= 0 && currentEditIndex < orderCart.length) {
                    orderCart[currentEditIndex].quantity = newQuantity;
                    orderCart[currentEditIndex].total = orderCart[currentEditIndex].price * newQuantity;
                    renderOrdersList();
                }
                
                editOrderModal.classList.add('hidden');
            });

            // Open customer orders modal
            function openOrdersModal() {
                renderOrdersList();
                customerOrdersModal.classList.remove('hidden');
            }

            // Close customer orders modal
            btnCloseOrdersModal.addEventListener('click', function() {
                customerOrdersModal.classList.add('hidden');
            });

            // Close modal when clicking outside
            customerOrdersModal.addEventListener('click', function(e) {
                if (e.target === customerOrdersModal) {
                    customerOrdersModal.classList.add('hidden');
                }
            });

            // Clear all orders
            btnClearOrders.addEventListener('click', function() {
                Confirm.show('Are you sure you want to clear all orders?', function() {
                    orderCart = [];
                    renderOrdersList();
                });
            });

            // Checkout
            btnCheckout.addEventListener('click', function() {
                if (orderCart.length === 0) {
                    Toast.warning('No orders to checkout!');
                    return;
                }
                
                // Close customer orders modal and open checkout modal
                customerOrdersModal.classList.add('hidden');
                openCheckoutModal();
            });

            // Checkout Modal functionality
            const checkoutModal = document.getElementById('checkoutModal');
            const exitConfirmModal = document.getElementById('exitConfirmModal');
            let currentStep = 1;
            let checkoutTotalAmount = 0;

            function openCheckoutModal() {
                currentStep = 1;
                resetStepProgress();
                renderCheckoutCart();
                showStep(1);
                checkoutModal.classList.remove('hidden');
            }

            function renderCheckoutCart() {
                const container = document.getElementById('checkoutCartItems');
                let total = 0;
                let html = '';
                
                orderCart.forEach((item, index) => {
                    total += item.total;
                    html += `
                        <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200">
                            <div class="flex-1">
                                <span class="font-semibold text-gray-800 text-base">${item.name}</span>
                                <p class="text-sm text-gray-500 mt-1">P${item.price.toFixed(2)} x ${item.quantity}</p>
                            </div>
                            <span class="font-bold text-primary text-lg">P${item.total.toFixed(2)}</span>
                        </div>
                    `;
                });
                
                container.innerHTML = html;
                checkoutTotalAmount = total;
                document.getElementById('checkoutTotal').textContent = 'P' + total.toFixed(2);
                document.getElementById('step3Total').textContent = 'P' + total.toFixed(2);
            }

            function showStep(step) {
                document.querySelectorAll('.checkout-step').forEach(s => s.classList.add('hidden'));
                document.getElementById('checkoutStep' + step).classList.remove('hidden');
                updateStepProgress(step);
            }

            function updateStepProgress(step) {
                // Update progress line width
                const progressLine = document.getElementById('progressLine');
                const progressPercent = ((step - 1) / 3) * 100;
                progressLine.style.width = progressPercent + '%';
                
                for (let i = 1; i <= 4; i++) {
                    const stepEl = document.getElementById('step' + i);
                    const label = document.getElementById('step' + i + 'Label');
                    
                    if (i < step) {
                        // Completed step
                        stepEl.classList.remove('border-gray-300', 'bg-white', 'text-gray-400');
                        stepEl.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow-md');
                        label.classList.remove('text-gray-400', 'font-medium');
                        label.classList.add('text-primary', 'font-semibold');
                    } else if (i === step) {
                        // Current step
                        stepEl.classList.remove('border-gray-300', 'bg-white', 'text-gray-400');
                        stepEl.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow-md');
                        label.classList.remove('text-gray-400', 'font-medium');
                        label.classList.add('text-primary', 'font-semibold');
                    } else {
                        // Future step
                        stepEl.classList.remove('border-primary', 'bg-primary', 'text-white', 'shadow-md');
                        stepEl.classList.add('border-gray-300', 'bg-white', 'text-gray-400');
                        label.classList.remove('text-primary', 'font-semibold');
                        label.classList.add('text-gray-400', 'font-medium');
                    }
                }
            }

            function resetStepProgress() {
                currentStep = 1;
                document.getElementById('amountTendered').value = '';
                document.getElementById('changeAmount').textContent = 'P0.00';
                document.getElementById('changeAmount').classList.remove('text-green-600', 'text-red-600');
                document.getElementById('checkoutOrderType').value = 'walk-in';
                document.getElementById('checkoutPaymentMethod').value = 'cash';
            }

            // Step navigation buttons
            document.getElementById('btnCancelCheckout').addEventListener('click', function() {
                showExitConfirmation();
            });

            document.getElementById('btnCloseCheckout').addEventListener('click', function() {
                if (currentStep < 4) {
                    showExitConfirmation();
                } else {
                    closeCheckoutModal();
                }
            });

            document.getElementById('btnToStep2').addEventListener('click', function() {
                currentStep = 2;
                showStep(2);
            });

            document.getElementById('btnBackToStep1').addEventListener('click', function() {
                currentStep = 1;
                showStep(1);
            });

            document.getElementById('btnToStep3').addEventListener('click', function() {
                currentStep = 3;
                showStep(3);
            });

            document.getElementById('btnBackToStep2').addEventListener('click', function() {
                currentStep = 2;
                showStep(2);
            });

            // Amount tendered input
            document.getElementById('amountTendered').addEventListener('input', function() {
                calculateChange();
            });

            // Quick amount buttons
            document.querySelectorAll('.quick-amount').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.dataset.type === 'exact') {
                        document.getElementById('amountTendered').value = checkoutTotalAmount.toFixed(2);
                    } else {
                        document.getElementById('amountTendered').value = this.dataset.amount;
                    }
                    calculateChange();
                });
            });

            function calculateChange() {
                const tendered = parseFloat(document.getElementById('amountTendered').value) || 0;
                const change = tendered - checkoutTotalAmount;
                const changeEl = document.getElementById('changeAmount');
                
                changeEl.textContent = 'P' + change.toFixed(2);
                changeEl.classList.remove('text-green-600', 'text-red-600');
                
                if (change >= 0) {
                    changeEl.classList.add('text-green-600');
                } else {
                    changeEl.classList.add('text-red-600');
                }
            }

            // Complete checkout
            document.getElementById('btnCompleteCheckout').addEventListener('click', async function() {
                const tendered = parseFloat(document.getElementById('amountTendered').value) || 0;
                
                if (tendered < checkoutTotalAmount) {
                    Toast.error('Insufficient amount tendered!');
                    return;
                }

                // Disable button to prevent double submission
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

                try {
                    // Prepare order data for API
                    const orderData = {
                        total_payment_due: checkoutTotalAmount,
                        amount_received: tendered,
                        payment_method: document.getElementById('checkoutPaymentMethod').value,
                        order_type: document.getElementById('checkoutOrderType').value,
                        items: orderCart.map(item => ({
                            product_id: item.product_id,
                            quantity: item.quantity,
                            price: item.price,
                            total: item.total
                        }))
                    };

                    // Send to server
                    const response = await fetch(BASE_URL + 'Order/ProcessPayment', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(orderData)
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Update step 4 content with server response
                        document.getElementById('orderNumber').textContent = result.data.order_number;
                        document.getElementById('finalTotal').textContent = 'P' + checkoutTotalAmount.toFixed(2);
                        document.getElementById('finalTendered').textContent = 'P' + tendered.toFixed(2);
                        document.getElementById('finalChange').textContent = 'P' + (tendered - checkoutTotalAmount).toFixed(2);

                        currentStep = 4;
                        showStep(4);
                        
                        // Clear cart after successful checkout
                        orderCart = [];
                        updateCartBadge();
                        renderMiniCart();

                        Toast.success('Order saved successfully!');
                    } else {
                        Toast.error(result.message || 'Failed to process order');
                    }
                } catch (error) {
                    console.error('Checkout error:', error);
                    Toast.error('Error connecting to server. Please try again.');
                } finally {
                    // Re-enable button
                    this.disabled = false;
                    this.innerHTML = 'Complete';
                }
            });

            // New order button
            document.getElementById('btnNewOrder').addEventListener('click', function() {
                closeCheckoutModal();
            });

            // Print invoice button
            document.getElementById('btnPrintInvoice').addEventListener('click', function() {
                Toast.info('Print feature coming soon!');
            });

            // Exit confirmation handlers
            function showExitConfirmation() {
                exitConfirmModal.classList.remove('hidden');
            }

            document.getElementById('btnCancelExit').addEventListener('click', function() {
                exitConfirmModal.classList.add('hidden');
            });

            document.getElementById('btnConfirmExit').addEventListener('click', function() {
                exitConfirmModal.classList.add('hidden');
                closeCheckoutModal();
            });

            function closeCheckoutModal() {
                checkoutModal.classList.add('hidden');
                resetStepProgress();
                document.getElementById('progressLine').style.width = '0%';
            }

            // Close checkout modal when clicking outside (with confirmation)
            checkoutModal.addEventListener('click', function(e) {
                if (e.target === checkoutModal && currentStep < 4) {
                    showExitConfirmation();
                }
            });

            // Floating Cart functionality
            const floatingCartBtn = document.getElementById('floatingCartBtn');
            const miniCartPanel = document.getElementById('miniCartPanel');
            const cartBadge = document.getElementById('cartBadge');
            const miniCartItems = document.getElementById('miniCartItems');
            const miniCartTotal = document.getElementById('miniCartTotal');
            const btnCloseMiniCart = document.getElementById('btnCloseMiniCart');
            const btnClearCart = document.getElementById('btnClearCart');
            const btnMiniCartCheckout = document.getElementById('btnMiniCartCheckout');
            let miniCartOpen = false;

            // Update cart badge
            function updateCartBadge() {
                const totalItems = orderCart.reduce((sum, item) => sum + item.quantity, 0);
                if (totalItems > 0) {
                    cartBadge.textContent = totalItems > 99 ? '99+' : totalItems;
                    cartBadge.classList.remove('hidden');
                    cartBadge.classList.add('flex');
                } else {
                    cartBadge.classList.add('hidden');
                    cartBadge.classList.remove('flex');
                }
            }

            // Render mini cart items
            function renderMiniCart() {
                if (orderCart.length === 0) {
                    miniCartItems.innerHTML = `
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-shopping-basket text-4xl mb-2"></i>
                            <p class="text-sm">Your cart is empty</p>
                        </div>
                    `;
                    miniCartTotal.textContent = 'P0.00';
                    return;
                }

                let total = 0;
                let html = '';
                
                orderCart.forEach((item, index) => {
                    total += item.total;
                    html += `
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                            <div class="flex-1 pr-3">
                                <p class="font-medium text-gray-800 text-sm">${item.name}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <button type="button" class="mini-qty-btn w-6 h-6 flex items-center justify-center bg-gray-200 hover:bg-gray-300 text-gray-600 text-xs font-bold" data-index="${index}" data-action="decrease">-</button>
                                    <span class="text-sm text-gray-600 w-6 text-center">${item.quantity}</span>
                                    <button type="button" class="mini-qty-btn w-6 h-6 flex items-center justify-center bg-gray-200 hover:bg-gray-300 text-gray-600 text-xs font-bold" data-index="${index}" data-action="increase">+</button>
                                    <span class="text-xs text-gray-400 ml-1">@ P${item.price.toFixed(2)}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-primary text-sm">P${item.total.toFixed(2)}</span>
                                <button type="button" class="mini-remove-btn text-red-400 hover:text-red-600" data-index="${index}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });
                
                miniCartItems.innerHTML = html;
                miniCartTotal.textContent = 'P' + total.toFixed(2);

                // Add event listeners for quantity buttons
                document.querySelectorAll('.mini-qty-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const index = parseInt(this.dataset.index);
                        const action = this.dataset.action;
                        if (action === 'increase') {
                            orderCart[index].quantity++;
                        } else if (action === 'decrease') {
                            if (orderCart[index].quantity > 1) {
                                orderCart[index].quantity--;
                            } else {
                                orderCart.splice(index, 1);
                            }
                        }
                        if (orderCart[index]) {
                            orderCart[index].total = orderCart[index].price * orderCart[index].quantity;
                        }
                        renderMiniCart();
                        updateCartBadge();
                    });
                });

                // Add event listeners for remove buttons
                document.querySelectorAll('.mini-remove-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const index = parseInt(this.dataset.index);
                        orderCart.splice(index, 1);
                        renderMiniCart();
                        updateCartBadge();
                    });
                });
            }

            // Toggle mini cart
            floatingCartBtn.addEventListener('click', function() {
                miniCartOpen = !miniCartOpen;
                if (miniCartOpen) {
                    renderMiniCart();
                    miniCartPanel.classList.remove('hidden');
                } else {
                    miniCartPanel.classList.add('hidden');
                }
            });

            // Close mini cart
            btnCloseMiniCart.addEventListener('click', function() {
                miniCartOpen = false;
                miniCartPanel.classList.add('hidden');
            });

            // Clear cart from mini cart
            btnClearCart.addEventListener('click', function() {
                Confirm.show('Clear all items from cart?', function() {
                    orderCart = [];
                    renderMiniCart();
                    updateCartBadge();
                });
            });

            // Checkout from mini cart
            btnMiniCartCheckout.addEventListener('click', function() {
                if (orderCart.length === 0) {
                    Toast.warning('Your cart is empty!');
                    return;
                }
                miniCartOpen = false;
                miniCartPanel.classList.add('hidden');
                openCheckoutModal();
            });

            // Close mini cart when clicking outside
            document.addEventListener('click', function(e) {
                if (miniCartOpen && !floatingCartBtn.contains(e.target) && !miniCartPanel.contains(e.target)) {
                    miniCartOpen = false;
                    miniCartPanel.classList.add('hidden');
                }
            });

            // Update badge whenever cart changes (hook into existing add to cart)
            const originalSubmitHandler = productOrderForm.onsubmit;
            productOrderForm.addEventListener('submit', function() {
                setTimeout(updateCartBadge, 100);
            });

            // Initialize cart badge on load
            updateCartBadge();
        });    </script>