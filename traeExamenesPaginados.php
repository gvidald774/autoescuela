<?php

require_once("include/helpers/BD.php");

BD::conectar();

$npag = BD::obtenCuantasPaginas($_GET['t'],"examen");

$lista = BD::obtenDatosPaginados($_GET['p'],$_GET['t'],"examen");

$lista["npag"] = $npag;

echo json_encode($lista);