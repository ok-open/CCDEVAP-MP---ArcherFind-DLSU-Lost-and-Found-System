<?php

class Categories
{
    private $conn;

    public function __construct($database)
    {
        $this->conn = $database;
    }

    // Retrieve all active categories (used for dropdown menus)
    public function getAllCategories()
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

    // Retrieve a single category by ID
    public function getCategoryById($categoryId)
    {
        $sql = "
            SELECT
                category_id,
                name
            FROM categories
            WHERE category_id = :category_id
            AND deleted = '0'
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":category_id", $categoryId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}