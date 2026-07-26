<?php
//this is the Model, Report.php
class Reports{
    private $conn;

    public function __construct($database)
    {
        $this->conn = $database;
    }

    private function normalizeImagePath($path)
    {
        if (empty($path)) {
            return $path;
        }

        $trimmedPath = trim($path);

        if (preg_match('#^(https?:)?//#', $trimmedPath) || strpos($trimmedPath, 'data:') === 0) {
            return $trimmedPath;
        }

        if (strpos($trimmedPath, '../../') === 0) {
            return $trimmedPath;
        }

        if (strpos($trimmedPath, 'assets/') === 0 || preg_match('~^(?:\.\/|\.\.\/)+assets~', $trimmedPath)) {
            return '../../' . ltrim($trimmedPath, './');
        }

        return $trimmedPath;
    }

    private function normalizeImageValue($value)
    {
        if (empty($value)) {
            return $value;
        }

        $paths = explode(',', $value);
        $normalizedPaths = array_map(function ($path) {
            return $this->normalizeImagePath($path);
        }, $paths);

        return implode(',', $normalizedPaths);
    }

    private function normalizeImageFields(array &$row)
    {
        foreach (['image_paths', 'found_item_image', 'proof_images'] as $field) {
            if (isset($row[$field]) && $row[$field] !== null) {
                $row[$field] = $this->normalizeImageValue($row[$field]);
            }
        }
    }

    // 1. Gets the most recently inserted item ID for a specific surrendered_by user
    // USED WHEN A SURRENDER FORM IS ACCEPTED, and the surrendered item is added to the ITEMS table
    
    public function getLastInsertedItem($studentId) {
        $sql = "SELECT item_id FROM items 
                WHERE surrendered_by = :student_id 
                ORDER BY item_id DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['item_id'] : null;
    }

    // 2. Inserts a new image reference for a physical item
    // USED WHEN A SURRENDER FORM IS ACCEPTED, and the surrendered item's image is added to the ITEMS_IMAGES table
    public function insertItemImage($itemId, $filePath) {
        $sql = "INSERT INTO items_images (item_id, img_filepath) VALUES (:item_id, :img_filepath)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':img_filepath', $filePath, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    // 3. Gets the Surrender Forms, includes all the images, location, and user details
    public function getSurrenderForms($search = '', $category = '', $sortBy = 'recent')
    {
    // 1. Base SQL Query (Aggregated safely via isolated Subquery)
    $sql = "SELECT 
                r.report_id,
                r.item_name,
                DATE(r.when_found) AS date_found,
                TIME(r.when_found) AS time_found,
                DATE(r.created_at) AS filed_on,
                CONCAT(u.first_name, ' ', u.last_name) AS student_name,
                u.email AS student_email,               
                COALESCE(rms.name, ars.name, 'Unknown Location') AS location_found,
                
                -- Fetch image paths, concat and separated for the multiple paths. The paths are split up in the view
                (SELECT GROUP_CONCAT(ri.img_filepath ORDER BY ri.image_id ASC SEPARATOR ',') 
                 FROM reports_images ri 
                 WHERE ri.report_id = r.report_id) AS image_paths

            FROM reports r
            INNER JOIN users u 
                ON r.student_id = u.user_id
            LEFT JOIN rooms rms 
                ON r.room_id = rms.room_id
            LEFT JOIN areas ars 
                ON r.area_id = ars.area_id
            LEFT JOIN categories cat 
                ON r.category_id = cat.category_id
            WHERE r.type = 'Surrender Form' 
              AND r.status = 'Active'
              AND r.deleted = '0'";

    // 2. Append Dynamic WHERE Conditions, for the search bar
    if (!empty($search)) {
        $sql .= " AND r.item_name LIKE :search";
    }

    if (!empty($category)) { // for the chosen category
        $sql .= " AND cat.name = :category";
    }

    // 3. Dynamic ORDER BY
    if ($sortBy === 'name') {
        $sql .= " ORDER BY r.item_name ASC";
    } else {
        // Default to 'recent'
        $sql .= " ORDER BY r.created_at DESC";
    }

    // 4. Prepare & Bind parameters
    $stmt = $this->conn->prepare($sql);

    if (!empty($search)) {
        $searchParam = "%" . $search . "%";
        $stmt->bindParam(':search', $searchParam);
    }

    if (!empty($category)) {
        $stmt->bindParam(':category', $category);
    }

    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as &$row) {
        $this->normalizeImageFields($row);
    }

    return $rows;
}

