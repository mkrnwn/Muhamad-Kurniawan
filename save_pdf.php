<?php
// Include the FPDF library
require('fpdf.php');

// Get the data (you could use the session to store the selected data from previous page)
session_start();

$data_points = $_SESSION['data_points'] ?? [];
$average_gap = $_SESSION['average_gap'] ?? 0;

// Create instance of FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Add title
$pdf->Cell(0, 10, 'Audit Data Report', 0, 1, 'C');
$pdf->Ln(10);

// Add table header
$pdf->Cell(30, 10, 'Activity', 1, 0, 'C');
$pdf->Cell(40, 10, 'Expected Maturity', 1, 0, 'C');
$pdf->Cell(50, 10, 'Current Maturity', 1, 0, 'C');
$pdf->Cell(30, 10, 'Gap', 1, 1, 'C');

// Add data to the table
foreach ($data_points as $point) {
    $pdf->Cell(30, 10, $point['activity'], 1);
    $pdf->Cell(40, 10, $point['expected_maturity'], 1);
    $pdf->Cell(50, 10, $point['current_maturity'], 1);
    $pdf->Cell(30, 10, $point['gap'], 1, 1, 'C');
}

// Add average gap
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Average Gap: ' . round($average_gap, 2), 0, 1, 'C');

// Output PDF
$pdf->Output();
?>
