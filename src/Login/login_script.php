<?php

use Firebase\JWT\JWT;
require_once '../../vendor/autoload.php';
require '../Utils/Connection.php';

class AuthAPI {
    private $conn;
    private $config;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
        $this->config = require '../../config.php';
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->login();
        } else {
            $this->respondMethodNotAllowed();
        }
    }

    private function login() {
        $input = json_decode(file_get_contents("php://input"), true);
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';

        if ($this->conn->connect_errno) {
            $this->respondInternalError('Could not connect to database');
            return;
        }

        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row && password_verify($password, $row['password'])) {
                $this->createSession($row);
                $token = $this->createJWT($row);
                $this->setTokenCookie($token);
                $this->respondSuccess($row['function']);
            } else {
                $this->respondUnauthorized('Invalid email or password');
            }
        } catch (Exception $e) {
            $this->respondInternalError($e->getMessage());
        }
    }

    private function createSession($user) {
        session_start();
        $_SESSION['is_logged_in'] = true;
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['function'] = $user['function'];
        $_SESSION['id'] = $user['user_id'];
        $_SESSION['photo'] = $user['photo'];
    }

    private function createJWT($user) {
        $issuedAt = new DateTimeImmutable();
        $expire = $issuedAt->modify('+6 hours')->getTimestamp();
        $data = [
            'iat' => $issuedAt->getTimestamp(),
            'iss' => $this->config['hostname'],
            'nbf' => $issuedAt->getTimestamp(),
            'exp' => $expire,
            'role' => $user['function'] == 'admin' ? 'admin' : 'user',
            'user_id' => $user['user_id']
        ];

        return JWT::encode($data, $this->config['secret_key'], 'HS256');
    }

    private function setTokenCookie($token) {
        setcookie('auth_token', $token, [
            'expires' => time() + (6 * 60 * 60), // 6 hours
            'path' => '/',
            'domain' => '', // Set domain
            'secure' => true, // TO DO? SET FALE FOR HTTP AT DEPLOY?
            'httponly' => true, // Prevent JavaScript access to the cookie
            'samesite' => 'Strict' // Adjust based on your CSRF protection strategy
        ]);
    }

    private function respondSuccess($role) {
        header('Content-Type: application/json');
        echo json_encode(['role' => $role]);
    }

    private function respondUnauthorized($message) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => $message]);
    }

    private function respondInternalError($message) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => $message]);
    }

    private function respondMethodNotAllowed() {
        http_response_code(405);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Method Not Allowed']);
    }
}

$authAPI = new AuthAPI();
$authAPI->handleRequest();

?>
