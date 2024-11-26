<?php 
session_start();
include('../config/config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch counts
$counts_sql = "
    SELECT 
        (SELECT COUNT(*) FROM users WHERE designation = 'student') AS students,
        (SELECT COUNT(*) FROM users WHERE designation = 'teacher') AS teachers,
        (SELECT COUNT(*) FROM users WHERE designation = 'alumni') AS alumni,
        (SELECT COUNT(*) FROM payments WHERE status = 'approved') AS payments_approved,
        (SELECT COUNT(*) FROM payments WHERE status = 'pending') AS payments_pending;
";
$counts_result = mysqli_query($conn, $counts_sql);
$counts = mysqli_fetch_assoc($counts_result);

// Fetch data for students by batch
$students_sql = "SELECT id, username, student_id, batch FROM users WHERE designation = 'student' ORDER BY batch";
$students_result = mysqli_query($conn, $students_sql);

// Fetch data for teachers
$teachers_sql = "SELECT id, username, student_id, batch FROM users WHERE designation = 'teacher'";
$teachers_result = mysqli_query($conn, $teachers_sql);

// Fetch data for alumni
$alumni_sql = "SELECT id, username, student_id, batch FROM users WHERE designation = 'alumni'";
$alumni_result = mysqli_query($conn, $alumni_sql);

// Group students by batch
$students_by_batch = [];
while ($student = mysqli_fetch_assoc($students_result)) {
    $students_by_batch[$student['batch']][] = $student;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assests/css/members.css">
    <title>Dashboard - Members</title>
</head>
<body>
    <header>
        <h1>Members</h1>
        <nav>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <!-- <section>
            <h2>Counts</h2>
            <ul>
                <li>Students: <?php echo $counts['students']; ?></li>
                <li>Teachers: <?php echo $counts['teachers']; ?></li>
                <li>Alumni: <?php echo $counts['alumni']; ?></li>
                <li>Approved Payments: <?php echo $counts['payments_approved']; ?></li>
                <li>Pending Payments: <?php echo $counts['payments_pending']; ?></li>
            </ul>
        </section> -->

        <!-- Students by Batch -->
        <section>
            <h2>Students by Batch</h2>
            <?php foreach ($students_by_batch as $batch => $students) : ?>
                <h3>Batch: <?php echo htmlspecialchars($batch); ?></h3>
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Student ID</th>
                            <th>Batch</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['username']); ?></td>
                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($student['batch']); ?></td>
                                <td>
                                    <a href="profile.php?id=<?php echo $student['id']; ?>">View Profile</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        </section>

        <!-- Teachers Table -->
        <section>
            <h2>Teachers</h2>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Student ID</th>
                        <th>Batch</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($teacher = mysqli_fetch_assoc($teachers_result)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($teacher['username']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['batch']); ?></td>
                            <td>
                                <a href="profile.php?id=<?php echo $teacher['id']; ?>">View Profile</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- Alumni Table -->
        <section>
            <h2>Alumni</h2>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Student ID</th>
                        <th>Batch</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($alumnus = mysqli_fetch_assoc($alumni_result)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($alumnus['username']); ?></td>
                            <td><?php echo htmlspecialchars($alumnus['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($alumnus['batch']); ?></td>
                            <td>
                                <a href="profile.php?id=<?php echo $alumnus['id']; ?>">View Profile</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
