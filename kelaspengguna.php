<?php
require_once './config/connection.php'; 

session_start();
$id = $_SESSION['user_id'] ?? 0;
$username = $_SESSION['nama'] ?? null;

$query_result = null;

if((int)$id > 0)
{
    $stmt = $conn->prepare("SELECT p.nama_paket, p.id_paket FROM paket p
                            JOIN pembayaran pb ON p.id_paket = pb.id_paket 
                            WHERE pb.id_user = ?");

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $query_result = $stmt->get_result(); 
        
        if ($query_result->num_rows === 0) {
            header("Location: landingpage.php");
            exit;
        }
        
    } else {
         die("Query error: " . $conn->error);
    }
} else {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paket <?= $username ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap; 
            gap: 30px; 
            justify-content: center;
            margin-top: 50px;
        }
        .custom-card {
            width: 500px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-img-top {
            max-width: 150px; 
            height: auto;
            margin: 10px;
        }
    </style>
</head>
<body>
    <center>
        <h3 style="margin-top:50px; font-weight: bold;">Paket yang Dimiliki Oleh <?= $username ?></h3>
        
        <div class="card-container">
            <?php 
            while ($row = $query_result->fetch_assoc()): 
                
                $idPaket = (int)$row['id_paket'];
                $namapaket = $row['nama_paket'];
                $text_deskripsi = "";
                $gambar_path = "";
                
                if ($idPaket === 1) {
                    $gambar_path = "./gambar/paketWeb.png";
                    $text_deskripsi = "Belajar bikin website dari nol sampai fullstack.";
                } elseif ($idPaket === 2) {
                    $gambar_path = "./gambar/paketDS.png";
                    $text_deskripsi = "Ubah data mentah jadi insight visual yang berguna.";
                } elseif ($idPaket === 3) {
                    $gambar_path = "./gambar/paketAI.png";
                    $text_deskripsi = "Masuk ke dunia AI dan bangun sistem cerdasmu sendiri.";
                }
            ?>
            <div class="card custom-card">
                <div class="container text-center">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <img src="<?= $gambar_path ?>" class="card-img-top" alt="gambar paket">
                        </div>
                        <div class="col-8">
                            <div class="card-body text-start">
                                <h5 class="card-title fw-bold"><?= $namapaket ?></h5>
                                <p class="card-text text-muted"><?= $text_deskripsi ?></p>
                                <!-- <a href="halaman_lanjut.php?id_paket=<?= $idPaket ?>" class="btn btn-primary mt-2">Lanjutkan</a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
            endwhile; 
            $stmt->close();
            ?>
        </div>
    </center>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>