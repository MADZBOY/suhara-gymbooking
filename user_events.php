<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$sql = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC";
$result = $conn->query($sql);
$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Gym Booking System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'user_menu.php'; ?>
    <div class="content">
        <h2>Events</h2>
        <ul class="events">
            <?php if (count($events) > 0): ?>
                <?php foreach ($events as $event): ?>
                    <li class="event">
                        <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p><?php echo htmlspecialchars($event['description']); ?></p>
                        <p><small>Event Date: <?php echo date('F j, Y', strtotime($event['event_date'])); ?></small></p>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="event">
                    <p>No upcoming events found.</p>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
<?php
$conn->close();
?>
