<?php
// Perbaikan: Jalur file koneksi harus keluar satu tingkat direktori (../)
require_once '../config/koneksi.php'; 
session_start();

// Ambil ID dari sesi
$id_user = $_SESSION['user_id'] ?? 0;

// Pastikan pengguna login dan ada input
if (empty($id_user) || !isset($_POST['nama'], $_POST['email'])) {
    // Jika tidak ada POST, berarti file diakses langsung. Arahkan kembali.
    header("Location: ../profile.php?error=invalid_access");
    exit;
}

// Sanitasi input
$username_input = trim($_POST['nama']);
$namalengkap_input = trim($_POST['namalengkap'] ?? '');
$email_input = trim($_POST['email']);
$phone_imput = trim($_POST['phone_number'] ?? '');

try {
    // Query UPDATE yang Aman
    $stmt = $conn->prepare("UPDATE user SET username = ?, nama = ?, email = ?, phone_number = ? WHERE id_user = ?");
    $stmt->bind_param("sssii", $username_input, $namalengkap_input, $email_input, $phone_imput, $id_user);
    $stmt->execute();
    
    // Cek apakah ada baris yang terpengaruh (update berhasil)
    if ($stmt->affected_rows > 0) {
        
        // PERBAIKAN UTAMA: Perbarui variabel sesi 'nama'
        $_SESSION['nama'] = $username_input; 
        
        $stmt->close();
        header("Location: ../profile.php?status=update_success");
        exit;
    } else {
        // Jika data yang diinput sama persis dengan yang lama (affected_rows = 0)
        $stmt->close();
        header("Location: ../profile.php?warning=no_changes_made");
        exit;
    }
} catch (mysqli_sql_exception $e){
    error_log("Profile Update Error: " . $e->getMessage());
    header("Location: ../profile.php?error=db_error");
    exit;
}
?>