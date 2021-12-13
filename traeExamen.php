<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    Sesion::iniciar();
    BD::conectar();

    if(isset($_GET["id"]))
    {
        $examen = json_encode(BD::getJSON_ExamenPorRealizar($_GET["id"]));
        echo $examen;
    }
    else
    {
        header("Location: tablaExamenes.php");
    }