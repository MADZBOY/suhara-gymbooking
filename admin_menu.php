<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Menu</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <h3>Admin Menu</h3>
        <ul>
            <li><a href="admin_dashboard.php"><i class="fas fa-home"></i>Home</a></li>
            <li><a href="admin_user_list.php"><i class="fas fa-users"></i>User List</a></li>
            <li><a href="admin_payment_list.php"><i class="fas fa-money-bill-wave"></i>Payment List</a></li>
            <li><a href="create_announcement.php"><i class="fas fa-bullhorn"></i>Create Announcement</a></li>
            <li><a href="admin_announcements.php"><i class="fas fa-bullhorn"></i>View Announcements</a></li>
            <li><a href="admin_booking_list.php"><i class="fas fa-book"></i>Booking List</a></li>
            <li><a href="admin_coach_selection_list.php"><i class="fas fa-chalkboard-teacher"></i>Selected Coach/Instructor List</a></li>
            <li><a href="admin_workout_session_list.php"><i class="fas fa-dumbbell"></i>Workout Session List</a></li>
            <li><a href="login.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
        </ul>
    </div>
</body>
</html>
