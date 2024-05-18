<?php

require '../Utils/BaseAPI.php';

class AddInmateAPI extends BaseAPI {

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        
        switch ($method) {
            case 'POST':
                $this->jwtValidation->validateAdminToken(); 
                $this->addInmate();
                break;
            default:
                http_response_code(405); // Method Not Allowed
                exit(json_encode(array("error" => "Only POST requests are allowed.")));
        }
    }

    private function addInmate() {
        $user_id = $this->jwtValidation->getUserId();

        // Get POST data
        $first_name = $_POST['first_name'] ?? null;
        $last_name = $_POST['last_name'] ?? null;
        $sentence_start_date = $_POST['sentence_start_date'] ?? null;
        $sentence_duration = $_POST['sentence_duration'] ?? null;
        $sentence_category = $_POST['sentence_category'] ?? null;

        // Check if all required fields are provided
        if (!$first_name || !$last_name || !$sentence_start_date || !$sentence_duration || !$sentence_category) {
            http_response_code(400); // Bad Request
            exit(json_encode(array("error" => "Missing required fields")));
        }

        // Check if inmate with the same name already exists
        $stmt = $this->conn->prepare("SELECT * FROM inmates WHERE first_name = ? AND last_name = ?");
        $stmt->bind_param("ss", $first_name, $last_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            http_response_code(400); // Bad Request
            exit(json_encode(array("error" => "An inmate with the same name already exists")));
        }

        $stmt = $this->conn->prepare("INSERT INTO inmates (person_id, first_name, last_name, sentence_start_date, sentence_duration, sentence_category) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssis", $user_id, $first_name, $last_name, $sentence_start_date, $sentence_duration, $sentence_category);

        if ($stmt->execute()) {
            http_response_code(200); // OK
            exit(json_encode(array("message" => "Inmate added successfully")));
        } else {
            http_response_code(500); // Internal Server Error
            exit(json_encode(array("error" => "Failed to add inmate")));
        }
    }
}

$addInmateAPI = new AddInmateAPI();
$addInmateAPI->handleRequest();

?>