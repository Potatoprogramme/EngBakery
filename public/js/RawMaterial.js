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

    // Close modal on outside click
    $('#addMaterialModal').on('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
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
        $('#cost_per_unit_display').text('0.000');
        $('#converted_quantity_display').addClass('hidden').text('');
        $('#converted_cost_display').addClass('hidden').text('');
    }

    // Calculate cost per unit with conversions
    function updateCostCalculations() {
        const qty = parseFloat($('#material_quantity').val()) || 0;
        const cost = parseFloat($('#total_cost').val()) || 0;
        const unit = $('#unit').val();
        const conversion = unitConversions[unit];

        // Calculate cost per base unit (grams, ml, pcs)
        const perUnit = qty > 0 ? (cost / qty) : 0;
        $('#cost_per_unit_display').text(perUnit.toFixed(3));

        // Show converted quantity (e.g., 25000 grams = 25 kg)
        if (conversion && qty > 0) {
            const convertedQty = (qty / conversion.factor).toFixed(2);
            $('#converted_quantity_display')
                .html('= <strong>' + convertedQty + ' ' + conversion.largeUnit + '</strong>')
                .removeClass('hidden');
        } else {
            $('#converted_quantity_display').addClass('hidden');
        }

        // Show converted cost (e.g., ₱0.054/gram = ₱54/kg)
        if (conversion && perUnit > 0) {
            const costPerLarge = (perUnit * conversion.factor).toFixed(2);
            $('#converted_cost_display')
                .html('= <strong>₱' + costPerLarge + '</strong> per ' + conversion.largeUnit)
                .removeClass('hidden');
        } else {
            $('#converted_cost_display').addClass('hidden');
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
                        rows += '<tr class="hover:bg-gray-50 cursor-pointer border-b" data-category="' + (mat.category_id || '') + '">';
                        rows += '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">' + mat.material_name + '</td>';
                        rows += '<td class="px-6 py-4">' + (mat.category_name || '-') + '</td>';
                        rows += '<td class="px-6 py-4">' + mat.material_quantity + '</td>';
                        rows += '<td class="px-6 py-4">' + mat.unit + '</td>';
                        rows += '<td class="px-6 py-4">' + parseFloat(mat.cost_per_unit || 0).toFixed(3) + '</td>';
                        rows += '<td class="px-6 py-4">';
                        rows += '<button class="text-blue-600 hover:text-blue-800 me-2 btn-edit" data-id="' + mat.material_id + '" title="Edit"><i class="fas fa-edit"></i></button>';
                        rows += '<button class="text-red-600 hover:text-red-800 btn-delete" data-id="' + mat.material_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
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
            alert('Material name already exists. Please use a different name.');
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
                    alert(materialId ? 'Material updated successfully!' : 'Material added successfully!');
                    closeModal();
                    loadMaterials();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error saving material: ' + error);
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
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error loading material: ' + error);
            }
        });
    });

    // Delete Material
    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this material?')) {
            $.ajax({
                url: baseUrl + 'RawMaterials/Delete/' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Material deleted successfully!');
                        loadMaterials();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error deleting material: ' + error);
                }
            });
        }
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
