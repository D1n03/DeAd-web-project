<?php
session_start();

require '../Utils/Connection.php';

class VisitUpdateAPI {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'POST':
                $this->updateVisit();
                break;
            default:
                http_response_code(405); // Method Not Allowed
                echo json_encode(array("error" => "Only POST requests are allowed."));
                exit;
        }
    }

    private function updateVisit() {

        // TO DO, JWT TOKEN

        // Process POST data for updating visit information
        $user_id = $_SESSION['id'];
        $items_offered_by_inmate = $_POST['itemsFrom'] ?? '';
        $items_provided_to_inmate = $_POST['itemsTo'] ?? '';
        $summary = $_POST['summary'] ?? '';
        $inmate_health = $_POST['inmate_health'] ?? '';
        $witnesses = $_POST['witnesses'] ?? '';
        $visit_id = $_POST['visit_id'] ?? '';

        try {
            // Update visit information
            $stmt = $this->conn->prepare("UPDATE visits_info SET
                witnesses = ?,
                items_provided_to_inmate = ?,
                items_offered_by_inmate = ?,
                health_status = ?,
                summary = ?
                WHERE visitor_id = ? AND visit_refID = ?");

            $stmt->bind_param("sssssii",
                $witnesses,
                $items_provided_to_inmate,
                $items_offered_by_inmate,
                $inmate_health,
                $summary,
                $user_id,
                $visit_id);
            $stmt->execute();
            $stmt->close();

            // Update visit status
            $is_active = 0;
            $stmt2 = $this->conn->prepare("UPDATE visits SET is_active = ? WHERE visit_id = ?");
            $stmt2->bind_param("ii", $is_active, $visit_id);
            $stmt2->execute();
            $stmt2->close();

            // Return success response
            http_response_code(200); // OK
            echo json_encode(array("message" => "Visit updated successfully."));
            exit;
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(array("error" => "Internal Server Error. Please try again later."));
            exit;
        }
    }
}

// Create instance of API and handle the request
$visitUpdateAPI = new VisitUpdateAPI();
$visitUpdateAPI->handleRequest();
?>