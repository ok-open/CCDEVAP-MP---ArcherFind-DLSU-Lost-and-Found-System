<?php
    require_once "../../controllers/StudentAuth.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArcherFind - Home</title>
    <link rel="stylesheet" href="../../styles/global/global.css">
    <link rel="stylesheet" href="../../styles/global/navbar.css">
    <link rel="stylesheet" href="../../styles/student/student_home.css">
    <script src="../../javascript/global/navbar.js" defer></script>
</head>

<body>
    <!------------------------ NAVIGATION BAR / HEADER ------------------------>
    <header id="home-header">
        <button class="archerfind-logo" onclick="window.location.href='student_home.php'">
            <h1>ArcherFind</h1>
            <img class="logo" src="../../assets/LOGOS/AF-ORIGINAL.png" alt="ArcherFind logo">
        </button>

        <!-- NAVBAR OPTIONS -->
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="../../pages/student/student_home.php" class="current-page">Home</a></li>
                <li><a href="../../pages/student/student_about.php">About</a></li>
                <!-- DROPDOWN MENU -->
                <li class="dropdown">
                    <a class="active">Lost and Found<i class="arrow down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="../../pages/student/student_item-view.php">Report Lost</a></li>
                        <li><a href="../../pages/student/student_surrender-form.php">Report Found</a></li>
                    </ul>
                </li>
                <li><a href="../../pages/student/student_contact.php">Contact Us</a></li>
                <li>
                    <!-- user profile -->
                    <div class="user-button"><button type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                <path
                                    d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm146.5-204.5Q340-521 340-580t40.5-99.5Q421-720 480-720t99.5 40.5Q620-639 620-580t-40.5 99.5Q539-440 480-440t-99.5-40.5ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
                            </svg>
                        </button>
                        <div class="user-profile">
                            <p class="user-greeting">Welcome back,<br>
                                <span class="name_of_user">
                                    <?= htmlspecialchars($_SESSION["first_name"]) ?>
                                </span>
                            </p>
                            <button type="button" class="manage-account" onclick="location.href='../../pages/student/student_manage-account.php'">Manage Account</button>
                            <div class="user-profile-container">
                                <div class="day-night"><button type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"> <path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Z" /> </svg> <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"> <path d="M338.5-338.5Q280-397 280-480t58.5-141.5Q397-680 480-680t141.5 58.5Q680-563 680-480t-58.5 141.5Q563-280 480-280t-141.5-58.5ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Z" /> </svg> <span></span>
                                    </button></div>
                                <div class="log-out"><button type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"> <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z" /> </svg>Log Out</button></div>
                                <div class="view-dashboard">
                                    <button type="button" onclick="window.location.href='student_dashboard.php'">
                                        View Dashboard
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- SIDEBAR OPTIONS -->
        <nav class="sidebar">
            <ul class="nav-links">
                <li><a href="../../pages/student/student_home.php" class="current-page">Home</a></li>
                <li><a href="../../pages/student/student_about.php">About</a></li>
                <!-- DROPDOWN MENU -->
                <li class="dropdown">
                    <a class="active">Lost and Found<i class="arrow down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="../../pages/student/student_item-view.php">> Report Lost</a></li>
                        <li><a href="../../pages/student/student_surrender-form.php">> Report Found</a></li>
                    </ul>
                </li>
                <li><a href="../../pages/student/student_contact.php">Contact Us</a></li>
            </ul>

            <!-- user profile -->
            <div class="user-button"><button type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"> <path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm146.5-204.5Q340-521 340-580t40.5-99.5Q421-720 480-720t99.5 40.5Q620-639 620-580t-40.5 99.5Q539-440 480-440t-99.5-40.5ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" /> </svg>
                </button>
                <div class="user-profile">
                    <p class="user-greeting">Welcome back,<br>
                        <span class="name_of_user">
                            <?= htmlspecialchars($_SESSION["first_name"]) ?>
                        </span>
                    </p>
                    <button type="button" class="manage-account" onclick="location.href='../../pages/student/student_manage-account.php'">Manage Account</button>
                    <div class="user-profile-container">
                        <div class="day-night"><button type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"> <path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Z" /> </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"> <path d="M338.5-338.5Q280-397 280-480t58.5-141.5Q397-680 480-680t141.5 58.5Q680-563 680-480t-58.5 141.5Q563-280 480-280t-141.5-58.5ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Z" /> </svg>
                            </button></div>
                        <div class="log-out"><button type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"> <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z" /> </svg>Log Out</button></div>
                        <div class="view-dashboard">
                            <button type="button" onclick="window.location.href='student_dashboard.php'">
                                View Dashboard
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar-open-close"><button type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                        <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                        <path
                            d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                    </svg>
                </button></div>
        </nav>
    </header>
    <!-------------------- END OF NAVIGATION BAR / HEADER --------------------->

    <div class="wrapper">
        <!-- INTRO SECTION -->
        <section class="intro">
            <div class="title-desc">
                <div class="archerfind-logo">
                    <h1>ArcherFind</h1>
                    <img class="logo" src="../../assets/LOGOS/AF-ORIGINAL.png" alt="">
                </div>
                <p>
                    An extended platform to report or recover your lost items on campus.<br>
                    By Lasallians, For Lasallians.
                </p>
            </div>

            <div class="intro-buttons">
                <button type="button" class="green-button" onclick="location.href='student_item-view.php'">
                    Lost something?
                    <p>Browse surrendered items or report a lost belonging!
                    </p>
                </button>
                <button type="button" class="green-button" onclick="location.href='student_surrender-form.php'">
                    Found something?
                    <p>Fill out a form to surrender the item that you found!</p>
                </button>
            </div>

            <button type="button" class="outline-white-button"
                onclick="document.querySelector('.how-it-works').scrollIntoView({behavior: 'smooth'});">
                LEARN MORE
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="48px" fill="#e3e3e3">
                    <path
                        d="M480-179.65 226.43-433.78 282-489.35l198 198.57 198-198.57 55.57 55.57L480-179.65Zm0-266L226.43-699.22 282-755.35l198 198.57 198-198.57 55.57 56.13L480-445.65Z" />
                </svg>
            </button>
        </section>

        <!-- HOW IT WORKS SECTION -->
        <section class="how-it-works">
            <h1>How It Works</h1>
            <div class="how-it-works-container">
                <div class="how-it-works-panel">
                    <h4>Find your item</h4>
                    <p>Browse the catalogue of lost items to check if your missing belonging has been surrendered. Use
                        filters such as category, location, and date found to locate matching items.</p>
                </div>

                <div class="how-it-works-panel">
                    <h4>Report your missing item</h4>
                    <p>Can't find your item in the listing? Submit a lost item report containing details such as its
                        description, last known location, and an image if available. Staff will review the report and
                        notify you if a matching item is found.</p>
                </div>

                <div class="how-it-works-panel">
                    <h4>Turn in a found item</h4>
                    <p>Found an item on campus? Submit a report with details and upload a photo of the item. After
                        verification, the item will be added to the lost and found catalogue to help return it to its
                        owner.</p>
                </div>
            </div>
        </section>

        <!-- ABOUT THE WEBSITE BUTTON -->
        <section class="about_button">
            <button class="outline-green-button" type="button" onclick="location.href='student_about.php'">About the
                Website</button>
        </section>
    </div>

    <!------------------------ CONNECTIONS AND FOOTER ------------------------>
    <section class="connections">
        <h3>Connections</h3>
        <div class="connections-container">
            <ul>
                <li><a href="../../pages/student/student_home.php">Home</a></li>
                <li><a href="../../pages/student/student_contact.php">Contact</a></li>
                <li><a href="../../pages/student/student_faq.php">F.A.Q</a></li>
            </ul>

            <ul>
                <li><a href="../../pages/student/student_about.php">About</a></li>
                <li><a href="../../pages/student/student_dashboard.php">Dashboard</a></li>
                <li><a onclick="if(confirm('WARNING: Clicking this link will take you to an external website: https://www.dlsu.edu.ph. This will exit you out of the ArcherFind Website. Continue?')) return true;"
                        href="https://www.dlsu.edu.ph">DLSU Website</a></li>
            </ul>

            <div>
                <h5>Have a Question?</h5>
                <div id="write-us">
                    <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px">
                        <path
                            d="M158.57-160q-32.52 0-56.21-23.69-23.69-23.7-23.69-56.21v-480.2q0-32.51 23.69-56.21Q126.05-800 158.57-800h642.86q32.52 0 56.21 23.69 23.69 23.7 23.69 56.21v480.2q0 32.51-23.69 56.21Q833.95-160 801.43-160H158.57ZM480-453.51 146.26-668.36v428.46q0 5.39 3.46 8.85t8.85 3.46h642.86q5.39 0 8.85-3.46t3.46-8.85v-428.46L480-453.51Zm0-67.57 331.03-211.33H149.64L480-521.08ZM140.92-668.36v-64.05 492.51q4 5.39 8.13 8.85 4.13 3.46 9.52 3.46h-17.65v-440.77Z" />
                    </svg>
                    <a href="../../pages/student/student_contact.php">Write us!</a>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <hr>
        De La Salle University - College of Computer Studies - Department of Information Technology<br>
        2026 © AY2526T3. CCDEVAP - Web Application and Development. All Rights Reserved.
    </footer>
</body>

</html>