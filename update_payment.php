<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];
    $sql = "SELECT * FROM payments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $payment = $result->fetch_assoc();

    if (!$payment) {
        echo "<script>alert('Payment not found!'); window.location.href='admin_payment_list.php';</script>";
        exit();
    }
} else {
    header('Location: admin_payment_list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_id = $_POST['id'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $method = $_POST['method'];
    $status = $_POST['status'];
    $expiry_date = $_POST['expiry_date'];

    $update_sql = "UPDATE payments SET type = ?, amount = ?, method = ?, status = ?, expiry_date = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sdsssi", $type, $amount, $method, $status, $expiry_date, $payment_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Payment updated successfully!'); window.location.href='admin_payment_list.php';</script>";
    } else {
        echo "<script>alert('Error updating payment.'); window.location.href='admin_payment_list.php';</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Payment - Gym Booking System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Update Payment</h2>
        <form action="update_payment.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($payment['id']); ?>">

            <label for="type">Type:</label>
            <input type="text" id="type" name="type" value="<?php echo htmlspecialchars($payment['type']); ?>" required>

            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" step="0.01" value="<?php echo htmlspecialchars($payment['amount']); ?>" required>

            <label for="method">Method:</label>
            <input type="text" id="method" name="method" value="<?php echo htmlspecialchars($payment['method']); ?>" required>

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="Pending" <?php if ($payment['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Completed" <?php if ($payment['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                <option value="Cancelled" <?php if ($payment['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
            </select>

            <label for="expiry_date">Expiry Date:</label>
            <input type="date" id="expiry_date" name="expiry_date" value="<?php echo htmlspecialchars($payment['expiry_date']); ?>" required>

            <button type="submit">Update Payment</button>
            <button type="button" onclick="window.location.href='admin_payment_list.php';">Cancel</button>
        </form>
    </div>
</body>
</html>
