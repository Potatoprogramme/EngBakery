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
                        <button type="button" id="btnAddMaterial"
                            class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            View Customers Orders
                        </button>
                        <a href="<?= base_url('Order/OrderHistory') ?>" id="btnExport" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            Order History
                        </a>
                    </div>
                </div>
            </div>

            <!-- Floating View Customer button for mobile -->
            <div class="fixed bottom-6 left-0 right-0 flex justify-center z-30 sm:hidden">
                <button type="button" id="btnAddMaterialMobile"
                    class="w-5/6 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    View Customer Orders
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
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <button type="button" class="product-card bg-white block p-4 border border-gray-300 rounded-lg shadow-sm hover:shadow-md hover:border-primary transition-all text-left" data-product-name="Pandesal" data-product-price="5.00">
                        <h5 class="text-lg/5 font-semibold text-gray-800">Pandesal</h5>
                        <p class="text-sm text-gray-600">Classic Filipino bread roll</p>
                        <p class="mt-2 py-2 text-center text-2xl font-bold text-primary rounded bg-gray-100 border border-gray-300">₱5.00</p>
                    </button>
                    <button type="button" class="product-card bg-white block p-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-primary transition-all text-left" data-product-name="Ensaymada" data-product-price="25.00">
                        <h5 class="text-lg/5 font-semibold text-gray-800">Ensaymada</h5>
                        <p class="text-sm text-gray-600">Sweet buttery pastry</p>
                        <p class="mt-2 py-2 text-center text-2xl font-bold text-primary rounded bg-gray-100 border border-gray-300">₱25.00</p>
                    </button>
                    <button type="button" class="product-card bg-white block p-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-primary transition-all text-left" data-product-name="Spanish Bread" data-product-price="8.00">
                        <h5 class="text-lg/5 font-semibold text-gray-800 ">Spanish Bread</h5>
                        <p class="text-sm text-gray-600">Sweet bread with filling</p>
                        <p class="mt-2 py-2 text-center text-2xl font-bold text-primary rounded bg-gray-100 border border-gray-300">₱8.00</p>
                    </button>
                    <button type="button" class="product-card bg-white block p-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-primary transition-all text-left" data-product-name="Cheese Bread" data-product-price="12.00">
                        <h5 class="text-lg/5 font-semibold text-gray-800">Cheese Bread</h5>
                        <p class="text-sm text-gray-600">Bread topped with cheese</p>
                        <p class="mt-2 py-2 text-center text-2xl font-bold text-primary rounded bg-gray-100 border border-gray-300">₱12.00</p>
                    </button>
                    <button type="button" class="product-card bg-white block p-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-primary transition-all text-left" data-product-name="Monay" data-product-price="6.00">
                        <h5 class="text-lg/5 font-semibold text-gray-800">Monay</h5>
                        <p class="text-sm text-gray-600">Traditional oval bread</p>
                        <p class="mt-2 py-2 text-center text-2xl font-bold text-primary rounded bg-gray-100 border border-gray-300">₱6.00</p>
                    </button>
                    <button type="button" class="product-card bg-white block p-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-primary transition-all text-left" data-product-name="Putok" data-product-price="7.00">
                        <h5 class="text-lg/5 font-semibold text-gray-800">Putok</h5>
                        <p class="text-sm text-gray-600">Cracked top bread</p>
                        <p class="mt-2 py-2 text-center text-2xl font-bold text-primary rounded bg-gray-100 border border-gray-300">₱7.00</p>
                    </button>
                </div>
            </div>

            <!-- Tab Content: Drinks -->
            <div id="drinks-content" class="tab-content hidden p-4 bg-white rounded-lg shadow-md mb-20 sm:mb-0">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <button type="button" class="product-card bg-white block p-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-primary transition-all text-left" data-product-name="Coffee" data-product-price="30.00">
                        <h5 class="text-lg font-semibold text-gray-800">Coffee</h5>
                        <p class="text-sm text-gray-600">Hot brewed coffee</p>
                        <p class="mt-2 py-2 text-center text-2xl font-bold text-primary rounded bg-gray-100 border border-gray-300">₱30.00</p>
                    </button>
                    <button type="button" class="product-card bg-white block p-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-primary transition-all text-left" data-product-name="Iced Coffee" data-product-price="35.00">
                        <h5 class="text-lg font-semibold text-gray-800">Iced Coffee</h5>
                        <p class="text-sm text-gray-600">Chilled coffee drink</p>
                        <p class="mt-2 py-2 text-center text-2xl font-bold text-primary rounded bg-gray-100 border border-gray-300">₱35.00</p>
                    </button>
                    <button type="button" class="product-card bg-white block p-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-primary transition-all text-left" data-product-name="Milk Tea" data-product-price="40.00">
                        <h5 class="text-lg font-semibold text-gray-800">Milk Tea</h5>
                        <p class="text-sm text-gray-600">Creamy milk tea</p>
                        <p class="mt-2 py-2 text-center text-2xl font-bold text-primary rounded bg-gray-100 border border-gray-300">₱40.00</p>
                    </button>
                    <button type="button" class="product-card bg-white block p-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-primary transition-all text-left" data-product-name="Juice" data-product-price="25.00">
                        <h5 class="text-lg font-semibold text-gray-800">Juice</h5>
                        <p class="text-sm text-gray-600">Fresh fruit juice</p>
                        <p class="mt-2 py-2 text-center text-2xl font-bold text-primary rounded bg-gray-100 border border-gray-300">₱25.00</p>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Selection Modal -->
    <div id="productModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-md mx-auto p-6 border shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800" id="productModalTitle">Product Name</h3>
                <button type="button" id="btnCloseProductModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="productOrderForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                    <div class="text-2xl font-bold text-primary" id="productPrice">₱0.00</div>
                </div>
                <div class="mb-4">
                    <label for="productQuantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <button type="button" id="btnQtyDecrease" class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 bg-gray-100 text-gray-700 text-lg font-semibold hover:bg-gray-200">
                            -
                        </button>
                        <input type="number" id="productQuantity" name="quantity" min="1" value="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-primary" required>
                        <button type="button" id="btnQtyIncrease" class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 bg-gray-100 text-gray-700 text-lg font-semibold hover:bg-gray-200">
                            +
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                    <div class="text-xl font-bold text-gray-800" id="productTotal">₱0.00</div>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelProduct" class="px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="flex-1 max-w-[75%] px-4 py-3 text-lg font-bold text-white bg-primary rounded-lg hover:bg-secondary transition-all">Add to Order</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Customer Orders Modal -->
    <div id="customerOrdersModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-2xl mx-auto p-6 border shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
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
                    <span class="text-2xl font-bold text-primary" id="grandTotal">₱0.00</span>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnClearOrders" class="px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Clear All</button>
                    <button type="submit" id="btnCheckout" class="flex-1 max-w-[75%] px-4 py-3 text-lg font-bold text-white bg-primary rounded-lg hover:bg-secondary transition-all">Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Order Quantity Modal -->
    <div id="editOrderModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-md mx-auto p-6 border shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800" id="editOrderModalTitle">Edit Order</h3>
                <button type="button" id="btnCloseEditOrderModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editOrderForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                    <div class="text-2xl font-bold text-primary" id="editOrderPrice">₱0.00</div>
                </div>
                <div class="mb-4">
                    <label for="editOrderQuantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <button type="button" id="btnEditQtyDecrease" class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 bg-gray-100 text-gray-700 text-lg font-semibold hover:bg-gray-200">
                            -
                        </button>
                        <input type="number" id="editOrderQuantity" name="quantity" min="1" value="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-primary" required>
                        <button type="button" id="btnEditQtyIncrease" class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 bg-gray-100 text-gray-700 text-lg font-semibold hover:bg-gray-200">
                            +
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                    <div class="text-xl font-bold text-gray-800" id="editOrderTotal">₱0.00</div>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelEditOrder" class="px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="flex-1 max-w-[75%] px-4 py-3 text-lg font-bold text-white bg-primary rounded-lg hover:bg-secondary transition-all">Update Order</button>
                </div>
            </form>
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
                // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
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

            // Product card click handlers
            const productCards = document.querySelectorAll('.product-card');
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

            let currentProductPrice = 0;

            // Open product modal when card is clicked
            productCards.forEach(card => {
                card.addEventListener('click', function() {
                    const productName = this.getAttribute('data-product-name');
                    const price = parseFloat(this.getAttribute('data-product-price'));
                    
                    currentProductPrice = price;
                    productModalTitle.textContent = productName;
                    productPrice.textContent = '₱' + price.toFixed(2);
                    productQuantity.value = 1;
                    productTotal.textContent = '₱' + price.toFixed(2);
                    
                    productModal.classList.remove('hidden');
                });
            });

            // Helper to keep quantity and total in sync
            function updateProductTotal() {
                let quantity = parseInt(productQuantity.value) || 0;
                if (quantity < 1) {
                    quantity = 1;
                    productQuantity.value = 1;
                }
                const total = currentProductPrice * quantity;
                productTotal.textContent = '₱' + total.toFixed(2);
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
                const productName = productModalTitle.textContent;
                const quantity = parseInt(productQuantity.value);
                const price = currentProductPrice;
                const total = price * quantity;
                
                // Check if product already exists in cart
                const existingItem = orderCart.find(item => item.name === productName);
                if (existingItem) {
                    existingItem.quantity += quantity;
                    existingItem.total = existingItem.quantity * existingItem.price;
                } else {
                    orderCart.push({
                        name: productName,
                        price: price,
                        quantity: quantity,
                        total: total
                    });
                }
                
                console.log('Order Cart:', orderCart);
                Toast.success('Added ' + quantity + ' x ' + productName + ' to order!');
                
                productModal.classList.add('hidden');
            });

            // Customer Orders Modal functionality
            const customerOrdersModal = document.getElementById('customerOrdersModal');
            const btnCloseOrdersModal = document.getElementById('btnCloseOrdersModal');
            const ordersListContainer = document.getElementById('ordersListContainer');
            const grandTotal = document.getElementById('grandTotal');
            const btnClearOrders = document.getElementById('btnClearOrders');
            const btnCheckout = document.getElementById('btnCheckout');
            const btnAddMaterial = document.getElementById('btnAddMaterial');
            const btnAddMaterialMobile = document.getElementById('btnAddMaterialMobile');

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
                    grandTotal.textContent = '₱0.00';
                    return;
                }

                let total = 0;
                let html = '<div class="space-y-3">';
                
                orderCart.forEach((item, index) => {
                    total += item.total;
                    html += `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                            <div class="flex-1 cursor-pointer btn-edit-order" data-index="${index}">
                                <h4 class="font-semibold text-gray-800">${item.name}</h4>
                                <p class="text-sm text-gray-600">₱${item.price.toFixed(2)} × ${item.quantity}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-primary">₱${item.total.toFixed(2)}</span>
                                <button type="button" class="btn-remove-order text-red-500 hover:text-red-700" data-index="${index}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                ordersListContainer.innerHTML = html;
                grandTotal.textContent = '₱' + total.toFixed(2);

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
                editOrderPrice.textContent = '₱' + item.price.toFixed(2);
                editOrderQuantity.value = item.quantity;
                editOrderTotal.textContent = '₱' + item.total.toFixed(2);
                
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
                editOrderTotal.textContent = '₱' + total.toFixed(2);
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
                
                // TODO: Implement checkout logic (save to database, process payment, etc.)
                Toast.info('Checkout feature coming soon!\nTotal: ' + grandTotal.textContent);
            });

            // View Customer Orders buttons
            btnAddMaterial.addEventListener('click', openOrdersModal);
            btnAddMaterialMobile.addEventListener('click', openOrdersModal);
        });    </script>