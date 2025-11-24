<?php
session_start(); // Tambahkan di awal
include "./config/connection.php";

if (!$conn) {
    die("ERROR: Tidak bisa connect ke database. " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Ambil dan Sanitasi Input (Sesuai dengan name attribute yang BENAR di HTML)
    $namalengkap = trim($_POST['nama']);       // name="nama"
    $username = trim($_POST['username']);       // name="username"
    $email = trim($_POST['email']);           // name="email"
    $phone = trim($_POST['phone']);           // name="phone" <-- BARU
    $pass = $_POST['password'];               // name="password"
    $confirm = $_POST['confirm'];             // name="confirm"

    // 2. Validasi Input
    if (empty($namalengkap) || empty($username) || empty($email) || empty($phone) || empty($pass) || empty($confirm)) {
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

    if (!ctype_digit($phone)) {
        echo "<script>alert('Nomor telepon hanya boleh berisi angka!'); window.history.back();</script>";
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

    // 4. Enkripsi Password dan Siapkan Nilai Default untuk Insert
    $passHash = password_hash($pass, PASSWORD_DEFAULT);
    $verify_code_default = 0;

    // 5. Query INSERT
    // Query: (username, nama, email, phone_number, password, verify_code)
    $stmt = mysqli_prepare($conn, "INSERT INTO user (username, nama, email, phone_number, password, verify_code) VALUES (?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        die("ERROR Prepare Insert: " . mysqli_error($conn));
    }
    

    mysqli_stmt_bind_param($stmt, "sssssi", $username, $namalengkap, $email, $phone, $passHash, $verify_code_default);
    
    if (mysqli_stmt_execute($stmt)) {
        $id_user = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        
        // Set session setelah registrasi berhasil (AUTO LOGIN)
        $_SESSION['user_id'] = $id_user;
        $_SESSION['nama'] = $namalengkap;
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $username;
        
        // Redirect ke halaman pembayaran dengan session aktif
        header("Location: pembayaran.php?id_user=$id_user");
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
            <input type="text" class="form-control" name="nama" placeholder="Nama lengkap" required maxlength="50">
        </div>
        
        <div class="mb-3">
            <input type="text" class="form-control" name="username" placeholder="Username" required maxlength="50">
        </div>

        <div class="mb-3">
            <input type="email" class="form-control" name="email" placeholder="Email" required maxlength="50">
        </div>
        
        <div class="mb-3">
            <input type="number" class="form-control" name="phone" placeholder="Nomor Telepon" required maxlength="50">
        </div>

        <div class="mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" required minlength="8">
        </div>
        <div class="password-requirements">Minimum 8 karakter</div>

        <div class="mb-3">
            <input type="password" class="form-control" name="confirm" placeholder="Confirm Password" required>
        </div>

        <button type="submit" class="btn-payment">Continue to Payment</button>
    </form>

    <div class="login-link">
        Sudah punya akun? <a href="login.php">Login disini</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>