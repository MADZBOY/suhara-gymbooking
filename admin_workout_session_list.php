<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT ws.id, u.full_name AS user_name, ws.exercise1, ws.exercise2, ws.exercise3, ws.date, ws.time
            FROM workout_sessions ws
            JOIN users u ON ws.user_id = u.id
            WHERE u.full_name LIKE ? OR ws.id LIKE ?
            ORDER BY ws.date DESC, ws.time DESC";
    $stmt = $conn->prepare($sql);
    $search_term = "%$search%";
    $stmt->bind_param("ss", $search_term, $search_term);
} else {
    $sql = "SELECT ws.id, u.full_name AS user_name, ws.exercise1, ws.exercise2, ws.exercise3, ws.date, ws.time
            FROM workout_sessions ws
            JOIN users u ON ws.user_id = u.id
            ORDER BY ws.date DESC, ws.time DESC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
$workout_sessions = [];
while ($row = $result->fetch_assoc()) {
    $workout_sessions[] = $row;
}

// Handle update and delete actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    if (isset($_POST['delete'])) {
        // Delete the selected workout session
        $sql = "DELETE FROM workout_sessions WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: admin_workout_session_list.php");
        exit();
    } elseif (isset($_POST['update'])) {
        // Update the workout session with new exercises
        $exercise1 = $_POST['exercise1'];
        $exercise2 = $_POST['exercise2'];
        $exercise3 = $_POST['exercise3'];
        $sql = "UPDATE workout_sessions SET exercise1 = ?, exercise2 = ?, exercise3 = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $exercise1, $exercise2, $exercise3, $id);
        $stmt->execute();
        header("Location: admin_workout_session_list.php");
        exit();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workout Session List - Gym Booking System</title>
    <link rel="stylesheet" href="css/style_new.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'admin_menu.php'; ?>
    <div class="content">
        <h2>Workout Session List</h2>

        <!-- Search Form -->
        <form action="admin_workout_session_list.php" method="get" class="search-form">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by name or ID">
            <button type="submit" class="search-button">Search</button>
        </form>

        <!-- Workout Session Table -->
        <div class="table-container">
            <table class="workout-session-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Exercise 1</th>
                        <th>Exercise 2</th>
                        <th>Exercise 3</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($workout_sessions) > 0): ?>
                        <?php foreach ($workout_sessions as $session): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($session['id']); ?></td>
                                <td><?php echo htmlspecialchars($session['user_name']); ?></td>
                                <td><input type="text" name="exercise1" form="form<?php echo $session['id']; ?>" value="<?php echo htmlspecialchars($session['exercise1']); ?>" required></td>
                                <td><input type="text" name="exercise2" form="form<?php echo $session['id']; ?>" value="<?php echo htmlspecialchars($session['exercise2']); ?>" required></td>
                                <td><input type="text" name="exercise3" form="form<?php echo $session['id']; ?>" value="<?php echo htmlspecialchars($session['exercise3']); ?>" required></td>
                                <td><?php echo htmlspecialchars($session['date']); ?></td>
                                <td><?php echo htmlspecialchars($session['time']); ?></td>
                                <td>
                                    <!-- Update and Delete Forms -->
                                    <form action="admin_workout_session_list.php" method="post" id="form<?php echo $session['id']; ?>" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $session['id']; ?>">
                                        <button type="submit" name="update" class="update"><i class="fas fa-edit"></i> Update</button>
                                        <button type="submit" name="delete" class="delete" onclick="return confirm('Are you sure you want to delete this session?');"><i class="fas fa-trash-alt"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No workout sessions found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
