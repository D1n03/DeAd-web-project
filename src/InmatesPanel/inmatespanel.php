<?php
session_start();
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
    <main class="inmate-panel-main">
        <div class="inmate-panel-main-container">
            <div class="inmate-table">
                <div class="main-page-title">
                    Inmates
                </div>
                <ol class="inmate-panel__list">
                    <?php
                    // we use curl to make a request to the api
                    $base_url = "localhost";
                    $url = $base_url . "/DeAd-web-Project/src/InmatesPanel/get_inmates.php";
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    // set the parameter id
                    curl_setopt($curl, CURLOPT_HTTPGET, true);
                    $curl_response = curl_exec($curl);
                    curl_close($curl);
                    $response = json_decode($curl_response, true);
                    // we parse the response
                    // if there are no appointments, display a message
                    if (empty($response)) {
                        // button to create an visits, copy paste lmao
                        echo '<div class="inmate-panel-not-found">';
                        echo '<h3>The database for the inmates is empty</h3>';
                        echo '</div>';
                        exit();
                    }
                    foreach ($response as $inmate) {
                        echo '<li>';
                        echo '<div class="table-element">';
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
                    ?>
                </ol>
            </div>
            <div class="inmate-panel__form__buttons">
                <a href="../AdminMain/adminmain.php" class="inmate-panel__form__buttons__back">Back</a>
                <a href="addInmate.php" class="inmate-panel__form__buttons__add">
                Add inmate
                </a>
            </div>
        </div>
    </main>
    <?php
    if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) :
    ?>
        <script src="../scripts/submenu.js"></script>
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