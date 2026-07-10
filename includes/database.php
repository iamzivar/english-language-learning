<?php
$host = "sql208.infinityfree.com";  // یا sql208.byetcluster.com
$dbname = "if0_42380325_english_db";
$username = "if0_42380325";
$password = "glvX5Qhd83fk";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("خطا در اتصال به دیتابیس: " . $e->getMessage());
}
?>
