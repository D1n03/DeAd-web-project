<?php

session_start(); // we need to start session in order to access it through CI
session_destroy();
header("Location: Index/index.php");
$_SESSION['is_logged_in'] = false;
$_SESSION['email'] = null;
$_SESSION['first_name'] = null;
$_SESSION['last_name'] = null;
$_SESSION['function'] = null;
$_SESSION['id'] = null;
