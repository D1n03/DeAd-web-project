<?php

require_once '../Utils/Connection.php';

class VisitHistory
{
    public static function getVisitHistory($active, $offset, $limit, $user_id)
    {
        $conn = Connection::getInstance()->getConnection();

        if ($conn->connect_errno) {
            die('Could not connect to db: ' . $conn->connect_error);
        } else {
            $stmt = $conn->prepare("SELECT visit_id, first_name, last_name, date, visit_start, visit_end, photo FROM visits WHERE is_active = ? AND person_id = ? LIMIT ?, ?");
            $stmt->bind_param('iiii', $active, $user_id, $offset, $limit);
            $stmt->execute();
            $result = $stmt->get_result();

            $visitHistory = array();

            while ($row = $result->fetch_assoc()) {
                $photo = base64_encode($row['photo']);

                $visitHistory[] = array(
                    'visit_id' => $row['visit_id'],
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    "time_interval" => $row['visit_start'] . " - " . $row['visit_end'],
                    'date' => $row['date'],
                    'photo' => $photo
                );
            }
            return $visitHistory;
        }
    }

    public static function getTotalEntriesCount($user_id)
    {
        $conn = Connection::getInstance()->getConnection();

        if ($conn->connect_errno) {
            die('Could not connect to db: ' . $conn->connect_error);
        } else {
            $stmt = $conn->prepare("SELECT COUNT(*) as total FROM visits WHERE is_active = 0 AND person_id = ?");
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['total'];
        }
    }
}
?>
