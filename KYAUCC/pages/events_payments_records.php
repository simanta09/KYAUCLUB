<?php
session_start();
include('../config/config.php');

// Ensure the user is logged in as admin
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Redirect if not admin
    exit();
}

// Handle Add Event Payment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_payment'])) {
    $event_id = mysqli_real_escape_string($conn, $_POST['event_id']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);

    $sql = "INSERT INTO event_payments (event_id, payment_method, amount_paid) 
            VALUES ('$event_id', '$payment_method', '$amount')";

    if (mysqli_query($conn, $sql)) {
        echo "Payment details added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch all events for dropdown
$sql_events = "SELECT * FROM events";
$result_events = mysqli_query($conn, $sql_events);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin: Add Payment</title>
</head>
<body>
    <h1>Add Payment Details for Event</h1>
    <form method="POST">
        <label for="event_id">Select Event:</label>
        <select name="event_id" required>
            <?php while ($event = mysqli_fetch_assoc($result_events)) { ?>
                <option value="<?php echo $event['event_id']; ?>">
                    <?php echo $event['event_name']; ?>
                </option>
            <?php } ?>
        </select><br><br>

        <label for="payment_method">Payment Method:</label>
        <input type="text" name="payment_method" placeholder="e.g., Bkash, Bank" required><br><br>

        <label for="amount">Amount:</label>
        <input type="number" name="amount" step="0.01" required><br><br>

        <button type="submit" name="add_payment">Add Payment</button>
    </form>
</body>
</html>
