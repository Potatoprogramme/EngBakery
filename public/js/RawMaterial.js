/**
 * Raw Materials Page Handler
 * Handles CRUD operations for raw materials
 */
$(document).ready(function() {
    const baseUrl = window.BASE_URL || '/';
    let dataTable = null;
    let materialNameExists = false;
    let checkNameTimeout = null;
    let allMaterials = []; // Store all materials for mobile view
    let currentPage = 1;
    const itemsPerPage = 10;
    let filteredMaterials = []; // Store filtered materials for search

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
        $('#converted_unit_label').text('kg');
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

        // Update converted cost display
        if (unit === 'pcs') {
            $('#converted_qty_wrapper').addClass('hidden');
            $('#converted_cost_wrapper').addClass('hidden');
        } else if (conversion) {
            $('#converted_qty_wrapper').removeClass('hidden');
            $('#converted_unit_label').text(conversion.largeUnit);

            // Update converted quantity (e.g., 25000 grams = 25 kg)
            const convertedQty = qty > 0 ? (qty / conversion.factor).toFixed(2) : '0';
            $('#converted_quantity').val(convertedQty);

            // Determine converted cost (cost per kg/liter)
            if (perUnit > 0) {
                const costPerLarge = (perUnit * conversion.factor).toFixed(2);
                $('#converted_cost').val(costPerLarge);
                $('#converted_cost_unit_label').text('per ' + conversion.largeUnit);
                $('#converted_cost_wrapper').removeClass('hidden');
            } else {
                $('#converted_cost_wrapper').addClass('hidden');
            }
        } else {
            $('#converted_cost_wrapper').addClass('hidden');
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

                if (response.success && response.data && response.data.length > 0) {
                    allMaterials = response.data;
                    filteredMaterials = [...allMaterials];
                    
                    // Render desktop table
                    renderDesktopTable(response.data);
                    
                    // Render mobile cards
                    currentPage = 1;
                    renderMobileCards();
                } else {
                    allMaterials = [];
                    filteredMaterials = [];
                    $('#materialsTableBody').html('');
                    $('#materialsCardsContainer').html('<div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500"><i class="fas fa-box-open text-4xl mb-3"></i><p>No raw materials found</p></div>');
                    $('#mobilePagination').html('');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error loading materials: ' + error);
                allMaterials = [];
                filteredMaterials = [];
                if (dataTable) {
                    dataTable.destroy();
                    dataTable = null;
                }
                $('#materialsTableBody').html('');
                $('#materialsCardsContainer').html('<div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500"><i class="fas fa-exclamation-triangle text-4xl mb-3"></i><p>Error loading materials</p></div>');
                $('#mobilePagination').html('');
            }
        });
    }

    // Render Desktop Table
    function renderDesktopTable(materials) {
        let rows = '';
        materials.forEach(function(mat) {
            // Label badge colors
            const labelColors = {
                'drinks': 'bg-blue-100 text-blue-800',
                'bread': 'bg-amber-100 text-amber-800',
                'grocery': 'bg-green-100 text-green-800',
                'general': 'bg-gray-100 text-gray-800'
            };
            const labelColor = labelColors[mat.label] || 'bg-gray-100 text-gray-800';
            const labelBadge = mat.label ? '<span class="text-xs px-2 py-1 rounded-full ' + labelColor + '">' + mat.label + '</span>' : '-';

            rows += '<tr class="hover:bg-gray-50 cursor-pointer border-b" data-category="' + (mat.category_id || '') + '">';
            rows += '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">' + mat.material_name + '</td>';
            rows += '<td class="px-6 py-4 text-gray-700">' + (mat.category_name || '-') + '</td>';
            rows += '<td class="px-6 py-4 text-gray-700">' + labelBadge + '</td>';
            let qtyClass = 'text-gray-700';
            let stockDot = '';
            const qty = parseFloat(mat.material_quantity) || 0;
            if (qty <= 10) {
                qtyClass = 'text-red-600 font-semibold';
                stockDot = '<span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-1.5"></span>';
            } else if (qty <= 25) {
                qtyClass = 'text-amber-600 font-semibold';
                stockDot = '<span class="inline-block w-2 h-2 rounded-full bg-amber-400 mr-1.5"></span>';
            }
            rows += '<td class="px-6 py-4 ' + qtyClass + '">' + stockDot + mat.material_quantity + '</td>';
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

    // Render Mobile Cards with Pagination
    function renderMobileCards() {
        const totalPages = Math.ceil(filteredMaterials.length / itemsPerPage);
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedMaterials = filteredMaterials.slice(startIndex, endIndex);

        if (paginatedMaterials.length === 0) {
            $('#materialsCardsContainer').html('<div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500"><i class="fas fa-search text-4xl mb-3"></i><p>No materials found</p></div>');
            $('#mobilePagination').html('');
            return;
        }

        let cards = '';
        paginatedMaterials.forEach(function(mat) {
            // Label badge colors
            const labelColors = {
                'drinks': 'bg-blue-100 text-blue-800',
                'bread': 'bg-amber-100 text-amber-800',
                'grocery': 'bg-green-100 text-green-800',
                'general': 'bg-gray-100 text-gray-800'
            };
            const labelColor = labelColors[mat.label] || 'bg-gray-100 text-gray-800';
            const labelBadge = mat.label ? '<span class="text-xs px-2 py-1 rounded-full ' + labelColor + '">' + mat.label + '</span>' : '';

            cards += `
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-base">${mat.material_name}</h3>
                                <p class="text-sm text-gray-500">${mat.category_name || 'Uncategorized'}</p>
                            </div>
                            ${labelBadge}
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            ${(() => {
                                const q = parseFloat(mat.material_quantity) || 0;
                                let bg = 'bg-gray-50', tc = 'text-gray-800', dot = '';
                                if (q <= 10) { bg = 'bg-red-50'; tc = 'text-red-700'; dot = '<span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-1"></span>'; }
                                else if (q <= 25) { bg = 'bg-amber-50'; tc = 'text-amber-700'; dot = '<span class="inline-block w-2 h-2 rounded-full bg-amber-400 mr-1"></span>'; }
                                return `<div class="${bg} rounded-lg p-2"><p class="text-xs text-gray-500">Quantity</p><p class="font-semibold ${tc}">${dot}${mat.material_quantity} ${mat.unit}</p></div>`;
                            })()}
                            <div class="bg-primary/10 rounded-lg p-2">
                                <p class="text-xs text-gray-500">Cost per Unit</p>
                                <p class="font-bold text-primary">₱ ${parseFloat(mat.cost_per_unit || 0).toFixed(2)}</p>
                            </div>
                        </div>
                        <div class="flex gap-2 pt-2 border-t border-gray-100">
                            <button class="flex-1 flex items-center justify-center gap-2 py-2 px-3 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 btn-edit" data-id="${mat.material_id}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="flex-1 flex items-center justify-center gap-2 py-2 px-3 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 btn-delete" data-id="${mat.material_id}">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        $('#materialsCardsContainer').html(cards);
        renderMobilePagination(totalPages);
    }

    // Render Mobile Pagination
    function renderMobilePagination(totalPages) {
        if (totalPages <= 1) {
            // Hide pagination completely when only 1 page or no data
            $('#mobilePagination').html('');
            return;
        }

        let pagination = '';
        
        // Previous button
        pagination += `<button class="px-3 py-2 text-sm font-medium rounded-lg border ${currentPage === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'}" ${currentPage === 1 ? 'disabled' : ''} data-page="${currentPage - 1}">
            <i class="fas fa-chevron-left"></i>
        </button>`;

        // Page numbers
        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
        
        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        if (startPage > 1) {
            pagination += `<button class="px-3 py-2 text-sm font-medium rounded-lg bg-white text-gray-700 hover:bg-gray-50 border" data-page="1">1</button>`;
            if (startPage > 2) {
                pagination += `<span class="px-2 py-2 text-gray-400">...</span>`;
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            pagination += `<button class="px-3 py-2 text-sm font-medium rounded-lg border ${i === currentPage ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50'}" data-page="${i}">${i}</button>`;
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                pagination += `<span class="px-2 py-2 text-gray-400">...</span>`;
            }
            pagination += `<button class="px-3 py-2 text-sm font-medium rounded-lg bg-white text-gray-700 hover:bg-gray-50 border" data-page="${totalPages}">${totalPages}</button>`;
        }

        // Next button
        pagination += `<button class="px-3 py-2 text-sm font-medium rounded-lg border ${currentPage === totalPages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'}" ${currentPage === totalPages ? 'disabled' : ''} data-page="${currentPage + 1}">
            <i class="fas fa-chevron-right"></i>
        </button>`;

        $('#mobilePagination').html(pagination);
    }

    // Handle Mobile Pagination Click
    $(document).on('click', '#mobilePagination button:not([disabled])', function() {
        const page = parseInt($(this).data('page'));
        if (page && page !== currentPage) {
            currentPage = page;
            renderMobileCards();
            // Scroll to top of cards
            $('html, body').animate({
                scrollTop: $('#materialsCardsContainer').offset().top - 100
            }, 300);
        }
    });

    // Mobile Search
    $('#mobileSearch').on('input', function() {
        const searchTerm = $(this).val().toLowerCase().trim();
        
        if (searchTerm === '') {
            filteredMaterials = [...allMaterials];
        } else {
            filteredMaterials = allMaterials.filter(function(mat) {
                return mat.material_name.toLowerCase().includes(searchTerm) ||
                       (mat.category_name && mat.category_name.toLowerCase().includes(searchTerm)) ||
                       (mat.label && mat.label.toLowerCase().includes(searchTerm));
            });
        }
        
        currentPage = 1;
        renderMobileCards();
    });

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
    let isSubmitting = false; // Prevent double submission
    
    $('#addMaterialForm').on('submit', function(e) {
        e.preventDefault();

        // Prevent double submission using ButtonLoader
        const submitBtn = $('#btnSaveMaterial');
        if (typeof ButtonLoader !== 'undefined' && ButtonLoader.isLoading(submitBtn)) {
            return;
        }

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

        // Set loading state
        if (typeof ButtonLoader !== 'undefined') {
            ButtonLoader.start(submitBtn, materialId ? 'Updating...' : 'Saving...');
        } else {
            isSubmitting = true;
            submitBtn.prop('disabled', true).text('Saving...');
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
            },
            complete: function() {
                // Reset loading state
                if (typeof ButtonLoader !== 'undefined') {
                    ButtonLoader.stop(submitBtn);
                } else {
                    isSubmitting = false;
                    $('#btnSaveMaterial').prop('disabled', false).text(materialId ? 'Update' : 'Save');
                }
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
        const btn = $(this);
        
        // Prevent double click
        if (typeof ButtonLoader !== 'undefined' && ButtonLoader.isLoading(btn)) {
            return;
        }
        
        Confirm.delete('Are you sure you want to delete this material?', function() {
            if (typeof ButtonLoader !== 'undefined') {
                ButtonLoader.start(btn, '');
            }
            $.ajax({
                url: baseUrl + 'RawMaterials/Delete/' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (typeof ButtonLoader !== 'undefined') {
                        ButtonLoader.stop(btn);
                    }
                    if (response.success) {
                        Toast.success('Material deleted successfully!');
                        loadMaterials();
                    } else {
                        Toast.error('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    if (typeof ButtonLoader !== 'undefined') {
                        ButtonLoader.stop(btn);
                    }
                    Toast.error('Error deleting material: ' + error);
                }
            });
        });
    });

    // Apply Filter
    $('#apply-filters').on('click', function() {
        const categoryId = $('#filter-category').val();
        
        // Filter materials
        if (categoryId === '') {
            filteredMaterials = [...allMaterials];
        } else {
            filteredMaterials = allMaterials.filter(function(mat) {
                return mat.category_id == categoryId;
            });
        }
        
        // Re-render desktop table with filtered data
        if (dataTable) {
            dataTable.destroy();
            dataTable = null;
        }
        
        if (filteredMaterials.length > 0) {
            renderDesktopTable(filteredMaterials);
        } else {
            $('#materialsTableBody').html('<tr><td colspan="7" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-filter text-4xl mb-3 block"></i><p>No materials found for the selected category</p></td></tr>');
        }
        
        // Reset mobile pagination and render cards
        currentPage = 1;
        renderMobileCards();
    });

    // Reset Filter
    $('#reset-filters').on('click', function() {
        $('#filter-category').val('');
        
        // Reset filtered materials to all materials
        filteredMaterials = [...allMaterials];
        
        // Re-render desktop table with all data
        if (dataTable) {
            dataTable.destroy();
            dataTable = null;
        }
        
        if (filteredMaterials.length > 0) {
            renderDesktopTable(filteredMaterials);
        } else {
            $('#materialsTableBody').html('<tr><td colspan="7" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-box-open text-4xl mb-3 block"></i><p>No raw materials found</p></td></tr>');
        }
        
        // Reset mobile cards
        currentPage = 1;
        $('#mobileSearch').val('');
        renderMobileCards();
    });
});
