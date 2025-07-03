<?php
include '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$kategori = $conn->query("SELECT * FROM kategori_keuangan WHERE user_id = $user_id");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kategori_id = $_POST['kategori_id'];
    $nominal = $_POST['nominal'];
    $barang = $_POST['barang'];
    $tempat = $_POST['tempat'];
    $tanggal = $_POST['tanggal'];

    // Ambil saldo kategori
    $saldo_result = $conn->query("SELECT saldo FROM kategori_keuangan WHERE id = $kategori_id AND user_id = $user_id");
    $saldo_data = $saldo_result->fetch_assoc();
    $saldo = $saldo_data['saldo'];

    $new_saldo = $saldo - $nominal;

    // Insert pengeluaran
    $stmt = $conn->prepare("INSERT INTO pengeluaran (user_id, kategori_id, nominal, barang, tempat, tanggal) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iidsss", $user_id, $kategori_id, $nominal, $barang, $tempat, $tanggal);

    if ($stmt->execute()) {
        // Update saldo kategori
        $conn->query("UPDATE kategori_keuangan SET saldo = $new_saldo WHERE id = $kategori_id");

        // Cek saldo negatif
        if ($new_saldo < 0) {
            echo "<script>alert('⚠️ Saldo kategori ini menjadi negatif! Harap kendalikan pengeluaran.');</script>";
        }

        header("Location: index.php");
    } else {
        echo "Gagal menyimpan pengeluaran.";
    }
}
?>

<h2>Tambah Pengeluaran</h2>
<form method="post">
    <label>Kategori:</label><br>
    <select name="kategori_id" required>
        <?php while($row = $kategori->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama_kategori']) ?> (Rp<?= number_format($row['saldo'], 0, ',', '.') ?>)</option>
        <?php endwhile; ?>
    </select><br>

    <label>Nominal:</label><br>
    <input type="number" step="0.01" name="nominal" required><br>

    <label>Barang:</label><br>
    <input type="text" name="barang" required><br>

    <label>Tempat:</label><br>
    <input type="text" name="tempat" required><br>

    <label>Tanggal:</label><br>
    <input type="date" name="tanggal" required><br>

    <button type="submit">Simpan Pengeluaran</button>
</form>
<a href="index.php">Kembali ke daftar pengeluaran</a>
