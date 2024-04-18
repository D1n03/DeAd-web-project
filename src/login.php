<?php
include("Utils/Connection.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Welcome to our Detention Admin website's Login page. Access your account securely for personalized experiences. Log in with your credentials to unlock a host of tailored features and join our community for a unique journey." />
  <link rel="stylesheet" href="../src/styles/css/styles.css" />
  <link rel="icon" href="../assets/header/police-icon.svg" />
  <title>Login</title>
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
              <img class="person-icon" src="../assets/header/person-icon.webp" alt="person-icon" onclick="toggleMenu()" id="person-icon" />
            </a>
          </li>
        </ul>
        <div class="sub-menu-wrap" id="subMenu">
          <div class="sub-menu">
            <div class="user-info">
              <img src="../assets/header/person-icon.webp" alt="person-icon-sub" />
              <h2>Name Surname</h2>
            </div>
            <hr />
            <a href="profile.php" class="sub-menu-link">
              <img src="../assets/header/profile-icon.png" alt="profile-icon" />
              <p>Profile</p>
              <span>⯈</span>
            </a>
            <a href="#" class="sub-menu-link">
              <img src="../assets/header/logout-icon.png" alt="logout-icon" />
              <p>Logout</p>
              <span>⯈</span>
            </a>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <main class="login">
    <div class="container">
      <h1 class="container__title">Login to your DeAd account</h1>
      <form class="container__form" id="login-form" action="login_script.php" method="POST">
        <div class="container__form-field">
          <?php
          if (isset($_GET['email'])) {
            $email = $_GET['email'];
            echo "<input id='email' required type='email' name='email' value='$email' />";
          } else {
            echo "<input id='email' required type='email' name='email' placeholder='Email' />";
          }
          ?>
          <p class="validation-error email-error"></p>
        </div>
        <div class="container__form-field">
          <input id="password" required type="password" name="password" placeholder="Password" />
          <p class="validation-error password-error"></p>
        </div>
        <div class="forgot-pass" onclick="location.href = './forgotpassword.php';">
          Forgot Password?
        </div>
        <?php
        if (isset($_GET['error'])) {
          if ($_GET['error'] == 1) {
            echo '<p class="error">Invalid email or password</p>';
          }
        }

        ?>
        <div class="container__form-buttons">
          <button type="submit" class="container__form-submit">Log In</button>
          <button type="button" class="container__form-submit-signup" onclick="location.href = './signup.php';">
            Sign Up
          </button>
        </div>
      </form>
    </div>
  </main>
  <script src="app.js"></script>
  <script src="../public/scripts/login.js"></script>
</body>

</html>