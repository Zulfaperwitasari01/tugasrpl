<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "keuangan_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>
