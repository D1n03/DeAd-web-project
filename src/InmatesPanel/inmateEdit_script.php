<?php

require '../Utils/BaseAPI.php';

class UpdateInmateAPI extends BaseAPI
{

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'PUT':
                $this->jwtValidation->validateAdminToken();
                $this->updateInmate();
                break;
            default:
                http_response_code(405); // Method Not Allowed
                exit(json_encode(array("error" => "Only PUT requests are allowed.")));
        }
    }

    private function updateInmate()
    {
        // Get PUT data
        $put_data = file_get_contents("php://input");
        parse_str($put_data, $put_vars);

        $first_name = $put_vars['first_name'] ?? null;
        $last_name = $put_vars['last_name'] ?? null;
        $sentence_start_date = $put_vars['sentence_start_date'] ?? null;
        $sentence_duration = $put_vars['sentence_duration'] ?? null;
        $sentence_category = $put_vars['sentence_category'] ?? null;
        $inmate_id = $put_vars['inmate_id'] ?? null;

        // Check if all required fields are provided
        if (!$first_name || !$last_name || !$sentence_start_date || !$sentence_duration || !$sentence_category || !$inmate_id) {
            http_response_code(400); // Bad Request
            exit(json_encode(array("error" => "Missing required fields")));
        }

        // Check if the provided inmate ID exists
        if (!$this->inmateExists($inmate_id)) {
            http_response_code(404); // Not Found
            exit(json_encode(array("error" => "Inmate not found")));
        }

        $stmt = $this->conn->prepare("UPDATE inmates SET first_name=?, last_name=?, sentence_start_date=?, sentence_duration=?, sentence_category=? WHERE inmate_id=?");
        $stmt->bind_param("sssssi", $first_name, $last_name, $sentence_start_date, $sentence_duration, $sentence_category, $inmate_id);

        if ($stmt->execute()) {
            http_response_code(200); // OK
            exit(json_encode(array("message" => "Inmate updated successfully")));
        } else {
            http_response_code(500); // Internal Server Error
            exit(json_encode(array("error" => "Failed to update inmate")));
        }

        $stmt->close();
    }

    private function inmateExists($inmate_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM inmates WHERE inmate_id = ?");
        $stmt->bind_param("i", $inmate_id);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
}

$updateInmateAPI = new UpdateInmateAPI();
$updateInmateAPI->handleRequest();
