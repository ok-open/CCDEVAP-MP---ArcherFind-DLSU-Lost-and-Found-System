<?php

class Brands
{
    private $conn;

    public function __construct($database)
    {
        $this->conn = $database;
    }

    // Retrieve all active brands (used for dropdown menus)
    public function getAllBrands()
    {
        $sql = "
            SELECT
                brand_id,
                name
            FROM brands
            WHERE deleted = '0'
            ORDER BY name ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve a single brand by ID
    public function getBrandById($brandId)
    {
        $sql = "
            SELECT
                brand_id,
                name
            FROM brands
            WHERE brand_id = :brand_id
            AND deleted = '0'
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":brand_id", $brandId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Retrieve brands linked to a specific category via category_brands
    // (for cascading dropdowns — e.g. only show "Apple, Samsung..." when
    // "Electronics" is selected). Optional to use for now.
    public function getBrandsByCategory($categoryId)
    {
        $sql = "
            SELECT
                b.brand_id,
                b.name
            FROM brands b
            INNER JOIN category_brands cb
                ON b.brand_id = cb.brand_id
            WHERE cb.category_id = :category_id
            AND b.deleted = '0'
            ORDER BY b.name ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":category_id", $categoryId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllBrandsWithCategories()
    {
        $sql = "
            SELECT
                b.brand_id,
                b.name,
                GROUP_CONCAT(cb.category_id SEPARATOR ',') AS category_ids
            FROM brands b
            LEFT JOIN category_brands cb
                ON b.brand_id = cb.brand_id
            WHERE b.deleted = '0'
            GROUP BY b.brand_id, b.name
            ORDER BY b.name ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}