<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'audit_db';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define APO activities and related statements
$apo_activities = [
   'APO01' => [
        'APO01.01' => 'Define the organisational structure.',
        'APO01.02' => 'Establish roles and responsibilities.',
        'APO01.03' => 'Maintain the enablers of the management system.',
        'APO01.04' => 'Communicate management objectives and direction.',
        'APO01.05' => 'Optimise the placement of the IT function.',
        'APO01.06' => 'Define information (data) and system ownership.',
        'APO01.07' => 'Manage continual improvement of processes.',
        'APO01.08' => 'Maintain compliance with policies and procedures.'
    ],
    'APO02' => [
        'APO02.01' => 'Understand enterprise direction.',
        'APO02.02' => 'Assess the current environment, capabilities and performance.',
        'APO02.03' => 'Define the target IT capabilities.',
        'APO02.04' => 'Conduct a gap analysis.',
        'APO02.05' => 'Define the strategic plan and road map.',
        'APO02.06' => 'Communicate the IT strategy and direction.'
    ],
    'APO03' => [
        'APO03.01' => 'Develop the enterprise architecture vision.',
        'APO03.02' => 'Define reference architecture.',
        'APO03.03' => 'Select opportunities and solutions.',
        'APO03.04' => 'Define architecture implementation.',
        'APO03.05' => 'Provide enterprise architecture services.'
    ],
    'APO04' => [
        'APO04.01' => 'Create an environment conducive to innovation.',
        'APO04.02' => 'Maintain an understanding of the enterprise environment.',
        'APO04.03' => 'Monitor and scan the technology environment.',
        'APO04.04' => 'Assess the potential of emerging technologies and innovation ideas.',
        'APO04.05' => 'Recommend appropriate further initiatives.',
        'APO04.06' => 'Monitor the implementation and use of innovation.'
    ],
    'APO05' => [
        'APO05.01' => 'Establish the target investment mix.',
        'APO05.02' => 'Determine the availability and sources of funds.',
        'APO05.03' => 'Evaluate and select programmes to fund.',
        'APO05.04' => 'Monitor, optimise and report on investment portfolio performance.',
        'APO05.05' => 'Maintain portfolios.',
        'APO05.06' => 'Manage benefits achievement.'
    ],
    'APO06' => [
        'APO06.01' => 'Manage finance and accounting.',
        'APO06.02' => 'Prioritise resource allocation.',
        'APO06.03' => 'Create and maintain budgets.',
        'APO06.04' => 'Model and allocate costs.',
        'APO06.05' => 'Manage costs.'
    ],
    'APO07' => [
        'APO07.01' => 'Maintain adequate and appropriate staffing.',
        'APO07.02' => 'Identify key IT personnel.',
        'APO07.03' => 'Maintain the skills and competencies of personnel.',
        'APO07.04' => 'Evaluate employee job performance.',
        'APO07.05' => 'Plan and track the usage of IT and business human resources.',
        'APO07.06' => 'Manage contract staff.'
    ],
    'APO08' => [
        'APO08.01' => 'Understand business expectations.',
        'APO08.02' => 'Identify opportunities, risks and constraints for IT to enhance the business.',
        'APO08.03' => 'Manage the business relationship.',
        'APO08.04' => 'Co-ordinate and communicate.',
        'APO08.05' => 'Provide input to the continual improvement of services.'
    ],
    'APO09' => [
        'APO09.01' => 'Identify IT services.',
        'APO09.02' => 'Catalogue IT-enabled services.',
        'APO09.03' => 'Define and prepare service agreements.',
        'APO09.04' => 'Monitor and report service levels.',
        'APO09.05' => 'Review service agreements and contracts.'
    ],
    'APO10' => [
        'APO10.01' => 'Identify and evaluate supplier relationships and contracts.',
        'APO10.02' => 'Select suppliers.',
        'APO10.03' => 'Manage supplier relationships and contracts.',
        'APO10.04' => 'Manage supplier risk.',
        'APO10.05' => 'Monitor supplier performance and compliance.'
    ],
    'APO11' => [
        'APO11.01' => 'Establish a quality management system (QMS).',
        'APO11.02' => 'Define and manage quality standards, practices and procedures.',
        'APO11.03' => 'Focus quality management on customers.',
        'APO11.04' => 'Perform quality monitoring, control and reviews.',
        'APO11.05' => 'Integrate quality management into solutions for development and service delivery.',
        'APO11.06' => 'Maintain continuous improvement.'
    ],
    'APO12' => [
        'APO12.01' => 'Collect data.',
        'APO12.02' => 'Analyse risk.',
        'APO12.03' => 'Maintain a risk profile.',
        'APO12.04' => 'Articulate risk.',
        'APO12.05' => 'Define a risk management action portfolio.',
        'APO12.06' => 'Respond to risk.'
    ],
    'APO13' => [
        'APO13.01' => 'Establish and maintain an ISMS.',
        'APO13.02' => 'Define and manage an information security risk treatment plan.',
        'APO13.03' => 'Monitor and review the ISMS.'
    ],
];

