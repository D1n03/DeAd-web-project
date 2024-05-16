<?php

require '../Utils/Connection.php';

class VisitDetailsAPI {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->getVisitDetails();
        } else {
            http_response_code(405);
            exit();
        }
    }

    private function getVisitDetails() {
        header('Content-Type: application/json');
        
        // Validate visit_id parameter
        if (!isset($_GET['visit_id'])) {
            http_response_code(400);
            echo json_encode(array("error" => "Visit ID is required"));
            exit();
        }

        $visit_id = $_GET['visit_id'];

        $stmt = $this->conn->prepare("SELECT v.visit_id, v.first_name AS inmate_first_name, v.last_name AS inmate_last_name, v.relationship, v.visit_nature, v.source_of_income, v.date, v.visit_start, v.visit_end,
                                            u.first_name AS user_first_name, u.last_name AS user_last_name,
                                            vi.items_provided_to_inmate, vi.items_offered_by_inmate, vi.health_status, vi.summary
                                        FROM visits v
                                        JOIN users u ON v.person_id = u.user_id
                                        LEFT JOIN visits_info vi ON v.visit_id = vi.visit_refID
                                        WHERE v.visit_id = ?");
        $stmt->bind_param("s", $visit_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the visit exists
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode($row);
            http_response_code(200);
            exit();
        } else {
            http_response_code(404);
            echo json_encode(array("error" => "Visit not found"));
            exit();
        }
    }
}

$visitDetailsAPI = new VisitDetailsAPI();
$visitDetailsAPI->handleRequest();

?>