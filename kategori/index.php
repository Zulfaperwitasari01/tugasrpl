<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data kategori dari database untuk user yang sedang login
$stmt = $conn->prepare("SELECT id_kategori, nama_kategori FROM kategori1 WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Kategori</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fb;
            padding: 40px;
            color: #333;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #36a2eb;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .add-link {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            background-color: #2ecc71;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            font-weight: bold;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📂 Daftar Kategori</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Belum ada kategori ditambahkan.</p>
    <?php endif; ?>

    <a class="add-link" href="index.php">➕ Tambah Kategori</a>
    <br>
    <a class="back" href="../dashboard.php">⬅️ Kembali ke Dashboard</a>
</div>

</body>
</html>
