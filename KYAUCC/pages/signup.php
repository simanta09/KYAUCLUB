<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - KYAU Computer Club</title>
    <link rel="stylesheet" href="../assests/css/signup.css">
    <style>
        .hidden { display: none; }
    </style>
</head>
<body>
<header>
        <div class="logo">
            <img src="../assests/images/logo.jpg" alt="KYAU Computer Club Logo">
        </div>
        <div class="title">KYAU Computer Club</div>
        <div class="login">
            <a href="login.php">Login</a>
        </div>
    </header>
    <div class="signup-form">
        <h2>Sign Up</h2>
        <form action="signup_process.php" method="POST" enctype="multipart/form-data">
            <!-- Username -->
            <input type="text" name="username" placeholder="Username" required>
            <!-- Password -->
            <input type="password" name="password" placeholder="Password" required>
            <!-- Student ID -->
            <input type="text" name="student_id" placeholder="ID" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
  
            
            <!-- Designation -->
            <div>
                <label>
                    <input type="radio" name="designation" value="teacher" required onchange="toggleFields()"> Teacher
                </label>
                <label>
                    <input type="radio" name="designation" value="student" required onchange="toggleFields()"> Student
                </label>
                <label>
                    <input type="radio" name="designation" value="alumni" required onchange="toggleFields()"> Alumni
                </label>
            </div>

            <!-- Batch (Only for Students) -->
            <div id="batch-field" class="hidden">
                <select name="batch">
                    <option value="">Select Batch</option>
                    <?php 
                    // Generate batch options dynamically
                    for ($i = 10; $i <= 20; $i++) {
                        echo "<option value='$i'>Batch $i</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Session (Only for Alumni) -->
            <div id="session-field" class="hidden">
                <select name="session">
                    <option value="">Select Session</option>
                    <?php
                    // Define session options
                    $sessions = [
                        "2015-2019",
                        "2016-2020",
                        "2017-2021",
                        "2018-2022",
                        "2019-2023"
                    ];
                    // Generate session options dynamically
                    foreach ($sessions as $session) {
                        echo "<option value='$session'>Session $session</option>";
                    }
                    ?>
                </select>
            </div>
            <label for="blood_group">Blood Group:</label>
<select name="blood_group" id="blood_group" required>
    <option value="">Select Blood Group</option>
    <option value="A+">A+</option>
    <option value="A-">A-</option>
    <option value="B+">B+</option>
    <option value="B-">B-</option>
    <option value="AB+">AB+</option>
    <option value="AB-">AB-</option>
    <option value="O+">O+</option>
    <option value="O-">O-</option>
</select>
<br>


            <!-- Profile Picture -->
            <input type="file" name="profile_picture" required>
            
            <!-- Submit Button -->
            <button type="submit">Sign Up</button>
        </form>
    </div>

    <script>
        // Function to toggle batch and session fields based on designation
        function toggleFields() {
            const designation = document.querySelector('input[name="designation"]:checked').value;
            const batchField = document.getElementById('batch-field');
            const sessionField = document.getElementById('session-field');

            if (designation === "student") {
                batchField.classList.remove('hidden');
                sessionField.classList.add('hidden');
            } else if (designation === "alumny") {
                sessionField.classList.remove('hidden');
                batchField.classList.add('hidden');
            } else {
                batchField.classList.add('hidden');
                sessionField.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
