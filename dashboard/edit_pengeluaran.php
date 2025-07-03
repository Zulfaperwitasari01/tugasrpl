<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Ambil data pengeluaran yang akan diedit
$query = $conn->query("SELECT * FROM pengeluaran WHERE id = $id AND user_id = $user_id");
$data = $query->fetch_assoc();

if (!$data) {
    echo "Data tidak ditemukan atau bukan milikmu.";
    exit;
}

// Ambil semua kategori untuk dropdown
$kategori = $conn->query("SELECT * FROM kategori WHERE user_id = $user_id OR user_id IS NULL");

if (isset($_POST['submit'])) {
    $kategori_id = $_POST['kategori_id'];
    $nominal = $_POST['nominal'];
    $barang = $_POST['barang'];
    $tempat = $_POST['tempat'];
    $tanggal = $_POST['tanggal'];

    $conn->query("UPDATE pengeluaran SET 
        kategori_id = '$kategori_id', 
        nominal = '$nominal', 
        barang = '$barang', 
        tempat = '$tempat', 
        tanggal = '$tanggal' 
        WHERE id = $id AND user_id = $user_id");

    header("Location: data_pengeluaran.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pengeluaran</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e6f2ff;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 500px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 100, 0.1);
        }

        h2 {
            text-align: center;
            color: #004080;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            color: #003366;
            font-weight: 500;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            background-color: #f9f9f9;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 12px;
            margin-top: 20px;
            width: 100%;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Pengeluaran</h2>
    <form method="post">
        <label for="kategori_id">Kategori:</label>
        <select name="kategori_id" id="kategori_id" required>
            <?php while ($row = $kategori->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= $row['id'] == $data['kategori_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['nama_kategori']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="nominal">Nominal:</label>
        <input type="number" name="nominal" id="nominal" value="<?= $data['nominal'] ?>" required>

        <label for="barang">Barang:</label>
        <input type="text" name="barang" id="barang" value="<?= $data['barang'] ?>">

        <label for="tempat">Tempat:</label>
        <input type="text" name="tempat" id="tempat" value="<?= $data['tempat'] ?>">

        <label for="tanggal">Tanggal:</label>
        <input type="date" name="tanggal" id="tanggal" value="<?= $data['tanggal'] ?>" required>

        <button type="submit" name="submit">Simpan Perubahan</button>
    </form>
    <a href="data_pengeluaran.php">← Kembali</a>
</div>

</body>
</html>
