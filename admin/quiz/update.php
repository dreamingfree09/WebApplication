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
    <title>Update Quiz - Admin Dashboard</title>
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
    <?php
    $sqlQuery = "SELECT id, question FROM quizzes";
    $resultSet = mysqli_query($dbConnect, $sqlQuery);
    $datas = mysqli_fetch_all($resultSet, MYSQLI_ASSOC);
    ?>
    <h1>Update Quiz Question</h1>
    <h3> <?php echo isset($error) ? $error : ""; ?></h3>
    <div class="select-question">
        <label for="selectQuestion">Select Question:</label>
        <select id="selectQuestion" name="selectQuestion" onchange="getQuestionData();">
            <option value="">Select Question</option>
            <?php
            if (!empty($datas) && count($datas) > 0) {
                foreach ($datas as $key => $data) {
                    ?>
                    <option value="<?php echo $data['id']; ?>" <?php if (isset($_GET['updateQuestionId']) && $_GET['updateQuestionId'] == $data['id']) {
                        echo "selected";
                    } ?>><?php echo $data['question']; ?></option>
                <?php }
            } ?>
            <!-- Other options will be dynamically populated -->
        </select>
    </div>
    <form action="update.php" method="post" class="form-quiz">
        <input type="hidden" value="<?php echo isset($_GET['updateQuestionId']) ? $_GET['updateQuestionId'] : ""; ?>"
               name="updateQuestionId">
        <div class="form-group">
            <label for="question">Question:</label>
            <input
                    type="text"
                    id="question"
                    name="question"
                    value="<?php echo !empty($result) ? $result['question'] : ""; ?>"
                    required
            />
        </div>
        <?php
        $options = [];
        if (!empty($result) && !empty($result['options']) && $result['options'] != "") {
            $options = explode(',', $result['options']);
        }
        ?>
        <div class="form-group">
            <label for="optionA">Option A:</label>
            <input
                    type="text"
                    id="optionA"
                    name="optionA"
                    value="<?php echo !empty($options) && isset($options[0]) ? $options[0] : ""; ?>"
                    required
            />
        </div>
        <div class="form-group">
            <label for="optionB">Option B:</label>
            <input
                    type="text"
                    id="optionB"
                    name="optionB"
                    value="<?php echo !empty($options) && isset($options[1]) ? $options[1] : ""; ?>"
                    required
            />
        </div>
        <div class="form-group">
            <label for="optionC">Option C:</label>
            <input
                    type="text"
                    id="optionC"
                    name="optionC"
                    value="<?php echo !empty($options) && isset($options[2]) ? $options[2] : ""; ?>"
                    required
            />
        </div>
        <div class="form-group">
            <label for="optionD">Option D:</label>
            <input
                    type="text"
                    id="optionD"
                    name="optionD"
                    value="<?php echo !empty($options) && isset($options[3]) ? $options[3] : ""; ?>"
                    required
            />
        </div>
        <div class="form-group">
            <label for="correctAnswer">Correct Answer:</label>
            <select id="correctAnswer" name="correctAnswer" required>
                <option value="0" <?php echo !empty($result) && $result['correctAnswer'] == "0" ? "selected" : ""; ?>>
                    A
                </option>
                <option value="1" <?php echo !empty($result) && $result['correctAnswer'] == "1" ? "selected" : ""; ?>>
                    B
                </option>
                <option value="2" <?php echo !empty($result) && $result['correctAnswer'] == "2" ? "selected" : ""; ?>>
                    C
                </option>
                <option value="3" <?php echo !empty($result) && $result['correctAnswer'] == "3" ? "selected" : ""; ?>>
                    D
                </option>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" value="Update" name="updateQuizButton"/>
        </div>
    </form>
</main>

<footer>
    <p>© <span id="currentYear"></span> Football Quiz</p>
</footer>
<script src="../../js/admin-script.js"></script>
<script>
    function getQuestionData() {
        selectElement = document.querySelector('#selectQuestion');
        output = selectElement.value;
        window.location.href = "update.php?updateQuestionId=" + output + "";
    }
</script>
<script>
    function logout() {
        window.location.href = "../index.php?logoutAdmin=true";
    }
</script>
</body>
</html>
