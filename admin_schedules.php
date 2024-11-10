<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    if (isset($_POST['delete'])) {
        $sql = "DELETE FROM schedules WHERE id = '$id'";
        $conn->query($sql);
        header("Location: admin_schedules.php");
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
    <?php include 'admin_menu.php'; ?>
    <div class="content">
        <h2>Schedules</h2>
        <ul class="schedules">
            <?php if (count($schedules) > 0): ?>
                <?php foreach ($schedules as $schedule): ?>
                    <li class="schedule">
                        <h3><?php echo htmlspecialchars($schedule['activity']); ?></h3>
                        <p><small>Scheduled Date: <?php echo date('F j, Y', strtotime($schedule['schedule_date'])); ?></small></p>
                        <p><small>Scheduled Time: <?php echo date('g:i A', strtotime($schedule['schedule_time'])); ?></small></p>
                        <form action="admin_schedules.php" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $schedule['id']; ?>">
                            <button type="submit" name="delete" class="delete" onclick="return confirm('Are you sure you want to delete this schedule?');">Delete</button>
                        </form>
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
