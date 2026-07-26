<?php
    require_once "../../controllers/AdminAuth.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArcherFind - Manage Staff</title>
    <link rel="stylesheet" href="../../styles/global/global.css">
    <link rel="stylesheet" href="../../styles/global/navbar.css">
     
    <link rel="stylesheet" href="../../styles/admin/admin_manage-staff.css">
    <script src="../../javascript/global/navbar.js" defer></script>


</head>

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
                <li><a href="../../pages/admin/admin_dashboard.php">Dashboard</a></li>
                <li><a href="../../pages/admin/admin_claim-list.php">Claim Requests</a></li>
                <li><a href="../../pages/admin/admin_report-list.php">Lost Reports</a></li>
                <li><a href="../../pages/admin/admin_surrender-list.php">Surrender Forms</a></li>
                <li><a href="../../pages/admin/admin_manage-student.php">Manage Users</a></li>
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
                <li><a href="../../pages/admin/admin_dashboard.php">Dashboard</a></li>
                <li><a href="../../pages/admin/admin_claim-list.php" class="current-page">Claim Requests</a></li>
                <!-- DROPDOWN MENU -->
                <li><a href="../../pages/admin/admin_report-list.php">Lost Reports</a></li>
                <li><a href="../../pages/admin/admin_surrender-list.php">Surrender Forms</a></li>
                <li><a href="../../pages/admin/admin_manage-student.php">Manage Users</a></li>
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
    
    <div class="itemview-wrapper">
        <aside class="itemview-sidebar">
            <div class="stats-box">
                <h3>Total Staff</h3>
                <h2>7</h2> <!-- Total Staff number-->
            </div>
        </aside>

        <main class="itemview-main">
            <h1 class="manage-title">MANAGE STAFF</h1>

            <div class="itemview-controls">
                <input type="text" placeholder="Search for staff..." class="search-bar">

                <select class="sort-dropdown">
                    <option>Sort: Name</option>
                    <option>Sort: ID Number</option>
                </select>
            </div>

            <div class="staff-list">
                <div class="staff-card">
                    <div><strong>Name:</strong> Robin Dela Cruz</div>
                    <div><strong>ID Number:</strong> 12345678</div>
                    <button class="remove-btn">Remove</button>
                </div>

                <div class="staff-card">
                    <div><strong>Name:</strong> Carl Crespo</div>
                    <div><strong>ID Number:</strong> 12479144</div>
                    <button class="remove-btn">Remove</button>
                </div>

                <div class="staff-card">
                    <div><strong>Name:</strong> Lebron James</div>
                    <div><strong>ID Number:</strong> 66666666</div>
                    <button class="remove-btn">Remove</button>
                </div>

                <div class="staff-card">
                    <div><strong>Name:</strong> Michael Jordan</div>
                    <div><strong>ID Number:</strong> 676767676</div>
                    <button class="remove-btn">Remove</button>
                </div>

                <div class="staff-card">
                    <div><strong>Name:</strong> Michael Jackson</div>
                    <div><strong>ID Number:</strong> 231231231</div>
                    <button class="remove-btn">Remove</button>
                </div>

                <div class="staff-card">
                    <div><strong>Name:</strong> Paul </div>
                    <div><strong>ID Number:</strong> 121231212 </div>
                    <button class="remove-btn">Remove</button>
                </div>

                <div class="staff-card">
                    <div><strong>Name:</strong> John </div>
                    <div><strong>ID Number:</strong> 236125426</div>
                    <button class="remove-btn">Remove</button>
                </div>

            </div>
        </main>
    </div>
</body>
</html>