<?php

$name = $_POST['name'];
$email = $_POST['email'];
$feedback = $_POST['feedback'];

require '../Utils/Connection.php';
$conn = Connection::getInstance()->getConnection();

if ($conn->connect_errno) {
    die('Could not connect to db: ' . $conn->connect_error);
} else {
    try {
        $stmt = $conn->prepare("INSERT INTO contact (name, email, feedback) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $feedback);
        $result = $stmt->execute();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    if ($result) {
        session_start();
        $_SESSION['email'] = $row['email'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['feedback'] = $row['feedback'];
        header('Location: contact.php');
    } else {
        header('Location: contact.php');
    }
}
