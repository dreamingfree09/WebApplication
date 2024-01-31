<?php
session_start();
require 'db_connect.php';
$dbConnect = new mysqli($servername, $username, $password, $dbname);

if ($dbConnect->connect_errno) {
    echo "Failed to connect to MySQL: " . $dbConnect->connect_error;
    exit();
}

// Prepare statement to fetch quizzes
$stmt = $dbConnect->prepare("SELECT * FROM quizzes");
$stmt->execute();
$resultSet = $stmt->get_result();

if ($resultSet) {
    $data = $resultSet->fetch_all(MYSQLI_ASSOC);
    $resultSet->free_result();
    $dbConnect->close();

    header('Content-Type: application/json');
    $records = [];
    foreach ($data as $key => $row) {
        $options = [];
        if (isset($row['options'])) {
            $options = explode(',', $row['options']); // convert from comma-separated to array
        }
        $array = [
            "id" => $key + 1,
            "question" => $row['question'], //question
            "options" => $options, // all options
            "correctAnswer" => $options[$row['correctAnswer']] // correct answer
        ];

        array_push($records, $array); // push data into records array of object
    }
    echo json_encode($records);
}
?>