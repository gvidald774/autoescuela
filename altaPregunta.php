<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    // Es posible que el problema de tener que actualizar la página dos veces se arregle poniendo lo del POST por encima de lo del GET y usando variables que se apliquen en ambas partes. Pero bueno eso requeriría restructurar toda la página, y no queremos eso, ¿verdad?

    BD::conectar();
    Sesion::iniciar();

    if(!Sesion::existe("usuario"))
    {
        header("Location: login.php");
    }
    else if(Sesion::leer("rol") != "Admin") // Cambiar a "Profesor".
    {
        echo "<div; class='mensaje_error'>No tiene permiso para acceder a estos contenidos.</div>";
        header("Refresh: 5, URL=login.php"); // Cambiar porque te lleve a otra página.
    }
    else
    { 
        $options = array();
        $options = BD::cogeTematicas();

        $modifiquino = false;
        $placeholder = "";

        if(isset($_GET["id"]))
        {
            $json_data = json_encode(BD::getPregunta_y_Respuestas($_GET["id"]));
            if($json_data == '{"pregunta":false,"respuestas":[]}')
            {
                $modifiquino = false; // Se insertará porque venimos sin datos
                $json_data = file_get_contents("modelos/pregunta.json");
                $placeholder = json_decode($json_data);
                $recursoCaja = "";
            }
            else
            {
                $placeholder = json_decode($json_data);
                $modifiquino = true;
                if($placeholder->pregunta->recurso != null)
                {
                    if($placeholder->pregunta->type == "video/mp4" )
                    {
                        $recursoCaja = "data:video/mp4;base64,".$placeholder->pregunta->recurso;
                    }
                    else if($placeholder->pregunta->type = "image/jpeg")
                    {
                        $recursoCaja = "data:image/jpeg;base64,".$placeholder->pregunta->recurso;
                    }
                }
                else
                {
                    $recursoCaja = "";
                }
                
            }
            // El botón es para modificar.
        }
        else
        {
            $json_data = file_get_contents("modelos/pregunta.json");
            $placeholder = json_decode($json_data);
            // El botón es para insertar.
            $recursoCaja = "";
        }
        $rc = $placeholder->pregunta->respuestaCorrecta;

        $errorcillos = array();
        $errorcillos["enunciado"] = "";
        $errorcillos["respuesta1"] = "";
        $errorcillos["respuesta2"] = "";
        $errorcillos["respuesta3"] = "";
        $errorcillos["respuesta4"] = "";
        $errorcillos["radioCorrecta"] = "";

        if(isset($_POST["botonEnviar"]))
        {   
            $validador = new Validator();

            $validador->existe("enunciado");
            $nRespuestas = 4;
            for ($i = 1; $i <= $nRespuestas; $i++)
            {
                $string_respuesta = "respuesta".$i;
                $validador->existe($string_respuesta);
            }

            $validador->marcado("radioCorrecta");

            if($validador->correcto())
            {
                $enunciado = $_POST["enunciado"];
                $respuestas = array();
                for ($i = 0; $i < $nRespuestas; $i++)
                {
                    $respuestas[$i] = $_POST["respuesta".($i+1)];
                }

                if($_FILES["recurso"]["tmp_name"]!='')
                {
                    $img = "";
                    $img = file_get_contents($_FILES["recurso"]["tmp_name"]);
                    $img = base64_encode($img);
                    $recurso = $img;
                    $tipo = $_FILES["recurso"]["type"];
                }
                else
                {
                    $recurso = $placeholder->pregunta->recurso;
                    $tipo = $placeholder->pregunta->tipo;
                }

                $tematica = $_POST["opciones_tematica"];

                if($placeholder->pregunta->id != null)
                {
                    $id = $_GET["id"];
                    $idr = array();
                    for ($i = 0; $i < count($placeholder->respuestas); $i++)
                    {
                        $idr[$i] = $placeholder->respuestas[$i]->id;
                    }
                }
                else
                {
                    $id = BD::cogeUltimoId("pregunta")+1;
                    $idr = array();
                    $idr[0] = BD::cogeUltimoId("respuesta")+1;
                    for ($i = 1; $i <= 3; $i++)
                    {
                        $idr[$i] = $idr[0]+$i;
                    }
                }

                $pregunta = new Pregunta($id, $enunciado, $tematica, $recurso, $tipo);

                $arrayRespuestas = array();
                for ($i = 0; $i < $nRespuestas; $i++)
                {
                    $arrayRespuestas[$i] = new Respuesta(intval($idr[$i]), $respuestas[$i], $pregunta);
                }
                
                if($modifiquino == true)
                {
                    BD::modificaPregunta($pregunta, intval($_POST["radioCorrecta"]), $arrayRespuestas); // A ver como nos las apañamos
                    header("Location: ".$_SERVER['REQUEST_URI']);
                }
                else
                {
                    BD::insertaPregunta($pregunta, intval($_POST["radioCorrecta"]), $arrayRespuestas);
                    header("Location: ".$_SERVER['REQUEST_URI']);
                }
            }
            else
            {
                $errorcillos["enunciado"] = "<div class='mensaje_error'>".$validador->imprimeError("enunciado")."</div>";
                $errorcillos["respuesta1"] = "<div class='mensaje_error'>".$validador->imprimeError("respuesta1")."</div>";
                $errorcillos["respuesta2"] = "<div class='mensaje_error'>".$validador->imprimeError("respuesta2")."</div>";
                $errorcillos["respuesta3"] = "<div class='mensaje_error'>".$validador->imprimeError("respuesta3")."</div>";
                $errorcillos["respuesta4"] = "<div class='mensaje_error'>".$validador->imprimeError("respuesta4")."</div>";
                $errorcillos["radioCorrecta"] = "<div class='mensaje_error'>".$validador->imprimeError("radioCorrecta")."</div>";
            }
        }
    };
    if($modifiquino == true)
    {
        Pintor::header("Editar pregunta",["js/altaPregunta.js"]);
    }
    else
    {
        Pintor::header("Crear pregunta",["js/altaPregunta.js"]);
    }
    Pintor::nav_admin();

