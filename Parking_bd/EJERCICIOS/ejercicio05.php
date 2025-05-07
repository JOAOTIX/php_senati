<!Ejemplo de redondeo>
    <html>

    <head>
        <title>Calculos </title>
    </head>

    <body>
        <h1>Calculos, redondeo y formato. </h1>
        <?php
        /* Primero declaramos las variables */
        $precioneto = 91.55;
        $igv = 0.18;
        $resultado = $precioneto * $igv;
        echo "El precio es de ";
        echo $precioneto;
        echo " y el IGV el ";
        echo $igv;
        echo "% <br><br>";
        echo "Resultado: $resultado";
        echo "<br><br>Resultado redondeado:" . round($resultado,2). "<br>";
        echo "<br>";
        $resultado2 = sprintf("%01.2f", $resultado);
        echo "Usando la funcion SPRINTF se ve asi: ";
        echo $resultado2
        ?>
    </body>

    </html>