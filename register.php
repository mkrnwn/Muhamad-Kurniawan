<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Validasi apakah username atau email sudah ada
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
  $stmt->bind_param("ss", $username, $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    echo "Username atau email sudah digunakan.";
  } else {
    // Masukkan data baru ke database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);
    if ($stmt->execute()) {
      echo "Registrasi berhasil. Silahkan <a href='loginpage.php'>login</a>.";
    } else {
      echo "Terjadi kesalahan, coba lagi.";
    }
  }

  $stmt->close();
}
