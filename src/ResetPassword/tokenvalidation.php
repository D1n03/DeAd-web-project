<?php

require '../Utils/Connection.php';

class TokenValidationAPI
{
    private $conn;

    public function __construct()
    {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                $this->validateToken();
                break;
            default:
                http_response_code(405); // Method Not Allowed
                exit(json_encode(array("error" => "Only GET requests are allowed.")));
        }
    }

    private function validateToken()
    {
        session_start();

        if (!isset($_GET['token'])) {
            http_response_code(400); // Bad Request
            exit(json_encode(array("error" => "Token is required.")));
        }

        $token = $_GET['token'];
        $token_hash = hash("sha256", $token);

        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE reset_token_hash = ?");
            $stmt->bind_param("s", $token_hash);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user === null) {
                http_response_code(400); // Bad Request
                exit(json_encode(array("error" => "Invalid token.")));
            }

            if (strtotime($user["reset_token_expires_at"]) <= time()) {
                http_response_code(400); // Bad Request
                exit(json_encode(array("error" => "Token expired.")));
            }

            http_response_code(200); // OK
            exit(json_encode(array("message" => "Token is valid.")));
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            exit(json_encode(array("error" => "Internal Server Error. Please try again later.")));
        }
    }
}

$tokenValidationAPI = new TokenValidationAPI();
$tokenValidationAPI->handleRequest();
