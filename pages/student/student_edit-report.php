<?php
    require_once "../../controllers/StudentAuth.php";
    require_once "../../controllers/LocationController.php";
    require_once "../../controllers/CategoriesController.php";
    require_once "../../controllers/BrandsController.php";
    require_once "../../db.php";
    require_once "../../models/Reports.php";

    $reportsModel = new Reports($conn);

    $reportId = $_GET["id"] ?? "";

    if (!is_numeric($reportId)) {
        header("Location: student_dashboard.php");
        exit();
    }

    $report = $reportsModel->getReportById($reportId);

    if (!$report) {
        header("Location: student_dashboard.php?error=not_found");
        exit();
    }

    // Ownership check — mirrors the one enforced server-side on submit
    if ((int) $report["student_id"] !== (int) $_SESSION["user_id"]) {
        header("Location: student_dashboard.php?error=unauthorized");
        exit();
    }

    // Lock editing once staff has acted on it — mirrors handleEditReport()
    if ($report["status"] !== "Active") {
        header("Location: student_dashboard.php?error=locked");
        exit();
    }

    $isClaim = ($report["type"] === "Claim request");
    $selectedBuildingId = "";
    $selectedFloor = "";

    if (!empty($report["room_id"])) {
        foreach ($rooms as $r) {
            if ($r["room_id"] == $report["room_id"]) {
                $selectedBuildingId = $r["building_id"];
                $selectedFloor = $r["level"];
                break;
            }
        }
    } elseif (!empty($report["area_id"])) {
        foreach ($areas as $a) {
            if ($a["area_id"] == $report["area_id"]) {
                $selectedBuildingId = $a["building_id"];
                $selectedFloor = $a["level"];
                break;
            }
        }
    }

    // Split combined datetime columns back into separate date/time inputs
    $whenLostDate = $whenLostTime = $whenFoundDate = $whenFoundTime = "";

    if (!empty($report["when_lost"])) {
        [$whenLostDate, $whenLostTime] = explode(" ", $report["when_lost"]);
    }

    if (!empty($report["when_found"])) {
        [$whenFoundDate, $whenFoundTime] = explode(" ", $report["when_found"]);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArcherFind - Edit Report</title>

    <link rel="stylesheet" href="../../styles/global/global.css">
    <link rel="stylesheet" href="../../styles/global/navbar.css">
    <link rel="stylesheet" href="../../styles/global/modal.css">
    <link rel="stylesheet" href="../../styles/student/student_lost-and-found-form.css">
    <script src="../../javascript/global/navbar.js" defer></script>
    <script src="../../javascript/global/toast.js" defer></script>
    <script src="../../javascript/global/modal.js" defer></script>
    <script src="../../javascript/student/student_edit-report.js" defer></script>
</head>

<body>

    <div class="surrender-wrapper">
        <div class="form-title">
            <h2>Edit Report</h2>
            <p>You can update this <?= htmlspecialchars($report["type"]) ?> while it's still pending review.</p>
        </div>

        <form class="form-wrapper" method="POST" action="../../controllers/ReportsController.php?action=edit">
            <input type="hidden" name="report_id" value="<?= htmlspecialchars($report["report_id"]) ?>">

            <section class="form-left">

                <div class="question-box-wrapper">
                    <h4>Item Details</h4>

                    <?php if ($isClaim): ?>
                        <!-- Claim requests: item identity mirrors the actual stored item, not user-editable -->
                        <div class="question-box">
                            <label>Item Name</label>
                            <input type="text" value="<?= htmlspecialchars($report["item_name"]) ?>" readonly>
                        </div>
                        <div class="question-box">
                            <label>Item Description</label>
                            <textarea readonly><?= htmlspecialchars($report["item_description"]) ?></textarea>
                        </div>
                    <?php else: ?>
                        <!-- Loss Report / Surrender Form: fully student-authored, editable -->
                        <div class="question-box">
                            <label for="name">Item Name<span class="required">required field</span></label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($report["item_name"]) ?>" required>
                        </div>

                        <div class="form-row">
                            <div class="question-box">
                                <label for="category">Category<span class="required">required field</span></label>
                                <select id="category" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $categoryOption): ?>
                                        <option value="<?= $categoryOption["category_id"] ?>" <?= $report["category_id"] == $categoryOption["category_id"] ? "selected" : "" ?>>
                                            <?= htmlspecialchars($categoryOption["name"]) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="question-box">
                                <label for="brand-id">Brand<span class="required">required field</span></label>
                                <select id="brand-id" name="brand_id" required>
                                    <option value="">Select Brand</option>
                                    <?php foreach ($brands as $brandOption): ?>
                                        <option value="<?= $brandOption["brand_id"] ?>"
                                                data-categories="<?= htmlspecialchars($brandOption["category_ids"] ?? "") ?>"
                                                <?= $report["brand_id"] == $brandOption["brand_id"] ? "selected" : "" ?>>
                                            <?= htmlspecialchars($brandOption["name"]) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="question-box">
                            <label for="description">Description</label>
                            <textarea id="description" name="description"><?= htmlspecialchars($report["item_description"]) ?></textarea>
                        </div>
                    <?php endif; ?>

                    <?php if ($isClaim): ?>
                        <div class="question-box">
                            <label for="description">Describe Features</label>
                            <textarea id="description" name="description"><?= htmlspecialchars($report["extra_details"]) ?></textarea>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="question-box-wrapper">
                    <h4>Location</h4>

                    <div class="form-row">
                        <div class="question-box">
                            <label>Building</label>
                            <select id="building_id" name="building_id">
                                <option value="">Select Building</option>
                                <?php foreach ($buildings as $building): ?>
                                    <option value="<?= $building["building_id"] ?>"
                                            data-max-level="<?= htmlspecialchars((string) $building["max_level"]) ?>"
                                            <?= $selectedBuildingId == $building["building_id"] ? "selected" : "" ?>>
                                        <?= htmlspecialchars($building["name"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="question-box">
                            <label>Floor number</label>
                            <input type="number" id="floor_number" name="floor_number" min="1" value="<?= htmlspecialchars((string) $selectedFloor) ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="question-box">
                            <label>Area</label>
                            <select id="area_id" name="area_id">
                                <option value="">Select Area</option>
                                <?php foreach ($areas as $area): ?>
                                    <option value="<?= $area["area_id"] ?>"
                                            data-building="<?= $area["building_id"] ?>"
                                            data-level="<?= $area["level"] ?>"
                                            <?= $report["area_id"] == $area["area_id"] ? "selected" : "" ?>>
                                        <?= htmlspecialchars($area["name"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="question-box">
                            <label>Room</label>
                            <select id="room_id" name="room_id">
                                <option value="">Select Room</option>
                                <?php foreach ($rooms as $room): ?>
                                    <option value="<?= $room["room_id"] ?>"
                                            data-building="<?= $room["building_id"] ?>"
                                            data-level="<?= $room["level"] ?>"
                                            <?= $report["room_id"] == $room["room_id"] ? "selected" : "" ?>>
                                        <?= htmlspecialchars($room["name"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="question-box-wrapper">
                    <?php if ($report["type"] === "Surrender Form"): ?>
                        <h4>When was this found?</h4>
                        <div class="form-row">
                            <div class="question-box">
                                <label>Date Found</label>
                                <input type="date" name="when_found" value="<?= htmlspecialchars($whenFoundDate) ?>">
                            </div>
                            <div class="question-box">
                                <label>Time Found</label>
                                <input type="time" name="when_found_time" value="<?= htmlspecialchars($whenFoundTime) ?>">
                            </div>
                        </div>
                    <?php else: ?>
                        <h4>When was this lost?</h4>
                        <div class="form-row">
                            <div class="question-box">
                                <label>Date Lost</label>
                                <input type="date" name="<?= $isClaim ? "date_lost" : "when_lost" ?>" value="<?= htmlspecialchars($whenLostDate) ?>">
                            </div>
                            <div class="question-box">
                                <label>Time Lost</label>
                                <input type="time" name="<?= $isClaim ? "time_lost" : "when_lost_time" ?>" value="<?= htmlspecialchars($whenLostTime) ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <section class="form-right">
                <button type="submit" class="form-button submit-button">Save Changes</button>
                <button type="button" class="form-button no-button" onclick="location.href='student_dashboard.php'">Cancel</button>
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