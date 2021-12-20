<?php
    use Dompdf\Dompdf;
    require "vendor/autoload.php";
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

    Sesion::iniciar();
    BD::conectar();

    function respuestaCorrecta($respuestaCorrecta, $respuestaMarcada)
    {
        if($respuestaCorrecta == $respuestaMarcada)
        {
            return true;
        }
        else return false;
    }

    // Tengo que coger el examen para imprimirlo y tal y pascual.
    $valoresJSON = BD::getJSON_examenRealizado($_GET["id"]);

    $valores = json_decode($valoresJSON);

    $html = '<style>
    .enunciado_pregunta { font-weight: bolder; }
    </style>
    <h2 id="titulo_examen">'.$valores->enunciado.'</h2>
    <form action="" method="POST" id="examen">
        <section id="seccion_preguntas_examen">';

    for($i = 0; $i < count($valores->preguntas); $i++)
    {
        $respuesta_marcada = $valores->preguntas[$i]->respuestaMarcada;
        $html .= '
            <article id="pregunta_'.$valores->preguntas[$i]->pregunta->id.'">
                <div class="izquierdo">
                    <article class="izquierdo">
                        <article class="enunciado_pregunta">'.$valores->preguntas[$i]->pregunta->enunciado.'</article>
                        <article>';
        for($j = 0; $j < count($valores->preguntas[$i]->respuestas); $j++)
        {
            if(respuestaCorrecta($respuesta_marcada, $valores->preguntas[$i]->respuestas[$j]->id))
            {
                $checked = 'checked=checked';
            }
            else
            {
                $checked = '';
            }
            $html .= '
                            <label for="respuesta_'.$valores->preguntas[$i]->respuestas[$j]->id.'">
                                <input type="radio" name="respuestas_'.$valores->preguntas[$i]->respuestas[$j]->idPregunta.'" id="respuesta_'.$valores->preguntas[$i]->respuestas[$j]->id.'" '.$checked.' disabled="">'.$valores->preguntas[$i]->respuestas[$j]->enunciado.'';

            if((respuestaCorrecta($valores->preguntas[$i]->pregunta->respuestaCorrecta, $respuesta_marcada) && respuestaCorrecta($valores->preguntas[$i]->respuestas[$j]->id, $respuesta_marcada))|| respuestaCorrecta($valores->preguntas[$i]->pregunta->respuestaCorrecta, $valores->preguntas[$i]->respuestas[$j]->id))
            {
                $html .= '
                                <span class="textoCorrecta"> Respuesta correcta </span>
                ';
            }
            else if($respuesta_marcada == null)
            {

            }
            else if(!respuestaCorrecta($valores->preguntas[$i]->pregunta->respuestaCorrecta, $respuesta_marcada) && respuestaCorrecta($valores->preguntas[$i]->respuestas[$j]->id, $respuesta_marcada))
            {
                $html .= '
                                <span class="textoIncorrecta"> Respuesta incorrecta </span>
                ';
            }

            $html .= '
                            </label>
                            <br>
            ';
        }
        $html .= '
                        </article>
                    </div>
                </article>
                <br>
        ';
    }
    $html .= '
        </section>
    </form>
    ';

    /* $html = '
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Examen Impreso en PDF</title>
    </head>
    <body>
        <section id="cabecera_examen">
            <h1>'.$valores->enunciado.'</h1>
        </section>
        <form>
            <section id="seccion_preguntas_examen">';
    for($i = 0; $i < count($valores->preguntas); $i++)
    {
        $html .= '
                <article id="pregunta_'.$valores->preguntas[$i]->pregunta->id.'" class="pregunta????">
                    <div class="izquierdo">
                        <article class="enunciado_pregunta"><strong>'.$valores->preguntas[$i]->pregunta->enunciado.'</strong></article>
                        <article>';
        for($j = 0; $j < 4; $j++)
        {
            $html .= '<input type="radio"/>'.$valores->preguntas[$i]->respuestas[$j]->enunciado.'<br>';
        }

        $html .= '</article></div></article>';

    }

    $html .= '
            </section>
        </form>
    </body>
    </html>
    '; */

    $mipdf = new Dompdf();
    # Definimos el tamaño y orientación del papel que queremos.
    # O por defecto cogerá el que está en el fichero de configuración.
    $mipdf->set_paper("A4","portrait");
    # Cargamos el contenido HTML
    $mipdf->load_html($html);

    # Renderizamos el documento PDF.
    $mipdf->render();

    # Creamos un fichero
    $pdf = $mipdf->output();
    $filename = "ExamenPDF.pdf";
    file_put_contents($filename, $pdf);

    # Enviamos el fichero PDF al navegador.
    $mipdf->stream($filename);

?>