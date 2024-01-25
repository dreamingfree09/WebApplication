<?php
session_start();
require 'db_connect.php';
$dbConnect = new mysqli($servername, $username, $password, $dbname);

if ($dbConnect->connect_errno) {
    echo "Failed to connect to MySQL: " . $dbConnect->connect_error;
    exit();
}


// Fetch  Quizes from databases and set the json format to pass on frontend
$sqlQuery = "SELECT * FROM quizzes";
$result = $dbConnect->query($sqlQuery);
if ($result) {
    $data = $result->fetch_all(MYSQLI_ASSOC);

    $result->free_result();

    $dbConnect->close();

    header('Content-Type: application/json');
    $records = [];
    foreach ($data as $key => $row) {
        $options = [];
        if (isset($row['options'])) {
            $options = explode(',', $row['options']); // convert from commas separated to array
        }
        $array = [
            "id" => $key + 1,
            "question" => $row['question'], //question
            "options" => $options, // all options
            "correctAnswer" => $options[$row['correctAnswer']] // correct  answer
        ];

        array_push($records, $array); // push data in records array of object
    }
    echo json_encode($records);
}


?>
