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
    <title>Create User - Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/admin-style.css" /> <!-- Stylesheet link for admin dashboard styling -->
</head>

<body>
    <div class="sidebar">
        <a href="../index.php" class="home-btn">Home</a>

        <!-- Dropdown menus for various admin functions -->
        <div class="dropdown">
            <button class="dropdown-btn">QUIZ</button>
            <div class="dropdown-content">
                <a href="../quiz/create.php">Create Quiz</a>
                <a href="../quiz/view.php">View Quizzes</a>
                <a href="../quiz/update.php">Update Quiz</a>
                <a href="../quiz/delete.php">Delete Quiz</a>
            </div>
        </div>

        <!-- User Admin Dropdown -->
        <div class="dropdown">
            <button class="dropdown-btn">USER</button>
            <div class="dropdown-content">
                <a href="create.php">Create User</a> <!-- Current page -->
                <a href="view.php">View User</a>
                <a href="update.php">Update Users</a>
                <a href="delete.php">Delete User</a>
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
            <button type="button" class="dropdown-btn" onclick="logout();">Logout</button> <!-- Logout functionality -->
        </div>
    </div>

    <main>
        <h1>Create New User</h1> <!-- Main content heading -->
        <h3>
            <?php echo isset($error) ? $error : ""; ?>
        </h3> <!-- Display error message if any -->
        <form action="create.php" method="post" class="form-user">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required />
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required />
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required />
            </div>
            <div class="form-group">
                <label for="userType">User Type</label>
                <select id="userType" name="userType">
                    <option value="">Select User Type</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Create User" name="createNewUser" /> <!-- Create User button -->
            </div>
        </form>
    </main>

    <footer>
        <p>© <span id="currentYear"></span> Football Quiz</p> <!-- Dynamic year in footer -->
    </footer>
    <script src="../../js/admin-script.js"></script>
    <script>
        function logout() {
            window.location.href = "../index.php?logoutAdmin=true";
        }
    </script>
</body>

</html>