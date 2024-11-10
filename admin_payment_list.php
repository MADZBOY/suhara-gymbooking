<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$search = '';
$edit_payment = null;

// Handle search functionality
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT p.*, u.full_name FROM payments p 
            JOIN users u ON p.user_id = u.id 
            WHERE u.full_name LIKE '%$search%' OR p.type LIKE '%$search%' 
            ORDER BY p.created_at DESC";
} else {
    $sql = "SELECT p.*, u.full_name FROM payments p 
            JOIN users u ON p.user_id = u.id 
            ORDER BY p.created_at DESC";
}

$result = $conn->query($sql);
$payments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
}

// Fetch payment details for updating if requested
if (isset($_GET['edit_id'])) {
    $payment_id = $_GET['edit_id'];
    $edit_sql = "SELECT * FROM payments WHERE id = ?";
    $stmt = $conn->prepare($edit_sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_payment = $result->fetch_assoc();
}

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_payment'])) {
    $payment_id = $_POST['id'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $method = $_POST['method'];
    $status = $_POST['status'];
    $expiry_date = $_POST['expiry_date'];

    $update_sql = "UPDATE payments SET type = ?, amount = ?, method = ?, status = ?, expiry_date = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sdsssi", $type, $amount, $method, $status, $expiry_date, $payment_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Payment updated successfully!'); window.location.href='admin_payment_list.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating payment.');</script>";
    }
}

// Handle delete action
if (isset($_POST['delete_payment'])) {
    $payment_id = $_POST['id'];
    $delete_sql = "DELETE FROM payments WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $payment_id);
    if ($delete_stmt->execute()) {
        echo "<script>alert('Payment deleted successfully!'); window.location.href='admin_payment_list.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error deleting payment.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment List - Gym Booking System</title>
    <link rel="stylesheet" href="css/style_new.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'admin_menu.php'; ?>
    <div class="container">
        <h2>Payment List</h2>
        
        <!-- Search Form -->
        <form action="admin_payment_list.php" method="get">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by name or type">
            <button type="submit">Search</button>
        </form>

        <!-- Payment Table -->
        <div class="table-container">
            <table class="payment-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Expiry Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($payments) > 0): ?>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment['id']); ?></td>
                                <td><?php echo htmlspecialchars($payment['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($payment['type']); ?></td>
                                <td><?php echo htmlspecialchars(number_format($payment['amount'], 2)); ?></td>
                                <td><?php echo htmlspecialchars($payment['method']); ?></td>
                                <td><?php echo htmlspecialchars($payment['status']); ?></td>
                                <td><?php echo htmlspecialchars($payment['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($payment['expiry_date']); ?></td>
                                <td>
                                    <a href="admin_payment_list.php?edit_id=<?php echo $payment['id']; ?>" class="update-btn"><i class="fas fa-edit"></i> Update</a>
                                    <form action="admin_payment_list.php" method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $payment['id']; ?>">
                                        <button type="submit" name="delete_payment" class="delete-btn" onclick="return confirm('Are you sure you want to delete this payment?');"><i class="fas fa-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">No payments found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Update Payment Form (only visible if editing) -->
        <?php if ($edit_payment): ?>
            <div class="form-container">
                <h2>Update Payment ID: <?php echo htmlspecialchars($edit_payment['id']); ?></h2>
                <form action="admin_payment_list.php" method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_payment['id']); ?>">

                    <label for="type">Type:</label>
                    <input type="text" id="type" name="type" value="<?php echo htmlspecialchars($edit_payment['type']); ?>" required>

                    <label for="amount">Amount:</label>
                    <input type="number" id="amount" name="amount" step="0.01" value="<?php echo htmlspecialchars($edit_payment['amount']); ?>" required>

                    <label for="method">Method:</label>
                    <input type="text" id="method" name="method" value="<?php echo htmlspecialchars($edit_payment['method']); ?>" required>

                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="Pending" <?php if ($edit_payment['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                        <option value="Completed" <?php if ($edit_payment['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                        <option value="Cancelled" <?php if ($edit_payment['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                    </select>

                    <label for="expiry_date">Expiry Date:</label>
                    <input type="date" id="expiry_date" name="expiry_date" value="<?php echo htmlspecialchars($edit_payment['expiry_date']); ?>" required>

                    <button type="submit" name="update_payment" class="update-btn"><i class="fas fa-save"></i> Save Changes</button>
                    <button type="button" onclick="window.location.href='admin_payment_list.php';" class="cancel-btn">Cancel</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
