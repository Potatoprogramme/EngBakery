/**
 * Order Main - Main initialization and all event handlers for the Order page
 * This file handles all the order functionality
 */

// ==========================================
// Global State Variables
// ==========================================
let productsData = [];
let currentProductPrice = 0;
let currentProductId = null;
let currentProductCategory = 'bakery';
let currentEditIndex = -1;
let currentEditPrice = 0;
let currentStep = 1;
let checkoutTotalAmount = 0;
let miniCartOpen = false;

// ==========================================
// DOM Ready - Initialize Everything
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    // Load products on page load
    loadProducts();
    
    // Initialize all modules
    initTabs();
    initProductModal();
    initCustomerOrdersModal();
    initEditOrderModal();
    initCheckoutModal();
    initFloatingCart();
    
    // Initialize cart badge and mini cart on load (restore from localStorage)
    updateCartBadge();
    renderMiniCart();
});

// ==========================================
// Products Loading and Rendering
// ==========================================
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

function renderProducts() {
    const breadsGrid = document.getElementById('breads-grid');
    const drinksGrid = document.getElementById('drinks-grid');
    const groceryGrid = document.getElementById('grocery-grid');
    
    const breads = productsData.filter(p => p.category === 'bakery');
    const drinks = productsData.filter(p => p.category === 'drinks');
    const grocery = productsData.filter(p => p.category === 'grocery');
    
    breadsGrid.innerHTML = breads.length > 0 ? breads.map(p => createProductCard(p)).join('') : noProductsHtml('bakery');
    drinksGrid.innerHTML = drinks.length > 0 ? drinks.map(p => createProductCard(p)).join('') : noProductsHtml('drinks');
    groceryGrid.innerHTML = grocery.length > 0 ? grocery.map(p => createProductCard(p)).join('') : noProductsHtml('grocery');
    
    // Re-attach click handlers to new product cards
    attachProductCardHandlers();
}

