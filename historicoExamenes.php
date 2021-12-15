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
        Pintor::header("Histórico de exámenes",["js/paginacion_hExamenes.js"]);
        Pintor::nav_admin();
        echo "<div class=\"oculto\" id=\"permisos\">Admin</div>
            <table id=\"tabla\">
            </table>
            <div id=\"paginas\"></div>";
        Pintor::footer();
    }
    else if(Sesion::leer("rol") == "Alumno")
    {
        Pintor::header("Histórico de exámenes",["js/paginacion_hExamenes.js"]);
        Pintor::nav_alumno();
        echo "<div class=\"oculto\" id=\"permisos\">Alumno</div>
            <div class=\"derecho\" id=\"sitioDatos\"></div>
            <table id=\"tabla\">
            </table>
            <div id=\"paginas\"></div>";
        Pintor::footer();
    }
    else
    {
        echo "what the fuck";
        Sesion::verContenido();
    }