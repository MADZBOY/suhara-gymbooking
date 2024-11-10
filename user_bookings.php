<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM bookings WHERE user_id = '$user_id' ORDER BY session_date ASC, session_time ASC";
$result = $conn->query($sql);
$bookings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Gym Booking System</title>
    <link rel="stylesheet" href="css/style_new.css">
</head>
<body>
    <?php include 'user_menu.php'; ?>
    <div class="content">
        <h2>My Bookings</h2>
        <ul class="bookings">
            <?php if (count($bookings) > 0): ?>
                <?php foreach ($bookings as $booking): ?>
                    <li class="booking">
                        <h3>Booking Date: <?php echo date('F j, Y', strtotime($booking['session_date'])); ?></h3>
                        <p>Booking Time: <?php echo date('g:i A', strtotime($booking['session_time'])); ?></p>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="booking">
                    <p>No bookings found.</p>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
<?php
$conn->close();
?>
