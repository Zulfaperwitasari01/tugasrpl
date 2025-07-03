<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data lama
$query = $conn->query("SELECT * FROM users WHERE id = $user_id");
$data = $query->fetch_assoc();

// Simpan perubahan
if (isset($_POST['simpan'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET nama='$nama', password='$hashed' WHERE id=$user_id");
    } else {
        $conn->query("UPDATE users SET nama='$nama' WHERE id=$user_id");
    }

    $_SESSION['nama'] = $nama;
    echo "<script>alert('Akun berhasil diperbarui!'); window.location='index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Akun</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f9ff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 420px;
        }

        h2 {
            margin-bottom: 24px;
            font-size: 24px;
            color: #36a2eb;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #2c3e50;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 20px;
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

        .cancel-link {
            display: block;
            margin-top: 16px;
            text-align: center;
            text-decoration: none;
            color: #7f8c8d;
            font-size: 14px;
        }

        .cancel-link:hover {
            color: #2c3e50;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>✏️ Edit Akun</h2>
    <form method="POST">
        <label>Nama:</label>
        <input type="text" name="nama" value="<?= $data['nama']; ?>" required>

        <label>Password Baru (opsional):</label>
        <input type="password" name="password" placeholder="Biarkan kosong jika tidak diubah">

        <button type="submit" name="simpan">Simpan Perubahan</button>
        <a href="index.php" class="cancel-link">← Batal dan kembali</a>
    </form>
</div>

</body>
</html>
