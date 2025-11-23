<?php
    require_once './config/connection.php';
    session_start();
    $username = $_SESSION['nama'] ?? null;

    $query = "SELECT u.nama, t.testimoni FROM testimoni t 
              JOIN user u ON u.id_user = t.id_user";
    $stmt = $conn->query($query);
    $result = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C🌸deBloom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        .login {
        text-decoration: none;
        color: black;
        margin-left: 400px;
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

        .header{
            height: 280px;
            background-color: #FFEAEA;
        }
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
                <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li>
                <li class="nav-item ms-5 d-flex align-items-center">
                    <?php if ($username): ?>
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

<!-- header -->
 <div class="header container-fluid">
        <div class="container text-center">
        <div class="row align-items-start">
            <div class="col">
            <h1 style="font-weight: bold; font-size: 50px; margin-top: 50px;">Apa Kata Mereka Tentang CodeBloom?</h1>
            </div>
            <div class="col">
            <img src="./gambar/testi.png" alt="loopy testimoni" style="width: auto; height: 250px; margin-top: 30px;">
            </div>
        </div>
        </div>
 </div>

    <!-- testimoni -->
     <?php if ($username): ?>
        <form action="./proses/tambahtesti_proses.php" method="post">
            <div class="mb-3" style="margin-top: 50px; margin-left: 100px; margin-right: 100px;">
            <textarea class="form-control" name="testi" id="exampleFormControlTextarea1" rows="3" placeholder="Tambahkan Testimoni"></textarea>
            </div>
            <center>
            <button type="submit" class="btn btn-primary" name="add" style="margin-top: 30px; margin-bottom: 30px;">Tambahkan</button>
            </center>
        </form>
    <?php endif; ?>
    <a href="testimoni.php" style="text-decoration: none; color: black; margin-top: 50px;">
    <h2 style="text-align: center; font-weight: bold; margin-bottom: 20px; ">Testimoni</h2>
    </a>

    <div class="container mb-5">
        <div class="row justify-content-center g-4"> 
            <?php foreach($result as $r): ?>    
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
            <?php endforeach; ?>
            
        </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>