<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM payments WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = $conn->query($sql);
$payments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
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
    <title>My Payments - Suhara Fitness Center</title>
</head>
<body>
    <?php include 'user_menu.php'; ?>
    <div class="content">
        <h2>My Payments</h2>
        <?php if (count($payments) > 0): ?>
            <div class="payment-cards">
                <?php foreach ($payments as $payment): ?>
                    <div class="payment-card">
                        <h3>Payment ID: <?php echo htmlspecialchars($payment['id']); ?></h3>
                        <p><strong>Type:</strong> <?php echo htmlspecialchars($payment['type']); ?></p>
                        <p><strong>Amount:</strong> $<?php echo htmlspecialchars($payment['amount']); ?></p>
                        <p><strong>Method:</strong> <?php echo htmlspecialchars($payment['method']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($payment['status']); ?></p>
                        <p><strong>Created At:</strong> <?php echo htmlspecialchars($payment['created_at']); ?></p>
                        <p><strong>Expiry Date:</strong> <?php echo htmlspecialchars($payment['expiry_date']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-payments">No payments found</p>
        <?php endif; ?>
    </div>
</body>
</html>
