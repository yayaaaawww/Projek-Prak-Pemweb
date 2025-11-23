<?php
// paket_user.php (contoh nama file)

// Pastikan session dimulai di baris pertama sebelum output apapun
session_start();

// include koneksi (sesuaikan path kalau filenya di folder lain)
require_once './config/koneksi.php';

// Ambil ID user yang login dengan pengecekan agar tidak undefined index
$id_user = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
$username = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Guest';

// Jika belum login, redirect ke login
if ($id_user <= 0) {
    header('Location: login.php');
    exit;
}

// Pastikan koneksi $conn tersedia
if (!isset($conn) || !$conn) {
    // debug: tampilkan pesan (atau redirect ke halaman error)
    die('Database connection error. Pastikan file koneksi benar dan variabel $conn tersedia.');
}

// Ambil paket yang dimiliki user (prepared statement lebih aman)
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

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Paket <?= htmlspecialchars($username) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-container { display:flex; flex-wrap:wrap; gap:30px; justify-content:center; margin-top:50px; }
        .custom-card { width:500px; box-shadow:0 4px 8px rgba(0,0,0,0.1); border-radius:12px; }
        .card-img-top { max-width:150px; height:auto; margin:10px; }
        .btn-pink { background:#ff4f9a; color:#fff; font-weight:600; border-radius:12px; padding:10px 20px; text-decoration:none; }
        .btn-pink:hover { background:#ff2f85; color:#fff; }
    </style>
</head>
<body>
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

                // halaman tujuan sesuai paket
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// tutup statement
$stmt->close();
?>
