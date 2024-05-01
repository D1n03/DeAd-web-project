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

$token = $_POST["token"];
$token_hash = hash("sha256", $token);

require '../Utils/Connection.php';
$conn = Connection::getInstance()->getConnection();

if ($conn->connect_errno) {
    die('Could not connect to db: ' . $conn->connect_error);
} else {
    $sql = "SELECT * FROM users
        WHERE reset_token_hash = ?";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token_hash);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    // redirect to error page reason 1 => the token is not valid
    if ($user === null) {
        header("Location: ../Error/error.php?reason=1");
        exit();
    }
    // redirect to error page reason 2 => the token has expired
    if (strtotime($user["reset_token_expires_at"]) <= time()) {
        header("Location: ../Error/error.php?reason=2");
        exit();
    }
    // get the passwords
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    $passwordStrength = check_password($password);

    if ($password != $password_confirm) {
        header("Location: resetpassword.php?token=$token&error=1");
        exit();
    } else if ($passwordStrength < 1) {
        header("Location: resetpassword.php?token=$token&strength=$passwordStrength");
        exit();
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE users
            SET password = ?,
                reset_token_hash = NULL,
                reset_token_expires_at = NULL
            WHERE user_id = ?";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $passwordHash, $user["user_id"]);
        $stmt->execute();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    header('Location: ../Index/index.php');
}