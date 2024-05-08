<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['is_logged_in'])) {
    header('Location: login.php');
    http_response_code(401); // Unauthorized
    exit;
}

$config = require '../../config.php';
require '../Utils/Connection.php';
$conn = Connection::getInstance()->getConnection();

// Check connection
if ($conn->connect_errno) {
    header('Location: ../Error/error.php');
    http_response_code(500);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $visit_id = $_POST['visit_id'];
    $date = $_POST['date'];
    $visit_time_start = $_POST['visit_time_start'];
    $visit_time_end = $_POST['visit_time_end'];
    $visit_nature = $_POST['visit_nature'];
    // TO DO, visit time and date validation, no overlap
    try {
        // Update data in visits table
        $stmt = $conn->prepare("UPDATE visits SET visit_nature=?, date=?, visit_start=?, visit_end=? WHERE visit_id=?");
        $stmt->bind_param("ssssi", $visit_nature, $date, $visit_time_start, $visit_time_end, $visit_id);
        $stmt->execute();

        // Update data in visits_info table
        $stmt = $conn->prepare("UPDATE visits_info SET visit_nature=?, visit_date=? WHERE visit_refID=?");
        $stmt->bind_param("sss", $visit_nature, $date, $visit_id);
        $stmt->execute();

        http_response_code(200); // OK
        header("Location: visitEdit.php?visit_id=$visit_id&success=1");
        exit;
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        header('Location: ../Error/error.php');
        exit;
    }
} else {
    // Return error response for unsupported request method
    http_response_code(405); // Method Not Allowed
    header('Location: ../Error/error.php');
    exit;
}
?>