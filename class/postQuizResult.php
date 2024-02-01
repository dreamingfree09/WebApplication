<?php
// Start a PHP session to manage user sessions.
session_start();

// Include the file for database connection settings.
require 'db_connect.php';

// Create a new MySQLi database connection.
$dbConnect = new mysqli($servername, $username, $password, $dbname);

// Check if the database connection was successful.
if ($dbConnect->connect_errno) {
    echo "Failed to connect to MySQL: " . $dbConnect->connect_error;
    exit(); // Exit the script if the connection fails.
}

// Decode the JSON data received from the client and store it as an associative array.
$postData = json_decode(file_get_contents('php://input'), true);

// Extract values from the decoded JSON data.
$correctCount = $postData['correctCount'];
$wrongCount = $postData['wrongCount'];
$incompleteCount = $postData['incompleteCount'];
$userId = $_SESSION['userid']; // Get the user's ID from the session data.

// Check if the user already has a quiz result in the database.
$checkStmt = $dbConnect->prepare("SELECT * FROM user_quiz_result WHERE user_id = ?");
$checkStmt->bind_param("i", $userId);
$checkStmt->execute();

// Fetch the result as an associative array.
$result = $checkStmt->get_result()->fetch_assoc();

if (!empty($result)) {
    // Update existing quiz result if it exists.
    $updateStmt = $dbConnect->prepare("UPDATE user_quiz_result SET correct_answer = ?, wrong_answer = ?, incomplete_answer = ?, result_date = ? WHERE user_id = ?");
    $date = date('Y-m-d'); // Get the current date.
    $updateStmt->bind_param("iiisi", $correctCount, $wrongCount, $incompleteCount, $date, $userId);

    if ($updateStmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Data updated successfully'];
    } else {
        $response = ['status' => 'error', 'message' => 'Error updating data: ' . $updateStmt->error];
    }
    $updateStmt->close(); // Close the update statement.
} else {
    // Insert a new quiz result if it doesn't exist in the database.
    $insertStmt = $dbConnect->prepare("INSERT INTO user_quiz_result (user_id, correct_answer, wrong_answer, incomplete_answer, result_date) VALUES (?, ?, ?, ?, ?)");
    $date = date('Y-m-d'); // Get the current date.
    $insertStmt->bind_param("iiisi", $userId, $correctCount, $wrongCount, $incompleteCount, $date);

    if ($insertStmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Data saved successfully'];
    } else {
        $response = ['status' => 'error', 'message' => 'Error saving data: ' . $insertStmt->error];
    }
    $insertStmt->close(); // Close the insert statement.
}

$dbConnect->close(); // Close the database connection.
header('Content-Type: application/json'); // Set the response header to JSON.
echo json_encode($response); // Encode the response data as JSON and send it back to the client.
?>