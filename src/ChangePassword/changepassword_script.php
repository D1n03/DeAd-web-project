<?php

// check if the password is strong
function check_password($password)
{
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);

    if (!$uppercase || !$number || strlen($password) < 8 || !$lowercase) {
        return 0;
    } else return 1;
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['email'])) {
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_new_password = $_POST["confirm_new_password"];

    // validate current password
    $email = $_SESSION['email'];
    require '../Utils/Connection.php';
    $conn = Connection::getInstance()->getConnection();

    if ($conn->connect_errno) {
        header("Location: ../Error/error.php");
        exit;   
    } else {
        try {
            $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];

            if (password_verify($current_password, $hashed_password)) {
                if ($new_password === $confirm_new_password) {

                    $passwordStrength = check_password($new_password);
                    if ($passwordStrength == 1) {
                        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

                        $sql = "UPDATE users SET password = ? WHERE email = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $hashed_new_password, $email);
                        $stmt->execute();

                        $_SESSION['email'] = $email;

                        header("Location: changepassword.php?password_change_success=1");
                        exit;
                    }
                    else
                    {
                        header("Location: changepassword.php?strength=$passwordStrength");
                        exit();
                    }
                } else {
                    // redirecting with an error if new passwords don't match
                    header("Location: changepassword.php?error=2");
                    exit;
                }
            } else {
                // redirecting with an error if current password is incorrect
                header("Location: changepassword.php?error=1");
                exit;
            }
        } catch (Exception $e) {
            header("Location: changepassword.php?error=3");
            exit;
        }
    }
} else {
    // redirect to error page if this is accessed directly or if session data is missing
    header("Location: ../Error/error.php");
    exit;
}
?>
