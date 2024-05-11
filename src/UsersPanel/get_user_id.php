<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // TODO TOKEN VALIDATION, MORE SECURITY

    $config = require '../../config.php';
    require '../Utils/Connection.php';
    $conn = Connection::getInstance()->getConnection();

    header('Content-Type: application/json');
    $user_id = $_GET['user_id'];

    $user_id = strval($user_id);
    $stmt = $conn->prepare("SELECT user_id, email, first_name, last_name, `function` FROM users WHERE user_id = ?");

    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    echo json_encode($row);
    exit();
}