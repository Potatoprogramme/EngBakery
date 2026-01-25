/**
 * Inventory Table Module refactored
 * Handles DataTable initialization and rendering
 */

const InventoryTable = {
    dataTable: null,

    /**
     * Load inventory items into the table
     * @param {Array} items - Array of inventory items
     */
    load: function(items) {
        // Destroy existing DataTable first
        if (this.dataTable) {
            this.dataTable.destroy();
            this.dataTable = null;
        }

        let rows = '';
        if (items && items.length > 0) {
            items.forEach(function(item) {
                rows += InventoryTable.buildRow(item);
            });
        } else {
            rows = '<tr><td colspan="8" class="px-6 py-4 text-center text-gray-500">No inventory data available</td></tr>';
        }

        $('#materialsTableBody').html(rows);

        // Initialize DataTable - ONLY if we have data
        const tableElement = document.getElementById('selection-table');
        if (tableElement && typeof simpleDatatables !== 'undefined' && items && items.length > 0) {
            this.dataTable = new simpleDatatables.DataTable('#selection-table', {
                labels: {
                    placeholder: "Search inventory...",
                    perPage: "entries per page",
                    noRows: "No inventory data available",
                    noResults: "No results match your search",
                    info: "Showing {start} to {end} of {rows} entries"
                },
                perPage: 10,
                perPageSelect: [5, 10, 25, 50]
            });
        }
    },

    /**
     * Build a table row HTML for an inventory item
     * @param {Object} item - Inventory item data
     * @returns {string} HTML string for the row
     */
    buildRow: function(item) {
        // Calculate sales: beginning - ending - pull_out
        const sales = (parseInt(item.beginning_stock) || 0) - (parseInt(item.ending_stock) || 0) - (parseInt(item.pull_out_quantity) || 0);
        
        // Use appropriate price based on category
        const price = item.category === 'bread' && item.selling_price_per_piece > 0 
            ? item.selling_price_per_piece 
            : item.selling_price;
        const formattedPrice = '₱' + parseFloat(price || 0).toFixed(2);
        
        // Category badge color
        const categoryClass = item.category === 'bread' 
            ? 'bg-amber-100 text-amber-800' 
            : 'bg-blue-100 text-blue-800';
        const categoryLabel = item.category ? item.category.charAt(0).toUpperCase() + item.category.slice(1) : 'N/A';

        let row = '<tr class="hover:bg-neutral-secondary-soft cursor-pointer" data-date="' + (item.inventory_date || '') + '" data-id="' + item.item_id + '">';
        row += '<td class="px-6 py-4"><span class="px-2 py-1 text-xs font-medium rounded-full ' + categoryClass + '">' + categoryLabel + '</span></td>';
        row += '<td class="px-6 py-4 font-medium text-heading whitespace-nowrap">' + formattedPrice + '</td>';
        row += '<td class="px-6 py-4 font-medium text-heading whitespace-nowrap">' + (item.product_name || 'N/A') + '</td>';
        row += '<td class="px-6 py-4">' + (item.beginning_stock || 0) + '</td>';
        row += '<td class="px-6 py-4">' + (item.pull_out_quantity || 0) + '</td>';
        row += '<td class="px-6 py-4">' + (item.ending_stock || 0) + '</td>';
        row += '<td class="px-6 py-4">' + sales + '</td>';
        row += '<td class="px-6 py-4">';
        row += '<button class="text-amber-600 hover:text-amber-800 me-2 btn-edit" data-id="' + item.item_id + '" title="Edit"><i class="fas fa-edit"></i></button>';
        row += '<button class="text-red-600 hover:text-red-800 btn-delete" data-id="' + item.item_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
        row += '</td>';
        row += '</tr>';

        return row;
    },

    /**
     * Clear the table and show empty message
     */
    clear: function() {
        if (this.dataTable) {
            this.dataTable.destroy();
            this.dataTable = null;
        }
        $('#materialsTableBody').html('<tr><td colspan="8" class="px-6 py-4 text-center text-gray-500">No inventory data available</td></tr>');
    }
};
