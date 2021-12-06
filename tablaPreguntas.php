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
    <title>Tabla de preguntas</title>
    <link rel="stylesheet" href="css/main.css" />
    <script src="js/paginacion_preguntas.js"></script>
    <style>
        /* Vale pues esto pero de otra manera mejor */
        table {
            width: 80%;
            height: 80%;
        }
    </style>
</head>
<body>
    <!-- Aquí cosas varias, header, footer... ya sabe usté -->
    <table id="tabla">
    </table>
    <div id="paginas"></div>
    <div class="footer-pad"></div>
    <footer>
        <section>
            <a href=#>Guía de estilo</a>
            <a href=#>Mapa web del sitio</a>
        </section>
        <section>
            <h6>Enlaces relacionados: </h6>
            <a href=#>DGT</a>
            <a href=#>Solicitud oficial de examen</a>
            <a href=#>Normativa de examen</a>
        </section>
        <section>
            <h6>Contacto</h6>
            <ul>
                <li>Teléfono: 953111222</li>
                <li>email: info@examinator.es</li>
                <li>Redes sociales</li>
            </ul>
        </section>
    </footer>
</body>
</html>