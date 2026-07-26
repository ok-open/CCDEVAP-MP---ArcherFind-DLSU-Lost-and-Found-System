<?php

require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../models/Buildings.php";

$buildingsModel = new Buildings($conn);
$buildings = $buildingsModel->getAllBuildings();