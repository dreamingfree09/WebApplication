<?php
session_start();
require 'db_connect.php';
// Create a database connection
$dbConnect = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($dbConnect->connect_errno) {
    echo "Failed to connect to MySQL: " . $dbConnect->connect_error;
    exit();
}
//Login the admin user and start the session
if (isset($_POST['loginButton'])) {
    if (isset($_POST['name']) && isset($_POST['password'])) {
        $name = $_POST['name'];
        $password = $_POST['password'];

        // Prepare and execute the statement
        $stmt = $dbConnect->prepare("SELECT * FROM users WHERE name = ? AND status = '1' AND type = 'admin'");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $resultSet = $stmt->get_result();

        if ($userDetails = $resultSet->fetch_assoc()) {
            // Check if password matches
            if (password_verify($password, $userDetails['password']) || $password === $userDetails['password']) {
                // If the password is in plaintext, rehash it and update in the database
                if ($password === $userDetails['password']) {
                    $newHashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $updateStmt = $dbConnect->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $updateStmt->bind_param("si", $newHashedPassword, $userDetails['id']);
                    $updateStmt->execute();
                    $updateStmt->close();
                }
                // Set session variables and redirect
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




// Create Quiz and also check if quiz Question already present in database
if (isset($_POST['createQuizButton'])) {
    $question = $_POST['question'];

    $stmt = $dbConnect->prepare("SELECT * FROM quizzes WHERE question = ?");
    $stmt->bind_param("s", $question);
    $stmt->execute();
    $resultSet = $stmt->get_result();
    $result = $resultSet->fetch_assoc();

    if (!empty($result)) {
        $error = "Question Already added in quizzes";
        $stmt->close();
    } else {
        $options = implode(',', [$_POST['optionA'], $_POST['optionB'], $_POST['optionC'], $_POST['optionD']]);
        $correctAnswer = $_POST['correctAnswer'];

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


// Get Quiz from database for update and also check if quiz Question present in database or not
if (isset($_GET['updateQuestionId'])) {

    $sqlQuery = "SELECT * FROM quizzes WHERE id=" . $_GET['updateQuestionId'] . "";
    $resultSet = mysqli_query($dbConnect, $sqlQuery);
    $result = mysqli_fetch_assoc($resultSet);
    if (empty($result)) {
        $error = "Question Not Found";
    }
}


// Update Quiz
if (isset($_POST['updateQuizButton'])) {
    $questionId = $_POST['updateQuestionId'];
    $question = $_POST['question'];
    $options = implode(',', [$_POST['optionA'], $_POST['optionB'], $_POST['optionC'], $_POST['optionD']]);
    $correctAnswer = $_POST['correctAnswer'];

    $updateStmt = $dbConnect->prepare("UPDATE quizzes SET question = ?, options = ?, correctAnswer = ? WHERE id = ?");
    $updateStmt->bind_param("ssii", $question, $options, $correctAnswer, $questionId);
    if ($updateStmt->execute()) {
        $error = "Updated Successfully";
    } else {
        $error = "Something went wrong";
    }
    $updateStmt->close();
}

//delete question
if (isset($_GET['deleteQuestionId'])) {
    $deleteQuestionId = $_GET['deleteQuestionId'];

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



// Create new user
if (isset($_POST['createNewUser'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $userType = $_POST['userType'];

    $checkStmt = $dbConnect->prepare("SELECT * FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result()->fetch_assoc();

    if (!empty($result)) {
        $error = "Email Already Exists.";
    } else {
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



// Get User and also check if user present in database or not
if (isset($_GET['updateUserId'])) {

    $sqlQuery = "SELECT * FROM users WHERE id=" . $_GET['updateUserId'] . "";
    $resultSet = mysqli_query($dbConnect, $sqlQuery);
    $result = mysqli_fetch_assoc($resultSet);
    if (empty($result)) {
        $error = "User Not Found";
    }
}


// Update User and also check if User is present in database or not
if (isset($_POST['updateNewUser'])) {
    $userId = $_POST['updateUserId'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $userType = $_POST['userType'];

    $checkStmt = $dbConnect->prepare("SELECT * FROM users WHERE email = ? AND id != ?");
    $checkStmt->bind_param("si", $email, $userId);
    $checkStmt->execute();
    $result = $checkStmt->get_result()->fetch_assoc();

    if (!empty($result)) {
        $error = "User email Already Exist";
    } else {
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



// Delete user from database
if (isset($_GET['deleteUserId'])) {
    $deleteUserId = $_GET['deleteUserId'];

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



// Register User and also login as well automatically
if (isset($_POST['registerUser'])) {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $insertStmt = $dbConnect->prepare("INSERT INTO users (name, email, password, type, status) VALUES (?, ?, ?, 'user', '1')");
    $insertStmt->bind_param("sss", $name, $email, $password);
    if ($insertStmt->execute()) {
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


// Login User and set the Authentication Session for Login
if (isset($_POST['loginUserButton'])) {
    if (isset($_POST['name']) && isset($_POST['password'])) {
        $name = mysqli_real_escape_string($dbConnect, $_POST['name']);
        $password = $_POST['password'];
        $sqlQuery = "SELECT * FROM users WHERE name='" . $name . "' AND status = '1' AND type = 'user'";
        $resultSet = mysqli_query($dbConnect, $sqlQuery);

        if ($userDetails = mysqli_fetch_assoc($resultSet)) {
            // Check if password matches the hashed password or the plaintext password
            if (password_verify($password, $userDetails['password']) || $password === $userDetails['password']) {
                // If the password is in plaintext, rehash it and update in the database
                if ($password === $userDetails['password']) {
                    $newHashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $updatePasswordQuery = "UPDATE users SET password = '" . $newHashedPassword . "' WHERE id = '" . $userDetails['id'] . "'";
                    mysqli_query($dbConnect, $updatePasswordQuery);
                }
                // Set session variables and redirect to the quiz page
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




//Delete User Quiz from database
if (isset($_GET['deleteResultId'])) {
    $deleteResultId = $_GET['deleteResultId'];

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

// Logout Admin
if (isset($_GET['logoutAdmin'])) {
    session_destroy();
    header("location: login.php");
}

// Logout User
if (isset($_GET['logoutUser'])) {
    session_destroy();
    header("location: login.php");
}


?>