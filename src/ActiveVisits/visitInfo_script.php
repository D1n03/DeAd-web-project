<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['is_logged_in'])) {
    header('Location: login.php');
    http_response_code(401); // Unauthorized
    exit;
}

// Include configuration and database connection
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
    // Process POST data for updating visit information
    $user_id = $_SESSION['id'];
    $items_offered_by_inmate = $_POST['itemsFrom'] ?? '';
    $items_provided_to_inmate = $_POST['itemsTo'] ?? '';
    $summary = $_POST['summary'] ?? '';
    $inmate_health = $_POST['inmate_health'] ?? '';
    $witnesses = $_POST['witnesses'] ?? '';
    $visit_id = $_POST['visit_id'] ?? '';

    try {
        // Update visit information
        $stmt = $conn->prepare("UPDATE visits_info SET
            witnesses = ?,
            items_provided_to_inmate = ?,
            items_offered_by_inmate = ?,
            health_status = ?,
            summary = ?
            WHERE visitor_id = ? AND visit_refID = ?");

        $stmt->bind_param("sssssii",
            $witnesses,
            $items_provided_to_inmate,
            $items_offered_by_inmate,
            $inmate_health,
            $summary,
            $user_id,
            $visit_id);
        $stmt->execute();
        $stmt->close();

        // Update visit status
        $is_active = 0;
        $stmt2 = $conn->prepare("UPDATE visits SET is_active = ? WHERE visit_id = ?");
        $stmt2->bind_param("ii", $is_active, $visit_id);
        $stmt2->execute();
        $stmt2->close();

        // Return success response
        http_response_code(200); // OK
        header("Location: activevisits.php");
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