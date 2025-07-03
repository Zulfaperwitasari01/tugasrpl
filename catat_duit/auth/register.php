<?php
session_start();
include '../config/db.php';

$alert = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $cek = $conn->prepare("SELECT * FROM users WHERE nama = ?");
    $cek->bind_param("s", $nama);
    $cek->execute();
    $result = $cek->get_result();

    if ($result->num_rows > 0) {
        $alert = "⚠️ Nama sudah dipakai!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (nama, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $nama, $password);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $alert = "❌ Gagal daftar!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #eef3fc;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            display: flex;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 90%;
            max-width: 900px;
        }

        .logo-side {
            background-color:hsl(219, 97.80%, 82.00%);
            color: white;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
        }

        .logo-side img {
            max-width: 80%;
            height: auto;
        }

        .form-side {
            padding: 40px;
            flex: 1;
        }

        h2 {
            color: #2a5dba;
            margin-bottom: 20px;
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
                padding: 20px;
            }

            .form-side {
                padding: 20px;
            }

            .logo-side img {
                max-width: 50%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo-side">
        <img src="../assets/foto.png" alt="Logo Aplikasi">
    </div>
    <div class="form-side">
        <h2>Registrasi</h2>

        <?php if (!empty($alert)): ?>
            <div class="alert"><?= $alert ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="nama" required placeholder="Nama">
            <input type="password" name="password" required placeholder="Password">
            <button type="submit">Daftar</button>
        </form>

        <a href="login.php">Sudah punya akun? Login</a>
    </div>
</div>

</body>
</html>
