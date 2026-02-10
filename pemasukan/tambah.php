<?php
include '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ambil kategori pengguna
$kategori = $conn->query("SELECT * FROM kategori_keuangan WHERE user_id = $user_id");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kategori_id = $_POST['kategori_id'];
    $nominal = $_POST['nominal'];
    $deskripsi = $_POST['deskripsi'];

    $stmt = $conn->prepare("INSERT INTO pemasukan (user_id, kategori_id, nominal, deskripsi) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iids", $user_id, $kategori_id, $nominal, $deskripsi);
    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Gagal menambahkan pemasukan";
    }
}
?>

<h2>Tambah Pemasukan</h2>
<form method="post">
    <label>Kategori:</label><br>
    <select name="kategori_id" required>
        <?php while($row = $kategori->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama_kategori']) ?></option>
        <?php endwhile; ?>
    </select><br>

    <label>Nominal:</label><br>
    <input type="number" step="0.01" name="nominal" required><br>

    <label>Deskripsi (opsional):</label><br>
    <textarea name="deskripsi"></textarea><br>

    <button type="submit">Simpan</button>
</form>
<a href="index.php">Kembali ke daftar pemasukan</a>
