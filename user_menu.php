<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Menu</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <h3>User Menu</h3>
        <div class="user-name">Welcome, <?php echo htmlspecialchars($username); ?></div>
        <ul>
            <li><a href="user_dashboard.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="user_announcements.php"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="create_payment.php"><i class="fas fa-money-bill-wave"></i> Payment</a></li>
            <li><a href="user_payments.php"><i class="fas fa-money-bill-wave"></i> My Payments</a></li>
            <li><a href="create_booking.php"><i class="fas fa-book"></i> Book a Session</a></li>
            <li><a href="user_bookings.php"><i class="fas fa-book"></i> My Bookings</a></li>
            <li><a href="workout_sessions.php"><i class="fas fa-dumbbell"></i> Workout Sessions</a></li>
            <li><a href="select_coach_instructor.php"><i class="fas fa-user-tie"></i> Select Coach/Instructor</a></li> <!-- Renamed Menu Item -->
            <li><a href="about_us.php"><i class="fas fa-info-circle"></i> About Us</a></li>
            <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
</body>
</html>
