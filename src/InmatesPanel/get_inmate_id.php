<?php

require '../../config.php';
require '../Utils/Connection.php';

class InmateAPI {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->getInmateDetails();
        } else {
            http_response_code(405); // Method Not Allowed
            exit();
        }
    }

    private function getInmateDetails() {
        // Token validation can be implemented here
        // Example: Check if a valid token is provided in the request headers

        if (!isset($_GET['inmate_id'])) {
            http_response_code(400); // Bad Request
            exit();
        }

        $inmateId = strval($_GET['inmate_id']);
        $stmt = $this->conn->prepare("SELECT inmate_id, first_name, last_name, sentence_start_date, sentence_duration, sentence_category FROM inmates WHERE inmate_id = ?");
        $stmt->bind_param("s", $inmateId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($row);
            exit();
        } else {
            http_response_code(404); // Not Found
            exit();
        }
    }
}

$inmateAPI = new InmateAPI();
$inmateAPI->handleRequest();

?>