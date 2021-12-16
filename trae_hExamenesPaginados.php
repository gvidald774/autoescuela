<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

BD::conectar();
Sesion::iniciar();

if($_GET['r']=="'Admin'")
{
    $npag = BD::obtenCuantasPaginasExamen($_GET['t']);
    $lista = BD::obtenExamenesPaginados($_GET['p'],$_GET['t']);
    $lista["npag"] = $npag;
    echo json_encode($lista);
}
else if($_GET['r']=="'Alumno'")
{
    $idAlumno = BD::getIdAlumno(Sesion::leer("usuario"));
    $npag = BD::obtenCuantasPaginasExamen($_GET['t']," AND u.id = ".$idAlumno);
    $lista = BD::obtenExamenesPaginados($_GET['p'],$_GET['t']," AND u.id = ".$idAlumno);
    $datos = BD::obtenStatsExamen($idAlumno);
    $lista["npag"] = $npag;
    $lista["datos"] = $datos;
    echo json_encode($lista);
}