<?php

require '../Utils/BaseAPI.php';

class PasswordChangeAPI extends BaseAPI {

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        
        switch ($method) {
            case 'POST':
                $this->jwtValidation->validateAnyToken(); 
                $this->changePassword();
                break;
            default:
                http_response_code(405); // Method Not Allowed
                exit(json_encode(array("error" => "Only POST requests are allowed.")));
        }
    }

    private function changePassword() {
        // Check if email is provided and validate it
        if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400); // Bad Request
            exit(json_encode(array("error" => "Invalid email format.")));
        }

        $current_password = $_POST["current_password"];
        $new_password = $_POST["new_password"];
        $confirm_new_password = $_POST["confirm_new_password"];
        $email = $_POST['email'];

        // Validate current password
        try {
            $stmt = $this->conn->prepare("SELECT password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];

            if (!password_verify($current_password, $hashed_password)) {
                http_response_code(400); // Bad Request
                exit(json_encode(array("error" => "Current password is incorrect.")));
            }

            if ($new_password !== $confirm_new_password) {
                http_response_code(400); // Bad Request
                exit(json_encode(array("error" => "New passwords do not match.")));
            }

            // Check password strength
            $passwordStrength = $this->checkPasswordStrength($new_password);
            if ($passwordStrength !== 1) {
                http_response_code(400); // Bad Request
                exit(json_encode(array("error" => "Password is not strong enough.")));
            }

            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

            $sql = "UPDATE users SET password = ? WHERE email = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $hashed_new_password, $email);
            $stmt->execute();

            http_response_code(200); // OK
            exit(json_encode(array("message" => "Password changed successfully.")));
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            exit(json_encode(array("error" => "Internal Server Error. Please try again later.")));
        }
    }

    private function checkPasswordStrength($password) {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);

        if (!$uppercase || !$number || strlen($password) < 8 || !$lowercase) {
            return 0;
        } else {
            return 1;
        }
    }
}

$passwordChangeAPI = new PasswordChangeAPI();
$passwordChangeAPI->handleRequest();
