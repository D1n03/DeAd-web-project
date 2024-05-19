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
  <main class="add-inmate">
    <form class="add-inmate__form" id="add-inmate-form-info" enctype="multipart/form-data">
      <div class="add-inmate__form__labels">
        <div class="add-inmate__form__labels__title">
          Edit inmate's data
        </div>
        <?php
        $base_url = "http://localhost";
        $url = $base_url . "/DeAd-web-Project/src/InmatesPanel/get_inmate_id.php?inmate_id=" . $_GET['inmate_id'];
        $curl = curl_init($url);

        if (isset($_COOKIE['auth_token'])) {
          curl_setopt($curl, CURLOPT_HTTPHEADER, array('Cookie: auth_token=' . $_COOKIE['auth_token']));
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        $curl_response = curl_exec($curl);

        // Check for cURL errors
        if ($curl_response === false) {
          $error_msg = curl_error($curl);
          curl_close($curl);
          echo "cURL Error: $error_msg";
          exit();
        }

        curl_close($curl);
        $response = json_decode($curl_response, true);

        // Check if response is valid and contains the expected data
        if ($response === null) {
          echo "Error: Invalid JSON response";
          var_dump($curl_response); // Debug the raw response
          exit();
        }

        if (!isset($response['inmate_id'])) {
          echo "Error: 'inmate_id' not found in response";
          var_dump($response); // Debug the parsed response
          exit();
        }

        // Send the inmate id to the next page
        echo "<input type='hidden' name='inmate_id' value='" . $response['inmate_id'] . "'>";
        ?>
        <div class="add-inmate__form__labels__container">
          <label class="form-text" for="first_name">First name:</label>
          <input class="form-input" id="first_name" type="text" autocomplete='on' name="first_name" required value="<?php echo $response['first_name']; ?>" />
        </div>

        <div class="add-inmate__form__labels__container">
          <label class="form-text" for="last_name">Last name:</label>
          <input class="form-input" id="last_name" type="text" autocomplete='on' name="last_name" required value="<?php echo $response['last_name']; ?>" />
        </div>

        <div class="add-inmate__form__labels__container">
          <label for="sentence_start_date" class="label-title">Sentence start date:</label>
          <input type="date" id="sentence_start_date" name="sentence_start_date" class="form-input" required value=<?php echo $response['sentence_start_date'] ?>>
        </div>

        <div class="add-inmate__form__labels__container">
          <label for="sentence_duration" class="label-title">Sentence duration (in days):</label>
          <input type="number" id="sentence_duration" name="sentence_duration" class="form-input" placeholder="Enter the sentence duration" min="1" step="1" value=<?php echo $response['sentence_duration'] ?> required>
        </div>

        <div class="add-inmate__form__labels__container">
          <label class="component__label-title" for="sentence_category">Sentence category: </label>
          <div class="add-inmate-group">
            <select class="form-input" id="sentence_category" name="sentence_category" required>
              <option value="Violent Crime" <?php echo ($response['sentence_category'] == 'Violent Crime') ? 'selected' : ''; ?>>Violent Crime</option>
              <option value="Armed robbery and assault" <?php echo ($response['sentence_category'] == 'Armed robbery and assault') ? 'selected' : ''; ?>>Armed robbery and assault</option>
              <option value="Manslaughter" <?php echo ($response['sentence_category'] == 'Manslaughter') ? 'selected' : ''; ?>>Manslaughter</option>
              <option value="Burglary and theft" <?php echo ($response['sentence_category'] == 'Burglary and theft') ? 'selected' : ''; ?>>Burglary and theft</option>
              <option value="Money laundering" <?php echo ($response['sentence_category'] == 'Money laundering') ? 'selected' : ''; ?>>Money laundering</option>
              <option value="Fraud and embezzlement" <?php echo ($response['sentence_category'] == 'Fraud and embezzlement') ? 'selected' : ''; ?>>Fraud and embezzlement</option>
              <option value="Carjacking" <?php echo ($response['sentence_category'] == 'Carjacking') ? 'selected' : ''; ?>>Carjacking</option>
              <option value="Terrorism" <?php echo ($response['sentence_category'] == 'Terrorism') ? 'selected' : ''; ?>>Terrorism</option>
              <option value="Illegal possesion of firearms" <?php echo ($response['sentence_category'] == 'Illegal possesion of firearms') ? 'selected' : ''; ?>>Illegal possesion of firearms</option>
            </select>
          </div>
        </div>

        <div class="add-inmate__form__labels__container-message">
          <div class="error-message"></div>
          <div class="success-message"></div>
        </div>
      </div>
      <div class="add-inmate__form__buttons">
        <a href="inmatespanel.php" class="add-inmate__form__buttons__back">Back</a>
        <button type="submit" class="add-inmate__form__buttons__submit">
          Submit
        </button>
      </div>
    </form>
  </main>
  <?php
  if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) :
  ?>
    <script src="../scripts/submenu.js"></script>
    <script src="../scripts/logout.js"></script>
  <?php endif; ?>
  <script src="../scripts/navbar.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      function submitEditInmateForm(formData) {
        fetch('inmateEdit_script.php', {
            method: 'PUT', // Use PUT method
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded' // Set Content-Type header
            },
            body: new URLSearchParams(formData).toString() // Serialize form data
          })
          .then(response => response.json())
          .then(data => {
            const successMessage = document.querySelector('.success-message');
            const errorMessage = document.querySelector('.error-message');

            successMessage.innerHTML = '';
            errorMessage.innerHTML = '';

            if (data.error) {
              errorMessage.innerHTML = '<p class="error">Error editing inmate: ' + data.error + '</p>'; // Display error message
            } else if (data.message && data.message === 'Inmate updated successfully') {
              successMessage.innerHTML = '<p class="success">Inmate edited successfully!</p>'; // Display success message
              setTimeout(function() {
                window.location.href = 'inmatespanel.php'; // Redirect after success
              }, 1000); // Redirect after 1 second
            } else {
              console.error('Unknown message:', data.message);
            }
          })
          .catch(error => console.error('Error:', error));
      }

      function handleFormSubmit(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        submitEditInmateForm(formData);
      }

      const editInmateForm = document.getElementById('add-inmate-form-info');
      if (editInmateForm) {
        editInmateForm.addEventListener('submit', handleFormSubmit);
      }
    });
  </script>
</body>

</html>