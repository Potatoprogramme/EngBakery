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
                        <a href="<?= base_url('Order/OrderHistory') ?>" id="btnExport"
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            Order History
                        </a>
                    </div>
                </div>
            </div>

            <!-- Floating Cart Button -->
            <div id="floatingCartContainer" class="fixed bottom-6 right-6 z-40">
                <!-- Mini Cart Panel (Hidden by default) -->
                <div id="miniCartPanel"
                    class="hidden absolute bottom-20 right-0 w-80 sm:w-96 bg-white border border-gray-200 shadow-2xl max-h-[70vh] overflow-hidden">
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
                            <button type="button" id="btnClearCart"
                                class="flex-1 px-3 py-2 text-sm font-medium text-gray-600 bg-gray-200 hover:bg-gray-300 transition-colors">
                                Clear
                            </button>
                            <button type="button" id="btnMiniCartCheckout"
                                class="flex-[2] px-4 py-2 text-sm font-bold text-white bg-primary hover:bg-secondary transition-colors">
                                Checkout
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Floating Cart Button -->
                <button type="button" id="floatingCartBtn"
                    class="relative w-16 h-16 bg-primary hover:bg-secondary text-white rounded-full shadow-lg hover:shadow-xl transition-all flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span id="cartBadge"
                        class="hidden absolute -top-1 -right-1 w-6 h-6 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">0</span>
                </button>
            </div>

            <!-- Tabs -->
            <div class="flex gap-2 mb-3">
                <button type="button" data-tab="breads" onclick="switchTab('breads')"
                    class="tab-btn flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-all border-2 border-primary text-white bg-primary shadow-md cursor-pointer">
                    Bakery
                </button>
                <button type="button" data-tab="drinks" onclick="switchTab('drinks')"
                    class="tab-btn flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-all border-2 border-gray-300 text-gray-700 bg-gray-100 hover:bg-gray-200 hover:border-gray-400 cursor-pointer">
                    Drinks
                </button>
                <button type="button" data-tab="grocery" onclick="switchTab('grocery')"
                    class="tab-btn flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-all border-2 border-gray-300 text-gray-700 bg-gray-100 hover:bg-gray-200 hover:border-gray-400 cursor-pointer">
                    Grocery
                </button>
            </div>

            <!-- Tab Content: Bakery -->
            <div id="breads-content" class="tab-content p-3 bg-white rounded-lg shadow-md mb-20 sm:mb-0">
                <div id="breads-grid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-2">
                    <!-- Products will be loaded dynamically -->
                    <div class="col-span-full text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                        <p>Loading products...</p>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Drinks -->
            <div id="drinks-content" class="tab-content hidden p-3 bg-white rounded-lg shadow-md mb-20 sm:mb-0">
                <div id="drinks-grid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-2">
                    <!-- Products will be loaded dynamically -->
                    <div class="col-span-full text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                        <p>Loading products...</p>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Grocery -->
            <div id="grocery-content" class="tab-content hidden p-3 bg-white rounded-lg shadow-md mb-20 sm:mb-0">
                <div id="grocery-grid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-2">
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
    <div id="productModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-md mx-auto p-6 border shadow-lg bg-white rounded-lg">
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
                    <label for="productQuantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity <span
                            class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <button type="button" id="btnQtyDecrease"
                            class="flex-shrink-0 w-10 h-10 flex items-center justify-center border border-gray-300 bg-gray-100 text-gray-700 text-lg font-semibold hover:bg-gray-200">
                            -
                        </button>
                        <input type="number" id="productQuantity" name="quantity" min="1" value="1"
                            class="w-full px-4 py-2 border border-gray-300 text-center focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                        <button type="button" id="btnQtyIncrease"
                            class="flex-shrink-0 w-10 h-10 flex items-center justify-center border border-gray-300 bg-gray-100 text-gray-700 text-lg font-semibold hover:bg-gray-200">
                            +
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                    <div class="text-xl font-bold text-gray-800" id="productTotal">P0.00</div>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelProduct"
                        class="px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200">Cancel</button>
                    <button type="submit"
                        class="flex-1 max-w-[75%] px-4 py-3 text-lg font-bold text-white bg-primary hover:bg-secondary transition-all">Add
                        to Order</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Customer Orders Modal -->
    <div id="customerOrdersModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
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
                    <button type="button" id="btnClearOrders"
                        class="px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200">Clear
                        All</button>
                    <button type="submit" id="btnCheckout"
                        class="flex-1 max-w-[75%] px-4 py-3 text-lg font-bold text-white bg-primary hover:bg-secondary transition-all">Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Order Quantity Modal -->
    <div id="editOrderModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
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
                    <label for="editOrderQuantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity <span
                            class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <button type="button" id="btnEditQtyDecrease"
                            class="flex-shrink-0 w-10 h-10 flex items-center justify-center border border-gray-300 bg-gray-100 text-gray-700 text-lg font-semibold hover:bg-gray-200">
                            -
                        </button>
                        <input type="number" id="editOrderQuantity" name="quantity" min="1" value="1"
                            class="w-full px-4 py-2 border border-gray-300 text-center focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                        <button type="button" id="btnEditQtyIncrease"
                            class="flex-shrink-0 w-10 h-10 flex items-center justify-center border border-gray-300 bg-gray-100 text-gray-700 text-lg font-semibold hover:bg-gray-200">
                            +
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                    <div class="text-xl font-bold text-gray-800" id="editOrderTotal">P0.00</div>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelEditOrder"
                        class="px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200">Cancel</button>
                    <button type="submit"
                        class="flex-1 max-w-[75%] px-4 py-3 text-lg font-bold text-white bg-primary hover:bg-secondary transition-all">Update
                        Order</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Checkout Modal with Step Progress -->
    <!-- Checkout Modal with Step Progress -->
    <div id="checkoutModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-4 border shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto"
            style="max-width: 42rem;">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary">Checkout</h3>
                <button type="button" id="btnCloseCheckout" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Progress Stepper (same style as Product modal) -->
            <div class="mb-6">
                <div class="flex items-center w-full px-2 sm:px-4">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center min-w-[60px] sm:min-w-[80px]">
                        <div id="step1"
                            class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-primary/10 border-2 border-primary text-primary text-xs sm:text-sm font-semibold mb-1 sm:mb-2">
                            1
                        </div>
                        <span id="step1Label"
                            class="text-[9px] sm:text-[11px] font-medium text-primary text-center leading-tight">Cart</span>
                    </div>
                    <!-- Connector -->
                    <div id="connector1" class="flex-1 h-0.5 bg-gray-300 -mt-5 sm:-mt-6 mx-1 sm:mx-2"></div>
                    <!-- Step 2 -->
                    <div class="flex flex-col items-center min-w-[60px] sm:min-w-[80px]">
                        <div id="step2"
                            class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full border-2 border-gray-300 text-gray-400 text-xs sm:text-sm font-semibold mb-1 sm:mb-2">
                            2
                        </div>
                        <span id="step2Label"
                            class="text-[9px] sm:text-[11px] font-medium text-gray-400 text-center leading-tight">Payment</span>
                    </div>
                    <!-- Connector -->
                    <div id="connector2" class="flex-1 h-0.5 bg-gray-300 -mt-5 sm:-mt-6 mx-1 sm:mx-2"></div>
                    <!-- Step 3 -->
                    <div class="flex flex-col items-center min-w-[60px] sm:min-w-[80px]">
                        <div id="step3"
                            class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full border-2 border-gray-300 text-gray-400 text-xs sm:text-sm font-semibold mb-1 sm:mb-2">
                            3
                        </div>
                        <span id="step3Label"
                            class="text-[9px] sm:text-[11px] font-medium text-gray-400 text-center leading-tight">Amount</span>
                    </div>
                    <!-- Connector -->
                    <div id="connector3" class="flex-1 h-0.5 bg-gray-300 -mt-5 sm:-mt-6 mx-1 sm:mx-2"></div>
                    <!-- Step 4 -->
                    <div class="flex flex-col items-center min-w-[60px] sm:min-w-[80px]">
                        <div id="step4"
                            class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full border-2 border-gray-300 text-gray-400 text-xs sm:text-sm font-semibold mb-1 sm:mb-2">
                            4
                        </div>
                        <span id="step4Label"
                            class="text-[9px] sm:text-[11px] font-medium text-gray-400 text-center leading-tight">Done</span>
                    </div>
                </div>
            </div>

            <!-- Step Content Container -->
            <div>
                <!-- Step 1: Cart Review -->
                <div id="checkoutStep1" class="checkout-step">
                    <div class="mb-3 p-3 border border-gray-200 rounded-md bg-gray-50">
                        <h4 class="text-center text-sm font-medium mb-3">Order Summary</h4>
                        <div id="checkoutCartItems" class="space-y-2 max-h-48 overflow-y-auto">
                            <!-- Cart items will be inserted here -->
                        </div>
                    </div>
                    <div class="p-3 border border-primary/20 rounded-md bg-primary/5 mb-4">
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>Total:</span>
                            <span class="text-primary" id="checkoutTotal">P0.00</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" id="btnCancelCheckout"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                        <button type="button" id="btnToStep2"
                            class="flex-1 px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Next</button>
                    </div>
                </div>

                <!-- Step 2: Payment Method -->
                <div id="checkoutStep2" class="checkout-step hidden">
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Type <span
                                class="text-red-500">*</span></label>
                        <input type="hidden" id="checkoutOrderType" value="walk-in">
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" id="btnOrderTypeWalkin" onclick="selectOrderType('walk-in')"
                                class="order-type-btn p-4 rounded-lg border-2 border-primary bg-primary/10 text-center transition-all hover:shadow-md">
                                <i class="fas fa-walking text-2xl text-primary mb-2"></i>
                                <p class="font-semibold text-primary">Walk-in</p>
                            </button>
                            <button type="button" id="btnOrderTypeFoodpanda" onclick="selectOrderType('foodpanda')"
                                class="order-type-btn p-4 rounded-lg border-2 border-gray-300 bg-white text-center transition-all hover:shadow-md hover:border-pink-300">
                                <img src="<?= base_url('assets/pictures/icons8-foodpanda-96.png') ?>" class="w-8 h-8 mx-auto mb-2" alt="FoodPanda">
                                <p class="font-semibold text-gray-700">FoodPanda</p>
                            </button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span
                                class="text-red-500">*</span></label>
                        <select id="checkoutPaymentMethod"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="cash">Cash</option>
                            <option value="gcash">GCash</option>
                            <option value="maya">Maya</option>
                            <option value="credit card">Credit Card</option>
                            <option value="debit card">Debit Card</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" id="btnBackToStep1"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Back</button>
                        <button type="button" id="btnToStep3"
                            class="flex-1 px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Next</button>
                    </div>
                </div>

                <!-- Step 3: Amount Tendered -->
                <div id="checkoutStep3" class="checkout-step hidden">
                    <div class="mb-3 p-3 border border-primary/20 rounded-md bg-primary/5">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Total Amount:</span>
                            <span class="text-xl font-bold text-primary" id="step3Total">P0.00</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount Tendered <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                            <input type="number" id="amountTendered"
                                class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary text-lg font-semibold"
                                placeholder="0.00" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-2 mb-3">
                        <button type="button"
                            class="quick-amount px-2 py-2 text-xs font-medium border border-gray-300 rounded-md bg-gray-50 hover:bg-primary hover:text-white hover:border-primary transition-colors"
                            data-type="exact">Exact</button>
                        <button type="button"
                            class="quick-amount px-2 py-2 text-xs font-medium border border-gray-300 rounded-md bg-gray-50 hover:bg-primary hover:text-white hover:border-primary transition-colors"
                            data-amount="50">₱50</button>
                        <button type="button"
                            class="quick-amount px-2 py-2 text-xs font-medium border border-gray-300 rounded-md bg-gray-50 hover:bg-primary hover:text-white hover:border-primary transition-colors"
                            data-amount="100">₱100</button>
                        <button type="button"
                            class="quick-amount px-2 py-2 text-xs font-medium border border-gray-300 rounded-md bg-gray-50 hover:bg-primary hover:text-white hover:border-primary transition-colors"
                            data-amount="500">₱500</button>
                    </div>
                    <div class="p-3 border border-gray-200 rounded-md bg-gray-50 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Change:</span>
                            <span class="text-xl font-bold text-green-600" id="changeAmount">₱0.00</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" id="btnBackToStep2"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Back</button>
                        <button type="button" id="btnCompleteCheckout"
                            class="flex-1 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">Complete</button>
                    </div>
                </div>

                <!-- Step 4: Success -->
                <div id="checkoutStep4" class="checkout-step hidden">
                    <div class="text-center py-4">
                        <div
                            class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-green-100 text-green-600 rounded-full border-2 border-green-500">
                            <i class="fas fa-check text-3xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Order Complete!</h4>
                        <p class="text-sm text-gray-500 mb-1">Order Number:</p>
                        <p class="text-2xl font-bold text-primary mb-4" id="orderNumber">ORD-000000-000</p>
                        <div class="p-3 border border-gray-200 rounded-md bg-gray-50 mb-4 text-left">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-bold text-gray-800" id="finalTotal">₱0.00</span>
                            </div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-600">Tendered:</span>
                                <span class="text-gray-800" id="finalTendered">₱0.00</span>
                            </div>
                            <div class="flex justify-between text-base font-bold pt-2 border-t border-gray-300">
                                <span class="text-gray-700">Change:</span>
                                <span class="text-green-600" id="finalChange">₱0.00</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" id="btnPrintInvoice"
                                class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                <i class="fas fa-print mr-2"></i>Print
                            </button>
                            <button type="button" id="btnNewOrder"
                                class="flex-1 px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">New
                                Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exit Confirmation Modal -->
    <div id="exitConfirmModal"
        class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 overflow-y-auto h-full w-full z-[60] flex items-center justify-center p-4">
        <div class="relative w-full max-w-md mx-auto p-8 border shadow-xl bg-white">
            <div class="text-center">
                <div
                    class="w-20 h-20 mx-auto mb-5 flex items-center justify-center bg-yellow-100 text-yellow-600 rounded-full">
                    <i class="fas fa-exclamation-triangle text-4xl"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-800 mb-3">Exit Checkout?</h4>
                <p class="text-gray-600 mb-6">Are you sure you want to exit? Your checkout progress will be lost.</p>
                <div class="flex gap-3">
                    <button type="button" id="btnCancelExit"
                        class="flex-1 px-5 py-4 text-base font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">Stay</button>
                    <button type="button" id="btnConfirmExit"
                        class="flex-1 px-5 py-4 text-base font-bold text-white bg-red-600 hover:bg-red-700 transition-colors">Exit</button>
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
                            <label for="material_quantity"
                                class="flex-1 block text-sm font-medium text-gray-700 mb-1">Quantity <span
                                    class="text-red-500">*</span></label>
                            <label for="unit" class="w-32 block text-sm font-medium text-gray-700 mb-1">Unit of
                                Measure</label>
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

    <!-- Tab Switching Function (inline to ensure it works) -->
    <script>
        function switchTab(tabName) {
            // Remove active state from all tab buttons
            document.querySelectorAll('.tab-btn').forEach(function (btn) {
                btn.classList.remove('text-white', 'bg-primary', 'shadow-md', 'border-primary');
                btn.classList.add('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200', 'border-gray-300', 'hover:border-gray-400');
            });

            // Add active state to clicked button
            var activeBtn = document.querySelector('.tab-btn[data-tab="' + tabName + '"]');
            if (activeBtn) {
                activeBtn.classList.remove('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200', 'border-gray-300', 'hover:border-gray-400');
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

        // Order Type Selection Function
        function selectOrderType(type) {
            document.getElementById('checkoutOrderType').value = type;
            
            // Reset all buttons
            document.getElementById('btnOrderTypeWalkin').classList.remove('border-primary', 'bg-primary/10');
            document.getElementById('btnOrderTypeWalkin').classList.add('border-gray-300', 'bg-white');
            document.getElementById('btnOrderTypeWalkin').querySelector('i').classList.remove('text-primary');
            document.getElementById('btnOrderTypeWalkin').querySelector('i').classList.add('text-gray-500');
            document.getElementById('btnOrderTypeWalkin').querySelector('p').classList.remove('text-primary');
            document.getElementById('btnOrderTypeWalkin').querySelector('p').classList.add('text-gray-700');
            
            document.getElementById('btnOrderTypeFoodpanda').classList.remove('border-pink-500', 'bg-pink-50');
            document.getElementById('btnOrderTypeFoodpanda').classList.add('border-gray-300', 'bg-white');
            document.getElementById('btnOrderTypeFoodpanda').querySelector('p').classList.remove('text-pink-600');
            document.getElementById('btnOrderTypeFoodpanda').querySelector('p').classList.add('text-gray-700');
            
            // Highlight selected button
            if (type === 'walk-in') {
                document.getElementById('btnOrderTypeWalkin').classList.remove('border-gray-300', 'bg-white');
                document.getElementById('btnOrderTypeWalkin').classList.add('border-primary', 'bg-primary/10');
                document.getElementById('btnOrderTypeWalkin').querySelector('i').classList.remove('text-gray-500');
                document.getElementById('btnOrderTypeWalkin').querySelector('i').classList.add('text-primary');
                document.getElementById('btnOrderTypeWalkin').querySelector('p').classList.remove('text-gray-700');
                document.getElementById('btnOrderTypeWalkin').querySelector('p').classList.add('text-primary');
            } else if (type === 'foodpanda') {
                document.getElementById('btnOrderTypeFoodpanda').classList.remove('border-gray-300', 'bg-white');
                document.getElementById('btnOrderTypeFoodpanda').classList.add('border-pink-500', 'bg-pink-50');
                document.getElementById('btnOrderTypeFoodpanda').querySelector('p').classList.remove('text-gray-700');
                document.getElementById('btnOrderTypeFoodpanda').querySelector('p').classList.add('text-pink-600');
            }
        }
    </script>

    <!-- Set base URL for JS modules -->
    <script>
        window.BASE_URL = '<?= rtrim(site_url(), '/') ?>/';
    </script>

    <!-- Order Module Scripts -->
    <script src="<?= base_url('js/order/cart-manager.js') ?>"></script>
    <script src="<?= base_url('js/order/order-main.js') ?>"></script>
</body>

</html>