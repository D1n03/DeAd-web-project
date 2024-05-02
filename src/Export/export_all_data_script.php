<?php

require '../Utils/Connection.php';
$config = require '../../config.php';

function export_as_JSON($export_data, $stats)
{
    $export_data_json = json_encode($export_data, JSON_PRETTY_PRINT);
    $stats_json = json_encode($stats, JSON_PRETTY_PRINT);
    $combined_json = json_encode(array('export_data' => json_decode($export_data_json), 'stats' => json_decode($stats_json)), JSON_PRETTY_PRINT);
    $filename = "export_" . date("Y-m-d") . ".json";
    header("Content-type: application/json");
    header("Content-Disposition: attachment; filename=$filename");
    $output = fopen("php://output", "w");
    fwrite($output, $combined_json);
    fclose($output);
}
function export_as_CSV($export_data, $stats)
{
    $filename = "export_".date("Y-m-d").".csv";
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=$filename");
    $output = fopen("php://output", "w");
    foreach ($export_data as $row) {
        fputcsv($output, $row);
    }
    fputcsv($output, array('Total'));
    fputcsv($output, $stats);
    fclose($output);
}

function export_as_HTML(array $export_data , array $stats)
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
    fwrite($output, "<tr><td>Total</td><td>" . $stats['total'] . "</td></tr>");
    fwrite($output, "</table></body></html>");
    fclose($output);
}

function sortByName($a, $b) {
    $first_name_comparison = strcmp($a['first_name'], $b['first_name']);
    if ($first_name_comparison !== 0) {
        return $first_name_comparison;
    }
    return strcmp($a['last_name'], $b['last_name']);
}

function sortBySentenceStartDate($a, $b) {
    $date_a = strtotime($a['sentence_start_date']);
    $date_b = strtotime($b['sentence_start_date']);
    return $date_a - $date_b; 
}

function sortBySentenceDuration($a, $b) {
    $duration_a = intval($a['sentence_duration']);
    $duration_b = intval($b['sentence_duration']);
    return $duration_a - $duration_b; 
}

function sortByDate($a, $b) {
    $date_a = strtotime($a['date']);
    $date_b = strtotime($b['date']);
    return $date_a - $date_b;
}

function sortByVisitor($a, $b) {
    $visitor_a = intval($a['person_id']);
    $visitor_b = intval($b['person_id']);
    return $visitor_a - $visitor_b;
}

function sortByInmate($a, $b) {
    $inmate_a = intval($a['inmate']);
    $inmate_b = intval($b['inmate']);
    return $inmate_a - $inmate_b;
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
            $result = $conn->query("SELECT first_name, last_name, email, `function` FROM users");
            while ($row = $result->fetch_assoc()) {
                $export_data[] = $row;
            }
            $result = $conn->query("SELECT COUNT(*) as total FROM users");
            $row = $result->fetch_assoc();
            $stats['total'] = $row['total'];

            if (isset($_POST['sorted'])) {
                if ($_POST['sorted'] == 'name') {
                    usort($export_data, 'sortByName');
                } 
            }
            break;

        case 'inmates':
            $result = $conn->query("SELECT person_id, first_name, last_name, sentence_start_date, sentence_duration, sentence_category FROM inmates");
            while ($row = $result->fetch_assoc()) {
                $export_data[] = $row;
            }

            $result = $conn->query("SELECT COUNT(*)  as total FROM inmates");
            $row = $result->fetch_assoc();
            $stats['total'] = $row['total'];

            if (isset($_POST['sorted'])) {
                if ($_POST['sorted'] == 'name') {
                    usort($export_data, 'sortByName');
                } else if ($_POST['sorted'] == 'sentence_start_date') {
                    usort($export_data, 'sortBySentenceStartDate');
                } else if ($_POST['sorted'] == 'sentence_duration') {
                    usort($export_data, 'sortBySentenceDuration');
                } 
            }
            break;

        case 'all_visits':
            $result = $conn->query("SELECT * FROM appointments");
            while ($row = $result->fetch_assoc()) {
                $export_data[] = $row;
            }

            $result = $conn->query("SELECT COUNT(*)  as total FROM appointments");
            $row = $result->fetch_assoc();
            $stats['total'] = $row['total'];

            if (isset($_POST['sorted'])) {
                if ($_POST['sorted'] == 'date') {
                    usort($export_data, 'sortByDate');
                } else if ($_POST['sorted'] == 'visitor') {
                    usort($export_data, 'sortByVisitor');
                } else if ($_POST['sorted'] == 'inmate') {
                    usort($export_data, 'sortByInmate');
                } 
            }
            break;

        default:
            http_response_code(400);
            exit();
    }

    switch ($_POST['format']) {
        case 'json':
            export_as_JSON($export_data, $stats);
            break;
        case 'csv':
            export_as_CSV($export_data, $stats);
            break;
        case 'html':
            export_as_HTML($export_data, $stats);
            break;
        default:
            http_response_code(400);
            exit();
    }
} else {
    http_response_code(401);
    exit();
}