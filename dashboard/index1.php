<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catat Duit - Landing Page</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins';
      background-color: white;
      color: #333;
    }

    header {
      background-color: #f5f5f5;
      padding: 15px 30px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .nav h3 {
      color: #007BFF;
      font-weight: bold;
    }

    .nav ul {
      list-style: none;
      display: flex;
      gap: 25px;
    }

    .nav ul li a {
      text-decoration: none;
      color: #333;
      font-weight: 500;
    }

    .hero {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background-color: #f0f8ff;
      padding: 60px 80px;
      gap: 20px;
    }

    .hero .text {
      flex: 2;
      max-width: 65%;
    }

    .hero .text h1 {
      font-size: 36px;
      margin-bottom: 10px;
    }

    .hero .text span {
      color: #007BFF;
    }

    .hero .text p {
      font-size: 16px;
      margin-bottom: 20px;
    }

    .hero .btn {
      padding: 10px 20px;
      background-color: #007BFF;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
    }

    .hero .img {
      flex: 1;
      display: flex;
      justify-content: flex-end;
    }

    .hero .img img {
      width: 100%;
      max-width: 300px;
      height: auto;
      object-fit: contain;
    }

    .features {
      padding: 60px 10%;
      text-align: center;
    }

    .features h2 {
      font-size: 28px;
      margin-bottom: 10px;
    }

    .features span {
      color: #007BFF;
    }

    .features p {
      max-width: 600px;
      margin: 0 auto 40px;
      color: #555;
    }

    .feature-card {
      display: flex;
      align-items: center;
      margin-bottom: 40px;
      text-align: left;
      gap: 30px;
      flex-wrap: wrap;
    }

    .feature-card img {
      width: 120px;
      max-width: 100%;
    }

    /* Tips Hemat */
    .tips {
      background-color: #f0f8ff;
      padding: 60px 10%;
      color: #000;
      text-align: center;
    }

    .tips h2 {
      font-size: 28px;
      margin-bottom: 40px;
      text-decoration: underline;
      font-weight: bold;
    }

    .tips-cards {
      display: flex;
      justify-content: center;
      gap: 40px;
      flex-wrap: wrap;
    }

    .tips-card {
      background-color: white;
      width: 250px;
      padding: 20px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
    }

    .tips-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .tips-card img {
      width: 100%;
      height: auto;
      border-radius: 6px;
    }

    .tips-card h4 {
      margin-top: 15px;
      font-size: 16px;
      font-weight: 600;
    }

    .tips-card p {
      font-size: 14px;
      color: #444;
      margin-top: 10px;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      padding-top: 60px;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.6);
    }

    .modal-content {
      background-color: #fff;
      margin: auto;
      padding: 30px;
      border-radius: 10px;
      width: 80%;
      max-width: 500px;
      animation: fadeIn 0.3s ease-in-out;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 24px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover {
      color: #000;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: scale(0.9);}
      to {opacity: 1; transform: scale(1);}
    }

    footer {
      background-color: #f5f5f5;
      text-align: center;
      padding: 20px;
      color: #666;
      font-size: 14px;
    }

    @media (max-width: 768px) {
      .hero, .feature-card, .tips-cards {
        flex-direction: column;
        text-align: center;
      }

      .tips-card {
        width: 100%;
        max-width: 300px;
      }

      .hero .text {
        max-width: 100%;
      }
    }
  </style>
</head>
<body>

  <header>
    <div class="nav">
      <h3>CATAT DUIT</h3>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#fitur">Features</a></li>
        <li><a href="#tips">Tips Hemat</a></li>
        <li><a href="../auth/login.php">Login</a></li>
        <li><a href="../auth/register.php">Signup</a></li>
      </ul>
    </div>
  </header>

  <section class="hero">
    <div class="text">
      <h1>Welcome to <span>Catat</span> Duit</h1>
      <p>CatatDuit adalah platform manajemen keuangan yang membantu kamu mencatat pemasukan dan pengeluaran dengan mudah, cepat, dan rapi.</p>
      <p>Di sini, kamu bisa mengatur arus kas harian, membuat laporan keuangan, dan memantau ke mana saja uangmu pergi — semua dalam satu tempat.</p>
      <p>Mulai sekarang, kelola uangmu lebih bijak dan terencana.</p>
      <p>Yuk, kendalikan keuanganmu bersama CatatDuit!</p>
      <a href="../auth/login.php" class="btn">Login Sekarang</a>
    </div>
    <div class="img">
      <img src="assets/img/hero.png" alt="Ilustrasi Hero">
    </div>
  </section>

  <section class="features" id="fitur">
    <h2>FEATURES <br><span>CATAT</span> DUIT</h2>
    <p>adalah aplikasi web pencatatan keuangan harian yang mempunyai fitur-fitur menarik untuk memonitoring keuangan harian anda. Direkomendasikan bagi para remaja yang kesulitan dalam melakukan pengelolaan keuangannya.</p>

    <div class="feature-card">
      <img src="assets/img/transaksi.png" alt="Transaksi">
      <div>
        <h3>Transaksi Harian</h3>
        <p>Kami memberikan fitur transaksi harian yang akan menampilkan data harian yang bisa mempermudah anda dalam mengelola keuangan prihadi, dan data keuangan anda akan tersimpan dengan aman di dalam aplikasi ini.</p>
      </div>
    </div>

    <div class="feature-card">
      <img src="assets/img/rekening.png" alt="Rekening">
      <div>
        <h3>Rekening Pribadi</h3>
        <p>Kami menyediakan ritur rekening pribadi yang dapat mempermudah anda dalam melakukan pengelolaan keuangan di dompet dan juga di rekening anda. Dengan fitur ini, pengelolaan uang anda di rekening menjadi lebih mudah dan terkelola dengan baik.</p>
      </div>
    </div>

    <div class="feature-card">
      <img src="assets/img/monitoring.png" alt="Monitoring">
      <div>
        <h3>Monitoring Keuangan</h3>
        <p>Monitoring keuangan tentunya sangat diperlukan untuk mengelola pengeluaran dan pemasukan kita, kami menyediakan dashboard yang berisi beberapa fitur, seperti saldo, total uang yang masuk dan keluar, dan rekening,</p>
      </div>
    </div>
  </section>

   <section class="tips" id="tips">
    <h2>TIPS HEMAT</h2>
    <div class="tips-cards">

      <div class="tips-card" onclick="openModal(0)">
        <img src="assets/img/tips1.png" alt="Mencatat Keuangan">
        <h4>MENCATAT KEUANGAN</h4>
        <p>Catat setiap pemasukan dan pengeluaran agar kamu tahu ke mana uangmu pergi.</p>
      </div>

      <div class="tips-card" onclick="openModal(1)">
        <img src="assets/img/tips2.png" alt="Prioritas Kebutuhan">
        <h4>PRIORITAS KEBUTUHAN</h4>
        <p>Utamakan kebutuhan daripada keinginan biar dompet tetap aman sampai akhir bulan.</p>
      </div>

      <div class="tips-card" onclick="openModal(2)">
        <img src="assets/img/tips3.png" alt="Menabung">
        <h4>MENABUNG</h4>
        <p>Sisihkan sebagian uang untuk ditabung agar siap menghadapi keadaan darurat.</p>
      </div>

    </div>
  </section>

  <!-- MODAL -->
  <div id="modal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h3 id="modal-title">Judul</h3>
      <p id="modal-desc">Deskripsi panjang tips akan tampil di sini.</p>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 Catat Duit. All rights reserved.</p>
  </footer>

  <script>
    const tips = [
      {
        title: 'MENCATAT KEUANGAN',
        desc: 'Dengan mencatat setiap pemasukan dan pengeluaran, kamu bisa tahu ke mana uangmu pergi dan bisa merencanakan keuangan lebih baik. Ini adalah langkah pertama agar kamu tidak kebablasan saat belanja atau jajan.'
      },
      {
        title: 'PRIORITAS KEBUTUHAN',
        desc: 'Kebutuhan seperti makan, transportasi, dan kewajiban harus lebih diutamakan dibanding keinginan seperti nongki atau beli barang lucu. Belajar memilah ini akan menyelamatkan isi dompetmu.'
      },
      {
        title: 'MENABUNG',
        desc: 'Menabung itu wajib, bukan sisa. Sisihkan minimal 10-20% dari uang bulananmu, lalu anggap saja itu "nggak ada". Bisa untuk dana darurat, beli gadget, atau traveling.'
      }
    ];

    function openModal(index) {
      document.getElementById('modal-title').innerText = tips[index].title;
      document.getElementById('modal-desc').innerText = tips[index].desc;
      document.getElementById('modal').style.display = 'block';
    }

    function closeModal() {
      document.getElementById('modal').style.display = 'none';
    }

    window.onclick = function(event) {
      if (event.target == document.getElementById('modal')) {
        closeModal();
      }
    }
  </script>

</body>
</html>
