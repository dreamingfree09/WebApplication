<?php include('../class/action.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Page</title>
    <meta name="description" content="Football Quiz login page"/>
    <meta name="keywords" content="football, quiz, login"/>
    <link rel="stylesheet" href="../css/home-style.css"/>
    <!-- <link rel="icon" href="../images/favicon.ico" type="image/x-icon" /> -->
</head>
<body>
<header>
    <h1>Football Quiz!</h1>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="quiz.php">Take the Quiz</a></li>
            <li>
                <a href="scoreboard.php">View the Scoreboard</a>
            </li>
            <li><a href="login.php">Log In</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>
</header>
<main>
    <div class="login-container">
        <form class="login-form" action="login.php" method="POST" role="form">
            <h2>Login to Your Account</h2>

            <h3> <?php echo isset($error) ? $error : ""; ?></h3>
            <div class="form-group">
                <label for="username">Username</label>
                <input
                        type="text"
                        id="username"
                        name="username"
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
            <button type="submit" class="login-button" name="loginUserButton">Log In</button>
            <p class="signup-link">
                Don't have an account? <a href="register.php">Sign up</a>
            </p>
        </form>
    </div>
</main>
<footer>
    <p>© <span id="currentYear"></span> Football Quiz</p>
</footer>
<script src="../js/login.js"></script>
</body>
</html>
