<?php
include('../config/config.php');
session_start();
$events = mysqli_query($conn, "SELECT * FROM events");
if (mysqli_num_rows($events) > 0) {
    while ($event = mysqli_fetch_assoc($events)) {
        // Construct the image path with relative path adjustment
        $imagePath = "../uploads/events/" . $event['image'];
        
        // Fallback to placeholder if image is missing or empty
        if (empty($event['image']) || !file_exists($imagePath)) {
            // Log the missing image for debugging (you can remove this line later)
            var_dump($event['image']);
            $imagePath = "../images/placeholder.png"; // Path to the placeholder image
        }

        // Format the event date
        $eventDate = date("F j, Y", strtotime($event['event_date']));
        ?>

        <div class="event-container">
            <h3><?php echo htmlspecialchars($event['name']); ?></h3>
            <p><?php echo htmlspecialchars($event['description']); ?></p>

            <!-- Display Event Image -->
            <img src="<?php echo $imagePath; ?>" 
                 alt="<?php echo htmlspecialchars($event['name']); ?>" 
                 onerror="this.onerror=null; this.src='../images/placeholder.png';">
            
            <p>Image path: <?php echo $imagePath; ?></p>

            <!-- Event Details -->
            <p class="event-price">Price: <?php echo htmlspecialchars($event['price']); ?> TK</p>
            <p class="event-date">Event Date: <?php echo $eventDate; ?></p>

            <!-- Payment Form -->
            <form action="events.php" method="POST" class="payment-form">
                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                <input type="hidden" name="amount" value="<?php echo $event['price']; ?>">

                <label for="txn_id">Transaction ID:</label>
                <input type="text" name="txn_id" placeholder="Enter Transaction ID" 
                       pattern="[A-Za-z0-9]{10,}" 
                       title="Transaction ID must be at least 10 alphanumeric characters" 
                       required class="form-input">

                <label for="amount">Enter Amount (TK):</label>
                <input type="number" name="amount" value="<?php echo $event['price']; ?>" 
                       required class="form-input" min="1">

                <button type="submit" name="pay_event" class="submit-button">Pay Now</button>
            </form>
        </div>
        <?php
    }
} else {
    echo "<p style='text-align: center;'>No upcoming events available.</p>";
}
?>
