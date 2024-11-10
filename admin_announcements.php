<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$sql = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = $conn->query($sql);
$announcements = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    if (isset($_POST['delete'])) {
        $sql = "DELETE FROM announcements WHERE id = '$id'";
        $conn->query($sql);
        header("Location: admin_announcements.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Gym Booking System</title>
    <link rel="stylesheet" href="css/style_new.css">
</head>
<body>
    <?php include 'admin_menu.php'; ?>
    <div class="content">
        <h2>Announcements</h2>
        <ul class="announcements">
            <?php if (count($announcements) > 0): ?>
                <?php foreach ($announcements as $announcement): ?>
                    <li class="announcement">
                        <h3><?php echo htmlspecialchars($announcement['title']); ?></h3>
                        <p><?php echo htmlspecialchars($announcement['content']); ?></p>
                        <p><small>Posted on: <?php echo date('F j, Y, g:i a', strtotime($announcement['created_at'])); ?></small></p>
                        <form action="admin_announcements.php" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $announcement['id']; ?>">
                            <button type="submit" name="delete" class="delete" onclick="return confirm('Are you sure you want to delete this announcement?');">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="announcement">
                    <p>No announcements found.</p>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
<?php
$conn->close();
?>
