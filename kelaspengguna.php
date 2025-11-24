<?php
// Pastikan session dimulai di baris pertama sebelum output apapun
session_start();

// Perbaikan: include koneksi (menggunakan connection.php sesuai struktur file Anda)
require_once './config/connection.php'; 

// Ambil ID user yang login dengan pengecekan
$id_user = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
$username = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Guest';

// Jika belum login, redirect ke login (Wajib Login)
if ($id_user <= 0) {
    header('Location: login.php');
    exit;
}

// Pastikan koneksi $conn tersedia
if (!isset($conn) || !$conn) {
    die('Database connection error. Pastikan file koneksi benar dan variabel $conn tersedia.');
}

// Ambil paket yang dimiliki user (prepared statement LEBIH AMAN)
$sql = "SELECT p.id_paket, p.nama_paket, p.deskripsi
        FROM paket p
        JOIN pembayaran pb ON p.id_paket = pb.id_paket
        WHERE pb.id_user = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Prepare failed: ' . $conn->error);
}

$stmt->bind_param('i', $id_user);
$stmt->execute();
$result = $stmt->get_result();

$query = "SELECT nama, username FROM user WHERE id_user = ?";
$stmtp = $conn->prepare($query);
$stmtp->bind_param('i', $id_user);
$stmtp->execute();
$resultp = $stmtp->get_result();

if ($data = $resultp->fetch_assoc()) {
    $namalengkap = $data['nama'];
    $nama = $data['username'];
} else {
    header("Location: landingpage.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Paket <?= htmlspecialchars($username) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        .rounded-image {
            width: 150px; 
            height: 150px; 
            object-fit: cover; 
            border-radius: 50%; 
        }

        .login {
        text-decoration: none;
        color: black;
        margin-left: 400px;
        }

        .navbar {
        background-color: white;
        }

        body {
        font-family: "Poppins", sans-serif;
        background-image: linear-gradient(to bottom, #ffffff 0%,   #fee0e0ff 100%);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        }

        .menu-samping {
            margin-left: 10px;
        }

        .menu-samping a {
            text-decoration: none; 
            color: #333;           
            line-height: 3;   
        }

        .kanan{
            margin-top: 50px;
            margin-left: 100px;
            margin-right: 100px;
            margin-bottom: 50px;
        }

        .card-container { 
            display:flex; 
            flex-wrap:wrap; 
            gap:30px; 
            justify-content:center; 
            margin-top:50px; }

        .custom-card { 
            width:500px; 
            box-shadow:0 4px 8px rgba(0,0,0,0.1); 
            border-radius:12px; }

        .card-img-top { 
            max-width:150px; 
            height:auto; 
            margin:10px; }

        .btn-pink { 
            background:#ff4f9a; 
            color:#fff; 
            font-weight:600; 
            border-radius:12px; 
            padding:10px 20px; 
            text-decoration:none; }

        .btn-pink:hover { 
            background:#ff2f85; 
            color:#fff; }
    </style>
</head>
<body>
<div class="navbar">
    <nav class="navbar navbar-expand-lg bg-body">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" style="font-weight : bold; margin-left : 200px;">c🌸deBloom</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="landingpage.php">Home</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="halamanpaket.php">Package</a>
                </li>
                <li class="nav-item">
                </li>
                <li class="nav-item d-flex align-items-center">
                    <?php if ($username): ?>
                        <a class="nav-link" href="pembayaran.php">Buy</a>
                        <a class="login nav-link" href="profile.php">
                            <i class="bi bi-person"></i> <?= htmlspecialchars($username) ?>
                        </a>
                        <a href="./proses/logout.php" class="ms-3">
                            <button type="button" class="btn btn-outline-dark">Logout</button>
                        </a>
                    <?php else: ?>
                        <a class="login nav-link" href="register.php">
                            <i class="bi bi-person"></i> Sign In
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
        </div>
    </nav>
</div>

    <div class="container-fluid">
    <div class="row">
        <div class="col-md-3" style="background-color: #f8d7da; padding: 15px; height: 626px;">
            <div >
                <center>
                <img src="./gambar/profile.jpg" alt="Gambar Bulat" class="rounded-image">
                <p style="margin-top: 24px; font-weight: bold;"><?= $namalengkap ?></p>
                <p style="color: grey;">@<?= $nama ?></p>
                <hr>
                </center>
                <div class="menu-samping">
                    <a href="profile.php">Profil</a><br>
                    <a href="kelaspengguna.php">Paket</a><br>
                </div>
            </div>
        </div>
        <div class="col-md-9" style="background-image: linear-gradient(to bottom, #ffffff 0%,   #fee0e0ff 100%); padding: 15px;">
            <div class="kanan">
                <div class="container text-center">
                    <h3 style="margin-top:50px; font-weight:bold;">Paket yang Dimiliki Oleh <?= htmlspecialchars($username) ?></h3>
                
                    <div class="card-container">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): 
                                $idPaket = (int)$row['id_paket'];
                                $namapaket = $row['nama_paket'];
                
                                $gambar_path = './gambar/default_paket.png';
                                if ($idPaket === 1) $gambar_path = './gambar/paketWeb.png';
                                if ($idPaket === 2) $gambar_path = './gambar/paketDS.png';
                                if ($idPaket === 3) $gambar_path = './gambar/paketAI.png';
                
                                // halaman tujuan materi
                                // Perbaikan: Link materi harus unik per paket (misalnya, materi_paket1.php, materi_paket2.php)
                                $halaman_lanjut = '#';
                                if ($idPaket === 1) $halaman_lanjut = './materi/materi_paket1.php';
                                if ($idPaket === 2) $halaman_lanjut = './materi/materi_paket2.php';
                                if ($idPaket === 3) $halaman_lanjut = './materi/materi_paket3.php';
                            ?>
                            <div class="card custom-card">
                                <div class="row g-0 align-items-center">
                                    <div class="col-4 text-center">
                                        <img src="<?= htmlspecialchars($gambar_path) ?>" alt="gambar paket" class="card-img-top">
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body text-start">
                                            <h5 class="card-title fw-bold"><?= htmlspecialchars($namapaket) ?></h5>
                                            <a href="<?= htmlspecialchars($halaman_lanjut) ?>" class="btn-pink">Lanjutkan</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="alert alert-info">Kamu belum membeli paket apapun. <a href="halamanpaket.php">Lihat paket</a></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// tutup statement
$stmt->close();
?>
