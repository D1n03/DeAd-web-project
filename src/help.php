<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta
      name="description"
      content="Help section about our app and how it works for each page"
    />
    <link rel="stylesheet" href="../src/styles/css/styles.css" />
    <link rel="icon" href="../assets/header/police-icon.svg" />
    <title>Help</title>
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
    <main class="about">
      <div class="container">
        <h1 class="container__title">Help &amp; Frequently Asked Questions</h1>
        <ul id="accordion">
          <li>
            <label for="help:home">About the Home page<span>&#x3e;</span></label>
            <input type="radio" name="accordion" id="help:home" checked>
            <div class="accordion__content">
              <p>This is the primary page of DeAd, where you can schedule a visit.</p>
            </div>
          </li>
          <li>
            <label for="help:contact">About the Contact page<span>&#x3e;</span></label>
            <input type="radio" name="accordion" id="help:contact">
            <div class="accordion__content">
              <p>The Contact page is where you can submit your feedback or issues 
                related to our website and the services we offer. Here, you can simply
                fill out the form and we will get in touch as soon as possible!
              </p>
            </div>
          </li>
          <li>
            <label for="help:profile">About the Profile page<span>&#x3e;</span></label>
            <input type="radio" name="accordion" id="help:profile">
            <div class="accordion__content">
              <p>This is the page where you can view and edit your profile information,
                such as your name, surname, e-mail address and profile picture. Here, 
                you can also browse the history of your visits on DeAd. 
              </p>
            </div>
          </li>
          <li>
            <label for="help:history">About the History page<span>&#x3e;</span></label>
            <input type="radio" name="accordion" id="help:history">
            <div class="accordion__content">
              <p>This page provides you with a chronological list of past
                visits, allowing you to view details about each visit. Additionally,
                you have the option to export this data in various formats, such as
                HTML, CSV, or JSON, using the provided export button.
              </p>
            </div>
          </li>
          <li>
            <label for="help:createaccount">How do I create an account? <span>&#x3e;</span></label>
            <input type="radio" name="accordion" id="help:createaccount">
            <div class="accordion__content">
              <p>In order to create an account, you can visit the Login page and click on 
                'Sign Up'. You will be prompted to fill out the necessary information for 
                the creation of the account: Your first and last name, e-mail address and 
                a password. Upon successfully signing up, you will gain access to sections 
                of the site and functionalities such as visit scheduling, the profile, and 
                the visit history.
              </p>
            </div>
          </li>
          <li>
            <label for="help:visitscheduling">How do I schedule a visit? <span>&#x3e;</span></label>
            <input type="radio" name="accordion" id="help:visitscheduling">
            <div class="accordion__content">
              <p>To schedule a visit, you must first log in to yout account in our
                database. You can then use the 'Schedule a visit'
                button on the Home page and fill out the form. You will be prompted to complete
                relevant information such as the date of the visit, its duration, the items 
                provided by the inmate (if any), their status, the purpose of the visit and the 
                summary of the discussions. When you submit the form, the visit will need to be 
                approved before being scheduled.
              </p>
            </div>
          </li>
          <li>
            <label for="help:forgotpassword">I forgot my password. How can I recover it? <span>&#x3e;</span></label>
            <input type="radio" name="accordion" id="help:forgotpassword">
            <div class="accordion__content">
              <p>By clicking the "Forgot Password?" button on the Login page, you will be 
                redirected to Account Recovery, where you can enter your e-mail address in the 
                given field. Upon pressing the button below, you will
                receive a verification token via e-mail. Once received, you can enter
                the token in a newly displayed form to proceed with the password
                reset.</p>
            </div>
          </li>
        </ul>
      </div>
    </main>  
  </body>
</html>
