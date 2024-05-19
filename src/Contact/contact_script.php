<?php

require '../Utils/Connection.php';

class ContactAPI
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
            case 'POST':
                $this->createContact();
                break;
            default:
                http_response_code(405); // Method Not Allowed
                exit(json_encode(array("error" => "Only POST requests are allowed.")));
        }
    }

    private function createContact()
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $feedback = $_POST['feedback'] ?? '';

        // Validate input data
        if (empty($name) || empty($email) || empty($feedback)) {
            http_response_code(400); // Bad Request
            exit(json_encode(array("error" => "All fields are required.")));
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400); // Bad Request
            exit(json_encode(array("error" => "Invalid email format.")));
        }

        try {
            $stmt = $this->conn->prepare("INSERT INTO contact (name, email, feedback) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $feedback);
            $result = $stmt->execute();

            if ($result) {
                // Success
                http_response_code(201); // Created
                exit(json_encode(array("message" => "Contact created successfully.", "redirect" => "contact.php")));
            } else {
                // Failure
                http_response_code(500); // Internal Server Error
                exit(json_encode(array("error" => "Failed to create contact.")));
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            exit(json_encode(array("error" => $e->getMessage())));
        }
    }
}

$contactAPI = new ContactAPI();
$contactAPI->handleRequest();
