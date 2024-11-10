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
    $sql = "SELECT b.*, u.full_name FROM bookings b JOIN users u ON b.user_id = u.id WHERE u.full_name LIKE '%$search%' ORDER BY b.session_date DESC, b.session_time DESC";
} else {
    $sql = "SELECT b.*, u.full_name FROM bookings b JOIN users u ON b.user_id = u.id ORDER BY b.session_date DESC, b.session_time DESC";
}
$result = $conn->query($sql);
$bookings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    if (isset($_POST['delete'])) {
        $sql = "DELETE FROM bookings WHERE id = '$id'";
        $conn->query($sql);
        header("Location: admin_booking_list.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking List - Gym Booking System</title>
    <link rel="stylesheet" href="css/style_new.css">
</head>
<body>
    <?php include 'admin_menu.php'; ?>
    <div class="content">
        <h2>Booking List</h2>

        <!-- Search Form -->
        <form action="admin_booking_list.php" method="get">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by user name">
            <button type="submit">Search</button>
        </form>

        <!-- Booking Table -->
        <div class="table-container">
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Session Date</th>
                        <th>Session Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($bookings) > 0): ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['id']); ?></td>
                                <td><?php echo htmlspecialchars($booking['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['session_date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['session_time']); ?></td>
                                <td><?php echo htmlspecialchars($booking['status']); ?></td>
                                <td>
                                    <form action="admin_update_booking.php" method="get" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
                                        <button type="submit" class="update">Update</button>
                                    </form>
                                    <form action="admin_booking_list.php" method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
                                        <button type="submit" name="delete" class="delete" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No bookings found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
