<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "INSERT INTO announcements (title, content) VALUES ('$title', '$content')";
    
    if ($conn->query($sql) === TRUE) {
        $success = "Announcement created successfully!";
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Announcement - Gym Booking System</title>
    <link rel="stylesheet" href="css/style_new.css">
</head>
<body>
    <?php include 'admin_menu.php'; ?>
    <div class="content">
        <h2>Create Announcement</h2>
        <?php if (isset($success)) echo "<p class='success-message'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form action="create_announcement.php" method="post" class="form-container">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
            
            <label for="content">Content</label>
            <textarea id="content" name="content" required></textarea>
            
            <button type="submit">Create Announcement</button>
        </form>
    </div>
</body>
</html>
