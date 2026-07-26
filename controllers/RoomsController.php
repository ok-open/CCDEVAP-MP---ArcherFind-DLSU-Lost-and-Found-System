<?php

require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../models/Rooms.php";

$roomsModel = new Rooms($conn);
$rooms = $roomsModel->getAllRooms();