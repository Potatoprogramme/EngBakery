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

            <!-- Desktop Table View -->
            <div class="hidden sm:block p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 sm:mb-0">
                <table id="selection-table" class="min-w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Product Name
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Category
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Direct Cost
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Total Cost
                                </span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="flex items-center">
                                    Selling Price
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

            <!-- Mobile Card View -->
            <div class="sm:hidden mb-20">
                <!-- Search input for mobile -->
                <div class="mb-4">
                    <div class="relative">
                        <input type="text" id="mobileSearchInput" placeholder="Search products..." 
                            class="w-full px-4 py-2.5 pl-10 text-sm border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Cards Container -->
                <div id="mobileCardsContainer" class="space-y-3">
                    <!-- Cards will be loaded via AJAX -->
                </div>
                
                <!-- No results message -->
                <div id="mobileNoResults" class="hidden text-center py-8 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-2 text-gray-300"></i>
                    <p>No products found</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="addMaterialModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-4 border shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto"
            style="max-width: 64rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary">Add Product</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Progress Stepper -->
            <div class="mb-6">
                <div class="flex items-center w-full px-2 sm:px-4">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center min-w-[60px] sm:min-w-[100px]">
                        <div id="step1Indicator" class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-primary/10 border-2 border-primary text-primary text-xs sm:text-sm font-semibold mb-1 sm:mb-2">
                            1
                        </div>
                        <span id="step1Label" class="text-[9px] sm:text-[12px] font-medium text-primary text-center leading-tight">Product Info</span>
                    </div>
                    <!-- Connector -->
                    <div id="connector1" class="flex-1 h-0.5 bg-gray-300 -mt-5 sm:-mt-6 mx-1 sm:mx-4"></div>
                    <!-- Step 2 -->
                    <div class="flex flex-col items-center min-w-[60px] sm:min-w-[100px]">
                        <div id="step2Indicator" class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full border-2 border-gray-300 text-gray-400 text-xs sm:text-sm font-semibold mb-1 sm:mb-2">
                            2
                        </div>
                        <span id="step2Label" class="text-[9px] sm:text-[12px] font-medium text-gray-400 text-center leading-tight">Ingredients</span>
                    </div>
                    <!-- Connector -->
                    <div id="connector2" class="flex-1 h-0.5 bg-gray-300 -mt-5 sm:-mt-6 mx-1 sm:mx-4"></div>
                    <!-- Step 3 -->
                    <div class="flex flex-col items-center min-w-[60px] sm:min-w-[100px]">
                        <div id="step3Indicator" class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full border-2 border-gray-300 text-gray-400 text-xs sm:text-sm font-semibold mb-1 sm:mb-2">
                            3
                        </div>
                        <span id="step3Label" class="text-[9px] sm:text-[12px] font-medium text-gray-400 text-center leading-tight">Costing</span>
                    </div>
                </div>
            </div>

            <form id="addMaterialForm">
                <!-- STEP 1: Product Info -->
                <div id="addStep1" class="step-content">
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
                            <option value="dough">Dough</option>
                            <option value="bread">Bread</option>
                            <option value="drinks">Drinks</option>
                        </select>
                    </div>
                </div>

                <!-- STEP 2: Ingredients -->
                <div id="addStep2" class="step-content hidden">
                    <div class="mb-3 p-3 border border-gray-200 rounded-md bg-gray-50">
                        <h2 class="text-center text-m font-medium mb-3">Product Ingredients</h2>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Ingredients <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" id="ingredient_search" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                    placeholder="Search ingredient..." autocomplete="off">
                                <div id="ingredient_dropdown" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto">
                                    <!-- Dropdown items will be populated here -->
                                </div>
                                <input type="hidden" name="ingredient_id" id="ingredient_id">
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

                    <!-- Additional Ingredients Container -->
                    <div id="combinedRecipeSection"
                        class="hidden mb-4 p-4 border border-amber-200 rounded-lg bg-amber-50 combined-recipe-container">
                        <h4 class="text-sm font-semibold text-amber-800 mb-3"><i
                                class="fas fa-layer-group me-1"></i>Additional (for dough and other breads)</h4>
                        <p class="text-xs text-amber-600 mb-3">Add other recipes (e.g., Soft Dough) per piece of this product. Set up Trays/Pieces first.</p>
                        
                        <!-- Warning if no yield info -->
                        <div id="additionalYieldWarning" class="mb-3 p-2 bg-yellow-100 border border-yellow-300 rounded text-xs text-yellow-800">
                            <i class="fas fa-exclamation-triangle me-1"></i> Please set up Pieces per Yield first to enable additional recipes.
                        </div>
                        
                        <div id="additionalRecipeInputs" class="hidden">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Select Recipe to Add (per piece)</label>
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
                        </div>
                        <!-- Combined Recipes List -->
                        <div id="combinedRecipesList" class="space-y-2 max-h-32 overflow-y-auto">
                            <p class="text-xs text-amber-500 text-center py-2">No additional recipes added</p>
                        </div>
                    </div>
                        
                    <!-- Direct Cost Display for Step 2 -->
                    <div class="mb-4 p-3 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-between">
                        <span class="text-sm text-gray-600 font-medium">Direct Cost</span>
                        <span id="step2DirectCostDisplay" class="text-lg font-semibold text-primary">₱ 0.00</span>
                    </div>
                </div>

                <!-- STEP 3: Costing -->
                <div id="addStep3" class="step-content hidden">
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
                                <div id="perTraySection" class="p-3 rounded-lg border border-dashed border-gray-300 bg-white">
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
                                        <div class="flex items-center justify-between gap-2">
                                            <label for="gramsPerTray" class="text-sm text-gray-600">Grams per Tray</label>
                                            <div class="flex w-32">
                                                <input type="number" id="gramsPerTray"
                                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                                    placeholder="0" min="0" step="0.01" value="0">
                                                <span
                                                    class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-medium">g</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Unit Price per Tray</span>
                                            <span id="unitPricePerTrayDisplay"
                                                class="text-sm font-medium text-purple-600">₱ 0.00</span>
                                        </div>
                                        <div id="additionalPricePerTrayRow" class="hidden flex items-center justify-between">
                                            <span class="text-sm text-amber-600">Additional Price per Tray</span>
                                            <span id="additionalPricePerTrayDisplay"
                                                class="text-sm font-medium text-amber-600">₱ 0.00</span>
                                        </div>
                                        <div id="totalPricePerTrayRow" class="hidden flex items-center justify-between border-t border-gray-200 pt-2">
                                            <span class="text-sm text-gray-700 font-semibold">Total Price per Tray</span>
                                            <span id="totalPricePerTrayDisplay"
                                                class="text-sm font-bold text-purple-700">₱ 0.00</span>
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
                                        <div class="flex items-center justify-between gap-2">
                                            <label for="gramsPerPiece" class="text-sm text-gray-600">Grams per Piece</label>
                                            <div class="flex w-32">
                                                <input type="number" id="gramsPerPiece"
                                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                                    placeholder="0" min="0" step="0.01" value="0">
                                                <span
                                                    class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-medium">g</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Unit Price per Piece</span>
                                            <span id="unitPricePerPieceDisplay"
                                                class="text-sm font-medium text-blue-600">₱ 0.00</span>
                                        </div>
                                        <div id="additionalPricePerPieceRow" class="hidden flex items-center justify-between">
                                            <span class="text-sm text-amber-600">Additional Price per Piece</span>
                                            <span id="additionalPricePerPieceDisplay"
                                                class="text-sm font-medium text-amber-600">₱ 0.00</span>
                                        </div>
                                        <div id="totalPricePerPieceRow" class="hidden flex items-center justify-between border-t border-gray-200 pt-2">
                                            <span class="text-sm text-gray-700 font-semibold">Total Price per Piece</span>
                                            <span id="totalPricePerPieceDisplay"
                                                class="text-sm font-bold text-blue-700">₱ 0.00</span>
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
                    <h2 class="font-semibold text-gray-700 uppercase tracking-wide mb-3">Target Selling Price
                    </h2>

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
                </div>

                <!-- Navigation Buttons -->
                <div class="flex gap-2 justify-between mt-4">
                    <div>
                        <button type="button" id="btnBackStep" class="hidden px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </button>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" id="btnCancelAdd"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                        <button type="button" id="btnNextStep"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Next <i class="fas fa-arrow-right ms-1"></i></button>
                        <button type="submit" id="btnSaveMaterial"
                            class="hidden px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editProductModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-4 border shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto"
            style="max-width: 64rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary">Edit Product</h3>
                <button type="button" id="btnCloseEditModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Progress Stepper for Edit -->
            <div class="mb-6">
                <div class="flex items-center w-full px-2 sm:px-4">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center min-w-[60px] sm:min-w-[100px]">
                        <div id="editStep1Indicator" class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-primary/10 border-2 border-primary text-primary text-xs sm:text-sm font-semibold mb-1 sm:mb-2">
                            1
                        </div>
                        <span id="editStep1Label" class="text-[9px] sm:text-[12px] font-medium text-primary text-center leading-tight">Product Info</span>
                    </div>
                    <!-- Connector -->
                    <div id="editConnector1" class="flex-1 h-0.5 bg-gray-300 -mt-5 sm:-mt-6 mx-1 sm:mx-4"></div>
                    <!-- Step 2 -->
                    <div class="flex flex-col items-center min-w-[60px] sm:min-w-[100px]">
                        <div id="editStep2Indicator" class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full border-2 border-gray-300 text-gray-400 text-xs sm:text-sm font-semibold mb-1 sm:mb-2">
                            2
                        </div>
                        <span id="editStep2Label" class="text-[9px] sm:text-[12px] font-medium text-gray-400 text-center leading-tight">Ingredients</span>
                    </div>
                    <!-- Connector -->
                    <div id="editConnector2" class="flex-1 h-0.5 bg-gray-300 -mt-5 sm:-mt-6 mx-1 sm:mx-4"></div>
                    <!-- Step 3 -->
                    <div class="flex flex-col items-center min-w-[60px] sm:min-w-[100px]">
                        <div id="editStep3Indicator" class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full border-2 border-gray-300 text-gray-400 text-xs sm:text-sm font-semibold mb-1 sm:mb-2">
                            3
                        </div>
                        <span id="editStep3Label" class="text-[9px] sm:text-[12px] font-medium text-gray-400 text-center leading-tight">Costing</span>
                    </div>
                </div>
            </div>

            <form id="editProductForm">
                <input type="hidden" id="edit_product_id" name="product_id">
                
                <!-- EDIT STEP 1: Product Info -->
                <div id="editStep1" class="edit-step-content">
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
                            <option value="dough">Dough</option>
                            <option value="bread">Bread</option>
                            <option value="drinks">Drinks</option>
                        </select>
                    </div>
                </div>

                <!-- EDIT STEP 2: Ingredients -->
                <div id="editStep2" class="edit-step-content hidden">
                    <div class="mb-3 p-3 border border-gray-200 rounded-md bg-gray-50">
                        <h2 class="text-center text-m font-medium">Product Ingredients</h2>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Ingredients <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" id="edit_ingredient_search" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                    placeholder="Search ingredient..." autocomplete="off">
                                <div id="edit_ingredient_dropdown" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto">
                                    <!-- Dropdown items will be populated here -->
                                </div>
                                <input type="hidden" name="edit_ingredient_id" id="edit_ingredient_id">
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

                    <!-- Direct Cost Display for Step 2 -->
                    <div class="mb-4 p-3 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-between">
                        <span class="text-sm text-gray-600 font-medium">Direct Cost</span>
                        <span id="editStep2DirectCostDisplay" class="text-sm font-semibold text-gray-900">₱ 0.00</span>
                    </div>

                    <!-- Additional Ingredients Container -->
                    <div id="editCombinedRecipeSection"
                        class="hidden mb-4 p-4 border border-amber-200 rounded-lg bg-amber-50 edit-combined-recipe-container">
                        <h4 class="text-sm font-semibold text-amber-800 mb-3"><i
                                class="fas fa-layer-group me-1"></i>Additional (for dough and other breads)</h4>
                        <p class="text-xs text-amber-600 mb-3">Add other recipes (e.g., Soft Dough) per piece of this product. Set up Trays/Pieces first.</p>
                        
                        <!-- Warning if no yield info -->
                        <div id="editAdditionalYieldWarning" class="mb-3 p-2 bg-yellow-100 border border-yellow-300 rounded text-xs text-yellow-800">
                            <i class="fas fa-exclamation-triangle me-1"></i> Please set up Pieces per Yield first to enable additional recipes.
                        </div>
                        
                        <div id="editAdditionalRecipeInputs" class="hidden">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Select Recipe to Add (per piece)</label>
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
                        </div>
                        <!-- Combined Recipes List -->
                        <div id="editCombinedRecipesList" class="space-y-2 max-h-32 overflow-y-auto">
                            <p class="text-xs text-amber-500 text-center py-2">No additional recipes added</p>
                        </div>
                    </div>
                </div>

                <!-- EDIT STEP 3: Costing -->
                <div id="editStep3" class="edit-step-content hidden">
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
                                    <div id="editPerTraySection" class="p-3 rounded-lg border border-dashed border-gray-300 bg-white">
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
                                            <div class="flex items-center justify-between gap-2">
                                                <label for="editGramsPerTray" class="text-sm text-gray-600">Grams per Tray</label>
                                                <div class="flex w-32">
                                                    <input type="number" id="editGramsPerTray"
                                                        class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                                        placeholder="0" min="0" step="0.01" value="0">
                                                    <span
                                                        class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-medium">g</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-600">Unit Price per Tray</span>
                                                <span id="editUnitPricePerTrayDisplay"
                                                    class="text-sm font-medium text-purple-600">₱ 0.00</span>
                                            </div>
                                            <div id="editAdditionalPricePerTrayRow" class="hidden flex items-center justify-between">
                                                <span class="text-sm text-amber-600">Additional Price per Tray</span>
                                                <span id="editAdditionalPricePerTrayDisplay"
                                                    class="text-sm font-medium text-amber-600">₱ 0.00</span>
                                            </div>
                                            <div id="editTotalPricePerTrayRow" class="hidden flex items-center justify-between border-t border-gray-200 pt-2">
                                                <span class="text-sm text-gray-700 font-semibold">Total Price per Tray</span>
                                                <span id="editTotalPricePerTrayDisplay"
                                                    class="text-sm font-bold text-purple-700">₱ 0.00</span>
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
                                            <div class="flex items-center justify-between gap-2">
                                                <label for="editGramsPerPiece" class="text-sm text-gray-600">Grams per Piece</label>
                                                <div class="flex w-32">
                                                    <input type="number" id="editGramsPerPiece"
                                                        class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                                        placeholder="0" min="0" step="0.01" value="0">
                                                    <span
                                                        class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-medium">g</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-600">Unit Price per Piece</span>
                                                <span id="editUnitPricePerPieceDisplay"
                                                    class="text-sm font-medium text-blue-600">₱ 0.00</span>
                                            </div>
                                            <div id="editAdditionalPricePerPieceRow" class="hidden flex items-center justify-between">
                                                <span class="text-sm text-amber-600">Additional Price per Piece</span>
                                                <span id="editAdditionalPricePerPieceDisplay"
                                                    class="text-sm font-medium text-amber-600">₱ 0.00</span>
                                            </div>
                                            <div id="editTotalPricePerPieceRow" class="hidden flex items-center justify-between border-t border-gray-200 pt-2">
                                                <span class="text-sm text-gray-700 font-semibold">Total Price per Piece</span>
                                                <span id="editTotalPricePerPieceDisplay"
                                                    class="text-sm font-bold text-blue-700">₱ 0.00</span>
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
                </div>

                <!-- Navigation Buttons -->
                <div class="flex gap-2 justify-between mt-4">
                    <button type="button" id="btnEditBackStep"
                        class="hidden px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </button>
                    <div class="flex gap-2 ml-auto">
                        <button type="button" id="btnCancelEdit"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                        <button type="button" id="btnEditNextStep"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">
                            Next <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                        <button type="submit" id="btnUpdateProduct"
                            class="hidden px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- View Product Modal -->
    <div id="viewProductModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="relative w-full max-w-md mx-auto p-4 sm:p-6 border shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto"
            style="max-width: 48rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-primary">Product Details</h3>
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
                    Ingredients
                </h4>
                <div id="viewIngredientsList" class="bg-gray-50 rounded-lg border border-gray-200 divide-y divide-gray-200 max-h-48 overflow-y-auto">
                    <!-- Ingredients will be loaded here -->
                </div>
            </div>

            <!-- Combined Recipes Section (shown only if applicable) -->
            <div id="viewCombinedRecipesSection" class="mb-4 hidden">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">
                    Combined Recipes
                </h4>
                <div id="viewCombinedRecipesList" class="bg-amber-50 rounded-lg border border-amber-200 divide-y divide-amber-200 max-h-32 overflow-y-auto">
                    <!-- Combined recipes will be loaded here -->
                </div>
            </div>

            <!-- Costing Breakdown -->
            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">
                    Costing Breakdown
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

            <!-- Yield Information (shown only for bread/dough) -->
            <div id="viewYieldSection" class="mb-4 hidden">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">
                    Yield Computation
                </h4>
                
                <!-- Total Yield and Price per Gram -->
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-3">
                        <div class="text-xs text-gray-500 uppercase mb-1">Total Yield</div>
                        <div id="viewYieldGrams" class="text-sm font-semibold text-gray-800">0 g</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-3">
                        <div class="text-xs text-gray-500 uppercase mb-1">Unit Price per Gram</div>
                        <div id="viewUnitPricePerGram" class="text-sm font-semibold text-gray-800">₱ 0.00</div>
                    </div>
                </div>

                <!-- Per Tray and Per Piece details -->
                <div class="grid gap-3 sm:grid-cols-2">
                    <!-- Per Tray -->
                    <div id="viewPerTraySection" class="hidden p-3 rounded-lg border border-dashed border-gray-300 bg-white">
                        <h6 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Per Tray / Box</h6>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Trays/Boxes</span>
                                <span id="viewTraysPerYield" class="font-medium text-gray-900">0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Grams per Tray</span>
                                <span id="viewGramsPerTray" class="font-medium text-gray-900">0 g</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Unit Price per Tray</span>
                                <span id="viewUnitPricePerTray" class="font-medium text-purple-600">₱ 0.00</span>
                            </div>
                            <div id="viewAdditionalPricePerTrayRow" class="hidden flex justify-between items-center">
                                <span class="text-gray-600 text-xs">Additional Price per Tray</span>
                                <span id="viewAdditionalPricePerTray" class="font-medium text-amber-600">₱ 0.00</span>
                            </div>
                            <div id="viewTotalPricePerTrayRow" class="hidden flex justify-between items-center border-t border-gray-200 pt-2">
                                <span class="text-gray-700 font-semibold">Total Price per Tray</span>
                                <span id="viewTotalPricePerTray" class="font-bold text-purple-700">₱ 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Per Piece -->
                    <div id="viewPerPieceSection" class="hidden p-3 rounded-lg border border-dashed border-gray-300 bg-white">
                        <h6 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Per Piece / Slice / Plate</h6>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600" id="viewPiecesLabelText">Pieces/Slices/Plates</span>
                                <span id="viewPiecesPerYield" class="font-medium text-gray-900">0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Grams per Piece</span>
                                <span id="viewGramsPerPiece" class="font-medium text-gray-900">0 g</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Unit Price per Piece</span>
                                <span id="viewUnitPricePerPiece" class="font-medium text-blue-600">₱ 0.00</span>
                            </div>
                            <div id="viewAdditionalPricePerPieceRow" class="hidden flex justify-between items-center">
                                <span class="text-gray-600 text-xs">Additional Price per Piece</span>
                                <span id="viewAdditionalPricePerPiece" class="font-medium text-amber-600">₱ 0.00</span>
                            </div>
                            <div id="viewTotalPricePerPieceRow" class="hidden flex justify-between items-center border-t border-gray-200 pt-2">
                                <span class="text-gray-700 font-semibold">Total Price per Piece</span>
                                <span id="viewTotalPricePerPiece" class="font-bold text-blue-700">₱ 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profit & Selling Price -->
            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">
                    Profit & Selling Price
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
        $(document).ready(function() {
            const baseUrl = '<?= base_url() ?>';
            let dataTable = null;

            // Load data on page load
            loadMaterials();
            loadFilterCategories();

            // Helper function to get category badge HTML
            function getCategoryBadge(category) {
                const cat = (category || '').toLowerCase();
                let bgColor, textColor, icon;
                
                switch(cat) {
                    case 'bread':
                        bgColor = 'bg-amber-100';
                        textColor = 'text-amber-700';
                        icon = 'fa-bread-slice';
                        break;
                    case 'dough':
                        bgColor = 'bg-orange-100';
                        textColor = 'text-orange-700';
                        icon = 'fa-circle';
                        break;
                    case 'drinks':
                        bgColor = 'bg-blue-100';
                        textColor = 'text-blue-700';
                        icon = 'fa-mug-hot';
                        break;
                    default:
                        bgColor = 'bg-gray-100';
                        textColor = 'text-gray-700';
                        icon = 'fa-box';
                }
                
                return '<span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded-full ' + bgColor + ' ' + textColor + '">' +
                       '<i class="fas ' + icon + ' text-[10px]"></i> ' + (category || '-') + '</span>';
            }

            // Mobile card menu toggle
            $(document).on('click', '.card-menu-btn', function(e) {
                e.stopPropagation();
                const $menu = $(this).siblings('.card-menu');
                
                // Close all other menus first
                $('.card-menu').not($menu).addClass('hidden');
                
                // Toggle this menu
                $menu.toggleClass('hidden');
            });

            // Close card menus when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.card-menu-btn, .card-menu').length) {
                    $('.card-menu').addClass('hidden');
                }
            });

            // View button in card menu
            $(document).on('click', '.card-view-btn', function(e) {
                e.stopPropagation();
                const productId = $(this).data('id');
                $('.card-menu').addClass('hidden');
                openViewModal(productId);
            });

            // Click on card to view (excluding menu area)
            $(document).on('click', '.product-card', function(e) {
                if (!$(e.target).closest('.card-menu-btn, .card-menu').length) {
                    const productId = $(this).data('product-id');
                    openViewModal(productId);
                }
            });

            // Mobile search functionality
            $('#mobileSearchInput').on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();
                let hasResults = false;
                
                $('.product-card').each(function() {
                    const productName = $(this).data('name') || '';
                    const category = $(this).data('category') || '';
                    
                    if (productName.includes(searchTerm) || category.toLowerCase().includes(searchTerm)) {
                        $(this).removeClass('hidden');
                        hasResults = true;
                    } else {
                        $(this).addClass('hidden');
                    }
                });
                
                if (hasResults || searchTerm === '') {
                    $('#mobileNoResults').addClass('hidden');
                } else {
                    $('#mobileNoResults').removeClass('hidden');
                }
            });

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
                
                // Reset to step 1 and update display
                currentAddStep = 1;
                updateStepDisplay();
            });

            // Track previous category for Add modal
            let addPreviousCategory = '';

            // Handle category change
            $('#category_id').on('change', function() {
                const category = $(this).val();
                const previousCategory = addPreviousCategory;

                // Only clear ingredients when switching between incompatible categories
                // (drinks use different units vs bread/dough which use grams)
                if ((previousCategory === 'bread' || previousCategory === 'dough') && category === 'drinks') {
                    ingredientsList = [];
                    combinedRecipesList = [];
                    updateIngredientsListDisplay();
                    updateCombinedRecipesListDisplay();
                }
                
                if (previousCategory === 'drinks' && (category === 'bread' || category === 'dough')) {
                    ingredientsList = [];
                    combinedRecipesList = [];
                    updateIngredientsListDisplay();
                    updateCombinedRecipesListDisplay();
                }
                
                // Update the previous category tracker
                addPreviousCategory = category;
                currentLabelRestriction = category; // Set restriction based on category

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
                    
                    // Show per tray section and enable pieces input for drinks
                    $('#perTraySection').removeClass('hidden');
                    $('#piecesPerYield').prop('disabled', false);

                    // Show all unit options for drinks
                    $('#ingredient_unit option').show();
                } else if (category === 'bread' || category === 'dough') {
                    if (category === 'bread') {
                        // Show combined recipes section for bread only
                        $('#combinedRecipeSection').removeClass('hidden');
                        $('#combinedCostCard').removeClass('hidden');
                        $('#directCostCard').removeClass('col-span-2').addClass('col-span-1');
                        
                        // Show per tray section and enable pieces input for bread
                        $('#perTraySection').removeClass('hidden');
                        $('#piecesPerYield').prop('disabled', false);
                    } else {
                        // Hide combined recipes section for dough
                        $('#combinedRecipeSection').addClass('hidden');
                        $('#combinedCostCard').addClass('hidden');
                        $('#directCostCard').removeClass('col-span-1').addClass('col-span-2');
                        
                        // Hide per tray section and disable pieces input for dough
                        $('#perTraySection').addClass('hidden');
                        $('#piecesPerYield').prop('disabled', true);
                        $('#traysPerYield').val(0);
                        $('#gramsPerTray').val(0);
                    }
                    
                    // Hide all units except grams for bread and dough
                    $('#ingredient_unit option').hide();
                    $('#ingredient_unit option[value="grams"]').show();
                    $('#ingredient_unit').val('grams');
                } else {
                    // Default state when no category selected
                    $('#combinedRecipeSection').addClass('hidden');
                    $('#combinedCostCard').addClass('hidden');
                    $('#directCostCard').removeClass('col-span-1').addClass('col-span-2');
                    $('#ingredient_unit option').show();
                    
                    // Show per tray section and enable pieces input by default
                    $('#perTraySection').removeClass('hidden');
                    $('#piecesPerYield').prop('disabled', false);
                }
            }

            // Allow Enter key in quantity field to add ingredient
            $('#ingredient_quantity').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    $('#btnAddIngredient').click();
                }
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
                addPreviousCategory = ''; // Reset the previous category tracker

                // Reset search inputs
                $('#ingredient_search').val('');
                $('#ingredient_id').val('');
                hideIngredientDropdown();

                // Reset combined recipes UI
                $('#combinedRecipeSection').addClass('hidden');
                $('#combinedCostCard').addClass('hidden');
                $('#directCostCard').removeClass('col-span-1').addClass('col-span-2');

                updateIngredientsListDisplay();
                updateCombinedRecipesListDisplay();
                updateCostingDisplay();

                // Reset UI to default state
                $('.combined-recipe-container').removeClass('hidden');
                $('#ingredient_unit option').show();

                // Reset to step 1
                currentAddStep = 1;
                updateStepDisplay();
            }

            // =====================================================
            // STEP NAVIGATION LOGIC
            // =====================================================
            let currentAddStep = 1;
            const totalSteps = 3;

            // Update step display based on current step
            function updateStepDisplay() {
                // Hide all step content
                $('#addStep1, #addStep2, #addStep3').addClass('hidden');
                
                // Show current step content
                $('#addStep' + currentAddStep).removeClass('hidden');

                // Update stepper indicators
                for (let i = 1; i <= totalSteps; i++) {
                    const indicator = $('#step' + i + 'Indicator');
                    const label = $('#step' + i + 'Label');

                    if (i < currentAddStep) {
                        // Completed step - solid primary background with checkmark
                        indicator.removeClass('border-2 border-gray-300 border-primary bg-primary/10 text-gray-400 text-primary')
                            .addClass('bg-primary text-white border-0');
                        indicator.html('<i class="fas fa-check"></i>');
                        label.removeClass('text-gray-400 text-primary').addClass('text-primary');
                    } else if (i === currentAddStep) {
                        // Current step - bordered circle with shaded background
                        indicator.removeClass('border-gray-300 bg-primary text-white border-0 text-gray-400')
                            .addClass('border-2 border-primary bg-primary/10 text-primary');
                        indicator.html(i);
                        label.removeClass('text-gray-400').addClass('text-primary');
                    } else {
                        // Future step - gray border and text
                        indicator.removeClass('border-primary bg-primary/10 bg-primary text-white text-primary border-0')
                            .addClass('border-2 border-gray-300 text-gray-400');
                        indicator.html(i);
                        label.removeClass('text-primary').addClass('text-gray-400');
                    }
                }

                // Update connector colors
                $('#connector1').removeClass('bg-primary bg-gray-300').addClass(currentAddStep > 1 ? 'bg-primary' : 'bg-gray-300');
                $('#connector2').removeClass('bg-primary bg-gray-300').addClass(currentAddStep > 2 ? 'bg-primary' : 'bg-gray-300');

                // Update button visibility
                if (currentAddStep === 1) {
                    $('#btnBackStep').addClass('hidden');
                    $('#btnNextStep').removeClass('hidden');
                    $('#btnSaveMaterial').addClass('hidden');
                } else if (currentAddStep === 2) {
                    $('#btnBackStep').removeClass('hidden');
                    $('#btnNextStep').removeClass('hidden');
                    $('#btnSaveMaterial').addClass('hidden');
                } else if (currentAddStep === 3) {
                    $('#btnBackStep').removeClass('hidden');
                    $('#btnNextStep').addClass('hidden');
                    $('#btnSaveMaterial').removeClass('hidden');
                }
            }

            // Next step button click
            $('#btnNextStep').on('click', function() {
                // Validate current step before proceeding
                if (currentAddStep === 1) {
                    const productName = $('#material_name').val().trim();
                    const category = $('#category_id').val();

                    if (!productName) {
                        Toast.warning('Please enter a product name.');
                        $('#material_name').focus();
                        return;
                    }
                    if (!category) {
                        Toast.warning('Please select a product category.');
                        $('#category_id').focus();
                        return;
                    }
                }

                if (currentAddStep === 2) {
                    if (ingredientsList.length === 0) {
                        Toast.warning('Please add at least one ingredient.');
                        return;
                    }
                }

                if (currentAddStep < totalSteps) {
                    currentAddStep++;
                    updateStepDisplay();
                    
                    // Scroll modal to top
                    $('#addMaterialModal .overflow-y-auto').scrollTop(0);
                }
            });

            // Back step button click
            $('#btnBackStep').on('click', function() {
                if (currentAddStep > 1) {
                    currentAddStep--;
                    updateStepDisplay();
                    
                    // Scroll modal to top
                    $('#addMaterialModal .overflow-y-auto').scrollTop(0);
                }
            });

            // Initialize step display when modal opens
            // (This is handled in the open modal click handler)

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
                // Clear search input when dropdown is updated
                $('#ingredient_search').val('');
                $('#ingredient_id').val('');
            }

            // Get filtered ingredients based on category and search term
            function getFilteredIngredients(searchTerm = '') {
                const category = $('#category_id').val();
                const filtered = [];

                allIngredientsData.forEach(function(mat) {
                    const label = (mat.label || 'general').toLowerCase();

                    // Filter logic based on product category
                    let shouldShow = false;

                    if (!category) {
                        shouldShow = true;
                    } else if (label === 'general' || label === '') {
                        shouldShow = true;
                    } else if ((category === 'bread' || category === 'dough') && label === 'bread' || label === 'dough') {
                        shouldShow = true;
                    } else if (category === 'drinks' && label === 'drinks') {
                        shouldShow = true;
                    }

                    // Apply search filter
                    if (shouldShow && searchTerm) {
                        const name = mat.material_name.toLowerCase();
                        shouldShow = name.includes(searchTerm.toLowerCase());
                    }

                    if (shouldShow) {
                        filtered.push(mat);
                    }
                });

                return filtered;
            }

            // Show ingredient dropdown with filtered results
            function showIngredientDropdown(searchTerm = '') {
                const filtered = getFilteredIngredients(searchTerm);
                let html = '';

                if (filtered.length === 0) {
                    html = '<div class="px-3 py-2 text-sm text-gray-500">No ingredients found</div>';
                } else {
                    filtered.forEach(function(mat) {
                        const label = (mat.label || 'general').toLowerCase();
                        html += '<div class="ingredient-option px-3 py-2 text-sm cursor-pointer hover:bg-primary/10 border-b border-gray-100 last:border-b-0" ' +
                            'data-id="' + mat.material_id + '" ' +
                            'data-name="' + mat.material_name + '" ' +
                            'data-cost="' + mat.cost_per_unit + '" ' +
                            'data-unit="' + mat.unit + '" ' +
                            'data-label="' + label + '">' +
                            '<span class="font-medium">' + mat.material_name + '</span>' +
                            '<span class="text-xs text-gray-400 ml-2">(' + label + ')</span>' +
                            '</div>';
                    });
                }

                $('#ingredient_dropdown').html(html).removeClass('hidden');
            }

            // Hide ingredient dropdown
            function hideIngredientDropdown() {
                $('#ingredient_dropdown').addClass('hidden');
            }

            // Ingredient search input events (Add Modal)
            $('#ingredient_search').on('focus', function() {
                showIngredientDropdown($(this).val());
            });

            $('#ingredient_search').on('input', function() {
                showIngredientDropdown($(this).val());
            });

            // Select ingredient from dropdown (Add Modal)
            $(document).on('click', '.ingredient-option', function() {
                const $this = $(this);
                const id = $this.data('id');
                const name = $this.data('name');
                const unit = $this.data('unit');

                $('#ingredient_id').val(id);
                $('#ingredient_search').val(name);
                $('#ingredient_unit').val(unit);
                hideIngredientDropdown();
                $('#ingredient_quantity').focus();
            });

            // Hide dropdown when clicking outside (Add Modal)
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#ingredient_search, #ingredient_dropdown').length) {
                    hideIngredientDropdown();
                }
                if (!$(e.target).closest('#edit_ingredient_search, #edit_ingredient_dropdown').length) {
                    hideEditIngredientDropdown();
                }
            })

            // Auto-select unit is now handled in the ingredient-option click event above

            // Add Ingredient to List
            $('#btnAddIngredient').on('click', function() {
                const ingredientId = $('#ingredient_id').val();
                const ingredientName = $('#ingredient_search').val();
                
                // Find the ingredient data from allIngredientsData
                const ingredientData = allIngredientsData.find(mat => mat.material_id == ingredientId);
                const costPerUnit = ingredientData ? parseFloat(ingredientData.cost_per_unit) || 0 : 0;
                const quantity = parseFloat($('#ingredient_quantity').val()) || 0;
                const unit = $('#ingredient_unit').val();
                const label = ingredientData ? (ingredientData.label || 'general').toLowerCase() : 'general';

                // Validation with specific messages
                if (!ingredientId) {
                    Toast.warning('Please select an ingredient from the dropdown.');
                    $('#ingredient_search').focus();
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
                $('#ingredient_search').val('');
                $('#ingredient_quantity').val('');
                $('#ingredient_search').focus();

                Toast.success('Ingredient added successfully!');
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
            function updateCostingDisplay(changedField = null) {
                const directCost = ingredientsList.reduce((sum, item) => sum + item.totalCost, 0);
                const combinedCost = combinedRecipesList.reduce((sum, item) => sum + item.totalCost, 0);
                const overheadCost = directCost * parseFloat($('#overheadCost').val()) / 100 || 0;
                // Combined cost is NOT added to totalCost - it's calculated per piece separately
                const totalCost = directCost + overheadCost;
                const profitMargin = parseFloat($('#profitMargin').val()) || 0;
                const targetProfit = totalCost / ((100 - profitMargin) / 100);
                const profitAmount = targetProfit - totalCost;
                const sellingPrice = targetProfit;

                // Check if all ingredients are in grams or ml (ml can be treated as grams for liquid ingredients like water)
                const allowedUnitsForYield = ['grams', 'ml', 'g'];
                const allIngredientsInGrams = ingredientsList.length > 0 && ingredientsList.every(item => allowedUnitsForYield.includes(item.unit.toLowerCase()));

                // Show/hide yield computation section based on whether all ingredients are in grams/ml
                if (allIngredientsInGrams) {
                    $('#yieldComputationSection').removeClass('hidden');

                    // Auto-calculate total yield from ingredients (all in grams/ml)
                    const totalYieldGrams = ingredientsList.reduce((sum, item) => sum + item.quantity, 0);

                    // Get current input values
                    let piecesPerYield = parseInt($('#piecesPerYield').val()) || 0;
                    let traysPerYield = parseInt($('#traysPerYield').val()) || 0;
                    let gramsPerPiece = parseFloat($('#gramsPerPiece').val()) || 0;
                    let gramsPerTray = parseFloat($('#gramsPerTray').val()) || 0;

                    // Unit price per gram
                    const unitPricePerGram = totalYieldGrams > 0 ? totalCost / totalYieldGrams : 0;

                    // Flexible Yield Computation Logic:
                    let unitPricePerPiece = 0;
                    let unitPricePerTray = 0;
                    let piecesPerTray = 0;

                    // Handle TRAY calculations
                    if (changedField === 'gramsPerTray' && gramsPerTray > 0 && totalYieldGrams > 0) {
                        // User entered grams per tray - calculate number of trays (whole numbers only)
                        traysPerYield = Math.floor(totalYieldGrams / gramsPerTray);
                        $('#traysPerYield').val(traysPerYield);
                    } else if (changedField === 'traysPerYield' && traysPerYield > 0 && totalYieldGrams > 0) {
                        // User entered trays - calculate grams per tray
                        gramsPerTray = totalYieldGrams / traysPerYield;
                        $('#gramsPerTray').val(gramsPerTray.toFixed(2));
                    } else if (traysPerYield > 0 && totalYieldGrams > 0 && gramsPerTray === 0) {
                        // Default: calculate grams per tray from trays
                        gramsPerTray = totalYieldGrams / traysPerYield;
                        $('#gramsPerTray').val(gramsPerTray.toFixed(2));
                    }

                    // Calculate unit price per tray
                    if (traysPerYield > 0) {
                        unitPricePerTray = totalCost / traysPerYield;
                    }

                    // Handle PIECE calculations
                    // Get the current category to check if it's dough
                    const currentCategory = $('#category_id').val();
                    
                    if (changedField === 'gramsPerPiece' && gramsPerPiece > 0) {
                        // User entered grams per piece - calculate number of pieces (whole numbers only)
                        // For dough, don't auto-calculate pieces from grams per piece
                        if (currentCategory !== 'dough') {
                            if (traysPerYield > 0 && gramsPerTray > 0) {
                                // If trays exist, pieces = pieces per tray (based on grams per tray)
                                piecesPerYield = Math.floor(gramsPerTray / gramsPerPiece);
                                piecesPerTray = piecesPerYield;
                            } else if (totalYieldGrams > 0) {
                                // Direct calculation from total yield
                                piecesPerYield = Math.floor(totalYieldGrams / gramsPerPiece);
                            }
                            $('#piecesPerYield').val(piecesPerYield);
                        }
                    } else if (changedField === 'piecesPerYield' && piecesPerYield > 0 && currentCategory !== 'dough') {
                        // User entered pieces - calculate grams per piece
                        // Skip this calculation entirely for dough category
                        if (traysPerYield > 0 && gramsPerTray > 0) {
                            // Pieces input = pieces per tray
                            piecesPerTray = piecesPerYield;
                            gramsPerPiece = gramsPerTray / piecesPerTray;
                        } else if (totalYieldGrams > 0) {
                            // Direct calculation
                            gramsPerPiece = totalYieldGrams / piecesPerYield;
                        }
                        $('#gramsPerPiece').val(gramsPerPiece.toFixed(2));
                    } else if (piecesPerYield > 0 && currentCategory !== 'dough') {
                        // Default: calculate grams per piece from pieces
                        // Skip this calculation entirely for dough category
                        if (traysPerYield > 0 && gramsPerTray > 0) {
                            piecesPerTray = piecesPerYield;
                            gramsPerPiece = gramsPerTray / piecesPerTray;
                        } else if (totalYieldGrams > 0) {
                            gramsPerPiece = totalYieldGrams / piecesPerYield;
                        }
                        if (parseFloat($('#gramsPerPiece').val()) === 0 || changedField === null) {
                            $('#gramsPerPiece').val(gramsPerPiece > 0 ? gramsPerPiece.toFixed(2) : 0);
                        }
                    }

                    // Calculate unit price per piece
                    if (piecesPerYield > 0) {
                        if (traysPerYield > 0) {
                            piecesPerTray = piecesPerYield;
                            unitPricePerPiece = unitPricePerTray / piecesPerTray;
                        } else {
                            unitPricePerPiece = totalCost / piecesPerYield;
                        }
                    }

                    // Yield displays
                    $('#totalYieldGramsDisplay').text(totalYieldGrams.toFixed(2) + ' g');
                    $('#unitPricePerGramDisplay').text('₱ ' + unitPricePerGram.toFixed(3));
                    $('#unitPricePerPieceDisplay').text(unitPricePerPiece > 0 ? '₱ ' + unitPricePerPiece.toFixed(3) : '-');
                    $('#unitPricePerTrayDisplay').text(unitPricePerTray > 0 ? '₱ ' + unitPricePerTray.toFixed(2) : '-');

                    // Calculate additional price per piece (from combined recipes)
                    const additionalPricePerPiece = combinedRecipesList.reduce((sum, item) => {
                        return sum + (item.costPerUnit * item.gramsPerPiece);
                    }, 0);
                    
                    // Calculate additional price per tray
                    let additionalPricePerTray = 0;
                    if (traysPerYield > 0 && piecesPerYield > 0) {
                        // If both trays and pieces are set, pieces = pieces per tray
                        additionalPricePerTray = additionalPricePerPiece * piecesPerYield;
                    } else if (traysPerYield > 0 && piecesPerYield === 0) {
                        // If only trays, no additional per tray calculation possible
                        additionalPricePerTray = 0;
                    }
                    
                    // Calculate total prices (unit price + additional)
                    const totalPricePerPiece = unitPricePerPiece + additionalPricePerPiece;
                    const totalPricePerTray = unitPricePerTray + additionalPricePerTray;

                    // Show/hide additional price rows based on whether there are combined recipes
                    if (combinedRecipesList.length > 0 && piecesPerYield > 0) {
                        $('#additionalPricePerPieceRow').removeClass('hidden');
                        $('#additionalPricePerPieceDisplay').text('₱ ' + additionalPricePerPiece.toFixed(2));
                        $('#totalPricePerPieceRow').removeClass('hidden');
                        $('#totalPricePerPieceDisplay').text('₱ ' + totalPricePerPiece.toFixed(2));
                    } else {
                        $('#additionalPricePerPieceRow').addClass('hidden');
                        $('#totalPricePerPieceRow').addClass('hidden');
                    }
                    
                    if (combinedRecipesList.length > 0 && traysPerYield > 0 && piecesPerYield > 0) {
                        $('#additionalPricePerTrayRow').removeClass('hidden');
                        $('#additionalPricePerTrayDisplay').text('₱ ' + additionalPricePerTray.toFixed(2));
                        $('#totalPricePerTrayRow').removeClass('hidden');
                        $('#totalPricePerTrayDisplay').text('₱ ' + totalPricePerTray.toFixed(2));
                    } else {
                        $('#additionalPricePerTrayRow').addClass('hidden');
                        $('#totalPricePerTrayRow').addClass('hidden');
                    }

                    // Update label based on whether Trays is filled
                    if (traysPerYield > 0) {
                        $('#piecesLabel').text('Pieces per Tray:');
                    } else {
                        $('#piecesLabel').text('Pieces/Slices/Plates:');
                    }

                    // Calculate Selling Price per Tray and per Piece (Recommended)
                    // Use total price (including additional) for recommended calculations
                    const recommendedPricePerTray = traysPerYield > 0 ? totalPricePerTray * (1 + profitMargin / 100) : 0;
                    const recommendedPricePerPiece = totalPricePerPiece > 0 ? totalPricePerPiece * (1 + profitMargin / 100) : 0;

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
                        
                        // Enable additional recipe inputs when pieces per yield is set
                        $('#additionalYieldWarning').addClass('hidden');
                        $('#additionalRecipeInputs').removeClass('hidden');
                    } else {
                        $('#sellingPricePerPieceRow').addClass('hidden');
                        
                        // Disable additional recipe inputs when no pieces per yield
                        $('#additionalYieldWarning').removeClass('hidden');
                        $('#additionalRecipeInputs').addClass('hidden');
                    }
                } else {
                    $('#yieldComputationSection').addClass('hidden');
                    // Hide per tray and per piece selling prices when yield computation is hidden
                    $('#sellingPricePerTrayRow').addClass('hidden');
                    $('#sellingPricePerPieceRow').addClass('hidden');
                    
                    // Disable additional recipe inputs when no yield computation
                    $('#additionalYieldWarning').removeClass('hidden');
                    $('#additionalRecipeInputs').addClass('hidden');
                }

                $('#directCostDisplay').text('₱ ' + directCost.toFixed(2));
                // Update Step 2 direct cost display
                $('#step2DirectCostDisplay').text('₱ ' + directCost.toFixed(2));
                
                // Show/hide combined cost card based on whether there are combined recipes
                if (combinedRecipesList.length > 0) {
                    $('#combinedCostCard').removeClass('hidden');
                    $('#combinedCostDisplay').text('₱ ' + combinedCost.toFixed(2));
                    $('#directCostCard').removeClass('col-span-2').addClass('col-span-1');
                } else {
                    $('#combinedCostCard').addClass('hidden');
                    $('#directCostCard').removeClass('col-span-1').addClass('col-span-2');
                }
                
                $('#totalCostDisplay').text('₱ ' + totalCost.toFixed(2));
                $('#profitAmountDisplay').text('₱ ' + profitAmount.toFixed(2));
                $('#recommendedPriceOverall').text('₱ ' + sellingPrice.toFixed(2));
            }

            // Recalculate combined recipes when pieces per yield changes
            function recalculateCombinedRecipes() {
                const piecesPerYield = parseInt($('#piecesPerYield').val()) || 0;
                const traysPerYield = parseInt($('#traysPerYield').val()) || 0;
                
                // Calculate total pieces: if trays > 0, pieces is per tray, so multiply
                // If no trays, pieces is total pieces
                const totalPieces = traysPerYield > 0 ? traysPerYield * piecesPerYield : piecesPerYield;
                
                combinedRecipesList.forEach(function(item) {
                    // Recalculate total cost based on total pieces
                    const costPerProductPiece = item.costPerUnit * item.gramsPerPiece;
                    item.costPerProductPiece = costPerProductPiece;
                    item.totalCost = costPerProductPiece * totalPieces;
                });
                
                updateCombinedRecipesListDisplay();
            }

            // Recalculate on overhead/profit/yield change
            $('#overheadCost, #profitMargin').on('input', function() {
                updateCostingDisplay();
            });

            // Handle yield field changes with specific field tracking
            $('#piecesPerYield').on('input', function() {
                recalculateCombinedRecipes();
                updateCostingDisplay('piecesPerYield');
            });

            $('#traysPerYield').on('input', function() {
                recalculateCombinedRecipes();
                updateCostingDisplay('traysPerYield');
            });

            $('#gramsPerPiece').on('input', function() {
                recalculateCombinedRecipes();
                updateCostingDisplay('gramsPerPiece');
            });

            $('#gramsPerTray').on('input', function() {
                recalculateCombinedRecipes();
                updateCostingDisplay('gramsPerTray');
            });

            // Load Products for Combined Recipes dropdown
            function loadCombinedRecipesDropdown() {
                $.ajax({
                    url: baseUrl + 'Products/GetAll',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data) {
                            console.log("All Products for Combined Recipes: ", response.data);
                            let options = '<option value="">Select a recipe...</option>';
                            response.data.forEach(function(product) {
                                // Exclude drinks from combined recipes
                                if (product.category === 'drinks') {
                                    return; // Skip drinks
                                }
                                
                                // Use grams_per_piece from database if available, otherwise calculate
                                const yieldGrams = parseFloat(product.yield_grams) || 0;
                                const piecesPerYield = parseInt(product.pieces_per_yield) || 0;
                                const traysPerYield = parseInt(product.trays_per_yield) || 0;
                                
                                // Prefer database values if available
                                let gramsPerPiece = parseFloat(product.grams_per_piece) || 0;
                                let gramsPerTray = parseFloat(product.grams_per_tray) || 0;

                                // Fallback to calculation if not in database
                                if (gramsPerPiece === 0 && yieldGrams > 0) {
                                    // Calculate grams per tray
                                    if (traysPerYield > 0 && gramsPerTray === 0) {
                                        gramsPerTray = yieldGrams / traysPerYield;
                                    }
                                    
                                    // Calculate grams per piece
                                    if (piecesPerYield > 0) {
                                        if (traysPerYield > 0) {
                                            // If trays exist, pieces is per tray
                                            gramsPerPiece = gramsPerTray / piecesPerYield;
                                        } else {
                                            // Direct calculation
                                            gramsPerPiece = yieldGrams / piecesPerYield;
                                        }
                                    }
                                }

                                options += '<option value="' + product.product_id + '" data-name="' + product.product_name + '" data-cost="' + (product.direct_cost || 0) + '" data-yield="' + yieldGrams + '" data-grams-per-piece="' + gramsPerPiece.toFixed(2) + '" data-grams-per-tray="' + gramsPerTray.toFixed(2) + '" data-pieces-per-yield="' + piecesPerYield + '" data-trays-per-yield="' + traysPerYield + '">' + product.product_name + '</option>';
                            });
                            $('#combinedRecipeSelect').html(options);
                        }
                    }
                });
            }

            // Auto-populate quantity when a combined recipe is selected
            $('#combinedRecipeSelect').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const gramsPerPiece = parseFloat(selectedOption.data('grams-per-piece')) || 0;
                const gramsPerTray = parseFloat(selectedOption.data('grams-per-tray')) || 0;
                const recipeName = selectedOption.data('name');
                const recipeId = selectedOption.val();
                
                console.log('========== ADDITIONAL RECIPE SELECTED ==========');
                console.log('Recipe ID:', recipeId);
                console.log('Recipe Name:', recipeName);
                console.log('Total Yield (grams):', selectedOption.data('yield'));
                console.log('Trays per Yield:', selectedOption.data('trays-per-yield'));
                console.log('Pieces per Yield:', selectedOption.data('pieces-per-yield'));
                console.log('Grams per Tray:', gramsPerTray);
                console.log('Grams per Piece:', gramsPerPiece);
                console.log('Total Cost:', selectedOption.data('cost'));
                console.log('All Data:', {
                    id: recipeId,
                    name: recipeName,
                    cost: selectedOption.data('cost'),
                    yield: selectedOption.data('yield'),
                    traysPerYield: selectedOption.data('trays-per-yield'),
                    piecesPerYield: selectedOption.data('pieces-per-yield'),
                    gramsPerTray: gramsPerTray,
                    gramsPerPiece: gramsPerPiece
                });
                console.log('================================================');
            });

            // Add Combined Recipe
            $('#btnAddCombinedRecipe').on('click', function() {
                const select = $('#combinedRecipeSelect');
                const selectedOption = select.find('option:selected');
                const recipeId = select.val();
                const recipeName = selectedOption.data('name');
                const recipeTotalCost = parseFloat(selectedOption.data('cost')) || 0;
                const recipeYield = parseFloat(selectedOption.data('yield')) || 0;
                const recipePiecesPerYield = parseInt(selectedOption.data('pieces-per-yield')) || 0;
                // Get grams per piece directly from the dropdown data attribute (from database)
                const gramsPerPiece = parseFloat(selectedOption.data('grams-per-piece')) || 0;
                
                // Get the new product's pieces and trays per yield
                const piecesPerYield = parseInt($('#piecesPerYield').val()) || 0;
                const traysPerYield = parseInt($('#traysPerYield').val()) || 0;
                
                // Calculate total pieces: if trays > 0, pieces is per tray, so multiply
                const totalPieces = traysPerYield > 0 ? traysPerYield * piecesPerYield : piecesPerYield;

                if (!recipeId) {
                    Toast.warning('Please select a recipe to add.');
                    return;
                }

                if (gramsPerPiece <= 0) {
                    Toast.warning('The selected recipe "' + recipeName + '" has no grams per piece data. Please set it up first.');
                    return;
                }
                
                if (piecesPerYield <= 0) {
                    Toast.warning('Please set up Pieces per Yield first.');
                    return;
                }

                // Check if recipe already exists
                const existingIndex = combinedRecipesList.findIndex(item => item.id === recipeId);
                if (existingIndex >= 0) {
                    Toast.warning('This recipe is already added. Remove it first to add again.');
                    return;
                }

                // Calculate cost per gram of the additional recipe
                let costPerGram = 0;
                if (recipeYield > 0) {
                    costPerGram = recipeTotalCost / recipeYield;
                } else {
                    Toast.warning('Warning: The selected recipe "' + recipeName + '" has no yield data.');
                }

                if (recipeTotalCost <= 0) {
                    Toast.warning('Warning: The selected recipe "' + recipeName + '" has zero cost. Please verify this is correct.');
                }

                // Total cost = (cost per gram) × (grams per product piece) × (total pieces)
                const costPerProductPiece = costPerGram * gramsPerPiece;
                const totalCost = costPerProductPiece * totalPieces;

                console.log('========== ADDING ADDITIONAL RECIPE ==========');
                console.log('Recipe:', recipeName);
                console.log('Recipe Total Cost:', recipeTotalCost.toFixed(2));
                console.log('Recipe Yield (grams):', recipeYield);
                console.log('Cost Per Gram:', costPerGram.toFixed(3));
                console.log('Grams Per Product Piece:', gramsPerPiece);
                console.log('Cost Per Product Piece:', costPerProductPiece.toFixed(3));
                console.log('Pieces Per Yield:', piecesPerYield);
                console.log('Trays Per Yield:', traysPerYield);
                console.log('Total Pieces:', totalPieces);
                console.log('Total Additional Cost:', totalCost.toFixed(2));
                console.log('==============================================');

                combinedRecipesList.push({
                    id: recipeId,
                    name: recipeName,
                    gramsPerPiece: gramsPerPiece,
                    unit: 'g',
                    costPerUnit: costPerGram,
                    costPerProductPiece: costPerProductPiece,
                    totalCost: totalCost
                });

                updateCombinedRecipesListDisplay();
                updateCostingDisplay();

                // Reset select
                $('#combinedRecipeSelect').val('');
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
                    $('#combinedRecipesList').html('<p class="text-xs text-gray-500 text-center py-2">No additional recipes added</p>');
                    return;
                }

                const piecesPerYield = parseInt($('#piecesPerYield').val()) || 0;
                const traysPerYield = parseInt($('#traysPerYield').val()) || 0;
                const totalPieces = traysPerYield > 0 ? traysPerYield * piecesPerYield : piecesPerYield;
                
                let html = '';
                combinedRecipesList.forEach(function(item, index) {
                    html += '<div class="flex items-center justify-between p-2 border border-amber-200 rounded-md bg-white">';
                    html += '<div class="flex-1">';
                    html += '<div class="text-xs font-medium text-gray-800">' + item.name + '</div>';
                    html += '<div class="text-xs text-gray-500">' + item.gramsPerPiece + 'g/pc × ₱' + item.costPerUnit.toFixed(3) + '/g = ₱' + (item.gramsPerPiece * item.costPerUnit).toFixed(3) + '/product pc</div>';
                    html += '<div class="text-xs text-amber-600 font-medium">Total: ₱' + item.totalCost.toFixed(2) + ' (' + totalPieces + ' pcs)</div>';
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
                        let cards = '';
                        
                        if (response.success && response.data && response.data.length > 0) {
                            response.data.forEach(function(product) {
                                // Desktop table rows
                                rows += '<tr class="hover:bg-neutral-secondary-soft cursor-pointer product-row" data-product-id="' + product.product_id + '" data-category="' + (product.category || '') + '">';
                                rows += '<td class="px-6 py-4 font-medium text-heading whitespace-nowrap">' + product.product_name + '</td>';
                                rows += '<td class="px-6 py-4">' + (product.category || '-') + '</td>';
                                rows += '<td class="px-6 py-4">' + parseFloat(product.direct_cost || 0).toFixed(2) + '</td>';
                                rows += '<td class="px-6 py-4">' + parseFloat(product.total_cost || 0).toFixed(2) + '</td>';
                                rows += '<td class="px-6 py-4">' + parseFloat(product.selling_price || 0).toFixed(2) + '</td>';
                                rows += '<td class="px-6 py-4">';
                                rows += '<button class="text-blue-600 py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-blue-800 me-2 btn-edit" data-id="' + product.product_id + '" title="Edit"><i class="fas fa-edit"></i></button>';
                                rows += '<button class="text-red-600 py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-red-800 btn-delete" data-id="' + product.product_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
                                rows += '</td>';
                                rows += '</tr>';
                                
                                // Mobile card view
                                const categoryBadge = getCategoryBadge(product.category);
                                cards += '<div class="product-card bg-white rounded-lg shadow-md border border-gray-100 p-4 cursor-pointer" data-product-id="' + product.product_id + '" data-category="' + (product.category || '') + '" data-name="' + product.product_name.toLowerCase() + '">';
                                cards += '  <div class="flex justify-between items-start mb-3">';
                                cards += '    <div class="flex-1 pr-2">';
                                cards += '      <h3 class="font-semibold text-gray-800 text-base leading-tight">' + product.product_name + '</h3>';
                                cards += '      <div class="mt-1">' + categoryBadge + '</div>';
                                cards += '    </div>';
                                cards += '    <div class="relative">';
                                cards += '      <button class="card-menu-btn p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors" data-id="' + product.product_id + '">';
                                cards += '        <i class="fas fa-ellipsis-v"></i>';
                                cards += '      </button>';
                                cards += '      <div class="card-menu hidden absolute right-0 top-8 bg-white border border-gray-200 rounded-lg shadow-lg z-10 min-w-[140px] py-1">';
                                cards += '        <button class="card-view-btn w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2" data-id="' + product.product_id + '">';
                                cards += '          <i class="fas fa-eye text-gray-400"></i> View';
                                cards += '        </button>';
                                cards += '        <button class="btn-edit w-full px-4 py-2 text-left text-sm text-blue-600 hover:bg-blue-50 flex items-center gap-2" data-id="' + product.product_id + '">';
                                cards += '          <i class="fas fa-edit"></i> Edit';
                                cards += '        </button>';
                                cards += '        <div class="border-t border-gray-100 my-1"></div>';
                                cards += '        <button class="btn-delete w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 flex items-center gap-2" data-id="' + product.product_id + '">';
                                cards += '          <i class="fas fa-trash"></i> Delete';
                                cards += '        </button>';
                                cards += '      </div>';
                                cards += '    </div>';
                                cards += '  </div>';
                                cards += '  <div class="grid grid-cols-3 gap-2 text-center">';
                                cards += '    <div class="bg-gray-50 rounded-lg p-2">';
                                cards += '      <p class="text-[10px] text-gray-500 uppercase tracking-wide">Direct Cost</p>';
                                cards += '      <p class="text-sm font-semibold text-gray-700">₱' + parseFloat(product.direct_cost || 0).toFixed(2) + '</p>';
                                cards += '    </div>';
                                cards += '    <div class="bg-gray-50 rounded-lg p-2">';
                                cards += '      <p class="text-[10px] text-gray-500 uppercase tracking-wide">Total Cost</p>';
                                cards += '      <p class="text-sm font-semibold text-gray-700">₱' + parseFloat(product.total_cost || 0).toFixed(2) + '</p>';
                                cards += '    </div>';
                                cards += '    <div class="bg-primary/10 rounded-lg p-2">';
                                cards += '      <p class="text-[10px] text-primary uppercase tracking-wide">Selling Price</p>';
                                cards += '      <p class="text-sm font-bold text-primary">₱' + parseFloat(product.selling_price || 0).toFixed(2) + '</p>';
                                cards += '    </div>';
                                cards += '  </div>';
                                cards += '</div>';
                            });
                            $('#mobileNoResults').addClass('hidden');
                        } else {
                            $('#mobileNoResults').removeClass('hidden');
                        }
                        
                        $('#materialsTableBody').html(rows);
                        $('#mobileCardsContainer').html(cards);

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

                // Calculate costs before submission
                const directCost = ingredientsList.reduce((sum, item) => sum + item.totalCost, 0);
                const combinedRecipeCost = combinedRecipesList.reduce((sum, item) => sum + item.totalCost, 0);
                const overheadPercentage = parseFloat($('#overheadCost').val()) || 0;
                const overheadCost = directCost * (overheadPercentage / 100);
                // Combined cost is NOT added to totalCost - it's calculated per piece separately
                const totalCost = directCost + overheadCost;
                const profitMargin = parseFloat($('#profitMargin').val()) || 0;
                const profitAmount = totalCost * (profitMargin / 100);

                // Calculate yield info
                // Check if all ingredients are in grams or ml (ml can be treated as grams for yield calculation)
                const allowedUnitsForYield = ['grams', 'ml', 'g'];
                const allIngredientsInGrams = ingredientsList.length > 0 && ingredientsList.every(item => allowedUnitsForYield.includes(item.unit.toLowerCase()));
                const yieldGrams = allIngredientsInGrams ? ingredientsList.reduce((sum, item) => sum + item.quantity, 0) : 0;
                const traysPerYield = parseInt($('#traysPerYield').val()) || 0;
                const piecesPerYield = parseInt($('#piecesPerYield').val()) || 0;
                const gramsPerTray = parseFloat($('#gramsPerTray').val()) || 0;
                const gramsPerPiece = parseFloat($('#gramsPerPiece').val()) || 0;

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
                    combined_recipes: combinedRecipesList.map(item => ({
                        product_id: item.id,
                        quantity: item.quantity,
                        unit: item.unit,
                        cost_per_unit: item.costPerUnit,
                        total_cost: item.totalCost
                    })),
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
                    pieces_per_yield: piecesPerYield,
                    grams_per_tray: gramsPerTray,
                    grams_per_piece: gramsPerPiece
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
                    success: function(response) {
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
                    error: function(xhr, status, error) {
                        console.log('--- AJAX ERROR ---');
                        console.log('Status:', status);
                        console.log('Error:', error);
                        console.log('Response Text:', xhr.responseText);
                        Toast.error('Error adding product: ' + error);
                    }
                });
            });

            // Delete Product
            $(document).on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                Confirm.delete('Are you sure you want to delete this product?', function() {
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
                });
            });

            // Apply Filter
            $('#apply-filters').on('click', function() {
                const categoryId = $('#filter-category').val();
                
                // Filter desktop table
                $('table tbody tr').each(function() {
                    if (categoryId === '' || $(this).data('category') == categoryId) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                
                // Filter mobile cards
                let hasResults = false;
                $('.product-card').each(function() {
                    if (categoryId === '' || $(this).data('category') == categoryId) {
                        $(this).removeClass('hidden');
                        hasResults = true;
                    } else {
                        $(this).addClass('hidden');
                    }
                });
                
                if (hasResults || categoryId === '') {
                    $('#mobileNoResults').addClass('hidden');
                } else {
                    $('#mobileNoResults').removeClass('hidden');
                }
            });

            // Reset Filter
            $('#reset-filters').on('click', function() {
                $('#filter-category').val('');
                $('table tbody tr').show();
                $('.product-card').removeClass('hidden');
                $('#mobileNoResults').addClass('hidden');
                $('#mobileSearchInput').val('');
            });

            // =====================================================
            // EDIT PRODUCT MODAL FUNCTIONALITY
            // =====================================================

            // Edit modal ingredients and combined recipes lists
            let editIngredientsList = [];
            let editCombinedRecipesList = [];

            // Open Edit Product Modal
            $(document).on('click', '.btn-edit', function() {
                const productId = $(this).data('id');
                openEditModal(productId);
            });

            // Function to open edit modal and load product data
            function openEditModal(productId) {
                console.log('=== OPEN EDIT MODAL DEBUG ===');
                console.log('openEditModal called with productId:', productId);
                console.log('productId type:', typeof productId);
                
                // Load ingredients dropdown first, then fetch product data
                $.ajax({
                    url: baseUrl + 'RawMaterials/GetAll',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Store all ingredients data for filtering and label lookup
                            allIngredientsData = response.data;
                            // Update dropdown based on current restriction
                            updateEditIngredientsDropdown();
                        }
                        
                        // Now load combined recipes dropdown
                        loadEditCombinedRecipesDropdown();

                        // Fetch product data after ingredients are loaded
                        console.log('=== FETCHING PRODUCT DATA ===');
                        console.log('URL:', baseUrl + 'Products/GetProduct/' + productId);
                        $.ajax({
                            url: baseUrl + 'Products/GetProduct/' + productId,
                            type: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                console.log('Product fetch response:', response);
                                if (response.success && response.data) {
                                    const product = response.data;
                                    console.log('Product Data:', product);

                            // Set basic product info
                            $('#edit_product_id').val(product.product_id);
                            $('#edit_material_name').val(product.product_name);
                            $('#edit_category_id').val(product.category);
                            
                            // Set the previous category tracker for edit modal
                            editPreviousCategory = product.category;

                            // Set costing values
                            $('#editOverheadCost').val(product.overhead_cost_percentage || 0);
                            $('#editProfitMargin').val(product.profit_margin_percentage || 30);

                            // Set yield values
                            $('#editTraysPerYield').val(product.trays_per_yield || 0);
                            $('#editPiecesPerYield').val(product.pieces_per_yield || 0);
                            // Set grams per piece/tray from database (will recalculate if 0)
                            $('#editGramsPerPiece').val(product.grams_per_piece || 0);
                            $('#editGramsPerTray').val(product.grams_per_tray || 0);

                            // Set selling prices
                            $('#editSellingPriceOverall').val(product.selling_price_overall || 0);
                            $('#editSellingPricePerTray').val(product.selling_price_per_tray || 0);
                            $('#editSellingPricePerPiece').val(product.selling_price_per_piece || 0);

                            // Load ingredients
                            editIngredientsList = [];
                            if (product.ingredients && product.ingredients.length > 0) {
                                product.ingredients.forEach(function(ing) {
                                    // Look up the label from allIngredientsData if not provided
                                    let ingredientLabel = ing.label || 'general';
                                    if (!ing.label || ing.label === 'general') {
                                        const rawMaterial = allIngredientsData.find(m => m.material_id == ing.material_id);
                                        if (rawMaterial && rawMaterial.label) {
                                            ingredientLabel = rawMaterial.label;
                                        }
                                    }
                                    
                                    editIngredientsList.push({
                                        id: ing.material_id,
                                        name: ing.material_name,
                                        quantity: parseFloat(ing.quantity),
                                        unit: ing.unit,
                                        costPerUnit: parseFloat(ing.cost_per_unit),
                                        totalCost: parseFloat(ing.total_cost),
                                        label: ingredientLabel
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

                                product.combined_recipes.forEach(function(recipe) {
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

                            // Reset to step 1 and update display
                            currentEditStep = 1;
                            updateEditStepDisplay();

                            // Show modal
                            $('#editProductModal').removeClass('hidden');
                        } else {
                            Toast.error('Error loading product data: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('=== AJAX ERROR ===');
                        console.log('XHR:', xhr);
                        console.log('Status:', status);
                        console.log('Error:', error);
                        console.log('Response Text:', xhr.responseText);
                        Toast.error('Error loading product: ' + error);
                    }
                });
                    }
                });
            }

            // Close Edit Modal
            $('#btnCloseEditModal, #btnCancelEdit').on('click', function() {
                closeEditModal();
            });

            function closeEditModal() {
                $('#editProductModal').addClass('hidden');
                $('#editProductForm')[0].reset();
                editIngredientsList = [];
                editCombinedRecipesList = [];
                editPreviousCategory = ''; // Reset the previous category tracker

                // Reset search inputs
                $('#edit_ingredient_search').val('');
                $('#edit_ingredient_id').val('');
                hideEditIngredientDropdown();

                // Reset combined recipes UI
                $('#editCombinedRecipeSection').addClass('hidden');
                $('#editCombinedCostCard').addClass('hidden');
                $('#editDirectCostCard').removeClass('col-span-1').addClass('col-span-2');

                updateEditIngredientsListDisplay();
                updateEditCombinedRecipesListDisplay();
                updateEditCostingDisplay();

                // Reset UI to default state
                $('.edit-combined-recipe-container').removeClass('hidden');
                $('#edit_ingredient_unit option').show();

                // Reset to step 1
                currentEditStep = 1;
                updateEditStepDisplay();
            }

            // =====================================================
            // EDIT STEP NAVIGATION LOGIC
            // =====================================================
            let currentEditStep = 1;
            const totalEditSteps = 3;

            // Update edit step display based on current step
            function updateEditStepDisplay() {
                // Hide all step content
                $('#editStep1, #editStep2, #editStep3').addClass('hidden');
                
                // Show current step content
                $('#editStep' + currentEditStep).removeClass('hidden');

                // Update stepper indicators
                for (let i = 1; i <= totalEditSteps; i++) {
                    const indicator = $('#editStep' + i + 'Indicator');
                    const label = $('#editStep' + i + 'Label');
                    
                    if (i < currentEditStep) {
                        // Completed step
                        indicator.removeClass('border-gray-300 text-gray-400 bg-primary/10 border-primary text-primary')
                            .addClass('bg-primary border-primary text-white');
                        indicator.html('<i class="fas fa-check text-xs"></i>');
                        label.removeClass('text-gray-400').addClass('text-primary');
                    } else if (i === currentEditStep) {
                        // Current step
                        indicator.removeClass('border-gray-300 text-gray-400 bg-primary text-white')
                            .addClass('bg-primary/10 border-primary text-primary');
                        indicator.html(i);
                        label.removeClass('text-gray-400').addClass('text-primary');
                    } else {
                        // Future step
                        indicator.removeClass('bg-primary/10 border-primary text-primary bg-primary text-white')
                            .addClass('border-gray-300 text-gray-400');
                        indicator.html(i);
                        label.removeClass('text-primary').addClass('text-gray-400');
                    }
                }

                // Update connector colors
                $('#editConnector1').removeClass('bg-primary bg-gray-300').addClass(currentEditStep > 1 ? 'bg-primary' : 'bg-gray-300');
                $('#editConnector2').removeClass('bg-primary bg-gray-300').addClass(currentEditStep > 2 ? 'bg-primary' : 'bg-gray-300');

                // Update button visibility
                if (currentEditStep === 1) {
                    $('#btnEditBackStep').addClass('hidden');
                    $('#btnEditNextStep').removeClass('hidden');
                    $('#btnUpdateProduct').addClass('hidden');
                } else if (currentEditStep === 2) {
                    $('#btnEditBackStep').removeClass('hidden');
                    $('#btnEditNextStep').removeClass('hidden');
                    $('#btnUpdateProduct').addClass('hidden');
                } else if (currentEditStep === 3) {
                    $('#btnEditBackStep').removeClass('hidden');
                    $('#btnEditNextStep').addClass('hidden');
                    $('#btnUpdateProduct').removeClass('hidden');
                }
            }

            // Edit Next step button click
            $('#btnEditNextStep').on('click', function() {
                // Validate current step before proceeding
                if (currentEditStep === 1) {
                    const productName = $('#edit_material_name').val().trim();
                    const category = $('#edit_category_id').val();
                    
                    if (!productName) {
                        Toast.error('Please enter a product name.');
                        $('#edit_material_name').focus();
                        return;
                    }
                    if (!category) {
                        Toast.error('Please select a category.');
                        $('#edit_category_id').focus();
                        return;
                    }
                }

                if (currentEditStep === 2) {
                    if (editIngredientsList.length === 0) {
                        Toast.error('Please add at least one ingredient.');
                        return;
                    }
                }

                if (currentEditStep < totalEditSteps) {
                    currentEditStep++;
                    updateEditStepDisplay();
                    // Update costing display when entering step 3
                    if (currentEditStep === 3) {
                        updateEditCostingDisplay();
                    }
                }
            });

            // Edit Back step button click
            $('#btnEditBackStep').on('click', function() {
                if (currentEditStep > 1) {
                    currentEditStep--;
                    updateEditStepDisplay();
                    // Update costing display when going back
                    updateEditCostingDisplay();
                }
            });

            // Handle edit category change
            // Track the previous category to determine if we need to clear ingredients
            let editPreviousCategory = '';
            
            $('#edit_category_id').on('change', function() {
                const category = $(this).val();
                const previousCategory = editPreviousCategory;
                
                // Only clear ingredients when switching from bread/dough to drinks
                // This is because drinks use different units (ml, pcs) vs bread/dough (grams)
                if ((previousCategory === 'bread' || previousCategory === 'dough') && category === 'drinks') {
                    editIngredientsList = [];
                    editCombinedRecipesList = [];
                    updateEditIngredientsListDisplay();
                    updateEditCombinedRecipesListDisplay();
                }
                
                // Also clear when switching from drinks to bread/dough
                if (previousCategory === 'drinks' && (category === 'bread' || category === 'dough')) {
                    editIngredientsList = [];
                    editCombinedRecipesList = [];
                    updateEditIngredientsListDisplay();
                    updateEditCombinedRecipesListDisplay();
                }
                
                // Update the previous category tracker
                editPreviousCategory = category;

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
                    
                    // Show per tray section and enable pieces input for drinks
                    $('#editPerTraySection').removeClass('hidden');
                    $('#editPiecesPerYield').prop('disabled', false);

                    // Show all unit options for drinks
                    $('#edit_ingredient_unit option').show();
                } else if (category === 'bread' || category === 'dough') {
                    if (category === 'bread') {
                        // Show combined recipes section for bread only
                        $('#editCombinedRecipeSection').removeClass('hidden');
                        $('#editCombinedCostCard').removeClass('hidden');
                        $('#editDirectCostCard').removeClass('col-span-2').addClass('col-span-1');
                        
                        // Show per tray section and enable pieces input for bread
                        $('#editPerTraySection').removeClass('hidden');
                        $('#editPiecesPerYield').prop('disabled', false);
                    } else {
                        // Hide combined recipes section for dough
                        $('#editCombinedRecipeSection').addClass('hidden');
                        $('#editCombinedCostCard').addClass('hidden');
                        $('#editDirectCostCard').removeClass('col-span-1').addClass('col-span-2');
                        
                        // Hide per tray section and disable pieces input for dough
                        $('#editPerTraySection').addClass('hidden');
                        $('#editPiecesPerYield').prop('disabled', true);
                        $('#editTraysPerYield').val(0);
                        $('#editGramsPerTray').val(0);
                    }
                    
                    // Hide all units except grams for bread and dough
                    $('#edit_ingredient_unit option').hide();
                    $('#edit_ingredient_unit option[value="grams"]').show();
                    $('#edit_ingredient_unit').val('grams');
                } else {
                    // Default state when no category selected
                    $('#editCombinedRecipeSection').addClass('hidden');
                    $('#editCombinedCostCard').addClass('hidden');
                    $('#editDirectCostCard').removeClass('col-span-1').addClass('col-span-2');
                    $('#edit_ingredient_unit option').show();
                    
                    // Show per tray section and enable pieces input by default
                    $('#editPerTraySection').removeClass('hidden');
                    $('#editPiecesPerYield').prop('disabled', false);
                }
            }

            // Load Edit Ingredients (Raw Materials) for dropdown
            function loadEditIngredients() {
                $.ajax({
                    url: baseUrl + 'RawMaterials/GetAll',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
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
                // Clear search input when dropdown is updated
                $('#edit_ingredient_search').val('');
                $('#edit_ingredient_id').val('');
            }

            // Get filtered ingredients for Edit modal based on category and search term
            function getEditFilteredIngredients(searchTerm = '') {
                const category = $('#edit_category_id').val();
                const filtered = [];

                allIngredientsData.forEach(function(mat) {
                    const label = (mat.label || 'general').toLowerCase();

                    let shouldShow = false;

                    if (!category) {
                        shouldShow = true;
                    } else if (label === 'general' || label === '') {
                        shouldShow = true;
                    } else if ((category === 'bread' || category === 'dough') && label === 'bread' || label === 'dough') {
                        shouldShow = true;
                    } else if (category === 'drinks' && label === 'drinks') {
                        shouldShow = true;
                    }

                    // Apply search filter
                    if (shouldShow && searchTerm) {
                        const name = mat.material_name.toLowerCase();
                        shouldShow = name.includes(searchTerm.toLowerCase());
                    }

                    if (shouldShow) {
                        filtered.push(mat);
                    }
                });

                return filtered;
            }

            // Show edit ingredient dropdown with filtered results
            function showEditIngredientDropdown(searchTerm = '') {
                const filtered = getEditFilteredIngredients(searchTerm);
                let html = '';

                if (filtered.length === 0) {
                    html = '<div class="px-3 py-2 text-sm text-gray-500">No ingredients found</div>';
                } else {
                    filtered.forEach(function(mat) {
                        const label = (mat.label || 'general').toLowerCase();
                        html += '<div class="edit-ingredient-option px-3 py-2 text-sm cursor-pointer hover:bg-primary/10 border-b border-gray-100 last:border-b-0" ' +
                            'data-id="' + mat.material_id + '" ' +
                            'data-name="' + mat.material_name + '" ' +
                            'data-cost="' + mat.cost_per_unit + '" ' +
                            'data-unit="' + mat.unit + '" ' +
                            'data-label="' + label + '">' +
                            '<span class="font-medium">' + mat.material_name + '</span>' +
                            '<span class="text-xs text-gray-400 ml-2">(' + label + ')</span>' +
                            '</div>';
                    });
                }

                $('#edit_ingredient_dropdown').html(html).removeClass('hidden');
            }

            // Hide edit ingredient dropdown
            function hideEditIngredientDropdown() {
                $('#edit_ingredient_dropdown').addClass('hidden');
            }

            // Ingredient search input events (Edit Modal)
            $('#edit_ingredient_search').on('focus', function() {
                showEditIngredientDropdown($(this).val());
            });

            $('#edit_ingredient_search').on('input', function() {
                showEditIngredientDropdown($(this).val());
            });

            // Select ingredient from dropdown (Edit Modal)
            $(document).on('click', '.edit-ingredient-option', function() {
                const $this = $(this);
                const id = $this.data('id');
                const name = $this.data('name');
                const unit = $this.data('unit');

                $('#edit_ingredient_id').val(id);
                $('#edit_ingredient_search').val(name);
                $('#edit_ingredient_unit').val(unit);
                hideEditIngredientDropdown();
                $('#edit_ingredient_quantity').focus();
            })

            // Auto-select unit is now handled in the edit-ingredient-option click event above

            // Load Edit Combined Recipes dropdown
            function loadEditCombinedRecipesDropdown() {
                $.ajax({
                    url: baseUrl + 'Products/GetAll',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data) {
                            let options = '<option value="">Select a recipe...</option>';
                            response.data.forEach(function(product) {
                                // Exclude drinks from combined recipes
                                if (product.category === 'drinks') {
                                    return; // Skip drinks
                                }
                                
                                // Use grams_per_piece from database if available, otherwise calculate
                                const yieldGrams = parseFloat(product.yield_grams) || 0;
                                const piecesPerYield = parseInt(product.pieces_per_yield) || 0;
                                const traysPerYield = parseInt(product.trays_per_yield) || 0;
                                
                                // Prefer database values if available
                                let gramsPerPiece = parseFloat(product.grams_per_piece) || 0;
                                let gramsPerTray = parseFloat(product.grams_per_tray) || 0;

                                // Fallback to calculation if not in database
                                if (gramsPerPiece === 0 && yieldGrams > 0) {
                                    // Calculate grams per tray
                                    if (traysPerYield > 0 && gramsPerTray === 0) {
                                        gramsPerTray = yieldGrams / traysPerYield;
                                    }
                                    
                                    // Calculate grams per piece
                                    if (piecesPerYield > 0) {
                                        if (traysPerYield > 0) {
                                            // If trays exist, pieces is per tray
                                            gramsPerPiece = gramsPerTray / piecesPerYield;
                                        } else {
                                            // Direct calculation
                                            gramsPerPiece = yieldGrams / piecesPerYield;
                                        }
                                    }
                                }

                                options += '<option value="' + product.product_id + '" data-name="' + product.product_name + '" data-cost="' + (product.direct_cost || 0) + '" data-yield="' + yieldGrams + '" data-grams-per-piece="' + gramsPerPiece.toFixed(2) + '" data-grams-per-tray="' + gramsPerTray.toFixed(2) + '" data-pieces-per-yield="' + piecesPerYield + '" data-trays-per-yield="' + traysPerYield + '">' + product.product_name + '</option>';
                            });
                            $('#editCombinedRecipeSelect').html(options);
                        }
                    }
                });
            }

            // Auto-populate quantity when a combined recipe is selected (Edit modal)
            $('#editCombinedRecipeSelect').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const gramsPerPiece = parseFloat(selectedOption.data('grams-per-piece')) || 0;
                const gramsPerTray = parseFloat(selectedOption.data('grams-per-tray')) || 0;
                const recipeName = selectedOption.data('name');
                const recipeId = selectedOption.val();
                
                console.log('========== EDIT: ADDITIONAL RECIPE SELECTED ==========');
                console.log('Recipe ID:', recipeId);
                console.log('Recipe Name:', recipeName);
                console.log('Total Yield (grams):', selectedOption.data('yield'));
                console.log('Trays per Yield:', selectedOption.data('trays-per-yield'));
                console.log('Pieces per Yield:', selectedOption.data('pieces-per-yield'));
                console.log('Grams per Tray:', gramsPerTray);
                console.log('Grams per Piece:', gramsPerPiece);
                console.log('Total Cost:', selectedOption.data('cost'));
                console.log('All Data:', {
                    id: recipeId,
                    name: recipeName,
                    cost: selectedOption.data('cost'),
                    yield: selectedOption.data('yield'),
                    traysPerYield: selectedOption.data('trays-per-yield'),
                    piecesPerYield: selectedOption.data('pieces-per-yield'),
                    gramsPerTray: gramsPerTray,
                    gramsPerPiece: gramsPerPiece
                });
                console.log('======================================================');
            });

            // Allow Enter key in edit quantity field to add ingredient
            $('#edit_ingredient_quantity').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#btnEditAddIngredient').click();
                }
            });

            // Add Ingredient to Edit List
            $('#btnEditAddIngredient').on('click', function() {
                const ingredientId = $('#edit_ingredient_id').val();
                const ingredientName = $('#edit_ingredient_search').val();
                
                // Find the ingredient data from allIngredientsData
                const ingredientData = allIngredientsData.find(mat => mat.material_id == ingredientId);
                const costPerUnit = ingredientData ? parseFloat(ingredientData.cost_per_unit) || 0 : 0;
                const quantity = parseFloat($('#edit_ingredient_quantity').val()) || 0;
                const unit = $('#edit_ingredient_unit').val();
                const label = ingredientData ? (ingredientData.label || 'general').toLowerCase() : 'general';

                // Validation with specific messages
                if (!ingredientId) {
                    Toast.warning('Please select an ingredient from the dropdown.');
                    $('#edit_ingredient_search').focus();
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
                $('#edit_ingredient_search').val('');
                $('#edit_ingredient_quantity').val('');
                $('#edit_ingredient_search').focus();

                Toast.success('Ingredient added successfully!');
            });

            // Remove Ingredient from Edit List
            $(document).on('click', '.btn-remove-edit-ingredient', function() {
                const index = $(this).data('index');
                const ingredientName = editIngredientsList[index].name;
                Confirm.delete('Are you sure you want to remove "' + ingredientName + '" from the ingredients list?', function() {
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
                editIngredientsList.forEach(function(item, index) {
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
            $('#btnEditAddCombinedRecipe').on('click', function() {
                const select = $('#editCombinedRecipeSelect');
                const selectedOption = select.find('option:selected');
                const recipeId = select.val();
                const recipeName = selectedOption.data('name');
                const recipeTotalCost = parseFloat(selectedOption.data('cost')) || 0;
                const recipeYield = parseFloat(selectedOption.data('yield')) || 0;
                const gramsPerPiece = parseFloat(selectedOption.data('grams-per-piece')) || 0;
                
                // Get the product's pieces and trays per yield
                const piecesPerYield = parseInt($('#editPiecesPerYield').val()) || 0;
                const traysPerYield = parseInt($('#editTraysPerYield').val()) || 0;
                
                // Calculate total pieces: if trays > 0, pieces is per tray, so multiply
                const totalPieces = traysPerYield > 0 ? traysPerYield * piecesPerYield : piecesPerYield;

                if (!recipeId) {
                    Toast.warning('Please select a recipe to add.');
                    return;
                }

                if (gramsPerPiece <= 0) {
                    Toast.warning('The selected product "' + recipeName + '" has no grams per piece set in its costing data.');
                    return;
                }
                
                if (piecesPerYield <= 0) {
                    Toast.warning('Please set up Pieces per Yield first.');
                    return;
                }

                // Check if recipe already exists
                const existingIndex = editCombinedRecipesList.findIndex(item => item.id === recipeId);
                if (existingIndex >= 0) {
                    Toast.warning('This recipe is already added. Remove it first to add again.');
                    return;
                }

                // Warn if recipe has no yield data
                if (recipeYield <= 0) {
                    Toast.warning('Warning: The selected recipe "' + recipeName + '" has no yield data. Cost calculations may be incorrect.');
                }

                if (recipeTotalCost <= 0) {
                    Toast.warning('Warning: The selected recipe "' + recipeName + '" has zero cost. Please verify this is correct.');
                }

                // Calculate cost per gram of the additional recipe
                const costPerGram = recipeYield > 0 ? (recipeTotalCost / recipeYield) : 0;
                
                // Total cost = (cost per gram) × (grams per product piece) × (total pieces)
                const costPerProductPiece = costPerGram * gramsPerPiece;
                const totalCost = costPerProductPiece * totalPieces;

                console.log('========== EDIT: ADDING ADDITIONAL RECIPE ==========');
                console.log('Recipe:', recipeName);
                console.log('Recipe Total Cost:', recipeTotalCost.toFixed(2));
                console.log('Recipe Yield (grams):', recipeYield);
                console.log('Cost Per Gram:', costPerGram.toFixed(3));
                console.log('Grams Per Product Piece:', gramsPerPiece);
                console.log('Cost Per Product Piece:', costPerProductPiece.toFixed(3));
                console.log('Pieces Per Yield:', piecesPerYield);
                console.log('Trays Per Yield:', traysPerYield);
                console.log('Total Pieces:', totalPieces);
                console.log('Total Additional Cost:', totalCost.toFixed(2));
                console.log('==================================================');

                editCombinedRecipesList.push({
                    id: recipeId,
                    name: recipeName,
                    gramsPerPiece: gramsPerPiece,
                    unit: 'g',
                    costPerUnit: costPerGram,
                    costPerProductPiece: costPerProductPiece,
                    totalCost: totalCost
                });

                updateEditCombinedRecipesListDisplay();
                updateEditCostingDisplay();

                // Reset inputs
                $('#editCombinedRecipeSelect').val('');
            });

            // Remove Combined Recipe from Edit
            $(document).on('click', '.btn-remove-edit-combined-recipe', function() {
                const index = $(this).data('index');
                const recipeName = editCombinedRecipesList[index].name;
                Confirm.delete('Are you sure you want to remove "' + recipeName + '" from combined recipes?', function() {
                    editCombinedRecipesList.splice(index, 1);
                    updateEditCombinedRecipesListDisplay();
                    updateEditCostingDisplay();
                });
            });

            // Update Edit Combined Recipes List Display
            function updateEditCombinedRecipesListDisplay() {
                if (editCombinedRecipesList.length === 0) {
                    $('#editCombinedRecipesList').html('<p class="text-xs text-gray-500 text-center py-2">No additional recipes added</p>');
                    return;
                }

                const piecesPerYield = parseInt($('#editPiecesPerYield').val()) || 0;
                const traysPerYield = parseInt($('#editTraysPerYield').val()) || 0;
                const totalPieces = traysPerYield > 0 ? traysPerYield * piecesPerYield : piecesPerYield;
                
                let html = '';
                editCombinedRecipesList.forEach(function(item, index) {
                    html += '<div class="flex items-center justify-between p-2 border border-amber-200 rounded-md bg-white">';
                    html += '<div class="flex-1">';
                    html += '<div class="text-xs font-medium text-gray-800">' + item.name + '</div>';
                    html += '<div class="text-xs text-gray-500">' + item.gramsPerPiece + 'g/pc × ₱' + item.costPerUnit.toFixed(3) + '/g = ₱' + (item.gramsPerPiece * item.costPerUnit).toFixed(3) + '/product pc</div>';
                    html += '<div class="text-xs text-amber-600 font-medium">Total: ₱' + item.totalCost.toFixed(2) + ' (' + totalPieces + ' pcs)</div>';
                    html += '</div>';
                    html += '<button type="button" class="text-red-600 hover:text-red-800 btn-remove-edit-combined-recipe" data-index="' + index + '" title="Remove"><i class="fas fa-times"></i></button>';
                    html += '</div>';
                });
                $('#editCombinedRecipesList').html(html);
            }

            // Update Edit Costing Display
            function updateEditCostingDisplay(changedField = null) {
                const directCost = editIngredientsList.reduce((sum, item) => sum + item.totalCost, 0);
                const combinedCost = editCombinedRecipesList.reduce((sum, item) => sum + item.totalCost, 0);
                const overheadCost = directCost * parseFloat($('#editOverheadCost').val()) / 100 || 0;
                // Combined cost is NOT added to totalCost - it's calculated per piece separately
                const totalCost = directCost + overheadCost;
                const profitMargin = parseFloat($('#editProfitMargin').val()) || 0;
                const targetProfit = totalCost / ((100 - profitMargin) / 100);
                const profitAmount = targetProfit - totalCost;
                const sellingPrice = targetProfit;

                // Check if all ingredients are in grams or ml (ml can be treated as grams for liquid ingredients like water)
                const allowedUnitsForYield = ['grams', 'ml', 'g'];
                const allIngredientsInGrams = editIngredientsList.length > 0 && editIngredientsList.every(item => allowedUnitsForYield.includes(item.unit.toLowerCase()));

                // Show/hide yield computation section
                if (allIngredientsInGrams) {
                    $('#editYieldComputationSection').removeClass('hidden');

                    const totalYieldGrams = editIngredientsList.reduce((sum, item) => sum + item.quantity, 0);
                    
                    // Get current input values
                    let piecesPerYield = parseInt($('#editPiecesPerYield').val()) || 0;
                    let traysPerYield = parseInt($('#editTraysPerYield').val()) || 0;
                    let gramsPerPiece = parseFloat($('#editGramsPerPiece').val()) || 0;
                    let gramsPerTray = parseFloat($('#editGramsPerTray').val()) || 0;

                    const unitPricePerGram = totalYieldGrams > 0 ? totalCost / totalYieldGrams : 0;

                    let unitPricePerPiece = 0;
                    let unitPricePerTray = 0;
                    let piecesPerTray = 0;

                    // Handle TRAY calculations
                    if (changedField === 'editGramsPerTray' && gramsPerTray > 0 && totalYieldGrams > 0) {
                        // User entered grams per tray - calculate number of trays (whole numbers only)
                        traysPerYield = Math.floor(totalYieldGrams / gramsPerTray);
                        $('#editTraysPerYield').val(traysPerYield);
                    } else if (changedField === 'editTraysPerYield' && traysPerYield > 0 && totalYieldGrams > 0) {
                        // User entered trays - calculate grams per tray
                        gramsPerTray = totalYieldGrams / traysPerYield;
                        $('#editGramsPerTray').val(gramsPerTray.toFixed(2));
                    } else if (traysPerYield > 0 && totalYieldGrams > 0 && gramsPerTray === 0) {
                        // Default: calculate grams per tray from trays
                        gramsPerTray = totalYieldGrams / traysPerYield;
                        $('#editGramsPerTray').val(gramsPerTray.toFixed(2));
                    }

                    // Calculate unit price per tray
                    if (traysPerYield > 0) {
                        unitPricePerTray = totalCost / traysPerYield;
                    }

                    // Handle PIECE calculations
                    // Get the current category to check if it's dough
                    const currentCategory = $('#edit_category_id').val();
                    
                    if (changedField === 'editGramsPerPiece' && gramsPerPiece > 0) {
                        // User entered grams per piece - calculate number of pieces (whole numbers only)
                        // For dough, don't auto-calculate pieces from grams per piece
                        if (currentCategory !== 'dough') {
                            if (traysPerYield > 0 && gramsPerTray > 0) {
                                // If trays exist, pieces = pieces per tray (based on grams per tray)
                                piecesPerYield = Math.floor(gramsPerTray / gramsPerPiece);
                                piecesPerTray = piecesPerYield;
                            } else if (totalYieldGrams > 0) {
                                // Direct calculation from total yield
                                piecesPerYield = Math.floor(totalYieldGrams / gramsPerPiece);
                            }
                            $('#editPiecesPerYield').val(piecesPerYield);
                        }
                    } else if (changedField === 'editPiecesPerYield' && piecesPerYield > 0 && currentCategory !== 'dough') {
                        // User entered pieces - calculate grams per piece
                        // Skip this calculation entirely for dough category
                        if (traysPerYield > 0 && gramsPerTray > 0) {
                            // Pieces input = pieces per tray
                            piecesPerTray = piecesPerYield;
                            gramsPerPiece = gramsPerTray / piecesPerTray;
                        } else if (totalYieldGrams > 0) {
                            // Direct calculation
                            gramsPerPiece = totalYieldGrams / piecesPerYield;
                        }
                        $('#editGramsPerPiece').val(gramsPerPiece.toFixed(2));
                    } else if (piecesPerYield > 0 && currentCategory !== 'dough') {
                        // Default: calculate grams per piece from pieces
                        // Skip this calculation entirely for dough category
                        if (traysPerYield > 0 && gramsPerTray > 0) {
                            piecesPerTray = piecesPerYield;
                            gramsPerPiece = gramsPerTray / piecesPerTray;
                        } else if (totalYieldGrams > 0) {
                            gramsPerPiece = totalYieldGrams / piecesPerYield;
                        }
                        if (parseFloat($('#editGramsPerPiece').val()) === 0 || changedField === null) {
                            $('#editGramsPerPiece').val(gramsPerPiece > 0 ? gramsPerPiece.toFixed(2) : 0);
                        }
                    }

                    // Calculate unit price per piece
                    if (piecesPerYield > 0) {
                        if (traysPerYield > 0) {
                            piecesPerTray = piecesPerYield;
                            unitPricePerPiece = unitPricePerTray / piecesPerTray;
                        } else {
                            unitPricePerPiece = totalCost / piecesPerYield;
                        }
                    }

                    // Yield displays
                    $('#editTotalYieldGramsDisplay').text(totalYieldGrams.toFixed(2) + ' g');
                    $('#editUnitPricePerGramDisplay').text('₱ ' + unitPricePerGram.toFixed(3));
                    $('#editUnitPricePerPieceDisplay').text(unitPricePerPiece > 0 ? '₱ ' + unitPricePerPiece.toFixed(3) : '-');
                    $('#editUnitPricePerTrayDisplay').text(unitPricePerTray > 0 ? '₱ ' + unitPricePerTray.toFixed(2) : '-');

                    // Calculate additional price per piece (from combined recipes)
                    const additionalPricePerPiece = editCombinedRecipesList.reduce((sum, item) => {
                        return sum + (item.costPerUnit * item.gramsPerPiece);
                    }, 0);
                    
                    // Calculate additional price per tray
                    let additionalPricePerTray = 0;
                    if (traysPerYield > 0 && piecesPerYield > 0) {
                        // If both trays and pieces are set, pieces = pieces per tray
                        additionalPricePerTray = additionalPricePerPiece * piecesPerYield;
                    }
                    
                    // Calculate total prices (unit price + additional)
                    const totalPricePerPiece = unitPricePerPiece + additionalPricePerPiece;
                    const totalPricePerTray = unitPricePerTray + additionalPricePerTray;

                    // Show/hide additional price rows based on whether there are combined recipes
                    if (editCombinedRecipesList.length > 0 && piecesPerYield > 0) {
                        $('#editAdditionalPricePerPieceRow').removeClass('hidden');
                        $('#editAdditionalPricePerPieceDisplay').text('₱ ' + additionalPricePerPiece.toFixed(2));
                        $('#editTotalPricePerPieceRow').removeClass('hidden');
                        $('#editTotalPricePerPieceDisplay').text('₱ ' + totalPricePerPiece.toFixed(2));
                    } else {
                        $('#editAdditionalPricePerPieceRow').addClass('hidden');
                        $('#editTotalPricePerPieceRow').addClass('hidden');
                    }
                    
                    if (editCombinedRecipesList.length > 0 && traysPerYield > 0 && piecesPerYield > 0) {
                        $('#editAdditionalPricePerTrayRow').removeClass('hidden');
                        $('#editAdditionalPricePerTrayDisplay').text('₱ ' + additionalPricePerTray.toFixed(2));
                        $('#editTotalPricePerTrayRow').removeClass('hidden');
                        $('#editTotalPricePerTrayDisplay').text('₱ ' + totalPricePerTray.toFixed(2));
                    } else {
                        $('#editAdditionalPricePerTrayRow').addClass('hidden');
                        $('#editTotalPricePerTrayRow').addClass('hidden');
                    }

                    // Update label based on whether Trays is filled
                    if (traysPerYield > 0) {
                        $('#editPiecesLabel').text('Pieces per Tray:');
                    } else {
                        $('#editPiecesLabel').text('Pieces/Slices/Plates:');
                    }

                    // Calculate Selling Price per Tray and per Piece (Recommended)
                    // Use total price (including additional) for recommended calculations
                    const recommendedPricePerTray = traysPerYield > 0 ? totalPricePerTray * (1 + profitMargin / 100) : 0;
                    const recommendedPricePerPiece = totalPricePerPiece > 0 ? totalPricePerPiece * (1 + profitMargin / 100) : 0;

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
                        
                        // Enable additional recipe inputs when pieces per yield is set
                        $('#editAdditionalYieldWarning').addClass('hidden');
                        $('#editAdditionalRecipeInputs').removeClass('hidden');
                    } else {
                        $('#editSellingPricePerPieceRow').addClass('hidden');
                        
                        // Disable additional recipe inputs when no pieces per yield
                        $('#editAdditionalYieldWarning').removeClass('hidden');
                        $('#editAdditionalRecipeInputs').addClass('hidden');
                    }
                } else {
                    $('#editYieldComputationSection').addClass('hidden');
                    $('#editSellingPricePerTrayRow').addClass('hidden');
                    $('#editSellingPricePerPieceRow').addClass('hidden');
                    
                    // Disable additional recipe inputs when no yield computation
                    $('#editAdditionalYieldWarning').removeClass('hidden');
                    $('#editAdditionalRecipeInputs').addClass('hidden');
                }

                $('#editDirectCostDisplay').text('₱ ' + directCost.toFixed(2));
                // Update Step 2 direct cost display
                $('#editStep2DirectCostDisplay').text('₱ ' + directCost.toFixed(2));
                
                // Show/hide combined cost card based on whether there are combined recipes
                if (editCombinedRecipesList.length > 0) {
                    $('#editCombinedCostCard').removeClass('hidden');
                    $('#editCombinedCostDisplay').text('₱ ' + combinedCost.toFixed(2));
                    $('#editDirectCostCard').removeClass('col-span-2').addClass('col-span-1');
                } else {
                    $('#editCombinedCostCard').addClass('hidden');
                    $('#editDirectCostCard').removeClass('col-span-1').addClass('col-span-2');
                }
                
                $('#editTotalCostDisplay').text('₱ ' + totalCost.toFixed(2));
                $('#editProfitAmountDisplay').text('₱ ' + profitAmount.toFixed(2));
                $('#editRecommendedPriceOverall').text('₱ ' + sellingPrice.toFixed(2));
            }

            // Recalculate combined recipes when pieces per yield changes (Edit modal)
            function recalculateEditCombinedRecipes() {
                const piecesPerYield = parseInt($('#editPiecesPerYield').val()) || 0;
                const traysPerYield = parseInt($('#editTraysPerYield').val()) || 0;
                
                // Calculate total pieces: if trays > 0, pieces is per tray, so multiply
                const totalPieces = traysPerYield > 0 ? traysPerYield * piecesPerYield : piecesPerYield;
                
                editCombinedRecipesList.forEach(function(item) {
                    // Recalculate total cost based on total pieces
                    const costPerProductPiece = item.costPerUnit * item.gramsPerPiece;
                    item.costPerProductPiece = costPerProductPiece;
                    item.totalCost = costPerProductPiece * totalPieces;
                });
                
                updateEditCombinedRecipesListDisplay();
            }

            // Recalculate edit costing on overhead/profit change
            $('#editOverheadCost, #editProfitMargin').on('input', function() {
                updateEditCostingDisplay();
            });

            // Handle yield field changes with specific field tracking (Edit modal)
            $('#editPiecesPerYield').on('input', function() {
                recalculateEditCombinedRecipes();
                updateEditCostingDisplay('editPiecesPerYield');
            });

            $('#editTraysPerYield').on('input', function() {
                recalculateEditCombinedRecipes();
                updateEditCostingDisplay('editTraysPerYield');
            });

            $('#editGramsPerPiece').on('input', function() {
                recalculateEditCombinedRecipes();
                updateEditCostingDisplay('editGramsPerPiece');
            });

            $('#editGramsPerTray').on('input', function() {
                recalculateEditCombinedRecipes();
                updateEditCostingDisplay('editGramsPerTray');
            });

            // Submit Edit Product Form via AJAX
            $('#editProductForm').on('submit', function(e) {
                e.preventDefault();

                // Calculate costs before submission
                const directCost = editIngredientsList.reduce((sum, item) => sum + item.totalCost, 0);
                const combinedRecipeCost = editCombinedRecipesList.reduce((sum, item) => sum + item.totalCost, 0);
                const overheadPercentage = parseFloat($('#editOverheadCost').val()) || 0;
                const overheadCost = directCost * (overheadPercentage / 100);
                // Combined cost is NOT added to totalCost - it's calculated per piece separately
                const totalCost = directCost + overheadCost;
                const profitMargin = parseFloat($('#editProfitMargin').val()) || 0;
                const profitAmount = totalCost * (profitMargin / 100);

                // Calculate yield info
                // Check if all ingredients are in grams or ml (ml can be treated as grams for yield calculation)
                const allowedUnitsForYield = ['grams', 'ml', 'g'];
                const allIngredientsInGrams = editIngredientsList.length > 0 && editIngredientsList.every(item => allowedUnitsForYield.includes(item.unit.toLowerCase()));
                const yieldGrams = allIngredientsInGrams ? editIngredientsList.reduce((sum, item) => sum + item.quantity, 0) : 0;
                const traysPerYield = parseInt($('#editTraysPerYield').val()) || 0;
                const piecesPerYield = parseInt($('#editPiecesPerYield').val()) || 0;
                const gramsPerTray = parseFloat($('#editGramsPerTray').val()) || 0;
                const gramsPerPiece = parseFloat($('#editGramsPerPiece').val()) || 0;

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
                    combined_recipes: editCombinedRecipesList.map(item => ({
                        product_id: item.id,
                        quantity: item.quantity,
                        unit: item.unit,
                        cost_per_unit: item.costPerUnit,
                        total_cost: item.totalCost
                    })),
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
                    pieces_per_yield: piecesPerYield,
                    grams_per_tray: gramsPerTray,
                    grams_per_piece: gramsPerPiece
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
                    success: function(response) {
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
                    error: function(xhr, status, error) {
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
            $(document).on('click', '.product-row', function(e) {
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
                    success: function(response) {
                        if (response.success && response.data) {
                            const product = response.data;
                            console.log('View Product Data:', product);

                            // Set product name and category
                            $('#viewProductName').text(product.product_name);
                            let categoryBadge = '';
                            if (product.category === 'bread') {
                                categoryBadge = '<i class="fas fa-bread-slice me-1"></i>Bread';
                            } else if (product.category === 'dough') {
                                categoryBadge = '<i class="fas fa-cookie-bite me-1"></i>Dough';
                            } else if (product.category === 'drinks') {
                                categoryBadge = '<i class="fas fa-coffee me-1"></i>Drinks';
                            } else {
                                categoryBadge = '<i class="fas fa-box me-1"></i>' + (product.category || 'Unknown');
                            }
                            $('#viewProductCategory').html(categoryBadge);

                            // Populate ingredients list
                            let ingredientsHtml = '';
                            if (product.ingredients && product.ingredients.length > 0) {
                                product.ingredients.forEach(function(ing) {
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
                                product.combined_recipes.forEach(function(recipe) {
                                    const totalCost = parseFloat(recipe.total_cost) || 0;
                                    const costPerGram = parseFloat(recipe.cost_per_gram) || 0;
                                    combinedHtml += `
                                        <div class="flex justify-between items-center p-2 hover:bg-amber-100">
                                            <div>
                                                <span class="text-sm font-medium text-gray-800">${recipe.product_name}</span>
                                                <div class="text-xs text-gray-500">${recipe.quantity} ${recipe.unit} × ₱${costPerGram.toFixed(3)}/g</div>
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

                            // Populate yield information with detailed computation
                            const yieldGrams = parseFloat(product.yield_grams || 0);
                            const traysPerYield = parseInt(product.trays_per_yield || 0);
                            const piecesPerYield = parseInt(product.pieces_per_yield || 0);
                            const totalCost = parseFloat(product.total_cost || 0);
                            const combinedRecipeCost = parseFloat(product.combined_recipe_cost || 0);

                            if (yieldGrams > 0 || traysPerYield > 0 || piecesPerYield > 0) {
                                $('#viewYieldSection').removeClass('hidden');
                                
                                // Display total yield
                                $('#viewYieldGrams').text(yieldGrams.toFixed(2) + ' g');
                                
                                // Calculate unit price per gram
                                const unitPricePerGram = yieldGrams > 0 ? totalCost / yieldGrams : 0;
                                $('#viewUnitPricePerGram').text('₱ ' + unitPricePerGram.toFixed(3));
                                
                                // Calculate and display per tray information
                                if (traysPerYield > 0) {
                                    $('#viewPerTraySection').removeClass('hidden');
                                    $('#viewTraysPerYield').text(traysPerYield);
                                    
                                    // Use database value if available, otherwise calculate
                                    const gramsPerTray = parseFloat(product.grams_per_tray) || (yieldGrams / traysPerYield);
                                    const unitPricePerTray = totalCost / traysPerYield;
                                    
                                    $('#viewGramsPerTray').text(gramsPerTray.toFixed(2) + ' g');
                                    $('#viewUnitPricePerTray').text('₱ ' + unitPricePerTray.toFixed(2));
                                    
                                    // Calculate additional price per tray if there are combined recipes
                                    if (product.combined_recipes && product.combined_recipes.length > 0 && piecesPerYield > 0) {
                                        const additionalPricePerPiece = product.combined_recipes.reduce((sum, recipe) => {
                                            const costPerGram = parseFloat(recipe.cost_per_gram) || 0;
                                            const gramsPerPiece = parseFloat(recipe.quantity) || 0;
                                            return sum + (costPerGram * gramsPerPiece);
                                        }, 0);
                                        const additionalPricePerTray = additionalPricePerPiece * piecesPerYield;
                                        
                                        $('#viewAdditionalPricePerTrayRow').removeClass('hidden');
                                        $('#viewAdditionalPricePerTray').text('₱ ' + additionalPricePerTray.toFixed(2));
                                        
                                        const totalPricePerTray = unitPricePerTray + additionalPricePerTray;
                                        $('#viewTotalPricePerTrayRow').removeClass('hidden');
                                        $('#viewTotalPricePerTray').text('₱ ' + totalPricePerTray.toFixed(2));
                                    } else {
                                        $('#viewAdditionalPricePerTrayRow').addClass('hidden');
                                        $('#viewTotalPricePerTrayRow').addClass('hidden');
                                    }
                                } else {
                                    $('#viewPerTraySection').addClass('hidden');
                                }
                                
                                // Calculate and display per piece information
                                if (piecesPerYield > 0) {
                                    $('#viewPerPieceSection').removeClass('hidden');
                                    
                                    // Update label based on whether trays exist
                                    if (traysPerYield > 0) {
                                        $('#viewPiecesLabelText').text('Pieces per Tray');
                                        $('#viewPiecesPerYield').text(piecesPerYield);
                                        
                                        // Use database value if available, otherwise calculate
                                        const gramsPerTray = parseFloat(product.grams_per_tray) || (yieldGrams / traysPerYield);
                                        const gramsPerPiece = parseFloat(product.grams_per_piece) || (gramsPerTray / piecesPerYield);
                                        const unitPricePerTray = totalCost / traysPerYield;
                                        const unitPricePerPiece = unitPricePerTray / piecesPerYield;
                                        
                                        $('#viewGramsPerPiece').text(gramsPerPiece.toFixed(2) + ' g');
                                        $('#viewUnitPricePerPiece').text('₱ ' + unitPricePerPiece.toFixed(2));
                                    } else {
                                        $('#viewPiecesLabelText').text('Pieces/Slices/Plates');
                                        $('#viewPiecesPerYield').text(piecesPerYield);
                                        
                                        // Use database value if available, otherwise calculate
                                        const gramsPerPiece = parseFloat(product.grams_per_piece) || (yieldGrams / piecesPerYield);
                                        const unitPricePerPiece = totalCost / piecesPerYield;
                                        
                                        $('#viewGramsPerPiece').text(gramsPerPiece.toFixed(2) + ' g');
                                        $('#viewUnitPricePerPiece').text('₱ ' + unitPricePerPiece.toFixed(2));
                                    }
                                    
                                    // Calculate additional price per piece if there are combined recipes
                                    if (product.combined_recipes && product.combined_recipes.length > 0) {
                                        const additionalPricePerPiece = product.combined_recipes.reduce((sum, recipe) => {
                                            const costPerGram = parseFloat(recipe.cost_per_gram) || 0;
                                            const gramsPerPiece = parseFloat(recipe.quantity) || 0;
                                            return sum + (costPerGram * gramsPerPiece);
                                        }, 0);
                                        
                                        $('#viewAdditionalPricePerPieceRow').removeClass('hidden');
                                        $('#viewAdditionalPricePerPiece').text('₱ ' + additionalPricePerPiece.toFixed(2));
                                        
                                        let unitPricePerPiece = 0;
                                        if (traysPerYield > 0) {
                                            const unitPricePerTray = totalCost / traysPerYield;
                                            unitPricePerPiece = unitPricePerTray / piecesPerYield;
                                        } else {
                                            unitPricePerPiece = totalCost / piecesPerYield;
                                        }
                                        
                                        const totalPricePerPiece = unitPricePerPiece + additionalPricePerPiece;
                                        $('#viewTotalPricePerPieceRow').removeClass('hidden');
                                        $('#viewTotalPricePerPiece').text('₱ ' + totalPricePerPiece.toFixed(2));
                                    } else {
                                        $('#viewAdditionalPricePerPieceRow').addClass('hidden');
                                        $('#viewTotalPricePerPieceRow').addClass('hidden');
                                    }
                                } else {
                                    $('#viewPerPieceSection').addClass('hidden');
                                }
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
                    error: function(xhr, status, error) {
                        Toast.error('Error loading product: ' + error);
                    }
                });
            }

            // Close View Modal
            $('#btnCloseViewModal, #btnViewClose').on('click', function() {
                closeViewModal();
            });

            function closeViewModal() {
                $('#viewProductModal').addClass('hidden');
                currentViewProductId = null;
            }

            // Edit button in view modal - close view modal and open edit modal
            $('#btnViewEdit').on('click', function() {
                console.log('=== EDIT BUTTON CLICK DEBUG ===');
                console.log('currentViewProductId:', currentViewProductId);
                const productId = currentViewProductId;
                console.log('productId stored:', productId);
                if (productId) {
                    closeViewModal();
                    console.log('Calling openEditModal with ID:', productId);
                    openEditModal(productId);
                } else {
                    console.log('No product ID found!');
                    Toast.error('No product selected to edit.');
                }
            });

            // =====================================================
            // END VIEW PRODUCT MODAL FUNCTIONALITY
            // =====================================================
        });
    </script>