<?php

require '../Utils/BaseAPI.php';

class NonAdminUsersAPI extends BaseAPI
{

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->jwtValidation->validateAdminToken();
            $this->getNonAdminUsers();
        } else {
            http_response_code(405); // Method Not Allowed
            exit();
        }
    }

    private function getNonAdminUsers()
    {
        $stmt = $this->conn->prepare("SELECT user_id, photo, first_name, last_name, email, `function` FROM users WHERE `function` = 'user'");
        $stmt->execute();
        $result = $stmt->get_result();

        $response = array();
        while ($row = $result->fetch_assoc()) {
            $photo = base64_encode($row['photo']);
            $line = array(
                "person_name" => $row['first_name'] . " " . $row['last_name'],
                "email" => $row['email'],
                "function" => $row['function'],
                "user_id" => $row['user_id'],
                "photo" => $photo
            );
            $response[] = $line;
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}

$nonAdminUsersAPI = new NonAdminUsersAPI();
$nonAdminUsersAPI->handleRequest();
