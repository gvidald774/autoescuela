<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

Pintor::header("Examen insertado");
echo '<main><div class="mensaje_acierto">Examen insertado con éxito.</div></main>';
Pintor::footer();
header("Refresh: 3, url='tablaExamenes.php'");