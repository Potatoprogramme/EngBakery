/**
 * Global Cart Manager - Persistent cart across pages using localStorage
 * Include this script on any page that needs cart functionality
 */
const CartManager = {
    STORAGE_KEY: 'engbakery_order_cart',
    
    // Get cart from localStorage
    getCart: function() {
        try {
            const cart = localStorage.getItem(this.STORAGE_KEY);
            return cart ? JSON.parse(cart) : [];
        } catch (e) {
            console.error('Error reading cart:', e);
            return [];
        }
    },
    
    // Save cart to localStorage
    saveCart: function(cart) {
        try {
            localStorage.setItem(this.STORAGE_KEY, JSON.stringify(cart));
        } catch (e) {
            console.error('Error saving cart:', e);
        }
    },
    
    // Add item to cart - each add is a SEPARATE line item (per product order)
    addItem: function(productId, name, price, quantity = 1) {
        const cart = this.getCart();
        // Each addition is a new line item (NOT aggregated)
        cart.push({
            cart_item_id: Date.now() + '_' + Math.random().toString(36).substr(2, 9),
            product_id: productId,
            name: name,
            price: parseFloat(price),
            quantity: parseInt(quantity),
            total: parseFloat(price) * parseInt(quantity)
        });
        this.saveCart(cart);
        return true;
    },
    
    // Remove item by cart_item_id
    removeItem: function(cartItemId) {
        let cart = this.getCart();
        cart = cart.filter(item => item.cart_item_id !== cartItemId);
        this.saveCart(cart);
    },
    
    // Remove item by index
    removeByIndex: function(index) {
        let cart = this.getCart();
        if (index >= 0 && index < cart.length) {
            cart.splice(index, 1);
            this.saveCart(cart);
        }
    },
    
    // Update item quantity by cart_item_id
    updateQuantity: function(cartItemId, newQuantity) {
        const cart = this.getCart();
        const item = cart.find(i => i.cart_item_id === cartItemId);
        if (item) {
            item.quantity = parseInt(newQuantity);
            item.total = item.price * item.quantity;
            if (item.quantity <= 0) {
                this.removeItem(cartItemId);
            } else {
                this.saveCart(cart);
            }
        }
    },
    
    // Update item by index
    updateByIndex: function(index, newQuantity) {
        let cart = this.getCart();
        if (index >= 0 && index < cart.length) {
            cart[index].quantity = parseInt(newQuantity);
            cart[index].total = cart[index].price * cart[index].quantity;
            if (cart[index].quantity <= 0) {
                cart.splice(index, 1);
            }
            this.saveCart(cart);
        }
    },
    
    // Clear entire cart
    clearCart: function() {
        localStorage.removeItem(this.STORAGE_KEY);
    },
    
    // Get cart total
    getTotal: function() {
        const cart = this.getCart();
        return cart.reduce((sum, item) => sum + item.total, 0);
    },
    
    // Get total items count
    getItemCount: function() {
        const cart = this.getCart();
        return cart.reduce((sum, item) => sum + item.quantity, 0);
    }
};

// Make globally available
window.CartManager = CartManager;
