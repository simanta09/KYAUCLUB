<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval</title>
    <link rel="stylesheet" href="../assests/css/header.css"> <!-- Link to your CSS -->
</head>
<body>

<header class="header">
    <!-- Left: Logo -->
    <div class="logo">
        <a href="../pages/home.php"><img src="../assests/images/logo.jpg" alt="KYAUCC Logo"></a>
        
    </div>
    
    <!-- Middle: Navigation Bar -->
    <nav class="navbar">
        <ul>
            <!-- Dropdown Menu for "About" -->
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">About</a>
                <div class="dropdown-content">
                    <a href="../user_pages/About_KYAUCC.php">About KYAUCC</a>
                    <a href="../user_pages/rulse_and_regulation.php">Rules and Regulations</a>
                </div>
            </li>
            
            <!-- Dropdown Menu for "People" -->
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">People</a>
                <div class="dropdown-content">
                    <a href="founders.php">Founders</a>
                    <a href="executive_committee.php">Executive Committee</a>
                    <a href="former_committee.php">Former Committees</a>
                    <a href="advisors.php">Advisors</a>
                    <a href="../pages/members.php
                    ">Members</a>
                </div>
            </li>
            
            <li><a href="activities.php">Activities</a></li>
            <li><a href="../user_pages/events.php">Events</a></li>
            <li><a href="../user_pages/notices.php">Notices</a></li>
            <li><a href="media.php">Media</a></li>
        </ul>
    </nav>
    
    <!-- Right: Profile and Logout -->
    <div class="user-actions">
        <a href="../user_pages/profile.php" class="profile">Profile</a>
        <a href="../pages/logout.php" class="logout">Logout</a>
    </div>
</header>

</body>
</html>
