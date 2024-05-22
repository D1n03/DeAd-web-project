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
  <title>Users Panel</title>
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
  <main class="user-panel-main">
    <div class="user-panel-main-container">
      <div class="user-table">
        <div class="main-page-title">
          Users
        </div>
        <ol class="user-panel__list">
          <?php
          $noresults = 0;

          // we use curl to make a request to the api
          $base_url = "localhost";
          $url = $base_url . "/DeAd-web-Project/src/UsersPanel/get_users.php";
          $curl = curl_init();
          // Set cURL options
          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Cookie: auth_token=' . $_COOKIE['auth_token'], // Pass the JWT token as a cookie
          ]);
          $curl_response = curl_exec($curl);
          curl_close($curl);
          $response = json_decode($curl_response, true);
          // we parse the response
          // if there are no users, display a message
          if (empty($response)) {
            echo '<div class="user-panel-not-found">';
            echo '<h3>The database for the users is empty</h3>';
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

            // Display users for the current page
            $usersToDisplay = array_slice($response, $offset, $numOfEntriesPerPage);

            if ($page == $totalPages && count($usersToDisplay) < 3) {
              // the number of element duplicates needed
              $numToDuplicate = 3 - count($usersToDisplay);

              // duplicate last user to fill the remaining slots
              $lastUser = end($usersToDisplay);
              for ($i = 0; $i < $numToDuplicate; $i++) {
                $usersToDisplay[] = $lastUser;
              }
            }

            foreach ($usersToDisplay as $index => $user) {
              echo '<li>';
              if ($page == $totalPages && $index >= count($usersToDisplay) - $numToDuplicate) {
                echo '<div class="table-element-duplicate">';
              } else {
                echo '<div class="table-element">';
              }
              if ($user['photo']) {
                echo '<img src="data:image/jpeg;base64,' . $user['photo'] . '" alt="user photo" class="user-panel__list__show__photo" />';
              } else {
                  echo '<img src="../../assets/visitormain/profile-icon-medium.png" alt="user photo" class="user-panel__list__show__photo" />';
              }
              echo '<div class="user-panel__list__show__name">';
              echo '<p class="user-panel__list__show__label">';
              echo 'Name:';
              echo '<span class="user-panel__list__show__info"> ' . $user['person_name'] . '</span>';
              echo '</p>';
              echo '</div>';
              echo '<div class="user-panel__list__show__element-value">';
              echo '<p class="user-panel__list__show__label">';
              echo 'Email:';
              echo '<span class="user-panel__list__show__info"> ' . $user['email'] . '</span>';
              echo '</p>';
              echo '</div>';
              echo '<div class="user-panel__list__show__element-value">';
              echo '<p class="user-panel__list__show__label">';
              echo 'Function:';
              echo '<span class="user-panel__list__show__info"> ' . $user['function'] . '</span>';
              echo '</p>';
              echo '</div>';
              echo '<div class="user-panel__list__show__buttons">';
              echo '<button class="user-panel__list__show__buttons__edit">';
              $user_href = "userEdit.php?user_id=" . $user['user_id'];
              echo '<a href=' . $user_href . '>';
              echo '<img src="../../assets/visitormain/edit-icon.svg" alt="edit button"/>';
              echo '</a>';
              echo '</button>';
              echo '<button class="user-panel__list__show__buttons__delete" user_id_data="' . $user['user_id'] . '">';
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
          <?php if ($page > 1) : ?>
            <a href="userspanel.php?page=<?php echo $page - 1; ?>" class="pagination-button-prev">Prev</a>
          <?php else : ?>
            <button class="pagination-button-disabled disabled">Prev</button>
          <?php endif; ?>
          <a href="../AdminMain/adminmain.php" class="user-panel__form__buttons__back">Back</a>
          <?php if ($page < $totalPages) : ?>
            <a href="userspanel.php?page=<?php echo $page + 1; ?>" class="pagination-button-next">Next</a>
          <?php else : ?>
            <button class="pagination-button-disabled disabled">Next</button>
          <?php endif; ?>
        <?php else : ?>
          <a href="../AdminMain/adminmain.php" class="visit-active__buttons__back">Back</a>
        <?php endif; ?>
      </nav>
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
    const deleteButtons = document.querySelectorAll('.user-panel__list__show__buttons__delete');

    deleteButtons.forEach(button => {
      button.addEventListener('click', (event) => {
        event.preventDefault();

        const userId = button.getAttribute('user_id_data');
        const currentUrl = window.location.href;

        if (confirm('Are you sure you want to delete this user?')) {
          const xhr = new XMLHttpRequest();
          xhr.open('DELETE', `deleteuser.php?user_id=${encodeURIComponent(userId)}`, true);
          xhr.onload = function() {
            if (xhr.status === 200) {
              window.location.href = currentUrl;
            } else {
              alert('Error deleting user. Please try again.');
            }
          };
          xhr.send();
        }
      });
    });
  </script>
</body>

</html>