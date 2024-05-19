<?php

// configuration file
// return an array of configuration settings

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv as Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = array(
    'hostname' => $_ENV['HOSTNAME'],
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
    'database' => $_ENV['DB_NAME'],
    'secret_key' => $_ENV['SECRET_KEY'],
);
return $config;
?>