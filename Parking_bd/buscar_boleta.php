<?php
require('fpdf.php');
date_default_timezone_set('America/Lima'); 

// Conexión
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=parking_bd', 'root', '123');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Obtener ID del vehículo
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID no proporcionado.");
}

// Obtener datos del vehículo
$sql = "SELECT * FROM vehiculo WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$vehiculo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vehiculo) {
    die("Vehículo no encontrado.");
}

// Calcular monto
$fecha_ingreso = new DateTime($vehiculo['fecha_ingreso']);
$fecha_salida = new DateTime(); // ahora

$intervalo = $fecha_ingreso->diff($fecha_salida);
$horas = $intervalo->h + ($intervalo->days * 24);
$horas = max(1, $horas); // mínimo 1 hora

// Tarifas según tipo
switch (strtolower($vehiculo['tipo'])) {
    case 'moto':
        $tarifa = 2;
        break;
    case 'auto':
        $tarifa = 3;
        break;
    case 'camion':
        $tarifa = 5;
        break;
    default:
        $tarifa = 3;
}

$monto = $tarifa * $horas;

// Registrar salida en BD (reporte_ingresos)
$sql_insert = "INSERT INTO reporte_ingresos (vehiculo_id, placa, tipo, fecha_entrada, fecha_salida, monto)
               VALUES (:vehiculo_id, :placa, :tipo, :entrada, :salida, :monto)";
$stmt_insert = $pdo->prepare($sql_insert);
$stmt_insert->execute([
    ':vehiculo_id' => $vehiculo['id'],
    ':placa' => $vehiculo['placa'],
    ':tipo' => $vehiculo['tipo'],
    ':entrada' => $vehiculo['fecha_ingreso'],
    ':salida' => $fecha_salida->format('Y-m-d H:i:s'),
    ':monto' => $monto
]);

// Eliminar el vehículo de la tabla "vehiculo" (ya salió del parqueo)
$pdo->prepare("DELETE FROM vehiculo WHERE id = :id")->execute([':id' => $id]);

// Generar PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(200, 10, 'Parking Senati - Boleta de Salida', 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Ln(10);
$pdf->Cell(50, 10, 'Placa:', 0, 0);
$pdf->Cell(0, 10, $vehiculo['placa'], 0, 1);

$pdf->Cell(50, 10, 'Tipo:', 0, 0);
$pdf->Cell(0, 10, $vehiculo['tipo'], 0, 1);

$pdf->Cell(50, 10, 'Fecha Ingreso:', 0, 0);
$pdf->Cell(0, 10, $vehiculo['fecha_ingreso'], 0, 1);

$pdf->Cell(50, 10, 'Fecha Salida:', 0, 0);
$pdf->Cell(0, 10, $fecha_salida->format('Y-m-d H:i:s'), 0, 1);

$pdf->Cell(50, 10, 'Tiempo (horas):', 0, 0);
$pdf->Cell(0, 10, $horas, 0, 1);

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(50, 10, 'Total a pagar (S/.):', 0, 0);
$pdf->Cell(0, 10, number_format($monto, 2), 0, 1);

$pdf->Output();
