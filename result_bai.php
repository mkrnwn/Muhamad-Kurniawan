<?php
session_start();
include 'db_connect.php';

// Daftar aktivitas BAI dan pernyataan terkait
$bai_activities = [
    'BAI01' => [
        'BAI01.01 Maintain a standard approach for programme and project management.',
        'BAI01.02 Initiate a programme.',
        'BAI01.03 Manage stakeholder engagement.',
        'BAI01.04 Develop and maintain the programme plan.',
        'BAI01.05 Launch and execute the programme.',
        'BAI01.06 Monitor, control and report on the programme outcomes.',
        'BAI01.07 Start up and initiate projects within a programme.',
        'BAI01.08 Plan projects.',
        'BAI01.09 Manage programme and project quality.',
        'BAI01.10 Manage programme and project risk.',
        'BAI01.11 Monitor and control projects.',
        'BAI01.12 Manage project resources and work packages.',
        'BAI01.13 Close a project or iteration.',
        'BAI01.14 Close a programme.',
    ],
    'BAI02' => [
        'BAI02.01 Define and maintain business functional and technical requirements.',
        'BAI02.02 Perform a feasibility study and formulate alternative solutions.',
        'BAI02.03 Manage requirements risk.',
        'BAI02.04 Obtain approval of requirements and solutions.',
    ],
    'BAI03' => [
        'BAI03.01 Design high-level solutions.',
        'BAI03.02 Design detailed solution components.',
        'BAI03.03 Develop solution components.',
        'BAI03.04 Procure solution components.',
        'BAI03.05 Build solutions.',
        'BAI03.06 Perform quality assurance.',
        'BAI03.07 Prepare for solution testing.',
        'BAI03.08 Execute solution testing.',
        'BAI03.09 Manage changes to requirements.',
        'BAI03.10 Maintain solutions.',
        'BAI03.11 Define IT services and maintain the service portfolio.',
    ],
    'BAI04' => [
        'BAI04.01 Assess current availability, performance and capacity and create a baseline.',
        'BAI04.02 Assess business impact.',
        'BAI04.03 Plan for new or changed service requirements.',
        'BAI04.04 Monitor and review availability and capacity.',
        'BAI04.05 Investigate and address availability, performance and capacity issues.',
    ],
    'BAI05' => [
        'BAI05.01 Establish the desire to change.',
        'BAI05.02 Form an effective implementation team.',
        'BAI05.03 Communicate desired vision.',
        'BAI05.04 Empower role players and identify short-term wins.',
        'BAI05.05 Enable operation and use.',
        'BAI05.06 Embed new approaches.',
        'BAI05.07 Sustain changes.',
    ],
    'BAI06' => [
        'BAI06.01 Evaluate, prioritise and authorise change requests.',
        'BAI06.02 Manage emergency changes.',
        'BAI06.03 Track and report change status.',
        'BAI06.04 Close and document the changes.',
    ],
    'BAI07' => [
        'BAI07.01 Establish an implementation plan.',
        'BAI07.02 Plan business process, system and data conversion.',
        'BAI07.03 Plan acceptance tests.',
        'BAI07.04 Establish a test environment.',
        'BAI07.05 Perform acceptance tests.',
        'BAI07.06 Promote to production and manage releases.',
        'BAI07.07 Provide early production support.',
        'BAI07.08 Perform a post-implementation review.',
    ],
    'BAI08' => [
        'BAI08.01 Nurture and facilitate a knowledge-sharing culture.',
        'BAI08.02 Identify and classify sources of information.',
        'BAI08.03 Organise and contextualise information into knowledge.',
        'BAI08.04 Use and share knowledge.',
        'BAI08.05 Evaluate and retire information.',
    ],
    'BAI09' => [
        'BAI09.01 Identify and record current assets.',
        'BAI09.02 Manage critical assets.',
        'BAI09.03 Manage the asset life cycle.',
        'BAI09.04 Optimise asset costs.',
        'BAI09.05 Manage licences.',
    ],
    'BAI10' => [
        'BAI10.01 Establish and maintain a configuration model.',
        'BAI10.02 Establish and maintain a configuration repository and baseline.',
        'BAI10.03 Maintain and control configuration items.',
        'BAI10.04 Produce status and configuration reports.',
        'BAI10.05 Verify and review integrity of the configuration repository.',
    ],
];

