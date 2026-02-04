/**
 * Inventory Main Module refactored 
 * Main entry point for inventory functionality
 * 
 * Dependencies:
 * - inventory-api.js
 * - inventory-table.js
 * - inventory-modal.js
 */

// Track if inventory exists for today
let inventoryExists = false;

/**
 * Safe toast notification helper
 * @param {string} type - Toast type (success, error, warning, danger, info)
 * @param {string} message - Message to display
 * @param {number} duration - Duration in milliseconds
 */
function safeToast(type, message, duration) {
    if (typeof window.showToast === 'function') {
        window.showToast(type, message, duration || 2000);
    } else {
        console.log(`[${type.toUpperCase()}] ${message}`);
    }
}

$(document).ready(function() {
    // Initialize modals
    InventoryModal.init();
    
    // Display today's date
    displayTodayDate();
    
    // Check if inventory exists for today
    checkIfInventoryExists();
    
    // Bind event handlers
    bindEventHandlers();
});

/**
 * Display today's date in the date field
 */
function displayTodayDate() {
    const today = new Date();
    const dateString = today.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    $('#todayDate').text(dateString);
}

/**
 * Update the date and time display from inventory data
 * @param {Object} data - Inventory data with date and time
 */
function updateDateTime(data) {
    // Update date display
    if (data.inventory_date) {
        const date = new Date(data.inventory_date);
        const dateString = date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        $('#todayDate').text(dateString);
    }

    // Update time range display
    if (data.time_start && data.time_end) {
        const formatTime = (time) => {
            const [hours, minutes] = time.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const displayHour = hour % 12 || 12;
            return `${displayHour}:${minutes} ${ampm}`;
        };

        const timeStart = formatTime(data.time_start);
        const timeEnd = formatTime(data.time_end);
        $('#timeRange').text(`${timeStart} - ${timeEnd}`);
    }
}

/**
 * Check if inventory exists for today and load data
 */
function checkIfInventoryExists() {
    InventoryAPI.checkInventoryToday(
        function(response) {
            console.log('CheckInventoryToday response:', response);
            if (response.success) {
                inventoryExists = true;
                updateDateTime(response.data);
                fetchAllStockItems();
            } else {
                inventoryExists = false;
                safeToast('warning', response.message, 2000);
                InventoryTable.load([]);
            }
        },
        function(xhr, status, error) {
            console.error('Error checking inventory:', error);
            inventoryExists = false;
            InventoryTable.load([]);
        }
    );
}

/**
 * Fetch all stock items and load into table
 */
function fetchAllStockItems() {
    InventoryAPI.fetchAllStockItems(
        function(response) {
            if (response.success) {
                InventoryTable.load(response.data);
                console.log('Inventory data:', response.data);
            } else {
                console.log("Error: " + response.error);
            }
        },
        function(xhr, status, error) {
            safeToast('danger', 'Error fetching inventory: ' + (xhr.responseJSON?.message || error), 2000);
        }
    );
}

/**
 * Add today's inventory with time range
 * @param {string} timeStart - Start time
 * @param {string} timeEnd - End time
 */
function addTodaysInventory(timeStart, timeEnd) {
    InventoryAPI.addTodaysInventory(timeStart, timeEnd,
        function(response) {
            if (response.success) {
                inventoryExists = true;
                safeToast('success', response.message, 2000);
                checkIfInventoryExists();
                fetchAllStockItems();
            } else {
                safeToast('error', response.message, 2000);
            }
        },
        function(xhr, status, error) {
            safeToast('danger', 'Error adding inventory: ' + (xhr.responseJSON?.message || error), 2000);
        }
    );
}

/**
 * Delete today's entire inventory
 */
