<?php
include('../config/config.php');

// Get payments by batch along with user information
$query = "SELECT u.batch, p.txn_id, e.name AS event_name, u.username AS user_name, u.student_id, u.profile_picture, p.amount
          FROM event_payments p
          JOIN users u ON p.user_id = u.id
          JOIN events e ON p.event_id = e.id
          ORDER BY u.batch";

$payments = mysqli_query($conn, $query);

$batch_payments = [];
$total_amount = 0; // To keep track of the total amount

while ($payment = mysqli_fetch_assoc($payments)) {
    $batch_payments[$payment['batch']][] = $payment;
    $total_amount += $payment['amount']; // Add the amount to the total
}

foreach ($batch_payments as $batch => $payments) {
    echo "<h3>Batch: $batch</h3>";
    echo "<table border='1'>
        <tr>
            <th>Event Name</th>
            <th>User Name</th>
            <th>Student ID</th>
            <th>Profile Photo</th>
            <th>Transaction ID</th>
            <th>Amount (TK)</th>
        </tr>";

    $batch_total = 0; // Total amount for this specific batch

    foreach ($payments as $payment) {
        // Display user information along with the event and payment details
        echo "<tr>
            <td>{$payment['event_name']}</td>
            <td>{$payment['user_name']}</td>
            <td>{$payment['student_id']}</td>
            <td><img src='../uploads/{$payment['profile_picture']}' alt='Profile Picture' width='50' height='50'></td>
            <td>{$payment['txn_id']}</td>
            <td>{$payment['amount']}</td>
        </tr>";

        $batch_total += $payment['amount']; // Add batch amount
    }

    echo "<tr><td colspan='5'><strong>Total for Batch $batch:</strong></td><td><strong>{$batch_total}</strong></td></tr>";
    echo "</table><br>";
}

// Display the overall total amount collected
echo "<h3>Total Amount Collected: {$total_amount} TK</h3>";

// Button to delete all payment information (after the fest is finished)
echo "<form method='POST'>
    <input type='submit' name='delete_payments' value='Delete All Payment Information' onclick='return confirm(\"Are you sure you want to delete all payment data?\");'>
</form>";

// Handle deletion of all payment information
if (isset($_POST['delete_payments'])) {
    // Delete all payment records from the database
    $delete_query = "DELETE FROM event_payments";
    if (mysqli_query($conn, $delete_query)) {
        echo "<p style='color: green;'>All payment information has been deleted successfully.</p>";
    } else {
        echo "<p style='color: red;'>Error deleting payment information: " . mysqli_error($conn) . "</p>";
    }
}
?>
