<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'audit_db';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define EDM activities and related statements
$edm_activities = [
    'EDM01' => [
        'EDM01.01 Evaluate the governance system.',
        'EDM01.02 Direct the governance system.',
        'EDM01.03 Monitor the governance system.'
    ],
    'EDM02' => [
        'EDM02.01 Evaluate value optimisation.',
        'EDM02.02 Direct value optimisation.',
        'EDM02.03 Monitor value optimisation.'
    ],
    'EDM03' => [
        'EDM03.01 Evaluate risk management.',
        'EDM03.02 Direct risk management.',
        'EDM03.03 Monitor risk management.'
    ],
    'EDM04' => [
        'EDM04.01 Evaluate resource management.',
        'EDM04.02 Direct resource management.',
        'EDM04.03 Monitor resource management.'
    ],
    'EDM05' => [
        'EDM05.01 Evaluate stakeholder reporting requirements.',
        'EDM05.02 Direct stakeholder communication and reporting.',
        'EDM05.03 Monitor stakeholder communication.'
    ],
];

// Save user responses to session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save'])) {
        foreach ($edm_activities as $activity => $statements) {
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
$selected_activity = isset($_GET['activity']) ? $_GET['activity'] : 'EDM01';
$selected_statements = isset($edm_activities[$selected_activity]) ? $edm_activities[$selected_activity] : [];


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

foreach ($edm_activities as $activity => $statements) {
    if (isset($_SESSION['answers'][$activity])) {
        $average_scores[$activity] = calculateAverageScore($_SESSION['answers'][$activity]);
        $gap_score[$activity] = $desired_score - $average_scores[$activity]; // Calculate gap as (to-be) - (as-is)
        $total_score[$activity] = array_sum($_SESSION['answers'][$activity]);
    } else {
        $average_scores[$activity] = 0;
        $gap_score[$activity] = $desired_score; // Gap is maximum if no answers
        $total_score[$activity] = 0;
    }
}

// Save results to the database
$company_name = 'YourCompany';
$audit_name = 'COBIT EDM Audit';
$domain = 'EDM';

// Pastikan bahwa $_SESSION['answers'] ada dan tidak kosong
if (isset($_SESSION['answers']) && is_array($_SESSION['answers'])) {
    foreach ($_SESSION['answers'] as $activity => $answers) {
        // Pastikan aktivitas yang dimasukkan ada dalam daftar EDM Activities
        if (isset($edm_activities[$activity])) {
            $average_score = calculateAverageScore($answers);
            $gap = $desired_score - $average_score; // Hitung gap sebagai (to-be) - (as-is)
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
        <h1>COBIT 5 Analysis Result</h1>
        <a href="home.php" class="back-button">Kembali</a>

        <!-- Form untuk memilih aktivitas EDM -->
        <form method="get" action="">
            <label for="activity">Select EDM Activity:</label>
            <select name="activity" id="activity" onchange="this.form.submit()">
                <?php foreach ($edm_activities as $activity => $statements): ?>
                    <option value="<?php echo $activity; ?>" <?php if ($selected_activity == $activity) echo 'selected'; ?>><?php echo $activity; ?></option>
                <?php endforeach; ?>
            </select>
        </form>

        <div class="activity-container">
    <?php 
    $edm_activities = [
        "EDM01 - Ensure Governance Framework Setting and Maintenance" => [
            "Apakah kebijakan tata kelola TI telah diselaraskan dengan tujuan organisasi?",
            "Apakah tanggung jawab tata kelola TI telah didefinisikan dengan jelas?",
            "Bagaimana mekanisme pengawasan tata kelola TI dijalankan secara rutin?",
        ],
        "EDM02 - Ensure Benefits Delivery" => [
            "Apakah hasil yang diharapkan dari inisiatif TI telah didefinisikan dengan jelas?",
            "Apakah pemangku kepentingan terlibat dalam penentuan manfaat yang diharapkan?",
            "Bagaimana manfaat dari inisiatif TI dipantau dan dilaporkan?",
        ],
        "EDM03 - Ensure Risk Optimization" => [
            "Apakah proses identifikasi risiko TI telah diimplementasikan dengan baik?",
            "Bagaimana risiko yang diidentifikasi dievaluasi dan direspons secara sistematis?",
            "Apakah ada dokumentasi yang jelas terkait risiko utama dalam sistem TI?",
        ],
        "EDM04 - Ensure Resource Optimization" => [
            "Apakah alokasi sumber daya TI dilakukan berdasarkan prioritas strategis?",
            "Apakah ada mekanisme untuk memantau penggunaan sumber daya TI secara efisien?",
            "Bagaimana organisasi mengidentifikasi kebutuhan sumber daya tambahan untuk inisiatif TI?",
        ],
        "EDM05 - Ensure Stakeholder Transparency" => [
            "Apakah laporan terkait kinerja TI telah disediakan secara berkala untuk pemangku kepentingan?",
            "Bagaimana organisasi memastikan kejelasan komunikasi mengenai tata kelola TI?",
            "Apakah masukan dari pemangku kepentingan diterima dan dipertimbangkan dalam pengambilan keputusan?",
        ]
    ];
    foreach ($edm_activities as $activity => $statements): 
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
    <?php endforeach; ?>
</div>


        <!-- Form untuk input score -->
        <form method="post" action="">
            <table>
                <thead>
                    <tr>
                        <th>Kuisioner</th>

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
                                               <?php 
                                               if (isset($_SESSION['answers'][$selected_activity . '_' . $index]) && 
                                                   $_SESSION['answers'][$selected_activity . '_' . $index] == $i) {
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
                        <th>EDM Activity</th>
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
    <script>
        document.querySelectorAll('.toggle-guideline').forEach(button => {
            button.addEventListener('click', () => {
                const guidelines = button.nextElementSibling;
                guidelines.style.display = guidelines.style.display === 'block' ? 'none' : 'block';
            });
        });
    </script>
</body>
</html>