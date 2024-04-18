<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Your profile here on DeAd." />
    <link rel="stylesheet" href="../src/styles/css/styles.css" />
    <link rel="icon" href="../assets/header/police-icon.svg" />
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
              <a href="index.php" class="nav-link">
                <img
                  class="list__item-icon"
                  src="../assets/header/home-icon.svg"
                  alt="home-icon"
                />
                <p class="list__item-text">Home</p>
              </a>
            </li>
            <li class="list__item">
              <a href="about.php" class="nav-link">
                <img
                  class="list__item-icon"
                  src="../assets/header/about-icon.svg"
                  alt="about-icon"
                />
                <p class="list__item-text">About</p>
              </a>
            </li>
            <li class="list__item">
              <a href="help.php" class="nav-link">
                <img
                  class="list__item-icon"
                  src="../assets/header/help-icon.svg"
                  alt="help-icon"
                />
                <p class="list__item-text">Help</p>
              </a>
            </li>
            <li class="list__item">
              <a href="contact.php" class="nav-link">
                <img
                  class="list__item-icon"
                  src="../assets/header/contact-icon.svg"
                  alt="contact-icon"
                />
                <p class="list__item-text">Contact</p>
              </a>
            </li>
            <li class="list__item">
              <a href="#" class="profile-link">
                <img
                  class="person-icon"
                  src="../assets/header/person-icon.webp"
                  alt="person-icon"
                  onclick="toggleMenu()"
                  id="person-icon"
                />
              </a>
            </li>
          </ul>
          <div class="sub-menu-wrap" id="subMenu">
            <div class="sub-menu">
              <div class="user-info">
                <img
                  src="../assets/header/person-icon.webp"
                  alt="person-icon-sub"
                />
                <h2>Name Surname</h2>
              </div>
              <hr />
              <a href="profile.php" class="sub-menu-link">
                <img
                  src="../assets/header/profile-icon.png"
                  alt="profile-icon"
                />
                <p>Profile</p>
                <span>⯈</span>
              </a>
              <a href="#" class="sub-menu-link">
                <img
                  src="../assets/header/logout-icon.png"
                  alt="logout-icon"
                />
                <p>Logout</p>
                <span>⯈</span>
              </a>
            </div>
          </div>
        </nav>
      </div>
    </header>
    <script src="app.js"></script>

    <main class="profile">
      <div class="container">
        <h1 class="container__title">Profile</h1>
        <img
          class="person-icon"
          src="../assets/header/person-icon.webp"
          alt="person-icon"
        />
        <!-- todo have separate class for name -->
        <p class="container__name-surname">Name Surname</p>
        <p class="container__text">name.surname@gmail.com</p>
        <a class="link" href="">Edit Profile</a>
        <a class="link" onclick="location.href = './history.php';">Visit History</a>
      </div>
    </main>
  </body>
</html>
