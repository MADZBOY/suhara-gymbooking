<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id']; // Ensure this is set in your login logic
$exercise_ids = isset($_POST['exercise_ids']) ? explode(',', $_POST['exercise_ids']) : [];

// Validate the number of selected exercises
if (count($exercise_ids) < 1 || count($exercise_ids) > 3) {
    echo "<script>alert('Please select between 1 to 3 exercises.'); window.location.href='workout_sessions.php';</script>";
    exit();
}

try {
    // Begin a transaction to save exercises
    $conn->begin_transaction();

    foreach ($exercise_ids as $exercise_id) {
        $stmt = $conn->prepare("INSERT INTO user_schedule (user_id, exercise_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $exercise_id);

        if (!$stmt->execute()) {
            throw new Exception("Error saving exercises.");
        }
    }

    $conn->commit(); // Commit transaction
    echo "<script>alert('Exercises saved successfully!'); window.location.href='workout_sessions.php';</script>";
} catch (Exception $e) {
    $conn->rollback(); // Rollback transaction on error
    echo "<script>alert('Error saving exercises. Please try again.'); window.location.href='workout_sessions.php';</script>";
}

// Close the prepared statement and the database connection
$stmt->close();
$conn->close();
?>
