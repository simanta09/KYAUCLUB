<?php
session_start();
include('../config/config.php');

// Validate input
if (!isset($_GET['id'])) {
    header("Location: members.php");
    exit();
}

$member_id = intval($_GET['id']);

// Fetch user and payment details
$sql = "
    SELECT 
        u.id AS user_id,
        u.username,
        u.student_id,
        u.batch,
        u.designation,
        u.profile_picture,
        p.trx_id,
        p.amount,
        p.month,
        p.status AS payment_status
    FROM 
        users u
    LEFT JOIN 
        payments p ON u.id = p.user_id
    WHERE 
        u.id = ?
";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $member_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

if (!$user_data) {
    echo "<p>User not found.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assests/css/profile.css">
    <title><?php echo htmlspecialchars($user_data['username']); ?>'s Profile</title>
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($user_data['username']); ?>'s Profile</h1>
        <nav>
            <a href="members.php">Back to Members</a>
        </nav>
    </header>

    <section>
        <h2>Details</h2>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user_data['username']); ?></p>
        <p><strong>Student ID:</strong> <?php echo htmlspecialchars($user_data['student_id']); ?></p>
        <p><strong>Batch:</strong> <?php echo htmlspecialchars($user_data['batch']); ?></p>
        <p><strong>Designation:</strong> <?php echo htmlspecialchars($user_data['designation']); ?></p>
    </section>

    <section>
        <h2>Profile Picture</h2>
        <img src="../<?php echo htmlspecialchars($user_data['profile_picture']); ?>" alt="Profile Picture" width="150">
    </section>

    <section>
        <h2>Payment Details</h2>
        <?php if ($user_data['trx_id']): ?>
            <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($user_data['trx_id']); ?></p>
            <p><strong>Amount:</strong> <?php echo htmlspecialchars($user_data['amount']); ?></p>
            <p><strong>Month:</strong> <?php echo htmlspecialchars($user_data['month']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($user_data['payment_status']); ?></p>
        <?php else: ?>
            <p>No payment records found.</p>
        <?php endif; ?>
    </section>
</body>
</html>
