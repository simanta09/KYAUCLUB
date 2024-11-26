<?php
session_start();
include('../config/config.php');

// Ensure the user is logged in as admin
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Redirect to login if not an admin
    exit();
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $member_id = intval($_GET['delete_id']); // Ensure the ID is an integer

    // Check if the member is a predefined admin before deleting
    $check_admin_sql = "SELECT designation FROM users WHERE id = ?";
    $stmt_check = mysqli_prepare($conn, $check_admin_sql);
    mysqli_stmt_bind_param($stmt_check, 'i', $member_id);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    $member = mysqli_fetch_assoc($result_check);

    if (!$member) {
        echo "<script>alert('Member not found.');</script>";
    } elseif ($member['designation'] == 'admin') {
        echo "<script>alert('You cannot delete a predefined admin!');</script>";
    } else {
        // Delete the member from the database
        $delete_sql = "DELETE FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($stmt, 'i', $member_id);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: view_recent_members.php'); // Refresh the page after deletion
            exit();
        } else {
            echo "<script>alert('Error deleting member: " . mysqli_error($conn) . "');</script>";
        }
    }
}

// Fetch recent teachers and alumni
$sql_recent_teachers = "SELECT * FROM users WHERE status = 'approved' AND designation = 'teacher' ORDER BY id DESC LIMIT 5";
$result_recent_teachers = mysqli_query($conn, $sql_recent_teachers);

$sql_recent_alumni = "SELECT * FROM users WHERE status = 'approved' AND designation = 'alumni' ORDER BY id DESC LIMIT 5";
$result_recent_alumni = mysqli_query($conn, $sql_recent_alumni);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assests/css/aproved_member.css">
    <title>Recent Approved Members</title>
    <script type="text/javascript">
        function confirmDelete(memberId) {
            if (confirm("Are you sure you want to delete this member?")) {
                window.location.href = 'view_recent_members.php?delete_id=' + memberId;
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>Recent Approved Members</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Students Grouped by Batch</h2>
        <?php
        $sql_batches = "SELECT DISTINCT batch FROM users WHERE status = 'approved' AND designation = 'student'";
        $result_batches = mysqli_query($conn, $sql_batches);

        if (mysqli_num_rows($result_batches) > 0) {
            while ($batch_row = mysqli_fetch_assoc($result_batches)) {
                $batch = $batch_row['batch'];
                echo "<h2>Students - Batch: " . htmlspecialchars($batch) . "</h2>";
                
                $sql_students = "SELECT * FROM users WHERE status = 'approved' AND designation = 'student' AND batch = '$batch'";
                $result_students = mysqli_query($conn, $sql_students);
                
                if (mysqli_num_rows($result_students) > 0) {
                    echo "<table border='1'>
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Student ID</th>
                                    <th>Batch</th>
                                    <th>Profile Picture</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>";
                    
                    while ($row = mysqli_fetch_assoc($result_students)) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['username']) . "</td>
                                <td>" . htmlspecialchars($row['student_id']) . "</td>
                                <td>" . htmlspecialchars($row['batch']) . "</td>
                                <td><img src='../" . htmlspecialchars($row['profile_picture']) . "' width='50' height='50' alt='Profile Picture'></td>
                                <td><button onclick='confirmDelete(" . intval($row['id']) . ")'>Delete</button></td>
                              </tr>";
                    }
                    
                    echo "</tbody>
                          </table>";
                } else {
                    echo "<p>No students found for Batch: " . htmlspecialchars($batch) . "</p>";
                }
            }
        } else {
            echo "<p>No batches found.</p>";
        }
        ?>
    </section>

    <section>
        <h2>Recent Teachers</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Student ID</th>
                    <th>Batch</th>
                    <th>Profile Picture</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result_recent_teachers) > 0) {
                    while ($row = mysqli_fetch_assoc($result_recent_teachers)) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['username']) . "</td>
                                <td>" . htmlspecialchars($row['student_id']) . "</td>
                                <td>" . htmlspecialchars($row['batch']) . "</td>
                                <td><img src='../" . htmlspecialchars($row['profile_picture']) . "' width='50' height='50' alt='Profile Picture'></td>
                                <td><button onclick='confirmDelete(" . intval($row['id']) . ")'>Delete</button></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No recent teachers.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Recent Alumni</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Student ID</th>
                    <th>Batch</th>
                    <th>Profile Picture</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result_recent_alumni) > 0) {
                    while ($row = mysqli_fetch_assoc($result_recent_alumni)) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['username']) . "</td>
                                <td>" . htmlspecialchars($row['student_id']) . "</td>
                                <td>" . htmlspecialchars($row['batch']) . "</td>
                                <td><img src='../" . htmlspecialchars($row['profile_picture']) . "' width='50' height='50' alt='Profile Picture'></td>
                                <td><button onclick='confirmDelete(" . intval($row['id']) . ")'>Delete</button></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No recent alumni.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>
