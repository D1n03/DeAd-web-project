<?php

session_start(); // we need to start session in order to access it through CI
session_destroy();
setcookie('auth_token', '', time() - 3600, '/'); // Set expiration time in the past
header("Location: Index/index.php");
$_SESSION['is_logged_in'] = false;
$_SESSION['email'] = null;
$_SESSION['first_name'] = null;
$_SESSION['last_name'] = null;
$_SESSION['function'] = null;
$_SESSION['id'] = null;
$_SESSION['photo'] = null;
$_SESSION['role'] = null;
