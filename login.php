<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Query untuk cek username dan password
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Login berhasil
    $_SESSION['username'] = $username;
    header("Location: home.php"); // Redirect ke halaman index.php
    exit();
  } else {
    echo "Username atau password salah.";
  }

  $stmt->close();
}
