<?php

require_once '../../vendor/autoload.php';
require 'Connection.php';
require 'JWTValidation.php';

class BaseAPI {
    protected $conn;
    protected $config;
    protected $jwtValidation;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
        $this->config = require '../../config.php';
        $this->jwtValidation = new JWTValidation($this->config);
    }
}
?>