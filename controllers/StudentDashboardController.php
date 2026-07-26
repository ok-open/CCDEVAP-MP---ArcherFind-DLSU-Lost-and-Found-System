<?php

session_start();


require_once "../db.php";
require_once "../models/Dashboard.php";



if (!isset($_SESSION["user_id"])) {

    echo json_encode([
        "error"=>"Unauthorized"
    ]);

    exit();

}



$dashboard = new Dashboard($conn);


$studentId = $_SESSION["user_id"];



$data = [

    "stats" =>
        $dashboard->getReportStatistics($studentId),


    "reports" =>
        $dashboard->getReportHistory($studentId),


    "chart" =>
        $dashboard->getLostItemFrequency($studentId)

];



header(
    "Content-Type: application/json"
);



echo json_encode($data);


?>