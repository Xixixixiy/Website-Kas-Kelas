<?php

/**
 * ========================================
 * UPDATE STATUS SISWA - AKTIF/NON-AKTIF
 * ========================================
 * File ini menangani perubahan status siswa
 * antara Aktif dan Non-Aktif oleh Wali Kelas
 */

session_start();
include __DIR__ . "/../config/database.php";

// Validasi request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit;
}

// Ambil data dari form
$id_user = $_POST['id_user'] ?? null;
$status_lama = $_POST['status_sekarang'] ?? null;

// Validasi input
if (empty($id_user) || empty($status_lama)) {
    echo "<script>
        alert('Data tidak lengkap!');
        window.location = '../wali_kelas/kelola_siswa.php';
    </script>";
    exit;
}

// Toggle status dari Aktif menjadi Non-Aktif atau sebaliknya
$status_baru = ($status_lama === 'Aktif') ? 'Non-Aktif' : 'Aktif';

// Query update status
$sql = "UPDATE user SET status = ? WHERE id_user = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "si", $status_baru, $id_user);

    if (mysqli_stmt_execute($stmt)) {
        // Status berhasil diubah
        echo "<script>
            alert('Status siswa berhasil diubah menjadi: {$status_baru}!');
            window.location = '../wali_kelas/kelola_siswa.php';
        </script>";
    } else {
        // Error saat update
        echo "<script>
            alert('Error: " . addslashes(mysqli_error($conn)) . "');
            window.location = '../wali_kelas/kelola_siswa.php';
        </script>";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<script>
        alert('Error: " . addslashes(mysqli_error($conn)) . "');
        window.location = '../wali_kelas/kelola_siswa.php';
    </script>";
}