function deleteTodaysInventory() {
    InventoryAPI.deleteTodaysInventory(
        function(response) {
            if (response.success) {
                inventoryExists = false;
                safeToast('success', response.message, 2000);
                InventoryTable.clear();
                $('#timeRange').text('--:-- - --:--');
                fetchAllStockItems();
            } else {
                safeToast('error', response.message, 2000);
            }
        },
        function(xhr, status, error) {
            safeToast('danger', 'Error deleting inventory: ' + (xhr.responseJSON?.message || error), 2000);
        }
    );
}

/**
 * Bind all event handlers
 */
function bindEventHandlers() {
    // ==========================================
    // Add Today's Inventory
    // ==========================================
    
    // Open Add Inventory Modal (Desktop & Mobile)
    $('#btnAddTodaysInventory, #btnAddTodaysInventoryMobile').on('click', function() {
        InventoryModal.openTimeInputModal();
    });

    // Submit Time Input Form
    $('#timeInputForm').on('submit', function(e) {
        e.preventDefault();
        
        // Prevent double submission
        const submitBtn = $(this).find('button[type="submit"]');
        if (ButtonLoader.isLoading(submitBtn)) {
            return;
        }
        
        if (!InventoryModal.validateTimeInput()) {
            return;
        }
        
        const values = InventoryModal.getTimeInputValues();
        ButtonLoader.start(submitBtn, 'Creating...');
        
        InventoryAPI.addTodaysInventory(values.timeStart, values.timeEnd,
            function(response) {
                ButtonLoader.stop(submitBtn);
                if (response.success) {
                    inventoryExists = true;
                    safeToast('success', response.message, 2000);
                    InventoryModal.closeTimeInputModal();
                    checkIfInventoryExists();
                    fetchAllStockItems();
                } else {
                    safeToast('error', response.message, 2000);
                }
            },
            function(xhr, status, error) {
                ButtonLoader.stop(submitBtn);
                safeToast('danger', 'Error adding inventory: ' + (xhr.responseJSON?.message || error), 2000);
            }
        );
    });

    // ==========================================
    // Delete Today's Inventory
    // ==========================================
    
    $('#btnDeleteTodaysInventory, #btnDeleteTodaysInventoryMobile').on('click', function() {
        const btn = $(this);
        if (ButtonLoader.isLoading(btn)) {
            return;
        }
        
        if (confirm('Are you sure you want to delete today\'s entire inventory? This action cannot be undone.')) {
            ButtonLoader.start(btn, 'Deleting...');
            InventoryAPI.deleteTodaysInventory(
                function(response) {
                    ButtonLoader.stop(btn);
                    if (response.success) {
                        inventoryExists = false;
                        safeToast('success', response.message, 2000);
                        InventoryTable.clear();
                        $('#timeRange').text('--:-- - --:--');
                        fetchAllStockItems();
                    } else {
                        safeToast('error', response.message, 2000);
                    }
                },
                function(xhr, status, error) {
                    ButtonLoader.stop(btn);
                    safeToast('danger', 'Error deleting inventory: ' + (xhr.responseJSON?.message || error), 2000);
                }
            );
        }
    });

    // ==========================================
    // Edit Inventory Item
    // ==========================================
    
    // Open edit modal when clicking edit button
    $(document).on('click', '.btn-edit', function() {
        const row = $(this).closest('tr');
        InventoryModal.openEditModal(row);
    });

    // Submit edit form
    $('#editInventoryForm').on('submit', function(e) {
        e.preventDefault();
        
        // Prevent double submission
        const submitBtn = $(this).find('button[type="submit"]');
        if (ButtonLoader.isLoading(submitBtn)) {
            return;
        }
        
        if (!InventoryModal.validateEditForm()) {
            return;
        }
        
        const values = InventoryModal.getEditFormValues();
        ButtonLoader.start(submitBtn, 'Updating...');
        
        InventoryAPI.updateStockItem(
            values.itemId,
            values.beginningStock,
            values.pullOutQuantity,
            function(response) {
                ButtonLoader.stop(submitBtn);
                if (response.success) {
                    safeToast('success', response.message, 2000);
                    InventoryModal.closeEditModal();
                    fetchAllStockItems();
                } else {
                    safeToast('error', response.message, 2000);
                }
            },
            function(xhr, status, error) {
                ButtonLoader.stop(submitBtn);
                safeToast('danger', 'Error updating inventory: ' + (xhr.responseJSON?.message || error), 2000);
            }
        );
    });

    // ==========================================
    // Delete Single Item
    // ==========================================
    
    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        const btn = $(this);
        
        if (ButtonLoader.isLoading(btn)) {
            return;
        }
        
        if (confirm('Are you sure you want to delete this inventory record?')) {
            ButtonLoader.start(btn, '');
            InventoryAPI.deleteItem(id,
                function(response) {
                    ButtonLoader.stop(btn);
                    if (response.success) {
                        safeToast('success', 'Inventory deleted successfully!', 2000);
                        fetchAllStockItems();
                    } else {
                        safeToast('error', response.message, 2000);
                    }
                },
                function(xhr, status, error) {
                    ButtonLoader.stop(btn);
                    safeToast('danger', 'Error deleting inventory: ' + error, 2000);
                }
            );
        }
    });

    // ==========================================
    // Add Product to Inventory
    // ==========================================
    
    // Open add product modal
    $('#btnAddProductToInventory').on('click', function() {
        // Check if inventory exists first
        if (!inventoryExists) {
            InventoryModal.showNoInventoryState();
            return;
        }
        
        InventoryAPI.getAvailableProducts(
            function(response) {
                if (response.success) {
                    InventoryModal.populateProductSelect(response.data);
                }
                InventoryModal.openAddProductModal();
            },
            function(xhr, status, error) {
                safeToast('danger', 'Error loading products: ' + error, 2000);
            }
        );
    });

    // Button to go to Add Inventory from the no-inventory state
    $('#btnGoToAddInventory').on('click', function() {
        InventoryModal.closeAddProductModal();
        InventoryModal.openTimeInputModal();
    });

    // Close no inventory state
    $('#btnCloseNoInventory').on('click', function() {
        InventoryModal.closeAddProductModal();
    });

    // Submit add product form
    $('#addProductForm').on('submit', function(e) {
        e.preventDefault();
        
        // Prevent double submission
        const submitBtn = $('#btnSubmitAddProduct');
        if (ButtonLoader.isLoading(submitBtn)) {
            return;
        }
        
        if (!InventoryModal.validateAddProductForm()) {
            return;
        }
        
        const values = InventoryModal.getAddProductFormValues();
        ButtonLoader.start(submitBtn, 'Adding...');
        
        InventoryAPI.addProductToInventory(
            values.productId,
            values.beginningStock,
            function(response) {
                ButtonLoader.stop(submitBtn);
                if (response.success) {
                    safeToast('success', response.message, 2000);
                    InventoryModal.closeAddProductModal();
                    fetchAllStockItems();
                } else {
                    safeToast('error', response.message, 2000);
                }
            },
            function(xhr, status, error) {
                ButtonLoader.stop(submitBtn);
                safeToast('danger', 'Error adding product: ' + (xhr.responseJSON?.message || error), 2000);
            }
        );
    });

    // ==========================================
    // Filters (if needed in future)
    // ==========================================
    
    $('#apply-filters').on('click', function() {
        const dateFrom = $('#filter-date-from').val();
        const dateTo = $('#filter-date-to').val();

        $('table tbody tr').each(function() {
            const rowDate = $(this).data('date');
            let show = true;

            if (dateFrom && rowDate) {
                show = show && (rowDate >= dateFrom);
            }
            if (dateTo && rowDate) {
                show = show && (rowDate <= dateTo);
            }

            $(this).toggle(show);
        });
    });

    $('#reset-filters').on('click', function() {
        $('#filter-date-from').val('');
        $('#filter-date-to').val('');
        $('table tbody tr').show();
    });
}