    // 4. Gets Active Claim Requests with the matching found item inventory details, 
    // proof of ownership texts, and claimant uploads.
    public function getClaimRequests($search = '', $category = '', $sortBy = 'recent')
    {
        $sql = "SELECT 
                    -- 1. CLAIM REQUEST REPORT DETAILS
                    r.report_id,
                    r.item_name AS claim_item_name,
                    r.item_description AS claim_description,
                    DATE(r.when_lost) AS date_lost,
                    TIME(r.when_lost) AS time_lost,
                    DATE(r.created_at) AS filed_on,
                    r.extra_details AS proof_of_ownership_text,
                    r.student_id,
                    
                    -- 2. CLAIMANT USER DETAILS
                    CONCAT(u.first_name, ' ', u.last_name) AS student_name,
                    u.email AS student_email,
                    
                    -- 3. FOUND ITEM INVENTORY DETAILS (From Items Table)
                    i.item_id,
                    i.name AS found_item_name,
                    DATE(i.when_found) AS date_found,
                    TIME(i.when_found) AS time_found,
                    COALESCE(irms.name, iars.name, 'Unknown Location') AS location_found,
                    
                    -- 4. ALL IMAGES OF THE FOUND ITEM (Aggregated safely via isolated Subquery)
                    (SELECT GROUP_CONCAT(ii.img_filepath ORDER BY ii.image_id ASC SEPARATOR ',') 
                    FROM items_images ii 
                    WHERE ii.item_id = i.item_id) AS found_item_image,
                    
                    -- 5. CLAIMANT'S UPLOADED PROOF IMAGES (Aggregated safely via isolated Subquery)
                    (SELECT GROUP_CONCAT(ri.img_filepath ORDER BY ri.image_id ASC SEPARATOR ',') 
                    FROM reports_images ri 
                    WHERE ri.report_id = r.report_id) AS proof_images,
                    
                    -- Report Location
                    COALESCE(rrms.name, rars.name, 'Unknown Location') AS location_lost

                FROM reports r
                INNER JOIN users u 
                    ON r.student_id = u.user_id
                -- Link Claim Request back to the matching Found Item in storage
                LEFT JOIN items i 
                    ON r.item_id = i.item_id
                -- Location resolved for Found Item
                LEFT JOIN rooms irms 
                    ON i.room_id = irms.room_id
                LEFT JOIN areas iars 
                    ON i.area_id = iars.area_id
                -- Location resolved for Claim Request
                LEFT JOIN rooms rrms 
                    ON r.room_id = rrms.room_id
                LEFT JOIN areas rars 
                    ON r.area_id = rars.area_id
                -- Categories helper
                LEFT JOIN categories cat 
                    ON r.category_id = cat.category_id

                WHERE r.type = 'Claim request' 
                AND r.status = 'Active'
                AND r.deleted = '0'";

        // Apply Dynamic Filters
        if (!empty($search)) {
            $sql .= " AND r.item_name LIKE :search";
        }

        if (!empty($category)) {
            $sql .= " AND cat.name = :category";
        }

        // Sorting Setup
        if ($sortBy === 'name') {
            $sql .= " ORDER BY r.item_name ASC";
        } else {
            $sql .= " ORDER BY r.created_at DESC";
        }

        $stmt = $this->conn->prepare($sql);

        if (!empty($search)) {
            $searchParam = "%" . $search . "%";
            $stmt->bindParam(':search', $searchParam);
        }

        if (!empty($category)) {
            $stmt->bindParam(':category', $category);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$row) {
            $this->normalizeImageFields($row);
        }
        return $rows;
    }


