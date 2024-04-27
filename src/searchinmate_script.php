<?php
use Firebase\JWT\JWT;

require_once '../vendor/autoload.php';
require_once 'Utils/Connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if searchName is set and not empty
    if(isset($_POST['searchName']) && !empty(trim($_POST['searchName']))) {
        $searchName = $_POST['searchName'];

        // Split the search input into first name and last name
        $names = explode(" ", $searchName);
        
        // Extract the first name and last name
        $searchFirstName = $names[0];
        $searchLastName = implode(" ", array_slice($names, 1)); // Join the remaining parts as last name

        $conn = Connection::getInstance()->getConnection();

        if ($conn->connect_errno) {
            die('Could not connect to db: ' . $conn->connect_error);
        } else {
            try {
                $stmt = $conn->prepare("SELECT * FROM inmates WHERE first_name = ? AND last_name = ?");
                $stmt->bind_param("ss", $searchFirstName, $searchLastName);
                $stmt->execute();
                $result = $stmt->get_result();
            } catch (Exception $e) {
                echo $e->getMessage();
            }

            if ($result->num_rows > 0) {
                // Inmate found, display the section
                $row = $result->fetch_assoc();
                ?>
                <div class="inmate__show-banner">
                    <div class="delete-show" id="deleteButton">
                        <div class="line line-1"></div>
                        <div class="line line-2"></div>
                    </div>
                    <img src="../assets/visitormain/inmate-icon.webp" alt="inmate-picture" class="inmate__show-photo" />
                    <h1 class="inmate__show-title"><?php echo $row['first_name'] . " " . $row['last_name']; ?></h1>
                </div>
                <?php
            } else {
                // Inmate not found
                echo '<h2 style="color: red;">Inmate not found!</h2>';
            }
        }
    } else {
        // searchName is not set or empty
        echo '<h2 style="color: red;">Please provide a name to search for!</h2>';
    }
}
?>