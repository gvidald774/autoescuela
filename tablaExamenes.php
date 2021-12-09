<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    BD::conectar();
    Sesion::iniciar();

    if(!Sesion::existe("usuario"))
    {
        header("Location: login.php");
    }
    else if(Sesion::leer("rol") != "Admin") // Cambiar a "Profesor".
    {
        echo "No tiene permiso para acceder a estos contenidos.";
        header("Refresh: 5, URL=login.php"); // Cambiar porque te lleve a otra página.
    }
    else
    {
        echo "<!DOCTYPE html>
        <html lang=\"en\">
        <head>
            <meta charset=\"UTF-8\">
            <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
            <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
            <title>Tabla de Exámenes</title>
            <link rel=\"stylesheet\" href=\"css/main.css\" />
            <script src=\"js/paginacion_examenes.js\"></script>
        </head>
        <body>
            <header></header>
                <nav>
                    <ul>
                        <li class=\"dropdown\">
                            <a href=\"tablaUsuarios.php\" class=\"dropbtn\">Usuarios</a>
                            <div class=\"dropdown-content\">
                                <a href=\"alta_usuario.php\">Alta de usuario</a>
                                <a href=\"alta_masiva.php\">Alta masiva</a>
                            </div>
                        </li>
                        <li class=\"dropdown\">
                            <a href=\"tablaTematicas.php\" class=\"dropbtn\">Temáticas</a>
                            <div class=\"dropdown-content\">
                                <a href=\"altaTematica.php\">Alta temática</a>
                            </div>
                        </li>
                        <li class=\"dropdown\">
                            <a href=\"tablaPreguntas.php\" class=\"dropbtn\">Preguntas</a>
                            <div class=\"dropdown-content\">
                                <a href=\"altaPregunta.php\">Alta pregunta</a>
                                <a href=\"alta_masiva_preguntas.php\">Alta masiva</a>
                            </div>
                        </li>
                        <li class=\"dropdown\">
                            <a href=\"tablaExamenes.php\" class=\"dropbtn\">Exámenes</a>
                            <div class=\"dropdown-content\">
                                <a href=\"creaExamen.php\">Alta examen</a>
                                <a href=\"historicoExamenes.php\">Histórico</a>
                            </div>
                        </li>
                    </ul>
                </nav>
            <a href=\"creaExamen.php\"><button>Crear examen</button></a>
            <table id=\"tabla\">
            </table>
            <div id=\"paginas\"></div>
            <footer></footer>
        </body>
        </html>
        ";
    }