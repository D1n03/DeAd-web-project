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

session_start();
//if the user is not logged in, redirect to the login page
if (!isset($_SESSION['is_logged_in'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    $password = (!empty($_POST['password'])) ? $_POST['password'] : null;
    $password_confirm = (!empty($_POST['password_confirm'])) ? $_POST['password_confirm'] : null;

    $passwordStrength = check_password($password);

    if ($password !== null && $password_confirm !== null) {
        if ($password != $password_confirm) {
            header("Location: userEdit.php?user_id=$user_id&error=2");
            exit();
        } else if ($passwordStrength < 1) {
            header("Location: userEdit.php?user_id=$user_id&strength=$passwordStrength");
            exit();
        }
    }

    if(isset($_FILES['new_photo']['tmp_name']) && !empty($_FILES['new_photo']['tmp_name'])) {
        $photo = file_get_contents($_FILES['new_photo']['tmp_name']); 
    } else {
        $photo = null;
    }

    $config = require '../../config.php';
    require '../Utils/Connection.php';
    $conn = Connection::getInstance()->getConnection();

    if ($conn->connect_errno) {
        header("Location: ../Error/error.php");
        exit();
    } else {
        try {
            $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE user_id = ?");
            $stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id); // separate bind for the blob
            $stmt->execute();
            $stmt->close();

            if($photo !== null) {
                $stmt2 = $conn->prepare("UPDATE users SET photo = ? WHERE user_id = ?");
                $stmt2->bind_param("bi", $photo, $user_id);
                $stmt2->send_long_data(0, $photo);
                $stmt2->execute();
                $stmt2->close();
            } 

            if ($password !== null && $password_confirm !== null) {
                $hashed_new_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt3 = $conn->prepare("UPDATE users SET `password` = ? WHERE user_id = ?");
                $stmt3->bind_param("si", $hashed_new_password, $user_id); 
                $stmt3->execute();
                $stmt3->close();
            }
            header("Location: ./userspanel.php");
            exit();

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
} else {
    header("Location: ../Error/error.php");
    exit;
}
?>


