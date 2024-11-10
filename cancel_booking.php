<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "UPDATE bookings SET status = 'cancelled' WHERE id = '$id' AND user_id = '{$_SESSION['user_id']}'";
    
    if ($conn->query($sql) === TRUE) {
        header('Location: user_bookings.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
