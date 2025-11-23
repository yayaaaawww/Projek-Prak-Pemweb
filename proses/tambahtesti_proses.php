<?php
require_once '../config/koneksi.php';
session_start();

if (isset($_POST['add'])) {

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit;
    }

    $user_id = (int)$_SESSION['user_id'];
    $testimoni = $_POST['testi'] ?? '';

    try {
        $query = "INSERT INTO testimoni (id_user, testimoni) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $user_id, $testimoni);
        $stmt->execute();
        $stmt->close();

        header("Location: ../testimoni.php");
        exit;
    } catch (mysqli_sql_exception $e) {
        error_log("Review insertion error: " . $e->getMessage());
        echo "Error: Terjadi kesalahan saat menambahkan ulasan.";
    }

} else {
    header("Location: ../landingpage.php");
    exit;
}
