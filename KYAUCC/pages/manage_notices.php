<?php
session_start();
include('../config/config.php');

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle form submissions (Add/Update/Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $stmt = $conn->prepare("INSERT INTO notices (title, content) VALUES (?, ?)");
            $stmt->bind_param("ss", $title, $content);
            $stmt->execute();
            $stmt->close();
        } elseif ($_POST['action'] === 'update') {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $stmt = $conn->prepare("UPDATE notices SET title = ?, content = ? WHERE id = ?");
            $stmt->bind_param("ssi", $title, $content, $id);
            $stmt->execute();
            $stmt->close();
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'];
            $stmt = $conn->prepare("DELETE FROM notices WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Fetch all notices with error handling
$notices_result = $conn->query("SELECT * FROM notices ORDER BY created_at DESC");
if (!$notices_result) {
    die("Error fetching notices: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assests/css/manage_notices.css">
    <title>Manage Notices</title>
</head>
<body>
    <header>
        <h1>Manage Notices</h1>
        <nav>
            <a href="admin_dashboard.php">Admin Dashboard</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <section>
            <h2>Add a New Notice</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <input type="text" name="title" placeholder="Notice Title" required>
                <textarea name="content" placeholder="Notice Content" required></textarea>
                <button type="submit">Add Notice</button>
            </form>
        </section>

        <section>
            <h2>Existing Notices</h2>
            <?php if ($notices_result->num_rows > 0): ?>
                <?php while ($notice = $notices_result->fetch_assoc()): ?>
                    <div class="notice">
                        <h3><?php echo htmlspecialchars($notice['title']); ?></h3>
                        <p><?php echo htmlspecialchars($notice['content']); ?></p>
                        <small>Created At: <?php echo $notice['created_at']; ?></small>
                        <form method="POST" class="update-form">
                            <input type="hidden" name="id" value="<?php echo $notice['id']; ?>">
                            <input type="hidden" name="action" value="update">
                            <input type="text" name="title" value="<?php echo htmlspecialchars($notice['title']); ?>" required>
                            <textarea name="content" required><?php echo htmlspecialchars($notice['content']); ?></textarea>
                            <button type="submit">Update</button>
                        </form>
                        <form method="POST" class="delete-form">
                            <input type="hidden" name="id" value="<?php echo $notice['id']; ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit">Delete</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No notices available.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
