<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta
      name="description"
      content="In case you forgot the password, you can provide your account's email in order to reset it."
    />
    <link rel="stylesheet" href="../src/styles/css/styles.css" />
    <link rel="icon" href="../assets/header/police-icon.svg" />
    <title>Forgot Password</title>
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

    <main class="recovery">
      <div class="container">
        <h1 class="container__title">Account Recovery</h1>
        <p class="container__text">
            Enter your email address associated with your account and we'll send you a token to reset your password.
          </p>
        <form class="container__form" id="recover-form">
            <div class="container__form-field">
              <input
                id="email"
                required
                type="text"
                name="email"
                placeholder="Email"
              />
              <p class="validation-error email-error"></p>
            </div>
            <div class="container__form-buttons">
              <button
                type="button"
                class="container__form-submit-signup"
                onclick="location.href = './resetpassword.php';"
              >
                Next
              </button>
            </div>
          </form>
      </div>
    </main>
  </body>
</html>
