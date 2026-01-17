<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <h1>Daily Stocks</h1>

    <h2>Add Daily Stock</h2>
    <form id="dailyStockForm">
        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required><br><br>

        <label for="stock_date">Date:</label>
        <input type="date" id="stock_date" name="stock_date" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" step="any" id="quantity" name="quantity" required><br><br>

        <label for="unit">Unit:</label>
        <input type="text" id="unit" name="unit"><br><br>

        <label for="notes">Notes:</label>
        <input type="text" id="notes" name="notes"><br><br>

        <button type="button" id="addDailyStockButton">Add Stock</button>
    </form>

    <form action="">
        <label for="CheckInventory">Click button to check if inventory exists today</label>
        <button type="button" id="CheckInventory">Check Inventory</button>
    </form>

    <script>
        $('#addDailyStockButton').on('click', function () {
            const data = {
                material_id: $('#material_id').val(),
                stock_date: $('#stock_date').val(),
                quantity: $('#quantity').val(),
                unit: $('#unit').val(),
                notes: $('#notes').val()
            };

            $.ajax({
                url: '<?= base_url() ?>DailyStock/CreateParticular',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                dataType: 'json',
                success: function (res) {
                    alert('Daily stock added.');
                    $('#dailyStockForm')[0].reset();
                },
                error: function (xhr) {
                    const res = xhr.responseJSON;
                    alert('Error: ' + (res?.message || xhr.statusText));
                }
            });
        });

        $('#CheckInventory').on('click', function () {
            $.ajax({
                url: `<?= base_url() ?>DailyStock/CheckIfInventoryExists`,
                type: 'GET',
                contentType: 'application/json',
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                },
            })
        });
    </script>

</body>

</html>