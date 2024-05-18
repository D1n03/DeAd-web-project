<?php

require '../Utils/BaseAPI.php';

class UserUpdateAPI extends BaseAPI {
    private $validExtensionsPhoto = array('jpeg', 'jpg', 'png');
    private $errorMessages = [
        1 => "New passwords do not match.",
        2 => "Invalid file type for the photo!",
        "password_strength" => "Password must contain at least 8 characters, a number, uppercase and lowercase letters"
    ];

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'POST':
                $this->jwtValidation->validateAdminToken(); 
                $this->updateUser();
                break;
            default:
                http_response_code(405); // Method Not Allowed
                exit(json_encode(array("error" => "Only POST requests are allowed.")));
        }
    }

    private function updateUser() {

        // Get POST data
        $user_id = $_POST['user_id'] ?? null;
        $first_name = $_POST['first_name'] ?? null;
        $last_name = $_POST['last_name'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $password_confirm = $_POST['password_confirm'] ?? null;

        // Check if all required fields are provided
        if (!$user_id || !$first_name || !$last_name || !$email) {
            http_response_code(400); // Bad Request
            exit(json_encode(array("error" => "Missing required fields")));
        }

        // Check if user ID exists
        $stmt_check = $this->conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
        $stmt_check->bind_param("i", $user_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        if ($result_check->num_rows == 0) {
            http_response_code(404); // Not Found
            exit(json_encode(array("error" => "User ID does not exist")));
        }

        // Check password if provided
        if (!empty($password) && $password !== $password_confirm) {
            http_response_code(400); // Bad Request
            exit(json_encode(array("error" => $this->errorMessages[1])));
        } elseif (!empty($password)) {
            $passwordStrength = $this->checkPasswordStrength($password);
            if ($passwordStrength < 1) {
                http_response_code(400); // Bad Request
                exit(json_encode(array("error" => $this->errorMessages["password_strength"])));
            }
        } else {
            // If password is not provided, set it to null to prevent it from being updated
            $password = null;
        }

        /// Check if photo is provided
        if (isset($_FILES['new_photo']) && $_FILES['new_photo']['error'] === UPLOAD_ERR_OK) {
            // Process photo upload
            $photo_tmp_name = $_FILES['new_photo']['tmp_name'];
            $photo_name = $_FILES['new_photo']['name'];
            $photo_extension = strtolower(pathinfo($photo_name, PATHINFO_EXTENSION));

            // Validate photo extension
            if (!in_array($photo_extension, $this->validExtensionsPhoto)) {
                http_response_code(400); // Bad Request
                exit(json_encode(array("error" => $this->errorMessages[2])));
            }

            // Read photo content
            $photo = file_get_contents($photo_tmp_name);
        } else {
            // No photo provided
            $photo = null;
        }

        // Update user in the database
        $stmt = $this->conn->prepare("UPDATE users SET first_name=?, last_name=?, email=? WHERE user_id=?");
        $stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id);

        if ($stmt->execute()) {
            if($photo !== null) {
                $stmt2 = $this->conn->prepare("UPDATE users SET photo=? WHERE user_id=?");
                $ceva = null;
                $stmt2->bind_param("bi", $ceva, $user_id); 
                $stmt2->send_long_data(0, $photo); 
                $stmt2->execute(); 
                $stmt2->close();
            }

            if ($password !== null && $password_confirm !== null) {
                $hashed_new_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt3 = $this->conn->prepare("UPDATE users SET `password` = ? WHERE user_id = ?");
                $stmt3->bind_param("si", $hashed_new_password, $user_id);
                $stmt3->execute();
                $stmt3->close();
            }

            http_response_code(200); 
            exit(json_encode(array("message" => "User updated successfully")));
        } else {
            http_response_code(500); 
            exit(json_encode(array("error" => "Failed to update user")));
        }

        $stmt->close();
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

$userUpdateAPI = new UserUpdateAPI();
$userUpdateAPI->handleRequest();