<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$conn->query("DELETE FROM pemasukan WHERE id = $id AND user_id = $user_id");

header("Location: data_pemasukan.php");
exit;
