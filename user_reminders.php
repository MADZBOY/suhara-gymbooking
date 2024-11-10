<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$username = $_SESSION['username'];

$sql = "SELECT type, expiry_date FROM payments WHERE username = '$username' AND expiry_date > NOW() ORDER BY expiry_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Reminders - Suhara Fitness Center</title>
</head>
<body>
    <?php include 'user_menu.php'; ?>
    <div class="container">
        <h2>Payment Reminders</h2>
        <ul class="reminders">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <li class="reminder">
                        <h3><?php echo ucfirst($row['type']); ?> Payment</h3>
                        <p>Due Date: <?php echo date('F j, Y', strtotime($row['expiry_date'])); ?></p>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li class="reminder">
                    <h3>No upcoming payments</h3>
                    <p>You have no upcoming payments.</p>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
<?php $conn->close(); ?>
