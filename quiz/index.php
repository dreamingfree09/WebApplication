<?php
// This PHP tag includes the 'action.php' file from the '../class' directory. 
// The 'action.php' file definitions for various actions, functions and database operations related to the website.
include('../class/action.php');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Home Page</title> <!-- The title of the webpage shown in the browser tab. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- The viewport meta tag ensures the site is responsive and displays correctly on all devices. -->
    <meta name="description"
        content="The Football Quiz is a fun and interactive way to test your knowledge of football." />
    <!-- Description meta tag for SEO, providing a brief description of the page's content. -->
    <link rel="stylesheet" href="../css/home-style.css" />
    <!-- Link to an external CSS file that styles the homepage. -->
</head>

<body>
    <header>
        <h1>Football Quiz!</h1> <!-- The main heading of the webpage. -->
        <nav>
            <!-- Navigation bar section for site navigation links. -->
            <ul>
                <!-- Unordered list used to contain navigation items. -->
                <li><a href="index.php">Home</a></li> <!-- Navigation link to the home page. -->
                <li><a href="quiz.php">Take the Quiz</a></li> <!-- Link to take the quiz. -->
                <li>
                    <a href="scoreboard.php">View the Scoreboard</a> <!-- Link to view the scoreboard. -->
                </li>
                <?php
                // PHP conditional statement to display navigation options based on the user's login status.
                if (isset($_SESSION['userid']) && $_SESSION['usertype'] == "user") {
                    // If the user is logged in (checked by session 'userid') and is of type 'user',
                    ?>
                    <li><a href="index.php?logoutUser=true">LogOut</a></li>
                    <!-- Display the logout option. -->
                <?php } else { ?>
                    <li><a href="login.php">Log In</a></li> <!-- Display login link if user not logged in. -->
                    <li><a href="register.php">Register</a></li> <!-- Display registration link for new users. -->
                <?php } ?>
            </ul>
        </nav>
    </header>
    <main>
        <!-- Main content area of the webpage. -->
        <section>
            <!-- Section for about the quiz information. -->
            <h2>About the Quiz</h2>
            <p>
                The Football Quiz is a fun and interactive way to test your knowledge
                of football. Our quiz features questions on a wide range of topics,
                including the history of football, famous players, and memorable
                matches.
            </p>
        </section>
        <figure id="imageCarousel" class="carousel">
            <!-- Placeholder for a carousel or image gallery (requires implementation in JS/CSS). -->
            <figcaption>Football is my life</figcaption>
            <!-- Caption for the carousel or image gallery. -->
        </figure>
        <section>
            <!-- Section describing how to play the quiz. -->
            <h2>How to Play</h2>
            <p>
                To play the quiz, simply click on the "Take the Quiz" link in the
                navigation bar. You will be presented with a series of multiple-choice
                questions. Choose the answer that you think is correct and click on
                the "Submit" button. At the end of the quiz, you will be shown your
                score and the correct answers to the questions.
            </p>
        </section>
        <section>
            <!-- Section about the creators of the quiz. -->
            <h2>About Us</h2>
            <p>
                The Football Quiz was created by a team of football enthusiasts who
                wanted to share their love of the game with others. Our goal is to
                provide a fun and engaging way for people to learn more about football
                and test their knowledge.
            </p>
        </section>
    </main>
    <footer>
        <!-- Footer section of the webpage. -->
        <p>© <span id="currentYear"></span> Football Quiz</p> <!-- Copyright notice with dynamic year update. -->
    </footer>
    <script src="../js/Home-script.js"></script>
    <!-- Link to external JavaScript file for dynamic behavior, like updating the current year. -->
</body>

</html>