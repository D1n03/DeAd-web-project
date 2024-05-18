<?php

require_once '../../vendor/autoload.php';
require '../Utils/Connection.php';
require '../Utils/JWTValidation.php';

class DeleteVisitAPI {
    private $conn;
    private $config;
    private $jwtValidation;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
        $this->config = require '../../config.php';
        $this->jwtValidation = new JWTValidation($this->config);
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $this->jwtValidation->validateAdminToken(); 
            $this->deleteVisit();
        } else {
            $this->respondMethodNotAllowed();
        }
    }

    private function deleteVisit() {
        header('Content-Type: application/json');

        // Validate visit_id parameter
        parse_str(file_get_contents("php://input"), $data);
        if (!isset($data['visit_id'])) {
            http_response_code(400); // Bad Request
            echo json_encode(array("error" => "Visit ID is required"));
            exit();
        }

        $visit_id = $data['visit_id'];

        // Prepare and execute SQL queries for deletion
        $sql2 = "DELETE FROM visits WHERE visit_id = ?";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->bind_param("s", $visit_id);
        $result2 = $stmt2->execute();

        $sql = "DELETE FROM visit_info WHERE visit_refID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $visit_id);
        $result = $stmt->execute();

        if ($result && $result2) {
            http_response_code(200); 
            echo json_encode(array("message" => "Visit deleted successfully"));
            exit();
        } else {
            http_response_code(500); 
            echo json_encode(array("error" => "Error deleting visit"));
            exit();
        }
    }

    private function respondUnauthorized($message) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => $message]);
        exit();
    }

    private function respondMethodNotAllowed() {
        http_response_code(405);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Method Not Allowed']);
        exit();
    }
}

$deleteVisitAPI = new DeleteVisitAPI();
$deleteVisitAPI->handleRequest();

?>
