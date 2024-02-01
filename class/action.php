<?php
// Start a new session or resume the existing session
session_start();

// Include the database connection configuration file
require 'db_connect.php';

// Create a new database connection using mysqli
$dbConnect = new mysqli($servername, $username, $password, $dbname);

// Check if the database connection was successful
if ($dbConnect->connect_errno) {
    echo "Failed to connect to MySQL: " . $dbConnect->connect_error;
    exit();
}

// Admin Login Section
if (isset($_POST['loginButton'])) {
    if (isset($_POST['name']) && isset($_POST['password'])) {
        // Get the username and password from the form
        $name = $_POST['name'];
        $password = $_POST['password'];

        // Prepare and execute a statement to select the admin user
        $stmt = $dbConnect->prepare("SELECT * FROM users WHERE name = ? AND status = '1' AND type = 'admin'");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $resultSet = $stmt->get_result();

        if ($userDetails = $resultSet->fetch_assoc()) {
            // Check if the provided password matches the hashed password or plaintext password
            if (password_verify($password, $userDetails['password']) || $password === $userDetails['password']) {
                // If the password is in plaintext, rehash it and update it in the database
                if ($password === $userDetails['password']) {
                    $newHashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $updateStmt = $dbConnect->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $updateStmt->bind_param("si", $newHashedPassword, $userDetails['id']);
                    $updateStmt->execute();
                    $updateStmt->close();
                }
                // Set session variables for the admin user and redirect to the admin panel
                $_SESSION["userid"] = $userDetails['id'];
                $_SESSION["usertype"] = $userDetails['type'];
                $_SESSION["name"] = $userDetails['name'];
                $_SESSION["email"] = $userDetails['email'];
                header("location: ../admin");
                exit();
            } else {
                $error = "Login Failed";
            }
            $stmt->close();
        } else {
            $error = "User not found";
            $stmt->close();
        }
    } else {
        $error = "Name and password are required.";
    }
}

// Create Quiz Question Section
if (isset($_POST['createQuizButton'])) {
    $question = $_POST['question'];

    // Prepare and execute a statement to check if the question already exists in the database
    $stmt = $dbConnect->prepare("SELECT * FROM quizzes WHERE question = ?");
    $stmt->bind_param("s", $question);
    $stmt->execute();
    $resultSet = $stmt->get_result();
    $result = $resultSet->fetch_assoc();

    if (!empty($result)) {
        $error = "Question Already added in quizzes";
        $stmt->close();
    } else {
        // Prepare the options as a comma-separated string
        $options = implode(',', [$_POST['optionA'], $_POST['optionB'], $_POST['optionC'], $_POST['optionD']]);
        $correctAnswer = $_POST['correctAnswer'];

        // Prepare and execute a statement to insert the new question into the database
        $insertStmt = $dbConnect->prepare("INSERT INTO quizzes (question, options, correctAnswer) VALUES (?, ?, ?)");
        $insertStmt->bind_param("ssi", $question, $options, $correctAnswer);

        if ($insertStmt->execute()) {
            $error = "Saved Successfully";
        } else {
            $error = "Something went wrong";
        }
        $insertStmt->close();
    }
}

// Update Quiz Question Section
if (isset($_POST['updateQuizButton'])) {
    $questionId = $_POST['updateQuestionId'];
    $question = $_POST['question'];

    // Prepare the options as a comma-separated string
    $options = implode(',', [$_POST['optionA'], $_POST['optionB'], $_POST['optionC'], $_POST['optionD']]);
    $correctAnswer = $_POST['correctAnswer'];

    // Prepare and execute a statement to update the quiz question in the database
    $updateStmt = $dbConnect->prepare("UPDATE quizzes SET question = ?, options = ?, correctAnswer = ? WHERE id = ?");
    $updateStmt->bind_param("ssii", $question, $options, $correctAnswer, $questionId);

    if ($updateStmt->execute()) {
        $error = "Updated Successfully";
    } else {
        $error = "Something went wrong";
    }
    $updateStmt->close();
}

// Delete Quiz Question Section
if (isset($_GET['deleteQuestionId'])) {
    $deleteQuestionId = $_GET['deleteQuestionId'];

    // Prepare and execute a statement to delete the quiz question from the database
    $deleteStmt = $dbConnect->prepare("DELETE FROM quizzes WHERE id = ?");
    $deleteStmt->bind_param("i", $deleteQuestionId);

    if ($deleteStmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Question deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting record: " . $deleteStmt->error]);
    }
    $deleteStmt->close();
    exit();
}

