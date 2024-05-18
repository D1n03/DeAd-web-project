<?php

require '../Utils/BaseAPI.php';

class DeleteVisitAPI extends BaseAPI {

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $this->jwtValidation->validateUserToken(); 
            $this->deleteVisit();
        } else {
            http_response_code(405); // Method Not Allowed
            exit;
        }
    }

    private function deleteVisit() {
        $visit_id = isset($_GET['visit_id']) ? $_GET['visit_id'] : null;
    
        if (!$visit_id) {
            $error = array("error" => "Visit ID is missing");
            http_response_code(400);
            echo json_encode($error);
            exit();
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
    
        // Get visit_info_id using visit_refID
        $stmt = $this->conn->prepare("SELECT visit_info_id FROM visits_info WHERE visit_refID = ?");
        $stmt->bind_param("s", $visit_id);
        $stmt->execute();
        $visit_info_result = $stmt->get_result();
    
        if ($visit_info_result->num_rows == 0) {
            $error = array("error" => "Visit info not found");
            http_response_code(404); // Not Found
            echo json_encode($error);
            exit();
        }
    
        $visit_info_row = $visit_info_result->fetch_assoc();
        $visit_info_id = $visit_info_row['visit_info_id'];
    
        // Prepare and execute SQL queries for deletion
        $sql2 = "DELETE FROM visits WHERE visit_id = ?";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->bind_param("s", $visit_id);
        $result2 = $stmt2->execute();
    
        $sql = "DELETE FROM visits_info WHERE visit_info_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $visit_info_id);
        $result = $stmt->execute();
    
        if ($result && $result2) {
            http_response_code(200); 
            echo json_encode(array("message" => "Visit deleted successfully"));
            exit();
        } else {
            http_response_code(500); 
            echo json_encode(array("error" => "Error deleting visit"));
            exit();
        }
    }
}

$deleteVisitAPI = new DeleteVisitAPI();
$deleteVisitAPI->handleRequest();
?>