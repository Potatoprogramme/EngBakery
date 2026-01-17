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
                    <li class="text-gray-700">Product</li>
                </ol>
            </nav>
            <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                <div class="flex flex-wrap items-center justify-between w-full gap-2">
                    <h2 class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">Product Lists</h2>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="btnAddMaterial" class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            Add Product
                        </button>
                        <button type="button" id="btnExport" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
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
                            <select id="filter-category" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:ring-1 focus:ring-primary">
                                <option value="">All Product Categories</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-center sm:justify-end">
                        <button id="apply-filters" type="button" class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">Apply</button>
                        <button id="reset-filters" type="button" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Floating Add Product button for mobile -->
            <div class="fixed bottom-6 left-0 right-0 flex justify-center z-30 sm:hidden">
                <button type="button" id="btnAddMaterialMobile" class="w-5/6 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
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
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Product Category
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Quantity
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Unit
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                    </svg>
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Cost per Unit
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

    <!-- Add Product Modal -->
    <div id="addMaterialModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-4 border shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto" style="max-width: 64rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary">Add Product</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addMaterialForm">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="material_name" id="material_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" placeholder="e.g., Cafe Latte" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Product Category <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
                        <option value="">Select Category</option>
                        <option value="bread">Bread</option>
                        <option value="drinks">Drinks</option>
                    </select>
                </div>
                <div class="mb-3 p-3 border border-gray-200 rounded-md bg-gray-50">
                    <h2 class="text-center text-m font-medium">Product Ingredients</h2>
                
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Ingredients <span class="text-red-500">*</span></label>
                        <div class="flex items-center">
                            <select name="ingredient_id" id="ingredient_id" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Select Ingredient</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                            <input type="number" name="ingredient_quantity" id="ingredient_quantity" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" placeholder="100" min="1">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <select name="ingredient_unit" id="ingredient_unit" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="grams">grams</option>
                                <option value="pcs">pcs</option>
                                <option value="ml">ml</option>
                                <option value="kg">kg</option>
                                <option value="liters">liters</option>
                            </select>
                        </div>
                    </div>
                    <div class="">
                        <button type="button" id="btnAddIngredient" class="w-full px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">
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
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                        <span class="ms-3 text-sm font-medium text-gray-700">Include Combined Recipes? <span class="text-xs font-normal text-gray-500">(Optional)</span></span>
                    </label>
                </div>

                <!-- Combined Recipe Container -->
                <div id="combinedRecipeSection" class="hidden mb-4 p-4 border border-amber-200 rounded-lg bg-amber-50 combined-recipe-container">
                    <h4 class="text-sm font-semibold text-amber-800 mb-3"><i class="fas fa-layer-group me-1"></i>Combined Recipes</h4>
                    <p class="text-xs text-amber-600 mb-3">Add other recipes like Soft Dough, Fillings, etc. to combine with this product.</p>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Recipe to Combine</label>
                        <div class="flex gap-2">
                            <select id="combinedRecipeSelect" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                                <option value="">Select a recipe...</option>
                            </select>
                            <button type="button" id="btnAddCombinedRecipe" class="px-3 py-2 text-sm font-medium text-white bg-amber-500 rounded-md hover:bg-amber-600">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Qty per Unit</label>
                            <input type="number" id="combinedRecipeQty" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="e.g., 30" min="0" step="0.01">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Unit Type</label>
                            <select id="combinedRecipeUnit" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary">
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
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Costing Breakdown</h4>
                            <p class="text-xs text-gray-500">Review the cost components and tweak overhead or profit to see totals instantly.</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <span class="text-xs text-gray-500 uppercase tracking-wide">Total Cost</span>
                            <div id="totalCostDisplay" class="text-xl font-semibold text-primary">₱ 0.00</div>
                        </div>
                    </div>

                    <div class="mt-4 grid gap-3">
                        <div id="costsGrid" class="grid gap-3 sm:grid-cols-2">
                            <div id="directCostCard" class="col-span-2 p-3 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-between">
                                <span class="text-sm text-gray-600">Direct Cost</span>
                                <span id="directCostDisplay" class="text-sm font-medium text-gray-900">₱ 0.00</span>
                            </div>
                            <div id="combinedCostCard" class="hidden p-3 rounded-lg border border-gray-200 bg-amber-50 flex items-center justify-between">
                                <span class="text-sm text-gray-600">Combined Recipes Cost</span>
                                <span id="combinedCostDisplay" class="text-sm font-medium text-amber-700">₱ 0.00</span>
                            </div>
                        </div>

                        <div class="p-3 rounded-lg border border-gray-200 bg-gray-50 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <span class="text-sm text-gray-600">Overhead Cost</span>
                                <p class="text-xs text-gray-500">Enter the overhead percentage to apply.</p>
                            </div>
                            <div class="flex w-full sm:w-32">
                                <input type="number" id="overheadCost" class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="0" min="0">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">
                                    %
                                </span>
                            </div>
                        </div>
                    </div>

                    <div id="yieldComputationSection" class="mt-4 hidden">
                        <div class="border-t border-dashed border-gray-300 pt-4">
                            <h5 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Yield Computation</h5>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="p-3 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Total Yield (grams)</span>
                                    <span id="totalYieldGramsDisplay" class="text-sm font-medium text-gray-900">0 g</span>
                                </div>
                                <div class="p-3 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Unit Price per Gram</span>
                                    <span id="unitPricePerGramDisplay" class="text-sm font-medium text-gray-900">₱ 0.00</span>
                                </div>
                            </div>

                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div class="p-3 rounded-lg border border-dashed border-gray-300 bg-white">
                                    <h6 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Per Tray / Box</h6>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between gap-2">
                                            <label for="traysPerYield" class="text-sm text-gray-600">Trays/Boxes</label>
                                            <div class="flex w-32">
                                                <input type="number" id="traysPerYield" class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="0" min="0" step="1" value="0">
                                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-medium">tray</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Grams per Tray</span>
                                            <span id="gramsPerTrayDisplay" class="text-sm font-medium text-gray-900">0 g</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Unit Price per Tray</span>
                                            <span id="unitPricePerTrayDisplay" class="text-sm font-medium text-purple-600">₱ 0.00</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3 rounded-lg border border-dashed border-gray-300 bg-white">
                                    <h6 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Per Piece / Slice / Plate</h6>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between gap-2">
                                            <label for="piecesPerYield" id="piecesLabel" class="text-sm text-gray-600">Pieces/Slices/Plates</label>
                                            <div class="flex w-32">
                                                <input type="number" id="piecesPerYield" class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="0" min="0" step="1" value="0">
                                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-medium">pcs</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Grams per Piece</span>
                                            <span id="gramsPerPieceDisplay" class="text-sm font-medium text-gray-900">0 g</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Unit Price per Piece</span>
                                            <span id="unitPricePerPieceDisplay" class="text-sm font-medium text-blue-600">₱ 0.00</span>
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
                                <input type="number" id="profitMargin" class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary" placeholder="30" min="0" value="30">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">
                                    %
                                </span>
                            </div>
                        </div>
                        <div class="p-3 rounded-lg border border-gray-200 bg-green-50 flex items-center justify-between">
                            <span class="text-sm text-gray-600">Profit Amount</span>
                            <span id="profitAmountDisplay" class="text-sm font-semibold text-green-700">₱ 0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Selling Price Container -->
                <div class="mb-4 p-4 border-2 border-primary rounded-lg bg-primary/5 shadow-sm">
                    <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Target Selling Price</h4>
                    
                    <!-- Overall Selling Price -->
                    <div class="mb-4">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <label for="sellingPriceOverall" class="text-sm font-medium text-gray-700">Overall Price</label>
                            <div class="flex w-full sm:w-40">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">₱</span>
                                <input type="number" id="sellingPriceOverall" class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-r-md focus:outline-none focus:ring-1 focus:ring-primary font-semibold text-primary" placeholder="0.00" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 text-right mt-1">Recommended: <span id="recommendedPriceOverall" class="font-medium text-green-600">₱ 0.00</span></div>
                    </div>
                    
                    <!-- Per Tray Selling Price -->
                    <div id="sellingPricePerTrayRow" class="mb-4 hidden">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <label for="sellingPricePerTray" class="text-sm font-medium text-gray-700">Price per Tray</label>
                            <div class="flex w-full sm:w-40">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">₱</span>
                                <input type="number" id="sellingPricePerTray" class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-r-md focus:outline-none focus:ring-1 focus:ring-primary font-semibold text-purple-600" placeholder="0.00" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 text-right mt-1">Recommended: <span id="recommendedPricePerTray" class="font-medium text-green-600">₱ 0.00</span></div>
                    </div>
                    
                    <!-- Per Piece Selling Price -->
                    <div id="sellingPricePerPieceRow" class="hidden">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <label for="sellingPricePerPiece" class="text-sm font-medium text-gray-700">Price per Piece</label>
                            <div class="flex w-full sm:w-40">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">₱</span>
                                <input type="number" id="sellingPricePerPiece" class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-r-md focus:outline-none focus:ring-1 focus:ring-primary font-semibold text-blue-600" placeholder="0.00" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 text-right mt-1">Recommended: <span id="recommendedPricePerPiece" class="font-medium text-green-600">₱ 0.00</span></div>
                    </div>
                </div>

                <div class="flex gap-2 justify-end">
                    <button type="button" id="btnCancelAdd" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                    <button type="submit" id="btnSaveMaterial" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Save</button>
                </div>
            </form>
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

    <script>
    $(document).ready(function() {
        const baseUrl = '<?= base_url() ?>';
        let dataTable = null;

        // Load data on page load
        loadMaterials();
        loadFilterCategories();

        // Open Add Product Modal (Desktop & Mobile)
        $('#btnAddMaterial, #btnAddMaterialMobile').on('click', function() {
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
        $('#category_id').on('change', function() {
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

        // Toggle Combined Recipes
        $('#enableCombinedRecipes').on('change', function() {
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
        $('#btnCloseModal, #btnCancelAdd').on('click', function() {
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
                success: function(response) {
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
            
            allIngredientsData.forEach(function(mat) {
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
        $('#btnAddIngredient').on('click', function() {
            const select = $('#ingredient_id');
            const selectedOption = select.find('option:selected');
            const ingredientId = select.val();
            const ingredientName = selectedOption.data('name');
            const costPerUnit = parseFloat(selectedOption.data('cost')) || 0;
            const quantity = parseFloat($('#ingredient_quantity').val()) || 0;
            const unit = $('#ingredient_unit').val();
            const label = selectedOption.data('label') || 'general';

            if (!ingredientId || quantity <= 0) {
                Toast.warning('Please select an ingredient and enter a valid quantity.');
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

            // Reset ingredient inputs
            $('#ingredient_id').val('');
            $('#ingredient_quantity').val('');
        });

        // Remove Ingredient from List
        $(document).on('click', '.btn-remove-ingredient', function() {
            const index = $(this).data('index');
            const ingredientName = ingredientsList[index].name;
            Confirm.delete('Are you sure you want to remove "' + ingredientName + '" from the ingredients list?', function() {
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
            ingredientsList.forEach(function(item, index) {
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
            switch(labelLower) {
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
            const profitAmount = totalCost * (profitMargin / 100);
            const sellingPrice = totalCost + profitAmount;
            
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
        $('#overheadCost, #profitMargin, #piecesPerYield, #traysPerYield').on('input', function() {
            updateCostingDisplay();
        });
        
        // Load Products for Combined Recipes dropdown
        function loadCombinedRecipesDropdown() {
            $.ajax({
                url: baseUrl + 'Products/GetAll',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.data) {
                        console.log("All Products: " + response.data);
                        let options = '<option value="">Select a recipe...</option>';
                        response.data.forEach(function(product) {
                            options += '<option value="' + product.product_id + '" data-name="' + product.material_name + '" data-cost="' + (product.total_cost || 0) + '" data-yield="' + (product.yield_grams || 0) + '">' + product.material_name + '</option>';
                        });
                        $('#combinedRecipeSelect').html(options);
                    }
                }
            });
        }
        
        // Add Combined Recipe
        $('#btnAddCombinedRecipe').on('click', function() {
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
        $(document).on('click', '.btn-remove-combined-recipe', function() {
            const index = $(this).data('index');
            const recipeName = combinedRecipesList[index].name;
            Confirm.delete('Are you sure you want to remove "' + recipeName + '" from combined recipes?', function() {
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
            combinedRecipesList.forEach(function(item, index) {
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
        $('#material_quantity, #total_cost').on('input', function() {
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
                success: function(response) {
                    
                    console.log("All Products: ", response.data);
                    // Destroy existing DataTable first
                    if (dataTable) {
                        dataTable.destroy();
                        dataTable = null;
                    }

                    let rows = '';
                    if (response.success && response.data && response.data.length > 0) {
                        response.data.forEach(function(mat) {
                            rows += '<tr class="hover:bg-neutral-secondary-soft cursor-pointer" data-category="' + (mat.category_id || '') + '">';
                            rows += '<td class="px-6 py-4 font-medium text-heading whitespace-nowrap">' + mat.material_name + '</td>';
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
                error: function(xhr, status, error) {
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
        $('#addMaterialForm').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                material_name: $('#material_name').val(),
                category_id: $('#category_id').val(),
                unit: $('#unit').val(),
                material_quantity: $('#material_quantity').val(),
                total_cost: $('#total_cost').val()
            };

            $.ajax({
                url: baseUrl + 'Products/AddProduct',
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Toast.success('Product added successfully!');
                        closeModal();
                        loadMaterials();
                    } else {
                        Toast.error('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    Toast.error('Error adding product: ' + error);
                }
            });
        });

        // Delete Product
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this product?')) {
                $.ajax({
                    url: baseUrl + 'Products/DeleteProduct/' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Toast.success('Product deleted successfully!');
                            loadMaterials();
                        } else {
                            Toast.error('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        Toast.error('Error deleting product: ' + error);
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
    </script>
