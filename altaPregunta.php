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
        $options = array();
        $options = BD::cogeTematicas();

        $modifiquino = false;
        $placeholder = "";

        if(isset($_GET["id"]))
        {
            $json_data = BD::getPregunta_y_Respuestas($_GET["id"]);
            if($json_data == '{"pregunta":false,"respuestas":[]}')
            {
                $modifiquino = false; // Se insertará porque venimos sin datos
                $json_data = file_get_contents("modelos/pregunta.json");
                $placeholder = json_decode($json_data);
                switch(json_last_error()) {
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
                $imgrecurso = "";
            }
            else
            {
                $placeholder = json_decode($json_data);
                $modifiquino = true;
                if($placeholder->pregunta->recurso != null)
                {
                    $imgrecurso = "data:image/jpeg;base64,".$placeholder->pregunta->recurso;
                }
                else
                {
                    $imgrecurso = "";
                }
                
            }
            // El botón es para modificar.
        }
        else
        {
            $json_data = file_get_contents("modelos/pregunta.json");
            $placeholder = json_decode($json_data);
            // El botón es para insertar.
            $imgrecurso = "";
        }
        $rc = $placeholder->pregunta->respuestaCorrecta;

        var_dump($placeholder);

        $errorcillos = array();
        $errorcillos["enunciado"] = "";
        $errorcillos["respuesta1"] = "";
        $errorcillos["respuesta2"] = "";
        $errorcillos["respuesta3"] = "";
        $errorcillos["respuesta4"] = "";
        $errorcillos["radioCorrecta"] = "";

        if(isset($_POST["botonEnviar"]))
        {
            var_dump($_POST);
            var_dump($_FILES);
            
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
                }
                else
                {
                    $recurso = null;
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

                $pregunta = new Pregunta($id, $enunciado, $tematica, $recurso);

                $arrayRespuestas = array();
                for ($i = 0; $i < $nRespuestas; $i++)
                {
                    $arrayRespuestas[$i] = new Respuesta(intval($idr[$i]), $respuestas[$i], $pregunta);
                }

                if($modifiquino == true)
                {
                    BD::modificaPregunta($pregunta, intval($_POST["radioCorrecta"]), $arrayRespuestas); // A ver como nos las apañamos   
                }
                else
                {
                    BD::insertaPregunta($pregunta, intval($_POST["radioCorrecta"]), $arrayRespuestas);
                }
            }
            else
            {
                $errorcillos["enunciado"] = $validador->imprimeError("enunciado");
                $errorcillos["respuesta1"] = $validador->imprimeError("respuesta1");
                $errorcillos["respuesta2"] = $validador->imprimeError("respuesta2");
                $errorcillos["respuesta3"] = $validador->imprimeError("respuesta3");
                $errorcillos["respuesta4"] = $validador->imprimeError("respuesta4");
                $errorcillos["radioCorrecta"] = $validador->imprimeError("radioCorrecta");
            }
        }
    };
    if($modifiquino == true)
    {
        Pintor::header("Editar pregunta","js/altaPregunta.js");
    }
    else
    {
        Pintor::header("Crear pregunta","js/altaPregunta.js");
    }
    Pintor::nav_admin();

?>
    <main>
        <style>
            table { width: 100%; }
            .invisible {
                border-color: green;
            }
            img {
                max-height: 400px;
            }
        </style>
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
                <label for="enunciado">Enunciado</label><input type="text" id="enunciado" name="enunciado" value="<?php echo $placeholder->pregunta->enunciado; ?>" /><?php echo $errorcillos["enunciado"]; ?>
                <label for="recurso">Imagen/Vídeo:</label><img src="<?php echo $imgrecurso; ?>" id="imagen" /><input type="file" id="recurso" alt="Recurso de imagen o vídeo" name="recurso" />
            </section>
            <section>
                <table class="invisible">
                    <tr>
                        <td>
                        <?php echo $errorcillos["respuesta1"]; ?><label for="respuesta1">Opción 1</label><input type="text" name="respuesta1" value="<?php echo $placeholder->respuestas[0]->enunciado; ?>" /></td>
                        <td><input type="radio" value="1" id="radio1" name="radioCorrecta" <?php if($rc==$placeholder->respuestas[0]->id) echo "checked" ?>/>Respuesta correcta</td>
                    </tr>
                    <tr>
                        <td>
                        <?php echo $errorcillos["respuesta2"]; ?><label for="respuesta2">Opción 2</label><input type="text" name="respuesta2" value="<?php echo $placeholder->respuestas[1]->enunciado; ?>" /></td>
                        <td><input type="radio" value="2" id="radio2" name="radioCorrecta" <?php if($rc==$placeholder->respuestas[1]->id) echo "checked" ?>/>Respuesta correcta</td>
                    </tr>
                    <tr>
                        <td>
                        <?php echo $errorcillos["respuesta3"]; ?><label for="respuesta3">Opción 2</label><input type="text" name="respuesta3" value="<?php echo $placeholder->respuestas[2]->enunciado; ?>" /></td>
                        <td><input type="radio" value="3" id="radio3" name="radioCorrecta" <?php if($rc==$placeholder->respuestas[2]->id) echo "checked" ?>/>Respuesta correcta</td>
                    </tr>
                    <tr>
                        <td>
                        <?php echo $errorcillos["respuesta4"]; ?><label for="respuesta4">Opción 2</label><input type="text" name="respuesta4" value="<?php echo $placeholder->respuestas[3]->enunciado; ?>" /></td>
                        <td><input type="radio" value="4" id="radio4" name="radioCorrecta" <?php if($rc==$placeholder->respuestas[3]->id) echo "checked" ?>/>Respuesta correcta</td>
                    </tr>
                    <?php echo $errorcillos["radioCorrecta"]; ?>
                </table>
                
                <input type="submit" id="botonEnviar" name="botonEnviar" value="Enviar" />
            </section>
        </form>
    </main>

<?php Pintor::footer(); ?>