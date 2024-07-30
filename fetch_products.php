<?php
include 'db.php';

$limit = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$price_min = isset($_GET['price_min']) ? (float)$_GET['price_min'] : 0;
$price_max = isset($_GET['price_max']) ? (float)$_GET['price_max'] : 1000000;
$category = isset($_GET['category']) && $_GET['category'] !== '' ? (int)$_GET['category'] : null;
$sale_status = isset($_GET['sale_status']) && $_GET['sale_status'] !== '' ? (int)$_GET['sale_status'] : null;

// Prepare the SQL query for counting total products
$sql = "SELECT COUNT(*) AS total FROM products WHERE price BETWEEN ? AND ?";
$params = [$price_min, $price_max];
$types = 'dd';

if ($category !== null) {
    $sql .= " AND category_id = ?";
    $params[] = $category;
    $types .= 'i';
}

if ($sale_status !== null) {
    $sql .= " AND sale_status = ?";
    $params[] = $sale_status;
    $types .= 'i';
}

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$totalProducts = mysqli_fetch_assoc($result)['total'];
$totalPages = ceil($totalProducts / $limit);
mysqli_stmt_close($stmt);

// Prepare the SQL query for fetching products
$sql = "SELECT * FROM products WHERE price BETWEEN ? AND ?";
if ($category !== null) {
    $sql .= " AND category_id = ?";
}
if ($sale_status !== null) {
    $sql .= " AND sale_status = ?";
}
$sql .= " LIMIT ?, ?";
$params[] = $start;
$params[] = $limit;
$types .= 'ii';

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

$response = [
    'products' => $products,
    'totalPages' => $totalPages
];

echo json_encode($response);

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