// Save user responses to session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save'])) {
        foreach ($bai_activities as $activity => $statements) {
            foreach ($statements as $index => $statement) {
                $input_name = $activity . '_' . $index;
                if (isset($_POST[$input_name])) {
                    $_SESSION['answers'][$activity][$index] = intval($_POST[$input_name]);
                }
            }
        }
    } elseif (isset($_POST['reset'])) {
        unset($_SESSION['answers']);
    }
}

// Initialize variables
$selected_activity = isset($_GET['activity']) ? $_GET['activity'] : 'BAI01';
$selected_statements = isset($bai_activities[$selected_activity]) ? $bai_activities[$selected_activity] : [];

// Function to calculate the average score for each activity
function calculateAverageScore($answers) {
    $total = array_sum($answers);
    $count = count($answers);
    return $count > 0 ? $total / $count : 0;
}

// Function to generate an explanation based on the average score
function getScoreExplanation($averageScore) {
    if ($averageScore >= 4.0) {
        return "Optimized";
    } elseif ($averageScore >= 3.0) {
        return "Established";
    } elseif ($averageScore >= 2.0) {
        return "Managed";
    } elseif ($averageScore >= 1.0) {
        return "Performed";
    } else {
        return "Incomplete";
    }
}

// Calculate average scores and gaps
$average_scores = [];
$gap_score = [];
$total_score = [];
$max_score = 5; // Define the maximum possible score per activity
$desired_score = 5; // Desired maturity level

foreach ($bai_activities as $activity => $statements) {
    if (isset($_SESSION['answers'][$activity])) {
        $average_scores[$activity] = calculateAverageScore($_SESSION['answers'][$activity]);
        $gap_score[$activity] = $desired_score - $average_scores[$activity];
        $total_score[$activity] = array_sum($_SESSION['answers'][$activity]);
    } else {
        $average_scores[$activity] = 0;
        $gap_score[$activity] = $desired_score;
        $total_score[$activity] = 0;
    }
}

// Save results to the database
$company_name = 'YourCompany';
$audit_name = 'COBIT BAI Audit';
$domain = 'BAI';

