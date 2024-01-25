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
    <title>View Quizzes - Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/admin-style.css"/>
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
    <h1>View Quiz Questions</h1>
    <table class="quiz-table">
        <thead>
        <tr>
            <th>Question</th>
            <th>Options</th>
            <th>Correct Answer</th>
        </tr>
        </thead>
        <?php
        $sqlQuery = "SELECT * FROM quizzes";
        $resultSet = mysqli_query($dbConnect, $sqlQuery);
        $datas = mysqli_fetch_all($resultSet, MYSQLI_ASSOC);
        ?>
        <tbody>
        <?php
        if (!empty($datas) && count($datas) > 0) {
            foreach ($datas as $key => $data) { ?>

                <tr>
                    <td><?php echo $data['question']; ?></td>
                    <?php
                    $optionString = "";
                    $correctAnswer = "";
                    if (isset($data['options']) && $data['options'] != "") {
                        $options = explode(',', $data['options']);
                        $keys = ['A', 'B', 'C', 'D'];
                        if (!empty($options) && count($options) > 0) {
                            foreach ($options as $key => $option) {
                                if ($optionString == "") {
                                    $optionString = "" . $keys[$key] . ") " . $option . "";
                                } else {
                                    $optionString = $optionString . ",  " . $keys[$key] . ") " . $option . "";
                                }
                            }

                            $correctAnswer = $keys[$data['correctAnswer']] . ") " . $options[$data['correctAnswer']];
                        }
                    }

                    ?>
                    <td><?php echo $optionString; ?></td>
                    <td><?php echo $correctAnswer; ?></td>
                </tr>
            <?php }
        } ?>
        </tbody>
    </table>
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
