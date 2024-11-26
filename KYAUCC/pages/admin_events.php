<?php
include('../config/config.php'); // Database connection file

// Ensure the 'uploads/events' directory exists or create it
$target_dir = "uploads/events/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true); // Create the folder if it doesn't exist
}

// Add event
if (isset($_POST['add_event'])) {
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_price = $_POST['event_price'];
    $event_date = $_POST['event_date'];

    // Image upload handling
    $event_image = $_FILES['event_image']['name'];
    $target_file = $target_dir . basename($event_image);

    // Check if the file is uploaded
    if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file)) {
        $query = "INSERT INTO events (name, description, image, price, event_date) 
                  VALUES ('$event_name', '$event_description', '$event_image', '$event_price', '$event_date')";
        mysqli_query($conn, $query);
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Update event
if (isset($_POST['update_event'])) {
    $event_id = $_POST['event_id'];
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_price = $_POST['event_price'];

    // Image upload handling (if new image is uploaded)
    if ($_FILES['event_image']['name']) {
        $event_image = $_FILES['event_image']['name'];
        $target_file = $target_dir . basename($event_image);
        if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file)) {
            $update_image_query = ", image = '$event_image'";
        } else {
            echo "Sorry, there was an error uploading the new image.";
        }
    }

    $query = "UPDATE events SET name = '$event_name', description = '$event_description', 
              price = '$event_price' $update_image_query WHERE id = '$event_id'";
    mysqli_query($conn, $query);
}

// Delete event
if (isset($_GET['delete_event'])) {
    $event_id = $_GET['delete_event'];
    $query = "DELETE FROM events WHERE id = '$event_id'";
    mysqli_query($conn, $query);
}
?>

<!-- Admin Events Management Page -->

<!-- Add Event Form -->
<form action="admin_events.php" method="POST" enctype="multipart/form-data" onsubmit="return confirmAdd()">
    <input type="text" name="event_name" placeholder="Event Name" required>
    <textarea name="event_description" placeholder="Event Description" required></textarea>
    <input type="number" name="event_price" placeholder="Event Price (in TK)" required>
    
    <!-- Event Date -->
    <input type="date" name="event_date" required>

    <input type="file" name="event_image" required>
    <button type="submit" name="add_event">Add Event</button>
</form>

<script>
    function confirmAdd() {
        return confirm('Are you sure you want to add this event?');
    }
</script>

<h2>Manage Events</h2>
<table>
    <tr>
        <th>Event Name</th>
        <th>Description</th>
        <th>Price (TK)</th>
        <th>Event Date</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
    <?php
    $events = mysqli_query($conn, "SELECT * FROM events");
    while ($event = mysqli_fetch_assoc($events)) {
        echo "<tr>
            <td>{$event['name']}</td>
            <td>{$event['description']}</td>
            <td>{$event['price']}</td>
            <td>{$event['event_date']}</td>
            <td><img src='uploads/events/{$event['image']}' width='100'></td>
            <td>
                <a href='admin_events.php?edit_event={$event['id']}'>Edit</a> | 
                <a href='javascript:void(0);' onclick='confirmDelete({$event['id']})'>Delete</a>
            </td>
        </tr>";
    }
    ?>
</table>

<script>
    function confirmDelete(event_id) {
        if (confirm('Are you sure you want to delete this event?')) {
            window.location.href = 'admin_events.php?delete_event=' + event_id;
        }
    }
</script>
