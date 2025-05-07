<?php
// Mostrar errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexión
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=parking_bd', 'root', '123');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Recibir ID
if (isset($_POST['vehiculo_id'])) {
    $vehiculo_id = $_POST['vehiculo_id'];

    // Fecha salida actual
    $fecha_salida = date('Y-m-d H:i:s');

    // Obtener fecha de ingreso
    $stmt = $pdo->prepare("SELECT fecha_ingreso FROM vehiculo WHERE id = :id");
    $stmt->execute([':id' => $vehiculo_id]);
    $vehiculo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vehiculo) {
        $fecha_ingreso = new DateTime($vehiculo['fecha_ingreso']);
        $salida = new DateTime($fecha_salida);
        $intervalo = $fecha_ingreso->diff($salida);

        // Calcular horas redondeando (mínimo 1 hora)
        $horas = max(1, $intervalo->h + ($intervalo->i > 0 ? 1 : 0));
        $tarifa = 2.00; // S/. 2 por hora
        $total = $horas * $tarifa;

        // Actualizar salida
        $stmt = $pdo->prepare("UPDATE vehiculo SET fecha_salida = :salida WHERE id = :id");
        $stmt->execute([
            ':salida' => $fecha_salida,
            ':id' => $vehiculo_id
        ]);

        // Insertar boleta con total
        $stmt = $pdo->prepare("INSERT INTO boleta (vehiculo_id, fecha_emision, total) VALUES (:id, NOW(), :total)");
        $stmt->execute([
            ':id' => $vehiculo_id,
            ':total' => $total
        ]);

        // Redirigir
        header("Location: reporte_ingresos.php");
        exit;
    } else {
        echo "Vehículo no encontrado.";
    }
} else {
    echo "ID no válido.";
}
