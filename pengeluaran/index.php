<?php
include '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("
    SELECT pengeluaran.*, kategori_keuangan.nama_kategori 
    FROM pengeluaran 
    JOIN kategori_keuangan ON pengeluaran.kategori_id = kategori_keuangan.id 
    WHERE pengeluaran.user_id = $user_id 
    ORDER BY pengeluaran.tanggal DESC
");
?>

<h2>Daftar Pengeluaran</h2>
<a href="tambah.php">+ Tambah Pengeluaran</a>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Tanggal</th>
        <th>Kategori</th>
        <th>Nominal</th>
        <th>Barang</th>
        <th>Tempat</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['tanggal'] ?></td>
        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
        <td>Rp<?= number_format($row['nominal'], 2, ',', '.') ?></td>
        <td><?= htmlspecialchars($row['barang']) ?></td>
        <td><?= htmlspecialchars($row['tempat']) ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<a href="../dashboard/index.php">Kembali ke Dashboard</a>
