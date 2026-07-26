<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// This is the controller, ReportListController.php
require_once "../../db.php";
require_once "../../models/Report.php";

// Start session to get the current staff user ID
if (session_status() === PHP_SESSION_NONE) { //In case the session started already
    session_start();
}

// the logged-in user's ID in $_SESSION['user_id']
$staffId = $_SESSION['user_id'] ?? 1; // Fallback to 1 for testing if session is not active yet

$reportModel = new Reports($conn); //Create a Model with the connection, to perform the functions in the model

// 1. TO HANDLE SURRENDER FORMS RESOLVE AND CLOSE. It will move the image from the IMG_SurrenderForm folder to the ITEMS Folder
// Ensure you have allowed permission for vscode, check the txt file in controllers (permissionfile.txt) for the sudo command

// 1. Accept both POST and GET requests dynamically
$reportId = $_REQUEST['report_id'] ?? null;
$action = $_REQUEST['action'] ?? null;
if ($reportId && $action) {
    // 2.. Fetch details of the report before resolving it
    $sql = "SELECT type, student_id FROM reports WHERE report_id = :report_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':report_id', $reportId, PDO::PARAM_INT);
    $stmt->execute();
    $reportData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($action === 'resolve' || $action === 'Resolved') {
        //2. Once verified to resolved, run the resolveReport function
        // This fires TRIGGER 3, creating the new record in the `items` table
        $reportModel->resolveReport($reportId, $staffId);

        // 3. Handle Surrender Form Image Transfer
        if ($reportData && $reportData['type'] === 'Surrender Form') {// 4. check ensure it is a Surrender Form
            $studentId = $reportData['student_id'];
            
            // 5. Retrieve the newly created item_id (inserted by Trigger 3)
            $newItemId = $reportModel->getLastInsertedItem($studentId);
            
            if ($newItemId) {
                // 6. Fetch all existing report images record for this Surrender Form
                $imgSql = "SELECT img_filepath FROM reports_images WHERE report_id = :report_id";
                $imgStmt = $conn->prepare($imgSql);
                $imgStmt->bindParam(':report_id', $reportId, PDO::PARAM_INT);
                $imgStmt->execute();
                $images = $imgStmt->fetchAll(PDO::FETCH_ASSOC);

                // 7. Renaming of the file path
                foreach ($images as $img) {
                    $rawDatabasePath = $img['img_filepath']; // 8. Get raw file path, ex. "../../assets/IMG_SurrenderForm/surrender7.png"
                    $fileName = basename($rawDatabasePath); // 9. Get filename only, ex. "surrender7.png"

                    // 10. Get the Absolute Project Root Directory on disk
                    $projectRoot = dirname(__DIR__); 

                    // Absolute paths (no relative backtracking) for filesystem operations
                    $cleanSourceRelative = str_replace('../../', '', $rawDatabasePath); //11. remove the backtrailing paths, replace with a space
                    $absoluteSourcePath = $projectRoot . '/' . $cleanSourceRelative; //12. add the root path, a dash, and the "assets/IMG_SurrenderForm/surrender7.png"
                    $absoluteTargetPath = $projectRoot . '/assets/ITEMS/' . $fileName; //13. Add the root path, target folder, and the file name
                    $targetDirectory = $projectRoot . '/assets/ITEMS/'; // 14. the target directory

                    // 15. Check target directory exists on disk
                    if (!is_dir($targetDirectory)) {
                        mkdir($targetDirectory, 0777, true); //sysad stuff read, write permissions
                    }

                    //16. Perform the physical COPY AND PASTE on disk
                    if (file_exists($absoluteSourcePath)) { 
                        if (copy($absoluteSourcePath, $absoluteTargetPath)) {//17. Copies the image to the target folder
                            // 18. Save with relative "../../" format for frontend template display
                            $dbRelativePath = "../../assets/ITEMS/" . $fileName;
                            $reportModel->insertItemImage($newItemId, $dbRelativePath); // 19. Insert new record in items_images table with the new file path
                        } else {
                            error_log("ArcherFind Error: PHP copy() failed from $absoluteSourcePath to $absoluteTargetPath");
                        }
                    } else {
                        error_log("ArcherFind Error: Source file not found at: $absoluteSourcePath");
                    }
                }
            }
        }
    } elseif ($action === 'close' || $action === 'Closed') { //For close reports, we just close the status. And the item is not added to the official ITEMS table
        $reportModel->closeReport($reportId, $staffId);
    }

    // 2. Seamless Automatic Redirection (Preserving view filter query strings)
    $search = $_REQUEST['search'] ?? '';
    $category = $_REQUEST['category'] ?? '';
    $sortBy = $_REQUEST['sort'] ?? 'recent';
    header("Location: " . $_SERVER['PHP_SELF'] . "?search=" . urlencode($search) . "&category=" . urlencode($category) . "&sort=" . urlencode($sortBy));
    exit();
}

// 1. Gather filter inputs from GET query parameters
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$sortBy = $_GET['sort'] ?? 'recent'; // default to 'recent'

$reportModel = new Reports($conn);

// 2. Fetch the filtered results
$lossReports = $reportModel->getLossReports($search, $category, $sortBy);
$claimRequests = $reportModel->getClaimRequests($search, $category, $sortBy);
$surrenderForms = $reportModel->getSurrenderForms($search, $category, $sortBy);


// 3. Check if the user clicked "Information and Proof" on a specific report
$selectedReportId = $_GET['selected_report'] ?? null; // if from claim-list.php the value stops here
$possibleMatches = [];
$selectedReportName = "";

if ($selectedReportId) {//if from "Possible Matches" on report-list.php, that id is used to find possible matches based on NAME
    // Locate the selected report in our current array to get its item_name
    foreach ($lossReports as $report) {
        if ($report['report_id'] == $selectedReportId) {
            $selectedReportName = $report['item_name'];
            // Fetch matches directly from the model using the function we made
            $possibleMatches = $reportModel->getPossibleMatches($selectedReportName);
            break;
        }
    }
}

$categories = $reportModel->getCategories();

?>