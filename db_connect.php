<?php
$servername = "sql100.infinityfree.com";
$username = "if0_40531169";
$password = "Tanhung2602";
$dbname = "if0_40531169_on_trac_nghiem";
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "on_trac_nghiem";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bai:  " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>