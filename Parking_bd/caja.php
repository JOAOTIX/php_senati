<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
$host = '127.0.0.1';// Cambia según tu configuración
$dbname = 'parking_bd';  // Cambia según tu base de datos
$username = 'root';   // Cambia según tu configuración
$password = '123';   // Cambia según tu configuración

// Conexión PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

// Obtener los vehículos registrados desde la base de datos
$sql = "SELECT * FROM vehiculo";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>CAJA - Parking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <!-- Barra de navegación -->
        <nav class="navbar navbar-expand-lg navbar-light bg-primary mb-4">
            <div class="container-fluid">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link text-white" href="parking.php">Parking</a>
                    <a class="nav-link text-white" href="admin.php">Administración</a>
                </div>
            </div>
        </nav>

        <h3 class="text-center">CAJA</h3>

        <!-- Formulario de ingreso -->
        <form action="ingresar.php" method="post">
            <div class="mb-3">
                <label for="placa" class="form-label">Placa del vehículo</label>
                <input type="text" name="placa" id="placa" class="form-control" placeholder="Ingrese la placa" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success">Buscar</button>
            </div>
        </form>

        <hr>

        <!-- Tabla con los vehículos ingresados -->
        <h4 class="text-center mt-4">Vehículos Ingresados</h4>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Placa</th>
                    <th>Tipo de Vehículo</th>
                    <th>Generar Boleta</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($vehiculos): ?>
                    <?php foreach ($vehiculos as $index => $vehiculo): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($vehiculo['placa']) ?></td>
                            <td><?= htmlspecialchars($vehiculo['tipo']) ?></td>
                            <td>
                                <a href="buscar_boleta.php?id=<?= $vehiculo['id'] ?>" class="btn btn-primary btn-sm">Generar</a>
                                <form action="registrar_pago.php" method="post" style="display:inline;">
                                    <input type="hidden" name="vehiculo_id" value="<?= $vehiculo['id'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Pagar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay vehículos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>