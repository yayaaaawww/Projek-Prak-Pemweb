<?php
    require_once'./config/connection.php';

    $query = "SELECT id_paket FROM paket";
    $stmt = $connection->query($query);
    $result = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>c🌸deBloom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        .Login {
        margin-left: 700px;
        }

        .navbar {
        background-color: white;
        position: sticky;
        top: 0;
        z-index: 100;
        }

        body {
        font-family: "Poppins", sans-serif;
        background-image: linear-gradient(to bottom, #ffffff 0%,   #fee0e0ff 100%);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        }

        .t_paket{
            background-color : #FEBCC2; 
            font-weight : bold;
        }
    </style>
</head>
<body>
    <div class="navbar">
    <nav class="navbar navbar-expand-lg bg-body">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" style="font-weight : bold; margin-left : 30px;">c🌸deBloom</a>
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
                <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li>
                <li>
                    <div class = "Login">
                    <button type="button" class="btn btn" style="background-color : #FEBCC2; font-weight : bold;">Login</button>
                    </div>
                </li>
            </ul>
        </div>
        </div>
    </nav>
    </div>  

    <!-- paket 1 -->
    <center>

    <div class="card" style="width: 900px; margin-top: 50px;">
    <div class="card-body">
        <h5 class="card-title">Paket Web Development Mastery</h5> <hr>
        <div class="container text-center">
        <div class="row align-items-start">
            <div class="col">
            <p class="card-text" style="text-align: left; font-size: 13px;">
                Fokus: Membangun website dari nol sampai jadi aplikasi web profesional. <br>
                Level: Pemula – Menengah <br>
                Durasi: ± 3 bulan <br>
                Bahasa yang digunakan: HTML, CSS, JavaScript, Python (Flask/Django), SQL <br> <br>
                Tujuan Pembelajaran:
                <ul style="text-align: left; font-size: 13px;">
                    <li>Membuat website statis & dinamis dari awal</li>
                    <li>Menghubungkan website dengan database</li>
                    <li>Membangun aplikasi web lengkap (frontend + backend)</li>
                    <li>Mendeploy website agar bisa diakses publik</li>
                </ul>
                <span style="font-weight: bold; font-size: 13px; text-align: left; display: block;">
                    Total Paket Web Dev: Rp 1.700.000 <br>
                    Sertifikat: 3 sertifikat kelas + 1 sertifikat paket
                </span>
            </p>
            </div>
            <div class="col">
            <img src="sertif.png" alt="paket web" style="width: 250px;">
            </div>
        </div>
        </div>
        <a href="nama_file_halaman_paket?id=<?= 1 ?>" class="t_paket btn">lihat paket lengkap</a>
    </div>
    </div>

    <div class="card" style="width: 900px; margin-top: 50px;">
    <div class="card-body">
        <h5 class="card-title">Paket Data Science Pro</h5> <hr>
        <div class="container text-center">
        <div class="row align-items-start">
            <div class="col">
            <p class="card-text" style="text-align: left; font-size: 13px;">
                Fokus: Analisis data, visualisasi, dan pembuatan insight dari data nyata. <br>
                Level: Pemula – Menengah <br>
                Durasi: ± 3–4 bulan <br>
                Bahasa yang digunakan: Python, SQL, R <br> <br>
                Tujuan Pembelajaran :
                <ul style="text-align: left; font-size: 13px;">
                    <li>Mengolah data mentah menjadi dataset yang siap analisis</li>
                    <li>Menganalisis data menggunakan Python dan R</li>
                    <li>Membuat visualisasi dan dashboard interaktif</li>
                    <li>Memahami dasar statistik dan penerapannya</li>
                </ul>
                <span style="font-weight: bold; font-size: 13px; text-align: left; display: block;">
                    Total Paket Data Science : Rp 2.100.000 <br>
                    Sertifikat: 3 sertifikat kelas + 1 sertifikat paket
                </span>
            </p>
        </div>
        <div class="col">
            <img src="sertif.png" alt="paket web" style="width: 250px;">
        </div>
    </div>
</div>
<a href="nama_file_halaman_paket?id=<?= 2 ?>" class="t_paket btn">Go somewhere</a>
</div>
</div>

<div class="card" style="width: 900px; margin-top: 50px; margin-bottom: 50px;">
    <div class="card-body">
        <h5 class="card-title">Paket AI & Machine Learning</h5> <hr>
        <div class="container text-center">
            <div class="row align-items-start">
                <div class="col">
                    <p class="card-text" style="text-align: left; font-size: 13px;">
                        ️Fokus: Membangun sistem cerdas dengan algoritma machine learning dan deep learning. <br>
                        Level: Pemula – Menengah – Mahir <br>
                        Durasi: ± 4–5 bulan <br>
                        Bahasa yang digunakan: Python, TensorFlow/PyTorch, SQL<br> <br>
                        Tujuan Pembelajaran:
                        <ul style="text-align: left; font-size: 13px;">
                            <li>Memahami konsep dasar machine learning</li>
                            <li>Membangun model ML sederhana dan deep learning</li>
                            <li>Menganalisis data dengan algoritma AI</li>
                            <li>Mengimplementasikan model ke aplikasi dunia nyata</li>
                        </ul>
                        <span style="font-weight: bold; font-size: 13px; text-align: left; display: block;">
                            Total Paket AI & ML: Rp 2.600.000 <br>
                            Sertifikat: 3 sertifikat kelas + 1 sertifikat paket
                        </span>
                    </p>
                </div>
                <div class="col">
                    <img src="sertif.png" alt="paket web" style="width: 250px;">
                </div>
            </div>
        </div>
        <a href="nama_file_halaman_paket?id=<?= 3 ?>" class="t_paket btn">Go somewhere</a>
    </div>
</div>
</center>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>