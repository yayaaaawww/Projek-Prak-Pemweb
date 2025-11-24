<?php
session_start();
include "../config/connection.php";

// Cek apakah user sudah login - PERBAIKI dari id_user ke user_id
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id_paket_required = 1;

$stmt = mysqli_prepare($conn, "
    SELECT id_paket 
    FROM pembayaran 
    WHERE id_user = ? AND id_paket = ?
    LIMIT 1
");

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $id_paket_required);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $d = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    // Jika tidak ada data atau paket tidak sesuai
    if (!$d) {
        echo "<script>alert('Akses ditolak! Kamu belum membeli Paket 1.'); window.location='../pembayaran.php?id_paket=1';</script>";
        exit();
    }
} else {
    die("Error: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Paket 1 — Web Development</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: linear-gradient(135deg, #ffe4f0 0%, #ffc4e1 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }
    .main-title {
        color: #d81b60;
        text-align: center;
        margin: 40px 0 20px 0;
        font-weight: bold;
        font-size: 2.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }
    .subtitle {
        text-align: center;
        color: #ec407a;
        font-size: 1.2rem;
        margin-bottom: 40px;
    }
    .card {
        border-radius: 20px;
        border: 3px solid #f06292;
        background: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        height: 100%;
    }
    .card:hover {
        transform: translateY(-10px);
        border-color: #d81b60;
        box-shadow: 0 12px 24px rgba(216,27,96,0.3);
    }
    .card-body {
        padding: 25px;
    }
    .card-title {
        color: #d81b60;
        font-weight: bold;
        font-size: 1.8rem;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .card-text {
        color: #424242;
        font-size: 1.1rem;
        line-height: 1.6;
    }
    .icon {
        font-size: 2rem;
    }
    a {
        text-decoration: none;
        color: inherit;
    }
    .back-btn {
        margin: 20px 0;
    }
    .btn-back {
        background: #f06292;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: bold;
        transition: all 0.3s;
    }
    .btn-back:hover {
        background: #d81b60;
        transform: scale(1.05);
        color: white;
    }
    .info-card {
        border-color: #f48fb1 !important;
        margin-top: 30px;
    }
</style>
</head>

<body>
<div class="container mt-4">
    <div class="back-btn">
        <a href="../landingpage.php" class="btn btn-back">← Kembali ke Dashboard</a>
    </div>

    <h1 class="main-title">💻 PAKET 1: WEB DEVELOPMENT MASTERY</h1>
    <p class="subtitle">Kuasai pembuatan website dari nol hingga menjadi Full Stack Developer profesional</p>

    <div class="row mt-5">
        <!-- Kelas 1 -->
        <div class="col-md-4 mb-4">
            <a href="webdasar.php">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <span class="icon">🎨</span>
                            Kelas 1
                        </h4>
                        <p class="card-text"><strong>Web Dasar (Frontend Foundation)</strong></p>
                        <p class="card-text" style="font-size: 0.95rem; color: #666;">
                            Pelajari HTML, CSS, dan JavaScript untuk membangun tampilan website yang menarik dan interaktif
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Kelas 2 -->
        <div class="col-md-4 mb-4">
            <a href="backend.php">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <span class="icon">⚙️</span>
                            Kelas 2
                        </h4>
                        <p class="card-text"><strong>Backend & Database</strong></p>
                        <p class="card-text" style="font-size: 0.95rem; color: #666;">
                            Kuasai PHP, MySQL, dan manajemen database untuk membuat website yang dinamis dan powerful
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Kelas 3 -->
        <div class="col-md-4 mb-4">
            <a href="fullstack.php">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <span class="icon">🚀</span>
                            Kelas 3
                        </h4>
                        <p class="card-text"><strong>Fullstack Project</strong></p>
                        <p class="card-text" style="font-size: 0.95rem; color: #666;">
                            Gabungkan semua skill untuk membuat aplikasi web lengkap dari frontend hingga backend
                        </p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Info Section -->
    <div class="row mt-4 mb-5">
        <div class="col-12">
            <div class="card info-card">
                <div class="card-body">
                    <h5 style="color: #d81b60; font-weight: bold;">💡 Yang Akan Kamu Pelajari:</h5>
                    <ul style="color: #424242; line-height: 2;">
                        <li><strong>Frontend Development</strong> - HTML5, CSS3, JavaScript, dan responsive design</li>
                        <li><strong>Backend Development</strong> - PHP, MySQL, dan REST API</li>
                        <li><strong>Full Stack Skills</strong> - Integrasi frontend dan backend</li>
                        <li><strong>Real Projects</strong> - E-commerce, blog system, dan portfolio website</li>
                        <li><strong>Best Practices</strong> - Security, optimization, dan deployment</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>