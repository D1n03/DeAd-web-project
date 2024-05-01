<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Join DeAd website now! Sign up for free on our Signup page to unlock exciting features. It's fast and simple. Set up your profile, connect with others, and start your journey. Experience the power of DeAd and let us assist you in fulfilling your needs.." />
  <link rel="stylesheet" href="../../src/styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>Sign Up</title>
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
              <img class="person-icon" src="../../assets/header/person-icon.webp" alt="person-icon" <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
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
                <img src="../../assets/header/person-icon.webp" alt="person-icon-sub" />
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

  <main class="login">
    <div class="container">
      <h1 class="container__title">Sign Up</h1>
      <form action="signup_script.php" method="POST" id="signup-form" class="container__form">
        <div class="container__form-field">
          <?php
          if (isset($_GET['first_name'])) {
            $first_name = $_GET['first_name'];
            echo "<input type='text' name='first_name' value='$first_name' required id='first_name'>";
          } else {
            echo "<input
                    id='first_name'
                    required
                    type='text'
                    name='first_name'
                    placeholder='First name'
                  />";
          }
          ?>
          <p class="validation-error first_name-error"></p>
        </div>
        <div class="container__form-field">
          <?php
          if (isset($_GET['last_name'])) {
            $last_name = $_GET['last_name'];
            echo "<input type='text' name='last_name' value='$last_name' required id='last_name'>";
          } else {
            echo "<input
                    id='last_name'
                    required
                    type='text'
                    name='last_name'
                    placeholder='Last name'
                  />";
          }
          ?>
          <p class="validation-error last_name-error"></p>
        </div>
        <div class="container__form-field">
          <?php
          if (isset($_GET['email'])) {
            $email = $_GET['email'];
            echo "<input type='email' name='email' value='$email' required id='email'>";
          } else {
            echo "<input
                    id='email'
                    required
                    type='email'
                    name='email'
                    placeholder='Email'
                  />";
          }
          ?>
          <p class="validation-error email-error"></p>
        </div>
        <div class="container__form-field">
          <input id="password" required type="password" name="password" placeholder="Password" />
          <p class="validation-error password-error"></p>
        </div>
        <div class="container__form-field">
          <input id="password_confirm" required type="password" name="password_confirm" placeholder="Confirm Password" />
          <p class="validation-error password-error"></p>
        </div>
        <?php

        if (isset($_GET['error'])) {
          if ($_GET['error'] == 1) {
            echo '<p class="error">Email already exists</p>';
          } else if ($_GET['error'] == 2) {
            echo '<p class="error">Passwords do not match</p>';
          } else if ($_GET['error'] == 3) {
            echo '<p class="error">Error. Failed to create account</p>';
          }
        } else if (isset($_GET['strength'])) {
          if ($_GET['strength'] == 0) {
            echo '<p class="error"> Password must contain at least 8 characters, a number, uppercase and lowercase letters</p>';
          } else {
            echo '<p class="error" style="color: green;">Password is strong!</p>';
          }
        } else if (isset($_GET['success'])) {
          if ($_GET['success'] == 1) {
            echo "<p class='success'>Account created successfully!</p>";
            echo "<meta http-equiv='refresh' content='1;url=../Login/login.php'>";
          }
        }
        ?>
        <div class="container__form-buttons">
          <button type="submit" class="container__form-submit-signup">
            Sign up
          </button>
        </div>
      </form>
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