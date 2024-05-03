<?php
// the all the users that are not admin from DB
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $config = require '../../config.php';
    require '../Utils/Connection.php';
    $conn = Connection::getInstance()->getConnection();

    $stmt = $conn->prepare("SELECT user_id, first_name, last_name, email, `function` FROM users WHERE `function` = 'user'");
    $stmt->execute();
    $result = $stmt->get_result();

    error_reporting(E_ERROR | E_PARSE);

    $response = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $line = array(
            "person_name" => $row['first_name'] . " " . $row['last_name'],
            "email" => $row['email'],
            "function" => $row['function'],
            "user_id" => $row['user_id']
        );
        $response[] = $line;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} else {

    http_response_code(405);
    exit();
}