?>
    <main>
        <h1>Pregunta</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <section>
                <label for="opciones_tematica">Temática</label>
                <select id="opciones_tematica" name="opciones_tematica">Temática:
                    <?php
                        for($i = 0; $i < count($options); $i++)
                        {
                            $id = $options[$i]->id;
                            $descripcion = $options[$i]->Nombre;
                            if ($id == $placeholder->pregunta->tematica)
                            {
                                echo "<option value=\"$id\" selected>$descripcion</option>";
                            }
                            else
                            {
                                echo "<option value=\"$id\">$descripcion</option>";
                            }
                        }
                    ?>
                </select>
                <label for="enunciado">Enunciado</label><input type="text" id="enunciado" name="enunciado" value="<?php echo $placeholder->pregunta->enunciado; ?>" required /><?php echo $errorcillos["enunciado"]; ?>
                <label for="recurso">Imagen/Vídeo:</label>
                <div id="caja_recurso">
                <?php
                    if($placeholder->pregunta->type == "video/mp4")
                    {
                        echo "<video id='recursoCaja' controls>
                                <source src='".$recursoCaja."' >
                            </video>"
                            ;
                    }
                    else if($recursoCaja=="")
                    {
                    }
                    else
                    {
                        echo "<img id='recursoCaja' src='".$recursoCaja."' />";
                    }
                ?>
                </div>
                <input type="file" id="recurso" name="recurso" />
            </section>
            <section>
                <table class="invisible">
                    <tr>
                        <td>
                        <?php echo $errorcillos["respuesta1"]; ?><label for="respuesta1">Opción 1</label><input type="text" name="respuesta1" value="<?php echo $placeholder->respuestas[0]->enunciado; ?>" required /></td>
                        <td><input type="radio" value="1" id="radio1" name="radioCorrecta" <?php if($rc==$placeholder->respuestas[0]->id) echo "checked" ?>/>Respuesta correcta</td>
                    </tr>
                    <tr>
                        <td>
                        <?php echo $errorcillos["respuesta2"]; ?><label for="respuesta2">Opción 2</label><input type="text" name="respuesta2" value="<?php echo $placeholder->respuestas[1]->enunciado; ?>"required /></td>
                        <td><input type="radio" value="2" id="radio2" name="radioCorrecta" <?php if($rc==$placeholder->respuestas[1]->id) echo "checked" ?>/>Respuesta correcta</td>
                    </tr>
                    <tr>
                        <td>
                        <?php echo $errorcillos["respuesta3"]; ?><label for="respuesta3">Opción 3</label><input type="text" name="respuesta3" value="<?php echo $placeholder->respuestas[2]->enunciado; ?>" required /></td>
                        <td><input type="radio" value="3" id="radio3" name="radioCorrecta" <?php if($rc==$placeholder->respuestas[2]->id) echo "checked" ?>/>Respuesta correcta</td>
                    </tr>
                    <tr>
                        <td>
                        <?php echo $errorcillos["respuesta4"]; ?><label for="respuesta4">Opción 4</label><input type="text" name="respuesta4" value="<?php echo $placeholder->respuestas[3]->enunciado; ?>" required /></td>
                        <td><input type="radio" value="4" id="radio4" name="radioCorrecta" <?php if($rc==$placeholder->respuestas[3]->id) echo "checked" ?>/>Respuesta correcta</td>
                    </tr>
                    <?php echo $errorcillos["radioCorrecta"]; ?>
                </table>
                
                <input type="submit" id="botonEnviar" name="botonEnviar" value="Enviar" />
            </section>
        </form>
    </main>

<?php Pintor::footer(); ?>