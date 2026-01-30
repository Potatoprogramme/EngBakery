<!-- Manage Categories Modal -->
<div id="manageCategoriesModal"
    class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 sm:p-0">
    <div class="relative w-full max-w-md mx-auto p-4 border shadow-lg rounded-md bg-white max-h-[90vh] flex flex-col"
        style="max-width: 42rem;">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-tags me-2"></i>Manage Categories</h3>
            <button type="button" id="btnCloseCategoryModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto pr-1">
            <!-- Add/Edit Category Form -->
            <form id="categoryForm" class="pb-4">
                <input type="hidden" id="edit_category_id" value="">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="category_name" id="category_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="e.g., Flour" required>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="category_description" id="category_description" rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="Optional description"></textarea>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Label <span
                            class="text-red-500">*</span></label>
                    <select name="category_label" id="category_label"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                        required>
                        <option value="">Select Label</option>
                        <option value="general">General (All Products)</option>
                        <option value="bread">Bakery Only</option>
                        <option value="drinks">Drinks Only</option>
                        <option value="grocery">Grocery Only</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Used to filter materials per product type</p>
                </div>
                <div class="flex gap-2 justify-end mb-4">
                    <button type="button" id="btnCancelCategory"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                    <button type="submit" id="btnSaveCategory"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-secondary">Save</button>
                </div>
            </form>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-4"></div>

            <!-- Categories List -->
            <div class="pb-2">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Categories</h4>
                <div id="categoriesList" class="space-y-2">
                    <!-- Categories will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
