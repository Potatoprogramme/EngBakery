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
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li class="text-gray-700">Raw Material</li>
                </ol>
            </nav>
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Raw Material Lists</h2>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="btnAddMaterial"
                            class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            Add Raw Material
                        </button>
                        <button type="button" id="btnExport"
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            Export
                        </button>
                    </div>
                </div>

                <!-- Divider line -->
                <div class="border-t border-gray-200 my-4"></div>

                <!-- Filters section -->
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full">
                        <div class="flex items-center gap-2 w-full">
                            <label for="filter-category" class="sr-only">Category</label>
                            <select id="filter-category"
                                class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                                <option value="">All Categories</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-center sm:justify-end">
                        <button id="apply-filters" type="button"
                            class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">Apply</button>
                        <button id="reset-filters" type="button"
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Floating Add Material button for mobile -->
            <div class="fixed bottom-6 left-0 right-0 flex justify-center z-30 sm:hidden">
                <button type="button" id="btnAddMaterialMobile"
                    class="w-5/6 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    Add Raw Material
                </button>
            </div>

            <div class="p-4 bg-white rounded-lg shadow-md mb-20 sm:mb-0">
                <table id="selection-table" class="min-w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">
                                    Material Name
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">
                                    Category
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">
                                    Label
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">
                                    Quantity
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                <span class="flex items-center">
                                    Unit
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap min-w-[120px]">
                                <span class="flex items-center">
                                    Cost per Unit
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
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

    <!-- Add Material Modal -->
    <div id="addMaterialModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-4 border shadow-lg rounded-md bg-white"
            style="max-width: 32rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary" id="modalTitle">Add Raw Material</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addMaterialForm">
                <input type="hidden" id="edit_material_id" value="">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Material Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="material_name" id="material_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="e.g., Flour - All Purpose" required>
                    <p id="material_name_error" class="text-red-500 text-xs mt-1 hidden">This material name already
                        exists.</p>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Category <span
                            class="text-red-500">*</span></label>
                    <div class="flex gap-2 items-center">
                        <select name="category_id" id="category_id"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                            <option value="">Select</option>
                        </select>
                        <button type="button" id="btnManageCategories"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary"
                            title="Manage Categories">
                            Manage
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-3 mb-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="material_quantity" id="material_quantity"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="25000" min="0.01" step="0.01" required>
                        <!-- Converted quantity display -->
                        <div id="converted_quantity_display" class="text-xs text-gray-500 mt-1 hidden"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit <span
                                class="text-red-500">*</span></label>
                        <select name="unit" id="unit"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                            <option value="grams">grams</option>
                            <option value="ml">ml</option>
                            <option value="pcs">pcs</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-2 mb-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Cost <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-700 font-medium">₱</span>
                            <input type="number" name="total_cost" id="total_cost"
                                class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="1350.00" step="0.01" min="0.01" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cost per Unit</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-700 font-medium">₱</span>
                            <div id="cost_per_unit_display"
                                class="w-full pl-7 pr-3 py-2 border-gray-200 rounded-md bg-gray-50 text-gray-600">0.000
                            </div>
                        </div>
                        <!-- Converted cost display (per kg/liter) -->
                        <div id="converted_cost_display" class="text-xs text-gray-500 mt-1 hidden"></div>
                    </div>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelAdd"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                    <button type="submit" id="btnSaveMaterial"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Modal Component -->
    <?= view('MaterialCategories/CategoryModal') ?>

    <!-- External Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a89dedcb22.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    
    <!-- App Scripts -->
    <script>
        // Set base URL for JS modules
        window.BASE_URL = '<?= rtrim(site_url(), '/') ?>/';
    </script>
    <script src="<?= base_url('js/CategoryModal.js') ?>"></script>
    <script src="<?= base_url('js/RawMaterial.js') ?>"></script>