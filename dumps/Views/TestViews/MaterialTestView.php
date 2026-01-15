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
                },
                error: function (xhr) {
                    const res = xhr.responseJSON;
                    alert('Error: ' + (res?.message || xhr.statusText));
                }
            });
        });

        // Delete Category
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
                },
                error: function (xhr) {
                    const res = xhr.responseJSON;
                    alert('Error: ' + (res?.message || xhr.statusText));
                }
            });
        });
    </script>

</body>

</html>