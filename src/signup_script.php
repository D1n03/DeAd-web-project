<?php

// check if the password is strong
function check_password($password)
{
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);

    if (!$uppercase || !$number || strlen($password) < 8 || !$lowercase) {
        return 0;
    } else return 1;
}

// get the password and email from the form
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];

// check if the email contains special domain
$domains_for_admin = array('mapn.com', 'mai.com', 'gov.com');
if (in_array(substr($email, strpos($email, '@') + 1), $domains_for_admin)) {
    $function = 'admin';
} else {
    $function = 'user';
}

$passwordStrength = check_password($password);

if ($password != $password_confirm) {
    header("Location: signup.php?error=2&first_name=$first_name&last_name=$last_name&email=$email");
    exit();
} else if ($passwordStrength < 1) {
    header("Location: signup.php?strength=$passwordStrength&first_name=$first_name&last_name=$last_name&email=$email");
    exit();
}

require 'Utils/Connection.php';
$conn = Connection::getInstance()->getConnection();

if ($conn->connect_errno) {
    die('Could not connect to db: ' . $conn->connect_error);
} else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) > 0) {
        // set the error and redirect to the register page with the credentials
        header("Location: signup.php?error=1&first_name=$first_name&last_name=$last_name&email=$email");
        exit();
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, function) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $email, $passwordHash, $first_name, $last_name, $function);
        $result = $stmt->execute();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    if ($result) {
        //set the session variables
        session_start();
        $_SESSION['is_logged_in'] = true;
        $_SESSION['email'] = $row['email'];
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];
        $_SESSION['function'] = $row['function'];
        //$_SESSION['id'] = $user_id;
        header('Location: login.php');
    } else {
        //redirect to the register page
        header('Location: signup.php');
    }
}
