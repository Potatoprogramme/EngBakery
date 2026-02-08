/**
 * Inventory Main Module
 * Main entry point for inventory functionality
 * 
 * Dependencies:
 * - inventory-api.js
 * - inventory-modal.js
 */

// Track if inventory exists for today
let inventoryExistsToday = false;

// Store all inventory items for mobile card rendering
let allInventoryItems = [];
let filteredItems = [];

/**
 * Safe toast notification helper
 */
function safeToast(type, message, duration) {
    if (typeof window.showToast === 'function') {
        window.showToast(type, message, duration || 2000);
    } else {
        console.log('[' + type.toUpperCase() + '] ' + message);
    }
}

$(document).ready(function() {
    // Display today's date
    displayTodayDate();

    // Check if inventory exists for today
    checkIfInventoryExists();

    // Bind event handlers
    bindEventHandlers();
});

/**
 * Display today's date
 */
function displayTodayDate() {
    var today = new Date();
    var dateString = today.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    $('#todayDate').text(dateString);
}

/**
 * Update the date and time display from inventory data
 */
function updateDateTime(data) {
    if (data.inventory_date) {
        var date = new Date(data.inventory_date);
        var dateString = date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        $('#todayDate').text(dateString);
    }

    if (data.time_start && data.time_end) {
        var formatTime = function(time) {
            var parts = time.split(':');
            var hour = parseInt(parts[0]);
            var minutes = parts[1];
            var ampm = hour >= 12 ? 'PM' : 'AM';
            var displayHour = hour % 12 || 12;
            return displayHour + ':' + minutes + ' ' + ampm;
        };

        var timeStart = formatTime(data.time_start);
        var timeEnd = formatTime(data.time_end);
        $('#timeRange').text(timeStart + ' - ' + timeEnd);
    }
}

/**
 * Check if inventory exists for today and load data
 */
function checkIfInventoryExists() {
    InventoryAPI.checkInventoryToday(
        function(response) {
            if (response.success) {
                inventoryExistsToday = true;
                updateDateTime(response.data);
                fetchAllStockitems();
            } else {
                inventoryExistsToday = false;
                safeToast('warning', response.message, 2000);
                loadInventory([]);
            }
        },
        function(xhr, status, error) {
            inventoryExistsToday = false;
            console.log('Error checking inventory: ' + error);
        }
    );
}

/**
 * Fetch all stock items and load into tables
 */
function fetchAllStockitems() {
    InventoryAPI.fetchAllStockItems(
        function(response) {
            console.log(response);
            if (response.success) {
                loadInventory(response.data);
                console.log('Inventory data:', response.data);
            } else {
                console.log("Error: " + response.error);
            }
        },
        function(xhr, status, error) {
            safeToast('danger', 'Error fetching inventory: ' + (xhr.responseJSON?.message || error), 2000);
            console.log(xhr.responseJSON);
        }
    );
}

// ==========================================
// Table Rendering (Category-based)
// ==========================================

/**
 * Load inventory items into category tables and mobile cards
 */
function loadInventory(items) {
    allInventoryItems = items || [];
    filteredItems = [].concat(allInventoryItems);

    // Separate items by category
    var bakeryItems = items ? items.filter(function(i) { return i.category === 'bakery'; }) : [];
    var drinksItems = items ? items.filter(function(i) { return i.category === 'drinks'; }) : [];
    var groceryItems = items ? items.filter(function(i) { return i.category === 'grocery'; }) : [];

    // Render each category table
    renderBakeryTable(bakeryItems);
    renderDrinksTable(drinksItems);
    renderGroceryTable(groceryItems);

    // Update totals
    updateGrandTotals(items || []);

    // Render mobile cards
    renderMobileCards();
}

