<?php
/**
 * ========================================
 * PROSES TAMBAH TRANSAKSI - PEMASUKAN KAS
 * ========================================
 * File ini menangani proses pencatatan
 * pemasukan kas dengan sistem FIFO
 */

session_start();
include __DIR__ . "/../config/database.php";

// --- 1. VALIDASI AKSES ---
if (!isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) !== 'bendahara') {
    header("Location: ../login.php");
    exit;
}

// --- 2. AMBIL DATA DARI FORM ---
$id_kategori   = $_POST['id_kategori'] ?? '';
$id_pembayar   = $_POST['id_user'] ?? '';
$nominal_total = $_POST['nominal'] ?? '';
$bulan         = $_POST['bulan'] ?? '';
$tahun         = $_POST['tahun'] ?? '';
$minggu_input  = $_POST['minggu'] ?? '';
$keterangan    = $_POST['keterangan'] ?? 'Pembayaran Kas';

// --- 3. VALIDASI INPUT ---
// Cek field utama tidak boleh kosong
if (empty($id_pembayar) || empty($id_kategori) || empty($nominal_total) || empty($bulan) || empty($tahun)) {
    $_SESSION['error'] = "Gagal! Semua field wajib diisi.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Cek untuk kategori Kas (ID 1), minggu harus diisi
if ($id_kategori == 1 && empty($minggu_input)) {
    $_SESSION['error'] = "Gagal! Untuk uang kas, target minggu harus dipilih.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Cek nominal harus positif
if ($nominal_total <= 0) {
    $_SESSION['error'] = "Gagal! Nominal harus lebih besar dari 0.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// --- 4. DEKLARASI VARIABEL ---
$tanggal = date('Y-m-d H:i:s');
$berhasil = 0;

// --- 5. LOGIKA BERDASARKAN KATEGORI ---

if ($id_kategori != 1) {
    // KATEGORI DENDA / SUMBANGAN (bukan kas reguler)
    $query_ins = "INSERT INTO transaksi 
                  (id_user, id_kategori, nominal, keterangan, bulan, tahun, minggu, created_at)
                  VALUES 
                  ('$id_pembayar', '$id_kategori', '$nominal_total', '$keterangan', '$bulan', '$tahun', '-', '$tanggal')";

    if (mysqli_query($conn, $query_ins)) {
        $berhasil = 1;
    } else {
        die("Gagal Simpan: " . mysqli_error($conn));
    }
} else {
    // KATEGORI UANG KAS (ID 1) - GUNAKAN LOGIKA FIFO
    $target_minggu = (int) str_replace(['M', '-'], '', $minggu_input);

    // Hitung lubang kosong (minggu yang belum dibayar)
    $lubang_kosong = 0;
    for ($j = 1; $j <= $target_minggu; $j++) {
        $m_cek = "M-$j";
        $sql_cek = "SELECT id_transaksi FROM transaksi 
                    WHERE id_user = '$id_pembayar' 
                    AND bulan = '$bulan' 
                    AND tahun = '$tahun' 
                    AND minggu = '$m_cek' 
                    AND id_kategori = 1";
        $res_cek = mysqli_query($conn, $sql_cek);
        if (mysqli_num_rows($res_cek) == 0) {
            $lubang_kosong++;
        }
    }

    // Eksekusi insert FIFO (isi lubang kosong dari M-1)
    if ($target_minggu > 0 && $lubang_kosong > 0) {
        $nominal_per_minggu = $nominal_total / $lubang_kosong;

        for ($i = 1; $i <= $target_minggu; $i++) {
            $minggu_ins = "M-$i";
            $cek_akhir = mysqli_query($conn, "SELECT id_transaksi FROM transaksi 
                                             WHERE id_user = '$id_pembayar' 
                                             AND bulan = '$bulan' 
                                             AND tahun = '$tahun' 
                                             AND minggu = '$minggu_ins' 
                                             AND id_kategori = 1");

            if (mysqli_num_rows($cek_akhir) == 0) {
                $query_ins = "INSERT INTO transaksi 
                              (id_user, id_kategori, nominal, keterangan, bulan, tahun, minggu, created_at)
                              VALUES 
                              ('$id_pembayar', '1', '$nominal_per_minggu', '$keterangan', '$bulan', '$tahun', '$minggu_ins', '$tanggal')";

                if (mysqli_query($conn, $query_ins)) {
                    $berhasil++;
                }
            }
        }
    }
}

// --- 6. FEEDBACK DAN REDIRECT ---
if ($berhasil > 0) {
    $pesan = ($id_kategori == 1) ? "Berhasil mencatat $berhasil minggu!" : "Berhasil mencatat data transaksi!";
    $_SESSION['success'] = $pesan;
    header("Location: ../bendahara/transaksi_masuk.php");
    exit;
} else {
    $_SESSION['error'] = "Gagal menyimpan transaksi!";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
    // --- Jika Gagal ---
    $_SESSION['error'] = "Gagal menyimpan data. Minggu mungkin sudah lunas atau input tidak valid.";
    header("Location: " . $_SERVER['HTTP_REFERER']); // Balik ke halaman sebelumnya
    exit;
}
