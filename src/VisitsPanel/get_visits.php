<?php

require '../Utils/BaseAPI.php';

class VisitsAPI extends BaseAPI {

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->jwtValidation->validateAdminToken(); 
            $this->getVisits();
        } else {
            http_response_code(405); 
            exit();
        }
    }

    private function getVisits() {
        $stmt = $this->conn->prepare("SELECT visit_id, photo, first_name, last_name, date, visit_start, visit_end FROM visits");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $photo = base64_encode($row['photo']);
                $line = array(
                    "inmate_name" => $row['first_name'] . " " . $row['last_name'],
                    "date" => $row['date'],
                    "time_interval" => $row['visit_start'] . " " . $row['visit_end'],
                    "visit_id" => $row['visit_id'],
                    "photo" => $photo
                );
                $response[] = $line;
            }
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        } else {
            http_response_code(404); 
            exit();
        }
    }
}

$visitsAPI = new VisitsAPI();
$visitsAPI->handleRequest();

?>