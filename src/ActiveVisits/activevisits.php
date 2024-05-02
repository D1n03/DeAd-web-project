<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../src/styles/css/styles.css" />
    <link rel="icon" href="../../assets/header/police-icon.svg" />
    <title>Active Visits</title>
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
        </div>
    </header>
    <main class="visitor-active-main">
        <div class="visitor-active-main-container">
            <div class="visit-active">
                <div class="main-page-title">
                    Your active visits
                </div>
                <ol class="visitor-main__list">
                    <?php
                    // we use curl to make a request to the api
                    $base_url = "localhost";
                    $url = $base_url . "/DeAd-web-Project/src/ActiveVisits/get_visits.php" . "?id=" . $_SESSION['id'];
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    // set the parameter id
                    curl_setopt($curl, CURLOPT_HTTPGET, true);
                    $curl_response = curl_exec($curl);
                    curl_close($curl);
                    $response = json_decode($curl_response, true);
                    // we parse the response
                    // if there are no appointments, display a message
                    if (empty($response)) {
                        // button to create an visits, copy paste lmao
                        echo '<div class="visitor-main-not-found">';
                        echo '<h3>You do not have any active visits</h3>';
                        echo '<a href="../CreateVisit/visit.php" class="visitor-active-main__button-add">';
                        echo 'Create a visit';
                        echo '</a>';
                        echo '</div>';
                        exit();
                    }
                    foreach ($response as $visit) {
                        echo '<li>';
                        echo '<div class="visitor-element">';
                        echo '<img src="../../assets/visitormain/profile-icon.png" alt="visitor photo" class="visitor-main__list__show__photo" />';
                        echo '<div class="visitor-main__list__show__name">';
                        echo '<p class="visitor-main__list__show__label">';
                        echo 'Inmate:';
                        echo '<span class="visitor-main__list__show__info"> ' . $visit['inmate_name'] . '</span>';
                        echo '</p>';
                        echo '</div>';
                        echo '<div class="visitor-main__list__show__dBirth">';
                        echo '<p class="visitor-main__list__show__label">';
                        echo 'Date:';
                        echo '<span class="visitor-main__list__show__info"> ' . $visit['date'] . '</span>';
                        echo '</p>';
                        echo '</div>';
                        echo '<div class="visitor-main__list__show__dBirth">';
                        echo '<p class="visitor-main__list__show__label">';
                        echo 'Time:';
                        echo '<span class="visitor-main__list__show__info"> ' . $visit['time_interval'] . '</span>';
                        echo '</p>';
                        echo '</div>';
                        echo '<div class="visitor-main__list__show__buttons">';
                        echo '<button class="visitor-main__list__show__buttons__edit">';
                        $visit_info_href = "visitInfo.php?visit_id=" . $visit['visit_id'];
                        echo '<a href=' . $visit_info_href . '>';
                        echo '<img src="../../assets/visitormain/confirm-icon.svg" alt="confirm button"/>';
                        echo '</a>';
                        echo '</button>';
                        echo '<button class="visitor-main__list__show__buttons__delete" visit_id_data="' . $visit['visit_id'] . '">';
                        echo '<img src="../../assets/visitormain/delete-icon.svg" alt="delete button" />';
                        echo '</button>';
                        echo '</div>';
                    }
                    ?>
                </ol>
            </div>
        </div>
    </main>
    <?php
    if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) :
    ?>
        <script src="../scripts/submenu.js"></script>
    <?php endif; ?>
    <script src="../scripts/navbar.js"></script>
    <script>
        const deleteButtons = document.querySelectorAll('.visitor-main__list__show__buttons__delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                const get_visit_id = button.getAttribute('visit_id_data');
                const currentUrl = window.location.href;

                if (confirm('Are you sure you want to delete this visit?')) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'deletevisit.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            window.location.href = currentUrl;
                        } else {
                            alert('Error deleting appointment. Please try again.');
                        }
                    };
                    xhr.send('visit_id=' + encodeURIComponent(get_visit_id));
                } else {
                    // nothing happens
                }
            });
        });
    </script>
</body>

</html>