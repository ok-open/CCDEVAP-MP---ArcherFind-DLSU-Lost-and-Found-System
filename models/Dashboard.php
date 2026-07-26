<?php

class Dashboard
{
    private $conn;


    public function __construct($database)
    {
        $this->conn = $database;
    }



    // ===============================
    // SUMMARY CARDS
    // ===============================

    public function getReportStatistics($studentId)
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

        $stmt->bindParam(
            ":student_id",
            $studentId
        );

        $stmt->execute();


        $result = $stmt->fetch(PDO::FETCH_ASSOC);


        return [
            "loss-reports" =>
                $result["loss_reports"] ?? 0,

            "found-reports" =>
                $result["found_reports"] ?? 0,

            "approved-reports" =>
                $result["approved_reports"] ?? 0,

            "pending-reports" =>
                $result["pending_reports"] ?? 0
        ];
    }



    // ===============================
    // REPORT HISTORY
    // ===============================

    public function getReportHistory($studentId)
    {
        $sql = "
            SELECT
                report_id,
                DATE_FORMAT(created_at,'%M %d, %Y') AS date,
                type,
                status

            FROM reports

            WHERE student_id = :student_id
            AND deleted = '0'

            ORDER BY created_at DESC

            LIMIT 10
        ";


        $stmt = $this->conn->prepare($sql);


        $stmt->bindParam(
            ":student_id",
            $studentId
        );


        $stmt->execute();


        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    // ===============================
    // LOST ITEM FREQUENCY
    // ===============================

    public function getLostItemFrequency($studentId)
    {

        $sql = "

            SELECT
                COALESCE(
                    b.name,
                    'Unknown Location'
                ) AS location,

                COUNT(*) AS total


            FROM reports r


            LEFT JOIN rooms rm
                ON r.room_id = rm.room_id


            LEFT JOIN areas a
                ON r.area_id = a.area_id


            LEFT JOIN buildings b
                ON b.building_id =
                COALESCE(
                    rm.building_id,
                    a.building_id
                )


            WHERE r.student_id = :student_id

            AND r.deleted='0'

            GROUP BY b.name

            ORDER BY total DESC

        ";


        $stmt = $this->conn->prepare($sql);


        $stmt->bindParam(
            ":student_id",
            $studentId
        );


        $stmt->execute();


        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===============================
// ADMIN / STAFF USER COUNTS
// ===============================

public function getUserCounts()
{
    $sql = "
        SELECT
            COUNT(*) AS total_users,
            SUM(CASE WHEN role = 'Student' THEN 1 ELSE 0 END) AS students,
            SUM(CASE WHEN role = 'Staff' THEN 1 ELSE 0 END) AS staff,
            SUM(CASE WHEN role = 'Admin' THEN 1 ELSE 0 END) AS admins
        FROM users
        WHERE deleted = '0'
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// ===============================
// ADMIN / STAFF REPORT TYPES
// ===============================

public function getReportTypes($from = null, $to = null)
{
    $sql = "
        SELECT type, COUNT(*) AS total
        FROM reports
        WHERE deleted = '0'
    ";

    $params = [];

    if ($from !== null && $to !== null) {
        $sql .= "
            AND when_found BETWEEN :fromDate AND :toDate
        ";

        $params = [
            ":fromDate" => $from,
            ":toDate" => $to
        ];
    }

    $sql .= " GROUP BY type";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ===============================
// ADMIN / STAFF INVENTORY STATUS
// ===============================

public function getInventoryStatus($from = null, $to = null)
{
    $sql = "
        SELECT status, COUNT(*) AS total
        FROM items
        WHERE deleted = '0'
    ";

    $params = [];

    if ($from !== null && $to !== null) {
        $sql .= "
            AND when_found BETWEEN :fromDate AND :toDate
        ";

        $params = [
            ":fromDate" => $from,
            ":toDate" => $to
        ];
    }

    $sql .= " GROUP BY status";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ===============================
// ADMIN / STAFF MONTHLY REPORTS
// ===============================

public function getMonthlyReports($from = null, $to = null)
{
    $sql = "
        SELECT
            MONTH(created_at) AS month,
            COUNT(*) AS total
        FROM reports
        WHERE deleted = '0'
    ";

    $params = [];

    if ($from !== null && $to !== null) {
        $sql .= "
            AND created_at BETWEEN :fromDate AND :toDate
        ";

        $params = [
            ":fromDate" => $from,
            ":toDate" => $to
        ];
    }

    $sql .= "
        GROUP BY MONTH(created_at)
        ORDER BY MONTH(created_at)
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// ===============================
// ADMIN / STAFF LOCATION FREQUENCY
// ===============================

public function getLocationFrequency($from = null, $to = null)
{
    $sql = "
        SELECT
            b.name,
            COUNT(i.item_id) AS total
        FROM items i
        LEFT JOIN rooms r
            ON i.room_id = r.room_id
        LEFT JOIN areas a
            ON i.area_id = a.area_id
        JOIN buildings b
            ON b.building_id =
            COALESCE(r.building_id, a.building_id)
        WHERE i.deleted = '0'
    ";

    $params = [];

    if ($from !== null && $to !== null) {
        $sql .= "
            AND i.when_found BETWEEN :fromDate AND :toDate
        ";

        $params = [
            ":fromDate" => $from,
            ":toDate" => $to
        ];
    }

    $sql .= "
        GROUP BY b.building_id, b.name
        ORDER BY total DESC
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ===============================
// ADMIN / STAFF CLAIM SUCCESS
// ===============================

public function getClaimStatistics($from = null, $to = null)
{
    $sql = "
        SELECT
            COUNT(*) AS total_claims,
            SUM(status = 'Accepted') AS accepted_claims
        FROM reports
        WHERE type = 'Claim request'
        AND deleted = '0'
    ";

    $params = [];

    if ($from !== null && $to !== null) {
        $sql .= " AND created_at BETWEEN :fromDate AND :toDate";

        $params = [
            ":fromDate" => $from,
            ":toDate" => $to
        ];
    }

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// ===============================
// ADMIN / STAFF ITEM DISPOSAL
// ===============================

public function getDisposalStatistics($from = null, $to = null)
{
    $sql = "
        SELECT
            COUNT(*) AS total_items,
            SUM(status = 'Disposed') AS disposed_items
        FROM items
        WHERE deleted = '0'
    ";

    $params = [];

    if ($from !== null && $to !== null) {
        $sql .= " AND when_found BETWEEN :fromDate AND :toDate";

        $params = [
            ":fromDate" => $from,
            ":toDate" => $to
        ];
    }

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// ===============================
// ADMIN / STAFF RESOLUTION TIMES
// ===============================

public function getResolutionTimes($from = null, $to = null)
{
    $sql = "
        SELECT
            AVG(
                CASE
                    WHEN r.type = 'Loss Report'
                    THEN TIMESTAMPDIFF(DAY, r.created_at, rul.updated_at)
                END
            ) AS loss_match,

            AVG(
                CASE
                    WHEN r.type = 'Surrender Form'
                    THEN TIMESTAMPDIFF(DAY, r.created_at, rul.updated_at)
                END
            ) AS surrender_approval,

            AVG(
                CASE
                    WHEN r.type = 'Claim request'
                    THEN TIMESTAMPDIFF(DAY, r.created_at, rul.updated_at)
                END
            ) AS claim_verification

        FROM reports r

        LEFT JOIN reports_update_log rul
            ON r.report_id = rul.report_id

        WHERE r.deleted = '0'
    ";

    $params = [];

    if ($from !== null && $to !== null) {
        $sql .= "
            AND r.created_at BETWEEN :fromDate AND :toDate
        ";

        $params = [
            ":fromDate" => $from,
            ":toDate" => $to
        ];
    }

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}

?>