<?php
session_start();
include "../config/connection.php";

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id_paket_required = 3; // Paket 3: AI & Machine Learning

// Cek apakah user sudah membeli paket ini
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
        echo "<script>alert('Akses ditolak! Kamu belum membeli Paket 3.'); window.location='../pembayaran.php?id_paket=3';</script>";
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
<title>Paket 3 — AI & Machine Learning Expert</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }
    .main-title {
        color: #4a148c;
        text-align: center;
        margin: 40px 0 20px 0;
        font-weight: bold;
        font-size: 2.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }
    .subtitle {
        text-align: center;
        color: #6a1b9a;
        font-size: 1.2rem;
        margin-bottom: 40px;
    }
    .card {
        border-radius: 20px;
        border: 3px solid #7b1fa2;
        background: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        height: 100%;
    }
    .card:hover {
        transform: translateY(-10px);
        border-color: #4a148c;
        box-shadow: 0 12px 24px rgba(74,20,140,0.3);
    }
    .card-body {
        padding: 25px;
    }
    .card-title {
        color: #4a148c;
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
        background: #7b1fa2;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: bold;
        transition: all 0.3s;
    }
    .btn-back:hover {
        background: #4a148c;
        transform: scale(1.05);
        color: white;
    }
    .info-card {
        border-color: #ab47bc !important;
        margin-top: 30px;
    }
</style>
</head>

<body>
<div class="container mt-4">
    <div class="back-btn">
        <a href="../landingpage.php" class="btn btn-back">← Kembali ke Dashboard</a>
    </div>

    <h1 class="main-title">🧠 PAKET 3: AI & MACHINE LEARNING EXPERT</h1>
    <p class="subtitle">Kuasai Machine Learning, Deep Learning, dan deployment AI untuk menjadi AI Engineer profesional</p>

    <div class="row mt-5">
        <div class="col-md-4 mb-4">
            <a href="introml.php">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <span class="icon">🤖</span>
                            Kelas 1
                        </h4>
                        <p class="card-text"><strong>Intro to Machine Learning</strong></p>
                        <p class="card-text" style="font-size: 0.95rem; color: #666;">
                            Pelajari supervised vs unsupervised learning, feature engineering, regresi & klasifikasi, serta evaluasi model
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 mb-4">
            <a href="deeplearning.php">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <span class="icon">🧬</span>
                            Kelas 2
                        </h4>
                        <p class="card-text"><strong>Deep Learning</strong></p>
                        <p class="card-text" style="font-size: 0.95rem; color: #666;">
                            Kuasai Neural Network, CNN untuk image recognition, RNN untuk NLP, dan sentiment analysis
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 mb-4">
            <a href="aiproject.php">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <span class="icon">🚀</span>
                            Kelas 3
                        </h4>
                        <p class="card-text"><strong>AI Projects & Deployment</strong></p>
                        <p class="card-text" style="font-size: 0.95rem; color: #666;">
                            Integrasikan model ke web app dengan Flask/Streamlit, deploy ke cloud, dan buat chatbot atau image recognition app
                        </p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row mt-4 mb-5">
        <div class="col-12">
            <div class="card info-card">
                <div class="card-body">
                    <h5 style="color: #4a148c; font-weight: bold;">💡 Yang Akan Kamu Pelajari:</h5>
                    <ul style="color: #424242; line-height: 2;">
                        <li><strong>Machine Learning Fundamentals</strong> - Supervised, unsupervised, dan model evaluation</li>
                        <li><strong>Deep Learning & Neural Networks</strong> - CNN, RNN, dan arsitektur modern</li>
                        <li><strong>Computer Vision & NLP</strong> - Image classification dan sentiment analysis</li>
                        <li><strong>Model Deployment</strong> - Flask, Streamlit, dan cloud deployment (Render, HuggingFace, Vercel)</li>
                        <li><strong>Real AI Projects</strong> - Prediksi harga rumah, chatbot, dan image recognition web app</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>