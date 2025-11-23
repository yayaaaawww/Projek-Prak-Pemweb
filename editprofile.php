<?php
require_once './config/connection.php';
session_start();

$username = $_SESSION['nama'] ?? null;
$id = $_SESSION['user_id'] ?? 0;

$query = "SELECT nama, email, password FROM user WHERE id_user = '$id'";
$stmt = $conn->query($query);

if ($data = $stmt->fetch_assoc()) {
    $nama = $data['nama'];
    $email = $data['email'];
    $password = $data['password'];
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
        One of three columns
        </div>
        <div class="col" style="margin-top: 130px;">
        <div class="card">
            <form action="./proses/editpass_proses.php" method="post" class="form">
            <h2 class="text">Ubah Profile</h2>
            <label for="inputPassword5" class="label form-label" style="text-align: left; margin-top: 10px;">Nama</label>
            <input type="text" name="nama" id="inputPassword5" class="form-control" aria-describedby="passwordHelpBlock" value="<?= htmlspecialchars($nama) ?>">
            
            <label for="inputPassword5" class="label form-label" style="text-align: left; margin-top: 10px;">Email</label>
            <input type="email" name="email" id="inputPassword5" class="form-control" aria-describedby="passwordHelpBlock" value="<?= htmlspecialchars($email) ?>">
            
            <label for="inputPassword5" class="label form-label" style="text-align: left; margin-top: 10px;">Phone</label>
            <input type="number" name="phone" id="inputPassword5" class="form-control" aria-describedby="passwordHelpBlock" value="<?= htmlspecialchars($password) ?>">
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