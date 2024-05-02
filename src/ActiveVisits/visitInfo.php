<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Please provide information regarding the visit." />
  <link rel="stylesheet" href="../../src/styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>Visit Info</title>
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
  <main class="details">
    <form class="details__form-visit" action="visitInfo_script.php" method="POST" id="visit-form-info">
      <div class="details__form-visit__labels">
        <h1 class="details__form-visit__labels__title">
          Details regarding the visit
        </h1>
        <?php
          $base_url = "localhost";
          $url = $base_url . "/DeAd-web-Project/src/ActiveVisits/get_visit_id.php" . "?visit_id=" . $_GET['visit_id'];
          $curl = curl_init($url);

          if(isset($_SESSION['token']))
          {
              curl_setopt($curl,CURLOPT_HTTPHEADER,array('Authorization: Bearer '.$_SESSION['token']));
          }

          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_HTTPGET, true);
          $curl_response = curl_exec($curl);
          curl_close($curl);
          $response = json_decode($curl_response, true);

          //send the appointment id to the next page
          echo "<input type='hidden' name='visit_id' value='" . $response['visit_id'] . "'>";
          ?>
        <div class="details__form-visit__labels__container">
          <label class="form-text" for="objectData">The items offered by the inmate</label>
          <input class="form-input" id="items_provided" type="text" name="itemsFrom"/>
        </div>

        <div class="details__form-visit__labels__container">
          <label class="form-text" for="inmateMood">The items provided to the inmate</label>
          <input class="form-input" id="items_provided" type="text" name="itemsTo"/>
        </div>

        <div class="details__form-visit__labels__container">
          <label class="form-text" for="summary">The summary of the discussions</label>
          <textarea class="form-textarea" required id="summary" maxlength="255" name="summary"></textarea>
        </div>
        <div class="details__form-visit__labels__container">
          <label class="form-text" for="inmate_health">Inmate's health: </label>
          <select class="form-input" id="inmate_health" name="inmate_health" required="required">
            <option value="ok" name="inmate_health">Ok</option>
            <option value="bad" name="inmate_health">Bad</option>
            <option value="good" name="inmate_health">Good</option>
          </select>
          <div class="details__form-visit__labels__container" style="align-items:center">
            <label class="form-text">Witnesses: </label>
            <div class="input-group">

              <label for="police">
                <input type="radio" name="witnesses" value="relative" id="police"> Police Guard</label>
              <label for="police">
                <input type="radio" name="witnesses" value="legal_gurdian" id="legal_gurdian"> Legal
                Guardian</label>
              <label for="doctor">
                <input type="radio" name="witnesses" value="doctor" id="doctor"> Doctor</label>
            </div>
          </div>
        </div>
      </div>
      <div class="details__form-visit__buttons">
        <a href="activevisits.php" class="details__form-visit__buttons__back">Back</a>
        <button type="submit" class="details__form-visit__buttons__submit">
          Submit
        </button>
      </div>
    </form>
  </main>
  <?php
  if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) :
  ?>
    <script src="../scripts/submenu.js"></script>
  <?php endif; ?>
  <script src="../scripts/navbar.js"></script>
</body>

</html>