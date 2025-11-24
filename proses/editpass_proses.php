<?php
require_once '../config/connection.php'; 

session_start();
$id_user = $_SESSION['user_id'] ?? 0;

if (empty($id_user)) {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['passwordlama'], $_POST['passwordbaru']) && !empty($_POST['passwordlama']))
{
    $passlama_input = trim($_POST['passwordlama']);
    $passbaru_input = trim($_POST['passwordbaru']);
    
    // Wajib: Hash password baru sebelum disimpan
    $hashed_passbaru = password_hash($passbaru_input, PASSWORD_DEFAULT);
    $password_old_hashed = null;

    // A. Ambil Hashed Password Lama dari Database
    $query_select = "SELECT password FROM user WHERE id_user = ?";
    $stmt_select = $conn->prepare($query_select);
    $stmt_select->bind_param("i", $id_user);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();

    if ($pass = $result_select->fetch_assoc()) {
        $password_old_hashed = $pass['password'];
    }
    $stmt_select->close();

    // B. Verifikasi Password Lama menggunakan HASHING
    if (password_verify($passlama_input, $password_old_hashed))
    {
        // C. Update Password Baru yang Sudah di-hash
        if (update_password($conn, $id_user, $hashed_passbaru)) {
            header("Location: ../profile.php?status=password_updated");
        } else {
            header("Location: ../profile.php?error=update_failed");
        }
        exit;
    } else {
        // Gagal verifikasi password lama
        header("Location: ../profile.php?error=password_mismatch");
        exit;
    }
} 

// --------------------------------------------------------
// --- BLOK 2: BYPASS LUPA PASSWORD (EMAIL & PASSWORD BARU) ---
// --------------------------------------------------------

elseif (isset($_POST['email'], $_POST['passwordbaru'])) {
    
    $email_input = trim($_POST['email']);
    $passbaru_input = trim($_POST['passwordbaru']);
    
    // Wajib: Hash password baru sebelum disimpan
    $hashed_passbaru = password_hash($passbaru_input, PASSWORD_DEFAULT);
    $email_registered = null;

    // A. Ambil Email Terdaftar dari Database untuk ID pengguna ini
    $query_select_email = "SELECT email FROM user WHERE id_user = ?";
    $stmt_select_email = $conn->prepare($query_select_email);
    $stmt_select_email->bind_param("i", $id_user);
    $stmt_select_email->execute();
    $result_email = $stmt_select_email->get_result();

    if ($data = $result_email->fetch_assoc()) {
        $email_registered = $data['email'];
    }
    $stmt_select_email->close();
    
    // B. Bandingkan email input dengan email terdaftar
    if ($email_input === $email_registered)
    {
        // Email cocok: Lakukan update password
        if (update_password($conn, $id_user, $hashed_passbaru)) {
            header("Location: ../profile.php?status=password_reset_by_email");
        } else {
            header("Location: ../profile.php?error=update_failed");
        }
        exit;
    } else {
        // GAGAL: Email tidak cocok
        header("Location: ../profile.php?error=email_not_matching");
        exit;
    }
}

// --------------------------------------------------------
// --- BLOK 3: JIKA TIDAK ADA INPUT YANG DIKENALI ---
// --------------------------------------------------------

else {
     header("Location: ../profile.php?error=invalid_request");
     exit;
}

// --------------------------------------------------------
// --- FUNGSI PEMBANTU UNTUK MENGHINDARI PENGULANGAN KODE ---
// --------------------------------------------------------

function update_password($conn, $id_user, $hashed_password) {
    try {
        $queryupdate = "UPDATE user SET password = ? WHERE id_user = ?";
        $stmtupdate = $conn->prepare($queryupdate);
        if ($stmtupdate === false) {
            throw new Exception("Gagal menyiapkan update statement.");
        }
        $stmtupdate->bind_param("si", $hashed_password, $id_user);
        
        if ($stmtupdate->execute()) {
            $stmtupdate->close();
            return true;
        } else {
            throw new Exception("Gagal menjalankan update: " . $stmtupdate->error);
        }
    } catch (Exception $e) {
        error_log("Password Update Error: " . $e->getMessage());
        return false;
    }
}

?>