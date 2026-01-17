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
                    <li class="text-gray-700">Inventory</li>
                </ol>
            </nav>
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Daily Inventory Lists</h2>
                    <div class="flex flex-wrap gap-2">
                        <a href="<?= base_url('Inventory/AddInventory') ?>" class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            Add Today's Inventory
                        </a>
                    </div>
                </div>
                
                <!-- Divider line -->
                <div class="border-t border-gray-200 my-4"></div>
                
                <!-- Filters section -->
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <!-- Date Filters -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 flex-1">
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <label for="filter-date-from" class="text-sm text-gray-600 whitespace-nowrap w-12 sm:w-auto">From:</label>
                            <input type="date" id="filter-date-from" class="flex-1 sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                            <label for="filter-date-to" class="text-sm text-gray-600 whitespace-nowrap w-12 sm:w-auto">To:</label>
                            <input type="date" id="filter-date-to" class="flex-1 sm:w-40 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                        </div>
                    </div>
                    <!-- Filter Buttons -->
                    <div class="flex gap-2 sm:justify-end">
                        <button id="apply-filters" type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">Apply</button>
                        <button id="reset-filters" type="button" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Floating Add Inventory button for mobile -->
            <div class="fixed bottom-6 left-0 right-0 flex justify-center z-30 sm:hidden">
                <a href="<?= base_url('Inventory/AddInventory') ?>" class="w-5/6 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    Add Inventory
                </a>  
            </div>
            <div class="p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 sm:mb-0">
                <table id="selection-table" class="min-w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Inventory Date
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Beginning Total
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Pull Out Total
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Ending Total
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
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">Actions</span>
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

    <!-- jQuery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a89dedcb22.js" crossorigin="anonymous"></script>
    <!-- Simple DataTables -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

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

        // Load inventory via AJAX
        function loadInventory() {
            $.ajax({
                url: baseUrl + 'Inventory/GetAll',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Destroy existing DataTable first
                    if (dataTable) {
                        dataTable.destroy();
                        dataTable = null;
                    }

                    let rows = '';
                    if (response.success && response.data && response.data.length > 0) {
                        response.data.forEach(function(inv) {
                            rows += '<tr class="hover:bg-neutral-secondary-soft cursor-pointer" data-date="' + (inv.inventory_date || '') + '">';
                            rows += '<td class="px-6 py-4 font-medium text-heading whitespace-nowrap">' + inv.inventory_date + '</td>';
                            rows += '<td class="px-6 py-4">' + parseFloat(inv.beginning_total || 0).toFixed(2) + '</td>';
                            rows += '<td class="px-6 py-4">' + parseFloat(inv.pullout_total || 0).toFixed(2) + '</td>';
                            rows += '<td class="px-6 py-4">' + parseFloat(inv.ending_total || 0).toFixed(2) + '</td>';
                            rows += '<td class="px-6 py-4">' + parseFloat(inv.sales || 0).toFixed(2) + '</td>';
                            rows += '<td class="px-6 py-4">';
                            rows += '<button class="text-blue-600 hover:text-blue-800 me-2 btn-view" data-id="' + inv.inventory_id + '" title="View"><i class="fas fa-eye"></i></button>';
                            rows += '<button class="text-red-600 hover:text-red-800 btn-delete" data-id="' + inv.inventory_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
                            rows += '</td>';
                            rows += '</tr>';
                        });
                    }
                    $('#materialsTableBody').html(rows);

                    // Initialize DataTable with custom labels
                    const tableElement = document.getElementById('selection-table');
                    if (tableElement && typeof simpleDatatables !== 'undefined') {
                        dataTable = new simpleDatatables.DataTable('#selection-table', {
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
                error: function(xhr, status, error) {
                    console.log('Error loading inventory: ' + error);
                    // Still initialize DataTable on error to show controls
                    if (dataTable) {
                        dataTable.destroy();
                        dataTable = null;
                    }
                    $('#materialsTableBody').html('');
                    const tableElement = document.getElementById('selection-table');
                    if (tableElement && typeof simpleDatatables !== 'undefined') {
                        dataTable = new simpleDatatables.DataTable('#selection-table', {
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

        // Delete Inventory
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this inventory record?')) {
                $.ajax({
                    url: baseUrl + 'Inventory/Delete/' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Inventory deleted successfully!');
                            loadInventory();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error deleting inventory: ' + error);
                    }
                });
            }
        });

        // Apply Filter
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
                
                if (show) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Reset Filter
        $('#reset-filters').on('click', function() {
            $('#filter-date-from').val('');
            $('#filter-date-to').val('');
            $('table tbody tr').show();
        });
    });
    </script>
