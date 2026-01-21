<!-- Floating Cart Component - Include this in any page that needs the cart -->
<!-- Include CartManager JS first: <script src="<?= base_url('js/cart-manager.js') ?>"></script> -->

<div id="floatingCartContainer" class="fixed bottom-6 right-6 z-40">
    <!-- Mini Cart Panel (Hidden by default) -->
    <div id="miniCartPanel" class="hidden absolute bottom-20 right-0 w-80 sm:w-96 bg-white border border-gray-200 shadow-2xl max-h-[70vh] overflow-hidden rounded-lg">
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
                <button type="button" id="btnClearCartGlobal" class="flex-1 px-3 py-2 text-sm font-medium text-gray-600 bg-gray-200 hover:bg-gray-300 transition-colors rounded">
                    Clear
                </button>
                <a href="<?= base_url('Order') ?>" id="btnGoToOrder" class="flex-[2] px-4 py-2 text-sm font-bold text-white bg-primary hover:bg-secondary transition-colors text-center rounded">
                    Go to Order
                </a>
            </div>
        </div>
    </div>
    
    <!-- Floating Cart Button -->
    <button type="button" id="floatingCartBtn" class="relative w-16 h-16 bg-primary hover:bg-secondary text-white rounded-full shadow-lg hover:shadow-xl transition-all flex items-center justify-center">
        <i class="fas fa-shopping-cart text-xl"></i>
        <span id="cartBadge" class="hidden absolute -top-1 -right-1 w-6 h-6 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">0</span>
    </button>
</div>

<script>
// FloatingCart UI - uses global CartManager from cart-manager.js
(function() {
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        // Update cart badge count
        function updateCartBadgeGlobal() {
            const badge = document.getElementById('cartBadge');
            if (badge && typeof CartManager !== 'undefined') {
                const itemCount = CartManager.getItemCount();
                if (itemCount > 0) {
                    badge.textContent = itemCount > 99 ? '99+' : itemCount;
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');
                } else {
                    badge.classList.add('hidden');
                    badge.classList.remove('flex');
                }
            }
        }
        
        // Render mini cart items
        function renderMiniCartGlobal() {
            const miniCartItems = document.getElementById('miniCartItems');
            const miniCartTotal = document.getElementById('miniCartTotal');
            
            if (!miniCartItems || typeof CartManager === 'undefined') return;
            
            const cart = CartManager.getCart();
            
            if (cart.length === 0) {
                miniCartItems.innerHTML = `
                    <div class="text-center py-6 text-gray-500">
                        <i class="fas fa-shopping-cart text-3xl mb-2"></i>
                        <p class="text-sm">Your cart is empty</p>
                    </div>
                `;
                if (miniCartTotal) miniCartTotal.textContent = 'P0.00';
                return;
            }
            
            let total = 0;
            let html = '';
            
            cart.forEach((item, index) => {
                total += item.total;
                html += `
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 text-sm truncate">${item.name}</p>
                            <p class="text-xs text-gray-500">P${item.price.toFixed(2)} x ${item.quantity}</p>
                        </div>
                        <div class="flex items-center gap-2 ml-2">
                            <span class="font-semibold text-primary text-sm">P${item.total.toFixed(2)}</span>
                            <button type="button" class="cart-remove-btn-global text-red-400 hover:text-red-600 p-1" data-index="${index}">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            
            miniCartItems.innerHTML = html;
            if (miniCartTotal) miniCartTotal.textContent = 'P' + total.toFixed(2);
            
            // Attach remove handlers
            document.querySelectorAll('.cart-remove-btn-global').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const index = parseInt(this.getAttribute('data-index'));
                    CartManager.removeByIndex(index);
                    renderMiniCartGlobal();
                    updateCartBadgeGlobal();
                });
            });
        }
        
        // Initialize
        updateCartBadgeGlobal();
        
        // Toggle mini cart panel
        const floatingCartBtn = document.getElementById('floatingCartBtn');
        const miniCartPanel = document.getElementById('miniCartPanel');
        const btnCloseMiniCart = document.getElementById('btnCloseMiniCart');
        
        if (floatingCartBtn && miniCartPanel) {
            floatingCartBtn.addEventListener('click', function() {
                renderMiniCartGlobal();
                miniCartPanel.classList.toggle('hidden');
            });
        }
        
        if (btnCloseMiniCart && miniCartPanel) {
            btnCloseMiniCart.addEventListener('click', function() {
                miniCartPanel.classList.add('hidden');
            });
        }
        
        // Clear cart button
        const btnClearCartGlobal = document.getElementById('btnClearCartGlobal');
        if (btnClearCartGlobal) {
            btnClearCartGlobal.addEventListener('click', function() {
                if (confirm('Clear all items from cart?')) {
                    CartManager.clearCart();
                    renderMiniCartGlobal();
                    updateCartBadgeGlobal();
                }
            });
        }
        
        // Close mini cart when clicking outside
        document.addEventListener('click', function(e) {
            if (miniCartPanel && !miniCartPanel.classList.contains('hidden')) {
                const container = document.getElementById('floatingCartContainer');
                if (container && !container.contains(e.target)) {
                    miniCartPanel.classList.add('hidden');
                }
            }
        });
    });
})();
</script>
