<?php
// Includes the 'action.php' file for backend logic, possibly containing database connections and user session management.
include('../class/action.php');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Scoreboard</title> <!-- The title of the scoreboard page shown in the browser tab -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" /> <!-- Ensures the page is responsive -->
    <meta name="description" content="Football Quiz scoreboard" /> <!-- Description for SEO -->
    <link rel="stylesheet" href="../css/home-style.css" /> <!-- Link to the CSS file for styling -->
</head>

<body>
    <header>
        <h1>Football Quiz Scoreboard</h1> <!-- Main heading of the webpage -->
        <nav>
            <!-- Navigation bar for the site -->
            <ul>
                <li><a href="index.php">Home</a></li> <!-- Link to the home page -->
                <li><a href="quiz.php">Take the Quiz</a></li> <!-- Link to start the quiz -->
                <li><a href="scoreboard.php">View the Scoreboard</a></li> <!-- Current page link -->
                <!-- Conditional display based on user's login status -->
                <?php if (isset($_SESSION['userid']) && $_SESSION['usertype'] == "user") { ?>
                    <li><a href="scoreboard.php?logoutUser=true">LogOut</a></li>
                <?php } else { ?>
                    <li><a href="login.php">Log In</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>
    <main>
        <table class="scoreboard-table">
            <!-- Table for displaying the scoreboard -->
            <thead>
                <tr>
                    <!-- Table headers -->
                    <th>User</th>
                    <th>Correct Answer</th>
                    <th>Wrong Answer</th>
                    <th>Incomplete Answer</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // SQL query to fetch the top 10 quiz results ordered by the number of correct answers
                $sqlQuery = "SELECT us.name, res.correct_answer, res.wrong_answer, res.incomplete_answer, res.result_date FROM user_quiz_result AS res INNER JOIN users AS us ON res.user_id = us.id ORDER BY res.correct_answer DESC LIMIT 10";

                // Executes the SQL query
                $resultSet = mysqli_query($dbConnect, $sqlQuery);
                // Fetches the result set as an associative array
                $datas = mysqli_fetch_all($resultSet, MYSQLI_ASSOC);
                ?>
                <?php
                // Checks if the data array is not empty and iterates through each result to display in the table
                if (!empty($datas) && count($datas) > 0) {
                    foreach ($datas as $key => $data) { ?>
                        <tr>
                            <!-- Data rows displaying user's quiz results -->
                            <td>
                                <?php echo $data['name']; ?>
                            </td>
                            <td>
                                <?php echo $data['correct_answer']; ?>
                            </td>
                            <td>
                                <?php echo $data['wrong_answer']; ?>
                            </td>
                            <td>
                                <?php echo $data['incomplete_answer']; ?>
                            </td>
                            <td>
                                <?php echo $data['result_date']; ?>
                            </td>
                        </tr>
                    <?php }
                } ?>
            </tbody>
        </table>
    </main>
    <footer>
        <!-- Footer with dynamic year update -->
        <p>© <span id="currentYear"></span> Football Quiz</p>
    </footer>
    <script src="../js/Home-script.js"></script>
    <!-- Link to JavaScript file for dynamic content like updating the current year -->
</body>

</html>