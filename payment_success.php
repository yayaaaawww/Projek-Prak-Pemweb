<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "./config/connection.php";

if (!isset($_GET['id']) || !isset($_GET['code'])) {
    header("Location: register.php");
    exit;
}

$id_user = intval($_GET['id']);
$verify_code = intval($_GET['code']);

$stmt = mysqli_prepare($conn, "SELECT nama, email FROM user WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    header("Location: register.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ffffff, #febcc2);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: white !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 16px;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 22px;
        }

        .success-container {
            background: #fff;
            border-radius: 20px;
            width: 100%;
            max-width: 520px;
            padding: 40px;
            margin: 90px auto 50px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #4caf50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
            color: white;
        }

        .verify-code-box {
            background: #f8f9fa;
            border: 3px dashed #ff7996;
            padding: 30px;
            border-radius: 12px;
            margin: 25px 0;
        }

        .verify-code {
            font-size: 48px;
            font-weight: 700;
            color: #ff7996;
            letter-spacing: 8px;
            margin: 10px 0;
        }

        .btn-login {
            background-color: #000;
            color: #fff;
            width: 100%;
            font-weight: 600;
            padding: 14px;
            border-radius: 12px;
            transition: 0.2s;
            text-decoration: none;
            display: block;
        }

        .btn-login:hover {
            opacity: 0.85;
            color: #fff;
        }

        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 13px;
            text-align: left;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">c🌸deBloom</a>
    </div>
</nav>

<div class="success-container">
    <div class="success-icon">✓</div>
    
    <h2 style="font-weight: 700; margin-bottom: 10px;">Pembayaran Berhasil!</h2>

    <div class="verify-code-box">
        <p style="margin: 0; font-size: 14px; color: #666;">Verify Code Anda:</p>
        <div class="verify-code"><?= $verify_code ?></div>
        <p style="margin: 0; font-size: 12px; color: #999;">Simpan kode ini dengan baik</p>
    </div>

    <a href="login.php" class="btn-login mt-4">Login Sekarang</a>
</div>

</body>
</html>