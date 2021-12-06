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
            <script src=\"js/paginacion_examenes.js\"></script>
        </head>
        <body>
            <header>
                <div class=\"oculto\">Admin</div>
            </header>
            <!-- Aquí cosas varias, header, footer... ya sabe usté -->
            <a href=\"creaExamen.php\"><button>Crear examen</button></a>
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
            <script src=\"js/paginacion_examenes.js\"></script>
        </head>
        <body>
            <header>
                <div class=\"oculto\" id=\"permisos\">Alumno</div>
            </header>
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