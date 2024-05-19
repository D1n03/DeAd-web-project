<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="The details of the selected visit." />
  <link rel="stylesheet" href="../styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>Visit Details</title>
</head>

<body>
  <header class="header" id="page-header">
    <div class="nav-container">
      <nav class="navbar">
        <div class="menu-toggle" id="mobile-menu">
          <span class="togglebar"></span>
          <span class="togglebar"></span>
          <span class="togglebar"></span>
        </div>
        <ul class="nav-list">
          <li class="list__item">
            <a href="../Index/index.php" class="nav-link">
              <img class="list__item-icon" src="../../assets/header/home-icon.svg" alt="home-icon" />
              <p class="list__item-text">Home</p>
            </a>
          </li>
          <li class="list__item">
            <a href="../About/about.php" class="nav-link">
              <img class="list__item-icon" src="../../assets/header/about-icon.svg" alt="about-icon" />
              <p class="list__item-text">About</p>
            </a>
          </li>
          <li class="list__item">
            <a href="../Help/help.php" class="nav-link">
              <img class="list__item-icon" src="../../assets/header/help-icon.svg" alt="help-icon" />
              <p class="list__item-text">Help</p>
            </a>
          </li>
          <li class="list__item">
            <a href="../Contact/contact.php" class="nav-link">
              <img class="list__item-icon" src="../../assets/header/contact-icon.svg" alt="contact-icon" />
              <p class="list__item-text">Contact</p>
            </a>
          </li>
          <li class="list__item">
            <a href="#" class="profile-link">
              <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) : ?>
                <?php if (isset($_SESSION['photo'])) : ?>
                  <img class="person-icon" src="data:image/jpeg;base64,<?php echo base64_encode($_SESSION['photo']); ?>" alt="profile-icon" onclick="toggleMenu()" id="person-icon" />
                <?php else : ?>
                  <img class="person-icon" src="../../assets/header/person-icon.webp" alt="person-icon" onclick="toggleMenu()" id="person-icon" />
                <?php endif; ?>
              <?php else : ?>
                <img class="person-icon" src="../../assets/header/person-icon.webp" alt="person-icon" id="person-icon" />
              <?php endif; ?>
            </a>
          </li>
        </ul>
        <?php
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['first_name']) && isset($_SESSION['last_name'])) :
        ?>
          <div class="sub-menu-wrap" id="subMenu">
            <div class="sub-menu">
              <div class="user-info">
                <?php if (isset($_SESSION['photo'])) : ?>
                  <img src="data:image/jpeg;base64,<?php echo base64_encode($_SESSION['photo']); ?>" alt="person-icon-sub" />
                <?php else : ?>
                  <img src="../../assets/header/person-icon.webp" alt="person-icon-sub" />
                <?php endif; ?>
                <h2><?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?></h2>
              </div>
              <hr />
              <a href="../Profile/profile.php" class="sub-menu-link">
                <img src="../../assets/header/profile-icon.png" alt="profile-icon" />
                <p>Profile</p>
                <span>⯈</span>
              </a>
              <a href="#" class="sub-menu-link" onclick="logout()">
                <img src="../../assets/header/logout-icon.png" alt="logout-icon" />
                <p>Logout</p>
                <span>⯈</span>
              </a>
            </div>
          </div>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <main class="visit-details">
    <div class="visit-details__form-visit">
      <div class="visit-details-visit__labels">
        <div class="visit-details-visit__labels__title">Visit Details</div>

        <?php
        if (isset($_GET['id'])) {
          require_once '../Utils/Connection.php';

          $visit_id = $_GET['id'];

          $conn = Connection::getInstance()->getConnection();
          $stmt = $conn->prepare("SELECT v.visit_id, v.person_id, v.first_name, v.last_name, v.relationship, v.visit_nature, v.source_of_income, v.date, v.visit_start, v.visit_end, v.photo, vi.witnesses, vi.summary, vi.items_provided_to_inmate, vi.items_offered_by_inmate 
                                    FROM visits v 
                                    LEFT JOIN `visits_info` vi ON v.visit_id = vi.visit_info_id 
                                    WHERE v.visit_id = ?");
          $stmt->bind_param("s", $visit_id);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
            $visit = $result->fetch_assoc();



            echo '<img class="visit-icon" src="data:image/jpeg;base64,' . base64_encode($visit['photo']) . '" alt="Inmate Photo">';
            echo '<div class="visit-details-visit__labels__container">';
            echo '<div class="visit-details-visit__labels__container">';
            echo '<p class="container__text">Inmate: ' . $visit['first_name'] . ' ' . $visit['last_name'] . '</p>';
            echo '</div>';

            echo '<div class="visit-details-visit__labels__container">';
            echo '<p class="container__text">Date: ' . $visit['date'] . '</p>';
            echo '</div>';

            echo '<div class="visit-details-visit__labels__container">';
            echo '<p class="container__text">Duration: ' . $visit['visit_start'] . ' - ' . $visit['visit_end'] . '</p>';
            echo '</div>';

            echo '<div class="visit-details-visit__labels__container">';
            echo '<p class="container__text">Relationship: ' . $visit['relationship'] . '</p>';
            echo '</div>';

            echo '<div class="visit-details-visit__labels__container">';
            echo '<p class="container__text">Source of income: ' . $visit['source_of_income'] . '</p>';
            echo '</div>';

            echo '<div class="visit-details-visit__labels__container">';
            echo '<p class="container__text">Witnesses: ' . $visit['witnesses'] . '</p>';
            echo '</div>';
            echo '</div>';

            echo '<div class="visit-details-visit__labels__container">';
            echo '<div class="visit-details-visit__labels__container">';
            echo '<p class="container__text">Items offered by inmate: ' . $visit['items_offered_by_inmate'] . '</p>';
            echo '</div>';
            echo '<div class="visit-details-visit__labels__container">';
            echo '<p class="container__text">Items provided to inmate: ' . $visit['items_provided_to_inmate'] . '</p>';
            echo '</div>';

            echo '</div>';

            echo '<div class="visit-details-visit__labels__container">';
            echo '<div class="visit-details-visit__labels__container">';
            echo '<p class="container__text">Visit nature: ' . $visit['visit_nature'] . '</p>';
            echo '</div>';
            echo '<div class="visit-details-visit__labels__container">';
            // echo '<p class="container__text">Summary of discussions: ' . $visit['summary'] . '</p>';
            echo '<p class="container__text">Summary of discussions:</p>';
            echo '<textarea class="form-textarea" id="visit-summary" name="visit-summary" readonly>' . htmlspecialchars($visit['summary'], ENT_QUOTES, 'UTF-8') . '</textarea>';
            echo '</div>';
            echo '</div>';
          } else {
            echo 'Visit not found.';
          }
        } else {
          echo 'Visit ID not provided.';
        }
        ?>

        <a class="link" href="history.php">Back</a>
        </h1>
      </div>
    </div>

  </main>
  <?php
  if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) :
  ?>
    <script src="../scripts/submenu.js"></script>
    <script src="../scripts/logout.js"></script>
  <?php endif; ?>
  <script src="../scripts/navbar.js"></script>
</body>

</html>