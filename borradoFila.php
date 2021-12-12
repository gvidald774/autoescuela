<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    Sesion::iniciar();

    BD::conectar();

    $tabla = $_POST["tabla"];
    $id = $_POST["id"];

    if($tabla == "pregunta")
    {
        $respuesta = BD::borraPregunta($id);
    }
    else if($tabla == "examen")
    {
        $respuesta = BD::borraExamen($id);
    }
    else
    {
        $respuesta = BD::borraDato($tabla, $id);
    }

    if($respuesta == "true")
    {
        echo "Borrado realizado con éxito.";
    }
    else
    {
        switch($respuesta[0])
        {
            case "23000":
                echo "Error: Hay preguntas con esta temática aún existentes en la base de datos. Por favor, bórrelas antes de borrar esta temática.";
                break;
            default:
                echo "Error desconocido, el borrado no se ha realizado con éxito.";
                break;
        }
    }