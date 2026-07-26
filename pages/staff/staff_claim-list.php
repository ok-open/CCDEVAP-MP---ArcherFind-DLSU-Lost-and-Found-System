<?php
    require_once "../../controllers/StaffAuth.php";
    require_once "../../controllers/ReportListController.php"; //Starts the controller
    //some variables are red underline due to being undefined, this is because it is initialized in the controller
    //it will still work
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Claim Requests</title>
    <link rel="stylesheet" href="../../styles/global/global.css">
    <link rel="stylesheet" href="../../styles/global/navbar.css">
    <link rel="stylesheet" href="../../styles/global/staffadmin_lists.css">
    <link rel="stylesheet" href="../../styles/global/toast.css">
    <script src="../../javascript/global/toast.js" defer></script>
    <script src="../../javascript/global/navbar.js" defer></script>
    <script src="../../javascript/admin/staffadmin_lists.js" defer></script>
</head>

<body>
    <!------------------------ NAVIGATION BAR / HEADER ------------------------>
    <header>
        <!-- LOGO (links to ADMIN DASHBOARD) -->
        <button class="archerfind-logo" onclick="window.location.href='../../pages/staff/staff_dashboard.php'">
            <h1>ArcherFind</h1>
            <img class="logo" src="../../assets/LOGOS/AF-ORIGINAL.png" alt="ArcherFind logo">
        </button>

        <!-- NAVBAR OPTIONS -->
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="../../pages/staff/staff_dashboard.php">Dashboard</a></li>
                <li><a href="../../pages/staff/staff_claim-list.php" class="current-page">Claim Requests</a></li>
                <li><a href="../../pages/staff/staff_report-list.php">Lost Reports</a></li>
                <li><a href="../../pages/staff/staff_surrender-list.php">Surrender Forms</a></li>
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
                                onclick="location.href='../../pages/staff/staff_manage-account.php'">Manage
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
                                        onclick="location.href='../../pages/staff/staff_dashboard.php'">
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
                <li><a href="../../pages/staff/staff_dashboard.php">Dashboard</a></li>
                <li><a href="../../pages/staff/staff_claim-list.php" class="current-page">Claim Requests</a></li>
                <li><a href="../../pages/staff/staff_report-list.php">Lost Reports</a></li>
                <li><a href="../../pages/staff/staff_surrender-list.php">Surrender Forms</a></li>
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
                                onclick="location.href='../../pages/staff/staff_manage-account.php'">Manage
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
                                        onclick="location.href='../../pages/staff/staff_dashboard.php'">
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
   
    <!-- CONTROLS -->
    <!-- GET function to retrieve the input from the staff-->
    <form method="GET" action="" class="controls-wrapper">
        <div class="title">
            <h2>Evaluate Claim Requests</h2>
            <p>Review submitted ownership details and credentials to verify if a claimant is the rightful owner of a found item.</p>
        </div>

        <div class="controls">
            <!-- Search bar (maps to 'search' query param) -->
            <input type="text" name="search" placeholder="Search for an item..." 
                class="control-box search-bar" 
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

            <!-- Sort Dropdown (maps to 'sort' query param) -->
            <select name="sort" class="control-box sort-dropdown" onchange="this.form.submit()">
                <option value="recent" <?= (($_GET['sort'] ?? '') === 'recent') ? 'selected' : '' ?>>Sort: Recent</option>
                <option value="name" <?= (($_GET['sort'] ?? '') === 'name') ? 'selected' : '' ?>>Sort: Name</option>
            </select>

            <!-- Filter Dropdown (maps to 'category' query param) -->
            <select name="category" class="control-box filter-dropdown" onchange="this.form.submit()">
                <option value="">Filter: All</option>
                <!-- The dropdown options are added dynamically with categories from the database -->
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['name']) ?>" <?= (($_GET['category'] ?? '') === $cat['name']) ? 'selected' : '' ?>> 
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <!-- REQUESTS -->
    <div class="requests-wrapper">
        <!-- REQUEST RECORD -->
        <?php if (empty($claimRequests)): ?> <!-- 1. Check first if there are active claim requests -->
        <div class="no-records" style="text-align: center; padding: 50px; color: #777;">
            <h3>No Active Claim Requests Found</h3>
        </div>
        <!-- 2. If there is active, then begin loop to display each record-->
        <!-- Each record is stored in $report -->
        <?php else: ?> 
            <?php foreach ($claimRequests as $recordIndex => $report): ?>
                <?php 
                    // 3. Separate the comma-delimited image paths into an array
                    $imagePaths = !empty($report['found_item_image']) ? explode(',', $report['found_item_image']) : [];
                ?>
        <div class="request-record">
            <!-------------------------------- REQUEST IMAGE ( CAROUSEL ) -------------------------------->
                <div class="request-image">
                    <?php if (!empty($imagePaths)): ?>
                        <!-- Full-width images dynamically grouped by record index -->
                        <?php foreach ($imagePaths as $imgIndex => $path): ?>
                            <!-- Note the class "slide-group-$recordIndex" the recordIndex indicates which record the image belongs to-->
                            <!-- 4. Generate the div for each image slide -->
                            <div class="mySlides fade slide-group-<?= $recordIndex ?>" style="display: <?= $imgIndex === 0 ? 'block' : 'none'; ?>">
                                <img class="ImgItem" src="<?= htmlspecialchars($path) ?>" alt="Item Image">
                            </div>
                        <?php endforeach; ?>

                        <!-- Next and previous buttons (Passing the recordIndex to plusSlides) -->
                        <a class="prev" onclick="plusSlides(-1, <?= $recordIndex ?>)">&#10094;</a>
                        <a class="next" onclick="plusSlides(1, <?= $recordIndex ?>)">&#10095;</a>

                        <!-- The dots/circles (Passing the recordIndex to currentSlide) -->
                        <div style="text-align:center">
                            <?php foreach ($imagePaths as $imgIndex => $path): ?>
                                <!-- Note the class "dot-group-$recordIndex" -->
                                <span class="dot dot-group-<?= $recordIndex ?> <?= $imgIndex === 0 ? 'active-img' : '' ?>" 
                                    onclick="currentSlide(<?= $imgIndex + 1 ?>, <?= $recordIndex ?>)"></span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <!-- Fallback placeholder if the report has no photos -->
                        <div class="mySlides fade" style="display: block;">
                            <img class="ImgItem" src="../../assets/placeholder-item.png" alt="No Image Uploaded">
                        </div>
                    <?php endif; ?>
                </div>

            <!-------------------------------- REQUEST ITEM DETAILS -------------------------------->
            <div class="request-item-details">
                <!-- ITEM NAME -->
                <div class="item-name">
                    <h2><?= htmlspecialchars($report['claim_item_name']) ?></h2>
                    <div class="request-buttons-panel">
                        <!-- INFORMATION AND PROOF BUTTON -->
                        <a href="?selected_report=<?= $report['report_id'] ?>&search=<?= urlencode($search) ?>&category=<?= urlencode($category) ?>&sort=<?= urlencode($sortBy) ?>" 
                            class="request-button openPanelBtn" 
                            style="text-decoration: none; display: inline-flex; align-items: center;">
                                Information and Proof
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M359.52-174.91q-84.11-33.44-138.88-104.21-54.77-70.77-65.77-160.88h91.76q8.76 51.8 38.16 94.49 29.41 42.68 74.73 69.68v100.92ZM503.11-71.87q-28.35 0-48.36-20.01-20.01-20.01-20.01-48.36v-236.41q0-28.35 20.01-48.24t48.36-19.89h91.35q17.15 0 32.44 8.19 15.3 8.2 24.49 22.87L671-384.3h149q28.35 0 48.24 19.89t19.89 48.24v175.93q0 28.35-19.89 48.36Q848.35-71.87 820-71.87H503.11ZM140.24-515.22q-28.35 0-48.36-20.01-20.01-20.01-20.01-48.36V-820q0-28.35 20.01-48.24t48.36-19.89h91.35q17.15 0 32.44 8.2 15.3 8.19 24.49 22.86l19.61 29.42h149q28.35 0 48.24 19.89t19.89 48.24v175.93q0 28.35-19.89 48.36-19.89 20.01-48.24 20.01H140.24ZM717.37-480q0-63.33-31.4-117.27-31.4-53.95-85.49-86.66v-100.68Q694.59-746.65 751.36-664q56.77 82.65 56.77 184h-90.76Z"/></svg>
                        </a>
                        
                        <!-- RESOLVE BUTTON -->
                        <!-- Toast messages show for short time only due to refresh of page -->
                        <button type="button" class="request-button accept-btn" onclick="onAcceptRequest(), submitStatusAction(<?= $report['report_id'] ?>, 'resolve')">
                            Mark as Resolved
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M423.28-291.22 708.87-576.8l-62.46-62.7-223.13 223.13L312.15-527.5l-62.45 62.7 173.58 173.58ZM480-71.87q-84.91 0-159.34-32.12-74.44-32.12-129.5-87.17-55.05-55.06-87.17-129.5Q71.87-395.09 71.87-480t32.12-159.34q32.12-74.44 87.17-129.5 55.06-55.05 129.5-87.17 74.43-32.12 159.34-32.12t159.34 32.12q74.44 32.12 129.5 87.17 55.05 55.06 87.17 129.5 32.12 74.43 32.12 159.34t-32.12 159.34q-32.12 74.44-87.17 129.5-55.06 55.05-129.5 87.17Q564.91-71.87 480-71.87Z"/></svg>
                        </button><br>
                        
                        <!-- CLOSE BUTTON -->
                        <button type="button" class="request-button reject-btn" onclick="onRejectRequest(), submitStatusAction(<?= $report['report_id'] ?>, 'close')">
                            Close Report
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M376.72-296.65 480-399.93l103.28 103.28 60.07-60.07L540.07-460l103.28-103.28-60.07-60.07L480-520.07 376.72-623.35l-60.07 60.07L419.93-460 316.65-356.72l60.07 60.07Zm-99.35 184.78q-37.78 0-64.39-26.61t-26.61-64.39v-514.5h-45.5v-91H354.5v-45.5h250.52v45.5h214.11v91h-45.5v514.5q0 37.78-26.61 64.39t-64.39 26.61H277.37Z"/></svg>
                        </button><br>
                    </div>
                </div>

                <hr>

                <div class="item-column-wrapper">
                    <!-- COLUMN 1: DATE, TIME, LOCATION -->
                    <div class="item-column">
                        <!-- DATE LOST -->
                        <div class="detail-box">
                            <label>Estimated Date Lost</label>
                            <input type="date" value="<?= htmlspecialchars($report['date_lost']) ?>" readonly>
                        </div>

                        <!-- TIME LOST -->
                        <div class="detail-box">
                            <label>Estimated Time Lost</label>
                            <!-- Formats time to Hour, Minute, PM/AM -->
                            <input type="time" value="<?= htmlspecialchars(date('H:i', strtotime($report['time_lost']))) ?>" readonly>
                        </div>

                        <!-- LOCATION -->
                        <div class="detail-box">
                            <label>Location Lost</label>
                            <input type="text" value="<?= htmlspecialchars($report['location_lost']) ?>" readonly>
                        </div>
                    </div>

                    <!-- COLUMN 2: USER DETAILS -->
                    <div class="item-column">
                        <!-- SUBMITTED BY -->
                        <div class="detail-box">
                            <label>Submitted By</label>
                            <!-- Dynamic integration of the report's student information -->
                            <input type="text" value="<?= htmlspecialchars($report['student_name'] . '') ?>" readonly>
                        </div>

                        <!-- CONTACT EMAIL -->
                        <div class="detail-box">
                            <label>Contact Email</label>
                            <input type="email" value="<?= htmlspecialchars($report['student_email']) ?>" readonly>
                        </div>

                        <!-- FILED ON -->
                        <div class="detail-box">
                            <label>Filed On</label>
                            <input type="date" value="<?= htmlspecialchars($report['filed_on']) ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

        
    <div id="toast"></div>
    <!-- This groups information for 1 record. -->
    </div>

    <?php
    // 1. Locate the selected claim request from your main array
    $selectedClaim = null;
    //selectedReportID gets its value when the user clicks "Information and Proof", the selected_report goes to the controller
    if (!empty($selectedReportId)) {
        foreach ($claimRequests as $req) { //Loop through the retrieved records, and get the record selected based on the ID
            if ($req['report_id'] == $selectedReportId) {
                $selectedClaim = $req;
                break;
            }
        }
    }
    // selectedClaim now contains the info of the chosen record
    ?>
    <!-- Only show/display the panel if a claim has been selected -->
    <div id="SidePanel_Iconsee" class="<?= !empty($selectedClaim) ? 'open' : '' ?>" style="<?= empty($selectedClaim) ? 'display: none;' : 'display: block;' ?>">
        
        <!-- Close Button: Reloads page clearing 'selected_report' while keeping filters intact -->
        <a href="?search=<?= urlencode($search) ?>&category=<?= urlencode($category) ?>&sort=<?= urlencode($sortBy) ?>" 
        class="close" id="closePanelBtn" style="text-decoration: none; text-align: center;">close</a>

        <?php if ($selectedClaim): ?>
            <!-- SECTION A: FOUND ITEM DETAILS (FROM DATABASE) -->
            <h3>Information retrieved from database</h3>

            <div class="LostDetails">
                <h4><?= htmlspecialchars($selectedClaim['found_item_name'] ?? 'Unlinked Storage Item') ?></h4>
                
                <h5>Date Found:</h5>
                <p><?= !empty($selectedClaim['date_found']) ? htmlspecialchars(date('m-d-Y', strtotime($selectedClaim['date_found']))) : 'N/A' ?></p> <br>
                
                <h5>Time Found:</h5>
                <p><?= !empty($selectedClaim['time_found']) ? htmlspecialchars(date('h:i A', strtotime($selectedClaim['time_found']))) : 'N/A' ?></p> <br>
                
                <h5>Location:</h5>
                <p><?= htmlspecialchars($selectedClaim['location_found'] ?? 'Unknown Location') ?></p> <br>
            </div>

            <br>
            
            <!--  SECTION B: PROOF OF OWNERSHIP -->
            <h3>Proof of Ownership</h3>
            
            <!-- Render Claimant's Uploaded Proof Images -->
            <div id="SidePanel_ImgContainer" >
                <?php if (!empty($selectedClaim['proof_images'])): ?>
                    <?php 
                    $proofImages = explode(',', $selectedClaim['proof_images']);
                    foreach ($proofImages as $pImg): 
                    ?>
                        <img class="ImgFromDB" 
                            alt="Ownership Proof Image" 
                            src="<?= htmlspecialchars($pImg) ?>" 
                            >
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #0c2100; font-style: italic; font-size: 0.9em;">No proof images uploaded.</p>
                <?php endif; ?>
            </div>

            <!-- Render Claimant's Written Statement -->
            <div class="LostDetails">
                <p style="font-style: italic; line-height: 1.5; color: #0c2100;">
                    "<?= htmlspecialchars($selectedClaim['proof_of_ownership_text'] ?: 'No physical description text provided.') ?>"
                </p>
            </div>

        <?php else: ?>
            <p style="color: #0c2100; text-align: center; padding: 40px 10px;">Select a claim request's information button to view matched details.</p>
        <?php endif; ?>
    </div>

    <!-- HIDDEN FORM FOR STATUS ACTIONS -->
    <!-- this form submits the report ID, and wether we are "Resolve" or "Close" the report to the Controller -->
    <form id="statusActionForm" method="POST" style="display: none;">
        <input type="hidden" name="report_id" id="formReportId">
        <input type="hidden" name="action" id="formAction">
        
        <!-- Preserves current filter states upon submission -->
        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
        <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sortBy) ?>">
    </form>

    <div id="ExpandPanel_ImgItem" class="modal">
        <img class="modal-content" id="imgExpand">
    </div>

</body>

</html>