<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';
include 'user_menu.php'; // Include the user menu

// Fetch available coaches/instructors from the `trainers` table
$sql = "SELECT id, name FROM trainers ORDER BY name";
$result = $conn->query($sql);
$coaches = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $coaches[] = $row;
    }
}

// Handle selection submission
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_coach_id = $_POST['coach_id'];

    // Check if the user has already selected this coach
    $check_sql = "SELECT * FROM user_trainer_selections WHERE user_id = ? AND trainer_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $user_id, $selected_coach_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Insert the new selection
        $insert_sql = "INSERT INTO user_trainer_selections (user_id, trainer_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ii", $user_id, $selected_coach_id);
        
        if ($stmt->execute()) {
            $success_message = "Coach/Instructor selected successfully!";
        } else {
            $error_message = "Failed to select the coach/instructor. Please try again.";
        }
    } else {
        $error_message = "You have already selected this coach/instructor.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Coach/Instructor</title>
    <link rel="stylesheet" href="css/style_new.css">
</head>
<body>
    <div class="main-content">
        <h2>Select Coach/Instructor</h2>
        
        <!-- Display success or error message -->
        <?php if (isset($success_message)) echo "<p class='success-message'>$success_message</p>"; ?>
        <?php if (isset($error_message)) echo "<p class='error-message'>$error_message</p>"; ?>
        
        <!-- Coach Selection Form -->
        <form action="select_coach_instructor.php" method="post" class="selection-form">
            <label for="coach_id">Choose a Coach/Instructor:</label>
            <select id="coach_id" name="coach_id" required>
                <option value="">-- Select Coach/Instructor --</option>
                <?php foreach ($coaches as $coach): ?>
                    <option value="<?php echo $coach['id']; ?>">
                        <?php echo htmlspecialchars($coach['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Select Coach/Instructor</button>
        </form>
    </div>
</body>
</html>
