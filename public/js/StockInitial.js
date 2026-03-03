/**
 * Stock Initial Page Handler
 * Handles CRUD operations for raw material stock initial entries
 * ALL costs and remaining values are computed DYNAMICALLY from cost_per_unit
 */
$(document).ready(function () {
    const baseUrl = window.BASE_URL || '/';
    let dataTable = null;
    let allEntries = [];
    let filteredEntries = [];
    let currentPage = 1;
    const itemsPerPage = 10;
    let deleteEntryId = null;
    let currentViewEntryId = null;

    // ──────────────────────────────
    //  Load data on page ready
    // ──────────────────────────────
    loadEntries();
    loadFilterCategories();

    // ──────────────────────────────
    //  Open Add Modal
    // ──────────────────────────────
    $('#btnAddEntry, #btnAddEntryMobile').on('click', function () {
        resetModal();
        $('#stockInitialModal').removeClass('hidden');
        loadMaterialsList();
    });

    // ──────────────────────────────
    //  Close Modal
    // ──────────────────────────────
    $('#btnCloseModal, #btnCancelAdd').on('click', function () {
        closeModal();
    });

    // ──────────────────────────────
    //  Searchable Material Dropdown
    // ──────────────────────────────
    let allMaterialsData = [];

    $('#material_search').on('focus', function () {
        showMaterialDropdown($(this).val());
    });

    $('#material_search').on('input', function () {
        if ($('#material_id').val()) {
            $('#material_id').val('');
            $('#edit_cost_per_unit').val(0);
            $('#btnClearMaterial').addClass('hidden');
        }
        showMaterialDropdown($(this).val());
    });

    $(document).on('click', '.material-option', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const unit = $(this).data('unit');
        const cost = parseFloat($(this).data('cost')) || 0;

        $('#material_id').val(id);
        $('#material_search').val(name);
        $('#unit').val(unit);
        $('#edit_cost_per_unit').val(cost);
        $('#btnClearMaterial').removeClass('hidden');
        hideMaterialDropdown();
        recalcModal();
        $('#initial_qty').focus();
    });

    $('#btnClearMaterial').on('click', function () {
        $('#material_id').val('');
        $('#material_search').val('');
        $('#edit_cost_per_unit').val(0);
        $(this).addClass('hidden');
        $('#material_search').focus();
        recalcModal();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#material_search, #material_dropdown').length) {
            hideMaterialDropdown();
        }
    });

    function showMaterialDropdown(searchTerm) {
        searchTerm = (searchTerm || '').toLowerCase();
        const editingMaterialId = $('#edit_stock_id').val() ? $('#material_id').val() : null;
        const existingMaterialIds = allEntries.map(function (e) { return String(e.material_id); });

        const filtered = allMaterialsData.filter(function (m) {
            const matchesSearch = !searchTerm || m.material_name.toLowerCase().includes(searchTerm);
            const isCurrentEdit = editingMaterialId && String(m.material_id) === String(editingMaterialId);
            const alreadyExists = existingMaterialIds.includes(String(m.material_id));
            return matchesSearch && (!alreadyExists || isCurrentEdit);
        });

        let html = '';
        if (filtered.length === 0) {
            html = '<div class="px-3 py-2 text-sm text-gray-500">No materials found</div>';
        } else {
            filtered.forEach(function (m) {
                const cost = parseFloat(m.cost_per_unit) || 0;
                html += '<div class="material-option px-3 py-2 text-sm cursor-pointer hover:bg-primary/10 border-b border-gray-100 last:border-b-0" ' +
                    'data-id="' + m.material_id + '" ' +
                    'data-name="' + m.material_name + '" ' +
                    'data-unit="' + m.unit + '" ' +
                    'data-cost="' + cost + '">' +
                    '<span class="font-medium">' + m.material_name + '</span>' +
                    '<span class="text-xs text-gray-400 ml-2">(' + m.unit + ')</span>' +
                    (cost > 0 ? '<span class="text-xs text-green-600 ml-2">₱' + cost.toFixed(2) + '/unit</span>' : '') +
                    '</div>';
            });
        }

        $('#material_dropdown').html(html).removeClass('hidden');
    }

    function hideMaterialDropdown() {
        $('#material_dropdown').addClass('hidden');
    }

    // ──────────────────────────────
    //  DYNAMIC RECALCULATION
    //  Fires on every keystroke in initial_qty or remaining_qty
    // ──────────────────────────────
    $('#initial_qty').on('input change', function () {
        const initial = parseFloat($('#initial_qty').val()) || 0;
        $('#remaining_qty').attr('max', initial);
        // If remaining now exceeds new initial, clamp it
        let remaining = parseFloat($('#remaining_qty').val()) || 0;
        if (remaining > initial) {
            $('#remaining_qty').val(initial);
        }
        recalcModal();
    });

    // Validate remaining on every keystroke — show inline error & disable Update if exceeded
    $('#remaining_qty').on('input change', function () {
        const initial = parseFloat($('#initial_qty').val()) || 0;
        let remaining = parseFloat($(this).val()) || 0;

        if (remaining < 0) {
            $(this).val(0);
            remaining = 0;
        }

        if (remaining > initial) {
            // Show inline error, red border, disable Update
            $(this).addClass('border-red-500 bg-red-50').removeClass('border-blue-300 bg-blue-50');
            $('#remaining_error').removeClass('hidden');
            $('#btnSaveEntry').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        } else {
            // Clear error, restore styling, enable Update
            $(this).removeClass('border-red-500 bg-red-50').addClass('border-blue-300 bg-blue-50');
            $('#remaining_error').addClass('hidden');
            $('#btnSaveEntry').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
        }
        recalcModal();
    });

    function recalcModal() {
        const initial = parseFloat($('#initial_qty').val()) || 0;
        const remaining = parseFloat($('#remaining_qty').val()) || 0;
        const costPerUnit = parseFloat($('#edit_cost_per_unit').val()) || 0;

        // Clamp remaining to not exceed initial
        const clampedRemaining = Math.min(Math.max(0, remaining), initial);
        const used = Math.max(0, initial - clampedRemaining);

        // Update used display field
        $('#qty_used_display').val(formatNumber(used));

        // Update cost displays
        const initialCost = initial * costPerUnit;
        const usedCost = used * costPerUnit;
        const remainingCost = clampedRemaining * costPerUnit;

        $('#display_initial_cost').text('₱' + initialCost.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#display_used_cost').text('₱' + usedCost.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#display_remaining_cost').text('₱' + remainingCost.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    }

    // ──────────────────────────────
    //  Submit Form (Add / Edit)
    // ──────────────────────────────
    $('#stockInitialForm').on('submit', function (e) {
        e.preventDefault();

        if (!$('#material_id').val()) {
            showToast('error', 'Please select a raw material.');
            $('#material_search').focus();
            return;
        }

        const entryId = $('#edit_stock_id').val();
        const isEdit = entryId !== '';

        const initial = parseFloat($('#initial_qty').val()) || 0;
        const remaining = parseFloat($('#remaining_qty').val()) || 0;

        const payload = {
            material_id: $('#material_id').val(),
            initial_qty: $('#initial_qty').val(),
            unit: $('#unit').val()
        };

        if (isEdit) {
            payload.stock_id = entryId;
            payload.remaining = Math.min(Math.max(0, remaining), initial); // Send remaining, server computes used
        } else {
            payload.qty_used = 0;
        }

        const url = isEdit
            ? baseUrl + 'MaterialStock/Update'
            : baseUrl + 'MaterialStock/Add';

        $('#btnSaveEntry').prop('disabled', true).text('Saving...');

        $.ajax({
            url: url,
            type: 'POST',
            data: JSON.stringify(payload),
            contentType: 'application/json',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    showToast('success', res.message);
                    closeModal();
                    loadEntries();
                } else {
                    showToast('error', res.message);
                }
            },
            error: function () {
                showToast('error', 'Server error. Please try again.');
            },
            complete: function () {
                $('#btnSaveEntry').prop('disabled', false).text('Save');
            }
        });
    });

    // ──────────────────────────────
    //  Edit Entry
    // ──────────────────────────────
    $(document).on('click', '.btn-edit-entry', function () {
        const entryId = $(this).data('id');

        $.ajax({
            url: baseUrl + 'MaterialStock/GetEntry/' + entryId,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    const d = res.data;
                    loadMaterialsList(function () {
                        $('#edit_stock_id').val(d.stock_id);
                        $('#material_id').val(d.material_id);
                        const costPerUnit = parseFloat(d.cost_per_unit) || 0;
                        $('#edit_cost_per_unit').val(costPerUnit);

                        // Find the material name to display in the search input
                        const mat = allMaterialsData.find(m => String(m.material_id) === String(d.material_id));
                        if (mat) {
                            $('#material_search').val(mat.material_name);
                            $('#btnClearMaterial').removeClass('hidden');
                        }

                        $('#initial_qty').val(d.initial_qty);
                        $('#unit').val(d.unit);

                        // Set remaining directly (remaining = initial - used)
                        const qtyUsed = parseFloat(d.qty_used) || 0;
                        const initialQty = parseFloat(d.initial_qty) || 0;
                        const remaining = Math.max(0, initialQty - qtyUsed);
                        $('#remaining_qty').val(remaining);

                        // Show edit-only fields
                        $('#qty_used_wrapper').removeClass('hidden');
                        $('#remaining_qty_wrapper').removeClass('hidden');
                        $('#cost_breakdown_wrapper').removeClass('hidden');

                        // Trigger recalculation to fill remaining & costs
                        recalcModal();

                        $('#modalTitle').text('Edit Stock Entry');
                        $('#btnSaveEntry').text('Update');
                        $('#stockInitialModal').removeClass('hidden');
                    });
                } else {
                    showToast('error', res.message);
                }
            }
        });
    });

    // ──────────────────────────────
    //  Delete Entry (open confirm)
    // ──────────────────────────────
    $(document).on('click', '.btn-delete-entry', function () {
        deleteEntryId = $(this).data('id');
        $('#deleteConfirmModal').removeClass('hidden');
    });

    $('#btnCancelDelete').on('click', function () {
        deleteEntryId = null;
        $('#deleteConfirmModal').addClass('hidden');
    });

    $('#btnConfirmDelete').on('click', function () {
        if (!deleteEntryId) return;

        $.ajax({
            url: baseUrl + 'MaterialStock/Delete/' + deleteEntryId,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    showToast('success', res.message);
                    loadEntries();
                } else {
                    showToast('error', res.message);
                }
            },
            error: function () {
                showToast('error', 'Server error. Please try again.');
            },
            complete: function () {
                deleteEntryId = null;
                $('#deleteConfirmModal').addClass('hidden');
            }
        });
    });

    // ──────────────────────────────
    //  Filters
    // ──────────────────────────────
    $('#apply-filters').on('click', function () {
        applyFilters();
    });

    $('#reset-filters').on('click', function () {
        $('#filter-category').val('');
        loadEntries();
    });

    // ──────────────────────────────
    //  Mobile search
    // ──────────────────────────────
    $('#mobileSearch').on('input', function () {
        const query = $(this).val().toLowerCase();
        filteredEntries = allEntries.filter(e =>
            e.material_name.toLowerCase().includes(query) ||
            (e.category_name || '').toLowerCase().includes(query) ||
            e.unit.toLowerCase().includes(query)
        );
        currentPage = 1;
        renderMobileCards();
    });

    // ═══════════════════════════════
    //  HELPER FUNCTIONS
    // ═══════════════════════════════

    function loadEntries() {
        $.ajax({
            url: baseUrl + 'MaterialStock/GetAll',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    allEntries = res.data;
                    filteredEntries = [...allEntries];
                    renderDesktopTable(allEntries);
                    renderMobileCards();
                }
            }
        });
    }

    function renderDesktopTable(data) {
        // Destroy existing DataTable
        if (dataTable) {
            dataTable.destroy();
            dataTable = null;
        }

        const tbody = $('#stockInitialTableBody');
        tbody.empty();

        if (data.length === 0) {
            tbody.html('<tr><td colspan="11" class="px-6 py-8 text-center text-gray-400">No stock entries found.</td></tr>');
            return;
        }

        data.forEach(function (entry) {
            const initial = parseFloat(entry.initial_qty) || 0;
            const used = parseFloat(entry.qty_used) || 0;
            const remaining = Math.max(0, initial - used);
            const costPerUnit = parseFloat(entry.cost_per_unit) || 0;

            // DYNAMIC cost calculations: qty * cost_per_unit
            const initialCost = initial * costPerUnit;
            const usedCost = used * costPerUnit;
            const remainingCost = remaining * costPerUnit;

            const pct = initial > 0 ? (remaining / initial * 100) : 0;

            // Health bar colors
            let barColor = 'bg-emerald-400', barTrack = 'bg-emerald-100';
            let remainText = 'text-gray-700';
            let barWidth = initial > 0 ? Math.min(100, (remaining / initial) * 100) : 0;
            if (pct <= 10) { barColor = 'bg-red-500'; barTrack = 'bg-red-100'; remainText = 'text-red-600 font-semibold'; }
            else if (pct <= 25) { barColor = 'bg-amber-400'; barTrack = 'bg-amber-100'; remainText = 'text-amber-600 font-semibold'; }
            else if (pct <= 50) { barColor = 'bg-yellow-400'; barTrack = 'bg-yellow-100'; }

            const dateStr = entry.updated_at ? new Date(entry.updated_at).toLocaleDateString('en-PH', {
                year: 'numeric', month: 'short', day: 'numeric'
            }) : '—';

            const row = `
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-900">${entry.material_name}</td>
                    <td class="px-6 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                            ${entry.category_name || '—'}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-700 tabular-nums text-sm">${formatNumber(initial)}</td>
                    <td class="px-6 py-3 tabular-nums text-sm"><span class="text-orange-600">${formatNumber(used)}</span></td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-2.5">
                            <span class="${remainText} tabular-nums text-sm min-w-[2.5rem]">${formatNumber(remaining)}</span>
                            <div class="flex-1 max-w-[4.5rem] h-1.5 rounded-full ${barTrack} overflow-hidden">
                                <div class="h-full rounded-full ${barColor} transition-all" style="width:${barWidth}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-gray-700">${entry.unit}</td>
                    <td class="px-6 py-3 text-green-700 tabular-nums text-sm">₱${formatNumber(initialCost)}</td>
                    <td class="px-6 py-3 text-orange-700 tabular-nums text-sm">₱${formatNumber(usedCost)}</td>
                    <td class="px-6 py-3 text-blue-700 tabular-nums text-sm">₱${formatNumber(remainingCost)}</td>
                    <td class="px-6 py-3 text-xs text-gray-400">${dateStr}</td>
                    <td class="px-6 py-3">
                        <button class="text-gray-600 py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-gray-800 me-2 btn-view-entry" data-id="${entry.stock_id}" title="View"><i class="fas fa-eye"></i></button><button class="text-blue-600 py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-blue-800 me-2 btn-edit-entry" data-id="${entry.stock_id}" title="Edit"><i class="fas fa-edit"></i></button><button class="text-red-600 py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-red-800 btn-delete-entry" data-id="${entry.stock_id}" title="Delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });

        // Init simpleDatatables
        const tableEl = document.getElementById('stockInitialTable');
        if (tableEl) {
            dataTable = new simpleDatatables.DataTable(tableEl, {
                searchable: true,
                perPage: 15,
                perPageSelect: [10, 15, 25, 50],
                labels: {
                    placeholder: "Search entries...",
                    noRows: "No entries found",
                    info: "Showing {start} to {end} of {rows} entries"
                }
            });
        }
    }

    function renderMobileCards() {
        const container = $('#stockInitialCardsContainer');
        container.empty();

        const startIndex = (currentPage - 1) * itemsPerPage;
        const pageItems = filteredEntries.slice(startIndex, startIndex + itemsPerPage);

        if (pageItems.length === 0) {
            container.html('<div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500"><i class="fas fa-box-open text-4xl mb-3"></i><p>No stock entries found</p></div>');
            renderMobilePagination();
            return;
        }

        let cards = '';
        pageItems.forEach(function (entry) {
            const initial = parseFloat(entry.initial_qty) || 0;
            const used = parseFloat(entry.qty_used) || 0;
            const remaining = Math.max(0, initial - used);
            const costPerUnit = parseFloat(entry.cost_per_unit) || 0;

            // DYNAMIC cost calculations
            const initialCost = initial * costPerUnit;
            const usedCost = used * costPerUnit;
            const remainingCost = remaining * costPerUnit;

            const pct = initial > 0 ? (remaining / initial * 100) : 0;
            let barColor = 'bg-emerald-400', barTrack = 'bg-emerald-100';
            let barW = initial > 0 ? Math.min(100, (remaining / initial) * 100) : 0;
            let remainTC = 'text-emerald-700';
            if (pct <= 10) { barColor = 'bg-red-500'; barTrack = 'bg-red-100'; remainTC = 'text-red-600'; }
            else if (pct <= 25) { barColor = 'bg-amber-400'; barTrack = 'bg-amber-100'; remainTC = 'text-amber-600'; }
            else if (pct <= 50) { barColor = 'bg-yellow-400'; barTrack = 'bg-yellow-100'; remainTC = 'text-yellow-700'; }

            cards += `
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-base">${entry.material_name}</h3>
                                <p class="text-sm text-gray-500">${entry.category_name || 'Uncategorized'}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mb-2">
                            <div class="bg-blue-50 rounded-lg p-2">
                                <p class="text-xs text-gray-500 mb-0.5">Initial</p>
                                <p class="font-semibold text-blue-700 text-sm">${formatNumber(initial)} ${entry.unit}</p>
                                <p class="text-xs text-green-700 mt-1">₱${formatNumber(initialCost)}</p>
                            </div>
                            <div class="bg-orange-50 rounded-lg p-2">
                                <p class="text-xs text-gray-500 mb-0.5">Used</p>
                                <p class="font-semibold text-orange-600 text-sm">${formatNumber(used)} ${entry.unit}</p>
                                <p class="text-xs text-orange-700 mt-1">₱${formatNumber(usedCost)}</p>
                            </div>
                            <div class="bg-emerald-50 rounded-lg p-2">
                                <p class="text-xs text-gray-500 mb-0.5">Remaining</p>
                                <p class="font-semibold ${remainTC} text-sm">${formatNumber(remaining)} ${entry.unit}</p>
                                <div class="mt-1 h-1.5 rounded-full ${barTrack} overflow-hidden"><div class="h-full rounded-full ${barColor}" style="width:${barW}%"></div></div>
                                <p class="text-xs text-blue-700 mt-1">₱${formatNumber(remainingCost)}</p>
                            </div>
                        </div>
                        <div class="flex gap-2 pt-2 border-t border-gray-100">
                            <button class="flex-1 flex items-center justify-center gap-2 py-2 px-3 text-sm font-medium text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 btn-view-entry" data-id="${entry.stock_id}">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="flex-1 flex items-center justify-center gap-2 py-2 px-3 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 btn-edit-entry" data-id="${entry.stock_id}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="flex-1 flex items-center justify-center gap-2 py-2 px-3 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 btn-delete-entry" data-id="${entry.stock_id}">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        container.html(cards);
        renderMobilePagination();
    }

    function renderMobilePagination() {
        const totalPages = Math.ceil(filteredEntries.length / itemsPerPage);
        const pag = $('#mobilePagination');
        pag.empty();

        if (totalPages <= 1) return;

        const maxVisible = 5;
        let pages = [];

        if (totalPages <= maxVisible) {
            for (let i = 1; i <= totalPages; i++) pages.push(i);
        } else {
            pages.push(1);
            let start = Math.max(2, currentPage - 1);
            let end = Math.min(totalPages - 1, currentPage + 1);
            if (currentPage <= 3) { start = 2; end = Math.min(4, totalPages - 1); }
            else if (currentPage >= totalPages - 2) { start = Math.max(totalPages - 3, 2); end = totalPages - 1; }
            if (start > 2) pages.push('...');
            for (let i = start; i <= end; i++) pages.push(i);
            if (end < totalPages - 1) pages.push('...');
            pages.push(totalPages);
        }

        pag.append(`<button class="px-2 py-1 rounded text-sm ${currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-primary hover:bg-primary/10'}" 
            ${currentPage === 1 ? 'disabled' : ''} data-page="${currentPage - 1}">&laquo;</button>`);

        pages.forEach(function (p) {
            if (p === '...') {
                pag.append(`<span class="px-1 py-1 text-sm text-gray-400">&hellip;</span>`);
            } else {
                pag.append(`<button class="px-2 py-1 rounded text-sm min-w-[28px] ${p === currentPage ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100'}" 
                    data-page="${p}">${p}</button>`);
            }
        });

        pag.append(`<button class="px-2 py-1 rounded text-sm ${currentPage === totalPages ? 'text-gray-300 cursor-not-allowed' : 'text-primary hover:bg-primary/10'}" 
            ${currentPage === totalPages ? 'disabled' : ''} data-page="${currentPage + 1}">&raquo;</button>`);

        pag.find('button').on('click', function () {
            const page = parseInt($(this).data('page'));
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                renderMobileCards();
            }
        });
    }

    function loadFilterCategories() {
        $.ajax({
            url: baseUrl + 'MaterialCategory/FetchAll',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    const select = $('#filter-category');
                    select.find('option:not(:first)').remove();
                    res.data.forEach(function (cat) {
                        select.append(`<option value="${cat.category_id}">${cat.category_name}</option>`);
                    });
                }
            }
        });
    }

    function loadMaterialsList(callback) {
        $.ajax({
            url: baseUrl + 'MaterialStock/GetMaterials',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    allMaterialsData = res.data;
                    if (typeof callback === 'function') callback();
                }
            }
        });
    }

    function applyFilters() {
        const categoryId = $('#filter-category').val();

        if (!categoryId) {
            filteredEntries = [...allEntries];
        } else {
            filteredEntries = allEntries.filter(e => String(e.category_id) === String(categoryId));
        }

        renderDesktopTable(filteredEntries);
        currentPage = 1;
        renderMobileCards();
    }

    function resetModal() {
        $('#stockInitialForm')[0].reset();
        $('#edit_stock_id').val('');
        $('#edit_cost_per_unit').val(0);
        $('#material_id').val('');
        $('#material_search').val('');
        $('#btnClearMaterial').addClass('hidden');
        $('#qty_used_wrapper').addClass('hidden');
        $('#remaining_qty_wrapper').addClass('hidden');
        $('#cost_breakdown_wrapper').addClass('hidden');
        $('#qty_used_display').val(0);
        $('#remaining_qty').val(0);
        $('#display_initial_cost').text('₱0.00');
        $('#display_used_cost').text('₱0.00');
        $('#display_remaining_cost').text('₱0.00');
        hideMaterialDropdown();
        $('#modalTitle').text('Add Stock Entry');
        $('#btnSaveEntry').text('Save');
    }

    function closeModal() {
        $('#stockInitialModal').addClass('hidden');
        resetModal();
    }

    function formatNumber(num) {
        const n = parseFloat(num);
        if (isNaN(n)) return '0';
        return n % 1 === 0 ? n.toLocaleString() : n.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 4 });
    }

    function formatCurrency(num) {
        const n = parseFloat(num) || 0;
        return '₱' + n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // ──────────────────────────────
    //  View Entry Modal
    // ──────────────────────────────
    $(document).on('click', '.btn-view-entry', function () {
        const id = $(this).data('id');
        openViewEntryModal(id);
    });

    function openViewEntryModal(entryId) {
        $.ajax({
            url: baseUrl + 'MaterialStock/GetEntry/' + entryId,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    const d = res.data;
                    currentViewEntryId = d.stock_id;

                    const initial = parseFloat(d.initial_qty) || 0;
                    const used = parseFloat(d.qty_used) || 0;
                    const remaining = Math.max(0, initial - used);
                    const costPerUnit = parseFloat(d.cost_per_unit) || 0;
                    const initialCost = initial * costPerUnit;
                    const usedCost = used * costPerUnit;
                    const remainingCost = remaining * costPerUnit;
                    const pct = initial > 0 ? (remaining / initial * 100) : 0;

                    // Material name & category
                    $('#view_material_name').text(d.material_name || 'Unknown');
                    $('#view_category').text(d.category_name || 'Uncategorized');

                    // Stock quantities
                    $('#view_initial_qty').text(formatNumber(initial) + ' ' + d.unit);
                    $('#view_used_qty').text(formatNumber(used) + ' ' + d.unit);
                    $('#view_remaining_qty').text(formatNumber(remaining) + ' ' + d.unit);

                    // Health bar
                    let barColor = 'bg-emerald-400', barTrack = 'bg-emerald-100';
                    if (pct <= 10) { barColor = 'bg-red-500'; barTrack = 'bg-red-100'; }
                    else if (pct <= 25) { barColor = 'bg-amber-400'; barTrack = 'bg-amber-100'; }
                    else if (pct <= 50) { barColor = 'bg-yellow-400'; barTrack = 'bg-yellow-100'; }

                    $('#view_health_bar_track').attr('class', 'h-2 rounded-full ' + barTrack + ' overflow-hidden');
                    $('#view_health_bar').attr('class', 'h-full rounded-full ' + barColor + ' transition-all').css('width', Math.min(100, pct) + '%');
                    $('#view_health_label').text(pct.toFixed(1) + '% remaining');

                    // Cost breakdown
                    $('#view_cost_per_unit').text(formatCurrency(costPerUnit.toFixed(3)));
                    $('#view_initial_cost').text(formatCurrency(initialCost));
                    $('#view_used_cost').text(formatCurrency(usedCost));
                    $('#view_remaining_cost').text(formatCurrency(remainingCost));

                    // Unit & Date
                    $('#view_unit').text(d.unit);
                    const dateStr = d.updated_at ? new Date(d.updated_at).toLocaleDateString('en-PH', {
                        year: 'numeric', month: 'long', day: 'numeric'
                    }) : '—';
                    $('#view_date').text(dateStr);

                    // Show modal
                    $('#viewStockModal').removeClass('hidden');
                    document.body.classList.add('overflow-hidden');
                } else {
                    showToast('error', res.message);
                }
            },
            error: function () {
                showToast('error', 'Error loading entry details.');
            }
        });
    }

    function closeViewModal() {
        $('#viewStockModal').addClass('hidden');
        document.body.classList.remove('overflow-hidden');
        currentViewEntryId = null;
    }

    // Close View Modal
    $('#btnCloseViewModal, #btnCloseViewBottom').on('click', function () {
        closeViewModal();
    });

    // Close View Modal on overlay click
    $('#viewStockModal').on('click', function (e) {
        if (e.target === this) {
            closeViewModal();
        }
    });

    // Edit from View Modal
    $('#btnViewEditEntry').on('click', function () {
        if (currentViewEntryId) {
            closeViewModal();
            // Trigger the existing edit handler
            const btn = $('.btn-edit-entry[data-id="' + currentViewEntryId + '"]').first();
            if (btn.length) {
                btn.trigger('click');
            } else {
                // Fallback: load directly if button not in DOM (paginated)
                const editId = currentViewEntryId;
                $.ajax({
                    url: baseUrl + 'MaterialStock/GetEntry/' + editId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            const d = res.data;
                            loadMaterialsList(function () {
                                $('#edit_stock_id').val(d.stock_id);
                                $('#material_id').val(d.material_id);
                                const costPerUnit = parseFloat(d.cost_per_unit) || 0;
                                $('#edit_cost_per_unit').val(costPerUnit);

                                const mat = allMaterialsData.find(m => String(m.material_id) === String(d.material_id));
                                if (mat) {
                                    $('#material_search').val(mat.material_name);
                                    $('#btnClearMaterial').removeClass('hidden');
                                }

                                $('#initial_qty').val(d.initial_qty);
                                $('#unit').val(d.unit);

                                const qtyUsed = parseFloat(d.qty_used) || 0;
                                const initialQty = parseFloat(d.initial_qty) || 0;
                                const remaining = Math.max(0, initialQty - qtyUsed);
                                $('#remaining_qty').val(remaining);

                                $('#qty_used_wrapper').removeClass('hidden');
                                $('#remaining_qty_wrapper').removeClass('hidden');
                                $('#cost_breakdown_wrapper').removeClass('hidden');
                                recalcModal();

                                $('#modalTitle').text('Edit Stock Entry');
                                $('#btnSaveEntry').text('Update');
                                $('#stockInitialModal').removeClass('hidden');
                            });
                        }
                    }
                });
            }
        }
    });

    // Delete from View Modal
    $('#btnViewDeleteEntry').on('click', function () {
        if (currentViewEntryId) {
            closeViewModal();
            deleteEntryId = currentViewEntryId;
            $('#deleteConfirmModal').removeClass('hidden');
        }
    });
});
