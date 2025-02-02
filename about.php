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
            max-width: 400px;
            height: auto;
            border-radius: 8px;

            top: 0;
            right: 0;
            margin: 20px;
            /* Memberikan jarak dari tepi */
            z-index: 1000;
            /* Memastikan gambar tetap di atas elemen lainnya */
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
                <h1>Apa itu COBIT5?</h1>
                <p>COBIT 5 adalah kerangka kerja yang komprehensif untuk tata kelola dan manajemen Teknologi Informasi (TI) di perusahaan. COBIT 5 membantu dalam pencapaian tujuan perusahaan melalui pengelolaan dan tata kelola TI yang efektif dan efisien.</p>
                <p>Website ini dirancang untuk membantu perusahaan dalam mengevaluasi, mengarahkan, dan memantau (EDM) kinerja TI berdasarkan kerangka kerja COBIT 5. Dengan fitur analisis berbasis domain seperti EDM, APO, BAI, DSS, dan MEA, perusahaan dapat memantau kinerja TI secara komprehensif.</p>

                <h1>Berikut adalah penjelasan lebih rinci tentang masing-masing domain COBIT 5:</h1>
                <p>EDM (Evaluate, Direct, and Monitor), domain EDM berfokus pada evaluasi, pengarahan, dan pemantauan kinerja TI dalam organisasi. Tujuan utama dari domain ini adalah untuk memastikan bahwa TI memberikan nilai yang optimal bagi organisasi dan mendukung pencapaian tujuan strategis. EDM membantu manajemen untuk mengevaluasi apakah TI berada di jalur yang benar dalam mendukung keputusan dan kebijakan perusahaan, serta memonitor keberlanjutan dan efektivitas dari inisiatif TI yang ada. Domain ini juga bertugas untuk memastikan bahwa tujuan bisnis dan TI selaras serta bahwa TI digunakan untuk mengurangi risiko dan memaksimalkan peluang.</p>
                <p>APO (Align, Plan, and Organize), domain APO memastikan bahwa pengelolaan TI diselaraskan dengan kebutuhan dan strategi bisnis organisasi. Domain ini melibatkan perencanaan jangka panjang dan pengorganisasian sumber daya TI untuk mendukung tujuan bisnis yang lebih luas. APO mencakup berbagai aspek, termasuk pengelolaan risiko TI, pengelolaan anggaran TI, serta perencanaan sumber daya yang efisien. Selain itu, domain ini memastikan bahwa TI dikelola dengan cara yang memperhatikan kepatuhan terhadap peraturan yang berlaku dan menjaga keberlanjutan organisasi melalui pemanfaatan teknologi yang tepat.</p>
                <p>BAI (Build, Acquire, and Implement), domain BAI berfokus pada pembangunan, akuisisi, dan implementasi solusi TI yang sesuai dengan kebutuhan organisasi. Tujuannya adalah untuk memastikan bahwa proyek TI dilaksanakan dengan cara yang tepat, sesuai waktu, biaya, dan kualitas yang diinginkan. BAI mencakup pengelolaan siklus hidup pengembangan perangkat lunak, pemilihan teknologi yang tepat, serta manajemen perubahan yang terjadi saat implementasi sistem baru. Selain itu, domain ini bertugas untuk memastikan bahwa sistem TI yang baru dapat beroperasi dengan efektif dan dapat memenuhi ekspektasi pengguna dan bisnis.</p>
                <p>DSS (Deliver, Service, and Support), domain DSS berfokus pada pengiriman, layanan, dan dukungan TI yang konsisten dan efektif. Tujuan utama domain ini adalah untuk memastikan bahwa layanan TI berjalan dengan lancar dan dapat mendukung operasional bisnis sehari-hari tanpa gangguan. DSS mencakup pengelolaan layanan TI, pengendalian kualitas layanan, serta pengelolaan insiden dan masalah yang dapat mengganggu operasi bisnis. Domain ini juga bertanggung jawab untuk memberikan dukungan berkelanjutan kepada pengguna TI, memastikan pemecahan masalah yang cepat, dan menjaga kepuasan pengguna dengan layanan TI yang disediakan.</p>
                <p>MEA (Monitor, Evaluate, and Assess), domain MEA bertanggung jawab untuk memantau, mengevaluasi, dan menilai kinerja serta efektivitas dari seluruh aktivitas dan kontrol TI dalam organisasi. Domain ini memastikan bahwa kebijakan, prosedur, dan kontrol yang diterapkan berjalan dengan baik dan sesuai dengan tujuan yang diinginkan. MEA bertujuan untuk menyediakan laporan yang objektif mengenai kinerja TI, termasuk audit dan evaluasi terhadap sistem yang ada, serta membantu dalam pengambilan keputusan terkait peningkatan sistem TI. Evaluasi berkala juga memastikan bahwa TI tetap relevan dan dapat diadaptasi dengan perubahan yang terjadi dalam organisasi atau lingkungan eksternal.</p>
                <h1>Sesuaikan domain dengan kebutuhan dan orientasi asessment mu!</h1>
            </div>
            <div class="image-container">
                <img src="https://logodix.com/logo/2073902.png" type="png" width="500" height="500">
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>