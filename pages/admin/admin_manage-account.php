<?php
    require_once "../../controllers/AdminAuth.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArcherFind - Manage Account</title>
    <link rel="stylesheet" href="../../styles/global/global.css">
    <link rel="stylesheet" href="../../styles/global/navbar.css">
    <link rel="stylesheet" href="../../styles/auth/manage-account.css">
    <script src="../../javascript/global/navbar.js" defer></script>
    <script src="../../javascript/auth/auth_account.js" defer></script>
    <script src="../../javascript/auth/manage_account.js" defer></script>
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
                <li><a href="../../pages/admin/admin_claim-list.php">Claim Requests</a></li>
                <li><a href="../../pages/admin/admin_report-list.php" class="current-page">Lost Reports</a></li>
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

    <!-- ACCOUNT PAGE -->
    <div class="account-wrapper">
        <section class="account-layout">

            <!-- FORM -->
            <div class="account-form">
                <form id="passwordForm" action="../../controllers/UpdatePasswordController.php" method="POST">
                    <h2>Manage Account</h2>

                    <!-- FIRST & LAST NAME -->
                    <div class="row two-cols">
                        <!-- FIRST NAME -->
                        <div class="form-group">
                            <label>First Name <span class="tag">READ ONLY</span></label>
                            <input type="text" value="<?= htmlspecialchars($_SESSION['first_name']) ?>" readonly>
                        </div>
                        <!-- LAST NAME -->
                        <div class="form-group">
                            <label>Last Name <span class="tag">READ ONLY</span></label>
                            <input type="text" value="<?= htmlspecialchars($_SESSION['last_name']) ?>" readonly>
                        </div>
                    </div>

                    <!-- ID NUMBER & EMAIL -->
                    <!-- Email -->
                    <div class="form-group">
                        <label>Email<span class="tag">READ ONLY</span></label>
                        <input type="text" value="<?= htmlspecialchars($_SESSION['email']) ?>" readonly>
                    </div>

                    <!-- CURRENT PASSWORD  -->
                    <div class="form-group full">
                        <label for="pass">Current Password:</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="current-pass" name="current_password" maxlength="20" required>
                            <button type="button" class="see-pass" aria-label="Toggle password visibility">
                                <!-- VISIBILITY OFF (eye closed) - shown when password is visible -->
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                    width="24px">
                                    <path
                                        d="m789.13-53.13-163.7-161.94q-34.52 11.24-70.5 16.86-35.97 5.62-74.93 5.62-152.67 0-272.71-84.93Q87.26-362.46 32.59-500q20.76-52.52 53-98.86 32.24-46.34 72.76-83.29L49.3-792.72l58.63-58.63 739.59 739.83-58.39 58.39ZM480-320q9.8 0 18.35-.88 8.54-.88 18.35-3.64L304.04-536.7q-2.52 9.81-3.28 18.47-.76 8.66-.76 18.23 0 75 52.5 127.5T480-320Zm299.65 20.63L646.2-432.07q6.52-15.8 10.16-32.94Q660-482.15 660-500q0-75-52.5-127.5T480-680q-18.33 0-34.99 3.64-16.66 3.64-32.94 10.93L304.09-773.41q41-17 84.95-25.5 43.96-8.5 90.96-8.5 152.43 0 272.47 84.69Q872.5-638.02 927.41-500q-23 59.48-60.98 110.93-37.97 51.46-86.78 89.7ZM577.67-500.59l-98-98q22.98-4.04 42.06 3.55 19.07 7.58 32.97 22.47 13.89 14.9 20.07 34.09 6.19 19.2 2.9 37.89Z" />
                                </svg>
                                <!-- VISIBILITY ON (eye open) - shown when password is hidden -->
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                    width="24px">
                                    <path
                                        d="M480-320q75 0 127.5-52.5T660-500t-52.5-127.5T480-680t-127.5 52.5T300-500t52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500t31.5-76.5T480-608t76.5 31.5T588-500t-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T32-500q60-127 180-208.5T480-790t268 81.5T928-500q-60 127-180 208.5T480-200Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- NEW PASSWORD  -->
                    <div class="form-group full">
                        <label for="pass">New Password:</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="new-pass" name="new_password" maxlength="20" required> <button
                                type="button" class="see-pass" aria-label="Toggle password visibility">
                                <!-- VISIBILITY OFF (eye closed) - shown when password is visible -->
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                    width="24px">
                                    <path
                                        d="m789.13-53.13-163.7-161.94q-34.52 11.24-70.5 16.86-35.97 5.62-74.93 5.62-152.67 0-272.71-84.93Q87.26-362.46 32.59-500q20.76-52.52 53-98.86 32.24-46.34 72.76-83.29L49.3-792.72l58.63-58.63 739.59 739.83-58.39 58.39ZM480-320q9.8 0 18.35-.88 8.54-.88 18.35-3.64L304.04-536.7q-2.52 9.81-3.28 18.47-.76 8.66-.76 18.23 0 75 52.5 127.5T480-320Zm299.65 20.63L646.2-432.07q6.52-15.8 10.16-32.94Q660-482.15 660-500q0-75-52.5-127.5T480-680q-18.33 0-34.99 3.64-16.66 3.64-32.94 10.93L304.09-773.41q41-17 84.95-25.5 43.96-8.5 90.96-8.5 152.43 0 272.47 84.69Q872.5-638.02 927.41-500q-23 59.48-60.98 110.93-37.97 51.46-86.78 89.7ZM577.67-500.59l-98-98q22.98-4.04 42.06 3.55 19.07 7.58 32.97 22.47 13.89 14.9 20.07 34.09 6.19 19.2 2.9 37.89Z" />
                                </svg>
                                <!-- VISIBILITY ON (eye open) - shown when password is hidden -->
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                    width="24px">
                                    <path
                                        d="M480-320q75 0 127.5-52.5T660-500t-52.5-127.5T480-680t-127.5 52.5T300-500t52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500t31.5-76.5T480-608t76.5 31.5T588-500t-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T32-500q60-127 180-208.5T480-790t268 81.5T928-500q-60 127-180 208.5T480-200Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- CONFIRM PASSWORD  -->
                    <div class="form-group full">
                        <label for="pass">Confirm Password:</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="confirm-pass" name="confirm_password" maxlength="20" required>
                            <button type="button" class="see-pass" aria-label="Toggle password visibility">
                                <!-- VISIBILITY OFF (eye closed) - shown when password is visible -->
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                    width="24px">
                                    <path
                                        d="m789.13-53.13-163.7-161.94q-34.52 11.24-70.5 16.86-35.97 5.62-74.93 5.62-152.67 0-272.71-84.93Q87.26-362.46 32.59-500q20.76-52.52 53-98.86 32.24-46.34 72.76-83.29L49.3-792.72l58.63-58.63 739.59 739.83-58.39 58.39ZM480-320q9.8 0 18.35-.88 8.54-.88 18.35-3.64L304.04-536.7q-2.52 9.81-3.28 18.47-.76 8.66-.76 18.23 0 75 52.5 127.5T480-320Zm299.65 20.63L646.2-432.07q6.52-15.8 10.16-32.94Q660-482.15 660-500q0-75-52.5-127.5T480-680q-18.33 0-34.99 3.64-16.66 3.64-32.94 10.93L304.09-773.41q41-17 84.95-25.5 43.96-8.5 90.96-8.5 152.43 0 272.47 84.69Q872.5-638.02 927.41-500q-23 59.48-60.98 110.93-37.97 51.46-86.78 89.7ZM577.67-500.59l-98-98q22.98-4.04 42.06 3.55 19.07 7.58 32.97 22.47 13.89 14.9 20.07 34.09 6.19 19.2 2.9 37.89Z" />
                                </svg>
                                <!-- VISIBILITY ON (eye open) - shown when password is hidden -->
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                    width="24px">
                                    <path
                                        d="M480-320q75 0 127.5-52.5T660-500t-52.5-127.5T480-680t-127.5 52.5T300-500t52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500t31.5-76.5T480-608t76.5 31.5T588-500t-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T32-500q60-127 180-208.5T480-790t268 81.5T928-500q-60 127-180 208.5T480-200Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
            </div>

            <!-- BUTTONS -->
            <div class="button-stack">
                <button id="saveBtn" class="save-btn" type="submit">
                    Confirm New Password
                </button>
                </form>

                <form id="disableAccountForm" action="../../controllers/DisableAccountController.php" method="POST">
                    <button id="disableBtn" class="disable-btn" type="button">
                        Disable Account
                    </button>
                </form>
            </div>
        </section>
    </div>
    <div id="toast"></div>

</body>
</html>