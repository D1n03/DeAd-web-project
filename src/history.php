<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta
      name="description"
      content="On this page you are able to see the history visits of your account, together with the posibility to export relevant data"
    />
    <link rel="stylesheet" href="../src/styles/css/styles.css" />
    <link rel="icon" href="../assets/header/police-icon.svg" />
    <title>History</title>
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
                <img
                  class="list__item-icon"
                  src="../assets/header/home-icon.svg"
                  alt="home-icon"
                />
                <p class="list__item-text">Home</p>
              </a>
            </li>
            <li class="list__item">
              <a href="about.php" class="nav-link">
                <img
                  class="list__item-icon"
                  src="../assets/header/about-icon.svg"
                  alt="about-icon"
                />
                <p class="list__item-text">About</p>
              </a>
            </li>
            <li class="list__item">
              <a href="help.php" class="nav-link">
                <img
                  class="list__item-icon"
                  src="../assets/header/help-icon.svg"
                  alt="help-icon"
                />
                <p class="list__item-text">Help</p>
              </a>
            </li>
            <li class="list__item">
              <a href="contact.php" class="nav-link">
                <img
                  class="list__item-icon"
                  src="../assets/header/contact-icon.svg"
                  alt="contact-icon"
                />
                <p class="list__item-text">Contact</p>
              </a>
            </li>
            <li class="list__item">
              <a href="#" class="profile-link">
                <img
                  class="person-icon"
                  src="../assets/header/person-icon.webp"
                  alt="person-icon"
                  onclick="toggleMenu()"
                  id="person-icon"
                />
              </a>
            </li>
          </ul>
          <div class="sub-menu-wrap" id="subMenu">
            <div class="sub-menu">
              <div class="user-info">
                <img
                  src="../assets/header/person-icon.webp"
                  alt="person-icon-sub"
                />
                <h2>Name Surname</h2>
              </div>
              <hr />
              <a href="profile.php" class="sub-menu-link">
                <img
                  src="../assets/header/profile-icon.png"
                  alt="profile-icon"
                />
                <p>Profile</p>
                <span>⯈</span>
              </a>
              <a href="#" class="sub-menu-link">
                <img
                  src="../assets/header/logout-icon.png"
                  alt="logout-icon"
                />
                <p>Logout</p>
                <span>⯈</span>
              </a>
            </div>
          </div>
        </nav>
      </div>
    </header>
    <script src="app.js"></script>
    <main class="history">
      <div class="history-container">
        <div class="info-container">
          <div class="history__title">Your Visit History</div>
          <ul class="history__list">
            <li>
              <div class="history-element">
                <img
                  src="../assets/visitormain/inmate-icon.webp"
                  alt="inmate photo"
                  class="history__list__show__photo"
                />
                <div class="visit-info">
                  <div class="history__list__show__name">
                    <p class="history__list__show__label">
                      Prisoner:
                      <span class="history__list__show__info"
                        >Name Surname</span
                      >
                    </p>
                  </div>
                  <div class="drop-arrow">
                    <span class="vBar"></span>
                    <span class="vBar"></span>
                  </div>
                  <div class="visitor-main__list__show__dBirth">
                    <p class="history__list__show__label">
                      Date:
                      <span class="history__list__show__info">dd/mm/yyyy</span>
                    </p>
                  </div>
                  <div class="visitor-main__list__show__dBirth">
                    <p class="history__list__show__label">
                      Duration:
                      <span class="history__list__show__info">1:30:00</span>
                    </p>
                  </div>
                </div>
                <div class="history__list__show__buttons">
                  <button class="history__list__show__buttons__info">
                    <img
                      src="../assets/visitormain/info-icon.svg"
                      alt="info button"
                    />
                  </button>
                </div>
              </div>
            </li>
            <li>
              <div class="history-element">
                <img
                  src="../assets/visitormain/inmate-icon.webp"
                  alt="inmate photo"
                  class="history__list__show__photo"
                />
                <div class="visit-info">
                  <div class="history__list__show__name">
                    <p class="history__list__show__label">
                      Prisoner:
                      <span class="history__list__show__info"
                        >Name Surname</span
                      >
                    </p>
                  </div>
                  <div class="drop-arrow">
                    <span class="vBar"></span>
                    <span class="vBar"></span>
                  </div>
                  <div class="visitor-main__list__show__dBirth">
                    <p class="history__list__show__label">
                      Date:
                      <span class="history__list__show__info">dd/mm/yyyy</span>
                    </p>
                  </div>
                  <div class="visitor-main__list__show__dBirth">
                    <p class="history__list__show__label">
                      Duration:
                      <span class="history__list__show__info">1:15:30</span>
                    </p>
                  </div>
                </div>
                <div class="history__list__show__buttons">
                  <button class="history__list__show__buttons__info">
                    <img
                      src="../assets/visitormain/info-icon.svg"
                      alt="info button"
                    />
                  </button>
                </div>
              </div>
            </li>
            <li>
              <div class="history-element">
                <img
                  src="../assets/visitormain/inmate-icon.webp"
                  alt="inmate photo"
                  class="history__list__show__photo"
                />
                <div class="visit-info">
                  <div class="history__list__show__name">
                    <p class="history__list__show__label">
                      Prisoner:
                      <span class="history__list__show__info"
                        >Name Surname</span
                      >
                    </p>
                  </div>
                  <div class="drop-arrow">
                    <span class="vBar"></span>
                    <span class="vBar"></span>
                  </div>
                  <div class="visitor-main__list__show__dBirth">
                    <p class="history__list__show__label">
                      Date:
                      <span class="history__list__show__info">dd/mm/yyyy</span>
                    </p>
                  </div>
                  <div class="visitor-main__list__show__dBirth">
                    <p class="history__list__show__label">
                      Duration:
                      <span class="history__list__show__info">1:55:10</span>
                    </p>
                  </div>
                </div>

                <div class="history__list__show__buttons">
                  <button class="history__list__show__buttons__info">
                    <img
                      src="../assets/visitormain/info-icon.svg"
                      alt="info button"
                    />
                  </button>
                </div>
              </div>
            </li>
          </ul>
          <nav class="pagination-visitor-container">
            <button
              class="pagination-visitor-button-prev"
              id="prev-button"
              title="Previous page"
              aria-label="Previous page"
            >
              <span class="button-text">Prev</span>
            </button>
            <button
              class="pagination-visitor-button-export"
              id="export-button"
              title="Export data"
              aria-label="Export button"
            >
              <span class="button-text">Export</span>
            </button>
            <button
              class="pagination-visitor-button-next"
              id="next-button"
              title="Next page"
              aria-label="Next page"
            >
              <span class="button-text">Next</span>
            </button>
          </nav>
        </div>
      </div>
    </main>
  </body>
</html>
