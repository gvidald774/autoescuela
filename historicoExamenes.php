<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    BD::conectar();
    Sesion::iniciar();

    if(!Sesion::existe("usuario"))
    {
        header("Location: login.php");
    }
    else if(Sesion::leer("rol") == "Admin") // Cambiar a "Profesor".
    {
        echo "<!DOCTYPE html>
        <html lang=\"en\">
        <head>
            <meta charset=\"UTF-8\">
            <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
            <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
            <title>Tabla de exámenes</title>
            <link rel=\"stylesheet\" href=\"css/main.css\" />
            <script src=\"js/paginacion_hExamenes.js\"></script>
        </head>
        <body>
            <header>
                <div class=\"oculto\" id=\"permisos\">Admin</div>
            </header>
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
                            <a href=\"alta_masiva.php\">Alta masiva</a>
                        </div>
                    </li>
                    <li class=\"dropdown\">
                        <a href=\"tablaExamenes\" class=\"dropbtn\">Exámenes</a>
                        <div class=\"dropdown-content\">
                            <a href=\"alta_examen\">Alta examen</a>
                            <a href=\"historicoExamenes\">Histórico</a>
                        </div>
                    </li>
                </ul>
            </nav>
            <table id=\"tabla\">
            </table>
            <div id=\"paginas\"></div>
        </body>
        </html>
        ";
    }
    else if(Sesion::leer("rol") == "Alumno")
    {
        echo "<!DOCTYPE html>
        <html lang=\"en\">
        <head>
            <meta charset=\"UTF-8\">
            <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
            <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
            <title>Tabla de exámenes</title>
            <link rel=\"stylesheet\" href=\"css/main.css\" />
            <script src=\"js/paginacion_hExamenes.js\"></script>
        </head>
        <body>
            <header>
                <div class=\"oculto\" id=\"permisos\">Alumno</div>
            </header>
            <nav>
                <ul>
                    <li><a href=\"historicoExamenes.php\">Histórico de exámenes</a></li>
                    <li><a href=\"examenPredefinido.php\">Examen predefinido</a></li>
                    <li><a href=\"examenAleatorio.php\">Examen aleatorio</a></li>
                </ul>
            </nav>
            <!-- Aquí cosas varias, header, footer... ya sabe usté -->
            <a href=\"creaExamen.php\"><button>Crear examen</button></a>
            <table id=\"tabla\">
            </table>
            <div id=\"paginas\"></div>
        </body>
        </html>
        ";
    }
    else
    {
        echo "what the fuck";
        Sesion::verContenido();
    }