<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$selectedBulan = $_GET['bulan'] ?? date('Y-m');

// Total Pemasukan
$sqlPemasukan = $conn->query("SELECT SUM(nominal) as total_pemasukan FROM pemasukan WHERE user_id = $user_id AND DATE_FORMAT(tanggal, '%Y-%m') = '$selectedBulan'");
$totalPemasukan = $sqlPemasukan->fetch_assoc()['total_pemasukan'] ?? 0;

// Total Pengeluaran
$sqlPengeluaran = $conn->query("SELECT SUM(nominal) as total_pengeluaran FROM pengeluaran WHERE user_id = $user_id AND DATE_FORMAT(tanggal, '%Y-%m') = '$selectedBulan'");
$totalPengeluaran = $sqlPengeluaran->fetch_assoc()['total_pengeluaran'] ?? 0;

// Saldo
$saldoBersih = $totalPemasukan - $totalPengeluaran;
$warnaSaldo = $saldoBersih < 0 ? '#e74c3c' : '#2ecc71';

// Grafik Pengeluaran per Kategori (bulan dipilih)
$query = $conn->query("
    SELECT k.nama_kategori, SUM(p.nominal) as total 
    FROM pengeluaran p 
    JOIN kategori k ON p.kategori_id = k.id 
    WHERE p.user_id = $user_id 
    AND DATE_FORMAT(p.tanggal, '%Y-%m') = '$selectedBulan'
    GROUP BY p.kategori_id
");

$labels = [];
$data = [];

while ($row = $query->fetch_assoc()) {
    $labels[] = $row['nama_kategori'];
    $data[] = round($row['total']); // dibulatkan
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Keuangan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f5f9ff;
            color: #2c3e50;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 220px;
            background-color: #ffffff;
            padding: 30px 20px 80px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: sticky;
            top: 0;
            height: 100vh;
        }
        .sidebar h2 {
            font-size: 20px;
            color: #36a2eb;
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            padding: 10px 14px;
            margin-bottom: 12px;
            text-decoration: none;
            color: #2c3e50;
            border-radius: 10px;
            font-weight: 500;
            border-bottom: 1px solid #eee;
            transition: background 0.2s;
        }
        .sidebar a:hover { background-color: #e3f2fd; }
        .sidebar a.logout { color: #e74c3c; }

        .main-content {
            flex: 1;
            padding: 40px;
        }
        .main-content h1 { font-size: 26px; }

        .cards {
            display: flex;
            gap: 20px;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .card {
            flex: 1;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            min-width: 200px;
        }

        .card h3 {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        .saldo { color: <?= $warnaSaldo ?>; }

        .filter-form {
            margin-bottom: 20px;
        }

        .filter-form select {
            padding: 8px;
            font-size: 14px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        th {
            background-color: #eaf4ff;
            color: #2c3e50;
        }

        tr:hover {
            background-color: #f9fcff;
        }

        .chart-container {
            max-width: 950px;
            margin: 40px auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        canvas { max-height: 300px; }
        .note {
            font-size: 13px;
            margin-top: 10px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="sidebar">
        <h2>📊 Catat Duit</h2>
        <a href="index.php">🏠 Dashboard</a>
        <a href="tambah_kategori.php">📂 Kategori</a>
        <a href="data_pemasukan.php">💰 Pemasukan</a>
        <a href="data_pengeluaran.php">🛒 Pengeluaran</a>
        <a href="edit_akun.php">⚙️ Edit Akun</a>
        <a href="../auth/logout.php" class="logout">🚪 Logout</a>
    </div>

    <div class="main-content">
        <h1>Halo, <?= $_SESSION['nama']; ?>!</h1>
        <p>Selamat datang di <b>Dashboard Keuanganmu</b>.</p>

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

        <div class="cards">
            <div class="card">
                <h3>Total Pemasukan</h3>
                <p>Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></p>
            </div>
            <div class="card">
                <h3>Total Pengeluaran</h3>
                <p>Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></p>
            </div>
            <div class="card">
                <h3>Saldo Bulan Ini</h3>
                <p class="saldo">Rp <?= number_format($saldoBersih, 0, ',', '.') ?></p>
            </div>
        </div>

        <div class="chart-container">
            <h2 style="text-align:center; color:#007bff;">Grafik Pengeluaran per Kategori</h2>
            <canvas id="lineChart" height="200"></canvas>
        </div>

        <h2>Riwayat Pemasukan</h2>
        <table>
            <tr>
                <th>Kategori</th>
                <th>Nominal</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
            <?php
            $pemasukan = $conn->query("SELECT p.*, k.nama_kategori FROM pemasukan p JOIN kategori k ON p.kategori_id = k.id WHERE p.user_id = $user_id AND DATE_FORMAT(p.tanggal, '%Y-%m') = '$selectedBulan' ORDER BY p.tanggal DESC");
            while ($row = $pemasukan->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['nama_kategori']}</td>
                        <td>Rp " . number_format($row['nominal'], 0, ',', '.') . "</td>
                        <td>{$row['tanggal']}</td>
                        <td><a href='hapus_pemasukan.php?id={$row['id']}' onclick='return confirm(\"Yakin hapus?\")'>🗑️</a></td>
                      </tr>";
            }
            ?>
        </table>

        <h2>Riwayat Pengeluaran</h2>
        <table>
            <tr>
                <th>Kategori</th>
                <th>Nominal</th>
                <th>Barang</th>
                <th>Tempat</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
            <?php
            $pengeluaran = $conn->query("SELECT p.*, k.nama_kategori FROM pengeluaran p JOIN kategori k ON p.kategori_id = k.id WHERE p.user_id = $user_id AND DATE_FORMAT(p.tanggal, '%Y-%m') = '$selectedBulan' ORDER BY p.tanggal DESC");
            while ($row = $pengeluaran->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['nama_kategori']}</td>
                        <td>Rp " . number_format($row['nominal'], 0, ',', '.') . "</td>
                        <td>{$row['barang']}</td>
                        <td>{$row['tempat']}</td>
                        <td>{$row['tanggal']}</td>
                        <td><a href='hapus_pengeluaran.php?id={$row['id']}' onclick='return confirm(\"Yakin hapus?\")'>🗑️</a></td>
                      </tr>";
            }
            ?>
        </table>

        <p class="note">* Statistik grafik ditampilkan dalam bentuk titik 🎯</p>
    </div>
</div>

<script>
const labels = <?= json_encode($labels) ?>;
const data = <?= json_encode($data) ?>;

new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Total Pengeluaran',
            data: data,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: '#36a2eb',
            borderWidth: 2,
            pointBackgroundColor: '#007bff',
            pointRadius: 5,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            tooltip: {
                callbacks: {
                    label: context => 'Rp ' + context.parsed.y.toLocaleString('id-ID')
                }
            },
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: value => 'Rp ' + value.toLocaleString('id-ID')
                }
            }
        }
    }
});
</script>

</body>
</html>
