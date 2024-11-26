<?php
session_start();
include('../config/config.php'); // Include your database configuration file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch user details based on session user ID
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result); // Fetch user data
} else {
    echo "User not found!";
    exit();
}

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $batch = mysqli_real_escape_string($conn, $_POST['batch']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $trx_id = mysqli_real_escape_string($conn, $_POST['trx_id']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    
    // Get selected months (checkboxes)
    $months = isset($_POST['month']) ? $_POST['month'] : [];
    
    // Ensure at least one month is selected
    if (empty($months)) {
        echo "<p style='color: red;'>Please select at least one month.</p>";
    } else {
        // Convert the months array to JSON format
        $month_json = json_encode($months); // Convert to JSON format

        // Get selected year
        $year = mysqli_real_escape_string($conn, $_POST['year']);

        // Insert the payment record
        $payment_sql = "INSERT INTO payments (user_id, name, student_id, batch, phone, trx_id, amount, month, year) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $payment_stmt = mysqli_prepare($conn, $payment_sql);
        mysqli_stmt_bind_param($payment_stmt, 'issssssss', $user_id, $name, $student_id, $batch, $phone, $trx_id, $amount, $month_json, $year);

        // Execute the statement
        if (mysqli_stmt_execute($payment_stmt)) {
            echo "<p style='color: green;'>Payment submitted successfully! Awaiting admin verification.</p>";
        } else {
            echo "<p style='color: red;'>Failed to submit payment. Please try again.</p>";
        }
    }
}

// Fetch payment history
$payment_history_sql = "SELECT * FROM payments WHERE user_id = ? ORDER BY created_at DESC";
$payment_history_stmt = mysqli_prepare($conn, $payment_history_sql);
mysqli_stmt_bind_param($payment_history_stmt, 'i', $user_id);
mysqli_stmt_execute($payment_history_stmt);
$payment_history = mysqli_stmt_get_result($payment_history_stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <style>
        /* Basic styling */
        .profile-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .profile-info {
            display: flex;
            align-items: center;
        }
        .profile-picture img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .details {
            margin-left: 20px;
        }
        .details p {
            font-size: 1.1em;
        }
        .details strong {
            color: #333;
        }
        .payment-form, .payment-history {
            margin-top: 20px;
        }
        .payment-history table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .payment-history table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <section class="profile-container">
        <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        
        <div class="profile-info">
            <div class="profile-picture">
                <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
            </div>
            <div class="details">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Student ID:</strong> <?php echo htmlspecialchars($user['student_id']); ?></p>
                <p><strong>Batch:</strong> <?php echo htmlspecialchars($user['batch']); ?></p>
                <p><strong>Designation:</strong> <?php echo htmlspecialchars($user['designation']); ?></p>
            </div>
        </div>

        <!-- Payment Submission Form -->
        <div class="payment-form">
            <h2>Submit Your Payment</h2>
            <form method="POST" action="">
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['username']); ?>" readonly required><br><br>
                
                <label for="student_id">Student ID:</label><br>
                <input type="text" id="student_id" name="student_id" value="<?php echo htmlspecialchars($user['student_id']); ?>" readonly required><br><br>
                
                <label for="batch">Batch:</label><br>
                <input type="text" id="batch" name="batch" value="<?php echo htmlspecialchars($user['batch']); ?>" readonly required><br><br>
                
                <label for="phone">Phone Number:</label><br>
                <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required><br><br>
                
                <label for="trx_id">Transaction ID:</label><br>
                <input type="text" id="trx_id" name="trx_id" required><br><br>
                
                <label for="amount">Amount:</label><br>
                <input type="number" id="amount" name="amount" step="0.01" required><br><br>

                <label for="month">Select Payment Month(s):</label><br>
                <input type="checkbox" name="month[]" value="January"> January
                <input type="checkbox" name="month[]" value="February"> February
                <input type="checkbox" name="month[]" value="March"> March
                <input type="checkbox" name="month[]" value="April"> April
                <input type="checkbox" name="month[]" value="May"> May
                <input type="checkbox" name="month[]" value="June"> June
                <input type="checkbox" name="month[]" value="July"> July
                <input type="checkbox" name="month[]" value="August"> August
                <input type="checkbox" name="month[]" value="September"> September
                <input type="checkbox" name="month[]" value="October"> October
                <input type="checkbox" name="month[]" value="November"> November
                <input type="checkbox" name="month[]" value="December"> December<br><br>

                <label for="year">Select Year:</label><br>
                <select name="year" id="year" required>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                </select><br><br>

                <button type="submit">Submit Payment</button>
            </form>
        </div>

        <!-- Payment History -->
        <div class="payment-history">
            <h2>Payment History</h2>
            <table>
                <tr>
                    <th>Transaction ID</th>
                    <th>Amount</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Status</th>
                </tr>
                <?php while ($payment = mysqli_fetch_assoc($payment_history)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($payment['trx_id']); ?></td>
                        <td><?php echo htmlspecialchars($payment['amount']); ?></td>
                        <td><?php echo htmlspecialchars(implode(", ", json_decode($payment['month']))); ?></td>
                        <td><?php echo htmlspecialchars($payment['year']); ?></td>
                        <td><?php echo htmlspecialchars($payment['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </section>
</body>
</html>