function renderBakeryTable(items) {
    var rows = '';
    var totalQty = 0;

    if (items && items.length > 0) {
        items.forEach(function(item) {
            var price = item.selling_price_per_piece > 0 ? item.selling_price_per_piece : item.selling_price;
            var formattedPrice = '₱' + parseFloat(price || 0).toFixed(2);
            var beginning = parseInt(item.beginning_stock) || 0;
            var pullOut = parseInt(item.pull_out_quantity) || 0;
            var qtySold = parseInt(item.quantity_sold) || 0;
            var ending_stock = beginning - pullOut - qtySold;

            totalQty += qtySold;

            rows += '<tr class="hover:bg-gray-50 border-b border-gray-100">';
            rows += '<td class="px-6 py-2.5 text-sm text-gray-800">' + (item.product_name || 'N/A') + '</td>';
            rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + formattedPrice + '</td>';
            rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + (item.beginning_stock || 0) + '</td>';
            rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + (item.pull_out_quantity || 0) + '</td>';
            rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + ending_stock + '</td>';
            rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + qtySold + '</td>';
            rows += '<td class="px-6 py-3">';
            rows += '<button class="text-amber-600 hover:text-amber-800 me-2 btn-edit" data-id="' + item.item_id + '" data-category="bakery" title="Edit"><i class="fas fa-edit"></i></button>';
            rows += '<button class="text-red-600 hover:text-red-800 btn-delete" data-id="' + item.item_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
            rows += '</td>';
            rows += '</tr>';
        });
    } else {
        rows = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No bakery items in inventory</td></tr>';
    }

    $('#bakeryTableBody').html(rows);
    $('#bakeryTotalQty').text(totalQty);
}

function renderDrinksTable(items) {
    var rows = '';
    var totalQty = 0;

    if (items && items.length > 0) {
        items.forEach(function(item) {
            var formattedPrice = '₱' + parseFloat(item.selling_price || 0).toFixed(2);
            var qtySold = parseInt(item.quantity_sold) || 0;

            totalQty += qtySold;

            rows += '<tr class="hover:bg-gray-50 border-b border-gray-100">';
            rows += '<td class="px-6 py-2.5 text-sm text-gray-800">' + (item.product_name || 'N/A') + '</td>';
            rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + formattedPrice + '</td>';
            rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + qtySold + '</td>';
            rows += '<td class="px-6 py-3">';
            rows += '<button class="text-red-600 hover:text-red-800 btn-delete" data-id="' + item.item_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
            rows += '</td>';
            rows += '</tr>';
        });
    } else {
        rows = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No drinks in inventory</td></tr>';
    }

    $('#drinksTableBody').html(rows);
    $('#drinksTotalQty').text(totalQty);
}

function renderGroceryTable(items) {
    var rows = '';
    var totalQty = 0;

    if (items && items.length > 0) {
        items.forEach(function(item) {
            var formattedPrice = '₱' + parseFloat(item.selling_price || 0).toFixed(2);
            var beginning = parseInt(item.beginning_stock) || 0;
            var pullOut = parseInt(item.pull_out_quantity) || 0;
            var qtySold = parseInt(item.quantity_sold) || 0;
            var ending_stock = beginning - pullOut - qtySold;

            totalQty += qtySold;

            rows += '<tr class="hover:bg-gray-50 border-b border-gray-100">';
            rows += '<td class="px-6 py-2.5 text-sm text-gray-800">' + (item.product_name || 'N/A') + '</td>';
            rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + formattedPrice + '</td>';
            rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + beginning + '</td>';
            rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + pullOut + '</td>';
            rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + ending_stock + '</td>';
            rows += '<td class="px-6 py-2.5 text-sm text-gray-600">' + qtySold + '</td>';
            rows += '<td class="px-6 py-3">';
            rows += '<button class="text-amber-600 hover:text-amber-800 me-2 btn-edit" data-id="' + item.item_id + '" data-category="grocery" title="Edit"><i class="fas fa-edit"></i></button>';
            rows += '<button class="text-red-600 hover:text-red-800 btn-delete" data-id="' + item.item_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
            rows += '</td>';
            rows += '</tr>';
        });
    } else {
        rows = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No grocery items in inventory</td></tr>';
    }

    $('#groceryTableBody').html(rows);
    $('#groceryTotalQty').text(totalQty);
}

function updateGrandTotals(items) {
    var grandQty = 0;
    items.forEach(function(item) {
        grandQty += parseInt(item.quantity_sold) || 0;
    });
    $('#grandTotalQty').text(grandQty);
}

