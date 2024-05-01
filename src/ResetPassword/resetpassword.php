<?php
session_start();
$token = $_GET["token"];
$token_hash = hash("sha256", $token);

require '../Utils/Connection.php';
$conn = Connection::getInstance()->getConnection();

$sql = "SELECT * FROM users
        WHERE reset_token_hash = ?";

try {
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $token_hash);
  $stmt->execute();

  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
} catch (Exception $e) {
  echo $e->getMessage();
}

// redirect to error page reason 1 
if ($user === null) {
  header("Location: ../Error/error.php?reason=1");
}
// redirect to error page reason 2
if (strtotime($user["reset_token_expires_at"]) <= time()) {
  header("Location: ../Error/error.php?reason=2");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Once you got the email, you can complete the form in order to reset your password by providing a new one" />
  <link rel="stylesheet" href="../../src/styles/css/styles.css" />
  <link rel="icon" href="../../assets/header/police-icon.svg" />
  <title>Reset Password</title>
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

  <main class="recovery">
    <div class="container">
      <h1 class="container__title">Reset Password</h1>
      <p class="container__text">
        Enter your new password below.
      </p>
      <form class="container__form" id="recovery-form" action="resetpassword_script.php" method="POST">
        <input type = "hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <div class="container__form-field">
          <input id="password" required type="password" name="password" placeholder="Password" />
          <p class="validation-error password-error"></p>
        </div>
        <div class="container__form-field">
          <input id="password_confirm" required type="password" name="password_confirm" placeholder="Confirm Password" />
          <p class="validation-error password-error"></p>
        </div>
        <?php
        if (isset($_GET['error'])) {
          if ($_GET['error'] == 1) {
            echo '<p class="error">Passwords do not match</p>';
          }
        } else if (isset($_GET['strength'])) {
          if ($_GET['strength'] == 0) {
            echo '<p class="error"> Password must contain at least 8 characters, a number, uppercase and lowercase letters</p>';
          } else {
            echo '<p class="error" style="color: green;">Password is strong!</p>';
          }
        }
        ?>
        <div class="container__form-buttons">
          <button type="submit" class="container__form-submit-signup">
            Reset
          </button>
        </div>
      </form>
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