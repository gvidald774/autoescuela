<?php
    require_once("include/cargadores/carga_helpers.php");

Sesion::iniciar();
Pintor::header("Guía de estilos");
(Sesion::leer("rol") == "Admin")?Pintor::nav_admin():Pintor::nav_alumno();
?>

<main class="mapa_sitio">
    <h1>Mapa del sitio</h1>
    <section class="izquierdo">
        <ul>Administrador: 
            <li><a href="alta_masiva.php">Alta masiva de usuarios</a></li>
            <li><a href="alta_usuario.php">Alta individual de usuarios</a></li>
            <li><a href="altaPregunta.php">Creación/Edición de preguntas</a></li>
            <li><a href="altaTematica.php">Creación/Edición de temáticas</a></li>
            <li><a href="creaExamen.php">Creación/Edición de exámenes</a></li>
            <li><a href="datosUsuario.php">Edición de los datos del usuario</a></li>
            <li><a href="historicoExamenes.php">Histórico de exámenes</a></li>
            <li><a href="login.php">Formulario de acceso</a></li>
            <li><a href="olvido.php">Recordatorio de contraseña</a></li>
            <li><a href="tablaExamenes.php">Lista de exámenes</a></li>
            <li><a href="tablaPreguntas.php">Lista de preguntas</a></li>
            <li><a href="tablaTematicas.php">Lista de temáticas</a></li>
            <li><a href="tablaUsuarios.php">Lista de usuarios</a></li>
        </ul>
    </section>
    <section class="izquierdo">
        <ul>Usuario: 
            <li><a href="datosUsuario.php">Edición de los datos del usuario</a></li>
            <li><a href="historicoExamenes.php">Histórico de exámenes del usuario</a></li>
            <li><a href="login.php">Formulario de acceso</a></li>
            <li><a href="olvido.php">Recordatorio de contraseña</a></li>
            <li><a href="tablaExamenes.php">Realización de examen predefinido</a></li>
    </section>
</main>

<?php Pintor::footer(); ?>