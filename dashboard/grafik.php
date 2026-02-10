<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil total pengeluaran per kategori
$query = $conn->query("
    SELECT k.nama_kategori, SUM(p.nominal) as total 
    FROM pengeluaran p 
    JOIN kategori k ON p.kategori_id = k.id 
    WHERE p.user_id = $user_id 
    GROUP BY p.kategori_id
");

$labels = [];
$data = [];

while ($row = $query->fetch_assoc()) {
    $labels[] = $row['nama_kategori'];
    $data[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Grafik Pengeluaran</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5faff;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #007bff;
        }

        .chart-container {
    max-width: 600px; /* sebelumnya 800px */
    margin: 40px auto;
    background: white;
    border-radius: 16px;
    box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
    padding: 30px;
}

canvas {
    margin-top: 20px;
    max-height: 300px; /* batasi tinggi grafik */
}


        a {
            display: block;
            text-align: center;
            margin-top: 40px;
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="chart-container">
    <h2>Grafik Pengeluaran per Kategori</h2>
    <canvas id="pieChart" height="200"></canvas>
    <canvas id="barChart" height="200"></canvas>
</div>

<a href="index.php">← Kembali ke Dashboard</a>

<script>
const labels = <?= json_encode($labels) ?>;
const data = <?= json_encode($data) ?>;

// Warna dinamis untuk pie chart
const colors = [
    '#36a2eb', '#4bc0c0', '#007bff', '#5dade2', '#aed6f1', '#2980b9',
    '#85c1e9', '#2874a6', '#1f618d', '#5499c7'
];

new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: colors,
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        animation: {
            animateScale: true,
            animateRotate: true
        },
        plugins: {
            legend: {
                position: 'right'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': Rp ' + context.parsed.toLocaleString();
                    }
                }
            }
        }
    }
});

new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Total Pengeluaran',
            data: data,
            backgroundColor: '#36a2eb',
            borderRadius: 10
        }]
    },
    options: {
        responsive: true,
        animation: {
            duration: 1200,
            easing: 'easeOutBounce'
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>

</body>
</html>
