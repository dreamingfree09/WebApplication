<?php
session_start();
require 'db_connect.php';
$dbConnect = new mysqli($servername, $username, $password, $dbname);

if ($dbConnect->connect_errno) {
    echo "Failed to connect to MySQL: " . $dbConnect->connect_error;
    exit();
}

// Store the result in database that user have taken the quiz
// total correct answers, total wrong answers , incomplete answers
// also attach the user id for scoreboard purpose to show the username
$postData = json_decode(file_get_contents('php://input'), true);

$correctCount = $postData['correctCount'];
$wrongCount = $postData['wrongCount'];
$incompleteCount = $postData['incompleteCount'];
$userId = $_SESSION['userid'];

$sqlQuery = "SELECT * FROM user_quiz_result WHERE user_id=" . $userId . "";
$resultSet = mysqli_query($dbConnect, $sqlQuery);
$result = mysqli_fetch_assoc($resultSet);
// if current user already taken the quiz update the scoreboard bases on current result
// else insert the new record in user quiz result table
if (!empty($result)) {
    //
    $sql = "UPDATE user_quiz_result SET correct_answer = " . $correctCount . ", wrong_answer = " . $wrongCount . ", incomplete_answer = " . $incompleteCount . ", result_date = '" . date('Y-m-d') . "' WHERE  user_id=" . $userId . "";
    if ($dbConnect->query($sql) === TRUE) {
        $response = array('status' => 'success', 'message' => 'Data saved successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Error saving data: ' . $dbConnect->error);
    }
    $dbConnect->close();

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    $sql = "INSERT INTO user_quiz_result (user_id, correct_answer, wrong_answer, incomplete_answer, result_date) VALUES (" . $userId . ", " . $correctCount . ", " . $wrongCount . ", " . $incompleteCount . ", '" . date('Y-m-d') . "')";
    if ($dbConnect->query($sql) === TRUE) {
        $response = array('status' => 'success', 'message' => 'Data saved successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Error saving data: ' . $dbConnect->error);
    }
    $dbConnect->close();

    header('Content-Type: application/json');
    echo json_encode($response);

}


?>
