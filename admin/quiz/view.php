<?php
// Include the action.php for shared functionality across the platform.
include('../../class/action.php');

// Check for admin session; if not found, redirect to login page.
if (!isset($_SESSION['userid']) || $_SESSION['usertype'] != "admin") {
    header("location: ../login.php");
    exit; // Prevent further execution in case of redirect.
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Quizzes - Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/admin-style.css" /> <!-- Stylesheet link for admin dashboard styling -->
</head>

<body>
    <div class="sidebar">
        <a href="../index.php" class="home-btn">Home</a> <!-- Sidebar navigation button to the dashboard home -->

        <!-- Dropdown menus for various admin functions -->
        <div class="dropdown">
            <button class="dropdown-btn">QUIZ</button>
            <div class="dropdown-content">
                <!-- Links to create, view, update, and delete quizzes -->
                <a href="create.php">Create Quiz</a>
                <a href="view.php">View Quizzes</a> <!-- Current page -->
                <a href="update.php">Update Quiz</a>
                <a href="delete.php">Delete Quiz</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropdown-btn">USER</button>
            <div class="dropdown-content">
                <!-- Links to user management functionalities -->
                <a href="../user/create.php">Create User</a>
                <a href="../user/view.php">View User</a>
                <a href="../user/update.php">Update Users</a>
                <a href="../user/delete.php">Delete User</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropdown-btn">SCOREBOARD</button>
            <div class="dropdown-content">
                <a href="../scoreboard/view.php">View Scoreboard</a> <!-- Link to view the scoreboard -->
            </div>
        </div>

        <div class="dropdown">
            <button type="button" class="dropdown-btn" onclick="logout();">Logout</button> <!-- Logout functionality -->
        </div>
    </div>

    <main>
        <h1>View Quiz Questions</h1> <!-- Main content heading -->
        <table class="quiz-table">
            <thead>
                <tr>
                    <th>Question</th> <!-- Table header for the question text -->
                    <th>Options</th> <!-- Table header for the options -->
                    <th>Correct Answer</th> <!-- Table header for the correct answer -->
                </tr>
            </thead>
            <?php
            // Fetch all quizzes from the database
            $sqlQuery = "SELECT * FROM quizzes";
            $resultSet = mysqli_query($dbConnect, $sqlQuery);
            $datas = mysqli_fetch_all($resultSet, MYSQLI_ASSOC);
            ?>
            <tbody>
                <?php
                // Loop through each quiz question and display
                if (!empty($datas) && count($datas) > 0) {
                    foreach ($datas as $data) { ?>
                        <tr>
                            <td>
                                <?php echo $data['question']; ?>
                            </td> <!-- Display question -->
                            <?php
                            // Process and display options and correct answer
                            $optionString = "";
                            $correctAnswer = "";
                            if (!empty($data['options'])) {
                                $options = explode(',', $data['options']);
                                $keys = ['A', 'B', 'C', 'D']; // Option labels
                                foreach ($options as $key => $option) {
                                    $optionString .= !empty($optionString) ? ", " : "";
                                    $optionString .= "{$keys[$key]}) {$option}";
                                }
                                $correctAnswer = "{$keys[$data['correctAnswer']]}) {$options[$data['correctAnswer']]}";
                            }
                            ?>
                            <td>
                                <?php echo $optionString; ?>
                            </td> <!-- Display options -->
                            <td>
                                <?php echo $correctAnswer; ?>
                            </td> <!-- Display correct answer -->
                        </tr>
                    <?php }
                } ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>© <span id="currentYear"></span> Football Quiz</p> <!-- Dynamic year in footer -->
    </footer>
    <script src="../../js/admin-script.js"></script>
    <script>
        function logout() {
            window.location.href = "../index.php?logoutAdmin=true"; // Logout function
        }
    </script>
</body>

</html>