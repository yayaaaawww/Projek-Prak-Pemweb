<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id_user'];

$q = mysqli_query($conn, "
    SELECT id_paket 
    FROM pembayaran 
    WHERE id_user = '$user_id'
    ORDER BY id_bayar DESC
    LIMIT 1
");

$d = mysqli_fetch_assoc($q);

if (!$d || $d['id_paket'] != 2) {
    echo "<script>alert('Akses ditolak! Kamu tidak memiliki Paket 2.'); window.location='landingpage.php';</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Paket 2 — Data Science Pro</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }
    .main-title {
        color: #0d47a1;
        text-align: center;
        margin: 40px 0 20px 0;
        font-weight: bold;
        font-size: 2.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }
    .subtitle {
        text-align: center;
        color: #1565c0;
        font-size: 1.2rem;
        margin-bottom: 40px;
    }
    .card {
        border-radius: 20px;
        border: 3px solid #1976d2;
        background: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        height: 100%;
    }
    .card:hover {
        transform: translateY(-10px);
        border-color: #0d47a1;
        box-shadow: 0 12px 24px rgba(13,71,161,0.3);
    }
    .card-body {
        padding: 25px;
    }
    .card-title {
        color: #0d47a1;
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
        background: #1976d2;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: bold;
        transition: all 0.3s;
    }
    .btn-back:hover {
        background: #0d47a1;
        transform: scale(1.05);
        color: white;
    }
    .info-card {
        border-color: #42a5f5 !important;
        margin-top: 30px;
    }
</style>
</head>

<body>
<div class="container mt-4">
    <div class="back-btn">
        <a href="../landingpage.php" class="btn btn-back">← Kembali ke Dashboard</a>
    </div>

    <h1 class="main-title">📊 PAKET 2: DATA SCIENCE PRO</h1>
    <p class="subtitle">Kuasai analisis data, statistik, dan visualisasi untuk menjadi Data Scientist profesional</p>

    <div class="row mt-5">
        <!-- Kelas 1 -->
        <div class="col-md-4 mb-4">
            <a href="datahandling.php">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <span class="icon">📈</span>
                            Kelas 1
                        </h4>
                        <p class="card-text"><strong>Data Handling & Python Basics</strong></p>
                        <p class="card-text" style="font-size: 0.95rem; color: #666;">
                            Pelajari fundamental Python, manipulasi data dengan Pandas & NumPy, serta analisis data penjualan
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Kelas 2 -->
        <div class="col-md-4 mb-4">
            <a href="statistik.php">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <span class="icon">🧮</span>
                            Kelas 2
                        </h4>
                        <p class="card-text"><strong>Statistik & SQL for Data</strong></p>
                        <p class="card-text" style="font-size: 0.95rem; color: #666;">
                            Kuasai statistik deskriptif & inferensial, SQL untuk analisis data, dan exploratory data analysis
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Kelas 3 -->
        <div class="col-md-4 mb-4">
            <a href="dataproject.php">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <span class="icon">🔍</span>
                            Kelas 3
                        </h4>
                        <p class="card-text"><strong>Data Project & Dashboard</strong></p>
                        <p class="card-text" style="font-size: 0.95rem; color: #666;">
                            Buat visualisasi data profesional, dashboard interaktif dengan Streamlit, dan storytelling data
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
                    <h5 style="color: #0d47a1; font-weight: bold;">💡 Yang Akan Kamu Pelajari:</h5>
                    <ul style="color: #424242; line-height: 2;">
                        <li><strong>Python untuk Data Science</strong> - Pandas, NumPy, dan manipulasi data</li>
                        <li><strong>Statistik & SQL</strong> - Analisis kuantitatif dan query database</li>
                        <li><strong>Visualisasi Data</strong> - Matplotlib, Seaborn, dan Streamlit dashboard</li>
                        <li><strong>Real Projects</strong> - Analisis penjualan, HR data, dan e-commerce</li>
                        <li><strong>Data Storytelling</strong> - Presentasi hasil analisis secara profesional</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>