<?php
session_start();
include __DIR__ . "/../config/database.php";

// --- 1. VALIDASI AKSES ---
if (!isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) != 'bendahara') {
    header("Location: ../login.php");
    exit;
}

// --- 2. VALIDASI INPUT KOSONG ---
$id_kategori   = $_POST['id_kategori'] ?? '';
$id_pembayar   = $_POST['id_user'] ?? '';
$nominal_total = $_POST['nominal'] ?? '';
$bulan         = $_POST['bulan'] ?? '';
$tahun         = $_POST['tahun'] ?? '';
$minggu_input  = $_POST['minggu'] ?? '';

// Cek apakah field utama ada yang kosong
if (empty($id_pembayar) || empty($id_kategori) || empty($nominal_total) || empty($bulan) || empty($tahun)) {
    $_SESSION['error'] = "Gagal! Semua field wajib diisi.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Cek khusus untuk Kas (ID 1), minggu tidak boleh kosong
if ($id_kategori == 1 && empty($minggu_input)) {
    $_SESSION['error'] = "Gagal! Untuk uang kas, target minggu harus dipilih.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Cek nominal tidak boleh nol atau negatif
if ($nominal_total <= 0) {
    $_SESSION['error'] = "Gagal! Nominal harus lebih besar dari 0.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// --- 3. DEKLARASI VARIABEL LANJUTAN ---
$keterangan    = $_POST['keterangan'] ?? 'Pembayaran Kas';
$tanggal       = date('Y-m-d H:i:s');
$berhasil      = 0;

// --- 4. PERCABANGAN LOGIKA BERDASARKAN KATEGORI ---

if ($id_kategori != 1) {
    // --- JIKA KATEGORI ADALAH DENDA / SUMBANGAN ---
    $query_ins = "INSERT INTO transaksi (id_user, id_kategori, nominal, keterangan, bulan, tahun, minggu, created_at)
                  VALUES ('$id_pembayar', '$id_kategori', '$nominal_total', '$keterangan', '$bulan', '$tahun', '-', '$tanggal')";

    if (mysqli_query($conn, $query_ins)) {
        $berhasil = 1;
    } else {
        die("Gagal Simpan: " . mysqli_error($conn));
    }
} else {
    // --- JIKA KATEGORI ADALAH UANG KAS (ID 1) -> PAKAI LOGIKA FIFO ---
    $target_minggu = (int) str_replace(['M', '-'], '', $minggu_input);

    // A. Hitung lubang kosong
    $lubang_kosong = 0;
    for ($j = 1; $j <= $target_minggu; $j++) {
        $m_cek = "M-$j";
        $sql_cek = "SELECT id_transaksi FROM transaksi 
                    WHERE id_user = '$id_pembayar' AND bulan = '$bulan' AND tahun = '$tahun' AND minggu = '$m_cek' AND id_kategori = 1";
        $res_cek = mysqli_query($conn, $sql_cek);
        if (mysqli_num_rows($res_cek) == 0) {
            $lubang_kosong++;
        }
    }

    // B. Eksekusi Insert FIFO
    if ($target_minggu > 0 && $lubang_kosong > 0) {
        $nominal_per_minggu = $nominal_total / $lubang_kosong;

        for ($i = 1; $i <= $target_minggu; $i++) {
            $minggu_ins = "M-$i";
            $cek_akhir = mysqli_query($conn, "SELECT id_transaksi FROM transaksi WHERE id_user = '$id_pembayar' AND bulan = '$bulan' AND tahun = '$tahun' AND minggu = '$minggu_ins' AND id_kategori = 1");

            if (mysqli_num_rows($cek_akhir) == 0) {
                $query_ins = "INSERT INTO transaksi (id_user, id_kategori, nominal, keterangan, bulan, tahun, minggu, created_at)
                              VALUES ('$id_pembayar', '1', '$nominal_per_minggu', '$keterangan', '$bulan', '$tahun', '$minggu_ins', '$tanggal')";

                if (mysqli_query($conn, $query_ins)) {
                    $berhasil++;
                }
            }
        }
    }
}

// --- 5. FEEDBACK ---
// --- Jika Berhasil ---
if ($berhasil > 0) {
    $pesan = ($id_kategori == 1) ? "Berhasil mencatat $berhasil minggu!" : "Berhasil mencatat data transaksi!";
    $_SESSION['success'] = $pesan; // Simpan pesan sukses ke session
    header("Location: ../bendahara/transaksi_masuk.php");
    exit;
} else {
    // --- Jika Gagal ---
    $_SESSION['error'] = "Gagal menyimpan data. Minggu mungkin sudah lunas atau input tidak valid.";
    header("Location: " . $_SERVER['HTTP_REFERER']); // Balik ke halaman sebelumnya
    exit;
}
