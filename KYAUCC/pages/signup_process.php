<?php
include('../config/config.php');

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize the inputs to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $designation = mysqli_real_escape_string($conn, $_POST['designation']);
    $batch = mysqli_real_escape_string($conn, $_POST['batch']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $session = ($designation == 'alumni') ? mysqli_real_escape_string($conn, $_POST['session']) : NULL;

    // Profile picture handling
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_name = basename($_FILES['profile_picture']['name']);
        $file_size = $_FILES['profile_picture']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($file_ext, $allowed_extensions) && $file_size <= 5 * 1024 * 1024) {
            $new_file_name = uniqid('profile_', true) . '.' . $file_ext;
            $profile_picture = 'uploads/' . $new_file_name;

            if (!move_uploaded_file($file_tmp, '../uploads/' . $new_file_name)) {
                echo "Error uploading the file.";
                exit;
            }
        } else {
            echo "Invalid file type or file size exceeds 5MB.";
            exit;
        }
    } else {
        echo "Please upload a profile picture.";
        exit;
    }

    // Hash the password using password_hash
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the user data using prepared statements
    $sql = "INSERT INTO users (username, password, student_id, designation, batch, session, profile_picture, status, email, blood_group) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?)";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'sssssssss', $username, $hashed_password, $student_id, $designation, $batch, $session, $profile_picture, $email, $blood_group);
        if (mysqli_stmt_execute($stmt)) {
            echo "Sign-up successful! Please wait for admin approval.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing the SQL statement.";
    }
}
?>
