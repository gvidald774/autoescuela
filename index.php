<?php
/**
 * Esta es la página por la que se entra normalmente.
 * Te lleva directamente al login, a no ser que estés logueado, en cuyo caso te lleva al histórico de exámenes.
 */

require_once("include/cargadores/carga_entities.php");
require_once("include/cargadores/carga_helpers.php");

BD::conectar();
Sesion::iniciar();

if(!Sesion::existe("usuario"))
{
    header("Location: login.php");
}
else
{
    // Cambiar por tablaExamenes
    header("Location: historicoExamenes.php");
}

?>