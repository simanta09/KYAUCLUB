<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About KYAUCC</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #0056b3;
            margin-bottom: 40px;
        }
        .section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
            gap: 20px;
        }
        .section:nth-child(even) .text-content {
            order: 2; /* Ensure text comes after the image for even sections */
        }
        .section:nth-child(even) .image-content {
            order: 1; /* Ensure image comes first for even sections */
        }
        .text-content, .image-content {
            flex: 1;
        }
        .text-content {
            padding: 20px;
        }
        .text-content h2 {
            color: #0056b3;
            margin-bottom: 10px;
        }
        .text-content p {
            margin: 10px 0;
            font-size: 1.1em;
        }
        .image-content img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        footer {
            text-align: center;
            margin-top: 40px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
<?php
// Include header
include '../includes/header.php';
?>
    <div class="container">
        <h1>About KYAU Computer Club</h1>

        <!-- Motive and Mission Section -->
        <div class="section">
            <div class="image-content">
                <img src="../assests/images/clubimage1.jpg" alt="Mission Image">
            </div>
            <div class="text-content">
                <h2>Our Motive and Mission</h2>
                <p>KYAU Computer Club (KYAUCC) is committed to fostering a dynamic learning environment for students, alumni, and faculty. 
                   Our mission is to enhance technical skills, encourage innovation, and nurture the next generation of tech leaders.</p>
                <p>We aim to provide a platform for collaborative learning, professional networking, and personal growth by organizing workshops, coding challenges, and tech-related events.</p>
            </div>
        </div>

        <!-- Goal Section -->
        <div class="section">
            <div class="text-content">
                <h2>Our Goals</h2>
                <p>Our primary goal is to bridge the gap between theoretical knowledge and practical application. We aim to equip members with skills that align with industry standards.</p>
                <p>We also focus on creating a community where members can contribute to real-world projects, stay updated with the latest technological trends, and achieve their career aspirations.</p>
            </div>
            <div class="image-content">
                <img src="../assests/images/clubimage1.jpg" alt="Goal Image">
            </div>
        </div>

        <!-- Vision Section -->
        <div class="section">
            <div class="image-content">
                <img src="../assests/images/clubimage1.jpg" alt="Vision Image">
            </div>
            <div class="text-content">
                <h2>Our Vision</h2>
                <p>To be a leading university club that empowers students and faculty through technology. We envision KYAUCC as a hub for innovation, leadership, and excellence in the field of computer science and IT.</p>
            </div>
        </div>

        <!-- Values Section -->
        <div class="section">
            <div class="text-content">
                <h2>Our Values</h2>
                <p>We value creativity, collaboration, and continuous learning. KYAUCC is dedicated to fostering an inclusive environment where every member feels empowered to contribute and grow.</p>
            </div>
            <div class="image-content">
                <img src="../assests/images/clubimage1.jpg" alt="Values Image">
            </div>
        </div>

        <!-- Achievements Section -->
        <div class="section">
            <div class="image-content">
                <img src="../assests/images/clubimage1.jpg" alt="Achievements Image">
            </div>
            <div class="text-content">
                <h2>Our Achievements</h2>
                <p>Over the years, KYAUCC has organized numerous successful workshops, hackathons, and seminars. We take pride in our members’ accomplishments, including internships at top tech companies, winning coding competitions, and contributing to impactful projects.</p>
            </div>
        </div>
    </div>

    <footer>
        &copy; 2024 KYAU Computer Club. All rights reserved.
    </footer>
</body>
</html>
