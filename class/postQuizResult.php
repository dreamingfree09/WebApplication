<?php
session_start();
require 'db_connect.php';
$dbConnect = new mysqli($servername, $username, $password, $dbname);

if ($dbConnect->connect_errno) {
    echo "Failed to connect to MySQL: " . $dbConnect->connect_error;
    exit();
}

$postData = json_decode(file_get_contents('php://input'), true);

$correctCount = $postData['correctCount'];
$wrongCount = $postData['wrongCount'];
$incompleteCount = $postData['incompleteCount'];
$userId = $_SESSION['userid'];

// Check if the user already has a quiz result
$checkStmt = $dbConnect->prepare("SELECT * FROM user_quiz_result WHERE user_id = ?");
$checkStmt->bind_param("i", $userId);
$checkStmt->execute();
$result = $checkStmt->get_result()->fetch_assoc();

if (!empty($result)) {
    // Update existing quiz result
    $updateStmt = $dbConnect->prepare("UPDATE user_quiz_result SET correct_answer = ?, wrong_answer = ?, incomplete_answer = ?, result_date = ? WHERE user_id = ?");
    $date = date('Y-m-d');
    $updateStmt->bind_param("iiisi", $correctCount, $wrongCount, $incompleteCount, $date, $userId);

    if ($updateStmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Data updated successfully'];
    } else {
        $response = ['status' => 'error', 'message' => 'Error updating data: ' . $updateStmt->error];
    }
    $updateStmt->close();
} else {
    // Insert new quiz result
    $insertStmt = $dbConnect->prepare("INSERT INTO user_quiz_result (user_id, correct_answer, wrong_answer, incomplete_answer, result_date) VALUES (?, ?, ?, ?, ?)");
    $date = date('Y-m-d');
    $insertStmt->bind_param("iiisi", $userId, $correctCount, $wrongCount, $incompleteCount, $date);

    if ($insertStmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Data saved successfully'];
    } else {
        $response = ['status' => 'error', 'message' => 'Error saving data: ' . $insertStmt->error];
    }
    $insertStmt->close();
}

$dbConnect->close();
header('Content-Type: application/json');
echo json_encode($response);
?>