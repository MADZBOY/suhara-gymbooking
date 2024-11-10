<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Fetch the list of coach/instructor selections with user details
$sql = "SELECT uts.id, u.full_name AS user_name, t.name AS trainer_name, uts.created_at
        FROM user_trainer_selections uts
        JOIN users u ON uts.user_id = u.id
        JOIN trainers t ON uts.trainer_id = t.id
        ORDER BY u.full_name";
$result = $conn->query($sql);
$trainer_selections = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $trainer_selections[] = $row;
    }
}

// Handle update and delete actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    if (isset($_POST['delete'])) {
        // Delete the selected record
        $sql = "DELETE FROM user_trainer_selections WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: admin_coach_selection_list.php");
        exit();
    } elseif (isset($_POST['update'])) {
        // Update the selected record with new trainer ID
        $new_trainer_id = $_POST['trainer_id'];
        $sql = "UPDATE user_trainer_selections SET trainer_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $new_trainer_id, $id);
        $stmt->execute();
        header("Location: admin_coach_selection_list.php");
        exit();
    }
}

// Fetch the list of all trainers for updating purposes
$trainer_sql = "SELECT id, name FROM trainers ORDER BY name";
$trainer_result = $conn->query($trainer_sql);
$trainers = [];
if ($trainer_result->num_rows > 0) {
    while ($trainer_row = $trainer_result->fetch_assoc()) {
        $trainers[] = $trainer_row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selected Coach/Instructor List - Gym Booking System</title>
    <link rel="stylesheet" href="css/style_new.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'admin_menu.php'; ?>
    <div class="content">
        <h2>Selected Coach/Instructor List</h2>
        <div class="table-container">
            <table class="coach-selection-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Current Trainer</th>
                        <th>Selected Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($trainer_selections) > 0): ?>
                        <?php foreach ($trainer_selections as $selection): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($selection['id']); ?></td>
                                <td><?php echo htmlspecialchars($selection['user_name']); ?></td>
                                <td>
                                    <form action="admin_coach_selection_list.php" method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $selection['id']; ?>">
                                        <select name="trainer_id" required>
                                            <?php foreach ($trainers as $trainer): ?>
                                                <option value="<?php echo $trainer['id']; ?>" <?php if ($trainer['name'] === $selection['trainer_name']) echo 'selected'; ?>>
                                                    <?php echo htmlspecialchars($trainer['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" name="update" class="update">
                                            <i class="fas fa-edit"></i> Update
                                        </button>
                                    </form>
                                </td>
                                <td><?php echo htmlspecialchars($selection['created_at']); ?></td>
                                <td>
                                    <form action="admin_coach_selection_list.php" method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $selection['id']; ?>">
                                        <button type="submit" name="delete" class="delete" onclick="return confirm('Are you sure you want to delete this record?');">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No coach/instructor selections found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
