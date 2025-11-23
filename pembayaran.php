<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "./config/koneksi.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: register.php");
    exit;
}

$id_user = intval($_GET['id']);

$stmt = mysqli_prepare($conn, "SELECT nama, email, verify_code FROM user WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    echo "<script>alert('User tidak ditemukan!'); window.location.href='register.php';</script>";
    exit;
}

$query_paket = "SELECT id_paket, nama_paket, nominal, deskripsi, jumlah_kelas FROM paket ORDER BY nominal ASC";
$result_paket = mysqli_query($conn, $query_paket);
$pakets = mysqli_fetch_all($result_paket, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    $id_paket = intval($_POST['id_paket']);

    $stmt = mysqli_prepare($conn, "SELECT nominal FROM paket WHERE id_paket = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_paket);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $paket = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    if (!$paket) {
        echo "<script>alert('Paket tidak ditemukan!');</script>";
    } else {

        $verify_code = rand(100000, 999999);

        $stmt = mysqli_prepare($conn, "UPDATE user SET verify_code = ? WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt, "ii", $verify_code, $id_user);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);

           $tanggal = date('Y-m-d');
           $va_code = $payment_method . '-' . time(); 
           $stmt_bayar = mysqli_prepare($conn, "INSERT INTO pembayaran (kode_pembayaran, tanggal, id_user, id_paket, nominal) VALUES (?, ?, ?, ?, ?)");

if ($stmt_bayar === false) {
    die("Error preparing statement: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt_bayar, "ssiii", $va_code, $tanggal, $id_user, $id_paket, $paket['nominal']);

if (mysqli_stmt_execute($stmt_bayar)) {
    mysqli_stmt_close($stmt_bayar);
    header("Location: payment_success.php?id=$id_user&code=$verify_code");
    exit;
} else {
    echo "<script>alert('Error: " . mysqli_stmt_error($stmt_bayar) . "');</script>";
    mysqli_stmt_close($stmt_bayar);
}
if ($stmt_bayar === false) {
    die("Error preparing statement: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt_bayar, "sssiii", $kode_pembayaran, $va_code, $tanggal, $id_user, $id_paket, $paket['nominal']);

if (mysqli_stmt_execute($stmt_bayar)) {
    mysqli_stmt_close($stmt_bayar);
    header("Location: payment_success.php?id=$id_user&code=$verify_code");
    exit;
} else {
    echo "<script>alert('Error: " . mysqli_stmt_error($stmt_bayar) . "');</script>";
    mysqli_stmt_close($stmt_bayar);
}
            header("Location: payment_success.php?id=$id_user&code=$verify_code");
            exit;
        } else {
            echo "<script>alert('Pembayaran gagal! Silakan coba lagi.');</script>";
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>

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

        .payment-container {
            background: #fff;
            border-radius: 20px;
            width: 100%;
            max-width: 600px;
            padding: 40px;
            margin: 90px auto 50px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
        }

        .payment-container h2 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .user-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .user-info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .user-info strong {
            color: #ff7996;
        }

        .paket-section {
            margin-bottom: 30px;
        }

        .paket-section h5 {
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .paket-card {
            border: 2px solid #e6e6e6;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .paket-card:hover {
            border-color: #ff7996;
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(255, 121, 150, 0.15);
        }

        .paket-card.selected {
            border-color: #ff7996;
            background: linear-gradient(135deg, #fff5f7, #ffffff);
            box-shadow: 0 8px 20px rgba(255, 121, 150, 0.2);
        }

        .paket-card.selected::before {
            content: "✓";
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ff7996;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
        }

        .paket-card input[type="radio"] {
            display: none;
        }

        .paket-info {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .paket-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .paket-details h6 {
            margin: 0 0 8px 0;
            font-weight: 700;
            font-size: 18px;
            color: #333;
        }

        .paket-details p {
            margin: 0;
            font-size: 13px;
            color: #666;
            line-height: 1.6;
        }

        .paket-badge {
            background: #ff7996;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
            margin-top: 8px;
        }

        .paket-price {
            font-size: 28px;
            font-weight: 700;
            color: #ff7996;
            white-space: nowrap;
        }

        .payment-methods {
            margin: 25px 0;
        }

        .payment-option {
            border: 2px solid #e6e6e6;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            display: none;
        }

        .payment-option.active {
            display: block;
            border-color: #ff7996;
            background: #fff5f7;
        }

        .payment-option h5 {
            margin: 0 0 10px 0;
            font-weight: 600;
        }

        .payment-code {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 18px;
            font-weight: 600;
            text-align: center;
            margin: 10px 0;
            letter-spacing: 2px;
        }

        .qr-image {
            width: 250px;
            height: 250px;
            margin: 15px auto;
            display: block;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .method-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .method-btn {
            flex: 1;
            padding: 15px;
            border: 2px solid #e6e6e6;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }

        .method-btn:hover {
            border-color: #ff7996;
        }

        .method-btn.selected {
            border-color: #ff7996;
            background: #fff5f7;
            color: #ff7996;
        }

        .btn-confirm {
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

        .btn-confirm:hover {
            opacity: 0.85;
            color: #fff;
        }

        .btn-confirm:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .info-text {
            font-size: 12px;
            color: #666;
            text-align: center;
            margin-top: 15px;
        }

        .total-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .total-section .total-label {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .total-section .total-price {
            font-size: 32px;
            font-weight: 700;
            color: #ff7996;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">c🌸deBloom</a>
    </div>
</nav>

<div class="payment-container">
    <h2 class="text-center">Pilih Paket & Metode Pembayaran</h2>
    <p class="text-center text-muted mb-4">Selesaikan pembayaran untuk mendapatkan Verify Code</p>

    <div class="user-info">
        <p><strong>Nama:</strong> <?= htmlspecialchars($user['nama']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    </div>

    <form method="POST" id="paymentForm">
        <div class="paket-section">
            <h5>📦 Pilih Paket Kelas</h5>
            <?php foreach ($pakets as $paket): ?>
            <label class="paket-card" onclick="selectPaket(<?= $paket['id_paket'] ?>, <?= $paket['nominal'] ?>)">
                <input type="radio" name="id_paket" value="<?= $paket['id_paket'] ?>" required>
                <div class="paket-info">
                    <div class="paket-header">
                        <div class="paket-details">
                            <h6><?= htmlspecialchars($paket['nama_paket']) ?></h6>
                            <p><?= htmlspecialchars($paket['deskripsi']) ?></p>
                            <span class="paket-badge"><?= $paket['jumlah_kelas'] ?> Kelas</span>
                        </div>
                        <div class="paket-price">
                            Rp <?= number_format($paket['nominal'], 0, ',', '.') ?>
                        </div>
                    </div>
                </div>
            </label>
            <?php endforeach; ?>
        </div>

        <div class="total-section" id="totalSection" style="display: none;">
            <div class="total-label">Total Pembayaran:</div>
            <div class="total-price" id="totalPrice">Rp 0</div>
        </div>

        <h5>Pilih Metode Pembayaran</h5>
        <div class="method-selector">
            <button type="button" class="method-btn" id="btnVA" onclick="selectMethod('va')">
                Virtual Account
            </button>
            <button type="button" class="method-btn" id="btnQR" onclick="selectMethod('qr')">
                QR Code
            </button>
        </div>

        <input type="hidden" name="payment_method" id="paymentMethod" value="">

        <div class="payment-option" id="vaOption">
            <h5>Virtual Account BCA</h5>
            <p style="font-size: 13px; color: #666;">Transfer ke nomor VA berikut:</p>
            <div class="payment-code">8012 3456 7890 1234</div>
            <p class="info-text">Nomor VA ini hanya simulasi untuk demo</p>
        </div>

        <div class="payment-option" id="qrOption">
            <h5>Scan QR Code</h5>
            <p style="font-size: 13px; color: #666;">Gunakan aplikasi e-wallet untuk scan:</p>
            <img src="assets/qr.png" alt="QR Code" class="qr-image">
            <p class="info-text">Scan QR Code untuk melakukan pembayaran</p>
        </div>

        <button type="submit" class="btn-confirm" id="btnConfirm" disabled>
            Konfirmasi Pembayaran
        </button>
    </form>

    <p class="text-center text-muted mt-3" style="font-size: 12px;">
        Setelah konfirmasi, Anda akan mendapatkan Verify Code untuk login
    </p>
</div>

<script>
    let selectedPaket = false;
    let selectedMethod = false;

    function selectPaket(idPaket, nominal) {
        const cards = document.querySelectorAll('.paket-card');
        cards.forEach(card => card.classList.remove('selected'));
        event.currentTarget.classList.add('selected');

        const totalSection = document.getElementById('totalSection');
        const totalPrice = document.getElementById('totalPrice');
        totalSection.style.display = 'block';
        totalPrice.textContent = 'Rp ' + nominal.toLocaleString('id-ID');

        selectedPaket = true;
        checkFormComplete();
    }

    function selectMethod(method) {
        if (!selectedPaket) {
            alert('Pilih paket terlebih dahulu!');
            return;
        }
        const btnVA = document.getElementById('btnVA');
        const btnQR = document.getElementById('btnQR');
        const vaOption = document.getElementById('vaOption');
        const qrOption = document.getElementById('qrOption');
        const paymentMethod = document.getElementById('paymentMethod');

        btnVA.classList.remove('selected');
        btnQR.classList.remove('selected');
        vaOption.classList.remove('active');
        qrOption.classList.remove('active');

        if (method === 'va') {
            btnVA.classList.add('selected');
            vaOption.classList.add('active');
            paymentMethod.value = 'va';
        } else {
            btnQR.classList.add('selected');
            qrOption.classList.add('active');
            paymentMethod.value = 'qr';
        }

        selectedMethod = true;
        checkFormComplete();
    }

    function checkFormComplete() {
        const btnConfirm = document.getElementById('btnConfirm');
        if (selectedPaket && selectedMethod) {
            btnConfirm.disabled = false;
        }
    }
</script>

</body>
</html>