<?php

session_start();

header("Content-Type: application/json");

require_once "../db.php";
require_once "../models/Dashboard.php";

/*
|--------------------------------------------------------------------------
| Admin / Staff Authorization
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION["user_id"]) ||
    !isset($_SESSION["role"]) ||
    !in_array($_SESSION["role"], ["Admin", "Staff"])
) {
    http_response_code(403);

    echo json_encode([
        "success" => false,
        "message" => "Unauthorized."
    ]);

    exit;
}

$dashboardModel = new Dashboard($conn);

/*
|--------------------------------------------------------------------------
| Date Filter
|--------------------------------------------------------------------------
*/

$fromInput = $_GET["from"] ?? null;
$toInput = $_GET["to"] ?? null;

$from = null;
$to = null;

if (!empty($fromInput) && !empty($toInput)) {
    $from = $fromInput . " 00:00:00";
    $to = $toInput . " 23:59:59";
}

/*
|--------------------------------------------------------------------------
| Dashboard Data
|--------------------------------------------------------------------------
*/

$userCounts = $dashboardModel->getUserCounts();
$reportTypes = $dashboardModel->getReportTypes($from, $to);
$inventoryStatuses = $dashboardModel->getInventoryStatus($from, $to);
$monthlyReportsResult = $dashboardModel->getMonthlyReports($from, $to);
$locationResult = $dashboardModel->getLocationFrequency($from, $to);
$claimStatistics = $dashboardModel->getClaimStatistics($from, $to);
$disposalStatistics = $dashboardModel->getDisposalStatistics($from, $to);
$resolutionResult = $dashboardModel->getResolutionTimes($from, $to);

/*
|--------------------------------------------------------------------------
| Report Types
|--------------------------------------------------------------------------
*/

$itemReports = [
    "Loss Report" => 0,
    "Claim request" => 0,
    "Surrender Form" => 0
];

foreach ($reportTypes as $report) {
    $itemReports[$report["type"]] = (int)$report["total"];
}

/*
|--------------------------------------------------------------------------
| Inventory Status
|--------------------------------------------------------------------------
*/

$inventoryStatus = [
    "In Storage" => 0,
    "Claimed" => 0,
    "Disposed" => 0
];

foreach ($inventoryStatuses as $item) {
    $inventoryStatus[$item["status"]] = (int)$item["total"];
}

/*
|--------------------------------------------------------------------------
| Monthly Reports
|--------------------------------------------------------------------------
*/

$monthlyReports = array_fill(0, 12, 0);

foreach ($monthlyReportsResult as $report) {
    $monthIndex = (int)$report["month"] - 1;
    $monthlyReports[$monthIndex] = (int)$report["total"];
}

/*
|--------------------------------------------------------------------------
| Location Frequency
|--------------------------------------------------------------------------
*/

$locationLabels = [];
$locationData = [];

foreach ($locationResult as $location) {
    $locationLabels[] = $location["name"];
    $locationData[] = (int)$location["total"];
}

/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

$totalReports = array_sum($itemReports);
$totalItems = array_sum($inventoryStatus);

$totalClaims = (int)($claimStatistics["total_claims"] ?? 0);
$acceptedClaims = (int)($claimStatistics["accepted_claims"] ?? 0);

$claimSuccessRate = $totalClaims > 0
    ? round(($acceptedClaims / $totalClaims) * 100, 1)
    : 0;

$totalDisposalItems = (int)($disposalStatistics["total_items"] ?? 0);
$disposedItems = (int)($disposalStatistics["disposed_items"] ?? 0);

$itemDisposalRate = $totalDisposalItems > 0
    ? round(($disposedItems / $totalDisposalItems) * 100, 1)
    : 0;

/*
|--------------------------------------------------------------------------
| JSON Response
|--------------------------------------------------------------------------
*/

echo json_encode([
    "success" => true,

    "totalUsers" => (int)($userCounts["total_users"] ?? 0),
    "students" => (int)($userCounts["students"] ?? 0),
    "staff" => (int)($userCounts["staff"] ?? 0),
    "admins" => (int)($userCounts["admins"] ?? 0),

    "itemReports" => array_values($itemReports),
    "inventoryStatus" => array_values($inventoryStatus),

    "totalReports" => $totalReports,
    "totalItems" => $totalItems,

    "locationLabels" => $locationLabels,
    "locationData" => $locationData,

    "monthlyReports" => $monthlyReports,

    "claimSuccessRate" => $claimSuccessRate,
    "acceptedClaims" => $acceptedClaims,
    "totalClaims" => $totalClaims,

    "itemDisposalRate" => $itemDisposalRate,
    "disposedItems" => $disposedItems,
    "totalDisposalItems" => $totalDisposalItems,

    "resolutionTimes" => [
        "lossMatch" => round((float)($resolutionResult["loss_match"] ?? 0), 1),
        "surrenderApproval" => round((float)($resolutionResult["surrender_approval"] ?? 0), 1),
        "claimVerification" => round((float)($resolutionResult["claim_verification"] ?? 0), 1)
    ]
]);