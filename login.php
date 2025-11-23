<?php
session_start();
include "./config/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $verify_code_input = trim($_POST['verify_code']);

    // Validasi input
    if (empty($email) || empty($password) || empty($verify_code_input)) {
        $error = "Semua field harus diisi!";
    } else {

        // Ambil user berdasarkan email
        $stmt = mysqli_prepare($conn, 
            "SELECT id_user, username, nama, email, password, verify_code 
             FROM user WHERE email = ?"
        );
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($user) {

            if (password_verify($password, $user['password'])) {

                if ($user['verify_code'] != $verify_code) {
                    $error = "Verify code salah!";
                } else {

$_SESSION['user_id'] = $user['id_user'];
$_SESSION['nama'] = $user['nama'];
$_SESSION['email'] = $user['email'];

$id_user = $user['id_user'];

if ($user) {

    if (password_verify($password, $user['password'])) {

        if ($user['verify_code'] != $verify_code) {
            $error = "Verify code salah!";
        } else {

            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];

            $id_user = $user['id_user'];

            // Cek pembayaran
            $cek_bayar = mysqli_query($conn, "SELECT * FROM pembayaran WHERE id_user = '$id_user'");
            $pembayaran = mysqli_fetch_assoc($cek_bayar);

            // Simpan id paket di session
            if ($pembayaran) {
                $_SESSION['id_paket'] = $pembayaran['id_paket'];
            } else {
                $_SESSION['id_paket'] = null;
            }

            // SELALU ke landingpage
            header("Location: landingpage.php");
            exit();
        }

    } else {
        $error = "Password salah!";
    }

} else {
    $error = "Email tidak terdaftar!";
}


                }
            } else {
                $error = "Password salah!";
            }

        } else {
            $error = "Email tidak terdaftar!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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

        .btn-login {
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

        .btn-login:hover {
            opacity: 0.85;
            color: #fff;
        }

        .alert {
            border-radius: 12px;
            padding: 12px;
            font-size: 14px;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .register-link a {
            color: #ff7996;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
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
    <h2>Welcome Back!</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <input type="email" class="form-control" name="email" placeholder="Email" required>
        </div>

        <div class="mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
        </div>

        <div class="mb-3">
            <input type="text" class="form-control" name="verify_code" placeholder="Verify Code (6 digit)" required maxlength="6" pattern="[0-9]{6}">
        </div>

        <button type="submit" class="btn-login">Login</button>
    </form>

    <div class="register-link">
        Belum punya akun? <a href="register.php">Register disini</a>
    </div>
</div>

</body>
</html>