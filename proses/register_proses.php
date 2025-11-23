<?php
ob_start(); // Mencegah error header already sent

include "../config/connection.php";

if (!$conn) {
    die("ERROR: Tidak bisa connect. " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($username) || empty($nama_lengkap) || empty($phone) || empty($email) || empty($pass) || empty($confirm)) {
        echo "<script>alert('Semua field harus diisi!'); window.history.back();</script>";
        exit;
    }

    if ($pass !== $confirm) {
        echo "<script>alert('Password tidak sama!'); window.history.back();</script>";
        exit;
    }

    if (strlen($pass) < 8) {
        echo "<script>alert('Password minimal 8 karakter'); window.history.back();</script>";
        exit;
    }

    if (!preg_match('/^[0-9]+$/', $phone)) {
        echo "<script>alert('Nomor telepon hanya boleh angka'); window.history.back();</script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Format email tidak valid'); window.history.back();</script>";
        exit;
    }

    $stmt = mysqli_prepare($conn, "SELECT id_user FROM user WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.history.back();</script>";
        exit;
    }
    mysqli_stmt_close($stmt);

    $passHash = password_hash($pass, PASSWORD_DEFAULT);
    $verify_code = 0;

    $stmt = mysqli_prepare($conn, 
        "INSERT INTO user (username, nama, phone_number, email, password, verify_code)
         VALUES (?, ?, ?, ?, ?, ?)");

    mysqli_stmt_bind_param($stmt, "sssssi",
        $username,
        $nama_lengkap,
        $phone,
        $email,
        $passHash,
        $verify_code
    );

    if (mysqli_stmt_execute($stmt)) {

        $id_user = mysqli_insert_id($conn); // 🔥 ID berhasil didapat

        header("Location: ../pembayaran.php?id=$id_user");
        exit;

    } else {
        $error = mysqli_stmt_error($stmt);
        echo "<script>alert('Registrasi gagal: " . addslashes($error) . "'); window.history.back();</script>";
        exit;
    }
}

ob_end_flush();
