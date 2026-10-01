<?php
// ================================================
// KONEKSI.PHP — Jembatan ke database lapor_sini
// ================================================

// Zona waktu WIB
date_default_timezone_set('Asia/Jakarta');

// Konfigurasi
$host = "localhost";
$port = 3307; // Port standar MySQL XAMPP
$user = "root";
$pass = ""; // Default XAMPP: kosong
$nama_db = "lapor_sini";

// Buat koneksi + tambah pengecekan lebih lengkap
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Tampilkan error jelas

try {
    $koneksi = new mysqli($host, $user, $pass, $nama_db, $port);
    $koneksi->set_charset("utf8mb4");
    $koneksi->query("SET time_zone = '+07:00'");
} catch (mysqli_sql_exception $e) {
    die("<strong>Koneksi Gagal!</strong><br>Periksa: <br>1. Apakah MySQL sudah dijalankan di XAMPP Control Panel?<br>2. Apakah database <b>$nama_db</b> sudah dibuat?<br><br>Pesan Error: " . $e->getMessage());
}
?>