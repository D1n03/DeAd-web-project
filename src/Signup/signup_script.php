<?php
require '../Utils/Connection.php';

class UserRegistrationController {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
        if ($this->conn->connect_errno) {
            $this->sendResponse(500, 'Could not connect to the database.');
        }
    }

    public function handleRequest() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->registerUser();
        } else {
            $this->sendResponse(405, 'Method Not Allowed.');
        }
    }

    private function registerUser() {
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($password_confirm)) {
            $this->sendResponse(400, 'All fields are required.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendResponse(400, 'Invalid email format.');
        }

        if ($password !== $password_confirm) {
            $this->sendResponse(400, 'Passwords do not match.');
        }

        if (!$this->checkPasswordStrength($password)) {
            $this->sendResponse(400, 'Password must contain at least 8 characters, a number, and both uppercase and lowercase letters.');
        }

        $domains_for_admin = array('mapn.com', 'mai.com', 'gov.com');
        $domain = substr($email, strpos($email, '@') + 1);
        $function = in_array($domain, $domains_for_admin) ? 'admin' : 'user';

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $this->sendResponse(409, 'An account with this email already exists.');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (email, password, first_name, last_name, function) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $email, $passwordHash, $first_name, $last_name, $function);

        if ($stmt->execute()) {
            $this->sendResponse(201, 'Account created successfully!', true);
        } else {
            $this->sendResponse(500, 'Error: Failed to create account.');
        }
    }

    private function checkPasswordStrength($password) {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        return $uppercase && $lowercase && $number && strlen($password) >= 8;
    }

    private function sendResponse($statusCode, $message, $success = false) {
        http_response_code($statusCode);
        echo json_encode(["message" => $message, "success" => $success]);
        exit();
    }
}

$controller = new UserRegistrationController();
$controller->handleRequest();
?>
