<?php
session_start();
include('../config/config.php');

// Check if the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all payments with user details
$sql = "SELECT p.id AS payment_id, p.trx_id, p.amount, p.month, p.status, 
        u.username, u.student_id, u.batch, u.phone, p.created_at
        FROM payments p 
        JOIN users u ON p.user_id = u.id 
        ORDER BY u.batch, p.created_at DESC";
$result = mysqli_query($conn, $sql);

// Update payment status if admin submits approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_id'], $_POST['action'])) {
    $payment_id = mysqli_real_escape_string($conn, $_POST['payment_id']);
    $action = $_POST['action'] === 'approve' ? 'approved' : 'rejected';

    $update_sql = "UPDATE payments SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt, 'si', $action, $payment_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='color: green;'>Payment status updated successfully!</p>";
    } else {
        echo "<p style='color: red;'>Failed to update payment status. Try again.</p>";
    }
}

// Group payments by batch
$payments_by_batch = [];
while ($payment = mysqli_fetch_assoc($result)) {
    $payments_by_batch[$payment['batch']][] = $payment;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Payment Approval</title>
    <link rel="stylesheet" href="../assests/css/admin_payment.css">
</head>
<body>
    <h1>Admin - Payment Approval</h1>

    <?php foreach ($payments_by_batch as $batch => $payments): ?>
        <h2>Batch: <?php echo htmlspecialchars($batch); ?></h2>
        <table>
            <tr>
                <th>Transaction ID</th>
                <th>Amount</th>
                <th>Month</th>
                <th>User</th>
                <th>Student ID</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Payment Date & Time</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($payment['trx_id']); ?></td>
                    <td><?php echo htmlspecialchars($payment['amount']); ?></td>
                    <td><?php echo htmlspecialchars($payment['month']); ?></td>
                    <td><?php echo htmlspecialchars($payment['username']); ?></td>
                    <td><?php echo htmlspecialchars($payment['student_id']); ?></td>
                    <td>
                        <?php 
                            // Check if phone number exists and display it
                            echo !empty($payment['phone']) ? htmlspecialchars($payment['phone']) : 'Not available';
                        ?>
                    </td>
                    <td><?php echo ucfirst(htmlspecialchars($payment['status'])); ?></td>
                    <td>
                        <?php 
                            // Format the created_at timestamp to a readable format
                            $payment_date = new DateTime($payment['created_at']);
                            echo $payment_date->format('Y-m-d H:i:s');
                        ?>
                    </td>
                    <td class="actions">
                        <?php if ($payment['status'] === 'pending'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id']; ?>">
                                <button type="submit" name="action" value="approve">Approve</button>
                                <button type="submit" name="action" value="reject">Reject</button>
                            </form>
                        <?php else: ?>
                            <span><?php echo ucfirst($payment['status']); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
</body>
</html>
