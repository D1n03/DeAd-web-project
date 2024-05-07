<?php
require_once '../../vendor/autoload.php';

// Start session (if needed)
session_start();

// Include necessary files
$config = require '../../config.php';
require '../Utils/Connection.php';

// Check if user is logged in
if (!isset($_SESSION['is_logged_in'])) {
    http_response_code(401); // Unauthorized
    //echo json_encode(array("error" => "Unauthorized"));
    exit();
}

// Get user ID from session
$user_id = $_SESSION['id'];

// Get POST data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$sentence_start_date = $_POST['sentence_start_date'];
$sentence_duration = $_POST['sentence_duration'];
$sentence_category = $_POST['sentence_category'];
$inmate_id = $_POST['inmate_id'];

// Check if all required fields are provided
if (!$first_name || !$last_name || !$sentence_start_date || !$sentence_duration || !$sentence_category || !$inmate_id) {
    http_response_code(400); // Bad Request
    //echo json_encode(array("error" => "Missing required fields"));
    exit();
}

// Get database connection
$conn = Connection::getInstance()->getConnection();

// Check database connection
if (!$conn) {
    http_response_code(500); // Internal Server Error
    //echo json_encode(array("error" => "Database connection error"));
    exit();
}

$stmt = $conn->prepare("UPDATE inmates SET first_name=?, last_name=?, sentence_start_date=?, sentence_duration=?, sentence_category=? WHERE inmate_id=?");
$stmt->bind_param("sssisi", $first_name, $last_name, $sentence_start_date, $sentence_duration, $sentence_category, $inmate_id);

if ($stmt->execute()) {
    http_response_code(200); // Created
    header("Location: ./inmateEdit.php?inmate_id=$inmate_id&success=1");
    exit();
    //echo json_encode(array("message" => "Inmate added successfully"));
} else {
    http_response_code(500); // Internal Server Error
    //echo json_encode(array("error" => "Failed to add inmate"));
}

$stmt->close();
?>
