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
    $filename = "export_" . date("Y-m-d") . ".csv";
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=$filename");
    $output = fopen("php://output", "w");

    fputcsv($output, array_keys($export_data[0]));

    foreach ($export_data as $row) {
        fputcsv($output, $row);
    }

    fputcsv($output, array());

    foreach ($stats as $category => $value) {
        if (is_array($value)) {
            foreach ($value as $subCategory => $subValue) {
                fputcsv($output, ["$category - $subCategory", $subValue]);
            }
        } else {
            fputcsv($output, [$category, $value]);
        }
    }

    fclose($output);
}

function export_as_HTML(array $export_data, array $stats)
{
    $filename = "export_" . date("Y-m-d") . ".html";
    header("Content-type: text/html");
    header("Content-Disposition: attachment; filename=$filename");
    $output = fopen("php://output", "w");
    fwrite($output, "<html><body><table>");

    fwrite($output, "<tr>");
    foreach (array_keys($export_data[0]) as $key) {
        fwrite($output, "<th>$key</th>");
    }
    fwrite($output, "</tr>");

    foreach ($export_data as $row) {
        fwrite($output, "<tr>");
        foreach ($row as $value) {
            fwrite($output, "<td>$value</td>");
        }
        fwrite($output, "</tr>");
    }

    foreach ($stats as $category => $value) {
        if (is_array($value)) {
            foreach ($value as $subCategory => $subValue) {
                fwrite($output, "<tr><td>$category - $subCategory</td><td>$subValue</td></tr>");
            }
        } else {
            fwrite($output, "<tr><td>$category</td><td>$value</td></tr>");
        }
    }

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
    $inmate_a = intval($a['inmate_id']);
    $inmate_b = intval($b['inmate_id']);
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
            $result = $conn->query("SELECT a.person_id, 
            a.first_name, 
            a.last_name,  
            a.relationship,
            a.visit_nature,
            a.source_of_income,
            a.date,
            a.visit_start,
            a.visit_end,
            a.is_active,
            b.visitor_id,
            b.inmate_id,
            b.witnesses,
            b.items_provided_to_inmate,
            b.items_offered_by_inmate,
            b.health_status,
            b.summary 
            FROM visits a
            INNER JOIN visits_info b ON a.visit_id = b.visit_refID");

            while ($row = $result->fetch_assoc()) {
                $export_data[] = $row;
            }

            $visitDurations = [];
            foreach ($export_data as $visit) {
                $start = strtotime($visit['visit_start']);
                $end = strtotime($visit['visit_end']);
                $duration = $end - $start; // in seconds
                $visitDurations[] = $duration;
            }

            // calculate average sentence duration
            $totalDuration = array_sum($visitDurations);
            $averageDuration = count($visitDurations) > 0 ? $totalDuration / count($visitDurations) : 0;

            $result = $conn->query("SELECT COUNT(*)  as total FROM visits");
            $row = $result->fetch_assoc();
            $stats['total'] = $row['total'];

            // number of visits for each inmate
            $result = $conn->query("SELECT inmate_id, COUNT(*) AS visit_count_per_inmate FROM visits_info GROUP BY inmate_id");
            while ($row = $result->fetch_assoc()) {
                $stats['visit_count_per_inmate'][$row['inmate_id']] = $row['visit_count_per_inmate'];
            }
            // Average sentence duration
            $stats['avg_sentence_duration'] = $averageDuration;

            // number of each visit_nature
            $result = $conn->query("SELECT visit_nature, COUNT(*) AS visit_nature_count FROM visits GROUP BY visit_nature");
            while ($row = $result->fetch_assoc()) {
                $stats['visit_nature_count'][$row['visit_nature']] = $row['visit_nature_count'];
            }

            // number of each relationship
            $result = $conn->query("SELECT relationship, COUNT(*) AS relationship_count FROM visits GROUP BY relationship");
            while ($row = $result->fetch_assoc()) {
                $stats['relationship_count'][$row['relationship']] = $row['relationship_count'];
            }

            // number of each source_of_income
            $result = $conn->query("SELECT source_of_income, COUNT(*) AS source_of_income_count FROM visits GROUP BY source_of_income");
            while ($row = $result->fetch_assoc()) {
                $stats['source_of_income_count'][$row['source_of_income']] = $row['source_of_income_count'];
            }

            // number of each witnesses
            $result = $conn->query("SELECT witnesses, COUNT(*) AS witnesses_count FROM visits_info GROUP BY witnesses");
            while ($row = $result->fetch_assoc()) {
                $stats['witnesses_count'][$row['witnesses']] = $row['witnesses_count'];
            }

            // number of each health_status
            $result = $conn->query("SELECT health_status, COUNT(*) AS health_status_count FROM visits_info GROUP BY health_status");
            while ($row = $result->fetch_assoc()) {
                $stats['health_status_count'][$row['health_status']] = $row['health_status_count'];
            }

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