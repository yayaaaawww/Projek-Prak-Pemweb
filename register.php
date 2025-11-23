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
            padding-left: 4px;
            font-style: italic;
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

    <form method="POST" action="./proses/register_proses.php">

        <div class="mb-3">
            <input type="text" class="form-control" name="username" placeholder="Username" required maxlength="50">
        </div>

        <div class="mb-3">
            <input type="text" class="form-control" name="nama_lengkap" placeholder="Nama Lengkap" required>
        </div>

        <div class="mb-3">
            <input type="number" class="form-control" name="phone" placeholder="Nomor Telepon" required>
        </div>

        <div class="mb-3">
            <input type="email" class="form-control" name="email" placeholder="Email" required maxlength="50">
        </div>

        <div class="mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" required minlength="8">
        </div>
        <div class="password-requirements">Minimum 8 karakter</div>

        <div class="mb-3">
            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
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
