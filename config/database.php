<?php

/**
 * ========================================
 * DATABASE CONFIGURATION
 * ========================================
 * File ini berisi konfigurasi koneksi database
 * untuk aplikasi Sistem Informasi Kas Kelas
 */

// Konfigurasi Database MySQL
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "db_kas_v2";

// Buat koneksi ke database
$conn = mysqli_connect($servername, $username, $password, $db_name);

// Cek apakah koneksi berhasil
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Base URL untuk aplikasi
$base_url = "http://localhost/projectKasKelas/";
