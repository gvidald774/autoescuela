<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    Sesion::iniciar();

    BD::conectar();

    $tabla = $_POST["tabla"];
    $id = $_POST["id"];

    $respuesta = BD::borraDato($tabla, $id);

    if($respuesta == true)
    {
        echo "Borrado realizado con éxito.";
    }
    else
    {
        echo $respuesta;
    }