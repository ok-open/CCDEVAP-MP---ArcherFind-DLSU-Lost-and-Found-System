<?php

class Reports
{
    private $conn;

    public function __construct($database)
    {
        $this->conn = $database;
    }

    // Submit Claim Request / Loss Report / Surrender Form
    public function createReport(
        $studentId,
        $itemName,
        $itemDescription,
        $categoryId,
        $brandId,
        $itemId,
        $roomId,
        $areaId,
        $whenLost,
        $whenFound,
        $extraDetails,
        $type
    )
    {
        $sql = "
            INSERT INTO reports
            (
                student_id,
                item_name,
                item_description,
                category_id,
                brand_id,
                item_id,
                room_id,
                area_id,
                when_lost,
                when_found,
                extra_details,
                type,
                status,
                deleted
            )
            VALUES
            (
                :student_id,
                :item_name,
                :item_description,
                :category_id,
                :brand_id,
                :item_id,
                :room_id,
                :area_id,
                :when_lost,
                :when_found,
                :extra_details,
                :type,
                'Active',
                '0'
            )
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":student_id", $studentId);
        $stmt->bindParam(":item_name", $itemName);
        $stmt->bindParam(":item_description", $itemDescription);
        $stmt->bindParam(":category_id", $categoryId);
        $stmt->bindParam(":brand_id", $brandId);
        $stmt->bindParam(":item_id", $itemId);
        $stmt->bindParam(":room_id", $roomId);
        $stmt->bindParam(":area_id", $areaId);
        $stmt->bindParam(":when_lost", $whenLost);
        $stmt->bindParam(":when_found", $whenFound);
        $stmt->bindParam(":extra_details", $extraDetails);
        $stmt->bindParam(":type", $type);

        return $stmt->execute();
    }

    // Retrieve a student's own submitted reports (e.g. "My Reports" page)
    public function getReportsByStudent($studentId)
    {
        $sql = "
            SELECT *
            FROM reports
            WHERE student_id = :student_id
            AND deleted = '0'
            ORDER BY created_at DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":student_id", $studentId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve a single report by ID (e.g. confirmation/detail view)
    public function getReportById($reportId)
    {
        $sql = "
            SELECT *
            FROM reports
            WHERE report_id = :report_id
            AND deleted = '0'
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":report_id", $reportId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Attach an uploaded proof image to a report
    public function addImage($reportId, $imagePath)
    {
        $sql = "
            INSERT INTO reports_images (report_id, img_filepath)
            VALUES (:report_id, :img_filepath)
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":report_id", $reportId);
        $stmt->bindParam(":img_filepath", $imagePath);

        return $stmt->execute();
    }

    public function getLastInsertId()
    {
        return $this->conn->lastInsertId();
    }

    // ===============================
    // DASHBOARD: SUMMARY CARDS
    // ===============================
    public function getStatistics($studentId)
    {
        $sql = "
            SELECT
                SUM(type = 'Loss Report') AS loss_reports,
                SUM(type = 'Surrender Form') AS found_reports,
                SUM(status IN ('Accepted','Resolved')) AS approved_reports,
                SUM(status = 'Active') AS pending_reports
            FROM reports
            WHERE student_id = :student_id
            AND deleted = '0'
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":student_id", $studentId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            "loss-reports" => $result["loss_reports"] ?? 0,
            "found-reports" => $result["found_reports"] ?? 0,
            "approved-reports" => $result["approved_reports"] ?? 0,
            "pending-reports" => $result["pending_reports"] ?? 0
        ];
    }

    // ===============================
    // DASHBOARD: REPORT HISTORY
    // ===============================
    public function getHistory($studentId)
    {
        $sql = "
            SELECT
                report_id,
                item_name,
                DATE_FORMAT(created_at, '%M %d, %Y') AS date,
                type,
                status
            FROM reports
            WHERE student_id = :student_id
            AND deleted = '0'
            ORDER BY created_at DESC
            LIMIT 10
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":student_id", $studentId);
        $stmt->execute();

        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Editable only while still Active — matches the lock enforced
        // server-side in handleEditReport()
        foreach ($reports as &$report) {
            $report["editable"] = ($report["status"] === "Active");
        }

        return $reports;
    }

    // ===============================
    // DASHBOARD: LOST ITEM FREQUENCY
    // ===============================
    public function getLocationFrequency($studentId)
    {
        $sql = "
            SELECT
                COALESCE(b.name, 'Unknown Location') AS location,
                COUNT(*) AS total
            FROM reports r
            LEFT JOIN rooms rm ON r.room_id = rm.room_id
            LEFT JOIN areas a ON r.area_id = a.area_id
            LEFT JOIN buildings b
                ON b.building_id = COALESCE(rm.building_id, a.building_id)
            WHERE r.student_id = :student_id
            AND r.deleted = '0'
            GROUP BY b.name
            ORDER BY total DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":student_id", $studentId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===============================
    // EDIT REPORT
    // ===============================
    public function updateReport(
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
    ) {
        $sql = "
            UPDATE reports
            SET
                item_name = :item_name,
                item_description = :item_description,
                category_id = :category_id,
                brand_id = :brand_id,
                room_id = :room_id,
                area_id = :area_id,
                when_lost = :when_lost,
                when_found = :when_found,
                extra_details = :extra_details
            WHERE report_id = :report_id
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":item_name" => $itemName,
            ":item_description" => $itemDescription,
            ":category_id" => $categoryId,
            ":brand_id" => $brandId,
            ":room_id" => $roomId,
            ":area_id" => $areaId,
            ":when_lost" => $whenLost,
            ":when_found" => $whenFound,
            ":extra_details" => $extraDetails,
            ":report_id" => $reportId
        ]);
    }
}