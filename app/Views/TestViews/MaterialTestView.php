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
    <form id="categoryForm">
        <label for="category_name">Category Name:</label>
        <input type="text" id="category_name" name="category_name" required><br><br>
        <label for="description">Description:</label>
        <input type="text" id="description" name="description" required><br><br>
        <button type="button" id="addCategoryButton">Add Category</button>
    </form>

    <script>
        $('#addCategoryButton').on('click', function (event) {
            const data = {
                category_name: $('#category_name').val(),
                description: $('#description').val()
            };

            $.ajax({
                url: '<?= base_url() ?>MaterialCategory/Add', // Fix: Wrap base_url() in quotes
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                dataType: 'json', // Add: Expect JSON response
                success: function (response) {
                    console.log(response.data);
                    $('#categoryForm')[0].reset(); // Add: Clear form after success
                },
                error: function (xhr) {
                    const res = xhr.responseJSON;
                    alert('Error: ' + (res?.message || xhr.statusText)); // Add: Fallback error message
                }
            });
        });
    </script>

</body>

</html>