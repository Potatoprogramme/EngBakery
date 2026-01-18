/**
 * Raw Materials Page Handler
 * Handles CRUD operations for raw materials
 */
$(document).ready(function() {
    const baseUrl = window.BASE_URL || '/';
    let dataTable = null;
    let materialNameExists = false;
    let checkNameTimeout = null;

    // Unit conversion mappings (base units: grams, ml, pcs)
    const unitConversions = {
        'grams': { largeUnit: 'kg', factor: 1000 },
        'ml': { largeUnit: 'liters', factor: 1000 },
        'pcs': null // no conversion for pieces
    };

    // Initialize Category Modal Component
    CategoryModal.init({
        baseUrl: baseUrl,
        onCategoryChange: function() {
            loadCategories();
            loadFilterCategories();
        }
    });

    // Load data on page load
    loadMaterials();
    loadFilterCategories();

    // Real-time material name validation
    $('#material_name').on('input', function() {
        const materialName = $(this).val().trim();
        const materialId = $('#edit_material_id').val();

        // Clear previous timeout
        if (checkNameTimeout) clearTimeout(checkNameTimeout);

        if (materialName.length < 2) {
            $('#material_name_error').addClass('hidden');
            $('#material_name').removeClass('border-red-500').addClass('border-gray-300');
            materialNameExists = false;
            return;
        }

        // Debounce the check
        checkNameTimeout = setTimeout(function() {
            $.ajax({
                url: baseUrl + 'RawMaterials/CheckMaterialName',
                type: 'POST',
                data: JSON.stringify({
                    material_name: materialName,
                    material_id: materialId
                }),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        $('#material_name_error').removeClass('hidden');
                        $('#material_name').removeClass('border-gray-300').addClass('border-red-500');
                        materialNameExists = true;
                    } else {
                        $('#material_name_error').addClass('hidden');
                        $('#material_name').removeClass('border-red-500').addClass('border-gray-300');
                        materialNameExists = false;
                    }
                }
            });
        }, 300);
    });

    // Open Add Material Modal (Desktop & Mobile)
    $('#btnAddMaterial, #btnAddMaterialMobile').on('click', function() {
        $('#addMaterialModal').removeClass('hidden');
        loadCategories();
    });

    // Close Material Modal
    $('#btnCloseModal, #btnCancelAdd').on('click', function() {
        closeModal();
    });

    function closeModal() {
        $('#addMaterialModal').addClass('hidden');
        $('#addMaterialForm')[0].reset();
        $('#edit_material_id').val('');
        $('#modalTitle').text('Add Raw Material');
        $('#material_name_error').addClass('hidden');
        $('#material_name').removeClass('border-red-500').addClass('border-gray-300');
        materialNameExists = false;
        $('#btnSaveMaterial').text('Save');
        
        // Reset calculated displays
        $('#cost_per_unit').val('0.000');
        $('#cost_unit_label').text('per unit');
        $('#converted_cost').val('0.00');
        $('#converted_cost_wrapper').addClass('hidden');
        
        // Reset converted Quantity display
        $('#converted_quantity').val('0');
        $('#converted_qty_wrapper').removeClass('hidden');
    }

    // Calculate cost per unit with conversions
    function updateCostCalculations() {
        const qty = parseFloat($('#material_quantity').val()) || 0;
        const cost = parseFloat($('#total_cost').val()) || 0;
        const unit = $('#unit').val();
        const conversion = unitConversions[unit];

        // Set base unit label
        $('#cost_unit_label').text(unit === 'pcs' ? 'per pc' : 'per ' + (unit === 'grams' ? 'gram' : unit));

        // Calculate cost per base unit (grams, ml, pcs)
        const perUnit = qty > 0 ? (cost / qty) : 0;
        $('#cost_per_unit').val(perUnit.toFixed(3));

        // Update converted quantity display
        if (unit === 'pcs') {
            $('#converted_qty_wrapper').addClass('hidden');
            $('#converted_cost_wrapper').addClass('hidden');
        } else {
            $('#converted_qty_wrapper').removeClass('hidden');
            
            if (conversion) {
                // Determine converted quantity
                const convertedQty = qty >= 0 ? (qty / conversion.factor).toFixed(2) : '0.00';
                $('#converted_quantity').val(convertedQty);
                $('#converted_unit_label').text(conversion.largeUnit);

                // Determine converted cost
                if (perUnit > 0) {
                    const costPerLarge = (perUnit * conversion.factor).toFixed(2);
                    $('#converted_cost').val(costPerLarge);
                    $('#converted_cost_unit_label').text('per ' + conversion.largeUnit);
                    $('#converted_cost_wrapper').removeClass('hidden');
                } else {
                     $('#converted_cost_wrapper').addClass('hidden');
                }
            } else {
                // Fail-safe if conversion not found but not pcs (shouldn't happen with current logic)
                 $('#converted_cost_wrapper').addClass('hidden');
            }
        }
    }

    // Trigger calculation on input changes
    $('#material_quantity, #total_cost, #unit').on('input change', function() {
        updateCostCalculations();
    });

    // Load Categories for Filter dropdown
    function loadFilterCategories() {
        $.ajax({
            url: baseUrl + 'MaterialCategory/FetchAll',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    let options = '<option value="">All Categories</option>';
                    response.data.forEach(function(cat) {
                        options += '<option value="' + cat.category_id + '">' + cat.category_name + '</option>';
                    });
                    $('#filter-category').html(options);
                }
            }
        });
    }

    // Load Categories for Modal dropdown
    function loadCategories() {
        $.ajax({
            url: baseUrl + 'MaterialCategory/FetchAll',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    let options = '<option value="">Select</option>';
                    response.data.forEach(function(cat) {
                        options += '<option value="' + cat.category_id + '">' + cat.category_name + '</option>';
                    });
                    $('#category_id').html(options);
                }
            }
        });
    }

    // Load Materials via AJAX
    function loadMaterials() {
        $.ajax({
            url: baseUrl + 'RawMaterials/GetAll',
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
                    response.data.forEach(function(mat) {
                        // Label badge colors
                        const labelColors = {
                            'drinks': 'bg-blue-100 text-blue-800',
                            'bread': 'bg-amber-100 text-amber-800',
                            'general': 'bg-gray-100 text-gray-800'
                        };
                        const labelColor = labelColors[mat.label] || 'bg-gray-100 text-gray-800';
                        const labelBadge = mat.label ? '<span class="text-xs px-2 py-1 rounded-full ' + labelColor + '">' + mat.label + '</span>' : '-';

                        rows += '<tr class="hover:bg-gray-50 cursor-pointer border-b" data-category="' + (mat.category_id || '') + '">';
                        rows += '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">' + mat.material_name + '</td>';
                        rows += '<td class="px-6 py-4 text-gray-700">' + (mat.category_name || '-') + '</td>';
                        rows += '<td class="px-6 py-4 text-gray-700">' + labelBadge + '</td>';
                        rows += '<td class="px-6 py-4 text-gray-700">' + mat.material_quantity + '</td>';
                        rows += '<td class="px-6 py-4 text-gray-700">' + mat.unit + '</td>';
                        rows += '<td class="px-6 py-4 text-gray-900 font-semibold">₱ ' + parseFloat(mat.cost_per_unit || 0).toFixed(2) + '</td>';
                        rows += '<td class="px-6 py-4 ">';
                        rows += '<button class="text-blue-600 py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-blue-800 me-2 btn-edit" data-id="' + mat.material_id + '" title="Edit"><i class="fas fa-edit"></i></button>';
                        rows += '<button class="text-red-600 py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-red-800 btn-delete" data-id="' + mat.material_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
                        rows += '</td>';
                        rows += '</tr>';
                    });

                    // Update table body
                    $('#materialsTableBody').html(rows);

                    // Re-initialize DataTable with simple config
                    const tableElement = document.getElementById('selection-table');
                    if (tableElement && typeof simpleDatatables !== 'undefined') {
                        dataTable = new simpleDatatables.DataTable('#selection-table', {
                            searchable: true,
                            sortable: true,
                            perPage: 10
                        });

                        // Add Tailwind classes for scrolling (only table content scrolls)
                        const container = document.querySelector('.datatable-container');
                        if (container) {
                            container.classList.add('max-h-96', 'overflow-y-auto', 'overflow-x-auto');
                        }

                        // Add sticky header classes
                        const thead = document.querySelector('.datatable-table thead');
                        if (thead) {
                            thead.classList.add('sticky', 'top-0', 'bg-white', 'z-10');
                        }

                        // Add sticky first column classes
                        document.querySelectorAll('.datatable-table thead th:first-child').forEach(th => {
                            th.classList.add('sticky', 'left-0', 'bg-white', 'z-20');
                        });
                        document.querySelectorAll('.datatable-table tbody td:first-child').forEach(td => {
                            td.classList.add('sticky', 'left-0', 'bg-white', 'z-5');
                        });

                        // Apply Tailwind responsive classes to datatable elements
                        applyDatatableStyles();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.log('Error loading materials: ' + error);
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
                            placeholder: "Search materials...",
                            perPage: "entries per page",
                            noRows: "No raw material data available",
                            noResults: "No results match your search",
                            info: "Showing {start} to {end} of {rows} entries"
                        },
                        perPage: 10,
                        perPageSelect: [5, 10, 25, 50]
                    });
                    applyDatatableStyles();
                }
            }
        });
    }

    // Apply Tailwind classes to datatable elements (replaces CSS media queries)
    function applyDatatableStyles() {
        // Mobile responsive styles for datatable
        const datatableTop = document.querySelector('.datatable-top');
        const datatableBottom = document.querySelector('.datatable-bottom');
        
        if (datatableTop) {
            datatableTop.classList.add('flex', 'flex-col', 'sm:flex-row', 'items-center', 'gap-2', 'py-2');
        }
        if (datatableBottom) {
            datatableBottom.classList.add('flex', 'flex-col', 'sm:flex-row', 'items-center', 'gap-2', 'py-2');
        }

        // Style dropdown and search
        const datatableDropdown = document.querySelector('.datatable-dropdown');
        const datatableSearch = document.querySelector('.datatable-search');
        const datatableInfo = document.querySelector('.datatable-info');
        const datatablePagination = document.querySelector('.datatable-pagination');

        if (datatableDropdown) {
            datatableDropdown.classList.add('w-full', 'sm:w-auto', 'text-center', 'sm:text-left');
        }
        if (datatableSearch) {
            datatableSearch.classList.add('w-full', 'sm:w-auto', 'mt-2', 'sm:mt-0');
        }
        if (datatableInfo) {
            datatableInfo.classList.add('w-full', 'sm:w-auto', 'text-center', 'sm:text-left');
        }
        if (datatablePagination) {
            datatablePagination.classList.add('w-full', 'sm:w-auto');
            const paginationUl = datatablePagination.querySelector('ul');
            if (paginationUl) {
                paginationUl.classList.add('justify-center', 'sm:justify-end');
            }
        }
    }

    // Submit Add/Edit Material Form via AJAX
    $('#addMaterialForm').on('submit', function(e) {
        e.preventDefault();

        // Check if material name already exists
        if (materialNameExists) {
            Toast.warning('Material name already exists. Please use a different name.');
            return;
        }

        const materialId = $('#edit_material_id').val();
        const formData = {
            material_name: $('#material_name').val(),
            category_id: $('#category_id').val(),
            unit: $('#unit').val(),
            material_quantity: $('#material_quantity').val(),
            total_cost: $('#total_cost').val()
        };

        // Use Update endpoint if editing, Add endpoint if creating new
        let endpoint = 'RawMaterials/AddRawMaterial';
        if (materialId) {
            formData.material_id = materialId;
            endpoint = 'RawMaterials/UpdateRawMaterial';
        }

        $.ajax({
            url: baseUrl + endpoint,
            type: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Toast.success(materialId ? 'Material updated successfully!' : 'Material added successfully!');
                    closeModal();
                    loadMaterials();
                } else {
                    Toast.error('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                Toast.error('Error saving material: ' + error);
            }
        });
    });

    // Edit Material
    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');

        $.ajax({
            url: baseUrl + 'RawMaterials/GetMaterial/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const mat = response.data;
                    $('#edit_material_id').val(mat.material_id);
                    $('#material_name').val(mat.material_name);
                    $('#category_id').val(mat.category_id);
                    $('#unit').val(mat.unit);
                    $('#material_quantity').val(mat.material_quantity);
                    $('#total_cost').val(parseFloat(mat.total_cost).toFixed(2));
                    $('#cost_per_unit_display').text(parseFloat(mat.cost_per_unit).toFixed(3));
                    $('#modalTitle').text('Edit Raw Material');
                    $('#btnSaveMaterial').text('Update');

                    // Open modal and load categories
                    loadCategories();
                    $('#addMaterialModal').removeClass('hidden');

                    // Set category after dropdown is loaded and update calculations
                    setTimeout(function() {
                        $('#category_id').val(mat.category_id);
                        updateCostCalculations();
                    }, 300);
                } else {
                    Toast.error('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                Toast.error('Error loading material: ' + error);
            }
        });
    });

    // Delete Material
    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        Confirm.delete('Are you sure you want to delete this material?', function() {
            $.ajax({
                url: baseUrl + 'RawMaterials/Delete/' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Toast.success('Material deleted successfully!');
                        loadMaterials();
                    } else {
                        Toast.error('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    Toast.error('Error deleting material: ' + error);
                }
            });
        });
    });

    // Apply Filter
    $('#apply-filters').on('click', function() {
        const categoryId = $('#filter-category').val();
        $('table tbody tr').each(function() {
            if (categoryId === '' || $(this).data('category') == categoryId) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Reset Filter
    $('#reset-filters').on('click', function() {
        $('#filter-category').val('');
        $('table tbody tr').show();
    });
});
