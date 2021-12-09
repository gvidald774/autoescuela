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
        if(isset($_POST["botonEnviar"]))
        {
            var_dump($_POST);
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea examen</title>
    <script src="js/crea_examen.js"></script>
    <link rel="stylesheet" href="css/main.css" />
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
            height: 50px;
            border: 1px solid;
            background-color: maroon;
            overflow: auto;
            margin: 10px;
            box-shadow: 5px 5px 5px black;
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
    <nav>
        <ul>
            <li class="dropdown">
                <a href="tablaUsuarios.php" class="dropbtn">Usuarios</a>
                <div class="dropdown-content">
                    <a href="alta_usuario.php">Alta de usuario</a>
                    <a href="alta_masiva.php">Alta masiva</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="tablaTematicas.php" class="dropbtn">Temáticas</a>
                <div class="dropdown-content">
                    <a href="altaTematica.php">Alta temática</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="tablaPreguntas.php" class="dropbtn">Preguntas</a>
                <div class="dropdown-content">
                    <a href="altaPregunta.php">Alta pregunta</a>
                    <a href="alta_masiva_preguntas.php">Alta masiva</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="tablaExamenes.php" class="dropbtn">Exámenes</a>
                <div class="dropdown-content">
                    <a href="creaExamen.php">Alta examen</a>
                    <a href="historicoExamenes.php">Histórico</a>
                </div>
            </li>
        </ul>
    </nav>
    <h1>Formulario de examen</h1>
    <main>
        <form action="" method="POST">
            <section>
                <input type="text" style="display:none" id="codigoExamen" name="codigoExamen" />
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
    <footer></footer>
</body>
</html>
<?php
    }
?>