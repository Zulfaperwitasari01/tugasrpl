<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil bulan awal pengeluaran
$awal = $conn->query("SELECT MIN(tanggal) as awal FROM pengeluaran WHERE user_id = $user_id")->fetch_assoc()['awal'];
$bulanAwal = $awal ? date('Y-m', strtotime($awal)) : date('Y-m');
$bulanSekarang = date('Y-m');

// Ambil bulan dari filter (GET)
$selectedBulan = isset($_GET['bulan']) ? $_GET['bulan'] : $bulanSekarang;
$bulanFilterAwal = $selectedBulan . "-01";
$bulanFilterAkhir = date('Y-m-t', strtotime($bulanFilterAwal));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pengeluaran</title> <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f8ff;
            color: #333;
            margin: 20px;
        }

        h2 {
            color: #1e90ff;
        }

        a {
            color: #1e90ff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        select, label {
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background-color: #b0d4f1;
            color: #003366;
            padding: 10px;
            text-align: left;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #dbeafe;
        }

        tr:nth-child(even) {
            background-color: #e6f0fa;
        }

        tr:hover {
            background-color: #d0ebff;
        }

        hr {
            border: none;
            height: 1px;
            background-color: #a3c9f1;
            margin: 20px 0;
        }

        form {
            margin: 10px 0;
        }

        .kategori-title {
            color: #0a6bb8;
            margin-top: 30px;
        }

        .no-data {
            color: gray;
            text-align: center;
            font-style: italic;
        }

        .footer-note {
            font-size: 13px;
            color: #666;
            margin-top: 15px;
        }

        .action-link {
            color: #0a6bb8;
        }

        .action-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>📉 Data Pengeluaran</h2>
<p>Hai, <?= $_SESSION['nama']; ?>. Berikut adalah riwayat pengeluaranmu bulan <b><?= date('F Y', strtotime($selectedBulan)) ?></b>.</p>

<a href="index.php">← Kembali ke Dashboard</a> |
<a href="tambah_pengeluaran.php">➕ Tambah Pengeluaran</a>

<hr>

<!-- Filter Bulan -->
<form method="get">
    <label for="bulan">Pilih Bulan:</label>
    <select name="bulan" id="bulan" onchange="this.form.submit()">
        <?php
        $current = strtotime($bulanSekarang . '-01');
        $start = strtotime($bulanAwal . '-01');

        while ($current >= $start) {
            $value = date('Y-m', $current);
            $label = date('F Y', $current);
            $selected = ($value == $selectedBulan) ? 'selected' : '';
            echo "<option value='$value' $selected>$label</option>";
            $current = strtotime('-1 month', $current);
        }
        ?>
    </select>
</form>

<hr>

<!-- Tabel Pengeluaran -->
<table>
    <tr>
        <th>No</th>
        <th>Kategori</th>
        <th>Nominal</th>
        <th>Barang</th>
        <th>Tempat</th>
        <th>Tanggal</th>
        <th>Aksi</th>
    </tr>
    <?php
    $query = "
        SELECT p.*, k.nama_kategori 
        FROM pengeluaran p 
        JOIN kategori k ON p.kategori_id = k.id 
        WHERE p.user_id = $user_id 
        AND p.tanggal BETWEEN '$bulanFilterAwal' AND '$bulanFilterAkhir'
        ORDER BY p.tanggal DESC
    ";
    $pengeluaran = $conn->query($query);
    $no = 1;

    if ($pengeluaran->num_rows > 0) {
        while ($row = $pengeluaran->fetch_assoc()) {
            echo "<tr>
                <td>{$no}</td>
                <td>{$row['nama_kategori']}</td>
                <td>Rp" . number_format((float)$row['nominal'], 0, ',', '.') . "</td>
                <td>{$row['barang']}</td>
                <td>{$row['tempat']}</td>
                <td>" . date('d-m-Y', strtotime($row['tanggal'])) . "</td>
                <td>
                    <a class='action-link' href='edit_pengeluaran.php?id={$row['id']}'>✏️ Edit</a> |
                    <a class='action-link' href='hapus_pengeluaran.php?id={$row['id']}' onclick='return confirm(\"Yakin ingin hapus data ini?\")'>🗑️ Hapus</a>
                </td>
            </tr>";
            $no++;
        }
    } else {
        echo "<tr><td colspan='7' class='no-data'>Tidak ada pengeluaran pada bulan ini.</td></tr>";
    }
    ?>
</table>

<p class="footer-note"><i>Gunakan filter bulan di atas untuk melihat pengeluaran pada bulan tertentu.</i></p>

<?php
// Ambil daftar kategori yang digunakan user
$kategoriList = $conn->query("
    SELECT DISTINCT k.id, k.nama_kategori 
    FROM pengeluaran p 
    JOIN kategori k ON p.kategori_id = k.id 
    WHERE p.user_id = $user_id
    ORDER BY k.nama_kategori ASC
");

if ($kategoriList->num_rows > 0) {
    while ($kategori = $kategoriList->fetch_assoc()) {
        $kategori_id = $kategori['id'];
        $kategori_nama = $kategori['nama_kategori'];

        echo "<h3 class='kategori-title'>📂 Kategori: <u>$kategori_nama</u></h3>";

        $query = "
            SELECT * 
            FROM pengeluaran 
            WHERE user_id = $user_id 
            AND kategori_id = $kategori_id
            AND tanggal BETWEEN '$bulanFilterAwal' AND '$bulanFilterAkhir'
            ORDER BY tanggal DESC
        ";
        $pengeluaran = $conn->query($query);
        $no = 1;

        if ($pengeluaran->num_rows > 0) {
            echo "<table>
                <tr>
                    <th>No</th>
                    <th>Nominal</th>
                    <th>Barang</th>
                    <th>Tempat</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>";
            while ($row = $pengeluaran->fetch_assoc()) {
                echo "<tr>
                    <td>{$no}</td>
                    <td>Rp" . number_format((float)$row['nominal'], 0, ',', '.') . "</td>
                    <td>{$row['barang']}</td>
                    <td>{$row['tempat']}</td>
                    <td>" . date('d-m-Y', strtotime($row['tanggal'])) . "</td>
                    <td>
                        <a class='action-link' href='edit_pengeluaran.php?id={$row['id']}'>✏️ Edit</a> |
                        <a class='action-link' href='hapus_pengeluaran.php?id={$row['id']}' onclick='return confirm(\"Yakin ingin hapus data ini?\")'>🗑️ Hapus</a>
                    </td>
                </tr>";
                $no++;
            }
            echo "</table><br>";
        } else {
            echo "<p class='no-data'>Tidak ada pengeluaran untuk kategori ini di bulan ini.</p>";
        }
    }
} else {
    echo "<p class='no-data'>Tidak ada data pengeluaran tersedia.</p>";
}
?>

</body>
</html>
