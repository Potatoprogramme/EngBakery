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
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </li>
                    <li>
                        <a href="<?= base_url('Inventory') ?>" class="hover:text-primary">Inventory</a>
                    </li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </li>
                    <li class="text-gray-700">Add Inventory</li>
                </ol>
            </nav>
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Add Today's Inventory</h2>
                    <div class="flex flex-wrap gap-2">
                        <!-- Enable Export Button -->
                        <!-- <button type="button" id="btnExport"
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            Export
                        </button> -->
                        <!-- Disable Export Button -->
                        <button type="button" id="btnExport" disabled
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            Export
                        </button>
                    </div>
                </div>
                
                <!-- Divider line -->
                <div class="border-t border-gray-200 my-4"></div>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        <div class="col-span-2 sm:col-span-1">
                            <label for="inventory_date" class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                            <input type="date" name="inventory_date" id="inventory_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label for="time_beginning" class="block text-sm font-medium text-gray-700 mb-1">Time Beginning <span class="text-red-500">*</span></label>
                            <input type="time" name="time_beginning" id="time_beginning" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label for="time_ending" class="block text-sm font-medium text-gray-700 mb-1">Time Ending <span class="text-red-500">*</span></label>
                            <input type="time" name="time_ending" id="time_ending" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="flex justify-end sm:mt-4">
                        <button type="button" id="btnSaveInventory" class="hidden sm:flex w-full items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">Save</button></div>
                    </div>
                </div>

                <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                    <!-- Filters section -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full">
                            <div class="flex items-center gap-2 w-full">
                                <label for="filter-category" class="sr-only">Product Category</label>
                                <select id="filter-category" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                                    <option value="">All Categories</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex gap-2 justify-center sm:justify-end">
                            <button id="apply-filters" type="button" class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">Apply</button>
                            <button id="reset-filters" type="button" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">Reset</button>
                        </div>
                    </div>
                </div>

            <!-- Floating Save button for mobile -->
            <div class="fixed bottom-6 left-0 right-0 flex justify-center z-30 sm:hidden">
                <button type="button" id="btnSaveInventoryMobile" class="w-5/6 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    Save
                </button>
            </div>

            <div class="p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 sm:mb-0">
                <table id="selection-table" class="min-w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Particular/Item Name
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Price
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Beginning
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Pull Out
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Ending
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Sales
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="materialsTableBody">
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 640px) {
            .datatable-top, .datatable-bottom {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                gap: 0.3rem !important;
                padding: 0.3rem 0;
            }
            .datatable-dropdown, .datatable-search, .datatable-info, .datatable-pagination {
                float: none !important;
                width: 100% !important;
                text-align: center !important;
                display: flex !important;
                justify-content: center !important;
                margin: 0 !important;
            }
            .datatable-search {
                margin-top: 0.5rem !important;
            }
            .datatable-pagination ul {
                justify-content: center !important;
            }
        }
    </style>

    <script>
    $(document).ready(function() {
        const baseUrl = '<?= site_url() ?>';
        let dataTable = null;

        // Load data on page load
        loadInventory();

        // Open Add Inventory Modal (Desktop & Mobile)
        $('#btnAddMaterial, #btnAddMaterialMobile').on('click', function() {
            $('#addMaterialModal').removeClass('hidden');
        });

        // Close Inventory Modal
        $('#btnCloseModal, #btnCancelAdd').on('click', function() {
            closeModal();
        });

        // Close modal on outside click
        $('#addMaterialModal').on('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        function closeModal() {
            $('#addMaterialModal').addClass('hidden');
            $('#addMaterialForm')[0].reset();
        }

        // Load inventory via AJAX with sample data
        function loadInventory() {
            // Sample data for demonstration
            const sampleData = [
                { id: 1, item_name: 'Pandesal', price: 5.00, beginning: 100, pullout: 0, ending: 75, sales: 15 },
                { id: 2, item_name: 'Ensaymada', price: 15.00, beginning: 50, pullout: 5, ending: 35, sales: 10 },
                { id: 3, item_name: 'Spanish Bread', price: 8.00, beginning: 80, pullout: 8, ending: 52, sales: 20 },
                { id: 4, item_name: 'Cheese Roll', price: 12.00, beginning: 60, pullout: 6, ending: 44, sales: 10 },
                { id: 5, item_name: 'Ube Bread', price: 10.00, beginning: 45, pullout: 3, ending: 30, sales: 12 }
            ];

            // Destroy existing DataTable first
            if (dataTable) {
                dataTable.destroy();
                dataTable = null;
            }

            let rows = '';
            sampleData.forEach(function(item) {
                rows += '<tr class="hover:bg-neutral-secondary-soft" data-id="' + item.id + '">';
                rows += '<td class="px-6 py-4 font-medium text-heading whitespace-nowrap">' + item.item_name + '</td>';
                rows += '<td class="px-6 py-4">₱' + parseFloat(item.price).toFixed(2) + '</td>';
                rows += '<td class="px-6 py-4"><div class="flex items-center gap-1"><button type="button" class="btn-minus w-7 h-7 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded text-gray-700" data-target="beginning" data-id="' + item.id + '"><i class="fas fa-minus text-xs"></i></button><input type="number" class="w-16 px-1 py-1 border border-gray-300 rounded text-center input-beginning" data-id="' + item.id + '" value="' + item.beginning + '" min="0" step="0.00001"><button type="button" class="btn-plus w-7 h-7 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded text-gray-700" data-target="beginning" data-id="' + item.id + '"><i class="fas fa-plus text-xs"></i></button></div></td>';
                rows += '<td class="px-6 py-4"><div class="flex items-center gap-1"><button type="button" class="btn-minus w-7 h-7 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded text-gray-700" data-target="pullout" data-id="' + item.id + '"><i class="fas fa-minus text-xs"></i></button><input type="number" class="w-16 px-1 py-1 border border-gray-300 rounded text-center input-pullout" data-id="' + item.id + '" value="' + item.pullout + '" min="0" step="0.00001"><button type="button" class="btn-plus w-7 h-7 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded text-gray-700" data-target="pullout" data-id="' + item.id + '"><i class="fas fa-plus text-xs"></i></button></div></td>';
                rows += '<td class="px-6 py-4"><div class="flex items-center gap-1"><button type="button" class="btn-minus w-7 h-7 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded text-gray-700" data-target="ending" data-id="' + item.id + '"><i class="fas fa-minus text-xs"></i></button><input type="number" class="w-16 px-1 py-1 border border-gray-300 rounded text-center input-ending" data-id="' + item.id + '" value="' + item.ending + '" min="0" step="0.00001"><button type="button" class="btn-plus w-7 h-7 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded text-gray-700" data-target="ending" data-id="' + item.id + '"><i class="fas fa-plus text-xs"></i></button></div></td>';
                rows += '<td class="px-6 py-4 sales-value" data-id="' + item.id + '">' + item.sales + '</td>';
                rows += '</tr>';
            });
            $('#materialsTableBody').html(rows);

            // Initialize DataTable with custom labels
            const tableElement = document.getElementById('selection-table');
            if (tableElement && typeof simpleDatatables !== 'undefined') {
                dataTable = new simpleDatatables.DataTable('#selection-table', {
                    labels: {
                        placeholder: "Search items...",
                        perPage: "entries per page",
                        noRows: "No inventory data available",
                        noResults: "No results match your search",
                        info: "Showing {start} to {end} of {rows} entries"
                    },
                    perPage: 10,
                    perPageSelect: [5, 10, 25, 50]
                });
            }

            // Auto-calculate sales when Beginning, Pull Out, or Ending changes
            $(document).on('input', '.input-beginning, .input-pullout, .input-ending', function() {
                const row = $(this).closest('tr');
                const beginning = parseInt(row.find('.input-beginning').val()) || 0;
                const pullout = parseInt(row.find('.input-pullout').val()) || 0;
                const ending = parseInt(row.find('.input-ending').val()) || 0;
                
                // Sales = Beginning - Pull Out - Ending
                const sales = beginning - pullout - ending;
                row.find('.sales-value').text(sales >= 0 ? sales : 0);
            });

            // Plus button handler
            $(document).on('click', '.btn-plus', function() {
                const target = $(this).data('target');
                const row = $(this).closest('tr');
                const input = row.find('.input-' + target);
                let value = parseInt(input.val()) || 0;
                input.val(value + 1).trigger('input');
            });

            // Minus button handler
            $(document).on('click', '.btn-minus', function() {
                const target = $(this).data('target');
                const row = $(this).closest('tr');
                const input = row.find('.input-' + target);
                let value = parseInt(input.val()) || 0;
                if (value > 0) {
                    input.val(value - 1).trigger('input');
                }
            });
        }

        // Submit Add Inventory Form via AJAX
        $('#addMaterialForm').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                // Add your inventory form fields here
            };

            $.ajax({
                url: baseUrl + 'Inventory/Add',
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Inventory added successfully!');
                        closeModal();
                        loadInventory();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error adding inventory: ' + error);
                }
            });
        });

        // Delete Inventory - removed since no Actions column

        // Apply Filter
        $('#apply-filters').on('click', function() {
            const category = $('#filter-category').val();
            
            $('table tbody tr').each(function() {
                // Filter logic can be added here if needed
                $(this).show();
            });
        });

        // Reset Filter
        $('#reset-filters').on('click', function() {
            $('#filter-category').val('');
            $('table tbody tr').show();
        });
    });
    </script>
