<?php  
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    Sesion::iniciar();
    BD::conectar();

    if(isset($_GET["id"]))
    {
        $examenRealizado = BD::getJSON_ExamenRealizado($_GET["id"]);
        echo $examenRealizado;
    }
    else
    {
        header("Location: historicoExamenes.php");
    }