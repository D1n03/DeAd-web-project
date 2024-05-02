<?php

require '../Utils/Connection.php';
$config = require '../../config.php';

function export_as_JSON($export_data, $statistic_data)
{

    $export_data = json_encode($export_data);
    $filename = "export_".date("Y-m-d").".json";
    header("Content-type: application/json");
    header("Content-Disposition: attachment; filename=$filename");
    $output = fopen("php://output", "w");
    fwrite($output, $export_data);
    fwrite($output, "\n");
    fwrite($output, json_encode($statistic_data));
    fclose($output);
}

function export_as_CSV($export_data, $statistic_data)
{
    $filename = "export_".date("Y-m-d").".csv";
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=$filename");
    $output = fopen("php://output", "w");
    foreach ($export_data as $row) {
        fputcsv($output, $row);
    }
    fputcsv($output, array("Total :"));
    fputcsv($output, $statistic_data);

    fclose($output);
}

function export_as_HTML(array $export_data , array $statistic_data)
{
    $filename = "export_".date("Y-m-d").".html";
    header("Content-type: text/html");
    header("Content-Disposition: attachment; filename=$filename");
    $output = fopen("php://output", "w");
    fwrite($output, "<html><body><table>");
    foreach ($export_data as $row) {
        fwrite($output, "<tr>");
        foreach ($row as $key => $value) {
            fwrite($output, "<td>$value</td>");
        }
        fwrite($output, "</tr>");
    }
    fwrite($output, "<tr><td>Total</td><td>" . $statistic_data['total'] . "</td></tr>");
    fwrite($output, "</table></body></html>");
    fclose($output);
}

$conn = Connection::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../../vendor/autoload.php';
    session_start();

    $user_id = $_SESSION['id'];
    $persons = $_POST['export'];
    // TO DO TOKEN CHECK

    $export_data = array();
    $stats = array();

    switch ($_POST['export']) {
        case 'users':
            
            break;
        case 'inmates':

            break;

        case 'all_visits':

            break;
    }

    switch ($_POST['format']) {
        case 'json':

            break;
        case 'csv':

            break;
        case 'html':

            break;
        default:
            http_response_code(400);
            exit();
    }
} else {
    http_response_code(401);
    exit();
}