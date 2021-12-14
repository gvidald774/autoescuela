<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    Sesion::iniciar();
    BD::conectar();

    $npag = BD::obtenCuantasPaginas($_GET['t'], "usuario");
    $lista = BD::obtenDatosPaginados($_GET['p'], $_GET['t'], "usuario");

    for($i = 0; $i < count($lista); $i++)
    {
        $idUsuario = $lista[$i]["id"];
        $lista[$i]["n_examenes"] = BD::getCuantosExamenes($idUsuario);

        $lista[$i]["rol"] = BD::getRol($lista[$i]["email"]);
    }

    $lista["npag"] = $npag;

    echo json_encode($lista);

    // Bueno más o menos funciona parece ya es cuestión de sacar los undefined y tal y pascual