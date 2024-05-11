<?php
session_start();

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: ../Login/login.php");
    exit;
}

$valid_extensions_photo = array('jpeg', 'jpg', 'png');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['email'])) {
    
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $old_email = $_SESSION["email"];

    if(isset($_FILES['photo']['tmp_name']) && !empty($_FILES['photo']['tmp_name'])) {
        $photo = file_get_contents($_FILES['photo']['tmp_name']); 
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $valid_extensions_photo)) {
            header("Location: editprofile.php?error=1");
            exit();
        }
    } else {
        $photo = null;
    }

    require '../Utils/Connection.php';
    $conn = Connection::getInstance()->getConnection();

    if ($conn->connect_errno) {
        header("Location: ../Error/error.php");
        exit;
    } else {
        try {
            if($photo !== null) {
                $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, photo = ? WHERE email = ?");
                $null = null; // placeholder for blob param
                $stmt->bind_param("sssbs", $first_name, $last_name, $email, $null, $old_email); // separate bind for the blob
                $stmt->send_long_data(3, $photo); // bind blob data
            } else {
                $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE email = ?");
                $stmt->bind_param("ssss", $first_name, $last_name, $email, $old_email);
            }
            $stmt->execute();

            // update session variables if necessary
            if ($_SESSION['email'] !== $email) {
                $_SESSION['email'] = $email;
            }
            if ($_SESSION['first_name'] !== $first_name) {
                $_SESSION['first_name'] = $first_name;
            }
            if ($_SESSION['last_name'] !== $last_name) {
                $_SESSION['last_name'] = $last_name;
            }
            if ($photo !== null) {
            $_SESSION['photo'] = $photo;
            }

            header("Location: editprofile.php?success=1");
            exit;
        } catch (Exception $e) {
            header("Location: ../Error/error.php");
            exit;
        }
    }
} else {
    header("Location: ../Error/error.php");
    exit;
}
?>
