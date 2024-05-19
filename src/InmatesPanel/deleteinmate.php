<?php

require '../Utils/BaseAPI.php';

class DeleteInmateAPI extends BaseAPI
{

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $this->jwtValidation->validateAdminToken();
            $this->deleteInmate();
        } else {
            http_response_code(405); // Method Not Allowed
            exit();
        }
    }

    private function deleteInmate()
    {
        $inmateId = isset($_GET['inmate_id']) ? $_GET['inmate_id'] : null;
        if (!$inmateId) {
            http_response_code(400);
            exit();
        }

        $stmt = $this->conn->prepare("SELECT inmate_id FROM inmates WHERE inmate_id = ?");
        $stmt->bind_param("s", $inmateId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            http_response_code(404);
            exit();
        }

        $stmt = $this->conn->prepare("DELETE FROM inmates WHERE inmate_id = ?");
        $stmt->bind_param("s", $inmateId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            http_response_code(200);
            exit();
        } else {
            http_response_code(500);
            exit();
        }
    }
}

$deleteInmateAPI = new DeleteInmateAPI();
$deleteInmateAPI->handleRequest();
