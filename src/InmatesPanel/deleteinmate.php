<?php

require '../Utils/Connection.php';

class DeleteInmateAPI {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $this->deleteInmate();
        } else {
            http_response_code(405); // Method Not Allowed
            exit();
        }
    }

    private function deleteInmate() {
        // Validate input
        $inmateId = isset($_GET['inmate_id']) ? $_GET['inmate_id'] : null;
        if (!$inmateId) {
            http_response_code(400); // Bad Request
            exit();
        }

        // Check if inmate exists in the database
        $stmt = $this->conn->prepare("SELECT inmate_id FROM inmates WHERE inmate_id = ?");
        $stmt->bind_param("s", $inmateId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            http_response_code(404); // Not Found
            exit();
        }

        // Delete inmate record
        $stmt = $this->conn->prepare("DELETE FROM inmates WHERE inmate_id = ?");
        $stmt->bind_param("s", $inmateId);
        $stmt->execute();

        // Check if deletion was successful
        if ($stmt->affected_rows > 0) {
            http_response_code(200); // OK
            exit();
        } else {
            http_response_code(500); // Internal Server Error
            exit();
        }
    }
}

$deleteInmateAPI = new DeleteInmateAPI();
$deleteInmateAPI->handleRequest();

?>