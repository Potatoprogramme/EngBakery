<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <h1>Material Test</h1>

    <!-- Add Category Form -->
    <h2>Add Category</h2>
    <form id="categoryForm">
        <label for="category_name">Category Name:</label>
        <input type="text" id="category_name" name="category_name" required><br><br>
        <label for="description">Description:</label>
        <input type="text" id="description" name="description" required><br><br>
        <button type="button" id="addCategoryButton">Add Category</button>
    </form>

    <hr>

    <!-- Delete Category Form -->
    <h2>Delete Category</h2>
    <form id="deleteCategoryForm">
        <label for="category_id">Category ID:</label>
        <input type="number" id="category_id" name="category_id" required><br><br>
        <button type="button" id="deleteCategoryButton">Delete Category</button>
    </form>

    <hr>

    <!-- Update Category Form -->
    <h2>Update Category</h2>
    <form id="updateCategoryForm">
        <label for="update_category_id">Category ID:</label>
        <input type="number" id="update_category_id" name="category_id" required><br><br>
        <label for="update_category_name">Category Name:</label>
        <input type="text" id="update_category_name" name="category_name" required><br><br>
        <label for="update_description">Description:</label>
        <input type="text" id="update_description" name="description" required><br><br>
        <button type="button" id="updateCategoryButton">Update Category</button>
    </form>

    <hr>

    <!-- All Categories Table -->
    <h2>All Categories</h2>
    <table border="1" cellpadding="6" cellspacing="0" id="categoriesTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="categoriesBody">
            <!-- rows inserted here -->
        </tbody>
    </table>

    <script>
        // Add Category
        $('#addCategoryButton').on('click', function (event) {
            const data = {
                category_name: $('#category_name').val(),
                description: $('#description').val()
            };

            $.ajax({
                url: '<?= base_url() ?>MaterialCategory/Add',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                dataType: 'json',
                success: function (response) {
                    console.log('Add response:', response);
                    alert('Category added successfully!');
                    $('#categoryForm')[0].reset();
                    loadCategories();
                },
                error: function (xhr) {
                    const res = xhr.responseJSON;
                    alert('Error: ' + (res?.message || xhr.statusText));
                }
            });
        });

        // Delete Category (form)
        $('#deleteCategoryButton').on('click', function (event) {
            const categoryId = $('#category_id').val();

            if (!confirm('Are you sure you want to delete this category?')) {
                return;
            }

            const data = {
                category_id: categoryId
            };

            $.ajax({
                url: '<?= base_url() ?>MaterialCategory/Delete',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                dataType: 'json',
                success: function (response) {
                    console.log('Delete response:', response);
                    alert('Category deleted successfully!');
                    $('#deleteCategoryForm')[0].reset();
                    loadCategories();
                },
                error: function (xhr) {
                    const res = xhr.responseJSON;
                    alert('Error: ' + (res?.message || xhr.statusText));
                }
            });
        });

        // Update Category
        $('#updateCategoryButton').on('click', function (event) {
            const data = {
                category_id: $('#update_category_id').val(),
                category_name: $('#update_category_name').val(),
                description: $('#update_description').val()
            };

            if (!data.category_id) {
                alert('Please provide a Category ID to update.');
                return;
            }

            $.ajax({
                url: '<?= base_url() ?>MaterialCategory/Update',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                dataType: 'json',
                success: function (response) {
                    console.log('Update response:', response);
                    alert('Category updated successfully!');
                    $('#updateCategoryForm')[0].reset();
                    loadCategories();
                },
                error: function (xhr) {
                    const res = xhr.responseJSON;
                    alert('Error: ' + (res?.message || xhr.statusText));
                }
            });
        });

        // Load and render categories
        function loadCategories() {
            $.ajax({
                url: '<?= base_url() ?>MaterialCategory/FetchAll',
                type: 'GET',
                dataType: 'json',
                success: function (response) {

                    console.log('Fetched categories:', response);
                    const tbody = $('#categoriesBody');
                    tbody.empty();
                    if (!Array.isArray(response.data)) return;
                    response.data.forEach(function (cat) {
                        const id = cat.category_id ?? cat.id ?? '';
                        const name = cat.category_name ?? cat.name ?? '';
                        const desc = cat.description ?? '';
                        const row = $('<tr>').attr('data-id', id)
                            .append($('<td>').text(id))
                            .append($('<td>').text(name))
                            .append($('<td>').text(desc))
                            .append($('<td>')
                                .append($('<button>').addClass('editBtn').text('Edit'))
                                .append(' ')
                                .append($('<button>').addClass('delBtn').text('Delete'))
                            );
                        tbody.append(row);
                    });
                },
                error: function (xhr) {
                    console.error('Failed to load categories', xhr);
                }
            });
        }

        // Delegate edit button: populate update form
        $(document).on('click', '.editBtn', function () {
            const tr = $(this).closest('tr');
            const id = tr.data('id');
            const name = tr.find('td').eq(1).text();
            const desc = tr.find('td').eq(2).text();
            $('#update_category_id').val(id);
            $('#update_category_name').val(name);
            $('#update_description').val(desc);
            window.scrollTo(0, document.body.scrollHeight);
        });

        // Delegate delete button in table
        $(document).on('click', '.delBtn', function () {
            const tr = $(this).closest('tr');
            const id = tr.data('id');

            if (!confirm('Are you sure you want to delete this category?')) return;

            $.ajax({
                url: '<?= base_url() ?>MaterialCategory/Delete',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ category_id: id }),
                dataType: 'json',
                success: function (res) {
                    alert('Category deleted successfully!');
                    loadCategories();
                },
                error: function (xhr) {
                    const res = xhr.responseJSON;
                    alert('Error: ' + (res?.message || xhr.statusText));
                }
            });
        });

        // Initial load
        $(document).ready(function () {
            loadCategories();
        });
    </script>

</body>

</html>