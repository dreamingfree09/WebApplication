<?php include('../class/action.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Page</title>
    <meta name="description" content="Football Quiz login page"/>
    <meta name="keywords" content="football, quiz, login"/>
    <link rel="stylesheet" href="../css/home-style.css"/>
</head>
<body>
<main>
    <div class="login-container">
        <form action="login.php" method="post" class="login-form" role="form">
            <input type="hidden" name="type" value="adminLogin">
            <h3> <?php echo isset($error) ? $error : ""; ?></h3>
            <h2>Login to Your Account</h2>
            <div class="form-group">
                <label for="username">Username</label>
                <input
                        type="text"
                        id="username"
                        name="email"
                        required
                        aria-label="Username"
                />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        aria-label="Password"
                />
            </div>
            <button type="submit" name="loginButton" class="login-button">Log In</button>
        </form>
    </div>
</main>
<script src="../js/login.js"></script>
</body>
</html>