    // 5. Gets Active Loss Reports
    public function getLossReports($search = '', $category = '', $sortBy = 'recent')
    {
        // 1. Base SQL Query (We left join the categories table to match by category name)
        $sql = "SELECT 
                r.report_id,
                r.item_name,
                DATE(r.when_lost) AS date_lost,
                TIME(r.when_lost) AS time_lost,
                DATE(r.created_at) AS filed_on,
                CONCAT(u.first_name, ' ', u.last_name) AS student_name,
                u.email AS student_email,               
                COALESCE(rms.name, ars.name, 'Unknown Location') AS location_lost,
                GROUP_CONCAT(ri.img_filepath ORDER BY ri.image_id ASC) AS image_paths
                FROM reports r
                INNER JOIN users u 
                    ON r.student_id = u.user_id
                LEFT JOIN rooms rms 
                    ON r.room_id = rms.room_id
                LEFT JOIN areas ars 
                    ON r.area_id = ars.area_id
                LEFT JOIN reports_images ri 
                    ON r.report_id = ri.report_id
                LEFT JOIN categories cat 
                    ON r.category_id = cat.category_id
                WHERE r.type = 'Loss Report' 
                AND r.status = 'Active'
                AND r.deleted = '0'";

        // 2. Append Dynamic WHERE Conditions
        if (!empty($search)) {
            $sql .= " AND r.item_name LIKE :search";
        }

        if (!empty($category)) {
            $sql .= " AND cat.name = :category";
        }

        // Group by report_id because of GROUP_CONCAT
        $sql .= " GROUP BY r.report_id";

        // 3. Dynamic ORDER BY (SQL variables cannot be parameterized, so we hardcode the safe choices)
        if ($sortBy === 'name') {
            $sql .= " ORDER BY r.item_name ASC";
        } else {
            // Default to 'recent'
            $sql .= " ORDER BY r.created_at DESC";
        }

        // 4. Prepare & Bind parameters
        $stmt = $this->conn->prepare($sql);

        if (!empty($search)) {
            $searchParam = "%" . $search . "%";
            $stmt->bindParam(':search', $searchParam);
        }

        if (!empty($category)) {
            $stmt->bindParam(':category', $category);
        }

        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$row) {
            $this->normalizeImageFields($row);
        }

        return $rows;
    }

    // 6. Get Possible Matches of an item based on NAME, for Loss Reports
    public function getPossibleMatches($itemName) 
    {
        // 1. Clean and split the item name into individual search terms (words)
        // Example: "Black Oversize Hoodie" -> ['Black', 'Oversize', 'Hoodie']
        $words = preg_split('/\s+/', trim($itemName));
        $words = array_filter($words, function($word) {
            // Filter out short/common stop words like "a", "an", "the", "with", "of" 
            return strlen($word) > 3; 
        });

        // If no valid search words remain, default back to the entire string
        if (empty($words)) {
            $words = [$itemName];
        }

        // 2. Build dynamic SQL with OR conditions for each keyword
        $sql = "SELECT 
                    i.item_id,
                    i.name AS item_name,
                    i.description,
                    COALESCE(rms.name, ars.name, 'Unknown Location') AS location_found,
                    i.when_found,
                    -- Get the first image associated with this item as its thumbnail
                    (SELECT img_filepath 
                    FROM items_images 
                    WHERE item_id = i.item_id 
                    LIMIT 1) AS primary_image
                FROM items i
                LEFT JOIN rooms rms ON i.room_id = rms.room_id
                LEFT JOIN areas ars ON i.area_id = ars.area_id
                WHERE i.status = 'In Storage' ";

        // Append keyword matching constraints
        $conditions = [];
        foreach ($words as $index => $word) {
            $conditions[] = "i.name LIKE :word_" . $index;
        }

        if (!empty($conditions)) {
            $sql .= " AND (" . implode(" OR ", $conditions) . ")";
        }

        // Order matches by relevance (approximate: sorting newer items first)
        $sql .= " ORDER BY i.when_found DESC LIMIT 10;";

        $stmt = $this->conn->prepare($sql);

        // 3. Bind each keyword parameter safely
        foreach ($words as $index => $word) {
            $paramValue = "%" . $word . "%";
            $stmt->bindValue(":word_" . $index, $paramValue);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //7. get the categories
    public function getCategories()
    {
        $sql = "
            SELECT
                category_id,
                name
            FROM categories
            WHERE deleted = '0'
            ORDER BY name ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //8. Resolves a Report
    public function resolveReport($reportId, $staffId) {
        $sql = "UPDATE reports 
                SET status = 'Resolved', 
                    reviewed_by = :staff_id,
                    last_updated = NOW()
                WHERE report_id = :report_id ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':report_id', $reportId, PDO::PARAM_INT);
        $stmt->bindParam(':staff_id', $staffId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    //9. Closes a report
    public function closeReport($reportId, $staffId) {
        $sql = "UPDATE reports 
                SET status = 'Closed', 
                    reviewed_by = :staff_id,
                    last_updated = NOW()
                WHERE report_id = :report_id ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':report_id', $reportId, PDO::PARAM_INT);
        $stmt->bindParam(':staff_id', $staffId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}



?>