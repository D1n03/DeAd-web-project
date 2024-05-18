<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Change data about a visit." />
  <link rel="stylesheet" href="../../src/styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>Edit Visit</title>
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
  <main class="edit-visit">
    <form class="edit-visit__form" action="visitEdit_script.php" method="POST" id="visit-form-info" enctype="multipart/form-data">
      <div class="edit-visit__form__labels">
        <div class="edit-visit__form__labels__title">
          Edit visit
        </div>
        <?php
          $base_url = "localhost";
          $url = $base_url . "/DeAd-web-Project/src/VisitsPanel/get_visit_id.php" . "?visit_id=" . $_GET['visit_id'];
          $curl = curl_init($url);

          if (isset($_COOKIE['auth_token'])) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Cookie: auth_token=' . $_COOKIE['auth_token']));
          }

          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_HTTPGET, true);
          $curl_response = curl_exec($curl);
          curl_close($curl);
          $response = json_decode($curl_response, true);

          //send the visit id to the next page
          echo "<input type='hidden' name='visit_id' value='" . $response['visit_id'] . "'>";
          ?>

        <div class="edit-visit__form__labels__container">
          <label class="form-text" for="visitor_first_name">Visitor's first name: </label>
          <input class="form-input" id="visitor_first_name" type="text" name="visitor_first_name" value="<?php echo $response['user_first_name']; ?>" disabled style="color: white"; />
          </select>
        </div>

        <div class="edit-visit__form__labels__container">
          <label class="form-text" for="visitor_last_name">Visitor's last name: </label>
          <input class="form-input" id="visitor_last_name" type="text" name="visitor_last_name" value="<?php echo $response['user_last_name']; ?>" disabled style="color: white"; />
          </select>
        </div>

        <input type="hidden" name="inmate_first_name" value="<?php echo $response['inmate_first_name']; ?>">
        <input type="hidden" name="inmate_last_name" value="<?php echo $response['inmate_last_name']; ?>">

        <div class="edit-visit__form__labels__container">
          <label class="form-text" for="inmate_first_name">Inmate's first name: </label>
          <input class="form-input" id="inmate_first_name" type="text" name="inmate_first_name" value="<?php echo $response['inmate_first_name']; ?>" disabled style="color: white"; />
          </select>
        </div>

        <div class="edit-visit__form__labels__container">
          <label class="form-text" for="inmate_last_name">Inmate's last name: </label>
          <input class="form-input" id="inmate_last_name" type="text" name="inmate_last_name" value="<?php echo $response['inmate_last_name']; ?>" disabled style="color: white"; />
          </select>
        </div>

        <div class="edit-visit__form__labels__container">
          <label class="form-text" for="relationship">Relationship: </label>
          <input class="form-input" id="relationship" type="text" name="relationship" value="<?php echo $response['relationship']; ?>" disabled style="color: white"; />
          </select>
        </div>

        <div class="edit-visit__form__labels__container">
          <label class="form-text" for="source_of_income">Source of income </label>
          <input class="form-input" id="source_of_income" type="text" name="source_of_income" value="<?php echo $response['source_of_income']; ?>" disabled style="color: white"; />
          </select>
        </div>

        <div class="edit-visit__form__labels__container">
          <label for="date" class="v">Date:</label>
          <input type="date" name="date" id="date" class="form-input" value="<?php echo $response['date']; ?>" required="required">
        </div>

        <div class="edit-visit__form__labels__container" style="align-items:center">
          <?php
          ?>
          <label for="time-start" class="v">Time interval (max 3h)</label>
          <label for="time-end" class="form-text"></label>
          <input type="time" id="time-start" name="visit_time_start" min="08:00" max="16:00" class="form-input" value="<?php echo $response['visit_start']; ?>" style="height:2rem;width:75%;padding:0;text-align:center" required>
          <input type="time" id="time-end" name="visit_time_end" min="08:00" max="16:00" class="form-input" value="<?php echo $response['visit_end']; ?>" style="height:2rem;width:75%;padding:0;text-align:center" required>

        </div>

        <div class="edit-visit__form__labels__container">
          <label class="form-text" for="visit_nature">Visit's nature </label>
          <select class="form-input" id="visit_nature" name="visit_nature" required="required">
              <option value="parental" <?php if ($response['visit_nature'] == 'parental') echo 'selected'; ?>>Parental</option>
              <option value="friendship" <?php if ($response['visit_nature'] == 'friendship') echo 'selected'; ?>>Friendship</option>
              <option value="lawyer" <?php if ($response['visit_nature'] == 'lawyer') echo 'selected'; ?>>Lawywership</option>
          </select>
      </div>

        <div class="edit-visit__form__labels__container">
          <label class="form-text" for="inmate_health">Inmate's health: </label>
          <input class="form-input" id="inmate_health" type="text" name="inmate_health" value="<?php echo $response['health_status']; ?>" disabled style="color: white"; />
        </div>

        <div class="edit-visit__form__labels__container">
          <label class="form-text" for="items_offered">The items offered by the inmate</label>
          <input class="form-input" id="items_offered" type="text" name="itemsFrom" value="<?php echo $response['items_offered_by_inmate']; ?>" disabled style="color: white"; />
        </div>

        <div class="edit-visit__form__labels__container">
          <label class="form-text" for="items_provided">The items provided to the inmate</label>
          <input class="form-input" id="items_provided" type="text" name="itemsTo" value="<?php echo $response['items_provided_to_inmate']; ?>" disabled style="color: white";/>
        </div>

        <div class="edit-visit__form__labels__container">
          <label class="form-text" for="summary">The summary of the discussions</label>
          <textarea class="form-textarea" required id="summary" maxlength="255" name="summary" readonly ><?php echo $response['summary']; ?></textarea>
        </div>

        

        <div class="edit-visit__form__labels__container-message">
          <div class="error-message"></div>
          <div class="success-message"></div>
        </div>
      </div>
        <div class="edit-user__form__buttons">
          <a href="visitspanel.php" class="edit-user__form__buttons__back">Back</a>
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
        const visitForm = document.getElementById('visit-form-info');

        visitForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(visitForm);

            fetch('visitEdit_script.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const successMessage = document.querySelector('.success-message');
                    const errorMessage = document.querySelector('.error-message');

                    successMessage.innerHTML = '';
                    errorMessage.innerHTML = '';

                    if (data.error) {
                        errorMessage.innerHTML = '<p class="error">Error editing visit: ' + data.error + '</p>';
                    } else if (data.message && data.message === 'Visit updated successfully') {
                        successMessage.innerHTML = '<p class="success">Visit edited successfully!</p>';
                        setTimeout(function() {
                            window.location.href = 'visitspanel.php';
                        }, 1000); // Redirect after 1 second
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