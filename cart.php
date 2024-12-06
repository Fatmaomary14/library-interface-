<?php
// Database connection
$conn = pg_connect("host=localhost dbname=retail user=postgres password=msfety1234");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Assuming $user_id is the logged-in user's ID (you would get this from the session or another source)
$user_id = 1;  // Replace with actual user ID

// Query to fetch cart items with required details
$query = "
    SELECT 
        c.cart_id, 
        c.user_id, 
        p.name AS product_name, 
        c.quantity, 
        p.price, 
        (c.quantity * p.price) AS total 
    FROM cart c
    JOIN products p ON c.product_id = p.product_id
    WHERE c.user_id = $user_id";

$result = pg_query($conn, $query);

if (!$result) {
    die("Error executing query: " . pg_last_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Shopping Cart</h1>

    <table>
        <thead>
            <tr>
                <th>Cart ID</th>
                <th>User ID</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if any rows are returned
            if (pg_num_rows($result) > 0) {
                // Loop through the result set and display data
                while ($row = pg_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['cart_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($row['price'], 2)) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($row['total'], 2)) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Your cart is empty.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php pg_close($conn); ?>
</body>
</html>
