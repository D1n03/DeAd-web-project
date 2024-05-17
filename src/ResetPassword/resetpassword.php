<?php
session_start();
$token = $_GET["token"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Once you got the email, you can complete the form in order to reset your password by providing a new one" />
  <link rel="stylesheet" href="../../src/styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>Reset Password</title>
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

  <main class="recovery">
    <div class="container">
      <h1 class="container__title">Reset Password</h1>
      <p class="container__text">
        Enter your new password below.
      </p>
      <form class="container__form" id="recovery-form">
        <input type="hidden" name="token" id="token" value="<?= htmlspecialchars($token) ?>">
        <div class="container__form-field">
          <input id="password" required type="password" name="password" placeholder="Password" />
          <p class="validation-error password-error"></p>
        </div>
        <div class="container__form-field">
          <input id="password_confirm" required type="password" name="password_confirm" placeholder="Confirm Password" />
          <p class="validation-error password-error"></p>
        </div>
        <div class="messages">
          <p class="error-message" id="error-message"></p>
          <p class="success-message" id="success-message"></p>
        </div>
        <div class="container__form-buttons">
          <button type="submit" class="container__form-submit-signup">
            Reset
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
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const token = document.getElementById('token').value;

      fetch(`tokenvalidation.php?token=${token}`)
        .then(response => {
          return response.json().then(data => ({
            status: response.status,
            data: data
          }));
        })
        .then(({
          status,
          data
        }) => {
          if (status !== 200) {
            let reason = 3; // Default reason for unexpected error
            if (data.error === "Token is required.") {
              reason = 3; // Adjust the reason as needed
            } else if (data.error === "Invalid token.") {
              reason = 1; // Token doesn't exist
            } else if (data.error === "Token expired.") {
              reason = 2; // Token expired
            }
            window.location.href = `../Error/error.php?reason=${reason}`;
          }
        })
        .catch(error => {
          window.location.href = `../Error/error.php?reason=4`; // Unexpected error
        });

      function submitPasswordResetForm(formData) {
        const data = new URLSearchParams();
        formData.forEach((value, key) => {
          data.append(key, value);
        });

        fetch('resetpassword_script.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: data.toString()
          })
          .then(response => response.json().then(data => ({
            status: response.status,
            body: data
          })))
          .then(response => {
            const errorMessage = document.querySelector('.error-message');
            const successMessage = document.querySelector('.success-message');

            errorMessage.innerHTML = '';
            successMessage.innerHTML = '';

            if (response.status === 200) {
              successMessage.innerHTML = '<p class="success">' + response.body.message + '</p>';
              setTimeout(() => {
                window.location.href = '../Index/index.php';
              }, 1000);
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
        submitPasswordResetForm(formData);
      }

      const resetPasswordForm = document.getElementById('recovery-form');
      if (resetPasswordForm) {
        resetPasswordForm.addEventListener('submit', handleFormSubmit);
      }
    });
  </script>
</body>

</html>