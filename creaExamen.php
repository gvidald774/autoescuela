<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    // Fallo tremebundo: bancoPreguntas no se actualiza en examen existente.

    BD::conectar();
    Sesion::iniciar();

    if(!Sesion::existe("usuario"))
    {
        header("Location: login.php");
    }
    else if(Sesion::leer("rol") != "Admin") // Cambiar a "Profesor".
    {
        echo "No tiene permiso para acceder a estos contenidos.";
        header("Refresh: 5, URL=login.php"); // Cambiar porque te lleve a otra página.
    }
    else
    {
        $modifiquino = false;
        $placeholder = "";

        if(isset($_GET["id"]))
        {
            if(BD::existeExamen($_GET["id"]) == "true")
            {
                $json_data = json_encode(BD::getExamen($_GET["id"]));
                $modifiquino = true;
            }
            else
            {
                $modifiquino = false; // Inserción, venimos de nuevas
                $json_data = file_get_contents("modelos/examen.json");
            }
            $placeholder = json_decode($json_data);
            $idExamen = $placeholder->codigoExamen;
        }
        else
        {
            $json_data = file_get_contents("modelos/examen.json");
            $placeholder = json_decode($json_data);
            $idExamen = 0;
        }

    }

    $errorcillos = array();
    $errorcillos["enunciado"] = "";
    $errorcillos["duracion"] = "";
    $errorcillos["nPreguntas"] = "";
    // Quizá comprobar de alguna manera que nPreguntas coincide con el número de preguntas que hay en la lista y tal y pascual

    if(isset($_GET["id"])) // A ver no lo sé
    {
        Pintor::header("Editar examen",["js/validator.js", "js/crea_examen.js"]);
    }
    else
    {
        Pintor::header("Crear examen",["js/validator.js", "js/crea_examen.js"]);
    }
    Pintor::nav_admin();

    $preguntasJSON = json_encode($placeholder->bancoPreguntas);
    $seleccionadasJSON = json_encode($placeholder->preguntasIncluidas);

    $horas = floor($placeholder->duracion/60);
    $minutos = floor($placeholder->duracion%60);

?>
    <h1>Formulario de examen</h1>
    <main>
        <form action="" method="POST">
            <section>
                <div><input type="text" class="oculto" id="codigoExamen" name="codigoExamen" <?php echo "value=\"".$idExamen."\"" ?> /></div>
                <div><label for="enunciado">Enunciado: </label><input type="text" id="enunciado" name="enunciado" <?php echo "value=\"".$placeholder->enunciado."\"" ?> required /></div>
                <div><label for="n_preg">Nº preguntas: </label><input type="number" id="n_preg" name="n_preg" <?php echo "value=\"".$placeholder->numPreguntas."\"" ?> required /><div id="error_npreg"></div>
                <div>
                    <label>Duración: </label>
                    <input type="number" id="horas" name="horas" <?php echo "value=\"".$horas."\"" ?> />horas <input type="number" id="minutos" name="minutos" <?php echo "value=\"".$minutos."\"" ?> />minutos
                    <div id="error_duracion"></div>
                </div>
            </section>
            <section>
                <article>
                    <div class="izquierdo"><input type="text" id="filtroBancoPreguntas" /><input type="button" value="Filtrar" id="botonFiltroBancoPreguntas" /></div>
                    <div class="derecho"><input type="text" id="filtroPreguntasSeleccionadas" /><input type="button" value="Filtrar" id="botonFiltroPreguntasSeleccionadas" /></div>
                </article>
                <article class="ninguno">
                    <div id="bancoPreguntas_JSON" class="oculto"><?php echo $preguntasJSON ?></div>
                    <div id="bancoPreguntas"></div>
                    <div id="seleccionadas_JSON" class="oculto"><?php echo $seleccionadasJSON ?></div>
                    <div id="preguntasSeleccionadas"></div>
                </article>
                <article class="ninguno">
                    <input class="ninguno" type="submit" id="botonEnviar" name="botonEnviar" value="Enviar" />
                </article>
            </section>
        </form>
    </main>
<?php
    Pintor::footer();
?>