function createProductCard(product) {
    const price = parseFloat(product.price) || 0;
    const category = product.category || 'bakery';
    const stock = parseInt(product.available_stock) || 0;
    
    // Drinks don't track stock - always available
    const isDrink = category === 'drinks';
    const isOutOfStock = !isDrink && stock <= 0;
    
    if (isOutOfStock) {
        return `
            <div class="product-card-disabled bg-gray-100 p-2 border border-dashed border-gray-300 rounded-lg cursor-not-allowed text-center">
                <p class="text-xs font-semibold text-gray-400 truncate">${product.product_name}</p>
                <p class="text-sm font-bold text-gray-300 mt-1">P${price.toFixed(2)}</p>
                <p class="text-[10px] text-red-400 mt-1">No Stock</p>
            </div>
        `;
    }
    
    // Stock display: drinks show "Available", others show count
    const stockDisplay = isDrink 
        ? '<p class="text-[10px] text-blue-600 mt-1">Available</p>'
        : `<p class="text-[10px] text-green-600 mt-1">${stock} left</p>`;
    
    return `
        <button type="button" class="product-card bg-white p-2 border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-primary hover:bg-primary/5 active:scale-95 transition-all text-center select-none" 
            data-product-id="${product.product_id}"
            data-product-name="${product.product_name}" 
            data-product-price="${price.toFixed(2)}"
            data-product-category="${category}"
            data-product-stock="${stock}">
            <p class="text-xs font-semibold text-gray-800 truncate">${product.product_name}</p>
            <p class="text-lg font-bold text-primary mt-1">P${price.toFixed(2)}</p>
            ${stockDisplay}
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
    document.getElementById('grocery-grid').innerHTML = errorHtml;
}

function attachProductCardHandlers() {
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const price = parseFloat(this.getAttribute('data-product-price'));
            const category = this.getAttribute('data-product-category') || 'bakery';
            
            currentProductId = productId;
            currentProductPrice = price;
            currentProductCategory = category;
            document.getElementById('productModalTitle').textContent = productName;
            document.getElementById('productPrice').textContent = 'P' + price.toFixed(2);
            document.getElementById('productQuantity').value = 1;
            document.getElementById('productTotal').textContent = 'P' + price.toFixed(2);
            
            document.getElementById('productModal').classList.remove('hidden');
        });
    });
}


// ==========================================
// Product Modal
// ==========================================

/**
 * Check if the current screen size is mobile or tablet (width <= 1024px)
 * @returns {boolean} True if mobile/tablet, false if desktop
 */
function isMobileOrTablet() {
    return window.innerWidth <= 1024;
}

/**
 * Initialize the product selection modal
 * Handles quantity controls, price display, and adding items to cart
 */
function initProductModal() {
    const productModal = document.getElementById('productModal');
    const productQuantity = document.getElementById('productQuantity');
    const btnQtyDecrease = document.getElementById('btnQtyDecrease');
    const btnQtyIncrease = document.getElementById('btnQtyIncrease');
    const btnCloseProductModal = document.getElementById('btnCloseProductModal');
    const btnCancelProduct = document.getElementById('btnCancelProduct');
    const productOrderForm = document.getElementById('productOrderForm');

    /**
     * Update the total price display based on quantity
     */
    function updateProductTotal() {
        let quantity = parseInt(productQuantity.value) || 0;
        if (quantity < 1) {
            quantity = 1;
            productQuantity.value = 1;
        }
        const total = currentProductPrice * quantity;
        document.getElementById('productTotal').textContent = 'P' + total.toFixed(2);
    }

    productQuantity.addEventListener('input', updateProductTotal);

    btnQtyDecrease.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let quantity = parseInt(productQuantity.value) || 1;
        if (quantity > 1) {
            productQuantity.value = quantity - 1;
            updateProductTotal();
        }
    });

    btnQtyIncrease.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let quantity = parseInt(productQuantity.value) || 1;
        productQuantity.value = quantity + 1;
        updateProductTotal();
    });

    btnCloseProductModal.addEventListener('click', function() {
        productModal.classList.add('hidden');
    });

    btnCancelProduct.addEventListener('click', function() {
        productModal.classList.add('hidden');
    });

    // Close modal on backdrop click - only on mobile/tablet
    productModal.addEventListener('click', function(e) {
        if (e.target === productModal && isMobileOrTablet()) {
            productModal.classList.add('hidden');
        }
    });

    productOrderForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const productName = document.getElementById('productModalTitle').textContent;
        const quantity = parseInt(productQuantity.value);
        
        CartManager.addItem(currentProductId, productName, currentProductPrice, quantity, currentProductCategory);
        
        console.log('Order Cart:', CartManager.getCart());
        Toast.success('Added ' + quantity + ' x ' + productName + ' to order!');
        updateCartBadge();
        renderMiniCart();
        
        productModal.classList.add('hidden');
    });
}

// ==========================================
// Customer Orders Modal
// ==========================================
function initCustomerOrdersModal() {
    const customerOrdersModal = document.getElementById('customerOrdersModal');
    const btnCloseOrdersModal = document.getElementById('btnCloseOrdersModal');
    const btnClearOrders = document.getElementById('btnClearOrders');
    const btnCheckout = document.getElementById('btnCheckout');

    btnCloseOrdersModal.addEventListener('click', function() {
        customerOrdersModal.classList.add('hidden');
    });

    // Close modal on backdrop click - only on mobile/tablet
    customerOrdersModal.addEventListener('click', function(e) {
        if (e.target === customerOrdersModal && isMobileOrTablet()) {
            customerOrdersModal.classList.add('hidden');
        }
    });

    btnClearOrders.addEventListener('click', function() {
        Confirm.show('Are you sure you want to clear all orders?', function() {
            CartManager.clearCart();
            renderOrdersList();
            updateCartBadge();
            renderMiniCart();
        });
    });

    btnCheckout.addEventListener('click', function() {
        if (CartManager.getCart().length === 0) {
            Toast.warning('No orders to checkout!');
            return;
        }
        customerOrdersModal.classList.add('hidden');
        openCheckoutModal();
    });
}

function openOrdersModal() {
    renderOrdersList();
    document.getElementById('customerOrdersModal').classList.remove('hidden');
}

function renderOrdersList() {
    const ordersListContainer = document.getElementById('ordersListContainer');
    const grandTotal = document.getElementById('grandTotal');
    const orderCart = CartManager.getCart();
    
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
            CartManager.removeByIndex(index);
            renderOrdersList();
            updateCartBadge();
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

// ==========================================
// Edit Order Modal
// ==========================================
function initEditOrderModal() {
    const editOrderModal = document.getElementById('editOrderModal');
    const editOrderQuantity = document.getElementById('editOrderQuantity');
    const btnEditQtyDecrease = document.getElementById('btnEditQtyDecrease');
    const btnEditQtyIncrease = document.getElementById('btnEditQtyIncrease');
    const btnCloseEditOrderModal = document.getElementById('btnCloseEditOrderModal');
    const btnCancelEditOrder = document.getElementById('btnCancelEditOrder');
    const editOrderForm = document.getElementById('editOrderForm');

    function updateEditOrderTotal() {
        let quantity = parseInt(editOrderQuantity.value) || 0;
        if (quantity < 1) {
            quantity = 1;
            editOrderQuantity.value = 1;
        }
        const total = currentEditPrice * quantity;
        document.getElementById('editOrderTotal').textContent = 'P' + total.toFixed(2);
    }

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

    btnCloseEditOrderModal.addEventListener('click', function() {
        editOrderModal.classList.add('hidden');
    });

    btnCancelEditOrder.addEventListener('click', function() {
        editOrderModal.classList.add('hidden');
    });

    // Close modal on backdrop click - only on mobile/tablet
    editOrderModal.addEventListener('click', function(e) {
        if (e.target === editOrderModal && isMobileOrTablet()) {
            editOrderModal.classList.add('hidden');
        }
    });

    editOrderForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const newQuantity = parseInt(editOrderQuantity.value);
        const orderCart = CartManager.getCart();
        
        if (currentEditIndex >= 0 && currentEditIndex < orderCart.length) {
            CartManager.updateByIndex(currentEditIndex, newQuantity);
            renderOrdersList();
            updateCartBadge();
            renderMiniCart();
        }
        
        editOrderModal.classList.add('hidden');
    });
}

function openEditOrderModal(index) {
    const orderCart = CartManager.getCart();
    const item = orderCart[index];
    currentEditIndex = index;
    currentEditPrice = item.price;
    
    document.getElementById('editOrderModalTitle').textContent = 'Edit: ' + item.name;
    document.getElementById('editOrderPrice').textContent = 'P' + item.price.toFixed(2);
    document.getElementById('editOrderQuantity').value = item.quantity;
    document.getElementById('editOrderTotal').textContent = 'P' + item.total.toFixed(2);
    
    document.getElementById('editOrderModal').classList.remove('hidden');
}

// ==========================================
// Checkout Modal (4 Steps)
// ==========================================
function initCheckoutModal() {
    const checkoutModal = document.getElementById('checkoutModal');
    const exitConfirmModal = document.getElementById('exitConfirmModal');

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

    document.getElementById('amountTendered').addEventListener('input', function() {
        calculateChange();
    });

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

    document.getElementById('btnCompleteCheckout').addEventListener('click', completeCheckout);

    document.getElementById('btnNewOrder').addEventListener('click', function() {
        closeCheckoutModal();
    });

    document.getElementById('btnPrintInvoice').addEventListener('click', function() {
        Toast.info('Print feature coming soon!');
    });

    document.getElementById('btnCancelExit').addEventListener('click', function() {
        exitConfirmModal.classList.add('hidden');
    });

    document.getElementById('btnConfirmExit').addEventListener('click', function() {
        exitConfirmModal.classList.add('hidden');
        closeCheckoutModal();
    });

    // Close modal on backdrop click - only on mobile/tablet (shows exit confirmation if not on step 4)
    checkoutModal.addEventListener('click', function(e) {
        if (e.target === checkoutModal && isMobileOrTablet()) {
            if (currentStep < 4) {
                showExitConfirmation();
            } else {
                closeCheckoutModal();
            }
        }
    });
}

function openCheckoutModal() {
    currentStep = 1;
    resetStepProgress();
    renderCheckoutCart();
    showStep(1);
    document.getElementById('checkoutModal').classList.remove('hidden');
}

function renderCheckoutCart() {
    const container = document.getElementById('checkoutCartItems');
    const orderCart = CartManager.getCart();
    let total = 0;
    let html = '';
    
    orderCart.forEach((item) => {
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

/**
 * Show a specific step in the checkout process
 * @param {number} step - The step number to display (1-4)
 */
function showStep(step) {
    // Hide all checkout steps
    document.querySelectorAll('.checkout-step').forEach(s => s.classList.add('hidden'));
    // Show the target step
    document.getElementById('checkoutStep' + step).classList.remove('hidden');
    // Update the progress indicator
    updateStepProgress(step);
}

/**
 * Update the step progress indicator UI
 * Matches the Product modal stepper style with circles and connectors
 * @param {number} step - The current active step (1-4)
 */
function updateStepProgress(step) {
    // Update step indicators (matching Product modal style)
    for (let i = 1; i <= 4; i++) {
        const stepEl = document.getElementById('step' + i);
        const label = document.getElementById('step' + i + 'Label');
        
        if (i < step) {
            // Completed steps - filled with primary color
            stepEl.classList.remove('border-gray-300', 'text-gray-400', 'bg-primary/10');
            stepEl.classList.add('border-primary', 'bg-primary', 'text-white');
            label.classList.remove('text-gray-400');
            label.classList.add('text-primary');
        } else if (i === step) {
            // Current step - primary outline with light background
            stepEl.classList.remove('border-gray-300', 'text-gray-400', 'bg-primary', 'text-white');
            stepEl.classList.add('border-primary', 'text-primary', 'bg-primary/10');
            label.classList.remove('text-gray-400');
            label.classList.add('text-primary');
        } else {
            // Future steps - gray outline
            stepEl.classList.remove('border-primary', 'bg-primary', 'text-white', 'text-primary', 'bg-primary/10');
            stepEl.classList.add('border-gray-300', 'text-gray-400');
            label.classList.remove('text-primary');
            label.classList.add('text-gray-400');
        }
    }
    
    // Update connector lines between steps
    for (let i = 1; i <= 3; i++) {
        const connector = document.getElementById('connector' + i);
        if (connector) {
            if (i < step) {
                // Completed connector - primary color
                connector.classList.remove('bg-gray-300');
                connector.classList.add('bg-primary');
            } else {
                // Incomplete connector - gray color
                connector.classList.remove('bg-primary');
                connector.classList.add('bg-gray-300');
            }
        }
    }
}

/**
 * Reset the checkout progress to initial state
 * Called when starting a new checkout or closing the modal
 */
function resetStepProgress() {
    currentStep = 1;
    document.getElementById('amountTendered').value = '';
    document.getElementById('changeAmount').textContent = '₱0.00';
    document.getElementById('changeAmount').classList.remove('text-green-600', 'text-red-600');
    document.getElementById('checkoutOrderType').value = 'walk-in';
    document.getElementById('checkoutPaymentMethod').value = 'cash';
}

/**
 * Calculate and display the change amount
 * Shows green for positive change, red for insufficient amount
 */
function calculateChange() {
    const tendered = parseFloat(document.getElementById('amountTendered').value) || 0;
    const change = tendered - checkoutTotalAmount;
    const changeEl = document.getElementById('changeAmount');
    
    // Display the change amount with peso sign
    changeEl.textContent = '₱' + change.toFixed(2);
    changeEl.classList.remove('text-green-600', 'text-red-600');
    
    // Color code: green for sufficient, red for insufficient
    if (change >= 0) {
        changeEl.classList.add('text-green-600');
    } else {
        changeEl.classList.add('text-red-600');
    }
}

/**
 * Complete the checkout process
 * Validates amount, sends order to server, and shows success
 */
async function completeCheckout() {
    const tendered = parseFloat(document.getElementById('amountTendered').value) || 0;
    
    if (tendered < checkoutTotalAmount) {
        Toast.error('Insufficient amount tendered!');
        return;
    }

    const btn = document.getElementById('btnCompleteCheckout');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

    try {
        const orderCart = CartManager.getCart();
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

        const response = await fetch(BASE_URL + 'Order/ProcessPayment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(orderData)
        });

        const result = await response.json();

        if (result.success) {
            document.getElementById('orderNumber').textContent = result.data.order_number;
            document.getElementById('finalTotal').textContent = 'P' + checkoutTotalAmount.toFixed(2);
            document.getElementById('finalTendered').textContent = 'P' + tendered.toFixed(2);
            document.getElementById('finalChange').textContent = 'P' + (tendered - checkoutTotalAmount).toFixed(2);

            currentStep = 4;
            showStep(4);
            
            CartManager.clearCart();
            updateCartBadge();
            renderMiniCart();
            loadProducts();

            Toast.success('Order saved successfully!');
        } else {
            Toast.error(result.message || 'Failed to process order');
        }
    } catch (error) {
        console.error('Checkout error:', error);
        Toast.error('Error connecting to server. Please try again.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = 'Complete';
    }
}

function showExitConfirmation() {
    document.getElementById('exitConfirmModal').classList.remove('hidden');
}

function closeCheckoutModal() {
    document.getElementById('checkoutModal').classList.add('hidden');
    resetStepProgress();
    const progressLine = document.getElementById('progressLine');
    if (progressLine) {
        progressLine.style.width = '0%';
    }
}

// ==========================================
// Floating Cart / Mini Cart
// ==========================================
function initFloatingCart() {
    const floatingCartBtn = document.getElementById('floatingCartBtn');
    const miniCartPanel = document.getElementById('miniCartPanel');
    const btnCloseMiniCart = document.getElementById('btnCloseMiniCart');
    const btnClearCart = document.getElementById('btnClearCart');
    const btnMiniCartCheckout = document.getElementById('btnMiniCartCheckout');

    floatingCartBtn.addEventListener('click', function() {
        miniCartOpen = !miniCartOpen;
        if (miniCartOpen) {
            renderMiniCart();
            miniCartPanel.classList.remove('hidden');
        } else {
            miniCartPanel.classList.add('hidden');
        }
    });

    btnCloseMiniCart.addEventListener('click', function(e) {
        e.stopPropagation();
        miniCartOpen = false;
        miniCartPanel.classList.add('hidden');
    });

    miniCartPanel.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    btnClearCart.addEventListener('click', function() {
        Confirm.show('Clear all items from cart?', function() {
            CartManager.clearCart();
            renderMiniCart();
            updateCartBadge();
        });
    });

    btnMiniCartCheckout.addEventListener('click', function() {
        if (CartManager.getCart().length === 0) {
            Toast.warning('Your cart is empty!');
            return;
        }
        miniCartOpen = false;
        miniCartPanel.classList.add('hidden');
        openCheckoutModal();
    });

    document.addEventListener('click', function(e) {
        if (miniCartOpen && !floatingCartBtn.contains(e.target) && !miniCartPanel.contains(e.target)) {
            miniCartOpen = false;
            miniCartPanel.classList.add('hidden');
        }
    });
}

function updateCartBadge() {
    const cartBadge = document.getElementById('cartBadge');
    const totalItems = CartManager.getItemCount();
    if (totalItems > 0) {
        cartBadge.textContent = totalItems > 99 ? '99+' : totalItems;
        cartBadge.classList.remove('hidden');
        cartBadge.classList.add('flex');
    } else {
        cartBadge.classList.add('hidden');
        cartBadge.classList.remove('flex');
    }
}

function renderMiniCart() {
    const miniCartItems = document.getElementById('miniCartItems');
    const miniCartTotal = document.getElementById('miniCartTotal');
    const orderCart = CartManager.getCart();
    
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
                        <button type="button" class="mini-qty-btn w-6 h-6 flex items-center justify-center bg-gray-200 hover:bg-gray-300 text-gray-600 text-xs font-bold rounded" data-index="${index}" data-action="decrease">-</button>
                        <span class="text-sm text-gray-600 w-6 text-center">${item.quantity}</span>
                        <button type="button" class="mini-qty-btn w-6 h-6 flex items-center justify-center bg-gray-200 hover:bg-gray-300 text-gray-600 text-xs font-bold rounded" data-index="${index}" data-action="increase">+</button>
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

    document.querySelectorAll('.mini-qty-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const index = parseInt(this.dataset.index);
            const action = this.dataset.action;
            let cart = CartManager.getCart();
            
            if (action === 'increase') {
                cart[index].quantity++;
                cart[index].total = cart[index].price * cart[index].quantity;
                CartManager.saveCart(cart);
            } else if (action === 'decrease') {
                if (cart[index].quantity > 1) {
                    cart[index].quantity--;
                    cart[index].total = cart[index].price * cart[index].quantity;
                    CartManager.saveCart(cart);
                } else {
                    CartManager.removeByIndex(index);
                }
            }
            
            renderMiniCart();
            updateCartBadge();
        });
    });

    document.querySelectorAll('.mini-remove-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const index = parseInt(this.dataset.index);
            CartManager.removeByIndex(index);
            renderMiniCart();
            updateCartBadge();
        });
    });
}
