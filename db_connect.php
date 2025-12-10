<?php
$servername = "sql100.infinityfree.com";
$username = "if0_40531169";
$password = "Tanhung2602";
$dbname = "if0_40531169_on_trac_nghiem";

// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "on_trac_nghiem";

try {
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die('Kết nối thất bại: ' . $e->getMessage());
}

// Backwards-compatible alias used in many files
$conn = $pdo;
?>