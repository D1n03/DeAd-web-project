<?php

require '../Utils/BaseAPI.php';

class InmateAPI extends BaseAPI {

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->jwtValidation->validateAdminToken();
            $this->getAllInmates();
        } else {
            http_response_code(405); // Method Not Allowed
            exit();
        }
    }

    private function getAllInmates() {
        $stmt = $this->conn->prepare("SELECT inmate_id, first_name, last_name, sentence_start_date, sentence_category FROM inmates");
        $stmt->execute();
        $result = $stmt->get_result();

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $line = array(
                "inmate_name" => $row['first_name'] . " " . $row['last_name'],
                "sentence_start_date" => $row['sentence_start_date'],
                "sentence_category" => $row['sentence_category'],
                "inmate_id" => $row['inmate_id']
            );
            $response[] = $line;
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}

$inmateAPI = new InmateAPI();
$inmateAPI->handleRequest();
?>