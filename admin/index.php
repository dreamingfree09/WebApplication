<?php
// Include the action.php file for shared functionality across the platform.
include('../class/action.php');

// Check if the user is logged in as an admin; if not, redirect to the login page.
if (!isset($_SESSION['userid']) || $_SESSION['usertype'] != "admin") {
    header("location: login.php");
    exit; // Prevent further execution if redirected.
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Football Quiz</title>
    <link rel="stylesheet" href="../css/admin-style.css" /> <!-- Stylesheet link for admin dashboard styling -->
</head>

<body>
    <div class="sidebar">
        <a href="index.php" class="home-btn">Home</a>

        <!-- Dropdown menus for various admin functions -->

        <!-- Quiz Admin Dropdown -->
        <div class="dropdown">
            <button class="dropdown-btn">QUIZ</button>
            <div class="dropdown-content">
                <a href="quiz/create.php">Create Quiz</a>
                <a href="quiz/view.php">View Quizzes</a>
                <a href="quiz/update.php">Update Quiz</a>
                <a href="quiz/delete.php">Delete Quiz</a>
            </div>
        </div>

        <!-- User Admin Dropdown -->
        <div class="dropdown">
            <button class="dropdown-btn">USER</button>
            <div class="dropdown-content">
                <a href="user/create.php">Create User</a>
                <a href="user/view.php">View User</a>
                <a href="user/update.php">Update Users</a>
                <a href="user/delete.php">Delete User</a>
            </div>
        </div>

        <!-- Scoreboard Admin Dropdown -->
        <div class="dropdown">
            <button class="dropdown-btn">SCOREBOARD</button>
            <div class="dropdown-content">
                <a href="scoreboard/view.php">View Scoreboard</a>
            </div>
        </div>

        <!-- Logout button -->
        <div class="dropdown">
            <button type="button" class="dropdown-btn" onclick="logout();">Logout</button>
        </div>

    </div>

    <main>
        <h1>Dashboard</h1> <!-- Main content heading -->

        <!-- Main content for the admin dashboard goes here -->
    </main>
    <footer>
        <p>© <span id="currentYear"></span> Football Quiz</p>
    </footer>
    <script src="../js/admin-script.js"></script> <!-- JavaScript for admin dashboard functionality -->
    <script>
        // Logout function
        function logout() {
            window.location.href = "index.php?logoutAdmin=true";
        }
    </script>
</body>

</html>