// ==========================================
// Mobile Card Rendering
// ==========================================

function renderMobileCards() {
    var bakeryItems = filteredItems.filter(function(i) { return i.category === 'bakery'; });
    var drinksItems = filteredItems.filter(function(i) { return i.category === 'drinks'; });
    var groceryItems = filteredItems.filter(function(i) { return i.category === 'grocery'; });

    // Bakery cards
    var bakeryCards = '';
    if (bakeryItems.length > 0) {
        bakeryItems.forEach(function(item) { bakeryCards += renderMobileCard(item, 'bakery'); });
    } else {
        bakeryCards = '<div class="bg-white rounded border border-gray-200 p-6 text-center text-gray-500 text-sm">No bakery items in inventory</div>';
    }
    $('#bakeryMobileCards').html(bakeryCards);

    // Drinks cards
    var drinksCards = '';
    if (drinksItems.length > 0) {
        drinksItems.forEach(function(item) { drinksCards += renderMobileCard(item, 'drinks'); });
    } else {
        drinksCards = '<div class="bg-white rounded border border-gray-200 p-6 text-center text-gray-500 text-sm">No drinks in inventory</div>';
    }
    $('#drinksMobileCards').html(drinksCards);

    // Grocery cards
    var groceryCards = '';
    if (groceryItems.length > 0) {
        groceryItems.forEach(function(item) { groceryCards += renderMobileCard(item, 'grocery'); });
    } else {
        groceryCards = '<div class="bg-white rounded border border-gray-200 p-6 text-center text-gray-500 text-sm">No grocery items in inventory</div>';
    }
    $('#groceryMobileCards').html(groceryCards);
}

function renderMobileCard(item, category) {
    var price = category === 'bakery' && item.selling_price_per_piece > 0 ?
        item.selling_price_per_piece : item.selling_price;
    var formattedPrice = '₱' + parseFloat(price || 0).toFixed(2);
    var isDrink = category === 'drinks';
    var ending_stock = isDrink ? null : (item.beginning_stock || 0) - (item.pull_out_quantity || 0) - (item.quantity_sold || 0);

    var borderColor = 'border-gray-200';
    if (category === 'bakery') borderColor = 'border-l-2 border-l-amber-400 border-gray-200';
    else if (category === 'drinks') borderColor = 'border-l-2 border-l-blue-400 border-gray-200';
    else if (category === 'grocery') borderColor = 'border-l-2 border-l-emerald-400 border-gray-200';

    var card = '<div class="bg-white rounded border ' + borderColor + ' p-3" data-id="' + item.item_id + '" data-product-id="' + item.product_id + '">';
    card += '  <div class="flex items-center justify-between mb-2">';
    card += '    <span class="text-sm text-gray-800">' + (item.product_name || 'N/A') + '</span>';
    card += '    <span class="text-sm font-medium text-gray-700">' + formattedPrice + '</span>';
    card += '  </div>';

    if (isDrink) {
        card += '  <div class="flex items-center justify-between text-xs text-gray-500 mb-2">';
        card += '    <span>Qty: <span class="text-gray-700 font-medium">' + (item.quantity_sold || 0) + '</span></span>';
        card += '    <span>Sales: <span class="text-gray-700 font-medium">₱' + (parseFloat(item.total_sales).toFixed(2) || 0) + '</span></span>';
        card += '  </div>';
    } else {
        card += '  <div class="flex items-center gap-3 text-xs text-gray-500 mb-2">';
        card += '    <span>Begin: <span class="text-gray-700">' + (item.beginning_stock || 0) + '</span></span>';
        card += '    <span>Out: <span class="text-gray-700">' + (item.pull_out_quantity || 0) + '</span></span>';
        card += '    <span>End: <span class="text-gray-700">' + ending_stock + '</span></span>';
        card += '    <span class="ml-auto">Sales: <span class="text-gray-700 font-medium">₱' + (parseFloat(item.total_sales).toFixed(2) || 0) + '</span></span>';
        card += '  </div>';
    }

    card += '  <div class="flex gap-2 pt-2 border-t border-gray-100">';
    if (!isDrink) {
        card += '    <button class="flex-1 text-xs text-gray-500 hover:text-amber-600 py-1 btn-edit" data-id="' + item.item_id + '">';
        card += '      <i class="fas fa-edit mr-1"></i>Edit';
        card += '    </button>';
    }
    card += '    <button class="flex-1 text-xs text-gray-500 hover:text-red-600 py-1 btn-delete" data-id="' + item.item_id + '">';
    card += '      <i class="fas fa-trash mr-1"></i>Delete';
    card += '    </button>';
    card += '  </div>';
    card += '</div>';

    return card;
}

