<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Please provide information about required below." />
  <link rel="stylesheet" href="../../src/styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>Visit Info</title>
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
  <main class="details">
    <form class="details__form-visit" enctype="multipart/form-data" id="visit-form">
      <div class="details__form-visit__labels">
        <h1 class="details__form-visit__labels__title">
          Create a visit
        </h1>
        <div class="details__form-visit__labels__container">
          <label for="first_name" class="form-text">First name of the inmate</label>
          <input type="text" id="first_name" name="first_name" class="form-input" autocomplete='on' placeholder="First name" required="required" />
        </div>

        <div class="details__form-visit__labels__container">
          <label for="last_name" class="form-text">Last name of the inmate</label>
          <input type="text" id="last_name" name="last_name" class="form-input" autocomplete='on' placeholder="Last name" required="required" />
        </div>

        <div class="details__form-visit__labels__container" style="align-items:center">
          <div class="form-text">Relationship with the inmate:</div>
          <div class="input-group">
            <label for="first_degree_relative">
              <input type="radio" name="relationship" value="first_degree_relative" id="first_degree_relative"> First-degree relative</label>
            <label for="second_degree_relative">
              <input type="radio" name="relationship" value="second_degree_relative" id="second_degree_relative"> Second-degree relative</label>
            <label for="psychologist">
              <input type="radio" name="relationship" value="psychologist" id="psychologist"> Psychologist</label>
            <label for="lawyer">
              <input type="radio" name="relationship" value="lawyer" id="lawyer"> Lawyer</label>
            <label for="friend">
              <input type="radio" name="relationship" value="friend" id="friend"> Friend</label>
          </div>
        </div>

        <div class="details__form-visit__labels__container" style="align-items:center">
          <div class="form-text">The nature of the visit:</div>
          <div class="input-group">
            <label>
              <input type="radio" name="visit_nature" value="parental"> Parental</label>
            <label>
              <input type="radio" name="visit_nature" value="friendship"> Friendship</label>
            <label>
              <input type="radio" name="visit_nature" value="lawyer"> Lawyership</label>
          </div>
        </div>

        <div class="details__form-visit__labels__container">
          <label class="form-text" for="level">Source of Income:</label>
          <select class="form-input" id="level" name="source_of_income">
            <option name="source_of_income" value="employed">Employed</option>
            <option name="source_of_income" value="self-employed">Self-employed</option>
            <option name="source_of_income" value="unemployed">Unemployed</option>
          </select>
        </div>

        <div class="details__form-visit__labels__container" style="align-items:center">
          <label class="form-text" for="image">Photo:</label>
          <input id="image" type="file" name="profile_photo" required="required" placeholder="Photo" accept="image/*">
        </div>

        <div class="details__form-visit__labels__container">
          <label for="date" class="v">Date:</label>
          <input type="date" name="date" id="date" class="form-input" required="required">
        </div>

        <div class="details__form-visit__labels__container" style="align-items:center">
          <?php
          ?>
          <label for="time-start" class="v">Time interval (max 3h)</label>
          <label for="time-end" class="form-text"></label>
          <input type="time" id="time-start" name="visit_time_start" min="08:00" max="16:00" class="form-input" style="height:2rem;width:75%;padding:0;text-align:center" required>
          <input type="time" id="time-end" name="visit_time_end" min="08:00" max="16:00" class="form-input" style="height:2rem;width:75%;padding:0;text-align:center" required>

        </div>
        <div class="messages">
            <p class="error-message" id="error-message"></p>
            <p class="success-message" id="success-message"></p>
        </div>
      </div>
      </div>
      <div class="details__form-visit__buttons">
        <a href="../VisitorMain/visitormain.php" class="details__form-visit__buttons__back">Back</a>
        <button type="submit" class="details__form-visit__buttons__submit">
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
  <script>
    document.getElementById('visit-form').addEventListener('submit', function(event) {
      event.preventDefault();

      const formData = new FormData(this);
      const errorMessageElement = document.getElementById('error-message');
      const successMessageElement = document.getElementById('success-message');

      fetch('visit_script.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          if (data.error === 'Invalid inmate name!') {
            errorMessageElement.innerHTML = '<p class="error">Invalid inmate name!</p>';
          } else if (data.error === 'Invalid file type!') {
            errorMessageElement.innerHTML = '<p class="error">Invalid file type!</p>';
          } else if (data.error === 'Visit time is exceeding the maximum duration!') {
            errorMessageElement.innerHTML = '<p class="error">Visit time is exceeding the maximum duration!</p>';
          } else if (data.error === 'Invalid start and end times!') {
            errorMessageElement.innerHTML = '<p class="error">Invalid start and end times!</p>';
          } else if (data.error === 'The inmate already has a visit at that time!') {
            errorMessageElement.innerHTML = '<p class="error">The inmate already has a visit at that time!</p>';
          } else if (data.error === 'Missing required fields') {
            errorMessageElement.innerHTML = '<p class="error">Missing required fields</p>';
          } else {
            errorMessageElement.innerHTML = '<p class="error">An unknown error occurred.</p>';
          }
          successMessageElement.textContent = ''; 
        } else if (data.message) {
          successMessageElement.innerHTML = '<p class="success">' + data.message + '</p>';
          errorMessageElement.textContent = ''; 
          setTimeout(function() {
            window.location.href = '../VisitorMain/visitormain.php';
          }, 1000); 
        }
      })
      .catch(error => console.error('Error:', error));
    });
  </script>
</body>

</html>