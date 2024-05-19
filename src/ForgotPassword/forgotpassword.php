<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="In case you forgot the password, you can provide your account's email in order to reset it." />
  <link rel="stylesheet" href="../../src/styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
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

  <main class="recovery">
    <div class="container">
      <h1 class="container__title">Account Recovery</h1>
      <p class="container__text">
        Enter your email address associated with your account and we'll send you a link to reset your password.
      </p>
      <form class="container__form" id="recover-form">
        <div class="container__form-field">
          <input id="email" required type="text" name="email" autocomplete='on' placeholder="Email" />
          <p class="validation-error email-error"></p>
        </div>
        <div class="messages">
            <p class="error-message" id="error-message"></p>
            <p class="success-message" id="success-message"></p>
        </div>
        <div class="container__form-buttons">
          <button type="submit" class="container__form-submit-signup">
            Send
          </button>
        </div>
      </form>
    </div>
  </main>
  <?php
  if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) :
  ?>
    <script src="../scripts/submenu.js"></script>
    <script src="../scripts/logout.js"></script>
  <?php endif; ?>
  <script src="../scripts/navbar.js"></script>
  <script>
      document.addEventListener('DOMContentLoaded', function () {
      function submitRecoveryForm(formData) {
          fetch('forgotpass_script.php', {
              method: 'POST',
              body: formData
          })
          .then(response => response.json().then(data => ({ status: response.status, body: data })))
          .then(response => {
              const errorMessage = document.querySelector('.error-message');
              const successMessage = document.querySelector('.success-message');

              errorMessage.innerHTML = '';
              successMessage.innerHTML = '';

              if (response.status === 200) {
                  successMessage.innerHTML = '<p class="success">' + response.body.message + '</p>';
                  setTimeout(() => {
                      window.location.href = '../Index/index.php';
                  }, 2000);
              } else {
                  errorMessage.innerHTML = '<p class="error">' + response.body.message + '</p>';
              }
          })
          .catch(error => {
              const errorMessage = document.querySelector('.error-message');
              const successMessage = document.querySelector('.success-message');
              
              errorMessage.innerHTML = '<p class="error">An error occurred: ' + error.message + '</p>';
              successMessage.innerHTML = '';
          });
      }

      function handleFormSubmit(event) {
          event.preventDefault();
          const formData = new FormData(event.target);
          submitRecoveryForm(formData);
      }

      const recoverForm = document.getElementById('recover-form');
      if (recoverForm) {
          recoverForm.addEventListener('submit', handleFormSubmit);
      }
  });
  </script>
</body>

</html>