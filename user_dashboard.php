<?php
include 'config.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Retrieve the username from the session
$username = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';

// Fetch purchased products for the logged-in user, including the order status
$user_id = $_SESSION['user_id'];
$queryPurchased = "SELECT o.order_id, p.name AS product_name, oi.quantity, oi.unit_price, o.status 
FROM orders o
JOIN orderitems oi ON o.order_id = oi.order_id
JOIN products p ON oi.product_id = p.product_id
WHERE o.customer_id = $1";

$resultPurchased = pg_query_params($conn, $queryPurchased, [$user_id]);

if (!$resultPurchased) {
    die("Error fetching purchased products: " . pg_last_error($conn));
}

$row_count = pg_num_rows($resultPurchased);

// Fetch all products for adding to cart
$queryAllProducts = "SELECT * FROM products";
$resultAllProducts = pg_query($conn, $queryAllProducts);

if (!$resultAllProducts) {
    die("Error fetching all products: " . pg_last_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
    <nav>
        <a href="cart.php">Cart</a> | 
        <a href="logout.php">Logout</a>
    </nav>

    <h2>Your Purchased Products</h2>
    <?php if ($row_count > 0): ?>
        <table border="1">
            <tr>
                <th>Order ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price (TZS)</th>
                <th>Status</th> <!-- Added status column -->
            </tr>
            <?php while ($row = pg_fetch_assoc($resultPurchased)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($row['unit_price']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td> <!-- Display the status -->
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No purchased products found.</p>
    <?php endif; ?>

    <h2>Add Products to Cart</h2>
    <form action="cart.php" method="POST">
        <label for="product_id">Select Product:</label>
        <select name="product_id" id="product_id" required>
            <?php while ($product = pg_fetch_assoc($resultAllProducts)): ?>
                <option value="<?php echo htmlspecialchars($product['product_id']); ?>">
                    <?php echo htmlspecialchars($product['name']); ?> - <?php echo htmlspecialchars($product['price']); ?> TZS
                </option>
            <?php endwhile; ?>
        </select>
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" value="1" min="1" required>
        <button type="submit">Add to Cart</button>
    </form>
</body>
</html>

<?php
pg_close($conn);
?>
