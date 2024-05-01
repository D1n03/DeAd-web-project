<?php
session_start();
//if the user is not logged in, redirect to the login page
if (!isset($_SESSION['is_logged_in'])) {
    header('Location: login.php');
    exit();
}

$config = require '../../config.php';
require '../Utils/Connection.php';
$conn = Connection::getInstance()->getConnection();

$user_id = $_SESSION['id'];
$items_offered_by_inmate = $_POST['itemsFrom'];
$items_provided_to_inmate = $_POST['itemsTo'];
$summary = $_POST['summary'];
$inmate_health = $_POST['inmate_health'];
$witnesses = $_POST['witnesses'];
$visit_id = $_POST['visit_id'];


if ($conn->connect_errno) {
    die('Could not connect to db: ' . $conn->connect_error);
} else {

    try {
        $stmt= $conn->prepare("UPDATE visits_info SET
        witnesses = ?,
        items_provided_to_inmate = ?,
        items_offered_by_inmate = ?,
        health_status = ?,
        summary = ?
        WHERE visitor_id = ? and visit_refID = ?");

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
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    
    $is_active = 0;
    try {
        $stmt2= $conn->prepare("UPDATE visits SET
        is_active = ?
        WHERE visit_id = ?");

        $stmt2->bind_param("ii",
        $is_active,
        $visit_id);
        $stmt2->execute();
        $stmt2->close();
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    header('Location: ../VisitorMain/visitormain.php');
    exit();
}
