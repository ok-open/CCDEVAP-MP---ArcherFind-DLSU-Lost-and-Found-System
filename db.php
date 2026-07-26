<?php
$host = "localhost";
$dbname = "ArcherFinddb";   // Replace with your database name
$username = "root";
$password = "22757205";           // Replace with your MySQL password if you have one

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>