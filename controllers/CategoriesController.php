<?php

require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../models/Categories.php";

$categoriesModel = new Categories($conn);
$categories = $categoriesModel->getAllCategories();