<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['id'])) {
        http_response_code(400); // Bad Request
        exit();
    }

    $config = require '../../config.php';
    require '../Utils/Connection.php';
    $conn = Connection::getInstance()->getConnection();

    $user_id = $_GET['id'];
    $user_id = strval($user_id);
    $stmt = $conn->prepare("SELECT visit_id, first_name, last_name, date, visit_start, visit_end, photo FROM visits WHERE person_id = ? and is_active = 1");

    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $photo = base64_encode($row['photo']);
            $line = array(
                "inmate_name" => $row['first_name'] . " " . $row['last_name'],
                "date" => $row['date'],
                "time_interval" => $row['visit_start'] . " - " . $row['visit_end'],
                "visit_id" => $row['visit_id'],
                "photo" => $photo
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