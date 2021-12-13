<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    Sesion::iniciar();
    BD::conectar();

    if(isset($_GET["id"]))
    {
        
    }
    else
    {
        header("Location: tablaExamenes.php");
    }