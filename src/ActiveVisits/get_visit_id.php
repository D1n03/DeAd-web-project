<?php

require '../Utils/BaseAPI.php';

class GetVisitDetailsAPI extends BaseAPI
{

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->jwtValidation->validateUserToken();
            $this->getVisitDetails();
        } else {
            http_response_code(405); // Method Not Allowed
            exit;
        }
    }

    private function getVisitDetails()
    {

        header('Content-Type: application/json');

        // Validate and sanitize input
        $visit_id = $_GET['visit_id'] ?? '';
        $visit_id = strval($visit_id);

        // Prepare and execute SQL query
        $stmt = $this->conn->prepare("SELECT visit_id, person_id, first_name, last_name, relationship, visit_nature, source_of_income, date, visit_start, visit_end FROM visits WHERE visit_id = ?");
        $stmt->bind_param("s", $visit_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if visit details exist
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode($row);
            http_response_code(200); // OK
            exit;
        } else {
            http_response_code(404); // Not Found
            exit;
        }
    }
}

$getVisitDetailsAPI = new GetVisitDetailsAPI();
$getVisitDetailsAPI->handleRequest();
