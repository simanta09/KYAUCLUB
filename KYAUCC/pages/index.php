<?php
// Include database configuration file
include('../config/config.php');  // Adjust the path to where config.php is located

// Get the count of students, teachers, and alumni based on their designations
$student_count_sql = "SELECT COUNT(*) AS student_count FROM users WHERE designation = 'student' AND status = 'approved'";
$teacher_count_sql = "SELECT COUNT(*) AS teacher_count FROM users WHERE designation = 'teacher' AND status = 'approved'";
$alumni_count_sql = "SELECT COUNT(*) AS alumni_count FROM users WHERE designation = 'alumni' AND status = 'approved'";

// Prepare and execute the query for students
$student_stmt = mysqli_prepare($conn, $student_count_sql);
if ($student_stmt === false) {
    die('Error preparing statement: ' . mysqli_error($conn));
}
mysqli_stmt_execute($student_stmt);
$student_result = mysqli_stmt_get_result($student_stmt);

// Prepare and execute the query for teachers
$teacher_stmt = mysqli_prepare($conn, $teacher_count_sql);
if ($teacher_stmt === false) {
    die('Error preparing statement: ' . mysqli_error($conn));
}
mysqli_stmt_execute($teacher_stmt);
$teacher_result = mysqli_stmt_get_result($teacher_stmt);

// Prepare and execute the query for alumni
$alumni_stmt = mysqli_prepare($conn, $alumni_count_sql);
if ($alumni_stmt === false) {
    die('Error preparing statement: ' . mysqli_error($conn));
}
mysqli_stmt_execute($alumni_stmt);
$alumni_result = mysqli_stmt_get_result($alumni_stmt);

// Initialize count variables
$student_count = 0;
$teacher_count = 0;
$alumni_count = 0;

// Fetch results for students
if ($student_result) {
    $row = mysqli_fetch_assoc($student_result);
    $student_count = $row['student_count'];
    mysqli_free_result($student_result);  // Free the result set after fetching
}

// Fetch results for teachers
if ($teacher_result) {
    $row = mysqli_fetch_assoc($teacher_result);
    $teacher_count = $row['teacher_count'];
    mysqli_free_result($teacher_result);  // Free the result set after fetching
}

// Fetch results for alumni
if ($alumni_result) {
    $row = mysqli_fetch_assoc($alumni_result);
    $alumni_count = $row['alumni_count'];
    mysqli_free_result($alumni_result);  // Free the result set after fetching
}

// Handle errors in fetching the results
if (!$student_result || !$teacher_result || !$alumni_result) {
    echo "Error fetching data: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Exo:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assests/css/style.css?v=1.0">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">
            <img src="../assests/images/logo.jpg" alt="KYAU Computer Club Logo">
        </div>
        <div class="title">KYAU Computer Club</div>
        <div class="login">
            <a href="login.php">Login</a>
        </div>
    </header>
    
    <!-- Banner Section -->
    <section class="banner">
        <!-- Banner content can go here -->
    </section>

    <!-- About Club Section -->
<!-- About Club Section -->
<section class="about-club">
    <div class="about-text">
        <h2>About KYAU Computer Club</h2>
        <p>
            The KYAU Computer Club is a dynamic student organization dedicated to fostering innovation, collaboration, and skill development in the field of computer science and technology. We aim to provide a platform where students can grow their skills through workshops, events, hackathons, and collaboration with industry professionals.
        </p>
        <p>
            Join us to be a part of a community that is passionate about technology, problem-solving, and pushing the boundaries of what's possible. Whether you're a beginner or an advanced learner, our club offers something for everyone.
        </p>
        <p>
            Transform your passion into skills! Connect, collaborate, and embrace the limitless possibilities of tech together.
        </p>
    </div>
    <div class="about-image">
        <img src="../assests/images/clubimage1.jpg" alt="About Club Image">
    </div>
</section>


    <!-- Event Cards Section -->
    <div class="container">
        <h2 class="title">Catch a Glimpse of What We Did</h2>
        <div class="event-cards">
            <div class="event-card">
                <img src="../assests/images/cse-fest-2022-2.jpg" alt="CS Fest 2018">
                <div class="event-info">
                    <h3>CS FEST 2018</h3>
                    <p>Some event description here.</p>
                </div>
            </div>
            <div class="event-card">
                <img src="../assests/images/tech-fest.jpg" alt="Cyber Gaming Fest">
                <div class="event-info">
                    <h3>CYBER GAMING FEST</h3>
                    <p>Some event description here.</p>
                </div>
            </div>
            <div class="event-card">
                <img src="../assests/images/cse-fest-20202.jpg" alt="JARVIS AI">
                <div class="event-info">
                    <h3>JARVIS AI</h3>
                    <p>Some event description here.</p>
                </div>
            </div>
        </div>
        <button class="more-events">Check Out More of Events</button>
    </div>

    <!-- Club Stats Section -->
<section class="club-stats">
    <div class="stat-box">
        <h2><?php echo $student_count; ?></h2>
        <h3>Students</h3>
    </div>
    <div class="stat-box">
        <h2><?php echo $teacher_count; ?></h2>
        <h3>Teachers</h3>
    </div>
    <div class="stat-box">
        <h2><?php echo $alumni_count; ?></h2>
        <h3>Alumni</h3>
    </div>
</section>



</body>
</html>
