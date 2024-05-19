<?php
include("../Utils/Connection.php");
session_start();

// Check if user is logged in
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    // Redirect the user to the login page if not logged in
    header("Location: ../Login/login.php");
    exit;
}

// Retrieve logged-in user's details
// TO DO, rework this so it an API? idk
if (isset($_SESSION['first_name']) && isset($_SESSION['last_name']) && isset($_SESSION['email'])) {
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $email = $_SESSION['email'];
} else {
    header("Location: ../Error/error.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Edit your profile's information." />
  <link rel="stylesheet" href="../../src/styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>Edit Profile</title>
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

  <main class="editprofile">
    <div class="container">
      <h1 class="container__title">Edit Profile</h1>
      <form class="container__form" id="edit-profile-form" enctype="multipart/form-data">

        <p class="container__text">First Name:</p>
        <div class="container__form-field">
          <input id="first_name" required type="text" name="first_name" autocomplete='on' required value="<?php echo $first_name; ?>" />
            <p class="validation-error first-name-error"></p>
        </div>

        <p class="container__text">Last Name:</p>
        <div class="container__form-field">
            <input id="last_name" required type="text" name="last_name" autocomplete='on' required value="<?php echo $last_name; ?>" />
            <p class="validation-error last-name-error"></p>
        </div>

        <p class="container__text">E-mail:</p>
        <div class="container__form-field">
            <input id="email" required type="email" name="email" autocomplete='on' required value="<?php echo $email; ?>" />
            <p class="validation-error email-error"></p>
        </div>

        <p class="container__text">Photo:</p>
        <div class="container__form-field">
            <label for="photo">Choose a photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*">
            <p class="validation-error photo-error"></p>
        </div>
        <div class="messages">
            <p class="error-message" id="error-message"></p>
            <p class="success-message" id="success-message"></p>
        </div>
        <div class="container__form-buttons">
          <button type="submit" class="container__form-submit">Submit Changes</button>
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
    function submitProfileForm(formData) {
        fetch('editprofile_script.php', {
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
                    window.location.href = '../Profile/profile.php';
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
        submitProfileForm(formData);
    }

    const profileForm = document.getElementById('edit-profile-form');
    if (profileForm) {
        profileForm.addEventListener('submit', handleFormSubmit);
    }
  });
</script>
</body>

</html>