<?php
// Function to check if user is logged in
function check_login() {
    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit();
    }
}

// Function to check if user is admin
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}
?>
