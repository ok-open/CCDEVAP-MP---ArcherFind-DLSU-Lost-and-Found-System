<?php

class Rooms
{
    private $conn;

    public function __construct($database)
    {
        $this->conn = $database;
    }

    // Retrieve all active rooms (used for dropdown menus)
    public function getAllRooms()
    {
        $sql = "
            SELECT
                room_id,
                name,
                building_id,
                level
            FROM rooms
            WHERE deleted = '0'
            ORDER BY building_id, level, name
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve a single room by ID
    public function getRoomById($roomId)
    {
        $sql = "
            SELECT
                room_id,
                name,
                building_id,
                level
            FROM rooms
            WHERE room_id = :room_id
            AND deleted = '0'
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":room_id", $roomId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}