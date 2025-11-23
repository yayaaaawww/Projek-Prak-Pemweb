<?php
require_once './config/connection.php';
session_start();

$username = $_SESSION['nama'] ?? null;
$id = $_SESSION['user_id'] ?? 0;

$query = "SELECT nama, email, phone_number, username FROM user WHERE id_user = '$id'";
$stmt = $conn->query($query);

if ($data = $stmt->fetch_assoc()) {
    $namalengkap = $data['nama'];
    $email = $data['email'];
    $noHP = $data['phone_number'];
    $nama = $data['username'];
} else {
    header("Location: index.php");
    exit;
}
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
                <h3 style="font-weight: bold;">Profile</h3>
                <div class="formprofile">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($nama) ?>" readonly>
                    </div>

                    <div>  
                        <label for="name" class="form-label">Nama lengkap</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($namalengkap) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Email</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($email) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Phone</label>
                        <input type="number" class="form-control" value="<?= htmlspecialchars($noHP) ?>" readonly>
                    </div>

                    <center>
                    <a href="editprofile.php">
                    <button type="button" class="btn btn-primary">Edit Pofil</button>
                    </a>
                    <a href="editpassword.php">
                    <button type="button" class="btn btn-primary">Ubah Password</button>
                    </a>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>