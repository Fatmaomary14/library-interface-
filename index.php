<?php
$conn = pg_connect("host=localhost dbname=retail user=postgres password=msfety1234");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

$query = "SELECT * FROM products";
$result = pg_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retail Store</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>Welcome to Our Retail Store</h1>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="login.php">Login</a>
    <a href="cart.php">View Cart</a>
    <a href="register.php">Register</a>
</nav>

<main>
    <h2>Our Products</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Category</th>
            <th>Action</th>
        </tr>

        <?php while ($row = pg_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td><a href="cart.php?add_to_cart=<?php echo $row['product_id']; ?>">Add to Cart</a></td>
        </tr>
        <?php } ?>
    </table>
</main>

</body>
</html>

<?php pg_close($conn); ?>
