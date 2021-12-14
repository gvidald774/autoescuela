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
        Pintor::header("Examen en proceso", "js/hacerExamen.js"); // Que tendrá el rollo del temporizador y tal
        (Sesion::leer("rol")=="Admin")?Pintor::nav_admin():Pintor::nav_alumno();

        echo '
        <div id="idSecretoOcultoEscondido" class="oculto">'.$_GET["examen"].'</div>
        <section id="cabecera_examen">
            <h1 id="titulo_examen">Pregunta 1</h1>
            <div id="temporizador" class="derecho"></div>
        </section>
        <form action="" method="POST" id="examen">
            <section id="seccion_preguntas_examen">
            </section>
            <section id="paginacion_preguntas_examen"></section>
            <input type="submit" id="botonEnviar" name="enviar" value="Enviar" />
        </form>
        ';

        // Pues se hace un examen.
        // Se asume que se viene ya con el examen creado y apascualizado.
        // Pues hay que coger el examen y pintarlo con los div ocultos y tal y pascual.
        Pintor::footer();
    }
    else if(isset($_GET["examenRealizado"]))
    {
        // Pues se revisa un examen.
        // Hay que comprobar que tengas acceso al examen, es decir, que si eres alumno lo hayas hecho tú.
        $permiso = BD::accesoExamenRealizado($_GET["examenRealizado"], Sesion::leer("usuario"));
        if($permiso == true || Sesion::leer("rol") == "Admin")
        {
            Pintor::header("Revisar examen", "js/revisarExamen.js");
            (Sesion::leer("rol")=="Admin")?Pintor::nav_admin():Pintor::nav_alumno();

            echo '
            <div id="idSecretoOcultoEscondido" class="oculto">'.$_GET["examenRealizado"].'</div>
            <section id="cabecera_examen">
                <h1 id="titulo_examen">Pregunta 1</h1>
            </section>
            <form action="" method="POST" id="examen">
                <section id="seccion_preguntas_examen">
                </section>
                <section id="paginacion_preguntas_examen" style="clear: both"></section>
            </form>
            ';

            Pintor::footer();
        }
        else
        {
            echo "No tiene permiso para acceder a estos contenidos.";
            header("Refresh: 5, URL=historicoExamenes.php");
        }
    }
    else
    {
        echo "No tiene permiso para acceder a estos contenidos."; // Hale.
        header("Refresh: 5, URL=login.php"); // Cambiar porque te lleve a otra página.
    }