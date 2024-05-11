<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // TODO TOKEN VALIDATION, MORE SECURITY

    $config = require '../../config.php';
    require '../Utils/Connection.php';
    $conn = Connection::getInstance()->getConnection();

    header('Content-Type: application/json');
    $inmate_id = $_GET['inmate_id'];

    $inmate_id = strval($inmate_id);
    $stmt = $conn->prepare("SELECT inmate_id, first_name, last_name, sentence_start_date, sentence_duration, sentence_category FROM inmates WHERE inmate_id = ?");

    $stmt->bind_param("s", $inmate_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    echo json_encode($row);
    exit();
}