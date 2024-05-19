<?php

require_once '../vendor/autoload.php';
require './Utils/Connection.php';
require './Utils/JWTValidation.php';

class LogoutAPI
{

    private $config;
    private $jwtValidation;

    public function __construct()
    {
        $this->config = require '../config.php';
        $this->jwtValidation = new JWTValidation($this->config);
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->jwtValidation->validateAnyToken();
            $this->logout();
        } else {
            $this->respondMethodNotAllowed();
        }
    }

    private function logout()
    {
        if (isset($_COOKIE['auth_token'])) {
            $this->destroySession();
            $this->clearTokenCookie();
            $this->respondSuccess('Logged out successfully');
        } else {
            $this->respondUnauthorized('No authentication token found');
        }
    }

    private function destroySession()
    {
        session_start();
        session_unset();
        session_destroy();
    }

    private function clearTokenCookie()
    {
        setcookie('auth_token', '', time() - 3600, '/', '', false, true);
    }

    private function respondSuccess($message)
    {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => $message]);
        exit();
    }

    private function respondUnauthorized($message)
    {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => $message]);
        exit();
    }

    private function respondMethodNotAllowed()
    {
        http_response_code(405);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Method Not Allowed']);
        exit();
    }
}

$logoutAPI = new LogoutAPI();
$logoutAPI->handleRequest();
