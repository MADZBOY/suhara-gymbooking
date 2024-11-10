<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$sql = "SELECT * FROM schedules ORDER BY schedule_date ASC, schedule_time ASC";
$result = $conn->query($sql);
$schedules = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedules - Gym Booking System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'user_menu.php'; ?>
    <div class="content">
        <h2>Schedules</h2>
        <ul class="schedules">
            <?php if (count($schedules) > 0): ?>
                <?php foreach ($schedules as $schedule): ?>
                    <li class="schedule">
                        <h3><?php echo htmlspecialchars($schedule['activity']); ?></h3>
                        <p><small>Scheduled Date: <?php echo date('F j, Y', strtotime($schedule['schedule_date'])); ?></small></p>
                        <p><small>Scheduled Time: <?php echo date('g:i A', strtotime($schedule['schedule_time'])); ?></small></p>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="schedule">
                    <p>No schedules found.</p>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
<?php
$conn->close();
?>
