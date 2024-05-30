<?php
require '../Utils/Connection.php';
require __DIR__ . "../../mailer.php";

class ForgotPasswordAPI
{
    private $conn;
    private $mailer;

    public function __construct()
    {
        $this->conn = Connection::getInstance()->getConnection();
        if ($this->conn->connect_errno) {
            $this->sendResponse(500, 'Could not connect to the database.');
        }
        $this->mailer = require __DIR__ . "../../mailer.php";
    }

    public function handleRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->processResetRequest();
        } else {
            $this->sendResponse(405, 'Method Not Allowed.');
        }
    }

    private function processResetRequest()
    {
        $email = $_POST['email'] ?? '';

        // Check if email is provided and validate it
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendResponse(400, 'Invalid email format.');
        }

        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
        } catch (Exception $e) {
            $this->sendResponse(500, 'Database error: ' . $e->getMessage());
        }

        if ($result->num_rows == 0) {
            $this->sendResponse(404, 'Email not found.');
        } else {
            $this->generateResetToken($email);
        }
    }

    private function generateResetToken($email)
    {
        $token = bin2hex(random_bytes(16));
        $token_hash = hash("sha256", $token);
        $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

        $sql = "UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sss", $token_hash, $expiry, $email);
            $stmt->execute();
        } catch (Exception $e) {
            $this->sendResponse(500, 'Database error: ' . $e->getMessage());
        }

        $this->sendResetEmail($email, $token);
    }

    private function sendResetEmail($email, $token)
    {
        $this->mailer->setFrom("noreply@example.com");
        $this->mailer->addAddress($email);
        $this->mailer->Subject = "[DeAd] Password Reset";
        $this->mailer->Body = <<<END
        Click <a href="http://localhost/DeAd-web-project/src/ResetPassword/resetpassword.php?token=$token">here</a>
        to reset your DeAd's account password.
        END;

        try {
            $this->mailer->send();
            $this->sendResponse(200, 'Email sent!');
        } catch (Exception $e) {
            $this->sendResponse(500, 'Error: The email couldn\'t be sent.');
        }
    }

    private function sendResponse($statusCode, $message)
    {
        http_response_code($statusCode);
        echo json_encode(["message" => $message]);
        exit();
    }
}

$controller = new ForgotPasswordAPI();
$controller->handleRequest();
