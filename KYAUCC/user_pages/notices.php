<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('../config/config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch all notices
$notices_result = $conn->query("SELECT * FROM notices ORDER BY created_at DESC");

// Check if the query was successful
if ($notices_result === false) {
    die("Error: Unable to fetch notices.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assests/css/notices.css">
    <title>Notices</title>
</head>
<body>
    <h1>Notice Board</h1>
    
    <?php if ($notices_result->num_rows > 0): ?>
        <!-- Loop through notices and display them -->
        <?php while ($notice = $notices_result->fetch_assoc()) : ?>
            <div>
                <h2><?php echo htmlspecialchars($notice['title']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($notice['content'])); ?></p>
                <small>Posted on: <?php echo date("F j, Y, g:i a", strtotime($notice['created_at'])); ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <!-- Message when no notices are available -->
        <p>No notices available at the moment.</p>
    <?php endif; ?>

</body>
</html>
