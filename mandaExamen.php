<?php
    require_once("include/cargadores/carga_helpers.php");

    Sesion::iniciar();
    BD::conectar();

    $payload = file_get_contents('php://input');
    $data = json_decode($payload);

    // Coge de aquí de la sesión el usuario hale a comer
    $idUsuario = BD::getIdAlumno(Sesion::leer("usuario"));

    // Sería lo suyo calcular aquí la calificación.
    $calificacion = 0;

    $preguntasCorrectas = 0;

    for($i = 0; $i < count($data->preguntas); $i++)
    {
        if(isset($data->preguntas[$i]->respuestaMarcada))
        {
            if($data->preguntas[$i]->respuestaMarcada == $data->preguntas[$i]->pregunta->respuestaCorrecta)
            {
                $preguntasCorrectas++;
            }
        }
    }

    $calificacion = ($preguntasCorrectas*100)/$data->numPreguntas;

    BD::guardaExamenRealizado($data, $idUsuario, $calificacion);

    echo BD::cogeUltimoId("examen_realizado");

?>