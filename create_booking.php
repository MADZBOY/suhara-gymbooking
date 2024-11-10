<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $session_date = $_POST['session_date'];
    $session_time = $_POST['session_time'];

    $sql = "INSERT INTO bookings (user_id, session_date, session_time) VALUES ('$user_id', '$session_date', '$session_time')";
    
    if ($conn->query($sql) === TRUE) {
        $success = "Booking successful!";
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style_new.css">
    <title>Book a Session - Gym Booking System</title>
</head>
<body>
    <?php include 'user_menu.php'; ?>
    <div class="container">
        <h2>Book a Session</h2>
        <?php if (isset($success)) echo "<p class='success-message'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form action="create_booking.php" method="post" class="form-container">
            <label for="session_date">Session Date:</label>
            <input type="date" id="session_date" name="session_date" required>
            
            <label for="session_time">Session Time:</label>
            <input type="time" id="session_time" name="session_time" required>
            
            <button type="submit">Book</button>
        </form>
    </div>
</body>
</html>
