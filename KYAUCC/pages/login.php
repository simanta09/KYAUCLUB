<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KYAU Computer Club</title>
    <link rel="stylesheet" href="../assests/css/login.css">
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
    <div class="login-form">
        <h2>Login</h2>
        <form action="login_process.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="signup-option">
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>
