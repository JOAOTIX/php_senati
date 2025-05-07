<html>

<body>
    <?php
    $usu = $_POST['usuario'];
    $clav = $_POST['clave'];
    echo $usu . $clav;
    $con = mysqli_connect("localhost", "root", "usbw", "dbempresa");

    $sql = "SELECT * FROM tbusuarios WHERE usuario='" . $usu . "' AND clave='" . $clav . "'";
    $resultado = $con->query($sql);
    if ($resultado->num_rows > 0) {
        echo "Bienvenido";
    } else {
        echo "Usuario o clave incorrecta";
    }
    ?>
</body>

</html>