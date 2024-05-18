<?php

require '../Utils/BaseAPI.php';

class UpdateVisitAPI extends BaseAPI {

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'POST':
                $this->jwtValidation->validateAdminToken(); 
                $this->updateVisit();
                break;
            default:
                http_response_code(405); // Method Not Allowed
                exit(json_encode(array("error" => "Only POST requests are allowed.")));
        }
    }

    private function updateVisit() {
        // Get POST data
        $visit_id = $_POST['visit_id'] ?? null;
        $date = $_POST['date'] ?? null;
        $visit_time_start = $_POST['visit_time_start'] ?? null;
        $visit_time_end = $_POST['visit_time_end'] ?? null;
        $visit_nature = $_POST['visit_nature'] ?? null;
        $first_name_inmate = $_POST['inmate_first_name'] ?? null; 
        $last_name_inmate = $_POST['inmate_last_name'] ?? null;
    
        // Check if all required fields are provided
        if (!$visit_id || !$date || !$visit_time_start || !$visit_time_end || !$visit_nature || !$first_name_inmate || !$last_name_inmate) {
            http_response_code(400); // Bad Request
            exit(json_encode(array("error" => "Missing required fields")));
        }

        // Check if visit exists in the database
        $stmt = $this->conn->prepare("SELECT visit_id FROM visits WHERE visit_id = ?");
        $stmt->bind_param("s", $visit_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows == 0) {
            $error = array("error" => "Visit not found");
            http_response_code(404); // Not Found
            echo json_encode($error);
            exit();
        }
        // Check duration of visit
        $start_time = new DateTime($visit_time_start);
        $end_time = new DateTime($visit_time_end);
        $duration = $start_time->diff($end_time)->h;
    
        if ($duration > 3) {
            http_response_code(200); 
            exit(json_encode(array("error" => "Visit time is exceeding the maximum duration")));
        }
    
        // Check if start time is before end time
        if ($start_time >= $end_time) {
            http_response_code(200); 
            exit(json_encode(array("error" => "Invalid start and end times")));
        }
    
        // Check if the inmate has a visit in the same time interval
        $stmt3 = $this->conn->prepare("SELECT * FROM visits WHERE date = ? AND (
            (visit_start <= ? AND visit_end >= ?) OR
            (visit_start <= ? AND visit_end >= ?) OR
            (visit_start >= ? AND visit_end <= ?) OR
            (visit_start >= ? AND visit_start <= ?) OR
            (visit_end >= ? AND visit_end <= ?)
        ) AND first_name = ? AND last_name = ? AND visit_id <> ?");
            
        $stmt3->bind_param("sssssssssssssi", $date, $visit_time_start, $visit_time_start, $visit_time_end, $visit_time_end, $visit_time_start, $visit_time_end, $visit_time_start, $visit_time_end, $visit_time_start, $visit_time_end, $first_name_inmate, $last_name_inmate, $visit_id);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
        $stmt3->close();
    
        if ($result3->num_rows > 0) {
            http_response_code(200); 
            exit(json_encode(array("error" => "The inmate already has a visit at that time")));
        }
    
        try {
            // Update data in visits table
            $stmt = $this->conn->prepare("UPDATE visits SET visit_nature=?, date=?, visit_start=?, visit_end=? WHERE visit_id=?");
            $stmt->bind_param("ssssi", $visit_nature, $date, $visit_time_start, $visit_time_end, $visit_id);
            $stmt->execute();
    
            // Update data in visits_info table
            $stmt = $this->conn->prepare("UPDATE visits_info SET visit_nature=?, visit_date=? WHERE visit_refID=?");
            $stmt->bind_param("sss", $visit_nature, $date, $visit_id);
            $stmt->execute();
    
            http_response_code(200); // OK
            exit(json_encode(array("message" => "Visit updated successfully")));
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            exit(json_encode(array("error" => "Failed to update visit")));
        }
    }
}

$updateVisitAPI = new UpdateVisitAPI();
$updateVisitAPI->handleRequest();
?>