<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM bookings WHERE id = '$id'";
$result = $conn->query($sql);
$booking = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $session_date = $_POST['session_date'];
    $session_time = $_POST['session_time'];
    $status = $_POST['status'];
    
    $sql = "UPDATE bookings SET session_date='$session_date', session_time='$session_time', status='$status' WHERE id='$id'";
    $conn->query($sql);
    header("Location: admin_booking_list.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Booking - Gym Booking System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'admin_menu.php'; ?>
    <div class="container">
        <h2>Update Booking</h2>
        <form action="admin_update_booking.php?id=<?php echo $booking['id']; ?>" method="post">
            <div class="form-container">
                <label for="session_date">Session Date</label>
                <input type="date" id="session_date" name="session_date" value="<?php echo htmlspecialchars($booking['session_date']); ?>" required>

                <label for="session_time">Session Time</label>
                <input type="time" id="session_time" name="session_time" value="<?php echo htmlspecialchars($booking['session_time']); ?>" required>

                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="booked" <?php if ($booking['status'] == 'booked') echo 'selected'; ?>>Booked</option>
                    <option value="cancelled" <?php if ($booking['status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
                </select>

                <button type="submit" class="update">Update</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