// ==========================================
// Tab Switching
// ==========================================

function switchTab(tabName) {
    // Remove active state from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
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
    document.querySelectorAll('.tab-content').forEach(function(content) {
        content.classList.add('hidden');
    });

    // Show selected tab content
    var targetContent = document.getElementById(tabName + '-content');
    if (targetContent) {
        targetContent.classList.remove('hidden');
    }
}

// ==========================================
// Event Handlers
// ==========================================

function bindEventHandlers() {

    // Open Add Inventory Modal (Desktop & Mobile)
    $('#btnAddTodaysInventory, #btnAddTodaysInventoryMobile').on('click', function() {
        $('#timeInputModal').removeClass('hidden');
        $('#time_start').val('08:00');
        $('#time_end').val('17:00');
    });

    // Submit Time Input Form
    $('#timeInputForm').on('submit', function(e) {
        e.preventDefault();
        var timeStart = $('#time_start').val();
        var timeEnd = $('#time_end').val();

        if (timeStart >= timeEnd) {
            safeToast('warning', 'End time must be after start time');
            return;
        }

        $('#timeInputModal').addClass('hidden');
        addTodaysInventory(timeStart, timeEnd);
        $('#timeInputForm')[0].reset();
    });

    // Delete Today's Inventory - Open Confirm Modal
    $('#btnDeleteTodaysInventory, #btnDeleteTodaysInventoryMobile').on('click', function() {
        if (!inventoryExistsToday) {
            safeToast('warning', 'No inventory exists for today to delete.', 2000);
            return;
        }
        $('#deleteConfirmModal').removeClass('hidden');
    });

    // Close Delete Confirmation Modal
    $('#deleteConfirmModalClose, #deleteConfirmModalCancel').on('click', function() {
        $('#deleteConfirmModal').addClass('hidden');
    });

    // Confirm Delete
    $('#btnConfirmDelete').on('click', function() {
        $('#deleteConfirmModal').addClass('hidden');
        deleteTodaysInventory();
    });

    // Edit Inventory Item - Open Modal
    $(document).on('click', '.btn-edit', function() {
        var itemId = $(this).data('id');
        var item = allInventoryItems.find(function(i) { return i.item_id == itemId; });

        if (item) {
            $('#editItemId').val(itemId);
            $('#editProductName').text(item.product_name || 'N/A');
            $('#editBeginningStock').val(item.beginning_stock || 0);
            $('#editPullOutQuantity').val(item.pull_out_quantity || 0);
            $('#editInventoryModal').removeClass('hidden');
        } else {
            safeToast('error', 'Could not find item data', 2000);
        }
    });

    // Close Edit Modal
    $('#editInventoryModalClose, #editInventoryModalCancel').on('click', function() {
        $('#editInventoryModal').addClass('hidden');
        $('#editInventoryForm')[0].reset();
    });

    // Submit Edit Form
    $('#editInventoryForm').on('submit', function(e) {
        e.preventDefault();

        var itemId = $('#editItemId').val();
        var beginningStock = $('#editBeginningStock').val();
        var pullOutQuantity = $('#editPullOutQuantity').val();

        if (beginningStock < 0 || pullOutQuantity < 0) {
            safeToast('warning', 'Values cannot be negative', 2000);
            return;
        }

        InventoryAPI.updateStockItem(itemId, beginningStock, pullOutQuantity,
            function(response) {
                if (response.success) {
                    safeToast('success', response.message, 2000);
                    showDeductionToast(response.deduction);
                    $('#editInventoryModal').addClass('hidden');
                    $('#editInventoryForm')[0].reset();
                    fetchAllStockitems();
                } else {
                    safeToast('error', response.message, 2000);
                }
            },
            function(xhr, status, error) {
                safeToast('danger', 'Error updating inventory: ' + (xhr.responseJSON?.message || error), 2000);
            }
        );
    });

    // Delete Single Item
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        if (typeof Confirm !== 'undefined' && Confirm.delete) {
            Confirm.delete('Are you sure you want to delete this inventory item?', function() {
                deleteInventoryItem(id);
            });
        } else if (confirm('Are you sure you want to delete this inventory item?')) {
            deleteInventoryItem(id);
        }
    });

    // Add Product to Inventory - Open Modal
    $('#btnAddProductToInventory').on('click', function() {
        loadAvailableProducts();
        $('#deductionPreviewContainer').addClass('hidden');
        $('#deductionPreviewList').empty();
        $('#addProductModal').removeClass('hidden');
    });

    // Close Add Product Modal
    $('#addProductModalClose, #addProductModalCancel').on('click', function() {
        $('#addProductModal').addClass('hidden');
        $('#addProductForm')[0].reset();
        $('#deductionPreviewContainer').addClass('hidden');
    });

    // Live deduction preview when product or quantity changes
    var previewTimer = null;
    $('#selectProduct, #addBeginningStock').on('change input', function() {
        clearTimeout(previewTimer);
        previewTimer = setTimeout(function() {
            var productId = $('#selectProduct').val();
            var pieces = parseInt($('#addBeginningStock').val()) || 0;

            if (!productId || pieces <= 0) {
                $('#deductionPreviewContainer').addClass('hidden');
                return;
            }

            InventoryAPI.previewDeduction(productId, pieces,
                function(response) {
                    if (response.deductions && response.deductions.length > 0) {
                        renderDeductionPreview(response);
                        $('#deductionPreviewContainer').removeClass('hidden');
                    } else {
                        $('#deductionPreviewContainer').addClass('hidden');
                    }
                },
                function() {
                    $('#deductionPreviewContainer').addClass('hidden');
                }
            );
        }, 400);
    });

    // Submit Add Product Form
    $('#addProductForm').on('submit', function(e) {
        e.preventDefault();

        var productId = $('#selectProduct').val();
        var beginningStock = $('#addBeginningStock').val() || 0;

        if (!productId) {
            safeToast('warning', 'Please select a product', 2000);
            return;
        }

        InventoryAPI.addProductToInventory(productId, beginningStock,
            function(response) {
                if (response.success) {
                    safeToast('success', response.message, 2000);
                    showDeductionToast(response.deduction);
                    $('#addProductModal').addClass('hidden');
                    $('#addProductForm')[0].reset();
                    $('#deductionPreviewContainer').addClass('hidden');
                    fetchAllStockitems();
                } else {
                    safeToast('error', response.message, 2000);
                }
            },
            function(xhr, status, error) {
                safeToast('danger', 'Error adding product: ' + (xhr.responseJSON?.message || error), 2000);
            }
        );
    });

    // Mobile Search
    $('#mobileSearchInput').on('input', function() {
        var searchTerm = $(this).val().toLowerCase().trim();

        if (searchTerm === '') {
            filteredItems = [].concat(allInventoryItems);
        } else {
            filteredItems = allInventoryItems.filter(function(item) {
                return (item.product_name && item.product_name.toLowerCase().indexOf(searchTerm) !== -1) ||
                    (item.category && item.category.toLowerCase().indexOf(searchTerm) !== -1);
            });
        }

        renderMobileCards();
    });
}

