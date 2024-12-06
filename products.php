<?php
include 'config.php';

$query = "SELECT * FROM products";
$result = pg_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Products</title>
</head>
<body>
    <h1>Available Products</h1>
    <nav>
        <a href="index.php">Home</a> | 
        <a href="cart.php">Cart</a>
    </nav>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock Level</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
        <?php while ($row = pg_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['stock_level']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td>
                <form action="cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                    <input type="number" name="quantity" value="1" min="1" max="<?php echo $row['stock_level']; ?>" required>
                    <button type="submit">Add to Cart</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
