<?php
// Includes the 'action.php' file for backend functionality, such as database operations or session management.
include('../class/action.php');

// Check if a user is logged in and of the 'user' type. If not, redirect them to the login page.
if (isset($_SESSION['userid']) && $_SESSION['usertype'] == "user") {
    // User is logged in and can access the page.
} else {
    // Redirects to login page if user is not logged in or not of type 'user'.
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Football Quiz</title> <!-- The page title shown in the browser tab -->
    <meta name="description" content="Football Quiz for testing your knowledge about football history." />
    <!-- Description for search engines -->
    <meta name="keywords" content="football, quiz, history" /> <!-- SEO keywords -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" /> <!-- Responsive design tag -->
    <link rel="stylesheet" href="../css/home-style.css" /> <!-- Link to CSS file for styling -->
</head>

<body>
    <header>
        <h1>Football Quiz!</h1> <!-- Main heading of the webpage -->
        <nav>
            <!-- Navigation bar with links to other pages -->
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="quiz.php">Take the Quiz</a></li>
                <li><a href="scoreboard.php">View the Scoreboard</a></li>
                <!-- Dynamically display links based on user's login status -->
                <?php if (isset($_SESSION['userid']) && $_SESSION['usertype'] == "user") { ?>
                    <li><a href="quiz.php?logoutUser=true">LogOut</a></li>
                <?php } else { ?>
                    <li><a href="login.php">Log In</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>
    <main>
        <section id="quiz-container" class="quiz-container">
            <!-- Container for the quiz -->
            <h2>Football History</h2> <!-- Quiz topic heading -->
            <button type="button" id="startQuiz">Start Quiz</button> <!-- Button to start the quiz -->

            <form id="quizForm">
                <!-- The quiz itself, contained within a form for user interaction -->
                <div id="quizProgress" currentIndex="0"></div> <!-- Displays quiz progress -->
                <div id="timer"></div> <!-- Timer for the quiz -->
                <div class="question-container" id="question-0" hidden></div>
                <!-- Container for quiz questions, initially hidden -->
                <button type="button" id="nextQuestion" hidden>Next Question</button>
                <!-- Button for navigating to the next question, initially hidden -->
            </form>
            <div class="quiz-results" id="quiz-results" hidden></div>
            <!-- Container for displaying quiz results, initially hidden -->
        </section>
    </main>
    <footer>
        <!-- Footer with copyright information and dynamic year update -->
        <p>© <span id="currentYear"></span> Football Quiz</p>
    </footer>
    <script src="../js/quiz.js"></script> <!-- Link to external JavaScript file for quiz functionality -->
</body>

</html>