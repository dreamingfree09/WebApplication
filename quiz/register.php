<?php
// Includes the 'action.php' file at the beginning, which likely contains the backend logic for handling the registration process.
include('../class/action.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registration Page</title> <!-- The title of the registration page shown in the browser tab -->
    <link rel="stylesheet" href="../css/home-style.css" /> <!-- External CSS file for styling the page -->
</head>

<body>
    <header>
        <h1>Football Quiz!</h1> <!-- Main heading of the webpage -->
        <nav>
            <!-- Navigation bar for site navigation -->
            <ul>
                <li><a href="index.php">Home</a></li> <!-- Link to the home page -->
                <li><a href="quiz.php">Take the Quiz</a></li> <!-- Link to the quiz page -->
                <li><a href="scoreboard.php">View the Scoreboard</a></li> <!-- Link to the scoreboard page -->
                <li><a href="login.php">Log In</a></li> <!-- Link to the login page -->
                <li><a href="register.php">Register</a></li> <!-- Link to the current registration page -->
            </ul>
        </nav>
    </header>
    <main>
        <h3>
            <?php echo isset($error) ? $error : ""; ?>
            <!-- Displays error messages that may have been set during the registration process -->
        </h3>
        <section class="registration-container">
            <!-- Container for the registration form -->
            <form class="registration-form" action="register.php" method="POST" enctype="multipart/form-data">
                <!-- The form for users to fill out to register. It posts data back to 'register.php'. -->
                <h2>Create Your Account</h2>
                <div class="form-group">
                    <!-- Input field for username -->
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required minlength="6" maxlength="20"
                        autocomplete="username" oninput="validateUsername()" />
                    <!-- Validates the username on input -->
                    <div id="username-error" class="error-message"></div>
                    <!-- Placeholder for username validation error message -->
                </div>
                <div class="form-group">
                    <!-- Input field for email -->
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required maxlength="50" autocomplete="email"
                        pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$" oninput="validateEmail()" />
                    <!-- Validates the email on input -->
                    <div id="email-error" class="error-message"></div>
                    <!-- Placeholder for email validation error message -->
                </div>
                <div class="form-group">
                    <!-- Input field for password -->
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="8" maxlength="20"
                        oninput="validatePassword()" />
                    <!-- Validates the password on input -->
                    <div id="password-error" class="error-message"></div>
                    <!-- Placeholder for password validation error message -->
                </div>
                <div class="form-group">
                    <!-- Input field for confirming password -->
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" required minlength="8"
                        maxlength="20" oninput="validateConfirmPassword()" />
                    <!-- Validates the confirmed password on input -->
                    <div id="confirm-password-error" class="error-message"></div>
                    <!-- Placeholder for confirm password validation error message -->
                </div>
                <button type="submit" class="register-button" name="registerUser">Register</button>
                <!-- Button to submit the registration form -->
                <p class="login-link">
                    Already have an account? <a href="login.php">Log in</a>
                    <!-- Provides a link to the login page for users who already have an account -->
                </p>
            </form>
        </section>
    </main>
    <footer>
        <!-- Footer with copyright information and dynamic year update -->
        <p>© <span id="currentYear"></span> Football Quiz</p>
    </footer>
    <script src="../js/register.js"></script>
    <!-- External JavaScript file linked to handle frontend form validation and dynamic behaviors like updating the current year -->
</body>

</html>