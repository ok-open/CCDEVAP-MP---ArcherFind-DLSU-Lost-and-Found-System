<?php

class Items
{
    private $conn;

    public function __construct($database)
    {
        $this->conn = $database;
    }

    public function getAvailableItems($search = "", $category = "", $sort = "recent")
    {
        $sql = "
            SELECT
                i.item_id,
                i.name,
                i.description,
                c.name AS category,
                b.name AS brand,

                (
                    SELECT img_filepath
                    FROM items_images
                    WHERE item_id = i.item_id
                    LIMIT 1
                ) AS img_filepath

            FROM items i

            LEFT JOIN categories c
                ON i.category_id = c.category_id

            LEFT JOIN brands b
                ON i.brand_id = b.brand_id

            WHERE
                i.deleted = '0'
                AND i.status = 'In Storage'
        ";

        $params = [];

        // Search by item name
        if (!empty($search)) {
            $sql .= " AND i.name LIKE ?";
            $params[] = "%{$search}%";
        }

        // Filter by category
        if (!empty($category)) {
            $sql .= " AND c.name = ?";
            $params[] = $category;
        }

        // Sorting
        switch ($sort) {
            case "name":
                $sql .= " ORDER BY i.name ASC";
                break;

            case "recent":
            default:
                $sql .= " ORDER BY i.created_at DESC";
                break;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    public function getItemById($itemId)
    {
        $sql = "
            SELECT
                i.item_id,
                i.name,
                i.description,
                c.name AS category,
                b.name AS brand,

                (
                    SELECT img_filepath
                    FROM items_images
                    WHERE item_id = i.item_id
                    LIMIT 1
                ) AS img_filepath,

                (
                    SELECT GROUP_CONCAT(img_filepath ORDER BY image_id SEPARATOR ',')
                    FROM items_images
                    WHERE item_id = i.item_id
                ) AS image_paths

            FROM items i

            LEFT JOIN categories c
                ON i.category_id = c.category_id

            LEFT JOIN brands b
                ON i.brand_id = b.brand_id

            WHERE
                i.item_id = :item_id
                AND i.deleted = '0'
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":item_id", $itemId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}