<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$nama = $_SESSION['nama'];
$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ffffff, #febcc2);
            min-height: 100vh;
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

        .dashboard-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
        }

        .welcome-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .welcome-card h2 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .user-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .user-info p {
            margin: 8px 0;
            font-size: 15px;
        }

        .btn-logout {
            background-color: #dc3545;
            color: #fff;
            padding: 12px 30px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: 0.2s;
        }

        .btn-logout:hover {
            opacity: 0.85;
            color: #fff;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">c🌸deBloom</a>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</nav>

<div class="dashboard-container">
    <div class="welcome-card text-center">
        <h2>Selamat Datang, <?= htmlspecialchars($nama) ?>! 🎉</h2>
        <p class="text-muted">Anda berhasil login ke dashboard</p>

        <div class="user-info">
            <p><strong>Nama:</strong> <?= htmlspecialchars($nama) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>Status:</strong> <span style="color: #4caf50; font-weight: 600;">✓ Verified</span></p>
        </div>
    </div>

</div>

</body>
</html>