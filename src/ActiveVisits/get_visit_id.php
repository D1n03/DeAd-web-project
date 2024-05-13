<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // TODO TOKEN VALIDATION, MORE SECURITY

    $config = require '../../config.php';
    require '../Utils/Connection.php';
    $conn = Connection::getInstance()->getConnection();

    header('Content-Type: application/json');
    $visit_id = $_GET['visit_id'];

    $visit_id = strval($visit_id);
    // $stmt = $conn->prepare("SELECT * FROM visits WHERE visit_id = ?");
    $stmt = $conn->prepare("SELECT visit_id, person_id, first_name, last_name, relationship, visit_nature, source_of_income, date, visit_start, visit_end FROM visits WHERE visit_id = ?");

    $stmt->bind_param("s", $visit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    echo json_encode($row);
    http_response_code(200);
    exit();
} else {
    http_response_code(405); // Bad Request
}
?>
