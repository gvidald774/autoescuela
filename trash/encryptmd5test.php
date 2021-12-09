<?php

$valor = rand(0,500000);
echo $valor."----";
$fecha = date(DATE_RFC2822);
echo $fecha."----";
echo md5($valor.$fecha);

$enlace = $_SERVER['DOCUMENT_ROOT'].'/proyectos/autoescuela/recuperaPass.php?token='.md5($valor.$fecha);

echo $enlace;

?>