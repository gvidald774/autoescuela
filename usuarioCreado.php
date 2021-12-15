<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    Sesion::iniciar();

Pintor::header("Usuario creado");
echo '<main><div>Usuario creado con éxito.</div></main>';
Pintor::footer();

header("Refresh: 3, url='tablaExamenes.php'");