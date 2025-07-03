<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Proses Tambah Kategori
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nama_kategori'])) {
    $nama_kategori = trim($_POST['nama_kategori']);

    if (!empty($nama_kategori)) {
        $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori, user_id) VALUES (?, ?)");
        $stmt->bind_param("si", $nama_kategori, $user_id);
        $stmt->execute();
        $stmt->close();
    } else {
        $error = "Nama kategori tidak boleh kosong!";
    }
}

// Proses Hapus Kategori
if (isset($_GET['hapus'])) {
    $hapus_id = intval($_GET['hapus']);
    $stmt = $conn->prepare("DELETE FROM kategori WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $hapus_id, $user_id);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori</title>
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
            width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2, h3 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="submit"] {
            background-color: #36a2eb;
            color: white;
            border: none;
            padding: 10px 20px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s;
        }

        input[type="submit"]:hover {
            background-color: #1b74d6;
        }

        .back {
            display: block;
            margin-top: 20px;
            text-decoration: none;
            color: #555;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }

        li {
            background: #eef3f7;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .hapus-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.2s;
            text-decoration: none;
        }

        .hapus-btn:hover {
            background-color: #c0392b;
        }

        hr {
            margin: 30px 0;
            border: none;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📂 Tambah Kategori Baru</h2>

    <?php if (isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <label for="nama_kategori">Nama Kategori:</label>
        <input type="text" name="nama_kategori" id="nama_kategori" required>
        <input type="submit" value="Tambah">
    </form>

    <hr>

    <h3>📋 Daftar Kategori Kamu</h3>
    <ul>
        <?php
        $result = $conn->prepare("SELECT id, nama_kategori FROM kategori WHERE user_id = ?");
        $result->bind_param("i", $user_id);
        $result->execute();
        $result->bind_result($id_kategori, $nama_kategori);

        $ada_data = false;
        while ($result->fetch()):
            $ada_data = true;
        ?>
            <li>
                <?= htmlspecialchars($nama_kategori) ?>
                <a class="hapus-btn" href="?hapus=<?= $id_kategori ?>" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
            </li>
        <?php endwhile; ?>

        <?php if (!$ada_data): ?>
            <li><em>Belum ada kategori</em></li>
        <?php endif; ?>

        <?php $result->close(); ?>
    </ul>

    <a class="back" href="index.php">⬅️ Kembali ke Dashboard</a>
</div>

</body>
</html>
