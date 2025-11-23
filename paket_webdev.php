<?php
session_start();
include "./config/koneksi.php";

// CEK LOGIN
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$id_paket = $_GET['id_paket'] ?? 0;
$id_paket = (int) $id_paket;

// AMBIL DATA PAKET
$qPaket = "SELECT * FROM paket WHERE id_paket = $id_paket";
$rPaket = mysqli_query($conn, $qPaket);
$paket = mysqli_fetch_assoc($rPaket);

if (!$paket) {
    die("Paket tidak ditemukan");
}

// AMBIL LIST KELAS DALAM PAKET INI
$qKelas = "SELECT * FROM kelas WHERE id_paket = $id_paket";
$rKelas = mysqli_query($conn, $qKelas);

$kelas_list = [];
while ($row = mysqli_fetch_assoc($rKelas)) {
    $kelas_list[] = $row;
}

// NOTE:
// Redirect ke materi nanti dilakukan di detail_kelas.php
// Tidak di halaman landingpage ini
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($paket['nama_paket']); ?> | CodeBloom</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to bottom, #fff, #ffe0eb);
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* NAVBAR */
    .navbar {
      background-color: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.07);
      padding: 16px;
      z-index: 1000;
    }
    .navbar-brand {
      font-weight: 700;
      font-size: 22px;
      color: #000;
    }
    .btn-register {
      background-color: #ff8caf;
      border: none;
      font-size: 13px;
      padding: 7px 18px;
      border-radius: 8px;
      font-weight: 600;
      color: white;
    }

    /* SIDEBAR */
    .sidebar {
      position: fixed;
      top: 0;
      left: -260px;
      height: 100%;
      width: 260px;
      background-color: #ffe6ec;
      padding-top: 80px;
      transition: all 0.3s ease;
      box-shadow: 2px 0 8px rgba(0,0,0,0.1);
      z-index: 999;
    }
    .sidebar.active {
      left: 0;
    }
    .sidebar a {
      display: block;
      padding: 8px 25px;
      color: #000;
      text-decoration: none;
      font-size: 14px;
    }
    .sidebar a:hover {
      background-color: #ffccd9;
      border-radius: 10px;
    }
    .sidebar .section-title {
      font-weight: 600;
      margin-top: 15px;
      padding: 0 25px;
    }

    #menu-toggle {
      background: none;
      border: none;
      font-size: 22px;
      cursor: pointer;
      margin-right: 10px;
    }

    /* CONTENT */
    .content {
      margin-top: 100px;
      padding: 20px;
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
      padding-bottom: 50px;
    }
    .content h1 {
      font-weight: 700;
      text-align: center;
    }
    .subtitle {
      text-align: center;
      font-weight: 500;
      font-size: 18px;
      margin-bottom: 25px;
    }

    .content img {
      width: 100%;
      border-radius: 10px;
      margin-bottom: 25px;
    }

    .content p {
      text-align: justify;
      color: #333;
      font-size: 14px;
      line-height: 1.6;
    }

    /* CARD KELAS */
    .kelas-card {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 20px;
      overflow: hidden;
      padding: 15px;
    }
    .kelas-card img {
      width: 120px;
      height: 90px;
      border-radius: 10px;
      object-fit: cover;
    }
    .kelas-text {
      flex: 1;
      padding-right: 15px;
    }
    .kelas-text h5 {
      margin-bottom: 6px;
      font-weight: 600;
    }
    .kelas-text p {
      font-size: 13px;
      margin-bottom: 8px;
    }

    .btn-pink {
      background-color: #ff8caf;
      border: none;
      font-size: 13px;
      font-weight: 600;
      border-radius: 8px;
      padding: 6px 15px;
      text-decoration: none;
      color: #000;
      display: inline-block;
    }
    .btn-pink:hover {
      background-color: #ff6c96;
      color: #fff;
    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
      <button id="menu-toggle">☰</button>
      <a class="navbar-brand" href="index.php">c🌸deBloom</a>

      <div class="collapse navbar-collapse justify-content-center">
        <ul class="navbar-nav">
          <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
          <li class="nav-item"><a href="#" class="nav-link">Package</a></li>
        </ul>
      </div>

      <?php if(isset($_SESSION['id_user'])): ?>
        <span style="margin-right: 15px; font-size: 14px;">Hi, <?= htmlspecialchars($_SESSION['nama']); ?></span>
        <a href="logout.php" class="btn-register">Logout</a>
      <?php else: ?>
        <a href="login.php" class="btn-register">Login</a>
      <?php endif; ?>
    </div>
  </nav>

  <!-- SIDEBAR -->
  <div class="sidebar" id="sidebar">
    <div class="section-title">Kelas Web Development</div>
    <?php foreach($kelas_list as $kelas): ?>
      <a href="detail_kelas.php?id=<?= $kelas['id_kelas']; ?>">
        <?= htmlspecialchars($kelas['nama_kelas']); ?>
      </a>
    <?php endforeach; ?>

    <div class="section-title">Paket Lainnya</div>
    <a href="paket_ai.php">AI & Machine Learning</a>
    <a href="paket_datascience.php">Data Science</a>
  </div>

  <!-- CONTENT -->
  <div class="content">
    <h1><?= htmlspecialchars($paket['nama_paket']); ?></h1>
    <div class="subtitle">Apa itu <?= htmlspecialchars($paket['nama_paket']); ?>?</div>

    <img src="https://cdn.pixabay.com/photo/2016/11/19/14/00/code-1839406_1280.jpg" alt="<?php echo htmlspecialchars($paket['nama_paket']); ?>">

    <p><strong>Mengenal lebih jauh paket <?= htmlspecialchars($paket['nama_paket']); ?></strong></p>
    <p><?= nl2br(htmlspecialchars($paket['deskripsi'])); ?></p>

    <?php foreach($kelas_list as $kelas): ?>
      <div class="kelas-card">
        <div class="kelas-text">
          <h5><?= htmlspecialchars($kelas['nama_kelas']); ?></h5>
          <p><?= htmlspecialchars($kelas['deskripsi']); ?></p>
          <a href="./materi/materi_paket3.php" class="btn-pink">Lihat selengkapnya</a>
        </div>

        <img src="https://cdn.pixabay.com/photo/2017/03/09/12/31/error-2129569_1280.jpg">
      </div>
    <?php endforeach; ?>

  </div>

  <script>
    const sidebar = document.getElementById("sidebar");
    const toggle = document.getElementById("menu-toggle");

    toggle.addEventListener("click", () => {
      sidebar.classList.toggle("active");
    });
  </script>

</body>
</html>
