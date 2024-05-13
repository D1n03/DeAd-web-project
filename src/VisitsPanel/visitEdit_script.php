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
    $first_name_inmate = $_POST['inmate_first_name']; 
    $last_name_inmate = $_POST['inmate_last_name'];
    // TO DO, visit time and date validation, no overlap

    $start_time = new DateTime($visit_time_start);
    $end_time = new DateTime($visit_time_end);
    $duration = $start_time->diff($end_time)->h;
    if ($duration > 3) {
        http_response_code(200); // OK
        header("Location: visitEdit.php?visit_id=$visit_id&error=1"); // Redirect with error code for exceeding duration
        exit();
    }

    // check if start time is before end time
    if ($start_time >= $end_time) {
        http_response_code(200); // OK
        header("Location: visitEdit.php?visit_id=$visit_id&error=2"); // Redirect with error code for invalid start and end times
        exit();
    }

    //check if the inmate has a visit in the same time interval
    $stmt3 = $conn->prepare("SELECT * FROM visits WHERE date = ? AND (
        (visit_start <= ? AND visit_end >= ?) -- Case 1: Existing visit starts before new visit and ends after new visit starts
        OR (visit_start <= ? AND visit_end >= ?) -- Case 2: Existing visit starts before new visit ends and ends after new visit ends
        OR (visit_start >= ? AND visit_end <= ?) -- Case 3: Existing visit starts after new visit and ends before new visit ends
        OR (visit_start >= ? AND visit_start <= ?) -- Case 4: Existing visit starts after new visit starts but before new visit ends
        OR (visit_end >= ? AND visit_end <= ?) -- Case 5: Existing visit ends after new visit starts but before new visit ends
    ) AND first_name = ? AND last_name = ?");
    $stmt3->bind_param("sssssssssssss", $date, $visit_time_start, $visit_time_start, $visit_time_end, $visit_time_end, $visit_time_start, $visit_time_end, $visit_time_start, $visit_time_end, $visit_time_start, $visit_time_end, $first_name_inmate, $last_name_inmate);
    $stmt3->execute();
    $result3 = $stmt3->get_result();
    $stmt3->close();

    if ($result3->num_rows > 0) {
        http_response_code(200); // OK
        header("Location: visitEdit.php?visit_id=$visit_id&error=3"); // the inmate has a visit in the same time interval
        exit();
    }

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