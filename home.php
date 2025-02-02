<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<style>
  /* Gaya tambahan untuk body jika diperlukan */
  body {
    min-height: 100vh;
    /* Memastikan body setidaknya setinggi viewport */
    background-image: url('https://wallpaperaccess.com/full/2362734.jpg');
    /* Ganti dengan URL gambar Anda */
    background-size: cover;
    /* Mengatur gambar mengisi seluruh halaman */
    background-position: center;
    /* Memposisikan gambar di tengah */
    background-repeat: no-repeat;
    /* Mencegah gambar diulang */
  }

  .deskripsi-container {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }


  .deskripsi h1 {
    color: #ffffff;
    font-size: 24px;
    margin-top: o;
    justify-content: center;
  }

  .deskripsi p {
    color: #ffffff;
    font-size: 16px;
    justify-content: center;
  }

  .navbar-nav .nav-link:hover {
    color: #000000;
    background-color: #ffffff;
    /* Warna latar belakang saat di-hover */
    box-shadow: 0 4px 8px rgba(106, 17, 203, 0.3);
    /* Bayangan pada hover */
  }

  /* Active Link Styling */
  .navbar-nav .nav-link.active {
    color: #000000;
    background-color: #ffffff;
    /* Warna latar belakang untuk link aktif */
    font-weight: 600;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
  }
</style>
</head>

<body>
  <?php require "navbar.php"; ?>
  <div class="deskripsi-container">
    <h1>Selamat datang, <?php echo $_SESSION['username']; ?>!</h1>
    <p><strong>Selamat datang di web kami, mari assesmen perusahahaan mu!
        Melakukan assessment pada perusahaan menggunakan COBIT 5 sangat penting untuk memastikan bahwa praktik manajemen TI dan pengendalian yang diterapkan berjalan dengan efektif dan efisien. COBIT 5 menyediakan kerangka kerja yang komprehensif untuk menilai dan mengelola risiko TI, memastikan bahwa teknologi yang digunakan mendukung tujuan bisnis, serta menjamin kepatuhan terhadap regulasi yang berlaku. Dengan melakukan assessment menggunakan COBIT 5, perusahaan dapat mengidentifikasi area yang memerlukan perbaikan, meningkatkan efisiensi operasional, dan mengoptimalkan penggunaan sumber daya TI, sehingga dapat mengurangi risiko dan memaksimalkan nilai bisnis dari teknologi yang diterapkan.
      </strong></p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>