// ==========================================
// Helper Functions
// ==========================================

function addTodaysInventory(time_start, time_end) {
    InventoryAPI.addTodaysInventory(time_start, time_end,
        function(response) {
            if (response.success) {
                safeToast('success', response.message, 2000);
                checkIfInventoryExists();
                fetchAllStockitems();
            } else {
                safeToast('error', response.message, 2000);
            }
        },
        function(xhr, status, error) {
            safeToast('danger', xhr.responseJSON?.message || 'An error occurred while adding inventory', 2000);
        }
    );
}

function deleteTodaysInventory() {
    InventoryAPI.deleteTodaysInventory(
        function(response) {
            if (response.success) {
                inventoryExistsToday = false;
                safeToast('success', response.message, 2000);
                $('#timeRange').text('--:-- - --:--');
                fetchAllStockitems();
            } else {
                safeToast('error', response.message, 2000);
            }
        },
        function(xhr, status, error) {
            safeToast('danger', xhr.responseJSON?.message || 'Error deleting inventory', 2000);
        }
    );
}

function deleteInventoryItem(id) {
    InventoryAPI.deleteItem(id,
        function(response) {
            if (response.success) {
                safeToast('success', 'Inventory item deleted successfully!', 2000);
                fetchAllStockitems();
            } else {
                safeToast('error', response.message, 3000);
            }
        },
        function(xhr, status, error) {
            safeToast('danger', xhr.responseJSON?.message || 'An error occurred while deleting inventory', 3000);
        }
    );
}

