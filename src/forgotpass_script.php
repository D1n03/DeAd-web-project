<?php

$email = $_POST["email"];

require 'Utils/Connection.php';
$conn = Connection::getInstance()->getConnection();

if ($conn->connect_errno) {
    die('Could not connect to db: ' . $conn->connect_error);
} else {
    // check if the email provided exists in the DB
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    // error type 1 - the email doesn't exist the DB
    if ($result->num_rows == 0) {
        header("Location: forgotpassword.php?error=1");
    } else {
        $token = bin2hex(random_bytes(16));
        $token_hash = hash("sha256", $token);

        // valid only for 30 minutes, IMO ugly date format, yikes
        $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

        // update the token and the expire in the DB
        $sql = "UPDATE users
                SET reset_token_hash = ?,
                reset_token_expires_at = ?
                WHERE email = ?";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $token_hash, $expiry, $email);
            $stmt->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // set up stuff
        $mail = require __DIR__ . "/mailer.php";
        $mail->setFrom("noreply@example.com");
        $mail->addAddress($email);
        $mail->Subject = "[DeAd] Password Reset";
        $mail->Body = <<<END

        Click <a href="http://localhost/DeAd-web-project/src/resetpassword.php?token=$token">here</a>
        to reset your DeAd's account password.

        END;
        /// TO DO: change the href in the future
        try {
            header("Location: forgotpassword.php?success=1");
            $mail->send();
        } catch (Exception $e) {
            header("Location: forgotpassword.php?error=2");
        }
    }
}
