<?php

require '../Utils/BaseAPI.php';

class VisitUpdateAPI extends BaseAPI {

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'POST':
                $this->jwtValidation->validateUserToken(); 
                $this->updateVisit();
                break;
            default:
                http_response_code(405); // Method Not Allowed
                echo json_encode(array("error" => "Only POST requests are allowed."));
                exit;
        }
    }

    private function updateVisit() {
        // Process POST data for updating visit information
        $user_id = $this->jwtValidation->getUserId();
        $items_offered_by_inmate = $_POST['itemsFrom'] ?? '';
        $items_provided_to_inmate = $_POST['itemsTo'] ?? '';
        $summary = $_POST['summary'] ?? '';
        $inmate_health = $_POST['inmate_health'] ?? '';
        $witnesses = $_POST['witnesses'] ?? '';
        $visit_id = $_POST['visit_id'] ?? '';

        try {
            // Check if the visit exists
            $stmt_check = $this->conn->prepare("SELECT visitor_id FROM visits_info WHERE visit_refID = ?");
            $stmt_check->bind_param("i", $visit_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            
            // If the visit does not exist, return an error
            if ($result_check->num_rows == 0) {
                http_response_code(404); // Not Found
                echo json_encode(array("error" => "Visit does not exist."));
                exit;
            }
            // Update visit information
            $stmt = $this->conn->prepare("UPDATE visits_info SET
                witnesses = ?,
                items_provided_to_inmate = ?,
                items_offered_by_inmate = ?,
                health_status = ?,
                summary = ?
                WHERE visitor_id = ? AND visit_refID = ?");

            $stmt->bind_param("sssssii",
                $witnesses,
                $items_provided_to_inmate,
                $items_offered_by_inmate,
                $inmate_health,
                $summary,
                $user_id,
                $visit_id);
            $stmt->execute();
            $stmt->close();

            // Update visit status
            $is_active = 0;
            $stmt2 = $this->conn->prepare("UPDATE visits SET is_active = ? WHERE visit_id = ?");
            $stmt2->bind_param("ii", $is_active, $visit_id);
            $stmt2->execute();
            $stmt2->close();

            // Return success response
            http_response_code(200); // OK
            echo json_encode(array("message" => "Visit updated successfully."));
            exit;
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(array("error" => "Internal Server Error. Please try again later."));
            exit;
        }
    }
}

// Create instance of API and handle the request
$visitUpdateAPI = new VisitUpdateAPI();
$visitUpdateAPI->handleRequest();
?>