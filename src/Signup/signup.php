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

  <main class="login">
  <div class="container">
    <h1 class="container__title">Sign Up</h1>
    <form id="signup-form" class="container__form">
      <div class="container__form-field">
        <input id="first_name" required type="text" name="first_name" autocomplete="on" placeholder="First name" value="<?php echo isset($_GET['first_name']) ? $_GET['first_name'] : ''; ?>" />
        <p class="validation-error first_name-error"></p>
      </div>
      <div class="container__form-field">
        <input id="last_name" required type="text" name="last_name" autocomplete="on" placeholder="Last name" value="<?php echo isset($_GET['last_name']) ? $_GET['last_name'] : ''; ?>" />
        <p class="validation-error last_name-error"></p>
      </div>
      <div class="container__form-field">
        <input id="email" required type="email" name="email" autocomplete="on" placeholder="Email" value="<?php echo isset($_GET['email']) ? $_GET['email'] : ''; ?>" />
        <p class="validation-error email-error"></p>
      </div>
      <div class="container__form-field">
        <input id="password" required type="password" name="password" placeholder="Password" />
        <p class="validation-error password-error"></p>
      </div>
      <div class="container__form-field">
        <input id="password_confirm" required type="password" name="password_confirm" placeholder="Confirm Password" />
        <p class="validation-error password_confirm-error"></p>
      </div>
      <p class="error-message" id="error-message"></p>
      <p class="success-message" id="success-message"></p>
      <div class="container__form-buttons">
        <button type="submit" class="container__form-submit-signup">Sign up</button>
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
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('signup-form');

      form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(form);

        const data = new URLSearchParams();
        for (const pair of formData) {
          data.append(pair[0], pair[1]);
        }

        try {
          const response = await fetch('signup_script.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: data.toString()
          });

          const result = await response.json();
          handleResponse(response.status, result);
        } catch (error) {
          console.error('Error:', error);
          const errorMessage = document.querySelector('.error-message');
          const successMessage = document.querySelector('.success-message');

          errorMessage.innerHTML = '<p class="error">Error: ' + error.message + '</p>';
          successMessage.innerHTML = '';
        }
      });

      function handleResponse(status, data) {
        const errorMessageElement = document.querySelector('.error-message');
        const successMessageElement = document.querySelector('.success-message');

        errorMessageElement.innerHTML = '';
        successMessageElement.innerHTML = '';

        if (status === 201) {
          successMessageElement.innerHTML = '<p class="success">' + data.message + '</p>';
          setTimeout(() => {
            window.location.href = '../Login/login.php';
          }, 1000);
        } else {
          errorMessageElement.innerHTML = '<p class="error">' + data.message + '</p>';
        }
      }
    });
  </script>
</body>

</html>