<?php

/**
 * ========================================
 * WALI KELAS DASHBOARD
 * ========================================
 * Halaman dashboard wali kelas yang menampilkan
 * ringkasan saldo, partisipasi murid, dan grafik kas
 */

session_start();
include __DIR__ . "/../config/database.php";

// --- VALIDASI AKSES ---
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'wali_kelas') {
    header("Location: ../login.php");
    exit;
}

$id_kelas = $_SESSION['id_kelas'];

// --- 1. HITUNG TOTAL PEMASUKAN & PENGELUARAN ---
$query_pemasukan = mysqli_query($conn, "
    SELECT SUM(t.nominal) as total
    FROM transaksi t
    JOIN user u ON t.id_user = u.id_user
    JOIN kategori k ON t.id_kategori = k.id_kategori
    WHERE u.id_kelas = '$id_kelas' AND k.jenis = 'Masuk'
");
$pemasukan = mysqli_fetch_assoc($query_pemasukan)['total'] ?? 0;

$query_pengeluaran = mysqli_query($conn, "
    SELECT SUM(t.nominal) as total 
    FROM transaksi t
    JOIN user u ON t.id_user = u.id_user
    JOIN kategori k ON t.id_kategori = k.id_kategori
    WHERE u.id_kelas = '$id_kelas' AND k.jenis = 'Keluar'
");
$pengeluaran = mysqli_fetch_assoc($query_pengeluaran)['total'] ?? 0;

$saldo = $pemasukan - $pengeluaran;

// --- 2. HITUNG STATISTIK PARTISIPASI MURID ---
$q_total_murid = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM user 
    WHERE id_kelas = '$id_kelas' AND id_role = 1 AND status = 'Aktif'
");
$total_murid = mysqli_fetch_assoc($q_total_murid)['total'] ?? 0;

// Hitung berapa murid yang sudah pernah membayar tahun ini
$q_sudah_bayar = mysqli_query($conn, "
    SELECT COUNT(DISTINCT t.id_user) as total 
    FROM transaksi t
    JOIN user u ON t.id_user = u.id_user
    WHERE u.id_kelas = '$id_kelas' AND u.id_role = 1 AND t.tahun = '" . date('Y') . "'
");
$sudah_bayar = mysqli_fetch_assoc($q_sudah_bayar)['total'] ?? 0;

// --- 3. AMBIL RIWAYAT TRANSAKSI TERAKHIR ---
$transaksi = mysqli_query($conn, "
    SELECT t.*, k.nama_kategori, k.jenis, a.nama_anggota 
    FROM transaksi t
    JOIN kategori k ON t.id_kategori = k.id_kategori
    JOIN user u ON t.id_user = u.id_user
    JOIN anggota_kelas a ON u.id_anggota = a.id_anggota
    WHERE u.id_kelas = '$id_kelas'
    ORDER BY t.created_at DESC 
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Wali Kelas - Kas Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body class="bg-light">
    <?php include "../layout/navbar.php"; ?>

    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Dashboard Kelas</h4>
                <p class="text-muted small">Monitoring keuangan dan partisipasi siswa</p>
            </div>
            <span class="text-muted">Periode: <?= date('Y') ?></span>
        </div>

        <!-- Row 1: Saldo & Partisipasi -->
        <div class="row">
            <!-- Total Saldo -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <small class="text-muted text-uppercase fw-semibold">
                            <i class="bi bi-wallet2"></i> Total Saldo
                        </small>
                        <h1 class="fw-bold mt-3 text-primary">
                            Rp <?php echo number_format($saldo, 0, ',', '.'); ?>
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Partisipasi Murid -->
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">
                            <i class="bi bi-people"></i> Partisipasi Pembayaran Murid
                        </small>
                        <?php $persen = $total_murid > 0 ? ($sudah_bayar / $total_murid) * 100 : 0; ?>
                        <div class="progress mt-3" style="height: 20px; border-radius: 10px;">
                            <div class="progress-bar bg-success" style="width: <?php echo round($persen); ?>%">
                                <?php echo round($persen); ?>%
                            </div>
                        </div>
                        <p class="mt-2 small text-muted mb-0">
                            <strong><?php echo $sudah_bayar; ?></strong> dari
                            <strong><?php echo $total_murid; ?></strong> siswa telah membayar tahun ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Grafik & Riwayat -->
        <div class="row">
            <!-- Grafik Kas -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 p-3">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-graph-up"></i> Perbandingan Kas
                    </h5>
                    <div style="height: 300px;">
                        <canvas id="perbandinganKasChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Riwayat Transaksi Terakhir -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold border-bottom">
                        <i class="bi bi-clock-history"></i> Transaksi Terakhir
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($transaksi && mysqli_num_rows($transaksi) > 0) {
                                        while ($row = mysqli_fetch_assoc($transaksi)) { ?>
                                            <tr>
                                                <td><?php echo $row['nama_anggota']; ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo ($row['jenis'] === 'Masuk' ? 'success' : 'danger'); ?>">
                                                        <?php echo $row['nama_kategori']; ?>
                                                    </span>
                                                </td>
                                                <td class="fw-semibold">
                                                    Rp <?php echo number_format($row['nominal'], 0, ',', '.'); ?>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox"></i> Belum ada transaksi
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script>
        const ctx = document.getElementById('perbandinganKasChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pemasukan', 'Pengeluaran'],
                datasets: [{
                    data: [<?php echo $pemasukan; ?>, <?php echo $pengeluaran; ?>],
                    backgroundColor: ['#198754', '#dc3545'],
                    borderColor: ['#198754', '#dc3545'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    </script>

    <?php include "../layout/notifikasi.php"; ?>
</body>

</html>