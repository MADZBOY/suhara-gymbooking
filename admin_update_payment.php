<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM payments WHERE id = '$id'";
$result = $conn->query($sql);
$payment = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $method = $_POST['method'];
    $status = $_POST['status'];
    
    $sql = "UPDATE payments SET type='$type', amount='$amount', method='$method', status='$status' WHERE id='$id'";
    $conn->query($sql);
    header("Location: admin_payment_list.php");
}
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
    <?php include 'admin_menu.php'; ?>
    <div class="container">
        <h2>Update Payment</h2>
        <form action="admin_update_payment.php?id=<?php echo $payment['id']; ?>" method="post">
            <div class="form-container">
                <label for="type">Type</label>
                <select id="type" name="type" required>
                    <option value="monthly" <?php if ($payment['type'] == 'monthly') echo 'selected'; ?>>Monthly</option>
                    <option value="membership" <?php if ($payment['type'] == 'membership') echo 'selected'; ?>>Membership</option>
                    <option value="premium" <?php if ($payment['type'] == 'premium') echo 'selected'; ?>>Premium</option>
                </select>

                <label for="amount">Amount</label>
                <input type="number" step="0.01" id="amount" name="amount" value="<?php echo htmlspecialchars($payment['amount']); ?>" required>

                <label for="method">Method</label>
                <select id="method" name="method" required>
                    <option value="cash" <?php if ($payment['method'] == 'cash') echo 'selected'; ?>>Cash</option>
                    <option value="gcash" <?php if ($payment['method'] == 'gcash') echo 'selected'; ?>>GCash</option>
                </select>

                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="paid" <?php if ($payment['status'] == 'paid') echo 'selected'; ?>>Paid</option>
                    <option value="pending" <?php if ($payment['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                </select>

                <button type="submit" class="update">Update</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
