<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $config = require '../../config.php';
    require '../Utils/Connection.php';
    $conn = Connection::getInstance()->getConnection();
    $stmt = $conn->prepare("SELECT visit_id, first_name, last_name, date, visit_start, visit_end FROM visits");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $line = array(
                "inmate_name" => $row['first_name'] . " " . $row['last_name'],
                "date" => $row['date'],
                "time_interval" => $row['visit_start'] . " " . $row['visit_end'],
                "visit_id" => $row['visit_id']
            );
            $response[] = $line;
        }
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    } else {
        http_response_code(404);
        exit();
    }
} else {
    http_response_code(405);
    exit();
}
?>