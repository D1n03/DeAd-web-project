<?php

require '../Utils/BaseAPI.php';

class ProfileController extends BaseAPI {

    public function handleRequest() {
        session_start();
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'POST':
                $this->jwtValidation->validateAnyToken(); 
                $this->updateProfile();
                break;
            default:
                $this->sendResponse(405, 'Method Not Allowed');
        }
    }

    private function updateProfile() {
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            $this->sendResponse(401, 'Unauthorized');
        }

        if (!isset($_SESSION['email'])) {
            $this->sendResponse(400, 'Bad Request: Session email not found');
        }

        $first_name = $_POST["first_name"] ?? null;
        $last_name = $_POST["last_name"] ?? null;
        $email = $_POST["email"] ?? null;
        $old_email = $_SESSION["email"];

        if (!$first_name || !$last_name || !$email) {
            $this->sendResponse(400, 'All fields are required.');
        }

        $photo = null;
        $valid_extensions_photo = array('jpeg', 'jpg', 'png');
        
        if (isset($_FILES['photo']['tmp_name']) && !empty($_FILES['photo']['tmp_name'])) {
            $photo = file_get_contents($_FILES['photo']['tmp_name']); 
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $valid_extensions_photo)) {
                $this->sendResponse(400, 'Invalid file type for the photo.');
            }
        }

        if ($this->conn->connect_errno) {
            $this->sendResponse(500, 'Database connection error.');
        } else {
            try {
                if ($photo !== null) {
                    $stmt = $this->conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, photo = ? WHERE email = ?");
                    $null = null; 
                    $stmt->bind_param("sssbs", $first_name, $last_name, $email, $null, $old_email);
                    $stmt->send_long_data(3, $photo);
                } else {
                    $stmt = $this->conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE email = ?");
                    $stmt->bind_param("ssss", $first_name, $last_name, $email, $old_email);
                }
                $stmt->execute();

                // what about updating the current session???
                if ($_SESSION['email'] !== $email) {
                    $_SESSION['email'] = $email;
                }
                if ($_SESSION['first_name'] !== $first_name) {
                    $_SESSION['first_name'] = $first_name;
                }
                if ($_SESSION['last_name'] !== $last_name) {
                    $_SESSION['last_name'] = $last_name;
                }
                if ($photo !== null) {
                    $_SESSION['photo'] = $photo;
                }

                $this->sendResponse(200, 'Profile updated successfully.');
            } catch (Exception $e) {
                $this->sendResponse(500, 'Database error: ' . $e->getMessage());
            }
        }
    }

    private function sendResponse($statusCode, $message) {
        http_response_code($statusCode);
        echo json_encode(["message" => $message]);
        exit();
    }
}

$controller = new ProfileController();
$controller->handleRequest();
?>