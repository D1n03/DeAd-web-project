<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="You, as an admin, you can do actions which involve the users and inmates" />
  <link rel="stylesheet" href="../../src/styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>Admin Panel</title>
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
              <img class="person-icon" src="../../assets/header/person-icon.webp" alt="person-icon" <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
                                                                                                    echo 'onclick="toggleMenu()"';
                                                                                                  } ?> id="person-icon" />
            </a>
          </li>
        </ul>
        <?php
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['first_name']) && isset($_SESSION['last_name'])) :
        ?>
          <div class="sub-menu-wrap" id="subMenu">
            <div class="sub-menu">
              <div class="user-info">
                <img src="../../assets/header/person-icon.webp" alt="person-icon-sub" />
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
  <main class="admin-main">
    <div class="admin-main-container">
      <div class="main-menu">
        <div class="person__show">
          <div class="person__show-banner">
            <div class="delete-show">
              <div class="line line-1"></div>
              <div class="line line-2"></div>
            </div>
            <img src="../../assets/visitormain/person-icon.webp" alt="person-picture" class="person__show-photo" />
            <h1 class="person__show-title">Name Surname</h1>
          </div>
        </div>
        <form class="search-bar list-off">
          <input type="text" name="searchPerson" id="searchPerson" placeholder="Search..." />
          <button type="submit">
            <img src="../../assets/visitormain/search-icon.svg" alt="search bar" />
          </button>
          <ul class="result-list hidden"></ul>
        </form>
        <div class="search-options">
          <div class="radio-item">
            <input type="radio" name="searchType" id="searchUser" value="user" checked />
            <label for="searchUser">User</label>
          </div>
          <div class="radio-item">
            <input type="radio" name="searchType" id="searchInmate" value="inmate" />
            <label for="searchInmate">Inmate</label>
          </div>
        </div>
        <button id="edit-person" class="admin-main__aside__button admin-main__aside__button-edit">
          Edit
        </button>
        <button id="add-inmate" class="admin-main__aside__button admin-main__aside__button-add">
          Add inmate
        </button>
        <button id="export-data" class="admin-main__aside__button admin-main__aside__button-export">
          Export data
        </button>
      </div>
    </div>
  </main>
  <?php
  if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) :
  ?>
    <script src="../scripts/submenu.js"></script>
  <?php endif; ?>
  <script src="../scripts/navbar.js"></script>
</body>

</html>