<?php
session_start();
include 'db_connect.php';

// Definisikan aktivitas DSS dan pernyataan terkait
$dss_activities = [
    'DSS01' => [
        'DSS01.01 Perform operational procedures.',
        'DSS01.02 Manage outsourced IT services.',
        'DSS01.03 Monitor IT infrastructure.',
        'DSS01.04 Manage the environment.',
        'DSS01.05 Manage facilities.',
    ],
    'DSS02' => [
        'DSS02.01 Define incident and service request classification schemes.',
        'DSS02.02 Record, classify and prioritise requests and incidents.',
        'DSS02.03 Verify, approve and fulfil service requests.',
        'DSS02.04 Investigate, diagnose and allocate incidents.',
        'DSS02.05 Resolve and recover from incidents.',
        'DSS02.06 Close service requests and incidents.',
        'DSS02.07 Track status and produce reports.',
    ],
    'DSS03' => [
        'DSS03.01 Identify and classify problems.',
        'DSS03.02 Investigate and diagnose problems.',
        'DSS03.03 Raise known errors.',
        'DSS03.04 Resolve and close problems.',
        'DSS03.05 Perform proactive problem management.',
    ],
    'DSS04' => [
        'DSS04.01 Define the business continuity policy, objectives and scope.',
        'DSS04.02 Maintain a continuity strategy.',
        'DSS04.03 Develop and implement a business continuity response.',
        'DSS04.04 Exercise, test and review the BCP.',
        'DSS04.05 Review, maintain and improve the continuity plan.',
        'DSS04.06 Conduct continuity plan training.',
        'DSS04.07 Manage backup arrangements.',
        'DSS04.08 Conduct post-resumption review.',
    ],
    'DSS05' => [
        'DSS05.01 Protect against malware.',
        'DSS05.02 Manage network and connectivity security.',
        'DSS05.03 Manage endpoint security.',
        'DSS05.04 Manage user identity and logical access.',
        'DSS05.05 Manage physical access to IT assets.',
        'DSS05.06 Manage sensitive documents and output devices.',
        'DSS05.07 Monitor the infrastructure for security-related events.',
    ],
    'DSS06' => [
        'DSS06.01 Align control activities embedded in business processes with enterprise objectives.',
        'DSS06.02 Control the processing of information.',
        'DSS06.03 Manage roles, responsibilities, access privileges and levels of authority.',
        'DSS06.04 Manage errors and exceptions.',
        'DSS06.05 Ensure traceability of information events and accountabilities.',
        'DSS06.06 Secure information assets.',
    ],
];
// Save user responses to session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save'])) {
        foreach ($dss_activities as $activity => $statements) {
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

// Tambahkan inisialisasi variabel di sini
$selected_activity = isset($_GET['activity']) ? $_GET['activity'] : 'DSS01';
$selected_statements = isset($dss_activities[$selected_activity]) ? $dss_activities[$selected_activity] : [];

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

foreach ($dss_activities as $activity => $statements) {
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
$audit_name = 'COBIT DSS Audit';
$domain = 'DSS';

// Ensure $_SESSION['answers'] is set and not empty
if (isset($_SESSION['answers']) && is_array($_SESSION['answers'])) {
    foreach ($_SESSION['answers'] as $activity => $answers) {
        if (isset($dss_activities[$activity])) {
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
    echo "<script>alert('Project saved successfully!');</script>";
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
        <h1>COBIT 5 DSS Analysis Result</h1>
        <a href="home.php" class="back-button">Kembali</a>

        <form method="get" action="">
            <label for="activity">Select DSS Activity:</label>
            <select name="activity" id="activity" onchange="this.form.submit()">
                <?php foreach ($dss_activities as $activity => $statements): ?>
                    <option value="<?php echo $activity; ?>" <?php if ($selected_activity == $activity) echo 'selected'; ?>><?php echo $activity; ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php
// Data aktivitas dan pertanyaan untuk domain DSS (Deliver, Service, and Support)
$dss_activities = [
    "DSS01 - Manage Operations" => [
        "Apakah operasi TI dikelola dengan standar yang jelas?",
        "Bagaimana organisasi memastikan ketersediaan layanan operasional secara konsisten?",
        "Apakah ada mekanisme untuk memantau kinerja operasional secara berkala?",
        "Bagaimana organisasi menangani insiden operasional dengan cepat dan efektif?",
        "Apakah pelaporan insiden dilakukan dengan dokumentasi yang lengkap?",
        "Bagaimana organisasi memastikan pemulihan yang cepat dari gangguan operasional?",
        "Apakah pemeliharaan preventif dijalankan untuk menghindari potensi masalah?",
        "Bagaimana proses pengelolaan perubahan dalam operasi dijalankan?",
    ],
    "DSS02 - Manage Service Requests and Incidents" => [
        "Apakah semua permintaan layanan dicatat dan dilacak dalam sistem terpusat?",
        "Bagaimana prioritas diberikan pada permintaan layanan dan insiden?",
        "Apakah respons terhadap insiden sesuai dengan waktu yang telah ditentukan (SLA)?",
        "Bagaimana organisasi menyelidiki dan menyelesaikan insiden secara menyeluruh?",
        "Apakah pelanggan diberi informasi yang memadai selama penanganan permintaan atau insiden?",
        "Bagaimana tinjauan terhadap insiden dilakukan untuk mencegah pengulangan?",
        "Apakah ada dokumentasi yang memadai untuk setiap insiden dan permintaan layanan?",
    ],
    "DSS03 - Manage Problems" => [
        "Apakah penyebab akar masalah diidentifikasi dengan metode yang sistematis?",
        "Bagaimana organisasi menentukan tindakan korektif untuk masalah yang terjadi?",
        "Apakah proses eskalasi diterapkan untuk masalah kritis?",
        "Bagaimana organisasi mengevaluasi tren masalah untuk mencegah insiden di masa depan?",
        "Apakah ada tinjauan periodik terhadap masalah yang belum terselesaikan?",
    ],
    "DSS04 - Manage Continuity" => [
        "Apakah organisasi memiliki rencana kelangsungan bisnis yang terperinci?",
        "Bagaimana rencana kelangsungan bisnis diuji untuk validitas dan keefektifannya?",
        "Apakah perencanaan kelangsungan bisnis melibatkan pemangku kepentingan yang relevan?",
        "Bagaimana organisasi memastikan keandalan solusi pemulihan bencana?",
        "Apakah langkah-langkah mitigasi diambil untuk meminimalkan risiko gangguan?",
    ],
    "DSS05 - Manage Security Services" => [
        "Bagaimana kebijakan keamanan informasi diterapkan di seluruh organisasi?",
        "Apakah risiko keamanan informasi diidentifikasi dan dievaluasi secara berkala?",
        "Bagaimana organisasi menangani insiden keamanan informasi?",
        "Apakah pelatihan keamanan diberikan kepada staf secara teratur?",
        "Bagaimana pengendalian akses diterapkan untuk memastikan data aman?",
    ],
    "DSS06 - Manage Business Process Controls" => [
        "Apakah kontrol proses bisnis didefinisikan dan diterapkan secara menyeluruh?",
        "Bagaimana organisasi memverifikasi efektivitas kontrol proses bisnis?",
        "Apakah pengujian kontrol dilakukan secara teratur?",
        "Bagaimana organisasi menangani temuan audit terkait kontrol bisnis?",
        "Apakah ada proses untuk memperbarui kontrol bisnis sesuai dengan perubahan kebutuhan?",
    ],
];

// Iterasi untuk setiap aktivitas dalam domain DSS
foreach ($dss_activities as $activity => $statements) {
    ?>
    <div class="dss-activity">
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
                        <th>Score (1-5)</th>
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
                                           <?php if (isset($_SESSION['answers'][$selected_activity][$index]) && $_SESSION['answers'][$selected_activity][$index] == $i) echo 'checked'; ?>>
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
                        <th>DSS Activity</th>
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

        <!-- Chart Display -->
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
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5 // Set maximum level of maturity to 5
                    }
                }
            }
        });
    </script>
</body>
</html>
