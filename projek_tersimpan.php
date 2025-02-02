<?php
session_start();

// Database connection (update details as necessary)
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

// Initialize variables
$selected_data = [];
$average_gap = 0;

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM audit_data WHERE id = ?");
    $stmt->bind_param('i', $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Handle process selected request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_ids']) && !empty($_POST['selected_ids'])) {
    $selected_ids = $_POST['selected_ids'];
    $ids_placeholder = implode(',', array_fill(0, count($selected_ids), '?'));

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("SELECT * FROM audit_data WHERE id IN ($ids_placeholder)");
    $stmt->bind_param(str_repeat('i', count($selected_ids)), ...$selected_ids);
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize variables for calculating average gap
    $total_gap = 0;
    $count = 0;
    $data_points = [];

    // Fetch selected records and calculate gap
    while ($row = $result->fetch_assoc()) {
        $gap = $row['gap'];
        $total_gap += $gap;
        $count++;
        $data_points[] = [
            'activity' => $row['activity_domain'],
            'expected_maturity' => 5, // Assuming to-be maturity is 5
            'current_maturity' => 5 - $gap,
            'gap' => $gap,
        ];
    }

    // Calculate average gap
    $average_gap = $count > 0 ? $total_gap / $count : 0;

    $stmt->close();
}

// Handle reset request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    // Reset the data
    $data_points = [];
    $average_gap = 0;
}


// Fetch all audit data for display and selection
$sql = "SELECT * FROM audit_data";
$all_results = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Projek Tersimpan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #EEE8AA;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        h1 {
            color: #343a40;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
            width: 100%;
            max-width: 800px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #708090;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table,
        th,
        td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: #ffffff;
            font-weight: bold;
        }

        td {
            color: #F8F8FF;
        }

        button {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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
            left: 70px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .delete-button {
            background-color: #dc3545;
            color: #ffffff;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .reset-button {
            background-color: #28a745;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        .reset-button:hover {
            background-color: #218838;
        }

        canvas {
            display: block;
            margin: 0 auto;
            max-width: 700px;
            margin-top: 20px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>

<body>
    <a href="home.php" class="back-button">Kembali</a>
    <h1>Projek Tersimpan</h1>
    <form method="POST" action="projek_tersimpan.php">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Company Name</th>
                    <th>Audit Name</th>
                    <th>Domain</th>
                    <th>Activity Domain</th>
                    <th>Total Score</th>
                    <th>Gap</th>
                    <th>Explanation</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $all_results->fetch_assoc()): ?>
                    <tr>
                        <td><input type="checkbox" name="selected_ids[]" value="<?php echo $row['id']; ?>"></td>
                        <td><?php echo $row['company_name']; ?></td>
                        <td><?php echo $row['audit_name']; ?></td>
                        <td><?php echo $row['domain']; ?></td>
                        <td><?php echo $row['activity_domain']; ?></td>
                        <td><?php echo $row['total_score']; ?></td>
                        <td><?php echo $row['gap']; ?></td>
                        <td><?php echo $row['explanation']; ?></td>
                        <td>
                            <form method="POST" action="projek_tersimpan.php" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="delete-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <button type="submit">Process Selected</button>
        <button type="submit" name="reset" class="reset-button">Reset Hasil</button>
    </form>

    <?php if (!empty($data_points)): ?>
        <h2>Processed Results</h2>
        <div id="resultsSection">
            <a>asessor : <?php echo $_SESSION['username']; ?></a>

            <table style="width: 100%; border-collapse: collapse; background-color: #708090;">
                <thead>
                    <tr>
                        <th style="color: #fff; text-align: center;" colspan="4">Results Summary</th>
                    </tr>
                    <tr>
                        <th>Activity</th>
                        <th>Expected Maturity (to-be)</th>
                        <th>Current Maturity (as-is)</th>
                        <th>Gap</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data_points as $point): ?>
                        <tr>
                            <td><?php echo $point['activity']; ?></td>
                            <td><?php echo $point['expected_maturity']; ?></td>
                            <td><?php echo $point['current_maturity']; ?></td>
                            <td><?php echo $point['gap']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p>Average Gap: <?php echo round($average_gap, 2); ?></p>
            <div>
                <canvas id="gapChart" width="400" height="200"></canvas>
            </div>
        </div>
        <button type="button" id="downloadPdf" class="reset-button">Download PDF</button>


        <!-- Row for Chart -->
        <tr>
            <td colspan="4" style="padding: 20px; text-align: center;">
                <canvas id="gapChart" width="600" height="300"></canvas>
            </td>
        </tr>
        </tbody>
        </table>

        <script>
            const ctx = document.getElementById('gapChart').getContext('2d');
            const gapChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($data_points, 'activity')); ?>,
                    datasets: [{
                            label: 'Expected Maturity (to-be)',
                            data: <?php echo json_encode(array_fill(0, count($data_points), 5)); ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Current Maturity (as-is)',
                            data: <?php echo json_encode(array_column($data_points, 'current_maturity')); ?>,
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5
                        }
                    }
                }
            });

            document.getElementById('downloadPdf').addEventListener('click', () => {
                const resultsSection = document.getElementById('resultsSection');
                html2canvas(resultsSection).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const pdf = new jspdf.jsPDF();
                    pdf.addImage(imgData, 'PNG', 10, 10, 190, 0);
                    pdf.save('results.pdf');
                });
            });
        </script>
    <?php endif; ?>

</body>

</html>

<?php
$conn->close();
?>