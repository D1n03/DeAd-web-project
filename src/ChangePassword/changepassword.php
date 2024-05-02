<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: ../Login/login.php");
    exit;
}

if (isset($_GET['email'])) {
    $email = $_GET['email'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="In case you need to change the password, you can provide a valid one to replace it." />
  <link rel="stylesheet" href="../styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>Change Password</title>
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
                  <img src="../assets/header/person-icon.webp" alt="person-icon-sub" />
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

  <main class="changepassword">
        <div class="container">
            <h1 class="container__title">Change Your Password</h1>
            <form class="container__form" id="change-password-form" action="changepassword_script.php" method="POST">
                <div class="container__form-field">
                    <input id="current_password" required type="password" name="current_password" placeholder="Current Password" />
                    <p class="validation-error current-password-error"></p>
                </div>
                <div class="container__form-field">
                    <input id="new_password" required type="password" name="new_password" placeholder="New Password" />
                    <p class="validation-error new-password-error"></p>
                </div>
                <div class="container__form-field">
                    <input id="confirm_new_password" required type="password" name="confirm_new_password" placeholder="Confirm New Password" />
                    <p class="validation-error confirm-new-password-error"></p>
                </div>
                <?php
                if (isset($_GET['error'])) {
                    if ($_GET['error'] == 1) {
                        echo '<p class="error">Current password is incorrect.</p>';
                    } elseif ($_GET['error'] == 2) {
                        echo '<p class="error">New passwords do not match.</p>';
                    }
                } else if (isset($_GET['strength'])) {
                  if ($_GET['strength'] == 0) {
                    echo '<p class="error"> Password must contain at least 8 characters, a number, uppercase and lowercase letters</p>';
                  } else {
                    echo '<p class="error" style="color: green;">Password is strong!</p>';
                  }
                } else if (isset($_GET['password_change_success'])) {
                  if ($_GET['password_change_success'] == 1) {
                    echo "<p class='success'>Password changed successfully!</p>";
                    echo "<meta http-equiv='refresh' content='1;url=../Profile/profile.php'>";
                  }
                }
                ?>
                <div class="container__form-buttons">
                    <button type="submit" class="container__form-submit">Change Password</button>
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