function loadAvailableProducts() {
    InventoryAPI.getAvailableProducts(
        function(response) {
            var select = $('#selectProduct');
            select.html('<option value="">-- Select a product --</option>');

            if (response.success && response.data.length > 0) {
                response.data.forEach(function(product) {
                    var categoryLabel = 'Unknown';
                    if (product.category === 'bakery') categoryLabel = 'Bakery';
                    else if (product.category === 'drinks') categoryLabel = 'Drinks';
                    else if (product.category === 'grocery') categoryLabel = 'Grocery';
                    else if (product.category === 'dough') categoryLabel = 'Dough';
                    else if (product.category) categoryLabel = product.category.charAt(0).toUpperCase() + product.category.slice(1);

                    select.append('<option value="' + product.product_id + '">[' + categoryLabel + '] ' + product.product_name + '</option>');
                });
                $('#noProductsMessage').addClass('hidden');
                $('#btnSubmitAddProduct').prop('disabled', false);
            } else {
                $('#noProductsMessage').removeClass('hidden');
                $('#btnSubmitAddProduct').prop('disabled', true);
            }
        },
        function(xhr, status, error) {
            safeToast('danger', 'Error loading products: ' + error, 2000);
        }
    );
}

// Deduction Helper Functions
// ==========================================
/**
 * Render deduction preview inside the add product modal
 */
function renderDeductionPreview(data) {
    var html = '';
    var deductions = data.deductions || [];

    deductions.forEach(function(d) {
        var statusClass = d.insufficient ? 'text-red-600' : 'text-gray-600';
        var statusIcon = d.insufficient ? '<i class="fas fa-exclamation-circle text-red-500 mr-1"></i>' : '';
        var fromLabel = d.from_combined ? ' <span class="text-gray-400">(from ' + d.from_combined + ')</span>' : '';

        html += '<div class="flex justify-between items-center py-0.5 ' + statusClass + '">'
            + '<span>' + statusIcon + d.material_name + fromLabel + '</span>'
            + '<span class="font-mono">' + parseFloat(d.deduct_amount).toFixed(2) + ' ' + d.unit + '</span>'
            + '</div>';
    });

    if (data.yields_needed) {
        html = '<div class="text-gray-500 mb-1">Yields needed: <strong>' + data.yields_needed + '</strong></div>' + html;
    }

    $('#deductionPreviewList').html(html);

    if (data.has_insufficient) {
        $('#deductionPreviewWarning').removeClass('hidden');
    } else {
        $('#deductionPreviewWarning').addClass('hidden');
    }
}

/**
 * Show a toast summarizing what raw materials were deducted
 */
function showDeductionToast(deduction) {
    if (!deduction || !deduction.success || !deduction.deductions || deduction.deductions.length === 0) {
        return;
    }

    var count = deduction.deductions.length;
    var msg = count + ' raw material' + (count > 1 ? 's' : '') + ' deducted for ' + deduction.pieces + ' pcs (' + deduction.yields_needed + ' yields)';

    if (deduction.has_insufficient) {
        safeToast('warning', msg + ' — some materials had insufficient stock', 4000);
    } else {
        safeToast('info', msg, 3000);
    }
}
