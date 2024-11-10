<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $activity = $_POST['activity'];
    $schedule_date = $_POST['schedule_date'];
    $schedule_time = $_POST['schedule_time'];

    $sql = "INSERT INTO schedules (activity, schedule_date, schedule_time) VALUES ('$activity', '$schedule_date', '$schedule_time')";
    
    if ($conn->query($sql) === TRUE) {
        $success = "Schedule created successfully!";
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Schedule - Gym Booking System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'admin_menu.php'; ?>
    <div class="content">
        <h2>Create Schedule</h2>
        <?php if (isset($success)) echo "<p class='success-message'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form action="create_schedule.php" method="post" class="form-container">
            <label for="activity">Activity</label>
            <input type="text" id="activity" name="activity" required>
            
            <label for="schedule_date">Schedule Date</label>
            <input type="date" id="schedule_date" name="schedule_date" required>
            
            <label for="schedule_time">Schedule Time</label>
            <input type="time" id="schedule_time" name="schedule_time" required>
            
            <button type="submit">Create Schedule</button>
        </form>
    </div>
</body>
</html>
