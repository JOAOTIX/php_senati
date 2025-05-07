<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Parking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <nav class="navbar navbar-expand-lg navbar-light bg-primary mb-4">
            <div class="container-fluid">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link text-white" href="caja.php">Caja</a>
                    <a class="nav-link text-white" href="admin.php">Administración</a>
                </div>
            </div>
        </nav>

        <h1 class="text-center">Parking</h1>
        <form action="generar_pdf_parking.php" method="post">
            <div class="mb-3">
                <label for="placa" class="form-label">Placa del vehículo</label>
                <input type="text" name="placa" id="placa" class="form-control" placeholder="Ingrese la placa" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de vehículo</label>
                <select name="tipo" id="tipo" class="form-select" required>
                    <option value="moto">Moto</option>
                    <option value="auto">Auto</option>
                    <option value="camion">Camión</option>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" name="accion" value="generar_pdf" class="btn btn-success">Generar PDF</button>
                <button type="submit" name="accion" value="registrar" class="btn btn-warning">Registrar</button>
            </div><br>
        </form>

    </div>
</body>

</html>