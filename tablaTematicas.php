<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    BD::conectar();
    Sesion::iniciar();

    if(!Sesion::existe("usuario"))
    {
        header("Location: login.php");
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de temáticas</title>
    <link rel="stylesheet" href="css/main.css" />
    <script src="js/paginacion_tematicas.js"></script>
</head>
<body>
    <!-- Aquí cosas varias, header, footer... ya sabe usté -->
    <a href="altaTematica.php"><button>Alta</button></a>
    <table id="tabla">
    </table>
    <div id="paginas"></div>
</body>
</html>