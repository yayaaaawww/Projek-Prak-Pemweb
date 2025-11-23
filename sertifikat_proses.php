<?php
session_start();
// Panggil koneksi database
include 'config/connection.php'; 

// 1. Ambil ID User dan ID Paket dari URL (Metode GET)
$id_user_login = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$id_paket_sertifikat = isset($_GET['paket_id']) ? (int)$_GET['paket_id'] : 0;

if ($id_user_login === 0 || $id_paket_sertifikat === 0) {
    die("ID Pengguna atau ID Paket tidak valid.");
}


// --- QUERY UNTUK MENGAMBIL DATA ---
$sql = "
    SELECT 
        u.nama, 
        p.nama_paket 
    FROM 
        user u
    JOIN 
        enrollment e ON u.id_user = e.id_user
    JOIN 
        paket p ON e.id_paket = p.id_paket
    WHERE 
        u.id_user = ? AND p.id_paket = ?
";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Prepare failed: ' . $conn->error);
}

$stmt->bind_param("ii", $id_user_login, $id_paket_sertifikat);
$stmt->execute();
$result = $stmt->get_result();

$nama_penerima = 'Nama Penerima Error';
$nama_paket = 'Paket Error';

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nama_penerima = htmlspecialchars($row['nama']);
    $nama_paket = htmlspecialchars($row['nama_paket']);
} else {
    // Jika data tidak ditemukan di tabel enrollment
    $nama_penerima = "Pengguna ID {$id_user_login}";
    $nama_paket = "Paket ID {$id_paket_sertifikat} (BELUM ENROLL)";
}

$stmt->close();
// $conn->close(); // Opsional: tutup koneksi jika tidak digunakan lagi
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat untuk <?php echo $nama_penerima; ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Italianno&display=swap" rel="stylesheet">
    
    <style>
        /* Gaya CSS untuk memaksimalkan tampilan sertifikat di layar */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }

        .certificate-container {
            /* Sesuaikan ukuran agar sesuai dengan rasio aspek gambar Anda */
            width: 1000px; 
            height: 750px; /* Rasio 4:3. Sesuaikan jika gambar berbeda */
            position: relative;
            background-image: url('sertifikatPengguna.png');
            background-size: cover;
            background-repeat: no-repeat;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        /* --- PENEMPATAN NAMA DENGAN FONT ITALIANNO --- */
        .recipient-name {
            position: absolute;
            top: 45%; /* SESUAIKAN: Posisi vertikal nama di tengah sertifikat */
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            text-align: center;
        }

        .name-text-display {
            font-family: 'Italianno', cursive; 
            font-size: 4em; 
            color: #000000; 
            line-height: 1.2;
            margin: 0;
            padding: 0;
            white-space: nowrap; /* Penting agar nama tidak terpotong */
        }
        
        /* --- PENEMPATAN TEKS ISI SERTIFIKAT --- */
        .certificate-content {
            position: absolute; 
            top: 60%; /* Di bawah nama utama */
            left: 50%; 
            transform: translateX(-50%); 
            width: 75%; 
            text-align: center;
            font-size: 1.2em; 
            color: #333;
        }

        /* --- PENEMPATAN TANDA TANGAN (Kurnia & Azzah) --- */
        .signature-area {
            position: absolute;
            width: 70%;
            left: 50%;
            transform: translateX(-50%);
            bottom: 120px; /* SESUAIKAN: Jarak dari bawah untuk area tanda tangan */
            display: flex;
            justify-content: space-between;
        }

        .signature-item {
            text-align: center;
            width: 45%; 
        }

        .signature-line {
            border-bottom: 2px solid #333; 
            margin: 0 auto;
            width: 80%;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        
        <div class="recipient-name">
            <h1 class="name-text-display"><?php echo $nama_penerima; ?></h1>
        </div>

        <div class="certificate-content">
            <p>
                Selamat anda telah menyelesaikan **Paket <?php echo $nama_paket; ?>** dari CodeBloom.
            </p>
        </div>

        <div class="signature-area">
            
            <div class="signature-item">
                <div class="signature-line"></div>
                <p style="margin-top: 5px; font-size: 0.9em;">Kurnia Ardiningrum</p>
            </div>

            <div class="signature-item">
                <div class="signature-line"></div>
                <p style="margin-top: 5px; font-size: 0.9em;">Azzah Fauziya Kamila</p>
            </div>
        </div>
    </div>
</body>
</html>