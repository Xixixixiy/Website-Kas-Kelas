<?php

/**
 * ========================================
 * PROSES LOGIN - VERIFIKASI IDENTITAS USER
 * ========================================
 * File ini menangani proses autentikasi user
 * berdasarkan NISN/NIK dan password
 */

session_start();

// Include file konfigurasi database
$path_db = __DIR__ . "/../config/database.php";

if (!file_exists($path_db)) {
    die("Error: File database.php tidak ditemukan di: " . $path_db);
}

include $path_db;

// Validasi koneksi database
if (!isset($conn)) {
    die("Error: Variabel \$conn tidak ditemukan. Periksa isi file config/database.php kamu.");
}

// Cek apakah form sudah disubmit
if (isset($_POST['nisn_nik']) && isset($_POST['password'])) {
    // Ambil data dari form
    $identitas = mysqli_real_escape_string($conn, $_POST['nisn_nik']);
    $password = $_POST['password'];

    // Query untuk mencari user berdasarkan identitas (NISN/NIK)
    $sql = "SELECT u.*, r.role, a.nama_anggota 
            FROM user u
            JOIN role r ON u.id_role = r.id_role
            JOIN anggota_kelas a ON u.id_anggota = a.id_anggota
            WHERE u.identitas = '$identitas' 
            AND u.status = 'Aktif'";

    $result = mysqli_query($conn, $sql);

    // Cek apakah user ditemukan
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        // Verifikasi password
        if ($password === $data['password']) {
            // Set session data
            $_SESSION['id_user']  = $data['id_user'];
            $_SESSION['nama']     = $data['nama_anggota'];
            $_SESSION['role']     = $data['role'];
            $_SESSION['id_kelas'] = $data['id_kelas'];

            // Tentukan halaman tujuan berdasarkan role
            $role_cek = strtolower(trim($data['role']));
            $target = "";

            if ($role_cek === 'bendahara') {
                $target = "bendahara/dashboard.php";
            } elseif ($role_cek === 'wali kelas' || $role_cek === 'wali_kelas') {
                $target = "wali_kelas/dashboard.php";
            } elseif ($role_cek === 'ketua kelas' || $role_cek === 'ketua_kelas') {
                $target = "ketua_kelas/dashboard.php";
            } else {
                $target = "murid/dashboard.php";
            }

            // Redirect ke dashboard sesuai role
            header("Location: " . $base_url . $target);
            exit();
        } else {
            // Password salah
            echo "<script>alert('Password Salah!'); window.history.back();</script>";
        }
    } else {
        // User tidak ditemukan atau akun tidak aktif
        echo "<script>alert('User tidak ditemukan atau Akun Tidak Aktif!'); window.history.back();</script>";
    }
}
