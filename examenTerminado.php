<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    BD::conectar();
    Sesion::iniciar();

    $id_examen_realizado = $_GET["id"];

    $valoresJSON = BD::getJSON_examenRealizado($id_examen_realizado);

    $valores = json_decode($valoresJSON);

    $sinMarcar = 0;
    $correctas = 0;
    $incorrectas = 0;

    $permiso = BD::accesoExamenRealizado($id_examen_realizado, Sesion::leer("usuario"));
    if($permiso == true || Sesion::leer("rol") == "Admin")
    {
        for ($i = 0; $i < $valores->numPreguntas; $i++)
        {
            if(isset($valores->preguntas[$i]->respuestaMarcada))
            {
                if($valores->preguntas[$i]->respuestaMarcada == $valores->preguntas[$i]->pregunta->respuestaCorrecta)
                {
                    $correctas++;
                }
                else
                {
                    $incorrectas++;
                }
            }
            else
            {
                $sinMarcar++;
            }
        }
    }

Pintor::header("Examen terminado");
?>
<main>
    <div class="mensaje_acierto">Examen realizado con éxito.</div>
    <div>Estadísticas:</div>
    <div class="acierto" >Preguntas acertadas:<?php echo $correctas." (".(($correctas*100)/($valores->numPreguntas))."%)"; ?></div>
    <div class="error" >Preguntas falladas:<?php echo $incorrectas." (".(($incorrectas*100)/($valores->numPreguntas))."%)"; ?></div>
    <div class="sin_contestar" >Preguntas sin contestar:<?php echo $sinMarcar." (".(($sinMarcar*100)/($valores->numPreguntas))."%)"; ?></div>
    <div>Total:</div>

    <a href="imprimirExamen.php?id=<?php echo $id_examen_realizado; ?>" >Imprimir</a>
    <a href="historicoExamenes.php">Volver</a>
</main>
<?php
Pintor::footer();
?>