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
        pre { background: rgba(0,0,0,0.05); padding: 5px; margin: 3px 0; border-radius: 3px; font-size: 13px; white-space: pre-wrap; word-wrap: break-word; }
    </style>
</head>
<body>

<h1>Automated Test Case Runner for Reports.php</h1>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Include Reports class relative to testCases/ folder
require_once __DIR__ . '/../models/Reports.php';

// Custom PDO class to convert MySQL date functions and GROUP_CONCAT to SQLite syntax
class SQLiteCompatiblePDO extends PDO {

    private function rewriteQuery($statement) {
        // 1. Rewrite DATE_SUB(NOW(), INTERVAL 1 MONTH) to SQLite datetime('now', '-1 month')
        $statement = preg_replace(
            '/DATE_SUB\s*\(\s*NOW\(\)\s*,\s*INTERVAL\s+(\d+)\s+([A-Z]+)\s*\)/i',
            "datetime('now', '-$1 $2')",
            $statement
        );

        // 2. Rewrite generic DATE_SUB(expr, INTERVAL count unit) to SQLite datetime(expr, '-count unit')
        $statement = preg_replace(
            '/DATE_SUB\s*\(\s*([^,]+)\s*,\s*INTERVAL\s+(\d+)\s+([A-Z]+)\s*\)/i',
            "datetime($1, '-$2 $3')",
            $statement
        );

        // 3. Rewrite MySQL GROUP_CONCAT(expr ORDER BY ... SEPARATOR ',') to SQLite GROUP_CONCAT(expr, ',')
        $statement = preg_replace(
            '/GROUP_CONCAT\s*\(\s*(.*?)\s+ORDER\s+BY\s+.*?\s+SEPARATOR\s+([\'"].*?[\'"])\s*\)/i',
            'GROUP_CONCAT($1, $2)',
            $statement
        );

        // 4. Rewrite MySQL GROUP_CONCAT(expr ORDER BY ...) without SEPARATOR to SQLite GROUP_CONCAT(expr)
        $statement = preg_replace(
            '/GROUP_CONCAT\s*\(\s*(.*?)\s+ORDER\s+BY\s+.*?\s*\)/i',
            'GROUP_CONCAT($1)',
            $statement
        );

        return $statement;
    }

    #[\ReturnTypeWillChange]
    public function exec($statement) {
        return parent::exec($this->rewriteQuery($statement));
    }

    #[\ReturnTypeWillChange]
    public function prepare($statement, $driver_options = []) {
        return parent::prepare($this->rewriteQuery($statement), $driver_options);
    }

    #[\ReturnTypeWillChange]
    public function query($statement, $mode = PDO::ATTR_DEFAULT_FETCH_MODE, ...$fetch_mode_args) {
        return parent::query($this->rewriteQuery($statement), $mode, ...$fetch_mode_args);
    }
}

