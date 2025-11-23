<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$q = mysqli_query($conn, "
    SELECT id_paket 
    FROM pembayaran 
    WHERE id_user = '$user_id'
    ORDER BY id_bayar DESC
    LIMIT 1
");

$d = mysqli_fetch_assoc($q);

if (!$d || $d['id_paket'] != 1) {
    echo "<script>alert('Akses ditolak! Kamu tidak memiliki Paket 1.'); window.location='../landingpage.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Paket 1 — Web Development</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: #ffe4f0;
        font-family: Arial, sans-serif;
    }
    .card {
        border-radius: 15px;
        border: 2px solid #ff8ac2;
        transition: 0.2s;
    }
    .card:hover {
        transform: scale(1.03);
        border-color: #ff3e9d;
    }
    h1 {
        color: #ff3e9d;
        text-align: center;
        margin-top: 30px;
        font-weight: bold;
    }
</style>
</head>

<body>
<div class="container mt-4">

    <h1>Paket 1: Web Development Mastery</h1>

    <div class="row mt-4">


        <div class="col-md-4 mb-3">
            <a href="webdasar.php" style="text-decoration:none; color:black;">
                <div class="card p-3">
                    <h4>Kelas 1</h4>
                    <p>Web Dasar (Frontend Foundation)</p>
                </div>
            </a>
        </div>

        <div class="col-md-4 mb-3">
            <a href="backend.php" style="text-decoration:none; color:black;">
                <div class="card p-3">
                    <h4>Kelas 2</h4>
                    <p>Backend & Database</p>
                </div>
            </a>
        </div>

        <div class="col-md-4 mb-3">
            <a href="fullstack.php" style="text-decoration:none; color:black;">
                <div class="card p-3">
                    <h4>Kelas 3</h4>
                    <p>Fullstack Project</p>
                </div>
            </a>
        </div>

    </div>

</div>

</body>
</html>
