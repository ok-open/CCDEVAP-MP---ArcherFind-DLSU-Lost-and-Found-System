<?php

session_start();

require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../models/Reports.php";
require_once __DIR__ . "/../models/Items.php";

$reportsModel = new Reports($conn);
$itemModel = new Items($conn);

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'dashboard') {
    if (!isset($_SESSION["user_id"])) {
        header("Content-Type: application/json");
        echo json_encode(["error" => "Unauthorized"]);
        exit();
    }

    handleDashboard($reportsModel, $_SESSION["user_id"]);
    exit();
}

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

switch ($action) {

    case 'claim':
        handleClaimRequest($reportsModel, $itemModel);
        break;

    case 'loss':
        handleLossReport($reportsModel);
        break;

    case 'surrender':
        handleSurrenderForm($reportsModel);
        break;

    case 'edit':
        handleEditReport($reportsModel);
        break;

    default:
        header("Location: ../pages/student/student_home.php?error=invalid_action");
        exit();
}

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
function handleDashboard($reportsModel, $studentId)
{
    header("Content-Type: application/json");

    echo json_encode([
        "stats" => $reportsModel->getStatistics($studentId),
        "reports" => $reportsModel->getHistory($studentId),
        "chart" => $reportsModel->getLocationFrequency($studentId)
    ]);
}

/*
|--------------------------------------------------------------------------
| Edit Report
|--------------------------------------------------------------------------
*/
function handleEditReport($reportsModel)
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: ../pages/student/student_dashboard.php");
        exit();
    }

    $reportId = $_POST["report_id"] ?? null;

    if (!$reportId) {
        header("Location: ../pages/student/student_dashboard.php?error=missing_report");
        exit();
    }

    $report = $reportsModel->getReportById($reportId);

    if (!$report) {
        header("Location: ../pages/student/student_dashboard.php?error=not_found");
        exit();
    }

    // Ownership check — a student can only edit their own reports
    if ((int) $report["student_id"] !== (int) $_SESSION["user_id"]) {
        header("Location: ../pages/student/student_dashboard.php?error=unauthorized");
        exit();
    }

    // Lock editing once staff has acted on it
    if ($report["status"] !== "Active") {
        header("Location: ../pages/student/student_dashboard.php?error=locked");
        exit();
    }

    $roomId = !empty($_POST["room_id"]) ? $_POST["room_id"] : null;
    $areaId = !empty($_POST["area_id"]) ? $_POST["area_id"] : null;

    if ($report["type"] === "Claim request") {
        // Item identity fields stay as they were — only location/details/date editable
        $itemName = $report["item_name"];
        $itemDescription = $report["item_description"];
        $categoryId = $report["category_id"];
        $brandId = $report["brand_id"];

        // "description" textarea on this form maps to extra_details
        // (the "Describe Features" field), not item_description
        $extraDetails = trim($_POST["description"] ?? $report["extra_details"]);

        $whenLost = null;
        if (!empty($_POST["date_lost"]) && !empty($_POST["time_lost"])) {
            $whenLost = $_POST["date_lost"] . " " . $_POST["time_lost"];
        }
        $whenFound = null;
    } else {
        // Loss Report / Surrender Form — fully student-authored, all fields editable
        $itemName = trim($_POST["name"] ?? $report["item_name"]);
        $itemDescription = trim($_POST["description"] ?? $report["item_description"]);
        $categoryId = !empty($_POST["category_id"]) ? $_POST["category_id"] : null;
        $brandId = !empty($_POST["brand_id"]) ? $_POST["brand_id"] : null;

        // extra_details isn't used by these two types — leave untouched
        $extraDetails = $report["extra_details"];

        $whenLost = null;
        $whenFound = null;

        if ($report["type"] === "Loss Report") {
            if (!empty($_POST["when_lost"]) && !empty($_POST["when_lost_time"])) {
                $whenLost = $_POST["when_lost"] . " " . $_POST["when_lost_time"];
            }
        } else {
            if (!empty($_POST["when_found"]) && !empty($_POST["when_found_time"])) {
                $whenFound = $_POST["when_found"] . " " . $_POST["when_found_time"];
            }
        }
    }

    $reportsModel->updateReport(
        $reportId,
        $itemName,
        $itemDescription,
        $categoryId,
        $brandId,
        $roomId,
        $areaId,
        $whenLost,
        $whenFound,
        $extraDetails
    );

    $removedImageIds = [];
    if (!empty($_POST["removed_image_ids"])) {
        $removedImageIds = array_filter(
            array_map("intval", explode(",", $_POST["removed_image_ids"])),
            function ($id) {
                return $id > 0;
            }
        );
    }

    if (!empty($removedImageIds)) {
        $imagesToDelete = $reportsModel->getReportImagesByIds($reportId, $removedImageIds);

        foreach ($imagesToDelete as $imageRow) {
            $relativePath = str_replace("../../", "", $imageRow["img_filepath"]);
            $absolutePath = dirname(__DIR__) . "/" . ltrim($relativePath, "/");

            if (is_file($absolutePath)) {
                @unlink($absolutePath);
            }
        }

        $reportsModel->deleteReportImagesByIds($reportId, $removedImageIds);
    }

    // Allow students to append up to 4 new proof images while editing.
    $typeUploadMap = [
        "Claim request" => ["IMG_ClaimRequest", "claim_"],
        "Loss Report" => ["IMG_LossReport", "loss_"],
        "Surrender Form" => ["IMG_SurrenderForm", "surrender_"]
    ];

    if (isset($typeUploadMap[$report["type"]])) {
        [$subfolder, $prefix] = $typeUploadMap[$report["type"]];
        handleReportImageUpload($reportId, $subfolder, $prefix, $reportsModel);
    }

    header("Location: ../pages/student/student_dashboard.php?success=report_updated");
    exit();
}

