<?php
// Include the action.php file for shared functionality across the platform.
include('../../class/action.php');

// Check if the user is logged in as an admin; if not, redirect to the login page.
if (!isset($_SESSION['userid']) || $_SESSION['usertype'] != "admin") {
    header("location: ../login.php");
    exit; // Prevent further execution if redirected.
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Users - Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/admin-style.css" /> <!-- Stylesheet link for admin dashboard styling -->
</head>

<body>
    <div class="sidebar">
        <a href="../index.php" class="home-btn">Home</a>

        <!-- Dropdown menus for various admin functions -->

        <!-- Quiz Admin Dropdown -->
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
                <a href="create.php">Create User</a>
                <a href="view.php">View User</a> <!-- Current page -->
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

        <!-- Logout button -->
        <div class="dropdown">
            <button type="button" class="dropdown-btn" onclick="logout();">Logout</button>
        </div>
    </div>

    <main>
        <h1>View Users</h1> <!-- Main content heading -->

        <!-- Table for displaying user data -->
        <table class="user-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>User Type</th>
                    <th>Email</th>
                    <!-- Add more columns if necessary -->
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch user data from the database.
                $sqlQuery = "SELECT * FROM users";
                $resultSet = mysqli_query($dbConnect, $sqlQuery);
                $datas = mysqli_fetch_all($resultSet, MYSQLI_ASSOC);
                ?>
                <?php
                // Display user data in the table rows.
                if (!empty($datas) && count($datas) > 0) {
                    foreach ($datas as $key => $data) { ?>
                        <tr>
                            <td>
                                <?php echo $data['name']; ?>
                            </td>
                            <td>
                                <?php echo $data['type']; ?>
                            </td>
                            <td>
                                <?php echo $data['email']; ?>
                            </td>
                        </tr>
                    <?php }
                } ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>© <span id="currentYear"></span> Football Quiz</p>
    </footer>
    <script src="../../js/admin-script.js"></script> <!-- JavaScript for admin dashboard functionality -->
    <script>
        // Logout function
        function logout() {
            window.location.href = "../index.php?logoutAdmin=true";
        }
    </script>
</body>

</html>