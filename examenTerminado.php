<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

Pintor::header("Examen terminado");
echo '<main><div>Examen terminado con éxito.</div></main>';
Pintor::footer();
header("Refresh: 3, url='historicoExamenes.php'");