/*
|--------------------------------------------------------------------------
| Claim Request
|--------------------------------------------------------------------------
*/
function handleClaimRequest($reportsModel, $itemModel)
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: ../pages/student/student_claim-request.php");
        exit();
    }

    if (empty($_POST["item_id"])) {
        header("Location: ../pages/student/student_home.php?error=noitem");
        exit();
    }

    $itemId = $_POST["item_id"];

    // Pull item info via the Model instead of inline SQL in the controller
    $item = $itemModel->getItemById($itemId);

    if (!$item) {
        header("Location: ../pages/student/student_home.php?error=itemnotfound");
        exit();
    }

    $roomId = !empty($_POST["room_id"]) ? $_POST["room_id"] : null;
    $areaId = !empty($_POST["area_id"]) ? $_POST["area_id"] : null;

    $whenLost = null;
    if (!empty($_POST["date_lost"]) && !empty($_POST["time_lost"])) {
        $whenLost = $_POST["date_lost"] . " " . $_POST["time_lost"];
    }

    $details = trim($_POST["description"] ?? "");

    $result = $reportsModel->createReport(
        $_SESSION["user_id"],
        $item["name"],
        $item["description"],
        $item["category_id"],
        $item["brand_id"],
        $itemId,
        $roomId,
        $areaId,
        $whenLost,
        null, // when_found — not applicable for a claim request
        $details,
        "Claim request"
    );

    if (!$result) {
        header("Location: ../pages/student/student_claim-request.php?id=" . $itemId . "&error=failed");
        exit();
    }

    $reportId = $reportsModel->getLastInsertId();

    handleReportImageUpload($reportId, "IMG_ClaimRequest", "claim_", $reportsModel);

    header(
        "Location: ../pages/student/student_claim-request.php?id="
        . $itemId .
        "&success=submitted&item=" . urlencode($item["name"])
    );
    exit();
}

/*
|--------------------------------------------------------------------------
| Loss Report
|--------------------------------------------------------------------------
*/
function handleLossReport($reportsModel)
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: ../pages/student/student_report-item.php");
        exit();
    }

    if (empty($_POST["name"])) {
        header("Location: ../pages/student/student_report-item.php?error=noitem");
        exit();
    }

    $itemName = trim($_POST["name"]);
    $itemDescription = trim($_POST["description"] ?? "");
    $categoryId = !empty($_POST["category_id"]) ? $_POST["category_id"] : null;
    $brandId = !empty($_POST["brand_id"]) ? $_POST["brand_id"] : null;

    $roomId = !empty($_POST["room_id"]) ? $_POST["room_id"] : null;
    $areaId = !empty($_POST["area_id"]) ? $_POST["area_id"] : null;

    $whenLost = null;
    if (!empty($_POST["when_found"]) && !empty($_POST["when_found_time"])) {
        $whenLost = $_POST["when_found"] . " " . $_POST["when_found_time"];
    }

    $result = $reportsModel->createReport(
        $_SESSION["user_id"],
        $itemName,
        $itemDescription,
        $categoryId,
        $brandId,
        null,       // item_id — student doesn't know which stored item is theirs
        $roomId,
        $areaId,
        $whenLost,  // when_lost — corrected to line up with its real slot
        null,       // when_found — not applicable for a loss report
        "",         // extra_details
        "Loss Report"
    );

    if (!$result) {
        header("Location: ../pages/student/student_report-item.php?error=failed");
        exit();
    }

    $reportId = $reportsModel->getLastInsertId();

    // Fixed: was incorrectly reusing IMG_SurrenderForm's folder
    handleReportImageUpload($reportId, "IMG_LossReport", "loss_", $reportsModel);

    header("Location: ../pages/student/student_report-item.php?success=submitted&item=" . urlencode($itemName));
    exit();
}

