<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil semua kategori milik user atau umum (NULL)
$kategori = $conn->query("SELECT * FROM kategori WHERE user_id = $user_id OR user_id IS NULL");

if (isset($_POST['submit'])) {
    $kategori_id = $_POST['kategori_id'];
    $nominal = str_replace('.', '', $_POST['nominal']);
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

    $conn->query("INSERT INTO pemasukan (user_id, kategori_id, nominal, deskripsi, tanggal) 
        VALUES ('$user_id', '$kategori_id', '$nominal', '$deskripsi', '$tanggal')");

    header("Location: data_pemasukan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pemasukan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 40px auto;
            max-width: 600px;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            color:hsl(212, 39.40%, 49.20%);
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"], input[type="date"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-top: 5px;
        }

        button {
            background-color:hsl(220, 63.90%, 62.00%);
            color: white;
            padding: 10px 16px;
            margin-top: 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color:#4569a0;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #555;
        }

        a:hover {
            color: #000;
        }
    </style>
</head>
<body>

    <h2>➕ Tambah Pemasukan</h2>

    <form method="post">
        <label for="kategori_id">Kategori:</label>
        <select name="kategori_id" id="kategori_id" required>
            <option value="">-- Pilih Kategori --</option>
            <?php while ($row = $kategori->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama_kategori']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="nominal">Nominal:</label>
        <input type="text" name="nominal" id="nominal" required placeholder="Contoh: 50000">

        <label for="deskripsi">Deskripsi:</label>
        <input type="text" name="deskripsi" id="deskripsi" placeholder="Misal: Gaji freelance">

        <label for="tanggal">Tanggal:</label>
        <input type="date" name="tanggal" id="tanggal" required>

        <button type="submit" name="submit">✔️ Simpan Pemasukan</button>
    </form>

    <a href="data_pemasukan.php">← Kembali ke Data Pemasukan</a>

    <script>
    document.getElementById('nominal').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value) {
            e.target.value = new Intl.NumberFormat('id-ID').format(value);
        } else {
            e.target.value = '';
        }
    });
    </script>

</body>
</html>
