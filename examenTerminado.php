<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    BD::conectar();
    Sesion::iniciar();

    $valoresJSON = BD::getJSON_examenRealizado($_GET["id"]);

    $valores = json_decode($valoresJSON);

    $sinMarcar = 0;
    $correctas = 0;
    $incorrectas = 0;

    $permiso = BD::accesoExamenRealizado($_GET["id"], Sesion::leer("usuario"));
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
    <div>Examen realizado con éxito.</div>
    <div>Estadísticas:</div>
    <div class="acierto" >Preguntas acertadas:<?php echo $correctas." (".(($correctas*100)/($valores->numPreguntas))."%)"; ?></div>
    <div class="error" >Preguntas falladas:<?php echo $incorrectas." (".(($incorrectas*100)/($valores->numPreguntas))."%)"; ?></div>
    <div class="sin_contestar" >Preguntas sin contestar:<?php echo $sinMarcar." (".(($sinMarcar*100)/($valores->numPreguntas))."%)"; ?></div>
    <div>Total:</div>

    <a href="#">Imprimir</a>
    <a href="historicoExamenes.php">Volver</a>
</main>
<?php
Pintor::footer();
?>