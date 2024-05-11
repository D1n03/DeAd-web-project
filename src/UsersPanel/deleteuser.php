<?php
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    require '../Utils/Connection.php';
    $conn = Connection::getInstance()->getConnection();

    $sql = "DELETE FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        http_response_code(200); // OK
    } else {
        http_response_code(500); // Internal Server Error
    }
    mysqli_close($conn);
} else {
    http_response_code(400); // Bad Request
}
