/**
 * Inventory API Module refactored
 * Handles all AJAX calls for inventory operations
 */

const InventoryAPI = {
    /**
     * Check if inventory exists for today
     * @param {Function} onSuccess - Callback on success
     * @param {Function} onError - Callback on error
     */
    checkInventoryToday: function(onSuccess, onError) {
        $.ajax({
            url: window.BASE_URL + 'Inventory/CheckInventoryToday',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (onSuccess) onSuccess(response);
            },
            error: function(xhr, status, error) {
                console.log('Error checking inventory: ' + error);
                if (onError) onError(xhr, status, error);
            }
        });
    },

    /**
     * Add today's inventory with time range
     * @param {string} timeStart - Start time
     * @param {string} timeEnd - End time
     * @param {Function} onSuccess - Callback on success
     * @param {Function} onError - Callback on error
     */
    addTodaysInventory: function(timeStart, timeEnd, onSuccess, onError) {
        $.ajax({
            url: window.BASE_URL + 'Inventory/AddTodaysInventory',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({ time_start: timeStart, time_end: timeEnd }),
            success: function(response) {
                if (onSuccess) onSuccess(response);
            },
            error: function(xhr, status, error) {
                if (onError) onError(xhr, status, error);
            }
        });
    },

    /**
     * Fetch all stock items for today's inventory
     * @param {Function} onSuccess - Callback on success
     * @param {Function} onError - Callback on error
     */
    fetchAllStockItems: function(onSuccess, onError) {
        $.ajax({
            url: window.BASE_URL + 'Inventory/FetchAllStockItems',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (onSuccess) onSuccess(response);
            },
            error: function(xhr, status, error) {
                if (onError) onError(xhr, status, error);
            }
        });
    },

    /**
     * Update a stock item
     * @param {number} itemId - Item ID to update
     * @param {number} beginningStock - Beginning stock value
     * @param {number} pullOutQuantity - Pull out quantity value
     * @param {Function} onSuccess - Callback on success
     * @param {Function} onError - Callback on error
     */
    updateStockItem: function(itemId, beginningStock, pullOutQuantity, onSuccess, onError) {
        $.ajax({
            url: window.BASE_URL + 'Inventory/UpdateStockItem/' + itemId,
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                beginning_stock: beginningStock,
                pull_out_quantity: pullOutQuantity
            }),
            success: function(response) {
                if (onSuccess) onSuccess(response);
            },
            error: function(xhr, status, error) {
                if (onError) onError(xhr, status, error);
            }
        });
    },

    /**
     * Delete today's inventory
     * @param {Function} onSuccess - Callback on success
     * @param {Function} onError - Callback on error
     */
    deleteTodaysInventory: function(onSuccess, onError) {
        const today = new Date().toISOString().split('T')[0];
        $.ajax({
            url: window.BASE_URL + 'Inventory/DeleteTodaysInventory',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({ date: today }),
            success: function(response) {
                if (onSuccess) onSuccess(response);
            },
            error: function(xhr, status, error) {
                if (onError) onError(xhr, status, error);
            }
        });
    },

    /**
     * Delete a single inventory item
     * @param {number} itemId - Item ID to delete
     * @param {Function} onSuccess - Callback on success
     * @param {Function} onError - Callback on error
     */
    deleteItem: function(itemId, onSuccess, onError) {
        $.ajax({
            url: window.BASE_URL + 'Inventory/Delete/' + itemId,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (onSuccess) onSuccess(response);
            },
            error: function(xhr, status, error) {
                if (onError) onError(xhr, status, error);
            }
        });
    },

    /**
     * Get available products not yet in inventory
     * @param {Function} onSuccess - Callback on success
     * @param {Function} onError - Callback on error
     */
    getAvailableProducts: function(onSuccess, onError) {
        $.ajax({
            url: window.BASE_URL + 'Inventory/GetAvailableProducts',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (onSuccess) onSuccess(response);
            },
            error: function(xhr, status, error) {
                if (onError) onError(xhr, status, error);
            }
        });
    },

    /**
     * Add a product to today's inventory
     * @param {number} productId - Product ID to add
     * @param {number} beginningStock - Initial stock value
     * @param {Function} onSuccess - Callback on success
     * @param {Function} onError - Callback on error
     */
    addProductToInventory: function(productId, beginningStock, onSuccess, onError) {
        $.ajax({
            url: window.BASE_URL + 'Inventory/AddProductToInventory',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                product_id: productId,
                beginning_stock: beginningStock
            }),
            success: function(response) {
                if (onSuccess) onSuccess(response);
            },
            error: function(xhr, status, error) {
                if (onError) onError(xhr, status, error);
            }
        });
    },

    /**
     * Preview raw material deductions for a product quantity
     * @param {number} productId - Product ID
     * @param {number} quantity - Number of pieces
     * @param {Function} onSuccess - Callback on success
     * @param {Function} onError - Callback on error
     */
    previewDeduction: function(productId, quantity, onSuccess, onError) {
        $.ajax({
            url: window.BASE_URL + 'Inventory/PreviewDeduction',
            type: 'GET',
            dataType: 'json',
            data: { product_id: productId, quantity: quantity },
            success: function(response) {
                if (onSuccess) onSuccess(response);
            },
            error: function(xhr, status, error) {
                if (onError) onError(xhr, status, error);
            }
        });
    }
};