<?php
include '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$result = $conn->query("
    SELECT pemasukan.*, kategori_keuangan.nama_kategori 
    FROM pemasukan 
    JOIN kategori_keuangan ON pemasukan.kategori_id = kategori_keuangan.id 
    WHERE pemasukan.user_id = $user_id 
    ORDER BY pemasukan.tanggal DESC
");
?>

<h2>Daftar Pemasukan</h2>
<a href="tambah.php">+ Tambah Pemasukan</a>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Tanggal</th>
        <th>Kategori</th>
        <th>Nominal</th>
        <th>Deskripsi</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['tanggal'] ?></td>
        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
        <td>Rp<?= number_format($row['nominal'], 2, ',', '.') ?></td>
        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<a href="../dashboard/index.php">Kembali ke Dashboard</a>
