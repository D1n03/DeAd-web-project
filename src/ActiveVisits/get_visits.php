<?php
require '../../config.php';
require '../Utils/Connection.php';

class VisitAPI {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->getVisits();
        } else {
            http_response_code(405); // Method Not Allowed
            exit();
        }
    }

    private function getVisits() {
        if (!isset($_GET['id'])) {
            http_response_code(400); // Bad Request
            exit();
        }

        $user_id = strval($_GET['id']);
        $stmt = $this->conn->prepare("SELECT visit_id, first_name, last_name, date, visit_start, visit_end, photo FROM visits WHERE person_id = ? AND is_active = 1");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response = [];
            while ($row = $result->fetch_assoc()) {
                $photo = base64_encode($row['photo']);
                $visit = [
                    "inmate_name" => $row['first_name'] . " " . $row['last_name'],
                    "date" => $row['date'],
                    "time_interval" => $row['visit_start'] . " - " . $row['visit_end'],
                    "visit_id" => $row['visit_id'],
                    "photo" => $photo
                ];
                $response[] = $visit;
            }
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        } else {
            http_response_code(404); // Not Found
            exit();
        }
    }
}

$visitAPI = new VisitAPI();
$visitAPI->handleRequest();
?>