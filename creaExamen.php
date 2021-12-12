<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

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
            $json_data = BD::getExamen($_GET["id"]);
            if($json_data == true/* Algo que sea vacío */)
            {
                $modifiquino = false; // Inserción, venimos de nuevas
                $json_data = file_get_contents("modelos/jsonConfExamen.json"); // Hay que cambiar el modelo de alguna manera
                // Aquí se insertarían las preguntas buenas, quitando las del modelo
                // Y se vaciaría las preguntas seleccionadas, quitando las del modelo
                $placeholder = json_decode($json_data);
                switch(json_last_error())
                {
                    case JSON_ERROR_NONE:
                        echo ' - Sin errores';
                    break;
                    case JSON_ERROR_DEPTH:
                        echo ' - Excedido tamaño máximo de la pila';
                    break;
                    case JSON_ERROR_STATE_MISMATCH:
                        echo ' - Desbordamiento de buffer o los modos no coinciden';
                    break;
                    case JSON_ERROR_CTRL_CHAR:
                        echo ' - Encontrado carácter de control no esperado';
                    break;
                    case JSON_ERROR_SYNTAX:
                        echo ' - Error de sintaxis, JSON mal formado';
                    break;
                    case JSON_ERROR_UTF8:
                        echo ' - Caracteres UTF-8 malformados, posiblemente codificados de forma incorrecta';
                    break;
                    default:
                        echo ' - Error desconocido';
                    break;
                }
            }
            else
            {
                $placeholder = json_decode($json_data);
                $modifiquino = true;
            }

        }
        else
        {
            $json_data = file_get_contents("modelos/examen.json"); // Va a ser lo mejor creo yo.
            // Hay que meterle las preguntas todas, o algo así.
            // Y el resto de datos se vienen del modelo JSON (?)
        }
    }

    $errorcillos = array();
    $errorcillos["enunciado"] = "";
    $errorcillos["duracion"] = "";
    $errorcillos["nPreguntas"] = "";
    // Quizá comprobar de alguna manera que nPreguntas coincide con el número de preguntas que hay en la lista y tal y pascual

    if(isset($_GET["id"])) // A ver no lo sé
    {
        Pintor::header("Editar examen","js/crea_examen.js");
    }
    else
    {
        Pintor::header("Crear examen","js/crea_examen.js");
    }
    Pintor::nav_admin();
?>
    <style>
        input[type="text"] {
            font-size: 100%;
        }
        #bancoPreguntas {
            float: left;
        }
        #preguntasSeleccionadas {
            float: right;
        }
        #preguntasSeleccionadas, #bancoPreguntas {
            margin: 10px;
            overflow: auto;
            background-color: palegoldenrod;
            box-shadow: 5px 5px 5px black;
            display: block;
            position: relative;
            width: 40%;
            min-height: 600px;
            max-height: 600px;
        }
        div.pregunta {
            position: relative;
            border: 1px solid;
            background-color: maroon;
            overflow: auto;
            margin: 10px;
            box-shadow: 5px 5px 5px black;
            min-height: 50px;
            max-height: 500px;
        }
        div.pregunta-enunciado {
            color: black;
            font-size: medium;
            font-family: sans-serif;
            display: block;
            overflow: auto;
        }
        div.pregunta-tema {
            color: gray;
            font-size: small;
            text-align: right;
            display: block;
        }
        div.marcado {
            border: 3px solid blue;
        }
        div.oculto {
            display: none;
        }
    </style>
    <h1>Formulario de examen</h1>
    <main>
        <form action="" method="POST">
            <section>
                <input type="text" style="display:none" id="codigoExamen" name="codigoExamen" <?php echo "value=\"".$idExamen."\"" ?> />
                <label for="enunciado">Enunciado: </label><input type="text" id="enunciado" name="enunciado" />
                <label for="n_preg">Nº preguntas: </label><input type="number" id="n_preg" name="n_preg" />
                <label for="duracion">Duración: </label><input type="number" id="duracion" name="duracion" />
            </section>
            <section>
                <article>
                    <input type="text" id="filtroBancoPreguntas" /><input type="button" value="filtrar" id="botonFiltroBancoPreguntas" />
                    <input type="text" id="filtroPreguntasSeleccionadas" /><input type="button" value="Filtrar" id="botonFiltroPreguntasSeleccionadas" />
                </article>
                <article>
                    <div id="bancoPreguntas"></div>
                    <div id="preguntasSeleccionadas"></div>
                </article>
                <article style="clear:both">
                    <input style="clear:both" type="submit" id="botonEnviar" name="botonEnviar" value="Enviar" />
                </article>
            </section>
        </form>
    </main>
<?php
    Pintor::footer();
?>