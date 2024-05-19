<?php
// setup for SMTP server to send the email to reset the password
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception; // more for debug

require __DIR__ . "../../vendor/autoload.php";

use Dotenv\Dotenv as Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$mail = new PHPMailer(true);

// $mail->SMTPDebug = SMTP::DEBUG_SERVER; // debug mode if something breaks, lol

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = $_ENV['MAIL_HOST'];                       // gmail SMTP 
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // encrypton type
$mail->Port = $_ENV['MAIL_PORT'];                       // default port for STARTTLS encryption
$mail->Username = $_ENV['MAIL_USER'];                   // email account 
$mail->Password = $_ENV['MAIL_PASS'];                   // password for the SMTP

$mail->isHTML(true);

return $mail;