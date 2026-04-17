<?php
$servername = "localhost";
$username = "root"; // Ganti dengan username phpMyAdmin Anda
$password = "";     // Ganti dengan password phpMyAdmin Anda
$dbname = "absensi_siswa";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
