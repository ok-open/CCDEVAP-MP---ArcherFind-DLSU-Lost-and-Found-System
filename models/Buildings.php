<?php

class Buildings
{
    private $conn;

    public function __construct($database)
    {
        $this->conn = $database;
    }

    // Retrieve all active buildings (used for dropdown menus)
    public function getAllBuildings()
    {
        $sql = "
            SELECT
                building_id,
                name,
                abbreviation,
                max_level
            FROM buildings
            WHERE deleted = '0'
            ORDER BY name
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve a single building by ID
    public function getBuildingById($buildingId)
    {
        $sql = "
            SELECT
                building_id,
                name,
                abbreviation,
                max_level
            FROM buildings
            WHERE building_id = :building_id
            AND deleted = '0'
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":building_id", $buildingId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}