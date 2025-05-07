<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Suma</title>
</head>

<body>
    <h1>Tabla de suma del número ingresado</h1>
    <?php
    if (isset($_POST['numero'])) {
        $numero = $_POST['numero'];
        if (is_numeric($numero)) {
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr><th>Operación</th><th>Valor</th><th>Operación</th><th>Resultado</th></tr>";

            // Mostrar la tabla de suma
            for ($i = 1; $i <= 10; $i++) {
                $resultado = $numero + $i;
                echo "<tr><td>$numero</td><td>+</td><td>$i</td><td>$resultado</td></tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Por favor ingrese un número válido.</p>";
        }
    }
    ?>
</body>

</html>