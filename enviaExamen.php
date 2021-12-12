<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    BD::conectar();

    $payload = file_get_contents("php://input");
    $data = json_decode($payload);
    
    if(!isset($data->idExamen))
    {
        // Esto significa que venimos de nuevas.
        echo "Se va a insertar un examen.";
        $id = BD::cogeUltimoId("examen")+1;
        $arrayPreguntas = array();
        for($i = 0; $i < count($data->preguntasIncluidas); $i++)
        {
            $preg = BD::getPreguntaSola($data->preguntasIncluidas[$i]);
            $p = new Pregunta($preg->id, $preg->enunciado, $preg->tematica, $preg->recurso);
            $arrayPreguntas[] = $p;
        }
        $examen = new Examen($id, $data->enunciado, $data->duracion, $data->numPreguntas, $arrayPreguntas);
        BD::insertaExamen($examen);
    }
    else
    {
        // Esto significa que vamos a editar un examen.
        echo "Se va a modificar un examen.";
        BD::modificaExamen($examen);
    }

?>