<?php
// Includes the 'action.php' file, which likely contains functions and logic for handling database operations, sessions, and user authentication.
include('../../class/action.php');

// Checks if a user is logged in and if their user type is 'admin'. If not, redirects to the login page.
if (isset($_SESSION['userid']) && $_SESSION['usertype'] == "admin") {
    // The user is an admin and can proceed.
} else {
    // Not an admin, redirect to login page.
    header("location: ../login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Quiz - Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/admin-style.css" />
</head>

<body>
    <div class="sidebar">
        <a href="../index.php" class="home-btn">Home</a>

        <!-- Quiz Admin Dropdown -->
        <div class="dropdown">
            <button class="dropdown-btn">QUIZ</button>
            <div class="dropdown-content">
                <a href="create.php">Create Quiz</a>
                <a href="view.php">View Quizzes</a>
                <a href="update.php">Update Quiz</a>
                <a href="delete.php">Delete Quiz</a>
            </div>
        </div>

        <!-- User Admin Dropdown -->
        <div class="dropdown">
            <button class="dropdown-btn">USER</button>
            <div class="dropdown-content">
                <a href="../user/create.php">Create User</a>
                <a href="../user/view.php">View User</a>
                <a href="../user/update.php">Update Users</a>
                <a href="../user/delete.php">Delete User</a>
            </div>
        </div>

        <!-- Scoreboard Admin Dropdown -->
        <div class="dropdown">
            <button class="dropdown-btn">SCOREBOARD</button>
            <div class="dropdown-content">
                <a href="../scoreboard/view.php">View Scoreboard</a>
            </div>
        </div>

        <div class="dropdown">
            <button type="button" class="dropdown-btn" onclick="logout();">Logout</button>

        </div>

    </div>

    <main>
        <!-- Main content area where admins can create new quiz questions -->
        <h1>Create New Quiz Question</h1>
        <h3>
            <?php echo isset($error) ? $error : ""; ?>
        </h3>
        <form action="create.php" method="post" class="form-quiz">
            <div class="form-group">
                <label for="question">Question:</label>
                <input type="text" id="question" name="question" required />
            </div>
            <div class="form-group">
                <label for="optionA">Option A:</label>
                <input type="text" id="optionA" name="optionA" required />
            </div>
            <div class="form-group">
                <label for="optionB">Option B:</label>
                <input type="text" id="optionB" name="optionB" required />
            </div>
            <div class="form-group">
                <label for="optionC">Option C:</label>
                <input type="text" id="optionC" name="optionC" required />
            </div>
            <div class="form-group">
                <label for="optionD">Option D:</label>
                <input type="text" id="optionD" name="optionD" required />
            </div>
            <div class="form-group">
                <label for="correctAnswer">Correct Answer:</label>
                <select id="correctAnswer" name="correctAnswer" required>
                    <option value="0">A</option>
                    <option value="1">B</option>
                    <option value="2">C</option>
                    <option value="3">D</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Submit" name="createQuizButton" />
            </div>
        </form>
    </main>

    <footer>
        <p>© <span id="currentYear"></span> Football Quiz</p>
    </footer>
    <script src="../../js/admin-script.js"></script>
    <script>
        function logout() {
            window.location.href = "../index.php?logoutAdmin=true";
        }
    </script>
</body>

</html>