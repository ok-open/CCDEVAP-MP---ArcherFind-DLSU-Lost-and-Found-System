<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Automated Test Runner</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        h3 { border-bottom: 2px solid #333; padding-bottom: 5px; margin-top: 30px; }
        .test-box { background: #f9f9f9; border-left: 4px solid #ccc; padding: 10px 15px; margin: 10px 0; }
        .pass { border-color: #28a745; color: #155724; background: #d4edda; }
        .fail { border-color: #dc3545; color: #721c24; background: #f8d7da; }
    </style>
</head>
<body>

<h1>Automated Test Suite Runner</h1>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Include Reports class relative to testCases/ folder
require_once __DIR__ . '/../models/Reports.php';

// Custom PDO class to automatically rewrite MySQL GROUP_CONCAT syntax for SQLite compatibility
class SQLiteCompatiblePDO extends PDO {
    #[\ReturnTypeWillChange]
    public function prepare($statement, $driver_options = []) {
        // Rewrite MySQL GROUP_CONCAT(expr ORDER BY ... SEPARATOR ',') to SQLite GROUP_CONCAT(expr, ',')
        $statement = preg_replace(
            '/GROUP_CONCAT\s*\(\s*(.*?)\s+ORDER\s+BY\s+.*?\s+SEPARATOR\s+([\'"].*?[\'"])\s*\)/i',
            'GROUP_CONCAT($1, $2)',
            $statement
        );

        // Rewrite MySQL GROUP_CONCAT(expr ORDER BY ...) without SEPARATOR to SQLite GROUP_CONCAT(expr)
        $statement = preg_replace(
            '/GROUP_CONCAT\s*\(\s*(.*?)\s+ORDER\s+BY\s+.*?\s*\)/i',
            'GROUP_CONCAT($1)',
            $statement
        );

        return parent::prepare($statement, $driver_options);
    }
}

function assertTest($testNumber, $testName, $actualResult, $expectedResult) {
    $expectedText = $expectedResult ? 'PASS' : 'FAIL';
    $actualText = $actualResult ? 'PASS' : 'FAIL';
    $isSuccess = ($actualResult === $expectedResult);

    $statusClass = $isSuccess ? 'pass' : 'fail';

    echo "<div class='test-box {$statusClass}'>";
    echo "<strong>Test {$testNumber}: {$testName}</strong><br>";
    echo "<strong>Expected Result:</strong> " . $expectedText . "<br>";
    echo "<strong>Actual Result:</strong> " . $actualText;
    echo "</div>";
}

// 2. Initialize SQLite In-Memory Database using the custom class
try {
    $pdo = new SQLiteCompatiblePDO('sqlite::memory:');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Disable foreign key checks for manual test inputs
    $pdo->exec("PRAGMA foreign_keys = OFF;");

    // Polyfill MySQL-specific functions for SQLite compatibility
    $pdo->sqliteCreateFunction('DATE_FORMAT', function ($dateStr, $format) {
        if (!$dateStr) return null;
        return date('F d, Y', strtotime($dateStr));
    }, 2);

    $pdo->sqliteCreateFunction('CONCAT', function (...$args) {
        return implode('', $args);
    });

    $pdo->sqliteCreateFunction('NOW', function () {
        return date('Y-m-d H:i:s');
    });

    $pdo->sqliteCreateFunction('DATE', function ($dateStr) {
        if (!$dateStr) return null;
        return date('Y-m-d', strtotime($dateStr));
    }, 1);

    $pdo->sqliteCreateFunction('TIME', function ($dateStr) {
        if (!$dateStr) return null;
        return date('H:i:s', strtotime($dateStr));
    }, 1);

    // Polyfill DATE_SUB for SQLite compatibility
    $pdo->sqliteCreateFunction('DATE_SUB', function ($dateStr, $intervalStr) {
        if (!$dateStr) return null;
        return date('Y-m-d H:i:s', strtotime('-1 month', strtotime($dateStr)));
    }, 2);

    // SQLite Schema mimicking MySQL structure
    $pdo->exec("
        CREATE TABLE reports (
            report_id INTEGER PRIMARY KEY AUTOINCREMENT,
            student_id INTEGER NOT NULL,
            item_name TEXT NOT NULL,
            item_description TEXT,
            category_id INTEGER,
            brand_id INTEGER,
            item_id INTEGER, 
            room_id INTEGER,
            area_id INTEGER,
            when_found TEXT,
            when_lost TEXT,
            extra_details TEXT,
            reviewed_by INTEGER,
            status TEXT NOT NULL DEFAULT 'Active',
            type TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            last_updated TEXT DEFAULT CURRENT_TIMESTAMP,
            deleted TEXT NOT NULL DEFAULT '0',
            CHECK (status IN ('Active', 'Closed', 'Resolved')),
            CHECK (type IN ('Claim request', 'Loss Report', 'Surrender Form'))
        );

        CREATE TABLE reports_images (
            image_id INTEGER PRIMARY KEY AUTOINCREMENT,
            report_id INTEGER NOT NULL,
            img_filepath TEXT NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE users (
            user_id INTEGER PRIMARY KEY AUTOINCREMENT,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password_hash TEXT,
            role TEXT NOT NULL DEFAULT 'Student',
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            deleted TEXT NOT NULL DEFAULT '0'
        );

        CREATE TABLE items (
            item_id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            description TEXT,
            room_id INTEGER,
            area_id INTEGER,
            when_found TEXT,
            surrendered_by INTEGER,
            status TEXT DEFAULT 'In Storage'
        );

        CREATE TABLE items_images (
            image_id INTEGER PRIMARY KEY AUTOINCREMENT,
            item_id INTEGER NOT NULL,
            img_filepath TEXT NOT NULL
        );

        CREATE TABLE categories (
            category_id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            deleted TEXT DEFAULT '0'
        );

        CREATE TABLE rooms (
            room_id INTEGER PRIMARY KEY AUTOINCREMENT,
            building_id INTEGER,
            name TEXT NOT NULL
        );

        CREATE TABLE areas (
            area_id INTEGER PRIMARY KEY AUTOINCREMENT,
            building_id INTEGER,
            name TEXT NOT NULL
        );

        CREATE TABLE buildings (
            building_id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL
        );
    ");

    // Seed dummy users
    $pdo->exec("INSERT INTO users (user_id, first_name, last_name, email) VALUES (1, 'Marc', 'Tester', 'Marc@example.com')");
    $pdo->exec("INSERT INTO users (user_id, first_name, last_name, email) VALUES (2, 'Carl', 'Tester', 'Carl@example.com')");
    $pdo->exec("INSERT INTO users (user_id, first_name, last_name, email) VALUES (3, 'Angelo', 'Tester', 'Angelo@example.com')");
    $pdo->exec("INSERT INTO users (user_id, first_name, last_name, email) VALUES (4, 'Daniel', 'Tester', 'Daniel@example.com')");

    // Seed dummy category & location for search/filter tests
    $pdo->exec("INSERT INTO categories (category_id, name) VALUES (1, 'Electronics')");
    $pdo->exec("INSERT INTO buildings (building_id, name) VALUES (1, 'Gokongwei Building')");
    $pdo->exec("INSERT INTO rooms (room_id, building_id, name) VALUES (1, 1, 'G302')");

    $reports = new Reports($pdo);

} catch (Exception $e) {
    die("<p style='color:red;'>Setup failed: " . htmlspecialchars($e->getMessage()) . "</p>");
}
?>

<h3>Function 1: createReport</h3>
<p>This is used to create a Report, used in the student pages for a "Loss Report", "Surrender Form", "Claim Request"</p>
<?php
    $created1 = $reports->createReport(
        1, 'Blue Backpack', 'Nylon bag', 1, 99, 99, 1, null, '2026-08-01 10:00:00', null, 'Has books', 'Loss Report'
    );
    assertTest(1, "Create a Valid Report, expected fields are provided", $created1, true);
    $validReportId = $pdo->lastInsertId();

    try {
        $created2 = $reports->createReport(
            2, 'Blue Backpack', 'Nylon bag', 1, 99, 99, 1, null, '2026-08-01 10:00:00', null, 'Has books', 'NonExisting Form'
        );
    } catch (Exception $e) {
        $created2 = false;
    }
    assertTest(2, "Create an Invalid Report, report type is not in the ENUM value of the database", $created2, false);
?>

<h3>Function 2: getReportsByStudent</h3>
<p>Retrieve a student's own submitted reports</p>
<?php
    $studentReports = $reports->getReportsByStudent(1);
    assertTest(3, "Retrieve active reports for student with existing reports", !empty($studentReports), true);

    $emptyReports = $reports->getReportsByStudent(999);
    assertTest(4, "Retrieve reports for non-existent student ID returns empty array", empty($emptyReports), true);
?>

<h3>Function 3: getReportById</h3>
<p>Retrieve a single report by ID</p>
<?php
    $fetchedReport = $reports->getReportById($validReportId);
    $isValid = ($fetchedReport && $fetchedReport['item_name'] === 'Blue Backpack');
    assertTest(5, "Retrieve an existing report by valid ID", $isValid, true);

    $nonExistent = $reports->getReportById(9999);
    assertTest(6, "Retrieve report by non-existent ID returns false", $nonExistent === false, true);
?>

<h3>Function 4: addImage</h3>
<p>Attach an uploaded proof image to a report</p>
<?php
    $imgAdded1 = $reports->addImage($validReportId, 'assets/uploads/proof1.jpg');
    assertTest(7, "Attach valid image file path to an existing report", $imgAdded1, true);

    $imgAdded2 = $reports->addImage($validReportId, 'assets/uploads/proof2.jpg');
    assertTest(8, "Attach secondary image file path to the same report", $imgAdded2, true);
?>

<h3>Function 5: getReportImages</h3>
<p>Retrieve all images attached to a report</p>
<?php
    $imagesList = $reports->getReportImages($validReportId);
    assertTest(9, "Retrieve images for a report containing attached photos", count($imagesList) === 2, true);

    $noImagesList = $reports->getReportImages(9999);
    assertTest(10, "Retrieve images for a report with no attached photos returns empty array", empty($noImagesList), true);
?>

<h3>Function 6: getReportImagesByIds</h3>
<p>Fetch exact image rows for a report limited to specific image IDs</p>
<?php
    $targetImageId = $imagesList[0]['image_id'];
    $filteredImages = $reports->getReportImagesByIds($validReportId, [$targetImageId]);
    assertTest(11, "Fetch specific images using array of valid image IDs", count($filteredImages) === 1, true);

    $emptyFiltered = $reports->getReportImagesByIds($validReportId, []);
    assertTest(12, "Fetch images passing empty image ID array returns empty list", empty($emptyFiltered), true);
?>

<h3>Function 7: deleteReportImagesByIds</h3>
<p>Delete specific images that belong to a report</p>
<?php
    $deleteResult = $reports->deleteReportImagesByIds($validReportId, [$targetImageId]);
    $remaining = $reports->getReportImages($validReportId);
    assertTest(13, "Delete image by valid image ID removes image from database", $deleteResult && count($remaining) === 1, true);

    $emptyDelete = $reports->deleteReportImagesByIds($validReportId, []);
    assertTest(14, "Delete images passing empty array returns true without deleting", $emptyDelete, true);
?>

<h3>Function 8: getLastInsertId</h3>
<p>Get the last inserted ID in the active connection</p>
<?php
    $lastId = $reports->getLastInsertId();
    assertTest(15, "getLastInsertId returns numeric value corresponding to recent insert", is_numeric($lastId) && $lastId > 0, true);
?>

<h3>Function 9: getStatistics</h3>
<p>Get report summary count statistics for a given student ID</p>
<?php
    $stats = $reports->getStatistics(1);
    $validStats = isset($stats['loss-reports']) && $stats['loss-reports'] >= 1;
    assertTest(16, "Get statistics for student returns array with expected keys and count", $validStats, true);

    $emptyStats = $reports->getStatistics(9999);
    assertTest(17, "Get statistics for student with no records returns zero counts", $emptyStats['loss-reports'] == 0, true);
?>

<h3>Function 10: getHistory</h3>
<p>Get report history list for a student dashboard</p>
<?php
    $history = $reports->getHistory(1);
    assertTest(18, "Get history returns recent reports for valid student", !empty($history), true);

    $noHistory = $reports->getHistory(9999);
    assertTest(19, "Get history for non-existent student returns empty array", empty($noHistory), true);
?>

<h3>Function 11: getLocationFrequency</h3>
<p>Count reported items grouped by location for a student</p>
<?php
    $locationFreq = $reports->getLocationFrequency(1);
    assertTest(20, "getLocationFrequency returns array grouped by location", is_array($locationFreq), true);
?>

<h3>Function 12: updateReport</h3>
<p>Update fields of an existing report</p>
<?php
    $updated = $reports->updateReport($validReportId, 'Updated Backpack', 'Updated Desc', 1, 99, 1, null, '2026-08-01 10:00:00', null, 'New details');
    $checkUpdated = $reports->getReportById($validReportId);
    assertTest(21, "Update report with valid parameters updates database values", $updated && $checkUpdated['item_name'] === 'Updated Backpack', true);
?>

<h3>Function 13: normalizeImagePath</h3>
<p>Normalizes individual image paths for display</p>
<?php
    $path1 = $reports->normalizeImagePath('assets/images/item.jpg');
    $path2 = $reports->normalizeImagePath('http://example.com/image.jpg');

    assertTest(33, "Normalize relative asset path adds prefix", $path1 === '../../assets/images/item.jpg', true);
    assertTest(34, "Normalize absolute URL returns unchanged string", $path2 === 'http://example.com/image.jpg', true);
?>

<h3>Function 14: normalizeImageValue</h3>
<p>Normalizes comma-separated lists of image paths</p>
<?php
    $csvPaths = $reports->normalizeImageValue('assets/img1.jpg,assets/img2.jpg');
    $expectedCsv = '../../assets/img1.jpg,../../assets/img2.jpg';

    assertTest(35, "Normalize CSV string of paths correctly transforms all paths", $csvPaths === $expectedCsv, true);
?>

<h3>Function 15: normalizeImageFields</h3>
<p>Normalizes image columns across a data row array in-place</p>
<?php
    $row = [
        'item_name' => 'Watch',
        'image_paths' => 'assets/watch.jpg',
        'proof_images' => 'assets/proof.jpg'
    ];

    $reports->normalizeImageFields($row);

    $isNormalized = ($row['image_paths'] === '../../assets/watch.jpg' && $row['proof_images'] === '../../assets/proof.jpg');
    assertTest(36, "normalizeImageFields normalizes targeted image array keys", $isNormalized, true);
?>

<h3>Function 16: runAutoDisposalReports</h3>
<p>Automatically flags active reports older than 1 month as deleted</p>
<?php
    // Insert an active report created 35 days ago
    $pdo->exec("
        INSERT INTO reports (student_id, item_name, status, type, created_at, deleted) 
        VALUES (1, 'Old Lost Item', 'Active', 'Loss Report', datetime('now', '-35 days'), '0')
    ");
    $oldReportId = $pdo->lastInsertId();

    // Buffer output to catch inline console log scripts
    ob_start();
    try {
        $reports->runAutoDisposalReports();
        $disposalExecuted = true;
    } catch (Exception $e) {
        $disposalExecuted = false;
    }
    ob_end_clean();

    $oldReport = $reports->getReportById($oldReportId);
    assertTest(37, "runAutoDisposalReports marks active reports older than 1 month as deleted", $disposalExecuted && ($oldReport === false), true);
?>

<h3>Function 17: getLastInsertedItem</h3>
<p>Get the most recently inserted item ID for a specific surrendered_by user</p>
<?php
    $pdo->exec("INSERT INTO items (name, surrendered_by) VALUES ('Found Keys', 1)");
    $insertedItemId = $reports->getLastInsertedItem(1);
    assertTest(22, "getLastInsertedItem returns latest item ID for valid user", $insertedItemId !== null, true);

    $noItem = $reports->getLastInsertedItem(9999);
    assertTest(23, "getLastInsertedItem returns null for user who surrendered no items", $noItem === null, true);
?>

<h3>Function 18: insertItemImage</h3>
<p>Insert a new image reference for a physical item</p>
<?php
    $itemImgInserted = $reports->insertItemImage($insertedItemId, 'assets/items/keys.jpg');
    assertTest(24, "insertItemImage inserts valid image reference for physical item", $itemImgInserted, true);
?>

<h3>Function 19: getSurrenderForms</h3>
<p>Get active surrender form reports</p>
<?php
    $reports->createReport(3, 'Found Wallet', 'Black leather', 1, null, null, 1, null, null, '2026-08-01 10:00:00', 'Near desk', 'Surrender Form');
    $surrenders = $reports->getSurrenderForms();
    assertTest(25, "getSurrenderForms returns active surrender forms", !empty($surrenders), true);

    $surrendersFiltered = $reports->getSurrenderForms('NonExistentQuery');
    assertTest(26, "getSurrenderForms with non-matching search term returns empty array", empty($surrendersFiltered), true);
?>

<h3>Function 20: getClaimRequests</h3>
<p>Get active claim requests</p>
<?php
    $reports->createReport(4, 'Claim Wallet', 'Brown leather', 1, null, null, 1, null, '2026-08-01 10:00:00', null, 'Proof text', 'Claim request');
    $claims = $reports->getClaimRequests();
    assertTest(27, "getClaimRequests returns active claim requests", !empty($claims), true);
?>

<h3>Function 21: getLossReports</h3>
<p>Get active loss reports</p>
<?php
    $lossReportsList = $reports->getLossReports();
    assertTest(28, "getLossReports returns active loss reports", !empty($lossReportsList), true);
?>

<h3>Function 22: getPossibleMatches</h3>
<p>Get possible matching found items based on loss item name keywords</p>
<?php
    $matches = $reports->getPossibleMatches('Found Keys');
    assertTest(29, "getPossibleMatches returns matching storage items by keyword", is_array($matches), true);
?>

<h3>Function 23: getCategories</h3>
<p>Fetch active category listing</p>
<?php
    $cats = $reports->getCategories();
    assertTest(30, "getCategories returns populated category list", !empty($cats), true);
?>

<h3>Function 24: resolveReport & closeReport</h3>
<p>Update report status to Resolved or Closed</p>
<?php
    $resolved = $reports->resolveReport($validReportId, 2);
    $resolvedRecord = $reports->getReportById($validReportId);
    assertTest(31, "resolveReport updates report status to Resolved", $resolved && $resolvedRecord['status'] === 'Resolved', true);

    $closed = $reports->closeReport($validReportId, 2);
    $closedRecord = $reports->getReportById($validReportId);
    assertTest(32, "closeReport updates report status to Closed", $closed && $closedRecord['status'] === 'Closed', true);
?>

</body>
</html>