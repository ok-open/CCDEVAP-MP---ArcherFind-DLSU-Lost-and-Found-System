<?php

require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../models/Brands.php";

$brandsModel = new Brands($conn);
$brands = $brandsModel->getAllBrandsWithCategories();