<?php
include "./config/koneksi.php";

if (!$conn) {
    die("ERROR: Tidak bisa connect ke database. " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    $confirm = $_POST['confirm'];

    if (empty($nama) || empty($email) || empty($pass) || empty($confirm)) {
        echo "<script>alert('Semua field harus diisi!'); window.history.back();</script>";
        exit;
    }

    if ($pass !== $confirm) {
        echo "<script>alert('Password tidak sama!'); window.history.back();</script>";
        exit;
    }

    if (strlen($pass) < 8) {
        echo "<script>alert('Password minimal 8 karakter!'); window.history.back();</script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Format email tidak valid!'); window.history.back();</script>";
        exit;
    }

    $stmt = mysqli_prepare($conn, "SELECT id_user FROM user WHERE email = ?");
    
    if (!$stmt) {
        die("ERROR Prepare Statement: " . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.history.back();</script>";
        mysqli_stmt_close($stmt);
        exit;
    }
    mysqli_stmt_close($stmt);

    $passHash = password_hash($pass, PASSWORD_DEFAULT);

    $verify_code_default = 0;
    $stmt = mysqli_prepare($conn, "INSERT INTO user (nama, email, password, verify_code) VALUES (?, ?, ?, ?)");
    
    if (!$stmt) {
        die("ERROR Prepare Insert: " . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($stmt, "sssi", $nama, $email, $passHash, $verify_code_default);
    
    if (mysqli_stmt_execute($stmt)) {
        $id_user = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        
        // Redirect ke halaman pembayaran
        header("Location: pembayaran.php?id=$id_user");
        exit;
    } else {
        $error_msg = mysqli_stmt_error($stmt);
        echo "<script>alert('Registrasi gagal: " . addslashes($error_msg) . "'); window.history.back();</script>";
        mysqli_stmt_close($stmt);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>

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

        .form-container {
            background: #fff;
            border-radius: 20px;
            width: 100%;
            max-width: 520px;
            padding: 40px;
            margin: 90px auto 50px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            font-weight: 700;
            margin-bottom: 20px;
        }

        .form-control {
            padding: 16px;
            font-size: 15px;
            border-radius: 12px;
            border: 2px solid #e6e6e6;
        }

        .form-control:focus {
            border-color: #ff7996;
            box-shadow: 0 0 0 0.15rem rgba(255, 121, 150, 0.3);
        }

        .btn-payment {
            background-color: #000;
            color: #fff;
            width: 100%;
            font-weight: 600;
            padding: 14px;
            border-radius: 12px;
            transition: 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-payment:hover {
            opacity: 0.85;
            color: #fff;
        }

        .password-requirements {
            font-size: 11px;
            color: #999;
            margin-top: 6px;
            margin-bottom: 16px;
            text-align: left;
            padding-left: 4px;
            font-style: italic;
        }

        .password-requirements::before {
            content: "ℹ️ ";
            margin-right: 4px;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .login-link a {
            color: #ff7996;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">c🌸deBloom</a>
    </div>
</nav>

<div class="form-container text-center">
    <h2>Create Your Account</h2>

    <form method="POST" action="">
        <div class="mb-3">
            <input type="text" class="form-control" name="nama" placeholder="Name" required maxlength="50">