<?php
session_start();
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$numToDuplicate = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../../src/styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>Inmates Panel</title>
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
  <main class="inmate-panel-main">
    <div class="inmate-panel-main-container">
      <div class="inmate-table">
        <div class="main-page-title">
          Inmates
        </div>
        <ol class="inmate-panel__list">
          <?php
          $noresults = 0;

          // Set the base URL for the API endpoint
          $base_url = "http://localhost/DeAd-web-Project/src/InmatesPanel/get_inmates.php";

          // Initialize cURL session
          $curl = curl_init();

          // Set cURL options
          curl_setopt($curl, CURLOPT_URL, $base_url);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Cookie: auth_token=' . $_COOKIE['auth_token'], // Pass the JWT token as a cookie
          ]);

          // Execute the cURL request
          $curl_response = curl_exec($curl);

          // Check for errors
          if ($curl_response === false) {
            $error_message = curl_error($curl);
            echo "cURL Error: $error_message";
            exit;
          }
          curl_close($curl);
          $response = json_decode($curl_response, true);
          if (empty($response)) {
            // No data retrieved from the API, display a message
            echo '<div class="inmate-panel-not-found">';
            echo '<h3>The database for the inmates is empty</h3>';
            echo '</div>';
            $noresults = 1;
          }
          else
          {
            // pagination logic
            $numOfEntriesPerPage = 3;
            $totalEntries = count($response);
            $totalPages = ceil($totalEntries / $numOfEntriesPerPage);
            $offset = ($page - 1) * $numOfEntriesPerPage;

            // Display inmates for the current page
            $inmatesToDisplay = array_slice($response, $offset, $numOfEntriesPerPage);

            if ($page == $totalPages && count($inmatesToDisplay) < 3) {
              // the number of element duplicates needed
              $numToDuplicate = 3 - count($inmatesToDisplay);

              // duplicate last user to fill the remaining slots
              $lastInmate = end($inmatesToDisplay);
              for ($i = 0; $i < $numToDuplicate; $i++) {
                $inmatesToDisplay[] = $lastInmate;
              }
            }

            foreach ($inmatesToDisplay as $index => $inmate) {
              echo '<li>';
              if ($page == $totalPages && $index >= count($inmatesToDisplay) - $numToDuplicate) {
                echo '<div class="table-element-duplicate">';
              } else {
                echo '<div class="table-element">';
              }
              echo '<div class="inmate-panel__list__show__name">';
              echo '<p class="inmate-panel__list__show__label">';
              echo 'Inmate name:';
              echo '<span class="inmate-panel__list__show__info"> ' . $inmate['inmate_name'] . '</span>';
              echo '</p>';
              echo '</div>';
              echo '<div class="inmate-panel__list__show__element-value">';
              echo '<p class="inmate-panel__list__show__label">';
              echo 'Start date:';
              echo '<span class="inmate-panel__list__show__info"> ' . $inmate['sentence_start_date'] . '</span>';
              echo '</p>';
              echo '</div>';
              echo '<div class="inmate-panel__list__show__element-value">';
              echo '<p class="inmate-panel__list__show__label">';
              echo 'Sentence category:';
              echo '<span class="inmate-panel__list__show__info"> ' . $inmate['sentence_category'] . '</span>';
              echo '</p>';
              echo '</div>';
              echo '<div class="inmate-panel__list__show__buttons">';
              echo '<button class="inmate-panel__list__show__buttons__edit">';
              $inmate_href = "inmateEdit.php?inmate_id=" . $inmate['inmate_id'];
              echo '<a href=' . $inmate_href . '>';
              echo '<img src="../../assets/visitormain/edit-icon.svg" alt="edit button"/>';
              echo '</a>';
              echo '</button>';
              echo '<button class="inmate-panel__list__show__buttons__delete" inmate_id_data="' . $inmate['inmate_id'] . '">';
              echo '<img src="../../assets/visitormain/delete-icon.svg" alt="delete button" />';
              echo '</button>';
              echo '</div>';
              echo '</li>';
            }
          }
          ?>
        </ol>
      </div>
      <nav class="pagination-container">
        <?php if ($noresults == 0) : ?>
          <a href="../AdminMain/adminmain.php" class="inmate-panel__form__buttons__back">Back</a>
          <?php if ($page > 1) : ?>
            <a href="inmatespanel.php?page=<?php echo $page - 1; ?>" class="pagination-button-prev">Prev</a>
          <?php else : ?>
            <button class="pagination-button-disabled disabled">Prev</button>
          <?php endif; ?>
          <?php if ($page < $totalPages) : ?>
            <a href="inmatespanel.php?page=<?php echo $page + 1; ?>" class="pagination-button-next">Next</a>
          <?php else : ?>
            <button class="pagination-button-disabled disabled">Next</button>
          <?php endif; ?>
          <a href="addInmate.php" class="inmate-panel__form__buttons__add">
          Add inmate
          </a>
        <?php else : ?>
          <a href="../AdminMain/adminmain.php" class="visit-active__buttons__back">Back</a>
        <?php endif; ?>
        </nav>
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
  <script>
    const deleteButtons = document.querySelectorAll('.inmate-panel__list__show__buttons__delete');

    deleteButtons.forEach(button => {
      button.addEventListener('click', (event) => {
        event.preventDefault();

        const inmateId = button.getAttribute('inmate_id_data');
        const currentUrl = window.location.href;

        if (confirm('Are you sure you want to delete this inmate?')) {
          const xhr = new XMLHttpRequest();
          xhr.open('DELETE', 'deleteinmate.php?inmate_id=' + encodeURIComponent(inmateId), true);
          xhr.onload = function() {
            if (xhr.status === 200) {
              window.location.href = currentUrl;
            } else {
              alert('Error deleting inmate. Please try again.');
            }
          };
          xhr.send();
        } else {
          // nothing happens
        }
      });
    });
  </script>
</body>

</html>