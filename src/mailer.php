<?php
// setup for SMTP server to send the email to reset the password
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception; // more for debug

require __DIR__ . "../../vendor/autoload.php";

$mail = new PHPMailer(true);

// $mail->SMTPDebug = SMTP::DEBUG_SERVER; // debug mode if something breaks, lol

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "smtp.gmail.com";                         // gmail SMTP 
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // encrypton type
$mail->Port = 587;                                      // default port for STARTTLS encryption
$mail->Username = "bubble03red@gmail.com";              // email account 
$mail->Password = "PUT_PASS_HERE";                           // password for the SMTP

$mail->isHTML(true);

return $mail;
