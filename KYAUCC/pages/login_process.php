<?php
session_start();
include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to fetch the user by username and approved status
    $sql = "SELECT * FROM users WHERE username = '$username' AND status = 'approved'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Check if the user is admin
        if ($user['username'] == 'admin') {
            // Direct comparison for admin (plain-text password)
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = 'admin';
                header('Location: admin_dashboard.php');
                exit();
            } else {
                echo "Incorrect admin password!";
            }
        } else {
            // For regular users, use password_verify() to check the hashed password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on user role
                if ($user['role'] == 'admin') {
                    header('Location: admin_dashboard.php');
                } else {
                    header('Location: home.php');  // Regular user home page
                }
                exit();
            } else {
                echo "Incorrect password!";
            }
        }
    } else {
        echo "User not found or pending approval.";
    }
}
?>
