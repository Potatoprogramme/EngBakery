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
                    <li class="text-gray-700">Product</li>
                </ol>
            </nav>
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Product Lists</h2>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="btnAddMaterial"
                            class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            Add Product
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
                            <label for="filter-category" class="sr-only">Product Category</label>
                            <select id="filter-category"
                                class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                                <option value="">All Product Categories</option>
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

            <!-- Floating Add Product button for mobile -->
            <div class="fixed bottom-6 left-0 right-0 flex justify-center z-30 sm:hidden">
                <button type="button" id="btnAddMaterialMobile"
                    class="w-5/6 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    Add Product
                </button>
            </div>

            <div class="p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 sm:mb-0">
                <table id="selection-table" class="min-w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Product Name
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Category
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Direct Cost
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Total Cost
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Selling Price
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
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

    <!-- Add Product Modal -->
    <div id="addMaterialModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-40 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-4 border shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto"
            style="max-width: 64rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary">Add Product</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addMaterialForm">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="material_name" id="material_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="e.g., Cafe Latte" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Product Category <span
                            class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        required>
                        <option value="">Select Category</option>
                        <option value="bread">Bread</option>
                        <option value="drinks">Drinks</option>
                    </select>
                </div>
                <div class="mb-3 p-3 border border-gray-200 rounded-md bg-gray-50">
                    <h2 class="text-center text-m font-medium">Product Ingredients</h2>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Ingredients <span
                                class="text-red-500">*</span></label>
                        <div class="flex items-center">
                            <select name="ingredient_id" id="ingredient_id"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Select Ingredient</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="ingredient_quantity" id="ingredient_quantity"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="100" min="0.01" step="0.01">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <select name="ingredient_unit" id="ingredient_unit"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="grams">grams</option>
                                <option value="pcs">pcs</option>
                                <option value="ml">ml</option>
                                <option value="kg">kg</option>
                                <option value="liters">liters</option>
                            </select>
                        </div>
                    </div>
                    <div class="">
                        <button type="button" id="btnAddIngredient"
                            class="w-full px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">
                            Add Ingredient
                        </button>
                    </div>
                </div>

                <!-- Ingredients List Container -->
                <div class="mb-4 p-3 border border-gray-200 rounded-md bg-gray-50">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Added Ingredients</h4>
                    <div id="ingredientsList" class="space-y-2 max-h-40 overflow-y-auto">
                        <p class="text-sm text-gray-500 text-center py-2">No ingredients added yet</p>
                    </div>
                </div>

                <!-- Combined Recipe Toggle -->
                <div class="mb-3 px-1 combined-recipe-toggle-wrapper">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="enableCombinedRecipes" class="sr-only peer">
                        <div
                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500">
                        </div>
                        <span class="ms-3 text-sm font-medium text-gray-700">Include Combined Recipes? <span
                                class="text-xs font-normal text-gray-500">(Optional)</span></span>
                    </label>
                </div>

                <!-- Combined Recipe Container -->
                <div id="combinedRecipeSection"
                    class="hidden mb-4 p-4 border border-amber-200 rounded-lg bg-amber-50 combined-recipe-container">
                    <h4 class="text-sm font-semibold text-amber-800 mb-3"><i
                            class="fas fa-layer-group me-1"></i>Combined Recipes</h4>
                    <p class="text-xs text-amber-600 mb-3">Add other recipes like Soft Dough, Fillings, etc. to combine
                        with this product.</p>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Recipe to Combine</label>
                        <div class="flex gap-2">
                            <select id="combinedRecipeSelect"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                                <option value="">Select a recipe...</option>
                            </select>
                            <button type="button" id="btnAddCombinedRecipe"
                                class="px-3 py-2 text-sm font-medium text-white bg-amber-500 rounded-md hover:bg-amber-600">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Qty per Unit</label>
                            <input type="number" id="combinedRecipeQty"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary"
                                placeholder="e.g., 30" min="0" step="0.01">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Unit Type</label>
                            <select id="combinedRecipeUnit"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary">
                                <option value="grams">grams</option>
                                <option value="pcs">pcs</option>
                                <option value="ml">ml</option>
                            </select>
                        </div>
                    </div>
                    <!-- Combined Recipes List -->
                    <div id="combinedRecipesList" class="space-y-2 max-h-32 overflow-y-auto">
                        <p class="text-xs text-amber-500 text-center py-2">No combined recipes added</p>
                    </div>
                </div>

                <!-- Costing Container -->
                <div class="mb-4 p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Costing Breakdown
                            </h4>
                            <p class="text-xs text-gray-500">Review the cost components and tweak overhead or profit to
                                see totals instantly.</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <span class="text-xs text-gray-500 uppercase tracking-wide">Total Cost</span>
                            <div id="totalCostDisplay" class="text-xl font-semibold text-primary">₱ 0.00</div>
                        </div>
                    </div>

                    <div class="mt-4 grid gap-3">
                        <div id="costsGrid" class="grid gap-3 sm:grid-cols-2">
                            <div id="directCostCard"
                                class="col-span-2 p-3 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-between">
                                <span class="text-sm text-gray-600">Direct Cost</span>
                                <span id="directCostDisplay" class="text-sm font-medium text-gray-900">₱ 0.00</span>
                            </div>
                            <div id="combinedCostCard"
                                class="hidden p-3 rounded-lg border border-gray-200 bg-amber-50 flex items-center justify-between">
                                <span class="text-sm text-gray-600">Combined Recipes Cost</span>
                                <span id="combinedCostDisplay" class="text-sm font-medium text-amber-700">₱ 0.00</span>
                            </div>
                        </div>

                        <div
                            class="p-3 rounded-lg border border-gray-200 bg-gray-50 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <span class="text-sm text-gray-600">Overhead Cost</span>
                                <p class="text-xs text-gray-500">Enter the overhead percentage to apply.</p>
                            </div>
                            <div class="flex w-full sm:w-32">
                                <input type="number" id="overheadCost"
                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                    placeholder="0" min="0">
                                <span
                                    class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">
                                    %
                                </span>
                            </div>
                        </div>
                    </div>

                    <div id="yieldComputationSection" class="mt-4 hidden">
                        <div class="border-t border-dashed border-gray-300 pt-4">
                            <h5 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Yield
                                Computation</h5>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div
                                    class="p-3 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Total Yield (grams)</span>
                                    <span id="totalYieldGramsDisplay" class="text-sm font-medium text-gray-900">0
                                        g</span>
                                </div>
                                <div
                                    class="p-3 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Unit Price per Gram</span>
                                    <span id="unitPricePerGramDisplay" class="text-sm font-medium text-gray-900">₱
                                        0.00</span>
                                </div>
                            </div>

                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div class="p-3 rounded-lg border border-dashed border-gray-300 bg-white">
                                    <h6 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Per
                                        Tray / Box</h6>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between gap-2">
                                            <label for="traysPerYield" class="text-sm text-gray-600">Trays/Boxes</label>
                                            <div class="flex w-32">
                                                <input type="number" id="traysPerYield"
                                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                                    placeholder="0" min="0" step="1" value="0">
                                                <span
                                                    class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-medium">tray</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Grams per Tray</span>
                                            <span id="gramsPerTrayDisplay" class="text-sm font-medium text-gray-900">0
                                                g</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Unit Price per Tray</span>
                                            <span id="unitPricePerTrayDisplay"
                                                class="text-sm font-medium text-purple-600">₱ 0.00</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3 rounded-lg border border-dashed border-gray-300 bg-white">
                                    <h6 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Per
                                        Piece / Slice / Plate</h6>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between gap-2">
                                            <label for="piecesPerYield" id="piecesLabel"
                                                class="text-sm text-gray-600">Pieces/Slices/Plates</label>
                                            <div class="flex w-32">
                                                <input type="number" id="piecesPerYield"
                                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                                    placeholder="0" min="0" step="1" value="0">
                                                <span
                                                    class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-medium">pcs</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Grams per Piece</span>
                                            <span id="gramsPerPieceDisplay" class="text-sm font-medium text-gray-900">0
                                                g</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Unit Price per Piece</span>
                                            <span id="unitPricePerPieceDisplay"
                                                class="text-sm font-medium text-blue-600">₱ 0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 border-t border-gray-200 pt-4 space-y-3">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <label for="profitMargin" class="text-sm text-gray-600">Profit Margin (%)</label>
                                <p class="text-xs text-gray-500">Adjust to calculate target selling price.</p>
                            </div>
                            <div class="flex w-full sm:w-28">
                                <input type="number" id="profitMargin"
                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                    placeholder="30" min="0" value="30">
                                <span
                                    class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">
                                    %
                                </span>
                            </div>
                        </div>
                        <div
                            class="p-3 rounded-lg border border-gray-200 bg-green-50 flex items-center justify-between">
                            <span class="text-sm text-gray-600">Profit Amount</span>
                            <span id="profitAmountDisplay" class="text-sm font-semibold text-green-700">₱ 0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Selling Price Container -->
                <div class="mb-4 p-4 border-2 border-primary rounded-lg bg-primary/5 shadow-sm">
                    <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Target Selling Price
                    </h4>

                    <!-- Overall Selling Price -->
                    <div class="mb-4">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <label for="sellingPriceOverall" class="text-sm font-medium text-gray-700">Overall
                                Price</label>
                            <div class="flex w-full sm:w-40">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">₱</span>
                                <input type="number" id="sellingPriceOverall"
                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-r-md focus:outline-none focus:ring-1 focus:ring-primary font-semibold text-primary"
                                    placeholder="0.00" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 text-right mt-1">Recommended: <span
                                id="recommendedPriceOverall" class="font-medium text-green-600">₱ 0.00</span></div>
                    </div>

                    <!-- Per Tray Selling Price -->
                    <div id="sellingPricePerTrayRow" class="mb-4 hidden">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <label for="sellingPricePerTray" class="text-sm font-medium text-gray-700">Price per
                                Tray</label>
                            <div class="flex w-full sm:w-40">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">₱</span>
                                <input type="number" id="sellingPricePerTray"
                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-r-md focus:outline-none focus:ring-1 focus:ring-primary font-semibold text-purple-600"
                                    placeholder="0.00" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 text-right mt-1">Recommended: <span
                                id="recommendedPricePerTray" class="font-medium text-green-600">₱ 0.00</span></div>
                    </div>

                    <!-- Per Piece Selling Price -->
                    <div id="sellingPricePerPieceRow" class="hidden">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <label for="sellingPricePerPiece" class="text-sm font-medium text-gray-700">Price per
                                Piece</label>
                            <div class="flex w-full sm:w-40">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">₱</span>
                                <input type="number" id="sellingPricePerPiece"
                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-r-md focus:outline-none focus:ring-1 focus:ring-primary font-semibold text-blue-600"
                                    placeholder="0.00" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 text-right mt-1">Recommended: <span
                                id="recommendedPricePerPiece" class="font-medium text-green-600">₱ 0.00</span></div>
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

    <!-- Edit Product Modal -->
    <div id="editProductModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-40 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-4 border shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto"
            style="max-width: 64rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary">Edit Product</h3>
                <button type="button" id="btnCloseEditModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editProductForm">
                <input type="hidden" id="edit_product_id" name="product_id">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="edit_material_name" id="edit_material_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="e.g., Cafe Latte" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Product Category <span
                            class="text-red-500">*</span></label>
                    <select name="edit_category_id" id="edit_category_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        required>
                        <option value="">Select Category</option>
                        <option value="bread">Bread</option>
                        <option value="drinks">Drinks</option>
                    </select>
                </div>
                <div class="mb-3 p-3 border border-gray-200 rounded-md bg-gray-50">
                    <h2 class="text-center text-m font-medium">Product Ingredients</h2>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Ingredients <span
                                class="text-red-500">*</span></label>
                        <div class="flex items-center">
                            <select name="edit_ingredient_id" id="edit_ingredient_id"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Select Ingredient</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="edit_ingredient_quantity" id="edit_ingredient_quantity"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="100" min="0.01" step="0.01">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <select name="edit_ingredient_unit" id="edit_ingredient_unit"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="grams">grams</option>
                                <option value="pcs">pcs</option>
                                <option value="ml">ml</option>
                                <option value="kg">kg</option>
                                <option value="liters">liters</option>
                            </select>
                        </div>
                    </div>
                    <div class="">
                        <button type="button" id="btnEditAddIngredient"
                            class="w-full px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">
                            Add Ingredient
                        </button>
                    </div>
                </div>

                <!-- Ingredients List Container -->
                <div class="mb-4 p-3 border border-gray-200 rounded-md bg-gray-50">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Added Ingredients</h4>
                    <div id="editIngredientsList" class="space-y-2 max-h-40 overflow-y-auto">
                        <p class="text-sm text-gray-500 text-center py-2">No ingredients added yet</p>
                    </div>
                </div>

                <!-- Combined Recipe Toggle -->
                <div class="mb-3 px-1 edit-combined-recipe-toggle-wrapper">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="editEnableCombinedRecipes" class="sr-only peer">
                        <div
                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500">
                        </div>
                        <span class="ms-3 text-sm font-medium text-gray-700">Include Combined Recipes? <span
                                class="text-xs font-normal text-gray-500">(Optional)</span></span>
                    </label>
                </div>

                <!-- Combined Recipe Container -->
                <div id="editCombinedRecipeSection"
                    class="hidden mb-4 p-4 border border-amber-200 rounded-lg bg-amber-50 edit-combined-recipe-container">
                    <h4 class="text-sm font-semibold text-amber-800 mb-3"><i
                            class="fas fa-layer-group me-1"></i>Combined Recipes</h4>
                    <p class="text-xs text-amber-600 mb-3">Add other recipes like Soft Dough, Fillings, etc. to combine
                        with this product.</p>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Recipe to Combine</label>
                        <div class="flex gap-2">
                            <select id="editCombinedRecipeSelect"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                                <option value="">Select a recipe...</option>
                            </select>
                            <button type="button" id="btnEditAddCombinedRecipe"
                                class="px-3 py-2 text-sm font-medium text-white bg-amber-500 rounded-md hover:bg-amber-600">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Qty per Unit</label>
                            <input type="number" id="editCombinedRecipeQty"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary"
                                placeholder="e.g., 30" min="0" step="0.01">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Unit Type</label>
                            <select id="editCombinedRecipeUnit"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary">
                                <option value="grams">grams</option>
                                <option value="pcs">pcs</option>
                                <option value="ml">ml</option>
                            </select>
                        </div>
                    </div>
                    <!-- Combined Recipes List -->
                    <div id="editCombinedRecipesList" class="space-y-2 max-h-32 overflow-y-auto">
                        <p class="text-xs text-amber-500 text-center py-2">No combined recipes added</p>
                    </div>
                </div>

                <!-- Costing Container -->
                <div class="mb-4 p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Costing Breakdown
                            </h4>
                            <p class="text-xs text-gray-500">Review the cost components and tweak overhead or profit to
                                see totals instantly.</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <span class="text-xs text-gray-500 uppercase tracking-wide">Total Cost</span>
                            <div id="editTotalCostDisplay" class="text-xl font-semibold text-primary">₱ 0.00</div>
                        </div>
                    </div>

                    <div class="mt-4 grid gap-3">
                        <div id="editCostsGrid" class="grid gap-3 sm:grid-cols-2">
                            <div id="editDirectCostCard"
                                class="col-span-2 p-3 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-between">
                                <span class="text-sm text-gray-600">Direct Cost</span>
                                <span id="editDirectCostDisplay" class="text-sm font-medium text-gray-900">₱ 0.00</span>
                            </div>
                            <div id="editCombinedCostCard"
                                class="hidden p-3 rounded-lg border border-gray-200 bg-amber-50 flex items-center justify-between">
                                <span class="text-sm text-gray-600">Combined Recipes Cost</span>
                                <span id="editCombinedCostDisplay" class="text-sm font-medium text-amber-700">₱ 0.00</span>
                            </div>
                        </div>

                        <div
                            class="p-3 rounded-lg border border-gray-200 bg-gray-50 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <span class="text-sm text-gray-600">Overhead Cost</span>
                                <p class="text-xs text-gray-500">Enter the overhead percentage to apply.</p>
                            </div>
                            <div class="flex w-full sm:w-32">
                                <input type="number" id="editOverheadCost"
                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                    placeholder="0" min="0">
                                <span
                                    class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">
                                    %
                                </span>
                            </div>
                        </div>
                    </div>

                    <div id="editYieldComputationSection" class="mt-4 hidden">
                        <div class="border-t border-dashed border-gray-300 pt-4">
                            <h5 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Yield
                                Computation</h5>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div
                                    class="p-3 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Total Yield (grams)</span>
                                    <span id="editTotalYieldGramsDisplay" class="text-sm font-medium text-gray-900">0
                                        g</span>
                                </div>
                                <div
                                    class="p-3 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Unit Price per Gram</span>
                                    <span id="editUnitPricePerGramDisplay" class="text-sm font-medium text-gray-900">₱
                                        0.00</span>
                                </div>
                            </div>

                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div class="p-3 rounded-lg border border-dashed border-gray-300 bg-white">
                                    <h6 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Per
                                        Tray / Box</h6>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between gap-2">
                                            <label for="editTraysPerYield" class="text-sm text-gray-600">Trays/Boxes</label>
                                            <div class="flex w-32">
                                                <input type="number" id="editTraysPerYield"
                                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                                    placeholder="0" min="0" step="1" value="0">
                                                <span
                                                    class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-medium">tray</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Grams per Tray</span>
                                            <span id="editGramsPerTrayDisplay" class="text-sm font-medium text-gray-900">0
                                                g</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Unit Price per Tray</span>
                                            <span id="editUnitPricePerTrayDisplay"
                                                class="text-sm font-medium text-purple-600">₱ 0.00</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3 rounded-lg border border-dashed border-gray-300 bg-white">
                                    <h6 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Per
                                        Piece / Slice / Plate</h6>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between gap-2">
                                            <label for="editPiecesPerYield" id="editPiecesLabel"
                                                class="text-sm text-gray-600">Pieces/Slices/Plates</label>
                                            <div class="flex w-32">
                                                <input type="number" id="editPiecesPerYield"
                                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                                    placeholder="0" min="0" step="1" value="0">
                                                <span
                                                    class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-medium">pcs</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Grams per Piece</span>
                                            <span id="editGramsPerPieceDisplay" class="text-sm font-medium text-gray-900">0
                                                g</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Unit Price per Piece</span>
                                            <span id="editUnitPricePerPieceDisplay"
                                                class="text-sm font-medium text-blue-600">₱ 0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 border-t border-gray-200 pt-4 space-y-3">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <label for="editProfitMargin" class="text-sm text-gray-600">Profit Margin (%)</label>
                                <p class="text-xs text-gray-500">Adjust to calculate target selling price.</p>
                            </div>
                            <div class="flex w-full sm:w-28">
                                <input type="number" id="editProfitMargin"
                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                    placeholder="30" min="0" value="30">
                                <span
                                    class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">
                                    %
                                </span>
                            </div>
                        </div>
                        <div
                            class="p-3 rounded-lg border border-gray-200 bg-green-50 flex items-center justify-between">
                            <span class="text-sm text-gray-600">Profit Amount</span>
                            <span id="editProfitAmountDisplay" class="text-sm font-semibold text-green-700">₱ 0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Selling Price Container -->
                <div class="mb-4 p-4 border-2 border-primary rounded-lg bg-primary/5 shadow-sm">
                    <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Target Selling Price
                    </h4>

                    <!-- Overall Selling Price -->
                    <div class="mb-4">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <label for="editSellingPriceOverall" class="text-sm font-medium text-gray-700">Overall
                                Price</label>
                            <div class="flex w-full sm:w-40">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">₱</span>
                                <input type="number" id="editSellingPriceOverall"
                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-r-md focus:outline-none focus:ring-1 focus:ring-primary font-semibold text-primary"
                                    placeholder="0.00" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 text-right mt-1">Recommended: <span
                                id="editRecommendedPriceOverall" class="font-medium text-green-600">₱ 0.00</span></div>
                    </div>

                    <!-- Per Tray Selling Price -->
                    <div id="editSellingPricePerTrayRow" class="mb-4 hidden">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <label for="editSellingPricePerTray" class="text-sm font-medium text-gray-700">Price per
                                Tray</label>
                            <div class="flex w-full sm:w-40">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">₱</span>
                                <input type="number" id="editSellingPricePerTray"
                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-r-md focus:outline-none focus:ring-1 focus:ring-primary font-semibold text-purple-600"
                                    placeholder="0.00" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 text-right mt-1">Recommended: <span
                                id="editRecommendedPricePerTray" class="font-medium text-green-600">₱ 0.00</span></div>
                    </div>

                    <!-- Per Piece Selling Price -->
                    <div id="editSellingPricePerPieceRow" class="hidden">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <label for="editSellingPricePerPiece" class="text-sm font-medium text-gray-700">Price per
                                Piece</label>
                            <div class="flex w-full sm:w-40">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">₱</span>
                                <input type="number" id="editSellingPricePerPiece"
                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-r-md focus:outline-none focus:ring-1 focus:ring-primary font-semibold text-blue-600"
                                    placeholder="0.00" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 text-right mt-1">Recommended: <span
                                id="editRecommendedPricePerPiece" class="font-medium text-green-600">₱ 0.00</span></div>
                    </div>
                </div>

                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelEdit"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                    <button type="submit" id="btnUpdateProduct"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Product Modal -->
    <div id="viewProductModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-40 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-6 border shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto"
            style="max-width: 48rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary"><i class="fas fa-box-open me-2"></i>Product Details</h3>
                <button type="button" id="btnCloseViewModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Product Name and Category -->
            <div class="mb-4 p-4 bg-gradient-to-r from-primary/10 to-primary/5 rounded-lg border border-primary/20">
                <h2 id="viewProductName" class="text-xl font-bold text-gray-800 mb-1">Product Name</h2>
                <span id="viewProductCategory" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/20 text-primary">
                    Category
                </span>
            </div>

            <!-- Ingredients Section -->
            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">
                    <i class="fas fa-list-ul me-1 text-primary"></i> Ingredients
                </h4>
                <div id="viewIngredientsList" class="bg-gray-50 rounded-lg border border-gray-200 divide-y divide-gray-200 max-h-48 overflow-y-auto">
                    <!-- Ingredients will be loaded here -->
                </div>
            </div>

            <!-- Combined Recipes Section (shown only if applicable) -->
            <div id="viewCombinedRecipesSection" class="mb-4 hidden">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">
                    <i class="fas fa-layer-group me-1 text-amber-500"></i> Combined Recipes
                </h4>
                <div id="viewCombinedRecipesList" class="bg-amber-50 rounded-lg border border-amber-200 divide-y divide-amber-200 max-h-32 overflow-y-auto">
                    <!-- Combined recipes will be loaded here -->
                </div>
            </div>

            <!-- Costing Breakdown -->
            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">
                    <i class="fas fa-calculator me-1 text-blue-500"></i> Costing Breakdown
                </h4>
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-3 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Direct Cost</span>
                        <span id="viewDirectCost" class="text-sm font-medium text-gray-900">₱ 0.00</span>
                    </div>
                    <div id="viewCombinedCostRow" class="flex justify-between items-center hidden">
                        <span class="text-sm text-gray-600">Combined Recipes Cost</span>
                        <span id="viewCombinedCost" class="text-sm font-medium text-amber-700">₱ 0.00</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Overhead Cost <span id="viewOverheadPercent" class="text-xs text-gray-400">(0%)</span></span>
                        <span id="viewOverheadCost" class="text-sm font-medium text-gray-900">₱ 0.00</span>
                    </div>
                    <div class="border-t border-gray-300 pt-2 flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-700">Total Cost</span>
                        <span id="viewTotalCost" class="text-sm font-bold text-primary">₱ 0.00</span>
                    </div>
                </div>
            </div>

            <!-- Yield Information (shown only for bread) -->
            <div id="viewYieldSection" class="mb-4 hidden">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">
                    <i class="fas fa-balance-scale me-1 text-green-500"></i> Yield Information
                </h4>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-3 text-center">
                        <div class="text-xs text-gray-500 uppercase">Total Yield</div>
                        <div id="viewYieldGrams" class="text-sm font-semibold text-gray-800">0 g</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-3 text-center">
                        <div class="text-xs text-gray-500 uppercase">Trays</div>
                        <div id="viewTraysPerYield" class="text-sm font-semibold text-gray-800">0</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-3 text-center">
                        <div class="text-xs text-gray-500 uppercase">Pieces</div>
                        <div id="viewPiecesPerYield" class="text-sm font-semibold text-gray-800">0</div>
                    </div>
                </div>
            </div>

            <!-- Profit & Selling Price -->
            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">
                    <i class="fas fa-tags me-1 text-green-500"></i> Profit & Selling Price
                </h4>
                <div class="bg-green-50 rounded-lg border border-green-200 p-3 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Profit Margin</span>
                        <span id="viewProfitMargin" class="text-sm font-medium text-green-700">0%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Profit Amount</span>
                        <span id="viewProfitAmount" class="text-sm font-medium text-green-700">₱ 0.00</span>
                    </div>
                    <div class="border-t border-green-300 pt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-700">Selling Price (Overall)</span>
                            <span id="viewSellingPriceOverall" class="text-lg font-bold text-green-600">₱ 0.00</span>
                        </div>
                    </div>
                    <div id="viewSellingPricePerTrayRow" class="flex justify-between items-center hidden">
                        <span class="text-sm text-gray-600">Selling Price per Tray</span>
                        <span id="viewSellingPricePerTray" class="text-sm font-semibold text-purple-600">₱ 0.00</span>
                    </div>
                    <div id="viewSellingPricePerPieceRow" class="flex justify-between items-center hidden">
                        <span class="text-sm text-gray-600">Selling Price per Piece</span>
                        <span id="viewSellingPricePerPiece" class="text-sm font-semibold text-blue-600">₱ 0.00</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 justify-end border-t border-gray-200 pt-4">
                <button type="button" id="btnViewClose"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    Close
                </button>
                <button type="button" id="btnViewEdit"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">
                    <i class="fas fa-edit me-1"></i> Edit Product
                </button>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 640px) {

            .datatable-top,
            .datatable-bottom {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                gap: 0.3rem !important;
                padding: 0.3rem 0;
            }

            .datatable-dropdown,
            .datatable-search,
            .datatable-info,
            .datatable-pagination {
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

    <script>
        $(document).ready(function () {
            const baseUrl = '<?= base_url() ?>';
            let dataTable = null;

            // Load data on page load
            loadMaterials();
            loadFilterCategories();

            // Open Add Product Modal (Desktop & Mobile)
            $('#btnAddMaterial, #btnAddMaterialMobile').on('click', function () {
                $('#addMaterialModal').removeClass('hidden');
                loadIngredients();
                loadCombinedRecipesDropdown();
                // test ingredients
                updateIngredientsListDisplay();
                updateCombinedRecipesListDisplay();
                updateCostingDisplay();
                updateUIBasedOnCategory();
            });

            // Handle category change
            $('#category_id').on('change', function () {
                const category = $(this).val();

                // Reset ingredients when category changes
                ingredientsList = [];
                combinedRecipesList = [];
                currentLabelRestriction = category; // Set restriction based on category

                updateIngredientsListDisplay();
                updateCombinedRecipesListDisplay();
                updateCostingDisplay();
                updateIngredientsDropdown();
                updateUIBasedOnCategory();
            });

            // Update UI based on selected category
            function updateUIBasedOnCategory() {
                const category = $('#category_id').val();

                if (category === 'drinks') {
                    // Hide combined recipes and yield computation for drinks
                    $('.combined-recipe-toggle-wrapper').addClass('hidden');
                    $('#enableCombinedRecipes').prop('checked', false);
                    $('#combinedRecipeSection').addClass('hidden');
                    $('#combinedCostCard').addClass('hidden');
                    $('#directCostCard').removeClass('col-span-1').addClass('col-span-2');
                    $('#yieldComputationSection').addClass('hidden');

                    // Show all unit options for drinks
                    $('#ingredient_unit option').show();
                } else if (category === 'bread') {
                    // Show combined recipes and yield computation for bread
                    $('.combined-recipe-toggle-wrapper').removeClass('hidden');

                    // Hide all units except grams for bread
                    $('#ingredient_unit option').hide();
                    $('#ingredient_unit option[value="grams"]').show();
                    $('#ingredient_unit').val('grams');
                } else {
                    // Default state when no category selected
                    $('.combined-recipe-toggle-wrapper').removeClass('hidden');
                    $('#ingredient_unit option').show();
                }
            }

            // Allow Enter key in quantity field to add ingredient
            $('#ingredient_quantity').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    $('#btnAddIngredient').click();
                }
            });

            // Toggle Combined Recipes
            $('#enableCombinedRecipes').on('change', function () {
                const isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#combinedRecipeSection').removeClass('hidden');
                    $('#combinedCostCard').removeClass('hidden');
                    $('#directCostCard').removeClass('col-span-2').addClass('col-span-1');
                } else {
                    $('#combinedRecipeSection').addClass('hidden');
                    $('#combinedCostCard').addClass('hidden');
                    $('#directCostCard').removeClass('col-span-1').addClass('col-span-2');
                }
                updateCostingDisplay();
            });

            // Close Product Modal
            $('#btnCloseModal, #btnCancelAdd').on('click', function () {
                closeModal();
            });

            // // Close modal on outside click
            // $('#addMaterialModal').on('click', function(e) {
            //     if (e.target === this) {
            //         closeModal();
            //     }
            // });

            function closeModal() {
                $('#addMaterialModal').addClass('hidden');
                $('#addMaterialForm')[0].reset();
                ingredientsList = [];
                combinedRecipesList = [];
                currentLabelRestriction = null;

                // Reset combined recipes UI
                $('#enableCombinedRecipes').prop('checked', false);
                $('#combinedRecipeSection').addClass('hidden');
                $('#combinedCostCard').addClass('hidden');
                $('#directCostCard').removeClass('col-span-1').addClass('col-span-2');

                updateIngredientsListDisplay();
                updateCombinedRecipesListDisplay();
                updateCostingDisplay();

                // Reset UI to default state
                $('.combined-recipe-container').removeClass('hidden');
                $('#ingredient_unit option').show();
            }

            // Ingredients test data
            let ingredientsList = [];

            // Combined recipes list
            let combinedRecipesList = [];

            // All ingredients data for filtering
            let allIngredientsData = [];

            // Current label restriction (null = show all, 'drinks' = show general+drinks, 'bread' = show general+bread)
            let currentLabelRestriction = null;

            // Load Ingredients (Raw Materials) for dropdown
            function loadIngredients() {
                $.ajax({
                    url: baseUrl + 'RawMaterials/GetAll',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            // Store all ingredients data for filtering
                            allIngredientsData = response.data;
                            console.log(allIngredientsData);
                            // Update dropdown based on current restriction
                            updateIngredientsDropdown();
                        }
                    }
                });
            }

            // Update ingredients dropdown based on current label restriction
            function updateIngredientsDropdown() {
                let options = '<option value="">Select Ingredient</option>';
                const category = $('#category_id').val();

                allIngredientsData.forEach(function (mat) {
                    const label = (mat.label || 'general').toLowerCase();

                    // Filter logic based on product category:
                    // - If category is 'bread', show only 'general' and 'bread' labeled ingredients
                    // - If category is 'drinks', show only 'general' and 'drinks' labeled ingredients
                    // - If no category selected, show all
                    let shouldShow = false;

                    if (!category) {
                        // No category selected, show all
                        shouldShow = true;
                    } else if (label === 'general' || label === '') {
                        // Always show general ingredients
                        shouldShow = true;
                    } else if (category === 'bread' && label === 'bread') {
                        // Show bread ingredients only for bread products
                        shouldShow = true;
                    } else if (category === 'drinks' && label === 'drinks') {
                        // Show drinks ingredients only for drinks products
                        shouldShow = true;
                    }

                    if (shouldShow) {
                        // Show label in dropdown for clarity
                        const labelDisplay = label !== 'general' ? ' [' + label + ']' : '';
                        options += '<option value="' + mat.material_id + '" data-name="' + mat.material_name + '" data-cost="' + mat.cost_per_unit + '" data-unit="' + mat.unit + '" data-label="' + label + '">' + mat.material_name + labelDisplay + '</option>';
                    }
                });
                $('#ingredient_id').html(options);
            }

            // Add Ingredient to List
            $('#btnAddIngredient').on('click', function () {
                const select = $('#ingredient_id');
                const selectedOption = select.find('option:selected');
                const ingredientId = select.val();
                const ingredientName = selectedOption.data('name');
                const costPerUnit = parseFloat(selectedOption.data('cost')) || 0;
                const quantity = parseFloat($('#ingredient_quantity').val()) || 0;
                const unit = $('#ingredient_unit').val();
                const label = selectedOption.data('label') || 'general';

                // Validation with specific messages
                if (!ingredientId) {
                    Toast.warning('Please select an ingredient from the dropdown.');
                    $('#ingredient_id').focus();
                    return;
                }
                
                if (quantity <= 0) {
                    Toast.warning('Please enter a valid quantity greater than 0.');
                    $('#ingredient_quantity').focus();
                    return;
                }

                // Check if ingredient already exists
                const existingIndex = ingredientsList.findIndex(item => item.id === ingredientId);
                if (existingIndex >= 0) {
                    Toast.warning('This ingredient is already added. Remove it first to add again.');
                    return;
                }

                const totalCost = costPerUnit * quantity;

                ingredientsList.push({
                    id: ingredientId,
                    name: ingredientName,
                    quantity: quantity,
                    unit: unit,
                    costPerUnit: costPerUnit,
                    totalCost: totalCost,
                    label: label
                });

                updateIngredientsListDisplay();
                updateCostingDisplay();

                // Reset ingredient inputs and refocus
                $('#ingredient_id').val('');
                $('#ingredient_quantity').val('');
                $('#ingredient_id').focus();
                
                Toast.success('Ingredient added successfully!');
            });

            // Remove Ingredient from List
            $(document).on('click', '.btn-remove-ingredient', function () {
                const index = $(this).data('index');
                const ingredientName = ingredientsList[index].name;
                Confirm.delete('Are you sure you want to remove "' + ingredientName + '" from the ingredients list?', function () {
                    ingredientsList.splice(index, 1);
                    updateIngredientsListDisplay();
                    updateCostingDisplay();
                });
            });

            // Update Ingredients List Display
            function updateIngredientsListDisplay() {
                if (ingredientsList.length === 0) {
                    $('#ingredientsList').html('<p class="text-sm text-gray-500 text-center py-2">No ingredients added yet</p>');
                    return;
                }

                let html = '';
                ingredientsList.forEach(function (item, index) {
                    const labelBadge = getLabelBadge(item.label);

                    html += '<div class="flex items-center justify-between p-2 border border-gray-200 rounded-md bg-white">';
                    html += '<div class="flex-1">';
                    html += '<div class="flex items-center gap-2">';
                    html += '<span class="text-sm font-medium text-gray-800">' + item.name + '</span>';
                    html += labelBadge;
                    html += '</div>';
                    html += '<div class="text-xs text-gray-500">' + item.quantity + ' ' + item.unit + ' × ₱' + item.costPerUnit.toFixed(3) + ' = ₱' + item.totalCost.toFixed(2) + '</div>';
                    html += '</div>';
                    html += '<button type="button" class="text-red-600 hover:text-red-800 btn-remove-ingredient" data-index="' + index + '" title="Remove"><i class="fas fa-times"></i></button>';
                    html += '</div>';
                });
                $('#ingredientsList').html(html);
            }

            // Get label badge HTML based on label type
            function getLabelBadge(label) {
                const labelLower = (label || 'general').toLowerCase();

                let bgColor, textColor;
                switch (labelLower) {
                    case 'drinks':
                        bgColor = 'bg-blue-100';
                        textColor = 'text-blue-700';
                        break;
                    case 'bread':
                        bgColor = 'bg-amber-100';
                        textColor = 'text-amber-700';
                        break;
                    case 'general':
                    default:
                        bgColor = 'bg-gray-100';
                        textColor = 'text-gray-600';
                        break;
                }

                return '<span class="px-1.5 py-0.5 text-xs font-medium rounded ' + bgColor + ' ' + textColor + '">' + labelLower + '</span>';
            }

            // Update Costing Display
            function updateCostingDisplay() {
                const directCost = ingredientsList.reduce((sum, item) => sum + item.totalCost, 0);
                const isCombinedEnabled = $('#enableCombinedRecipes').is(':checked');
                const combinedCost = isCombinedEnabled ? combinedRecipesList.reduce((sum, item) => sum + item.totalCost, 0) : 0;
                const overheadCost = directCost * parseFloat($('#overheadCost').val()) / 100 || 0;
                const totalCost = directCost + combinedCost + overheadCost;
                const profitMargin = parseFloat($('#profitMargin').val()) || 0;
                const targetProfit = totalCost / ((100 - profitMargin) / 100);
                const profitAmount = targetProfit - totalCost;
                const sellingPrice = targetProfit;

                // Check if all ingredients are in grams
                const allIngredientsInGrams = ingredientsList.length > 0 && ingredientsList.every(item => item.unit === 'grams');

                // Show/hide yield computation section based on whether all ingredients are in grams
                if (allIngredientsInGrams) {
                    $('#yieldComputationSection').removeClass('hidden');

                    // Auto-calculate total yield from ingredients (all in grams)
                    const totalYieldGrams = ingredientsList.reduce((sum, item) => sum + item.quantity, 0);

                    const piecesPerYield = parseFloat($('#piecesPerYield').val()) || 0;
                    const traysPerYield = parseFloat($('#traysPerYield').val()) || 0;

                    // Unit price per gram
                    const unitPricePerGram = totalYieldGrams > 0 ? totalCost / totalYieldGrams : 0;

                    // Flexible Yield Computation Logic:
                    let gramsPerPiece = 0;
                    let unitPricePerPiece = 0;
                    let gramsPerTray = 0;
                    let unitPricePerTray = 0;
                    let piecesPerTray = 0;

                    // Calculate Grams per Tray first (if Trays is filled)
                    if (traysPerYield > 0) {
                        gramsPerTray = totalYieldGrams / traysPerYield;
                        unitPricePerTray = totalCost / traysPerYield;
                    }

                    // Calculate Grams per Piece:
                    // - If Trays is filled: Pieces input means "pieces per tray", so divide Grams per Tray by Pieces
                    // - If Trays is empty: divide Total Yield directly by Pieces
                    if (piecesPerYield > 0) {
                        if (traysPerYield > 0) {
                            // Pieces input = pieces per tray
                            piecesPerTray = piecesPerYield;
                            // Divide Grams per Tray by Pieces per Tray to get Grams per Piece
                            gramsPerPiece = gramsPerTray / piecesPerTray;
                            unitPricePerPiece = unitPricePerTray / piecesPerTray;
                        } else {
                            // Divide Total Yield directly by Pieces
                            gramsPerPiece = totalYieldGrams / piecesPerYield;
                            unitPricePerPiece = totalCost / piecesPerYield;
                        }
                    }

                    // Yield displays
                    $('#totalYieldGramsDisplay').text(totalYieldGrams.toFixed(2) + ' g');
                    $('#unitPricePerGramDisplay').text('₱ ' + unitPricePerGram.toFixed(4));
                    $('#gramsPerPieceDisplay').text(gramsPerPiece > 0 ? gramsPerPiece.toFixed(2) + ' g' : '-');
                    $('#unitPricePerPieceDisplay').text(unitPricePerPiece > 0 ? '₱ ' + unitPricePerPiece.toFixed(2) : '-');
                    $('#gramsPerTrayDisplay').text(gramsPerTray > 0 ? gramsPerTray.toFixed(2) + ' g' : '-');
                    $('#unitPricePerTrayDisplay').text(unitPricePerTray > 0 ? '₱ ' + unitPricePerTray.toFixed(2) : '-');
                    $('#piecesPerTrayDisplay').text(piecesPerTray > 0 ? piecesPerTray.toFixed(0) + ' pcs' : '-');

                    // Update label based on whether Trays is filled
                    if (traysPerYield > 0) {
                        $('#piecesLabel').text('Pieces per Tray:');
                    } else {
                        $('#piecesLabel').text('Pieces/Slices/Plates:');
                    }

                    // Calculate Selling Price per Tray and per Piece (Recommended)
                    const recommendedPricePerTray = traysPerYield > 0 ? sellingPrice / traysPerYield : 0;
                    const recommendedPricePerPiece = unitPricePerPiece > 0 ? unitPricePerPiece * (1 + profitMargin / 100) : 0;

                    // Show/hide and update recommended price per tray
                    if (traysPerYield > 0) {
                        $('#sellingPricePerTrayRow').removeClass('hidden');
                        $('#recommendedPricePerTray').text('₱ ' + recommendedPricePerTray.toFixed(2));
                    } else {
                        $('#sellingPricePerTrayRow').addClass('hidden');
                    }

                    // Show/hide and update recommended price per piece
                    if (piecesPerYield > 0) {
                        $('#sellingPricePerPieceRow').removeClass('hidden');
                        $('#recommendedPricePerPiece').text('₱ ' + recommendedPricePerPiece.toFixed(2));
                    } else {
                        $('#sellingPricePerPieceRow').addClass('hidden');
                    }
                } else {
                    $('#yieldComputationSection').addClass('hidden');
                    // Hide per tray and per piece selling prices when yield computation is hidden
                    $('#sellingPricePerTrayRow').addClass('hidden');
                    $('#sellingPricePerPieceRow').addClass('hidden');
                }

                $('#directCostDisplay').text('₱ ' + directCost.toFixed(2));
                $('#combinedCostDisplay').text('₱ ' + combinedCost.toFixed(2));
                $('#totalCostDisplay').text('₱ ' + totalCost.toFixed(2));
                $('#profitAmountDisplay').text('₱ ' + profitAmount.toFixed(2));
                $('#recommendedPriceOverall').text('₱ ' + sellingPrice.toFixed(2));
            }

            // Recalculate on overhead/profit/yield change
            $('#overheadCost, #profitMargin, #piecesPerYield, #traysPerYield').on('input', function () {
                updateCostingDisplay();
            });

            // Load Products for Combined Recipes dropdown
            function loadCombinedRecipesDropdown() {
                $.ajax({
                    url: baseUrl + 'Products/GetAll',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success && response.data) {
                            console.log("All Products: " + response.data);
                            let options = '<option value="">Select a recipe...</option>';
                            response.data.forEach(function (product) {
                                options += '<option value="' + product.product_id + '" data-name="' + product.material_name + '" data-cost="' + (product.total_cost || 0) + '" data-yield="' + (product.yield_grams || 0) + '">' + product.material_name + '</option>';
                            });
                            $('#combinedRecipeSelect').html(options);
                        }
                    }
                });
            }

            // Add Combined Recipe
            $('#btnAddCombinedRecipe').on('click', function () {
                const select = $('#combinedRecipeSelect');
                const selectedOption = select.find('option:selected');
                const recipeId = select.val();
                const recipeName = selectedOption.data('name');
                const recipeTotalCost = parseFloat(selectedOption.data('cost')) || 0;
                const recipeYield = parseFloat(selectedOption.data('yield')) || 0;
                const qtyPerUnit = parseFloat($('#combinedRecipeQty').val()) || 0;
                const unitType = $('#combinedRecipeUnit').val();

                if (!recipeId) {
                    Toast.warning('Please select a recipe to combine.');
                    return;
                }

                if (qtyPerUnit <= 0) {
                    Toast.warning('Please enter a valid quantity per unit.');
                    return;
                }

                // Check if recipe already exists
                const existingIndex = combinedRecipesList.findIndex(item => item.id === recipeId);
                if (existingIndex >= 0) {
                    Toast.warning('This recipe is already added. Remove it first to add again.');
                    return;
                }

                // Calculate cost per gram from the recipe's total cost and yield
                const costPerGram = (recipeYield > 0) ? recipeTotalCost / recipeYield : 0;
                const totalCost = costPerGram * qtyPerUnit;

                combinedRecipesList.push({
                    id: recipeId,
                    name: recipeName,
                    quantity: qtyPerUnit,
                    unit: unitType,
                    costPerGram: costPerGram,
                    totalCost: totalCost
                });

                updateCombinedRecipesListDisplay();
                updateCostingDisplay();

                // Reset inputs
                $('#combinedRecipeSelect').val('');
                $('#combinedRecipeQty').val('');
            });

            // Remove Combined Recipe
            $(document).on('click', '.btn-remove-combined-recipe', function () {
                const index = $(this).data('index');
                const recipeName = combinedRecipesList[index].name;
                Confirm.delete('Are you sure you want to remove "' + recipeName + '" from combined recipes?', function () {
                    combinedRecipesList.splice(index, 1);
                    updateCombinedRecipesListDisplay();
                    updateCostingDisplay();
                });
            });

            // Update Combined Recipes List Display
            function updateCombinedRecipesListDisplay() {
                if (combinedRecipesList.length === 0) {
                    $('#combinedRecipesList').html('<p class="text-xs text-gray-500 text-center py-2">No combined recipes added</p>');
                    return;
                }

                let html = '';
                combinedRecipesList.forEach(function (item, index) {
                    html += '<div class="flex items-center justify-between p-2 border border-amber-200 rounded-md bg-white">';
                    html += '<div class="flex-1">';
                    html += '<div class="text-xs font-medium text-gray-800">' + item.name + '</div>';
                    html += '<div class="text-xs text-gray-500">' + item.quantity + ' ' + item.unit + ' × ₱' + item.costPerGram.toFixed(4) + '/g = ₱' + item.totalCost.toFixed(2) + '</div>';
                    html += '</div>';
                    html += '<button type="button" class="text-red-600 hover:text-red-800 btn-remove-combined-recipe" data-index="' + index + '" title="Remove"><i class="fas fa-times"></i></button>';
                    html += '</div>';
                });
                $('#combinedRecipesList').html(html);
            }

            // Calculate cost per unit
            $('#material_quantity, #total_cost').on('input', function () {
                const qty = parseFloat($('#material_quantity').val()) || 0;
                const cost = parseFloat($('#total_cost').val()) || 0;
                const perUnit = qty > 0 ? (cost / qty).toFixed(3) : '0.000';
                $('#cost_per_unit_display').text(perUnit);
            });

            // Load Product Categories for Filter dropdown (static enum values)
            function loadFilterCategories() {
                let options = '<option value="">All Product Categories</option>';
                options += '<option value="bread">Bread</option>';
                options += '<option value="drinks">Drinks</option>';
                $('#filter-category').html(options);
            }

            // Load products via AJAX
            function loadMaterials() {
                $.ajax({
                    url: baseUrl + 'Products/GetAll',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {

                        console.log("All Products: ", response.data);
                        // Destroy existing DataTable first
                        if (dataTable) {
                            dataTable.destroy();
                            dataTable = null;
                        }

                        let rows = '';
                        if (response.success && response.data && response.data.length > 0) {
                            response.data.forEach(function (product) {
                                rows += '<tr class="hover:bg-neutral-secondary-soft cursor-pointer product-row" data-product-id="' + product.product_id + '" data-category="' + (product.category || '') + '">';
                                rows += '<td class="px-6 py-4 font-medium text-heading whitespace-nowrap">' + product.product_name + '</td>';
                                rows += '<td class="px-6 py-4">' + (product.category || '-') + '</td>';
                                rows += '<td class="px-6 py-4">' + parseFloat(product.direct_cost || 0).toFixed(2) + '</td>';
                                rows += '<td class="px-6 py-4">' + parseFloat(product.total_cost || 0).toFixed(2) + '</td>';
                                rows += '<td class="px-6 py-4">' + parseFloat(product.selling_price || 0).toFixed(2) + '</td>';
                                rows += '<td class="px-6 py-4">';
                                rows += '<button class="text-blue-600 hover:text-blue-800 me-2 btn-edit" data-id="' + product.product_id + '" title="Edit"><i class="fas fa-edit"></i></button>';
                                rows += '<button class="text-red-600 hover:text-red-800 btn-delete" data-id="' + product.product_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
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
                                    placeholder: "Search products...",
                                    perPage: "entries per page",
                                    noRows: "No product data available",
                                    noResults: "No results match your search",
                                    info: "Showing {start} to {end} of {rows} entries"
                                },
                                perPage: 10,
                                perPageSelect: [5, 10, 25, 50]
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log('Error loading products: ' + error);
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
                                    placeholder: "Search products...",
                                    perPage: "entries per page",
                                    noRows: "No product data available",
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

            // Submit Add Product Form via AJAX
            $('#addMaterialForm').on('submit', function (e) {
                e.preventDefault();

            // Calculate costs before submission
            const directCost = ingredientsList.reduce((sum, item) => sum + item.totalCost, 0);
            const isCombinedEnabled = $('#enableCombinedRecipes').is(':checked');
            const combinedRecipeCost = isCombinedEnabled ? combinedRecipesList.reduce((sum, item) => sum + item.totalCost, 0) : 0;
            const overheadPercentage = parseFloat($('#overheadCost').val()) || 0;
            const overheadCost = directCost * (overheadPercentage / 100);
            const totalCost = directCost + combinedRecipeCost + overheadCost;
            const profitMargin = parseFloat($('#profitMargin').val()) || 0;
            const profitAmount = totalCost * (profitMargin / 100);
            
            // Calculate yield info
            const allIngredientsInGrams = ingredientsList.length > 0 && ingredientsList.every(item => item.unit === 'grams');
            const yieldGrams = allIngredientsInGrams ? ingredientsList.reduce((sum, item) => sum + item.quantity, 0) : 0;
            const traysPerYield = parseInt($('#traysPerYield').val()) || 0;
            const piecesPerYield = parseInt($('#piecesPerYield').val()) || 0;

            const formData = {
                product_name: $('#material_name').val(),
                category: $('#category_id').val(),
                overhead_cost_percentage: overheadPercentage,
                profit_margin_percentage: profitMargin,
                // Ingredients array
                ingredients: ingredientsList.map(item => ({
                    material_id: item.id,
                    quantity: item.quantity,
                    unit: item.unit,
                    cost_per_unit: item.costPerUnit,
                    total_cost: item.totalCost
                })),
                // Combined recipes array
                combined_recipes: isCombinedEnabled ? combinedRecipesList.map(item => ({
                    product_id: item.id,
                    quantity: item.quantity,
                    unit: item.unit,
                    cost_per_gram: item.costPerGram,
                    total_cost: item.totalCost
                })) : [],
                // Costing data
                direct_cost: directCost,
                combined_recipe_cost: combinedRecipeCost,
                overhead_cost_amount: overheadCost,
                total_cost: totalCost,
                profit_amount: profitAmount,
                // Selling prices
                selling_price_overall: parseFloat($('#sellingPriceOverall').val()) || (totalCost + profitAmount),
                selling_price_per_tray: parseFloat($('#sellingPricePerTray').val()) || 0,
                selling_price_per_piece: parseFloat($('#sellingPricePerPiece').val()) || 0,
                // Yield data
                yield_grams: yieldGrams,
                trays_per_yield: traysPerYield,
                pieces_per_yield: piecesPerYield
            };

            // ==================== DEBUG LOGGING ====================
            console.log('========== FORM SUBMISSION DEBUG ==========');
            console.log('Product Name:', formData.product_name);
            console.log('Category:', formData.category);
            console.log('');
            console.log('--- INGREDIENTS ---');
            console.log('Ingredients Count:', formData.ingredients.length);
            console.log('Ingredients List:', JSON.stringify(formData.ingredients, null, 2));
            console.log('');
            console.log('--- COMBINED RECIPES ---');
            console.log('Combined Recipes Enabled:', isCombinedEnabled);
            console.log('Combined Recipes Count:', formData.combined_recipes.length);
            console.log('Combined Recipes List:', JSON.stringify(formData.combined_recipes, null, 2));
            console.log('');
            console.log('--- COSTING ---');
            console.log('Direct Cost:', formData.direct_cost);
            console.log('Combined Recipe Cost:', formData.combined_recipe_cost);
            console.log('Overhead Percentage:', formData.overhead_cost_percentage);
            console.log('Overhead Cost Amount:', formData.overhead_cost_amount);
            console.log('Total Cost:', formData.total_cost);
            console.log('Profit Margin Percentage:', formData.profit_margin_percentage);
            console.log('Profit Amount:', formData.profit_amount);
            console.log('');
            console.log('--- SELLING PRICES ---');
            console.log('Selling Price Overall:', formData.selling_price_overall);
            console.log('Selling Price Per Tray:', formData.selling_price_per_tray);
            console.log('Selling Price Per Piece:', formData.selling_price_per_piece);
            console.log('');
            console.log('--- YIELD DATA ---');
            console.log('Yield Grams:', formData.yield_grams);
            console.log('Trays Per Yield:', formData.trays_per_yield);
            console.log('Pieces Per Yield:', formData.pieces_per_yield);
            console.log('');
            console.log('--- FULL FORM DATA (JSON) ---');
            console.log(JSON.stringify(formData, null, 2));
            console.log('============================================');
            // ==================== END DEBUG LOGGING ====================

            // Validate required fields
            if (!formData.product_name || formData.product_name.trim() === '') {
                Toast.error('Product name is required.');
                return;
            }
            if (!formData.category) {
                Toast.error('Product category is required.');
                return;
            }
            if (formData.ingredients.length === 0) {
                Toast.error('Please add at least one ingredient.');
                return;
            }

                $.ajax({
                    url: baseUrl + 'Products/AddProduct',
                    type: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function (response) {
                    console.log('--- AJAX RESPONSE ---');
                    console.log('Response:', JSON.stringify(response, null, 2));
                        if (response.success) {
                            Toast.success('Product added successfully!');
                            closeModal();
                            loadMaterials();
                        } else {
                            Toast.error('Error: ' + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                    console.log('--- AJAX ERROR ---');
                    console.log('Status:', status);
                    console.log('Error:', error);
                    console.log('Response Text:', xhr.responseText);
                        Toast.error('Error adding product: ' + error);
                    }
                });
            });

            // Delete Product
            $(document).on('click', '.btn-delete', function () {
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this product?')) {
                    $.ajax({
                        url: baseUrl + 'Products/DeleteProduct/' + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Toast.success('Product deleted successfully!');
                                loadMaterials();
                            } else {
                                Toast.error('Error: ' + response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            Toast.error('Error deleting product: ' + error);
                        }
                    });
                }
            });

            // Apply Filter
            $('#apply-filters').on('click', function () {
                const categoryId = $('#filter-category').val();
                $('table tbody tr').each(function () {
                    if (categoryId === '' || $(this).data('category') == categoryId) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Reset Filter
            $('#reset-filters').on('click', function () {
                $('#filter-category').val('');
                $('table tbody tr').show();
            });

            // =====================================================
            // EDIT PRODUCT MODAL FUNCTIONALITY
            // =====================================================

            // Edit modal ingredients and combined recipes lists
            let editIngredientsList = [];
            let editCombinedRecipesList = [];

            // Open Edit Product Modal
            $(document).on('click', '.btn-edit', function () {
                const productId = $(this).data('id');
                openEditModal(productId);
            });

            // Function to open edit modal and load product data
            function openEditModal(productId) {
                // Load ingredients dropdown first
                loadEditIngredients();
                loadEditCombinedRecipesDropdown();

                // Fetch product data
                $.ajax({
                    url: baseUrl + 'Products/GetProduct/' + productId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success && response.data) {
                            const product = response.data;
                            console.log('Product Data:', product);

                            // Set basic product info
                            $('#edit_product_id').val(product.product_id);
                            $('#edit_material_name').val(product.product_name);
                            $('#edit_category_id').val(product.category);

                            // Set costing values
                            $('#editOverheadCost').val(product.overhead_cost_percentage || 0);
                            $('#editProfitMargin').val(product.profit_margin_percentage || 30);

                            // Set yield values
                            $('#editTraysPerYield').val(product.trays_per_yield || 0);
                            $('#editPiecesPerYield').val(product.pieces_per_yield || 0);

                            // Set selling prices
                            $('#editSellingPriceOverall').val(product.selling_price_overall || 0);
                            $('#editSellingPricePerTray').val(product.selling_price_per_tray || 0);
                            $('#editSellingPricePerPiece').val(product.selling_price_per_piece || 0);

                            // Load ingredients
                            editIngredientsList = [];
                            if (product.ingredients && product.ingredients.length > 0) {
                                product.ingredients.forEach(function (ing) {
                                    editIngredientsList.push({
                                        id: ing.material_id,
                                        name: ing.material_name,
                                        quantity: parseFloat(ing.quantity),
                                        unit: ing.unit,
                                        costPerUnit: parseFloat(ing.cost_per_unit),
                                        totalCost: parseFloat(ing.total_cost),
                                        label: ing.label || 'general'
                                    });
                                });
                            }

                            // Load combined recipes
                            editCombinedRecipesList = [];
                            if (product.combined_recipes && product.combined_recipes.length > 0) {
                                $('#editEnableCombinedRecipes').prop('checked', true);
                                $('#editCombinedRecipeSection').removeClass('hidden');
                                $('#editCombinedCostCard').removeClass('hidden');
                                $('#editDirectCostCard').removeClass('col-span-2').addClass('col-span-1');

                                product.combined_recipes.forEach(function (recipe) {
                                    editCombinedRecipesList.push({
                                        id: recipe.product_id,
                                        name: recipe.product_name,
                                        quantity: parseFloat(recipe.quantity),
                                        unit: recipe.unit,
                                        costPerGram: parseFloat(recipe.cost_per_gram),
                                        totalCost: parseFloat(recipe.total_cost)
                                    });
                                });
                            } else {
                                $('#editEnableCombinedRecipes').prop('checked', false);
                                $('#editCombinedRecipeSection').addClass('hidden');
                                $('#editCombinedCostCard').addClass('hidden');
                                $('#editDirectCostCard').removeClass('col-span-1').addClass('col-span-2');
                            }

                            // Update UI
                            updateEditUIBasedOnCategory();
                            updateEditIngredientsListDisplay();
                            updateEditCombinedRecipesListDisplay();
                            updateEditCostingDisplay();

                            // Show modal
                            $('#editProductModal').removeClass('hidden');
                        } else {
                            Toast.error('Error loading product data: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function (xhr, status, error) {
                        Toast.error('Error loading product: ' + error);
                    }
                });
            }

            // Close Edit Modal
            $('#btnCloseEditModal, #btnCancelEdit').on('click', function () {
                closeEditModal();
            });

            function closeEditModal() {
                $('#editProductModal').addClass('hidden');
                $('#editProductForm')[0].reset();
                editIngredientsList = [];
                editCombinedRecipesList = [];

                // Reset combined recipes UI
                $('#editEnableCombinedRecipes').prop('checked', false);
                $('#editCombinedRecipeSection').addClass('hidden');
                $('#editCombinedCostCard').addClass('hidden');
                $('#editDirectCostCard').removeClass('col-span-1').addClass('col-span-2');

                updateEditIngredientsListDisplay();
                updateEditCombinedRecipesListDisplay();
                updateEditCostingDisplay();

                // Reset UI to default state
                $('.edit-combined-recipe-container').removeClass('hidden');
                $('#edit_ingredient_unit option').show();
            }

            // Handle edit category change
            $('#edit_category_id').on('change', function () {
                const category = $(this).val();

                // Reset ingredients when category changes
                editIngredientsList = [];
                editCombinedRecipesList = [];

                updateEditIngredientsListDisplay();
                updateEditCombinedRecipesListDisplay();
                updateEditCostingDisplay();
                updateEditIngredientsDropdown();
                updateEditUIBasedOnCategory();
            });

            // Update Edit UI based on selected category
            function updateEditUIBasedOnCategory() {
                const category = $('#edit_category_id').val();

                if (category === 'drinks') {
                    // Hide combined recipes and yield computation for drinks
                    $('.edit-combined-recipe-toggle-wrapper').addClass('hidden');
                    $('#editEnableCombinedRecipes').prop('checked', false);
                    $('#editCombinedRecipeSection').addClass('hidden');
                    $('#editCombinedCostCard').addClass('hidden');
                    $('#editDirectCostCard').removeClass('col-span-1').addClass('col-span-2');
                    $('#editYieldComputationSection').addClass('hidden');

                    // Show all unit options for drinks
                    $('#edit_ingredient_unit option').show();
                } else if (category === 'bread') {
                    // Show combined recipes and yield computation for bread
                    $('.edit-combined-recipe-toggle-wrapper').removeClass('hidden');

                    // Hide all units except grams for bread
                    $('#edit_ingredient_unit option').hide();
                    $('#edit_ingredient_unit option[value="grams"]').show();
                    $('#edit_ingredient_unit').val('grams');
                } else {
                    // Default state when no category selected
                    $('.edit-combined-recipe-toggle-wrapper').removeClass('hidden');
                    $('#edit_ingredient_unit option').show();
                }
            }

            // Toggle Edit Combined Recipes
            $('#editEnableCombinedRecipes').on('change', function () {
                const isChecked = $(this).is(':checked');
                if (isChecked) {
                    $('#editCombinedRecipeSection').removeClass('hidden');
                    $('#editCombinedCostCard').removeClass('hidden');
                    $('#editDirectCostCard').removeClass('col-span-2').addClass('col-span-1');
                } else {
                    $('#editCombinedRecipeSection').addClass('hidden');
                    $('#editCombinedCostCard').addClass('hidden');
                    $('#editDirectCostCard').removeClass('col-span-1').addClass('col-span-2');
                }
                updateEditCostingDisplay();
            });

            // Load Edit Ingredients (Raw Materials) for dropdown
            function loadEditIngredients() {
                $.ajax({
                    url: baseUrl + 'RawMaterials/GetAll',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            // Store all ingredients data for filtering
                            allIngredientsData = response.data;
                            // Update dropdown based on current restriction
                            updateEditIngredientsDropdown();
                        }
                    }
                });
            }

            // Update edit ingredients dropdown based on current label restriction
            function updateEditIngredientsDropdown() {
                let options = '<option value="">Select Ingredient</option>';
                const category = $('#edit_category_id').val();

                allIngredientsData.forEach(function (mat) {
                    const label = (mat.label || 'general').toLowerCase();

                    let shouldShow = false;

                    if (!category) {
                        shouldShow = true;
                    } else if (label === 'general' || label === '') {
                        shouldShow = true;
                    } else if (category === 'bread' && label === 'bread') {
                        shouldShow = true;
                    } else if (category === 'drinks' && label === 'drinks') {
                        shouldShow = true;
                    }

                    if (shouldShow) {
                        const labelDisplay = label !== 'general' ? ' [' + label + ']' : '';
                        options += '<option value="' + mat.material_id + '" data-name="' + mat.material_name + '" data-cost="' + mat.cost_per_unit + '" data-unit="' + mat.unit + '" data-label="' + label + '">' + mat.material_name + labelDisplay + '</option>';
                    }
                });
                $('#edit_ingredient_id').html(options);
            }

            // Load Edit Combined Recipes dropdown
            function loadEditCombinedRecipesDropdown() {
                $.ajax({
                    url: baseUrl + 'Products/GetAll',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success && response.data) {
                            let options = '<option value="">Select a recipe...</option>';
                            response.data.forEach(function (product) {
                                options += '<option value="' + product.product_id + '" data-name="' + product.material_name + '" data-cost="' + (product.total_cost || 0) + '" data-yield="' + (product.yield_grams || 0) + '">' + product.material_name + '</option>';
                            });
                            $('#editCombinedRecipeSelect').html(options);
                        }
                    }
                });
            }

            // Allow Enter key in edit quantity field to add ingredient
            $('#edit_ingredient_quantity').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#btnEditAddIngredient').click();
                }
            });

            // Add Ingredient to Edit List
            $('#btnEditAddIngredient').on('click', function () {
                const select = $('#edit_ingredient_id');
                const selectedOption = select.find('option:selected');
                const ingredientId = select.val();
                const ingredientName = selectedOption.data('name');
                const costPerUnit = parseFloat(selectedOption.data('cost')) || 0;
                const quantity = parseFloat($('#edit_ingredient_quantity').val()) || 0;
                const unit = $('#edit_ingredient_unit').val();
                const label = selectedOption.data('label') || 'general';

                // Validation with specific messages
                if (!ingredientId) {
                    Toast.warning('Please select an ingredient from the dropdown.');
                    $('#edit_ingredient_id').focus();
                    return;
                }

                if (quantity <= 0) {
                    Toast.warning('Please enter a valid quantity greater than 0.');
                    $('#edit_ingredient_quantity').focus();
                    return;
                }

                // Check if ingredient already exists
                const existingIndex = editIngredientsList.findIndex(item => item.id === ingredientId);
                if (existingIndex >= 0) {
                    Toast.warning('This ingredient is already added. Remove it first to add again.');
                    return;
                }

                const totalCost = costPerUnit * quantity;

                editIngredientsList.push({
                    id: ingredientId,
                    name: ingredientName,
                    quantity: quantity,
                    unit: unit,
                    costPerUnit: costPerUnit,
                    totalCost: totalCost,
                    label: label
                });

                updateEditIngredientsListDisplay();
                updateEditCostingDisplay();

                // Reset ingredient inputs and refocus
                $('#edit_ingredient_id').val('');
                $('#edit_ingredient_quantity').val('');
                $('#edit_ingredient_id').focus();

                Toast.success('Ingredient added successfully!');
            });

            // Remove Ingredient from Edit List
            $(document).on('click', '.btn-remove-edit-ingredient', function () {
                const index = $(this).data('index');
                const ingredientName = editIngredientsList[index].name;
                Confirm.delete('Are you sure you want to remove "' + ingredientName + '" from the ingredients list?', function () {
                    editIngredientsList.splice(index, 1);
                    updateEditIngredientsListDisplay();
                    updateEditCostingDisplay();
                });
            });

            // Update Edit Ingredients List Display
            function updateEditIngredientsListDisplay() {
                if (editIngredientsList.length === 0) {
                    $('#editIngredientsList').html('<p class="text-sm text-gray-500 text-center py-2">No ingredients added yet</p>');
                    return;
                }

                let html = '';
                editIngredientsList.forEach(function (item, index) {
                    const labelBadge = getLabelBadge(item.label);

                    html += '<div class="flex items-center justify-between p-2 border border-gray-200 rounded-md bg-white">';
                    html += '<div class="flex-1">';
                    html += '<div class="flex items-center gap-2">';
                    html += '<span class="text-sm font-medium text-gray-800">' + item.name + '</span>';
                    html += labelBadge;
                    html += '</div>';
                    html += '<div class="text-xs text-gray-500">' + item.quantity + ' ' + item.unit + ' × ₱' + item.costPerUnit.toFixed(3) + ' = ₱' + item.totalCost.toFixed(2) + '</div>';
                    html += '</div>';
                    html += '<button type="button" class="text-red-600 hover:text-red-800 btn-remove-edit-ingredient" data-index="' + index + '" title="Remove"><i class="fas fa-times"></i></button>';
                    html += '</div>';
                });
                $('#editIngredientsList').html(html);
            }

            // Add Combined Recipe to Edit
            $('#btnEditAddCombinedRecipe').on('click', function () {
                const select = $('#editCombinedRecipeSelect');
                const selectedOption = select.find('option:selected');
                const recipeId = select.val();
                const recipeName = selectedOption.data('name');
                const recipeTotalCost = parseFloat(selectedOption.data('cost')) || 0;
                const recipeYield = parseFloat(selectedOption.data('yield')) || 0;
                const qtyPerUnit = parseFloat($('#editCombinedRecipeQty').val()) || 0;
                const unitType = $('#editCombinedRecipeUnit').val();

                if (!recipeId) {
                    Toast.warning('Please select a recipe to combine.');
                    return;
                }

                if (qtyPerUnit <= 0) {
                    Toast.warning('Please enter a valid quantity per unit.');
                    return;
                }

                // Check if recipe already exists
                const existingIndex = editCombinedRecipesList.findIndex(item => item.id === recipeId);
                if (existingIndex >= 0) {
                    Toast.warning('This recipe is already added. Remove it first to add again.');
                    return;
                }

                const costPerGram = (recipeYield > 0) ? recipeTotalCost / recipeYield : 0;
                const totalCost = costPerGram * qtyPerUnit;

                editCombinedRecipesList.push({
                    id: recipeId,
                    name: recipeName,
                    quantity: qtyPerUnit,
                    unit: unitType,
                    costPerGram: costPerGram,
                    totalCost: totalCost
                });

                updateEditCombinedRecipesListDisplay();
                updateEditCostingDisplay();

                // Reset inputs
                $('#editCombinedRecipeSelect').val('');
                $('#editCombinedRecipeQty').val('');
            });

            // Remove Combined Recipe from Edit
            $(document).on('click', '.btn-remove-edit-combined-recipe', function () {
                const index = $(this).data('index');
                const recipeName = editCombinedRecipesList[index].name;
                Confirm.delete('Are you sure you want to remove "' + recipeName + '" from combined recipes?', function () {
                    editCombinedRecipesList.splice(index, 1);
                    updateEditCombinedRecipesListDisplay();
                    updateEditCostingDisplay();
                });
            });

            // Update Edit Combined Recipes List Display
            function updateEditCombinedRecipesListDisplay() {
                if (editCombinedRecipesList.length === 0) {
                    $('#editCombinedRecipesList').html('<p class="text-xs text-gray-500 text-center py-2">No combined recipes added</p>');
                    return;
                }

                let html = '';
                editCombinedRecipesList.forEach(function (item, index) {
                    html += '<div class="flex items-center justify-between p-2 border border-amber-200 rounded-md bg-white">';
                    html += '<div class="flex-1">';
                    html += '<div class="text-xs font-medium text-gray-800">' + item.name + '</div>';
                    html += '<div class="text-xs text-gray-500">' + item.quantity + ' ' + item.unit + ' × ₱' + item.costPerGram.toFixed(4) + '/g = ₱' + item.totalCost.toFixed(2) + '</div>';
                    html += '</div>';
                    html += '<button type="button" class="text-red-600 hover:text-red-800 btn-remove-edit-combined-recipe" data-index="' + index + '" title="Remove"><i class="fas fa-times"></i></button>';
                    html += '</div>';
                });
                $('#editCombinedRecipesList').html(html);
            }

            // Update Edit Costing Display
            function updateEditCostingDisplay() {
                const directCost = editIngredientsList.reduce((sum, item) => sum + item.totalCost, 0);
                const isCombinedEnabled = $('#editEnableCombinedRecipes').is(':checked');
                const combinedCost = isCombinedEnabled ? editCombinedRecipesList.reduce((sum, item) => sum + item.totalCost, 0) : 0;
                const overheadCost = directCost * parseFloat($('#editOverheadCost').val()) / 100 || 0;
                const totalCost = directCost + combinedCost + overheadCost;
                const profitMargin = parseFloat($('#editProfitMargin').val()) || 0;
                const targetProfit = totalCost / ((100 - profitMargin) / 100);
                const profitAmount = targetProfit - totalCost;
                const sellingPrice = targetProfit;

                // Check if all ingredients are in grams
                const allIngredientsInGrams = editIngredientsList.length > 0 && editIngredientsList.every(item => item.unit === 'grams');

                // Show/hide yield computation section
                if (allIngredientsInGrams) {
                    $('#editYieldComputationSection').removeClass('hidden');

                    const totalYieldGrams = editIngredientsList.reduce((sum, item) => sum + item.quantity, 0);
                    const piecesPerYield = parseFloat($('#editPiecesPerYield').val()) || 0;
                    const traysPerYield = parseFloat($('#editTraysPerYield').val()) || 0;

                    const unitPricePerGram = totalYieldGrams > 0 ? totalCost / totalYieldGrams : 0;

                    let gramsPerPiece = 0;
                    let unitPricePerPiece = 0;
                    let gramsPerTray = 0;
                    let unitPricePerTray = 0;
                    let piecesPerTray = 0;

                    if (traysPerYield > 0) {
                        gramsPerTray = totalYieldGrams / traysPerYield;
                        unitPricePerTray = totalCost / traysPerYield;
                    }

                    if (piecesPerYield > 0) {
                        if (traysPerYield > 0) {
                            piecesPerTray = piecesPerYield;
                            gramsPerPiece = gramsPerTray / piecesPerTray;
                            unitPricePerPiece = unitPricePerTray / piecesPerTray;
                        } else {
                            gramsPerPiece = totalYieldGrams / piecesPerYield;
                            unitPricePerPiece = totalCost / piecesPerYield;
                        }
                    }

                    // Yield displays
                    $('#editTotalYieldGramsDisplay').text(totalYieldGrams.toFixed(2) + ' g');
                    $('#editUnitPricePerGramDisplay').text('₱ ' + unitPricePerGram.toFixed(4));
                    $('#editGramsPerPieceDisplay').text(gramsPerPiece > 0 ? gramsPerPiece.toFixed(2) + ' g' : '-');
                    $('#editUnitPricePerPieceDisplay').text(unitPricePerPiece > 0 ? '₱ ' + unitPricePerPiece.toFixed(2) : '-');
                    $('#editGramsPerTrayDisplay').text(gramsPerTray > 0 ? gramsPerTray.toFixed(2) + ' g' : '-');
                    $('#editUnitPricePerTrayDisplay').text(unitPricePerTray > 0 ? '₱ ' + unitPricePerTray.toFixed(2) : '-');
                    $('#editPiecesPerTrayDisplay').text(piecesPerTray > 0 ? piecesPerTray.toFixed(0) + ' pcs' : '-');

                    // Update label based on whether Trays is filled
                    if (traysPerYield > 0) {
                        $('#editPiecesLabel').text('Pieces per Tray:');
                    } else {
                        $('#editPiecesLabel').text('Pieces/Slices/Plates:');
                    }

                    // Calculate Selling Price per Tray and per Piece (Recommended)
                    const recommendedPricePerTray = traysPerYield > 0 ? sellingPrice / traysPerYield : 0;
                    const recommendedPricePerPiece = unitPricePerPiece > 0 ? unitPricePerPiece * (1 + profitMargin / 100) : 0;

                    // Show/hide and update recommended price per tray
                    if (traysPerYield > 0) {
                        $('#editSellingPricePerTrayRow').removeClass('hidden');
                        $('#editRecommendedPricePerTray').text('₱ ' + recommendedPricePerTray.toFixed(2));
                    } else {
                        $('#editSellingPricePerTrayRow').addClass('hidden');
                    }

                    // Show/hide and update recommended price per piece
                    if (piecesPerYield > 0) {
                        $('#editSellingPricePerPieceRow').removeClass('hidden');
                        $('#editRecommendedPricePerPiece').text('₱ ' + recommendedPricePerPiece.toFixed(2));
                    } else {
                        $('#editSellingPricePerPieceRow').addClass('hidden');
                    }
                } else {
                    $('#editYieldComputationSection').addClass('hidden');
                    $('#editSellingPricePerTrayRow').addClass('hidden');
                    $('#editSellingPricePerPieceRow').addClass('hidden');
                }

                $('#editDirectCostDisplay').text('₱ ' + directCost.toFixed(2));
                $('#editCombinedCostDisplay').text('₱ ' + combinedCost.toFixed(2));
                $('#editTotalCostDisplay').text('₱ ' + totalCost.toFixed(2));
                $('#editProfitAmountDisplay').text('₱ ' + profitAmount.toFixed(2));
                $('#editRecommendedPriceOverall').text('₱ ' + sellingPrice.toFixed(2));
            }

            // Recalculate edit costing on overhead/profit/yield change
            $('#editOverheadCost, #editProfitMargin, #editPiecesPerYield, #editTraysPerYield').on('input', function () {
                updateEditCostingDisplay();
            });

            // Submit Edit Product Form via AJAX
            $('#editProductForm').on('submit', function (e) {
                e.preventDefault();

                // Calculate costs before submission
                const directCost = editIngredientsList.reduce((sum, item) => sum + item.totalCost, 0);
                const isCombinedEnabled = $('#editEnableCombinedRecipes').is(':checked');
                const combinedRecipeCost = isCombinedEnabled ? editCombinedRecipesList.reduce((sum, item) => sum + item.totalCost, 0) : 0;
                const overheadPercentage = parseFloat($('#editOverheadCost').val()) || 0;
                const overheadCost = directCost * (overheadPercentage / 100);
                const totalCost = directCost + combinedRecipeCost + overheadCost;
                const profitMargin = parseFloat($('#editProfitMargin').val()) || 0;
                const profitAmount = totalCost * (profitMargin / 100);

                // Calculate yield info
                const allIngredientsInGrams = editIngredientsList.length > 0 && editIngredientsList.every(item => item.unit === 'grams');
                const yieldGrams = allIngredientsInGrams ? editIngredientsList.reduce((sum, item) => sum + item.quantity, 0) : 0;
                const traysPerYield = parseInt($('#editTraysPerYield').val()) || 0;
                const piecesPerYield = parseInt($('#editPiecesPerYield').val()) || 0;

                const formData = {
                    product_id: $('#edit_product_id').val(),
                    product_name: $('#edit_material_name').val(),
                    category: $('#edit_category_id').val(),
                    overhead_cost_percentage: overheadPercentage,
                    profit_margin_percentage: profitMargin,
                    // Ingredients array
                    ingredients: editIngredientsList.map(item => ({
                        material_id: item.id,
                        quantity: item.quantity,
                        unit: item.unit,
                        cost_per_unit: item.costPerUnit,
                        total_cost: item.totalCost
                    })),
                    // Combined recipes array
                    combined_recipes: isCombinedEnabled ? editCombinedRecipesList.map(item => ({
                        product_id: item.id,
                        quantity: item.quantity,
                        unit: item.unit,
                        cost_per_gram: item.costPerGram,
                        total_cost: item.totalCost
                    })) : [],
                    // Costing data
                    direct_cost: directCost,
                    combined_recipe_cost: combinedRecipeCost,
                    overhead_cost_amount: overheadCost,
                    total_cost: totalCost,
                    profit_amount: profitAmount,
                    // Selling prices
                    selling_price_overall: parseFloat($('#editSellingPriceOverall').val()) || (totalCost + profitAmount),
                    selling_price_per_tray: parseFloat($('#editSellingPricePerTray').val()) || 0,
                    selling_price_per_piece: parseFloat($('#editSellingPricePerPiece').val()) || 0,
                    // Yield data
                    yield_grams: yieldGrams,
                    trays_per_yield: traysPerYield,
                    pieces_per_yield: piecesPerYield
                };

                // Debug logging
                console.log('========== EDIT FORM SUBMISSION DEBUG ==========');
                console.log('Product ID:', formData.product_id);
                console.log('Product Name:', formData.product_name);
                console.log('Category:', formData.category);
                console.log('Full Form Data:', JSON.stringify(formData, null, 2));
                console.log('================================================');

                // Validate required fields
                if (!formData.product_name || formData.product_name.trim() === '') {
                    Toast.error('Product name is required.');
                    return;
                }
                if (!formData.category) {
                    Toast.error('Product category is required.');
                    return;
                }
                if (formData.ingredients.length === 0) {
                    Toast.error('Please add at least one ingredient.');
                    return;
                }

                $.ajax({
                    url: baseUrl + 'Products/UpdateProduct',
                    type: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function (response) {
                        console.log('--- AJAX RESPONSE ---');
                        console.log('Response:', JSON.stringify(response, null, 2));
                        if (response.success) {
                            Toast.success('Product updated successfully!');
                            closeEditModal();
                            loadMaterials();
                        } else {
                            Toast.error('Error: ' + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log('--- AJAX ERROR ---');
                        console.log('Status:', status);
                        console.log('Error:', error);
                        console.log('Response Text:', xhr.responseText);
                        Toast.error('Error updating product: ' + error);
                    }
                });
            });

            // =====================================================
            // END EDIT PRODUCT MODAL FUNCTIONALITY
            // =====================================================

            // =====================================================
            // VIEW PRODUCT MODAL FUNCTIONALITY
            // =====================================================

            // Store current viewing product ID
            let currentViewProductId = null;

            // Open View Product Modal when clicking on a row (but not on action buttons)
            $(document).on('click', '.product-row', function (e) {
                console.log('=== ROW CLICK DEBUG ===');
                console.log('Row clicked!');
                console.log('Event target:', e.target);
                console.log('Target tagName:', e.target.tagName);
                console.log('Target classes:', e.target.className);
                
                // Don't open view modal if clicking on action buttons or their icons
                if ($(e.target).closest('.btn-edit, .btn-delete, button').length > 0) {
                    console.log('Click was on action button, skipping view modal');
                    return;
                }

                const productId = $(this).data('product-id');
                console.log('Product ID from row data:', productId);
                
                if (productId) {
                    openViewModal(productId);
                } else {
                    console.log('No product ID found in row');
                }
            });

            // Function to open view modal and load product data
            function openViewModal(productId) {
                console.log('Opening view modal for product ID:', productId);
                currentViewProductId = productId;

                $.ajax({
                    url: baseUrl + 'Products/GetProduct/' + productId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success && response.data) {
                            const product = response.data;
                            console.log('View Product Data:', product);

                            // Set product name and category
                            $('#viewProductName').text(product.product_name);
                            const categoryBadge = product.category === 'bread' 
                                ? '<i class="fas fa-bread-slice me-1"></i>Bread' 
                                : '<i class="fas fa-coffee me-1"></i>Drinks';
                            $('#viewProductCategory').html(categoryBadge);

                            // Populate ingredients list
                            let ingredientsHtml = '';
                            if (product.ingredients && product.ingredients.length > 0) {
                                product.ingredients.forEach(function (ing) {
                                    const totalCost = parseFloat(ing.total_cost) || 0;
                                    const costPerUnit = parseFloat(ing.cost_per_unit) || 0;
                                    ingredientsHtml += `
                                        <div class="flex justify-between items-center p-2 hover:bg-gray-100">
                                            <div>
                                                <span class="text-sm font-medium text-gray-800">${ing.material_name}</span>
                                                <div class="text-xs text-gray-500">${ing.quantity} ${ing.unit} × ₱${costPerUnit.toFixed(3)}</div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">₱ ${totalCost.toFixed(2)}</span>
                                        </div>
                                    `;
                                });
                            } else {
                                ingredientsHtml = '<div class="p-3 text-center text-sm text-gray-500">No ingredients found</div>';
                            }
                            $('#viewIngredientsList').html(ingredientsHtml);

                            // Populate combined recipes (if any)
                            if (product.combined_recipes && product.combined_recipes.length > 0) {
                                $('#viewCombinedRecipesSection').removeClass('hidden');
                                $('#viewCombinedCostRow').removeClass('hidden');
                                let combinedHtml = '';
                                product.combined_recipes.forEach(function (recipe) {
                                    const totalCost = parseFloat(recipe.total_cost) || 0;
                                    const costPerGram = parseFloat(recipe.cost_per_gram) || 0;
                                    combinedHtml += `
                                        <div class="flex justify-between items-center p-2 hover:bg-amber-100">
                                            <div>
                                                <span class="text-sm font-medium text-gray-800">${recipe.product_name}</span>
                                                <div class="text-xs text-gray-500">${recipe.quantity} ${recipe.unit} × ₱${costPerGram.toFixed(4)}/g</div>
                                            </div>
                                            <span class="text-sm font-medium text-amber-700">₱ ${totalCost.toFixed(2)}</span>
                                        </div>
                                    `;
                                });
                                $('#viewCombinedRecipesList').html(combinedHtml);
                                $('#viewCombinedCost').text('₱ ' + parseFloat(product.combined_recipe_cost || 0).toFixed(2));
                            } else {
                                $('#viewCombinedRecipesSection').addClass('hidden');
                                $('#viewCombinedCostRow').addClass('hidden');
                            }

                            // Populate costing breakdown
                            $('#viewDirectCost').text('₱ ' + parseFloat(product.direct_cost || 0).toFixed(2));
                            $('#viewOverheadPercent').text('(' + parseFloat(product.overhead_cost_percentage || 0).toFixed(0) + '%)');
                            $('#viewOverheadCost').text('₱ ' + parseFloat(product.overhead_cost_amount || 0).toFixed(2));
                            $('#viewTotalCost').text('₱ ' + parseFloat(product.total_cost || 0).toFixed(2));

                            // Populate yield information
                            const yieldGrams = parseFloat(product.yield_grams || 0);
                            const traysPerYield = parseInt(product.trays_per_yield || 0);
                            const piecesPerYield = parseInt(product.pieces_per_yield || 0);

                            if (yieldGrams > 0 || traysPerYield > 0 || piecesPerYield > 0) {
                                $('#viewYieldSection').removeClass('hidden');
                                $('#viewYieldGrams').text(yieldGrams.toFixed(2) + ' g');
                                $('#viewTraysPerYield').text(traysPerYield);
                                $('#viewPiecesPerYield').text(piecesPerYield);
                            } else {
                                $('#viewYieldSection').addClass('hidden');
                            }

                            // Populate profit and selling price
                            $('#viewProfitMargin').text(parseFloat(product.profit_margin_percentage || 0).toFixed(0) + '%');
                            $('#viewProfitAmount').text('₱ ' + parseFloat(product.profit_amount || 0).toFixed(2));
                            $('#viewSellingPriceOverall').text('₱ ' + parseFloat(product.selling_price_overall || product.selling_price || 0).toFixed(2));

                            // Show per tray/per piece prices if applicable
                            const sellingPricePerTray = parseFloat(product.selling_price_per_tray || 0);
                            const sellingPricePerPiece = parseFloat(product.selling_price_per_piece || 0);

                            if (sellingPricePerTray > 0) {
                                $('#viewSellingPricePerTrayRow').removeClass('hidden');
                                $('#viewSellingPricePerTray').text('₱ ' + sellingPricePerTray.toFixed(2));
                            } else {
                                $('#viewSellingPricePerTrayRow').addClass('hidden');
                            }

                            if (sellingPricePerPiece > 0) {
                                $('#viewSellingPricePerPieceRow').removeClass('hidden');
                                $('#viewSellingPricePerPiece').text('₱ ' + sellingPricePerPiece.toFixed(2));
                            } else {
                                $('#viewSellingPricePerPieceRow').addClass('hidden');
                            }

                            // Show modal
                            $('#viewProductModal').removeClass('hidden');
                        } else {
                            Toast.error('Error loading product details: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function (xhr, status, error) {
                        Toast.error('Error loading product: ' + error);
                    }
                });
            }

            // Close View Modal
            $('#btnCloseViewModal, #btnViewClose').on('click', function () {
                closeViewModal();
            });

            function closeViewModal() {
                $('#viewProductModal').addClass('hidden');
                currentViewProductId = null;
            }

            // Edit button in view modal - close view modal and open edit modal
            $('#btnViewEdit').on('click', function () {
                if (currentViewProductId) {
                    closeViewModal();
                    openEditModal(currentViewProductId);
                }
            });

            // =====================================================
            // END VIEW PRODUCT MODAL FUNCTIONALITY
            // =====================================================
        });
    </script>