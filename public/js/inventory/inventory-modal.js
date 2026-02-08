/**
 * Inventory Modal Module refactored
 * Handles all modal operations for inventory
 */

/**
 * Safe toast helper for modals
 */
function modalToast(type, message, duration) {
    if (typeof window.showToast === 'function') {
        window.showToast(type, message, duration || 2000);
    } else if (typeof safeToast === 'function') {
        safeToast(type, message, duration || 2000);
    } else {
        console.log(`[${type.toUpperCase()}] ${message}`);
    }
}

const InventoryModal = {
    /**
     * Initialize all modal event handlers
     */
    init: function() {
        this.initTimeInputModal();
        this.initEditModal();
        this.initAddProductModal();
    },

    // ==========================================
    // Time Input Modal (Add Today's Inventory)
    // ==========================================
    
    /**
     * Initialize time input modal events
     */
    initTimeInputModal: function() {
        // Close Time Input Modal
        $('#timeInputModalClose, #timeInputModalCancel').on('click', function() {
            InventoryModal.closeTimeInputModal();
        });
    },

    /**
     * Open the time input modal with default values
     */
    openTimeInputModal: function() {
        $('#timeInputModal').removeClass('hidden');
        // Set default values to current time
        const now = new Date();
        const timeStr = now.toTimeString().slice(0, 5);
        $('#time_start').val(timeStr);
        $('#time_end').val(timeStr);
    },

    /**
     * Close the time input modal
     */
    closeTimeInputModal: function() {
        $('#timeInputModal').addClass('hidden');
        $('#timeInputForm')[0].reset();
    },

    /**
     * Validate time input form
     * @returns {boolean} True if valid, false otherwise
     */
    validateTimeInput: function() {
        const timeStart = $('#time_start').val();
        const timeEnd = $('#time_end').val();

        if (timeStart >= timeEnd) {
            modalToast('warning', 'End time must be after start time');
            return false;
        }
        return true;
    },

    /**
     * Get time input values
     * @returns {Object} Object with timeStart and timeEnd
     */
    getTimeInputValues: function() {
        return {
            timeStart: $('#time_start').val(),
            timeEnd: $('#time_end').val()
        };
    },

    // ==========================================
    // Edit Inventory Modal
    // ==========================================

    /**
     * Initialize edit modal events
     */
    initEditModal: function() {
        // Close Edit Modal
        $('#editInventoryModalClose, #editInventoryModalCancel').on('click', function() {
            InventoryModal.closeEditModal();
        });
    },

    /**
     * Open the edit modal with item data
     * @param {jQuery} row - The table row jQuery element
     */
    openEditModal: function(row) {
        const itemId = row.data('id');
        const productId = row.data('product-id') || row.closest('[data-product-id]').data('product-id');
        
        // Get current values from the row (adjusted for correct column indices)
        const productName = row.find('td:eq(2)').text(); // Items/Particulars column
        const beginningStock = row.find('td:eq(3)').text(); // Beginning Total column
        const pullOutQty = row.find('td:eq(4)').text(); // Pull Out Total column

        // Store item ID and populate modal
        $('#editItemId').val(itemId);
        $('#editProductName').text(productName);
        $('#editBeginningStock').val(beginningStock);
        $('#editPullOutQuantity').val(pullOutQty);

        // Show modal
        $('#editInventoryModal').removeClass('hidden');
    },

    /**
     * Close the edit modal
     */
    closeEditModal: function() {
        $('#editInventoryModal').addClass('hidden');
        $('#editInventoryForm')[0].reset();
    },

    /**
     * Validate edit form inputs
     * @returns {boolean} True if valid, false otherwise
     */
    validateEditForm: function() {
        const beginningStock = $('#editBeginningStock').val();
        const pullOutQuantity = $('#editPullOutQuantity').val();

        if (beginningStock < 0 || pullOutQuantity < 0) {
            modalToast('warning', 'Values cannot be negative', 2000);
            return false;
        }
        return true;
    },

    /**
     * Get edit form values
     * @returns {Object} Object with itemId, beginningStock, pullOutQuantity
     */
    getEditFormValues: function() {
        return {
            itemId: $('#editItemId').val(),
            beginningStock: $('#editBeginningStock').val(),
            pullOutQuantity: $('#editPullOutQuantity').val()
        };
    },

    // ==========================================
    // Add Product Modal
    // ==========================================

    /**
     * Initialize add product modal events
     */
    initAddProductModal: function() {
        // Close Add Product Modal
        $('#addProductModalClose, #addProductModalCancel').on('click', function() {
            InventoryModal.closeAddProductModal();
        });

        // Close button for empty state
        $('#btnCloseNoProducts').on('click', function() {
            InventoryModal.closeAddProductModal();
        });
    },

    /**
     * Open the add product modal
     */
    openAddProductModal: function() {
        $('#addProductModal').removeClass('hidden');
    },

    /**
     * Close the add product modal
     */
    closeAddProductModal: function() {
        $('#addProductModal').addClass('hidden');
        $('#addProductForm')[0].reset();
        // Reset all visibility states
        $('#addProductForm').show();
        $('#noProductsState').addClass('hidden');
        $('#noInventoryState').addClass('hidden');
    },

    /**
     * Show the no inventory state (need to create inventory first)
     */
    showNoInventoryState: function() {
        $('#addProductForm').hide();
        $('#noProductsState').addClass('hidden');
        $('#noInventoryState').removeClass('hidden');
        $('#addProductModal').removeClass('hidden');
    },

    /**
     * Populate the product select dropdown
     * @param {Array} products - Array of available products
     */
    populateProductSelect: function(products) {
        const select = $('#selectProduct');
        select.html('<option value="">Select a product</option>');
        
        // Hide all states first
        $('#noInventoryState').addClass('hidden');
        
        if (products && products.length > 0) {
            // Show form, hide empty state
            $('#addProductForm').show();
            $('#noProductsState').addClass('hidden');
            
            products.forEach(function(product) {
                const categoryLabel = product.category.charAt(0).toUpperCase() + product.category.slice(1);
                select.append(`<option value="${product.product_id}">[${categoryLabel}] ${product.product_name}</option>`);
            });
            $('#btnSubmitAddProduct').prop('disabled', false);
        } else {
            // Hide form, show empty state
            $('#addProductForm').hide();
            $('#noProductsState').removeClass('hidden');
        }
    },

    /**
     * Validate add product form
     * @returns {boolean} True if valid, false otherwise
     */
    validateAddProductForm: function() {
        const productId = $('#selectProduct').val();
        if (!productId) {
            modalToast('warning', 'Please select a product', 2000);
            return false;
        }
        return true;
    },

    /**
     * Get add product form values
     * @returns {Object} Object with productId and beginningStock
     */
    getAddProductFormValues: function() {
        return {
            productId: $('#selectProduct').val(),
            beginningStock: $('#addBeginningStock').val() || 0
        };
    }
};
