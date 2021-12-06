<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    BD::conectar();

    $arr=array();

    $arr = BD::traePreguntas();

    $cadena_json = json_encode($arr);

    echo $cadena_json;

    // Me voy a pegar un tiro esto no funciona