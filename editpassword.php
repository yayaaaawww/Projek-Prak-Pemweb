<?php
    require_once './config/connection.php'; 

    session_start();
    $id = $_SESSION['user_id'] ?? 0;
    $username = $_SESSION['nama'] ?? null;

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
        <div class="col" style="margin-top: 130px;">
        <div class="card">
            <form action="./proses/editpass_proses.php" method="post" class="form">
            <h2 class="text">Ubah Password</h2>
            <label for="inputPassword5" class="label form-label" style="text-align: left; margin-top: 10px;">Password Lama</label>
            <input type="password" name="passwordlama" id="inputPassword5" class="form-control" aria-describedby="passwordHelpBlock">
            
            <label for="inputPassword5" class="label form-label" style="text-align: left; margin-top: 10px;">Password Baru</label>
            <input type="password" name="passwordbaru" id="inputPassword5" class="form-control" aria-describedby="passwordHelpBlock">
            <center>
            <button type="submit" class="btn btn-primary">Ubah Password</button><br><br>
            Lupa kata sandi? <a href="editpassword2.php">klik disini</a>
            </center>
            </form>
        </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>