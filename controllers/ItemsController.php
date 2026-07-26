<?php

require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../models/Items.php";

$itemModel = new Items($conn);

$itemId = $_GET["id"] ?? "";

if (is_numeric($itemId)) {
    $item = $itemModel->getItemById($itemId);

    if (!$item) {
        header("Location: ../pages/student/student_item-view.php");
        exit();
    }
} else {
    $search = trim($_GET["search"] ?? "");
    $category = trim($_GET["category"] ?? "");
    $sort = $_GET["sort"] ?? "recent";

    $items = $itemModel->getAvailableItems(
        $search,
        $category,
        $sort
    );

    $categories = $itemModel->getCategories();
}