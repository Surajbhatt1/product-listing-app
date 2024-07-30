<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .product {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px 0;
        }
        #pagination {
            margin-top: 20px;
        }
        .page-link {
            margin: 0 5px;
            cursor: pointer;
        }
        .active {
            font-weight: bold;
            text-decoration: underline;
        }
        #loading {
            display: none;
            text-align: center;
        }
    </style>
</head>
<body>
    <div>
        <h1>Product Listing</h1>
        <form id="filterForm">
            <label for="price_min">Min Price:</label>
            <input type="number" id="price_min" name="price_min" step="0.01">
            <label for="price_max">Max Price:</label>
            <input type="number" id="price_max" name="price_max" step="0.01">
            <label for="category">Category:</label>
            <select id="category" name="category">
                <option value="">All</option>
                <!-- Populate categories with PHP -->
                <?php
                include 'db.php';
                $sql = "SELECT * FROM categories";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    }
                } else {
                    echo "<option value=''>No categories available</option>";
                }
                ?>
            </select>
            <label for="sale_status">Sale Status:</label>
            <select id="sale_status" name="sale_status">
                <option value="">All</option>
                <option value="1">On Sale</option>
                <option value="0">Not On Sale</option>
            </select>
            <button type="submit">Apply Filters</button>
        </form>
    </div>
    <div id="productList"></div>
    <div id="pagination"></div>
    <div id="loading">Loading...</div>
    <script>
        document.getElementById('filterForm').addEventListener('submit', function (e) {
            e.preventDefault();
            loadProducts();
        });

        function loadProducts(page = 1) {
            const formData = new FormData(document.getElementById('filterForm'));
            formData.append('page', page);
            const queryParams = new URLSearchParams(formData).toString();

            document.getElementById('loading').style.display = 'block';

            fetch(`fetch_products.php?${queryParams}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loading').style.display = 'none';
                    const productList = document.getElementById('productList');
                    productList.innerHTML = '';

                    if (data.products.length > 0) {
                        data.products.forEach(product => {
                            productList.innerHTML += `<div class="product">${product.name} - ₹${product.price}</div>`;
                        });
                    } else {
                        productList.innerHTML = '<p>No products found.</p>';
                    }

                    const pagination = document.getElementById('pagination');
                    pagination.innerHTML = '';

                    if (data.totalPages > 1) {
                        for (let i = 1; i <= data.totalPages; i++) {
                            pagination.innerHTML += `<span class="page-link${i === page ? ' active' : ''}" onclick="loadProducts(${i})">${i}</span>`;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                    document.getElementById('loading').style.display = 'none';
                });
        }

        loadProducts();
    </script>
</body>
</html>
