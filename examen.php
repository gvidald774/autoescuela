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

        $examen = BD::getJSON_ExamenPorRealizar($_GET["examen"]);

        var_dump($examen);

        echo
        '
        <div id="idSecretoOcultoEscondido" class="oculto">'.$_GET["examen"].'</div>
        <section id="cabecera_examen">
            <h1 id="titulo_examen">Pregunta 1</h1>
            <div id="temporizador" class="derecho">10:00:00</div>
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
        echo "ALBRICIAS";
        // Pues se revisa un examen.
        // Hay que comprobar que tengas acceso al examen, es decir, que si eres alumno lo hayas hecho tú.
        $permiso = BD::accesoExamenRealizado($_GET["examenRealizado"], Sesion::leer("usuario"));
        if($permiso == true || Sesion::leer("rol") == "Admin")
        {
            // Pillar los datos del examen realizado.
            $examenRealizado = json_decode(BD::getJSON_ExamenRealizado($_GET["examenRealizado"]));
            Pintor::header("Examen", "js/revisarExamen.js");
            (Sesion::leer("rol")=="Admin")?Pintor::nav_admin():Pintor::nav_usuario();
                var_dump($examenRealizado);
                echo '
                    <main>
                        <form>
                        <h1>Número de la pregunta</h1>
                        <section style="float: left" id="recursos">';

                            
                echo '
                        </section>
                        <section style="float: right">
                            <article>Y aquí pondríamos el enunciado de la pregunta</article>
                            <article>
                                <p>Y aquí pues las cuatro respuestas</p>
                            </article>
                        </section>
                        <section style="clear: both">
                            <p>Páginas
                        </section>
                        </form>
                    </main>
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