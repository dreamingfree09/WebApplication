<?php include('../../class/action.php'); ?>
<?php if (isset($_SESSION['userid']) && $_SESSION['usertype'] == "admin") {
} else {
    header("location: ../login.php");
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Read Scoreboard - Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/admin-style.css"/>
</head>
<body class="read-scoreboard">
<div class="sidebar">
    <a href="../index.php" class="home-btn">Home</a>

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
            <a href="view.php">View Scoreboard</a>
        </div>
    </div>

    <div class="dropdown">
        <button type="button" class="dropdown-btn" onclick="logout();">Logout</button>

    </div>

</div>
<main>
    <h1>Scoreboard List</h1>
    <table class="scoreboard-table">
        <thead>
        <tr>
            <th>User</th>
            <th>Correct Answer</th>
            <th>Wrong Answer</th>
            <th>Incomplete Answer</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sqlQuery = "SELECT res.id, us.name, res.correct_answer,res.wrong_answer,res.incomplete_answer,res.result_date  FROM `user_quiz_result` AS res INNER JOIN users AS us ON res.user_id = us.id";
        $resultSet = mysqli_query($dbConnect, $sqlQuery);
        $datas = mysqli_fetch_all($resultSet, MYSQLI_ASSOC);
        ?>
        <?php
        if (!empty($datas) && count($datas) > 0) {
            foreach ($datas as $key => $data) { ?>
                <tr>
                    <td><?php echo $data['name']; ?></td>
                    <td><?php echo $data['correct_answer']; ?></td>
                    <td><?php echo $data['wrong_answer']; ?></td>
                    <td><?php echo $data['incomplete_answer']; ?></td>
                    <td><?php echo $data['result_date']; ?></td>
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
    <p>© <span id="currentYear"></span> Football Quiz</p>
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
