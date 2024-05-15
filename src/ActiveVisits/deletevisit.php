<?php
require '../Utils/Connection.php';

class DeleteVisitAPI {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $this->deleteVisit();
        } else {
            http_response_code(405); // Method Not Allowed
            exit;
        }
    }

    private function deleteVisit() {
        // Validate input
        $visit_id = isset($_GET['visit_id']) ? $_GET['visit_id'] : null;
        if (!$visit_id) {
            http_response_code(400); // Bad Request
            exit;
        }

        // Check if visit_id exists in the database
        $stmt = $this->conn->prepare("SELECT visit_id FROM visits WHERE visit_id = ?");
        $stmt->bind_param("i", $visit_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            http_response_code(404); // Not Found
            exit;
        }

        // so the order of deletion is the most important thing here

        $stmt2 = $this->conn->prepare("DELETE FROM visits WHERE visit_id = ?");
        $stmt2->bind_param("i", $visit_id);
        $stmt2->execute();

        $stmt1 = $this->conn->prepare("DELETE FROM visit_info WHERE visit_refID = ?");
        $stmt1->bind_param("i", $visit_id);
        $stmt1->execute();

        // Check if deletion was successful
        if ($stmt1->affected_rows > 0 && $stmt2->affected_rows > 0) {
            http_response_code(200); // OK
            exit;
        } else {
            http_response_code(500); // Internal Server Error
            exit;
        }
    }
}

$deleteVisitAPI = new DeleteVisitAPI();
$deleteVisitAPI->handleRequest();
?>