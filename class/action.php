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


// Login Admin User and Set the Session for Authentication
if (isset($_POST['loginButton'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sqlQuery = "SELECT * FROM users WHERE email='" . $email . "' AND password='" . $password . "' AND status = '1' AND type = 'admin'";
    $resultSet = mysqli_query($dbConnect, $sqlQuery);
    $isValidLogin = mysqli_num_rows($resultSet);
    if ($isValidLogin) {
        $userDetails = mysqli_fetch_assoc($resultSet);
        $_SESSION["userid"] = $userDetails['id'];
        $_SESSION["usertype"] = $userDetails['type'];
        $_SESSION["name"] = $userDetails['name'];
        $_SESSION["email"] = $userDetails['email'];
        header("location: ../admin");
    } else {
        $error = "Login Failed";
    }

}

// Create Quiz and also check if quiz Question already present in database
if (isset($_POST['createQuizButton'])) {
    $question = $_POST['question'];
    $optionA = $_POST['optionA'];
    $optionB = $_POST['optionB'];
    $optionC = $_POST['optionC'];
    $optionD = $_POST['optionD'];
    $correctAnswer = $_POST['correctAnswer'];

    $sqlQuery = "SELECT * FROM quizzes WHERE question='" . $question . "'";
    $resultSet = mysqli_query($dbConnect, $sqlQuery);
    $result = mysqli_fetch_assoc($resultSet);
    if (!empty($result)) {
        $error = "Question Already add in quizzes";
    } else {
        $options = $optionA . ',' . $optionB . ',' . $optionC . ',' . $optionD;
        $insertQuery = "INSERT INTO quizzes (question, options, correctAnswer) 
                        VALUES ('" . $_POST["question"] . "', '" . $options . "', '" . $_POST["correctAnswer"] . "')";
        $quizSaved = mysqli_query($dbConnect, $insertQuery);
        if ($quizSaved) {
            $error = "Saved Successfully";
        } else {
            $error = "something went wrong";
        }

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
    $optionA = $_POST['optionA'];
    $optionB = $_POST['optionB'];
    $optionC = $_POST['optionC'];
    $optionD = $_POST['optionD'];
    $correctAnswer = $_POST['correctAnswer'];

    $sqlQuery = "SELECT * FROM quizzes WHERE question='" . $question . "' and id != " . $questionId . "";
    $resultSet = mysqli_query($dbConnect, $sqlQuery);
    $result = mysqli_fetch_assoc($resultSet);
    if (!empty($result)) {
        $error = "Question Already add in quizzes";
    } else {
        $options = $optionA . ',' . $optionB . ',' . $optionC . ',' . $optionD;

        $updateQuery = "UPDATE quizzes SET question = '" . $question . "', options = '" . $options . "', correctAnswer = '" . $correctAnswer . "' 
						WHERE id=" . $questionId . "";
        $isUpdated = mysqli_query($dbConnect, $updateQuery);
        if ($isUpdated) {
            $error = "Updated Successfully";
        } else {
            $error = "something went wrong";
        }
    }
}

// Delete quiz from database
if (isset($_GET['deleteQuestionId'])) {
    $sqlQuery = "DELETE FROM quizzes WHERE id=" . $_GET['deleteQuestionId'] . "";
    if (mysqli_query($dbConnect, $sqlQuery)) {
        $error = "Record deleted successfully";
        header("location: delete.php");
    } else {
        $error = "Error deleting record: " . mysqli_error($dbConnect);
    }
}

// Create new user
if (isset($_POST['createNewUser'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];

    $sqlQuery = "SELECT * FROM users WHERE email='" . $email . "'";
    $resultSet = mysqli_query($dbConnect, $sqlQuery);
    $result = mysqli_fetch_assoc($resultSet);
    if (!empty($result)) {
        $error = "Email Already Exist.";
    } else {
        $insertQuery = "INSERT INTO users (name, email, password, type) 
                        VALUES ('" . $username . "', '" . $email . "', '" . $password . "', '" . $userType . "')";
        $userSaved = mysqli_query($dbConnect, $insertQuery);
        if ($userSaved) {
            $error = "Saved User Successfully";
        } else {
            $error = "something went wrong";
        }
    }
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
    $password = $_POST['password'];
    $userType = $_POST['userType'];

    $sqlQuery = "SELECT * FROM users WHERE email='" . $email . "' and id != " . $userId . "";
    $resultSet = mysqli_query($dbConnect, $sqlQuery);
    $result = mysqli_fetch_assoc($resultSet);
    if (!empty($result)) {
        $error = "User email Already Exist";
    } else {

        $updateQuery = "UPDATE users SET name = '" . $username . "', email = '" . $email . "', password = '" . $password . "', type = '" . $userType . "' WHERE id=" . $userId . "";
        $isUpdated = mysqli_query($dbConnect, $updateQuery);
        if ($isUpdated) {
            $error = "Updated Successfully";
        } else {
            $error = "something went wrong";
        }
    }
}

// Delete user from database
if (isset($_GET['deleteUserId'])) {
    $sqlQuery = "DELETE FROM users WHERE id=" . $_GET['deleteUserId'] . "";
    if (mysqli_query($dbConnect, $sqlQuery)) {
        $error = "Record deleted successfully";
        header("location: delete.php");
    } else {
        $error = "Error deleting record: " . mysqli_error($dbConnect);
    }
}


// Register User and also login as well automatically
if (isset($_POST['registerUser'])) {

    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $insertQuery = "INSERT INTO users (name, email, password, type, status) 
                        VALUES ('" . $name . "', '" . $email . "', '" . $password . "', 'user', '1')";
    $quizSaved = mysqli_query($dbConnect, $insertQuery);
    if ($quizSaved) {
        $sqlQuery = "SELECT * FROM users WHERE email='" . $email . "' AND password='" . $password . "' AND status = '1' AND type = 'user'";
        $resultSet = mysqli_query($dbConnect, $sqlQuery);
        $userDetails = mysqli_fetch_assoc($resultSet);
        $_SESSION["userid"] = $userDetails['id'];
        $_SESSION["usertype"] = $userDetails['type'];
        $_SESSION["name"] = $userDetails['name'];
        $_SESSION["email"] = $userDetails['email'];
        header("location: quiz.php");
    } else {
        $error = "something went wrong";
    }
}

// Login User and set the Authentication Session for Login
if (isset($_POST['loginUserButton'])) {
    $email = $_POST['username'];
    $password = $_POST['password'];
    $sqlQuery = "SELECT * FROM users WHERE email='" . $email . "' AND password='" . $password . "' AND status = '1' AND type = 'user'";
    $resultSet = mysqli_query($dbConnect, $sqlQuery);
    $isValidLogin = mysqli_num_rows($resultSet);
    if ($isValidLogin) {
        $userDetails = mysqli_fetch_assoc($resultSet);
        $_SESSION["userid"] = $userDetails['id'];
        $_SESSION["usertype"] = $userDetails['type'];
        $_SESSION["name"] = $userDetails['name'];
        $_SESSION["email"] = $userDetails['email'];
        header("location: quiz.php");
    } else {
        $error = "Login Faild";
    }

}

//Delete User Quiz from database
if (isset($_GET['deleteResultId'])) {
    $sqlQuery = "DELETE FROM user_quiz_result WHERE id=" . $_GET['deleteResultId'] . "";
    if (mysqli_query($dbConnect, $sqlQuery)) {
        $error = "Record deleted successfully";
        header("location: view.php");
    } else {
        $error = "Error deleting record: " . mysqli_error($dbConnect);
    }
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
