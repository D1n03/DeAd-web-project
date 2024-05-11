<?php
session_start();
require_once '../Utils/VisitHistory.php';

$numOfEntriesPerPage = 3;

// get the page number from the URL, default to 1 if not set
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// calculate the offset based on the page number (we start fetching entries starting from this point)
$offset = ($page - 1) * $numOfEntriesPerPage;

$visitHistory = VisitHistory::getVisitHistory(0, $offset, $numOfEntriesPerPage);

$totalEntries = VisitHistory::getTotalEntriesCount();

// total number of pages based on entries
$totalPages = ceil($totalEntries / $numOfEntriesPerPage);

// if the last page has fewer than 3 items, duplicate the last item to fill the row
if ($page == $totalPages && count($visitHistory) < $numOfEntriesPerPage) {

  $numToDuplicate = $numOfEntriesPerPage - count($visitHistory);
  $lastItem = end($visitHistory);

  for ($i = 0; $i < $numToDuplicate; $i++) {
      $visitHistory[] = $lastItem;
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="On this page you are able to see the history visits of your account, together with the posibility to export relevant data" />
  <link rel="stylesheet" href="../../src/styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>History</title>
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
              <img class="person-icon" src="../../assets/header/person-icon.webp" alt="person-icon" onclick="toggleMenu()" id="person-icon" />
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
              <a href="../logout_script.php" class="sub-menu-link">
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
  <main class="history">
    <div class="history-container">
      <div class="info-container">
        <div class="history__title">Your Visit History</div>
        <ul class="history__list">
          <?php

            foreach ($visitHistory as $index => $visit) { // $index keeps track of the current index in the loop
              echo '<li>';
              if ($page == $totalPages && $index >= count($visitHistory) - $numToDuplicate) {
                echo '<div class="history-element-duplicate">';
              } else {
                echo '<div class="history-element">';
              } 
              echo '<img src="data:image/jpeg;base64,' . $visit['photo'] . '" alt="prisoner photo" class="history__list__show__photo" />';
              echo '<div class="visit-info">';
              echo '<div class="history__list__show__name">';
              echo '<p class="history__list__show__label">Prisoner: <span class="history__list__show__info">' . $visit['first_name'] . ' ' . $visit['last_name'] . '</span></p>';
              echo '</div>';
              echo '<div class="drop-arrow">';
              echo '<span class="vBar"></span>';
              echo '<span class="vBar"></span>';
              echo '</div>';
              echo '<div class="visitor-main__list__show__dBirth">';
              echo '<p class="history__list__show__label">Date: <span class="history__list__show__info">' . $visit['date'] . '</span></p>';
              echo '</div>';
              echo '<div class="visitor-main__list__show__dBirth">';
              echo '<p class="history__list__show__label">';
              echo 'Duration:';
              echo '<span class="history__list__show__info"> ' . $visit['time_interval'] . '</span>';
              echo '</p>';
              echo '</div>';
              echo '</div>';
              echo '<div class="history__list__show__buttons">';
              echo '<a href="visit_details.php?id=' . $visit['visit_id'] . '" class="history__list__show__buttons__info">';
              echo '<img src="../../assets/visitormain/info-icon.svg" alt="info button" />';
              echo '</a>';
              echo '</div>';
              echo '</div>';
              echo '</li>';
          }
          ?>
        </ul>
        <nav class="pagination-visitor-container">
          <?php if ($page > 1) : ?>
            <a href="history.php?page=<?php echo $page - 1; ?>" class="pagination-visitor-button-prev">Prev</a>
          <?php else : ?>
            <button class="pagination-visitor-button-disabled disabled">Prev</button>
          <?php endif; ?>

          <?php if ($page < $totalPages) : ?>
            <a href="history.php?page=<?php echo $page + 1; ?>" class="pagination-visitor-button-next">Next</a>
          <?php else : ?>
            <button class="pagination-visitor-button-disabled disabled">Next</button>
          <?php endif; ?>
        </nav>
      </div>
    </div>
  </main>
  <?php
  if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) :
  ?>
    <script src="../scripts/submenu.js"></script>
  <?php endif; ?>
  <script src="../scripts/navbar.js"></script>
</body>

</html>