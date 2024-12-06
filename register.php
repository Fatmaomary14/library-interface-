        <?php
        // Database connection
        $conn = pg_connect("host=localhost dbname=retail user=postgres password=msfety1234");

        if (!$conn) {
            die("Connection failed: " . pg_last_error());
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = pg_escape_string($conn, $_POST['username']);
            $email = pg_escape_string($conn, $_POST['email']);
            $password = pg_escape_string($conn, $_POST['password']);

            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            $query = "INSERT INTO users (username, email, password_hash) VALUES ('$username', '$email', '$password_hash')";

            if (pg_query($conn, $query)) {
                echo "<div class='success'>Registration successful! You can now <a href='login.php'>login</a>.</div>";
            } else {
                echo "<div class='error'>Error: " . pg_last_error($conn) . "</div>";
            }
        }

        pg_close($conn);
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Register</title>
            <link rel="stylesheet" href="./css/style.css">
        </head>
        <body>

            <div class="form-container">
                <h1>Register</h1>


                <form action="register.php" method="POST">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                    <br>

                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                    <br>

                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                    <br>

                    <input type="submit" value="Register">

                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </form>

             
            </div>
        </body>
        </html>

