<?php

/**
 * ========================================
 * NAVBAR - NAVIGATION BAR
 * ========================================
 * Template navigasi utama untuk semua halaman
 * Menampilkan menu sesuai dengan role user
 */

// Ambil nama file yang sedang aktif untuk highlighting menu
$current_page = basename($_SERVER['PHP_SELF']);

// Bersihkan role dari session
$raw_role = isset($_SESSION['role']) ? strtolower(trim($_SESSION['role'])) : '';

// Tentukan folder berdasarkan role
$role_folder = '';
if ($raw_role === 'bendahara') {
    $role_folder = 'bendahara';
} elseif ($raw_role === 'wali kelas' || $raw_role === 'wali_kelas') {
    $role_folder = 'wali_kelas';
} elseif ($raw_role === 'ketua kelas' || $raw_role === 'ketua_kelas') {
    $role_folder = 'ketua_kelas';
} elseif ($raw_role === 'murid') {
    $role_folder = 'murid';
}

// Pastikan base_url diakhiri dengan satu slash saja
$base = rtrim($base_url, '/') . '/';
?>

<nav class="navbar navbar-expand-lg bg-white shadow-sm mb-4">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fw-bold text-primary" href="<?= $base . $role_folder ?>/dashboard.php">
            <i class="bi bi-wallet2 me-2"></i>Kas Kelas
        </a>

        <!-- Toggle Button (Mobile) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Dashboard (Untuk Semua Role) -->
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'dashboard.php') ? 'active' : '' ?>"
                        href="<?= $base . $role_folder ?>/dashboard.php">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                </li>

                <!-- Menu Kelola Kas (Hanya Bendahara) -->
                <?php if ($raw_role === 'bendahara'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'transaksi_masuk.php') ? 'active' : '' ?>"
                            href="<?= $base ?>bendahara/transaksi_masuk.php">
                            <i class="bi bi-cash-coin me-1"></i>Kelola Kas
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Menu Kelola Siswa (Hanya Wali Kelas) -->
                <?php if ($raw_role === 'wali kelas' || $raw_role === 'wali_kelas'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'kelola_siswa.php') ? 'active' : '' ?>"
                            href="<?= $base ?>wali_kelas/kelola_siswa.php">
                            <i class="bi bi-people me-1"></i>Kelola Siswa
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Menu Status Kas (Bendahara, Wali Kelas, Ketua Kelas) -->
                <?php if ($raw_role !== 'murid'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'status_kas.php') ? 'active' : '' ?>"
                            href="<?= $base ?>status_kas.php">
                            <i class="bi bi-graph-up me-1"></i>Status Kas
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Menu Detail Kas (Untuk Semua Role) -->
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'detail_kas.php') ? 'active' : '' ?>"
                        href="<?= $base ?>detail_kas.php">
                        <i class="bi bi-receipt me-1"></i>Detail Kas
                    </a>
                </li>

                <!-- Badge Mode Pantau (Hanya Ketua Kelas) -->
                <?php if ($raw_role === 'ketua kelas' || $raw_role === 'ketua_kelas'): ?>
                    <li class="nav-item">
                        <span class="nav-link disabled text-primary fw-bold">
                            <i class="bi bi-eye me-1"></i>Mode Pantau
                        </span>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- User Info & Logout -->
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">
                    <i class="bi bi-person-circle me-1"></i><?php echo $_SESSION['nama']; ?>
                    <span class="badge bg-info text-dark"><?= ucwords($raw_role) ?></span>
                </span>
                <a href="<?= $base ?>actions/logout.php" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </a>
            </div>
        </div>
    </div>
</nav>