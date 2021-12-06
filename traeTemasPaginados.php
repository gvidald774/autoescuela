<?php

include_once("include/helpers/BD.php");
BD::conectar();

$npag = BD::obtenCuantasPaginas($_GET['t'],"tematica");

$lista = BD::obtenDatosPaginados($_GET['p'],$_GET['t'],"tematica");

$lista["npag"] = $npag;

echo json_encode($lista);