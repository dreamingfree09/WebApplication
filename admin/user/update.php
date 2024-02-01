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
    <title>Update User - Admin Dashboard</title>
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
            <button class "dropdown-btn">USER</button>
            <div class="dropdown-content">
                <a href="create.php">Create User</a>
                <a href="view.php">View User</a>
                <a href="update.php">Update Users</a> <!-- Current page -->
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

        <?php
        // Fetch user data for updating from the database.
        $sqlQuery = "SELECT id, name, email, password, type FROM users";
        $resultSet = mysqli_query($dbConnect, $sqlQuery);
        $datas = mysqli_fetch_all($resultSet, MYSQLI_ASSOC);

        // Check if a user is selected for update and retrieve their data.
        if (isset($_GET['updateUserId'])) {
            $selectedUserId = $_GET['updateUserId'];
            $result = array();
            foreach ($datas as $data) {
                if ($data['id'] == $selectedUserId) {
                    $result = $data;
                    break;
                }
            }
        }
        ?>

        <h1>Update User</h1> <!-- Main content heading -->

        <h3>
            <?php echo isset($error) ? $error : ""; ?>
        </h3> <!-- Display error message if any -->

        <div class="select-question">
            <label for="selectUser">Select User:</label>
            <select id="selectUser" name="selectUser" onchange="getUserData();">
                <option value="">Select User</option>
                <?php
                if (!empty($datas) && count($datas) > 0) {
                    foreach ($datas as $key => $data) {
                        ?>
                        <option value="<?php echo $data['id']; ?>" <?php if (isset($_GET['updateUserId']) && $_GET['updateUserId'] == $data['id']) {
                               echo "selected";
                           } ?>><?php echo $data['name'] . "(" . $data['email'] . ")"; ?>
                        </option>
                    <?php }
                } ?>
            </select>
        </div>

        <form action="update.php" method="post" class="form-user">
            <input type="hidden" value="<?php echo isset($_GET['updateUserId']) ? $_GET['updateUserId'] : ""; ?>"
                name="updateUserId">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username"
                    value="<?php echo !empty($result) ? $result['name'] : ""; ?>" required />
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email"
                    value="<?php echo !empty($result) ? $result['email'] : ""; ?>" required />
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password"
                    value="<?php echo !empty($result) ? $result['password'] : ""; ?>" required />
            </div>
            <div class="form-group">
                <label for="userType">User Type</label>
                <select id="userType" name="userType">
                    <option value="">Select User Type</option>
                    <option value="admin" <?php echo !empty($result) && $result['type'] == "admin" ? "selected" : ""; ?>>
                        Admin
                    </option>
                    <option value="user" <?php echo !empty($result) && $result['type'] == "user" ? "selected" : ""; ?>>
                        User
                    </option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Update User" name="updateNewUser" />
            </div>
        </form>
    </main>

    <footer>
        <p>© <span id="currentYear"></span> Football Quiz</p>
    </footer>
    <script src="../../js/admin-script.js"></script>
    <script>
        function getUserData() {
            selectElement = document.querySelector('#selectUser');
            output = selectElement.value;
            window.location.href = "update.php?updateUserId=" + output + "";
        }
    </script>
    <script>
        function logout() {
            window.location.href = "../index.php?logoutAdmin=true";
        }
    </script>
</body>

</html>