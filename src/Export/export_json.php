<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once '../../vendor/autoload.php';
    require_once '../Utils/Connection.php';
    $config = require '../../config.php';

    session_start();
    // TO DO token validation

    $user_id = $_GET['user_id'];
    $conn = Connection::getInstance()->getConnection();
    $sql = "SELECT user_id, first_name, last_name, email, function FROM users where user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $export_data = array();
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $encode = json_encode($row);
    $export_data['user_data'] = $row;

    $sql = "SELECT * FROM visits WHERE person_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $visit = array();

    while ($row = $result->fetch_assoc()) {
        $first_name_inmate = $row['first_name'];
        $last_name_inmate = $row['last_name'];
        $sql = "SELECT first_name, last_name, sentence_start_date, sentence_duration, sentence_category FROM inamtes WHERE first_name = ? and last_name = ?";
    }
    
}
