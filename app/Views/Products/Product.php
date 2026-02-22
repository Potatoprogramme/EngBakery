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
                    <h2 id="productListTitle" class="text-2xl font-bold text-gray-800 sm:text-xl sm:font-semibold">
                        Product Lists</h2>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="btnAddMaterial"
                            class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            Add Product
                        </button>
                        <button type="button" id="viewDisabledProducts"
                            class="hidden sm:inline-flex items-center rounded-lg bg-gray-500 px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                            <div id="disabledProductsCount"
                                class="inline-flex items-center justify-center w-5 h-5 mr-2 rounded-full bg-white text-sm font-semibold text-gray-800">
                                0
                            </div>
                            Disabled Products
                        </button>
                        <button type="button" id="viewDisabledProductsMobile"
                            class="sm:hidden inline-flex items-center rounded-lg bg-gray-500 px-4 py-2 text-sm font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            <div id="disabledProductsCountMobile"
                                class="inline-flex items-center justify-center w-5 h-5 mr-2 rounded-full bg-white text-sm font-semibold text-gray-800">
                                0
                            </div>
                            Disabled Products
                        </button>
                        <!-- Enable Export Button -->
                        <!-- <button type="button" id="btnExport"
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            Export
                        </button> -->
                        <!-- Disable Export Button -->
                        <!-- <button type="button" id="btnExport" disabled
                            class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            Export
                        </button> -->
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
            <div class="fixed bottom-6 left-0 right-0 flex justify-center z-30 md:hidden">
                <button type="button" id="btnAddMaterialMobile"
                    class="w-5/6 inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                    Add Product
                </button>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block p-4 bg-white rounded-lg shadow-md overflow-x-auto mb-20 lg:mb-0">
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
                            <th scope="col" class="px-6 py-3 w-px whitespace-nowrap">
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
            <div class="lg:hidden mb-24">
                <!-- Search input for mobile -->
                <div class="mb-3">
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

                <!-- Mobile Pagination -->
                <div id="mobilePagination" class="mt-4 flex items-center justify-center gap-2">
                    <!-- Pagination will be generated via JS -->
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
                <h3 id="productModalTitle" class="text-lg font-semibold text-primary">Add Product</h3>
                <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Progress Stepper -->
            <div class="mb-6">
                <div class="flex items-center w-full px-2 sm:px-4">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center min-w-[60px] sm:min-w-[100px]">
                        <div id="step1Indicator"
                            class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-primary/10 border-2 border-primary text-primary text-xs sm:text-sm font-semibold mb-1 sm:mb-2">
                            1
                        </div>
                        <span id="step1Label"
                            class="text-[9px] sm:text-[12px] font-medium text-primary text-center leading-tight">Product
                            Info</span>
                    </div>
                    <!-- Connector -->
                    <div id="connector1" class="flex-1 h-0.5 bg-gray-300 -mt-5 sm:-mt-6 mx-1 sm:mx-4"></div>
                    <!-- Step 2 -->
                    <div class="flex flex-col items-center min-w-[60px] sm:min-w-[100px]">
                        <div id="step2Indicator"
                            class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full border-2 border-gray-300 text-gray-400 text-xs sm:text-sm font-semibold mb-1 sm:mb-2">
                            2
                        </div>
                        <span id="step2Label"
                            class="text-[9px] sm:text-[12px] font-medium text-gray-400 text-center leading-tight">Ingredients</span>
                    </div>
                    <!-- Connector -->
                    <div id="connector2" class="flex-1 h-0.5 bg-gray-300 -mt-5 sm:-mt-6 mx-1 sm:mx-4"></div>
                    <!-- Step 3 -->
                    <div class="flex flex-col items-center min-w-[60px] sm:min-w-[100px]">
                        <div id="step3Indicator"
                            class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full border-2 border-gray-300 text-gray-400 text-xs sm:text-sm font-semibold mb-1 sm:mb-2">
                            3
                        </div>
                        <span id="step3Label"
                            class="text-[9px] sm:text-[12px] font-medium text-gray-400 text-center leading-tight">Costing</span>
                    </div>
                </div>
            </div>

            <form id="addMaterialForm">
                <input type="hidden" id="product_mode" name="product_mode" value="add">
                <input type="hidden" id="product_id" name="product_id" value="">
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
                            <option value="bakery">Bakery</option>
                            <option value="drinks">Drinks</option>
                            <option value="grocery">Grocery</option>
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
                                    class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                    placeholder="Search ingredient..." autocomplete="off">
                                <button type="button" id="btnClearIngredient"
                                    class="hidden absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div id="ingredient_dropdown"
                                    class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto">
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
                                    placeholder="100" min="1" step="1">
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
                        <p class="text-xs text-amber-600 mb-3">Add other recipes (e.g., Soft Dough) per piece of this
                            product. Set up Trays/Pieces first.</p>

                        <div id="additionalRecipeInputs">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Select Recipe to Add</label>
                                <select id="combinedRecipeSelect"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                                    <option value="">Select a recipe...</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity in Grams (per
                                    piece)</label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <input type="number" id="combinedRecipeGrams" step="0.01" min="0"
                                            placeholder="Enter grams per piece"
                                            class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                                        <span
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">g</span>
                                    </div>
                                    <button type="button" id="btnAddCombinedRecipe"
                                        class="px-3 py-2 text-sm font-medium text-white bg-amber-500 rounded-md hover:bg-amber-600">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <p id="combinedRecipeGramsHint" class="text-xs text-gray-400 mt-1 hidden">
                                    Source recipe: <span id="combinedRecipeSourceGrams">0</span>g/pc available
                                </p>
                            </div>
                        </div>
                        <!-- Combined Recipes List -->
                        <div id="combinedRecipesList" class="space-y-2 max-h-32 overflow-y-auto">
                            <p class="text-xs text-amber-500 text-center py-2">No additional recipes added</p>
                        </div>
                    </div>

                    <!-- Direct Cost Display for Step 2 -->
                    <div
                        class="mb-4 p-3 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-between">
                        <span class="text-sm text-gray-600 font-medium">Direct Cost</span>
                        <span id="step2DirectCostDisplay" class="text-lg font-semibold text-primary">₱ 0.00</span>
                    </div>
                </div>

                <!-- STEP 3: Costing -->
                <div id="addStep3" class="step-content hidden">
                    <!-- Grocery Product Price Section (only shown for grocery category) -->
                    <div id="groceryPriceSection"
                        class="hidden mb-4 p-4 bg-white border-2 border-green-200 rounded-lg shadow-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between mb-4">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                    <i class="fas fa-tag text-green-600 me-1"></i> Product Price
                                </h4>
                                <p class="text-xs text-gray-500">Enter the purchase/acquisition cost of this grocery
                                    item.</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <label for="groceryDirectCost" class="text-sm font-medium text-gray-700">Product Price
                                (Direct Cost) <span class="text-red-500">*</span></label>
                            <div class="flex w-full sm:w-48">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">₱</span>
                                <input type="number" id="groceryDirectCost"
                                    class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-r-md focus:outline-none focus:ring-1 focus:ring-green-500 font-semibold text-green-700"
                                    placeholder="0.00" min="0" step="1">
                            </div>
                        </div>
                    </div>

                    <!-- Costing Container -->
                    <div class="mb-4 p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Costing
                                    Breakdown
                                </h4>
                                <p class="text-xs text-gray-500">Review the cost components and tweak overhead or profit
                                    to
                                    see totals instantly.</p>
                            </div>
                            <div id="totalCostSection" class="text-left sm:text-right">
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
                                    <span class="text-sm text-gray-600">Additional Cost</span>
                                    <span id="combinedCostDisplay" class="text-sm font-medium text-amber-700">₱
                                        0.00</span>
                                </div>
                            </div>

                            <div id="overheadCostSection"
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

                                <div id="yieldGridContainer" class="mt-4 grid gap-3 sm:grid-cols-2">
                                    <div id="perTraySection"
                                        class="p-3 rounded-lg border border-dashed border-gray-300 bg-white">
                                        <h6 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Per
                                            Tray / Box</h6>
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between gap-2">
                                                <label for="traysPerYield"
                                                    class="text-sm text-gray-600">Trays/Boxes</label>
                                                <div class="flex w-32">
                                                    <input type="number" id="traysPerYield"
                                                        class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                                        placeholder="0" min="0" step="1">
                                                    <span
                                                        class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-medium">tray</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between gap-2">
                                                <label for="gramsPerTray" class="text-sm text-gray-600">Grams per
                                                    Tray</label>
                                                <div class="flex w-32">
                                                    <input type="number" id="gramsPerTray"
                                                        class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                                        placeholder="0" min="0" step="1">
                                                    <span
                                                        class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-medium">g</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-600">Unit Price per Tray</span>
                                                <span id="unitPricePerTrayDisplay"
                                                    class="text-sm font-medium text-purple-600">₱ 0.00</span>
                                            </div>
                                            <div id="additionalPricePerTrayRow"
                                                class="hidden flex items-center justify-between">
                                                <span class="text-sm text-amber-600">Additional Price per Tray</span>
                                                <span id="additionalPricePerTrayDisplay"
                                                    class="text-sm font-medium text-amber-600">₱ 0.00</span>
                                            </div>
                                            <div id="totalPricePerTrayRow"
                                                class="hidden flex items-center justify-between border-t border-gray-200 pt-2">
                                                <span class="text-sm text-gray-700 font-semibold">Total Price per
                                                    Tray</span>
                                                <span id="totalPricePerTrayDisplay"
                                                    class="text-sm font-bold text-purple-700">₱ 0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="perPieceSection"
                                        class="p-3 rounded-lg border border-dashed border-gray-300 bg-white">
                                        <h6 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Per
                                            Piece / Slice / Plate</h6>
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between gap-2">
                                                <label for="piecesPerYield" id="piecesLabel"
                                                    class="text-sm text-gray-600">Pieces/Slices/Plates</label>
                                                <div class="flex w-32">
                                                    <input type="number" id="piecesPerYield"
                                                        class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                                        placeholder="0" min="0" step="1">
                                                    <span
                                                        class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-medium">pcs</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between gap-2">
                                                <label for="gramsPerPiece" class="text-sm text-gray-600">Grams per
                                                    Piece</label>
                                                <div class="flex w-32">
                                                    <input type="number" id="gramsPerPiece"
                                                        class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                                        placeholder="0" min="0" step="1">
                                                    <span
                                                        class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-medium">g</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-600">Unit Price per Piece</span>
                                                <span id="unitPricePerPieceDisplay"
                                                    class="text-sm font-medium text-blue-600">₱ 0.00</span>
                                            </div>
                                            <div id="additionalPricePerPieceRow"
                                                class="hidden flex items-center justify-between">
                                                <span class="text-sm text-amber-600">Additional Price per Piece</span>
                                                <span id="additionalPricePerPieceDisplay"
                                                    class="text-sm font-medium text-amber-600">₱ 0.00</span>
                                            </div>
                                            <div id="totalPricePerPieceRow"
                                                class="hidden flex items-center justify-between border-t border-gray-200 pt-2">
                                                <span class="text-sm text-gray-700 font-semibold">Total Price per
                                                    Piece</span>
                                                <span id="totalPricePerPieceDisplay"
                                                    class="text-sm font-bold text-blue-700">₱ 0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="profitMarginSection" class="mt-4 border-t border-gray-200 pt-4 space-y-3">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <label for="profitMargin" class="text-sm text-gray-600">Profit Margin (%)</label>
                                    <p class="text-xs text-gray-500">Adjust to calculate target selling price.</p>
                                </div>
                                <div class="flex w-full sm:w-28">
                                    <input type="number" id="profitMargin"
                                        class="flex-1 w-full px-3 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary"
                                        placeholder="30" min="0" max="99.99" value="30">
                                    <span
                                        class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-100 text-gray-600 text-sm font-medium">
                                        %
                                    </span>
                                </div>
                            </div>
                            <div
                                class="p-3 rounded-lg border border-gray-200 bg-green-50 flex items-center justify-between">
                                <span class="text-sm text-gray-600">Profit Amount</span>
                                <span id="profitAmountDisplay" class="text-sm font-semibold text-green-700">₱
                                    0.00</span>
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
                                        placeholder="0.00" step="1" min="0">
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
                                        placeholder="0.00" step="1" min="0">
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
                                        placeholder="0.00" step="0.01" min="0">
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
                        <button type="button" id="btnBackStep"
                            class="hidden px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </button>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" id="btnCancelAdd"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                        <button type="button" id="btnNextStep"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Next
                            <i class="fas fa-arrow-right ms-1"></i></button>
                        <button type="submit" id="btnSaveMaterial"
                            class="hidden px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Save</button>
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
                <span id="viewProductCategory"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/20 text-primary">
                    Category
                </span>
            </div>

            <!-- Ingredients Section -->
            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">
                    Ingredients
                </h4>
                <div id="viewIngredientsList"
                    class="bg-gray-50 rounded-lg border border-gray-200 divide-y divide-gray-200 max-h-48 overflow-y-auto">
                    <!-- Ingredients will be loaded here -->
                </div>
            </div>

            <!-- Combined Recipes Section (shown only if applicable) -->
            <div id="viewCombinedRecipesSection" class="mb-4 hidden">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-2">
                    Combined Recipes
                </h4>
                <div id="viewCombinedRecipesList"
                    class="bg-amber-50 rounded-lg border border-amber-200 divide-y divide-amber-200 max-h-32 overflow-y-auto">
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
                        <span class="text-sm text-gray-600">Additional Cost</span>
                        <span id="viewCombinedCost" class="text-sm font-medium text-amber-700">₱ 0.00</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Overhead Cost <span id="viewOverheadPercent"
                                class="text-xs text-gray-400">(0%)</span></span>
                        <span id="viewOverheadCost" class="text-sm font-medium text-gray-900">₱ 0.00</span>
                    </div>
                    <div class="border-t border-gray-300 pt-2 flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-700">Total Cost</span>
                        <span id="viewTotalCost" class="text-sm font-bold text-primary">₱ 0.00</span>
                    </div>
                </div>
            </div>

            <!-- Yield Information (shown only for bakery/dough) -->
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
                <div id="viewYieldGridContainer" class="grid gap-3 sm:grid-cols-2">
                    <!-- Per Tray -->
                    <div id="viewPerTraySection"
                        class="hidden p-3 rounded-lg border border-dashed border-gray-300 bg-white">
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
                            <div id="viewTotalPricePerTrayRow"
                                class="hidden flex justify-between items-center border-t border-gray-200 pt-2">
                                <span class="text-gray-700 font-semibold">Total Price per Tray</span>
                                <span id="viewTotalPricePerTray" class="font-bold text-purple-700">₱ 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Per Piece -->
                    <div id="viewPerPieceSection"
                        class="hidden p-3 rounded-lg border border-dashed border-gray-300 bg-white">
                        <h6 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Per Piece / Slice /
                            Plate</h6>
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
                            <div id="viewTotalPricePerPieceRow"
                                class="hidden flex justify-between items-center border-t border-gray-200 pt-2">
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
                    <i class="fas fa-edit me-1"></i> Edit
                </button>
                <button type="button" id="btnViewDelete"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                    <i class="fas fa-trash me-1"></i> Delete
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

    <script>
        $(document).ready(function () {
            const baseUrl = '<?= base_url() ?>';
            let dataTable = null;

            // Mobile pagination variables
            let allProducts = [];
            let filteredProducts = [];
            let currentPage = 1;
            const itemsPerPage = 10;

            // Track disabled products view state
            let showingDisabledOnly = false;

            // Load data on page load
            loadMaterials();
            loadFilterCategories();

            // Helper function to get category badge HTML
            function getCategoryBadge(category) {
                const cat = (category || '').toLowerCase();
                let bgColor, textColor, icon;

                switch (cat) {
                    case 'bakery':
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
                    case 'grocery':
                        bgColor = 'bg-green-100';
                        textColor = 'text-green-700';
                        icon = 'fa-cart-shopping';
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
            $(document).on('click', '.card-menu-btn', function (e) {
                e.stopPropagation();
                const $menu = $(this).siblings('.card-menu');

                // Close all other menus first
                $('.card-menu').not($menu).addClass('hidden');

                // Toggle this menu
                $menu.toggleClass('hidden');
            });

            // Close card menus when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.card-menu-btn, .card-menu').length) {
                    $('.card-menu').addClass('hidden');
                }
            });

            // View button in card menu
            $(document).on('click', '.card-view-btn', function (e) {
                e.stopPropagation();
                const productId = $(this).data('id');
                $('.card-menu').addClass('hidden');
                openViewModal(productId);
            });

            // Click on card to view (excluding menu area)
            $(document).on('click', '.product-card', function (e) {
                if (!$(e.target).closest('.card-menu-btn, .card-menu').length) {
                    const productId = $(this).data('product-id');
                    openViewModal(productId);
                }
            });

            // Mobile search functionality
            $('#mobileSearchInput').on('input', function () {
                const searchTerm = $(this).val().toLowerCase().trim();

                if (searchTerm === '') {
                    filteredProducts = [...allProducts];
                } else {
                    filteredProducts = allProducts.filter(function (product) {
                        const productName = (product.product_name || '').toLowerCase();
                        const category = (product.category || '').toLowerCase();
                        return productName.includes(searchTerm) || category.includes(searchTerm);
                    });
                }

                currentPage = 1;
                renderMobileCards();
            });

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

                // Reset to step 1 and update display
                currentAddStep = 1;
                updateStepDisplay();
            });

            // Track previous category for Add modal
            let addPreviousCategory = '';

            // Handle category change
            $('#category_id').on('change', function () {
                const category = $(this).val();
                const previousCategory = addPreviousCategory;

                // Disable pieces input for dough category
                if (category === 'dough') {
                    $('#piecesPerYield').prop('readonly', true).val(0);
                    $('#piecesLabel').text('Pieces/Slices/Plates');
                    // Hide per tray section for dough
                    $('#perTraySection').addClass('hidden');
                } else if (category === 'bakery') {
                    $('#piecesPerYield').prop('readonly', false);
                    $('#perTraySection').removeClass('hidden');
                } else if (category === 'drinks') {
                    $('#piecesPerYield').prop('readonly', false);
                }

                // Only clear ingredients when switching between incompatible categories
                // (drinks use different units vs bakery/dough which use grams)
                if ((previousCategory === 'bakery' || previousCategory === 'dough') && category === 'drinks') {
                    ingredientsList = [];
                    combinedRecipesList = [];
                    updateIngredientsListDisplay();
                    updateCombinedRecipesListDisplay();
                }

                if (previousCategory === 'drinks' && (category === 'bakery' || category === 'dough')) {
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

                // Update grocery flag and total steps
                isGroceryCategory = (category === 'grocery');
                totalSteps = isGroceryCategory ? 2 : 3;

                if (category === 'grocery') {
                    // Hide combined recipes and yield computation for grocery
                    $('.combined-recipe-toggle-wrapper').addClass('hidden');
                    $('#enableCombinedRecipes').prop('checked', false);
                    $('#combinedRecipeSection').addClass('hidden');
                    $('#combinedCostCard').addClass('hidden');
                    $('#directCostCard').removeClass('col-span-1').addClass('col-span-2');
                    $('#yieldComputationSection').addClass('hidden');
                    $('#totalCostSection').addClass('hidden');
                    $('#directCostCard').addClass('hidden');
                    $('#profitMarginSection').removeClass('mt-4 border-t border-gray-200 pt-4');

                    // Hide yield-related sections for grocery
                    $('#perTraySection').addClass('hidden');
                    $('#perPieceSection').addClass('hidden');

                    // Show grocery-specific Product Price input
                    $('#groceryPriceSection').removeClass('hidden');

                    // Hide overhead cost section for grocery
                    $('#overheadCostSection').addClass('hidden');
                    $('#overheadCost').val(0); // Reset overhead to 0

                    // Hide per tray/piece selling price rows for grocery
                    $('#sellingPricePerTrayRow').addClass('hidden');
                    $('#sellingPricePerPieceRow').addClass('hidden');

                    // Show all unit options for grocery
                    $('#ingredient_unit option').show();

                    // Update step display for grocery (2 steps)
                    updateStepDisplay();
                } else if (category === 'drinks') {
                    // Hide combined recipes and yield computation for drinks
                    $('.combined-recipe-toggle-wrapper').addClass('hidden');
                    $('#enableCombinedRecipes').prop('checked', false);
                    $('#combinedRecipeSection').addClass('hidden');
                    $('#combinedCostCard').addClass('hidden');
                    $('#directCostCard').removeClass('col-span-1').addClass('col-span-2');
                    $('#yieldComputationSection').addClass('hidden');

                    // Show per tray section and enable pieces input for drinks
                    $('#perTraySection').removeClass('hidden');
                    $('#perPieceSection').removeClass('hidden');
                    $('#piecesPerYield').prop('disabled', false);

                    // Enable Grams per Tray/Piece inputs for drinks
                    $('#gramsPerTray').prop('disabled', false);
                    $('#gramsPerPiece').prop('disabled', false);

                    // Hide grocery-specific section
                    $('#groceryPriceSection').addClass('hidden');

                    // Show overhead cost section for drinks
                    $('#overheadCostSection').removeClass('hidden');

                    // Show all unit options for drinks
                    $('#ingredient_unit option').show();

                    // Update step display for non-grocery (3 steps)
                    updateStepDisplay();
                } else if (category === 'bakery' || category === 'dough') {
                    if (category === 'bakery') {
                        // Show combined recipes section for bakery only
                        $('#combinedRecipeSection').removeClass('hidden');
                        $('#combinedCostCard').removeClass('hidden');
                        $('#directCostCard').removeClass('col-span-2').addClass('col-span-1');

                        // Show per tray section and enable pieces input for bakery
                        $('#perTraySection').removeClass('hidden');
                        $('#perPieceSection').removeClass('hidden');
                        $('#piecesPerYield').prop('disabled', false);

                        // Disable Grams per Tray/Piece inputs for bakery (they should not be editable)
                        $('#gramsPerTray').prop('disabled', true);
                        $('#gramsPerPiece').prop('disabled', true);

                        // Bread uses 2 columns layout
                        $('#yieldGridContainer').addClass('sm:grid-cols-2').removeClass('sm:grid-cols-1');
                        $('#perPieceSection').removeClass('col-span-2');
                    } else {
                        // Hide combined recipes section for dough
                        $('#combinedRecipeSection').addClass('hidden');
                        $('#combinedCostCard').addClass('hidden');
                        $('#directCostCard').removeClass('col-span-1').addClass('col-span-2');

                        // Hide per tray section for dough and make pieces readonly (calculated automatically)
                        $('#perTraySection').addClass('hidden');
                        $('#perPieceSection').removeClass('hidden');
                        $('#piecesPerYield').prop('disabled', true);
                        $('#traysPerYield').val(0);
                        $('#gramsPerTray').val(0);

                        // Dough uses 1 column layout (full width for Per Piece)
                        $('#yieldGridContainer').removeClass('sm:grid-cols-2').addClass('sm:grid-cols-1');
                        $('#perPieceSection').addClass('col-span-2');
                    }

                    // Hide grocery-specific section
                    $('#groceryPriceSection').addClass('hidden');

                    // Show overhead cost section for bakery/dough
                    $('#overheadCostSection').removeClass('hidden');

                    // Hide all units except grams for bakery and dough
                    $('#ingredient_unit option').hide();
                    $('#ingredient_unit option[value="grams"]').show();
                    $('#ingredient_unit').val('grams');

                    // Update step display for non-grocery (3 steps)
                    updateStepDisplay();
                } else {
                    // Default state when no category selected
                    $('#combinedRecipeSection').addClass('hidden');
                    $('#combinedCostCard').addClass('hidden');
                    $('#directCostCard').removeClass('col-span-1').addClass('col-span-2');
                    $('#ingredient_unit option').show();

                    // Show per tray section and enable pieces input by default
                    $('#perTraySection').removeClass('hidden');
                    $('#perPieceSection').removeClass('hidden');
                    $('#piecesPerYield').prop('disabled', false);

                    // Enable Grams per Tray/Piece inputs by default
                    $('#gramsPerTray').prop('disabled', false);
                    $('#gramsPerPiece').prop('disabled', false);

                    // Hide grocery-specific section
                    $('#groceryPriceSection').addClass('hidden');

                    // Show overhead cost section by default
                    $('#overheadCostSection').removeClass('hidden');
                }
            }

            // Allow Enter key in quantity field to add ingredient
            $('#ingredient_quantity').on('keypress', function (e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    $('#btnAddIngredient').click();
                }
            });

            // Allow Enter key in combined recipe grams field to add recipe
            $('#combinedRecipeGrams').on('keypress', function (e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    $('#btnAddCombinedRecipe').click();
                }
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
                addPreviousCategory = ''; // Reset the previous category tracker

                // Reset grocery-specific fields
                isGroceryCategory = false;
                totalSteps = 3;
                $('#groceryDirectCost').val(0);
                $('#groceryPriceSection').addClass('hidden');

                // Reset search inputs
                $('#ingredient_search').val('');
                $('#ingredient_id').val('');
                $('#btnClearIngredient').addClass('hidden');
                hideIngredientDropdown();

                // Reset combined recipes UI
                $('#combinedRecipeSection').addClass('hidden');
                $('#combinedCostCard').addClass('hidden');
                $('#directCostCard').removeClass('col-span-1').addClass('col-span-2');
                $('#combinedRecipeGrams').val('');
                $('#combinedRecipeGramsHint').addClass('hidden');
                $('#combinedRecipeSelect').val('');

                updateIngredientsListDisplay();
                updateCombinedRecipesListDisplay();
                updateCostingDisplay();

                // Reset UI to default state
                $('.combined-recipe-container').removeClass('hidden');
                $('#ingredient_unit option').show();

                // Reset stepper indicators for 3-step mode
                $('#step2Indicator').parent().removeClass('hidden');
                $('#connector2').removeClass('hidden');
                $('#step3Indicator').html('3');

                // Reset mode/title/button
                $('#product_mode').val('add');
                $('#product_id').val('');
                $('#productModalTitle').text('Add Product');
                $('#btnSaveMaterial').text('Save');

                // Reset to step 1
                currentAddStep = 1;
                updateStepDisplay();
            }

            // =====================================================
            // STEP NAVIGATION LOGIC
            // =====================================================
            let currentAddStep = 1;
            let totalSteps = 3; // Default to 3 steps, will change to 2 for grocery
            let isGroceryCategory = false; // Track if grocery category is selected

            // Update step display based on current step
            function updateStepDisplay() {
                // Hide all step content
                $('#addStep1, #addStep2, #addStep3').addClass('hidden');

                // For grocery category: map step 2 to costing (addStep3)
                if (isGroceryCategory) {
                    if (currentAddStep === 1) {
                        $('#addStep1').removeClass('hidden');
                    } else if (currentAddStep === 2) {
                        $('#addStep3').removeClass('hidden'); // Show costing directly
                    }
                } else {
                    // Show current step content
                    $('#addStep' + currentAddStep).removeClass('hidden');
                }

                // Update stepper indicators based on grocery mode
                if (isGroceryCategory) {
                    // For grocery: 2-step display (Product Info -> Costing)
                    // Hide step 2 indicator and connector2
                    $('#step2Indicator').parent().addClass('hidden');
                    $('#connector2').addClass('hidden');

                    // Update step 1 indicator
                    const step1Indicator = $('#step1Indicator');
                    const step1Label = $('#step1Label');
                    if (currentAddStep === 1) {
                        step1Indicator.removeClass('border-gray-300 bg-primary text-white border-0 text-gray-400')
                            .addClass('border-2 border-primary bg-primary/10 text-primary');
                        step1Indicator.html('1');
                        step1Label.removeClass('text-gray-400').addClass('text-primary');
                    } else {
                        step1Indicator.removeClass('border-2 border-gray-300 border-primary bg-primary/10 text-gray-400 text-primary')
                            .addClass('bg-primary text-white border-0');
                        step1Indicator.html('<i class="fas fa-check"></i>');
                        step1Label.removeClass('text-gray-400 text-primary').addClass('text-primary');
                    }

                    // Update step 3 indicator (now becomes step 2 visually)
                    const step3Indicator = $('#step3Indicator');
                    const step3Label = $('#step3Label');
                    if (currentAddStep === 2) {
                        step3Indicator.removeClass('border-gray-300 bg-primary text-white border-0 text-gray-400')
                            .addClass('border-2 border-primary bg-primary/10 text-primary');
                        step3Indicator.html('2');
                        step3Label.removeClass('text-gray-400').addClass('text-primary');
                    } else {
                        step3Indicator.removeClass('border-primary bg-primary/10 bg-primary text-white text-primary border-0')
                            .addClass('border-2 border-gray-300 text-gray-400');
                        step3Indicator.html('2');
                        step3Label.removeClass('text-primary').addClass('text-gray-400');
                    }

                    // Update connector1 for grocery
                    $('#connector1').removeClass('bg-primary bg-gray-300').addClass(currentAddStep > 1 ? 'bg-primary' : 'bg-gray-300');
                } else {
                    // Normal 3-step display
                    // Show step 2 indicator and connector2
                    $('#step2Indicator').parent().removeClass('hidden');
                    $('#connector2').removeClass('hidden');

                    // Reset step 3 indicator to show "3"
                    const step3Indicator = $('#step3Indicator');

                    // Update stepper indicators
                    for (let i = 1; i <= 3; i++) {
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
                }

                // Update button visibility based on grocery mode
                const maxStep = isGroceryCategory ? 2 : 3;

                if (currentAddStep === 1) {
                    $('#btnBackStep').addClass('hidden');
                    $('#btnNextStep').removeClass('hidden');
                    $('#btnSaveMaterial').addClass('hidden');
                } else if (currentAddStep === maxStep) {
                    $('#btnBackStep').removeClass('hidden');
                    $('#btnNextStep').addClass('hidden');
                    $('#btnSaveMaterial').removeClass('hidden');
                } else {
                    $('#btnBackStep').removeClass('hidden');
                    $('#btnNextStep').removeClass('hidden');
                    $('#btnSaveMaterial').addClass('hidden');
                }
            }

            // Next step button click
            $('#btnNextStep').on('click', function () {
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

                // Skip ingredients validation for grocery (step 2 is costing for grocery)
                if (currentAddStep === 2 && !isGroceryCategory) {
                    if (ingredientsList.length === 0) {
                        Toast.warning('Please add at least one ingredient.');
                        return;
                    }
                }

                const maxStep = isGroceryCategory ? 2 : 3;
                if (currentAddStep < maxStep) {
                    currentAddStep++;
                    updateStepDisplay();

                    // Scroll modal to top
                    $('#addMaterialModal .overflow-y-auto').scrollTop(0);
                }
            });

            // Back step button click
            $('#btnBackStep').on('click', function () {
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

            // Current label restriction (null = show all, 'drinks' = show general+drinks, 'bakery' = show general+bakery)
            let currentLabelRestriction = null;

            // Load Ingredients (Raw Materials) for dropdown
            function loadIngredients() {
                $.ajax({
                    url: baseUrl + 'MaterialCosting/GetAll',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            // Store all ingredients data for filtering
                            allIngredientsData = response.data;
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

                allIngredientsData.forEach(function (mat) {
                    const label = (mat.label || 'general').toLowerCase();

                    // Filter logic based on product category
                    let shouldShow = false;

                    if (!category) {
                        shouldShow = true;
                    } else if (label === 'general' || label === '') {
                        shouldShow = true;
                    } else if ((category === 'bakery' || category === 'dough') && label === 'bread') {
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
                    filtered.forEach(function (mat) {
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
            $('#ingredient_search').on('focus', function () {
                showIngredientDropdown($(this).val());
            });

            $('#ingredient_search').on('input', function () {
                showIngredientDropdown($(this).val());
            });

            // Select ingredient from dropdown (Add Modal)
            $(document).on('click', '.ingredient-option', function () {
                const $this = $(this);
                const id = $this.data('id');
                const name = $this.data('name');
                const unit = $this.data('unit');

                $('#ingredient_id').val(id);
                $('#ingredient_search').val(name);
                $('#ingredient_unit').val(unit);
                $('#btnClearIngredient').removeClass('hidden');
                hideIngredientDropdown();
                $('#ingredient_quantity').focus();
            });

            // Clear ingredient selection
            $('#btnClearIngredient').on('click', function () {
                $('#ingredient_id').val('');
                $('#ingredient_search').val('');
                $(this).addClass('hidden');
                $('#ingredient_search').focus();
            });

            // Show/hide clear button based on input
            $('#ingredient_search').on('input', function () {
                if ($(this).val().trim() !== '' && $('#ingredient_id').val() !== '') {
                    $('#btnClearIngredient').removeClass('hidden');
                } else if ($('#ingredient_id').val() === '') {
                    $('#btnClearIngredient').addClass('hidden');
                }
            });

            // Hide dropdown when clicking outside (Add Modal)
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#ingredient_search, #ingredient_dropdown').length) {
                    hideIngredientDropdown();
                }
            })

            // Auto-select unit is now handled in the ingredient-option click event above

            // Add Ingredient to List
            $('#btnAddIngredient').on('click', function () {
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
                $('#btnClearIngredient').addClass('hidden');
                $('#ingredient_search').focus();

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
                    const costPerUnit = parseFloat(item.costPerUnit) || 0;
                    const totalCost = parseFloat(item.totalCost) || 0;

                    html += '<div class="flex items-center justify-between p-2 border border-gray-200 rounded-md bg-white">';
                    html += '<div class="flex-1">';
                    html += '<div class="flex items-center gap-2">';
                    html += '<span class="text-sm font-medium text-gray-800">' + item.name + '</span>';
                    html += labelBadge;
                    html += '</div>';
                    html += '<div class="text-xs text-gray-500">' + item.quantity + ' ' + item.unit + ' × ₱' + costPerUnit.toFixed(5) + ' = ₱' + totalCost.toFixed(2) + '</div>';
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
                    case 'bakery':
                        bgColor = 'bg-amber-100';
                        textColor = 'text-amber-700';
                        break;
                    case 'grocery':
                        bgColor = 'bg-green-100';
                        textColor = 'text-green-700';
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
                const currentCategory = $('#category_id').val();

                // For grocery category, use the grocery direct cost input
                let directCost;
                if (currentCategory === 'grocery') {
                    directCost = parseFloat($('#groceryDirectCost').val()) || 0;
                } else {
                    directCost = ingredientsList.reduce((sum, item) => sum + item.totalCost, 0);
                }

                const combinedCost = combinedRecipesList.reduce((sum, item) => sum + item.totalCost, 0);
                const overheadCost = directCost * parseFloat($('#overheadCost').val()) / 100 || 0;
                // Combined cost is NOT added to totalCost for per-unit pricing - it's calculated per piece separately
                const totalCost = directCost + overheadCost;
                // But for overall selling price calculation, we need to include combined cost
                const totalCostWithCombined = totalCost + combinedCost;
                const profitMargin = Math.min(parseFloat($('#profitMargin').val()) || 0, 99.99); // Cap at 99.99% to avoid infinity
                const targetProfit = totalCostWithCombined / ((100 - profitMargin) / 100);
                const profitAmount = targetProfit - totalCostWithCombined;
                const sellingPrice = targetProfit;

                // Show yield computation section based on category (bakery or dough categories)
                // Yield computation is only available for bakery and dough categories
                const showYieldComputation = (currentCategory === 'bakery' || currentCategory === 'dough') && ingredientsList.length > 0;

                // Show/hide yield computation section based on category
                if (showYieldComputation) {
                    $('#yieldComputationSection').removeClass('hidden');

                    // Auto-calculate total yield from ingredients that are in grams or ml
                    const allowedUnitsForYield = ['grams', 'ml', 'g'];
                    let totalYieldGrams = 0;
                    let yieldContributingCost = 0;

                    ingredientsList.forEach(item => {
                        if (allowedUnitsForYield.includes(item.unit.toLowerCase())) {
                            totalYieldGrams += item.quantity;
                            yieldContributingCost += item.totalCost;
                        }
                    });

                    // Get current input values
                    let piecesPerYield = parseInt($('#piecesPerYield').val()) || 0;
                    let traysPerYield = parseInt($('#traysPerYield').val()) || 0;
                    let gramsPerPiece = parseFloat($('#gramsPerPiece').val()) || 0;
                    let gramsPerTray = parseFloat($('#gramsPerTray').val()) || 0;

                    // Unit price per gram - based only on yield-contributing ingredients (5 decimal places)
                    const unitPricePerGram = totalYieldGrams > 0 ? yieldContributingCost / totalYieldGrams : 0;

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

                    // Calculate unit price per tray (only if gramsPerTray > 0)
                    if (traysPerYield > 0 && gramsPerTray > 0) {
                        unitPricePerTray = totalCost / traysPerYield;
                    }

                    // Handle PIECE calculations
                    if (changedField === 'gramsPerPiece' && gramsPerPiece > 0) {
                        // User entered grams per piece - calculate number of pieces (whole numbers only)
                        if (traysPerYield > 0 && gramsPerTray > 0) {
                            // If trays exist, pieces = pieces per tray (based on grams per tray)
                            piecesPerYield = Math.floor(gramsPerTray / gramsPerPiece);
                            piecesPerTray = piecesPerYield;
                        } else if (totalYieldGrams > 0) {
                            // Direct calculation from total yield
                            piecesPerYield = Math.floor(totalYieldGrams / gramsPerPiece);
                        }
                        $('#piecesPerYield').val(piecesPerYield);
                    } else if (changedField === 'piecesPerYield' && piecesPerYield > 0) {
                        // User entered pieces - calculate grams per piece
                        if (traysPerYield > 0 && gramsPerTray > 0) {
                            // Pieces input = pieces per tray
                            piecesPerTray = piecesPerYield;
                            gramsPerPiece = gramsPerTray / piecesPerTray;
                        } else if (totalYieldGrams > 0) {
                            // Direct calculation
                            gramsPerPiece = totalYieldGrams / piecesPerYield;
                        }
                        $('#gramsPerPiece').val(gramsPerPiece.toFixed(2));
                    }
                    else if (piecesPerYield > 0 && gramsPerPiece === 0 && changedField !== 'gramsPerPiece') {
                        // Only auto-calculate grams per piece if it's currently 0 (not user-entered)
                        if (traysPerYield > 0 && gramsPerTray > 0) {
                            piecesPerTray = piecesPerYield;
                            gramsPerPiece = gramsPerTray / piecesPerTray;
                        } else if (totalYieldGrams > 0) {
                            gramsPerPiece = totalYieldGrams / piecesPerYield;
                        }
                    }

                    // Calculate unit price per piece (only if gramsPerPiece > 0)
                    // For dough: use the INPUT value of gramsPerPiece directly, not recalculated value
                    const inputGramsPerPiece = parseFloat($('#gramsPerPiece').val()) || 0;
                    if (piecesPerYield > 0 && inputGramsPerPiece > 0) {
                        const category = $('#category_id').val();

                        if (category === 'dough') {
                            // For dough: multiply grams per piece by unit price per gram
                            // Use INPUT value to ensure consistency regardless of rounding
                            // Use yieldContributingCost (direct cost only, no overhead) for accurate per-gram pricing
                            const doughUnitPricePerGram = totalYieldGrams > 0 ? yieldContributingCost / totalYieldGrams : 0;
                            unitPricePerPiece = inputGramsPerPiece * doughUnitPricePerGram;
                        } else if (traysPerYield > 0) {
                            piecesPerTray = piecesPerYield;
                            unitPricePerPiece = unitPricePerTray / piecesPerTray;
                        } else {
                            unitPricePerPiece = totalCost / piecesPerYield;
                        }
                    }

                    // Yield displays
                    $('#totalYieldGramsDisplay').text(totalYieldGrams.toFixed(2) + ' g');
                    $('#unitPricePerGramDisplay').text('₱ ' + unitPricePerGram.toFixed(5));
                    $('#unitPricePerPieceDisplay').text(unitPricePerPiece > 0 ? '₱ ' + unitPricePerPiece.toFixed(5) : '-');
                    $('#unitPricePerTrayDisplay').text(unitPricePerTray > 0 ? '₱ ' + unitPricePerTray.toFixed(5) : '-');

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
                        $('#additionalPricePerPieceDisplay').text('₱ ' + additionalPricePerPiece.toFixed(5));
                        $('#totalPricePerPieceRow').removeClass('hidden');
                        $('#totalPricePerPieceDisplay').text('₱ ' + totalPricePerPiece.toFixed(5));
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
                    } else {
                        $('#sellingPricePerPieceRow').addClass('hidden');
                    }
                } else {
                    $('#yieldComputationSection').addClass('hidden');
                    // Hide per tray and per piece selling prices when yield computation is hidden
                    $('#sellingPricePerTrayRow').addClass('hidden');
                    $('#sellingPricePerPieceRow').addClass('hidden');
                }

                $('#directCostDisplay').text('₱ ' + directCost.toFixed(3));
                // Update Step 2 direct cost display
                $('#step2DirectCostDisplay').text('₱ ' + directCost.toFixed(3));

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

                combinedRecipesList.forEach(function (item) {
                    // Recalculate total cost based on total pieces
                    const costPerProductPiece = item.costPerUnit * item.gramsPerPiece;
                    item.costPerProductPiece = costPerProductPiece;
                    item.totalCost = costPerProductPiece * totalPieces;
                });

                updateCombinedRecipesListDisplay();
            }

            // Recalculate on overhead/profit/yield change
            $('#overheadCost, #profitMargin').on('input', function () {
                updateCostingDisplay();
            });

            // Handle grocery direct cost input change
            $('#groceryDirectCost').on('input', function () {
                updateCostingDisplay();
            });

            // Handle yield field changes with specific field tracking
            $('#piecesPerYield').on('input', function () {
                recalculateCombinedRecipes();
                updateCostingDisplay('piecesPerYield');
            });

            $('#traysPerYield').on('input', function () {
                recalculateCombinedRecipes();
                updateCostingDisplay('traysPerYield');
            });

            // Handle gramsPerPiece input for dough products
            // Handle gramsPerPiece input for dough products
            $('#gramsPerPiece').on('input', function () {
                const category = $('#category_id').val();
                const gramsPerPiece = parseFloat($(this).val()) || 0;

                if (category === 'dough' && gramsPerPiece > 0) {
                    const allowedUnitsForYield = ['grams', 'ml', 'g'];
                    let totalYieldGrams = 0;

                    ingredientsList.forEach(item => {
                        if (allowedUnitsForYield.includes(item.unit.toLowerCase())) {
                            totalYieldGrams += item.quantity;
                        }
                    });

                    if (totalYieldGrams > 0) {
                        const piecesPerYield = Math.floor(totalYieldGrams / gramsPerPiece);
                        $('#piecesPerYield').val(piecesPerYield);

                        const directCost = ingredientsList.reduce((sum, item) => sum + item.totalCost, 0);
                        const overheadCost = directCost * parseFloat($('#overheadCost').val()) / 100 || 0;
                        const totalCost = directCost + overheadCost;

                        const unitPricePerGram = totalYieldGrams > 0 ? totalCost / totalYieldGrams : 0;
                        const unitPricePerPiece = gramsPerPiece * unitPricePerGram;

                        // Display unit price per gram with 5 decimal places
                        $('#unitPricePerGramDisplay').text('₱ ' + unitPricePerGram.toFixed(5));

                        // Display unit price per piece with 5 decimal places
                        $('#unitPricePerPieceDisplay').text(unitPricePerPiece > 0 ? '₱ ' + unitPricePerPiece.toFixed(5) : '₱ 0.00000');

                        updateCostingDisplay('gramsPerPiece');
                    }
                }
            });

            $('#gramsPerTray').on('input', function () {
                recalculateCombinedRecipes();
                updateCostingDisplay('gramsPerTray');
            });

            // Load Products for Combined Recipes dropdown
            function loadCombinedRecipesDropdown() {
                $.ajax({
                    url: baseUrl + 'Products/GetAll',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success && response.data) {
                            let options = '<option value="">Select a recipe...</option>';
                            response.data.forEach(function (product) {
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
            $('#combinedRecipeSelect').on('change', function () {
                const selectedOption = $(this).find('option:selected');
                const gramsPerPiece = parseFloat(selectedOption.data('grams-per-piece')) || 0;
                const gramsPerTray = parseFloat(selectedOption.data('grams-per-tray')) || 0;
                const recipeName = selectedOption.data('name');
                const recipeId = selectedOption.val();

                // Auto-populate grams input with source recipe's grams per piece
                if (recipeId && gramsPerPiece > 0) {
                    $('#combinedRecipeGrams').val(gramsPerPiece);
                    $('#combinedRecipeSourceGrams').text(gramsPerPiece.toFixed(2));
                    $('#combinedRecipeGramsHint').removeClass('hidden');
                } else {
                    $('#combinedRecipeGrams').val('');
                    $('#combinedRecipeGramsHint').addClass('hidden');
                }
            });

            // Add Combined Recipe
            $('#btnAddCombinedRecipe').on('click', function () {
                const select = $('#combinedRecipeSelect');
                const selectedOption = select.find('option:selected');
                const recipeId = select.val();
                const recipeName = selectedOption.data('name');
                const recipeTotalCost = parseFloat(selectedOption.data('cost')) || 0;
                const recipeYield = parseFloat(selectedOption.data('yield')) || 0;
                const recipePiecesPerYield = parseInt(selectedOption.data('pieces-per-yield')) || 0;
                // Get grams per piece from the user input field
                const gramsPerPiece = parseFloat($('#combinedRecipeGrams').val()) || 0;

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
                    Toast.warning('Please enter a valid quantity in grams per piece.');
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

                // Reset select and grams input
                $('#combinedRecipeSelect').val('');
                $('#combinedRecipeGrams').val('');
                $('#combinedRecipeGramsHint').addClass('hidden');
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
                    $('#combinedRecipesList').html('<p class="text-xs text-gray-500 text-center py-2">No additional recipes added</p>');
                    return;
                }

                let html = '';
                combinedRecipesList.forEach(function (item, index) {
                    html += '<div class="flex items-center justify-between p-2 border border-amber-200 rounded-md bg-white">';
                    html += '<div class="flex-1">';
                    html += '<div class="text-xs font-medium text-gray-800">' + item.name + '</div>';
                    html += '<div class="text-xs text-gray-500">' + item.gramsPerPiece + 'g/pc × ₱' + item.costPerUnit.toFixed(5) + '/g = ₱' + (item.gramsPerPiece * item.costPerUnit).toFixed(5) + '/product pc</div>';
                    html += '</div>';
                    html += '<button type="button" class="text-red-600 hover:text-red-800 btn-remove-combined-recipe" data-index="' + index + '" title="Remove"><i class="fas fa-times"></i></button>';
                    html += '</div>';
                });
                $('#combinedRecipesList').html(html);
            }

            // Calculate cost per unit
            $('#initial_quantity, #total_cost').on('input', function () {
                const qty = parseFloat($('#initial_quantity').val()) || 0;
                const cost = parseFloat($('#total_cost').val()) || 0;
                const perUnit = qty > 0 ? (cost / qty).toFixed(5) : '0.000';
                $('#cost_per_unit_display').text(perUnit);
            });

            // Load Product Categories for Filter dropdown (static enum values)
            function loadFilterCategories() {
                let options = '<option value="">All Product Categories</option>';
                options += '<option value="bakery">Bakery</option>';
                options += '<option value="dough">Dough</option>';
                options += '<option value="drinks">Drinks</option>';
                options += '<option value="grocery">Grocery</option>';
                $('#filter-category').html(options);
            }

            // Load products via AJAX
            function loadMaterials(categoryFilter = '', showDisabledOnly = false) {
                $.ajax({
                    url: baseUrl + 'Products/GetAll',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {

                        // Destroy existing DataTable first
                        if (dataTable) {
                            dataTable.destroy();
                            dataTable = null;
                        }

                        let rows = '';

                        if (response.success && response.data && response.data.length > 0) {
                            // Store all products for mobile pagination
                            allProducts = response.data;

                            // Apply category filter if specified
                            let displayProducts = allProducts;
                            if (categoryFilter !== '') {
                                displayProducts = allProducts.filter(function (product) {
                                    return product.category && product.category.toLowerCase() === categoryFilter.toLowerCase();
                                });
                            }

                            // Filter based on disabled status
                            // is_disabled: 1 = disabled, 0 or undefined = enabled
                            if (showDisabledOnly) {
                                // Show only disabled products
                                displayProducts = displayProducts.filter(function (product) {
                                    return product.is_disabled == 1 || product.is_disabled === '1';
                                });
                            } else {
                                // Show only enabled products (is_disabled is 0, null, or undefined)
                                displayProducts = displayProducts.filter(function (product) {
                                    return !product.is_disabled || product.is_disabled == 0 || product.is_disabled === '0';
                                });
                            }

                            filteredProducts = [...displayProducts];

                            // Count disabled products and update badge
                            const disabledCount = allProducts.filter(p => p.is_disabled == 1 || p.is_disabled === '1').length;
                            $('#disabledProductsCount').text(disabledCount);
                            $('#disabledProductsCountMobile').text(disabledCount);

                            displayProducts.forEach(function (product) {
                                // Desktop table rows
                                rows += '<tr class="hover:bg-neutral-secondary-soft cursor-pointer product-row" data-product-id="' + product.product_id + '" data-category="' + (product.category || '') + '">';
                                rows += '<td class="px-6 py-4 font-medium text-heading whitespace-nowrap">' + product.product_name + '</td>';
                                rows += '<td class="px-6 py-4">' + (product.category || '-') + '</td>';
                                rows += '<td class="px-6 py-4">' + parseFloat(product.direct_cost || 0).toFixed(2) + '</td>';
                                rows += '<td class="px-6 py-4">' + parseFloat(product.total_cost || 0).toFixed(2) + '</td>';
                                rows += '<td class="px-6 py-4">' + parseFloat(product.selling_price || 0).toFixed(2) + '</td>';
                                rows += '<td class="px-6 py-4 w-px whitespace-nowrap">';
                                rows += '<div class="flex items-center gap-2">';
                                rows += '<button class="text-blue-600 h-10 w-10 flex items-center justify-center bg-gray-100 rounded border border-gray-300 hover:text-blue-800 btn-edit" data-id="' + product.product_id + '" title="Edit"><i class="fas fa-edit"></i></button>';
                                // Delete button (commented out)
                                rows += '<button class="text-red-600 py-2 px-3 bg-gray-100 rounded border border-gray-300 hover:text-red-800 btn-delete" data-id="' + product.product_id + '" title="Delete"><i class="fas fa-trash"></i></button>';
                                // Toggle Enable/Disable button (is_disabled: 1 = disabled, 0 = enabled)
                                var isEnabled = !product.is_disabled || product.is_disabled == 0 || product.is_disabled === '0';
                                if (isEnabled) {
                                    rows += '<button class="text-green-600 h-10 w-12 flex items-center justify-center bg-green-50 rounded border border-green-300 hover:bg-green-100 btn-toggle" data-id="' + product.product_id + '" data-enabled="true" title="Click to Disable"><i class="fas fa-toggle-on text-lg"></i></button>';
                                } else {
                                    rows += '<button class="text-gray-400 h-10 w-12 flex items-center justify-center bg-gray-100 rounded border border-gray-300 hover:bg-gray-200 btn-toggle" data-id="' + product.product_id + '" data-enabled="false" title="Click to Enable"><i class="fas fa-toggle-off text-lg"></i></button>';
                                }
                                rows += '</div>';
                                rows += '</td>';
                                rows += '</tr>';
                            });

                            // Render mobile cards with pagination
                            currentPage = 1;
                            renderMobileCards();

                            $('#mobileNoResults').addClass('hidden');
                        } else {
                            allProducts = [];
                            filteredProducts = [];
                            $('#mobileCardsContainer').html('<div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500"><i class="fas fa-box-open text-4xl mb-3"></i><p>No products found</p></div>');
                            $('#mobilePagination').html('');
                            $('#mobileNoResults').addClass('hidden');
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
                        allProducts = [];
                        filteredProducts = [];

                        // Reset disabled products count
                        $('#disabledProductsCount').text('0');
                        $('#disabledProductsCountMobile').text('0');

                        // Still initialize DataTable on error to show controls
                        if (dataTable) {
                            dataTable.destroy();
                            dataTable = null;
                        }
                        $('#materialsTableBody').html('');
                        $('#mobileCardsContainer').html('<div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500"><i class="fas fa-exclamation-triangle text-4xl mb-3"></i><p>Error loading products</p></div>');
                        $('#mobilePagination').html('');
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

            // Render Mobile Cards with Pagination
            function renderMobileCards() {
                const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const paginatedProducts = filteredProducts.slice(startIndex, endIndex);

                if (paginatedProducts.length === 0) {
                    $('#mobileCardsContainer').html('<div class="p-8 bg-white rounded-lg shadow-md text-center text-gray-500"><i class="fas fa-search text-4xl mb-3"></i><p>No products found</p></div>');
                    $('#mobilePagination').html('');
                    $('#mobileNoResults').addClass('hidden');
                    return;
                }

                let cards = '';
                paginatedProducts.forEach(function (product) {
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
                    cards += '        <button class="btn-delete w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 flex items-center gap-2" data-id="' + product.product_id + '">';
                    cards += '          <i class="fas fa-trash"></i> Delete';
                    cards += '        </button>';
                    cards += '        <div class="border-t border-gray-100 my-1"></div>';
                    // Toggle Enable/Disable button (is_disabled: 1 = disabled, 0 = enabled)
                    var cardIsEnabled = !product.is_disabled || product.is_disabled == 0 || product.is_disabled === '0';
                    if (cardIsEnabled) {
                        cards += '        <button class="btn-toggle w-full px-2 py-3 text-left text-base font-medium text-green-600 hover:bg-green-50 flex items-center gap-3" data-id="' + product.product_id + '" data-enabled="true">';
                        cards += '          <i class="fas fa-toggle-on text-xl"></i> Enabled';
                        cards += '        </button>';
                    } else {
                        cards += '        <button class="btn-toggle w-full px-2 py-3 text-left text-base font-medium text-gray-500 hover:bg-gray-50 flex items-center gap-3" data-id="' + product.product_id + '" data-enabled="false">';
                        cards += '          <i class="fas fa-toggle-off text-xl"></i> Disabled';
                        cards += '        </button>';
                    }
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

                $('#mobileCardsContainer').html(cards);
                $('#mobileNoResults').addClass('hidden');
                renderMobilePagination(totalPages);
            }

            // Render Mobile Pagination
            function renderMobilePagination(totalPages) {
                if (totalPages <= 1) {
                    $('#mobilePagination').html('<p class="text-sm text-gray-500">Showing ' + filteredProducts.length + ' item(s)</p>');
                    return;
                }

                let pagination = '';

                // Previous button
                pagination += '<button class="pagination-btn px-3 py-2 text-sm font-medium rounded-lg border ' + (currentPage === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50') + '" ' + (currentPage === 1 ? 'disabled' : '') + ' data-page="' + (currentPage - 1) + '">';
                pagination += '<i class="fas fa-chevron-left"></i>';
                pagination += '</button>';

                // Page numbers
                const maxVisiblePages = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }

                if (startPage > 1) {
                    pagination += '<button class="pagination-btn px-3 py-2 text-sm font-medium rounded-lg bg-white text-gray-700 hover:bg-gray-50 border" data-page="1">1</button>';
                    if (startPage > 2) {
                        pagination += '<span class="px-2 py-2 text-gray-400">...</span>';
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    pagination += '<button class="pagination-btn px-3 py-2 text-sm font-medium rounded-lg border ' + (i === currentPage ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50') + '" data-page="' + i + '">' + i + '</button>';
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        pagination += '<span class="px-2 py-2 text-gray-400">...</span>';
                    }
                    pagination += '<button class="pagination-btn px-3 py-2 text-sm font-medium rounded-lg bg-white text-gray-700 hover:bg-gray-50 border" data-page="' + totalPages + '">' + totalPages + '</button>';
                }

                // Next button
                pagination += '<button class="pagination-btn px-3 py-2 text-sm font-medium rounded-lg border ' + (currentPage === totalPages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50') + '" ' + (currentPage === totalPages ? 'disabled' : '') + ' data-page="' + (currentPage + 1) + '">';
                pagination += '<i class="fas fa-chevron-right"></i>';
                pagination += '</button>';

                $('#mobilePagination').html(pagination);
            }

            // Handle Mobile Pagination Click
            $(document).on('click', '#mobilePagination .pagination-btn:not([disabled])', function () {
                const page = parseInt($(this).data('page'));
                if (page && page !== currentPage) {
                    currentPage = page;
                    renderMobileCards();
                    // Scroll to top of cards
                    $('html, body').animate({
                        scrollTop: $('#mobileCardsContainer').offset().top - 100
                    }, 300);
                }
            });

            // Submit Add Product Form via AJAX
            $('#addMaterialForm').on('submit', function (e) {
                e.preventDefault();

                const category = $('#category_id').val();
                const isGrocery = (category === 'grocery');

                // Calculate costs before submission
                let directCost;
                if (isGrocery) {
                    directCost = parseFloat($('#groceryDirectCost').val()) || 0;
                } else {
                    directCost = ingredientsList.reduce((sum, item) => sum + item.totalCost, 0);
                }

                const combinedRecipeCost = combinedRecipesList.reduce((sum, item) => sum + item.totalCost, 0);
                const overheadPercentage = parseFloat($('#overheadCost').val()) || 0;
                const overheadCost = directCost * (overheadPercentage / 100);
                // Combined cost is NOT added to totalCost - it's calculated per piece separately
                const totalCost = directCost + overheadCost;
                const profitMargin = parseFloat($('#profitMargin').val()) || 0;
                const profitAmount = totalCost * (profitMargin / 100);

                // Calculate yield info (not applicable for grocery)
                let yieldGrams = 0;
                let traysPerYield = 0;
                let piecesPerYield = 0;
                let gramsPerTray = 0;
                let gramsPerPiece = 0;

                if (!isGrocery) {
                    // Check if all ingredients are in grams or ml (ml can be treated as grams for yield calculation)
                    const allowedUnitsForYield = ['grams', 'ml', 'g'];
                    const allIngredientsInGrams = ingredientsList.length > 0 && ingredientsList.every(item => allowedUnitsForYield.includes(item.unit.toLowerCase()));
                    yieldGrams = allIngredientsInGrams ? ingredientsList.reduce((sum, item) => sum + item.quantity, 0) : 0;
                    traysPerYield = parseInt($('#traysPerYield').val()) || 0;
                    piecesPerYield = parseInt($('#piecesPerYield').val()) || 0;
                    gramsPerTray = parseFloat($('#gramsPerTray').val()) || 0;
                    gramsPerPiece = parseFloat($('#gramsPerPiece').val()) || 0;
                }

                const formData = {
                    product_name: $('#material_name').val(),
                    category: category,
                    overhead_cost_percentage: overheadPercentage,
                    profit_margin_percentage: profitMargin,
                    // Ingredients array (empty for grocery)
                    ingredients: isGrocery ? [] : ingredientsList.map(item => ({
                        material_id: item.id,
                        quantity: item.quantity,
                        unit: item.unit,
                        cost_per_unit: item.costPerUnit,
                        total_cost: item.totalCost
                    })),
                    // Combined recipes array (empty for grocery)
                    combined_recipes: isGrocery ? [] : combinedRecipesList.map(item => ({
                        id: item.id,
                        source_product_id: item.id,
                        grams_per_piece: item.gramsPerPiece,
                        cost_per_gram: item.costPerUnit,
                        total_cost: item.totalCost
                    })),
                    // Costing data
                    direct_cost: directCost,
                    combined_recipe_cost: isGrocery ? 0 : combinedRecipeCost,
                    overhead_cost_amount: overheadCost,
                    total_cost: totalCost,
                    profit_amount: profitAmount,
                    // Selling prices
                    selling_price_overall: parseFloat($('#sellingPriceOverall').val()) || (totalCost + profitAmount),
                    selling_price_per_tray: isGrocery ? 0 : (parseFloat($('#sellingPricePerTray').val()) || 0),
                    selling_price_per_piece: isGrocery ? 0 : (parseFloat($('#sellingPricePerPiece').val()) || 0),
                    // Yield data (not applicable for grocery)
                    yield_grams: yieldGrams,
                    trays_per_yield: traysPerYield,
                    pieces_per_yield: piecesPerYield,
                    grams_per_tray: gramsPerTray,
                    grams_per_piece: gramsPerPiece
                };

                // Validate required fields
                if (!formData.product_name || formData.product_name.trim() === '') {
                    Toast.error('Product name is required.');
                    return;
                }
                if (!formData.category) {
                    Toast.error('Product category is required.');
                    return;
                }

                // For grocery, validate direct cost; for others, validate ingredients
                if (isGrocery) {
                    if (directCost <= 0) {
                        Toast.error('Please enter a valid product price.');
                        return;
                    }
                } else {
                    if (formData.ingredients.length === 0) {
                        Toast.error('Please add at least one ingredient.');
                        return;
                    }
                }

                // Determine mode (add or edit) and set product_id for edit
                const mode = $('#product_mode').val() || 'add';
                if (mode === 'edit') {
                    formData.product_id = $('#product_id').val();
                }

                const ajaxUrl = mode === 'edit' ? baseUrl + 'Products/UpdateProduct' : baseUrl + 'Products/AddProduct';
                const submitBtn = $('#btnSaveMaterial');

                // Prevent double submission
                if (typeof ButtonLoader !== 'undefined' && ButtonLoader.isLoading(submitBtn)) {
                    return;
                }

                if (typeof ButtonLoader !== 'undefined') {
                    ButtonLoader.start(submitBtn, mode === 'edit' ? 'Updating...' : 'Saving...');
                }

                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function (response) {
                        if (typeof ButtonLoader !== 'undefined') {
                            ButtonLoader.stop(submitBtn);
                        }
                        if (response.success) {
                            Toast.success(mode === 'edit' ? 'Product updated successfully!' : 'Product added successfully!');
                            closeModal();
                            loadMaterials();
                        } else {
                            Toast.error('Error: ' + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        if (typeof ButtonLoader !== 'undefined') {
                            ButtonLoader.stop(submitBtn);
                        }
                        Toast.error((mode === 'edit' ? 'Error updating product: ' : 'Error adding product: ') + error);
                    }
                });
            });

            // Delete button in table row / mobile card
            $(document).on('click', '.btn-delete', function (e) {
                e.stopPropagation();
                const btn = $(this);
                const id = btn.data('id');

                if (!id) {
                    Toast.error('No product selected to delete.');
                    return;
                }

                if (typeof ButtonLoader !== 'undefined' && ButtonLoader.isLoading(btn)) {
                    return;
                }

                Confirm.delete('Are you sure you want to delete this product?', function () {
                    if (typeof ButtonLoader !== 'undefined') {
                        ButtonLoader.start(btn, '');
                    }
                    $.ajax({
                        url: baseUrl + 'Products/DeleteProduct/' + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            if (typeof ButtonLoader !== 'undefined') {
                                ButtonLoader.stop(btn);
                            }
                            if (response.success) {
                                Toast.success('Product deleted successfully!');
                                loadMaterials();
                            } else {
                                Toast.error('Error: ' + response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            if (typeof ButtonLoader !== 'undefined') {
                                ButtonLoader.stop(btn);
                            }
                            Toast.error('Error deleting product: ' + error);
                        }
                    });
                });
            });

            $('#btnViewDelete').on('click', function () {
                const id = currentViewProductId;
                const btn = $(this);

                if (!id) {
                    Toast.error('No product selected to delete.');
                    return;
                }

                // Prevent double click
                if (typeof ButtonLoader !== 'undefined' && ButtonLoader.isLoading(btn)) {
                    return;
                }

                Confirm.delete('Are you sure you want to delete this product?', function () {
                    if (typeof ButtonLoader !== 'undefined') {
                        ButtonLoader.start(btn, '');
                    }
                    $.ajax({
                        url: baseUrl + 'Products/DeleteProduct/' + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            if (typeof ButtonLoader !== 'undefined') {
                                ButtonLoader.stop(btn);
                            }
                            if (response.success) {
                                Toast.success('Product deleted successfully!');
                                closeViewModal();
                                loadMaterials();
                            } else {
                                Toast.error('Error: ' + response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            if (typeof ButtonLoader !== 'undefined') {
                                ButtonLoader.stop(btn);
                            }
                            Toast.error('Error deleting product: ' + error);
                        }
                    });
                });
            });


            // Toggle Enable/Disable Product (Frontend only - no backend)
            $(document).on('click', '.btn-toggle', function (e) {
                e.stopPropagation(); // Prevent event bubbling to parent elements

                const btn = $(this);
                const productId = btn.data('id');
                const isEnabled = btn.data('enabled') === true || btn.data('enabled') === 'true';
                const isMobileCard = btn.hasClass('w-full');

                // Prevent double click
                if (typeof ButtonLoader !== 'undefined' && ButtonLoader.isLoading(btn)) {
                    return;
                }

                // Toggle the state
                const newState = !isEnabled;

                // Show loading state
                if (typeof ButtonLoader !== 'undefined') {
                    ButtonLoader.start(btn, '');
                }

                // Send AJAX request to backend
                $.ajax({
                    url: baseUrl + 'Products/ToggleProductStatus',
                    type: 'POST',
                    data: JSON.stringify({
                        product_id: productId,
                        is_enabled: newState
                    }),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function (response) {
                        if (typeof ButtonLoader !== 'undefined') {
                            ButtonLoader.stop(btn);
                        }

                        if (response.success) {
                            // Update button state
                            btn.data('enabled', newState);

                            if (newState) {
                                // Set to enabled state
                                btn.removeClass('text-gray-400 bg-gray-100 border-gray-300 hover:bg-gray-200 text-gray-500 hover:bg-gray-50');
                                btn.addClass('text-green-600 bg-green-50 border-green-300 hover:bg-green-100');
                                btn.attr('title', 'Click to Disable');
                                btn.find('i').removeClass('fa-toggle-off').addClass('fa-toggle-on');

                                if (isMobileCard) {
                                    btn.html('<i class="fas fa-toggle-on text-xl"></i> Enabled');
                                }

                                Toast.success('Product enabled successfully!');
                            } else {
                                // Set to disabled state
                                btn.removeClass('text-green-600 bg-green-50 border-green-300 hover:bg-green-100');
                                btn.addClass('text-gray-400 bg-gray-100 border-gray-300 hover:bg-gray-200');
                                btn.attr('title', 'Click to Enable');
                                btn.find('i').removeClass('fa-toggle-on').addClass('fa-toggle-off');

                                if (isMobileCard) {
                                    btn.removeClass('text-green-600').addClass('text-gray-500');
                                    btn.html('<i class="fas fa-toggle-off text-xl"></i> Disabled');
                                }

                                Toast.info('Product disabled successfully!');
                            }

                            // Reload the products list to move product to correct list
                            const categoryFilter = $('#filter-category').val();
                            loadMaterials(categoryFilter, showingDisabledOnly);
                        } else {
                            Toast.error('Error: ' + (response.message || 'Failed to update product status'));
                        }
                    },
                    error: function (xhr, status, error) {
                        if (typeof ButtonLoader !== 'undefined') {
                            ButtonLoader.stop(btn);
                        }
                        Toast.error('Error toggling product status: ' + error);
                    }
                });
            });

            // Apply Filter
            $('#apply-filters').on('click', function () {
                const categoryId = $('#filter-category').val();
                // Reload the table with the selected category filter, respecting disabled view state
                loadMaterials(categoryId, showingDisabledOnly);
            });

            // Reset Filter
            $('#reset-filters').on('click', function () {
                $('#filter-category').val('');
                // Reload the table without any filter, respecting disabled view state
                loadMaterials('', showingDisabledOnly);
                $('#mobileSearchInput').val('');
            });

            // View Disabled Products Button Click (Desktop & Mobile)
            $('#viewDisabledProducts, #viewDisabledProductsMobile').on('click', function () {
                showingDisabledOnly = !showingDisabledOnly;
                const categoryFilter = $('#filter-category').val();

                if (showingDisabledOnly) {
                    // Hide Add Product buttons
                    $('#btnAddMaterial').addClass('sm:hidden').removeClass('sm:inline-flex');
                    $('#btnAddMaterialMobile').addClass('hidden');

                    // Change title to "Disabled Product Lists"
                    $('#productListTitle').text('Disabled Product Lists');

                    // Update desktop button appearance
                    $('#viewDisabledProducts').removeClass('bg-gray-500 hover:bg-gray-600')
                        .addClass('bg-blue-600 hover:bg-blue-700');
                    $('#viewDisabledProducts').html(`
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to All Products
                    `);

                    // Update mobile button appearance
                    $('#viewDisabledProductsMobile').removeClass('bg-gray-500 hover:bg-gray-600')
                        .addClass('bg-blue-600 hover:bg-blue-700');
                    $('#viewDisabledProductsMobile').html(`
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to All Products
                    `);

                    // Reload with disabled filter (respecting current category filter)
                    loadMaterials(categoryFilter, true);
                } else {
                    // Show Add Product buttons
                    $('#btnAddMaterial').removeClass('sm:hidden').addClass('sm:inline-flex');
                    $('#btnAddMaterialMobile').removeClass('hidden');

                    // Change title back to "Product Lists"
                    $('#productListTitle').text('Product Lists');

                    const disabledCount = allProducts.filter(p => p.is_disabled == 1 || p.is_disabled === '1').length;

                    // Update desktop button appearance
                    $('#viewDisabledProducts').removeClass('bg-blue-600 hover:bg-blue-700')
                        .addClass('bg-gray-500 hover:bg-gray-600');
                    $('#viewDisabledProducts').html(`
                        <div id="disabledProductsCount"
                            class="inline-flex items-center justify-center w-5 h-5 mr-2 rounded-full bg-white text-sm font-semibold text-gray-800">
                            ${disabledCount}
                        </div>
                        Disabled Products
                    `);

                    // Update mobile button appearance
                    $('#viewDisabledProductsMobile').removeClass('bg-blue-600 hover:bg-blue-700')
                        .addClass('bg-gray-500 hover:bg-gray-600');
                    $('#viewDisabledProductsMobile').html(`
                        <div id="disabledProductsCountMobile"
                            class="inline-flex items-center justify-center w-5 h-5 mr-2 rounded-full bg-white text-sm font-semibold text-gray-800">
                            ${disabledCount}
                        </div>
                        Disabled Products
                    `);

                    // Reload without disabled filter (respecting current category filter)
                    loadMaterials(categoryFilter, false);
                }
            });

            // =====================================================
            // EDIT PRODUCT MODAL FUNCTIONALITY (merged)
            // =====================================================

            // Open Edit Product Modal: reuse Add modal
            $(document).on('click', '.btn-edit', function () {
                const productId = $(this).data('id');
                if (productId) openEditModal(productId);
            });

            function openEditModal(productId) {
                // Load RawMaterials and Products (for combined recipes dropdown) first
                $.when(
                    $.ajax({ url: baseUrl + 'MaterialCosting/GetAll', type: 'GET', dataType: 'json' }),
                    $.ajax({ url: baseUrl + 'Products/GetAll', type: 'GET', dataType: 'json' })
                ).done(function (rawResp, prodResp) {
                    if (rawResp[0] && rawResp[0].success) allIngredientsData = rawResp[0].data;

                    // populate combined recipes dropdown (same logic as loadCombinedRecipesDropdown)
                    if (prodResp[0] && prodResp[0].success) {
                        let options = '<option value="">Select a recipe...</option>';
                        prodResp[0].data.forEach(function (product) {
                            if (product.category === 'drinks') return;
                            const yieldGrams = parseFloat(product.yield_grams) || 0;
                            const piecesPerYield = parseInt(product.pieces_per_yield) || 0;
                            const traysPerYield = parseInt(product.trays_per_yield) || 0;
                            let gramsPerPiece = parseFloat(product.grams_per_piece) || 0;
                            let gramsPerTray = parseFloat(product.grams_per_tray) || 0;
                            if (gramsPerPiece === 0 && yieldGrams > 0) {
                                if (traysPerYield > 0 && gramsPerTray === 0) gramsPerTray = yieldGrams / traysPerYield;
                                if (piecesPerYield > 0) {
                                    if (traysPerYield > 0) gramsPerPiece = gramsPerTray / piecesPerYield;
                                    else gramsPerPiece = yieldGrams / piecesPerYield;
                                }
                            }
                            options += '<option value="' + product.product_id + '" data-name="' + product.product_name + '" data-cost="' + (product.direct_cost || 0) + '" data-yield="' + yieldGrams + '" data-grams-per-piece="' + gramsPerPiece.toFixed(2) + '" data-grams-per-tray="' + gramsPerTray.toFixed(2) + '" data-pieces-per-yield="' + piecesPerYield + '" data-trays-per-yield="' + traysPerYield + '">' + product.product_name + '</option>';
                        });
                        $('#combinedRecipeSelect').html(options);
                    }

                    // Now fetch product details
                    $.ajax({
                        url: baseUrl + 'Products/GetProduct/' + productId,
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            if (response.success && response.data) {
                                const product = response.data;

                                // set mode to edit and populate Add modal fields
                                $('#product_mode').val('edit');
                                $('#product_id').val(product.product_id);
                                $('#productModalTitle').text('Edit Product');
                                $('#btnSaveMaterial').text('Update');

                                $('#material_name').val(product.product_name);
                                $('#category_id').val(product.category);
                                addPreviousCategory = product.category;

                                // Check if this is a grocery product
                                const isGroceryProduct = (product.category === 'grocery');
                                isGroceryCategory = isGroceryProduct;
                                totalSteps = isGroceryProduct ? 2 : 3;

                                // For grocery products, set the direct cost input
                                if (isGroceryProduct) {
                                    $('#groceryDirectCost').val(product.direct_cost || 0);
                                }

                                $('#overheadCost').val(product.overhead_cost_percentage || 0);
                                $('#profitMargin').val(product.profit_margin_percentage || 30);
                                $('#traysPerYield').val(product.trays_per_yield || 0);
                                $('#piecesPerYield').val(product.pieces_per_yield || 0);
                                $('#gramsPerPiece').val(product.grams_per_piece || 0);
                                $('#gramsPerTray').val(product.grams_per_tray || 0);
                                $('#sellingPriceOverall').val(product.selling_price_overall || product.selling_price || 0);
                                $('#sellingPricePerTray').val(product.selling_price_per_tray || 0);
                                $('#sellingPricePerPiece').val(product.selling_price_per_piece || 0);

                                // Populate ingredientsList and combinedRecipesList (reuse add modal arrays)
                                ingredientsList = [];
                                if (product.ingredients && product.ingredients.length) {
                                    product.ingredients.forEach(function (ing) {
                                        let ingredientLabel = ing.label || 'general';
                                        if ((!ing.label || ing.label === 'general') && allIngredientsData.length) {
                                            const raw = allIngredientsData.find(m => m.material_id == ing.material_id);
                                            if (raw && raw.label) ingredientLabel = raw.label;
                                        }
                                        ingredientsList.push({
                                            id: ing.material_id,
                                            name: ing.material_name,
                                            quantity: parseFloat(ing.quantity) || 0,
                                            unit: ing.unit,
                                            costPerUnit: parseFloat(ing.cost_per_unit) || 0,
                                            totalCost: parseFloat(ing.total_cost) || 0,
                                            label: ingredientLabel
                                        });
                                    });
                                }

                                combinedRecipesList = [];
                                if (product.combined_recipes && product.combined_recipes.length) {
                                    product.combined_recipes.forEach(function (r) {
                                        combinedRecipesList.push({
                                            id: r.source_product_id,
                                            name: r.source_product_name,
                                            gramsPerPiece: parseFloat(r.grams_per_piece) || 0,
                                            unit: 'g',
                                            costPerUnit: parseFloat(r.cost_per_gram) || 0,
                                            costPerProductPiece: (parseFloat(r.cost_per_gram) || 0) * (parseFloat(r.grams_per_piece) || 0),
                                            totalCost: parseFloat(r.total_cost) || 0
                                        });
                                    });
                                }

                                updateIngredientsListDisplay();
                                updateCombinedRecipesListDisplay();
                                updateCostingDisplay();
                                updateUIBasedOnCategory();

                                currentAddStep = 1;
                                updateStepDisplay();

                                $('#addMaterialModal').removeClass('hidden');
                            } else {
                                Toast.error('Error loading product data: ' + (response.message || 'Unknown error'));
                            }
                        },
                        error: function (xhr, status, error) {
                            Toast.error('Error loading product: ' + error);
                        }
                    });
                }).fail(function () {
                    Toast.error('Error loading lookup data for editing product.');
                });
            }

            // When opening Edit from the View modal
            $('#btnViewEdit').off('click').on('click', function () {
                const productId = currentViewProductId;
                if (productId) {
                    closeViewModal();
                    openEditModal(productId);
                } else {
                    Toast.error('No product selected to edit.');
                }
            });

            // =====================================================
            // END EDIT PRODUCT MODAL FUNCTIONALITY (merged)
            // =====================================================

            // =====================================================
            // VIEW PRODUCT MODAL FUNCTIONALITY
            // =====================================================

            // Store current viewing product ID
            let currentViewProductId = null;

            // Open View Product Modal when clicking on a row (but not on action buttons)
            $(document).on('click', '.product-row', function (e) {

                // Don't open view modal if clicking on action buttons or their icons
                if ($(e.target).closest('.btn-edit, .btn-delete, button').length > 0) {
                    return;
                }

                const productId = $(this).data('product-id');

                if (productId) {
                    openViewModal(productId);
                } else {
                    Toast.error('Invalid product ID.');
                }
            });

            // Function to open view modal and load product data
            function openViewModal(productId) {
                currentViewProductId = productId;

                $.ajax({
                    url: baseUrl + 'Products/GetProduct/' + productId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success && response.data) {
                            const product = response.data;

                            // Set product name and category
                            $('#viewProductName').text(product.product_name);
                            let categoryBadge = '';
                            if (product.category === 'bakery') {
                                categoryBadge = '<i class="fas fa-bread-slice me-1"></i>Bakery';
                            } else if (product.category === 'dough') {
                                categoryBadge = '<i class="fas fa-cookie-bite me-1"></i>Dough';
                            } else if (product.category === 'drinks') {
                                categoryBadge = '<i class="fas fa-coffee me-1"></i>Drinks';
                            } else if (product.category === 'grocery') {
                                categoryBadge = '<i class="fas fa-shopping-basket me-1"></i>Grocery';
                            } else {
                                categoryBadge = '<i class="fas fa-box me-1"></i>' + (product.category || 'Unknown');
                            }
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
                                                <div class="text-xs text-gray-500">${ing.quantity} ${ing.unit} × ₱${costPerUnit.toFixed(5)}</div>
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
                                    const gramsPerPiece = parseFloat(recipe.grams_per_piece) || 0;
                                    combinedHtml += `
                                        <div class="flex justify-between items-center p-2 hover:bg-amber-100">
                                            <div>
                                                <span class="text-sm font-medium text-gray-800">${recipe.source_product_name}</span>
                                                <div class="text-xs text-gray-500">${gramsPerPiece}g/pc × ₱${costPerGram.toFixed(5)}/g</div>
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

                                // Set grid layout based on category AND whether Per Tray has data
                                // If no trays data, Per Piece should take full width regardless of category
                                if (traysPerYield > 0) {
                                    // Has tray data - use 2 columns
                                    $('#viewYieldGridContainer').addClass('sm:grid-cols-2').removeClass('sm:grid-cols-1');
                                    $('#viewPerPieceSection').removeClass('col-span-2');
                                } else {
                                    // No tray data - Per Piece takes full width (1 column)
                                    $('#viewYieldGridContainer').removeClass('sm:grid-cols-2').addClass('sm:grid-cols-1');
                                    $('#viewPerPieceSection').addClass('col-span-2');
                                }

                                // Display total yield
                                $('#viewYieldGrams').text(yieldGrams.toFixed(2) + ' g');

                                // Calculate unit price per gram
                                const unitPricePerGram = yieldGrams > 0 ? totalCost / yieldGrams : 0;
                                $('#viewUnitPricePerGram').text('₱ ' + unitPricePerGram.toFixed(5));

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
                                            const recipeGramsPerPiece = parseFloat(recipe.grams_per_piece) || 0;
                                            return sum + (costPerGram * recipeGramsPerPiece);
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
                                        let unitPricePerPiece = 0;

                                        // For dough category: use direct cost only (no overhead) divided by yield grams
                                        if (product.category === 'dough') {
                                            const directCost = parseFloat(product.direct_cost) || 0;
                                            const unitPricePerGramDough = yieldGrams > 0 ? directCost / yieldGrams : 0;
                                            unitPricePerPiece = gramsPerPiece * unitPricePerGramDough;
                                        } else {
                                            unitPricePerPiece = unitPricePerTray / piecesPerYield;
                                        }

                                        $('#viewGramsPerPiece').text(gramsPerPiece.toFixed(2) + ' g');
                                        $('#viewUnitPricePerPiece').text('₱ ' + unitPricePerPiece.toFixed(5));
                                    } else {
                                        $('#viewPiecesLabelText').text('Pieces/Slices/Plates');
                                        $('#viewPiecesPerYield').text(piecesPerYield);

                                        // Use database value if available, otherwise calculate
                                        const gramsPerPiece = parseFloat(product.grams_per_piece) || (yieldGrams / piecesPerYield);
                                        let unitPricePerPiece = 0;

                                        // For dough category: use direct cost only (no overhead) divided by yield grams
                                        if (product.category === 'dough') {
                                            const directCost = parseFloat(product.direct_cost) || 0;
                                            const unitPricePerGramDough = yieldGrams > 0 ? directCost / yieldGrams : 0;
                                            unitPricePerPiece = gramsPerPiece * unitPricePerGramDough;
                                        } else {
                                            unitPricePerPiece = totalCost / piecesPerYield;
                                        }

                                        $('#viewGramsPerPiece').text(gramsPerPiece.toFixed(2) + ' g');
                                        $('#viewUnitPricePerPiece').text('₱ ' + unitPricePerPiece.toFixed(5));
                                    }

                                    // Calculate additional price per piece if there are combined recipes
                                    if (product.combined_recipes && product.combined_recipes.length > 0) {
                                        const additionalPricePerPiece = product.combined_recipes.reduce((sum, recipe) => {
                                            const costPerGram = parseFloat(recipe.cost_per_gram) || 0;
                                            const recipeGramsPerPiece = parseFloat(recipe.grams_per_piece) || 0;
                                            return sum + (costPerGram * recipeGramsPerPiece);
                                        }, 0);

                                        $('#viewAdditionalPricePerPieceRow').removeClass('hidden');
                                        $('#viewAdditionalPricePerPiece').text('₱ ' + additionalPricePerPiece.toFixed(5));

                                        let unitPricePerPiece = 0;
                                        if (traysPerYield > 0) {
                                            const unitPricePerTray = totalCost / traysPerYield;
                                            unitPricePerPiece = unitPricePerTray / piecesPerYield;
                                        } else {
                                            unitPricePerPiece = totalCost / piecesPerYield;
                                        }

                                        const totalPricePerPiece = unitPricePerPiece + additionalPricePerPiece;
                                        $('#viewTotalPricePerPieceRow').removeClass('hidden');
                                        $('#viewTotalPricePerPiece').text('₱ ' + totalPricePerPiece.toFixed(5));
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
                const productId = currentViewProductId;
                if (productId) {
                    closeViewModal();
                    openEditModal(productId);
                } else {
                    Toast.error('No product selected to edit.');
                }
            });
        });
    </script>