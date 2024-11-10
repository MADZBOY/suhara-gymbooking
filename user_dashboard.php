<?php
session_start();
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
    <title>User Dashboard - Suhara Fitness Center</title>
    <link rel="stylesheet" href="css/style_new.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'user_menu.php'; ?>
    
    <div class="main-content">
        <div class="container">
            <h2>Welcome to Suhara Fitness Center, <?php echo htmlspecialchars($username); ?>!</h2>
            <div id="datetime" class="datetime"></div>
            
            <div class="dashboard-cards">
                <!-- Announcements Card -->
                <div class="card" onclick="window.location.href='user_announcements.php'">
                    <i class="fas fa-bullhorn"></i>
                    <div class="card-info">
                        <h3>Announcements</h3>
                    </div>
                </div>

                <!-- Payment Cards -->
                <div class="card" onclick="window.location.href='create_payment.php'">
                    <i class="fas fa-money-bill-wave"></i>
                    <div class="card-info">
                        <h3>Payment</h3>
                    </div>
                </div>
                <div class="card" onclick="window.location.href='user_payment.php'">
                    <i class="fas fa-wallet"></i>
                    <div class="card-info">
                        <h3>My Payments</h3>
                    </div>
                </div>

                <!-- Booking Cards -->
                <div class="card" onclick="window.location.href='create_booking.php'">
                    <i class="fas fa-calendar-plus"></i>
                    <div class="card-info">
                        <h3>Book a Session</h3>
                    </div>
                </div>
                <div class="card" onclick="window.location.href='user_bookings.php'">
                    <i class="fas fa-calendar-check"></i>
                    <div class="card-info">
                        <h3>My Bookings</h3>
                    </div>
                </div>

                <!-- Workout Sessions and Coach Selection Cards -->
                <div class="card" onclick="window.location.href='workout_sessions.php'">
                    <i class="fas fa-dumbbell"></i>
                    <div class="card-info">
                        <h3>Workout Sessions</h3>
                    </div>
                </div>
                <div class="card" onclick="window.location.href='select_coach_instructor.php'">
                    <i class="fas fa-user-tie"></i>
                    <div class="card-info">
                        <h3>Select Coach/Instructor</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const date = now.toLocaleDateString(undefined, options);
            const time = now.toLocaleTimeString();
            document.getElementById('datetime').innerHTML = `${date} - ${time}`;
        }

        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
</body>
</html>
