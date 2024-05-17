<?php
require '../Utils/Connection.php';

class PasswordResetController {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
        if ($this->conn->connect_errno) {
            $this->sendResponse(500, 'Database connection error.');
        }
    }

    public function handleRequest() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->processPasswordReset();
        } else {
            $this->sendResponse(405, 'Method Not Allowed.');
        }
    }

    private function processPasswordReset() {
        $token = $_POST["token"] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        $token_hash = hash("sha256", $token);

        $sql = "SELECT * FROM users WHERE reset_token_hash = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $token_hash);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user === null) {
            $this->sendResponse(400, 'Invalid token.');
        }

        if (strtotime($user["reset_token_expires_at"]) <= time()) {
            $this->sendResponse(400, 'Token has expired.');
        }

        if ($password !== $password_confirm) {
            $this->sendResponse(400, 'Passwords do not match.');
        }

        if (!$this->checkPasswordStrength($password)) {
            $this->sendResponse(400, 'Password must contain at least 8 characters, a number, uppercase and lowercase letters.');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET password = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $passwordHash, $user["user_id"]);
        $stmt->execute();

        $this->sendResponse(200, 'Password reset successfully.');
    }

    private function checkPasswordStrength($password) {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);

        return $uppercase && $lowercase && $number && strlen($password) >= 8;
    }

    private function sendResponse($statusCode, $message) {
        http_response_code($statusCode);
        echo json_encode(["message" => $message]);
        exit();
    }
}

$controller = new PasswordResetController();
$controller->handleRequest();
?>
