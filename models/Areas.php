<?php

class Areas
{
    private $conn;

    public function __construct($database)
    {
        $this->conn = $database;
    }

    // Retrieve all active areas (used for dropdown menus)
    public function getAllAreas()
    {
        $sql = "
            SELECT
                area_id,
                name,
                building_id,
                level
            FROM areas
            WHERE deleted = '0'
            ORDER BY building_id, level, name
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve a single area by ID
    public function getAreaById($areaId)
    {
        $sql = "
            SELECT
                area_id,
                name,
                building_id,
                level
            FROM areas
            WHERE area_id = :area_id
            AND deleted = '0'
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":area_id", $areaId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}