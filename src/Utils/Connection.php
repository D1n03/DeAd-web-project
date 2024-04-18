<?php

class Connection
{
    private static ?Connection $instance = null;
    private mysqli $connection;

    private function __construct()
    {
        $config = require '../config.php';
        $this->connection = new mysqli($config['hostname'], $config['username'], $config['password'],  $config['database']);
        if($this->connection->connect_errno){
            die('Could not connect to db: ' . $this->connection->connect_error);
        }
    }

    public static function getInstance(): ?Connection
    {
        if (self::$instance == null) {
            self::$instance = new Connection();
        }
        return self::$instance;
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}