<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

try {
    // Count users
    $sql = "SELECT COUNT(*) as count FROM users";
    $result = $conn->query($sql);
    $user_count = $result->fetch_assoc()['count'];

    // Count payments
    $sql = "SELECT COUNT(*) as count FROM payments";
    $result = $conn->query($sql);
    $payment_count = $result->fetch_assoc()['count'];

    // Count bookings
    $sql = "SELECT COUNT(*) as count FROM bookings";
    $result = $conn->query($sql);
    $booking_count = $result->fetch_assoc()['count'];

    // Count selected coaches/instructors
    $sql = "SELECT COUNT(*) as count FROM coach_selections"; // Change if table name differs
    $result = $conn->query($sql);
    $selected_coach_count = $result->fetch_assoc()['count'];

    // Count workout sessions
    $sql = "SELECT COUNT(*) as count FROM workout_sessions"; // Change if table name differs
    $result = $conn->query($sql);
    $workout_session_count = $result->fetch_assoc()['count'];
} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Gym Booking System</title>
    <link rel="stylesheet" href="css/style_new.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'admin_menu.php'; ?>
    
    <div class="content">
        <h2 style="color: #76ff03;">Admin Dashboard</h2>
        
        <div class="dashboard-cards">
            <!-- Total Users Card -->
            <a href="admin_user_list.php" class="card">
                <i class="fas fa-users"></i>
                <div class="card-info">
                    <h3 style="color: #76ff03;"><?php echo $user_count; ?></h3>
                    <p style="color: #76ff03;">Total Users</p>
                </div>
            </a>

            <!-- Total Payments Card -->
            <a href="admin_payment_list.php" class="card">
                <i class="fas fa-money-bill-wave"></i>
                <div class="card-info">
                    <h3 style="color: #76ff03;"><?php echo $payment_count; ?></h3>
                    <p style="color: #76ff03;">Total Payments</p>
                </div>
            </a>

            <!-- Total Bookings Card -->
            <a href="admin_booking_list.php" class="card">
                <i class="fas fa-book"></i>
                <div class="card-info">
                    <h3 style="color: #76ff03;"><?php echo $booking_count; ?></h3>
                    <p style="color: #76ff03;">Total Bookings</p>
                </div>
            </a>

            <!-- Selected Coach/Instructor List Card -->
            <a href="admin_coach_list.php" class="card">
                <i class="fas fa-user-tie"></i>
                <div class="card-info">
                    <h3 style="color: #76ff03;"><?php echo $selected_coach_count; ?></h3>
                    <p style="color: #76ff03;">Selected Coaches/Instructors</p>
                </div>
            </a>

            <!-- Workout Session List Card -->
            <a href="admin_workout_session_list.php" class="card">
                <i class="fas fa-dumbbell"></i>
                <div class="card-info">
                    <h3 style="color: #76ff03;"><?php echo $workout_session_count; ?></h3>
                    <p style="color: #76ff03;">Workout Sessions</p>
                </div>
            </a>
        </div>
    </div>
</body>
</html>
