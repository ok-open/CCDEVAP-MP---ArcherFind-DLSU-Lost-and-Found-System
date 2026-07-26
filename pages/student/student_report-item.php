<?php
    require_once "../../controllers/StudentAuth.php";
    require_once "../../controllers/LocationController.php";
    require_once "../../controllers/CategoriesController.php";
    require_once "../../controllers/BrandsController.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArcherFind - Surrender Found Item</title>
    <link rel="stylesheet" href="../../styles/global/global.css">
    <link rel="stylesheet" href="../../styles/global/navbar.css">
    <link rel="stylesheet" href="../../styles/global/modal.css">
    <link rel="stylesheet" href="../../styles/student/student_lost-and-found-form.css">
    <script src="../../javascript/global/navbar.js" defer></script>
    <script src="../../javascript/global/image.js" defer></script>
    <script src="../../javascript/global/toast.js" defer></script>
    <script src="../../javascript/global/modal.js" defer></script>
    <script src="../../javascript/student/student_report-item.js" defer></script>
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
                    <a class="active current-page">Lost and Found<i class="arrow down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="../../pages/student/student_item-view.php" class="current-page">Report Lost</a></li>
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
                    <a class="active current-page">Lost and Found<i class="arrow down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="../../pages/student/student_item-view.php" class="current-page">> Report Lost</a></li>
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

    <div class="surrender-wrapper">
        <div class="form-title">
            <h2>Can't find the item you're looking for? Submit a missing item report.</h2>
            <p>If you didn't see your item in our list, log its details here. We will monitor incoming surrenders and
                notify you if a match is found.</p>
        </div>

        <!-- REPORT AN ITEM FORM -->
        <form class="form-wrapper" method="POST" enctype="multipart/form-data" action="../../controllers/ReportsController.php?action=loss">            <!-- LEFT SIDE -->
            <section class="form-left">
                <!-- QUESTION TITLE: What item are you looking for? -->
                <div class="question-box-wrapper">
                    <div class="question-box">
                        <h4>What item are you looking for?</h4>
                        <label for="name">
                            Item Name<span class="required">required field</span>
                        </label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <!-- FORM ROW: Category === Brand -->
                    <div class="form-row">
                        <!-- QUESTION: Category of Item (what type of item) -->
                        <div class="question-box">
                            <label for="category">
                                Category<span class="required">required field</span>
                            </label>
                            <select id="category" name="category_id" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $categoryOption): ?>
                                    <option value="<?= $categoryOption["category_id"] ?>">
                                        <?= htmlspecialchars($categoryOption["name"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- QUESTION: Brand of the Item -->
                        <div class="question-box">
                            <label for="brand-id">
                                Brand<span class="required">required field</span>
                            </label>
                            <select id="brand-id" name="brand_id" required>
                                <option value="">Select Brand</option>
                                <?php foreach ($brands as $brandOption): ?>
                                    <option value="<?= $brandOption["brand_id"] ?>"
                                            data-categories="<?= htmlspecialchars($brandOption["category_ids"] ?? "") ?>">
                                        <?= htmlspecialchars($brandOption["name"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- DESCRIPTION TEXT AREA -->
                    <div class="question-box">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"></textarea>
                    </div>
                </div>

                <!-- QUESTION TITLE: Where was it last seen? -->
                <div class="question-box-wrapper">
                    <h4>Where was it last seen?</h4>

                    <!-- FORM ROW: Building === Room Number -->
                    <div class="form-row">
                        <!-- QUESTION: Building -->
                        <div class="question-box">
                            <label>Building <span class="required">required field</span></label>
                            <select id="building_id" name="building_id" required>
                                <option value="">Select Building</option>
                                <?php foreach ($buildings as $building): ?>
                                    <option value="<?= $building["building_id"] ?>" data-max-level="<?= $building["max_level"] ?>">
                                        <?= htmlspecialchars($building["name"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- QUESTION: Room number -->
                        <div class="question-box">
                            <label>Floor number <span class="required">required field</span></label>
                            <input type="number" id="floor_number" name="floor_number" min="1" required>
                        </div>
                    </div>

                    <!-- QUESTION: Area -->
                    <div class="form-row">
                        <div class="question-box">
                            <label>Area <span class="required">at least one required</span></label>
                            <select id="area_id" name="area_id">
                                <option value="">Select Area</option>
                                <?php foreach ($areas as $area): ?>
                                    <option value="<?= $area["area_id"] ?>" data-building="<?= $area["building_id"] ?>" data-level="<?= $area["level"] ?>">
                                        <?= htmlspecialchars($area["name"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- QUESTION: Room number -->
                        <div class="question-box">
                            <label>Room <span class="required">at least one required</span></label>
                            <select id="room_id" name="room_id">
                                <option value="">Select Room</option>
                                <?php foreach ($rooms as $room): ?>
                                    <option value="<?= $room["room_id"] ?>" data-building="<?= $room["building_id"] ?>" data-level="<?= $room["level"] ?>">
                                        <?= htmlspecialchars($room["name"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="question-box-wrapper">
                    <!-- QUESTION TITLE: When did you last have it? -->
                    <h4>When did you last have it?</h4>

                    <!-- FORM ROW: Date Found === Time Found -->
                    <div class="form-row">
                        <!-- QUESTION: Date Last Seen -->
                        <div class="question-box">
                            <label>
                                Date Last Seen
                                <span class="required">required field</span>
                            </label>
                            <input type="date" name="when_found" required>
                        </div>

                        <!-- QUESTION: Estimated Time-->
                        <div class="question-box">
                            <label>
                                Estimated Time
                                <span class="required">required field</span>
                            </label>
                            <input type="time" name="when_found_time" required>
                        </div>
                    </div>
                </div>
            </section>

            <!-- RIGHT SIDE -->
            <section class="form-right">

                <!-- UPLOAD IMAGES (up to 4) -->
                <label class="upload-box">
                    <input type="file" name="images[]" accept="image/*" multiple>
                    <span class="upload-text">Upload up to 4 images </span>
                    <div class="preview-container"></div>
                </label>

                <!-- SUBMIT BUTTON -->
                <button type="submit" class="form-button submit-button">
                    Submit Item
                </button>
            </section>
        </form>
    </div>
    <div id="confirm-modal" class="confirm-modal" hidden>
        <div class="confirm-modal-content">
            <p id="confirm-modal-text"></p>
            <div class="confirm-modal-actions">
                <button type="button" id="confirm-modal-cancel" class="form-button no-button">Cancel</button>
                <button type="button" id="confirm-modal-yes" class="form-button yes-button">Yes</button>
            </div>
        </div>
    </div>

    <div id="toast"></div>
</body>

</html>