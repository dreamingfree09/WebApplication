<?php
// Include the action.php file for shared functionality across the platform.
include('../class/action.php');

// Check if there's an error message set, and if so, display it.
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login Page</title>
    <meta name="description" content="Football Quiz login page" />
    <meta name="keywords" content="football, quiz, login" />
    <link rel="stylesheet" href="../css/home-style.css" /> <!-- Stylesheet link for login page styling -->
</head>

<body>
    <main>
        <div class="login-container">
            <form action="login.php" method="post" class="login-form" role="form">
                <input type="hidden" name="type" value="adminLogin">
                <!-- Hidden input field for specifying login type (adminLogin) -->
                <h3>
                    <?php echo isset($error) ? $error : ""; ?> <!-- Display error message if it's set -->
                </h3>
                <h2>Login to Your Account</h2> <!-- Login form heading -->
                <div class="form-group">
                    <label for="name">Username</label>
                    <input type="text" id="name" name="name" required aria-label="Username" />
                    <!-- Input field for username -->
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required aria-label="Password" />
                    <!-- Input field for password -->
                </div>
                <button type="submit" name="loginButton" class="login-button">Log In</button> <!-- Login button -->
            </form>
        </div>
    </main>
    <script src="../js/login.js"></script> <!-- JavaScript for login page functionality -->
</body>

</html>