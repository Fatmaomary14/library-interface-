<?php
// Database connection
$conn = pg_connect("host=localhost dbname=retail user=postgres password=msfety1234");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = pg_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = pg_query($conn, $query);

    if ($row = pg_fetch_assoc($result)) {
        if (password_verify($password, $row['password_hash'])) {
            session_start();

            // Set session variables
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['name'] = $row['username']; // Store the username in session

            if ($row['role'] === 'admin') {
                header("Location: admin.php");
            } else if ($row['role'] === 'customer') {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            echo "<div class='error'>Invalid password.</div>";
        }
    } else {
        echo "<div class='error'>User not found.</div>";
    }
}

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>
        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <br>

            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
