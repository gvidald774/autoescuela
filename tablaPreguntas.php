<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    BD::conectar();
    Sesion::iniciar();

    if(!Sesion::existe("usuario"))
    {
        header("Location: login.php");
    }
    // Señor no tiene usted billete
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
    <header></header>
    <nav>
        <ul>
            <li class="dropdown">
                <a href="tablaUsuarios.php" class="dropbtn">Usuarios</a>
                <div class="dropdown-content">
                    <a href="alta_usuario.php">Alta de usuario</a>
                    <a href="alta_masiva.php">Alta masiva</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="tablaTematicas.php" class="dropbtn">Temáticas</a>
                <div class="dropdown-content">
                    <a href="altaTematica.php">Alta temática</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="tablaPreguntas.php" class="dropbtn">Preguntas</a>
                <div class="dropdown-content">
                    <a href="altaPregunta.php">Alta pregunta</a>
                    <a href="alta_masiva_preguntas.php">Alta masiva</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="tablaExamenes.php" class="dropbtn">Exámenes</a>
                <div class="dropdown-content">
                    <a href="creaExamen.php">Alta examen</a>
                    <a href="historicoExamenes.php">Histórico</a>
                </div>
            </li>
        </ul>
    </nav>
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