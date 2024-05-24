<?php

require '../Utils/BaseAPI.php';

class GetInactiveVisitDetailsAPI extends BaseAPI
{

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->jwtValidation->validateUserToken();
            $this->getInactiveVisitDetails();
        } else {
            http_response_code(405); // Method Not Allowed
            exit;
        }
    }

    private function getInactiveVisitDetails()
{
    header('Content-Type: application/json');

    $visit_id = $_GET['visit_id'] ?? '';
    $visit_id = strval($visit_id);

    $stmt = $this->conn->prepare("SELECT v.visit_id, v.person_id, v.first_name, v.last_name, v.relationship, v.visit_nature, v.source_of_income, v.date, v.visit_start, v.visit_end, v.photo, vi.witnesses, vi.summary, vi.items_provided_to_inmate, vi.items_offered_by_inmate 
                                FROM visits v 
                                LEFT JOIN `visits_info` vi ON v.visit_id = vi.visit_info_id 
                                WHERE v.visit_id = ?");
    $stmt->bind_param("s", $visit_id);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $photo = base64_encode($row['photo']);
        $visit = [
            "inmate_name" => $row['first_name'] . " " . $row['last_name'],
            "date" => $row['date'],
            "time_interval" => $row['visit_start'] . " - " . $row['visit_end'],
            "visit_id" => $row['visit_id'],
            "relationship" => $row['relationship'],
            "visit_nature" => $row['visit_nature'],
            "source_of_income" => $row['source_of_income'],
            "witnesses" => $row['witnesses'],
            "summary" => $row['summary'],
            "items_offered_by_inmate" => $row['items_offered_by_inmate'],
            "items_provided_to_inmate" => $row['items_provided_to_inmate'],
            "photo" => $photo
        ];
        echo json_encode($visit);
        http_response_code(200); // OK
        exit;
    }
     else {
        http_response_code(404); // Not Found
        exit;
    }
}

}

$getInactiveVisitDetailsAPI = new GetInactiveVisitDetailsAPI();
$getInactiveVisitDetailsAPI->handleRequest();