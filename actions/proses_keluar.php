<?php

/**
 * ========================================
 * PROSES KELUAR - PENGELUARAN KAS
 * ========================================
 * File ini menangani proses pencatatan
 * pengeluaran kas dari bendahara
 */

session_start();
include __DIR__ . "/../config/database.php";

// --- 1. AMBIL DATA DARI FORM ---
$id_kelas = $_SESSION['id_kelas'];
$id_user_bendahara = $_SESSION['id_user'];
$jumlah = $_POST['nominal'] ?? 0;
$keterangan = $_POST['keterangan'] ?? '';
$id_kategori_pengeluaran = 4; // Kategori pengeluaran di database

// --- 2. VALIDASI INPUT ---
if (empty($jumlah) || empty($keterangan)) {
    $_SESSION['error'] = "Gagal! Semua field wajib diisi.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// --- 3. HITUNG SALDO UNTUK VALIDASI ---
$q_masuk = mysqli_query($conn, "SELECT SUM(t.nominal) as total 
                                FROM transaksi t 
                                JOIN kategori k ON t.id_kategori = k.id_kategori 
                                JOIN user u ON t.id_user = u.id_user
                                WHERE u.id_kelas = '$id_kelas' AND k.jenis = 'Masuk'");

$q_keluar = mysqli_query($conn, "SELECT SUM(t.nominal) as total 
                                 FROM transaksi t 
                                 JOIN kategori k ON t.id_kategori = k.id_kategori 
                                 JOIN user u ON t.id_user = u.id_user
                                 WHERE u.id_kelas = '$id_kelas' AND k.jenis = 'Keluar'");

$res_masuk = mysqli_fetch_assoc($q_masuk)['total'] ?? 0;
$res_keluar = mysqli_fetch_assoc($q_keluar)['total'] ?? 0;
$saldo = $res_masuk - $res_keluar;

// Validasi saldo cukup
if ($jumlah > $saldo) {
    $_SESSION['error'] = "Saldo tidak cukup! Sisa saldo: Rp " . number_format($saldo, 0, ',', '.');
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// --- 4. PROSES SIMPAN ---
$bulan_sekarang = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
][(int)date('m') - 1];

$query = mysqli_query($conn, "INSERT INTO transaksi 
          (id_user, id_kategori, nominal, keterangan, bulan, tahun, created_at) 
          VALUES 
          ('$id_user_bendahara', '$id_kategori_pengeluaran', '$jumlah', '$keterangan', '$bulan_sekarang', '" . date('Y') . "', NOW())");

// --- 5. REDIRECT DENGAN SESSION MESSAGE ---
if ($query) {
    $_SESSION['success'] = "Berhasil! Pengeluaran telah dicatat.";
    header("Location: ../bendahara/transaksi_keluar.php");
} else {
    $_SESSION['error'] = "Gagal simpan data: " . mysqli_error($conn);
    header("Location: " . $_SERVER['HTTP_REFERER']);
}
exit;
