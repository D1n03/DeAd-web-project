<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
  header("Location: ../Login/login.php");
  exit;
} else if (!isset($_SESSION['function']) || $_SESSION['function'] !== 'user') {
  header("Location: ../Index/index.php");
  exit;
}
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
        if (isset($_GET['visit_id'])) {
          $base_url = "localhost";
          $url = $base_url . "/DeAd-web-Project/src/VisitHistory/get_visit_id.php?visit_id=" . $_GET['visit_id'];
          $curl = curl_init($url);

          if (isset($_COOKIE['auth_token'])) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Cookie: auth_token=' . $_COOKIE['auth_token']));
          }

          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_HTTPGET, true);
          $curl_response = curl_exec($curl);
          curl_close($curl);
          $visit = json_decode($curl_response, true);
          
          if ($visit && isset($visit['visit_id'])) {
            echo "<input type='hidden' name='visit_id' value='" . htmlspecialchars($visit['visit_id'], ENT_QUOTES, 'UTF-8') . "'>";

            echo '<img class="visit-icon" src="data:image/jpeg;base64,' . $visit['photo'] . '" alt="Inmate Photo">';
            echo '<div class="visit-details-visit__labels__container">';
            echo '<div class="visit-details-visit__labels__container">';
            echo '<p class="container__text">Inmate: ' . $visit['inmate_name'] . '</p>';
            echo '</div>';

            echo '<div class="visit-details-visit__labels__container">';
            echo '<p class="container__text">Date: ' . htmlspecialchars($visit['date'], ENT_QUOTES, 'UTF-8') . '</p>';
            echo '</div>';

            echo '<div class="visit-details-visit__labels__container">';
            echo '<p class="container__text">Duration: ' . htmlspecialchars($visit['time_interval'], ENT_QUOTES, 'UTF-8') . '</p>';
            echo '</div>';

            switch ($visit['relationship']) {
              case 'first_degree_relative':
                $relationshipText = 'first-degree relative';
                break;
              case 'second_degree_relative':
                $relationshipText = 'second-degree relative';
                break;
              default:
                $relationshipText = htmlspecialchars($visit['relationship'], ENT_QUOTES, 'UTF-8');
            }

            echo '<div class="visit-details-visit__labels__container">';
            echo '<p class="container__text">Relationship: ' . $relationshipText . '</p>';
            echo '</div>';

            echo '<div class="visit-details-visit__labels__container">';
            echo '<p class="container__text">Source of income: ' . htmlspecialchars($visit['source_of_income'], ENT_QUOTES, 'UTF-8') . '</p>';
            echo '</div>';

            switch ($visit['witnesses']) {
              case 'police_guard':
                $witnessText = 'police guard';
                break;
              case 'legal_guardian':
                $witnessText = 'legal guardian';
                break;
              default:
                $witnessText = $visit['witnesses'];
            }

            echo '<div class="visit-details-visit__labels__container">';
            echo '<p class="container__text">Witnesses: ' . $witnessText . '</p>';
            echo '</div>';
            echo '</div>';
            echo '<div class="visit-details-visit__labels__container">';
            echo '<div class="visit-details-visit__labels__container">';
            switch ($visit['items_offered_by_inmate']) {
              case '':
                $itemsOfferedByInmate = 'none';
                break;
              default:
                $itemsOfferedByInmate = htmlspecialchars($visit['items_offered_by_inmate'], ENT_QUOTES, 'UTF-8');
                break;
            }
            echo '<p class="container__text">Items offered by inmate: ' . $itemsOfferedByInmate . '</p>';
            echo '</div>';

            echo '<div class="visit-details-visit__labels__container">';
            switch ($visit['items_provided_to_inmate']) {
              case '':
                $itemsProvidedToInmate = 'none';
                break;
              default:
                $itemsProvidedToInmate = htmlspecialchars($visit['items_provided_to_inmate'], ENT_QUOTES, 'UTF-8');
                break;
            }
            echo '<p class="container__text">Items provided to inmate: ' . $itemsProvidedToInmate . '</p>';
            echo '</div>';
            echo '</div>';
            echo '<div class="visit-details-visit__labels__container">';
            echo '<div class="visit-details-visit__labels__container">';
            
            echo '<p class="container__text">Visit nature: ' . htmlspecialchars($visit['visit_nature'], ENT_QUOTES, 'UTF-8') . '</p>';
            echo '</div>';

            echo '<div class="visit-details-visit__labels__container">';
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
      </div>
    </div>
    <nav class="button-container">
      <a href="../VisitHistory/history.php" class="visit-details__buttons__back">Back</a>
    </nav>
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
