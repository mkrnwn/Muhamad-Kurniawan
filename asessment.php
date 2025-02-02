<?php
// Koneksi ke database baru
$conn = new mysqli('localhost', 'root', '', 'audit_db');

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Memulai sesi
session_start();

// Variabel default
$error_msg = '';
$audit = '';
$company = '';

// Cek jika form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['audit']) && !empty($_POST['company'])) {
        $audit = filter_var($_POST['audit'], FILTER_SANITIZE_STRING);
        $company = filter_var($_POST['company'], FILTER_SANITIZE_STRING);

        // Simpan audit dan company di sesi
        $_SESSION['audit'] = $audit;
        $_SESSION['company'] = $company;

        // Arahkan ke halaman domain yang dipilih
        $domain = $_POST['domain'];

        // Redirect ke halaman yang sesuai berdasarkan domain yang dipilih
        switch ($domain) {
            case 'EDM':
                header("Location: result_edm.php");
                break;
            case 'APO':
                header("Location: result_apo.php");
                break;
            case 'BAI':
                header("Location: result_bai.php");
                break;
            case 'DSS':
                header("Location: result_dss.php");
                break;
            case 'MEA':
                header("Location: result_mea.php");
                break;
            default:
                echo "Domain tidak valid!";
                break;
        }
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COBIT 5 Dashboard - Analysis</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
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

        .container {
            background-color: transparent !important;
        }


        .scroll-section h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #ffffff;
            font-weight: 600;
        }

        .scroll-section .btn-group {
            display: flex;
            gap: 15px;
        }

        .domain-btn {
            background-color: #8e44ad;
            color: white;
            padding: 20px;
            font-size: 1.2rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        .domain-btn:hover {
            transform: scale(1.05);
            background-color: #732d91;
        }

        .form-section form {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-control {
            margin-bottom: 20px;
            padding: 15px;
            font-size: 1.1rem;
        }

        .input-group {
            display: flex;
            gap: 10px;
        }

        .input-group input {
            flex: 1;
            padding: 15px;
            font-size: 1.1rem;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            position: absolute;
            top: 30px;
            right: 70px;
        }


        /* Navbar Items Style */
        .navbar-nav {
            flex-direction: row;
            gap: 20px;
        }

        .navbar-nav .nav-link {
            color: #000000;
            /* Warna teks kalem untuk keterbacaan */
            font-size: 1.1rem;
            font-weight: 500;
            padding: 0.5rem 1.2rem;
            text-decoration: none;
            /* Menghilangkan garis bawah */
            border-radius: 10px;
            transition: color 0.3s ease, background-color 0.3s ease;
            position: relative;
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

        /* Centering Navbar Items */
        .navbar-collapse {
            display: flex;
            justify-content: left;
        }


        /* Form Input Style */
        .scroll-section {

            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 50px;
            /* Hapus atau ubah background-color */
            background-color: rgba(0, 0, 0, 0.7);
            /* Transparan */


        }

        .scroll-section h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #ffffff;
            font-weight: 600;
        }

        .input-group {
            display: flex;
            gap: 15px;
            margin-bottom: 1.5rem;
        }

        .input-group select,
        .input-group input {
            flex: 1;
            padding: 12px;
            font-size: 1rem;
            border-radius: 8px;
            border: 1px solid #bdc3c7;
            background-color: #ffffff;
            transition: box-shadow 0.3s, border-color 0.3s;
        }

        .input-group select:focus,
        .input-group input:focus {
            border-color: #8e44ad;
            box-shadow: 0px 4px 8px rgba(142, 68, 173, 0.2);
        }

        /* Button Group Style */
        .btn-group {
            display: flex;
            gap: 15px;
        }

        .domain-btn {
            background-color: #FF0000;
            color: white;
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        .domain-btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>

    <?php require "navbar.php"; ?>

    <!-- Halaman Kedua untuk Analisis -->
    <section id="scroll-to-analysis" class="scroll-section">
        <div class="container text-center">
            <h2>Mulai Analisis Anda</h2>
            <form method="POST" action="">
                <div class="input-group">
                <input type="text" name="company" placeholder="Company Name" value="<?php echo htmlspecialchars($company); ?>" required>
                    <select name="audit" class="form-control" required>
                        <option value="" disabled selected>Pilih Tipe Auditor</option>
                        <option value="Internal" <?php echo ($audit == "Internal") ? "selected" : ""; ?>>Internal</option>
                        <option value="External" <?php echo ($audit == "External") ? "selected" : ""; ?>>External</option>
                    </select>
                    
                </div>

                <div class="btn-group ">
                    <button type="submit" name="domain" value="EDM" class="domain-btn bg-danger">EDM</button>
                    <button type="submit" name="domain" value="APO" class="domain-btn bg-danger">APO</button>
                    <button type="submit" name="domain" value="BAI" class="domain-btn bg-danger">BAI</button>
                    <button type="submit" name="domain" value="DSS" class="domain-btn bg-danger">DSS</button>
                    <button type="submit" name="domain" value="MEA" class="domain-btn bg-danger">MEA</button>
                </div>
            </form>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>