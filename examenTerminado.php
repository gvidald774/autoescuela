<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    if(isset($_GET["id"]))
    {
        
    }

Pintor::header("Examen terminado");
?>
<main>
    <div>Examen realizado con éxito.</div>
    <div>Estadísticas:</div>
    <div>Preguntas acertadas:</div>
    <div>Preguntas falladas:</div>
    <div>Preguntas sin contestar:</div>

    <a href="historicoExamenes.php">Volver</a>
</main>
<?php
Pintor::footer();
?>