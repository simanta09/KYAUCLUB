<?php
session_start();
include('../config/config.php');

// Ensure the user is logged in as admin
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");  // Redirect to login if not an admin
    exit();
}

// Get the count of approved and pending members, excluding admins
$sql_count = "SELECT COUNT(*) AS total_members FROM users WHERE designation != 'admin'";
$sql_approved = "SELECT COUNT(*) AS approved_members FROM users WHERE status = 'approved' AND designation != 'admin'";
$sql_pending = "SELECT COUNT(*) AS pending_members FROM users WHERE status = 'pending' AND designation != 'admin'";

$result_count = mysqli_query($conn, $sql_count);
$result_approved = mysqli_query($conn, $sql_approved);
$result_pending = mysqli_query($conn, $sql_pending);

$count_data = mysqli_fetch_assoc($result_count);
$approved_data = mysqli_fetch_assoc($result_approved);
$pending_data = mysqli_fetch_assoc($result_pending);

$total_members = $count_data['total_members'];
$approved_members = $approved_data['approved_members'];
$pending_members = $pending_data['pending_members'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assests/css/admin_dashboard.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin_approval.php">Approve Members</a></li>
            <li><a href="view_recent_members.php">View Recent Approved Members</a></li>
            <li><a href="admin_events.php">Events</a></li>
            <li><a href="admin_payments.php">Payments</a></li>
            <li><a href="manage_notices.php">Notices</a></li>
            <li><a href="members.php">Members</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li><a href="../pages/event_payments.php">event payments</a></li>
            
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h1>Admin Dashboard</h1>
        </header>

        <section>
            <h2>Overview</h2>
            <p>Total Members: <?php echo $total_members; ?></p>
            <p>Approved Members: <?php echo $approved_members; ?></p>
            <p>Pending Members: <?php echo $pending_members; ?></p>
        </section>
    </div>
</body>
</html>
