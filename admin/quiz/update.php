<?php 
// Includes the backend logic script for operational functionality.
include('../../class/action.php');

// Checks if a user session exists and if the user is an admin. If not, redirects to the login page.
if (!isset($_SESSION['userid']) || $_SESSION['usertype'] != "admin") {
    header("location: ../login.php");
    exit; // Stops script execution to ensure redirection.
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" /> <!-- Sets the character encoding for the document. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" /> <!-- Ensures the page is mobile-responsive. -->
    <title>Update Quiz - Admin Dashboard</title> <!-- The title of the page -->
    <link rel="stylesheet" href="../../css/admin-style.css" /> <!-- Link to the admin dashboard's stylesheet -->
</head>

<body>
    <div class="sidebar">
        <!-- Navigation sidebar with links for admin actions -->
        <a href="../index.php" class="home-btn">Home</a> <!-- Home button -->

        <!-- Dropdown for Quiz management -->
        <div class="dropdown">
            <button class="dropdown-btn">QUIZ</button> <!-- Dropdown button -->
            <div class="dropdown-content"> <!-- Dropdown content with links to quiz management pages -->
                <a href="create.php">Create Quiz</a>
                <a href="view.php">View Quizzes</a>
                <a href="update.php">Update Quiz</a> <!-- Current page -->
                <a href="delete.php">Delete Quiz</a>
            </div>
        </div>

        <!-- Dropdown for User management -->
        <div class="dropdown">
            <button class="dropdown-btn">USER</button> <!-- Dropdown button -->
            <div class="dropdown-content"> <!-- Dropdown content with links to user management pages -->
                <a href="../user/create.php">Create User</a>
                <a href="../user/view.php">View User</a>
                <a href="../user/update.php">Update Users</a>
                <a href="../user/delete.php">Delete User</a>
            </div>
        </div>

        <!-- Dropdown for Scoreboard management -->
        <div class="dropdown">
            <button class="dropdown-btn">SCOREBOARD</button> <!-- Dropdown button -->
            <div class="dropdown-content"> <!-- Dropdown content with a link to the scoreboard view page -->
                <a href="../scoreboard/view.php">View Scoreboard</a>
            </div>
        </div>

        <!-- Logout functionality -->
        <div class="dropdown">
            <button type="button" class="dropdown-btn" onclick="logout();">Logout</button> <!-- Logout button -->
        </div>
    </div>

    <main>
        <!-- PHP block to fetch all quiz questions for selection -->
        <?php
        $sqlQuery = "SELECT id, question FROM quizzes"; // SQL query to fetch quiz questions
        $resultSet = mysqli_query($dbConnect, $sqlQuery); // Executes the query
        $datas = mysqli_fetch_all($resultSet, MYSQLI_ASSOC); // Fetches the results as an associative array
        ?>
        <h1>Update Quiz Question</h1> <!-- Page heading -->
        <h3>
            <?php echo isset($error) ? $error : ""; ?> <!-- Displays any error messages -->
        </h3>
        <div class="select-question">
            <label for="selectQuestion">Select Question:</label> <!-- Dropdown to select a question for updating -->
            <select id="selectQuestion" name="selectQuestion" onchange="getQuestionData();">
                <option value="">Select Question</option>
                <?php
                // Populates dropdown options with questions from the database
                if (!empty($datas) && count($datas) > 0) {
                    foreach ($datas as $key => $data) {
                        echo "<option value=\"{$data['id']}\"" . (isset($_GET['updateQuestionId']) && $_GET['updateQuestionId'] == $data['id'] ? " selected" : "") . ">{$data['question']}</option>";
                    }
                }
                ?>
            </select>
        </div>
        <!-- Form for updating the selected quiz question -->
        <form action="update.php" method="post" class="form-quiz">
            <input type="hidden" value="<?php echo isset($_GET['updateQuestionId']) ? $_GET['updateQuestionId'] : ""; ?>" name="updateQuestionId">
            <!-- Input fields for question text and options (A, B, C, D) -->
            <!-- Dynamically fills in the question and option fields if data is available -->
            <div class="form-group">
                <label for="question">Question:</label>
                <input type="text" id="question" name="question" value="<?php echo !empty($result) ? $result['question'] : ""; ?>" required />
            </div>
            <!-- Repeats for options A-D -->
            <div class="form-group">
                <label for="correctAnswer">Correct Answer:</label> <!-- Dropdown to select the correct answer -->
                <select id="correctAnswer" name="correctAnswer" required>
                    <!-- Options for correct answer (A-D), pre-selected based on current data -->
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Update" name="updateQuizButton" /> <!-- Submit button for the form -->
            </div>
        </form>
    </main>

    <footer>
        <p>© <span id="currentYear"></span> Football Quiz</p> <!-- Footer with dynamic year update -->
    </footer>

    <script src="../../js/admin-script.js"></script> <!-- External JavaScript for admin functionalities -->
    <script>
        // JavaScript function to fetch and display data for the selected question
        function getQuestionData() {
            var selectElement = document.querySelector('#selectQuestion');
            var output = selectElement.value;
            window.location.href = "update.php?updateQuestionId=" + output; // Redirect to update page with selected question ID
        }
        // JavaScript function for admin logout
        function logout() {
            window.location.href = "../index.php?logoutAdmin=true"; // Redirect to logout action
        }
    </script>
</body>
</html>
