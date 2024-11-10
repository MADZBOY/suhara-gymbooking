<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Get the current user's ID
$user_id = $_SESSION['user_id']; // Make sure this is set in your login/session logic

// Retrieve the form data
$exercise_ids = isset($_POST['exercise_ids']) ? explode(',', $_POST['exercise_ids']) : [];
$date = $_POST['date'];
$time = $_POST['time'];

// Check if the user selected 1 to 3 exercises
if (count($exercise_ids) < 1 || count($exercise_ids) > 3) {
    echo "<script>alert('Please select between 1 to 3 exercises.'); window.location.href='workout_sessions.php';</script>";
    exit();
}

try {
    // Insert each selected exercise as a separate entry in the user_schedule table
    $conn->begin_transaction(); // Begin transaction

    foreach ($exercise_ids as $exercise_id) {
        $stmt = $conn->prepare("INSERT INTO user_schedule (user_id, exercise_id, scheduled_date, scheduled_time) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $user_id, $exercise_id, $date, $time);

        if (!$stmt->execute()) {
            throw new Exception("Error scheduling workout.");
        }
    }

    $conn->commit(); // Commit transaction
    echo "<script>alert('Workout scheduled successfully!'); window.location.href='workout_sessions.php';</script>";
} catch (Exception $e) {
    $conn->rollback(); // Rollback transaction on error
    echo "<script>alert('Error scheduling workout. Please try again.'); window.location.href='workout_sessions.php';</script>";
}

// Close the prepared statement and the database connection
$stmt->close();
$conn->close();
?>
