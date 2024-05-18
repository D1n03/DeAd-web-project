<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTValidation {
    private $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function validateAdminToken() {
        $this->validateToken('admin');
    }

    public function validateUserToken() {
        $this->validateToken('user');
    }

    public function validateAnyToken() {
        $this->validateToken(null);
    }

    public function getUserId() {
        if (!isset($_COOKIE['auth_token'])) {
            return null;
        }

        $token = $_COOKIE['auth_token'];
        try {
            $decoded = JWT::decode($token, new Key($this->config['secret_key'], 'HS256'));
            return $decoded->user_id;
        } catch (Exception $e) {
            return null;
        }
    }

    private function validateToken($role) {
        if (!isset($_COOKIE['auth_token'])) {
            http_response_code(401); // Unauthorized
            exit();
        }

        $token = $_COOKIE['auth_token'];
        try {
            $decoded = JWT::decode($token, new Key($this->config['secret_key'], 'HS256'));
            // Check if user role matches the required role
            if ($role !== null && $decoded->role !== $role) {
                http_response_code(403); // Forbidden
                exit();
            }
        } catch (Exception $e) {
            http_response_code(401); // Unauthorized
            exit();
        }
    }
}
?>
