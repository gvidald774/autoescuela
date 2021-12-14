<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    BD::conectar();
    Sesion::iniciar();

    if(!Sesion::existe("usuario"))
    {
        header("Location: login.php");
    }
    else
    {
        Pintor::header("Tabla exámenes","js/paginacion_examenes.js");
        Pintor::nav_admin();
        echo '<div class="oculto" id="idOcultoEscondido">'.Sesion::leer("rol").'</div>';
        if(Sesion::leer("rol")=="Admin") {echo "<a href=\"creaExamen.php\"><button>Crear examen</button></a>";}
            echo "<table id=\"tabla\">
            </table>
            <div id=\"paginas\"></div>";
        Pintor::footer();
    }