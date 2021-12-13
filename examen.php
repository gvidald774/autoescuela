<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    BD::conectar();
    Sesion::iniciar();

    if(!Sesion::existe("usuario"))
    {
        header("Location: login.php");
    }

    if(isset($_GET["examen"]))
    {
        // Pues se hace un examen.
    }
    else if(isset($_GET["examenRealizado"]))
    {
        // Pues se revisa un examen.
    }
    else
    {
        // Oh god oh fuck
        echo "No tiene permiso para acceder a estos contenidos."; // Hale.
        header("Refresh: 5, URL=login.php"); // Cambiar porque te lleve a otra página.
    }


$examen = "Hola buenas tardes";

Pintor::header($examen);
?>

<?php Pintor::footer(); ?>