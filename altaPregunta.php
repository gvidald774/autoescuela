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

        if(isset($_GET["id"]))
        {
            // Aquí ahora tendríamos que coger la pregunta de la Base de Datos.
            // Quizá aquí se podría usar el JSON?
        }

        if(isset($_POST["botonEnviar"]))
        {
            var_dump($_POST);
            var_dump($_FILES);
        }
    };
    if(isset($_GET["id"]))
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
                            echo "<option value=\"$id\">$descripcion</option>";
                        }
                    ?>
                </select>
                <label for="enunciado">Enunciado</label><input type="text" id="enunciado" name="enunciado" />
                <label for="recurso">Imagen/Vídeo:</label><img src="media/img/small-logo.jpg" id="imagen" /><input type="file" id="recurso" alt="Recurso de imagen o vídeo" name="recurso" />
            </section>
            <section>
                <table class="invisible">
                    <tr>
                        <td><label for="respuesta1">Opción 1</label><input type="text" value="A" name="respuesta1" /></td>
                        <td><input type="radio" value="radio1" id="radio1" name="radioCorrecta" />Respuesta correcta</td>
                    </tr>
                    <tr>
                        <td><label for="respuesta2">Opción 2</label><input type="text" value="B" name="respuesta2" /></td>
                        <td><input type="radio" value="radio2" id="radio2" name="radioCorrecta" />Respuesta correcta</td>
                    </tr>
                    <tr>
                        <td><label for="respuesta3">Opción 2</label><input type="text" value="B" name="respuesta3" /></td>
                        <td><input type="radio" value="radio3" id="radio3" name="radioCorrecta" />Respuesta correcta</td>
                    </tr>
                    <tr>
                        <td><label for="respuesta4">Opción 2</label><input type="text" value="B" name="respuesta4" /></td>
                        <td><input type="radio" value="radio4" id="radio4" name="radioCorrecta" />Respuesta correcta</td>
                    </tr>
                </table>
                <input type="submit" id="botonEnviar" name="botonEnviar" value="Enviar" />
            </section>
        </form>
    </main>

<?php Pintor::footer(); ?>