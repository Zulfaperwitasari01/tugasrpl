<?php

ob_start();


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    
    $id = intval($id);
    $user_id = intval($_SESSION['user_id']);

    // Cari data pengeluaran
    $cek = $conn->query("SELECT * FROM pengeluaran WHERE id = $id AND user_id = $user_id");
    if ($cek && $cek->num_rows > 0) {
        $data = $cek->fetch_assoc();
        $kategori_id = intval($data['kategori_id']);
        $nominal = floatval($data['nominal']);

        // Hapus pengeluaran
        $conn->query("DELETE FROM pengeluaran WHERE id = $id");

        // Kembalikan saldo kategori
        $conn->query("UPDATE kategori SET saldo = saldo + $nominal WHERE id = $kategori_id");
    }
}

header("Location: index.php");
exit;


ob_end_flush();
