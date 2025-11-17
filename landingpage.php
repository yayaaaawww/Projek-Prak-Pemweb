<?php 
    require_once'./config/connection.php';

    $query = "SELECT u.nama, t.testimoni FROM testimoni t 
              JOIN user u ON u.id_user = t.id_user";
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
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        }

        #welcome{
            height: 580px;
            background-image: url(welcome.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            color : white;
        }

        .bawahwelcome{
        background-image: linear-gradient(to bottom, #ffffff 0%,   #fdefef 100%);  
        }

        .testimoni{
        margin-bottom: 20px;
        }

        .instagram{
        text-decoration: none;
        font-size: 10px;
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
                <a class="nav-link active" aria-current="page" href="#">Home</a>
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

<div id="welcome" class="container-fluid">
    <center>
        <h1 style="font-weight : bold; margin-top : 200px;">WELCOME, BLOOMERS !</h1>
        <p>Halo bloomers selamat datang di CodeBloom tempat asik buat memekarkan skill ngoding kamu ! <br>- Dari loading jadi blooming 🌸 -</p>
        <div class = "lihatlengkap">
            <button type="button" class="btn btn" style="background-color : #FEBCC2; font-weight : bold;">Lihat Selengkapnya</button>
        </div>
    </center>
</div>

<div class="bawahwelcome">
    <!-- definisi kursus -->
    <div class="container text-center" style="margin-top: 50px; margin-bottom: 50px;">
    <div class="row align-items-start">
        <div class="col" style="weight: 300px; margin-top : 100px; margin-bottom : 100px;">
            CodeBloom adalah platform kursus ngoding online yang membantu siapa pun belajar pemrograman dengan cara yang seru, santai, dan terarah. CodeBloom percaya bahwa ngoding bukan cuma tentang menulis perintah untuk komputer, tapi juga tentang menumbuhkan kreativitas, ketekunan, dan rasa percaya diri untuk menciptakan sesuatu yang berarti. 🌷
        </div>
        <div class="col">
            <img src="logo.png" alt="logo codebloom">
        </div>
    </div>
    </div>

    <!-- fasilitas -->
    <h2 style="text-align: center; font-weight: bold;">Fasilitas</h2>
    <div class="fasilitas" style="margin-top : 50px; margin-bottom: 50px;">
        <div class="container text-center">
            <div class="row align-items-start">
        <div class="col" >
            <img src="materi.png" class="card-img-top" alt="...">
        </div>
        <div class="col">
            <img src="projek.png" class="card-img-top" alt="...">     
        </div>
        <div class="col"> 
            <img src="sertif.png" class="card-img-top" alt="...">      
        </div>
    </div>
    </div>
    <div class="container text-center">
    <div class="row align-items-start">
        <div class="col">
            <p class="card-text">Materi lengkap (online)</p>
        </div>
        <div class="col">
            <p class="card-text">Projek individu</p>
        </div>
        <div class="col">
        <p class="card-text">e-sertifikat</p>
        </div>
    </div>
    </div>
    </div>
    <div class = "lihatpaketlengkap mb-5">
        <center>
            <button type="button" class="btn btn" style="background-color : #FEBCC2; font-weight : bold;">Lihat Paket Lengkap</button>
        </center>
    </div>
    
    <!-- testimoni -->
    <h2 style="text-align: center; font-weight: bold; margin-bottom: 20px;">Testimoni</h2>
    
    <div class="container mb-5">
        <div class="row justify-content-center g-4"> 
            
            <?php $batas = 1; ?>
            <?php foreach($result as $r): ?>
                <?php if($batas <= 3): ?> 
                    
                    <div class="col-12 col-md-4 d-flex justify-content-center"> 
                        <div class="card shadow-sm w-100" style="max-width: 300px;"> 
                            <div class="card-body">
                                <i class="bi bi-chat-quote fs-4 mb-2" style="color: #FEBCC2;"></i> 
                                <p class="card-text text-start">
                                    "<?= htmlspecialchars($r['testimoni']) ?>"
                                    <br>
                                    <strong class="mt-2 d-block">- <?= htmlspecialchars($r['nama']) ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php $batas++; ?>
                <?php endif; ?>
            <?php endforeach; ?>
            
        </div>
    </div>
    <div style="text-align: center; margin-top: 50px; margin-bottom: 50px; font-weight: bold;">
    “Learning to write programs stretches your mind, and helps you think better.” <br>
    - Bill Gates -
    </div>
</div>


<!-- footer -->
<div>
<footer class="bg-dark text-white pt-5 pb-4 mt-auto">
    <div class="container">
        <div class="row text-center text-md-start">
            
            <div class="col-md-4 col-lg-4 col-xl-3 mx-auto mb-4">
                <h6 class="text-uppercase fw-bold mb-4">
                    <span style="color: #FEBCC2;">🌸</span> CodeBloom
                </h6>
                <p class="mb-2">
                    <i class="bi bi-envelope me-3" style="color: #FEBCC2;"></i> 
                    <a href="mailto:codebloom@gmail.com" class="text-decoration-none text-light">codebloom@gmail.com</a>
                </p>
                <p class="mb-2">
                    <i class="bi bi-telephone me-3" style="color: #FEBCC2;"></i> 
                    <span class="text-light">0821 4578 8876</span>
                </p>
            </div>

            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                <h6 class="text-uppercase fw-bold mb-4">
                    Tautan Cepat
                </h6>
                <p><a href="#!" class="text-white text-decoration-none">Layanan Kami</a></p>
                <p><a href="#!" class="text-white text-decoration-none">Portofolio</a></p>
                <p><a href="#!" class="text-white text-decoration-none">Blog Terbaru</a></p>
                <p><a href="#!" class="text-white text-decoration-none">Karir</a></p>
            </div>

            <div class="col-md-5 col-lg-3 col-xl-3 mx-auto mb-4 text-center text-md-start">
                <h6 class="text-uppercase fw-bold mb-4">
                    Ikuti Kami
                </h6>
                <a href="https://instagram.com/azzahfk" target="_blank" class="instagram text-white me-3 fs-4">
                    <i class="bi bi-instagram"></i> @codebloom
                </a>
            </div>
        </div>
        
        <hr class="mb-4 mt-0 bg-secondary"> <div class="row d-flex justify-content-center">
            <div class="col-12 text-center">
                <p class="text-muted small mb-0">
                    &copy; 2025 CodeBloom. All rights reserved. Powered by Bootstrap.
                </p>
            </div>
        </div>
    </div>
</footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>