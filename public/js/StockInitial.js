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
        // If user types after selecting, clear the selection
        if ($('#material_id').val()) {
            $('#material_id').val('');
            $('#btnClearMaterial').addClass('hidden');
        }
        showMaterialDropdown($(this).val());
    });

    $(document).on('click', '.material-option', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const unit = $(this).data('unit');

        $('#material_id').val(id);
        $('#material_search').val(name);
        $('#unit').val(unit);
        $('#btnClearMaterial').removeClass('hidden');
        hideMaterialDropdown();
        $('#initial_qty').focus();
    });

    $('#btnClearMaterial').on('click', function () {
        $('#material_id').val('');
        $('#material_search').val('');
        $(this).addClass('hidden');
        $('#material_search').focus();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#material_search, #material_dropdown').length) {
            hideMaterialDropdown();
        }
    });

    function showMaterialDropdown(searchTerm) {
        searchTerm = (searchTerm || '').toLowerCase();
        const filtered = allMaterialsData.filter(function (m) {
            return !searchTerm || m.material_name.toLowerCase().includes(searchTerm);
        });

        let html = '';
        if (filtered.length === 0) {
            html = '<div class="px-3 py-2 text-sm text-gray-500">No materials found</div>';
        } else {
            filtered.forEach(function (m) {
                html += '<div class="material-option px-3 py-2 text-sm cursor-pointer hover:bg-primary/10 border-b border-gray-100 last:border-b-0" ' +
                    'data-id="' + m.material_id + '" ' +
                    'data-name="' + m.material_name + '" ' +
                    'data-unit="' + m.unit + '">' +
                    '<span class="font-medium">' + m.material_name + '</span>' +
                    '<span class="text-xs text-gray-400 ml-2">(' + m.unit + ')</span>' +
                    '</div>';
            });
        }

        $('#material_dropdown').html(html).removeClass('hidden');
    }

    function hideMaterialDropdown() {
        $('#material_dropdown').addClass('hidden');
    }

    // ──────────────────────────────
    //  Submit Form (Add / Edit)
    // ──────────────────────────────
    $('#stockInitialForm').on('submit', function (e) {
        e.preventDefault();

        // Validate material is selected
        if (!$('#material_id').val()) {
            showToast('Please select a raw material.', 'error');
            $('#material_search').focus();
            return;
        }

        const entryId = $('#edit_stock_id').val();
        const isEdit = entryId !== '';

        const payload = {
            material_id: $('#material_id').val(),
            initial_qty: $('#initial_qty').val(),
            unit: $('#unit').val()
        };

        if (isEdit) {
            payload.stock_id = entryId;
            // Don't send qty_used on edit — preserve existing value
        } else {
            payload.qty_used = 0; // Only set to 0 for new entries
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
            url: baseUrl + 'MaterialStock/GetEntry/' + entryId,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    const d = res.data;
                    loadMaterialsList(function () {
                        $('#edit_stock_id').val(d.stock_id);
                        $('#material_id').val(d.material_id);
                        // Find the material name to display in the search input
                        const mat = allMaterialsData.find(m => String(m.material_id) === String(d.material_id));
                        if (mat) {
                            $('#material_search').val(mat.material_name);
                            $('#btnClearMaterial').removeClass('hidden');
                        }
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
            url: baseUrl + 'MaterialStock/Delete/' + deleteEntryId,
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
            tbody.html('<tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">No stock entries found.</td></tr>');
            return;
        }

        data.forEach(function (entry) {
            const initial = parseFloat(entry.initial_qty) || 0;
            const used = parseFloat(entry.qty_used) || 0;
            const remaining = Math.max(0, initial - used);
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
            const initial = parseFloat(entry.initial_qty) || 0;
            const used = parseFloat(entry.qty_used) || 0;
            const remaining = Math.max(0, initial - used);
            const pct = initial > 0 ? (remaining / initial * 100) : 0;

            let barColor = 'bg-emerald-400', barTrack = 'bg-emerald-100';
            let barW = initial > 0 ? Math.min(100, (remaining / initial) * 100) : 0;
            let remainTC = 'text-emerald-700';
            if (pct <= 10) { barColor = 'bg-red-500'; barTrack = 'bg-red-100'; remainTC = 'text-red-600'; }
            else if (pct <= 25) { barColor = 'bg-amber-400'; barTrack = 'bg-amber-100'; remainTC = 'text-amber-600'; }
            else if (pct <= 50) { barColor = 'bg-yellow-400'; barTrack = 'bg-yellow-100'; remainTC = 'text-yellow-700'; }

            const dateStr = entry.updated_at ? new Date(entry.updated_at).toLocaleDateString('en-PH', {
                year: 'numeric', month: 'short', day: 'numeric'
            }) : '—';

            const card = `
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
                            </div>
                            <div class="bg-orange-50 rounded-lg p-2">
                                <p class="text-xs text-gray-500 mb-0.5">Used</p>
                                <p class="font-semibold text-orange-600 text-sm">${formatNumber(used)} ${entry.unit}</p>
                            </div>
                            <div class="bg-emerald-50 rounded-lg p-2">
                                <p class="text-xs text-gray-500 mb-0.5">Remaining</p>
                                <p class="font-semibold ${remainTC} text-sm">${formatNumber(remaining)} ${entry.unit}</p>
                                <div class="mt-1 h-1.5 rounded-full ${barTrack} overflow-hidden"><div class="h-full rounded-full ${barColor}" style="width:${barW}%"></div></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-gray-100">
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
        $('#material_id').val('');
        $('#material_search').val('');
        $('#btnClearMaterial').addClass('hidden');
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
