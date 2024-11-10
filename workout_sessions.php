<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$success_message = $error_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $exercise1 = $_POST['exercise1'];
    $exercise2 = $_POST['exercise2'];
    $exercise3 = $_POST['exercise3'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Insert the workout session
    $sql = "INSERT INTO workout_sessions (user_id, exercise1, exercise2, exercise3, date, time) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $user_id, $exercise1, $exercise2, $exercise3, $date, $time);
    
    if ($stmt->execute()) {
        $success_message = "Workout session saved successfully!";
    } else {
        $error_message = "Failed to save the workout session. Please try again.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Workout Session</title>
    <link rel="stylesheet" href="css/style_new.css">
</head>
<body>
    <?php include 'user_menu.php'; ?>
    <div class="content">
        <h2>Create Workout Session</h2>

        <?php if ($success_message) echo "<p class='success-message'>$success_message</p>"; ?>
        <?php if ($error_message) echo "<p class='error-message'>$error_message</p>"; ?>

        <form action="workout_sessions.php" method="post" class="workout-form">
            <label for="exercise1">Exercise 1:</label>
            <select name="exercise1" id="exercise1" required>
                <option value="Abs">Abs</option>
                <option value="Chest">Chest</option>
                <option value="Back">Back</option>
                <option value="Legs">Legs</option>
                <option value="Biceps">Biceps</option>
                <option value="Shoulders">Shoulders</option>
            </select>

            <label for="exercise2">Exercise 2:</label>
            <select name="exercise2" id="exercise2" required>
                <option value="Abs">Abs</option>
                <option value="Chest">Chest</option>
                <option value="Back">Back</option>
                <option value="Legs">Legs</option>
                <option value="Biceps">Biceps</option>
                <option value="Shoulders">Shoulders</option>
            </select>

            <label for="exercise3">Exercise 3:</label>
            <select name="exercise3" id="exercise3" required>
                <option value="Abs">Abs</option>
                <option value="Chest">Chest</option>
                <option value="Back">Back</option>
                <option value="Legs">Legs</option>
                <option value="Biceps">Biceps</option>
                <option value="Shoulders">Shoulders</option>
            </select>

            <label for="date">Date:</label>
            <input type="date" name="date" id="date" required>

            <label for="time">Time:</label>
            <input type="time" name="time" id="time" required>

            <button type="submit">Save Workout Session</button>
        </form>
    </div>
</body>
</html>
