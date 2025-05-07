<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
$host = '127.0.0.1';
$dbname = 'parking_bd';
$username = 'root';
$password = '123';

// Conexión PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

require('fpdf.php');

// Función para registrar vehículo en la base de datos
function registrarVehiculo($placa, $tipo)
{
    global $pdo;

    // Insertar vehículo en la base de datos
    $fechaIngreso = date('Y-m-d H:i:s');
    $sql = "INSERT INTO vehiculo (placa, tipo, fecha_ingreso) VALUES (:placa, :tipo, :fecha_ingreso)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':placa' => $placa,
        ':tipo' => $tipo,
        ':fecha_ingreso' => $fechaIngreso
    ]);
}

// Función para generar el PDF
class generar_pdf_parking
{
    public static function generarPDF($placa, $tipo)
    {
        date_default_timezone_set('America/Lima'); // Hora de Perú

        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(200, 10, 'Parking Senati', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(200, 10, 'Av 28 de Julio', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->Cell(40, 10, 'Placa: ', 0, 0);
        $pdf->Cell(0, 10, $placa, 0, 1);
        $pdf->Cell(40, 10, 'Tipo de Vehiculo: ', 0, 0);
        $pdf->Cell(0, 10, $tipo, 0, 1);

        $fecha = date('d/m/Y');
        $hora = date('H:i:s');
        $pdf->Cell(40, 10, 'Fecha: ', 0, 0);
        $pdf->Cell(0, 10, $fecha, 0, 1);
        $pdf->Cell(40, 10, 'Hora: ', 0, 0);
        $pdf->Cell(0, 10, $hora, 0, 1);

        $pdf->Output();
    }
}

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa = trim($_POST["placa"] ?? '');
    $tipo = $_POST["tipo"] ?? '';

    // Verificar que la placa y el tipo no estén vacíos
    if ($placa !== '' && $tipo !== '') {
        // Si se presiona "Registrar", se guarda el vehículo en la base de datos
        if (isset($_POST['accion']) && $_POST['accion'] == 'registrar') {
            registrarVehiculo($placa, $tipo);
            echo "Vehículo registrado correctamente.";

            // Después de registrar, redirigir para evitar que se envíen múltiples solicitudes al recargar
            header("Location: parking.php");
            exit;
        }

        // Si se presiona "Generar PDF", se genera el PDF
        if (isset($_POST['accion']) && $_POST['accion'] == 'generar_pdf') {
            generar_pdf_parking::generarPDF($placa, $tipo);
        }
    } else {
        echo "Complete los datos.";
    }
}
