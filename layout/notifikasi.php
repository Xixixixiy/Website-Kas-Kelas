<?php

/**
 * ========================================
 * NOTIFIKASI - SWEETALERT2
 * ========================================
 * Template notifikasi menggunakan SweetAlert2
 * Menampilkan pesan success atau error dari session
 */
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['error'])): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: '<?= $_SESSION['error'] ?>',
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    </script>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= $_SESSION['success'] ?>',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>