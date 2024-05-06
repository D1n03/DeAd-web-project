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
$first_name = $_POST['first_name'] ?? null;
$last_name = $_POST['last_name'] ?? null;
$sentence_start_date = $_POST['sentence_start_date'] ?? null;
$sentence_duration = $_POST['sentence_duration'] ?? null;
$sentence_category = $_POST['sentence_category'] ?? null;

// Check if all required fields are provided
if (!$first_name || !$last_name || !$sentence_start_date || !$sentence_duration || !$sentence_category) {
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

// Prepare and execute SQL query to insert inmate
$stmt = $conn->prepare("INSERT INTO inmates (person_id, first_name, last_name, sentence_start_date, sentence_duration, sentence_category) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssis", $user_id, $first_name, $last_name, $sentence_start_date, $sentence_duration, $sentence_category);

if ($stmt->execute()) {
    http_response_code(200); // Created
    header('Location: ./addInmate.php?success=1');
    exit();
    //echo json_encode(array("message" => "Inmate added successfully"));
} else {
    http_response_code(500); // Internal Server Error
    //echo json_encode(array("error" => "Failed to add inmate"));
}

$stmt->close();
?>
