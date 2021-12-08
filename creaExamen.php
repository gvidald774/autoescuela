<?php
    require_once("include/cargadores/carga_entities.php");
    require_once("include/cargadores/carga_helpers.php");

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
    // Aquí definitivamente hace falta el botón.
?>
<!--
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creación y modificación de exámenes
    </title>
    <style>
        select {
            width: 150px;
            height: 250px;
        }
        .pregunta {
            margin: 10px;
            overflow: auto;
            background-color: maroon;
            box-shadow: 5px 5px 5px black;
        }
        div#bancoPreguntas, div#preguntasSeleccionadas {
            margin: 10px;
            overflow: auto;
            background-color: palegoldenrod;
            box-shadow: 5px 5px 5px black;
            display: block;
            position: relative;
            width: 40%;
            min-height: 200px;
        }
        div#bancoPreguntas {
            float: left;
        }
        div#preguntasSeleccionadas {
            float: right;
        }
        .pregunta-tema {
            color: gray;
            font-size: small;
            text-align: right;
            display: block;
        }
        .pregunta-enunciado {
            color: black;
            font-size: large;
            font-family: sans-serif;
            display: block;
        }
        .oculto {
            display: none;
        }
    </style>
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuck</title>
    <script src="js/crea_examen.js"></script>
    <link rel="stylesheet" href="css/main.css" />
    <style>
        #bancoPreguntas {
            position: relative;
            width: 40%;
            float: left;
            min-height: 200px;
            background-color: aqua;
        }
        #preguntasSeleccionadas {
            position: relative;
            width: 40%;
            float: right;
            min-height: 200px;
            background-color: aqua;
        }
        #preguntasSeleccionadas, #bancoPreguntas {
            margin: 10px;
            overflow: auto;
            background-color: palegoldenrod;
            box-shadow: 5px 5px 5px black;
            display: block;
            position: relative;
            width: 40%;
            min-height: 200px;
        }
        div.pregunta {
            position: relative;
            height: 50px;
            border: 1px solid;
            background-color: maroon;
            overflow: auto;
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
</head>
<body>
    <header></header>
    <h1>Formulario de examen</h1>
    <main>
        <section>
            <input type="text" style="display:none" id="codigoExamen" name="codigoExamen" />
            <label for="enunciado">Enunciado: </label><input type="text" id="enunciado" name="enunciado" />
            <label for="n_preg">Nº preguntas: </label><input type="number" id="n_preg" name="n_preg" />
            <label for="duracion">Duración: </label><input type="number" id="duracion" name="duracion" />
        </section>
        <section>
            <article>
                <input type="text" id="filtro" /><input type="button" value="Filtrar" id="botonFiltro" />
            </article>
            <article>
                <div id="bancoPreguntas"></div>
                <div id="preguntasSeleccionadas"></div>
            </article>
        </section>
    </main>
    <footer></footer>
</body>
</html>
<?php
    }
?>