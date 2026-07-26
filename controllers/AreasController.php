<?php

require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../models/Areas.php";

$areasModel = new Areas($conn);
$areas = $areasModel->getAllAreas();