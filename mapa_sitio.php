<?php
    require_once("include/cargadores/carga_helpers.php");

Sesion::iniciar();
Pintor::header("Guía de estilos");
(Sesion::leer("rol") == "Admin")?Pintor::nav_admin():Pintor::nav_alumno();
?>

<main>
    <h1>Mapa del sitio</h1>
</main>

<?php Pintor::footer(); ?>