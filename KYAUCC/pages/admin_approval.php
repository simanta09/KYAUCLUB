<?php
session_start();
include('../config/config.php');

// Ensure the user is logged in as admin
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");  // Redirect to login if not an admin
    exit();
}

// Fetch pending members
$sql_pending = "SELECT * FROM users WHERE status = 'pending'";
$result_pending = mysqli_query($conn, $sql_pending);

// Handle approve or reject action
if (isset($_GET['action'])) {
    $member_id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'approve') {
        // Approve the member
        $update_sql = "UPDATE users SET status = 'approved' WHERE id = ?";
    } elseif ($action == 'reject') {
        // Reject the member
        $update_sql = "UPDATE users SET status = 'rejected' WHERE id = ?";
    }

    // Prepare and execute the query
    $stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt, 'i', $member_id);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: admin_approval.php'); // Refresh the page after action
        exit();
    } else {
        echo "Error updating member status: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assests/css/admin_aproval.css">
    <title>Admin Approval</title>
    <script type="text/javascript">
        function confirmReject(memberId) {
            var result = confirm("Are you sure you want to reject this member?");
            if (result) {
                window.location.href = 'admin_approval.php?action=reject&id=' + memberId; // Redirect to rejection action
            }
        }

        function confirmApprove(memberId) {
            var result = confirm("Are you sure you want to approve this member?");
            if (result) {
                window.location.href = 'admin_approval.php?action=approve&id=' + memberId; // Redirect to approval action
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>Admin Approval</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Pending Members</h2>
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
                if (mysqli_num_rows($result_pending) > 0) {
                    while ($row = mysqli_fetch_assoc($result_pending)) {
                        echo "<tr>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['student_id'] . "</td>";
                        echo "<td>" . $row['batch'] . "</td>";
                        echo "<td><img src='" . $row['profile_picture'] . "' width='50' height='50' alt='Profile Picture'></td>";
                        echo "<td>
                                <button onclick='confirmApprove(" . $row['id'] . ")'>Approve</button>
                                <button onclick='confirmReject(" . $row['id'] . ")'>Reject</button>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No pending members.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

</body>
</html>
