<?php
session_start();
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    // Cari data pengeluaran
    $cek = $conn->query("SELECT * FROM pengeluaran WHERE id = $id AND user_id = {$_SESSION['user_id']}");
    if ($cek->num_rows > 0) {
        $data = $cek->fetch_assoc();
        $kategori_id = $data['kategori_id'];
        $nominal = $data['nominal'];

        // Hapus pengeluaran
        $conn->query("DELETE FROM pengeluaran WHERE id = $id");

        // Kembalikan saldo kategori
        $conn->query("UPDATE kategori SET saldo = saldo + $nominal WHERE id = $kategori_id");
    }
}

header("Location: index.php");
exit;
