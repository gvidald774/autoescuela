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
        Pintor::header("Tabla usuarios",["js/paginacion_usuarios.js"]);
        Pintor::nav_admin();
        echo "
            <a href=\"alta_usuario.php\"><button>Alta</button></a>
            <table id=\"tabla\">
            </table>
            <div id=\"paginas\"></div>";
        Pintor::footer();
    }