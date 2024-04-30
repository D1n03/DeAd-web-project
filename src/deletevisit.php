<?php
if (isset($_POST['visit_id'])) {
    $visit_id = $_POST['visit_id'];

    require 'Utils/Connection.php';
    $conn = Connection::getInstance()->getConnection();

    // so the order of deletion is the most important thing here
    $sql2 = "DELETE FROM visits WHERE visit_id = '$visit_id'";
    $result2 = mysqli_query($conn, $sql2);

    $sql = "DELETE FROM visit_info WHERE visit_refID = '$visit_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && $result2) {
        http_response_code(200); // OK
    } else {
        http_response_code(500); // Internal Server Error
    }
    mysqli_close($conn);
} else {
    http_response_code(400); // Bad Request
}
