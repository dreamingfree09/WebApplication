<?php
// Include the necessary PHP file for handling database operations and session management.
include('../../class/action.php');

// Check if a user is logged in and if they have admin privileges. Redirect to login page if not.
if (!isset($_SESSION['userid']) || $_SESSION['usertype'] != "admin") {
    header("location: ../login.php");
    exit; // Ensure no further script execution after redirection.
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Delete Quiz - Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/admin-style.css" />
</head>

<body>
    <div class="sidebar">
        <a href="../index.php" class="home-btn">Home</a>

        <div class="dropdown">
            <button class="dropdown-btn">QUIZ</button>
            <div class="dropdown-content">
                <a href="create.php">Create Quiz</a>
                <a href="view.php">View Quizzes</a>
                <a href="update.php">Update Quiz</a>
                <a href="delete.php">Delete Quiz</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropdown-btn">USER</button>
            <div class="dropdown-content">
                <a href="../user/create.php">Create User</a>
                <a href="../user/view.php">View User</a>
                <a href="../user/update.php">Update Users</a>
                <a href="../user/delete.php">Delete User</a>
            </div>
        </div>

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
        <h1>Delete Quiz Question</h1>
        <h3>
            <?php echo isset($error) ? $error : ""; ?>
        </h3>

        <?php
        // Fetch quiz questions from the database for selection in the dropdown.
        $sqlQuery = "SELECT id, question FROM quizzes";
        $resultSet = mysqli_query($dbConnect, $sqlQuery);
        $datas = mysqli_fetch_all($resultSet, MYSQLI_ASSOC);
        ?>
        <div class="delete-quiz">
            <label for="deleteQuestion">Select Question to Delete:</label>
            <select id="deleteQuestion" name="deleteQuestion">
                <option value="">Select Question</option>
                <?php foreach ($datas as $data) { ?>
                    <option value="<?php echo $data['id']; ?>">
                        <?php echo htmlspecialchars($data['question']); ?>
                    </option>
                <?php } ?>
            </select>
            <button type="button" onclick="deleteItem();">Delete</button>
        </div>
    </main>

    <footer>
        <p>© <span id="currentYear"></span> Football Quiz</p>
    </footer>

    <script src="../../js/admin-script.js"></script>
    <script>
        function deleteItem() {
            // Confirm deletion with the admin before proceeding.
            if (confirm("Are you sure you want to delete this question?")) {
                const selectedQuestionId = document.getElementById('deleteQuestion').value;
                // Validate selection.
                if (!selectedQuestionId) {
                    alert("Please select a question to delete.");
                    return;
                }
                // Perform the deletion request.
                fetch('delete.php?deleteQuestionId=' + selectedQuestionId, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Question deleted successfully');
                            window.location.reload(); // Reload the page to update the list.
                        } else {
                            alert('Error deleting question: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the question');
                    });
            }
        }

        function logout() {
            // Handle logout action.
            window.location.href = "../index.php?logoutAdmin=true";
        }
    </script>
</body>

</html>