/*
|--------------------------------------------------------------------------
| Surrender Form
|--------------------------------------------------------------------------
*/
function handleSurrenderForm($reportsModel)
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: ../pages/student/student_surrender-form.php");
        exit();
    }

    if (empty($_POST["name"])) {
        header("Location: ../pages/student/student_surrender-form.php?error=noitem");
        exit();
    }

    $itemName = trim($_POST["name"]);
    $itemDescription = trim($_POST["description"] ?? "");
    $categoryId = !empty($_POST["category_id"]) ? $_POST["category_id"] : null;
    $brandId = !empty($_POST["brand_id"]) ? $_POST["brand_id"] : null;

    $roomId = !empty($_POST["room_id"]) ? $_POST["room_id"] : null;
    $areaId = !empty($_POST["area_id"]) ? $_POST["area_id"] : null;

    $whenFound = null;
    if (!empty($_POST["when_found"]) && !empty($_POST["when_found_time"])) {
        $whenFound = $_POST["when_found"] . " " . $_POST["when_found_time"];
    }

    $result = $reportsModel->createReport(
        $_SESSION["user_id"],
        $itemName,
        $itemDescription,
        $categoryId,
        $brandId,
        null,        // item_id — doesn't exist yet; trigger creates it once Resolved
        $roomId,
        $areaId,
        null,        // when_lost — not applicable for a surrender form
        $whenFound,  // when_found — corrected to line up with its real slot
        "",          // extra_details
        "Surrender Form"
    );

    if (!$result) {
        header("Location: ../pages/student/student_surrender-form.php?error=failed");
        exit();
    }

    $reportId = $reportsModel->getLastInsertId();

    handleReportImageUpload($reportId, "IMG_SurrenderForm", "surrender_", $reportsModel);

    header("Location: ../pages/student/student_surrender-form.php?success=submitted&item=" . urlencode($itemName));
    exit();
}

/*
|--------------------------------------------------------------------------
| Shared image upload handling for report proof images (up to 4)
|--------------------------------------------------------------------------
*/
function handleReportImageUpload($reportId, $subfolder, $prefix, $reportsModel)
{
    $allowedTypes = ["image/jpeg", "image/png", "image/jpg", "image/webp"];
    $allowedExtensions = ["jpg", "jpeg", "png", "webp", "jfif"];
    $uploadDirectory = dirname(__DIR__) . "/assets/" . $subfolder . "/";

    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    if (!isset($_FILES['images'])) {
        return;
    }

    $files = $_FILES['images'];
    $count = is_array($files['name']) ? count($files['name']) : 0;

    for ($i = 0; $i < $count && $i < 4; $i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;

        $extension = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions, true)) continue;

        $detectedType = "";
        if (function_exists("finfo_open")) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $detectedType = finfo_file($finfo, $files['tmp_name'][$i]) ?: "";
                finfo_close($finfo);
            }
        }

        $reportedType = $files['type'][$i] ?? "";
        if (!in_array($detectedType, $allowedTypes, true) && !in_array($reportedType, $allowedTypes, true)) {
            continue;
        }

        $filename = uniqid($prefix, true) . "." . $extension;
        $destination = $uploadDirectory . $filename;

        if (move_uploaded_file($files['tmp_name'][$i], $destination)) {
            $imagePath = "../../assets/" . $subfolder . "/" . $filename;
            $reportsModel->addImage($reportId, $imagePath);
        }
    }
}