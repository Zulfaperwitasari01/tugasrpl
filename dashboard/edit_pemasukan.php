<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$data = $conn->query("SELECT * FROM pemasukan WHERE id = $id AND user_id = $user_id")->fetch_assoc();
$kategori = $conn->query("SELECT * FROM kategori WHERE user_id = $user_id OR user_id IS NULL");

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}

if (isset($_POST['submit'])) {
    $kategori_id = $_POST['kategori_id'];
    $nominal = $_POST['nominal'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

    $conn->query("UPDATE pemasukan SET 
        kategori_id = '$kategori_id', 
        nominal = '$nominal', 
        deskripsi = '$deskripsi', 
        tanggal = '$tanggal' 
        WHERE id = $id AND user_id = $user_id");

    header("Location: data_pemasukan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Pemasukan</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #eef5ff;
      color: #2c3e50;
      padding: 30px 15px;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #fff;
      border-radius: 16px;
      padding: 40px 30px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    h2 {
      text-align: center;
      color: #007bff;
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 30px;
    }

    form label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: #34495e;
    }

    select,
    input[type="text"],
    input[type="number"],
    input[type="date"] {
      width: 100%;
      padding: 14px 16px;
      font-size: 15px;
      margin-bottom: 22px;
      border: 1px solid #ccc;
      border-radius: 12px;
      background-color: #fefefe;
      transition: all 0.2s ease-in-out;
    }

    select:focus,
    input:focus {
      border-color: #007bff;
      outline: none;
      background-color: #f9fbff;
    }

    button {
      width: 100%;
      padding: 14px;
      font-size: 16px;
      background-color: #007bff;
      color: #fff;
      font-weight: 600;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #0056b3;
    }

    .back-link {
      margin-top: 25px;
      text-align: center;
    }

    .back-link a {
      color: #007bff;
      font-size: 14px;
      text-decoration: none;
    }

    .back-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>✏️ Edit Pemasukan</h2>
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
      <input type="text" name="nominal" id="nominal" value="<?= number_format($data['nominal'], 2, ',', '.') ?>" required>

      <label for="deskripsi">Deskripsi:</label>
      <input type="text" name="deskripsi" id="deskripsi" value="<?= htmlspecialchars($data['deskripsi']) ?>">

      <label for="tanggal">Tanggal:</label>
      <input type="date" name="tanggal" id="tanggal" value="<?= $data['tanggal'] ?>" required>

      <button type="submit" name="submit">💾 Simpan Perubahan</button>
    </form>
    <div class="back-link">
      <a href="data_pemasukan.php">← Kembali ke Data Pemasukan</a>
    </div>
  </div>
</body>
</html>
