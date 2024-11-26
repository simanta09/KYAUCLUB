<?php
session_start();
include('../config/config.php');

// Ensure the user is logged in as admin
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");  // Redirect to login if not an admin
    exit();
}

// Get the member ID from the query string
if (isset($_GET['id'])) {
    $member_id = $_GET['id'];

    // Update the member status to 'approved'
    $sql = "UPDATE users SET status = 'approved' WHERE id = $member_id";
    if (mysqli_query($conn, $sql)) {
        echo "Member approved successfully.";
        header('Location: admin_approval.php');  // Redirect to the approval page after success
    } else {
        echo "Error approving member: " . mysqli_error($conn);
    }
}
?>