if (isset($_POST['save_project']) && isset($_SESSION['answers']) && is_array($_SESSION['answers'])) {
    foreach ($_SESSION['answers'] as $activity => $answers) {
        if (isset($bai_activities[$activity])) {
            $average_score = calculateAverageScore($answers);
            $gap = $desired_score - $average_score;
            $explanation = getScoreExplanation($average_score);

            $sql = "INSERT INTO audit_data (company_name, audit_name, domain, activity_domain, total_score, gap, explanation)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssdds", $company_name, $audit_name, $domain, $activity, $average_score, $gap, $explanation);

            if (!$stmt->execute()) {
                echo "Error: " . $stmt->error;
            }
        }
    }

}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COBIT 5 Analysis Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-image: url('https://wallpaperaccess.com/full/2362734.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            opacity: 0.9;
        }

        h1,
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        table,
        th,
        td {
            border: 1px solid #444;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #ff0000;
            /* Merah */
        }

        canvas {
            max-width: 100%;
            background-color: #f5f5f5;
            border-radius: 8px;
            padding: 10px;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }

        label {
            font-weight: bold;
            margin-right: 10px;
            color: #ffffff;
        }

        select {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #444;
            background-color: #333;
            color: #ffffff;
            font-size: 16px;
        }

        .radio-group {
            display: flex;
            justify-content: center;
            margin: 10px 0;
        }

        .radio-group input[type="radio"] {
            display: none;
        }

        .radio-group label {
            padding: 10px 15px;
            margin-right: 5px;
            border: 1px solid #444;
            border-radius: 4px;
            background-color: #555;
            cursor: pointer;
        }

        .radio-group input[type="radio"]:checked+label {
            background-color: #ff0000;
            /* Merah */
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff0000;
            /* Merah */
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            position: absolute;
            top: 30px;
            left: 70px;
        }

        .result-summary {
            margin-top: 30px;
            background-color: #2e2e2e;
            padding: 20px;
            border-radius: 8px;
        }

        .result-summary h3 {
            color: #ffffff;
            margin-bottom: 20px;
            text-align: center;
        }

        .result-summary table {
            width: 100%;
            margin-top: 20px;
        }

        .result-summary th,
        .result-summary td {
            padding: 10px;
            border: 1px solid #444;
            text-align: center;
        }

        .result-summary th {
            background-color: #ff0000;
            /* Merah */
            color: #ffffff;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .button-group button {
            padding: 10px 20px;
            background-color: #ff0000;
            /* Merah */
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .button-group button:hover {
            background-color: #cc0000;
            /* Merah lebih gelap */
        }
       /* Container Utama untuk Aktivitas */
/* Container Utama untuk Aktivitas */
.activity-container {
  display: grid; /* Gunakan grid untuk kontrol baris dan kolom */
  grid-template-columns: repeat(2, 1fr); /* 2 kolom dalam satu baris */
  grid-auto-rows: auto; /* Tinggi baris otomatis sesuai isi */
  gap: 15px; /* Jarak antar elemen */
  justify-content: center; /* Pusatkan grid dalam canvas */
  padding: 20px; /* Ruang di sekitar container */
  max-width: 100%; /* Pastikan tidak melewati lebar canvas */
  box-sizing: border-box; /* Termasuk padding dalam lebar total */
}

/* Card untuk Setiap Aktivitas */
.edm-activity {
  background-color: #000000;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  padding: 20px;
  color: #ffffff; /* Warna teks */
  text-align: left; /* Teks rata kiri */
}

/* Tombol untuk Menampilkan Pedoman */
.toggle-guideline {
  margin-top: 10px;
  cursor: pointer;
  background-color: #4CAF50;
  color: white;
  border: none;
  padding: 10px 15px;
  text-align: center;
  font-size: 14px;
  border-radius: 5px;
  width: 100%; /* Tombol penuh mengikuti lebar card */
  box-sizing: border-box;
}

.toggle-guideline:hover {
  background-color: #45a049;
}

/* Pedoman yang Ditampilkan atau Disembunyikan */
.guidelines {
  display: none; /* Awalnya disembunyikan */
  margin-top: 15px;
}

.guidelines ul {
  list-style-type: disc;
  padding-left: 20px;
}

.guidelines li {
  font-size: 17px;
  color: #ffffff;
}

/* Batas Baris: Tampilkan Maksimal 5 Baris */
.activity-container {
  max-height: calc(5 * auto + 60px); /* 5 baris, + padding dan gap */
  overflow-y: auto; /* Tambahkan scroll jika isi lebih dari 5 baris */
}


    </style>
</head>
<body>
    <div class="container">
        <h1>COBIT 5 Analysis for BAI</h1>
        <a href="home.php" class="back-button">Kembali</a>

        <form method="get" action="">
            <label for="activity">Select BAI Activity:</label>
            <select name="activity" id="activity" onchange="this.form.submit()">
                <?php foreach ($bai_activities as $activity => $statements): ?>
                    <option value="<?php echo $activity; ?>" <?php if ($selected_activity == $activity) echo 'selected'; ?>><?php echo $activity; ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php
// Data aktivitas dan pertanyaan untuk domain BAI (Build, Acquire, and Implement)
$bai_activities = [
   "BAI01 - Manage Programs and Projects" => [
        "Apakah program dan proyek TI direncanakan dan dipantau dengan baik?",
        "Bagaimana organisasi memastikan pendekatan standar diterapkan dalam manajemen program dan proyek?",
        "Bagaimana keterlibatan pemangku kepentingan dikelola dalam setiap tahap proyek?",
        "Apakah rencana program diperbarui secara berkala sesuai kebutuhan?",
        "Bagaimana pelaksanaan program dilakukan untuk mencapai hasil yang diinginkan?",
        "Apakah hasil program dipantau dan dilaporkan secara berkala?",
        "Bagaimana organisasi memulai dan mengelola proyek dalam suatu program?",
        "Apakah setiap proyek direncanakan dengan mempertimbangkan sumber daya dan risiko?",
        "Bagaimana kualitas program dan proyek dijaga selama pelaksanaan?",
        "Apakah risiko dalam program dan proyek dikelola secara proaktif?",
        "Bagaimana status proyek dipantau dan dikontrol untuk memastikan penyelesaian tepat waktu?",
        "Apakah sumber daya proyek dialokasikan dan dikelola secara efisien?",
        "Apakah proyek yang selesai ditutup dengan dokumentasi lengkap?",
        "Bagaimana proses penutupan program dilakukan untuk memastikan pembelajaran diterapkan?",
    ],
    "BAI02 - Manage Requirements Definition" => [
        "Apakah kebutuhan bisnis didefinisikan dan dikelola secara sistematis?",
        "Bagaimana studi kelayakan dilakukan untuk solusi alternatif?",
        "Apakah risiko terkait kebutuhan diidentifikasi dan dikelola secara efektif?",
        "Bagaimana organisasi memperoleh persetujuan untuk kebutuhan dan solusi yang diusulkan?",
    ],
    "BAI03 - Manage Solutions Identification and Build" => [
        "Apakah solusi tingkat tinggi dirancang dengan mempertimbangkan kebutuhan bisnis?",
        "Bagaimana rincian komponen solusi dirancang sebelum pengembangan?",
        "Apakah pengembangan komponen solusi dilakukan dengan standar yang jelas?",
        "Bagaimana organisasi menangani pengadaan komponen solusi yang dibutuhkan?",
        "Apakah solusi dibangun dengan fokus pada efisiensi dan kualitas?",
        "Bagaimana organisasi melakukan jaminan kualitas terhadap solusi?",
        "Apakah persiapan untuk pengujian solusi dilakukan secara memadai?",
        "Bagaimana pengujian solusi dilaksanakan untuk memastikan kelayakan?",
        "Apakah perubahan kebutuhan tercatat dan disesuaikan dalam pengembangan solusi?",
        "Bagaimana solusi dipelihara untuk memenuhi kebutuhan bisnis yang berubah?",
        "Apakah layanan TI didefinisikan dan portofolio layanan dikelola dengan baik?",
    ],
    "BAI04 - Manage Availability and Capacity" => [
        "Bagaimana organisasi menilai ketersediaan dan kapasitas saat ini untuk menciptakan baseline?",
        "Apakah dampak bisnis dari ketersediaan dan kapasitas dinilai dengan cermat?",
        "Bagaimana perencanaan kebutuhan layanan baru atau yang diubah dilakukan?",
        "Bagaimana organisasi memantau dan meninjau ketersediaan serta kapasitas secara berkala?",
        "Apakah masalah ketersediaan, kinerja, dan kapasitas ditangani dengan tepat?",
    ],
    "BAI05 - Manage Organizational Change Enablement" => [
        "Bagaimana organisasi mendorong keinginan untuk berubah di seluruh lapisan?",
        "Apakah tim implementasi yang efektif telah dibentuk?",
        "Bagaimana visi perubahan dikomunikasikan kepada semua pihak terkait?",
        "Apakah peran pemain kunci diberdayakan untuk mendukung perubahan?",
        "Bagaimana operasi baru diaktifkan dan digunakan?",
        "Apakah pendekatan baru dibangun ke dalam proses operasional?",
        "Bagaimana perubahan dijaga agar tetap berkelanjutan?",
    ],
    "BAI06 - Manage Changes" => [
        "Bagaimana perubahan yang diajukan dievaluasi, diprioritaskan, dan disetujui?",
        "Apakah ada proses yang jelas untuk mengelola perubahan darurat?",
        "Bagaimana status perubahan dilacak dan dilaporkan?",
        "Apakah dokumentasi penutupan perubahan dilakukan dengan baik?",
    ],
    "BAI07 - Manage Change Acceptance and Transitioning" => [
        "Apakah rencana implementasi telah disusun dengan jelas?",
        "Bagaimana organisasi merencanakan konversi proses bisnis, sistem, dan data?",
        "Apakah rencana uji penerimaan telah dibuat dan dilaksanakan?",
        "Bagaimana lingkungan pengujian disiapkan untuk mendukung transisi?",
        "Apakah uji penerimaan dilaksanakan dengan hasil yang terukur?",
        "Bagaimana organisasi memastikan promosi ke produksi dilakukan dengan baik?",
        "Apakah dukungan awal produksi diberikan selama transisi?",
        "Apakah tinjauan pasca-implementasi dilakukan untuk pembelajaran ke depan?",
    ],
    "BAI08 - Manage Knowledge" => [
        "Apakah budaya berbagi pengetahuan didorong dalam organisasi?",
        "Bagaimana organisasi mengidentifikasi dan mengklasifikasikan sumber informasi?",
        "Apakah informasi diorganisir menjadi pengetahuan yang relevan?",
        "Bagaimana organisasi memastikan pengetahuan digunakan dan dibagikan?",
        "Apakah evaluasi terhadap informasi lama dilakukan secara berkala?",
    ],
    "BAI09 - Manage Assets" => [
        "Bagaimana organisasi mengidentifikasi dan mencatat semua aset TI?",
        "Apakah aset kritis dikelola dengan baik untuk mendukung operasi?",
        "Bagaimana siklus hidup aset dikelola secara efisien?",
        "Apakah biaya aset dioptimalkan untuk efisiensi?",
        "Bagaimana organisasi mengelola lisensi TI untuk memastikan kepatuhan?",
    ],
    "BAI10 - Manage Configuration" => [
        "Apakah model konfigurasi disusun dan dipelihara dengan baik?",
        "Bagaimana organisasi membangun dan menjaga repositori serta baseline konfigurasi?",
        "Apakah item konfigurasi dikelola untuk mendukung integritas sistem?",
        "Bagaimana status konfigurasi dilaporkan secara berkala?",
        "Apakah integritas repositori konfigurasi diverifikasi secara teratur?",
    ],
];

// Iterasi untuk setiap aktivitas dalam domain BAI
foreach ($bai_activities as $activity => $statements) {
    ?>
    <div class="edm-activity">
        <h3><?php echo $activity; ?></h3>
        <button class="toggle-guideline">Lihat Pertanyaan</button>
        <div class="guidelines">
            <ul>
                <?php foreach ($statements as $statement): ?>
                    <li><?php echo $statement; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php
}
?>
<script>
    document.querySelectorAll('.toggle-guideline').forEach(button => {
        button.addEventListener('click', () => {
            const guidelines = button.nextElementSibling;
            guidelines.style.display = guidelines.style.display === 'block' ? 'none' : 'block';
        });
    });
</script>

        <form method="post" action="">
            <table>
                <thead>
                    <tr>
                        <th>Statement</th>
                        <th>Score (0-5)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($selected_statements as $index => $statement): ?>
                        <tr>
                            <td><?php echo $statement; ?></td>
                            <td>
                                <div class="radio-group">
                                    <?php for ($i = 0; $i <= 5; $i++): ?>
                                        <input type="radio" id="option_<?php echo $selected_activity . '_' . $index . '_' . $i; ?>" 
                                               name="<?php echo $selected_activity . '_' . $index; ?>" 
                                               value="<?php echo $i; ?>" 
                                               <?php 
                                               if (isset($_SESSION['answers'][$selected_activity][$index]) && 
                                                   $_SESSION['answers'][$selected_activity][$index] == $i) {
                                                   echo 'checked';
                                               } ?>>
                                        <label for="option_<?php echo $selected_activity . '_' . $index . '_' . $i; ?>"><?php echo $i; ?></label>
                                    <?php endfor; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" name="save">Save Answers</button>
            <button type="submit" name="reset">Reset</button>
            <button type="submit" name="save_project">Save Project</button>
        </form>

        <div class="result-summary">
            <h3>Summary of Results</h3>
            <table>
                <thead>
                    <tr>
                        <th>BAI Activity</th>
                        <th>Average Score</th>
                        <th>Gap</th>
                        <th>Explanation</th>
                    </tr>
                </thead>                    
                <tbody>
                    <?php foreach ($average_scores as $activity => $score): ?>
                        <tr>
                            <td><?php echo $activity; ?></td>
                            <td><?php echo number_format($score, 2); ?> / <?php echo $max_score; ?></td>
                            <td><?php echo number_format($gap_score[$activity], 2); ?></td>
                            <td><?php echo getScoreExplanation($score); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <h2>Chart of Scores and GAP</h2>
        <canvas id="scoreChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('scoreChart').getContext('2d');
        const labels = <?php echo json_encode(array_keys($average_scores)); ?>;
        const averageScores = <?php echo json_encode(array_values($average_scores)); ?>;
        const gapScores = <?php echo json_encode(array_values($gap_score)); ?>;

        const scoreChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Average Score',
                        data: averageScores,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    },
                    {
                        label: 'GAP',
                        data: gapScores,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true, max: <?php echo $max_score; ?> }
                }
            }
        });
    </script>
</body>
</html>

