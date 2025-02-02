<?php
session_start();
include 'db_connect.php';

// Definisikan aktivitas MEA dan pernyataan terkait
$mea_activities = [
    'MEA01' => [
        'MEA01.01' => 'Establish a monitoring approach.',
        'MEA01.02' => 'Set performance and conformance targets.',
        'MEA01.03' => 'Collect and process performance and conformance data.',
        'MEA01.04' => 'Analyse and report performance.',
        'MEA01.05' => 'Ensure the implementation of corrective actions.'
    ],
    'MEA02' => [
        'MEA02.01' => 'Monitor internal controls.',
        'MEA02.02' => 'Review business process controls effectiveness.',
        'MEA02.03' => 'Perform control self-assessments.',
        'MEA02.04' => 'Identify and report control deficiencies.',
        'MEA02.05' => 'Ensure that assurance providers are independent and qualified.',
        'MEA02.06' => 'Plan assurance initiatives.',
        'MEA02.07' => 'Scope assurance initiatives.',
        'MEA02.08' => 'Execute assurance initiatives.'
    ],
    'MEA03' => [
        'MEA03.01' => 'Identify external compliance requirements.',
        'MEA03.02' => 'Optimise response to external requirements.',
        'MEA03.03' => 'Confirm external compliance.',
        'MEA03.04' => 'Obtain assurance of external compliance.'
    ]
];

// Save user responses to session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save'])) {
        foreach ($mea_activities as $activity => $statements) {
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

// Selected activity initialization
$selected_activity = isset($_GET['activity']) ? $_GET['activity'] : 'MEA01';
$selected_statements = isset($mea_activities[$selected_activity]) ? $mea_activities[$selected_activity] : [];

// Function to calculate average score for each activity
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
$max_score = 5; // Maximum possible score per activity
$desired_score = 5; // Desired maturity level

foreach ($mea_activities as $activity => $statements) {
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
$audit_name = 'COBIT MEA Audit';
$domain = 'MEA';

// Ensure $_SESSION['answers'] is set and not empty
if (isset($_SESSION['answers']) && is_array($_SESSION['answers'])) {
    foreach ($_SESSION['answers'] as $activity => $answers) {
        if (isset($mea_activities[$activity])) {
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
        <h1>COBIT 5 MEA Analysis Result</h1>
        <a href="home.php" class="back-button">Kembali</a>

        <form method="get" action="">
            <label for="activity">Select MEA Activity:</label>
            <select name="activity" id="activity" onchange="this.form.submit()">
                <?php foreach ($mea_activities as $activity => $statements): ?>
                    <option value="<?php echo $activity; ?>" <?php if ($selected_activity == $activity) echo 'selected'; ?>><?php echo $activity; ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php
// Data aktivitas dan pertanyaan untuk domain MEA (Monitor, Evaluate, and Assess)
$mea_activities = [
    "MEA01 - Monitor, Evaluate, and Assess Performance and Conformance" => [
        "Apakah kinerja TI dievaluasi terhadap tujuan organisasi secara berkala?",
        "Bagaimana organisasi memantau kepatuhan terhadap kebijakan dan peraturan yang berlaku?",
        "Apakah metrik kinerja yang digunakan sesuai dengan tujuan strategis organisasi?",
        "Bagaimana hasil evaluasi kinerja didokumentasikan dan dianalisis?",
        "Apakah tindakan perbaikan diterapkan berdasarkan hasil evaluasi kinerja?",
        "Bagaimana organisasi memastikan kepatuhan terhadap standar industri?",
        "Apakah ada tinjauan periodik terhadap kebijakan TI dan praktik kerja?",
        "Bagaimana organisasi melibatkan pemangku kepentingan dalam evaluasi kinerja?",
    ],
    "MEA02 - Monitor, Evaluate, and Assess the System of Internal Control" => [
        "Apakah pengendalian internal dirancang sesuai dengan kebutuhan organisasi?",
        "Bagaimana efektivitas pengendalian internal dievaluasi secara berkala?",
        "Apakah ada mekanisme untuk memantau kepatuhan terhadap pengendalian internal?",
        "Bagaimana hasil evaluasi pengendalian internal dilaporkan kepada manajemen?",
        "Apakah ada rencana tindak lanjut untuk mengatasi kelemahan pengendalian internal?",
        "Bagaimana organisasi memastikan bahwa pengendalian internal sesuai dengan peraturan eksternal?",
    ],
    "MEA03 - Monitor, Evaluate, and Assess Compliance with External Requirements" => [
        "Apakah organisasi memantau perubahan peraturan eksternal secara aktif?",
        "Bagaimana organisasi memastikan kepatuhan terhadap hukum dan regulasi yang berlaku?",
        "Apakah ada proses untuk mengidentifikasi dan menilai risiko ketidakpatuhan?",
        "Bagaimana organisasi menyusun laporan kepatuhan untuk pihak eksternal?",
        "Apakah pelatihan kepatuhan diberikan kepada staf secara teratur?",
        "Bagaimana organisasi menangani temuan audit eksternal?",
        "Apakah ada mekanisme untuk memastikan tindakan korektif terhadap ketidakpatuhan?",
    ],
];

// Iterasi untuk setiap aktivitas dalam domain MEA
foreach ($mea_activities as $activity => $statements) {
    ?>
    <div class="mea-activity">
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
                        <th>MEA Activity</th>
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
                scales: {
                    y: {
                        beginAtZero: true,
                        max: <?php echo $max_score; ?>
                    }
                }
            }
        });
    </script>
</body>
</html>