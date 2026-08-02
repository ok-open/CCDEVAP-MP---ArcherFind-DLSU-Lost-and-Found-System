<?php
    require_once "../../controllers/StudentAuth.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArcherFind - Surrender Found Item</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../styles/global/global.css">
    <link rel="stylesheet" href="../../styles/global/navbar.css">
    <link rel="stylesheet" href="../../styles/global/dashboard.css">
    <link rel="stylesheet" href="../../styles/global/toast.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- JavaScript -->
    <script src="../../javascript/global/navbar.js" defer></script>
    <script src="../../javascript/global/lost-item-chart.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../javascript/global/toast.js" defer></script>
    <script src="../../javascript/student/student_dashboard.js" defer></script>
</head>

<body>
    <!------------------------ NAVIGATION BAR / HEADER ------------------------>
    <header>
        <button class="archerfind-logo" onclick="window.location.href='student_home.php'">
            <h1>ArcherFind</h1>
            <img class="logo" src="../../assets/LOGOS/AF-ORIGINAL.png" alt="ArcherFind logo">
        </button>

        <!-- NAVBAR OPTIONS -->
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="../../pages/student/student_home.php">Home</a></li>
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
                <li><a href="../../pages/student/student_home.php">Home</a></li>
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
        <section class="dashboard-wrapper">
            <h1 class="greeting">My Dashboard</h1>

            <!-- TOP ROW: Summary Stat Cards -->
            <div class="card-row">
                <!-- Loss Reports -->
                <div class="stat-block green-border card" data-stat="loss-reports">
                    <h5>Loss Reports</h5>
                    <hr>
                    <h3><span class="stat-count">0</span></h3>
                </div>

                <!-- Found Item Reports -->
                <div class="stat-block green-border card" data-stat="found-reports">
                    <h5>Found Item Reports</h5>
                    <hr>
                    <h3><span class="stat-count">0</span></h3>
                </div>

                <!-- Approved Reports/Requests -->
                <div class="stat-block green-border card" data-stat="approved-reports">
                    <h5>Approved Reports/Requests</h5>
                    <hr>
                    <h3><span class="stat-count">0</span></h3>
                </div>

                <!-- Pending Reports/Requests -->
                <div class="stat-block green-border card" data-stat="pending-reports">
                    <h5>Pending Reports/Requests</h5>
                    <hr>
                    <h3><span class="stat-count">0</span></h3>
                </div>
            </div>

            <!-- BOTTOM ROW: Chart + reports history / notifications -->
            <div class="card-row">
                <!-- Loss Item Frequency by Location -->
                <div class="graph-card card">
                    <h5>Lost Item Frequency by Location</h5>
                    <hr>
                    <div class="graph-card-wrapper">
                        <canvas id="ITEM"></canvas>
                    </div>
                </div>

                <div class="cards-wrapper">
                    <!-- User Reports History -->
                    <div class="card history-card">
                        <h5>User Reports History</h5>
                        <hr>
                        <table class="data-table" id="report-table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                            <tbody id="report-body">
                                <!-- Rows will be injected here by JS -->
                                <tr class="report-row" data-report-id="">
                                    <td class="report-date"></td>
                                    <td class="report-type"></td>
                                    <td class="report-status"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Notifications -->
                    <div class="card notifications-card">
                        <h5>Notifications</h5>
                        <hr>
                        <p>✓ Claim Request Approved</p>
                        <p>✖ Found Item Report Rejected</p>
                    </div>
                </div>
            </div>
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
                    <li><a onclick="if(confirm('WARNING: Clicking this link will take you to an external website: https://www.dlsu.edu.ph. This will exit you out of the ArcherFind Website. Continue?')) return true;" href="https://www.dlsu.edu.ph">DLSU Website</a></li>
                </ul>
        
                <div>
                    <h5>Have a Question?</h5>
                    <div id="write-us">
                        <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px"
                            >
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
    </div>
    <div id="toast"></div>
</body>

</html>