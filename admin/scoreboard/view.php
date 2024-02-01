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
    <title>Read Scoreboard - Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/admin-style.css" /> <!-- Stylesheet link for admin dashboard styling -->
</head>

<body class="read-scoreboard">
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
                <a href="view.php">View Scoreboard</a> <!-- Current page -->
            </div>
        </div>

        <div class="dropdown">
            <button type="button" class="dropdown-btn" onclick="logout();">Logout</button> <!-- Logout functionality -->
        </div>

    </div>
    <main>
        <h1>Scoreboard List</h1> <!-- Main content heading -->
        <table class="scoreboard-table">
            <thead>
                <tr>
                    <th>User</th> <!-- Table header for user names -->
                    <th>Correct Answer</th> <!-- Table header for correct answers -->
                    <th>Wrong Answer</th> <!-- Table header for wrong answers -->
                    <th>Incomplete Answer</th> <!-- Table header for incomplete answers -->
                    <th>Date</th> <!-- Table header for the date of results -->
                    <th>Action</th> <!-- Table header for actions (e.g., delete) -->
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch scoreboard data from the database
                $sqlQuery = "SELECT res.id, us.name, res.correct_answer,res.wrong_answer,res.incomplete_answer,res.result_date  FROM `user_quiz_result` AS res INNER JOIN users AS us ON res.user_id = us.id";
                $resultSet = mysqli_query($dbConnect, $sqlQuery);
                $datas = mysqli_fetch_all($resultSet, MYSQLI_ASSOC);
                ?>
                <?php
                if (!empty($datas) && count($datas) > 0) {
                    foreach ($datas as $key => $data) { ?>
                        <tr>
                            <td>
                                <?php echo $data['name']; ?>
                            </td> <!-- Display user names -->
                            <td>
                                <?php echo $data['correct_answer']; ?>
                            </td> <!-- Display correct answers -->
                            <td>
                                <?php echo $data['wrong_answer']; ?>
                            </td> <!-- Display wrong answers -->
                            <td>
                                <?php echo $data['incomplete_answer']; ?>
                            </td> <!-- Display incomplete answers -->
                            <td>
                                <?php echo $data['result_date']; ?>
                            </td> <!-- Display result dates -->
                            <td class="delete-quiz">
                                <button type="button" onclick="deleteItem('<?php echo $data['id']; ?>');">Delete</button>
                            </td>
                        </tr>
                    <?php }
                } ?>
                <!-- Add more rows for other users -->
            </tbody>
        </table>

        <div class="scoreboard-pagination">
            <!-- Generate pagination links dynamically based on the number of scoreboard entries -->
        </div>
    </main>

    <footer>
        <p>© <span id="currentYear"></span> Football Quiz</p> <!-- Dynamic year in footer -->
    </footer>
    <script src="../../js/admin-script.js"></script>
    <script>
        function deleteItem(id) {
            if (confirm("Are you sure you want to delete this row")) {
                window.location.href = "view.php?deleteResultId=" + id + "";
            }
        }
    </script>
    <script>
        function logout() {
            window.location.href = "../index.php?logoutAdmin=true";
        }
    </script>
</body>

</html>