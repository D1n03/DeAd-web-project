<?php

require '../Utils/BaseAPI.php';

class DeleteUserAPI extends BaseAPI
{

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $this->jwtValidation->validateAdminToken();
            $this->deleteUser();
        } else {
            http_response_code(405); // Method Not Allowed
            exit();
        }
    }

    private function deleteUser()
    {
        // Extract user_id from the URL
        $userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;

        if (!$userId) {
            http_response_code(400); // Bad Request
            exit();
        }
        // Check if user exists in the database
        $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            http_response_code(404); // Not Found
            exit();
        }

        // Delete user record
        $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
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

$deleteUserAPI = new DeleteUserAPI();
$deleteUserAPI->handleRequest();
