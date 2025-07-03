<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Proses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kategori_id = $_POST['kategori_id'];
    $nominal = $_POST['nominal'];
    $barang = $_POST['barang'];
    $tempat = $_POST['tempat'];
    $tanggal = $_POST['tanggal'];

    // Simpan pengeluaran
    $stmt = $conn->prepare("INSERT INTO pengeluaran (user_id, kategori_id, nominal, barang, tempat, tanggal) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisss", $user_id, $kategori_id, $nominal, $barang, $tempat, $tanggal);
    $stmt->execute();

    // Update saldo kategori (kurangi)
    $conn->query("UPDATE kategori SET saldo = saldo - $nominal WHERE id = $kategori_id AND user_id = $user_id");

    // Redirect kembali
    header("Location: data_pengeluaran.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pengeluaran</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f9ff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: start;
            min-height: 100vh;
        }

        .form-container {
            background: #ffffff;
            padding: 36px 28px;
            border-radius: 16px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.1);
            margin-top: 40px;
            width: 100%;
            max-width: 520px;
        }

        h2 {
            margin-bottom: 18px;
            font-size: 22px;
            color: #36a2eb;
            text-align: center;
        }

        a.back-link {
            display: inline-block;
            margin-bottom: 24px;
            text-decoration: none;
            color: #7f8c8d;
            font-size: 14px;
        }

        a.back-link:hover {
            color: #2c3e50;
        }

        label {
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
            color: #2c3e50;
        }

        select,
        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 14px;
        }

        button {
            background-color: #36a2eb;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
            font-size: 15px;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #1e88e5;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>➖ Tambah Pengeluaran</h2>
    <a href="data_pengeluaran.php" class="back-link">⬅️ Kembali ke Riwayat Pengeluaran</a>

    <form method="post">
        <label for="kategori_id">Kategori:</label>
        <select name="kategori_id" required>
            <option value="">-- Pilih Kategori --</option>
            <?php
            $kategori = $conn->query("SELECT * FROM kategori WHERE user_id = $user_id");
            while ($row = $kategori->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['nama_kategori']}</option>";
            }
            ?>
        </select>

        <label for="nominal">Nominal (Rp):</label>
        <input type="number" name="nominal" required min="1">

        <label for="barang">Barang:</label>
        <input type="text" name="barang" required>

        <label for="tempat">Tempat:</label>
        <input type="text" name="tempat" required>

        <label for="tanggal">Tanggal:</label>
        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required>

        <button type="submit">💸 Simpan Pengeluaran</button>
    </form>
</div>

</body>
</html>
