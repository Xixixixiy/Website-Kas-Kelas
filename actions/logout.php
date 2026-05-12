<?php
session_start();

// Hapus semua data session
session_unset();

// Hancurkan session
session_destroy();

// Arahkan kembali ke halaman login utama
// Kita asumsikan logout.php ada di folder 'actions', jadi kita keluar dulu satu level
header("Location: ../login.php");
exit();
