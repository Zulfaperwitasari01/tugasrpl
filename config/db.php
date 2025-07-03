<?php
$host = getenv("MYSQLHOST");
$user = getenv("MYSQLUSER");
$pass = getenv("MYSQLPASSWORD");
$dbname = getenv("MYSQL_DATABASE"); 
echo "MYSQLHOST: " . getenv("MYSQLHOST") . "<br>";
echo "MYSQLUSER: " . getenv("MYSQLUSER") . "<br>";
echo "MYSQLPASSWORD: " . getenv("MYSQLPASSWORD") . "<br>";
echo "MYSQL_DATABASE: " . getenv("MYSQL_DATABASE") . "<br>";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>
