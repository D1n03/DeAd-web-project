<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // TODO TOKEN VALIDATION, MORE SECURITY

    $config = require '../../config.php';
    require '../Utils/Connection.php';
    $conn = Connection::getInstance()->getConnection();

    header('Content-Type: application/json');
    $visit_id = $_GET['visit_id'];

    $visit_id = strval($visit_id);
    $stmt = $conn->prepare("SELECT v.visit_id, v.first_name AS inmate_first_name, v.last_name AS inmate_last_name, v.relationship, v.visit_nature, v.source_of_income, v.date, v.visit_start, v.visit_end,
                                u.first_name AS user_first_name, u.last_name AS user_last_name,
                                vi.items_provided_to_inmate, vi.items_offered_by_inmate, vi.health_status, vi.summary
                            FROM visits v
                            JOIN users u ON v.person_id = u.user_id
                            LEFT JOIN visits_info vi ON v.visit_id = vi.visit_refID
                            WHERE v.visit_id = ?");

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