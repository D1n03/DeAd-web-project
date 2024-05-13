<?php
session_start();
//if the user is not logged in, redirect to the login page
if (!isset($_SESSION['is_logged_in'])) {
    header('Location: ../Login/login.php');
}

$config = require '../../config.php';
require '../Utils/Connection.php';
$conn = Connection::getInstance()->getConnection();

$person_id = $_SESSION['id'];

if ($conn->connect_errno) {
    die('Could not connect to db: ' . $conn->connect_error);
} else {

    $first_name_inmate = $_POST['first_name']; // first name of the inmate
    $last_name_inmate = $_POST['last_name'];  // last name of the inmate
    $relationship = $_POST['relationship'];
    $visit_nature = $_POST['visit_nature'];
    $source_of_income = $_POST['source_of_income'];
    $date = $_POST['date'];
    $visit_start = $_POST['visit_time_start'];
    $visit_end = $_POST['visit_time_end'];

    //check if the inmate exists in the database
    $stmt = $conn->prepare("SELECT inmate_id FROM inmates WHERE first_name = ? AND last_name = ?");
    $stmt->bind_param("ss", $first_name_inmate, $last_name_inmate);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close(); // Close the statement after fetching the result

    if ($row == null) {
        header('Location: visit.php?error=1'); //inmate does not exist
        exit();
    }
    $inmate_id = $row['inmate_id'];

    if (!empty($_FILES['profile_photo']['name'])) {
        $photo_contents = file_get_contents($_FILES['profile_photo']['tmp_name']);
    } else {
        $photo_contents = null;
    }
    $valid_extensions_photo = array('jpeg', 'jpg', 'png');

    //get the extension of the uploaded file
    $ext = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $valid_extensions_photo)) {
        header('Location: visit.php?error=2'); //invalid extension
        exit();
    }
    // check time that doesn't pass x hours and start < end
    $start_time = new DateTime($visit_start);
    $end_time = new DateTime($visit_end);
    $duration = $start_time->diff($end_time)->h;
    if ($duration > 3) {
        header('Location: visit.php?error=3'); // Redirect with error code for exceeding duration
        exit();
    }

    // check if start time is before end time
    if ($start_time >= $end_time) {
        header('Location: visit.php?error=4'); // Redirect with error code for invalid start and end times
        exit();
    }

    //check if the inmate has a visit in the same time interval
    $stmt3 = $conn->prepare("SELECT * FROM visits WHERE date = ? AND ((visit_start < ? AND visit_end > ?) OR (visit_start < ? AND visit_end > ?) OR (visit_start > ? AND visit_end < ?)) AND first_name = ? AND last_name = ?");
    $stmt3->bind_param("sssssssss", $date, $visit_end, $visit_start, $visit_end, $visit_start, $visit_start, $visit_end, $first_name_inmate, $last_name_inmate);
    $stmt3->execute();
    $result3 = $stmt3->get_result();
    $stmt3->close();

    if ($result3->num_rows > 0) {
        header('Location: visit.php?error=5'); // the inmate has a visit in the same time interval
        exit();
    }
    // put data into the visit
    $is_active = 1;
    $stmt = $conn->prepare("INSERT INTO visits
        (person_id,
        first_name,
        last_name,
        relationship,
        visit_nature,
        photo,
        source_of_income,
        date,
        visit_start,
        visit_end,
        is_active)
    VALUES (?,?,?,?,?,?,?,?,?,?,?)");

    try {
        $stmt->bind_param(
            "isssssssssi",
            $person_id,
            $first_name_inmate,
            $last_name_inmate,
            $relationship,
            $visit_nature,
            $photo_contents,
            $source_of_income,
            $date,
            $visit_start,
            $visit_end,
            $is_active
        );
        $stmt->execute();
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    //get the visit id
    $stmt2 = $conn->prepare("SELECT visit_id FROM visits WHERE person_id = ? AND date = ? AND visit_start = ? AND visit_end = ?");
    $stmt2->bind_param("isss", $person_id, $date, $visit_start, $visit_end);
    $stmt2->execute();
    $result = $stmt2->get_result();
    $row_appoint = $result->fetch_assoc();
    $stmt2->close();

    $stmt = $conn->prepare("INSERT INTO visits_info (
        visitor_id,
        inmate_id,
        visit_date,
        visit_nature,
        visit_refID) 
    VALUES (?,?,?,?,?)");

    $stmt->bind_param(
        "iissi",
        $person_id,
        $inmate_id,
        $date,
        $visit_nature,
        $row_appoint['visit_id']
    );
    $stmt->execute();
    $stmt->get_result();
    $stmt->close();

    header('Location: ../VisitorMain/visitormain.php');
}
