<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Please provide information about yourself and any other visitors who may accompany you to visit a prisoner. A visitor could be a family member, lawyer, or psychologist, and they are required to furnish personal details to schedule a visit on the DeAd website." />
  <link rel="stylesheet" href="../src/styles/css/styles.css" />
  <link rel="icon" href="../assets/header/police-icon.svg" />
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
            <a href="index.php" class="nav-link">
              <img class="list__item-icon" src="../assets/header/home-icon.svg" alt="home-icon" />
              <p class="list__item-text">Home</p>
            </a>
          </li>
          <li class="list__item">
            <a href="about.php" class="nav-link">
              <img class="list__item-icon" src="../assets/header/about-icon.svg" alt="about-icon" />
              <p class="list__item-text">About</p>
            </a>
          </li>
          <li class="list__item">
            <a href="help.php" class="nav-link">
              <img class="list__item-icon" src="../assets/header/help-icon.svg" alt="help-icon" />
              <p class="list__item-text">Help</p>
            </a>
          </li>
          <li class="list__item">
            <a href="contact.php" class="nav-link">
              <img class="list__item-icon" src="../assets/header/contact-icon.svg" alt="contact-icon" />
              <p class="list__item-text">Contact</p>
            </a>
          </li>
          <li class="list__item">
            <a href="#" class="profile-link">
              <img class="person-icon" src="../assets/header/person-icon.webp" alt="person-icon" <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
                                                                                                    echo 'onclick="toggleMenu()"';
                                                                                                  } ?> id="person-icon" />
            </a>
          </li>
        </ul>
        <?php
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['first_name']) && isset($_SESSION['last_name'])) :
        ?>
          <div class="sub-menu-wrap" id="subMenu">
            <div class="sub-menu">
              <div class="user-info">
                <img src="../assets/header/person-icon.webp" alt="person-icon-sub" />
                <h2><?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?></h2>
              </div>
              <hr />
              <a href="profile.php" class="sub-menu-link">
                <img src="../assets/header/profile-icon.png" alt="profile-icon" />
                <p>Profile</p>
                <span>⯈</span>
              </a>
              <a href="logout_script.php" class="sub-menu-link">
                <img src="../assets/header/logout-icon.png" alt="logout-icon" />
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
    <form action="#" class="details__form-visit" id="visitForm">
      <div class="details__form-visit__labels">
        <h1 class="details__form-visit__labels__title">
          Details regarding the visit
        </h1>
        <div class="details__form-visit__labels__container">
          <label class="form-text" for="visitDate">The date of the visit*</label>
          <input class="form-input" required id="visitDate" type="date" />
          <p class="validation-error visitDate-error">I</p>
        </div>

        <div class="details__form-visit__labels__container">
          <label class="form-text" for="visitTime">The visit duration*</label>
          <input class="form-input" required id="visitTime" type="text" placeholder="e.g. 1h:30m" />
          <p class="validation-error visitTime-error">I</p>
        </div>

        <div class="details__form-visit__labels__container">
          <label class="form-text" for="objectData">The items provided by the inmate*</label>
          <input class="form-input" required id="objectData" type="text" />
          <p class="validation-error objectData-error">I</p>
        </div>

        <div class="details__form-visit__labels__container">
          <label class="form-text" for="prisonerMood">The status of the inmate*</label>
          <input class="form-input" required id="prisonerMood" type="text" />
          <p class="validation-error prisonerMood-error">I</p>
        </div>
        <div class="details__form-visit__labels__container">
          <label class="form-text" for="visitNature">The purpose of the visit*</label>
          <textarea class="form-textarea" required id="visitNature" maxlength="150"></textarea>
          <p class="validation-error visitNature-error">I</p>
        </div>

        <div class="details__form-visit__labels__container">
          <label class="form-text" for="summary">The summary of the discussions*</label>
          <textarea class="form-textarea" required id="summary" maxlength="300"></textarea>
          <p class="validation-error summary-error">I</p>
        </div>
      </div>
      <div class="details__form-visit__buttons">
        <a href="visitormain.php" class="details__form-visit__buttons__back">Back</a>
        <button type="submit" class="details__form-visit__buttons__submit">
          Submit
        </button>
      </div>
    </form>
  </main>
  <?php
  if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) :
  ?>
    <script src="scripts/submenu.js"></script>
  <?php endif; ?>
  <script src="scripts/navbar.js"></script>
</body>

</html>