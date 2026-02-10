<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$selectedBulan = $_GET['bulan'] ?? date('Y-m');

// Ambil data pemasukan sesuai bulan
$pemasukan = $conn->query("
    SELECT p.*, k.nama_kategori 
    FROM pemasukan p 
    JOIN kategori k ON p.kategori_id = k.id 
    WHERE p.user_id = $user_id 
    AND DATE_FORMAT(p.tanggal, '%Y-%m') = '$selectedBulan'
    ORDER BY p.tanggal DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pemasukan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafc;
            margin: 40px;
            color: #2c3e50;
        }

        .container {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            max-width: 1000px;
            margin: auto;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h2, h3 {
            color: #1f3a93;
        }

        a {
            text-decoration: none;
            color: #3498db;
            margin-right: 10px;
        }

        a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 30px;
        }

        table th, table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #36a2eb;
            color: white;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .aksi a {
            margin-right: 8px;
            color: #2980b9;
            font-weight: 500;
        }

        .aksi a:hover {
            color: #e74c3c;
        }

        hr {
            margin: 30px 0;
            border: none;
            border-top: 1px solid #ccc;
        }

        .kategori-title {
            margin-top: 30px;
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }

        .no-data {
            color: #888;
            font-style: italic;
            margin-left: 10px;
        }

        .filter-form {
            margin: 20px 0;
        }

        .filter-form select {
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>💰 Data Pemasukan</h2>
    <p>Hai, <?= htmlspecialchars($_SESSION['nama']); ?>. Berikut adalah catatan pemasukanmu.</p>

    <a href="index.php">← Kembali ke Dashboard</a> | 
    <a href="tambah_pemasukan.php">➕ Tambah Pemasukan</a>

    <form method="get" class="filter-form">
        <label for="bulan">📅 Pilih Bulan:</label>
        <select name="bulan" id="bulan" onchange="this.form.submit()">
            <?php
            for ($i = 0; $i < 12; $i++) {
                $bulanPilihan = date('Y-m', strtotime("-$i month"));
                $labelBulan = date('F Y', strtotime($bulanPilihan));
                $selected = ($bulanPilihan == $selectedBulan) ? 'selected' : '';
                echo "<option value='$bulanPilihan' $selected>$labelBulan</option>";
            }
            ?>
        </select>
    </form>

    <hr>

    <h3>📋 Pemasukan Bulan <?= date('F Y', strtotime($selectedBulan)) ?></h3>
    <table>
        <tr>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Nominal</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
        <?php if ($pemasukan->num_rows > 0): ?>
            <?php while ($row = $pemasukan->fetch_assoc()): ?>
                <tr>
                    <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                    <td>Rp<?= number_format($row['nominal'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td class="aksi">
                        <a href="edit_pemasukan.php?id=<?= $row['id'] ?>">✏️ Edit</a> | 
                        <a href="hapus_pemasukan.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus?')">🗑️ Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" class="no-data">Belum ada data pemasukan untuk bulan ini.</td></tr>
        <?php endif; ?>
    </table>

    <hr>

    <?php
    // Ambil kategori dari pemasukan yang sesuai bulan
    $kategoriList = $conn->query("
        SELECT DISTINCT k.id, k.nama_kategori 
        FROM pemasukan p 
        JOIN kategori k ON p.kategori_id = k.id 
        WHERE p.user_id = $user_id
        AND DATE_FORMAT(p.tanggal, '%Y-%m') = '$selectedBulan'
        ORDER BY k.nama_kategori ASC
    ");

    if ($kategoriList->num_rows > 0):
        while ($kategori = $kategoriList->fetch_assoc()):
            $kategori_id = $kategori['id'];
            $kategori_nama = $kategori['nama_kategori'];

            echo "<div class='kategori-title'>📂 Kategori: <u>$kategori_nama</u></div>";

            $query = "
                SELECT * 
                FROM pemasukan 
                WHERE user_id = $user_id 
                AND kategori_id = $kategori_id 
                AND DATE_FORMAT(tanggal, '%Y-%m') = '$selectedBulan'
                ORDER BY tanggal DESC
            ";
            $pemasukan_per_kategori = $conn->query($query);
            $no = 1;

            if ($pemasukan_per_kategori->num_rows > 0) {
                echo "<table>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nominal</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>";
                while ($row = $pemasukan_per_kategori->fetch_assoc()) {
                    echo "<tr>
                            <td>{$no}</td>
                            <td>" . date('d-m-Y', strtotime($row['tanggal'])) . "</td>
                            <td>Rp" . number_format($row['nominal'], 0, ',', '.') . "</td>
                            <td>" . htmlspecialchars($row['deskripsi']) . "</td>
                            <td class='aksi'>
                                <a href='edit_pemasukan.php?id={$row['id']}'>✏️ Edit</a> |
                                <a href='hapus_pemasukan.php?id={$row['id']}' onclick='return confirm(\"Yakin hapus?\")'>🗑️ Hapus</a>
                            </td>
                        </tr>";
                    $no++;
                }
                echo "</table>";
            } else {
                echo "<p class='no-data'>Tidak ada pemasukan untuk kategori ini.</p>";
            }
        endwhile;
    else:
        echo "<p class='no-data'>Tidak ada data pemasukan untuk bulan ini.</p>";
    endif;
    ?>
</div>
</body>
</html>
