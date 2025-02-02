<?php
$host = 'localhost'; // Sesuaikan host jika diperlukan
$user = 'root'; // Sesuaikan dengan user MySQL
$pass = ''; // Masukkan password MySQL Anda
$dbname = 'audit_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}
