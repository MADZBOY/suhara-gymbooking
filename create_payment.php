<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $method = $_POST['method'];
    $status = 'Pending'; // Default status for new payments

    // Insert the new payment without user_id constraint
    $sql = "INSERT INTO payments (user_id, type, amount, method, status, created_at) 
            VALUES ('$user_id', '$type', '$amount', '$method', '$status', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Payment created successfully!'); window.location.href='user_payments.php';</script>";
    } else {
        echo "<script>alert('Error creating payment: " . $conn->error . "'); window.location.href='user_payments.php';</script>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Payment - Suhara Fitness Center</title>
    <link rel="stylesheet" href="css/style_new.css">
</head>
<body>
    <?php include 'user_menu.php'; ?>
    <div class="container">
        <h2>Create a Payment</h2>
        <form action="create_payment.php" method="POST">
            <label for="type">Payment Type:</label>
            <input type="text" id="type" name="type" required>

            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" step="0.01" required>

            <label for="method">Payment Method:</label>
            <input type="text" id="method" name="method" required>

            <button type="submit">Submit Payment</button>
        </form>
    </div>
</body>
</html>
