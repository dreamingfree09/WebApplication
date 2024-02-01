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

// Prepare a statement to fetch quiz data from the database.
$stmt = $dbConnect->prepare("SELECT * FROM quizzes");

// Execute the prepared statement.
$stmt->execute();

// Get the result set from the executed statement.
$resultSet = $stmt->get_result();

if ($resultSet) {
    // Fetch all rows from the result set as associative arrays.
    $data = $resultSet->fetch_all(MYSQLI_ASSOC);

    // Free the result set.
    $resultSet->free_result();

    // Close the database connection.
    $dbConnect->close();

    // Set the response content type to JSON.
    header('Content-Type: application/json');

    // Initialize an array to store the quiz records.
    $records = [];

    // Iterate through the fetched data to format it for JSON response.
    foreach ($data as $key => $row) {
        $options = [];

        // Check if 'options' key is set and convert comma-separated options to an array.
        if (isset($row['options'])) {
            $options = explode(',', $row['options']);
        }

        // Create an associative array with formatted quiz data.
        $array = [
            "id" => $key + 1, // Unique ID for the quiz.
            "question" => $row['question'], // Quiz question text.
            "options" => $options, // Array of all options.
            "correctAnswer" => $options[$row['correctAnswer']] // Correct answer from options array.
        ];

        // Push the formatted data into the records array of objects.
        array_push($records, $array);
    }

    // Encode the records array as JSON and echo it as the response.
    echo json_encode($records);
}
?>