// Save user responses to session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save'])) {
        foreach ($apo_activities as $activity => $statements) {
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

// Define variables
$selected_activity = isset($_GET['activity']) ? $_GET['activity'] : 'APO01';
$selected_statements = isset($apo_activities[$selected_activity]) ? $apo_activities[$selected_activity] : [];

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

foreach ($apo_activities as $activity => $statements) {
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
$audit_name = 'COBIT APO Audit';
$domain = 'APO';

if (isset($_SESSION['answers']) && is_array($_SESSION['answers'])) {
    foreach ($_SESSION['answers'] as $activity => $answers) {
        if (isset($apo_activities[$activity])) {
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

        <form method="get" action="">
            <label for="activity">Select APO Activity:</label>
            <select name="activity" id="activity" onchange="this.form.submit()">
                <?php foreach ($apo_activities as $activity => $statements): ?>
                    <option value="<?php echo $activity; ?>" <?php if ($selected_activity == $activity) echo 'selected'; ?>><?php echo $activity; ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <div class="activity-container">
        <?php
// Data aktivitas dan pertanyaan untuk domain APO (Align, Plan, and Organize)
$apo_activities = [
    "APO01 - Manage the IT Management Framework" => [
        "Apakah organisasi memiliki kerangka manajemen TI yang selaras dengan tujuan bisnis?",
        "Bagaimana organisasi mendefinisikan peran dan tanggung jawab terkait manajemen TI?",
        "Apakah kebijakan manajemen TI dikembangkan, diterapkan, dan dipantau?",
        "Bagaimana organisasi memastikan adanya proses perbaikan berkelanjutan dalam kerangka manajemen TI?"
    ],
    "APO02 - Manage Strategy" => [
        "Apakah strategi TI selaras dengan strategi bisnis secara keseluruhan?",
        "Bagaimana organisasi mengevaluasi lingkungan eksternal untuk memetakan peluang dan ancaman?",
        "Apakah inisiatif strategis didasarkan pada analisis yang menyeluruh?",
        "Bagaimana hasil dari strategi TI dilaporkan kepada manajemen senior?",
        "Apakah organisasi mengevaluasi kesenjangan antara status saat ini dan visi strategis?"
    ],
    "APO03 - Manage Enterprise Architecture" => [
        "Apakah arsitektur TI dirancang untuk mendukung tujuan organisasi?",
        "Bagaimana pengelolaan arsitektur TI dijalankan untuk menjaga integritas sistem?",
        "Apakah organisasi memiliki panduan standar terkait arsitektur TI?",
        "Bagaimana organisasi mengevaluasi dan memperbarui arsitektur sesuai dengan perubahan kebutuhan?"
    ],
    "APO04 - Manage Innovation" => [
        "Apakah organisasi memiliki proses formal untuk mendeteksi dan mengevaluasi inovasi teknologi?",
        "Bagaimana organisasi menilai dampak teknologi baru terhadap tujuan bisnis?",
        "Apakah strategi inovasi dikelola untuk memaksimalkan nilai bagi organisasi?",
        "Bagaimana hasil inovasi diterapkan secara efektif di dalam organisasi?"
    ],
    "APO05 - Manage Portfolio" => [
        "Apakah portofolio TI dikelola untuk memaksimalkan nilai investasi?",
        "Bagaimana organisasi mengevaluasi risiko dan keuntungan dari setiap inisiatif portofolio?",
        "Apakah perubahan dalam prioritas bisnis memengaruhi portofolio TI secara tepat waktu?",
        "Bagaimana organisasi memantau kinerja keseluruhan portofolio TI?"
    ],
    "APO06 - Manage Budget and Costs" => [
        "Bagaimana organisasi menyusun anggaran TI untuk mendukung tujuan strategis?",
        "Apakah biaya TI dipantau secara berkala dan sesuai dengan anggaran yang disetujui?",
        "Bagaimana efisiensi pengeluaran TI dikelola tanpa mengorbankan kualitas?",
        "Apakah ada evaluasi terhadap pengembalian investasi (ROI) untuk setiap inisiatif TI?"
    ],
    "APO07 - Manage Human Resources" => [
        "Bagaimana organisasi mengelola kebutuhan sumber daya manusia untuk mendukung inisiatif TI?",
        "Apakah program pelatihan dan pengembangan sesuai dengan kebutuhan karyawan TI?",
        "Bagaimana organisasi memastikan keberlanjutan pengetahuan dalam tim TI?",
        "Apakah ada rencana suksesi untuk peran penting dalam departemen TI?"
    ],
    "APO08 - Manage Relationships" => [
        "Apakah hubungan dengan pemangku kepentingan dikelola untuk mendukung tujuan strategis?",
        "Bagaimana organisasi memastikan komunikasi yang efektif antara TI dan bisnis?",
        "Apakah ada mekanisme formal untuk menangani eskalasi masalah terkait hubungan bisnis dan TI?",
        "Bagaimana kepuasan pemangku kepentingan dipantau dan ditingkatkan?"
    ],
    "APO09 - Manage Service Agreements" => [
        "Apakah organisasi memiliki perjanjian tingkat layanan (SLA) yang jelas dan terdokumentasi?",
        "Bagaimana SLA dipantau dan diperbarui sesuai kebutuhan bisnis?",
        "Apakah ada mekanisme untuk mengevaluasi kinerja penyedia layanan?",
        "Bagaimana organisasi menangani perselisihan terkait SLA?"
    ],
    "APO10 - Manage Suppliers" => [
        "Bagaimana organisasi memilih dan mengevaluasi penyedia layanan TI?",
        "Apakah kontrak dengan penyedia layanan mencakup semua aspek penting layanan?",
        "Bagaimana hubungan dengan penyedia layanan dipantau untuk memastikan kepatuhan?",
        "Apakah penyedia layanan dievaluasi secara berkala untuk mempertahankan kualitas?"
    ],
    "APO11 - Manage Quality" => [
        "Bagaimana organisasi memastikan kualitas dalam setiap proses dan layanan TI?",
        "Apakah ada standar kualitas yang diterapkan di seluruh departemen TI?",
        "Bagaimana organisasi menangani masalah yang memengaruhi kualitas secara proaktif?",
        "Apakah metrik kualitas dipantau dan dilaporkan kepada manajemen?"
    ],
    "APO12 - Manage Risk" => [
        "Apakah risiko TI dikelola secara sistematis untuk meminimalkan dampak negatif?",
        "Bagaimana organisasi mengidentifikasi dan mengevaluasi risiko TI yang muncul?",
        "Apakah ada rencana mitigasi untuk mengelola risiko dengan dampak tinggi?",
        "Bagaimana organisasi mengevaluasi risiko residu setelah tindakan mitigasi?"
    ],
    "APO13 - Manage Security" => [
        "Apakah organisasi memiliki kebijakan keamanan TI yang komprehensif?",
        "Bagaimana keamanan data dan sistem dikelola untuk mencegah pelanggaran?",
        "Apakah pelatihan keamanan TI diberikan secara rutin kepada karyawan?",
        "Bagaimana organisasi merespons insiden keamanan untuk meminimalkan dampaknya?"
    ],
];

// Iterasi untuk setiap aktivitas dalam domain APO
foreach ($apo_activities as $activity => $statements) {
?>
    <div class="apo-activity">
        <h3><?php echo $activity; ?></h3>
        <button class="toggle-guideline">Lihat Pertanyaan</button>
        <div class="guidelines" style="display: none;">
            <ul>
                <?php foreach ($statements as $statement): ?>
                    <li><?php echo $statement; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php } ?>

<script>
    // Menambahkan fungsi toggle untuk melihat pertanyaan
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
                        <th>APO Activity</th>
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
