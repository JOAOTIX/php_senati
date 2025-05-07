<?php
require('fpdf.php');

$reporte = new FPDF();
$reporte->AddPage();
$reporte->SetFont('Arial', 'B', 16);
$reporte->Cell(40, 10, 'Senati - 2025');
$reporte->Output();
