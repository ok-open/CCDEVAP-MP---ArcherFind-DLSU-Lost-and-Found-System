<?php

$error = $_GET['error'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
        <link rel="stylesheet" href="styles/global/global.css">
        <link rel="stylesheet" href="styles/global/navbar.css">
        <link rel="stylesheet" href="styles/global/toast.css">
        <link rel="stylesheet" href="styles/auth/login-register.css">

        <script src="javascript/global/navbar.js" defer></script>
        <script src="javascript/global/toast.js" defer></script>
        <script src="javascript/auth/auth_account.js" defer></script>
        <script src="javascript/auth/login_toast.js" defer></script>
    <title>Login</title>
</head>

<body data-error="<?= htmlspecialchars($error) ?>">  
    <div class="wrapper">
        <!-- LEFT: LOGIN INTRO (this is where to insert title and description) -->
        <section class="logo-intro">
            <!-- TITLE AND DESCRIPTION -->
            <div class="title-desc">
                <div class="archerfind-logo">
                    <h1>ArcherFind</h1>
                        <img class="logo" src="assets/LOGOS/AF-ORIGINAL.png" alt="ArcherFind Logo">
                </div>
                <p>
                    An extended platform to report or recover your lost items on campus.<br>
                    By Lasallians, For Lasallians.
                </p>
            </div>
        </section>

        <!-- RIGHT: LOG-IN PAGE -->
        <section class="login-register-page">
            <div class="login-register-box">
                <h2>Welcome back!</h2>

                <!-- LOGIN DETAILS (FLEX) -->
                <form action="controllers/UsersController.php" method="POST"><!-- SHOULD BE CHANGED SOON DEPENDING ON LOGIN CREDENTIALS -->
                    <div class="login-register-details">
                        <!-- ID Number Form-->
                        <div class="form">
                            <label for="email">DLSU Email:</label>
                            <input type="email" id="email" name="email" placeholder="juan_delacruz@dlsu.edu.ph" maxlength="100" required>
                        </div>

                        <!-- Password Form  -->
                        <div class="form password-form">
                            <label for="pass">Password:</label>
                            <div class="password-input-wrapper">
                                    <input type="password" id="pass" name="password" maxlength="255" required>                                <button type="button" class="see-pass" aria-label="Toggle password visibility">
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

                        <!-- Login -->
                        <button type="submit" class="form-button" id="login-submit">Login</button>
                    </div>
                </form>

                <!-- REGISTER -->
                <p>Don't have an account? <a href="pages/auth/register.php">Register</a></p>

                <!-- DARK MODE BUTTON -->
                <div class="day-night">
                    <button type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                            <path
                                d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                            <path
                                d="M338.5-338.5Q280-397 280-480t58.5-141.5Q397-680 480-680t141.5 58.5Q680-563 680-480t-58.5 141.5Q563-280 480-280t-141.5-58.5ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Z" />
                        </svg>

                        <span>Switch to [dark/light] Mode</span>
                    </button>
                </div>
            </div>
        </section>
    </div>

    <footer>
        De La Salle University - College of Computer Studies - Department of Information Technology<br>
        2026 © AY2526T3. CCDEVAP - Web Application and Development. All Rights Reserved.
    </footer>

    <div id="toast"></div>
</body>

</html>