<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COBIT 5 Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      background-image: url('https://wallpaperaccess.com/full/2362734.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .header {
      background-color: rgba(0, 0, 0, 0.7);
      /* Transparansi pada latar belakang */
      color: white;
      padding: 50px 20px;
      /* Menambah padding sisi untuk responsif */
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
      min-height: 50vh;
      /* Agar header lebih tinggi pada layar besar */
      width: 100%;
    }

    .header .container {
      display: flex;
      align-items: center;
      justify-content: space-between;
      max-width: 1200px;
      margin: 0 auto;
    }

    .header h1 {
      font-size: 2.5rem;
      font-weight: 600;
      line-height: 1.3;
      margin-bottom: 20px;
    }

    .header p {
      font-size: 1.25rem;
      line-height: 1.5;
      max-width: 500px;
      margin-right: 20px;
    }

    .image-container img {
      max-width: 250px;
      height: auto;
      border-radius: 8px;
    }

    @media (max-width: 768px) {
      .header {
        flex-direction: column;
        text-align: center;
      }

      .header .container {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }

      .header h1 {
        font-size: 2rem;
      }

      .header p {
        font-size: 1rem;
        margin: 0;
      }

      .image-container img {
        max-width: 200px;
      }
    }

    .navbar-nav .nav-link:hover {
      color: #000000;
      background-color: #ffffff;
      box-shadow: 0 4px 8px rgba(106, 17, 203, 0.3);
    }

    .navbar-nav .nav-link.active {
      color: #000000;
      background-color: #ffffff;
      font-weight: 600;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>

<body>
  <?php require "navbar.php"; ?>
  <!-- Bagian Atas Halaman -->
  <section class="header">
    <div class="container">
      <div class="text-section">
        <h1>Bagaimana cara memlakukan Asessment?</h1>
        <p>1. masuk ke halaman asessment</p>
        <p>2. Pilih tipe auditor, pilih internal jika assesment mu tertuju pada perusahaan mu sendir dan pilih eksternal jika asessment mu tertuju pada perusahaan orang lain.</p>
        <p>3. input nama perusahaan tujuan asessment</p>
        <p>4. pilih domain sesuai orientasi Asessment</p>
        <p>5. pada halaman analisis pilih sub domain</p>
        <p>6. perhatikan poin pertanyaan sebagai acuan pengisian index</p>
        <p>7. berikan poin pada setiap pertanyaan, simpan</p>
        <p>8. pilih sub domain lain dan lakukan hal yang sama seterusnya hingga pertanyaan pada setiap sub domain telah terisi</p>
        <p>9. hasil akan di tampilkan dalam bentuk diagram</p>
        <h1>ikuti setiap langkah dan ada siap menjadi asessor</h1>
      </div>
      <div class="image-container">
        <img src="https://logodix.com/logo/2073902.png" type="png" width="500" height="500">
      </div>
    </div>
  </section>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>