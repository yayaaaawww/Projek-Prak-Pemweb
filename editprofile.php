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
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        
        body {
        font-family: "Poppins", sans-serif;
        background-image: linear-gradient(to bottom, #ffffff 0%,   #fee0e0ff 100%);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        }

        .form{
            margin-top: 50px;
            margin-bottom: 50px;
            margin-left: 20px;
            margin-right: 20px;
        }

        .text{
            text-align: center;
            font-weight: bold;
        }

        .btn{
            margin-top: 20px;
        }

        .card{
            background-color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="row align-items-start">
        <div class="col">
        <center>
        <img src="./gambar/loopy.png" alt="loopy" style="height: 400px; margin-top: 50px;">
        </center>
        </div>
        <div class="col" style="margin-top: 50px;">
        <div class="card">
            <form action="./proses/editprofile_proses.php" method="post" class="form">
            <h2 class="text">Ubah Profile</h2>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama) ?>">
                </div>

                <div>  
                    <label for="name" class="form-label">Nama lengkap</label>
                    <input type="text" name="namalengkap" class="form-control" value="<?= htmlspecialchars($namalengkap) ?>">
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Phone</label>
                    <input type="number" name="phone_number" class="form-control" value="<?= htmlspecialchars($noHP) ?>">
                </div>
            <center>
            <button type="submit" class="btn btn-primary">Ubah Profil</button>
            </center>
            </form>
        </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>