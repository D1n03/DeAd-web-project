<?php
require '../Utils/BaseAPI.php';

class ExportController extends BaseAPI
{

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                $this->jwtValidation->validateAdminToken();
                $this->ExportData();
                break;
            default:
                http_response_code(405);
                exit(json_encode(array("error" => "Only GET requests are allowed.")));
        }
    }

    private function ExportData()
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, 'Method Not Allowed');
        }

        $exportType = $_GET['export'] ?? '';
        $format = $_GET['format'] ?? '';
        $sorted = $_GET['sorted'] ?? '';

        if (empty($exportType) || empty($format)) {
            $this->sendResponse(400, 'Bad Request');
        }

        $exportData = [];
        $stats = [];

        switch ($exportType) {
            case 'users':
                $this->exportUsers($exportData, $stats, $sorted);
                break;
            case 'inmates':
                $this->exportInmates($exportData, $stats, $sorted);
                break;
            case 'all_visits':
                $this->exportAllVisits($exportData, $stats, $sorted);
                break;
            default:
                $this->sendResponse(400, 'Invalid Export Type');
        }

        switch ($format) {
            case 'json':
                $this->exportAsJSON($exportData, $stats);
                break;
            case 'csv':
                $this->exportAsCSV($exportData, $stats);
                break;
            case 'html':
                $this->exportAsHTML($exportData, $stats);
                break;
            default:
                $this->sendResponse(400, 'Invalid Format');
        }
    }

    private function exportUsers(&$exportData, &$stats, $sorted)
    {
        $result = $this->conn->query("SELECT first_name, last_name, email, `function` FROM users");
        while ($row = $result->fetch_assoc()) {
            $exportData[] = $row;
        }

        $result = $this->conn->query("SELECT COUNT(*) as total FROM users");
        $row = $result->fetch_assoc();
        $stats['total'] = $row['total'];

        if ($sorted == 'name') {
            usort($exportData, [$this, 'sortByName']);
        }
    }

    private function exportInmates(&$exportData, &$stats, $sorted)
    {
        $result = $this->conn->query("SELECT person_id, first_name, last_name, sentence_start_date, sentence_duration, sentence_category FROM inmates");
        while ($row = $result->fetch_assoc()) {
            $exportData[] = $row;
        }

        $result = $this->conn->query("SELECT COUNT(*)  as total FROM inmates");
        $row = $result->fetch_assoc();
        $stats['total'] = $row['total'];

        if ($sorted == 'name') {
            usort($exportData, [$this, 'sortByName']);
        } elseif ($sorted == 'sentence_start_date') {
            usort($exportData, [$this, 'sortBySentenceStartDate']);
        } elseif ($sorted == 'sentence_duration') {
            usort($exportData, [$this, 'sortBySentenceDuration']);
        }
    }

    private function exportAllVisits(&$exportData, &$stats, $sorted)
    {
        $result = $this->conn->query("SELECT a.person_id, a.first_name, a.last_name, a.relationship, a.visit_nature, a.source_of_income, a.date, a.visit_start, a.visit_end, a.is_active, b.visitor_id, b.inmate_id, b.witnesses, b.items_provided_to_inmate, b.items_offered_by_inmate, b.health_status, b.summary 
                                      FROM visits a INNER JOIN visits_info b ON a.visit_id = b.visit_refID");
        while ($row = $result->fetch_assoc()) {
            $exportData[] = $row;
        }

        $visitDurations = array_map(function ($visit) {
            return strtotime($visit['visit_end']) - strtotime($visit['visit_start']);
        }, $exportData);

        $totalDuration = array_sum($visitDurations);
        $averageDuration = count($visitDurations) > 0 ? $totalDuration / count($visitDurations) : 0;

        $result = $this->conn->query("SELECT COUNT(*)  as total FROM visits");
        $row = $result->fetch_assoc();
        $stats['total'] = $row['total'];

        $stats['visit_count_per_inmate'] = $this->getVisitCountPerInmate();
        $stats['avg_sentence_duration'] = $averageDuration;
        $stats['visit_nature_count'] = $this->getVisitNatureCount();
        $stats['relationship_count'] = $this->getRelationshipCount();
        $stats['source_of_income_count'] = $this->getSourceOfIncomeCount();
        $stats['witnesses_count'] = $this->getWitnessesCount();
        $stats['health_status_count'] = $this->getHealthStatusCount();

        if ($sorted == 'date') {
            usort($exportData, [$this, 'sortByDate']);
        } elseif ($sorted == 'visitor') {
            usort($exportData, [$this, 'sortByVisitor']);
        } elseif ($sorted == 'inmate') {
            usort($exportData, [$this, 'sortByInmate']);
        }
    }

    private function getVisitCountPerInmate()
    {
        $stats = [];
        $result = $this->conn->query("SELECT inmate_id, COUNT(*) AS visit_count_per_inmate FROM visits_info GROUP BY inmate_id");
        while ($row = $result->fetch_assoc()) {
            $stats[$row['inmate_id']] = $row['visit_count_per_inmate'];
        }
        return $stats;
    }

    private function getVisitNatureCount()
    {
        $stats = [];
        $result = $this->conn->query("SELECT visit_nature, COUNT(*) AS visit_nature_count FROM visits GROUP BY visit_nature");
        while ($row = $result->fetch_assoc()) {
            $stats[$row['visit_nature']] = $row['visit_nature_count'];
        }
        return $stats;
    }

    private function getRelationshipCount()
    {
        $stats = [];
        $result = $this->conn->query("SELECT relationship, COUNT(*) AS relationship_count FROM visits GROUP BY relationship");
        while ($row = $result->fetch_assoc()) {
            $stats[$row['relationship']] = $row['relationship_count'];
        }
        return $stats;
    }

    private function getSourceOfIncomeCount()
    {
        $stats = [];
        $result = $this->conn->query("SELECT source_of_income, COUNT(*) AS source_of_income_count FROM visits GROUP BY source_of_income");
        while ($row = $result->fetch_assoc()) {
            $stats[$row['source_of_income']] = $row['source_of_income_count'];
        }
        return $stats;
    }

    private function getWitnessesCount()
    {
        $stats = [];
        $result = $this->conn->query("SELECT witnesses, COUNT(*) AS witnesses_count FROM visits_info GROUP BY witnesses");
        while ($row = $result->fetch_assoc()) {
            $stats[$row['witnesses']] = $row['witnesses_count'];
        }
        return $stats;
    }

    private function getHealthStatusCount()
    {
        $stats = [];
        $result = $this->conn->query("SELECT health_status, COUNT(*) AS health_status_count FROM visits_info GROUP BY health_status");
        while ($row = $result->fetch_assoc()) {
            $stats[$row['health_status']] = $row['health_status_count'];
        }
        return $stats;
    }

    private function exportAsJSON($exportData, $stats)
    {
        $exportDataJson = json_encode($exportData, JSON_PRETTY_PRINT);
        $statsJson = json_encode($stats, JSON_PRETTY_PRINT);
        $combinedJson = json_encode(['export_data' => json_decode($exportDataJson), 'stats' => json_decode($statsJson)], JSON_PRETTY_PRINT);
        $filename = "export_" . date("Y-m-d") . ".json";
        header("Content-type: application/json");
        header("Content-Disposition: attachment; filename=$filename");
        echo $combinedJson;
        exit();
    }

    private function exportAsCSV($exportData, $stats)
    {
        $filename = "export_" . date("Y-m-d") . ".csv";
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$filename");
        $output = fopen("php://output", "w");

        fputcsv($output, array_keys($exportData[0]));

        foreach ($exportData as $row) {
            fputcsv($output, $row);
        }

        fputcsv($output, []);

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
        exit();
    }

    private function exportAsHTML($exportData, $stats)
    {
        $filename = "export_" . date("Y-m-d") . ".html";
        header("Content-type: text/html");
        header("Content-Disposition: attachment; filename=$filename");
        $output = fopen("php://output", "w");
        fwrite($output, "<html><body><table>");

        fwrite($output, "<tr>");
        foreach (array_keys($exportData[0]) as $key) {
            fwrite($output, "<th>$key</th>");
        }
        fwrite($output, "</tr>");

        foreach ($exportData as $row) {
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
        exit();
    }

    private function sortByName($a, $b)
    {
        $firstNameComparison = strcmp($a['first_name'], $b['first_name']);
        return $firstNameComparison === 0 ? strcmp($a['last_name'], $b['last_name']) : $firstNameComparison;
    }

    private function sortBySentenceStartDate($a, $b)
    {
        return strtotime($a['sentence_start_date']) - strtotime($b['sentence_start_date']);
    }

    private function sortBySentenceDuration($a, $b)
    {
        return intval($a['sentence_duration']) - intval($b['sentence_duration']);
    }

    private function sortByDate($a, $b)
    {
        return strtotime($a['date']) - strtotime($b['date']);
    }

    private function sortByVisitor($a, $b)
    {
        return intval($a['person_id']) - intval($b['person_id']);
    }

    private function sortByInmate($a, $b)
    {
        return intval($a['inmate_id']) - intval($b['inmate_id']);
    }

    private function sendResponse($statusCode, $message)
    {
        http_response_code($statusCode);
        echo json_encode(['message' => $message]);
        exit();
    }
}

$controller = new ExportController();
$controller->handleRequest();
