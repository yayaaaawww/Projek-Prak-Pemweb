<?php
session_start();
// ASUMSI: Setelah login, Anda menyimpan ID user di session
$_SESSION['user_id'] = 1; 

$user_id = $_SESSION['user_id'];
$paket_id = 1; // Contoh: Paket Web Developer Mastery

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Sertifikat</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f4f4; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); text-align: center; }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 14px 20px;
            margin: 15px 0;
            border-radius: 4px;
            text-decoration: none;
            font-size: 18px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Selamat, ID User Anda: **<?php echo $user_id; ?>**</h2>
        <p>Anda telah menyelesaikan paket.</p>
        
        <a 
            href="sertifikat_proses.php?user_id=<?php echo $user_id; ?>&paket_id=<?php echo $paket_id; ?>" 
            class="button"
            target="_blank"
        >
            Tampilkan Sertifikat "Web Developer Mastery"
        </a>
        <p style="margin-top: 20px;">*Buka di tab baru untuk hasil terbaik</p>
    </div>
</body>
</html>