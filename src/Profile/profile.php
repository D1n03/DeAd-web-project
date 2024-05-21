<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Your profile here on DeAd." />
  <link rel="stylesheet" href="../styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>Profile</title>
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

  <main class="profile">
    <div class="container">
      <h1 class="container__title">Profile</h1>
      <?php
      // check if account has photo
      if (isset($_SESSION['photo'])) {
        // display photo from session
        echo '<img class="profile-icon" src="data:image/jpeg;base64,' . base64_encode($_SESSION['photo']) . '" alt="User Photo" />';
      } else {
        echo '<img class="profile-icon" src="../../assets/header/person-icon.webp" alt="person-icon" />';
      }
      ?>
      <p class="container__name-surname"><?php echo isset($_SESSION['first_name']) ? $_SESSION['first_name'] : 'First Name'; ?> <?php echo isset($_SESSION['last_name']) ? $_SESSION['last_name'] : 'Last Name'; ?></p>
      <p class="container__text"><?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'Email'; ?></p>
      <a class="link" href="../EditProfile/editprofile.php">Edit Profile</a>
      <a class="link" id="changepassword" href="../ChangePassword/changepassword.php">Change Password</a>
      <?php
      if (isset($_SESSION['function']) && $_SESSION['function'] === 'user') {
          echo '<a class="link" href="../VisitHistory/history.php">Visit History</a>';
      }
      ?>
      
    </div>
  </main>
  <?php
  if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) :
  ?>
    <script src="../scripts/submenu.js"></script>
    <script src="../scripts/logout.js"></script>
  <?php endif; ?>
  <script src="../scripts/navbar.js"></script>
</body>

</html>