<?php
// Get the email and password from the form
use Firebase\JWT\JWT;

require_once  '../vendor/autoload.php';


$email = $_POST['email'];
$password = $_POST['password'];

$config =  require '../config.php';
require 'Utils/Connection.php';
$conn = Connection::getInstance()->getConnection();

if ($conn->connect_errno) {
    die('Could not connect to db: ' . $conn->connect_error);
} else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // verify hash password
    $row = mysqli_fetch_assoc($result); // get the row from the result
    //check if the username has admin function

    if (password_verify($password, $row['password'])) {
        //set the session variables
        session_start();
        $_SESSION['is_logged_in'] = true;
        $_SESSION['email'] = $row['email'];
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];
        $_SESSION['function'] = $row['function'];
        $_SESSION['id'] = $row['user_id'];

        // create a new authentification token
        $key = $config['secret_key'];
        $issuedAt = new DateTimeImmutable();
        $role = "user";
        if ($row['function'] == 'admin') {
            $role = "admin";
        }
        $expire = $issuedAt->modify('+6 hours')->getTimestamp();
        $serverName = $config['hostname'];
        $data  = [
            'iat' => $issuedAt->getTimestamp(),         // time when the token was generated
            'iss' => $serverName,                       // issuer
            'nbf' => $issuedAt->getTimestamp(),         // not before
            'exp' => $expire,                           // expire
            'role' => $role,                            // user role
            'firstName' => $row['first_name'],          // first name
            'lastName' => $row['last_name']             // last name
        ];
        $token  =  JWT::encode(
            $data,
            $key,
            'HS256'
        );
        // set the token in the session
        $_SESSION['token'] = $token;
        // echo $token;
        if ($role == "user") {
            header("Location: visitormain.php");
        } else if ($role == "admin") {
            header("Location: /adminmain.php");
        }
    } else {
        // redirect to the login page with an error message
        header("Location: login.php?error=1&email=$email");
    }
}
