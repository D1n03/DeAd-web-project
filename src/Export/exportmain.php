<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="You, as an admin, you can export data about inmates, users and visits" />
  <link rel="stylesheet" href="../../src/styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>Export data</title>
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

  <main class="export__main">
    <form class="export__main__form " enctype="multipart/form-data" id="export-form">
      <div class="export__main__form__labels">
        <div class="export__form__labels__title">
          Export data
        </div>
        <div class="container__form-field">
          <label class="component__label-title" for="export">Export data for: </label>
          <div class="form-export-group">
            <select class="form-input" id="export" name="export">
              <option name="export" value="inmates">Inmates</option>
              <option name="export" value="users">Users</option>
              <option name="export" value="all_visits">All Visits</option>
            </select>
          </div>
        </div>
        <div class="container__form-field">
          <label class="component__label-title">Sort By:</label>
          <div class="form-export-group" id="sortOptions">
            <!-- sorting options will be dynamically added here -->
          </div>
        </div>
        <div class="container__form-field">
          <label class="component__label-title">Format: </label>
          <div class="form-export-group">
            <label>
              <input type="radio" name="format" value="json" required>JSON</label>
            <label>
              <input type="radio" name="format" value="csv">CSV</label>
            <label>
              <input type="radio" name="format" value="html">HTML</label>
          </div>
        </div>
      </div>
      <div class="export__form__buttons">
        <a href="../AdminMain/adminmain.php" class="export__form__buttons__back">Back</a>
        <button type="submit" class="export__form__buttons__submit">
          Export
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
    document.addEventListener("DOMContentLoaded", function() {
      const exportSelect = document.getElementById("export");
      const sortOptions = document.getElementById("sortOptions");

      function toggleSortOptions() {
        sortOptions.innerHTML = "";

        if (exportSelect.value === "users") {
          sortOptions.innerHTML = '<label><input type="radio" name="sorted" value="name"> Name</label>';
        } else if (exportSelect.value === "inmates") {
          sortOptions.innerHTML = '<label><input type="radio" name="sorted" value="name"> Name</label>';
          sortOptions.innerHTML += '<label><input type="radio" name="sorted" value="sentence_start_date"> Sentence start date</label>';
          sortOptions.innerHTML += '<label><input type="radio" name="sorted" value="sentence_duration"> Sentence duration</label>';
        } else if (exportSelect.value === "all_visits") {
          sortOptions.innerHTML = '<label><input type="radio" name="sorted" value="date"> Date</label>';
          sortOptions.innerHTML += '<label><input type="radio" name="sorted" value="visitor"> Visitor</label>';
          sortOptions.innerHTML += '<label><input type="radio" name="sorted" value="inmate"> Inmate</label>';
        }
      }

      toggleSortOptions();
      exportSelect.addEventListener("change", toggleSortOptions);

      document.getElementById("export-form").addEventListener("submit", function(event) {
        event.preventDefault();

        const exportValue = document.getElementById("export").value;
        const formatValue = document.querySelector('input[name="format"]:checked').value;
        const sortedValue = document.querySelector('input[name="sorted"]:checked') ? document.querySelector('input[name="sorted"]:checked').value : '';

        const params = new URLSearchParams({
          export: exportValue,
          format: formatValue,
          sorted: sortedValue
        });

        const url = 'export_all_data_script.php?' + params.toString();

        fetch(url, {
            method: 'GET',
            headers: {
              'Content-Type': 'application/json'
            }
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.blob();
          })
          .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `export_${exportValue}_${new Date().toISOString().slice(0, 10)}.${formatValue}`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
          })
          .catch(error => console.error('There was a problem with the fetch operation:', error));
      });
    });
  </script>
</body>

</html>