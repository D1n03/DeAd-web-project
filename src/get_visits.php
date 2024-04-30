<?php
//for the current user make a query to get all the visits and send them to the frontend
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $config = require '../config.php';
    require 'Utils/Connection.php';
    $conn = Connection::getInstance()->getConnection();

    session_start();
    $user_id = $_GET['id'];
    $user_id = strval($user_id);
    $stmt = $conn->prepare("SELECT visit_id, first_name, last_name, date, visit_start, visit_end FROM visits WHERE person_id = ? and is_active = 1");

    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // dont print the warning
    error_reporting(E_ERROR | E_PARSE);

    //get all the lines from the DB
    $respone = array();
    while ($row = mysqli_fetch_assoc($result)) {

        $line = array(
            "inmate_name" => $row['first_name'] . " " . $row['last_name'],
            "date" => $row['date'],
            "time_interval" => $row['visit_start'] . " " . $row['visit_end'],
            "visit_id" => $row['visit_id']
        );
        $respone[] = $line;
    }
    // we go back to user visit page
    header('Content-Type: application/json');
    echo json_encode($respone);
    exit();
}
