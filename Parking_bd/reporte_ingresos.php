<?php
// Mostrar errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = '127.0.0.1';
$dbname = 'parking_bd';
$username = 'root';
$password = '123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

$sql = "SELECT 
            b.id AS boleta_id,
            v.placa,
            v.fecha_ingreso,
            v.fecha_salida,
            v.tipo
        FROM boleta b
        JOIN vehiculo v ON b.vehiculo_id = v.id
        ORDER BY b.fecha_emision DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte Ingresos - Parking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Reporte de Ingresos</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th># Boleta</th>
                                    <th>Placa</th>
                                    <th>Fecha y Hora de Entrada</th>
                                    <th>Fecha y Hora de Salida</th>
                                    <th>Tipo de Vehículo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($reportes): ?>
                                    <?php foreach ($reportes as $fila): ?>
                                        <tr>
                                            <td>B<?= str_pad($fila['boleta_id'], 3, '0', STR_PAD_LEFT) ?></td>
                                            <td><?= htmlspecialchars($fila['placa']) ?></td>
                                            <td><?= htmlspecialchars($fila['fecha_ingreso']) ?></td>
                                            <td><?= htmlspecialchars($fila['fecha_salida']) ?: 'En curso' ?></td>
                                            <td><?= ucfirst($fila['tipo']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No hay registros aún.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>