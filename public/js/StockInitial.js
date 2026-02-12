/**
 * Stock Initial Page Handler
 * Handles CRUD operations for raw material stock initial entries
 */
$(document).ready(function () {
    const baseUrl = window.BASE_URL || '/';
    let dataTable = null;
    let allEntries = [];
    let filteredEntries = [];
    let currentPage = 1;
    const itemsPerPage = 10;
    let deleteEntryId = null;

    // ──────────────────────────────
    //  Load data on page ready
    // ──────────────────────────────
    loadEntries();
    loadFilterMaterials();

    // ──────────────────────────────
    //  Open Add Modal
    // ──────────────────────────────
    $('#btnAddEntry, #btnAddEntryMobile').on('click', function () {
        resetModal();
        $('#stockInitialModal').removeClass('hidden');
        loadMaterialsDropdown();
    });

    // ──────────────────────────────
    //  Close Modal
    // ──────────────────────────────
    $('#btnCloseModal, #btnCancelAdd').on('click', function () {
        closeModal();
    });

    // ──────────────────────────────
    //  Auto-fill unit when material selected
    // ──────────────────────────────
    $('#material_id').on('change', function () {
        const selected = $(this).find(':selected');
        const unit = selected.data('unit');
        if (unit) {
            $('#unit').val(unit);
        }
    });

    // ──────────────────────────────
    //  Submit Form (Add / Edit)
    // ──────────────────────────────
    $('#stockInitialForm').on('submit', function (e) {
        e.preventDefault();

        const entryId = $('#edit_stock_id').val();
        const isEdit = entryId !== '';

        const payload = {
            material_id: $('#material_id').val(),
            initial_qty: $('#initial_qty').val(),
            qty_used: 0,
            unit: $('#unit').val()
        };

        if (isEdit) {
            payload.stock_id = entryId;
        }

        const url = isEdit
            ? baseUrl + 'StockInitial/Update'
            : baseUrl + 'StockInitial/Add';

        $('#btnSaveEntry').prop('disabled', true).text('Saving...');

        $.ajax({
            url: url,
            type: 'POST',
            data: JSON.stringify(payload),
            contentType: 'application/json',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    showToast(res.message, 'success');
                    closeModal();
                    loadEntries();
                } else {
                    showToast(res.message, 'error');
                }
            },
            error: function () {
                showToast('Server error. Please try again.', 'error');
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
            url: baseUrl + 'StockInitial/GetEntry/' + entryId,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    const d = res.data;
                    loadMaterialsDropdown(function () {
                        $('#edit_stock_id').val(d.stock_id);
                        $('#material_id').val(d.material_id);
                        $('#initial_qty').val(d.initial_qty);
                        $('#unit').val(d.unit);
                        $('#modalTitle').text('Edit Stock Entry');
                        $('#btnSaveEntry').text('Update');
                        $('#stockInitialModal').removeClass('hidden');
                    });
                } else {
                    showToast(res.message, 'error');
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
            url: baseUrl + 'StockInitial/Delete/' + deleteEntryId,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    showToast(res.message, 'success');
                    loadEntries();
                } else {
                    showToast(res.message, 'error');
                }
            },
            error: function () {
                showToast('Server error. Please try again.', 'error');
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
        $('#filter-material').val('');
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
            url: baseUrl + 'StockInitial/GetAll',
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
            tbody.html('<tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">No stock entries found.</td></tr>');
            return;
        }

        data.forEach(function (entry) {
            const remaining = parseFloat(entry.remaining) || 0;
            const initial = parseFloat(entry.initial_qty) || 0;
            const used = parseFloat(entry.qty_used) || 0;
            const pct = initial > 0 ? (remaining / initial * 100) : 0;

            // Health bar color
            let barColor = 'bg-emerald-400';
            if (pct <= 10) barColor = 'bg-red-500';
            else if (pct <= 30) barColor = 'bg-amber-400';

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
                    <td class="px-6 py-3">${formatNumber(initial)} ${entry.unit}</td>
                    <td class="px-6 py-3">${formatNumber(used)} ${entry.unit}</td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-2">
                            <span>${formatNumber(remaining)} ${entry.unit}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="${barColor} h-1.5 rounded-full transition-all" style="width: ${Math.min(pct, 100)}%"></div>
                        </div>
                    </td>
                    <td class="px-6 py-3">${entry.unit}</td>
                    <td class="px-6 py-3 text-xs text-gray-400">${dateStr}</td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-2">
                            <button class="btn-edit-entry text-blue-500 hover:text-blue-700" data-id="${entry.stock_id}" title="Edit">
                                <i class="fas fa-pen-to-square"></i>
                            </button>
                            <button class="btn-delete-entry text-red-500 hover:text-red-700" data-id="${entry.stock_id}" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
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
            container.html('<div class="text-center text-gray-400 py-8">No stock entries found.</div>');
            renderMobilePagination();
            return;
        }

        pageItems.forEach(function (entry) {
            const stockOnHand = parseFloat(entry.initial_qty) || 0;
            const initial = parseFloat(entry.initial_qty) || 0;
            const pct = initial > 0 ? Math.min(100, (stockOnHand / initial) * 100) : 0;

            let barColor = 'bg-emerald-400';
            if (stockOnHand <= 10) barColor = 'bg-red-500';
            else if (stockOnHand <= 50) barColor = 'bg-amber-400';

            const dateStr = entry.updated_at ? new Date(entry.updated_at).toLocaleDateString('en-PH', {
                year: 'numeric', month: 'short', day: 'numeric'
            }) : '—';

            const card = `
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-gray-900 text-sm">${entry.material_name}</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                            ${entry.category_name || '—'}
                        </span>
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <span class="block text-xs text-gray-400">Stock On Hand</span>
                            <span class="text-lg font-bold text-gray-800">${formatNumber(stockOnHand)}</span>
                            <span class="text-xs text-gray-500 ml-1">${entry.unit}</span>
                        </div>
                    </div>
                    <div class="w-full min-w-0 bg-gray-200 rounded-full h-1.5 mb-2">
                        <div class="${barColor} h-1.5 rounded-full transition-all" style="width: ${Math.max(Math.min(pct, 100), 2)}%"></div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400">${dateStr}</span>
                        <div class="flex items-center gap-3">
                            <button class="btn-edit-entry text-blue-500 hover:text-blue-700 text-sm" data-id="${entry.stock_id}" title="Edit">
                                <i class="fas fa-pen-to-square"></i>
                            </button>
                            <button class="btn-delete-entry text-red-500 hover:text-red-700 text-sm" data-id="${entry.stock_id}" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.append(card);
        });

        renderMobilePagination();
    }

    function renderMobilePagination() {
        const totalPages = Math.ceil(filteredEntries.length / itemsPerPage);
        const pag = $('#mobilePagination');
        pag.empty();

        if (totalPages <= 1) return;

        // Prev
        pag.append(`<button class="px-3 py-1 rounded text-sm ${currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-primary hover:bg-primary/10'}" 
            ${currentPage === 1 ? 'disabled' : ''} data-page="${currentPage - 1}">&laquo;</button>`);

        for (let i = 1; i <= totalPages; i++) {
            pag.append(`<button class="px-3 py-1 rounded text-sm ${i === currentPage ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100'}" 
                data-page="${i}">${i}</button>`);
        }

        // Next
        pag.append(`<button class="px-3 py-1 rounded text-sm ${currentPage === totalPages ? 'text-gray-300 cursor-not-allowed' : 'text-primary hover:bg-primary/10'}" 
            ${currentPage === totalPages ? 'disabled' : ''} data-page="${currentPage + 1}">&raquo;</button>`);

        pag.find('button').on('click', function () {
            const page = parseInt($(this).data('page'));
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                renderMobileCards();
            }
        });
    }

    function loadFilterMaterials() {
        $.ajax({
            url: baseUrl + 'StockInitial/GetMaterials',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    const select = $('#filter-material');
                    select.find('option:not(:first)').remove();
                    res.data.forEach(function (m) {
                        select.append(`<option value="${m.material_id}">${m.material_name}</option>`);
                    });
                }
            }
        });
    }

    function loadMaterialsDropdown(callback) {
        $.ajax({
            url: baseUrl + 'StockInitial/GetMaterials',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    const select = $('#material_id');
                    select.find('option:not(:first)').remove();
                    res.data.forEach(function (m) {
                        select.append(`<option value="${m.material_id}" data-unit="${m.unit}">${m.material_name}</option>`);
                    });
                    if (typeof callback === 'function') callback();
                }
            }
        });
    }

    function applyFilters() {
        const materialId = $('#filter-material').val();

        if (!materialId) {
            filteredEntries = [...allEntries];
        } else {
            filteredEntries = allEntries.filter(e => String(e.material_id) === String(materialId));
        }

        renderDesktopTable(filteredEntries);
        currentPage = 1;
        renderMobileCards();
    }

    function resetModal() {
        $('#stockInitialForm')[0].reset();
        $('#edit_stock_id').val('');
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
        return n % 1 === 0 ? n.toLocaleString() : n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function showToast(message, type) {
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-amber-500'
        };
        const bgColor = colors[type] || 'bg-gray-700';

        const toast = $(`
            <div class="fixed top-20 right-4 z-[9999] px-4 py-3 rounded-lg shadow-lg text-white text-sm ${bgColor} transition-all duration-300 transform translate-x-full opacity-0">
                ${message}
            </div>
        `);

        $('body').append(toast);

        // Animate in
        setTimeout(() => toast.removeClass('translate-x-full opacity-0'), 50);

        // Animate out
        setTimeout(() => {
            toast.addClass('translate-x-full opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});
