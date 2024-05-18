<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Change data about a user's account." />
  <link rel="stylesheet" href="../../src/styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>Edit User</title>
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
  <main class="edit-user">
    <form class="edit-user__form" id="user-form-info" enctype="multipart/form-data">
      <div class="edit-user__form__labels">
        <div class="edit-user__form__labels__title">
          Edit user's account data
        </div>
        <?php
          $base_url = "localhost";
          $url = $base_url . "/DeAd-web-Project/src/UsersPanel/get_user_id.php" . "?user_id=" . $_GET['user_id'];
          $curl = curl_init($url);

          if (isset($_COOKIE['auth_token'])) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Cookie: auth_token=' . $_COOKIE['auth_token']));
          }

          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_HTTPGET, true);
          $curl_response = curl_exec($curl);
          curl_close($curl);
          $response = json_decode($curl_response, true);

          //send the appointment id to the next page
          echo "<input type='hidden' name='user_id' value='" . $response['user_id'] . "'>";
          ?>
        <div class="edit-user__form__labels__container">
            <label class="form-text" for="first_name">First name:</label>
            <input class="form-input" id="first_name" type="text" autocomplete='on' name="first_name" required value="<?php echo htmlspecialchars($response['first_name']); ?>" />
        </div>

        <div class="edit-user__form__labels__container">
            <label class="form-text" for="last_name">Last name:</label>
            <input class="form-input" id="last_name" type="text" autocomplete='on' name="last_name" required value="<?php echo htmlspecialchars($response['last_name']); ?>" />
        </div>

        <div class="edit-user__form__labels__container">
          <label class="form-text" for="email">Email:</label>
          <input class="form-input" id="email" type="email" autocomplete='on' name="email" required value= <?php echo $response['email'] ?> />
        </div>

        <div class="edit-user__form__labels__container">
          <div class="form-text">Photo:</div>
          <label class="form-text" for="new_photo">Upload a photo</label>
          <input type="file" id="new_photo" name="new_photo" accept="image/*">
        </div>  

        <div class="edit-user__form__labels__container">
          <label class="form-text" for="password">Password:</label>
          <input class="form-input" id="password" type="password" name="password" placeholder="Empty for no password change"/>
        </div>

        <div class="edit-user__form__labels__container">
          <label class="form-text" for="password_confirm">Password confirm:</label>
          <input class="form-input" id="password_confirm" type="password" name="password_confirm" placeholder="Empty for no password change"/>
        </div>

        <div class="edit-user__form__labels__container">
          <label class="form-text" for="function">Function: </label>
          <input class="form-input" id="function" type="text" name="function" value="<?php echo $response['function']; ?>" disabled style="color: white"; />
          </select>
        </div>
        <div class="edit-user__form__labels__container-message">
          <div class="error-message"></div>
          <div class="success-message"></div>
        </div>
      </div>
        <div class="edit-user__form__buttons">
          <a href="userspanel.php" class="edit-user__form__buttons__back">Back</a>
          <button type="submit" class="edit-user__form__buttons__submit">
            Submit
          </button>
        </div>
    </form>
  </main>
  <?php
  if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) :
  ?>
    <script src="../scripts/submenu.js"></script>
  <?php endif; ?>
  <script src="../scripts/navbar.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('user-form-info');

      form.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(form);

        fetch('userEdit_script.php', {
            method: 'POST',
            body: formData,
          })
          .then(response => response.json())
          .then(data => {
            const successMessage = document.querySelector('.success-message');
            const errorMessage = document.querySelector('.error-message');

            successMessage.innerHTML = '';
            errorMessage.innerHTML = '';

            if (data.error) {
              errorMessage.innerHTML = '<p class="error">Error updating user: ' + data.error + '</p>';
            } else if (data.message && data.message === 'User updated successfully') {
              successMessage.innerHTML = '<p class="success">User updated successfully!</p>'; 
              setTimeout(function() {
                window.location.href = './userspanel.php';
              }, 1000);
            } else {
              console.error('Unknown message:', data.message);
            }
          })
          .catch(error => console.error('Error:', error));
      });
    });
  </script>
</body>

</html>