// Create New User Section
if (isset($_POST['createNewUser'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $userType = $_POST['userType'];

    // Prepare and execute a statement to check if the user with the same email already exists
    $checkStmt = $dbConnect->prepare("SELECT * FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result()->fetch_assoc();

    if (!empty($result)) {
        $error = "Email Already Exists.";
    } else {
        // Prepare and execute a statement to insert the new user into the database
        $insertStmt = $dbConnect->prepare("INSERT INTO users (name, email, password, type) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param("ssss", $username, $email, $password, $userType);

        if ($insertStmt->execute()) {
            $error = "Saved User Successfully";
        } else {
            $error = "Something went wrong";
        }
        $insertStmt->close();
    }
    $checkStmt->close();
}

// Get User for Update Section
if (isset($_GET['updateUserId'])) {
    $sqlQuery = "SELECT * FROM users WHERE id=" . $_GET['updateUserId'] . "";
    $resultSet = mysqli_query($dbConnect, $sqlQuery);
    $result = mysqli_fetch_assoc($resultSet);

    if (empty($result)) {
        $error = "User Not Found";
    }
}

// Update User Section
if (isset($_POST['updateNewUser'])) {
    $userId = $_POST['updateUserId'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $userType = $_POST['userType'];

    // Prepare and execute a statement to check if the user's email already exists (excluding the current user)
    $checkStmt = $dbConnect->prepare("SELECT * FROM users WHERE email = ? AND id != ?");
    $checkStmt->bind_param("si", $email, $userId);
    $checkStmt->execute();
    $result = $checkStmt->get_result()->fetch_assoc();

    if (!empty($result)) {
        $error = "User email Already Exist";
    } else {
        // Construct the update query dynamically based on the presence of a new password
        $updateQuery = "UPDATE users SET name = ?, email = ?";
        $types = "ss"; // Types for bind_param
        $params = [&$username, &$email]; // Parameters to bind

        if ($password) {
            $updateQuery .= ", password = ?";
            $types .= "s";
            $params[] = &$password;
        }
        $updateQuery .= ", type = ? WHERE id = ?";
        $types .= "si";
        $params[] = &$userType;
        $params[] = &$userId;

        // Prepare and execute the update statement
        $updateStmt = $dbConnect->prepare($updateQuery);
        $updateStmt->bind_param($types, ...$params);

        if ($updateStmt->execute()) {
            $error = "Updated Successfully";
        } else {
            $error = "Something went wrong";
        }
        $updateStmt->close();
    }
    $checkStmt->close();
}

// Delete User Section
if (isset($_GET['deleteUserId'])) {
    $deleteUserId = $_GET['deleteUserId'];

    // Prepare and execute a statement to delete the user from the database
    $deleteStmt = $dbConnect->prepare("DELETE FROM users WHERE id = ?");
    $deleteStmt->bind_param("i", $deleteUserId);

    if ($deleteStmt->execute()) {
        $error = "Record deleted successfully";
        header("location: delete.php");
    } else {
        $error = "Error deleting record: " . $deleteStmt->error;
    }
    $deleteStmt->close();
}

// Register User Section
if (isset($_POST['registerUser'])) {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Prepare and execute a statement to insert a new user into the database
    $insertStmt = $dbConnect->prepare("INSERT INTO users (name, email, password, type, status) VALUES (?, ?, ?, 'user', '1')");
    $insertStmt->bind_param("sss", $name, $email, $password);

    if ($insertStmt->execute()) {
        // Login the newly registered user automatically and set session variables
        $loginStmt = $dbConnect->prepare("SELECT * FROM users WHERE email = ? AND password = ? AND status = '1' AND type = 'user'");
        $loginStmt->bind_param("ss", $email, $password);
        $loginStmt->execute();
        $userDetails = $loginStmt->get_result()->fetch_assoc();

        $_SESSION["userid"] = $userDetails['id'];
        $_SESSION["usertype"] = $userDetails['type'];
        $_SESSION["name"] = $userDetails['name'];
        $_SESSION["email"] = $userDetails['email'];
        header("location: quiz.php");
    } else {
        $error = "Something went wrong";
    }
    $insertStmt->close();
}

// User Login Section
if (isset($_POST['loginUserButton'])) {
    if (isset($_POST['name']) && isset($_POST['password'])) {
        // Get the username and password from the form
        $name = mysqli_real_escape_string($dbConnect, $_POST['name']);
        $password = $_POST['password'];

        // Prepare and execute a SQL query to select the user
        $sqlQuery = "SELECT * FROM users WHERE name='" . $name . "' AND status = '1' AND type = 'user'";
        $resultSet = mysqli_query($dbConnect, $sqlQuery);

        if ($userDetails = mysqli_fetch_assoc($resultSet)) {
            // Check if the provided password matches the hashed password or plaintext password
            if (password_verify($password, $userDetails['password']) || $password === $userDetails['password']) {
                // If the password is in plaintext, rehash it and update it in the database
                if ($password === $userDetails['password']) {
                    $newHashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $updatePasswordQuery = "UPDATE users SET password = '" . $newHashedPassword . "' WHERE id = '" . $userDetails['id'] . "'";
                    mysqli_query($dbConnect, $updatePasswordQuery);
                }
                // Set session variables for the user and redirect to the quiz page
                $_SESSION["userid"] = $userDetails['id'];
                $_SESSION["usertype"] = $userDetails['type'];
                $_SESSION["name"] = $userDetails['name'];
                $_SESSION["email"] = $userDetails['email'];
                header("location: quiz.php");
            } else {
                $error = "Login Failed";
            }
        } else {
            $error = "User not found";
        }
    } else {
        $error = "Name and password are required.";
    }
}

// Delete User Quiz Result Section
if (isset($_GET['deleteResultId'])) {
    $deleteResultId = $_GET['deleteResultId'];

    // Prepare and execute a statement to delete the user's quiz result from the database
    $deleteStmt = $dbConnect->prepare("DELETE FROM user_quiz_result WHERE id = ?");
    $deleteStmt->bind_param("i", $deleteResultId);

    if ($deleteStmt->execute()) {
        $error = "Record deleted successfully";
        header("location: view.php");
    } else {
        $error = "Error deleting record: " . $deleteStmt->error;
    }
    $deleteStmt->close();
}

// Logout Admin Section
if (isset($_GET['logoutAdmin'])) {
    session_destroy();
    header("location: login.php");
}

// Logout User Section
if (isset($_GET['logoutUser'])) {
    session_destroy();
    header("location: login.php");
}
?>