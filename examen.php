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
        // Se asume que se viene ya con el examen creado y apascualizado.
        // Pues hay que coger el examen y pintarlo con los div ocultos y tal y pascual.
    }
    else if(isset($_GET["examenRealizado"]))
    {
        // Pues se revisa un examen.
        // Hay que comprobar que tengas acceso al examen, es decir, que si eres alumno lo hayas hecho tú.
        $permiso = BD::accesoExamenRealizado($_GET["examenRealizado"], Sesion::leer("usuario"));
        if($permiso)
        {
            Pintor::header("Examen");
            // Se pinta todo el rollito del examen y tal y pascual.
        }
        else
        {
            echo "No tiene permiso para acceder a estos contenidos.";
            header("Refresh: 5, URL=historicoExamenes.php");
        }
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