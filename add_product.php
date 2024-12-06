<?php
session_start();
require('./config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header('Location: admin.php');
    exit;
}


if (isset($_POST['submit'])) {
    // Collect form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock_level = intval($_POST['stock_level']);
    $category = trim($_POST['category']);

    // Validate
    if (empty($name) || empty($description) || empty($category) || $price <= 0 || $stock_level < 0) {
        echo "All fields are required, and values must be valid.";
        exit;
    }


    
    $query = "INSERT INTO products (name, description, price, stock_level, category) VALUES ($1, $2, $3, $4, $5)";
    $result = pg_query_params($conn, $query, [$name, $description, $price, $stock_level, $category]);

    
    if ($result) {
        echo "Product added successfully!";
        header('Location: admin.php?action=available_product');
        exit;
    } else {
        echo "Error: ";
    }
}
?>