// Structured assertion helper accepting input and output values
function assertTest($testNumber, $testName, $inputValue, $outputValue, $actualResult, $expectedResult) {
    $expectedText = $expectedResult ? 'PASS' : 'FAIL';
    $actualText = $actualResult ? 'PASS' : 'FAIL';
    $isSuccess = ($actualResult === $expectedResult);

    $statusClass = $isSuccess ? 'pass' : 'fail';

    $inputDisplay = is_string($inputValue) ? $inputValue : json_encode($inputValue, JSON_PRETTY_PRINT);
    $outputDisplay = is_string($outputValue) ? $outputValue : json_encode($outputValue, JSON_PRETTY_PRINT);

    echo "<div class='test-box {$statusClass}'>";
    echo "<strong>Test {$testNumber}: {$testName}</strong><br>";
    echo "<strong>INPUT:</strong><pre>" . htmlspecialchars($inputDisplay) . "</pre>";
    echo "<strong>OUTPUT:</strong><pre>" . htmlspecialchars($outputDisplay) . "</pre>";
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

    // Polyfill DATE_SUB to handle multi-parameter DATE_SUB(NOW(), INTERVAL 1 MONTH)
    $pdo->sqliteCreateFunction('DATE_SUB', function (...$args) {
        $dateStr = $args[0] ?? null;
        if (!$dateStr) return date('Y-m-d H:i:s');
        return date('Y-m-d H:i:s', strtotime('-1 month', strtotime($dateStr)));
    });

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
    $input1 = [
        'student_id' => 1, 'item_name' => 'Blue Backpack', 'item_description' => 'Nylon bag', 
        'category_id' => 1, 'brand_id' => 99, 'item_id' => 99, 'room_id' => 1, 'area_id' => null, 
        'when_lost' => '2026-08-01 10:00:00', 'when_found' => null, 'extra_details' => 'Has books', 'type' => 'Loss Report'
    ];
    $created1 = $reports->createReport(
        1, 'Blue Backpack', 'Nylon bag', 1, 99, 99, 1, null, '2026-08-01 10:00:00', null, 'Has books', 'Loss Report'
    );
    assertTest(1, "Create a Valid Report, expected fields are provided", $input1, $created1, $created1, true);
    $validReportId = $pdo->lastInsertId();

    $input2 = [
        'student_id' => 2, 'item_name' => 'Blue Backpack', 'item_description' => 'Nylon bag', 
        'category_id' => 1, 'brand_id' => 99, 'item_id' => 99, 'room_id' => 1, 'area_id' => null, 
        'when_lost' => '2026-08-01 10:00:00', 'when_found' => null, 'extra_details' => 'Has books', 'type' => 'NonExisting Form'
    ];
    try {
        $created2 = $reports->createReport(
            2, 'Blue Backpack', 'Nylon bag', 1, 99, 99, 1, null, '2026-08-01 10:00:00', null, 'Has books', 'NonExisting Form'
        );
    } catch (Exception $e) {
        $created2 = false;
    }
    assertTest(2, "Create an Invalid Report, report type is not in the ENUM value of the database", $input2, $created2, $created2, false);
?>

<h3>Function 2: getReportsByStudent</h3>
<p>Retrieve a student's own submitted reports</p>
<?php
    $studentReports = $reports->getReportsByStudent(1);
    assertTest(3, "Retrieve active reports for student with existing reports", ['student_id' => 1], $studentReports, !empty($studentReports), true);

    $emptyReports = $reports->getReportsByStudent(999);
    assertTest(4, "Retrieve reports for non-existent student ID returns empty array", ['student_id' => 999], $emptyReports, empty($emptyReports), true);
?>

<h3>Function 3: getReportById</h3>
<p>Retrieve a single report by ID</p>
<?php
    $fetchedReport = $reports->getReportById($validReportId);
    $isValid = ($fetchedReport && $fetchedReport['item_name'] === 'Blue Backpack');
    assertTest(5, "Retrieve an existing report by valid ID", ['report_id' => $validReportId], $fetchedReport, $isValid, true);

    $nonExistent = $reports->getReportById(9999);
    assertTest(6, "Retrieve report by non-existent ID returns false", ['report_id' => 9999], $nonExistent, $nonExistent === false, true);
?>

<h3>Function 4: addImage</h3>
<p>Attach an uploaded proof image to a report</p>
<?php
    $imgInput1 = ['report_id' => $validReportId, 'img_filepath' => 'assets/uploads/proof1.jpg'];
    $imgAdded1 = $reports->addImage($validReportId, 'assets/uploads/proof1.jpg');
    assertTest(7, "Attach valid image file path to an existing report", $imgInput1, $imgAdded1, $imgAdded1, true);

    $imgInput2 = ['report_id' => $validReportId, 'img_filepath' => 'assets/uploads/proof2.jpg'];
    $imgAdded2 = $reports->addImage($validReportId, 'assets/uploads/proof2.jpg');
    assertTest(8, "Attach secondary image file path to the same report", $imgInput2, $imgAdded2, $imgAdded2, true);
?>

<h3>Function 5: getReportImages</h3>
<p>Retrieve all images attached to a report</p>
<?php
    $imagesList = $reports->getReportImages($validReportId);
    assertTest(9, "Retrieve images for a report containing attached photos", ['report_id' => $validReportId], $imagesList, count($imagesList) === 2, true);

    $noImagesList = $reports->getReportImages(9999);
    assertTest(10, "Retrieve images for a report with no attached photos returns empty array", ['report_id' => 9999], $noImagesList, empty($noImagesList), true);
?>

<h3>Function 6: getReportImagesByIds</h3>
<p>Fetch exact image rows for a report limited to specific image IDs</p>
<?php
    $targetImageId = $imagesList[0]['image_id'];
    $filteredImages = $reports->getReportImagesByIds($validReportId, [$targetImageId]);
    assertTest(11, "Fetch specific images using array of valid image IDs", ['report_id' => $validReportId, 'image_ids' => [$targetImageId]], $filteredImages, count($filteredImages) === 1, true);

    $emptyFiltered = $reports->getReportImagesByIds($validReportId, []);
    assertTest(12, "Fetch images passing empty image ID array returns empty list", ['report_id' => $validReportId, 'image_ids' => []], $emptyFiltered, empty($emptyFiltered), true);
?>

<h3>Function 7: deleteReportImagesByIds</h3>
<p>Delete specific images that belong to a report</p>
<?php
    $deleteResult = $reports->deleteReportImagesByIds($validReportId, [$targetImageId]);
    $remaining = $reports->getReportImages($validReportId);
    assertTest(13, "Delete image by valid image ID removes image from database", ['report_id' => $validReportId, 'image_ids' => [$targetImageId]], $deleteResult, $deleteResult && count($remaining) === 1, true);

    $emptyDelete = $reports->deleteReportImagesByIds($validReportId, []);
    assertTest(14, "Delete images passing empty array returns true without deleting", ['report_id' => $validReportId, 'image_ids' => []], $emptyDelete, $emptyDelete, true);
?>

<h3>Function 8: getLastInsertId</h3>
<p>Get the last inserted ID in the active connection</p>
<?php
    $lastId = $reports->getLastInsertId();
    assertTest(15, "getLastInsertId returns numeric value corresponding to recent insert", "None", $lastId, is_numeric($lastId) && $lastId > 0, true);
?>

<h3>Function 9: getStatistics</h3>
<p>Get report summary count statistics for a given student ID</p>
<?php
    $stats = $reports->getStatistics(1);
    $validStats = isset($stats['loss-reports']) && $stats['loss-reports'] >= 1;
    assertTest(16, "Get statistics for student returns array with expected keys and count", ['student_id' => 1], $stats, $validStats, true);

    $emptyStats = $reports->getStatistics(9999);
    assertTest(17, "Get statistics for student with no records returns zero counts", ['student_id' => 9999], $emptyStats, $emptyStats['loss-reports'] == 0, true);
?>

<h3>Function 10: getHistory</h3>
<p>Get report history list for a student dashboard</p>
<?php
    $history = $reports->getHistory(1);
    assertTest(18, "Get history returns recent reports for valid student", ['student_id' => 1], $history, !empty($history), true);

    $noHistory = $reports->getHistory(9999);
    assertTest(19, "Get history for non-existent student returns empty array", ['student_id' => 9999], $noHistory, empty($noHistory), true);
?>

<h3>Function 11: getLocationFrequency</h3>
<p>Count reported items grouped by location for a student</p>
<?php
    $locationFreq = $reports->getLocationFrequency(1);
    assertTest(20, "getLocationFrequency returns array grouped by location", ['student_id' => 1], $locationFreq, is_array($locationFreq), true);
?>

<h3>Function 12: updateReport</h3>
<p>Update fields of an existing report</p>
<?php
    $updateInput = [
        'report_id' => $validReportId, 'item_name' => 'Updated Backpack', 'item_description' => 'Updated Desc', 
        'category_id' => 1, 'brand_id' => 99, 'room_id' => 1, 'area_id' => null, 
        'when_lost' => '2026-08-01 10:00:00', 'when_found' => null, 'extra_details' => 'New details'
    ];
    $updated = $reports->updateReport($validReportId, 'Updated Backpack', 'Updated Desc', 1, 99, 1, null, '2026-08-01 10:00:00', null, 'New details');
    $checkUpdated = $reports->getReportById($validReportId);
    assertTest(21, "Update report with valid parameters updates database values", $updateInput, $checkUpdated, $updated && $checkUpdated['item_name'] === 'Updated Backpack', true);
?>

<h3>Function 13: normalizeImagePath</h3>
<p>Normalizes individual image paths for display</p>
<?php
    $path1 = $reports->normalizeImagePath('assets/images/item.jpg');
    assertTest(22, "Normalize relative asset path adds prefix", 'assets/images/item.jpg', $path1, $path1 === '../../assets/images/item.jpg', true);

    $path2 = $reports->normalizeImagePath('http://example.com/image.jpg');
    assertTest(23, "Normalize absolute URL returns unchanged string", 'http://example.com/image.jpg', $path2, $path2 === 'http://example.com/image.jpg', true);
?>

<h3>Function 14: normalizeImageValue</h3>
<p>Normalizes comma-separated lists of image paths</p>
<?php
    $csvPaths = $reports->normalizeImageValue('assets/img1.jpg,assets/img2.jpg');
    $expectedCsv = '../../assets/img1.jpg,../../assets/img2.jpg';
    assertTest(24, "Normalize CSV string of paths correctly transforms all paths", 'assets/img1.jpg,assets/img2.jpg', $csvPaths, $csvPaths === $expectedCsv, true);
?>

<h3>Function 15: normalizeImageFields</h3>
<p>Normalizes image columns across a data row array in-place</p>
<?php
    $row = [
        'item_name' => 'Watch',
        'image_paths' => 'assets/watch.jpg',
        'proof_images' => 'assets/proof.jpg'
    ];
    $inputRow = $row;
    $reports->normalizeImageFields($row);
    $isNormalized = ($row['image_paths'] === '../../assets/watch.jpg' && $row['proof_images'] === '../../assets/proof.jpg');
    assertTest(25, "normalizeImageFields normalizes targeted image array keys", $inputRow, $row, $isNormalized, true);
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

    $executionError = null;

    try {
        $reports->runAutoDisposalReports();
        $disposalExecuted = true;
    } catch (Throwable $e) {
        $disposalExecuted = false;
        $executionError = $e->getMessage();
    }

    // Directly fetch record from DB to verify deleted flag was updated to '1'
    $stmt = $pdo->prepare("SELECT * FROM reports WHERE report_id = ?");
    $stmt->execute([$oldReportId]);
    $disposedRecord = $stmt->fetch(PDO::FETCH_ASSOC);

    $isSuccessfullyDisposed = $disposalExecuted && ($disposedRecord && $disposedRecord['deleted'] == '1');

    assertTest(
        26, 
        "runAutoDisposalReports marks active reports older than 1 month as deleted", 
        ['active_report_created' => '35 days ago', 'report_id' => $oldReportId], 
        $executionError ? ['error' => $executionError] : $disposedRecord, 
        $isSuccessfullyDisposed, 
        true
    );
?>

<h3>Function 17: getLastInsertedItem</h3>
<p>Get the most recently inserted item ID for a specific surrendered_by user</p>
<?php
    $pdo->exec("INSERT INTO items (name, surrendered_by) VALUES ('Found Keys', 1)");
    $insertedItemId = $reports->getLastInsertedItem(1);
    assertTest(27, "getLastInsertedItem returns latest item ID for valid user", ['surrendered_by' => 1], $insertedItemId, $insertedItemId !== null, true);

    $noItem = $reports->getLastInsertedItem(9999);
    assertTest(28, "getLastInsertedItem returns null for user who surrendered no items", ['surrendered_by' => 9999], $noItem, $noItem === null, true);
?>

<h3>Function 18: insertItemImage</h3>
<p>Insert a new image reference for a physical item</p>
<?php
    $itemImgInput = ['item_id' => $insertedItemId, 'img_filepath' => 'assets/items/keys.jpg'];
    $itemImgInserted = $reports->insertItemImage($insertedItemId, 'assets/items/keys.jpg');
    assertTest(29, "insertItemImage inserts valid image reference for physical item", $itemImgInput, $itemImgInserted, $itemImgInserted, true);
?>

<h3>Function 19: getSurrenderForms</h3>
<p>Get active surrender form reports</p>
<?php
    $reports->createReport(3, 'Found Wallet', 'Black leather', 1, null, null, 1, null, null, '2026-08-01 10:00:00', 'Near desk', 'Surrender Form');
    $surrenders = $reports->getSurrenderForms();
    assertTest(30, "getSurrenderForms returns active surrender forms", "No input from user, just retrieves from db", $surrenders, !empty($surrenders), true);

    $surrendersFiltered = $reports->getSurrenderForms('NonExistentQuery');
    assertTest(31, "getSurrenderForms with non-matching search term returns empty array", ['search' => 'NonExistentQuery'], $surrendersFiltered, empty($surrendersFiltered), true);
?>

<h3>Function 20: getClaimRequests</h3>
<p>Get active claim requests</p>
<?php
    $reports->createReport(4, 'Claim Wallet', 'Brown leather', 1, null, null, 1, null, '2026-08-01 10:00:00', null, 'Proof text', 'Claim request');
    $claims = $reports->getClaimRequests();
    assertTest(32, "getClaimRequests returns active claim requests", "No input from user, just retrieves from db", $claims, !empty($claims), true);
?>

<h3>Function 21: getLossReports</h3>
<p>Get active loss reports</p>
<?php
    $lossReportsList = $reports->getLossReports();
    assertTest(33, "getLossReports returns active loss reports", "No input from user, just retrieves from db", $lossReportsList, !empty($lossReportsList), true);
?>

<h3>Function 22: getPossibleMatches</h3>
<p>Get possible matching found items based on loss item name keywords</p>
<?php
    $matches = $reports->getPossibleMatches('Found Keys');
    assertTest(34, "getPossibleMatches returns matching storage items by keyword", ['keyword' => 'Found Keys'], $matches, is_array($matches), true);
?>

<h3>Function 23: getCategories</h3>
<p>Fetch active category listing</p>
<?php
    $cats = $reports->getCategories();
    assertTest(35, "getCategories returns populated category list", "None", $cats, !empty($cats), true);
?>

<h3>Function 24: resolveReport & closeReport</h3>
<p>Update report status to Resolved or Closed</p>
<?php
    $resolved = $reports->resolveReport($validReportId, 2);
    $resolvedRecord = $reports->getReportById($validReportId);
    assertTest(36, "resolveReport updates report status to Resolved", ['report_id' => $validReportId, 'reviewed_by' => 2], $resolvedRecord, $resolved && $resolvedRecord['status'] === 'Resolved', true);

    $closed = $reports->closeReport($validReportId, 2);
    $closedRecord = $reports->getReportById($validReportId);
    assertTest(37, "closeReport updates report status to Closed", ['report_id' => $validReportId, 'reviewed_by' => 2], $closedRecord, $closed && $closedRecord['status'] === 'Closed', true);
?>

</body>
</html>