<?php
// Includes the 'action.php' file from the '../class' directory, which likely contains the logic for user authentication and other actions.
include('../class/action.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login Page</title> <!-- The title of the login page shown in the browser tab. -->
    <meta name="description" content="Football Quiz login page" /> <!-- Brief description of the page's purpose. -->
    <meta name="keywords" content="football, quiz, login" /> <!-- SEO keywords for search engine optimization. -->
    <link rel="stylesheet" href="../css/home-style.css" /> <!-- External CSS file for styling the page. -->
    <!-- Uncomment the below line to include a favicon to the website. -->
    <!-- <link rel="icon" href="../images/favicon.ico" type="image/x-icon" /> -->
</head>

<body>
    <header>
        <h1>Football Quiz!</h1> <!-- Main heading of the webpage. -->
        <nav>
            <!-- Navigation bar for the website. -->
            <ul>
                <!-- List of navigation links. -->
                <li><a href="index.php">Home</a></li> <!-- Link to the home page. -->
                <li><a href="quiz.php">Take the Quiz</a></li> <!-- Link to start the quiz. -->
                <li><a href="scoreboard.php">View the Scoreboard</a></li> <!-- Link to view the scoreboard. -->
                <li><a href="login.php">Log In</a></li> <!-- Link for users to log in (current page). -->
                <li><a href="register.php">Register</a></li> <!-- Link for new users to register. -->
            </ul>
        </nav>
    </header>
    <main>
        <div class="login-container">
            <!-- Container for the login form. -->
            <form class="login-form" action="login.php" method="POST" role="form">
                <!-- The form for logging in, submits to 'login.php' itself for processing. -->
                <h2>Login to Your Account</h2>
                <!-- Heading for the form. -->

                <h3>
                    <?php echo isset($error) ? $error : ""; ?>
                    <!-- Displays error messages returned from the login process. -->
                </h3>
                <div class="form-group">
                    <!-- Group for username input. -->
                    <label for="name">Username</label>
                    <input type="text" id="name" name="name" required aria-label="Username" />
                    <!-- Username input field. -->
                </div>
                <div class="form-group">
                    <!-- Group for password input. -->
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required aria-label="Password" />
                    <!-- Password input field. -->
                </div>
                <button type="submit" class="login-button" name="loginUserButton">Log In</button>
                <!-- Button to submit the form and attempt login. -->
                <p class="signup-link">
                    Don't have an account? <a href="register.php">Sign up</a>
                    <!-- Link to the registration page for new users. -->
                </p>
            </form>
        </div>
    </main>
    <footer>
        <!-- Footer section of the page. -->
        <p>© <span id="currentYear"></span> Football Quiz</p>
        <!-- Copyright notice with dynamic year update handled by JavaScript. -->
    </footer>
    <script src="../js/login.js"></script>
    <!-- Link to external JavaScript file to manage dynamic behavior, such as updating the current year. -->
</body>

</html>