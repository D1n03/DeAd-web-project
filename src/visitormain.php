<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Please provide information about yourself and any other visitors who may accompany you to visit a prisoner. A visitor could be a family member, lawyer, or psychologist, and they are required to furnish personal details to schedule a visit on the DeAd website." />
  <link rel="stylesheet" href="../src/styles/css/styles.css" />
  <link rel="icon" href="../assets/header/police-icon.svg" />
  <title>Visitor Main</title>
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
            <a href="index.php" class="nav-link">
              <img class="list__item-icon" src="../assets/header/home-icon.svg" alt="home-icon" />
              <p class="list__item-text">Home</p>
            </a>
          </li>
          <li class="list__item">
            <a href="about.php" class="nav-link">
              <img class="list__item-icon" src="../assets/header/about-icon.svg" alt="about-icon" />
              <p class="list__item-text">About</p>
            </a>
          </li>
          <li class="list__item">
            <a href="help.php" class="nav-link">
              <img class="list__item-icon" src="../assets/header/help-icon.svg" alt="help-icon" />
              <p class="list__item-text">Help</p>
            </a>
          </li>
          <li class="list__item">
            <a href="contact.php" class="nav-link">
              <img class="list__item-icon" src="../assets/header/contact-icon.svg" alt="contact-icon" />
              <p class="list__item-text">Contact</p>
            </a>
          </li>
          <li class="list__item">
            <a href="#" class="profile-link">
              <img class="person-icon" src="../assets/header/person-icon.webp" alt="person-icon" onclick="toggleMenu()" id="person-icon" />
            </a>
          </li>
        </ul>
        <div class="sub-menu-wrap" id="subMenu">
          <div class="sub-menu">
            <div class="user-info">
              <img src="../assets/header/person-icon.webp" alt="person-icon-sub" />
              <h2>
                <?php
                session_start();
                if (
                  isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true &&
                  isset($_SESSION['first_name']) && isset($_SESSION['last_name'])
                ) {
                  echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
                } else {
                  // If user is not logged in or first name/last name are not set, display a default value
                  echo "Name Surname";
                }
                ?>
              </h2>
            </div>
            <hr />
            <a href="profile.php" class="sub-menu-link">
              <img src="../assets/header/profile-icon.png" alt="profile-icon" />
              <p>Profile</p>
              <span>⯈</span>
            </a>
            <a href="#" class="sub-menu-link">
              <img src="../assets/header/logout-icon.png" alt="logout-icon" />
              <p>Logout</p>
              <span>⯈</span>
            </a>
          </div>
        </div>
      </nav>
    </div>
  </header>
  <main class="visitor-main">
    <div class="visitor-main-container">
      <div class="visit-left">
        <div class="inmate__show">
          <div class="inmate__show-banner">
            <div class="delete-show">
              <div class="line line-1"></div>
              <div class="line line-2"></div>
            </div>
            <img src="../assets/visitormain/inmate-icon.webp" alt="inmate-picture" class="inmate__show-photo" />
            <h1 class="inmate__show-title">Name Surname</h1>
          </div>
        </div>
        <form class="search-bar list-off">
          <input type="text" name="searchInmate" id="searchInmate" placeholder="Search inmate" />
          <button type="submit">
            <img src="../assets/visitormain/search-icon.svg" alt="search bar" />
          </button>
          <ul class="result-list hidden"></ul>
        </form>
        <button id="add-visitor" class="visitor-main__aside__button">
          Add visitor
        </button>
      </div>
      <div class="visit-right">
        <ul class="visitor-main__list">
          <li>
            <div class="visitor-element">
              <img src="../assets/visitormain/profile-icon.png" alt="visitor photo" class="visitor-main__list__show__photo" />
              <div class="visitor-main__list__show__name">
                <p class="visitor-main__list__show__label">
                  Name:
                  <span class="visitor-main__list__show__info">Visitor Name</span>
                </p>
              </div>
              <div class="drop-arrow">
                <span class="vBar"></span>
                <span class="vBar"></span>
              </div>
              <div class="visitor-main__list__show__dBirth">
                <p class="visitor-main__list__show__label">
                  ID Number:
                  <span class="visitor-main__list__show__info">Visitor Number</span>
                </p>
              </div>
              <div class="visitor-main__list__show__buttons">
                <button class="visitor-main__list__show__buttons__edit">
                  <img src="../assets/visitormain/edit-icon.svg" alt="edit button" />
                </button>
                <button class="visitor-main__list__show__buttons__delete">
                  <img src="../assets/visitormain/delete-icon.svg" alt="delete button" />
                </button>
              </div>
            </div>
          </li>
          <li>
            <div class="visitor-element">
              <img src="../assets/visitormain/profile-icon.png" alt="visitor photo" class="visitor-main__list__show__photo" />
              <div class="visitor-main__list__show__name">
                <p class="visitor-main__list__show__label">
                  Name:
                  <span class="visitor-main__list__show__info">Visitor Name
                  </span>
                </p>
              </div>
              <div class="drop-arrow">
                <span class="vBar"></span>
                <span class="vBar"></span>
              </div>
              <div class="visitor-main__list__show__dBirth">
                <p class="visitor-main__list__show__label">
                  ID Number:
                  <span class="visitor-main__list__show__info">Visitor Number</span>
                </p>
              </div>
              <div class="visitor-main__list__show__buttons">
                <button class="visitor-main__list__show__buttons__edit">
                  <img src="../assets/visitormain/edit-icon.svg" alt="edit button" />
                </button>
                <button class="visitor-main__list__show__buttons__delete">
                  <img src="../assets/visitormain/delete-icon.svg" alt="delete button" />
                </button>
              </div>
            </div>
          </li>
        </ul>
        <nav class="pagination-visitor-container">
          <button class="pagination-visitor-button-delete" id="del-all-button" title="Delete all visitors" aria-label="Delete all visitors">
            <span class="button-text">Delete</span>
          </button>
          <button class="pagination-visitor-button-prev" id="prev-button" title="Previous page" aria-label="Previous page">
            <span class="button-text">Prev</span>
          </button>
          <button class="pagination-visitor-button-next" id="next-button" title="Next page" aria-label="Next page">
            <span class="button-text">Next</span>
          </button>
          <button class="pagination-visitor-button-confirm" id="confirm-button" title="Confirm" aria-label="Confirm" onclick="redirectToVisitInfoPage()">
            <span class="button-text">Confirm</span>
          </button>
        </nav>
      </div>
    </div>
  </main>
  <script src="app.js"></script>
  <script src="scripts/modals.js"></script>
  <script src="scripts/visitormain.js"></script>
</body>

</html>