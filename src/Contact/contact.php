<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Contact us at DeAd! We'd like to hear your feedback." />
  <link rel="stylesheet" href="../../src/styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>Contact</title>
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

  <main class="contact">
    <div class="container">
      <h1 class="container__title">Contact Us</h1>
      <p class="container__text">
        Have some feedback for our services? Let us know by completing the
        online form below:
      </p>
      <form action="contact_script.php" method="POST" id="contact-form" class="container__form">
        <div class="container__form-field">
          <?php
          if (isset($_GET['name'])) {
            $name = $_GET['name'];
            echo "<input type='text' name='name' value='$name' required id='name'>";
          } else {
            echo "<input
                    id='name'
                    required
                    type='text'
                    name='name'
                    placeholder='Name'
                  />";
          }
          ?>
          <p class="validation-error name-error"></p>
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
          <!-- <textarea id="feedbackMessage" name="feedback" placeholder="Feedback" rows="4" cols="50"></textarea> -->
          <?php
          if (isset($_GET['feedback'])) {
            $feedback = $_GET['feedback'];
            echo "<textarea name='feedback' required id='feedbackMessage' rows='4' cols='50'>$feedback</textarea>";
          } else {
            echo "<textarea id='feedbackMessage' required name='feedback' placeholder='Feedback' rows='4' cols='50'></textarea>";
          }
          ?>
        </div>
        <div class="container__form-buttons">
          <button type="submit" class="container__form-submit">Submit</button>
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