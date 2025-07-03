<?php
session_start();
include '../config/db.php';

/
error_reporting(E_ALL);
ini_set('display_errors', 1);

$alert = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE nama = ?");
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $result = $stmt->get_result();
    $akun = $result->fetch_assoc();

    if ($akun) {
        if (password_verify($password, $akun['password'])) {
            $_SESSION['user_id'] = $akun['id'];
            $_SESSION['nama'] = $akun['nama'];
            $_SESSION['role'] = $akun['role'];
            header("Location: ../dashboard/index.php");
            exit;
        } else {
            $alert = "⚠️ Password salah!";
        }
    } else {
        $alert = "⚠️ Akun tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #eef3fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            display: flex;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 800px;
            width: 100%;
        }

        .logo-side {
            background:#aec2e9;
            color: white;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .logo-side img {
            max-width: 100%;
            max-height: 300px;
        }

        .login-container {
            flex: 1;
            padding: 40px;
            text-align: center;
        }

        h2 {
            color: #2a5dba;
            margin-bottom: 24px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            background-color: #2a5dba;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #1f4aa5;
        }

        a {
            display: block;
            margin-top: 16px;
            color: #2a5dba;
            font-size: 14px;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .alert {
            background: #ffe5e5;
            color: #d8000c;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .logo-side {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo-side">
        <!-- Ganti src dengan gambar/logomu -->
        <img src="../assets/foto.png" alt="Logo Aplikasi">
    </div>
    <div class="login-container">
        <h2>Login</h2>

        <?php if (!empty($alert)): ?>
            <div class="alert"><?= $alert ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="nama" required placeholder="Nama">
            <input type="password" name="password" required placeholder="Password">
            <button type="submit">Login</button>
        </form>
        <a href="register.php">Belum punya akun? Daftar</a>
    </div>
</div>

</body>
</html>

