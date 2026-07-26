<?php
    require_once "../../controllers/AdminAuth.php";
    require_once "../../controllers/ReportListController.php";//Starts the controller
    //some variables are red underline due to being undefined, this is because it is initialized in the controller
    //it will still work
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArcherFind - Surrender Found Item</title>
    <link rel="stylesheet" href="../../styles/global/global.css">
    <link rel="stylesheet" href="../../styles/global/navbar.css">
    <link rel="stylesheet" href="../../styles/global/dashboard.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"></script>
    <script src="../../javascript/global/navbar.js" defer></script>
    <script src="../../javascript/admin/admin_dashboard.js" defer></script>
</head>

<!-- <div id="reportModal" class="modal">
    <div class="modal-card">
        <h3>Select Time Frame</h3>
        <label for="fromDate">From</label>
        <input type="date" id="fromDate">
        <label for="toDate">To</label>
        <input type="date" id="toDate">
        <div class="modal-buttons">
            <button id="generateBtn">Generate</button>
            <button id="cancelBtn">Cancel</button>

        </div>

    </div> -->

<body>
    <!------------------------ NAVIGATION BAR / HEADER ------------------------>
    <header>
        <!-- LOGO (links to ADMIN DASHBOARD) -->
        <button class="archerfind-logo" onclick="window.location.href='../../pages/admin/admin_dashboard.php'">
            <h1>ArcherFind</h1>
            <img class="logo" src="../../assets/LOGOS/AF-ORIGINAL.png" alt="ArcherFind logo">
        </button>

        <!-- NAVBAR OPTIONS -->
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="../../pages/admin/admin_dashboard.php" class="current-page">Dashboard</a></li>
                <li><a href="../../pages/admin/admin_claim-list.php">Claim Requests</a></li>
                <!-- DROPDOWN MENU -->
                <li><a href="../../pages/admin/admin_report-list.php">Lost Reports</a></li>
                <li><a href="../../pages/admin/admin_surrender-list.php">Surrender Forms</a></li>
                <li><a href="../../pages/admin/admin_manage-users.php">Manage Users</a></li>
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
                            <button type="button" class="manage-account"
                                onclick="location.href='../../pages/admin/admin_manage-account.php'">Manage
                                Account</button>
                            <div class="user-profile-container">
                                <div class="day-night">
                                    <!-- day and night logic handled in navbar.js -->
                                    <button type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px">
                                            <path
                                                d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px">
                                            <path
                                                d="M338.5-338.5Q280-397 280-480t58.5-141.5Q397-680 480-680t141.5 58.5Q680-563 680-480t-58.5 141.5Q563-280 480-280t-141.5-58.5ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Z" />
                                        </svg>
                                        <span></span>
                                    </button>
                                </div>
                                <div class="log-out">
                                    <!-- log out logic handled in navbar.js -->
                                    <button type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px">
                                            <path
                                                d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z" />
                                        </svg>Log Out
                                    </button>
                                </div>
                                <div class="view-dashboard">
                                    <button type="button"
                                        onclick="location.href='../../pages/admin/admin_dashboard.php'">
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
                <li><a href="../../pages/admin/admin_dashboard.php" class="current-page">Dashboard</a></li>
                <li><a href="../../pages/admin/admin_claim-list.php">Claim Requests</a></li>
                <li><a href="../../pages/admin/admin_report-list.php">Lost Reports</a></li>
                <li><a href="../../pages/admin/admin_surrender-list.php">Surrender Forms</a></li>
                <li><a href="../../pages/admin/admin_manage-users.php">Manage Users</a></li>
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
                                <span class="name_of_user">[superlongname]</span>!
                                <!-- TODO: TO BE ADJUSTED DEPENDING ON NAME OF USER-->
                            </p>
                            <button type="button" class="manage-account"
                                onclick="location.href='../../pages/admin/admin_manage-account.php'">Manage
                                Account</button>
                            <div class="user-profile-container">
                                <div class="day-night">
                                    <!-- day and night logic handled in navbar.js -->
                                    <button type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px">
                                            <path
                                                d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px">
                                            <path
                                                d="M338.5-338.5Q280-397 280-480t58.5-141.5Q397-680 480-680t141.5 58.5Q680-563 680-480t-58.5 141.5Q563-280 480-280t-141.5-58.5ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Z" />
                                        </svg>
                                        <span></span>
                                    </button>
                                </div>
                                <div class="log-out">
                                    <!-- log out logic handled in navbar.js -->
                                    <button type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px">
                                            <path
                                                d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z" />
                                        </svg>Log Out
                                    </button>
                                </div>
                                <div class="view-dashboard">
                                    <button type="button"
                                        onclick="location.href='../../pages/admin/admin_dashboard.php'">
                                        View Dashboard
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

            <div class="sidebar-open-close">
                <button type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                        <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                        <path
                            d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                    </svg>
                </button>
            </div>
        </nav>
    </header>
    <!-------------------- END OF NAVIGATION BAR / HEADER --------------------->

    <div class="wrapper">
        <!-- GREETING HEADER -->
        <section class="dashboard-wrapper">
            <h1 class="greeting">Hello, <span id="username">Admin</span>!</h1>

            <div class="cards-wrapper">
                <!-- ROW 1: USER DISTRIBUTION -->
                <div class="card-row">
                    <!-- Stat Block: Total Users -->
                    <div class="stat-grid-wrapper">
                        <div class="stat-grid green-border card">
                            <h5>Total Users</h5>
                            <hr>
                            <h3><span class="stat-count" id="totalUsers">0</span></h3>
                        </div>

                        <!-- Stat Block: Students Active Card -->
                        <div class="stat-grid green-border card">
                            <h5>Students Active</h5>
                            <hr>
                            <h3><span class="stat-count" id="studentCount">0</span></h3>
                        </div>

                        <!-- Stat Block: Staff Active Card -->
                        <div class="stat-grid green-border card">
                            <h5>Staff Active</h5>
                            <hr>
                            <h3><span class="stat-count" id="staffCount">0</span></h3>
                        </div>

                        <div class="stat-block green-border card">
                            <h5>Admin Active</h5>
                            <hr>
                            <h3><span class="stat-count" id="adminCount">0</span></h3>
                        </div>
                    </div>

                    <!-- Stat Block / GRAPH: User Distribution Graph -->
                    <div class="graph-card card">
                        <h5>User Graph</h5>
                        <hr>
                        <canvas id="userChart"></canvas>
                    </div>

                    <!-- Item and Reports Analytics -->
                    <div class="info-card card">
                        <h5>Item and Reports Analytics</h5>
                        <hr>

                        <div class="info-row">
                            <span>Total Reports</span>
                            <span class="info-number" id="analyticsTotalReports">0</span>
                        </div>

                        <div class="info-row info-row-margin">
                            <span>Total Items</span>
                            <span class="info-number" id="analyticsTotalItems">0</span>
                        </div>

                        <h5>FILTER BY DATE</h5>
                        <div class="info-row">
                            <form action="">
                                <div class="time-frame">
                                    <label for="from-date">From:</label>
                                    <input type="date" name="from-date" id="from-date">

                                    <label for="to-date">To:</label>
                                    <input type="date" name="to-date" id="to-date">
                                </div>
                            </form>
                        </div>
                        <button id="resetFilterBtn" class="green-button">RESET</button>
                    </div>
                </div>
            </div>

            <!-- BUTTON ROW -->


            <div class="cards-wrapper">
                <!-- ROW 2: LIVE PLATFORM OPERATIONS -->
                <div class="card-row">
                    <!-- Graph Card: Item Reports -->
                    <div class="graph-card card">
                        <h5>Item Reports</h5>
                        <hr>
                        <div class="graph-card-wrapper">
                            <canvas id="itemReportChart"></canvas>
                            <p>Total Reports: <span id="totalReports">0</span></p>
                        </div>
                    </div>

                    <!-- Graph Card: Inventory Status -->
                    <div class="graph-card card">
                        <h5>Inventory Status</h5>
                        <hr>
                        <div class="graph-card-wrapper">
                            <canvas id="inventoryChart"></canvas>
                            <p>Total Items: <span id="totalItems">0</span></p>
                        </div>
                    </div>

                    <!-- Info Card: Inventory Status -->
                    <div class="info-card card">
                        <h5>Average Resolution Time</h5>
                        <hr>

                        <div class="info-header">
                            <span>Time</span>
                            <span>Type</span>
                        </div>

                        <div class="info-row">
                            <span class="info-number" id="lossMatchTime">0 days</span>
                            <span>Loss Match Time</span>
                        </div>

                        <div class="info-row">
                            <span class="info-number" id="surrenderApprovalTime">0 days</span>
                            <span>Surrender Approval</span>
                        </div>

                        <div class="info-row">
                            <span class="info-number" id="claimVerificationTime">0 days</span>
                            <span>Claim Verification</span>
                        </div>
                    </div>

                    <div class="stat-block-row">
                        <div class="stat-block card">
                            <h5>Claim Success Rate</h5>
                            <hr>
                            <h4>
                                <span class="stat-count" id="claimSuccessRate">0% Success Rate</span>
                            </h4>

                            <span class="stat-count"
                                id="claimSuccessDetails"
                                style="font-style: italic; font-size: calc(var(--text-small) - 4px);">
                                0 / 0 Claim Requests Accepted
                            </span>
                        </div>

                        <!-- Stat Block: Item Disposal Rate -->
                        <div class="stat-block card">
                            <h5>Item Disposal Rate</h5>
                            <hr>
                            <h4>
                                <span class="stat-count" id="itemDisposalRate">0% Items Disposed</span>
                            </h4>

                            <span class="stat-count"
                                id="itemDisposalDetails"
                                style="font-style: italic; font-size: calc(var(--text-small) - 4px);">
                                0 / 0 Items Disposed
                            </span>
                        </div>
                    </div>
                </div>

                <!-- ROW 3: ANALYTICS -->
                <div class="card-row">
                    <!-- Table: Top 5 Most Commonly Lost Categories -->
                    <div class="graph-card card">
                        <h5>Monthly Reports</h5>
                        <hr>
                        <div class="graph-card-wrapper">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>

                    <!-- Loss Item Frequency by Location -->
                    <div class="graph-card card">
                        <h5>Lost Item Frequency by Location</h5>
                        <hr>
                        <div class="chart-container">
                            <canvas id="lostItemFreqChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

</body>

</html>