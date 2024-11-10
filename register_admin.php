<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // storing plain text (not recommended for production)

    // Check if the admin user already exists
    $sql = "SELECT * FROM admin_users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        $sql = "INSERT INTO admin_users (username, password) VALUES ('$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            $adminFile = fopen("admin_registered.txt", "w");
            fwrite($adminFile, "true");
            fclose($adminFile);
            header("Location: login.php");
            exit();
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $error = "Admin username already exists";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Admin Register - Gym Booking System</title>
</head>
<body>
    <div class="container">
        <h2>Admin Register</h2>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form action="register_admin.php" method="post" class="form-container">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>
