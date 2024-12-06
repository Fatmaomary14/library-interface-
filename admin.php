<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit;
}

// Fetch all products
$products = pg_query($conn, "SELECT * FROM products");

// Fetch all orders
$orders = pg_query($conn, "SELECT o.order_id, o.status, o.date, o.order_total, c.name AS customer_name 
                           FROM orders o
                           JOIN customers c ON o.customer_id = c.customer_id");

// Check the action parameter to decide which section to show
$action = isset($_GET['action']) ? $_GET['action'] : 'add_product'; // Default action is 'add_product'
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Admin Panel</title>
    <style>
        nav ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
        }
        nav ul li {
            display: inline;
            margin: 0 20px;
        }
        nav ul li a {
            text-decoration: none;
            font-size: 18px;
            color: #333;
            padding: 10px;
            border: 2px solid #333;
            border-radius: 5px;
        }
        nav ul li a:hover {
            background-color: #333;
            color: white;
        }

        /* General Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #f9f9f9; /* Matching the form background */
        }
        table th, table td {
            padding: 12px 15px; /* Matching padding with form inputs */
            text-align: center;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #f4f4f4; /* Light background for headers */
            color: #333;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9; /* Alternating row colors */
        }
        table tr:hover {
            background-color: #e2e2e2; /* Slight highlight on row hover */
        }

        /* Form Styling */
        form {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        form input, form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        form button {
            background-color: light blue;
            color: white;
            border: none;
        }
        form button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <h1></h1>
    <nav>
        <ul>
            <li><a href="admin.php?action=available_product">Available products</a></li>
            <li><a href="admin.php?action=manage_orders">Manage Orders</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <?php if ($action == 'add_product'): ?>
        <!-- Add Product Form -->
        <h2>Add Products</h2>
        <form action="add_product.php" method="POST">
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="description">Description:</label>
            <input type="text" name="description" id="description" required>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" min="0" required>

            <label for="stock_level">Stock Level:</label>
            <input type="number" name="stock_level" id="stock_level" min="0" required>

            <label for="category">Category:</label>
            <input type="text" name="category" id="category" required>



            <input type="submit" name="submit" >Add Product</input>
        </form>
    <?php endif; ?>

    <?php if ($action == 'available_product'): ?>


        <h3>Existing Products</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price (TZS)</th>
                <th>Stock Level</th>
                <th>Category</th>
            </tr>
            <?php while ($row = pg_fetch_assoc($products)): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['stock_level']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>

    <?php if ($action == 'manage_orders'): ?>
        <!-- Manage Orders Table -->
        <h3>Manage Orders</h3>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Date</th>
                <th>Total (TZS)</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = pg_fetch_assoc($orders)): ?>
                <tr>
                    <td><?php echo $row['order_id']; ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['order_total']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <form action="update_order.php" method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                            <select name="status" required>
                                <option value="Pending">Pending</option>
                                <option value="Shipped">Shipped</option>
                                <option value="Delivered">Delivered</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</body>
</html>
