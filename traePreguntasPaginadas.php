<?php

include_once("include/helpers/BD.php");
BD::conectar();

$npag = BD::obtenCuantasPaginas($_GET['t'],"pregunta");

$lista = BD::obtenDatosPaginados($_GET['p'],$_GET['t'],"pregunta");

for($i = 0; $i < count($lista); $i++)
{
    $lista[$i]["tematica"] = BD::getTematica($lista[$i]["tematica"]);
}

$lista["npag"] = $npag;

echo json_encode($lista);