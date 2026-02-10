<?php
include '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama_kategori'];
    $saldo = $_POST['saldo_awal'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO kategori_keuangan (user_id, nama_kategori, saldo_awal) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $user_id, $nama, $saldo);
    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Gagal menambahkan kategori";
    }
}
?>

<h2>Tambah Kategori Keuangan</h2>
<form method="post">
    <input type="text" name="nama_kategori" required placeholder="Nama Kategori"><br>
    <input type="number" name="saldo_awal" step="0.01" required placeholder="Saldo Awal"><br>
    <button type="submit">Tambah</button>
</form>
<a href="index.php">Kembali ke daftar</a>
