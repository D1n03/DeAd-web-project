<?php

require '../Utils/Connection.php';

class AddVisitAPI {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'POST':
                $this->addVisit();
                break;
            default:
                http_response_code(405);
                exit(json_encode(array("error" => "Only POST requests are allowed.")));
        }
    }

    private function addVisit() {
        session_start();
        // to do jwt
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            http_response_code(401);
            exit(json_encode(array("error" => "Unauthorized")));
        }

        $person_id = $_SESSION['id'];
        $first_name_inmate = $_POST['first_name'] ?? null;
        $last_name_inmate = $_POST['last_name'] ?? null;
        $relationship = $_POST['relationship'] ?? null;
        $visit_nature = $_POST['visit_nature'] ?? null;
        $source_of_income = $_POST['source_of_income'] ?? null;
        $date = $_POST['date'] ?? null;
        $visit_start = $_POST['visit_time_start'] ?? null;
        $visit_end = $_POST['visit_time_end'] ?? null;

        if (!$first_name_inmate || !$last_name_inmate || !$relationship || !$visit_nature || !$source_of_income || !$date || !$visit_start || !$visit_end) {
            http_response_code(400); 
            exit(json_encode(array("error" => "Missing required fields")));
        }

        $stmt = $this->conn->prepare("SELECT inmate_id FROM inmates WHERE first_name = ? AND last_name = ?");
        $stmt->bind_param("ss", $first_name_inmate, $last_name_inmate);
        $stmt->execute();
        $result = $stmt->get_result();
        $inmate = $result->fetch_assoc();
        $stmt->close();

        if (!$inmate) {
            http_response_code(400); 
            exit(json_encode(array("error" => "Invalid inmate name!")));
        }

        $inmate_id = $inmate['inmate_id'];

        if (!empty($_FILES['profile_photo']['name'])) {
            $photo_contents = file_get_contents($_FILES['profile_photo']['tmp_name']);
        } else {
            $photo_contents = null;
        }
        $valid_extensions_photo = array('jpeg', 'jpg', 'png');
        $ext = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $valid_extensions_photo)) {
            http_response_code(400); 
            exit(json_encode(array("error" => "Invalid file type!")));
        }

        $start_time = new DateTime($visit_start);
        $end_time = new DateTime($visit_end);
        $duration = $start_time->diff($end_time)->h;
        if ($duration > 3) {
            http_response_code(400); 
            exit(json_encode(array("error" => "Visit time is exceeding the maximum duration!")));
        }

        if ($start_time >= $end_time) {
            http_response_code(400); 
            exit(json_encode(array("error" => "Invalid start and end times!")));
        }

        $stmt = $this->conn->prepare("SELECT * FROM visits WHERE date = ? AND (
            (visit_start <= ? AND visit_end >= ?) OR
            (visit_start <= ? AND visit_end >= ?) OR
            (visit_start >= ? AND visit_end <= ?) OR
            (visit_start >= ? AND visit_start <= ?) OR
            (visit_end >= ? AND visit_end <= ?)
        ) AND first_name = ? AND last_name = ?");
            
        $stmt->bind_param("sssssssssssss", $date, $visit_start, $visit_start, $visit_end, $visit_end, $visit_start, $visit_end, $visit_start, $visit_end, $visit_start, $visit_end, $first_name_inmate, $last_name_inmate);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            http_response_code(400);
            exit(json_encode(array("error" => "The inmate already has a visit at that time!")));
        }

        $stmt = $this->conn->prepare("INSERT INTO visits (person_id, first_name, last_name, relationship, visit_nature, photo, source_of_income, date, visit_start, visit_end, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("isssssssss", $person_id, $first_name_inmate, $last_name_inmate, $relationship, $visit_nature, $photo_contents, $source_of_income, $date, $visit_start, $visit_end);
        $stmt->execute();
        $visit_id = $stmt->insert_id;
        $stmt->close();

        $stmt = $this->conn->prepare("INSERT INTO visits_info (visitor_id, inmate_id, visit_date, visit_nature, visit_refID) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iissi", $person_id, $inmate_id, $date, $visit_nature, $visit_id);
        $stmt->execute();
        $stmt->close();

        http_response_code(200); 
        exit(json_encode(array("message" => "Visit created successfully!")));
    }
}

$addVisitAPI = new AddVisitAPI();
$addVisitAPI->handleRequest();