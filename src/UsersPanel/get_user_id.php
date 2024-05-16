<?php

require_once '../../config.php';
require_once '../Utils/Connection.php';

class GetUserAPI {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->getUser();
        } else {
            http_response_code(405); // Method Not Allowed
            exit();
        }
    }

    private function getUser() {
        if (!isset($_GET['user_id'])) {
            http_response_code(400); // Bad Request
            exit(json_encode(array("error" => "Missing user ID")));
        }

        $userId = $_GET['user_id'];
        $stmt = $this->conn->prepare("SELECT user_id, email, first_name, last_name, `function` FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            http_response_code(404); // Not Found
            exit(json_encode(array("error" => "User not found")));
        }

        $row = $result->fetch_assoc();
        http_response_code(200); // OK
        header('Content-Type: application/json');
        echo json_encode($row);
        exit();
    }
}

$userAPI = new GetUserAPI();
$userAPI->handleRequest();

?>